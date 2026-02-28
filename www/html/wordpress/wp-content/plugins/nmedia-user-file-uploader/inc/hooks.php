<?php 

 if( ! defined('ABSPATH' ) ){
	exit;
}

function wpfm_hooks_do_callbacks() {
	/*
	** wpfm_array_get_ajax_callbacks() get array of calbacks
	** in inc/arrays.php
	*/
	$ajax_callbacks = wpfm_array_get_ajax_callbacks();

	/*
	** all calback function in inc/calbacks-functions.php
	*/
	foreach ($ajax_callbacks  as $callback => $viewer){
		add_action( 'wp_ajax_'.$callback, $callback );
		// if logged in user can see
		if( $viewer )
			add_action( 'wp_ajax_nopriv_'.$callback, $callback );
	}
}

// Update wpfm_get_wp_file query
function wpfm_hooks_update_query( $query, $parent_id ) {
	
	$request_type = wpfm_get_file_request_type();
	
	switch( $request_type ) {
		
		case 'wpfm_shared':
			// unset post_parent
			unset( $query['post_parent'] );
			unset( $query['author'] );
			$query['meta_query'] = ['relation' => 'OR', ['key'=>'shared_with'], ['key'=>'shared_with_role']];
			$query['nopaging'] = true;
			break;
			
		case 'wpfm_group':
			
			$group_id = get_query_var('group_id');
			$query['tax_query'] = array(
											array(
												'taxonomy' => 'file_groups',
												'field'    => 'id',
												'terms'    => explode(',', $group_id),
												'operator' => 'IN',
											),
								);
			break;
		case 'wpfm_bp':
			// unset( $query['post_parent'] );
			unset( $query['author'] );
			$query['meta_query'] = array( array('key' => 'wpfm_bp_group_id',
														'value'   => get_query_var('wpfm_bp_group_id'),
														'compare' => '=')
												);
			break;
			
		default:
			
			// if (class_exists('WPFM_PRO')) {
			// 	$current_user = wpfm_get_current_user();
			// 	if(wpfm_get_option('_allow_each_user_see_files') != 'yes' || in_array( 'administrator', $current_user->roles )){
					
			// 		$query['author'] = $current_user->ID;
			// 		break;
			// 	}	
			// }
			
			
	}
	
	// Amazmon is not active then hide all Amazom files
	// if( ! wpfm_is_amazon_addon_enable() ) {
	// 	$query['meta_query'] = array(
	// 							array('key'	=> 'wpfm_file_location',
	// 								'value'	=> 'amazon',
	// 								'compare' => '!=')
	// 							);
	// }
		
	// wpfm_pa($query);
	return $query;
}

function wpfm_hooks_after_dir_saved( $wpfm_dir_id, $user_id ) {
	
	update_post_meta($wpfm_dir_id, 'wpfm_node_type', 'dir');
	
	/* sharing file in buddypress group if set to share */
	if (isset($_REQUEST['wpfm_bp_group_id']) && wpfm_is_bp_group_public($_REQUEST['wpfm_bp_group_id']) ){
		update_post_meta($wpfm_dir_id, 'wpfm_bp_group_id', intval($_REQUEST['wpfm_bp_group_id']));
	}
}

