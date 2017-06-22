<?php
/**
 * Iconbox New Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_iconbox_new', 'pixflow_get_style_script'); // pixflow_sc_iconbox_new

function pixflow_sc_iconbox_new( $atts, $content = null )
{

    extract(shortcode_atts(array(
        'iconbox_new_alignment' => 'center',
        'iconbox_new_icon' => 'icon-microphone-outline',
        'iconbox_new_title' => 'Super Flexible',
        'iconbox_new_heading' => 'h6',
        'iconbox_new_icon_color' => 'rgb(0,0,0)',
        'iconbox_new_general_color' => '#5e5e5e',
        'iconbox_new_description' => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable",
        'left_right_padding' => '0',
        'iconbox_new_readmore' => 'Find Out More',
        'iconbox_new_url' => '#',
        'iconbox_new_target' => '_self',
        'align' => 'center',
        'iconbox_new_hover' => 'circle-hover',
        'iconbox_new_hover_color' => '#efefef'
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_iconbox_new', $atts);
    $id = pixflow_sc_id('iconbox-new');

    ob_start(); ?>

    <style scoped>

        <?php if('right' == $iconbox_new_alignment) { ?>
        <?php echo '.'.esc_attr($id) ?>
        .iconbox-new-content {
            text-align: right;
        }

        <?php echo '.'.esc_attr($id) ?>
        .icon-holder,
        <?php echo '.'.esc_attr($id) ?>.iconbox-new .description {
            float: right;
        }

        <?php echo '.'.esc_attr($id) ?>
        .icon-holder {
            margin-right: -25px;
        }

        <?php } elseif ('center' == $iconbox_new_alignment) { ?>

        <?php echo '.'.esc_attr($id) ?>
        .iconbox-new-content {
            text-align: center;
        }

        <?php echo '.'.esc_attr($id) ?>
        .icon-holder,
        <?php echo '.'.esc_attr($id) ?>.iconbox-new .description {
            margin-right: auto;
            margin-left: auto;
        }

        <?php } elseif ('left' == $iconbox_new_alignment) { ?>
        <?php echo '.'.esc_attr($id) ?>
        .iconbox-new-content {
            text-align: left;
        }

        <?php echo '.'.esc_attr($id) ?>
        .icon-holder,
        <?php echo '.'.esc_attr($id) ?>.iconbox-new .description {
            float: left;
        }

        <?php echo '.'.esc_attr($id) ?>
        .icon-holder {
            margin-left: -25px;
        }

        <?php } ?>

        <?php echo '.'.esc_attr($id) ?>
        .icon {
            color: <?php echo esc_attr($iconbox_new_icon_color); ?>;
        }

        <?php echo '.'.esc_attr($id) ?>
        .read-more {
            color: <?php echo esc_attr($iconbox_new_icon_color); ?>;
        }

        <?php echo '.'.esc_attr($id) ?>
        .title {
            color: <?php echo esc_attr($iconbox_new_general_color); ?>;
        }

        <?php echo '.'.esc_attr($id) ?>
        .description {
            color: <?php echo esc_attr(pixflow_colorConvertor($iconbox_new_general_color,'rgba', 0.7)); ?>;
        }

        <?php echo '.'.esc_attr($id) ?>
        .iconbox-new-content.box-hover:hover {
            background-color: <?php echo esc_attr($iconbox_new_hover_color); ?>;
        }

    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="iconbox-new <?php echo esc_attr($id . ' ' . $animation['has-animation'] . ' md-align-' . $align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <div class="iconbox-new-content <?php if ($iconbox_new_hover == 'box-hover') { echo "box-hover"; } ?>">

            <div class="hover-holder">

                <?php if (isset($iconbox_new_icon) && 'icon-empty' != $iconbox_new_icon) { ?>
                    <div class="icon-holder">
                        <?php if ($iconbox_new_hover == 'circle-hover') { ?>
                            <svg class="svg-circle">
                                <circle cx="49" cy="49" r="50" stroke="<?php echo esc_attr($iconbox_new_hover_color); ?>"
                                        stroke-width="100" fill="none"></circle>
                            </svg>
                        <?php } ?>
                        <div class="icon <?php echo esc_attr($iconbox_new_icon) ?>"></div>
                    </div>
                <?php } ?>

                <div class=" clearfix"></div>
                <!--End of Icon section-->

                <?php if (isset($iconbox_new_title) && '' != $iconbox_new_title) { ?>
                <<?php echo esc_attr($iconbox_new_heading); ?> class="title">
                <?php echo esc_attr($iconbox_new_title); ?>
            </<?php echo esc_attr($iconbox_new_heading); ?>>
            <?php } ?>
            <!--End of Title section-->
        </div>

        <?php if (isset($iconbox_new_description) && '' != $iconbox_new_description) { ?>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i", '', esc_attr($iconbox_new_description)); ?></p>
            <div class=" clearfix"></div>
        <?php } ?>
        <!--End of Description section-->

        <?php if (isset($iconbox_new_readmore) && '' != $iconbox_new_readmore){ ?>
            <a class="read-more <?php echo ' ' . esc_attr($id); ?>" href="<?php echo esc_url($iconbox_new_url); ?>"
               target="<?php echo esc_attr($iconbox_new_target); ?>">
                <i class="icon icon-arrow-right5"></i>
                <span>
                            <?php echo esc_attr($iconbox_new_readmore); ?>
                        </span>
                <i class="icon icon-arrow-right5"></i>
            </a>
            <!--End of Read More-->
        <?php } ?>

    </div>
    </div>

    <?php if ($iconbox_new_hover == 'circle-hover') { ?>

    <script>

        "use strict";
        var $ = (jQuery);

        if (typeof pixflow_iconboxNewShortcode == 'function')
            pixflow_iconboxNewShortcode();

    </script>

<?php } ?>

    <script>
        "use strict";
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    return ob_get_clean();
}
