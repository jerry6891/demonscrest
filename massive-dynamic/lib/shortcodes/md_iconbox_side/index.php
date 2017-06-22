<?php
/**
 * Icon Box Side Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_iconbox_side', 'pixflow_get_style_script'); // pixflow_sc_iconbox_side

function pixflow_sc_iconbox_side( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'iconbox_alignment'         => 'left',
        'iconbox_icon'              => 'icon-location',
        'iconbox_icon_background'   => 'yes',
        'iconbox_title'             => 'Figure it out',
        'iconbox_heading'           => 'H3',
        'iconbox_icon_color'        => '#5e5e5e',
        'iconbox_icon_hover_color'  => '#FFF',
        'iconbox_general_color'     => '#5e5e5e',
        'iconbox_description'       => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable',
        'iconbox_button'            => 'yes',
        'button_style'              => 'fade-square',
        'iconbox_side_button_color' => '#5e5e5e',
        'button_hover_color'        => '#fff',
        'button_text_color'         => '#fff',
        'button_bg_hover_color'     => '#9b9b9b',
        'iconbox_button_text'       => 'Read more',
        'button_icon_class'         => 'icon-snowflake2',
        'iconbox_button_size'       => 'standard',
        'iconbox_button_url'        => '#',
        'iconbox_button_target'     => '_self',
        'left_right_padding'        => '0'
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_iconbox_side',$atts);
    $id = pixflow_sc_id('iconbox-side');

    ob_start(); ?>
    <style scoped>
        <?php echo '.'.esc_attr($id) ?> .icon{
            color: <?php echo esc_attr($iconbox_icon_color); ?>;
        }

        <?php if ( $iconbox_icon_background == 'yes'){

            echo '.'.esc_attr($id) ?> .icon-container .icon:after{
                                                  border:1px solid  <?php echo esc_attr($iconbox_icon_color); ?>;
                                              }

        <?php echo '.'.esc_attr($id) ?>.iconbox-side:hover .icon{
            box-shadow: 0 0 0 2px <?php echo esc_attr($iconbox_icon_color); ?>;;
            background: <?php echo esc_attr($iconbox_icon_color); ?>;
            color: <?php echo esc_attr($iconbox_icon_hover_color); ?>;
        }
        <?php
        } echo '.'.esc_attr($id) ?> .title{
                                                color: <?php echo esc_attr($iconbox_general_color); ?>;
                                            }

        <?php echo '.'.esc_attr($id) ?> .description{
                                            color: <?php echo esc_attr($iconbox_general_color); ?>;
                                            opacity: 0.7;
                                        }

    </style>

    <?php   $sideClass = '';
    if('right' == $iconbox_alignment) {
        $sideClass .= ' right-align';
    } else if('left' == $iconbox_alignment) {
        $sideClass .= ' left-align';
    }?>

    <div class="iconbox-side clearfix <?php echo esc_attr($id.' '.$animation['has-animation']); echo esc_attr($sideClass); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <?php if( isset($iconbox_icon) && 'icon-empty' != $iconbox_icon ){ ?>
            <div class="icon-container <?php if ( $iconbox_icon_background == 'yes') echo 'icon-background'; ?>">
                <div class="icon <?php echo esc_attr($iconbox_icon) ?>"></div>
            </div>
        <?php }?>
        <!--End of Icon section-->

        <div class="iconbox-side-container " >
            <?php if( isset($iconbox_title) && '' != $iconbox_title ){ ?>
            <<?php echo esc_attr($iconbox_heading) ?> class="title"> <?php echo esc_attr($iconbox_title) ?> </<?php echo esc_attr($iconbox_heading) ?>>
    <?php } ?>
        <!--End of Title section-->

        <?php if( isset($iconbox_description) && '' != $iconbox_description ){ ?>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($iconbox_description)); ?></p>
        <?php } ?>
        <!--End of Description section-->

        <?php
        if( 'yes' == $iconbox_button ){
            echo pixflow_buttonMaker($button_style,$iconbox_button_text,$button_icon_class,$iconbox_button_url,$iconbox_button_target,$iconbox_alignment,$iconbox_button_size,$iconbox_side_button_color,$button_hover_color,$left_right_padding,$button_text_color,$button_bg_hover_color);
        }
        ?>
        <!--End of Button section-->
    </div>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
