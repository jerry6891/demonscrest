<?php
$data = $sidebarStyle = '';

//sidebar values
$sidebarSwitch = $sidebarBgType = '';
$sidebarWidth = 0;

//header value
$header = pixflow_get_theme_mod('header_position', PIXFLOW_HEADER_POSITION);
//navigation values
$navColor = pixflow_get_theme_mod('nav_color', PIXFLOW_NAV_COLOR);
$navHoverColor = pixflow_get_theme_mod('nav_hover_color', PIXFLOW_NAV_HOVER_COLOR);


if (is_home()) {
    $postID = get_option('page_for_posts');
} elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
    $postID = get_option('woocommerce_shop_page_id');
} else {
    $postID = get_the_ID();
}
if ((in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || class_exists('WooCommerce')) && function_exists('is_product')) {
    if (is_product()) {
        $postType = 'product';
    } else if ((is_woocommerce() || is_account_page())) {
        $postType = 'shop';
    }
}
if (is_single()) {
    $postType = 'single';
} else if (is_home() || (is_front_page() && is_home())) {
    $postType = 'blog';
} else if (is_page()) {
    $postType = 'page';
} elseif (function_exists('is_shop') && (is_shop() || is_product_category()) && !is_product()) {
    $postType = 'shop';
} else {
    $postType = 'blog';
    $postID = get_option('page_for_posts');
}


$sidebarType = $postType;

$sidebarType = ('product' == $sidebarType) ? 'shop' : $sidebarType;
$post_type = get_post_type();

if ($sidebarType == 'single' || $sidebarType == 'blog' || $sidebarType == 'shop') {
    $sidebarSwitch = pixflow_get_theme_mod('sidebar-switch-' . $sidebarType, constant(strtoupper('PIXFLOW_SIDEBAR_SWITCH_' . $sidebarType)));
    $sidebarWidth = pixflow_get_theme_mod('sidebar-width-' . $sidebarType, constant(strtoupper('PIXFLOW_SIDEBAR_WIDTH_' . $sidebarType)));
    $sidebarStyle = pixflow_get_theme_mod('sidebar-style-' . $sidebarType, constant(strtoupper('PIXFLOW_SIDEBAR_STYLE_' . $sidebarType)));
    $sidebarShadow = pixflow_get_theme_mod('sidebar-shadow-color-' . $sidebarType, constant(strtoupper('PIXFLOW_' . $sidebarType . '_SIDEBAR_SHADOW_COLOR')));

} else if ($post_type == 'page') {
    $sidebarSwitch = pixflow_get_theme_mod('sidebar-switch', PIXFLOW_SIDEBAR_SWITCH);
    $sidebarWidth = pixflow_get_theme_mod('sidebar-width', PIXFLOW_SIDEBAR_WIDTH);
    $sidebarStyle = pixflow_get_theme_mod('sidebar-style', PIXFLOW_SIDEBAR_STYLE);
    $sidebarShadow = pixflow_get_theme_mod('sidebar-shadow-color', PIXFLOW_PAGE_SIDEBAR_SHADOW_COLOR);
}


if ($sidebarType == 'single') {
    $sidebarShadow = pixflow_get_theme_mod('sidebar-shadow-color-single', PIXFLOW_SINGLE_SIDEBAR_SHADOW_COLOR);
}


/*====================================================
                    Heading
======================================================*/
$h1_font_family = (pixflow_get_theme_mod('h1_fontfamily_mode', PIXFLOW_H1_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h1_name', PIXFLOW_H1_NAME) : 'h1_custom_font';
$data .= 'h1{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h1_color', PIXFLOW_H1_COLOR)) . ';' .
    'font-family:' . $h1_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h1_weight', PIXFLOW_H1_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h1_style', PIXFLOW_H1_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h1_size', PIXFLOW_H1_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h1_lineHeight', PIXFLOW_H1_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h1_letterSpace', PIXFLOW_H1_LETTERSPACE)) . 'px' . ';' .
    '}';

$h2_font_family = (pixflow_get_theme_mod('h2_fontfamily_mode', PIXFLOW_H2_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h2_name', PIXFLOW_H2_NAME) : 'h2_custom_font';
$data .= 'h2{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h2_color', PIXFLOW_H2_COLOR)) . ';' .
    'font-family:' . $h2_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h2_weight', PIXFLOW_H2_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h2_style', PIXFLOW_H2_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h2_size', PIXFLOW_H2_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h2_lineHeight', PIXFLOW_H2_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h2_letterSpace', PIXFLOW_H2_LETTERSPACE)) . 'px' . ';' .
    '}';

