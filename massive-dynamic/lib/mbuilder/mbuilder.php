<?php
/**
 * mBuilder provides some functionality for editing shortcodes in customizer.
 *
 * mBuilder is a visual editor for shortcodes and makes working with shortcodes more easier and fun.
 * It is added as a part of Massive Dynamic since V3.0.0 and designed to work with customizer. Enjoy Editing ;)
 *
 * @author  PixFlow
 *
 */
$fonts_list = PIXFLOW_THEME_LIB_URI . '/googlefonts-small.txt';
$fonts_list_dir = PIXFLOW_THEME_LIB . '/googlefonts-small.txt';
$file_content = wp_remote_get(
    $fonts_list,
    array(
        "timeout" => 90,
        "sslverify" => false
    )
);
if(is_wp_error($file_content)){
    $fonts = json_decode( @file_get_contents( $fonts_list_dir ) );
}else{
    $fonts = json_decode(  $file_content['body'] );
}

$mBuilderShortcodes = array();
$in_mbuilder = false;
$mBuilderExternalTypes = array();

/**
 * @version 1.1.0
 */
class MBuilder{

    /**
     * @var MBuilder - The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * @var array - models of each shortcode
     */
    public $models;

    /**
     * @var array - used shortcodes in content
     */
    public $used_shortcodes;

