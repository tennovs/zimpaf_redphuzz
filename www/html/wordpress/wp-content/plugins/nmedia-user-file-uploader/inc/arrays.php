<?php

 if( ! defined('ABSPATH' ) ){
	exit;
}
/**
*/

function wpfm_general_options() {

	$general_options = array(
		
		'button-title'	=> array(	'label'		=> __('Choose File Button Label', 'wpfm'),
		 							'id'			=> 'wpfm'.'_button_title',
		 							'type'			=> 'text',
		 							'default'		=> 'Select',
		 							'help'			=> 'Label to Select File on Frontend'
		 							),
		 'upload-title'	=> array(	'label'		=> __('Save File Button', 'wpfm'),
		 							'id'			=> 'wpfm'.'_upload_title',
		 							'type'			=> 'text',
		 							'default'		=> __('Upload File', 'wpfm'),
		 							'help'			=> 'Label to Upload or Save File'
		 							),
		'max-file_size'	=> array(	'label'		=> __('Maximum Filesize', 'wpfm'),
		 							'desc'		=> __('Enter maximum file size in mb', 'wpfm'),
		 							'id'			=> 'wpfm'.'_max_file_size',
		 							'type'			=> 'text',
		 							'default'		=> '3mb',
		 							'help'			=> __('In MB e.g: <strong>3mb</strong>', 'wpfm')
		 							),
		
		'custom-file-types'	=> array(	'label'		=> __('Allowed Filetypes', 'wpfm'),
		 							'desc'			=> __('Enter type of files to upload', 'wpfm'),
		 							'id'			=> 'wpfm'.'_file_types',
		 							'type'			=> 'text',
		 							'default'		=> '',
		 							'help'			=> __('e.g: <strong>jpg,png,gif,zip,pdf</strong> (Default types will be jpg,png,zip,pdf)', 'wpfm')
		 							),
		'load-bootstrap'=> array(  'label'	=> __('Do not load Bootstrap by Plugin?', 'wpfm'),
							   'desc'		=> __('If your theme already has Bootstrap, disable this option.', 'wpfm'),
		   						'id'		=> 'wpfm'.'_disable_bootstarp',
							   'type'		=> 'checkbox',
							   'default'	=> 'yes',
							   'options'	=> array('yes'	=> 'Yes'),
							   'help'		=> __('If your theme already has Bootstrap, Tick this option.', 'wpfm')
							),

		'disable-breadcrumbs'=> array(  'label'	=> __('Hide breadcrumb?', 'wpfm'),
							   'desc'		=> __('Hide breadcrumbs in fornt.', 'wpfm'),
		   						'id'		=> 'wpfm'.'_disable_breadcrumbs',
							   'type'		=> 'checkbox',
							   'default'	=> 'no',
							   'options'	=> array('yes'	=> 'Yes'),
							   'help'		=> __('If you want to hide breadcrumbs, Tick this option.', 'wpfm')
							),
							
	);

	return apply_filters('wpfm_general_options', $general_options);
}

function wpfm_messages_options() {
	
	$messages_options = array(
		'file-uploaded'	=> array(	'label'		=> __('File Saved', 'wpfm'),
									'desc'		=> '',
									'id'		=> 'wpfm'.'_file_saved',
									'type'		=> 'text',
									'default'	=> __('File(s) Uploaded!', 'wpfm'),
									'help'		=> __('Show when file or directory is created.', 'wpfm')
									),
		'public-message'=> array(  'label'	=> __('Message for guests', 'wpfm'),
								   'desc'		=> '',
  		   						   'id'			=> 'wpfm'.'_public_message',
								   'type'		=> 'text',
								   'default'	=> '',
								   'help'		=> __('This message will be displayed if user is not logged. If not provided user will be redirected to login page.', 'wpfm')
									),		
	);

	return apply_filters("wpfm_messages_options", $messages_options);
}
function wpfm_user_files_options(){
	$user_files_options = array(
			'file-meta'	=> 	array(	
					 		 'desc'		=> '',
							 'type'		=> 'file',
							 'id'		=> 'user-files.php',
		),
	);
	return apply_filters("wpfm_user_files_options", $user_files_options);
}

function wpfm_howto_options() {
	
	$howto_options = array(
		'how-to'	=> array(	
							'desc'		=> '',
							'type'		=> 'file',
							'id'		=> 'howtouse.php',
						),
	);
	return apply_filters("wpfm_howto_options", $howto_options);
}

