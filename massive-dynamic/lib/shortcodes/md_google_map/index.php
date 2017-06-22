<?php
/**
 * Google Map Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_google_map', 'pixflow_get_style_script'); // pixflow_sc_google_map

function pixflow_sc_google_map( $atts, $content = null ){

    $output = $md_google_map_lat = $md_google_map_lon = $md_google_map_zoom=$md_google_map_type= $md_google_map_height=$md_google_map_marker='';
    extract( shortcode_atts( array(
        'md_google_map_lat'  =>'37.7533106' ,
        'md_google_map_lon'  => '-122.4818691',
        'md_google_map_zoom' => '15',
        'md_google_map_type' => 'gray',
        'md_google_map_marker' => '',
        'md_google_map_height' => '400',
        'md_google_map_key'    => '',
    ),$atts ));

    if(is_numeric($md_google_map_marker)){
        $md_google_map_marker =  wp_get_attachment_image_src( $md_google_map_marker, 'pixflow_recent-portfolio-widget') ;
        $md_google_map_marker = (false == $md_google_map_marker)?PIXFLOW_PLACEHOLDER_BLANK:$md_google_map_marker[0];
    }else{
        $md_google_map_marker=PIXFLOW_THEME_IMAGES_URI."/marker.png";
    }

    if ($md_google_map_key == '') {

        $a='<a href="https://developers.google.com/maps/documentation/javascript/">google map website </a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('You need to create a project on  %s and get a token for using google map first, then use this shortcode.','massive-dynamic'),$a).'</p></div>';

        return $mis;

    }else {

        //Main JS handler
        $callback = 'initMap_'.uniqid();
        wp_enqueue_script('googleMap', "//maps.googleapis.com/maps/api/js?key=".esc_attr($md_google_map_key)."&callback=".$callback, array(), PIXFLOW_THEME_VERSION, true);

        $id = pixflow_sc_id('google_map');
        ob_start();
        ?>
        <style scoped>
            .<?php echo esc_attr($id); ?>{
                height:<?php echo esc_attr($md_google_map_height); ?>px;
            }
        </style>
        <div class="<?php echo esc_attr($id); ?> md-google-map">
        </div>

        <script>
            var $ = jQuery;
            if (typeof google === 'object' && typeof google.maps === 'object'){
                <?php echo esc_attr($callback); ?>();
            }
            function <?php echo esc_attr($callback); ?>(){
                if ( typeof pixflow_googleMap == 'function' ){
                    pixflow_googleMap('<?php echo esc_attr($id); ?>','<?php echo esc_attr($md_google_map_lat); ?>','<?php echo esc_attr($md_google_map_lon); ?>','<?php echo esc_attr($md_google_map_zoom); ?>','<?php echo esc_attr($md_google_map_type); ?>','<?php echo esc_attr($md_google_map_marker); ?>');
                }
            };

        </script>

        <?php
    }
    return ob_get_clean();
}