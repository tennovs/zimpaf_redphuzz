<?php

/* 
Plugin Name: Pie Register - Basic
Plugin URI: https://pieregister.com/
Description: Create custom user registration forms, drag & drop form builder, send invitation codes, add conditional logic, 2-step authentication, assign user roles, accept payments and more!
Version: 3.7.2.3
Author: Pie Register
Author URI: https://pieregister.com/
Text Domain: pie-register
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;	
}

/*
	* Get Pie Register Dir Name
*/
$piereg_dir_path = dirname(__FILE__);

// Plugin Folder Path.
if(!defined("PIEREG_DIR_NAME")){
	define("PIEREG_DIR_NAME",  $piereg_dir_path );
}

// Plugin Folder URL.
if ( ! defined( 'PIEREG_PLUGIN_URL' ) ) {
	define( 'PIEREG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/*
	* Include PR Files
*/

require_once(PIEREG_DIR_NAME.'/classes/base.php');
require_once(PIEREG_DIR_NAME.'/classes/profile_admin.php');
require_once(PIEREG_DIR_NAME.'/classes/profile_front.php');
require_once(PIEREG_DIR_NAME.'/classes/registration_form.php');
require_once(PIEREG_DIR_NAME.'/classes/edit_form.php');
require_once(PIEREG_DIR_NAME.'/widget.php');
require_once(PIEREG_DIR_NAME.'/dash_widget.php');
require_once(PIEREG_DIR_NAME.'/piereg_walker-nav-menu.php');
// Add Notices
if(file_exists(PIEREG_DIR_NAME.'/classes/admin/admin-notice.php'))
	require_once(PIEREG_DIR_NAME.'/classes/admin/admin-notice.php');

/**
 * Include PR App Services files
 * Since 3.5
 */

if(file_exists(PIEREG_DIR_NAME.'/classes/app/app_api.php')) require_once(PIEREG_DIR_NAME.'/classes/app/app_api.php');
if(file_exists(PIEREG_DIR_NAME.'/classes/app/firebase.php')) require_once(PIEREG_DIR_NAME.'/classes/app/firebase.php');
if(file_exists(PIEREG_DIR_NAME.'/classes/app/push.php')) require_once(PIEREG_DIR_NAME.'/classes/app/push.php');

/*
	Move PR DB Version Name to PieRegisterBaseVariables
*/
if (!function_exists("pr_licenseKey_errors")) {
	function pr_licenseKey_errors() {
		do_action("pr_licenseKey_errors");
	}
}

global $pie_success,$pie_error,$pie_error_msg,$pie_suucess_msg, $pagenow,$action,$profile,$errors,$piereg_math_captcha_register,$piereg_math_captcha_register_widget,$piereg_math_captcha_login,$piereg_math_captcha_login_widget,$piereg_math_captcha_forgot_pass,$piereg_math_captcha_forgot_pass_widget;

$piereg_math_captcha_register = false;
$piereg_math_captcha_register_widget = false;
$piereg_math_captcha_login = false;
$piereg_math_captcha_login_widget = false;
$piereg_math_captcha_forgot_pass = false;
$piereg_math_captcha_forgot_pass_widget = false;

register_activation_hook(__FILE__, 'checkPieRegisterPremiumVersion');

//Action to Perform when activating
function checkPieRegisterPremiumVersion()
{
	if (is_plugin_active('pie-register-premium/pie-register-premium.php')) {
		// Deactivate Premium Version
		deactivate_plugins('pie-register-premium/pie-register-premium.php');
	}

	add_option( 'piereg_activated_time', time(), '', false );
}

if( !class_exists('PieRegister') ){
	class PieRegister extends PieReg_Base{
		var $pie_success,$pie_error,$pie_error_msg,$pie_success_msg,$txn_id,$ipn_log,$ipn_data, $piereg_forms_per_page = array(),$postvars,$pie_pr_dec_vars_array,$pie_pr_backend_dec_vars_array,$pie_ua_renew_account_url,$pie_is_social_renew_account_call,$pie_payment_methods_dat,$pie_after_login_page_redirect_url,$is_pr_preview;
		private $ipn_status,$ipn_debug,$post_block_content,$ipn_response = array(),$piereg_jquery_enable = false;
		
		function __construct(){
			global $pagenow,$wp_version,$profile;
			
			/////
			$this->is_pr_preview = false;
			$this->pie_pr_dec_vars_array = false;
			$this->pie_pr_backend_dec_vars_array = false;
			$this->pie_ua_renew_account_url = false;
			$this->pie_is_social_renew_account_call = false;
			$this->pie_after_login_page_redirect_url = false;
			
			///////////////////
			$this->pie_payment_methods_dat = apply_filters('add_select_payment_script_',$this->pie_payment_methods_dat);
			///////////////////
			$this->ipn_status = '';
			$this->txn_id = null;
			$this->ipn_log = true;
			$this->ipn_response = '';
			$this->ipn_debug = false;
			//self::$pieinstance = $this;
			/***********************/
			parent::__construct();
			
			$this->pieActions();
			$this->pieFilters();
			$errors = new WP_Error();
			
			/*
				*	API Classes
			*/
			
			if( is_admin() && ( (isset($_POST['piereg_license_key']) && !empty($_POST['piereg_license_key'])) || (isset($_POST['piereg_pro_not_activated'] )) ) )
			{
				$plugin_name = untrailingslashit( plugin_basename( __FILE__ ) ); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
				/*if
				(post || has)*/
				// Performs activations and deactivations of API License Keys
				if( file_exists(PIEREG_DIR_NAME.'/classes/api/class-wc-key-api.php') )
					require_once(PIEREG_DIR_NAME.'/classes/api/class-wc-key-api.php');
				// Checks for software updatess
				if( file_exists(PIEREG_DIR_NAME.'/classes/api/class-wc-plugin-update.php') )
					require_once(PIEREG_DIR_NAME.'/classes/api/class-wc-plugin-update.php');
				// Admin menu with the license key and license email form
				if( file_exists(PIEREG_DIR_NAME. '/classes/api/class-wc-api-manager-menu.php') )
					require_once( PIEREG_DIR_NAME. '/classes/api/class-wc-api-manager-menu.php' );
					
				/* Load update class to update $this plugin
				** This function is not using since version 3.3.0. It was previously using for premium plugin. 
				** Function in base.php removed. Below line will also soon remove from upcoming version.
				** 27-08-2021
				*/
				# $this->load_plugin_self_updater($plugin_name); // Temporary disable license checker.
			}
						
			//Download or View Log file
			$this->pr_logfile_download_or_view();
		}

		function pie_main(){
			global $piereg_global_options, $pagenow;
			$option = $piereg_global_options;

			$this->get_notifications();


			//LOCALIZATION
			#Place your language file in the plugin folder and name it "pie-register-{language}.mo"
			#replace {language} with your language value from wp-config.php
			//load_textdomain( 'pie-register', ABS_PATH_TO_MO_FILE ); // OK
			
			#$locale = ( get_locale() != '' ) ? get_locale() : 'en_US';
			#load_textdomain( 'pie-register', WP_LANG_DIR . '/plugins/' . 'pie-register' . '-' . $locale . '.mo');
			load_plugin_textdomain( 'pie-register', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			

			$pie_plugin_data = get_plugin_data( __FILE__ );
			$pie_plugin_version = $pie_plugin_data['Version'];
			if(!get_option('pie_countries_v352') && $pie_plugin_version >= "3.5.2"){
				$this->updated_countries_list();
			}

			$pie_plugin_db_version = get_option('piereg_plugin_db_version');
			if($pie_plugin_db_version != PIEREG_DB_VERSION){
				$this->install_settings();
			}
			
			/* 
				* Define PR Version
			*/
			if ( ! defined( 'PIEREGISTER_VERSION' ) ) {
				define( 'PIEREGISTER_VERSION', $pie_plugin_version );
			}
			
			$_changed_un_email_template		= get_option('pie_email_template_updated_blocked_unverified_users');
			if(!$_changed_un_email_template)
			{
				$this->update_email_template_unverified_grace();
			} 
			
			//	:added elementor-preview - Fixing elementor loading issue with PR forms //
			$this->is_pr_preview = (isset($_GET['pr_preview']) || isset($_GET['elementor-preview']) || isset($_GET['et_fb']) || (isset($_GET['preview']) && $_GET['preview'] == "true") || (isset($_GET['action']) && $_GET['action'] == 'elementor') || (isset($_GET['ct_builder']) && $_GET['ct_builder'] == 'true') )?true:false;

			//	:added DIVI-preview - Fixing DIVI loading issue with PR forms //
			$this->is_pr_preview = (isset($_GET['et_pb_preview']))?true:$this->is_pr_preview;

			if(!function_exists('wp_get_current_user')) {
				include_once(ABSPATH . WPINC . "/pluggable.php"); 				
			}
			
			# Change User Avatar With Pie Profile Picture, If Used:
			add_filter( 'get_avatar' , array($this,'pie_user_avatar') , 1 , 5 );
			if( !is_admin() && (is_user_logged_in() && !current_user_can('administrator') ) )
			{
				//add_filter( 'get_avatar' , array($this,'pie_user_avatar') , 1 , 5 );
			}
			
			if( is_admin() && current_user_can('administrator') )
			{
				add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1000 );
				
				# Delete Unverified Users Over Grace Period
				$this->deleteUnverifiedUsers();
			}
			
			if( file_exists(PIEREG_DIR_NAME."/editors/elementor/Elementor.php") )
					require_once(PIEREG_DIR_NAME."/editors/elementor/Elementor.php");
				
			do_action( 'pieregister_elementor' );

			if( file_exists(PIEREG_DIR_NAME."/editors/divi/Divi.php") )
				require_once(PIEREG_DIR_NAME."/editors/divi/Divi.php");
				
			do_action( 'pieregister_divi' );

			add_action( 'wp_ajax_pie_rated', array( $this, 'pieregister_rated' ) );
			//add_filter( 'wp_privacy_personal_data_exporters', array($this, 'register_my_plugin_exporter'), 10);
						
			if( current_user_can( 'administrator' ) && $this->show_notice_premium_users ){
				#Pro Version Release notices
				add_action( 'admin_notices', array( $this, 'pie_free_notice_to_premium_users' ), 1 );
			}

			add_action( 'admin_notices', array( $this, 'pie_promo_notice_security' ), 1 );
			add_action( 'admin_notices_specific_pages', array( $this, 'pie_premium_features_notice_for_free' ), 1 );
			
			add_action( 'admin_notices', array( $this, 'pie_leave_a_review_request' ), 1 );

			# // license_expiry
			add_action( 'admin_notices', array( $this, 'pie_license_expire_msg' ), 1 );
			add_action( 'wp_ajax_dismiss_pie_license_expire_msg', array( $this, 'dismiss_pie_license_expire_msg' ) );

			if( is_plugin_active('woocommerce/woocommerce.php') && !is_plugin_active('pie-register-woocommerce/pie-register-woocommerce.php') ) {
				add_action( 'admin_notices', array( $this, 'pie_promo_notice_for_wc_user' ), 1 );	
			}
			if( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') && !is_plugin_active('pie-register-mailchimp/pie-register-mailchimp.php') ) {
				add_action( 'admin_notices', array( $this, 'pie_promo_notice_for_mc_user' ), 1 );
			}
			if( is_plugin_active('bbpress/bbpress.php') && !is_plugin_active('pie-register-bbpress/pie-register-bbpress.php') ) {
				add_action( 'admin_notices', array( $this, 'pie_promo_notice_for_bb_user' ), 1 );
			}
			$all_plugins = get_plugins();

			if ( is_plugin_active('js_composer/js_composer.php') && ( !array_key_exists( 'vc-addons-by-bit14/bit14-vc-addons.php', $all_plugins ) && !array_key_exists( 'page-builder-addons-premium/bit14-addons.php', $all_plugins ) ) ) {
				add_action( 'admin_notices', array( $this, 'pie_promo_notice_for_wpb_user' ), 1 );
			} 

			add_action( 'wp_ajax_dismiss_pie_promo_notice_security', array( $this, 'dismiss_pie_promo_notice_security' ) );	
			add_action( 'wp_ajax_dismiss_pie_promo_notice_for_wc_user', array( $this, 'dismiss_pie_promo_notice_for_wc_user' ) );	
			add_action( 'wp_ajax_dismiss_pie_promo_notice_for_mc_user', array( $this, 'dismiss_pie_promo_notice_for_mc_user' ) );
			add_action( 'wp_ajax_dismiss_pie_promo_notice_for_bb_user', array( $this, 'dismiss_pie_promo_notice_for_bb_user' ) );
			add_action( 'wp_ajax_dismiss_pie_promo_notice_for_wpb_user', array( $this, 'dismiss_pie_promo_notice_for_wpb_user' ) );
			add_action( 'wp_ajax_dismiss_pie_premium_features_notice_for_free', array( $this, 'dismiss_pie_premium_features_notice_for_free' ) );			
			add_action( 'wp_ajax_dismiss_pie_leave_a_review_request', array( $this, 'dismiss_pie_leave_a_review_request' ) );
			add_action( 'wp_ajax_dismiss_pie_free_notice_to_premium_users', array( $this, 'dismiss_pie_free_notice_to_premium_users' ) );
			
			/*********************************************/
			/////////////// PIEREG LOGOUT ////////////////
			if( isset($_GET['piereg_logout_url']) && $_GET['piereg_logout_url'] == "true" ){				
				$after_logout_page = (intval($option['alternate_logout']) <= 0)? wp_logout_url() : $this->get_page_uri($option['alternate_logout']);
				$redirect_to = (isset($_GET['redirect_to']) && !empty($_GET['redirect_to']))? esc_url_raw(urldecode($_GET['redirect_to'])) : $after_logout_page;
				wp_logout();
				wp_redirect(esc_url_raw($redirect_to));
				exit;
			}
			
			/*********************************************/
			
			/////////// Register Scripts ////////////
			$this->piereg_register_scripts();
			////////////////////////////////////////
			
			// check to prevent php "notice: undefined index" msg
			$theaction = '';	
			if(isset($_GET['action'])) 
				$theaction = sanitize_text_field($_GET['action']); 
			
			if((isset($_GET['show_dash_widget']) && $_GET['show_dash_widget']==1) and (isset($_GET['invitaion_code']) && $_GET['invitaion_code']!="")){
				$this->show_invitaion_code_user();
			}
			
			# PAYPAL VALIDATION
			$this->ValidPUser();
			
			# Save Settings
			if( isset($_POST['action']) && $_POST['action'] == 'pie_reg_update' )
			{
				$this->SaveSettings();
			}
			
			if( isset($_POST['action']) && $_POST['action'] == 'pie_reg_settings' ) {
				$this->PieRegSettingsProcess();
			}
			// task av
			# Admin verifying user through email
			if(isset($_GET['action']) && $_GET['action'] == 'unverified_user'){
				add_action ('wp_body_open' , array( $this, 'process_verify_user_email' ));				
			}
			
			#Reset Settings to default
			if( isset($_POST['piereg_default_settings']) )
			{
				$this->piereg_default_settings();
			}
			
			#Admin Verify Users
			if( isset($_POST['verifyit']) )		
				$this->verifyUsers();
				
			#Admin Send Payment Link
			if( isset($_POST['paymentl']) )
				$this->PaymentLink();
			
			#Admin Resend VerificatioN Email
			if( isset($_POST['emailverifyit']) )
				$this->AdminEmailValidate() ;		
				
			#Admin Delete Unverified User
			if( isset($_POST['vdeleteit']))			
				$this->AdminDeleteUnvalidated();	
		
			/*
				*	Add since 2.0.13
				*	Change email after verify
			*/
			$this->edit_email_verification();
			/* End */

			// Payment Log File Download & Delete
			$this->piereg_payment_log_file_action();
			
			//Blocking wp admin for registered users			
			if(
			   ($pagenow == 'wp-login.php' && $option['block_wp_login']==1) && 
			   ($option['alternate_login']  && $theaction != 'logout') && 
			   (!isset($_REQUEST['interim-login']))
			   ){	
				
				if ( force_ssl_admin()  && ! is_ssl() ) {
					$is_ssl = true;
				}else{
					$is_ssl = false;
				}
				
				if($theaction=="register"){
					if($is_ssl){
						wp_safe_redirect(preg_replace('|^http://|', 'https://', $this->get_redirect_url_pie(get_permalink($option['alternate_register']))));
						exit;
					}
					else{
						wp_safe_redirect($this->get_redirect_url_pie(get_permalink($option['alternate_register'])));
						exit;
					}
					
				}
				else if($theaction=="lostpassword")
				{
					if($is_ssl){
						wp_safe_redirect(preg_replace('|^http://|', 'https://', $this->get_redirect_url_pie(get_permalink($option['alternate_forgotpass']))));
						exit;
					}
					else{
						wp_safe_redirect($this->get_redirect_url_pie(get_permalink($option['alternate_forgotpass'])));						
						exit;
					}
				}
				else if($theaction=="")
				{
					if($is_ssl){
						wp_safe_redirect(preg_replace('|^http://|', 'https://', $this->get_redirect_url_pie(get_permalink($option['alternate_login']))));
						exit;
					}
					else{
						wp_safe_redirect($this->get_redirect_url_pie(get_permalink($option['alternate_login'])));
						exit;
					}
				}
			}
			
			//Blocking access of users to default pages if redirect is on 
			
				if($theaction != 'logout' && $theaction != 'postpass' )
				{
					if((is_user_logged_in() && $pagenow == 'wp-login.php') && ($option['redirect_user']==1   && $theaction != 'logout'))
					{
						if(!isset($_REQUEST['interim-login'])){
							$this->afterLoginPage();
						}
					}
				}
				if(trim($pagenow) == "profile.php" && $option['block_WP_profile']==1 )
				{
					$current_user = wp_get_current_user();
					if(trim($current_user->roles[0]) !== "administrator")
					{
						//$profile_page = get_option("Profile_page_id");
						$profile_page = $option['alternate_profilepage'];
						if($profile_page > 0){
							wp_safe_redirect($this->get_redirect_url_pie(get_permalink($profile_page)));
							exit;
						}
					}
				}
			
				//Blocking wp admin for registered users			
				if( isset($_POST['pie_submit'], $_POST['piereg_registration_form_nonce']) && wp_verify_nonce($_POST['piereg_registration_form_nonce'], 'piereg_wp_registration_form_nonce') )
				{
					$this->check_register_form();
				}
				else if(isset($_POST['pie_renew']))
				{
					$this->renew_account();
				}
				
				// if the user is on the login page, then let the game begin
				if($theaction != 'logout' && $theaction != 'postpass' )
				{
					if ($pagenow == 'wp-login.php' && $theaction != 'logout'){
						add_action('login_init',array($this,'pieregister_login'),1);
					}
				}
				
			//OImport Export Section
			if(isset($_POST['pie_fields_csv']) || isset($_POST['pie_meta_csv'])){
				$this->generateCSV();
			}
			else if(isset($_FILES['csvfile']['name'])){
				$this->importUsers();
			}

			if(isset($_POST['pie_form']))
			{
				//This will make sure no one tempers the field from the client side
				/*$required = array("form","username","email","password","submit");*/
				$required = array("form","email","password","submit");
				$length   = 0;
				foreach($_POST['field'] as $field)
				{
					if(in_array($field['type'],$required))
					$length++;
				}
				if($length==sizeof($required))
				{
					$this->saveFields();
				}
			}
			
			if(isset($_GET['prfrmid']) and ((int)$_GET['prfrmid']) != 0 and isset($_GET['status']) and $_GET['status'] != "")
			{
				$fields_id 					= ((int)$_GET['prfrmid']);
				$pr_form_option 			= get_option("piereg_form_field_option_".$fields_id);
				$pr_form_option['Status'] 	= ( strtolower( sanitize_key($_GET['status']) ) );
				update_option("piereg_form_field_option_".$fields_id,$pr_form_option);
				
				#$this->pie_post_array['notice'] = __("Successfully Change Status of Pie Register Registration Form","pie-register");
				
			}	
			
			if( ( isset($_GET['prfrmid']) and ((int)$_GET['prfrmid']) != 0 and isset($_GET['action']) and $_GET['action'] == "delete" ) && $this->check_if_role_admin() )
			{

				// if users created from Form then disable it, delete if no submission. 
				$fields_id 			= ((int)sanitize_key($_GET['prfrmid']));
				$pr_all_form_info 	= $this->get_pr_forms_info();
				if(count($pr_all_form_info) > 1) {
					
					$fields_id 		= ((int)sanitize_key($_GET['prfrmid']));
					$optionsform	= get_option("piereg_form_field_option_".$fields_id);
					
					if( $optionsform['Entries'] > 0 ) {
						$optionsform['IsDeleted'] 	= 1;
						update_option("piereg_form_field_option_".$fields_id,$optionsform);
						
						// Assign new free form.
						$this->regFormForFreeVers(true);					
					} else {
						$this->delete_piereg_form();						
					}
				
				} else {
					$this->pie_post_array['error_message'] = __("Form deletion failed.","pie-register");
				}
			}
			
			if(
				isset($_POST['invitaion_code_bulk_option'],$_POST['piereg_invitaion_code_bulk_option_nonce']) and isset($_POST['btn_submit_invitaion_code_bulk_option']) and isset($_POST['select_invitaion_code_bulk_option']) and $_POST['invitaion_code_bulk_option'] != "" and $_POST['btn_submit_invitaion_code_bulk_option'] != "" and wp_verify_nonce($_POST['piereg_invitaion_code_bulk_option_nonce'], 'piereg_wp_invitaion_code_bulk_option_nonce')
			)
			{
				if(trim($_POST['invitaion_code_bulk_option']) == "delete")
				{
					$this->delete_invitation_codes($_POST['select_invitaion_code_bulk_option']);
				}
				else if(trim($_POST['invitaion_code_bulk_option']) == "active")
				{
					$this->active_or_unactive_invitation_codes($_POST['select_invitaion_code_bulk_option'],"1");
				}
				else if(trim($_POST['invitaion_code_bulk_option']) == "unactive")
				{
					$this->active_or_unactive_invitation_codes($_POST['select_invitaion_code_bulk_option'],"0");
				}
			}
			if( (isset($_POST['piereg_invitation_code_per_page_nonce']) && wp_verify_nonce( $_POST['piereg_invitation_code_per_page_nonce'], 'piereg_wp_invitation_code_per_page_nonce' )) && isset($_POST['invitation_code_per_page_items']) and $_POST['invitation_code_per_page_items'] != "")
			{
				$opt = get_option(OPTION_PIE_REGISTER);
				$val = ((int)($_POST['invitation_code_per_page_items']) != 0)? ((int)$_POST['invitation_code_per_page_items']) : "10";
				$opt['invitaion_codes_pagination_number'] = intval( $val );
				update_option(OPTION_PIE_REGISTER,$opt);
				$piereg_global_options = $opt;
				unset($opt);
			}

			if(
				isset($_POST['custom_role_bulk_option'],$_POST['piereg_custom_role_bulk_option_nonce']) and isset($_POST['btn_submit_custom_role_bulk_option']) and isset($_POST['select_custom_role_bulk_option']) and $_POST['custom_role_bulk_option'] != "" and $_POST['btn_submit_custom_role_bulk_option'] != "" and wp_verify_nonce($_POST['piereg_custom_role_bulk_option_nonce'], 'piereg_wp_custom_role_bulk_option_nonce')
			)
			{

				if(trim($_POST['custom_role_bulk_option']) == "delete")
				{
					$this->delete_custom_roles($_POST['select_custom_role_bulk_option']);
				}
			}

			if( isset($option['show_admin_bar']) && $option['show_admin_bar'] == "1" )
			{
				$this->subscriber_show_admin_bar();// show/hide admin bar
			}
			
			if( (isset($_POST['piereg_old_version_import']) && wp_verify_nonce( $_POST['piereg_old_version_import'], 'piereg_wp_old_version_import' )) && ( isset($_POST['import_email_template_from_version_1']) and $_POST['old_version_import'] == "yes" ) )
			{
				$old_options = get_option("pie_register_2");
				$new_options = get_option(OPTION_PIE_REGISTER);
				$new_options['user_message_email_admin_verification'] 	= nl2br($old_options['adminvmsg']);
				$new_options['user_message_email_email_verification'] 	= nl2br($old_options['emailvmsg']);
				$new_options['user_message_email_default_template'] 	= nl2br($old_options['msg']);
				update_option(OPTION_PIE_REGISTER,$new_options);
				global $piereg_global_options;
				$piereg_global_options = $new_options;
			}
			
				if(isset($option['show_custom_logo']) && $option['show_custom_logo'] == 1){
					if(trim($option['custom_logo_url']) != ""){
						add_action( 'login_enqueue_scripts', array($this,'piereg_login_logo'));
					}
					add_filter( 'login_headertext',  array($this,'piereg_login_logo_url_title' )); // login_headertitle was deprecated.
					add_filter( 'login_headerurl',  array($this,'piereg_login_logo_url' ));
				}
			
			/*
				*	Activate addon license key
			*/
			$this->activate_addon_license_key();

			// 3.6.15
			add_action( 'wp_ajax_pieregister_activate_addon', array($this, 'pieregister_activate_addon' ));
			add_action( 'wp_ajax_pieregister_deactivate_addon', array($this, 'pieregister_deactivate_addon' ));
			add_action( 'wp_ajax_pieregister_install_addon', array($this, 'pieregister_install_addon' ));

			// 3.7.15 - To allow properties in style attributes with wp_kses
			add_filter( 'safe_style_css', function( $styles ) {
				$styles[] = 'visibility';
				$styles[] = 'display';
				return $styles;
			} );
		}
		function pieActions(){
			global $piereg_global_options, $pagenow;
			
			add_action('wp_ajax_check_username',  array($this,'unique_user' ));
			add_action('wp_ajax_nopriv_check_username',  array($this,'unique_user' ));	
			
			add_action( 'admin_init', array($this,'piereg_register_scripts') );
			add_action( 'admin_init', array($this,'piereg_backendregister_scripts') );
			#Adding Menus
			add_action( 'admin_menu',  array($this,'AddPanel') );
			// Show Pie Register Menu on the Admin Bar
			add_action( 'admin_bar_menu',  array($this,'AddPanelOnAdminBar') , 999);
			
			//Add paypal payment method
			add_action("check_payment_method_paypal", array($this, "check_payment_method_paypal"),10,1);
			
			
			//Adding "embed form" button      
			add_action('media_buttons', array($this, 'add_pie_form_button'));
			
			if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))  && is_admin() ){
				add_action('admin_footer',  array($this, 'add_pie_form_popup'));
			}
			
			//after_setup_theme {{ previously fires on after_setup_theme hook }}
			add_action("init",array($this,"piereg_after_setup_theme"));
			
			#Genrate Warnings
			add_action('admin_notices', array($this, 'warnings'),20);
						
			add_action( 'init', array($this,'pie_main') );			
			$profile = new Profile_admin();
			add_action('show_user_profile',array($profile,"edit_user_profile"));
			add_action('personal_options_update',array($profile,"updateMyProfile"));
			
			add_action('edit_user_profile',array($profile,"edit_user_profile"));
			add_action('edit_user_profile_update', array($profile,'updateProfile'));	
			
			add_action( 'widgets_init', array($this,'initPieWidget'));
			
			add_action('get_header', array($this,'add_ob_start'));
			//It will redirect the User to the home page if the curren tpage is a alternate login page
			//add_filter('get_header', array($this,'checkLoginPage'));
			
			add_action('payment_validation_paypal',	array($this, 'payment_validation_paypal'));
	
			add_action("add_select_payment_script",	 array($this,"add_select_payment_script"));
			add_filter("get_payment_content_area",	 array($this,"get_payment_content_area"));			
			add_action("show_icon_payment_gateway",	array($this,"show_icon_payment_gateway"));			
			add_action("pr_licenseKey_errors",array($this,"print_Rpr_licenseKey_errors"),30);			
			add_filter("piereg_messages",array($this,"modify_all_notices"));
						
			/*update update_invitation_code form ajax*/
			add_action( 'wp_ajax_pireg_update_invitation_code', array($this,'pireg_update_invitation_code_cb_url' ));
			add_action( 'wp_ajax_nopriv_pireg_update_invitation_code', array($this,'pireg_update_invitation_code_cb_url' ));
			
			// FRONT END SCRIPTS
			add_action('wp_enqueue_scripts',array($this,'piereg_frontend_assets_header'));
			add_action( 'wp_footer', array( $this, 'piereg_frontend_assets_footer' ), 15 );
			add_action('admin_enqueue_scripts', array($this,'pie_backend_enqueue_scripts'));

			if( file_exists(PIEREG_DIR_NAME."/editors/wpbakery/wpbakery-pie-element.php") && defined( 'WPB_VC_VERSION' ))
				require_once(PIEREG_DIR_NAME."/editors/wpbakery/wpbakery-pie-element.php");
				
			if( file_exists(PIEREG_DIR_NAME."/editors/gutenberg/gutenberg-block.php") )
				require_once(PIEREG_DIR_NAME."/editors/gutenberg/gutenberg-block.php");

			do_action( 'pieregister_gutenberg');

			//User Deletion
			add_action( 'delete_user', array( $this, 'piereg_user_deletion' ) );
			#Adding Short Code Functionality
			add_shortcode( 'pie_register_login',  array($this,'showLoginForm') );
			add_shortcode( 'pie_register_profile', array($this,'showProfile') );
			add_shortcode( 'pie_register_forgot_password',  array($this,'showForgotPasswordForm') );
			add_shortcode( 'pie_register_renew_account',  array($this,'show_renew_account') );
			add_shortcode( 'pie_register_form',  array($this,'piereg_registration_form') );
			// Adding shortcode for user's profile picture
			// ver - 3.6.3
			add_shortcode( 'pie_user_profile_pic',  array($this,'piereg_user_profile_pic') );
			add_action("the_post",array($this,"piereg_template_restrict"));
			//Add Post Meta Box
			add_action('add_meta_boxes', array($this,'piereg_add_meta_box'));
			//Save Post Meta Box
			add_action('save_post', array($this,'piereg_save_meta_box_data'));
			//Validate User expiry period
			add_action("piereg_validate_user_expiry_period", array($this,"piereg_validate_user_expiry_period_func"),10,1);
			add_action( 'wp_footer', array($this,'print_multi_captcha_skin' ));
			add_action('wp_footer', array($this,'pie_update_user_meta_admin_hash' ));
			
			if(in_array($GLOBALS['pagenow'], array('wp-login.php'))){
				add_filter( 'wp_authenticate_user', array( $this, 'pie_check_status_on_login' ), 10, 2 );
			}

			
			//Rest API Initialized
			$pie_api = new Pie_api();
		}
		function pieFilters(){
			global $piereg_global_options;
			add_action("add_payment_method_script", array($this,"add_payment_method_script"));
			//Add sub links in wp plugin's page
			add_filter( 'plugin_row_meta', array( $this, 'piereg_plugin_row_meta' ), 10, 2 );
			//plugin page links
			add_filter( 'plugin_action_links', array($this,'add_action_links'),10,2 );
			//add_filter("Add_payment_option_PaypalStandard", array($this,'Add_payment_option_PaypalStandard'),10,1);
			add_filter("Add_payment_option", array($this,"Add_payment_option"),10,3);
			add_filter("get_payment_gateway_content", array($this,"get_paypalstandard"),10,3);
			
			add_filter('allow_password_reset',array($this,'checkUserAllowedPassReset'),20,2);
			if(isset($piereg_global_options['block_wp_login']) && $piereg_global_options['block_wp_login']){
				add_filter( 'login_url', array($this,'pie_login_url'),88888,1);
				add_filter( 'lostpassword_url', array($this,'pie_lostpassword_url'),88888,1);
				add_filter( 'register_url', array($this,'pie_registration_url'),88888,1);
				add_filter( 'logout_url', array($this,'piereg_logout_url'),88888,2);
			}
			add_filter( 'piereg_password_reset_not_allowed_text', array($this,'piereg_password_reset_not_allowed_text_function'),20,1);
			
			/**
			 * Invitation code column added in Users grid
			 * Since v3.5.4
			 */
			add_filter( 'manage_users_columns', array($this,'pie_column_invite') ); // Add column in Users Table
			add_filter( 'manage_users_custom_column', array($this,'pie_column_invite_value'), 10, 3 ); // Add column value in Users Table
		}
		function pieregister_activate_addon() {

			// Check for permissions.
			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'pie-register' ) );
			}

			if ( isset( $_POST['plugin'] ) ) {
				$type = 'addon';
				if ( ! empty( $_POST['type'] ) ) {
					$type = sanitize_key( $_POST['type'] );
				}

				$plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
				$activate = activate_plugins( $plugin );

				do_action( 'pieregister_plugin_activated', $plugin );

				if ( ! is_wp_error( $activate ) ) {
					if ( 'plugin' === $type ) {
						wp_send_json_success( esc_html__( 'Plugin activated.', 'pie-register' ) );
					} else {
						wp_send_json_success( esc_html__( 'Addon activated.', 'pie-register' ) );
					}
				}
			}

			wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'pie-register' ) );
		}
		function pieregister_deactivate_addon() {

			// Check for permissions.
			if ( ! current_user_can( 'deactivate_plugins' ) ) {
				wp_send_json_error( esc_html__( 'Plugin deactivation is disabled for you on this site.', 'pie-register' ) );
			}
		
			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}
		
			if ( isset( $_POST['plugin'] ) ) {
				$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		
				deactivate_plugins( $plugin );
		
				do_action( 'pieregister_plugin_deactivated', $plugin );
		
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin deactivated.', 'pie-register' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon deactivated.', 'pie-register' ) );
				}
			}
		
			wp_send_json_error( esc_html__( 'Could not deactivate the addon. Please deactivate from the Plugins page.', 'pie-register' ) );
		}
		function pieregister_install_addon() {

			$generic_error = esc_html__( 'There was an error while performing your request.', 'pie-register' );
		
			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}
		
			$error = esc_html__( 'Could not install addon. Please download and install manually.', 'pie-register' );
		
			if ( empty( $_POST['plugin'] ) ) {
				wp_send_json_error( $error );
			}
		
			// Set the current screen to avoid undefined notices.
			set_current_screen( 'pie-register_page_pie-about-us' );
		
			// Prepare variables.
			$url = esc_url_raw(
				add_query_arg(
					array(
						'page' => 'pie-about-us',
					),
					admin_url( 'admin.php' )
				)
			);
		
			$creds = request_filesystem_credentials( $url, '', false, false, null );
		
			// Check for file system permissions.
			if ( false === $creds ) {
				wp_send_json_error( $error );
			}
		
			if ( ! WP_Filesystem( $creds ) ) {
				wp_send_json_error( $error );
			}
		
			/*
			 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
			 */
		
			require_once(plugin_dir_path( __FILE__ ) . 'classes/admin/pie-class-install-skin.php');
			require_once(plugin_dir_path( __FILE__ ) . 'classes/admin/PieRegPluginSilentUpgrader.php');

			// Do not allow WordPress to search/download translations, as this will break JS output.
			remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
		
			// Create the plugin upgrader with our custom skin.
			$installer = new PieRegPluginSilentUpgrader( new PieReg_Install_Skin() );
		
			// Error check.
			if ( ! method_exists( $installer, 'install' ) || empty( $_POST['plugin'] ) ) {
				wp_send_json_error( $error );
			}
		
			$installer->install( $_POST['plugin'] ); // phpcs:ignore
		
			// Flush the cache and return the newly installed plugin basename.
			wp_cache_flush();
		
			$plugin_basename = $installer->plugin_info();
		
			if ( empty( $plugin_basename ) ) {
				wp_send_json_error( $error );
			}
		
			$result = array(
				'msg'          => $generic_error,
				'is_activated' => false,
				'basename'     => $plugin_basename,
			);
		
			// Check for permissions.
			if ( ! current_user_can( 'activate_plugins' ) ) {
				$result['msg'] = 'plugin' === $type ? esc_html__( 'Plugin installed.', 'pie-register' ) : esc_html__( 'Addon installed.', 'pie-register' );
		
				wp_send_json_success( $result );
			}
		
			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );
		
			if ( ! is_wp_error( $activated ) ) {
				$result['is_activated'] = true;
				$result['msg']          = 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'pie-register' ) : esc_html__( 'Addon installed & activated.', 'pie-register' );
		
				wp_send_json_success( $result );
			}
		
			// Fallback error just in case.
			wp_send_json_error( $result );
		}
		function pie_column_invite( $column ) {
			$column['invitation_code'] = __('Invitation Code','pie-register');
			$column['registration_date'] = __('Registration Date','pie-register');
			return $column;
		}
		function pie_column_invite_value( $val, $column_name, $user_id ) {
			switch($column_name) {
				case 'registration_date' :
					$user_registration_date = date('jS F, Y h:i:s A', strtotime(get_userdata( $user_id)->user_registered)) ;
					return $user_registration_date ? $user_registration_date : '-';
					break;		

				case 'invitation_code' :
					$user_meta_data = get_user_meta($user_id, 'invite_code', true);
					return $user_meta_data ? $user_meta_data : '-';
					break;
		
				   default:
			}
		}
		/*
			*	When user deletion
		*/
		function piereg_user_deletion( $user_id ) {
			
			$subscribtion_method 	= get_user_meta( $user_id, "piereg_user_subscribtion_method", true );
			$subscribtion_id 		= get_user_meta( $user_id, "piereg_user_subscribtion_id", true );
			
			if( !empty($subscribtion_method) && !empty($subscribtion_id) )
			{
				do_action("piereg_delete_subscribtion_on_user_deletion_".$subscribtion_method ,$user_id);
			}
		}
		
		function piereg_register_scripts(){
			wp_register_script('pie_prVariablesDeclaration_script',plugins_url("/assets/js/prVariablesDeclaration.js",__FILE__),false,PIEREGISTER_VERSION);
			wp_register_script('pie_prBackendVariablesDeclaration_script',plugins_url("/assets/js/prBackendVariablesDeclaration.js",__FILE__),false,PIEREGISTER_VERSION);
			wp_register_script('pie_prVariablesDeclaration_script_Footer',plugins_url("/assets/js/prVariablesDeclarationFooter.js",__FILE__),'',PIEREGISTER_VERSION,true);
			wp_register_script('pie_prBackendVariablesDeclaration_script_Footer',plugins_url("/assets/js/prBackendVariablesDeclarationFooter.js",__FILE__),'',PIEREGISTER_VERSION,true);
			wp_register_script('prBackendCustomLogo_script',plugins_url("/assets/js/prBackendCustomLogo.js",__FILE__),'',PIEREGISTER_VERSION,true);
			wp_register_script('pie_datepicker_js',plugins_url("/assets/js/datepicker.js",__FILE__),array('jquery'),PIEREGISTER_VERSION,false);
			wp_register_script('pie_drag_js',plugins_url("/assets/js/drag.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_mCustomScrollbar_js',plugins_url("/assets/js/jquery.mCustomScrollbar.min.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);			
			wp_register_script('pie_mousewheel_js',plugins_url("/assets/js/jquery.mousewheel.min.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_phpjs_js',plugins_url("/assets/js/phpjs.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_registermain_js',plugins_url("/assets/js/pie-register-main.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_alphanum_js',plugins_url("/assets/js/jquery.alphanum.js",__FILE__),array('jquery'),PIEREGISTER_VERSION,false);
			wp_register_script('pie_validation_js',plugins_url("/assets/js/piereg_validation.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_password_checker',plugins_url("/assets/js/pie_password_checker.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_recpatcha_script','//www.google.com/recaptcha/api.js?onload=prRecaptchaCallBack','','',true);	// &render=explicit removed
			
			wp_register_style( 'pie_front_css', plugins_url("/assets/css/front.css",__FILE__),false,PIEREGISTER_VERSION, "all" );			
			wp_register_style( 'pie_wload_css', plugins_url("/assets/css/jquery.wload.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_style_css', plugins_url("/assets/css/style.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_ui_css', plugins_url("/assets/css/pie_ui.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_jquery_ui', plugins_url("/assets/css/jquery-ui-theme.css",__FILE__),false,'1.12.1', "all" );
			wp_register_style( 'pie_font_awesome', plugins_url("/assets/css/font-awesome.min.css",__FILE__),false,'4.7.0', "all" );
			
			// For notices
			wp_enqueue_style('pie_notice_cs',plugins_url("/assets/css/pie_notice.css",__FILE__),false,PIEREGISTER_VERSION, "all");
		}
		function piereg_backendregister_scripts(){
			//Styles
			wp_register_style( 'pie_admin_css', plugins_url("/assets/css/admin.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_restrict_widget_css', plugins_url("/restrict_widget/restrict_widget_css.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_go_pro_menu_css', plugins_url("/assets/css/admin-go-pro-styles.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_admin_about_us_menu_css', plugins_url("/assets/css/admin-about-us.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_admin_about_us_slick_css', plugins_url("/assets/css/slick.min.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style( 'pie_mCustomScrollbar_css', plugins_url("/assets/css/jquery.mCustomScrollbar.css",__FILE__),false,PIEREGISTER_VERSION, "all" );
			wp_register_style('pie_notification_css', plugins_url("assets/css/pie_notifications.css",__FILE__), false, PIEREGISTER_VERSION, "all");
			//Scripts
			wp_register_script('pie_ckeditor',plugins_url("/assets/lib/ckeditor/ckeditor.js",__FILE__),'jquery','1.0',false);
			wp_register_script('pie_wload_js',plugins_url("/assets/js/jquery.wload.js",__FILE__),'jquery','1.0',false);
			wp_register_script('pie_restrict_widget_script',plugins_url("/restrict_widget/pie_register_widget_script.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_admin_about_us_menu_js',plugins_url("/assets/js/admin-about-us.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_admin_about_us_slick_js',plugins_url("/assets/js/slick.min.js",__FILE__),'jquery',PIEREGISTER_VERSION,false);
			wp_register_script('pie_notification_js',plugins_url("assets/js/pie_notifications.js", __FILE__), array('jquery'), PIEREGISTER_VERSION, true);
		}
		function getVauleOrEmpty($string=false){
			return (isset($string) && !empty($string))?$string:'';
		}
		function print_multi_lang_script_vars(){
			global $piereg_global_options;
			$opt 			= $piereg_global_options;
			$fields_id 		= get_option("piereg_form_fields_id");
			$count 			= 0;
			$fields_data 	= $fields_data_filtered = array();
			$form_ids 		= array();

			
			for($a=1;$a<=$fields_id;$a++){
				$option = get_option("piereg_form_field_option_".$a);
				if( !empty($option) && is_array($option) && isset($option['Id']) && trim($option['Status']) != "" && $option['Status'] == "enable" && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) )
				{
					array_push($form_ids, 'reg_form_'.$option['Id']);
					$fields_data 	= maybe_unserialize(get_option("piereg_form_fields_".$option['Id']));
					$the_cap_field = "captcha";
					$fields_data = is_array($fields_data) ? $fields_data : [$fields_data];
					$fields_data_filtered = array_filter($fields_data, function($el) use ($the_cap_field) {
						return ( isset($el['type']) && ($el['type'] == $the_cap_field) );
					});
					$reg_forms_theme['reg_form_'.$option['Id']] = $fields_data_filtered;
					$count++;
				}
			}
						
			$is_widgetTheme = $not_widgetTheme = (isset($opt['piereg_recapthca_skin_login']) && !empty($opt['piereg_recapthca_skin_login']))?$opt['piereg_recapthca_skin_login']:"red";
			$is_forgot_widgetTheme = $not_forgot_widgetTheme = (isset($opt['piereg_recapthca_skin_forgot_pass']) && !empty($opt['piereg_recapthca_skin_forgot_pass']))?$opt['piereg_recapthca_skin_forgot_pass']:"red";
			$mathCaptchaOperator = rand(0,1);
			////1 for add(+)
			////0 for subtract(-)
			$mathCaptchaResult = 0;
			if($mathCaptchaOperator == 1){	
				$mathCaptchaStart = rand(1,9);
				$mathCaptchaEnd = rand(5,20);
				$mathCaptchaResult = $mathCaptchaStart + $mathCaptchaEnd;
				$mathCaptchaOperator = "+";
			}
			else{
				$mathCaptchaStart = rand(50,30);
				$mathCaptchaEnd = rand(5,20);
				$mathCaptchaResult = $mathCaptchaStart - $mathCaptchaEnd;
				$mathCaptchaOperator = "-";
			}
				
			$mathCaptchaResult1 = $mathCaptchaResult + 12;
			$mathCaptchaResult2 = $mathCaptchaResult + 786;
			$mathCaptchaResult3 = $mathCaptchaResult - 5;
			$mathCaptchaResult1 = base64_encode($mathCaptchaResult1);
			$mathCaptchaResult2 = base64_encode($mathCaptchaResult2);
			$mathCaptchaResult3 = base64_encode($mathCaptchaResult3);
			$matchCapImage_name = rand(0,10);
			$matchCapColor = array('rgba(0, 0, 0, 0.6)','rgba(153, 31, 0, 0.9)','rgba(64, 171, 229,0.8)','rgba(0, 61, 21, 0.8)','rgba(0, 0, 204, 0.7)','rgba(0, 0, 0, 0.5)','rgba(198, 81, 209, 1.0)','rgba(0, 0, 999, 0.5)','rgba(0, 0, 0, 0.5)','rgba(0, 0, 0, 0.5)','rgba(255, 63, 143, 0.9)');
			
			$matchCapHTML = $mathCaptchaStart." ".$mathCaptchaOperator." ".$mathCaptchaEnd . " = ";
			$pie_payment_methods_data = array();
			
			if(!empty($this->pie_payment_methods_dat) && count($this->pie_payment_methods_dat) > 0){				
				foreach($this->pie_payment_methods_dat as $data){
					$pie_payment_methods_data[$data['method']]['payment']	= $data['payment'];
					$pie_payment_methods_data[$data['method']]['image']		= $data['image'];
				}
			}
			
			$isSocialLoginRedirectOnLogin = ( ( isset($opt['social_site_popup_setting']) && $opt['social_site_popup_setting'] == 1 )  && (isset($_POST['social_site']) && $_POST['social_site'] == "true"))?true:false;
			$this->pie_pr_dec_vars_array = array(
				'ajaxURL'						=> admin_url('admin-ajax.php'),
				'dateY'							=> date_i18n("Y"),//__( 'NiceString', 'pie-register' )
				'piereg_startingDate'			=> $opt['piereg_startingDate'],
				'piereg_endingDate'				=> $opt['piereg_endingDate'],
				'pass_strength_indicator_label'	=> $this->getVauleOrEmpty($opt['pass_strength_indicator_label']),
				'pass_very_weak_label'			=> $this->getVauleOrEmpty($opt['pass_very_weak_label']),
				'pass_weak_label'				=> $this->getVauleOrEmpty($opt['pass_weak_label']),
				'pass_medium_label'				=> $this->getVauleOrEmpty($opt['pass_medium_label']),
				'pass_strong_label'				=> $this->getVauleOrEmpty($opt['pass_strong_label']),
				'pass_mismatch_label'			=> $this->getVauleOrEmpty($opt['pass_mismatch_label']),
				'ValidationMsgText1'					=> __("none","pie-register"),
				'ValidationMsgText2'					=> __("* This field is required","pie-register"),
				'ValidationMsgText3'					=> __("* Please select an option","pie-register"),
				'ValidationMsgText4'					=> __("* This checkbox is required","pie-register"),
				'ValidationMsgText5'					=> __("* Both date range fields are required","pie-register"),
				'ValidationMsgText6'					=> __("* Field must equal test","pie-register"),
				'ValidationMsgText7'					=> __("* Invalid ","pie-register"),
				'ValidationMsgText8'					=> __("Date Range","pie-register"),
				'ValidationMsgText9'					=> __("Date Time Range","pie-register"),
				'ValidationMsgText10'					=> __("* Minimum ","pie-register"),
				'ValidationMsgText11'					=> __(" characters required","pie-register"),
				'ValidationMsgText12'					=> __("* Maximum ","pie-register"),
				'ValidationMsgText13'					=> __(" characters allowed","pie-register"),
				'ValidationMsgText14'					=> __("* You must fill-in one of the following fields","pie-register"),
				'ValidationMsgText15'					=> __("* Minimum value is ","pie-register"),
				'ValidationMsgText16'					=> __("* Date prior to ","pie-register"),
				'ValidationMsgText17'					=> __("* Date past ","pie-register"),
				'ValidationMsgText18'					=> __(" options allowed","pie-register"),
				'ValidationMsgText19'					=> __("* Please select ","pie-register"),
				'ValidationMsgText20'					=> __(" options","pie-register"),
				'ValidationMsgText21'					=> __("* Fields do not match","pie-register"),
				'ValidationMsgText22'					=> __("* Invalid credit card number","pie-register"),
				'ValidationMsgText23'					=> __("* Invalid phone number","pie-register"),
				'ValidationMsgText24'					=> __("* Invalid phone number. Allowed format: (xxx)xxx-xxxx","pie-register"),
				'ValidationMsgText25'					=> __("* Minimum 10 digits starting with area code without the '+' sign.","pie-register"),
				'ValidationMsgText26'					=> __("* Invalid email address","pie-register"),
				'ValidationMsgText27'					=> __("* Not a valid integer","pie-register"),
				'ValidationMsgText28'					=> __("* Invalid number","pie-register"),
				'ValidationMsgText29'					=> __("* Invalid month","pie-register"),
				'ValidationMsgText30'					=> __("* Invalid day","pie-register"),
				'ValidationMsgText31'					=> __("* Invalid year","pie-register"),
				'ValidationMsgText32'					=> __("* Invalid file extension","pie-register"),
				'ValidationMsgText33'					=> __("* Invalid date, must be in YYYY-MM-DD format","pie-register"),
				'ValidationMsgText34'					=> __("* Invalid IP address","pie-register"),
				'ValidationMsgText35'					=> __("* Invalid URL","pie-register"),
				'ValidationMsgText36'					=> __("* Numbers only","pie-register"),
				'ValidationMsgText37'					=> __("* Letters only","pie-register"),
				'ValidationMsgText38'					=> __("* No special characters allowed","pie-register"),
				'ValidationMsgText39'					=> __("* This username is not available","pie-register"),
				'ValidationMsgText40'					=> __("* Validating, please wait","pie-register"),
				'ValidationMsgText41'					=> __("* This username is available","pie-register"),
				'ValidationMsgText42'					=> __("* This username is not available","pie-register"),
				'ValidationMsgText43'					=> __("* Validating, please wait","pie-register"),
				'ValidationMsgText44'					=> __("* This name is not available","pie-register"),
				'ValidationMsgText45'					=> __("* This name is available","pie-register"),
				'ValidationMsgText46'					=> __("* Validating, please wait","pie-register"),
				'ValidationMsgText47'					=> __("* This name is not available","pie-register"),
				'ValidationMsgText48'					=> __("* Please enter HELLO","pie-register"),
				'ValidationMsgText49'					=> __("* Invalid Date","pie-register"),
				'ValidationMsgText50'					=> __("* Invalid Date or Date Format","pie-register"),
				'ValidationMsgText51'					=> __("Expected Format: ","pie-register"),
				'ValidationMsgText52'					=> __("mm/dd/yyyy hh:mm:ss AM|PM or ","pie-register"),
				'ValidationMsgText53'					=> __("yyyy-mm-dd hh:mm:ss AM|PM","pie-register"),
				'ValidationMsgText54'					=> __("* Invalid Username","pie-register"),
				'ValidationMsgText55'					=> __("* Invalid File","pie-register"),
				'ValidationMsgText56'					=> __("* Maximum value is ","pie-register"),
				'ValidationMsgText57'					=> __("* Letters only","pie-register"),
				'ValidationMsgText58'					=> __("* Only Alphanumeric characters are allowed","pie-register"),
				'ValidationMsgText61'					=> __("* Invalid file size","pie-register"),
				'ValidationMsgText59'					=> __("Delete","pie-register"),
				'ValidationMsgText60'					=> __("Edit","pie-register"),
				'piereg_recaptcha_type'					=> isset($opt['piereg_recaptcha_type']) ? $opt['piereg_recaptcha_type'] : 'v2',
				'reCaptcha_public_key'					=> isset($opt['captcha_publc']) ? $opt['captcha_publc'] : '',
				'reCaptchaV3_public_key'				=> isset($opt['captcha_publc_v3']) ? $opt['captcha_publc_v3'] : '',
				'reCaptcha_language'					=> isset($opt['piereg_recaptcha_language']) ? $opt['piereg_recaptcha_language'] : "en",
				'prRegFormsIds'							=> $form_ids,
				'not_widgetTheme'						=> $not_widgetTheme,
				'is_widgetTheme'						=> $is_widgetTheme,
				'not_forgot_widgetTheme'				=> $not_forgot_widgetTheme,
				'is_forgot_widgetTheme'					=> $fields_data_filtered,
				'reg_forms_theme'						=> $fields_data,
				'matchCapResult1'						=> $mathCaptchaResult1,
				'matchCapResult2'						=> $mathCaptchaResult2,
				'matchCapResult3'						=> $mathCaptchaResult3,
				'matchCapColors'						=> $matchCapColor,
				//'prMathCaptchaID'						=> $fff,
				'matchCapImgColor'						=> $matchCapColor[$matchCapImage_name],
				'matchCapImgURL'						=> plugins_url('/assets/images/math_captcha/'.$matchCapImage_name.'.png',__FILE__),
				'matchCapHTML'							=> $matchCapHTML,
				'is_socialLoginRedirect'				=> $this->pie_is_social_renew_account_call,
				'socialLoginRedirectRenewAccount'		=> $this->pie_ua_renew_account_url,
				'isSocialLoginRedirectOnLogin'			=> $isSocialLoginRedirectOnLogin,
				'socialLoginRedirectOnLoginURL'			=> $this->pie_after_login_page_redirect_url,
				'pie_payment_methods_data'				=> $pie_payment_methods_data,
				'prTimedFieldVal'						=> date("y-m-d H:i:s"),
				'process_submission'					=> __("Processing...","pie-register")
											);
			wp_localize_script( 'pie_prVariablesDeclaration_script', 'pie_pr_dec_vars', $this->pie_pr_dec_vars_array );

			wp_enqueue_script('jquery-ui-progressbar');
			wp_enqueue_script( 'pie_prVariablesDeclaration_script' );
			wp_enqueue_script( 'pie_prVariablesDeclaration_script_Footer' );
			
		}
		//Function print_multi_lang_backend_script_vars()
		//Declares Variables that we need on WP Backend
		//Uses wp_localize to translate the variables
		function print_multi_lang_backend_script_vars(){
			global $piereg_global_options,$wp_roles,$hook_suffix;
			$payment_gateways_html = "";
			$payment_gateways_list = $this->payment_gateways_list();
			if(isset($payment_gateways_list) && is_array($payment_gateways_list) && !empty($payment_gateways_list)){
				foreach($payment_gateways_list as $pgKey=>$pgval){
					$payment_gateways_html .= '<label for="allow_payment_gateways_'.$pgKey.'" class="required piereg-payment-list"><input name="field[%d%][allow_payment_gateways][]" id="allow_payment_gateways_'.$pgKey.'" value="'.$pgKey.'" type="checkbox" checked="checked" class="checkbox_fields">'.$pgval.'</label>';
				}
			}
			$roles = $wp_roles->roles;
			$user_role_option	= "";
			$user_role_object	= "";
			$user_role_array	= array();
			if(isset($roles) && is_array($roles) && !empty($roles)){
				foreach($roles as $key=>$value){
					$user_role_array[$key] = $value['name'];
				}
			}
			$user_role_object = json_encode($user_role_array);
			$defaultMeta = $this->getDefaultMeta();
			$fields_data = $this->getCurrentFields();
			if(!is_array($fields_data) || sizeof($fields_data) == 0) {	
				$fields_data 	= get_option( 'pie_fields_default' );
			}
			$fillvalKey		= array();
			$fillvalValue	= array();
			$fillvalNum		= 0;			
			$options 		= get_option(OPTION_PIE_REGISTER);
			
			foreach($fields_data as $k=>$field){
				
				if( ($field['type'] == "honeypot") || $field['type']=="submit" || $field['type']=="" || $field['type']=="form" || ($field['type']=="invitation" && $options["enable_invitation_codes"]=="0" || $field['type'] == "hcaptcha") ){
					continue;
				}
				if($field['type'] == "url" || $field['type'] == "aim" || $field['type'] == "yim" || $field['type'] == "jabber" || $field['type'] == "description"){
					$field['type'] = "default";
				}
				
				if( $field['type'] == "html" )
				{
					$field['html']	= html_entity_decode($field['html']);
				}
				
				if(isset($field['desc']) && !empty($field['desc']))
				{
					$field['desc'] = html_entity_decode($field['desc']);
				}
				
				$fillvalNum++;
				array_push($fillvalKey,$field['id']);
				array_push($fillvalValue,serialize($field));
			}
			$this->pie_pr_backend_dec_vars_array = array(
				'ajaxURL'				=> admin_url('admin-ajax.php'),
				'hook_suffix'			=> $hook_suffix,
				'wp_content_url'		=> content_url(),
				'wp_home_url'			=> home_url(),
				'wp_site_url'			=> site_url(),
				'wp_admin_url'			=> admin_url(),
				'wp_includes_url'		=> includes_url(),
				'wp_plugins_url'		=> plugins_url(),
				'wp_pie_register_url'	=> PIEREG_PLUGIN_URL,
				'payment_gateways_list' => $payment_gateways_html,
				'user_default_role'		=> get_option("default_role"),
				'current_date'			=> date_i18n("Y"),
				'startingDate'			=> $piereg_global_options['piereg_startingDate'],
				'endingDate'			=> $piereg_global_options['piereg_endingDate'],
				'user_roles_array'		=> $user_role_array,
				'user_roles_object'		=> $user_role_object,
				'plsSelectForm'			=> __("Please select a form","pie-register"),
				'isPRFormEditor'		=> (isset($_GET['page']) && in_array($_GET['page'],array('pie-register','pr_new_registration_form')))?true:false,
				'inValidFields'			=> __('Invalid Fields','pie-register'),
				'display_hints'			=> $piereg_global_options['display_hints'],
				'defaultMeta'			=> $defaultMeta,
				'appFormCondFldsStart'	=> '<div class="advance_fields"><label for="form_notification">'.__("Verifications","pie-register").'</label><select name="field[form][notification][]" id="form_notification"  class="form_notification" style="width:100px;" ><option value="1" selected="selected">'.__("Admin Verification","pie-register").'</option><option value="2">'.__("E-mail Verification","pie-register").'</option><option value="3">'.__("E-mail & Admin Verification","pie-register").'</option></select><span style="color:#fff;"> '.__("this field if","pie-register").' </span><select data-selected_field="form_selected_field" name="field[form][selected_field][]" class="form_selected_field piereg_all_field_dropdown" style="width:100px;">',
				'appFormCondFldsEnd'	=> '</select>&nbsp;<select id="form_field_rule_operator" name="field[form][field_rule_operator][]" class="field_rule_operator_select" style="width:auto;"><option selected="selected" value="==">'.(__("equal","pie-register")).'</option><option value="!=">'.(__("not equal","pie-register")).'</option><option value="empty">'.(__("empty","pie-register")).'</option><option value="not_empty">'.(__("not empty","pie-register")).'</option><option value=">">'.(__("greater than","pie-register")).'</option><option value="<">'.(__("less than","pie-register")).'</option><option value="contains">'.(__("contains","pie-register")).'</option><option value="starts_with">'.(__("starts with","pie-register")).'</option><option value="ends_with">'.(__("ends with","pie-register")).'</option></select>&nbsp;<div class="wrap_cond_value"><input type="text" name="field[form][conditional_value][]" id="form_conditional_value" class="input_fields conditional_value" placeholder="Enter Value"></div>&nbsp;<a href="javascript:;" class="delete_conditional_value_fields" style="color:white;font-size: 13px;margin-left: 2px;">x</a></div>',
				'fillvalKey'			=> $fillvalKey,
				'fillvalValue'			=> $fillvalValue,
				'fillvalNo'				=> (max(array_filter(array_keys($fields_data), 'is_numeric'))+1),
				'fillvalNum'			=> $fillvalNum,
				'block_wp_login'		=> $piereg_global_options['block_wp_login'],
				'selectLogoText'		=> 'Upload/Select Logo',
				'mediaUploadURL'		=> admin_url('media-upload.php')
				
			);
			wp_localize_script( 'pie_prBackendVariablesDeclaration_script', 'pie_pr_backend_dec_vars', $this->pie_pr_backend_dec_vars_array );
			wp_enqueue_script( 'pie_prBackendVariablesDeclaration_script' );
			wp_enqueue_script( 'pie_prBackendVariablesDeclaration_script_Footer' );
		}
		// function pie_admin_enqueu_scripts
		//Will be replace with print_multi_lang_backend_script_vars();
		function pie_admin_enqueu_scripts(){
			$this->print_multi_lang_backend_script_vars();
		}
		function print_multi_captcha_skin() {
			
			$settings	=	get_option(OPTION_PIE_REGISTER);
			$publickey	=	$settings['captcha_publc'];
			
			$fields_id = get_option("piereg_form_fields_id");
			$count = 0;
			$form_ids = array();
			for($a=1;$a<=$fields_id;$a++)
			{
				$option = get_option("piereg_form_field_option_".$a);
				if( !empty($option) && is_array($option) && isset($option['Id']) && trim($option['Status']) != "" && $option['Status'] == "enable" && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) )
				{
					array_push($form_ids, 'reg_form_'.$option['Id']);
					$count++;
				}
			}
			
			$captcha_login_script = false;
			$captcha_forgot_script = true;
			$captcha_reg_script = false;
			if($settings['captcha_in_login_value'] == 1 && $settings['capthca_in_login'] == 3 ){
				$captcha_login_script = true;
			}
			if($settings['captcha_in_forgot_value'] == 1 && $settings['capthca_in_forgot_pass'] == 3 ){
				$captcha_forgot_script = true;
			}
			if($count > 0){
				$captcha_reg_script = true;
			}
			
				if($captcha_login_script || $captcha_reg_script || $captcha_forgot_script){}
		}
		
		function add_admin_body_class( $classes ) {
			$classes .= ' pieregister-page';
			return $classes;			 
		}

		function pie_backend_enqueue_scripts($hook_s){
			global $piereg_global_options;
			$pr_backend_hook_suffixes = array('pie-register','pr_new_registration_form','pie-notifications','pie-invitation-codes','pie-bulk-emails','pie-gateway-settings','pie-black-listed-users','pie-settings','pie-import-export','unverified-users','pie-help','post.php','edit.php','post-new.php');
			$pr_backend_hook_suffixes = apply_filters('pr_backend_hook_suffixes',$pr_backend_hook_suffixes);
			$is_pr_page = array_filter($pr_backend_hook_suffixes, function($el) use ($hook_s) {
				return ( stripos($hook_s,$el) !== false );
			});
			if(empty($is_pr_page)){
				return false;
			}
			
			add_filter( 'admin_body_class', array($this,'add_admin_body_class'));			
			if(in_array('pie-settings', $is_pr_page))
			{
				wp_enqueue_script('prBackendCustomLogo_script');
			}
			
			///////////// So We are on PR pages /////////////////
			//Enqueue Styles
			wp_enqueue_style('pie_admin_css');
			//wp_enqueue_style('pie_jqueryui_css');
			wp_enqueue_style('pie_mCustomScrollbar_css');
			wp_enqueue_style('pie_style_css');
			#wp_enqueue_style('pie_tooltip_css');
			wp_enqueue_style('pie_ui_css');
			wp_enqueue_style('pie_validation_css');

			//Now We Enqueue Variables			
			$this->print_multi_lang_script_vars();
			$this->pie_admin_enqueu_scripts();
			
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('pie_datepicker_js');
			wp_enqueue_script('pie_drag_js');
			wp_enqueue_script('pie_mCustomScrollbar_js');
			wp_enqueue_script('pie_mousewheel_js');
			wp_enqueue_script('pie_phpjs_js');
			wp_enqueue_script('pie_registermain_js');
			// wp_enqueue_script('pie_admin_about_us_menu_js');
			
			if(in_array('pie-settings', $is_pr_page)) {
				wp_enqueue_script('prBackendCustomLogo_script');
			}
			
			//JQuery UI Effects
				wp_enqueue_script('jquery-ui-droppable');
				wp_enqueue_script('jquery-ui-tooltip');
				wp_enqueue_script('jquery-ui-tabs');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('jquery-ui-datepicker');	
				wp_enqueue_script('jquery-ui-draggable');
				/* wp_enqueue_script('jquery-ui-dialog'); */

				wp_enqueue_style('pie_jquery_ui'); // jquery-ui-theme-smoothness
				
			wp_enqueue_script('pie_alphanum_js');
			wp_enqueue_script('pie_validation_js');
			wp_enqueue_script("pie_ckeditor");
			wp_enqueue_style('pie_font_awesome');
		}

		function piereg_frontend_assets_header(){
			if(is_front_page()){
				wp_enqueue_script('pie_dialog_js',plugins_url("/assets/js/dialog.js",__FILE__),array('jquery'),PIEREGISTER_VERSION,false);
				wp_enqueue_style('pie_dialog_css',plugins_url("/assets/css/dialog.css",__FILE__),false,PIEREGISTER_VERSION,"all");
				wp_enqueue_script('jquery-ui-dialog');
			}

			if ( ! is_singular() ) {
				return;
			}
	
			global $post;
	
			if (
				( has_shortcode( $post->post_content, 'pie_register_form' ) || has_shortcode( $post->post_content, 'pie_register_login' ) || has_shortcode( $post->post_content, 'pie_register_forgot_password' ) || has_shortcode( $post->post_content, 'pie_register_profile' ) ) || has_shortcode( $post->post_content, 'pie_register_search_profile_form' ) || has_shortcode( $post->post_content, 'pie_register_search_profile_result' ) || ( function_exists( 'has_block' ) && has_block( 'pie-register/form-selector' ) )
			) {
				$this->pie_frontend_enqueu_scripts();
			}
		}

		function piereg_frontend_assets_footer(){
			if ( empty( $this->piereg_forms_per_page ) ) {
				return;
			}
			
			$this->pie_frontend_enqueu_scripts();
		}

		function pie_frontend_enqueu_scripts(){
			global $piereg_global_options;
			$this->print_multi_lang_script_vars();

			if(isset($piereg_global_options['outputcss']) && $piereg_global_options['outputcss'] == 1){
				wp_enqueue_style( 'pie_front_css' );
				wp_enqueue_style( 'pie_validation_css' );
			}
			
				// LOAD RECAPTCHA SCRIPT IS ENABLED OR ADDED IN REGISTRATION FORM(S) - START
				$captcha_login_script 		= false;
				$captcha_forgot_script 		= false;
				$captcha_reg_script 		= false;
				
				// Check if Captcha is to be added in the login form
				if($piereg_global_options['captcha_in_login_value'] == 1 && $piereg_global_options['capthca_in_login'] == 3 ){

					$captcha_login_script 	= true;
				}
				// Check if Captcha is to be added in the reset password form
				if($piereg_global_options['captcha_in_forgot_value'] == 1 && $piereg_global_options['capthca_in_forgot_pass'] == 3 ){
					$captcha_forgot_script 	= true;
				}
				
				$PR_forrms_fields_id = get_option("piereg_form_fields_id");
				for($a=1;$a<=$PR_forrms_fields_id;$a++)
				{
					$_form_fields	= unserialize(get_option('piereg_form_fields_'.$a));
					$_form_option 	= get_option("piereg_form_field_option_".$a);
					
					if( !is_array($_form_fields)  ) continue;
					
					if( !isset($_form_option['IsDeleted']) || trim($_form_option['IsDeleted']) != 1 )
					{
						foreach($_form_fields as $key=>$field)
						{
							if($field['type'] == 'captcha')
							{
								$captcha_reg_script = true;
								break;
							}
						}
					}					
					if($captcha_reg_script) break;					
				}
				
				if($captcha_login_script || $captcha_reg_script || $captcha_forgot_script){
					if($piereg_global_options['piereg_recaptcha_type'] == 'v2'){
						wp_enqueue_script("pie_recpatcha_script" );
					}elseif($piereg_global_options['piereg_recaptcha_type'] == 'v3'){
						$recaptcha_sitekey  = isset($piereg_global_options['captcha_publc_v3']) ? $piereg_global_options['captcha_publc_v3'] : "";
						$recaptcha_language = isset($piereg_global_options['piereg_recaptcha_language']) ? $piereg_global_options['piereg_recaptcha_language'] : "";
						wp_enqueue_script('pie_recpatchav3_script','//www.google.com/recaptcha/api.js?render='.$recaptcha_sitekey.'&hl='.$recaptcha_language,'','',true);
					}
				}
				// LOAD RECAPTCHA SCRIPT IS ENABLED OR ADDED IN REGISTRATION FORM(S) - END
			
			wp_enqueue_script("pie_datepicker_js" );
			wp_enqueue_script("pie_alphanum_js");
			wp_enqueue_script("pie_validation_js");
			wp_enqueue_script('password-strength-meter',false,array('jquery','zxcvbn-async'),'',true);
			wp_enqueue_script('pie_password_checker',false,array('password-strength-meter'),'',true);

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');	
		}
				
		function check_if_role_admin()
		{
			$is_admin 			= false;
			
			$current_user 		= wp_get_current_user();
			if (user_can( $current_user, 'administrator' )) {
			  	$is_admin	= true;
			}
			
			return $is_admin;
		}
		
		function piereg_registration_form($attributes="") {
			$this->piereg_ssl_template_redirect();
			
			$id 			= !empty($form_attr) && isset($form_attr['id']) ? $form_attr['id'] : 0;
			$title 			= !empty($form_attr) && isset($form_attr['title']) ? $form_attr['title'] : "true" ;
			$description 	= !empty($form_attr) && isset($form_attr['description']) ? $form_attr['description'] : "true" ;
			$is_preview 	= false;
			
			//// **** ONLY ADMINISTRATOR CAN VIEW FORM PREVIEW ELSE IT WILL SHOW PAGE CONTENT **** ////
			$show_preview_form 	= $this->check_if_role_admin();
			$elementor_preview  = isset($_GET['action']) && $_GET['action'] == 'elementor' ? true : false;
			//  For Form with Gutenberg Preview
			$gutenberg_preview  = isset($_GET['context']) && $_GET['context'] == 'edit' ? true : false;
			// For WPBakery Frontend Preview
			$WPBakery_preview  = isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true' ? true : false;

			if( (isset($_GET['pr_preview']) || $elementor_preview ) && $show_preview_form ){
				$is_preview 		= true;
				$prFormId 			= isset($_GET['prFormId']) ? intval(trim($_GET['prFormId'])) : '';
				$preview_form_id 	= 0;
				
				if(isset($_GET['prFormId']) && $prFormId > 0){
					$preview_form_id 	= $prFormId;
				}
				
			}
			
			$use_free_form 		= false;
			if( !is_array($attributes) ){
				$use_free_form = true;
			}
			
			if(is_array($attributes) || $is_preview || $use_free_form ){
				
				if( $use_free_form && !$is_preview )
				{
					$id = $this->regFormForFreeVers();
				}
				else {
					
					if(is_array($attributes)) extract($attributes);					
					
					if( $id != $this->regFormForFreeVers() ) {
						$id = false;
					}
					
				}
				
				if($is_preview && $preview_form_id > 0){
					$id = $preview_form_id;
				}
				
				if( intval($id) != 0 )
				{
					$check_form_in_db = get_option("piereg_form_fields_".((int)$id));
					if($check_form_in_db == false || trim($check_form_in_db) == "")
					{
						$id = false;
					}else{
						$check_form_in_db = get_option("piereg_form_field_option_".((int)$id));
						if( ($check_form_in_db['Status'] == "enable" && (!isset($check_form_in_db['IsDeleted']) || trim($check_form_in_db['IsDeleted']) != 1) ) || $is_preview){
							if (is_user_logged_in() && !is_admin() && !$is_preview && !$this->is_pr_preview && !$gutenberg_preview && !$WPBakery_preview ) {
								$global_options = $this->get_pr_global_options();
									
								return apply_filters("pie_reg_form_logged_in_msg",sprintf(__("<p>Already logged in, Click <a href='%s'>here</a> to edit profile.<p>","pie-register"),$this->get_page_uri($global_options["alternate_profilepage"],"edit_user=1")));
							} 
							if(!$is_preview){
								$check_form_in_db['Views'] = ( intval($check_form_in_db['Views'])+1 );
								update_option("piereg_form_field_option_".((int)$id),$check_form_in_db);
								$this->set_pr_stats("register","view");
							}
							return $this->showForm($id,$title,$description);
						}else{
							return __("This form is disabled by the administrator.","pie-register");
						}
					}
				}else{
					$id = false;
				}
				
				if($id == false){
					return __("Incorrect shortcode used.","pie-register");
					$this->pr_error_log("Incorrect shortcode used.".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
			}
			else{
				return __("Incorrect shortcode used.","pie-register");
				$this->pr_error_log("Incorrect shortcode used.".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
		}

		// ver - 3.6.3
		function piereg_user_profile_pic(){
			$this->piereg_ssl_template_redirect();

			global $wpdb;
			$user_id = get_current_user_id();
			$data    = '';

			if($user_id){
				$sql_query = "SELECT * FROM {$wpdb->prefix}usermeta WHERE `meta_key` LIKE '%pie_profile_pic_%' AND `user_id`={$user_id}";

				$result = $wpdb->get_results($sql_query);

				if(isset($result[0]) && $result[0]->meta_value){
					$imgPath = $result[0]->meta_value;
				}else{
					$imgPath = get_avatar_url($user_id);
				}

				$data .= '<div class="show-profile-img">';
					$data .= '<div class="file-wrapper"><img src="'.$imgPath.'" alt="'.__('User Profile Picture',"pie-register").'" />';
					$data .= '</div>';
				$data .= '</div>';

				return $data;
			}else{
				return __("Incorrect shortcode used.","pie-register");
				$this->pr_error_log("Incorrect shortcode used.".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
		}
		
		function modify_all_notices($notice)
		{
			$Start_notice = "";/*Write your message*/
			$End_notice = "";/*Write your message*/
			return $Start_notice.$notice.$End_notice;
		}
		
		function print_Rpr_licenseKey_errors()
		{
			if(isset($_POST['PR_license_notice']))
				return $_POST['PR_license_notice'];
		}
		function initPieWidget()
		{
			register_widget( 'Pie_Register_Widget' );
			register_widget( 'Pie_Login_Widget' );
			register_widget( 'Pie_Forgot_Widget' );	
		}
		
		//Plugin Menu Link
		function add_action_links( $links, $file ) 
		{
			 if ( $file != plugin_basename( __FILE__ ))
				return $links;
			
			$links[] = '<a style="color:#13ad11;" target="_blank" href="https://pieregister.com/plan-and-pricing/">'.__("Go Premium","pie-register").'</a>';
			$links[] = '<a href="'. get_admin_url(null, 'admin.php?page=pie-settings') .'">'.(__("Settings","pie-register")).'</a>';
			return $links;
		}
				
		function piereg_after_setup_theme(){
			if(isset($_POST['log']) && isset($_POST['pwd'])){
				$this->checkLogin();
			}
		}
		
		/*
			*	Restrict Post / Page Content
		*/
		function piereg_template_restrict($post_object){
			/*
				*	Get Options
			*/
			
			$option = get_post_meta($post_object->ID,"_piereg_post_restriction");
			/*
				*	Get Global Options
			*/
			$global_options = $this->get_pr_global_options();
			
			$this->post_block_content = "";
			$piereg_post_visibility_var = ((isset($option[0]["piereg_post_visibility"]) && $option[0]["piereg_post_visibility"] != "")?$option[0]["piereg_post_visibility"]:"");
			if ( is_array($piereg_post_visibility_var) ) 
			{
				foreach ($piereg_post_visibility_var as $key => $val)
				{
					$visible =  $this->get_post_visibility($val);

					if (!$visible)
					{
						break;
					}
				}
			}
			else
			{
				$visible =  $this->get_post_visibility($piereg_post_visibility_var);
			}
			/* Restrict for Users */
			if(!isset($option[0]["piereg_post_visibility"])){
				return $post_object;
			}
			//if Visibility status is default then return
			if( is_array($option[0]['piereg_post_visibility']) && in_array('default',$option[0]['piereg_post_visibility']) || !is_array($option[0]['piereg_post_visibility']) && $option[0]['piereg_post_visibility'] == "default")
			{
				return $post_object;
			}
			if( isset($option[0]) && $visible ){
				
				////0 = Redirect
				////1 = Block Content
				
				$page_object = get_queried_object();
				$page_id     = get_queried_object_id();
				
				if(($page_id == $post_object->ID) && isset($option[0]['piereg_restriction_type']) && $option[0]['piereg_restriction_type'] == 0)
				{
					//1st Redirect URL and 2nd Redirect Page
					// Current Page URL
					$pv_sslport	= 443; 
					$pv_URIprotocol = isset($_SERVER["HTTPS"]) ? (($_SERVER["HTTPS"]==="on" || $_SERVER["HTTPS"]===1 || $_SERVER["SERVER_PORT"]===$pv_sslport) ? "https://" : "http://") :  (($_SERVER["SERVER_PORT"]===$pv_sslport) ? "https://" : "http://");
					
					$redirect_to = "redirect_to=".$pv_URIprotocol.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ;
					
					if($option[0]['piereg_redirect_url'] != ""){
						// Redirtect URL 
						$redirect_url = $option[0]['piereg_redirect_url'];
						//Redirect
						$redirect_url = isset($redirect_to) && !empty($redirect_to) ? $this->pie_modify_custom_url($redirect_url , $redirect_to) : $redirect_url; 
						wp_redirect(esc_url_raw($redirect_url));
						exit;
					}else{
						//Redirect Url
						$redirect_url = get_permalink($option[0]['piereg_redirect_page']);
						//Redirect
						$redirect_url = isset($redirect_to) && !empty($redirect_to) ? $this->pie_modify_custom_url($redirect_url , $redirect_to) : $redirect_url; 
						if($redirect_url)
						{
							wp_safe_redirect($redirect_url);
							exit;	
						}
					}
				}
				
				if( $option[0]['piereg_block_content'] != "" )
				{
					$this->post_block_content = "<p>".$option[0]['piereg_block_content']."</p>"; 
					add_filter("the_content",array($this,"restrict_content_post"));
					return $post_object;
				}
			}
			return $post_object;
		}
		/*
			*	Checkl Visibility
		*/
		function get_post_visibility($logic){
			$visible = false;
			if($logic == "default"){
				$visible = true;
			}
			elseif($logic == "after_login"){
				$visible = !is_user_logged_in();
			}
			elseif($logic == "before_login"){
				$visible = is_user_logged_in();
				
				if( $this->check_if_role_admin()	== true ){
					$visible = false;		
				}
			}
			elseif($logic != "" ){
				global $current_user;
				$current_user = wp_get_current_user();
				$current_user_role = (array)$current_user->roles;
				$visible = !in_array( $logic, $current_user_role );
				if($visible && $this->check_if_role_admin()	== true)
				{
					$visible = false;
				}
			} 
			return $visible;
		}
		/*
			*	Restrict Post/Page Content
		*/
		function restrict_content_post($content){
			if($this->post_block_content != "")
			$content = nl2br($this->post_block_content);
			
			return $content;
		}
		/*
			*	Add Meta Box in post / page
			* 	Since version 3.1.3 
			* 	- Custom post types included	
		*/
		function piereg_add_meta_box($postType) {
			$args = array(
			   'public'   => true,
			   //'_builtin' => true
			);
			
			$output = 'names'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'
			
			$screens = get_post_types( $args, $output, $operator );
			unset($screens['attachment']);
			//$screens = array( 'post', 'page' );
			foreach ( $screens as $screen ) {
				add_meta_box(
					'myplugin_sectionid',
					__( 'Pie Register - '.ucwords($screen).' Restriction', 'pie-register' ),
					array($this,'myplugin_meta_box_callback'),
					$screen
				);
			}
		}
	
		public function pie_leave_a_review_request()
		{
			$show_review_notice   = get_option( 'pie_review_request_delete' );
			if ( empty( $show_review_notice ) ) {				

				$install_date = get_option('pie_install_date', '');
				if (empty($install_date)) return;
				$diff = round((time() - strtotime($install_date)) / 24 / 60 / 60);
				if ($diff < 4) return;
		
				$review_url = 'https://wordpress.org/support/plugin/pie-register/reviews/';
				?>
					<div class="notice notice-info pie-admin-notice-3 is-dismissible">
					<p><?php echo  sprintf(
								__( 'Hello there, Thank you for using Pie Register. If you are happy and satisfied, will you leave us a 5-star %1$sreview on WordPress?%2$s We would love to know about your experience so far with the plugin. Your feedback helps us grow and reach more people. Thanks!','pie-register'),
								'<a href="' . esc_url($review_url) . '" target="_blank">',
								'</a>'
							); ?></p>
						<p>
							<a href="<?php echo esc_url($review_url); ?>" target="_blank" id="pie_review_in_start" class="button button-primary">
								<?php _e( 'Sure', 'pie-register' ) ?>
							</a>	&nbsp;
							<a href="javascript:void(0);" class="button-secondary pie_review_in_link">
								<?php _e( 'Maybe Later', 'pie-register' ) ?>
							</a>
						</p>
					</div>
					<script type="text/javascript">
						jQuery(document).on('click', '#pie_review_in_start', function (e) {
							jQuery(this).parents('.pie-admin-notice-3').find( '.notice-dismiss' ).trigger('click');
						});
						
						jQuery(document).on('click', '.pie_review_in_link', function (e) {
							jQuery(this).parents('.pie-admin-notice-3').find( '.notice-dismiss' ).trigger('click');
						});
						
						jQuery( '.pie-admin-notice-3' ).on( 'click', '.notice-dismiss', function() {		
							var data = {
								action: 'dismiss_pie_leave_a_review_request'
							};
							jQuery.post( ajaxurl, data );
						});
					</script>		
				<?php
			}	
		}
				
		public function dismiss_pie_leave_a_review_request()
		{
			update_option( 'pie_review_request_delete', true );
		}
		
		public function pie_license_expire_msg()
		{
			$show_review_notice   	= get_option( 'pie_license_expire_msg' );
			$status_addon			= $this->get_addons_status();
			$renew_url 				= 'https://store.genetech.co/renew-order/';
			if($status_addon && empty( $show_review_notice )) {
				?>
					<div class="notice notice-info pie-notice-license-expire is-dismissible">
					<p><?php echo  sprintf(
								__( 'Your Pie Register Premium License key has expired; click %2$shere%3$s to check the status. Please %1$srenew%3$s your order to get all the plugin updates and support.','pie-register'),
								'<a href="' . esc_url($renew_url) . '" target="_blank">',
								'<a href="'.get_admin_url().'admin.php?page=pie-help&tab=license">',
								'</a>'
							); ?></p>
					</div>
					<script type="text/javascript">
						jQuery( '.pie-notice-license-expire' ).on( 'click', '.notice-dismiss', function() {		
							var data = {
								action: 'dismiss_pie_license_expire_msg'
							};
							jQuery.post( ajaxurl, data );
						});
					</script>
				<?php
			}	
		}
				
		public function dismiss_pie_license_expire_msg()
		{
			update_option( 'pie_license_expire_msg', true );
		}

		/* 
			Since version 3.6
			@TODO remove this notice in the future.
		*/
		public function pie_promo_notice_security()
		{
			$show_release_notice   = get_option( 'pie_promo_security_notice' );
			
			if ( empty( $show_release_notice ) ) {
				
				?>
                <div class="notice notice-info pie-admin-notice pie_security_msg is-dismissible" style="border:1px solid #ccd0d4;">
					<h3><?php ( _e( '<img src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/icon-secure.png').'" style="height:32px;vertical-align: middle;margin-right:8px;" /> Do You Want to Make Your Registration form More Secure? We hear you.','pie-register')); ?></h3>
					<p><?php echo ( _e('The fear of bots, spammers, and fake sign-ups with registration forms is real. Pie Register’s Premium Security features render an extra-added layer of protection to your registration forms to rule-out all these possible threats.','pie-register')) ?></p>
					<p><?php echo ( _e( 'The <a href="https://pieregister.com/features/invitation-based-registrations/?utm_source=admindashboard&utm_medium=notification&utm_campaign=invitationcodes" target="_blank">Invitation Codes</a> for keeping your website content exclusive, Timed Form Submissions to prevent bots, <a href="https://pieregister.com/features/customizable-login-security/?utm_source=admindashboard&utm_medium=notification&utm_campaign=loginsecurity" target="_blank">Customizable Login Security</a> to throw CAPTCHA at multiple failed login attempts, and <a href="https://pieregister.com/how-to-make-your-registration-form-secure/?utm_source=admindashboard&utm_medium=notification&utm_campaign=formsecure" target="_blank">More</a>.','pie-register')); ?></p>                	
                </div>
                <script type="text/javascript">
					
                	jQuery( '.pie_security_msg' ).on( 'click', '.notice-dismiss', function() {		
						var data = {
							action: 'dismiss_pie_promo_notice_security'
						};
						jQuery.post( ajaxurl, data );
					});
                </script>
                <?php 
            }
		}		
		public function dismiss_pie_promo_notice_security()
		{
			update_option( 'pie_promo_security_notice', 'yes' );
		}
		
		/* 
			Since version 3.5.4
			@TODO remove this notice in the future.
		*/
		public function pie_premium_features_notice_for_free()
		{
			?>
			<div class="notice notice-info pie-admin-notice pre-feat">
				
				<p style="font-weight:700;font-size:16px;"><?php echo ( _e( 'Creating Your First Registration Form','pireg')); ?></p>
				
				<p>Creating online registration forms for your WordPress website can take a significant amount of time and effort to both code and test but using a plugin with a form builder module can make this job hassle-free. <br> With Pie Register's drag and drop form builder, you can get the job done without writing a single line of code and in almost no time.</p>

				<p>
					<a target="_blank" style="color:#0073aa;" href="https://pieregister.com/documentation/how-to-create-your-first-registration-form/?utm_source=admindashboard&utm_medium=manageforms&utm_campaign=creatingforms">How to add Fields </a><br>
					<a target="_blank" style="color:#0073aa;" href="https://pieregister.com/documentation/settings/?utm_source=admindashboard&utm_medium=manageforms&utm_campaign=creatingforms">Complete Guide to Pie Register Settings</a><br>
					<a target="_blank" style="color:#0073aa;" href="https://pieregister.com/documentation/how-to-verify-and-moderate-user-registration-using-pie-register/?utm_source=admindashboard&utm_medium=manageforms&utm_campaign=creatingforms">Complete Guide to Verify Users</a><br>
					<a target="_blank" style="color:#0073aa;" href="https://pieregister.com/documentation/invitation-only-registrations/?utm_source=admindashboard&utm_medium=manageforms&utm_campaign=creatingforms">How to make your website exclusive using Invitation Codes</a>
				</p>
			</div>
			<?php 
		}
			
		public function pie_promo_notice_for_wc_user()
		{
			$show_release_notice   = get_option( 'pie_promo_notice_for_wc_user' );
			
			if ( empty( $show_release_notice ) ) {
				$img_src     = plugin_dir_url(__FILE__).'assets/images/pieregister-woocommerce.png';
				?>
                <div class="notice notice-info pie-admin-notice wc_promo_users is-dismissible">
					<div class="pr-launch-icon">
						<img src="<?php echo esc_url($img_src); ?>" alt="Pie Register and Woocommerce Logo">
					</div>
					<div>
						<p style="font-weight:700;">WooCommerce Addon</p>
						<p><?php echo __( ' Using WooCommerce? Synchronize the billing and shipping address on your Pie Register form with WooCommerce with our WooCommerce addon.','pireg'); ?></p> 
						<a href="https://pieregister.com/addons/woocommerce-addon/?utm_source=admindashboard&utm_medium=notification&utm_campaign=woocommerce" target="_blank" id="pie_review_in_start" class="button button-primary">
							<?php _e( "Here's how you can do it", 'pie-register' ) ?>
						</a>
					</div>
                </div>
                <script type="text/javascript">
					
                	jQuery( '.wc_promo_users' ).on( 'click', '.notice-dismiss', function() {		
						var data = {
							action: 'dismiss_pie_promo_notice_for_wc_user'
						};
						jQuery.post( ajaxurl, data );
					});
                </script>
                <?php 
            }
		}		
		public function dismiss_pie_promo_notice_for_wc_user()
		{
			update_option( 'pie_promo_notice_for_wc_user', 'yes' );
		}

		public function pie_promo_notice_for_mc_user()
		{
			$show_release_notice   = get_option( 'pie_promo_notice_for_mc_user_2' );
			
			if ( empty( $show_release_notice ) ) {
				$img_src     = plugin_dir_url(__FILE__).'assets/images/pieregister-mailchimp.png';
				?>
                <div class="notice notice-info pie-admin-notice mc_promo_users is-dismissible">
					<div class="pr-launch-icon">
						<img src="<?php echo esc_url($img_src); ?>" alt="Pie Register and Mail Chimp Logo">
					</div>
					<div>
						<p style="font-weight:700;">Mail Chimp Addon</p>
						<p><?php echo __( 'MailChimp Add-on now has the GDPR Compliance Field, Merge Fields, and a lot more.','pie-register'); ?></p> 
						<a href="https://pieregister.com/addons/mailchimp-addon/?utm_source=admindashboard&utm_medium=notification&utm_campaign=mailchimp" target="_blank" id="pie_review_in_start" class="button button-primary">
							<?php _e( "View list of new functions", 'pie-register' ) ?>
						</a>
					</div>
                </div>
                <script type="text/javascript">					
                	jQuery( '.mc_promo_users' ).on( 'click', '.notice-dismiss', function() {		
						var data = {
							action: 'dismiss_pie_promo_notice_for_mc_user'
						};
						jQuery.post( ajaxurl, data );
					});
                </script>
                <?php 
            }
		}		
		public function dismiss_pie_promo_notice_for_mc_user()
		{
			update_option( 'pie_promo_notice_for_mc_user_2', 'yes' );
		}

		public function pie_promo_notice_for_bb_user()
		{
			$show_release_notice   = get_option( 'pie_promo_notice_for_bb_user_3' );
			
			if ( empty( $show_release_notice ) ) {
				$img_src     = plugin_dir_url(__FILE__).'assets/images/pieregister-bbPress.png';
				?>
                <div class="notice notice-info pie-admin-notice bb_promo_users is-dismissible">
					<div class="pr-launch-icon">
						<img src="<?php echo esc_url($img_src); ?>" alt="Pie Register and bbPress Logo">
					</div>
					<div>
						<p style="font-weight:700;">bbPress Addon</p>
						<p><?php echo __( 'Want to connect bbPress with Pie Register? You can now display the Pie Register fields on your bbPress user profile and let your users edit the profile directly from there.'); ?></p> 
						<a href="https://pieregister.com/addons/bbpress-addon/?utm_source=admindashboard&utm_medium=notification&utm_campaign=bbpress" target="_blank" id="pie_review_in_start" class="button button-primary">
							<?php _e( "Learn More", 'pie-register' ) ?>
						</a>
					</div>
                </div>
                <script type="text/javascript">					
                	jQuery( '.bb_promo_users' ).on( 'click', '.notice-dismiss', function() {		
						var data = {
							action: 'dismiss_pie_promo_notice_for_bb_user'
						};
						jQuery.post( ajaxurl, data );
					});
                </script>
                <?php 
            }
		}		
		public function dismiss_pie_promo_notice_for_bb_user()
		{
			update_option( 'pie_promo_notice_for_bb_user_3', 'yes' );
		}

		/* 
			Since version 3.7.0.4
			@TODO remove this notice in the future.
		*/
		
		public function pie_promo_notice_for_wpb_user()
		{
			if ( !function_exists( 'get_current_screen' ) ) { 
				require_once ABSPATH . '/wp-admin/includes/screen.php'; 
			} 
			$screen = get_current_screen();

			if($screen->base == 'dashboard'){
				wp_enqueue_style('pie_font_awesome');

				wp_enqueue_script("pie_admin_about_us_menu_js" );

				$can_install_plugins = true;
				if ( ! current_user_can( 'install_plugins' ) ) {
					$can_install_plugins = false;
				}
				
				$show_release_notice   = get_option( 'pie_promo_notice_for_wpb_user' );
				
				if ( empty( $show_release_notice ) ) {
					$img_src     = plugin_dir_url(__FILE__).'assets/images/pieregister-woocommerce.png';
					$images_url  = PIEREG_PLUGIN_URL . 'assets/images/about-us/';
					$plugin		 = 'vc-addons-by-bit14/bit14-vc-addons.php';
					$all_plugins = get_plugins();
					$plugin_data = array(
						'icon'  => $images_url . 'pb-logo.png',
						'name'  => esc_html__( 'PB Add-ons for WP Bakery', 'pie-register' ),
						'desc'  => esc_html__( 'Build your website with premium quality All-in-One Web elements for WPBakery Page Builder.', 'pie-register' ),
						'wporg' => 'https://wordpress.org/plugins/vc-addons-by-bit14/',
						'url'   => 'https://downloads.wordpress.org/plugin/vc-addons-by-bit14.zip',
					);
	
					$plugin_data = $this->get_aboutus_plugin_data( $plugin, $plugin_data, $all_plugins );
	
					?>
					<div class="notice notice-info pie-admin-notice wpb_promo_users is-dismissible">
						<div class="pr-launch-icon">
							<img src="<?php echo esc_url( $plugin_data['details']['icon'] ); ?>">
						</div>
						<div class="sib-product-container">
							<div class="sib-product-notice">
								<div class="sib-product-detail">
									<h5>
										<?php echo esc_html( $plugin_data['details']['name'] ); ?>
									</h5>
									<p>
										<?php echo wp_kses_post( $plugin_data['details']['desc'] ); ?>
									</p>
								</div>
								<div class="sib-product-action">
									<div class="product-action">
										<?php if ( $can_install_plugins ) { ?>
											<button class="<?php echo esc_attr( $plugin_data['action_class'] ); ?>" data-plugin="<?php echo esc_attr( $plugin_data['plugin_src'] ); ?>" data-type="plugin">
												<?php echo wp_kses_post( $plugin_data['action_text'] ); ?>
											</button>
										<?php } else { ?>
											<a href="<?php echo esc_url( $details['wporg'] ); ?>" target="_blank" rel="noopener noreferrer">
												<?php esc_html_e( 'WordPress.org', 'pie-register' ); ?>
												<span aria-hidden="true" class="dashicons dashicons-external"></span>
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						
						jQuery( '.wpb_promo_users' ).on( 'click', '.notice-dismiss', function() {		
							var data = {
								action: 'dismiss_pie_promo_notice_for_wpb_user'
							};
							jQuery.post( ajaxurl, data );
						});
					</script>
					<?php 
				}
			}
		}		
		public function dismiss_pie_promo_notice_for_wpb_user()
		{
			update_option( 'pie_promo_notice_for_wpb_user', 'yes' );
		}
		
		/* 
			Since version 3.6
			@TODO remove this notice in the future.
		*/
		public function pie_free_notice_to_premium_users()
		{
			$show_release_notice   = get_option( 'pie_free_notice_to_premium_users' );
			
			if ( empty( $show_release_notice ) ) {
				
				?>
                <div style="border-left-color: #c00;background-color: #f9e3e3;" class="notice notice-info free_promo_users_upgrade is-dismissible">
					<h3><?php echo __('ALERT','pie-register'); ?>:</h3>
					<p style="font-size:14px;"><?php echo sprintf( __( ' Looks like you haven\'t installed Pie Register Premium Version. Before changing any settings or option make sure to install and activate Pie Register Premium plugin. You can get the Premium plugin from  <a style="color:#c30604;" target="_blank" id="pie_opt_in_start" href="%s">My Account dashboard</a></strong>.','pie-register'),'https://store.genetech.co/my-account'); ?></p> 
                </div>
                <script type="text/javascript">
					
                	jQuery( '.free_promo_users_upgrade' ).on( 'click', '.notice-dismiss', function() {		
						var data = {
							action: 'dismiss_pie_free_notice_to_premium_users'
						};
						jQuery.post( ajaxurl, data );
					});
                </script>
                <?php 
            }
		}		
		public function dismiss_pie_free_notice_to_premium_users()
		{
			update_option( 'pie_free_notice_to_premium_users', 'yes' );
		}
		

		/*
			*	Show Post Meta Box
		*/
		function myplugin_meta_box_callback( $post ) { 
			wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );
			?>
            <div class="pie_register-admin-meta">
			<?php $result = get_post_meta( $post->ID, '_piereg_post_restriction', true );?>
				
	            <div class="piereg_restriction_field_area">
                	<h2><?php _e( 'Visibility Restrictions', 'pie-register' ); ?></h2>
	                <input type="hidden" name="post_restriction[piereg_post_type]" value="<?php echo esc_attr($post->post_type); ?>" />
                	<div class="piereg_label">
						<label for="piereg_post_visibility"><?php _e( 'Visibility', 'pie-register' ); ?></label>
					</div>
					<div class="piereg_input">
						<?php
							if ( isset($result['piereg_post_visibility']) && is_array(isset($result['piereg_post_visibility'])) )
							{
								$option = !empty($result['piereg_post_visibility']) ? $result['piereg_post_visibility'] : array( 0 => 'default' );
							}
							else
							{
								$option = !empty($result['piereg_post_visibility']) ? $result['piereg_post_visibility'] : "default";
							}
						?>
						<select multiple id="piereg_post_visibility" name="piereg_post_visibility[]">
			            	<option value="default" <?php echo ( is_array($option) && in_array('default',$option) ) || ( !is_array($option) && $option == 'default') ?'selected="selected"':''?> ><?php _e('Default',"pie-register") ?></option>
			            	<option value="after_login" <?php echo ( is_array($option) && in_array('after_login',$option) ) || ( !is_array($option) && $option == 'after_login') ?'selected="selected"':''?>><?php _e('Show to Logged in Users',"pie-register") ?></option>
			            	<option value="before_login" <?php echo ( is_array($option) && in_array('before_login',$option) ) || ( !is_array($option) && $option == 'before_login') ?'selected="selected"':''?>><?php _e('Show to users who have not logged in.',"pie-register") ?></option>
			                <?php
							global $wp_roles;
							$role = $wp_roles->roles;
							
							foreach($role as $key => $value)
							{ 
								?>
								<option value="<?php echo esc_attr($key) ?>"<?php echo ( is_array($option) && in_array($key,$option) ) || ( !is_array($option) && $option == $key) ?'selected="selected"':''?>><?php _e("Show to","pie-register");echo " ".esc_html($value['name']); ?></option>
			                    <?php
							}
							?>
			            </select>
                	</div>
                </div>

                <div class="piereg_restriction_field_area pieregister_restriction_type_area">
                	<div class="piereg_label"><label><?php _e( 'Restriction Type', 'pie-register' ); ?></label></div>
                    
					<div class="piereg_input">
						<?php $restriction_option = (isset($result['piereg_restriction_type']) && $result['piereg_restriction_type'] != "") ? $result['piereg_restriction_type'] : 0; ?>
                        <div class="piereg_input_radio">
	                        <label for="redirect">
                            <input type="radio" id="redirect" class="piereg_restriction_type" name="post_restriction[piereg_restriction_type]" value="0" <?php echo ($restriction_option == 0)? 'checked="checked"' : '' ?> /><?php _e( 'Redirect', 'pie-register' ); ?></label>
                        </div>
                        <div class="piereg_input_radio">
	                        <label for="block_content">
                            <input type="radio" id="block_content" class="piereg_restriction_type" name="post_restriction[piereg_restriction_type]" value="1" <?php echo ($restriction_option == 1)? 'checked="checked"' : '' ?> /><?php _e( 'Block Content', 'pie-register' ); ?></label>
                        </div>
						<div ><p class="piereg_restriction_msg error" ><strong><?php _e( 'Please Provide Either Redirect Page or Redirect URL.', 'pie-register' ); ?></strong></p></div>
                	</div>
                </div>
                
				<div id="pieregister_restriction_url_area" <?php echo ($restriction_option != 0)? 'style="display:none"' : '' ?> >
                    <div class="piereg_restriction_field_area pieregister_restriction_url_area">
                        <div class="piereg_label">
                            <?php $option = ((isset($result['piereg_redirect_url']) && $result['piereg_redirect_url'] != "") ? $result['piereg_redirect_url'] : ""); ?>
                            <label for="piereg_redirect_url"><?php _e( 'Redirect Url', 'pie-register' ); ?></label>
                        </div>
                        <div class="piereg_input">
                            <input type="url" id="piereg_redirect_url" name="post_restriction[piereg_redirect_url]" value="<?php echo esc_attr($option);  ?>" style="width:70%;" class="pieregister_redirect_url" />
                        </div>
                    </div>
                    
                    <div class="piereg_restriction_field_area pieregister_restriction_url_area">
                        <center><strong><?php _e('OR','pie-register'); ?></strong></center>
                    </div>
                    
                    <div class="piereg_restriction_field_area pieregister_restriction_url_area">
                        <div class="piereg_label">
                            <?php $option = ((isset($result['piereg_redirect_page']) && $result['piereg_redirect_page'] != "") ? $result['piereg_redirect_page'] : -1); ?>
                            <label for="piereg_redirect_page"><?php _e( 'Redirect Page', 'pie-register' ); ?></label>
                        </div>
                        <div class="piereg_input">
                            <?php
							$args =  array("show_option_no_change"=>"None","id"=>"piereg_redirect_page","class"=>"pieregister_redirect_url","name"=>"post_restriction[piereg_redirect_page]","selected"=>$option);
                            wp_dropdown_pages( $args ); ?>
                        </div>
                    </div>
                </div>
                
                <div class="piereg_restriction_field_area pieregister_block_content_area" <?php echo ($restriction_option != 1)? 'style="display:none"' : '' ?> >
                	<div class="piereg_label">
						<?php $option = ((isset($result['piereg_block_content']) && $result['piereg_block_content'] != "") ? $result['piereg_block_content'] : ""); ?>
						<label for="piereg_block_content"><?php _e( 'Block Content', 'pie-register' ); ?></label>
					</div>
					<div class="piereg_input">
						<textarea id="piereg_block_content" name="post_restriction[piereg_block_content]"><?php echo esc_textarea($option); ?></textarea>
                	</div>
                </div>
            </div>
            <noscript><?php _e('You browser does not support Javascript. Please turn on Javascript support.','pie-register'); ?></noscript>
            
			<?php
		}
		/*
			Save Post Meta Box
		*/
		function piereg_save_meta_box_data( $post_id ) {
			//Don't update on Quick Edit
			if (defined('DOING_AJAX') ) {
				return $post_id;
			}
			$post_restriction_meta 		= isset($_POST['post_restriction']) ? $this->piereg_sanitize_post_data( 'post_restriction', $_POST ) :  array();
			
			if ( isset( $_POST['piereg_post_visibility'] ) )
			{
				// Sanitize piereg post visibility array
				$npost = array();
				foreach ( $_POST['piereg_post_visibility'] as $key=>$val ) 
				{
					$npost[$key] =  sanitize_key( $val );
				}
				$post_restriction_meta['piereg_post_visibility'] =  $npost;
			}
			else
			{
				$post_restriction_meta['piereg_post_visibility'] =  "default";
			}
			update_post_meta( $post_id, '_piereg_post_restriction', $post_restriction_meta );
		}		
		
		private function show_invitaion_code_user(){
			global $errors,$wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$inv_code = sanitize_text_field(base64_decode($_GET['invitaion_code']));
			if (!preg_match("/[^A-Za-z0-9-_]/", $inv_code)) {
			
			$invitaion_code_users = $wpdb->get_results(  $wpdb->prepare( "SELECT `user_login`,`user_email` FROM `wp_users` WHERE `ID` IN (SELECT user_id FROM `wp_usermeta` Where meta_key = 'invite_code' and meta_value = %s )", $inv_code )  );
			
			if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			?>
			<style type="text/css">
				table.invitaion-code-table thead td,table.invitaion-code-table tfoot td{
					background:#333;
					font-size:16px;
					font-weight:bold;
					color:#FFF;
				}
				table.invitaion-code-table tr:nth-child(even){background:#E8E8E8;}
				table.invitaion-code-table tr:hover{background:#666;color:#FFF;}
			</style>
			<div style="width:100%">
			<h2><?php _e("Activation Code","pie-register");echo " : ".esc_html($inv_code); ?></h2>
				<table class="invitaion-code-table" width="100%" cellpadding="10" cellspacing="0">
					<thead>
						<tr>
							<td><?php _e("User Name","pie-register"); ?></td>
							<td><?php _e("User E-mail","pie-register"); ?></td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td><?php _e("User Name","pie-register"); ?></td>
							<td><?php _e("User E-mail","pie-register"); ?></td>
						</tr>
					</tfoot>
					<?php
						foreach($invitaion_code_users as $row){
							echo '<tr>';
							echo '<td>'.esc_html($row->user_login).'</td>';
							echo '<td>'.esc_html($row->user_email).'</td>';
							echo '</tr>';
						}
					?>
				</table>
			</div>
			<?php
			}
			exit;
		}
		function piereg_login_logo() {
			$option = get_option(OPTION_PIE_REGISTER);	
			$logo_data = @getimagesize($option['custom_logo_url']);
			?>
			<style type="text/css">
				body.login div#login h1 a {
					background-image: url('<?php echo esc_url($option['custom_logo_url']); ?>');
					background-size:<?php echo esc_attr($logo_data[0])."px ".esc_attr($logo_data[1])."px"; ?>;
					width:<?php echo esc_attr($logo_data[0])."px "; ?>;
					height:<?php echo esc_attr($logo_data[1])."px "; ?>;
				}
			</style>
			<?php
			unset($option);
		}
		function piereg_login_logo_url_title() {
			$option = get_option(OPTION_PIE_REGISTER);
			return $option['custom_logo_tooltip'];
			unset($option);
		}
		function piereg_login_logo_url() {
			$option = get_option(OPTION_PIE_REGISTER);
			return $option['custom_logo_link'];
			unset($option);
		}
		function payment_success_cancel_after_register($query_string){
			global $wpdb;
			$option = get_option(OPTION_PIE_REGISTER);
			$fields 			= maybe_unserialize(get_option("pie_fields"));
			$confirmation_type 	= $fields['submit']['confirmation'];
			
			if($confirmation_type == "page"){
				wp_safe_redirect(get_permalink($fields['submit']['page']));
				exit;
			}elseif($confirmation_type == "redirect"){
				wp_redirect(esc_url_raw($fields['submit']['redirect_url']));
				exit;
			}elseif($confirmation_type == "text" ){
				wp_safe_redirect($this->pie_modify_custom_url(get_permalink($option['alternate_login']),$query_string));
				exit;
			}
		}
		function get_redirect_url_pie($get_url){
			$get_url = trim($get_url);
			if(!$get_url) return false;
			if($_SERVER['QUERY_STRING']){
				if(strpos($get_url,"?"))
					$url = $get_url."&".$_SERVER['QUERY_STRING'];
				else
					$url = $get_url."?".$_SERVER['QUERY_STRING'];
			}
			else{
				$url = $get_url;
			}
			return $url;
		}
		function subscriber_show_admin_bar()
		{
			global $current_user;
			$current_user_caps_keys = array_keys($current_user->caps);
			$ncaps = count($current_user_caps_keys);
			if($ncaps) {
				//if( !in_array('administrator', $current_user->caps) )
				if( !array_key_exists('administrator', $current_user->caps) )
				{
					show_admin_bar( false );
				}
			}
			unset($current_user);
		}
		
		function delete_piereg_form()
		{
			if(isset($_GET['prfrmid']) and ((int)$_GET['prfrmid']) != 0 and isset($_GET['action']) and $_GET['action'] == "delete")
			{
				$fields_id 				= ((int)sanitize_key($_GET['prfrmid']));
				$assign_new_free_form 	= false;
				
				if( $this->regFormForFreeVers() == $fields_id ) {
					$assign_new_free_form = true;
				}
				
				delete_option("piereg_form_field_option_".$fields_id);
				delete_option("piereg_form_fields_".$fields_id);
				$user_role = get_option(OPTION_PIE_REGISTER);
				unset($user_role['pie_regis_set_user_role_'.((int)$_GET['prfrmid'])]);
				update_option(OPTION_PIE_REGISTER,$user_role);
				unset($user_role);
				unset($fields_id);
				unset($_GET['prfrmid']);
				
				if( $assign_new_free_form ) {
					$this->regFormForFreeVers(true);
				}
			}
		}
		
		//"Insert Form" button to the post/page edit screen
		function add_pie_form_button($context)
		{
			$_post_type 		=  get_post_type();
			if($_post_type == 'product')
				return $context;

			$is_post_edit_page 	= in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
			if(!$is_post_edit_page)
				return $context;
			
			printf('<a href="%s" class="thickbox button" id="add_pie_form" title="%s"><span style="%s" class="wp-media-buttons-icon"></span>%s</a>','#TB_inline?width=480&inlineId=select_pie_form',__("Add Pie Register Form", 'pie-register'),'background: url('.PIEREG_PLUGIN_URL.'assets/images/form-icon.png); background-repeat: no-repeat; background-position: left bottom;',__("Add Form","pie-register"));

		}
		
		function checkLoginPage()
		{
			$option 		= get_option(OPTION_PIE_REGISTER);	
			$current_page	= get_the_ID();
			if($option['block_wp_login']==1  && $option['redirect_user']==1 && $option['alternate_login'] > 0 && is_user_logged_in() && $current_page == $option['alternate_login'] && !$this->is_pr_preview )
			{	
				$this->afterLoginPage();
			}
		}
		function add_pie_form_popup()
		{
			 ?>
			 <div id="select_pie_form" style="display:none;">
				<div >
					<div>
						<div style="padding:15px 15px 0 15px;">
							<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Select A Form", "pie-register"); ?></h3>
							<span>
								<?php _e("Select a form below to add it to your post or page.", "pie-register"); ?>
							</span>
						</div>
						<div style="padding:15px 15px 0 15px;">
							<select id="pie_forms">
                            	<option value=""><?php _e("Select","pie-register") ?></option>
                            	<optgroup label="<?php _e("Registration Form","pie-register") ?>">
                                    <?php
                                    $fields_id = get_option("piereg_form_fields_id");
									for($a=1;$a<=$fields_id;$a++)
									{
										$option = get_option("piereg_form_field_option_".$a);
										if($option != "" && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) )
										{
											echo '<option value=\'[pie_register_form id="'.esc_attr($option['Id']).'" title="true" description="true" ]\'>'.esc_html($option['Title']).'</option>';
										}
									}
?>
                                </optgroup>
                                <optgroup label="<?php _e("Other Form","pie-register") ?>">
                                    <option value="[pie_register_login]"><?php _e("Login Form","pie-register") ?></option>
                                    <option value="[pie_register_forgot_password]"><?php _e("Forgot Password Form","pie-register") ?></option>
                                    <option value="[pie_register_profile]"><?php _e("Profile Page","pie-register") ?></option>
                                </optgroup>
							</select> <br/>
						</div>
						<div style="padding:15px;">
							<input type="button" class="button-primary" value="Insert Form" onclick="addForm();"/>&nbsp;&nbsp;&nbsp;
							<a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", "pie-register"); ?></a>
						</div>
					</div>
				</div>  
			</div>	
		<?php
		}
		function process_verify_user_email(){

			if(isset($_GET['verification_key']) && !empty($_GET['verification_key'])){

				$unverified = get_users(array('meta_key'=> 'admin_hash','meta_value' => sanitize_key($_GET['verification_key'])));
				$option = get_option(OPTION_PIE_REGISTER);
				if(sizeof($unverified )==1)
				{
					$user_id	= $unverified[0]->ID;
					$user_login = $unverified[0]->user_login;
					$user_email = $unverified[0]->user_email;
					$register_type = get_user_meta( $user_id , "register_type" , true);

					if($register_type == "admin_email_verify"){							
						update_user_meta( $user_id, 'active', 0);
						$hash = md5( time() );
						update_user_meta( $user_id, 'hash', $hash );
						update_user_meta( $user_id, 'register_type', "email_verify");
						
						$subject 		= html_entity_decode($option['user_subject_email_email_verification'],ENT_COMPAT,"UTF-8");
						$subject 		= $this->filterSubject($user_email,$subject);
						$message_temp = "";
						if($option['user_formate_email_email_verification'] == "0"){
							$message_temp	= nl2br(strip_tags($option['user_message_email_email_verification']));
						}else{
							$message_temp	= $option['user_message_email_email_verification'];
						}
						$message		= $this->filterEmail($message_temp,$user_email );
						$from_name		= $option['user_from_name_email_verification'];
						$from_email		= $option['user_from_email_email_verification'];					
						$reply_email 	= $option['user_to_email_email_verification'];
						
						//Headers
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
						
						
						if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
						$headers .= "From: ".$from_name." <".$from_email."> \r\n";	
						if($reply_email){
							$headers .= "Reply-To: {$reply_email}\r\n";
							$headers .= "Return-Path: {$reply_email}\r\n";
						}else{
							$headers .= "Reply-To: {$from_email}\r\n";
							$headers .= "Return-Path: {$from_email}\r\n";
						}
						if((isset($option['user_enable_email_verification']) && $option['user_enable_email_verification'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
							$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}else{
							echo('<div id="dialog-message" title="User Verified Successfully">
							<p>
							'.apply_filters("pie_user_veri_by_email_admin",__("User has been verified.","pie-register")).'
							</p>
							</div>');
						}
					}elseif($user_login == $_GET['pie_id']){
						
						$user = new WP_User($user_id);
						do_action( "piereg_action_hook_before_user_verified", $user_id, $user_login, $user_email ); # newlyAddedHookFilter
						update_user_meta( $user_id, 'active', 1);
						
						/*************************************/
						/////////// THANK YOU E-MAIL //////////
						$form 			= new Registration_form();
						$subject 		= html_entity_decode($option['user_subject_email_email_thankyou'],ENT_COMPAT,"UTF-8");
						$subject = $form->filterSubject($user_email,$subject);
						$message_temp = "";
						if($option['user_formate_email_email_thankyou'] == "0"){
							$message_temp	= nl2br(strip_tags($option['user_message_email_email_thankyou']));
						}else{
							$message_temp	= $option['user_message_email_email_thankyou'];
						}
						$message		= $form->filterEmail($message_temp,$user_email);
						$from_name		= $option['user_from_name_email_thankyou'];
						$from_email		= $option['user_from_email_email_thankyou'];
						$reply_email 	= $option['user_to_email_email_thankyou'];
						//Headers
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
						if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
						$headers .= "From: ".$from_name." <".$from_email."> \r\n";
						if($reply_email){
							$headers .= "Reply-To: {$reply_email}\r\n";
							$headers .= "Return-Path: {$reply_email}\r\n";
						}else{
							$headers .= "Reply-To: {$from_email}\r\n";
							$headers .= "Return-Path: {$from_email}\r\n";
						}	
						/*************************************/

						if( (isset($option['user_enable_email_thankyou']) && $option['user_enable_email_thankyou'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
							$form->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".(PieRegister::get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}else{
							echo('<div id="dialog-message" title="User Verified Successfully">
							<p>
							  '.apply_filters("pie_user_veri_by_email_admin",__("User has been verified.","pie-register")).'
							</p>
						  </div>');	
						}
						// mailchimp related code within PR
						do_action('pireg_after_verification_users', $user);
					}
					else
					{
						echo('<div id="dialog-message" title="User Not Verified">
						<p>
						<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_invalid_verification_key",__("Invalid verification key","pie-register"))).'
						</p>
					  	</div>';
					}
				}else{
					$user_name = sanitize_user($_GET['pie_id']);
					$user = get_user_by('login',$user_name);
					if($user){
						$user_meta = get_user_meta( $user->ID, 'active');
						$user_meta_email = get_user_meta($user->ID,'hash');

						if(isset($user_meta[0]) && $user_meta[0] == 1){
							echo('<div id="dialog-message" title="User Verified">
							<p><strong>'.ucwords(__("Warning","pie-register")).'</strong>:
							  '.apply_filters("piereg_canelled_your_registration",__("User is already verified","pie-register")).'
							</p>
						  </div>');
							unset($user_meta);
							unset($user_meta_email);
							unset($user_name);
							unset($user);
						}elseif(isset($user_meta_email[0]) && $user_meta_email[0]){
							echo('<div id="dialog-message" title="User Verified">
							<p><strong>'.ucwords(__("Warning","pie-register")).'</strong>:
							  '.apply_filters("piereg_canelled_your_registration",__("User is already verified","pie-register")).'
							</p>
						  </div>');
							unset($user_meta);
							unset($user_meta_email);
							unset($user_name);
							unset($user);
						}
						else{
							echo('<div id="dialog-message" title="User Not Verified">
							<p><strong>'.ucwords(__("error","pie-register")).'</strong>: 
							'.apply_filters("piereg_invalid_verification_key",__("Invalid verification key","pie-register")).'
							</p>
							</div>');
						}
					}
					else{
						echo('<div id="dialog-message" title="User Not Verified">
							<p><strong>'.ucwords(__("error","pie-register")).'</strong>: 
							'.apply_filters("piereg_invalid_verification_key",__("Invalid verification key","pie-register")).'
							</p>
							</div>');
					}
				}
			}
		}
		function pie_update_user_meta_admin_hash() {
			$verification_key = isset($_GET['verification_key']) ? sanitize_key($_GET['verification_key']) : "";
			$unverified = get_users(array('meta_key'=> 'admin_hash','meta_value' => $verification_key));
			if(sizeof($unverified )==1)
			{
				$user_id	= $unverified[0]->ID;
				$user_login = $unverified[0]->user_login;
				if( isset($_GET['pie_id']) && $user_login == $_GET['pie_id'])
				{
					$admin_hash = "";
					update_user_meta( $user_id, 'admin_hash', $admin_hash );
				}
			}
		}
		
		function process_login_form(){
			get_header();
			$this->set_pr_stats("login","view");
			if( file_exists(PIEREG_DIR_NAME . "/login_form.php") )
				include_once("login_form.php");
			$output = "<div class='wrapper-wp-login-form'>";
			$output .= pieOutputLoginForm();
			$output .= "</div>";
			echo wp_kses($output, $this->piereg_forms_get_allowed_tags());
			get_footer();
			exit;
		}
		function checkLogin(){
			global $wpdb, $errors, $wp_session;
			$errors = new WP_Error();
			$option = get_option(OPTION_PIE_REGISTER);
			
			if( (isset($_POST['piereg_login_form_nonce']) && wp_verify_nonce( $_POST['piereg_login_form_nonce'], 'piereg_wp_login_form_nonce' )) || (isset($_POST['social_site']) && $_POST['social_site'] == "true") || (isset($_POST['piereg_login_after_registration']) && $_POST['piereg_login_after_registration'] == true) )
			{
				if(empty($_POST['log']) || empty($_POST['pwd']))
				{
					$errors->add('login-error',apply_filters("piereg_Invalid_username_or_password",__('Invalid username or password.','pie-register')));					
				}
				else
				{
					/*
					 *	Sanitizing post data
					 */
					$this->pie_post_array	=	$this->piereg_sanitize_post_data( 'piereg_login', ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
					$error_found = 0;
					
					$table_name = $wpdb->prefix . "pieregister_lockdowns";
					$user_ip = $_SERVER['REMOTE_ADDR'];
					$get_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$table_name."` WHERE `user_ip` = %s AND `attempt_from` = 'is_login';",$user_ip));
					
					$piereg_security_attempts_login_value = false;
					
					if($option['captcha_in_login_value'] == 1 && !isset($this->pie_post_array['piereg_login_after_registration']) ){
						
						$attempts = false;
						if( $option['captcha_in_login_attempts'] > 0 ){
							$attempts = true;
						}elseif( $option['captcha_in_login_attempts'] == 0 ){
							$attempts = true;
						}
						
						if( $attempts ){
							if($option['capthca_in_login'] == 2 || ( isset($this->pie_post_array['piereg_math_captcha_login']) || isset($this->pie_post_array['piereg_math_captcha_login_widget']) ) ){
								if(isset($this->pie_post_array['piereg_math_captcha_login']))//Login form in Page
								{
									$currentTabId =  intval($_COOKIE['currentTabId']);		
									$piereg_cookie_array =  ( (isset( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form'] ) && 0 < strpos( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form'], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form']) : "");
									$piereg_cookie_array = explode("|",$piereg_cookie_array);
									$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
									$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
									$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
									if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $this->pie_post_array['piereg_math_captcha_login'])){
									}
									else{
										if($this->piereg_authentication($this->pie_post_array['log'],$this->pie_post_array['pwd'])){
											$errors->add('login-error',apply_filters("Invalid_Security_Code",__('Invalid Security Code','pie-register')));
										}else{
											if( $piereg_security_attempts_login_value ){
												$errors->add('login-error',apply_filters("blocked_ip_due_to_many_attempts_failed",__("IP address blocked due to too many failed login attempts.","pie-register")));
											}
										}
										$error_found++;
									}
								}
								elseif(isset($this->pie_post_array['piereg_math_captcha_login_widget']))//Login form in widget
								{
									$currentTabId =  intval($_COOKIE['currentTabId']);		
									$piereg_cookie_array =  ( (isset( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form_widget'] ) && 0 < strpos( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form_widget'], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_Login_form_widget']) : "");
									$piereg_cookie_array = explode("|",$piereg_cookie_array);
									$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
									$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
									$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
									if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $this->pie_post_array['piereg_math_captcha_login_widget'])){
									}
									else{
										if($this->piereg_authentication($this->pie_post_array['log'],$this->pie_post_array['pwd'])){
											$errors->add('login-error',apply_filters("Invalid_Security_Code",__('Invalid Security Code','pie-register')));
										}else{
											if( $piereg_security_attempts_login_value ){
												$errors->add('login-error',apply_filters("blocked_ip_due_to_many_attempts_failed",__("IP address blocked due to too many failed login attempts.","pie-register")));
											}
										}
										$error_found++;
									}
								}else{
									if($this->piereg_authentication($this->pie_post_array['log'],$this->pie_post_array['pwd'])){
										$errors->add('login-error',apply_filters("Invalid_Security_Code",__('Invalid Security Code','pie-register')));
									}else{
										if( $piereg_security_attempts_login_value ){
											$errors->add('login-error',apply_filters("blocked_ip_due_to_many_attempts_failed",__("IP address blocked due to too many failed login attempts.","pie-register")));
										}
									}
									$error_found++;
								}
							}//New Recaptcha
							elseif($option['capthca_in_login'] == 3 || (isset($this->pie_post_array["recaptcha_challenge_field"], $this->pie_post_array["recaptcha_response_field"]) && !empty($this->pie_post_array["recaptcha_challenge_field"]) && !empty($this->pie_post_array["recaptcha_response_field"]) ) ){
								$settings  	=  get_option(OPTION_PIE_REGISTER);
								if($settings['piereg_recaptcha_type'] == "v2"){
									$privatekey		= $settings['captcha_private'];
								}elseif($settings['piereg_recaptcha_type'] == "v3"){
									$privatekey		= $settings['captcha_private_v3'];
								}
								
								$captcha = "";
								$captcha	= (isset($this->pie_post_array['g-recaptcha-response']) && ! empty( $this->pie_post_array['g-recaptcha-response'] )) ? trim($this->pie_post_array['g-recaptcha-response']) : ""; 
								
								$response = $this->read_file_from_url("https://www.google.com/recaptcha/api/siteverify?secret=".trim($privatekey)."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
								$resp = json_decode($response,true);
								if($resp['success'] == false){
										
									if($this->piereg_authentication($this->pie_post_array['log'],$this->pie_post_array['pwd'])){
										$errors->add('login-error',apply_filters("Invalid_Security_Code",__('Invalid Security Code','pie-register')));
									}else{
										if( $piereg_security_attempts_login_value ){
											$errors->add('login-error',apply_filters("blocked_ip_due_to_many_attempts_failed",__("IP address blocked due to too many failed login attempts.","pie-register")));
										}
									}
									$error_found++;
								}
							}
						}
					}
					
					do_action('pie_validate_before_login', $errors);
					
					if( sizeof($errors->errors) == 0 && $error_found == 0){
						$creds['user_login'] 	= $this->pie_post_array['log'];
						// is_email($creds['user_login']) - 3.6.11
						$regXemail = "/^[a-zA-Z0-9.'_%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,5}/";
						if( preg_match($regXemail,$creds['user_login']) )
						{
							$userdata 				= get_user_by('email', $creds['user_login']);
							$creds['user_login']	= (isset($userdata->user_login)) ? strtolower($userdata->user_login) : $creds['user_login'];
						}
						
						$this->pie_post_array['pwd']			= html_entity_decode($this->pie_post_array['pwd']);
						$creds['user_password'] = $this->pie_post_array['pwd'];
						$creds['remember'] 		= isset($this->pie_post_array['rememberme']);
						
						$remember_user	= (isset($this->pie_post_array['rememberme'])) ? true : false ;
						
						if(isset($this->pie_post_array['social_site']) and $this->pie_post_array['social_site'] == "true" and is_plugin_active("pie-register-social-site/pie-register-social-site.php"))
						{
							// Code for Social Login
							// Since v3.7.1.6 - The code has been shifted to the addon for user authentication
							require_once( ABSPATH . WPINC . '/user.php' );
							$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
							$email_add = isset($this->pie_post_array['user_email_social_site']) ? $this->pie_post_array['user_email_social_site'] : '';
							$user = apply_filters( 'piereg_user_from_social_login', $this->pie_post_array['log'], $email_add, $remember_user );
						}
						else
						{
							$piereg_secure_cookie = false;
							$piereg_secure_cookie = $this->PR_IS_SSL();
							if($this->piereg_authentication($this->pie_post_array['log'],$this->pie_post_array['pwd'])){
								
								$user = wp_signon( $creds, $piereg_secure_cookie);
								
								if ( !is_wp_error($user) ){
									$this->piereg_delete_authentication();
								}
							}else{
								if( $piereg_security_attempts_login_value ){
									$user = new WP_Error('piereg_authentication_failed', __("IP address blocked due to too many failed login attempts.","pie-register"));
								}
							}
						}
						
						if ( is_wp_error($user))
						{
							$user_login_error = $user->get_error_message();
							if(strpos(strip_tags($user_login_error),'Invalid username',5) > 6 || strpos($user_login_error,'field is empty') !== false || strpos($user_login_error,'Unknown email') !== false || strpos($user_login_error,'Unknown username') !== false )
							{
								$user_login_error = apply_filters('pie_invalid_username_password_msg_txt','<strong>'.ucwords(__("error","pie-register")).'</strong>: '.__("Invalid username or password","pie-register").'. <a href="'.$this->pie_lostpassword_url().'" title="'.__("Reset your password","pie-register").'">'.__("Lost your password?","pie-register").'</a>');
							}else if(strpos(strip_tags($user_login_error),'password you entered',9) > 10)
							{
								$user_login_error = apply_filters('pie_invalid_user_password_msg_txt','<strong>'.ucwords(__("error","pie-register")).'</strong>: '.__("Invalid username or password","pie-register").'. <a href="'.$this->pie_lostpassword_url().'" title="'.__("Reset your password","pie-register").'">'.__("Lost your password?","pie-register").'</a>');
							}
							$errors->add('login-error',apply_filters("piereg_login_error",$user_login_error));
							if(isset($this->pie_post_array['piereg_login_after_registration']) && $option['login_after_register'] == 1){
								$error_message = base64_encode(__("Invalid username","pie-register"));
								wp_safe_redirect($this->pie_modify_custom_url($this->pie_login_url(),"pr_invalid_username=true&pr_key={$error_message}"));
								exit;
							}
						}
						else
						{
							$this->set_pr_stats("login","used");
							if(in_array("administrator",(array)$user->roles)){
								
								/*
									*	Add Since 2.0.13
								*/
								if( $user ) {
									wp_set_current_user( $user->ID, $user->user_login );
									wp_set_auth_cookie( $user->ID, $remember_user );
									do_action( 'wp_login', $user->user_login, $user );
								}
								do_action("piereg_admin_login_before_redirect_hook",$user);
								
								$this->afterLoginPage(); 
								exit;
							}
							else{
								
								# Hook before login from social login
								if( isset($this->pie_post_array['social_site']) && $this->pie_post_array['social_site'] == "true" )
								{
									do_action('pie_register_after_social_login',$user);
								}
																
								/*
								 *	Check User Expiry
								 *	Deprecate
								 */
								
								$active = get_user_meta($user->ID,"active",true);
								
								//Delete User after grace Period
								if(!$this->deleteUsers($user->ID,$user->user_email,$user->user_registered)){
									if($active == "0")//If not active
									{
										wp_logout();
										$check_payment = get_option(OPTION_PIE_REGISTER);
										
										// Payment Cycle Is Disabled In 3.0 Release.
										if((($this->check_enable_payment_method()) == "true") && ("not" === "using") )
										{
											global $wpdb,$pr_wp_db_prefix;
											$user_name_or_email = esc_sql($this->pie_post_array['log']);
											$myrows = $wpdb->get_results( $wpdb->prepare("SELECT ID,user_login,user_email FROM `".($pr_wp_db_prefix)."users` where user_login = %s OR `user_email` = %s", $user_name_or_email, $user_name_or_email) );
											if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
												$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
											}
											
											$errors->add('login-error',__('Please Renew your account. ','pie-register'));
											$this->pie_post_array['error'] = __('Please Renew your account',"pie-register");
											$auth_key = md5( $myrows[0]->user_login );
											$user_name = trim( $myrows[0]->user_login );
											update_user_meta( $myrows[0]->ID , "pr_renew_account_hash" , $auth_key );
											
											$query_str = "pr_renew_account=true&auth=".base64_encode((urlencode($user_name)))."&auth_key=".(urlencode($auth_key));
											$renew_account_url = $this->pie_modify_custom_url($this->pie_login_url($check_payment['alternate_login']),$query_str);
											$this->pie_ua_renew_account_url = $renew_account_url;
											if(
												   isset($option['social_site_popup_setting'],$this->pie_post_array['social_site']) and 
												   $option['social_site_popup_setting'] == 1 and 
												   $this->pie_post_array['social_site']  == "true"
											   )
											{
												//Redirect will be triggered from js file now
											}
											else{
												wp_safe_redirect($renew_account_url);
											}
											exit;
										}
										else
										{
											$errors->add("login-error",apply_filters("piereg_your_account_is_not_active",__("Your account is not active","pie-register")));
										}
									}elseif(empty($active)){
										if( $user ) {
											wp_set_current_user( $user->ID, $user->user_login );
											wp_set_auth_cookie( $user->ID, $remember_user );
											do_action( 'wp_login', $user->user_login, $user );
										}
										
										do_action('pie_register_after_login',$user);
										do_action("piereg_user_login_before_redirect_hook",$user);
										$this->afterLoginPage();
										exit;
									}
									else
									{
										do_action('pie_register_after_login',$user);
										
										// After Validation Show after login page.
										$option = get_option(OPTION_PIE_REGISTER);
										if(
											   isset($option['social_site_popup_setting']) and 
											   $option['social_site_popup_setting'] == 1 and 
											   $this->pie_post_array['social_site']  == "true"
										   )
										{
											if( $user ) {
												wp_set_current_user( $user->ID, $user->user_login );
												wp_set_auth_cookie( $user->ID, $remember_user );
												do_action( 'wp_login', $user->user_login, $user );
											}
											
											do_action("piereg_user_login_before_redirect_hook",$user);
											$this->afterLoginPage();
											exit;
										}
										else
										{
											if( $user ) {
												wp_set_current_user( $user->ID, $user->user_login );
												wp_set_auth_cookie( $user->ID, $remember_user );
												do_action( 'wp_login', $user->user_login, $user );
											}
											
											do_action("piereg_user_login_before_redirect_hook",$user);
											$this->afterLoginPage();
											exit;
										}
									}
								}
							}
						}	
					}
				}			
			} 
			else 
			{
				$errors->add('login-error',apply_filters("piereg_something_went_wrong_msg",__('Something went wrong.','pie-register')));	
			}
		}
		function piereg_authentication($log,$pwd){
			//authenticate username and password
			$authenticate_user = wp_authenticate($log,$pwd);
			$option = get_option(OPTION_PIE_REGISTER);
			
			return true;
		}
		function piereg_delete_authentication(){
			global $wpdb;
			$table_name = $wpdb->prefix . "pieregister_lockdowns";
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$user_ip = esc_sql($user_ip);
			$wpdb->query($wpdb->prepare("DELETE FROM `".$table_name."` WHERE `user_ip` = %s AND `attempt_from` = 'is_login'",$user_ip));
			if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			return true;
		}
		function pie_check_status_on_login($user, $password){
			$errors = new WP_Error();
			if( ! $user instanceof WP_User ) {
				return $user;
			}
	
			$status = get_user_meta($user->ID,"active",true);
			
			if($status == "0"){
				$message = apply_filters("piereg_your_account_is_not_active",__("Your account is not active","pie-register"));
				
				return new WP_Error( 'login-error', $message );
			}

			return $user;
		}
		function piereg_validate_user_expiry_period_func($user)
		{
			/*
				*	Check Payment Addons
			*/
			
			$is_active = get_user_meta($user->ID,"active",true);
			if( ($this->check_enable_payment_method()) == "true" && $is_active == 1)
			{
				global $wpdb, $wp_session,$errors,$pr_wp_db_prefix;
				$datediff = (strtotime(date_i18n("Y-m-d H:m:s"))) - (strtotime($user->data->user_registered)); // current time - registeration time
				$datediff = floor($datediff/(86400));//60*60*24 = 86400
				$pie_reg = get_option(OPTION_PIE_REGISTER);
				$user_id = $user->ID;
				$pricing_activation_cycle = 0;
				$user_payment_amount = "";
				$payment_activation_cycle = 0;
					
				$piereg_pricing_key_number = get_user_meta( $user_id , "piereg_pricing_key_number" , true );
				$piereg_pricing_key_number = intval($piereg_pricing_key_number);
				
				$user_registered_form_id = get_user_meta($user_id, "user_registered_form_id" , true);
				$piereg_form_pricing_fields = get_option("piereg_form_pricing_fields");
				$piereg_use_starting_period = get_user_meta( $user_id , "use_starting_period" );
				
				if(isset($piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))]) && !empty($piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))]) && empty($piereg_use_starting_period)){
					
					$piereg_pricing_fields = $piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))];
					
					$pricing_for = $piereg_pricing_fields['for'][$piereg_pricing_key_number];
					$pricing_for = intval($pricing_for);
					$pricing_for_period = $piereg_pricing_fields['for_period'][$piereg_pricing_key_number];
					
					if(date_i18n('Y-m-d H:i:s', strtotime('+'.$pricing_for.' '.$pricing_for_period, strtotime($user->data->user_registered))) < date_i18n('Y-m-d H:i:s')){
						update_user_meta( $user->ID, 'use_starting_period', date_i18n('Y-m-d H:i:s'));
						$errors->add('login-error',__("Account suspended. Please renew account.","pie-register"));
						update_user_meta( $user->ID, 'active', 0);
						if((isset($pie_reg['user_enable_user_temp_blocked_notice']) && $pie_reg['user_enable_user_temp_blocked_notice'] == 1) ) 
							$this->wp_mail_send($user->data->user_email,"user_temp_blocked_notice");
						if((isset($pie_reg['user_enable_user_renew_temp_blocked_account_notice']) && $pie_reg['user_enable_user_renew_temp_blocked_account_notice'] == 1) )
							$this->wp_mail_send($user->data->user_email,'user_renew_temp_blocked_account_notice');
						wp_logout();
					}
				}
				else{
					if(isset($piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))]['activation_cycle']) && ((int)($piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))]['activation_cycle'])) > 0)
					{
						$payment_activation_cycle = intval($piereg_form_pricing_fields["form_id_".(intval($user_registered_form_id))]['activation_cycle']);
					}else{
						$payment_activation_cycle = intval($pie_reg['payment_setting_activation_cycle']);
					}
				}
				
				if( ($payment_activation_cycle) != 0)
				{
					$notice_period = ((int)$payment_activation_cycle) - ((int)$pie_reg['payment_setting_expiry_notice_days']);
					if( $datediff <= ((int)$payment_activation_cycle) )
					{
						$daysdiff = (intval($payment_activation_cycle) - $datediff);
						if($daysdiff <= intval($pie_reg['payment_setting_expiry_notice_days']))
						{
							$last_date = ( strtotime(date_i18n("Y-m-d H:m:s")) + (86400 * ( ((int)$payment_activation_cycle) - $datediff ) ) );
							$last_date = date_i18n("Y-m-d",$last_date);
							$email_variable = array();
							$email_variable['user_last_date'] = $last_date;
							if((isset($pie_reg['user_enable_user_expiry_notice']) && $pie_reg['user_enable_user_expiry_notice'] == 1) ) 
								$this->wp_mail_send($user->data->user_email,"user_expiry_notice","","",$email_variable);
						}
					}
					else
					{
						$errors->add('login-error',__("Account has been blocked.","pie-register"));
						update_user_meta( $user->ID, 'active', 0);
						if((isset($pie_reg['user_enable_user_temp_blocked_notice']) && $pie_reg['user_enable_user_temp_blocked_notice'] == 1) ) 
							$this->wp_mail_send($user->data->user_email,"user_temp_blocked_notice");
						if((isset($pie_reg['user_enable_user_renew_temp_blocked_account_notice']) && $pie_reg['user_enable_user_renew_temp_blocked_account_notice'] == 1) )
							$this->wp_mail_send($user->data->user_email,'user_renew_temp_blocked_account_notice');
						wp_logout();
						
					}
				}
			}
		}
		
		//Add the Settings and User Panels
		function AddPanel()
		{
			$update = get_option(OPTION_PIE_REGISTER);

			$count = $this->has_notifications();
			
			$pie_page_suffix_1 = add_menu_page( "Pie Register",  __( 'Pie Register', 'pie-register') , 'manage_options', 'pie-register',  array($this,'PieRegNewForm'), plugins_url("/assets/images/pr_icon.png",__FILE__), 60 );
			add_action('admin_print_scripts-' . $pie_page_suffix_1 , array($this,'pieregister_admin_scripts_styles'));
			
			$pie_page_suffix_2 = add_submenu_page( 'pie-register', 'Manage Forms', __('Manage Forms',"pie-register") , 'manage_options', 'pie-register', array($this, 'PieRegNewForm'));
			add_action('admin_print_scripts-' . $pie_page_suffix_2 , array($this,'pieregister_admin_scripts_styles'));
			add_action('admin_print_scripts-' . $pie_page_suffix_2 , array($this,'pieregister_admin_scripts_styles_about_us_menu'));
			
			do_action("piereg_add_addon_menu_profile_search");
			
			$pie_page_suffix_3 = add_submenu_page( 'admin.php?page=pie-register', 'Manage Forms', __('New Form',"pie-register") , 'manage_options', 'pr_new_registration_form', array($this, 'RegPlusEditForm') );
			add_action('admin_print_scripts-' . $pie_page_suffix_3 , array($this,'pieregister_ck_admin_scripts_styles'));
			
			$pie_page_suffix_14 = add_submenu_page( 'pie-register', 'Notifications Setting', __('Email Notifications',"pie-register") , 'manage_options', 'pie-notifications', array($this, 'PieRegNotifications') );
			add_action('admin_print_scripts-' . $pie_page_suffix_14 , array($this,'pieregister_ck_admin_scripts_styles'));
						
			$pie_page_suffix_9 = add_submenu_page( 'pie-register', 'Invitation Codes', __('Invitation Codes',"pie-register") , 'manage_options', 'pie-invitation-codes', array($this, 'PieRegInvitationCodes'));
			add_action('admin_print_scripts-' . $pie_page_suffix_9 , array($this,'pieregister_admin_scripts_styles'));
			
			if(	is_plugin_active('pie-register-aweber/pie-register-aweber.php') || is_plugin_active('pie-register-mailchimp/pie-register-mailchimp.php') )
			{
				// Restrict Users by username and ip address
				$pie_page_suffix_16 = add_submenu_page( 'pie-register', 'Marketing', __('Marketing',"pie-register") , 'manage_options', 'pie-export-users', array($this, 'PieRegExportUsers'));
				add_action('admin_print_scripts-' . $pie_page_suffix_16, array($this,'pieregister_rw_admin_scripts_styles'));
			}

			if(	is_plugin_active('pie-register-bulkemail/pie-register-bulkemail.php') )
			{
				$addon_BulkEmail_activated = get_option( 'piereg_api_manager_addon_BulkEmail_activated' );
				
				if ($addon_BulkEmail_activated == "Activated")
				{
					$pie_page_suffix_18 = add_submenu_page( 'pie-register', 'Bulk Email', __('Bulk Email',"pie-register") , 'manage_options', 'pie-bulkemail', array($this, 'PieRegBulkEmail'));
					add_action('admin_print_scripts-' . $pie_page_suffix_18, array($this,'pieregister_admin_scripts_styles'));
				}
			}

			/*
			 *	Add Add-ons menu in dashboard
			 */
			do_action("piereg_add_addons_menu");
			
			$pie_page_suffix_5 = add_submenu_page( 'pie-register', 'Payment Gateways Setting', __('Payment Gateways',"pie-register") , 'manage_options', 'pie-gateway-settings', array($this, 'PieRegPaymentGateway') );
			add_action('admin_print_scripts-' . $pie_page_suffix_5 , array($this,'pieregister_admin_scripts_styles'));
			
			$pie_page_suffix_6 = add_submenu_page( 'pie-register', 'Attachments', __('Attachments',"pie-register") , 'manage_options', 'pie-attachments', array($this, 'PieRegAttachment') );
			add_action('admin_print_scripts-' . $pie_page_suffix_6 , array($this,'pieregister_fu_admin_scripts_styles'));

			// Restrict Users by username and ip address
			$pie_page_suffix_15 = add_submenu_page( 'pie-register', 'User Control', __('User Control',"pie-register") , 'manage_options', 'pie-black-listed-users', array($this, 'PieRegRestrictUsers'));
			add_action('admin_print_scripts-' . $pie_page_suffix_15, array($this,'pieregister_rw_admin_scripts_styles'));
			
			$pie_page_suffix_19 = add_submenu_page( 'pie-register', 'User Roles', __('User Roles',"pie-register") , 'manage_options', 'pie-user-roles-custom', array($this, 'PieRegCustomRoles'));
			add_action('admin_print_scripts-' . $pie_page_suffix_19 , array($this,'pieregister_admin_scripts_styles'));
	
			$pie_page_suffix_17 = add_submenu_page( 'pie-register', 'Settings', __('Settings',"pie-register") , 'manage_options', 'pie-settings', array($this, 'PieRegSettings') );
			add_action('admin_print_scripts-' . $pie_page_suffix_17 , array($this,'pieregister_rw_admin_scripts_styles'));
			
			$pie_page_suffix_8 = add_submenu_page( 'pie-register', 'Import/Export', __('Import/Export',"pie-register") , 'manage_options', 'pie-import-export', array($this, 'PieRegImportExport'));
			add_action('admin_print_scripts-' . $pie_page_suffix_8 , array($this,'pieregister_admin_scripts_styles'));
			
			add_filter('set-screen-option', array($this,'pie_set_option'), 10, 3);
			$pie_page_suffix_11 = add_users_page( 'Unverified Users', __('Unverified Users',"pie-register") , 'edit_users', 'unverified-users', [$this, 'Unverified'] );
			add_action('admin_print_scripts-' . $pie_page_suffix_11 , array($this,'pieregister_admin_scripts_styles'));
			add_action( "load-$pie_page_suffix_11", array($this,'pie_add_option') );

			$pie_page_suffix_12 = add_submenu_page( 'pie-register', 'Help', __('Help',"pie-register") , 'manage_options', 'pie-help', array($this, 'PieRegHelp'));
			add_action('admin_print_scripts-' . $pie_page_suffix_12, array($this,'pieregister_admin_scripts_styles'));
			
			// Added since v3.5.4
			$this->no_addon_activated = $this->anyAddonActivated();

			if( $this->no_addon_activated )
			{
				$pie_page_suffix_14 = add_submenu_page( 'pie-register', 'Addons', '<span style="color: #82b3f1">'. __('Addons',"pie-register") .'</span>' , 'manage_options', 'pie-pro-features&tab=addons', array($this, 'PieRegProFeatures'));
				add_action('admin_print_scripts-' . $pie_page_suffix_14, array($this,'pieregister_admin_scripts_styles_go_pro_menu'));
			}

			$pie_page_suffix_13 = add_submenu_page( 'pie-register', 'Go Premium', '<span style="color: #82b3f1">'. __('Go Premium',"pie-register") .'</span>' , 'manage_options', 'pie-pro-features', array($this, 'PieRegProFeatures'));
			add_action('admin_print_scripts-' . $pie_page_suffix_13, array($this,'pieregister_admin_scripts_styles_go_pro_menu'));

			$pie_page_suffix_18 = add_submenu_page( 
				'pie-register', 
				'About Us',
				$count ? sprintf( __( 'About Us <div class="piereg-menu-notification-indicator"></div>', 'pie-register' ) ) : __( 'About Us', 'pie-register') , 'manage_options', 'pie-about-us', array($this, 'PieAboutUs'));
				add_action('admin_print_scripts-' . $pie_page_suffix_18, array($this,'pieregister_admin_scripts_styles_about_us_menu'));
			do_action('pie_register_add_menu');
		}
		function AddPanelOnAdminBar(){
			global $wp_admin_bar, $wpdb;

			$count = $this->has_notifications();

			$pieregister_menu = array(
										array(
											"page"  => "pie-register",
											"title" => "Manage Forms"
										),
										array(
											"page"  => "pie-notifications",
											"title" => "Email Notifications"
										),
										array(
											"page"  => "pie-invitation-codes",
											"title" => "Invitation Codes"
										),
										array(
											"page"  => "pie-settings",
											"title" => "Settings"
										),
										array(
											"page"  => "pie-help",
											"title" => "Help"
										),
										array(
											"page"  => "pie-about-us",
											"title" => __( 'About Us', 'pie-register')
										)
									);
			
			$wp_admin_bar->add_menu( array( 
										'id' => 'pie_register',
										'title' =>$count ? sprintf( __( 'Pie Register <span class="pie-reg-admin-bar-menu-notification-counter">%d</span>', 'pie-register' ), $count ) : __( 'Pie Register', 'pie-register'),
										'href' => get_admin_url().'admin.php?page=pie-register' 
									));
			foreach($pieregister_menu as $pieregister_menu_items){
				$wp_admin_bar->add_menu( array( 
												'parent' => 'pie_register',
												'id' => $pieregister_menu_items['title'],
												'title' => $pieregister_menu_items['title'],
												'href' => get_admin_url().'admin.php?page='.$pieregister_menu_items['page']
											   )
										);

			}

		}
		/**
         * Load the plugin admin notifications functionality and initializes it.
         *
         * @return Notifications
         */
        public function get_notifications() {

            static $notifications;

            if ( ! isset( $notifications ) ) {
                $notifications = apply_filters(
                    'piereg_get_notifications',
                    new PieReg_Admin_Notices()
                );

                if ( method_exists( $notifications, 'init' ) ) {
                    $notifications->init();
                }
            }

            return $notifications;
        }
		/**
		 * Check if new notifications are available.
		 *
		 *
		 * @return bool
		 */
		public function has_notifications() {
			
			return $this->get_notifications()->get_count();
		}
		
		function pieregister_admin_scripts_styles(){
			//$this->pie_admin_enqueu_scripts();
		}
		function pieregister_admin_scripts_styles_go_pro_menu()
		{
			$this->pieregister_admin_scripts_styles();
			wp_enqueue_style("pie_go_pro_menu_css" );
			
		}
		function pieregister_admin_scripts_styles_about_us_menu()
		{
			wp_enqueue_style("pie_admin_about_us_menu_css" );
			wp_enqueue_script("pie_admin_about_us_menu_js" );
			$this->pieregister_admin_scripts_styles_slick();
			
			wp_enqueue_style('pie_notification_css');
			wp_enqueue_script('pie_notification_js');
		}
		function pieregister_admin_scripts_styles_slick(){
			wp_enqueue_style("pie_admin_about_us_slick_css" );
			wp_enqueue_script("pie_admin_about_us_slick_js" );
		}
		function pieregister_ck_admin_scripts_styles(){
			//$this->pie_admin_enqueu_scripts();
			//wp_enqueue_editor();
			wp_enqueue_script("pie_ckeditor");
		}
		function pieregister_rw_admin_scripts_styles(){
			//$this->pie_admin_enqueu_scripts();
			wp_enqueue_style("pie_restrict_widget_css" );
			wp_enqueue_script("pie_restrict_widget_script");
		}

		function pieregister_fu_admin_scripts_styles(){
			//$this->pie_admin_enqueu_scripts();
			wp_enqueue_style("pie_wload_css" );
			wp_enqueue_script("pie_wload_js");
		}
		
		function saveFields()
		{
			$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
			if(isset($_POST['piereg_reg_form_nonce']) && wp_verify_nonce( $_POST['piereg_reg_form_nonce'], 'piereg_wp_reg_form_nonce' ))
			{
				$math_cpatcha_enable 			 = "false";
				$piereg_startingDate 			 = "1901";
				$piereg_endingDate 				 = date_i18n("Y");
				$piereg_form_pricing_fields 	 = get_option("piereg_form_pricing_fields");
				$piereg_form_pricing_fields_temp = array();				
				
				$this->pie_post_array['field']	= $this->piereg_sanitize_post_data( 'piereg_form_builder',  ( (isset($_POST['field']) && !empty($_POST['field']))?$_POST['field'] : array() ) );
				
				foreach($this->pie_post_array['field'] as $k=>$fv){
					
					
					if($fv['type'] == 'math_captcha'){
						$math_cpatcha_enable = "true";
					}

					if($fv['type'] == 'submit'){
						if( isset($fv["redirect_url"]) && !empty($fv["redirect_url"]) ){
							$fv["redirect_url"]  = preg_replace("/^www./i", "https://", $fv["redirect_url"] );
						}
					}
					
					//since 2.0.12					
					if($fv['type'] == 'password'){
						$meter_label_options = get_option(OPTION_PIE_REGISTER);			
						
						$meter_label_options['pass_strength_indicator_label'] 	= $fv['pass_strength_indicator_label'];
						$meter_label_options['pass_very_weak_label'] 			= $fv['pass_very_weak_label'];
						$meter_label_options['pass_weak_label'] 				= $fv['pass_weak_label'];
						$meter_label_options['pass_medium_label'] 				= $fv['pass_medium_label'];
						$meter_label_options['pass_strong_label'] 				= $fv['pass_strong_label'];
						$meter_label_options['pass_mismatch_label'] 			= $fv['pass_mismatch_label'];
						
						update_option(OPTION_PIE_REGISTER, $meter_label_options );
						PieReg_Base::set_pr_global_options($meter_label_options, OPTION_PIE_REGISTER );
					}
						
						if(isset($fv['desc'])) {
							$fv['desc'] 	= $this->sanitize_field_options($fv['desc']);							
						}
						
						if(isset($fv['label'])) {
							$fv['label'] 	= $this->sanitize_field_options($fv['label']); 
						}
						
						if(isset($fv['validation_message'])) {
							$fv['validation_message'] = $this->sanitize_field_options($fv['validation_message']); 
						}
						
						if(isset($fv['css'])) {
							$fv['css'] = $this->sanitize_field_options($fv['css']); 
						}
						 
						if(isset($fv['default_value'])) {
							$fv['default_value'] = $this->sanitize_field_options($fv['default_value']); 
						}
						
						if(isset($fv['placeholder'])) {
							$fv['placeholder'] = $this->sanitize_field_options($fv['placeholder']); 
						}
					
					if($fv['type'] == 'date'){
						$pattern = '/[0-9]{4}/';
						$subject = isset($fv['startingDate']) ? $fv['startingDate'] : "";
						if(
							( (isset($fv['startingDate']) && strlen($fv['startingDate']) == 4) && preg_match($pattern, $subject))&&
							(intval($fv['startingDate']) <= intval($fv['endingDate']))
						  ){
							$fv['startingDate'] = $fv['startingDate'];
							$piereg_startingDate = $fv['startingDate'];
						}
						else{
							$fv['startingDate'] = "1901";
							$piereg_startingDate = "1900";
						}
							
						$subject = isset($fv['endingDate']) ? $fv['endingDate'] : "";
						if(
						   ( (isset($fv['endingDate']) && strlen($fv['endingDate']) == 4) && preg_match($pattern, $subject)) && 
						   (intval($fv['endingDate']) >= intval($fv['startingDate']))
						   ){
							$fv['endingDate'] = $fv['endingDate'];
							$piereg_endingDate = $fv['endingDate'];
						}
						else{
							$fv['endingDate'] = date_i18n("Y");
							$piereg_endingDate = date_i18n("Y");
						}
					}
					
					if($fv['type'] == "pricing" && "not" == "required" )
					{
						$piereg_form_pricing_fields_temp = $fv;
						
						foreach($fv['starting_price'] as $starting_price_key=>$starting_price_val)
						{
							$fv['starting_price'][$starting_price_key] = sprintf('%0.2f', $starting_price_val);
						}
						
						foreach($fv['then_price'] as $then_price_key=>$then_price_val)
						{
							$fv['then_price'][$then_price_key] = sprintf('%0.2f', $then_price_val);
						}
						
					}
					$updated_post[$k] = $fv;
				}
				if(!$this->pie_post_array['field'])
					$this->pie_post_array['field'] =  get_option( 'pie_fields_default' );
				
				do_action("pie_fields_save");
				update_option("pie_fields",serialize($updated_post));
				
				/*modify code for multiple registration form*/
				if(isset($_POST['form_id']) and intval(base64_decode($_POST['form_id'])) != 0 and isset($_POST['page']) and $_POST['page'] == "edit")
				{
					$fields_id = intval(base64_decode($_POST['form_id']));
					
					// update fields
					update_option("piereg_form_fields_".$fields_id,serialize($updated_post));
					$piereg_form_pricing_fields["form_id_".$fields_id] = $piereg_form_pricing_fields_temp;
					update_option("piereg_form_pricing_fields",$piereg_form_pricing_fields);
					
					// update user role
					$options = get_option(OPTION_PIE_REGISTER);
					$options['pie_regis_set_user_role_'.$fields_id] = sanitize_key($_POST['set_user_role_']);
					$options['piereg_startingDate_'.$fields_id] = $piereg_startingDate;
					$options['piereg_endingDate_'.$fields_id] = $piereg_endingDate;
					
					// sync form fields with profile search forms
					$pie_ps_form_id = isset($options['piereg_profile_search_form_id']) ? $options['piereg_profile_search_form_id'] : "";
					if(!empty($pie_ps_form_id) && $pie_ps_form_id == $fields_id){
						
						$match_form_fields = array();
						foreach($updated_post as $updated_posts){							
							if( !isset($updated_posts['label']) )
								$updated_posts['label'] = "";
								
							if($updated_posts['type'] == 'name'){
								$first_name = strtolower(str_replace(' ' , '_', $updated_posts['label']));
								$match_form_fields[$first_name.'+:+'.$updated_posts['label']] = $updated_posts['label'];
								$last_name = strtolower(str_replace(' ' , '_', $updated_posts['label2']));
								$match_form_fields[$last_name.'+:+'.$updated_posts['label']] = $updated_posts['label'];
							}elseif($updated_posts['type'] == 'username'){
								$username = str_replace(' ' , '_', $updated_posts['type']);
								$match_form_fields[$username.'+:+'.$updated_posts['label']] = $updated_posts['label'];
							}elseif($updated_posts['type'] == 'email'){
								$email = str_replace(' ' , '_', $updated_posts['type']);
								$match_form_fields[$email.'+:+'.$updated_posts['label']] = $updated_posts['label'];
							}elseif($updated_posts['type'] != 'math_captcha' && $updated_posts['type'] != 'captcha' && $updated_posts['type'] != 'password' && $updated_posts['type'] != 'form' && $updated_posts['type'] != 'submit' && $updated_posts['type'] != 'upload'){
								$other_field = 'pie_'.str_replace(' ' , '_', $updated_posts['type']).'_'.$updated_posts['id'];
								$match_form_fields[$other_field.'+:+'.$updated_posts['label']] = $updated_posts['label'];
							}							
						}
						
						foreach($options['piereg_ps_psv_fields_label'] as $ps_fields_array){
							if(!empty($ps_fields_array)){
								foreach($ps_fields_array as $key_array=>$ps_fields_arr){
									foreach($ps_fields_arr as $key=>$ps_fields){
										if($key != 'full_name' && $key != 'registered'){
											$key_check = $key.'+:+'.$ps_fields;
											if(!array_key_exists($key_check, $match_form_fields)){
												unset($ps_fields_arr[$key]);
											}
										}
									}
									$ps_fields_ar[$key_array] = $ps_fields_arr;
								}
							}
						}
						
						if(!empty($ps_fields_ar)){
							if( empty($ps_fields_ar[1]) && array_key_exists(1, $ps_fields_ar) ){
								unset($ps_fields_ar[1]);
							}elseif( empty($ps_fields_ar[2]) && array_key_exists(2, $ps_fields_ar) ){
								unset($ps_fields_ar[2]);
							}elseif( empty($ps_fields_ar[3]) && array_key_exists(3, $ps_fields_ar) ){
								unset($ps_fields_ar[3]);
							}
							$ps_fields_a[$pie_ps_form_id] = $ps_fields_ar;
							$options['piereg_ps_psv_fields_label'] = $ps_fields_a;
						}
					}
					
					// Update Wordpress Drefault User Role
					update_option(OPTION_PIE_REGISTER,$options);
					//update form title
					$_field['Id'] 		= intval( $fields_id );
					$_field 			= get_option("piereg_form_field_option_".$fields_id);
					$_field['Title'] 	= trim( $this->pie_post_array['field']['form']['label'] );
					update_option("piereg_form_field_option_".$fields_id,$_field);
				}
				$this->pie_post_array['success_message'] = __("Settings Saved","pie-register");
			}
			else{
				$this->pie_post_array['error_message'] = __("Invalid nonce.","pie-register");
			}
		}
		
		function pieregister_login()
		{
			$option = get_option(OPTION_PIE_REGISTER);					
			if($option['allow_pr_edit_wplogin'] == 1){
				global $errors;
				if (isset($_REQUEST['action'])) :
					$action = sanitize_key($_REQUEST['action']);
				else :
					$action = 'login';
				endif;
				switch($action) :
					case 'lostpassword' :
					case 'retrievepassword' :
						$this->process_lostpassword();
					break;
					case 'resetpass' :
					case 'rp' :
						$this->process_getpassword();
					break;	
					case 'register':
						$this->process_register_form();		
					case 'login':
					default:
						$this->process_login_form();
					break;
				endswitch;	
				exit;
			}
			return false;
		}
		function process_register_form()
		{
			
			global $errors;
		
			$form 		= new Registration_form();
			$success 	= '' ;	
			
			get_header();
			//Printing Success Message
			echo wp_kses($this->outputRegForm("true","true"), $this->piereg_forms_get_allowed_tags());
			$this->set_pr_stats("register","view");
			get_footer();	
			
			exit;
		}
		
		function check_register_form()
		{
			global $errors, $wp_session, $piereg_post_array;
			$option = get_option(OPTION_PIE_REGISTER);	
			
			if(	$this->check_enable_payment_method() == "false" )
			{
				$this->pie_save_registration();
			}
			else if(($this->check_enable_payment_method()) == "true")
			{
				$pricing_key_number = 0;
				$pricing_payment_amount = 0;
				if(isset($_POST['pricing'])){
					$piereg_form_pricing_fields = get_option("piereg_form_pricing_fields");
					foreach($piereg_form_pricing_fields['form_id_'.intval($_POST['form_id'])]['display'] as $pricing_key=>$pricing_value){
						if($pricing_value == $_POST['pricing']){
							$pricing_key_number = $pricing_key;
							break;
						}
					}
					$pricing_payment_amount = $piereg_form_pricing_fields['form_id_'.intval($_POST['form_id'])]["starting_price"][$pricing_key_number];
				}
				
				if(
				   	(isset($_POST['select_payment_method']) and trim($_POST['select_payment_method']) != "" and $_POST['select_payment_method'] != "select")
					//|| ($pricing_payment_amount <= 0)
				)
				{
					if((isset($_POST['select_payment_method']) && ($_POST['select_payment_method'] == 'stripe') ) && (isset($_POST['error_stripe']) && $_POST['error_stripe'] != ""))
					{
						$this->pie_post_array['error'] = sanitize_text_field($_POST['error_stripe']);
					}
					else if((isset($_POST['select_payment_method']) && ($_POST['select_payment_method'] == 'authorizeNet') ) && (isset($_POST['error_authorizeNet']) && $_POST['error_authorizeNet'] != "") ){
					    $this->pie_post_array['error'] = sanitize_text_field($_POST['error_authorizeNet']);
                    }
                    else {
						$this->pie_save_registration();
					}
				}
				else if( isset($_POST['select_payment_method']) && ( empty($_POST['select_payment_method']) || $_POST['select_payment_method'] == "select" ) )
				{
					$this->pie_post_array['error'] = __("Please select a payment method","pie-register");	
				}
				else{
					$this->pie_save_registration();	
				}
			}
			else if(trim($wp_session['payment_error']) != "")
			{
				$this->pie_post_array['error'] = __($wp_session['payment_error'],"pie-register");
				$wp_session['payment_error'] = "";
				$wp_session['payment_success'] = "";
			}
			
			$piereg_post_array		= $this->pie_post_array;
		}
		function piereg_generate_username($email = "",$is_generate_username = false,$username_prifex = ""){
			if($is_generate_username && !isset($_POST['username']) ){
				$username = "";
				while(1){
					$username = strtolower( ( !empty($username_prifex) ? $username_prifex . "_" : "" ) .wp_generate_password( 7, false, false));
					if(!username_exists($username)){
						break;
					}
				}
				$_POST['username'] = sanitize_user(trim($username));
			}
			else if(isset($_POST['e_mail'],$email) && !empty($_POST['e_mail']) && !isset($_POST['username']))
				$_POST['username'] = sanitize_email($_POST['e_mail']);
			else if(!isset($_POST['username']))
				$this->piereg_generate_username("",true);
		}
		function pie_save_registration()
		{
			
			$form_id = 0;
			
			if(isset($_POST['form_id']) && intval($_POST['form_id']) > 0)
			{
				$form_id = intval($_POST['form_id']);
			}
			else{
				global $errors;
				$errors = new WP_Error();
				$errors->add("registration-error","Invalid Form");
				return false;
			}
			
			$this->piereg_generate_username(sanitize_email($_POST['e_mail']), apply_filters("piereg_generate_unique_username",false), apply_filters("piereg_generate_username_with_prifex",false));
			
			add_filter('wp_mail_content_type', array($this,'set_html_content_type'));
			global $errors;
			$form 		= new Registration_form($form_id);
			$errors 	= $form->validateRegistration($errors);
			$option 	= get_option(OPTION_PIE_REGISTER);
			
			do_action('pie_register_before_register_validate');	
			
			$option_user_verification 	= $option['verification'];
			$form_user_verification 	= isset($form->data['form']['user_verification']) ? $form->data['form']['user_verification'] : "";
			
			if(intval($form_user_verification) > 0)
			{
				$option_user_verification = intval($form_user_verification);
			}
			
			if(sizeof($errors->errors) == 0)
			{
				do_action('pie_register_after_register_validate');	
				global $piereg_post_array, $wp_session;
				
				/**
				 * Since version 3.6.14
				 * Removed strtolower from $_POST['e_mail']
				 */

				//Inserting User
				$pass = html_entity_decode($_POST['password']);
				$user_data = array(
								   'user_pass' 	=> $pass,
								   'user_login' => sanitize_user($_POST['username']),
								   'user_email' => sanitize_email($_POST['e_mail']),
								   'role' 		=> get_option('default_role')
								  );
				
				if(isset($_POST['first_name']) && !empty($_POST['first_name']) )
				{
					$display_name = sanitize_text_field($_POST['first_name']).((isset($_POST['last_name']) && !empty($_POST['last_name']))?" ".sanitize_text_field($_POST['last_name']):"");
					$user_data['display_name'] 	= $display_name;
					$user_data['user_nicename'] = $display_name;
				}
				
				if(isset($_POST['url']))
				{
					$user_data["user_url"] =  esc_url_raw($_POST['url']);
				}
				
				$this->set_pr_stats("register","used");
				$user_id = wp_insert_user( $user_data );
				
				/*
					*	Check Pricing
				*/
				$pricing_user_role = "";
				$pricing_key_number = 0;
				$pricing_payment_amount = 0;
				$pricing_activation_cycle = 0;
				$piereg_form_pricing_fields = "";
				
				if(isset($_POST['pricing'])){
					$piereg_form_pricing_fields = get_option("piereg_form_pricing_fields");
					foreach($piereg_form_pricing_fields['form_id_'.intval($form_id)]['display'] as $pricing_key=>$pricing_value){
						if($pricing_value == $_POST['pricing']){
							$pricing_key_number = $pricing_key;
							break;
						}
					}
					$pricing_user_role = $piereg_form_pricing_fields['form_id_'.intval($form_id)]["role"][$pricing_key_number];
					$pricing_payment_amount = $piereg_form_pricing_fields['form_id_'.intval($form_id)]["starting_price"][$pricing_key_number];
					$pricing_activation_cycle = ( ( intval($piereg_form_pricing_fields['form_id_'.intval($form_id)]["activation_cycle"][$pricing_key_number]) >= 0 )? intval($piereg_form_pricing_fields['form_id_'.intval($form_id)]["activation_cycle"][$pricing_key_number]) : intval($option['payment_setting_activation_cycle']) );
				}else{
					$pricing_payment_amount = $option['payment_setting_amount'];
					$pricing_activation_cycle = (isset($option['payment_setting_activation_cycle'])) ? $option['payment_setting_activation_cycle'] : "";
				}
				/***** End Pricing *****/
				$form->addUser($user_id,$form_id);

				# If user choose to add in mailchimp list.	
				if(isset($_POST['checkbox_add_to_mailchimp']) && !empty($_POST['checkbox_add_to_mailchimp']) )
				{
					update_user_meta( $user_id, 'pie_checkbox_add_to_mailchimp', wp_kses_post($_POST['checkbox_add_to_mailchimp']) );
				}
				
				/*
					*	Update Nickname User Meta
				*/
				if(isset($_POST['first_name']) && !empty($_POST['first_name']) )
				{
					update_user_meta( $user_id, 'nickname', (sanitize_text_field($_POST['first_name']) . ((isset($_POST['last_name']) && !empty($_POST['last_name'])) ? " " . sanitize_text_field($_POST['last_name']) : "" )) );
				}
				$new_role = 'subscriber';
				if( intval($_POST['form_id']) != 0)
				{
					$new_role = ($option['pie_regis_set_user_role_'.intval($form_id)]); 		// Till v3.0.8 using strtolower()
					$new_role = ( $new_role != "") ? $new_role : 'subscriber'; 
					$form_options = get_option("piereg_form_field_option_".intval($form_id));
					$form_options['Entries'] = intval($form_options['Entries']) + 1;
					update_option("piereg_form_field_option_".intval($form_id),$form_options);
					unset($form_options);
				}
				else
				{
					if(isset($option['pie_regis_set_user_role_']) and trim($option['pie_regis_set_user_role_']) != "")
					{
						$new_role = strtolower($option['pie_regis_set_user_role_']);
						$new_role = ( $new_role != "") ? $new_role : 'subscriber';
					}
				}
				// dropdown_ur
				if(isset($_POST['custom_role']) && !empty($_POST['custom_role'])){
					$field_options = get_option("pie_fields");
					$field_options = maybe_unserialize($field_options);
					foreach($field_options as $field_key){
						if($field_key['type'] == 'custom_role'){
							foreach($field_key['value'] as $key=>$value){
								if($value == $_POST['custom_role']){
									$new_role = $field_key['role_selection'][ $key];
									// update_user_meta($user_id, "custom_role", $new_role);
									break;
								}
							}
							break;
						}
					}
				}

				/*
					*	Add Pricing Role
				*/
				$new_role = ( ( isset($pricing_user_role) && !empty($pricing_user_role) ) ? trim($pricing_user_role) : $new_role );
				//// update user role using wordpress function
				wp_update_user( array ('ID' => $user_id, 'role' => $new_role ) ) ;
				update_user_meta( $user_id, "is_social", "false", $unique = false );
				update_user_meta( $user_id, "social_site_name", "", $unique = false );
				update_user_meta( $user_id, "user_registered_form_id", ((int)$form_id) );
				/*
					* User Meta for Pricing
				*/
				update_user_meta( $user_id, "piereg_pricing_key_number", $pricing_key_number );
				update_user_meta( $user_id, "piereg_pricing_payment_amount", $pricing_payment_amount );
				update_user_meta( $user_id, "piereg_pricing_user_role", $pricing_user_role );
				update_user_meta( $user_id, "piereg_pricing_activation_cycle", $pricing_activation_cycle );
				
				$user 		= new WP_User($user_id);
				
				/*
					*	Add pricing variables in user object
				*/
				$user_array = (array) $user;
				$user_array['piereg_pricing']['pricing_key_number'] = $pricing_key_number;
				$user_array['piereg_pricing']['pricing_payment_amount'] = $pricing_payment_amount;
				$user = (object) $user_array;
				
				do_action('pie_register_after_user_created',$user);
				
				///////////////////////////////////////////////////
				/******** Admin Notification *******/
				if($option_user_verification == 0 || $option_user_verification == 2){
					$this->send_admin_notifications($option,$user,$pass);
				}
				////////////////////////////////////////////////////
				
				// until multiple payment gateways release we use this variable to get away with multiple gateway process
				$isPayPalStandard = false;
				if( isset($_POST['select_payment_method']) && $_POST['select_payment_method'] == 'PaypalStandard'  ) {
					$isPayPalStandard = true;
				}
								
				if( 'recurring' == 'enable' )
					$checkStartPayment = true;
				else 
					$checkStartPayment = false;
				
				if( $isPayPalStandard == false && isset($user_array['piereg_pricing']['pricing_payment_amount']) && floatval($user_array['piereg_pricing']['pricing_payment_amount']) <= 0){
					$checkStartPayment = false;
				}
				
				
				
				/*Goto payment method Like check_payment_method_paypal*/
				if( $isPayPalStandard == false && $this->check_enable_payment_method() == "true" && ($checkStartPayment || isset($_POST['select_payment_method'])  ) )
				{
					
					if( $_POST['select_payment_method'] == "stripe" || $_POST['select_payment_method'] == "authorizeNet"){
						update_user_meta( $user_id, 'register_type', "payment_verify");
						$_POST['user_id'] = $user_id;
						update_user_meta( $user_id, 'active', 0);
						/*
							*	trigger Wordpress "user_register" hook
						*/											
						
						do_action("user_register",$user_id);
						do_action("check_payment_method_".sanitize_text_field($_POST['select_payment_method']),$user);// function prefix check_payment_method_
						
						// If payment failed
						if(!empty($_POST['error'])) 	{
							$this->pie_post_array['payment_failed'] = 1;
							$this->pie_post_array['error'] 	 		= sanitize_text_field($_POST['error']);
						}
						
						// If payment success
						if(!empty($_POST['success']))	$this->pie_post_array['success'] = sanitize_text_field($_POST['success']);				
						
					}
					
					if( (($this->check_enable_payment_method()) == "true" and !isset($_POST['select_payment_method']) and isset($_POST['pricing']) ) or
						( isset($_POST['select_payment_method']) and $_POST['select_payment_method'] == "" or $_POST['select_payment_method'] == "select")
					  )
					{
						$this->pie_post_array['error'] = __("Please select a payment method","pie-register");
					}
				}
				else if( $isPayPalStandard == true && (!(empty($option['paypal_butt_id'])) && $option['enable_paypal']==1) 
										/*&&
                                        (	$option['enable_authorize_net'] != 1 &&
                                        	$option['enable_2checkout'] 	!= 1 &&
                                        	$option['enable_PaypalPro'] 	!= 1 &&
                                        	$option['enable_PaypalExp'] 	!= 1 &&
                                        	$option['enable_Skrill'] 		!= 1 )*/
                                        )
				{
					$_POST['user_id'] = $user_id;
					update_user_meta( $user_id, 'active', 0);
					update_user_meta( $user_id, 'register_type', "payment_verify");
					do_action("user_register",$user_id); // temp

					do_action("check_payment_method_paypal",$user);// function prefix check_payment_method_
				}
				else if($option_user_verification == 1 )//Admin Verification
				{
					update_user_meta( $user_id, 'active', 0);
					$admin_hash = md5( time() );
					update_user_meta( $user_id, 'admin_hash', $admin_hash);
					update_user_meta( $user_id, 'register_type', "admin_verify");

					$this->send_admin_notifications($option,$user,$pass);

					$subject 		= html_entity_decode($option['user_subject_email_admin_verification'],ENT_COMPAT,"UTF-8");
					$subject = $this->filterSubject($user,$subject);
					$message_temp = "";
					if($option['user_formate_email_admin_verification'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_admin_verification']));
					}else{
						$message_temp	= $option['user_message_email_admin_verification'];
					}
					$message		= $form->filterEmail($message_temp,$user, $pass );
					$from_name		= $option['user_from_name_admin_verification'];
					$from_email		= $option['user_from_email_admin_verification'];					
					$reply_email 	= $option['user_to_email_admin_verification'];
							
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
					
					if((isset($option['user_enable_admin_verification']) && $option['user_enable_admin_verification'] == 1) && !wp_mail(sanitize_email($_POST['e_mail']), $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					$this->pie_post_array['registration_success'] = apply_filters("piereg_thank_you_for_your_registration_admin_veri",__("Thank you for submitting a registration request. An email will be sent when the administrator has reviewed the request.",'pie-register'));
				}
				
				else if($option_user_verification == 2 )//E-Mail Link Verification
				{
					update_user_meta( $user_id, 'active', 0);
					$hash = md5( time() );
					update_user_meta( $user_id, 'hash', $hash );
					update_user_meta( $user_id, 'register_type', "email_verify");
					
					$subject 		= html_entity_decode($option['user_subject_email_email_verification'],ENT_COMPAT,"UTF-8");
					$subject = $this->filterSubject($user,$subject);
					$message_temp = "";
					if($option['user_formate_email_email_verification'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_email_verification']));
					}else{
						$message_temp	= $option['user_message_email_email_verification'];
					}
					$message		= $form->filterEmail($message_temp,$user, $pass );
					$from_name		= $option['user_from_name_email_verification'];
					$from_email		= $option['user_from_email_email_verification'];					
					$reply_email 	= $option['user_to_email_email_verification'];
					
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
					
					
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";	
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
								
					if((isset($option['user_enable_email_verification']) && $option['user_enable_email_verification'] == 1) && !wp_mail(sanitize_email($_POST['e_mail']), $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					$this->pie_post_array['registration_success'] = apply_filters("piereg_thank_you_for_your_registration_email_veri",__("Thank you for submitting a registration request. An email has been sent to verify the email address.",'pie-register'));
						
				}
				
				else if($option_user_verification == 0 )//No verification required
				{
					update_user_meta( $user_id, 'active', 1);
					
					$subject 		= html_entity_decode($option['user_subject_email_default_template'],ENT_COMPAT,"UTF-8");
					$subject = $this->filterSubject($user,$subject);
					$message_temp = "";
					if($option['user_formate_email_default_template'] == "0"){
						$message_temp	= (strip_tags($option['user_message_email_default_template']));
					}else{
						$message_temp	= $option['user_message_email_default_template'];
					}
					$message		= $form->filterEmail($message_temp,$user, $pass );
					$from_name		= $option['user_from_name_default_template'];
					$from_email		= $option['user_from_email_default_template'];		
					$reply_email 	= $option['user_to_email_default_template'];					
							
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL)){//Validating From
						$headers .= "From: ".$from_name." <".$from_email."> \r\n";
					}
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
							
					if((isset($option['user_enable_default_template']) && $option['user_enable_default_template'] == 1) && !wp_mail(sanitize_email($_POST['e_mail']), $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					
				}
				
				if( !isset($this->pie_post_array['payment_failed']) )
				{
					/*
						*	trigger Wordpress "user_register" hook
					*/
					do_action("user_register",$user->ID);
					do_action('pie_register_after_register',$user);
					/*
						*	User Verification and payment methods are off then Trigger Login After Registration Hook
					*/
					
					if($option_user_verification == 0  && $this->check_enable_payment_method() == "false" ){
						do_action('pie_register_login_after_registration',$user);
					}
				}
				$fields 			= maybe_unserialize(get_option("pie_fields"));
				$confirmation_type 	= $form->data['submit']['confirmation'];
				
				if( isset($wp_session['payment_error']) && trim($wp_session['payment_error']) != "")
				{
					$this->pie_post_array['error'] = __($wp_session['payment_error'],"pie-register");
					$wp_session['payment_error'] = "";
					$wp_session['payment_success'] = "";
				}
				else if( isset( $this->pie_post_array['payment_failed'] ) && $this->pie_post_array['payment_failed'] == 1 ) {
					$this->pie_post_array['error'] = $this->pie_post_array['error']; 
				}
				else if( isset($wp_session['payment_success']) && trim($wp_session['payment_success']) != "")
				{					
					$this->pie_post_array['registration_success'] = apply_filters("piereg_payment_success",__($wp_session['payment_success'],"pie-register"));
					$wp_session['payment_error'] = "";
					$wp_session['payment_success'] = "";
				}
				else if($confirmation_type == "" || $confirmation_type == "text" )
				{
					if(empty($this->pie_post_array['registration_success']))
						$this->pie_post_array['registration_success']	= __($form->data['submit']['message'],"pie-register");
				}
				else if($confirmation_type == "page")
				{
					wp_safe_redirect(get_permalink($form->data['submit']['page']));
					exit;
				}
				else if($confirmation_type == "redirect")
				{
					wp_redirect(esc_url_raw($form->data['submit']['redirect_url']));
					exit;
				}
				else
				{
					if(empty($this->pie_post_array['registration_success']))
						$this->pie_post_array['registration_success']	= __($form->data['submit']['message'],"pie-register");
				}
				
				$piereg_post_array		= $this->pie_post_array;
			}
		}
		
		function send_admin_notifications($option,$user,$pass){
			$is_allowed = true;
			$is_allowed = apply_filters('piereg_check_email_allowed_to_send', $is_allowed, $user);

			/******** Admin Notification *******/
			if( $option['enable_admin_notifications']=="1" && $is_allowed )
			{
				$message_temp = "";
				if($option['admin_message_email_formate'] == "0"){
					$message_temp	= (strip_tags($option['admin_message_email']));
				}else{
					$message_temp	= $option['admin_message_email'];
				}
				$message  		= $this->filterEmail($message_temp,$user,$pass);
				$subject		= html_entity_decode($option['admin_subject_email'],ENT_COMPAT,"UTF-8");
				$subject		= $this->filterSubject($user,$subject);
				$to				= trim($option['admin_sendto_email']); // values can be comma seperated.
				$from_name		= trim($option['admin_from_name']);
				$from_email		= trim($option['admin_from_email']);
				$bcc			= trim($option['admin_bcc_email']);
				$reply_to_email	= trim($option['admin_to_email']);
				
				if(empty($to))//if not valid email address then use wordpress default admin
				{
					$to = get_option('admin_email');
				}
				
				//Headers
				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
				
				if(!empty($from_email))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";
				
				if(!empty($bcc))//Validating BCC
					$headers .= "Bcc: " . $bcc . " \r\n";
				
				if(!empty($reply_to_email))//Validating Reply To
					$headers .= "Reply-To: <".$reply_to_email."> \r\n";
				
				if($reply_to_email)
					$headers .= "Return-Path: ".$reply_to_email." \r\n";
				else
					$headers .= "Return-Path: ".$from_email." \r\n";
	
				do_action("piereg_action_before_admin_notify_email", $option, $user); # newlyAddedHookFilter
				if(!wp_mail($to,$subject, $message,$headers)){
					$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
			}
		}
		
		function check_payment_method_paypal($user)
		{
			$user_id = intval($_POST['user_id']);
			$user_email = sanitize_email($_POST['e_mail']);
			add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
			global $errors;
			$form 		= new Registration_form();
			$errors 	= $form->validateRegistration($errors);
			$option 	= get_option(OPTION_PIE_REGISTER);
			
			update_user_meta( $user_id, 'active', 0);
			$hash = md5( time() );
			update_user_meta( $user_id, 'hash', $hash );
			
			$subject 		= html_entity_decode($option['user_subject_email_pending_payment'],ENT_COMPAT,"UTF-8");
			$subject = $this->filterSubject($user_email,$subject);
			$message_temp = "";
			if($option['user_formate_email_pending_payment'] == "0"){
				$message_temp	= nl2br(strip_tags($option['user_message_email_pending_payment']));
			}else{
				$message_temp	= $option['user_message_email_pending_payment'];
			}
			
			$message		= $form->filterEmail($message_temp,$user_email);
			$from_name		= $option['user_from_name_pending_payment'];
			$from_email		= $option['user_from_email_pending_payment'];
			$reply_email	= $option['user_to_email_pending_payment'];
					
			//Headers
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		
			if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
			$headers .= "From: ".$from_name." <".$from_email."> \r\n";	
			if($reply_email){
				$headers .= "Reply-To: {$reply_email}\r\n";
				$headers .= "Return-Path: {$reply_email}\r\n";
			}else{
				$headers .= "Reply-To: {$from_email}\r\n";
				$headers .= "Return-Path: {$from_email}\r\n";
			}		
			
			if( (isset($option['user_enable_pending_payment']) && $option['user_enable_pending_payment'] == 1) &&  !wp_mail(sanitize_email($_POST['e_mail']), $subject, $message , $headers)){
				$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			update_user_meta( $user_id, 'register_type', "payment_verify");
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
			if($option['paypal_sandbox']=="no")
			{
				$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
				
			}
			else
			{
				$paypal_url = "https://sandbox.paypal.com/cgi-bin/webscr";
			}
			
			$nvpStr = 	"?cmd=_s-xclick&hosted_button_id=".trim($option['paypal_butt_id']).
						"&custom=".$hash.'__'.$user_id.
						"&bn=Genetech_SI_Custom".
						"&cancel_return=".urlencode( trailingslashit(get_bloginfo("url")).'?action=payment_cancel&paypal='.base64_encode($user_id) ).
						"&notify_url=".urlencode( trailingslashit(get_bloginfo("url")).'?action=ipn_success&paypal='.base64_encode( $hash.'|'.$user_id ) );
						
			wp_redirect(esc_url_raw($paypal_url.$nvpStr));
			exit;
		}
	
		function process_lostpassword()
		{
			global $errors ;
			if( file_exists(PIEREG_DIR_NAME . "/forgot_password.php") )
				include_once("forgot_password.php");
			get_header();	
			$this->set_pr_stats("forgot","view");
			
			$output =  pieResetFormOutput();
			echo wp_kses( $output, $this->piereg_forms_get_allowed_tags() );
			get_footer();
			exit;
		}
		function process_getpassword()
		{
			global $errors ;
			$user 		= check_password_reset_key(sanitize_text_field($_GET['key']), sanitize_user($_GET['login']));
			if ( is_wp_error($user) ) 
			{	
				wp_safe_redirect( site_url('wp-login.php?action=lostpassword&error=invalidkey') );
				exit;
			}
			
			get_header();
			if( file_exists(PIEREG_DIR_NAME . "/get_password.php") )
				include_once("get_password.php");
			/*$get_form = piereg_get_passwird();
			echo $get_form;*/
			get_footer();
			exit;	
		}
		
		function pie_add_option() {
		
			$option = 'per_page';		
			$args = array(
				'label' => 'Number of items per page:',
				'default' => 10,
				'option' => 'pie_users_per_page'
			);
		
			add_screen_option( $option, $args );
		
		}
		function pie_set_option($status, $option, $value) { 
			if ( 'pie_users_per_page' == $option ) return $value;		 
			return $status;
		 
		}
		function Unverified(){
			
			if( file_exists(PIEREG_DIR_NAME."/classes/unverified_pagination.php") )
				include_once( PIEREG_DIR_NAME."/classes/unverified_pagination.php");

			if( isset($this->pie_post_array['notice']) && !empty($this->pie_post_array['notice']) )
				echo '<div id="message" class="updated fade"><p><strong>' . esc_html($this->pie_post_array['notice']) . '.</strong></p></div>';
			if( isset($this->pie_post_array['error_message']) && !empty($this->pie_post_array['error_message']) )
				echo '<div id="error" class="error fade"><p><strong>' . esc_html($this->pie_post_array['error_message']) . '.</strong></p></div>';

			$unverified_list	= new Pie_Unverfied_users_Table();
			echo '<div class="wrap"><h2>'.__('Unverified Users', 'pie-register').'</h2>'; 
			
			if( isset($_POST['s']) ){
				$unverified_list->prepare_items(sanitize_text_field($_POST['s']));
			} else {
				$unverified_list->prepare_items(); 
			}
			
			?>
			<form method="post" id="pie-unverified-users">
				<input type="hidden" name="page" value="unverified-users">
				<?php
				$unverified_list->search_box( 'search', 'search_id' );				
				$unverified_list->display(); 
			echo '</form></div>';
		}
		
		function verifyUsers()
		{
			$valid = isset($_POST['vusers']) ? array_map( 'sanitize_key', $_POST['vusers'] ) : "";
			if($valid)
			{
				$option = get_option(OPTION_PIE_REGISTER);
				foreach( $valid as $user_id )
				{
					if ( $user_id ) 
					{
						$register_type = get_user_meta( $user_id , "register_type" , true);
						if($register_type == "admin_email_verify")
						{
							$user 			= new WP_User($user_id);
							$user_email 	= $user->user_email;
							
							update_user_meta( $user_id, 'active', 0);
							$hash = md5( time() );
							update_user_meta( $user_id, 'hash', $hash );
							update_user_meta( $user_id, 'admin_hash',"");
							update_user_meta( $user_id, 'register_type', "email_verify");
							
							$subject 		= html_entity_decode($option['user_subject_email_email_verification'],ENT_COMPAT,"UTF-8");
							$subject 		= $this->filterSubject($user_email,$subject);
							$message_temp = "";
							if($option['user_formate_email_email_verification'] == "0"){
								$message_temp	= nl2br(strip_tags($option['user_message_email_email_verification']));
							}else{
								$message_temp	= $option['user_message_email_email_verification'];
							}
							$message		= $this->filterEmail($message_temp,$user_email );
							$from_name		= $option['user_from_name_email_verification'];
							$from_email		= $option['user_from_email_email_verification'];					
							$reply_email 	= $option['user_to_email_email_verification'];
							
							//Headers
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							
							if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
							$headers .= "From: ".$from_name." <".$from_email."> \r\n";	
							if($reply_email){
								$headers .= "Reply-To: {$reply_email}\r\n";
								$headers .= "Return-Path: {$reply_email}\r\n";
							}else{
								$headers .= "Reply-To: {$from_email}\r\n";
								$headers .= "Return-Path: {$from_email}\r\n";
							}
							if((isset($option['user_enable_email_verification']) && $option['user_enable_email_verification'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
								$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
							}
						}else{
							update_user_meta( $user_id, 'active',1);
							update_user_meta( $user_id, 'admin_hash',"");
							
							//Sending E-Mail to newly active user
							$user 			= new WP_User($user_id);
							$subject 		= html_entity_decode($option['user_subject_email_email_thankyou'],ENT_COMPAT,"UTF-8");
							$subject 		= $this->filterSubject($user,$subject);
							$user_email 	= $user->user_email;
							$message_temp = "";
							if($option['user_formate_email_email_thankyou'] == "0"){
								$message_temp	= nl2br(strip_tags($option['user_message_email_email_thankyou']));
							}else{
								$message_temp	= $option['user_message_email_email_thankyou'];
							}
							
							$message		= $this->filterEmail($message_temp,$user_email);
							$from_name		= $option['user_from_name_email_thankyou'];
							$from_email		= $option['user_from_email_email_thankyou'];
							$reply_email	= $option['user_to_email_email_thankyou'];
							
							//Headers
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
							$headers .= "From: ".$from_name." <".$from_email."> \r\n";
							
							if($reply_email){
								$headers .= "Reply-To: {$reply_email}\r\n";
								$headers .= "Return-Path: {$reply_email}\r\n";
							}else{
								$headers .= "Reply-To: {$from_email}\r\n";
								$headers .= "Return-Path: {$from_email}\r\n";
							}
							if((isset($option['user_enable_email_thankyou']) && $option['user_enable_email_thankyou'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
								$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
							}
							// mailchimp related code within PR
							do_action('pireg_after_verification_users', $user);
						}
					}
				}
				
				$this->pie_post_array['notice'] = __("User(s) activated");
			}
			else
				$this->pie_post_array['notice'] = "<strong>".__('error','pie-register').":</strong>".__("Please select user(s) to send email.", "pie-register");
		}
		function PaymentLink()
		{
			global $wpdb;			
			$valid = isset($_POST['vusers']) ? array_map( 'sanitize_key', $_POST['vusers'] ) : "";
			add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
			if( is_array($valid)) 
			{
				
				$option = get_option(OPTION_PIE_REGISTER);
				$sent = 0;
				foreach( $valid as $user_id )
				{		
					$reg_type = get_user_meta($user_id, 'register_type');
					if($reg_type[0] != "payment_verify")
					{
						continue;	
					}
					$sent++;
					update_user_meta( $user_id, 'active', 0);
					$hash = md5( time() );
					update_user_meta( $user_id, 'hash', $hash );
					
		
					$user 			= new WP_User($user_id);
					$subject 		= html_entity_decode($option['user_subject_email_pending_payment_reminder'],ENT_COMPAT,"UTF-8");
					$subject 		= $this->filterSubject($user->user_email,$subject);
					$message_temp = "";
					if($option['user_formate_email_pending_payment_reminder'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_pending_payment_reminder']));
					}else{
						$message_temp	= $option['user_message_email_pending_payment_reminder'];
					}
					
					$message		= $this->filterEmail($message_temp,$user->user_email );
					$from_name		= $option['user_from_name_pending_payment_reminder'];
					$from_email		= $option['user_from_email_pending_payment_reminder'];
					$reply_email	= $option['user_to_email_pending_payment_reminder'];
									
							
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
					if((isset($option['user_enable_pending_payment_reminder']) && $option['user_enable_pending_payment_reminder'] == 1) && !wp_mail($user->user_email, $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
				}
				if($sent > 0)
					$this->pie_post_array['notice'] = __("Payment link sent via email", "pie-register");
				else
					$this->pie_post_array['notice'] = __("Invalid User Types", "pie-register");
					
			}
			else
			{
				$this->pie_post_array['notice'] = "<strong>".__('error','pie-register').":</strong>".__("Please select user(s) to send email.", "pie-register");
			}
		}
		function AdminEmailValidate()
		{
			global $wpdb;			
			$valid = isset($_POST['vusers']) ? array_map( 'sanitize_key', $_POST['vusers'] ) : "";
			add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
			if( is_array($valid) ) 
			{			
				$option = get_option(OPTION_PIE_REGISTER);
				$sent = 0;
				foreach( $valid as $user_id )
				{						
					$reg_type = get_user_meta($user_id, 'register_type');
					if($reg_type[0] != "email_verify")
					{
						continue;
					}
					$sent ++;
					update_user_meta( $user_id, 'active', 0);
					$hash = md5( time() );
					update_user_meta( $user_id, 'hash', $hash );					
		
					$user 			= new WP_User($user_id);					
					$subject 		= html_entity_decode($option['user_subject_email_email_verification_reminder'],ENT_COMPAT,"UTF-8");
					$subject 		= $this->filterSubject($user,$subject);
					$message_temp = "";
					if($option['user_formate_email_email_verification_reminder'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_email_verification_reminder']));
					}else{
						$message_temp	= $option['user_message_email_email_verification_reminder'];
					}
					
					$message		= $this->filterEmail($message_temp,$user->user_email );
					
					$from_name		= $option['user_from_name_email_verification_reminder'];
					$from_email		= $option['user_from_email_email_verification_reminder'];	
					$reply_email	= $option['user_to_email_email_verification_reminder'];					
					
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
								
					if((isset($option['user_enable_email_verification_reminder']) && $option['user_enable_email_verification_reminder'] == 1) && !wp_mail($user->user_email, $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
				}
				if($sent > 0)
					$this->pie_post_array['notice'] = __("Verification Emails have been re-sent", "pie-register");
				else
					$this->pie_post_array['notice'] = __("Invalid User Types", "pie-register");
			}
			else
				$this->pie_post_array['notice'] = "<strong>".__('error','pie-register').":</strong>".__("Please select user(s) to send email.", "pie-register");
		}
		function AdminDeleteUnvalidated()
		{
			global $wpdb;
				
			$piereg = get_option(OPTION_PIE_REGISTER);
			$valid = isset($_POST['vusers']) ? array_map( 'sanitize_key', $_POST['vusers'] ) : "";
			if($valid)
			{	
				include_once( $this->admin_path . 'includes/user.php' );
				foreach( $valid as $user_id )
				{
					if ( $user_id ) 
					{
						wp_delete_user($user_id);
					}
				}
				$this->pie_post_array['notice'] = __("User(s) deleted");
			}
		}
		function cleantext($text)
		{
			foreach($text as $key => $value){

				if( is_array( $value ) ) {
					$this->cleantext($value);				
					continue;
				}
				
				$text[$key] = str_replace(chr(13), " ", $value); //remove carriage returns
				$text[$key] = str_replace(chr(10), " ", $value);
			}
			
			return $text;
		}
		function disable_magic_quotes_gpc(&$value)
		{	
			$value = stripslashes($value);
			return $value;
		}
		
		function PieRegSettings()
		{
			if( isset($_GET['subtab']) && $_GET['subtab'] == 'role-based' )
				$this->PieRegRedirectSettingsAction();
			
			$option = get_option(OPTION_PIE_REGISTER);
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegSettings.php');
		}
		function PieRegPaymentGateway()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegPaymentGateway.php');			
		}
		function PieRegAttachment()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegAttachment.php');			
		}
		function PieRegNotifications()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegNotifications.php');
		}
		function PieRegImportExport()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegImportExport.php');
		}		
		function PieRegHelp()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegHelp.php');
		}
		function PieRegProFeatures()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegProFeatures.php');
		}
		function PieAboutUs()
		{
			do_action( 'piereg_admin_pages_before_content' ); 
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieAboutUs.php');
		}
		function PieRegRestrictUsers()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegRestrictUsers.php');			
		}

		/**
		 * PieRegCustomRoles function
		 * Description: Create custom user roles.
		 * Since v3.5.4
		 */
		function PieRegCustomRoles()
		{
			if( isset($_POST['piereg_user_role_nonce']) || isset($_POST['piereg_user_role_delete_nonce']) )
			{
				$this->pie_post_array	= $this->piereg_sanitize_post_data( 'piereg_user_role',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );

				if(isset($this->pie_post_array['piereg_user_role_delete_nonce']) && wp_verify_nonce( $this->pie_post_array['piereg_user_role_delete_nonce'], 'piereg_wp_user_role_delete_nonce' ))
				{
					global $wpdb;
					$custom_role_table_name = $wpdb->prefix."pieregister_custom_user_roles";

					if(isset($this->pie_post_array['role_del_id'])){
						$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
						if(isset($this->pie_post_array['piereg_user_role_delete_nonce']) && wp_verify_nonce( $this->pie_post_array['piereg_user_role_delete_nonce'], 'piereg_wp_user_role_delete_nonce' ))
						{
							if($wpdb->query( $wpdb->prepare("DELETE FROM ".$custom_role_table_name." WHERE id = %d", $this->pie_post_array['role_del_id']) )){
								$this->pie_post_array['notice'] = __("The Role has been deleted","pie-register");
								remove_role( $this->pie_post_array['role_del_key']);
							}	
							else{
								$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
							}
						}else{
							$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
						}
						
					}
				}else{
					$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
				}
			}

			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegCustomRoles.php');			
		}
		function PieRegExportUsers()
		{
			$this->pieregister_fu_admin_scripts_styles();
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegExportUsers.php');
		}
		function PieRegBulkEmail()
		{
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegBulkEmail.php');
		}	
		function PieRegNewForm()
		{
			wp_enqueue_style('pie_notification_css');
			wp_enqueue_script('pie_notification_js');
			do_action( 'piereg_admin_pages_before_content' ); 
			$this->pieregister_admin_scripts_styles_slick();
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegRegistrationForm.php');
		}
		//Opening Form Editor
		function RegPlusEditForm(){
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegEditForm.php');
		}
		
		function PieRegSettingsProcess() {
			$update = get_option(OPTION_PIE_REGISTER);
			$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
			
			if(isset($_POST['piereg_settings_allusers']) && wp_verify_nonce( $_POST['piereg_settings_allusers'], 'piereg_wp_settings_allusers' )) {
				
				$this->pie_post_array	= $this->piereg_sanitize_post_data('piereg_settings_allusers',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				$pie_pages 				= get_option("pie_pages");
				
				$update['alternate_login'] 			= $pie_pages[0] = intval($this->pie_post_array['alternate_login']);
				$update['alternate_register'] 		= $pie_pages[1] = intval($this->pie_post_array['alternate_register']);
				$update['alternate_forgotpass'] 	= $pie_pages[2] = intval($this->pie_post_array['alternate_forgotpass']);
				$update['alternate_profilepage']	= $pie_pages[3] = intval($this->pie_post_array['alternate_profilepage']);
				$update['after_login']				= trim($this->pie_post_array['after_login']);
				$update['alternate_login_url']		= $this->pie_post_array['alternate_login_url'];
				$update['alternate_logout']			= trim($this->pie_post_array['alternate_logout']);
				$update['alternate_logout_url']		= $this->pie_post_array['alternate_logout_url'];
				update_option('pie_pages', $pie_pages);
				
			} elseif(isset($_POST['piereg_settings_ux']) && wp_verify_nonce( $_POST['piereg_settings_ux'], 'piereg_wp_settings_ux' )) {
				
				$this->pie_post_array	= $this->piereg_sanitize_post_data('piereg_settings_ux',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
					$update['display_hints'] 				= isset($this->pie_post_array['display_hints']) ? intval($this->pie_post_array['display_hints']) :0;
					$update['login_username_label']			= $this->pie_post_array['login_username_label'];
					$update['login_username_placeholder']	= $this->pie_post_array['login_username_placeholder'];
					$update['login_password_label'] 		= $this->pie_post_array['login_password_label'];
					$update['login_password_placeholder'] 	= $this->pie_post_array['login_password_placeholder'];
					
					$update['forgot_pass_username_label']	= $this->pie_post_array['forgot_pass_username_label'];
					$update['forgot_pass_username_placeholder'] = $this->pie_post_array['forgot_pass_username_placeholder'];
					
					$update['custom_logo_url']			= $this->pie_post_array['custom_logo_url'];
					$update['custom_logo_tooltip']		= $this->pie_post_array['custom_logo_tooltip'];
					$update['custom_logo_link']			= $this->pie_post_array['custom_logo_link'];
					$update['show_custom_logo']			= (isset($this->pie_post_array['show_custom_logo']))?$this->pie_post_array['show_custom_logo']:0;
					$update['outputcss'] 				= isset($this->pie_post_array['outputcss']) ? intval($this->pie_post_array['outputcss']) :0;
					$update['outputjquery_ui']			= isset($this->pie_post_array['outputjquery_ui']) ? intval($this->pie_post_array['outputjquery_ui']) :0;
					
					
					$custom_css					= isset( $this->pie_post_array['custom_css'] ) ? $this->pie_post_array['custom_css'] : "";
					$custom_css					= mb_convert_encoding(strip_tags($custom_css),'HTML-ENTITIES','utf-8');
					$update['custom_css']		= $this->disable_magic_quotes_gpc($custom_css);
					
					$tracking_code				= isset( $this->pie_post_array['tracking_code'] ) ? $this->pie_post_array['tracking_code'] : "";
					$tracking_code				= mb_convert_encoding(strip_tags($tracking_code),'HTML-ENTITIES','utf-8');
					$update['tracking_code']	= $this->disable_magic_quotes_gpc($tracking_code);
				
			} elseif(isset($_POST['piereg_settings_overrides']) && wp_verify_nonce( $_POST['piereg_settings_overrides'], 'piereg_wp_settings_overrides' )) {
				
				$this->pie_post_array	= $this->piereg_sanitize_post_data('piereg_settings_overrides',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
				$update['redirect_user'] 			= isset($this->pie_post_array['redirect_user']) ? intval($this->pie_post_array['redirect_user']) :0;
				$update['show_admin_bar']			= isset($this->pie_post_array['show_admin_bar']) ? intval($this->pie_post_array['show_admin_bar']) :0;
				$update['block_WP_profile']			= isset($this->pie_post_array['block_WP_profile']) ? intval($this->pie_post_array['block_WP_profile']) :0;
				$update['block_wp_login'] 			= isset($this->pie_post_array['block_wp_login']) ? intval($this->pie_post_array['block_wp_login']) :0;
				$update['allow_pr_edit_wplogin']	= isset($this->pie_post_array['allow_pr_edit_wplogin']) ? intval($this->pie_post_array['allow_pr_edit_wplogin']) :0;
				
			} elseif(isset($_POST['piereg_settings_security_b']) && wp_verify_nonce( $_POST['piereg_settings_security_b'], 'piereg_wp_settings_security_b' )) {
				
				$this->pie_post_array	= $this->piereg_sanitize_post_data('piereg_settings_security_b',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
				$update['captcha_in_login_value'] = isset($this->pie_post_array['captcha_in_login_value']) ? $this->pie_post_array['captcha_in_login_value']: 0; 
				$update['captcha_in_login_attempts'] = isset($this->pie_post_array['captcha_in_login_attempts']) ? $this->pie_post_array['captcha_in_login_attempts']: 0; 
				$update['capthca_in_login_label'] = isset($this->pie_post_array['capthca_in_login_label']) ? $this->pie_post_array['capthca_in_login_label']: 0; 
				$update['piereg_recapthca_skin_login'] = isset($this->pie_post_array['piereg_recapthca_skin_login']) ? $this->pie_post_array['piereg_recapthca_skin_login'] : 0; 
				$update['capthca_in_login'] = isset($this->pie_post_array['capthca_in_login']) ? intval($this->pie_post_array['capthca_in_login']) : 0;
				
				$update['captcha_in_forgot_value'] = $this->pie_post_array['captcha_in_forgot_value'];
				$update['capthca_in_forgot_pass_label'] = $this->pie_post_array['capthca_in_forgot_pass_label'];
				$update['piereg_recapthca_skin_forgot_pass'] = isset($this->pie_post_array['piereg_recapthca_skin_forgot_pass']) ? $this->pie_post_array['piereg_recapthca_skin_forgot_pass'] : 0;
				$update['capthca_in_forgot_pass'] = isset($this->pie_post_array['capthca_in_forgot_pass']) ? intval($this->pie_post_array['capthca_in_forgot_pass']) : 0;
				
				$update['piereg_recaptcha_type']     = isset($this->pie_post_array['piereg_recaptcha_type']) ? $this->pie_post_array['piereg_recaptcha_type'] : "v2";
				$update['captcha_publc'] = $this->pie_post_array['captcha_publc'];
				$update['captcha_private'] = $this->pie_post_array['captcha_private'];
				$update['piereg_recaptcha_language'] = $this->pie_post_array['piereg_recaptcha_language'];	
				$update['captcha_publc_v3']          = isset($this->pie_post_array['captcha_publc_v3']) ? $this->pie_post_array['captcha_publc_v3'] : '';
				$update['captcha_private_v3']        = isset($this->pie_post_array['captcha_private_v3']) ? $this->pie_post_array['captcha_private_v3'] : '';
					
				$update['verification'] = intval($this->pie_post_array['verification']);
				$update['email_edit_verification_step'] = intval($this->pie_post_array['email_edit_verification_step']);
				$update['grace_period'] = intval($this->pie_post_array['grace_period']);
					
				$update['restrict_bot_enabel'] = isset($this->pie_post_array['restrict_bot_enabel']) ? intval($this->pie_post_array['restrict_bot_enabel']) : "";				
				$restrict_bot_content = isset($this->pie_post_array['restrict_bot_content']) ? mb_convert_encoding($this->pie_post_array['restrict_bot_content'],'HTML-ENTITIES','utf-8') : "";
				$update['restrict_bot_content'] = $this->disable_magic_quotes_gpc($restrict_bot_content);
				
				$restrict_bot_content_message = isset($this->pie_post_array['restrict_bot_content_message']) ? mb_convert_encoding($this->pie_post_array['restrict_bot_content_message'],'HTML-ENTITIES','utf-8'): "";
				$update['restrict_bot_content_message'] = $this->disable_magic_quotes_gpc($restrict_bot_content_message);
				
			} elseif(isset($_POST['piereg_settings_security_advanced']) && wp_verify_nonce( $_POST['piereg_settings_security_advanced'], 'piereg_wp_settings_security_advanced' )){ 
				
				$this->pie_post_array	= $this->piereg_sanitize_post_data('piereg_settings_security_advanced',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
				$update['reg_form_submission_time_enable'] = isset($this->pie_post_array['reg_form_submission_time_enable']) ? intval($this->pie_post_array['reg_form_submission_time_enable']):0;
				$update['reg_form_submission_time'] = isset($this->pie_post_array['reg_form_submission_time']) ? intval($this->pie_post_array['reg_form_submission_time']):0;
			
			} else {
				$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");				
			}
			
			update_option(OPTION_PIE_REGISTER, $update );
			PieReg_Base::set_pr_global_options($update, OPTION_PIE_REGISTER );
			
			if(!isset($this->pie_post_array['error']) && empty($this->pie_post_array['error']))
				$this->pie_post_array['notice'] = apply_filters("piereg_settings_saved",__('Settings Saved', 'pie-register'));

		}
		
		function PieRegRedirectSettingsAction(){
			
			$this->pie_post_array	= $this->piereg_sanitize_post_data( 'piereg_redirect_settings',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
			
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$piereg_table_name =$prefix."redirect_settings";
			/*	Change Status	*/
			if( isset($this->pie_post_array['redirect_settings_status_id']) && !empty($this->pie_post_array['redirect_settings_status_id']) ){
				if($wpdb->query($wpdb->prepare("update `".$piereg_table_name."` SET `status` = CASE WHEN status = 1 THEN  0 WHEN status = 0 THEN 1 ELSE  0 END  WHERE `id` = %d",intval($this->pie_post_array['redirect_settings_status_id']))))
					$this->pie_post_array['notice'] = __("Status has been changed.","pie-register");
				else
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			/*	Delete Record	*/
			elseif(isset($this->pie_post_array['redirect_settings_del_id'])){
				if($wpdb->query($wpdb->prepare("DELETE FROM `".$piereg_table_name."` WHERE `id` = %d", intval($this->pie_post_array['redirect_settings_del_id']))))
					$this->pie_post_array['notice'] = __("Record has been deleted","pie-register");
				else
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
		}
		function PieRegInvitationCodes()
		{
			global $wpdb;
			$piereg 	= get_option(OPTION_PIE_REGISTER);
			$codetable	= $this->codeTable();
			$this->pie_post_array	= $this->piereg_sanitize_post_data( 'piereg_invitation_code',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
			$_data_updated	= false;
					
			if( isset($this->pie_post_array['invi_del_id']) ) 
			{
				
				$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
				if(isset($this->pie_post_array['piereg_invitation_code_del_form_nonce']) && wp_verify_nonce( $this->pie_post_array['piereg_invitation_code_del_form_nonce'], 'piereg_wp_invitation_code_del_form_nonce' ))
				{
					if($wpdb->query( $wpdb->prepare("DELETE FROM ".$codetable." WHERE id = %d", $this->pie_post_array['invi_del_id']) ))	
						$this->pie_post_array['notice'] = __("Invitation code deleted.","pie-register");
					else
						$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}else{
					$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
				}
			}
			
			else if( isset($this->pie_post_array['status_id']) ) 
			{
				$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
				if(isset($this->pie_post_array['piereg_invitation_code_status_form_nonce']) && wp_verify_nonce( $this->pie_post_array['piereg_invitation_code_status_form_nonce'], 'piereg_wp_invitation_code_status_form_nonce' ))
				{
					if($wpdb->query( $wpdb->prepare("update ".$codetable." SET status = CASE WHEN status = 1 THEN  0 WHEN status = 0 THEN 1 ELSE  0 END  WHERE id = %s", $this->pie_post_array['status_id']) )){
						$this->pie_post_array['notice'] = __("Status has been changed","pie-register");
					}else{
						$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
				}else{
					$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
				}
			}
			if( isset($_POST['piereg_invitation_code_enable_nonce']) && wp_verify_nonce( $_POST['piereg_invitation_code_enable_nonce'], 'piereg_wp_invitation_code_enable_nonce' ) )
			{
				if( isset($this->pie_post_array['hdn_enable_invitation_codes']) )
				{
					$piereg['enable_invitation_codes'] = isset($this->pie_post_array['enable_invitation_codes']) ? intval($this->pie_post_array['enable_invitation_codes']) : 0;
					$_data_updated	= true;
				}	
			}
			if( $_data_updated && update_option(OPTION_PIE_REGISTER,$piereg)){
				$this->pie_post_array['notice'] = __("Settings Saved","pie-register");
			}
			else if( isset($this->pie_post_array['piereg_codepass']) )
			{
				
				$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
				if(isset($this->pie_post_array['piereg_invitation_code_nonce']) && wp_verify_nonce( $this->pie_post_array['piereg_invitation_code_nonce'], 'piereg_wp_invitation_code_nonce' ))
				{
					if(isset($this->pie_post_array['add_code'])){
						if(isset($this->pie_post_array['invitation_code_prefix'])){
							$this->pie_post_array['error'] = __("Please activate the premium version","pie-register");
						}
						
						if(empty($this->pie_post_array['piereg_codepass']) || trim($this->pie_post_array['piereg_codepass']) == ''){
							$this->pie_post_array['error'] = __("Invitation code field is empty.","pie-register");
						}
						
						if(isset($this->pie_post_array['invitation_code_usage']) && !is_numeric($this->pie_post_array['invitation_code_usage']) && trim($this->pie_post_array['invitation_code_usage']) != ''){
							if(empty($this->pie_post_array['piereg_codepass']) || trim($this->pie_post_array['piereg_codepass']) == ''){
								$this->pie_post_array['error'] .= '<br/>';
							}
							$this->pie_post_array['error'] .= __("Usage must be a number greater than zero.","pie-register");
						}elseif(isset($this->pie_post_array['invitation_code_usage']) && $this->pie_post_array['invitation_code_usage'] < 0 && trim($this->pie_post_array['invitation_code_usage']) != ''){
							if(empty($this->pie_post_array['piereg_codepass']) || trim($this->pie_post_array['piereg_codepass']) == ''){
								$this->pie_post_array['error'] .= '<br/>';
							}
							$this->pie_post_array['error'] .= __("Usage must be a number greater than zero.","pie-register");
						}
										
						$update["codepass"] = isset($this->pie_post_array['piereg_codepass']) ? $this->pie_post_array['piereg_codepass'] : '';
						$codespasses = array();

						if($update['codepass'] != ""){
							$codespasses=explode("\n",$update["codepass"]);
						} 
				
						$codeadded = false;
						
						$count_code = 0;
						$count_added_code = 0;
						$count_special_char = 0;
						
						if( is_array($codespasses ) && count($codespasses) > 0 ) 
						{
							foreach( $codespasses as $k=>$v )
							{
								$v = trim($v);
								if($v != '')
								{
									$count_code++;
									
									if( $this->InsertCode($v) )
									{
										$count_added_code++;
										$codeadded = true;
									}
								
									if(!preg_match('/^[A-Za-z0-9_-]+$/', $v))
									{
										$count_special_char++;
										$special_char = true;
									}
								}						
							}
						}							
						
						if(isset($special_char) && $special_char){
							$this->pie_post_array['error'] = __("Special characters are not allowed in code field.","pie-register");
						}
						
						if(!$codeadded && $count_code != $count_added_code && !isset($this->pie_post_array['error'])){
							$count_not_added_code = $count_code - $count_added_code - $count_special_char;
							$this->pie_post_array['notice'] = $count_not_added_code.__(" invitation Code(s) already exists","pie-register");
						}elseif($codeadded) {
							if(isset($this->pie_post_array['invitation_code_usage']) && is_numeric($this->pie_post_array['invitation_code_usage']) && $this->pie_post_array['invitation_code_usage'] >= 0)
							{
								$piereg["invitation_code_usage"] = $this->pie_post_array['invitation_code_usage'];
								
								if(update_option(OPTION_PIE_REGISTER,$piereg))
								{
									$this->pie_post_array['notice'] = __("Invitation Code(s) added successfully","pie-register");
								}
							}
							
							if($count_code != $count_added_code){
								$count_not_added_code = $count_code - $count_added_code - $count_special_char;
								$this->pie_post_array['notice'] = $count_added_code.__(" invitation Code(s) added successfully.","pie-register");
								if($count_not_added_code != 0){
									$this->pie_post_array['notice'] .= '<br/>'.$count_not_added_code.__(" invitation Code(s) already exists","pie-register");
								}
							}else{						
								$this->pie_post_array['notice'] = $count_added_code.__(" invitation Code(s) added successfully","pie-register");
							}
						}
					}
				}else{
					
					$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
				}
			}		
			$this->include_pr_menu_pages(PIEREG_DIR_NAME.'/menus/PieRegInvitationCodes.php');	
		}
		function InsertCode($name)
		{
				if(empty($name) || trim($name) == '') return false;
				
				$name_lower = strtolower($name);
				$name_upper = strtoupper($name);
				
				global $wpdb;
				
				$piereg=get_option(OPTION_PIE_REGISTER);
				
				$codetable=$this->codeTable();
				$expiry = (isset($piereg['codeexpiry'])) ? $piereg['codeexpiry']: "";
				$codes = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $codetable WHERE BINARY `name`=%s OR `name`=%s" , $name_lower, $name_upper) );

				if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
				$counts = count($codes);
				$wpdb->flush();
				
				if( $counts > 0 )
				{
					return false;
				}
				
				if(!preg_match('/^[A-Za-z0-9_-]+$/', $name)) return false;
				
				$name = trim(preg_replace("/[^A-Za-z0-9_-]/", '', $name));
				if(empty($name)) return false;
				
				$date = date_i18n("Y-m-d");
				
				$usage = intval($_POST['invitation_code_usage']);
				
				$expiry_date = "0000-00-00";

				if(!empty($usage) && !is_numeric($usage) || $usage < 0){
					return false;
				}
								
				if(!$wpdb->query( $wpdb->prepare("INSERT INTO ".$codetable." (`created`,`modified`,`name`,`count`,`status`,`code_usage`,`expiry_date`)VALUES(%s,%s,%s,%s,%s,%s,%s)", $date, $date, $name, $counts, "1", $usage, $expiry_date) )){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
				$wpdb->flush();
				return true;
				
			}
		function generateCSV()
		{	
		
			if(isset($_POST['piereg_export_users_nonce']) && wp_verify_nonce( $_POST['piereg_export_users_nonce'], 'piereg_wp_exportusers_nonce' ))
			{
				$this->pie_post_array	= $this->piereg_sanitize_post_data( 'piereg_export_csv',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
				global $wpdb;
				$user_table 		= $wpdb->prefix . "users";
				$user_meta_table 	= $wpdb->prefix . "usermeta";
				
				
				$fields 	= "";
				if( isset($this->pie_post_array['pie_fields_csv']) && sizeof($this->pie_post_array['pie_fields_csv']) > 0) {
					$fields		= implode(',',array_keys($this->pie_post_array['pie_fields_csv']));					
				}			
								
				if(	!isset($this->pie_post_array['pie_fields_csv']) || sizeof($this->pie_post_array['pie_fields_csv']) == 0	) {
					$this->pie_post_array['pie_fields_csv'] = array();
				}
				
				if(	!isset($this->pie_post_array['pie_meta_csv']) || sizeof($this->pie_post_array['pie_meta_csv']) == 0	) {
					$this->pie_post_array['pie_meta_csv'] 	= array();
				}
					
				
				$heads	= array_merge(array("id"=>"User ID"),$this->pie_post_array['pie_fields_csv'],$this->pie_post_array['pie_meta_csv']);
				
				$query 	= "SELECT ID ";
				$query 	.= ($fields)?",$fields " : "";
				$query 	.= " FROM $user_table ";
				
				if($this->pie_post_array['date_start'] != "" || $this->pie_post_array['date_end'] != "")
				{
					$_date_start 	= date_i18n("Y-m-d",strtotime($this->pie_post_array['date_start']));
					$_date_end 		= date_i18n("Y-m-d",strtotime($this->pie_post_array['date_end']));
					$date_start 	= FALSE;
					$query 			.= " where ";
					
					if($this->pie_post_array['date_start'] != "")
					{
						$query .= " user_registered >= '{$_date_start} 00:00:00' ";
						$date_start = TRUE;			
					}
					
					if($this->pie_post_array['date_end'] != "")
					{
						if($date_start)
						{
							$query .= " AND ";	
						}
						$query .= " user_registered <= '{$_date_end} 23:59:59' ";			
					}		
				}		
				$query .= " order by user_login asc";
				
				/*$sql_pre 	= $wpdb->prepare( $query, $user_table );
				$users 		= $wpdb->get_results( $sql_pre, ARRAY_A ); #WPS*/
				// User data being fetched from the database
				$users = $wpdb->get_results($query,ARRAY_A);				
				if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
				global  $wp_roles,$wpdb;
				if(sizeof($users ) > 0){
					// Creating the CSV of the exported user's data
					$dfile = "pieregister_exported_users_".date_i18n("Y-m-d-H:i").".csv";
					header('Content-Type: application/csv');
					header('Content-Disposition: attachment; filename='.$dfile);
					header('Pragma: no-cache');
					// Set headers in the exported CSV
					echo '"'.implode('","',$heads).'"'."\r\n";
					
					foreach ($users as $user_key=>$user_value){
						$content_data = '';
						foreach($user_value as $single_user_data){
							$content_data.='"'.$single_user_data.'",';
						}
						if(sizeof($this->pie_post_array['pie_meta_csv']) > 0){
							foreach($this->pie_post_array['pie_meta_csv'] as $key=>$value){
								
								if($key == "wp_capabilities"){
									$user = get_userdata( $user_value['ID'] );
									
									 $capabilities = $user->{$wpdb->prefix . 'capabilities'};
			
									if ( !isset( $wp_roles ) )
										$wp_roles = new WP_Roles();
									$meta_value = '';
									foreach ( $wp_roles->role_names as $role => $name ):
										if ( array_key_exists( $role, $capabilities ) )
											$meta_value = $role;
									endforeach;
								}
								else{
									$meta_value = get_user_meta($user_value['ID'],$key,true);
								}
								
								$content_data.='"'.htmlentities($meta_value, ENT_QUOTES | ENT_IGNORE, "UTF-8").'"'.","; 
							}
						}
						// Set content in the exported CSV
						echo rtrim($content_data,',');
						echo "\r\n"; 
					}
					die(); // ask baqar
				}
				else
				{
					$this->pie_post_array['error_message'] = __("No Record Found","pie-register");
				}
			}else{
				$this->pie_post_array['error_message'] = __("Invalid nonce.","pie-register");
			}
		}
		
		function csv_to_array($filename='', $delimiter=';'){
			if(!file_exists($filename) || !is_readable($filename))
				return FALSE;
		
			$header = array();
			$data 	= array();
			
			if (($handle = fopen($filename, 'r')) !== FALSE)
			{
				while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
				{
					if(!$header)
						$header = $row;
					else
						$data[] = array_combine($header, $row);
				}
				fclose($handle);
			}
			return $data;
		}
		function importUsers()
		{
			if(isset($_POST['piereg_import_users_nonce']) && wp_verify_nonce( $_POST['piereg_import_users_nonce'], 'piereg_wp_importusers_nonce' ))
			{
				$this->pie_post_array	= $this->piereg_sanitize_post_data( 'piereg_import_users',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
				
				$success_import = 0;
				$unsuccess_import = 0;
				$already_exist = 0;
				
				if(empty(sanitize_file_name($_FILES['csvfile']['name'])))
				{
					$this->pie_post_array['error_message'] = apply_filters("piereg_didnt_select_file_to_import",__("You did not select a file to import users",'pie-register'));
					return;	
				}
				$checked_user_file = realpath($_FILES['csvfile']['tmp_name']);
				if(!$checked_user_file){
					$this->pie_post_array['error_message'] = apply_filters("piereg_didnt_select_file_to_import",__("The file can't be processed",'pie-register'));
					return;
				}

				$ext = pathinfo(sanitize_file_name($_FILES['csvfile']['name']), PATHINFO_EXTENSION); // To get file extension
				if($ext != "csv")
				{
					$this->pie_post_array['error_message'] = __("Invalid CSV file.",'pie-register');	
					return;	
				}
				$current_csv_file_data = "";
				if ($_FILES['csvfile']['tmp_name']){
					$csv_data = $this->csv_to_array($checked_user_file,',');
				}
				if(!isset($csv_data[0]) or sizeof($csv_data[0]) < 2)
				{
					$this->pie_post_array['error_message'] = __("Invalid CSV File. It must contain all the default user fields.",'pie-register');
					return;	
				}
				$table_fields = array(
									  //////////// DEFAULT FEILDS //////////
									  "User ID"=>"ID",
									  "Username"=>"user_login",
									  "Password"=>'user_pass',
									  "Nickname"=>"user_nicename",
									  "E-mail"=>"user_email",
									  "Website"=>"user_url",
									  "User Registered"=>"user_registered",
									  "Display name"=>"display_name",
									  ///////////// USER META /////////////
									  "First Name"=>"first_name",
									  "Last Name"=>"last_name",
									  "Biographical Info"=>"description",
									  "Role"=>"wp_capabilities");
				$user_csv_data = array();
				$temp_data = array();
				$user_default_data = array();
				$user_meta_key = array();
				if(is_array($csv_data) && !empty($csv_data))
				{
					foreach($csv_data as $arr_key=>$arr_val){		
						foreach($arr_val as $head_key=>$user_data){							
							switch($head_key):
								case 'User ID' :
								case 'Username' :
								case 'Password' :
								case 'Nickname' :
								case 'E-mail' :
								case 'Website' :
								case 'User Registered' :
								case 'Display name' :								
									if( mb_detect_encoding($user_data) !== "UTF-8"):
										$user_default_data[$table_fields[$head_key]] 	= utf8_encode($user_data);
									else:
										$user_default_data[$table_fields[$head_key]] 	= $user_data;
									endif;
								break;
								case 'First Name' :
								case 'Last Name' :
								case 'Biographical Info' :
								case 'Role' :
									if( mb_detect_encoding($user_data) !== "UTF-8"):
										$user_meta_key[$table_fields[$head_key]] 		= utf8_encode($user_data);
									else:
										$user_meta_key[$table_fields[$head_key]] 		= $user_data;
									endif;
								break;
							endswitch;
							$temp_data[$table_fields[$head_key]] = $user_data;
						}
						$user_csv_data[$arr_key] = $temp_data;						
						
						if( empty($user_default_data['user_pass']) )
						{
							$user_default_data['user_pass'] = wp_generate_password();
						}
						
						if ( username_exists( $user_default_data['user_login'] ) ){
							$already_exist++;
							if(isset($this->pie_post_array['update_existing_users']) && $this->pie_post_array['update_existing_users'] == "yes"){
								unset($user_default_data['user_pass']);
								$user_id = wp_update_user($user_default_data);
								if(is_int($user_id)){
									$this->update_user_meta_by_array($user_id,$user_meta_key);
								}
							}
						}else{
							if(get_user_by('ID',$user_default_data['ID'])){
								$already_exist++;
								if(isset($this->pie_post_array['update_existing_users']) && $this->pie_post_array['update_existing_users'] == "yes"){
									unset($user_default_data['user_pass']);
									$user_id = wp_update_user($user_default_data);
									if(is_int($user_id)){
										$this->update_user_meta_by_array($user_id,$user_meta_key);
									}
								}
							}else{
								unset($user_default_data['ID']);
								$user_id = wp_insert_user($user_default_data);
								if( is_int($user_id) ) 
								{
									do_action('piereg_new_user_import',	$user_id);
									if(isset($user_id)){									
										$this->update_user_meta_by_array($user_id,$user_meta_key);
									}
									$success_import++;
								}								
							}
						}
						
						unset($temp_data);
						unset($user_meta_key);
						unset($user_default_data);
					}
				}
				
				if($success_import)
					$this->pie_post_array['success_message'] = __("$success_import user(s) imported.",'pie-register');				
				
				if($unsuccess_import)
					$this->pie_post_array['error_message'] = __("$unsuccess_import user(s) do not imported.",'pie-register');
					
				if($already_exist){
					if(isset($this->pie_post_array['update_existing_users']) && $this->pie_post_array['update_existing_users'] == "yes"){					
						if($success_import) {$this->pie_post_array['success_message'] .= "<br />".__("$already_exist user(s) Update.",'pie-register');}
						else {$this->pie_post_array['success_message'] = __("$already_exist user(s) Update.",'pie-register');}
					}else{
						$this->pie_post_array['error_message'] = __("$already_exist user(s) already exist.",'pie-register');
					}
				}
			}else{
				$this->pie_post_array['error_message'] = __("Invalid nonce.","pie-register");
			}
		}
		
		function update_user_meta_by_array($user_id,$user_meta_keys)
		{
			if(isset($user_id) and isset($user_meta_keys))
			{
				if(is_array($user_meta_keys)){
					foreach($user_meta_keys as $key=>$val){
						if($key == "wp_capabilities"){
							$wp_user_object = new WP_User($user_id);
							$wp_user_object->set_role($val);
							unset($wp_user_object);
						}else{
							update_user_meta($user_id,$key,$val);
						}
					}
				}
			}
		}
		
		function SaveSettings()
		{
			// asnitize_post here
			if(isset($_POST['is_deactivate_plugin_license'], $_POST['piereg_deactivate_plugin_license_nonce']) && $_POST['is_deactivate_plugin_license'] == "true" && wp_verify_nonce($_POST['piereg_deactivate_plugin_license_nonce'], 'piereg_wp_deactivate_plugin_license_nonce') )
			{
				$piereg_api_manager_menu = new Piereg_API_Manager_Example_MENU();
				
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once $this->admin_path . 'includes/plugin.php';
				}
				$activated_plugins 	= get_option('active_plugins');
				$list_all_plugins 	= get_plugins();
				
				foreach( $list_all_plugins as $key => $plugin )
				{
					if( in_array($key,$activated_plugins) && strpos($plugin['Name'],'Pie Register (Add on) - ') !== false )
					{
						$addon_name = str_replace(array('Pie Register (Add on) - ', '(', ')'), '', $plugin['Name']);
						$addon_name = str_replace(array(' ', '.'), '_', $addon_name);
						$is_addon	= "addon_" . $addon_name;
						
						if($is_addon == 'addon_Authorize_Net')
                        {
                            $is_addon = 'addon_AuthNet';
                        }
						
						$plugin_status 	= get_option( 'piereg_api_manager_'.$is_addon.'_activated' );
						
						if( $plugin_status == 'Activated' ) {
							$piereg_api_manager_menu->wc_am_license_key_deactivation( "on", array('is_addon'=>sanitize_text_field($is_addon),'is_addon_version'=>sanitize_text_field($plugin['Version'])) );
						}
					}
				}
				
				$piereg_api_manager_menu->wc_am_license_key_deactivation( "on" );
			}
			
			if(isset($_POST['is_deactivate_addon']) && $_POST['is_deactivate_addon'] == "true")
			{
				$piereg_pro_not_activated	= ( isset($_POST['piereg_pro_not_activated']) ) ? true: false; 
				
				$piereg_api_manager_menu = new Piereg_API_Manager_Example_MENU();
				$piereg_api_manager_menu->wc_am_license_key_deactivation( "on", array('is_addon'=>$_POST['is_deactivate_addon_license'],'is_addon_version'=>$_POST['addon_software_version'],'is_piereg_pro_inactive'=>$piereg_pro_not_activated) );
			}
			
			$update = get_option(OPTION_PIE_REGISTER);
			if( isset($_POST['action']) && $_POST['action'] == 'pie_reg_update' )
			{
				if(isset($_POST['payment_gateway_page'],$_POST['piereg_payment_gateway_page_nonce']) && wp_verify_nonce($_POST['piereg_payment_gateway_page_nonce'], 'piereg_wp_payment_gateway_page_nonce') )
				{
					$this->pie_post_array	= $this->piereg_sanitize_post_data( 'payment_gateway_page',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
					
					$update["enable_paypal"]	= (isset($this->pie_post_array['enable_paypal'])) ? intval($this->pie_post_array['enable_paypal']) : "";
					$update["paypal_butt_id"]	= $this->disable_magic_quotes_gpc($this->pie_post_array['piereg_paypal_butt_id']);
					$update["paypal_sandbox"]	= $this->pie_post_array['piereg_paypal_sandbox'];
				}
				else if(isset($_POST['payment_gateway_general_settings'],$_POST['piereg_payment_gateway_settings_nonce']) && wp_verify_nonce($_POST['piereg_payment_gateway_settings_nonce'], 'piereg_wp_payment_gateway_settings_nonce') ){
					
					$this->pie_post_array	= $this->piereg_sanitize_post_data( 'payment_gateway_general_settings',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
					
					$payment_success_msg			= trim(((isset($this->pie_post_array['payment_success_msg']) && !empty($this->pie_post_array['payment_success_msg']))?$this->pie_post_array['payment_success_msg']:__("Payment was successful.","pie-register")));
					$update["payment_success_msg"]	= $this->disable_magic_quotes_gpc($payment_success_msg);
					
					$payment_faild_msg				= trim(((isset($this->pie_post_array['payment_faild_msg']) && !empty($this->pie_post_array['payment_faild_msg']))?$this->pie_post_array['payment_faild_msg']:__("Payment failed.","pie-register")));
					$update["payment_faild_msg"]	= $this->disable_magic_quotes_gpc($payment_faild_msg);
					
					$payment_renew_msg				= trim(((isset($this->pie_post_array['payment_renew_msg']) && !empty($this->pie_post_array['payment_renew_msg']))?$this->pie_post_array['payment_renew_msg']:__("Account needs to be activated.","pie-register")));
					$update["payment_renew_msg"]	= $this->disable_magic_quotes_gpc($payment_renew_msg);
					
					
					$payment_already_activate_msg	= trim(((isset($this->pie_post_array['payment_already_activate_msg']) && !empty($this->pie_post_array['payment_already_activate_msg']))?$this->pie_post_array['payment_already_activate_msg']:__("Account is already active.","pie-register")));
					$update["payment_already_activate_msg"]	= $this->disable_magic_quotes_gpc($payment_already_activate_msg);
				}
				else if(isset($_POST['admin_email_notification_page'])){
					$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
					if(isset($_POST['piereg_admin_email_notification']) && wp_verify_nonce( $_POST['piereg_admin_email_notification'], 'piereg_wp_admin_email_notification' ))
					{
						$this->pie_post_array	= $this->piereg_sanitize_post_data( 'admin_email_notification_page',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
						
						$update['enable_admin_notifications']	= isset($this->pie_post_array['enable_admin_notifications']) ? intval($this->pie_post_array['enable_admin_notifications']) : 0;
						$update['admin_sendto_email']			= trim($this->pie_post_array['admin_sendto_email']);
						
						// $admin_from_name						= mb_convert_encoding($this->pie_post_array['admin_from_name'],'HTML-ENTITIES','utf-8');
						$admin_from_name						= sanitize_text_field( $this->pie_post_array['admin_from_name']);
						$update['admin_from_name']				= $this->disable_magic_quotes_gpc($admin_from_name);
						
						$update['admin_from_email']				= trim($this->pie_post_array['admin_from_email']);
						$update['admin_to_email']				= trim($this->pie_post_array['admin_to_email']);
						$update['admin_bcc_email']				= trim($this->pie_post_array['admin_bcc_email']);
						
						$admin_subject_email					= mb_convert_encoding($this->pie_post_array['admin_subject_email'],'HTML-ENTITIES','utf-8');
						$update['admin_subject_email']			= $this->disable_magic_quotes_gpc($admin_subject_email);
						
						$update['admin_message_email_formate']	= isset($this->pie_post_array['admin_message_email_formate']) ? intval($this->pie_post_array['admin_message_email_formate']) :0;
						
						$admin_message_email					= mb_convert_encoding($this->pie_post_array['admin_message_email'],'HTML-ENTITIES','utf-8');
						$update['admin_message_email']			= $this->disable_magic_quotes_gpc($admin_message_email);
					}else{
						$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
					}
				}
				else if(isset($_POST['user_email_notification_page']))
				{
					$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
					if(isset($_POST['piereg_user_email_notification']) && wp_verify_nonce( $_POST['piereg_user_email_notification'], 'piereg_wp_user_email_notification' ))
					{
						
						$this->pie_post_array	= $this->piereg_sanitize_post_data( 'user_email_notification_page',  ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
						
						$pie_user_email_types 	= get_option( 'pie_user_email_types'); 
						
						foreach ($pie_user_email_types as $val=>$type) 
						{
							if( isset($this->pie_post_array['user_from_name_'.$val]) ) 
							{
								$user_from_name = mb_convert_encoding(trim($this->pie_post_array['user_from_name_'.$val]),'HTML-ENTITIES','utf-8');
							}
							$update['user_from_name_'.$val]		= $this->disable_magic_quotes_gpc($user_from_name);
							
							if( isset($this->pie_post_array['user_from_email_'.$val]) ) 
							{
								$user_from_email = mb_convert_encoding(trim($this->pie_post_array['user_from_email_'.$val]),'HTML-ENTITIES','utf-8');
							}
							$update['user_from_email_'.$val]	= $this->disable_magic_quotes_gpc($user_from_email);

							if( isset($this->pie_post_array['user_to_email_'.$val]) ) 
							{
								$user_to_email = mb_convert_encoding(trim($this->pie_post_array['user_to_email_'.$val]),'HTML-ENTITIES','utf-8');
							}							
							$update['user_to_email_'.$val]		= $this->disable_magic_quotes_gpc($user_to_email);
							
							$user_bcc_email = "";
							if( isset($this->pie_post_array['user_bcc_email_'.$val]) ) 
							{
								$user_bcc_email = mb_convert_encoding(trim($this->pie_post_array['user_bcc_email_'.$val]),'HTML-ENTITIES','utf-8');
							}
							$update['user_bcc_email_'.$val]		= $this->disable_magic_quotes_gpc($user_bcc_email);
							
							if( isset($this->pie_post_array['user_subject_email_'.$val]) ) 
							{
								$user_subject_email = mb_convert_encoding(trim($this->pie_post_array['user_subject_email_'.$val]),'HTML-ENTITIES','utf-8');
							}	
							$update['user_subject_email_'.$val] = $this->disable_magic_quotes_gpc($user_subject_email);

							if( isset($this->pie_post_array['user_formate_email_'.$val]) ) 
							{
								$update['user_formate_email_'.$val]	= intval($this->pie_post_array['user_formate_email_'.$val]);
							}	
							
							
							$update['user_enable_'.$val]	= isset($this->pie_post_array['user_enable_'.$val]) ? intval($this->pie_post_array['user_enable_'.$val]) : 0;
							
							if( isset($this->pie_post_array['user_message_email_'.$val]) ) 
							{
								$user_message_email = mb_convert_encoding(trim($this->pie_post_array['user_message_email_'.$val]),'HTML-ENTITIES','utf-8');
							}	
							$update['user_message_email_'.$val] = $this->disable_magic_quotes_gpc($user_message_email);	
						}
					}else{
						$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
					}
				}
				
				update_option(OPTION_PIE_REGISTER, $update );
				PieReg_Base::set_pr_global_options($update, OPTION_PIE_REGISTER );
				if( isset($error) && trim($error) != "" )
				{
					$this->pie_post_array['PR_license_notice'] = $error;
				}
				if(!isset($this->pie_post_array['error']) && empty($this->pie_post_array['error']))
					$this->pie_post_array['notice'] = apply_filters("piereg_settings_saved",__('Settings Saved', 'pie-register'));

			}
		}
		
		
		/*
			*	Get Field's Name For Post
			*	Add this snipt at 17/10/2014
		*/
		function getPieregFieldName($field,$no,$field_type = ""){
			
			$fieldName = "";
			if(isset($field['type']) && !empty($field['type']) && isset($field['id']) )
			{
				switch($field['type']){
					case "username":
					case "password":
					case "custom_role":  // dropdown_ur
					case "pricing":	
						$fieldName = $field['type'];
					break;
					case "email":	
						$fieldName = "e_mail";
					break;
					case "default":	
						$fieldName = $field['field_name'];
					break;
					default:
						$fieldName = $field['type']."_".$field['id'];
					break;
				}
			}
			
			return $fieldName;
		}
		function addTextField($field,$no,$field_type)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no,$field_type);
			
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" class="input_fields"  placeholder="'.((isset($field['placeholder']))?esc_attr($field['placeholder']):"").'" type="text" value="'.((isset($field['default_value']))?esc_attr($field['default_value']): "" ).'" data-field-post-name="'.esc_attr($fieldPostName).'" />';

		}
		function addInvitationField($field,$no)
		{		
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 	= $this->createFieldName($field,$no);
			
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			echo '<input disabled="disabled" id="invitation_field" name="'.esc_attr($name).'" class="input_fields"  placeholder="'.esc_attr($field['placeholder']).'" type="text" value="'.((isset($field['default_value']))?esc_attr($field['default_value']): "" ).'" />';	

		}
		function addTermsField($field,$no)
		{		
			$name 	= $this->createFieldName($field,$no);
			
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			echo '<label for="terms_field"><input type="checkbox" name="terms" id="terms_field" value="1" class="input_fields_checkbox" checked disabled>' . ((isset($field['label']))?esc_html($field['label']): "" ) . '</label>';

		}
		function addDefaultField($field,$no)
		{		
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			if($field['field_name']=="description")
			{
				echo '<textarea  rows="5" cols="73" disabled="disabled" data-field_id="piereg_field_'.esc_attr($no).'" id="default_'.esc_attr($field['field_name']).'" name="'.esc_attr($name).'"  style="width:100%;"  data-field-post-name="'.esc_attr($fieldPostName).'" ></textarea>';
			}
			else
			{
				echo '<input disabled="disabled" id="default_'.esc_attr($field['field_name']).'" data-field_id="piereg_field_'.esc_attr($no).'" name="'.esc_attr($name).'" class="input_fields"  placeholder="'.(isset($field['placeholder'])? esc_attr($field['placeholder']):"").'" type="text"  data-field-post-name="'.esc_attr($fieldPostName).'" />';
			}
			echo '<input type="hidden" name="field['.esc_attr($field['id']).'][id]" value="'.esc_attr($field['id']).'" id="id_'.esc_attr($field['id']).'">';
			echo '<input type="hidden" name="field['.esc_attr($field['id']).'][type]" value="default" id="type_'.esc_attr($field['id']).'">';
			echo '<input type="hidden" name="field['.esc_attr($field['id']).'][label]" value="'.esc_attr($field['label']).'" id="label_'.esc_attr($field['id']).'">';
			echo '<input type="hidden" name="field['.esc_attr($field['id']).'][field_name]" value="'.esc_attr($field['type']).'" id="label_'.esc_attr($field['id']).'">';
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
		}
		function addEmail($field,$no)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 			= $this->createFieldName($field,$no);
			$id 			= $this->createFieldID($field,$no);
			$confirm_email = 'style="display:none;"';
			
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" data-field_id="piereg_field_'.esc_attr($no).'" class="input_fields"  placeholder="'.((isset($field['placeholder']))?esc_attr($field['placeholder']): "" ).'" type="text" value="'.((isset($field['default_value']))?esc_attr($field['default_value']): "" ).'"  data-field-post-name="'.esc_attr($fieldPostName).'" />';
			if(isset($field['confirm_email']))
			{
				$confirm_email	= "";
			}
			
			$label2 = (isset($field['label2']) and !empty($field['label2']))?$field['label2'] : __("Confirm E-Mail","pie-register");
			echo '</div><div '.$confirm_email.' id="field_label2_'.esc_attr($no).'" class="label_position confrim_email_label2"><label>'.esc_html($label2).'</label></div><div class="fields_position"><div id="confirm_email_field_'.esc_attr($no).'" '.$confirm_email.' class="inner_fields"><input disabled="disabled" type="text" id="pie2_'.esc_attr($id).'" class="input_fields" placeholder="'.((isset($field['placeholder2']))?esc_attr($field['placeholder2']): "" ).'" > </div>';	
		}
		function addPassword($field,$no)
		{		
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 			= $this->createFieldName($field,$no);
			$id 			= $this->createFieldID($field,$no);
			
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" class="input_fields"  placeholder="'.esc_attr($field['placeholder']).'" type="text" value=""  data-field-post-name="'.esc_attr($fieldPostName).'" />';
			
			$label2 = (isset($field['label2']) and !empty($field['label2']))?$field['label2'] : __("Confirm Password","pie-register");
			echo '</div><div id="field_label2_'.esc_attr($no).'" class="label_position"><label>'.esc_html($label2).'</label></div><div class="fields_position"><div id="confirm_email_field_'.esc_attr($no).'" class="inner_fields"><input disabled="disabled" type="text" class="input_fields" id="pie2_'.esc_attr($id).'" placeholder="'.((isset($field['placeholder2']))?esc_attr($field['placeholder2']): "" ).'" > </div>';	
		}
		
		function addUpload($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" class="input_fields"  type="file"  />';	
		}
		function addProfilePicUpload($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" class="input_fields"  type="file"  />';
		}
		
		function addAddress($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			echo '<div class="address" id="address_fields">
			  <input disabled="disabled" type="text" class="input_fields">
			  <label>'.__("Street Address","pie-register").'</label>
			</div>';
			
			$hideAddress2= "";
			if(isset($field['hide_address2']) && $field['hide_address2'])
			{
				$hideAddress2 = 'style="display:none;"';	 
			}
			 
			echo '<div class="address" id="address_address2_'.esc_attr($no).'" '.$hideAddress2.'>
			  <input disabled="disabled" type="text" class="input_fields">
			  <label>'.__("Address Line 2","pie-register").'</label>
			</div>
			<div class="address">
			  <div class="address2">
				<input disabled="disabled" type="text" class="input_fields">
				<label>'.__("City","pie-register").'</label>
			  </div>';
			
			 $hide_state = "";
			 if(isset($field['hide_state']) && $field['hide_state'])
			 {
				$hide_state 		= 'style="display:none;"';	
				$hide_usstate 		= 'style="display:none;"';	
				$hide_canstate 		= 'style="display:none;"';	 
			 } 
			 else 
			 {
					if($field['address_type'] == "International")
					{
						$hide_state 		= '';		
					}
					else if($field['address_type'] == "United States")
					{
						$hide_usstate 		= '';	
					}
					else if($field['address_type'] == "Canada")
					{
						$hide_canstate 		= '';	
					}
			 }
			
			
			 echo '<div class="address2 state_div_'.esc_attr($no).'" id="state_'.esc_attr($no).'" '.$hide_state .'>
				<input disabled="disabled" type="text" class="input_fields">
				<label>'.__("State / Province / Region","pie-register").'</label>
			  </div>
			  <div class="address2 state_div_'.esc_attr($no).'" id="state_us_'.esc_attr($no).'" '.((isset($hide_usstate))?$hide_usstate:"") .'>
				<select disabled="disabled" id="state_us_field_'.esc_attr($no).'">
				  <option value="" selected="selected">'.esc_html($field['us_default_state']).'</option>
				  
				</select>
				<label>'.__("State","pie-register").'</label>
			  </div>
			  <div class="address2 state_div_'.esc_attr($no).'" id="state_canada_'.esc_attr($no).'" '.((isset($hide_canstate))?$hide_canstate:"").'>
				<select disabled="disabled" id="state_canada_field_'.esc_attr($no).'">
				  <option value="" selected="selected">'.esc_html($field['canada_default_state']).'</option>
				  
				</select>
				<label>'.__("Province","pie-register").'</label>
			  </div>
			</div>
			<div class="address">
				<div class="address2">
				<input disabled="disabled" type="text" class="input_fields">
				<label>'.__("Zip / Postal Code","pie-register").'</label>
			  </div>';
			 
			 $hideCountry = "";
			 if(isset($field['address_type']) && $field['address_type'] != "International")
			 {
				$hideCountry = 'style="display:none;"';	 
			 }
			 
			  echo '<div id="address_country_'.esc_attr($no).'" class="address2" '.$hideCountry.'>
						<select disabled="disabled">
							<option>'.esc_html($field['default_country']).'</option>
						</select>
						<label>'.__("Country","pie-register").'</label>
					</div>
			</div>';	
		}
		//pie-register-woocommerce addon 
		function addWooCommerceBillingAddress($field,$no)
		{
			if ($this->woocommerce_and_piereg_wc_addon_active)
			{
				$name 	= $this->createFieldName($field,$no);
				$id 	= $this->createFieldID($field,$no);

				echo apply_filters("pieregister_print_woocommerce_billing_address_admin", $name, $id, $field, $no);
			}
		}

		function addWooCommerceShippingAddress($field,$no)
		{
			if ($this->woocommerce_and_piereg_wc_addon_active)
			{
				$name 	= $this->createFieldName($field,$no);
				$id 	= $this->createFieldID($field,$no);
				
				echo apply_filters("pieregister_print_woocommerce_shipping_address_admin", $name, $id, $field, $no);
			}
		}

		function addTextArea($field,$no)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
				
			echo '<textarea disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" data-field_id="piereg_field_'.esc_attr($no).'" rows="'.esc_attr($field['rows']).'" cols="'.esc_attr($field['cols']).'"   placeholder="'.esc_attr($field['placeholder']).'"  style="width:100%;" data-field-post-name="'.esc_attr($fieldPostName).'">'.esc_textarea($field['default_value']).'</textarea>';

		}
		
		function addDropdown($field,$no)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no);
			
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			$multiple = "";
			
			$data_f_post_name = 'data-field-post-name="'.$fieldPostName.'"';
			if($field['type']=="multiselect")
			{
				$multiple 	= 'multiple';	
				$name		.= "[]";
				$data_f_post_name = "";
			}elseif($field['type']=="custom_role"){
				echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			}
			echo '<select '.$multiple.' id="'.esc_attr($name).'" name="'.esc_attr($name).'" data-field_id="piereg_field_'.esc_attr($no).'" disabled="disabled" '.$data_f_post_name.'>';
		
			if(sizeof($field['value']) > 0)
			{
			
				for($a = 0 ; $a < sizeof($field['value']) ; $a++)
				{
					$selected = '';
					if(isset($field['selected']) && in_array($a,$field['selected']))
					{
						$selected = 'selected="selected"';	
					}				
					echo '<option '.$selected.' value="'.esc_attr($field['value'][$a]).'">'.esc_html($field['display'][$a]).'</option>';	
				}		
			}	
			echo '</select>';			
		}
		function addNumberField($field,$no)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input disabled="disabled" id="'.esc_attr($id).'" name="'.esc_attr($name).'" class="input_fields"  placeholder="'.esc_attr($field['placeholder']).'" min="'.esc_attr($field['min']).'" max="'.esc_attr($field['max']).'" type="number" value="'.esc_attr($field['default_value']).'" data-field-post-name="'.esc_attr($fieldPostName).'"/>';
		}
		function addCheckRadio($field,$no)
		{
			if(sizeof($field['value']) > 0)
			{
				$fieldPostName = $this->getPieregFieldName($field,$no);
				echo '<div class="radio_wrap">';
				$name 	= $this->createFieldName($field,$no);
				$id 	= $this->createFieldID($field,$no);
				
				echo '<input type="hidden"  data-field-post-name="'.esc_attr($fieldPostName).'"/>';
				for($a = 0 ; $a < sizeof($field['value']) ; $a++)
				{
					$checked = '';
					if(isset($field['selected']) && is_array($field['selected']) && in_array($a,$field['selected']))
					{
						$checked = 'checked="checked"';	
					}				
					echo '<div class="wrapcheckboxes"><label>'.esc_html($field['display'][$a]).'</label>';
					
						echo '<input '.$checked.' type="'.esc_attr($field['type']).'" name="'.esc_attr($field['type']).'_'.esc_attr($field['id']).'[]" class="radio_fields" disabled="disabled" ></div>';
				}		
				echo '</div>';
			}			
		}	
		function addDate($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			$datefield 		= 'style="display:none;"';
			$datepicker 	= 'style="display:none;"';
			$datedropdown 	= 'style="display:none;"';
			$calendar_icon 	= 'style="display:none;"';
			$calendar_url 	= 'style="display:none;"';
			
			if($field['date_type'] == "datefield")
			{
				$datefield = "";		
			}
			else if($field['date_type'] == "datepicker")
			{
				$datepicker = "";
				if($field['calendar_icon'] == "calendar")		
				{
					$calendar_icon = "";
				}
			}
			else if($field['date_type'] == "datedropdown")
			{
				$datedropdown = "";		
			}
			
			echo '<div class="time date_format_field" id="datefield_'.esc_attr($no).'" '.$datefield.'>
					  <div class="time_fields" id="mm_'.esc_attr($no).'">
						<input disabled="disabled" type="text" class="input_fields">
						<label>'.__("MM","pie-register").'</label>
					  </div>
					  <div class="time_fields" id="dd_'.esc_attr($no).'">
						<input disabled="disabled" type="text" class="input_fields">
						<label>'.__("DD","pie-register").'</label>
					  </div>
					  <div class="time_fields" id="yyyy_'.esc_attr($no).'">
						<input disabled="disabled" type="text" class="input_fields">
						<label>'.__("YYYY","pie-register").'</label>
					  </div>
					</div>';
					
			echo	'<div class="time date_format_field" id="datepicker_'.esc_attr($no).'" '.$datepicker.'>
					  <input disabled="disabled" type="text" class="input_fields">
	  				  <img src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/calendar.png').'" id="calendar_image_'.esc_attr($no).'" '.$calendar_icon.' /> </div>';

					  
				  
			echo '<div class="time date_format_field" id="datedropdown_'.esc_attr($no).'"  '.$datedropdown.'>
					  <div class="time_fields" id="month_'.esc_attr($no).'">
						<select disabled="disabled">
						  <option>'.__("Month","pie-register").'</option>
						</select>
					  </div>
					  <div class="time_fields" id="day_'.esc_attr($no).'">
						<select disabled="disabled">
						  <option>'.__("Day","pie-register").'</option>
						</select>
					  </div>
					  <div class="time_fields" id="year_'.esc_attr($no).'">
						<select disabled="disabled">
						  <option>'.__("Year","pie-register").'</option>
						</select>
					  </div>
					</div>';	
			
			
		}
		function piereg_get_small_string($string,$lenght=100,$atitional_string = "...."){
			$string = strip_tags(html_entity_decode( $string , ENT_COMPAT, 'UTF-8'));
			if(strlen($string) > $lenght){
				$string = wordwrap($string, $lenght, "<br />", true);
				$string = explode("<br />",$string);
				return $string[0].$atitional_string;
			}
			return $string;
		}
		function addHTML($field,$no)
		{
			echo '<div id="field_'.esc_attr($no).'" class="htmldiv" id="htmlbox_'.esc_attr($no).'_div">'.wp_kses_post($this->piereg_get_small_string($field['html'],200)).'</div>';
		}
		function addSectionBreak($field,$no)
		{
			echo '<div style="width:100%;float:left;border: 1px solid #aaaaaa;margin-top:25px;"></div>';	
		}
		function addPageBreak($field,$no)
		{
			echo '<img src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/pagebreak.png').'" style="max-width:100%;" />';
		}
		function addName($field,$no)
		{
			$label2 		= (isset($field['label2']) and !empty($field['label2']))?$field['label2'] : __("Last Name","pie-register");
			$placeholder 	= (isset($field['placeholder']) and !empty($field['placeholder']))?$field['placeholder'] : "";
			$placeholder2 	= (isset($field['placeholder2']) and !empty($field['placeholder2']))?$field['placeholder2'] : "";
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input disabled="disabled" type="text" id="'.esc_attr($id).'" placeholder="'.esc_attr($placeholder).'" class="input_fields" data-field-post-name="first_name">';
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			
			echo '</div><div id="field_label2_'.esc_attr($no).'" class="label_position"><label>'.esc_html($label2).'</label></div><div class="fields_position">  <input disabled="disabled" type="text" id="pie2_'.esc_attr($id).'" placeholder="'.esc_attr($placeholder2).'" class="input_fields" data-field-post-name="last_name">';
		}
		function addTime($field,$no)
		{
			
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			$format = "display:none;";		
			
			if($field['time_type']=="12")
			{
				$format = "";
			}		
			
		echo '<div class="time"><div class="time_fields"><input disabled="disabled" type="text" class="input_fields"><label>'.__("HH","pie-register").'</label></div><span class="colon">:</span><div class="time_fields"><input disabled="disabled" type="text" class="input_fields"><label>'.__("MM","pie-register").'</label></div><div id="time_format_field_'.esc_attr($no).'" class="time_fields" style="'.$format.'"><select disabled><option>'.__("AM","pie-register").'</option><option>PM</option></select></div></div>';

		
		}
		function addCaptcha($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			if(isset($field['recaptcha_type']) && $field['recaptcha_type'] == 2)
				echo '<img id="captcha_img" src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/new-recatpcha.png').'" data-captcha-img-src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/').'" />';
			else
				echo '<img id="captcha_img" src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/recatpcha.jpg').'" data-captcha-img-src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/').'" />';
			
			echo '<p>'.'<strong>'.__("Note","pie-register").':</strong> '.__("Please ensure that Re-captcha keys are entered on the Settings page","pie-register").'</p>';
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';	
		}
		function addMath_Captcha($field,$no)
		{
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
				
			echo '<img id="captcha_img" src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/math_catpcha.png').'" />';	
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
		}
		function addList($field,$no)
		{
			if($field['cols']=="0")
			$field['cols'] = 1;
			
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			$width  = 90 / $field['cols']; 
			
			for($a = 1 ; $a <= $field['cols'] ;$a ++)
			{
				echo '<input type="text" id="field_'.esc_attr($no).'" class="input_fields" style="width:'.$width.'%;margin-right:2px;" >';
			}
			
			echo '<img src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/plus.png').'" />';
		}
		function addPricing($field,$no)
		{
			$fieldPostName = $this->getPieregFieldName($field,$no);
			$name 	= $this->createFieldName($field,$no);
			$id 	= $this->createFieldID($field,$no);
			
			echo '<input type="hidden"  data-field-post-name="'.esc_attr($fieldPostName).'"/>';
			echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
			if(isset($field['display']) && sizeof($field['display']) > 0)
			{
				echo '<div class="piereg_pricing_radio radio_wrap" '.((isset($field['field_as']) && $field['field_as'] == 1)?'style="display: none;"':((!isset($field['field_as']))?'style="display: none;"':"")).'>';
				echo '<input type="hidden"  data-field-post-name="'.esc_attr($fieldPostName).'"/>';
				for($a = 0 ; $a < sizeof($field['display']) ; $a++)
				{
					$checked = '';
					if(isset($field['selected']) && is_array($field['selected']) && in_array($a,$field['selected']))
					{
						$checked = 'checked="checked"';	
					}				
					echo '<label>'.esc_html($field['display'][$a]).'</label>';
					echo '<input '.$checked.' type="radio" name="'.esc_attr($field['type']).'_'.esc_attr($field['id']).'[]" class="radio_fields" disabled="disabled" >';
				}
				echo '<input type="hidden" id="default_'.esc_attr($field['type']).'">';
				echo '</div>';
			}
			
			echo '<div class="piereg_pricing_select select_wrap" '.((isset($field['field_as']) && $field['field_as'] != 1)?'style="display: none;"':"").'>';
			
			echo '<select id="'.esc_attr($name).'" name="piereg_pricing" data-field_id="piereg_pricing_field_'.esc_attr($no).'" disabled="disabled"  data-field-post-name="'.esc_attr($fieldPostName).'">';
			if(isset($field['display']) && sizeof($field["display"]) > 0)
			{
				for($a = 0 ; $a < sizeof($field["display"]) ; $a++)
				{
					$selected = '';
					if(in_array($a,$field['selected']))
					{
						$selected = 'selected="selected"';
					}
					echo '<option '.$selected.' value="'.esc_attr($field['value'][$a]).'">'.esc_html($field['display'][$a]).'</option>';
				}
			}
			echo '</select>';
			echo '</div>';
			
		}		
		function createFieldName($field)
		{
			return "field_[".$field['id']."]";		
		}
		function createFieldID($field,$no)
		{
			return "field_".$field['id'];	
		}
		
		private function log_ipn_results($success) {
			$hostname = gethostbyaddr( $_SERVER ['REMOTE_ADDR'] );
			// Timestamps
			$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
			// Success or failure being logged?
			if ($success)
				$this->ipn_status = $text . 'SUCCESS:' . $this->ipn_status . "!\n";
			else
				$this->ipn_status = $text . 'FAIL: ' . $this->ipn_status . "!\n";
				// Log the POST variables
			$this->ipn_status .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]IPN POST Vars Received By Paypal_IPN Response API:\n";
			foreach ( $this->ipn_data as $key => $value ) {
				$this->ipn_status .= "$key=$value \n";
			}
			// Log the response from the paypal server
			$this->ipn_status .= "IPN Response from Paypal Server:\n" . $this->ipn_response;
			$this->write_to_log();
		}
		private function write_to_log() {
			if (! $this->ipn_log)
				return; // is logging turned off?
	
			if(file_exists(PIE_LOG_FILE))
			{
				// Write to log
				$fp = fopen ( PIE_LOG_FILE , 'a+' );
				fwrite ( $fp, $this->ipn_status . "\r\n" );
				fclose ( $fp ); // close file
				//chmod ( PIE_LOG_FILE , 0600 );
			}
		}
		
		public function get_user_by_hash($user_data){		
			$data_array = explode("__",$user_data);
			$user		= get_user_by('id', $data_array[1]);
			return sanitize_email($user->user_email);		
		}
		
		public function validate_ipn() {
			
			/*
				*	IPN LOG
			*/
			$user_payment_log 				= array();
			$user_payment_log['method'] 	= "Paypal";
			$user_payment_log['type'] 		= "Hosted Button IPN ";
			$user_payment_log['responce'] 	= $_REQUEST;
			$user_payment_log['date'] 		= date_i18n("d-m-Y H:i:s");
			$user_email						= $this->get_user_by_hash($_REQUEST['custom']);
			$log_message = print_r( $user_payment_log, 1 );
			$this->pr_payment_log($log_message);
			 /**
			 * v3.7.1.5 - Temporary disabled log in database.
			 * 			  Payment logs are logging in log file only.
			 */
			// $this->piereg_save_payment_log_option( $user_email, "PayPal", "Hosted Button IPN", $_REQUEST );
			unset($log_message);
			unset($user_payment_log);
			//IPN log end
			
			global $wpdb;
			$piereg = get_option(OPTION_PIE_REGISTER);
			$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
			if (! preg_match ( '/paypal\.com$/', $hostname )) {
				$this->ipn_status = 'Validation post isn\'t from PayPal';
				$this->log_ipn_results ( false );
				return false;
			}
			
			if (isset($this->txn_id) && ($_POST['txn_id']==$this->txn_id)) {
				$this->ipn_status = "txn_id have a duplicate";
				$this->log_ipn_results ( false );
				return false;
			}
	
			// parse the paypal URL
			$paypal_url = ($_POST['test_ipn'] == 1) ? PIE_SSL_SAND_URL : PIE_SSL_P_URL;
			$url_parsed = parse_url($paypal_url);        
			
			// generate the post string from the _POST vars aswell as load the
			// _POST vars into an arry so we can play with them from the calling
			// script.
			$post_string = '';
			
			foreach ($_POST as $field=>$value) { 
				$this->ipn_data["$field"] = $value;
				$post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
			}
			$post_string.="cmd=_notify-validate"; // append ipn command
			
			// open the connection to paypal
			if ($piereg['paypal_sandbox'] == "yes") {
				$fp = fsockopen ( 'tls://www.sandbox.paypal.com', "443", $err_num, $err_str, 60 );
				if(!$fp) {
					$fp = fsockopen ( 'ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60 );
				}
			}else{
				$fp = fsockopen ( 'tls://www.paypal.com', "443", $err_num, $err_str, 60 );
	 			if(!$fp) {
					$fp = fsockopen ( 'ssl://www.paypal.com', "443", $err_num, $err_str, 60 );		
				}
			}
	 		
			if(!$fp) {
				// could not open the connection.  If loggin is on, the error message
				// will be in the log.
				$this->ipn_status = "fsockopen error no. $err_num: $err_str";
				$this->log_ipn_results(false);       
				return false;
			} else { 
				// Post the data back to paypal
				fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
				fputs($fp, "Host: $url_parsed[host]\r\n");
				fputs($fp, "User-Agent: Pie-Register IPN Validation Service\r\n");
				fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
				fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
				fputs($fp, "Connection: close\r\n\r\n"); 
				fputs($fp, $post_string . "\r\n\r\n"); 
			
				// loop through the response from the server and append to variable
				while(!feof($fp)) { 
				$this->ipn_response .= fgets($fp, 1024); 
			   } 
			  fclose($fp); // close connection
			}
			
			// Invalid IPN transaction.  Check the $ipn_status and log for details.
			if (!preg_match("/VERIFIED/",$this->ipn_response)) {
				$this->ipn_status = "IPN NOT VERIFIED\n".print_r($this->ipn_response,1)."\n";
				$this->log_ipn_results(false);
				return false;
			} else {
				$this->ipn_status = "IPN VERIFIED";
				//////////// Verify User /////////////
				// paypal Variable our custom variable
				if( isset($_REQUEST['paypal']) && !empty($_REQUEST['paypal']) )
					$this->processPostPayment($_REQUEST['paypal']);
				//////////////////////////////////////
				$this->log_ipn_results(true); 
				header("HTTP/1.1 200 OK");
				die();
				return true;
			}
			header("HTTP/1.1 402 Payment Required");
			die();
		}
		function ValidPUser(){
			global $wpdb;
			//$piereg = get_option( 'pie_register' );
			$piereg = get_option(OPTION_PIE_REGISTER);
			
			if(isset($_POST['txn_id']) && $_GET['action'] == 'ipn_success'){
				//We have a IPN to Validate
				$this->validate_ipn();
			}
			if(isset($_GET['action']) && $_GET['action'] == 'payment_success'){
				$this->payment_success_cancel_after_register("payment=success");
			}elseif(isset($_GET['action']) && $_GET['action'] == 'payment_cancel'){
				/******************************************************/
				$user_id 		= intval(base64_decode($_GET['paypal']));
				$user_data		= get_userdata($user_id);
				if(is_object($user_data)){
					$form 			= new Registration_form();
					//$option 		= get_option( 'pie_register' );
					$option 		= get_option(OPTION_PIE_REGISTER);
					$subject 		= html_entity_decode($option['user_subject_email_payment_faild'],ENT_COMPAT,"UTF-8");
					$subject = $this->filterSubject($user_data,$subject);
					$message_temp = "";
					if($option['user_formate_email_payment_faild'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_payment_faild']));
					}else{
						$message_temp	= $option['user_message_email_payment_faild'];
					}
					$message		= $form->filterEmail($message_temp,$user_data, "" );
					$from_name		= $option['user_from_name_payment_faild'];
					$from_email		= $option['user_from_email_payment_faild'];
					$reply_email 	= $option['user_to_email_payment_faild'];
					//Headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	
					if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
						$headers .= "From: ".$from_name." <".$from_email."> \r\n";
	
					if($reply_email){
						$headers .= "Reply-To: {$reply_email}\r\n";
						$headers .= "Return-Path: {$reply_email}\r\n";
	
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
	
					if((isset($option['user_enable_payment_faild']) && $option['user_enable_payment_faild'] == 1) && !wp_mail($user_data->user_email, $subject, $message , $headers)){
						$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					unset($user_data);
				}
				/******************************************************/
				$this->payment_success_cancel_after_register("payment=cancel");
			}else{
				return false;
			}
		}
		function processPostPayment( $custom_user_data )
		{
			if( empty($custom_user_data) )
				return false;
			
			add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
			
			$custom_user_data_decode = base64_decode($custom_user_data);
			$return_data = explode( "|", $custom_user_data_decode );
			$hash 		= $return_data[0];
			$user_id 	= intval($return_data[1]);				 
			
			#get_usermeta deprecated
			$check_hash = get_user_meta( $user_id, "hash", true);
			
			if($check_hash != $hash)
				return false;
			
			if(!is_numeric($user_id ))
				return false;	
				
			$user 		= new WP_User($user_id);
			$option 	= get_option(OPTION_PIE_REGISTER);
			update_user_meta( $user_id, 'active',1);
			update_user_meta( $user_id, 'pie_paypal_txn_id', sanitize_text_field($_POST['txn_id']));
			update_user_meta( $user_id, 'pie_paypal_payer_id', sanitize_text_field($_POST['payer_id']));
			
			//Sending E-Mail to newly active user
			$subject 		= html_entity_decode($option['user_subject_email_payment_success'],ENT_COMPAT,"UTF-8");
			$subject 		= $this->filterSubject($user,$subject);
			$user_email 	= $user->data->user_email;
			$message_temp = "";
			if($option['user_formate_email_payment_success'] == "0"){
				$message_temp	= nl2br(strip_tags($option['user_message_email_payment_success']));
			}else{
				$message_temp	= $option['user_message_email_payment_success'];
			}
			
			$message		= $this->filterEmail($message_temp,$user);
			$from_name		= $option['user_from_name_payment_success'];
			$from_email		= $option['user_from_email_payment_success'];
			$reply_email	= $option['user_to_email_payment_success'];
			
			//Headers
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
			$headers .= "From: ".$from_name." <".$from_email."> \r\n";
			if($reply_email){
				$headers .= "Reply-To: {$reply_email}\r\n";
				$headers .= "Return-Path: {$reply_email}\r\n";
			}else{
				$headers .= "Reply-To: {$from_email}\r\n";
				$headers .= "Return-Path: {$from_email}\r\n";
			}
			if((isset($option['user_enable_payment_success']) && $option['user_enable_payment_success'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
				$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			// mailchimp related code within PR
			do_action('pireg_after_verification_users', $user);
		}	
		
		function Add_payment_option_PaypalStandard($field_as)
		{
			$PR_options = "";
			$check_payment = PieRegister::get_pr_global_options();
			if( $check_payment["enable_paypal"] == 1 && !empty($check_payment['paypal_butt_id']) )
			{
				if( $field_as == 0 )
					$PR_options = '<div class="piereg_payment_selection piereg_payment_selection_paypalStandard"><label><input type="radio" name="select_payment_method" id="select_payment_method_PaypalStandard" value="PaypalStandard" data-img="'.plugins_url("assets/images/PaypalStandard-logo.png",__FILE__).'" class="input_fields  radio_fields piereg_select_payment_method" /><img src="'.plugins_url("assets/images/paypal_std_btn.png",__FILE__).'" /></label></div>';
				else
					$PR_options = '<option value="PaypalStandard" data-img="'.plugins_url("assets/images/PaypalStandard-logo.png",__FILE__).'">'.__("Paypal (Standard)","pie-register").'</option>';
			}
			return $PR_options;
		}
		
		function set_html_content_type() 
		{
			return 'text/html';
		}
		function deleteUsers($user_id = 0,$user_email = "",$user_registered = "",$autodelete = false)
		{
			$option 		= get_option(OPTION_PIE_REGISTER);
			$grace_period 	= isset($option['grace_period']) ? ((int)$option['grace_period']) : 0;
			$is_active 		= get_user_meta($user_id, 'active', true);

			if( isset($option['enable_paypal']) && $option['enable_paypal'] == 1 )
			{
				$grace			= $grace_period;
			}
			else if(($this->check_enable_payment_method()) == "true" )
			{
				$payment_setting_remove_user_days	= isset($option['payment_setting_remove_user_days']) ? $option['payment_setting_remove_user_days'] : 0;
				$grace								= ((int)$payment_setting_remove_user_days);
			}
			else
			{
				$grace			= $grace_period;
			}

			$register_type = get_user_meta( $user_id , "register_type" , true);
			
			
			if( ($grace != 0 and $user_id != 0) and ($user_email != "" and $user_registered != "") and ($register_type != '' and $register_type != "admin_verify") && $is_active == 0 )
			{
				$date			= date_i18n("Y-m-d 00:00:00",strtotime("-{$grace} days"));
				
				if($user_registered < $date)
				{
					global $errors;
					$errors = new WP_Error();
					
					if((isset($option['user_enable_user_perm_blocked_notice']) && $option['user_enable_user_perm_blocked_notice'] == 1))
					{
						$this->wp_mail_send($user_email,"user_perm_blocked_notice");
					}  
					
					global $wpdb;
					$user_table = $wpdb->prefix."users";
					$user_meta_table = $wpdb->prefix."usermeta";
					if(!$wpdb->query( $wpdb->prepare("DELETE FROM `".$user_meta_table."` WHERE `user_id` =  %d", $user_id) )){
						$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					if(!$wpdb->query( $wpdb->prepare("DELETE FROM `".$user_table."` WHERE `ID` = %d", $user_id) )){
						$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					
					if( $autodelete === false )
					{
						$errors->add('login-error',apply_filters("piereg_msg_deleted_unverified_user",__('Account has been removed because it was not verified before the grace period expired.','pie-register')));
						wp_logout();
					}
					
					return true;
				}
			}
			return false;
		}
		function deleteUnverifiedUsers()
		{
						
			$args = array(
				'meta_query' => array(
					'relation' => 'AND', 
						array(
							'key'     	=> 'active',
							'value'   	=> '0',
							 'compare' 	=> '='
						),
						array(
							'key'     	=> 'register_type',
							'value'   	=> 'email_verify',
							 'compare' 	=> '='
						)
				)
			);
			
			$user_query 	= new WP_User_Query( $args );
			$users 			= $user_query->get_results();
			
			if(count($users) > 0)
			{
				foreach($users as $user)
				{
					$user_email	= $user->user_email;
					$user_login	= $user->user_login;
					
					if($this->deleteUsers($user->ID,$user->user_email,$user->user_registered,true))
					{
						$deleted	= get_option("deleted_users");
						
						$deleted["pie_user_" . $user_login]	= $user_email;
						update_option("deleted_users",$deleted);
					}
				}
			}
		}
		function unique_user()
		{
			$username 	= sanitize_user($_REQUEST['fieldValue']);		
			$validateId	= sanitize_key($_REQUEST['fieldId']);
			
			$arrayToJs = array();
			$arrayToJs[0] = $validateId;
	
			if(!username_exists($username ))
			{		// validate??
					$arrayToJs[1] = true;			// RETURN TRUE
					echo json_encode($arrayToJs);			// RETURN ARRAY WITH success
			}
			else
			{
				for($x=0;$x<1000000;$x++)
				{
					if($x == 990000)
					{
						$arrayToJs[1] = false;
						echo json_encode($arrayToJs);		// RETURN ARRAY WITH ERROR
					}
				}				
			}
			die();
		}
		function outputRegForm($fromwidget=false,$form_id = "0",$title="true",$description="true"){
			$form = new Registration_form_template();
			$success 	= '' ;
			$error 		= '' ;
			$option 	= get_option(OPTION_PIE_REGISTER);
			$registration_from_fields = '<div class="pieregformWrapper pieregWrapper">';
			$registration_from_fields .= '<div id="show_pie_register_error_js" class="piereg_entry-content"></div>';
			
			$IsWidgetForm = "";
			if($fromwidget)
				$IsWidgetForm = "widget_";
				
			$registration_from_fields .= '<div id="pie_register_reg_form">';

			$registration_from_fields = apply_filters( 'pie_register_frontend_output_before', __($registration_from_fields,"pie-register") );
			
			/* Anyone can register */
			if($this->is_anyone_can_register() || $this->is_pr_preview){
				if(false === $this->is_pr_preview):
					$registration_from_fields .= '<form enctype="multipart/form-data" class="pie_register_reg_form" id="pie_'.(trim($IsWidgetForm)).'regiser_form" method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'" data-form="'.$form_id.'">';					
					if( function_exists( 'wp_nonce_field' )):
						$registration_from_fields .= wp_nonce_field('piereg_wp_registration_form_nonce','piereg_registration_form_nonce', true, false);		
					endif;					 				
				else:
					$registration_from_fields .= '<form onsubmit="return false" class="prRegFormPreview" id="pie_'.(trim($IsWidgetForm)).'regiser_form" data-form="'.$form_id.'">';
				endif;
				
				
					$output = $form->printFields($fromwidget,$form_id,$title,$description);
					if($form->countPageBreaks() > 1){
						$registration_from_fields .= '<div class="piereg_progressbar"></div>';
					}
					$registration_from_fields .= $output;
					if(false === $this->is_pr_preview):
						$registration_from_fields .= '</form>';
					else:
						$registration_from_fields .= '</form>';
					endif;
					
				}else{
					$registration_from_fields .= '<div class="alert alert-warning"><p class="piereg_warning">'.__("User registration is currently not allowed.","pie-register").'</p></div>';
				}
				$registration_from_fields = apply_filters( 'pie_register_frontend_output_after', __($registration_from_fields,"pie-register") );
				
				$registration_from_fields .= '</div></div>';

				$this->piereg_forms_per_page[ $form_id ] = $this->getCurrentFields($form_id);

				return $registration_from_fields;
			
			}
		function showForm($id="",$title="true",$description="true")
		{
			global $errors, $piereg_post_array;
			$option 		= get_option(OPTION_PIE_REGISTER);
			add_filter( 'wp_mail_content_type', array($this,'set_html_content_type' ));
			$classes_preset =  array( 'piereg_container', 'pieregWrapper' );

			$classes = apply_filters( 'pie_register_frontend_container_class', $classes_preset);
			
			$classes = implode( ' ' , $classes );

			$output = '<div class="'.$classes.'">';
			if( empty($this->pie_post_array) )  
			{
				$this->pie_post_array	= $piereg_post_array;
			}

			if( isset($_GET['action'],$_GET['key']) && $_GET['action'] == 'pie-ic' && !empty($_GET['key']) ) {
				$invite_code = sanitize_text_field(base64_decode(urldecode(trim($_GET['key']))));
				$invite_code = explode('|', $invite_code);
				$invite_code = ($invite_code[1]) ? $invite_code[1] : "";
				$allowed_invite_codes = [];
				$manage_settings = maybe_unserialize(get_option("pie_fields")); 
				foreach($manage_settings as $managed_setting){
					if($managed_setting['type'] == 'invitation'){
						if(isset($managed_setting['allowed_codes'])){
							$allowed_invite_codes = $managed_setting['allowed_codes'];
							
							if(!in_array($invite_code,$allowed_invite_codes)){
								$this->pie_post_array['error'] = apply_filters("piereg_invalid_invitaion_code",'<strong>'.ucwords(__('error','pie-register')).'</strong>: Invalid invitation code');
							}
							break;
						}
					}
				}
			}

			if(isset($this->pie_post_array['success']) && $this->pie_post_array['success'] != "")
				$output .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($this->pie_post_array['success'],"pie-register")).'</p>';
			if(isset($this->pie_post_array['error']) && $this->pie_post_array['error'] != "")
				$output .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($this->pie_post_array['error'],"pie-register")).'</p>';
			
			if(isset($this->pie_post_array['registration_success']) && $this->pie_post_array['registration_success'] != ""){
				$output .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($this->pie_post_array['registration_success'],"pie-register")).'</p>';
				unset($_POST);
			}
			if(isset($errors->errors) && sizeof($errors->errors) > 0)
			{
				$error = "";
				foreach($errors->errors as $key=>$err)
				{
					if($key != "login-error")
						$error .= $err[0] . "<br />";
				}
				if(!empty($error))
					$output .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($error,"pie-register")).'</p>';
			}
			$output .= $this->outputRegForm(FALSE,$id,$title,$description);
			$output .= '</div>';
			return $output;
		}
		function showLoginForm()
		{
			$this->piereg_ssl_template_redirect();
			global $errors,$pagenow;
			$option 		= $this->get_pr_global_options();

			//  For Form with Gutenberg or WPBakery Preview
			$this->is_pr_preview = ( (isset($_GET['context']) && $_GET['context'] == 'edit') || (isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true') ) ? true :  $this->is_pr_preview;
			
			if(isset($_GET['pr_renew_account']) && $_GET['pr_renew_account'] == true)
			{
				$this->pie_post_array['warning'] = (__("Please renew your account","pie-register"));
				if( file_exists(PIEREG_DIR_NAME . "/renew_account.php") )
					include_once("renew_account.php");
				
				$is_renew_after_auth = false;
				$user_array = array();
				
				if(isset($_GET['auth'], $_GET['auth_key']) && !empty($_GET['auth']) && !empty($_GET['auth_key']) )
				{
					$user_name = sanitize_text_field(urldecode($_GET['auth']));
					$auth_key = sanitize_text_field(urldecode($_GET['auth_key']));
					
					$user_name = sanitize_user(urldecode(base64_decode($user_name)));
					$user = get_user_by("login",$user_name);
					
					if(!empty($user))
					{
						$auth_key = sanitize_text_field($_GET['auth_key']);
						$auth_key_hash = get_user_meta($user->ID,"pr_renew_account_hash", true);
						if(!empty($auth_key_hash))
						{
							if(trim($auth_key) == trim($auth_key_hash))
							{
								$is_renew_after_auth = true;
								$user_array['username'] = $user->data->user_login;
								$user_array['email'] = $user->data->user_email;
							}
						}
					}
				}
				
				$PR_show_renew_account = PR_show_renew_account($is_renew_after_auth,$user_array);
				return $PR_show_renew_account;
			}
			else{
				if (is_user_logged_in() && !$this->is_pr_preview && !is_admin()) {											
					return apply_filters("pie_login_form_logged_in_msg",__("<p>You are already logged in.</p>","pie-register"));
				} 

				$this->set_pr_stats("login","view");
				if( file_exists(PIEREG_DIR_NAME . "/login_form.php") )
					include_once("login_form.php");
				$output = pieOutputLoginForm();
				return  $output;
			}
		}
		
		function showForgotPasswordForm()
		{
			$this->piereg_ssl_template_redirect();
			global $errors;
			
			$option 		= get_option(OPTION_PIE_REGISTER);
			//  For Form with Gutenberg Or WPBakery Preview
			$this->is_pr_preview = ( (isset($_GET['context']) && $_GET['context'] == 'edit') || (isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true') ) ? true :  $this->is_pr_preview;

			if(!is_admin() && is_user_logged_in() && !$this->is_pr_preview)
			{
				return apply_filters("pie_forgot_form_logged_in_msg",__("<p>You are already logged in.</p>","pie-register"));
			}
			else
			{							
				$this->set_pr_stats("forgot","view");
				
				if( file_exists(PIEREG_DIR_NAME . "/forgot_password.php") )
					include_once("forgot_password.php");
				$output =  pieResetFormOutput();
				return $output;
			}
				
		}
		
		function showProfile()
		{
			$this->piereg_ssl_template_redirect();
			global $current_user,$pie_success,$pie_error,$pie_error_msg,$pie_suucess_msg;
			if ( is_user_logged_in() ){
				
				global $current_user;			
				wp_get_current_user();
				$form_id = get_user_meta($current_user->ID,"user_registered_form_id",true);
				if( isset($_GET['edit_user']) && $_GET['edit_user'] == "1" ){
					$form 		= new Edit_form($current_user,$form_id);
					if( isset($_POST['pie_submit_update'], $_POST['piereg_edit_profile_nonce']) && wp_verify_nonce($_POST['piereg_edit_profile_nonce'], 'piereg_wp_edit_profile_nonce') ) {
						$form->error = "";
						$errors = new WP_Error();
						$errors = $form->validateRegistration($errors);	
						if(sizeof($errors->errors) > 0) {
							foreach($errors->errors as $err)
							{
								$form->error .= $err[0] . "<br />";	
							}		  	
						}	
						else
						{
							$user_data = array('ID' => $current_user->ID);
							if(isset($_POST['url'])) {
								$user_data["user_url"] =  esc_url_raw($_POST['url']);	 
								$form->pie_success = 1;
							}
							 
							if($current_user->data->user_email != $_POST['e_mail']) {
								$user_data["user_email"] =  sanitize_email($_POST['e_mail']);
								$form->pie_success = 1;
							}
							if(wp_check_password( $_POST['old_password'], $current_user->data->user_pass, $current_user->ID ) && $_POST['password'] != ''){

								if(isset($_POST['confirm_password'])){
									if($_POST['password'] == $_POST['confirm_password']){
										$user_data["user_pass"] =  trim($_POST['password']);
										$form->pie_success = 1;		
									}
								}else{
									$user_data["user_pass"] =  trim($_POST['password']);
									$form->pie_success = 1;
								}							
							}
							
							$this->pie_post_array	= $this->piereg_sanitize_post_data_escape( ( (isset($_POST) && !empty($_POST)) ? $_POST : array() ) );
							
							# newlyAddedHookFilter 
							do_action( 'piereg_update_profile_event', $current_user->ID, $form_id, $this->pie_post_array );
							
							$id = wp_update_user( $user_data );						
							$form->UpdateUser();
						}
								
					}
					$output = '';
					$output .= '<div class="piereg_container pieregWrapper">';	
					if($form->pie_success)
						$output .= '<div class="alert alert-success"><p class="piereg_message">'.$form->pie_success_msg.'</p></div>';
	
					elseif($form->error != "")
						$output .= '<div class="alert alert-danger"><p class="piereg_login_error">'.$form->error.'</p></div>';	

					if(isset($this->pie_post_array['success']) && $this->pie_post_array['success'] != "")
						$output .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($this->pie_post_array['success'],"pie-register")).'</p>';
	
					if(isset($this->pie_post_array['error']) && $this->pie_post_array['error'] != "")
						$output .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($this->pie_post_array['error'],"pie-register")).'</p>';
						
					if( isset($_GET['pr_msg'], $_GET['type']) && !empty($_GET['pr_msg']) && $_GET['type'] == "success" )
						$output .= '<p class="piereg_message">'.apply_filters('piereg_messages',sanitize_text_field(base64_decode($_GET['pr_msg']))).'</p>';
					elseif( isset($_GET['pr_msg'], $_GET['type']) && !empty($_GET['pr_msg']) && $_GET['type'] == "error" )
						$output .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',sanitize_text_field(base64_decode($_GET['pr_msg']))).'</p>';
					
					if( file_exists(PIEREG_DIR_NAME."/edit_form.php") )
						require_once(PIEREG_DIR_NAME."/edit_form.php");
					
					$output.= pie_edit_userdata($form_id);
					$output .= '</div>';
					return $output;
				}
				else
				{
					$form_id = get_user_meta($current_user->ID,"user_registered_form_id",true);
					if( $form_id == "" ) {
						$form_free_id 	= $this->regFormForFreeVers();
						update_user_meta($current_user->ID, 'user_registered_form_id', $form_free_id);
						$form_id 		= $form_free_id;
					}
					
					$profile_front = new Profile_front($current_user,$form_id);
					// Newly added hook to make changes to the profile content
					$profile_form_data = apply_filters('piereg_after_profile_printed', __($profile_front->print_user_profile($form_id),'pie-register')); 
					return $profile_form_data;
				}
			}
			else
			{
				$notloggedinmsg = __('Please','pie-register').' <a class="linkStyle1" href="'.wp_login_url().'">'. __('login','pie-register').'</a> '.__('to see your profile','pie-register');
				
				$notloggedinmsg = apply_filters('piereg_profile_if_not_loggedin',$notloggedinmsg); # newlyAddedHookFilter
				
				return $notloggedinmsg;
			}	
		}
		
		
		/* GDPR - Personal Data Exporter */
		/*
		function my_plugin_exporter( $email_address, $page = 1 ) {
		  
		  $export_items = array();
		  $user 		= get_user_by('email', $email_address);	
		  $user_ID		= $user->ID;
		  
		  $data = array(
				array(
				  'name' => __( 'Commenter Latitude' ),
				  'value' => 'ABC'
				),
				array(
				  'name' => __( 'Commenter Longitude' ),
				  'value' => 'XYZ'
				)
			  );
		  $export_items[] = array(
				'group_id' => 167,
				'group_label' => "Comments",
				'item_id' => 265,
				'data' => $data,
			  );
		  
		  */
		  /*
		  $comments = get_comments(
			array(
			  'author_email' => $email_address,
			  'number' => $number,
			  'paged' => $page,
			  'order_by' => 'comment_ID',
			  'order' => 'ASC',
			)
		  );
		 
		  foreach ( (array) $comments as $comment ) {
			$latitude = get_comment_meta( $comment->comment_ID, 'latitude', true );
			$longitude = get_comment_meta( $comment->comment_ID, 'longitude', true );
		 
			// Only add location data to the export if it is not empty
			if ( ! empty( $latitude ) ) {
			  // Most item IDs should look like postType-postID
			  // If you don't have a post, comment or other ID to work with,
			  // use a unique value to avoid having this item's export
			  // combined in the final report with other items of the same id
			  $item_id = "comment-{$comment->comment_ID}";
		 
			  // Core group IDs include 'comments', 'posts', etc.
			  // But you can add your own group IDs as needed
			  $group_id = 'comments';
		 
			  // Optional group label. Core provides these for core groups.
			  // If you define your own group, the first exporter to
			  // include a label will be used as the group label in the
			  // final exported report
			  $group_label = __( 'Comments' );
		 
			  // Plugins can add as many items in the item data array as they want
			  $data = array(
				array(
				  'name' => __( 'Commenter Latitude' ),
				  'value' => $latitude
				),
				array(
				  'name' => __( 'Commenter Longitude' ),
				  'value' => $longitude
				)
			  );
		 
			  $export_items[] = array(
				'group_id' => $group_id,
				'group_label' => $group_label,
				'item_id' => $item_id,
				'data' => $data,
			  );
			}
		  }
		 
		  // Tell core if we have more comments to work on still
		  $done = count( $comments ) < $number;
		  
		  */
		  /*
		  return array(
			'data' => $export_items,
			'done' => true,
		  );
		  
		}
		function register_my_plugin_exporter( $exporters ) {
			$exporters['pie-register'] = array(
				'exporter_friendly_name' => __( 'Pie Register - Custom Fields' ),
				'callback' 				 => array($this, 'my_plugin_exporter'),
			);
			
			return $exporters;
		}
 		*/

		/* GDPR - Personal Data Exporter */
		
		
		function show_renew_account()
		{
			$this->piereg_ssl_template_redirect();
			if( file_exists(PIEREG_DIR_NAME . "/renew_account.php") )
				include_once("renew_account.php");
			
			$show_renew_account = PR_show_renew_account();
			return $show_renew_account;
		}
		
		function afterLoginPage()
		{
			global $wpdb,$current_user;
			$option = $this->get_pr_global_options();		
			/*
				Get after Logged in url by current user role
				*/
			
			$redirecturi = $this->ifRedirectUrlSet($current_user);			
			if($redirecturi) {	
				$this->pie_after_login_page_redirect_url = $redirecturi;			
				$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],true);
				exit;
			}
						
			$logged_in_url = "";
			$logged_in_page = "";
			
			if( !isset($option['social_site_popup_setting']) )
			{
				$option['social_site_popup_setting']	= 0;
			}
			
			if(!empty($logged_in_url) && ($logged_in_page == 0 || $logged_in_page == "")){
				$this->pie_after_login_page_redirect_url = $logged_in_url;
				$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],false);
				exit;
			}elseif(!empty($logged_in_page) && $logged_in_page > 0){
				$this->pie_after_login_page_redirect_url = get_permalink($logged_in_page);
				$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],true);
				exit;
			}
			elseif( $option['after_login'] == 'url' && !empty($option['alternate_login_url']) ){
				$this->pie_after_login_page_redirect_url = $option['alternate_login_url'];
				$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],false);
				exit;
			}
			elseif( $option['after_login'] > 0 ){
				if($option['after_login'] != 'url'){
					$this->pie_after_login_page_redirect_url = get_permalink($option['after_login']);
					$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],true);
					exit;
				}
			}
			elseif( isset($_GET['redirect_to']) && $_GET['redirect_to'] != "" && !current_user_can( 'administrator' ) ){
				// When account login with activation link and not any login page assigned
				if( (isset($_GET['action']) && $_GET['action'] == "activate") && (isset($_GET['activation_key']) && $_GET['activation_key'] != "") ) {
					$this->pie_after_login_page_redirect_url = site_url();
					$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],false);
				} else {
					$this->pie_after_login_page_redirect_url = esc_url_raw($_GET['redirect_to']);
					$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],false);
				}
				exit;					
			}else{
				$this->pie_after_login_page_redirect_url = site_url();
				$this->afterLoginPageRedirect($this->pie_after_login_page_redirect_url,$option['social_site_popup_setting'],false); 
				exit;
			}
			exit;
		}
		
		function ifRedirectUrlSet($user)
		{
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = esc_url_raw($_REQUEST['redirect_to']);
				// Redirect to https if user wants ssl
				if ( ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')  && false) !== strpos($redirect_to, 'wp-admin') )
					$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
			} else {
				$redirect_to = admin_url();
			}
			
			$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw($_REQUEST['redirect_to']) : '';
			$redirect_to 	= apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user );
			$last_segment 	= basename($redirect_to);
			$current_url 	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
			if( $last_segment == 'wp-admin' || $redirect_to == admin_url() )
			{
				return false;
			}
			
			return $redirect_to;
		}
		
		function afterLoginPageRedirect($url,$social_site_popup_setting = 0,$safe = true){
			if(
				   $social_site_popup_setting == 1 and 
				   $_POST['social_site']  == "true"
			   )
			{
				//Redirect thru JS File
			}
			else{
				if($safe)
					wp_safe_redirect($url);
				else
					wp_redirect(esc_url_raw($url));
			}
			exit;
		}
		
		function add_ob_start(){
			ob_start();
		}
		
		function get_paypalstandard($value,	$payment_gateways=array())
		{
			$data	= "";
			$check_payment 	= get_option(OPTION_PIE_REGISTER);
			
			if($check_payment["enable_paypal"] == 1 && !(empty($check_payment['paypal_butt_id'])) && in_array( "PaypalStandard", $payment_gateways ))
			{
				$data .= '<img src="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/paypal_std_btn.png').'">';
				$data .= '<p class="desc">'.__('Paypal (Standard) payment method applied.','pie-register').'</p>';
				$data .= '<input type="hidden" name="select_payment_method" value="'.$payment_gateways[0].'" />';
			}
			
			return $data.$value;
		}
		
		function Add_payment_option($send_data,$paymeny_gatways) // Only For Paypal
		{
			$check_payment 	= get_option(OPTION_PIE_REGISTER);
			if($check_payment["enable_paypal"] == 1 && !(empty($check_payment['paypal_butt_id'])) && in_array('PaypalStandard', $paymeny_gatways))
			{
				$string = 'Paypal (Standard)';
				if( strpos($send_data, $string) == false )
				{
					$send_data .= '<option data-img="'.esc_url(PIEREG_PLUGIN_URL.'assets/images/paypal_std_btn.png').'" value="PaypalStandard">'.(__("Paypal (Standard)","pie-register")).'</option>';
				}
			}
			
			return $send_data;
		}
		
		function add_payment_method_script() // Only For Paypal Standard
		{
			$check_payment = get_option(OPTION_PIE_REGISTER);
			if($check_payment["enable_paypal"] == 1 && !(empty($check_payment['paypal_butt_id'])) )
			{
				//Add jQuery for payment Method
				?>
                	if(jQuery(this).val() == "PaypalStandard")
					{
						payment = '<div class="desc">Paypal (Standard) payment method applied.</div>';
						image = '<img src="'+jQuery('option:selected',jQuery(this)).attr('data-img')+'" />';
					}
				<?php 
			}
		}
		
		function add_select_payment_script(){
			?> 
			<script type="text/javascript">
				var piereg = jQuery.noConflict();
				piereg(document).ready(function(){
					piereg("#select_payment_method").change(function(){
						if(piereg(this).val() != "")
						{

							var payment = "", image = "";
							<?php do_action('add_payment_method_script'); ?>
							piereg("#show_payment_method").html(payment);
							piereg("#show_payment_method_image").html(image);
						}
						else
						{
							piereg("#show_payment_method").html("");
							piereg("#show_payment_method_image").html("");
						}
					});
				});
			</script>
			<?php
		}
		function get_payment_content_area()
		{
			$data = '<div class="fieldset">';
			//$data .= '<label for="select_payment_method"> </label>';
			$data .= '<div id="show_payment_method_image"></div>';
			$data .= '<div id="show_payment_method"></div>';
			$data .= '</div>';
			return $data;
		}
		function show_icon_payment_gateway() // for paypal
		{
			
			$button = get_option(OPTION_PIE_REGISTER);
			if(
			   		(!(empty($button['paypal_butt_id'])) && $button['enable_paypal']==1)															  					
				)
			{
				?>
				  <div class="fields_options submit_field">
				  	<img style="width:100%;" src="<?php echo esc_url(plugins_url('/assets/images/btn_buynowCC_LG.gif',__FILE__)); ?>" />
                  </div>
				<?php
			}
		}
		
		function renew_account()
		{ 
			global $errors;
			$errors = new WP_Error();
			
			if( isset($_POST['piereg_renew_account_nonce']) && wp_verify_nonce( $_POST['piereg_renew_account_nonce'], 'piereg_wp_renew_account_nonce' ) )
			{
				
				if( isset($_POST['select_payment_method']) and trim($_POST['select_payment_method']) != "" )
				{
					if(isset($_GET['auth'], $_GET['auth_key']) && !empty($_GET['auth']) && !empty($_GET['auth_key']) )
					{
						$user_name 	= sanitize_text_field(urldecode($_GET['auth']));
						$auth_key 	= sanitize_text_field(urldecode($_GET['auth_key']));
						$user_name 	= sanitize_user(urldecode(base64_decode($user_name)));
						$user 		= get_user_by("login",$user_name);
						
						if(!empty($user))
						{
							$auth_key = sanitize_text_field($_GET['auth_key']);
							$auth_key_hash = get_user_meta($user->ID,"pr_renew_account_hash", true);
							if(!empty($auth_key_hash))
							{
								if(trim($auth_key) == trim($auth_key_hash))
								{
									if(isset($user->ID)){
										/*
											*	Check Pricing
										*/
										$user_id = $user->ID;
										
										$pricing_key_number = get_user_meta( $user_id , "piereg_pricing_key_number" , true );
										$piereg_user_registered_form_id = get_user_meta( $user_id, "user_registered_form_id", true );
										$piereg_use_starting_period = get_user_meta( $user_id , "use_starting_period" , true );
										$piereg_form_pricing_fields = get_option("piereg_form_pricing_fields");
										$piereg_pricing_fields = "";
										$user_array = (array) $user;
										if(isset($piereg_form_pricing_fields['form_id_'.(intval($piereg_user_registered_form_id))])){
											$piereg_pricing_fields = $piereg_form_pricing_fields['form_id_'.(intval($piereg_user_registered_form_id))];
											$user_array['piereg_pricing']['pricing_key_user_role'] = $piereg_pricing_fields['role'][$pricing_key_number];
											if(empty($piereg_use_starting_period)){
												$user_array['piereg_pricing']['pricing_payment_amount'] = $piereg_pricing_fields['starting_price'][$pricing_key_number];
											}else{
												$user_array['piereg_pricing']['pricing_payment_amount'] = $piereg_pricing_fields['then_price'][$pricing_key_number];
											}
										}
										$user = (object) $user_array;
									}
								}
								else
								{
									$user = new WP_Error("invalid_hash","User hash mismatch");
								}
							}
							else{
								$user = new WP_Error("piereg_invalid_auth_keys_hash",apply_filters("piereg_invalid_auth_keys_hash",__("Invalid auth keys hash","pie-register")));
							}
						}
						else{
							$user = new WP_Error("piereg_invalid_auth_keys",apply_filters("piereg_invalid_auth_keys",__("Invalid User","pie-register")));
						}
					}
					else{
						$user = $this->piereg_user_login(sanitize_user($_POST['user_name']),$_POST['u_pass']);
						wp_logout();
					}
					
					$piereg = $this->get_pr_global_options();
					
					if ( is_wp_error($user))
					{
						$user_login_error = $user->get_error_message();
						if(strpos(strip_tags($user_login_error),'Invalid username',5) > 6){
							$user_login_error = '<b>'.ucwords(__("error","pie-register")).'</b>: '.__("Invalid username or password","pie-register");
						}
						else if(strpos(strip_tags($user_login_error),'password you entered',9) > 10){
							$user_login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.__("Invalid username or password","pie-register");
						}
						$errors->add('renew-account-error',apply_filters("piereg_renew_account_error",$user_login_error));
					}
					elseif($user->ID != 0 or $user->ID != ""){
						$user_meta = get_user_meta($user->ID);
						if($user_meta['active'][0] == 0){
							if(isset($_POST['select_payment_method']) and $_POST['select_payment_method'] != "" )//Goto payment method Like check_payment_method_paypal
							{
								$_POST['user_id'] = $user->data->ID;
								$_POST['e_mail'] = $user->data->user_email;
								$_POST['username'] = $user->data->user_login;
								$_POST['renew_account_msg'] = apply_filters("piereg_Renew_Account",__("Renew Account","pie-register"));
								$_POST['renew_account'] = "Renew Account";
								do_action("piereg_before_renew_account_hook",$user);
								do_action("check_payment_method_".sanitize_text_field($_POST['select_payment_method']),$user);
							}
						}else{
							$this->pie_post_array['success'] = apply_filters("you_are_already_active",__($piereg['payment_already_activate_msg'],"pie-register"));
						}
					}
					else{
						$this->pie_post_array['error'] = apply_filters("piereg_Invalid_Username_or_Password",__("Invalid Username or Password","pie-register"));
					}
				}
				else{
					$this->pie_post_array['error'] = apply_filters("piereg_Please_Select_any_payment_method",__("Please select a payment method","pie-register"));
				}
			} else {
				$this->pie_post_array['error'] = 'Invalid nonce.';
			}
		}
		
		function piereg_user_login($username,$password,$remember = false){
			$result = array();
			if($username != "" && $password != ""){
				$creds = array();
				$creds['user_login'] 	= trim($username);
				$creds['user_password'] = trim($password);
				$creds['remember'] 		= ((!empty($remember))?true:false);
				$piereg_secure_cookie = $this->PR_IS_SSL();
				if($this->piereg_authentication($_POST['log'],$_POST['pwd'])){
					$user = wp_signon( $creds, $piereg_secure_cookie);
					if ( !is_wp_error($user) ){
						$this->piereg_delete_authentication();
					}
				}else{
					$user = new WP_Error('piereg_authentication_failed', __("IP address blocked due to too many failed login attempts.","pie-register"));
				}
				
				if(isset($user->ID)){
					/*
						*	Check Pricing
					*/
					$user_id = $user->ID;
					
					$pricing_key_number 			= get_user_meta( $user_id, "piereg_pricing_key_number", true );
					$piereg_user_registered_form_id = get_user_meta( $user_id, "user_registered_form_id", true );
					$piereg_use_starting_period 	= get_user_meta( $user_id, "use_starting_period" , true );
					$piereg_form_pricing_fields 	= get_option("piereg_form_pricing_fields");
					$piereg_pricing_fields = "";
					$user_array = (array) $user;
					if(isset($piereg_form_pricing_fields['form_id_'.(intval($piereg_user_registered_form_id))])){
						$piereg_pricing_fields = $piereg_form_pricing_fields['form_id_'.(intval($piereg_user_registered_form_id))];
						$user_array['piereg_pricing']['pricing_key_user_role'] = $piereg_pricing_fields['role'][$pricing_key_number];
						if(empty($piereg_use_starting_period)){
							$user_array['piereg_pricing']['pricing_payment_amount'] = $piereg_pricing_fields['starting_price'][$pricing_key_number];
						}else{
							$user_array['piereg_pricing']['pricing_payment_amount'] = $piereg_pricing_fields['then_price'][$pricing_key_number];
						}
					}
					$user = (object) $user_array;
					
					/***** End Pricing *****/
				}
				$result = $user;
			}
			else{
				$result['error'] = apply_filters('piereg_Invalid_Fields', __("Invalid Field(s)",'pie-register')); # newlyAddedHookFilter
			}
			return $result;
		}
		function wp_mail_send($to_email = "",$key = "",$additional_msg = "",$msg = "",$email_variable = array())
		{
			global $errors;
			$errors = new WP_Error();
			if(trim($to_email) != "" and trim($key) != "" )
			{
				$email_types = get_option(OPTION_PIE_REGISTER);
				
				$message_temp = "";
				if($email_types['user_formate_email_'.$key] == "0"){
					$message_temp	= nl2br(strip_tags($email_types['user_message_email_'.$key]));
				}else{
					$message_temp	= $email_types['user_message_email_'.$key];
				}
				
				$message  		= $this->filterEmail($message_temp,$to_email,"","",$email_variable);
				$to				= $to_email;
				$from_name		= $email_types['user_from_name_'.$key];
				$from_email		= $email_types['user_from_email_'.$key];
				$reply_to_email	= $email_types['user_to_email_'.$key];
				$subject		= html_entity_decode($email_types['user_subject_email_'.$key],ENT_COMPAT,"UTF-8");
				$subject 		= $this->filterSubject($to_email,$subject);

				if(!filter_var($to,FILTER_VALIDATE_EMAIL))//if not valid email address then use wordpress default admin
				{
					$to = get_option('admin_email');
				}
				//Headers
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				
				if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
					$headers .= "From: ".$from_name." <".$from_email."> \r\n";
				
				if(!empty($reply_to_email) && filter_var($reply_to_email,FILTER_VALIDATE_EMAIL))//Validating Reply To
					$headers .= 'Reply-To: <'.$reply_to_email.'> \r\n';
				if($reply_to_email){
					$headers .= "Return-Path: {$reply_to_email}\r\n";
				}else{
					$headers .= "Return-Path: {$from_email}\r\n";
				}
				
				if(!wp_mail($to,$subject,$message,$headers))
				{
					$errors->add('check-error',apply_filters("piereg_problem_and_the_email_was_probably_not_sent",__("There was a problem sending the email.",'pie-register')));
				}
				else{
					if(trim($msg) != "")
					{
						$this->pie_post_array['success'] = __($msg,"pie-register");
					}
				}
			}
		}
		
		function delete_invitation_codes($ids="0")
		{
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			
			$array_ids 		= explode(',', $ids);
			$count_ids 		= count($array_ids);
			$placeholders 	= array_fill(0, $count_ids, '%d');
			$format 		= implode(', ', $placeholders);
			
			$sql = "DELETE FROM `$codetable` WHERE `id` IN($format)";
			if(!$wpdb->query( $wpdb->prepare($sql, $array_ids) )){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			else{
				$this->pie_post_array['notice'] = __("Invitation code deleted.","pie-register");
			}
		}
		function active_or_unactive_invitation_codes($ids="0",$status="1")
		{
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			
			$array_ids 		= explode(',', $ids);
			$count_ids 		= count($array_ids);
			$placeholders 	= array_fill(0, $count_ids, '%d');
			$format 		= implode(', ', $placeholders);
			
			$sql 	= "UPDATE `".$codetable."` SET `status`= %s WHERE `id` IN($format)";
			$args[] = $status;
			$args	= array_merge($args, $array_ids);
			
			if(!$wpdb->query( $wpdb->prepare($sql, $args) ) ){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			else{
				$this->pie_post_array['notice'] = __("Status has been changed","pie-register");
			}
		}
		function pireg_update_invitation_code_cb_url()
		{
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			$inv_code_id = intval($_POST['data']['id']);
			if( isset($_POST['data']))
			{
				if(trim($_POST['data']['id']) != "" and trim($_POST['data']['value']) != "" and trim($_POST['data']['type']) != "")
				{
					global $wpdb;
					$sql ="";
					$_data_value = sanitize_text_field($_POST['data']['value']);
					if(trim($_POST['data']['type']) == "name")
					{
						$sql_res_sel = $wpdb->get_var( $wpdb->prepare( "SELECT `name` FROM `{$codetable}` WHERE BINARY `name` = %s OR `name` = %s", strtolower($_data_value), strtoupper($_data_value)) );
						if(!$sql_res_sel)
							$sql = "UPDATE `{$codetable}` SET `name`=%s WHERE `id` = '{$inv_code_id}'";
						else{
							echo "duplicate";
							die();
						}
					}
					else if(trim($_POST['data']['type']) == "code_usage")
					{
						if(is_numeric($_data_value) && $_data_value >= 0  && trim($_data_value) != ''){
							$sql = "SELECT `code_usage`,`count` FROM `{$codetable}` WHERE `id` = ".((int)$_POST['data']['id'])."";
							$usage_count = $wpdb->get_results( $sql );
							if($_data_value >= $usage_count[0]->count){
								$sql = "UPDATE `{$codetable}` SET `code_usage`=%s WHERE `id` = ".((int)$_POST['data']['id'])."";
							}else{
								echo "invalid usage";
								die();
							}
						}
					}
					$result = $wpdb->query( $wpdb->prepare($sql, $_data_value) );
					
					if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
						$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
					}
					if($result)
					{
						echo "done";
					}
					else{
						echo "error";
					}
				}
			}
			die();
		}

		function delete_custom_roles($ids="0")
		{
			global $wpdb;
			$custom_role_table_name = $wpdb->prefix."pieregister_custom_user_roles";

			$array_ids 		= explode(',', $ids);
			$count_ids 		= count($array_ids);
			$placeholders 	= array_fill(0, $count_ids, '%d');
			$format 		= implode(', ', $placeholders);
			
			$sql_data = "SELECT `role_key` FROM `$custom_role_table_name` WHERE `id` IN($format)";
			$role_keys = $wpdb->get_results($wpdb->prepare($sql_data, $array_ids));
			

			$sql = "DELETE FROM `$custom_role_table_name` WHERE `id` IN($format)";
			if(!$wpdb->query( $wpdb->prepare($sql, $array_ids) )){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}else{
				$this->pie_post_array['notice'] = __("The User Role(s) has been deleted","pie-register");
				
				foreach($role_keys as $role_key){
					remove_role( $role_key->role_key);
				}
			}
		}
		
		function checkUserAllowedPassReset($val,$userid){	
			if(!$userid) return false;
			//Check if the user is active or not
			//if active true, or false
			global $piereg_global_options;
			
			if(
			   (isset($piereg_global_options['verification']) && ($piereg_global_options['verification'] == "2" || $piereg_global_options['verification'] == "1")) ||
			   ((!empty($piereg_global_options['paypal_butt_id'])) && $piereg_global_options['enable_paypal'] == "1" )
			   ){
				$user_active_status = get_user_meta($userid,"active",true);
				//If employee register from wp-register the active meta is not saved
				if($user_active_status == "")
					return true;
				//If employee register from wp-register the active meta is not saved
				return (($user_active_status == 1)?true:false);
			}
			return true;
		}
				
		function piereg_password_reset_not_allowed_text_function($text)
		{
			return $text;
		}
		
		/*
			*	Pie Register log file download
		*/
		function pr_logfile_download_or_view(){
			if(isset($_POST['piereg_download_log_file']) && $_POST['piereg_download_log_file'] != ""){
				$this->piereg_get_wp_plugable_file(true); // require_once pluggable.php
				if(isset($_POST['piereg_download_log_file_nonce']) && wp_verify_nonce( $_POST['piereg_download_log_file_nonce'], 'piereg_wp_download_log_file_nonce' ))
				{
					if (file_exists(PIE_LOG_FILE."/piereg_log.log")) {
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename='.("PieRegister_logfile_".date_i18n("Y-m-d-H-i-s").".log"));
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize(PIE_LOG_FILE."/piereg_log.log"));
						readfile(PIE_LOG_FILE."/piereg_log.log");
						exit;
					}
				}else{
					$this->pie_post_array['error_message'] = __("Invalid nonce.","pie-register");
				}
			}
		}
		/*
			*	Pie Register Log File View 
		*/
		function piereg_get_log_file(){
			//Log File Dir
			$file_dir = PIE_LOG_FILE."/piereg_log.log";
			$result = "";
			$logFileData = $this->read_upload_file($file_dir);
			$result = htmlentities($logFileData);
			return $result;
		}
		
		function check_json_file_db_version($array_json_file){
			if(isset($array_json_file['piereg_plugin_db_version']) && !empty($array_json_file['piereg_plugin_db_version'])){
				$import_file_db_version = $array_json_file['piereg_plugin_db_version'];
				$pie_plugin_db_version = get_option('piereg_plugin_db_version');
				if($pie_plugin_db_version === $import_file_db_version)
					return true;
				else
					return false;
			}else
				return false;
		}
		function set_json_header($file_name){
			header('Content-disposition: attachment; filename='.($file_name).'.json');
			header('Content-type: application/json');
		}
		
		
		function piereg_plugin_row_meta( $links, $file ) {
			if ( $file == PIEREG_PLUGIN_BASENAME ) {
				$row_meta = array(
					'documentation'		=>	'<a href="' . esc_url( apply_filters( 'pieregister_docs_url', 'https://pieregister.com/documentation/' ) ) . '" title="' . esc_attr( __( 'Please refer to the Pie Register user manual.', 'pie-register' ) ) . '" target="_blank">' . __( 'Documentation', 'pie-register' ) . '</a>',
					'support'	=>	'<a href="' . esc_url( apply_filters( 'pieregister_support_url', 'https://wordpress.org/support/plugin/pie-register/' ) ) . '" title="' . esc_attr( __( 'Please visit the support forum.', 'pie-register' ) ) . '" target="_blank">' . __( 'Support', 'pie-register' ) . '</a>',
				);				
				
				return array_merge( $links, $row_meta );
			}
	
			return (array) $links;
		}
		
		function activate_addon_license_key(){
			
			if( isset($_POST['piereg_activate_addon_license_key'], $_POST['save_addon_license_key_settings']) )
			{
				if(isset($_POST['piereg_addon_license_key_nonce']))
				{
					if(wp_verify_nonce( $_POST['piereg_addon_license_key_nonce'], 'piereg_wp_addon_license_key_nonce' )){
						
						$piereg_pro_not_activated	= ( isset($_POST['piereg_pro_not_activated']) ) ? true: false; 
						$LK_options 				= array();
							
						if( $piereg_pro_not_activated )
						{
							$LK_options['api_key']			= sanitize_text_field($_POST['piereg_addon_license_key']);
							$LK_options['activation_email']	= sanitize_email($_POST['piereg_addon_license_email']);	
						}
						else {
							$LK_options = get_option( PIEREG_LICENSE_KEY_OPTION );
						}
												
						$piereg_api_manager_menu 	= new Piereg_API_Manager_Example_MENU();
						$piereg_api_manager_menu->validate_addon_options( array("api_key"=> $LK_options['api_key'], "activation_email"=> $LK_options['activation_email'], "api_addon"=> sanitize_text_field($_POST['is_activate_addon_license']), "api_addon_version"=> sanitize_text_field($_POST['addon_software_version']), "is_piereg_pro_inactive"=> $piereg_pro_not_activated ) );
													
					}					
					else{
						$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
					}
				}
			}
		}
		public function plugin_url() {
			if ( isset( $this->piereg_plugin_url ) ) return $this->piereg_plugin_url;
			return $this->piereg_plugin_url = plugins_url( '/', __FILE__ );
		}
		
		function pie_user_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
			$user 			= false;
			$profile_pic	= "";
			
			if( !is_admin() )
			{
				$current_user 			= wp_get_current_user();
				
				if( is_object($id_or_email) )
					$id_or_email = $id_or_email->user_id;
					
				if( !is_numeric( $id_or_email ) ) {
					$user = get_user_by('email', $id_or_email);
					$id_or_email = $user->ID;
				}

				if( is_numeric( $id_or_email ) && $id_or_email != $current_user->ID )
				{
					$current_user 			= get_userdata($id_or_email);
				}
				
				$profile_pic_array 		= get_user_meta($current_user->ID);
				
				if( is_array($profile_pic_array) )
				{
					foreach($profile_pic_array as $key => $val) {
						if(strpos($key,'profile_pic') !== false) {
							$profile_pic = trim($val[0]);
						}
					}
				}
			}
			
			if(!empty( $profile_pic ))
			{
				$profile_pic 			= apply_filters("piereg_profile_image_url",$profile_pic,$current_user);			
				if ( is_numeric( $id_or_email ) ) {
			
					$id = (int) $id_or_email;
					$user = get_user_by( 'id' , $id );
			
				} elseif ( is_object( $id_or_email ) ) {
			
					if ( ! empty( $id_or_email->user_id ) ) {
						$id = (int) $id_or_email->user_id;
						$user = get_user_by( 'id' , $id );
					}
			
				} else {
					$user = get_user_by( 'email', $id_or_email );	
				}
			
				if ( $user && is_object( $user ) ) {
			
					//if ( $user->data->ID == get_current_user_id() ) {
						$avatar = $profile_pic;
						$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
					//}
			
				}
			
			}
			
			return $avatar;
		}
		
		function is_anyone_can_register(){
			return get_option("users_can_register");
		}
		function piereg_ssl_template_redirect(){
			if ( !is_admin() && ( (defined("FORCE_SSL_ADMIN") && FORCE_SSL_ADMIN == true) && !is_ssl() ) ) { #checkbyM
				if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
					wp_safe_redirect( preg_replace('|^http://|', 'https://', $this->piereg_get_current_url()) );
					exit;
				}
			}
		}
		function edit_email_verification(){
			if(isset($_GET['action']) && ($_GET['action'] == "current_email_verify" || $_GET['action'] == "email_edit")){
				$username 			= sanitize_user($_GET['login']);
				$email_verify_key 	= sanitize_text_field($_GET['key']);
				$user_data_temp 	= get_user_by('login', $username);
				global $errors, $piereg_global_options;
				$errors = new WP_Error();
				$global_options = $piereg_global_options;
				$success_message = "";
				$type = "success";
				if( ($_GET['action'] == "current_email_verify" ) && (isset($_GET['key']) && !empty($_GET['key']) ) && (isset($_GET['login']) && !empty($_GET['login']) ))
				{
					$email_verify_orignal_key = get_user_meta($user_data_temp->data->ID,"new_email_address_hashed",true);
					
					if($email_verify_orignal_key == $email_verify_key)
					{
						$new_email_address = get_user_meta($user_data_temp->data->ID,"new_email_address",true);
						$email_key = md5(uniqid("piereg_").time());
						$keys_array = array("reset_email_key"=>$email_key);
						
						/*
							*	Email send snipt
						*/
						$subject		= html_entity_decode($global_options["user_subject_email_email_edit_verification"],ENT_COMPAT,"UTF-8");
						$subject 		= $this->filterSubject($user_data_temp,$subject);
						$message_temp 	= "";
						
						$message_temp	= $global_options["user_message_email_email_edit_verification"];						
						$message		= $this->filterEmail($message_temp,$user_data_temp, "",false,$keys_array );
						$from_name		= $global_options["user_from_name_email_edit_verification"];
						$from_email		= $global_options["user_from_email_email_edit_verification"];					
						$reply_email 	= $global_options["user_to_email_email_edit_verification"];
						
						//Headers
						$headers  = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
					
						if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From
						$headers .= "From: ".$from_name." <".$from_email."> \r\n";
						
						if($reply_email){
							$headers .= "Reply-To: {$reply_email}\r\n";
							$headers .= "Return-Path: {$reply_email}\r\n";
						}else{
							$headers .= "Reply-To: {$from_email}\r\n";
							$headers .= "Return-Path: {$from_email}\r\n";
						}
						
						if((isset($global_options['user_enable_email_edit_verification']) && $global_options['user_enable_email_edit_verification'] == 1) && !wp_mail($new_email_address, $subject, $message , $headers))
						{
							$this->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
							$print_message = __("There was a problem sending the email.",'pie-register');
							$type = "0";
						}
						
						/*
							*	Update Email Hash Key
						*/
						update_user_meta($user_data_temp->data->ID,"new_email_address_hashed",$email_key);
						if(empty($print_message)){
							$print_message = __("Use the link sent to your email to verify and apply the change.","pie-register");
							$type="success";
						}
					}else{
						$print_message = __('Verification link has expired or is invalid.','pie-register' );
						$type = "error";
					}
				}
				elseif( ($_GET['action'] == "email_edit" ) && (isset($_GET['key']) && !empty($_GET['key']) ) && (isset($_GET['login']) && !empty($_GET['login']) ))
				{
					$email_verify_orignal_key = get_user_meta($user_data_temp->data->ID,"new_email_address_hashed",true);
					$old_email = get_user_meta($user_data_temp->data->ID,"new_email_address",true);
					if($email_verify_orignal_key == $email_verify_key)
					{
						$user_id_temp = wp_update_user( array( 'ID' => $user_data_temp->data->ID, 'user_email' => $old_email ) );
						if ( is_wp_error( $user_id_temp ) ) {
							$print_message = __('There was a problem updating the new Email address, try again.','pie-register' );
							$type = "error";
						}
						else{
							delete_user_meta($user_data_temp->data->ID,"new_email_address_hashed");
							delete_user_meta($user_data_temp->data->ID,"new_email_address");
							$print_message = __("New email address has been verified. Account email address has been changed.","pie-register");
							$type = "success";
						}
					}
					else{
						$print_message = __('Verification link has expired or is invalid.','pie-register' );
						$type = "error";
					}
				}
				
				if( is_user_logged_in() ){
					$print_message = base64_encode($print_message);
					wp_safe_redirect($this->get_page_uri($global_options["alternate_profilepage"],"edit_user=1&pr_msg={$print_message}&type={$type}"));
					exit;
				}else{
					$_POST[$type] = $print_message;
				}
			}
		}
		/*
			* PR Rated
		 */
		public function pieregister_rated(){
			update_option( 'pie_admin_footer_text_rated', 1 );
			wp_send_json_success();
		}
		
		/*
			*	Payment log file Download & Delete
		*/
		function piereg_payment_log_file_action(){
			if( !is_admin() )
				return false;
				
			if( isset($_POST['piereg_download_payment_log_file']) && isset($_POST['piereg_payment_log']) && wp_verify_nonce( $_POST['piereg_payment_log'], 'piereg_wp_payment_log' ) ){
					$this->piereg_get_wp_plugable_file(); 
					$read_log_file = $this->read_upload_file(PIE_LOG_FILE."/payment-log.log");
					if( !empty($read_log_file) ){
						// Send file headers
						header('Content-disposition: attachment; filename=payment-log-'.date_i18n("d-m-y-H-i-s").'.log');
						header('Content-type: application/text');
						// Send the file contents.
						echo $read_log_file;
						exit;
					}else{
						$this->pie_post_array['error'] = __("Empty Payment Log File","pie-register");
					}
			}elseif( isset($_POST['piereg_delete_payment_log_file']) && isset($_POST['piereg_payment_log']) && wp_verify_nonce( $_POST['piereg_payment_log'], 'piereg_wp_payment_log' ) ){
					$this->piereg_get_wp_plugable_file(); 
					update_option( "piereg_payment_log_option", array() );
					$this->pie_post_array['notice'] = __("Payment log successfully removed.","pie-register");
				
			}
			else if( isset($_POST['piereg_download_payment_log_file']) || isset($_POST['piereg_delete_payment_log_file']) ) {
				$this->pie_post_array['error'] = __("Invalid nonce.","pie-register");
			}
		}
	}
}

if( class_exists('PieRegister') ){
	$pie_register = new PieRegister();
	$GLOBALS['piereg_api_manager'] = $pie_register;
	if(isset($pie_register)){
		register_activation_hook( __FILE__, array(  &$pie_register, 'install_settings' ) );
		register_deactivation_hook( __FILE__, array(  &$pie_register, 'deactivation_settings' ) );
		
		if (!function_exists("pie_registration_url")) 
		{
			function pie_registration_url($url=false) 
			{
				return PieRegister::static_pie_registration_url($url);
			}
		}
		
		if (!function_exists("pie_login_url")) 
		{
			function pie_login_url($url=false) 
			{
				return PieRegister::static_pie_login_url($url);
			}
		}
		
		if (!function_exists("pie_lostpassword_url")) 
		{
			function pie_lostpassword_url($url=false)
			{
				return PieRegister::static_pie_lostpassword_url($url);
			}
		}
		
		if (!function_exists("piereg_logout_url")) 
		{	
			function piereg_logout_url($url=false)
			{
				return PieRegister::static_piereg_logout_url($url);
			}
		}
		
		if (!function_exists("pie_modify_custom_url"))
		{	
			function pie_modify_custom_url($url,$query_string=false)
			{
				return PieRegister::static_pie_modify_custom_url($url,$query_string);
			}
		}
		
		if (!function_exists("set_pr_stats")) 
		{
			function set_pr_stats($stats,$type)
			{
				return PieRegister::static_set_pr_stats($stats,$type);
			}
		}
	}
	
	if (!function_exists("uninstall_pr_ff"))
	{
		function uninstall_pr_ff() {
			global $pie_register;			
			if(!is_object($pie_register)) {
				$pie_register = new PieRegister();
			}			
			$pie_register->uninstall_settings();
		}
	}
	register_uninstall_hook( __FILE__, 'uninstall_pr_ff' ); 
}