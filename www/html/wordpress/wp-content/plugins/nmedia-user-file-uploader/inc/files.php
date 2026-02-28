<?php
/**
** File and directory related functions
** Create directory
** Delete directory
** Upload files
** Delete files
** Get files
*/

if( !defined("ABSPATH") ) die('Not Allowed' );

/**
 * $groups_ids: array of group taxonomy terms
 * */
 
function wpfm_create_post_file( $user_id, $title, $description, $parent_id=0, $groups_ids=null ) {

	$allowed_html = array (
			'a' => array (
					'href' => array (),
					'title' => array () 
			),
			'br' => array (),
			'em' => array (),
			'strong' => array (),
			'p' => array (),
			'ul' => array (),
			'li' => array (),
			'h3' => array () 
		);

	$wpfm_post = array(
			'post_title' 		=> sanitize_text_field($title),
			'post_content' 		=> wp_kses ( $description, $allowed_html ),
			'post_status' 		=> 'publish',	// --connect with action --
			'post_type'			=> 'wpfm-files' , // --connect with action--
			'post_author' 		=> $user_id,
			'comment_status'	=> 'closed',
			'ping_status'		=> 'closed',
			'post_parent' 		=> intval($parent_id),
	);

	$wpfm_post = apply_filters('wpfm_file_post_data', $wpfm_post, $user_id, $parent_id);

	$the_post_id = wp_insert_post( $wpfm_post );
	
	
	$current_user = get_userdata( $user_id );
	update_post_meta($the_post_id, 'author_name', $current_user -> user_login);
	
	
	if( $groups_ids != null ) {
		
		wpfm_set_file_group( $the_post_id, $groups_ids);
	}

	return $the_post_id;
}

function wpfm_create_directory() {
	
	// wp_send_json($_POST);
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	}*/

	$current_user = wpfm_get_current_user();
	if( ! $current_user ) {
		$resp = array('status' => 'error', 
					'message' => __("User object not found", 'wpfm'));

		wp_send_json($resp);
	}
	
	$group_ids = isset($_REQUEST['shortcode_groups']) ? explode(",", sanitize_text_field($_REQUEST['shortcode_groups']) ) : null;
	if($group_ids){
		// removing space
		$group_ids = array_map('trim', $group_ids);
	}
	
	// wp_send_json($group_ids);
	$wpfm_dir_id = wpfm_create_post_file($current_user->ID, 
						sanitize_text_field($_REQUEST['dir_name']),
						sanitize_text_field($_REQUEST['directory_detail']),
						sanitize_text_field($_REQUEST['parent_id']),
						$group_ids);
	
	// the $_REQUEST VARIABLE access our action
	do_action('wpfm_after_directory_post_saved', $wpfm_dir_id, $current_user->ID);
	
	$wpfm_dir = new WPFM_File( $wpfm_dir_id );
	
	$resp = ['new_dir' => $wpfm_dir, 
			'dir_id'=>$wpfm_dir_id, 
			'message'=>__("Directory created successfully", 'wpfm')];
	
	wp_send_json_success($resp);
	
}
/*
 * uploading file here
 */
