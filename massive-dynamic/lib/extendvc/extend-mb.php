<?php
$filedClass = 'vc_col-sm-12 vc_column ';
$separatorCounter = 0;

/* custom icon picker field */
function pixflow_vc_iconpicker_field($settings, $value)
{

    return '<button value="' . $value . '" input-class="wpb_vc_param_value wpb-textinput px-input-vc-icon'
    . $settings['param_name'] . ' ' . $settings['type'] . '_field" name="' . $settings['param_name'] . '" class="iconpicker" data-original-title="" title="">'
    . '</button>';
}

/* custom date picker field */
function pixflow_vc_datepicker_field($settings, $value)
{
    return '<input type="text" value="' . $value . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_datepicker" data-timepicker="true" data-language="en"  data-time-format="hh:ii"/>';
}

/* custom base64 text field  */
function pixflow_vc_base64_text_field($settings, $value){

    if( preg_match('/pixflow_base64/' , $value)){
        $value = str_replace('pixflow_base64' , '' , $value);
        $value = base64_decode($value);
    }
    return '<input type="text" name="'.$settings['param_name'].'_text" class="to-base64 mbuilder-skip">
            <input type="hidden" name="'.$settings['param_name'].'" value="pixflow_base64'.base64_encode($value).'" class="wpb_vc_param_value wpb-textinput ">';
}
/* custom base64 text field  */
function pixflow_vc_base64_textarea_field($settings, $value){

    if( preg_match('/pixflow_base64/' , $value)){
        $value = str_replace('pixflow_base64' , '' , $value);
        $value = base64_decode($value);
    }
    return '<textarea name="'.$settings['param_name'].'_text" class="to-base64 mbuilder-skip">'.$value.'</textarea>
            <textarea name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput mBuilder-hidden hidden">pixflow_base64'.base64_encode($value).'</textarea>';
}
/* custom color picker field */
function pixflow_vc_colorpicker_field($settings, $value)
{

    $opacity = (isset($settings['opacity']) && $settings['opacity'] === true) ? 'true' : 'false';
    $defaultColor = (isset($settings['defaultColor']) && $settings['defaultColor'] != '') ? $settings['defaultColor'] : '#000';
    $value = ($value != '') ? $value : $defaultColor;
    $id = uniqid();
    return '<input id="' . $id . '" opacity="' . $opacity . '" type="text" value="' . $value . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_colorpicker" />';
}

/* custom gradient color picker field */
function pixflow_vc_gradientcolorpicker_field($settings, $value)
{
    if( preg_match('/pixflow_base64/' , $value)){
        $value = str_replace('pixflow_base64' , '' , $value);
        $value = base64_decode($value);
    }
    $value = str_replace('``', '"', $value);
    $value = str_replace('\'', '"', $value);
    $output = '';
    $defaults = (object)array('color1' => '#fff', 'color2' => '#000', 'color1Pos' => '0', 'color2Pos' => '100', 'angle' => '0');
    $defaultColor = (isset($settings['defaultColor']) && $settings['defaultColor'] != '') ? $settings['defaultColor'] : $defaults;
//    $value = (!isset($value)) ? $defaultColor : $value;

    $value = ($value != '' && isset($value)) ? json_decode($value) : $defaultColor;
    $value = ($value == null)?$defaultColor:$value;
    $id = uniqid();
    $output .= '<input id="input-' . $id . '" type="text" value="' . json_encode($value) . '" name="' . $settings['param_name'] . '" class="md-hidden wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_gradientcolorpicker md-base64" />';
    $output .= '<div id="' . $id . '" pos1="' . $value->{"color1Pos"} . '" pos2="' . $value->{"color2Pos"} . '" col1="' . $value->{"color1"} . '" col2="' . $value->{"color2"} . '" class="gradient_color_picker"></div>';
    $output .= '<br/><br/>';
    $output .= '<div angle="' . $value->{"angle"} . '" gID="' . $id . '" id="angle-' . $id . '" class="gradient_color_picker_angle"></div><input type="text" id="angleValue-' . $id . '" class="gradient-angle" value="' . $value->{"angle"} . '" />';
    return $output;
}

/* custom range slider controller */
function pixflow_vc_slider_field($settings, $value)
{
// Note : You can define these parameters to your range slider --> min, max, prefix, step.
    $output = '';
    $defaults = array('min' => '0', 'max' => '100', 'prefix' => '%', 'step' => '1', 'decimal' => '0');
    $defaultSetting = (isset($settings['defaultSetting']) && $settings['defaultSetting'] != '') ? $settings['defaultSetting'] : $defaults;
    $defaultSetting['decimal'] = (isset($defaultSetting['decimal'])) ? $defaultSetting['decimal'] : 0;
    if ((int)$defaultSetting['step'] < 1) {
        $value = ((float)$value === '') ? $defaultSetting['min'] : (float)$value;
        $value = number_format($value, 1);
    } else {
        $value = ((int)$value === '') ? $defaultSetting['min'] : (int)$value;
    }
    $id = uniqid();
    $defaultSetting['prefix'] = isset($defaultSetting['prefix']) ? $defaultSetting['prefix'] : '';
    $output .= '<input id="input-' . $id . '" type="text" value="' . $value . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_slider" />';
    $output .= '<div value="' . $value . '" id="' . $id . '" class="vc_slider" min="' . $defaultSetting['min'] . '" max="' . $defaultSetting['max'] . '" prefix="' . $defaultSetting['prefix'] . '" step="' . $defaultSetting['step'] . '" decimal="' . $defaultSetting['decimal'] . '" ></div>';
    $output .= '<div id="' . $id . '" class="vc_slider_value" value="' . $value . '">' . $value . $defaultSetting['prefix'] . '</div>';
    return $output;
}

/* Spacing field */
function pixflow_vc_spacing_field($settings,$value){
    $value = str_replace('``', '"', $value);
    $value = str_replace("'", '"', $value);

    $value = ($value != '' && isset($value)) ? json_decode($value,true) : $settings['defaultSetting'];
    $value = ($value == null)?$settings['defaultSetting']:$value;

    $output = '';
    $id = uniqid();

    $output = '<div class="spacing-field">';
    $output .= '<input id="' . $id . '" type="text" value=\'' . json_encode($value) . '\' name="' . $settings['param_name'] . '" class="md-hidden wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_spacing" />';
    $output .= '<input class="val margin-left mbuilder-skip" name="'.$settings['param_name'].'_marginLeft" value="'.$value['marginLeft'].'"><input class="val margin-top mbuilder-skip" name="'.$settings['param_name'].'_marginTop" value="'.$value['marginTop'].'"><input class="val margin-right mbuilder-skip" name="'.$settings['param_name'].'_marginRight" value="'.$value['marginRight'].'"><input class="val margin-bottom mbuilder-skip" name="'.$settings['param_name'].'_marginBottom" value="'.$value['marginBottom'].'">';
    $output .= '<div class="border"><input class="val padding-left mbuilder-skip" name="'.$settings['param_name'].'_paddingLeft" value="'.$value['paddingLeft'].'"><input class="val padding-top mbuilder-skip" name="'.$settings['param_name'].'_paddingTop" value="'.$value['paddingTop'].'"><input class="val padding-right mbuilder-skip" name="'.$settings['param_name'].'_paddingRight" value="'.$value['paddingRight'].'" ><input class="val padding-bottom mbuilder-skip" name = "'.$settings['param_name'].'_paddingBottom" value="'.$value['paddingBottom'].'"></div>';
    $output .= '</div>';
    return $output;

}

