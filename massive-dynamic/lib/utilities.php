<?php
require_once(PIXFLOW_THEME_LIB . '/includes/simple_html_dom.php');
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
$jsString = '';
function pixflow_get_pagination($query = null, $range = 3, $default_pagination = true)
{
    global $paged, $wp_query, $md_allowed_HTML_tags;

    $q = $query == null ? $wp_query : $query;
    $output = '';

    // How much pages do we have?
    if (!isset($max_page)) {
        $max_page = $q->max_num_pages;

        if (array_key_exists('paged', $q->query)) {
            $post_count = esc_attr($q->query['paged']);
        } else {
            $post_count = 1;
        }
    }

    // We need the pagination only if there is more than 1 page
    if ($max_page < 2)
        return $output;

    $output .= '<div class="post-pagination">';

    if (!$paged) $paged = 1;

    // If current page is our home page we will change the pagination structure to prevent 404 error , if not we use the default structure
    if (!$default_pagination) {
        $ppage = $paged + 1;
        $npage = $paged - 1;
        $plink = get_home_url() . "/?paged=" . $ppage;
        $nlink = get_home_url() . "/?paged=" . $npage;

        // If we are on page 2 , next page would be page 1 and its better that we just go to home page instead of passing pagination argument
        if ($paged == 2) {
            $nlink = $nlink = get_home_url();
        }
    } else {
        $plink = get_pagenum_link($paged + 1);
        $nlink = get_pagenum_link($paged - 1);
    }

    // Next page
    if ($paged < $max_page)
        $output .= '<a class="prev-page-link" href="' . $plink . '"><span class="prev-page"></span><span class="text">' . esc_attr__('PREVIOUS POSTS', 'massive-dynamic') . '</span></a>';
    else if ($paged == $max_page)
        $output .= '<a class="no-prev-page" href="#"><span class="text">' . esc_attr__('NO OLD POSTS', 'massive-dynamic') . '</span><span class="prev-page"></span></a>';


    $output .= '<span class="page-num">' . "Page $post_count of $max_page" . '</span>';

    // To the previous page
    if ($paged > 1)
        $output .= '<a class="next-page-link" href="' . $nlink . '"><span class="text">' . esc_attr__('NEXT POSTS', 'massive-dynamic') . '</span><span class="next-page"></span></a>';
    else if ($paged == 1)
        $output .= '<a class="no-next-page" href="#"><span class="text">' . esc_attr__('NO NEW POSTS', 'massive-dynamic') . '</span><span class="next-page"></span></a>';

    $output .= '<div class="clearfix"></div><a class="pagination-border"></a><a class="post-pagination-hover"></a></div><!-- post-pagination -->';

    echo wp_kses($output, $md_allowed_HTML_tags);
}

// retrieves the attachment ID from the file URL
function pixflow_get_image_id($image_url)
{
    global $wpdb;

    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid='%s';", $image_url));

    if (count($attachment))
        return $attachment[0];
    else
        return false;
}

function pixflow_get_related_posts_by_taxonomy($postId, $taxonomy, $maxPosts = 9)
{
    $terms = wp_get_object_terms($postId, $taxonomy);

    if (!count($terms))
        return new WP_Query();

    $termsSlug = array();

    foreach ($terms as $term)
        $termsSlug[] = $term->slug;

    $args = array(
        'post__not_in' => array((int)$postId),
        'post_type' => get_post_type($postId),
        'showposts' => (int)$maxPosts,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $termsSlug
            )
        )
    );

    return new WP_Query($args);
}


//Return theme option
function pixflow_opt($option)
{
    $opt = get_option(PIXFLOW_OPTIONS_KEY);
    return stripslashes($opt[$option]);
}


//Echo theme option
function pixflow_eopt($option)
{
    echo pixflow_opt($option);
}

function pixflow_print_terms($terms, $separatorString)
{
    $termIndex = 1;
    if ($terms)
        foreach ($terms as $term) {
            echo esc_attr($term->name);

            if (count($terms) > $termIndex)
                echo esc_attr($separatorString);

            $termIndex++;
        }
}


/*
 * Gets array value with specified key, if the key doesn't exist
 * default value is returned
 */
function pixflow_array_value($key, $arr, $default = '')
{
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}


/*
 * Deletes attachment by given url
 */
function pixflow_delete_attachment($url)
{
    global $wpdb;

    // We need to get the image's meta ID.
    $query = "SELECT ID FROM wp_posts where guid = '" . esc_url($url) . "' AND post_type = 'attachment'";
    $results = $wpdb->get_results($wpdb->prepare($query));

    // And delete it
    foreach ($results as $row) {
        wp_delete_attachment($row->ID);
    }
}

function pixflow_get_post_terms_names($taxonomy)
{
    $terms = get_the_terms(get_the_ID(), $taxonomy);

    if (!is_array($terms))
        return $terms;

    $termNames = array();

    foreach ($terms as $term)
        $termNames[] = $term->name;

    return $termNames;
}


/*
 * Concatenate post category names
 */
function pixflow_implode_post_terms($taxonomy, $separator = ', ')
{
    $terms = pixflow_get_post_terms_names($taxonomy);

    if (!is_array($terms))
        return null;

    return implode($separator, $terms);
}


/*
 * Converts array of slugs to corresponding array of IDs
 */
function pixflow_slugs_to_ids($slugs = array(), $taxonomy)
{
    $tempArr = array();
    foreach ($slugs as $slug) {
        if (!strlen(trim($slug))) continue;
        $term = get_term_by('slug', $slug, $taxonomy);
        if (!$term) continue;
        $tempArr[] = $term->term_id;
    }

    return $tempArr;
}

/* Get video url from known sources such as youtube and vimeo */

function pixflow_extract_video_info($string)
{
    //check for youtube video url
    if (preg_match('/https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[^&\n\s"<>]+/i', $string, $matches)) {
        $url = parse_url($matches[0]);
        parse_str($url['query'], $queryParams);

        return array('type' => 'youtube', 'url' => $matches[0], 'id' => $queryParams['v']);
    } //Vimeo
    else if (preg_match('/https?:\/\/(?:www\.)?vimeo\.com\/\d+/i', $string, $matches)) {
        $url = parse_url($matches[0]);

        return array('type' => 'vimeo', 'url' => $matches[0], 'id' => ltrim($url['path'], '/'));
    }


    return null;
}

function pixflow_extract_audio_info($string)
{
    //check for soundcloud url
    if (preg_match('/https?:\/\/(?:www\.)?soundcloud\.com\/[^&\n\s"<>]+\/[^&\n\s"<>]+\/?/i', $string, $matches)) {
        return array('type' => 'soundcloud', 'url' => $matches[0]);
    }

    return null;
}

function pixflow_get_video_meta(array &$video)
{
    if ($video['type'] != 'youtube' && $video['type'] != 'vimeo')
        return null;

    $ret = pixflow_get_url_content($video['url']/*, '127.0.0.1:8080'*/);

    if (is_array($ret))
        return 'Server Error: ' . $ret['error'] . " \nError No: " . $ret['errorno'];

    if (trim($ret) == '')
        return 'Error: got empty response from youtube';

    $html = pixflow_str_get_html($ret);
    $vW = $html->find('meta[property="og:video:width"]');
    $vH = $html->find('meta[property="og:video:height"]');

    if (count($vW) && count($vH)) {
        $video['width'] = $vW[0]->content;
        $video['height'] = $vH[0]->content;
    }

    return null;
}

function pixflow_soundcloud_get_embed($url, $height)
{
    $json = pixflow_get_url_content("http://soundcloud.com/oembed?format=json&url=$url"/*, '127.0.0.1:8580'*/);

    if (is_array($json))
        return 'Server Error: ' . $json['error'] . " \nError No: " . $json['errorno'];

    if (trim($json) == '')
        return 'Error: got empty response from soundcloud';

    //Convert the response string to PHP object
    $data = json_decode($json);

    if (NULL == $data)
        return "Cant decode the soundcloud response \nData: $json";

    //TODO: add additional error checks
    $data->html = str_replace('height="400"', 'height="' . $height . '"', $data->html);
    return $data->html;
}


/* downloads data from given url */

function pixflow_get_url_content($url, $proxy = '')
{
    $args = array(
        'headers' => array(),
        'body' => null,
        'sslverify' => true,
    );

    $response = wp_remote_get($url, array(
            'timeout' => 45,
        )
    );

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        $ret = array('error' => $error_message, 'errorno' => '');
    } else {
        $ret = $response['body'];
    }

    return $ret;
}


//Thanks to:
//http://bavotasan.com/tutorials/limiting-the-number-of-words-in-your-excerpt-or-content-in-wordpress/
function pixflow_excerpt($limit)
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . '...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}

/* Sidebar widget count */
function pixflow_count_sidebar_widgets($sidebar_id, $echo = false)
{
    $sidebars = wp_get_sidebars_widgets();

    if (!isset($sidebars[$sidebar_id])) {
        return -1;
    }


    $cnt = count($sidebars[$sidebar_id]);

    if ($echo)
        echo esc_attr($cnt);
    else
        return $cnt;
}

function pixflow_get_custom_sidebars()
{
    $sidebarStr = pixflow_opt('custom_sidebars');

    if (strlen($sidebarStr) < 1)
        return array();

    $arr = explode(',', $sidebarStr);
    $sidebars = array();

    foreach ($arr as $item) {
        $sidebars["custom-" . hash("crc32b", $item)] = str_replace('%666', ',', $item);
    }

    return $sidebars;
}

/* Get Sidebar */
function pixflow_get_sidebar($id = 'main-sidebar', $type, $class)
{
    $sidebarClass = "sidebar widget-area";
    $sidebarWidth = $GLOBALS['sidebarWidth'];
    $sidebarWidth = ($GLOBALS['sidebarPosition'] == 'double') ? $sidebarWidth / 2 : $sidebarWidth;

    if ('' != $class)
        $sidebarClass .= " $class";

    if ($type == 'sticky') {
        if (pixflow_count_sidebar_widgets($id) < 1)
            $sidebarClass .= ' no-widgets';
        ?>
        <div class="stickySidebar" style="width: <?php echo esc_attr($sidebarWidth) . '%'; ?>">
            <aside class="<?php echo esc_attr($sidebarClass); ?>">
                <div class="color-overlay"></div>
                <div class="texture-overlay"></div>
                <div class="bg-image"></div>
                <?php dynamic_sidebar($id); ?>
            </aside>
        </div>

        <?php
    } elseif ($type != 'sticky') {
        if (pixflow_count_sidebar_widgets($id) < 1)
            $sidebarClass .= ' no-widgets';

        $closeIcon = (strpos($sidebarClass, 'smart-sidebar') < 0 || !strpos($sidebarClass, 'smart-sidebar')) ? true : false;

        ?>
        <div widgetID="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($sidebarClass); ?>"
             style="width: <?php echo esc_attr($sidebarWidth) . '%'; ?>">

            <?php if ($closeIcon) { ?>
                <div class="color-overlay color-type"></div>
                <div class="color-overlay texture-type"></div>
                <div class="color-overlay image-type"></div>
                <div class="texture-overlay"></div>
                <div class="bg-image"></div>
            <?php } else { ?>
                <span class="close-sidebar"><i class="icon-cross"></i></span>
            <?php } ?>
            <?php dynamic_sidebar($id); ?>
        </div>

        <?php
    }
    ?>

    <?php
}
// Get socials
function pixflow_get_active_socials()
{
    $active_socials = array();
    $socials = array(
        'facebook' => 'icon-facebook2',
        'twitter' => 'icon-twitter5',
        'vimeo' => 'icon-vimeo',
        'youtube' => 'icon-youtube2',
        'googleP' => 'icon-googleplus',
        'dribbble' => 'icon-dribbble',
        'tumblr' => 'icon-tumblr',
        'linkedin' => 'icon-linkedin',
        'flickr' => 'icon-flickr2',
        'forrst' => 'icon-forrst',
        'github' => 'icon-github2',
        'lastfm' => 'icon-lastfm',
        'paypal' => 'icon-paypal2',
        'rss' => 'icon-feed2',
        'wp' => 'icon-wordpress',
        'deviantart' => 'icon-deviantart2',
        'steam' => 'icon-steam',
        'soundcloud' => 'icon-soundcloud3',
        'foursquare' => 'icon-foursquare',
        'skype' => 'icon-skype',
        'reddit' => 'icon-reddit',
        'instagram' => 'icon-instagram',
        'blogger' => 'icon-blogger',
        'yahoo' => 'icon-yahoo',
        'behance' => 'icon-behance',
        'delicious' => 'icon-delicious',
        'stumbleupon' => 'icon-stumbleupon3',
        'pinterest' => 'icon-pinterest3',
        'xing' => 'icon-xing'
    );
    $defaults = array('facebook', 'twitter', 'youtube');

    foreach ($socials as $setting => $icon) {
        $link = pixflow_get_theme_mod($setting . '_social');
        $default = (in_array($setting, $defaults)) ? '#' : '';
        $link = ($link === null) ? $default : $link;
        if ($link != '') {
            $active_socials[$setting]['title'] = $setting;
            $active_socials[$setting]['icon'] = $icon;
            $active_socials[$setting]['link'] = $link;
        }
    }
    if (count($active_socials) > 0) {
        return $active_socials;
    } else {
        return false;
    }
}

function pixflow_colorConvertor($color, $to, $alpha = 1)
{

    if (strpos($color, 'rgba') !== false) {
        $format = 'rgba';
    } elseif (strpos($color, 'rgb') !== false) {
        $format = 'rgb';
    } elseif (strpos($color, '#') !== false) {
        $format = 'hex';
    } else {
        return '#000';
    }


    switch ($format) {
        case 'rgb':
            if ($to == 'rgba') {
                sscanf($color, 'rgb(%d,%d,%d)', $r, $g, $b);
                return ('rgba(' . $r . ',' . $g . ',' . $b . ',' . $alpha . ');');
            } elseif ($to == 'hex') {
                return pixflow_rgb2hex($color);
            } elseif ($to == 'rgb') {
                return $color;
            }
            break;

        case 'rgba':
            if ($to == 'rgb') {
                return pixflow_RgbaToRgb($color);
            } elseif ($to == 'hex') {
                $rgb = pixflow_RgbaToRgb($color);
                return pixflow_rgb2hex($rgb);
            } elseif ($to == 'rgba') {
                sscanf($color, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a);
                return ('rgba(' . $r . ',' . $g . ',' . $b . ',' . $alpha . ');');
            }
            break;

        case 'hex' :
            $default = 'rgb(0,0,0)';
            //Sanitize $color if "#" is provided
            if ($color[0] == '#') {
                $color = mb_substr($color, 1);
            }
            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
            } elseif (strlen($color) == 3) {
                $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
            } else {
                return $default;
            }
            //Convert hexadec to rgb
            $rgb = array_map('hexdec', $hex);

            if ($to == 'rgba') {
                return 'rgba(' . implode(",", $rgb) . ',' . $alpha . ')';
            } elseif ($to == 'rgb') {
                return 'rgb(' . implode(",", $rgb) . ')';
            } elseif ($to == 'hex') {
                return $color;
            }
    }
}