function wpfm_upload_file() {
	
	// wp_send_json($_REQUEST);
	
	/*if (! wp_verify_nonce ( $_REQUEST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		$response ['status'] = 'error';
		$response ['message'] = __ ( 'Error while uploading file, please contact admin', 'wpfm' );
		wp_send_json($response);
	}*/
	
	header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
	header ( "Cache-Control: no-store, no-cache, must-revalidate" );
	header ( "Cache-Control: post-check=0, pre-check=0", false );
	header ( "Pragma: no-cache" );


	// setting up some variables
	$file_dir_path = wpfm_files_setup_get_directory();
	
	$response = array ();
	if ($file_dir_path == null) {
			
		$response ['status'] = 'error';
		$response ['message'] = __ ( 'Error while creating directory', 'wpfm' );
		die ( 0 );
	}
	
	$file_name = '';
	
	if( isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
		$file_name = sanitize_file_name( $_REQUEST['name'] );
	}elseif( isset($_REQUEST['_file']) && $_REQUEST['_file'] != '') {
		$file_name = sanitize_file_name( $_REQUEST['_file'] );
	}

	// Clean the fileName for security reasons
	// $file_name = preg_replace ( '/[^\w\._]+/', '_', $file_name );
	$file_name = sanitize_file_name($file_name);
	
	$file_name = apply_filters('wpfm_uploaded_filename', $file_name);
	
	/* ========== Invalid File type checking ========== */
	$file_type = wp_check_filetype_and_ext($file_dir_path, $file_name);
	$extension = $file_type['ext'];
	

	// for some files if above function fails to check extension we need to check otherway
	if( ! $extension ) {
		$extension = pathinfo($file_name, PATHINFO_EXTENSION);
	}
	
	$extension = strtolower($extension);
	
	$allowed_types = wpfm_get_option('_file_types');
	if( ! $allowed_types ) {
		$good_types = apply_filters('nm_allowed_file_types', array('jpg', 'png', 'gif', 'zip','pdf') );
	}else {
		$good_types = explode(",", $allowed_types );
	}
	

	if( ! in_array($extension, $good_types ) ){
		$response ['status'] = 'error';
		$response ['message'] = __ ( 'File type not valid', 'nm-filemanager' );
		die ( json_encode($response) );
	}
	/* ========== Invalid File type checking ========== */

	$cleanupTargetDir = true; // Remove old files
	$maxFileAge = 5 * 3600; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit ( 5 * 60 );

	// Uncomment this one to fake upload time
	// usleep(5000);

	// Get parameters
	$chunk = isset ( $_REQUEST ["chunk"] ) ? intval ( $_REQUEST ["chunk"] ) : 0;
	$chunks = isset ( $_REQUEST ["chunks"] ) ? intval ( $_REQUEST ["chunks"] ) : 0;

	

	// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists ( $file_dir_path . $file_name )) {
		$ext = strrpos ( $file_name, '.' );
		$file_name_a = substr ( $file_name, 0, $ext );
		$file_name_b = substr ( $file_name, $ext );
			
		$count = 1;
		while ( file_exists ( $file_dir_path . $file_name_a . '_' . $count . $file_name_b ) )
			$count ++;
			
		$file_name = $file_name_a . '_' . $count . $file_name_b;
	}

	// Remove old temp files
	if ($cleanupTargetDir && is_dir ( $file_dir_path ) && ($dir = opendir ( $file_dir_path ))) {
		while ( ($file = readdir ( $dir )) !== false ) {
			$tmpfilePath = $file_dir_path . $file;

			// Remove temp file if it is older than the max age and is not the current file
			if (preg_match ( '/\.part$/', $file ) && (filemtime ( $tmpfilePath ) < time () - $maxFileAge) && ($tmpfilePath != "{$file_path}.part")) {
				@unlink ( $tmpfilePath );
			}
		}
			
		closedir ( $dir );
	} else
		die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );

	$file_path = $file_dir_path . $file_name;

	// Look for the content type header
	if (isset ( $_SERVER ["HTTP_CONTENT_TYPE"] ))
		$contentType = $_SERVER ["HTTP_CONTENT_TYPE"];

	if (isset ( $_SERVER ["CONTENT_TYPE"] ))
		$contentType = $_SERVER ["CONTENT_TYPE"];
		
	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos ( $contentType, "multipart" ) !== false) {
		if (isset ( $_FILES ['file'] ['tmp_name'] ) && is_uploaded_file ( $_FILES ['file'] ['tmp_name'] )) {
			// Open temp file
			$out = fopen ( "{$file_path}.part", $chunk == 0 ? "wb" : "ab" );
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen ( sanitize_text_field($_FILES ['file'] ['tmp_name']), "rb" );
					
				if ($in) {
					while ( $buff = fread ( $in, 4096 ) )
						fwrite ( $out, $buff );
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
				fclose ( $in );
				fclose ( $out );
				@unlink ( sanitize_text_field($_FILES ['file'] ['tmp_name']) );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		} else
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
	} else {
		// Open temp file
		$out = fopen ( "{$file_path}.part", $chunk == 0 ? "wb" : "ab" );
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen ( "php://input", "rb" );

			if ($in) {
				while ( $buff = fread ( $in, 4096 ) )
					fwrite ( $out, $buff );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );

			fclose ( $in );
			fclose ( $out );
		} else
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
	}

	// Check if file has been uploaded
	if (! $chunks || $chunk == $chunks - 1) {
		// Strip the temp .part suffix off
		rename ( "{$file_path}.part", $file_path );
			
		// making thumb if images
		if(wpfm_is_image($file_name))
		{
			$h = wpfm_get_option('_thumb_size', 150);
			$w = wpfm_get_option('_thumb_size', 150);				
			$thumb_size = array(array('h' => $h, 'w' => $w, 'crop' => false),
			);

			$thumb_size = apply_filters('wpfm_thumb_size', $thumb_size, $file_name);

			// var_dump($file_dir_path);
			// var_dump($file_name);

			$thumb_meta = wpfm_create_thumb($file_dir_path, $file_name, $thumb_size);

			$response = array(
					'file_name'		=> $file_name,
					'thumb_meta'	=> $thumb_meta,
					'status' 		=> 'success',
					'file_groups'	=> wpfm_get_file_groups());
		}else{
			$response = array(
					'file_name'		=> $file_name,
					'file_w'		=> 'na',
					'file_h'		=> 'na',
					'status'		=> 'success',
					'file_groups'	=> wpfm_get_file_groups());
		}
	}
	
	apply_filters( 'wpfm_file_upload_response', $response, $file_name);
		
	wp_send_json($response);
}


