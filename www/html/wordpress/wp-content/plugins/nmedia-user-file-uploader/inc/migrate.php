<?php
/**
** migrating old version files to new version
*/

if( !defined("ABSPATH") ) die('Not Allowed' );

// Getting old files
function wpfm_migrate() {

	$post_parent = 0;
	
	$wpfm_args = array(
            'orderby'       => wpfm_get_sort_by(),
            'order'         => wpfm_get_sort_order(),
            'post_type'     => 'nm-userfiles',
            'post_status'   => 'publish',
            'post_parent'	=> $post_parent,
            'nopaging'      => true,
            'meta_key'		=> 'wpfm_migrated',
            'meta_value'		=> 'yes',
            'meta_compare'	=> 'NOT EXISTS',
    );
    
 	$old_files = get_posts($wpfm_args);
    // wpfm_pa($old_files); exit;
    
    // Add meta data
	wpfm_add_meta_data();
	
	if( count($old_files) == 0 ) return '';
	
	wpfm_start_migration( $old_files, $post_parent );

}

function wpfm_start_migration( $old_files, $post_parent ) {
	
	$new_files = array();
	foreach($old_files as $file) {
		
		// changing post type
		// $changed_type = set_post_type( $file->ID, 'wpfm-files' );
		// if( $changed_type )
		// 	$new_files[] = $file->ID;
		
		$file_owner 	= $file->post_author;
		$title			= $file->post_title;
		$description	= $file->post_content;
		// $parent_id		= $file->post_parent;
		
		$wpfm_post_id = wpfm_create_post_file( $file_owner, $title, $description, $post_parent );
		
		$file_obj = new WPFM_File($file->ID);
		
		$children = wpfm_get_old_children($file_obj);
		// wpfm_pa($children); exit;
		if( !empty($children) ) {
			
			wpfm_start_migration($children, $wpfm_post_id);
		}
		
		
		update_post_meta($wpfm_post_id, 'wpfm_file_name', $file_obj->name);
		update_post_meta($wpfm_post_id, 'wpfm_node_type', $file_obj->node_type);
		update_post_meta($wpfm_post_id, 'wpfm_title', $file_obj->title);
		update_post_meta($wpfm_post_id, 'wpfm_discription', $file_obj->description);
		update_post_meta($wpfm_post_id, 'wpfm_file_parent', $post_parent);
		update_post_meta($wpfm_post_id, 'wpfm_dir_path', $file_obj->path);
		update_post_meta($wpfm_post_id, 'wpfm_file_url', $file_obj->url);
		// update_post_meta($wpfm_post_id, 'wpfm_is_dir', 'false');
		update_post_meta($wpfm_post_id, 'wpfm_date_created', $file_obj->created_on);
		update_post_meta($wpfm_post_id, 'wpfm_file_size', $file_obj->size);
		update_post_meta($wpfm_post_id, 'wpfm_total_downloads', $file_obj->total_downloads);
		
		$is_image = wpfm_is_image( $file_obj->name ) ? 'yes' : 'no';
		update_post_meta($wpfm_post_id, 'wpfm_is_image', $is_image);
		if( $is_image ) {
			$file_thumb_url		= wpfm_get_file_dir_url($user_id, true,$wpfm_post_id).$file_obj->name;
			update_post_meta($wpfm_post_id, 'wpfm_file_thumb_url', $file_thumb_url);
			
		}
		
		// adding file meta if found
		$old_meta_data = get_option('filemanager_meta', true);
		if( $old_meta_data ) {
			
			foreach($old_meta_data as $old_meta) {
		
				$data_name	= isset($old_meta['data_name']) ? $old_meta['data_name'] : '';
				$value = get_post_meta($file->ID, $data_name, true);
				update_post_meta($wpfm_post_id, $data_name, $value);
			}
		}
		
		// Set flag for migration
		update_post_meta($file->ID, 'wpfm_migrated', 'yes');
			
	}
	
}

function wpfm_add_meta_data() {
	
	if( get_option('wpfm_fields_migrated') == 'yes' ) return ;
	
	
	$old_meta_data = get_option('filemanager_meta');
	// wpfm_pa($old_meta_data); exit;
	
	if( !$old_meta_data ) return;
	
	$new_meta_data = array();
	foreach($old_meta_data as $old_meta) {
		
		$temp_meta = array();
		$type		= $old_meta['type'];
		$title		= isset($old_meta['title']) ? $old_meta['title'] : '';
		$data_name	= isset($old_meta['data_name']) ? $old_meta['data_name'] : '';
		$required	= isset($old_meta['required']) ? $old_meta['required'] : '';
		$class		= isset($old_meta['class']) ? $old_meta['class'] : '';
		$description= isset($old_meta['description']) ? $old_meta['description'] : '';
		$options	= isset($old_meta['options']) ? explode("\n", $old_meta['options']) : '';
		$selected	= isset($old_meta['selected']) ? $old_meta['selected'] : '';
		
		
		$temp_meta[$type] = array(	'title'		=> $title,
									'data_name'	=> $data_name,
									'required'	=> $required,
									'class'		=> $class,
									'desc'		=> $description,
									'permission'=> 'everyone',
									'placeholder'=>'',
							);
							
		if( $options ) {
			
			$temp_meta[$type]['options'] = $options;
			$temp_meta[$type]['select_option'] = $selected;
		}
		
		$new_meta_data[] = $temp_meta;
	}
	
	update_option ( WPFM_SHORT_NAME . '_file_meta', $new_meta_data );
	update_option ( 'wpfm_fields_migrated', 'yes' );
	// wpfm_pa($new_meta_data); exit;
}
	
function wpfm_get_old_children($old_file) {
		
	if( $old_file->node_type != 'dir' ) return null;
	
	$children = array();
	$wpfm_args = array(
	        'orderby'       => wpfm_get_sort_by(),
	        'order'         => wpfm_get_sort_order(),
	        'post_type'     => 'nm-userfiles',
	        'post_status'   => 'publish',
	        'nopaging'      => true,
	        'post_parent'   => $old_file->id,
	        'meta_key'		=> 'wpfm_migrated',
            'meta_value'		=> 'yes',
            'meta_compare'	=> 'NOT EXISTS',
	);
	
	$child_files = get_posts($wpfm_args);
	
	return $child_files;
}
