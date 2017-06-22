<?php
/**
 * Blog Classic Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_blog_classic', 'pixflow_get_style_script'); // pixflow_sc_blog_classic

/***********************************************************
 *                    Classic Blog
 **********************************************************/
function pixflow_sc_blog_classic( $atts, $content = null ){
    $query=$output =$content
        =$blog_category=$blog_post_number=$blog_text_color=$blog_category_color=
    $blog_category_align=$blog_category_author= $blog_shadow_color  ='';
    $list=$day=array();
    $i=$subStr=0;
    global $paged,$post;
    extract( shortcode_atts( array(
        'blog_category'        => '',
        'blog_title_color'      => 'rgb(68,37,153)',
        'blog_text_color'      => 'rgb(163,163,163)' ,
        'blog_category_color'  => 'rgb(52,202,161)',
        'blog_category_align'  => 'left',
        'blog_category_author' => 'yes',
        'blog_post_number'     => '5',
        'blog_title_size'      => '47',
        'blog_shadow_color'    => 'rgba(0,0,0,.12)'
    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_blog_classic',$atts);
    $id = pixflow_sc_id('blog-classic');


    if ( get_query_var('paged') ) {
        $paged = get_query_var('paged');
    } elseif ( get_query_var('page') ) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }

    $arrg = array(
        'category_name'=> $blog_category,
        'posts_per_page' => $blog_post_number,
        'paged'          => $paged,
    );

    $query = new WP_Query($arrg);

    ob_start();
    ?>

    <style scoped>
        .classic-blog .loop-post-content .post-meta .post-date .symbol {
            background: transparent url(<?php echo PIXFLOW_THEME_IMAGES_URI."/date-icon.png" ?>) no-repeat center;
        }

        .classic-blog .loop-post-content .post-share .share {
            background-image: url(<?php echo PIXFLOW_THEME_IMAGES_URI."/share-blog.png" ?>);
        }

        .classic-blog .loop-post-content .post-comment {
            background-image: url(<?php echo PIXFLOW_THEME_IMAGES_URI."/blog-chat.png" ?>);
        }

        <?php if($blog_category_align=='center'){ ?>
        .<?php echo esc_attr($id); ?>.classic-blog .loop-post-content,
        .<?php echo esc_attr($id); ?>.classic-blog .post-categories,
        .<?php echo esc_attr($id); ?>.classic-blog .post-title,
        .<?php echo esc_attr($id); ?>.classic-blog .post-info {
            text-align: center;
        }
        <?php }else{ ?>
        .<?php echo esc_attr($id); ?>.classic-blog .loop-post-content,
        .<?php echo esc_attr($id); ?>.classic-blog .post-categories,
        .<?php echo esc_attr($id); ?>.classic-blog .post-title,
        .<?php echo esc_attr($id); ?>.classic-blog .post-info {
            text-align: left;
        }
        <?php }?>

        .<?php echo esc_attr($id); ?> .loop-post-content{
            -webkit-box-shadow: 0 1px 21px <?php echo esc_attr($blog_shadow_color); ?>;
            -moz-box-shadow: 0 1px 21px <?php echo esc_attr($blog_shadow_color); ?>;;
            box-shadow: 0 1px 21px <?php echo esc_attr($blog_shadow_color); ?>;
        }

        .<?php echo esc_attr($id); ?>.classic-blog .post-title a
        {
            color: <?php echo esc_attr($blog_title_color) ?>;
        }

        .<?php echo esc_attr($id); ?>.classic-blog .continue-reading{
            color: <?php echo esc_attr(pixflow_colorConvertor( $blog_title_color,'rgba',.6)) ?>
        }

        .<?php echo esc_attr($id); ?>.classic-blog .continue-reading:hover{
            background-color: <?php echo esc_attr($blog_title_color) ?>;
            color:#ffffff;
        }

        .<?php echo esc_attr($id); ?>.classic-blog .post-categories a{
            background-color: <?php echo esc_attr($blog_category_color); ?> ;
            border: 2px solid <?php echo esc_attr($blog_category_color); ?>;
        }

        .<?php echo esc_attr($id); ?>.classic-blog .post-categories a:hover{
            color: <?php echo esc_attr($blog_text_color); ?>;
            border-color:<?php echo esc_attr($blog_text_color); ?> ;
            background-color: #FFFFFF;
        }

        .<?php echo esc_attr($id); ?>.classic-blog blockquote{
            color: <?php echo esc_attr(pixflow_colorConvertor($blog_category_color,'rgba',0.16)); ?> ;
        }

        .<?php echo esc_attr($id); ?>.classic-blog  .post-author,
        .<?php echo esc_attr($id); ?>.classic-blog  .post-date a,
        .<?php echo esc_attr($id); ?>.classic-blog blockquote .name{
            color: <?php echo esc_attr($blog_text_color,'rgba','0.6'); ?>;
        }
        .<?php echo esc_attr($id); ?>.classic-blog p{
            color: <?php echo esc_attr($blog_text_color); ?>;
        }
        <?php ?>
        .<?php echo esc_attr($id); ?>.classic-blog .post-title a{
            font-size: <?php echo esc_attr($blog_title_size);?>px;
        }
    </style>

    <div class="<?php echo esc_attr($id.' '.$animation['has-animation']);?> classic-blog classic-blog-<?php echo esc_attr($blog_category_align);?>" <?php echo esc_attr($animation['animation-attrs']);?>>

        <?php while ($query->have_posts()) {
        $subStr=0;
        $i++;
        $query->the_post();

        $format = get_post_format( $post->ID );
        if($format==false) $format = 'standard';

        ?>
        <div class="loop-post-content enblog-classic-container <?php echo esc_attr($format);?>" >

            <?php

            if($format=='audio'){ ?>
                <?php
                $audio=pixflow_extract_audio_info(get_post_meta(get_the_ID(), 'audio-url', true));
                ?>
                <div class="post-media">

                    <?php if($blog_category_author=='yes'){ ?>
                        <div class="post-author-meta">
                                    <span class="author-image">
                                   <?php $authorId =  get_the_author_meta('ID');
                                   echo get_avatar( $authorId, 50 ); ?>
                                    </span>
                            <p class="post-author">by:<?php the_author_posts_link(); ?></p>
                        </div>
                    <?php } ?>
                    <?php
                    if($audio != null)
                    {
                        ?>
                        <div class="post-media audio-frame">
                            <?php
                            echo pixflow_soundcloud_get_embed($audio['url'],'460');
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                </div> <!-- post media -->
            <?php }
            elseif($format=='gallery'){
                wp_enqueue_script('flexslider-script');
                wp_enqueue_style('flexslider-style');
                ?>
                <div class="post-media">
                    <?php if($blog_category_author=='yes'){ ?>
                        <div class="post-author-meta">
                               <span class="author-image">
                               <?php $authorId =  get_the_author_meta('ID');
                               echo get_avatar( $authorId, 50 ); ?>
                                </span>
                            <p class="post-author">by: <?php the_author_posts_link(); ?></p>
                        </div>
                    <?php } ?>
                    <?php
                    $images = get_post_meta( get_the_ID(), 'fg_perm_metadata');
                    $images = (isset($images[0]))?explode(',',$images[0]):array();
                    if(count($images))
                    { ?>
                        <div class="flexslider">
                            <ul class="slides">
                                <?php
                                $imageSize = 'pixflow_post-single';
                                foreach($images as $img){
                                    $imgTag = wp_get_attachment_image_src($img, $imageSize);
                                    $imgTag = (false == $imgTag)?PIXFLOW_PLACEHOLDER1:$imgTag[0];
                                    ?>
                                    <li class="images" style="background-image: url('<?php echo esc_url($imgTag); ?>');" onclick='window.location="<?php the_permalink(); ?>"'>
                                    </li>
                                    <?php
                                }?>
                            </ul>
                        </div>
                        <?php
                    }?>
                </div> <!-- post media -->

            <?php }
            elseif($format=='video'){
                $videoUrl=get_post_meta( get_the_ID(), 'video-url', true);
                $findme   = 'vimeo.com';
                $pos = strpos($videoUrl, $findme);
                if($pos==false) {
                    $host = 'youtube';
                }else {
                    $host = 'vimeo';
                }
                ?>

                <div class="post-media">
                    <?php if($blog_category_author=='yes'){ ?>
                        <div class="post-author-meta">
                               <span class="author-image">
                               <?php $authorId =  get_the_author_meta('ID');
                               echo get_avatar( $authorId, 50 ); ?>
                                </span>
                            <p class="post-author">by: <?php the_author_posts_link(); ?></p>
                        </div>
                    <?php } ?>
                    <div class="post-image" title="<?php echo esc_attr(get_the_title()); ?>" >
                        <?php
                        if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                            $image = get_post_thumbnail_id($post->ID);
                        }else {
                            $image = "";
                        }

                        echo do_shortcode('[md_video md_video_host="'.$host.'" md_video_url_vimeo="'.$videoUrl.'" md_video_url_youtube="'.$videoUrl.'" md_video_style="squareImage" md_video_image="'.$image.'"]'); ?>
                    </div>
                </div> <!-- post media -->
            <?php }
            elseif($format=='standard'){
                //Post thumbnail
                if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) { ?>
                    <div class="post-media">
                        <?php if($blog_category_author=='yes'){ ?>
                            <div class="post-author-meta">
                                   <span class="author-image">
                                       <?php $authorId =  get_the_author_meta('ID');
                                       echo get_avatar( $authorId, 50 ); ?>
                                    </span>
                                <p class="post-author">by: <?php the_author_posts_link(); ?></p>
                            </div>
                        <?php } ?>
                        <div class="post-image" title="<?php echo esc_attr(get_the_title()); ?>" >
                            <?php $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
                            <?php $image_url = (false == $image_url)?PIXFLOW_PLACEHOLDER1:$image_url[0]; ?>
                            <a href="<?php the_permalink(); ?>"><img src='<?php echo esc_url($image_url); ?>'></a>
                        </div>
                    </div> <!-- post media -->
                <?php }

            }
            elseif($format=='quote'){?>
            <blockquote class="px-icon icon-quotes-left">
                <?php
                $content = strip_shortcodes(get_the_content(esc_attr__('keep reading', 'massive-dynamic')));
                print("<p>".$content."</p>");
                ?>
                <p class="name"><?php the_title(); ?></p>
            </blockquote>
        </div>
    <?php } ?>

        <?php
        if($format!='quote'){?>
            <h6 class="post-categories">
                <?php
                $terms = get_the_category($post->ID);
                $catNames=array();
                if($terms)
                    foreach ($terms as $term)
                        $catNames[] = "<a href=".esc_url( get_category_link( get_cat_ID($term->name)))." title='".$term->name."'>".$term->name."</a>" ;
                echo implode(', ', $catNames);
                ?>
            </h6>
            <?php
            $archive_year  = get_the_time('Y');
            $archive_month = get_the_time('m');
            $archive_day   = get_the_time('d');
            ob_start();
            ?>
            <div class="post-info-container ">

                <div class="post-info ">
                    <p class="post-date"><i class="px-icon icon-clipboard3 classic-blog-icon"></i> <a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day); ?>"><?php the_time(get_option('date_format')) ?></a></p>
                </div>

            </div>
            <?php
            $postInfoHtml = ob_get_clean();
            global $md_allowed_HTML_tags;
            if($blog_category_align == 'left'){
                echo wp_kses($postInfoHtml,$md_allowed_HTML_tags);
            }
            ?>
            <div class="post-meta">
                <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php
                if($blog_category_align == 'center'){
                    echo wp_kses($postInfoHtml,$md_allowed_HTML_tags);
                }
                ?>
            </div>
        <?php }

        if($format!='quote'){
        if(get_the_excerpt() != '' )
        {
            $content = get_the_excerpt();
        }
        else
        {
            $content = apply_filters('the_content',strip_shortcodes(get_the_content()));
        }
        $subStr=1;
        if(strlen($content) > 800 ){
            $content= mb_substr($content,0,800).'...';
        }?>
        <div class="classic-blog-content">
            <p><?php echo wp_kses($content,$md_allowed_HTML_tags); ?></p>
        </div>
        <?php if($subStr){ ?>
        <a href="<?php the_permalink(); ?>" class="continue-reading"><?php _e('Continue Reading','massive-dynamic'); ?> <i class="continue-reading-arrow px-icon icon-arrow-right2"></i> <a>
                <?php } ?>


                <div class="sharing clearfix">
                    <?php
                    if ( function_exists('is_plugin_active') && is_plugin_active( 'add-to-any/add-to-any.php' ) ) {
                        if(!get_post_meta( get_the_ID(), 'sharing_disabled', false)){?>
                            <div class="post-share">
                                <a href="#" class="share a2a_dd"></a>
                                <a href="#" class="a2a_dd share-hover"></a>
                            </div>
                            <span class="sepretor">|</span>
                        <?php  }
                    } ?>

                    <div class="post-comment-holder">
                        <a class="post-comment" href="<?php comments_link(); ?>"></a>
                        <a class="post-comment-hover" href="<?php comments_link(); ?>">
                            <span><?php comments_number('0','1','%');?></span>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
    </div>

    <?php

}

}

    // We check to see if the shortcode used in a front page or normal page so we can decide about pagination permalink structure
    if(is_front_page()){
        pixflow_get_pagination($query,'',false);
    }
    else{
        pixflow_get_pagination($query,'',true);
    }

    wp_reset_postdata();
    ?>
    </div>
    <script type="text/javascript">
        var $ = jQuery;

        $(function(){
            if ( typeof pixflow_blogPage == 'function' ){
                pixflow_blogPage();
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $return = ob_get_clean();
    return $return;
}