$h3_font_family = (pixflow_get_theme_mod('h3_fontfamily_mode', PIXFLOW_H3_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h3_name', PIXFLOW_H3_NAME) : 'h3_custom_font';
$data .= 'h3,
h3.wpb_accordion_header,
h3.wpb_toggle_header{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h3_color', PIXFLOW_H3_COLOR)) . ';' .
    'font-family:' . $h3_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h3_weight', PIXFLOW_H3_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h3_style', PIXFLOW_H3_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h3_size', PIXFLOW_H3_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h3_lineHeight', PIXFLOW_H3_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h3_letterSpace', PIXFLOW_H3_LETTERSPACE)) . 'px' . ';' .
    '}';

$h4_font_family = (pixflow_get_theme_mod('h4_fontfamily_mode', PIXFLOW_H4_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h4_name', PIXFLOW_H4_NAME) : 'h4_custom_font';
$data .= 'h4{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h4_color', PIXFLOW_H4_COLOR)) . ';' .
    'font-family:' . $h4_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h4_weight', PIXFLOW_H4_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h4_style', PIXFLOW_H4_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h4_size', PIXFLOW_H4_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h4_lineHeight', PIXFLOW_H4_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h4_letterSpace', PIXFLOW_H4_LETTERSPACE)) . 'px' . ';' .
    '}';

$h5_font_family = (pixflow_get_theme_mod('h5_fontfamily_mode', PIXFLOW_H5_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h5_name', PIXFLOW_H5_NAME) : 'h5_custom_font';
$data .= 'h5{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h5_color', PIXFLOW_H5_COLOR)) . ';' .
    'font-family:' . $h5_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h5_weight', PIXFLOW_H5_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h5_style', PIXFLOW_H5_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h5_size', PIXFLOW_H5_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h5_lineHeight', PIXFLOW_H5_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h5_letterSpace', PIXFLOW_H5_LETTERSPACE)) . 'px' . ';' .
    '}';

$h6_font_family = (pixflow_get_theme_mod('h6_fontfamily_mode', PIXFLOW_H6_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('h6_name', PIXFLOW_H6_NAME) : 'h6_custom_font';
$data .= 'h6{' .
    'color:' . esc_attr(pixflow_get_theme_mod('h6_color', PIXFLOW_H6_COLOR)) . ';' .
    'font-family:' . $h6_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h6_weight', PIXFLOW_H6_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h6_style', PIXFLOW_H6_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('h6_size', PIXFLOW_H6_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('h6_lineHeight', PIXFLOW_H6_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h6_letterSpace', PIXFLOW_H6_LETTERSPACE)) . 'px' . ';' .
    '}';

$paragraph_font_family = (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';
$data .= 'p{' .
    'color:' . esc_attr(pixflow_get_theme_mod('p_color', PIXFLOW_P_COLOR)) . ';' .
    'font-family:' . $paragraph_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('p_weight', PIXFLOW_P_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('p_style', PIXFLOW_P_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('p_size', PIXFLOW_P_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('p_lineHeight', PIXFLOW_P_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('p_letterSpace', PIXFLOW_P_LETTERSPACE)) . 'px' . ';' .
    '}';

$link_font_family = (pixflow_get_theme_mod('link_fontfamily_mode', PIXFLOW_LINK_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('link_name', PIXFLOW_LINK_NAME) : 'link_custom_font';
$data .= 'a{' .
    'color:' . esc_attr(pixflow_get_theme_mod('link_color', PIXFLOW_LINK_COLOR)) . ';' .
    'font-family:' . $link_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('link_weight', PIXFLOW_LINK_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('link_style', PIXFLOW_LINK_STYLE)) . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('link_size', PIXFLOW_LINK_SIZE)) . 'px' . ';' .
    'line-height:' . esc_attr(pixflow_get_theme_mod('link_lineHeight', PIXFLOW_LINK_LINEHEIGHT)) . 'px' . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('link_letterSpace', PIXFLOW_LINK_LETTERSPACE)) . 'px' . ';' .
    '}';


/*====================================================
                    Layout
======================================================*/
$data .= '.layout{' .
    'padding-top:' . esc_attr(pixflow_get_theme_mod('site_top', PIXFLOW_SITE_TOP)) . 'px;' .
    'padding-bottom:' . esc_attr(pixflow_get_theme_mod('footer-marginB', PIXFLOW_FOOTER_MARGINB)) . 'px;'.
    'width:'.esc_attr(pixflow_get_theme_mod('site_width',PIXFLOW_SITE_WIDTH)).'%;}';

$data .= 'main{' .
    'padding-top:' . esc_attr(pixflow_get_theme_mod('main-top', PIXFLOW_MAIN_TOP)) . 'px;}';


/*====================================================
                    Header
======================================================*/
if (!pixflow_get_theme_mod('notification_enable', PIXFLOW_NOTIFICATION_ENABLE)) {
    $data .= 'header .content ul.icons-pack li.icon ,header.top-block .style-style2 .icons-pack .icon.notification-item{display:none;}';
} else {
    if ((!pixflow_get_theme_mod('shop_cart_enable', PIXFLOW_SHOP_CART_ENABLE) || !pixflow_get_theme_mod('notification_cart', PIXFLOW_NOTIFICATION_CART))) {
        $data .= ' header.side-classic .icons-holder ul.icons-pack > li.icon.shopcart-item,header ul.icons-pack li.shopcart-item, header .mobile-shopcart,header.top-block .style-style2 .icons-pack li.icon.shopcart-item {display:none;}';
    }

    $notifyPosts = pixflow_get_theme_mod('notification_post', PIXFLOW_NOTIFICATION_POST);
    $notifyPortfolio = pixflow_get_theme_mod('notification_portfolio', PIXFLOW_NOTIFICATION_PORTFOLIO);
    $notifyIcon = pixflow_get_theme_mod('active_icon', PIXFLOW_ACTIVE_ICON);
    if ((!$notifyPosts && !$notifyPortfolio) || (!$notifyIcon)) {
        $data .= 'header.side-classic .icons-holder ul.icons-pack > li.icon.notification-item,
        header ul.icons-pack li.notification-item,
        header.top-block .style-style2 .icons-pack .icon.notification-item{display:none;}';
    }
    if ((!pixflow_get_theme_mod('search_enable', PIXFLOW_SEARCH_ENABLE) || !pixflow_get_theme_mod('notification_search', PIXFLOW_SEARCH_ENABLE))) {
        $data .= 'header.side-classic .icons-holder ul.icons-pack > li.icon.search-item,
        header ul.icons-pack li.search-item,
        header.top-block .style-style2 .icons-pack .icon.search-item{display:none;}';
    }
}

/*================= General Styles ================ */

$headerPosition = ($header == 'left' || $header == 'right') ? 'side' : 'top';
$headerSideTheme = pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME);
$headerTopTheme = pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME);
$headerTopBlockSkin = pixflow_get_theme_mod('block_style', PIXFLOW_BLOCK_STYLE);
$businessEnable = pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE);
$headerTopPos = pixflow_get_theme_mod('header_top_position', PIXFLOW_HEADER_TOP_POSITION);


if ($headerPosition == 'top' && !$businessEnable) {
    $data .= 'header { top:' . esc_attr($headerTopPos) . 'px;}';
} else if ($headerPosition == 'top' && $businessEnable && $headerTopPos > 36) {
    $data .= 'header {top:' . esc_attr($headerTopPos) . 'px;}';
} else if ($headerPosition == 'top' && $businessEnable && $headerPosition < 36) {
    if ($headerTopTheme == 'modern') {
        $data .= 'header { top:' . esc_attr($headerTopPos) . 'px;}';
    } else {
        $data .= 'header {top: 36px;}';
    }
}

$headerSideWidth = (pixflow_get_theme_mod('header-side-width',PIXFLOW_HEADER_SIDE_WIDTH)>40)?40:pixflow_get_theme_mod('header-side-width',PIXFLOW_HEADER_SIDE_WIDTH);
$headerSideWidth = ($headerSideTheme == 'modern')?3:$headerSideWidth;
if($headerSideTheme == 'modern'){
    $headerStyle = 'width: 65px;';
}else{
    $headerStyle = 'width: ' . $headerSideWidth . '%;';
}
$data .= 'header[class *= "side-" ]{'.esc_attr($headerStyle).';}';


$menu_item_styleDefault = ($headerPosition == 'top' && $headerTopTheme == 'block') ? 'icon-text' : 'text';
$menu_item_style = pixflow_get_theme_mod('menu_item_style', $menu_item_styleDefault);
if ($menu_item_style == 'icon' || ($headerPosition == 'side' && $headerSideTheme == 'modern') || ($headerPosition == 'top' && $headerTopTheme == 'block' && $headerTopBlockSkin == 'style2')) {
    $data .= 'header:not(.top-block) .top nav > ul > li .menu-title .icon ,header:not(.top-block) .top nav > ul > li .hover-effect .icon {display:inline-block;}
    header.side-classic .side nav > ul > li > a .menu-title .icon{display:block}
    header:not(.top-block) .top nav > ul > li .menu-title .title,
    header.side-classic .side nav > ul > li > a .menu-title .title,
    header:not(.top-block) .top nav > ul > li .hover-effect .title {display:none;}';
} else if ($menu_item_style == 'text') {
    $data .= 'header:not(.top-block) .top nav > ul > li .menu-title .icon ,
    header.side-classic .side nav > ul > li > a .menu-title .icon,
    header.side-classic.standard-mode .style-center nav > ul > li > a .menu-title .icon,
    .gather-overlay .navigation li a span.icon,
    header.top-block.header-style1 .navigation > ul > li > a span.icon,
    header:not(.top-block) .top nav > ul > li .hover-effect .icon {display:none;}
    header:not(.top-block) .top nav > ul > li .menu-title .title,
    header.side-classic .side nav > ul > li > a .menu-title .title,
    header:not(.top-block) .top nav > ul > li .hover-effect .title {display:inline-block;}';
} else {
    $data .= 'header:not(.top-block) .top nav > ul > li .menu-title .icon ,
    header:not(.top-block) .top nav > ul > li .hover-effect .icon ,
    header:not(.top-block) .top nav > ul > li .menu-title .title,
    header.side-classic .side nav > ul > li > a .menu-title .title,
    header:not(.top-block) .top nav > ul > li .hover-effect .title {display:inline-block;}
    header.side-classic .side nav > ul > li > a .menu-title .icon{display:inline-block}
    header.side-classic .style-center nav > ul > li > a .menu-title .icon{display:block}';
}


$data .= '.activeMenu{ color:' . esc_attr($navHoverColor) . ' !important;}';

$header_font_family = (pixflow_get_theme_mod('nav_fontfamily_mode', PIXFLOW_NAV_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('nav_name', PIXFLOW_NAV_NAME) : 'nav_custom_font';
$data .= 'header a,
header .navigation a,
header .navigation,
.gather-overlay .menu a,
header.side-classic div.footer .footer-content .copyright p{
    color:' . esc_attr($navColor) . ';' .
    'font-family:' . $header_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('nav_weight', PIXFLOW_NAV_WEIGHT)) . ';' .
    'font-style:' . esc_attr((pixflow_get_theme_mod('nav_style', PIXFLOW_NAV_STYLE) == true) ? 'italic' : 'normal') . ';' .
    'font-size:' . esc_attr(pixflow_get_theme_mod('nav_size', PIXFLOW_NAV_SIZE)) . 'px;' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('nav_letterSpace', PIXFLOW_NAV_LETTERSPACE)) . 'px;' .
    'line-height : 1.5em;' .
    '}';

$data .= 'header .icons-pack a{color:' . esc_attr($navColor) . '}';

$data .= 'header .navigation .separator a {background-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';
/* Menu icons pack color */
$data .= 'header .icons-pack .elem-container .title-content{color:' . esc_attr($navColor) . ';}';

$data .= '.top-classic .navigation .menu-separator,' .
    '.top-logotop .navigation .menu-separator{ background-color:' . esc_attr($navHoverColor) . ';}';
$data .= '.top-classic:not(.header-clone) .style-wireframe .navigation .menu-separator{ background-color:' . esc_attr($navColor) . ';}';
$data .= 'header.top-block .icons-pack li .elem-container,header .top .icons-pack .icon span,header.top-block .icons-pack li .title-content .icon,' .
    'header.top-modern .icons-pack li .title-content .icon,' .
    'header .icons-pack a{ font-size:' . esc_attr(pixflow_get_theme_mod('nav_icon_size', PIXFLOW_NAV_ICON_SIZE)) . 'px;}';

$navigation_icon_font_size = esc_attr(pixflow_get_theme_mod('nav_icon_size', PIXFLOW_NAV_ICON_SIZE)) + 3;
$data .= '.gather-btn .gather-menu-icon,header .icons-pack a.shopcart .icon-shopcart2,header .icons-pack a.shopcart .icon-shopping-cart{font-size:' . $navigation_icon_font_size . 'px;}';
$data .= 'header .icons-pack .shopcart-item .number{' .
    'color:' . esc_attr($navColor) . ';' .
    'background-color:' . esc_attr($navHoverColor) . ';}';

if (pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE) != 1) {
    $data .= '.layout-container .business{display:none;}';
}
/*================= Header Top - Classic ================ */
if (pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME) == 'classic' && pixflow_get_theme_mod('classic_style', PIXFLOW_CLASSIC_STYLE) == 'border') {
    $data .= 'header.top-classic .style-border nav  > ul > li,
    header.top-classic .style-border nav > ul > li:last-child {
        border-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';' .
        'border-right: 1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';

    $data .= ' header.top-classic .style-border nav > ul > li:first-child {
        border-left: 1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';
}
if ($header == 'top' && pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME) == 'classic') {
    $data .= 'header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title  ,
    header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title:after{
        color:' . esc_attr($navHoverColor) . ';}
    .top-classic .style-wireframe .navigation  > ul > li:hover .menu-separator{
        background-color:' . esc_attr($navHoverColor) . ';}
    header.top-classic .icons-pack .icon:hover {
        color:' . esc_attr($navHoverColor) . ';}';
}
/*================= Header Top - Block ================ */
if ($header == 'top' && pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME) == 'block') {

    $data .= 'header.top-block .style-style2 nav > ul > li:hover > a,
        header.top-block .style-style2 .icons-pack li:hover a{
            color:' . esc_attr(pixflow_get_theme_mod('nav_color', PIXFLOW_NAV_COLOR)) . ';}';

    $data .= ' header.top-block .style-style2 nav > ul > li:hover,
        header.top-block .style-style2 .icons-pack  li:hover ,
        header.top-block .style-style2 nav > ul > li a .hover-effect:after,
        header.top-block .style-style2 .icons-pack li .elem-container .hover-content:after{
            background-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .15)) . ';}';

    $data .= 'header.top-block .style-style2 nav > ul > li,
        header.top-block .style-style2 nav > ul > li > a,
        header.top-block .style-style2 .icons-pack li,
        header.top-block .style-style2 .icons-pack li a{
            line-height : 74px;
            height : 74px;
        }';

    $data .= 'header.top-block .style-style2 .menu-separator-block{background-color:' . esc_attr($navHoverColor) . ';}';

    if (pixflow_get_theme_mod('block_style', PIXFLOW_BLOCK_STYLE) == 'style1') {
        $headerBg = pixflow_get_theme_mod('header_bg_solid_color', PIXFLOW_HEADER_BG_SOLID_COLOR);

        $data .= 'header.top-block  .color-overlay{
        background:' . esc_attr(pixflow_colorConvertor($headerBg, 'rgb')) . ';}';

        $data .= 'header.top-block nav > ul > li > a .menu-title,
        header.top-block .style-style1 .icons-pack li a .title-content{
        color:' . esc_attr($navColor) . ';}';

        $data .= 'header.top-block .style-style1 nav > ul > li > a .hover-effect,
        header.top-block .style-style1 nav > ul > li > a .menu-title,
        header.top-block .style-style1 .icons-pack .title-content{
        background:' . esc_attr(pixflow_colorConvertor($headerBg, 'rgb')) . ';}';

        $data .= 'header.top-block .style-style1 nav  > ul > li:hover > a .menu-title,
        header.top-block .style-style1 .icons-pack li:hover a .title-content{
        background-color:' . esc_attr($navHoverColor) . ';}' .
            'color:' . esc_attr($navHoverColor) . ';}';

        $data .= 'header.top-block .style-style1 nav > ul > li > a .hover-effect,
        header.top-block .style-style1 ul.icons-pack  li .elem-container .hover-content{
        background-color:' . esc_attr($navHoverColor) . ';}';
    }

    /* block menu border color */
    $data .= 'header.top-block .style-style2 nav > ul > li,
header.top-block .style-style1 nav  > ul > li:last-child,
header.top-block .style-style2 .icons-pack li,
header.top-block .style-style1 .icons-pack li{
    border-right-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .3)) . ';}';

    $data .= 'header.top-block .style-style2 nav > ul > li:first-child,
header.top-block .style-style1 .icons-pack li:first-child,
header.top-block .style-style1 nav > ul > li,
header.top-block .style-style2 .icons-pack li:first-child{
    border-left: 1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .3)) . ';' .
        'position: relative;}';

}

/*================= Header Top - Gather ================ */

if ($header == 'top' && pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME) == 'gather') {

    $data .= 'header.top-gather .style-style2 .icons-pack li{
        border-right:1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';

    $data .= 'header.top-gather .style-style2 .icons-pack li:first-child{
        border-left:1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';

    $data .= 'header.top-gather .style-style2 .border-right,
    header.top-gather .style-style2 .border-left{
        border-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .5)) . ';}';

    $data .= '.gather-overlay{
        background-color :' . esc_attr(pixflow_get_theme_mod('overlay_bg', PIXFLOW_OVERLAY_BG)) . ';}';

    $data .= '.gather-overlay .menu nav > ul > li{
        border-color:' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('popup_menu_color', PIXFLOW_POPUP_MENU_COLOR), 'rgba', .3)) . ';}';

    $data .= '.gather-overlay nav > ul > li:after,
    .gather-overlay nav > ul > li a,
    .gather-overlay .menu a,
    .gather-overlay .gather-btn > span{
        color:' . esc_attr(pixflow_get_theme_mod('popup_menu_color', PIXFLOW_POPUP_MENU_COLOR)) . ';}';

    $data .= '.gather-overlay .menu nav > ul > li:hover > a,
    .gather-overlay .gather-btn > span:hover{
        color:' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('popup_menu_color', PIXFLOW_POPUP_MENU_COLOR), 'rgba', .7)) . ';}';

    $data .= '.top-gather .icons-pack li.icon:hover .title-content,
    .top-gather .gather-btn > span:hover{
        color:' . esc_attr($navHoverColor) . ';}';

    $data .= '.top-gather .icons-pack .icon .hover{
        color:' . esc_attr($navHoverColor) . ';}';
}
/*================= Header Top - LogoTop ================ */
if ($header == 'top' && pixflow_get_theme_mod('header_theme', PIXFLOW_HEADER_THEME) == 'logotop') {

    $businessHeight = (pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE) == '1') ? 36 : 0;

    $data .= 'header.top-logotop .logo-top-container {
        margin-top:' . (pixflow_get_theme_mod('logotop_logoSpace', PIXFLOW_LOGOTOP_LOGOSPACE)) . 'px' . ';}';

    $data .= 'header.top-logotop .icons-pack li a{
        color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .7)) . ';}';

    $data .= 'header.top-logotop .icons-pack .icon:hover {
        color:' . esc_attr($navHoverColor) . ';}';

    $data .= 'header.top-logotop nav > ul > li > a:hover{
        color:' . esc_attr($navHoverColor) . ';}';

    $data .= 'header.top-logotop nav > ul > li > a:after {
        background-color:' . esc_attr($navHoverColor) . ';}';

    $data .= 'header.top-logotop nav > ul > li .active{
        color:' . esc_attr($navHoverColor) . ';}';

    if (!pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE)) {
        $data .= '.layout-container .business.content{margin:auto;}';
    }
}
/*================= Header Top - Modern ================ */
$data .= 'header.top-modern .btn-1b:after {
        background:' . esc_attr($navColor) . ';}';

