<?php
/**
 * Slider Carousel Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_slider_carousel', 'pixflow_get_style_script'); // pixflow_sc_slider_carousel

function pixflow_sc_slider_carousel($atts, $content = null)
{
    $slider_heights=0;
    extract(shortcode_atts(array(
        'slider_images'              => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'slider_heights'             => '600',
        'slider_margin'             => '20',
        'slider_nav_active_color'   => 'rgba(68,123,225,1)',
        'slider_shadow'             => 'yes',
        'slider_slider_speed'       => '5',
        'slider_auto_play'          => 'yes',
        'align'                     => 'center',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_slider_carousel',$atts);
    $id = pixflow_sc_id('SliderCarousel');

    if($slider_auto_play=='yes'){
        $slider_auto_play= $slider_slider_speed*1000;
    }else{
        $slider_auto_play='false';
    }
    ob_start();

    // Main Image
    $image_url = wp_get_attachment_url($slider_images);

    $image_pointer = explode(",",$slider_images);

    if($image_url==false){
        $image_pointer=array(
            '0'=>$slider_images,
            '1'=>$slider_images,
            '2'=>$slider_images
        );
    }
    $counter = 0;
    ?>
    <style scoped>
        #<?php echo esc_attr($id); ?> .gallery-cell{
            height: <?php echo round(esc_attr($slider_heights))-40?>px;
            margin:0 <?php echo esc_attr($slider_margin)?>px;
        }
        #<?php echo esc_attr($id); ?> .flickity-viewport{
                                          height: <?php echo round(esc_attr($slider_heights))+40?>px !important;
                                      }

        #<?php echo esc_attr($id); ?> .dot.is-selected{
                                          background:<?php echo esc_attr($slider_nav_active_color)?>
                                      }
        <?php if($slider_shadow == 'yes'){?>
        @media screen and ( min-width: 768px ) {
        #<?php echo esc_attr($id); ?> .gallery-cell{
            box-shadow: 0px 20px 40px 0px #aaa;
        }
        }
        <?php }?>
    </style>
    <?php
    $align = trim($align);
    ?>
    <div id="<?php echo esc_attr($id); ?>" class="slider-carousel <?php echo esc_attr($animation['has-animation'].' md-align-'.$align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <div class="gallery js-flickity" data-flickity-options='{
                "contain": true ,
                "initialIndex": 1 ,
                "autoPlay": <?php echo esc_attr($slider_auto_play);?>,
                "prevNextButtons": false,
                "percentPosition": false,
                "wrapAround": true,
                "pauseAutoPlayOnHover": false,
                "selectedAttraction": 0.045,
                "friction": 0.5
            }'>
            <?php
            foreach( $image_pointer as $value )
            {
                $image_url = wp_get_attachment_url($value);
                $image_url_flag = true;
                if ($image_url == false){
                    $image_url = PIXFLOW_PLACEHOLDER1;
                    $image_url_flag = false;
                }
                ?>
                <div class="gallery-cell" style="<?php echo "background-image:url('".esc_attr($image_url)."');"?>"></div>
                <?php
                $counter++;
            } ?>
        </div>
    </div> <!-- End Slider Carousel -->
    <script>
        var $ = jQuery;
        $(function(){
            if(typeof pixflow_sliderCarousel == 'function'){
                pixflow_sliderCarousel($('#<?php echo esc_attr($id); ?> .gallery'),<?php echo esc_attr($slider_auto_play);?>);
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}