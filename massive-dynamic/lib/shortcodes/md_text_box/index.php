<?php
/**
 * Text Box Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_text_box', 'pixflow_get_style_script'); // pixflow_sc_text_box

function pixflow_sc_text_box( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'textbox_title'                   => 'Tags & Models',
        'textbox_text'                    => 'It is a long established fact that a reader will be dis It is a long',
        'textbox_icon'                    => 'icon-PriceTag',
        'textbox_text_color'              => 'rgb(80,80,80)',
        'textbox_text_hover_color'        => 'rgb(255,255,255)',
        'textbox_background_color'        => 'rgb(230,231,237)',
        'textbox_background_hover_color'  => 'rgb(255,0,84)'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_text_box',$atts);
    $id = pixflow_sc_id('text_box');

    ob_start(); ?>

    <style scoped>
        <?php echo '.'.esc_attr($id); ?>{
            background-color: <?php echo esc_attr($textbox_background_color); ?>;
        }

        <?php echo '.'.esc_attr($id); ?>:hover{
            background-color: <?php echo esc_attr($textbox_background_hover_color); ?>;
        }

        <?php echo '.'.esc_attr($id); ?> .text-box-title,
                                         <?php echo '.'.esc_attr($id); ?> .text-box-icon,
                                         <?php echo '.'.esc_attr($id); ?> .text-box-description{
                                             color: <?php echo esc_attr($textbox_text_color); ?>;
                                         }

        <?php echo '.'.esc_attr($id); ?>:hover .text-box-title,
        <?php echo '.'.esc_attr($id); ?>:hover .text-box-icon,
        <?php echo '.'.esc_attr($id); ?>:hover .text-box-description{
            color: <?php echo esc_attr($textbox_text_hover_color); ?>;
        }

    </style>

    <div class="text-box <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="text-box-content">
            <div class="text-box-icon-holder">
                <?php if( isset($textbox_icon) && 'icon-empty' != $textbox_icon ){ ?>
                    <div class="text-box-icon <?php echo esc_attr($textbox_icon) ?>"></div>
                <?php }?>
            </div>
            <div class="clearfix"></div>
            <!--End of Icon section-->

            <?php if( isset($textbox_title) && '' != $textbox_title ){ ?>
                <h3 class="text-box-title"> <?php echo mb_substr(esc_attr($textbox_title),0,20); ?> </h3>
            <?php } ?>
            <!--End of Title section-->
        </div>
        <?php if( isset($textbox_text) && '' != $textbox_text ){ ?>
            <p class="text-box-description"><?php echo mb_substr(preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($textbox_text)),0,80); ?></p>
            <div class="clearfix"></div>
        <?php } ?>
        <!--End of Description section-->
    </div>

    <script type="text/javascript">
        var $ = jQuery;
        if(typeof pixflow_textBox == 'function'){
            pixflow_textBox();
        }

        <?php pixflow_callAnimation(); ?>

    </script>

    <?php
    return ob_get_clean();
}
