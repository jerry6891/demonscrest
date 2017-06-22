<?php
$mbuilder_model = ''  ;
$assets_path = array();
//@TODO: Refactor
function pixflow_theme_scripts()
{
	global $assets_path;
	// run out of admin pannel
	if ( is_rtl() )
	{
		//Register RTL Style
		wp_enqueue_style('rtl-style', pixflow_path_combine(PIXFLOW_THEME_URI,'style-rtl.min.css'),array(),PIXFLOW_THEME_VERSION);
	}

	//Register Main Theme Styles
	wp_enqueue_style('style', get_stylesheet_uri(), false, PIXFLOW_THEME_VERSION);

	pixflow_load_page_assets();
	pixflow_load_page_style($assets_path);


	//Register google fonts
	pixflow_theme_fonts();

	//enqueue style
	wp_enqueue_style('plugin-styles',pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'plugin.min.css'),false,PIXFLOW_THEME_VERSION);

	//TF requirement (we have our own reply script for gods sake!)
	if(PIXFLOW_USE_COMMENT_REPLY_SCRIPT && is_singular())
		wp_enqueue_script( "comment-reply" );


	if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ){
		//enqueue style
		wp_enqueue_style('woo-commerce-styles',pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'woo-commerce.min.css'),false,PIXFLOW_THEME_VERSION);

	}

	//register and enqueue html5shiv
	global $wp_scripts;
	wp_register_script(
		'html5shiv',
		pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'html5shiv.js'),
		array(),
		'3.6.2'
	);
	$wp_scripts->add_data('html5shiv', 'conditional', 'lt IE 9');

	if (! isset($_SERVER['HTTP_USER_AGENT'])) {
		$_SERVER['HTTP_USER_AGENT'] = '';
	}
	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

	if (count($matches) > 1) {
		//Then we're using IE
		$version = $matches[1];

		if ($version <= 9) {
			wp_enqueue_script('html5shiv');
		}
		if ($version <= 10) {
			wp_enqueue_style('isotope', pixflow_path_combine(PIXFLOW_THEME_CSS_URI, 'isotope.min.css'), false, PIXFLOW_THEME_VERSION);
		}
	}

	//Include jQuery UI in Customizer
	if(is_customize_preview()){
		wp_enqueue_script('jquery-ui-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'customzier-jquery-ui.min.js'), array(), PIXFLOW_THEME_VERSION, true);
	}
	global $in_mbuilder;
	if($in_mbuilder){
		wp_enqueue_script('jquery-ui-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'builder-jquery-ui.min.js'), array(), PIXFLOW_THEME_VERSION, true);
	}

	//Register Pixflow icon-font library
	wp_enqueue_style('px=iconfonts-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI, 'iconfonts.min.css'), array(), PIXFLOW_THEME_VERSION);

	// Flexslider(added for mac device slider shortcode)
	wp_enqueue_script('flexslider-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'jquery.flexslider-min.js'), array(), PIXFLOW_THEME_VERSION);
	wp_enqueue_style('flexslider-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI, 'flexslider.min.css'), array(), PIXFLOW_THEME_VERSION);

	if(defined('LS_PLUGIN_VERSION')){
		wp_dequeue_script('greensock');
	}

	wp_enqueue_script('plugin-js', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'plugins.min.js'), array(), PIXFLOW_THEME_VERSION, true);
	if (!wp_script_is('niceScroll', 'enqueued')) {
		wp_enqueue_script('niceScroll', pixflow_path_combine(PIXFLOW_THEME_LIB_URI, 'assets/script/jquery.nicescroll.min.js'), false, PIXFLOW_THEME_VERSION, true);
	}

	// Main theme Scripts
	wp_enqueue_script('main-custom-js', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'custom.min.js'), array(), PIXFLOW_THEME_VERSION, true);
	wp_localize_script( 'main-custom-js', 'ajax_var', array(
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);

	pixflow_load_page_scripts($assets_path);

	$mbuilder = MBuilder::getInstance();
	global $in_mbuilder;
	if(!$in_mbuilder){
		pixflow_load_shortcodes();
	}
	// Scripts and  of shortcodes
	$page_id = get_the_id();
	$page_js_path = PIXFLOW_THEME_CACHE . '/' . $page_id . '.js';
	$page_css_path = PIXFLOW_THEME_CACHE . '/' . $page_id . '.css';
	$last_modified = get_post_modified_time('Y-m-d-h-i-s');
	if(!file_exists($page_js_path) || !file_exists($page_css_path)){
		$mbuilder->generate_static_js_css($page_id);
	}
	if(file_exists($page_js_path)){
		wp_enqueue_script('page-script',PIXFLOW_THEME_CACHE_URI.'/'.$page_id.'.js' , array('main-custom-js') , $last_modified,true);
	}
	if(file_exists($page_css_path)){
		wp_enqueue_style('page-style',PIXFLOW_THEME_CACHE_URI.'/'.$page_id.'.css' ,false,$last_modified);
	}

	wp_enqueue_style('responsive-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI, 'responsive.min.css'), false, PIXFLOW_THEME_VERSION);
	//styles Inline
	require_once (PIXFLOW_THEME_CSS .'/styles-inline.php');


	if ( is_rtl() ){
		wp_enqueue_script('rtl-custom-js', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'rtl.min.js'), array(), PIXFLOW_THEME_VERSION, true);
	}

	// Load Flickity for Notification Center
	$notificationEnable = pixflow_get_theme_mod('notification_enable',PIXFLOW_NOTIFICATION_ENABLE);
	if($notificationEnable){
		$shortcodes_used_flickity = array('md_slider','md_blog_carousel','md_slider_carousel');
		$used_shortcodes = count($mbuilder->used_shortcodes)?$mbuilder->used_shortcodes:array();
		$result = array_intersect($used_shortcodes, $shortcodes_used_flickity) ;
		if(count($result) == 0){
			wp_enqueue_style('flickity-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI, 'flickity.min.css'), array(), PIXFLOW_THEME_VERSION);
			wp_enqueue_script('flickity-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI, 'flickity.min.js'), array(), PIXFLOW_THEME_VERSION, true);
		}
	}


	$customizeLocalizedOptions = array(

		'site_bg_image_attach' => pixflow_get_theme_mod('site_bg_image_attach', PIXFLOW_SITE_BG_IMAGE_ATTACH),
		'headerBgColorType' => pixflow_get_theme_mod('header_bg_color_type', PIXFLOW_HEADER_BG_COLOR_TYPE),
		'navColor' => pixflow_get_theme_mod('nav_color', PIXFLOW_NAV_COLOR),
		'navHoverColor' => pixflow_get_theme_mod('nav_hover_color', PIXFLOW_NAV_HOVER_COLOR),
		'navColorSecond' => pixflow_get_theme_mod('nav_color_second', PIXFLOW_NAV_COLOR_SECOND),
		'navHoverColorSecond' => pixflow_get_theme_mod('nav_hover_color_second', PIXFLOW_NAV_HOVER_COLOR_SECOND),
		'headerBgGradientColor1' => pixflow_get_theme_mod('header_bg_gradient_color1', PIXFLOW_HEADER_BG_GRADIENT_COLOR1),
		'headerBgGradientColor2' => pixflow_get_theme_mod('header_bg_gradient_color2', PIXFLOW_HEADER_BG_GRADIENT_COLOR2),
		'headerBgGradientOrientation' => pixflow_get_theme_mod('header_bg_gradient_orientation', PIXFLOW_HEADER_BG_GRADIENT_ORIENTATION),
		'headerBgColorTypeSecond' => pixflow_get_theme_mod('header_bg_color_type_second', PIXFLOW_HEADER_BG_COLOR_TYPE_SECOND),
		'headerBgGradientSecondColor1' => pixflow_get_theme_mod('header_bg_gradient_second_color1', PIXFLOW_HEADER_BG_GRADIENT_SECOND_COLOR1),
		'headerBgGradientSecondColor2' => pixflow_get_theme_mod('header_bg_gradient_second_color2', PIXFLOW_HEADER_BG_GRADIENT_SECOND_COLOR2),
		'headerBgGradientSecondOrientation' => pixflow_get_theme_mod('header_bg_gradient_second_orientation', PIXFLOW_HEADER_BG_GRADIENT_SECOND_ORIENTATION),
		'headerBgSolidColorSecond' => pixflow_get_theme_mod('header_bg_solid_color_second', PIXFLOW_HEADER_BG_SOLID_COLOR_SECOND),
		'headerBgSolidColor' => pixflow_get_theme_mod('header_bg_solid_color', PIXFLOW_HEADER_BG_SOLID_COLOR),
		'businessBarEnable' => pixflow_get_theme_mod('businessBar_enable', PIXFLOW_BUSINESSBAR_ENABLE),
		'sidebar_style' => pixflow_get_theme_mod('sidebar-style', PIXFLOW_SIDEBAR_STYLE),
		'page_sidebar_bg_image_position' => pixflow_get_theme_mod('page_sidebar_bg_image_position', PIXFLOW_PAGE_SIDEBAR_BG_IMAGE_POSITION),
		'sidebar_style_shop' => pixflow_get_theme_mod('sidebar-style-shop', PIXFLOW_SIDEBAR_STYLE_SHOP),
		'shop_sidebar_bg_image_position' => pixflow_get_theme_mod('shop_sidebar_bg_image_position', PIXFLOW_SHOP_SIDEBAR_BG_IMAGE_POSITION),
		'sidebar_style_single' => pixflow_get_theme_mod('sidebar-style-single', PIXFLOW_SIDEBAR_STYLE_SINGLE),
		'single_sidebar_bg_image_position' => pixflow_get_theme_mod('single_sidebar_bg_image_position', PIXFLOW_SINGLE_SIDEBAR_BG_IMAGE_POSITION),
		'sidebar_style_blog' => pixflow_get_theme_mod('sidebar-style-blog', PIXFLOW_SIDEBAR_STYLE_BLOG),
		'blog_sidebar_bg_image_position' => pixflow_get_theme_mod('blog_sidebar_bg_image_position', PIXFLOW_BLOG_SIDEBAR_BG_IMAGE_POSITION),
		'showUpAfter' => pixflow_get_theme_mod('show_up_after', PIXFLOW_SHOW_UP_AFTER),
		'showUpStyle' => pixflow_get_theme_mod('show_up_style', PIXFLOW_SHOW_UP_STYLE),
		'siteTop' => pixflow_get_theme_mod('site_top', PIXFLOW_SITE_TOP),
		'footerWidgetAreaSkin' => pixflow_get_theme_mod('footer_widget_area_skin', PIXFLOW_FOOTER_WIDGET_AREA_SKIN),
		'headerTopWidth' => pixflow_get_theme_mod('header_top_width', PIXFLOW_HEADER_TOP_WIDTH),
		'layoutWidth' => pixflow_get_theme_mod('site_width', PIXFLOW_SITE_WIDTH),
		'lightLogo' => pixflow_get_theme_mod('light_logo', PIXFLOW_LIGHT_LOGO),
		'darkLogo' => pixflow_get_theme_mod('dark_logo', PIXFLOW_DARK_LOGO),
		'logoStyle' => pixflow_get_theme_mod('logo_style', PIXFLOW_LOGO_STYLE),
		'logoStyleSecond' => pixflow_get_theme_mod('logo_style_second', PIXFLOW_LOGO_STYLE_SECOND),
		'activeNotificationTab' => pixflow_get_theme_mod('active_tab_sec', PIXFLOW_ACTIVE_TAB_SEC),
		'goToTopShow' => pixflow_get_theme_mod('go_to_top_show', PIXFLOW_GO_TO_TOP_SHOW),
		'loadingType' => pixflow_get_theme_mod('loading_type', PIXFLOW_LOADING_TYPE),
		'leaveMsg' => esc_attr__('You are about to leave this page and you haven\'t saved changes yet, would you like to save changes before leaving?','massive-dynamic'),
		'unsaved' => esc_attr__('Unsaved Changes!','massive-dynamic'),
		'save_leave' => esc_attr__('Save & Leave','massive-dynamic'),
		'mailchimpNotInstalled' => esc_attr__('MailChimp for Wordpress is not installed.','massive-dynamic'),
		'search' => esc_attr__('Search...','massive-dynamic'),
	);
	if(is_front_page()){
		$customizeLocalizedOptions['loadingText'] = pixflow_get_theme_mod('loading_text', PIXFLOW_LOADING_TEXT);
	}else{
		$customizeLocalizedOptions['loadingText'] = '';
	}
	wp_localize_script('main-custom-js', 'themeOptionValues', $customizeLocalizedOptions);

	// Load required scripts in customizer
	if (is_customize_preview()) {
		global $md_allowed_HTML_tags;
		wp_register_script('livepreview',pixflow_path_combine(PIXFLOW_THEME_CUSTOMIZER_URI . '/assets/js', 'livepreview.min.js'),false,PIXFLOW_THEME_VERSION,false);
		wp_enqueue_style('livepreviewstyle', PIXFLOW_THEME_LIB_URI . '/customizer/assets/css/livepreview.min.css',array(),PIXFLOW_THEME_VERSION);
		wp_enqueue_script('livepreview');
		wp_localize_script('livepreview', 'livepreview_var', array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce'),
				'settings' => esc_attr__('settings','massive-dynamic'),
				'footerSetting' => esc_attr__('Footer Settings','massive-dynamic'),
				'saving' => esc_attr__('Saving...','massive-dynamic'),
				'save_preview' => esc_attr__('Publish','massive-dynamic'),
				'allDone' => esc_attr__('All Done!','massive-dynamic'),
				'cantSave' => esc_attr__('Can\'t save changes','massive-dynamic'),
				'cantSaveBtn' => esc_attr__('Got It','massive-dynamic'),
				'cantSaveMsg' => wp_kses(__("Massive Builder has detected an error which prevents the builder from saving data. In most cases, 3rd party plugins and server configurations cause this issue.<br/>Please disable all plugins and test the save functionality again. If it worked, try enabling them one by one to find the one that is causing the issue. If this method didn't work, please submit a ticket from <a href='https://help.massivedynamic.co/hc/en-us/requests/new' target='_blank'>Help Center</a> and we will help you quickly.",'massive-dynamic'),$md_allowed_HTML_tags)
			)
		);
	}

	if(is_singular('portfolio')){
		wp_enqueue_style('carousel_css',pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'owl.carousel.min.css'),array(),PIXFLOW_THEME_VERSION);
		wp_enqueue_script('carousel_js',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'owl.carousel.min.js'),array(),PIXFLOW_THEME_VERSION,true);
	}

	pixflow_add_inline_css();
}
add_action('wp_enqueue_scripts', 'pixflow_theme_scripts');

//for define tween max in admin panel
function pixflow_admin_theme_scripts(){
	wp_enqueue_script('videojs-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/video.min.js'),array(),PIXFLOW_THEME_VERSION,true);
	wp_enqueue_script('videojs-youtube-script',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'video-js/youtube.min.js'),array(),PIXFLOW_THEME_VERSION,true);
	wp_enqueue_script('tweenMax',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'TweenMax.min.js'),array(),PIXFLOW_THEME_VERSION,true);
}
add_action('admin_enqueue_scripts', 'pixflow_admin_theme_scripts');

