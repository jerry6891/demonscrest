<?php
/*
unique setting System
*/
global $md_uniqueSettings;
/**
 * (1) Enqueue scripts
 */
function pixflow_unique_setting_scripts() {
	global $md_uniqueSettings;
	wp_enqueue_script( 'unique_setting', pixflow_path_combine(PIXFLOW_THEME_LIB_URI,'/assets/script/unique-setting.js'), array('jquery'), '1.0', 1 );
	wp_localize_script( 'unique_setting', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' ),
		'uniqueSettings' => json_encode($md_uniqueSettings),
		)
	);
}
add_action( 'init', 'pixflow_unique_setting_scripts');

/**
 * Save post meta
 */
add_action( 'wp_ajax_nopriv_pixflow-save-unique-setting', 'pixflow_save_unique_setting');
add_action( 'wp_ajax_pixflow-save-unique-setting', 'pixflow_save_unique_setting');
function pixflow_save_unique_setting() {
	global $md_uniqueSettings;
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( '' );
	
	if ( isset( $_POST['pixflow_save_unique_setting'] ) ) {
	
		$post_id = $_POST['id']; // post id
		$post_detail = $_POST['detail']; // post detail
		$settings = $md_uniqueSettings; // settings & value
		$customized = $_POST['dirtyCustomized']; // customized values
		$headerItemOrder = $_POST['headerItemOrder'];
		$headerItemOrder = stripslashes(htmlspecialchars_decode($headerItemOrder));
		$customized = (json_decode(stripslashes($customized),true));
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		if ( current_user_can( 'edit_theme_options' ) ) { // user is logged in
			foreach($customized as $key=>$value){
			    if(in_array($key,$settings)){
					//unique setting
					$value = (is_bool($value) && !boolval($value))?'false':$value;
					if($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product'){
						update_option( $post_detail.'_'.$key, $value );
					}else{
						delete_post_meta($post_id,$key);
						update_post_meta( $post_id, $key, $value );
					}
				}else{
					//general setting
					set_theme_mod($key,$value);
				}
			}
			if($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product') {
				update_option( $post_detail.'_'."header_items_order", $headerItemOrder );
			}else{
				delete_post_meta($post_id,"header_items_order");
				update_post_meta( $post_id, "header_items_order", $headerItemOrder );
			}

        }
	}
	
	wp_die();
}

/**
 * Save post status
 */
add_action( 'wp_ajax_nopriv_pixflow-save-status', 'pixflow_save_status');
add_action( 'wp_ajax_pixflow-save-status', 'pixflow_save_status');
function pixflow_save_status() {
	global $md_uniqueSettings;
	$unique_settings = $md_uniqueSettings;
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
		die ( '' );

	if ( isset( $_POST['pixflow_save_status'] ) ) {
		$post_id = $_POST['id']; // post id
		$post_detail = $_POST['detail']; // post detail
		$action = $_POST['act']; // post detail
		$status = $_POST['status']; // status
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		if ( current_user_can( 'edit_theme_options' ) ) { // user is logged in
            if($action == 'save'){

				$purchase_code=get_option("md_purchase_code") !=''?get_option("md_purchase_code"):pixflow_old_get_theme_mod('purchase_code',PIXFLOW_PURCHASE_CODE);
				update_option("md_purchase_code",$purchase_code);

				if($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product'){
					unset($_SESSION[$post_detail.'_status']);
					if($action == 'save' && (get_option($post_detail."_setting_status") != 'unique') && $status == 'unique') {
						pixflow_copy_general_setting($post_detail);
					}
					update_option( $post_detail."_setting_status", $status );

				}else{
					$current_status = get_post_meta( $post_id, "setting_status",true );
					if($action == 'save' && ($current_status != 'unique' && $status == 'unique')) {
						pixflow_copy_general_setting($post_detail,$post_id);
					}
					delete_post_meta($post_id,"setting_status");
					update_post_meta( $post_id, "setting_status", $status );
				}

			}
			if($status == 'general'){
				foreach($unique_settings as $setting){
					if($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product'){
						delete_option($post_detail.'_'.$setting);
					}else{
						delete_post_meta($post_id,$setting);
					}
				}
			}
			if($status == 'general'){
				unset($_SESSION['unique_customized']);
			}elseif($status == 'unique'){
				unset($_SESSION['general_customized']);
			}
			if(($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product') && ($action == 'change')) {
				$_SESSION[$post_detail . '_status'] = $status;
			}

			if($action == 'save'){
				echo 'status saved!';
				unset($_SESSION['temp_status']);
			}else{
				$_SESSION['temp_status']['id'] = $post_id;
				$_SESSION['temp_status']['status'] = $status;
				echo 'status changed!';
			}
		}
	}
	exit;
}

/**
 * Get master setting
 */
add_action( 'wp_ajax_nopriv_pixflow-get-setting', 'pixflow_get_setting');
add_action( 'wp_ajax_pixflow-get-setting', 'pixflow_get_setting');
function pixflow_get_setting() {
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
		die ( '' );

	if ( isset( $_POST['status'] ) ) {

		$post_id = $_POST['id']; // post id
		$status = $_POST['status']; //  Setting status
		$settings = $_POST['settings']; // settings data
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		if ( current_user_can( 'edit_theme_options' ) ) { // user is logged in
			$settings = (json_decode(stripslashes($settings),true));
			pixflow_removeTemp();
			foreach($settings as $id=>$setting){
				$default = (defined('PIXFLOW_'.strtoupper(str_replace('-','_',$id))))?constant('PIXFLOW_'.strtoupper(str_replace('-','_',$id))):'';
				$settings[$id]['value'] = pixflow_old_get_theme_mod($id,$default,$post_id);
			}
            echo json_encode($settings);
		}
	}
	exit;
}

/*Copy from General and save to option/meta*/
function pixflow_copy_general_setting($post_detail='other',$post_id=''){
	global $md_uniqueSettings;
	foreach($md_uniqueSettings as $key){
		$default = (defined('PIXFLOW_'.strtoupper(str_replace('-','_',$key))))?constant('PIXFLOW_'.strtoupper(str_replace('-','_',$key))):'';
		$value = get_theme_mod($key,$default);
		if($post_detail == 'post' || $post_detail == 'portfolio' || $post_detail == 'product'){
			update_option( $post_detail.'_'.$key, $value );
		}else{
			delete_post_meta($post_id,$key);
			update_post_meta( $post_id, $key, $value );
		}
	}
}