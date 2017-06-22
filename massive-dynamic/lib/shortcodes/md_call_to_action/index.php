<?php
/**
 * Call To Action Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_call_to_action', 'pixflow_get_style_script'); // pixflow_sc_callToAction

/*-----------------------------------------------------------------------------------*/
/*  Call To Action
/*-----------------------------------------------------------------------------------*/


function pixflow_sc_call_to_action( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'call_to_action_title'             => 'Are you looking for job?',
        'call_to_action_heading'           => 'h1',
        'call_to_action_heading_color'     => 'rgb(255,255,255)',
        'call_to_action_description'       => 'We are a fairly small, flexible design studio that designs for print and web. We work flexibly with Send us your resume & portfolio',
        'call_to_action_description_color' => 'rgb(255,255,255)',
        'call_to_action_background_type'   => 'color_background',
        'call_to_action_background_color'  => 'rgb(37,37,37)',
        'call_to_action_background_image'  => '',
        'call_to_action_button_style'      => 'animation',
        'call_to_action_button_text'       => 'READ MORE',
        'call_to_action_button_icon_class' => 'icon-angle-right',
        'call_to_action_button_size'       => 'standard',
        'call_to_action_button_color'      => 'rgb(255,255,255)',
        'call_to_action_button_text_color' => 'rgb(255,255,255)',
        'call_to_action_button_bg_hover_color' => '#9b9b9b',
        'call_to_action_button_hover_color'=> '#fff',
        'call_to_action_button_url'        => '#',
        'call_to_action_button_target'     => '_self',
        'call_to_action_left_right_padding' => '0',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_call_to_action',$atts);
    $id = pixflow_sc_id('call-to-action');
    $buttonSize = strtolower('button-'.$call_to_action_button_size);
    ob_start();
    ?>
    <style scoped>

        <?php if($call_to_action_background_type == 'color_background'){ ?>

        .<?php echo esc_attr($id) ?>{
            background-color : <?php echo esc_attr($call_to_action_background_color); ?>;
        }

        <?php }
        else if($call_to_action_background_type == 'image_background'){
            if (is_numeric($call_to_action_background_image)) {
                $imageSrc = wp_get_attachment_url($call_to_action_background_image);
                $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER1:$imageSrc;
                $call_to_action_background_image = $imageSrc;
            }
            ?>
        .<?php echo esc_attr($id) ?>{
            background-image : url("<?php echo esc_url($call_to_action_background_image); ?>");
        }
        <?php }
        else{ ?>

        .<?php echo esc_attr($id) ?>{
            background : transparent;
        }
        <?php }?>


        <?php if($buttonSize == 'button-standard'){ ?>
        .<?php echo esc_attr($id) ?> .button-parent {
            height: 50px;
            margin-top: -20px;
        }
        <?php } else{
                echo ".".esc_attr($id) ?> .button-parent {
                                                      height: 40px;
                                                      margin-top: -15px;
                                                  }
        <?php } ?>

        .<?php echo esc_attr($id) ?>  .title {
            color: <?php echo esc_attr($call_to_action_heading_color); ?>;
        }

        .<?php echo esc_attr($id) ?> .description{
            color: <?php echo esc_attr($call_to_action_description_color); ?>;
        }
    </style>
    <div class="call-to-action <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="">
            <div class="content clearfix">
                <<?php echo esc_attr($call_to_action_heading); ?> class="title"><?php echo esc_attr($call_to_action_title); ?></<?php echo esc_attr($call_to_action_heading); ?>>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($call_to_action_description)); ?></p>
            <div class="button-parent">
                <?php echo pixflow_buttonMaker($call_to_action_button_style,$call_to_action_button_text,$call_to_action_button_icon_class,$call_to_action_button_url,$call_to_action_button_target,'right',$call_to_action_button_size,$call_to_action_button_color,$call_to_action_button_hover_color,$call_to_action_left_right_padding,$call_to_action_button_text_color,$call_to_action_button_bg_hover_color); ?>
            </div>
        </div>
    </div>
    </div> <!-- Call to Action ends -->
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}