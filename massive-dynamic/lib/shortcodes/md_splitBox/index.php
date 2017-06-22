<?php
/**
 * Split Box Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_splitBox', 'pixflow_get_style_script'); // pixflow_sc_splitBox

function pixflow_sc_splitBox($atts, $content = null)
{
    extract(shortcode_atts(array(
        'sb_title_size'               => 'h3',
        'sb_title'                    => 'Super Flexible',
        'sb_alignment'                => 'sb-left',
        'sb_subtitle'                 => 'OBJECT',
        'sb_desc'                     => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores.',
        'sb_bg_color'                 => 'rgb(233,233,233)',
        'sb_text_color'               => 'rgb(0,0,0)',
        'sb_height'                   => '470',
        'sb_image'                    => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'use_button'                  => 'yes',
        'button_style'                => 'fill-rectangle',
        'button_text'                 => 'VIEW MORE',
        'button_icon_class'           => 'icon-angle-right',
        'button_color'                => 'rgba(255,255,255,1)',
        'button_text_color'           => 'rgba(126,126,126,1)',
        'button_bg_hover_color'       => 'rgb(0,0,0)',
        'button_hover_color'          => 'rgb(255,255,255)',
        'button_size'                 => 'standard' ,
        'left_right_padding'          => '0' ,
        'button_url'                  => '#' ,
        'button_target'               => '_self',

    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_splitbox',$atts);
    $id = pixflow_sc_id('splitBox');

    $image = $sb_image;
    if($image != '' && is_numeric($image)){
        $image = wp_get_attachment_image_src( $image,'full') ;
        $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
    }

    ob_start();
    ?>

    <style scoped>



        .<?php echo esc_attr($id)?> .image-holder{
            background-image: url('<?php echo esc_attr($image)?>');
        }
        .<?php echo esc_attr($id)?> .arrow-right{
            border-left-color:<?php echo esc_attr($sb_bg_color)?>;
        }
        .<?php echo esc_attr($id)?> .text-holder{
            background-color: <?php echo esc_attr($sb_bg_color)?>;
        }

        .<?php echo esc_attr($id)?> .fixed-width .subtitle,
        .<?php echo esc_attr($id)?> .fixed-width p{
            color:<?php echo pixflow_colorConvertor(esc_attr($sb_text_color),'rgba',.7);?>;

        }

        .<?php echo esc_attr($id)?> .fixed-width .title{
            color:<?php echo esc_attr($sb_text_color)?>;
        }

    </style>

    <div id="id-<?php echo esc_attr($id) ?>" class="clearfix <?php echo esc_attr($id.' '.$animation['has-animation'].' '.$sb_alignment)?> md-splitBox" <?php echo esc_attr($animation['animation-attrs']); ?> data-height="<?php echo(esc_attr($sb_height)); ?>">
        <div class="splitBox-holder">
            <div class="arrow-right"></div>
            <div class="image-holder"></div>
            <div class="text-holder">
                <div class="fixed-width">
                    <h6 class="subtitle"><?php echo esc_attr($sb_subtitle); ?></h6>
                    <<?php echo esc_attr($sb_title_size); ?> class="title"><?php echo esc_attr($sb_title); ?></<?php echo esc_attr($sb_title_size); ?>>
                <p><?php echo esc_attr($sb_desc); ?></p>
                <?php if($use_button=='yes'){?>
                    <div class="splitBox-button">
                        <?php echo pixflow_buttonMaker($button_style,$button_text,$button_icon_class,$button_url,$button_target,'left',$button_size,$button_color,$button_hover_color,$left_right_padding,$button_text_color,$button_bg_hover_color); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>

    <script>
        var $ = jQuery;
        if ( typeof pixflow_splitBox == 'function' ){
            pixflow_splitBox(false,false);
        }
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}