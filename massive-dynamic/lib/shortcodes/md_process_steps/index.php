<?php
/**
 * Process Steps Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_process_steps', 'pixflow_get_style_script'); // pixflow_sc_process_steps

function pixflow_sc_process_steps( $atts, $content = null ){
    $output = '';
    extract( shortcode_atts( array(
        'pstep_step_num'  =>4,
        'pstep_style'  =>'color',
        'pstep_border_color'  =>'rgba(176,227,135,1)',
        'pstep_overlay_color'  =>'rgba(0,0,0,0.5)',
        'pstep_general_color'  =>'rgb(0,0,0)',
    ), $atts ) );

    for($i=1; $i<=$pstep_step_num; $i++){
        $steps[$i] = shortcode_atts( array(
            'pstep_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
            'pstep_size_'.$i => 'small',
            'pstep_title_'.$i => "Step ".$i,
            'pstep_desc_'.$i => "Description of step".$i,
            'pstep_caption_'.$i => '201'.$i,
        ), $atts );
    }

    $id = pixflow_sc_id('process-steps');
    ob_start();
    ?>
    <style scoped>
        <?php if('color' == $pstep_style){ ?>

        .<?php echo esc_attr($id) ?> .circle{
            border: 2px solid <?php echo esc_attr($pstep_border_color) ?>;
        }

        <?php } ?>
        .<?php echo esc_attr($id) ?> .caption,
        .<?php echo esc_attr($id) ?> .title {
            color: <?php echo esc_attr($pstep_general_color) ?>
        }

        .<?php echo esc_attr($id) ?> .description {
            color: <?php echo esc_attr(pixflow_colorConvertor($pstep_general_color,'rgba', 0.6)) ?>
        }

        .<?php echo esc_attr($id) ?> .separator {
            border-top: 2px dotted <?php echo esc_attr(pixflow_colorConvertor($pstep_general_color,'rgba', 0.6)) ?>;
        }

        .<?php echo esc_attr($id) ?> .overlay-background{
            background-color: <?php echo esc_attr($pstep_overlay_color) ?>;
        }

    </style>
    <div class="process-steps <?php echo esc_attr($id) ?>  clearfix">
        <?php
        if(is_array($steps)){
            foreach($steps as $key=>$step) {
                $img = $step['pstep_image_' . $key];

                $size = $step['pstep_size_' . $key];
                $title = $step['pstep_title_' . $key];
                $desc = $step['pstep_desc_' . $key];
                $caption = $step['pstep_caption_' . $key];
                $inlineStyle = '';

                if('image' == $pstep_style){

                    if (is_numeric($img)) {
                        $img = wp_get_attachment_url($img);

                        $img = (false == $img)?PIXFLOW_PLACEHOLDER1:$img;
                    }
                } else{
                    $img = '';
                }
                ?>
                <div class="step <?php echo esc_attr($size); ?>">
                    <div class="circle" style="background-image:url('<?php echo esc_url($img)?>')">
                        <span class="caption"><?php echo esc_attr($caption); ?></span>
                        <div class="separator"></div>
                        <?php if('image' == $pstep_style){ ?>
                            <div class="overlay-background"></div>
                        <?php } ?>
                    </div>
                    <h4 class="title"><?php echo esc_html($title); ?></h4>
                    <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($desc)); ?></p>
                </div>
            <?php }} ?>
    </div>
    <script type="text/javascript">
        var $ = jQuery;
        $(document).ready(function(){
            if ( typeof pixflow_processSteps == 'function' ){
                pixflow_processSteps();
            }
            if (typeof  pixflow_shortcodeScrollAnimation == 'function'){
                pixflow_shortcodeScrollAnimation();
            }
        });


    </script>
    <?php
    return ob_get_clean();
}
