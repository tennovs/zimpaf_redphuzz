<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if(!class_exists("Reset_pass_form_template"))
{
	class Reset_pass_form_template
	{
		var $pr_option;
		function __construct($option)
		{
			$this->pr_option = $option;
		}
		function add_new_confirm_pass(){
			$data  = '<p class="field password_field">';
				$data .= '<label for="pass1">'.__("New password","pie-register").'</label>';
				$data .= '<input type="password" name="pass1" id="pass1" class="input input_fields validate[required]" size="20" value="" autocomplete="off">';
				$data .= '<span class="show-hide-password-innerbtn pass-eye-forgot-pass eye"></span>';
			$data .= '</p>';
			$data .= '<p class="field">';
		  	$data .= '<label for="pass2">'.__("Confirm new password","pie-register").'</label>';
		  	$data .= '<input type="password" name="pass2" id="pass2" class="input input_fields validate[required,equals[pass1]]" size="20" value="" autocomplete="off">';
			$data .= '</p>';
			return $data;
		}
		function add_submit(){
			$data  = '<div class="pie_submit">';
			$data .= '<input type="submit" name="wp-submit" id="wp-reset-submit" class="button button-primary button-large" value="'.__("Reset Password","pie-register").'">';
			$data .= '</div>';
			return $data;
		}
		function add_login_register($pagenow){
			$data  = '<div class="field">';
				$data .= '<p class="nav">';
					$data .= '<a href="'.esc_url(pie_login_url()).'">'.__("Log in","pie-register").'</a>';
					$data .= ' | ';
					$data .= '<a href="'.esc_url(pie_registration_url()).'">'.__("Register","pie-register").'</a>';
				$data .= '</p>';
			$data .= '</div>';
			if(isset($pagenow) && $pagenow == 'wp-login.php' ){
				$data .= '<div class="backtoblog">';
					$data .= '<a title="'.__("Are you lost?","pie-register").'" href="'.esc_url(get_bloginfo("url")).'">&larr; '.__("Back to","pie-register").' '.get_bloginfo("name").'</a>';
				$data .= '</div>';
			}
			return $data;
		}
		function add_email_or_username(){
			$data = '';
			if(isset($this->pr_option['forgot_pass_username_label']) && !empty($this->pr_option['forgot_pass_username_label']))
			{
				$data .= '<label for="user_login">'.((isset($this->pr_option['forgot_pass_username_label']) && !empty($this->pr_option['forgot_pass_username_label']))? esc_html($this->pr_option['forgot_pass_username_label']): __("Username or E-mail:","pie-register")).'</label>';
			}
		    $data .= '<input type="text" size="20" value="" class="input input_fields validate[required]" id="reset_user_login" name="user_login" placeholder="'.((isset($this->pr_option['forgot_pass_username_placeholder']) && !empty($this->pr_option['forgot_pass_username_placeholder']))? esc_attr($this->pr_option['forgot_pass_username_placeholder']): "").'">';
			return $data;
		}
		function add_capthca_label(){
			$forgot_pass_form = '<label style="margin-top:0px;">'.esc_html($this->pr_option['capthca_in_forgot_pass_label']).'</label>';
			return $forgot_pass_form;
		}
		function add_mathcapthca_input($piereg_widget = false){
			$data = "";
			$field_id = "";
			if($piereg_widget == true){
				$data .= '<div id="pieregister_math_captha_forgot_password_widget" class="piereg_math_captcha"></div>';
				$data .= '<input id="" type="text" class="piereg_math_captcha_input pr_math_captcha_input_field" name="piereg_math_captcha_forgot_pass_widget"/>';
				$field_id = "#pieregister_math_captha_forgot_password_widget";
			}
			else{
				$data .= '<div id="pieregister_math_captha_forgot_password" class="piereg_math_captcha"></div>';
				$data .= '<input id="" type="text" class="piereg_math_captcha_input pr_math_captcha_input_field" name="piereg_math_captcha_forgot_pass"/>';
				$field_id = "#pieregister_math_captha_forgot_password";
			}
			return array("data"=>$data,"field_id"=>$field_id);
		}
		function add_reset_submit(){
			$forgot_pass_form  = '<p class="submit">';
			$forgot_pass_form .= '<input type="submit" value="'.esc_attr(__('Reset my password',"pie-register")).'" class="button button-primary button-large" id="wp-reset-submit" name="user-submit">';
			$forgot_pass_form .= '</p>';
			return $forgot_pass_form;
		}
		function add_register_or_login($pagenow){
			$forgot_pass_form = "";
			if(isset($pagenow) && $pagenow == 'wp-login.php'){
				$forgot_pass_form  = '<p class="forgot_pass_links">';
				$forgot_pass_form .= '<a href="'.esc_url(wp_login_url()).'">'.__('Log in',"pie-register").'</a>';
				$forgot_pass_form .= ' | ';
				$forgot_pass_form .= '<a href="'.esc_url(wp_registration_url()).'">'.__('Register',"pie-register").'</a>';
				$forgot_pass_form .= '</p>';
				$forgot_pass_form .= '<p class="forgot_pass_links">';
				$forgot_pass_form .= '<a title="'.__('Are you lost?',"pie-register").'" href="'.esc_url(get_bloginfo("url")).'">&larr; '.__('Back to',"pie-register").' '.get_bloginfo("name").'</a>';
				$forgot_pass_form .= '</p>';
			}
			
			$forgot_pass_form = apply_filters('pie_login_form_links',$forgot_pass_form); # newlyAddedHookFilter
			return $forgot_pass_form;
		}
	}
}