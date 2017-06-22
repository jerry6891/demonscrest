<?php
/*-----------------------------------------------------------------------------------
	Theme Shortcodes
-----------------------------------------------------------------------------------*/
//Generate ID for shortcodes
function pixflow_sc_id($key)
{
    $globalKey = "md_sc_$key";
    $id    = uniqid();
    return esc_attr("$key-$id");
}
// read animation fields and return required values
function pixflow_shortcodeAnimation($shortcode,$atts){

    $animationFields = array('animation'=>'no','animation_speed'=>400,'animation_delay'=>'0','animation_position'=>'center','animation_show'=>'once','animation_easing'=>'Quart.easeInOut');
    foreach($animationFields as $field=>$value){
        $animation[] = shortcode_atts( array(
            $shortcode.'_'.$field => $value,
        ), $atts );
    }

    foreach($animation as $val){
        foreach($val as $k=>$v){
            $k = str_replace($shortcode.'_','',$k);
            $animationValues[$k] = $v;
        }
    }
    $animationClass = $animationAttrs = '';
    if($animationValues["animation"] != 'no'){
        $animationClass = 'has-animation';
        $animationAttrs .= 'data-animation-speed='.$animationValues["animation_speed"].' data-animation-delay='.$animationValues["animation_delay"].' data-animation-position='.$animationValues["animation_position"].' data-animation-show='.$animationValues["animation_show"].' data-animation-easing='.$animationValues['animation_easing'];
    }
    $output['has-animation'] = $animationClass;
    $output['animation-attrs'] = $animationAttrs;
    return $output;
}

// Call Shortcode Animation
function pixflow_callAnimation($script = false){
    ob_start();
    if($script){ ?>
    <script type="text/javascript">
    <?php } ?>
    if ( document.readyState === 'complete' ){
        if(typeof pixflow_shortcodeAnimation == 'function'){
            pixflow_shortcodeAnimation();
        }
        if(typeof pixflow_shortcodeAnimationScroll == 'function'){
            pixflow_shortcodeAnimationScroll();
        }
    }
    <?php if($script){ ?>
    </script>
    <?php }
    return ob_get_flush();
}
/*-----------------------------------------------------------------------------------*/
/*  MD Button
/*-----------------------------------------------------------------------------------*/