/*
 * creating thumb using WideImage Library Since 21 April, 2013
 */
function wpfm_create_thumb($dest, $image_name, $thumb_size) {

	// using wp core image processing editor, 6 May, 2014
	$image = wp_get_image_editor ( $dest . $image_name );
	
	$thumbs_resp = array();
	if( is_array($thumb_size) ){
		
		foreach($thumb_size as $size){
			$thumb_name = $image_name;
			$thumb_dest = $dest . 'thumbs/' . $thumb_name;
			if (! is_wp_error ( $image )) {
				$image->resize ( $size['h'], $size['w'], $size['crop'] );
				$image->save ( $thumb_dest );
				$thumbs_resp[$thumb_name] = array('name' => $thumb_name, 'thumb_size' => getimagesize($thumb_dest) );
			}
		}
	}
	return $thumbs_resp;
}

/** SHOULD BE REMOVED SOON - IT WAS USED IN AWS S3 BUT IT IS NOT REQUIRED SINCE 20.6 **/
/** create image thumb from url **/
function wpfm_create_image_thumb($file_id) {
	
	$wpfm_file = new WPFM_File( $file_id );
	
	$destination_path	= wpfm_get_image_thumb_dir($wpfm_file);
	
	$result = false;
	
	if( wpfm_is_image( $wpfm_file->name ) ) {
		
		// wpfm_pa($wpfm_file);
		if( $wpfm_file->location == 'amazon' && isset($wpfm_file->amazon_data['location']) ) {
			
			$wpfm_url = $wpfm_file->amazon_data['location'];
			$image = imagecreatefromstring( file_get_contents($wpfm_url) );
			
			$height = wpfm_get_option('_thumb_size', 150);
			$width	= wpfm_get_option('_thumb_size', 150);
			
			$height = $height == '' ? 150 : intval($height);
			$width	= $width  == '' ? 150 : intval($width);
			
			// calculate resized ratio
			// Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
			$height = $height === true ? (ImageSY($image) * $width / ImageSX($image)) : $height;
			
			// create image 
			$output = ImageCreateTrueColor($width, $height);
			ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));
			
			// save image
			$result = ImageJPEG($output, $destination_path, 95);
			
		}
	}
	
	return $result;
}



/**
 * return file groups html/select
 * for file upload
 * @since 11.4
 **/
function wpfm_get_file_groups() {
 	
 	$file_groups = get_terms( array(
    'taxonomy' => 'file_groups',
    'hide_empty' => false,
	) );

	return apply_filters('wpfm_file_groups', $file_groups);
}

 /*
 * check if file is image and return true
 */