$data .= 'header.top-modern .btn-1b:active{
        background:' . esc_attr($navColor) . ';}';

$data .= 'header.top-modern nav > ul> li,
        header.top-modern .icons-pack li,
        header.top-modern .first-part{
            border-right: 1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .3)) . ';}';

$data .= 'header.top-modern .business{
        border-bottom: 1px solid ' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .3)) . ';}';

$data .= 'header.top-modern .business,
    header.top-modern .business a{
        color:' . esc_attr($navColor) . ';}';


/*================= Header Side - Classic ================ */
if (($header == 'left' || $header == 'right') && pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'classic') {
    $data .= 'header.side-classic nav > ul > li > a span.menu-separator{
        border-color:' . esc_attr($navHoverColor) . ';}';

    $data .= 'header.side-classic .icons-holder{
        border-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .3)) . ';}';

    $data .= 'header.side-classic div.footer ul li.info .footer-content span{
        color:' . esc_attr($navColor) . ';' .
        'font-family:' . $header_font_family . ';' .
        'font-weight:' . esc_attr(pixflow_get_theme_mod('nav_weight', PIXFLOW_NAV_WEIGHT)) . ';' .
        'font-style:' . esc_attr((pixflow_get_theme_mod('nav_style', PIXFLOW_NAV_STYLE) == true) ? 'italic' : 'normal') . ';' .
        'font-size: 13px' .
        'letter-spacing:' . esc_attr(pixflow_get_theme_mod('nav_letterSpace', PIXFLOW_NAV_LETTERSPACE)) . 'px;}';

}
/* Header Side Background Image */
if ($headerPosition == 'side' && $headerSideTheme != 'modern') {
    $data .= 'header.side-classic > .bg-image {';
    if (pixflow_get_theme_mod('header_side_image_image', '') != '') {
        $data .= 'background-image: url(' . esc_url(pixflow_get_theme_mod('header_side_image_image')) . ');';
    }
    $data .= 'background-repeat:' . esc_attr(pixflow_get_theme_mod('header_side_image_repeat', PIXFLOW_HEADER_SIDE_IMAGE_REPEAT)) . ';' .
        'background-size:' . esc_attr(pixflow_get_theme_mod('header_side_image_size', PIXFLOW_HEADER_SIDE_IMAGE_SIZE)) . ';' .
        'background-position:' . esc_attr(str_replace('-', ' ', pixflow_get_theme_mod('header_side_image_position', PIXFLOW_HEADER_SIDE_IMAGE_POSITION))) . ';';
    $data .= '}';
}

