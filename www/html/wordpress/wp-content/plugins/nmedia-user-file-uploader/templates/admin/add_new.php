<?php

if ( !is_user_logged_in() && !$allow_public) {

		$public_message = wpfm_get_option('_public_message');
		if($public_message != ''){
			ob_start ();
		
			printf(__('%s', 'wpfm'), $public_message);
			$output_string = ob_get_contents ();
			ob_end_clean ();
			return $output_string;
		}else{
			echo '<script type="text/javascript">
			window.location = "'.wp_login_url( get_permalink() ).'"
			</script>';
			return ;
		}
	}
	

	// Loading all required scripts
	wpfm_load_scripts();


	$file_id = isset($_REQUEST['file_id']) ? intval($_REQUEST['file_id']) : '';
	$template_vars = array('group_ids' => '', 'file_id' => $file_id, 'wpfm_bp_group_id'=>'');
	
	ob_start ();
	
		wpfm_load_templates('wpfm-main.php', $template_vars);
		$output_string = ob_get_contents ();
		
	ob_end_clean ();

	echo $output_string;
