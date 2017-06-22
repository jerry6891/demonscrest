<?php
/**
 * List Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_list', 'pixflow_get_style_script'); // pixflow_sc_list

function pixflow_sc_list( $atts, $content = null ){
    $output = '';
    extract( shortcode_atts( array(
        'list_style'               =>'number',
        'list_icon_class'      =>'icon-checkmark' ,
        'list_general_color'         => '#a3a3a3',
        'list_hover_color'  => '#e45d75',
        'list_item_num' => 5,
        'align' => 'left'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_list',$atts);
    for($i=1; $i<=$list_item_num; $i++){
        $items[$i] = shortcode_atts( array(
            'list_item_'.$i => 'This is text for item'.$i,
        ), $atts );
    }
    $id = pixflow_sc_id('list');
    $list_starter = ($list_style == 'icon')?'<ul>':'<ol>';
    $list_finisher = ($list_style == 'icon')?'</ul>':'</ol>';
    ob_start();
    ?>
    <style scoped>

        .<?php echo esc_attr($id) ?> ul > li span{
            color: <?php echo esc_attr($list_hover_color); ?>;
            border-color: <?php echo esc_attr($list_hover_color); ?>;
        }
        .<?php echo esc_attr($id) ?> li,
        .<?php echo esc_attr($id) ?> li p{
            color: <?php echo esc_attr($list_general_color); ?>;
        }
        .<?php echo esc_attr($id) ?> li:hover,
        .<?php echo esc_attr($id) ?> li:hover p,
        .<?php echo esc_attr($id) ?> ol > li:hover:before{
            color: <?php echo esc_attr($list_hover_color); ?>;
        }
    </style>
    <?php
    $align = trim($align);
    ?>
    <div class="<?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align) ?> list-shortcode" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php global $md_allowed_HTML_tags;  echo wp_kses($list_starter,$md_allowed_HTML_tags);?>
        <?php
        foreach($items as $key=>$item){
            $title  = $item['list_item_'.$key];
            if('' != $title) {?>
                <li>
                    <?php if($list_style == 'icon'){ ?>
                        <span class="<?php echo esc_attr($list_icon_class) ?>"></span>
                    <?php } ?>
                    <p><?php echo esc_attr($title); ?></p>
                </li>
            <?php } ?>
        <?php } ?>
        <?php echo wp_kses($list_finisher,$md_allowed_HTML_tags); ?>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
