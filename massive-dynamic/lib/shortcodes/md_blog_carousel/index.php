<?php
/**
 * Blog Carousel Shortcode
 *
 * @author Pixflow
*/

add_shortcode('md_blog_carousel', 'pixflow_get_style_script'); // pixflow_sc_blog_carousel
/*----------------------------------------------------------------
                    Carousel Blog
-----------------------------------------------------------------*/

function pixflow_sc_blog_carousel( $atts, $content = null ){
    extract( shortcode_atts( array(
        'blog_category'          => '',
        'blog_post_number'       => '5' ,
        'carousel_autoplay'=>'no',
        'blog_foreground_color'  => 'rgb(0,0,0)',
        'blog_background_color'  => 'rgb(255,255,255)',
        'blog_date_color'=>'rgb(84,84,84)',
    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_blog_carousel',$atts);
    $id = pixflow_sc_id('blog_carousel');
    $carousel_autoplay=$carousel_autoplay=='no'?'false':'true';

    $arrg = array(
        'category_name'=> $blog_category,
        'posts_per_page' => $blog_post_number,
    );

    $query = new WP_Query($arrg);
    ob_start();
    ?>

    <style scoped>

        .<?php echo esc_attr($id); ?> .post-wrap.is-selected .post-content-container {
            -webkit-box-shadow: 0px 9px 32px -1px rgba(0,0,0,0.2);
            -moz-box-shadow: 0px 9px 32px -1px rgba(0,0,0,0.2);
            box-shadow: 0px 9px 32px -1px rgba(0,0,0,0.2);
            margin-top:-10px;
        }

        .<?php echo esc_attr($id); ?> .post-wrap .post-content-container{
            background-color: <?php echo esc_attr($blog_background_color); ?>
        }

        .<?php echo esc_attr($id); ?> .vertical-separator{
            background-color: <?php echo esc_attr(pixflow_colorConvertor($blog_date_color,'rgba',0.70)); ?>;
        }

        .<?php echo esc_attr($id); ?> .post-title a h2,
        .<?php echo esc_attr($id); ?> .post-title h2,
        .<?php echo esc_attr($id); ?> .post-author-name,
        .<?php echo esc_attr($id); ?> .post-author-name a,
        .<?php echo esc_attr($id); ?> .post-description p,
        .<?php echo esc_attr($id); ?> .post-date-day,
        .<?php echo esc_attr($id); ?> .post-date-month{
            color: <?php echo esc_attr($blog_foreground_color); ?>
        }

        .<?php echo esc_attr($id); ?> .post-date-day,
        .<?php echo esc_attr($id); ?> .post-date-month{
            color: <?php echo esc_attr($blog_date_color); ?>
        }

        .<?php echo esc_attr($id); ?> .post-description p{
            color: <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.70)); ?>;
        }

        .<?php echo esc_attr($id); ?> .separator{
            background-color: <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.90)); ?>;
        }

        .<?php echo esc_attr($id); ?> .post-separator{
            background-color: <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.30)); ?>;
        }
    </style>


    <div id="<?php echo esc_attr($id) ?>" class="post-carousel-container <?php echo esc_attr($id.' '.$animation['has-animation']);?> "  <?php echo esc_attr($animation['animation-attrs']);?> data-flickity-options='{
                "contain": true,
                "prevNextButtons": false,
                "pageDots": true,
                "initialIndex": 1,
                "autoPlay": <?php echo esc_attr($carousel_autoplay) ?>,
                "wrapAround": true,
                "pauseAutoPlayOnHover": false,
                "selectedAttraction": 0.045,
                "friction: 0.5",
                "percentPosition": false,
            }'>
        <?php
        while ($query->have_posts()) {

            $query->the_post();
            global $post;
            $title=get_the_title();
            $format=get_post_format();

            if(strlen($title)>30){
                $title=mb_substr($title, 0,20)."...";
            }

            $exc=get_the_excerpt();
            if(strlen($exc)>150){
                $exc = mb_substr($exc, 0,130)."...";
            }
            global $md_allowed_HTML_tags ;
            ?>
            <div class="post-wrap">
                <div class="post-content-container">
                    <?php
                    if('quote'!=$format){
                        ?>
                        <div class="post-title <?php echo esc_attr($format); ?>" ><a href="<?php the_permalink(); ?>"><h2 class="blog-title"><?php echo esc_attr($title); ?></h2></a></div>
                        <?php
                    }
                    else{
                        ?>
                        <div class="post-title <?php echo esc_attr($format); ?>" ><h2 class="blog-title"><?php echo esc_attr($title); ?></h2></div>
                        <?php
                    }
                    ?>
                    <div class="post-description"><p><?php echo wp_kses($exc,$md_allowed_HTML_tags ); ?></p></div>
                    <div class="post-separator"></div>
                    <div class="post-meta-container">
                        <div class="post-author-image"><?php echo get_avatar(get_the_author_meta('ID'), 26 ); ?></div>
                        <div class="post-author-name">By <?php echo get_the_author_meta('display_name'); ?></div>
                    </div>
                    <div class="vertical-separator"></div>
                </div>

                <div class="post-date">
                    <div class="post-date-day"> <?php the_time( 'j', '', '', true ); ?> </div>
                    <div class="post-date-month"><?php the_time( 'F', '', '', true ); ?></div>
                </div>
            </div>




            <?php
        }
        ?>
    </div>
    <script>
        "use strict";
        var $ = (jQuery);
        $(function() {
            if(typeof $.prototype.flickity == 'function') {
                setTimeout(function(){
                    $('.<?php echo esc_attr($id); ?>').not('.flickity-enabled').flickity({
                        contain: true,
                        prevNextButtons: false,
                        pageDots: true,
                        initialIndex: 1,
                        autoPlay: <?php echo esc_attr($carousel_autoplay); ?>,
                        wrapAround: true,
                        pauseAutoPlayOnHover: false,
                        selectedAttraction: 0.045,
                        friction: 0.5,
                        percentPosition: false,
                    });
                    setTimeout(function(){
                        $('.<?php echo esc_attr($id); ?>').flickity('resize');
                    },1000);
                },100);
            }
            pixflow_fixflickityheight(false,$('.<?php echo esc_attr($id); ?>'));
        });
    </script>
    <?php
    return ob_get_clean();
}