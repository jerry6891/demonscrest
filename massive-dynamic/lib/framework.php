<?php

require_once(PIXFLOW_THEME_LIB .'/string.php');

class PixflowFramework {
    /**
     * Includes (require_once) php file(s) inside selected folder
     */
    public static function Pixflow_Require_Files($path, $fileName)
    {


        if(is_string($fileName))
        {
            require_once(pixflow_path_combine($path, $fileName) . '.php');
        }
        elseif(is_array($fileName))
        {
            foreach($fileName as $name)
            {
                require_once(pixflow_path_combine($path, $name) . '.php');
            }
        }
        else
        {
            //Throw error
            throw new Exception('Unknown parameter type');
        }
    }

    public static function Pixflow_Shortcodes()
    {
        $file_contents = @file_get_contents( PIXFLOW_THEME_SHORTCODES . '/shortcodes_list.json') ;

        $shortcodes_list = json_decode($file_contents,true);
        $shortcodes_list = array_map("pixflow_shortcode_path", $shortcodes_list['shortcodes']);
        return $shortcodes_list;
    }

}
function pixflow_shortcode_path($value) {
    return $value.'/index';
}
$pixflow_loaded_shortcodes = $pixflow_loaded_plugins = array();
//Include framework files
$requiredArray = array(
    'constants',
    'utilities',
    'admin/admin',
    'google-fonts',
    'scripts',
    'support',
    'retina-upload',
    'sidebars',
    'plugins-handler',
    'nav-walker',
    'menus',
    'shortcodes/shortcodes',
    'customizer/customizer',
    'metaboxes',
    'layout-functions',
    'unique-setting',
    'woocommerce/woocommerce',
    'instagram/instagram',
    'post-like',
);

PixflowFramework::Pixflow_Require_Files( PIXFLOW_THEME_LIB,$requiredArray);

//Add post types
PixflowFramework::Pixflow_Require_Files( PIXFLOW_THEME_LIB . '/post-types',
    array( 'blog','page','portfolio','featured-gallery'));

//Add widgets
PixflowFramework::Pixflow_Require_Files( PIXFLOW_THEME_LIB . '/widgets',
    array(
        'widget-recent_portfolio/index',
        'widget-recent_posts/index',
        'widget-progress/index',
        'widget-contact_info/index',
        'widget-instagram/index',
        'widget-text/index',
        'widget-social/index',
        //'widget-twitter',
        'widget-subscribe/index'
    )

);

if(is_customize_preview()){
    //Add Shortcodes
    $shortcodesBootStrap = PixflowFramework::Pixflow_Shortcodes();
    PixflowFramework::Pixflow_Require_Files( PIXFLOW_THEME_LIB . '/shortcodes',$shortcodesBootStrap);
}

pixflow_empty_cache();
if(!is_dir(PIXFLOW_THEME_CACHE)){
    wp_mkdir_p(PIXFLOW_THEME_CACHE);
}