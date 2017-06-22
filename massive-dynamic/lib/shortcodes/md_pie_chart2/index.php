<?php
/**
 * Pie chart2 Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_pie_chart2', 'pixflow_get_style_script'); // pixflow_sc_pie_chart_2

function pixflow_sc_pie_chart2( $atts, $content = null ){

    $output = $pie_chart_title = $pie_chart_percent = $pie_chart_percent_color=$pie_chart_text_color= '';
    extract( shortcode_atts( array(
        'pie_chart2_title'         =>'Animation' ,
        'pie_chart2_percent'       => '70',
        'pie_chart2_percent_color' => 'rgb(34,188,168)',
        'pie_chart2_text_color'    => 'rgb(0,0,0)',
        'pie_chart2_icon'=>'icon-cog',
        'pie_chart2_animation'=>'easeInOutQuad',
        'pie_chart_2_show_type'=>'no',
        'pie_chart_2_animation_delay'=>'',
        'pie_chart_2_line_width'=>'9',
        'pie_chart2_bottom_title'=>'',
        'align'    => 'center'
    ),$atts ));

    $id = pixflow_sc_id('pie_chart2');
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_pie_chart2',$atts);
    if (''==$pie_chart2_title && 'yes'!=$pie_chart_2_show_type)
    {
        $pie_chart2_class="without-title";
    }else {
        $pie_chart2_class="";
    }
    ob_start();

    ?>
    <style scoped>
        .<?php echo esc_attr($id); ?> .label,
        .<?php echo esc_attr($id); ?> .percentage,
        .<?php echo esc_attr($id); ?> .md_pieChart2_title{
            color:<?php echo esc_attr($pie_chart2_text_color); ?>;
        }
    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="<?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?> md-pie-chart type-2 <?php echo esc_attr($pie_chart2_class) ?>" id="<?php echo esc_attr($id); ?>" data-line-width="<?php echo esc_attr($pie_chart_2_line_width); ?>" data-animation-delay="<?php echo esc_attr($pie_chart_2_animation_delay); ?>" data-show-type="<?php echo esc_attr($pie_chart_2_show_type); ?>" data-barColor="<?php echo esc_attr(pixflow_rgb2hex($pie_chart2_percent_color)); ?>" data-animation-type="<?php echo esc_attr($pie_chart2_animation) ?>"    <?php echo esc_attr($animation['animation-attrs']); ?> data-trackColor="<?php echo esc_attr(str_replace(';','',pixflow_colorConvertor($pie_chart2_percent_color,'rgba','0.2'))); ?>"   >

        <div class="chart">
            <div class="percentage" data-percent="<?php echo esc_attr($pie_chart2_percent); ?>" data-title="<?php echo esc_attr($pie_chart2_title); ?>">
                <span class="icon <?php echo esc_attr($pie_chart2_icon); ?>"><p class="md_pieChart2_title"><?php echo esc_attr($pie_chart2_title); ?></p></span>
                <p class="pie_chart2_bottom_title"><?php echo esc_attr($pie_chart2_bottom_title) ?></p>
            </div>

        </div>
    </div>
    <script>
        var $ = jQuery;

        $(function(){
            if ( typeof pixflow_pieChart == 'function' ){
                pixflow_pieChart2($('#<?php echo esc_attr($id); ?>'),'<?php echo esc_attr(pixflow_rgb2hex($pie_chart2_percent_color)); ?>','<?php echo esc_attr(str_replace(';','',pixflow_colorConvertor($pie_chart2_percent_color,'rgba','0.2'))); ?>');
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
