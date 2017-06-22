<?php
/**
 * Image Box Full Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_imagebox_full', 'pixflow_get_style_script'); // pixflow_sc_imagebox_full

function pixflow_sc_imagebox_full( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'imagebox_title'             => 'Products that perform as good as they look',
        'imagebox_heading_size'      => 'h3',
        'imagebox_description'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo. Sed quis augue ut eros tincidunt hendrerit eu eget nisl. Duis malesuada vehicula massa...
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus magna massa at justo. Sed quis augue ut eros tincidunt hendrerit eu eget nisl.',
        'imagebox_general_color'     => '#ffffff',
        'imagebox_text_height'       => '300',
        'imagebox_alignment'         => 'left',
        'imagebox_use_background'    => 'yes',
        'imagebox_background'        => '',
        'imagebox_overlay'           => 'yes',
        'imagebox_overlay_color'     => 'rgba(90,31,136,0.5)',
        'imagebox_button'            => 'yes',
        'imagebox_button_style'      =>'slide',
        'imagebox_button_text'       => 'Read more',
        'imagebox_button_icon'       => 'icon-arrow-right4',
        'imagebox_button_color'      => '#fff',
        'imagebox_button_text_color' => '#000',
        'imagebox_button_bg_hover_color' => '#9b9b9b',
        'imagebox_button_hover_color'=> '#9b9b9b',
        'imagebox_button_size'       => 'standard',
        'imagebox_left_right_padding'=> 0,
        'imagebox_button_url'        => '#',
        'imagebox_button_target'     => '_self',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_imagebox_full',$atts);

    $id = pixflow_sc_id('imagebox-full');

    if('yes' == $imagebox_use_background && is_numeric($imagebox_background)){
        $imagebox_background =  wp_get_attachment_url( $imagebox_background ) ;
        $imagebox_background = (false == $imagebox_background)?PIXFLOW_PLACEHOLDER1:$imagebox_background;

    }

    ob_start();?>
    <style scoped>
        <?php if($imagebox_alignment == 'center'){ ?>
        <?php echo '.'.esc_attr($id) ?>{
            text-align:center;
        }
        <?php }else{ ?>
        <?php echo '.'.esc_attr($id) ?>{
            text-align:left;
        }
        <?php } ?>

        <?php echo '.'.esc_attr($id) ?> .title,
        <?php echo '.'.esc_attr($id) ?> .description{
            color:  <?php echo esc_attr($imagebox_general_color); ?>;
        <?php if($imagebox_alignment == 'center'){ ?>
            margin-left: auto;
            margin-right: auto;
        <?php } ?>
        }
        <?php if($imagebox_alignment == 'center'){ ?>
        <?php echo '.'.esc_attr($id) ?> .shortcode-btn {
                                            max-width: 570px;
                                            margin: auto;
                                            display: block;
                                        }
        <?php echo '.'.esc_attr($id) ?> .shortcode-btn .button{
                                            text-align: left;
                                        }
        <?php } ?>
    </style>
    <div class="imagebox-full clearfix <?php echo esc_attr($id.' '.$animation['has-animation'].' align-'.$imagebox_alignment); ?>" style="<?php if($imagebox_background != ''){ ?>background-image:url('<?php echo esc_attr($imagebox_background) ?>');<?php } ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <?php if( 'yes' == $imagebox_overlay){ ?>
            <div class="overlay" style="<?php if('yes' == $imagebox_use_background && 'yes' == $imagebox_overlay){ ?>background-color: <?php echo esc_attr($imagebox_overlay_color)?><?php } ?>"></div>
        <?php } ?>
        <div class="text-container" style="height: <?php echo esc_attr(abs((int)$imagebox_text_height)) ?>px;max-height: <?php echo esc_attr(abs((int)$imagebox_text_height)) ?>px">
            <<?php echo esc_attr($imagebox_heading_size) ?> class="title"><?php echo esc_attr($imagebox_title) ?></<?php echo esc_attr($imagebox_heading_size) ?>>
        <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($imagebox_description)); ?></p>
    </div>
    <?php
    if( 'yes' == $imagebox_button ) {
        echo pixflow_buttonMaker($imagebox_button_style, $imagebox_button_text,$imagebox_button_icon, $imagebox_button_url, $imagebox_button_target,$imagebox_alignment,$imagebox_button_size, $imagebox_button_color,$imagebox_button_hover_color,$imagebox_left_right_padding,$imagebox_button_text_color,$imagebox_button_bg_hover_color);

    } ?>
    <script>
        "use strict";
        if ( typeof pixflow_imageboxFull == 'function' )
        {
            pixflow_imageboxFull();
        }
        <?php pixflow_callAnimation(); ?>
    </script>
    </div>
    <?php
    return ob_get_clean();
}