/* custom multiselect field */
function pixflow_vc_multiselect_field($settings, $value)
{
    $output = '';
    $items = (isset($settings['items']) && is_array($settings['items'])) ? $settings['items'] : array();
    $defaults = (isset($settings['defaults']) && $settings['defaults'] == 'all') ? $items : array();
    $value = ($value != '') ? $value : implode(',', $defaults);
    $values = explode(',', $value);
    $id = uniqid();
    $output .= '<input id="input-' . $id . '" type="text" value="' . $value . '" name="' . $settings['param_name'] . '" class="md-hidden wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field md_vc_muliselect" />';
    ob_start();
    ?>
    <dl class="dropdown" xmlns="http://www.w3.org/1999/html">
        <dt>
            <a href="#">
                <span class="hida"><?php esc_attr_e('Select Items', 'massive-dynamic') ?></span>
                <span data-id="<?php echo esc_attr($id); ?>" class="multiSel"></span>
            </a>
        </dt>
        <dd>
            <div class="mutliSelect">
                <ul>
                    <?php if (count($items) < 1) { ?>
                        <li><?php esc_attr_e('No items to select!', 'massive-dynamic') ?></li>
                    <?php } else { ?>
                        <?php foreach ($items as $item) { ?>
                            <li>
                                <input
                                    data-id="<?php echo esc_attr($id); ?>" <?php echo (in_array($item, $values)) ? 'checked="checked"' : ''; ?>
                                    type="checkbox"
                                    value="<?php echo esc_attr($item); ?>"/><?php echo esc_attr($item); ?>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </dd>
    </dl>
    <?php
    $output .= ob_get_clean();
    return $output;
}

function pixflow_vc_checkbox_field($settings, $value)
{
    $output = '';
    $id = uniqid();
    if (is_array($value)) {
        foreach ($value as $val) {
            $value = $val;
            break;
        }
    }
    $checked = checked($value, 'yes', false);
    $output .= '<input ' . $checked . ' data-name=' . $settings['param_name'] . '  el-id="' . $id . '" value="' . $value . '" class="wpb_vc_param_value ' . $settings['param_name'] . ' ' . $settings['type'] . '" type="checkbox" > ';
    $output .= '<input id="' . $settings['param_name'] . '-" el-id="' . $id . '" type="hidden" value="' . $value . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['type'] . '_field ' . $settings['param_name'] . ' md_vc_checkbox" />';

    return $output;
}

if (!function_exists('js_composer_bridge_admin')) {

    function pixflow_js_composer_scripts_admin()
    {

        // Find Where we are
        $bodyClasess = get_body_class();

        // "no-customize-support" show we are in dashboard

        if (!in_array('no-customize-support', $bodyClasess)) {
            {
                //Register RTL inner Style
                wp_enqueue_style('rtl-inner-customizer', PIXFLOW_THEME_LIB_URI . '/assets/css/rtl-inner-customizer.min.css');
            }
        }

        // For dashboard Env.

        if (is_rtl()) {
            //Register RTL inner Style
            wp_enqueue_style('rtl-dashboard', PIXFLOW_THEME_LIB_URI . '/assets/css/rtl-dashboard.min.css');
            wp_enqueue_style('rtl-dashboard', PIXFLOW_THEME_LIB_URI . '/assets/css/rtl-dashboard.min.css');
        }

        // run out of admin pannel

        if (is_customize_preview() && is_rtl()) {
            //Register RTL Style
            wp_enqueue_style('rtl-style', pixflow_path_combine(PIXFLOW_THEME_URI, 'rtl-customizer.min.css'), array(), PIXFLOW_THEME_VERSION);
        }


        // presscore stuff
        wp_enqueue_style('', PIXFLOW_THEME_LIB_URI . '/assets/css/vc-extend.min.css');

        wp_enqueue_style('controller-rgba', pixflow_path_combine(PIXFLOW_THEME_CUSTOMIZER_URI, 'assets/css/spectrum.min.css'), array(), PIXFLOW_THEME_VERSION);
        wp_enqueue_script('controller-rgba', pixflow_path_combine(PIXFLOW_THEME_CUSTOMIZER_URI, 'assets/js/spectrum.min.js'), array(), PIXFLOW_THEME_VERSION, true);

        wp_enqueue_style('nouislider-style', pixflow_path_combine(PIXFLOW_THEME_CUSTOMIZER_URI, 'assets/css/jquery.nouislider.min.css'), array(), PIXFLOW_THEME_VERSION);
        wp_enqueue_script('nouislider-script', pixflow_path_combine(PIXFLOW_THEME_CUSTOMIZER_URI, 'assets/js/jquery.nouislider.min.js'), array(), PIXFLOW_THEME_VERSION, true);
    }

}

function pixflow_vc_separator_field($settings)
{
    return '<hr/>' . '<input class="wpb_vc_param_value wpb-textinput" type="hidden" name="' . $settings['param_name'] . '">';
}

function pixflow_vc_url_field($settings, $value)
{
    $output = '';
    $id = esc_attr(uniqid());
    ob_start();
    ?>
    <div class="md_vc_url_control">
        <input id="<?php echo esc_attr($id) ?>" type="text" value="<?php echo esc_attr($value); ?>"
               name="<?php echo esc_attr($settings['param_name']); ?>"
               class="wpb_vc_param_value wpb-textinput <?php echo esc_attr($settings['type']) . '_field'; ?> md_vc_url"/>
        <textarea onclick="this.focus();this.select()" readonly id="<?php echo esc_attr('url_' . $id) ?>" class="add"
                  rows="4" cols="50"><?php esc_attr_e('Type section name and copy URL', 'massive-dynamic') ?></textarea>
    </div>
    <?php
    $output .= ob_get_clean();
    return $output;
}

function pixflow_vc_description_field($settings)
{
    return "<div class='content'>" . $settings['value'] . "</div>" . '<input class="wpb_vc_param_value wpb-textinput" type="hidden" name="' . $settings['param_name'] . '">';
}



//hooks
add_action('admin_enqueue_scripts', 'pixflow_js_composer_scripts_admin', 15);

// Removing shortcodes

pixflow_remove_element("vc_wp_meta");
pixflow_remove_element("vc_wp_recentcomments");
pixflow_remove_element("vc_wp_pages");
pixflow_remove_element("vc_wp_custommenu");
//pixflow_remove_element("vc_wp_text");
pixflow_remove_element("vc_wp_posts");
pixflow_remove_element("vc_wp_links");
pixflow_remove_element("vc_wp_categories");
pixflow_remove_element("vc_wp_archives");
pixflow_remove_element("vc_wp_rss");
//pixflow_remove_element("vc_teaser_grid");
pixflow_remove_element("vc_button");
pixflow_remove_element("vc_button2");
pixflow_remove_element("vc_cta_button");
pixflow_remove_element("vc_cta_button2");
pixflow_remove_element("vc_message");
pixflow_remove_element("vc_progress_bar");
pixflow_remove_element("vc_pie");
pixflow_remove_element("vc_posts_slider");
//pixflow_remove_element("vc_posts_grid");
pixflow_remove_element("vc_carousel");
pixflow_remove_element("vc_images_carousel");
//pixflow_remove_element("vc_column_text");
pixflow_remove_element("vc_separator");
pixflow_remove_element("vc_text_separator");
pixflow_remove_element("vc_toggle");
pixflow_remove_element("vc_single_image");
pixflow_remove_element("vc_gallery");
pixflow_remove_element("vc_tabs");
pixflow_remove_element("vc_tour");
pixflow_remove_element("vc_accordion");
pixflow_remove_element("vc_video");
//pixflow_remove_element("vc_raw_html");
//pixflow_remove_element("vc_raw_js");
pixflow_remove_element("vc_flickr");
pixflow_remove_element("vc_custom_heading");
//pixflow_remove_element("vc_basic_grid");
//pixflow_remove_element("vc_media_grid");
//pixflow_remove_element("vc_masonry_grid");
//pixflow_remove_element("vc_masonry_media_grid");
pixflow_remove_element("vc_icon");
pixflow_remove_element("vc_btn");
pixflow_remove_element("vc_cta");
pixflow_remove_element("vc_wp_search");
pixflow_remove_element("vc_wp_calendar");
pixflow_remove_element("vc_wp_calendar");
pixflow_remove_element("vc_wp_tagcloud");
pixflow_remove_element("vc_tta_tabs");
pixflow_remove_element("vc_tta_tour");
pixflow_remove_element("vc_tta_accordion");
pixflow_remove_element("vc_tta_section");
pixflow_remove_element("vc_tta_pageable");
pixflow_remove_element("vc_widget_sidebar");

$sociallink = array(
    'Facebook' => 'facebook3',
    'Twitter' => 'twitter2',
    'Vimeo' => 'vimeo',
    'YouTube' => 'youtube',
    'Google+' => 'googleplus2',
    'Dribbble' => 'dribbble2',
    'Tumblr' => 'tumblr2',
    'linkedin' => 'linkedin2',
    'Flickr' => 'flickr2',
    'forrst' => 'forrst',
    'github' => 'github',
    'lastfm' => 'lastfm',
    'paypal' => 'paypal',
    'RSS' => 'feed2',
    'skype' => 'skype',
    'wordpress' => 'wordpress',
    'yahoo' => 'yahoo',
    'steam' => 'steam',
    'reddit' => 'reddit',
    'stumbleupon' => 'stumbleupon',
    'pinterest' => 'pinterest',
    'deviantart' => 'deviantart2',
    'xing' => 'xing',
    'blogger' => 'blogger',
    'soundcloud' => 'soundcloud',
    'delicious' => 'delicious',
    'foursquare' => 'foursquare',
    'instagram' => 'instagram'
);

/*-----------------------------------------------------------------------------------*/
/*  MD TEXT
/*-----------------------------------------------------------------------------------*/
$textParamArray = array(
    array(
        "type" => "dropdown",
        "heading" => esc_attr__("Alignment", 'massive-dynamic'),
        "param_name" => "md_text_alignment",
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue first last",
        "value" => array(
            esc_attr__("Left", 'massive-dynamic') => "left",
            esc_attr__("Center", 'massive-dynamic') => "center",
            esc_attr__("Right", 'massive-dynamic') => "right",

        ),
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue first",
        'heading' => esc_attr__('Title Line Height', 'massive-dynamic'),
        'param_name' => 'md_text_title_line_height',
        'value' => '40',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "12",
            "max" => "120",
            "prefix" => " px",
            "step" => "1",
        )
    ),

    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "param_name" => "md_text_content_size_separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,

    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue last",
        'heading' => esc_attr__('Description line Height', 'massive-dynamic'),
        'param_name' => 'md_text_desc_line_height',
        'value' => '21',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "12",
            "max" => "120",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue first",
        'heading' => esc_attr__('Title Bottom Space', 'massive-dynamic'),
        'param_name' => 'md_text_title_bottom_space',
        'value' => '10',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "0",
            "max" => "110",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "param_name" => "separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue",
        'heading' => esc_attr__('Separator Bottom Space', 'massive-dynamic'),
        'param_name' => 'md_text_separator_bottom_space',
        'value' => '10',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "0",
            "max" => "110",
            "prefix" => " px",
            "step" => "1",
        ),
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "param_name" => "separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue last",
        'heading' => esc_attr__('Description Bottom Space', 'massive-dynamic'),
        'param_name' => 'md_text_description_bottom_space',
        'value' => '25',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "0",
            "max" => "110",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        'type' => 'md_vc_checkbox',
        "edit_field_class" => $filedClass . "glue first last",
        'heading' => esc_attr__('Separator', 'massive-dynamic'),
        'param_name' => 'md_text_title_separator',
        'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
        'checked' => true,
        "group" => esc_attr__("Design", 'massive-dynamic'),
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top glue",
        "param_name" => "separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue",
        'heading' => esc_attr__('Width', 'massive-dynamic'),
        'param_name' => 'md_text_separator_width',
        'value' => '110',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "1",
            "max" => "600",
            "prefix" => " px",
            "step" => "1",
        ),
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "param_name" => "separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue",
        'heading' => esc_attr__('height', 'massive-dynamic'),
        'param_name' => 'md_text_separator_height',
        'value' => '5',
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "1",
            "max" => "100",
            "prefix" => " px",
            "step" => "1",
        ),
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "param_name" => "separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )
    ),
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Color", 'massive-dynamic'),
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "param_name" => "md_text_separator_color",
        "admin_label" => false,
        "value" => "rgb(0, 255, 153)",
        "opacity" => true,
        "dependency" => array(
            'element' => "md_text_title_separator",
            'value' => array('yes')
        )

    ),//separator color
 
    array(
        "type" => "dropdown",
        "edit_field_class" => $filedClass . "first glue last",
        "heading" => esc_attr__("Title Style", 'massive-dynamic'),
        "param_name" => "md_text_style",
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "value" => array(
            esc_attr__("Solid", 'massive-dynamic') => "solid",
            esc_attr__("Gradient", 'massive-dynamic') => "gradient",
            esc_attr__("Image", 'massive-dynamic') => "image",
        ),
    ),
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue last dont-show",
        "heading" => esc_attr__("Title Color", 'massive-dynamic'),
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "param_name" => "md_text_solid_color",
        "value" => 'rgba(20,20,20,1)',
        "admin_label" => false,
        "opacity" => true,
        "dependency" => array(
            'element' => "md_text_style",
            'value' => array('solid')
        )
    ),
    array(
        "type" => "md_vc_gradientcolorpicker",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Title Gradient", 'massive-dynamic'),
        "param_name" => "md_text_gradient_color",
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        'dependency' => array(
            'element' => "md_text_style",
            'value' => array('gradient')
        ),
        'defaultColor' => (object)array(
            'color1' => '#8702ff',
            'color2' => '#06ff6e',
            'color1Pos' => '0',
            'color2Pos' => '100',
            'angle' => '0'),
    ),
    array(
        'type' => 'attach_image',
        "edit_field_class" => $filedClass . "glue last",
        'heading' => esc_attr__('Title Image', 'massive-dynamic'),
        'param_name' => 'md_text_image_bg',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_style",
            'value' => array('image')
        ),
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue first last dont-show",
        'heading' => esc_attr__('Title Size', 'massive-dynamic'),
        'param_name' => 'md_text_title_size',
        'value' => '32',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "12",
            "max" => "120",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "first glue",
        'heading' => esc_attr__('Letter Spacing', 'massive-dynamic'),
        'param_name' => 'md_text_letter_space',
        'value' => '0',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "-2",
            "max" => "15",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "param_name" => "md_text_letter_space_separator" . ++$separatorCounter,
    ),
    array(
        'type' => 'md_vc_slider',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue",
        'heading' => esc_attr__('Hover Letter Spacing', 'massive-dynamic'),
        'param_name' => 'md_text_hover_letter_space',
        'value' => '0',
        'defaultSetting' => array(
            "min" => "-2",
            "max" => "15",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "glue",
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "param_name" => "md_text_hover_letter_space_separator" . ++$separatorCounter,
    ),
    array(
        "type" => "dropdown",
        "heading" => esc_attr__("Easing", 'massive-dynamic'),
        "param_name" => "md_text_easing",
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue last",
        "value" => array(
            "easeOutCubic" => "cubic-bezier(0.215, 0.61, 0.355, 1)",
            "easeOutBack" => "cubic-bezier(0.175, 0.885, 0.32, 1.275);",
            "easeInOutQuint" => "cubic-bezier(0.86, 0, 0.07, 1);",
            "easeOutCirc" => "cubic-bezier(0.075, 0.82, 0.165, 1);",
        ),
    ),
    array(
        'type' => 'md_vc_checkbox',
        "edit_field_class" => $filedClass . "first glue last dont-show",
        'heading' => esc_attr__('Use Custom font', 'massive-dynamic'),
        'param_name' => 'md_text_use_title_custom_font',
        'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
        'checked' => false,
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top ",
        "param_name" => "md_text_use_title_custom_font_separator" . ++$separatorCounter,
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "admin_label" => false,
        "mb_dependency" => array(
            'element' => "md_text_use_title_custom_font",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'google_fonts',
        'preview' => false,
        "edit_field_class" => $filedClass . "glue last",
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        'param_name' => 'md_text_title_google_fonts',
        'settings' => array(
            'fields' => array(
                'font_family_description' => esc_attr__('Font family', 'massive-dynamic'),
                'font_style_description' => esc_attr__('Font styling', 'massive-dynamic')
            )
        ),
        "mb_dependency" => array(
            'element' => "md_text_use_title_custom_font",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'md_vc_checkbox',
        "edit_field_class" => $filedClass . "first glue last",
        'heading' => esc_attr__('Use Title Slider', 'massive-dynamic'),
        'param_name' => 'md_text_use_title_slider',
        'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
        'checked' => false,
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
    ),
    array(
        "type" => "md_vc_description",
        "param_name" => "md_text_image_color_description",
        "admin_label" => false,
        "value" => '<ul><li>' . esc_attr__('Please note that title image only works in Google Chrome.', 'massive-dynamic') . ' </li></ul>',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_style",
            'value' => array('image')
        ),
    ),
    array(
        "type" => "md_vc_description",
        "param_name" => "md_text_style_description",
        "admin_label" => false,
        "value" => '<ul><li>' . esc_attr__('Please note that title gradient only works in Google Chrome.', 'massive-dynamic') . '</li></ul>',
        "group" => esc_attr__("Title Option", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_style",
            'value' => array('gradient')
        ),
    ),
    array(
        "type" => "dropdown",
        "heading" => esc_attr__("Title Number", 'massive-dynamic'),
        "param_name" => "md_text_number",
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue first last",
        "value" => array(
            esc_attr__("One", 'massive-dynamic') => "1",
            esc_attr__("Two", 'massive-dynamic') => "2",
            esc_attr__("Three", 'massive-dynamic') => "3",
            esc_attr__("Four", 'massive-dynamic') => "4",
            esc_attr__("Five", 'massive-dynamic') => "5",
        ),
        "mb_dependency" => array(
            'element' => "md_text_use_title_slider",
            'value' => array('yes')
        )
    ),
    array(
        "type" => "md_vc_base64_text",
        "edit_field_class" => $filedClass . "glue first last",
        "heading" => esc_attr__("Title 1", 'massive-dynamic'),
        "param_name" => "md_text_title1",
        "admin_label" => false,
        "value" => "pixflow_base64VGV4dCBTaG9ydGNvZGU=", // <= Text Shortcode in Base64
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "mb_dependency" => array(
            'element' => "md_text_use_title_slider",
            'value' => array('yes')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top",
        "param_name" => "md_text_title1_separator" . ++$separatorCounter,
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('2', '3', '4', '5')
        )
    ),
    array(
        "type" => "textarea",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Title 2", 'massive-dynamic'),
        "param_name" => "md_text_title2",
        "admin_label" => false,
        "value" => "Typography Shortcode",
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('2', '3', '4', '5')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top",
        "param_name" => "md_text_title2_separator" . ++$separatorCounter,
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('3', '4', '5')
        )
    ),
    array(
        "type" => "textarea",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Title 3", 'massive-dynamic'),
        "param_name" => "md_text_title3",
        "admin_label" => false,
        "value" => "Typography Shortcode",
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('3', '4', '5')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top",
        "param_name" => "md_text_title3_separator" . ++$separatorCounter,
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('4', '5')
        )
    ),
    array(
        "type" => "textarea",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Title 4", 'massive-dynamic'),
        "param_name" => "md_text_title4",
        "admin_label" => false,
        "value" => "Typography Shortcode",
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('4', '5')
        )
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top",
        "param_name" => "md_text_title4_separator" . ++$separatorCounter,
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('5')
        )
    ),
    array(
        "type" => "textarea",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Title 5", 'massive-dynamic'),
        "param_name" => "md_text_title5",
        "admin_label" => false,
        "value" => "Typography Shortcode",
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_number",
            'value' => array('5')
        )
    ),
    array(
        "type" => "md_vc_description",
        "param_name" => "md_text_title_description",
        "admin_label" => false,
        "value" => esc_attr__("Don't change titles from the inline editor while you are using title slider.", 'massive-dynamic'),
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "mb_dependency" => array(
            'element' => "md_text_use_title_slider",
            'value' => array('yes')
        )
    ),
	  array(
        "type" => "md_vc_description",
        "param_name" => "md_text_title_description",
        "admin_label" => false,
        "value" => esc_attr__("If you choose more than 1 title (Title Slider Mode), you should open text settings panel to edit this shortcode.", 'massive-dynamic'),
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "mb_dependency" => array(
            'element' => "md_text_use_title_slider",
            'value' => array('yes')
        )
    ),
    array(
        "type" => "md_vc_description",
        "param_name" => "md_text_title_description2",
        "admin_label" => false,
        "value" => esc_attr__("To break the text, you can use Enter key in textarea", 'massive-dynamic'),
        "group" => esc_attr__("Titles", 'massive-dynamic'),
        "mb_dependency" => array(
            'element' => "md_text_use_title_slider",
            'value' => array('yes')
        )
    ),

	  array(
        "type" => "textarea_html",
        "heading" => esc_attr__("Description", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue first last dont-show",
        "param_name" => "content",
        "admin_label" => false,
        "value" => wp_kses(  __ ( "<p>Meet the most advanced live website builder on WordPress.</p><p>Featuring latest web technologies,enjoyable UX and the</p><p>most beautiful design trends. Simply drag&drop elements</p>", 'massive-dynamic' ) , array('p'=>array()) ),
        "description" => esc_attr__("Enter your content.", 'massive-dynamic'),
        "group" => esc_attr__("Description", 'massive-dynamic'),
    ),
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue first last dont-show",
        'heading' => esc_attr__('Description Size', 'massive-dynamic'),
        'param_name' => 'md_text_content_size',
        'value' => '14',
        "group" => esc_attr__("Description", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "10",
            "max" => "40",
            "prefix" => " px",
            "step" => "1",
        )
    ),
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "first glue last dont-show",
        "heading" => esc_attr__("Description Color", 'massive-dynamic'),
        "group" => esc_attr__("Description", 'massive-dynamic'),
        "param_name" => "md_text_content_color",
        "value" => 'rgba(20,20,20,1)',
        "admin_label" => false,
        "opacity" => true,

    ),
    array(
        'type' => 'md_vc_checkbox',
        "edit_field_class" => $filedClass . "first glue last dont-show",
        'heading' => esc_attr__('Use Custom Font For Description', 'massive-dynamic'),
        'param_name' => 'md_text_use_desc_custom_font',
        'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
        'checked' => true,
        "group" => esc_attr__("Design", 'massive-dynamic'),
    ),
    array(
        "type" => 'md_vc_separator',
        "edit_field_class" => $filedClass . "stick-to-top",
        "param_name" => "md_text_use_desc_custom_font_separator" . ++$separatorCounter,
        "group" => esc_attr__("Design", 'massive-dynamic'),
        "admin_label" => false,
        "mb_dependency" => array(
            'element' => "md_text_use_desc_custom_font",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'google_fonts',
        'preview' => false,
        "edit_field_class" => $filedClass . "glue last",
        "group" => esc_attr__("Design", 'massive-dynamic'),
        'param_name' => 'md_text_desc_google_fonts',
        'settings' => array(
            'fields' => array(
                'font_family_description' => esc_attr__('Font family', 'massive-dynamic'),
                'font_style_description' => esc_attr__('Font styling', 'massive-dynamic')
            )
        ),
        "mb_dependency" => array(
            'element' => "md_text_use_desc_custom_font",
            'value' => array('yes')
        )
    ),
    array(
        'type' => 'md_vc_checkbox',
        "edit_field_class" => $filedClass . "first glue last",
        'heading' => esc_attr__('Use Button', 'massive-dynamic'),
        'param_name' => 'md_text_use_button',
        'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
        'checked' => false,
        "group" => esc_attr__("Button", 'massive-dynamic'),
    ),
	  array(
        "type" => "md_vc_description",
        "param_name" => "md_title_bottom_space_description",
        "admin_label" => false,
        "value" => esc_attr__("Title bottom space will also affect separator bottom space.", 'massive-dynamic'),
        "group" => esc_attr__("Design", 'massive-dynamic'),
    ),
	//add btn
    array(
        "type" => "dropdown",
        "edit_field_class" => $filedClass . "glue first last",
        "separate" => true,
        "heading" => esc_attr__("Button Style", 'massive-dynamic'),
        "param_name" => "md_text_button_style",
        "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
        "admin_label" => false,
        "value" => array(
            esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
            esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
            esc_attr__("Slide", 'massive-dynamic') => "slide",
            esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
            esc_attr__("Animation", 'massive-dynamic') => "animation",
            esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
            esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
            esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
        ),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn kind
    array(
        "type" => "textfield",
        "edit_field_class" => $filedClass . "glue first",
        "heading" => esc_attr__("Text", 'massive-dynamic'),
        "param_name" => "md_text_button_text",
        "description" => esc_attr__("Button text", 'massive-dynamic'),
        "admin_label" => false,
        "value" => 'READ MORE',
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn text
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_text_separator" . ++$separatorCounter,
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//separator
    array(
        "type" => "md_vc_iconpicker",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
        "param_name" => "md_text_button_icon_class",
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "admin_label" => false,
        "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        ),
        'value' => 'icon-angle-right'
    ),//btn icon
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue first last",
        "heading" => esc_attr__("General Color", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_color",
        "admin_label" => false,
        "opacity" => true,
        "value" => "rgba(0,0,0,1)",
        "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn general color
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_color_separator" . ++$separatorCounter,
        "edit_field_class" => $filedClass . "stick-to-top",
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
        ),
    ),//separator
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue ",
        "heading" => esc_attr__("Text Color", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_text_color",
        "admin_label" => false,
        "opacity" => true,
        "value" => "rgba(255,255,255,1)",
        "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('fill-oval', 'fill-rectangle'),
        ),
    ),//btn text color
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_color_separator" . ++$separatorCounter,
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('fill-oval', 'fill-rectangle'),
        ),
    ),//separator
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue",
        "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_bg_hover_color",
        "admin_label" => false,
        "value" => "rgb(0,0,0)",
        "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('fill-oval', 'fill-rectangle'),
        ),

    ),//btn bg hover color
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_color_separator" . ++$separatorCounter,
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('fill-oval', 'fill-rectangle')
        )
    ),//separator
    array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_hover_color",
        "admin_label" => false,
        "value" => "rgb(255,255,255)",
        "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_button_style",
            'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
        ),

    ),//btn text hover color
    array(
        "type" => "dropdown",
        "edit_field_class" => $filedClass . "glue first",
        "heading" => esc_attr__("Button size", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_size",
        "admin_label" => false,
        "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
        "value" => array(
            esc_attr__("Standard", 'massive-dynamic') => "standard",
            esc_attr__("Small", 'massive-dynamic') => "small"
        ),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn size
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_size_separator" . ++$separatorCounter,
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//separator
    array(
        'type' => 'md_vc_slider',
        "edit_field_class" => $filedClass . "glue last",
        'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
        'param_name' => 'left_right_padding',
        'value' => '0',
        "group" => esc_attr__("Button", 'massive-dynamic'),
        'defaultSetting' => array(
            "min" => "0",
            "max" => "300",
            "prefix" => " px",
            "step" => "1",
        ),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//spacing
    array(
        "type" => "textfield",
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "edit_field_class" => $filedClass . "glue first",
        "heading" => esc_attr__("Link URL", 'massive-dynamic'),
        "param_name" => "md_text_button_url",
        "admin_label" => false,
        "value" => "#",
        "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn url
    array(
        "type" => 'md_vc_separator',
        "param_name" => "md_text_button_linkr_separator" . ++$separatorCounter,
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//separator
    array(
        "type" => "dropdown",
        "edit_field_class" => $filedClass . "glue last",
        "heading" => esc_attr__("Link's target", 'massive-dynamic'),
        "group" => esc_attr__("Button", 'massive-dynamic'),
        "param_name" => "md_text_button_target",
        "admin_label" => false,
        "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
        "value" => array(
            esc_attr__("Open in same window", 'massive-dynamic') => "_self",
            esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
        ),
        "dependency" => array(
            'element' => "md_text_use_button",
            'value' => array('yes')
        )
    ),//btn target

);
pixflow_map(
    array(
        "name" => "Text",
        "base" => "md_text",
        "category" => esc_attr__('Basic', 'massive-dynamic'),
        'show_settings_on_create' => false,
        "allowed_container_element" => 'vc_row',
        "params" => $textParamArray
    )
);
pixflow_map(
    array(
        'base' => 'vc_column_text',
        'name' => esc_attr__('Text', 'massive-dynamic'),
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "allowed_container_element" => 'vc_row',
        "params" => $textParamArray
    )
);


/*************** shortcode Empty Space ************************/

pixflow_remove_param('vc_empty_space', 'el_class');

$empty_space_setting = array(
    'name' => esc_attr__("Empty Space", 'massive-dynamic'),
    'show_settings_on_create' => false,
    "category" => esc_attr__("Structure", 'massive-dynamic'),
);

pixflow_add_param("vc_empty_space", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Height", 'massive-dynamic'),
    "param_name" => "height",
    "value" => "100",
    "edit_field_class" => $filedClass . "glue first last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));
/*pixflow_add_param("vc_empty_space", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Laptop Height", 'massive-dynamic'),
    "param_name" => "laptop_height",
    "value" => "100",
    "group" => __('Responsive','massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));
pixflow_add_param("vc_empty_space", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Tablet Portable Height", 'massive-dynamic'),
    "param_name" => "tablet_portrate_height",
    "value" => "100",
    "group" => __('Responsive','massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));
pixflow_add_param("vc_empty_space", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Tablet Landscape Height", 'massive-dynamic'),
    "param_name" => "tablet_landscape_height",
    "value" => "100",
    "group" => __('Responsive','massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));
pixflow_add_param("vc_empty_space", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Mobile Height", 'massive-dynamic'),
    "param_name" => "mobile_height",
    "value" => "100",
    "group" => __('Responsive','massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));*/

pixflow_map_update('vc_empty_space', $empty_space_setting);


/*-----------------------------------------------------------------------------------*/
/*  Display Slider
/*-----------------------------------------------------------------------------------*/

function pixflow_display_slider_param()
{
    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $slide_num_param = 'slide_num';
    $slide_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        /*"Six"   => "6",
        "Seven"   => "7",
        "Eight"   => "8",
        "Nine"   => "9",
        "Ten"   => "10"*/
    );
    $param = array(
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "text_color",
            "admin_label" => false,
            "defaultColor" => '#000'
        ),
        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "text_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last slide_number",
            //"class" => "slide_number",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Slide Number:", 'massive-dynamic'),
            "param_name" => $slide_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue first last",
            'heading' => esc_attr__('Auto Slideshow ? ', 'massive-dynamic'),
            'param_name' => 'device_slider_slideshow',
            "group" => esc_attr__('General', 'massive-dynamic'),
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
        ),
    );
    for ($i = 1; $i <= (int)$slide_num; $i++) {
        $value = array();
        for ($k = $i; $k <= $slide_num; $k++) {
            $value[] = (string)$k;
        }
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "slide_title_" . $i,
            "description" => esc_attr__("Slide Title", 'massive-dynamic'),
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_title_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "slide_description_" . $i,
            "description" => esc_attr__("slide description text", 'massive-dynamic'),
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_description_" . $i . "_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Slide Image", 'massive-dynamic'),
            "param_name" => "slide_image_" . $i,
            "admin_label" => false,
            "description" => esc_attr__("Choose Slide image", 'massive-dynamic'),
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Display Slider", 'massive-dynamic'),
    "base" => "md_display_slider",
    "category" => esc_attr__('Media', 'massive-dynamic'),
    "show_settings_on_create" => false,
    "params" => pixflow_display_slider_param()
));

/*-----------------------------------------------------------------------------------*/
/*  Full width BUTTON
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        "name" => "Full Width Button",
        "base" => "md_full_button",
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,
        "params" => array(
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Button Height', 'massive-dynamic'),
                'param_name' => 'full_button_height',
                'value' => '90',
                'defaultSetting' => array(
                    "min" => "50",
                    "max" => "500",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "full_button_text",
                "admin_label" => false,
                "value" => esc_attr__('Read more', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title Typography", 'massive-dynamic'),
                "param_name" => "full_button_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H3" => "h3",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Text Size', 'massive-dynamic'),
                'param_name' => 'full_button_text_size',
                'value' => '19',
                'defaultSetting' => array(
                    "min" => "10",
                    "max" => "100",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Hover letter Spacing', 'massive-dynamic'),
                'param_name' => 'full_button_hover_letter_spacing',
                'value' => '2',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "15",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "full_button_url",
                "admin_label" => false,
                'value' => '#'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "full_button_target",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Bg Color", 'massive-dynamic'),
                "param_name" => "full_button_bg_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#202020'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "full_button_text_color",
                "admin_label" => false,
                "opacity" => true,
                'value' => '#fff',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "full_button_bg_hover_color",
                "admin_label" => false,
                'value' => '#3E005D'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "full_button_hover_color",
                "admin_label" => false,
                'value' => '#FFF'
            ),
        ),
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Accordion
/*-----------------------------------------------------------------------------------*/

pixflow_map(

    array(
        'name' => esc_attr__('Accordion', 'massive-dynamic'),
        'base' => 'md_accordion',
        'show_settings_on_create' => false,
        'is_container' => true,
        "category" => esc_attr__('Tab', 'massive-dynamic'),
        'description' => esc_attr__('Collapsible content panels', 'massive-dynamic'),
        "as_parent" => array('only' => 'md_accordion_tab'),
        'params' => array(
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Appearance", 'massive-dynamic'),
                "param_name" => "theme_style",
                "description" => esc_attr__("Choose one theme style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("With Border", 'massive-dynamic') => "with_border",
                    esc_attr__("Without Border", 'massive-dynamic') => "without_border"
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "heading_size_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Main Color", 'massive-dynamic'),
                "param_name" => "main_color",
                "admin_label" => false,
                "value" => 'rgb(0,0,0)',
                "description" => esc_attr__("Choose a color for Border,Icon,Arrow and Title", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "main_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "hover_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => 'rgb(220,220,220)',
                "description" => esc_attr__("Choose a color for hover", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "hover_color_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Extra class name', 'massive-dynamic'),
                'param_name' => 'el_class',
                'description' => esc_attr__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "el_class_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Active section', 'massive-dynamic'),
                'param_name' => 'active_tab',
                'value' => '1',
                'description' => esc_attr__('Enter section number to be active on load or enter "false" to collapse all sections.', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "accordion_description",
                "admin_label" => false,
                "value" => esc_attr__("This is the general setting for accordion shortcode. Each tab has a unique setting icon, click on it and customize them separately.", 'massive-dynamic')
            )
        ),
        'custom_markup' => '
        <div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">

        </div>
        <div class="tab_controls">
            <a class="add_tab" title="' . esc_attr__('Add section', 'massive-dynamic') . '"><span class="vc_icon"></span> <span class="tab-label">' . esc_attr__('Add section', 'massive-dynamic') . '</span></a>
        </div>
        ',
        'default_content' => '
        [md_accordion_tab title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_accordion_tab]
        [md_accordion_tab title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_accordion_tab]
        ',
        'js_view' => 'MdAccordionView'
    )
);
if (class_exists('WPBakeryShortCodesContainer')) {
    class Pixflow_WPBakeryShortCode_Md_Accordion extends WPBakeryShortCodesContainer
    {
        protected $controls_css_settings = 'out-tc vc_controls-content-widget';

        public function __construct($settings)
        {
            parent::__construct($settings);
        }


        public function contentAdmin($atts, $content = null)
        {
            $width = $custom_markup = '';
            $shortcode_attributes = array('width' => '1/1');
            foreach ($this->settings['params'] as $param) {
                if ($param['param_name'] != 'content') {
                    if (isset($param['value']) && is_string($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    } elseif (isset($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    }
                } else if ($param['param_name'] == 'content' && $content == null) {
                    $content = $param['value'];
                }
            }
            extract(shortcode_atts(
                $shortcode_attributes
                , $atts));

            $output = '';

            $elem = $this->getElementHolder($width);

            $inner = '';
            foreach ($this->settings['params'] as $param) {
                $param_value = '';
                $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
                if (is_array($param_value)) {
                    // Get first element from the array
                    reset($param_value);
                    $first_key = key($param_value);
                    $param_value = $param_value[$first_key];
                }
                $inner .= $this->singleParamHtmlHolder($param, $param_value);
            }
            $tmp = '';

            if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
                if ($content != '') {
                    $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
                } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                    $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
                } else {
                    $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
                }
                //$output .= do_shortcode($this->settings["custom_markup"]);
                $inner .= do_shortcode($custom_markup);
            }
            $elem = str_ireplace('%wpb_element_content%', $inner, $elem);
            $output = $elem;

            return $output;
        }
    }
}
/*-----------------------------------------------------------------------------------*/
/*  Accordion Tab
/*-----------------------------------------------------------------------------------*/

pixflow_map(

    array(
        'name' => esc_attr__('Section', 'massive-dynamic'),
        'base' => 'md_accordion_tab',
        "as_child" => array('only' => 'md_accordion'),
        'is_container' => true,
        'content_element' => false,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => esc_attr__('Title', 'massive-dynamic'),
                'edit_field_class' => $filedClass . "glue first",
                'param_name' => 'title',
                'value' => "Section",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "title_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "icon",
                "admin_label" => false,
                "value" => "icon-laptop"
            )
        ),
        'js_view' => 'MdAccordionTabView'
    )
);

if (class_exists('PixflowShortCode')) {
    class Pixflow_PixflowShortCode_Md_Accordion_tab extends WPBakeryShortCode
    {
        protected $controls_css_settings = 'tc vc_control-container';
        protected $controls_list = array('add', 'edit', 'clone', 'delete');
        protected $predefined_atts = array(
            'el_class' => '',
            'width' => '',
            'title' => ''
        );

        public function contentAdmin($atts, $content = null)
        {

            $output = $title = $el_id = '';

            extract(shortcode_atts(array(
                'title' => esc_attr__("Section", 'massive-dynamic'),
                'icon' => 'icon-laptop',
            ), $atts));


            $output = <<<CONTENTEND
            <div data-element_type="md_accordion_tab" class="wpb_md_accordion_tab wpb_content_element wpb_sortable"><div class="vc_controls">
	<div class="vc_controls-tc vc_control-container">
		<a class="vc_control-btn vc_element-name vc_element-move">
				<span class="vc_btn-content"
				      title="Drag to move Section">Section</span>
		</a>
														<a class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"
					   title="Prepend to Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-edit" href="#"
					   title="Edit Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-clone" href="#"
					   title="Clone Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-delete" href="#"
					   title="Delete Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
										</div>
</div><div class="wpb_element_wrapper ">
CONTENTEND;
            $output .= "\n\t\t\t\t" . '<h3 class="wpb_accordion_header ui-accordion-header"><div class="icon_left"><span class="icon ' . $icon . '"></span></div><a href="#' . sanitize_title($title) . '" class="md-accordion-tab-title">' . $title . '</a></h3>';

            $output .= "\n\t\t\t\t" . '<div class="wpb_accordion_content ui-accordion-content vc_clearfix">';
            $output .= ($content == '' || $content == ' ') ? '<div class="wpb_column_container vc_container_for_children vc_empty-container ui-droppable ui-sortable"></div>' : "\n\t\t\t\t" . pixflow_js_remove_wpautop($content);
            $output .= "\n\t\t\t\t" . '</div>';
            $output .= "\n\t\t\t" . '</div> ' . "\n";
            return $output;
        }
    }
}


/*-----------------------------------------------------------------------------------*/
/*  Product Compare
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Product Compare",
        "base" => "md_product_compare",
        "category" => esc_attr__("Commerce", 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",

                "heading" => esc_attr__("Price", 'massive-dynamic'),
                "param_name" => "product_compare_price",
                "description" => esc_attr__("Type your price", 'massive-dynamic'),
                "admin_label" => false,
                "value" => '150',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_price_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Currency", 'massive-dynamic'),
                "param_name" => "product_compare_currency",
                "admin_label" => false,
                "description" => esc_attr__("Choose currency", 'massive-dynamic'),
                "value" => array(
                    esc_attr__('Dollar', 'massive-dynamic') => '$',
                    esc_attr__('Euro', 'massive-dynamic') => '&euro;',
                    esc_attr__('Pound', 'massive-dynamic') => '&pound;'
                )
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",

                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "product_compare_title",
                "value" => 'GENERAL',
                "description" => esc_attr__("Price table heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",

                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "product_compare_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_heading_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Product Summery", 'massive-dynamic'),
                "param_name" => "product_compare_text",
                "value" => "Show your work & create impassive portfolios without knowing any HTML or how to code.",
                "description" => esc_attr__("Text", 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",

                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "product_compare_general_color",
                "value" => '#000000',
                "admin_label" => false
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Image', 'massive-dynamic'),
                'param_name' => 'product_compare_add_image',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
                "group" => esc_attr__("Product Image", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "product_compare_add_image_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Product Image", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_add_image",
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue last",

                'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
                'param_name' => 'product_compare_image',
                "group" => esc_attr__("Product Image", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_add_image",
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Add Button', 'massive-dynamic'),
                'param_name' => 'product_compare_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
                "group" => esc_attr__("Button", 'massive-dynamic')
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "product_compare_button_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "product_compare_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"

                ),
            ),//btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "product_compare_button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => "BUY IT",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_button_text_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//separator
            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Button Icon", 'massive-dynamic'),
                "param_name" => "product_compare_icon_class",
                "value" => "icon-shopcart",
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "product_compare_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => '#000',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),
                "description" => esc_attr__("Choose background color", 'massive-dynamic')
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "product_compare_button_text_color",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "product_compare_button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Button Hover Color", 'massive-dynamic'),
                "param_name" => "product_compare_hover_color",
                "admin_label" => false,
                "value" => '#ffffff',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "product_compare_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//btn hover color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "param_name" => "product_compare_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_button_size_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'product_compare_left_right_padding',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'value' => '0',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),
            ),//space
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Link", 'massive-dynamic'),
                "param_name" => "product_compare_button_url",
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//link
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_button_url_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "product_compare_button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                'dependency' => array(
                    'element' => "product_compare_button",
                    'value' => array('yes')
                ),

            ),//target

        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  List
/*-----------------------------------------------------------------------------------*/
// Generate list shortcode params
function pixflow_list_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $item_num_param = 'list_item_num';
    $item_num = 10;
    $dropDown = array(
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10

    );
    $param = array(
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__('Style', 'massive-dynamic'),
            "edit_field_class" => $filedClass . "first glue last",
            'param_name' => 'list_style',
            'group' => esc_attr__("General", 'massive-dynamic'),
            'value' => array(
                esc_attr__("Number", 'massive-dynamic') => "number",
                esc_attr__("Icon", 'massive-dynamic') => "icon",
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "list_style_separator" . ++$separatorCounter,
            "group" => esc_attr__("General", 'massive-dynamic'),
            "dependency" => array(
                'element' => "list_style",
                'value' => array('icon')
            )
        ),
        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "list_icon_class",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "list_style",
                'value' => array('icon')
            ),
            'value' => 'icon-checkmark'
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "list_general_color",
            "value" => '#a3a3a3',
            "admin_label" => false,
            "opacity" => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "list_general_color_separator" . ++$separatorCounter,
            "group" => esc_attr__("General", 'massive-dynamic'),
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "list_hover_color",
            "value" => '#e45d75',
            "admin_label" => false,
            "opacity" => false,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "heading" => esc_attr__("Item Number:", 'massive-dynamic'),
            "param_name" => $item_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
    );

    for ($i = 1; $i <= (int)$item_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $item_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first last glue",
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "heading" => esc_attr__("Item ", 'massive-dynamic') . $i,
            "param_name" => "list_item_" . $i,
            "value" => 'This is text for item' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $item_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

pixflow_map(
    array(
        'base' => 'md_list',
        'name' => esc_attr__('List', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__("more", 'massive-dynamic'),
        "params" => pixflow_list_param()
    )
);


/******************* shortcode row ***************************/
// remove css animation and disable
pixflow_remove_param('vc_row', 'css_animation');
pixflow_remove_param('vc_column', 'css_animation');
pixflow_remove_param('vc_row', 'disable_element');
// remove bg image
pixflow_remove_param('vc_row', 'bg_image');
pixflow_remove_param('vc_row', 'full_height');
pixflow_remove_param('vc_row', 'content_placement');
pixflow_remove_param('vc_row', 'video_bg');
pixflow_remove_param('vc_row', 'video_bg_url');
pixflow_remove_param('vc_row', 'video_bg_parallax');

// remove get class
pixflow_remove_param('vc_row', 'el_class');

// remove get id
pixflow_remove_param('vc_row', 'el_id');

// remove default parallax
pixflow_remove_param('vc_row', 'parallax');

// remove stretch
pixflow_remove_param('vc_row', 'full_width');

// remove default parallax
pixflow_remove_param('vc_row', 'parallax_image');

// remove default css editor
pixflow_remove_param('vc_row', 'css');

// remove columns gap
pixflow_remove_param('vc_row', 'gap');

// remove columns position
pixflow_remove_param('vc_row', 'columns_placement');

// remove equal height
pixflow_remove_param('vc_row', 'equal_height');

// remove Parallax speed
pixflow_remove_param('vc_row', 'parallax_speed_video');
pixflow_remove_param('vc_row', 'parallax_speed_bg');

$row_setting = array(
    "name" => "Row",
    'show_settings_on_create' => false,
    "category" => esc_attr__("Structure", 'massive-dynamic'),
);

pixflow_map_update('vc_row', $row_setting);

$separator_setting = array(
    "'show_settings_on_create" => true,
    "controls" => '',
);

// row spacing - Padding all directions
pixflow_add_param('vc_row', array(
    'type' => 'md_vc_url',
    "weight" => "2",
    "heading" => esc_attr__("URL", 'massive-dynamic'),
    "param_name" => "row_section_id",
    "value" => "",
    'group' => esc_attr__("+URL", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_section_id_description",
    "admin_label" => false,
    "group" => esc_attr__("+URL", 'massive-dynamic'),
    "value" => wp_kses(__("<strong>How to add this row in menu:</strong>
                    <ul>
                        <li>First enter a unique ID in URL field, this ID should not be used for any other row in this page</li>
                        <li>Click on generated URL and copy it, then press save changes button</li>
                        <li>In builder's sidebar, click on Menus, then click on your current menu or add a new one</li>
                        <li>Next, click on Add Items and choose Custom Links</li>
                        <li>Paste the generated URL in URL field and give it a name in Link Text field</li>
                        <li>Click on Add To Menu button and refresh your page</li>
                    </ul>", "massive-dynamic"), array('strong' => array(), 'ul' => array(), 'li' => array()))
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Padding Top", 'massive-dynamic'),
    "param_name" => "row_padding_top",
    "description" => esc_attr__("insert top padding for current row . example : 200 ", 'massive-dynamic'),
    "value" => "45",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Bottom", 'massive-dynamic'),
    "param_name" => "row_padding_bottom",
    "description" => esc_attr__("insert bottom padding for current row . example : 200", 'massive-dynamic'),
    "value" => "45",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Right", 'massive-dynamic'),
    "param_name" => "row_padding_right",
    "description" => esc_attr__("insert Right padding for current row . example : 200", 'massive-dynamic'),
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Left", 'massive-dynamic'),
    "param_name" => "row_padding_left",
    "description" => esc_attr__("insert left padding for current row . example : 200", 'massive-dynamic'),
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

// row spacing Margin only top and bottom

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "edit_field_class" => $filedClass . "glue first",
    "heading" => esc_attr__("Margin Top", 'massive-dynamic'),
    "param_name" => "row_margin_top",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_slider',
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Margin Bottom", 'massive-dynamic'),
    "param_name" => "row_margin_bottom",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "px",
        "step" => '5',
    )
));

// Background color overlay for default state
pixflow_add_param("vc_row", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Color", 'massive-dynamic'),
    "param_name" => "background_color",
    "group" => esc_attr__("BG [color]", 'massive-dynamic'),
    "weight" => "3",
    "opacity" => true,
    "admin_label" => false,
    "description" => esc_attr__("Choose a color to be used as this section's background. Please noticed that background color, has higher priority than background image.", 'massive-dynamic'),
    "value" => "rgba(255,255,255,1)",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('none'),
    )
));

// Background Video for Video state
pixflow_add_param("vc_row", array(
    "type" => "textfield",
    "edit_field_class" => $filedClass . "glue first",
    "heading" => esc_attr__("Webm file URL", 'massive-dynamic'),
    "param_name" => "row_webm_url",
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    "weight" => "3",
    "admin_label" => false,
    'dependency' => array(
        'element' => "row_type",
        'value' => array('video'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    "weight" => "3",
    "param_name" => "row_webm_url_separator" . ++$separatorCounter,
    "dependency" => array(
        'element' => "row_type",
        'value' => array('video')
    )
));

pixflow_add_param("vc_row", array(
    "type" => "textfield",
    "edit_field_class" => $filedClass . "glue",
    "heading" => esc_attr__("MP4 file URL", 'massive-dynamic'),
    "param_name" => "row_mp4_url",
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    "weight" => "3",
    "admin_label" => false,
    'dependency' => array(
        'element' => "row_type",
        'value' => array('video'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    "weight" => "3",
    "param_name" => "row_poster_url_separator" . ++$separatorCounter,
    "dependency" => array(
        'element' => "row_type",
        'value' => array('video')
    )
));

pixflow_add_param("vc_row", array(
    "type" => "attach_image",
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Video Preview Image", 'massive-dynamic'),
    "param_name" => "row_poster_url",
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    "weight" => "3",
    "admin_label" => false,
    'dependency' => array(
        'element' => "row_type",
        'value' => array('video'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_video_description",
    "admin_label" => false,
    "group" => esc_attr__("BG [video]", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('video'),
    ),
    "value" => "You should add a URL to videos in related fields. These URLs should either end with .mp4 or .webm . Video preview image will be shown when the video is not loaded yet.",
));

// Background color overlay for image state
pixflow_add_param("vc_row", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Color", 'massive-dynamic'),
    "param_name" => "background_color_image",
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "weight" => "3",
    "opacity" => true,
    "admin_label" => false,
    "value" => "rgba(0,0,0,0.2)",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "weight" => "3",
    "param_name" => "row_bg_tab_separator" . ++$separatorCounter,
    "dependency" => array(
        'element' => "row_type",
        'value' => array('image')
    )
));

// Color Transition

pixflow_add_param("vc_row", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue first",
    "heading" => esc_attr__("Starting Color", 'massive-dynamic'),
    "param_name" => "first_color",
    "group" => esc_attr__("BG [Transition]", 'massive-dynamic'),
    "weight" => "3",
    "opacity" => false,
    "admin_label" => false,
    "description" => esc_attr__("Choose a second color as destination color for row background..", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('transition'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [Transition]", 'massive-dynamic'),
    "weight" => "3",
    "param_name" => "row_bg_tab_separator" . ++$separatorCounter,
    "dependency" => array(
        'element' => "row_type",
        'value' => array('transition')
    )
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue last",

    "heading" => esc_attr__("Destination Color", 'massive-dynamic'),
    "param_name" => "second_color",
    "group" => esc_attr__("BG [Transition]", 'massive-dynamic'),
    "weight" => "3",
    "opacity" => false,
    "admin_label" => false,
    "description" => esc_attr__("Choose a second color as destination color for row background..", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('transition'),
    )
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_transition_description",
    "admin_label" => false,
    "group" => esc_attr__("BG [Transition]", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('transition'),
    ),
    "value" => esc_attr__("To see color transition correctly, it's better to have a row with great height. Try adding several elements inside same row.", 'massive-dynamic'),
));

// Gradient

pixflow_add_param("vc_row", array(
    "type" => "md_vc_gradientcolorpicker",
    "edit_field_class" => $filedClass . "glue first",
    "heading" => esc_attr__("Gradient", 'massive-dynamic'),
    "param_name" => "row_gradient_color",
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "weight" => "3",
    "description" => esc_attr__("Choose a color to be used as this section's background. Please notice that background color, has higher priority than background image.", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
    'defaultColor' => (object)array(
        'color1' => '#fff',
        'color2' => 'rgba(255,255,255,0)',
        'color1Pos' => '0',
        'color2Pos' => '100',
        'angle' => '0'),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "param_name" => "row_bg_tab_separator" . ++$separatorCounter,
    "weight" => "3",
    "admin_label" => false,
    "dependency" => array(
        'element' => "row_type",
        'value' => array('gradient')
    )
));

// Select image

pixflow_add_param("vc_row", array(
    'type' => 'attach_image',
    "edit_field_class" => $filedClass . "glue",
    'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
    'param_name' => 'row_image',
    'description' => esc_attr__('choose image from media library.', 'massive-dynamic'),
    "value" => "",
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "weight" => "3",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image')
    ),
));

pixflow_add_param("vc_row", array(
    'type' => 'attach_image',
    "edit_field_class" => $filedClass . "glue",
    'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
    'param_name' => 'row_image_gradient',
    'description' => esc_attr__('choose image from media library.', 'massive-dynamic'),
    "value" => "",
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "weight" => "3",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "param_name" => "row_image_separator" . ++$separatorCounter,
    "weight" => "3",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image')
    ),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "param_name" => "row_image_separator" . ++$separatorCounter,
    "weight" => "3",
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
));

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "heading" => esc_attr__("Image Position", 'massive-dynamic'),
    "param_name" => "row_image_position",
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "weight" => "3",
    "edit_field_class" => $filedClass . "glue last",
    "value" => array(
        esc_attr__("Fit to row", 'massive-dynamic') => "default",
        esc_attr__("Top", 'massive-dynamic') => "top",
        esc_attr__("Bottom", 'massive-dynamic') => "bottom",
    ),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image')
    ),
));

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "weight" => "3",
    "heading" => esc_attr__("Image Position", 'massive-dynamic'),
    "param_name" => "row_image_position_gradient",
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue last",
    "value" => array(
        esc_attr__("Fit to row", 'massive-dynamic') => "fit",
        esc_attr__("Top", 'massive-dynamic') => "top",
        esc_attr__("Bottom", 'massive-dynamic') => "bottom",
    ),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
));


// ***** row - general - tab *****

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "weight" => "5",
    "heading" => esc_attr__("Row Background", 'massive-dynamic'),
    "param_name" => "row_type",
    "description" => esc_attr__("Choose different type of containers and set the options.", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first last",
    "value" => array(
        esc_attr__("Solid Color", 'massive-dynamic') => "none",
        esc_attr__("Image", 'massive-dynamic') => "image",
        esc_attr__("Color Transition", 'massive-dynamic') => "transition",
        esc_attr__("Gradient and Image", 'massive-dynamic') => "gradient",
        esc_attr__("Video", 'massive-dynamic') => "video",
    ),
));

// Background Image Size On mage Tab
pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "edit_field_class" => $filedClass . "glue last first",
    "heading" => esc_attr__("Image Size", 'massive-dynamic'),
    "param_name" => "row_bg_image_size_tab_image",
    "description" => esc_attr__("Enable Image Size", 'massive-dynamic'),
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Stretch", 'massive-dynamic') => "cover",
        esc_attr__("Real Size", 'massive-dynamic') => "auto",
        esc_attr__("Fit To Height", 'massive-dynamic') => "contain",
    ),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image')
    ),
));

// Background Image Size On Gradient Tab
pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "edit_field_class" => $filedClass . "glue last first",
    "heading" => esc_attr__("Image Size", 'massive-dynamic'),
    "param_name" => "row_bg_image_size_tab_gradient",
    "description" => esc_attr__("Enable Image Size", 'massive-dynamic'),
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Stretch", 'massive-dynamic') => "cover",
        esc_attr__("Real Size", 'massive-dynamic') => "auto",
        esc_attr__("Fit To Height", 'massive-dynamic') => "contain",
    ),
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
));

// Background width

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "weight" => "4",
    "edit_field_class" => $filedClass . "first glue",

    "heading" => esc_attr__("Background Width", 'massive-dynamic'),
    "param_name" => "type_width",
    "description" => esc_attr__("Full width will use all of your screen width, while Boxed will created an invisible box in middle of your screen.", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Full Screen", 'massive-dynamic') => "full_size",
        esc_attr__("Container", 'massive-dynamic') => "box_size",
    )
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "weight" => "4",
    "param_name" => "row_bg_tab_separator" . ++$separatorCounter,
));

// Content width

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "weight" => "4",
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Content Width", 'massive-dynamic'),
    "param_name" => "box_size_states",
    "description" => esc_attr__("Full width will use all of your screen width, while Boxed will created an invisible box in middle of your screen.", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Container", 'massive-dynamic') => "content_box_size",
        esc_attr__("Full Screen", 'massive-dynamic') => "content_full_size",
    )
));


// Inner shadow

pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "weight" => "3",
    "edit_field_class" => $filedClass . "glue first last",
    "param_name" => "row_inner_shadow",
    "heading" => esc_attr__('Inner shadow', 'massive-dynamic'),
    'checked' => false,
    'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

// Sloped Row Edges

pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue first last",
    "param_name" => "row_sloped_edge",
    "heading" => esc_attr__('Sloped Edge', 'massive-dynamic'),
    'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
    'checked' => false,
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "param_name" => "row_sloped_edge" . ++$separatorCounter,
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Slope Position", 'massive-dynamic'),
    "param_name" => "row_slope_edge_position",
    "description" => esc_attr__("Choose to have sloped edge on top, down or both position of your row.", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Top of Row", 'massive-dynamic') => "top",
        esc_attr__("Bottom of Row", 'massive-dynamic') => "bottom",
        esc_attr__("Top and Bottom", 'massive-dynamic') => "both",
    ),
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "param_name" => "row_sloped_edge" . ++$separatorCounter,
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Sloped Edge Color", 'massive-dynamic'),
    "param_name" => "row_sloped_edge_color",
    "admin_label" => false,
    "opacity" => false,
    "description" => esc_attr__("Enter sloped edge color.", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "param_name" => "row_sloped_edge" . ++$separatorCounter,
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

pixflow_add_param("vc_row", array(
    'type' => 'dropdown',
    "edit_field_class" => $filedClass . "glue last",
    'heading' => esc_attr__('Slope Angle', 'massive-dynamic'),
    'param_name' => 'row_sloped_edge_angle',
    'description' => esc_attr__('Set the slope angle', 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "value" => array(
        "-3 " . esc_attr__('degree', 'massive-dynamic') => "-3",
        "-2 " . esc_attr__('degree', 'massive-dynamic') => "-2",
        "-1 " . esc_attr__('degree', 'massive-dynamic') => "-1",
        "0 " . esc_attr__('degree', 'massive-dynamic') => "0",
        "1 " . esc_attr__('degree', 'massive-dynamic') => "1",
        "2 " . esc_attr__('degree', 'massive-dynamic') => "2",
        "3 " . esc_attr__('degree', 'massive-dynamic') => "3",
    ),
    "group" => esc_attr__("Design", 'massive-dynamic'),
));

// Row parallax

pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "last glue first",
    "heading" => esc_attr__("Set Parallax", 'massive-dynamic'),
    "param_name" => "parallax_status",
    "description" => esc_attr__("Parallax enable or disable.", 'massive-dynamic'),
    'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'no'),
    "group" => esc_attr__("Design", 'massive-dynamic'),
    'checked' => true,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "param_name" => "row_bg_tab_separator" . ++$separatorCounter,
    "group" => esc_attr__("Design", 'massive-dynamic'),
    "dependency" => array(
        'element' => "parallax_status",
        'value' => array('yes')
    )
));

pixflow_add_param("vc_row", array(
    'type' => 'md_vc_slider',
    "edit_field_class" => $filedClass . "glue last",
    'heading' => esc_attr__('Parallax Speed', 'massive-dynamic'),
    'param_name' => 'parallax_speed',
    'description' => esc_attr__('Set controllers for image parallax', 'massive-dynamic'),
    "group" => esc_attr__("Design", 'massive-dynamic'),
    'dependency' => array(
        'element' => "parallax_status",
        'value' => array('yes')
    ),
    'defaultSetting' => array(
        "min" => "1",
        "max" => "10",
        "prefix" => " / 10",
        "step" => '1',
    )
));

pixflow_add_param("vc_row", array(
    "type" => "textfield",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Extra Class Name", 'massive-dynamic'),
    "param_name" => "el_class",
    "description" => esc_attr__("Enable fit to height feature", 'massive-dynamic')
));


// Fit to screen
pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Fit To Screen", 'massive-dynamic'),
    "param_name" => "row_fit_to_height",
    "description" => esc_attr__("Enable fit to height feature", 'massive-dynamic'),
    'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'no'),
    'checked' => false,
));

pixflow_add_param("vc_row", array(
    "type" => 'md_vc_separator',
    "edit_field_class" => $filedClass . "stick-to-top",
    "param_name" => "row_vertical_align_separator" . ++$separatorCounter,
    'dependency' => array(
        'element' => "row_fit_to_height",
        'value' => array('yes')
    ),
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "last glue",
    "heading" => esc_attr__("Centered Content", 'massive-dynamic'),
    "param_name" => "row_vertical_align",
    "description" => esc_attr__("Enable vertical align feature", 'massive-dynamic'),
    'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'no'),
    'checked' => false,
    'dependency' => array(
        'element' => "row_fit_to_height",
        'value' => array('yes')
    ),
));

// Equal Column Heigh
pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Equalize Column's Height", 'massive-dynamic'),
    "param_name" => "row_equal_column_heigh",
    'value' => array(esc_attr__('Enable', 'massive-dynamic') => 'no'),
    'checked' => false,
));

// Content Vertical Align
pixflow_add_param("vc_row", array(
    "type" => "dropdown",
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Element's Vertical Position", 'massive-dynamic'),
    "param_name" => "row_content_vertical_align",
    "value" => array(
        esc_attr__('None', 'massive-dynamic') => "0",
        esc_attr__('Top', 'massive-dynamic') => "top",
        esc_attr__('Middle', 'massive-dynamic') => "middle",
        esc_attr__('Bottom', 'massive-dynamic') => "bottom",
    )
));

// Background Repeat for Image
pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue last first",
    "heading" => esc_attr__("Repeat Image", 'massive-dynamic'),
    "param_name" => "row_bg_repeat_image_gp",
    "description" => esc_attr__("Enable repeat background", 'massive-dynamic'),
    "group" => esc_attr__("BG [Image]", 'massive-dynamic'),
    'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
    'checked' => false,
    'dependency' => array(
        'element' => "row_type",
        'value' => array('image')
    ),
));

