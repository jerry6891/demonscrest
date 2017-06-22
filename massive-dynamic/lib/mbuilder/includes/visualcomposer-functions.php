<?php
// Check functions called from backend or not
function pixflow_called_from_backend(){
    if(is_customize_preview() == false && !isset($_POST['mbuilder_editor']) && !isset($_GET['mbuilder']) && is_admin()){
        return true;
    }else{
        return false;
    }
}
function pixflow_called_from_mbuilder(){
    if((is_customize_preview() == true || isset($_POST['mbuilder_editor']))){
        return true;
    }else{
        return false;
    }
}
/**
 * Make Builder compatible with visual composer
 *
 * @global array $mBuilderModelIdArray Models array for mBuilder
 *
 * @param string $content Content to search for shortcodes.
 * @param bool $autop When true, <p> tags will be replace by \n
 *
 * @return string Content with shortcodes rendered.
 * @since 1.0.0
 */
function pixflow_js_remove_wpautop($content, $autop = false){
    if(pixflow_called_from_backend() && function_exists('wpb_js_remove_wpautop')){
        return wpb_js_remove_wpautop($content, $autop);
    }
    global $mBuilderModelIdArray,$in_mbuilder;
    if($mBuilderModelIdArray===null){
        $mBuilderModelIdArray = array();
    }
    if ($autop) {
        $content = wpautop(preg_replace('/<\/?p\>/', "\n", $content) . "\n");
    }

    $shortcode = $content;//shortcode_unautop($content);
    if ($in_mbuilder) {
        $pat = "~\[[^\/][^=]*?( .*?)*?\]~s";
        if(preg_match_all($pat, $shortcode, $mats)){
            $els = $mats[0];
            $dels = array_count_values($els);
            foreach($dels as $el=>$c){
                if($c>1){
                    $pat = '~'.preg_quote($el,'/')."~s";
                    for($i=0;$i<$c;$i++){
                        $replace = trim($el);
                        $replace = str_replace(']'," el_id='".uniqid()."']",$replace);
                        $shortcode = preg_replace($pat,$replace, $shortcode,1);
                    }
                }
            }
        }
        $pattern = get_shortcode_regex();
        $matches = array();
        if (preg_match_all('/' . $pattern . '/s', $shortcode, $matches)
            && array_key_exists(2, $matches)
        ) {
            if (count($matches[0])) {
                foreach ($matches[0] as $key => $match) {
                    $el = $matches[2][$key];
                    $inArray = true;
                    while($inArray) {
                        $id = count($mBuilderModelIdArray)+1;
                        if(!array_key_exists($id,$mBuilderModelIdArray)){
                            $content = $matches[5][$key];
                            if(preg_match('~^\[.*?\]~s',$content)){
                                $content = '';
                            }else {
                                $content = preg_replace('~(\[.*?\](.*?\[.*?\])?)~s', '', $content);
                            }
                            $matches[3][$key] = str_replace('``','"',$matches[3][$key]);
                            $mBuilderModelIdArray[$id] = array('attr'=>$matches[3][$key],'content'=>$content,'type'=>$el);

                            $inArray = false;
                        }
                    }
                    if(preg_match('/mbuilder-id=\\\?["\'](.*?)\\\?["\']/s',$matches[3][$key],$ids)){
                        $id = $ids[1];
                    }
                    $el_classes = '';
                    if('md_text' == $el){
                        $title = MBuilder::getModelAttribute($match,'md_text_title1');
                        if($title!==false){
                            $title = strip_tags($title);
                            $el_classes = ($title == '')?' no-title':'';
                        }
                        $text = strip_tags($content);
                        if($text == '') {
                            $el_classes .= ' no-text' ;
                        }
                    }

                    if($el == 'vc_column' || $el == 'vc_column_inner'){
                        $width = MBuilder::getModelAttribute($match,'width');
                        $width = ($width && $width != '')?$width:'1/1';
                        $width = explode('/',$width);
                        $width = $width[0] / $width[1] * 12;
                        $el_classes .= "col-sm-$width";
                    }
                    $shortcode = str_replace($match, "<div class='mBuilder-element mBuilder-$el vc_$el $el_classes' data-mBuilder-el='$el' data-mBuilder-id='$id'>" . $match . "</div>", $shortcode);

                }
            }
        }
        $mBuilderModels = array(
            'models' => $mBuilderModelIdArray,
        );
        wp_localize_script('mBuilder', 'mBuilderModels', $mBuilderModels);

    }

    return do_shortcode($shortcode);
}