function wpfm_array_get_admin_settigns() {

	$option_tabs = array(
		'general-settings'	=> array(	'name'		=> __('General Settings', 'wpfm'),
										'type'	=> 'tab',
										'desc'	=> '',
										'meat'	=> wpfm_general_options(),
							  
		),
		
		'messages-settings'	=> array(	'name'		=> __('Messages', 'wpfm'),
								'type'	=> 'tab',
								'desc'	=> __('All user messages regarding file manager', 'wpfm'),
								'meat'	=> wpfm_messages_options(),
								'class'	=> 'pro',
					
		),
		
		'howto'				=> array(	'name'		=> __('How to use?', 'wpfm'),
								'type'	=> 'tab',
								'desc'	=> __('Following guide is provided to use this plugin', 'wpfm'),
								'meat'	=> wpfm_howto_options(),
								'class'	=> '',
		
								),
	);
	
	$option_tabs = apply_filters('wpfm_admin_settings', $option_tabs);
	
	return $option_tabs;
}

// becuase it was defined in PRO, checking with old version
if( ! function_exists('wpfm_more_tabs') ) {
	function wpfm_more_tabs(){
		$legend = array();
		$wpfm_more_options = array(
			'How to Use' => array(
					
				'how'	=> array(	
								'desc'		=> '',
								'type'		=> 'file',
								'id'		=> 'howtouse.php',
							),
				
				),
				
			'Addon List' => array(
					
				'how'	=> array(	
								'desc'		=> '',
								'type'		=> 'file',
								'id'		=> 'more-plugins.php',
							),
				
				),
			
		);
		
		return apply_filters("wpfm_howto_options", $wpfm_more_options);
	}
}

function wpfm_array_get_ajax_callbacks() {
	
	return array(
		"wpfm_save_settings" 	=> false,
		"wpfm_create_directory" => true,
		// "wpfm_get_user_files" 	=> true,
		"wpfm_save_file_data" 	=> true,
		"wpfm_upload_file"		=> wpfm_is_guest_upload_allow() ? true : false,
		"wpfm_edit_file_title_desc" 	=> true,
		"wpfm_delete_file" 	=> true,
		"wpfm_delete_multiple_files" => true,
		"nm_uploadfile_move_file" 	=> true,
		"wpfm_save_meta" 	=> false,
		"wpfm_file_meta_update" 	=> true,
		"wpfm_send_file_in_email"	=> true,
		// "wpfm_save_ftp_files"	=> true,
	);
}


