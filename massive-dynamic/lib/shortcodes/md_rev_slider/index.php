<?php
/**
 * Revolution Slider Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_rev_slider', 'pixflow_get_style_script'); // pixflow_sc_rev_slider

function pixflow_sc_rev_slider( $atts, $content = null ){

    if(!class_exists('RevSlider')) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">Revolution Slider</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s to use this shortcode','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    extract( shortcode_atts( array(
        'md_rev_slider_alias'  =>''
    ),$atts ));

    //$output = $md_rev_slider_alias = '';

    ob_start();
    $md_rev_slider_alias = esc_attr($md_rev_slider_alias);
    if ($md_rev_slider_alias == '' || $md_rev_slider_alias == '0'){

        $slider = new RevSlider();
        $arrSliders = $slider->getArrSliders();
        $revsliders = array();
        if ( $arrSliders ) {
            foreach ( $arrSliders as $slider ) {
                $revsliders[ $slider->getTitle() ] = $slider->getAlias();
            }
        }
        if ( is_array($revsliders) && (count($revsliders) > 0)){
            $echo = do_shortcode('[rev_slider alias="'.esc_attr(current($revsliders)).'"]');
            echo preg_replace('~<script type="text/javascript">(.*?)</script>~is',"<script type=\"text/javascript\">try{\n $1 \n}catch(e){}</script>",$echo);
        }else{
            $url = admin_url('admin.php?page=revslider');
            $a='<a href="'.$url.'">Revolution Slider</a>';
            $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Can\'t find any slider, please create a slider in %s, then use this shortcode. ','massive-dynamic'),$a).'</p></div>';
            return $mis;
        }
    }else{
        $echo =  do_shortcode("[rev_slider alias=$md_rev_slider_alias]");
        echo preg_replace('~<script type="text/javascript">(.*?)</script>~is',"<script type=\"text/javascript\">try{\n $1 \n}catch(e){}</script>",$echo);
    }
    return ob_get_clean();

}