function pixflow_rgb2hex($color)
{
    $hex = "#";
    if (!is_array($color)) {
        $color = explode(',', $color);
        $color[0] = str_replace('rgb', '', $color[0]);
        $color[0] = str_replace('(', '', $color[0]);
        $color[2] = str_replace(')', '', $color[2]);
    }
    $hex .= str_pad(dechex($color[0]), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($color[1]), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($color[2]), 2, "0", STR_PAD_LEFT);
    return $hex; // returns the hex value including the number sign (#)
}

// convert rgba to rgb
function pixflow_RgbaToRgb($rgba)
{

    sscanf($rgba, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a);
    return ('rgb(' . $r . ',' . $g . ',' . $b . ');');
}

function pixflow_get_post_id($post_id = false){

    if ($post_id != false) {
        $post_id = $post_id;
    } elseif (isset($_SESSION['pixflow_post_id']) && $_SESSION['pixflow_post_id'] != null) {
        $post_id = $_SESSION['pixflow_post_id'];
    } else {
        if (is_home() || is_404() || is_search()) {
            $post_id = get_option('page_for_posts');
        } elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
            $post_id = get_option('woocommerce_shop_page_id');
        } else {
            $post_id = get_the_ID();
        }
    }
    return $post_id;
}

$pixflow_general_settings = $pixflow_unique_settings = array();
$pixflow_post_setting_status = array('no-post-id'=>'');
$pixflow_post_type = '';
$pixflow_post_id = '';
/*
* get post setting status
* return unique or general
*/
function pixflow_get_post_setting_status($post_id=false){
    $id = $post_id;
    global $pixflow_post_setting_status;
    if($id && isset($pixflow_post_setting_status[$id])){
        return $pixflow_post_setting_status[$id];
    }elseif($pixflow_post_setting_status['no-post-id']!=''){
        return $pixflow_post_setting_status['no-post-id'];
    }
    $post_id =  pixflow_get_post_id($post_id);
    global $pixflow_post_id;
    $pixflow_post_id = $post_id;
    $post_type = get_post_type($post_id);
    global $pixflow_post_type;
    $pixflow_post_type = $post_type;
    if ((isset($_SESSION['temp_status'])) && $_SESSION['temp_status']['id'] == $post_id) {
        $setting_status = $_SESSION['temp_status']['status'];
    } elseif (get_option('page_for_posts') != $post_id && ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product')) {
        if (isset($_SESSION[$post_type . '_status'])) {
            $setting_status = $_SESSION[$post_type . '_status'];
        } else {
            $setting_status = get_option($post_type . '_setting_status');
        }
    } else {
        $setting_status = get_post_meta($post_id, 'setting_status', true);
    }
    $setting_status = ($setting_status == 'unique') ? 'unique' : 'general';
    if($id){
        $pixflow_post_setting_status[$id] = $setting_status;
    }else{
        $pixflow_post_setting_status['no-post-id'] = $setting_status;
    }
    return $setting_status;
}
/*
 * Get value from cashed setting for general setting and cache setting if its not cashed before
 * */
function pixflow_get_general_setting($setting,$default){
    if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'pixflow-get-setting') || is_customize_preview()){
        return get_theme_mod($setting, $default);
    }
    global $pixflow_general_settings;
    if(!count($pixflow_general_settings)){
        $pixflow_general_settings = get_theme_mods();
    }
    return isset($pixflow_general_settings[$setting])?$pixflow_general_settings[$setting]:$default;
}

/*
 * Get value from cashed setting for unique setting and cache setting if its not cashed before
 * */
function pixflow_get_unique_setting($post_id,$post_type,$setting,$default){
    global $pixflow_unique_settings;
    if(!count($pixflow_unique_settings)){
        if ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product') {
            $pixflow_unique_settings = wp_load_alloptions();
        } else {
            $settings = get_post_meta($post_id);
            foreach($settings as $key=>$val){
                $pixflow_unique_settings[$key] = $val[0];
            }
        }
    }
    if ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product') {
        $value = (isset($pixflow_unique_settings[$post_type . '_' . $setting]) && $pixflow_unique_settings[$post_type . '_' . $setting] != '')?$pixflow_unique_settings[$post_type . '_' . $setting]:pixflow_get_general_setting($setting, $default);
        $value = ($value === false) ? pixflow_get_general_setting($setting, $default) : $value;
    } else {
        $value = (isset($pixflow_unique_settings[$setting]) && $pixflow_unique_settings[$setting] != '')?$pixflow_unique_settings[$setting]:pixflow_get_general_setting($setting, $default);
        $value = ($value === 'false') ? false : $value;
    }
    return $value;
}

//Return customizer option value
function pixflow_get_theme_mod($name, $default = null, $post_id = false){
    $setting_status = pixflow_get_post_setting_status($post_id);
    $customizedValues = (isset($_SESSION[$setting_status . '_customized'])) ? $_SESSION[$setting_status . '_customized'] : array();
    if (isset($_POST['customized'])) {
        $customizedValues = json_decode(wp_unslash($_POST['customized']), true);
    }
    if (count($customizedValues) && array_key_exists($name, $customizedValues)) {
        $value = $customizedValues[$name];

    }else{
        if ($setting_status == 'unique') {
            global $md_uniqueSettings;
            if (in_array($name, $md_uniqueSettings)) {
                global $pixflow_post_type;
                global $pixflow_post_id;
                $value = pixflow_get_unique_setting($pixflow_post_id,$pixflow_post_type,$name, $default);
            }else{
                $value = pixflow_get_general_setting($name, $default);
            }
        } else {
            $value = pixflow_get_general_setting($name, $default);
        }
    }
    $value = ($value === 'false') ? false : $value;
    return $value;
}


/*
    generate gradient css
    parameters: json(if source is json(json string)),first color(hex or rgb-optional),second color(hex or rgb-optional),start position(int),end position(int),angle(int0-360)
*/
function pixflow_makeGradientCSS($json = false, $color1 = '#fff', $color2 = '#000', $pos1 = 0, $pos2 = 100, $angle = 0)
{
    if ($json && $json != '') {

        if( preg_match('/pixflow_base64/' , $json)){
            $json = str_replace('pixflow_base64' , '' , $json);
            $json = base64_decode($json);
        }

        $json = str_replace("``", '"', $json);
        $json = str_replace("'", '"', $json);
        $value = json_decode($json);
        $color1 = $value->{"color1"};
        $color2 = $value->{"color2"};
        $pos1 = $value->{"color1Pos"};
        $pos2 = $value->{"color2Pos"};
        $angle = $value->{"angle"};
    }
    $angle = (int)$angle;
    if ($angle <= 90) {
        $masterAngle = 90 - $angle;
    } else {
        $masterAngle = 360 - ($angle - 90);
    }
    /*$masterAngle = (int)$angle + 90;
    $masterAngle = ($masterAngle>360)?$masterAngle - 360:$masterAngle;*/
    ob_start();
    ?>
    background: <?php echo esc_attr($color1) ?>;
    background: -webkit-gradient(linear, <?php echo esc_attr($angle) ?>deg, color-stop(<?php echo esc_attr($pos1) ?>%,<?php echo esc_attr($color1) ?>), color-stop(<?php echo esc_attr($pos2) ?>%,<?php echo esc_attr($color2) ?>));
    background: -webkit-linear-gradient(<?php echo esc_attr($angle) ?>deg,  <?php echo esc_attr($color1) ?> <?php echo esc_attr($pos1) ?>%,<?php echo esc_attr($color2) ?> <?php echo esc_attr($pos2) ?>%);
    background: -o-linear-gradient(<?php echo esc_attr($angle) ?>deg,  <?php echo esc_attr($color1) ?> <?php echo esc_attr($pos1) ?>%,<?php echo esc_attr($color2) ?> <?php echo esc_attr($pos2) ?>%);
    background: -ms-linear-gradient(<?php echo esc_attr($angle) ?>deg,  <?php echo esc_attr($color1) ?> <?php echo esc_attr($pos1) ?>%,<?php echo esc_attr($color2) ?> <?php echo esc_attr($pos2) ?>%);
    background: linear-gradient(<?php echo esc_attr($masterAngle) ?>deg,  <?php echo esc_attr($color1) ?> <?php echo esc_attr($pos1) ?>%,<?php echo esc_attr($color2) ?> <?php echo esc_attr($pos2) ?>%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo esc_attr($color1) ?>', endColorstr='<?php echo esc_attr($color2) ?>', GradientType=0);
    <?php
    return ob_end_flush();
}

add_action('wp_ajax_pixflow_generateThumbs', 'pixflow_generateThumbs');
add_action('wp_ajax_nopriv_pixflow-generateThumbs', 'pixflow_generateThumbs');
function pixflow_generateThumbs()
{
    set_time_limit(0);
    if (!isset($_SESSION['pixflow_media']) && !is_array($_SESSION['pixflow_media'])) {
        die('err');
    }
    foreach ($_SESSION['pixflow_media'] as $post_id => $item) {
        wp_update_attachment_metadata($post_id, wp_generate_attachment_metadata($post_id, $item));
    }
    die('done!');
}

// Demo Importer
add_action('wp_ajax_pixflow_ImportClearCache', 'pixflow_ImportClearCache');
add_action('wp_ajax_nopriv_pixflow_ImportClearCache', 'pixflow_ImportClearCache');
function pixflow_ImportClearCache()
{
    //if(!isset($_SESSION['importDemo']) || $_SESSION['importDemo'] != $_POST['demo']) {
    unset($_SESSION['importPosts']);
    unset($_SESSION['importRemove']);
    unset($_SESSION['importFiles']);
    unset($_SESSION['importImported']);
    unset($_SESSION['importContent']);
    unset($_SESSION['importDoIt']);
    unset($_SESSION['importProcessedPosts']);
    unset($_SESSION['importProcessedAuthors']);
    unset($_SESSION['importProcessedMenuItems']);
    unset($_SESSION['importProcessedTerms']);
    unset($_SESSION['importMenuItemOrphans']);
    unset($_SESSION['importMissingMenuItems']);
    //}elseif($_SESSION['importDemo'] == $_POST['demo']){
    unset($_SESSION['importDemo']);
    echo "retry";
    //}
}

add_action('wp_ajax_pixflow_ImportClearAllCache', 'pixflow_ImportClearAllCache');
add_action('wp_ajax_nopriv_pixflow_ImportClearAllCache', 'pixflow_ImportClearAllCache');
function pixflow_ImportClearAllCache()
{
    unset($_SESSION['importPosts']);
    unset($_SESSION['importRemove']);
    unset($_SESSION['importFiles']);
    unset($_SESSION['importImported']);
    unset($_SESSION['importContent']);
    unset($_SESSION['importDoIt']);
    unset($_SESSION['importProcessedPosts']);
    unset($_SESSION['importProcessedAuthors']);
    unset($_SESSION['importProcessedMenuItems']);
    unset($_SESSION['importProcessedTerms']);
    unset($_SESSION['importMenuItemOrphans']);
    unset($_SESSION['importMissingMenuItems']);

}

add_action('wp_ajax_pixflow_ImportDummyData', 'pixflow_ImportDummyData');
add_action('wp_ajax_nopriv_pixflow_ImportDummyData', 'pixflow_ImportDummyData');
function pixflow_ImportDummyData()
{
    @set_time_limit(0);
    // remove Front Page meta
    if (!isset($_SESSION['removeFrontPage'])) {
        $args = array(
            'post_type' => 'page'
        );
        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()) {
                $my_query->the_post();
                $id = get_the_ID();
                delete_post_meta($id, 'pixflow_front_page');
            } // end while
        } // end if
        wp_reset_postdata();
        $_SESSION['removeFrontPage'] = true;
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem(false,false,true);
    global $wp_filesystem;
    $demo = $_POST['demo'];
    $_SESSION['importDemo'] = $demo;
    $revslider = $_POST['revslider'];
    $purchase = $_POST['purchase'];
    $content = $_POST['content'];
    $setting = $_POST['setting'];
    $widget = $_POST['widget'];
    $media = $_POST['media'];
    $isStore = $_POST['isStore'];
    $revslider = ($revslider == 'false') ? false : true;
    $content = ($content == 'false') ? false : true;
    $setting = ($setting == 'false') ? false : true;
    $widget = ($widget == 'false') ? false : true;
    $media = ($media == 'false') ? false : true;
    $isStore = ($isStore == 'false') ? false : $isStore;
    // Check woocommerce is active
    $revsliderErr = false;
    $woocommerceErr = false;
    $cf7Err = false;

    $woocommerce = 'deactive';
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || class_exists('WooCommerce')) {
        $woocommerce = 'active';
    }
    // Check contact form 7 is active
    $cf7 = 'deactive';
    if ((is_plugin_active('contact-form-7/wp-contact-form-7.php') || defined('WPCF7_PLUGIN'))) {
        $cf7 = 'active';
    }

    // Check Rev Slider is active
    $revsliderStatus = 'deactive';
    if (class_exists('RevSlider')) {
        $revsliderStatus = 'active';
    }
    if ($isStore && $woocommerce == 'deactive') {
        $woocommerceErr = true;
    }
    if ($revslider && $revsliderStatus == 'deactive') {
        $revsliderErr = true;
    }
    if ($cf7 == 'deactive' && $content == true) {
        $cf7Err = true;
    }

    if ($cf7Err == true || $pxPortfolioErr == true || $woocommerceErr == true || $revsliderErr == true) {
        $err = array();
        if ($cf7Err == true) {
            $err[] = esc_attr__('Please install & activate ContactForm7 before importing this demo data.', 'massive-dynamic');
        }
        if ($revsliderErr == true) {
            $err[] = esc_attr__('Please install & activate Revolution Slider before importing this demo data.', 'massive-dynamic');
        }

        if ($woocommerceErr == true) {
            $err[] = esc_attr__('Please install & activate WooCommerce before importing this demo data.', 'massive-dynamic');
        }
        print(json_encode($err));
        die();
    }

    $upload_dir = wp_upload_dir();
    if (!isset($_SESSION['importRemove']) || $_SESSION['importRemove'] !== true) {
        if (is_dir($upload_dir['basedir'] . '/demo')) {
            $dirPath = $upload_dir['basedir'] . '/demo/demo' . $demo . '/';
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
	            $wp_filesystem->delete($file);
            }

            $wp_filesystem->rmdir( $dirPath );
            $wp_filesystem->rmdir( $upload_dir['basedir'] . '/demo/' );
        }
        $_SESSION['importRemove'] = true;
        echo 'continue';
        return;
    }
    if (!isset($_SESSION['importFiles'])) {
        $files = array();
        if ($revslider && $content) {
            $files[] = 'revslider.zip';
        }
        if ($content) {
            $files[] = 'content.xml';
        }
        if ($setting) {
            $files[] = 'customizer.dat';
        }
        if ($widget) {
            $files[] = 'widget.json';
        }
        $_SESSION['importFiles'] = $files;
    } else {
        $files = $_SESSION['importFiles'];
    }



    $demo_url = 'http://massivedynamic.co/dummydata/demo'.$demo;
    wp_remote_get( $demo_url.'/content.xml', array(
            'timeout' => 45,
        )
    );

    $upload_dir = wp_upload_dir();
    $d_path = $upload_dir['basedir'] . '/demo/';
    $filepath = PIXFLOW_THEME_DEMOS . '/demo' . $demo . '.zip';
    $unzipfile = unzip_file($filepath, $d_path);
    if (is_wp_error($unzipfile)) {
        define('FS_METHOD', 'direct'); //lets try direct.
        WP_Filesystem();  //WP_Filesystem() needs to be called again since now we use direct !
        $unzipfile = unzip_file($filepath, $d_path);
    }
    if (!is_wp_error($unzipfile)) {
        $_SESSION['importFiles'] = array();
        global $md_allowed_HTML_tags;
        echo wp_kses('extracted successfully<br/>',array("br"=>array()));
    } else {
        $message = $unzipfile->get_error_message();
        $wp_filesystem->delete($d_path, true);
        return;
    }

    // Import Content
    if ($content && !isset($_SESSION['importContent'])) {

        if (!class_exists('WP_Import')) {
            //Try to use custom version of the plugin
            require_once PIXFLOW_THEME_INCLUDES . '/demo-importer/wordpress-importer.php';
        }
        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = $media;
        // dont remove it
        /*if($media == false){
            $wp_import->placeholder = true;
        }*/
        echo wp_kses("-- Importing Content.xml File <br /> <br />",array("br"=>array()));
        $wp_import->import($upload_dir['basedir'] . '/demo/demo' . $demo . '/content.xml');
        $_SESSION['importContent'] = true;
        die('continue:1/1');
    }

    // Import Customizer Setting
    if ($setting) {
        if (!class_exists('Pixflow_CEI_Core')) {
            require_once PIXFLOW_THEME_INCLUDES . '/demo-importer/class-pixflow-cei-core.php';
        }
        global $wp_customize;
        //$customizer_file = content_url().'/uploads/demo/customizer.dat';
        echo wp_kses("-- Importing Customizer Setting <br /><br />",array("br"=>array()));
        Pixflow_CEI_Core::init('import', $wp_customize, $upload_dir['basedir'] . '/demo/demo' . $demo . '/customizer.dat');

        //set navigation to theme locations
        $d_path = $d_path . 'demo' . $demo . '/';
        $customizerResponse = ($wp_filesystem->exists($d_path . 'customizer.dat')) ? @file_get_contents($d_path . 'customizer.dat') : '';
        if ($customizerResponse == '') {
            echo esc_attr('customizer.dat does not exist!');
        }
        $contentResponse = ($wp_filesystem->exists($d_path . 'content.xml')) ? @file_get_contents($d_path . 'content.xml') : '';
        if ($contentResponse == '') {
            echo esc_attr('content.xml does not exist!');
        }

        $pos = strpos($customizerResponse, 'a:3');
        $customizerResponse = substr($customizerResponse, $pos);
        $data = @unserialize($customizerResponse);
        $menu_id = $data['mods']['nav_menu_locations']['primary-nav'];
        preg_match("~<wp:term>.*?<wp:term_id>$menu_id</wp:term_id>.*?<wp:term_slug><!\[CDATA\[(.*?)\]\]></wp:term_slug>~is", $contentResponse, $res);
        $term = get_term_by('slug', $res[1], 'nav_menu');
        $menu_id = $term->term_id;
        echo wp_kses("-- Menu Configuration <br /><br />",array("br"=>array()));
        if (is_int($menu_id)) {
            $locations['primary-nav'] = $menu_id;
            $locations['mobile-nav'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
        // Set Front Page
        $args = array(
            'post_type' => 'page',
            'meta_query' => array(
                array(
                    'key' => 'pixflow_front_page',
                    'value' => 'true',
                    'compare' => '=',
                    'type' => 'CHAR',
                ),
            ),
        );
        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()) {
                $my_query->the_post();
                $id = get_the_ID();
                update_option('show_on_front', 'page');
                update_option('page_on_front', $id);
            } // end while
        } // end if
        wp_reset_postdata();
    }

    //Import Widgets
    if ($widget) {
        // include Widget data class
        if (!class_exists('Pixflow_Widget_Data')) {
            require_once PIXFLOW_THEME_INCLUDES . '/demo-importer/class-widget-data.php';
        }

        $widget_data = new Pixflow_Widget_Data();
        echo wp_kses("-- Importing Widgets <br /><br />",array("br"=>array()));
        $widget_data->ajax_import_widget_data(content_url() . '/uploads/demo/demo' . $demo . '/widget.json');
    }


    // Import revslider
    if ($revslider) {

        require_once ABSPATH . 'wp-admin/includes/file.php';
        if (pixflow_get_filesystem()) {
            global $wp_filesystem;
        }
        $filepath = $upload_dir['basedir'] . '/demo/demo' . $demo . '/revslider.zip';
        ob_start();
        $slider = new RevSlider();
        echo wp_kses("-- Importing Revolution Slider <br /><br />",array("br"=>array()));
        $response = $slider->importSliderFromPost(true, true, $filepath, false, false);
        ob_end_clean();

    }


    // Remove Downloaded files
    $dirPath = $upload_dir['basedir'] . '/demo/demo' . $demo . '/';
    $files = glob($dirPath . '*', GLOB_MARK);

    foreach ($files as $file) {
        print("-- Removing $file <br /><br />");
	    $wp_filesystem->delete($file);
    }
	$wp_filesystem->rmdir($dirPath);
	$wp_filesystem->rmdir($upload_dir['basedir'] . '/demo/');

    unset($_SESSION['importPosts']);
    unset($_SESSION['importImported']);
    unset($_SESSION['importFiles']);
    unset($_SESSION['importRemove']);
    unset($_SESSION['importContent']);
    unset($_SESSION['importDemo']);
    unset($_SESSION['removeFrontPage']);
    unset($_SESSION['importDoIt']);
    unset($_SESSION['importProcessedPosts']);
    unset($_SESSION['importProcessedAuthors']);
    unset($_SESSION['importProcessedMenuItems']);
    unset($_SESSION['importProcessedTerms']);
    unset($_SESSION['importMenuItemOrphans']);
    unset($_SESSION['importMissingMenuItems']);

    wp_cache_flush();

    $catArr = get_terms(array(
        'taxonomy' => 'skills',
        'hide_empty' => false,
        'fields' => 'ids',
    ));
    if (count($catArr)) {
        wp_update_term_count_now($catArr, 'skills');
    }

    return true;
    die();
}

