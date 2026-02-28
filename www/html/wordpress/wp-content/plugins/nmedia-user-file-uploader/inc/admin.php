<?php

 if( ! defined('ABSPATH' ) ){
	exit;
}
/*
* plugin settings
*/
function wpfm_save_settings() {
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("Sorry, this request cannot be completed contact admin", "wpfm"));
	}*/
	
	if( !current_user_can('manage_options') ) {
		wp_send_json_error(__("Sorry, you are not allowed to save settings.", "wpfm"));
	}
	
	$wpfm_settigns = array( 
		"wpfm_files_view" 				=> isset($_REQUEST['wpfm_files_view'] ) && $_REQUEST['wpfm_files_view'] != '' ? $_REQUEST['wpfm_files_view']  : 'grid',
		"wpfm_thumb_size" 				=> isset($_REQUEST['wpfm_thumb_size']) ? sanitize_text_field($_REQUEST['wpfm_thumb_size']) : '',
		"wpfm_button_title" 			=> isset($_REQUEST['wpfm_button_title']) ? sanitize_text_field($_REQUEST['wpfm_button_title']) : '',
		"wpfm_upload_title" 			=> isset($_REQUEST['wpfm_upload_title']) ? sanitize_text_field($_REQUEST['wpfm_upload_title']) : '',
		"wpfm_max_file_size" 			=> isset($_REQUEST['wpfm_max_file_size']) ? sanitize_text_field($_REQUEST['wpfm_max_file_size']) : '',
		"wpfm_number_server_file_role" 	=> isset($_REQUEST['wpfm_number_server_file_role']) ? sanitize_textarea_field($_REQUEST['wpfm_number_server_file_role']) : '',
		"wpfm_max_files" 				=> isset($_REQUEST['wpfm_max_files'] ) && $_REQUEST['wpfm_max_files'] != '' ? intval( $_REQUEST['wpfm_max_files']  ): '',
		"wpfm_max_files_user" 			=> isset($_REQUEST['wpfm_max_files_user']) && $_REQUEST['wpfm_max_files_user'] !='' ? intval( $_REQUEST['wpfm_max_files_user'] ) : '',
		"wpfm_file_format" 				=> isset($_REQUEST['wpfm_file_format']) ? sanitize_text_field($_REQUEST['wpfm_file_format']) : '',
		"wpfm_file_types" 				=> isset($_REQUEST['wpfm_file_types']) ? sanitize_text_field($_REQUEST['wpfm_file_types']) : '',
		"wpfm_file_sharing" 			=> isset($_REQUEST['wpfm_file_sharing']) ? sanitize_text_field($_REQUEST['wpfm_file_sharing']) : '',
		"wpfm_file_allow_drag_n_drop" 	=> isset($_REQUEST['wpfm_file_allow_drag_n_drop']) ? sanitize_text_field($_REQUEST['wpfm_file_allow_drag_n_drop']) : '',
		
		"wpfm_min_files" 				=> isset($_REQUEST['wpfm_min_files']) ? sanitize_text_field($_REQUEST['wpfm_min_files']) : '',
		"wpfm_default_quota" 			=> isset($_REQUEST['wpfm_default_quota']) ? sanitize_textarea_field($_REQUEST['wpfm_default_quota']) : '',
		"wpfm_filesize_role" 			=> isset($_REQUEST['wpfm_filesize_role']) ? sanitize_textarea_field($_REQUEST['wpfm_filesize_role']) : '',
		"wpfm_on_of_file_role" 			=> isset($_REQUEST['wpfm_on_of_file_role']) ? sanitize_textarea_field($_REQUEST['wpfm_on_of_file_role']) : '',
		"wpfm_default_dir" 				=> isset($_REQUEST['wpfm_default_dir']) ? sanitize_textarea_field($_REQUEST['wpfm_default_dir']) : '',
		"wpfm_file_notification" 		=> isset($_REQUEST['wpfm_file_notification']) ? sanitize_text_field($_REQUEST['wpfm_file_notification']) : '',
		"wpfm_from_email" 				=> isset($_REQUEST['wpfm_from_email']) ? sanitize_text_field($_REQUEST['wpfm_from_email']) : '',
		"wpfm_public_user" 				=> isset($_REQUEST['wpfm_public_user']) ? intval($_REQUEST['wpfm_public_user']) : '',
		"wpfm_email_recipients"			=> isset($_REQUEST['wpfm_email_recipients']) ? sanitize_text_field($_REQUEST['wpfm_email_recipients']) : '',
		"wpfm_email_recipients_subject"			=> isset($_REQUEST['wpfm_email_recipients_subject']) ? sanitize_text_field($_REQUEST['wpfm_email_recipients_subject']) : '',
		"wpfm_file_rename" 				=> isset($_REQUEST['wpfm_file_rename']) ? sanitize_text_field($_REQUEST['wpfm_file_rename']) : '',
		"wpfm_create_dir_role" 				=> isset($_REQUEST['wpfm_create_dir_role']) ? sanitize_text_field($_REQUEST['wpfm_create_dir_role']) : '',
		
		"wpfm_disable_bootstarp" 		=> isset($_REQUEST['wpfm_disable_bootstarp']) ? sanitize_text_field($_REQUEST['wpfm_disable_bootstarp']) : '',
		"wpfm_disable_breadcrumbs" 		=> isset($_REQUEST['wpfm_disable_breadcrumbs']) ? sanitize_text_field($_REQUEST['wpfm_disable_breadcrumbs']) : '',
		"wpfm_down_open" 				=> isset($_REQUEST['wpfm_down_open']) ? sanitize_text_field($_REQUEST['wpfm_down_open']) : '',
		"wpfm_files_move" 				=> isset($_REQUEST['wpfm_files_move']) ? sanitize_text_field($_REQUEST['wpfm_files_move']) : '',
		"wpfm_diss_allow_file" 			=> isset($_REQUEST['wpfm_diss_allow_file']) ? sanitize_text_field($_REQUEST['wpfm_diss_allow_file']) : '',
		"wpfm_keep_old_log"				=> isset($_REQUEST['wpfm_keep_old_log']) ? sanitize_text_field($_REQUEST['wpfm_keep_old_log']) : '',
		"wpfm_pagination_limit"			=> isset($_REQUEST['wpfm_pagination_limit']) ? sanitize_text_field($_REQUEST['wpfm_pagination_limit']) : '0',
		"wpfm_files_per_row" 			=> isset($_REQUEST['wpfm_files_per_row']) ? sanitize_text_field($_REQUEST['wpfm_files_per_row']) : '',
		"wpfm_allow_guest_upload" 		=> isset($_REQUEST['wpfm_allow_guest_upload']) ? sanitize_text_field($_REQUEST['wpfm_allow_guest_upload']) : '',
		"wpfm_allow_each_user_see_files" => isset($_REQUEST['wpfm_allow_each_user_see_files']) ? sanitize_text_field($_REQUEST['wpfm_allow_each_user_see_files']) : '',
		"wpfm_allow_admin_see_all_files" => isset($_REQUEST['wpfm_allow_admin_see_all_files']) ? sanitize_text_field($_REQUEST['wpfm_allow_admin_see_all_files']) : '',
		"wpfm_create_dir" 				=> isset($_REQUEST['wpfm_create_dir']) ? sanitize_text_field($_REQUEST['wpfm_create_dir']) : '',
		"wpfm_create_directory_label"  	=> isset($_REQUEST['wpfm_create_directory_label']) ? sanitize_text_field($_REQUEST['wpfm_create_directory_label']) : '',
		"wpfm_send_file" 				=> isset($_REQUEST['wpfm_send_file']) ? sanitize_text_field($_REQUEST['wpfm_send_file']) : '',
		"wpfm_file_groups" 				=> isset($_REQUEST['wpfm_file_groups']) ? sanitize_text_field($_REQUEST['wpfm_file_groups']) : '',
		"wpfm_file_groups_add" 			=> isset($_REQUEST['wpfm_file_groups_add']) ? sanitize_text_field($_REQUEST['wpfm_file_groups_add']) : '',
		"wpfm_show_logout_button"		=> isset($_REQUEST['wpfm_show_logout_button']) ? sanitize_text_field($_REQUEST['wpfm_show_logout_button']) : '',
		"wpfm_hide_uploader"			=> isset($_REQUEST['wpfm_hide_uploader']) ? sanitize_text_field($_REQUEST['wpfm_hide_uploader']) : '',
		"wpfm_hide_files"				=> isset($_REQUEST['wpfm_hide_files']) ? sanitize_text_field($_REQUEST['wpfm_hide_files']) : '',
		
		"wpfm_file_saved" 				=> isset($_REQUEST['wpfm_file_saved']) ? sanitize_textarea_field($_REQUEST['wpfm_file_saved']) : '',
		"wpfm_public_message" 			=> isset($_REQUEST['wpfm_public_message']) ? sanitize_textarea_field($_REQUEST['wpfm_public_message']) : '',
		"wpfm_role_message" 			=> isset($_REQUEST['wpfm_role_message']) ? sanitize_textarea_field($_REQUEST['wpfm_role_message']) : '',
		"wpfm_email_message_sendfile" 	=> isset($_REQUEST['wpfm_email_message_sendfile']) ? sanitize_textarea_field($_REQUEST['wpfm_email_message_sendfile']) : '',
		"wpfm_email_message_notification"=>isset($_REQUEST['wpfm_email_message_notification']) ? sanitize_textarea_field($_REQUEST['wpfm_email_message_notification']) : '',
		
		"wpfm_enable_image_sizing"		=> isset($_REQUEST['wpfm_enable_image_sizing']) ? sanitize_text_field($_REQUEST['wpfm_enable_image_sizing']) : '',
		"wpfm_image_min_width"			=> isset($_REQUEST['wpfm_image_min_width']) ? sanitize_text_field($_REQUEST['wpfm_image_min_width']) : '',
		"wpfm_image_min_height"			=> isset($_REQUEST['wpfm_image_min_height']) ? sanitize_text_field($_REQUEST['wpfm_image_min_height']) : '',
		"wpfm_image_max_width" 			=> isset($_REQUEST['wpfm_image_max_width']) ? sanitize_text_field($_REQUEST['wpfm_image_max_width']) : '',
		"wpfm_image_max_height"			=> isset($_REQUEST['wpfm_image_max_height']) ? sanitize_text_field($_REQUEST['wpfm_image_max_height']) : '',
		"wpfm_resize_transform"			=> isset($_REQUEST['wpfm_resize_transform']) ? sanitize_text_field($_REQUEST['wpfm_resize_transform']) : '',
		
		"wpfm_ftp_notification" 		=> isset($_REQUEST['wpfm_ftp_notification']) ? sanitize_text_field($_REQUEST['wpfm_ftp_notification']) : '',
		"wpfm_email_message_ftp" 		=> isset($_REQUEST['wpfm_email_message_ftp']) ? sanitize_text_field($_REQUEST['wpfm_email_message_ftp']) : '',
		
		"wpfm_enable_amazon" 			=> isset($_REQUEST['wpfm_enable_amazon']) ? sanitize_text_field($_REQUEST['wpfm_enable_amazon']) : '',
		"wpfm_amazon_apikey" 			=> isset($_REQUEST['wpfm_amazon_apikey']) ? sanitize_text_field($_REQUEST['wpfm_amazon_apikey']) : '',
		"wpfm_amazon_apisecret" 		=> isset($_REQUEST['wpfm_amazon_apisecret']) ? sanitize_text_field($_REQUEST['wpfm_amazon_apisecret']) : '',
		"wpfm_amazon_bucket" 			=> isset($_REQUEST['wpfm_amazon_bucket']) ? sanitize_text_field($_REQUEST['wpfm_amazon_bucket']) : '',
		"wpfm_amazon_expires" 			=> isset($_REQUEST['wpfm_amazon_expires']) ? sanitize_text_field($_REQUEST['wpfm_amazon_expires']) : '',
		"wpfm_amazon_region" 			=> isset($_REQUEST['wpfm_amazon_region']) ? sanitize_text_field($_REQUEST['wpfm_amazon_region']) : '',
		"wpfm_acl_public" 				=> isset($_REQUEST['wpfm_acl_public']) ? sanitize_text_field($_REQUEST['wpfm_acl_public']) : '',
		
		"wpfm_enable_google_drive"		=> isset($_REQUEST['wpfm_enable_google_drive']) ? sanitize_text_field($_REQUEST['wpfm_enable_google_drive']) : '',
		"wpfm_google_apikey" 			=> isset($_REQUEST['wpfm_google_apikey']) ? sanitize_text_field($_REQUEST['wpfm_google_apikey']) : '',
		"wpfm_google_clientid" 			=> isset($_REQUEST['wpfm_google_clientid']) ? sanitize_text_field($_REQUEST['wpfm_google_clientid']) : '',
		
		// via hook
		"wpfm_allow_file_sharing"		=> isset($_REQUEST['wpfm_allow_file_sharing']) ? sanitize_text_field($_REQUEST['wpfm_allow_file_sharing']) : '',
		"wpfm_us_email_notification"		=> isset($_REQUEST['wpfm_us_email_notification']) ? sanitize_text_field($_REQUEST['wpfm_us_email_notification']) : '',
		"wpfm_us_email_message"		=> isset($_REQUEST['wpfm_us_email_message']) ? sanitize_textarea_field($_REQUEST['wpfm_us_email_message']) : '',
		
		// watermark
		"wpfm_wm_image"		=> isset($_REQUEST['wpfm_wm_image']) ? sanitize_text_field($_REQUEST['wpfm_wm_image']) : '',
		"wpfm_wm_position"		=> isset($_REQUEST['wpfm_wm_position']) ? sanitize_text_field($_REQUEST['wpfm_wm_position']) : '',
		
		//table view
		"wpfm_enable_table"		=> isset($_REQUEST['wpfm_enable_table']) ? sanitize_text_field($_REQUEST['wpfm_enable_table']) : '',
		"wpfm_enable_document_viewer"		=> isset($_REQUEST['wpfm_enable_document_viewer']) ? sanitize_text_field($_REQUEST['wpfm_enable_document_viewer']) : '',
	);
	
	update_option ( WPFM_SHORT_NAME . '_settings', $wpfm_settigns );
	
	wp_send_json_success(__("Settings saved successfully.", "wpfm"));
}


