<?php
/**
 * makes mBuilder Compatible with Visual Composer
 *
 * @author PixFlow
 */

function mBuilderExcludeVisualComposer() {
    if(preg_match('/\.php/is',$_SERVER['REQUEST_URI'])) {
        $active_plugins = (array) get_option( 'active_plugins', array() );
        $p = array();
        foreach ($active_plugins as $key => $value) {
            if (strpos($value, 'js_composer.php') !== false) {
                if (strpos($value, '.dontload') === false) {
                    $value = $value . '.dontload';
                }
            }
            $p[$key] = $value;
        }
        update_option('active_plugins', $p);
        wp_cache_flush();
    }
    if(!function_exists('pixflow_js_remove_wpautop')) {
        mBuilderPrerequisits();
    }
}

function mBuilderIncludeVisualComposer(){
    $reload = false;
    $active_plugins = (array) get_option( 'active_plugins', array() );
    if(in_array("js_composer/js_composer.php.dontload",$active_plugins)) {
        foreach($active_plugins as $key=> $value){
            $active_plugins[$key] = str_replace('.dontload','',$value);
        }
        $reload = true;
    }
    if(!in_array("js_composer/js_composer.php",$active_plugins)) {
        $active_plugins[] = "js_composer/js_composer.php";
        $reload = true;
    }
    if($reload) {
        $active_plugins = array_unique($active_plugins);
        update_option('active_plugins', $active_plugins);
        wp_cache_flush();

        header("Refresh:0");
    }
}