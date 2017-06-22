<?php
/**
 * Quote Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_quote', 'pixflow_get_style_script'); // pixflow_sc_quote

function pixflow_sc_quote( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'quote_title'             => 'Your Name',
        'quote_job_title'         => 'Your Job',
        'quote_description'       => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, sunt explicabo. Nemo enim ipsam voluptatem quia',
        'quote_background_image'  => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'quote_text_color'        => 'rgb(24,24,24)',
        'quote_background_color'  => 'rgb(243,243,243)',
        'quote_icon_color'        => 'rgb(150,223,92)'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_quote',$atts);
    $id = pixflow_sc_id('md_quote');

    if(is_numeric($quote_background_image)){
        $quote_background_image =  wp_get_attachment_image_src( $quote_background_image, 'pixflow_quote-thumb') ;
        $quote_background_image = (false == $quote_background_image)?PIXFLOW_PLACEHOLDER1:$quote_background_image[0];
    }

    ob_start();
    ?>

    <style scoped>

        .<?php echo esc_attr($id); ?>.sc-quote .message i {
            color: <?php echo esc_attr($quote_icon_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-quote .message {
            background-color: <?php echo esc_attr($quote_background_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-quote .message:after{
            border-top-color: <?php echo esc_attr($quote_background_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-quote .message p,
        .<?php echo esc_attr($id); ?>.sc-quote .main .titles h4 {
            color: <?php echo esc_attr( pixflow_colorConvertor( $quote_text_color, 'rgba', .6 ) )?>;
        }

        .<?php echo esc_attr($id); ?>.sc-quote .main .titles h3 {
            color: <?php echo esc_attr($quote_text_color)?>;
        }

    </style>

    <div class="sc-quote clearfix <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="message">
            <p> <?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($quote_description)); ?> </p>
            <i class="icon icon-quote4"></i>
        </div>

        <div class="main">
            <img class="quote-image" alt="Image Caption" src="<?php echo esc_url($quote_background_image) ?>">
            <div class="titles">
                <h3> <?php echo esc_attr($quote_title); ?> </h3>
                <h4> <?php echo esc_attr($quote_job_title); ?> </h4>
            </div>
        </div>

    </div> <!-- Quote ends -->

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
