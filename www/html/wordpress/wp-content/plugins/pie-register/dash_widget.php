<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('PieRegisterWidget') ){
	class PieRegisterWidget{
		function __construct() { //contructor
			// Add the widget to the dashboard
			add_action( 'wp_dashboard_setup', array($this, 'register_widget') );
			add_filter( 'wp_dashboard_widgets', array($this, 'add_widget') );		
		}
		function register_widget() {
			$piereg = get_option(OPTION_PIE_REGISTER);
			if ( current_user_can('manage_options') )
				wp_register_sidebar_widget( 'piereg_invite_tracking', __('Pie Register Invitation Code Tracking Dashboard', 'pie-register' ), array($this, 'widget'), array( 'settings' => 'options-general.php?page=pie-register' ) );
		}
		// Modifies the array of dashboard widgets and adds this plugin's
		function add_widget( $widgets ) {
			global $wp_registered_widgets,$wp_registered_widget_controls;
			$wp_registered_widget_controls['piereg_invite_tracking']['callback'] = '';
	
			if ( !isset($wp_registered_widgets['piereg_invite_tracking']) ) return $widgets;
	
			array_splice( $widgets, 2, 0, 'piereg_invite_tracking' );
	
			return $widgets;
			
		}
		// Output the widget contents
		function widget( $args ) {
				
			global $wpdb;

			$users = $wpdb->get_results( $wpdb->prepare("SELECT COUNT(user_id) as total_users,`meta_value` FROM $wpdb->usermeta WHERE meta_key=%s GROUP BY BINARY `meta_value`", 'invite_code') );
			
			$count = 0;
			echo '<div class="pieregister_dash_widget_style">
					<style type="text/css">
					table.piereg_dash_widget td h3{border:none;}
					table.piereg_dash_widget tr td a{display:none;}
					table.piereg_dash_widget tr:nth-child(even){background:#F9F9F9;}
					table.piereg_dash_widget tr:hover{background:#F3F3F3;}
					</style>
			</div>';
			echo '<table width="100%" class="piereg_dash_widget" cellspacing="0" cellpaddinig="10">';
			echo '<tr><td colspan="2" align="center"><em>'. __("Showing invitation code of currently registered users only","pie-register") .'</em></td></tr>';
			foreach($users as $user){
				$total_users = $user->total_users;
				$count++;
				$meta_value = $user->meta_value;
				if(!empty($meta_value)){
					  echo '<tr>';
					  echo '<td><h3>' . esc_html($meta_value) . '</h3></td>';
					  echo '<td>' . esc_html($total_users).' ';
					  echo __("Users Registered","pie-register");
					  echo '</td>';
					  echo '</tr>';
				}
			}
			echo '</table>';
		}
	}
} # End Class PieRegisterWidget


// Start this plugin once all other plugins are fully loaded
add_action( 'plugins_loaded', 'initialize_pr_dashwidget');
function initialize_pr_dashwidget(){
	$piereg_widget = new PieRegisterWidget();
}
?>