// Ajax Search
add_action('wp_ajax_pixflow_load_search_results', 'pixflow_load_search_results');
add_action('wp_ajax_nopriv_pixflow_load_search_results', 'pixflow_load_search_results');

function pixflow_load_search_results()
{
    $query = esc_attr($_POST['query']);

    $args = array(
        'post_status' => 'publish',
        's' => $query
    );
    $search = new WP_Query($args);

    ob_start();

    if ($search->have_posts()) :
        ?>
        <div class="search-title"><span
                class="stand-out"><?php echo sizeof($search->posts) ?></span> <?php echo esc_attr__('result(s) found for', 'massive-dynamic') ?>
            <span class="stand-out"><?php echo esc_attr($query); ?></span></div>
        <div class="row">
            <?php
            while ($search->have_posts()) : $search->the_post();
                $id = get_the_ID();
                $title = the_title('', '', false);
                $type = get_post_type($id);
                $thumbnail = (has_post_thumbnail()) ? get_post_thumbnail_id($id) : PIXFLOW_THEME_IMAGES_URI . '/placeholder-' . $type . '.jpg';
                if (is_numeric($thumbnail)) {
                    $thumbnail = wp_get_attachment_image_src($thumbnail, 'pixflow_post-related-sm');
                    $thumbnail = (false == $thumbnail) ? PIXFLOW_PLACEHOLDER_BLANK : $thumbnail[0];
                } ?>
                <div class="item col-lg-3 col-md-3 col-sm-3 col-xs-1">
                    <a href="<?php echo get_permalink() ?>">
                        <div class="thumbnail" style="background-image: url('<?php echo esc_url($thumbnail); ?>')">
                            <div class="background-overlay"></div>
                        </div>
                        <h4 class="title"><?php echo esc_attr($title); ?></h4>
                    </a>
                </div>
                <?php
            endwhile; ?>
        </div>
        <a class="more-result"
           href="<?php echo get_search_link($query); ?>"><?php echo esc_attr__('See more results..', 'massive-dynamic') ?></a>
        <?php
    else :
        echo '<div class="search-title-empty">' . esc_attr__('Nothing Found!', 'massive-dynamic') . '</div>';
    endif;

    ob_get_flush();
    die();

}

// remove temp content and vars in frontend
add_action('get_header', 'pixflow_removeTemp');
function pixflow_removeTemp()
{
    // destroy session when site load in frontend
    if (is_customize_preview() == false) {
        unset($_SESSION['general_customized']);
        unset($_SESSION['unique_customized']);
        unset($_SESSION['temp_status']);
    }
}

if (basename($_SERVER['PHP_SELF']) == 'customize.php') {
    if (session_id() == '' && !headers_sent()) {
        session_start();
    }
    unset($_SESSION['temp_status']);
    echo wp_kses('&nbsp;<div class="customizer-beautifier"></div>',array('div' => array('class' => array())));
}

//Customizing wp_title
function pixflow_filter_wp_title($title, $separator)
{

    if (is_feed()) return $title;

    global $paged, $page;

    if (is_search()) {
        $title = sprintf(esc_attr__('Search results for %s', 'massive-dynamic'), '"' . get_search_query() . '"');

        if ($paged >= 2) {
            $title .= " $separator " . sprintf(esc_attr__('Page %s', 'massive-dynamic'), $paged);
        }
        $title .= " $separator " . get_bloginfo('name', 'display');
        return $title;
    }

    $title .= get_bloginfo('name', 'display');
    $site_description = get_bloginfo('description', 'display');

    if ($site_description && (is_home() || is_front_page())) {
        $title .= " $separator " . $site_description;
    }

    if ($paged >= 2 || $page >= 2) {
        $title .= " $separator " . sprintf(esc_attr__('Page %s', 'massive-dynamic'), max($paged, $page));
    }

    return $title;
}

add_filter('wp_title', 'pixflow_filter_wp_title', 10, 2);

/* Detect Browser */
function pixflow_detectBrowser($user_agent)
{
    if (empty($user_agent)) {
        return false;
    }
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
        return 'Internet explorer';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
        return 'Mozilla Firefox';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
        return 'Google Chrome';
    } else {
        return 'Something else';
    }
}

function pixflow_redirectToCustomizer($links)
{
    remove_filter('install_plugin_complete_actions', 'pixflow_redirectToCustomizer');
    return '';
}

// add a Customizer link to the WP Toolbar
function pixflow_custom_toolbar_link($wp_admin_bar)
{
    $site_url = get_site_url();
    if (is_home()) {
        $pageID = get_option('page_for_posts');
    } elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
        $pageID = get_option('woocommerce_shop_page_id');
    } else if(is_category()){
        $category_id = get_cat_ID( single_cat_title('' , false ) );
    }else{
        $pageID = get_the_ID();
    }
    if ($pageID != 0){
        $link = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    } else {
        $link = home_url('/');
    }
    $link = isset($category_id) ? '?cat=' . $category_id : $link ;
	$address = $link ;
    if( ( function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product() )  ){
	    global $wp;
	    $address =  strtok( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) , '?' );
    }else if( (true == is_singular( 'portfolio' )
               && 'standard' == pixflow_metabox('portfolio_options.template_type','standard')) ){
	    $address = $site_url . '/' . $link ;
    }
    // Add setting button
    $setting_button = array(
        'id' => 'md_customizer_button',
        'title' => __('site Setting','massive-dynamic'),
        'href' => admin_url('customize.php?url='.urlencode($address)),
        'meta' => array(
            'class' => 'pixflow_custom_links md_customizer',
            'title' => __('Edit Setting','massive-dynamic')
        )
    );
    // Add Builder button
    if(pixflow_is_builder_editable(get_the_ID()) == true){
	    $builder_url = (count($_GET))?'&':'?';
        $address .= $builder_url.'mbuilder=true';
        $builder_button = array(
		    'id' => 'md_setting_button',
		    'title' => __('Edit Content','massive-dynamic'),
		    'href' => $address,
		    'meta' => array(
			    'class' => 'pixflow_custom_links md_builder',
			    'title' => __('Edit Content','massive-dynamic')
		    )
	    );
    }

    if (!is_admin()) {
        $wp_admin_bar->add_node($setting_button);
        if(pixflow_is_builder_editable(get_the_ID()) == true){
            $wp_admin_bar->add_node($builder_button);
        }
    }

}

add_action('admin_bar_menu', 'pixflow_custom_toolbar_link', 999);

// Ensure cart contents update when products are added to the cart via AJAX
add_filter('woocommerce_add_to_cart_fragments', 'pixflow_woocommerce_header_add_to_cart_fragment');
function pixflow_woocommerce_header_add_to_cart_fragment($fragments)
{
    ob_start();
    global $woocommerce, $md_allowed_HTML_tags;

    do_action('woocommerce_before_mini_cart');
    ?>
    <ul class="cart_list product_list_widget ">

        <?php if (!$woocommerce->cart->is_empty()) : ?>

            <?php
            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {

                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
                    $product_price = apply_filters('woocommerce_cart_item_price', $woocommerce->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $url = wp_get_attachment_image_src(get_post_thumbnail_id($_product->id), 'woocommerce_cart_item_thumbnail');
                    $url = (false == $url) ? PIXFLOW_PLACEHOLDER_BLANK : $url['0'];
                    if ($url != '')
                        $thumbnail = '<div class="cart-img" style="background-image: url(' . $url . ')"></div>';

                    ?>
                    <li class="<?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
                        <?php
                        echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                            '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                            esc_url($woocommerce->cart->get_remove_url($cart_item_key)),
                            esc_attr__('Remove this item', 'massive-dynamic'),
                            esc_attr($product_id),
                            esc_attr($_product->get_sku())
                        ), $cart_item_key);
                        ?>
                        <?php if (!$_product->is_visible()) : ?>
                            <?php echo str_replace(array('http:', 'https:'), '', $thumbnail) . $product_name . '&nbsp;'; ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>">
                                <?php echo str_replace(array('http:', 'https:'), '', $thumbnail) . $product_name . '&nbsp;'; ?>
                            </a>
                        <?php endif; ?>
                        <?php echo wp_kses($woocommerce->cart->get_item_data($cart_item), $md_allowed_HTML_tags); ?>

                        <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); ?>
                    </li>
                    <?php
                }
            }
            ?>

        <?php else : ?>

            <li class="empty"><?php esc_attr_e('No products in the cart.', 'massive-dynamic'); ?></li>

        <?php endif; ?>

    </ul><!-- end product list -->

    <?php if (!WC()->cart->is_empty()) : ?>

    <p class="total"><strong><?php esc_attr_e('Subtotal', 'massive-dynamic'); ?>
            :</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

    <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

    <p class="buttons">
        <a href="<?php echo WC()->cart->get_cart_url(); ?>"
           class="button wc-forward"><?php esc_attr_e('View Cart', 'massive-dynamic'); ?></a>
        <a href="<?php echo WC()->cart->get_checkout_url(); ?>"
           class="button checkout wc-forward"><?php esc_attr_e('Checkout', 'massive-dynamic'); ?></a>
    </p>

