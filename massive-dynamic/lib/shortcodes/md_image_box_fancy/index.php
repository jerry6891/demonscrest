<?php
/**
 * Image Box Fancy Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_image_box_fancy', 'pixflow_get_style_script'); // pixflow_sc_imageBoxFancy

function pixflow_sc_image_box_fancy($atts, $content = null)
{
    extract(shortcode_atts(array(
        'image_box_fancy_image'              => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'image_box_fancy_height_type'       => 'manual',
        'image_box_fancy_height'             => '450',
        'image_box_fancy_size'               => 'auto',
        'image_box_fancy_effect_slider'      => 'fade',
        'image_box_fancy_speed'              => '3000',
        'image_box_fancy_style'             => 'normal',
        'image_box_fancy_icon'              => 'icon-Diamond',
        'image_box_fancy_icon_color'        => 'rgba(0,177,177,1)',
        'image_box_fancy_text_color'        => 'rgba(0,0,0,1)',
        'image_box_fancy_background_color'  => 'rgba(255,255,255,1)',
        'image_box_fancy_description_title' => 'Fancy Image Box',
        'image_box_fancy_description_text'  => 'Massive Dynamic has over 10 years of experience in Design. We take pride in delivering Intelligent Designs and Engaging Experiences for clients all over the World.',
        'align'                             => 'center',


    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_image_box_fancy',$atts);
    $id = pixflow_sc_id('imageBoxFancy');

    ob_start();



    // Main Image
    $imageSrc = wp_get_attachment_url($image_box_fancy_image);
    $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER1:$imageSrc;
    $image_url = $imageSrc;
    $image_pointer = explode(",",$image_box_fancy_image);

    $counter = 0;
    ?>
    <style scoped>
        #<?php echo esc_attr($id); ?> .image-box-fancy-desc{
            background: <?php echo esc_attr($image_box_fancy_background_color)?>;
        }
        #<?php echo esc_attr($id); ?> .image-box-fancy-collapse,
        #<?php echo esc_attr($id); ?> .image-box-fancy-icon{
                                          color: <?php echo esc_attr($image_box_fancy_icon_color)?>;
                                      }
        #<?php echo esc_attr($id); ?> .image-box-fancy-desc .image-box-fancy-title,
        #<?php echo esc_attr($id); ?> .image-box-fancy-desc p{
                                          color: <?php echo esc_attr($image_box_fancy_text_color)?>;
                                      }
    </style>
    <?php
    $align = trim($align);
    ?>
    <div id="<?php echo esc_attr($id); ?>" data-effect="<?php echo esc_attr($image_box_fancy_effect_slider); ?>" data-speed="<?php echo esc_attr($image_box_fancy_speed); ?>" class="img-box-fancy <?php echo esc_attr($animation['has-animation'].' md-align-'.$align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <ul class="slides">
            <?php foreach( $image_pointer as $value )
            {
                $image_url = wp_get_attachment_url($value);
                $image_url_flag = true;
                if ($image_url == false){
                    $image_url = PIXFLOW_PLACEHOLDER1;
                    $image_url_flag = false;
                }
                ?>
                <li>
                    <div class="imgBox-image imgBox-image-main image-<?php echo esc_attr($id).$counter ?>" style="<?php echo "background-image:url('".esc_attr($image_url)."');background-size:" . esc_attr($image_box_fancy_size); ?>"></div>
                </li>
                <?php $counter++; } ?>
        </ul>
        <div class="image-box-fancy-desc image-box-fancy-desc-<?php echo esc_attr($image_box_fancy_style)?>">
            <div class="image-box-fancy-collapse"><i class="px-icon icon-maximize"></i></div>
            <div class="image-box-fancy-container">
                <div class="image-box-fancy-icon"><i class="px-icon <?php echo esc_attr($image_box_fancy_icon)?>"></i></div>
                <div class="image-box-fancy-title"><?php echo esc_attr($image_box_fancy_description_title)?></div>
                <p class="image-box-fancy-text"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($image_box_fancy_description_text)); ?></p>
            </div>
        </div>
    </div> <!-- End image box fancy -->

    <script>
        "use strict";
        $(document).ready(function() {
            if (typeof pixflow_imageBoxFancy == 'function') {
                pixflow_imageBoxFancy("<?php echo esc_attr($id) ?>", "<?php echo esc_attr($image_box_fancy_height_type) == 'manual'?esc_attr($image_box_fancy_height):'fit'; ?>");
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
