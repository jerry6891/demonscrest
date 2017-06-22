<?php
/**
 * Counter Shortcode
 *
 * @author Pixflow
 */

/*Counter*/
add_shortcode('md_counter', 'pixflow_get_style_script'); // pixflow_sc_counter

/*-----------------------------------------------------------------------------------*/
/*  Counter
/*-----------------------------------------------------------------------------------*/
function pixflow_sc_counter($atts, $content = null)
{
    $counter_from = $counter_to = $counter_title = $counter_title_color =
    $counter_icon_class=$counter_icon_color='';
    extract(shortcode_atts(array(
        'counter_from'         => '0',
        'counter_to'           => '46',
        'counter_title'        => 'Documents',
        'counter_title_color'  => 'rgb(0,0,0)',
        'counter_icon_class'   => 'icon-Diamond',
        'counter_icon_color'   => 'rgb(132,206,27)',
        'align'   => 'center',

    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_counter',$atts);
    $id = pixflow_sc_id('counter');

    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id)?> .icon i{
            color:<?php echo esc_attr($counter_icon_color); ?>;
        }
        .<?php echo esc_attr($id)?> .text .timer,
        .<?php echo esc_attr($id)?> .text h2{
            color:<?php echo esc_attr($counter_title_color); ?>;
        }


    </style>
    <?php
    $align = trim($align);
    ?>
    <div id="id-<?php echo esc_attr($id) ?>" class="<?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?> md-counter" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="counter">
            <div class="icon">
                <i class="<?php echo esc_attr($counter_icon_class); ?>"></i>
            </div>
            <div class="text">
                <div class="timer count-number" id="<?php echo esc_attr($id) ?>" data-to="<?php echo esc_attr($counter_to); ?>" data-from="<?php echo esc_attr($counter_from); ?>" data-speed="1500"></div>
                <h2 class="title"><?php echo esc_attr($counter_title); ?></h2>
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