<?php
/**
 * Instagram Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_instagram', 'pixflow_get_style_script'); // pixflow_sc_instagram

function pixflow_sc_instagram($atts, $content = null)
{
    $md_client_logo = $md_client_bg = $md_client_general_color = $md_client_text_color = $md_client_text = '';
    extract(shortcode_atts(array(
        'instagram_token_access' => '',
        'instagram_title' => 'Follow on Instagram',
        'instagram_image_number' => "4",
        'instagram_heading' => 'yes',
        'instagram_like' => 'yes',
        'instagram_comment' => 'yes',
        'instagram_general_color' => 'rgb(0,0,0)',
        'instagram_overlay_color' => 'rgba(255,255,255,0.6)',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_instagram',$atts);
    $id = pixflow_sc_id('instagram');
    $redirect_uri = PIXFLOW_THEME_LIB_URI . '/instagram/get_token_access.php';
    $instagram = new pixflow_Instagram(array(
        'apiKey' => 'a0416c7630d74bfb894916fb4c8d0c70',
        'apiSecret' => '9df90946a6c142c9b75e6df51726124c',
        'apiCallback' => 'http://demo2.pixflow.net/instagram-app/redirect.php?redirect_uri=' . urlencode($redirect_uri)
    ));

    wp_enqueue_script('videojs-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/video.min.js'),array(),PIXFLOW_THEME_VERSION,true);

    if (isset($instagram_token_access) && $instagram_token_access!= '') {
        $token = $instagram_token_access;
    } else {
        $redirect_uri = PIXFLOW_THEME_LIB_URI . '/instagram/get_token_access.php';
        $instagram = new pixflow_Instagram(array(
            'apiKey' => 'a0416c7630d74bfb894916fb4c8d0c70',
            'apiSecret' => '9df90946a6c142c9b75e6df51726124c',
            'apiCallback' => 'http://demo2.pixflow.net/instagram-app/redirect.php?redirect_uri=' . urlencode($redirect_uri)
        ));
        $InstagramloginURL = $instagram->getLoginUrl();

        $a='<a href="'.$InstagramloginURL.'">create a token</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('You need to %s for your instagram account first, then use this shortcode.','massive-dynamic'),$a).'</p></div>';

        return $mis;

    }
    // check authentication
    if ($token !== false && $token != '') {
        // store user access token
        $instagram->setAccessToken($token);
        // now we have access to all authenticated user methods
        $images = $instagram->getUserMedia();
        $user = $instagram->getUser();
        $profile['image'] = $user->data->profile_picture;
        $profile['bio'] = $user->data->bio;
        $profile['username'] = $user->data->username;
        $profile['posts'] = $user->data->counts->media;
        $profile['followers'] = $user->data->counts->followed_by;
        $profile['following'] = $user->data->counts->follows;
        $imagesData = $images->data;
    }
    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id) ?> .heading .title,
        .<?php echo esc_attr($id) ?> .heading .username,
        .<?php echo esc_attr($id) ?> .heading .number,
        .<?php echo esc_attr($id) ?> .meta .description,
        .<?php echo esc_attr($id) ?> .overlay-background .description{
            color: <?php echo esc_attr($instagram_general_color) ?>;
        }

        .<?php echo esc_attr($id) ?> .heading .separator{
            background-color: <?php echo esc_attr($instagram_general_color) ?>;
        }

        .<?php echo esc_attr($id) ?> .media .overlay-background{
            background-color: <?php echo esc_attr($instagram_overlay_color) ?>;
        }

        .<?php echo esc_attr($id) ?> .statistic .label {
            color: <?php echo esc_attr(pixflow_colorConvertor($instagram_general_color,'rgba',0.6)); ?>
        }

        .<?php echo esc_attr($id) ?> .statistic .item {
            border-color: <?php echo esc_attr(pixflow_colorConvertor($instagram_general_color,'rgba',0.2)); ?>
        }

        .<?php echo esc_attr($id) ?> .meta .likes,
        .<?php echo esc_attr($id) ?> .meta .comments {
            color: <?php echo esc_attr(pixflow_colorConvertor($instagram_general_color,'rgba',0.7)); ?>
        }

        .<?php echo esc_attr($id) ?> .meta .likes i,
        .<?php echo esc_attr($id) ?> .meta .comments i {
            color: <?php echo esc_attr(pixflow_colorConvertor($instagram_general_color,'rgba',0.5)); ?>
        }
    </style>
    <div class="instagram <?php echo esc_attr($id.' '.$animation['has-animation']) ?>" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php if ('yes' == $instagram_heading) {
            $avatar = $profile['image'];
            $username = $profile['username'];
            ?>
            <div class="heading clearfix">
                <div class="left-aligned clearfix">
                    <div class="avatar" style="background-image: url('<?php echo esc_url($avatar); ?>')"></div>
                    <div class="title-holder">
                        <a href="https://instagram.com/<?php echo esc_attr($username); ?>" target="_blank">
                            <?php if ($instagram_title != '') { ?>
                                <h3 class="title"><?php echo esc_attr($instagram_title); ?></h3>
                                <div class="separator"></div>
                            <?php } ?>
                            <h4 class="username">@<?php echo esc_attr($username); ?></h4>
                        </a>
                    </div>
                </div>
                <div class="right-aligned statistic clearfix">
                    <div class="item">
                        <span class="number"><?php echo esc_attr($profile['posts']); ?></span>
                        <h6 class="label"><?php echo esc_attr__('Posts', 'massive-dynamic'); ?></h6>
                    </div>
                    <div class="item">
                        <span class="number"><?php echo esc_attr($profile['followers']); ?></span>
                        <h6 class="label"><?php echo esc_attr__('Followers', 'massive-dynamic'); ?></h6>
                    </div>
                    <div class="item">
                        <span class="number"><?php echo esc_attr($profile['following']); ?></span>
                        <h6 class="label"><?php echo esc_attr__('Following', 'massive-dynamic'); ?></h6>
                    </div>
                </div>


            </div>
        <?php } ?>
        <div class="photo-list clearfix">
            <?php
            if(isset($images->data) && is_array($images->data)){
                $counter = 1;
                foreach ($images->data as $media) {
                    if($counter > $instagram_image_number){
                        break;
                    }
                    $likes = $media->likes->count;
                    $comments = $media->comments->count;
                    if (!is_null($media->caption)) {
                        $comment = $media->caption->text;
                    }else {
                        $comment ="";
                    }
                    ?>
                    <div id="item<?php echo esc_attr($counter); ?>" class="item">
                        <div class="media">
                            <?php
                            if ($media->type === 'video') {
                                // video
                                $poster = $media->images->low_resolution->url;
                                $source = $media->videos->standard_resolution->url;
                                ?>
                                <div class="video_instagram item-image <?php echo esc_attr($id.$counter) ?>" data-id="<?php echo esc_attr($id.$counter) ?>" data-source="<?php echo esc_url($source) ?>" style="background:url(<?php echo esc_url($poster); ?>)">
                                    <div class="play-btn icon-play-curve" ></div>
                                </div>

                                <?php
                            } else {
                                // image
                                $image = $media->images->low_resolution->url;
                                ?>
                                <div class="item-image" style="background-image: url('<?php echo esc_url($image)?>')"></div>
                            <?php }
                            ?>
                            <div class="overlay-background">
                                <p class="description"><?php echo esc_attr($comment)?></p>
                            </div>
                        </div>
                        <div class="meta clearfix">
                            <?php if(strlen($comment) > 15){
                                $comment = mb_substr($comment,0,15).'...';
                            } ?>
                            <h5 class="description"><?php echo esc_attr($comment)?></h5>
                            <?php if ('yes' == $instagram_comment){ ?> <span class="comments"><i class="icon-comment"></i><?php echo esc_attr($comments) ?> </span> <?php } ?>
                            <?php if ('yes' == $instagram_like){ ?> <span class="likes"><i class="icon-heart"></i><?php echo esc_attr($likes) ?> </span> <?php } ?>
                        </div>
                    </div>
                    <?php
                    $counter++;
                }
            }

            ?>
        </div>
    </div>
    <script type="text/javascript">
        var $ = jQuery;

        if ( typeof pixflow_instagramShortcode == 'function' ){
            pixflow_instagramShortcode();
        }
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}
