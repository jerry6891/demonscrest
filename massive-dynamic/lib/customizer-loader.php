<?php
if(session_id() == '' && !headers_sent()) {
    session_start();
}
unset($_SESSION['temp_status']);
require_once(PIXFLOW_THEME_LIB . '/constants.php');
//Return theme option
function pixflow_opt($option){
    $opt = get_option(PIXFLOW_OPTIONS_KEY);
    return stripslashes($opt[$option]);
}
// retrieves the attachment ID from the file URL
function pixflow_get_image_id($image_url) {
    global $wpdb;

    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " .$wpdb->prefix. "posts WHERE guid='%s';", $image_url));

    if(count($attachment))
        return $attachment[0];
    else
        return false;
}
function pixflow_get_custom_sidebars()
{
    $sidebarStr = pixflow_opt('custom_sidebars');

    if(strlen($sidebarStr) < 1)
        return array();

    $arr      = explode(',', $sidebarStr);
    $sidebars = array();

    foreach($arr as $item)
    {
        $sidebars["custom-" . hash("crc32b", $item)] = str_replace('%666', ',', $item);
    }

    return $sidebars;
}
function pixflow_get_post_id($post_id = false){

    if ($post_id != false) {
        $post_id = $post_id;
    } elseif (isset($_SESSION['pixflow_post_id']) && $_SESSION['pixflow_post_id'] != null) {
        $post_id = $_SESSION['pixflow_post_id'];
    } else {
        if (is_home() || is_404() || is_search()) {
            $post_id = get_option('page_for_posts');
        } elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
            $post_id = get_option('woocommerce_shop_page_id');
        } else {
            $post_id = get_the_ID();
        }
    }
    return $post_id;
}

$pixflow_general_settings = $pixflow_unique_settings = array();
$pixflow_post_setting_status = array('no-post-id'=>'');
$pixflow_post_type = '';
$pixflow_post_id = '';
/*
* get post setting status
* return unique or general
*/
function pixflow_get_post_setting_status($post_id=false){
    $id = $post_id;
    global $pixflow_post_setting_status;
    if($id && isset($pixflow_post_setting_status[$id])){
        return $pixflow_post_setting_status[$id];
    }elseif($pixflow_post_setting_status['no-post-id']!=''){
        return $pixflow_post_setting_status['no-post-id'];
    }
    $post_id =  pixflow_get_post_id($post_id);
    global $pixflow_post_id;
    $pixflow_post_id = $post_id;
    $post_type = get_post_type($post_id);
    global $pixflow_post_type;
    $pixflow_post_type = $post_type;
    if ((isset($_SESSION['temp_status'])) && $_SESSION['temp_status']['id'] == $post_id) {
        $setting_status = $_SESSION['temp_status']['status'];
    } elseif (get_option('page_for_posts') != $post_id && ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product')) {
        if (isset($_SESSION[$post_type . '_status'])) {
            $setting_status = $_SESSION[$post_type . '_status'];
        } else {
            $setting_status = get_option($post_type . '_setting_status');
        }
    } else {
        $setting_status = get_post_meta($post_id, 'setting_status', true);
    }
    $setting_status = ($setting_status == 'unique') ? 'unique' : 'general';
    if($id){
        $pixflow_post_setting_status[$id] = $setting_status;
    }else{
        $pixflow_post_setting_status['no-post-id'] = $setting_status;
    }
    return $setting_status;
}
/*
 * Get value from cashed setting for general setting and cache setting if its not cashed before
 * */
function pixflow_get_general_setting($setting,$default){
    if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'pixflow-get-setting') || is_customize_preview()){
        return get_theme_mod($setting, $default);
    }
    global $pixflow_general_settings;
    if(!count($pixflow_general_settings)){
        $pixflow_general_settings = get_theme_mods();
    }
    return isset($pixflow_general_settings[$setting])?$pixflow_general_settings[$setting]:$default;
}

/*
 * Get value from cashed setting for unique setting and cache setting if its not cashed before
 * */