    /**
     * @var string - content of shortcodes
     */
    public $content = '';

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return MBuilder - The *Singleton* instance.
     * @since 1.0.0
     */
    public static function getInstance(){
        if (null === MBuilder::$instance) {
            MBuilder::$instance = new MBuilder();
        }

        return MBuilder::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     * @since 1.0.0
     */
    private function __clone(){}

    /**
     * Private unserialize method to prevent unserializing of the *Singleton* instance.
     *
     * @return void
     * @since 1.0.0
     */
    private function __wakeup(){}

    /**
     * MBuilder constructor.
     */
    protected function __construct(){
        global $mBuilderShortcodes,$in_mbuilder;
        $loadBuilder = true;
        // Skip load Builder if its not in customizer
        if(is_customize_preview()) {
            $loadBuilder = false;
        }
        // Skip load Builder if its blog or single portfolio template page
        if ( true == is_home() || (true == is_singular( 'portfolio' ) && 'standard' == pixflow_metabox('portfolio_options.template_type','standard')) ) {
            $loadBuilder = false;
        }
        // Skip load Builder if its shop page
        if(function_exists('is_shop')){
            if(is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url()){
                $loadBuilder = false;
            }
        }
        if($loadBuilder && $in_mbuilder) {
            do_action('mBuilder_before_init');

            // Enqueue required assets
            wp_enqueue_style('massivebuilderfonts', PIXFLOW_THEME_LIB_URI . '/customizer/assets/css/massivebuilderfonts.min.css',array(),PIXFLOW_THEME_VERSION);
            wp_enqueue_script('webfont', PIXFLOW_THEME_LIB_URI . '/customizer/assets/js/webfont.min.js', array(),PIXFLOW_THEME_VERSION,true);
            wp_enqueue_script('tinyMce', PIXFLOW_THEME_LIB_URI . '/mbuilder/assets/js/tinymce.min.js',array(),PIXFLOW_THEME_VERSION);
            wp_enqueue_script('mBuilder', PIXFLOW_THEME_LIB_URI . '/mbuilder/assets/js/mbuilder.min.js',array(),PIXFLOW_THEME_VERSION,true);
            wp_enqueue_media();
            wp_enqueue_style('mBuilder-gizmo', pixflow_path_combine(PIXFLOW_THEME_LIB_URI . '/mbuilder/assets/css/', 'mbuilder.min.css'), array(), PIXFLOW_THEME_VERSION);

            wp_localize_script('mBuilder', 'mBuilderValues', $this->localize_values() );
            wp_enqueue_style('admin',pixflow_path_combine(PIXFLOW_THEME_LIB_URI,'/assets/css/admin.min.css'),false,PIXFLOW_THEME_VERSION);


            do_action('mBuilder_shortcodes_init');

            foreach($mBuilderShortcodes as $key => $value){
                unset($value['params']);
                $mBuilderShortcode[$key] = $value;
            }

            wp_localize_script('mBuilder', 'mBuilderShortcodes', $mBuilderShortcode);
        }

    }

    /*
     * Create a list of wordpress urls that we need in our js files
     * @return void
    */
    private function localize_values() {
        $mBuilderValues = array(
            'ajax_url'    => admin_url('admin-ajax.php'),
            'ajax_nonce'  => wp_create_nonce('ajax-nonce'),
            'deleteText'  => __('Delete','massive-dynamic'),
            'duplicateText'  => __('Duplicate','massive-dynamic'),
            'animationText'  => __('Animation','massive-dynamic'),
            'rowText'     => __('Row','massive-dynamic'),
            'layoutText'  => __('Layout','massive-dynamic'),
            'customColText'  => __('Custom Column','massive-dynamic'),
            'deleteDescText' => __('Are you sure ?','massive-dynamic'),
            'settingText'    => __('Setting','massive-dynamic'),
            'leaveMsg' => esc_attr__('You are about to leave this page and you haven\'t saved changes yet, would you like to save changes before leaving?','massive-dynamic'),
            'unsaved' => esc_attr__('Unsaved Changes!','massive-dynamic'),
            'saved' => esc_attr__('Publish','massive-dynamic'),
            'saving' => esc_attr__('Saving...','massive-dynamic'),
            'save' => esc_attr__('Publish','massive-dynamic'),
            'google_font' => PIXFLOW_THEME_LIB_URI . '/googlefonts.txt' ,
            'designText' => esc_attr__('Design','massive-dynamic'),
            'responsiveText' => esc_attr__('Responsive','massive-dynamic'),
            'spacingText' => esc_attr__('Spacing','massive-dynamic'),
        );
        return  $mBuilderValues;
    }
    /**
     * Build setting panel form and inputs to edit shortcodes visually
     *
     * @return void
     * @since 1.0.0
     */
    public static function buildForm($params,$atts = array(),$content=null){
        if(isset($atts['md_text_use_title_slider']) || in_array('md_text_use_title_slider',$atts)){
            if((isset($atts['md_text_number']) && $atts['md_text_number']<2) || !isset($atts['md_text_number'])){
                $atts['md_text_use_title_slider'] = '';
            }
        }
        $innerContent = $content;
        global $mBuilderExternalTypes;
        foreach($params as $param){
            if($param['group'] == '') $param['group'] = esc_attr__( "General",  'massive-dynamic');
            $form[$param['group']][] = $param;
            extract( shortcode_atts( array(
                $param['param_name']  => $param['value']
            ),$atts ));
        }
        if(isset($atts['css']) && $atts['css'] != ''){
            $css = $atts['css'];
            $r = preg_match ('/.*?{(.*?)}.*?/is', $css,$matches);
            if(is_array($matches) && isset($matches[1])){
                $css = $matches[1];
            }else{
                $css = '';
            }
            $css = str_replace('``','\'',$css);
            $pat = '~[!]important~s';
            $css = trim(preg_replace($pat,'', $css));
            $pat = '~px~s';
            $css = trim(preg_replace($pat,'', $css));
            $css = explode(';',$css);
            $final_css = array();
            foreach($css as $prop){
                if($prop == ''){
                    continue;
                }
                $property = explode(':',$prop);
                $final_css[str_replace('-','_',trim($property[0]))]=trim($property[1]);
            }
        }
        if(count($form[esc_attr__( "General",  'massive-dynamic')])) {
            $generalTab = $form[esc_attr__("General", 'massive-dynamic')];
            unset($form[esc_attr__("General", 'massive-dynamic')]);
            $form = array(esc_attr__("General", 'massive-dynamic') => $generalTab) + $form;
            $content = $innerContent;
        }
        $groupHtml = array();
        echo '<div id="mBuilderTabs">';
        echo "<ul>";
        foreach($form as $key=>$group){
            echo '<li><a href="#mBuilder'.str_replace(' ','',esc_attr($key)).'">'.esc_attr($key).'</a></li>';
            foreach($group as $k=>$param){
                $dependency = '';
                if(isset($param['dependency'])){
                    $dependency = "data-mBuilder-dependency='".json_encode($param['dependency'])."'";
                }
                if(isset($param['mb_dependency'])){
                    $dependency = "data-mBuilder-dependency='".json_encode($param['mb_dependency'])."'";
                }
                $groupHtml[$key] .='<div class="vc_col-sm-12 vc_column '.$param['type'].' ' . $param['edit_field_class'] . '" '.$dependency.' >';
                if(isset($atts['css']) && array_key_exists($param['param_name'],$final_css) && ${$param['param_name']}==''){
                    ${$param['param_name']} = $final_css[$param['param_name']];
                }
                if($param['param_name'] == 'content'){
                    ${$param['param_name']} = $innerContent;
                }
                if(count($mBuilderExternalTypes[$param['type']])){
                    $groupHtml[$key] .='<div class="mBuilder_element_label">' . $param['heading'] . '</div><div class="edit_form_line">' ;
                    $groupHtml[$key] .= call_user_func_array ($mBuilderExternalTypes[$param['type']]['callback'],array($param,${$param['param_name']}));
                    $groupHtml[$key] .='</div>';
                    $groupJs[] = $mBuilderExternalTypes[$param['type']]['requiredjs'];
                }else {
                    switch ($param['type']) {
                        case 'textfield':
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line"><input type="text" class="simple-textbox wpb_vc_param_value wpb-textinput" value="' . ${$param['param_name']} . '" name="' . $param['param_name'] . '"></div>';
                            break;
                        case 'hidden':
                            $groupHtml[$key] .=
                                '<input type="hidden" class="wpb_vc_param_value wpb-textinput" value="' . ${$param['param_name']} . '" name="' . $param['param_name'] . '">';
                            break;
                        case 'textarea_html':
                            $groupHtml[$key] .=
                                '<div class="edit_form_line"><textarea name="' . $param['param_name'] . '">' . stripslashes($content) . '</textarea></div>';
                            break;
                        case 'textarea':
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line"><textarea name="' . $param['param_name'] . '">' . ${$param['param_name']} . '</textarea></div>';
                            break;
                        case 'textarea_raw_html':
                            $raw_content =(bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', ${$param['param_name']}) ;
                            $raw_content = ($raw_content)?htmlentities( rawurldecode( base64_decode( strip_tags( $raw_content ) ) ), ENT_COMPAT, 'UTF-8' ):$raw_content;
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line"><textarea class="textarea_raw_html" name="' . $param['param_name'] . '">' . $raw_content . '</textarea></div>';
                            break;
                        case 'separator':
                            $groupHtml[$key] .=
                                '<div class="edit_form_line"><hr></div>';
                            break;
                        case 'dropdown':
                            $options = '';
                            foreach ($param['value'] as $optkey => $optValue) {
                                $select = ($optValue == ${$param['param_name']}) ? 'selected="selected"' : '';
                                $options .= '<option value="' . $optValue . '" ' . $select . '>' . $optkey . '</option>';
                            }
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line"><select name="' . $param['param_name'] . '">' .
                                $options .
                                '</select></div>';
                            break;
                        case 'attach_image':
                            $image_id = (int)${$param['param_name']};
                            $placeholder = '';
                            if($image_id != '' && is_int($image_id) && $image_id != 0){
                                $style = 'background-image: url('.wp_get_attachment_url( $image_id ).')';
                                $placeholder .= '<div data-id="'.$image_id.'" class="mBuilder-upload-img single has-img" style="'.$style.'"><span class="remove-img">X</span>';
                            }else{
                                $placeholder = '<div class="mBuilder-upload-img single"><span class="remove-img mBuilder-hidden">X</span>';
                            }
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line">'.
                                    $placeholder.
                                    '<input type="text" name="'.$param['param_name'].'" value="'.${$param['param_name']}.'"></div>'.
                                '</div>';
                            break;
                        case 'attach_images':
                            $images_id = (${$param['param_name']}!='')?explode(',',${$param['param_name']}):array();
                            $placeholder = '';
                            if(count($images_id)){
                                foreach($images_id as $id){
                                    $style = 'background-image: url('.wp_get_attachment_url( $id ).')';
                                    $placeholder .= '<div data-id="'.$id.'" class="mBuilder-upload-img multi has-img" style="'.$style.'"><span class="remove-img">X</span></div>';
                                }
                                $placeholder .= '<div class="mBuilder-upload-img multi"><span class="remove-img mBuilder-hidden">X</span></div>';
                            }else{
                                $placeholder = '<div class="mBuilder-upload-img multi"><span class="remove-img mBuilder-hidden">X</span></div>';
                            }

                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">' . $param['heading'] . '</div>' .
                                '<div class="edit_form_line  mBuilder-upload-imgs">'.
                                $placeholder.
                                '<input type="text" name="'.$param['param_name'].'" value="'.${$param['param_name']}.'" class="mBuilder-hidden">'.
                                '</div>';
                            break;
                        case 'google_fonts':
                            $inputValue = ${$param['param_name']};
                            $value = urldecode(${$param['param_name']});
                            $value = str_replace("font_family:", "", $value);
                            $value = str_replace("font_style:", "", $value);
                            $fontFamily = explode(':',$value);
                            $fontFamily = $fontFamily[0];
                            $fontStyle = explode('|',$value);
                            $fontStyle = $fontStyle[1];
                            $value = array('font_family'=>$fontFamily,'font_style'=>$fontStyle);
                            global $fonts;
                            $fontKey = 0;
                            $options = '';

                            foreach ( $fonts as $fKey=>$font_data ) {
                                $select='';
                                if( strtolower( $value['font_family'] ) == strtolower( $font_data->font_family )){
                                    $fontKey=$fKey;
                                    $select = 'selected="selected"';
                                }
                                $options .=
                                    '<option data-font-id="'.$fKey.'" value="'.$font_data->font_family . ':' . $font_data->font_styles.'" data-font="'.$font_data->font_family.'" '.$select.'>'.$font_data->font_family.'</option>';
                            }
                            $fontStyleOptions = pixflow_loadfontStyles($fontKey,$value['font_style']);
                            $groupHtml[$key] .=
                                '<div class="mBuilder_element_label">Font</div>' .
                                '<div class="edit_form_line  mBuilder-google-font-picker">'.
                                '<select class="google-fonts-families" data-input="'.$param['param_name'].'">'.
                                $options.
                                '</select>'.
                                '<select class="google-fonts-styles" data-input="'.$param['param_name'].'">'.
                                $fontStyleOptions.
                                '</select>'.
                                '<input type="text" name="'.$param['param_name'].'" value="'.$inputValue.'" class="mBuilder-hidden"/>'.
                                '</div>';
                            break;
                        default:
                            $groupHtml[$key] .= '[Unknown controller]';
                            break;
                    }
                }
                $groupHtml[$key] .= '</div>';
            }
        }
        echo "</ul>";
        $groupJs = array_unique($groupJs);
        foreach ($groupHtml as $key=>$html){
            print('<div id="mBuilder'.str_replace(' ','',esc_attr($key)).'" class="mBuilder-edit-el">'.$html."</div>");
        }
        $spectrum = PIXFLOW_THEME_CUSTOMIZER_URI.'/assets/js/spectrum.min.js';
        echo '<script src="'.esc_url($spectrum).'"></script>';
        $spectrumCSS = PIXFLOW_THEME_CUSTOMIZER_URI.'/assets/css/spectrum.min.css';
        echo '<link rel="stylesheet" href="'.esc_url($spectrumCSS).'">';

        $nouislider = PIXFLOW_THEME_CUSTOMIZER_URI.'/assets/js/jquery.nouislider.min.js';
        echo '<script src="'.esc_url($nouislider).'"></script>';
        $nouisliderCSS = PIXFLOW_THEME_CUSTOMIZER_URI.'/assets/css/jquery.nouislider.min.css';
        echo '<link rel="stylesheet" href="'.esc_url($nouisliderCSS).'">';

        foreach($groupJs as $value){
            echo '<script src="'.esc_url($value).'"></script>';
        }
        echo "</div>";

    }

    public static function parseAttributes($attributes){
        $attr = json_decode(stripslashes($attributes),true);

        if(!is_array($attr)){

            if($attr == null){
                $attr = stripslashes($attributes);
            }
            $attributes = array();
            if(preg_match('/^ *\[/s',$attr )) {
                if (!preg_match('/^\[[^\]]*? /s', $attr)) {
                    return $attributes;
                }
                $attr = preg_replace('/^\[[^\]]*? /s','' ,$attr );
            }
            $i=0;

            while($attr) {
                if(++$i>200){
                    break;
                }
                $attr = trim($attr);
                if(preg_match('/^\].*/s',$attr )){
                    $attr = null;
                    break;
                }
                preg_match('/(?=[^\'"]*)[\'"]/s', $attr, $separator);

                if(isset($separator[0])){
                    if($separator[0] == '') {
                        echo esc_attr($attr);
                        break;
                    }
                    $attrs = explode($separator[0], $attr, 2);
                    $key = $attrs[0];
                    if(preg_match('/^'.$separator[0].'/s',$attrs[1])){
                        $value = array();
                        $value[0] = '';
                        $value[1] = '';
                        $value[2] = substr($attrs[1],1);
                    }else{
                        $value = preg_split("/([^\\\])$separator[0]/s", $attrs[1], 2, PREG_SPLIT_DELIM_CAPTURE);
                    }
                    $key = str_replace('=', '', $key);
                    if( ! (isset($value[0]) && isset($value[1]) && isset($value[2])) ){
                        $value = array();
                        $value[0] = '';
                        $value[1] = '';
                        $value[2] = substr($attrs[1],1);
                    }
                    $attr = $value[2];
                    $value = $value[0].$value[1];
                    $value = str_replace('\"','"',$value);
                    $key = trim($key);
                    $attributes[$key] = $value;
                }
            }
            return $attributes;
        }
        return $attr;
    }

    public static function getModelAttribute($attributes,$attr){
        $attrs = MBuilder::parseAttributes($attributes);
        if(isset($attrs[$attr])){
            return $attrs[$attr];
        }else{
            return false;
        }
    }

    /**
     * Prepare content from models
     *
     * @param $models - shortcode models
     *
     * @return string - content of the page by shortcode tags
     * @since 1.0.0
     */
    public function getContent($models){
        $this->content = '';
        $this->models = json_decode(stripslashes($models),true);

        // Find childs
        foreach ($this->models as $id=>$model) {
            $current_id = $id;
            $this->models[$id]['flag'] = false;
            $this->models[$id]['id'] = $id;
            //find childes
            $childes = array();
            foreach ($this->models as $key2=>$model2) {
                $el = $model2;
                if(isset($el['parentId'])){
                    if($el['parentId'] == $current_id){
                        $childes[] = $key2;
                    }
                }
            }
            $orderedChildes = array();
            $o = 1;
            foreach($childes as $child){
                if(array_key_exists('order', $this->models[$child])){
                    if(isset($orderedChildes[$this->models[$child]['order']])){
                        $orderedChildes[++$this->models[$child]['order']] = $child;
                    }else{
                        $orderedChildes[$this->models[$child]['order']] = $child;
                    }
                }else{
                    $orderedChildes[$o++] = $child;
                }
            }
            ksort($orderedChildes);
            $this->models[$id]['childes'] = $orderedChildes;
        }
        $els = $this->models;
        $rows = array();

        foreach($this->models as $key=>$item){
            if($item['type'] == 'vc_row'){
                $rows[$key] = $item['order'];
                unset($this->models[$key]);
            }
        }
        arsort($rows);
        foreach($rows as $key=>$item){
            $this->models = array($key=>$els[$key])+$this->models;
        }
        foreach ($this->models as $id=>$model) {
            if($this->models[$id]['flag']){
                continue;
            }else{
                $this->models[$id]['flag'] = true;
            }
            $this->generateContent($id);
        }
        return $this->content;
    }

    /**
     * Save content of page/post to the database
     *
     * @param $id - post/page ID
     *
     * @return void
     * @since 1.0.0
     */
    public function saveContent($id){
        $current_item = array(
            'ID'           => $id,
            'post_content' => $this->content,
        );
        $post_id = wp_update_post( $current_item, true );
        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            foreach ($errors as $error) {
                echo esc_attr($error);
            }
        }else{
            echo 'updated';
        }
    }

    /**
     * replace shortcode models with wordpress shortcode pattern
     *
     * @param $id - Shortcode model ID
     *
     * @return void
     * @since 1.0.0
     */
    public function generateContent($id){
        $type = trim($this->models[$id]['type']);
        $attr = trim($this->models[$id]['attr']);


        $pat = '~el_id=".*?"~s';
        $attr = trim(preg_replace($pat,'', $attr));
        $childes = $this->models[$id]['childes'];
        $content = $this->models[$id]['content'];
        $attr = ($attr != '')?' '.$attr:$attr;
        // to prevent put double qoutation on VC Column
        if($type == 'vc_column'){
            $attr = str_replace('url("','url(``',$attr);
            $attr = str_replace('");','``)',$attr);
        }
        $this->content .= '['.$type.$attr.']';
        if(count($childes)){
            foreach ($childes as $child) {
                if( $this->models[$child]['flag']){
                    continue;
                }else{
                    $this->models[$child]['flag'] = true;
                }
                $this->content .= $this->generateContent($child);
            }
        }
        if($content != ''){
            $this->content .= $content;
        }
        $this->content .='[/'.$type.']';
    }

    public function generate_pages_models($page_id=null){
        if(null == $page_id){
            $page_id = get_the_ID();
        }
    	global $mBuilderModelIdArray,$in_mbuilder;
        $last_in_mbuilder = $in_mbuilder;
        $in_mbuilder =true;
		$page_content = get_post($page_id);

        if(!function_exists('pixflow_js_remove_wpautop')){
            require_once ('includes/visualcomposer-functions.php');
        }
        if($page_content) {
            pixflow_js_remove_wpautop($page_content->post_content);
        }
	    $this->models = $mBuilderModelIdArray;
        $in_mbuilder = $last_in_mbuilder;
	    return $this->models;
    }

    public function list_used_shortcodes($page_id=null){
        if(null == $page_id){
            $page_id = get_the_ID();
        }
        $used_shortcodes = array();
        $content = get_post($page_id);
        if (! $content){
            return $used_shortcodes;
        }
        $content = $content->post_content;
        $pat = "~\[[^\/][^=]*?( .*?)*?\]~s";

        if(preg_match_all($pat, $content, $mats)){
            $els = $mats[0];
            $dels = array_count_values($els);
            foreach($dels as $el=>$n){
                $el = substr($el,1);
                $el = str_replace(']','',$el);
                $el = explode(' ',trim($el));
                $used_shortcodes[] = $el[0];
            }
            $used_shortcodes = array_unique($used_shortcodes);
        }

        $this->used_shortcodes = $used_shortcodes ;
        return $this->used_shortcodes;
    }

    /**
     * Generate static JS and CSS for each page based on their shortcodes after publish
     *
     * @param $id - Page ID
     * @param $models - Shortcode models
     *
     * @return boolean
     * @since 1.0.0
     */
    public function generate_static_js_css($id){
        require_once(ABSPATH . 'wp-admin/includes/file.php');
	    $page_js_path = PIXFLOW_THEME_CACHE . '/' . $id . '.js';
	    $page_css_path = PIXFLOW_THEME_CACHE . '/' . $id . '.css';
        WP_Filesystem(false,false,true);
        global $wp_filesystem;
        $models = array();
        $js_content = $css_content ='';
        if(empty($this->models) && (!file_exists($page_js_path) || !file_exists($page_css_path)) ) {
            $this->generate_pages_models($id);
        }
        if(empty($this->models)) {
            $this->models = array();
        }
        $used_do_shortcodes = array();
        $do_shortcodes = pixflow_load_do_shortcodes();
        foreach ($do_shortcodes as $shortcode){
            $used_do_shortcodes[] = array('attr'=>'','content'=>'','type'=>$shortcode);
        }
        if(count($used_do_shortcodes)){
            $this->models = $used_do_shortcodes + $this->models;
        }
        foreach($this->models as $model){
            if(!in_array($model['type'],$models)){
                $models[] = $model['type'];
                $dependencies = pixflow_load_dependency($model['type']);
                $js_content .= $dependencies['js'];
                $js_content .= @file_get_contents(PIXFLOW_THEME_LIB.'/shortcodes/'. $model['type'] . '/script.min.js');
                $css_content .= $dependencies['css'];
                $css_content .= @file_get_contents(PIXFLOW_THEME_LIB.'/shortcodes/'. $model['type'] . '/style.min.css');
            }
        }

        if ( ! $wp_filesystem->put_contents(  PIXFLOW_THEME_CACHE .'/'.$id.'.js', $js_content) )
        {
            echo esc_attr__("error saving file!",'massive-dynamic');
        }
        if ( ! $wp_filesystem->put_contents(  PIXFLOW_THEME_CACHE . '/'.$id.'.css', $css_content) )
        {
            echo esc_attr__("error saving file!",'massive-dynamic');
        }
    }

    /**
     * A filter on the_content if mBuilder is loaded to change normal texts to the Text Shortcode
     *
     * @since 1.0.0
     */
    public function textToShortcode($content){
        if(strpos($content,"[vc_row")===false){
            $temp = str_replace( array('<p>','</p>'), '', $content );
            if ( strlen( trim( $temp ) ) > 0 ) {
                $content = '[vc_row][vc_column][md_text md_text_title1="" md_text_title_separator="no"]'.$content.'[/md_text][/vc_column][/vc_row]';
            }
        }
        return $content;
    }

}

/**
 * Add visual composer classes to the editor
 *
 * @param $classes - classes of the body
 *
 * @return string - new classes for the body
 * @since 1.0.0
 */
function addBodyClasses($classes){
    global $in_mbuilder;
    if ($in_mbuilder) {
        $classes[] = 'compose-mode';
        $classes[] = 'vc_editor';
        $classes[] = 'pixflow-builder';
    }
    return $classes;
}
add_filter('body_class', 'addBodyClasses');

function mbuilder_set_assets(){
    $shortcodes_list = PixflowFramework::Pixflow_Shortcodes() ;
    $shortcodes_list = array_map('pixflow_rename_shortcode' , $shortcodes_list);
    return pixflow_get_assets_for_customizer($shortcodes_list) ;
}
/**
 * Massive Dynamic Starts using mBuilder as its default builder
 *
 * @param $content
 * @return string
 */
function pixflow_mBuilder($content){
    $mBuilder = MBuilder::getInstance();
    // Skip load Builder if its not in customizer
	global $in_mbuilder;

    if(pixflow_is_builder_editable(get_the_ID()) == false && isset($_GET['mbuilder'] )) {
    	$url = get_permalink();
    	?>
		<script> window.location.href = ' <?php echo esc_url($url); ?> ' </script>
		<?php
        return false ;
    }

    if(!strpos($content,'[md_blog')){
        $content = $in_mbuilder ? $mBuilder->textToShortcode($content) : $content ;
    }


    do_action('mBuilder_before_render');

    return $content;
}
add_filter('the_content','pixflow_mBuilder');

$current_user = wp_get_current_user();
if(isset($_GET['mbuilder']) && user_can( $current_user, 'administrator' )){
    global $in_mbuilder;
    $in_mbuilder = true;
    add_action('wp_enqueue_scripts','mbuilder_set_assets');
}

/**
 * Add visual basic shortcodes to mBuilder
 *
 *
 * @return void
 * @since 1.0.0
 */
function mBuilderPrerequisits(){
    add_shortcode("vc_row",'pixflow_get_style_script');
    add_shortcode("vc_row_inner",'pixflow_get_style_script');
    add_shortcode("vc_column",'pixflow_get_style_script');
    add_shortcode("vc_column_inner",'pixflow_get_style_script');
    require_once(PIXFLOW_THEME_LIB.'/mbuilder/includes/visualcomposer-functions.php');
}
require_once(PIXFLOW_THEME_LIB.'/mbuilder/includes/visualcomposer-compatibilities.php');
require_once(PIXFLOW_THEME_LIB.'/mbuilder/includes/ajax-actions.php');

add_action('init', 'mBuilderPrerequisits', 998);

function pixflow_tinymce_config( $init ) {
    $init['wpautop'] = false;
    $init['cleanup'] = false;
    $init['forced_root_block'] = false;
    $init['force_br_newlines'] = true;
    $init['remove_linebreaks'] = false;
    $init['convert_newlines_to_brs'] = false;
    $init['remove_redundant_brs'] = false;
    return $init;
}
add_filter('tiny_mce_before_init', 'pixflow_tinymce_config');

/**
 * Late load bootstrap styles to override visualcomposer styles.
 *
 * @since 1.0.0
 */
function mbuilderLateLoadStyles(){
    wp_enqueue_style('bootstrap-style',pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'bootstrap.min.css'),array(),PIXFLOW_THEME_VERSION);
}
add_action('get_footer','mbuilderLateLoadStyles',999);

/**
 * Delete cache files from cache directory after each save post
 *
 * @since 1.1.0
 */
function mbuilder_generate_cache_files($post_id){
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem(false,false,true);
    global $wp_filesystem;

    $wp_filesystem->delete(PIXFLOW_THEME_CACHE.'/'.$post_id.'.css');
    $wp_filesystem->delete(PIXFLOW_THEME_CACHE.'/'.$post_id.'.js');
}
add_action( 'save_post', 'mbuilder_generate_cache_files' );

function pixflow_load_builder_layout() {
    get_template_part('lib/mbuilder/templates/toolbar');
    get_template_part('lib/mbuilder/templates/shortcode-sidebar');
}
if($in_mbuilder){
    add_action('pixflow_body_start', 'pixflow_load_builder_layout');
}

/*
 * Add custom styles when load pixflow builder toolbar
 * */
function pixflow_builder_toolbar_style(){
    $inline_css = 'html { margin-top: 50px !important; }'.'* html body { margin-top: 50px !important; }';
    wp_add_inline_style('custom-style' , wp_strip_all_tags( $inline_css ) );
}
if($in_mbuilder){
    add_action('wp_enqueue_scripts', 'pixflow_builder_toolbar_style');
}