/* Side menu color */
$data .= 'header.side-classic nav > ul > li:hover > a,
header.side-classic.standard-mode .icons-holder ul.icons-pack li:hover a,
header.side-classic.standard-mode .footer-socials li:hover a,
header.side-classic nav > ul > li.has-dropdown:not(.megamenu):hover > a,
header.side-classic nav > ul > li:hover > a > .menu-title span,
header.side-classic .footer-socials li a .hover,
header.side-classic .icons-pack li a .hover,
header.side-modern .icons-pack li a span.hover,
header.side-modern .nav-modern-button span.hover,
header.side-modern .footer-socials span.hover,
header.side-classic nav > ul > li.has-dropdown:not(.megamenu) .dropdown a:hover .menu-title span,
header.side-classic nav > ul > li > ul li.has-dropdown:not(.megamenu):hover > a .menu-title span{
    color:' . esc_attr($navHoverColor) . ';' .
    'border-color:' . esc_attr($navHoverColor) . ';}';

$data .= 'header.side-classic div.footer ul li.info .footer-content span,
header.side-classic .icons-pack li.search .search-form input{
    color:' . esc_attr($navColor) . ';}';

$data .= 'header.side-classic div.footer ul,
header.side-classic div.footer ul li,
header.side-classic .icons-holder{
    border-color:' . esc_attr($navColor) . ';}';

$data .= 'header.side-classic .icons-holder li hr{
    background-color:' . esc_attr($navColor) . ';}';

$data .= 'header .side .footer .copyright p{
    color:' . esc_attr($navColor) . ';}';
/*================= Header Side - Modrn ================ */
if (($header == 'left' || $header == 'right') && pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'modern') {

    $data .= 'header.side-modern .side .logo,
    header.side-modern .side .footer,
    header.side-modern .nav-modern-button,
    header.side-modern .footer .info .footer-content ul,
    header.side-modern .icons-pack li{
    border-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', .15)) . ';}';

    $data .= 'header.side-modern .nav-modern-button span,
    header.side-modern .footer .info .footer-content ul a,
    header.side-modern .footer .info> a,
    header.side-modern .footer .copyright p,
    header.side-modern .search-form input[type="text"]{
    color:' . esc_attr($navColor) . ';}';

    if (($header == "left")) {
        $data .= '.li-level2{transform-origin: left top!important;}';
    } elseif (($header == "right")) {
        $data .= '.li-level2{transform-origin: right top!important;}';
    }

}

/* Header Overlay*/
$data .= 'header .color-overlay,
header.side-modern .footer .info .footer-content .copyright,
header.side-modern .footer .info .footer-content .footer-socials,
header.side-modern .search-form input[type="text"]{';
if (pixflow_get_theme_mod('header_bg_color_type', PIXFLOW_HEADER_BG_COLOR_TYPE) == 'gradient') {
    $color1 = pixflow_get_theme_mod('header_bg_gradient_color1', PIXFLOW_HEADER_BG_GRADIENT_COLOR1);
    $color2 = pixflow_get_theme_mod('header_bg_gradient_color2', PIXFLOW_HEADER_BG_GRADIENT_COLOR2);
    $orientation = pixflow_get_theme_mod('header_bg_gradient_orientation', PIXFLOW_HEADER_BG_GRADIENT_ORIENTATION);
    $colorSecond1 = pixflow_get_theme_mod('header_bg_gradient_second_color1', PIXFLOW_HEADER_BG_GRADIENT_SECOND_COLOR1);
    $colorSecond2 = pixflow_get_theme_mod('header_bg_gradient_second_color2', PIXFLOW_HEADER_BG_GRADIENT_SECOND_COLOR2);
    $orientation = pixflow_get_theme_mod('header_bg_gradient_orientation', PIXFLOW_HEADER_BG_GRADIENT_ORIENTATION);
    $data .= ' background:' . esc_attr($color1) . ';'; /* Old browsers */
    if ($orientation == 'horizontal') {
        $data .= 'background: -moz-linear-gradient(left,' . esc_attr($color1) . '0%,' . esc_attr($color2) . '33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . '100%);' . /* FF3.6+ */
            'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(33%,' . esc_attr($color2) . '),color-stop(66%,' . esc_attr($colorSecond1) . '),color-stop(100%,' . esc_attr($colorSecond2) . '));' . /* Chrome,Safari4+ */
            'background: -webkit-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . ' 100%);' . /* Chrome10+,Safari5.1+ */
            ' background: -o-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . '100%);' . /* Opera 11.10+ */
            'background: -ms-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . ' 100%);' . /* IE10+ */
            'background: linear-gradient(to right,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . '66%,' . esc_attr($colorSecond2) . '100%);' . /* W3C */
            'background-size:400% 100%;';
    } else {
        $data .= 'background: -moz-linear-gradient(top,' . esc_attr($color1) . '0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . ' 100%);' . /* FF3.6+ */
            'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(33%,' . esc_attr($color2) . '),color-stop(66%,' . esc_attr($colorSecond1) . '),color-stop(100%,' . esc_attr($colorSecond2) . '));' . /* Chrome,Safari4+ */
            'background: -webkit-linear-gradient(top,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '33%,' . esc_attr($colorSecond1) . '66%,' . esc_attr($colorSecond2) . ' 100%);' . /* Chrome10+,Safari5.1+ */
            'background: -o-linear-gradient(top,' . esc_attr($color1) . '0%,' . esc_attr($color2) . '33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . '100%);' . /* Opera 11.10+ */
            'background: -ms-linear-gradient(top, ' . esc_attr($color1) . '0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . ' 100%);' . /* IE10+ */
            'background: linear-gradient(to bottom,' . esc_attr($color1) . '0%,' . esc_attr($color2) . ' 33%,' . esc_attr($colorSecond1) . ' 66%,' . esc_attr($colorSecond2) . '100%);' . /* W3C */
            'background-size:100% 400%;';
    }
    $data .= 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="' . esc_attr($color1) . '", endColorstr="' . esc_attr($color2) . '", GradientType=0); ' ./* IE6-8 */
        'transition: background-position 500ms;';
} elseif (pixflow_get_theme_mod('header_bg_color_type', PIXFLOW_HEADER_BG_COLOR_TYPE) == 'solid') {
    $data .= 'background-color: ' . esc_attr(pixflow_get_theme_mod('header_bg_solid_color', PIXFLOW_HEADER_BG_SOLID_COLOR)) . ';';
}
$data .= '}';

$data .= 'header:not(.header-clone) > .color-overlay{';
$header_border_enable = pixflow_get_theme_mod('header_border_enable');
if ((pixflow_get_theme_mod('header_border_enable', PIXFLOW_HEADER_BORDER_ENABLE) == 1 && $header == 'top') || ($header == 'top' && pixflow_get_theme_mod('classic_style', PIXFLOW_CLASSIC_STYLE) == 'wireframe')) {
    $data .= 'border-bottom: 1px solid;
        border-bottom-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', 0.3)) . ';';
}
if (($header_border_enable == 1 || $header_border_enable === null) && $header == 'left') {
    $data .= 'border-right: 1px solid;
        border-right-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', 0.3)) . ';';
}

if (($header_border_enable == 1 || $header_border_enable === null) && $header == 'right') {
    $data .= 'border-left: 1px solid;
        border-left-color:' . esc_attr(pixflow_colorConvertor($navColor, 'rgba', 0.3)) . ';';
}
$data .= '}';