// Background Repeat for gradient
pixflow_add_param("vc_row", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue last first",
    "heading" => esc_attr__("Repeat Image", 'massive-dynamic'),
    "param_name" => "row_bg_repeat_gradient_gp",
    "description" => esc_attr__("Enable repeat background", 'massive-dynamic'),
    "group" => esc_attr__("BG [Gradient]", 'massive-dynamic'),
    'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
    'checked' => false,
    'dependency' => array(
        'element' => "row_type",
        'value' => array('gradient')
    ),
));



// Row description

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_parallax_description",
    "admin_label" => false,
    "group" => esc_attr__("Design", 'massive-dynamic'),
    'dependency' => array(
        'element' => "parallax_status",
        'value' => array('yes')
    ),
    "value" => esc_attr__("Speed 1 is the slowest and 10 is fastest. For faster parallax speed, you need a taller image, otherwise the background image will keep repeating.", 'massive-dynamic')
));

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_sloped_edge_description",
    "admin_label" => false,
    "group" => esc_attr__("Design", 'massive-dynamic'),
    'dependency' => array(
        'element' => "row_sloped_edge",
        'value' => array('yes')
    ),
    "value" => esc_attr__("Please note that sloped edge does not work with color transition and video background.", 'massive-dynamic')
));

// Row description

pixflow_add_param("vc_row", array(
    "type" => "md_vc_description",
    "param_name" => "row_type_width_description",
    "admin_label" => false,
    "value" => wp_kses(__("<ul>
                        <li>When you change Row Background, you can choose the related options in BG tab.</li>
                        <li>Container size can be set from Site Content > Main Layout > Container Width</li>
                        <li>Full Screen size will ignore the container width and get the same width as user's screen</li>
                        <li>Fit To Screen option increases the row height to same height of user's screen, it's a great choice for first row.</li>
                        <li>Centered Content will only appear if you choose Fit To Screen, it will move(vertically) all columns of current row to center of the row. Also it will ignore top padding and bottom padding.</li>
                        <li>Element's Vertical Position gives you the option to arrange elements based on the highest column in this row.</li>
                    </ul>", 'massive-dynamic'), array('ul' => array(), 'li' => array()))
));

// VC shortcodes update

pixflow_map_update('vc_facebook', array(
    "weight" => '-1',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_tweetmeme', array(
    "weight" => '-2',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_googleplus', array(
    "weight" => '-3',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_pinterest', array(
    "weight" => '-4',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_gmaps', array(
    "weight" => '-6',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_round_chart', array(
    "weight" => '-7',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));

pixflow_map_update('vc_line_chart', array(
    "weight" => '-7',
    "category" => esc_attr__('Business', 'massive-dynamic'),
));


/*-----------------------------------------------------------------------------------*/
/*  MD BUTTON
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        "name" => "Button",
        "base" => "md_button",
        "category" => esc_attr__('Basic', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,
        "params" => array(
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => esc_attr__('Read more', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_text_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-Layers'
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "button_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#000'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "button_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
                'value' => '#FFF'
            ),

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                "param_name" => "button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_size_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'left_right_padding',
                'value' => '0',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "button_align",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Center", 'massive-dynamic') => "center",
                    esc_attr__("Right", 'massive-dynamic') => 'right'
                ),

            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "button_url",
                "admin_label" => false,
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                'value' => '#'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_linkr_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
            ),

        ),
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Call to action
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Call To Action",
        "show_settings_on_create" => false,
        "base" => "md_call_to_action",
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "call_to_action_title",
                "description" => esc_attr__("Call to action heading text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => "Are you looking for job?"
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "call_to_action_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title Color", 'massive-dynamic'),
                "param_name" => "call_to_action_heading_color",
                "admin_label" => false,
                "description" => esc_attr__("Choose title color", 'massive-dynamic'),
                "value" => 'rgb(255,255,255)',
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "call_to_action_description",
                "description" => esc_attr__("Call to action description text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => esc_attr__("We are a fairly small, flexible design studio that designs for print and web. We work flexibly with Send us your resume and portfolio", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Description Color", 'massive-dynamic'),
                "param_name" => "call_to_action_description_color",
                "admin_label" => false,
                "description" => esc_attr__("Choose description color", 'massive-dynamic'),
                "value" => 'rgb(255,255,255)',
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Background Type", 'massive-dynamic'),
                "param_name" => "call_to_action_background_type",
                "description" => esc_attr__("Choose between color or image background", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Background', 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Color", 'massive-dynamic') => "color_background",
                    esc_attr__("Image", 'massive-dynamic') => "image_background",
                    esc_attr__("Transparent", 'massive-dynamic') => "transparent",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "call_to_action_bg_separator" . ++$separatorCounter,
                "group" => esc_attr__('Background', 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "call_to_action_background_type",
                    'value' => array('color_background', 'image_background')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "call_to_action_background_color",
                "admin_label" => false,
                "description" => esc_attr__("Choose background color", 'massive-dynamic'),
                "group" => esc_attr__('Background', 'massive-dynamic'),
                "value" => 'rgb(37,37,37)',
                "dependency" => array(
                    'element' => "call_to_action_background_type",
                    'value' => array('color_background')
                )
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Background Image", 'massive-dynamic'),
                "param_name" => "call_to_action_background_image",
                "admin_label" => false,
                "description" => esc_attr__("Choose background image", 'massive-dynamic'),
                "group" => esc_attr__('Background', 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "call_to_action_background_type",
                    'value' => array('image_background')
                )
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "call_to_action_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "call_to_action_button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "admin_label" => false,
                "value" => "READ MORE",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "call_to_action_button_icon_class",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-chevron-right'
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "call_to_action_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'call_to_action_left_right_padding',
                'value' => '0',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
            ),


            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "call_to_action_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => 'rgb(255,255,255)',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "description" => esc_attr__("Choose background color", 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "stick-to-top",
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "call_to_action_button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "call_to_action_button_bg_hover_color",
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "call_to_action_button_hover_color",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "call_to_action_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
                'value' => '#FFF'
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "call_to_action_button_url",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "call_to_action_title_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "call_to_action_button_target",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
            ),
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Toggle
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        'name' => esc_attr__('Toggle', 'massive-dynamic'),
        'base' => 'md_toggle',
        'show_settings_on_create' => false,
        'is_container' => true,
        "category" => esc_attr__('Tab', 'massive-dynamic'),
        'description' => esc_attr__('Collapsible content panels', 'massive-dynamic'),
        "as_parent" => array('only' => 'md_toggle_tab'),
        'params' => array(

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Theme Style", 'massive-dynamic'),
                "param_name" => "theme_style",
                "description" => esc_attr__("Choose one theme style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("With Border", 'massive-dynamic') => "with_border",
                    esc_attr__("Without Border", 'massive-dynamic') => "without_border"
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "theme_style_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Main Color", 'massive-dynamic'),
                "param_name" => "main_color",
                "admin_label" => false,
                "value" => "rgb(0,0,0)",
                "description" => esc_attr__("Choose a color for Border,Icon,Arrow and Title", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "main_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "hover_color",
                "value" => "rgb(220,220,220)",
                "opacity" => false,
                "admin_label" => false,
                "description" => esc_attr__("Choose a color for hover", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "hover_color_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Extra class name', 'massive-dynamic'),
                'param_name' => 'el_class',
                'description' => esc_attr__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "el_class_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_attr__('Active section', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue",
                'param_name' => 'active_tab',
                "value" => "1",
                'description' => esc_attr__('Enter section number to be active on load or enter "false" to collapse all sections.', 'massive-dynamic')
            ),

            array(
                "type" => "md_vc_description",
                "edit_field_class" => $filedClass . "glue last",
                "param_name" => "toggle_description_control",
                "admin_label" => false,
                "value" => esc_attr__("Use comma (,) for having multiple active sections. e.g:1,2,3 ", 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "accordion_description",
                "admin_label" => false,
                "value" => esc_attr__("This is the general setting for toggle shortcode. Each tab has a unique setting icon, click on it and customize them separately.", 'massive-dynamic')
            )
        ),
        'custom_markup' => '
        <div class="wpb_toggle_holder wpb_holder clearfix vc_container_for_children">

        </div>
        <div class="tab_controls">
            <a class="add_tab" title="' . esc_attr__('Add section', 'massive-dynamic') . '"><span class="vc_icon"></span> <span class="tab-label">' . esc_attr__('Add section', 'massive-dynamic') . '</span></a>
        </div>
        ',
        'default_content' => '
        [md_toggle_tab title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_toggle_tab]
        [md_toggle_tab title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_toggle_tab]
        ',
        'js_view' => 'MdToggleView'
    )
);
if (class_exists('WPBakeryShortCodesContainer')) {
    class Pixflow_WPBakeryShortCode_Md_Toggle extends WPBakeryShortCodesContainer
    {
        protected $controls_css_settings = 'out-tc vc_controls-content-widget';

        public function __construct($settings)
        {
            parent::__construct($settings);
        }

        public function contentAdmin($atts, $content = null)
        {
            $width = $custom_markup = '';
            $shortcode_attributes = array('width' => '1/1');
            foreach ($this->settings['params'] as $param) {
                if ($param['param_name'] != 'content') {
                    if (isset($param['value']) && is_string($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    } elseif (isset($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    }
                } else if ($param['param_name'] == 'content' && $content == null) {
                    $content = $param['value'];
                }
            }
            extract(shortcode_atts(
                $shortcode_attributes
                , $atts));

            $output = '';

            $elem = $this->getElementHolder($width);

            $inner = '';
            foreach ($this->settings['params'] as $param) {
                $param_value = '';
                $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
                if (is_array($param_value)) {
                    // Get first element from the array
                    reset($param_value);
                    $first_key = key($param_value);
                    $param_value = $param_value[$first_key];
                }
                $inner .= $this->singleParamHtmlHolder($param, $param_value);
            }
            $tmp = '';

            if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
                if ($content != '') {
                    $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
                } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                    $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
                } else {
                    $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
                }
                //$output .= do_shortcode($this->settings["custom_markup"]);
                $inner .= do_shortcode($custom_markup);
            }
            $elem = str_ireplace('%wpb_element_content%', $inner, $elem);
            $output = $elem;

            return $output;
        }
    }
}
/*-----------------------------------------------------------------------------------*/
/*  Toggle Tab
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        'name' => esc_attr__('Section', 'massive-dynamic'),
        'base' => 'md_toggle_tab',
        "as_child" => array('only' => 'md_toggle'),
        'is_container' => true,
        'content_element' => false,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => esc_attr__('Title', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                'param_name' => 'title',
                "value" => "Section",
                'description' => esc_attr__('Enter accordion section title.', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "icon",
                "admin_label" => false,
                "value" => "icon-laptop"
            )
        ),
        'js_view' => 'MdToggleTabView'
    )
);

if (class_exists('PixflowShortCode')) {
    class Pixflow_PixflowShortCode_Md_Toggle_tab extends WPBakeryShortCode
    {
        protected $controls_css_settings = 'tc vc_control-container';
        protected $controls_list = array('add', 'edit', 'clone', 'delete');
        protected $predefined_atts = array(
            'el_class' => '',
            'width' => '',
            'title' => ''
        );

        public function contentAdmin($atts, $content = null)
        {

            $output = $title = $el_id = '';

            extract(shortcode_atts(array(
                'title' => esc_attr__("Section", 'massive-dynamic'),
                'el_id' => '',
                'icon' => 'icon-laptop',
            ), $atts));

            $output = <<<CONTENTEND
            <div data-element_type="md_toggle_tab" class="wpb_md_toggle_tab wpb_content_element wpb_sortable"><div class="vc_controls">
	<div class="vc_controls-tc vc_control-container">
		<a class="vc_control-btn vc_element-name vc_element-move">
				<span class="vc_btn-content"
				      title="Drag to move Section">Section</span>
		</a>
														<a class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"
					   title="Prepend to Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-edit" href="#"
					   title="Edit Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-clone" href="#"
					   title="Clone Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-delete" href="#"
					   title="Delete Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
										</div>
</div><div class="wpb_element_wrapper ">
CONTENTEND;
            $output .= "\n\t\t\t\t" . '<h3 class="wpb_toggle_header ui-accordion-header"><div class="icon_left"><span class="icon ' . $icon . '"></span></div><a href="#' . sanitize_title($title) . '" class="md-toggle-tab-title">' . $title . '</a></h3>';

            $output .= "\n\t\t\t\t" . '<div class="wpb_toggle_content ui-accordion-content vc_clearfix">';
            $output .= ($content == '' || $content == ' ') ? '<div class="wpb_column_container vc_container_for_children vc_empty-container ui-droppable ui-sortable"></div>' : "\n\t\t\t\t" . pixflow_js_remove_wpautop($content);
            $output .= "\n\t\t\t\t" . '</div>';
            $output .= "\n\t\t\t" . '</div> ' . "\n";
            return $output;
        }
    }
}


/*-----------------------------------------------------------------------------------*/
/*  Business Toggle
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        'name' => esc_attr__('Business Toggle', 'massive-dynamic'),
        'base' => 'md_toggle2',
        'show_settings_on_create' => false,
        'is_container' => true,
        "category" => esc_attr__('Tab', 'massive-dynamic'),
        'description' => esc_attr__('Collapsible content panels', 'massive-dynamic'),
        "as_parent" => array('only' => 'md_toggle_tab2'),
        'params' => array(
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Main Color", 'massive-dynamic'),
                "param_name" => "main_color",
                "admin_label" => false,
                "value" => "rgb(0,0,0)",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "main_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "hover_color",
                "value" => "rgb(255,255,255)",
                "opacity" => false,
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "hover_color_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Extra class name', 'massive-dynamic'),
                'param_name' => 'el_class',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "el_class_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_attr__('Active section', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue last",
                'param_name' => 'active_tab',
                "value" => "",
            ),

            array(
                "type" => "md_vc_description",
                "param_name" => "toggle_description_control",
                "admin_label" => false,
                "value" => esc_attr__("Use comma (,) for having multiple active sections. e.g:1,2,3 ", 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "accordion_description",
                "admin_label" => false,
                "value" => esc_attr__("This is the general setting for toggle shortcode. Each tab has a unique setting icon, click on it and customize them separately.", 'massive-dynamic')
            )
        ),
        'custom_markup' => '
        <div class="wpb_toggle_holder wpb_holder clearfix vc_container_for_children">

        </div>
        <div class="tab_controls">
            <a class="add_tab" title="' . esc_attr__('Add section', 'massive-dynamic') . '"><span class="vc_icon"></span> <span class="tab-label">' . esc_attr__('Add section', 'massive-dynamic') . '</span></a>
        </div>
        ',
        'default_content' => '
        [md_toggle_tab2 title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_toggle_tab2]
        [md_toggle_tab2 title="' . esc_attr__('Section', 'massive-dynamic') . '"][/md_toggle_tab2]
        ',
        'js_view' => 'MdToggle2View'
    )
);
if (class_exists('WPBakeryShortCodesContainer')) {
    class Pixflow_WPBakeryShortCode_Md_Toggle2 extends WPBakeryShortCodesContainer
    {
        protected $controls_css_settings = 'out-tc vc_controls-content-widget';

        public function __construct($settings)
        {
            parent::__construct($settings);
        }

        public function contentAdmin($atts, $content = null)
        {
            $width = $custom_markup = '';
            $shortcode_attributes = array('width' => '1/1');
            foreach ($this->settings['params'] as $param) {
                if ($param['param_name'] != 'content') {
                    if (isset($param['value']) && is_string($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    } elseif (isset($param['value'])) {
                        $shortcode_attributes[$param['param_name']] = $param['value'];
                    }
                } else if ($param['param_name'] == 'content' && $content == null) {
                    $content = $param['value'];
                }
            }
            extract(shortcode_atts(
                $shortcode_attributes
                , $atts));

            $output = '';

            $elem = $this->getElementHolder($width);

            $inner = '';
            foreach ($this->settings['params'] as $param) {
                $param_value = '';
                $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
                if (is_array($param_value)) {
                    // Get first element from the array
                    reset($param_value);
                    $first_key = key($param_value);
                    $param_value = $param_value[$first_key];
                }
                $inner .= $this->singleParamHtmlHolder($param, $param_value);
            }
            $tmp = '';

            if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
                if ($content != '') {
                    $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
                } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                    $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
                } else {
                    $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
                }
                $inner .= do_shortcode($custom_markup);
            }
            $elem = str_ireplace('%wpb_element_content%', $inner, $elem);
            $output = $elem;

            return $output;
        }
    }
}
/*-----------------------------------------------------------------------------------*/
/*  Toggle Tab 2 (Business Version)
/*-----------------------------------------------------------------------------------*/
pixflow_map(

    array(
        'name' => esc_attr__('Section', 'massive-dynamic'),
        'base' => 'md_toggle_tab2',
        "as_child" => array('only' => 'md_toggle2'),
        'is_container' => true,
        'content_element' => false,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => esc_attr__('Title', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                'param_name' => 'title',
                "value" => "Section",
                'description' => esc_attr__('Enter accordion section title.', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "icon",
                "admin_label" => false,
                "value" => "icon-empty"
            )
        ),
        'js_view' => 'MdToggleTab2View'
    )
);

if (class_exists('PixflowShortCode')) {
    class Pixflow_PixflowShortCode_Md_Toggle_tab2 extends WPBakeryShortCode
    {
        protected $controls_css_settings = 'tc vc_control-container';
        protected $controls_list = array('add', 'edit', 'clone', 'delete');
        protected $predefined_atts = array(
            'el_class' => '',
            'width' => '',
            'title' => ''
        );

        public function contentAdmin($atts, $content = null)
        {

            $output = $title = $el_id = '';

            extract(shortcode_atts(array(
                'title' => esc_attr__("Section", 'massive-dynamic'),
                'icon' => 'icon-laptop',
            ), $atts));

            $output = <<<CONTENTEND
            <div data-element_type="md_toggle_tab2" class="wpb_md_toggle_tab2 wpb_content_element wpb_sortable"><div class="vc_controls">
	<div class="vc_controls-tc vc_control-container">
		<a class="vc_control-btn vc_element-name vc_element-move">
				<span class="vc_btn-content"
				      title="Drag to move Section">Section</span>
		</a>
														<a class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"
					   title="Prepend to Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-edit" href="#"
					   title="Edit Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-clone" href="#"
					   title="Clone Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
																<a class="vc_control-btn vc_control-btn-delete" href="#"
					   title="Delete Section"><span
							class="vc_btn-content"><span class="icon"></span></span></a>
										</div>
</div><div class="wpb_element_wrapper ">
CONTENTEND;
            $output .= "\n\t\t\t\t" . '<h3 class="wpb_toggle_header ui-accordion-header"><div class="icon_left"><span class="icon ' . $icon . '"></span></div><a href="#' . sanitize_title($title) . '" class="md-toggle-tab2-title">' . $title . '</a></h3>';

            $output .= "\n\t\t\t\t" . '<div class="wpb_toggle_content ui-accordion-content vc_clearfix">';
            $output .= ($content == '' || $content == ' ') ? '<div class="wpb_column_container vc_container_for_children vc_empty-container ui-droppable ui-sortable"></div>' : "\n\t\t\t\t" . pixflow_js_remove_wpautop($content);
            $output .= "\n\t\t\t\t" . '</div>';
            $output .= "\n\t\t\t" . '</div> ' . "\n";
            return $output;
        }
    }
}


/*-----------------------------------------------------------------------------------*/
/*  Icon Box Top
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Icon Box Top",
        "base" => "md_iconbox_top",
        "category" => esc_attr__("more", 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "iconbox_title",
                "value" => "Figure it out",
                "description" => esc_attr__("Iconbox heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "iconbox_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "iconbox_description",
                "value" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
                "description" => esc_attr__("Iconbox description text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "iconbox_alignment",
                "description" => esc_attr__("Choose icnobox alignment", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Center", 'massive-dynamic') => "center",
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Right", 'massive-dynamic') => "right"
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "iconbox_general_color",
                "value" => "#5e5e5e",
                "admin_label" => false,
                "description" => esc_attr__("Choose general color", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "iconbox_icon_color",
                "value" => "rgb(0,0,0)",
                "admin_label" => false,
                "description" => esc_attr__("Choose icon color", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "iconbox_icon",
                "value" => "icon-diamond",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon", 'massive-dynamic')
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last first ",
                'heading' => esc_attr__('Add Button', 'massive-dynamic'),
                'param_name' => 'iconbox_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),// iconbox top add button
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "iconbox_use_button" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "iconbox_button_text",
                "value" => "Read more",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_text_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-snowflake2',
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "iconbox_top_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => '#5e5e5e',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Choose background color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
            ),//iconbox top btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "stick-to-top",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//iconbox top btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "iconbox_button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox top btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//iconbox top btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox top btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "button_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                'value' => '#FFF',
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn text hover color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "param_name" => "iconbox_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn  btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_size_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'left_right_padding',
                'value' => '0',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn space
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Link", 'massive-dynamic'),
                "param_name" => "iconbox_button_url",
                "value" => "#",
                "admin_label" => false,
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_url_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "iconbox_button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox top btn target
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Icon Box Side
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Icon Box Side",
        "base" => "md_iconbox_side",
        "category" => esc_attr__("more", 'massive-dynamic'),
        'show_settings_on_create' => false,
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "iconbox_title",
                "value" => "Figure it out",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Iconbox heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "iconbox_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "iconbox_heading",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H3" => "h3",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "iconbox_description",
                "value" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Iconbox description text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "iconbox_alignment",
                "description" => esc_attr__("Choose icnobox alignment", 'massive-dynamic'),
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Right", 'massive-dynamic') => "right"
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "iconbox_general_color",
                "value" => "#5e5e5e",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose general color", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Icon  Color", 'massive-dynamic'),
                "param_name" => "iconbox_icon_color",
                "value" => "#5e5e5e",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose icon color", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Icon Hover Color", 'massive-dynamic'),
                "param_name" => "iconbox_icon_hover_color",
                "value" => "#FFF",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose icon hover color", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "icon_hover_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "iconbox_icon",
                "value" => "icon-location",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon", 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Icon Background', 'massive-dynamic'),
                'param_name' => 'iconbox_icon_background',
                "group" => esc_attr__('General', 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes')
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Add button  ', 'massive-dynamic'),
                'param_name' => 'iconbox_button',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes')
            ),//iconbox side add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "products_use_button" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "iconbox_button_text",
                "value" => "Read more",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_text_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-snowflake2',
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "iconbox_side_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => '#5e5e5e',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                ),
                "description" => esc_attr__("Choose background color", 'massive-dynamic')
            ),//iconbox side btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "stick-to-top",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//iconbox side btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "button_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                'value' => '#FFF',
                'dependency' => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),

                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn hover text color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "param_name" => "iconbox_button_size",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_size_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'left_right_padding',
                'value' => '0',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn space
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Link", 'massive-dynamic'),
                "param_name" => "iconbox_button_url",
                "value" => "#",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn link
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_url_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "iconbox_button_target",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                'dependency' => array(
                    'element' => "iconbox_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn target
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Icon Box Side 2
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Icon Box Side 2",
        "base" => "md_iconbox_side2",
        "category" => esc_attr__("more", 'massive-dynamic'),
        'show_settings_on_create' => false,
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Sub Title", 'massive-dynamic'),
                "param_name" => "iconbox_side2_title",
                "value" => "Advertisement",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
            ),//small title
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "iconbox_side2_title_big",
                "value" => "Creative Elements",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
            ),//big title
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "iconbox_title_separator" . ++$separatorCounter,
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "iconbox_side2_title_big_heading",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H6" => "h6",
                    "H5" => "h5",
                    "H4" => "h4",
                    "H3" => "h3",
                    "H2" => "h2",
                    "H1" => "h1"
                ),
            ),//heading
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "iconbox_side2_description",
                "value" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Iconbox description text", 'massive-dynamic'),
                "admin_label" => false,
            ),//description

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Side Type", 'massive-dynamic'),
                "param_name" => "iconbox_side2_type",
                "admin_label" => false,
                "group" => esc_attr__("General", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Icon", 'massive-dynamic') => "icon",
                    esc_attr__("Image", 'massive-dynamic') => "image",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "iconbox_title_separator" . ++$separatorCounter,
            ),//separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "iconbox_side2_icon",
                "value" => "icon-ribbon",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_type",
                    'value' => array('icon')
                )
            ),//icon
            array(
                'type' => 'attach_image',
                'edit_field_class' => $filedClass . "glue last",
                'heading' => esc_attr__('Choose Image(s)', 'massive-dynamic'),
                'param_name' => 'iconbox_side2_image',
                "group" => esc_attr__("General", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_type",
                    'value' => array('image')
                )
            ),//Image
            array(
                "type" => "md_vc_description",
                "param_name" => "icobbox_side2_description",
                "admin_label" => false,
                "value" => esc_attr__('Best image size is 50x50 pixels or less. ', 'massive-dynamic'),
                "group" => esc_attr__("General", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_type",
                    'value' => array('image')
                )
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "iconbox_side2_alignment",
                "description" => esc_attr__("Choose icnobox alignment", 'massive-dynamic'),
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Right", 'massive-dynamic') => "right"
                ),
            ),//alignment
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Sub Title Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_small_title_color",
                "value" => "#12be83",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose icon hover color", 'massive-dynamic'),
            ),//small title color
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_general_color",
                "value" => "#000",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose general color", 'massive-dynamic'),
            ),//general color
            array(
                "type" => 'md_vc_separator',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Icon  Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_icon_color",
                "value" => "rgba(0,0,0,.5)",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "opacity" => true,
                "admin_label" => false,
                "description" => esc_attr__("Choose icon color", 'massive-dynamic'),
            ),//icon color
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Add button  ', 'massive-dynamic'),
                'param_name' => 'iconbox_side2_button',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no')
            ),//iconbox side add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "products_use_button" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_text",
                "value" => "Read more",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_text_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "iconbox_side2_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-snowflake2',
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => '#5e5e5e',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                ),
                "description" => esc_attr__("Choose background color", 'massive-dynamic')
            ),//iconbox side btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "stick-to-top",
                "dependency" => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//iconbox side btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "dependency" => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//iconbox side btn separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                'value' => '#FFF',
                'dependency' => array(
                    'element' => "iconbox_side2_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),

                ),
                "group" => esc_attr__('Button', 'massive-dynamic'),
            ),//iconbox side btn hover text color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_size",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_size_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'iconbox_side2_left_right_padding',
                'value' => '0',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn space
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Link", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_url",
                "value" => "#",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn link
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_button_url_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "iconbox_side2_button_target",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                'dependency' => array(
                    'element' => "iconbox_side2_button",
                    'value' => array('yes')
                )
            ),//iconbox side btn target
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Image Box
/*-----------------------------------------------------------------------------------*/

pixflow_map(

    array(
        "name" => "Image Box",
        "base" => "md_image_box_slider",
        "category" => esc_attr__("Basic", 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,

        "params" => array(

            /* Background image tab */

            array(
                'type' => 'attach_images',
                'edit_field_class' => $filedClass . "first glue last",
                'heading' => esc_attr__('Choose Image(s)', 'massive-dynamic'),
                'param_name' => 'image_box_slider_image',
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue first",
                'heading' => esc_attr__('Image Height', 'massive-dynamic'),
                'param_name' => 'image_box_slider_height',
                'value' => '300',
                'defaultSetting' => array(
                    "min" => "100",
                    "max" => "1000",
                    "prefix" => " px",
                    "step" => "10",
                ),

            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_slider_separator" . ++$separatorCounter,
            ),

            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Image Size', 'massive-dynamic'),
                'param_name' => 'image_box_slider_size',
                'checked' => true,
                "value" => array(
                    esc_attr__("Real Size", 'massive-dynamic') => "auto",
                    esc_attr__("Stretch", 'massive-dynamic') => "cover",
                    esc_attr__("Fit Box", 'massive-dynamic') => "contain",
                ),
            ),

            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Hover Effect', 'massive-dynamic'),
                'param_name' => 'image_box_slider_hover',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "image_box_slider_hover_link",
                'value' => '',
                "admin_label" => false,
            ),


            // Slider Effects

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Slider Effect", 'massive-dynamic'),
                "param_name" => "image_box_slider_effect_slider",
                "admin_label" => false,
                "group" => esc_attr__("Slider", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Fade Effect", 'massive-dynamic') => "fade",
                    esc_attr__("Slide Effect", 'massive-dynamic') => "slide",
                ),
            ),


            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_slider_separator" . ++$separatorCounter,
                "group" => esc_attr__("Slider", 'massive-dynamic'),
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Slider Speed', 'massive-dynamic'),
                'param_name' => 'image_box_slider_speed',
                'value' => '3000',
                "group" => esc_attr__("Slider", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "1000",
                    "max" => "5000",
                    "prefix" => " / ms",
                    "step" => "100",
                )
            ),


            // Hover Tab

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Hover Type", 'massive-dynamic'),
                "param_name" => "image_box_slider_hover_effect",
                "admin_label" => false,
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Text", 'massive-dynamic') => "text",
                    esc_attr__("Image", 'massive-dynamic') => "image",
                ),
                "dependency" => Array(
                    'element' => "image_box_slider_hover",
                    'value' => array('yes')
                )
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_slider_separator" . ++$separatorCounter,
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "image_box_slider_hover",
                    'value' => array('yes')
                )
            ),

            array(
                'type' => 'attach_image',
                'edit_field_class' => $filedClass . "glue last",
                'heading' => esc_attr__('Choose Image(s)', 'massive-dynamic'),
                'param_name' => 'image_box_slider_hover_image_sec',
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "image_box_slider_hover_effect",
                    'value' => 'image'
                )
            ),


            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Hover Style", 'massive-dynamic'),
                "param_name" => "image_box_slider_hover_text_effect",
                "admin_label" => false,
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Light", 'massive-dynamic') => "light",
                    esc_attr__("Dark", 'massive-dynamic') => "dark",
                ),
                "dependency" => Array(
                    'element' => "image_box_slider_hover_effect",
                    'value' => 'text'
                )
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_slider_separator" . ++$separatorCounter,
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "image_box_slider_hover_effect",
                    'value' => 'text'
                )
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "image_box_slider_hover_text",
                'value' => 'Text Hover',
                "admin_label" => false,
                "group" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "image_box_slider_hover_effect",
                    'value' => 'text'
                )
            ),


        ),

    )
);

/*-----------------------------------------------------------------------------------*/
/*  Image Box Fancy
/*-----------------------------------------------------------------------------------*/

