<?php
/**
 * Contact Form Shortcode
 *
 * @author Pixflow
 */

/*Contact Form*/
add_shortcode('md_contactform', 'pixflow_get_style_script'); // pixflow_sc_contactform

/*-----------------------------------------------------------------------------------*/
/*  Contact Form
/*-----------------------------------------------------------------------------------*/

function pixflow_sc_contactform( $atts, $content = null ){

    if (!(is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || defined( 'WPCF7_PLUGIN' ))) {

        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">Contact Form 7</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activat %s to use this shortcode. For importing form styles, please read the description in contact form setting panel.','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    $output = '';
    extract( shortcode_atts( array(
        'contactform_id'            =>'' ,
        'contactform_title'         =>'CONTACT FORM',
        'contactform_description'   => 'We are a fairly small, flexible design studio that designs for print and web.',
        'contactform_general_color' => 'rgb(0,0,0)',
        'contactform_field_color'   => 'rgb(255,255,255)',
        'contactform_button_color'  => 'rgb(0,0,0)',
        'contactform_button_hover'  => 'rgba(150,150,150,0.9)',
        'left_right_padding'        => 12,
        'align'        => 'center'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_contactform',$atts);
    $id = pixflow_sc_id('contact-form');

    ob_start(); ?>
    <style scoped>
        .<?php echo esc_attr($id) ?> .form-title,
        .<?php echo esc_attr($id) ?> .input input,
        .<?php echo esc_attr($id) ?> .input textarea {
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 1)); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-description,
        .<?php echo esc_attr($id) ?> .form-input,
        .<?php echo esc_attr($id) ?> .input__label{
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 0.7)); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-input ::-webkit-input-placeholder{
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 0.7)); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-input ::-moz-placeholder{
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 0.7)); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-input :-ms-input-placeholder{
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 0.7)); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-input input,
        .<?php echo esc_attr($id) ?> .form-input textarea,
        .<?php echo esc_attr($id) ?> .input__label--hoshi::before,
        .<?php echo esc_attr($id) ?> .input__label--hoshi::after{
            border-color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba', 0.7)); ?>;
        }

        .<?php echo esc_attr($id) ?>   .form-input input:not(.input__field--hoshi),
        .<?php echo esc_attr($id) ?>  .input input:not(.input__field--hoshi),
        .<?php echo esc_attr($id) ?>   .form-input textarea:not(.input__field--hoshi),
        .<?php echo esc_attr($id) ?>  .input textarea:not(.input__field--hoshi){
            background-color: <?php echo esc_attr($contactform_field_color); ?>;
        }

        .<?php echo esc_attr($id) ?> .form-submit input,
        .<?php echo esc_attr($id) ?> .submit-button{
            background-color: <?php echo esc_attr($contactform_button_color); ?> ;
        }

        .<?php echo esc_attr($id) ?> .form-submit input:hover,
        .<?php echo esc_attr($id) ?> .submit-button:hover{
            background-color: <?php echo esc_attr($contactform_button_hover); ?> ;
        }
        .<?php echo esc_attr($id) ?> .wpcf7-response-output{
            color: <?php echo esc_attr(pixflow_colorConvertor($contactform_general_color,'rgba',0.9)); ?>;
        }
        .<?php echo esc_attr($id) ?> .form-container-classic .form-submit input {
            padding: 0 <?php echo esc_attr((int)$left_right_padding+50);?>px;
        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="contact-form <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align) ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <?php if("" != $contactform_title){ ?>
            <h3 class="form-title"><?php echo esc_attr($contactform_title) ?></h3>
        <?php }
        if("" != $contactform_description){ ?>
            <p class="form-description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($contactform_description)); ?></p>
        <?php }


        if ($contactform_id == ''){
            global $md_allowed_HTML_tags;
            $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
            if ( is_array($cf7) && count($cf7) > 0){
                $index = count($cf7)-1;
                $contactform_id = $cf7[$index]->ID;
                echo do_shortcode('[contact-form-7 id="'.esc_attr($contactform_id).'"]');
            }else{
                $url = admin_url('themes.php?page=install-required-plugins');
                $a='<a href="'.$url.'">Contact Form 7</a>';
                $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('No Contact Form Found; No Form found, make sure you have created a From in %s before using this shortcode. ','massive-dynamic'),$a).'</p></div>';
                echo wp_kses($mis,$md_allowed_HTML_tags);
            }

        }else if($contactform_id && $contactform_id != 0){
            echo do_shortcode('[contact-form-7 id="'.esc_attr($contactform_id).'"]');
        } ?>
    </div>

    <script>
        if(typeof pixflow_contactForm == 'function'){
            pixflow_contactForm();
        }
    </script>

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}