function wpfm_is_image($file){
	
	$type = strtolower ( substr ( strrchr ( $file, '.' ), 1 ) );
	
	if (($type == "gif") || ($type == "jpeg") || ($type == "png") || ($type == "pjpeg") || ($type == "jpg"))
		return true;
	else 
		return false;
}

function wpfm_file_icon($file){
	

	$type = strtolower ( substr ( strrchr ( $file, '.' ), 1 ) );

	if ( $type != "" ) {
		
		return WPFM_URL."/images/ext/48px/".$type.".png";
	} else {
		return WPFM_URL."/images/file-icon.png";
	}
	
}

/*
 * sending data to admin/others
 */
function wpfm_save_file_data() {

	if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_save_nonce'], 'wpfm_saving_file' )) {
		print 'Sorry, You are not HUMANE.';
		die(0);
	}
	// Setting query var used in 
	set_query_var('wpfm_uploading', true);
	
	$current_user = wpfm_get_current_user();
	
	if( ! $current_user ) {

		$resp ['status'] = 'error';
		$resp ['message'] = __("User object not found", 'wpfm');
		wp_send_json($resp);
	}


	//merging all file title and description in each array
	$all_files_with_data = array();
	$uploaded_files = array_map( function($file_data){
		
		$arr = array( 'br' => array(), 'p' => array(), 'strong' => array() );
		$file_data['file_group']	= isset($file_data['file_group']) ? array_map('sanitize_text_field', $file_data['file_group']) : '';
		$file_data['filename']		= sanitize_text_field($file_data['filename']);
		$file_data['title'] 		= sanitize_title($file_data['title']);
		$file_data['file_details']	= wp_kses($file_data['file_details'], $arr);
		return $file_data;
	}, $_REQUEST['uploaded_files'] );

	
	foreach($uploaded_files as $key => $file){

		$file_group = isset($file['file_group']) ? $file['file_group'] : '';
		$all_files_with_data[$key] = array('filename'	=> $file['filename'],
											'title'		=> $file['title'],
											'description'	=> $file['file_details'],
											'file_group'	=> $file_group,
											);
											
		//if amazon data found
		if( isset($file['amazon']) ){
			$all_files_with_data[$key]['amazon'] = $file['amazon'];
			$all_files_with_data[$key]['dataurl'] = $file['dataurl'];
		}
		
		//if shared_with key exist due to addon
		if( isset($file['shared_with']) ){
			$all_files_with_data[$key]['shared_with'] = $file['shared_with'];
		}

		// groups com with shortcode argumnt
		if (isset($_POST['shortcode_groups']) && $_POST['shortcode_groups'] != '0') {
		
			$shortcode_groups = explode(",", sanitize_text_field($_POST['shortcode_groups']) );
			// removing space
			$shortcode_groups = array_map('trim', $shortcode_groups);
			
			$all_files_with_data[$key]['shortcode_groups'] = $shortcode_groups;
		}
		
		// file meta
		if( isset($file['file_meta']) ){
			$all_files_with_data[$key]['file_meta'] = $file['file_meta'];
		}
		
	}
	
	// checking data without the title
	$without_title = array_filter($all_files_with_data, function($f){
		return $f['title'] == '';
	});
	
	if( $without_title ) {
		
		$resp ['status'] = 'error';
		$resp ['message'] = apply_filters('wpfm_file_data_error_message', 'Title & Detail is required field');
		
		wp_send_json($resp);
	}
	
	// wp_send_json($all_files_with_data);
	
	$all_files_with_data = apply_filters('wpfm_uploaded_files', $all_files_with_data);
	
	$post_id = apply_filters('wpfm_new_post_id', $all_files_with_data, $current_user);
	
	do_action('wpfm_before_all_files_post_save', $all_files_with_data, $current_user, $post_id);
	
	$file_objects = wpfm_save_uploaded_transferred_files( $current_user->ID, $all_files_with_data, $post_id );
	
	$resp ['status'] = 'success';
	$resp ['new_files'] = $file_objects;
	$resp ['message'] = sprintf(__("%s", 'wpfm'), wpfm_get_message_file_saved());
	
	do_action('wpfm_after_all_files_post_save', $file_objects, $current_user);
	
	wp_send_json( $resp );
}