pixflow_map(

    array(
        "name" => "Image Box Fancy",
        "base" => "md_image_box_fancy",
        "category" => esc_attr__('Media','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,

        "params" => array(

            /* Background image tab */

            array(
                'type' => 'attach_images',
                'edit_field_class' => $filedClass . "first glue last",
                'heading' => esc_attr__('Choose Image(s)', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_image',
            ),
            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "first glue",
                'heading' => esc_attr__('Height', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_height_type',
                "value" => array(
                    esc_attr__("Manual", 'massive-dynamic') => "manual",
                    esc_attr__("Fit to Row Height", 'massive-dynamic') => "fit",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "image_box_fancy_height_type",
                    'value' => 'manual'
                )
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Image Height', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_height',
                'value' => '450',
                'defaultSetting' => array(
                    "min" => "100",
                    "max" => "1000",
                    "prefix" => " px",
                    "step" => "10",
                ),
                "dependency" => array(
                    'element' => "image_box_fancy_height_type",
                    'value' => 'manual'
                )
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
            ),

            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Image Size', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_size',
                'checked' => true,
                "value" => array(
                    esc_attr__("Real Size", 'massive-dynamic') => "auto",
                    esc_attr__("Stretch", 'massive-dynamic') => "cover",
                    esc_attr__("Fit Box", 'massive-dynamic') => "contain",
                ),
            ),

            // Description
            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "first last glue",
                'heading' => esc_attr__('Style', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_style',
                'value' => array(
                    esc_attr__('Normal Overlay', 'massive-dynamic') => 'normal',
                    esc_attr__('Full Overlay', 'massive-dynamic') => 'full',
                ),
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Icon", 'massive-dynamic'),
                "param_name" => "image_box_fancy_icon",
                "value" => "icon-Diamond",
                "admin_label" => false,
                "group" => esc_attr__("Description", 'massive-dynamic'),

            ),//icon
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "image_box_fancy_description_title",
                'value' => 'Fancy Image Box',
                "group" => esc_attr__("Description", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . " glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "image_box_fancy_description_text",
                'value' => 'Massive Dynamic has over 10 years of experience in Design. We take pride in delivering Intelligent Designs and Engaging Experiences for clients all over the World.',
                "group" => esc_attr__("Description", 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "image_box_fancy_icon_color",
                "admin_label" => false,
                "value" => "rgba(0,177,177,1)",
                "opacity" => true,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . " glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "image_box_fancy_text_color",
                "admin_label" => false,
                "value" => "rgba(0,0,0,1)",
                "opacity" => true,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "image_box_fancy_background_color",
                "admin_label" => false,
                "value" => "rgba(255,255,255,1)",
                "opacity" => true,
                "group" => esc_attr__("Description", 'massive-dynamic'),
            ),
            // Slider Effects

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Slider Effect", 'massive-dynamic'),
                "param_name" => "image_box_fancy_effect_slider",
                "admin_label" => false,
                "group" => esc_attr__("Slider", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Fade Effect", 'massive-dynamic') => "fade",
                    esc_attr__("Slide Effect", 'massive-dynamic') => "slide",
                ),
            ),


            array(
                "type" => 'md_vc_separator',
                "param_name" => "image_box_fancy_separator" . ++$separatorCounter,
                "group" => esc_attr__("Slider", 'massive-dynamic'),
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Slider Speed', 'massive-dynamic'),
                'param_name' => 'image_box_fancy_speed',
                'value' => '3000',
                "group" => esc_attr__("Slider", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "1000",
                    "max" => "5000",
                    "prefix" => " / ms",
                    "step" => "100",
                )
            ),



        ),

    )
);

/* -------------------------------------------------------
--------------------------Tab-----------------------------
---------------------------------------------------------*/

$tab_id_1 = ''; // 'def' . time() . '-1-' . rand( 0, 100 );
$tab_id_2 = ''; // 'def' . time() . '-2-' . rand( 0, 100 );
pixflow_map(array(
    "name" => esc_attr__('Tabs', 'massive-dynamic'),
    'base' => 'md_tabs',
    'show_settings_on_create' => false,
    'is_container' => true,
    "category" => esc_attr__("Structure", 'massive-dynamic'),
    'description' => esc_attr__('Tabbed content', 'massive-dynamic'),
    'as_parent' => array('only' => 'md_tab'),
    'params' => array(
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("Title Color", 'massive-dynamic'),
            "param_name" => "title_color",
            "value" => "rgba(255,255,255,1)",
            "opacity" => true,
            "admin_label" => false,
            "description" => esc_attr__("Enter optional tab title's color", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_color_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . " glue",
            "heading" => esc_attr__("Tab Color", 'massive-dynamic'),
            "param_name" => "tab_color",
            "admin_label" => false,
            "value" => "rgba(43,42,40,1)",
            "opacity" => true,
            "description" => esc_attr__("Enter optional tab's color", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "tab_color_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Active Tab Color", 'massive-dynamic'),
            "param_name" => "tab_active_color",
            "value" => "rgba(235,78,1,1)",
            "opacity" => true,
            "admin_label" => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "tab_color_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Content BG Color", 'massive-dynamic'),
            "param_name" => "tabs_background",
            "value" => "rgba(247,247,247,1)",
            "opacity" => true,
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_description",

            "param_name" => "tabs_description",
            "admin_label" => false,
            "value" => esc_attr__("This is the general setting for tab. To customize each tab, click on them, then click on setting icon which appears under them.", 'massive-dynamic')
        ),
    ),
    'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
    'default_content' => '
[md_tab title="' . esc_attr__('TAB', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_tab]
[md_tab title="' . esc_attr__('TAB', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_tab]
',
    'js_view' => 'MdTabsView'
));


pixflow_map(array(
    'name' => esc_attr__('Tab', 'massive-dynamic'),
    'base' => 'md_tab',
    'allowed_container_element' => 'vc_row',
    'is_container' => true,
    'content_element' => false,
    'params' => array(
        array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "glue first",
            'heading' => esc_attr__('Title', 'massive-dynamic'),
            'param_name' => 'title',
            "value" => "TAB",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "tab_icon_class",
            "value" => "icon-cog",
            "admin_label" => false,
        )
    ),
    'js_view' => 'MdTabView'
));
require_once pixflow_path_dir('SHORTCODES_DIR', 'vc-column.php');
if (!class_exists('WPBakeryShortCode_VC_Column')) {
    class WPBakeryShortCode_VC_Column
    {
    }
}

if (!class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode
    {
    }
}

class Pixflow_PixflowShortCode_Md_Tab extends WPBakeryShortCode_VC_Column
{
    protected $controls_css_settings = 'tc vc_control-container';
    protected $controls_list = array('add', 'edit', 'clone', 'delete');
    protected $predefined_atts = array(
        'tab_id' => TAB_TITLE,
        'title' => '',
        'tab_icon_class' => ''
    );
    protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

    public function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function mainHtmlBlockParams($width, $i)
    {
        return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
    }

    public function customAdminBlockParams()
    {
        return ' id="tab-' . $this->atts['tab_id'] . '"';
    }

    public function containerHtmlBlockParams($width, $i)
    {
        return 'class="wpb_column_container vc_container_for_children"';
    }

    public function getColumnControls($controls, $extended_css = '')
    {
        return $this->getColumnControlsModular($extended_css);
    }
}

class Pixflow_PixflowShortCode_Md_Tabs extends WPBakeryShortCode
{
    static $filter_added = false;
    protected $controls_css_settings = 'out-tc vc_controls-content-widget';
    protected $controls_list = array('edit', 'clone', 'delete');

    public function __construct($settings)
    {
        parent::__construct($settings);
        if (!self::$filter_added) {
            $this->addFilter('vc_inline_template_content', 'setCustomTabId');
            self::$filter_added = true;
        }
    }

    public function contentAdmin($atts, $content = null)
    {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ($this->settings['params'] as $param) {
            if ($param['param_name'] != 'content') {
                if (isset($param['value']) && is_string($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                } elseif (isset($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ($param['param_name'] == 'content' && $content == null) {
                $content = $param['value'];
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));

        // Extract tab titles

        preg_match_all('/md_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $output = '';
        $tab_titles = array();

        if (isset($matches[0])) {
            $tab_titles = $matches[0];
        }
        $tmp = '';
        if (count($tab_titles)) {
            $tmp .= '<ul class="clearfix tabs_controls">';
            foreach ($tab_titles as $tab) {
                preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
                if (isset($tab_matches[1][0])) {
                    $tmp .= '<li><a href="#tab-' . (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title($tab_matches[1][0])) . '">' . $tab_matches[1][0] . '</a><span class="' . $tab_icon_class . '" ></span></li>';

                }
            }
            $tmp .= '</ul>' . "\n";
        } else {
            $output .= do_shortcode($content);
        }


        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $custom_markup = '';
            $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
            if (is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }

        if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
            if ($content != '') {
                $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
            } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
            } else {
                $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;
        return $output;
    }

    public function getTabTemplate()
    {
        return '<div class="wpb_template">' . do_shortcode('[md_tab title="TAB" tab_id="" tab_icon_class=""][/md_tab]') . '</div>';
    }

    public function setCustomTabId($content)
    {
        return preg_replace('/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content);
    }
}


/* -------------------------------------------------------
--------------------------Modern Tab-----------------------------
---------------------------------------------------------*/
$tab_id_1 = '';
$tab_id_2 = '';
pixflow_map(array(
    "name" => esc_attr__('Modern Tabs', 'massive-dynamic'),
    'base' => 'md_modernTabs',
    'show_settings_on_create' => false,
    'is_container' => true,
    "category" => esc_attr__("Tab", 'massive-dynamic'),
    'description' => esc_attr__('Tabbed content', 'massive-dynamic'),
    'as_parent' => array('only' => 'md_modernTab'),
    'params' => array(
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "general_color",
            "value" => "rgb(60,60,60)",
            "admin_label" => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_color_separator" . ++$separatorCounter,
        ),

        array(
            'type' => 'dropdown',
            "edit_field_class" => $filedClass . "last glue",
            'heading' => esc_attr__('Auto Rotate', 'massive-dynamic'),
            'param_name' => 'interval',
            'value' => array(esc_attr__('Disable', 'massive-dynamic') => 0, 3, 5, 10, 15),
            'std' => 0,
        ),
        array(
            "type" => "md_vc_description",

            "param_name" => "moderntabs_description",
            "admin_label" => false,
            "value" => esc_attr__("This is the general setting for tab. To customize each tab, click on them, then click on setting icon which appears under them. You can set auto rotation between tabs, for example 3 means after 3 seconds current tab will be changed.", 'massive-dynamic')
        ),
    ),
    'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
    'default_content' => '
[md_modernTab title="' . esc_attr__('TAB', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_modernTab]
[md_modernTab title="' . esc_attr__('TAB', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_modernTab]
',
    'js_view' => 'MdModernTabsView'
));


pixflow_map(array(
    'name' => esc_attr__('Modern Tab', 'massive-dynamic'),
    'base' => 'md_modernTab',
    'allowed_container_element' => 'vc_row',
    'is_container' => true,
    'content_element' => false,
    'params' => array(
        array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "first glue",
            'heading' => esc_attr__('Title', 'massive-dynamic'),
            'param_name' => 'title',
            "value" => "TAB"
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_separator" . ++$separatorCounter,
            "admin_label" => false
        ),

        array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "tab_icon_class",
            "value" => "icon-cog",
            "admin_label" => false
        ),

    ),
    'js_view' => 'MdModernTabView'
));
define('MODERN_TAB_TITLE', '');

class Pixflow_PixflowShortCode_Md_ModernTab extends WPBakeryShortCode_VC_Column
{
    protected $controls_css_settings = 'tc vc_control-container';
    protected $controls_list = array('add', 'edit', 'clone', 'delete');
    protected $predefined_atts = array(
        'tab_id' => MODERN_TAB_TITLE,
        'title' => '',
        'tab_icon_class' => ''
    );
    protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

    public function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function mainHtmlBlockParams($width, $i)
    {
        return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
    }

    public function customAdminBlockParams()
    {
        return ' id="tab-' . $this->atts['tab_id'] . '"';
    }

    public function containerHtmlBlockParams($width, $i)
    {
        return 'class="wpb_column_container vc_container_for_children"';
    }

    public function getColumnControls($controls, $extended_css = '')
    {
        return $this->getColumnControlsModular($extended_css);
    }
}

class Pixflow_PixflowShortCode_Md_ModernTabs extends WPBakeryShortCode
{
    static $filter_added = false;
    protected $controls_css_settings = 'out-tc vc_controls-content-widget';
    protected $controls_list = array('edit', 'clone', 'delete');

    public function __construct($settings)
    {
        parent::__construct($settings);
        if (!self::$filter_added) {
            $this->addFilter('vc_inline_template_content', 'setCustomTabId');
            self::$filter_added = true;
        }
    }

    public function contentAdmin($atts, $content = null)
    {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ($this->settings['params'] as $param) {
            if ($param['param_name'] != 'content') {
                if (isset($param['value']) && is_string($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                } elseif (isset($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ($param['param_name'] == 'content' && $content == null) {
                $content = $param['value'];
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));


        preg_match_all('/md_modernTab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $output = '';
        $tab_titles = array();

        if (isset($matches[0])) {
            $tab_titles = $matches[0];
        }
        $tmp = '';
        if (count($tab_titles)) {
            $tmp .= '<ul class="clearfix tabs_controls">';
            foreach ($tab_titles as $tab) {
                preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
                if (isset($tab_matches[1][0])) {
                    $tmp .= '<li><a href="#tab-' . (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title($tab_matches[1][0])) . '">' . $tab_matches[1][0] . '</a><span class="' . $tab_icon_class . '" ></span></li>';

                }
            }
            $tmp .= '</ul>' . "\n";
        } else {
            $output .= do_shortcode($content);
        }


        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $custom_markup = '';
            $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
            if (is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }

        if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
            if ($content != '') {
                $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
            } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
            } else {
                $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;
        return $output;
    }

    public function getTabTemplate()
    {
        return '<div class="wpb_template">' . do_shortcode('[md_modernTab title="TAB" tab_id="" tab_icon_class=""][/md_modernTab]') . '</div>';
    }

    public function setCustomTabId($content)
    {
        return preg_replace('/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content);
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Imagebox Full-Width
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Image Box Full-Width",
        "base" => "md_imagebox_full",
        "category" => esc_attr__('Media','massive-dynamic'),
        'show_settings_on_create' => false,
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first textNsize-text",

                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => "Products that perform as good as they look",
                "param_name" => "imagebox_title",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Imagebox heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last textNsize-size",

                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "imagebox_heading_size",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Choose your heading size", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H3" => "h3",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "imagebox_description",
                "value" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo. Sed quis augue ut eros tincidunt hendrerit eu eget nisl. Duis malesuada vehicula massa...
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo. Sed quis augue ut eros tincidunt hendrerit eu eget nisl.",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Imagebox description text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "imagebox_general_color",
                "value" => '#ffffff',
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose general color", 'massive-dynamic'),
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("Height", 'massive-dynamic'),
                "value" => "300",
                "param_name" => "imagebox_text_height",
                "group" => esc_attr__('General', 'massive-dynamic'),
                "description" => esc_attr__("Imagebox text height", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "imagebox_alignment",
                "admin_label" => false,
                "group" => esc_attr__('General', 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    "Center" => "center"
                ),
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Background', 'massive-dynamic'),
                'param_name' => 'imagebox_use_background',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
                "group" => esc_attr__("Background", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "imagebox_use_background_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Background", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_use_background",
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue",

                'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
                'param_name' => 'imagebox_background',
                "group" => esc_attr__("Background", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_use_background",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "imagebox_background_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Background", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_use_background",
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Add overlay  ', 'massive-dynamic'),
                'param_name' => 'imagebox_overlay',
                "group" => esc_attr__("Background", 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
                'dependency' => array(
                    'element' => "imagebox_use_background",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "imagebox_overlay_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Background", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_overlay",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "imagebox_overlay_color",
                "value" => 'rgba(90,31,136,0.5)',
                "opacity" => true,
                "group" => esc_attr__("Background", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Choose overlay color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_overlay",
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Add button  ', 'massive-dynamic'),
                'param_name' => 'imagebox_button',
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),//image box full add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "imagebox_button_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "imagebox_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",

                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Text", 'massive-dynamic'),
                "param_name" => "imagebox_button_text",
                "value" => "Read more",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "imagebox_button_text_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full separator
            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "imagebox_button_icon",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                ),
                'value' => 'icon-arrow-right4'
            ),//image box full btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("Button Color", 'massive-dynamic'),
                "param_name" => "imagebox_button_color",
                "admin_label" => false,
                "opacity" => false,
                "value" => '#fff',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => Array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                ),
                "description" => esc_attr__("Choose background color", 'massive-dynamic')
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "imagebox_button_text_color",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "imagebox_button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Button Hover Color", 'massive-dynamic'),
                "param_name" => "imagebox_button_hover_color",
                "admin_label" => false,
                "value" => '#9b9b9b',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "imagebox_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//btn hover color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Size", 'massive-dynamic'),
                "param_name" => "imagebox_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                ),

            ),//size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "product_compare_button_size_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                ),

            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'imagebox_left_right_padding',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'value' => '0',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                ),
            ),//space
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",

                "heading" => esc_attr__("Button Link", 'massive-dynamic'),
                "param_name" => "imagebox_button_url",
                "value" => "#",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "imagebox_button_url_separator" . ++$separatorCounter,
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),//image box full separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "imagebox_button_target",
                "admin_label" => false,
                "group" => esc_attr__('Button', 'massive-dynamic'),
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                'dependency' => array(
                    'element' => "imagebox_button",
                    'value' => array('yes')
                )
            ),////image box full btn target
            array(
                "type" => "md_vc_description",

                "group" => esc_attr__('General', 'massive-dynamic'),
                "param_name" => "imagebox_description_control",
                "admin_label" => false,
                "value" => "You can change description height to display background image better."
            ),


        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Team member Style 1
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Team Member Classic",
        "base" => "md_team_member_classic",
        "category" => esc_attr__('Business','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,

        "params" => array(

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Name", 'massive-dynamic'),
                "param_name" => "team_member_classic_title",
                'value' => 'John Parker!',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Job Title", 'massive-dynamic'),
                "param_name" => "team_member_classic_subtitle",
                'value' => 'writer',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("About Member", 'massive-dynamic'),
                "param_name" => "team_member_classic_description",
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus.',
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "team_member_classic_texts_color",
                "admin_label" => false,
                'value' => '#fff'
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "team_member_classic_hover_color",
                "admin_label" => false,
                "opacity" => true,
                'value' => 'rgba(11, 171, 167, 0.85)'
            ),

            /* Background image tab */

            array(
                'type' => 'attach_image',
                'edit_field_class' => $filedClass . "glue first last",
                'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
                'param_name' => 'team_member_classic_image',
                'group' => esc_attr__('Picture', 'massive-dynamic'),
            ),

            /* Social Icons tab */

            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Social Network 1", 'massive-dynamic'),
                "param_name" => "team_member_social_icon1",
                "group" => esc_attr__("Social Icons", 'massive-dynamic'),
                "admin_label" => false,
                "value" => "icon-facebook2"
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_social_icon1_url",
                "value" => 'http://www.facebook.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Social Network 2", 'massive-dynamic'),
                "param_name" => "team_member_social_icon2",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-twitter5',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_social_icon2_url",
                'value' => 'http://www.twitter.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Social Network 3", 'massive-dynamic'),
                "param_name" => "team_member_social_icon3",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-google',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_social_icon3_url",
                'value' => 'http://www.google.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Social Network 4", 'massive-dynamic'),
                "param_name" => "team_member_social_icon4",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-dribbble',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_social_icon4_url",
                'value' => 'http://www.dribbble.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Social Network 5", 'massive-dynamic'),
                "param_name" => "team_member_social_icon5",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-instagram',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separatorCounter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_social_icon5_url",
                'value' => 'http://www.instagram.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

        ),

    )
);

/*-----------------------------------------------------------------------------------*/
/*  Tablet Slider
/*-----------------------------------------------------------------------------------*/

function pixflow_tablet_slider_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $slide_num_param = 'tablet_slide_num';
    $slide_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        /*"Six"   => "6",
        "Seven"   => "7",
        "Eight"   => "8",
        "Nine"   => "9",
        "Ten"   => "10"*/
    );
    $param = array(
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "first glue",

            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "tablet_slider_text_color",
            "admin_label" => false,
            "defaultColor" => '#000'
        ),
        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "tablet_slider_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last slide_number",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Slide Number:", 'massive-dynamic'),
            "param_name" => $slide_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue first last",
            'heading' => esc_attr__('Auto Slideshow  ', 'massive-dynamic'),
            'param_name' => 'tablet_slider_slideshow',
            "group" => esc_attr__('General', 'massive-dynamic'),
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
        ),
    );

    for ($i = 1; $i <= (int)$slide_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $slide_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",

            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "tablet_slider_slide_title_" . $i,
            "description" => esc_attr__("Slide Title", 'massive-dynamic'),
            "value" => 'Slide' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "tablet_slider_slide_title_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Slide Image", 'massive-dynamic'),
            "param_name" => "tablet_slider_slide_image_" . $i,
            "admin_label" => false,
            "description" => esc_attr__("Choose Slide image", 'massive-dynamic'),
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Tablet Slider", 'massive-dynamic'),
    "base" => "md_tablet_slider",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Media','massive-dynamic'),
    "params" => pixflow_tablet_slider_param()
));


/*-----------------------------------------------------------------------------------*/
/*  Mobile Slider
/*-----------------------------------------------------------------------------------*/

function pixflow_mobile_slider_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $slide_num_param = 'mobile_slide_num';
    $slide_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        /*"Six"   => "6",
        "Seven"   => "7",
        "Eight"   => "8",
        "Nine"   => "9",
        "Ten"   => "10"*/
    );
    $param = array(
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last slide_number",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Slide Number:", 'massive-dynamic'),
            "param_name" => $slide_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue first last",
            'heading' => esc_attr__('Auto Slideshow  ', 'massive-dynamic'),
            'param_name' => 'mobile_slider_slideshow',
            "group" => esc_attr__('General', 'massive-dynamic'),
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
        ),
    );

    for ($i = 1; $i <= (int)$slide_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $slide_num; $k++) {
            $value[] = (string)$k;
        }
        $param[] = array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Slide Image", 'massive-dynamic'),
            "param_name" => "mobile_slider_slide_image_" . $i,
            "admin_label" => false,
            "description" => esc_attr__("Choose Slide image", 'massive-dynamic'),
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Mobile Slider", 'massive-dynamic'),
    "base" => "md_mobile_slider",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Media','massive-dynamic'),
    "params" => pixflow_mobile_slider_param()
));


/*-----------------------------------------------------------------------------------*/
/*  Contact Form
/*-----------------------------------------------------------------------------------*/

// Load Created Contact Forms form contact Form7
$cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
$contact_forms = array();
if ($cf7) {
    foreach ($cf7 as $cform) {
        $contact_forms[$cform->post_title] = $cform->ID;
    }
} else {
    $contact_forms[esc_attr__('No contact forms found', 'massive-dynamic')] = 0;
}
pixflow_map(array(
    'base' => 'md_contactform',
    'name' => esc_attr__('Contact', 'massive-dynamic'),
    "category" => esc_attr__('Basic', 'massive-dynamic'),
    'show_settings_on_create' => false,
    'description' => esc_attr__('Place Contact Form', 'massive-dynamic'),
    'params' => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__('Select contact form', 'massive-dynamic'),
            "edit_field_class" => $filedClass . "first glue last",
            'param_name' => 'contactform_id',
            'value' => $contact_forms,
        ),
        array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "glue first",
            'heading' => esc_attr__('Title', 'massive-dynamic'),
            'param_name' => 'contactform_title',
            'admin_label' => false,
            "value" => "CONTACT FORM",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "contactform_title_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "contactform_description",
            "admin_label" => false,
            "value" => "We are a fairly small, flexible design studio that designs for print and web.",
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "contactform_general_color",
            "value" => 'rgb(0,0,0)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "contactform_general_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Field Background", 'massive-dynamic'),
            "param_name" => "contactform_field_color",
            "value" => 'rgb(255,255,255)',
            "opacity" => true,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "contactform_general_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Button Color", 'massive-dynamic'),
            "param_name" => "contactform_button_color",
            "value" => 'rgb(0,0,0)',
            "opacity" => true,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "contactform_button_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Button Hover Color", 'massive-dynamic'),
            "param_name" => "contactform_button_hover",
            "value" => 'rgba(150,150,150,0.9)',
            "opacity" => true,
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
            'param_name' => 'left_right_padding',
            'value' => '12',
            'defaultSetting' => array(
                "min" => "0",
                "max" => "300",
                "prefix" => " px",
                "step" => "1",
            )
        ),
        array(
            "type" => "md_vc_description",
            "param_name" => "contactform_attention",
            "admin_label" => false,
            "value" => '<ul><li>' . esc_attr__('Please note that you can\'t see the process of contact form in the builder area. Also contact form colors will not work, unless you use our contact form styles.', 'massive-dynamic') . '</li><li>' . esc_attr__('To import our contact form styles, go to massive dynamic complete package files(you should download complete package from themeforest) and open Contact Form Templates, locate \'business contact form.txt\' or \'classic contact form.txt\', copy the text inside it, then go to WordPress dashboard > Contact > add new, give this form a name and replace the text in textarea with the text from text files, now press save. Now you can come back to contact form shortcode and choose the form you\'ve just created.', 'massive-dynamic') . '</li></ul>',
        ),

    )
));

/*-----------------------------------------------------------------------------------*/
/*  Portfolio
/*-----------------------------------------------------------------------------------*/
$portfolio_cats = array();
$terms = get_terms('skills', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $portfolio_cats[] = $term->name;
    }
}
pixflow_map(
    array(
        'base' => 'md_portfolio_multisize',
        'name' => esc_attr__('Portfolio', 'massive-dynamic'),
        'description' => esc_attr__('Choose Portfolio Options', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__("Basic", 'massive-dynamic'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Meta Position', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'multisize_meta_position',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'value' => array(
                    esc_attr__("Inside Portfolio Item", 'massive-dynamic') => "inside",
                    esc_attr__("Outside Portfolio Item", 'massive-dynamic') => "outside",
                ),
            ),
            array(
                'type' => 'md_vc_multiselect',
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'multisize_category',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'items' => $portfolio_cats,
                'defaults' => 'all',
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Enable Filters ', 'massive-dynamic'),
                'param_name' => 'multisize_filters',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " first glue last",
                'heading' => esc_attr__('Show Post Counts ', 'massive-dynamic'),
                'param_name' => 'multisize_post_count',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top glue",
                'group' => esc_attr__("General", 'massive-dynamic'),
                "admin_label" => false,
                'dependency' => array(
                    'element' => "multisize_post_count",
                    'value' => array('yes'),
                )
            ),//separator

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " glue",
                "heading" => esc_attr__("Background", 'massive-dynamic'),
                "param_name" => "multisize_counter_background_color",
                "value" => '#af72ff',
                "opacity" => false,
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Background", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_post_count",
                    'value' => array('yes'),
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . " glue",
                "admin_label" => false,
                "group" => esc_attr__('General', 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_post_count",
                    'value' => array('yes'),
                )
            ),//separator

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Number", 'massive-dynamic'),
                "param_name" => "multisize_counter_color",
                "value" => '#ffffff',
                "opacity" => false,
                "group" => esc_attr__('General', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Counter Background Color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_post_count",
                    'value' => array('yes'),
                )
            ),

            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Filters Align', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'multisize_filters_align',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'value' => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Center", 'massive-dynamic') => "center",
                    esc_attr__("Right", 'massive-dynamic') => "right",
                ),
                'dependency' => array(
                    'element' => "multisize_filters",
                    'value' => array('yes'),
                )
            ),
            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Items Padding", 'massive-dynamic'),
                "param_name" => "multisize_spacing",
                "value" => "0",
                'group' => esc_attr__("General", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first last",
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "30",
                    "prefix" => "px",
                    "step" => '1',
                )
            ), array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Open Mode', 'massive-dynamic'),
                'param_name' => 'multisize_detail_target',
                'group' => esc_attr__("General", 'massive-dynamic'),
                //'value' => array(esc_attr__('popup', 'massive-dynamic') => 'popup'),
                'value' => array(
                    esc_attr__("Popup", 'massive-dynamic') => "popup",
                    esc_attr__("Link Thumbnail", 'massive-dynamic') => "page"
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Title', 'massive-dynamic'),
                'param_name' => 'multisize_title',
                'group' => esc_attr__("General", 'massive-dynamic'),
                'admin_label' => false,
                "value" => "OUR PROJECTS",
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Enable Like ', 'massive-dynamic'),
                'param_name' => 'multisize_like',
                "group" => esc_attr__('General', 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),

            array(
                'type' => 'md_vc_description',
                'heading' => ' ',
                'param_name' => 'multisize_description',
                "group" => esc_attr__('General', 'massive-dynamic'),
                'checked' => true,
                "value" => "<div class='portfolio-multisize1'>
                                             " . '<ul><li>' . esc_attr__('To add portfolio items, go to WordPress Dashboard > Portfolio > add new', 'massive-dynamic') . '</li><li>' . esc_attr__('Please notice that popup detail will not work in builder area', 'massive-dynamic') . '</li></ul>' . "
                                        </div>"
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Title/Filter Color", 'massive-dynamic'),
                "param_name" => "multisize_filter_color",
                "value" => 'rgb(0,0,0)',
                "opacity" => false,
                "group" => esc_attr__('Colors', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Filter Color", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Item Text Color", 'massive-dynamic'),
                "param_name" => "multisize_text_color",
                "value" => 'rgba(191,191,191,1)',
                "opacity" => true,
                "group" => esc_attr__('Colors', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Text Color", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "multisize_overlay_color",
                "value" => 'rgba(0,0,0,0.5)',
                "opacity" => true,
                "group" => esc_attr__('Colors', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Overlay Color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_meta_position",
                    'value' => array('inside')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Frame Color", 'massive-dynamic'),
                "param_name" => "multisize_frame_color",
                "value" => '#fff',
                "opacity" => false,
                "group" => esc_attr__('Colors', 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Frame Color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_meta_position",
                    'value' => array('outside')
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Item Number', 'massive-dynamic'),
                'param_name' => 'multisize_item_number',
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'admin_label' => false,
                "value" => "-1",
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "multisize_item_number_description",
                "admin_label" => false,
                "value" => '<ul><li>' . esc_attr__('Please note that Load More functionality does not work in builder view for technical reasons.', 'massive-dynamic') . '</li><li>' . esc_attr__('To display all your portfolio items, enter -1 in Item Number filed.', 'massive-dynamic') . '</li></ul>',
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "md_text_style",
                    'value' => array('image')
                ),
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Load More Button', 'massive-dynamic'),
                'param_name' => 'multisize_load_more',
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "admin_label" => false,
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "multisize_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//btn kind
            array(
                "type" => "textfield",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "multisize_button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'LOAD MORE',
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_button_text_separator" . ++$separatorCounter,
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//separator
            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "multisize_button_icon_class",
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                ),
                'value' => 'icon-plus6'
            ),//btn icon
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "param_name" => "multisize_button_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(0,0,0,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "multisize_button_text_color",
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "multisize_button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_button_color_separator" . ++$separatorCounter,
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "param_name" => "multisize_button_hover_color",
                "admin_label" => false,
                "value" => "rgb(255,255,255)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "multisize_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),

            ),//text hover color
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                "param_name" => "multisize_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "multisize_button_hover_color_separator" . ++$separatorCounter,
                'group' => esc_attr__("Item Number", 'massive-dynamic'),
                'dependency' => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes'),
                )
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'multisize_button_padding',
                'value' => '0',
                "group" => esc_attr__("Item Number", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "multisize_load_more",
                    'value' => array('yes')
                )
            ),//btn space
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Skill
/*-----------------------------------------------------------------------------------*/

function pixflow_skill_style1_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $bar_num_param = 'skill_style1_num';
    $bar_num = 10;
    $dropDown = array(
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number of Skills:", 'massive-dynamic'),
            "param_name" => $bar_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
    );

    for ($i = 1; $i <= (int)$bar_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $bar_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",

            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "skill_style1_title_" . $i,
            "description" => esc_attr__("Skill Title", 'massive-dynamic'),
            "value" => 'Bar' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "param_name" => "skill_style1_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_slider",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Percentage", 'massive-dynamic'),
            "param_name" => "skill_style1_percentage_" . $i,
            "admin_label" => false,
            "value" => '40',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
            'defaultSetting' => array(
                "min" => "0",
                "max" => "100",
                "prefix" => "%",
                "step" => "1",
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "param_name" => "skill_style1_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Texts Color", 'massive-dynamic'),
            "param_name" => "skill_style1_texts_color_" . $i,
            "admin_label" => false,
            "value" => '#9b9b9b',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "param_name" => "skill_style1_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Progressbar Color", 'massive-dynamic'),
            "param_name" => "skill_style1_color_" . $i,
            "admin_label" => false,
            "value" => '#9b9b9b',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Skills", 'massive-dynamic'),
    "base" => "md_skill_style1",
    "show_settings_on_create" => false,
    "category" => esc_attr__("more", 'massive-dynamic'),
    "params" => pixflow_skill_style1_param()
));


/*-----------------------------------------------------------------------------------*/
/*  Skill 2
/*-----------------------------------------------------------------------------------*/

function pixflow_skill_style2_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $bar_num_param = 'skill_style2_num';
    $bar_num = 10;
    $dropDown = array(
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number of Skills:", 'massive-dynamic'),
            "param_name" => $bar_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Texts Color", 'massive-dynamic'),
            "param_name" => "skill_style2_texts_color",
            "admin_label" => false,
            "value" => '#4d4d4e',
        ),
    );

    for ($i = 1; $i <= (int)$bar_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $bar_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",

            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "skill_style2_title_" . $i,
            "description" => esc_attr__("Skill Title", 'massive-dynamic'),
            "value" => 'Skill Title ' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "param_name" => "skill_style2_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_slider",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Percentage", 'massive-dynamic'),
            "param_name" => "skill_style2_percentage_" . $i,
            "admin_label" => false,
            "value" => '40',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
            'defaultSetting' => array(
                "min" => "0",
                "max" => "100",
                "prefix" => "%",
                "step" => "1",
            ),
        );


        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "param_name" => "skill_style2_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Bar ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Progressbar Color", 'massive-dynamic'),
            "param_name" => "skill_style2_color_" . $i,
            "admin_label" => false,
            "value" => '#7b58c3',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Skills 2", 'massive-dynamic'),
    "base" => "md_skill_style2",
    "show_settings_on_create" => false,
    "category" => esc_attr__("more", 'massive-dynamic'),
    "params" => pixflow_skill_style2_param()
));


/*-----------------------------------------------------------------------------------*/
/*  Video
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => esc_attr__('Video', 'massive-dynamic'),
        "base" => "md_video",
        "category" => esc_attr__("Basic", 'massive-dynamic'),
        'show_settings_on_create' => false,
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => esc_attr__("Host", 'massive-dynamic'),
                "param_name" => "md_video_host",
                "edit_field_class" => $filedClass . "glue first last",
                "value" => array(

                    "Youtube" => "youtube",
                    "Vimeo" => "vimeo",
                    "Self Hosted" => "self",

                ),
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue",
                'param_name' => 'md_video_url_mp4',
                "heading" => esc_attr__("MP4 Link", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('self')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_video_url_mp4_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('self')
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue",
                'param_name' => 'md_video_url_webm',
                "heading" => esc_attr__("Webm Link", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('self')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_video_url_webm_separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('self')
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue last",
                'param_name' => 'md_video_url_ogg',
                "heading" => esc_attr__("Ogg Link", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('self')
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'md_video_url_youtube',
                "heading" => esc_attr__("Link", 'massive-dynamic'),
                "value" => 'https://www.youtube.com/watch?v=tcxlSrYEkq8',
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('youtube')
                )
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'md_video_url_vimeo',
                "heading" => esc_attr__("Link", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "md_video_host",
                    'value' => array('vimeo')
                )
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_attr__("Style", 'massive-dynamic'),
                "param_name" => "md_video_style",
                "edit_field_class" => $filedClass . "glue first",
                "value" => array(
                    esc_attr__("Color Button", 'massive-dynamic') => "color",
                    esc_attr__("Square Image", 'massive-dynamic') => "squareImage",
                    esc_attr__("Circle Image", 'massive-dynamic') => "circleImage",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_text_style_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Color", 'massive-dynamic'),
                "param_name" => "md_video_solid_color",
                "value" => 'rgba(20,20,20,1)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => array(
                    'element' => "md_video_style",
                    'value' => array('color')
                )
            ),

            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Image', 'massive-dynamic'),
                'param_name' => 'md_video_image',
                "dependency" => array(
                    'element' => "md_video_style",
                    'value' => array('circleImage', 'squareImage')
                ),
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . " glue first last",
                'heading' => esc_attr__('Size', 'massive-dynamic'),
                'param_name' => 'md_video_size',
                'value' => '100',
                'defaultSetting' => array(
                    "min" => "60",
                    "max" => "100",
                    "prefix" => " %",
                    "step" => "1",
                )
            ),


        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Showcase
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_showcase',
        'name' => esc_attr__('Showcase', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        'params' => array(
            array(
                "type" => "md_vc_description",
                "param_name" => "showcase_description",
                "admin_label" => false,
                "value" => wp_kses(__("<span>Tip:</span><ul><li>This Shortcode is only designed for 12 columns (1/1 or full-width column), don't use it in less than 12 columns.</li><li>To see how the showcase appears, save your changes in builder and check your website outside builder area.</li></ul>", 'massive-dynamic'), array('span' => array(), 'ul' => array(), 'li' => array())),
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first last",

                "heading" => esc_attr__("Image Number", 'massive-dynamic'),
                "param_name" => "showcase_count",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Three", 'massive-dynamic') => "three",
                    esc_attr__("Five", 'massive-dynamic') => "five"
                ),
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue",

                "heading" => esc_attr__("Featured Image", 'massive-dynamic'),
                "param_name" => "showcase_featured_image",
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Meta Box', 'massive-dynamic'),
                'param_name' => 'showcase_meta1',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "showcase_title1",
                "admin_label" => false,
                "value" => 'title',
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "showcase_subtitle1",
                "admin_label" => false,
                "value" => 'subtitle',
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Border Color", 'massive-dynamic'),
                "param_name" => "showcase_border_color1",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => Array(
                    'element' => "showcase_meta1",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "showcase_image1",
                "admin_label" => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('three', 'five')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Meta Box', 'massive-dynamic'),
                'param_name' => 'showcase_meta2',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "showcase_title2",
                "admin_label" => false,
                "value" => 'title',
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "showcase_subtitle2",
                "admin_label" => false,
                "value" => 'subtitle',
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Border Color", 'massive-dynamic'),
                "param_name" => "showcase_border_color2",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => Array(
                    'element' => "showcase_meta2",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "showcase_image2",
                "admin_label" => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('three', 'five')
                )
            ),//for three
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Meta Box', 'massive-dynamic'),
                'param_name' => 'showcase_meta3',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "showcase_title3",
                "admin_label" => false,
                "value" => 'title',
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "showcase_subtitle3",
                "admin_label" => false,
                "value" => 'subtitle',
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Border Color", 'massive-dynamic'),
                "param_name" => "showcase_border_color3",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => Array(
                    'element' => "showcase_meta3",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "showcase_image3",
                "admin_label" => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Meta Box', 'massive-dynamic'),
                'param_name' => 'showcase_meta4',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "showcase_title4",
                "admin_label" => false,
                "value" => 'title',
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "showcase_subtitle4",
                "admin_label" => false,
                "value" => 'subtitle',
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Border Color", 'massive-dynamic'),
                "param_name" => "showcase_border_color4",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => Array(
                    'element' => "showcase_meta4",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "showcase_image4",
                "admin_label" => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Meta Box', 'massive-dynamic'),
                'param_name' => 'showcase_meta5',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
                "dependency" => Array(
                    'element' => "showcase_count",
                    'value' => array('five')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "showcase_title5",
                "admin_label" => false,
                "value" => 'title',
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "showcase_subtitle5",
                "admin_label" => false,
                "value" => 'subtitle',
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "showcase_separator" . ++$separatorCounter,
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Border Color", 'massive-dynamic'),
                "param_name" => "showcase_border_color5",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
                "dependency" => Array(
                    'element' => "showcase_meta5",
                    'value' => array('yes')
                )
            ),
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Blog Calendar
/*-----------------------------------------------------------------------------------*/
$posts_cats = array();
$terms = get_terms('category', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $posts_cats[] = $term->name;
    }
}
pixflow_map(
    array(
        'base' => 'md_blog',
        'name' => esc_attr__('Blog Calendar', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        'params' => array(

            array(
                'type' => 'md_vc_multiselect',
                "edit_field_class" => $filedClass . "first glue",
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                'param_name' => 'blog_category',
                'items' => $posts_cats,
                'defaults' => 'all',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Post Number", 'massive-dynamic'),
                "param_name" => "blog_post_number",
                "admin_label" => false,
                "value" => '5',
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "blog_forground_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_forground_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "blog_background_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue last",

                "heading" => esc_attr__("Background Image", 'massive-dynamic'),
                "param_name" => "blog_bg",
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "calendar_description",
                "admin_label" => false,
                "value" => esc_attr__("To add blog posts, go to WordPress Dashboard > Posts > add new", 'massive-dynamic'),
            ),

        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Client Normal
/*-----------------------------------------------------------------------------------*/
pixflow_map(
    array(
        'base' => 'md_client_normal',
        'name' => esc_attr__('Client Normal', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "allowed_container_element" => 'vc_row',
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => array(
            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Logo', 'massive-dynamic'),
                'param_name' => 'md_client_logo',
                'value' => PIXFLOW_THEME_IMAGES_URI . "/logo.png",
            ),

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Background Type", 'massive-dynamic'),
                "param_name" => "md_client_bg_type",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Color", 'massive-dynamic') => "color",
                    esc_attr__("Image", 'massive-dynamic') => "image"
                ),
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue ",
                'heading' => esc_attr__('Height', 'massive-dynamic'),
                'param_name' => 'md_client_height',
                'value' => '300',
                'defaultSetting' => array(
                    "min" => "300",
                    "max" => "800",
                    "prefix" => " px",
                    "step" => "10",
                ),
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
            ),

            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Background Image', 'massive-dynamic'),
                'param_name' => 'md_client_bg_img',
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'image'
                ),
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'image'
                ),
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "md_client_overlay_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'image'
                ),
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "md_client_bg_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'color'
                ),
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'color'
                ),
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "md_client_hover_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                'dependency' => array(
                    'element' => 'md_client_bg_type',
                    'value' => 'color'
                ),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Link", 'massive-dynamic'),
                "param_name" => "md_client_link",
                "admin_label" => false,
                "value" => '#',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "md_client_text_color",
                "value" => 'rgb(240,240,240)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_client_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "md_client_text",
                "admin_label" => false,
                "value" => "Creative Digital Agency",
            ),

        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Client Carousel
