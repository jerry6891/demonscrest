<?php
/**
 * iconBox Side 2 Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_iconbox_side2', 'pixflow_get_style_script'); // pixflow_sc_iconbox_side2

function pixflow_sc_iconbox_side2( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'iconbox_side2_alignment'  => 'left',
        'iconbox_side2_icon'       => 'icon-ribbon',
        'iconbox_side2_title'      => 'Advertisement',
        'iconbox_side2_title_big'  => 'Creative Elements',
        'iconbox_side2_title_big_heading'  => 'H6',
        'iconbox_side2_icon_color'         => 'rgba(0,0,0,.5)',
        'iconbox_side2_small_title_color'  => '#12be83',
        'iconbox_side2_general_color'      => '#000',
        'iconbox_side2_description'        => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable',
        'iconbox_side2_button'             => 'no',
        'iconbox_side2_button_style'       => 'fade-square',
        'iconbox_side2_button_color'       => '#5e5e5e',
        'iconbox_side2_button_hover_color' => '#fff',
        'iconbox_side2_button_text_color'  => '#fff',
        'iconbox_side2_button_bg_hover_color' => '#9b9b9b',
        'iconbox_side2_button_text'           => 'Read more',
        'iconbox_side2_class'                 => 'icon-snowflake2',
        'iconbox_side2_button_size'           => 'standard',
        'iconbox_side2_button_url'            => '#',
        'iconbox_side2_button_target'         => '_self',
        'iconbox_side2_left_right_padding'    => '0',
        'iconbox_side2_image'=> '',
        'iconbox_side2_type'=>'icon',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_iconbox_side2',$atts);
    $id = pixflow_sc_id('iconbox-side');

    ob_start(); ?>
    <style scoped>
        <?php echo '.'.esc_attr($id) ?> .icon{
            color: <?php echo esc_attr($iconbox_side2_icon_color); ?>;
        }

        <?php echo '.'.esc_attr($id) ?> .info-title{
                                            color: <?php echo esc_attr($iconbox_side2_small_title_color); ?>;
                                        }

        <?php echo '.'.esc_attr($id) ?> .description,
                                        <?php echo '.'.esc_attr($id) ?> .title{
                                            color: <?php echo esc_attr($iconbox_side2_general_color); ?>;
                                        }

    </style>

    <?php   $sideClass = '';
    if('right' == $iconbox_side2_alignment) {
        $sideClass .= ' right-align';
    } else if('left' == $iconbox_side2_alignment) {
        $sideClass .= ' left-align';
    }?>

    <div class="iconbox-side2 style2  <?php echo esc_attr($id.' '.$animation['has-animation']); echo esc_attr($sideClass); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="iconbox-content">
            <div class="iconbox-side2-container  clearfix" >
                <?php if( isset($iconbox_side2_icon) && 'icon-empty' != $iconbox_side2_icon && $iconbox_side2_type=='icon' ){ ?>
                    <div class="icon-container">
                        <div class="icon <?php echo esc_attr($iconbox_side2_icon) ?>"></div>
                    </div>
                <?php }
                else if ( isset($iconbox_side2_image) && '' != $iconbox_side2_image && $iconbox_side2_type=='image' )
                {
                    $imageSrc = wp_get_attachment_url($iconbox_side2_image) ;
                    $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER_BLANK:$imageSrc;
                    $iconbox_side2_image_url= $imageSrc;
                    ?>
                    <div class="image-container">
                        <div class="iconbox_side2_image" style="background-image:url(<?php echo esc_url($iconbox_side2_image_url)  ?>);"></div>
                    </div>
                    <?php
                }
                ?>

                <!--End of Icon section-->

                <?php if( (isset($iconbox_side2_title_big) && '' != $iconbox_side2_title_big ) || (isset($iconbox_side2_title) && '' != $iconbox_side2_title)){ ?>
                <div class="heading">
                    <?php  if ((isset($iconbox_side2_title) && '' != $iconbox_side2_title)){ ?>
                        <span class="info-title"><?php echo esc_attr($iconbox_side2_title) ?></span>
                    <?php }

                    if(isset($iconbox_side2_title_big) && '' != $iconbox_side2_title_big ){ ?>
                    <<?php echo esc_attr($iconbox_side2_title_big_heading) ?> class="title"> <?php echo esc_attr($iconbox_side2_title_big) ?> </<?php echo esc_attr($iconbox_side2_title_big_heading) ?>>
            <?php } ?>
            </div>
            <?php } ?>
            <!--End of Title section-->
        </div>
        <!-- End of title container -->

        <?php if( isset($iconbox_side2_description) && '' != $iconbox_side2_description ){ ?>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($iconbox_side2_description)); ?></p>
        <?php } ?>
        <!--End of Description section-->

        <?php
        if( 'yes' == $iconbox_side2_button ){
            echo pixflow_buttonMaker($iconbox_side2_button_style,$iconbox_side2_button_text,$iconbox_side2_class,$iconbox_side2_button_url,$iconbox_side2_button_target,$iconbox_side2_alignment,$iconbox_side2_button_size,$iconbox_side2_button_color,$iconbox_side2_button_hover_color,$iconbox_side2_left_right_padding,$iconbox_side2_button_text_color,$iconbox_side2_button_bg_hover_color);
        } ?>
        <!--End of Button section-->
    </div>
    </div>

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
