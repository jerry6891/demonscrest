<?php
/**
 * Skill Style 2 Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_skill_style2', 'pixflow_get_style_script'); // pixflow_sc_skill_style2

function pixflow_sc_skill_style2( $atts, $content = null ) {

    $output = $skill_style2_num = '';

    extract( shortcode_atts( array(
        'skill_style2_num' => '4',
        'align' => 'left',
        'skill_style2_texts_color'=> '#4d4d4e',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_skill_style2',$atts);

    for( $i=1; $i<=$skill_style2_num; $i++ ){
        $bars[$i] = shortcode_atts( array(
            'skill_style2_percentage_'.$i  => '40',
            'skill_style2_title_'.$i       => 'Skill Title '.$i,
            'skill_style2_color_'.$i       => '#7b58c3',
        ), $atts );
    }

    $id = pixflow_sc_id('skill_style2');
    $func_id = uniqid();
    $align = trim($align);
    $output .= '<div id='.$id.' class="skill-style1 style2 md-align-'.esc_attr($align).'">';

    foreach( $bars as $key=>$bar )
    {
        $title       = $bar['skill_style2_title_'.$key];
        $progressbar = $bar['skill_style2_percentage_'.$key];

        $barColor    = $bar['skill_style2_color_'.$key];

        $output .= '<div id="'.$id.$key.'" class="bar-main-container '.esc_attr($animation['has-animation']).'" '.esc_attr($animation['animation-attrs']).'>';
        $output .= '<div>';
        $output .= '<div class="bar-container">';
        $output .= '<div class="bar-title">'. $title .'</div>';
        $output .= '<div class="bar"></div>';

        $output .= '<div class="back-bar"></div>';

        $output .= '<div class="middle-bar"><div class="circle"></div></div>';

        $output .= '</div>';
        $output .= '<div class="bar-percentage" data-percentage="'. $progressbar .'">0%</div>';



        $output .= '</div>';
        $output .= '</div>';

        $output .=
            '<style scoped="scoped">
                #'. $id.$key .' .bar{ background-color: '. $barColor .' }
                #'. $id.$key .' .middle-bar .circle{ background-color: '. $skill_style2_texts_color .' }
                #'. $id.$key .' .bar-percentage{ color: '. $skill_style2_texts_color .' }
                #'. $id.$key .' .bar-title{ color: '. $skill_style2_texts_color .' }
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