$data .= '.second-header-bg
{';
$navColorSecond = pixflow_get_theme_mod('nav_color_second', PIXFLOW_NAV_COLOR_SECOND);
if ((pixflow_get_theme_mod('header_border_enable', PIXFLOW_HEADER_BORDER_ENABLE) == 1 && $header == 'top') || ($header == 'top' && pixflow_get_theme_mod('classic_style', PIXFLOW_CLASSIC_STYLE) == 'wireframe')) {
    $data .= 'border-bottom: 1px solid;
    border-bottom-color:' . esc_attr(pixflow_colorConvertor($navColorSecond, 'rgba', 0.3)) . ';';
}
if (($header_border_enable == 1 || $header_border_enable === null) && $header == 'left') {
    $data .= 'border-right: 1px solid;
    border-right-color:' . esc_attr(pixflow_colorConvertor($navColorSecond, 'rgba', 0.3)) . ';';
}

if (($header_border_enable == 1 || $header_border_enable === null) && $header == 'right') {
    $data .= 'border-left: 1px solid;
    border-left-color:' . esc_attr(pixflow_colorConvertor($navColorSecond, 'rgba', 0.3)) . ';';
}

$data .= '}';

/*================= DropDown Styles ================ */
$data .= 'header nav.navigation li.megamenu > .dropdown,
header nav.navigation li.has-dropdown > .dropdown{
    display : table;
    position: absolute;
    top:' . esc_attr(pixflow_get_theme_mod('header-top-height', PIXFLOW_HEADER_TOP_HEIGHT)) . 'px;}';

$data .= 'header nav.navigation li.megamenu > .dropdown > .megamenu-dropdown-overlay,
.gather-overlay  nav li.megamenu > .dropdown > .megamenu-dropdown-overlay,
header nav > ul > li.has-dropdown:not(.megamenu)  ul .megamenu-dropdown-overlay{
    background-color:' . esc_attr(pixflow_get_theme_mod('dropdown_bg_solid_color', PIXFLOW_DROPDOWN_BG_SOLID_COLOR)) . ';}';

$data .= 'header nav.navigation > ul > li.megamenu > ul > li > a{
    color:' . esc_attr(pixflow_get_theme_mod('dropdown_heading_solid_color', PIXFLOW_DROPDOWN_HEADING_SOLID_COLOR)) . ';}';

$data .= 'header[class *= "top-"]:not(.right) nav.navigation li.megamenu > ul.dropdown:not(.side-line),
header[class *= "top-"]:not(.right) nav.navigation > ul > li.has-dropdown > ul.dropdown:not(.side-line){' .
    'border-top:3px solid ' . pixflow_get_theme_mod('dropdown_fg_hover_color', PIXFLOW_DROPDOWN_FG_HOVER_COLOR) . ';}';

$data .= 'header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line,
header.top nav.navigation li.megamenu > .dropdown.side-line,
.gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line,
.gather-overlay nav.navigation li.megamenu > .dropdown.side-line{
    border-left: 3px solid ' . pixflow_get_theme_mod('dropdown_fg_hover_color', PIXFLOW_DROPDOWN_FG_HOVER_COLOR) . ';}';

$data .= 'header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after,
.gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after{
    background-color:' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('dropdown_fg_solid_color', PIXFLOW_DROPDOWN_FG_SOLID_COLOR), 'rgba', 0.3)) . ';}';

$data .= 'header[class *= "top-"]:not(.right) nav.navigation li.megamenu > .dropdown,header[class *= "top-"]:not(.right) nav.navigation li.has-dropdown > .dropdown{left: 0;}';

$navigation_font_size = esc_attr(pixflow_get_theme_mod('nav_size', PIXFLOW_NAV_SIZE)) - 1;
$data .= 'header[class *= "top-"] nav .dropdown a,
header[class *= "side-"] nav .dropdown a,
.gather-overlay nav .dropdown a{
    font-size:' . $navigation_font_size . 'px' . ';}';

$data .= '.gather-overlay nav.navigation li.megamenu > .dropdown,
.gather-overlay nav.navigation li.has-dropdown > .dropdown{
    background-color:' . esc_attr(pixflow_get_theme_mod('dropdown_bg_solid_color', PIXFLOW_DROPDOWN_BG_SOLID_COLOR)) . ';' .
    'display : table;
    left: 0;
    position: absolute;
    top: 150%;
}';

$data .= 'header.left nav.navigation > ul > li.has-dropdown > .dropdown .megamenu-dropdown-overlay,
header.side-modern .side.style-style2 nav  > ul > li .megamenu-dropdown-overlay,
header.side-modern .side.style-style1 nav > ul .megamenu-dropdown-overlay,
header.side-modern .style-style1.side nav  ul  li{
    background-color:' . esc_attr(pixflow_get_theme_mod('dropdown_bg_solid_color', PIXFLOW_DROPDOWN_BG_SOLID_COLOR)) . ';}';

$data .= 'header.side-modern .style-style1.side nav  ul  li,
header.side-modern .style-style1.side nav.navigation > ul > li.has-dropdown .dropdown{
    border-color:' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('dropdown_fg_solid_color', PIXFLOW_DROPDOWN_FG_SOLID_COLOR), 'rgba', 0.3)) . ';' .
    'color:' . esc_attr(pixflow_get_theme_mod('dropdown_fg_solid_color', PIXFLOW_DROPDOWN_FG_SOLID_COLOR)) . ';}';

$data .= 'header nav.navigation .dropdown a,
header.side-modern nav.navigation a,
.gather-overlay nav.navigation .dropdown a{
    color:' . esc_attr(pixflow_get_theme_mod('dropdown_fg_solid_color', PIXFLOW_DROPDOWN_FG_SOLID_COLOR)) . ';' .
    'position: relative !important;
    width: auto !important;}';

/* dropDown Hover */

$data .= 'header .top nav > ul > li > ul li:hover > a .menu-title span,
header .top nav > ul > li .dropdown a:hover .menu-title span,
.gather-overlay nav > ul > li > ul li:hover > a .menu-title span,
.gather-overlay nav > ul > li .dropdown a:hover .menu-title span,
header.side-classic nav > ul > li > ul li:hover > a .menu-title span,
header.side-classic nav > ul > li .dropdown a:hover .menu-title span,
header.side-modern .side.style-style2 nav.navigation ul li a:hover{
    color:' . esc_attr(pixflow_get_theme_mod('dropdown_fg_hover_color', PIXFLOW_DROPDOWN_FG_HOVER_COLOR)) . ';' .
    'border-color:' . esc_attr(pixflow_get_theme_mod('dropdown_fg_hover_color', PIXFLOW_DROPDOWN_FG_HOVER_COLOR)) . ';}';

$data .= 'header.side-modern .side.style-style1 nav.navigation ul li:hover{
    background-color:' . esc_attr(pixflow_get_theme_mod('dropdown_fg_hover_color', PIXFLOW_DROPDOWN_FG_HOVER_COLOR)) . ';}';
/*====================================================
                Body
======================================================*/

function pixflow_backgroundStyles($prefix, $parent)
{
    if (!isset($prefix, $parent)) {
        return false;
    }
    $data ='';
    // this if always is true (because background controller set to postMessage.)
    //TODO: check location of view(customizer or output)
    if (pixflow_get_theme_mod($prefix . '_bg', constant(strtoupper('PIXFLOW_' . $prefix . '_BG'))) != true) {
        $data .= esc_attr($parent) . '> .color-overlay,' .
            esc_attr($parent) . '> .texture-overlay,' . esc_attr($parent) . ' > .bg-image {  display:none;  }';
    }
    if (pixflow_get_theme_mod($prefix . '_bg', constant(strtoupper('PIXFLOW_' . $prefix . '_BG'))) == '1' || true) {
        /*** Body Overlay ***/

        $bg_type = pixflow_get_theme_mod($prefix . '_bg_type', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_TYPE')));
        if ($bg_type != 'color') {
            $data .= esc_attr($parent) . '> .color-overlay.color-type { display:none; }';
        }
        if ($bg_type != 'image') {
            $data .= esc_attr($parent) . ' > .color-overlay.image-type,' .
                esc_attr($parent) . '> .bg-image { display:none; }';
        }

        if ($bg_type != 'texture') {
            $data .= esc_attr($parent) . ' > .color-overlay.texture-type,' .
                esc_attr($parent) . '> .texture-overlay{ display:none; }';
        }
        if ($prefix != 'footer') {
            $data .= esc_attr($parent) . '> .color-overlay.color-type {';
            if (pixflow_get_theme_mod($prefix . '_bg_color_type', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_COLOR_TYPE'))) == 'gradient') {
                $color1 = pixflow_get_theme_mod($prefix . '_bg_gradient_color1', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_GRADIENT_COLOR1')));
                $color2 = pixflow_get_theme_mod($prefix . '_bg_gradient_color2', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_GRADIENT_COLOR2')));
                $orientation = esc_attr(pixflow_get_theme_mod($prefix . '_bg_gradient_orientation', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_GRADIENT_ORIENTATION'))));
                $data .= 'background:' . esc_attr($color1) . ';';
                if ($orientation == 'horizontal') {
                    $data .= 'background: -moz-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
                        'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
                        'background: -webkit-linear-gradient(left, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -o-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -ms-linear-gradient(left, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: linear-gradient(to right, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);';
                } else {
                    $data .= 'background: -moz-linear-gradient(top,' . esc_attr($color1) . ' 0%, ' . esc_attr($color2) . ' 100%);' .
                        'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
                        'background: -webkit-linear-gradient(top,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -o-linear-gradient(top,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -ms-linear-gradient(top,  ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
                        'background: linear-gradient(to bottom, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);';
                }
                $data .= 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="' . esc_attr($color1) . '", endColorstr="' . esc_attr($color2) . '", GradientType=0);';
            } elseif (pixflow_get_theme_mod($prefix . '_bg_color_type', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_COLOR_TYPE'))) == 'solid') {
                $data .= 'background-color:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_solid_color', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_SOLID_COLOR')))) . ';';
            }
            $data .= '}';
        }
        /* Body Background Image */
        if (pixflow_get_theme_mod($prefix . '_bg_image_image') != '') {
            $data .= esc_attr($parent) . '> .bg-image {
            background-image: url(' . esc_url(pixflow_get_theme_mod($prefix . '_bg_image_image')) . ');}';
        }
        $data .= esc_attr($parent) . '> .bg-image {
            background-repeat:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_repeat', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_REPEAT')))) . ';';
        if (esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_attach', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_ATTACH')))) == 'fixed' && $parent == '.layout-container') {
            $data .= 'background-attachment:fixed;';
        } else {
            $data .= 'background-attachment:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_attach', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_ATTACH')))) . ';';
        }
        $data .= 'background-position:' . esc_attr(str_replace('-', ' ', pixflow_get_theme_mod($prefix . '_bg_image_position', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_POSITION'))))) . ';' .
            'background-size: ' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_size', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_SIZE')))) . ';' .
            'opacity:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_opacity', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_OPACITY')))) . ';}';
        if (pixflow_get_theme_mod($prefix . '_bg_image_overlay', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_OVERLAY'))) != '') {
            $data .= esc_attr($parent) . ' > .color-overlay.image-type {';
            if (pixflow_get_theme_mod($prefix . '_bg_image_overlay_type', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_OVERLAY_TYPE'))) == 'gradient') {
                $color1 = pixflow_get_theme_mod($prefix . '_bg_overlay_gradient_color1', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_OVERLAY_GRADIENT_COLOR1')));
                $color2 = pixflow_get_theme_mod($prefix . '_bg_overlay_gradient_color2', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_OVERLAY_GRADIENT_COLOR2')));
                $orientation = esc_attr(pixflow_get_theme_mod($prefix . '_bg_overlay_gradient_orientation', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_OVERLAY_GRADIENT_ORIENTATION'))));
                $data .= ' background:' . esc_attr($color1) . ';';
                if ($orientation == 'horizontal') {
                    $data .= 'background: -moz-linear-gradient(left,  ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
                        'background: -webkit-linear-gradient(left,  ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -o-linear-gradient(left, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -ms-linear-gradient(left, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
                        'background: linear-gradient(to right, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);';
                } else {
                    $data .= 'background: -moz-linear-gradient(top,  ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        ' background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
                        'background: -webkit-linear-gradient(top,  ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        ' background: -o-linear-gradient(top, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: -ms-linear-gradient(top, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
                        'background: linear-gradient(to bottom, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);';
                }
                $data .= 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="' . esc_attr($color1) . '", endColorstr="' . esc_attr($color2) . '", GradientType=0);';
            } elseif (pixflow_get_theme_mod($prefix . '_bg_image_overlay_type', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_OVERLAY_TYPE'))) == 'solid') {
                $data .= 'background-color: ' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_image_solid_overlay', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_IMAGE_SOLID_OVERLAY')))) . ';';
            }
            $data .= '}';
        }//end if has image
        /* Body Texture Overlay */
        $data .= esc_attr($parent) . '> .texture-overlay {
        opacity:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_texture_opacity', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_TEXTURE_OPACITY')))) . ';' .
            'background-image: url(' . esc_url(pixflow_get_theme_mod($prefix . '_bg_texture', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_TEXTURE')))) . ');}';

        if (pixflow_get_theme_mod($prefix . '_bg_texture_overlay', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_TEXTURE_OVERLAY'))) != '') {
            $data .= esc_attr($parent) . '> .color-overlay.texture-type {
            background-color:' . esc_attr(pixflow_get_theme_mod($prefix . '_bg_texture_solid_overlay', constant(strtoupper('PIXFLOW_' . $prefix . '_BG_TEXTURE_SOLID_OVERLAY')))) . ';}';
        }// end if texture overlay

    }//if site_bg
    return $data;
}

