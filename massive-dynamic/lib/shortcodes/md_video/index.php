<?php
/**
 * Video Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_video', 'pixflow_get_style_script'); // pixflow_sc_video

function pixflow_sc_video( $atts, $content = null )
{

    extract(shortcode_atts(array(
        'md_video_host'           => 'youtube',
        'md_video_url_mp4'           => '',
        'md_video_url_webm'           => '',
        'md_video_url_ogg'           => '',
        'md_video_url_youtube'           => 'https://www.youtube.com/watch?v=tcxlSrYEkq8',
        'md_video_url_vimeo'           => '',
        'md_video_style'   => 'color',
        'md_video_solid_color'        => 'rgba(20,20,20,1)',
        'md_video_image'         => '',
        'md_video_size'   => '100',
        'align'   => 'center',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_video',$atts);
    $id = pixflow_sc_id('video');
    if(is_numeric($md_video_image)){
        $md_video_image =  wp_get_attachment_url( $md_video_image ) ;
        $md_video_image = (false == $md_video_image)?PIXFLOW_PLACEHOLDER1:$md_video_image;
    }
    $md_video_play_image_position = 50;
    $solid_color_max_size = 100;
    $md_video_solid_size = $md_video_size*$solid_color_max_size/100;
    $md_video_play_image_position = $md_video_size*50/100;

    wp_enqueue_script('videojs-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/video.min.js'),array(),PIXFLOW_THEME_VERSION,true);

    $sources = $dataSetup = $extURL = '';
    if($md_video_host=='self'){
        if($md_video_url_mp4!='')
            $sources .= '<source src="'.esc_url($md_video_url_mp4).'" type="video/mp4">';
        if($md_video_url_webm!='')
            $sources .= '<source src="'.esc_url($md_video_url_webm).'" type="video/webm">';
        if($md_video_url_ogg!='')
            $sources .= '<source src="'.esc_url($md_video_url_ogg).'" type="video/ogg">';
    }elseif($md_video_host=='youtube'){
        wp_enqueue_script('videojs-youtube-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/youtube.min.js'),array(),PIXFLOW_THEME_VERSION,true);
        $dataSetup = '"techOrder": ["youtube"], "src": "'.esc_url($md_video_url_youtube).'"';
        $extURL = esc_url($md_video_url_youtube);
    }elseif($md_video_host=='vimeo'){
        wp_enqueue_script('videojs-vimeo-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/vjs.vimeo.min.js'),array(),PIXFLOW_THEME_VERSION,true);
        $dataSetup = '"techOrder": ["vimeo"], "src": "'.esc_url($md_video_url_vimeo).'"';
        $extURL = esc_url($md_video_url_vimeo);
    }

    $opacityColor = pixflow_colorConvertor($md_video_solid_color,'rgba',0.5);
    if($md_video_solid_size < 74){
        $fontSize = '17px';
        $borderSize ='4px';
    }elseif($md_video_solid_size > 74 && $md_video_solid_size < 86){
        $fontSize = '22px';
        $borderSize ='5px';
    }else{
        $fontSize = '28px';
        $borderSize ='6px';
    }
    ob_start();
    global $md_allowed_HTML_tags;
    ?>

    <style>
        .vjs-default-skin .vjs-fullscreen-control:before{
            background: url(<?php echo PIXFLOW_THEME_IMAGES_URI; ?>/fullscreen.png) no-repeat;
        }
        .video-overlay .close{background:#232627 url(<?php echo PIXFLOW_THEME_URI; ?>/assets/img/video-close.png) no-repeat 10px ;}
        .<?php echo esc_attr($id) ?> .play-btn{
            border:<?php echo esc_attr($borderSize) ?> solid <?php echo esc_attr($opacityColor) ?>;
            width: <?php echo esc_attr($md_video_solid_size);?>px;
            height: <?php echo esc_attr($md_video_solid_size);?>px;
        }
        .<?php echo esc_attr($id) ?> .icon-play-curve{
            color: <?php echo esc_attr($md_video_solid_color) ?>;
            font-size: <?php echo esc_attr($fontSize) ?>;
        }

        <?php if( $md_video_style == "squareImage" ) { ?>
        .<?php echo esc_attr($id) ?> .video-img{
            background-image: url(<?php echo esc_url($md_video_image);?>);
            max-width:<?php echo esc_attr($md_video_size)/100*496;?>px;
            height:<?php echo esc_attr($md_video_size)/100*386;?>px;
        }
        <?php } ?>

        <?php
            if($md_video_style == 'circleImage'){
            ?>
        .<?php echo esc_attr($id) ?> .video-img {
            border-radius: 50%;
            overflow: hidden;
            background-image: url(<?php echo esc_attr($md_video_image);?>);
            max-width:<?php echo esc_attr($md_video_size)/100*420;?>px;
            height:<?php echo esc_attr($md_video_size)/100*420;?>px;
        }
        .<?php echo esc_attr($id);?> .video-poster-overlay {
            border-radius: 100%;
        }
        .<?php echo esc_attr($id) ?> .image-play-btn {
            left:50%
        }
        <?php
        }
        ?>
        .<?php echo esc_attr($id);?> .image-play-btn,.<?php echo esc_attr($id);?> .play-btn{
            cursor: pointer;
        }
        iframe<?php echo esc_attr($id);?>_video_vimeo_api{
            height:180%;
            top:-40px;
        }
        .<?php echo esc_attr($id);?> .video-poster-overlay{
            position: absolute;
            background: #000;
            opacity: .3;
            width: 100%;
            height: 100%;
            left:0;
            top:0;
        }

        .<?php echo esc_attr($id) ?> .play-btn:hover{
            border-color: <?php echo esc_attr(pixflow_colorConvertor($md_video_solid_color,'rgba',1))?>;
        }
    </style>
    <?php
    $align = trim($align);
    ?>
    <div data-id="<?php echo esc_attr($id);?>" class="video video-shortcode <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align);?>" <?php echo esc_attr($animation['animation-attrs']);?>>
        <?php
        if($md_video_style == 'color'){
            ?>
            <div class="play-btn">
                <div class="play-helper">
                    <span class="icon-play-curve"></span>
                </div>
            </div>
        <?php }else{ ?>
            <div class="video-img">
                <div class="video-poster-overlay"></div>
                <img src="<?php echo PIXFLOW_THEME_IMAGES_URI . "/play.png"?>" class="image-play-btn">

            </div>
        <?php }?>
        <script type="text/javascript">
            "use strict"
            var $=jQuery;
            $(document).ready(function()
            {
                if(typeof pixflow_videoShortcode == 'function'){
                    pixflow_videoShortcode('<?php echo esc_attr($id);?>','<?php echo wp_kses($sources,$md_allowed_HTML_tags);?>','<?php echo esc_attr($md_video_host);?>','<?php echo esc_url($extURL);?>');
                }
            });
        </script>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();

}
