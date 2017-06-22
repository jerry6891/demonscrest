<?php
/**
 * Iconbox Top Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_iconbox_top', 'pixflow_get_style_script'); // pixflow_sc_iconbox_top

function pixflow_sc_iconbox_top( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'iconbox_alignment'         => 'center',
        'iconbox_icon'              => 'icon-diamond',
        'iconbox_title'             => 'Figure it out',
        'iconbox_heading'           => 'h1',
        'iconbox_icon_color'        => 'rgb(0,0,0)',
        'iconbox_general_color'     => '#5e5e5e',
        'iconbox_description'       => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
        'iconbox_button'            => 'yes',
        'button_style'              => 'fade-square',
        'iconbox_top_button_color'  => '#5e5e5e',
        'iconbox_button_text_color' => '#fff',
        'button_bg_hover_color'     => '#9b9b9b',
        'button_hover_color'        => '#fff',
        'iconbox_button_text'       => 'Read more',
        'button_icon_class'         => 'icon-snowflake2',
        'iconbox_button_size'       => 'standard',
        'iconbox_button_url'        => '#',
        'iconbox_button_target'     => '_self',
        'left_right_padding'        => '0',
        'align' => 'center'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_iconbox_top',$atts);
    $id = pixflow_sc_id('iconbox-top');

    ob_start(); ?>

    <style scoped>

        <?php if('right' == $iconbox_alignment) { ?>
        <?php echo '.'.esc_attr($id) ?> .iconbox-top-content{
            text-align: right;
        }

        <?php echo '.'.esc_attr($id) ?> .icon-holder,
                                        <?php echo '.'.esc_attr($id) ?>.iconbox-top .description{
                                            float: right;
                                        }

        <?php echo '.'.esc_attr($id) ?> .icon-holder {
                                            margin-right: -25px;
                                        }

        <?php } elseif ('center' == $iconbox_alignment) { ?>

        <?php echo '.'.esc_attr($id) ?> .iconbox-top-content{
                                            text-align: center;
                                        }

        <?php echo '.'.esc_attr($id) ?> .icon-holder,
                                        <?php echo '.'.esc_attr($id) ?>.iconbox-top .description{
                                            margin-right: auto;
                                            margin-left: auto;
                                        }

        <?php } elseif ('left' == $iconbox_alignment) { ?>
        <?php echo '.'.esc_attr($id) ?> .iconbox-top-content{
                                            text-align: left;
                                        }

        <?php echo '.'.esc_attr($id) ?> .icon-holder,
                                        <?php echo '.'.esc_attr($id) ?>.iconbox-top .description{
                                            float: left;
                                        }

        <?php echo '.'.esc_attr($id) ?> .icon-holder {
                                            margin-left: -25px;
                                        }

        <?php } ?>

        <?php echo '.'.esc_attr($id) ?> .icon{
                                            color: <?php echo esc_attr($iconbox_icon_color); ?>;
                                        }

        <?php echo '.'.esc_attr($id) ?> .title{
                                            color: <?php echo esc_attr($iconbox_general_color); ?>;
                                        }

        <?php echo '.'.esc_attr($id) ?> .description{
                                            color: <?php echo esc_attr(pixflow_colorConvertor($iconbox_general_color,'rgba', 0.7)); ?>;
                                        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="iconbox-top <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="iconbox-top-content">
            <div class="hover-holder">
                <?php if( isset($iconbox_icon) && 'icon-empty' != $iconbox_icon ){ ?>
                    <div class="icon-holder">
                        <svg class="svg-circle">
                            <circle cx="49" cy="49" r="47" stroke="<?php echo esc_attr($iconbox_icon_color); ?>" stroke-width="2" fill="none"></circle>
                        </svg>
                        <div class="icon <?php echo esc_attr($iconbox_icon) ?>"></div>
                    </div>
                <?php }?>
                <div class=" clearfix"></div>
                <!--End of Icon section-->

                <?php if( isset($iconbox_title) && '' != $iconbox_title ){ ?>
                <<?php echo esc_attr($iconbox_heading) ?> class="title"> <?php echo esc_attr($iconbox_title) ?> </<?php echo esc_attr($iconbox_heading) ?>>
            <?php } ?>
            <!--End of Title section-->
        </div>

        <?php if( isset($iconbox_description) && '' != $iconbox_description ){ ?>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($iconbox_description)); ?></p>
            <div class=" clearfix"></div>
        <?php } ?>
        <!--End of Description section-->

        <?php
        if( 'yes' == $iconbox_button ){

            echo pixflow_buttonMaker($button_style,$iconbox_button_text,$button_icon_class,$iconbox_button_url,$iconbox_button_target,$iconbox_alignment,$iconbox_button_size,$iconbox_top_button_color,$button_hover_color,$left_right_padding,$iconbox_button_text_color,$button_bg_hover_color);

        }
        ?>
        <!--End of Button section-->
    </div>
    </div>

    <script>

        "use strict";

        var $ = (jQuery);

        if ( typeof pixflow_iconboxTopShortcode == 'function' )
        {
            pixflow_iconboxTopShortcode();
        }
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
