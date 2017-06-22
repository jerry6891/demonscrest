<?php
/*
 * VC Column
 * */

function mBuilder_vccolumn($atts,$content){
    extract( shortcode_atts( array(
        'width'               => '1/1',
        'css'                 => '',
        'el_class'            => '',
        'offset'              => '',
        'md_laptop_visibility'            => 'yes',
        'md_tablet_portrait_visibility'   => 'yes',
        'md_tablet_landscape_visibility'  => 'yes',
        'md_mobile_portrait_visibility'   => 'yes',
        'md_mobile_landscape_visibility'  => 'yes',
        'md_tablet_portrait_width'  => '0',
    ), $atts ));

    ob_start();
    $flag_fill='';
    $width = explode('/',$width);
    $width = $width[0] / $width[1] * 12;
    $r = preg_match ('/.*?{(.*?)}.*?/is', $css,$matches);
    $id = pixflow_sc_id('md_col');
    if(is_array($matches) && isset($matches[1])){
        $css = $matches[1];

        $str_post_bgc=strpos($matches[1],"background-color");
        if(gettype($str_post_bgc)=='integer' && $str_post_bgc >=0){
            $flag_fill='vc_col-has-fill';
        }

        $str_post_bgi=strpos($matches[1],"background-image");
        if(gettype($str_post_bgi)=='integer' && $str_post_bgi >=0){
            $flag_fill='vc_col-has-fill';
        }

    }else{
        $css = '';
    }
    $css = str_replace('``','\'',$css);
    $class=''. ' ' .$flag_fill;
    $content = preg_replace('/(<style data-type="mBuilderInternal">.*?<\/style>)/is','',$content);
    if(trim($content) == ''){
        $class = 'vc_empty-element';
    }

    if($offset!=''){
        $class .= ' '.$offset;
    }

    if ($md_laptop_visibility == 'no'){
        $class .= ' hidden-laptop';
    }
    if ($md_tablet_portrait_visibility == 'no'){
        $class .= ' hidden-tablet-p';
    }
    if ($md_tablet_landscape_visibility == 'no'){
        $class .= ' hidden-tablet-l';
    }
    if ($md_mobile_portrait_visibility == 'no'){
        $class .= ' hidden-mobile-p';
    }
    if ($md_mobile_landscape_visibility == 'no'){
        $class .= ' hidden-mobile-l';
    }

    if($md_tablet_portrait_width!= '0'){
        switch ($md_tablet_portrait_width){
            case '12':
                $class .= ' responsive-full-width';
                break;

            case '6':
                $class .= ' responsive-col-50';
                break;
        }
    }

    ?>

    <div class='wpb_column vc_column_container <?php echo esc_attr($class)?> <?php echo esc_attr($el_class) ?> col-sm-<?php echo esc_attr($width); ?>'>
        <div class='vc_column-inner <?php echo $id; ?>'>
            <div class='wpb_wrapper'>
                <style data-type="mBuilderInternal">
                    div.vc_column_container>.vc_column-inner.<?php echo esc_attr($id); ?>{<?php print($css); ?>}
                </style>
                <?php print(pixflow_js_remove_wpautop( $content )); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}