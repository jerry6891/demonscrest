<?php
/**
 * Portfolio MultiSize Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_portfolio_multisize', 'pixflow_get_style_script'); // pixflow_sc_portfolio_multisize

require_once(PIXFLOW_THEME_LIB . '/portfolio-walker.php');

// Ajax function to save portfolio
function pixflow_portfolio_size() {
    $nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( '' );

    if ( isset( $_POST['portfolio_size'] ) ) {

        $post_id = $_POST['post_id']; // post id

        if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
            $GLOBALS["super_cache_enabled"]=1;
            wp_cache_post_change( $post_id );
        }

        update_post_meta( $post_id, "_portfolio_size", $_POST['portfolio_size'] );
        return true;
    }
    exit;
}

function pixflow_sc_portfolio_multisize( $atts, $content = null ){

    extract(shortcode_atts(array(
        'multisize_title'           => 'OUR PROJECTS',
        'multisize_meta_position'   => 'inside',
        'multisize_category'        => '',
        'multisize_filters'         => 'yes',
        'multisize_filters_align'   => 'left',
        'multisize_like'            => 'yes',
        'multisize_spacing'         => '0',
        'multisize_filter_color'        => 'rgb(0,0,0)',
        'multisize_text_color'          => 'rgba(191,191,191,1)',
        'multisize_overlay_color'       => 'rgba(0,0,0,0.5)',
        'multisize_frame_color'         => '#fff',
        'multisize_item_number'         => '-1',
        'multisize_load_more'           => 'yes',
        'multisize_button_style'        => 'fade-square',
        'multisize_button_text'         => 'LOAD MORE',
        'multisize_button_icon_class'   => 'icon-plus6',
        'multisize_button_color'        => 'rgba(0,0,0,1)',
        'multisize_button_text_color'   => '#fff',
        'multisize_button_bg_hover_color' => '#9b9b9b',
        'multisize_button_hover_color'  => 'rgb(255,255,255)',
        'multisize_button_size'         => 'standard',
        'multisize_button_padding'      => '0',
        'multisize_detail_target'       => 'popup',
        'multisize_post_count'       => 'no',
        'multisize_counter_color'=>'#ffffff',
        'multisize_counter_background_color'=>'#af72ff',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_portfolio_multisize',$atts);
    //Get the Page ID
    $pid = 0;
    if(is_page())
        $pid = get_the_ID();

    $portfolioId = pixflow_sc_id('portfolio-multisize');

    //Check detail target
    if($multisize_detail_target !='popup' && $multisize_detail_target !='page' )
    {
        $multisize_detail_target = ($multisize_detail_target == 'yes'?'popup':'page');
    }

    if($multisize_detail_target == 'popup'){
        $detailTarget = 'portfolio-popup';
    } else{
        $detailTarget = '';
    }

    //Check the style
    if('inside' != $multisize_meta_position && 'outside' != $multisize_meta_position)
        $multisize_meta_position = 'inside';

    //Item Number
    $items   = max($multisize_item_number, -1);


    //Convert slugs to IDs
    $catArr  = pixflow_slugs_to_ids(explode(',', $multisize_category), 'skills');

    //Show category filter either:
    $catList = '';


    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if ( get_query_var('paged') ) {
        $paged = get_query_var('paged');
    } elseif ( get_query_var('page') ) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }


    $queryArgs = array(
        'post_type'      => 'portfolio',
        'posts_per_page' => $items,
        'paged'          => $paged
    );

    //Taxonomy filter
    if(count($catArr))
    {
        $queryArgs['tax_query'] =  array(
            // Note: tax_query expects an array of arrays!
            array(
                'taxonomy' => 'skills',
                'field'    => 'id',
                'terms'    => $catArr
            ));
    }

    $query = new WP_Query($queryArgs);
    $md_portfolio_count=$query->post_count;

    if(count($catArr) == 0 || count($catArr) > 1)
    {
        if('yes'==$multisize_post_count)
        {
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new PixflowPortfolioWalker(), 'echo' => 0, 'include' => implode(',', $catArr));
            $catList = '<li class="current have-counter"><a data-filter="*" href="#">'.esc_attr__('All Items', 'massive-dynamic').'</a><span class="md_portfolio_counter">'.$md_portfolio_count.'</span></li>';
            $catList .= wp_list_categories($listCatsArgs);
        }
        else
        {
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new PixflowPortfolioWalker(), 'echo' => 0, 'include' => implode(',', $catArr));
            $catList = '<li class="current"><a data-filter="*" href="#">'.esc_attr__('All Items', 'massive-dynamic').'</a></li>';
            $catList .= wp_list_categories($listCatsArgs);
        }

    }

    $filterClass = 'filter';
    ob_start();
    ?>
    <style scoped>

        body:not(.compose-mode) .portfolio.inside .item-wrap.portfolio-popup {
            cursor: url(<?php echo PIXFLOW_THEME_IMAGES_URI."/popup-cursor.png" ?>) 25 25,auto;
        }

        body:not(.compose-mode) .outside .item-image.portfolio-popup{
            cursor :url(<?php echo PIXFLOW_THEME_IMAGES_URI."/popup-cursor.png" ?>) , auto;
        }

        .<?php echo esc_attr($portfolioId)?> .md_portfolio_counter{
            background-color: <?php echo esc_attr($multisize_counter_background_color); ?>;
            color: <?php echo esc_attr($multisize_counter_color); ?>;
        }
        <?php if('left' == $multisize_filters_align){ //filter left aligned ?>
        .<?php echo esc_attr($portfolioId)?> .filter{
            float: left;
        }
        .<?php echo esc_attr($portfolioId)?> .title{
            float: right;
        }
        <?php }elseif('center' == $multisize_filters_align){ //filter center aligned ?>
        .<?php echo esc_attr($portfolioId)?> .filter,
        .<?php echo esc_attr($portfolioId)?> .title{
            float: none;
        }
        .<?php echo esc_attr($portfolioId)?> .heading{
            text-align: center;
        }
        <?php }elseif('right' == $multisize_filters_align){ //filter right aligned ?>
        .<?php echo esc_attr($portfolioId)?> .filter{
            float: right;
        }
        .<?php echo esc_attr($portfolioId)?> .title{
            float: left;
        }
        <?php }

        if('yes' != $multisize_filters){ ?>
        .<?php echo esc_attr($portfolioId)?> .title{
            float: none;
        }
        .<?php echo esc_attr($portfolioId)?> .heading{
            text-align: inherit;
        }
        <?php }?>

        .<?php echo esc_attr($portfolioId)?> .filter a{
            color: <?php echo esc_attr(pixflow_colorConvertor($multisize_filter_color,'rgba',0.5)); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .title,
        .<?php echo esc_attr($portfolioId)?> .filter li.current a{
            color: <?php echo esc_attr(pixflow_colorConvertor($multisize_filter_color,'rgba',1)); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .item-title a,
        .<?php echo esc_attr($portfolioId)?> .item-category,
        .<?php echo esc_attr($portfolioId)?> .like-heart,
        .<?php echo esc_attr($portfolioId)?> .like-count{
            color: <?php echo esc_attr($multisize_text_color); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .overlay-background{
            background-color: <?php echo esc_attr($multisize_overlay_color); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .item-image div{
            background-color: <?php echo esc_attr($multisize_frame_color); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .line{
            background-color: <?php echo esc_attr($multisize_text_color); ?>
        }

        .<?php echo esc_attr($portfolioId)?> .heading{
            padding: 0 <?php echo esc_attr($multisize_spacing)?>px;
        }
        <?php if($multisize_like!='yes'){?>
        .<?php echo esc_attr($portfolioId)?> .md-post-like{
            display:none!important;
        }
        <?php }?>
    </style>
    <div class="portfolio portfolio-multisize gizmo-container <?php echo esc_attr($portfolioId.' '.$multisize_meta_position.' '.$animation['has-animation']); ?>" data-id="<?php echo esc_attr($portfolioId)?>" data-items-padding="<?php echo esc_attr($multisize_spacing)?>" <?php echo esc_attr($animation['animation-attrs'])?>>
        <?php if($multisize_title != '' || (strlen($catList) && 'yes' == $multisize_filters)){ ?>
            <div class="heading clearfix">

                <?php if('' != $multisize_title){ ?>
                    <h3 class="title"><?php echo esc_attr($multisize_title) ?></h3>
                <?php }

                if(strlen($catList) && 'yes' == $multisize_filters){
                    global $md_allowed_HTML_tags;
                    ?>
                    <div class="<?php echo esc_attr($filterClass); ?> <?php echo esc_attr($multisize_post_count) ?>" >
                        <ul>
                            <?php echo wp_kses($catList,$md_allowed_HTML_tags); ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="isotope clearfix portfolio-container">
            <?php while ($query->have_posts()) {
                $query->the_post();

                $terms = get_the_terms( get_the_ID(), 'skills' );

                if('inside' == $multisize_meta_position)
                    pixflow_sc_portfolio_multisize_inside($terms,$pid,$detailTarget);
                else
                    pixflow_sc_portfolio_multisize_outside($terms,$pid,$detailTarget);

            } ?>
        </div>
        <?php if('yes' == $multisize_load_more && $items != -1 ){
            wp_reset_postdata();
            $queryArgs = array (
                'post_type'      => 'portfolio',
                'posts_per_page' =>  $items,
            );
            $query = new WP_Query($queryArgs);
            $ppaged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
            $pmax = $query->max_num_pages;
            $count_posts = wp_count_posts( 'portfolio' )->publish;
            $ppostperpage = abs($items) ;
            $maxPages =  ceil ($count_posts / $ppostperpage)  ;
            $multisize_button_url = '#';
            $multisize_button_target = '_self';
            echo pixflow_buttonMaker($multisize_button_style,$multisize_button_text,$multisize_button_icon_class,$multisize_button_url,$multisize_button_target,'center',$multisize_button_size,$multisize_button_color,$multisize_button_hover_color,$multisize_button_padding,$multisize_button_text_color,$multisize_button_bg_hover_color);
            ?>
            <div class="loadmore-button md-hidden" data-portfolio-id="<?php echo esc_attr($portfolioId); ?>" data-startPage="<?php echo esc_attr($ppaged); ?>" data-maxPages="<?php echo esc_attr($maxPages); ?>" data-nextLink="<?php echo next_posts($pmax, false); ?>" data-loadMoreText="<?php echo esc_attr($multisize_button_text); ?>" data-loadingText="<?php echo esc_attr__('Loading ...','massive-dynamic'); ?>" data-noMorePostText="<?php echo esc_attr__('No More Items','massive-dynamic'); ?>">
                <div class="portfolio-pagination container">
                    <?php
                    if(PIXFLOW_USE_CUSTOM_PAGINATION)
                        pixflow_get_pagination($query);
                    else
                        paginate_links($query);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>

        var $ = jQuery;
        $(function(){
            if ( typeof pixflow_portfolioMultisize == 'function' ){

                $('.<?php echo esc_attr($portfolioId)?> .item').each(function(){
                    var item = $('<div></div>');
                    item.attr('class',$(this).attr('class'));
                    item.attr('data-item_id',$(this).attr('data-item_id'));
                    item.html($(this).html());
                    $(this).closest('.isotope').append(item);
                    $(this).remove();
                })
                pixflow_portfolioMultisize();
                if($('.vc_editor').length) {
                    try{pixflow_portfolioItemsPanel();}
                    catch(e){

                    }
                }
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    wp_reset_postdata();

    return ob_get_clean();

}

/***************** Multisize Inside function ****************/

