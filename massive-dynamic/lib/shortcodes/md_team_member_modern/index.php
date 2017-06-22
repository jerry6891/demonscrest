<?php
add_shortcode('md_team_member_modern', 'pixflow_get_style_script');
function pixflow_sc_team_member_modern($atts, $content = null)
{
    extract(shortcode_atts(array(
        'team_member_modern_title' => 'Maria Guerra',
        'team_member_modern_subtitle' => 'Finance Manager',
        'team_member_modern_description' => 'Hello, my name is Maria Guerra, I am a Finance Manager at Pixflow.',

        'team_member_modern_texts_color' => '#fff',
        'team_member_modern_hover_color' => '#1c61a8',
        'team_member_modern_image' => PIXFLOW_THEME_IMAGES_URI . "/place-holder.jpg",
        'team_member_modern_icon1' => 'icon-twitter5',
        'team_member_modern_icon2' => 'icon-google',
        'team_member_modern_icon3' => 'icon-instagram',
        'team_member_modern_icon1_url' => 'http://www.twitter.com',
        'team_member_modern_icon2_url' => 'http://www.google.com',
        'team_member_modern_icon3_url' => 'http://www.instagram.com',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_team_member_modern', $atts);
    $id = pixflow_sc_id('teamMemberModern');

    ob_start();

    if (is_numeric($team_member_modern_image)) {
        $image_url = wp_get_attachment_url($team_member_modern_image);
        $image_url = (false == $image_url) ? PIXFLOW_PLACEHOLDER1 : $image_url;
    } else {
        $image_url = $team_member_modern_image;
    }
    if ($image_url == false) {
        $image_url = $team_member_modern_image;
    }

    ?>
    <style scoped>
        .<?php echo esc_attr($id)?> .description{
            font-family:<?php echo (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';?>
        }
        .<?php echo esc_attr($id)?> .title{
            font-family:<?php echo (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';?>
        }

        .<?php echo esc_attr($id)?> .team-member-hover {
            background-color: <?php echo esc_attr($team_member_modern_hover_color)?>;
        }

        .<?php echo esc_attr($id)?> .subtitle {
            color: <?php echo esc_attr($team_member_modern_hover_color)?>;
            font-family:<?php echo (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';?>
        }

    </style>

    <div id="<?php echo esc_attr($id); ?>" class="team-member-modern <?php echo esc_attr($id . ' ' . $animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <div class="team-member-top">
            <div class="team-member-modern-img <?php echo esc_attr($id); ?>">
                <img src="<?php echo esc_url($image_url) ?>">
            </div>
            <div class="lines">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <div class="team-member-hover <?php echo esc_attr($id); ?>"></div>

            <div class="team-member-hover-button <?php echo esc_attr($id); ?>">
                <div class="team-member-center">



            <div class="description <?php echo esc_attr($id); ?>"><?php echo preg_replace("/&lt;(.*?)&gt;/i", '', esc_attr($team_member_modern_description)); ?></div>
            <ul class="social-icons <?php echo esc_attr($id); ?>">
                <?php
                if($team_member_modern_icon1_url != ''){?>

                    <li class="social-icon-1 <?php echo esc_attr($id); ?>">
                        <a href="<?php echo esc_attr($team_member_modern_icon1_url); ?>">
                            <i class="<?php echo esc_attr($team_member_modern_icon1); ?>"></i>
                        </a>
                    </li>
                <?php  }
                if($team_member_modern_icon2_url != ''){?>
                    <li class="social-icon-2 <?php echo esc_attr($id); ?>">
                        <a href="<?php echo esc_attr($team_member_modern_icon2_url); ?>">
                            <i class="<?php echo esc_attr($team_member_modern_icon2); ?>"></i>
                        </a>
                    </li>
                <?php  }
                if($team_member_modern_icon3_url != ''){?>

                    <li class="social-icon-3 <?php echo esc_attr($id); ?>">
                        <a href="<?php echo esc_attr($team_member_modern_icon3_url); ?>">
                            <i class="<?php echo esc_attr($team_member_modern_icon3); ?>"></i>
                        </a>
                    </li>
                <?php }?>

            </ul>
           </div>
        </div>
        </div>

        <div class="team-member-down">

            <div class="title">
                <?php echo esc_attr($team_member_modern_title); ?>
            </div>

            <div class="subtitle">
                <?php echo esc_attr($team_member_modern_subtitle); ?>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            var $ = (jQuery),
                $teamMemberId = $('<?php echo "#" . esc_attr($id) ?>');

            if (typeof pixflow_team_member_modern_hover == 'function') {
                pixflow_team_member_modern_hover('<?php echo esc_attr($id); ?>');

            }

            if (typeof pixflow_team_member_modern == 'function') {

                pixflow_team_member_modern($teamMemberId, "<?php echo esc_attr($team_member_modern_hover_color); ?>");
            }
            if(typeof  pixflow_team_memeber_modern_check_size == 'function' ){
                pixflow_team_memeber_modern_check_size('<?php echo esc_attr($id); ?>');
            }
            <?php pixflow_callAnimation(); ?>
        })
    </script>




    <?php
    return ob_get_clean();

}
?>