<?php endif; ?>

    <?php do_action('woocommerce_after_mini_cart'); ?>
    <script type="text/javascript">pixflow_addToCart();</script>
    <?php
    $fragments['ul.cart_list'] = ob_get_clean();

    return $fragments;
}


// Style Our Customizer Button
function pixflow_style_admin_bar()
{
	$inline_css = '#wpadminbar .pixflow_custom_links a {' .
	              'text-transform: uppercase;' .
                  'background-color: transparent;' .
                  'font-size: 9px;' .
                  'color: #bbc5ff;' .
                  'letter-spacing: 2px;' .
                  'padding: 0 18px 0 18px !important;' .
                  'transition: all 0.3s ease-in;' .
                  'display: flex;'.
                  'align-items: center;'.
        '}' ;

    $inline_css .= '#wpadminbar .ab-top-menu>li.hover>.ab-item,'.
    '#wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus,'.
    '#wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, '.
    '#wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus{'.
        'background-color: transparent;' .
         'color: #bbc5ff;'.
         'opacity : .7'.
        '}';

	$inline_css .= '#wpadminbar .pixflow_custom_links {' .
            'background-color: #3242a2;' .
            'transition : background-color .3s;' .
        '}' ;

    $inline_css .= '#wpadminbar .pixflow_custom_links:hover {' .
        'background-color: #253074;' .
    '}' ;

	$inline_css .= '#wpadminbar .pixflow_custom_links a:hover {' .
            'background-color: rgba(75, 162, 94, 0.65);' .
            'color: #bbc5ff;' .
        '}' ;

	$inline_css .= '#wpadminbar .pixflow_custom_links a:before {' .
            'font: 400 13px/1 pixflow-font-library;' .
        '}' ;

	$inline_css .= '#wpadminbar .pixflow_custom_links a:hover:before {' .
            'color:#bbc5ff;' .
        '}' ;

    $inline_css .= '#wpadminbar .pixflow_custom_links.md_builder a:before {' .
            'content: "";' .
            'width:12px;'.
            'height:12px;'.
        '}' ;
    $inline_css .= '#wpadminbar #wp-admin-bar-md_setting_button a {' .
        'background-image : url('.PIXFLOW_THEME_LIB_URI.'/assets/img/edit-setting.png);'.
        'background-repeat:no-repeat;'.
        'background-position: 18px center;'.
        '}' ;

    $inline_css .= '#wpadminbar .pixflow_custom_links.md_customizer a:before {' .
            'content: "\e6e0";' .
        '}' ;

    $inline_css .= '#wpadminbar  li.pixflow_custom_links.md_customizer{' .
        '  margin-right: 5px !important;' .
        '}';

    $inline_css .= '#wpadminbar ul#wp-admin-bar-root-default>li img{' .
        'display: inline-block;' .
        '}';

	$inline_css .= '#wp-admin-bar-vc_inline-admin-bar-link {' .
            'display: none;' .
        '}' ;

	wp_add_inline_style('custom-style' , wp_strip_all_tags( $inline_css ) );
}
add_action('wp_enqueue_scripts', 'pixflow_style_admin_bar');

//set sefault setting for add to any plugin
if (function_exists('A2A_menu_locale')) {
    $options = get_option('addtoany_options');
    $options['display_in_posts_on_front_page'] = '-1';
    $options['display_in_posts_on_archive_pages'] = '-1';
    $options['display_in_excerpts'] = '-1';
    $options['display_in_posts'] = '-1';
    $options['display_in_feed'] = '-1';
    $options['display_in_pages'] = '-1';
    $options['display_in_posts_on_front_page'] = '-1';
    $options['display_in_cpt_portfolio'] = '-1';
    if (isset($options['display_in_cpt_product'])) {
        $options['display_in_cpt_product'] = '-1';
    }
    update_option('addtoany_options', $options);
}

//Load Google Font Dropdown
function pixflow_googleFontsDropDown()
{
    global $md_allowed_HTML_tags;
    $id = $_POST['id'];
    $gf = new PixflowGoogleFonts(pixflow_path_combine(PIXFLOW_THEME_LIB_URI, 'googlefonts.txt'));
    $fontNames = $gf->Pixflow_GetFontNames();
    $string = '';
    $default = ('PIXFLOW_' . defined(strtoupper(str_replace('-', '_', $id)))) ? constant('PIXFLOW_' . strtoupper(str_replace('-', '_', $id))) : '';
    $value = pixflow_get_theme_mod($id, $default);
    foreach ($fontNames as $font) {
        $selected = ($font == $value) ? 'selected' : '';
        $string .= '<span value="' . $font . '" class="select-item ' . $selected . '">' . $font . '<span class="cd-dropdown-option"></span></span>';
    }
    echo wp_kses($string, $md_allowed_HTML_tags);
    wp_die();
}

add_action('wp_ajax_nopriv_pixflow_googleFontsDropDown', 'pixflow_googleFontsDropDown');
add_action('wp_ajax_pixflow_googleFontsDropDown', 'pixflow_googleFontsDropDown');

//Get Metabox value from vafpress function
function pixflow_metabox($key, $default = null)
{
    $value = vp_metabox($key, $default);
    $value = (null == $value) ? $default : $value;
    return $value;
}

function pixflow_drfw_postID_by_url($url)
{
    global $wpdb;
    $id = url_to_postid($url);
    if ($id == 0) {
        $fileupload_url = get_option('fileupload_url', null) . '/';
        if (strpos($url, $fileupload_url) !== false) {
            $url = str_replace($fileupload_url, '', $url);
            $id = $wpdb->get_var($wpdb->prepare("SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '$url' AND wposts.post_type = 'attachment'"));
        }
    }
    return $id;
}

//Moving comment form to bottom
function pixflow_move_comment_field_to_bottom($fields)
{
    $comment_field = $fields['comment'];
    unset($fields['comment']);
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter('comment_form_fields', 'pixflow_move_comment_field_to_bottom');


//Add no-js class to body tag in a non hardcode way
add_action('body_class', 'pixflow_add_custom_bodyclass');

function pixflow_add_custom_bodyclass($classes)
{
    $classes[] = 'no-js';
    global $post;
    if(isset($post->ID)){
       $isOnePageScroll = get_post_meta($post->ID, 'one_page_scroll', true);
    }else{
       $isOnePageScroll = 'no' ;
    }
    if($isOnePageScroll == 'yes'){
        $classes[] = 'one_page_scroll';
        wp_enqueue_script('section_scroll', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'section_scroll.min.js'), array('main-custom-js'), PIXFLOW_THEME_VERSION, true);
    }
    if (is_customize_preview()) {
        $classes[] = 'compose-mode';
        $classes[] = 'pixflow-customizer';
    }
    return $classes;
}


// Allow users to upload to media library for using in icon shortcode
function pixflow_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'pixflow_mime_types');

// remove Open Sans font
add_action('wp_enqueue_scripts', 'pixflow_deregister_styles', 100);

function pixflow_deregister_styles()
{
    wp_deregister_style('open-sans');
    wp_register_style('open-sans', false);
    wp_enqueue_style('open-sans');
}

function pixflow_decodeSetting()
{
    list($detail, $setting_status, $pageID) = pixflow_getPageInfo();
    if (isset($_POST['customized'])) {
        $_SESSION[$setting_status . '_customized'] = json_decode(wp_unslash($_POST['customized']), true);
    }
    return true;
}

function pixflow_metaPageType()
{
    list($detail, $setting_status, $pageID) = pixflow_getPageInfo();
    if ($pageID != 0) {
        $link = get_permalink($pageID);
    } else {
        $link = '';
    }
    //Get sidebar
    $sidebar = '';
    if (is_single()) {
        $sidebar = 'blogSingle';
    } elseif ((is_front_page() && is_home()) || is_home()) {
        $sidebar = 'blogPage';
    } elseif (is_page()) {
        $sidebar = 'general';
    }
    if ((in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || class_exists('WooCommerce')) && function_exists('is_woocommerce')) {
        if (is_woocommerce()) {
            $sidebar = 'shop';
        }
    }
    echo '<meta sidebar-type="' . esc_attr($sidebar) . '" name="post-id" content="' . esc_attr($pageID) . '" setting-status="' . esc_attr($setting_status) . '" detail="' . esc_attr($detail) . '" page-url="' . esc_url($link) . '" />';
}

function pixflow_getPageInfo()
{
    if (is_home()) {
        $pageID = get_option('page_for_posts');
    } elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
        $pageID = get_option('woocommerce_shop_page_id');
    } else {
        $pageID = get_the_ID();
    }

    $post_type = get_post_type($pageID);
    if ((isset($_SESSION['temp_status'])) && ($_SESSION['temp_status']['id'] == $pageID || (!is_home()))) {
        $setting_status = $_SESSION['temp_status']['status'];
    } else {
        if (isset($_SESSION['temp_status'])) {
            unset($_SESSION['temp_status']);
        }
        if (is_single() && ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product')) {
            if (isset($_SESSION[$post_type . '_status'])) {
                $setting_status = $_SESSION[$post_type . '_status'];
                unset($_SESSION[$post_type . '_status']);
            } else {
                $setting_status = get_option($post_type . '_setting_status');
            }
        } else {
            $setting_status = get_post_meta($pageID, 'setting_status', true);
        }
    }
    $setting_status = ($setting_status == 'unique') ? 'unique' : 'general';
    if (is_singular('post')) {
        $detail = 'post';
    } elseif (is_singular('portfolio')) {
        $detail = 'portfolio';
    } elseif (is_singular('product')) {
        $detail = 'product';
    } else {
        $detail = 'other';
    }
    return array($detail, $setting_status, $pageID);
}


function pixflow_get_filesystem()
{
    $access_type = get_filesystem_method();
    if ($access_type === 'direct') {
        /* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
        $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
        /* initialize the API */
        if (!WP_Filesystem($creds)) {
            /* any problems and we exit */
            return false;
        }
        return true;
    } else {
        return false;
    }
}

function pixflow_forbiddenStyle()
{
    global $wp_styles;
    // loop over all of the registered scripts
    foreach ($wp_styles->registered as $handle => $data) {
        // remove it
        wp_deregister_style($handle);
        wp_dequeue_style($handle);
    }
    wp_enqueue_style("robotoFont", "https://fonts.googleapis.com/css?family=Roboto");
    wp_enqueue_style("forbiddenStyles", PIXFLOW_THEME_CSS_URI . "/forbidden-styles.min.css");
}

function pixflow_vcInstallationStyles()
{
    global $wp_styles;
    // loop over all of the registered scripts
    foreach ($wp_styles->registered as $handle => $data) {
        // remove it
        wp_deregister_style($handle);
        wp_dequeue_style($handle);
    }
    wp_enqueue_style("robotoFont", "https://fonts.googleapis.com/css?family=Roboto");
    wp_enqueue_style("vcInstallation", PIXFLOW_THEME_CSS_URI . "/vc-installation.min.css");
}

/*Make retina image for orginal file*/
function pixflow_makeRetiaImage($post_ID)
{
    return;
    $file = get_attached_file($post_ID);
    $path_parts = pathinfo($file);
    $retinaFile = $path_parts['dirname'] . '/' . $path_parts['filename'] . '@2x.' . $path_parts['extension'];
    copy($file, $retinaFile);
    return $post_ID;
}

add_filter('add_attachment', 'pixflow_makeRetiaImage', 10, 2);

/*Remove retina original image after delete*/
function pixflow_removeRetinaImage($post_ID)
{
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	WP_Filesystem(false , false , true);
	global $wp_filesystem;
    $file = get_attached_file($post_ID);
    $path_parts = pathinfo($file);
    $retinaFile = $path_parts['dirname'] . '/' . $path_parts['filename'] . '@2x.' . $path_parts['extension'];
	$wp_filesystem->delete($retinaFile);
    return $post_ID;
}

add_filter('delete_attachment', 'pixflow_removeRetinaImage', 10, 2);

/*********************************************************************/
/* Add featured post checkbox
/********************************************************************/

add_action('add_meta_boxes', 'pixflow_showPageTitleMetaBox');
function pixflow_showPageTitleMetaBox()
{
    add_meta_box('show_page_title_id', esc_attr__('Page Options', 'massive-dynamic'), 'pixflow_pageMetaBox_callback', 'page', 'normal', 'high');
}

function pixflow_pageMetaBox_callback($post)
{
    global $post;
    $onePageScroll = get_post_meta($post->ID, 'one_page_scroll', true);
    $showPageTitle = get_post_meta($post->ID, 'show_page_title', true);
    //$showPageTitle = ($showPageTitle === '')?'yes':$showPageTitle;
    ?>

    <br/>

    <input type="checkbox" name="one_page_scroll"
           value="yes" <?php echo esc_attr(($onePageScroll == 'yes') ? 'checked="checked"' : ''); ?>/> Section Scroll

    <br/>
    <br/>

    <input type="checkbox" name="show_page_title"
           value="yes" <?php echo esc_attr(($showPageTitle == 'yes') ? 'checked="checked"' : ''); ?>/> Display Page Title

    <br/>
    <br/>

    <?php
}

add_action('save_post', 'pixflow_savePageMetaBox');
function pixflow_savePageMetaBox()
{
    global $post;
    if (null != $post && !isset($_POST['show_page_title']) && !isset($_POST['one_page_scroll'])) {
        delete_post_meta($post->ID, 'one_page_scroll');
        delete_post_meta($post->ID, 'show_page_title');
    } else {
        if (isset($_POST['one_page_scroll'])) {
            update_post_meta($post->ID, 'one_page_scroll', $_POST['one_page_scroll']);
        }
        if (isset($_POST['show_page_title'])) {
            update_post_meta($post->ID, 'show_page_title', $_POST['show_page_title']);
        }
    }
}

// Embed pixflow metabox to theme, so we deactivate pixflow metabox anymore
function pixflow_deactivatePixflowMetabox($plugin, $network_activation)
{
    if (defined('PX_Metabox_VER')) {
        deactivate_plugins(WP_PLUGIN_DIR . '/pixflow-metabox/pixflow-metabox.php');
    }
}

add_action('activated_plugin', 'pixflow_deactivatePixflowMetabox', 10, 2);

/**
 * Try alternative way to test for bool value
 *
 * @param mixed
 * @param bool
 */