function wpfm_admin_add_menu_pages() {
	
	$submenu = add_submenu_page( 'edit.php?post_type=wpfm-files',
								 sprintf( __('%s', 'wpfm' ), 'Add New Files'),
								 sprintf( __('%s', 'wpfm' ), 'Add New Files'),
								 'manage_options',
								 'wpfm-addnew',
								 'wpfm_admin_render_addnew_file'
								);
						
	$submenu = add_submenu_page( 'edit.php?post_type=wpfm-files',
								 sprintf( __('%s', 'wpfm' ), 'Settings'),
								 sprintf( __('%s', 'wpfm' ), 'Settings'),
								 'manage_options',
								 'wpfm-settings',
								 'wpfm_admin_render_settings'
								);
}

function wpfm_admin_render_addnew_file(){
	// wpfm_load_templates('admin/add_new.php');
	$attrs = null;
	echo FFWP_Frontend()->ffmwp_render_frontend($attrs);
}

function wpfm_admin_render_settings() {

	if( wpfm_is_pro_installed() ){
		wpfm_load_templates('admin/settings.php');
	} else {
		wpfm_load_templates('admin/settings-legacy.php');
	}
}

function wpfm_admin_load_scripts( $hook ){
	
	global $post;
	
    if ( ( isset($post) && 'wpfm-files' === $post->post_type) || $hook == 'toplevel_page_wpfm' || $hook == 'users.php' || $hook == "wpfm-files_page_wpfm-settings" || $hook == 'admin_page_wpfm-settings' ) {
        // AnimateModal
	    wp_enqueue_style( 'wpfm-normalize', WPFM_URL .'/css/normalize.min.css');
	    wp_enqueue_style( 'wpfm-animate-modal', WPFM_URL .'/css/animate.min.css');
	    wp_enqueue_script( 'wpfm-modal-js', WPFM_URL .'/js/animatedModal.min.js', array('jquery'));

		wp_enqueue_style( WPFM_SHORT_NAME . '-' .'admin-style', WPFM_URL.'/css/admin.css' );
		wp_enqueue_style( WPFM_SHORT_NAME . '-' .'jquery-ui-styles', WPFM_URL.'/css/jquery-ui.min.css' );
		wp_enqueue_script ( WPFM_SHORT_NAME . '-' . 'admin-script', WPFM_URL . '/' . 'js/admin.js', array('jquery', 'jquery-ui-accordion', 'jquery-ui-tabs', 'jquery-ui-draggable', 'jquery-ui-dialog', 'jquery-ui-sortable'), true );
	
		// bootstrap-4
        wp_enqueue_style( 'wpfm-bootstrap4', WPFM_URL .'/css/bootstrap-4/css/bootstrap.css');
        wp_enqueue_script( 'wpfm-bootstrap4-min-js', WPFM_URL .'/css/bootstrap-4/bootstrap.min.js');
        
		wp_enqueue_script( 'wpfm-blcok-ui-js', WPFM_URL .'/js/block-ui.js', array('jquery','jquery-ui-core'));
		wp_enqueue_script( 'wpfm-lib', WPFM_URL .'/js/wpfm-lib.js', array('jquery'));
		wp_localize_script('wpfm-lib', 'wpfm_vars', wpfm_array_fileapi_vars());
		// wp_enqueue_script( 'wpfm-frizi-js', WPFM_URL .'/js/frizi-modal.js', array('jquery'));
		
		//wpfm modal css/js
		wp_enqueue_style( 'wpfm-modal-css', WPFM_URL .'/css/wpfm-modal.css');
		wp_enqueue_script( 'wpfm-admin-modal', WPFM_URL .'/js/modal.js', array('jquery', 'wpfm-modal-js', 'wpfm-lib'));
		// SweetAlert
		wp_enqueue_style('wpfm-swal', WPFM_URL."/js/swal/sweetalert.css");
		wp_enqueue_script( 'wpfm-swal', WPFM_URL .'/js/swal/sweetalertdown.min.js', array('jquery'));
		
		
		
		if( wpfm_is_addon_installed('amazon-upload') ) {
			WPFM_AMAZON()->load_script();
		}
		
    }
    

    if ( isset($post) && 'wpfm_downloads' === $post->post_type ){
	
	    wp_enqueue_style( 'wpfm-select2-css', WPFM_URL .'/css/select2.min.css');
		wp_enqueue_script( 'wpfm-select2-js', WPFM_URL .'/js/select2.js', array('jquery'));
		wp_enqueue_script ( 'wpfm-download-manager-js', WPFM_URL . '/' . 'js/download-manger.js', array('jquery'), true );
    }
    
}

