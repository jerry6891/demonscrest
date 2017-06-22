<?php
/**
 * Team member Classic Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_team_member_classic', 'pixflow_get_style_script'); // pixflow_sc_teamMemberClassic

function pixflow_sc_team_member_classic($atts, $content = null)
{
    extract(shortcode_atts(array(
        'team_member_classic_title'       => 'John Parker!',
        'team_member_classic_subtitle'    => 'writer',
        'team_member_classic_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta, mi ut facilisis ullamcorper, magna risus vehicula augue, eget faucibus.',

        'team_member_classic_texts_color' => '#fff',
        'team_member_classic_hover_color' => 'rgba(11, 171, 167, 0.85)',
        'team_member_classic_image'       => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",

        'team_member_social_icon1'       => 'icon-facebook2',
        'team_member_social_icon2'       => 'icon-twitter5',
        'team_member_social_icon3'       => 'icon-google',
        'team_member_social_icon4'       => 'icon-dribbble',
        'team_member_social_icon5'       => 'icon-instagram',

        'team_member_social_icon1_url'   => 'http://www.facebook.com',
        'team_member_social_icon2_url'   => 'http://www.twitter.com',
        'team_member_social_icon3_url'   => 'http://www.google.com',
        'team_member_social_icon4_url'   => 'http://www.dribbble.com',
        'team_member_social_icon5_url'   => 'http://www.instagram.com',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_team_member_classic',$atts);

    $id = pixflow_sc_id('teamMemberClassic');

    ob_start();

    wp_enqueue_script('team-member-classic-js',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'sliphover.min.js'),array(),PIXFLOW_THEME_VERSION,true);

    if( is_numeric($team_member_classic_image) ){
        $image_url =  wp_get_attachment_url( $team_member_classic_image );
        $image_url = (false == $image_url)?PIXFLOW_PLACEHOLDER1:$image_url;

    }
    else{
        $image_url = $team_member_classic_image;
    }

    if ( $image_url == false){
        $image_url = $team_member_classic_image;
    }

    ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <div class="team-member-classic">

            <!-- team member content -->

            <div class="content"

                 data-image="<?php echo esc_url($image_url); ?>"

                 data-color="<?php echo esc_attr($team_member_classic_texts_color); ?>"

                 data-caption='

                    <!-- Top position -->
                    <div class="<?php echo esc_attr($id); ?>-topPos">

                        <h3 class="title">
                            <?php echo esc_attr($team_member_classic_title); ?>
                        </h3>

                        <h4 class="subtitle">
                            <?php echo esc_attr($team_member_classic_subtitle); ?>
                        </h4>

                    </div>

                    <!-- Bottom position -->
                    <div class="teammember-classic <?php echo esc_attr($id); ?>-bottomPos" >

                        <div class="description">
                            <?php echo mb_substr(preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($team_member_classic_description)),0 , 200); echo strlen(preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($team_member_classic_description))) > 100 ? '...' : ''; ?>
                        </div>

                        <ul class="social-icons">

                            <li>
                                <a href="<?php echo esc_attr($team_member_social_icon1_url); ?>">
                                    <i class="<?php echo esc_attr($team_member_social_icon1); ?>"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo esc_attr($team_member_social_icon2_url); ?>">
                                    <i class="<?php echo esc_attr($team_member_social_icon2); ?>"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo esc_attr($team_member_social_icon3_url); ?>">
                                    <i class="<?php echo esc_attr($team_member_social_icon3); ?>"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo esc_attr($team_member_social_icon4_url); ?>">
                                    <i class="<?php echo esc_attr($team_member_social_icon4); ?>"></i>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo esc_attr($team_member_social_icon5_url); ?>">
                                    <i class="<?php echo esc_attr($team_member_social_icon5); ?>"></i>
                                </a>
                            </li>

                        </ul>

                    </div> <!-- End bottom position -->

                '>

            </div>

        </div>

    </div> <!-- End team member classic -->


    <script>

        var $ = (jQuery),
            $teamMemberId = $('<?php echo "#".esc_attr($id) ?>');

        $teamMemberId.attr('data-bgColor', "<?php echo esc_attr($team_member_classic_hover_color); ?>");

        if ( typeof pixflow_teamMemberClassic == 'function' ){
            pixflow_teamMemberClassic( $teamMemberId, "<?php echo esc_attr($team_member_classic_hover_color); ?>" );
        }

        if ( typeof pixflow_teamMemberClassicHover == 'function' ){
            pixflow_teamMemberClassicHover( "<?php echo esc_attr($id) ?>", "<?php echo esc_attr($image_url); ?>", "<?php echo esc_attr($team_member_classic_texts_color); ?>" );
        }

        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