// Saving all files uploaded/transferred by ftp
function wpfm_save_uploaded_transferred_files( $user_id, $wpfm_files, $wpfm_post_id) {
	
	$file_objects = array();

	foreach( $wpfm_files as $key => $file_data){
		$parent_id = isset($_POST['parent_id']) ? intval( $_POST['parent_id'] ) : 0;


		if(is_array($wpfm_post_id) || empty($wpfm_post_id)) {
			
			// var_dump($parent_id);

		$wpfm_post_id = wpfm_create_post_file($user_id, 
											$file_data['title'], 
											$file_data['description'],
											$parent_id
											);
		}
	
		do_action('wpfm_after_file_post_save', $wpfm_post_id, $file_data, $user_id);
		
		$file_objects[] = array('id' => $wpfm_post_id,
							'title' => $file_data['title'], 
							'filename' => $file_data['filename'],
							'file_obj'	=> new WPFM_File( $wpfm_post_id ),
							'file_id'	=> $key,
							);
							
							
		// When file revision addon used, existing file id used and reset here.
		$wpfm_post_id ="";
	}
	
	return $file_objects;
}


function wpfm_get_user_files() {

	
	$send_json = isset($_POST['send_json']) ? true : false;
	$user_id  = get_current_user_id();

	if(wpfm_get_option('_allow_admin_see_all_files') == 'yes' && class_exists('WPFM_PRO')){
		$current_user = wpfm_get_current_user();
		if(in_array( 'administrator', $current_user->roles )){
			$wpfm_files = wpfm_get_wp_files(0);
		}else{
			$wpfm_files = wpfm_get_wp_files(0, $user_id);
		}
	}elseif(wpfm_get_option('_allow_each_user_see_files') == 'yes' && class_exists('WPFM_PRO')){
			$wpfm_files = wpfm_get_wp_files(0);
	}else{
		$wpfm_files = wpfm_get_wp_files(0, $user_id);
	}
	
	
	$files = array();
	foreach($wpfm_files as $file) {
		
		$file_obj = new WPFM_File($file->ID);
		// wpfm_pa($file_obj);
		if( $file_obj->path || $file_obj->location == 'amazon' || $file_obj->node_type == 'dir') {
		    
		    $files[] = $file_obj;
		}
	}
	
	// wpfm_pa($files);
	
	if( $send_json ) {
		
		return wp_send_json($files);
	} else {
		
		return $files;
	}

}


// Get files from wp post
function wpfm_get_wp_files( $parent_id = 0, $user_id=null) {
	
	$pagination_limit = wpfm_get_option ( '_pagination_limit' );
	
	if($user_id != 0){
		$user_id = $user_id;
	}

	if($pagination_limit !=0) {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$wpfm_args = array(
            'orderby'       => wpfm_get_sort_by(),
            'order'         => wpfm_get_sort_order(),
            'post_type'     => 'wpfm-files',
            'post_status'   => 'publish',
            'nopaging'      => false,
            'post_parent'   => $parent_id,
            'posts_per_page' => $pagination_limit,
			'paged'          => $paged,
			'author' => $user_id
			
    	);
	}else{
		$wpfm_args = array(
            'orderby'       => wpfm_get_sort_by(),
            'order'         => wpfm_get_sort_order(),
            'post_type'     => 'wpfm-files',
            'post_status'   => 'publish',
            'nopaging'      => true,
            'post_parent'   => $parent_id,
            'author' => $user_id
    	);
	}
    
    $wpfm_args = apply_filters('wpfm_wp_files_query', $wpfm_args, $parent_id);
	
    $post_files = get_posts($wpfm_args);
   
    return apply_filters('wpfm_wp_files', $post_files);
}