$data .= pixflow_backgroundStyles('site', '.layout-container');
$data .= pixflow_backgroundStyles('footer', 'footer');


/*====================================================
                    Main
======================================================*/

/* Main Overlay*/

$main_bg = pixflow_get_theme_mod('main_bg', PIXFLOW_MAIN_BG);

if ($main_bg == false) {
    $data .= 'main .content .color-overlay.color-type { display:none }';
}
/*** SITE Content Overlay ***/
$data .= 'main .content .color-overlay.color-type {';
if (pixflow_get_theme_mod('main_bg_color_type', PIXFLOW_MAIN_BG_COLOR_TYPE) == 'gradient') {
    $color1 = pixflow_get_theme_mod('main_bg_gradient_color1', PIXFLOW_MAIN_BG_GRADIENT_COLOR1);
    $color2 = pixflow_get_theme_mod('main_bg_gradient_color2', PIXFLOW_MAIN_BG_GRADIENT_COLOR2);
    $orientation = esc_attr(pixflow_get_theme_mod('main_bg_gradient_orientation', PIXFLOW_MAIN_BG_GRADIENT_ORIENTATION));
    $data .= ' background:' . esc_attr($color1) . ';';
    if ($orientation == 'horizontal') {
        $data .= 'background: -moz-linear-gradient(left, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
            'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
            'background: -webkit-linear-gradient(left,' . esc_attr($color1) . '0%,' . esc_attr($color2) . '100%);' .
            'background: -o-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
            'background: -ms-linear-gradient(left,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . ' 100%);' .
            'background: linear-gradient(to right,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);';
    } else {
        $data .= 'background: -moz-linear-gradient(top,' . esc_attr($color1) . '0%,' . esc_attr($color2) . '100%);' .
            'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . esc_attr($color1) . '), color-stop(100%,' . esc_attr($color2) . '));' .
            'background: -webkit-linear-gradient(top, ' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
            'background: -o-linear-gradient(top,' . esc_attr($color1) . ' 0%,' . esc_attr($color2) . '100%);' .
            'background: -ms-linear-gradient(top, ' . esc_attr($color1) . '0%,' . esc_attr($color2) . '100%);' .
            'background: linear-gradient(to bottom, ' . esc_attr($color1) . '0%,' . esc_attr($color2) . '100%);';
    }
    $data .= 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="' . esc_attr($color1) . '", endColorstr="' . esc_attr($color2) . '", GradientType=0);';
} elseif (pixflow_get_theme_mod('main_bg_color_type', PIXFLOW_MAIN_BG_COLOR_TYPE) == 'solid') {
    $data .= ' background-color: ' . esc_attr(pixflow_get_theme_mod('main_bg_solid_color', PIXFLOW_MAIN_BG_SOLID_COLOR)) . ';';
}
$data .= '}';
$data .= 'main .content {
    padding:' . esc_attr(pixflow_get_theme_mod('mainC-padding', PIXFLOW_MAINC_PADDING)) . 'px;' .
    '}';

if ($sidebarSwitch != 'on' && $sidebarSwitch != '1') {
    $data .= 'main #content {
        margin-left: auto;
        margin-right: auto;
    }';
}

/*Run in header side classic */
if ($header == 'left' && pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'standard') {
    $headersideW = pixflow_get_theme_mod('header-side-width', PIXFLOW_HEADER_SIDE_WIDTH);
    $wrapContentW = (100 - $headersideW) . '%';

    $data .= '.layout > .wrap{' .
        'margin-left:' . esc_attr($headersideW) . '%' . ';' .
        '}';

}

