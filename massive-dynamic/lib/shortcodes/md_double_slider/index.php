<?php
/**
 * Double Slider Shortcode
 *
 * @author Pixflow
 */

/*-----------------------------------------------------------------------------------*/
/*  Double Slider
/*-----------------------------------------------------------------------------------*/

/*Double Slider*/
add_shortcode('md_double_slider', 'pixflow_get_style_script'); // pixflow_sc_doubleSlider

function pixflow_sc_double_slider($atts, $content = null){
    extract( shortcode_atts( array(
        'slide_num' => '3',
        'double_slider_auto_play' => 'yes',
        'double_slider_duration' => '5',
        'double_slider_height' => '500',
        'double_slider_appearance' => 'double-slider-left'
    ), $atts ) );

    for($i=1; $i<=$slide_num; $i++){
        $slides[$i] = shortcode_atts( array(
            'slide_title_'.$i => 'Title'.$i,
            'slide_sub_title_'.$i => 'Subtitle'.$i,
            'slide_description_'.$i => 'Slide Description'.$i,
            'slide_bg_'.$i => '#447be0',
            'slide_fg_'.$i => '#ffffff',
            'slide_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        ), $atts );
    }
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_double_slider',$atts);

    $id = pixflow_sc_id('md_double_slider');

    if( 'yes' == $double_slider_auto_play){
        $autoPlay = 'true';
    } else{
        $autoPlay = 'false';
    }
    ob_start();
    ?>
    <style scoped>
        #<?php echo esc_attr($id); ?> .double-slider-image-container li div{
            background-size: cover;
            height: <?php echo esc_attr($double_slider_height)?>px;
        }
        #<?php echo esc_attr($id); ?> .double-slider-text-container ul.double-slider-slides{
                                          height: <?php echo esc_attr($double_slider_height)?>px;
                                      }
    </style>
    <div id="<?php echo esc_attr($id); ?>" class="double-slider clearfix <?php echo esc_attr($animation['has-animation'].' '.$double_slider_appearance) ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="double-slider-image-container">
            <ul class="double-slider-slides slides clearfix">
                <?php
                foreach($slides as $key=>$slide){
                    $image = $slide['slide_image_'.$key];
                    if($image != '' && is_numeric($image)){
                        $image = wp_get_attachment_image_src( $image,'full') ;
                        $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
                    }
                    ?>
                    <li>
                        <div style="background-image: url(<?php echo esc_attr($image);?>);"></div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>

        <div class="double-slider-text-container">
            <div class="double-slider-nav">
                <a href="#" class="double-slider-prev"><i class="px-icon icon-arrow-left4"></i></a>
                <a href="#" class="double-slider-next"><i class="px-icon icon-arrow-right7"></i></a>
            </div>
            <ul class="double-slider-slides slides clearfix">
                <?php
                $bg = array();
                $fgArr = array();
                foreach($slides as $key=>$slide){

                    $title = $slide['slide_title_'.$key];
                    $subTitle = $slide['slide_sub_title_'.$key];
                    $decription = $slide['slide_description_'.$key];
                    $bg[] = esc_attr($slide['slide_bg_'.$key]);
                    $fgArr[] = esc_attr($slide['slide_fg_'.$key]);
                    $fg = $slide['slide_fg_'.$key];
                    $image = $slide['slide_image_'.$key];

                    if($image != '' && is_numeric($image)){
                        $image = wp_get_attachment_image_src( $image) ;
                        $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
                    }
                    ?>
                    <li style="color:<?php echo esc_attr($fg)?>">
                        <div class="double-slider-container">
                            <p class="double-slider-sub-title"><?php echo esc_attr($subTitle)?></p>
                            <h3 class="double-slider-title"><?php echo esc_attr($title)?></h3>
                            <p class="double-slider-description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($decription))?></p>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>

        </div>
    </div>
    <script>
        "use strict";
        var $ = jQuery;
        $(function(){
            if(typeof pixflow_doubleSlider == 'function'){
                pixflow_doubleSlider('<?php echo esc_attr($id); ?>',["<?php echo implode('","',$bg);?>"],["<?php echo implode('","',$fgArr);?>"],<?php echo esc_attr($autoPlay)?>,<?php echo esc_attr($double_slider_duration*1000)?>);
            }
        });
        <?php echo pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}
