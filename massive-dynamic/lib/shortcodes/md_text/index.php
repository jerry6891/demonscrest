<?php
/**
 * Text Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_text', 'pixflow_get_style_script' ); //pixflow_sc_text

function pixflow_sc_text( $atts, $content = null ){
    $output=$md_text_style = $md_text_solid_color = $md_text_gradient_color = $md_text_alignment= $md_text_description_bottom_space ='';
    $md_text_title_separator = $md_text_title = $md_text_title_size = $desc_font_weigth=$md_text_letter_space= '';
    $md_text_hover_letter_space = $md_text_use_title_custom_font = $md_text_title_google_fonts = $md_text_content_size= '';
    $md_text_use_desc_custom_font = $md_text_use_button = $md_text_button_style=$left_right_padding= '';
    $md_text_button_text = $md_text_button_icon_class = $md_text_button_color = $md_text_button_hover_color= '';
    $md_text_button_size = $md_text_button_url = $md_text_button_target = $desc_font = $title_font=$desc_font_family= '';
    $md_text_content_color = $md_text_title_line_height = $md_text_title_bottom_space=$title_font_family=$ss=$fontList = '';
    $md_text_separator_width = $md_text_separator_height = $md_text_seperator_color = $md_text_separator_bottom_space ='';
    $desc_font_weigth=$desc_font_style=$title_font_weight=$title_font_weight=$title_font_style=$title_font_style=array();

    extract( shortcode_atts( array(
        'md_text_style'                => 'solid' ,
        'md_text_solid_color'          => 'rgba(20,20,20,1)',
        'md_text_gradient_color'       => '',
        'md_text_image_bg'             => '',
        'md_text_alignment'            => 'left',
        'md_text_title_separator'      => 'yes',
        'md_text_separator_width'      => '110',
        'md_text_separator_height'     => '5',
        'md_text_separator_color'     => 'rgb(0, 255, 153)',
        'md_text_separator_bottom_space' => '10',
        'md_text_description_bottom_space'=> '25',
        'md_text_number'               => '1',
        'md_text_title1'               => 'Text Shortcode',
        'md_text_title2'               => 'Text Shortcode',
        'md_text_title3'               => 'Text Shortcode',
        'md_text_title4'               => 'Text Shortcode',
        'md_text_title5'               => 'Text Shortcode',
        'md_text_title_size'           => '32',
        'md_text_letter_space'         => '0',
        'md_text_hover_letter_space'   => '0',
        'md_text_use_title_custom_font'=> 'no',
        'md_text_title_google_fonts'   => 'font_family:Roboto%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900|font_style:200%20light%20regular%3A200%3Anormal',
        'md_text_content_size'         => '14',
        'md_text_content_color'        => 'rgba(20,20,20,1)',
        'md_text_use_desc_custom_font' => 'yes',
        'md_text_desc_google_fonts'    => 'font_family:Roboto%3A100%2C100italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal',
        'md_text_use_button'           => 'no',
        'md_text_button_style'         => 'fade-oval',
        'md_text_button_text'          => 'READ MORE',
        'md_text_button_icon_class'    => 'icon-chevron-right',
        'md_text_button_color'         => 'rgba(0,0,0,1)',
        'md_text_button_text_color'    => 'rgba(255,255,255,1)',
        'md_text_button_bg_hover_color'=> 'rgb(0,0,0)',
        'md_text_button_hover_color'   => 'rgb(255,255,255)',
        'md_text_button_size'          => 'standard',
        'left_right_padding'           => 0,
        'md_text_button_url'           => '#',
        'md_text_button_target'        => '_self',
        'md_text_easing'               => 'cubic-bezier(0.215, 0.61, 0.355, 1)',
        'md_text_title_line_height'    => '40',
        'md_text_title_bottom_space'   => '10',
        'md_text_desc_line_height'     => '21',
        'md_text_use_title_slider'     => 'no',
        'align'     => 'center',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_text',$atts);
    if($md_text_number > 1){
        wp_enqueue_script('textillate-js',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'jquery.textillate.min.js'),array(),PIXFLOW_THEME_VERSION,true);
    }

    $titles[1] = $md_text_title1;
    $titles[2] = $md_text_title2;
    $titles[3] = $md_text_title3;
    $titles[4] = $md_text_title4;
    $titles[5] = $md_text_title5;

    $titles[1] = pixflow_checkBase64($titles[1]);
    if(preg_match('/pixFLow_editor/' , $titles[1])){
        $titles[1] = str_replace('pixFLow_editor' , '"' , $titles[1]);
    }

    if('yes' == $md_text_use_title_custom_font  && $md_text_title_google_fonts != ''){
        $md_text_title_google_fonts = str_replace("font_family:", "", $md_text_title_google_fonts);
        $arr = explode("%3A", $md_text_title_google_fonts, 2);
        $title_font = $arr[0];
        $title_font = str_replace("%20", " ", $title_font);
    }
    if('yes' == $md_text_use_desc_custom_font && $md_text_desc_google_fonts != ''){
        $md_text_desc_google_fonts = str_replace("font_family:", "", $md_text_desc_google_fonts);
        $arr = explode("%3A", $md_text_desc_google_fonts, 2);
        $desc_font = $arr[0];
        $desc_font = str_replace("%20", " ", $desc_font);
    }

    if('yes' == $md_text_use_title_custom_font || 'yes' == $md_text_use_desc_custom_font){
        // check theme typography font
        // TODO : Compare font weights

        if('yes' == $md_text_use_title_custom_font) {
            //if(true == $title_font_load){
            $md_text_title_google_fonts = str_replace("font_family:", "", $md_text_title_google_fonts);
            $fontList = $md_text_title_google_fonts ? $md_text_title_google_fonts . '|' : ''  ;
            $title_font1 = uniqid();
            wp_enqueue_style('vc_google_fonts_' . $title_font1, '//fonts.googleapis.com/css?family=' . $fontList );
            //}
            // Extract Title font style
            if (isset($md_text_title_google_fonts[0])) {
                $md_text_title_google_fonts = explode('|', rawurldecode($md_text_title_google_fonts));
	            if(isset($md_text_title_google_fonts[1])){
		            $title_font_style = explode(':', $md_text_title_google_fonts[1]);
		            $title_font_style = explode(' ', $title_font_style[1]);
		            $title_font_family = $title_font;
		            $title_font_weight = $title_font_style[0];
		            $title_font_style = $title_font_style[1];
	            }
            }
        }
        if('yes' == $md_text_use_desc_custom_font){
            //if(true == $desc_font_load){
            wp_enqueue_style( 'vc_google_fonts_' . $desc_font, '//fonts.googleapis.com/css?family=' . $md_text_desc_google_fonts );
            //}
            if (isset($md_text_desc_google_fonts[0])) {
                $md_text_desc_google_fonts = explode('|', rawurldecode($md_text_desc_google_fonts));
                if(count($md_text_desc_google_fonts)>1){
                    $desc_font_style = explode(':', $md_text_desc_google_fonts[1]);
                    $desc_font_style = explode(' ', $desc_font_style[1]);
                    $desc_font_family = $desc_font;
                    $desc_font_weigth = $desc_font_style[0];
                    $desc_font_style = $desc_font_style[1];
                }
            }
        }
    }
    $font_load = '' ;
    $font_list_result = pixflow_extract_font_families( $titles[1] . $content ) ;
   if ( $font_list_result !== false ) {
       foreach ($font_list_result as $font){
           if( strpos( $font_load , $font) === false){
               $font_load .= $font. '|' ;
           }
       }
       $font_id = uniqid();
       wp_enqueue_style('vc_google_fonts_' . $font_id , '//fonts.googleapis.com/css?family=' . $font_load );
   }

    $desc_font_weigth  = (!is_string($desc_font_weigth))?'normal':$desc_font_weigth;
    $desc_font_style  = (!is_string($desc_font_style))?'normal':$desc_font_style;
    $id = esc_attr(pixflow_sc_id('md_text_style'));
    $md_text_gradient_color = str_replace('``','"',$md_text_gradient_color);

    if(is_numeric($md_text_image_bg)){
        $md_text_image_bg =  wp_get_attachment_url( $md_text_image_bg ) ;
        $md_text_image_bg = (false == $md_text_image_bg)?PIXFLOW_PLACEHOLDER_BLANK:$md_text_image_bg;
    }
    // Change Title style to solid if browser is not chrome
    if('Google Chrome' != pixflow_detectBrowser($_SERVER['HTTP_USER_AGENT'])){
        $md_text_style = 'solid';
    }

    $noTitleClass =  ($md_text_number == '1' && ($md_text_title1 == '' ||  $titles[1] == '<div><br data-mce-bogus="1"></div>'   ||  $titles[1] == '<div></div>'  ) ) ? ' without-title':'';
    $noDescription = ($content == '' || strip_tags($content) == '&nbsp;' || $content=='<p>&nbsp;<br /></p>' || $content=='<p>&nbsp;</p>' )? ' without-content' : '';

    ob_start();
    ?>
    <style scoped>

        /* Solid Style*/
        <?php if($md_text_style=='solid'){?>
        .<?php echo esc_attr($id); ?> .md-text-title {
            color: <?php echo esc_attr($md_text_solid_color); ?>;
        }
        <?php }
        /* gradiant style */
        elseif($md_text_style=='gradient'){?>
        .<?php echo esc_attr($id); ?> .md-text-title,
        .<?php echo esc_attr($id); ?> .md-text-title > div{
        <?php echo esc_attr(pixflow_makeGradientCSS($md_text_gradient_color)); ?>
            background-clip: text;
            -webkit-background-clip: text;
            text-fill-color: transparent;
            -webkit-text-fill-color: transparent;
            padding-bottom: 3px;
        }
        <?php }
        /* Image style */
         else{?>
        .<?php echo esc_attr($id); ?> .md-text-title,
        .<?php echo esc_attr($id); ?> .md-text-title > div{
            background: url("<?php echo esc_url($md_text_image_bg); ?>") no-repeat 100% 100%;
            background-clip: text;
            -webkit-background-clip: text;
            text-fill-color: transparent;
            -webkit-text-fill-color: transparent;
            padding-bottom: 3px;
        }
        <?php } ?>

        .<?php echo esc_attr($id); ?>{
            text-align:     <?php echo esc_attr($md_text_alignment); ?>;

        }
        .<?php echo esc_attr($id); ?> .md-text-title *{
            line-height:    <?php echo esc_attr($md_text_title_line_height); ?>px;
        <?php if($md_text_use_title_custom_font=='yes') {?>
            font-family:    <?php echo esc_attr($title_font_family); ?>;
            font-style:     <?php echo esc_attr($title_font_style); ?>;
            font-weight:    <?php echo esc_attr($title_font_weight); ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?> .md-text-title {
            font-size:      <?php echo esc_attr($md_text_title_size); ?>px;
            line-height:    <?php echo esc_attr($md_text_title_line_height); ?>px;
            letter-spacing: <?php echo esc_attr($md_text_letter_space); ?>px;
            margin-bottom: <?php echo esc_attr($md_text_title_bottom_space); ?>px;
            transition: all .3s <?php echo esc_attr($md_text_easing); ?> ;
        <?php if($md_text_use_title_custom_font=='yes') {?>
            font-family:    <?php echo esc_attr($title_font_family); ?>;
            font-style:     <?php echo esc_attr($title_font_style); ?>;
            font-weight:    <?php echo esc_attr($title_font_weight); ?>;
        <?php }?>
        }
        .<?php echo esc_attr($id); ?> .md-text-title:not(.title-slider):hover{
            letter-spacing:  <?php echo esc_attr($md_text_hover_letter_space); ?>px;
        }
        .<?php echo esc_attr($id); ?> .md-text-title-separator{
            margin-bottom:<?php echo esc_attr($md_text_separator_bottom_space) ?>px ;
            width: <?php echo esc_attr($md_text_separator_width).'px' ?>;
            border-top: <?php echo esc_attr($md_text_separator_height).'px' ?> solid <?php echo esc_attr($md_text_separator_color); ?>;
        <?php if($md_text_alignment=='left' ){ ?>
            margin-left: 0;
            margin-right: auto;
        <?php }elseif($md_text_alignment=='right' ){?>
            margin-right: 0;
            margin-left: auto;
        <?php }elseif($md_text_alignment=='center' ){ ?>
            margin-left: auto;
            margin-right: auto;
        <?php }?>
        }

        .<?php echo esc_attr($id) ?> .md-text-content{
            margin-bottom: <?php echo esc_attr($md_text_description_bottom_space); ?>px;
        }

        .<?php echo esc_attr($id); ?> .md-text-content p{
            color:          <?php echo esc_attr($md_text_content_color);?>;
            font-size:      <?php echo esc_attr($md_text_content_size); ?>px;
            line-height:    <?php echo esc_attr($md_text_desc_line_height); ?>px;
        <?php if($md_text_use_desc_custom_font=='yes') {?>
            font-family:    <?php echo esc_attr($desc_font_family); ?>;
            font-style:     <?php echo esc_attr($desc_font_style); ?>;
            font-weight:    <?php echo esc_attr($desc_font_weigth); ?>;
        <?php }?>
        }

        <?php if($md_text_alignment == 'center'){?>
        .<?php echo esc_attr($id); ?> .md-text-content p{
            margin: 0  auto;
        }
        <?php } ?>


    </style>
    <?php $align = trim($align); ?>
    <div class="md-text-container <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?> wpb_wrapper wpb_md_text_wrapper ui-md_text" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="md-text gizmo-container">

            <?php if($md_text_number>1 && $md_text_use_title_slider == 'yes') { ?>

                <div class="md-text-title title-slider <?php echo esc_attr($noTitleClass) ?>">
                    <ul class="texts">
                        <?php for ($i=1; $i<=$md_text_number; $i++) { ?>
                            <li class="text-title"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($titles[$i])); ?></li>
                        <?php } ?>
                    </ul>
                </div>

            <?php } else {
                ?>
                <?php
                $titles[1] = strip_tags($titles[1] , '<a><h1><h2><h3><h4><h5><h6><div><b><i><span><strong><i><em><b><br>');
                ?>
                <div class="md-text-title inline-editor-title <?php echo esc_attr($noTitleClass) ?>"><?php echo pixflow_detectNewLines($titles[1]) ; ?></div>
                <?php
            } ?>

            <?php if($md_text_title_separator=='yes'){?>
                <div class="full_width_sep" >  <div class="md-text-title-separator"></div></div>
            <?php } ?>

            <?php if($content != '') {
                $content = str_replace('<p></p>', '', $content);
                $content = pixflow_detectBasetext($content);
                $content = pixflow_detectNewLines($content);
            }?>
            <div class="md-text-content inline-editor <?php echo esc_attr($noDescription) ?>" ><?php
                echo pixflow_detectPTags(pixflow_js_remove_wpautop($content . false ));
                ?></div>
            <?php //} ?>

            <?php if($md_text_use_button=='yes'){?>
                <div class="md-text-button">
                    <?php echo pixflow_buttonMaker($md_text_button_style,$md_text_button_text,$md_text_button_icon_class,$md_text_button_url,$md_text_button_target,$md_text_alignment,$md_text_button_size,$md_text_button_color,$md_text_button_hover_color,$left_right_padding,$md_text_button_text_color,$md_text_button_bg_hover_color); ?>
                </div>
            <?php } ?>

        </div>

    </div>
    <script type="text/javascript">
        if(typeof pixflow_title_slider == 'function'){
            pixflow_title_slider();
        }
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output.=ob_get_clean();

    return $output;

}
