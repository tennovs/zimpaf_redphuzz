<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!isset($pagenow)){
	global $pagenow;
}

if(!class_exists("PieRegister")){
	require_once(PIEREG_DIR_NAME.'/pie-register.php');
}

function pieOutputLoginForm($piereg_widget = false){
if(!isset($pagenow)){
	global $pagenow;
}

$pie_register_base = new PieReg_Base();
$action = isset($_GET['action']) ? sanitize_key($_GET['action']) : "";
$option			= get_option(OPTION_PIE_REGISTER);
$form_data = "";
$form_data .= '<div class="piereg_container">';

$classes_preset =  array( 'piereg_login_container', 'pieregWrapper' );
$classes 		= apply_filters( 'pie_register_frontend_login_container_class', $classes_preset);
$classes		= implode( ' ' , $classes );

$form_data .= '<div class="'.esc_attr($classes).'">';

$form_data .= '<div class="piereg_login_wrapper">';
//If Registration contanis errors
global $wp_session,$errors;
$newpasspageLock = 0;

			if(isset($_GET['payment']) && sanitize_key($_GET['payment']) == "success")
			{
				$fields = maybe_unserialize(get_option("pie_fields"));
				$login_success = apply_filters("piereg_success_message",__( $option['payment_success_msg'], "pie-register" ));
				unset($fields);
			}elseif(isset($_GET['payment']) && sanitize_key($_GET['payment']) == "cancel"){
				# noutusing
				/******************************************************/
				/*$user_id 		= intval(base64_decode($_GET['pay_id']));
				$user_data		= get_userdata($user_id);
				if(is_object($user_data)){
					$form 			= new Registration_form();
					$option 		= get_option( 'pie_register_2' );
					$subject 		= html_entity_decode($option['user_subject_email_payment_faild'],ENT_COMPAT,"UTF-8");
					$subject = $form->filterSubject($user_data,$subject);
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
						$headers .= "Return-Path: {$from_name}\r\n";
	
					}else{
						$headers .= "Reply-To: {$from_email}\r\n";
						$headers .= "Return-Path: {$from_email}\r\n";
					}
	
					wp_mail($user_data->user_email, $subject, $message , $headers);
					unset($user_data);
				}*/
				/******************************************************/

				$login_error = apply_filters("piereg_cancled_message",__("You have cancelled the payment.","pie-register"));
			}
			if(isset($errors->errors['login-error'][0]) > 0)
			{
				$login_error = apply_filters("piereg_login_error",__($errors->errors['login-error'][0],"pie-register"));
				$newpasspageLock = 1;
			}
			elseif( 
				   ( isset($_GET['pr_key']) && isset($_GET['pr_invalid_username']) ) && 
				   ( $_GET['pr_key'] != "" && $_GET['pr_invalid_username'] != "" ) && 
				   ( !isset($_REQUEST['action']) || (isset($_REQUEST['action']) && $_REQUEST['action'] != 'pie_login_sms') ) 
				   )
			{
				$pr_error_message = sanitize_text_field(base64_decode($_GET['pr_key']));
				if(!empty($pr_error_message))
					$login_error = apply_filters("piereg_login_after_registration_error",esc_html(__($pr_error_message,"pie-register")));
				else
					$login_error = apply_filters("piereg_login_after_registration_error",esc_html(__("Invalid username","pie-register")));
			}
			else if (! empty($action) )
        	{
            if ( 'loggedout' == $action )
                $login_warning = '<strong>'.ucwords(__("Warning","pie-register")).'</strong>: '.apply_filters("piereg_now_logout",__("You are now logged out.","pie-register"));

            elseif ( 'recovered' == $action )
                $login_success = '<strong>'.ucwords(__("success","pie-register")).'</strong>: '.apply_filters("piereg_check_yor_emailconfrm_link",__("Check your e-mail for the confirmation link.","pie-register"));

			elseif ( 'payment_cancel' == $action )
                $login_warning = '<strong>'.ucwords(__("Warning","pie-register")).'</strong>: '.apply_filters("piereg_canelled_your_registration",__("You have cancelled the registration.","pie-register"));

			elseif ( 'payment_success' == $action )
                $login_success = '<strong>'.ucwords(__("success","pie-register")).'</strong>: '.apply_filters("piereg_thank_you_for_registration",__("Thank you for registering. Login credentials will be sent soon.","pie-register"));		

			elseif ( 'activate' == $action )
			{
				$unverified = get_users(array('meta_key'=> 'hash','meta_value' => sanitize_key($_GET['activation_key'])));
				if(sizeof($unverified )==1)
				{
					$user_id	= $unverified[0]->ID;
					$user 		= new WP_User($user_id);

					$user_login = $unverified[0]->user_login;
					$user_email = $unverified[0]->user_email;
					if($user_login == $_GET['pie_id'])
					{
						do_action( "piereg_action_hook_before_user_activate", $user_id, $user_login, $user_email ); # newlyAddedHookFilter
						update_user_meta( $user_id, 'active', 1);
						
						/*************************************/
						/////////// THANK YOU E-MAIL //////////
						$form 			= new Registration_form();
						$subject 		= html_entity_decode($option['user_subject_email_email_thankyou'],ENT_COMPAT,"UTF-8");;
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
						if( (isset($option['user_enable_email_thankyou']) && $option['user_enable_email_thankyou'] == 1) && !wp_mail($user_email, $subject, $message , $headers)){
							$form->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".(PieRegister::get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}
						/*************************************/
						$login_success = '<strong>'.ucwords(__("success","pie-register")).'</strong>: '.apply_filters("piereg_your_account_is_now_active",__("Your account is now active","pie-register"));
						do_action( "piereg_action_hook_after_user_activate", $user_id, $user_login, $user_email ); # newlyAddedHookFilter
						// mailchimp related code within PR
						do_action('pireg_after_verification_users', $user);
					}
					else
					{
							 $login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("Invalid activation key","pie-register"));

					}	
				}else{
					$user_name = sanitize_text_field($_GET['pie_id']);
					$user = get_user_by('login',$user_name);
					if($user){
						$user_meta = get_user_meta( $user->ID, 'active');
						if(isset($user_meta[0]) && $user_meta[0] == 1){
							$login_warning = '<strong>'.ucwords(__("Warning","pie-register")).'</strong>: '.apply_filters("piereg_canelled_your_registration",__("This account is already active.","pie-register"));
							unset($user_meta);
							unset($user_name);
							unset($user);
						}
						else{
							$login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("Invalid activation key","pie-register"));
						}
					}
					else{
						$login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("Invalid activation key","pie-register"));
					}
				}
			}
			elseif ( 'resetpass' == $action || 'rp' == $action ){
				$user = check_password_reset_key(sanitize_text_field( $_GET['key'] ), sanitize_user($_GET['login']));
				if ( is_wp_error($user) ) {
					if ( $user->get_error_code() === 'expired_key' )
						$login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_you_key_has_been_expired",__("This link has expired, please reset the password again.","pie-register").' <a href="'.pie_lostpassword_url().'" title="'.__("Reset your password","pie-register").'">'.__("Lost your password?","pie-register").'</a>');
					else
						$login_error = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_this_reset_key_invalid_or_no_longer_exists",__("Reset key is invalid or has expired.","pie-register").' <a href="'.pie_lostpassword_url().'" title="'.__("Reset your password","pie-register").'">'.__("Lost your password?","pie-register").'</a>');
						$newpasspageLock = 1;
				}else{
					$login_warning = '<strong>'.ucwords(__("Warning","pie-register")).'</strong>: '.__('Enter the new password below.',"pie-register");
				}
				
				
				if( isset($_POST['pass1'], $_POST['piereg_get_password_nonce']) && wp_verify_nonce( $_POST['piereg_get_password_nonce'], 'piereg_wp_get_password_nonce' ) && !is_wp_error($user)){
					$errors = new WP_Error();
					if(isset($_POST['pass1']) && trim($_POST['pass1']) == ""){
						$login_error =  '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_invalid_password",__( 'Invalid Password',"pie-register" ));
						$errors->add( 'password_reset_mismatch',$login_error );
					}elseif ( isset($_POST['pass1']) and strlen($_POST['pass1']) < 7  ){
						$login_error =  '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_minimum_8_characters_required_in_password",__( 'The password must be at least 8 characters long',"pie-register" ));
						$errors->add( 'password_reset_mismatch',$login_error );
					}elseif ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ){
						$login_error =  '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters("piereg_the_passwords_do_not_match",__( 'The passwords do not match',"pie-register"));
						$errors->add( 'password_reset_mismatch',$login_error );
					}
					do_action( 'validate_password_reset', $errors, $user );
					if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
						reset_password($user, trim($_POST['pass1']));
						$newpasspageLock = 1;
						$login_warning = '';
						$login_error = '';
						$login_success = '<strong>'.ucwords(__("success","pie-register")).'</strong>: '.apply_filters("piereg_your_password_has_been_reset",__( 'The password has been reset.' , "pie-register"));
					}
				}
			}
        }
		if(isset($wp_session['message']) && trim($wp_session['message']) != "" )
		{
			$form_data .= '<p class="piereg_login_error"> ' . apply_filters('piereg_messages',__($wp_session['message'],"pie-register")) . "</p>";
			$wp_session['message'] = "";
		}
		if ( !empty($login_error) )
			$form_data .= '<p class="piereg_login_error"> ' . apply_filters('piereg_messages', $login_error) . "</p>";

		if ( !empty($login_success) )
			$form_data .= '<p class="piereg_message">' . apply_filters('piereg_messages',$login_success) . "</p>";

		if ( !empty($login_warning) )
			$form_data .= '<p class="piereg_warning">' . apply_filters('piereg_messages',$login_warning) . "</p>";

		if(isset($pie_register_base->pie_post_array['success']) && $pie_register_base->pie_post_array['success'] != "")
			$form_data .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($pie_register_base->pie_post_array['success'],"pie-register")).'</p>';

		if(isset($pie_register_base->pie_post_array['error']) && $pie_register_base->pie_post_array['error'] != "")
			$form_data .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($pie_register_base->pie_post_array['error'],"pie-register")).'</p>';