function wpfm_get_wp_files_count( $user_id ) {

		$parent_id = 0;
		$wpfm_args = array(
            'orderby'       => wpfm_get_sort_by(),
            'order'         => wpfm_get_sort_order(),
            'post_type'     => 'wpfm-files',
            'post_status'   => 'publish',
            'author'        => $user_id,
            'nopaging'      => true,
            'post_parent'   => $parent_id,
 
    	);
 
    $post_files = get_posts($wpfm_args);
    $total_user_files = 0;
    foreach($post_files as $file){
    	$file_name = wpfm_get_attachment_file_name( $file->ID );
    	$file_path = wpfm_files_setup_get_directory();
		if( file_exists($file_path.$file_name)){
			$total_user_files++;
		}
    }

    return $total_user_files;
}

function wpfm_get_date_format() {

	return '';
}

function wpfm_get_sort_by() {
	
	$file_sortby = (isset($_REQUEST['sortby'])) ? sanitize_text_field($_REQUEST['sortby']) : 'title' ;

	return apply_filters('wpfm_sort_by', $file_sortby);
}

function wpfm_get_sort_order() {
	
	$file_order = (isset($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : 'ASC' ;

	return apply_filters('wpfm_sort_order', strtolower($file_order) );
}

// file reques type 1. wpfm_shared, 2. wpfm_group, 3. wpfm_bp
function wpfm_get_file_request_type() {
	
	$request_type = '';
	
	$group_id = get_query_var('group_id');
	$wpfm_bp_group_id = get_query_var('wpfm_bp_group_id');
	
	if( ! empty($_REQUEST['file-src']) && $_REQUEST['file-src'] == 'shared' ) {
		
		$request_type = 'wpfm_shared';
	} elseif( ! empty($group_id) ) {
		$request_type = 'wpfm_group';
	} elseif( ! empty($wpfm_bp_group_id) && wpfm_is_bp_group_public($wpfm_bp_group_id)) {
		
		$request_type = 'wpfm_bp';
	}
	
	return apply_filters('wpfm_file_request_type', $request_type, $_REQUEST);
}


/**
 * get user used file size functions
 */
function wpfm_get_user_files_size($user_id){

	$total_file_size = 0;
	$args = array(
		'post_type'        => 'wpfm-files',
		'post_status'      => 'publish',
		'nopaging'		   => true,
		'author'           => $user_id,
	);

	$user_files = new WP_Query($args);
	
	//filemanager_pa($user_files);
	while ( $user_files -> have_posts() ) {
		$user_files -> the_post();
		$file_name = wpfm_get_attachment_file_name( get_the_ID() );
		$file_path = wpfm_files_setup_get_directory();

		if( file_exists($file_path.$file_name))
			$total_file_size+= filesize( $file_path.$file_name );
	}
	
	wp_reset_query();
	
	
	
	return $total_file_size;
}

/*
 * deleting file/directories with sub directories and files.
 */
function wpfm_delete_file() {
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("File cannot be deleted, please contact admin", "wpfm"));
	}*/
	
	$allow_guest = wpfm_get_option('_allow_guest_upload') == 'yes' ? true : false;
	if( !$allow_guest && ! wpfm_is_current_user_post_author($_POST['file_id'] )) {
		wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	}
	
	//check if it has attachment
	$file_id = intval($_REQUEST['file_id']);
	$curent_user = wpfm_get_current_user();
	
	// $a = getChildren($file_id);
	// wpfm_pa($a);
	// return;
	$file = new WPFM_File($file_id);
	
	$response = array();
  	if( $file->delete_file() ){
		  	
		$message = sprintf(__('%s Successfully Removed', 'wpfm'), $file->title);
		update_user_meta( get_current_user_id(), 'wpfm_total_filesize_used', wpfm_get_user_files_size(get_current_user_id()) );
		
		// Now deleting post
        wp_delete_post($file_id, true);
        
        $response = ['message' => $message];
        
		wp_send_json_success($response);
	} else {
		
		$message = sprintf(__('Error while deleting %s, Try again.', 'wpfm'), $file->title);
		wp_send_json_error($message);
	}

}


