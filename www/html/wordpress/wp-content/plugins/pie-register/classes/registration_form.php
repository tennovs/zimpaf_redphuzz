<?php
if( file_exists( dirname(__FILE__) . '/base.php') ) 
	require_once('base.php');

class Registration_form extends PieReg_Base
{
	var $id;
	var $no;
	var $name;
	var $field;
	var $data;
	var $label_alignment;
	var $pages;
	
	var $field_status;
	
	function __construct($form_id = "0")	
	{
		parent::__construct();
		$this->data = $this->getCurrentFields($form_id);
	}
	
	function createFieldName($text)
	{
		return $this->getMetaKey($text);			
	}
	function createFieldID()
	{
		return "field_".$this->field['id'];	
	}
	function getDefaultValue($name="")
	{
		if($name != "")
		{
			$this->name = $name;	
		}
		if(isset($_POST[$this->name]))
		{
			if( is_array($_POST[$this->name]) ){
				return $this->recursive_sanitize_array($_POST[$this->name]);
			}
			else{
				return wp_kses_post($_POST[$this->name]);
			}			
		}
		return ((isset($this->field['default_value']))?$this->field['default_value']:"");
	}
	
	function addClass($default = "input_fields",$val = array())
	{
		$class = $default." ".(isset($this->field['css'])?$this->field['css']:"");
		if(isset($this->field['required']) && $this->field['required'])
		{
			$val[] = "required";		
		}
		
		if(isset($this->field['length']) && intval($this->field['length']) > 0 )
		{
			$val[] = "maxSize[".intval($this->field['length'])."]";
		}
		if((isset($this->field['validation_rule']) && $this->field['validation_rule']=="number" ) || $this->field['type']=="number")
		{
			$val[] = "custom[number]";		
		}
		else if(isset($this->field['validation_rule']) && $this->field['validation_rule']=="alphanumeric")
		{
			$val[] = "custom[alphanumeric]";		
		}
		else if((isset($this->field['validation_rule']) && $this->field['validation_rule']  =="email" ) || $this->field['type']=="email")
		{
			$val[] = "custom[email]";		
		}
		else if((isset($this->field['validation_rule']) && $this->field['validation_rule']  =="alphabetic" ) || $this->field['type']=="name")
		{
			$val[] = "custom[alphabetic]";		
		}
		else if(
				((isset($this->field['validation_rule']) && $this->field['validation_rule']=="website") || $this->field['type']=="website")
				|| (isset($this->field['field_name']) && $this->field['field_name'] == 'url')
			)
		{
			$val[] = "custom[url]";		
		}		
		else if((isset($this->field['validation_rule']) && $this->field['validation_rule']=="standard") || (isset($this->field['phone_format']) && $this->field['phone_format']=="standard" ))
		{
			$val[] = "custom[phone_standard]";		
		}
		else if((isset($this->field['validation_rule']) && $this->field['validation_rule']=="international") || (isset($this->field['phone_format']) && $this->field['phone_format']=="international"))
		{
			$val[] = "custom[phone_international]";		
		}
		else if($this->field['type']=="time")
		{
			$val[] = "custom[number]";	
			$val[] = "minSize[1]";
			$val[] = "maxSize[2]";
			$val[] = "min[0]";
			
			if($this->field['hours']==TRUE)
			{
				if($this->field['time_type']=="12")
				{
					$val[] = "max[12]";
				}
				else
				{
					$val[] = "max[23]";	
				}
			}
			else if($this->field['mins']==TRUE)
			{
				$val[] = "max[59]";	
			}
				
		}
		else if( $this->field['type']=="upload" && (explode(",",$this->field['file_types']) > 0) )
        {
            if(!empty($this->field['file_types']))
            {
                $val[] = "funcCall[checkExtensions]"; 
                $val[] = "ext[".str_replace(array(","," "),array("|",""),$this->field['file_types'])."]";
            }
        }
		
		if(sizeof($val) > 0)
		{
			$val = " piereg_validate[".implode(",",$val)."]";
			$class .= $val;	
		}
		
		return $class;	
	}
	