/*-----------------------------------------------------------------------------------*/
function pixflow_client_carousel_param()
{
    $filedClass = 'vc_col-sm-12 vc_column ';
    $members_param = 'client_carousel_num';
    $members_num = 12;
    $dropDown = array(
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,
        esc_attr__("Eleven", 'massive-dynamic') => 11,
        esc_attr__("Twelve", 'massive-dynamic') => 12,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number Of Clients:", 'massive-dynamic'),
            "param_name" => $members_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "md_vc_slider",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Slide to show:", 'massive-dynamic'),
            "param_name" => "client_carousel_number",
            "admin_label" => false,
            "value" => '5',
            'defaultSetting' => array(
                "min" => "1",
                "max" => "12",
                "prefix" => "",
                "step" => '1',
            )
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Play Mode:', 'massive-dynamic'),
            'param_name' => 'client_play_mode',
            'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
            'checked' => false,
            "group" => esc_attr__("General", 'massive-dynamic'),
        )
    );

    for ($i = 1; $i <= (int)$members_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $members_num; $k++) {
            $value[] = (string)$k;
        }
        $param[] = array(
            'type' => 'attach_image',
            'edit_field_class' => $filedClass . "glue first last",
            'heading' => esc_attr__('Client Logo', 'massive-dynamic'),
            'param_name' => 'client_carousel_logo_' . $i,
            "group" => esc_attr__("Client", 'massive-dynamic'),
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

pixflow_map(
    array(
        'base' => 'md_client_carousel',
        'name' => esc_attr__('Client Carousel', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "allowed_container_element" => 'vc_row',
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => pixflow_client_carousel_param()
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Testimonial Classic
/*-----------------------------------------------------------------------------------*/

function pixflow_testimoial_classic()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $count_num_param = 'testimonial_classic_num';
    $testimonial_num = 5;
    $dropDown = array(
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Testimonial Slides:", 'massive-dynamic'),
            "param_name" => $count_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "testimonial_classic_title",
            "admin_label" => false,
            "value" => 'TESTIMONIAL',
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "testimonial_classic_separator" . ++$separatorCounter,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Texts Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "md_testimonial_solid_color",
            "value" => '#000',
            "admin_label" => false,
            "opacity" => true
        ),

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Alignment", 'massive-dynamic'),
            "param_name" => "md_testimonial_alignment",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "value" => array(
                esc_attr__("Left", 'massive-dynamic') => "left",
                esc_attr__("Center", 'massive-dynamic') => "center",
            ),
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "testimonial_classic_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Text size", 'massive-dynamic'),
            "param_name" => "md_testimonial_text_size",
            "description" => esc_attr__("Paragraph Size", 'massive-dynamic'),
            "admin_label" => false,
            "value" => array(
                "H5" => "h5",
                "H1" => "h1",
                "H2" => "h2",
                "H3" => "h3",
                "H4" => "h4",
                "H6" => "h6"
            ),
        ),

    );

    for ($i = 1; $i <= (int)$testimonial_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $testimonial_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "md_vc_base64_textarea",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "testimonial_classic_desc_" . $i,
            "admin_label" => false,
            "value" => 'Ipsum dol conse ctetuer adipis cing elit. Morbi com modo, ipsum sed pharetr gravida, orciut magna rhoncus neque,id pulvinaodio lorem non sansunioto koriot.Morbcom magna rhoncus neque,id',
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "param_name" => "testimonial_classic_slide_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Name and Job Title", 'massive-dynamic'),
            "param_name" => "testimonial_classic_name_job_" . $i,
            "admin_label" => false,
            "value" => 'Randy Nicklson . ATC resident manager co.',
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "param_name" => "testimonial_classic_slide_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'attach_image',
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__('Title Image', 'massive-dynamic'),
            "param_name" => "testimonial_classic_img_" . $i,
            "description" => esc_attr__("Testimonial Title", 'massive-dynamic'),
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "admin_label" => false,
            "dependency" => array(
                "element" => $count_num_param,
                "value" => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Testimonial Classic", 'massive-dynamic'),
    "base" => "md_testimonial_classic",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business', 'massive-dynamic'),
    "params" => pixflow_testimoial_classic()
));

/*-----------------------------------------------------------------------------------*/
/*  Testimonial carousel
/*-----------------------------------------------------------------------------------*/

function pixflow_testimonial_carousel()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $count_num_param = 'testimonial_carousel_num';
    $testimonial_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Testimonial Slides:", 'massive-dynamic'),
            "param_name" => $count_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Texts Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_text_color",
            "value" => '#000',
            "admin_label" => false,
            "opacity" => true
        ),
        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Text size", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_text_size",
            "description" => esc_attr__("Paragraph Size", 'massive-dynamic'),
            "admin_label" => false,
            "value" => array(
                "H6" => "h6",
                "H1" => "h1",
                "H2" => "h2",
                "H3" => "h3",
                "H4" => "h4",
                "H5" => "h5"
            ),
        ),
    );

    for ($i = 1; $i <= (int)$testimonial_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $testimonial_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_desc_" . $i,
            "admin_label" => false,
            "value" => 'orem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pellentesque fringilla vel, non sed arcu integevestibulum in lorem nec',
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "param_name" => "testimonial_carousel_slide_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Name", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_name_" . $i,
            "admin_label" => false,
            "value" => 'Mari Javani',
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "param_name" => "testimonial_carousel_slide_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Job Name", 'massive-dynamic'),
            "param_name" => "testimonial_carousel_job_name_" . $i,
            "admin_label" => false,
            "value" => 'Graphic Designer, Stupids Magazine',
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "param_name" => "testimonial_carousel_slide_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $count_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'attach_image',
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__('Title Image', 'massive-dynamic'),
            "param_name" => "testimonial_carousel_img_" . $i,
            "description" => esc_attr__("Testimonial Title", 'massive-dynamic'),
            "group" => esc_attr__("Slide", 'massive-dynamic') . $i,
            "admin_label" => false,
            "dependency" => array(
                "element" => $count_num_param,
                "value" => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Testimonial Carousel", 'massive-dynamic'),
    "base" => "md_testimonial_carousel",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business', 'massive-dynamic'),
    "params" => pixflow_testimonial_carousel()
));

/*-----------------------------------------------------------------------------------*/
/*  Instagram
/*-----------------------------------------------------------------------------------*/
$redirect_uri = PIXFLOW_THEME_LIB_URI . '/instagram/get_token_access.php';
$instagram = new pixflow_Instagram(array(
    'apiKey' => 'a0416c7630d74bfb894916fb4c8d0c70',
    'apiSecret' => '9df90946a6c142c9b75e6df51726124c',
    'apiCallback' => 'http://demo2.pixflow.net/instagram-app/redirect.php?redirect_uri=' . urlencode($redirect_uri)
));
$InstagramloginURL = $instagram->getLoginUrl();