function pixflow_sc_portfolio_multisize_inside($terms,$pageID,$detailTarget)
{
    if(0 != $pageID)
        $permalink = add_query_arg( 'pnt', $pageID, get_permalink() );
    else
        $permalink = get_permalink();

    $item_id = get_the_ID();
    $portfolio_size = get_post_meta( $item_id, "_portfolio_size", true );
    $portfolio_size = ($portfolio_size == '')?'thumbnail-small':$portfolio_size;
    ?>

    <div data-item_id="<?php echo esc_attr($item_id); ?>" class="portfolio-item item <?php if($terms) { foreach ($terms as $term) { echo "term-$term->term_id "; } } ?> <?php echo esc_attr($portfolio_size); ?>">
        <div class="item-wrap <?php echo esc_attr($detailTarget);?>" >

            <?php

            if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ){
                //Adding thumbnail
                $thumbSize = "pixflow_multisize-thumb";
                $thumbId= get_post_thumbnail_id( get_the_ID() );
                $thumb = wp_get_attachment_image_src( $thumbId , $thumbSize );
                $thumb = (false == $thumb)?PIXFLOW_PLACEHOLDER1:$thumb[0];
                $thumbLarge = wp_get_attachment_image_src( $thumbId,'large');
                $thumbLarge = (false == $thumbLarge)?PIXFLOW_PLACEHOLDER1:$thumbLarge[0];
                ?>
                <div class="item-image" data-src="<?php echo esc_attr($thumbLarge);?>" style="background-image: <?php echo 'url('. esc_url($thumb) .')';?>"></div>
                <?php
            } ?>
            <div class="overlay-background" href="<?php echo esc_url($permalink); ?>">
                <div class="item-meta">
                    <h3 class="item-title <?php echo esc_attr($detailTarget);?>">
                        <a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
                    </h3>
                    <h5 class="item-category"><?php
                        $termNames = array();
                        if($terms)
                            foreach ($terms as $term)
                                $termNames[] = $term->name;


                        echo implode(', ', $termNames);
                        ?>
                    </h5>
                </div>
                <?php echo pixflow_getPostLikeLink( get_the_ID() );?>
            </div>
        </div>
    </div>

    <?php
}

