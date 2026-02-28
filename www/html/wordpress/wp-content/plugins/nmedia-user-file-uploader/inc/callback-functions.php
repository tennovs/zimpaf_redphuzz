<?php

 if( ! defined('ABSPATH' ) ){
	exit;
}


/**
 * 
 * move file/directory into other directory
 * 
 * @since 11.6
 **/
	
function nm_uploadfile_move_file() {
	
	// if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
	// 	wp_send_json_error(__("Sorry, this request cannot be completed contact admin", "wpfm"));
	// }
	
	$allow_guest = wpfm_get_option('_allow_guest_upload') == 'yes' ? true : false;
	if( !$allow_guest && ! wpfm_is_current_user_post_author($_POST['file_id'] )) {
		wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	}
	
	$dir_id = intval( $_REQUEST['parent_id'] );
	$file_id = intval( $_REQUEST['file_id'] );

	if (isset($_REQUEST)) {

	    $result  = array(
	        'ID' => $file_id, 
	        'post_parent' => $dir_id 
	    );

	    $post_id = wp_update_post( $result, true );
	}
	
	$wpfm_dir = new WPFM_File( $dir_id );
	
	if($result){
		$message = __('File is move successfully', 'wpfm');
		$response = ['message' => $message, 'updated_dir' => $wpfm_dir];
		wp_send_json_success($response);
	} else {
		$message = __('Error while moveing file, please try again.', 'wpfm');
		$response = ['message' => $message, 'user_files' => wpfm_get_user_files()];
		wp_send_json_error($response);
	}
	
}

/*
 * Edit file title and description
 */
function wpfm_edit_file_title_desc(){
	
	// if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
	// 	wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	// }
	
	if( ! wpfm_is_current_user_post_author($_POST['file_id'] )) {
		wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	}
	
	$allowed_html = [
        'a'      => [
            'href'  => [],
            'title' => [],
        ],
        'br'     => [],
        'em'     => [],
        'strong' => [],
    ];
	
	$id 		= isset($_POST['file_id']) ? intval($_POST['file_id']) : '';
	$title 		= isset($_POST['file_title']) ? sanitize_text_field($_POST['file_title']) : '';
	$content	= wp_kses( $_POST['file_content'], $allowed_html );
	
	$file = array(
		'ID'           => $id,
		'post_title'   => $title,
		'post_content' => $content,
	);
	
	$post_id = wp_update_post( $file, true );
	// Update the post into the database
	if( $post_id != 0 ) {
		update_post_meta( $post_id,'wpfm_title', $title);
		$wpfm_file = new WPFM_File( $post_id );
		$response = ['message'=>__("File updated successfully.", "wpfm"),'file'=>$wpfm_file];
		wp_send_json_success($response);
	}
}


// sending file in email
function wpfm_send_file_in_email() {
	
	// if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['wpfm_ajax_nonce'], 'wpfm_securing_ajax' )) {
	// 	wp_send_json_error(__("Sorry, not allowed", "wpfm"));
	// }
	
	$file_id = isset($_REQUEST['file_id']) ? intval($_REQUEST['file_id']) : '';
	
	$file = new WPFM_File($file_id);
	
	if( empty($_POST['emailaddress']) ) {
		
		wp_send_json_error( __('Recipient email not given.','wpfm') );
	}
	
	$subject	= sprintf(__("%s is shared with you", "wpfm"), $file->title);
	
    $file_hash = $file->add_file_hash();
    
    $download_url	= add_query_arg('file_hash',$file_hash, $file->download_url);
    
	$message		= sprintf(__('<a href="%s">Download %s</a>','wpfm'), esc_url($download_url), $file->title );
	
	if( isset($_POST['message']) ) {
		$sender_message	= "<br><br>";
		$sender_message	.= "Message from sender:<br>";
		$sender_message	.= sanitize_text_field($_POST['message']);
		$message	.= sprintf(__("%s","wpfm"), $sender_message);
	}
	
	$context = 'send-file';
	$email = new WPFM_Email($file_id, $context);
	$email->to		= sanitize_email($_POST['emailaddress']);
	$email->subject = apply_filters('wpfm_file_email_subject', $subject, $file);
	$email->message	= $message;
	
	// send
	$email->send();
	
	wp_send_json_success( __('File is shared successfully','wpfm') );
}