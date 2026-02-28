<?php
include("base_variables.php");
if( !class_exists('PieReg_Base') ){
	class PieReg_Base extends PieRegisterBaseVariables
	{
		/*
			*	Move Variable PieReg_Base to PieRegisterBaseVariables (01-09-2014)
		*/
		
		function __construct()
		{
			/*
				*	Execute Parent construct
			*/
			parent::__construct();
			
			$this->plugin_dir = dirname(dirname(__FILE__));
			$this->plugin_url = plugins_url() .'/'. basename(dirname(dirname(__FILE__))) .'/';			
			$this->admin_path = $this->pie_get_admin_path();
		}
		
		/*
			*	GET PR_GLOBAL_OPTIONS
			*	return PR global option
			*	
		*/
		function get_pr_global_options($option_name = NULL){
			switch($option_name){
				case OPTION_PIE_REGISTER:
					global $PR_GLOBAL_OPTIONS;
					$options = $PR_GLOBAL_OPTIONS;
				break;
				default:
					global $PR_GLOBAL_OPTIONS;
					$options = $PR_GLOBAL_OPTIONS;
				break;
			}
			return $options;
		}
		/*
			*	set PR global option and return true and false
		*/
		function set_pr_global_options($value, $option_name = NULL){
			switch($option_name){
				case OPTION_PIE_REGISTER:
					if(!empty($value))
					{
						global $PR_GLOBAL_OPTIONS;
						$PR_GLOBAL_OPTIONS = $value;
					}
				break;
				default:
					return false;
				break;
			}
			return true;
		}
		
		function getPieMeta()
		{
			global $wpdb;
			$this->user_table		= $wpdb->prefix . "users";
			$this->user_meta_table 	= $wpdb->prefix . "usermeta";
			$result	 = $wpdb->get_results( $wpdb->prepare("SELECT distinct(meta_key) FROM $this->user_meta_table WHERE `meta_key` like %s", 'pie_%') ); #WPS	
			if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			if(sizeof($result) > 0)
			{
				return $result;
			}
			return false;
		}
		function replaceMetaKeys($text,$user_id)
		{
			if($result = $this->getPieMeta())
			{	
				foreach($result as $meta)
				{
					$key 		= "%".$meta->meta_key."%";
					$value		= get_user_meta($user_id, $meta->meta_key, true );
					$get_value = "";
					if(is_array($value)){
						foreach($value as $val){
							if(is_array($val)){
								if(array_filter($val))
								$get_value .= implode(", ",$val)."<br />";
							}else{
								$get_value = (is_array($value))? implode(", ",$value) : $value;
							}
						}
					}else{
						$get_value .= (!empty($value))? $value : "";
					}
					$text		= str_replace($key,$get_value,$text);
				}
			}
			return $text;
		}
		function getCurrentFields($id="")
		{
			if(((int)$id) != 0 and $id != "" )
			{
				$data 	= get_option("piereg_form_fields_".((int)$id));
			}
			else if(isset($_GET['form_id']) and ((int)$_GET['form_id']) != 0 and isset($_GET['form_name'])){
				
				$data 	= get_option("piereg_form_fields_".((int)$_GET['form_id']));
			}
			
			else{
				$data 	= get_option("pie_fields_default");
			}
			
			$data 	= maybe_unserialize($data );				
			
			if(!$data)
			{
				return false;		
			}
			return $data;			
		}
		
		function update_email_template_unverified_grace(){
			
			// Update Email Types
			$pie_user_email_types 								= get_option('pie_user_email_types');
			$pie_user_email_types['user_perm_blocked_notice']	= __("Notice: User removed because email address was not verfied.","pie-register");
			update_option("pie_user_email_types", $pie_user_email_types);
			
			
			$update_options								= get_option(OPTION_PIE_REGISTER);
			foreach ($pie_user_email_types as $val=>$type) 
			{
				if( $val == 'user_perm_blocked_notice' )
				{
					$update_options['user_subject_email_'.$val] = $type;
				}
			}
			
			// Update Email Template
			$update_options['user_message_email_user_perm_blocked_notice']	= '<p>Dear %user_login%,</p><p>You were required to verify your account, but you failed to do so within grace period. So, your account is being removed from %blogname%. You need to register again. </p><p>Kind Regards,</p><p>Team %blogname%</p>';
			update_option(OPTION_PIE_REGISTER, $update_options);
			
			
			update_option('pie_email_template_updated_blocked_unverified_users', 'yes');
		}
		
		function updated_countries_list()
		{
			$country = array(__("Afghanistan","pie-register"),__("Albania","pie-register"),__("Algeria","pie-register"),__("American Samoa","pie-register"),__("Andorra","pie-register"),__("Angola","pie-register"),__("Antigua and Barbuda","pie-register"),__("Argentina","pie-register"),__("Armenia","pie-register"),__("Australia","pie-register"),__("Austria","pie-register"),__("Azerbaijan","pie-register"),__("Bahamas","pie-register"),__("Bahrain","pie-register"),__("Bangladesh","pie-register"),__("Barbados","pie-register"),__("Belarus","pie-register"),__("Belgium","pie-register"),__("Belize","pie-register"),__("Benin","pie-register"),__("Bermuda","pie-register"),__("Bhutan","pie-register"),__("Bolivia","pie-register"),__("Bosnia and Herzegovina","pie-register"),__("Botswana","pie-register"),__("Brazil","pie-register"),__("Brunei","pie-register"),__("Bulgaria","pie-register"),__("Burkina Faso","pie-register"),__("Burundi","pie-register"),__("Cambodia","pie-register"),__("Cameroon","pie-register"),__("Canada","pie-register"),__("Cape Verde","pie-register"),__("Central African Republic","pie-register"),__("Chad","pie-register"),__("Chile","pie-register"),__("China","pie-register"),__("Colombia","pie-register"),__("Comoros","pie-register"),__("Democratic Republic of the Congo","pie-register"),__("Republic of theCongo","pie-register"),__("Costa Rica","pie-register"),__("Côte d'Ivoire","pie-register"),__("Croatia","pie-register"),__("Cuba","pie-register"),__("Cyprus","pie-register"),__("Czech Republic","pie-register"),__("Denmark","pie-register"),__("Djibouti","pie-register"),__("Dominica","pie-register"),__("Dominican Republic","pie-register"),__("East Timor","pie-register"),__("Ecuador","pie-register"),__("Egypt","pie-register"),__("El Salvador","pie-register"),__("Equatorial Guinea","pie-register"),__("Eritrea","pie-register"),__("Estonia","pie-register"),__("Eswatini","pie-register"),__("Ethiopia","pie-register"),__("Fiji","pie-register"),__("Finland","pie-register"),__("France","pie-register"),__("Gabon","pie-register"),__("Gambia","pie-register"),__("Georgia","pie-register"),__("Germany","pie-register"),__("Ghana","pie-register"),__("Greece","pie-register"),__("Greenland","pie-register"),__("Grenada","pie-register"),__("Guam","pie-register"),__("Guatemala","pie-register"),__("Guinea","pie-register"),__("Guinea-Bissau","pie-register"),__("Guyana","pie-register"),__("Haiti","pie-register"),__("Honduras","pie-register"),__("Hong Kong","pie-register"),__("Hungary","pie-register"),__("Iceland","pie-register"),__("India","pie-register"),__("Indonesia","pie-register"),__("Iran","pie-register"),__("Iraq","pie-register"),__("Ireland","pie-register"),__("Israel","pie-register"),__("Italy","pie-register"),__("Jamaica","pie-register"),__("Japan","pie-register"),__("Jordan","pie-register"),__("Kazakhstan","pie-register"),__("Kenya","pie-register"),__("Kiribati","pie-register"),__("North Korea","pie-register"),__("South Korea","pie-register"),__("Kuwait","pie-register"),__("Kyrgyzstan","pie-register"),__("Laos","pie-register"),__("Latvia","pie-register"),__("Lebanon","pie-register"),__("Lesotho","pie-register"),__("Liberia","pie-register"),__("Libya","pie-register"),__("Liechtenstein","pie-register"),__("Lithuania","pie-register"),__("Luxembourg","pie-register"),__("Madagascar","pie-register"),__("Malawi","pie-register"),__("Malaysia","pie-register"),__("Maldives","pie-register"),__("Mali","pie-register"),__("Malta","pie-register"),__("Marshall Islands","pie-register"),__("Mauritania","pie-register"),__("Mauritius","pie-register"),__("Mexico","pie-register"),__("Micronesia","pie-register"),__("Moldova","pie-register"),__("Monaco","pie-register"),__("Mongolia","pie-register"),__("Montenegro","pie-register"),__("Morocco","pie-register"),__("Mozambique","pie-register"),__("Myanmar","pie-register"),__("Namibia","pie-register"),__("Nauru","pie-register"),__("Nepal","pie-register"),__("Netherlands","pie-register"),__("New Zealand","pie-register"),__("Nicaragua","pie-register"),__("Niger","pie-register"),__("Nigeria","pie-register"),__("Norway","pie-register"),__("Northern Mariana Islands","pie-register"),__("Oman","pie-register"),__("Pakistan","pie-register"),__("Palau","pie-register"),__("Palestine","pie-register"),__("Panama","pie-register"),__("Papua New Guinea","pie-register"),__("Paraguay","pie-register"),__("Peru","pie-register"),__("Philippines","pie-register"),__("Poland","pie-register"),__("Portugal","pie-register"),__("Puerto Rico","pie-register"),__("Qatar","pie-register"),__("Republic of North Macedonia","pie-register"),__("Romania","pie-register"),__("Russia","pie-register"),__("Rwanda","pie-register"),__("Saint Kitts and Nevis","pie-register"),__("Saint Lucia","pie-register"),__("Saint Vincent and the Grenadines","pie-register"),__("Samoa","pie-register"),__("San Marino","pie-register"),__("Sao Tome and Principe","pie-register"),__("Saudi Arabia","pie-register"),__("Senegal","pie-register"),__("Serbia","pie-register"),__("Seychelles","pie-register"),__("Sierra Leone","pie-register"),__("Singapore","pie-register"),__("Slovakia","pie-register"),__("Slovenia","pie-register"),__("Solomon Islands","pie-register"),__("Somalia","pie-register"),__("South Africa","pie-register"),__("Spain","pie-register"),__("Sri Lanka","pie-register"),__("Sudan","pie-register"),__("Sudan, South","pie-register"),__("Suriname","pie-register"),__("Sweden","pie-register"),__("Switzerland","pie-register"),__("Syria","pie-register"),__("Taiwan","pie-register"),__("Tajikistan","pie-register"),__("Tanzania","pie-register"),__("Thailand","pie-register"),__("Togo","pie-register"),__("Tonga","pie-register"),__("Trinidad and Tobago","pie-register"),__("Tunisia","pie-register"),__("Turkey","pie-register"),__("Turkmenistan","pie-register"),__("Tuvalu","pie-register"),__("Uganda","pie-register"),__("Ukraine","pie-register"),__("United Arab Emirates","pie-register"),__("United Kingdom","pie-register"),__("United States","pie-register"),__("Uruguay","pie-register"),__("Uzbekistan","pie-register"),__("Vanuatu","pie-register"),__("Vatican City","pie-register"),__("Venezuela","pie-register"),__("Vietnam","pie-register"),__("Virgin Islands, British","pie-register"),__("Virgin Islands, U.S.","pie-register"),__("Yemen","pie-register"),__("Zambia","pie-register"),__("Zimbabwe","pie-register"));
			update_option("pie_countries",$country);
			update_option("pie_countries_v352","yes");
		}
		function install_settings()
		{
			$this->activate_pieregister_license_key();
			
			/*Get old settings from options*/
			$old_options = get_option("pie_register_2");
			$new_options = get_option(OPTION_PIE_REGISTER);
			
			if($new_options == "" and $old_options != "")
			{
				update_option(OPTION_PIE_REGISTER,$old_options);
				unset($old_options);
				unset($new_options);
			}
						
			//Alternate Pages
			$get_pie_pages_from_db		= get_option("pie_pages");
			$piereg_registration_create_new_page = false;
			if(is_array($get_pie_pages_from_db))
			{
				$piereg_login 		= (isset($get_pie_pages_from_db[0]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[0]) : false;
				$piereg_registrtion = (isset($get_pie_pages_from_db[1]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[1]) : false;
				$piereg_forgot_pass = (isset($get_pie_pages_from_db[2]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[2]) : false;
				$piereg_profile 	= (isset($get_pie_pages_from_db[3]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[3]) : false;
			}
			else
			{
				$piereg_login 		= $piereg_registrtion = $piereg_forgot_pass = $piereg_profile = false;
			}
			
			$pie_pages = get_option("pie_pages");
			
			if(($piereg_login) === false )//Login
			{
				$_p = array();
				$_p['post_title'] 		= __("Login","pie-register");
				$_p['post_content'] 	= "[pie_register_login]";
				$_p['post_status'] 		= 'publish';
				$_p['post_type'] 		= 'page';
				$_p['comment_status'] 	= 'closed';
				$_p['ping_status'] 		= 'closed';
				$login_page_id 			= wp_insert_post( $_p );
				$pie_pages[0]			= $login_page_id;
			}
			
			if(($piereg_registrtion) === false )//Registration
			{
				$_p = array();
				$_p['post_title'] 		= __("Registration","pie-register");
				$_p['post_content'] 	= '[pie_register_form id="0" title="true" description="true" ]';
				$_p['post_status'] 		= 'publish';
				$_p['post_type'] 		= 'page';
				$_p['comment_status'] 	= 'closed';
				$_p['ping_status'] 		= 'closed';
				$reg_page_id 			= wp_insert_post( $_p );
				$pie_pages[1]			= $reg_page_id;
				$piereg_registration_create_new_page = true;
			}
				
			if(($piereg_forgot_pass) === false )//Forgot Password
			{
				$_p = array();
				$_p['post_title'] 		= __("Forgot Password","pie-register");
				$_p['post_content'] 	= "[pie_register_forgot_password]";
				$_p['post_status'] 		= 'publish';
				$_p['post_type'] 		= 'page';
				$_p['comment_status'] 	= 'closed';
				$_p['ping_status'] 		= 'closed';
				$forPas_page_id	 		= wp_insert_post( $_p );
				$pie_pages[2]			= $forPas_page_id;
			}
			
			if(($piereg_profile) === false )//Profile Page
			{
				$_p = array();
				$_p['post_title'] 		= __("Profile","pie-register");
				$_p['post_content'] 	= "[pie_register_profile]";
				$_p['post_status'] 		= 'publish';
				$_p['post_type'] 		= 'page';
				$_p['comment_status'] 	= 'closed';
				$_p['ping_status'] 		= 'closed';
				$Profile_page_id 		= wp_insert_post( $_p );
				$pie_pages[3]			= $Profile_page_id;
				update_option("Profile_page_id",$Profile_page_id);
			}
			update_option("pie_pages",$pie_pages);
			
			//Countries
			$country = array(__("Afghanistan","pie-register"),__("Albania","pie-register"),__("Algeria","pie-register"),__("American Samoa","pie-register"),__("Andorra","pie-register"),__("Angola","pie-register"),__("Antigua and Barbuda","pie-register"),__("Argentina","pie-register"),__("Armenia","pie-register"),__("Australia","pie-register"),__("Austria","pie-register"),__("Azerbaijan","pie-register"),__("Bahamas","pie-register"),__("Bahrain","pie-register"),__("Bangladesh","pie-register"),__("Barbados","pie-register"),__("Belarus","pie-register"),__("Belgium","pie-register"),__("Belize","pie-register"),__("Benin","pie-register"),__("Bermuda","pie-register"),__("Bhutan","pie-register"),__("Bolivia","pie-register"),__("Bosnia and Herzegovina","pie-register"),__("Botswana","pie-register"),__("Brazil","pie-register"),__("Brunei","pie-register"),__("Bulgaria","pie-register"),__("Burkina Faso","pie-register"),__("Burundi","pie-register"),__("Cambodia","pie-register"),__("Cameroon","pie-register"),__("Canada","pie-register"),__("Cape Verde","pie-register"),__("Central African Republic","pie-register"),__("Chad","pie-register"),__("Chile","pie-register"),__("China","pie-register"),__("Colombia","pie-register"),__("Comoros","pie-register"),__("Democratic Republic of the Congo","pie-register"),__("Republic of theCongo","pie-register"),__("Costa Rica","pie-register"),__("Côte d'Ivoire","pie-register"),__("Croatia","pie-register"),__("Cuba","pie-register"),__("Cyprus","pie-register"),__("Czech Republic","pie-register"),__("Denmark","pie-register"),__("Djibouti","pie-register"),__("Dominica","pie-register"),__("Dominican Republic","pie-register"),__("East Timor","pie-register"),__("Ecuador","pie-register"),__("Egypt","pie-register"),__("El Salvador","pie-register"),__("Equatorial Guinea","pie-register"),__("Eritrea","pie-register"),__("Estonia","pie-register"),__("Eswatini","pie-register"),__("Ethiopia","pie-register"),__("Fiji","pie-register"),__("Finland","pie-register"),__("France","pie-register"),__("Gabon","pie-register"),__("Gambia","pie-register"),__("Georgia","pie-register"),__("Germany","pie-register"),__("Ghana","pie-register"),__("Greece","pie-register"),__("Greenland","pie-register"),__("Grenada","pie-register"),__("Guam","pie-register"),__("Guatemala","pie-register"),__("Guinea","pie-register"),__("Guinea-Bissau","pie-register"),__("Guyana","pie-register"),__("Haiti","pie-register"),__("Honduras","pie-register"),__("Hong Kong","pie-register"),__("Hungary","pie-register"),__("Iceland","pie-register"),__("India","pie-register"),__("Indonesia","pie-register"),__("Iran","pie-register"),__("Iraq","pie-register"),__("Ireland","pie-register"),__("Israel","pie-register"),__("Italy","pie-register"),__("Jamaica","pie-register"),__("Japan","pie-register"),__("Jordan","pie-register"),__("Kazakhstan","pie-register"),__("Kenya","pie-register"),__("Kiribati","pie-register"),__("North Korea","pie-register"),__("South Korea","pie-register"),__("Kuwait","pie-register"),__("Kyrgyzstan","pie-register"),__("Laos","pie-register"),__("Latvia","pie-register"),__("Lebanon","pie-register"),__("Lesotho","pie-register"),__("Liberia","pie-register"),__("Libya","pie-register"),__("Liechtenstein","pie-register"),__("Lithuania","pie-register"),__("Luxembourg","pie-register"),__("Madagascar","pie-register"),__("Malawi","pie-register"),__("Malaysia","pie-register"),__("Maldives","pie-register"),__("Mali","pie-register"),__("Malta","pie-register"),__("Marshall Islands","pie-register"),__("Mauritania","pie-register"),__("Mauritius","pie-register"),__("Mexico","pie-register"),__("Micronesia","pie-register"),__("Moldova","pie-register"),__("Monaco","pie-register"),__("Mongolia","pie-register"),__("Montenegro","pie-register"),__("Morocco","pie-register"),__("Mozambique","pie-register"),__("Myanmar","pie-register"),__("Namibia","pie-register"),__("Nauru","pie-register"),__("Nepal","pie-register"),__("Netherlands","pie-register"),__("New Zealand","pie-register"),__("Nicaragua","pie-register"),__("Niger","pie-register"),__("Nigeria","pie-register"),__("Norway","pie-register"),__("Northern Mariana Islands","pie-register"),__("Oman","pie-register"),__("Pakistan","pie-register"),__("Palau","pie-register"),__("Palestine","pie-register"),__("Panama","pie-register"),__("Papua New Guinea","pie-register"),__("Paraguay","pie-register"),__("Peru","pie-register"),__("Philippines","pie-register"),__("Poland","pie-register"),__("Portugal","pie-register"),__("Puerto Rico","pie-register"),__("Qatar","pie-register"),__("Republic of North Macedonia","pie-register"),__("Romania","pie-register"),__("Russia","pie-register"),__("Rwanda","pie-register"),__("Saint Kitts and Nevis","pie-register"),__("Saint Lucia","pie-register"),__("Saint Vincent and the Grenadines","pie-register"),__("Samoa","pie-register"),__("San Marino","pie-register"),__("Sao Tome and Principe","pie-register"),__("Saudi Arabia","pie-register"),__("Senegal","pie-register"),__("Serbia","pie-register"),__("Seychelles","pie-register"),__("Sierra Leone","pie-register"),__("Singapore","pie-register"),__("Slovakia","pie-register"),__("Slovenia","pie-register"),__("Solomon Islands","pie-register"),__("Somalia","pie-register"),__("South Africa","pie-register"),__("Spain","pie-register"),__("Sri Lanka","pie-register"),__("Sudan","pie-register"),__("Sudan, South","pie-register"),__("Suriname","pie-register"),__("Sweden","pie-register"),__("Switzerland","pie-register"),__("Syria","pie-register"),__("Taiwan","pie-register"),__("Tajikistan","pie-register"),__("Tanzania","pie-register"),__("Thailand","pie-register"),__("Togo","pie-register"),__("Tonga","pie-register"),__("Trinidad and Tobago","pie-register"),__("Tunisia","pie-register"),__("Turkey","pie-register"),__("Turkmenistan","pie-register"),__("Tuvalu","pie-register"),__("Uganda","pie-register"),__("Ukraine","pie-register"),__("United Arab Emirates","pie-register"),__("United Kingdom","pie-register"),__("United States","pie-register"),__("Uruguay","pie-register"),__("Uzbekistan","pie-register"),__("Vanuatu","pie-register"),__("Vatican City","pie-register"),__("Venezuela","pie-register"),__("Vietnam","pie-register"),__("Virgin Islands, British","pie-register"),__("Virgin Islands, U.S.","pie-register"),__("Yemen","pie-register"),__("Zambia","pie-register"),__("Zimbabwe","pie-register"));
			update_option("pie_countries",$country);	
			
			//USA States
			$us_states = array(__("Alabama","pie-register"),__("Alaska","pie-register"),__("Arizona","pie-register"),__("Arkansas","pie-register"),__("California","pie-register"),__("Colorado","pie-register"),__("Connecticut","pie-register"),__("Delaware","pie-register"),__("District of Columbia","pie-register"),__("Florida","pie-register"),__("Georgia","pie-register"),__("Hawaii","pie-register"),__("Idaho","pie-register"),__("Illinois","pie-register"),__("Indiana","pie-register"),__("Iowa","pie-register"),__("Kansas","pie-register"),__("Kentucky","pie-register"),__("Louisiana","pie-register"),__("Maine","pie-register"),__("Maryland","pie-register"),__("Massachusetts","pie-register"),__("Michigan","pie-register"),__("Minnesota","pie-register"),__("Mississippi","pie-register"),__("Missouri","pie-register"),__("Montana","pie-register"),__("Nebraska","pie-register"),__("Nevada","pie-register"),__("New Hampshire", "pie-register"),__("New Jersey", "pie-register"),__("New Mexico", "pie-register"),__("New York", "pie-register"),__("North Carolina", "pie-register"),__("North Dakota", "pie-register"),__("Ohio","pie-register"),__("Oklahoma","pie-register"),__("Oregon","pie-register"),__("Pennsylvania","pie-register"),__("Rhode Island", "pie-register"),__("South Carolina", "pie-register"),__("South Dakota", "pie-register"),__("Tennessee","pie-register"),__("Texas","pie-register"),__("Utah","pie-register"),__("Vermont","pie-register"),__("Virginia","pie-register"),__("Washington","pie-register"),__("West Virginia", "pie-register"),__("Wisconsin","pie-register"),__("Wyoming","pie-register"),__("Armed Forces Americas","pie-register"),__("Armed Forces Europe","pie-register"),__("Armed Forces Pacific","pie-register"));
			update_option("pie_us_states",$us_states);
					
			//Canadian States
			$can_states = array(__("Alberta","pie-register"),__("British Columbia","pie-register"),__("Manitoba","pie-register"),__("New Brunswick","pie-register"),__("Newfoundland and Labrador","pie-register"),__("Northwest Territories","pie-register"),__("Nova Scotia","pie-register"),__("Nunavut","pie-register"),__("Ontario","pie-register"),__("Prince Edward Island","pie-register"),__("Quebec","pie-register"),__("Saskatchewan","pie-register"),__("Yukon","pie-register"));
			update_option("pie_can_states",$can_states);
			
			
			//E-Mail TYpes
			$email_type = array(
								"default_template"							=> __("Your account is ready.","pie-register"),
								"admin_verification"						=> __("Your account is being processed.","pie-register"),
								"email_verification"						=> __("Email verification.","pie-register"),
								"email_edit_verification"					=> __("Email address change verification.","pie-register"),
								"current_email_verification"				=> __("Current Email address change verification.","pie-register"),
								"email_thankyou"							=> __("Your account has been activated.","pie-register"),
								"forgot_password_notification"				=> __("Password Reset Request.","pie-register"),
								"pending_payment"							=> __("Overdue Payment.","pie-register"),
								"payment_success"							=> __("Payment Processed.","pie-register"),
								"payment_faild"								=> __("Payment Failed.","pie-register"),
								"pending_payment_reminder"					=> __("Payment Pending.","pie-register"),
								"email_verification_reminder"				=> __("Email Verification Reminder.","pie-register"),
								"user_expiry_notice"						=> __("Final Email Verification Reminder.","pie-register"),
								"user_temp_blocked_notice"					=> __("User Temporarily Blocked Notice","pie-register"),
								"user_renew_temp_blocked_account_notice"	=> __("Payment Failed.","pie-register"),
								"user_perm_blocked_notice"					=> __("Notice: User removed because email address was not verfied.","pie-register")
								);
			
			update_option("pie_user_email_types",$email_type);
			
			// add installation time
			add_option('pie_install_date', current_time('mysql'));

			global $PR_Bot_List;
			$current 	= get_option(OPTION_PIE_REGISTER);
			$update 	= $current;
			
			$update["paypal_butt_id"] = (isset($current["paypal_butt_id"]) && $current["paypal_butt_id"])?$current["paypal_butt_id"]:"";
			$update["paypal_pdt"]     = (isset($current["paypal_pdt"]) && $current["paypal_pdt"])?$current["paypal_pdt"]:"";
			$update["paypal_sandbox"] = (isset($current["paypal_sandbox"]) && $current["paypal_sandbox"])?$current["paypal_sandbox"]:"";
			$update["payment_success_msg"] 	= (isset($current["payment_success_msg"]) && $current["payment_success_msg"])?$current["payment_success_msg"]:__("Payment was successful.","pie-register");
			$update["payment_faild_msg"] 	= (isset($current["payment_faild_msg"]) && $current["payment_faild_msg"])?$current["payment_faild_msg"]:__("Payment failed.","pie-register");
			$update["payment_renew_msg"] 	= (isset($current["payment_renew_msg"]) && $current["payment_renew_msg"])?$current["payment_renew_msg"]:__("Account needs to be activated.","pie-register");
			$update["payment_already_activate_msg"] 	= (isset($current["payment_already_activate_msg"]) && $current["payment_already_activate_msg"])?$current["payment_already_activate_msg"]:__("Account is already active.","pie-register");
			$update['enable_admin_notifications'] = (isset($current["enable_admin_notifications"]) && $current['enable_admin_notifications'] !== "")?$current['enable_admin_notifications']:1;
			$update['enable_paypal'] = (isset($current["enable_paypal"]) && $current['enable_paypal'])?$current['enable_paypal']:0;
			$update['enable_blockedips'] = (isset($current["enable_blockedips"]) && $current['enable_blockedips'])?$current['enable_blockedips']:0;
			$update['enable_blockedusername'] = (isset($current["enable_blockedusername"]) && $current['enable_blockedusername'])?$current['enable_blockedusername']:0;
			$update['enable_blockedemail'] = (isset($current["enable_blockedemail"]) && $current['enable_blockedemail'])?$current['enable_blockedemail']:0;
			
			$update['admin_sendto_email'] 	= (isset($current["admin_sendto_email"]) && $current['admin_sendto_email'])?$current['admin_sendto_email']:get_option( 'admin_email' );				
			$update['admin_from_name'] 		= (isset($current["admin_from_name"]) && $current['admin_from_name'])?$current['admin_from_name']:"Administrator";
			$update['admin_from_email'] 	= (isset($current["admin_from_email"]) && $current['admin_from_email'])?$current['admin_from_email']:get_option( 'admin_email' );
			$update['admin_to_email'] 		= (isset($current["admin_to_email"]) && $current['admin_to_email'])?$current['admin_to_email']:get_option( 'admin_email' );
			$update['admin_bcc_email'] 		= (isset($current["admin_bcc_email"]) && $current['admin_bcc_email'])?$current['admin_bcc_email']:get_option( 'admin_email' );
			$update['admin_subject_email'] 	= (isset($current["admin_subject_email"]) && $current['admin_subject_email'])?$current['admin_subject_email']:__("New User Registration","pie-register");
			$update['admin_message_email_formate'] 			= (isset($current["admin_message_email_formate"]) && $current['admin_message_email_formate'])?$current['admin_message_email_formate']:1;
			$update['user_formate_email_default_template'] 	= (isset($current["user_formate_email_default_template"]) && $current['user_formate_email_default_template'])?$current['user_formate_email_default_template']:1;
			$update['user_enable_default_template'] 	= (isset($current["user_enable_default_template"]) && $current['user_enable_default_template'])?$current['user_enable_default_template']:1;
			$update['admin_message_email'] 		= (isset($current["admin_message_email"]) && $current['admin_message_email'])?$current['admin_message_email']:'<p>Hello Admin,</p><p>A new user has been registered on your Website,. Details are given below:</p><p>Thanks</p><p>Team %blogname%</p>';
			$update['display_hints']			= (isset($current["display_hints"]) && $current['display_hints'])?$current['display_hints']:0; // (1) - 090415
			$update['redirect_user']			= (isset($current["redirect_user"]) && $current['redirect_user'] !== "")?$current['redirect_user']:1;
			$update['subscriber_login']			= (isset($current["subscriber_login"]) && $current['subscriber_login'])?$current['subscriber_login']:0;
			$update['login_form_in_website']	= (isset($current["login_form_in_website"]) && $current['login_form_in_website'] !== "")?$current['login_form_in_website']:1;
			$update['registration_in_website']	= (isset($current["registration_in_website"]) && $current['registration_in_website'] !== "")?$current['registration_in_website']:1;
			$update['block_WP_profile']			= (isset($current["block_WP_profile"]) && $current['block_WP_profile'])?$current['block_WP_profile']:0;
			$update['allow_pr_edit_wplogin']	= (isset($current["allow_pr_edit_wplogin"]) && $current['allow_pr_edit_wplogin'])?$current['allow_pr_edit_wplogin']:0;
			$update['modify_avatars']			= (isset($current["modify_avatars"]) && $current['modify_avatars'])?$current['modify_avatars']:0;
			$update['show_admin_bar']			= (isset($current["show_admin_bar"]) && $current['show_admin_bar'] !== "")?$current['show_admin_bar']:1;
			$update['block_wp_login']			= (isset($current["block_wp_login"]) && $current['block_wp_login'] !== "")?$current['block_wp_login']:1;
			$update['alternate_login']			= $pie_pages[0];
			$update['alternate_register']		= $pie_pages[1];
			$update['alternate_forgotpass']		= $pie_pages[2];
			$update['alternate_profilepage']	= $pie_pages[3];
			
			////// Date Starting/Ending Variables////////////
			//////////////// Since 2.0.12 ///////////////////
			$update['piereg_startingDate']		= (isset($current["piereg_startingDate"]) && $current['piereg_startingDate'])?$current['piereg_startingDate']:'1901';
			$update['piereg_endingDate']		= (isset($current["piereg_endingDate"]) && $current['piereg_endingDate'])?$current['piereg_endingDate']:date_i18n("Y");
			
			$update['after_login']				= (isset($current["after_login"]) && $current['after_login'])?$current['after_login']:-1;
			$update['alternate_logout']			= (isset($current["alternate_logout"]) && $current['alternate_logout'])?$current['alternate_logout']:-1;
			$update['alternate_logout_url']		= (isset($current["alternate_logout"]) && $current['alternate_logout'])?$current['alternate_logout_url']:"";
			$update['outputcss'] 				= (isset($current["outputcss"]) && $current['outputcss'] !== "")?$current['outputcss']:1;
			$update['outputjquery_ui'] 			= (isset($current["outputjquery_ui"]) && $current['outputjquery_ui'] !== "")?$current['outputjquery_ui']:1;
			$update['login_after_register'] 	= (isset($current["login_after_register"]) && $current['login_after_register'])?$current['login_after_register']:0;
			
			$update['pass_strength_indicator_label']	= (isset($current["pass_strength_indicator_label"]) && $current['pass_strength_indicator_label'])? $current['pass_strength_indicator_label'] : "Strength Meter";
			$update['pass_very_weak_label']				= (isset($current["pass_very_weak_label"]) && $current['pass_very_weak_label'])? $current['pass_very_weak_label'] : "Very weak";
			$update['pass_weak_label']					= (isset($current["pass_weak_label"]) && $current['pass_weak_label'])? $current['pass_weak_label'] : "Weak";
			$update['pass_medium_label']				= (isset($current["pass_medium_label"]) && $current['pass_medium_label'])? $current['pass_medium_label'] : "Medium";
			$update['pass_strong_label']				= (isset($current["pass_strong_label"]) && $current['pass_strong_label'])? $current['pass_strong_label'] : "Strong";
			$update['pass_mismatch_label']				= (isset($current["pass_mismatch_label"]) && $current['pass_mismatch_label'])? $current['pass_mismatch_label'] : "Mismatch";
			$update['pr_theme']							= (isset($current["pr_theme"]) && $current['pr_theme'])? $current['pr_theme'] : "0";
			
			/* Bot Settings */
			$update['restrict_bot_enabel'] 		= (isset($current["restrict_bot_enabel"]) && $current['restrict_bot_enabel'])?$current['restrict_bot_enabel']:0;
			$update['restrict_bot_content']		= (isset($current["restrict_bot_content"]) && $current['restrict_bot_content'])?$current['restrict_bot_content']: $PR_Bot_List;
			$update['restrict_bot_content_message']		= (isset($current["restrict_bot_content_message"]) && $current['restrict_bot_content_message'])?$current['restrict_bot_content_message']:"Restricted Post: You are not allowed to view the content of this Post";
			
			$update['outputhtml'] 				= (isset($current["outputhtml"]) && $current['outputhtml'] !== "")?$current['outputhtml']:1;
			$update['no_conflict']				= (isset($current["no_conflict"]) && $current['no_conflict'])?$current['no_conflict']:0;
			$update['currency'] 				= (isset($current["currency"]) && $current['currency'])?$current['currency']:"USD";
			$update['verification'] 			= (isset($current["verification"]) && $current['verification'])?$current['verification']:0;
			$update['email_edit_verification_step']	= (isset($current["email_edit_verification_step"]) && $current['email_edit_verification_step'] !== "")?$current['email_edit_verification_step']:1;
			
			$update['grace_period'] 			= (isset($current["grace_period"]) && $current['grace_period'])?$current['grace_period']:0;
			$update['piereg_recaptcha_type'] = (isset($current["piereg_recaptcha_type"]) && $current['piereg_recaptcha_type'])? $current['piereg_recaptcha_type'] : "v2";
			$update['captcha_publc'] 			= (isset($current["captcha_publc"]) && $current['captcha_publc'])?$current['captcha_publc']:"";
			$update['captcha_private'] 			= (isset($current["captcha_private"]) && $current['captcha_private'])?$current['captcha_private']:"";
			$update['captcha_publc_v3'] 		 = (isset($current["captcha_publc_v3"]) && $current['captcha_publc_v3'])?$current['captcha_publc_v3']:"";
			$update['captcha_private_v3'] 		 = (isset($current["captcha_private_v3"]) && $current['captcha_private_v3'])?$current['captcha_private_v3']:"";
			$update['piereg_recaptcha_language'] = (isset($current["piereg_recaptcha_language"]) && $current['piereg_recaptcha_language'])?$current['piereg_recaptcha_language']:"en";
			$update['paypal_button_id'] 		= (isset($current["paypal_button_id"]) && $current['paypal_button_id'])?$current['paypal_button_id']:"";
			$update['paypal_pdt_token'] 		= (isset($current["paypal_pdt_token"]) && $current['paypal_pdt_token'])?$current['paypal_pdt_token']:"";
			$update['custom_css'] 				= (isset($current["custom_css"]) && $current['custom_css'])?$current['custom_css']:"";
			$update['tracking_code'] 			= (isset($current["tracking_code"]) && $current['tracking_code'])?$current['tracking_code']:"";
			$update['enable_invitation_codes'] 	= (isset($current["enable_invitation_codes"]) && $current['enable_invitation_codes'])?$current['enable_invitation_codes']:0;
			$update['invitation_codes'] 		= (isset($current["invitation_codes"]) && $current['invitation_codes'])?$current['invitation_codes']:"";
			
			// Invitation Code Send Invitation Settings
			$update['pie_email_linkpage']		= (isset($current["pie_email_linkpage"]) && $current['pie_email_linkpage'])?$current['pie_email_linkpage']: $pie_pages[1];			
			$update['pie_email_invitecode']		= (isset($current["pie_email_invitecode"]) && $current['pie_email_invitecode'])?$current['pie_email_invitecode']: 0;
			$update['pie_name_from']			= (isset($current["pie_name_from"]) && $current['pie_name_from'])?$current['pie_name_from']: "Admin";
			$update['pie_email_from']			= (isset($current["pie_email_from"]) && $current['pie_email_from'])?$current['pie_email_from']: get_option( 'admin_email' );
			$update['pie_email_subject']		= (isset($current["pie_email_subject"]) && $current['pie_email_subject'])?$current['pie_email_subject']: "Invitation to create an account on %blogname%";
			$update['pie_email_content']		= (isset($current["pie_email_content"]) && $current['pie_email_content'])?$current['pie_email_content']: 'Hi there,'.PHP_EOL.PHP_EOL.'You are invited to create an account on our website: %blogname%'.PHP_EOL.'Click the link below to begin.'.PHP_EOL.PHP_EOL.'<a href="%invitation_link%">%invitation_link%</a>'.PHP_EOL.PHP_EOL.'Thank you.';

			// Payment Setting 
			$update['payment_setting_amount']	= (isset($current["payment_setting_amount"]) && $current['payment_setting_amount'])?$current['payment_setting_amount']:"10";
			
			// Role setting
			$update['pie_regis_set_user_role_'] = (isset($current["pie_regis_set_user_role_"]) && $current['pie_regis_set_user_role_'])?$current['pie_regis_set_user_role_']:"subscriber";
			$update['pie_regis_set_user_role_1'] = (isset($current["pie_regis_set_user_role_1"]) && $current['pie_regis_set_user_role_1'])?$current['pie_regis_set_user_role_1']:"subscriber";
			
			$update['custom_logo_url']					= (isset($current["custom_logo_url"]) && $current['custom_logo_url'])? $current['custom_logo_url'] : "";
			$update['reg_form_submission_time_enable']  = (isset($current["reg_form_submission_time_enable"]) && $current['reg_form_submission_time_enable'])? $current['reg_form_submission_time_enable'] : "0";
			$update['reg_form_submission_time'] 		= (isset($current["reg_form_submission_time"]) && $current['reg_form_submission_time'])? $current['reg_form_submission_time'] : "0";
			$update['custom_logo_tooltip']				= (isset($current["custom_logo_tooltip"]) && $current['custom_logo_tooltip'])? $current['custom_logo_tooltip'] : "";
			$update['custom_logo_link']					= (isset($current["custom_logo_link"]) && $current['custom_logo_link'])? $current['custom_logo_link'] : "";
			$update['show_custom_logo']					= (isset($current["show_custom_logo"]) && $current['show_custom_logo'] !== "")? $current['show_custom_logo'] : 1;
			// Login form
			$update['login_username_label']			= (isset($current["login_username_label"]) && $current['login_username_label'])? $current['login_username_label'] : "Username";
			$update['login_username_placeholder']	= (isset($current["login_username_placeholder"]) && $current['login_username_placeholder'])? $current['login_username_placeholder'] : "";
			$update['login_password_label']			= (isset($current["login_password_label"]) && $current['login_password_label'])? $current['login_password_label'] : "Password";
			$update['login_password_placeholder']	= (isset($current["login_password_placeholder"]) && $current['login_password_placeholder'])? $current['login_password_placeholder'] : "";
			$update['capthca_in_login_label']		= (isset($current["capthca_in_login_label"]) && $current['capthca_in_login_label'])? $current['capthca_in_login_label'] : "";
			$update['capthca_in_login']				= (isset($current["capthca_in_login"]) && $current['capthca_in_login'])? $current['capthca_in_login'] : "0";
			
			//New Settings 
			$update['captcha_in_login_value']				 = (isset($current["captcha_in_login_value"]) && $current['captcha_in_login_value']) ? $current['captcha_in_login_value'] : 0;
			$update['piereg_security_attempts_login_value']  = (isset($current["piereg_security_attempts_login_value"]) && $current['piereg_security_attempts_login_value']) ? $current['piereg_security_attempts_login_value'] : '0';
			$update['captcha_in_forgot_value']				 = (isset($current["capthca_in_forgot_pass"]) && $current['capthca_in_forgot_pass']) ? $current['capthca_in_forgot_pass'] : 0;
			$update['piereg_security_attempts_forgot_value'] = (isset($current["piereg_security_attempts_forgot_value"]) && $current['piereg_security_attempts_forgot_value'])? $current['piereg_security_attempts_forgot_value'] : "0";
			
			//security_attempts_login
			$update['security_captcha_attempts_login']	= (isset($current["security_captcha_attempts_login"]) && $current['security_captcha_attempts_login'])? $current['security_captcha_attempts_login'] : "0";
			$update['security_captcha_login']			= (isset($current["security_captcha_login"]) && $current['security_captcha_login'] !== "")? $current['security_captcha_login'] : "2";
			$update['security_attempts_login']			= (isset($current["security_attempts_login"]) && $current['security_attempts_login'])? $current['security_attempts_login'] : "0";
			$update['security_attempts_login_time']		= (isset($current["security_attempts_login_time"]) && $current['security_attempts_login_time'] !== "")? $current['security_attempts_login_time'] : "1";
			
			// Forgot Password form
			$update['forgot_pass_username_label']		= (isset($current["forgot_pass_username_label"]) && $current['forgot_pass_username_label'])? $current['forgot_pass_username_label'] : "Username or Email:";
			$update['forgot_pass_username_placeholder']	= (isset($current["forgot_pass_username_placeholder"]) && $current['forgot_pass_username_placeholder'])? $current['forgot_pass_username_placeholder'] : "";
			$update['forgot_pass_username_placeholder']	= (isset($current["forgot_pass_username_placeholder"]) && $current['forgot_pass_username_placeholder'])? $current['forgot_pass_username_placeholder'] : "";
			$update['capthca_in_forgot_pass_label']		= (isset($current["capthca_in_forgot_pass_label"]) && $current['capthca_in_forgot_pass_label'])? $current['capthca_in_forgot_pass_label'] : "";
			$update['capthca_in_forgot_pass']			= (isset($current["capthca_in_forgot_pass"]) && $current['capthca_in_forgot_pass'])? $current['capthca_in_forgot_pass'] : "0";
						
			$pie_user_email_types 	= get_option( 'pie_user_email_types');				
			foreach ($pie_user_email_types as $val=>$type) 
			{
				$update['enable_user_notifications'] = (isset($current["enable_user_notifications"]) && $current['enable_user_notifications'])?$current['enable_user_notifications']:0;
				$update['user_from_name_'.$val] 	 = (isset($current['user_from_name_'.$val]) && $current['user_from_name_'.$val])?$current['user_from_name_'.$val]:"Admin";
				$update['user_from_email_'.$val] 	 = (isset($current['user_from_email_'.$val]) && $current['user_from_email_'.$val])?$current['user_from_email_'.$val]:get_option( 'admin_email' );
				$update['user_to_email_'.$val]	 	 = (isset($current['user_to_email_'.$val]) && $current['user_to_email_'.$val])?$current['user_to_email_'.$val]:get_option( 'admin_email' );
				$update['user_subject_email_'.$val]  = (isset($current['user_subject_email_'.$val]) && $current['user_subject_email_'.$val])?$current['user_subject_email_'.$val]:$type;
				$update['user_formate_email_'.$val]  = (isset($current['user_formate_email_'.$val]) && $current['user_formate_email_'.$val] !== "")?$current['user_formate_email_'.$val]:1;
				$update['user_enable_'.$val]  		= (isset($current['user_enable_'.$val]) && $current['user_enable_'.$val] !== "")?$current['user_enable_'.$val]:1;
			}
			$update['user_message_email_admin_verification']	 					= (isset($current["user_message_email_admin_verification"]) && $current['user_message_email_admin_verification'])?$current['user_message_email_admin_verification']: '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>A site administrator will review your request. Once approved, you will be notified via email.</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			$update['user_message_email_email_verification']			 			= (isset($current["user_message_email_email_verification"]) && $current['user_message_email_email_verification'])?$current['user_message_email_email_verification']:'<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>Please use the link below to verify your email address. </p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			$update['user_message_email_email_thankyou'] 							= (isset($current["user_message_email_email_thankyou"]) && $current['user_message_email_email_thankyou'])?$current['user_message_email_email_thankyou']:'<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>You are ready to enjoy the benefits of our products and services by signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_payment_success'] 							= (isset($current["user_message_email_payment_success"]) && $current['user_message_email_payment_success'])?$current['user_message_email_payment_success']:'<p>Dear %user_login%,</p><p>Congratulations, your payment has been successfully processed. <br/>Please enjoy the benefits of your membership on %blogname% </p><p>Thank You,</p><p>Team %blogname%</p>';
			$update['user_message_email_payment_faild'] 							= (isset($current["user_message_email_payment_faild"]) && $current['user_message_email_payment_faild'])?$current['user_message_email_payment_faild']:'<p>Dear %user_login%,</p><p>Our last attempt to charge the membership payment for your account has failed. </p><p>You are requested to log in to your account at %blogname% to provide a different payment method, or contact your bank/credit-card company to resolve this issue.</p><p>Kind Regards,</p><p>Team %blogname%<br/></p>';
			$update['user_message_email_pending_payment'] 							= (isset($current["user_message_email_pending_payment"]) && $current['user_message_email_pending_payment'])?$current['user_message_email_pending_payment']:'<p>Dear %user_login%,</p><p>This is a reminder that membership payment is overdue for your account on %blogname%. Please process your payment immediately to keep membership previlages active. </p><p>Best Regards,</p><p>Team %blogname%</p>';
			$update['user_message_email_default_template'] 							= (isset($current["user_message_email_default_template"]) && $current['user_message_email_default_template'])?$current['user_message_email_default_template']: '<p>Dear %user_login%,</p><p>Thank You for registering with our website.</p><p>You are ready to enjoy the benefits of our products and services by signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_pending_payment_reminder'] 					= (isset($current["user_message_email_pending_payment_reminder"]) && $current['user_message_email_pending_payment_reminder'])?$current['user_message_email_pending_payment_reminder']: '<p>Dear %user_login%,</p><p>We have noticed that you created an account on %blogname% a few days ago, but have not completed the payment. Please use the link below to complete the payment. <br/>Your account will be activated once the payment is received.</p><p>%pending_payment_url%</p><p>Best Regards,</p><p>Team %blogname%</p>';
			$update['user_message_email_email_verification_reminder']			 	= (isset($current["user_message_email_email_verification_reminder"]) && $current['user_message_email_email_verification_reminder'])?$current['user_message_email_email_verification_reminder']: '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>We noticed that you created an account on %blogname% but have not completed the email verification process. </p><p>Please use the link below to verify your email address. </p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			$update['user_message_email_forgot_password_notification']				= (isset($current["user_message_email_forgot_password_notification"]) && $current['user_message_email_forgot_password_notification'])?$current['user_message_email_forgot_password_notification']: '<p>Dear %user_login%,</p><p>We have received a request to reset your account password on %blogname%. Please use the link below to reset your password. If you did not request a new password, please ignore this email and the change will not be made.</p><p>( %reset_password_url% )</p><p>Best Regards,</p><p>Team %user_login%</p>';
			$update['user_message_email_user_expiry_notice'] 						= (isset($current["user_message_email_user_expiry_notice"]) && $current['user_message_email_user_expiry_notice'])? $current['user_message_email_user_expiry_notice']: '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>We noticed that you created an account on %blogname% but have not completed the email verification process. Failure to do so will result in your account being removed.</p><p>Please use the link below to verify your email address. </p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			$update['user_message_email_user_temp_blocked_notice']					= (isset($current["user_message_email_user_temp_blocked_notice"]) && $current['user_message_email_user_temp_blocked_notice'])?$current['user_message_email_user_temp_blocked_notice'] :__("You are temporarily blocked at","pie-register")." %blogname%";
			$update['user_message_email_user_renew_temp_blocked_account_notice']	= (isset($current["user_message_email_user_renew_temp_blocked_account_notice"]) && $current['user_message_email_user_renew_temp_blocked_account_notice'])?$current['user_message_email_user_renew_temp_blocked_account_notice'] : '<p>Dear %user_login%,</p><p>Our last attempt to charge the membership payment for your account has failed. </p><p>You are requested to log in to your account at %blogname% to provide a different payment method, or contact your bank/credit-card company to resolve this issue. </p><p>Access to your account has been temporarily disabled until this issue is resolved.</p><p>Kind Regards,</p><p>Team %blogname%</p>';
			$update['user_message_email_user_perm_blocked_notice']					= (isset($current["user_message_email_user_perm_blocked_notice"]) && $current['user_message_email_user_perm_blocked_notice'])? $current['user_message_email_user_perm_blocked_notice']: '<p>Dear %user_login%,</p><p>You were required to verify your account, but you failed to do so within grace period. So, your account is being removed from %blogname%. You need to register again. </p><p>Kind Regards,</p><p>Team %blogname%</p>'; # Change since 3.0.9	
			
			$update['user_message_email_email_edit_verification']					= (isset($current["user_message_email_email_edit_verification"]) && $current['user_message_email_email_edit_verification'])?$current['user_message_email_email_edit_verification']: '<p>Hello %user_login%,</p><p>We have received a request to change the email address for your account on %blogname%.</p><p>Old Email Address: %user_email%<br/>New Email Address: %user_new_email%. </p><p>Please use the link below to complete this change.</p><p>(%reset_email_url%)</p><p>Thanks</p><p>%blogname%<br/></p>';
			$update['user_message_email_current_email_verification']				= (isset($current["user_message_email_current_email_verification"]) && $current['user_message_email_current_email_verification'])?$current['user_message_email_current_email_verification']: '<p>Hello %user_login%,</p><p>We have received a request to change the email address for your account on %blogname%.</p><p>Old Email Address: %user_email%<br/>  New Email Address: %user_new_email%. </p><p>If you requested this change, please use the link below to complete the action. Otherwise please ignore this email and the change will not be made.</p><p>(%confirm_current_email_url%)</p><p>Thanks</p><p>%blogname%<br/></p>';
			
			update_option(OPTION_PIE_REGISTER, $update );
			
			$current_fields 	= maybe_unserialize(get_option( 'pie_fields' ));
			$fields 					= array();
			
			$fields['form']['label'] 				= (isset($current_fields['form']['label']) && $current_fields['form']['label'])?$current_fields['form']['label']:__("Registration Form","pie-register");
			$fields['form']['desc'] 				= (isset($current_fields['form']['desc']) && $current_fields['form']['desc'])?$current_fields['form']['desc']:__("Please fill in the form below to register.","pie-register");
			$fields['form']['label_alignment'] 		= (isset($current_fields['form']['label_alignment']) && $current_fields['form']['label_alignment'])?$current_fields['form']['label_alignment']:"left";
			$fields['form']['css']					= (isset($current_fields['form']['css']) && $current_fields['form']['css'])?$current_fields['form']['css']:"";
			$fields['form']['type']					= (isset($current_fields['form']['type']) && $current_fields['form']['type'])?$current_fields['form']['type']:"form";
			$fields['form']['meta']					= (isset($current_fields['form']['meta']) && $current_fields['form']['meta'])?$current_fields['form']['meta']:0;
			$fields['form']['reset']				= (isset($current_fields['form']['reset']) && $current_fields['form']['reset'])?$current_fields['form']['reset']:0;
			
			$fields[0]['label'] 					= (isset($current_fields[0]['label']) && $current_fields[0]['label'])?$current_fields[0]['label']:__("Username","pie-register");
			$fields[0]['type'] 						= (isset($current_fields[0]['type']) && $current_fields[0]['type'])?$current_fields[0]['type']:"username";
			$fields[0]['id'] 						= (isset($current_fields[0]['id']) && $current_fields[0]['id'])?$current_fields[0]['id']:0;
			$fields[0]['remove'] 					= (isset($current_fields[0]['remove']) && $current_fields[0]['remove'])?$current_fields[0]['remove']:0;
			$fields[0]['required'] 					= (isset($current_fields[0]['required']) && $current_fields[0]['required'])?$current_fields[0]['required']:1;
			$fields[0]['desc'] 						= (isset($current_fields[0]['desc']) && $current_fields[0]['desc'])?$current_fields[0]['desc']:"";
			$fields[0]['length'] 					= (isset($current_fields[0]['length']) && $current_fields[0]['length'])?$current_fields[0]['length']:"";
			$fields[0]['default_value'] 			= (isset($current_fields[0]['default_value']) && $current_fields[0]['default_value'])?$current_fields[0]['default_value']:"";
			$fields[0]['placeholder'] 				= (isset($current_fields[0]['placeholder']) && $current_fields[0]['placeholder'])?$current_fields[0]['placeholder']:"";
			$fields[0]['css'] 						= (isset($current_fields[0]['css']) && $current_fields[0]['css'])?$current_fields[0]['css']:""; 
			$fields[0]['meta']						= (isset($current_fields[0]['meta']) && $current_fields[0]['meta'])?$current_fields[0]['meta']:0;
			
			$fields[1]['label'] 			= (isset($current_fields[1]['label']) && $current_fields[1]['label'])?$current_fields[1]['label']:__("Email","pie-register");
			$fields[1]['label2'] 			= (isset($current_fields[1]['label2']) && $current_fields[1]['label2'])?$current_fields[1]['label2']:__("Confirm Email","pie-register");
			$fields[1]['type'] 				= (isset($current_fields[1]['type']) && $current_fields[1]['type'])?$current_fields[1]['type']:"email";
			$fields[1]['id'] 				= (isset($current_fields[1]['id']) && $current_fields[1]['id'])?$current_fields[1]['id']:1;
			$fields[1]['remove'] 			= (isset($current_fields[1]['required']) && $current_fields[1]['remove'])?$current_fields[1]['remove']:0;
			$fields[1]['required'] 			= (isset($current_fields[1]['required']) && $current_fields[1]['required'])?$current_fields[1]['required']:1;
			$fields[1]['desc'] 				= (isset($current_fields[1]['desc']) && $current_fields[1]['desc'])?$current_fields[1]['desc']:"";
			$fields[1]['length'] 			= (isset($current_fields[1]['length']) && $current_fields[1]['length'])?$current_fields[1]['length']:"";
			$fields[1]['default_value'] 	= (isset($current_fields[1]['default_value']) && $current_fields[1]['default_value'])?$current_fields[1]['default_value']:"";
			$fields[1]['placeholder'] 		= (isset($current_fields[1]['placeholder']) && $current_fields[1]['placeholder'])?$current_fields[1]['placeholder']:"";			
			$fields[1]['placeholder2'] 		= (isset($current_fields[1]['placeholder2']) && $current_fields[1]['placeholder2'])?$current_fields[1]['placeholder2']:"";
			$fields[1]['css'] 				= (isset($current_fields[1]['css']) && $current_fields[1]['css'])?$current_fields[1]['css']:""; 
			$fields[1]['validation_rule'] 	= (isset($current_fields[1]['validation_rule']) && $current_fields[1]['validation_rule'])?$current_fields[1]['validation_rule']:"email";
			$fields[1]['meta']				= (isset($current_fields[1]['meta']) && $current_fields[1]['meta'])?$current_fields[1]['meta']:0;
			
			$fields[2]['label'] 			= (isset($current_fields[2]['label']) && $current_fields[2]['label'])?$current_fields[2]['label']:__("Password","pie-register");
			$fields[2]['label2'] 			= (isset($current_fields[2]['label2']) && $current_fields[2]['label2'])?$current_fields[2]['label2']:__("Confirm Password","pie-register");
			$fields[2]['type'] 				= (isset($current_fields[2]['type']) && $current_fields[2]['type'])?$current_fields[2]['type']:"password";
			$fields[2]['id'] 				= (isset($current_fields[2]['id']) && $current_fields[2]['id'])?$current_fields[2]['id']:2;
			$fields[2]['remove'] 			= (isset($current_fields[2]['remove']) && $current_fields[2]['remove'])?$current_fields[2]['remove']:0;
			$fields[2]['required'] 			= (isset($current_fields[2]['required']) && $current_fields[2]['required'])?$current_fields[2]['required']:1;
			$fields[2]['desc'] 				= (isset($current_fields[2]['desc']) && $current_fields[2]['desc'])?$current_fields[2]['desc']:"";
			$fields[2]['length'] 			= (isset($current_fields[2]['length']) && $current_fields[2]['length'])?$current_fields[2]['length']:"";
			$fields[2]['default_value'] 	= (isset($current_fields[2]['default_value']) && $current_fields[2]['default_value'])?$current_fields[2]['default_value']:"";
			$fields[2]['placeholder'] 		= (isset($current_fields[2]['placeholder']) && $current_fields[2]['placeholder'])?$current_fields[2]['placeholder']:"";
			$fields[2]['placeholder2'] 		= (isset($current_fields[2]['placeholder2']) && $current_fields[2]['placeholder2'])?$current_fields[2]['placeholder2']:"";
			$fields[2]['css'] 				= (isset($current_fields[2]['css']) && $current_fields[2]['css'])?$current_fields[2]['css']:""; 
			$fields[2]['validation_rule'] 	= (isset($current_fields[2]['validation_rule']) && $current_fields[2]['validation_rule'])?$current_fields[2]['validation_rule']:""; 
			$fields[2]['meta']				= (isset($current_fields[2]['meta']) && $current_fields[2]['meta'])?$current_fields[2]['meta']:0;	
			$fields[2]['show_meter']		= (isset($current_fields[2]['show_meter']) && $current_fields[2]['show_meter'])?$current_fields[2]['show_meter']:1;		
						
			//Getting data from old plugins
			$num = 3;
			if( ( isset($current['firstname']) && $current['firstname'] ) || ( isset($current['lastname']) && $current['lastname'] ) )
			{
				$fields[$num]['type'] 			= "name";
				$fields[$num]['label'] 			= __("First Name","pie-register");	
				$fields[$num]['label2'] 		= __("Last Name","pie-register");
				$fields[$num]['field_name'] 	= "first_name";			
				$fields[$num]['id'] 			= $num;	
				$num++;		
			}
			
			if( isset($current['website']) && $current['website'] )
			{
				$fields[$num]['type'] 			= "default";
				$fields[$num]['label'] 			= __("Website","pie-register");	
				$fields[$num]['field_name'] 	= "url";		
				$fields[$num]['id'] 			= $num;	
				$num++;		
			}
			if( isset($current['aim']) && $current['aim'] )
			{
				$fields[$num]['type'] 			= "default";
				$fields[$num]['label'] 			= __("AIM","pie-register");
				$fields[$num]['field_name'] 	= "aim";			
				$fields[$num]['id'] 			= $num;	
				$num++;			
			}
			if( isset($current['yahoo']) && $current['yahoo'] )
			{
				$fields[$num]['type'] 			= "default";
				$fields[$num]['label'] 			= __("Yahoo IM","pie-register");
				$fields[$num]['field_name'] 	= "yim";			
				$fields[$num]['id'] 			= $num;	
				$num++;		
			}
			if( isset($current['jabber']) && $current['jabber'] )
			{
				$fields[$num]['type'] 			= "default";
				$fields[$num]['label'] 			= __("Jabber / Google Talk","pie-register");
				$fields[$num]['field_name'] 	= "jabber";			
				$fields[$num]['id'] 			= $num;	
				$num++;		
			}
			if( isset($current['about']) && $current['about'] )
			{
				$fields[$num]['type'] 			= "default";
				$fields[$num]['label'] 			= __("About Yourself","pie-register");	
				$fields[$num]['field_name'] 	= "description";			
				$fields[$num]['id'] 			= $num;	
				$num++;		
			}
			
			$piereg_custom = get_option( 'pie_register_custom' );
			if( is_array($piereg_custom ))
			{
				foreach( $piereg_custom as $k=>$v)
				{	
					
					if($v['fieldtype']=="select" || $v['fieldtype']=="checkbox" || $v['fieldtype']=="radio")//Populating values
					{
						$ops = explode(',',$v['extraoptions']);
						foreach( $ops as $op )
						{
							$fields[$num]['value'][] 	= $op;
							$fields[$num]['display'][] 	= $op;
						}
					}
					else
					{
						$fields[$num]['default_value'] 	= $v['extraoptions'];				
					}
					
					$fields[$num]['type'] 			= $v['fieldtype'];
					$fields[$num]['label'] 			= $v['label'];			
					$fields[$num]['id'] 			= $num;			
					$fields[$num]['required'] 		= $v['required'];
					
					if($fields[$num]['type']=="select")
					{
						$fields[$num]['type'] = "dropdown";	
					}
					
					if($fields[$num]['type']=="date")
					{
						$fields[$num]['date_type'] 	 	= "datepicker";
						$fields[$num]['date_format'] 	= $current["dateformat"];
						$fields[$num]['firstday'] 		= $current["firstday"];
						$fields[$num]['startdate'] 		= $current["startdate"];
						$fields[$num]['calyear'] 		= $current["calyear"];	
						$fields[$num]['calmonth'] 		= $current["calmonth"];				
					}
					
					$num++;
				}
			}
			
			$fields['submit']['message'] 			= __("Thank you for registering","pie-register");
			$fields['submit']['confirmation'] 		= "text";
			$fields['submit']['text'] 				= "Submit";
			$fields['submit']['reset']				= 0;
			$fields['submit']['reset_text'] 		= "Reset";
			$fields['submit']['type'] 				= "submit";
			$fields['submit']['meta']				= 0;
			$fields['submit']['redirect_url']		= "";
		
			
			update_option( 'pie_fields_default', $fields  );
			
			$structure 	= $this->getDefaultMeta();
			
					
			update_option( 'pie_fields_meta', $structure  );
			
			
			/*
				*	Get old form or create default form
			*/
			$created_form_id = $this->install_default_reg_form();
			
			//Alternate Pages
			$get_pie_pages_from_db		= get_option("pie_pages");
			if(is_array($get_pie_pages_from_db))
			{
				$piereg_registrtion = (isset($get_pie_pages_from_db[1]) and $get_pie_pages_from_db != "")? get_post_status($get_pie_pages_from_db[1]) : "null";
			}
			
			$pie_pages = get_option("pie_pages");
			
			if($piereg_registration_create_new_page && isset($pie_pages[1]) && !empty($pie_pages[1]) )//Registration
			{
				$_p = array();
				$_p['ID'] 				= intval($pie_pages[1]);
				$_p['post_title'] 		= __("Registration","pie-register");
				$_p['post_content'] 	= '[pie_register_form id="' . ( (intval($created_form_id) > 0) ? intval($created_form_id) : "0" ) . '" title="true" description="true" ]';
				$_p['post_status'] 		= 'publish';
				$_p['post_type'] 		= 'page';
				$_p['comment_status'] 	= 'closed';
				$_p['ping_status'] 		= 'closed';
				wp_update_post( $_p );
			}
			
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			
			$invitation_code_sql = "CREATE TABLE IF NOT EXISTS ".$codetable."(`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`created` DATE NOT NULL ,`modified` DATE NOT NULL ,`name` TEXT NOT NULL ,`count` INT( 5 ) NOT NULL ,`status` INT( 2 ) NOT NULL ,`code_usage` INT( 5 ) NOT NULL, `expiry_date` DATE NOT NULL) ENGINE = MYISAM ;"; 
			
			if(!$wpdb->query($invitation_code_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			$status = $wpdb->get_results( "SHOW COLUMNS FROM {$codetable}" ); #WPS
			if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			$check = 0;
			$check_expiry = 0;
			foreach($status as $key=>$val)
			{
				if(trim(strtolower($val->Field)) == "code_usage")
				{
					$check = 1;
				}
				if(trim(strtolower($val->Field)) == "usage")
				{
					$check = 2;
				}
				if(trim(strtolower($val->Field)) == "expiry_date")
				{
					$check_expiry = 1;
				}
			}
			
			if($check === 2)
			{
				if(!$wpdb->query("ALTER TABLE ".$codetable." CHANGE `usage` `code_usage` int(11) NULL")){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}				
			}
			
			if($check === 0)
			{
				if(!$wpdb->query("alter table ".$codetable." add column `code_usage` int(11) NULL")){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
			}

			if( $check_expiry === 0 && PIEREG_DB_VERSION > "3.0" )
			{
				if(!$wpdb->query("ALTER TABLE ".$codetable." ADD COLUMN `expiry_date` DATE NOT NULL")){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
			}
			
			$redirect_settings_table_name = $wpdb->prefix."pieregister_redirect_settings";
			$redirect_table_sql = "CREATE TABLE IF NOT EXISTS `".$redirect_settings_table_name."` (
									  `id` int(11) NOT NULL AUTO_INCREMENT,
									  `user_role` varchar(100) NOT NULL,
									  `logged_in_url` text NOT NULL,
									  `logged_in_page_id` int(11) NOT NULL,
									  `log_out_url` text NOT NULL,
									  `log_out_page_id` int(11) NOT NULL,
									  `status` bit(1) NOT NULL DEFAULT b'1',
									  PRIMARY KEY (`user_role`),
									  UNIQUE KEY `id` (`id`)
									) ENGINE=MYISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			
			if(!$wpdb->query($redirect_table_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}

			// email count
			$invite_code_emails_table_name = $wpdb->prefix."pieregister_invite_code_emails";
			$invite_code_emails_table_sql  = "CREATE TABLE IF NOT EXISTS $invite_code_emails_table_name (
											`id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
											`code_id` int NOT NULL,
											`email_address` varchar(150) NOT NULL,
											FOREIGN KEY (`code_id`) REFERENCES $codetable(`id`)
											) ENGINE=MYISAM;";
			
			if(!$wpdb->query($invite_code_emails_table_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			// ver 3.5.4
			$custom_role_table_name = $wpdb->prefix."pieregister_custom_user_roles";
			$custom_role_table_sql  = "CREATE TABLE IF NOT EXISTS $custom_role_table_name (
											`id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
											`role_key` varchar(150) NOT NULL UNIQUE,
											`role_name` varchar(150) NOT NULL,
											`wp_role_name` varchar(150) NOT NULL
											) ENGINE=MYISAM;";
			
			if(!$wpdb->query($custom_role_table_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			$lockdowns_table_name = $wpdb->prefix."pieregister_lockdowns";
			$lockdowns_table_sql = "CREATE TABLE IF NOT EXISTS `".$lockdowns_table_name."` (
									  `id` int(11) NOT NULL AUTO_INCREMENT,
									  `user_id` int(11) NOT NULL,
									  `login_attempt` int(11) NOT NULL,
									  `attempt_from` varchar(56) NOT NULL,
									  `is_security_captcha` tinyint(4) NOT NULL DEFAULT '0',
									  `attempt_time` datetime NOT NULL,
									  `release_time` datetime NOT NULL,
									  `user_ip` varchar(30) NOT NULL,
									  PRIMARY KEY (`id`)
									) ENGINE=MYISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			
			if(!$wpdb->query($lockdowns_table_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			$lockdowns_all_columns = "SHOW COLUMNS FROM ".$lockdowns_table_name . " LIKE 'attempt_from'";
			$lockdowns_get_columns = $wpdb->get_results($lockdowns_all_columns);
			if(empty($lockdowns_get_columns)){	
				$lockdowns_add_column = "ALTER TABLE `".$lockdowns_table_name."` ADD attempt_from varchar(56) NOT NULL AFTER login_attempt";
				$wpdb->query($lockdowns_add_column);
			}
		
			/*
				*	Create Pie Register Stats array If Do not Exist
			*/
			$piereg_stats = get_option(PIEREG_STATS_OPTION);
			
			$new_piereg_stats = array();
			$new_piereg_stats['login']['view'] = (isset($piereg_stats['login']['view'])?$piereg_stats['login']['view']:0);
			$new_piereg_stats['login']['used'] = (isset($piereg_stats['login']['used'])?$piereg_stats['login']['used']:0);
			
			$new_piereg_stats['forgot']['view'] = (isset($piereg_stats['forgot']['view'])?$piereg_stats['forgot']['view']:0);
			$new_piereg_stats['forgot']['used'] = (isset($piereg_stats['forgot']['view'])?$piereg_stats['forgot']['view']:0);
			
			$new_piereg_stats['register']['view'] = (isset($piereg_stats['register']['view'])?$piereg_stats['register']['view']:0);
			$new_piereg_stats['register']['used'] = (isset($piereg_stats['register']['used'])?$piereg_stats['register']['used']:0);
			
			update_option(PIEREG_STATS_OPTION,$new_piereg_stats);
			unset($new_piereg_stats);
			unset($piereg_stats);
			
			/*
				*	Save Currency name and array
			*/
			$this->piereg_save_currency();
			
			/*
			 * Removed in v 3.0.10
			 *
			 *
				//Adding active meta to existing users
				$blogusers = get_users();
				foreach ($blogusers as $user) 
				{
					update_user_meta( $user->ID, 'active', 1);
				}
			*/
			//  From 3.7.0.5 - Updating Firebase API Key for PR Android App
			update_option('pie_app_firebase_api_key', 'AAAAn4vZc1A:APA91bH8vNDyZZMqu1UsZii2EGEGrdmDwTg4rqvCzfJ1jSkd28sl_NUelOPg2JqPKC8r3lK2j082xnsFerOwrX_-1524KmqeDUECZbxPPTojt4Uzp-d2ZbQsRiega3-zDm3PKypp_B6P' );
			
			# updating pieregister db version 
			update_option('piereg_plugin_db_version',PIEREG_DB_VERSION);

			// Create error directory for pieregister error logs
			$upload_dir = wp_upload_dir();
			$temp_dir = realpath($upload_dir['basedir'])."/pie-logs/";
			wp_mkdir_p($temp_dir);
			
		}
		
		function activate_pieregister_license_key(){
			global $wpdb;
			$old_pr_keys = get_option("api_manager_example");
			
			$global_options = array();
			$global_options['api_key'] = ( (isset($old_pr_keys['api_key']) && !empty($old_pr_keys['api_key'])) ? $old_pr_keys['api_key'] : "" );
			$global_options['activation_email'] = ( (isset($old_pr_keys['activation_email']) && !empty($old_pr_keys['activation_email'])) ? $old_pr_keys['activation_email'] : "" );
			
			if( empty($global_options['api_key']) || empty($old_pr_keys['activation_email']) ) :
				update_option( 'api_manager_example', $global_options );
				
				if( file_exists(PIEREG_DIR_NAME . '/classes/api/class-wc-api-manager-passwords.php') )
					require_once(PIEREG_DIR_NAME . '/classes/api/class-wc-api-manager-passwords.php');
		
				$API_Manager_Example_Password_Management = new API_Manager_Example_Password_Management();
				// Generate a unique installation $instance id
				$instance = $API_Manager_Example_Password_Management->generate_password( 12, false );
				
				$single_options = array(
					'piereg_api_manager_product_id' 			=> 'Pie-Register-pro',
					'piereg_api_manager_instance' 				=> $instance,
					'api_manager_example_deactivate_checkbox' 	=> 'on',
					'piereg_api_manager_activated' 				=> 'Deactivated',
					);
				foreach ( $single_options as $key => $value ) {
					update_option( $key, $value );
				}
				
				$curr_ver = get_option( $this->piereg_api_manager_version_name );
				// checks if the current plugin version is lower than the version being installed
				if ( version_compare( $this->version, $curr_ver, '>' ) ) {
					// update the version
					update_option( $this->piereg_api_manager_version_name, $this->version );
				}
			endif;
		}
		function install_default_reg_form(){
			$form_id = get_option("piereg_form_fields_id");
			$all_forms_info = $this->get_pr_forms_info();
			
			if(empty($form_id) || count($all_forms_info) == 0 )
			{
				$form_id = intval($form_id)+1;//increment form id
				update_option("piereg_form_fields_id",$form_id);//updated form id
				
				update_option('piereg_form_free_id', $form_id); // assignining reg for free ver
				
				$pie_fields = get_option("pie_fields");//get reg form
				$pie_fields = ( (empty($pie_fields)) ? get_option("pie_fields_default") : $pie_fields );//get default form
				
				
				// add membership field if paypal payment is ON.
				$current 				= get_option(OPTION_PIE_REGISTER);
				$pie_plugin_db_version 	= get_option('piereg_plugin_db_version');
				$pie_plugin_db_version 	= explode('.',$pie_plugin_db_version);		
				if($pie_plugin_db_version[0] == 2 && isset($current['enable_paypal'],$current['paypal_butt_id']) && ($current['enable_paypal'] == 1 && !empty($current['paypal_butt_id'])) )
				{
					$pie_fields		= maybe_unserialize($pie_fields);
					
					$pie_fields_submit = $pie_fields['submit'];
					unset($pie_fields['submit']);
					
					$int_m = count($pie_fields);
					
					$membership_field['id'] 					= $int_m;
					$membership_field['type'] 					= "pricing";
					$membership_field['label'] 					= "Membership";
					$membership_field['desc'] 					= ""; 
					$membership_field['allow_payment_gateways'] = array("PaypalStandard");
					$membership_field['validation_message'] 	= ""; 
					$membership_field['css'] 					= ""; 
					$membership_field['field_as'] 				= 1;
					
					array_push($pie_fields,$membership_field);
					$pie_fields['submit'] = $pie_fields_submit;
					
					if( !is_serialized( $pie_fields ) )
					{
						serialize($pie_fields);
					}
				}
				// add membership field if paypal payment is ON.
				
				
				if( is_serialized( $pie_fields ) ) {
					update_option("piereg_form_fields_".$form_id, $pie_fields );//install default form
				} else {
					update_option("piereg_form_fields_".$form_id, serialize($pie_fields) );//install default form
				}
				$_field['Id'] = $form_id;
				$_field['Title'] = ( (isset($pie_fields['form']['label']) && !empty($pie_fields['form']['label']) ) ? $pie_fields['form']['label'] : 'Registration Form' );
				$_field['Views'] = "0";
				$_field['Entries'] = "0";
				$_field['Status'] = "enable";
				update_option("piereg_form_field_option_".$form_id, $_field);
			}
			return $form_id;
		}
		function getDefaultMeta()
		{
			$structure = array();
			$structure["text"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="length_%d%">'.__("Length","pie-register").'</label><input type="text" name="field[%d%][length]" id="length_%d%" class="input_fields character_fields field_length numeric"></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_rule_%d%">'.__("Validation Rule","pie-register").'</label><select name="field[%d%][validation_rule]" id="validation_rule_%d%"><option>'.__("None","pie-register").'</option><option value="number">'.__("Number","pie-register").'</option><option value="alphabetic">'.__("Alphabetic","pie-register").'</option><option value="alphanumeric">'.__("Alphanumeric","pie-register").'</option><option value="email">'.__("Email","pie-register").'</option><option value="website">'.__("Website","pie-register").'</option><option value="standard">'.__("USA Format","pie-register").' (xxx) (xxx-xxxx)</option><option value="international">'.__("Phone International","pie-register").' xxx-xxx-xxxx</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if($this->piereg_field_visbility_addon_active){
				$structure["text"] = apply_filters('pie_addon_field_visibility_settings', $structure["text"]);
			}else{
				$structure["text"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			
			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["text"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			$structure["text"] .= '</div></div>';
			
			$structure["username"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="Username" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"><input type="hidden" id="default_username"></div>';
			
			$structure["username"] .='</div></div>';
			
			$structure["default"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';
			
			$structure["aim"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="AIM" id="label_%d%" class="input_fields field_label"></div></div></div>';
			
			
			$structure["url"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Website" class="input_fields field_label"></div></div></div>';
			
			
			$structure["yim"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Yahoo IM" class="input_fields field_label"></div></div></div>';
			
			
			$structure["description"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="About Yourself" class="input_fields field_label"></div></div></div>';
			
			
			$structure["jabber"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="Jabber / Google Talk" id="label_%d%" class="input_fields field_label"></div></div></div>';
			$structure["password"] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][validation_rule]"><input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="Password" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="label2_%d%">'.__("Label2","pie-register").'</label><input type="text" name="field[%d%][label2]" value="Confrim Password" id="label2_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="placeholder2_%d%">Placeholder 2</label><input type="text" name="field[%d%][placeholder2]" id="placeholder2_%d%" class="input_fields field_placeholder2"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="password_generator_%d%">'.__("Password Generator","pie-register").'</label><input value="1" type="checkbox" name="field[%d%][password_generator]" id="password_generator_%d%" class="checkbox_fields"></div><div class="advance_fields"><label for="show_meter_%d%">'.__("Show Strength Meter","pie-register").'</label><select class="strength_meter show_meter checkbox_fields" name="field[%d%][show_meter]" id="show_meter_%d%"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div><div class="strength_labels_div"><div class="advance_fields"><label for="pass_strength_indicator_label_%d%">'.__("Strength Indicator",'pie-register').'</label><input type="text" name="field[%d%][pass_strength_indicator_label]" id="pass_strength_indicator_label_%d%" class="input_fields" value="Strength Indicator" /></div><div class="advance_fields"><label for="pass_very_weak_label_%d%">'.__("Very Weak",'pie-register').'</label><input type="text" name="field[%d%][pass_very_weak_label]" id="pass_very_weak_label_%d%" class="input_fields" value="Very Weak" /></div><div class="advance_fields"><label for="pass_weak_label_%d%">'.__("Weak",'pie-register').'</label><input type="text" name="field[%d%][pass_weak_label]" id="pass_weak_label_%d%" class="input_fields" value="Weak" /></div><div class="advance_fields"><label for="pass_medium_label_%d%">'.__("Medium",'pie-register').'</label><input type="text" name="field[%d%][pass_medium_label]" id="pass_medium_label_%d%" class="input_fields" value="Medium" /></div><div class="advance_fields"><label for="pass_strong_label_%d%">'.__("Strong",'pie-register').'</label><input type="text" name="field[%d%][pass_strong_label]" id="pass_strong_label_%d%" class="input_fields" value="Strong" /></div><div class="advance_fields"><label for="pass_mismatch_label_%d%">'.__("Mismatch",'pie-register').'</label><input type="text" name="field[%d%][pass_mismatch_label]" id="pass_mismatch_label_%d%" class="input_fields" value="Mismatch" /></div><div class="advance_fields"><label for="restrict_strength_%d%">'.__("Minimum Strength","pie-register").'</label><select class="show_meter" name="field[%d%][restrict_strength]" id="restrict_strength_%d%"><option value="1" selected="selected">'.__("Very weak","pie-register").'</option><option value="2">'.__("Weak","pie-register").'</option><option value="3">'.__("Medium","pie-register").'</option><option value="4">'.__("Strong","pie-register").'</option></select></div><div class="advance_fields"><label for="strength_message_%d%">'.__("Strength Message","pie-register").'</label><input type="text" name="field[%d%][strength_message]" id="strength_message_%d%" class="input_fields" value="Weak Password"></div></div>';
			
			$structure["password"] .= '</div></div>';
			
			$structure['email']	= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="Email" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields confrim_email_label2"><label for="label2_%d%">'.__("Label2","pie-register").'</label><input type="text" name="field[%d%][label2]" value="Confrim Email" id="label2_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="confirm_email_%d%">'.__("Confirm Email","pie-register").'</label><input name="field[%d%][confirm_email]" id="confirm_email" value="%d%" type="checkbox" class="checkbox_fields"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields confrim_email_label2"><label for="placeholder2_%d%">'.__("Placeholder 2","pie-register").'</label><input type="text" name="field[%d%][placeholder2]" id="placeholder2_%d%" class="input_fields field_placeholder2"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';
			
			if($this->piereg_field_visbility_addon_active ){
				$structure["email"] = apply_filters('pie_addon_field_visibility_settings', $structure["email"],'email');
			}
			
			$structure['email']	.= '</div></div>';
			
			$structure["textarea"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="rows_%d%">'.__("Rows","pie-register").'</label><input type="text" value="8" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields field_rows numeric"></div><div class="advance_fields"><label for="cols_%d%">'.__("Columns","pie-register").'</label><input type="text" value="73" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields field_cols numeric"></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if($this->piereg_field_visbility_addon_active ){
				$structure["textarea"] = apply_filters('pie_addon_field_visibility_settings', $structure["textarea"]);
			}else{
				$structure["textarea"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
					
			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["textarea"] .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			$structure["textarea"] .= '</div></div>';
			
			
			$structure["dropdown"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields multi_options sel_options_%d%"><label for="display_%d%">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">'.__("Value","pie-register").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>'.__("Checked","pie-register").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'radio\',jQuery(this));">+</a><!--<a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a>--></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">'.__("List Type","pie-register").'</label><select name="field[%d%][list_type]" id="list_type_%d%"><option value="None">'.__("None","pie-register").'</option><option value="country">'.__("Country","pie-register").'</option><option value="us_states">'.__("US States","pie-register").'</option><option value="can_states">'.__("Canadian States","pie-register").'</option> </select></div>';

			if($this->piereg_field_visbility_addon_active ){
				$structure["dropdown"] = apply_filters('pie_addon_field_visibility_settings', $structure["dropdown"]);
			}else{
				$structure["dropdown"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["dropdown"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			$structure["dropdown"] .= '</div></div>';
			
			
			$structure["multiselect"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields multi_options sel_options_%d%"><label for="display_%d%">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">'.__("Value","pie-register").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>'.__("Checked","pie-register").'</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'checkbox\',jQuery(this));">+</a><!--<a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a>--></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">'.__("List Type","pie-register").'</label><select name="field[%d%][list_type]" id="list_type_%d%"><option value="None">'.__("None","pie-register").'</option><option value="country">'.__("Country","pie-register").'</option><option value="us_states">'.__("US States","pie-register").'</option><option value="can_states">'.__("Canadian States","pie-register").'</option></select></div>';

			if($this->piereg_field_visbility_addon_active ){
				$structure["multiselect"] = apply_filters('pie_addon_field_visibility_settings', $structure["multiselect"]);	
			}else{
				$structure["multiselect"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}				
			
			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["multiselect"] .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			
			$structure["multiselect"] .= '</div></div>';
						
			
			$structure["number"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="min_%d%">'.__("Min","pie-register").'</label><input type="text" name="field[%d%][min]" id="min_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label for="max_%d%">'.__("Max","pie-register").'</label><input type="text" name="field[%d%][max]" id="max_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if($this->piereg_field_visbility_addon_active ){
				$structure["number"] = apply_filters('pie_addon_field_visibility_settings', $structure["number"]);	
			}else{
				$structure["number"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
		    if ($this->piereg_bbpress_addon_active){
				$structure["number"] .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			
			$structure["number"] .= '</div></div>';
			
			$structure["checkbox"] = '<div class="fields_main">  <div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div>  <div class="advance_fields multi_options sel_options_%d%"><label for="display_%d%">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields checkbox_option_display"><label for="value_%d%">'.__("Value","pie-register").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields checkbox_option_value"><label>'.__("Checked","pie-register").'</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="checkbox_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'checkbox\');">+</a></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if($this->piereg_field_visbility_addon_active ){
				$structure["checkbox"] = apply_filters('pie_addon_field_visibility_settings', $structure["checkbox"]);
			}else{
				$structure["checkbox"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["checkbox"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			$structure["checkbox"] .= '</div></div>';
			
			$structure["radio"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields multi_options sel_options_%d%"><label for="display_%d%">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields radio_option_display"><label for="value_%d%">'.__("Value","pie-register").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields radio_option_value"><label>'.__("Checked","pie-register").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="radio_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'radio\');">+</a></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if( $this->piereg_field_visbility_addon_active ){
				$structure["radio"] = apply_filters('pie_addon_field_visibility_settings', $structure["radio"]);	
			}else{
				$structure["radio"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["radio"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			$structure["radio"] .= '</div></div>';

			$structure["html"] 	= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><textarea rows="8" id="htmlbox_%d%" class="ckeditor" name="field[%d%][html]" cols="16"></textarea></div></div></div>';
			
			$structure["sectionbreak"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div>';

			$structure["sectionbreak"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			
			$structure["sectionbreak"] .= '</div></div>';
			
			$structure["pagebreak"] 	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="advance_fields"><label for="next_button_%d%">'.__("Next Button","pie-register").'</label><div class="calendar_icon_type">  <input class="next_button" type="radio" id="next_button_%d%_text" name="field[%d%][next_button]" value="text" checked="checked">  <label for="next_button_%d%_text">'.__("Text","pie-register").' </label>  <input class="next_button" type="radio" id="next_button_%d%_url" name="field[%d%][next_button]" value="url"><label for="next_button_%d%_url"> '.__("Image","pie-register").'</label></div><div id="next_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="next_button_%d%_url"> '.__("Image URL","pie-register").': </label>  <input type="text" name="field[%d%][next_button_url]" class="input_fields" id="next_button_%d%_url"></div><div id="next_button_text_container_%d%" style="float:left;clear: both;">  <label for="next_button_%d%_text"> '.__("Text","pie-register").': </label>  <input type="text" name="field[%d%][next_button_text]" value="Next" class="input_fields" id="next_button_%d%_text"></div></div><div class="advance_fields"><label for="prev_button_%d%">'.__("Previous Button","pie-register").'</label><div class="calendar_icon_type">  <input class="prev_button" type="radio" id="prev_button_%d%_text" name="field[%d%][prev_button]" value="text" checked="checked">  <label for="prev_button_%d%_text">'.__("Text","pie-register").' </label>  <input class="prev_button" type="radio" id="prev_button_%d%_url" name="field[%d%][prev_button]" value="url">  <label for="prev_button_%d%_url"> '.__("Image","pie-register").'</label></div><div id="prev_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="prev_button_%d%_url"> '.__("Image URL","pie-register").': </label>  <input type="text" name="field[%d%][prev_button_url]" class="input_fields" id="prev_button_%d%_url"></div><div id="prev_button_text_container_%d%" style="float:left;clear: both;">  <label for="prev_button_%d%_text"> '.__("Text","pie-register").': </label>  <input type="text" name="field[%d%][prev_button_text]" value="Previous" class="input_fields" id="prev_button_%d%_text"></div></div></div></div>';
						
			$structure['name']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="First Name" id="label_%d%" class="input_fields field_label"><input type="hidden" name="field[%d%][validation_rule]"></div><div class="advance_fields"><label for="label2_%d%">'.__("Label2","pie-register").'</label><input type="text" name="field[%d%][label2]" value="Last Name" id="label2_%d%" class="input_fields field_label2"></div><div class="advance_fields"><label for="length_%d%">'.__("Length","pie-register").'</label><input type="text" name="field[%d%][length]" id="length_%d%" class="input_fields character_fields field_length numeric"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="placeholder2_%d%">'.__("Placeholder 2","pie-register").'</label><input type="text" name="field[%d%][placeholder2]" id="placeholder2_%d%" class="input_fields field_placeholder2"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if( $this->piereg_field_visbility_addon_active ){
				$structure["name"] = apply_filters('pie_addon_field_visibility_settings', $structure["name"]);
			}else{
				$structure["name"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["name"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

		$structure['name'] .= '</div></div>';
	
		$structure['time']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="time_type_%d%">'.__("List Type","pie-register").'</label><select class="time_format" name="field[%d%][time_type]" id="time_type_%d%"><option value="12">'.__("12 hour","pie-register").'</option><option value="24">'.__("24 hour","pie-register").'</option></select></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

		if( $this->piereg_field_visbility_addon_active ){
			$structure["time"] = apply_filters('pie_addon_field_visibility_settings', $structure["time"]);
		}else{
			$structure["time"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		// if bbpress plugin activated
		if ($this->piereg_bbpress_addon_active){
			$structure["time"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}			

		$structure['time'] .= '</div></div>';	
		
		$structure['website']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		$structure['upload']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="file_types_%d%">'.__("File Types","pie-register").'</label><input type="text" name="field[%d%][file_types]" id="file_types_%d%" class="input_fields"><a class="info" href="javascript:;">'.__("Separated with commas","pie-register").' (i.e. jpg, gif, png, pdf)</a></div>';
		
		$structure['upload']	.= '<div class="advance_fields"><label for="file_size_%d%">'.__("File Size","pie-register").'</label><input type="number" class="input_fields" disabled><a class="info" href="javascript:;" style="color:red;">'.__("Available in premium version","pie-register").'</a></div>';
		
		$structure['upload']	.= '<div clss="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

		if( $this->piereg_field_visbility_addon_active ){
			$structure["upload"] = apply_filters('pie_addon_field_visibility_settings', $structure["upload"]);
		}else{
			$structure["upload"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		$structure['upload'] .= '</div>';
		
		$structure['profile_pic'] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><input type="hidden" id="default_profile_pic"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div clss="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

		if( $this->piereg_field_visbility_addon_active ){
			$structure["profile_pic"] = apply_filters('pie_addon_field_visibility_settings', $structure["profile_pic"]);
		}else{
			$structure["profile_pic"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		// if bbpress plugin activated
		if ($this->piereg_bbpress_addon_active){
			$structure["profile_pic"] .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		$structure['profile_pic'] .= '</div></div>';
		
		$structure['address']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="address_type_%d%">'.__("List Type","pie-register").'</label><select class="address_type" name="field[%d%][address_type]" id="address_type_%d%"><option value="International">'.__("International","pie-register").'</option><option value="United States">'.__("United States","pie-register").'</option><option value="Canada">'.__("Canada","pie-register").'</option></select></div><div id="default_country_div_%d%" class="advance_fields"> <label for="default_country_%d%">'.__("Default Country","pie-register").'</label><select class="default_country" name="field[%d%][default_country]" id="default_country_%d%"><option value="" selected="selected"></option><option value="Afghanistan">'.__("Afghanistan","pie-register").'</option><option value="Albania">'.__("Albania","pie-register").'</option><option value="Algeria">'.__("Algeria","pie-register").'</option><option value="American Samoa">'.__("American Samoa","pie-register").'</option><option value="Andorra">'.__("Andorra","pie-register").'</option><option value="Angola">'.__("Angola","pie-register").'</option><option value="Antigua and Barbuda">'.__("Antigua and Barbuda","pie-register").'</option><option value="Argentina">'.__("Argentina","pie-register").'</option><option value="Armenia">'.__("Armenia","pie-register").'</option><option value="Australia">'.__("Australia","pie-register").'</option><option value="Austria">'.__("Austria","pie-register").'</option><option value="Azerbaijan">'.__("Azerbaijan","pie-register").'</option><option value="Bahamas">'.__("Bahamas","pie-register").'</option><option value="Bahrain">'.__("Bahrain","pie-register").'</option><option value="Bangladesh">'.__("Bangladesh","pie-register").'</option><option value="Barbados">'.__("Barbados","pie-register").'</option><option value="Belarus">'.__("Belarus","pie-register").'</option><option value="Belgium">'.__("Belgium","pie-register").'</option><option value="Belize">'.__("Belize","pie-register").'</option><option value="Benin">'.__("Benin","pie-register").'</option><option value="Bermuda">'.__("Bermuda","pie-register").'</option><option value="Bhutan">'.__("Bhutan","pie-register").'</option><option value="Bolivia">'.__("Bolivia","pie-register").'</option><option value="Bosnia and Herzegovina">'.__("Bosnia and Herzegovina","pie-register").'</option><option value="Botswana">'.__("Botswana","pie-register").'</option><option value="Brazil">'.__("Brazil","pie-register").'</option><option value="Brunei">'.__("Brunei","pie-register").'</option><option value="Bulgaria">'.__("Bulgaria","pie-register").'</option><option value="Burkina Faso">'.__("Burkina Faso","pie-register").'</option><option value="Burundi">'.__("Burundi","pie-register").'</option><option value="Cambodia">'.__("Cambodia","pie-register").'</option><option value="Cameroon">'.__("Cameroon","pie-register").'</option><option value="Canada">'.__("Canada","pie-register").'</option><option value="Cape Verde">'.__("Cape Verde","pie-register").'</option><option value="Central African Republic">'.__("Central African Republic","pie-register").'</option><option value="Chad">'.__("Chad","pie-register").'</option><option value="Chile">'.__("Chile","pie-register").'</option><option value="China">'.__("China","pie-register").'</option><option value="Colombia">'.__("Colombia","pie-register").'</option><option value="Comoros">'.__("Comoros","pie-register").'</option><option value="Congo, Democratic Republic of the">'.__("Congo, Democratic Republic of the","pie-register").'</option><option value="Congo, Republic of the">'.__("Congo, Republic of the","pie-register").'</option><option value="Costa Rica">'.__("Costa Rica","pie-register").'</option><option value="Côte d\'Ivoire">'.__("Côte d\'Ivoire","pie-register").'</option><option value="Croatia">'.__("Croatia","pie-register").'</option><option value="Cuba">'.__("Cuba","pie-register").'</option><option value="Cyprus">'.__("Cyprus","pie-register").'</option><option value="Czech Republic">'.__("Czech Republic","pie-register").'</option><option value="Denmark">'.__("Denmark","pie-register").'</option><option value="Djibouti">'.__("Djibouti","pie-register").'</option><option value="Dominica">'.__("Dominica","pie-register").'</option><option value="Dominican Republic">'.__("Dominican Republic","pie-register").'</option><option value="East Timor">'.__("East Timor","pie-register").'</option><option value="Ecuador">'.__("Ecuador","pie-register").'</option><option value="Egypt">'.__("Egypt","pie-register").'</option><option value="El Salvador">'.__("El Salvador","pie-register").'</option><option value="Equatorial Guinea">'.__("Equatorial Guinea","pie-register").'</option><option value="Eritrea">'.__("Eritrea","pie-register").'</option><option value="Estonia">'.__("Estonia","pie-register").'</option><option value="Eswatini">'.__("Eswatini","pie-register").'</option><option value="Ethiopia">'.__("Ethiopia","pie-register").'</option><option value="Fiji">'.__("Fiji","pie-register").'</option><option value="Finland">'.__("Finland","pie-register").'</option><option value="France">'.__("France","pie-register").'</option><option value="Gabon">'.__("Gabon","pie-register").'</option><option value="The Gambia">'.__("The Gambia","pie-register").'</option><option value="Georgia">'.__("Georgia","pie-register").'</option><option value="Germany">'.__("Germany","pie-register").'</option><option value="Ghana">'.__("Ghana","pie-register").'</option><option value="Greece">'.__("Greece","pie-register").'</option><option value="Greenland">'.__("Greenland","pie-register").'</option><option value="Grenada">'.__("Grenada","pie-register").'</option><option value="Guam">'.__("Guam","pie-register").'</option><option value="Guatemala">'.__("Guatemala","pie-register").'</option><option value="Guinea">'.__("Guinea","pie-register").'</option><option value="Guinea-Bissau">'.__("Guinea-Bissau","pie-register").'</option><option value="Guyana">'.__("Guyana","pie-register").'</option><option value="Haiti">'.__("Haiti","pie-register").'</option><option value="Honduras">'.__("Honduras","pie-register").'</option><option value="Hong Kong">'.__("Hong Kong","pie-register").'</option><option value="Hungary">'.__("Hungary","pie-register").'</option><option value="Iceland">'.__("Iceland","pie-register").'</option><option value="India">'.__("India","pie-register").'</option><option value="Indonesia">'.__("Indonesia","pie-register").'</option><option value="Iran">'.__("Iran","pie-register").'</option><option value="Iraq">'.__("Iraq","pie-register").'</option><option value="Ireland">'.__("Ireland","pie-register").'</option><option value="Israel">'.__("Israel","pie-register").'</option><option value="Italy">'.__("Italy","pie-register").'</option><option value="Jamaica">'.__("Jamaica","pie-register").'</option><option value="Japan">'.__("Japan","pie-register").'</option><option value="Jordan">'.__("Jordan","pie-register").'</option><option value="Kazakhstan">'.__("Kazakhstan","pie-register").'</option><option value="Kenya">'.__("Kenya","pie-register").'</option><option value="Kiribati">'.__("Kiribati","pie-register").'</option><option value="North Korea">'.__("North Korea","pie-register").'</option><option value="South Korea">'.__("South Korea","pie-register").'</option><option value="Kuwait">'.__("Kuwait","pie-register").'</option><option value="Kyrgyzstan">'.__("Kyrgyzstan","pie-register").'</option><option value="Laos">'.__("Laos","pie-register").'</option><option value="Latvia">'.__("Latvia","pie-register").'</option><option value="Lebanon">'.__("Lebanon","pie-register").'</option><option value="Lesotho">'.__("Lesotho","pie-register").'</option><option value="Liberia">'.__("Liberia","pie-register").'</option><option value="Libya">'.__("Libya","pie-register").'</option><option value="Liechtenstein">'.__("Liechtenstein","pie-register").'</option><option value="Lithuania">'.__("Lithuania","pie-register").'</option><option value="Luxembourg">'.__("Luxembourg","pie-register").'</option><option value="Madagascar">'.__("Madagascar","pie-register").'</option><option value="Malawi">'.__("Malawi","pie-register").'</option><option value="Malaysia">'.__("Malaysia","pie-register").'</option><option value="Maldives">'.__("Maldives","pie-register").'</option><option value="Mali">'.__("Mali","pie-register").'</option><option value="Malta">'.__("Malta","pie-register").'</option><option value="Marshall Islands">'.__("Marshall Islands","pie-register").'</option><option value="Mauritania">'.__("Mauritania","pie-register").'</option><option value="Mauritius">'.__("Mauritius","pie-register").'</option><option value="Mexico">'.__("Mexico","pie-register").'</option><option value="Micronesia">'.__("Micronesia","pie-register").'</option><option value="Moldova">'.__("Moldova","pie-register").'</option><option value="Monaco">'.__("Monaco","pie-register").'</option><option value="Mongolia">'.__("Mongolia","pie-register").'</option><option value="Montenegro">'.__("Montenegro","pie-register").'</option><option value="Morocco">'.__("Morocco","pie-register").'</option><option value="Mozambique">'.__("Mozambique","pie-register").'</option><option value="Myanmar">'.__("Myanmar","pie-register").'</option><option value="Namibia">'.__("Namibia","pie-register").'</option><option value="Nauru">'.__("Nauru","pie-register").'</option><option value="Nepal">'.__("Nepal","pie-register").'</option><option value="Netherlands">'.__("Netherlands","pie-register").'</option><option value="New Zealand">'.__("New Zealand","pie-register").'</option><option value="Nicaragua">'.__("Nicaragua","pie-register").'</option><option value="Niger">'.__("Niger","pie-register").'</option><option value="Nigeria">'.__("Nigeria","pie-register").'</option><option value="Norway">'.__("Norway","pie-register").'</option><option value="Northern Mariana Islands">'.__("Northern Mariana Islands","pie-register").'</option><option value="Oman">'.__("Oman","pie-register").'</option><option value="Pakistan">'.__("Pakistan","pie-register").'</option><option value="Palau">'.__("Palau","pie-register").'</option><option value="Palestine">'.__("Palestine","pie-register").'</option><option value="Panama">'.__("Panama","pie-register").'</option><option value="Papua New Guinea">'.__("Papua New Guinea","pie-register").'</option><option value="Paraguay">'.__("Paraguay","pie-register").'</option><option value="Peru">'.__("Peru","pie-register").'</option><option value="Philippines">'.__("Philippines","pie-register").'</option><option value="Poland">'.__("Poland","pie-register").'</option><option value="Portugal">'.__("Portugal","pie-register").'</option><option value="Puerto Rico">'.__("Puerto Rico","pie-register").'</option><option value="Qatar">'.__("Qatar","pie-register").'</option><option value="Republic of North Macedonia">'.__("Republic of North Macedonia","pie-register").'</option><option value="Romania">'.__("Romania","pie-register").'</option><option value="Russia">'.__("Russia","pie-register").'</option><option value="Rwanda">'.__("Rwanda","pie-register").'</option><option value="Saint Kitts and Nevis">'.__("Saint Kitts and Nevis","pie-register").'</option><option value="Saint Lucia">'.__("Saint Lucia","pie-register").'</option><option value="Saint Vincent and the Grenadines">'.__("Saint Vincent and the Grenadines","pie-register").'</option><option value="Samoa">'.__("Samoa","pie-register").'</option><option value="San Marino">'.__("San Marino","pie-register").'</option><option value="Sao Tome and Principe">'.__("Sao Tome and Principe","pie-register").'</option><option value="Saudi Arabia">'.__("Saudi Arabia","pie-register").'</option><option value="Senegal">'.__("Senegal","pie-register").'</option><option value="Serbia">'.__("Serbia","pie-register").'</option><option value="Seychelles">'.__("Seychelles","pie-register").'</option><option value="Sierra Leone">'.__("Sierra Leone","pie-register").'</option><option value="Singapore">'.__("Singapore","pie-register").'</option><option value="Slovakia">'.__("Slovakia","pie-register").'</option><option value="Slovenia">'.__("Slovenia","pie-register").'</option><option value="Solomon Islands">'.__("Solomon Islands","pie-register").'</option><option value="Somalia">'.__("Somalia","pie-register").'</option><option value="South Africa">'.__("South Africa","pie-register").'</option><option value="Spain">'.__("Spain","pie-register").'</option><option value="Sri Lanka">'.__("Sri Lanka","pie-register").'</option><option value="Sudan">'.__("Sudan","pie-register").'</option><option value="Sudan, South">'.__("Sudan, South","pie-register").'</option><option value="Suriname">'.__("Suriname","pie-register").'</option><option value="Sweden">'.__("Sweden","pie-register").'</option><option value="Switzerland">'.__("Switzerland","pie-register").'</option><option value="Syria">'.__("Syria","pie-register").'</option><option value="Taiwan">'.__("Taiwan","pie-register").'</option><option value="Tajikistan">'.__("Tajikistan","pie-register").'</option><option value="Tanzania">'.__("Tanzania","pie-register").'</option><option value="Thailand">'.__("Thailand","pie-register").'</option><option value="Togo">'.__("Togo","pie-register").'</option><option value="Tonga">'.__("Tonga","pie-register").'</option><option value="Trinidad and Tobago">'.__("Trinidad and Tobago","pie-register").'</option><option value="Tunisia">'.__("Tunisia","pie-register").'</option><option value="Turkey">'.__("Turkey","pie-register").'</option><option value="Turkmenistan">'.__("Turkmenistan","pie-register").'</option><option value="Tuvalu">'.__("Tuvalu","pie-register").'</option><option value="Uganda">'.__("Uganda","pie-register").'</option><option value="Ukraine">'.__("Ukraine","pie-register").'</option><option value="United Arab Emirates">'.__("United Arab Emirates","pie-register").'</option><option value="United Kingdom">'.__("United Kingdom","pie-register").'</option><option value="United States">'.__("United States","pie-register").'</option><option value="Uruguay">'.__("Uruguay","pie-register").'</option><option value="Uzbekistan">'.__("Uzbekistan","pie-register").'</option><option value="Vanuatu">'.__("Vanuatu","pie-register").'</option><option value="Vatican City">'.__("Vatican City","pie-register").'</option><option value="Venezuela">'.__("Venezuela","pie-register").'</option><option value="Vietnam">'.__("Vietnam","pie-register").'</option><option value="Virgin Islands, British">'.__("Virgin Islands, British","pie-register").'</option><option value="Virgin Islands, U.S.">'.__("Virgin Islands, U.S.","pie-register").'</option><option value="Yemen">'.__("Yemen","pie-register").'</option><option value="Zambia">'.__("Zambia","pie-register").'</option><option value="Zimbabwe">'.__("Zimbabwe","pie-register").'</option></select></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="hide_address2_%d%">'.__("Hide Address 2","pie-register").'</label><input onChange="checkEvents(this,\'address_address2_%d%\')" name="field[%d%][hide_address2]" id="hide_address2_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_address2_%d%" class="required"></label></div><div class="advance_fields"><label for="hide_state_%d%">'.__("Hide State","pie-register").'</label><input class="hide_state" name="field[%d%][hide_state]" id="hide_state_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_state_%d%" class="required"></label></div><div style="display:none;" id="default_state_div_%d%" class="advance_fields"><label for="default_state_%d%">'.__("Default State","pie-register").'</label><select id="us_states_%d%" style="display:none;" class="default_state us_states_%d%" name="field[%d%][us_default_state]"><option value="" selected="selected"></option><option value="Alabama">'.__("Alabama","pie-register").'</option><option value="Alaska">'.__("Alaska","pie-register").'</option><option value="Arizona">'.__("Arizona","pie-register").'</option><option value="Arkansas">'.__("Arkansas","pie-register").'</option><option value="California">'.__("California","pie-register").'</option><option value="Colorado">'.__("Colorado","pie-register").'</option><option value="Connecticut">'.__("Connecticut","pie-register").'</option><option value="Delaware">'.__("Delaware","pie-register").'</option><option value="District of Columbia">'.__("District of Columbia","pie-register").'</option><option value="Florida">'.__("Florida","pie-register").'</option><option value="Georgia">'.__("Georgia","pie-register").'</option><option value="Hawaii">'.__("Hawaii","pie-register").'</option><option value="Idaho">'.__("Idaho","pie-register").'</option><option value="Illinois">'.__("Illinois","pie-register").'</option><option value="Indiana">'.__("Indiana","pie-register").'</option><option value="Iowa">'.__("Iowa","pie-register").'</option><option value="Kansas">'.__("Kansas","pie-register").'</option><option value="Kentucky">'.__("Kentucky","pie-register").'</option><option value="Louisiana">'.__("Louisiana","pie-register").'</option><option value="Maine">'.__("Maine","pie-register").'</option><option value="Maryland">'.__("Maryland","pie-register").'</option><option value="Massachusetts">'.__("Massachusetts","pie-register").'</option><option value="Michigan">'.__("Michigan","pie-register").'</option><option value="Minnesota">'.__("Minnesota","pie-register").'</option><option value="Mississippi">'.__("Mississippi","pie-register").'</option><option value="Missouri">'.__("Missouri","pie-register").'</option><option value="Montana">'.__("Montana","pie-register").'</option><option value="Nebraska">'.__("Nebraska","pie-register").'</option><option value="Nevada">'.__("Nevada","pie-register").'</option><option value="New Hampshire">'.__("New Hampshire","pie-register").'</option><option value="New Jersey">'.__("New Jersey","pie-register").'</option><option value="New Mexico">'.__("New Mexico","pie-register").'</option><option value="New York">'.__("New York","pie-register").'</option><option value="North Carolina">'.__("North Carolina","pie-register").'</option><option value="North Dakota">'.__("North Dakota","pie-register").'</option><option value="Ohio">'.__("Ohio","pie-register").'</option><option value="Oklahoma">'.__("Oklahoma","pie-register").'</option><option value="Oregon">'.__("Oregon","pie-register").'</option><option value="Pennsylvania">'.__("Pennsylvania","pie-register").'</option><option value="Rhode Island">'.__("Rhode Island","pie-register").'</option><option value="South Carolina">'.__("South Carolina","pie-register").'</option><option value="South Dakota">'.__("South Dakota","pie-register").'</option><option value="Tennessee">'.__("Tennessee","pie-register").'</option><option value="Texas">'.__("Texas","pie-register").'</option><option value="Utah">'.__("Utah","pie-register").'</option><option value="Vermont">'.__("Vermont","pie-register").'</option><option value="Virginia">'.__("Virginia","pie-register").'</option><option value="Washington">'.__("Washington","pie-register").'</option><option value="West Virginia">'.__("West Virginia","pie-register").'</option><option value="Wisconsin">'.__("Wisconsin","pie-register").'</option><option value="Wyoming">'.__("Wyoming","pie-register").'</option><option value="Armed Forces Americas">'.__("Armed Forces Americas","pie-register").'</option><option value="Armed Forces Europe">'.__("Armed Forces Europe","pie-register").'</option><option value="Armed Forces Pacific">'.__("Armed Forces Pacific","pie-register").'</option></select><select id="can_states_%d%" style="display:none;" class="default_state can_states_%d%" name="field[%d%][canada_default_state]"><option value="" selected="selected"></option><option value="Alberta">'.__("Alberta","pie-register").'</option><option value="British Columbia">'.__("British Columbia","pie-register").'</option><option value="Manitoba">'.__("Manitoba","pie-register").'</option><option value="New Brunswick">'.__("New Brunswick","pie-register").'</option><option value="Newfoundland &amp; Labrador">'.__("Newfoundland and Labrador","pie-register").'</option><option value="Northwest Territories">'.__("Northwest Territories","pie-register").'</option><option value="Nova Scotia">'.__("Nova Scotia","pie-register").'</option><option value="Nunavut">'.__("Nunavut","pie-register").'</option><option value="Ontario">'.__("Ontario","pie-register").'</option><option value="Prince Edward Island">'.__("Prince Edward Island","pie-register").'</option><option value="Quebec">'.__("Quebec","pie-register").'</option><option value="Saskatchewan">'.__("Saskatchewan","pie-register").'</option><option value="Yukon">'.__("Yukon","pie-register").'</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

// pie-register-woocommerce Addon

        if ($this->woocommerce_and_piereg_wc_addon_active)
		{
			$structure['wc_billing_address'] 	= apply_filters("pieregister_create_woocommerce_billing_fields", "");
			$structure['wc_shipping_address'] 	= apply_filters("pieregister_create_woocommerce_shipping_fields", "");
		}

		if( $this->piereg_field_visbility_addon_active ){
			$structure["address"] = apply_filters('pie_addon_field_visibility_settings', $structure["address"]);
		}else{
			$structure["address"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		// if bbpress plugin activated
		if ($this->piereg_bbpress_addon_active){
			$structure["address"]  .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
		}

		$structure["address"] .= '</div></div>';	
			
			if( false )
			{
				// With Classic Recaptch which is deprecated since PR  ver 3.0 
				$structure['captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields piereg_recaptcha_skin"><label for="recaptcha_skin_%d%">'.__("Captcha Skin","pie-register").'</label><select class="show_in_profile checkbox_fields" name="field[%d%][recaptcha_skin]" id="recaptcha_skin_%d%"><option value="red" selected="selected">'.__("Red","pie-register").'</option><option value="white">'.__("White","pie-register").'</option><option value="clean">'.__("Clean","pie-register").'</option><option value="blackglass">'.__("Blackglass","pie-register").'</option></select></div><div class="advance_fields piereg_recaptcha_type"><label for="recaptcha_type_%d%">'.__("Captcha Type","pie-register").'</label><select name="field[%d%][recaptcha_type]" id="recaptcha_type_%d%"  class="show_in_profile checkbox_fields piereg_recaptcha_type"><option value="1" selected="selected">'.__("Classic ReCaptcha","pie-register").'</option><option value="2">'.__("No Captcha ReCaptcha","pie-register").'</option></select></div></div></div>';
			} 
			else 
			{
				$structure['captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields piereg_recaptcha_type"><input type="hidden" class="input_fields" name="field[%d%][recaptcha_type]" value="2"></div></div></div>';
			}
			
			$structure['math_captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" value="Math Captcha" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
			
			$structure['phone']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"> <label for="phone_format_%d%">'.__("Phone Format","pie-register").'</label><select class="phone_format" name="field[%d%][phone_format]" id="phone_format_%d%"><option value="standard">'.__("USA Format","pie-register").' (###) ###-####</option><option value="international">'.__("International","pie-register").'</option></select></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';
			
			if($this->piereg_field_visbility_addon_active){
				$structure["phone"] = apply_filters('pie_addon_field_visibility_settings', $structure["phone"]);
			}else{
				$structure["phone"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

				// if bbpress plugin activated
				if ($this->piereg_bbpress_addon_active){
					$structure["phone"] .=  '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
				}

			$structure['phone']	.= '</div></div>';
			
			include_once( $this->admin_path .  'includes/plugin.php' );

			# [The function that control is_plugin_active is not loaded before code below. 20-04-2015]
			if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") ){
				$structure['two_way_login_phone'] = '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="international" class="input_fields" name="field[%d%][phone_format]"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label" value="2Way Login Phone #"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16">'.__("Please do not use the + sign. \n e.g. 4155551212 (USA), 07400123456 (GB).","pie-register").'</textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><!--<div class="advance_fields"> <label for="phone_format_%d%">'.__("Phone Format","pie-register").'</label><select class="phone_format" name="field[%d%][phone_format]" id="phone_format_%d%"><option value="standard">'.__("USA Format","pie-register").' (###) ###-####</option><option value="international">'.__("International","pie-register").'</option></select></div>--><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><input type="hidden" id="default_two_way_login_phone">';

				if($this->piereg_field_visbility_addon_active){
					$structure["two_way_login_phone"] = apply_filters('pie_addon_field_visibility_settings', $structure["two_way_login_phone"]);
				}else{
					$structure["two_way_login_phone"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
				}
				
				$structure['two_way_login_phone'] .= '</div></div>';
			}
			
			$structure['date']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"> <label for="date_type_%d%">'.__("Date Format","pie-register").'</label><select class="date_format" name="field[%d%][date_format]" id="date_format_%d%"><option value="mm/dd/yy">mm/dd/yy</option><option value="dd/mm/yy">dd/mm/yy</option><option value="dd-mm-yy">dd-mm-yy</option><option value="dd.mm.yy">dd.mm.yy</option><option value="yy/mm/dd">yy/mm/dd</option><option value="yy.mm.dd">yy.mm.dd</option></select></div><div class="advance_fields"> <label for="date_type_%d%">'.__("Date Input Type","pie-register").'</label><select class="date_type" name="field[%d%][date_type]" id="date_type_%d%"><option value="datefield">'.__("Date Field","pie-register").'</option><option value="datepicker">'.__("Date Picker","pie-register").'</option><option value="datedropdown">'.__("Date Drop Down","pie-register").'</option></select></div><div style="display:none;" id="icon_div_%d%" class="advance_fields"> <label for="date_type_%d%">&nbsp;</label><div class="calendar_icon_type"><input class="calendar_icon" type="radio" id="calendar_icon_%d%_none" name="field[%d%][calendar_icon]" value="none" checked="checked"><label for="calendar_icon_%d%_none"> '.__("No Icon","pie-register").' </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_calendar" name="field[%d%][calendar_icon]" value="calendar"><label for="calendar_icon_%d%_calendar"> '.__("Calendar Icon","pie-register").' </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_custom" name="field[%d%][calendar_icon]" value="custom"><label for="calendar_icon_%d%_custom"> '.__("Custom Icon","pie-register").' </label></div><div id="icon_url_container_%d%" style="display: none;float:left;clear: both;">  <label for="cfield_calendar_icon_%d%_url"> '.__("Image URL","pie-register").': </label>  <input type="text" class="input_fields" name="field[%d%][calendar_icon_url]" id="cfield_calendar_icon_%d%_url"></div></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if( $this->piereg_field_visbility_addon_active ){
				$structure["date"] = apply_filters('pie_addon_field_visibility_settings', $structure["date"]);
			}else{
				$structure["date"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			// if bbpress plugin activated
			if ($this->piereg_bbpress_addon_active){
				$structure["date"] .= '<div class="advance_fields"><label for="show_in_bbpress_%d%">'.__("Show in bbPress","pie-register").'</label><select name="field[%d%][show_in_bbpress]" id="show_in_bbpress_%d%"  class="show_in_bbpress checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}
			$structure["date"] .= '</div></div>';
			
			$structure['list'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="rows_%d%">'.__("Rows","pie-register").'</label><input type="text" value="1" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields list_rows numeric greaterzero"></div><div class="advance_fields"><label for="cols_%d%">'.__("Columns","pie-register").'</label><input type="text" value="1" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields list_cols numeric greaterzero"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			if( $this->piereg_field_visbility_addon_active ){
				$structure["list"] = apply_filters('pie_addon_field_visibility_settings', $structure["list"]);
			}else{
				$structure["list"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';
			}

			$structure["list"] .= '</div></div>'; 

			// dropdown_ur
			$structure["custom_role"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div>  <div class="advance_fields multi_options sel_options_%d% role_options"><label for="display_%d%">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields radio_option_display"><label class="label-role" for="value_%d%">'.__("Value","pie-register").'</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields radio_option_value"><label class="label-role" for="custom_role_%d%">'.__("Role","pie-register").'</label>';

			$structure["custom_role"] .= '<select class="checkbox_fields select-role" id="role_selection_%d%" name="field[%d%][role_selection][]" >';
						
			global $wp_roles;
			
			$role = $wp_roles->roles;
			$wp_default_user_role = get_option("default_role");
			
			foreach($role as $key=>$value)
			{
				$structure["custom_role"] .= '<option value="'.$key.'"';
				$structure["custom_role"] .= '>'.$value['name'].'</option>';
			}

			$structure["custom_role"] .= '</select>';

			$structure["custom_role"] .= '<label>'.__("Checked","pie-register").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="radio_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'radio\');">+</a></div>';
			
			$structure["custom_role"] .= '<div class="advance_fields"><label>'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

			$structure["custom_role"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';

			$structure["custom_role"] .= '</div></div>';


			$structure['hidden'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="default_value_%d%">'.__("Default Value","pie-register").'</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"><a href="javascript:;" class="piereg-toggle-tags  piereg-not-show-tags" ><i class="fa fa-tags"></i> <span>Show Smart Tags</span></a><div class="piereg-toggle-tag-display" style="display:none;"><ul class="piereg-others"><li class="heading">Other</li><li class="smart-tag-field" data-type="other" data-field_name="admin_email">Site Admin Email</li><li class="smart-tag-field" data-type="other" data-field_name="site_name">Site Name</li><li class="smart-tag-field" data-type="other" data-field_name="site_url">Site URL</li><li class="smart-tag-field" data-type="other" data-field_name="page_title">Page Title</li><li class="smart-tag-field" data-type="other" data-field_name="page_url">Page URL</li><li class="smart-tag-field" data-type="other" data-field_name="page_id">Page ID</li><li class="smart-tag-field" data-type="other" data-field_name="form_name">Form Name</li><li class="smart-tag-field" data-type="other" data-field_name="user_ip_address">User IP Address</li></ul></div></div></div></div>';
			
			$structure['invitation'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="placeholder_%d%">'.__("Placeholder","pie-register").'</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';

				$structure["invitation"] .=  '<div class="advance_fields"><label for="show_in_profile_%d%">'.__("Show in Profile","pie-register").'</label><select name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="show_in_profile checkbox_fields"><option value="1" selected="selected">'.__("Yes","pie-register").'</option><option value="0">'.__("No","pie-register").'</option></select></div>';

				global $wpdb;
				$invitation_code_table_name = $wpdb->prefix."pieregister_code";
				$invite_codes = "";

				if($wpdb->get_var("SHOW TABLES LIKE '$invitation_code_table_name'") == $invitation_code_table_name) {
					$sql = "SELECT * FROM {$invitation_code_table_name} ORDER BY `id` ASC";
					$result_invitaion_code = $wpdb->get_results( $sql );
					$today = date('Y-m-d');
					foreach($result_invitaion_code as $object){
						if( 
							( $object->expiry_date == "0000-00-00" || strtotime($object->expiry_date) > strtotime($today) )
							&&
							intval($object->status) != 0
							&&
							( 
								intval($object->code_usage) == 0
								||
								( intval($object->code_usage) > 0 && intval($object->code_usage) > intval($object->count) )
							)
						){
							$invite_codes .= '<option value="'.$object->name.'" >'.$object->name.'</option>';
						}
					}
				}

				$structure["invitation"] .=  '<div class="advance_fields"><label for="allowed_codes_%d%">'.__("Allowed Codes","pie-register").'</label><select multiple name="field[%d%][allowed_codes][]" id="allowed_codes_%d%"  class="allowed_codes checkbox_fields">'.$invite_codes.'</select></div>';
			
			$structure['invitation'] .= '</div></div>';
			
			$structure['terms'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div>';
			
			$structure['terms'] .= '<div class="advance_fields"><label for="cont_%d%">'.__("Content","pie-register").'</label><select name="field[%d%][cont]" id="cont_%d%">';			
			$pages = get_pages(array( 'numberposts' => -1));
			if(sizeof($pages) > 0)
			{
				foreach( $pages as $page ) : $page->post_content; 
					$structure['terms'] .= '<option class="level-0" value="'.$page->ID.'">';
					$structure['terms'] .= $page->post_title; 
					$structure['terms'] .= '</option>';
				endforeach;
			}		
			$structure['terms'] .='</select></div>';
			
			$structure['terms'] .= '<div class="advance_fields"><label for="required_%d%">'.__("Rules","pie-register").'</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">'.__("Required","pie-register").'</label></div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
			
			global $wp_roles;
			$role = $wp_roles->roles;
			$user_role_option = "";
			$piereg_default_wp_usre_role = get_option("default_role");
			$piereg_selected_user_role = "";
			foreach($role as $key=>$value)
			{
				if($piereg_default_wp_usre_role == $key)
				{
					$piereg_selected_user_role = ' selected="selected" ';
				}
				$user_role_option .= '<option value="'.$key.'" '. $piereg_selected_user_role .' >'.$value['name'].'</option>';
				$piereg_selected_user_role = "";
			}
			
			$payment_gateways_html = "%payment_gateways_list_box%";
			
			$structure['pricing'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">'.__("Label","pie-register").'</label><input type="text" name="field[%d%][label]" id="label_%d%" value="Membership" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">'.__("Description","pie-register").'</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div>';
			
			if( "not" == "allowed" ) {
				$structure['pricing'] .= '<div class="advance_fields dropdown_field_value sel_options_%d%"><div class="advance_fields dropdown_field_value"><label for="display_%d%" class="select_option_display">'.__("Display Value","pie-register").'</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="starting_price_%d%">'.__("Starting Price","pie-register").'</label><input type="text" name="field[%d%][starting_price][]" id="starting_price_%d%" class="input_fields character_fields select_option_starting_price"><label for="for_%d%">'.__("For","pie-register").'</label><input type="text" name="field[%d%][for][]" id="for_%d%" class="input_fields character_fields select_option_for"><select class="input_fields character_fields_mon select_option_for_period" name="field[%d%][for_period][]" id="for_period_%d%" ><option value="days">'.__("Days","pie-register").'</option><option value="weeks">'.__("Weeks","pie-register").'</option><option value="months">'.__("Months","pie-register").'</option></select></div><div class="advance_fields dropdown_field_value"><label class="select_option_then_price" for="then_price_%d%">'.__("Then Price","pie-register").'</label><input type="text" name="field[%d%][then_price][]" id="then_price_%d%" class="input_fields character_fields select_option_then_price"><label for="activation_cycle_%d%">'.__("Activation Cycle","pie-register").'</label><select class="input_fields character_fields_sec select_option_activation_cycle" name="field[%d%][activation_cycle][]" id="activation_cycle_%d%" ><option value="-1">'.__("Use Default","pie-register").'</option><option value="0">'.__("One Time","pie-register").'</option><option value="7">'.__("Weekly","pie-register").'</option><option value="30">'.__("Monthly","pie-register").'</option><option value="182">'.__("Half Yearly","pie-register").'</option><option value="273">'.__("Quarterly","pie-register").'</option><option value="365">'.__("Yearly","pie-register").'</option></select><label for="role_%d%">'.__("Role","pie-register").'</label><select class="input_fields character_fields_sec select_option_role" name="field[%d%][role][]" id="role_%d%" >'.$user_role_option.'</select><label>'.__("Checked","pie-register").'</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onClick="addPricingOptions(%d%,\'radio\',jQuery(this));">+</a></div></div>';
			} // until multiple payment gateways with recurring payment release
			
			$structure['pricing'] .= '<div class="advance_fields"><label>'.__("Allow Payment Gateways","pie-register").'</label>'.$payment_gateways_html.'</div><div class="advance_fields"><label for="validation_message_%d%">'.__("Validation Message","pie-register").'</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">'.__("CSS Class Name","pie-register").'</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div>';
			
			// if stripe activate and enable
			
			if($this->checkAddonActivated('Stripe') == true || $this->checkAddonActivated('AuthNet') == true)
			{
				$structure['pricing'] .= '<div class="advance_fields stripe_price"><label for="payment_charge_%d%">'.__("Price (not for PayPal)","pie-register").'</label><input type="text" name="field[%d%][payment_charge]" id="payment_charge_%d%" class="input_fields"></div>';
			}
			
			$structure['pricing'] .= '</div></div>';
			
			return $structure;
		}
		function payment_gateways_list(){
			$pie_reg = get_option(OPTION_PIE_REGISTER);
			$payment_gateways_name_list = array();
			/* For Authorize.Net*/
			if ( (isset($pie_reg['enable_authorize_net'],$pie_reg['piereg_authorize_net_login_id']) && $pie_reg['enable_authorize_net'] == 1 and trim($pie_reg['piereg_authorize_net_login_id'])	 != "" && $this->checkAddonActivated('AuthNet') == true) ){
				$payment_gateways_name_list["authorizeNet"] = "Authorize.Net";
			}
			/*For Stripe*/
			if( (isset($pie_reg['enable_stripe']) && $pie_reg['enable_stripe'] == 1 && $this->checkAddonActivated('Stripe') == true ) ){
				$payment_gateways_name_list["stripe"] = "Stripe";
			}
			/*For Paypal (Standard)*/
			if( (isset($pie_reg['enable_paypal'],$pie_reg['paypal_butt_id']) && $pie_reg['enable_paypal'] == 1 and trim($pie_reg['paypal_butt_id']) != "") ){
				$payment_gateways_name_list["PaypalStandard"] = "Paypal (Standard)";
			}
			return $payment_gateways_name_list;
		}
		function deactivation_settings(){
			global $wpdb;
			$option = get_option(OPTION_PIE_REGISTER);
			do_action( 'pie_deactivation_base', $option );
			
			//$this->uninstall_settings();
		}
		function uninstall_settings()
		{
			do_action( 'pie_uninstall_base' );
			
			// Remove - deleting options and tables
			// $this->uninstall_pieregister_license_key();
		}
		function uninstall_pieregister_license_key(){
			global $wpdb, $blog_id;

			$this->license_key_deactivation();
	
			// Remove options
			if ( is_multisite() ) {
	
				switch_to_blog( $blog_id );
	
				foreach ( array(
						'api_manager_example',
						'piereg_api_manager_product_id',
						'piereg_api_manager_instance',
						'api_manager_example_deactivate_checkbox',
						'piereg_api_manager_activated',
						'bf_version'
						) as $option) {
							delete_option( $option );
						}
	
				restore_current_blog();
	
			} else {
				foreach ( array(
						'api_manager_example',
						'piereg_api_manager_product_id',
						'piereg_api_manager_instance',
						'api_manager_example_deactivate_checkbox',
						'piereg_api_manager_activated'
						) as $option) {
							delete_option( $option );
						}
			}
		}
		/*
		 * Deactivates the license on the API server
		 * @return void
		*/
		public function license_key_deactivation() {
	
			$piereg_api_manager_key_class = new Api_Manager_Example_Key();
			$activation_status = get_option( 'piereg_api_manager_activated' );
			$default_options = get_option( 'api_manager_example' );	
			$api_email = $default_options['activation_email'];
			$api_key = $default_options['api_key'];
	
			$args = array(
				'email' => $api_email,
				'licence_key' => $api_key,
				);
	
			if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
				$piereg_api_manager_key_class->deactivate( $args ); // reset license key activation
			}
		}
		
		function createFieldName($text)
		{
			return $this->getMetaKey($text);			
		}
		function getMetaKey($text)
		{
			return str_replace("-","_",sanitize_title($text));	
		}
		function filterSubject($user,$subject)
		{
			if(!is_object($user))
			{
				if ( username_exists( $user ) ){
					$user = get_user_by('login', $user);
				}
				// Then, by e-mail address
				elseif( email_exists($user) ){
					$user = get_user_by('email', $user);
				}
				// Then, by user ID
				else{
					$user = new WP_User( intval($user) );
				}
			}
			if(!$user) return false;
			
			$user_login = stripslashes($user->data->user_login);
			$user_email = stripslashes($user->data->user_email);
			$blog_name	= get_option("blogname"); 
			
			$keys 	= array("%user_login%","%user_email%","%blogname%");
			$values = array($user_login,$user_email,$blog_name);
			
			$return = str_replace($keys,$values,$subject);
			
			return $return;
		}
		
		function filterEmail($text,$user,$user_pass="",$password_reset_key=false,$extra_variables = array())
		{
			if(!is_object($user))
			{
				
				if ( username_exists( $user ) ){
					$user = get_user_by('login', $user);
				}
				// Then, by e-mail address
				elseif( email_exists($user) ){
					$user = get_user_by('email', $user);
				}
				// Then, by user ID
				else{
					$user = new WP_User( intval($user) );
				}
			}
			if(!$user) return false;
			
			/*
				*	Define Variables
			*/
			$reset_email_key = "";//Reset Email Key
			$confirm_current_email_url = "";
			$user_last_date = "";//Add User Last Date
			
			/*
				*	Replace Array to Variables
			*/
			if(!empty($extra_variables))
				extract($extra_variables);
			
			$option = get_option(OPTION_PIE_REGISTER);
			
			$text					= $this->replaceMetaKeys($text,$user->ID);
			$user_login 			= stripslashes($user->data->user_login);
			$user_email 			= stripslashes($user->data->user_email);
			$blog_name 				= get_option("blogname"); 
			$site_url 				= get_option("siteurl");
			$blogname_url			= '<a href="'.get_option("siteurl").'">'.get_option("blogname").'</a>';
			$first_name				= get_user_meta( $user->ID, 'first_name' );
			$last_name				= get_user_meta( $user->ID, 'last_name' );
			$user_url				= $user->data->user_url;
			$user_aim				= get_user_meta( $user->ID, 'aim' );
			$user_yim				= get_user_meta( $user->ID, 'yim' );
			$user_jabber			= get_user_meta( $user->ID, 'jabber' );
			$user_biographical_nfo	= get_user_meta( $user->ID, 'description' );
			$invitation_code		= get_user_meta( $user->ID, 'invite_code' );
			$invitation_code		= (isset($invitation_code[0]) && is_array($invitation_code))? $invitation_code[0] : "";
			$custom_role			= get_user_meta( $user->ID, 'custom_role' ); // dropdown-ur
			$custom_role			= (isset($custom_role[0]) && is_array($custom_role))? $custom_role[0] : ""; // dropdown-ur
			$user_ip				= $_SERVER['REMOTE_ADDR'];
			$hash 					= get_user_meta( $user->ID, 'hash', true );
			$admin_hash				= get_user_meta( $user->ID, 'admin_hash', true ); 			// task av
			$wc_billing_address		= apply_filters("pieregister_display_woocommerce_billing_address", $user->ID);
			$wc_shipping_address	= apply_filters("pieregister_display_woocommerce_shipping_address", $user->ID);

			
			if(isset($hash))
				$activationurl = $this->pie_modify_custom_url($this->pie_login_url(),"action=activate").'&pie_id='.$user->data->user_login.'&activation_key='.((isset($hash))?$hash:"");
			else
				$activationurl = "";
			
			if($activationurl != ""){
				$activationurl			= '<a href="'.$activationurl.'" target="_blank">'.$activationurl.'</a>';
			}
			// task av
			if(isset($admin_hash))
				$verificationurl = $this->pie_modify_custom_url(site_url(),"action=unverified_user").'&pie_id='.$user->data->user_login.'&verification_key='.((isset($admin_hash))?$admin_hash:"");
			else
				$verificationurl = "";
			
			if($verificationurl != ""){
				$verificationurl			= '<a href="'.$verificationurl.'" target="_blank">'.$verificationurl.'</a>';
			}

			$all_field 				= $this->get_all_field($user->data->user_email);
			$user_registration_date = $user->data->user_registered;
			
			if($password_reset_key)
				$reset_password_url = $this->pie_modify_custom_url($this->pie_login_url(),"action=rp&key={$password_reset_key}&login={$user_login}");
			else
				$reset_password_url = "";
			
			if($reset_password_url != ""){
				$reset_password_url			= '<a href="'.$reset_password_url.'" target="_blank">'.$reset_password_url.'</a>';
			}	
			/*
				*	Add since 2.0.13
				*	User New Email
			*/
			$user_new_email = get_user_meta( $user->ID, 'new_email_address', true );
	
			/*
				*	Add since 2.0.13
				*	Email edit verification url
			*/
			$reset_email_url = "";
			if($reset_email_key)
				$reset_email_url = $this->get_page_uri($option['alternate_login'],"action=email_edit&key={$reset_email_key}&login={$user_login}");
			else
				$reset_email_url = "";
			if($reset_email_url != ""){
				$reset_email_url			= '<a href="'.$reset_email_url.'" target="_blank">'.$reset_email_url.'</a>';
			}	
			
			$confirm_current_email_url = "";
			if(isset($confirm_current_email_key))
				$confirm_current_email_url = $this->get_page_uri($option['alternate_login'],"action=current_email_verify&key={$confirm_current_email_key}&login={$user_login}");
			else
				$confirm_current_email_url = "";
			if($confirm_current_email_url != ""){
				$confirm_current_email_url			= '<a href="'.$confirm_current_email_url.'" target="_blank">'.$confirm_current_email_url.'</a>';
			}	
			
			/////////////// PAYMENT LINK ///////////
			$pending_payment_url = "";
			$register_type = get_user_meta($user->ID, 'register_type', true);
			if($register_type == "payment_verify"){
				$hash = md5( time() );
				update_user_meta( $user->ID, 'hash', $hash );
				if($option['paypal_sandbox'] == "yes")
					$pending_payment_url = PIE_SSL_SAND_URL."?cmd=_s-xclick&hosted_button_id=".$option['paypal_butt_id']."&custom=".$hash."|".$user->ID."&bn=Genetech_SI_Custom";
				else
					$pending_payment_url = PIE_SSL_P_URL."?cmd=_s-xclick&hosted_button_id=".$option['paypal_butt_id']."&custom=".$hash."|".$user->ID."&bn=Genetech_SI_Custom";
				$pending_payment_url = '<a href="'.$pending_payment_url.'">'.$pending_payment_url.'</a>';
			}
			$user_pass = "********";
			//////////////////////////////////////
			$keys 	= array("%user_login%","%user_email%","%blogname%","%siteurl%","%activationurl%","%verificationurl%","%firstname%","%lastname%","%user_url%","%user_aim%","%user_yim%","%user_jabber%","%user_biographical_nfo%","%all_field%","%user_registration_date%","%reset_password_url%" ,"%invitation_code%","%custom_role%","%pending_payment_url%","%blogname_url%","%user_ip%","%user_pass%","%user_new_email%","%reset_email_url%","%user_last_date%","%confirm_current_email_url%","%wc_billing_address%","%wc_shipping_address%"); // dropdown-ur
						
			$values = array($user_login ,$user_email,$blog_name, $site_url,$activationurl,$verificationurl,$this->returnFormattedValue($first_name),$this->returnFormattedValue($last_name),$user_url,$this->returnFormattedValue($user_aim),$this->returnFormattedValue($user_yim),$this->returnFormattedValue($user_jabber),$this->returnFormattedValue($user_biographical_nfo), $all_field,$user_registration_date,$reset_password_url,$invitation_code,$custom_role,$pending_payment_url,$blogname_url,$user_ip,$user_pass,$user_new_email,$reset_email_url,$user_last_date,$confirm_current_email_url,$wc_billing_address,$wc_shipping_address);
			
			$return_text = str_replace($keys,$values,$text);
			
				/////////////// CUSTOM FIELDS ///////////////
				$customfields = array();
				$user_form_id	= get_user_meta( $user->ID, 'user_registered_form_id', true);
				$form_fields	= unserialize( get_option("piereg_form_fields_" . $user_form_id ) );
				
				if( preg_match_all("'%pie_(.*?)%'si", $text, $customfields) )
				{
					
					
					foreach( $customfields[0] as $val )
					{
						$pie_field_slug			= str_replace( "%", "", $val );
						$pie_field_value		= get_user_meta( $user->ID, $pie_field_slug, true);
						$_type					= explode("_", $pie_field_slug);
						$_types_multi_val		= array('radio','checkbox','multiselect','dropdown');
						
						if($_type[1] == 'pricing'){
							$pie_field_value = get_user_meta( $user->ID, 'pie_pricing', true);
						}
						
						if( $pie_field_value && (is_array($pie_field_value) || in_array( $_type[1], $_types_multi_val ))  )
						{
														
							if( in_array( $_type[1], $_types_multi_val ) )
							{
								$field_data		= $form_fields[$_type[2]];
								
								if(is_array($field_data['value']))							
									$combined_array	= array_combine($field_data['value'], $field_data['display']);
								
								$corrected_value 	= array();
								
								for($a = 0 ; $a < sizeof($pie_field_value) ; $a++ )
								{
									if(isset($pie_field_value[$a]))
										$corrected_value[$a] = $combined_array[$pie_field_value[$a]];
								}
								
								$pie_field_value = implode(", ",$corrected_value);
							}
							else 
							{
								$_params			= array(
															"_type" 	=> $_type[1],
															"_value" 	=> $pie_field_value
														);
								
								$pie_field_value	= $this->getValue( true, $_params );						
							}
							
						}
						
						$return_text 			= str_replace($val,$pie_field_value,$return_text);
					
					}
				}
			
			return $this->nl2br_save_html($return_text);
		}
		
		function nl2br_save_html($string) {
			if(! preg_match("#</.*>#", $string)) // return if no html tags.
				return nl2br($string);

			$string = str_replace(array("\r\n", "\r", "\n"), "\n", $string);

			$lines	= explode("\n", $string);
			$output	= '';
			foreach($lines as $line) {
				$line = rtrim($line);
				if(! preg_match("#</?[^/<>]*>$#", $line)) // Check if opening or closing tag present in line.
					$line .= '<br />';
				$output .= $line . "\n";
			}

			return $output;
		}
		
		function returnFormattedValue($array){
			if($array !== ''){
				if(is_array($array) && isset($array[0])){
					return $array[0];
				}else if(!is_array($array)){
					return $array;
				}
			}
			return '';
		}
		
		function get_all_field($user)
		{
			if(!is_object($user))
			{
				global $wpdb;
				$user_table = $wpdb->prefix."users";
				$user = $wpdb->get_results( $wpdb->prepare("SELECT `ID`, `user_login`, `user_nicename`, `user_email`, `user_registered` FROM `".$user_table."` WHERE `user_email` = %s", stripslashes(( $user ) )) );
				if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
					$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
				}
				$user = $user[0];
			}
			if($user)
			{
				$val = "<table>";
					foreach($user as $key=>$value)
					{
						if($key != "ID")
						{
							$val .= "<tr>
										<td>".$this->chnge_case($key)."</td>
										<td>".$value."</td>
									</tr>";
						}
					}
				$val .= "</table>";
			}
			else{
				$val = "";
			}
			return $val;
		}
		function chnge_case($key = "")
		{
			return @ucwords(strtolower(str_replace("_"," ",$key)));
		}
		function createDropdown($options,$sel = "")
		{
			$html = "";
			if(is_array($options))
			{
				for($a = 0 ;$a < sizeof($options);$a++)
				{
					if( $a == 0 )
					{
						$html .= '<option value="">'.apply_filters('piereg_dropdown_please_select',esc_html(__("--Please Select --","pie-register"))).'</option>';	
					}
					
					$selected = "";
					if(isset($sel) && is_array($sel)){
						if(in_array($options[$a],$sel)){
							$selected = 'selected="selected"';
						}
					}else{
						if($options[$a]==$sel)
							$selected = 'selected="selected"';
					}
					$html .= '<option '.$selected.' value="'.esc_attr($options[$a]).'">'.esc_html($options[$a]).'</option>';	
				}
			}
			return $html;
		}
		// pie-register-woocommerce Addon
		function createCountryDropdown($options,$sel = "")
		{
			$html = "";
			if(is_array($options))
			{
				for($a = 0 ;$a < sizeof($options);$a++)
				{
					if( $a == 0 )
					{
						$html .= '<option value="">'.apply_filters('piereg_dropdown_please_select',esc_html(__("--Please Select --","pie-register"))).'</option>';	
					}
					
					$selected = "";
					if(isset($sel) && is_array($sel)){
						if(in_array($options[$a]['iso_code'],$sel)){
							$selected = 'selected="selected"';
						}
					}else{
						if($options[$a]['iso_code']==$sel)
							$selected = 'selected="selected"';
					}
					$html .= '<option '.$selected.' value="'.esc_attr($options[$a]['iso_code']).'">'.esc_html($options[$a]['name']).'</option>';	
				}
			}
			return $html;
		}
		function createStatesDropdown($options,$sel = "")
		{
			$html = "";
			if(is_array($options))
			{
				for($a = 0 ;$a < sizeof($options);$a++)
				{
					if( $a == 0 )
					{
						$html .= '<option value="">'.apply_filters('piereg_dropdown_please_select',__("--Please Select --","pie-register")).'</option>';	
					}
					
					$selected = "";
					if(isset($sel) && is_array($sel)){
						if(in_array($options[$a]['iso_code'],$sel)){
							$selected = 'selected="selected"';
						}
					}else{
						if($options[$a]['iso_code']==$sel)
							$selected = 'selected="selected"';
					}
					$html .= '<option '.$selected.' value="'.esc_attr($options[$a]['iso_code']).'">'.esc_html($options[$a]['name']).'</option>';	
				}
			}
			return $html;
		}
		function codeTable()
		{
			global $wpdb;		
			return $wpdb->prefix."pieregister_code";			
		}
		function warnings()
		{ //Show warning if plugin is installed on a WordPress lower than 3.2
			global $wp_version;			
			//VERSION CONTROL
			if( $wp_version < 3.5 )
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Pie Register is compatible with WordPress v3.5 and up. You are currently using an older version WordPress.', 'pie-register').esc_html($wp_version).". ".__("The plugin may not work as expected.","pie-register")."</strong> </p></div>";
			
		}
		public function check_enable_social_site_method()// only check any Social Site method enable or not.
		{
			$pie_reg = get_option(OPTION_PIE_REGISTER);
			if(
			(isset($pie_reg['piereg_enable_facebook']) and $pie_reg['piereg_enable_facebook'] == 1 and trim($pie_reg['piereg_facebook_app_id']) != "") or
			(isset($pie_reg['piereg_enable_linkedin']) and $pie_reg['piereg_enable_linkedin'] == 1 and trim($pie_reg['piereg_linkedin_app_id']) != "")	or
			(isset($pie_reg['piereg_enable_google']) and $pie_reg['piereg_enable_google'] 	  == 1 )	or
			(isset($pie_reg['piereg_enable_yahoo']) and $pie_reg['piereg_enable_yahoo'] 	  == 1 )	or
			(isset($pie_reg['piereg_enable_twitter']) and $pie_reg['piereg_enable_twitter']   == 1 and trim($pie_reg['piereg_twitter_app_id'] ) != "") or
			(isset($pie_reg['piereg_enable_wordpress']) and $pie_reg['piereg_enable_wordpress']   == 1 and trim($pie_reg['piereg_wordpress_app_id'] ) != "")
			  )
			{
				return "true";
			}
			else
			{
				return "false";
			}
			
		}
		
		public function check_enable_payment_method()// only check any payment method enable or not.
		{
			$pie_reg = get_option(OPTION_PIE_REGISTER);
			if(
			   	/* For Authorize.Net*/
				(isset($pie_reg['enable_authorize_net'],$pie_reg['piereg_authorize_net_login_id']) && $pie_reg['enable_authorize_net'] == 1 and trim($pie_reg['piereg_authorize_net_login_id'])	 != "" && $this->checkAddonActivated('AuthNet') == true) or 
				/*For Stripe*/
				(isset($pie_reg['enable_stripe']) && $pie_reg['enable_stripe'] == 1 && $this->checkAddonActivated('Stripe') == true ) or 				
				/*For Paypal (Standard)*/
				(isset($pie_reg['enable_paypal'],$pie_reg['paypal_butt_id']) && $pie_reg['enable_paypal'] == 1 and trim($pie_reg['paypal_butt_id']) != "")
			  )
			{
				return "true";
			}
			else
			{
				return "false";
			}
		}
		function check_plugin_activation()
		{
			if(
				is_plugin_active("pie-register-stripe/pie-register-stripe.php")										or
				is_plugin_active("pie-register-authorize-net/pie-register-authorize-net.php")
			  )
			{
				return "true";
			}
			else{
				return "false";
			}
		}
		function check_payment_plugin_activation(){
			return $this->check_plugin_activation();
		}

		function get_addons_status(){
			if(
				(is_plugin_active("pie-register-authorize-net/pie-register-authorize-net.php") && get_option('piereg_api_manager_addon_AuthNet_status') == "inactive")
				|| (is_plugin_active("pie-register-bbpress/pie-register-bbpress.php") && get_option('piereg_api_manager_addon_Bbpress_status') == "inactive")
				|| (is_plugin_active("pie-register-bulkemail/pie-register-bulkemail.php") && get_option('piereg_api_manager_addon_BulkEmail_status') == "inactive")
				|| (is_plugin_active("pie-register-field-visibility/pie-register-field-visibility.php") &&  get_option('piereg_api_manager_addon_Field_Visibility_status') == "inactive")
				|| (is_plugin_active("pie-register-geolocation/pie-register-geolocation.php") && get_option('piereg_api_manager_addon_geolocation_status') == "inactive")
				|| (is_plugin_active("pie-register-mailchimp/pie-register-mailchimp.php") && get_option('piereg_api_manager_addon_Mail_Chimp_status') == "inactive")
				|| (is_plugin_active("pie-register-profile-search/pie-register-profile-search.php") && get_option('piereg_api_manager_addon_Profile_Search_status') == "inactive")
				|| (is_plugin_active("pie-register-social-site/pie-register-social-site.php") && get_option('piereg_api_manager_addon_Social_Sites_Login_status') == "inactive")
				|| (is_plugin_active("pie-register-stripe/pie-register-stripe.php") && get_option('piereg_api_manager_addon_Stripe_status') == "inactive")
				|| (is_plugin_active("pie-register-twilio/pie-register-twilio.php") && get_option('piereg_api_manager_addon_Twilio_status') == "inactive")
				|| (is_plugin_active("pie-register-woocommerce/pie-register-woocommerce.php") && get_option('piereg_api_manager_addon_WooCommerce_status') == "inactive")

			)	{
				return true;
			} else {
				return false;
			}
		}

		function piereg_default_settings()
		{
			$pie_pages_id = get_option("pie_pages");
			$update = get_option(OPTION_PIE_REGISTER);
			//E-Mail TYpes
			$email_type = array(
								"default_template"							=> __("Your account is ready.","pie-register"),
								"admin_verification"						=> __("Your account is being processed.","pie-register"),
								"email_verification"						=> __("Email verification.","pie-register"),
								"email_thankyou"							=> __("Your account has been activated.","pie-register"),
								"pending_payment"							=> __("Overdue Payment.","pie-register"),
								"payment_success"							=> __("Payment Processed.","pie-register"),
								"payment_faild"								=> __("Payment Failed.","pie-register"),
								"pending_payment_reminder"					=> __("Payment Pending.","pie-register"),
								"email_verification_reminder"				=> __("Email Verification Reminder.","pie-register"),
								"forgot_password_notification"				=> __("Password Reset Request.","pie-register")
								);
			
			add_option("pie_user_email_types",$email_type);
			
			// Truncate redirect roles table
			global $wpdb;
			global $PR_Bot_List;
			$redirect_settings_table_name = $wpdb->prefix."pieregister_redirect_settings";
			$redirect_settings_sql = "TRUNCATE TABLE `".$redirect_settings_table_name."` ";
			
			if(!$wpdb->query($redirect_settings_sql))
			{
				$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			$update = get_option(OPTION_PIE_REGISTER);
			
			$update["paypal_butt_id"] = "";
			$update["paypal_pdt"]     = "";
			$update["paypal_sandbox"] = "";
			$update["payment_success_msg"] 			= __("Payment was successful.","pie-register");
			$update["payment_faild_msg"] 			= __("Payment failed.","pie-register");
			$update["payment_renew_msg"] 			= __("Account needs to be activated.","pie-register");
			$update["payment_already_activate_msg"] = __("Account is already active.","pie-register");
			$update['enable_admin_notifications'] = 1;
			$update['enable_paypal'] = 0;
			$update['enable_blockedips'] = 0;
			$update['enable_blockedusername'] = 0;
			$update['enable_blockedemail'] = 0;
			$update['admin_sendto_email'] 	= get_option( 'admin_email' );				
			$update['admin_from_name'] 		= "Administrator";
			$update['admin_from_email'] 	= get_option( 'admin_email' );
			$update['admin_to_email'] 		= get_option( 'admin_email' );
			$update['admin_bcc_email'] 		= get_option( 'admin_email' );
			$update['admin_subject_email'] 	= __("New User Registration","pie-register");
			$update['admin_message_email_formate'] 	= 1;
			$update['admin_message_email'] 	= '<p>Hello Admin,</p><p>A new user has been registered on your Website,. Details are given below:</p><p>Thanks</p><p>Team %blogname%</p>';
			
			
			//UX_Basic_settings
			$update['display_hints']					= 0;
			$update['login_username_label']				= 'Username';
			$update['login_username_placeholder']		= '';
			$update['login_password_label']				= 'Password';
			$update['login_password_placeholder']		= '';
			$update['forgot_pass_username_label']		= 'Username or Email:';
			$update['forgot_pass_username_placeholder']	= '';
			
			
			$update['redirect_user']			= 1;
			$update['subscriber_login']			= 0;
			$update['allow_pr_edit_wplogin']	= 0;
			$update['block_WP_profile']			= 0;
			$update['modify_avatars']			= 0;
			$update['show_admin_bar']			= 1;
			$update['block_wp_login']			= 1;
			$update['alternate_login']			= $pie_pages_id[0];
			$update['alternate_register']		= $pie_pages_id[1];
			$update['alternate_forgotpass']		= $pie_pages_id[2];
			$update['alternate_profilepage']	= $pie_pages_id[3];
			
			$update['after_login']				= -1;
			
			//Captcha_login_form
			$update['captcha_in_login_value']		= 0;
			$update['captcha_in_login_attempts']		= 0;
			$update['capthca_in_login_label']		= '';
			$update['capthca_in_login']		= '2';
			$update['piereg_security_attempts_login_value']		= '0';
			$update['security_attempts_login_time']		= '1';
			$update['security_attempts_login']		= '2';
			
			//Captcha_forgot_form
			$update['captcha_in_forgot_value']		= 0;
			$update['capthca_in_forgot_pass_label']		= '';
			$update['capthca_in_forgot_pass']		= '2';
			$update['piereg_security_attempts_forgot_value']		= '0';
			$update['security_attempts_forgot_time']		= '1';
			$update['security_attempts_forgot']		= '2';
			
			//security_attempts_login
			$update['security_captcha_attempts_login']	= 0;
			$update['security_captcha_login']	= 2;
			$update['security_attempts_login']	= 0;
			$update['security_attempts_login_time']	= 1;
			
			
			$update['alternate_login_url']		= '';
			
			$update['alternate_logout']			= -1;
			$update['alternate_logout_url']		= '';
			$update['login_after_register'] 	= 0;
			/* Bot Settings */
			$update['restrict_bot_enabel']		= 0;
			$update['restrict_bot_content']				= $PR_Bot_List;
			$update['restrict_bot_content_message']		= "Restricted Post: You are not allowed to view the content of this Post";
			
			$update['outputhtml'] 				= 1;
			$update['outputcss'] 				= 1;
			
			$update['pass_strength_indicator_label']	= "Strength Meter";
			$update['pass_very_weak_label']				= "Very weak";
			$update['pass_weak_label']					= "Weak";
			$update['pass_medium_label']				= "Medium";
			$update['pass_strong_label']				= "Strong";
			$update['pass_mismatch_label']				= "Mismatch";
			$update['pr_theme']							= "0";
		
			
			$update['outputjquery_ui'] 			= 1;
			$update['no_conflict']				= 0;
			$update['currency'] 				= "USD";
			$update['verification'] 			= 0;
			$update['email_edit_verification_step'] = 1;
			$update['piereg_recaptcha_type'] 		= "v2";
			$update['grace_period'] 			= 0;
			$update['captcha_publc'] 			= "";
			$update['captcha_private'] 			= "";
			$update['captcha_publc_v3'] 		= "";
			$update['captcha_private_v3'] 		= "";
			$update['paypal_button_id'] 		= "";
			$update['paypal_pdt_token'] 		= "";
			$update['custom_css'] 				= "";
			$update['tracking_code'] 			= "";

			$update['enable_invitation_codes'] 	= 0;
			$update['invitation_codes'] 		= "";

			$update['pie_email_linkpage']		= $pie_pages_id[1];			
			$update['pie_email_invitecode']		= 0;
			$update['pie_name_from']			= "Admin";
			$update['pie_email_from']			= get_option( 'admin_email' );
			$update['pie_email_subject']		= "Invitation to register with " . get_bloginfo('name');
			$update['pie_email_content']		= 'Hi there,'.PHP_EOL.PHP_EOL.'You are invited to create an account with' . get_bloginfo('name') . '. Click <a href="%invitation_link%">this link</a> to complete registration.'.PHP_EOL.PHP_EOL.'Regards,'.PHP_EOL.get_bloginfo('name');
			
			$update['reg_form_submission_time_enable'] = "0";
			$update['reg_form_submission_time'] = "0";
			$update['custom_logo_url']			= "";
					
			$update['custom_logo_tooltip']		= "";
			
			$update['custom_logo_link']			= "";
			$update['show_custom_logo']			= 1;
			
			$update['pie_regis_set_user_role_']	= "subscriber";
			
			
			$pie_user_email_types 	= get_option( 'pie_user_email_types'); 
					
			foreach ($pie_user_email_types as $val=>$type) 
			{
				$update['enable_user_notifications'] = 0;
				
				$update['user_from_name_'.$val] 	= "Admin";
				$update['user_from_email_'.$val] 	= get_option( 'admin_email' );
				$update['user_to_email_'.$val]	 	= get_option( 'admin_email' );
				$update['user_subject_email_'.$val] = $type;
				$update['user_formate_email_'.$val] = 1;
			}
	
			$update['user_message_email_admin_verification']	 					= '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>A site administrator will review your request. Once approved, you will be notified via email.</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_email_verification']			 			= '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>Please use the link below to verify your email address. </p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_email_thankyou'] 							= '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>You are ready to enjoy the benefits of our products and services by signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
			$update['user_message_email_payment_success'] 							= '<p>Dear %user_login%,</p><p>Congratulations, your payment has been successfully processed. <br/>Please enjoy the benefits of your membership on %blogname% </p><p>Thank You,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_payment_faild'] 							= '<p>Dear %user_login%,</p><p>Our last attempt to charge the membership payment for your account has failed. </p><p>You are requested to log in to your account at %blogname% to provide a different payment method, or contact your bank/credit-card company to resolve this issue.</p><p>Kind Regards,</p><p>Team %blogname%<br/></p>';
			
			$update['user_message_email_pending_payment'] 							= '<p>Dear %user_login%,</p><p>This is a reminder that membership payment is overdue for your account on %blogname%. Please process your payment immediately to keep membership previlages active. </p><p>Best Regards,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_default_template'] 							= '<p>Dear %user_login%,</p><p>Thank You for registering with our website.</p><p>You are ready to enjoy the benefits of our products and services by signing in to your personalized account.</p><p>Best Regards,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_pending_payment_reminder'] 					='<p>Dear %user_login%,</p><p>We have noticed that you created an account on %blogname% a few days ago, but have not completed the payment. Please use the link below to complete the payment. <br/>Your account will be activated once the payment is received.</p><p>%pending_payment_url%</p><p>Best Regards,</p><p>Team %blogname%</p>';
			
			$update['user_message_email_email_verification_reminder']			 	= '<p>Dear %user_login%,</p><p>Thank You for registering with our website. </p><p>We noticed that you created an account on %blogname% but have not completed the email verification process. </p><p>Please use the link below to verify your email address. </p><p>%activationurl%</p><p>Best Wishes,</p><p>Team %blogname%</p>';
	
			$update['user_message_email_forgot_password_notification']				= '<p>Dear %user_login%,</p><p>We have received a request to reset your account password on %blogname%. Please use the link below to reset your password. If you did not request a new password, please ignore this email and the change will not be made.</p><p>( %reset_password_url% )</p><p>Best Regards,</p><p>Team %user_login%</p>';
			
			$update['user_message_email_email_edit_verification']		= '<p>Hello %user_login%,</p><p>We have received a request to change the email address for your account on %blogname%.</p><p>Old Email Address: %user_email%<br/>New Email Address: %user_new_email%. </p><p>Please use the link below to complete this change.</p><p>(%reset_email_url%)</p><p>Thanks</p><p>%blogname%<br/></p>';
			
			$update['user_message_email_current_email_verification']	= '<p>Hello %user_login%,</p><p>We have received a request to change the email address for your account on %blogname%.</p><p>Old Email Address: %user_email%<br/>  New Email Address: %user_new_email%. </p><p>If you requested this change, please use the link below to complete the action. Otherwise please ignore this email and the change will not be made.</p><p>(%confirm_current_email_url%)</p><p>Thanks</p><p>%blogname%<br/></p>';
			
			//Reset_all_pie_register_settings
			update_option(OPTION_PIE_REGISTER, $update );
			$this->set_pr_global_options( $update, OPTION_PIE_REGISTER );
		}
		function pie_registration_url($url=false)
		{
			$this->pr_get_WP_Rewrite();
			
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			$pie_registration_url = get_permalink($options['alternate_register']);
			return ($pie_registration_url)? $pie_registration_url : wp_registration_url();
		}
		static function static_pie_registration_url($url=false)
		{
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			$pie_registration_url = get_permalink($options['alternate_register']);
			return ($pie_registration_url)? $pie_registration_url : wp_registration_url();
		}
		function pie_login_url($url=false)
		{
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			$this->pr_get_WP_Rewrite();
			
			$pie_login_url = $this->get_page_uri($options['alternate_login']);			
			$redirect_to_url = explode("?redirect_to=",$url);

			if(isset($redirect_to_url[1]) && !empty($redirect_to_url[1])){
				$pie_login_url = $this->pie_modify_custom_url($pie_login_url,"redirect_to=".(urlencode($redirect_to_url[1])) );
			}else{				
				if (doing_filter('login_url')) {
					$pie_login_url = $pie_login_url;
				} else{
					$current_page_uri = $this->get_current_permalink();
					$current_page_uri = ( (!empty($current_page_uri)) ? $current_page_uri : $this->piereg_get_current_url() );
					$pie_login_url = $this->pie_modify_custom_url($pie_login_url,"redirect_to=".(urlencode($current_page_uri)) );	
				}				
			}
			
			return ( ($pie_login_url)? $pie_login_url : ( (!empty($url))?$url:wp_login_url() ) );
		}
		static function static_pie_login_url($url=false)
		{
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			
			$pie_login_url = PieReg_Base::static_get_page_uri($options['alternate_login']);
			
			$redirect_to_url = explode("?redirect_to=",$url);
			
			if(isset($redirect_to_url[1]) && !empty($redirect_to_url[1])){
				$pie_login_url = PieReg_Base::static_pie_modify_custom_url($pie_login_url,"redirect_to=".(urlencode($redirect_to_url[1])) );
			}else{
				$current_page_uri = PieReg_Base::static_get_current_permalink();
				$current_page_uri = ( (!empty($current_page_uri)) ? $current_page_uri : self::piereg_get_current_url() );
				$pie_login_url = PieReg_Base::static_pie_modify_custom_url($pie_login_url,"redirect_to=".(urlencode($current_page_uri)) );
			}
			
			return ( ($pie_login_url)? $pie_login_url : ( (!empty($url))?$url:wp_login_url() ) );
		}
		function pie_lostpassword_url($url=false)
		{
			$this->pr_get_WP_Rewrite();
			
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			$pie_lostpass_url = get_permalink($options['alternate_forgotpass']);
			return ($pie_lostpass_url)? $pie_lostpass_url : wp_lostpassword_url();
		}
		static function static_pie_lostpassword_url($url=false)
		{
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			
			global $PR_GLOBAL_OPTIONS;
			$options = $PR_GLOBAL_OPTIONS;
			$pie_lostpass_url = get_permalink($options['alternate_forgotpass']);
			return ($pie_lostpass_url)? $pie_lostpass_url : wp_lostpassword_url();
		}
		function piereg_logout_url($url,$redirect)
		{
			$options = $this->get_pr_global_options();
			$this->pr_get_WP_Rewrite();
			$this->piereg_get_wp_plugable_file();
				
			/*
			 *	Get after Log Out url by current user role
			 */
			$log_out_url 		= "";
			$log_out_page_id	= "";
			
			if(!empty($log_out_url) && ($log_out_page_id == 0 || $log_out_page_id == "")){
				$redirect = trim($log_out_url);
			}
			elseif(!empty($log_out_page_id) && $log_out_page_id > 0){
				$redirect = $this->get_page_uri(intval($log_out_page_id));
			}
			elseif( $options['alternate_logout'] == 'url' && !empty($options['alternate_logout_url'])){
				$piereg_after_redirect_page = $options['alternate_logout_url'];
				$redirect = $piereg_after_redirect_page;
			}
			elseif( intval($options['alternate_logout']) > 0 && $options['alternate_logout'] != 'url'){
				$piereg_after_redirect_page = (intval($options['alternate_logout']) <= 0)? wp_logout_url() : $this->get_page_uri($options['alternate_logout']);
				$redirect = $piereg_after_redirect_page;
			}
			elseif(isset($_GET['redirect_to']) && $_GET['redirect_to'] != ""){
				$redirect = sanitize_url($_GET['redirect_to']);
			}
			
			if(empty($redirect))
				$redirect = home_url();
			
			$redirect = urlencode($redirect);
			$new_logout_url = home_url() . '/?piereg_logout_url=true&redirect_to=' . $redirect;
			return $new_logout_url;
		}
		static function static_piereg_logout_url($url,$redirect="")
		{
			$options = self::get_pr_global_options();
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			self::piereg_get_wp_plugable_file();
			
			$log_out_url 		= "";
			$log_out_page_id	= "";
			
			if(!empty($log_out_url) && ($log_out_page_id == 0 || $log_out_page_id == "")){
				$redirect = trim($log_out_url);
			}
			elseif(!empty($log_out_page_id) && $log_out_page_id > 0){
				$redirect = self::get_page_uri(intval($log_out_page_id));
			}
			elseif( $options['alternate_logout'] == 'url' && !empty($options['alternate_logout_url']) ){
				$piereg_after_redirect_page = $options['alternate_logout_url'];
				$redirect = $piereg_after_redirect_page;
			}
			elseif( $options['alternate_logout'] > 0 && $options['alternate_logout'] != 'url' ){
				$piereg_after_redirect_page = (intval($options['alternate_logout']) <= 0)? wp_logout_url() : self::get_page_uri($options['alternate_logout']);
				$redirect = $piereg_after_redirect_page;
			}
			elseif(isset($_GET['redirect_to']) && $_GET['redirect_to'] != ""){
				$redirect = esc_url_raw($_GET['redirect_to']);
			}
			
			if(empty($redirect))
				$redirect = home_url();
			
			$redirect = urlencode($redirect);
			$new_logout_url = home_url() . '/?piereg_logout_url=true&redirect_to=' . $redirect;
			return $new_logout_url;
		}
		function pie_modify_custom_url($get_url,$query_string=false){
			$get_url = trim($get_url);
			if(!$get_url) return false;
			
			if(strpos($get_url,"?"))
				$url = $get_url."&".$query_string;
			else
				$url = $get_url."?".$query_string;
				
			return $url;
		}
		static function static_pie_modify_custom_url($get_url,$query_string=false){
			$get_url = trim($get_url);
			if(!$get_url) return false;
			
			if(strpos($get_url,"?"))
				$url = $get_url."&".$query_string;
			else
				$url = $get_url."?".$query_string;
				
			return $url;
		}
		// get current URL
		function piereg_get_current_url($query_string = "") {
			$current_url  = 'http';
			$server_https = isset($_SERVER["HTTPS"]) ? $_SERVER["HTTPS"] : "";
			$server_name  = $_SERVER["SERVER_NAME"];
			$server_port  = $_SERVER["SERVER_PORT"];
			$request_uri  = $_SERVER["REQUEST_URI"];
			if (strtolower($server_https) == "on") 
				$current_url .= "s";
			$current_url .= "://";
			if ($server_port != "80")
				$current_url .= $server_name . ":" . $server_port . $request_uri;
			else 
				$current_url .= $server_name . $request_uri;
			
			if(!empty($query_string))
				return $this->pie_modify_custom_url($current_url,$query_string);
			
			return $current_url;
		}
		function piereg_validate_files($file_info,$extantion_array = array())
		{
			$result = false;
			$extantion_array = array_map("trim",$extantion_array);
			$result = in_array(pathinfo($file_info,PATHINFO_EXTENSION),$extantion_array);
			$result = (trim($result))? $result : false;
			return $result;
		}

		function pie_profile_pictures_upload($user_id,$field,$field_slug,$form_id=0){
			global $errors;
			$errors = new WP_Error();
			if( isset($_FILES[$field_slug]['name']) && $_FILES[$field_slug]['name'] != ''){
				////////////////////////////UPLOAD PROFILE PICTURE//////////////////////////////
				$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "jpg", "JPG", "png", "PNG", "bmp", "BMP" );
				$result = $this->piereg_validate_files(sanitize_file_name($_FILES[$field_slug]['name']),$allowedExts);
				if($result)
				{
					$temp = explode(".", sanitize_file_name($_FILES[$field_slug]["name"]));
					$extension = end($temp);
					$upload_dir = wp_upload_dir();
					$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
					wp_mkdir_p($temp_dir);
					$temp_file_name = sanitize_file_name("profile_pic_".abs( crc32( wp_generate_password( rand(7,12) ) ."_".time() ) )."_".$form_id.".".$extension);
					$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
					if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name)){
						$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_fail_to_upload_profile picture",__('Failed to upload the profile picture.','pie-register' )));
					}else{
						/*Upload Index.html file on User dir*/
						$this->upload_forbidden_html_file( realpath($upload_dir['basedir'])."/piereg_users_files" );
						/*Upload Index.html file on User dir*/
						$this->upload_forbidden_html_file( $temp_dir );
						
						$old_picture = get_user_meta($user_id,"pie_".$field_slug, true);
						if( !empty($old_picture) ){
							if( file_exists($temp_dir."/".basename( $old_picture )) ){
								unlink( $temp_dir."/".basename( $old_picture ) );
							}
						}
						update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
						$this->pie_success = 1;
					}
					
				}else{
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_invalid_file_type_in_profile_picture",__('Invalid file type used for profile picture.','pie-register' )));
					$this->pie_error = 1;
				}
			}else{
				update_user_meta($user_id,"pie_".$field_slug, "");
			}
	
		}
		function upload_forbidden_html_file($dir_name){
			if( !empty($dir_name) && !file_exists($dir_name."/index.html") ){
				$myfile = @fopen($dir_name."/index.html", "w");
				@fwrite( $myfile, "<html><head><title>Forbidden</title></head><body><h1>Forbidden</h1><p>You Don't have permission to access on this server</p></body></html>" );
				@fclose( $myfile );
			}
		}
		function pie_remove_upload($user_id,$field,$field_slug) {
			$upload_dir = wp_upload_dir();
			$temp_dir 	= realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
			$file 		= get_user_meta($user_id,"pie_".$field_slug, true);
			if( !empty($file ) ){
				if( file_exists($temp_dir."/".basename( $file  )) ){
					unlink( $temp_dir."/".basename( $file  ) );
				}
			}
			delete_user_meta($user_id,"pie_".$field_slug);
		}
		function pie_upload_files($user_id,$field,$field_slug,$form_id=0){
			global $errors;
			$errors = new WP_Error();
			$result = false;
			if($_FILES[$field_slug]['name'] != ''){
				///////////////////UPLOAD ALL FILES//////////////////////////
				
				if($field['file_types'] != ""){
					$filter_string = stripcslashes($field['file_types']);
					$filter_array = explode(",",$filter_string);
					$result = $this->piereg_validate_files($_FILES[$field_slug]['name'],$filter_array);
					
					if($result){
						$temp = explode(".", sanitize_file_name($_FILES[$field_slug]["name"]));
						$extension = end($temp);
						$upload_dir = wp_upload_dir();
						$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
						wp_mkdir_p($temp_dir);
						$temp_file_name = sanitize_file_name("file_".abs( crc32( wp_generate_password( rand(7,12) ) ."_".time() ) )."_".$form_id.".".$extension);
						$temp_file_url 	= $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
						if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name)){
							$errors->add( $field_slug , '<strong>'.__(ucwords('error'),'pie-register').'</strong>: '.apply_filters("piereg_Fail_to_upload_profile_picture",__('Failed to upload the profile picture.','pie-register' )));
						}else{
							/*Upload Index.html file on User dir*/
							$this->upload_forbidden_html_file( realpath($upload_dir['basedir'])."/piereg_users_files" );
							/*Upload Index.html file on User dir*/
							$this->upload_forbidden_html_file( $temp_dir );
							
							$old_file = get_user_meta($user_id,"pie_".$field_slug, true);
							if( !empty($old_file) ){
								if( file_exists($temp_dir."/".basename( $old_file )) ){
									unlink( $temp_dir."/".basename( $old_file ) );
								}
							}
							update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
							$this->pie_success = 1;
						}
					}else{
						$errors->add( $field_slug , '<strong>'.__(ucwords('error'),'pie-register').'</strong>: '.apply_filters("piereg_invalid_file",__('Invalid File.','pie-register' )));
					}
				}
				elseif($field['file_types'] == ""){
					$temp = explode(".", sanitize_file_name($_FILES[$field_slug]["name"]));
					$extension = end($temp);
					$upload_dir = wp_upload_dir();
					$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
					wp_mkdir_p($temp_dir);
					$temp_file_name = sanitize_file_name("file_".abs( crc32( wp_generate_password( rand(7,12) ) ."_".time() ) )."_".$form_id.".".$extension);
					$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
					if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name)){
						$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_fail_to_upload_profile_picture",__('Failed to upload the profile picture.','pie-register' )));
					}else{
						/*Upload Index.html file on User dir*/
						$this->upload_forbidden_html_file( realpath($upload_dir['basedir'])."/piereg_users_files" );
						/*Upload Index.html file on User dir*/
						$this->upload_forbidden_html_file( $temp_dir );
						
						$old_file = get_user_meta($user_id,"pie_".$field_slug, true);
						if( !empty($old_file) ){
							if( file_exists($temp_dir."/".basename( $old_file )) ){
								unlink( $temp_dir."/".basename( $old_file ) );
							}
						}
						update_user_meta($user_id,"pie_".$field_slug, $temp_file_url);
						$this->pie_success = 1;
					}
				}
			}else{
				update_user_meta($user_id,"pie_".$field_slug, "");
			}
		}
		/*
			*	Check SSL enable or not. And return true/false
		*/
		function PR_IS_SSL(){
			$piereg_secure_cookie = false;
			if ( !force_ssl_admin() && is_ssl() ) {
				$piereg_secure_cookie = true;
				force_ssl_admin(true);
			}
			return $piereg_secure_cookie;
		}
		function require_once_file($file_name = false)
		{
			if($file_name)
			{
				if(file_exists($file_name))
					require_once($file_name);
				else
					echo '<div style="background: none repeat scroll 0 0 rgb(252, 214, 214);border: 1px solid rgb(204, 204, 204);color: rgb(145, 7, 7);display: inline-block;margin: 5px 0;padding: 10px;width: auto;"><b>Warning :</b> File ( <b>'.(basename($file_name)).'</b> ) not found! [DIR : '.$file_name.']</div>';
			}
			return false;
		}
		/*
			*	Error Logging
			*	Error_log Message Type
			0 	message is sent to PHP's system logger, using the Operating System's system logging mechanism or a file, depending on what the error_log configuration directive is set to. This is the default option.
			1 	message is sent by email to the address in the destination parameter. This is the only message type where the fourth parameter, extra_headers is used.
			2 	No longer an option.
			3 	message is appended to the file destination. A newline is not automatically added to the end of the message string.
			4 	message is sent directly to the SAPI logging handler. 
		*/
		function pr_error_log($error,$error_type = 'error',$message_type = 3){
			if(!$error)
				return false;

			$file_dir = PIE_LOG_FILE;
			if(!file_exists($file_dir))
			{
				return false;
			}
			
			$error_header = "[".date_i18n("D d-m-Y H:i:s")."] [Client : ".$_SERVER['REMOTE_ADDR']."] [URI : ".$_SERVER['REQUEST_URI']."] [".$error_type."] ";
			$error_message = $error_header.$error."\r\n\r\n";
			return error_log($error_message, $message_type, PIE_LOG_FILE."/piereg_log.log");
		}
		function pr_payment_log($log_message,$file_name = "payment-log",$error_type = 'error',$message_type = 3){
			if(!$log_message)
				return false;

			$file_dir = PIE_LOG_FILE;
			if(!file_exists($file_dir))
			{
				return false;
			}
			
			$log_message = $log_message."\r\n============================================\r\n";
			return error_log($log_message, $message_type, PIE_LOG_FILE."/".$file_name.".log");
		}
		/*
			*	Return error info
		*/
		function get_error_log_info($function = "",$line = "",$file = ""){
			return " [Function : ".($function)."] [Line : ".($line)."] [File : ".($file)."]";
		}
		/*
			* Set PR forms Stats
		*/
		function set_pr_stats($stats,$type){
			if(!$stats || !$type)
				return false;
			
			switch($stats){
				case "login":
				case "forgot":
				case "register":
					if($type === "view" || $type === "used"){
						$piereg_stats = get_option(PIEREG_STATS_OPTION);
						if(!isset($piereg_stats[$stats][$type]))
							$piereg_stats[$stats][$type] = 1;
						else
							$piereg_stats[$stats][$type] = (intval($piereg_stats[$stats][$type]) + 1);
						
						update_option(PIEREG_STATS_OPTION,$piereg_stats);
						return true;
					}
					return false;
				break;
				default:
					return false;
				break;
			}
		}
		static function static_set_pr_stats($stats,$type){
			if(!$stats || !$type)
				return false;
			
			switch($stats){
				case "login":
				case "forgot":
				case "register":
					if($type === "view" || $type === "used"){
						$piereg_stats = get_option(PIEREG_STATS_OPTION);
						if(!isset($piereg_stats[$stats][$type]))
							$piereg_stats[$stats][$type] = 1;
						else
							$piereg_stats[$stats][$type] = (intval($piereg_stats[$stats][$type]) + 1);
						
						update_option(PIEREG_STATS_OPTION,$piereg_stats);
						return true;
					}
					return false;
				break;
				default:
					return false;
				break;
			}
		}
		/*
			*	Save currency name with array
		*/
		function piereg_save_currency(){
                    $old_currency_array = get_option(PIEREG_CURRENCY_OPTION);
					$currency_array[0]["code"] = "AFN";$currency_array[0]["name"] = "Afghanistan Afghani";
					$currency_array[1]["code"] = "ALL";$currency_array[1]["name"] = "Albania Lek";
					$currency_array[2]["code"] = "DZD";$currency_array[2]["name"] = "Algeria Dinar";
					$currency_array[3]["code"] = "AOA";$currency_array[3]["name"] = "Angola Kwanza";
					$currency_array[4]["code"] = "ARS";$currency_array[4]["name"] = "Argentina Peso";
					$currency_array[5]["code"] = "AMD";$currency_array[5]["name"] = "Armenia Dram";
					$currency_array[6]["code"] = "AWG";$currency_array[6]["name"] = "Aruba Guilder";
					$currency_array[7]["code"] = "AUD";$currency_array[7]["name"] = "Australia Dollar";
					$currency_array[8]["code"] = "AZN";$currency_array[8]["name"] = "Azerbaijan New Manat";
					$currency_array[9]["code"] = "BSD";$currency_array[9]["name"] = "Bahamas Dollar";
					$currency_array[10]["code"] = "BHD";$currency_array[10]["name"] = "Bahrain Dinar";
					$currency_array[11]["code"] = "BDT";$currency_array[11]["name"] = "Bangladesh Taka";
					$currency_array[12]["code"] = "BBD";$currency_array[12]["name"] = "Barbados Dollar";
					$currency_array[13]["code"] = "BYR";$currency_array[13]["name"] = "Belarus Ruble";
					$currency_array[14]["code"] = "BZD";$currency_array[14]["name"] = "Belize Dollar";
					$currency_array[15]["code"] = "BMD";$currency_array[15]["name"] = "Bermuda Dollar";
					$currency_array[16]["code"] = "BTN";$currency_array[16]["name"] = "Bhutan Ngultrum";
					$currency_array[17]["code"] = "BOB";$currency_array[17]["name"] = "Bolivia Boliviano";
					$currency_array[18]["code"] = "BAM";$currency_array[18]["name"] = "Bosnia and Herzegovina Convertible Marka";
					$currency_array[19]["code"] = "BWP";$currency_array[19]["name"] = "Botswana Pula";
					$currency_array[20]["code"] = "BRL";$currency_array[20]["name"] = "Brazil Real";
					$currency_array[21]["code"] = "BND";$currency_array[21]["name"] = "Brunei Darussalam Dollar";
					$currency_array[22]["code"] = "BGN";$currency_array[22]["name"] = "Bulgaria Lev";
					$currency_array[23]["code"] = "BIF";$currency_array[23]["name"] = "Burundi Franc";
					$currency_array[24]["code"] = "KHR";$currency_array[24]["name"] = "Cambodia Riel";
					$currency_array[25]["code"] = "CAD";$currency_array[25]["name"] = "Canada Dollar";
					$currency_array[26]["code"] = "CVE";$currency_array[26]["name"] = "Cape Verde Escudo";
					$currency_array[27]["code"] = "KYD";$currency_array[27]["name"] = "Cayman Islands Dollar";
					$currency_array[28]["code"] = "CLP";$currency_array[28]["name"] = "Chile Peso";
					$currency_array[29]["code"] = "CNY";$currency_array[29]["name"] = "China Yuan Renminbi";
					$currency_array[30]["code"] = "COP";$currency_array[30]["name"] = "Colombia Peso";
					$currency_array[31]["code"] = "XOF";$currency_array[31]["name"] = "CommunautÃ© FinanciÃ¨re Africaine (BCEAO) Franc";
					$currency_array[32]["code"] = "XAF";$currency_array[32]["name"] = "CommunautÃ© FinanciÃ¨re Africaine (BEAC) CFA Franc BEAC";
					$currency_array[33]["code"] = "KMF";$currency_array[33]["name"] = "Comoros Franc";
					$currency_array[34]["code"] = "XPF";$currency_array[34]["name"] = "Comptoirs FranÃ§ais du Pacifique (CFP) Franc";
					$currency_array[35]["code"] = "CDF";$currency_array[35]["name"] = "Congo/Kinshasa Franc";
					$currency_array[36]["code"] = "CRC";$currency_array[36]["name"] = "Costa Rica Colon";
					$currency_array[37]["code"] = "HRK";$currency_array[37]["name"] = "Croatia Kuna";
					$currency_array[38]["code"] = "CUC";$currency_array[38]["name"] = "Cuba Convertible Peso";
					$currency_array[39]["code"] = "CUP";$currency_array[39]["name"] = "Cuba Peso";
					$currency_array[40]["code"] = "CZK";$currency_array[40]["name"] = "Czech Republic Koruna";
					$currency_array[41]["code"] = "DKK";$currency_array[41]["name"] = "Denmark Krone";
					$currency_array[42]["code"] = "DJF";$currency_array[42]["name"] = "Djibouti Franc";
					$currency_array[43]["code"] = "DOP";$currency_array[43]["name"] = "Dominican Republic Peso";
					$currency_array[44]["code"] = "XCD";$currency_array[44]["name"] = "East Caribbean Dollar";
					$currency_array[45]["code"] = "EGP";$currency_array[45]["name"] = "Egypt Pound";
					$currency_array[46]["code"] = "SVC";$currency_array[46]["name"] = "El Salvador Colon";
					$currency_array[47]["code"] = "ERN";$currency_array[47]["name"] = "Eritrea Nakfa";
					$currency_array[48]["code"] = "ETB";$currency_array[48]["name"] = "Ethiopia Birr";
					$currency_array[49]["code"] = "EUR";$currency_array[49]["name"] = "Euro Member Countries";
					$currency_array[50]["code"] = "FKP";$currency_array[50]["name"] = "Falkland Islands (Malvinas) Pound";
					$currency_array[51]["code"] = "FJD";$currency_array[51]["name"] = "Fiji Dollar";
					$currency_array[52]["code"] = "GMD";$currency_array[52]["name"] = "Gambia Dalasi";
					$currency_array[53]["code"] = "GEL";$currency_array[53]["name"] = "Georgia Lari";
					$currency_array[54]["code"] = "GHS";$currency_array[54]["name"] = "Ghana Cedi";
					$currency_array[55]["code"] = "GIP";$currency_array[55]["name"] = "Gibraltar Pound";
					$currency_array[56]["code"] = "GTQ";$currency_array[56]["name"] = "Guatemala Quetzal";
					$currency_array[57]["code"] = "GGP";$currency_array[57]["name"] = "Guernsey Pound";
					$currency_array[58]["code"] = "GNF";$currency_array[58]["name"] = "Guinea Franc";
					$currency_array[59]["code"] = "GYD";$currency_array[59]["name"] = "Guyana Dollar";
					$currency_array[60]["code"] = "HTG";$currency_array[60]["name"] = "Haiti Gourde";
					$currency_array[61]["code"] = "HNL";$currency_array[61]["name"] = "Honduras Lempira";
					$currency_array[62]["code"] = "HKD";$currency_array[62]["name"] = "Hong Kong Dollar";
					$currency_array[63]["code"] = "HUF";$currency_array[63]["name"] = "Hungary Forint";
					$currency_array[64]["code"] = "ISK";$currency_array[64]["name"] = "Iceland Krona";
					$currency_array[65]["code"] = "INR";$currency_array[65]["name"] = "India Rupee";
					$currency_array[66]["code"] = "IDR";$currency_array[66]["name"] = "Indonesia Rupiah";
					$currency_array[67]["code"] = "XDR";$currency_array[67]["name"] = "International Monetary Fund (IMF) Special Drawing Rights";
					$currency_array[68]["code"] = "IRR";$currency_array[68]["name"] = "Iran Rial";
					$currency_array[69]["code"] = "IQD";$currency_array[69]["name"] = "Iraq Dinar";
					$currency_array[70]["code"] = "IMP";$currency_array[70]["name"] = "Isle of Man Pound";
					$currency_array[71]["code"] = "ILS";$currency_array[71]["name"] = "Israel Shekel";
					$currency_array[72]["code"] = "JMD";$currency_array[72]["name"] = "Jamaica Dollar";
					$currency_array[73]["code"] = "JPY";$currency_array[73]["name"] = "Japan Yen";
					$currency_array[74]["code"] = "JEP";$currency_array[74]["name"] = "Jersey Pound";
					$currency_array[75]["code"] = "JOD";$currency_array[75]["name"] = "Jordan Dinar";
					$currency_array[76]["code"] = "KZT";$currency_array[76]["name"] = "Kazakhstan Tenge";
					$currency_array[77]["code"] = "KES";$currency_array[77]["name"] = "Kenya Shilling";
					$currency_array[78]["code"] = "KPW";$currency_array[78]["name"] = "Korea (North) Won";
					$currency_array[79]["code"] = "KRW";$currency_array[79]["name"] = "Korea (South) Won";
					$currency_array[80]["code"] = "KWD";$currency_array[80]["name"] = "Kuwait Dinar";
					$currency_array[81]["code"] = "KGS";$currency_array[81]["name"] = "Kyrgyzstan Som";
					$currency_array[82]["code"] = "LAK";$currency_array[82]["name"] = "Laos Kip";
					$currency_array[83]["code"] = "LBP";$currency_array[83]["name"] = "Lebanon Pound";
					$currency_array[84]["code"] = "LSL";$currency_array[84]["name"] = "Lesotho Loti";
					$currency_array[85]["code"] = "LRD";$currency_array[85]["name"] = "Liberia Dollar";
					$currency_array[86]["code"] = "LYD";$currency_array[86]["name"] = "Libya Dinar";
					$currency_array[87]["code"] = "MOP";$currency_array[87]["name"] = "Macau Pataca";
					$currency_array[88]["code"] = "MKD";$currency_array[88]["name"] = "Macedonia Denar";
					$currency_array[89]["code"] = "MGA";$currency_array[89]["name"] = "Madagascar Ariary";
					$currency_array[90]["code"] = "MWK";$currency_array[90]["name"] = "Malawi Kwacha";
					$currency_array[91]["code"] = "MYR";$currency_array[91]["name"] = "Malaysia Ringgit";
					$currency_array[92]["code"] = "MVR";$currency_array[92]["name"] = "Maldives (Maldive Islands) Rufiyaa";
					$currency_array[93]["code"] = "MRO";$currency_array[93]["name"] = "Mauritania Ouguiya";
					$currency_array[94]["code"] = "MUR";$currency_array[94]["name"] = "Mauritius Rupee";
					$currency_array[95]["code"] = "MXN";$currency_array[95]["name"] = "Mexico Peso";
					$currency_array[96]["code"] = "MDL";$currency_array[96]["name"] = "Moldova Leu";
					$currency_array[97]["code"] = "MNT";$currency_array[97]["name"] = "Mongolia Tughrik";
					$currency_array[98]["code"] = "MAD";$currency_array[98]["name"] = "Morocco Dirham";
					$currency_array[99]["code"] = "MZN";$currency_array[99]["name"] = "Mozambique Metical";
					$currency_array[100]["code"] = "MMK";$currency_array[100]["name"] = "Myanmar (Burma) Kyat";
					$currency_array[101]["code"] = "NAD";$currency_array[101]["name"] = "Namibia Dollar";
					$currency_array[102]["code"] = "NPR";$currency_array[102]["name"] = "Nepal Rupee";
					$currency_array[103]["code"] = "ANG";$currency_array[103]["name"] = "Netherlands Antilles Guilder";
					$currency_array[104]["code"] = "NZD";$currency_array[104]["name"] = "New Zealand Dollar";
					$currency_array[105]["code"] = "NIO";$currency_array[105]["name"] = "Nicaragua Cordoba";
					$currency_array[106]["code"] = "NGN";$currency_array[106]["name"] = "Nigeria Naira";
					$currency_array[107]["code"] = "NOK";$currency_array[107]["name"] = "Norway Krone";
					$currency_array[108]["code"] = "OMR";$currency_array[108]["name"] = "Oman Rial";
					$currency_array[109]["code"] = "PKR";$currency_array[109]["name"] = "Pakistan Rupee";
					$currency_array[110]["code"] = "PAB";$currency_array[110]["name"] = "Panama Balboa";
					$currency_array[111]["code"] = "PGK";$currency_array[111]["name"] = "Papua New Guinea Kina";
					$currency_array[112]["code"] = "PYG";$currency_array[112]["name"] = "Paraguay Guarani";
					$currency_array[113]["code"] = "PEN";$currency_array[113]["name"] = "Peru Nuevo Sol";
					$currency_array[114]["code"] = "PHP";$currency_array[114]["name"] = "Philippines Peso";
					$currency_array[115]["code"] = "PLN";$currency_array[115]["name"] = "Poland Zloty";
					$currency_array[116]["code"] = "QAR";$currency_array[116]["name"] = "Qatar Riyal";
					$currency_array[117]["code"] = "RON";$currency_array[117]["name"] = "Romania New Leu";
					$currency_array[118]["code"] = "RUB";$currency_array[118]["name"] = "Russia Ruble";
					$currency_array[119]["code"] = "RWF";$currency_array[119]["name"] = "Rwanda Franc";
					$currency_array[120]["code"] = "SHP";$currency_array[120]["name"] = "Saint Helena Pound";
					$currency_array[121]["code"] = "WST";$currency_array[121]["name"] = "Samoa Tala";
					$currency_array[122]["code"] = "SAR";$currency_array[122]["name"] = "Saudi Arabia Riyal";
					$currency_array[123]["code"] = "SPL*";$currency_array[123]["name"] = "Seborga Luigino";
					$currency_array[124]["code"] = "RSD";$currency_array[124]["name"] = "Serbia Dinar";
					$currency_array[125]["code"] = "SCR";$currency_array[125]["name"] = "Seychelles Rupee";
					$currency_array[126]["code"] = "SLL";$currency_array[126]["name"] = "Sierra Leone Leone";
					$currency_array[127]["code"] = "SGD";$currency_array[127]["name"] = "Singapore Dollar";
					$currency_array[128]["code"] = "SBD";$currency_array[128]["name"] = "Solomon Islands Dollar";
					$currency_array[129]["code"] = "SOS";$currency_array[129]["name"] = "Somalia Shilling";
					$currency_array[130]["code"] = "ZAR";$currency_array[130]["name"] = "South Africa Rand";
					$currency_array[131]["code"] = "LKR";$currency_array[131]["name"] = "Sri Lanka Rupee";
					$currency_array[132]["code"] = "SDG";$currency_array[132]["name"] = "Sudan Pound";
					$currency_array[133]["code"] = "SRD";$currency_array[133]["name"] = "Suriname Dollar";
					$currency_array[134]["code"] = "SZL";$currency_array[134]["name"] = "Swaziland Lilangeni";
					$currency_array[135]["code"] = "SEK";$currency_array[135]["name"] = "Sweden Krona";
					$currency_array[136]["code"] = "CHF";$currency_array[136]["name"] = "Switzerland Franc";
					$currency_array[137]["code"] = "SYP";$currency_array[137]["name"] = "Syria Pound";
					$currency_array[138]["code"] = "STD";$currency_array[138]["name"] = "SÃ£o TomÃ© and PrÃ­ncipe Dobra";
					$currency_array[139]["code"] = "TWD";$currency_array[139]["name"] = "Taiwan New Dollar";
					$currency_array[140]["code"] = "TJS";$currency_array[140]["name"] = "Tajikistan Somoni";
					$currency_array[141]["code"] = "TZS";$currency_array[141]["name"] = "Tanzania Shilling";
					$currency_array[142]["code"] = "THB";$currency_array[142]["name"] = "Thailand Baht";
					$currency_array[143]["code"] = "TOP";$currency_array[143]["name"] = "Tonga Pa'anga";
					$currency_array[144]["code"] = "TTD";$currency_array[144]["name"] = "Trinidad and Tobago Dollar";
					$currency_array[145]["code"] = "TND";$currency_array[145]["name"] = "Tunisia Dinar";
					$currency_array[146]["code"] = "TRY";$currency_array[146]["name"] = "Turkey Lira";
					$currency_array[147]["code"] = "TMT";$currency_array[147]["name"] = "Turkmenistan Manat";
					$currency_array[148]["code"] = "TVD";$currency_array[148]["name"] = "Tuvalu Dollar";
					$currency_array[149]["code"] = "UGX";$currency_array[149]["name"] = "Uganda Shilling";
					$currency_array[150]["code"] = "UAH";$currency_array[150]["name"] = "Ukraine Hryvnia";
					$currency_array[151]["code"] = "AED";$currency_array[151]["name"] = "United Arab Emirates Dirham";
					$currency_array[152]["code"] = "GBP";$currency_array[152]["name"] = "United Kingdom Pound";
					$currency_array[153]["code"] = "USD";$currency_array[153]["name"] = "United States Dollar";
					$currency_array[154]["code"] = "UYU";$currency_array[154]["name"] = "Uruguay Peso";
					$currency_array[155]["code"] = "UZS";$currency_array[155]["name"] = "Uzbekistan Som";
					$currency_array[156]["code"] = "VUV";$currency_array[156]["name"] = "Vanuatu Vatu";
					$currency_array[157]["code"] = "VEF";$currency_array[157]["name"] = "Venezuela Bolivar";
					$currency_array[158]["code"] = "VND";$currency_array[158]["name"] = "Viet Nam Dong";
					$currency_array[159]["code"] = "YER";$currency_array[159]["name"] = "Yemen Rial";
					$currency_array[160]["code"] = "ZMW";$currency_array[160]["name"] = "Zambia Kwacha";
					$currency_array[161]["code"] = "ZWD";$currency_array[161]["name"] = "Zimbabwe Dollar";
                    update_option(PIEREG_CURRENCY_OPTION,$currency_array);
		}
		function read_upload_file($file_dir){
			
			if(!file_exists($file_dir) || empty($file_dir))
			{
				$this->pie_post_array['error'] = __("File does not exist.","pie-register");
				return false;
			}
			//To read the file
			$FileData = "";
			if(function_exists("file_get_contents") && ini_get('allow_url_fopen')){
				$FileData = file_get_contents($file_dir,false);
			}
			//Get Log File by `fopen`
			elseif(function_exists("fopen")){
				$fh = fopen($file_dir, 'r');
				$FileData = fread($fh, filesize($file_dir));
				fclose($fh);
			}
			//Get Log File by `Command`
			else{
				$FileData = `cat $file_dir`;
			}
			return $FileData;
		}
		function read_file_from_url($url){
			$FileData = "";
			//Read URL By WP_Http
			//Use: To get google recaptcha on forms. 
			$request 	= wp_remote_get($url);
			if ( is_array( $request ) && ! is_wp_error( $request ) ) {
				$FileData 	= wp_remote_retrieve_body( $request );
			}

			return $FileData;
		}
		function get_pr_forms_info()
		{
			$pr_form = array();
			$fields_id = get_option("piereg_form_fields_id");
			if(!empty($fields_id))
			{
				$count = 0;
				for($a=1;$a<=$fields_id;$a++)
				{
					$option = get_option("piereg_form_field_option_".$a);
					if( !empty($option) && is_array($option) && isset($option['Id']) && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) ) 
						$pr_form[$a] = $option;
				}
			}
			return $pr_form;
		}
		// since 3.7.0.0
		// Gets form(s) by checking the LK
		// TODO: Simplify
		function get_pr_forms_info_check()
		{
			$pr_form = array();
			$fields_id = get_option("piereg_form_fields_id");
			if(!empty($fields_id))
			{
				$count = 0;
				for($a=1;$a<=$fields_id;$a++)
				{
					$option = get_option("piereg_form_field_option_".$a);
					if( !empty($option) && is_array($option) && isset($option['Id']) && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) ) 
						$pr_form[$a] = $option;

						if(!$this->piereg_pro_is_activate)
							break;

				}
			}
			return $pr_form;
		}
		function get_page_uri($page_id, $query_string = ""){
			$this->pr_get_WP_Rewrite();
			if(!empty($query_string))
				return $this->pie_modify_custom_url(get_permalink($page_id),$query_string);
			else
				return get_permalink(intval($page_id));
		}
		static function static_get_page_uri($page_id, $query_string = ""){
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			
			if(!empty($query_string))
				return PieReg_Base::static_pie_modify_custom_url(get_permalink($page_id),$query_string);
			else
				return get_permalink(intval($page_id));
		}
		function get_current_permalink(){
			$this->pr_get_WP_Rewrite();
			return get_permalink();
		}
		static function static_get_current_permalink(){
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
			
			return get_permalink();
		}
		function pr_get_WP_Rewrite(){
			if ( empty( $GLOBALS['wp_rewrite'] ) )
				$GLOBALS['wp_rewrite'] = new WP_Rewrite();
		}
		function include_pr_menu_pages($page_dir = false){
			$this->require_once_file($page_dir);
		}		
				
		function piereg_get_wp_plugable_file( $required = false, $file_name = "",$function_name = ""){
			if($file_name == "" || $function_name = ""){
				if(!function_exists('wp_get_current_user')) {
					if($required) {
						require_once(ABSPATH . WPINC . "/pluggable.php");
					} else {
						include(ABSPATH . WPINC . "/pluggable.php"); 
					}
				}
			}
			elseif(!function_exists($function_name)) {
				if(file_exists(ABSPATH . WPINC . "/". $file_name.".php"))
					include(ABSPATH . WPINC . "/".$file_name.".php"); 
			}
		}
		
		/*
			*	Get currency code for payment method
		*/
		function pr_get_currency_code($option = array()){
			if( empty($option) || !is_array($option) )
				$options = $this->get_pr_global_options();
			
			return ( ( isset($options['currency']) && !empty($options['currency']) ) ? $options['currency'] : 'USD' );
		}
		
		/*
			*	Get and Update User Payment Log
		*/
		function update_user_payment_log($user_id,$payment_log){
			if( empty($user_id) || empty($payment_log) || !is_array($payment_log) )
				return false;
			
			$old_payment_log =  $this->get_user_payment_log( $user_id );
			$payment_log_array = array();
			
			if(!empty($old_payment_log))
				$payment_log_array = $old_payment_log;
			
			$payment_log_array[] = $payment_log;
			update_user_meta( $user_id, "piereg_user_payment_log", $payment_log_array);
			return $payment_log_array;
		}
		function get_user_payment_log($user_id){
			if( empty($user_id) )
				return false;
			$payment_log =  get_user_meta( $user_id, "piereg_user_payment_log", true );
			return $payment_log;
		}
		
		/*
			*	Sanitize  All Post Fields
		*/
		function piereg_sanitize_post_data_escape($post = array()){
			if(!is_array($post) || empty($post))
				return false;
			
			foreach($post as $key=>$val){
				if( isset($_POST[$key]) && strpos($key,"username") !== false ){
					$post[$key] = sanitize_user($_POST[$key]);
				}elseif( isset($_POST[$key]) && ( strpos($key,"email") !== false ||  strpos($key,"e_mail") !== false ) ){
					$post[$key] = sanitize_email($_POST[$key]);
				}elseif( isset($_POST[$key]) ){
					$post[$key] = $this->piereg_post_array_filter($_POST[$key]);
				}
			}
			
			return $post;
		}
		
		function piereg_sanitize_post_data($form = '',$post = array()){
			if(!is_array($post) || empty($post) || $form == '')
				return false;


			switch ($form) {
				case 'payment_gateway_page':
					
					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'enable_paypal':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'piereg_paypal_butt_id':
							case 'piereg_paypal_sandbox':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'payment_gateway_general_settings':
					
					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'payment_success_msg':
							case 'payment_faild_msg':
							case 'payment_renew_msg':
							case 'payment_already_activate_msg':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'admin_email_notification_page':
					
					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'admin_from_name':
							case 'admin_subject_email':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;

							case 'admin_sendto_email':
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
							
							case 'admin_to_email':
							case 'admin_bcc_email':
								$post[$key] = $this->sanitize_validate_as('email',$value);
								break;

							case 'enable_admin_notifications':
							case 'admin_message_email_formate':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'admin_message_email':
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;
							
							default:
								//$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'user_email_notification_page':
					
					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'user_email_type':

							case 'user_from_name_default_template':
							case 'user_from_name_admin_verification':
							case 'user_from_name_email_verification':
							case 'user_from_name_email_edit_verification':
							case 'user_from_name_current_email_verification':
							case 'user_from_name_email_thankyou':
							case 'user_from_name_forgot_password_notification':
							case 'user_from_name_pending_payment':
							case 'user_from_name_payment_success':
							case 'user_from_name_payment_faild':
							case 'user_from_name_pending_payment_reminder':
							case 'user_from_name_email_verification_reminder':
							case 'user_from_name_user_expiry_notice':
							case 'user_from_name_user_temp_blocked_notice':
							case 'user_from_name_user_renew_temp_blocked_account_notice':
							case 'user_from_name_user_perm_blocked_notice':

							case 'user_subject_email_default_template':
							case 'user_subject_email_admin_verification':
							case 'user_subject_email_email_verification':
							case 'user_subject_email_email_edit_verification':
							case 'user_subject_email_current_email_verification':
							case 'user_subject_email_email_thankyou':
							case 'user_subject_email_forgot_password_notification':
							case 'user_subject_email_pending_payment':
							case 'user_subject_email_payment_success':
							case 'user_subject_email_payment_faild':
							case 'user_subject_email_pending_payment_reminder':
							case 'user_subject_email_email_verification_reminder':
							case 'user_subject_email_user_expiry_notice':
							case 'user_subject_email_user_temp_blocked_notice':
							case 'user_subject_email_user_renew_temp_blocked_account_notice':
							case 'user_subject_email_user_perm_blocked_notice':

								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;

							case 'user_from_email_default_template':
							case 'user_from_email_admin_verification':
							case 'user_from_email_email_verification':
							case 'user_from_email_email_edit_verification':
							case 'user_from_email_current_email_verification':
							case 'user_from_email_email_thankyou':
							case 'user_from_email_forgot_password_notification':
							case 'user_from_email_pending_payment':
							case 'user_from_email_payment_success':
							case 'user_from_email_payment_faild':
							case 'user_from_email_pending_payment_reminder':
							case 'user_from_email_email_verification_reminder':
							case 'user_from_email_user_expiry_notice':
							case 'user_from_email_user_temp_blocked_notice':
							case 'user_from_email_user_renew_temp_blocked_account_notice':
							case 'user_from_email_user_perm_blocked_notice':

							case 'user_to_email_default_template':
							case 'user_to_email_admin_verification':
							case 'user_to_email_email_verification':
							case 'user_to_email_email_edit_verification':
							case 'user_to_email_current_email_verification':
							case 'user_to_email_email_thankyou':
							case 'user_to_email_forgot_password_notification':
							case 'user_to_email_pending_payment':
							case 'user_to_email_payment_success':
							case 'user_to_email_payment_faild':
							case 'user_to_email_pending_payment_reminder':
							case 'user_to_email_email_verification_reminder':
							case 'user_to_email_user_expiry_notice':
							case 'user_to_email_user_temp_blocked_notice':
							case 'user_to_email_user_renew_temp_blocked_account_notice':
							case 'user_to_email_user_perm_blocked_notice':
								$post[$key] = $this->sanitize_validate_as('email',$value);
								break;

							case 'user_formate_email_default_template':
							case 'user_formate_email_admin_verification':
							case 'user_formate_email_email_verification':
							case 'user_formate_email_email_edit_verification':
							case 'user_formate_email_current_email_verification':
							case 'user_formate_email_email_thankyou':
							case 'user_formate_email_forgot_password_notification':
							case 'user_formate_email_pending_payment':
							case 'user_formate_email_payment_success':
							case 'user_formate_email_payment_faild':
							case 'user_formate_email_pending_payment_reminder':
							case 'user_formate_email_email_verification_reminder':
							case 'user_formate_email_user_expiry_notice':
							case 'user_formate_email_user_temp_blocked_notice':
							case 'user_formate_email_user_renew_temp_blocked_account_notice':
							case 'user_formate_email_user_perm_blocked_notice':

							case 'user_enable_default_template':
							case 'user_enable_admin_verification':
							case 'user_enable_email_verification':
							case 'user_enable_email_edit_verification':
							case 'user_enable_current_email_verification':
							case 'user_enable_email_thankyou':
							case 'user_enable_forgot_password_notification':
							case 'user_enable_pending_payment':
							case 'user_enable_payment_success':
							case 'user_enable_payment_faild':
							case 'user_enable_pending_payment_reminder':
							case 'user_enable_email_verification_reminder':
							case 'user_enable_user_expiry_notice':
							case 'user_enable_user_temp_blocked_notice':
							case 'user_enable_user_renew_temp_blocked_account_notice':
							case 'user_enable_user_perm_blocked_notice':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'user_message_email_default_template':
							case 'user_message_email_admin_verification':
							case 'user_message_email_email_verification':
							case 'user_message_email_email_edit_verification':
							case 'user_message_email_current_email_verification':
							case 'user_message_email_email_thankyou':
							case 'user_message_email_forgot_password_notification':
							case 'user_message_email_pending_payment':
							case 'user_message_email_payment_success':
							case 'user_message_email_payment_faild':
							case 'user_message_email_pending_payment_reminder':
							case 'user_message_email_email_verification_reminder':
							case 'user_message_email_user_expiry_notice':
							case 'user_message_email_user_temp_blocked_notice':
							case 'user_message_email_user_renew_temp_blocked_account_notice':
							case 'user_message_email_user_perm_blocked_notice':
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_settings_allusers':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'alternate_login':
							case 'alternate_register':
							case 'alternate_forgotpass':
							case 'alternate_profilepage':
							case 'after_login':
							case 'alternate_logout':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
							case 'login_after_register':
								$post[$key] = $this->sanitize_validate_as('number',$value);
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_license_key':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'piereg_license_key':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;

							case 'piereg_license_email':
								$post[$key] = $this->sanitize_validate_as('email',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_settings_ux':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'display_hints':
							case 'outputcss':
							case 'outputjquery_ui':
							case 'pr_theme':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'login_username_label':
							case 'login_username_placeholder':
							case 'login_password_label':
							case 'login_password_placeholder':
							case 'forgot_pass_username_label':
							case 'forgot_pass_username_placeholder':
							case 'custom_logo_tooltip':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
								
							case 'custom_css':
							case 'tracking_code':
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;

							case 'pie_custom_logo_url':
							case 'custom_logo_link':
								$post[$key] = $this->sanitize_validate_as('url',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_settings_overrides':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'redirect_user':
							case 'show_admin_bar':
							case 'block_WP_profile':
							case 'block_wp_login':
							case 'allow_pr_edit_wplogin':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_settings_security_b':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'captcha_in_login_value':
							case 'captcha_in_forgot_value':
							case 'grace_period':
							case 'email_edit_verification_step':
							case 'capthca_in_login':
							case 'piereg_security_attempts_login_value':
							case 'security_attempts_login_time':
							case 'security_attempts_login':
							case 'captcha_in_forgot_value':
							case 'piereg_security_attempts_forgot_value':
							case 'security_attempts_forgot_time':
							case 'security_attempts_forgot':
							case 'restrict_bot_enabel':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'captcha_publc':
							case 'capthca_in_login_label':
							case 'captcha_private':
							case 'verification':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
							
							case 'restrict_bot_content':
							case 'restrict_bot_content_message';
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;
				
				case 'piereg_settings_security_advanced';
					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'reg_form_submission_time_enable':
							case 'reg_form_submission_time':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}
					}

					break;
					
				case 'piereg_invitation_code':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{
							case 'enable_invitation_codes':
							case 'invitation_code_usage':
							case 'invitation_code_numbers':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;

							case 'piereg_codepass':
								$post[$key] = $this->sanitize_validate_as('html',$value);
							break;

							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_export_csv':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'pie_fields_csv':
							case 'date_start':
							case 'date_end':
							case 'pie_meta_csv':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_import_users':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'update_existing_users':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;

							case 'csvfile':
								$post[$key] = $this->sanitize_validate_as('file',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_form_builder':
					foreach ($post as $key => $value)
					{

						switch ($value['type'])
						{
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_restrict_users':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'piereg_blk_username':
							case 'piereg_blk_ip':
							case 'piereg_blk_email':
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;

							case 'enable_blockedusername':
							case 'enable_blockedips':
							case 'enable_blockedemail':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_allowed_users':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'piereg_ald_username':
							case 'piereg_ald_email':
								$post[$key] = $this->sanitize_validate_as('html',$value);
								break;

							case 'enable_allowedusername':
							case 'enable_allowedemail':
								$post[$key] = $this->sanitize_validate_as('number',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;	

				case 'piereg_redirect_settings':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'piereg_user_role':
							case 'log_in_page':
							case 'log_out_page':
								$post[$key] = $this->sanitize_validate_as('text',$value);
								break;

							case 'logged_in_url':
							case 'log_out_url':
								$post[$key] = $this->sanitize_validate_as('url',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;

				case 'piereg_login':

					foreach ($post as $key => $value)
					{
						switch ($key)
						{

							case 'log':
								$post[$key] = $this->sanitize_validate_as('user',$value);
								break;

							case 'redirect_to':
								$post[$key] = $this->sanitize_validate_as('url',$value);
								break;
							
							default:
								$post[$key] = $this->sanitize_validate_as('esc_textarea',$value);
								break;
						}	
					}

					break;
				case 'post_restriction':
					$npost = array();
					foreach ($post['post_restriction'] as $key => $value)
					{
						switch ( $key ) {

							case 'piereg_post_type':
								$npost[ $key ] = sanitize_key( $value );
								break;
		
							case 'piereg_post_visibility':
								$npost[ $key ] = sanitize_key( $value );
								break;
		
							case 'piereg_restriction_type':
								$npost[ $key ] = intval( $value );
								break;
		
							case 'piereg_redirect_url':
								$npost[ $key ] = esc_url_raw( $value );
								break;
		
							case 'piereg_redirect_page':
								$npost[ $key ] = intval( $value );
								break;
		
							case 'piereg_block_content':
								$npost[ $key ] = sanitize_textarea_field( $value );
								break;
		
						}
					}
					$post = $npost;
					
					break;	
				default:
					# do nothing...
					break;
			}

			return $post;
		}

		function recursive_sanitize_array($array) {
			foreach ( $array as $key => &$value ) {
				if ( is_array( $value ) ) {
					$value = $this->recursive_sanitize_array($value);
				}
				else {
					$value = sanitize_text_field( $value );
				}
			}
		
			return $array;
		}

		function sanitize_validate_as($type,$value){

			if( is_array($value) ){

				$_TMP_arr = array();

				foreach ($value as $k => $v) {
					$_TMP_arr[$k] = $this->sanitize_validate_as($type,$v);
					if($k === 'html')
					{
						$_TMP_arr[$k] = $this->sanitize_validate_as('html',$v);
					} else {
						$_TMP_arr[$k] = $this->sanitize_validate_as($type,$v);
					}
				}
				
				return $_TMP_arr;
			}

			switch ($type) {
				case 'text':
					return sanitize_text_field($value);
					break;

				case 'number':
					return filter_var($value,FILTER_SANITIZE_NUMBER_INT);
					break;

				case 'email':
					return sanitize_email($value);
					break;

				case 'html':
					return wp_kses($value,$this->alowed_iframe_tag_post());
					break;

				case 'url':
					return esc_url($value);
					break;

				case 'file':
					return sanitize_file_name($value);
					break;

				case 'user':
					return sanitize_user(stripslashes($value));
					break;
				
				case 'esc_textarea':
				default:
					return sanitize_textarea_field($value);
					break;
			}

		}		
		
		/*
			*	Add iframe in allowed tags ep_kses
		*/
		function alowed_iframe_tag_post() {
				$allowed_tags = wp_kses_allowed_html( 'post' );
				
				// iframe
				$allowed_tags['iframe'] = array(
					'src'             => array(),
					'height'          => array(),
					'width'           => array(),
					'frameborder'     => array(),
					'allowfullscreen' => array(),
				);
				
				return $allowed_tags;
		}
		
		/*
			*	Sanitize  All Get Data
		*/		
		function piereg_sanitize_get_data($get = array()){
			if(!is_array($get) || empty($get))
				return false;
			
			foreach($get as $key=>$val){
				if( isset($_GET[$key]) ){
					$_GET[$key] = $this->piereg_post_array_filter($_GET[$key]);
				}
			}
		}
		
		function piereg_post_array_filter($post){
			$new_post = $post;
			if( isset($new_post) && is_array($new_post) ){
				foreach($new_post as $k=>$val){
					$new_post[$k] = $this->piereg_post_array_filter($val);
				}
				return $new_post;
			}else{
				return (sanitize_textarea_field(trim($new_post)));
			}
		}
		
		function stripslashes_deep($value){
			return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		}
		
		function sanitize_field_options($value)
		{
			return !is_array($value) ? htmlspecialchars(str_replace(array('\/','\\'),'',stripslashes($value))) : $value;	
		}
		
		function piereg_save_payment_log_option($email,$method,$type,$responce){
			$date_time 			= date_i18n("d-m-Y H:i:s");
			$key 				= md5( time() );
			$data 				= get_option("piereg_payment_log_option");
			$duplicate_enty 	= false;
			
			if( $method == 'PayPal' && ( !empty($data) && is_array($data) ) ) {
				foreach($data as $k=>$v) {
					$key_exists = array_search($responce['txn_id'], $v['responce']);
					if( $key_exists == 'txn_id' ) {
						$duplicate_enty = true;
						break;	
					}
				}
			}
			
			if( empty($data) )
				$data = array();
			
			if(!$duplicate_enty) {			
				$data[$key]['email'] 	= $email;
				$data[$key]['method'] 	= $method;
				$data[$key]['type'] 	= $type;
				$data[$key]['responce'] = $responce;
				$data[$key]['date'] 	= $date_time;
				
				update_option( "piereg_payment_log_option", $data );
			}
		}
		
		function isUserIpsIsBlocked($current_ip, $array_ips)
		{
			$isblocked	= false;			
			foreach( $array_ips as $blockip ) {
				if( strpos($blockip,'-') !== false ) {
					$rangefrom 		= ip2long(substr($blockip, 0, strpos($blockip,"-")));
					$rangesexplode	= explode("-",$blockip);
					
					$arr 			= explode('.', $rangesexplode[0]);
					$ipstart 		= implode('.',	array_slice($arr, 0, 3));
					$rangeto		= ip2long($ipstart . "." . $rangesexplode[1]);
					
					if($current_ip >= $rangefrom && $current_ip <= $rangeto ){
						$isblocked = true;
						break;
					}
					
				} else if( ($current_ip == ip2long($blockip)) && ip2long($blockip)) {
						$isblocked = true;
						break;
				}				
			}
			
			if($isblocked){
				return true;				
			}else{
				return false;	
			}
		}
		
		
		function isUserNameIsBlocked( $current_username, $array_username) {
			$isblocked	= false;
			
			$userdata 			= get_user_by('login', $current_username);
			if( $userdata !== false && in_array('administrator', $userdata->roles) )
			{
				return false;
			}

			foreach($array_username as $username)
			{
				if(strpos($username,"*") !== false ) 
				{
					$username = str_replace("*","",$username);
					if( strpos($current_username,$username) !== false ) {
						$isblocked = true;
						break;
					}
				
				} else if($username == $current_username) {
					
						$isblocked = true;
						break;
				}
			}
			
			if($isblocked)
				return true;
			else
				return false;
			
		}
		
		function isEmailAddressIsBlocked( $current_emailaddr, $array_email ) {
			$isblocked	= false;
			
			$userdata 			= get_user_by('email', $current_emailaddr);
			if( $userdata !== false && in_array('administrator', $userdata->roles) )
			{
				return false;
			}

			foreach($array_email as $email)
			{
				if(strpos($email,"*") !== false ) 
				{
					$email = str_replace("*","",$email);
					if( strpos($current_emailaddr,$email) !== false ) {
						$isblocked = true;
						break;
					}
				
				} else if($email == $current_emailaddr) {
					
						$isblocked = true;
						break;
				}
			}
			
			if($isblocked)
				return true;
			else
				return false;
		}

		function isUserNameIsAllowed( $current_username, $array_username) {
			$isblocked	= false;
			
			$userdata 			= get_user_by('login', $current_username);
			if($userdata !== false && in_array('administrator', $userdata->roles) )
			{
				return false;
			}

			foreach($array_username as $username)
			{
				if(strpos($username,"*") !== false ) 
				{
					$username = str_replace("*","",$username);
					if( strpos($current_username,$username) !== false ) {
						$isblocked = true;
						break;
					}
				
				} else if($username == $current_username) {
					
						$isblocked = true;
						break;
				}
			}
			
			if($isblocked)
				return false;
			else
				return true;
			
		}
		
		function isEmailAddressIsAllowed( $current_emailaddr, $array_email ) {
			$isblocked	= false;
			
			$userdata 	= get_user_by('email', $current_emailaddr);
			if( $userdata !== false && in_array('administrator', $userdata->roles) )
			{
				return false;
			}

			foreach($array_email as $email)
			{
				if(strpos($email,"*") !== false ) 
				{
					$email = str_replace("*","",$email);
					if( strpos($current_emailaddr,$email) !== false ) {
						$isblocked = true;
						break;
					}
				
				} else if($email == $current_emailaddr) {
					
						$isblocked = true;
						break;
				}
			}
			
			if($isblocked)
				return false;
			else
				return true;
		}
		
		function regFormForFreeVers( $isDelete=false ) {
			$fields_id 		= get_option("piereg_form_fields_id");
			$form_on_free	= get_option("piereg_form_free_id");
			$count 			= 0;
			
			if( ( !empty($fields_id) && !$form_on_free ) || $isDelete )
			{
				for( $a=1; $a<=$fields_id; $a++ )
				{
					$option 	= get_option("piereg_form_field_option_".$a);					
					if( !empty($option) && is_array($option) && isset($option['Id']) && !isset($option['IsDeleted']) )
					{	
						$count++;
						if( $count == 1 )
						{
							update_option('piereg_form_free_id', $option['Id']);
							$form_on_free .= $option['Id'];
						}
						break;
					}
				}
			}
			return $form_on_free;			
		}
		
		function checkAddonActivated($addon_name="")
		{
			if( $addon_name !== "")
			{
				$_option_name = get_option('piereg_api_manager_addon_'.$addon_name.'_activated');
				if($_option_name && $_option_name == "Activated")
				{
					return true;
				} 
			}
			
			return false;
		}
		//ver 3.5.4

		function anyAddonActivated(){

			// check if any addon is activated 
			if(
				!is_plugin_active('pie-register-authorize-net/pie-register-authorize-net.php')
				&& 
				!is_plugin_active('pie-register-bulkemail/pie-register-bulkemail.php')
				&& 
				!is_plugin_active('pie-register-field-visibility/pie-register-field-visibility.php')
				&& 
				!is_plugin_active('pie-register-profile-search/pie-register-profile-search.php')
				&& 
				!is_plugin_active('pie-register-social-site/pie-register-social-site.php')
				&& 
				!is_plugin_active('pie-register-social-site/pie-register-social-site.php')
				&& 
				!is_plugin_active('pie-register-stripe/pie-register-stripe.php')
				&& 
				!is_plugin_active('pie-register-geolocation/pie-register-geolocation.php')
				&& 
				!is_plugin_active('pie-register-mailchimp/pie-register-mailchimp.php')
				&& 
				!is_plugin_active("pie-register-twilio/pie-register-twilio.php") ){

					return true;
			}
		}
		
		// 3.6.15
		function get_aboutus_plugin_data( $plugin, $details, $all_plugins ) {

			if ( array_key_exists( $plugin, $all_plugins ) ) {
				if ( is_plugin_active( $plugin ) ) {
					// Status text/status.
					$plugin_data['status_class'] = 'status-active';
					$plugin_data['status_text']  = esc_html__( 'Active', 'pie-register' );
					// Button text/status.
					$plugin_data['action_class'] = $plugin_data['status_class'] . ' button button-secondary disabled';
					$plugin_data['action_text']  = esc_html__( 'Activated', 'pie-register' );
					$plugin_data['plugin_src']   = esc_attr( $plugin );
				} else {
					// Status text/status.
					$plugin_data['status_class'] = 'status-inactive';
					$plugin_data['status_text']  = esc_html__( 'Inactive', 'pie-register' );
					// Button text/status.
					$plugin_data['action_class'] = $plugin_data['status_class'] . ' button button-secondary';
					$plugin_data['action_text']  = esc_html__( 'Activate', 'pie-register' );
					$plugin_data['plugin_src']   = esc_attr( $plugin );
				}
			} else {
				// Doesn't exist, install.
				// Status text/status.
				$plugin_data['status_class'] = 'status-download';
				if ( isset( $details['act'] ) && 'go-to-url' === $details['act'] ) {
					$plugin_data['status_class'] = 'status-go-to-url';
				}
				$plugin_data['status_text'] = esc_html__( 'Not Installed', 'pie-register' );
				// Button text/status.
				$plugin_data['action_class'] = $plugin_data['status_class'] . ' button button-primary';
				$plugin_data['action_text']  = esc_html__( 'Install & Activate Plugin', 'pie-register' );
				$plugin_data['plugin_src']   = esc_url( $details['url'] );
			}
	
			$plugin_data['details'] = $details;
	
			return $plugin_data;
		}
		
		function getValue( $_is_not_form=false, $_other_values=array() ){
			
			if( is_bool($_is_not_form) && $_is_not_form === true )
			{				
				$value 				= isset($_other_values['_value']) 	? $_other_values['_value'] 	: "";
				$this->type			= isset($_other_values['_type']) 	? $_other_values['_type']	: "";
			}
			else {
				$value 		= $this->stripslashes_deep(get_user_meta($this->user_id, $this->slug,true));  #get_usermeta deprecated
			}
			
			if($this->type=="date")
			{
				if( ($this->field['date_type'] == "datepicker") && isset($value['date']) && !isset($value['date']['mm']) ) {
					$val = isset($value['date'][0]) ? $value['date'][0] : "";
					return $val;
				}
				else if(isset($value['date']) && is_array($value['date']))
				{
					if(!isset($value['date']['mm'])) {
						$val = isset($value['date'][0]) ? $value['date'][0] : "";
						return $val;
					}
					
					$val = $this->field['date_format'];
					if( is_bool($_is_not_form) && $_is_not_form === true )
					{
						$val	= "mm/dd/yy";
					}
					
					$mm_val = (!empty($value['date']['mm'])) ? $value['date']['mm'] : "mm";
					$val = str_replace("mm",$mm_val,$val);
					
					$dd_val = (!empty($value['date']['dd'])) ? $value['date']['dd'] : "dd";
					$val = str_replace("dd",$dd_val,$val);
					
					$yy_val = (!empty($value['date']['yy'])) ? $value['date']['yy'] : "yy";
					$val = str_replace("yy",$yy_val,$val);
					
					return 	$val;				
				} 
				
				return $value;			
			}
			else if($this->type=="time")
			{
				if(((isset($value['hh']) && $value['hh'] === '') && (isset ($value['mm']) && $value['mm'] === ''))){
					return false;
				}
				if(is_array($value)){
					if($value['hh'] != '')
						$value['hh'] = ($value['hh']);
					if($value['mm'] != '')
						$value['mm'] = ($value['mm']);
						
					
					if ( isset($value['time_format'])  )  $last = array_pop($value);	
					else $last = "";
					
					return implode(" : ",$value) . ' ' . $last;
				}
				return $value;
			}
			else if($this->type=="invitation")
			{
				$value = get_user_meta($this->user_id, "invite_code", true); #get_usermeta deprecated
				
				if(is_array($value))
					return implode(", ",$value);
				else 
					return $value;
			}
			else if($this->type=="custom_role") // dropdown-ur
			{
				$value = get_user_meta($this->user_id, "custom_role", true); #get_usermeta deprecated
				
				if(is_array($value))
					return implode(", ",$value);
				else 
					return $value;
			}
			else if($this->type=="list")
			{				
			
				if(!is_array($value))
				return $value;
				$list = "";
				$list = '<table class="piereg_custom_list '.$this->slug.'">';
				for($a = 0 ; $a < sizeof($value) ; $a++)
				{
					if(array_filter($value[$a])){
						$list .= '<tr>';
						$row  = "";
						for($b = 0 ; $b < sizeof($value[$a]) ; $b++)
						{
							$row 	.= $value[$a][$b];
							$list 	.= '<td>'.$value[$a][$b]."</td>";
						}
						if(!empty($row))
						$list .= '</tr>';
					}
				}
				$list .= '</table>';
				$value = $list ;	

			}
			else if($this->type=="multiselect" && $_is_not_form !== true )
			{
				$list = "";
				if($value) {
					$list = "<ol>";
					$combined_array = array_combine($this->field['value'],$this->field['display']);	
					
					for($a = 0 ; $a < sizeof($value) ; $a++ )
					{
						if(isset($value[$a]))
						{
							if( $this->field['list_type'] == 'None' )
							{
								$list .= "<li>".$combined_array[$value[$a]]."</li>";
								
							} else {
								
								$list .= "<li>".$value[$a]."</li>";
							
							}
						}
					}	
					$list .= "</ol>";				
				}
				$value = $list;					
			}
			elseif($this->type == "dropdown" && $_is_not_form !== true ){
				$combined_array 	= array_combine($this->field['value'],$this->field['display']);
				$corrected_value 	= array();
				
				if( gettype($value) == 'string' )	$value = array($value); 	
				
				for($a = 0 ; $a < sizeof($value) ; $a++ )
				{
					if(isset($value[$a]))
					{
						if($this->field['list_type']=='None')
						{
							$corrected_value[$a] = isset($combined_array[$value[$a]]) ? $combined_array[$value[$a]] : "";
							if( isset($_other_values['is_conditional']) )
							{
								$corrected_value[$a] = $value[$a];	
							}						
						} else {							
							$corrected_value[$a] = $value[$a];						
						}
					}					
					
				}				
				$value = implode(", ",$corrected_value);
				
			}else if( ($this->type == "checkbox" || $this->type == "radio") && $_is_not_form !== true )
			{
				$combined_array = array_combine($this->field['value'],$this->field['display']);
				$corrected_value = array();

				if( gettype($value) == 'string' )	$value = array($value);
				
				for($a = 0 ; $a < sizeof($value) ; $a++ )
				{
					if(isset($value[$a]) && !empty($value[$a]))
						$corrected_value[$a] = $combined_array[$value[$a]];
				}
				
				$value = implode(", ",$corrected_value);
			}
			else if($this->type=="address")
			{
				$results = "";
				if(is_array($value)) {
					$ret = (isset($value[0]) && is_array($value[0])) ? $value[0] : $value; 
					foreach($ret as $key => $val) {
						$results .= ucwords($key) . ' : ' . $val . '<br />';
					}
				}
				$value = $results;
			}
			else if($this->type=="textarea" || ($this->type=="default" && $this->slug == "description") ){
				$value = nl2br($value);
			}
			else if( is_array($value) )
			{				
				$value 	= implode(",", $value);								
			}
			return $value;	
		}	
			
		public function admin_footer_text()
		{
			$current_screen = get_current_screen();
			
			// Add the dashboard pages
			$pie_pages[] = 'toplevel_page_pie-register';
			$pie_pages[] = 'admin_page_pr_new_registration_form';
			$pie_pages[] = 'pie-register_page_pie-notifications';
			$pie_pages[] = 'pie-register_page_pie-invitation-codes';
			$pie_pages[] = 'pie-register_page_pie-social-sites';
			$pie_pages[] = 'pie-register_page_pie-gateway-settings';
			$pie_pages[] = 'pie-register_page_pie-settings';
			$pie_pages[] = 'pie-register_page_pie-import-export';
			$pie_pages[] = 'pie-register_page_pie-pie-black-listed-users';
			$pie_pages[] = 'pie-register_page_pie-pro-features';
			if( !get_option("pie_admin_footer_text_rated") )
			{
				if ( isset( $current_screen->id ) && in_array( $current_screen->id, $pie_pages ) ) {
					?>            	
						<p>If you like Pie Register please leave us a <a href="https://wordpress.org/support/plugin/pie-register/reviews/?filter=5" target="_blank" class="pie-admin-rating-link" data-rated="<?php esc_attr_e( 'Thanks :)', 'pie-register' ) ?>"> &#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. A huge thanks in advance!</p>
						
						<script type="text/javascript">
							jQuery( 'a.pie-admin-rating-link' ).click(function() {
								jQuery.ajax({
									url: ajaxurl,
									type: 'post',
									data: {
										action: 'pie_rated',
									},
									success: function(){
	
									}
								});									   
								jQuery(this).parent().text( jQuery( this ).data( 'rated' ) );
							});
						</script>
					<?php 
					
				}	
			}
		}

		/**
		 * Get the list of allowed tags, used in pair with wp_kses() function.
		 * This allows getting rid of all potentially harmful HTML tags and attributes.
		 *
		 * @since 3.7.1.5
		 *
		 * @return array Allowed Tags.
		 */
		function piereg_forms_get_allowed_tags() {

			static $allowed_tags;

			if ( ! empty( $allowed_tags ) ) {
				return $allowed_tags;
			}

			$atts = [ 'align', 'class', 'type', 'id', 'for', 'style', 'src', 'rel', 'href', 'target', 'value', 'width', 'height', 'data-*', 'placeholder', 'name', 'method', 'action', 'min', 'max', 'enctype', 'checked', 'selected', 'disabled', 'alt', 'onclick', 'autocomplete', 'onsubmit', 'multiple'];
			$tags = [ 'label', 'form', 'style', 'input','button', 'strong', 'small', 'table', 'span', 'abbr', 'code', 'pre', 'div', 'img', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ol', 'ul', 'li', 'em', 'hr', 'br', 'th', 'tr', 'td', 'p', 'a', 'b', 'i', 'select', 'option', 'textarea', 's' ];
			
			$allowed_atts = array_fill_keys( $atts, true );
			$allowed_tags = array_fill_keys( $tags, $allowed_atts );
			
			return $allowed_tags;
		}
	}
}	