function pixflow_get_unique_setting($post_id,$post_type,$setting,$default){
    global $pixflow_unique_settings;
    if(!count($pixflow_unique_settings)){
        if ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product') {
            $pixflow_unique_settings = wp_load_alloptions();
        } else {
            $settings = get_post_meta($post_id);
            foreach($settings as $key=>$val){
                $pixflow_unique_settings[$key] = $val[0];
            }
        }
    }
    if ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product') {
        $value = isset($pixflow_unique_settings[$post_type . '_' . $setting])?$pixflow_unique_settings[$post_type . '_' . $setting]:pixflow_get_general_setting($setting, $default);
        $value = ($value === false) ? pixflow_get_general_setting($setting, $default) : $value;
    } else {
        $value = isset($pixflow_unique_settings[$setting])?$pixflow_unique_settings[$setting]:pixflow_get_general_setting($setting, $default);
        $value = ($value === 'false') ? false : $value;
    }
    return $value;
}

//Return customizer option value
function pixflow_get_theme_mod($name, $default = null, $post_id = false){
    $setting_status = pixflow_get_post_setting_status($post_id);
    $customizedValues = (isset($_SESSION[$setting_status . '_customized'])) ? $_SESSION[$setting_status . '_customized'] : array();
    if (isset($_POST['customized'])) {
        $customizedValues = json_decode(wp_unslash($_POST['customized']), true);
    }
    if (count($customizedValues) && array_key_exists($name, $customizedValues)) {
        $value = $customizedValues[$name];

    }else{
        if ($setting_status == 'unique') {
            global $md_uniqueSettings;
            if (in_array($name, $md_uniqueSettings)) {
                global $pixflow_post_type;
                global $pixflow_post_id;
                $value = pixflow_get_unique_setting($pixflow_post_id,$pixflow_post_type,$name, $default);
            }else{
                $value = pixflow_get_general_setting($name, $default);
            }
        } else {
            $value = pixflow_get_general_setting($name, $default);
        }
    }
    $value = ($value === 'false') ? false : $value;
    return $value;
}

function pixflow_path_combine($path1, $path2)
{
    $dirSep = '/';//It should be DIRECTORY_SEPARATOR constant but doesn't work with URIs in WordPress
    $e1   = $path1{strlen($path1) - 1};
    $b2   = $path2{0};

    //Convert
    if($e1 === '\\')
        $e1 = $dirSep;

    if($b2 === '\\')
        $b2 = $dirSep;


    //Both paths has no separator chars
    if($e1 !== $dirSep && $b2 !== $dirSep)
    {
        $value = $path1 . $dirSep . $path2;
    }
    //One path has directory separator and the other doesn't
    elseif(($e1 === $dirSep && $b2 !== $dirSep) ||
        ($e1 !== $dirSep && $b2 === $dirSep)
    )
    {
        $value = $path1 . $path2;
    }
    //Else both path has directory separator
    else
    {
        $value = $path1 . mb_substr($path2, 1);
    }

    $args  = func_get_args();

    if(count($args) < 3)
        return $value;

    $newArgs = array_merge(array($value), array_slice($args, 2));

    return call_user_func_array('pixflow_path_combine', $newArgs);
}
function custom_remove_themes_section() {
    global $wp_customize;
    $wp_customize->remove_section( 'themes' );
    $wp_customize->remove_control( 'active_theme' );
}
add_action( 'customize_register', 'custom_remove_themes_section' );
require_once(PIXFLOW_THEME_LIB . '/customizer/customizer.php');
require_once(PIXFLOW_THEME_LIB . '/menus.php');
require_once(PIXFLOW_THEME_LIB . '/sidebars.php');

/*Allow uploader to upload fonts files*/
function pixflow_allow_font_upload ( $existing_mimes=array() ) {
    $existing_mimes['woff2'] = 'font/woff2';
    $existing_mimes['woff'] = 'font/woff';
    $existing_mimes['ttf'] = 'font/ttf';
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['eot'] = 'font/eot';
    return $existing_mimes;
}
add_filter('upload_mimes', 'pixflow_allow_font_upload');

/*
 * Fix issues  with upload custom fonts and files such as SVG that disabled from wordpress 4.7.1
 *  */
function pixflow_fix_upload_custom_issue($data, $file, $filename, $mimes) {
    global $wp_version;
    if ( $wp_version !== '4.7.1' && $wp_version !== '4.7.2' ) {
        return $data;
    }
    $filetype = wp_check_filetype( $filename, $mimes );
    return array(
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    );
}
add_filter( 'wp_check_filetype_and_ext', 'pixflow_fix_upload_custom_issue', 10, 4 );