if ($header != 'top') {

    //Calculating the margin
    if (pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'modern') {
        $sideMargin = '65px';

        //Assigning the margin
        if ($header == 'left') {
            $data .= ' .layout > .wrap{
                margin-left:' . esc_attr($sideMargin) . ';' .
                '}';
        } else if ($header == 'right') {
            $data .= '.layout > .wrap{
                margin-right:' . esc_attr($sideMargin) . ';' .
                '}';
        }

    } else if (pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'classic') {

        if ($header == 'left') {
            $headersideW = pixflow_get_theme_mod('header-side-width', PIXFLOW_HEADER_SIDE_WIDTH);
            $sidebarW = $sidebarWidth;
            $wrapContentW = (100 - $headersideW) . '%';


            $data .= '.layout > .wrap{
                margin-left:' . esc_attr($headersideW) . '%' . ';' .
                '}';


        } else if ($header == 'right') {
            $headersideW = pixflow_get_theme_mod('header-side-width', PIXFLOW_HEADER_SIDE_WIDTH);
            $sidebarW = $sidebarWidth;
            $wrapContentW = (100 - $headersideW) . '%';


            $data .= ' .layout > .wrap{
                margin-right:' . esc_attr($headersideW) . '%' . ';' .
                '}';

        }
    }
}


/* Run in header side modern */

if ($header == 'left' && pixflow_get_theme_mod('header_side_theme', PIXFLOW_HEADER_SIDE_THEME) == 'modern') {
    $headersideW = 65;
    $wrapContentW = (100 - $headersideW) . '%';


    $data .= '.layout > .wrap{
        width: 100%;
        padding-left: 65px;
    }';

}

/*====================================================
                        Footer
======================================================*/

$color = pixflow_get_theme_mod('copyright_color', PIXFLOW_COPYRIGHT_COLOR);

$footerStyle = 'width: ' . pixflow_get_theme_mod('footer-width',PIXFLOW_FOOTER_WIDTH) . '% ; margin-top:' . pixflow_get_theme_mod('footer-marginT',PIXFLOW_FOOTER_MARGINT) . 'px; ';
$data .= 'footer {'.$footerStyle;
$footer_parallax = pixflow_get_theme_mod('footer_parallax',PIXFLOW_FOOTER_PARALLAX);
    if($footer_parallax == 'on' || $footer_parallax == '1'):
        $data .=  "visibility: hidden; display: block;";
    endif;
$data .= '}';

$data .= 'footer .content{width:'.pixflow_get_theme_mod('footerC-width',PIXFLOW_FOOTERC_WIDTH).'%;}';

$data .= '#footer-bottom .social-icons span a,' .
    '#footer-bottom .go-to-top a,' .
    '#footer-bottom p{' .
    'color:' . esc_attr($color) .
    '}';

$data .= 'footer.footer-default .footer-widgets {' .
    'background-color:' . esc_attr(pixflow_get_theme_mod('footer_widget_area_bg_color_rgba', PIXFLOW_FOOTER_WIDGET_AREA_BG_COLOR_RGBA)) . ';' .
    'overflow: hidden' . ';' .
    '}';

$data .= 'footer .widget-area {' .
    'height:' . esc_attr(pixflow_get_theme_mod('footer_widget_area_height', PIXFLOW_FOOTER_WIDGET_AREA_HEIGHT)) . 'px;' .
    '}';

$data .= 'footer hr.footer-separator{' .
    'height:' . esc_attr(pixflow_get_theme_mod('copyright_separator', PIXFLOW_COPYRIGHT_SEPARATOR)) . 'px;' .
    'background-color:' . esc_attr(pixflow_get_theme_mod('copyright_separator_bg_color', PIXFLOW_COPYRIGHT_SEPARATOR_BG_COLOR)) .
    '}';

$footer_wigdet_area_height = esc_attr(pixflow_get_theme_mod('footer_widget_area_height', PIXFLOW_FOOTER_WIDGET_AREA_HEIGHT)) - 120;
$data .= 'footer.footer-default .widget-area.classicStyle.border.boxed div[class*="col-"]{' .
    'height:' . $footer_wigdet_area_height . 'px;' .
    '}';

$data .= 'footer.footer-default .widget-area.classicStyle.border.full div[class*="col-"]{' .
    'height :' . esc_attr(pixflow_get_theme_mod('footer_widget_area_height', PIXFLOW_FOOTER_WIDGET_AREA_HEIGHT)) . 'px;' .
    'padding : 45px 30px' . ';' .
    '}';

$data .= 'footer.footer-default #footer-bottom{' .
    'background-color:' . esc_attr(pixflow_get_theme_mod('footer_bottom_area_bg_color_rgba', PIXFLOW_FOOTER_BOTTOM_AREA_BG_COLOR_RGBA)) . ';' .
    '}';
$data .= '#footer-bottom{' .
    'height:' . esc_attr(pixflow_get_theme_mod('footer_bottom_area_height', PIXFLOW_FOOTER_BOTTOM_AREA_HEIGHT)) . 'px;' .
    '}';

/*Footer Switcher*/

if (pixflow_get_theme_mod('footer_social', PIXFLOW_FOOTER_SOCIAL) == 'on' || pixflow_get_theme_mod('footer_switcher', PIXFLOW_FOOTER_SWITCHER) == '1') {
    $data .= '#footer-bottom .social-icons > span:not(.go-to-top){display:inline-flex;}';
} else {
    $data .= '#footer-bottom .social-icons > span:not(.go-to-top){display:none;}';
}

if (pixflow_get_theme_mod('footer_copyright', PIXFLOW_FOOTER_COPYRIGHT) == 'on' || pixflow_get_theme_mod('footer_copyright', PIXFLOW_FOOTER_SWITCHER) == '1') {
    $data .= '#footer-bottom .copyright{display:block;}';
} else {
    $data .= '#footer-bottom .copyright{display:none;}';
}

$data .= '#footer-bottom .logo{opacity:' . esc_attr(pixflow_get_theme_mod('footer_logo_opacity', PIXFLOW_FOOTER_LOGO_OPACITY)) . ';' . '}';

if (pixflow_get_theme_mod('footer_logo', PIXFLOW_FOOTER_LOGO) == false || pixflow_get_theme_mod('footer_logo', PIXFLOW_FOOTER_LOGO) === 'false') {
    $data .= '#footer-bottom .logo{display:none;}';
}

if (pixflow_get_theme_mod('footer_switcher', PIXFLOW_FOOTER_SWITCHER) == 'on' || pixflow_get_theme_mod('footer_switcher', PIXFLOW_FOOTER_SWITCHER) == '1') {
    $data .= '#footer-bottom {display:block;}';
} else {
    $data .= '#footer-bottom {display:none;}';
}

/*====================================================
                    Sidebar
======================================================*/

/* Sidebar BACKGROUND */

$data .= pixflow_backgroundStyles($sidebarType . '_sidebar', '.sidebar.box .widget');
$data .= pixflow_backgroundStyles($sidebarType . '_sidebar', '.sidebar');
if ($sidebarStyle != 'box') {
    $data .= '.sidebar.box .widget .color-overlay,
.sidebar.box .widget .texture-overlay,
.sidebar.box .widget .bg-image{
    display:none;}';
} else {
    $data .= '.sidebar.box .widget{
box-shadow : 2px 3px 16px 4px ' . esc_attr($sidebarShadow) . ';}';
}


/*=================Widget Contact Info================ */

$data .= '.dark-sidebar .widget-contact-info-content,
.dark .widget-contact-info-content{
    background:url(' . esc_url(PIXFLOW_THEME_IMAGES_URI . '/map-dark.png')  . ')no-repeat 10px 15px;' .
    '}';
$data .= '.light-sidebar .widget-contact-info-content,
.light .widget-contact-info-content{
    background:url(' . esc_url(PIXFLOW_THEME_IMAGES_URI . '/map-light.png')  . ')no-repeat 10px 15px;' .
    '}';

/*====================================================
                    Bussiness Bar
======================================================*/

/* Business Bar */

$headerTopPosition = (pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE)) ? pixflow_get_theme_mod('header_top_position', PIXFLOW_HEADER_TOP_POSITION) : 0;

$data .= '.layout-container .business {
        background:' . esc_attr(pixflow_get_theme_mod('businessBar_bg_color', PIXFLOW_BUSINESSBAR_BG_COLOR)) . ';' .
    'top:' . esc_attr($headerTopPosition) . 'px;' .
    'height: 36px;' .
    '}';

$data .= '.layout-container .business,.layout-container  .business a {
        color:' . esc_attr(pixflow_get_theme_mod('businessBar_content_color', PIXFLOW_BUSINESSBAR_CONTENT_COLOR)) . ';' .
    '}';

$data .= 'header {
        margin-top: 0
    }';


/*====================================================
                ShortCodes
======================================================*/


/*================= Row ================ */
$data .= '.box_size{
    width:' . esc_attr(pixflow_get_theme_mod('mainC-width', PIXFLOW_MAINC_WIDTH) . '%') .
    '}' .

    '.box_size_container{
    width:' . esc_attr(pixflow_get_theme_mod('mainC-width', PIXFLOW_MAINC_WIDTH) . '%') .
    '}';