function getChildren($parent_id, &$tree_string=array()) {
    $tree = array();
    // getOneLevel() returns a one-dimensional array of child ids        
    $tree = new WPFM_File($parent_id);     
	// var_dump($tree->children);
    if(count($tree->children>0)){      
        $tree_string=array_merge($tree_string,$tree->children);
    }
    // var_dump($tree_string);
    foreach ($tree->children() as $child) {
        $this->getChildren($child->id, $tree_string);
    }
    
    return $tree_string;
}

/*
 * deleting multiple files/multiple directories with sub directories and files.
 */
function wpfm_delete_multiple_files() {
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("File cannot be deleted, please contact admin", "wpfm"));
	}*/
	
	//check if it has attachment
	$file_ids = isset($_REQUEST['file_ids']) ? $_REQUEST['file_ids'] : array();
	$curent_user = wpfm_get_current_user();
	// wpfm_pa($file_ids);
	// exit;
	$response = array();
	$error_message = '';
	foreach($file_ids as $file_id){
		
		$file = new WPFM_File($file_id);
		
		$files_success = 0;
		$files_error = 0;
	  	
	  	if( $file->delete_file() ){
			  	
			$message = sprintf(__('%s Successfully Removed', 'wpfm'), $file->title);
			update_user_meta( get_current_user_id(), 'wpfm_total_filesize_used', wpfm_get_user_files_size(get_current_user_id()) );
			
			// Now deleting post
	        wp_delete_post($file_id, true);
	        
	        $files_success++;
	    	// wp_send_json_success($response);
		} else {
			$files_error++;
		}
	}
	
	$message = sprintf(__("%d File(s) removed successfully.\n\n", 'wpfm'), $files_success);
	if( $files_error ) {
		$message .= sprintf(__('%d File(s) cannot be removed, please try again.', 'wpfm'), $files_error);
	}
	
	$response = ['message' => $message, 'user_files' => wpfm_get_user_files()];
	
	if (!empty($response)) {
		wp_send_json_success($response);
	}else{
		wp_send_json_error($error_message);
	}

}

function wpfm_extrac_group_from_shortcode( $atts ){
	
	extract ( shortcode_atts ( array ('group_id'  => 0), $atts ) );
	return	apply_filters( 'wpfm_get_file_group_id', $group_id);
}

function wpfm_extract_bp_group_from_shortcode( $atts ){
	extract ( shortcode_atts ( array ('wpfm_bp_group_id'  => 0), $atts ) );
	return	apply_filters( 'wpfm_get_file_wpfm_bp_group_id', $wpfm_bp_group_id);
}

// Set file's group id
function wpfm_set_file_group( $file_id, $group_ids ) {

	// setting terms id as int as required by wp
	$groups_ids = array_map('intval', $group_ids);
	wp_set_object_terms( $file_id , $groups_ids,'file_groups');
}

function wpfm_file_meta_update() {
	
	/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
		wp_send_json_error(__("Sorry, this request cannot be completed contact admin", "wpfm"));
	}*/
	
	$allow_guest = wpfm_get_option('_allow_guest_upload') == 'yes' ? true : false;
	if( !$allow_guest && ! wpfm_is_current_user_post_author($_POST['file_id'] )) {
		wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	}

	$file_id = isset($_REQUEST['file_id']) ? intval($_REQUEST['file_id']) : '' ;
	// we have meta fiels array with action and field_id
	// we remove file_id and action key form meta array
	unset($_REQUEST['action']);
	unset($_REQUEST['file_id']);
	unset($_REQUEST['wpfm_dir_path']);
	unset($_REQUEST['wpfm_ajax_nonce']);

	// now we have pure meta fields array
	$meta_fields = $_REQUEST;
	// wp_send_json_success($meta_fields);

	if ($file_id != '') {
		
		foreach ($meta_fields as $meta_key => $meta_value) {
			$meta_value = is_array($meta_value) ? array_map('sanitize_text_field', $meta_value) : sanitize_text_field($meta_value);
			update_post_meta( $file_id, sanitize_key($meta_key),  $meta_value);
		}
		wp_send_json_success(__("File meta saved successfully", "wpfm"));
	}else{
		wp_send_json_error(__("Error while saving the file meta.", "wpfm"));
	}
}

