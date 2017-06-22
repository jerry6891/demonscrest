<?php
/**
 * Pie Chart Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_pie_chart', 'pixflow_get_style_script'); // pixflow_sc_pie_chart

function pixflow_sc_pie_chart( $atts, $content = null ){

    $output = $pie_chart_title = $pie_chart_percent = $pie_chart_percent_color=$pie_chart_text_color= '';
    extract( shortcode_atts( array(
        'pie_chart_title'         =>'Animation' ,
        'pie_chart_percent'       => '70',
        'pie_chart_percent_color' => 'rgb(34,188,168)',
        'pie_chart_text_color'    => 'rgb(0,0,0)',
        'align'    => 'center'
    ),$atts ));

    $id = pixflow_sc_id('pie_chart');
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_pie_chart',$atts);
    ob_start();

    ?>
    <style scoped>
        .<?php echo esc_attr($id); ?> .label,
        .<?php echo esc_attr($id); ?> .percentage{
            color:<?php echo esc_attr($pie_chart_text_color); ?>;
        }
    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="<?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?> md-pie-chart type-1" id="<?php echo esc_attr($id); ?>" data-barColor="<?php echo esc_attr(pixflow_rgb2hex($pie_chart_percent_color)); ?>" data-trackColor="<?php echo esc_attr(str_replace(';','',pixflow_colorConvertor($pie_chart_percent_color,'rgba','0.2'))); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <div class="chart">
            <div class="percentage" data-percent="<?php echo esc_attr($pie_chart_percent); ?>">
                <span><?php echo esc_attr($pie_chart_percent); ?></span><i>%</i>
            </div>
            <div class="label"><?php echo esc_attr($pie_chart_title); ?></div>
        </div>
    </div>
    <script>
        var $ = jQuery;

        $(function(){
            if ( typeof pixflow_pieChart == 'function' ){
                pixflow_pieChart($('#<?php echo esc_attr($id); ?>'),'<?php echo esc_attr(pixflow_rgb2hex($pie_chart_percent_color)); ?>','<?php echo esc_attr(str_replace(';','',pixflow_colorConvertor($pie_chart_percent_color,'rgba','0.2'))); ?>');
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}