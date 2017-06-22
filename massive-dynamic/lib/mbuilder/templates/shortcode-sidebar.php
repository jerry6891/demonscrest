<div class="pixflow-shortcodes-panel hide-panel">
    <div class="shortcode-panel-background"></div>
    <div class="content">
        <div class="pixflow_close_shortcodes_panel">
            <svg class="pixflow-element-button-icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 12 12" style="enable-background:new 0 0 12 12;" xml:space="preserve">
                <polygon points="6.9,5.1 6.9,0 5,0 5,5.1 0,5.1 0,6.9 5,6.9 5,12 6.9,12 6.9,6.9 11.9,6.9 11.9,5.1 "/>
            </svg>
        </div>
        <input class="pixflow-search-shortcode" name="qsearch" placeholder="search" value=""/>
        <div class="pixflow-shortcodes-container">

            <div class="search-result"></div>
            <?php if (is_home() || (is_front_page() && is_home()) || function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) { ?>
                <div class="no-shortcode"><div class="tip-image"></div>
                <div class="heading">You Don't Need Shortcodes</div>There's no need to use shortcodes in blog and shop pages, because they have their own templates. To add contents to these pages, you should use post or product in WordPress dashboard.</div>
            <?php } else {
                    global $mBuilderShortcodes;
                    $category_list= array();
                    foreach ($mBuilderShortcodes as $shortcode => $meta) {
                       if (isset($meta['category'])){
                           /* check if it is visual composer shortcode or not */
                            $base = (isset($meta['base'])) ? $meta['base'] : '';
                            $allowed_shortcodes = array('vc_row', 'vc_empty_space');
                            if ($base == 'vc_column_text' || ( strpos($base,'vc_') === 0  && $base != ''&& !in_array($base,$allowed_shortcodes) )) {
                                continue;
                            }

                            /* Create shortcodes Category HTML */
                            $category = $meta['category'];
                            $name = (isset($meta['name'])) ? $meta['name']:'';
                            $icon_name = strtolower($name);
                            $icon_name = str_replace(' ', '-', $icon_name);
                            if (!array_key_exists($category,$category_list)){
                                $category_list[$category] = '<div class="shortcodes active ' . $category . '" id="' . $base . '" data-name="' . strtolower($name) .'"><div class="inner-container" ><div class="icon mdb-'.$icon_name.'"></div><p class="md-shortcode-title">' . $name . '</p></div></div>' ;

                            }else{
                                $category_list[$category] .= '<div class="shortcodes active ' . $category . '" id="' . $base . '" data-name="' . strtolower($name) .'"><div class="inner-container" ><div class="icon mdb-'.$icon_name.'"></div><p class="md-shortcode-title" >' . $name . '</p></div></div>';
                            }
                       }
                    }
                    /* Echo shortcodes HTML */
                   global $md_allowed_HTML_tags;
                   foreach ($category_list as $category => $items_html ){
                       echo '<div class=" show category-container"><h6>' . esc_attr($category) . '</h6>'. wp_kses($items_html,$md_allowed_HTML_tags).'</div>';
                   }
                } ?>
        </div>
    </div>
</div>
<div class="pixflow-add-element-button">
<svg class="pixflow-element-button-icon hide" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 12 12" style="enable-background:new 0 0 12 12;" xml:space="preserve">
    <polygon points="6.9,5.1 6.9,0 5,0 5,5.1 0,5.1 0,6.9 5,6.9 5,12 6.9,12 6.9,6.9 11.9,6.9 11.9,5.1 "/>
</svg>
<svg class="pixflow-element-button-icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 323.7 286.7" enable-background="new 0 0 323.7 286.7" xml:space="preserve">
<path d="M123.9,237.3c-26.8-2.9-47.3-20.3-53-46c-6.8-30.9-7.4-61.9-0.3-92.9c5.9-26,23.2-42.8,49.3-46.9
	c27.6-4.4,55.2-4.5,82.9-0.2c26.6,4.1,43.3,19.3,49.8,45c7.5,29.6,7,59.6,1.7,89.5c-2.2,12.6-7.4,24-16.1,33.6
	c-12.5,13.9-29.3,17.2-46.7,19C183.5,239.1,134.5,238.4,123.9,237.3z M197.9,225.1c24.7-3.1,38.6-15.8,43.9-40
	c5.8-26.7,5.6-53.5,0.6-80.2c-4.3-23-18-38.5-43.3-41.9c-25.8-3.4-51.4-3.6-77,0.4c-21.4,3.4-34.4,16.3-39.4,37
	c-6.7,28.1-6.5,56.4-0.7,84.5c3.9,18.7,13.8,33.7,33.7,37.9C130.6,226.1,185.8,226.6,197.9,225.1z"/>
    <path d="M161.7,102.1c12.3,0,24.6,0,36.9,0c5.6,0,8.1,2.1,8,6.5c-0.1,4.2-2.8,6.6-8.1,6.6c-24.4,0-48.9,0-73.3,0
	c-5.1,0-8.7-3.2-8.5-7.1c0.2-4,3.1-6.1,8.6-6.1C137.4,102,149.6,102.1,161.7,102.1z"/>
    <path d="M162,137.1c12.1,0,24.3,0,36.4,0c5.7,0,8.1,2,8.2,6.3c0,4.5-2.8,6.8-8.4,6.9c-24.3,0-48.6,0-72.9,0c-6.9,0-10.9-5.3-7.5-10
	c1.3-1.7,4.4-3,6.7-3C137.1,136.9,149.5,137.1,162,137.1z"/>
    <path d="M162,173.1c12.1,0,24.3,0,36.4,0c5.8,0,8.1,1.9,8.1,6.3c0,4.5-2.8,6.9-8.4,6.9c-24.3,0-48.6,0-72.9,0
	c-6.9,0-10.9-5.4-7.4-10.1c1.3-1.7,4.4-2.9,6.7-3C137.1,172.9,149.6,173.1,162,173.1z"/>
</svg>
</div>
