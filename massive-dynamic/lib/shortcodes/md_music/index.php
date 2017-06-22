<?php
/**
 * Music Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_music', 'pixflow_get_style_script'); // pixflow_sc_music

function pixflow_sc_music( $atts, $content = null ) {

    $output = $music_num = '';
    $tracks = array();
    extract( shortcode_atts( array(
        'music_num'          => 7,
        'music_album'        => 'Audio Jungle',
        'music_artist'       => 'PR_MusicProductions',
        'music_texts_color'  => 'rgb(80,80,80)',
        'music_played_color' => 'rgb(106, 222, 174)',
        'music_alignment'    => 'right-music-panel',
        'music_image'        =>  PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'align'        =>  'center',
    ), $atts ) );

    if ($music_image != '' && is_numeric($music_image)){
        $music_image = wp_get_attachment_image_src($music_image, 'pixflow_music-thumb');
        $music_image = (false == $music_image)?PIXFLOW_PLACEHOLDER1:$music_image[0];
    }

    for( $i=1; $i<=$music_num; $i++ ){
        $tracks[$i] = shortcode_atts( array(
            'music_track_name_'.$i => "Inspiring ".$i,
            'music_track_url'.$i   => 'https://0.s3.envato.com/files/131328937/preview.mp3',
        ), $atts );
    }

    $id = pixflow_sc_id('music');
    $func_id = uniqid();

    wp_enqueue_script('jplayer-js',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'jPlayer.min.js'),array(),PIXFLOW_THEME_VERSION,true);
    ob_start();

    ?>
    <?php
    $align = trim($align);
    ?>
    <div id="<?php echo esc_attr($id); ?>" class="music-sc <?php echo esc_attr($music_alignment.' md-align-'.$align); ?> clearfix">

        <div class="wrap-image">
            <span class="image-album">
                <span class="image" style="background-image:url('<?php echo esc_attr($music_image); ?>');"></span>
                <img src="<?php echo PIXFLOW_THEME_IMAGES_URI . "/music-shadow.png"?>" class="image-shadow" alt="music image shadow" />
                <span class="disc-image"></span>

                <div class="btnSimulate"></div>
            </span>

            <div class="main-album-name"> <?php echo esc_attr($music_album); ?> </div>
            <div class="artist"> <?php echo esc_attr($music_artist); ?> </div>
        </div> <!-- End wrap-image -->

        <div class="music-main-container">

            <ol class="tracks">
                <?php
                if(is_array($tracks)) {
                    foreach ($tracks as $key => $track) {
                        $track_name = $track['music_track_name_' . $key];
                        ?>
                        <li class="track">

                            <i class="music-hoverd icon-play"></i>

                            <div id="<?php echo esc_attr($id) . $key?>-track" class="track-bar"></div>

                            <div id="<?php echo esc_attr($id) . $key?>-inner-track"
                                 class="jp-audio <?php echo esc_attr($id) . $key?>-class" role="application"
                                 aria-label="media player">

                            <span class="link jp-play">
                                <span class="track-name"> <?php echo esc_attr($track_name); ?> </span>
                                <span class="track-album-name"> <?php echo esc_attr($music_album); ?> </span>
                            </span>

                                <div class="jp-type-single">

                                    <div class="jp-gui jp-interface">

                                        <div class="jp-controls-holder">

                                            <div class="jp-progress">
                                                <div class="jp-seek-bar">
                                                    <div class="jp-play-bar"></div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End jp-controls-holder -->

                                    </div>
                                    <!-- End jp-interface -->

                                    <div class="jp-duration" role="timer" aria-label="duration"></div>
                                    <span class="music-bar"></span>

                                </div>
                                <!-- End jp-type-single -->

                                <div class="jp-controls <?php echo esc_attr($id) . $key?>-class">
                                    <button class="jp-play play-pause" role="button" tabindex="0">
                                        <div class="icon icon-play"></div>
                                    </button>
                                </div>

                            </div>
                            <!-- End jp-audio -->


                            <script type="text/javascript">
                                "use strict";

                                var $ = jQuery;
                                $(function () {
                                    $('#<?php echo esc_attr($id).'1'?>-class').show();
                                    if (typeof $.jPlayer == 'function') {
                                        $("#<?php echo esc_attr($id).$key?>-track").jPlayer({
                                            ready: function () {
                                                $(this).jPlayer("setMedia", {
                                                    m4a: "<?php echo esc_attr( $track['music_track_url'.$key] ); ?>",
                                                    oga: "<?php echo esc_attr( $track['music_track_url'.$key] ); ?>",
                                                });
                                            },
                                            play: function () {
                                                $(this).jPlayer("pauseOthers", 0); // stop all players except this one.
                                            },
                                            cssSelectorAncestor: "#<?php echo esc_attr($id).$key?>-inner-track",
                                            swfPath: "/js",
                                            supplied: "m4a, oga, mp3",
                                            useStateClassSkin: true,
                                            autoBlur: true,
                                            smoothPlayBar: true,
                                            keyEnabled: false,
                                            remainingDuration: false,
                                            toggleDuration: true,
                                        });

                                    }
                                });

                            </script>

                        </li>
                        <?php
                    }
                }
                ?>
            </ol>
        </div> <!-- End music-main-container -->

        <style scoped>

            .music-sc .image-album .disc-image {
                background-image: url('<?php echo PIXFLOW_THEME_URI; ?>/assets/img/disc.png');
            }
            .music-sc .jp-audio .music-bar {
                background-image: url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/music-bar.gif");
            }
            #<?php echo esc_attr($id); ?>.music-sc .track,
            #<?php echo esc_attr($id); ?>.music-sc .track-name,
            #<?php echo esc_attr($id); ?>.music-sc .track-album-name,
            #<?php echo esc_attr($id); ?>.music-sc .main-album-name,
            #<?php echo esc_attr($id); ?>.music-sc .artist{
                 color: <?php echo esc_attr($music_texts_color) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .track:first-child{
                 border-top-color: <?php echo (pixflow_colorConvertor($music_texts_color,'rgba',0.6)) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .music-played,
            #<?php echo esc_attr($id); ?>.music-sc .music-played .track-name{
                 color: <?php echo esc_attr($music_played_color) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .music-played .jp-audio .jp-play-bar{
                 background-color: <?php echo esc_attr($music_played_color) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .jp-audio .jp-duration{
                 color: <?php echo esc_attr(pixflow_colorConvertor($music_texts_color,'rgba',0.7)) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .jp-audio .jp-progress{
                 background-color: <?php echo esc_attr(pixflow_colorConvertor($music_texts_color,'rgba',0.6)) ?>;
             }

            #<?php echo esc_attr($id); ?>.music-sc .jp-audio .jp-play-bar{
                 background-color: <?php echo esc_attr($music_texts_color) ?>;
             }

        </style>

    </div> <!-- End music -->

    <script>

        "use strict";
        var $ = (jQuery),
            playedFlag = false;

        $(function() {
            if ( typeof pixflow_music == 'function' )
            {
                pixflow_music('<?php echo esc_attr($id) ?>');
            }
            if ( typeof pixflow_shortcodeScrollAnimation == 'function' )
            {
                pixflow_shortcodeScrollAnimation();
            }

            $('.jp-audio').click( function() {

                setTimeout(function(){
                    $('.music-sc .track').removeClass('music-played');
                    $('.jp-state-playing').parent().addClass('music-played');
                    playedFlag = true;
                }, 100);

            });

            var clearHoverIn1, clearHoverIn2;

            $('.track').hover( function() {

                    //clearTimeout(clearHoverOut);
                    var $this = $(this);

                    if (!playedFlag) {

                        clearHoverIn1 = setTimeout( function() {
                            $this.css({ listStyle: 'none' });
                        }, 170);

                        clearHoverIn2 = setTimeout( function() {
                            $this.find('.music-hoverd').css({ opacity: '1', left: '0' });
                        }, 200);
                    }

                    playedFlag = false;

                }
                , function() {

                    var $this = $(this);
                    clearTimeout(clearHoverIn1);
                    clearTimeout(clearHoverIn2);

                    $('.music-hoverd').css({ opacity: '0', left: '-5px' });

                    setTimeout( function() {
                        $this.css({ listStyle: 'inherit' });
                    }, 200);

                });
        });


    </script>

    <?php
    $output .= ob_get_clean();
    return $output;
}
