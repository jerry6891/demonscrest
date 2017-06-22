<?php
/**
 * Separator Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_separator', 'pixflow_get_style_script'); // pixflow_sc_separator

function pixflow_sc_separator( $atts, $content = null ){
    $output = '';
    extract( shortcode_atts( array(
        'separator_style'  =>'line',
        'separator_size'  =>'5',
        'separator_width'  =>'70',
        'separator_color' =>'#ccc',
        'align' =>'center'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_separator',$atts);
    $id = pixflow_sc_id('separator');
    ob_start();
    if($separator_style == 'line') {
        ?>
        <style scoped>
            .<?php echo esc_attr($id)?>{
                height:<?php echo esc_attr($separator_size)?>px;
                border-radius:10px;
                width:<?php echo esc_attr($separator_width.'%')?>;
                background:<?php echo esc_attr($separator_color)?>;
            }
        </style>
        <?php
    }
    ?>
    <?php
    $align = trim($align);
    ?>
    <div class="sc-separator  <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align) ?> clearfix" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php
        if($separator_style == 'shadow'){
            ?>
            <img src="<?php echo pixflow_path_combine(PIXFLOW_THEME_IMAGES_URI,'separator-shadow.png')?>">
            <?php
        }
        ?>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