// Download file
function wpfm_file_download() {
	
	if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'wpfm_download' && !$_REQUEST['file_id'] == '') {
			
		$retrieved_nonce = $_REQUEST['nm_file_nonce'];
		$file = new WPFM_File( $_REQUEST['file_id'] );
		
		// When a file is shared by email, a file_hash will be generated
		$hash_found = isset($_REQUEST['file_hash']) ? $_REQUEST['file_hash'] : '' ;
		
		if( !isset($_REQUEST['nm_file_by_email']) && ! $file->file_hash_matched($hash_found)){
			if (!wp_verify_nonce($retrieved_nonce, 'securing_file_download' ) ) 
				wp_die( 'Sorry, you are not allow to download this file', 'wpfm' );
		}
		
		$file_dir_path = $file->path;
		
		if($file->location == 'amazon' && wpfm_is_amazon_addon_enable()) {
		
			$amazon_url = WPFM_AMAZON()->build_amazon_file_url( $file->id );
			
			$link_html = '<a class="button button-primary"';
	        $link_html .= ' data-id="'.esc_attr($file->id).'"';
	        $link_html .= ' title="'.__('Download','wpfm').'"';
	        $link_html .= ' href="'.esc_url($amazon_url).'">';
	        $link_html .= '<span class="dashicons dashicons-download"></span>';
	        $link_html .= '<span class="wpfm-amazon-download-url"></span>';
	        $link_html .= __("Download", 'wpfm');
        	$link_html .= '</a>';
        	
        	wp_die($link_html, "Download {$file->title}");
		}
		
		if (file_exists($file_dir_path)) {
			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($file_dir_path));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file_dir_path));
		    ob_clean();		
			
			@ob_end_flush();
			flush();
			
			$fileDescriptor = fopen($file_dir_path, 'rb');
			
			while ($chunk = fread($fileDescriptor, 8192)) {
			    echo $chunk;
			    @ob_end_flush();
			    flush();
			}
			
			fclose($fileDescriptor);
			$total_downloads = $file->total_downloads + 1;
			
			$file->set_meta('wpfm_total_downloads', $total_downloads);
			
			// Action hook
			do_action('wpfm_after_file_download', $_REQUEST);
			exit;
		}else{
		
			die( printf(__('no file found at %s', 'nm-filemanager'), $file_dir_path) );
		}
		
	}
}


function wpfm_digital_file_download() {
	if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'wpfm_add_to_cart' && !$_REQUEST['file_id'] == '') {
			
		$retrieved_nonce = $_REQUEST['nm_cart_file_nonce'];
		$file = new WPFM_File( $_REQUEST['file_id'] );
		
		// When a file is shared by email, a file_hash will be generated
		$hash_found = isset($_REQUEST['file_hash']) ? $_REQUEST['file_hash'] : '' ;
		
		if( !isset($_REQUEST['nm_file_by_email']) && ! $file->file_hash_matched($hash_found)){
			if (!wp_verify_nonce($retrieved_nonce, 'securing_cart_file' ) ) 
				wp_die( 'Sorry, you are not allow add to cart.', 'wpfm' );
		}
		$product_id = NMEDD()->eddw_get_product();
		// var_dump($product_id);
		// var_dump($file);
		// global $woocommerce;
		// $woocommerce->cart->add_to_cart( $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array() );
		
	}
}

// Get image thumb dir path
function wpfm_get_image_thumb_dir($file) {
	
	$file_dir_path		= wpfm_files_setup_get_directory(null, 'root', $file->id);
	
	$wpfm_thumb_dir		= "{$file_dir_path}thumbs/".$file->name;
	return apply_filters('wpfm_thumb_dir_path', $wpfm_thumb_dir, $file);
}

// If the current user is post author
function wpfm_is_current_user_post_author($post_id){
	
	global $current_user;
    get_currentuserinfo();
    
    $post = get_post($post_id);
    
    $return = false;
    if (is_user_logged_in() && $current_user->ID == $post->post_author)  {
        $return = true;
    }
    
    return $return;
}