/***************** Multisize Outside function ****************/

function pixflow_sc_portfolio_multisize_outside($terms,$pageID,$detailTarget)
{
    if(0 != $pageID)
        $permalink = add_query_arg( 'pnt', $pageID, get_permalink() );
    else
        $permalink = get_permalink();

    $item_id = get_the_ID();
    $portfolio_size = get_post_meta( $item_id, "_portfolio_size", true );
    $portfolio_size = ($portfolio_size == '')?'thumbnail-small':$portfolio_size;
    ?>

    <div data-item_id="<?php echo esc_attr($item_id); ?>" class="portfolio-item item <?php if($terms) { foreach ($terms as $term) { echo esc_attr("term-$term->term_id "); } } ?> <?php echo esc_attr($portfolio_size); ?>">
        <div class="item-wrap ">
            <?php

            if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ){
                //Adding thumbnail
                $thumbSize = "pixflow_multisize-thumb";
                $thumbId= get_post_thumbnail_id( get_the_ID() );
                $thumb = wp_get_attachment_image_src( $thumbId , $thumbSize );
                $thumb = (false == $thumb)?PIXFLOW_PLACEHOLDER1:$thumb[0];
                $thumbLarge = wp_get_attachment_image_src( $thumbId,'large');
                $thumbLarge = (false == $thumbLarge)?PIXFLOW_PLACEHOLDER1:$thumbLarge[0];
                ?>
                <?php
                if($detailTarget !== "portfolio-popup"){
                    echo '<div class="item-image ' . esc_attr($detailTarget) . '" data-src="' . esc_attr($thumbLarge) . '" style="background-image: url(\'' . esc_url($thumb) . '\');" >';
                    echo '<a class="portfolio-page-mode" href="' . esc_url($permalink) . '"> </a>';
                    echo '<div class="border-top"></div>';
                    echo '<div class="border-right"></div>';
                    echo '<div class="border-bottom"></div>';
                    echo '<div class="border-left"></div>';
                    echo pixflow_getPostLikeLink( get_the_ID() );
                    echo '</div>';

                }
                else{
                    echo '<div class="item-image ' . esc_attr($detailTarget) . '" data-src="' . esc_attr($thumbLarge) . '" style="background-image: url(\'' . esc_url($thumb) . '\');" >';
                    echo '<div class="border-top"></div>';
                    echo '<div class="border-right"></div>';
                    echo '<div class="border-bottom"></div>';
                    echo '<div class="border-left"></div>';
                    echo pixflow_getPostLikeLink( get_the_ID() );
                    echo '</div>';
                }
                ?>
                <?php
            } ?>
            <div class="item-meta">
                <div class="line"></div>
                <h3 class="item-title <?php echo esc_attr($detailTarget);?>">
                    <a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
                </h3>
                <h5 class="item-category"><?php
                    $termNames = array();
                    if($terms)
                        foreach ($terms as $term)
                            $termNames[] = $term->name;
                    echo implode(', ', $termNames);
                    ?>
                </h5>
            </div>
        </div>
    </div>

    <?php
}
