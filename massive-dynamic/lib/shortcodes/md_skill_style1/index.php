<?php
/**
 * Skill Style 1 Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_skill_style1', 'pixflow_get_style_script'); // pixflow_sc_skill_style1

function pixflow_sc_skill_style1( $atts, $content = null ) {

    $output = $skill_style1_num = '';

    extract( shortcode_atts( array(
        'skill_style1_num' => '4',
        'align' => 'left',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_skill_style1',$atts);

    for( $i=1; $i<=$skill_style1_num; $i++ ){
        $bars[$i] = shortcode_atts( array(
            'skill_style1_percentage_'.$i  => '40',
            'skill_style1_title_'.$i       => 'Title',
            'skill_style1_texts_color_'.$i => '#9b9b9b',
            'skill_style1_color_'.$i       => '#9b9b9b',
        ), $atts );
    }

    $id = pixflow_sc_id('skill_style1');
    $func_id = uniqid();
    $align = trim($align);
    $output .= '<div id='.$id.' class="skill-style1 md-align-'.esc_attr($align).'">';

    foreach( $bars as $key=>$bar )
    {
        $title       = $bar['skill_style1_title_'.$key];
        $progressbar = $bar['skill_style1_percentage_'.$key];
        $textsColor  = $bar['skill_style1_texts_color_'.$key];
        $barColor    = $bar['skill_style1_color_'.$key];

        $output .= '<div id="'.$id.$key.'" class="bar-main-container '.esc_attr($animation['has-animation']).'" '.esc_attr($animation['animation-attrs']).'>';
        $output .= '<div>';
        $output .= '<div class="bar-percentage" data-percentage="'. $progressbar .'">0%</div>';
        $output .= '<div class="bar-container">';
        $output .= '<div class="bar"></div>';
        $output .= '</div>';

        $output .= '<div class="bar-title">'. $title .'</div>';

        $output .= '</div>';
        $output .= '</div>';

        $output .=
            '<style scoped="scoped">
                #'. $id.$key .' .bar{ background-color: '. $barColor .' }
                #'. $id.$key .' .bar-percentage{ color: '. $textsColor .' }
                #'. $id.$key .' .bar-title{ color: '. $textsColor .' }
            </style>';
    }

    $output .= '</div>';  // End skill style1

    ob_start();
    ?>

    <script type="text/javascript">
        "use strict";
        var $ = jQuery;

        if ( typeof pixflow_skill_style1 == 'function' ){
            pixflow_skill_style1( "#<?php echo esc_attr( $id ); ?>" );
        }
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;
}