function wpfm_array_fileapi_vars () {

	$messages_js = array('files_loading'	=> __('Files are being loaded ...', 'wpfm'),
		 						'file_sharing'	=> __('Please wait ...', 'wpfm'),
		 						'file_uploading'=> __('File(s) are being saved', 'wpfm'),
		 						'file_upload_completed' => __('File Upload Complete', 'wpfm'),
		 						'file_uploaded' => wpfm_get_message_file_saved(),
		 						'file_upload_error' => __('Sorry, but some error while uplaoding. Please try again', 'wpfm'),
		 						'file_dimension_error' => __("Image Dimensions are Not Allowed, Required: \n", 'wpfm'),
		 						'file_size_error' => __("File Size Not Allowed, Required: \n", 'wpfm'),
		 						'file_delete' => __('Are you sure?', 'wpfm'),
		 						'file_deleting' => __('Deleting file ...', 'wpfm'),
		 						'file_deleted' => __('File deleted', 'wpfm'),
		 						'file_updating' => __('Updating file ...', 'wpfm'),
		 						'directory_creating' => __('Creating directory ...', 'wpfm'),
		 						// 'directory_created' => __('Directory is created', 'wpfm'),
		 						'select_group' => __('Select Group', 'wpfm'),
		 						'http_server_error' => __("Oops! Something wrong with server, please try again"),
		 						'file_type_error'	=> sprintf(__("Allowed Types: %s",'wpfm'), implode(',',wpfm_get_allowed_file_types())),
		 						'text_cancel'		=> __("Cancel", 'wpfm'),
		 						'text_yes'		=> __("Yes", 'wpfm'),
		 						'text_description'	=> __("Description", 'wpfm'),
		 						'text_title'	=> __("Title", 'wpfm'),
		 						'wpfm_lib_msg' => __('Just a moment...', 'wpfm')
		 						
		 					);

	$wpfm_js_vars = array(
			'ajaxurl' 			=> admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') ),
			'plugin_url'		=> WPFM_URL,
			'image_size' 		=> (wpfm_get_option('_thumb_size') != '') ? wpfm_get_option('_thumb_size') : '150',
			'max_file_size' 	=> wpfm_max_filesize_limit_by_role(),
			'max_files' 		=> user_can_upload_file_one_atemp(),
			'max_files_message' => sprintf(__("Max. %d file(s) can be uploaded", 'wpfm'), user_can_upload_file_one_atemp()),
			'file_types' 		=> wpfm_get_allowed_file_types(),
			'file_auto_upload' 	=> (wpfm_get_option('_file_auto_upload') == 'yes') ? true : false,
			'file_allow_duplicate' => (wpfm_get_option('_file_allow_duplicate') == 'yes') ? true : false,
			'file_drag_drop' 	=> wpfm_get_option('_file_allow_drag_n_drop'),
			'breadcrumb'		=> array('id'=>0, 'title'=>__('Home', 'wpfm') ),
 			'directory_init'	=> isset($_GET['dir_id']) ? intval($_GET['dir_id']) : 0,
 			'is_send_file'		=> (wpfm_get_option('_send_file') == 'yes') ? true : false,
 			'is_visible_file_details' => wpfm_is_files_area_visible(),
 			'total_file_allow'  => wpfm_files_allower_per_user(),
 			
 			// All files
 			'user_files'		=> wpfm_get_user_files(),
 			'file_meta'			=> wpfm_get_fields_meta(),
 			'url'				=> get_permalink(),
 			'files_per_row'		=> wpfm_get_option( '_files_per_row') == "" ? "2": wpfm_get_option( '_files_per_row'),

			// Image related
			'image_sizing' 		=> (wpfm_get_option('_enable_image_sizing') == 'yes') ? true : false,
			'image_min_width' 	=> (wpfm_get_option('_image_min_width') != '') ? wpfm_get_option('_image_min_width') : '320',
			'image_min_height' 	=> (wpfm_get_option('_image_min_height') != '') ? wpfm_get_option('_image_min_height') : '240',
			'image_max_width' 	=> (wpfm_get_option('_image_max_width') != '') ? wpfm_get_option('_image_max_width') : '3840',
			'image_max_height' 	=> (wpfm_get_option('_image_max_height') != '') ? wpfm_get_option('_image_max_height') : '2160',
			'image_resize' 		=> (wpfm_get_option('_resize_transform') != '') ? wpfm_get_option('_resize_transform') : false,
			
			// checking if amazon enable
			'amazon_enabled'	=> wpfm_is_amazon_addon_enable() ? 'yes' : 'no',

			'messages' 			=> apply_filters('wpfm_js_strings', $messages_js),
			
			'file_group_add' 	=> wpfm_can_user_choose_group_fileupload(),
			
			// check file source
			'file_src'		=> isset($_GET['file-src']) ? sanitize_text_field($_GET['file-src']) : 'my',
			'wpfm_lib_msg' => __('Just a moment...', 'wpfm')

			);
	

	return apply_filters('wpfm_js_vars', $wpfm_js_vars);
}

// We need to add different values for downloader shortcode
function wpfm_array_download_vars() {
	
	$wpfm_js_downnload_vars = array(
		'ajaxurl' 			=> admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') ),
		'plugin_url'		=> WPFM_URL,
		'breadcrumb'		=> array('id'=>0, 'title'=>__('Home', 'wpfm') ),
 		'directory_init'	=> isset($_GET['dir_id']) ? intval($_GET['dir_id']) : 0,
 		'is_send_file'		=> false,
 		
 		// All files
 		'user_files'		=> WPFM_DOWNLOAD()->get_files(),
 		'file_meta'			=> wpfm_get_fields_meta(),
 		'files_per_row'		=> wpfm_get_option( '_files_per_row') == "" ? "2": wpfm_get_option( '_files_per_row'),
		'is_visible_file_details'		=> (wpfm_get_option('_hide_files') == 'yes') ? false : true,
		'wpfm_lib_msg' => __('Just a moment...', 'wpfm')
		);
	

	return apply_filters('wpfm_js_download_vars', $wpfm_js_downnload_vars);
}

function wpfm_array_digital_downloads_vars() {
	
	$wpfm_js_digital_download_vars = array(
		'ajaxurl' 			=> admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') ),
		'plugin_url'		=> WPFM_URL,
		'breadcrumb'		=> array('id'=>0, 'title'=>__('Home', 'wpfm') ),
 		'directory_init'	=> isset($_GET['dir_id']) ? intval($_GET['dir_id']) : 0,
 		
 		// All files
 		'user_files'		=> NMEDD()->get_digital_files_for_sale(),
 		'file_meta'			=> wpfm_get_file_meta(),
 		'files_per_row'		=> wpfm_get_option( '_files_per_row') == "" ? "2": wpfm_get_option( '_files_per_row'),
		'is_visible_file_details'		=> (wpfm_get_option('_hide_files') == 'yes') ? false : true,
		'wpfm_lib_msg' => __('Just a moment...', 'wpfm')
	);
	return apply_filters('wpfm_js_digital_download_vars', $wpfm_js_digital_download_vars);
}