/*==================================================
                        widget
====================================================*/
$widgets_font_famity = (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';
$data .= '.widget a,
.widget p,
.widget span:not(.icon-caret-right)/*:not(.star-rating span)*/{
    font-family:' . $widgets_font_famity . ';' .
    '}';

/*=====================================================
                blog
=======================================================*/
$data .= '.loop-post-content .post-title:hover{
    color:' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('h1_color', PIXFLOW_H1_COLOR), 'rgba', 0.8)) . ';}';
/*=====================================================
                woocommerce
======================================================*/
$woocommerce_font_family = (pixflow_get_theme_mod('link_fontfamily_mode', PIXFLOW_LINK_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('link_name', PIXFLOW_LINK_NAME) : 'link_custom_font';
$data .= '.woocommerce ul.product_list_widget li span:not(.star-rating span){
    font-family:' . $woocommerce_font_family . ';' .
    '}';

/*====================================================
                    Notification Center
======================================================*/
$data .= '.notification-center .post .date .day.accent-color,
    #notification-tabs p.total,
    #notification-tabs p.total .amount,
    #notification-tabs .cart_list li .quantity,
    #notification-tabs .cart_list li .quantity  .amount{
    color :' . esc_attr(pixflow_get_theme_mod('notification_color', PIXFLOW_NOTIFICATION_COLOR)) . ';' .
    '}';

$notification_center_font_family = (pixflow_get_theme_mod('nav_fontfamily_mode', PIXFLOW_NAV_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('nav_name', PIXFLOW_NAV_NAME) : 'nav_custom_font';
$data .= '.notification-center span,
.notification-center a,
.notification-center p,
#notification-tabs #result-container .search-title,
#notification-tabs #result-container .more-result,
#notification-tabs #result-container .item .title,
#notification-tabs #search-input,
#notification-tabs .cart_list li.empty,
.notification-collapse{
    font-family :' . $notification_center_font_family . ';' .
    '}';
if (!pixflow_get_theme_mod('notification_post', PIXFLOW_NOTIFICATION_POST)) {
    $data .= '.notification-center .pager .posts,
    .notification-center #notification-tabs .pager .posts.selected{
        display :none;
    }';

    $data .= '.notification-center .tabs-container .posts-tab{
        opacity : 0 ;
    }';
}

if (!pixflow_get_theme_mod('notification_portfolio', PIXFLOW_NOTIFICATION_PORTFOLIO)) {
    $data .= '.notification-center .pager .portfolio,
    .notification-center #notification-tabs .pager .portfolio.selected{
        display :none;
    }';

    $data .= '.notification-center .tabs-container .protfolio-tab{
        opacity : 0 ;
    }';
}

if (!pixflow_get_theme_mod('notification_search', PIXFLOW_NOTIFICATION_SEARCH)) {
    $data .= '.notification-center .pager .search,
    .notification-center #notification-tabs .pager .search.selected{
        display :none;
    }';
    $data .= '.notification-center .tabs-container .search-tab{
        opacity : 0;
    }';

}
if (!pixflow_get_theme_mod('notification_cart', PIXFLOW_NOTIFICATION_CART)) {
    $data .= '.notification-center .pager .shop,
     .notification-center #notification-tabs .pager .shop.selected{
         display :none;
    }';

    $data .= '.notification-center .tabs-container .shop-tab{
        opacity : 0;
    }';
}

$oneItemChecked = 0;

if (pixflow_get_theme_mod('notification_cart', PIXFLOW_NOTIFICATION_POST)) {
    $oneItemChecked++;
}
if (pixflow_get_theme_mod('notification_post', PIXFLOW_NOTIFICATION_POST)) {
    $oneItemChecked++;
}
if (pixflow_get_theme_mod('notification_portfolio', PIXFLOW_NOTIFICATION_POST)) {
    $oneItemChecked++;
}
if (pixflow_get_theme_mod('notification_search', PIXFLOW_NOTIFICATION_POST)) {
    $oneItemChecked++;
}

if ($oneItemChecked == 1) {
    $data .= '#notification-tabs .pager {
        display : none !important;
    }';
}
$data .= '.portfolio .accent-color,
.portfolio .accent-color.more-project,
.portfolio-carousel .accent-color.like:hover,
.portfolio-carousel .buttons .sharing:hover{
    color :' . esc_attr(pixflow_get_theme_mod('portfolio_accent', PIXFLOW_PORTFOLIO_ACCENT)) .
    '}';

$data .= '.portfolio-split .accent-color.like:hover,
.portfolio-full .accent-color.like:hover{
    background-color :' . esc_attr(pixflow_get_theme_mod('portfolio_accent', PIXFLOW_PORTFOLIO_ACCENT)) . ';' .
    'border-color :' . esc_attr(pixflow_get_theme_mod('portfolio_accent', PIXFLOW_PORTFOLIO_ACCENT)) . ';' .
    'color:#fff;
}';

$data .= '.portfolio .accent-color.more-project:after{
    background-color :' . esc_attr(pixflow_get_theme_mod('portfolio_accent', PIXFLOW_PORTFOLIO_ACCENT)) .
    '}';

$data .= '.portfolio .accent-color.more-project:hover{
    color :' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('portfolio_accent', PIXFLOW_PORTFOLIO_ACCENT), 'rgba', .6)) .
    '}';

$data .= '.portfolio .category span {
    color :' . esc_attr(pixflow_colorConvertor(pixflow_get_theme_mod('h2_color', PIXFLOW_H2_COLOR), 'rgba', .7)) .
    '}';

$data .= '.portfolio .buttons .sharing,
.portfolio-carousel .buttons .like{
    border-color:' . esc_attr(pixflow_get_theme_mod('p_color', PIXFLOW_P_COLOR)) . ';' .
    'color: ' . esc_attr(pixflow_get_theme_mod('p_color', PIXFLOW_P_COLOR)) . ';
}';

$data .= '.portfolio-split .buttons .sharing:hover,
.portfolio-full .buttons .sharing:hover{
    background-color:' . esc_attr(pixflow_get_theme_mod('p_color', PIXFLOW_P_COLOR)) . ';' .
    'color: #fff;
}';

/*====================================================
Pixflow Slider
======================================================*/
$pixflow_slider_font_family = (pixflow_get_theme_mod('p_fontfamily_mode', PIXFLOW_P_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('p_name', PIXFLOW_P_NAME) : 'p_custom_font';
$data .= '.md-pixflow-slider .btn-container .shortcode-btn a.button{
   font-family:' . $pixflow_slider_font_family . ';' .
    '}';


if ('side' == $headerPosition) {
    $portfolioNavWidth = ($headerSideTheme == 'modern') ? '100' : 100 - esc_attr(pixflow_get_theme_mod('header-side-width', PIXFLOW_HEADER_SIDE_WIDTH));

    /*Portfolio detail Nav*/
    $data .= '.portfolio-nav{ width:' . esc_attr($portfolioNavWidth) . '%' . ' !important; }';
    if ('left' == $header) {
        $data .= '.portfolio-nav{right:0;left:auto;}';
    } else {
        $data .= '.portfolio-nav{left:0;right:auto;}';
    }
    if ('modern' == $headerSideTheme && 'left' == $header) {
        $data .= '.portfolio-nav a.prev{left:65px;}';
    } elseif ('modern' == $headerSideTheme && 'right' == $header) {
        $data .= '.portfolio-nav a.next{right:65px;}';
    }
}


/*====================================================
    after removing h1 from shortcodes
======================================================*/
$data .= '.md-statistic .timer-holder .timer,
.md-counter:not(.md-countbox):not(.md-counter-card)  .timer,
.img-box-fancy .image-box-fancy-title{
    font-family:' . $h1_font_family . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h1_letterSpace', PIXFLOW_H1_LETTERSPACE)) . 'px' . ';' .
    '}';

$data .= '.process-panel-main-container .sub-title{
    font-family:' . $h3_font_family . ';' .
    'font-weight:' . esc_attr(pixflow_get_theme_mod('h3_weight', PIXFLOW_H3_WEIGHT)) . ';' .
    'font-style:' . esc_attr(pixflow_get_theme_mod('h3_style', PIXFLOW_H3_STYLE)) . ';' .
    'letter-spacing:' . esc_attr(pixflow_get_theme_mod('h3_letterSpace', PIXFLOW_H3_LETTERSPACE)) . 'px' . ';' .
    '}';

$data .= '.error404 .item-setting,
body:not(.compose-mode) .item-setting{display: none;}';

$styles = preg_replace("/\s+/", " ", $data);
wp_add_inline_style('responsive-style', $styles);