pixflow_map(
    array(
        'base' => 'md_instagram',
        'name' => esc_attr__('Instagram', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        "params" => array(
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'instagram_token_access',
                "heading" => esc_attr__("Token Access", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_description",

                "param_name" => "row_inner_type_width_description",
                "admin_label" => false,
                "value" => "<a href='" . $InstagramloginURL . "' target='_blank'>" . esc_attr__('Get Token Access', 'massive-dynamic') . "</a>"
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'instagram_title',
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => "Follow on Instagram",
            ),
            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Image number", 'massive-dynamic'),
                "param_name" => "instagram_image_number",
                "value" => "4",
                "edit_field_class" => $filedClass . "glue first last",
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "50",
                    "prefix" => "",
                    "step" => '1',
                )
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first",
                'heading' => esc_attr__('Show Header', 'massive-dynamic'),
                'param_name' => 'instagram_heading',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "instagram_header_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Show Like', 'massive-dynamic'),
                'param_name' => 'instagram_like',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "instagram_like_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Show Comments', 'massive-dynamic'),
                'param_name' => 'instagram_comment',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "instagram_general_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "instagram_general_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "instagram_overlay_color",
                "value" => 'rgba(255,255,255,0.6)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "instagram_shortcode_description",
                "admin_label" => false,
                "value" => '<ul><li>' . esc_attr__('Please note that when you change a value in Instagram shortcode, it takes a little time to apply changes. It\'s suggested to save and refresh the page.', 'massive-dynamic') . '</li></ul>',
            ),


        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Blog Masonry
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_blog_masonry',
        'name' => esc_attr__('Blog Masonry', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        'params' => array(
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Column Number", 'massive-dynamic'),
                "param_name" => "blog_column",
                "group" => esc_attr__("General", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Three", 'massive-dynamic') => "three",
                    esc_attr__("Four", 'massive-dynamic') => "four",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_column_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                'type' => 'md_vc_multiselect',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                'param_name' => 'blog_category',
                'items' => $posts_cats,
                'defaults' => 'all',
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Post Number", 'massive-dynamic'),
                "param_name" => "blog_post_number",
                "value" => "5",
                "edit_field_class" => $filedClass . "glue last",
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "30",
                    "prefix" => "",
                    "step" => '1',
                ),
                'group' => esc_attr__('General', 'massive-dynamic')

            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "blog_background_color",
                "value" => 'rgb(87,63,203)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_background_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "blog_foreground_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_foreground_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Accent Color", 'massive-dynamic'),
                "param_name" => "blog_accent_color",
                "value" => 'rgb(220,38,139)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ), array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_accent_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Accent Color", 'massive-dynamic'),
                "param_name" => "blog_text_accent_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_accent_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ), array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Shadow Color", 'massive-dynamic'),
                'group' => esc_attr__('Design', 'massive-dynamic'),
                "param_name" => "blog_post_shadow",
                "value" => 'rgba(0,0,0,.12)',
                "admin_label" => false,
                "opacity" => true,
            ),
        )
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Blog Carousel
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_blog_carousel',
        'name' => esc_attr__('Blog Carousel', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        'params' => array(
            array(
                'type' => 'md_vc_multiselect',
                "edit_field_class" => $filedClass . "glue first",
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                'param_name' => 'blog_category',
                'items' => $posts_cats,
                'defaults' => 'all',
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Auto Play', 'massive-dynamic'),
                'param_name' => 'carousel_autoplay',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Post Number", 'massive-dynamic'),
                "param_name" => "blog_post_number",
                "value" => "5",
                "edit_field_class" => $filedClass . "glue last",
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "30",
                    "prefix" => "",
                    "step" => '1',
                ),

                'group' => esc_attr__('General', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "blog_background_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_background_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "blog_foreground_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_background_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Date Color", 'massive-dynamic'),
                "param_name" => "blog_date_color",
                "value" => 'rgb(84,84,84)',
                "admin_label" => false,
                "opacity" => false,
                'group' => esc_attr__('Design', 'massive-dynamic')
            ),
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Process Steps
/*-----------------------------------------------------------------------------------*/
// Generate process step shortcode params
function pixflow_processStep_param()
{
    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $step_num_param = 'pstep_step_num';
    $step_num = 7;
    $dropDown = array(
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6
    );
    $param = array(
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Step Number:", 'massive-dynamic'),
            "param_name" => $step_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__('Style', 'massive-dynamic'),
            "edit_field_class" => $filedClass . "first glue last",
            'param_name' => 'pstep_style',
            'group' => esc_attr__("General", 'massive-dynamic'),
            'value' => array(
                esc_attr__("Border", 'massive-dynamic') => "color",
                esc_attr__("Image", 'massive-dynamic') => "image",
            ),
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first last glue",
            "heading" => esc_attr__("Border Color", 'massive-dynamic'),
            "param_name" => "pstep_border_color",
            "value" => 'rgba(176,227,135,1)',
            "admin_label" => false,
            "opacity" => true,
            'group' => esc_attr__("General", 'massive-dynamic'),
            'dependency' => array(
                'element' => 'pstep_style',
                'value' => array('color')
            ),
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first last glue",
            "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
            "param_name" => "pstep_overlay_color",
            "value" => 'rgba(0,0,0,0.5)',
            "admin_label" => false,
            "opacity" => true,
            'group' => esc_attr__("General", 'massive-dynamic'),
            'dependency' => array(
                'element' => 'pstep_style',
                'value' => array('image')
            ),
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first last glue",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "pstep_general_color",
            'group' => esc_attr__("General", 'massive-dynamic'),
            "value" => 'rgb(0,0,0)',
            "admin_label" => false,
            "opacity" => true,
        ),
        array(
            "type" => "md_vc_description",
            "param_name" => "pstep_description",
            "admin_label" => false,
            'group' => esc_attr__("General", 'massive-dynamic'),
            "value" => wp_kses(__("<span>Tip:</span><ul><li>To see how this shortcode appears on scroll, save your changes in builder and check your website outside builder area.</li></ul>", 'massive-dynamic'), array('span' => array(), 'ul' => array(), 'li' => array())),
        ),
    );

    for ($i = 1; $i <= (int)$step_num; $i++) {
        $value = array();
        for ($k = $i; $k <= $step_num; $k++) {
            $value[] = (string)$k;
        }
        $param[] = array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "first glue last",
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Image", 'massive-dynamic'),
            "param_name" => "pstep_image_" . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "first glue last",
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Size", 'massive-dynamic'),
            "param_name" => "pstep_size_" . $i,
            "admin_label" => false,
            'value' => array(
                esc_attr__("Small", 'massive-dynamic') => "small",
                esc_attr__("Medium", 'massive-dynamic') => "medium",
                esc_attr__("Large", 'massive-dynamic') => "large",
            ),
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Caption", 'massive-dynamic'),
            "param_name" => "pstep_caption_" . $i,
            "admin_label" => false,
            "value" => '201' . $i,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "param_name" => "pstep_caption_" . $i . "_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "pstep_title_" . $i,
            "value" => "Step " . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "param_name" => "pstep_title_" . $i . "_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Step ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "pstep_desc_" . $i,
            "value" => "Description of step" . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $step_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

pixflow_map(
    array(
        'base' => 'md_process_steps',
        'name' => esc_attr__('Process Steps', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => pixflow_processStep_param(),
    )
);


/*-----------------------------------------------------------------------------------*/
/*  Separator
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_separator',
        'name' => esc_attr__('Separator', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__("Structure", 'massive-dynamic'),
        "params" => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Style', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'separator_style',
                'value' => array(
                    esc_attr__("Line", 'massive-dynamic') => "line",
                    esc_attr__("Shadow", 'massive-dynamic') => "shadow",
                ),
            ),

            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Height", 'massive-dynamic'),
                "param_name" => "separator_size",
                "value" => "5",
                "edit_field_class" => $filedClass . "glue first",
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "10",
                    "prefix" => "px",
                    "step" => '1',
                ),
                'dependency' => array(
                    'element' => 'separator_style',
                    'value' => array('line')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator_separator" . ++$separatorCounter,
                'dependency' => array(
                    'element' => 'separator_style',
                    'value' => array('line')
                )
            ),
            array(
                "type" => 'md_vc_slider',
                "heading" => esc_attr__("Width", 'massive-dynamic'),
                "param_name" => "separator_width",
                "value" => "70",
                "edit_field_class" => $filedClass . "glue",
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "100",
                    "prefix" => "%",
                    "step" => '1',
                ),
                'dependency' => array(
                    'element' => 'separator_style',
                    'value' => array('line')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator_separator" . ++$separatorCounter,
                'dependency' => array(
                    'element' => 'separator_style',
                    'value' => array('line')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Color", 'massive-dynamic'),
                "param_name" => "separator_color",
                "value" => '#cccccc',
                "admin_label" => false,
                "opacity" => false,
                'dependency' => array(
                    'element' => 'separator_style',
                    'value' => array('line')
                )
            ),
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Team Member Style 2
/*-----------------------------------------------------------------------------------*/

function pixflow_team_member2_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $members_param = 'team_member_style2_num';
    $members_num = 12;
    $dropDown = array(
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,
        esc_attr__("Eleven", 'massive-dynamic') => 11,
        esc_attr__("Twelve", 'massive-dynamic') => 12,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number Of Members:", 'massive-dynamic'),
            "param_name" => $members_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "team_member_style2_texts_color",
            "admin_label" => false,
            'value' => '#fff',
            "group" => esc_attr__("General", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("General", 'massive-dynamic'),
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Overlay Text Color", 'massive-dynamic'),
            "param_name" => "team_member_style2_hover_color",
            "admin_label" => false,
            'value' => 'rgba(255, 255, 255, 1)',
            "group" => esc_attr__("General", 'massive-dynamic'),
        )
    );
    $s = 1;
    for ($i = 1; $i <= (int)$members_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $members_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",

            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Name", 'massive-dynamic'),
            "param_name" => "team_member_style2_name_" . $i,
            "value" => 'Member' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "param_name" => "team_member_style2_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",

            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Job Title", 'massive-dynamic'),
            "param_name" => "team_member_style2_position_" . $i,
            "value" => 'Manager',
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "param_name" => "team_member_style2_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("About Member", 'massive-dynamic'),
            "param_name" => "team_member_style2_description_" . $i,
            'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper.',
            "admin_label" => false,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            'type' => 'attach_image',
            'edit_field_class' => $filedClass . "glue first last",
            'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
            'param_name' => 'team_member_style2_image_' . $i,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Social Network 1", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_" . $s,
            "admin_label" => false,
            "value" => "icon-facebook2",
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue last",

            "heading" => esc_attr__("Page Url", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_url_" . $s++,
            "value" => 'http://www.facebook.com',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Social Network 2", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_" . $s,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "value" => 'icon-twitter5',
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue last",

            "heading" => esc_attr__("Page Url", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_url_" . $s++,
            'value' => 'http://www.twitter.com',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Social Network 3", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_" . $s,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "value" => 'icon-google',
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue last",

            "heading" => esc_attr__("Page Url", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_url_" . $s++,
            'value' => 'http://www.google.com',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Social Network 4", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_" . $s,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "value" => 'icon-dribbble',
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue last",

            "heading" => esc_attr__("Page Url", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_url_" . $s++,
            'value' => 'http://www.dribbble.com',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Social Network 5", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_" . $s,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "value" => 'icon-instagram',
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "param_name" => "team_member_style2_separator" . ++$separatorCounter,
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue last",

            "heading" => esc_attr__("Page Url", 'massive-dynamic'),
            "param_name" => "team_member_style2_social_icon_url_" . $s++,
            'value' => 'http://www.instagram.com',
            "group" => esc_attr__("Member ", 'massive-dynamic') . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $members_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

pixflow_map(
    array(
        "name" => "Team Carousel",
        "base" => "md_teammember2",
        "category" => esc_attr__('Business','massive-dynamic'),
        "show_settings_on_create" => false,
        "params" => pixflow_team_member2_param(),

    )
);

/*-----------------------------------------------------------------------------------*/
/*  Subscribe
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_subscribe',
        'name' => esc_attr__('Subscribe', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => array(
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "subscribe_bgcolor",
                "value" => '#ebebeb',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Inputs Radius', 'massive-dynamic'),
                'param_name' => 'subscribe_input_radius',
                'value' => '35',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "35",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "subscribe_title",
                "value" => 'SUBSCRIBE',
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . " glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "subscribe_sub_title",
                "value" => 'Sign up to receive news and updates',
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " last glue",
                "heading" => esc_attr__("Title Color", 'massive-dynamic'),
                "param_name" => "subscribe_textcolor",
                "value" => '#000',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_attr__("Email Input Style", 'massive-dynamic'),
                "param_name" => "subscribe_input_style",
                "edit_field_class" => $filedClass . "first glue",
                "value" => array(
                    esc_attr__("Fill", 'massive-dynamic') => "fill",
                    esc_attr__("Stroke", 'massive-dynamic') => "stroke",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_attr__("Email Input Skin", 'massive-dynamic'),
                "param_name" => "subscribe_input_skin",
                "edit_field_class" => $filedClass . " glue",
                "value" => array(
                    esc_attr__("Light", 'massive-dynamic') => "light",
                    esc_attr__("Dark", 'massive-dynamic') => "dark",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Email Input Opacity', 'massive-dynamic'),
                'param_name' => 'subscribe_input_opacity',
                'value' => '100',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "100",
                    "prefix" => " %",
                    "step" => "1",
                )
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " first glue",
                "heading" => esc_attr__("Button Background Color", 'massive-dynamic'),
                "param_name" => "subscribe_button_bgcolor",
                "value" => '#c7b299',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " last glue",
                "heading" => esc_attr__("Button Text Color", 'massive-dynamic'),
                "param_name" => "subscribe_button_textcolor",
                "value" => '#FFF',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "pstep_description",
                "admin_label" => false,
                "value" => '<ul><li>' . esc_attr__('You must install and configure "MailChimp for WordPress Lite" plugin before using this shortcode.', 'massive-dynamic') . '</li></ul>',
            ),
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Music
/*-----------------------------------------------------------------------------------*/

function pixflow_music_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $track_num_param = 'music_num';
    $track_num = 12;
    $dropDown = array(
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,
        esc_attr__("Eleven", 'massive-dynamic') => 11,
        esc_attr__("Twelve", 'massive-dynamic') => 12,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number of Tracks", 'massive-dynamic'),
            "param_name" => $track_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Album", 'massive-dynamic'),
            "param_name" => "music_album",
            "value" => "Audio Jungle",
            "admin_label" => false,
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Artist", 'massive-dynamic'),
            "param_name" => "music_artist",
            "value" => "by PR MusicProductions",
            "admin_label" => false,
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Album Image", 'massive-dynamic'),
            "param_name" => "music_image",
            "admin_label" => false,
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Track Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_texts_color",
            "value" => 'rgb(80,80,80)',
            "admin_label" => false,
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Play Color", 'massive-dynamic'),
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_played_color",
            "value" => 'rgb(106, 222, 174)',
            "admin_label" => false,
        ),

        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("General", 'massive-dynamic'),
            "param_name" => "music_sep" . ++$separatorCounter,
        ),

        array(
            "type" => "dropdown",
            "heading" => esc_attr__("Orientation", 'massive-dynamic'),
            "param_name" => "music_alignment",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "edit_field_class" => $filedClass . "glue last",
            "value" => array(
                esc_attr__("Right to Left", 'massive-dynamic') => "right-music-panel",
                esc_attr__("Left to Right", 'massive-dynamic') => "left-music-panel",
            ),
        ),
        array(
            "type" => "md_vc_description",
            "param_name" => "music_description",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "admin_label" => false,
            "value" => esc_attr__("Tip: to see how this shortcode appears when you scroll, save your changes in builder and check your website outside builder area.", 'massive-dynamic'),
        ),

    );


    for ($i = 1; $i <= (int)$track_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $track_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("Track ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Track Name", 'massive-dynamic'),
            "param_name" => "music_track_name_" . $i,
            "value" => "Inspiring " . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $track_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => "Track " . $i,
            "param_name" => "music_sep" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $track_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "glue last",
            'param_name' => 'music_track_url' . $i,
            "group" => esc_attr__("Track ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Track Link", 'massive-dynamic'),
            "value" => 'https://0.s3.envato.com/files/131328937/preview.mp3',
            "admin_label" => false,
            'dependency' => array(
                'element' => $track_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Music", 'massive-dynamic'),
    "base" => "md_music",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Media','massive-dynamic'),
    "params" => pixflow_music_param()
));


/*-----------------------------------------------------------------------------------*/
/*  Inner Row
/*-----------------------------------------------------------------------------------*/

// remove css animation and disable
pixflow_remove_param('vc_row_inner', 'css_animation');
pixflow_remove_param('vc_row_inner', 'disable_element');
// remove bg image
pixflow_remove_param('vc_row_inner', 'bg_image');

// remove get class
//pixflow_remove_param('vc_row_inner', 'el_class');

// remove get id
pixflow_remove_param('vc_row_inner', 'el_id');

// remove default parallax
pixflow_remove_param('vc_row_inner', 'parallax');

// remove stretch
pixflow_remove_param('vc_row_inner', 'full_width');

// remove default parallax
pixflow_remove_param('vc_row_inner', 'parallax_image');

// remove default css editor
pixflow_remove_param('vc_row_inner', 'css');

//remove spacing attributes
pixflow_remove_param('vc_row_inner', 'row_inner_padding_top');
pixflow_remove_param('vc_row_inner', 'row_inner_padding_bottom');
pixflow_remove_param('vc_row_inner', 'row_inner_padding_left');
pixflow_remove_param('vc_row_inner', 'row_inner_padding_right');

pixflow_remove_param('vc_row_inner', 'row_inner_margin_top');
pixflow_remove_param('vc_row_inner', 'row_inner_margin_bottom');

pixflow_remove_param('vc_row_inner', 'row_inner_type');

$row_setting = array(
    "name" => "Inner Row",
    'show_settings_on_create' => false,
    "category" => esc_attr__('Container','massive-dynamic'),
);



pixflow_map_update('vc_row_inner', $row_setting);

$separator_setting = array(
    "'show_settings_on_create" => true,
    "controls" => '',
);

// row spacing - Padding all directions

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "weight" => "2",
    "heading" => esc_attr__("Padding Top", 'massive-dynamic'),
    "param_name" => "inner_row_padding_top",
    "description" => esc_attr__("insert top padding for current row . example : 200 ", 'massive-dynamic'),
    "value" => "45",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Bottom", 'massive-dynamic'),
    "param_name" => "inner_row_padding_bottom",
    "description" => esc_attr__("insert bottom padding for current row . example : 200", 'massive-dynamic'),
    "value" => "47",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_inner_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Right", 'massive-dynamic'),
    "param_name" => "inner_row_padding_right",
    "description" => esc_attr__("insert Right padding for current row . example : 200", 'massive-dynamic'),
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_inner_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "heading" => esc_attr__("Padding Left", 'massive-dynamic'),
    "param_name" => "inner_row_padding_left",
    "description" => esc_attr__("insert left padding for current row . example : 200", 'massive-dynamic'),
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue last",
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

// row spacing Margin only top and bottom

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "edit_field_class" => $filedClass . "glue first",
    "heading" => esc_attr__("Margin Top", 'massive-dynamic'),
    "param_name" => "inner_row_margin_top",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Spacing", 'massive-dynamic'),
    "param_name" => "row_inner_padding_tab_separator" . ++$separatorCounter,
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_slider',
    "edit_field_class" => $filedClass . "glue last",
    "heading" => esc_attr__("Margin Bottom", 'massive-dynamic'),
    "param_name" => "inner_row_margin_bottom",
    'group' => esc_attr__("Spacing", 'massive-dynamic'),
    'defaultSetting' => array(
        "min" => "0",
        "max" => "800",
        "prefix" => "",
        "step" => '5',
    )
));

// ***** row - background - tab *****


pixflow_add_param("vc_row_inner", array(
    "type" => "dropdown",
    "weight" => "3",
    "class" => "",
    "heading" => esc_attr__("Background Type", 'massive-dynamic'),
    "param_name" => "inner_row_type",
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "description" => esc_attr__("Choose different type of containers and set the options.", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue first",
    "value" => array(
        esc_attr__("Solid Color", 'massive-dynamic') => "none",
        esc_attr__("Gradient and Image", 'massive-dynamic') => "gradient",
    ),
));



pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "param_name" => "row_inner_bg_tab_separator" . ++$separatorCounter,
));


// Background color overlay for default state
pixflow_add_param("vc_row_inner", array(
    "type" => "md_vc_colorpicker",
    "edit_field_class" => $filedClass . "glue last",

    "heading" => esc_attr__("Color", 'massive-dynamic'),
    "param_name" => "row_inner_background_color",
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "opacity" => true,
    "admin_label" => false,
    "description" => esc_attr__("Choose a color to be used as this section's background. Please noticed that background color, has higher priority than background image.", 'massive-dynamic'),
    "value" => "rgba(255,255,255,0)",
    'dependency' => array(
        'element' => "inner_row_type",
        'value' => array('none'),
    )
));


// Gradient

pixflow_add_param("vc_row_inner", array(
    "type" => "md_vc_gradientcolorpicker",
    "edit_field_class" => $filedClass . "glue",

    "heading" => esc_attr__("Gradient", 'massive-dynamic'),
    "param_name" => "row_inner_gradient_color",
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "description" => esc_attr__("Choose a color to be used as this section's background. Please notice that background color, has higher priority than background image.", 'massive-dynamic'),
    'dependency' => array(
        'element' => "inner_row_type",
        'value' => array('gradient')
    ),
    'defaultColor' => (object)array(
        'color1' => '#fff',
        'color2' => 'rgba(255,255,255,0)',
        'color1Pos' => '0',
        'color2Pos' => '100',
        'angle' => '0'),
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "param_name" => "row_inner_bg_tab_separator" . ++$separatorCounter,
    "admin_label" => false,
    "dependency" => array(
        'element' => "inner_row_type",
        'value' => array('gradient')
    )
));

// Select image

pixflow_add_param("vc_row_inner", array(
    'type' => 'attach_image',
    "edit_field_class" => $filedClass . "glue",
    'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
    'param_name' => 'row_inner_image',
    'description' => esc_attr__('choose image from media library.', 'massive-dynamic'),
    "value" => "",
    "group" => esc_attr__("Background", 'massive-dynamic'),
    'dependency' => array(
        'element' => "inner_row_type",
        'value' => array('gradient')
    ),
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "param_name" => "row_inner_image_separator" . ++$separatorCounter,
    'dependency' => array(
        'element' => "inner_row_type",
        'value' => array('gradient')
    ),
));

pixflow_add_param("vc_row_inner", array(
    "type" => "dropdown",
    "heading" => esc_attr__("Image Position", 'massive-dynamic'),
    "param_name" => "row_inner_image_position",
    "group" => esc_attr__("Background", 'massive-dynamic'),
    "edit_field_class" => $filedClass . "glue last",
    "value" => array(
        esc_attr__("Fit to row", 'massive-dynamic') => "fit",
        esc_attr__("Top", 'massive-dynamic') => "top",
        esc_attr__("Bottom", 'massive-dynamic') => "bottom",
    ),
    'dependency' => array(
        'element' => "inner_row_type",
        'value' => array('gradient')
    ),
));


// ***** row - general - tab *****

// Background width

pixflow_add_param("vc_row_inner", array(
    "type" => "dropdown",
    "weight" => "4",
    "edit_field_class" => $filedClass . "first glue",

    "heading" => esc_attr__("Background Width", 'massive-dynamic'),
    "param_name" => "row_inner_type_width",
    "description" => esc_attr__("Full width will use all of your screen width, while Boxed will created an invisible box in middle of your screen.", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Full Screen", 'massive-dynamic') => "full_size",
        esc_attr__("Container", 'massive-dynamic') => "box_size",
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'md_vc_separator',
    "weight" => "4",
    "param_name" => "row_inner_bg_tab_separator" . ++$separatorCounter,
));


// Content width

pixflow_add_param("vc_row_inner", array(
    "type" => "dropdown",
    "weight" => "4",
    "edit_field_class" => $filedClass . "glue last",

    "heading" => esc_attr__("Content Width", 'massive-dynamic'),
    "param_name" => "row_inner_box_size_states",
    "description" => esc_attr__("Full width will use all of your screen width, while Boxed will created an invisible box in middle of your screen.", 'massive-dynamic'),
    "value" => array(
        esc_attr__("Container", 'massive-dynamic') => "content_box_size",
        esc_attr__("Full Screen", 'massive-dynamic') => "content_full_size",
    )
));

pixflow_add_param("vc_row_inner", array(
    "type" => 'textfield',
    "edit_field_class" => $filedClass . "glue first last",
    "heading" => esc_attr__("Extra Class Name", 'massive-dynamic'),
    "param_name" => "el_class",
));

// Inner shadow

pixflow_add_param("vc_row_inner", array(
    "type" => "md_vc_checkbox",
    "edit_field_class" => $filedClass . "glue first last",
    "param_name" => "row_inner_inner_shadow",
    "heading" => esc_attr__('Inner shadow', 'massive-dynamic')
));


// Row description

pixflow_add_param("vc_row_inner", array(
    "type" => "md_vc_description",

    "param_name" => "row_inner_type_width_description",
    "admin_label" => false,
    "value" => wp_kses(__("<ul>
                        <li>Container size can be set from Site Content > Main Layout > Container Width</li>
                        <li>Full Screen size will ignore the container width and get the same width as user's screen</li>
                    </ul>", 'massive-dynamic'), array('ul' => array(), 'li' => array()))
));

/*************************************
 * WooCommerce shortcodes
 *************************************/
//TODO
//remove VC - woocommerce shortcodes
//pixflow_remove_element("products");
/*** Get product categories ***/
$product_cats = array();
$terms = get_terms('product_cat', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $product_cats[] = $term->name;
    }
}
/*** Category Shortcode ***/
pixflow_map(
    array(
        'base' => 'md_product_categories',
        'name' => esc_attr__('Product Categories', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__("Commerce", 'massive-dynamic'),
        'params' => array(
            array(
                'type' => 'md_vc_multiselect',
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'product_categories',
                'items' => $product_cats,
                'defaults' => 'all',
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Columns', 'massive-dynamic'),
                'param_name' => 'product_categories_cols',
                'value' => 3,
                'defaultSetting' => array(
                    "min" => 1,
                    "max" => 12,
                    "prefix" => "",
                    "step" => 1,
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first last glue",
                "heading" => esc_attr__("Thumbnails Height", 'massive-dynamic'),
                "param_name" => "product_categories_height",
                "value" => '400',
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "product_categories_hover_color",
                "admin_label" => false,
                "opacity" => false,
                'value' => 'rgb(255,255,255,255)'
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "product_categories_overlay_color",
                "admin_label" => false,
                "opacity" => true,
                'value' => 'rgba(0,0,0,0.2)'
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first last glue",
                "heading" => esc_attr__("Hover Text", 'massive-dynamic'),
                "param_name" => "product_categories_hover_text",
                "value" => 'SEE THE COLLECTION',
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Meta Alignment", 'massive-dynamic'),
                "param_name" => "product_categories_align",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Center", 'massive-dynamic') => "center",
                    esc_attr__("Left Corner", 'massive-dynamic') => "left",
                ),
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "product_categories_description",
                "admin_label" => false,
                "value" => wp_kses(__("<ul>
                    <li>To edit fonts for category name and hover text, you should edit h4 and h6 typography. Category name uses h4, while hover text uses h6.</li>
                    <li>To add category thumbnail images, go to Dashboard > Products > Categories, click on the desired category and add an image in Thumbnail field.</li>
                </ul>", 'massive-dynamic'), array('ul' => array(), 'li' => array()))
            ),
        )
    )
);

$product_cats = array();

$terms = get_terms('product_cat', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $product_cats[$term->name] = $term->name;
    }
}

/*** Products Shortcode ***/
pixflow_map(
    array(
        'base' => 'md_products',
        'name' => esc_attr__('Products', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__("Commerce", 'massive-dynamic'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'products_categories',
                "admin_label" => false,
                'value' => $product_cats,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first last glue",
                "heading" => esc_attr__("Products Number", 'massive-dynamic'),
                "param_name" => "products_number",
                "value" => '-1',
                "admin_label" => false,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Columns', 'massive-dynamic'),
                'param_name' => 'products_cols',
                'value' => 3,
                'defaultSetting' => array(
                    "min" => 1,
                    "max" => 6,
                    "prefix" => "",
                    "step" => 1,
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first last glue",
                "heading" => esc_attr__("Thumbnails Height", 'massive-dynamic'),
                "param_name" => "products_height",
                "value" => '500',
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "products_align",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Center", 'massive-dynamic') => "center",
                ),
            ),
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("Products Style", 'massive-dynamic'),
                "param_name" => "products_style",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Classic", 'massive-dynamic') => "classic",
                    esc_attr__("Modern", 'massive-dynamic') => "modern",
                ),
            ),

            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . " first glue",
                "heading" => esc_attr__("Sale Badge Background", 'massive-dynamic'),
                "param_name" => "products_sale_bg_color",
                "admin_label" => false,
                "opacity" => true,
                'value' => 'rgba(255,255,255,1)',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "products_color_separator" . ++$separatorCounter,
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Sale Badge Text Color", 'massive-dynamic'),
                "param_name" => "products_sale_text_color",
                "admin_label" => false,
                "opacity" => true,
                'value' => 'rgba(0,0,0,1)',
            ),

            array(
                "type" => "md_vc_description",

                "param_name" => "products_description",
                "admin_label" => false,
                "value" => wp_kses(__("<ul>
                    <li>In products number field, you can choose all products by entering -1 </li>
                    <li>Please note that 'add to cart button' is not supposed to work correctly in builder environment</li>
                </ul>", 'massive-dynamic'), array('ul' => array(), 'li' => array()))
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Button', 'massive-dynamic'),
                'param_name' => 'products_use_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => false,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "products_use_button" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "products_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn kind
            array(
                "type" => "textfield",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "products_button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'More Products',
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "products_button_text_separator" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "products_button_icon_class",
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                ),
                'value' => 'icon-chevron-right'
            ),//btn icon
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "param_name" => "products_button_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(0,0,0,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "products_button_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "products_button_text_color",
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "admin_label" => false,
                "opacity" => true,
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                'value' => '#fff',
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "param_name" => "products_button_bg_hover_color",
                "admin_label" => false,
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
                'value' => '#9b9b9b'
            ),//bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_hover_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "param_name" => "products_button_hover_color",
                "admin_label" => false,
                "value" => "rgb(255,255,255)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),

            ),//btn hover text color
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "param_name" => "products_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "products_button_size_separator" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'products_left_right_padding',
                'value' => '0',
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn space
            array(
                "type" => "textfield",

                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "products_button_url",
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "products_button_linkr_separator" . ++$separatorCounter,
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "group" => esc_attr__("More Products Button", 'massive-dynamic'),
                "param_name" => "products_button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                "dependency" => array(
                    'element' => "products_use_button",
                    'value' => array('yes')
                )
            ),//btn target
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Counter
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_counter',
        'name' => esc_attr__('Counter', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                'type' => 'textfield',
                'edit_field_class' => $filedClass . "first glue",
                'param_name' => 'counter_from',
                'heading' => esc_attr__("From", 'massive-dynamic'),
                'value' => '0',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "counter_from_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "param_name" => "counter_to",
                "heading" => esc_attr__("To", 'massive-dynamic'),
                "value" => '46',
            ),
            array(
                "type" => 'textfield',
                "edit_field_class" => $filedClass . "first glue",
                "param_name" => 'counter_title',
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => 'Documents',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "counter_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "counter_title_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "counter_icon_class",
                "admin_label" => false,
                'value' => 'icon-Diamond',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "counter_icon_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "counter_icon_color",
                "value" => 'rgb(132,206,27)',
                "admin_label" => false,
                "opacity" => false,
            ),
        )
    )
);

/*******************************************************************
 *                  Count Box
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_countbox',
        'name' => esc_attr__('Count Box', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue last",
                "param_name" => "countbox_to",
                "heading" => esc_attr__("Count To", 'massive-dynamic'),
                "value" => '46',
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "countbox_title",
                "admin_label" => false,
                "value" => "YEARS OF MY EXPERIENCE",
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_title_separator" . ++$separatorCounter,
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "countbox_desc",
                "admin_label" => false,
                "value" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo.",
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "countbox_general_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_general_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Number Color", 'massive-dynamic'),
                "param_name" => "countbox_number_color",
                "value" => 'rgb(255,54,116)',
                "admin_label" => false,
                "opacity" => false,
                "group" => esc_attr__("General", 'massive-dynamic'),
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Button', 'massive-dynamic'),
                'param_name' => 'countbox_use_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "countbox_use_button" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "countbox_button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn kind
            array(
                "type" => "textfield",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "countbox_button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'READ MORE',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_text_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "countbox_button_icon_class",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                ),
                'value' => 'icon-angle-right'
            ),//btn icon
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(0,0,0,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(255,255,255,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_bg_hover_color",
                "admin_label" => false,
                "value" => "rgb(0,0,0)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),

            ),//btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('fill-oval', 'fill-rectangle')
                )
            ),//separator
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_hover_color",
                "admin_label" => false,
                "value" => "rgb(255,255,255)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),

            ),//btn text hover color
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_size_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'left_right_padding',
                'value' => '0',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//spacing
            array(
                "type" => "textfield",

                "group" => esc_attr__("Button", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "countbox_button_url",
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "countbox_button_linkr_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",

                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "countbox_button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                "dependency" => array(
                    'element' => "countbox_use_button",
                    'value' => array('yes')
                )
            ),//btn target
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Price Table
/*-----------------------------------------------------------------------------------*/

// Load Created Price tables form Go Pricing
if (class_exists('GW_GoPricing_Data')) {
    $pricing_tables = get_posts( 'post_type="go_pricing_tables"&numberposts=-1' );;
    if (count($pricing_tables)) {
        foreach ($pricing_tables as $pricing_table) {
            $title = $pricing_table->post_title;
            $id = $pricing_table->post_excerpt;
            if (!empty($title) && !empty($id)) $dropdown_data[$title] = $id;
        }
    }
} else {
    $dropdown_data = array();
}
if (empty($dropdown_data)) $dropdown_data[0] = esc_attr__('No tables found!', 'massive-dynamic');

pixflow_map(
    array(
        'base' => 'md_pricetabel',
        'name' => esc_attr__('Price Table', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Commerce', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Select Price Table', 'massive-dynamic'),
                "edit_field_class" => $filedClass . "first glue last",
                'param_name' => 'pricetable_id',
                'value' => $dropdown_data,
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "pricetable_attention",
                "admin_label" => false,
                "value" => esc_attr__("You should install Go Pricing plugin first, then create tables and use this shortcode to drop them in your website.", 'massive-dynamic'),
            ),
        )
    )
);


/* -------------------------------------------------------
--------------------------Horizontal Tab-----------------------------
---------------------------------------------------------*/
pixflow_map(array(
    "name" => esc_attr__('Horizontal Tabs', 'massive-dynamic'),
    'base' => 'md_hor_tabs',
    'show_settings_on_create' => false,
    'is_container' => true,
    "category" => esc_attr__("Tab", 'massive-dynamic'),
    'description' => esc_attr__('Tabbed content', 'massive-dynamic'),
    'as_parent' => array('only' => 'md_hor_tab'),
    'params' => array(
        array(
            "type" => "md_vc_colorpicker",

            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "general_color",
            "value" => "rgb(255,255,255)",
            "admin_label" => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "general_color_separator" . ++$separatorCounter,
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Use Background', 'massive-dynamic'),
            'param_name' => 'use_bg',
            'value' => array(esc_attr__('yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "use_bg_separator" . ++$separatorCounter,
            "admin_label" => false,
            "dependency" => array(
                'element' => "use_bg",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Background Type", 'massive-dynamic'),
            "param_name" => "bg_type",
            "dependency" => array(
                'element' => "use_bg",
                'value' => 'yes',
            ),
            "value" => array(
                esc_attr__("Color", 'massive-dynamic') => "color",
                esc_attr__("Image", 'massive-dynamic') => "image",
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "bg_type_separator" . ++$separatorCounter,
            "dependency" => array(
                'element' => "use_bg",
                'value' => 'yes',
            ),
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Background Color", 'massive-dynamic'),
            "param_name" => "bg_color",
            "value" => 'rgb(215,176,126)',
            "admin_label" => false,
            "opacity" => true,
            "dependency" => array(
                'element' => "bg_type",
                'value' => array('color')
            )
        ),
        array(
            'type' => 'attach_image',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Background Image', 'massive-dynamic'),
            'param_name' => 'bg_image',
            "dependency" => array(
                'element' => "bg_type",
                'value' => array('image')
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "bg_type_separator" . ++$separatorCounter,
            "edit_field_class" => $filedClass . "stick-to-top",
            "dependency" => array(
                'element' => "use_bg",
                'value' => 'no',
            ),
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "param_name" => "hor_tab_hover_color",
            "value" => 'rgb(215,176,126)',
            "admin_label" => false,
            "opacity" => true,
            "dependency" => array(
                'element' => "use_bg",
                'value' => 'no',
            ),
        ),
    ),
    'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
    'default_content' => '
[md_hor_tab title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab]
[md_hor_tab title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab]
[md_hor_tab title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab]
[md_hor_tab title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab]
',
    'js_view' => 'MdHorTabsView'
));

pixflow_map(array(
    'name' => esc_attr__('Horizontal Tab', 'massive-dynamic'),
    'base' => 'md_hor_tab',
    'allowed_container_element' => 'vc_row',
    'is_container' => true,
    'content_element' => false,
    'params' => array(
        array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "glue first",
            'heading' => esc_attr__('Title', 'massive-dynamic'),
            'param_name' => 'title',
            "value" => "TAB",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_iconpicker",

            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "tab_icon",
            "value" => "icon-cog",
            "admin_label" => false,
        )
    ),
    'js_view' => 'MdHorTabView'
));

define('HOR_TAB_TITLE', '');

class Pixflow_PixflowShortCode_Md_HorTab extends WPBakeryShortCode_VC_Column
{
    protected $controls_css_settings = 'tc vc_control-container';
    protected $controls_list = array('add', 'edit', 'clone', 'delete');
    protected $predefined_atts = array(
        'tab_id' => HOR_TAB_TITLE,
        'title' => '',
        'tab_icon' => ''
    );
    protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

    public function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function mainHtmlBlockParams($width, $i)
    {
        return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
    }

    public function customAdminBlockParams()
    {
        return ' id="tab-' . $this->atts['tab_id'] . '"';
    }

    public function containerHtmlBlockParams($width, $i)
    {
        return 'class="wpb_column_container vc_container_for_children"';
    }

    public function getColumnControls($controls, $extended_css = '')
    {
        return $this->getColumnControlsModular($extended_css);
    }
}

class Pixflow_PixflowShortCode_Md_HorTabs extends WPBakeryShortCode
{
    static $filter_added = false;
    protected $controls_css_settings = 'out-tc vc_controls-content-widget';
    protected $controls_list = array('edit', 'clone', 'delete');

    public function __construct($settings)
    {
        parent::__construct($settings);
        if (!self::$filter_added) {
            $this->addFilter('vc_inline_template_content', 'setCustomTabId');
            self::$filter_added = true;
        }
    }

    public function contentAdmin($atts, $content = null)
    {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ($this->settings['params'] as $param) {
            if ($param['param_name'] != 'content') {
                if (isset($param['value']) && is_string($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                } elseif (isset($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ($param['param_name'] == 'content' && $content == null) {
                $content = $param['value'];
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));


        preg_match_all('/md_hor_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $output = '';
        $tab_titles = array();

        if (isset($matches[0])) {
            $tab_titles = $matches[0];
        }
        $tmp = '';
        if (count($tab_titles)) {
            $tmp .= '<ul class="clearfix tabs_controls">';
            foreach ($tab_titles as $tab) {
                preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
                if (isset($tab_matches[1][0])) {
                    $tmp .= '<li><a href="#tab-' . (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title($tab_matches[1][0])) . '">' . $tab_matches[1][0] . '</a><span class="' . $tab_icon . '" ></span></li>';

                }
            }
            $tmp .= '</ul>' . "\n";
        } else {
            $output .= do_shortcode($content);
        }


        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $custom_markup = '';
            $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
            if (is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }

        if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
            if ($content != '') {
                $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
            } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
            } else {
                $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            //$output .= do_shortcode($this->settings["custom_markup"]);
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;
        return $output;
    }

    public function getTabTemplate()
    {
        return '<div class="wpb_template">' . do_shortcode('[md_hor_tab title="TAB" tab_id="" tab_icon=""][/md_hor_tab]') . '</div>';
    }

    public function setCustomTabId($content)
    {
        return preg_replace('/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content);
    }
}

/* -------------------------------------------------------
-----------------Horizontal Tab 2-------------------------
---------------------------------------------------------*/
pixflow_map(array(
    "name" => esc_attr__('Business Tab', 'massive-dynamic'),
    'base' => 'md_hor_tabs2',
    'show_settings_on_create' => false,
    'is_container' => true,
    "category" => esc_attr__("Tab", 'massive-dynamic'),
    'description' => esc_attr__('Tabbed content', 'massive-dynamic'),
    'as_parent' => array('only' => 'md_hor_tab2'),
    'params' => array(
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "general_color",
            "value" => "rgb(0,0,0)",
            "admin_label" => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "general_color_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "param_name" => "hor_tab_hover_color",
            "value" => 'rgb(215,176,126)',
            "admin_label" => false
        ),
    ),
    'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
    'default_content' => '
[md_hor_tab2 title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab2]
[md_hor_tab2 title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab2]
[md_hor_tab2 title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab2]
[md_hor_tab2 title="' . esc_attr__('Tab', 'massive-dynamic') . '" tab_id="' . uniqid('tab') . '"][/md_hor_tab2]
',
    'js_view' => 'MdHorTabs2View'
));

pixflow_map(array(
    'name' => esc_attr__('Horizontal Tab 2', 'massive-dynamic'),
    'base' => 'md_hor_tab2',
    'allowed_container_element' => 'vc_row',
    'is_container' => true,
    'content_element' => false,
    'params' => array(
        array(
            'type' => 'textfield',
            "edit_field_class" => $filedClass . "glue first",
            'heading' => esc_attr__('Title', 'massive-dynamic'),
            'param_name' => 'title',
            "value" => "TAB",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "title_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "tab_icon",
            "value" => "icon-cog",
            "admin_label" => false,
        )
    ),
    'js_view' => 'MdHorTab2View'
));

define('HOR_TAB2_TITLE', '');

class Pixflow_PixflowShortCode_Md_HorTab2 extends WPBakeryShortCode_VC_Column
{
    protected $controls_css_settings = 'tc vc_control-container';
    protected $controls_list = array('add', 'edit', 'clone', 'delete');
    protected $predefined_atts = array(
        'tab_id' => HOR_TAB2_TITLE,
        'title' => '',
        'tab_icon' => ''
    );
    protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

    public function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function mainHtmlBlockParams($width, $i)
    {
        return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
    }

    public function customAdminBlockParams()
    {
        return ' id="tab-' . $this->atts['tab_id'] . '"';
    }

    public function containerHtmlBlockParams($width, $i)
    {
        return 'class="wpb_column_container vc_container_for_children"';
    }

    public function getColumnControls($controls, $extended_css = '')
    {
        return $this->getColumnControlsModular($extended_css);
    }
}

class Pixflow_PixflowShortCode_Md_HorTabs2 extends WPBakeryShortCode
{
    static $filter_added = false;
    protected $controls_css_settings = 'out-tc vc_controls-content-widget';
    protected $controls_list = array('edit', 'clone', 'delete');

    public function __construct($settings)
    {
        parent::__construct($settings);
        if (!self::$filter_added) {
            $this->addFilter('vc_inline_template_content', 'setCustomTabId');
            self::$filter_added = true;
        }
    }

    public function contentAdmin($atts, $content = null)
    {
        $width = $custom_markup = '';
        $shortcode_attributes = array('width' => '1/1');
        foreach ($this->settings['params'] as $param) {
            if ($param['param_name'] != 'content') {
                if (isset($param['value']) && is_string($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                } elseif (isset($param['value'])) {
                    $shortcode_attributes[$param['param_name']] = $param['value'];
                }
            } else if ($param['param_name'] == 'content' && $content == null) {
                $content = $param['value'];
            }
        }
        extract(shortcode_atts(
            $shortcode_attributes
            , $atts));


        preg_match_all('/md_hor_tab2 title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $output = '';
        $tab_titles = array();

        if (isset($matches[0])) {
            $tab_titles = $matches[0];
        }
        $tmp = '';
        if (count($tab_titles)) {
            $tmp .= '<ul class="clearfix tabs_controls">';
            foreach ($tab_titles as $tab) {
                preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
                if (isset($tab_matches[1][0])) {
                    $tmp .= '<li><a href="#tab-' . (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title($tab_matches[1][0])) . '">' . $tab_matches[1][0] . '</a><span class="' . $tab_icon . '" ></span></li>';

                }
            }
            $tmp .= '</ul>' . "\n";
        } else {
            $output .= do_shortcode($content);
        }


        $elem = $this->getElementHolder($width);

        $iner = '';
        foreach ($this->settings['params'] as $param) {
            $custom_markup = '';
            $param_value = isset($$param['param_name']) ? $$param['param_name'] : '';
            if (is_array($param_value)) {
                // Get first element from the array
                reset($param_value);
                $first_key = key($param_value);
                $param_value = $param_value[$first_key];
            }
            $iner .= $this->singleParamHtmlHolder($param, $param_value);
        }

        if (isset($this->settings["custom_markup"]) && $this->settings["custom_markup"] != '') {
            if ($content != '') {
                $custom_markup = str_ireplace("%content%", $tmp . $content, $this->settings["custom_markup"]);
            } else if ($content == '' && isset($this->settings["default_content_in_template"]) && $this->settings["default_content_in_template"] != '') {
                $custom_markup = str_ireplace("%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"]);
            } else {
                $custom_markup = str_ireplace("%content%", '', $this->settings["custom_markup"]);
            }
            $iner .= do_shortcode($custom_markup);
        }
        $elem = str_ireplace('%wpb_element_content%', $iner, $elem);
        $output = $elem;
        return $output;
    }

    public function getTabTemplate()
    {
        return '<div class="wpb_template">' . do_shortcode('[md_hor_tab2 title="TAB" tab_id="" tab_icon=""][/md_hor_tab2]') . '</div>';
    }

    public function setCustomTabId($content)
    {
        return preg_replace('/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content);
    }
}

/*******************************************************************
 *                  Skill Piechart
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_pie_chart',
        'name' => esc_attr__('Skill Pie Chart', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue ",
                "param_name" => "pie_chart_title",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => 'Animation',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_title_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue ",
                'heading' => esc_attr__('Percent', 'massive-dynamic'),
                'param_name' => 'pie_chart_percent',
                'value' => '70',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "100",
                    "step" => "1",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Chart Color", 'massive-dynamic'),
                "param_name" => "pie_chart_percent_color",
                "value" => 'rgb(34,188,168)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "pie_chart_text_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
        )
    )
);

/*******************************************************************
 *                  Skill Piechart 2
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_pie_chart2',
        'name' => esc_attr__('Skill Pie Chart 2', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue ",
                "param_name" => "pie_chart2_title",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => 'Animation',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart2_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue ",
                "param_name" => "pie_chart2_bottom_title",
                "heading" => esc_attr__("Bottom Title", 'massive-dynamic'),
                "value" => '',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart2_title_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Percent', 'massive-dynamic'),
                'param_name' => 'pie_chart2_percent',
                'value' => '70',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "100",
                    "step" => "1",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Show Percentage', 'massive-dynamic'),
                'param_name' => 'pie_chart_2_show_type',
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),//Show PErcentage
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "pie_chart2_icon",
                "value" => "icon-cog",
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Thickness', 'massive-dynamic'),
                'param_name' => 'pie_chart_2_line_width',
                'value' => '9',
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "40",
                    "step" => "1",
                ),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue ",
                "param_name" => "pie_chart2_animation_delay",
                "heading" => esc_attr__("Animation Delay", 'massive-dynamic'),
                "value" => '',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart2_percent_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Animation Easing', 'massive-dynamic'),
                'param_name' => 'pie_chart2_animation',
                'checked' => true,
                "value" => array(
                    "easeInOutQuart" => "easeInOutQuart",
                    "easeOutBack" => "easeOutBack",
                    "easeOutBounce" => "easeOutBounce",
                    "easeOutElastic" => "easeOutElastic",
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Chart Color", 'massive-dynamic'),
                "param_name" => "pie_chart2_percent_color",
                "value" => 'rgb(34,188,168)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "pie_chart_percent_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "pie_chart2_text_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
        )
    )
);


/*******************************************************************
 *                  Google Map
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_google_map',
        'name' => esc_attr__('Custom Google Map', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue ",
                "param_name" => "md_google_map_lat",
                "heading" => esc_attr__("Map latitude", "massive-dynamic"),
                "value" => '37.7533106',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_google_map_lat_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue ",
                'heading' => esc_attr__('Map Longitude', 'massive-dynamic'),
                'param_name' => 'md_google_map_lon',
                'value' => '-122.4818691',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_google_map_lon_separator" . ++$separatorCounter,
            ),

            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Map Zoom', 'massive-dynamic'),
                'param_name' => 'md_google_map_zoom',
                'value' => '15',
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "19",
                    "step" => "1",
                ),
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Choose Type", "massive-dynamic"),
                "param_name" => "md_google_map_type",
                "value" => array(
                    esc_attr__("Gray", 'massive-dynamic') => "gray",
                    esc_attr__("Map", 'massive-dynamic') => "map",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_google_map_type_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue",
                'heading' => esc_attr__('Choose Marker', 'massive-dynamic'),
                'param_name' => 'md_google_map_marker',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_google_map_marker_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Map Height', 'massive-dynamic'),
                'param_name' => 'md_google_map_height',
                'value' => '400',
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "glue last first",
                'heading' => esc_attr__('API Key', 'massive-dynamic'),
                'param_name' => 'md_google_map_key',
                'value' => '',
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "md_google_map_description",
                "admin_label" => false,
                "value" => esc_attr__("To use google map you should go to google app console and create a browser api key for your site", 'massive-dynamic'),
            ),

        )
    )
);

/*******************************************************************
 *                  Master Slider
 ******************************************************************/
$master_sliders = array();
if (function_exists('get_masterslider_names')) {
    pixflow_remove_element("masterslider_pb");
    $slides = get_masterslider_names('title-id');
    $master_sliders = array_merge(array(esc_attr__('Select slider', 'massive-dynamic') => ''), $slides);
}
pixflow_map(
    array(
        'name' => 'Master Slider',
        'base' => 'md_masterslider',
        'controls' => 'full',
        'show_settings_on_create' => false,
        "category" => esc_attr__('Media', 'massive-dynamic'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Master Slider', 'massive-dynamic'),
                'param_name' => 'md_masterslider_id',
                'value' => $master_sliders,
            )
        )
    )
);

/*******************************************************************
 *                  Rev Slider
 ******************************************************************/
$revsliders = array();
if (class_exists('RevSlider')) {
    pixflow_remove_element("rev_slider_vc");
    pixflow_remove_element("rev_slider");
    $slider = new RevSlider();
    $arrSliders = $slider->getArrSliders();
    if ($arrSliders) {
        foreach ($arrSliders as $slider) {
            $revsliders[$slider->getTitle()] = $slider->getAlias();
        }
    } else {
        $revsliders[esc_attr__('No sliders found', 'massive-dynamic')] = 0;
    }
}
pixflow_map(
    array(
        'name' => 'Revolution Slider',
        'base' => 'md_rev_slider',
        'controls' => 'full',
        'show_settings_on_create' => false,
        "category" => esc_attr__('Media', 'massive-dynamic'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__('Select Slider', 'massive-dynamic'),
                'param_name' => 'md_rev_slider_alias',
                'value' => $revsliders,
            )
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Blog Classic
/*-----------------------------------------------------------------------------------*/
$posts_cats = array();
$terms = get_terms('category', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $posts_cats[] = $term->name;
    }
}
pixflow_map(
    array(
        'base' => 'md_blog_classic',
        'name' => esc_attr__('Blog Classic', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Media','massive-dynamic'),
        'params' => array(

            array(
                'type' => 'md_vc_multiselect',
                "edit_field_class" => $filedClass . "first glue",
                'heading' => esc_attr__('Category', 'massive-dynamic'),
                'param_name' => 'blog_category',
                'items' => $posts_cats,
                'defaults' => 'all',
            ), array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Post Per Page", 'massive-dynamic'),
                "param_name" => "blog_post_number",
                "admin_label" => false,
                "value" => '5',
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title Color", 'massive-dynamic'),
                "param_name" => "blog_title_color",
                "value" => 'rgb(68,37,153)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_text_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "blog_text_color",
                "value" => 'rgb(163,163,163)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_text_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",

                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Category Color", 'massive-dynamic'),
                "param_name" => "blog_category_color",
                "value" => 'rgb(52,202,161)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_text_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Shadow Color", 'massive-dynamic'),
                "param_name" => "blog_shadow_color",
                "value" => 'rgba(0,0,0,.12)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Alignment", "massive-dynamic"),
                "param_name" => "blog_category_align",
                "value" => array(
                    esc_attr__("Left", 'massive-dynamic') => "left",
                    esc_attr__("Center", 'massive-dynamic') => "center",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_align_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title Size", 'massive-dynamic'),
                "param_name" => "blog_title_size",
                "admin_label" => false,
                'value' => array(
                    esc_attr__("Large", 'massive-dynamic') => "47",
                    esc_attr__("Medium", 'massive-dynamic') => "35",
                    esc_attr__("Small", 'massive-dynamic') => "25"
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "blog_category_align_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_checkbox",
                "edit_field_class" => $filedClass . "glue  last",
                "param_name" => "blog_category_author",
                "heading" => esc_attr__("Show Author", "massive-dynamic"),
                'checked' => true,
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "blog_description",
                "admin_label" => false,
                "value" => esc_attr__("To add blog posts, go to WordPress Dashboard > Posts > add new", 'massive-dynamic'),
            ),

        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Icon
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Icon",
        "base" => "md_icon",
        "category" => esc_attr__('Media','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Source", 'massive-dynamic'),
                "param_name" => "icon_source",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Massive Dynamic Icons", 'massive-dynamic') => "massive_dynamic",
                    esc_attr__("Custom Icon", 'massive-dynamic') => "custom",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "icon_icon",
                "value" => "icon-diamond",
                "admin_label" => false,
                "dependency" => array(
                    'element' => "icon_source",
                    'value' => array('massive_dynamic')
                )
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("SVG URL", 'massive-dynamic'),
                "param_name" => "icon_url",
                "value" => "http://",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "icon_color",
                "opacity" => true,
                "value" => "#5f5f5f",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('massive_dynamic')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Fill Color", 'massive-dynamic'),
                "param_name" => "icon_fill_color",
                "opacity" => true,
                "value" => "rgba(0,0,0,1)",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Icon Stroke Color", 'massive-dynamic'),
                "param_name" => "icon_stroke_color",
                "opacity" => true,
                "value" => "rgba(0,0,0,1)",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "icon_hover_color",
                "opacity" => true,
                "value" => "#b6b6b6",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('massive_dynamic')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Hover Fill Color", 'massive-dynamic'),
                "param_name" => "icon_hover_fill_color",
                "opacity" => true,
                "value" => "rgba(100,100,100,1)",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Hover Stroke Color", 'massive-dynamic'),
                "param_name" => "icon_hover_stroke_color",
                "opacity" => true,
                "value" => "rgba(100,100,100,1)",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "icon_source",
                    'value' => array('custom')
                )
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Size', 'massive-dynamic'),
                'param_name' => 'icon_size',
                'value' => '153',
                'defaultSetting' => array(
                    "min" => "10",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            array(
                "type" => "md_vc_checkbox",
                "edit_field_class" => $filedClass . "glue first last",
                "param_name" => "icon_use_link",
                "heading" => esc_attr__('Set Link', 'massive-dynamic'),
                'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
                'checked' => false,
            ),
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue",
                'heading' => esc_attr__('Link URL', 'massive-dynamic'),
                'param_name' => 'icon_link',
                'value' => 'http://',
                'dependency' => array(
                    'element' => 'icon_use_link',
                    'value' => array('yes')
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_link_separator" . ++$separatorCounter,
                "admin_label" => false,
                'dependency' => array(
                    'element' => 'icon_use_link',
                    'value' => array('yes')
                )
            ),
            array(
                'type' => 'dropdown',
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Open in", 'massive-dynamic'),
                "param_name" => "icon_link_target",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("New Tab", 'massive-dynamic') => "_blank",
                    esc_attr__("This Window", 'massive-dynamic') => "_self",
                ),
                'dependency' => array(
                    'element' => 'icon_use_link',
                    'value' => array('yes')
                )
            )
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Text in Box
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Text in Box",
        "base" => "md_textbox",
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "textbox_icon",
                "value" => "icon-diamond",
                "admin_label" => false
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "textbox_title",
                "value" => "Figure it out",
                "description" => esc_attr__("Iconbox heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
                "admin_label" => false
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "textbox_heading",
                "admin_label" => false,
                "value" => array(
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
                "admin_label" => false
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "textbox_description",
                "value" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
                "admin_label" => false
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "textbox_bg_color",
                "value" => "#FFF",
                "admin_label" => false
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
                "admin_label" => false
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "textbox_icon_color",
                "value" => "#01b1ae",
                "admin_label" => false
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => ++$separatorCounter,
                "admin_label" => false
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Content Color", 'massive-dynamic'),
                "param_name" => "textbox_content_color",
                "value" => "#5e5e5e",
                "admin_label" => false
            )
        )
    )
);
/*-----------------------------------------------------------------------------------*/
/*  Fancy Text
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Fancy Text",
        "base" => "md_fancy_text",
        "category" => esc_attr__('Media','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,
        "params" => array(
            array(
                'type' => 'textfield',
                "edit_field_class" => $filedClass . "first glue",
                'heading' => esc_attr__('Title', 'massive-dynamic'),
                'param_name' => 'fancy_text_title',
                'value' => 'Fancy Text',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "fancy_text_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "fancy_text_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H5" => "h5",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "fancy_text_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                'type' => 'textarea',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Text', 'massive-dynamic'),
                'param_name' => 'fancy_text_text',
                'value' => 'Massive Dynamic has over 10 years of experience in Design. We take pride in delivering Intelligent Designs and Engaging Experiences for clients all over the World.',
            ),


            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Type", 'massive-dynamic'),
                "param_name" => "fancy_text_bg_type",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Icon", 'massive-dynamic') => "icon",
                    esc_attr__("Text", 'massive-dynamic') => "text",
                ),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "fancy_text_separator" . ++$separatorCounter,
                "admin_label" => false,

            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "fancy_text_icon",
                "value" => "icon-MusicalNote",
                "admin_label" => false,
                "dependency" => array(
                    'element' => "fancy_text_bg_type",
                    'value' => array('icon')
                )
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Background Text", 'massive-dynamic'),
                "param_name" => "fancy_text_bg_text",
                "value" => "01",
                "admin_label" => false,
                'dependency' => array(
                    'element' => "fancy_text_bg_type",
                    'value' => array('text')
                )
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title Color", 'massive-dynamic'),
                "param_name" => "fancy_text_title_color",
                "opacity" => true,
                "value" => "rgba(55,55,55,1)",
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "fancy_text_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "fancy_text_text_color",
                "opacity" => true,
                "value" => "rgba(55,55,55,1)",
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "fancy_text_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "fancy_text_bg_color",
                "opacity" => true,
                "value" => "rgba(7, 0, 255, 0.15)",
                "admin_label" => false,
            ),

        )
    )
);

/*-----------------------------------------------------------------------------------
            Pixflow Slider
-----------------------------------------------------------------------------------*/

function pixflow_slider_param()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $slider_param = 'slider_num';
    $slider_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number Of Slides:", 'massive-dynamic'),
            "param_name" => $slider_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Skin", 'massive-dynamic'),
            "param_name" => 'slider_skin',
            "admin_label" => false,
            "value" => array(
                esc_attr__("Horizontal Left", 'massive-dynamic') => "hr-left",
                esc_attr__("Horizontal Center", 'massive-dynamic') => "hr-center",

            ),
            'dependency' => array(
                'callback' => 'pixflow_pixflowSliderDependency_contentType'
            )
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Auto Play', 'massive-dynamic'),
            'param_name' => 'slider_autoplay',
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => false,
            "group" => esc_attr__("General", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("General", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_autoplay",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Auto Play Duration', 'massive-dynamic'),
            'param_name' => 'slider_autoplay_duration',
            "group" => esc_attr__('General', 'massive-dynamic'),
            "value" => "3",
            'defaultSetting' => array(
                "min" => "1",
                "max" => "30",
                "prefix" => " s",
                "step" => "0.1",
                "decimal" => "1",
            ),
            "dependency" => array(
                'element' => "slider_autoplay",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "heading" => esc_attr__("Slider Height", 'massive-dynamic'),
            "param_name" => "slider_height_mode",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "value" => array(
                esc_attr__("Fit To Screen", 'massive-dynamic') => "fit",
                esc_attr__("Custom", 'massive-dynamic') => "custom",
            )
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("General", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_height_mode",
                'value' => array('custom')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Custom Height', 'massive-dynamic'),
            'param_name' => 'slider_height',
            "group" => esc_attr__('General', 'massive-dynamic'),
            "value" => "600",
            'defaultSetting' => array(
                "min" => "400",
                "max" => "2000",
                "prefix" => " px",
                "step" => "1"
            ),
            "dependency" => array(
                'element' => "slider_height_mode",
                'value' => array('custom'),
            )
        ),


        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last first",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Indicators :", 'massive-dynamic'),
            "param_name" => "slider_indicator",
            "admin_label" => false,
            "value" => array(
                esc_attr__("Do Not Show", 'massive-dynamic') => "off",
                esc_attr__("Oval Mode", 'massive-dynamic') => "oval",
                esc_attr__("Circle Mode", 'massive-dynamic') => "circle",
            )
        ),

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Indicator Theme :", 'massive-dynamic'),
            "param_name" => "slider_indicator_theme",
            "admin_label" => false,
            "value" => array(
                esc_attr__("Dark", 'massive-dynamic') => "indicator-dark",
                esc_attr__("Light", 'massive-dynamic') => "indicator-light",
            ),
            "dependency" => array(
                'element' => "slider_indicator",
                'value' => array('oval', 'circle')
            )

        ),

        /*Title Typo*/
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Tilte Custom font', 'massive-dynamic'),
            'param_name' => 'slider_title_custom_font',
            'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
            'checked' => false,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'google_fonts',
            'preview' => false,
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            'param_name' => 'slider_title_font',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_attr__('Font family', 'massive-dynamic'),
                    'font_style_description' => esc_attr__('Font styling', 'massive-dynamic')
                )
            ),
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue",
            'heading' => esc_attr__('Title Size', 'massive-dynamic'),
            'param_name' => 'slider_title_size',
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => "70",
            'defaultSetting' => array(
                "min" => "20",
                "max" => "120",
                "prefix" => " px",
                "step" => "1"
            ),
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Title Line Height', 'massive-dynamic'),
            'param_name' => 'slider_title_line_height',
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => "80",
            'defaultSetting' => array(
                "min" => "20",
                "max" => "120",
                "prefix" => " px",
                "step" => "1"
            ),
            "dependency" => array(
                'element' => "slider_title_custom_font",
                'value' => array('yes')
            )
        ),
        /*Subtitle Typo*/
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Subtitle Custom font', 'massive-dynamic'),
            'param_name' => 'slider_subtitle_custom_font',
            'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
            'checked' => false,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'google_fonts',
            'preview' => false,
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            'param_name' => 'slider_subtitle_font',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_attr__('Font family', 'massive-dynamic'),
                    'font_style_description' => esc_attr__('Font styling', 'massive-dynamic')
                )
            ),
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue",
            'heading' => esc_attr__('Subtitle Size', 'massive-dynamic'),
            'param_name' => 'slider_subtitle_size',
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => "20",
            'defaultSetting' => array(
                "min" => "20",
                "max" => "100",
                "prefix" => " px",
                "step" => "1"
            ),
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Subtitle Line Height', 'massive-dynamic'),
            'param_name' => 'slider_subtitle_line_height',
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => "20",
            'defaultSetting' => array(
                "min" => "20",
                "max" => "100",
                "prefix" => " px",
                "step" => "1"
            ),
            "dependency" => array(
                'element' => "slider_subtitle_custom_font",
                'value' => array('yes')
            )
        ),
        /*Description Typo*/
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last classic-hidden",
            'heading' => esc_attr__('Description Custom font', 'massive-dynamic'),
            'param_name' => 'slider_desc_custom_font',
            'value' => array(esc_attr__('No', 'massive-dynamic') => 'no'),
            'checked' => false,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top classic-hidden",
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "slider_desc_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            'type' => 'google_fonts',
            'preview' => false,
            "edit_field_class" => $filedClass . "glue last classic-hiddenn",
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            'param_name' => 'slider_desc_font',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_attr__('Font family', 'massive-dynamic'),
                    'font_style_description' => esc_attr__('Font styling', 'massive-dynamic')
                )
            ),
            "dependency" => array(
                'element' => "slider_desc_custom_font",
                'value' => array('yes')
            )
        ),
        array(
            "type" => "md_vc_description",
            "param_name" => "slider_typography_description",
            "admin_label" => false,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => '<ul><li>' . esc_attr__('By default pixflow slider get its font setting from ...... but if you choose custom font you can set your typography setting from  google font.', 'massive-dynamic') . '</li></ul>',
        ),
        array(
            "type" => "md_vc_description",
            "param_name" => "slider_typography_description",
            "admin_label" => false,
            "group" => esc_attr__("Typography", 'massive-dynamic'),
            "value" => '<ul><li>' . esc_attr__('Consider that custom font setting will load extra files and will affect your website load speed. ', 'massive-dynamic') . '</li></ul>',
        ),

    );
    $s = 1;
    for ($i = 1; $i <= (int)$slider_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $slider_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first last textNsize-text",
            "separate" => true,
            "heading" => esc_attr__("Title Type", 'massive-dynamic'),
            "param_name" => "slide_content_type_" . $i,
            "admin_label" => false,
            "value" => array(
                esc_attr__("Text", 'massive-dynamic') => "text",
                esc_attr__("Image", 'massive-dynamic') => "image"
            ),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),

        );//Content Type

        /*Title*/
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue first last textNsize-text",
            "heading" => esc_attr__("Slide Title", 'massive-dynamic'),
            "param_name" => "slide_title_" . $i,
            'value' => 'Massive Dynamic <br> Unique Slider',
            "admin_label" => false,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => "slide_content_type_" . $i,
                'value' => 'text'
            ),
        );
        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "textNsize-size glue-color-textarea",
            "heading" => esc_attr__("Title Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_title_color_" . $i,
            "admin_label" => false,
            "value" => "#ffffff",
            'dependency' => array(
                'element' => "slide_content_type_" . $i,
                'value' => 'text'
            ),
        );

        /* Image */
        $param[] = array(
            'type' => 'attach_image',
            'edit_field_class' => $filedClass . "glue first last",
            'heading' => esc_attr__('Content Image', 'massive-dynamic'),
            'param_name' => 'slide_content_image_' . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => "slide_content_type_" . $i,
                'value' => 'image'
            ),
        );

        /*Subtitle*/
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue last textNsize-text",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("subtitle", 'massive-dynamic'),
            "param_name" => "slide_subtitle_" . $i,
            "value" => 'Know About',
            "admin_label" => false,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue textNsize-size",
            "heading" => esc_attr__("Subtitle Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_subtitle_color_" . $i,
            "admin_label" => false,
            "value" => "#dbdbdb",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),

        );

        /*Description*/
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue first last textNsize-text classic-hidden",
            "heading" => esc_attr__("Slide Description", 'massive-dynamic'),
            "param_name" => "slide_desc_" . $i,
            'value' => 'Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet.',
            "admin_label" => false,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value,

            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "textNsize-size glue-color-textarea classic-hidden",
            "heading" => esc_attr__("Description Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_desc_color_" . $i,
            "admin_label" => false,
            "value" => "rgb(0, 255, 153)",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        /* Image  */
        $param[] = array(
            'type' => 'attach_image',
            'edit_field_class' => $filedClass . "glue first last glue-color-image",
            'heading' => esc_attr__('Slide Image', 'massive-dynamic'),
            'param_name' => 'slide_image_' . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "textNsize-size glue-color-image",
            "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_image_color_" . $i,
            "admin_label" => false,
            "value" => "rgba(0, 0, 0, 0.4)",
            "opacity" => true,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        /* Button 1 */
        $param[] = array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last ",
            'heading' => esc_attr__('Button1', 'massive-dynamic'),
            'param_name' => 'slide_btn1_' . $i,
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value,
                'callback' => 'pixflow_pixflowSliderDependency_btn'
            )
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue stick-to-top slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "separate" => true,
            "heading" => esc_attr__("Button Style", 'massive-dynamic'),
            "param_name" => "slide_btn1_kind_" . $i,
            "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
            "admin_label" => false,
            "value" => array(
                esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                esc_attr__("Slide", 'massive-dynamic') => "slide",
                esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                esc_attr__("Animation", 'massive-dynamic') => "animation",
                esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
            ),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );//btn kind

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "slide_btn1_title_" . $i,
            "value" => 'DOWNLOAD',
            "admin_label" => false,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Link", 'massive-dynamic'),
            "param_name" => "slide_btn1_link_" . $i,
            "value" => 'http://massivedynamic.co/',
            "admin_label" => false,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "heading" => esc_attr__("Target", 'massive-dynamic'),
            "param_name" => "slide_btn1_target_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "value" => array(
                esc_attr__("Open in new window", 'massive-dynamic') => "_blank",
                esc_attr__("Open in same window", 'massive-dynamic') => "_self"
            ),
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "heading" => esc_attr__("Button Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_btn1_color_" . $i,
            "admin_label" => false,
            "value" => "#FFF",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),

        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
            "param_name" => "slide_btn1_bg_hover_color_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            "dependency" => array(
                'element' => "slide_btn1_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            'value' => '#9b9b9b'
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "dependency" => array(
                'element' => "slide_btn1_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "slide_btn1_text_hover_color_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            "dependency" => array(
                'element' => "slide_btn1_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            'value' => '#000'
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn1_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "dependency" => array(
                'element' => "slide_btn1_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last slide_btn1_" . $i . '_dependency',
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_btn1_hover_color_" . $i,
            "admin_label" => false,
            "value" => "#ff0054",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),

        );
        //button2
        $param[] = array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last ",
            'heading' => esc_attr__('Button2', 'massive-dynamic'),
            'param_name' => 'slide_btn2_' . $i,
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value,
                'callback' => 'pixflow_pixflowSliderDependency_btn'
            )
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " glue stick-to-top slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "separate" => true,
            "heading" => esc_attr__("Button Style", 'massive-dynamic'),
            "param_name" => "slide_btn2_kind_" . $i,
            "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
            "admin_label" => false,
            "value" => array(
                esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                esc_attr__("Slide", 'massive-dynamic') => "slide",
                esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                esc_attr__("Animation", 'massive-dynamic') => "animation",
                esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
            ),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );//btn kind

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . " slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "slide_btn2_title_" . $i,
            "value" => 'READ MORE',
            "admin_label" => false,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Link", 'massive-dynamic'),
            "param_name" => "slide_btn2_link_" . $i,
            "value" => 'http://demo.massivedynamic.co/general/',
            "admin_label" => false,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "heading" => esc_attr__("Target", 'massive-dynamic'),
            "param_name" => "slide_btn2_target_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "value" => array(
                esc_attr__("Open in new window", 'massive-dynamic') => "_blank",
                esc_attr__("Open in same window", 'massive-dynamic') => "_self"
            ),
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "heading" => esc_attr__("Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_btn2_color_" . $i,
            "admin_label" => false,
            "value" => "#FFF",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),

        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            )
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
            "param_name" => "slide_btn2_bg_hover_color_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            "dependency" => array(
                'element' => "slide_btn2_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            'value' => '#9b9b9b'
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "dependency" => array(
                'element' => "slide_btn2_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "slide_btn2_text_hover_color_" . $i,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            "dependency" => array(
                'element' => "slide_btn2_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            'value' => '#000'
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "glue slide_btn2_" . $i . '_dependency',
            "param_name" => "slider" . ++$separatorCounter,
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "dependency" => array(
                'element' => "slide_btn2_kind_" . $i,
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last slide_btn2_" . $i . '_dependency',
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_btn2_hover_color_" . $i,
            "admin_label" => false,
            "value" => "#ff0054",
            'dependency' => array(
                'element' => $slider_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

pixflow_map(
    array(
        "name"                    => "Pixflow Slider",
        "base"                    => "md_slider",
        "category"                => esc_attr__('Media','massive-dynamic'),
        "show_settings_on_create" => false,
        "params"                  => pixflow_slider_param()
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Text Box
/*-----------------------------------------------------------------------------------*/
pixflow_map(
    array(
        'base' => 'md_text_box',
        'name' => esc_html__('Text Box', 'massive-dynamic'),
        "category" => esc_html__('Business', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_html__("Title", 'massive-dynamic'),
                "param_name" => "textbox_title",
                "value" => 'Tags & Models',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "textbox_title_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_html__("Description", 'massive-dynamic'),
                "param_name" => "textbox_text",
                "admin_label" => false,
                "value" => "It is a long established fact that a reader will be dis",
            ),

            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_html__("Choose an icon", 'massive-dynamic'),
                "param_name" => "textbox_icon",
                "value" => "icon-PriceTag",
                "admin_label" => false
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_html__("Text Color", 'massive-dynamic'),
                "param_name" => "textbox_text_color",
                "value" => 'rgb(80,80,80)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "textbox_text_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_html__("Text Hover Color", 'massive-dynamic'),
                "param_name" => "textbox_text_hover_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "textbox_text_hover_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_html__("Background Color", 'massive-dynamic'),
                "param_name" => "textbox_background_color",
                "value" => 'rgb(230,231,237)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "textbox_background_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_html__("Background Hover Color", 'massive-dynamic'),
                "param_name" => "textbox_background_hover_color",
                "value" => 'rgb(255,0,84)',
                "admin_label" => false,
                "opacity" => true,
            ),
        )
    )
);


/*************************************
 * Add Animation tab to shortcodes
 *************************************/
function pixflow_addAnimationTab($shortcode)
{
    if ($shortcode == '') {
        return array();
    }
    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $animationTab = array(
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue first last",
            'heading' => esc_attr__('Use Animation', 'massive-dynamic'),
            'param_name' => $shortcode . '_animation',
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            'checked' => false,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Speed", 'massive-dynamic'),
            "param_name" => $shortcode . "_animation_speed",
            "admin_label" => false,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "value" => array(
                esc_attr__("Super Fast", 'massive-dynamic') => 200,
                esc_attr__("Fast", 'massive-dynamic') => 400,
                esc_attr__("Medium", 'massive-dynamic') => 600,
                esc_attr__("Slow", 'massive-dynamic') => 800,
            ),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => $shortcode . "_animation_speed_separator" . ++$separatorCounter,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue",
            'heading' => esc_attr__('Animation delay', 'massive-dynamic'),
            'param_name' => $shortcode . '_animation_delay',
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            'defaultSetting' => array(
                "min" => "0",
                "max" => "2",
                "prefix" => " s",
                "step" => "0.1",
                "decimal" => "1",
            ),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => $shortcode . "_animation_delay_separator" . ++$separatorCounter,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Animate From", 'massive-dynamic'),
            "param_name" => $shortcode . "_animation_position",
            "admin_label" => false,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "value" => array(
                esc_attr__("Center", 'massive-dynamic') => "center",
                esc_attr__("Top", 'massive-dynamic') => "top",
                esc_attr__("Right", 'massive-dynamic') => "right",
                esc_attr__("Bottom", 'massive-dynamic') => "bottom",
                esc_attr__("Left", 'massive-dynamic') => "left"
            ),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => $shortcode . "_animation_position_separator" . ++$separatorCounter,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue ",
            "heading" => esc_attr__("Animation Type", 'massive-dynamic'),
            "param_name" => $shortcode . "_animation_show",
            "admin_label" => false,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "value" => array(
                esc_attr__("Play Once", 'massive-dynamic') => "once",
                esc_attr__("Play On scroll", 'massive-dynamic') => "scroll"
            ),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => $shortcode . "_animation_position_separator" . ++$separatorCounter,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Animation Easing", 'massive-dynamic'),
            "param_name" => $shortcode . "_animation_easing",
            "admin_label" => false,
            "group" => esc_attr__('Animation', 'massive-dynamic'),
            "value" => array(
                esc_attr__("Quart", 'massive-dynamic') => "Quart.easeInOut",
                esc_attr__("Quint", 'massive-dynamic') => "Power4.easeOut",
            ),
            "dependency" => array(
                'element' => $shortcode . "_animation",
                'value' => array('yes'),
            ),
        )
    );
    return $animationTab;
}

/*-----------------------------------------------------------------------------------*/
/*  Slider Carousel
/*-----------------------------------------------------------------------------------*/

pixflow_map(

    array(
        "name" => "Slider Carousel",
        "base" => "md_slider_carousel",
        "category" => esc_attr__('Media','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,

        "params" => array(

            /* Background image tab */

            array(
                'type' => 'attach_images',
                'edit_field_class' => $filedClass . "first glue last",
                'heading' => esc_attr__('Choose Image(s)', 'massive-dynamic'),
                'param_name' => 'slider_images',
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue ",
                'heading' => esc_attr__('Slider Height', 'massive-dynamic'),
                'param_name' => 'slider_heights',
                'value' => '600',
                'defaultSetting' => array(
                    "min" => "100",
                    "max" => "1200",
                    "prefix" => " px",
                    "step" => "5",
                )
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "slider_separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . " glue last",
                'heading' => esc_attr__('Margin', 'massive-dynamic'),
                'param_name' => 'slider_margin',
                'value' => '20',
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "100",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Nav Active Color", 'massive-dynamic'),
                "param_name" => "slider_nav_active_color",
                "value" => 'rgba(68,123,225,1)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Shadow', 'massive-dynamic'),
                'param_name' => 'slider_shadow',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),

            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Auto Play', 'massive-dynamic'),
                'param_name' => 'slider_auto_play',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "separator" . ++$separatorCounter,
                "dependency" => array(
                    'element' => "slider_auto_play",
                    'value' => array('yes'),
                ),
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "last glue",
                'heading' => esc_attr__('Auto Play Duration', 'massive-dynamic'),
                'param_name' => 'slider_slider_speed',
                'value' => '5',
                'defaultSetting' => array(
                    "min" => "1",
                    "max" => "30",
                    "prefix" => " / s",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "slider_auto_play",
                    'value' => array('yes'),
                ),
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "modern_description",
                "admin_label" => false,
                "value" => '<ul><li>' . esc_attr__('For correct output, please use at least 3 images.', 'massive-dynamic') . '</li><li>' . esc_attr__('For adding multiple images to carousel, hold Ctrl(Cmd on Mac) and select images in image uploader.', 'massive-dynamic') . '</li></ul>',
            ),
        ),
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Subscribe Modern
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_modern_subscribe',
        'name' => esc_attr__('Modern Subscribe', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "subscribe_title",
                "value" => 'Sign Up To Our Newsletter',
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . " glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "subscribe_desc",
                "value" => 'To get the latest news from us please subscribe your email.we promise worthy news with no spam.',
                "admin_label" => false,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . " glue first ",
                'heading' => esc_attr__('Shadow', 'massive-dynamic'),
                'param_name' => 'subscribe_shadow',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes')
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . " glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "subscribe_textcolor",
                "value" => '#000',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "subscribe_sep" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "subscribe_bgcolor",
                "value" => '#fff',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                'type' => 'attach_image',
                "edit_field_class" => $filedClass . "glue first last",
                'heading' => esc_attr__('Image', 'massive-dynamic'),
                'param_name' => 'subscribe_image',
                'value' => PIXFLOW_THEME_IMAGES_URI . "/place-holder.jpg",
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "modern_description",
                "admin_label" => false,
                "value" => '<ul><li>' . esc_attr__('You must install and configure "MailChimp for WordPress Lite" plugin before using this shortcode.', 'massive-dynamic') . '</li></ul>',
            ),
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Double Slider
/*-----------------------------------------------------------------------------------*/

function pixflow_double_slider_param()
{
    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $slide_num_param = 'slide_num';
    $slide_num = 5;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        /*"Six"   => "6",
        "Seven"   => "7",
        "Eight"   => "8",
        "Nine"   => "9",
        "Ten"   => "10"*/
    );
    $param = array(
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "first glue last slide_number",
            //"class" => "slide_number",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Slide Number", 'massive-dynamic'),
            "param_name" => $slide_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "first glue last",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Orientation", 'massive-dynamic'),
            "param_name" => 'double_slider_appearance',
            "admin_label" => false,
            "value" => array(
                "First Image" => 'double-slider-left',
                "First Text" => 'double-slider-right'
            )
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue first last",
            'heading' => esc_attr__('Auto Play ', 'massive-dynamic'),
            'param_name' => 'double_slider_auto_play',
            "group" => esc_attr__("General", 'massive-dynamic'),
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
        ),

        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . " first last glue",
            'heading' => esc_attr__('Auto Play Duration', 'massive-dynamic'),
            'param_name' => 'double_slider_duration',
            "group" => esc_attr__("General", 'massive-dynamic'),
            'value' => '5',
            'defaultSetting' => array(
                "min" => "1",
                "max" => "30",
                "prefix" => " s",
                "step" => "1",
            ),
            "dependency" => array(
                'element' => "double_slider_auto_play",
                'value' => array('yes'),
            ),
        ),

        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . " first last glue",
            'heading' => esc_attr__('Height', 'massive-dynamic'),
            'param_name' => 'double_slider_height',
            "group" => esc_attr__("General", 'massive-dynamic'),
            'value' => '500',
            'defaultSetting' => array(
                "min" => "250",
                "max" => "800",
                "prefix" => "px",
                "step" => "10",
            ),
            "dependency" => array(
                'element' => "double_slider_auto_play",
                'value' => array('yes'),
            ),
        ),
    );
    for ($i = 1; $i <= (int)$slide_num; $i++) {
        $value = array();
        for ($k = $i; $k <= $slide_num; $k++) {
            $value[] = (string)$k;
        }
        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Background color", 'massive-dynamic'),
            "param_name" => "slide_bg_" . $i,
            "value" => "#447be0",
            "description" => esc_attr__("Slide Background", 'massive-dynamic'),
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_description_" . $i . "_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "last glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Text color", 'massive-dynamic'),
            "param_name" => "slide_fg_" . $i,
            "value" => "#ffffff",
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "slide_title_" . $i,
            "admin_label" => false,
            "value" => 'Title' . $i,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_title_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . " glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
            "param_name" => "slide_sub_title_" . $i,
            "value" => 'Subtitle' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_title_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "slide_description_" . $i,
            "value" => 'Slide Description' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "param_name" => "slide_description_" . $i . "_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );
        $param[] = array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Slide ", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Slide Image", 'massive-dynamic'),
            "param_name" => "slide_image_" . $i,
            "admin_label" => false,
            "description" => esc_attr__("Choose Slide image", 'massive-dynamic'),
            'dependency' => array(
                'element' => $slide_num_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

pixflow_map(array(
    "name" => esc_attr__("Double Slider", 'massive-dynamic'),
    "base" => "md_double_slider",
    "category" => esc_attr__('Media', 'massive-dynamic'),
    "show_settings_on_create" => false,
    "params" => pixflow_double_slider_param()
));

/*-----------------------------------------------------------------------------------*/
/*  Pixflow Price Table
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_price_table',
        'name' => esc_attr__('Pixflow Price Table', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Commerce', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue last textNsize-text",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "title",
                "value" => 'Personal Plan',
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue textNsize-size",
                "heading" => esc_attr__("Title Color", 'massive-dynamic'),
                "param_name" => "title_color",
                "admin_label" => false,
                "value" => "#623e95",
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Price", 'massive-dynamic'),
                "param_name" => "price",
                "description" => esc_attr__("Type your price", 'massive-dynamic'),
                "admin_label" => false,
                "value" => '50',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Currency", 'massive-dynamic'),
                "param_name" => "currency",
                "admin_label" => false,
                "value" => array(
                    esc_attr__('Dollar', 'massive-dynamic') => '$',
                    esc_attr__('Euro', 'massive-dynamic') => '&euro;',
                    esc_attr__('Pound', 'massive-dynamic') => '&pound;'
                )
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "description",
                "admin_label" => false,
                "value" =>
                    "Mobile-Optimized
Powerful Metrics
Free Domain
Annual Purchase
24/7 Support",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "general_color",
                "admin_label" => false,
                "value" => "#898989",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "bg_color",
                "admin_label" => false,
                "value" => "#fff",
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Button', 'massive-dynamic'),
                'param_name' => 'use_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
                "group" => esc_attr__("Button", 'massive-dynamic'),
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "button_style",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle"
                ),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "button_text",
                "admin_label" => false,
                "value" => 'PURCHASE',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                ),
                'value' => 'icon-empty'
            ),//btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "#b3b3b3",
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "#fff",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_bg_hover_color",
                "admin_label" => false,
                "value" => "#623e95",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),

            ),//btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle')
                )
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_hover_color",
                "admin_label" => false,
                "value" => "#fff",
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),

            ),//btn text hover color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_size",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'button_padding',
                'value' => '30',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//spacing
            array(
                "type" => "textfield",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "button_url",
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_target",
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn target
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Quote
/*-----------------------------------------------------------------------------------*/


function pixflow_quote()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;

    $param = array(

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "quote_title",
            "description" => esc_attr__("Quote heading text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => "Scarlet Snow"
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_title_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Job Title", 'massive-dynamic'),
            "param_name" => "quote_job_title",
            "description" => esc_attr__("Quote job title text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => "Sat Manager"
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_job_title_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "quote_description",
            "description" => esc_attr__("Quote description text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => esc_attr__("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, sunt explicabo.", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_description_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Image", 'massive-dynamic'),
            "param_name" => "quote_background_image",
            "admin_label" => false,
            "description" => esc_attr__("Choose background image", 'massive-dynamic')
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_image_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "quote_text_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose color", 'massive-dynamic'),
            "value" => 'rgb(24,24,24)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_text_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Background Color", 'massive-dynamic'),
            "param_name" => "quote_background_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose background color", 'massive-dynamic'),
            "value" => 'rgb(243,243,243)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "quote_background_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
            "param_name" => "quote_icon_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose icon color", 'massive-dynamic'),
            "value" => 'rgb(150,223,92)',
        ),

    );
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Quote", 'massive-dynamic'),
    "base" => "md_quote",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business','massive-dynamic'),
    "params" => pixflow_quote()
));

