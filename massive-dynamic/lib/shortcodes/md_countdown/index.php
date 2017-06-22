<?php
/**
 * Countdown Shortcode
 *
 * @author Pixflow
 */

/*Count Down*/
add_shortcode('md_countdown', 'pixflow_get_style_script'); // pixflow_sc_countdown

/*-----------------------------------------------------------------------------------*/
/*  Count Down
/*-----------------------------------------------------------------------------------*/

function pixflow_sc_countdown($atts, $content = null)
{
    extract(shortcode_atts(array(
        'count_down'               => '2020/10/9 20:30',
        'count_down_general_color' => '#727272',
        'count_down_sep_color'     => '#96df5c'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_countdown',$atts);
    $id = pixflow_sc_id('countdown');

    // explode date time

    $date = date('Y-m-d', strtotime($count_down));
    $time = date('H:i', strtotime($count_down));

    $date = explode("-",$date);
    $time = explode(":",$time);

    $year  = $date[0];
    $month = $date[1];
    $day   = $date[2];

    $hour = $time[0];
    $min  = $time[1];

    ob_start();

    ?>

    <style scoped>

        .<?php echo esc_attr($id)?>.count-down {
            color: <?php echo esc_attr($count_down_general_color); ?>;
        }

        .<?php echo esc_attr($id)?>.count-down hr {
            background-color: <?php echo esc_attr($count_down_sep_color); ?>;
        }


        .<?php echo esc_attr($id)?>.count-down #date-time .content{
            font-family:"<?php echo esc_attr(pixflow_get_theme_mod('h3_name', PIXFLOW_H3_NAME)); ?>";
        }

    </style>


    <div id="<?php echo esc_attr($id) ?>" class="count-down clearfix <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div id="date-time"> <?php echo esc_attr($date[0]); ?> </div>
    </div>

    <script type="text/javascript">

        jQuery(function() {
            if (typeof pixflow_countdown == 'function') {
                pixflow_countdown("<?php echo esc_attr($year); ?>", "<?php echo esc_attr($month); ?>", "<?php echo esc_attr($day); ?>", "<?php echo esc_attr($hour); ?>", "<?php echo esc_attr($min); ?>");
            }
        });

    </script>

    <?php
    return ob_get_clean();
}