if (!function_exists('boolval')) {
    function boolval($BOOL, $STRICT = false)
    {

        if (is_string($BOOL)) {
            $BOOL = strtoupper($BOOL);
        }

        // no strict test, check only against false bool
        if (!$STRICT && in_array($BOOL, array(false, 0, NULL, 'FALSE', 'NO', 'N', 'OFF', '0'), true)) {

            return false;

            // strict, check against true bool
        } elseif ($STRICT && in_array($BOOL, array(true, 1, 'TRUE', 'YES', 'Y', 'ON', '1'), true)) {

            return true;

        }

        // let PHP decide
        return $BOOL ? true : false;
    }
}

// Fixt The Http Error on Uploading Images
add_filter('wp_image_editors', 'pixflow_change_graphic_lib');
function pixflow_change_graphic_lib($array)
{
    return array('WP_Image_Editor_GD', 'WP_Image_Editor_Imagick');
}


function unset_filters_for($hook = '')
{
    global $wp_filter;
    if (empty($hook) || !isset($wp_filter[$hook]))
        return;

    unset($wp_filter[$hook]);
}

unset_filters_for('vc_shortcode_output');

/*Set FrontPage post meta*/
function pixflow_setFrontPgaePostMeta($oldValue, $newValue)
{
    update_post_meta($oldValue, 'pixflow_front_page', 'false');
    update_post_meta($newValue, 'pixflow_front_page', 'true');
}
add_action('update_option_page_on_front', 'pixflow_setFrontPgaePostMeta', 10, 2);

/*
 * return lik for get started button on dashboard
 * @return string url of link
 * */
function pixflow_get_start_link($env = 'builder'){
    if (get_option('show_on_front') == 'posts' || (get_option('show_on_front') == 'page') && !is_object(get_post(get_option('page_on_front')))) {
        $sample_page_id = pixflow_get_sample_page_id();
        if(0 === $sample_page_id){
            $sample_page_id = pixflow_create_sample_page();
        }
        $url = get_permalink( $sample_page_id );
    }else{
        $url = home_url('/');
    }
    if($env == 'builder'){
        if( strpos($url , '?') !== false ){
            $url = $url.'&mbuilder=ture';
        }else{
            $url = $url.'?mbuilder=ture';
        }
    }elseif($env == 'customizer'){
        $url = admin_url('customize.php?url='.urlencode($url));
    }
    return  $url;
}

/*
 * create pixflow sample page
 * @return int id of pixflow sample page
 * */
