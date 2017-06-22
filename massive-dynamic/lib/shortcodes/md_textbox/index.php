<?php
/**
 * TextBox Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_textbox', 'pixflow_get_style_script'); // pixflow_sc_textbox

function pixflow_sc_textbox( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'textbox_bg_color'          => '#FFF',
        'textbox_icon'              => 'icon-diamond',
        'textbox_icon_color'        => '#01b1ae',
        'textbox_title'             => 'Figure it out',
        'textbox_heading'           => 'h4',
        'textbox_description'       => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable',
        'textbox_content_color'     => '#000'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_textbox',$atts);
    $id = pixflow_sc_id('textbox');

    ob_start(); ?>

    <style scoped>
        <?php echo '.'.esc_attr($id) ?>{
            background-color: <?php echo esc_attr($textbox_bg_color); ?>;
            border-bottom: 3px solid <?php echo esc_attr($textbox_icon_color); ?>;
        }
        <?php echo '.'.esc_attr($id) ?> .icon{
            color: <?php echo esc_attr($textbox_icon_color); ?>;
        }
        <?php echo '.'.esc_attr($id) ?> .title,
                                        <?php echo '.'.esc_attr($id) ?> .description{
                                            color: <?php echo esc_attr($textbox_content_color); ?>;
                                        }
    </style>

<div class="text-in-box <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
    <?php if( isset($textbox_icon) && 'icon-empty' != $textbox_icon ){ ?>
        <div class="icon-holder">
            <div class="icon <?php echo esc_attr($textbox_icon) ?>"></div>
        </div>
    <?php }?>
    <div class=" clearfix"></div>
    <!--End of Icon section-->

    <?php if( isset($textbox_title) && '' != $textbox_title ){ ?>
        <<?php echo esc_attr($textbox_heading) ?> class="title"> <?php echo esc_attr($textbox_title) ?> </<?php echo esc_attr($textbox_heading) ?>>
    <?php } ?>
    <!--End of Title section-->

    <?php if( isset($textbox_description) && '' != $textbox_description ){ ?>
        <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($textbox_description)); ?></p>
        <div class=" clearfix"></div>
    <?php } ?>
    <!--End of Description section-->
    </div>

    <?php pixflow_callAnimation(true); ?>

    <?php
    return ob_get_clean();
}