	function addValidation()
	{
		if( !isset($this->field["validation_message"]) )	$this->field["validation_message"]		= "";
				
		if((isset($this->field['required']) && $this->field['required']) && !empty($this->field['validation_message']))
		{
			$val[] = 'data-errormessage-value-missing="'.$this->field['validation_message'].'"';
		}
		
		if(isset($this->field['validation_rule']))
		{
			if(
				$this->field['validation_rule']=="number" || 
				$this->field['type']=="number" || $this->field['validation_rule']=="alphanumeric" || 
				$this->field['validation_rule']=="email" || $this->field['type']=="email" || 
				$this->field['validation_rule']=="website" || $this->field['type']=="website" || 
				$this->field['type']=="phone" || $this->field['type']=="date")
			{
				$val[] = 'data-errormessage-custom-error="'.$this->field['validation_message'].'"';		
			}		
		}
		else if($this->field['type']=="time")
		{
			$val[] = 'data-errormessage-custom-error="'.$this->field['validation_message'].'"';		
			$val[] = 'data-errormessage-range-underflow="'.$this->field['validation_message'].'"';	
			$val[] = 'data-errormessage-range-overflow="'.$this->field['validation_message'].'"';
		}
		
		if(isset($val) && sizeof($val) > 0)
		{
			return implode(" ",$val);			
		}
	}
	
	
	function validateRegistration($errors)
	{
		
		if(!is_wp_error($errors))
		$errors = new WP_Error();
		$piereg 	= get_option(OPTION_PIE_REGISTER);
		/*
			*	Sanitizing post data
		*/
		$this->pie_post_array	= $this->piereg_sanitize_post_data_escape( ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
		global $wpdb;
	
		do_action("pieregister_registration_validation_before", $this->pie_post_array, $errors);
		
		$this->pie_post_array['username'] = preg_replace('/\s+/', '', strtolower($this->pie_post_array['username']));
		if ( !isset($this->pie_post_array['username']) || empty( $this->pie_post_array['username'] ) || !validate_username($this->pie_post_array['username']) )
		{
			$errors->add( "username" , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Invalid_Username",__('Invalid Username','pie-register' )));
		}
		else if ( username_exists( $this->pie_post_array['username'] ) )
		{
			$errors->add( "username" , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Username_already_exists",__('Username already exists','pie-register' )));
		}		
		
		// !is_email($this->pie_post_array['e_mail']) - 3.6.11
		
		$regXemail = "/^[a-zA-Z0-9.'_%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,5}/";

		if ( !isset($this->pie_post_array['e_mail']) || empty( $this->pie_post_array['e_mail'] ) || !preg_match($regXemail,$this->pie_post_array['e_mail'] ))
		{
			$errors->add( "email" , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Invalid_Email_address",__('Invalid email address','pie-register' )));
		}
		else if ( email_exists( $this->pie_post_array['e_mail'] ) )
		{
			$errors->add( "email" , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Email_address_already_exists",__('E-mail address already exists','pie-register' )));
		}
		
		if(is_array($this->data)){
			 foreach($this->data as $field)
			 {
				$checking = false;
				if($this->piereg_field_visbility_addon_active){
					if(isset($field['show_on']) && !empty($field['show_on']) && $field['show_on'] == "profile"){
						$checking = apply_filters('pie_addon_field_visibility_conditions',$checking,$field);
						if($checking){
							continue;
						}
					}
				}
				
			if(isset($field['id'])) {
				$slug = $this->createFieldName($field['type']."_".$field['id']);			
			} else {
				$slug = $this->createFieldName($field['type']."_");
			}
			$phone_format = "";
			if($field['type']=="username" || $field['type']=="password"){
				  $slug  = $this->createFieldName($field['type']);
			}
			elseif($field['type']=="email"){
				  $slug  = $this->createFieldName("e_mail");
			}
			/*
				*	work just 2way login phone
			*/
			elseif($field['type']=="two_way_login_phone"){
				include_once( $this->admin_path . 'includes/plugin.php' );
				$twilio_option = get_option("pie_register_twilio");
				$plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
				if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") && isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 1 && $plugin_status == "Activated" ){
					$slug  = "piereg_two_way_login_phone";
					$phone_format = "international";
				}
			}
			elseif($field['type']=="phone"){
				$phone_format = ( isset($field['phone_format']) ? $field['phone_format'] : "" );
			}

			$field_name			= ( isset($this->pie_post_array[$slug]) ? $this->pie_post_array[$slug] : "");
			if( $field['type']=="dropdown" )
			{
				$field_name			= ( isset($this->pie_post_array[$slug][0]) ? $this->pie_post_array[$slug][0] : "");
			}
			
			$required 			= ( isset($field['required']) ? $field['required'] : "" );
			
			$rule				= ( isset($field['validation_rule']) ? $field['validation_rule'] : "" );
			
			if( !isset($field['validation_message']) )	$field['validation_message']		= "";
			
			$validation_message	= "";
			
			if(isset($this->pie_post_array[$slug]) && $this->pie_post_array[$slug] != "")
			{
				$key = $slug;
				$row = $this->pie_post_array[$slug];
				$key_id = explode("_",$key);
					
				if($row == "" and $this->data[$field['required']] != "" ){
					$crnt_fld 	= $this->data[$field['id']];
					$main_fld 	= $this->data[$field['selected_field']];					
					$slug 		= $this->createFieldName($main_fld['type']."_".$main_fld['id']);
					
					$main_field_value = "";
					if($main_fld['type'] == "dropdown")
					{
						$main_field_value = $this->pie_post_array[$slug][0];
					} 
					else 
					{
						$main_field_value = $this->pie_post_array[$slug];
					}
				}
			}
			
			/*
			 	* Valiate terms and conditions field. 
			*/
				if($field['type'] == "terms" && $required != "" )
				{
					$error_msg = __('Please confirm your acceptance of our terms & conditions','pie-register');
					$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : $error_msg .". ");					
				}

			/*
				* Validate List
			*/
				if($field['type'] == "list" && (isset($field['required']) && $field['required'] != "") )
				{					
						
					$list = $this->pie_post_array[$field['type'] .'_'.$field['id']];					
					$validation = false; 										
					foreach ($list[0] as $value) {

						if ($value != "") { 
							$validation = true;
						}
					}

					

					if( $validation == false )
					{ 						
						$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : $field['label']." ".__("is required.","pie-register"));
						$errors->add( $slug , "<strong>". ucwords(__("error","pie-register")).":</strong> " .$validation_message );	
						
					}else
					{
						$required 			= "";
					}
				}
			
			if($field['type']=="two_way_login_phone")
			{
				include_once( $this->admin_path . 'includes/plugin.php' );
				$twilio_option = get_option("pie_register_twilio");
				$plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
				if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") && isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 1 && $plugin_status == "Activated" ){
					$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : $field['label']." ".__("is required.","pie-register"));
				}else{
					$required = "";
				}
			}
				
			if( $validation_message == "" && $required != "" )
			{
				if( $field['type'] !== 'form' )
				{
					$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : $field['label']." ".__("is required.","pie-register"));
				}				
			}
			

			//Handling File Field
			if($field['type']=="profile_pic")
			{
				$field_name = sanitize_text_field($_FILES[$slug]['name']);
				if($_FILES[$slug]['name'] != ''){
					$result = $this->piereg_validate_files($_FILES[$slug]['name'],array("gif","GIF","jpeg","JPEG","jpg","JPG","png","PNG","bmp","BMP"));
					if(!$result){
						$errors->add( $slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Invalid_File_Type_In_Profile_Picture",__('Invalid file type used for profile picture.','pie-register' )));
					}
				}
			}
			elseif($field['type']=="upload"){
				$field_name = sanitize_text_field($_FILES[$slug]['name']);
				if($_FILES[$slug]['name'] != '' and $field['file_types'] != ""){
					$filter_array = stripcslashes($field['file_types']);
					$filter_array = explode(",",$filter_array);
					$result = $this->piereg_validate_files($_FILES[$slug]['name'],$filter_array);
					if(!$result){
						$errors->add( $slug , apply_filters("piereg_invalid_file",'<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Invalid_File_Type",__('Invalid file type','pie-register' ))));
					}
				}
			} // dropdown_ur
			elseif($field['type']=="custom_role"){
				$field_name = isset($this->pie_post_array['custom_role']) ? $this->pie_post_array['custom_role'] : '';
			}
			else if($field['type']=="invitation")
			{
				if($piereg["enable_invitation_codes"]=="1"){

					$manage_settings = maybe_unserialize(get_option("pie_fields")); 
					$allowed_invite_codes = []; 
					foreach($manage_settings as $managed_setting){
						if($managed_setting['type'] == 'invitation'){
							if(isset($managed_setting['allowed_codes'])){
								$allowed_invite_codes = $managed_setting['allowed_codes'];
							}
						}
					}
					$field_name = $code = $this->pie_post_array['invitation'];
					if($required != "" || $this->pie_post_array['invitation'] != "")
					{
						$expiry     = '';
						$codetable	= $this->codeTable();				
						$codes = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $codetable where BINARY name = %s and status = %d", $code, 1) );
						if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
							$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}
						if(is_array($codes)){
							foreach($codes as $c)	
							{
								$times_used = $c->count;
								$usage 		= $c->code_usage;
								$expiry 	= $c->expiry_date;
							}
						}
	
						if(count($codes) != 1)
						{
							$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : __('Invalid invitation code','pie-register' ));
							$errors->add( $slug , apply_filters("piereg_invalid_invitaion_code",'<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$validation_message));
						}elseif(!empty($allowed_invite_codes) && !in_array($codes[0]->name,$allowed_invite_codes)){
							$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : __('Invalid invitation code','pie-register' ));
							$errors->add( $slug , apply_filters("piereg_invalid_invitaion_code",'<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$validation_message));
						}
						elseif($times_used >= $usage and $usage != 0)
						{
							$errors->add( $slug , apply_filters("piereg_invitaion_code_expired",'<strong>'.ucwords(__('error','pie-register')).'</strong>: '.__('Invitation code has expired','pie-register' )));
						}
					}
				}elseif($required != ""){
					$required = 0;
				}
			}
			else if($field['type']=="captcha"){
				$settings  		=  get_option(OPTION_PIE_REGISTER);
				if($settings['piereg_recaptcha_type'] == "v2"){
					$privatekey		= $settings['captcha_private'];
				}elseif($settings['piereg_recaptcha_type'] == "v3"){
					$privatekey		= $settings['captcha_private_v3'];
				}
				//No Captcha ReCaptcha
				if( !empty($privatekey) ){
					$captcha	= (isset($_POST['g-recaptcha-response']) && ! empty( $_POST['g-recaptcha-response'] )) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : "";
					$response = $this->read_file_from_url("https://www.google.com/recaptcha/api/siteverify?secret=".trim($privatekey)."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
					$resp = json_decode($response,true);
					if($resp['success'] == false){
						$errors->add('recaptcha_mismatch',"<strong>".ucwords(__('error','pie-register'))."</strong>: ". apply_filters("piereg_Invalid_Security_Code",__("Invalid Security Code", 'pie-register')));
					}
				}
				
			
			}
			else if($field['type']=="math_captcha")
			{ 
				if(isset($_POST['piereg_math_captcha']))
				{
					$currentTabId =  intval($_COOKIE['currentTabId']);		
					$piereg_cookie_array =  ( (isset( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration'] ) && 0 < strpos( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration'], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration']) : "");
					$piereg_cookie_array = explode("|",$piereg_cookie_array);
					$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
					$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
					$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
					$field_name = sanitize_text_field($_POST['piereg_math_captcha']);
					if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $_POST['piereg_math_captcha'])){
					}
					else{
						$errors->add('math_captcha_mismatch',"<strong>".ucwords(__('error','pie-register'))."</strong>: ". apply_filters("piereg_Invalid_Security_Code",__("Invalid Math Captcha", 'pie-register')));
					}
				}
				elseif(isset($_POST['piereg_math_captcha_widget']))
				{
					$currentTabId =  intval($_COOKIE['currentTabId']);		
					$piereg_cookie_array =  ( (isset( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration_widget'] ) && 0 < strpos( $_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration_widget'], '|' )) ? sanitize_text_field($_COOKIE['tab_'.$currentTabId.'_piereg_math_captcha_registration_widget']) : "");
					$piereg_cookie_array = explode("|",$piereg_cookie_array);
					$cookie_result1 = (intval(base64_decode($piereg_cookie_array[0])) - 12);
					$cookie_result2 = (intval(base64_decode($piereg_cookie_array[1])) - 786);
					$cookie_result3 = (intval(base64_decode($piereg_cookie_array[2])) + 5);
					$field_name = sanitize_text_field($_POST['piereg_math_captcha_widget']);
					if( ($cookie_result1 == $cookie_result2) && ($cookie_result3 == $_POST['piereg_math_captcha_widget'])){
					}
					else{
						$errors->add('math_captcha_mismatch',"<strong>".ucwords(__('error','pie-register'))."</strong>: ". apply_filters("piereg_Invalid_Security_Code",__("Invalid Math Captcha", 'pie-register')));
					}
				}
				else{
					$errors->add('math_captcha_mismatch',"<strong>".ucwords(__('error','pie-register'))."</strong>: ". apply_filters("piereg_Invalid_Security_Code",__("Invalid Math Captcha", 'pie-register')));
				}
			}
			else if($field['type']=="name")
			{
				$field_name		= $this->pie_post_array["first_name"];
				$field_name_lst	= $this->pie_post_array["last_name"];				
			}			
			
			if( (!isset($field_name) || empty($field_name)) && $required)
			{
				
				$errors->add( $slug , "<strong>". ucwords(__("error","pie-register")).":</strong> " .$validation_message );
				if( $field['type']=="name" && ( (!isset($field_name_lst) || empty($field_name_lst)) && $required) )
				{
					$validation_message_lst	= (!empty($field['validation_message']) ? $field['validation_message'] : $field['label2']." ".__("is required.","pie-register"));
					$errors->add( $slug."_lst" , "<strong>". ucwords(__("error","pie-register")).":</strong> " .$validation_message_lst );
				}
			
			} else if((!isset($field_name) || empty($field_name)) && !$required){
				continue;
			}
			else if($rule=="number" && !empty($field_name))
			{
				if(!is_numeric($field_name))
				{
					$errors->add( $slug , "<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_field_must_contain_only_numbers",__(" Field must only contain numbers." ,"pie-register")));		
				}	
			}
			else if($rule=="alphanumeric" && !empty($field_name))
			{
				if(! preg_match("/^([a-z 0-9])+$/i", $field_name))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_field_may__alpha_numeric_characters",__(" Field must only contain alpha-numeric characters."  ,"pie-register")));		
				}	
			}	
			else if($rule=="alphabetic" && !empty($field_name))
			{
				//if(! preg_match("/^[a-zA-Z ]+$/", $field_name)) //
				if(! preg_match("/^[a-zA-Z\p{Cyrillic}\s\-]+$/u", $field_name))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_field_may__alphabetic_characters",__(" Field must only contain letters."  ,"pie-register")));		
				}	
			}
			else if($rule=="email" && !empty($field_name))
			{
				// $regXemail = "/[-0-9a-zA-Z.+_']+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/";

				if( !is_email($field_name) )
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_field_must_contain_valid_email",__(" Field must contain a valid email address." ,"pie-register")));		
				}	
			}	
			else if($rule=="website" && !empty($field_name) || (isset($field['field_name']) && $field['field_name'] == 'url'))
			{
				if(!filter_var($field_name,FILTER_VALIDATE_URL))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_must_be_a_valid_URL",__(" Must be a valid URL." ,"pie-register")));
				}	
			}
			if( $phone_format == "international" && isset($this->pie_post_array[$slug]) && !empty($this->pie_post_array[$slug]) ){
				$regXinternational_phone = "/^(\+|00)?(?:[0-9]( |-)?){1,3}(?:[0-9]( |-)?){4,14}$/";
				if(!preg_match($regXinternational_phone,$this->pie_post_array[$slug])){
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"pie-register").":</strong> ".$field['label'] .apply_filters("piereg_invalid_phone_field",__(" is an invalid phone number." ,"pie-register")));
				}
			}			
		 }
		}
		
		do_action("pieregister_registration_validation_after", $this->pie_post_array, $errors);

		return $errors;
	}
	function addUser($user_id, $form_id = 0)
	{
		global $wpdb;
		$piereg 	= get_option(OPTION_PIE_REGISTER);

		if(is_array($this->data)){

			$this->pie_post_array = apply_filters('piereg_userreg_data_before_adding', $this->pie_post_array);

			foreach($this->data as $field)
			{
				//Some form fields which we can't save like paypal, submit,formdata
				if(!isset($field['meta']))
				{
					if($field['type']=="default")
					{
						$slug 				= $field['field_name'];				
						$value				= $this->pie_post_array[$slug];
						update_user_meta($user_id, $slug, $value);	

						$usermeta_id		= $this->get_usermeta_id_by_key($user_id, $slug);
						do_action("pieregister_update_usermeta_piereg_field", $usermeta_id, $field['id'], $user_id);
					}
					else if($field['type']=="invitation" && $piereg["enable_invitation_codes"]=="1")
					{
						$prefix		= $wpdb->prefix."pieregister_";
						$codetable	= $prefix."code";				
						$codes 		= $wpdb->query( $wpdb->prepare("update $codetable set count = count + 1 where BINARY name = %s and status = %d", $this->pie_post_array['invitation'], 1) );
						if(isset($wpdb->last_error) && !empty($wpdb->last_error)){
							$this->pr_error_log($wpdb->last_error.($this->get_error_log_info(__FUNCTION__,__LINE__,__FILE__)));
						}
						
						update_user_meta($user_id, "invite_code", $this->pie_post_array['invitation']);							
					} //  dropdown_ur
					else if($field['type']=="custom_role" && isset($this->pie_post_array['custom_role']))
					{	
						update_user_meta($user_id, "custom_role", $this->pie_post_array['custom_role']);							
					}
					else if($field['type']=="name")
					{
						$slug 				= "first_name";				
						$value				= $this->pie_post_array[$slug];
						update_user_meta($user_id, $slug, $value);	

						$usermeta_id		= $this->get_usermeta_id_by_key($user_id, $slug);
						do_action("pieregister_update_usermeta_piereg_field", $usermeta_id, $field['id'], $user_id);

						$slug 				= "last_name";				
						$value				= $this->pie_post_array[$slug];
						update_user_meta($user_id, $slug, $value);	

						$usermeta_id		= $this->get_usermeta_id_by_key($user_id, $slug);
						do_action("pieregister_update_usermeta_piereg_field", $usermeta_id, $field['id'] . 1000, $user_id);
					}
					else if($field['type']=="profile_pic")
					{
						$slug 			= $this->createFieldName($field['type']."_".$field['id']);
						$field_name		= isset($this->pie_post_array[$slug]) ? $this->pie_post_array[$slug] : "";
						$this->pie_profile_pictures_upload($user_id,$field,$slug);
						
						// mailchimp related code within PR
						$usermeta_id		= $this->get_usermeta_id_by_key($user_id, 'pie_'.$slug);
						do_action("pieregister_update_usermeta_piereg_field", $usermeta_id, $field['id'], $user_id);
					
					}
					else if($field['type']=="upload")
					{
						$slug 			= $this->createFieldName($field['type']."_".$field['id']);
						if(!empty($_FILES[$slug]['name'])){
							$field_name		= isset($this->pie_post_array[$slug]) ? $this->pie_post_array[$slug] : "";
							$this->pie_upload_files($user_id,$field,$slug,$form_id);	
						}
					}
					else if($field['type']=="two_way_login_phone")
					{
						include_once( $this->admin_path . 'includes/plugin.php' );
						$twilio_option = get_option("pie_register_twilio");
						$plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
						if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") && isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 1 && $plugin_status == "Activated" ){
							$field_name			= (isset($this->pie_post_array["piereg_two_way_login_phone"]) ? trim($this->pie_post_array["piereg_two_way_login_phone"]) : "");
							update_user_meta($user_id, "piereg_two_way_login_phone", $field_name);
						}
					}
					else if($field['type']=="pricing")
					{
						$field_name			= (isset($this->pie_post_array["select_payment_method"]) ? trim($this->pie_post_array["select_payment_method"]) : "");
						update_user_meta($user_id, "pie_pricing", $field_name);
												
					}
					//pie-register-woocommerce addon
					else if($field['type']=="wc_billing_address")
					{
						do_action("pieregister_meta_update_woocommerce_billing_address", $user_id, $this->createFieldName($field['type']."_".$field['id']), $this->pie_post_array);
					}
					else if($field['type']=="wc_shipping_address")
					{
						do_action("pieregister_meta_update_woocommerce_shipping_address", $user_id, $this->createFieldName($field['type']."_".$field['id']), $this->pie_post_array);
					}
					else
					{
						if($field['type'] != "honeypot"){
							$slug 				= $this->createFieldName($field['type']."_".$field['id']);
							$field_name			= isset($this->pie_post_array[$slug]) ? $this->pie_post_array[$slug] : "";
							update_user_meta($user_id, "pie_".$slug, $field_name);
						}
						// mailchimp related code within PR
						$usermeta_id		= $this->get_usermeta_id_by_key($user_id, 'pie_'.$slug);
						do_action("pieregister_update_usermeta_piereg_field", $usermeta_id, $field['id'], $user_id);
					}
				}
			}
		}
	}

	function get_usermeta_id_by_key($user_id, $meta_key)
	{
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}usermeta WHERE `user_id` = ".$user_id." AND `meta_key` = '".$meta_key."'";
		$query = $wpdb->get_row( $sql, OBJECT );
		return $query->umeta_id;
	}
}

if( file_exists( get_stylesheet_directory().'/pie-register/pie_register_template/registration/registration_form_template.php' ) ){
	require_once( get_stylesheet_directory().'/pie-register/pie_register_template/registration/registration_form_template.php' );
}
else{
	require_once(PIEREG_DIR_NAME.'/pie_register_template/registration/registration_form_template.php');
}