<?php
/**
 * Subscribe Business Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_subscribe_business', 'pixflow_get_style_script'); // pixflow_sc_business_subscribe

function pixflow_sc_subscribe_business( $atts, $content = null ){
    if ( !shortcode_exists( 'mc4wp_form' ) ) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">MailChimp for WordPress Lite</a>';
        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate "%s" to use this shortcode.','massive-dynamic'),$a).'</p></div>';
        return $mis;
    }
    $mailChimp = get_posts( 'post_type="mc4wp-form"&numberposts=1' );

    if ( empty($mailChimp)){
        $url = admin_url('/admin.php?page=mailchimp-for-wp-forms&view=add-form');
        $a='<a href="'.$url.'">MailChimp for WordPress Lite</a>';
        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please create a form in " %s" plugin before using this shortcode. ','massive-dynamic'),$a).'</p></div>';
        return $mis;
    }
    extract( shortcode_atts( array(
        'general_color'     =>'rgb(35,58,91)',
        'button_icon_class' =>'icon-Mail',
        'button_text_color' => 'rgb(255,255,255)',
        'align'     => 'center',

    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_business_subscribe',$atts);


    $id = pixflow_sc_id('business-subscribe');

    ob_start();
    ?>
    <style scoped>

        .<?php echo esc_attr($id);?> .business-subscribe-email-input{
            border:1px solid <?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.8);?> ;
            color:<?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.7);?> ;
        }
        .<?php echo esc_attr($id);?> input::-webkit-input-placeholder{
            /* WebKit browsers */
            color:<?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.7);?> ;
        }

        .<?php echo esc_attr($id);?> .input:-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            color:<?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.7);?> ;
        }

        .<?php echo esc_attr($id);?> input:-moz-placeholder {
            /* Mozilla Firefox 19+ */
            color:<?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.7);?> ;
        }


        .<?php echo esc_attr($id);?> input:-ms-input-placeholder {
            /* Internet Explorer 10+ */
            color:<?php echo pixflow_colorConvertor(esc_attr($general_color),'rgba',.7);?> ;
        }

        .<?php echo esc_attr($id);?> .business-subscribe-button{
            background-color:<?php echo esc_attr($general_color);?> ;
            color : <?php echo esc_attr($button_text_color); ?>
        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="business-subscribe <?php echo esc_attr($id.' '.$animation['has-animation'] . ' md-align-' . $align) ?> clearfix" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php
        echo do_shortcode('[mc4wp_form id="'.$mailChimp[0]->ID.'"]');
        ?>
        <div class="subscribe-content">
            <form class="send">

                <input type="email" name="mail" placeholder="Email Address" class="subscribe-textbox email-input business-subscribe-email-input">
                <button class="business-subscribe-button">
                    Subscribe
                    <?php if($button_icon_class != ''){?>
                        <i  class="px-icon <?php echo esc_attr($button_icon_class); ?>"></i>
                    <?php } ?>
                </button>
                <input type="hidden" class="errorcolor">
                <input type="hidden" class="successcolor">
                <div class="subscribe-err"></div>
            </form>
        </div>
    </div>

    <?php
    return ob_get_clean();
}
