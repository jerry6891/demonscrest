<?php
/**
 * Count Box Shortcode
 *
 * @author Pixflow
 */

/*Count Box*/
add_shortcode('md_countbox', 'pixflow_get_style_script'); // pixflow_sc_countbox

/*-----------------------------------------------------------------------------------*/
/*  Count Box
/*-----------------------------------------------------------------------------------*/
function pixflow_sc_countbox($atts, $content = null)
{
    $countbox_to = $countbox_title = $countbox_desc = $countbox_general_color =
    $countbox_number_color=$counter_icon_color=
    $countbox_use_button=$countbox_button_style=$countbox_button_text=
    $countbox_button_icon_class=$countbox_button_color=$countbox_button_text_color=
    $countbox_button_bg_hover_color=$countbox_button_hover_color=$countbox_button_size=
    $left_right_padding=$countbox_button_url=$countbox_button_target='';

    extract(shortcode_atts(array(
        'countbox_to'         => '46',
        'countbox_title'      => 'YEARS OF MY EXPERIENCE',
        'countbox_desc'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo.',
        'countbox_general_color'  => 'rgb(0,0,0)',
        'countbox_number_color'   => 'rgb(255,54,116)',
        'countbox_use_button'           => 'yes',
        'countbox_button_style'         => 'come-in',
        'countbox_button_text'          => 'READ MORE',
        'countbox_button_icon_class'    => 'icon-chevron-right',
        'countbox_button_color'         => 'rgba(0,0,0,1)',
        'countbox_button_text_color'    => 'rgba(255,255,255,1)',
        'countbox_button_bg_hover_color'=> 'rgb(0,0,0)',
        'countbox_button_hover_color'   => 'rgb(255,255,255)',
        'countbox_button_size'          => 'standard',
        'left_right_padding'           => 0,
        'countbox_button_url'           => '#',
        'countbox_button_target'        => '_self',
        'align'        => 'center'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_countbox',$atts);
    $id = pixflow_sc_id('countbox');

    ob_start();
    $titleClass = ($countbox_desc == '')?'countbox-nodesc':'';
    ?>

    <style scoped>

        .<?php echo esc_attr($id)?> .timer{
            color:<?php echo esc_attr($countbox_number_color); ?>;
        }

        .<?php echo esc_attr($id)?> h2,
        .<?php echo esc_attr($id)?> .countbox-title-separator,
        .<?php echo esc_attr($id)?> p{
            color:<?php echo esc_attr($countbox_general_color); ?>;
        }

        .<?php echo esc_attr($id)?> .countbox-title-separator{
            border-color:<?php echo esc_attr($countbox_general_color); ?>;
        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div id="id-<?php echo esc_attr($id) ?>" class="clearfix <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?> md-counter md-countbox" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="counter">
            <div class="timer count-number" id="<?php echo esc_attr($id) ?>" data-to="<?php echo esc_attr((int)$countbox_to); ?>" data-from="0" data-speed="1500"></div>
            <div class="countbox-text">
                <?php if($countbox_title != ''){ ?>
                    <h2 class="title <?php echo esc_attr($titleClass); ?>"><?php echo esc_attr($countbox_title); ?></h2>

                <?php }if($countbox_desc != ''){ ?>
                    <div class="countbox-title-separator"></div>
                    <p><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($countbox_desc)); ?></p>
                <?php }
                if($countbox_use_button=='yes'){?>
                    <div class="countbox-button">
                        <?php echo pixflow_buttonMaker($countbox_button_style,$countbox_button_text,$countbox_button_icon_class,$countbox_button_url,$countbox_button_target,'left',$countbox_button_size,$countbox_button_color,$countbox_button_hover_color,$left_right_padding,$countbox_button_text_color,$countbox_button_bg_hover_color); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        var $ = jQuery;
        if ( typeof pixflow_counterShortcode == 'function' ){
            pixflow_counterShortcode( "#id-<?php echo esc_attr($id) ?>", false );
        }
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}