/*-----------------------------------------------------------------------------------*/
/*  Feature Image
/*-----------------------------------------------------------------------------------*/


function pixflow_feature_image()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;

    $param = array(

        array(
            "type" => "attach_image",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Image", 'massive-dynamic'),
            "param_name" => "feature_image_background_image",
            "admin_label" => false,
            "description" => esc_attr__("Choose background image", 'massive-dynamic')
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_image_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "feature_image_icon_class",
            "admin_label" => false,
            "description" => esc_attr__("Select an icon", 'massive-dynamic'),
            'value' => 'icon-romance-love-target'
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_icon_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "feature_image_title",
            "description" => esc_attr__("feature image heading text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => "Imagine & Create"
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_title_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "feature_image_description",
            "description" => esc_attr__("feature image description text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => esc_attr__("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_description_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Background Color", 'massive-dynamic'),
            "param_name" => "feature_image_background_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose background color", 'massive-dynamic'),
            "value" => 'rgb(255,255,255)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_background_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Foreground Color", 'massive-dynamic'),
            "param_name" => "feature_image_foreground_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose foreground color", 'massive-dynamic'),
            "value" => 'rgb(24,24,24)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_foreground_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
            "param_name" => "feature_image_hover_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose hover color", 'massive-dynamic'),
            "value" => 'rgb(26,192,182)',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "feature_image_hover_color_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Image Height', 'massive-dynamic'),
            'param_name' => 'feature_image_height_slider',
            'value' => '300',
            'defaultSetting' => array(
                "min" => "100",
                "max" => "1000",
                "prefix" => " px",
                "step" => "10",
            ),
        ),



    );
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Feature Image", 'massive-dynamic'),
    "base" => "md_feature_image",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business','massive-dynamic'),
    "params" => pixflow_feature_image()
));

/*-----------------------------------------------------------------------------------*/
/*  Pixflow Price Box
/*-----------------------------------------------------------------------------------*/

function pixflow_price_box()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $item_num_param = 'price_box_item_num';
    $item_num = 10;
    $dropDown = array(
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("One", 'massive-dynamic') => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Three", 'massive-dynamic') => 3,
        esc_attr__("Four", 'massive-dynamic') => 4,
        esc_attr__("Five", 'massive-dynamic') => 5,
        esc_attr__("Six", 'massive-dynamic') => 6,
        esc_attr__("Seven", 'massive-dynamic') => 7,
        esc_attr__("Eight", 'massive-dynamic') => 8,
        esc_attr__("Nine", 'massive-dynamic') => 9,
        esc_attr__("Ten", 'massive-dynamic') => 10,

    );

    $param = array(

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue last textNsize-text",
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "price_box_title",
            "value" => 'Personal',
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue textNsize-size",
            "heading" => esc_attr__("Title Color", 'massive-dynamic'),
            "param_name" => "price_box_title_color",
            "admin_label" => false,
            "value" => "#623e95",
        ),
        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("Price", 'massive-dynamic'),
            "param_name" => "price_box_price",
            "description" => esc_attr__("Type your price", 'massive-dynamic'),
            "admin_label" => false,
            "value" => '69.00',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Currency", 'massive-dynamic'),
            "param_name" => "price_box_currency",
            "admin_label" => false,
            "value" => array(
                esc_attr__('Dollar', 'massive-dynamic') => '$',
                esc_attr__('Euro', 'massive-dynamic') => '&euro;',
                esc_attr__('Pound', 'massive-dynamic') => '&pound;'
            )
        ),

        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue last textNsize-text",
            "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
            "param_name" => "price_box_subtitle",
            "value" => 'Monthly',
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "param_name" => "price_box_general_color",
            "admin_label" => false,
            "value" => "#96df5c",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Border Color", 'massive-dynamic'),
            "param_name" => "price_box_border_color",
            "admin_label" => false,
            "value" => "#cccccc",
        ),
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "first glue last",
            'heading' => esc_attr__('Use Button', 'massive-dynamic'),
            'param_name' => 'price_box_use_button',
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => true,
            "group" => esc_attr__("Button", 'massive-dynamic'),
        ),//add btn
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "separate" => true,
            "heading" => esc_attr__("Button Style", 'massive-dynamic'),
            "param_name" => "price_box_button_style",
            "admin_label" => false,
            "value" => array(
                esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval",
                esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                esc_attr__("Slide", 'massive-dynamic') => "slide",
                esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                esc_attr__("Animation", 'massive-dynamic') => "animation",
                esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate"

            ),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn kind
        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "heading" => esc_attr__("Text", 'massive-dynamic'),
            "param_name" => "price_box_button_text",
            "admin_label" => false,
            "value" => 'Purchase',
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn text
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "price_box_button_icon_class",
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            ),
            'value' => 'icon-empty'
        ),//btn icon
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first last",
            "heading" => esc_attr__("General Color", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_color",
            "admin_label" => false,
            "opacity" => true,
            "value" => "#f0f0f0",
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn general color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "edit_field_class" => $filedClass . "stick-to-top",
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
            ),
        ),//separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue ",
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_text_color",
            "admin_label" => false,
            "opacity" => true,
            "value" => "#7e7e7e",
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        ),//btn text color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        ),//separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_bg_hover_color",
            "admin_label" => false,
            "value" => "#96df5c",
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),

        ),//btn bg hover color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle')
            )
        ),//separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_hover_color",
            "admin_label" => false,
            "value" => "#623e95",
            "dependency" => array(
                'element' => "price_box_button_style",
                'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
            ),

        ),//btn text hover color
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Button size", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_size",
            "admin_label" => false,
            "value" => array(
                esc_attr__("Standard", 'massive-dynamic') => "standard",
                esc_attr__("Small", 'massive-dynamic') => "small"
            ),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn size
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//separator
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
            'param_name' => 'price_box_button_padding',
            'value' => '30',
            "group" => esc_attr__("Button", 'massive-dynamic'),
            'defaultSetting' => array(
                "min" => "0",
                "max" => "300",
                "prefix" => " px",
                "step" => "1",
            ),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//spacing
        array(
            "type" => "textfield",
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Link URL", 'massive-dynamic'),
            "param_name" => "price_box_button_url",
            "admin_label" => false,
            "value" => "#",
            "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn url
        array(
            "type" => 'md_vc_separator',
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Link's target", 'massive-dynamic'),
            "group" => esc_attr__("Button", 'massive-dynamic'),
            "param_name" => "price_box_button_target",
            "admin_label" => false,
            "value" => array(
                esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
            ),
            "dependency" => array(
                'element' => "price_box_use_button",
                'value' => array('yes')
            )
        ),//btn target

        array(
            "type" => "md_vc_checkbox",
            "edit_field_class" => $filedClass . "glue first last",
            "heading" => esc_attr__("Enable Offer", 'massive-dynamic'),
            "param_name" => "price_box_offer_chk",
            "admin_label" => false,
            "value" => array(esc_attr__('No', 'massive-dynamic') => 'no'),
            'checked' => false
        ),//offer checkbox
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "admin_label" => false,
            "dependency" => array(
                'element' => "price_box_offer_chk",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "last glue",
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "price_box_offer_title",
            "value" => "BEST OFFER",
            "description" => esc_attr__("Offer text box", 'massive-dynamic'),
            "admin_label" => false,
            'dependency' => array(
                'element' => "price_box_offer_chk",
                'value' => array('yes'),
            )
        ),//offer text

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "heading" => esc_attr__("Item Number:", 'massive-dynamic'),
            "param_name" => $item_num_param,
            "admin_label" => false,
            "value" => $dropDown
        ),//list items
        array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "param_name" => "price_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),//separator

        array(
            "type" => "md_vc_colorpicker",
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Items Color", 'massive-dynamic'),
            "param_name" => "price_box_items_color",
            "admin_label" => false,
            "value" => "#898989",
        ),

    );

    for ($i = 1; $i <= (int)$item_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $item_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first last glue",
            "group" => esc_attr__("Items", 'massive-dynamic'),
            "heading" => esc_attr__("Item ", 'massive-dynamic') . $i,
            "param_name" => "price_box_list_item_" . $i,
            "value" => 'This is text for item' . $i,
            "admin_label" => false,
            'dependency' => array(
                'element' => $item_num_param,
                'value' => $value
            ),
        );
    }
    return $param;
}