function pixflow_buttonMaker( $button_style = 'fade-square',$button_text = 'Read More',$button_icon_class = 'icon-Layers',
                         $button_url='#',$button_target = '_self',$button_align = 'left',$button_size = 'standard',
                         $button_color='#000',$button_hover_color='#fff',$left_right_padding='0',$button_text_color='#fff',
                         $button_hover_bg_color='#000',$animation=array(),$clearfix=true,$gizmoContainer=false) {

    $class = "button ".$button_style;
    if(count($animation)<1){
        $animation['has-animation'] = null;
        $animation['animation-attrs'] = null;
    }
    switch($button_size)
    {
        case 'small':
            $class .=' button-small';
            break;
        case 'standard':
            $class .=' button-standard';
            break;
    }
    $gizmoClass='';
    if($gizmoContainer){
        $gizmoClass="gizmo-container small-gizmo";
    }
    $id = pixflow_sc_id('button');

    ob_start();


    ?>

    <style scoped>

        <?php if($button_align == 'left' || $button_align == 'right') { ?>
            <?php echo esc_attr('#'.$id); ?>{
                float: <?php echo esc_attr($button_align);  ?>;
            }
        <?php } ?>

        /* Fade Square */

        <?php if( strstr($class, 'fade-square') ) {

            if ($button_size == 'standard') {
                $paddingTop =  ($button_icon_class == 'icon-empty') ? 15 : 12;
            } else {
                $paddingTop =  10;
            }

            echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.fade-square{
                padding: <?php echo esc_attr($paddingTop).'px '. esc_attr((int)$left_right_padding+27);?>px;
            }

            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.fade-square{
                padding:<?php echo esc_attr($paddingTop).'px '. esc_attr((int)$left_right_padding+21);?>px;
            }

            <?php echo esc_attr('.'.$id); ?>.fade-square{
                color: <?php echo esc_attr($button_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.fade-square:hover{
                color: <?php echo esc_attr($button_hover_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.fade-square:hover{
                background-color: <?php echo esc_attr($button_color); ?>;
                border-color: <?php echo esc_attr($button_color); ?>;
            }

        <?php } ?>

        /* Fade & Fill Oval */

        <?php if( strstr($class, 'fade-oval') || strstr($class,'fill-oval')) {

            $btnName  = (strstr($class, 'fade-oval'))?'.fade-oval':'.fill-oval';

            if ($button_size == 'standard'){
                $paddingTop =  ($button_icon_class == 'icon-empty') ? 17 : 14;
            }else{
                $paddingTop =  ($btnName == '.fade-oval') ? 10 : 11;
            }

            echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard<?php echo esc_attr($btnName)?>{
                padding: <?php echo esc_attr($paddingTop).'px '.esc_attr((int)$left_right_padding+24);?>px;
            }

            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small<?php echo esc_attr($btnName)?>{
                padding: <?php echo esc_attr($paddingTop).'px '. esc_attr((int)$left_right_padding+15);?>px;
            }

            <?php echo esc_attr('.'.$id).' '.esc_attr($btnName); ?>{
                color: <?php echo esc_attr($button_color); ?>;
            }

             <?php if (strstr($class, 'fade-oval')){ ?>
                <?php echo esc_attr('.'.$id); ?>.fade-oval{
                    color: <?php echo esc_attr($button_color); ?>;
                }

                <?php echo esc_attr('.'.$id); ?>.fade-oval:hover{
                    background-color: <?php echo esc_attr($button_color); ?>;
                    border-color: <?php echo esc_attr($button_color); ?>;
                    color: <?php echo esc_attr($button_hover_color); ?>;
                }
            <?php } else{?>
                <?php echo esc_attr('#'.$id); ?>.shortcode-btn .fill-oval{
                    background-color: <?php echo esc_attr($button_color) ?>;
                    color: <?php echo esc_attr($button_text_color) ?>;
                    border: none;
                }

                <?php echo esc_attr('#'.$id); ?>.shortcode-btn .fill-oval:hover{
                    background-color: <?php echo esc_attr($button_hover_bg_color) ?>;
                    color: <?php echo esc_attr($button_hover_color) ?>;
                    border: none;
                }
            <?php } ?>


        <?php } ?>


        /* Slide */

        <?php if( strstr($class, 'slide') ) { ?>

            <?php echo esc_attr('.'.$id); ?>.slide{
                color: <?php echo esc_attr($button_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.slide span{
                color: <?php echo esc_attr($button_hover_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.slide:hover .button-icon{
                color: <?php echo esc_attr($button_hover_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.slide:hover{
                background-color: <?php echo esc_attr($button_color); ?>;
                border-color: <?php echo esc_attr($button_color); ?>;
            }

        <?php } ?>

        /* Come In */

        <?php if( strstr($class, 'come-in') || strstr($class,'fill-rectangle') ) {
            if ($button_size == 'standard'){
              if (strstr($class, 'come-in')){
                $paddingTop =  ($button_icon_class == 'icon-empty') ? 15 : 12;
              }else
                $paddingTop =  ($button_icon_class == 'icon-empty') ? 18 : 15;

            }else{
                 $paddingTop =  (strstr($class, 'come-in')) ? 10 : 12;
             }
         ?>
            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.come-in,
            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.fill-rectangle{
                padding: <?php echo esc_attr($paddingTop).'px '. esc_attr((int)$left_right_padding+32);?>px;
            }

            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.come-in,
            <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.fill-rectangle{
                padding: <?php echo esc_attr($paddingTop).'px '. esc_attr((int)$left_right_padding+29);?>px;
            }

            <?php echo esc_attr('.'.$id); ?>.come-in,
            <?php echo esc_attr('.'.$id); ?>.fill-rectangle{
                color: <?php echo esc_attr($button_color); ?>;
            }

        <?php if( strstr($class, 'come-in')){ ?>

            <?php echo esc_attr('.'.$id); ?>.come-in:after{
                background-color: <?php echo esc_attr($button_color); ?>;
            }

            <?php echo esc_attr('.'.$id); ?>.come-in:hover span,
            <?php echo esc_attr('.'.$id); ?>.come-in:hover .button-icon{
                color: <?php echo esc_attr($button_hover_color); ?>;
            }
        <?php }else{ ?>
            <?php echo esc_attr('.'.$id); ?>.fill-rectangle{
                background-color: <?php echo esc_attr($button_color); ?>;
                color: <?php echo esc_attr($button_text_color) ?>;
                border: none;
            }

            <?php echo esc_attr('.'.$id); ?>.fill-rectangle:hover{
                background-color: <?php echo esc_attr($button_hover_bg_color); ?>;
                color: <?php echo esc_attr($button_hover_color); ?>;
                border: none;
            }


        <?php } ?>

        <?php } ?>

        /* Animation */

        <?php
        if( strstr($class, 'animation') ){
            $button_color = pixflow_colorConvertor($button_color, 'rgb');
        ?>
        <?php echo esc_attr('#'.$id); ?>.shortcode-btn{
            overflow:hidden;
        }
        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.animation{
            padding: 12px <?php echo esc_attr((int)$left_right_padding+26);?>px 12px <?php echo esc_attr((int)$left_right_padding+35);?>px;
        }
        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.animation {
            padding: 11px <?php echo esc_attr((int)$left_right_padding+28);?>px;
        }

        <?php echo esc_attr('.'.$id); ?>.animation:after{
            background-color : <?php echo esc_attr($button_color); ?>;
        }

        <?php echo esc_attr('.'.$id); ?>.animation{
            color: <?php echo pixflow_colorConvertor($button_color,'rgba',.7); ?>;
        }

        <?php echo esc_attr('.'.$id); ?>.animation:hover{
            color: <?php echo esc_attr(pixflow_colorConvertor($button_color,'rgba', 1)); ?>;
            border-color: <?php echo esc_attr(pixflow_colorConvertor($button_color,'rgba', 1)); ?>;
        }

        <?php } ?>

        /* Flash Animate */

        <?php if( strstr($class, 'flash-animate') ){
            $paddingTop =  ($button_icon_class == 'icon-empty') ? 14 : 12;
        ?>

        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.flash-animate{
            padding: <?php echo esc_attr($paddingTop) . 'px '.esc_attr((int)$left_right_padding+13);?>px 10px <?php echo esc_attr((int)$left_right_padding+23);?>px;
        }

        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.flash-animate{
            padding: 12px <?php echo esc_attr((int)$left_right_padding+13);?>px 12px <?php echo esc_attr((int)$left_right_padding+23);?>px;
        }

        <?php echo esc_attr('.'.$id); ?>.flash-animate{
            color : <?php echo esc_attr($button_color); ?>;
        }

        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-standard.flash-animate:hover{
            padding-right: <?php echo esc_attr((int)$left_right_padding+30);?>px;
        }

        <?php echo esc_attr('#'.$id); ?>.shortcode-btn .button-small.flash-animate:hover{
            padding-right: <?php echo esc_attr((int)$left_right_padding+29);?>px;
        }



        <?php } ?>
		

    </style>

    <div id="<?php echo esc_attr($id);?>" class="shortcode-btn <?php echo esc_attr($gizmoClass); ?> <?php echo esc_attr($animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <?php if( strstr($class, 'fade-square') || strstr($class, 'fade-oval') ||strstr($class, 'fill-oval') || strstr($class, 'slide') || strstr($class, 'come-in') || strstr($class, 'fill-rectangle') )
        { ?>

            <a class="<?php echo esc_attr($class); echo ' ' . esc_attr($id); ?>" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>" >
                <?php if ($button_icon_class != 'icon-empty') { ?>
                    <i class="button-icon <?php echo esc_attr($button_icon_class); ?>"></i>
                <?php } ?>
                <span>
                    <?php echo esc_attr($button_text); ?>
                </span>
            </a>

        <?php } else { ?>

            <a class="<?php echo esc_attr($class); echo ' ' . esc_attr($id); ?>" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>" >
                <span>
                    <?php echo esc_attr($button_text); ?>
                </span>
                <?php if ($button_icon_class != 'none'){ ?>
                    <i class="button-icon <?php echo esc_attr($button_icon_class); ?>"></i>
                <?php } ?>
            </a>

        <?php } ?>

    </div> <!-- End wrap button -->
    <?php if(true == $clearfix){ ?>
    <div class="clearfix"></div>
    <?php } ?>
    <?php if($button_align == 'center'){ ?>
    <script>

        "use strict";

        var $ = (jQuery),
            $button = $('#<?php echo esc_attr($id) ?>');

        $button.parents('.wpb_wrapper').css({'text-align':'center'});

    </script>

    <?php }?>


    <?php if (strstr($class, 'slide') ) { ?>
        <script>

            "use strict";

            var $ = (jQuery),
                $btnIdSlide = $('<?php echo "." . esc_attr($id) ?>');

            if ( $btnIdSlide.length )
                $btnIdSlide.attr("data-width", "<?php echo "." . esc_attr($id) ?>");

            if ( typeof pixflow_btnSlide == 'function' )
            {
                pixflow_btnSlide( "<?php echo esc_attr($id) ?>" );
            }

        </script>
    <?php } ?>

    <?php
    return ob_get_clean();
}


add_action( 'wp_ajax_nopriv_pixflow_portfolio_size', 'pixflow_portfolio_size_ajax' );
add_action( 'wp_ajax_pixflow_portfolio_size', 'pixflow_portfolio_size_ajax' );

function pixflow_portfolio_size_ajax(){
    require_once ('md_portfolio_multisize/index.php');
    pixflow_portfolio_size();
}