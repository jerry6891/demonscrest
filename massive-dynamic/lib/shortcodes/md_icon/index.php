<?php
/**
 * Icon Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_icon', 'pixflow_get_style_script'); // pixflow_sc_icon
function pixflow_sc_icon( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'icon_source'               => 'massive_dynamic',
        'icon_icon'                 => 'icon-diamond',
        'icon_url'                  => 'http://',
        'icon_color'                => '#5f5f5f',
        'icon_hover_color'          => '#b6b6b6',
        'icon_fill_color'           => 'rgba(0,0,0,1)',
        'icon_hover_fill_color'     => 'rgba(100,100,100,1)',
        'icon_stroke_color'         => 'rgba(0,0,0,1)',
        'icon_hover_stroke_color'   => 'rgba(100,100,100,1)',
        'icon_size'                 => "153",
        'icon_use_link'             => "No",
        'icon_link'                 => "http://",
        'icon_link_target'          => "_blank",
        'align'                     => 'center'

    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_icon',$atts);
    $id = pixflow_sc_id('icon');

    ob_start(); ?>

    <style scoped>
        <?php echo '.'.esc_attr($id) ?> .icon{
            color: <?php echo esc_attr($icon_color); ?>;
            font-size: <?php echo esc_attr($icon_size); ?>px;
            transition: color 400ms;
        }

        <?php echo '.'.esc_attr($id) ?> .icon:hover{
                                            color: <?php echo esc_attr($icon_hover_color); ?>;
                                        }
        <?php echo '.'.esc_attr($id);?> .svg{
                                            width:<?php echo esc_attr($icon_size); ?>px;
                                            height:<?php echo esc_attr($icon_size); ?>px;
                                        }
        <?php echo '.'.esc_attr($id);?> .svg path{
                                            fill: <?php echo esc_attr($icon_fill_color); ?>;
                                            stroke: <?php echo esc_attr($icon_stroke_color); ?>;
                                            transition: fill 400ms, stroke 400ms;
                                        }

        <?php echo '.'.esc_attr($id);?> .svg:hover path{
                                            fill: <?php echo esc_attr($icon_hover_fill_color); ?>;
                                            stroke: <?php echo esc_attr($icon_hover_stroke_color); ?>;
                                        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="md-icon <?php echo esc_attr($id.' '.$animation['has-animation']); ?> md-align-<?php echo esc_attr($align);?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <?php if($icon_source=='massive_dynamic'){
            if($icon_use_link=='yes'){
                ?>
                <a target="<?php echo esc_attr($icon_link_target)?>" href="<?php echo esc_attr($icon_link)?>">
            <?php }?>
            <span class="icon <?php echo esc_attr($icon_icon) ?>"></span>
            <?php
            if($icon_use_link=='yes'){
                ?></a><?php
            }
        }else{
            if($icon_use_link=='yes'){
                ?>
                <a target="<?php echo esc_attr($icon_link_target)?>" href="<?php echo esc_attr($icon_link)?>">
            <?php }?>
            <img src="<?php echo esc_attr($icon_url)?>" class="svg" width="<?php echo esc_attr($icon_size)?>">
            <?php
            if($icon_use_link=='yes'){
                ?></a>
            <?php }?>
        <?php }?>
    </div>
    <?php
    if($icon_source!='massive_dynamic'){
        ?>
        <script>
            "use strict";
            var $ = (jQuery);
            $(document).ready(function(){
                if(typeof pixflow_iconShortcode == 'function'){
                    pixflow_iconShortcode('<?php echo esc_attr($id)?>');
                }
            });
        </script>
        <?php
    }
    pixflow_callAnimation(true);
    return ob_get_clean();
}
