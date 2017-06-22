<?php
/**
 * Article Box Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_article_box', 'pixflow_get_style_script'); // pixflow_sc_md_article_box

function pixflow_sc_article_box( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'article_image'                   => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'article_title'                   => 'Unique Element',
        'article_text'                    => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed',
        'article_icon'                    => 'icon-file-tasks-add',
        'article_overlay_color'           => 'rgb(48,71,103)',
        'article_text_color'              => 'rgb(255,255,255)',
        'article_icon_color'              => 'rgb(150,223,92)',
        'article_read_more_text'          => 'VIEW MORE',
        'article_read_more_link'          => '#',
        'article_target'                  => '_blank',
        'article_height'                     => '345',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_article_box',$atts);
    $id = pixflow_sc_id('article_box');

    $image = $article_image;
    if($image != '' && is_numeric($image)){
        $image = wp_get_attachment_image_src( $image,'full') ;
        $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
    }

    $top=esc_attr($article_height)-70;
    ob_start(); ?>

    <style scoped>
        <?php echo '.'.esc_attr($id); ?>{
            height:<?php echo esc_attr($article_height); ?>px;
        }
        <?php echo '.'.esc_attr($id); ?> .article-overlay{
            top: <?php echo esc_attr($top); ?>px;
        }
        <?php echo '.'.esc_attr($id); ?> .article-box-img{
                                             background-image: url(<?php echo esc_attr($image); ?>);
                                         }

        <?php echo '.'.esc_attr($id); ?> .article-overlay{
                                             background-color:<?php echo esc_attr($article_overlay_color); ?>
                                         }
        <?php echo '.'.esc_attr($id); ?> .article-box-icon,
                                         <?php echo '.'.esc_attr($id); ?> .read-more{
                                             color:<?php echo esc_attr($article_icon_color); ?>
                                         }
        <?php echo '.'.esc_attr($id); ?> .article-box-title,
                                         <?php echo '.'.esc_attr($id); ?> .article-box-description{
                                             color:<?php echo esc_attr($article_text_color); ?>
                                         }

    </style>

    <div class="article-box <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="article-box-img"></div>
        <div class="article-overlay">
            <div class="article-box-content">
                <div class="title-icon">
                    <?php if( isset($article_icon) && 'icon-empty' != $article_icon ){ ?>
                        <div class="article-box-icon <?php echo esc_attr($article_icon) ?>"></div>
                    <?php }?>

                    <?php if( isset($article_title) && '' != $article_title ){ ?>
                        <h3 class="article-box-title"> <?php echo esc_attr($article_title); ?> </h3>
                    <?php } ?>
                </div>
                <?php if( isset($article_text) && '' != $article_text ){
                    if(strlen(esc_attr($article_text))>290){
                        $text=mb_substr(esc_attr($article_text),0,290).'...';
                    }else{
                        $text=esc_attr($article_text);
                    }
                    ?>
                    <p class="article-box-description"><?php print($text); ?></p>
                <?php } ?>

                <?php if( isset($article_read_more_text) && '' != $article_read_more_text ){ ?>
                    <br/>
                    <a class="read-more" href="<?php echo esc_attr($article_read_more_link); ?>" target="<?php echo esc_attr($article_target); ?>"> <?php echo esc_attr($article_read_more_text); ?><i class="read-more-icon px-icon icon-angle-right"></i> </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        "use strict";
        var $ = jQuery;
        $(function(){
            if(typeof pixflow_articleBox == 'function'){
                pixflow_articleBox();
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}