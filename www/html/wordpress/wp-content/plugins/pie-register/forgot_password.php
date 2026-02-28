<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists("PieRegister")){
	require_once(PIEREG_DIR_NAME.'/pie-register.php');
}

function pieResetFormOutput($piereg_widget = false){
	$pie_register_base = new PieReg_Base();
	/*
		*	Sanitizing post data
	*/
	
	$pie_register_base->pie_post_array	= $pie_register_base->piereg_sanitize_post_data_escape( ( (isset($_POST['is_forgot_form']) && !empty($_POST['is_forgot_form'])) ? $_POST : array() ) );
	$option = get_option(OPTION_PIE_REGISTER);
	$forgot_pass_form = '';
	$forgot_pass_form .= '<div class="piereg_container">';
	$classes_preset =  array( 'piereg_entry-content', 'pieregForgotPassword', 'pieregWrapper' );
	$classes 		= apply_filters( 'pie_register_forgot_pass_container_class', $classes_preset);
	$classes		= implode( ' ' , $classes );

	$forgot_pass_form .= '<div class="'.$classes.'">';
	$forgot_pass_form .= '<div id="piereg_forgotpassword">';
	
	$warning 	 = __("Please enter your username or email address. You will receive a link to create a new password via email.",'pie-register');
	$success	 = "";
	$error		 = array();
	$error_class = "piereg_login_error";
	
	if(isset($_POST['piereg_reset_password_nonce']) && wp_verify_nonce( $_POST['piereg_reset_password_nonce'], 'piereg_wp_reset_password_nonce' ))
	{
		if (isset($pie_register_base->pie_post_array['reset_pass']) && trim($pie_register_base->pie_post_array['user_login']) != "")
		{
			
			if(isset($pie_register_base->pie_post_array['user_login']) and trim($pie_register_base->pie_post_array['user_login']) == ""){
				$error[] = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.__('Invalid Username or Email, try again!','pie-register');
			}
			$error_found = 0;
			
			if(isset($option['captcha_in_forgot_value']) && $option['captcha_in_forgot_value'] == 1){
				if($option['capthca_in_forgot_pass'] == 2){
					
					$invalidcaptchaerror = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '. apply_filters('piereg_forgot_invalid_captcha_error',__('Invalid Captcha','pie-register')); # newlyAddedHookFilter
					if(isset($pie_register_base->pie_post_array['piereg_math_captcha_forgot_pass']))
					{
						$currentTabId =  intval($_COOKIE['currentTabId']);		
						$piereg_cookie_array =  ( (isset( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_forgot_password'] ) && 0 < strpos( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_forgot_password'], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_forgot_password']) : "");
						$piereg_cookie_array = explode("|",$piereg_cookie_array);
						$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
						$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
						$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
						if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $pie_register_base->pie_post_array['piereg_math_captcha_forgot_pass'])){
						}
						else{
							$error[] = $invalidcaptchaerror;
							$error_found++;
						}
					}
					elseif(isset($pie_register_base->pie_post_array['piereg_math_captcha_forgot_pass_widget']))
					{
						$currentTabId =  intval($_COOKIE['currentTabId']);		
						$piereg_cookie_array = ( (isset( $_COOKIE[ 'tab_'.$currentTabId.'_piereg_math_captcha_forgot_password_widget' ] ) && 0 < strpos( $_COOKIE[ 'tab_'.$currentTabId.'_piereg_math_captcha_forgot_password_widget' ], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_forgot_password_widget']) : ""); 
						$piereg_cookie_array = explode("|",$piereg_cookie_array);
						$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
						$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
						$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
						if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $pie_register_base->pie_post_array['piereg_math_captcha_forgot_pass_widget'])){
						}
						else{
							$error[] = $invalidcaptchaerror;
							$error_found++;
						}
					}
					else{
						$error[] = $invalidcaptchaerror;
						$error_found++;
					}
				}//Validate New Recaptcha
				elseif($option['capthca_in_forgot_pass'] == 3){
					
					$settings  	=  get_option(OPTION_PIE_REGISTER);
					if($settings['piereg_recaptcha_type'] == "v2"){
						$privatekey		= $settings['captcha_private'];
					}elseif($settings['piereg_recaptcha_type'] == "v3"){
						$privatekey		= $settings['captcha_private_v3'];
					}
					$captcha	= (isset($_POST['g-recaptcha-response']) && ! empty( $_POST['g-recaptcha-response'] )) ? sanitize_text_field($_POST['g-recaptcha-response']) : "";
					$response = $pie_register_base->read_file_from_url("https://www.google.com/recaptcha/api/siteverify?secret=".trim($privatekey)."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
					$resp = json_decode($response,true);
					if($resp['success'] == false){
						$error[] = '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.apply_filters('piereg_forgot_invalid_captcha_error',__('Invalid Security Code','pie-register')); # newlyAddedHookFilter
						$error_found++;
					}
				}
			}
			
			if( $error_found == 0 && (isset($error) && count($error) == 0) ){
				global $wpdb,$wp_hasher;
				$error 		= array();
				$username = trim($pie_register_base->pie_post_array['user_login']);
				$user_exists = false;
				// First check by username
				if ( username_exists( $username ) ){
					$user_exists = true;
					$user = get_user_by('login', $username);
				}
				// Then, by e-mail address
				elseif( email_exists($username) ){
						$user_exists = true;
						//$user = get_user_by_email($username);
						$user = get_user_by( 'email', $username );
				}
				else{
					$error_class = "";
					$error[] = apply_filters('piereg_forgot_invalid_user_error',__('If a matching account is found, a link is sent on that email address to reset the password.','pie-register'));
				}
				if ($user_exists){
					$user_login = $user->user_login;
					$user_email = $user->user_email;
					$allow = apply_filters( 'allow_password_reset', true, $user->ID );
					
					piereg_delete_authentication();
					
					if($allow){
						
						// Generate something random for a key...
						$key = wp_generate_password( 20, false );
						do_action( 'retrieve_password_key', $user_login, $key );
						
						if ( empty( $wp_hasher ) ) {
							require_once ABSPATH . WPINC . '/class-phpass.php';
							$wp_hasher = new PasswordHash( 8, true );
						}
											
						$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
						
						// Now insert the new md5 key into the db
						$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));				
						
						$message_temp = "";
						if($option['user_formate_email_forgot_password_notification'] == "0"){
							$message_temp	= nl2br(strip_tags($option['user_message_email_forgot_password_notification']));
						}else{
							$message_temp	= $option['user_message_email_forgot_password_notification'];
						}
						$message		= $pie_register_base->filterEmail($message_temp,$user->user_login, '',$key );
						$from_name		= $option['user_from_name_forgot_password_notification'];
						$from_email		= $option['user_from_email_forgot_password_notification'];					
						$reply_email 	= $option['user_to_email_forgot_password_notification'];
						$subject 		= html_entity_decode($option['user_subject_email_forgot_password_notification'],ENT_COMPAT,"UTF-8");
						$subject 		= $pie_register_base->filterSubject($user->user_login,$subject);
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
						//send email meassage
						if ( (isset($option['user_enable_forgot_password_notification']) && $option['user_enable_forgot_password_notification'] == 1) && FALSE == wp_mail($user_email, $subject, $message,$headers)){
							$error[] =  '<strong>'.ucwords(__("error","pie-register")).'</strong>: '.__('The e-mail could not be sent. Please contact site administrator.','pie-register') ;
							$pie_register_base->pr_error_log("'The e-mail could not be sent. Possible reason: mail() function may have disabled by your host.'".($pie_register_base->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}
						unset($key);
						unset($hashed);
						unset($_POST['user_login']);
						unset($pie_register_base->pie_post_array['user_login']);
					}else{
						$error[] = apply_filters('piereg_password_reset_not_allowed_text',__("Password reset is not allowed for this user","pie-register"));
					}
					
					if (count($error) == 0 )
					{
						$obj_pie_class = new PieRegister();
						$obj_pie_class->set_pr_stats("forgot","used");
						$error_class = "";
						$success =  apply_filters("piereg_message_will_be_sent_to_your_email",__('If a matching account is found, a link is sent on that email address to reset the password.','pie-register'));
					}	
				}
			}
		}
	}
	
	$forgot_pass_form .='<div id="piereg_login">';
	if ( (isset($error) && is_array($error) && count($error) == 0) && !empty($success) ) {
		$forgot_pass_form .= '<div class="alert"><p class="piereg_message">';
		$forgot_pass_form .= $success;
		$forgot_pass_form .= '</p></div>';
	} else if (isset($error) && is_array($error) && count($error) > 0 ) {
		$forgot_pass_form .= '<div class="alert"><p class="piereg_message '.$error_class.'">';
		$forgot_pass_form .= $error[0];
		$forgot_pass_form .= '</p></div>';
	} elseif($warning) {
		$forgot_pass_form .= '<div class="alert alert-warning"><p class="piereg_warning fp_desc">'.$warning.'</p></div>';
	}
	if(file_exists( (get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php"))){
		require_once(get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php");
	}
	else{
		require_once(PIEREG_DIR_NAME."/pie_register_template/reset_password/reset_password_form_template.php");
	}

	$reset_form = new Reset_pass_form_template($option);

	$forgot_pass_form = apply_filters( 'pie_register_forgot_pass_output_before', __($forgot_pass_form,"pie-register") );
	$forgot_pass_form .= '<ul id="pie_register">';
	$forgot_pass_form .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'" id="piereg_lostpasswordform">';
	$forgot_pass_form .= '<li class="fields">';	
		$forgot_pass_form .= $reset_form->add_email_or_username();
	$forgot_pass_form .= '</li>';
		if( function_exists( 'wp_nonce_field' ))
			$forgot_pass_form .= wp_nonce_field( 'piereg_wp_reset_password_nonce','piereg_reset_password_nonce', true, false);

		$forgot_pass_form .= '<input type="hidden" value="" name="redirect_to">';
		$forgot_pass_form .= '<input type="hidden" value="1" name="is_forgot_form">';
		$forgot_pass_form .= '<li class="fields">';
		$forgot_pass_form .= '<div class="fieldset">';	
		global $piereg_math_captcha_forgot_pass,$piereg_math_captcha_forgot_pass_widget;
		
		if($option['capthca_in_forgot_pass'] != 0 && !empty($option['capthca_in_forgot_pass']) && isset($option['captcha_in_forgot_value']) && $option['captcha_in_forgot_value'] == 1){
			if($piereg_math_captcha_forgot_pass == false && $piereg_widget == false)
			{
				if(!empty($option['capthca_in_forgot_pass_label'])){
					if($option['capthca_in_forgot_pass'] == 3){
						if($option['piereg_recaptcha_type'] != 'v3')
							$forgot_pass_form .= $reset_form->add_capthca_label();

					}else{
						$forgot_pass_form .= $reset_form->add_capthca_label();

					}
				}
				$forgot_pass_form .= forgot_pass_captcha($option['capthca_in_forgot_pass'],$piereg_widget);
				$piereg_math_captcha_forgot_pass = true;
			}elseif($piereg_math_captcha_forgot_pass_widget == false && $piereg_widget == true && isset($option['captcha_in_forgot_value']) && $option['captcha_in_forgot_value'] == 1){
				if(!empty($option['capthca_in_forgot_pass_label']) ){
					if($option['capthca_in_forgot_pass'] == 3){
						if($option['piereg_recaptcha_type'] != 'v3')
							$forgot_pass_form .= $reset_form->add_capthca_label();

					}else{
						$forgot_pass_form .= $reset_form->add_capthca_label();

					}
				}
				$forgot_pass_form .= forgot_pass_captcha($option['capthca_in_forgot_pass'],$piereg_widget);
				$piereg_math_captcha_forgot_pass_widget = true;
			}
		}
		$forgot_pass_form .= '</div>';
		$forgot_pass_form .= '</li>';	
		do_action('pieresetpass');

		$forgot_pass_form .= '<li class="fields">';	
		$forgot_pass_form .= $reset_form->add_reset_submit();
			
			if(isset($pagenow)){$pagenow;}else{$pagenow="";}
			$forgot_pass_form .= $reset_form->add_register_or_login($pagenow);
		$forgot_pass_form .= '</li>';	
		$forgot_pass_form .= '</ul>';
		$forgot_pass_form .= '
		<input type="hidden" name="reset_pass" value="1" />
		<input type="hidden" name="user-cookie" value="1" />
	  </form>';
	  $forgot_pass_form = apply_filters( 'pie_register_forgot_pass_output_after', __($forgot_pass_form,"pie-register") );
	  $forgot_pass_form .= '
	</div>
	</div>
	</div>
	</div>';

	$PieReg = new PieRegister();
	$PieReg->piereg_forms_per_page[ 'forgot_pass' ] = $forgot_pass_form;
	return $forgot_pass_form;
}

if(!function_exists("forgot_pass_captcha"))
{
	function forgot_pass_captcha($value = 0,$piereg_widget = false){
		$option = get_option(OPTION_PIE_REGISTER);
		if(file_exists( (get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php"))){
			require_once(get_stylesheet_directory()."/pie-register/pie_register_template/reset_password/reset_password_form_template.php");
		}
		else{
			require_once(PIEREG_DIR_NAME."/pie_register_template/reset_password/reset_password_form_template.php");
		}
		$reset_form = new Reset_pass_form_template($option);
		$output = "";
		//Forcefully override the new captcha
		if($value == 1) $value = 3;
		if($value == 2){ // Math Captcha
			$cap_id = "";
			 if( $piereg_widget ){
				$cap_id = "is_forgot_widget";
				$cookie = "forgot_password_widget";
			 }else{
				$cap_id = "not_forgot_widget";
				$cookie = "forgot_password";
			 }
			$data = "";
			
			$data .='<div class="prMathCaptcha" data-cookiename="'.$cookie.'" id="'.$cap_id.'" style="display:inline-block;">';
			$field_id = "";
			
			$mathcapthca_input = $reset_form->add_mathcapthca_input($piereg_widget);
			$data 	.= $mathcapthca_input['data'];
			$field_id = $mathcapthca_input['field_id'];
			
			
			$data .= '</div>';
			$output = $data;
			 
		}elseif($value == 3 || $value == 1){//New Re-Captcha
			$data = "";
			$settings       =  get_option(OPTION_PIE_REGISTER);
			$recaptcha_type	= $settings['piereg_recaptcha_type'] ;
			$publickey	    = $settings['captcha_publc'] ;
			$publickeyv3    = $settings['captcha_publc_v3'] ;

			
			$cap_id = "";
			if( $piereg_widget ){
				$cap_id = "is_forgot_widget";
			}else{
				$cap_id = "not_forgot_widget";
			}
			if( ( $publickey && $recaptcha_type == 'v2') || ( $publickeyv3 && $recaptcha_type == 'v3' ) ){
				if($recaptcha_type == 'v2')
					$data .= '<div class="piereg_recaptcha_widget_div" id="'.$cap_id.'" value="">';
				else
					$data .= '<div class="piereg_recaptcha_widget_div">';

				if($recaptcha_type == 'v3')
					$data .= '<input type="hidden" name="g-recaptcha-response" id="'.$cap_id.'" value="">';
				$data .= '</div>';
			}
			return $data;
		
		}
		return $output;
	}
}
function piereg_delete_authentication(){
	global $wpdb;
	$table_name = $wpdb->prefix . "pieregister_lockdowns";
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$user_ip = esc_sql($user_ip);
	$wpdb->query($wpdb->prepare("DELETE FROM `".$table_name."` WHERE `user_ip` = %s AND `attempt_from` = 'is_forgot'",$user_ip));
	if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
		$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
	}
	return true;
}
?>