function wpfm_hooks_after_file_saved( $wpfm_post_id, $file_data, $user_id ) {
	
	// wpfm_pa($file_data);
	// exit;

	$filename = $file_data['filename'];
	
	// Helping metas
	$file_dir_path	= wpfm_files_setup_get_directory($user_id).$filename;
	$file_dir_path 	= wp_slash($file_dir_path);
	$file_url		= wpfm_get_file_dir_url($user_id,false,$wpfm_post_id).$filename;
	
	update_post_meta($wpfm_post_id, 'wpfm_file_name', $filename);
	update_post_meta($wpfm_post_id, 'wpfm_node_type', 'file');
	update_post_meta($wpfm_post_id, 'wpfm_title', get_the_title($wpfm_post_id));
	update_post_meta($wpfm_post_id, 'wpfm_discription', get_the_content());
	update_post_meta($wpfm_post_id, 'wpfm_file_parent', wp_get_post_parent_id($wpfm_post_id));
	update_post_meta($wpfm_post_id, 'wpfm_dir_path', $file_dir_path);
	update_post_meta($wpfm_post_id, 'wpfm_file_url', $file_url);
	// update_post_meta($wpfm_post_id, 'wpfm_is_dir', 'false');
	update_post_meta($wpfm_post_id, 'wpfm_date_created', get_the_date(wpfm_get_date_format(), $wpfm_post_id));
	
	
	$is_image = wpfm_is_image( $filename ) ? 'yes' : 'no';
	update_post_meta($wpfm_post_id, 'wpfm_is_image', $is_image);
	if( wpfm_is_image( $filename ) ) {
		// noticed that imge with canpital type (PNG) has thumbs in lower case so here is fix:
		$filename_new = preg_replace_callback('/\.\w+$/', function($m){
		   return strtolower($m[0]);
		}, $filename);
		$file_thumb_url		= wpfm_get_file_dir_url($user_id, true,$wpfm_post_id).$filename_new;
		update_post_meta($wpfm_post_id, 'wpfm_file_thumb_url', $file_thumb_url);
	}
	

    //filesize
   $file_size = '--';
    if( file_exists( $file_dir_path )) {
        $file_size = size_format( filesize( $file_dir_path ));
    }

	update_post_meta($wpfm_post_id, 'wpfm_file_size', $file_size);

	update_post_meta($wpfm_post_id, 'wpfm_total_downloads', 0);
	
	/**
	 * setting file group if selected after uploading
	 * @since 11.4
	**/
	if( isset($file_data['file_group']) && !empty($file_data['file_group'] ) ) {
		
		$file_groups = $file_data['file_group'];
		if( isset($file_data['shortcode_groups']) ) {
			$file_groups = array_merge($file_groups, $file_data['shortcode_groups']);
		}
		
		wpfm_set_file_group( $wpfm_post_id, $file_groups );
	} elseif( isset($file_data['shortcode_groups']) && !empty($file_data['shortcode_groups'] ) ) {
		
		wpfm_set_file_group( $wpfm_post_id, $file_data['shortcode_groups']);
	}
	
	
	// Saving file meta since version 20.0
	if( isset($file_data['file_meta']) ) {
		foreach($file_data['file_meta'] as $meta_key => $meta_value){
			update_post_meta( $wpfm_post_id, sanitize_key($meta_key), sanitize_text_field($meta_value) );
		}
	}

	/**
	**************** If Share File Add on enabled *****************************
	*/
	if( isset($file_data['shared_with']) && !empty($file_data['shared_with'] ) ) {
		
		$shared_with = explode(",", $file_data['shared_with']);
		update_post_meta($wpfm_post_id, 'shared_with', $shared_with );
		
		foreach($shared_with as $user_id) {
            update_user_meta($user_id, 'wpfm_share_alert', 1);
        }
	}

	// adding taxonomy to file.explode(',', $this->group_id)
	if (isset($_POST['file_term_id']) && $_POST['file_term_id'] !== '0'){
		$file_group_ids = sanitize_text_field($_POST['file_term_id']);
		$myar = explode(',', $file_group_ids); //array('3');
		$terms = array_map('intval', $myar );
		wp_set_object_terms( $wpfm_post_id , $terms, 'file_groups');
	}

	/* sharing file in buddypress group if set to share */
	if (isset($_REQUEST['wpfm_bp_group_id']) && wpfm_is_bp_group_public($_REQUEST['wpfm_bp_group_id']) ){
		update_post_meta($wpfm_post_id, 'wpfm_bp_group_id', intval($_REQUEST['wpfm_bp_group_id']));
	}


	$post_attachment_url = '';
	$post_attachment_path = '';
	$wpfm_file_location	= 'local';

	if ( wpfm_is_amazon_addon_enable()	&& isset($file_data['amazon']) ) {
		
		$wpfm_file_location = 'amazon';
		
		update_post_meta($wpfm_post_id, 'wpfm_amazon_data', $file_data['amazon']);
		$post_attachment_url = $wpfm_file_location;
		
	}else{
	
		$post_attachment_url 	= wpfm_get_file_dir_url($user_id,false,$wpfm_post_id) . $file_data['filename'];
		$post_attachment_path 	= wpfm_files_setup_get_directory() . $file_data['filename'];
	}


	if( file_exists($post_attachment_path) ){
		
		include_once( ABSPATH . 'wp-admin/includes/image.php' );
			
		$wp_filetype = wp_check_filetype(basename( $post_attachment_url ), null );
	
		$attachment = array(
				'guid' => $post_attachment_url,
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => basename($post_attachment_url),
				'post_content' => '',
				'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment($attachment, $post_attachment_url, $wpfm_post_id);
		
		wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata($attach_id, $post_attachment_path ));
	}

	//setting a post meta for file location 1. local or 2. amazon
	update_post_meta($wpfm_post_id, 'wpfm_file_location', $wpfm_file_location);
	
	$admin_message = (wpfm_get_option ( '_file_saved' ) == '' ? 'File saved' : wpfm_get_option ( '_file_saved' ));
	update_user_meta( $user_id, 'wpfm_total_filesize_used', wpfm_get_user_files_size($user_id) );
	
	// file upload sucess full
}

