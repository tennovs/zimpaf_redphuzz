<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
	*	Defaine PR Global option's name
*/
if(!defined('OPTION_PIE_REGISTER'))
	define('OPTION_PIE_REGISTER','pie_register');
	
/*
	*	Define PR DB Version Name
*/
if(!defined('PIEREG_DB_VERSION'))
	define('PIEREG_DB_VERSION','3.7.1.0');

/*
	*	Define Restrict Widgets Option Name
*/
if(!defined("PIEREGISTER_RW_OPTIONS"))
	define("PIEREGISTER_RW_OPTIONS","pieregister_restrict_widgets");


/*
	*	Define name of Pie Register's Stats
*/
if(!defined("PIEREG_STATS_OPTION"))
	define("PIEREG_STATS_OPTION","pieregister_stats_option");
	
/*
	*	Define name of Currency Name with Code
*/
if(!defined("PIEREG_CURRENCY_OPTION"))
	define("PIEREG_CURRENCY_OPTION","piereg_currency");

/*
	*	Define name of Currency Name with Code
*/
if(!defined("PIEREG_DIR_NAME"))
	define("PIEREG_DIR_NAME",plugin_dir_path(__FILE__));

/*
	*	Define Plugin Base name
*/
if(!defined("PIEREG_PLUGIN_BASENAME"))
define( 'PIEREG_PLUGIN_BASENAME', "pie-register/pie-register.php" );
	
/*
	*	Define License Key opeion's name
*/
if(!defined("PIEREG_LICENSE_KEY_OPTION"))
define( 'PIEREG_LICENSE_KEY_OPTION', 'api_manager_example' );

/*
	*	Define PayPal Live Account URL
*/
if(!defined("PIE_SSL_P_URL"))
	define('PIE_SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');

/*
	*	Define PayPal Sandbox Account URL
*/
if(!defined("PIE_SSL_SAND_URL"))
	define('PIE_SSL_SAND_URL','https://www.sandbox.paypal.com/cgi-bin/webscr');

/*
	*	Define Log File Name
*/
$upload_dir = wp_upload_dir();
$temp_dir   = realpath($upload_dir['basedir'])."/pie-logs";
if(!defined("PIE_LOG_FILE"))
	define( 'PIE_LOG_FILE', $temp_dir );

if( !class_exists('PieRegisterBaseVariables') ){
	class PieRegisterBaseVariables
	{
		var $user_table;		
		var $user_meta_table; 
		var $plugin_dir;
		var	$plugin_url;
		var	$pie_success;
		var	$pie_error;
		var	$pie_error_msg;
		var	$pie_success_msg;
		var $piereg_global_options;// deprecated
		var $PR_GLOBAL_OPTIONS;
		var $pr_wp_db_prefix;
		var $pie_post_array;
		
		public $upgrade_url = 'http://store.genetech.co/';
		public $version 	= '1.0';
		
		public $piereg_api_manager_version_name 	= 'pie-register'; //plugin_api_manager_example_version
		public $piereg_plugin_url;
		
		public $piereg_text_domain 	= 'pie-register';
		
		var $piereg_pro_is_activate = false;
		var $no_addon_activated     = false;
		var $show_notice_premium_users = false;

         //pie-register-woocommerce addon
		var $woocommerce_and_piereg_wc_addon_active = false;
		//pie-register-field-visibility addon
		var $piereg_field_visbility_addon_active = false;

		//pie-register-bbpress
		var $piereg_bbpress_addon_active = false;
		
		function __construct(){
			
			/*
				*	Get PR Options from DB
			*/
			global $piereg_global_options;// deprecated
			global $PR_GLOBAL_OPTIONS;
			global $PR_Bot_List;
			
			$PR_GLOBAL_OPTIONS 		= get_option(OPTION_PIE_REGISTER);
			$piereg_global_options 	= $PR_GLOBAL_OPTIONS;
			$PR_Bot_List			= "bot\r\nia_archiver\r\ngooglebot\r\nbingbot\r\nslurp\r\nduckduckbot\r\nbaiduspider\r\nyandexbot\r\nsogou\r\nexabot\r\nfacebot";
			
			/*
				*	Get Wp DB Prefix
			*/
			global $wpdb,$pr_wp_db_prefix;
			$pr_wp_db_prefix = $wpdb->prefix;
			
			
			$this->pie_post_array	= array();
			
			
			/*
				*	check is activate plugins
			*/
			$options 	= get_option( PIEREG_LICENSE_KEY_OPTION );
			$activated 	= get_option( 'piereg_api_manager_activated' );
			$instance 	= get_option( 'piereg_api_manager_instance' );
			
            if (isset($options['api_key'], $options['activation_email']) && !empty($options['api_key']) && !empty($options['activation_email']) && $activated == "Activated" && !empty($instance)) {
				$this->show_notice_premium_users = true;
			} else {
				$this->show_notice_premium_users = false;
			}
			
			if(!defined("PIEREG_IS_ACTIVE"))
				define( 'PIEREG_IS_ACTIVE', false );
			
			
			if ( ! function_exists( 'is_plugin_active' ) )
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}			
			//pie-register-woocommerce addon
			if( is_plugin_active( 'woocommerce/woocommerce.php') && is_plugin_active('pie-register-woocommerce/pie-register-woocommerce.php') && get_option('piereg_api_manager_addon_WooCommerce_activated') == "Activated" ) {
				$this->woocommerce_and_piereg_wc_addon_active = true;
			}

			//pie-register-bbpress addon
			if( is_plugin_active('bbpress/bbpress.php') && is_plugin_active('pie-register-bbpress/pie-register-bbpress.php') && get_option('piereg_api_manager_addon_Bbpress_activated') == "Activated" ) {
				$this->piereg_bbpress_addon_active = true;
			}

			//pie-register-field-visibility addon
			if( is_plugin_active('pie-register-field-visibility/pie-register-field-visibility.php') && get_option('piereg_api_manager_addon_Field_Visibility_activated') == "Activated" ) {
				$this->piereg_field_visbility_addon_active = true;
			}
		}
		public function pie_get_admin_path()
		{
			// Replace the site base URL with the absolute path to its installation directory. 
			$admin_path = str_replace( get_bloginfo( 'wpurl' ) . '/', ABSPATH, get_admin_url() );
			
			if(	$admin_path == get_admin_url()	)
			{
				return ABSPATH . 'wp-admin/';
			}
			
			// Make it filterable, so other plugins can hook into it.
			$admin_path = apply_filters( 'pie_get_admin_path', $admin_path );
			return $admin_path;
		}
		function piereg_pro_is_activate(){
			return false;
		}
		
	}
}