if (!class_exists('WPBakeryVisualComposerAbstract')) {
    // Make it compatible with Visual Composer
    define('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'mBuilderCssClass');
}
function pixflow_widget_title($params = array( 'title' => '' )){
    if(pixflow_called_from_backend() && function_exists('wpb_widget_title')){
        return wpb_widget_title($params);
    }
    return null;
}

function pixflow_remove_element($shortcode){
    if(pixflow_called_from_backend() && function_exists('vc_remove_element')){
        return vc_remove_element($shortcode);
    }
}

function pixflow_remove_param($name = '', $attribute_name = ''){
    if(pixflow_called_from_backend() && function_exists('vc_remove_param')){
        return vc_remove_param($name,$attribute_name);
    }
}

function pixflow_add_param($key, $arr){
    if(pixflow_called_from_backend() && function_exists('vc_add_param')){
        return vc_add_param($key, $arr);
    }
    global $mBuilderShortcodes;

    $mBuilderShortcodes[$key]['params'][] = $arr;

}

function pixflow_add_params($key, $arr){
    if(pixflow_called_from_backend() && function_exists('vc_add_params')){
        return vc_add_params($key, $arr);
    }
    global $mBuilderShortcodes;
    $mBuilderShortcodes[$key]['params'] = array_merge($mBuilderShortcodes[$key]['params'],$arr);
}

function pixflow_map_update($key, $arr){
    if(pixflow_called_from_backend() && function_exists('vc_map_update')){
        return vc_map_update($key, $arr);
    }
    global $mBuilderShortcodes;
    foreach($arr as $k=>$value) {
        $mBuilderShortcodes[$key][$k] = $value;
    }
    $mBuilderShortcodes[$key]['base'] = $key;
}

function pixflow_path_dir($d, $f){
    if(pixflow_called_from_backend() && function_exists('vc_path_dir')){
        return vc_path_dir($d, $f);
    }
    return __FILE__;
}



function pixflow_add_shortcode_param($name, $callback, $requiredjs = ''){
    if(pixflow_called_from_backend() && function_exists('vc_add_shortcode_param')){
        return vc_add_shortcode_param($name, $callback, $requiredjs);
    }
    global $mBuilderExternalTypes;
    $mBuilderExternalTypes[$name]['callback'] = $callback;
    $mBuilderExternalTypes[$name]['requiredjs'] = $requiredjs;
}

function pixflow_map($arr){
    if(pixflow_called_from_backend() && function_exists('vc_map')){
        return vc_map($arr);
    }
    global $mBuilderShortcodes;
    $mBuilderShortcodes[$arr['base']] = $arr;
    foreach($arr['params'] as $param){
        if($param['param_name'] == 'content'){
            $mBuilderShortcodes[$arr['base']]['default_content'] = $param['value'];
        }
    }
}

/**
 * Add vc_column shortcode panel to the mBuidler
 *
 * @global array $mBuidlerShortcodes
 *
 * @return void
 * @since 1.0.0
 */
function mBuilderVcColumn(){
    global $mBuilderShortcodes;
    $separatorCounter = 0;
    $params = array();
    $params[] = array(
        "type"             => "textfield",
        "edit_field_class" => "first glue last",
        "heading"          => esc_attr__("Extra class name", 'massive-dynamic'),
        "param_name"       => "el_class",
        "value"            => '',
        'group'            => esc_attr__("Design", 'massive-dynamic'),
        "admin_label"      => false,
    );
    $params[] = array(
        "type"             => "hidden",
        "edit_field_class" => "",
        "heading"          => esc_attr__("width", 'massive-dynamic'),
        "param_name"       => "width",
        "value"            => '',
        'group'            => esc_attr__("Design", 'massive-dynamic'),
        "admin_label"      => false,
    );
    $designOptions = array('margin'=>array('top','right','bottom','left'),'padding'=>array('top','right','bottom','left'),'border'=>array('top','right','bottom','left'));
    $showBorderRelated = false;
    foreach($designOptions as $key=>$option){
        $g = 0;
        foreach($option as $filed){
            $param_name = $key.'_'.$filed;
            if($key=='border'){
                $param_name.='_width';
                $min = "0";
                $max = "50";
                if($showBorderRelated == false){
                    $showBorderRelated = true;
                    $params[] = array(
                        "type"             => "md_vc_colorpicker",
                        "edit_field_class" => "first glue".' column-design-css',
                        "heading"          => esc_attr__("Border Color", 'massive-dynamic'),
                        "param_name"       => "border_color",
                        'group'            => esc_attr__("Design", 'massive-dynamic'),
                        "opacity"	       => true,
                        "admin_label"      => false,
                        "value"            => "rgba(0,0,0,1)",
                    );
                    $params[] = array(
                        "type"       => 'md_vc_separator',
                        'group'      => esc_attr__("Design", 'massive-dynamic'),
                        "param_name" => "col_separator".++$separatorCounter,
                    );
                    $params[] = array(
                        "type" => "dropdown",
                        "edit_field_class" => "glue last".' column-design-css',
                        "heading" => esc_attr__("Border Style", 'massive-dynamic'),
                        "param_name" => "border_style",
                        "admin_label" => false,
                        'group'            => esc_attr__("Design", 'massive-dynamic'),
                        "value" => array(
                            esc_attr__('solid','massive-dynamic') => 'solid',
                            esc_attr__('dotted','massive-dynamic') => 'dotted',
                            esc_attr__('dashed','massive-dynamic') => 'dashed',
                            esc_attr__('none','massive-dynamic') => 'none',
                            esc_attr__('hidden','massive-dynamic') => 'hidden',
                            esc_attr__('double','massive-dynamic') => 'double',
                            esc_attr__('groove','massive-dynamic') => 'groove',
                            esc_attr__('ridge','massive-dynamic') => 'ridge',
                            esc_attr__('inset','massive-dynamic') => 'inset',
                            esc_attr__('outset','massive-dynamic') => 'outset',
                            esc_attr__('initial','massive-dynamic') => 'initial',
                            esc_attr__('inherit','massive-dynamic') => 'inherit',
                        )
                    );
                }
            }else{
                if($key=='padding'){
                    $min = "0";
                    $max = "500";
                }else{
                    $min = "-500";
                    $max = "500";
                }
            }
            $heading = ucfirst($key).' '.ucfirst($filed);
            $edit_field_class = ($g == 0)?'first glue':'glue';
            $edit_field_class = ($g == 3)?$edit_field_class.' last':$edit_field_class;
            if($param_name=='padding_top') {
                $params[] = array(
                    "type" => "md_vc_slider",
                    "edit_field_class" => $edit_field_class . ' column-design-css column-design-prefix-px',
                    'group' => esc_attr__("Spacing", 'massive-dynamic'),
                    "heading" => $heading,
                    "param_name" => $param_name,
                    "value" => 35,
                    'defaultSetting' => array(
                        "min" => $min ,
                        "max" => $max,
                        "prefix" => "px",
                        "step" => '1',
                    ),
                    "admin_label" => false,
                );
            }else{
                $params[] = array(
                    "type" => "md_vc_slider",
                    "edit_field_class" => $edit_field_class . ' column-design-css column-design-prefix-px',
                    'group' => esc_attr__("Spacing", 'massive-dynamic'),
                    "heading" => $heading,
                    "param_name" => $param_name,
                    "value" => '',
                    'defaultSetting' => array(
                        "min" => $min ,
                        "max" => $max,
                        "prefix" => "px",
                        "step" => '1',
                    ),
                    "admin_label" => false,
                );
            }
            if($g != 3){
                $params[] = array(
                    "type"       => 'md_vc_separator',
                    'group'      => esc_attr__("Spacing", 'massive-dynamic'),
                    "param_name" => "col_separator".++$separatorCounter,
                );
            }
            $g++;
        }
    }
    $params[] = array(
        "type"             => "md_vc_colorpicker",
        "edit_field_class" => "first glue".' column-design-css',
        "heading"          => esc_attr__("Background Color", 'massive-dynamic'),
        "param_name"       => "background_color",
        'group'            => esc_attr__("Design", 'massive-dynamic'),
        "opacity"	       => true,
        "admin_label"      => false,
        "value"            => "rgba(0,0,0,0)",
    );
    $params[] = array(
        "type"       => 'md_vc_separator',
        'group'      => esc_attr__("Design", 'massive-dynamic'),
        "param_name" => "col_separator".++$separatorCounter,
    );
    $params[] = array(
        'type'             => 'attach_image',
        'edit_field_class' => "glue".' column-design-css',
        'heading'          => esc_attr__( 'Background Image', 'massive-dynamic' ),
        'param_name'       => 'background_image',
        'group'            => esc_attr__("Design", 'massive-dynamic'),
        "value"            => "",
    );
    $params[] = array(
        "type"       => 'md_vc_separator',
        'group'      => esc_attr__("Design", 'massive-dynamic'),
        "param_name" => "col_separator".++$separatorCounter,
    );
    $params[] = array(
        "type" => "dropdown",
        "edit_field_class" => "glue last".' column-design-css',
        "heading" => esc_attr__("Background Style", 'massive-dynamic'),
        "param_name" => "background_size",
        "admin_label" => false,
        'group'            => esc_attr__("Design", 'massive-dynamic'),
        "value" => array(
            esc_attr__('Theme defaults','massive-dynamic') => '',
            esc_attr__('Cover','massive-dynamic') => 'cover',
            esc_attr__('Contain','massive-dynamic') => 'contain',
            esc_attr__('No Repeat','massive-dynamic') => 'no-repeat',
            esc_attr__('Repeat','massive-dynamic') => 'repeat'
        )
    );

    // Column Responsive
    $params[] = array(
        "type" => "md_vc_checkbox",
        "edit_field_class" => "first glue last".' column-design-css',
        "heading" => esc_attr__("Laptop Visibility", 'massive-dynamic'),
        "param_name" => "md_laptop_visibility",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'yes'),
        'checked' => true,
    );

    $params[] = array(
        "type" => "md_vc_checkbox",
        "edit_field_class" => "first glue".' column-design-css',
        "heading" => esc_attr__("Tablet Portrait Visibility", 'massive-dynamic'),
        "param_name" => "md_tablet_portrait_visibility",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'yes'),
        'checked' => true,
    );

    $params[] = array(
        "type"       => 'md_vc_separator',
        'group'      => esc_attr__("Responsive", 'massive-dynamic'),
        "param_name" => "col_separator".++$separatorCounter,
    );

    $params[] = array(
        "type" => "md_vc_checkbox",
        "edit_field_class" => "glue last".' column-design-css',
        "heading" => esc_attr__("Tablet Landscape Visibility", 'massive-dynamic'),
        "param_name" => "md_tablet_landscape_visibility",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'yes'),
        'checked' => true,
    );

    $params[] = array(
        "type" => "md_vc_checkbox",
        "edit_field_class" => "first glue".' column-design-css',
        "heading" => esc_attr__("Mobile Portrait Visibility", 'massive-dynamic'),
        "param_name" => "md_mobile_portrait_visibility",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'yes'),
        'checked' => true,
    );

    $params[] = array(
        "type"       => 'md_vc_separator',
        'group'      => esc_attr__("Responsive", 'massive-dynamic'),
        "param_name" => "col_separator".++$separatorCounter,
    );

    $params[] = array(
        "type" => "md_vc_checkbox",
        "edit_field_class" => "glue last".' column-design-css',
        "heading" => esc_attr__("Mobile Landscape Visibility", 'massive-dynamic'),
        "param_name" => "md_mobile_landscape_visibility",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'yes'),
        'checked' => true,
    );

    $params[] = array(
        "type" => "dropdown",
        "edit_field_class" => "glue first last".' column-design-css',
        "heading" => esc_attr__("Tablet Portrait Width", 'massive-dynamic'),
        "param_name" => "md_tablet_portrait_width",
        'group'            => esc_attr__("Responsive", 'massive-dynamic'),
        'value' => array(
            esc_attr__('Select', 'massive-dynamic') => '0',
            esc_attr__('12/12(Full)', 'massive-dynamic') => '12',
            esc_attr__('6/12(Half
            )', 'massive-dynamic') => '6',
        )
    );



    $mBuilderShortcodes['vc_column'] = array(
        'name'=>'Column',
        'params'=> $params,
        'display'=>'none'
    );
    $mBuilderShortcodes['vc_column_inner'] = array(
        'name'=>'Column',
        'params'=> $params,
        'display'=>'none'
    );
    mBuilderPrerequisits();
}
add_action('mBuilder_shortcodes_init', 'mBuilderVcColumn',999);