function wpfm_user_upload_files_counter($file_ojbects, $user) {
	
	$file_counter = 0;
	$file_counter = get_user_meta($user->ID, 'wpfm_file_upload_limit',true);
	
	$file_counter = intval($file_counter) + count($file_ojbects);
	
	update_user_meta($user->ID, 'wpfm_file_upload_limit', $file_counter);
	
}

// Send notification to admin when all files are saved
function wpfm_hooks_send_notification( $file_ojbects, $user ) {
	
	if( empty( $file_ojbects) ) return '';

	
	$send_notification = wpfm_get_option('_file_notification');
	if( $send_notification != 'yes' ) return '';
	
	$context = 'file-saved';
	$email = new WPFM_Email($file_ojbects, $context);
	
	$message = '';
	$message	.= wpfm_get_option('_email_message_notification');
	$file_ids = array();
	
	foreach($file_ojbects as $file) {
		
			$file_ids[] = $file['id'];
	}
	
	// If message is empty then use default
	if( empty($message) ) {
		$message	.= '<h3>'.__('Files Uploaded', 'wpfm').'</h3>';
	
		$message	.= '<dl>';
		foreach($file_ojbects as $file) {
			
			$file_obj = $file['file_obj'];
			
			$message	.= '<dt>';
			$message	.= sprintf(__('<a href="%s">Download %s</a>','wpfm'), esc_url( $file_obj->download_url ), $file_obj->title );
			$message	.= '</dt>';
		}
		$message	.= '</dl>';
	}
	
	$message	= nl2br($message);
	
	
	// Recipeints
	$notification_recipients = wpfm_get_option('_email_recipients');

	$notification_recipients = ($notification_recipients == '' ? get_bloginfo('admin_email') : explode(',', $notification_recipients));
	
	if(is_array($notification_recipients )){
			// removing space if any
		$notification_recipients = array_map('trim', $notification_recipients);
	}
	
	$subject = sprintf(_n("%d File uploaded by %s", "%d Files uploaded by %s", count($file_ids), 'wpfm'), count($file_ids), $user->user_login );

	$email_recipients_subject = wpfm_get_option('_email_recipients_subject');
	if(!empty($email_recipients_subject)){
		
		$subject = $email_recipients_subject;
	}
	
	
	$email->to		= $notification_recipients;
	$email->subject = $subject;
	$email->message	= $message;
	
	// send
	$email->send();
}

// Rename file if settings allow
function wpfm_hook_rename_file( $file_name ) {
	
	$rename_with = apply_filters('wpfm_prefix_filename', time());
	
  	$do_rename = wpfm_get_option('_file_rename');
  	
  	if( $do_rename == 'yes' ) {
  		
  		$file_name = $rename_with . '_' . $file_name;
  	}
  	
  	return $file_name;
}

// Logout link in navbar
function wpfm_hooks_logout_link_nav_bar( $nav_items ) {
	
	if( wpfm_get_option('_show_logout_button') == 'yes' ) {
		
		$nav_items[] = array('icon' => 'glyphicon-off',
	                        'label' => __(' Logout', 'wpfm'),
	                        'link'  => wp_logout_url(),
	                        );
	}
                        
	return $nav_items;                        
}


function wpfm_hooks_register_template ($templates) {
	$templates['wpfm-page.php'] = __('File Manager','wpfm');
	return $templates;
}

function wpfm_hooks_load_page_template ($template) {
	if ('wpfm-page.php' == basename ($template))
	    $template = WPFM_PATH . '/page/index.php';
	// var_dump($template); exit;
	return $template;
}