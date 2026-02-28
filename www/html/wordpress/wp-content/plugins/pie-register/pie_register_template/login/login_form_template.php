<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if(!class_exists("Login_form_template"))
{
	class Login_form_template
	{
		var $pr_option;
		function __construct($option)
		{
			$this->pr_option = $option;
		}
		
		function add_username(){
			$form_data = '<div class="fieldset">';
			if(isset($this->pr_option['login_username_label']) && !empty($this->pr_option['login_username_label'])){
					$form_data .= '<label for="user_login">'.((isset($this->pr_option['login_username_label']) && !empty($this->pr_option['login_username_label']))? esc_html(__($this->pr_option['login_username_label'],"pie-register")) : __("Username","pie-register")) .'</label>';
			}
			$user_name_val = ((isset($_POST['log']) && !empty($_POST['log']))? sanitize_user($_POST['log']):"");
			$form_data .= '<input placeholder="'.((isset($this->pr_option['login_username_placeholder']) && !empty($this->pr_option['login_username_placeholder']))? esc_attr(__($this->pr_option['login_username_placeholder'],"pie-register")) : "").'" type="text" value="'.esc_attr($user_name_val).'" class="input input_fields piereg_validate[required]" id="user_login" name="log">';
			$form_data .= '</div>';
			return $form_data;
		}
		function add_password(){
			
			$form_data = '<div class="fieldset">';
			$form_data .= '<div class="password_field">';
			if(isset($this->pr_option['login_password_label']) && !empty($this->pr_option['login_password_label'])){
				$form_data .= '<label for="user_pass">'.((isset($this->pr_option['login_password_label']) && !empty($this->pr_option['login_password_label']))? esc_html(__($this->pr_option['login_password_label'],"pie-register")) : __("Password","pie-register")).'</label>';
			}
			$form_data .= '<input placeholder="'.((isset($this->pr_option['login_password_placeholder']) && !empty($this->pr_option['login_password_placeholder']))? esc_attr(__($this->pr_option['login_password_placeholder'],"pie-register")) : "").'" type="password" value="" class="input input_fields piereg_validate[required]" id="user_pass" name="pwd">';
			$form_data .= '<span class="show-hide-password-innerbtn pass-eye-login eye"></span>';
			$form_data .= '</div>';
			$form_data .= '</div>';
			return $form_data;
		}
		function add_rememberme(){
			$form_data = '<p class="forgetmenot">';
                $form_data .= '<input type="checkbox" value="forever" id="rememberme" name="rememberme">';
				$form_data .= '<label for="rememberme">';
					$form_data .= __("Remember Me","pie-register");
				$form_data .= '</label>';
			$form_data .= '</p>';
			return $form_data;
		}
		function add_submit(){
			$form_data = '<p class="submit">';
				$form_data .= '<input type="submit" value="'.__("Log In","pie-register").'" class="button button-primary button-large" id="wp-login-submit" name="wp-submit">';
				$form_data .= '<input type="hidden" value="'.admin_url().'" name="redirect_to">';
				$form_data .= '<input type="hidden" value="1" name="testcookie">';
			$form_data .= '</p>';
			return $form_data;
		}
		function add_register_lostpassword($pagenow){
			$form_data = '<p id="nav">';
				/* Anyone can register */
				$classPieRegister = new PieRegister();
				if($classPieRegister->is_anyone_can_register()){
					$form_data .= '<a href="'.esc_url(pie_registration_url()).'">'.__("Register","pie-register").'</a>';
					$form_data .= '<a style="cursor:default;text-decoration:none;" href="javascript:;"> | </a>';
				}
				$form_data .= '<a title="'.__("Reset your password","pie-register").'" href="'.esc_url(pie_lostpassword_url()).'">'.__("Lost your password?","pie-register").'</a>';
			$form_data .= '</p>';
			if(isset($pagenow) && $pagenow == 'wp-login.php' ){
				$form_data .= '<p id="backtoblog">';
					$form_data .= '<a title="'.__("Are you lost?","pie-register").'" href="'.esc_url(get_bloginfo("url")).'">&larr;'.__(" Back to ".get_bloginfo("name"),"pie-register").'</a>';
				$form_data .= '</p>';
			}
			
			$form_data = apply_filters('pie_forgotpassword_form_links',$form_data);
			
			return $form_data;
		}
		function add_mathcaptcha_input($piereg_widget = false){
			$data = "";
			$field_id = "";
			if($piereg_widget == true){
				$data .= '<div id="pieregister_math_captha_login_form_widget" class="piereg_math_captcha"></div>';
				$data .= '<input id="" type="text" class="piereg_math_captcha_input pr_math_captcha_input_field" name="piereg_math_captcha_login_widget" autocomplete="off" />';
				$field_id = "#pieregister_math_captha_login_form_widget";
			}
			else{
				$data .= '<div id="pieregister_math_captha_login_form" class="piereg_math_captcha"></div>';
				$data .= '<input id="" type="text" class="piereg_math_captcha_input pr_math_captcha_input_field" name="piereg_math_captcha_login" autocomplete="off" />';
				$field_id = "#pieregister_math_captha_login_form";
			}
			return array("data" => $data,"field_id" => $field_id);
		}
		function add_capthca_label(){
			// since 3.7.0.0
			// $form_data  = '<p>';
			$form_data = '<label style="margin-top:0px;">'.esc_html($this->pr_option['capthca_in_login_label']).'</label>';
			// $form_data .= '</p>';
			return $form_data;
		}
	}
}