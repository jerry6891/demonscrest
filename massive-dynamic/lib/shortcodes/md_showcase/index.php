<?php
/**
 * ShowCase Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_showcase','pixflow_get_style_script'); // pixflow_sc_showcase

function pixflow_sc_showcase($atts,$content = null){
    $output='';

    extract( shortcode_atts( array(
        'showcase_count'  => 'three' ,
        'showcase_image1' => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'showcase_image2' => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'showcase_image3' => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'showcase_image4' => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'showcase_featured_image' =>PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'showcase_meta1' =>"no",
        'showcase_meta2' =>"no",
        'showcase_meta3' =>"no",
        'showcase_meta4' =>"no",
        'showcase_meta5' =>"no",
        'showcase_title1' =>"title",
        'showcase_title2' =>"title",
        'showcase_title3' =>"title",
        'showcase_title4' =>"title",
        'showcase_title5' =>"title",
        'showcase_subtitle1' =>"subtitle",
        'showcase_subtitle2' =>"subtitle",
        'showcase_subtitle3' =>"subtitle",
        'showcase_subtitle4' =>"subtitle",
        'showcase_subtitle5' =>"subtitle",
        'showcase_border_color1' =>"rgb(255,255,255)",
        'showcase_border_color2' =>"rgb(255,255,255)",
        'showcase_border_color3' =>"rgb(255,255,255)",
        'showcase_border_color4' =>"rgb(255,255,255)",
        'showcase_border_color5' =>"rgb(255,255,255)",
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_skill_style1',$atts);

    $id = pixflow_sc_id('showcase');

    $showcase_count = ( $showcase_count == 'three' ) ? 3 : 5;

    if(is_numeric($showcase_featured_image)){
        $showcase_featured_image =  wp_get_attachment_image_src( $showcase_featured_image, 'full') ;
        $showcase_featured_image = (false == $showcase_featured_image)?PIXFLOW_PLACEHOLDER1:$showcase_featured_image[0];
    }

    ob_start(); ?>

    <div class="carousel <?php echo esc_attr($id.' '.$animation['has-animation']) ?> showcase" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <a class="showcase-feature-image">
            <img class="carousel-shadow" src="<?php echo pixflow_path_combine(PIXFLOW_THEME_IMAGES_URI,'shadow.png')?>">
            <img class="carousel-image" alt="Image Caption" src="<?php echo esc_url($showcase_featured_image) ?>">
            <?php if('yes' == $showcase_meta1){ ?>
                <span class="overlay">
                    <span class="border" style="border-color: <?php echo esc_attr($showcase_border_color1) ?>">
                        <span class="text-container">
                            <h5 class="title"><?php echo esc_attr($showcase_title1) ?></h5>
                            <h6 class="subtitle"><?php echo esc_attr($showcase_subtitle1) ?></h6>
                        </span>
                    </span>
                </span>
            <?php } ?>
        </a>

        <?php
        for($i=1; $i < $showcase_count ; $i++){
            $image_url = ${'showcase_image' . $i};
            if(is_numeric($image_url)){
                $image_url =  wp_get_attachment_image_src( $image_url, 'full') ;
                $image_url = (false == $image_url)?PIXFLOW_PLACEHOLDER1:$image_url[0];
            }
            ?>
            <a>
                <img class="carousel-shadow" src="<?php echo pixflow_path_combine(PIXFLOW_THEME_IMAGES_URI,'shadow.png')?>">
                <img class="carousel-image" alt="Image Caption" src="<?php echo esc_url($image_url)  ?>">
                <?php
                $j = $i + 1 ;
                if('yes' == ${'showcase_meta' . $j}){ ?>
                    <span class="overlay">
                    <span class="border" style="border-color: <?php echo esc_attr(${'showcase_border_color'.$j}) ?>">
                        <span class="text-container">
                            <h5 class="title"><?php echo esc_attr(${'showcase_title'.$j}) ?></h5>
                            <h6 class="subtitle"><?php echo esc_attr(${'showcase_subtitle'.$j}) ?></h6>
                        </span>
                    </span>
                </span>
                <?php } ?>
            </a>
        <?php }?>

    </div>
    <?php
    wp_enqueue_script('showcaseScript',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'jquery.waterwheelCarousel.min.js'),array(),PIXFLOW_THEME_VERSION,true);
    ?>

    <script type="text/javascript">
        var $ = jQuery;
        $(function(){
            if(typeof pixflow_shortcodeScrollAnimation == 'function'){
                pixflow_shortcodeScrollAnimation();
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output = ob_get_clean();
    return $output;
}
