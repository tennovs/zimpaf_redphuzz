<?php
/**
 * FrontEnd FileManager WP Upload Area Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 

?>

<div id="upload_files_btn_area" class="wpfm_upload_button ffmwp-uploadarea-content ffmwp-controls">
	<label class="ffmwp-select-input-wrap ffmwp_choosefile_lebel" for="ffmwp_choosefile">
		<div class="wpfm-new-select-wrapper">
			<?php 
				$select_file_label = wpfm_get_option ( '_button_title' );
			    $select_file_label	= (!$select_file_label == '') ? $select_file_label : 'Select Files';
				printf(__('%s', 'wpfm'), $select_file_label);
			?>
			<input type="file" id="ffmwp_choosefile" multiple accept="<?php echo wpfm_get_file_types(); ?>"/>
		</div>
	</label>
	
	<?php if( wpfm_can_user_create_directory() ): 
		$dir_create_lbl	= wpfm_get_option ( '_create_directory_label', __('Create Directory','wpfm') );
	?>
	
	<!-- create directory button -->
	<button class="ffmwp-click-to-reveal" id="wpfm-create-dir-option-btn"><?php printf(__('%s', 'wpfm'), $dir_create_lbl); ?></button>
	
	<?php
	/**
	 * action space for third party plugins
	 **/
	do_action('ffmwp_after_create_directory_button', $wpfm_bp_group_id, $shortcode_groups);
	?>
	
	<div class="ffmwp-click-to-reveal-block">
	  	<form id="ffmwp-create-dir-form">
	  		<input type="hidden" name="action" value="wpfm_create_directory">
	  		<input type="hidden" name="wpfm_bp_group_id" value="<?php echo esc_attr($wpfm_bp_group_id);?>">
	  		<input type="hidden" name="shortcode_groups" value="<?php echo esc_attr($shortcode_groups);?>">
	  		
		  	<div class="ffmwp-uploadarea-form-content">
		    	<label class="ffmwp-inputs" for="wpfm-dirname"><?php _e( "Directory Name", "wpfm" ); ?></label>
		    	<input type="text" id="wpfm-dirname" required name="dir_name">
		    	<label class="ffmwp-inputs"for="wpfm-description"><?php _e( "Description", "wpfm" ); ?></label>
		    	<input type="text" id="wpfm-description" name="directory_detail">
		  		<button class="ffmwp-uploadarea-btn" id="wpfm-dir-created-btn"><?php _e( "Create", "wpfm" ); ?></button>
		  		<button class="ffmwp-uploadarea-btn ffmwp-uploadarea-cancel-btn wpfm-cancle-btn"><?php _e( "Cancel", "wpfm" ); ?></button>
		  	</div>
		</form>
  	</div>
  	<?php
  	endif;
  	?>
  	
</div>