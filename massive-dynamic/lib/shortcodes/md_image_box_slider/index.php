<?php
/**
 * Image Box Slider Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_image_box_slider', 'pixflow_get_style_script'); // pixflow_sc_imageBoxSlider

function pixflow_sc_image_box_slider($atts, $content = null)
{
    extract(shortcode_atts(array(
        'image_box_slider_image'              => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'image_box_slider_height'             => '300',
        'image_box_slider_size'               => 'initial',
        'image_box_slider_effect_slider'      => 'fade',
        'image_box_slider_speed'              => '3000',
        'image_box_slider_hover'              => 'no',
        'image_box_slider_hover_link'         => '',
        'image_box_slider_hover_effect'       => 'text',
        'image_box_slider_hover_image_sec'    => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'image_box_slider_hover_text_effect'  => 'light',
        'image_box_slider_hover_text'         => 'Text Hover',
        'align'                               => 'center',

    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_image_box_slider',$atts);
    $id = pixflow_sc_id('imageBoxSlider');

    $image_box_slider_hover_effect;

    ob_start();



    // Main Image

    if(is_numeric($image_box_slider_image)){
        $image_url = wp_get_attachment_url($image_box_slider_image);
    }
    else{
        $image_url = $image_box_slider_image;
    }

    $image_url = (false == $image_url)?PIXFLOW_PLACEHOLDER1:$image_url;
    $image_pointer = explode(",",$image_box_slider_image);

    // Hover Image
    $image_url_hover = wp_get_attachment_url($image_box_slider_hover_image_sec);
    $image_url_hover = (false == $image_url_hover)?PIXFLOW_PLACEHOLDER1:$image_url_hover;
    $image_pointer_hover = explode(",",$image_box_slider_hover_image_sec);

    $counter = 0;
    ?>
    <?php
    $align = trim($align);
    ?>
    <div id="<?php echo esc_attr($id); ?>" data-speed="<?php echo esc_attr($image_box_slider_speed); ?>" data-effect="<?php echo esc_attr($image_box_slider_effect_slider); ?>" class="img-box-slider <?php echo esc_attr($animation['has-animation'].' md-align-'.$align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <ul class="slides">
            <?php
            foreach( $image_pointer as $value )
            {
                if(is_numeric($value)) {
                    $image_url = wp_get_attachment_url($value);
                }else{
                    $image_url = $value;
                }
                $image_url_flag = true;
                $image_meta_data = wp_get_attachment_metadata($value);
                if ($image_url == false){
                    $image_meta_data = array();
                    $image_url = PIXFLOW_PLACEHOLDER1;
                    $image_url_flag = false;
                }
                // if wordpress dont return any meta
                if(empty($image_meta_data )){
                    list($image_meta_data['width'] , $image_meta_data['height']) = @getimagesize($image_url);
                }
                else {
                    if (!isset($image_meta_data['width'])) {
                        $image_meta_data['width'] = '';
                        $image_meta_data['height'] = '';
                    }
                }
                ?>
                <li>

                    <div class="imgBox-image imgBox-image-main image-<?php echo esc_attr($id).$counter ?> <?php echo esc_attr($image_box_slider_size); ?>" data-height="<?php echo esc_attr($image_meta_data['height']);  ?>" data-width="<?php echo esc_attr($image_meta_data['width']);  ?>"></div>
                    <a href="<?php echo esc_url($image_box_slider_hover_link); ?>" class="imgBox-image imgBox-image-hover image-hover-<?php echo esc_attr($id).$counter ?>"></a>

                    <?php if ($image_box_slider_hover_effect == 'text') { ?>
                        <a target="_self" href="<?php echo(esc_url($image_box_slider_hover_link)); ?>" class="image-box-slider-hover-text <?php echo esc_attr( $image_box_slider_hover_text_effect == 'light' ? 'light' : 'dark'); ?>"> <?php echo esc_attr($image_box_slider_hover_text); ?> </a>
                    <?php } ?>

                    <!-- Set image background -->
                    <style>

                        .imgBox-image.image-<?php echo esc_attr($id).$counter ?> {
                            background-image: url("<?php echo esc_url($image_url); ?>");

                        <?php if($image_url_flag == false) { ?>
                            background-size: inherit;
                        <?php $image_url_flag = true; } ?>
                        }

                        <?php if ($image_box_slider_hover_link == '') { ?>

                        #<?php echo esc_attr($id) ?> .imgBox-image,
                        #<?php echo esc_attr($id) ?> .image-box-slider-hover-text {
                                                         pointer-events: none;
                                                         cursor: default;
                                                     }

                        <?php } ?>


                        /* Check if hover image effect selected */
                        <?php if ($image_box_slider_hover == 'yes' && $image_box_slider_hover_effect == 'image') { ?>

                        #<?php echo esc_attr($id) ?> .imgBox-image.image-<?php echo esc_attr($id).$counter ?> {
                                                         transition: all .3s;
                                                     }

                        .image-hover-<?php echo esc_attr($id).$counter ?> {
                            background-image: url("<?php echo esc_url($image_url_hover); ?>");
                            opacity: 0;
                        }

                        #<?php echo esc_attr($id) ?>:hover .image-hover-<?php echo esc_attr($id).$counter ?> {
                             opacity: 1;
                         }

                        #<?php echo esc_attr($id) ?> .image-hover-<?php echo esc_attr($id).$counter ?> {
                                                         background-size: <?php echo esc_attr($image_box_slider_size); ?>;
                                                     }

                        #<?php echo esc_attr($id) ?>:hover .image-<?php echo esc_attr($id).$counter ?> {
                             opacity: 0;
                         }

                        #<?php echo esc_attr($id) ?> .imgBox-image.image-<?php echo esc_attr($id).$counter ?>:after {
                                                         content: "";
                                                         height: 100%;
                                                         width: 100%;
                                                         display: block;
                                                         transition: all .3s;
                                                         opacity: 0;
                                                     }

                        #<?php echo esc_attr($id) ?>:hover .imgBox-image.image-<?php echo esc_attr($id).$counter ?>:after {
                             opacity: 1;
                         }

                        <?php } ?>


                        /* Check if hover text effect selected */
                        <?php if ($image_box_slider_hover == 'yes' && $image_box_slider_hover_effect == 'text') { ?>

                        #<?php echo esc_attr($id) ?> .imgBox-image.image-<?php echo esc_attr($id).$counter ?>:after {
                                                         opacity: 0;
                                                         transition: all .3s;
                                                         content: "";
                                                         display: block;
                                                         width: 100%;
                                                         height: 100%;
                                                     }

                        <?php if ($image_box_slider_hover_text_effect == 'light') { ?>

                        #<?php echo esc_attr($id) ?>:hover .imgBox-image.image-<?php echo esc_attr($id).$counter ?>:after {
                             background-color: rgba(255,255,255, .5);
                             opacity: 1;
                         }

                        #<?php echo esc_attr($id) ?>:hover .image-box-slider-hover-text{
                             opacity: 1;
                             color: #000;
                         }

                        <?php } ?>

                        <?php if ($image_box_slider_hover_text_effect == 'dark') { ?>

                        #<?php echo esc_attr($id) ?>:hover .imgBox-image.image-<?php echo esc_attr($id).$counter ?>:after {
                             background-color: rgba(0,0,0, .5);
                             opacity: 1;
                         }

                        #<?php echo esc_attr($id) ?>:hover .image-box-slider-hover-text{
                             opacity: 1;
                             color: #fff;
                         }

                        <?php } ?>

                        <?php } ?>


                        /* Image Size */
                        #<?php echo esc_attr($id) ?> .imgBox-image.image-<?php echo esc_attr($id).$counter ?> {
                                                         background-size: <?php echo esc_attr($image_box_slider_size); ?>;
                                                     }

                    </style>

                </li>

                <?php $counter++; } ?>
        </ul>

    </div> <!-- End image box slider -->

    <script>
        $(document).ready(function() {
            if (typeof pixflow_imageBoxSlider == 'function') {
                pixflow_imageBoxSlider("<?php echo esc_attr($id) ?>", "<?php echo esc_attr($image_box_slider_height) ?>");
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