function wpfm_array_main_vars() {
	$is_enable_files_move =  wpfm_get_option('_files_move');
    if(empty($is_enable_files_move)) $is_enable_files_move = 'no';
    
    
	$wpfm_array_js_main_vars = array(
		'wpfm_files_upload_dir' =>  user_upload_file_in_given_dir(),
		'wpfm_files_move_var'	=>  $is_enable_files_move,
		'wpfm_main_msg'			=>  __('Please Wait...', 'wpfm'),
		'rest_api_url'			=> get_rest_url(null, 'wpfm/v1')
	);
	
	return apply_filters('wpfm_array_js_main_vars', $wpfm_array_js_main_vars);
}

function wpfm_get_allowed_file_types() {
	
	$allowed_types = wpfm_get_option('_file_types');
	
	$allowed_types_array = array('jpg','png','zip','pdf');
	
	if( ! empty($allowed_types) ) {
		$allowed_types_array = explode(',', $allowed_types);
		$allowed_types_array = array_map('trim', $allowed_types_array);
	}
	
	return apply_filters('wpfm_allowed_file_types', $allowed_types_array);
}

// User directories map
function wpfm_get_users_directories($user_id, $file_id) {
	
	$current_user = is_null($user_id) ? wpfm_get_current_user() : get_userdata( $user_id );
	$author_name = $current_user->user_login;
	
	// if(class_exists('WPFM_PRO')){
	// 	if(wpfm_get_option('_allow_each_user_see_files') == 'yes'){
			
	// 		$author_name = get_post_meta($file_id, 'author_name', true);
	// 	}
	// }
	
	
	$dir_map = array('root'		=> $author_name,
		'thumbs'	=> $author_name.'/thumbs',
		'ftp'		=> $author_name.'/ftp');
		
	return apply_filters('wpfm_users_directory', $dir_map, $current_user);
}

// Custom post type meta
function wpfm_array_cpt_meta() {
	
	$cpt_meta = array();
	
	$cpt_meta['wpfm-files'] = array (
		'labels' => array (
				'name' 				 => __ ( 'Files', 'wpfm' ),
				'singular_name' 	 => __ ( 'Files', 'wpfm' ),
				'add_new' 			 => __('Add New', 'wpfm'),
				'all_items' 		 => __('All Files', 'wpfm'),
				'add_new_item' 		 => __('Add Files', 'wpfm'),
				'edit' 				 => __('Edit', 'wpfm'),
				'edit_item' 		 => __('Edit Files', 'wpfm'),
				'new_item' 			 => __('New Files', 'wpfm'),
				'view' 				 => __('View', 'wpfm'),
				'view_item' 		 => __('View Files', 'wpfm'),
				'search_items' 		 => __('Search Files', 'wpfm'),
				'not_found' 		 => __('No Files found', 'wpfm'),
				'not_found_in_trash' => __('No Files found in Trash', 'wpfm'),
				'parent' 			 => __('Parent Files', 'wpfm'),
				'menu_name' 			 => __('File Manager', 'wpfm')
		),
		'public' => true,
		'supports' => array (
				'title',
				'',
				'custom-fields',
				'comments',
				'thumbnail',
		),
		'exclude_from_search'=>true,
		'menu_icon' => WPFM_URL.'/images/logo.png'
	);
	
	return apply_filters('wpfm_cpt_meta', $cpt_meta);
}

// becuase it was defined in PRO, checking with old version
if( ! function_exists('wpfm_more_tabs') ) {
	function wpfm_more_tabs(){
		$legend = array();
		$wpfm_more_options = array(
			'How to Use' => array(
					
				'how'	=> array(	
								'desc'		=> '',
								'type'		=> 'file',
								'id'		=> 'howtouse.php',
							),
				
				),
				
			'Addon List' => array(
					
				'how'	=> array(	
								'desc'		=> '',
								'type'		=> 'file',
								'id'		=> 'more-plugins.php',
							),
				
				),
			
		);
		
		return apply_filters("wpfm_howto_options", $wpfm_more_options);
	}
}