if ( isset($_GET['action']) && ('rp' == $action || 'resetpass' == $action) && ($newpasspageLock == 0) ){
	
	if(file_exists( (get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php"))){
		require_once(get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php");
	}
	elseif(file_exists(PIEREG_DIR_NAME."/pie_register_template/reset_password/reset_password_form_template.php")){
		require_once(PIEREG_DIR_NAME."/pie_register_template/reset_password/reset_password_form_template.php");
	}
	$r_pass_form = new Reset_pass_form_template($option);
	$this_user = sanitize_user($_GET['login']);
	$the_key  = urlencode( sanitize_text_field( $_GET['key']) );
	$the_user = urlencode( $this_user );
	$form_data .= '
	  <form name="resetpassform" class="piereg_resetpassform" action="'.esc_url(pie_modify_custom_url(pie_login_url(),'action=resetpass&key=' . $the_key . '&login=' . $the_user)).'" method="post" autocomplete="off">
	  	
		<input type="hidden" id="user_login" value="'.$this_user.'" autocomplete="off">';
		
			if( function_exists( 'wp_nonce_field' )) 
				$form_data .= wp_nonce_field( 'piereg_wp_get_password_nonce','piereg_get_password_nonce', true, false);
			$form_data .= $r_pass_form->add_new_confirm_pass();
			$form_data .= $r_pass_form->add_submit();
			$form_data .= $r_pass_form->add_login_register($pagenow);
		$form_data .= '</form>';
}
elseif ( isset($_GET['action'],$_GET['reference_key'],$_GET['security_token']) && sanitize_key($_REQUEST['action']) == 'pie_login_sms' ){
	$form_data .= apply_filters("piereg_login_sms_form",$piereg_widget);
}
else{
	
		if(file_exists( (get_stylesheet_directory()."/pie-register/pie_register_template/login/login_form_template.php"))){
			require_once(get_stylesheet_directory()."/pie-register/pie_register_template/login/login_form_template.php");
		}
		elseif(file_exists(PIEREG_DIR_NAME."/pie_register_template/login/login_form_template.php")){
			require_once(PIEREG_DIR_NAME."/pie_register_template/login/login_form_template.php");
		}

		$login_form = new Login_form_template($option);
		
		if( $piereg_widget )
		{
			$pr_loginform_id	= 'piereg_login_form_widget';
		}
		else{
			$pr_loginform_id	= 'piereg_login_form';
		}

		$form_data = apply_filters( 'pie_register_frontend_login_output_before', __($form_data,"pie-register") );

		$form_data .= '
		<form method="post" id="'.$pr_loginform_id.'" class="piereg_loginform" name="loginform" action="'.esc_url(htmlentities($_SERVER['REQUEST_URI'])).'">';

			$form_data .= '<ul id="pie_register">';

				$form_data .= '<li class="fields">';
					$form_data .= $login_form->add_username();
				$form_data .= '</li>';
				
				$form_data .= '<li class="fields">';
					$form_data .= $login_form->add_password();
				$form_data .= '</li>';

			
			$form_data .= '<li class="fields"><div class="fieldset">';
			global $piereg_math_captcha_login,$piereg_math_captcha_login_widget,$wpdb;
			$table_name = $wpdb->prefix . "pieregister_lockdowns";
			$user_ip = $_SERVER['REMOTE_ADDR'];
			
			$get_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$table_name."` WHERE `user_ip` = %s;",$user_ip));
			
			if(isset($wpdb->last_error) && !empty($wpdb->last_error))
			{
				PieRegister::pr_error_log($wpdb->last_error.(PieRegister::get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
			}
			
			$is_security_captcha = false;
			$security_captcha_login = 0;
			if(isset($get_results[0]->is_security_captcha) && $get_results[0]->is_security_captcha == 2){
				$is_security_captcha = true;
				$security_captcha_login = $option['security_captcha_login'];
			}
			$capthca_in_login = $option['capthca_in_login'];
			if($is_security_captcha){
				$capthca_in_login = $security_captcha_login;
			}
			
			if($capthca_in_login != 0 && !empty($capthca_in_login) && $option['captcha_in_login_value'] == 1){
				$attempts = true;
				
				if( $attempts ){
					if($piereg_math_captcha_login == false && $piereg_widget == false){
						if( !empty($option['capthca_in_login_label']) ){
							if($option['capthca_in_login'] == 3){
								if($option['piereg_recaptcha_type'] != 'v3')
									$form_data .= $login_form->add_capthca_label();
							}else{
								$form_data .= $login_form->add_capthca_label();
							}
						}
						
						$form_data  .= pie_login_form_captcha($capthca_in_login,$piereg_widget);
						$piereg_math_captcha_login = true;
					}elseif($piereg_math_captcha_login_widget == false && $piereg_widget == true){
						if( !empty($option['capthca_in_login_label']) ){
							if($option['capthca_in_login'] == 3){
								if($option['piereg_recaptcha_type'] != 'v3')
									$form_data .= $login_form->add_capthca_label();
							}else{
								$form_data .= $login_form->add_capthca_label();
							}
						}
						
						$form_data  .= pie_login_form_captcha($capthca_in_login,$piereg_widget);
						$piereg_math_captcha_login_widget = true;
					}
				}
			}
			
			$form_data .= '</div></li>';
			$form_data .= '</ul>';
			
			$form_data .= $login_form->add_rememberme();
			
			if( function_exists( 'wp_nonce_field' ))
				$form_data .= wp_nonce_field( 'piereg_wp_login_form_nonce','piereg_login_form_nonce', true, false); 
			
			$form_data .= $login_form->add_submit();
			$form_data .= $login_form->add_register_lostpassword($pagenow);
			
		$form_data .= '
		</form>';
		$form_data = apply_filters( 'pie_register_frontend_login_output_after', __($form_data,"pie-register") );
	
}

$form_data .='</div>
</div></div>';

$PieReg = new PieRegister();
$PieReg->piereg_forms_per_page[ 'login_form' ] = $form_data;

return $form_data;
}

if(!function_exists("pie_login_form_captcha"))
{
	function pie_login_form_captcha($value = 0,$piereg_widget = false){
		if(file_exists( (get_stylesheet_directory()."/pie-register/pie_register_template/login/login_form_template.php"))){
			require_once(get_stylesheet_directory()."/pie-register/pie_register_template/login/login_form_template.php");
		}
		elseif(file_exists(PIEREG_DIR_NAME."/pie_register_template/login/login_form_template.php")){
			require_once(PIEREG_DIR_NAME."/pie_register_template/login/login_form_template.php");
		}
		
		if(!isset($option)){
			$option = get_option(OPTION_PIE_REGISTER);
		}
		$login_form = new Login_form_template($option);
		$output = "";
		if($value == 2){ // Math Captcha
			$cap_id = "";
			if( $piereg_widget ){
				$cap_id = "is_login_widget";
				$cookie = 'Login_form_widget';
			}else{
				$cap_id = "not_login_widget";
				$cookie = 'Login_form';
			}
			
			$data = "";
			$data .='<div class="prMathCaptcha" data-cookiename="'.$cookie.'" id="'.$cap_id.'" style="display:inline-block;">';
			
			$field_id = "";
			$math_captcha_field = $login_form->add_mathcaptcha_input($piereg_widget);
			$data .=  $math_captcha_field['data'];
			$field_id = $math_captcha_field['field_id'];
			$data .= '</div>';
			$output = $data;
			 
		}elseif($value == 1 || $value == 3){//Re-Captcha
			$data = "";
			$settings  	    =  get_option(OPTION_PIE_REGISTER);
			$publickey	    = $settings['captcha_publc'] ;
			$recaptcha_type	= $settings['piereg_recaptcha_type'] ;
			$publickeyv3  	= $settings['captcha_publc_v3'];
			
			
			$cap_id = "";
			$style_inline = "";
			
			if( $piereg_widget ){
				$cap_id = "is_widget";
			
			}else{
				$cap_id = "not_widget";
				$style_inline = 'style="display:inline-block;"';
			}

			if( ( $publickey && $recaptcha_type == 'v2') || ( $publickeyv3 && $recaptcha_type == 'v3' ) ){
				if($recaptcha_type == 'v2')
					$data .= '<div '.$style_inline.' class="piereg_recaptcha_widget_div" id="'.$cap_id.'">';
				else
					$data .= '<div '.$style_inline.' class="piereg_recaptcha_widget_div">';

				if($recaptcha_type == 'v3')
					$data .= '<input type="hidden" name="g-recaptcha-response" id="'.$cap_id.'" value="">';
				$data .= '</div>';
			}
			return $data;
		
		}
		
		return $output;
	}
}

function pie_update_user_meta_hash() {
	$activation_key = isset($_GET['activation_key']) ? sanitize_key($_GET['activation_key']) : "";
    $unverified = get_users(array('meta_key'=> 'hash','meta_value' => $activation_key));
    if(sizeof($unverified )==1)
    {
        $user_id	= $unverified[0]->ID;
        $user_login = $unverified[0]->user_login;
        if( isset($_GET['pie_id']) && $user_login == $_GET['pie_id'])
        {
            $hash = "";
            update_user_meta( $user_id, 'hash', $hash );
        }
    }
}
add_action('wp_footer','pie_update_user_meta_hash');