function wpfm_admin_load_block_js(){
	//block js
	wp_enqueue_script( 'wpfm-block', WPFM_URL.'/js/block.js', array('wp-blocks', 'wp-editor' , 'wp-i18n', 'wp-element'), filemtime( WPFM_PATH .'/js/block.js' ) );
}




function wpfm_is_guest_upload_allow( $shortcode_params = null ) {
	
 	$is_guest_upload_allow = false;
 	
 	$can_public_upload_file_option = wpfm_get_option('_allow_guest_upload');
 	
 	/**
 	 * Guest upload option/setting b shortcode is disabled
 	 * @since 13.8
 	 **/ 
 	
 	if( !empty($can_public_upload_file_option)  ) {
  		$is_guest_upload_allow = true;
 	}
 	
 	if( $is_guest_upload_allow ) {
 		
 		/**
 		 * This will create a Guest user with following detail
 		 * usename: wpfm_guest
 		 * email: wpfm@wordpress.com
 		 **/
 		wpfm_setup_guest_user();
 	}
 	
 	return apply_filters('wpfm_guest_user_upload', $is_guest_upload_allow);
}

// Check guest user and create if not found
function wpfm_setup_guest_user() {
	
	$guest_username = apply_filters('wpfm_guest_username', 'wpfm_guest');
	$guest_email = apply_filters('wpfm_guest_email', 'wpfm@wordpress.com');
	
	$user_id = username_exists( $guest_username );
	if ( !$user_id and email_exists($guest_email) == false ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		$user_id = wp_create_user( $guest_username, $random_password, $guest_email );
	}
	
	// set option for guest user
	update_option('wpfm_guest_user_id', $user_id);
}