function pixflow_create_sample_page(){
    $contentMassivePage = "
[vc_row row_type='image' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='yes' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='185' row_padding_bottom='195' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(255, 255, 255, 0.84)' row_image='http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/business-wom-an.jpg' row_image_position='top' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='yes' parallax_speed='4' align='yes'][vc_column][md_text md_text_alignment=\"center\" md_text_title_line_height=\"66\" md_text_desc_line_height=\"27\" md_text_title_bottom_space=\"23\" md_text_separator_bottom_space=\"10\" md_text_description_bottom_space=\"25\" md_text_title_separator=\"no\" md_text_separator_width=\"110\" md_text_separator_height=\"5\" md_text_separator_color=\"rgb(0, 255, 153)\" md_text_use_desc_custom_font=\"yes\" md_text_desc_google_fonts=\"font_family:Poppins%3A300%2Cregular%2C500%2C600%2C700|font_style:400%20regular%3A400%3Anormal\" md_text_style=\"solid\" md_text_solid_color=\"rgb(58, 58, 58)\" md_text_gradient_color=\"pixflow_base64eyJjb2xvcjEiOiIjODcwMmZmIiwiY29sb3IyIjoiIzA2ZmY2ZSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=\" md_text_title_size=\"40\" md_text_letter_space=\"-1\" md_text_hover_letter_space=\"-1\" md_text_easing=\"cubic-bezier(0.215, 0.61, 0.355, 1)\" md_text_use_title_custom_font=\"yes\" md_text_title_google_fonts=\"font_family:Poppins%3A300%2Cregular%2C500%2C600%2C700|font_style:600%20bold%20regular%3A600%3Anormal\" md_text_number=\"1\" md_text_title1_text=\"<div><span style='color: rgb(255, 255, 255); font-size: 56px; font-family: Poppins;' data-mce-style='color: #ffffff; font-size: 56px; font-family: Poppins;'>Your First Step In </span></div><div><span style='color: rgb(255, 255, 255); font-size: 56px; font-family: Poppins;' data-mce-style='color: #ffffff; font-size: 56px; font-family: Poppins;'>Massive </span><span style='color: rgb(255, 255, 255); font-size: 56px; font-family: Poppins;' data-mce-style='color: #ffffff; font-size: 56px; font-family: Poppins;'>Live Customizer</span></div>\" md_text_title1=\"pixflow_base64PGRpdj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiA1NnB4OyBmb250LWZhbWlseTogUG9wcGluczsgY29sb3I6IHJnYigwLCAwLCAwKTsiIGRhdGEtbWNlLXN0eWxlPSJmb250LXNpemU6IDU2cHg7IGZvbnQtZmFtaWx5OiBQb3BwaW5zOyBjb2xvcjogIzAwMDAwMDsiPllvdXIgRmlyc3QgU3RlcCBJbiA8L3NwYW4+PC9kaXY+PGRpdj48c3BhbiBzdHlsZT0iY29sb3I6IHJnYigwLCAwLCAwKTsiIGRhdGEtbWNlLXN0eWxlPSJjb2xvcjogIzAwMDAwMDsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IDU2cHg7IGZvbnQtZmFtaWx5OiBQb3BwaW5zOyIgZGF0YS1tY2Utc3R5bGU9ImZvbnQtc2l6ZTogNTZweDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7Ij5NYXNzaXZlIDwvc3Bhbj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiA1NnB4OyBmb250LWZhbWlseTogUG9wcGluczsiIGRhdGEtbWNlLXN0eWxlPSJmb250LXNpemU6IDU2cHg7IGZvbnQtZmFtaWx5OiBQb3BwaW5zOyI+TGl2ZSBDdXN0b21pemVyPC9zcGFuPjwvc3Bhbj48L2Rpdj4=\"          md_text_title2=\"Typography Shortcode\" md_text_title3=\"Typography Shortcode\" md_text_title4=\"Typography Shortcode\" md_text_title5=\"Typography Shortcode\" md_text_content_size=\"16\" md_text_content_color=\"rgb(82, 82, 82)\" md_text_use_button=\"no\" md_text_button_style=\"fade-oval\" md_text_button_text=\"READ MORE\" md_text_button_icon_class=\"icon-angle-right\" md_text_button_color=\"rgba(0,0,0,1)\" md_text_button_text_color=\"rgba(255,255,255,1)\" md_text_button_bg_hover_color=\"rgb(0,0,0)\" md_text_button_hover_color=\"rgb(255,255,255)\" md_text_button_size=\"standard\" left_right_padding=\"0\" md_text_button_url=\"#\" md_text_button_target=\"_self\" md_text_animation=\"no\" md_text_animation_speed=\"600\" md_text_animation_delay=\"0.3\" md_text_animation_position=\"bottom\" md_text_animation_show=\"scroll\" md_text_animation_easing=\"Power4.easeOut\" align=\"center\"  md_text_fonts=\"\"  md_text_use_title_slider=\"yes\"][/md_text][md_button button_style=\"fill-rectangle\" button_text=\"BEGIN HERE\" button_icon_class=\"icon-empty\" button_color=\"rgb(12, 119, 230)\" button_text_color=\"#fff\" button_bg_hover_color=\"#9b9b9b\" button_hover_color=\"#FFF\" button_size=\"standard\" left_right_padding=\"21\" button_align=\"center\" button_url=\"#\" button_target=\"_self\" md_button_animation_speed=\"400\" md_button_animation_delay=\"0.0\" md_button_animation_position=\"center\" md_button_animation_show=\"once\" align=\"center\"][/md_button][/vc_column][/vc_row][vc_row row_type='none' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='no' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='100' row_padding_bottom='100' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(0,0,0,0.2)' row_image_position='default' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='no' parallax_speed='1'][vc_column width=\"2/12\" css=\".vc_custom_1457598198860{padding-right: 50px !important;}\"][/vc_column][vc_column el_class=\"\" width=\"8/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"100\" padding_bottom=\"0\" padding_left=\"100\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:100px;padding-bottom:0px;padding-left:100px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_text md_text_alignment=\"center\" md_text_title_line_height=\"12\" md_text_desc_line_height=\"12\" md_text_title_bottom_space=\"13\" md_text_separator_bottom_space=\"10\" md_text_description_bottom_space=\"0\" md_text_title_separator=\"no\" md_text_separator_width=\"110\" md_text_separator_height=\"5\" md_text_separator_color=\"rgb(0, 255, 153)\" md_text_use_desc_custom_font=\"yes\" md_text_desc_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_style=\"solid\" md_text_solid_color=\"rgba(20,20,20,1)\" md_text_gradient_color=\"pixflow_base64eyJjb2xvcjEiOiIjODcwMmZmIiwiY29sb3IyIjoiIzA2ZmY2ZSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=\" md_text_title_size=\"32\" md_text_letter_space=\"2\" md_text_hover_letter_space=\"2\" md_text_easing=\"cubic-bezier(0.215, 0.61, 0.355, 1)\" md_text_use_title_custom_font=\"no\" md_text_title_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_number=\"1\" md_text_title1_text=\"<div><span style='color: rgb(77, 77, 77); font-weight: 500; font-family: Poppins; font-size: 14px;' data-mce-style='color: #4d4d4d; font-weight: 500; font-family: Poppins; font-size: 14px;'>See it yourself</span></div>\" md_text_title1=\"pixflow_base64PGRpdj48c3BhbiBzdHlsZT0iY29sb3I6IHJnYigxMiwgMTE5LCAyMzApOyBmb250LXdlaWdodDogNTAwOyBmb250LWZhbWlseTogUG9wcGluczsgZm9udC1zaXplOiAxNXB4OyIgZGF0YS1tY2Utc3R5bGU9ImNvbG9yOiAjMGM3N2U2OyBmb250LXdlaWdodDogNTAwOyBmb250LWZhbWlseTogUG9wcGluczsgZm9udC1zaXplOiAxNXB4OyI+U2VlIGl0IHlvdXJzZWxmPC9zcGFuPjwvZGl2Pg==\"       md_text_title2=\"Typography Shortcode\" md_text_title3=\"Typography Shortcode\" md_text_title4=\"Typography Shortcode\" md_text_title5=\"Typography Shortcode\" md_text_content_size=\"14\" md_text_content_color=\"rgba(20,20,20,1)\" md_text_use_button=\"no\" md_text_button_style=\"fade-oval\" md_text_button_text=\"READ MORE\" md_text_button_icon_class=\"icon-angle-right\" md_text_button_color=\"rgba(0,0,0,1)\" md_text_button_text_color=\"rgba(255,255,255,1)\" md_text_button_bg_hover_color=\"rgb(0,0,0)\" md_text_button_hover_color=\"rgb(255,255,255)\" md_text_button_size=\"standard\" left_right_padding=\"0\" md_text_button_url=\"#\" md_text_button_target=\"_self\" md_text_animation_speed=\"400\" md_text_animation_delay=\"0.0\" md_text_animation_position=\"center\" md_text_animation_show=\"once\"  md_text_fonts=\"\"  md_text_use_title_slider=\"yes\"][/md_text][md_text md_text_alignment=\"center\" md_text_title_line_height=\"40\" md_text_desc_line_height=\"25\" md_text_title_bottom_space=\"25\" md_text_separator_bottom_space=\"26\" md_text_description_bottom_space=\"0\" md_text_title_separator=\"yes\" md_text_separator_width=\"80\" md_text_separator_height=\"5\" md_text_separator_color=\"rgb(38, 38, 38)\" md_text_use_desc_custom_font=\"yes\" md_text_desc_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_style=\"solid\" md_text_solid_color=\"rgba(20,20,20,1)\" md_text_gradient_color=\"pixflow_base64eyJjb2xvcjEiOiIjODcwMmZmIiwiY29sb3IyIjoiIzA2ZmY2ZSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=\" md_text_title_size=\"32\" md_text_letter_space=\"-1\" md_text_hover_letter_space=\"-1\" md_text_easing=\"cubic-bezier(0.215, 0.61, 0.355, 1)\" md_text_use_title_custom_font=\"no\" md_text_title_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_use_title_slider=\"yes\"  md_text_number=\"1\" md_text_title1_text=\"<div><span style='position: relative; font-weight: 600; font-family: Poppins;' data-mce-style='position: relative; font-weight: 600; font-family: Poppins;'>Experience the first live </span><span style='position: relative; font-weight: 600; font-family: Poppins;' data-mce-style='position: relative; font-weight: 600; font-family: Poppins;'>text </span></div><div><span style='position: relative; font-weight: 600; font-family: Poppins;' data-mce-style='position: relative; font-weight: 600; font-family: Poppins;'>editor </span><span style='position: relative; font-weight: 600; font-family: Poppins;' data-mce-style='position: relative; font-weight: 600; font-family: Poppins;'>in the market</span></div>\" md_text_title1=\"pixflow_base64PGRpdj48c3BhbiBzdHlsZT0icG9zaXRpb246IHJlbGF0aXZlOyBmb250LXdlaWdodDogNjAwOyBmb250LWZhbWlseTogUG9wcGluczsiIGRhdGEtbWNlLXN0eWxlPSJwb3NpdGlvbjogcmVsYXRpdmU7IGZvbnQtd2VpZ2h0OiA2MDA7IGZvbnQtZmFtaWx5OiBQb3BwaW5zOyI+RXhwZXJpZW5jZSB0aGUgZmlyc3QgbGl2ZSA8L3NwYW4+PHNwYW4gc3R5bGU9InBvc2l0aW9uOiByZWxhdGl2ZTsgZm9udC13ZWlnaHQ6IDYwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7IiBkYXRhLW1jZS1zdHlsZT0icG9zaXRpb246IHJlbGF0aXZlOyBmb250LXdlaWdodDogNjAwOyBmb250LWZhbWlseTogUG9wcGluczsiPnRleHQgPC9zcGFuPjwvZGl2PjxkaXY+PHNwYW4gc3R5bGU9InBvc2l0aW9uOiByZWxhdGl2ZTsgZm9udC13ZWlnaHQ6IDYwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7IiBkYXRhLW1jZS1zdHlsZT0icG9zaXRpb246IHJlbGF0aXZlOyBmb250LXdlaWdodDogNjAwOyBmb250LWZhbWlseTogUG9wcGluczsiPmVkaXRvciA8L3NwYW4+PHNwYW4gc3R5bGU9InBvc2l0aW9uOiByZWxhdGl2ZTsgZm9udC13ZWlnaHQ6IDYwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7IiBkYXRhLW1jZS1zdHlsZT0icG9zaXRpb246IHJlbGF0aXZlOyBmb250LXdlaWdodDogNjAwOyBmb250LWZhbWlseTogUG9wcGluczsiPmluIHRoZSBtYXJrZXQ8L3NwYW4+PC9kaXY+\"  md_text_title2=\"Typography Shortcode\" md_text_title3=\"Typography Shortcode\" md_text_title4=\"Typography Shortcode\" md_text_title5=\"Typography Shortcode\" md_text_content_size=\"14\" md_text_content_color=\"rgba(20,20,20,1)\" md_text_use_button=\"no\" md_text_button_style=\"fade-oval\" md_text_button_text=\"READ MORE\" md_text_button_icon_class=\"icon-angle-right\" md_text_button_color=\"rgba(0,0,0,1)\" md_text_button_text_color=\"rgba(255,255,255,1)\" md_text_button_bg_hover_color=\"rgb(0,0,0)\" md_text_button_hover_color=\"rgb(255,255,255)\" md_text_button_size=\"standard\" left_right_padding=\"0\" md_text_button_url=\"#\" md_text_button_target=\"_self\" md_text_animation_speed=\"400\" md_text_animation_delay=\"0.0\" md_text_animation_position=\"center\" md_text_animation_show=\"once\" align=\"center\"  md_text_fonts=\"\" md_text_fonts=\"\"]<p><span style=\"color: #666666; font-size: 16px; font-weight: 300; font-family: Poppins;\" data-mce-style=\"color: #666666; font-size: 16px; font-weight: 300; font-family: Poppins;\">Now You Know Us We have never been so happy before. Massive Dynamic has over 10 years of experience in Design, Technology and Marketing. We take pride in delivering Intelligent Designs and Engaging. Now You Know Us We have never been so happy before.&nbsp;</span></p>[/md_text][/vc_column][vc_column width=\"2/12\" el_id='5822e016098b7'][/vc_column][/vc_row][vc_row row_type='image' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='no' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='165' row_padding_bottom='140' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(12, 119, 230, 0.75)' row_image='http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/fgh.jpg' row_image_position='bottom' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='yes' parallax_speed='4' align='no'][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"50\" padding_bottom=\"0\" padding_left=\"0\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:0px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_iconbox_side2 iconbox_side2_title=\"\" iconbox_side2_title_big=\"Unique Elements\" iconbox_side2_title_big_heading=\"h5\" iconbox_side2_description=\"Massive Builder provides a platform to simply drag & drop elements, choose styles and see the result instantly… You can literally create a whole website in minutes! \" iconbox_side2_type=\"icon\" iconbox_side2_icon=\"icon-android2\" iconbox_side2_alignment=\"left\" iconbox_side2_small_title_color=\"#12be83\" iconbox_side2_general_color=\"#ffffff\" iconbox_side2_icon_color=\"rgb(255, 255, 255)\" iconbox_side2_button=\"no\" iconbox_side2_button_style=\"fade-square\" iconbox_side2_button_text=\"Read more\" iconbox_side2_class=\"icon-empty\" iconbox_side2_button_color=\"#5e5e5e\" iconbox_side2_button_text_color=\"#fff\" iconbox_side2_button_bg_hover_color=\"#9b9b9b\" iconbox_side2_button_hover_color=\"#FFF\" iconbox_side2_button_size=\"standard\" iconbox_side2_left_right_padding=\"0\" iconbox_side2_button_url=\"#\" iconbox_side2_button_target=\"_self\" md_iconbox_side2_animation_speed=\"400\" md_iconbox_side2_animation_delay=\"0.0\" md_iconbox_side2_animation_position=\"center\" md_iconbox_side2_animation_show=\"once\"][/md_iconbox_side2][/vc_column][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"20\" padding_bottom=\"0\" padding_left=\"30\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:30px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_iconbox_side2 iconbox_side2_title=\"\" iconbox_side2_title_big=\"Drag & Drop\" iconbox_side2_title_big_heading=\"h5\" iconbox_side2_description=\"Massive Dynamic comes with most advanced live website builder on WordPress. Featuring latest web technologies, enjoyable UX and the most beautiful design trends. \" iconbox_side2_type=\"icon\" iconbox_side2_icon=\"icon-vector-square-1\" iconbox_side2_alignment=\"left\" iconbox_side2_small_title_color=\"#12be83\" iconbox_side2_general_color=\"#ffffff\" iconbox_side2_icon_color=\"rgb(255, 255, 255)\" iconbox_side2_button=\"no\" iconbox_side2_button_style=\"fade-square\" iconbox_side2_button_text=\"Read more\" iconbox_side2_class=\"icon-empty\" iconbox_side2_button_color=\"#5e5e5e\" iconbox_side2_button_text_color=\"#fff\" iconbox_side2_button_bg_hover_color=\"#9b9b9b\" iconbox_side2_button_hover_color=\"#FFF\" iconbox_side2_button_size=\"standard\" iconbox_side2_left_right_padding=\"0\" iconbox_side2_button_url=\"#\" iconbox_side2_button_target=\"_self\" md_iconbox_side2_animation_speed=\"400\" md_iconbox_side2_animation_delay=\"0.0\" md_iconbox_side2_animation_position=\"center\" md_iconbox_side2_animation_show=\"once\"][/md_iconbox_side2][/vc_column][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"0\" padding_bottom=\"0\" padding_left=\"50\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:50px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_iconbox_side2 iconbox_side2_title=\"\" iconbox_side2_title_big=\"Text Editor\" iconbox_side2_title_big_heading=\"h5\" iconbox_side2_description=\"Massive Builder provides a rich user experience for everyone, whether you are a web ninja or a WordPress beginner, it helps you create any website quickly.\" iconbox_side2_type=\"icon\" iconbox_side2_icon=\"icon-pencil-ruler\" iconbox_side2_alignment=\"left\" iconbox_side2_small_title_color=\"#12be83\" iconbox_side2_general_color=\"#ffffff\" iconbox_side2_icon_color=\"rgb(255, 255, 255)\" iconbox_side2_button=\"no\" iconbox_side2_button_style=\"fade-square\" iconbox_side2_button_text=\"Read more\" iconbox_side2_class=\"icon-empty\" iconbox_side2_button_color=\"#5e5e5e\" iconbox_side2_button_text_color=\"#fff\" iconbox_side2_button_bg_hover_color=\"#9b9b9b\" iconbox_side2_button_hover_color=\"#FFF\" iconbox_side2_button_size=\"standard\" iconbox_side2_left_right_padding=\"0\" iconbox_side2_button_url=\"#\" iconbox_side2_button_target=\"_self\" md_iconbox_side2_animation_speed=\"400\" md_iconbox_side2_animation_delay=\"0.0\" md_iconbox_side2_animation_position=\"center\" md_iconbox_side2_animation_show=\"once\"][/md_iconbox_side2][/vc_column][/vc_row][vc_row row_type='none' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='no' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='100' row_padding_bottom='30' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(0,0,0,0.2)' row_image_position='default' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='no' parallax_speed='1' align='no'][vc_column width=\"2/12\" el_id='582409850115c'][vc_empty_space height=\"60\" el_id='58240985011f7'][/vc_empty_space][/vc_column][vc_column width=\"8/12\"][md_text md_text_alignment=\"center\" md_text_title_line_height=\"43\" md_text_desc_line_height=\"12\" md_text_title_bottom_space=\"0\" md_text_separator_bottom_space=\"31\" md_text_description_bottom_space=\"0\" md_text_title_separator=\"no\" md_text_separator_width=\"110\" md_text_separator_height=\"5\" md_text_separator_color=\"rgb(50, 50, 50)\" md_text_use_desc_custom_font=\"yes\" md_text_desc_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_style=\"solid\" md_text_solid_color=\"rgba(20,20,20,1)\" md_text_gradient_color=\"pixflow_base64eyJjb2xvcjEiOiIjODcwMmZmIiwiY29sb3IyIjoiIzA2ZmY2ZSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=\" md_text_title_size=\"32\" md_text_letter_space=\"-1\" md_text_hover_letter_space=\"-1\" md_text_easing=\"cubic-bezier(0.215, 0.61, 0.355, 1)\" md_text_use_title_custom_font=\"no\" md_text_title_google_fonts=\"font_family:Roboto%3Aregular%2C100%2C100italic%2C300%2C300italic%2Citalic%2C500%2C500italic%2C700%2C700italic%2C900%2C900italic|font_style:400%20regular%3A400%3Anormal\" md_text_number=\"1\" md_text_title1_text=\"<div style='font-weight: 500; font-family: Poppins;font-weight: 500;font-family: Poppinsfont-weight: 600;font-family: Poppins' data-mce-style='font-weight: 500; font-family: Poppins;'><span data-mce-style='color: #2e2e2e; font-size: 32px; font-weight: 600;' style='color: rgb(46, 46, 46); font-size: 32px; font-weight: 600;'><span style='position: relative; font-weight: 300; font-family: Poppins;' data-mce-style='position: relative; font-weight: 300; font-family: Poppins;'>Take Control Of</span></span></div><div style='font-weight: 500; font-family: Poppins;font-weight: 500;font-family: Poppinsfont-weight: 600;font-family: Poppins' data-mce-style='font-weight: 500; font-family: Poppins;'><span style='font-size: 32px;' data-mce-style='font-size: 32px;'><span data-mce-style='color: #2e2e2e; font-weight: 600;' style='color: rgb(46, 46, 46); font-weight: 600;'>your </span><span data-mce-style='color: #2e2e2e; font-weight: 600;' style='color: rgb(46, 46, 46); font-weight: 600;'>text </span><span data-mce-style='color: #2e2e2e; font-weight: 600;' style='color: rgb(46, 46, 46); font-weight: 600;'>with single click</span></span><br></div>\" md_text_title1=\"pixflow_base64PGRpdiBzdHlsZT0iZm9udC13ZWlnaHQ6IDUwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7Zm9udC13ZWlnaHQ6IDUwMDtmb250LWZhbWlseTogUG9wcGluc2ZvbnQtd2VpZ2h0OiA2MDA7Zm9udC1mYW1pbHk6IFBvcHBpbnMiIGRhdGEtbWNlLXN0eWxlPSJmb250LXdlaWdodDogNTAwOyBmb250LWZhbWlseTogUG9wcGluczsiPjxzcGFuIGRhdGEtbWNlLXN0eWxlPSJjb2xvcjogIzJlMmUyZTsgZm9udC1zaXplOiAzMnB4OyBmb250LXdlaWdodDogNjAwOyIgc3R5bGU9ImNvbG9yOiByZ2IoNDYsIDQ2LCA0Nik7IGZvbnQtc2l6ZTogMzJweDsgZm9udC13ZWlnaHQ6IDYwMDsiPjxzcGFuIHN0eWxlPSJwb3NpdGlvbjogcmVsYXRpdmU7IGZvbnQtd2VpZ2h0OiAzMDA7IGZvbnQtZmFtaWx5OiBQb3BwaW5zOyIgZGF0YS1tY2Utc3R5bGU9InBvc2l0aW9uOiByZWxhdGl2ZTsgZm9udC13ZWlnaHQ6IDMwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7Ij5UYWtlIGNvbnRyb2wgb2Y8L3NwYW4+PC9zcGFuPjwvZGl2PjxkaXYgc3R5bGU9ImZvbnQtd2VpZ2h0OiA1MDA7IGZvbnQtZmFtaWx5OiBQb3BwaW5zO2ZvbnQtd2VpZ2h0OiA1MDA7Zm9udC1mYW1pbHk6IFBvcHBpbnNmb250LXdlaWdodDogNjAwO2ZvbnQtZmFtaWx5OiBQb3BwaW5zIiBkYXRhLW1jZS1zdHlsZT0iZm9udC13ZWlnaHQ6IDUwMDsgZm9udC1mYW1pbHk6IFBvcHBpbnM7Ij48c3BhbiBzdHlsZT0iZm9udC1zaXplOiAzMnB4OyIgZGF0YS1tY2Utc3R5bGU9ImZvbnQtc2l6ZTogMzJweDsiPjxzcGFuIGRhdGEtbWNlLXN0eWxlPSJjb2xvcjogIzJlMmUyZTsgZm9udC13ZWlnaHQ6IDYwMDsiIHN0eWxlPSJjb2xvcjogcmdiKDQ2LCA0NiwgNDYpOyBmb250LXdlaWdodDogNjAwOyI+eW91ciA8L3NwYW4+PHNwYW4gZGF0YS1tY2Utc3R5bGU9ImNvbG9yOiAjMmUyZTJlOyBmb250LXdlaWdodDogNjAwOyIgc3R5bGU9ImNvbG9yOiByZ2IoNDYsIDQ2LCA0Nik7IGZvbnQtd2VpZ2h0OiA2MDA7Ij50ZXh0IDwvc3Bhbj48c3BhbiBkYXRhLW1jZS1zdHlsZT0iY29sb3I6ICMyZTJlMmU7IGZvbnQtd2VpZ2h0OiA2MDA7IiBzdHlsZT0iY29sb3I6IHJnYig0NiwgNDYsIDQ2KTsgZm9udC13ZWlnaHQ6IDYwMDsiPndpdGggc2luZ2xlIGNsaWNrPC9zcGFuPjwvc3Bhbj48L2Rpdj4=\"    md_text_title2=\"Typography Shortcode\" md_text_title3=\"Typography Shortcode\" md_text_title4=\"Typography Shortcode\" md_text_title5=\"Typography Shortcode\" md_text_content_size=\"14\" md_text_content_color=\"rgba(20,20,20,1)\" md_text_use_button=\"no\" md_text_button_style=\"fade-oval\" md_text_button_text=\"READ MORE\" md_text_button_icon_class=\"icon-angle-right\" md_text_button_color=\"rgba(0,0,0,1)\" md_text_button_text_color=\"rgba(255,255,255,1)\" md_text_button_bg_hover_color=\"rgb(0,0,0)\" md_text_button_hover_color=\"rgb(255,255,255)\" md_text_button_size=\"standard\" left_right_padding=\"0\" md_text_button_url=\"#\" md_text_button_target=\"_self\" md_text_animation_speed=\"400\" md_text_animation_delay=\"0.0\" md_text_animation_position=\"center\" md_text_animation_show=\"once\" align=\"center\"  md_text_fonts=\"\"  md_text_use_title_slider=\"yes\"]<div class=\"disable-edit\" style=\"z-index: 100;\" data-mce-style=\"z-index: 100;\"> <br></div><p> <br></p>[/md_text][/vc_column][vc_column width=\"2/12\" el_id='58240985011af'][vc_empty_space height=\"60\" el_id='5824098501240'][/vc_empty_space][/vc_column][/vc_row][vc_row row_type='none' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='no' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='25' row_padding_bottom='0' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(0,0,0,0.2)' row_image_position='default' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='no' parallax_speed='1'][vc_column el_class=\"\" width=\"6/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"10\" padding_bottom=\"10\" padding_left=\"10\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:10px;padding-bottom:10px;padding-left:10px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_image_box_slider image_box_slider_image=\"http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/photo-1477973370894-00f376675344.jpg\" image_box_slider_height=\"400\" image_box_slider_size=\"cover\" image_box_slider_hover=\"no\" image_box_slider_hover_link=\"\" image_box_slider_effect_slider=\"fade\" image_box_slider_speed=\"3000\" image_box_slider_hover_effect=\"text\" image_box_slider_hover_text_effect=\"light\" image_box_slider_hover_text=\"Text Hover\" md_image_box_slider_animation_speed=\"400\" md_image_box_slider_animation_delay=\"0.0\" md_image_box_slider_animation_position=\"center\" md_image_box_slider_animation_show=\"once\"][/md_image_box_slider][/vc_column][vc_column el_class=\"\" width=\"6/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"0\" padding_right=\"10\" padding_bottom=\"10\" padding_left=\"10\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:0px;padding-right:10px;padding-bottom:10px;padding-left:10px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_image_box_slider image_box_slider_image=\"http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/Death_to_stock_photography_Wake_Up_1-870x587.jpg\" image_box_slider_height=\"400\" image_box_slider_size=\"cover\" image_box_slider_hover=\"no\" image_box_slider_hover_link=\"\" image_box_slider_effect_slider=\"fade\" image_box_slider_speed=\"3000\" image_box_slider_hover_effect=\"text\" image_box_slider_hover_text_effect=\"light\" image_box_slider_hover_text=\"Text Hover\" md_image_box_slider_animation_speed=\"400\" md_image_box_slider_animation_delay=\"0.0\" md_image_box_slider_animation_position=\"center\" md_image_box_slider_animation_show=\"once\"][/md_image_box_slider][/vc_column][/vc_row][vc_row row_type='none' type_width='full_size' box_size_states='content_box_size' el_class='' row_fit_to_height='no' row_vertical_align='no' row_equal_column_heigh='no' row_content_vertical_align='0' row_padding_top='0' row_padding_bottom='100' row_padding_right='0' row_padding_left='0' row_margin_top='0' row_margin_bottom='0' background_color='rgba(255,255,255,1)' row_webm_url='' row_mp4_url='' background_color_image='rgba(0,0,0,0.2)' row_image_position='default' row_bg_image_size_tab_image='cover' row_bg_repeat_image_gp='no' first_color='#000' second_color='#000' row_gradient_color='pixflow_base64eyJjb2xvcjEiOiIjZmZmIiwiY29sb3IyIjoicmdiYSgyNTUsMjU1LDI1NSwwKSIsImNvbG9yMVBvcyI6IjAuMDAiLCJjb2xvcjJQb3MiOiIxMDAuMDAiLCJhbmdsZSI6MH0=' row_image_position_gradient='fit' row_bg_image_size_tab_gradient='cover' row_bg_repeat_gradient_gp='no' row_inner_shadow='no' row_sloped_edge='no' row_slope_edge_position='top' row_sloped_edge_color='#000' row_sloped_edge_angle='-3' parallax_status='no' parallax_speed='1' align='no'][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"10\" padding_right=\"10\" padding_bottom=\"0\" padding_left=\"10\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\" el_id='5823172781703'][md_image_box_slider image_box_slider_image=\"http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/photo-1471171768346-d08fb2813c45.jpg\" image_box_slider_height=\"400\" image_box_slider_size=\"cover\" image_box_slider_hover=\"no\" image_box_slider_hover_link=\"\" image_box_slider_effect_slider=\"fade\" image_box_slider_speed=\"3000\" image_box_slider_hover_effect=\"text\" image_box_slider_hover_text_effect=\"light\" image_box_slider_hover_text=\"Text Hover\" md_image_box_slider_animation_speed=\"400\" md_image_box_slider_animation_delay=\"0.0\" md_image_box_slider_animation_position=\"center\" md_image_box_slider_animation_show=\"once\"][/md_image_box_slider][/vc_column][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"10\" padding_right=\"10\" padding_bottom=\"0\" padding_left=\"10\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" background_image=\"undefined\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\" el_id='5823172781791'][md_image_box_slider image_box_slider_image=\"http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/photo-1478207820126-2b0217b53ed1.jpg\" image_box_slider_height=\"400\" image_box_slider_size=\"cover\" image_box_slider_hover=\"no\" image_box_slider_hover_link=\"\" image_box_slider_effect_slider=\"fade\" image_box_slider_speed=\"3000\" image_box_slider_hover_effect=\"text\" image_box_slider_hover_text_effect=\"light\" image_box_slider_hover_text=\"Text Hover\" md_image_box_slider_animation_speed=\"400\" md_image_box_slider_animation_delay=\"0.0\" md_image_box_slider_animation_position=\"center\" md_image_box_slider_animation_show=\"once\"][/md_image_box_slider][/vc_column][vc_column el_class=\"\" width=\"4/12\" margin_top=\"0\" margin_right=\"0\" margin_bottom=\"0\" margin_left=\"0\" padding_top=\"10\" padding_right=\"10\" padding_bottom=\"0\" padding_left=\"10\" border_color=\"rgba(0,0,0,1)\" border_style=\"solid\" border_top_width=\"0\" border_right_width=\"0\" border_bottom_width=\"0\" border_left_width=\"0\" background_color=\"rgba(0,0,0,0)\" css=\"{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;border-color:rgba(0,0,0,1);border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;background-color:rgba(0,0,0,0);background-image:undefined;border-style:solid;background-size:;}\" md_laptop_visibility=\"yes\" md_tablet_portrait_visibility=\"yes\" md_tablet_landscape_visibility=\"yes\" md_mobile_portrait_visibility=\"yes\" md_mobile_landscape_visibility=\"yes\" md_tablet_portrait_width=\"0\"][md_image_box_slider image_box_slider_image=\"http://demo.massivedynamic.co/general/wp-content/uploads/2016/11/sas.jpg\" image_box_slider_height=\"400\" image_box_slider_size=\"cover\" image_box_slider_hover=\"no\" image_box_slider_hover_link=\"\" image_box_slider_effect_slider=\"fade\" image_box_slider_speed=\"3000\" image_box_slider_hover_effect=\"text\" image_box_slider_hover_text_effect=\"light\" image_box_slider_hover_text=\"Text Hover\" md_image_box_slider_animation_speed=\"400\" md_image_box_slider_animation_delay=\"0.0\" md_image_box_slider_animation_position=\"center\" md_image_box_slider_animation_show=\"once\"][/md_image_box_slider][/vc_column][/vc_row]
            ";

    global $user_ID;
    $page = array();
    $page['post_type'] = 'page';
    $page['post_content'] = $contentMassivePage;
    $page['post_parent'] = 0;
    $page['post_author'] = $user_ID;
    $page['post_status'] = 'publish';
    $page['post_title'] = esc_attr__('Test Page', 'massive-dynamic');
    $page_id = wp_insert_post($page);
    update_post_meta($page_id, 'pixflow_sample_page', 'true');
    return $page_id;
}

/*
 * get pixflow sample page id
 * @return int id of page if exist or 0 if page dose not exist
 * */
function pixflow_get_sample_page_id(){
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'pixflow_sample_page',
                'value' => 'true',
                'compare' => '=',
                'type' => 'CHAR',
            ),
        ),
        'post_type' => 'page',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    if (count($posts)>0) {
        $id = $posts[0]->ID;
    } else {
        $id =  0;
    }
    return $id;
}

