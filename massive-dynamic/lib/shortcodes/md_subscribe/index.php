<?php
/**
 * Subscribe Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_subscribe', 'pixflow_get_style_script'); // pixflow_sc_subscribe

function pixflow_sc_subscribe( $atts, $content = null ){
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
    $output = '';
    extract( shortcode_atts( array(
        'subscribe_bgcolor' =>'#ebebeb',
        'subscribe_input_radius' =>'35',
        'subscribe_title'  =>'SUBSCRIBE',
        'subscribe_sub_title'  => 'Sign up to receive news and updates',
        'subscribe_textcolor' =>'#000',
        'subscribe_input_style' => 'fill',
        'subscribe_input_skin' => 'light',
        'subscribe_input_opacity' => '100',
        'subscribe_button_bgcolor'=>'#c7b299',
        'subscribe_button_textcolor'=>'#FFF',
        'subscribe_successcolor'=>'rgba(116, 195, 116,1)',
        'subscribe_errorcolor'=>'#FF6A6A',

    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_subscribe',$atts);
    if(strpos($subscribe_textcolor, 'rgb(') !== false){
        $subscribe_textcolor = pixflow_colorConvertor($subscribe_textcolor,'rgba',1);
    }

    $id = pixflow_sc_id('subscribe');
    $skinColor = ('dark' == $subscribe_input_skin)?'#000':'#FFF';
    $skinTextColor = ('dark' == $subscribe_input_skin)?'#FFF':'#000';

    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id)?>{
            background: <?php echo esc_attr($subscribe_bgcolor);?>;
            color: <?php echo esc_attr($subscribe_textcolor)?>;
        }
        .<?php echo esc_attr($id)?> .subscribe-title{
            color: <?php echo esc_attr($subscribe_textcolor)?>;
        }
        .<?php echo esc_attr($id)?> .subscribe-textbox,
        .<?php echo esc_attr($id)?> .subscribe-button{
            border-radius: <?php echo esc_attr($subscribe_input_radius)?>px;
        }
        .<?php echo esc_attr($id)?> .subscribe-textbox{
            border: 2px solid;
            color: <?php echo pixflow_colorConvertor($skinTextColor,'rgba',1)?>;
        }
        <?php if('fill' == $subscribe_input_style){ ?>
        .<?php echo esc_attr($id)?> .subscribe-textbox{
            background-color: <?php echo pixflow_colorConvertor($skinColor,'rgba',(int)$subscribe_input_opacity * 0.01)?>;
            border-color: <?php echo pixflow_colorConvertor($skinColor,'rgba',0)?>;
        }
        <?php }else{ ?>
        .<?php echo esc_attr($id)?> .subscribe-textbox{
            border-color: <?php echo pixflow_colorConvertor($skinColor,'rgba',(int)$subscribe_input_opacity * 0.01)?>;
        }
        <?php } ?>

        .<?php echo esc_attr($id) ?> .subscribe-textbox::-webkit-input-placeholder {
            color: <?php echo pixflow_colorConvertor($skinTextColor,'rgba',0.6)?>;
        }
        .<?php echo esc_attr($id) ?> .subscribe-textbox:-moz-placeholder { /* Firefox 18- */
            color: <?php echo pixflow_colorConvertor($skinTextColor,'rgba',0.6)?>;
        }
        .<?php echo esc_attr($id) ?> .subscribe-textbox::-moz-placeholder {  /* Firefox 19+ */
            color: <?php echo pixflow_colorConvertor($skinTextColor,'rgba',0.6)?>;
        }
        .<?php echo esc_attr($id) ?> .subscribe-textbox:-ms-input-placeholder {
            color: <?php echo pixflow_colorConvertor($skinTextColor,'rgba',0.6)?>;
        }

        .<?php echo esc_attr($id) ?> .subscribe-button{
            background: <?php echo esc_attr($subscribe_button_bgcolor);?>;
            color: <?php echo esc_attr($subscribe_button_textcolor);?>;
        }
        .<?php echo esc_attr($id) ?> .subscribe-button:hover{
            background: <?php echo esc_attr($subscribe_button_textcolor);?>;
            color: <?php echo esc_attr($subscribe_button_bgcolor);?>;
        }

        <?php if(empty($subscribe_title) && empty($subscribe_sub_title)){ ?>
        .<?php echo esc_attr($id) ?>{
            padding-top: 0;
        }
        <?php } ?>

    </style>

    <div class="sc-subscribe <?php echo esc_attr($id.' '.$animation['has-animation']) ?> clearfix" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php
        echo do_shortcode('[mc4wp_form id="'.$mailChimp[0]->ID.'"]');
        ?>
        <form class="send">
            <?php if(!empty($subscribe_title)){ ?>
                <h2 class="subscribe-title"><?php echo esc_attr($subscribe_title);?></h2>
            <?php } ?>
            <?php if(!empty($subscribe_sub_title)){ ?>
                <div class="subscribe-sub-title"><?php echo esc_attr($subscribe_sub_title);?></div>
            <?php } ?>
            <div><input type="email" name="mail" placeholder="<?php _e('ENTER YOUR E-MAIL ADDRESS','massive-dynamic') ?>" class="subscribe-textbox"></div>
            <div class="subscribe-err"></div>
            <div><button class="subscribe-button"><?php _e('SUBSCRIBE','massive-dynamic') ?>&nbsp;&nbsp;&nbsp;<i class="button-icon icon-angle-right"></i></button></div>
            <input type="hidden" class="errorcolor" value="<?php echo esc_attr($subscribe_errorcolor) ?>">
            <input type="hidden" class="successcolor" value="<?php echo esc_attr($subscribe_successcolor) ?>">
        </form>
    </div>

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