function wpfm_is_amazon_addon_enable() {
	
	$return = false;
 	if ( class_exists('WPFM_AmazonS3') && wpfm_get_option('_enable_amazon') == 'yes' ) {
 		
 		$amazon_key		= wpfm_get_option('_amazon_apikey');
		$amazon_secret	= wpfm_get_option('_amazon_apisecret');
		$amazon_bucket	= wpfm_get_option('_amazon_bucket');
		
		if(empty($amazon_key) || empty($amazon_secret) || empty($amazon_bucket) ) {
			$return = false;
		} else {
			$return = true;
		}
	}else{
		
		$return = false;
	}
	
	return apply_filters('wpfm_is_amazon_addon_enable',$return); 
}

function wpfm_get_file_types() {

	$file_types = (wpfm_get_option('_file_format') == 'custom') ? wpfm_get_option('_file_types') : wpfm_get_option('_file_format');
	return apply_filters('wpfm_file_types_accepted', $file_types);
}

function wpfm_can_user_choose_group_fileupload() {
 	
 	// $shortcode_params = NMFILEMANAGER() -> shortcode_params;
 	$can_choose_file_group_option = wpfm_get_option('_file_groups_add');
 	if( isset($shortcode_params['nm_filemanager_file_groups_add']) ){
 		return $shortcode_params['nm_filemanager_file_groups_add'];
 	} elseif( !isset($shortcode_params['nm_filemanager_file_groups_add'])  ) {
 		return $can_choose_file_group_option;
 	}
 	
}

