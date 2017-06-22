<?php
/**
 * Modern Subscribe Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_modern_subscribe', 'pixflow_get_style_script'); // pixflow_sc_modern_subscribe
function pixflow_sc_modern_subscribe( $atts, $content = null ){
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
        'subscribe_bgcolor'    =>'#fff',
        'subscribe_title'      =>'Sign Up To Our Newsletter',
        'subscribe_desc'       => 'To get the latest news from us please subscribe your email.we promise worthy news with no spam.',
        'subscribe_shadow'  =>'yes',
        'subscribe_textcolor'  =>'#000',
        'subscribe_image'      =>   PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",

    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_modern_subscribe',$atts);
    if(strpos($subscribe_textcolor, 'rgb(') !== false){
        $subscribe_textcolor = pixflow_colorConvertor($subscribe_textcolor,'rgba',1);
    }

    $id = pixflow_sc_id('modern-subscribe');

    if(is_numeric($subscribe_image)){
        $subscribe_image =  wp_get_attachment_image_src( $subscribe_image, 'pixflow_subscribe-modern') ;
        $subscribe_image = (false == $subscribe_image)?PIXFLOW_PLACEHOLDER1:$subscribe_image[0];
    }
    $subscribeShadow = ('yes' == $subscribe_shadow)?'shadow':'';
    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id);?>{
            background-color:<?php echo esc_attr($subscribe_bgcolor);?>;
        }
        .<?php echo esc_attr($id);?> .modern-subscribe-title{
            color: <?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.9)); ?> ;
        }
        .<?php echo esc_attr($id);?> .modern-subscribe-desc{
            color: <?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.7)); ?> ;
        }
        .<?php echo esc_attr($id);?> .subscribe-image{
            background-image:url(<?php echo esc_attr($subscribe_image); ?>);
        }
        .<?php echo esc_attr($id);?> .modern-subscribe-button{
            color: <?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.4)); ?> ;
        }

        .<?php echo esc_attr($id);?> .modern-subscribe-button:hover{
            color: <?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',1)); ?> ;
        }

        .<?php echo esc_attr($id);?> .modern-subscribe-textbox{
            border-color:<?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.7)); ?> ;
            color: <?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.5)); ?> ;
        }

        .<?php echo esc_attr($id);?> .send ::-webkit-input-placeholder{
            /* WebKit browsers */
            color:<?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.5)); ?> ;
        }

        .<?php echo esc_attr($id);?> .send :-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            color:<?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.5)); ?> ;
            opacity: 1;
        }

        .<?php echo esc_attr($id);?> .send :-moz-placeholder {
            /* Mozilla Firefox 19+ */
            color:<?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.5)); ?> ;
            opacity: 1;
        }


        .<?php echo esc_attr($id);?> .send :-ms-input-placeholder {
            /* Internet Explorer 10+ */
            color:<?php echo esc_attr(pixflow_colorConvertor($subscribe_textcolor,'rgba',0.5)); ?> ;
        }
    </style>

    <div class="modern-subscribe <?php echo esc_attr($id.' '.$animation['has-animation'].' '.$subscribeShadow) ?> clearfix" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php
        echo do_shortcode('[mc4wp_form id="'.$mailChimp[0]->ID.'"]');
        ?>
        <div class="subscribe-content">
            <?php if(!empty($subscribe_title)){ ?>
                <h2 class="modern-subscribe-title"><?php echo esc_attr($subscribe_title);?></h2>
            <?php } ?>
            <?php if(!empty($subscribe_desc)){ ?>
                <div class="modern-subscribe-desc"><?php echo esc_attr($subscribe_desc);?></div>
            <?php } ?>

            <form class="send">
                <input type="text" name="name" placeholder="Name" class="modern-subscribe-textbox name-input">
                <br/>
                <input type="email" name="mail" placeholder="Email Address" class="modern-subscribe-textbox email-input left">
                <button class="modern-subscribe-button left px-icon icon-arrow-right2"></button>
                <input type="hidden" class="errorcolor">
                <input type="hidden" class="successcolor">
                <div class="subscribe-err"></div>
            </form>

        </div>
        <div class="subscribe-image"></div>

    </div>

    <script type="text/javascript">
        var $ = jQuery;
        if(typeof pixflow_modernSubscribe == 'function'){
            pixflow_modernSubscribe();
        }
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}