/*
 * make default menu if there is no menu when theme activate
 * */
add_action('after_switch_theme', 'pixflow_create_default_menu');
function pixflow_create_default_menu()
{
    $menu_name = 'Main Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    if( !$menu_exists){
        // get id of pixflow sample page
        $sample_page_id = pixflow_get_sample_page_id();
        if(0 === $sample_page_id){
            $sample_page_id = pixflow_create_sample_page();
        }
        // get all page ids
        $args = array(
            'exclude' => $sample_page_id,
            'number' => 2,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        $menu_id = wp_create_nav_menu($menu_name);
        if(is_int($menu_id)){
            $locations['primary-nav'] = $menu_id;
            $locations['mobile-nav'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'     =>  __( 'Test Page', 'massive-dynamic' ),
                'menu-item-object-id' => $sample_page_id,
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish'
            ) );
            foreach ($pages as $page){
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => $page->post_title,
                    'menu-item-object-id' => $page->ID,
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish'
                ) );
            }
        }
    }
}

// detect new lines for editor
function pixflow_detectNewLines($value)
{
    $new = array();
    $newKey = 0;
//    $value = str_replace('!'.PIXFLOW_NL_TAG.'!',"\n",$value);
    $NewString = explode('<br />', nl2br($value));
    for ($i = 0; $i < count($NewString); $i++) {
        if (strlen($NewString[$i]) !== 1) {
            $new[$newKey] = trim($NewString[$i]);
            $newKey++;
        }
    }
    $NewString = implode('<br />', $new);
    return $NewString;
}
// detect text have p tag
function pixflow_detectPTags($value)
{
    if (strpos($value, '</p>') == false) :
        $NewString = '<p>' . $value . '</p>';
        return $NewString;
    else:
        return $value;
    endif;
}
//Remove unnecessary tags
function pixflow_detectBasetext($content)
{
    if (preg_match("/<div[^>]*?class=\"md-text-content\">(.*?)<\\/div>/si", $content, $match)) {
        $content = $match[1];
        return trim($content);
    } else {
        return $content;
    }
}
// output content shortcode without js and css
function pixflow_output_validation($content)
{
    if (strpos($content, '</style>') || strpos($content, '</script>')) {
        $content = preg_replace('/(<script[^>]*>.+?<\/script>)/s', '', $content);
//        $content = trim(preg_replace(array('/\r/', '/\n/' , '/<!--(.*)-->/Uis'), '',$content));
        return $content;
    } else {
        return $content;
    }
}
// use wp_filesystem for import
function pixflow_import_media($content)
{
    unset($_SESSION['pixflow_' . get_site_url() .'inlinejs']);
    $_SESSION['pixflow_' . get_site_url() .'inlinejs'] = $content;
}
// pixflow_get_inline_scripts
function pixflow_get_inline_scripts($data)
{
    global $jsString;
    if (preg_match_all('#<\s*?script\b[^>]*>(.*?)</script\b[^>]*>#s', $data, $match)) {
        foreach ($match[1] as $jsdata) {
            $jsString .= $jsdata;
        }
        return trim($jsString);
    } else {
        return "";
    }
}

/*
 * load dependency file (shortcode or widget) and return array of dependent js,css and shortcodes
 * @param string $name shortcode name or widget name
 * @param string $type can be shortcode or widget
 * @return array
*/
function pixflow_load_dependecy_file($name, $type = 'shortcode'){
    $return = array('js'=>'','css'=>'');

    if('shortcode'==$type){
        $path = PIXFLOW_THEME_SHORTCODES;
    }elseif('widget'==$type){
        $path = PIXFLOW_THEME_WIDGETS;
    }

    $dependency_list =  $path. '/' . $name . '/dependency.json';

    if(!file_exists($dependency_list)) {
        return $return;
    }
    $require_plugin = json_decode(@file_get_contents($dependency_list), true);
    if($require_plugin){
        $require_plugin['shortcode'] = (isset($require_plugin['shortcode']))?$require_plugin['shortcode']:array();
        return $require_plugin;
    }else{
        return $return;
    }
}

/*
 * load dependet scripts of shortcodes and widgets
 * @param array list of depentens plugins
 * @return string as dependent scripts
*/
function pixflow_load_dependent_scripts($require_plugins){
    global $pixflow_loaded_plugins;
    $pixflow_loaded_dependency = array();
    $scripts = '';
    // Load dependent plugin scripts
    if (count($require_plugins['js']) != 0 &&
        ( is_array( $require_plugins['js'] ) || is_object( $require_plugins['js']  ) ) ) {
        foreach ($require_plugins['js'] as $js_path) {
            if(file_exists(PIXFLOW_THEME_DIR . '/'. $js_path) &&
               array_search( $js_path , $pixflow_loaded_dependency , true ) == false ) {
                if(in_array($js_path,$pixflow_loaded_plugins)){
                    continue;
                }
                $scripts .= @file_get_contents(PIXFLOW_THEME_DIR . '/'. $js_path);
                $pixflow_loaded_dependency[] = $js_path ;
                $pixflow_loaded_plugins[] = $js_path ;
            }
        }
    }
    return $scripts;
}

/*
 * load dependet styles of shortcodes and widgets
 * @param array list of depentens plugins and shortcodes
 * @return string as dependent styles
*/
function pixflow_load_dependent_styles($require_plugins){
    global $pixflow_loaded_plugins;
    $styles = '';
    $pixflow_loaded_dependency = array();
    // Load dependent plugin styles
    if (count($require_plugins['css']) != 0 &&
        ( is_array( $require_plugins['css'] ) || is_object( $require_plugins['css'] ) ) ) {
        foreach ($require_plugins['css'] as $css_path) {
            if(file_exists(PIXFLOW_THEME_DIR . '/'. $css_path) &&
               array_search( $css_path , $pixflow_loaded_dependency , true ) == false ) {
                if(in_array($css_path,$pixflow_loaded_plugins)){
                    continue;
                }
                $styles .= @file_get_contents(PIXFLOW_THEME_DIR . '/'. $css_path);
                $pixflow_loaded_dependency[] = $css_path;
                $pixflow_loaded_plugins[] = $css_path;
            }
        }
    }
    return $styles;
}

// Load Require Plugin
function pixflow_load_dependency($name,$type = 'shortcode'){
    global $pixflow_loaded_shortcodes;
    global $pixflow_loaded_plugins;

    // Load dependency array
    $require_plugins = pixflow_load_dependecy_file($name,$type);
    $return = array(
        'js' => '' ,
        'css' => ''
    );

    // Load dependent Shortcodes
	if(isset($require_plugins['shortcode'])){
        foreach($require_plugins['shortcode'] as $dependent_shortcodes){
            if(in_array($dependent_shortcodes,$pixflow_loaded_shortcodes)){
               continue;
            }
            $shortcodes_files = pixflow_load_dependency($dependent_shortcodes,'shortcode');
            $return['js'] .= $shortcodes_files['js'];
            $return['css'] .= $shortcodes_files['css'];
            $return['js'] .= @file_get_contents(PIXFLOW_THEME_SHORTCODES . '/' . $dependent_shortcodes . '/script.min.js');
            $return['css'] .= @file_get_contents(PIXFLOW_THEME_SHORTCODES. '/' . $dependent_shortcodes . '/style.min.css');
            $shortcode_index_file = PIXFLOW_THEME_SHORTCODES . '/'. $dependent_shortcodes . '/index.php';
            if(file_exists($shortcode_index_file)) {
                require_once $shortcode_index_file;
            }
            $pixflow_loaded_shortcodes[] = $dependent_shortcodes;
        }
	}

    // Load dependent scripts
    $return['js'] .= pixflow_load_dependent_scripts($require_plugins);

    // Load dependent styles
    $return['css'] .= pixflow_load_dependent_styles($require_plugins);

	return $return;
}

