<?php
/**
 * Fancy Text Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_fancy_text', 'pixflow_get_style_script');

function pixflow_sc_fancy_text( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'fancy_text_bg_type'        => 'icon',
        'fancy_text_icon'              => 'icon-MusicalNote',
        'fancy_text_bg_text'             => '01',
        'fancy_text_bg_color'        => 'rgba(7, 0, 255, 0.15)',
        'fancy_text_title_color'    => 'rgba(55,55,55,1)',
        'fancy_text_title'          => 'Fancy Text',
        'fancy_text_heading'        => 'h5',
        'fancy_text_text_color'     => 'rgba(55,55,55,1)',
        'fancy_text_text'       => "Massive Dynamic has over 10 years of experience in Design. We take pride in delivering Intelligent Designs and Engaging Experiences for clients all over the World.",
        'align'                     => 'left'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_fancy_text',$atts);
    $id = pixflow_sc_id('fancy_text');
    ob_start();
    ?>
    <style scoped>
        <?php echo '.'.esc_attr($id);?> .fancy-text-bg{
            color:<?php echo esc_attr($fancy_text_bg_color)?>;
        }
        <?php echo '.'.esc_attr($id);?> .fancy-text-title{
                                            color:<?php echo esc_attr($fancy_text_title_color)?>;
                                        }
        <?php echo '.'.esc_attr($id);?> .fancy-text-text{
                                            color:<?php echo esc_attr($fancy_text_text_color)?>;
                                        }

    </style>
    <?php
    $align = trim($align);
    ?>
<div class="md-fancy-text-container <?php echo 'md-align-'.esc_attr($align); ?>">
<div class="md-fancy-text fancy-text-type-<?php echo esc_attr($fancy_text_bg_type);?> <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.esc_attr($align)); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

    <<?php echo esc_attr($fancy_text_heading) ?> class="fancy-text-bg <?php echo is_numeric($fancy_text_bg_text)?' fancy-text-numeric':''; ?> ">
    <?php
    if($fancy_text_bg_type == 'icon'){
        ?>
        <span class="icon <?php echo esc_attr($fancy_text_icon) ?>"></span>
    <?php }else{
        echo esc_attr($fancy_text_bg_text);
    }?>
    </<?php echo esc_attr($fancy_text_heading) ?>>
    <<?php echo esc_attr($fancy_text_heading) ?> class="fancy-text-title">
    <?php
    echo esc_attr($fancy_text_title);
    ?>
    </<?php echo esc_attr($fancy_text_heading) ?>>
    <p class="fancy-text-text"><?php echo esc_attr($fancy_text_text);?></p>
    </div>
    <div class="clearfix"></div>
    <?php pixflow_callAnimation(true); ?>
    </div>
    <?php
    return ob_get_clean();
}