/**
 * Add controllers
 *
 * @return void
 * @since 1.0.0
 */
function pixflow_add_custom_fields() {
    // add icon picker field to vc
    pixflow_add_shortcode_param('md_vc_slider', 'pixflow_vc_slider_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_url', 'pixflow_vc_url_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_multiselect', 'pixflow_vc_multiselect_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_checkbox', 'pixflow_vc_checkbox_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_description', 'pixflow_vc_description_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_separator', 'pixflow_vc_separator_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_gradientcolorpicker', 'pixflow_vc_gradientcolorpicker_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_base64_text', 'pixflow_vc_base64_text_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_base64_textarea', 'pixflow_vc_base64_textarea_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_colorpicker', 'pixflow_vc_colorpicker_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_iconpicker', 'pixflow_vc_iconpicker_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_datepicker', 'pixflow_vc_datepicker_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );
    pixflow_add_shortcode_param('md_vc_spacing', 'pixflow_vc_spacing_field', PIXFLOW_THEME_LIB_URI . '/extendvc/js/all.min.js' );

}
add_action( 'admin_init', 'pixflow_add_custom_fields');
add_action( 'admin_enqueue_scripts', 'pixflow_add_custom_fields');
//add_action( 'mBuilder_shortcodes_init', 'pixflow_add_custom_fields');
