<?php
/**
 * Process Panel Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_process_panel', 'pixflow_get_style_script'); // pixflow_sc_process_panel

function pixflow_sc_process_panel( $atts, $content = null ) {

    $process_panel_num = '';

    extract( shortcode_atts( array(
        'process_panel_num'        => '3',
        'process_panel_base_color' => '#fff',
    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_process_panel',$atts);

    $stepColor = array('#65d97d','#42a881','#1f8784','#156664');
    for( $i=1; $i<=$process_panel_num; $i++ ){
        $bars[$i] = shortcode_atts( array(
            'process_panel_title_'.$i      => 'Online Presence Analysis',
            'process_panel_subtitle_'.$i   => 'Complete Projects',
            'process_panel_icon_'.$i       => 'icon-Health',
            'process_panel_bg_color_'.$i   => $stepColor[$i-1],
            'process_panel_icon_color_'.$i => '#fff',
        ), $atts );
    }

    $id = pixflow_sc_id('process_panel');
    $func_id = uniqid();

    ob_start();
    ?>

    <style scoped>

        <?php echo '.'.esc_attr($id) ?>.process-panel-main i,
        <?php echo '.'.esc_attr($id) ?>.process-panel-main h3,
        <?php echo '.'.esc_attr($id) ?>.process-panel-main .sub-title {
            color: <?php echo esc_attr($process_panel_base_color) ?>
        }

        <?php echo '.'.esc_attr($id) ?>.process-panel-main .process-panel-main-container {
            color: <?php echo esc_attr($process_panel_base_color) ?>
        }

    </style>

    <div id="<?php echo esc_attr($id); ?>" class="process-panel-main <?php echo (esc_attr($id . ' ' . $animation['has-animation'])." items-".esc_attr($process_panel_num)); ?>">
        <?php

        foreach( $bars as $key=>$bar )
        {
            $title      = $bar['process_panel_title_'.$key];
            $subTitle   = $bar['process_panel_subtitle_'.$key];
            $icon       = $bar['process_panel_icon_'.$key];
            $bgColor    = $bar['process_panel_bg_color_'.$key];
            $iconColor  = $bar['process_panel_icon_color_'.$key];

            ?>

            <div class="process-panel-main-container container-<?php echo esc_attr($id).$key; ?>">
                <div class="kesho"></div>
                <div class="process-panel-icon">
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                </div>

                <div class="process-panel-txt">
                    <h3 class="title"> <?php echo esc_attr($title); ?> </h3>
                    <div class="sub-title"> <?php echo esc_attr($subTitle); ?> </div>
                </div>

                <style>
                    @media (min-width:800px) and (orientation: landscape)
                    {
                    <?php if ($process_panel_num == '2') { ?>
                    <?php echo '.'.esc_attr($id) ?> .process-panel-main-container {
                        width: calc(100% / 2);
                    }
                    <?php } ?>

                    <?php if ($process_panel_num == '3') { ?>
                    <?php echo '.'.esc_attr($id) ?> .process-panel-main-container {
                                                        width: calc(100% / 3);
                                                    }

                    <?php echo '.'.esc_attr($id) ?> .process-panel-main-container:first-child {
                                                        width: calc(100% / 3 - 52px);
                                                    }

                    <?php echo '.'.esc_attr($id) ?> .process-panel-main-container:not(:first-child) {
                                                        width: calc(100% / 3 + 26px);
                                                    }
                    <?php } ?>

                    <?php if ($process_panel_num == '4') { ?>
                    <?php echo '.'.esc_attr($id) ?> .process-panel-main-container {
                                                        width: calc(100% / 4);
                                                    }
                    <?php } ?>
                    }


                    .process-panel-main .container-<?php echo esc_attr($id.$key) ?> {
                        background-color: <?php echo esc_attr($bgColor) ?>
                    }

                    .process-panel-main .container-<?php echo esc_attr($id.($key+1)) ?>:after{
                        border-left-color: <?php echo esc_attr($bgColor) ?>
                    }

                    .process-panel-main .container-<?php echo esc_attr($id.$key) ?> .process-panel-icon i {
                        color: <?php echo esc_attr($iconColor) ?>
                    }

                    .process-panel-main .container-<?php echo esc_attr($id.($key+1)) ?> .kesho{
                        background-color: <?php echo esc_attr($bgColor) ?>;
                    }

                    @media (max-width : 482px) {
                        body .process-panel-main .container-<?php echo esc_attr($id.($key+1)) ?>:after {
                            border-top-color: <?php echo esc_attr($bgColor) ?>
                        }
                    }

                    @media (min-width: 500px) and (max-width: 900px) {
                        body .process-panel-main .container-<?php echo esc_attr($id.($key+1)) ?>:after {
                            border-top-color: <?php echo esc_attr($bgColor) ?>
                        }
                    }

                </style>

            </div>

            <?php
        }
        ?>
    </div> <!-- end of process panel main -->

    <?php

    return ob_get_clean();
}
