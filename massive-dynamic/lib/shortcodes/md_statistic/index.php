<?php
/**
 * Statistic Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_statistic', 'pixflow_get_style_script'); // pixflow_sc_statistic

function pixflow_sc_statistic($atts, $content = null)
{
    extract(shortcode_atts(array(
        'statistic_to'              => '80',
        'statistic_symbol'          => '%',
        'statistic_title'           => 'Complete Projects',
        'statistic_general_color'   => 'rgb(0,0,0)',
        'statistic_symbol_color'    => 'rgb(150,223,92)',
        'statistic_separatoe'       => 'yes',
        'align'                     =>'left',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_statistic',$atts);
    $id = pixflow_sc_id('md_statistic');

    ob_start();

    ?>

    <style scoped>
        <?php
        if($statistic_separatoe=='yes'){
            echo '.'.esc_attr($id); ?> .timer-holder,
        <?php echo '.'.esc_attr($id); ?> .statistic-text
        {
            border-right: 1px solid #b3b3b3;
        }
        <?php }?>
        .<?php echo esc_attr($id)?> .timer,
        .<?php echo esc_attr($id)?> .statistic-text h2{
            color:<?php echo esc_attr($statistic_general_color); ?>;
        }



        .<?php echo esc_attr($id)?> .statistic-symbol{
            color:<?php echo esc_attr($statistic_symbol_color); ?>;
        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div id="id-<?php echo esc_attr($id) ?>" class="clearfix <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-' . $align); ?> md-counter md-statistic" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="counter">
            <div class="timer-holder">
                <div class="timer count-number" id="<?php echo esc_attr($id) ?>" data-to="<?php echo esc_attr((int)$statistic_to); ?>" data-from="0" data-speed="1500"></div>
                <?php if($statistic_symbol != ''){ ?>
                    <div class="statistic-symbol">
                        <?php echo esc_attr($statistic_symbol); ?>
                    </div>
                <?php } ?>
            </div>

            <?php if($statistic_title != ''){ ?>
                <div class="statistic-text">
                    <h2 class="title"><?php echo esc_attr($statistic_title); ?></h2>
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