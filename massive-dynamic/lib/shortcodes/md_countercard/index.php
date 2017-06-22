<?php
/**
 * Counter Card Shortcode
 *
 * @author Pixflow
 */

/*Counter Card*/
add_shortcode('md_countercard', 'pixflow_get_style_script'); // pixflow_sc_countercard

/*-----------------------------------------------------------------------------------*/
/*  Counter Card
/*-----------------------------------------------------------------------------------*/
function pixflow_sc_countercard($atts, $content = null)
{
    extract(shortcode_atts(array(
        'counter_to'         => '560',
        'counter_title'      => 'Complete Projects',
        'coutner_icon_class' => 'icon-share3',
        'counter_bg_color'   => 'rgb(255,255,255)',
        'counter_text_color' => 'rgb(26,51,86)',
        'counter_icon_color' => 'rgb(150,223,92)',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_countercard',$atts);
    $id = pixflow_sc_id('countercard');

    ob_start();

    ?>

    <style scoped>
        .<?php echo esc_attr($id)?>{
            background-color: <?php echo esc_attr($counter_bg_color); ?>;
        }

        .<?php echo esc_attr($id)?> .timer,
        .<?php echo esc_attr($id)?> .counter-text h2{
            color:<?php echo esc_attr($counter_text_color); ?>;
        }



        .<?php echo esc_attr($id)?> .counter-icon i{
            color:<?php echo esc_attr($counter_icon_color); ?>;
        }

    </style>

    <div id="id-<?php echo esc_attr($id) ?>" class="clearfix <?php echo esc_attr($id.' '.$animation['has-animation']); ?> md-counter md-counter-card" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="counter">
            <?php if($coutner_icon_class != ''){ ?>
                <div class="counter-icon">
                    <i class="px-icon <?php echo esc_attr($coutner_icon_class); ?>"></i>
                </div>
            <?php } ?>
            <div class="timer count-number" id="<?php echo esc_attr($id) ?>" data-to="<?php echo esc_attr((int)$counter_to); ?>" data-from="0" data-speed="1500"></div>

            <?php if($counter_title != ''){ ?>
                <div class="counter-text">
                    <h2 class="title"><?php echo esc_attr($counter_title); ?></h2>
                </div>
            <?php } ?>
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