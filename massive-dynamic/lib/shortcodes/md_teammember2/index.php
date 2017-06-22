<?php
/**
 * Team Member Carousel Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_teammember2', 'pixflow_get_style_script'); // pixflow_sc_teamMemberCarousel

function pixflow_sc_teammember2($atts, $content = null)
{
    $output = $team_member_style2_num = '';

    wp_enqueue_style('slick-theme', pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'slick-theme.min.css'), array(), PIXFLOW_THEME_VERSION);
    wp_enqueue_style('slick-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'slick.min.css'), array(), PIXFLOW_THEME_VERSION);
    wp_enqueue_script('slick-js', pixflow_path_combine(PIXFLOW_THEME_JS_URI,'slick.min.js'), array(), PIXFLOW_THEME_VERSION, true);

    extract( shortcode_atts( array(
        'team_member_style2_num'         => '8',
        'team_member_style2_texts_color' => '#393939',
        'team_member_style2_hover_color' => '#fff',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_teammember2',$atts);
    $k=1;
    for($i=1; $i<=$team_member_style2_num; $i++){



        $slides[$i] = shortcode_atts( array(
            'team_member_style2_name_'.$i        => 'Member'.$i,
            'team_member_style2_position_'.$i    => 'Manager',
            'team_member_style2_description_'.$i => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper.',

            'team_member_style2_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",

            'team_member_style2_social_icon_'.$k       => 'icon-facebook2',
            'team_member_style2_social_icon_url_'.$k++ => 'http://www.facebook.com',

            'team_member_style2_social_icon_'.$k       => 'icon-twitter5',
            'team_member_style2_social_icon_url_'.$k++ => 'http://www.twitter.com',

            'team_member_style2_social_icon_'.$k       => 'icon-google',
            'team_member_style2_social_icon_url_'.$k++ => 'http://www.google.com',

            'team_member_style2_social_icon_'.$k       => 'icon-dribbble',
            'team_member_style2_social_icon_url_'.$k++ => 'http://www.dribbble.com',

            'team_member_style2_social_icon_'.$k     => 'icon-instagram',
            'team_member_style2_social_icon_url_'.$k++ => 'http://www.instagram.com',
        ), $atts );
    }
    $id = pixflow_sc_id('teammember_style2');
    $func_id = uniqid();

    $output .= '<div id="'.$id.'" class="wrap-teammember-style2 clearfix '.esc_attr($animation["has-animation"]).'" '.esc_attr($animation["animation-attrs"]).'>';

    $output .= '<ul class="slides">';
    $teamindex=1;
    foreach( $slides as $key=>$slide ){

        $name = $slide['team_member_style2_name_'.$key];
        $position = $slide['team_member_style2_position_'.$key];
        $description = $slide['team_member_style2_description_'.$key];

        $generateLi = "";

        for($i=1; $i<=5; $i++) {
            if(isset($slide['team_member_style2_social_icon_url_' . $teamindex])){
                if($slide['team_member_style2_social_icon_url_' . $teamindex] != '' && $slide['team_member_style2_social_icon_' . $teamindex] != ''){
                    $generateLi .= '<li> <a href="' . $slide['team_member_style2_social_icon_url_' . $teamindex] . '"> <i class="' . $slide['team_member_style2_social_icon_' . $teamindex] . '"></i> </a> </li>';
                }
            }
            $teamindex++;


        }


        $image = $slide['team_member_style2_image_'.$key];

        if ($image != '' && is_numeric($image)) {
            $image = wp_get_attachment_image_src($image, 'pixflow_team-member-style2-thumb') ;
            $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
        }

        $output .= ' <li> ';

        $output .= ' <div class="wrap"> <div class="teammember-image" style="background-image: url(\' '. esc_attr($image) .' \') "></div> ';

        $output .= ' <div class="teammember-hover">'.

            '<p class="teammember-description">'. preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($description)) .'</p>'.
            '<ul class="social-icons">'. $generateLi. '</ul>'.

            '</div> ';

        $output .= ' </div> <div class="meta"> <div class="name">'. $name .'</div> <div class="position">'. $position .'</div> </div>';

        $output .= ' </li> '; // end wrap

    }

    $output .= ' </ul> ';

    $output .= ' </div> ';

    ob_start();
    ?>

    <style scoped>

        .wrap-teammember-style2 .slick-arrow.slick-next {
            background-image: url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/tm2-next.png");
        }
        .wrap-teammember-style2 .slick-arrow.slick-prev {
            background-image: url('<?php echo PIXFLOW_THEME_URI; ?>/assets/img/tm2-back.png');
        }

        #<?php echo esc_attr($id) ?> .meta .name{
            color: <?php echo esc_attr($team_member_style2_texts_color); ?>;
        }

        #<?php echo esc_attr($id) ?> .meta .position{
                                         color: <?php echo esc_attr(pixflow_colorConvertor($team_member_style2_texts_color,'rgba', .5)); ?>;
                                     }

        #<?php echo esc_attr($id) ?> .teammember-hover > p,
        #<?php echo esc_attr($id) ?> .teammember-hover ul li a{
                                         color: <?php echo esc_attr($team_member_style2_hover_color); ?>;
                                     }

    </style>

    <script type="text/javascript">

        var $ = jQuery,
            slickTtrackWidth, CTO;

        $('document').ready(function() {

            if (typeof pixflow_teammemberCarousel == 'function' /*&& typeof slick == 'function'*/) {
                pixflow_teammemberCarousel("<?php echo esc_attr($id) ?>");
            }

        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;

}
