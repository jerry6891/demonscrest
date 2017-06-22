<?php
/**
 * Pixflow Slider Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_slider', 'pixflow_get_style_script'); // pixflow_sc_slider

function pixflow_sc_slider($atts, $content = null)
{
    $output = $slider_num = '';
    extract( shortcode_atts( array(
        'slider_num'         => '3',
        'slider_autoplay' => 'yes',
        'slider_autoplay_duration' => '3',
        'slider_height_mode' => 'fit',
        'slider_height' => '600',
        'slider_skin' => 'hr-left',
        'slider_title_custom_font' => 'no',
        'slider_title_font' => 'font_family:Roboto%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900|font_style:200%20light%20regular%3A200%3Anormal',
        'slider_title_size' => '70',
        'slider_title_line_height' => '80',
        'slider_subtitle_custom_font' => 'no',
        'slider_subtitle_font' => 'font_family:Roboto%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900|font_style:200%20light%20regular%3A200%3Anormal',
        'slider_subtitle_size' => '20',
        'slider_subtitle_line_height'   =>'20',
        'slider_desc_custom_font' => 'no',
        'slider_indicator'=>'off',
        'slider_indicator_theme'=>'indicator-dark',
        'slider_desc_font' => 'font_family:Roboto%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900|font_style:200%20light%20regular%3A200%3Anormal',
    ), $atts ) );

    for($i=1; $i<=$slider_num; $i++){
        $slides[$i] = shortcode_atts( array(
            'slide_content_type_'.$i => 'text',
            'slide_subtitle_'.$i        => 'Know About',
            'slide_subtitle_color_'.$i    => "#dbdbdb",
            'slide_title_'.$i => 'Massive Dynamic <br> Unique Slider',
            'slide_title_color_'.$i => '#ffffff',
            'slide_desc_'.$i => 'Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet.',
            'slide_desc_color_'.$i => 'rgb(0, 255, 153)',
            'slide_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
            'slide_content_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
            'slide_image_color_'.$i => 'rgba(0, 0, 0, 0.4)',
            //button1
            "slide_btn1_kind_".$i       => 'fade-oval',
            'slide_btn1_'.$i => 'yes',
            'slide_btn1_title_'.$i => 'DOWNLOAD',
            'slide_btn1_link_'.$i => 'http://massivedynamic.co/',
            'slide_btn1_target_'.$i => '_blank',
            'slide_btn1_color_'.$i => '#FFF',
            'slide_btn1_bg_hover_color_'.$i => '#9b9b9b',
            'slide_btn1_text_hover_color_'.$i => '#000',
            'slide_btn1_hover_color_'.$i => '#ff0054',
            //button2
            'slide_btn2_'.$i => 'yes',
            "slide_btn2_kind_".$i       => 'fade-oval',
            'slide_btn2_title_'.$i => 'READ MORE',
            'slide_btn2_link_'.$i => 'http://demo.massivedynamic.co/general/',
            'slide_btn2_target_'.$i => '_blank',
            'slide_btn2_color_'.$i => '#FFF',
            'slide_btn2_bg_hover_color_'.$i => '#9b9b9b',
            'slide_btn2_text_hover_color_'.$i => '#000',
            'slide_btn2_hover_color_'.$i => '#ff0054',

        ), $atts );
    }
    $id = pixflow_sc_id('md_slider');
    // Load Custom fonts
    //$slider_skin = 'classic';
    if('yes' == $slider_title_custom_font  && $slider_title_font != ''){
        $slider_title_font = str_replace("font_family:", "", $slider_title_font);
        $arr = explode("%3A", $slider_title_font, 2);
        $title_font = $arr[0];
        $title_font = str_replace("%20", " ", $title_font);
    }
    if('yes' == $slider_subtitle_custom_font  && $slider_subtitle_font != ''){
        $slider_subtitle_font = str_replace("font_family:", "", $slider_subtitle_font);
        $arr = explode("%3A", $slider_subtitle_font, 2);
        $subtitle_font = $arr[0];
        $subtitle_font = str_replace("%20", " ", $subtitle_font);
    }
    if('yes' == $slider_desc_custom_font  && $slider_desc_font != ''){
        $slider_desc_font = str_replace("font_family:", "", $slider_desc_font);
        $arr = explode("%3A", $slider_desc_font, 2);
        $desc_font = $arr[0];
        $desc_font = str_replace("%20", " ", $desc_font);
    }
    if('yes' == $slider_title_custom_font || 'yes' == $slider_subtitle_custom_font || 'yes' == $slider_desc_custom_font){
        if('yes' == $slider_title_custom_font) {
            wp_enqueue_style('vc_google_fonts_' . $title_font, '//fonts.googleapis.com/css?family=' . $slider_title_font);
            // Extract Title font style
            if (isset($slider_title_font[0])) {
                $slider_title_font = explode('|', rawurldecode($slider_title_font));
                $title_font_style = explode(':', $slider_title_font[1]);
                $title_font_style = explode(' ', $title_font_style[1]);
                $title_font_family = $title_font;
                $title_font_weight = $title_font_style[0];
                $title_font_style = $title_font_style[1];
            }
        }
        if('yes' == $slider_subtitle_custom_font) {
            wp_enqueue_style('vc_google_fonts_' . $subtitle_font, '//fonts.googleapis.com/css?family=' . $slider_subtitle_font);
            // Extract Subtitle font style
            if (isset($slider_subtitle_font[0])) {
                $slider_subtitle_font = explode('|', rawurldecode($slider_subtitle_font));
                $subtitle_font_style  = explode(':', $slider_subtitle_font[1]);
                $subtitle_font_style  = explode(' ', $subtitle_font_style[1]);
                $subtitle_font_family = $subtitle_font;
                $subtitle_font_weight = $subtitle_font_style[0];
                $subtitle_font_style  = $subtitle_font_style[1];
            }
        }
        if('yes' == $slider_desc_custom_font) {
            wp_enqueue_style('vc_google_fonts_' . $desc_font, '//fonts.googleapis.com/css?family=' . $slider_desc_font);
            // Extract Description font style
            if (isset($slider_subtitle_font[0])) {
                $slider_desc_font = explode('|', rawurldecode($slider_desc_font));
                $desc_font_style  = explode(':', $slider_desc_font[1]);
                $desc_font_style  = explode(' ', $desc_font_style[1]);
                $desc_font_family = $desc_font;
                $desc_font_weight = $desc_font_style[0];
                $desc_font_style  = $desc_font_style[1];
            }
        }
    }
    ob_start();

    $orientation  = 'classic';
    $sliderClass = 'pixflow-slider '. $slider_autoplay ." ";
    $slideClass = 'pixflow-slide ';
    if ($slider_skin == 'vertical'){
        $data = 'data-skin='.esc_attr($slider_skin).' data-autoplay='.esc_attr($slider_autoplay).' data-autoplay-speed='.esc_attr($slider_autoplay_duration).' data-slider-id='.esc_attr($id).'';
        $orientation = 'vertical';
    }else{
        $autoPlay = ($slider_autoplay == 'no') ? 'false' : 'true';
        $data = 'data-autoplay ='.$autoPlay.' data-autoplay-speed='.esc_attr($slider_autoplay_duration);
        $sliderClass .= 'gallery';
        $slideClass .= 'gallery-cell';
    }

    $sliderheight=($slider_height_mode=='custom')? $slider_height : false;
    if($sliderheight){
        $style="height:". $sliderheight."px";
    }else{
        $style="";
    }

    ?>



    <div class="md-pixflow-slider <?php echo esc_attr($id.' '.$slider_skin.' '.$orientation); ?>  <?php echo esc_attr($slider_indicator_theme); ?>"  style="<?php echo esc_attr($style); ?>">
        <div  <?php echo esc_attr($data); ?>  data-height-mode="<?php echo esc_attr($slider_height_mode) ?>"  class="<?php echo esc_attr($sliderClass) ?>" >
            <?php
            foreach( $slides as $key=>$slide ){
                $subtitle = $slide['slide_subtitle_'.$key];
                $contentType = $slide['slide_content_type_'.$key];
                $subtitleColor = $slide['slide_subtitle_color_'.$key];
                $title = $slide['slide_title_'.$key];
                $titleColor = $slide['slide_title_color_'.$key];
                $desc = $slide['slide_desc_'.$key];
                $descColor = $slide['slide_desc_color_'.$key];
                $image = $slide['slide_image_'.$key];
                if ($image != '' && is_numeric($image)) {
                    $image = wp_get_attachment_image_src($image, 'full') ;
                    $image = (false == $image)?PIXFLOW_PLACEHOLDER_BLANK:$image[0];
                }

                $contentImage = $slide['slide_content_image_'.$key];
                if ($contentImage != '' && is_numeric($contentImage)) {
                    $contentImage = wp_get_attachment_image_src($contentImage, 'full') ;
                    $contentImage = (false == $contentImage)?PIXFLOW_PLACEHOLDER_BLANK:$contentImage[0];
                }
                $overlay = $slide['slide_image_color_'.$key];
                $btn1 = $slide['slide_btn1_'.$key];
                $btn1Kind = $slide['slide_btn1_kind_'.$key];
                $btn1Title = $slide['slide_btn1_title_'.$key];
                $btn1Link = $slide['slide_btn1_link_'.$key];
                $btn1Target = $slide['slide_btn1_target_'.$key];
                $btn1Color = $slide['slide_btn1_color_'.$key];
                $btn1BgColor = $slide['slide_btn1_bg_hover_color_'.$key];
                $btn1TextColor = $slide['slide_btn1_text_hover_color_'.$key];
                $btn1HoverColor = $slide['slide_btn1_hover_color_'.$key];

                $btn2 = $slide['slide_btn2_'.$key];
                $btn2Kind = $slide['slide_btn2_kind_'.$key];
                $btn2Title = $slide['slide_btn2_title_'.$key];
                $btn2Link = $slide['slide_btn2_link_'.$key];
                $btn2Target = $slide['slide_btn2_target_'.$key];
                $btn2Color = $slide['slide_btn2_color_'.$key];
                $btn2BgColor = $slide['slide_btn2_bg_hover_color_'.$key];
                $btn2TextColor = $slide['slide_btn2_text_hover_color_'.$key];
                $btn2HoverColor = $slide['slide_btn2_hover_color_'.$key];

                $subtitleStyle = ('vertical' == $orientation)?'background-color':'color';

                ?>
                <div class="<?php echo esc_attr($slideClass) ?>">
                    <div class="pixflow-slide-bg" style="background-image: url(<?php echo esc_url($image); ?>) "></div>
                    <div class="pixflow-slide-overlay" style="background-color:<?php echo esc_attr($overlay) ?>"></div>
                    <div class="pixflow-slide-container">
                        <?php  if ($contentType == 'text'){ ?>
                            <div class="slide-subtitle" style="<?php echo esc_attr($subtitleStyle); ?>: <?php echo esc_attr($subtitleColor); ?>"><?php echo esc_attr($subtitle); ?></div>
                            <div class="slide-title" style="color: <?php echo esc_attr($titleColor); ?>"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($title));?></div>
                            <div class="slide-desc" style="color: <?php echo esc_attr($descColor); ?>"><?php echo pixflow_js_remove_wpautop($desc); ?></div>
                        <?php } else { ?>
                            <div class="slide-content-image" >
                                <img src="<?php echo esc_url($contentImage) ?>">
                            </div>
                            <div class="slide-subtitle" style="<?php echo esc_attr($subtitleStyle); ?>: <?php echo esc_attr($subtitleColor); ?>"><?php echo esc_attr($subtitle); ?></div>
                        <?php } if('classic' == $orientation){ ?>
                            <div class="btn-container">
                                <?php $btnAlign = ($slider_skin == 'hr-left')?'left':'center'; ?>
                                <?php echo ('yes' == $btn1)?pixflow_buttonMaker($btn1Kind,$btn1Title,'icon-empty',$btn1Link,$btn1Target,$btnAlign,'standard',$btn1Color,$btn1HoverColor,0,$btn1TextColor,$btn1BgColor,array(),false):''; ?>
                                <?php echo ('yes' == $btn2)?pixflow_buttonMaker($btn2Kind,$btn2Title,'icon-empty',$btn2Link,$btn2Target,$btnAlign,'standard',$btn2Color,$btn2HoverColor,0,$btn2TextColor,$btn2BgColor,array(),false):''; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }
            ?>
        </div>
        <div data-slider-id="<?php echo esc_attr($id); ?>" class="pixflow-slider-dots-container" >
            <div class="current-slide-no">01</div>
            <ul class="pixflow-slider-dots">
                <?php foreach( $slides as $key=>$slide ){ ?>
                    <li class="pixflow-slider-dot <?php echo ('1' == $key)?esc_attr('active'):''; ?>" data-slide-no="<?php echo esc_attr($key); ?>">
                        <span class="circle-dot"></span>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <style scoped>

        <?php if($slider_indicator=='off'){
        ?>
        .<?php echo esc_attr($id); ?> .flickity-page-dots{
            display:none;
        }
        <?php
        }
        elseif($slider_indicator=='circle'){
        ?>
        .<?php echo esc_attr($id); ?> .flickity-page-dots .dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        <?php
        }
        ?>


        .<?php echo esc_attr($id); ?> .pixflow-slide{
        <?php if('custom' == $slider_height_mode) {?>
            height: <?php echo esc_attr($slider_height); ?>px;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?>.md-pixflow-slider .pixflow-slide .pixflow-slide-container .slide-subtitle{
        <?php if('yes' == $slider_subtitle_custom_font) {?>
            font-family: <?php echo esc_attr($subtitle_font_family); ?>;
            font-style:  <?php echo esc_attr($subtitle_font_style); ?>;
            font-weight: <?php echo esc_attr($subtitle_font_weight); ?>;
            font-size:   <?php echo esc_attr($slider_subtitle_size).'px'; ?>;
            line-height: <?php echo esc_attr($slider_subtitle_line_height).'px'; ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?>.md-pixflow-slider .pixflow-slide .pixflow-slide-container .slide-title {
        <?php if('yes' == $slider_title_custom_font) {?>
            font-family: <?php echo esc_attr($title_font_family); ?>;
            font-style:  <?php echo esc_attr($title_font_style); ?>;
            font-weight: <?php echo esc_attr($title_font_weight); ?>;
            font-size:   <?php echo esc_attr($slider_title_size).'px' ?>;
            line-height: <?php echo esc_attr($slider_title_line_height).'px'; ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?> .shortcode-btn span{
        <?php if('yes' == $slider_title_custom_font) {?>
            font-family:    <?php echo esc_attr($title_font_family); ?>;
            font-style:     <?php echo esc_attr($title_font_style); ?>;
            font-weight:    <?php echo esc_attr($title_font_weight); ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?> .shortcode-btn .button-standard.fade-oval{
        <?php if('yes' == $slider_title_custom_font) {?>
            font-family:    <?php echo esc_attr($title_font_family); ?>;
            font-style:     <?php echo esc_attr($title_font_style); ?>;
            font-weight:    <?php echo esc_attr($title_font_weight); ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?> .slide-desc{
        <?php if('yes' == $slider_desc_custom_font) {?>
            font-family:    <?php echo esc_attr($desc_font_family); ?>;
            font-style:     <?php echo esc_attr($desc_font_style); ?>;
            font-weight:    <?php echo esc_attr($desc_font_weight); ?>;
        <?php }?>
        }
    </style>

    <script type="text/javascript">
        var $ = jQuery;
        if(typeof pixflow_pixflowSlider == 'function'){
            pixflow_pixflowSlider();
        }
    </script>

    <?php
    return ob_get_clean();
}