function pixflow_customScript() {
	// wp_enqueue_script('loadcss', PIXFLOW_THEME_JS_URI . '/loadcss.js', array(), PIXFLOW_THEME_VERSION);
	wp_enqueue_script( 'custom-script', pixflow_path_combine(PIXFLOW_THEME_JS_URI,'custom-script.min.js'),array('jquery'));
	wp_enqueue_style( 'custom-style', pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'custom-style.min.css'));
	$custom_js = 'try {'.pixflow_get_theme_mod('custom_js').'}catch(e){console.log("Syntax Error in Custom JS")}';
	wp_add_inline_script( 'custom-script',$custom_js );
}
add_action( 'wp_enqueue_scripts', 'pixflow_customScript',1 );

function pixflow_theme_fonts()
{
	$h1   = (pixflow_get_theme_mod('h1_fontfamily_mode',PIXFLOW_H1_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h1_name',PIXFLOW_H1_NAME) : '';
	$h2   = (pixflow_get_theme_mod('h2_fontfamily_mode',PIXFLOW_H2_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h2_name',PIXFLOW_H2_NAME) : '';
	$h3   = (pixflow_get_theme_mod('h3_fontfamily_mode',PIXFLOW_H3_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h3_name',PIXFLOW_H3_NAME) : '';
	$h4   = (pixflow_get_theme_mod('h4_fontfamily_mode',PIXFLOW_H4_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h4_name',PIXFLOW_H4_NAME) : '';
	$h5   = (pixflow_get_theme_mod('h5_fontfamily_mode',PIXFLOW_H5_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h5_name',PIXFLOW_H5_NAME) : '';
	$h6   = (pixflow_get_theme_mod('h6_fontfamily_mode',PIXFLOW_H6_FONTFAMILY_MODE)     == 'google') ? pixflow_get_theme_mod('h6_name',PIXFLOW_H6_NAME) : '';
	$p    = (pixflow_get_theme_mod('p_fontfamily_mode',PIXFLOW_P_FONTFAMILY_MODE)       == 'google') ? pixflow_get_theme_mod('p_name',PIXFLOW_P_NAME)  : '';
	$link = (pixflow_get_theme_mod('link_fontfamily_mode',PIXFLOW_LINK_FONTFAMILY_MODE) == 'google') ? pixflow_get_theme_mod('link_name',PIXFLOW_LINK_NAME) : '';
	$nav  = (pixflow_get_theme_mod('nav_fontfamily_mode',PIXFLOW_NAV_FONTFAMILY_MODE)   == 'google') ? pixflow_get_theme_mod('nav_name',PIXFLOW_NAV_NAME) : '';
	$notification = $nav;

	//Fix for setup problem (shouldn't happen after the update, just for old setups)
	if('' == $h1 && '' == $h2 && '' == $h3 && '' == $h4 && '' == $h5 && '' == $h6
	   && '' == $p && '' == $nav && ''== $link && '' == $notification )
		$notification = $h1 = $h2 = $h3 = $h4 = $h5 = $h6 = $p = $link = $nav = '';

	$fonts        = array($h1, $h2, $h3,$h4,$h5,$h6,$p,$nav,$link,$notification);
	$fonts        = array_filter($fonts);//remove empty elements

	$fontVariants = array(
		array( (pixflow_get_theme_mod('h1_style',PIXFLOW_H1_STYLE)== 1 ) ? pixflow_get_theme_mod('h1_weight',PIXFLOW_H1_WEIGHT).'italic': pixflow_get_theme_mod('h1_weight',PIXFLOW_H1_WEIGHT) ),
		array( (pixflow_get_theme_mod('h2_style',PIXFLOW_H2_STYLE)== 1 ) ? pixflow_get_theme_mod('h2_weight',PIXFLOW_H2_WEIGHT).'italic': pixflow_get_theme_mod('h2_weight',PIXFLOW_H2_WEIGHT) ),
		array( (pixflow_get_theme_mod('h3_style',PIXFLOW_H3_STYLE)== 1 ) ? pixflow_get_theme_mod('h3_weight',PIXFLOW_H3_WEIGHT).'italic': pixflow_get_theme_mod('h3_weight',PIXFLOW_H3_WEIGHT) ),
		array( (pixflow_get_theme_mod('h4_style',PIXFLOW_H4_STYLE)== 1 ) ? pixflow_get_theme_mod('h4_weight',PIXFLOW_H4_WEIGHT).'italic': pixflow_get_theme_mod('h4_weight',PIXFLOW_H4_WEIGHT) ),
		array( (pixflow_get_theme_mod('h5_style',PIXFLOW_H5_STYLE)== 1 ) ? pixflow_get_theme_mod('h5_weight',PIXFLOW_H5_WEIGHT).'italic': pixflow_get_theme_mod('h5_weight',PIXFLOW_H5_WEIGHT) ),
		array( (pixflow_get_theme_mod('h6_style',PIXFLOW_H6_STYLE)== 1 ) ? pixflow_get_theme_mod('h6_weight',PIXFLOW_H6_WEIGHT).'italic': pixflow_get_theme_mod('h6_weight',PIXFLOW_H6_WEIGHT) ),
		array( (pixflow_get_theme_mod('p_style',PIXFLOW_P_STYLE)== 1 )  ? pixflow_get_theme_mod('p_weight',PIXFLOW_P_WEIGHT).'italic' : pixflow_get_theme_mod('p_weight',PIXFLOW_P_WEIGHT) ),
		array( (pixflow_get_theme_mod('nav_style',PIXFLOW_NAV_STYLE)== 1 )  ? pixflow_get_theme_mod('nav_weight',PIXFLOW_NAV_WEIGHT).'italic' : pixflow_get_theme_mod('nav_weight',PIXFLOW_NAV_WEIGHT) ),
		array( (pixflow_get_theme_mod('link_style',PIXFLOW_LINK_STYLE)== 1 ) ? pixflow_get_theme_mod('link_weight',PIXFLOW_LINK_WEIGHT).'italic' : pixflow_get_theme_mod('link_weight',PIXFLOW_LINK_WEIGHT)),
		array(400,700)
	);//Suggested variants if available//Suggested variants if available

	$fontSubsets = array();
	if (pixflow_get_theme_mod('advance_char',PIXFLOW_ADVANCE_CHAR)){

		if(pixflow_get_theme_mod('cyrillic',PIXFLOW_CYRILLIC)== 1)
			$fontSubsets [] = 'cyrillic';

		if(pixflow_get_theme_mod('cyrillic_ext',PIXFLOW_CYRILLIC_EXT)== 1)
			$fontSubsets [] = 'cyrillic-ext';

		if(pixflow_get_theme_mod('latin',PIXFLOW_LATIN)== 1)
			$fontSubsets [] = 'latin';

		if(pixflow_get_theme_mod('latin_ext',PIXFLOW_LATIN_EXT)== 1)
			$fontSubsets [] = 'latin-ext';

		if(pixflow_get_theme_mod('greek',PIXFLOW_GREEK)== 1)
			$fontSubsets [] = 'greek';

		if(pixflow_get_theme_mod('greek_ext',PIXFLOW_GREEK_EXT)== 1)
			$fontSubsets [] = 'greek-ext';

		if(pixflow_get_theme_mod('vietnamese',PIXFLOW_VIETNAMESE)== 1)
			$fontSubsets [] = 'vietnamese';

	}

	$fontList     = array();
	$fontReq      = '//fonts.googleapis.com/css?family=';
	$gf           = new PixflowGoogleFonts(pixflow_path_combine(PIXFLOW_THEME_LIB_URI, 'googlefonts.txt'));

	//Build font list
	foreach($fonts as $key => $font)
	{

		$duplicate = false;
		//Search for duplicate
		foreach($fontList as &$item)
		{
			if($font == $item['font'] )
			{
				$duplicate = true;
				$item['variants'] = array_unique(array_merge($item['variants'],$fontVariants[$key] ));
				break;
			}
		}

		//Add
		if(!$duplicate){
			$fontList[] = array('font' => $font, 'variants' => $fontVariants[$key]);
		}
	}

	$temp=array();
	$i=0;

	$subsets = array();

	foreach($fontList as $fontItem)
	{
		$i++;

		$font = $gf->Pixflow_GetFontByName($fontItem['font']);

		if(null==$font){
			continue;
		}

		$variants = array();

		foreach($fontItem['variants'] as $variant)
		{
			//Check if font object has the variant
			if(in_array($variant, $font->variants))
			{
				$variants[] = $variant;
			}
			else if(400 == $variant && in_array('regular', $font->variants))
			{
				$variants[] = $variant;
			}else if('400italic' === $variant && in_array('italic',$font->variants))
			{
				$variants[] = $variant ;
			}else{
				$num = mb_substr($variant,0,3);
				if(in_array( $num , $font->variants )){
					$variants[] = $num;
				}
			}
		}

		$subsets =$fontSubsets;


		$query = preg_replace('/ /', '+', $fontItem['font']);

		if(count($variants))
			$query .= ':' . implode(',', $variants);

		$temp[] = $query;
	}

	$subsetReq = '';
	if(count($subsets))
	{
		$subsetReq = '&subset=' . implode(',', $subsets);
	}

	if(count($temp))
	{
		$fontReq .= implode('|', $temp);

		if(strlen($subsetReq))
			$fontReq .= $subsetReq;

		wp_enqueue_style('fonts', $fontReq);
	}
}

function pixflow_generate_static_js_css($assets_path){
	$mbuilder = MBuilder::getInstance();
	if(!file_exists($assets_path['js']) || !file_exists($assets_path['css'])){
		$mbuilder->generate_static_js_css($assets_path['page_id']);
	}
}

function pixflow_load_page_style($assets_path){
	$last_modified = get_post_modified_time('Y-m-d-h-i-s');
	if(file_exists($assets_path['css'])){
		wp_enqueue_style('page-style',PIXFLOW_THEME_CACHE_URI.'/'. $assets_path['page_id'] .'.css' ,false,$last_modified);
	}
}

function pixflow_load_page_scripts($assets_path){
	$last_modified = get_post_modified_time('Y-m-d-h-i-s');
	if(file_exists($assets_path['js'])){
		wp_enqueue_script('page-script',PIXFLOW_THEME_CACHE_URI.'/'. $assets_path['page_id'] .'.js' , array('main-custom-js') , $last_modified,true);
	}
}

function pixflow_load_page_assets(){
	global $assets_path ;
	if(!is_customize_preview()){
		pixflow_load_shortcodes();
	}
	$page_id = get_the_ID();
	$path = array (
		'page_id' => $page_id ,
		'js' => PIXFLOW_THEME_CACHE . '/' . $page_id . '.js' ,
		'css' => PIXFLOW_THEME_CACHE . '/' . $page_id. '.css'
	);
	pixflow_generate_static_js_css($path);
	$assets_path = $path ;
	return;
}


/*====================================================
                Custom Fonts
======================================================*/
function pixflow_load_custom_font(){

	$styleCustomFont = '';

	$typography = array('h1','h2','h3','h4','h5','h6','p','link','nav');

	foreach ($typography as $typo){

		$fontFamilyMode  = pixflow_get_theme_mod($typo.'_fontfamily_mode',constant('PIXFLOW_' . strtoupper($typo) . '_FONTFAMILY_MODE'));
		$fontFamily  = $typo.'_custom_font';
		$fontFamilyUrl  = pixflow_get_theme_mod($typo.'_custom_font_url',constant('PIXFLOW_' . strtoupper($typo) . '_CUSTOM_FONT_URL'));

		if ($fontFamilyMode == 'custom' && $fontFamily != '' && $fontFamilyUrl != ''){
			ob_start();
			?>
            @font-face {
            font-family: <?php echo "'".esc_attr($fontFamily)."'" ?>;
            font-weight: <?php echo esc_attr(pixflow_get_theme_mod($typo.'_weight',constant('PIXFLOW_' . strtoupper($typo) . '_WEIGHT'))); ?>;
            src: url(<?php echo esc_url($fontFamilyUrl); ?>) format('woff2');
            }
			<?php
			$styleCustomFont .= ob_get_clean();
		}

	}

	return $styleCustomFont;
}


/*====================================================
                Custom Css
======================================================*/
function pixflow_add_inline_css(){
	$customCssText = pixflow_get_theme_mod('custom_css');
	// load custom font styles
	$customFontStyles = pixflow_load_custom_font();
	$customCssText .= $customFontStyles;
	wp_add_inline_style("responsive-style",$customCssText);
	wp_add_inline_style("child-style",$customCssText);
}
//add_action( 'wp_enqueue_scripts', 'pixflow_add_inline_css', 100 );

/* load shortcodes */
function pixflow_load_shortcodes(){
	//Load Shortcodes
	global $shortcodesBootStrap;
	$shortcodesBootStrap = PixflowFramework::Pixflow_Shortcodes();
	$mbuilder = MBuilder::getInstance();
	$used_shortcodes = $mbuilder->list_used_shortcodes();
	$used_shortcodes = array_map(function($value) { return $value.'/index'; }, $used_shortcodes);
	$shortcodesBootStrap = array_intersect($shortcodesBootStrap,$used_shortcodes);
	/* load dependent shortcodes */
	$dependent_shortcodes = array();
	foreach ($shortcodesBootStrap as $shortcode){
		$shortcode = str_replace('/index','',$shortcode);
		// Load dependency array
		$require_plugins = pixflow_load_dependecy_file($shortcode);
		$required_shortcodes = $require_plugins['shortcode'];
		$dependent_shortcodes = array_merge($dependent_shortcodes, $required_shortcodes);
	}
	$dependent_shortcodes = array_map(function($value) { return $value.'/index'; }, $dependent_shortcodes);
	$shortcodesBootStrap = array_unique(array_merge($shortcodesBootStrap,$dependent_shortcodes));
	$do_shortcodes = pixflow_load_do_shortcodes();
	$do_shortcodes = array_map(function($value) { return $value.'/index'; }, $do_shortcodes);
	$shortcodesBootStrap = array_unique(array_merge($shortcodesBootStrap,$do_shortcodes));
	PixflowFramework::Pixflow_Require_Files( PIXFLOW_THEME_LIB . '/shortcodes',$shortcodesBootStrap);
}
