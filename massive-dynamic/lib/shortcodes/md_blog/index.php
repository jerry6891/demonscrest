<?php
/**
 * Blog Calendar Shortcode
 *
 * @author Pixflow
 */

/*Blog*/
add_shortcode('md_blog', 'pixflow_get_style_script'); // pixflow_sc_blog
/*----------------------------------------------------------------
                    Calendar Blog
-----------------------------------------------------------------*/
function pixflow_sc_blog( $atts, $content = null ){
    $query=$output=$blog_bg=$blog_post_number=
    $blog_category=$blog_forground_color=$blog_background_color=$id= '';
    $list=$day=$catNotExist=array();
    $i=0;

    extract( shortcode_atts( array(

        'blog_bg'               =>'',
        'blog_post_number'      =>'5' ,
        'blog_category'         => '',
        'blog_forground_color'  => 'rgb(255,255,255)',
        'blog_background_color' => 'rgb(0,0,0)',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_blog',$atts);
    $id = pixflow_sc_id('blog');
    $categories=explode(",",$blog_category);
    $categories_id=array();
    foreach ($categories as $cat){
        $cat_id = get_cat_ID($cat);
        if($cat_id == 0){
            $catNotExist[] = $cat;
        }else{
            array_push($categories_id,get_cat_ID($cat));
        }
    }


    $arrg =  array(
        'cat'  => $categories_id,
        'posts_per_page' => (int)$blog_post_number,
    ) ;

    $query = new WP_Query($arrg);

    if(is_numeric($blog_bg)){
        $blog_bg =  wp_get_attachment_image_src( $blog_bg, 'full') ;
        $blog_bg = (false == $blog_bg)?PIXFLOW_PLACEHOLDER1:$blog_bg[0];
    }
    ob_start();
    if(count($catNotExist) && '' != $blog_category){
        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('These categories not exist anymore: %s','massive-dynamic'),implode(", ",$catNotExist)).'</p></div>';
        print($mis);
    }
    ?>
    <style scoped>
        .calendar-blog .blog-comment {
            background-image: url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/comment.png");
        }
        .<?php echo esc_attr($id)?>{
            background-image: url('<?php echo esc_attr($blog_bg);?>');
            border-color:<?php echo pixflow_colorConvertor(esc_attr($blog_forground_color),'rgba',0.3); ?>
            background-size: 123%;
        }

        .<?php echo esc_attr($id)?> .blog-container{
            border-color:<?php echo pixflow_colorConvertor(esc_attr($blog_forground_color),'rgba',0.3); ?>
        }

        .<?php echo esc_attr($id)?> .blog-overlay{
            background-color: <?php echo pixflow_colorConvertor(esc_attr($blog_background_color),'rgba',0.8); ?>
        }

        .<?php echo esc_attr($id)?>.calendar-blog p,
        .<?php echo esc_attr($id)?>.calendar-blog h6{
            color:<?php echo pixflow_colorConvertor(esc_attr($blog_forground_color),'rgba',0.7); ?>
        }

        .<?php echo esc_attr($id)?> .blog-container:hover p,
        .<?php echo esc_attr($id) ?> .blog-container:hover h6{
            color:<?php echo pixflow_colorConvertor(esc_attr($blog_forground_color),'rgba',1); ?>
        }


    </style>

    <div class="<?php echo esc_attr($id.' '.$animation['has-animation']);?> calendar-blog" <?php echo esc_attr($animation['animation-attrs']);?>>
        <div class="blog-overlay"></div>
        <?php while ($query->have_posts()) {
            $i++;
            $query->the_post();
            global $post;
            ?>
            <a class="blog-container" href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) {
                    $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'pixflow_post-thumbnail-calendar');
                    $thumbnail_src = (false == $thumbnail_src)?PIXFLOW_PLACEHOLDER_BLANK:$thumbnail_src[0];
                    ?>
                    <div class="image" style='background-image: url("<?php echo esc_url($thumbnail_src); ?>")'></div>
                <?php }?>
                <h6 class="blog-day"><?php echo get_the_time('j'); ?></h6>
                <p class="blog-month"><?php echo get_the_time('F'); ?></p>
                <p class="blog-year"><?php echo get_the_time('Y') ?></p>
                <p class="blog-title"> <?php the_title(); ?></p>
                <p class="blog-details">
                    <span class="blog-cat">
                        <?php
                        $catNames = array();
                        $terms = get_the_category($post->ID);
                        if($terms)
                            foreach ($terms as $term)
                                $catNames[] = $term->name;
                        echo implode(', ', $catNames);
                        ?>
                    </span>
                    <span class="blog-comment"><?php comments_number('0','1','%');?></span>
                </p>

            </a>
        <?php }?>

        <div class="clearfix"></div>
    </div>
    <script>

        var $ = jQuery;
        $(function(){
            if ( typeof pixflow_calendarBlog == 'function' ){
                pixflow_calendarBlog('<?php echo esc_attr($id); ?>',<?php echo esc_attr($i); ?>);
                var doIt;
                $(window).resize(function () {
                    if(doIt){
                        clearTimeout(doIt);
                    }
                    doIt = setTimeout(function(){
                        pixflow_calendarBlog(<?php echo esc_attr($i); ?>);
                    },150)
                });
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    wp_reset_postdata();

    return ob_get_clean();

}