/*
 * load required shortcodes that used do_shortcode
 * @param array list of dependents shortcodes
*/
function pixflow_load_do_shortcodes(){
    $do_shortcodes = array();
    // load video shortcode for loop-blog-video
    if ( (is_front_page() && is_home()) || (!is_front_page() && is_home())) {
        $do_shortcodes[] = 'md_video';
    }
    // load subscribe shortcode on single blog
    if (is_singular('post')) {
        $do_shortcodes[] = 'md_subscribe';
    }
    return $do_shortcodes;
}

function pixflow_rename_shortcode($value){
    return trim(str_replace('/index' , '' , $value));
}

function pixflow_get_assets_for_customizer($shortcodes_list){

    $js_content = $css_content = '';
    foreach($shortcodes_list as $shortcode):
        $dependencies = pixflow_load_dependency($shortcode);
        $js_content .= $dependencies['js'];
        $js_content .= @file_get_contents(PIXFLOW_THEME_LIB.'/shortcodes/'. $shortcode . '/script.min.js');
        $css_content .= $dependencies['css'];
        $css_content .= @file_get_contents(PIXFLOW_THEME_LIB.'/shortcodes/'. $shortcode . '/style.min.css');
    endforeach;

    wp_enqueue_style( 'custom-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'custom-style.min.css'));
    wp_add_inline_style( 'custom-style', $css_content );
    wp_enqueue_script( 'custom-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI,'custom-script.min.js'));
    wp_add_inline_script( 'main-custom-js', $js_content );
    return ;
}



// detect and call short code
function pixflow_get_style_script($atts, $content = null, $shortcodename = '')
{
    global $cssString;
    $shortCode_deny = array(
        'master_slider' => 'pixflow_sc_masterslider' ,
        'row' => 'mBuilder_vcrow' ,
        'col' => 'mBuilder_vccolumn'
    ) ;
    if (preg_match('/vc_/', $shortcodename)) {
        if ($shortcodename == 'vc_column_inner') {
            $funcName = 'mBuilder_vccolumn';
        } else {
            $funcName = str_replace('vc_', 'mBuilder_vc', $shortcodename);
        }
    } else {
        $funcName = str_replace('md', 'pixflow_sc', $shortcodename);
    }
    if(function_exists($funcName)){
        $output = call_user_func_array($funcName, array($atts, $content));
        if (is_customize_preview() == false && (!defined('DOING_AJAX') || !DOING_AJAX)) {
            if(array_search($funcName , $shortCode_deny) == FALSE ){
                /*
                pixflow_import_media(pixflow_get_inline_scripts($output));
                return pixflow_output_validation($output);
                */
                return $output;
            }else{
                return $output;
            }
        } else {
            return $output;
        }
    }else{
        return ;
    }
}

function pixflow_get_style_script_widget($widget_name = '')
{
    if($widget_name != '') {
        $dependent = pixflow_load_dependency($widget_name,'widget');
        if(file_exists(PIXFLOW_THEME_WIDGETS.'/'.$widget_name.'/script.js')) {
            wp_enqueue_script($widget_name, PIXFLOW_THEME_LIB_URI . '/widgets/' . $widget_name . '/script.min.js', array('main-custom-js'), PIXFLOW_THEME_VERSION, true);
        }
        if(file_exists(PIXFLOW_THEME_WIDGETS.'/'.$widget_name.'/style.css')){
            wp_enqueue_style($widget_name,PIXFLOW_THEME_LIB_URI.'/widgets/'.$widget_name.'/style.min.css');
        }

        wp_add_inline_script( $widget_name,$dependent['js'] );
        wp_add_inline_style( $widget_name,$dependent['css'] );
    }
    return ;
}
//extract spacing
function pixflow_extractSpacing($json = false, $marginTop = 0, $marginRight = 0, $marginBottom = 0, $marginLeft = 0, $paddingTop = 0, $paddingRight = 0, $paddingBottom = 0, $paddingLeft = 0)
{
    if ($json && $json != '') {
        $json = str_replace("``", '"', $json);
        $json = str_replace("'", '"', $json);
        $value = json_decode($json);
        $marginTop = $value->{"marginTop"};
        $marginRight = $value->{"marginRight"};
        $marginBottom = $value->{"marginBottom"};
        $marginLeft = $value->{"marginLeft"};
        $paddingTop = $value->{"paddingTop"};
        $paddingRight = $value->{"paddingRight"};
        $paddingBottom = $value->{"paddingBottom"};
        $paddingLeft = $value->{"paddingLeft"};
    }
    ob_start();
    ?>
    padding : <?php echo esc_attr($paddingTop . 'px ' . $paddingRight . 'px ' . $paddingBottom . 'px ' . $paddingLeft . 'px'); ?>;
    margin : <?php echo esc_attr($marginTop . 'px ' . $marginRight . 'px ' . $marginBottom . 'px ' . $marginLeft . 'px '); ?>;
    <?php
    return ob_end_flush();
}
// Get the browser info
function pixflow_get_browser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    $pattern = "";
    $ub='';
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    if (isset($ub)) {
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
        }
        $i = count($matches['browser']);
        if ($i != 1) {
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }
    }
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'agent' => $u_agent
    );
}
// THIS CODE IT 'S FOR ADDING DEFER AND ASYNCE TO JS
$GLOBALS['script_array'] = array('jquery-cookie', 'autoloadpost');
function add_defer_attribute($tag, $handle)
{
    $browser = pixflow_get_browser();
    if ($browser['name'] == 'Google Chrome' && !(preg_match('/Edge/' , $browser['agent'])) ) {
        foreach ($GLOBALS['script_array'] as $individual_script) {
            if ($individual_script == $handle  || preg_match('/nicescroll.min/', $tag) ) {
                return str_replace('src', 'async="async" src', $tag);
            } else if(preg_match('/jquery.js/', $tag)  || preg_match('/assets\/js\/plugins.js/', $tag) ) {
                return $tag;
            }
            else{
                return str_replace('src', 'defer src', $tag);
            }
        }
    }
    else
    {
        return $tag;
    }
}
// Defer the css files for renderbloking
function add_defers_attribute($tag, $handle)
{
    $browser = pixflow_get_browser();
    if ($browser['name'] == 'Google Chrome' && !(preg_match('/Edge/' , $browser['agent'])) ) {
        return str_replace('rel=\'stylesheet\'', ' rel="preload" as="style" onload="this.rel=\'stylesheet\'"', $tag);
    } else {
        return $tag;
    }
}
/*
if (!is_admin() && is_customize_preview() == false ) {
    add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);
    add_filter('style_loader_tag', 'add_defers_attribute', 10, 2);
}
*/
function pixflow_checkBase64($value){
    if( preg_match('/pixflow_base64/' , $value)){
        $value = str_replace('pixflow_base64' , '' , $value);
        $value = base64_decode($value);
    }
    return $value;
}


function pixflow_base64TextTitle( $content ) {

    $content = preg_replace_callback(
        "/md_text_title1=[\"']((?!pixflow_base64)(.*?)(?=md_text_))?/si",
        "pixflow_base64TextTitleReplace",
        $content);
    return $content;
}
function pixflow_base64TextTitleReplace($matches){
    if(isset($matches[1])) {
        $matches[1] = preg_replace("/['\"](?=[^'\"]*$)/s",'',$matches[1]);
        return 'md_text_title1="pixflow_base64' . base64_encode($matches[1]) . '" ';
    }
    return $matches[0];
}
add_filter( 'content_edit_pre', 'pixflow_base64TextTitle' );

add_filter( 'tiny_mce_before_init', 'pixflow_unsetAutoresizeOn' );
function pixflow_unsetAutoresizeOn( $init ) {
    unset( $init['wp_autoresize_on'] );
    return $init;
}

/*Allow uploader to upload fonts files*/
function pixflow_allow_font_upload ( $existing_mimes=array() ) {
    $existing_mimes['woff2'] = 'font/woff2';
    $existing_mimes['woff'] = 'font/woff';
    $existing_mimes['ttf'] = 'font/ttf';
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['eot'] = 'font/eot';
    return $existing_mimes;
}
add_filter('upload_mimes', 'pixflow_allow_font_upload');

/*add_filter('the_content', 'pixflow_un_autop',0);
function pixflow_un_autop($content){
    $content = str_replace("\n",'<!-- wpnl -->',$content);
    $content = str_replace('<p>','<'.PIXFLOW_P_TAG.'>',$content);
    $content = str_replace('</p>','</'.PIXFLOW_P_TAG.'>',$content);
    return $content;
}*/


// Add massive link edit button to each page
function pixflow_render_edit_button($actions, $page_object){
	$page_object = (array) $page_object ;
	if( pixflow_is_builder_editable( $page_object["ID"] ) == true ){
		$actions['massive-dynamic-link']  = '<a href="'. get_site_url() . '/?page_id=' . $page_object["ID"] .'&mbuilder=true'
		           .'" class="md-link">' . __('Edit with Massive Builder','massive-dynamic') . '</a>';
	}
	return $actions;
}
add_filter( 'page_row_actions', 'pixflow_render_edit_button' , 10 , 2 );

function render_close_button(){
        $view_link = get_permalink( get_the_ID() );
        return $view_link;
}


function pixflow_is_builder_editable($id){

	if( ( function_exists('is_shop') &&  (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() ||
	     is_checkout() || is_account_page() || is_wc_endpoint_url()) ) || ( true == is_home() || (true == is_singular( 'portfolio' )
	     && 'standard' == pixflow_metabox('portfolio_options.template_type','standard')) ) || ( get_option('page_for_posts') == $id  ) || is_customize_preview()) {
	    return false;
	}else{
	    return true ;
    }

}

/*
 * Fix issues  with upload custom fonts and files such as SVG that disabled from wordpress 4.7.1
 *  */
function pixflow_fix_upload_custom_issue($data, $file, $filename, $mimes) {
    global $wp_version;
    if ( $wp_version !== '4.7.1' && $wp_version !== '4.7.2' ) {
        return $data;
    }
    $filetype = wp_check_filetype( $filename, $mimes );
    return array(
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    );
}
add_filter( 'wp_check_filetype_and_ext', 'pixflow_fix_upload_custom_issue', 10, 4 );


/**
 * Empty All Caches When New Version Realesed
 *
 *
 * @return void
 * @since 2.0.0
 */

function pixflow_empty_cache(){
    $last_version = get_option('pixflow_md_version') ;
    if( $last_version != PIXFLOW_THEME_VERSION  ){
        pixflow_empty_massive_cache();
        pixflow_empty_w3_total_cache();
        pixflow_empty_super_cache();
        update_option('pixflow_md_version', PIXFLOW_THEME_VERSION );
    }
    return true ;
}

/**
 * Empty All Caches From w3 Total Cache Plugin
 *
 *
 * @return void
 * @since 2.0.0
 */

function pixflow_empty_w3_total_cache(){
    if ( function_exists('is_plugin_active') && is_plugin_active( 'w3-total-cache/w3-total-cache.php' )  ) {
        if (function_exists('w3tc_dbcache_flush')) {
            w3tc_flush_all();
        }
    }
    return true ;
}

/**
 * Empty All Caches From Super Cache Plugin
 *
 *
 * @return void
 * @since 2.0.0
 */
function pixflow_empty_super_cache() {
    if (function_exists('is_plugin_active') && is_plugin_active('wp-super-cache/wp-cache.php')) {
        echo "<!--[if !IE]> Clear Super Cache <![endif]-->";
    }
    return true ;
}

/**
 * Empty All Caches That MD Created
 *
 *
 * @return void
 * @since 2.0.0
 */
function pixflow_empty_massive_cache(){
    require_once ABSPATH . 'wp-admin/includes/file.php';
    if (pixflow_get_filesystem()) {
        global $wp_filesystem;
    }
    if( $wp_filesystem != null ){
        $wp_filesystem->rmdir( PIXFLOW_THEME_CACHE , true);

    }else{
        array_map('unlink', glob(PIXFLOW_THEME_CACHE . "/*.*"));
        rmdir(PIXFLOW_THEME_CACHE);
    }
    return true;
}


/**
 * Return The Font Family Of String In Style Attribute
 * @param String It Is String Variable
 *
 * @return Array The List Of Font Family That Used And Return False If Nothing Found
 * @since 2.0.0
 */
function pixflow_extract_font_families( $string ){
    $fonts = array();
    preg_match_all('/<[^>]+ (style=".*?")/i', $string , $style);
    foreach ($style[1] as $tag){
        preg_match('@font-family(\s*):(.*?)(\s?)("|;|$)@i',  $tag, $matches);
        preg_match('@font-weight(\s*):(.*?)(\s?)("|;|$)@i', $tag, $result);
        if (!empty($matches[2])) {
            $font_name = trim($matches[2]);
            $font_name .= !empty($result[2]) ? ':' . trim($result[2]) : '' ;
            $fonts[] = $font_name;
        }
    }
    if( count($fonts) > 0 ) {
        return $fonts ;
    }else{
        return false ;
    }
}


function pixflow_old_get_theme_mod($name, $default = null, $post_id = false)
{
    $post_id =  pixflow_get_post_id($post_id);
    $post_type = get_post_type($post_id);
    if ((isset($_SESSION['temp_status'])) && $_SESSION['temp_status']['id'] == $post_id) {
        $setting_status = $_SESSION['temp_status']['status'];
    } elseif (get_option('page_for_posts') != $post_id && ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product')) {
        if (isset($_SESSION[$post_type . '_status'])) {
            $setting_status = $_SESSION[$post_type . '_status'];
        } else {
            $setting_status = get_option($post_type . '_setting_status');
        }
    } else {
        $setting_status = get_post_meta($post_id, 'setting_status', true);
    }

    $setting_status = ($setting_status == 'unique') ? 'unique' : 'general';

    $customizedValues = (isset($_SESSION[$setting_status . '_customized'])) ? $_SESSION[$setting_status . '_customized'] : array();
    if (isset($_POST['customized'])) {
        $customizedValues = json_decode(wp_unslash($_POST['customized']), true);
    }

    if (count($customizedValues) && array_key_exists($name, $customizedValues)) {
        $value = $customizedValues[$name];

    } else {
        global $md_uniqueSettings;
        $settings = $md_uniqueSettings;

        if ($setting_status == 'unique' && in_array($name, $settings)) {

            if ($post_type == 'post' || $post_type == 'portfolio' || $post_type == 'product') {
                $value = get_option($post_type . '_' . $name);
                $value = ($value === false) ? get_theme_mod($name, $default) : $value;
            } else {
                $value = get_post_meta($post_id, $name, true);
                $value = ($value === 'false') ? false : $value;
            }

            if ($value === '') {
                $value = get_theme_mod($name, $default);
                $value = ($value === '') ? $default : $value;
            }
        } else {
            $value = get_theme_mod($name, $default);
        }
    }
    $value = ($value === 'false') ? false : $value;
    return $value;
}