pixflow_map(

    array(
        'base' => 'md_price_box',
        'name' => esc_attr__('Pixflow Price Box', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Commerce', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => pixflow_price_box()
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Article Box
/*-----------------------------------------------------------------------------------*/
pixflow_map(
    array(
        'base' => 'md_article_box',
        'name' => esc_html__('Article Box', 'massive-dynamic'),
        "category" => esc_html__('Business', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "allowed_container_element" => 'vc_row',
        "params" => array(

            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue  last",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "article_image",
                "admin_label" => false,
                "description" => esc_attr__("Choose an image", 'massive-dynamic'),
                'value' => PIXFLOW_THEME_IMAGES_URI . "/place-holder.jpg",
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_html__("Title", 'massive-dynamic'),
                "param_name" => "article_title",
                "value" => 'Unique Element',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),

            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_html__("Choose an icon", 'massive-dynamic'),
                "param_name" => "article_icon",
                "value" => "icon-file-tasks-add",
                "admin_label" => false
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_html__("Description", 'massive-dynamic'),
                "param_name" => "article_text",
                "admin_label" => false,
                "value" => "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed",
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Height', 'massive-dynamic'),
                'param_name' => 'article_height',
                'value' => '345',
                'defaultSetting' => array(
                    "min" => "250",
                    "max" => "600",
                    "prefix" => " px",
                    "step" => "1",
                ),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_html__("Overlay Color", 'massive-dynamic'),
                "param_name" => "article_overlay_color",
                "value" => 'rgb(48,71,103)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_html__("Text Color", 'massive-dynamic'),
                "param_name" => "article_text_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => true,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_html__("Icon Color", 'massive-dynamic'),
                "param_name" => "article_icon_color",
                "value" => 'rgb(150,223,92)',
                "admin_label" => false,
                "opacity" => true,
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_html__("Read more Text", 'massive-dynamic'),
                "param_name" => "article_read_more_text",
                "value" => 'VIEW MORE',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_html__("Read more Link", 'massive-dynamic'),
                "param_name" => "article_read_more_link",
                "value" => '#',
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "article_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
            ),

        )
    )
);

/*******************************************************************
 *                  Counter Card
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_countercard',
        'name' => esc_attr__('Counter Card', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue last",
                "param_name" => "counter_to",
                "heading" => esc_attr__("Count To", 'massive-dynamic'),
                "value" => '560',
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "counter_title",
                "admin_label" => false,
                "value" => "Complete Projects",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "coutner_icon_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-share3'
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "counter_bg_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "counter_text_color",
                "value" => 'rgb(26,51,86)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "counter_icon_color",
                "value" => 'rgb(150,223,92)',
                "admin_label" => false,
                "opacity" => false,
            ),

        )
    )
);

/*******************************************************************
 *                  Count Down
 ******************************************************************/

pixflow_map(
    array(
        'base' => 'md_countdown',
        'name' => esc_attr__('Count Down', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => array(
            array(
                "type" => "md_vc_datepicker",
                "edit_field_class" => $filedClass . "first glue last",
                "param_name" => "count_down",
                "heading" => esc_attr__("Count To", 'massive-dynamic'),
                "value" => '2020/10/9 20:30',
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "count_down_general_color",
                "value" => '#727272',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "last glue",
                "heading" => esc_attr__("Seperator Color", 'massive-dynamic'),
                "param_name" => "count_down_sep_color",
                "value" => '#96df5c',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => "md_vc_description",
                "param_name" => "md_count_down_description",
                "admin_label" => false,
                "value" => esc_attr__("The Count Down Shortcode Font Come From H3 Font Family.", 'massive-dynamic'),
            )
        )
    )
);

/*******************************************************************
 *                  Statistic
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_statistic',
        'name' => esc_attr__('Statistic', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue last",
                "param_name" => "statistic_to",
                "heading" => esc_attr__("Count To", 'massive-dynamic'),
                "value" => '80',
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Symbol ", 'massive-dynamic'),
                "param_name" => "statistic_symbol",
                "admin_label" => false,
                "value" => "%",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "statistic_title",
                "admin_label" => false,
                "value" => "Complete Projects",
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("General  Color", 'massive-dynamic'),
                "param_name" => "statistic_general_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Symbol Color", 'massive-dynamic'),
                "param_name" => "statistic_symbol_color",
                "value" => 'rgb(150,223,92)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Separator', 'massive-dynamic'),
                'param_name' => 'statistic_separatoe',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => true,
            ),

        )
    )
);

/*******************************************************************
 *                  Split Box
 ******************************************************************/
pixflow_map(
    array(
        'base' => 'md_splitBox',
        'name' => esc_attr__('Split Box', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "params" => array(
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "sb_title_size",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H3" => "h3",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H4" => "h4",
                    "H5" => "h5",
                    "H6" => "h6"
                ),
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "param_name" => "sb_title",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "value" => 'Super Flexible',
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "sb_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
                "param_name" => "sb_subtitle",
                "admin_label" => false,
                "value" => "OBJECT",
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "sb_subtitle_separator" . ++$separatorCounter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_html__("Description", 'massive-dynamic'),
                "param_name" => "sb_desc",
                "admin_label" => false,
                "value" => "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores.",
            ),

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Background Color", 'massive-dynamic'),
                "param_name" => "sb_bg_color",
                "value" => 'rgb(233,233,233)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "sb_bg_color_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "param_name" => "sb_text_color",
                "value" => 'rgb(0,0,0)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => "attach_image",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Image", 'massive-dynamic'),
                "param_name" => "sb_image",
                "admin_label" => false,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Alignment", 'massive-dynamic'),
                "param_name" => "sb_alignment",
                "admin_label" => false,
                "value" => array(
                    "Left" => "sb-left",
                    "Right" => "sb-right"
                ),
            ),
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Split Box Height', 'massive-dynamic'),
                'param_name' => "sb_height",
                'value' => '470',
                'defaultSetting' => array(
                    "min" => "300",
                    "max" => "900",
                    "prefix" => " px",
                    "step" => "1",
                )
            ),
            //btn tab
            array(
                'type' => 'md_vc_checkbox',
                "edit_field_class" => $filedClass . "first glue last",
                'heading' => esc_attr__('Use Button', 'massive-dynamic'),
                'param_name' => 'use_button',
                'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
                'checked' => false,
                "group" => esc_attr__("Button", 'massive-dynamic'),
            ),//add btn
            array(
                "type" => 'md_vc_separator',
                "edit_field_class" => $filedClass . "stick-to-top",
                "param_name" => "use_button" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "separate" => true,
                "heading" => esc_attr__("Button Style", 'massive-dynamic'),
                "param_name" => "button_style",
                "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                    esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                    esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                    esc_attr__("Slide", 'massive-dynamic') => "slide",
                    esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                    esc_attr__("Animation", 'massive-dynamic') => "animation",
                    esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                    esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
                ),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn kind
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "button_text",
                "description" => esc_attr__("Button text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'VIEW MORE',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_text_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                ),
                'value' => 'icon-angle-right'
            ),//btn icon
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue first last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(255,255,255,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn general color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "md_text_button_color_separator" . ++$separatorCounter,
                "edit_field_class" => $filedClass . "stick-to-top",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue ",
                "heading" => esc_attr__("Text Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_text_color",
                "admin_label" => false,
                "opacity" => true,
                "value" => "rgba(126,126,126,1)",
                "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//btn text color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_bg_hover_color",
                "admin_label" => false,
                "value" => "rgb(0,0,0)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle'),
                ),

            ),//btn bg hover color
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_color_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('fill-oval', 'fill-rectangle')
                )
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_hover_color",
                "admin_label" => false,
                "value" => "rgb(255,255,255)",
                "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "button_style",
                    'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
                ),

            ),//btn text hover color
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Button size", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_size",
                "admin_label" => false,
                "description" => esc_attr__("Choose between three button sizes", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Standard", 'massive-dynamic') => "standard",
                    esc_attr__("Small", 'massive-dynamic') => "small"
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn size
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_size_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                'type' => 'md_vc_slider',
                "edit_field_class" => $filedClass . "glue last",
                'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
                'param_name' => 'left_right_padding',
                'value' => '0',
                "group" => esc_attr__("Button", 'massive-dynamic'),
                'defaultSetting' => array(
                    "min" => "0",
                    "max" => "300",
                    "prefix" => " px",
                    "step" => "1",
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//spacing
            array(
                "type" => "textfield",
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Link URL", 'massive-dynamic'),
                "param_name" => "button_url",
                "admin_label" => false,
                "value" => "#",
                "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "button_linkr_separator" . ++$separatorCounter,
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "group" => esc_attr__("Button", 'massive-dynamic'),
                "param_name" => "button_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
                "dependency" => array(
                    'element' => "use_button",
                    'value' => array('yes')
                )
            ),//btn target


        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Subscribe Business
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        'base' => 'md_subscribe_business',
        'name' => esc_attr__('Business Subscribe', 'massive-dynamic'),
        "show_settings_on_create" => false,
        "category" => esc_attr__('Business', 'massive-dynamic'),
        "params" => array(


            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "general_color",
                "value" => 'rgb(35,58,91)',
                "admin_label" => false,
                "opacity" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),//separator
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "button_icon_class",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
                'value' => 'icon-Mail'
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "separator" . ++$separatorCounter,
            ),//separator
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Button Text Color", 'massive-dynamic'),
                "param_name" => "button_text_color",
                "value" => 'rgb(255,255,255)',
                "admin_label" => false,
                "opacity" => false,
            )
        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Icon Box New
/*-----------------------------------------------------------------------------------*/

pixflow_map(
    array(
        "name" => "Icon Box New",
        "base" => "md_iconbox_new",
        "category" => esc_attr__("more", 'massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        'show_settings_on_create' => false,

        "params" => array(

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Title", 'massive-dynamic'),
                "param_name" => "iconbox_new_title",
                "value" => "Super Flexible",
                "description" => esc_attr__("Iconbox new heading text", 'massive-dynamic'),
                "admin_label" => false,
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_new_title_separator" . ++$separatorCounter,
            ),
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Title size", 'massive-dynamic'),
                "param_name" => "iconbox_new_heading",
                "description" => esc_attr__("Choose your heading", 'massive-dynamic'),
                "admin_label" => false,
                "value" => array(
                    "H6" => "h6",
                    "H1" => "h1",
                    "H2" => "h2",
                    "H3" => "h3",
                    "H4" => "h4",
                    "H5" => "h5"
                ),
            ),
            array(
                "type" => "textarea",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("Description", 'massive-dynamic'),
                "param_name" => "iconbox_new_description",
                "value" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
                "description" => esc_attr__("Iconbox new description text", 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue first",
                "heading" => esc_attr__("Hover Effect", 'massive-dynamic'),
                "param_name" => "iconbox_new_hover",
                "admin_label" => false,
                "description" => esc_attr__("Icon box new hover effect", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Circle", 'massive-dynamic') => "circle-hover",
                    esc_attr__("Box", 'massive-dynamic') => "box-hover"
                ),
            ),//hover effect
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_new_hover_effect_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),//separator

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Hover Color", 'massive-dynamic'),
                "param_name" => "iconbox_new_hover_color",
                "value" => "#efefef",
                "admin_label" => false,
                "description" => esc_attr__("Choose hover color", 'massive-dynamic'),
            ),//hover color

            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue last",
                "heading" => esc_attr__("General Color", 'massive-dynamic'),
                "param_name" => "iconbox_new_general_color",
                "value" => "#5e5e5e",
                "admin_label" => false,
                "description" => esc_attr__("Choose general color", 'massive-dynamic'),
            ),
            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
                "param_name" => "iconbox_new_icon_color",
                "value" => "rgb(0,0,0)",
                "admin_label" => false,
                "description" => esc_attr__("Choose icon color", 'massive-dynamic'),
            ),
            array(
                "type" => 'md_vc_separator',
                "param_name" => "icon_color_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),
            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
                "param_name" => "iconbox_new_icon",
                "value" => "icon-microphone-outline",
                "admin_label" => false,
                "description" => esc_attr__("Select an icon", 'massive-dynamic')
            ),//iconbox icon

            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "first glue",
                "heading" => esc_attr__("Text", 'massive-dynamic'),
                "param_name" => "iconbox_new_readmore",
                "description" => esc_attr__("Read more text", 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'Find Out More',
            ),//Read More Text
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_new_readmore_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),//separator
            array(
                "type" => "textfield",
                "edit_field_class" => $filedClass . "glue",
                "heading" => esc_attr__("URL Link", 'massive-dynamic'),
                "param_name" => "iconbox_new_url",
                "value" => "#",
                "admin_label" => false,
                "description" => esc_attr__("destination URL", 'massive-dynamic'),
            ),//iconbox new url
            array(
                "type" => 'md_vc_separator',
                "param_name" => "iconbox_new_url_separator" . ++$separatorCounter,
                "admin_label" => false,
            ),//separator
            array(
                "type" => "dropdown",
                "edit_field_class" => $filedClass . "glue last",
                "heading" => esc_attr__("Link's target", 'massive-dynamic'),
                "param_name" => "iconbox_new_target",
                "admin_label" => false,
                "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
                "value" => array(
                    esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                    esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
                ),
            ),//iconbox new target

        )
    )
);

/*-----------------------------------------------------------------------------------*/
/*  Process Panel
/*-----------------------------------------------------------------------------------*/

function pixflow_process_panel()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;
    $bar_num_param = 'process_panel_num';
    $bar_num = 4;
    $dropDown = array(
        esc_attr__("Three", 'massive-dynamic') => 3,
//        esc_attr__("One",'massive-dynamic')   => 1,
        esc_attr__("Two", 'massive-dynamic') => 2,
        esc_attr__("Four", 'massive-dynamic') => 4,
    );

    $param = array(

        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",
            "group" => esc_attr__("General", 'massive-dynamic'),
            "heading" => esc_attr__("Number of Process Panel:", 'massive-dynamic'),
            "param_name" => $bar_num_param,
            "admin_label" => false,
            "value" => $dropDown,
        ),
    );

    $param[] = array(
        "type" => 'md_vc_separator',
        "group" => esc_attr__("General", 'massive-dynamic'),
        "param_name" => "process_panel_bar_separator" . ++$separatorCounter,
    );

    $param[] = array(
        "type" => "md_vc_colorpicker",
        "edit_field_class" => $filedClass . "glue last",
        "group" => esc_attr__("General", 'massive-dynamic'),
        "heading" => esc_attr__("Base Color", 'massive-dynamic'),
        "param_name" => "process_panel_base_color",
        "admin_label" => false,
        "value" => '#fff',
    );

    $stepColor = array('#65d97d', '#42a881', '#1f8784', '#156664');
    for ($i = 1; $i <= (int)$bar_num; $i++) {
        $value = array();

        for ($k = $i; $k <= $bar_num; $k++) {
            $value[] = (string)$k;
        }

        $param[] = array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "first glue",
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "process_panel_title_" . $i,
            "description" => esc_attr__("Process Panel Title", 'massive-dynamic'),
            "value" => 'Online Presence Analysis',
            "admin_label" => false,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "param_name" => "process_panel_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "textfield",
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "edit_field_class" => $filedClass . " glue",
            "heading" => esc_attr__("Subtitle", 'massive-dynamic'),
            "param_name" => "process_panel_subtitle_" . $i,
            "value" => 'Complete Projects',
            "admin_label" => false,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "param_name" => "process_panel_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_iconpicker",
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "edit_field_class" => $filedClass . " glue",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "process_panel_icon_" . $i,
            "admin_label" => false,
            "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
            'value' => 'icon-Health',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "param_name" => "process_panel_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Icon Color", 'massive-dynamic'),
            "param_name" => "process_panel_icon_color_" . $i,
            "admin_label" => false,
            "value" => '#fff',
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => 'md_vc_separator',
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "param_name" => "process_panel_bar_" . $i . "_separator" . ++$separatorCounter,
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

        $param[] = array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "group" => esc_attr__("Step", 'massive-dynamic') . $i,
            "heading" => esc_attr__("Background Color", 'massive-dynamic'),
            "param_name" => "process_panel_bg_color_" . $i,
            "admin_label" => false,
            "value" => $stepColor[$i - 1],
            'dependency' => array(
                'element' => $bar_num_param,
                'value' => $value
            ),
        );

    }
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Process Panel", 'massive-dynamic'),
    "base" => "md_process_panel",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business','massive-dynamic'),
    "params" => pixflow_process_panel()
));


/*-----------------------------------------------------------------------------------*/
/*  Info Box
/*-----------------------------------------------------------------------------------*/


function pixflow_info_box()
{

    $filedClass = 'vc_col-sm-12 vc_column ';
    $separatorCounter = 0;

    $param = array(
        array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Title", 'massive-dynamic'),
            "param_name" => "info_box_title",
            "admin_label" => false,
            "value" => "Planning for the
future.",
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . "glue",
            'heading' => esc_attr__('Separator', 'massive-dynamic'),
            'param_name' => 'info_box_checkbox',
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            'checked' => false,
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "textarea",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Description", 'massive-dynamic'),
            "param_name" => "info_box_description",
            "description" => esc_attr__("info box description text", 'massive-dynamic'),
            "admin_label" => false,
            "value" => esc_attr__("Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.", 'massive-dynamic'),
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "info_box_icon_class",
            "admin_label" => false,
            "description" => esc_attr__("Select an icon", 'massive-dynamic'),
            'value' => 'icon-romance-love-target'
        ),

        // colors

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Title Color", 'massive-dynamic'),
            "param_name" => "info_box_title_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose title color", 'massive-dynamic'),
            "value" => '#0338a2',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Description Color", 'massive-dynamic'),
            "param_name" => "info_box_description_color",
            "admin_label" => false,
            "description" => esc_attr__("Choose description color", 'massive-dynamic'),
            "value" => '#7e7e7e',
        ),
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_separator" . ++$separatorCounter,
            "admin_label" => false,
        ),

        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Border Color", 'massive-dynamic'),
            "param_name" => "info_box_border_color",
            "admin_label" => false,
            "opacity" => true,
            "description" => esc_attr__("Choose border color", 'massive-dynamic'),
            "value" => 'rgba(31,213,190, .1)',
        ),
        /* Button */
        array(
            'type' => 'md_vc_checkbox',
            "edit_field_class" => $filedClass . " glue last first ",
            'heading' => esc_attr__('Add Button', 'massive-dynamic'),
            'param_name' => 'info_box_button',
            'value' => array(esc_attr__('Yes', 'massive-dynamic') => 'yes'),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),// info_box top add button
        array(
            "type" => 'md_vc_separator',
            "edit_field_class" => $filedClass . "stick-to-top",
            "param_name" => "info_box_use_button" . ++$separatorCounter,
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "admin_label" => false,
            "dependency" => array(
                'element' => "info_box_button",
                'value' => array('yes')
            )
        ),//separator
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "separate" => true,
            "heading" => esc_attr__("Button Style", 'massive-dynamic'),
            "param_name" => "info_box_button_style",
            "description" => esc_attr__("Choose between five button style", 'massive-dynamic'),
            "admin_label" => false,
            "value" => array(
                esc_attr__("Fill Rectangle", 'massive-dynamic') => "fill-rectangle",
                esc_attr__("Fade Square", 'massive-dynamic') => "fade-square",
                esc_attr__("Fade Oval", 'massive-dynamic') => "fade-oval",
                esc_attr__("Slide", 'massive-dynamic') => "slide",
                esc_attr__("Fill Slide", 'massive-dynamic') => "come-in",
                esc_attr__("Animation", 'massive-dynamic') => "animation",
                esc_attr__("Flash Animate", 'massive-dynamic') => "flash-animate",
                esc_attr__("Fill Oval", 'massive-dynamic') => "fill-oval"
            ),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn kind
        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Button Text", 'massive-dynamic'),
            "param_name" => "info_box_button_text",
            "value" => "View more",
            "description" => esc_attr__("Button text", 'massive-dynamic'),
            "admin_label" => false,
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn text
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_button_text_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top separator
        array(
            "type" => "md_vc_iconpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Choose an icon", 'massive-dynamic'),
            "param_name" => "info_box_button_icon_class",
            "admin_label" => false,
            "description" => esc_attr__("Select an icon that shown before text", 'massive-dynamic'),
            'value' => 'icon-snowflake2',
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn icon
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue first last",
            "heading" => esc_attr__("Button Color", 'massive-dynamic'),
            "param_name" => "info_box_button_color",
            "admin_label" => false,
            "opacity" => false,
            "value" => '#017eff',
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "description" => esc_attr__("Choose background color", 'massive-dynamic'),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
        ),//info_box top btn general color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_color_separator" . ++$separatorCounter,
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "edit_field_class" => $filedClass . "stick-to-top",
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
            ),
        ),//info_box top btn separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Text Color", 'massive-dynamic'),
            "param_name" => "info_box_button_text_color",
            "admin_label" => false,
            "opacity" => true,
            "description" => esc_attr__("Enter optional button's color", 'massive-dynamic'),
            'value' => '#fff',
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        ),//info_box top btn text color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_button_hover_color_separator" . ++$separatorCounter,
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue",
            "heading" => esc_attr__("Bg Hover Color", 'massive-dynamic'),
            "param_name" => "info_box_button_bg_hover_color",
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
            'value' => '#017eff'
        ),//info_box top btn bg hover color
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_button_hover_color_separator" . ++$separatorCounter,
            "group" => esc_attr__('Button', 'massive-dynamic'),
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('fill-oval', 'fill-rectangle'),
            ),
        ),//info_box top btn separator
        array(
            "type" => "md_vc_colorpicker",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Text Hover Color", 'massive-dynamic'),
            "param_name" => "info_box_button_hover_color",
            "admin_label" => false,
            "description" => esc_attr__("Enter optional button hover's color", 'massive-dynamic'),
            'value' => '#fff',
            "dependency" => array(
                'element' => "info_box_button_style",
                'value' => array('come-in', 'slide', 'fade-oval', 'fill-oval', 'fill-rectangle', 'fade-square'),
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn text hover color
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue first",

            "heading" => esc_attr__("Button Size", 'massive-dynamic'),
            "param_name" => "info_box_button_size",
            "admin_label" => false,
            "description" => esc_attr__("Choose size of your button", 'massive-dynamic'),
            "value" => array(
                esc_attr__("Standard", 'massive-dynamic') => "standard",
                esc_attr__("Small", 'massive-dynamic') => "small"
            ),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn  btn size
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_button_size_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn separator
        array(
            'type' => 'md_vc_slider',
            "edit_field_class" => $filedClass . "glue last",
            'heading' => esc_attr__('Button Padding', 'massive-dynamic'),
            'param_name' => 'info_box_button_padding',
            'value' => '0',
            'defaultSetting' => array(
                "min" => "0",
                "max" => "300",
                "prefix" => " px",
                "step" => "1",
            ),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn space
        array(
            "type" => "textfield",
            "edit_field_class" => $filedClass . "glue first",
            "heading" => esc_attr__("Button Link", 'massive-dynamic'),
            "param_name" => "info_box_button_url",
            "value" => "#",
            "admin_label" => false,
            "description" => esc_attr__("Button destination URL", 'massive-dynamic'),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn url
        array(
            "type" => 'md_vc_separator',
            "param_name" => "info_box_button_url_separator" . ++$separatorCounter,
            "admin_label" => false,
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn separator
        array(
            "type" => "dropdown",
            "edit_field_class" => $filedClass . "glue last",
            "heading" => esc_attr__("Link's target", 'massive-dynamic'),
            "param_name" => "info_box_button_target",
            "admin_label" => false,
            "description" => esc_attr__("Open the link in the same tab or a blank browser tab", 'massive-dynamic'),
            "value" => array(
                esc_attr__("Open in same window", 'massive-dynamic') => "_self",
                esc_attr__("Open in new window", 'massive-dynamic') => "_blank"
            ),
            'dependency' => array(
                'element' => "info_box_button",
                'value' => array('yes')
            ),
            "group" => esc_attr__('Button', 'massive-dynamic'),
        ),//info_box top btn target

    );
    return $param;
}

//Register "container" content element. It will hold all your inner (child) content elements
pixflow_map(array(
    "name" => esc_attr__("Info Box", 'massive-dynamic'),
    "base" => "md_info_box",
    "show_settings_on_create" => false,
    "category" => esc_attr__('Business','massive-dynamic'),
    "params" => pixflow_info_box()
));

/* **************************************************************************************************** */


pixflow_add_params('md_mobile_slider', pixflow_addAnimationTab('md_mobile_slider'));
pixflow_add_params('md_text', pixflow_addAnimationTab('md_text'));
pixflow_add_params('md_image_box_slider', pixflow_addAnimationTab('md_image_box_slider'));
pixflow_add_params('md_image_box_fancy', pixflow_addAnimationTab('md_image_box_fancy'));
pixflow_add_params('md_button', pixflow_addAnimationTab('md_button'));
pixflow_add_params('md_call_to_action', pixflow_addAnimationTab('md_call_to_action'));
pixflow_add_params('md_accordion', pixflow_addAnimationTab('md_accordion'));
pixflow_add_params('md_toggle', pixflow_addAnimationTab('md_toggle'));
pixflow_add_params('md_toggle2', pixflow_addAnimationTab('md_toggle2'));
pixflow_add_params('md_display_slider', pixflow_addAnimationTab('md_display_slider'));
pixflow_add_params('md_iconbox_top', pixflow_addAnimationTab('md_iconbox_top'));
pixflow_add_params('md_iconbox_side', pixflow_addAnimationTab('md_iconbox_side'));
pixflow_add_params('md_product_compare', pixflow_addAnimationTab('md_product_compare'));
pixflow_add_params('md_imagebox_full', pixflow_addAnimationTab('md_imagebox_full'));
pixflow_add_params('md_tabs', pixflow_addAnimationTab('md_tabs'));
pixflow_add_params('md_hor_tabs', pixflow_addAnimationTab('md_hor_tabs'));
pixflow_add_params('md_hor_tabs2', pixflow_addAnimationTab('md_hor_tabs2'));
pixflow_add_params('md_team_member_classic', pixflow_addAnimationTab('md_team_member_classic'));
pixflow_add_params('md_modernTabs', pixflow_addAnimationTab('md_modernTabs'));
pixflow_add_params('md_tablet_slider', pixflow_addAnimationTab('md_tablet_slider'));
pixflow_add_params('md_contactform', pixflow_addAnimationTab('md_contactform'));
pixflow_add_params('md_skill_style1', pixflow_addAnimationTab('md_skill_style1'));
pixflow_add_params('md_skill_style2', pixflow_addAnimationTab('md_skill_style2'));
pixflow_add_params('md_portfolio_multisize', pixflow_addAnimationTab('md_portfolio_multisize'));
pixflow_add_params('md_video', pixflow_addAnimationTab('md_video'));
pixflow_add_params('md_showcase', pixflow_addAnimationTab('md_showcase'));
pixflow_add_params('md_testimonial_classic', pixflow_addAnimationTab('md_testimonial_classic'));
pixflow_add_params('md_testimonial_carousel', pixflow_addAnimationTab('md_testimonial_carousel'));
pixflow_add_params('md_client_normal', pixflow_addAnimationTab('md_client_normal'));
pixflow_add_params('md_instagram', pixflow_addAnimationTab('md_instagram'));
pixflow_add_params('md_blog', pixflow_addAnimationTab('md_blog'));
pixflow_add_params('md_list', pixflow_addAnimationTab('md_list'));
pixflow_add_params('md_separator', pixflow_addAnimationTab('md_separator'));
pixflow_add_params('md_subscribe', pixflow_addAnimationTab('md_subscribe'));
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || class_exists('WooCommerce')) {
    pixflow_add_params('md_product_categories', pixflow_addAnimationTab('md_product_categories'));
    pixflow_add_params('md_products', pixflow_addAnimationTab('md_products'));
}
pixflow_add_params('md_teammember2', pixflow_addAnimationTab('md_teammember2'));
pixflow_add_params('md_blog_masonry', pixflow_addAnimationTab('md_blog_masonry'));
pixflow_add_params('md_blog_carousel', pixflow_addAnimationTab('md_blog_carousel'));
pixflow_add_params('md_counter', pixflow_addAnimationTab('md_counter'));
pixflow_add_params('md_countbox', pixflow_addAnimationTab('md_countbox'));
pixflow_add_params('md_pie_chart', pixflow_addAnimationTab('md_pie_chart'));
pixflow_add_params('md_pie_chart2', pixflow_addAnimationTab('md_pie_chart2'));
pixflow_add_params('md_icon', pixflow_addAnimationTab('md_icon'));
pixflow_add_params('md_textbox', pixflow_addAnimationTab('md_textbox'));
pixflow_add_params('md_text_box', pixflow_addAnimationTab('md_text_box'));
pixflow_add_params('md_fancy_text', pixflow_addAnimationTab('md_fancy_text'));
pixflow_add_params('md_full_button', pixflow_addAnimationTab('md_full_button'));
pixflow_add_params('md_iconbox_side2', pixflow_addAnimationTab('md_iconbox_side2'));
pixflow_add_params('md_slider_carousel', pixflow_addAnimationTab('md_slider_carousel'));
pixflow_add_params('md_modern_subscribe', pixflow_addAnimationTab('md_modern_subscribe'));
pixflow_add_params('md_double_slider', pixflow_addAnimationTab('md_double_slider'));
pixflow_add_params('md_price_table', pixflow_addAnimationTab('md_price_table'));
pixflow_add_params('md_quote', pixflow_addAnimationTab('md_quote'));
pixflow_add_params('md_feature_image', pixflow_addAnimationTab('md_feature_image'));
pixflow_add_params('md_price_box', pixflow_addAnimationTab('md_price_box'));
pixflow_add_params('md_article_box', pixflow_addAnimationTab('md_article_box'));
pixflow_add_params('md_countercard', pixflow_addAnimationTab('md_countercard'));
pixflow_add_params('md_statistic', pixflow_addAnimationTab('md_statistic'));
pixflow_add_params('md_splitBox', pixflow_addAnimationTab('md_splitBox'));
pixflow_add_params('md_iconbox_new', pixflow_addAnimationTab('md_iconbox_new'));
pixflow_add_params('md_process_panel', pixflow_addAnimationTab('md_process_panel'));
pixflow_add_params('md_info_box', pixflow_addAnimationTab('md_info_box'));



/****************************team member new*********************************************/
$filed_class = 'vc_col-sm-12 vc_column ';
$separator_counter = 0;
pixflow_map(
    array(
        "name" => "Team Member Modern",
        "base" => "md_team_member_modern",
        "category" => esc_attr__('Business','massive-dynamic'),
        "allowed_container_element" => 'vc_row',
        "show_settings_on_create" => false,

        "params" => array(

            array(
                "type" => "textfield",
                "edit_field_class" => $filed_class . "first glue",
                "heading" => esc_attr__("Name", 'massive-dynamic'),
                "param_name" => "team_member_modern_title",
                'value' => 'Maria Guerra',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separator_counter,
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filed_class . "glue",
                "heading" => esc_attr__("Job Title", 'massive-dynamic'),
                "param_name" => "team_member_modern_subtitle",
                'value' => 'Finance Manager',
                "admin_label" => false,
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separator_counter,
            ),

            array(
                "type" => "textarea",
                "edit_field_class" => $filed_class . "glue last",
                "heading" => esc_attr__("About Member", 'massive-dynamic'),
                "param_name" => "team_member_modern_description",
                'value' => 'Hello, my name is Maria Guerra, I\'m a Finance Manager at Pixflow.',
                "admin_label" => false,
            ),


            array(
                'type' => 'attach_image',
                'edit_field_class' => $filed_class . "glue first last",
                'heading' => esc_attr__('Choose Image', 'massive-dynamic'),
                'param_name' => 'team_member_modern_image',

            ),



            array(
                "type" => "md_vc_colorpicker",
                "edit_field_class" => $filed_class . "first glue last",
                "heading" => esc_attr__("Overlay Color", 'massive-dynamic'),
                "param_name" => "team_member_modern_hover_color",
                "admin_label" => false,
                'value' => '#1c61a8'
            ),





            array(
                "type" => "md_vc_iconpicker",
                "edit_field_class" => $filed_class . "glue first",
                "heading" => esc_attr__("Social Network 1", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon1",
                "group" => esc_attr__("Social Icons", 'massive-dynamic'),
                "admin_label" => false,
                "value" => "icon-facebook2"
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separator_counter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filed_class . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon1_url",
                "value" => 'http://www.facebook.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filed_class . "glue first",
                "heading" => esc_attr__("Social Network 2", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon2",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-twitter5',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separator_counter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filed_class . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon2_url",
                'value' => 'http://www.twitter.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),

            array(
                "type" => "md_vc_iconpicker",

                "edit_field_class" => $filed_class . "glue first",
                "heading" => esc_attr__("Social Network 3", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon3",
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
                "value" => 'icon-google',
            ),

            array(
                "type" => 'md_vc_separator',
                "param_name" => "team_member_styl1_separator" . ++$separator_counter,
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
            ),

            array(
                "type" => "textfield",
                "edit_field_class" => $filed_class . "glue last",

                "heading" => esc_attr__("Page Url", 'massive-dynamic'),
                "param_name" => "team_member_modern_icon3_url",
                'value' => 'http://www.google.com',
                'group' => esc_attr__('Social Icons', 'massive-dynamic'),
                "admin_label" => false,
            ),


        ),

    )
);

pixflow_add_params('md_team_member_modern', pixflow_addAnimationTab('md_team_member_modern'));