function wpfm_save_meta() {

	// wp_send_json($_POST['wpfm']);
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("Sorry, this request cannot be completed contact admin", "wpfm"));
	}*/
	
	if( !current_user_can('manage_options') ) {
		wp_send_json_error(__("Sorry, you are not allowed to save settings.", "wpfm"));
	}
	
	// sanitized option
	$wpfm_options = $_POST['wpfm'];
	$sanitized_options = array_map(function($option){
		
		$option_sanitized = [];
		foreach($option as $type => $meta){
			
			$meta_array = [];
			foreach($meta as $key => $value){
				$meta_array[$key] = $key == 'options' ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
			}
			
			$option_sanitized[$type] = $meta_array;
		}
		return $option_sanitized;
		
	}, $_POST['wpfm']);
	
	
	// wpfm_pa($sanitized_options); 
	
	update_option ( WPFM_SHORT_NAME . '_file_meta', json_encode($sanitized_options) );
	
	wp_send_json_success(__("File meta is saved successfully", "wpfm"));
}

function wpfm_admin_delete_files($file_id){
	
	if(get_post_type( $file_id ) != 'wpfm-files') return;
	
	if( isset($_REQUEST['wpfm_delete_file']) ) return;
		
	$file  = new WPFM_File($file_id);
	
	if( $file->delete_file() ){
		update_user_meta( $file->owner_id, 'wpfm_total_filesize_used', wpfm_get_user_files_size($file->owner_id) );
	}
}