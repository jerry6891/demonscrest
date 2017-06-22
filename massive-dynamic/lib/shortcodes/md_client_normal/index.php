<?php
/**
 * Client Normal Shortcode
 *
 * @author Pixflow
 */

/*Client Normal*/
add_shortcode('md_client_normal', 'pixflow_get_style_script'); // pixflow_sc_client_normal
/*-----------------------------------------------------------------------------------*/
/*  Client Normal
/*-----------------------------------------------------------------------------------*/
function pixflow_sc_client_normal($atts, $content = null){
    $md_client_logo = $md_client_bg = $md_client_general_color = $md_client_text_color = $md_client_text='';
    extract(shortcode_atts(array(
        'md_client_logo'          => PIXFLOW_THEME_IMAGES_URI."/logo.png",
        'md_client_bg_type'       => "color",
        'md_client_bg_img'        => "",
        'md_client_overlay_color' =>"rgb(0,0,0)",
        'md_client_bg_color'      => 'rgb(0,0,0)',
        'md_client_hover_color'   => 'rgb(240,240,0)',
        'md_client_link'          => '#',
        'md_client_text_color'    => 'rgb(240,240,240)',
        'md_client_text'          => 'Creative Digital Agency',
        'md_client_height'       => '300',

    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_client_normal',$atts);
    $id = pixflow_sc_id('clientNormal');

    ob_start();

    if( is_numeric($md_client_logo) ){
        $md_client_logo =  wp_get_attachment_url( $md_client_logo );
        $md_client_logo = (false == $md_client_logo)?PIXFLOW_PLACEHOLDER_BLANK:$md_client_logo;
    }else{
        $md_client_logo = $md_client_logo;
    }

    if(is_numeric($md_client_bg_img)){
        $md_client_bg_img =  wp_get_attachment_image_src( $md_client_bg_img, 'thumbnail-large') ;
        $md_client_bg_img = (false == $md_client_bg_img)?PIXFLOW_PLACEHOLDER_BLANK:$md_client_bg_img[0];
    }



    ?>
    <style scoped>

        <?php if($md_client_bg_type=='image'){ ?>
        .<?php echo esc_attr($id); ?>.client-normal{
            height:<?php echo esc_attr($md_client_height);?>px;
        }
        .<?php echo esc_attr($id); ?>.client-normal .bg-image{
            background-image: url("<?php echo esc_attr($md_client_bg_img); ?>") ;
        }

        .<?php echo esc_attr($id); ?> .content .overlay{
            background-color: <?php echo esc_attr($md_client_overlay_color); ?>;
        }


        <?php }else{?>
        .<?php echo esc_attr($id); ?>.client-normal{
            background: <?php echo esc_attr($md_client_bg_color); ?>;
            height:<?php echo esc_attr($md_client_height);?>px;
        }
        .<?php echo esc_attr($id); ?>.client-normal:hover{
            background: <?php echo esc_attr($md_client_hover_color); ?>;
            height:<?php echo esc_attr($md_client_height);?>px;
        }
        <?php } ?>

        .<?php echo esc_attr($id); ?>.client-normal .content .title{
            color:<?php echo esc_attr($md_client_text_color); ?>;
        }
    </style>


    <div  class="<?php echo esc_attr($id.' '.$animation['has-animation']); ?> client-normal" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="bg-image"></div>
        <div class="content">
            <?php if($md_client_bg_type=='image'){ ?>
                <div class="overlay"></div>
            <?php } ?>
            <div class="holder">
                <div class="logo">
                    <img src="<?php echo esc_attr($md_client_logo);?>" />
                </div>
                <a href="<?php echo esc_attr($md_client_link); ?>" target="_blank">
                    <p class="title"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($md_client_text)); ?></p>
                </a>
            </div>
        </div>
    </div>
    <script>
        var $ = jQuery;
        $(function(){
            if(typeof pixflow_clientNormal == 'function'){
                pixflow_clientNormal();
            }
        });

        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}