<?php
/**
 * Blog Masonry Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_blog_masonry', 'pixflow_get_style_script'); // pixflow_sc_blog_masonry



/*----------------------------------------------------------------
                    Masonry Blog
-----------------------------------------------------------------*/

function pixflow_sc_blog_masonry( $atts, $content = null ){
    $query=$output=$width=$subStr=$style = $col
        =$blog_accent_color=$blog_post_number=$blog_text_accent_color=
    $blog_category=$blog_foreground_color=$blog_background_color=$id=$blog_column=$blog_bg = $blog_post_shadow = '';
    $list=$day=array();
    $i=0;

    extract( shortcode_atts( array(
        'blog_column'            => 'three',
        'blog_category'          => '',
        'blog_post_number'       => '5' ,
        'blog_foreground_color'  => 'rgb(255,255,255)',
        'blog_background_color'  => 'rgb(87,63,203)',
        'blog_accent_color'      => 'rgb(220,38,139)',
        'blog_text_accent_color' => 'rgb(0,0,0)',
        'blog_post_shadow'      => 'rgba(0,0,0,.12)'

    ), $atts ) );

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_blog_masonry',$atts);
    $id = pixflow_sc_id('blog-masonry');

    $arrg = array(
        'category_name'=> $blog_category,
        'posts_per_page' => $blog_post_number,
    );

    $query = new WP_Query($arrg);

    if(is_numeric($blog_bg)){
        $blog_bg =  wp_get_attachment_image_src( $blog_bg, 'pixflow_post-single') ;
        $blog_bg = (false == $blog_bg)?PIXFLOW_PLACEHOLDER1:$blog_bg[0];
    }

    if($blog_column=='three'){
        $width=100/3 ;
        $col = 3;

    }else{
        $width=100/4;
        $col = 4 ;
    }

    ob_start();
    ?>

    <style scoped>

        .<?php echo esc_attr($id); ?> .blog-masonry-container{
            background-color: <?php echo esc_attr($blog_background_color); ?>;
            width:calc(<?php echo esc_attr($width).'%'; ?> - 30px);
            -webkit-box-shadow: 0 1px 21px <?php echo esc_attr($blog_post_shadow); ?>;
            -moz-box-shadow: 0 1px 21px <?php echo esc_attr($blog_post_shadow); ?>;
            box-shadow: 0 1px 21px <?php echo esc_attr($blog_post_shadow); ?>;
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container p,
        .<?php echo esc_attr($id); ?> .blog-masonry-container span,
        .<?php echo esc_attr($id); ?> .blog-masonry-container h1,
        .<?php echo esc_attr($id); ?> .blog-masonry-container a{
            color:<?php echo esc_attr($blog_foreground_color); ?>;
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container span.blog-cat a,
        .<?php echo esc_attr($id); ?> .quote.blog-masonry-container p,
        .<?php echo esc_attr($id); ?> .quote.blog-masonry-container span,
        .<?php echo esc_attr($id); ?> .quote.blog-masonry-container h1,
        .<?php echo esc_attr($id); ?> .quote.blog-masonry-container a{
            color:<?php echo esc_attr($blog_text_accent_color); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-cat{
            background-color:<?php echo esc_attr($blog_accent_color); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-title:hover{
            color:<?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.50)); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .video-img{
            width:100%;
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container.quote{
            background:<?php echo esc_attr($blog_accent_color);?>
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-heart,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share-hover{
            border: 2px solid <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.55)); ?>;
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-heart i,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share i,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share-hover i{
            color:<?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.55)); ?>;
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-like-holder:hover .like-heart,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-share:hover .share,
        {
            background-color: <?php echo esc_attr($blog_foreground_color); ?>
        }
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-count,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-share:hover .share-hover i{
            color: <?php echo esc_attr($blog_background_color); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-like-holder .like-heart,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share{
            background:<?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.20)); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-like-holder .like-heart,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share{
            border:2px solid  <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0)); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share-hover i,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .share i,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-heart i,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-count,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-share:hover .share-hover i
        {
            color:<?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.40)); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-like-holder:hover .like-heart,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-share:hover .share-hover{
            background-color: transparent;
            border:2px solid  <?php echo esc_attr(pixflow_colorConvertor($blog_foreground_color,'rgba',0.5)); ?>;
        }


    </style>

    <div id="<?php echo esc_attr($id) ?>" class="<?php echo esc_attr($id.' '.$animation['has-animation']);?> masonry-blog clearfix " <?php echo esc_attr($animation['animation-attrs']);?>>

        <?php while ($query->have_posts()) {
            $i++;
            $query->the_post();
            global $post;

            if(strlen(get_the_excerpt())>150){
                $subStr = '...';
            }else{
                $subStr='';
            }
            $format = get_post_format( $post->ID );
            if($format==false) $format = 'standard';
            $style='';

            ?>
            <div class="blog-masonry-container <?php echo esc_attr($format);?>" >
                <?php
                if($format=='audio'){
                    $audio=pixflow_extract_audio_info(get_post_meta(get_the_ID(), 'audio-url', true));
                    if($audio != null)
                    {
                        echo pixflow_soundcloud_get_embed($audio['url'],'250');
                    }
                }elseif($format=='gallery'){
                    wp_enqueue_script('flexslider-script');
                    wp_enqueue_style('flexslider-style');
                    $images =get_post_meta( get_the_ID(), 'fg_perm_metadata');
                    $images=explode(',',$images[0]);
                    if(count($images)){ ?>
                        <div class="flexslider">
                            <ul class="slides">
                                <?php
                                $imageSize = 'pixflow_team-member-style2-thumb';
                                if (has_post_thumbnail()) {
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( ),$imageSize);
                                    $thumb = (false == $thumb)?PIXFLOW_PLACEHOLDER1:$thumb[0];
                                    $url = $thumb;
                                    ?>
                                    <li class="images" style="background-image: url('<?php echo esc_url($url); ?>');">
                                    </li>
                                    <?php
                                }
                                foreach($images as $img){
                                    $imgTag = wp_get_attachment_image_src($img, $imageSize);
                                    $imgTag = (false == $imgTag)?PIXFLOW_PLACEHOLDER1:$imgTag[0];
                                    ?>
                                    <li class="images" style="background-image: url('<?php echo esc_url($imgTag); ?>');">
                                    </li>
                                    <?php
                                }?>
                            </ul>
                        </div>
                        <?php
                    }
                }elseif($format=='video'){
                    $videoUrl=get_post_meta( get_the_ID(), 'video-url', true);
                    $findme   = 'vimeo.com';
                    $pos = strpos($videoUrl, $findme);
                    if($pos==false) {
                        $host = 'youtube';
                    }else {
                        $host = 'vimeo';
                    }
                    if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                        $image = get_post_thumbnail_id($post->ID);
                    }else {
                        $image = "";
                    }
                    echo do_shortcode('[md_video md_video_host="'.$host.'" md_video_url_vimeo="'.esc_url($videoUrl).'" md_video_url_youtube="'.esc_url($videoUrl).'" md_video_style="squareImage" md_video_image="'.esc_attr($image).'"]');
                }elseif($format=='standard'){
                    if (has_post_thumbnail()) {
                        $imageSize = 'full';
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( ),$imageSize);
                        $thumb = (false == $thumb)?PIXFLOW_PLACEHOLDER1:$thumb[0];
                        echo '<img class="attachment-post-thumbnail size-post-thumbnail wp-post-image" src="'.esc_attr($thumb).'" />';
                    }
                }elseif($format=='quote') {
                    echo '<img class="quote-img" src="'.PIXFLOW_THEME_IMAGES_URI.'/masonry-quote.png" />';
                }
                ?>
                <div class="blog-masonry-content">
                    <?php if($format!='quote') { ?>
                        <span class="blog-details">
                    <?php
                    $terms = get_the_category($post->ID);
                    $catNames=array();
                    $md_catcounter=0;
                    if($terms)
                        foreach ($terms as $term){
                            $md_catcounter++;
                            if ($md_catcounter<2)
                            {
                                ?>
                                <span class="blog-cat"><a href="<?php echo esc_url( get_category_link( get_cat_ID($term->name)))?>" title='<?php echo esc_attr($term->name) ?>'><?php echo esc_attr($term->name) ?></a></span>
                            <?php }

                        } ?>
                    </span>
                        <?php
                    }
                    $archive_year  = get_the_time('Y');
                    $archive_month = get_the_time('m');
                    $archive_day   = get_the_time('d');

                    ?>
                    <?php if($format=='quote') {?>
                        <span class="blog-date">
                    <i class="px-icon icon-calendar-1 classic-blog-icon"></i> <a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day); ?>"><?php the_time(get_option('date_format')) ?></a>
                </span>
                    <?php }?>

                    <?php if($format!='quote') {?>
                        <a href="<?php the_permalink(); ?>"><h1 class="blog-title"> <?php the_title(); ?></h1></a>
                        <span class="blog-date">
                    <i class="px-icon icon-calendar-1 classic-blog-icon"></i> <a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day); ?>"><?php the_time(get_option('date_format')) ?></a>
                </span>
                    <?php }?>
                    <p class="blog-excerpt"> <?php echo mb_substr(get_the_excerpt(), 0,150).$subStr; ?></p>
                    <?php if($format=='quote') {?>

                        <p class="blog-excerpt"> <?php the_title(); ?></p>

                    <?php }
                    if($format!='quote') {
                        ?>
                        <div class="post-like-holder">
                            <?php echo pixflow_getPostLikeLink( get_the_ID() );?>
                        </div>
                        <?php
                        if ( function_exists('is_plugin_active') && is_plugin_active( 'add-to-any/add-to-any.php' ) ) {
                            if(!get_post_meta( get_the_ID(), 'sharing_disabled', false)){?>
                                <div class="post-share">
                                    <a href="#" class="share a2a_dd"><i class="icon-share3"></i></a>
                                    <a href="#" class="a2a_dd share-hover"><i class="icon-share3"></i></a>
                                </div>
                            <?php  }
                        } ?>


                    <?php } ?>
                </div>
            </div>
        <?php }?>

        <div class="clearfix"></div>
    </div>
    <script>
        var $ = jQuery;

        $(document).ready(function(){
            if(typeof pixflow_blogMasonry == 'function'){
//            $('.<?php //echo esc_attr($id)?>// .blog-masonry-container').each(function(){
//                    var item = $('<div></div>');
//                    item.attr('class',$(this).attr('class'));
//                    item.html($(this).html());
//                    $(this).closest('.masonry-blog').append(item);
//                    $(this).remove();
//            })
                pixflow_blogMasonry('<?php echo esc_attr($id)?>');
            }

        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    wp_reset_postdata();

    return ob_get_clean();

}