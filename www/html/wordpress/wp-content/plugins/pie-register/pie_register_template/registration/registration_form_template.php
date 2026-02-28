<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if( file_exists( (PIEREG_DIR_NAME)."/classes/registration_form.php" ) ){
	require_once( (PIEREG_DIR_NAME)."/classes/registration_form.php" );
}

class Registration_form_template extends Registration_form
{
	var $is_pr_widget = false;	
	var $pageBreak_prev_label 	= '';
	var $pageBreak_prev_type 	= '';
	
	function addDesc()
	{
		if(!empty($this->field['desc']))
		{
			return '<p class="desc">'.html_entity_decode($this->field['desc']).'</p>';
		}
	}
	function addLabel($isblank="")
	{
		if(isset($this->field['label']) && empty($this->field['label']) ) 
		{
			return "";
		}
		
		if($this->field['type'] == "html" && $this->field['label'] == ""){
			return "";
		}
		if($this->field['type']=="name" && $this->field['name_format']=="normal")
		{
			return "";
		}
		$field_required = "";
		if( isset($this->field['required']) && $this->field['required'] != "" )
			$field_required .= '&nbsp;<span class="piereg_field_required_label">*</span>';
		
		$topclass = "";
		if($this->label_alignment=="top")
			$topclass = "label_top";
	
		$labelled = "";
		if(isset($this->field['label'])) {
			$labelled = __(html_entity_decode(stripslashes($this->field['label'])),"pie-register").$field_required;
		}
		
		if($isblank == 'empty'){
			$labelled = "&nbsp;";
		}
				
		return '<label class="'.$topclass .'" for="'.$this->name.'">'.$labelled.'</label>';
	}
	function addFormData($title="true",$description="true")
	{
		if( !isset($this->data['form']['css']) ) 		$this->data['form']['css'] 		= "";
		if( !isset($this->data['form']['label']) ) 		$this->data['form']['label'] 	= "";
		if( !isset($this->data['form']['desc']) ) 		$this->data['form']['desc'] 	= "";
		
		$data = "";
		$data .= '<div class="fieldset '.$this->data['form']['css'].'">';
		if($title == "true"){
			$data .= '<h2 id="piereg_pie_form_heading">'.$this->data['form']['label'].'</h2>';	
		}
		if($description == "true"){
			$data .= '<p id="piereg_pie_form_desc" >'.nl2br(html_entity_decode(stripslashes($this->data['form']['desc']))).'</p>';
		}
		$data .= '</div>';		
		$data  = apply_filters('piereg_edit_above_form_data',$data); // newlyAddedHookFilter
		
		return $data;
	
	}
	function addDefaultField()
	{
		
		$data = "";
		$this->name = $this->field['field_name'];
		
		if(isset($this->field['placeholder'])):
			$this->field['placeholder'];
		else:
			$this->field['placeholder'] = "";
		endif;	
		
		$data .= '<div class="fieldset">'.$this->addLabel();
		
		if($this->field['field_name']=="description")
		{
			$data .= '<textarea name="description" id="description" rows="5" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" cols="80" >'.$this->getDefaultValue().'</textarea>';	
		}
		else
		{
			$data .= '<input id="'.$this->id.'" name="'.$this->field['field_name'].'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass().'"  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue().'" />';	
		}
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addTextField(){
		
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input '.$this->read_only.' id="'.$this->get_pr_widget_prefix().$this->id.'" name="'.$this->name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass().'" '.$this->addValidation().'  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue().'" />';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addHiddenField($form_label)
	{
		$default_value = $this->getDefaultValue();
		$tag_fields = array(
			'admin_email'     => esc_html__( 'Site Admin Email', 'pie-register' ),
			'site_name'       => esc_html__( 'Site Name', 'pie-register' ),
			'site_url'        => esc_html__( 'Site URL', 'pie-register' ),
			'page_title'      => esc_html__( 'Page Title', 'pie-register' ),
			'page_url'        => esc_html__( 'Page URL', 'pie-register' ),
			'page_id'         => esc_html__( 'Page ID', 'pie-register' ),
			'form_name'       => esc_html__( 'Form Name', 'pie-register' ),
			'user_ip_address' => esc_html__( 'User IP Address', 'pie-register' ),
			'user_id'         => esc_html__( 'User ID', 'pie-register' ),
			'user_name'       => esc_html__( 'User Name', 'pie-register' ),
			'user_email'      => esc_html__( 'User Email', 'pie-register' ),
			'referrer_url'    => esc_html__( 'Referrer URL', 'pie-register' )
		);

		foreach ( $tag_fields as $key => $key ) {
			switch ( $key ) {
				case 'admin_email':
					$admin_email = sanitize_email( get_option( 'admin_email' ) );
					$field_name  = str_replace( '{' . $key . '}', $admin_email, $default_value );
					break;

				case 'site_name':
					$site_name    = get_option( 'blogname' );
					$default_value   = str_replace( '{' . $key . '}', $site_name, $default_value );
					break;

				case 'site_url':
					$site_url    = get_option( 'siteurl' );
					$default_value  = str_replace( '{' . $key . '}', $site_url, $default_value );
					break;

				case 'page_title':
					$page_title   = get_the_ID() ? get_the_title( get_the_ID() ) : '';
					$default_value   = str_replace( '{' . $key . '}', $page_title, $default_value );
					break;

				case 'page_url':
					$page_url    = get_the_ID() ? get_permalink( get_the_ID() ) : '';
					$default_value  = str_replace( '{' . $key . '}', $page_url, $default_value );
					break;

				case 'page_id':
					$page_id    = get_the_ID() ? get_the_ID() : '';
					$default_value = str_replace( '{' . $key . '}', $page_id, $default_value );
					break;

				case 'form_name':
					$default_value = str_replace( '{' . $key . '}', $form_label, $default_value );
					break;

				case 'user_ip_address':
					$user_ip_add = $user_ip = $_SERVER['REMOTE_ADDR'];
					$default_value  = str_replace( '{' . $key . '}', $user_ip_add, $default_value );
					break;
				case 'user_id':
					$tag_user_id = is_user_logged_in() ? get_current_user_id() : '';
					$default_value = str_replace( '{' . $key . '}', $tag_user_id, $default_value );
					break;

				case 'user_email':
					if ( is_user_logged_in() ) {
						$user  = wp_get_current_user();
						$email = sanitize_email( $user->user_email );
					} else {
						$email = '';
					}
					$default_value = str_replace( '{' . $key . '}', $email, $default_value );
					break;

				case 'user_name':
					if ( is_user_logged_in() ) {
						$user = wp_get_current_user();
						$name = sanitize_text_field( $user->user_login );
					} else {
						$name = '';
					}
					$default_value = str_replace( '{' . $key . '}', $name, $default_value );
					break;

				case 'referrer_url':
					$referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
					$default_value = str_replace( '{' . $key . '}', sanitize_text_field( $referer ), $default_value );
					break;

			}
		}
		$data  = '<div class="fieldset">';
		$data .= '<input id="'.$this->id.'" name="'.$this->name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" type="hidden" value="'.$default_value.'" />';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addUsername($form_widget = false){
		if( !isset($this->field["validation_message"]) )	$this->field["validation_message"]		= "";
		
		$formwidget = (isset($form_widget) && $form_widget == true)? '_widget' : '';
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input id="username'.$formwidget.'" name="username" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="input_fields '.$this->field['css'].' piereg_validate[required,username] piereg_username_input_field" placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue('username').'" data-errormessage-value-missing="'.$this->field['validation_message'].'"  />';
		$data .= $this->addDesc();
		$data .= '</div>';	
		return $data;
	}
	function addPassword($fromwidget,$field_status = "")
	{
		if( !isset($this->field["validation_message"]) )	$this->field["validation_message"]		= "";
		
		$style = "";
		$data = "";
		if($fromwidget == true)
		{
			$this->id = $this->id."_widget";
		}
		$data .= '<div class="fieldset">'.$this->addLabel();
		if($this->label_alignment=="left")
			$style = 'class = "wdth-lft mrgn-lft"';		
		
		$pass_strength = apply_filters( 'pie_password_strength_length', 'minSize[8]' );	

		if( isset($this->field['password_generator']) && $this->field['password_generator'] != "" ){
			$data .= '<div class="password_field" style="display: none;">';
			$data .= '<input type="password" name="password" id="'.$this->id.'" name="password" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass("input_fields",array($pass_strength)).' prPass1" placeholder="'.$this->field['placeholder'].'" type="password" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" autocomplete="off" >';
			$data .= '<span class="show-hide-password-innerbtn eye-slash"></span>';
			$data .= '</div>';

				$data .= '<div class="password_generator_div">';
				$data .= '<input ';
				$data .= 'name="password_generator" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" placeholder="'.$this->field['placeholder'].'" type="button" value="'.__('Generate Password','pie-register').'" class="generate_password gen_pass">';
				$data .= '</div>';
			
			$data .= '</div>';

				$field_required = "";
				if( isset($this->field['required']) && $this->field['required'] != "" )
					$field_required .= '&nbsp;<span class="piereg_field_required_label">*</span>';

				$class = '';
				$fclass = '';
				
				$topclass = "";
				if($this->label_alignment=="top")
					$topclass = "label_top"; 
				$label2 = (isset($this->field['label2']) and !empty($this->field['label2']))? $this->field['label2'] : "";
				
				$data .= '<div class="fieldset edit_confirm_pass" style="display:none;">';

					if(!empty($label2)) $data .= '<label>'.$label2.$field_required.'</label>';
					$data .= '<input id="confirm_password_'.$this->id.'" type="password" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" class="input_fields '.$this->field['css'].' piereg_validate['.$pass_strength.',required,equals['.$this->id.']]  prPass2" placeholder="'.$this->field['placeholder2'].'" autocomplete="off" >';

				$data .= '</div>';
			
		}else{
			$data .= '<input ';		
			$data .= 'id="'.$this->id.'" name="password" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass("input_fields",array($pass_strength)).' prPass1" placeholder="'.$this->field['placeholder'].'" type="password" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" autocomplete="off" >';
			$data .= '<span class="show-hide-password-innerbtn pass-eye-reg eye"></span>';
			$data .= '</div>';

			$field_required = "";
			if( isset($this->field['required']) && $this->field['required'] != "" )
				$field_required .= '&nbsp;<span class="piereg_field_required_label">*</span>';

			$class = '';
			$fclass = '';
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 
			$label2 = (isset($this->field['label2']) and !empty($this->field['label2']))? $this->field['label2'] : "";
			
			$data .= '<div class="fieldset">';
			if(!empty($label2)) $data .= '<label>'.$label2.$field_required.'</label>';
			$data .= '<input id="confirm_password_'.$this->id.'" type="password" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" class="input_fields '.$this->field['css'].' piereg_validate['.$pass_strength.',required,equals['.$this->id.']]  prPass2" placeholder="'.$this->field['placeholder2'].'" autocomplete="off" />';
			
			$data .= $this->addDesc();
			$data .= '</div>';

		}
			
		return $data;
	}	
	function addEmail($fromwidget)
	{
		
		$data = $_readonly = "";
		
		if($fromwidget == true)
		{
			$this->id = $this->id."_widget";
		}
		$data .= '<div class="fieldset">'.$this->addLabel();
		
		// invite_through_email		
		$_email_invite = $this->getDefaultValue("e_mail");

		$data .='<input '.$_readonly.' id="'.$this->id.'" name="e_mail" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass().'" '.$this->addValidation().' placeholder="'.$this->field['placeholder'].'" type="text" value="'.$_email_invite.'" />';
		
		if(isset($this->field['confirm_email']))
		{
			$class = '';
			$fclass = '';
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 	
			
			$field_required = "";
			if( isset($this->field['required']) && $this->field['required'] != "" )
				$field_required .= '&nbsp;<span class="piereg_field_required_label">*</span>';
			
			$data .= '</div>';
			$label2 = (isset($this->field['label2']) and !empty($this->field['label2']))? $this->field['label2'] : "";
			$data .= '<div class="fieldset">';
			if(!empty($label2)) $data .= '<label>'.$label2.$field_required.'</label>';
			$data .= '<input  placeholder="'.$this->field['placeholder2'].'" id="confirm_email_'.$this->id.'" '.$this->addValidation().' type="text" class="input_fields piereg_validate[required,equals['.$this->id.']]" autocomplete="off">';
		}
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addUpload()
	{
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input '.$this->read_only.' id="'.$this->id.'" name="'.$this->name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass().'"  '.$this->addValidation().' type="file"  />';
		
		if( isset( $this->field['file_types'] ) && !empty($this->field['file_types'])  )
		{
			$data .= '<p class="desc style_filetypes">Allowed File Types: '.$this->field['file_types'].'</p>';	
		}
		
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addProfilePicUpload()
	{
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input '.$this->read_only.' id="'.$this->id.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" name="'.$this->name.'" class="'.$this->addClass().' piereg_validate[funcCall[checkExtensions],ext[gif|GIF|jpeg|JPEG|jpg|JPG|png|PNG|bmp|BMP]]"  '.$this->addValidation().' type="file"  />';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addTextArea()
	{
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<textarea '.$this->read_only.' id="'.$this->id.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" name="'.$this->name.'" rows="'.$this->field['rows'].'" cols="'.$this->field['cols'].'" class="'.$this->addClass().'" '.$this->addValidation().' placeholder="'.$this->field['placeholder'].'">';
		$data .= $this->getDefaultValue();
		$data .= '</textarea>';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addName()
	{
		$field_required="";
		if( isset($this->field['required']) && $this->field['required'] != "" ){
			$field_required .= '&nbsp;<span class="piereg_field_required_label">*</span>';
		}
		
		$placeholder 	= (isset($this->field['placeholder']) and !empty($this->field['placeholder']))?$this->field['placeholder'] : "";
		$placeholder2 	= (isset($this->field['placeholder2']) and !empty($this->field['placeholder2']))?$this->field['placeholder2'] : "";
		
		$data  = '<div class="fieldset">';
		if(!empty($this->field['label'])) $data .= '<label>'.__($this->field['label'],"pie-register") . $field_required . '</label>';
		$data .= '<input '.$this->read_only.' value="'.$this->getDefaultValue('first_name').'" placeholder="'.$placeholder.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" id="'.$this->id.'_firstname" name="first_name" class="'.$this->addClass().' input_fields piereg_name_input_field" '.$this->addValidation().'  type="text"  />';				
		
		$topclass = "";
		if($this->label_alignment=="top")
			$topclass = "label_top";
		$data .= '</div>';
		$label2 = (isset($this->field['label2']) and !empty($this->field['label2']))? $this->field['label2'] : "";
		$data .= '<div class="fieldset">';
		if(!empty($label2)) $data .= '<label>'.$label2 . $field_required .'</label>';
		$data .= '<input '.$this->read_only.' value="'.$this->getDefaultValue('last_name').'" placeholder="'.$placeholder2.'" id="'.$this->id.'_lastname" name="last_name" class="'.$this->addClass().' input_fields piereg_name_input_field" '.$this->addValidation().'  type="text"  />';	
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
		
	}
	function addTime()
	{
		$data = "";
		$this->field['hours'] = TRUE;
		$name = $this->name;
		
		$time_this_values = $this->getDefaultValue($name);
		$data .= '<div class="fieldset">'.$this->addLabel();
		$data .= '<div class="piereg_time">';
		$data .= '<div class="time_fields">';
		$data .= '<input '.$this->read_only.' value="'.( (isset($time_this_values["hh"])) ? $time_this_values["hh"] : "" ).'" maxlength="2" id="hh_'.$this->id.'" name="'.$this->name.'[hh]" type="text"  class="'.$this->addClass().'"  '.$this->addValidation().'>';
		$data .= '<label>'.__("HH","pie-register").'</label>';
		$data .= '</div>';
		$this->field['hours'] = FALSE;
		
		$this->field['mins'] = TRUE;
		$data .= '<span class="colon">:</span>';
		$data .= '<div class="time_fields">';
		$data .= '<input '.$this->read_only.' value="'.( (isset($time_this_values["mm"])) ? $time_this_values["mm"] : "" ).'" maxlength="2" id="mm_'.$this->id.'" type="text" name="'.$this->name.'[mm]"  class="'.$this->addClass().'"  '.$this->addValidation().'>';
		$data .= '<label>'.__("MM","pie-register").'</label>';
		$data .= '</div>';
		$data .= '<div id="time_format_field_'.$this->id.'" class="time_fields"></div>';
		$this->field['mins'] = FALSE;
		
		if($this->field['time_type']=="12")
		{
			$time_format_val = ( (isset($time_this_values["time_format"])) ? $time_this_values["time_format"] : "" );
			$data .= '<div class="time_fields">';
			$data .= '<select '.$this->read_only.' name="'.$this->name.'[time_format]" >';
				$data .= '<option value="am" '; 
						$data .=($time_format_val == "am")?'selected=""':'';
						$data .='>AM</option>';
				$data .='<option value="pm"  ';
						$data .=($time_format_val == "pm")?'selected=""':'';
						$data .='>PM</option>';
			$data .= '</select>';
			$data .= '</div>';
		}
		
		$data .= '</div>';
		$data .= $this->addDesc();
		$data .= '</div>';

		if($this->piereg_field_visbility_addon_active){
			$this->readibility = apply_filters("pie_add_hidden_field_addon", $this->read_only);
		}

		if($this->readibility){
			$this->read_only = "";
			$data .= '<div class="control_visibility">';
			$data .=  $this->addTime();
			$data .=  '</div>';
		}
		
		return $data;
	}	
	function addDropdown()
	{ 
		
		$data = "";
		$multiple = "";
		$name = $this->name."[]";
		$field_id = $this->name;
		$thispostedvalue = $this->getDefaultValue();
		
		$data .= '<div class="fieldset" >'.$this->addLabel();
		if($this->field['type']=="multiselect")
		{
			$multiple 	= 'multiple';			
			//$name = $this->name."[]";
		} elseif($this->field['type'] == 'custom_role'){
			$multiple 	= "";
			$name 		= "custom_role";
		}

		$data .= '<select '.$this->read_only.' '.$multiple.' id="'.$field_id.'" name="'.$name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" class="'.$this->addClass().'" '.$this->addValidation().'  >';
	
		if(isset($this->field['list_type']) && $this->field['list_type']=="country")
		{
			 $countries = get_option("pie_countries");			 
			$data .= $this->createDropdown($countries);			   	
		}
		else if(isset($this->field['list_type']) && $this->field['list_type']=="us_states")
		{
			 $us_states = get_option("pie_us_states");
			 $options 	= $this->createDropdown($us_states);				 
			 $data .= $options;						   	
		}
		else if(isset($this->field['list_type']) && $this->field['list_type'] == "can_states")
		{
			$can_states = get_option("pie_can_states");			
			$data .= $options 	= $this->createDropdown($can_states);					
		}
		else if(sizeof($this->field['value']) > 0)
			{	
				for($a = 0 ; $a < sizeof($this->field['value']) ; $a++)
				{
					$selected = '';
					if(isset($this->field['selected']) && is_array($this->field['selected']) && in_array($a,$this->field['selected']))
					{
						$selected = 'selected="selected"';	
					}
					if(is_array($thispostedvalue)){
						foreach($thispostedvalue as $thissinglepostedval){
							if(!empty($this->field['value'][$a]) && $thissinglepostedval == $this->field['value'][$a]){
								$selected = 'selected="selected"';
							}
						}
					}
					elseif(!empty($this->field['value'][$a]) && $thispostedvalue == $this->field['value'][$a]){
						$selected = 'selected="selected"';
					}

					//if($this->field['value'][$a] !="" || $this->field['display'][$a] != "")
						$data .= '<option '.$selected.' value="'.$this->field['value'][$a].'">'.$this->field['display'][$a].'</option>';	
				}		
			}
		$data .= '</select>';
		
		$data .= $this->addDesc();
		$data .= '</div>';
		
		if($this->piereg_field_visbility_addon_active){
			$this->readibility = apply_filters("pie_add_hidden_field_addon", $this->read_only);
		}
		
		if($this->readibility){
			$this->read_only = "";
			$data .= '<div class="control_visibility">';
			$data .=  $this->addDropdown();
			$data .=  '</div>';
		}

		return $data;
	}
	function addPricing() //stripe_changes
	{
		$data = "";
		
		if ($this->check_enable_payment_method() == "true" && isset($this->field['allow_payment_gateways']) && !empty($this->field['allow_payment_gateways']) )
		{
			$send_data	= "";
			if( count( $this->field['allow_payment_gateways'] ) == 1 ) {				
				$data .= '<div class="fieldset">';
				$data .= $this->addLabel('empty');
				$send_data = apply_filters('get_payment_gateway_content',$send_data,$this->field['allow_payment_gateways']);
				$data .= $send_data;
				$data .= $this->addDesc();
				$data .= '<input type="hidden" name="select_payment_method" value="'.$this->field['allow_payment_gateways'][0].'" /></div>';
				
			}  else  {
				
				$data .= '<div class="pieregister_payment_option fieldset">';
				do_action("add_select_payment_script"); // Add script
				$data .= "<label>".__("Select Payment","pie-register")." <span class='piereg_field_required_label'>*</span></label>";
				$data .= '<select id="select_payment_method" name="select_payment_method">';
				$data .= '<option value="">'.__("Select Payment Method","pie-register").'</option>';
				$send_data = apply_filters('Add_payment_option',$send_data,$this->field['allow_payment_gateways']);
				$data .= $send_data."</select>";
				$data .= apply_filters("get_payment_content_area",0);
				$data .= '</div>';				
			
			}
			
			if( isset( $this->field['payment_charge'] ) && !empty($this->field['payment_charge']) )
			{
				$data	.= '<input type="hidden" name="payment_charge" value="'.((int)$this->field['payment_charge']).'" />';	
			}		
			
			return $data;
			
			// For future release, when recurring payment enable
			if( isset($this->field['allow_payment_gateways']) && !empty($this->field['allow_payment_gateways']) && 'recurring' == 'enable' )
			{
				/*
					0 = Dropdown
					1 = Radio
				*/
				if($this->field['field_as'] == 0){
					$data .= '<div class="fieldset">'.$this->addLabel();
					if(sizeof($this->field['display']) > 0)
					{
						$data .= '<div class="radio_wrap">';
						$thispostedvalue = $this->getDefaultValue();
						for($a = 0 ; $a < sizeof($this->field['display']) ; $a++)
						{
							$checked = '';						
								
							if(is_array($this->field['selected']) && in_array($a,$this->field['selected']) && empty($thispostedvalue))
								$checked = 'checked="checked"';	
							else
								$checked = '';
							
							if($thispostedvalue == $this->field['display'][$a])
								$checked = 'checked="checked"';
							
							$dymanic_class = $this->field['type']."_".$this->field['id'];
							$data .= "<label>";
							$data .= $this->field['display'][$a];	
							$data .= "</label>";
		
							$data .= '<input '.$checked.' value="'.$this->field['display'][$a].'" data-field_id="piereg_field_'.$this->no.'" type="radio" name="pricing" class="'.$this->addClass("input_fields").' radio_fields" '.$this->addValidation().' data-map-field-by-class="radio_'.$this->field['id'].'">';
						}
						$data .= "</div>";		
					}
				}else{
					$data .= '<div class="fieldset">';
					$data .= $this->addLabel();
					$data .= '<select id="field_id_'.$this->field['id'].'" name="pricing" data-field_id="piereg_field_'.$this->no.'" class="'.$this->addClass("input_fields").'" '.$this->addValidation().'  >';
					if(sizeof($this->field['display']) > 0)
					{	
						for($a = 0 ; $a < sizeof($this->field['display']) ; $a++)
						{
							$selected = '';
							if(isset($this->field['selected']) && is_array($this->field['selected']) && in_array($a,$this->field['selected']) && $thispostedvalue == "")
							{
								$selected = 'selected="selected"';	
							}
							if(is_array($thispostedvalue)){
								foreach($thispostedvalue as $thissinglepostedval){
									if($thissinglepostedval == $this->field['display'][$a]){
										$selected = 'selected="selected"';
									} 
								}
							}
							elseif($thispostedvalue == $this->field['display'][$a]){
								$selected = 'selected="selected"';
							}
			
							if($this->field['display'][$a] !="") 
								$data .= '<option '.$selected.' value="'.$this->field['display'][$a].'">'.$this->field['display'][$a].'</option>';
						}		
					}
					$data .= '</select>';
					
				}
				$data .= $this->addDesc();
				$data .= '</div>';
				return $data;
			}
			
		} else {
			$data = '<div class="fieldset">';
			$data .= $this->addLabel();
			$data .= '<p>'.__("No payment methods selected by administrator.","pie-register").'</p>';
			$data .= $this->addDesc();
			$data .= '</div>';
			return $data;
		}
	}
	
	function addNumberField()
	{
		$data = "";
		$min = "";
		$max = "";

		if( $this->field['min'] !== "" )
			$min .= 'min="'.$this->field['min'].'"';
		
		if(!empty($this->field['max']))
			$max .= 'max="'.$this->field['max'].'"';

		$data .= '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input '.$this->read_only.' id="'.$this->id.'" name="'.$this->name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" '.$min.' '.$max.' class="'.$this->addClass().'" '.$this->addValidation().'  placeholder="'.$this->field['placeholder'].'" type="number" value="'.$this->getDefaultValue().'"/>';
		
		$data .= $this->addDesc();
		$data .= '</div>';	
		return $data;
	}
	function addPhone()
	{
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input '.$this->read_only.' id="'.$this->id.'" class="'.$this->addClass().'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'"  '.$this->addValidation().' placeholder="'.((isset($this->field['placeholder']))?$this->field['placeholder']:"").'" name="'.$this->name.'" type="text" value="'.$this->getDefaultValue().'" />';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addList()
	{
		$data = "";
		$width  = 90 /  intval($this->field['cols']);
		$name = $this->name;
		
		$list_this_values = array(); //$this->getDefaultValue($name);
		
		$data .= '<div class="fieldset">'.$this->addLabel();
		$data .= '<div class="'.$this->field['css'].' pie_list_cover">';
		
		for($a = 1 ,$c=0; $a <= $this->field['rows'] ; $a++,$c++)
		{
			if($a==1)
			{
				$data .= '<div class="'.$this->id.'_'.$a.' pie_list">';
				
				
				for($b = 1 ; $b <= $this->field['cols'] ;$b++)
				{
					$data .= '<input '.$this->read_only.' data-type="list" value="'.((isset($list_this_values[$c][$b-1]))?$list_this_values[$c][$b-1]:"").'" style="width:'.$width.'%;" type="text" '.$this->addValidation().' name="'.$this->name.'['.$c.'][]" class="'.$this->addClass().' input_fields"> ';
				}
				if( ((int)$this->field['rows']) > 1)
				{
					$data .= ' <img src="'.PIEREG_PLUGIN_URL.'assets/images/plus.png" onclick="addList(this,'.$this->field['rows'].','.$this->field['id'].');" alt="add" /></div>';		
				}
				
				if( $this->field['rows'] == 1 )	$data .= '</div>';		
			}
			else
			{
				if(isset($list_this_values[$c]) != false)
					$display_list_style = (!array_filter($list_this_values[$c]))? "display:none;" : "display:block;";
				else
					$display_list_style = "display:none;";
					
				$data .= '<div style="'.$display_list_style.'" class="'.$this->id.'_'.$a.' pie_list">';
				for($b = 1 ; $b <= $this->field['cols'] ;$b++)
				{
					$data .= '<input '.$this->read_only.' data-type="list" value="'.((isset($list_this_values[$c][$b-1]))?$list_this_values[$c][$b-1]:"").'" style="width:'.$width.'%;" type="text" '.$this->addValidation().' name="'.$this->name.'['.$c.'][]" class="'.$this->addClass().' input_fields">';
				}
					$data .= ' <img src="'.PIEREG_PLUGIN_URL.'assets/images/minus.gif" onclick="removeList(this,'.$this->field['rows'].','.$this->field['id'].','.$a.');" alt="add" />';
					$data .= '</div>';
			}
			
			
		}
		
		$data .= '</div>';		
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addHTML()
	{
		$data  = '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<div class="piereg-html-field-content" >';
		$data .= html_entity_decode($this->field['html']);
		$data .= '</div>';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addSectionBreak()
	{
		$class = "";
		
		if($this->label_alignment == "left")
			$class .= "wdth-lft ";
		
		$class .= "sectionBreak";
		
		$data  = '<div class="fieldset aligncenter">';
		if($this->field['label'] != ''){
			$class .= ' break-label';
		}
			// $data .= $this->addLabel();
		$data .= '<div class="'.$class.'">';
			$data .= $this->addLabel();
		$data .= '</div>';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addCheckRadio()
	{
		$data = "";
		$data .= '<div class="fieldset">';
		$data .= $this->addLabel();
		if(sizeof($this->field['value']) > 0)
		{
			$data .= '<div class="radio_wrap">';
			$thispostedvalue = $this->getDefaultValue();
			for($a = 0 ; $a < sizeof($this->field['value']) ; $a++)
			{
				$checked = '';
				if( (isset($this->field['selected'])) && (is_array($this->field['selected']) && in_array($a,$this->field['selected'])) )
					$checked = 'checked="checked"';	
				else
					$checked = '';
				
				if(is_array($thispostedvalue)){
					foreach($thispostedvalue as $thissinglepostedval){
						if($thissinglepostedval == $this->field['value'][$a])
							$checked = 'checked="checked"';
					}
				}
				
				$dymanic_class = $this->field['type']."_".$this->field['id'];

				$data .= '<div class="radio_container">';
				$data .= '<input '.$this->read_only.' '.$checked.' value="'.$this->field['value'][$a].'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" type="'.$this->field['type'].'" name="'.$this->name.'[]" class="'.$this->addClass("input_fields").' radio_fields" '.$this->addValidation().' data-map-field-by-class="'.$dymanic_class.'" >';
				$data .= "<label>";
					$data .= $this->field['display'][$a];
				$data .= "</label>";
				$data .= '</div>';
			}
			$data .= "</div>";		
		}
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addAddress()
	{
		$address_values = $this->getDefaultValue($this->name);
		$data = "";
		$data .= '<div class="fieldset">'.$this->addLabel();
		$data .= '<div class="address_main">';
		$data .= '<div class="address">';
		$data .= '<input '.$this->read_only.' type="text" name="'.$this->name.'[address]" id="'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().' value="'.((isset($address_values['address']))?$address_values['address']:"").'">';
		$data .= '<label>'.__("Street Address","pie-register").'</label>';
		$data .= '</div>';
		
		 if(empty($this->field['hide_address2']))
		 {
			$data .= '<div class="address">';
			$data .= '<input '.$this->read_only.' type="text" name="'.$this->name.'[address2]" id="address2_'.$this->id.'"  class="'.$this->addClass().'"  '.$this->addValidation().' value="'.((isset($address_values['address2']))?$address_values['address2']:"").'">';
			$data .= '<label>'.__("Address Line 2","pie-register").'</label>';
			$data .= '</div>';
		 }
		
		$data .= '<div class="address">';
		$data .= '<div class="address2">';
		$data .= '<input '.$this->read_only.' type="text" name="'.$this->name.'[city]" id="city_'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().' value="'.((isset($address_values['city']))?$address_values['city']:"").'">';
		$data .= '<label>'.__("City","pie-register").'</label>';
		$data .= '</div>';
		
		 if(empty($this->field['hide_state']))
		 {
			 	if($this->field['address_type'] == "International")
				{
					$data .= '<div class="address2" >';
					$data .= '<input '.$this->read_only.' type="text" name="'.$this->name.'[state]" id="state_'.$this->id.'" class="'.$this->addClass().'" value="'.((isset($address_values['state']))?$address_values['state']:"").'">';
					$data .= '<label>'.__("State / Province / Region","pie-register").'</label>';
					$data .= '</div>';
				}
				else if($this->field['address_type'] == "United States")
				{
				  $us_states = get_option("pie_us_states");
				  $selectedoption = (isset($address_values['state']))?$address_values['state']:$this->field['us_default_state'];
				  $options 	= $this->createDropdown($us_states,$selectedoption);	
				 
				  $data .= '<div class="address2" >';
					$data .= '<select '.$this->read_only.' id="state_'.$this->id.'" name="'.$this->name.'[state]" class="'.$this->addClass("").'">';
					$data .= $options;
					$data .= '</select>';
					$data .= '<label>'.__("State","pie-register").'</label>';
				  $data .= '</div>';
				}
				else if($this->field['address_type'] == "Canada")
				{
					
					$can_states = get_option("pie_can_states");
					$selectedoption = (isset($address_values['state']))?$address_values['state']:$this->field['canada_default_state'];
				  	$options 	= $this->createDropdown($can_states,$selectedoption);
					$data .= '<div class="address2" >';
						$data .= '<select '.$this->read_only.' id="state_'.$this->id.'" class="'.$this->addClass("").'" name="'.$this->name.'[state]">';
						$data .= $options;
						$data .= '</select>';
						$data .= '<label>'.__("Province","pie-register").'</label>';
					$data .= '</div>';
				}
		}
		$data .= '</div>';
		$data .= '<div class="address">';	
		$data .= ' <div class="address2">';
		$data .= '<input '.$this->read_only.' id="zip_'.$this->id.'" name="'.$this->name.'[zip]" type="text" class="'.$this->addClass().'"  '.$this->addValidation().' value="'.((isset($address_values['zip']))?$address_values['zip']:"").'">';
		$data .= '<label>'.__("Zip / Postal Code","pie-register").'</label>';
		$data .= '</div>';	 
		
		
		 if($this->field['address_type'] == "International")
		 {
			 $countries = get_option("pie_countries");
			 $selectedoption = (isset($address_values['country']) && $address_values['country'])?$address_values['country']:$this->field['default_country'];		 
			 $options 	= $this->createDropdown($countries,$selectedoption);  
			 $data .= '<div  class="address2" >';
				$data .= '<select '.$this->read_only.' id="country_'.$this->id.'" name="'.$this->name.'[country]" class="'.$this->addClass().'" '.$this->addValidation().'>';
						$data .= $options;
					$data .= '</select>';
				$data .= '<label>'.__("Country","pie-register").'</label>';
		  	$data .= '</div>';
		 }
		 
		 
		$data .= '</div>';
		$data .= '</div>';
		$data .= $this->addDesc();
		$data .= '</div>';

		if($this->piereg_field_visbility_addon_active){
			$this->readibility = apply_filters("pie_add_hidden_field_addon", $this->read_only);
		}

		if($this->readibility){
			$this->read_only = "";
			$data .= '<div class="control_visibility">';
			$data .=  $this->addAddress();
			$data .=  '</div>';
		}
		return $data;
	}	
	//pie-register-woocommerce adoon 
	function addWooCommerceBillingAddress()
	{
		if ($this->woocommerce_and_piereg_wc_addon_active)
		{
			$wc_billing_address_values = $this->getDefaultValue($this->name);
			
			// country_options
			global $woocommerce;
			$countries_obj   	= new WC_Countries();
			$countries_list  	= $countries_obj->get_allowed_countries();
			$countries			= array();
			foreach($countries_list as $iso_code => $country_name)
			{
				$countries[] = array('iso_code' => $iso_code, 'name' => $country_name);
			}
			$default_country 	= $countries_obj->get_base_country();
			$selectedoption 	= (isset($wc_billing_address_values['country']) && $wc_billing_address_values['country']) ? $wc_billing_address_values['country'] : (isset($default_country) ? $default_country : "" );		 
			$country_options 	= $this->createCountryDropdown($countries,$selectedoption);
            // states_options
            $states_list 		= $countries_obj->get_states( $default_country );
            $states				= array();
            if ($states_list) 
            {
	            foreach($states_list as $iso_code => $state_name)
	            {
	        	$states[] = array('iso_code' => $iso_code, 'name' => $state_name);
				}
            }
			$selectedoption 	= (isset($wc_billing_address_values['state']))?$wc_billing_address_values['state']:"";
			$state_options 		= $this->createStatesDropdown($states,$selectedoption);

			$arguments = array(
				'field' 						=> $this->field, 
				'id' 							=> $this->id, 
				'user'							=> (isset($this->user) ? $this->user : false), 
				'slug'							=> (isset($this->slug) ? $this->slug : false), 
				'name'							=> $this->name, 
				'addLabel'						=> $this->addLabel(), 
				'defaultLabel'					=> '<label class="" for="wc_billing_address_'.$this->id.'">Billing Address</label>',
				'addClass'						=> $this->addClass(), 
				'addValidation'					=> $this->addValidation(), 
				'addDesc'						=> $this->addDesc(), 
				'wc_billing_address_values' 	=> $this->getDefaultValue($this->name), 
				'country_options'				=> $country_options, 
				'state_options'					=> $state_options, 
				'hidden_fields'					=> explode(",", $this->field['hidden_fields']),
				'required_fields'				=> explode(",", $this->field['required_fields'])	
			);

			return apply_filters("pieregister_print_woocommerce_billing_address_front_template", $arguments); 
		}
	}

	function addWooCommerceShippingAddress()
	{
		if ($this->woocommerce_and_piereg_wc_addon_active)
		{
			$wc_shipping_address_values = $this->getDefaultValue($this->name);
				
			// country_options
			global $woocommerce;
			$countries_obj   	= new WC_Countries();
			$countries_list  	= $countries_obj->get_allowed_countries();
			$countries			= array();
			foreach($countries_list as $iso_code => $country_name)
			{
				$countries[] = array('iso_code' => $iso_code, 'name' => $country_name);
			}
			$default_country 	= $countries_obj->get_base_country();
			$selectedoption 	= ( isset($wc_shipping_address_values['country']) && $wc_shipping_address_values['country'] ) ? $wc_shipping_address_values['country'] : (isset($default_country) ? $default_country : "" ); 
			$country_options 	= $this->createCountryDropdown($countries,$selectedoption);  

			// states_options
			$states_list 		= $countries_obj->get_states( $default_country );
			$states				= array();
			if ($states_list) 
			{
				foreach($states_list as $iso_code => $state_name)
				{
					$states[] = array('iso_code' => $iso_code, 'name' => $state_name);
				}
			}
			$selectedoption 	= (isset($wc_shipping_address_values['state']))?$wc_shipping_address_values['state']:"";
			$state_options 		= $this->createStatesDropdown($states,$selectedoption);
			
			$arguments = array(
				'field' 						=> $this->field, 
				'id' 							=> $this->id, 
				'user'							=> (isset($this->user) ? $this->user : false), 
				'slug'							=> (isset($this->slug) ? $this->slug : false), 
				'name'							=> $this->name, 
				'addLabel'						=> $this->addLabel(), 
				'defaultLabel'					=> '<label class="" for="wc_shipping_address_'.$this->id.'">Shipping Address</label>',
				'addClass'						=> $this->addClass(), 
				'addValidation'					=> $this->addValidation(), 
				'addDesc'						=> $this->addDesc(), 
				'wc_shipping_address_values' 	=> $this->getDefaultValue($this->name), 
				'country_options'				=> $country_options, 
				'state_options'					=> $state_options,
				'hidden_fields'					=> explode(",", $this->field['hidden_fields']),
				'required_fields'				=> explode(",", $this->field['required_fields'])	
			);

			return apply_filters("pieregister_print_woocommerce_shipping_address_front_template", $arguments);
		}
	}	
	function addDate()
	{
		$data = "";
		$data .= '<div class="fieldset">';
		$data .= $this->addLabel();
		$date_this_values = $this->getDefaultValue($this->name);
		
		if($this->field['date_type'] == "datefield")
		{
			
			if($this->field['date_format']=="mm/dd/yy")
			{
				$data .= '<div class="piereg_time date_format_field">';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().' value="'.((isset($date_this_values['date']['mm']))?$date_this_values['date']['mm']:"").'">';
                        $data .= '<label>'.__("MM","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'" '.$this->addValidation().' value="'.((isset($date_this_values['date']['dd']))?$date_this_values['date']['dd']: "").'">';
                        $data .= '<label>'.__("DD","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'" '.$this->addValidation().' value="'.((isset($date_this_values['date']['yy']))?$date_this_values['date']['yy']:"").'">';
                        $data .= '<label>'.__("YYYY","pie-register").'</label>';
                    $data .= '</div>';
				$data .= '</div>';
			} 
			else if($this->field['date_format']=="yy/mm/dd" || $this->field['date_format']=="yy.mm.dd")
			{
				$data .= '<div class="piereg_time date_format_field">';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'" value="'.((isset($date_this_values['date']['yy']))?$date_this_values['date']['yy']:"").'">';
                        $data .= '<label>'.__("YYYY","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().' value="'.((isset($date_this_values['date']['mm']))?$date_this_values['date']['mm']:"").'">';
                        $data .= '<label>'.__("MM","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'" value="'.((isset($date_this_values['date']['dd']))?$date_this_values['date']['dd']:"").'">';
                        $data .= '<label>'.__("DD","pie-register").'</label>';
                    $data .= '</div>';
				$data .= '</div>';
			}
			else if($this->field['date_format']=="dd/mm/yy" || $this->field['date_format']=="dd-mm-yy" || $this->field['date_format']=="dd.mm.yy")
			{
                $data .= '<div class="piereg_time date_format_field">';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'" value="'.((isset($date_this_values['date']['dd']))?$date_this_values['date']['dd']:"").'">';
                        $data .= '<label>'.__("DD","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().' value="'.((isset($date_this_values['date']['mm']))?$date_this_values['date']['mm']:"").'">';
                        $data .= '<label>'.__("MM","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'" value="'.((isset($date_this_values['date']['yy']))?$date_this_values['date']['yy']:"").'">';
                        $data .= '<label>'.__("YYYY","pie-register").'</label>';
                    $data .= '</div>';
                $data .= '</div>';
			}
			else
			{
                $data .= '<div class="piereg_time date_format_field">';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'" value="'.((isset($date_this_values['date']['dd']))?$date_this_values['date']['dd']:"").'">';
                        $data .= '<label>'.__("DD","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'" value="'.((isset($date_this_values['date']['yy']))?$date_this_values['date']['yy']:"").'">';
                        $data .= '<label>'.__("YYYY","pie-register").'</label>';
                    $data .= '</div>';
                    $data .= '<div class="time_fields">';
                        $data .= '<input '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'" value="'.((isset($date_this_values['date']['mm']))?$date_this_values['date']['mm']:"").'">';
                        $data .= '<label>'.__("MM","pie-register").'</label>';
                    $data .= '</div>';
                $data .= '</div>';
			}
		}
		else if($this->field['date_type'] == "datepicker")
		{
				if( $this->field['calendar_icon'] == "calendar" || $this->field['calendar_icon'] == "custom" ) 
				  $data .= '<div class="piereg_time date_format_field date_with_icon">';
				else 
				  $data .= '<div class="piereg_time date_format_field">';
				
				  $data .= '<input '.$this->read_only.' id="'.$this->id.'" name="'.$this->name.'[date][]" type="text" class="'.$this->addClass().' date_start" title="'.$this->field['date_format'].'" value="';
				  
				$data .= ( (isset($date_this_values['date'][0]) && !empty($date_this_values['date'][0])) ? $date_this_values['date'][0] : "" );
				$data .= '" />';
				$data .= '<input id="'.$this->id.'_format" type="hidden"  value="'.((isset($this->field['date_format'])) ? $this->field['date_format'] : "").'">';
				$data .= '<input id="'.$this->id.'_firstday" type="hidden"  value="'.((isset($this->field['firstday'])) ? $this->field['firstday'] : "").'">';
				$data .= '<input id="'.$this->id.'_startdate" type="hidden"  value="'.((isset($this->field['startdate'])) ? $this->field['startdate'] : "").'">';
				  
				if($this->field['calendar_icon'] == "calendar")
				{
					 $data .=  '<img id="'.$this->id.'_icon" class="calendar_icon" src="'.PIEREG_PLUGIN_URL.'assets/images/calendar.png" />';
				}
				else if($this->field['calendar_icon'] == "custom")
				{
					 $data .=  '<img id="'.$this->id.'_icon" class="calendar_icon" src="'.$this->field['calendar_icon_url'].'" />'; 
				}
				  
				 $data .= '</div>';	
		}
		else if($this->field['date_type'] == "datedropdown")
		{
				
			if($this->field['date_format']=="mm/dd/yy")
			{
			
					$data .= '<div class="piereg_time date_format_field">';
					  $data .= '<div class="time_fields">';
						$data .= '<select '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'>';
						  $data .= '<option value="">'.__("Month","pie-register").'</option>';
						  for($a=1;$a<=12;$a++){
							  if(isset($date_this_values['date']['mm']) && $date_this_values['date']['mm'] == $a)
								$sel = ' selected=""';
							  else
							  $sel = '';	
							  $data .= '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>';
						  }
						  $data .= '</select>';
					  $data .= '</div>';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("input_fields",array("custom[day]")).'" '.$this->addValidation().'>';
					  $data .= '<option value="">'.__("Day","pie-register").'</option>';
					  for($a=1;$a<=31;$a++){
						  if(isset($date_this_values['date']['dd']) && $date_this_values['date']['dd'] == $a)
						  	$sel = ' selected=""';
						  else
						  $sel = '';	
						  $data .= '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>';
					  }
					  $data .= '</select>';
				  $data .= '</div>';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("input_fields",array("custom[year]")).'" '.$this->addValidation().'>';
					  $data .= '<option value="">'.__("Year","pie-register").'</option>';
					  for($a=((int)date("Y") + 10);$a>=(((int)date("Y"))-100);$a--){
						  if(isset($date_this_values['date']['yy']) && $date_this_values['date']['yy'] == $a)
						  	$sel = ' selected=""';
						  else
						  	$sel = '';	
						  $data .= '<option value="'.$a.'" '.$sel.'>'.__($a,"pie-register").'</option>';
					  }
					  $data .= '</select>';
				  $data .= '</div>';
				$data .= '</div>';
			}
			else if($this->field['date_format']=="yy/mm/dd" || $this->field['date_format']=="yy.mm.dd")
			{
					$data .= '<div class="piereg_time date_format_field">';
					 $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("input_fields",array("custom[year]")).'">';
					  $data .= '<option value="">'.__("Year","pie-register").'</option>';
					  for($a=((int)date("Y") + 10);$a>=(((int)date("Y"))-100);$a--){
						  if(isset($date_this_values['date']['yy']) && $date_this_values['date']['yy'] == $a)
						  	$sel = ' selected=""';
						  else
						  $sel = '';	
						  $data .=  '<option value="'.$a.'" '.$sel.'>'.__($a,"pie-register").'</option>';
					  }
					  $data .=  '</select>';
				  $data .= '</div>';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'>';
					  $data .= '<option value="">'.__("Month","pie-register").'</option>';
					  for($a=1;$a<=12;$a++){
						  if(isset($date_this_values['date']['mm']) && $date_this_values['date']['mm'] == $a)
						  	$sel = ' selected=""';
						  else
						  $sel = '';	
						  $data .=  '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>';
					  }
					  $data .=  '</select>';
				  $data .= '</div>';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("input_fields",array("custom[day]")).'">';
					  $data .= '<option value="">'.__("Day","pie-register").'</option>';
					  for($a=1;$a<=31;$a++){
						  if(isset($date_this_values['date']['dd']) && $date_this_values['date']['dd'] == $a)
						  	$sel = ' selected=""';
						  else
						  $sel = '';	
						  $data .=  '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>';
					  }
					  $data .= '</select>';
				  $data .= '</div>';
				$data .= '</div>';
			}
			else
			{
				$data .= '<div class="piereg_time date_format_field">';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("input_fields",array("custom[day]")).'">';
					  $data .= '<option value="">'.__("Day","pie-register").'</option>';
					  for($a=1;$a<=31;$a++){
						  if(isset($date_this_values['date']['dd']) && $date_this_values['date']['dd'] == $a)
						  	$sel = ' selected=""';
						  else
							  $sel = '';	
						  $data .=  '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>';
					  }
					  $data .= '</select>';
				  $data .= '</div>';
				  $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'>';
					  $data .= '<option value="">'.__("Month","pie-register").'</option>';
					  for($a=1;$a<=12;$a++){
						  if(isset($date_this_values['date']['mm']) && $date_this_values['date']['mm'] == $a)
						  	$sel = ' selected=""';
						  else
							  $sel = '';
						  $data .=  '<option value="'.str_pad($a, 2, "0", STR_PAD_LEFT).'" '.$sel.'>'.str_pad(__($a,"pie-register"), 2, "0", STR_PAD_LEFT).'</option>'; 
					  }
					  $data .=  '</select>';
				  $data .= '</div>';
				  	 $data .= '<div class="time_fields">';
					$data .= '<select '.$this->read_only.' id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("input_fields",array("custom[year]")).'">';
					  $data .= '<option value="">'.__("Year","pie-register").'</option>';
					  for($a=((int)date("Y") + 10);$a>=(((int)date("Y"))-100);$a--){
						  if(isset($date_this_values['date']['yy']) && $date_this_values['date']['yy'] == $a)
						  	$sel = ' selected=""';
						  else
							  $sel = '';	
						  $data .=  '<option value="'.$a.'" '.$sel.'>'.__($a,"pie-register").'</option>';
					  }
					  $data .=  '</select>';
				  $data .= '</div>';
				$data .= '</div>';
			}	
			
			if($this->piereg_field_visbility_addon_active){
				$this->readibility = apply_filters("pie_add_hidden_field_addon", $this->read_only);
			}

			if($this->readibility){
				$this->read_only = "";
				$data .= '<div class="control_visibility">';
				$data .=  $this->addDate();
				$data .=  '</div>';
			}
		}
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addInvitationField(){
		
		// invite_through_email
		$data = "";
		$type = "text";

		$data .= '<div class="fieldset">';
		$data .= $this->addLabel();
		$data .= '<input id="'.$this->id.'" '.$this->addValidation().' name="invitation" class="'.$this->addClass().'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" placeholder="'.$this->field['placeholder'].'" type="'.$type.'" value="" />';
		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addTermsField(){
		$data  = '<div class="fieldset piereg-wrap-terms">';
		$data .= '<label for="terms_'.$this->id.'">';
		$dymanic_class = $this->field['type']."_".$this->field['id'];
		$selected = (!empty($this->getDefaultValue($this->name)) && $this->getDefaultValue($this->name) == '1') ? "checked='checked'": "";
		$data .= '<input '.$selected.' id="terms_'.$this->id.'" name="'.$this->name.'" class="'.$this->addClass().'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" type="checkbox" value="1" data-map-field-by-class="'.$dymanic_class.'" data-errormessage-value-missing="'.$this->field['validation_message'].'" />';
		$page_id	= $this->field['cont'];
		$page_url	= get_the_permalink($page_id);		
		if( !empty($this->field['label']) )
		{
			$data .= $this->field['label'];
			$data .= (substr_compare($this->field['label'], '.', -strlen('.')) === 0) ? '' : '. ';		
		}		
		$data  .= apply_filters('piereg_terms_field_text',sprintf(__('Click <a target="_blank" href="%s">here</a> to view.','pie-register'),$page_url));		
		$data .= '</label></div>';
		return $data;
	}
	function addCaptcha($id)
	{
		$data  = "";
		$class = "";
		$settings  	= get_option(OPTION_PIE_REGISTER);
		$recaptcha_type	= $settings['piereg_recaptcha_type'] ;
		$publickey_v3	= $settings['captcha_publc_v3'];
		$publickey	    = $settings['captcha_publc'];
		
		if($recaptcha_type == "v3" && $publickey_v3)
			$class = ' piereg-recaptcha-v3';

		$data .= '<div class="fieldset'.$class.'">';

		if($recaptcha_type == "v3" && $publickey_v3){
			$data .= '<div class="input_fields piereg_recaptcha_reg_div_v3" >';
				$data .= '<input type="hidden" name="g-recaptcha-response" id="reg_form_'.$id.'" value="">';
			$data .= '</div>';
		}
		elseif($publickey){
			$data .= $this->addLabel();
			$data .= '<div class="input_fields piereg_recaptcha_reg_div" id="reg_form_'.$id.'">';
				// When Form is added through Gutenberg/WPBakery - Captcha is not rendered on the backend
				if( (isset($_GET['context']) && $_GET['context'] == 'edit') || (isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true'))
					$data .= __("Actual captcha will be displayed on the front-end","pie-register").'.';
			$data .= '</div>';
		}
		else {
			$data .= '<div class="input_fields pie_captcha_err" id="reg_form_'.$id.'">';
				$data .= __("Captcha is not configured correctly","pie-register").'.';
			$data .= '</div>';
		}

		$data .= $this->addDesc();
		$data .= '</div>';
		return $data;
	}
	function addMath_Captcha($piereg_widget = false){
		if( $piereg_widget ){
			$cookie = 'registration_widget';
		}else{
			$cookie = 'registration';
		}
		$data = '<div class="fieldset">'.$this->addLabel();
		$operator = rand(0,1);
		////1 for add(+)
		////0 for subtract(-)
		$data = "";
		$field_id = "";
		if($piereg_widget == true){
			$data .= '<div data-cookiename="'.$cookie.'" class="piereg_math_captcha prMathCaptcha">';
			$data .= '<div class="wrapmathcaptcha prMathCaptcha">';
			$data .= '<div id="pieregister_math_captha_widget" class="piereg_math_captcha"></div>';
			$data .= '<input id="'.$this->id.'" type="text" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" class="'.$this->addClass().'" placeholder="'.$this->field['placeholder'].'" style="width:50%;margin-top:9px;" name="piereg_math_captcha_widget"/>';
			$field_id = "#pieregister_math_captha_widget";
		}
		else{
			$data .= '<div data-cookiename="'.$cookie.'" class="wrapmathcaptcha prMathCaptcha">';
			$data .= '<div id="pieregister_math_captha" class="piereg_math_captcha"></div>';
			$data .= '<input id="'.$this->id.'" type="text" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" class="'.$this->addClass().'" placeholder="'.$this->field['placeholder'].'" style="width:50%;margin-top:9px;" name="piereg_math_captcha"/>';
			$data .= '</div>';
			
			$field_id = "#pieregister_math_captha";
		}
		$data .= $this->addDesc();
		return $data;
	}
	
	function addSubmit($options = array())
	{
		$data = "";
		$data .= '<div class="fieldset">';
		$data .= '<div class="pie_wrap_buttons">';
		
		if($this->pages > 1)
		{
			if( $this->pageBreak_prev_type == 'url' ) {
				$data .= '<img class="pie_prev" name="pie_prev" id="pie_prev_'.$this->pages.'" src="'.$this->pageBreak_prev_label.'"  />';				
			} else{
				if($this->pageBreak_prev_label == '')
					$this->pageBreak_prev_label = "Previous";
					
				$data .= '<input class="pie_prev" name="pie_prev" id="pie_prev_'.$this->pages.'" type="button" value="'.__($this->pageBreak_prev_label,"pie-register").'" />';
			}			
			$data .= '<input id="pie_prev_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages-1).'" />';						
		}
		
		$data .= '<input name="pie_submit" class="pie_submit" type="submit" value="'.__($this->field['text'],"pie-register").'" />';		// stripe_changes	
		
		if($this->field['reset']==1)
		{
			$data .= '<input name="pie_reset" type="reset" value="'.__($this->field['reset_text'],"pie-register").'" />';
		}
		
		$data .= $this->addDesc();
		$data .= '</div>';
		$data .= '</div>';
		return $data;
	}
	
	function addPaypal()
	{
		return '<input name="pie_submit" value="paypal" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" />';	
	}
	
	function addPagebreak($fromwidget = false)
	{
		$data = "";
		$cl = "";
		if($fromwidget)
			$cl = 'piewid_';
		
		
		$data .= '<div class="fieldset">'.$this->addLabel();
		
		$data .= '<input id="'.$cl.'total_pages" class="piereg_regform_total_pages" name="pie_total_pages" type="hidden" value="'.$this->countPageBreaks().'" />';
		
		if($this->pageBreak_prev_label == ''){
			if($this->field['prev_button']=="text"){
				$this->pageBreak_prev_label = $this->field['prev_button_text'];
			} else if($this->field['prev_button']=="url") {
				$this->pageBreak_prev_label = $this->field['prev_button_url'];
			}			
		}
		
		if( $this->pageBreak_prev_type == '')
			$this->pageBreak_prev_type = $this->field['prev_button'];
		
		if($this->pages > 1){
			
			$data .= '<input id="'.$cl.'pie_prev_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages-1).'" />';		
			
			if($this->pageBreak_prev_type == "text")
			{
				//$data .= '<input class="pie_prev" name="pie_prev" id="'.$cl.'pie_prev_'.$this->pages.'" type="button" value="'.__($this->field['prev_button_text'],"pie-register").'" />';
				$data .= '<input class="pie_prev" name="pie_prev" id="'.$cl.'pie_prev_'.$this->pages.'" type="button" value="'.__($this->pageBreak_prev_label,"pie-register").'" />';	
			}
			else if($this->pageBreak_prev_type == "url")
			{
				//$data .= '<img class="pie_prev" name="pie_prev" id="'.$cl.'pie_prev_'.$this->pages.'" src="'.$this->field['prev_button_url'].'"  />';
				$data .= '<img class="pie_prev" name="pie_prev" id="'.$cl.'pie_prev_'.$this->pages.'" src="'.$this->pageBreak_prev_label.'"  />';		
			}
			
			if($this->field['prev_button']=="text"){
				$this->pageBreak_prev_label = $this->field['prev_button_text'];
			} else if($this->field['prev_button']=="url") {
				$this->pageBreak_prev_label = $this->field['prev_button_url'];
			}
			
			$this->pageBreak_prev_type = $this->field['prev_button'];
			
		}
		
		
		$data .= '<input id="'.$cl.'pie_next_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages+1).'" />';	
		if($this->field['next_button']=="text")
		{
			$data .= '<input class="'.$cl.'pie_next" name="pie_next" id="'.$cl.'pie_next_'.$this->pages.'" type="button" value="'.__($this->field['next_button_text'],"pie-register").'" />';
		}
		else if($this->field['next_button']=="url")
		{
			$data .= '<img style="cursor:pointer;" src="'.$this->field['next_button_url'].'" class="'.$cl.'pie_next" name="pie_next" id="'.$cl.'pie_next_'.$this->pages.'" />';	
		}
		$data .= $this->addDesc();
		$data .= '</div>';
		
		return $data;	
	}
	
	function countPageBreaks()
	{
		$pages = 1;
		if( isset($this->data) && !empty($this->data) && is_array($this->data) ){
			foreach($this->data as $field)
			{
				if($field['type']=="pagebreak")
					$pages++;	
			}
		}
		return $pages ;
	}
	
	function check_readability(){
		if($this->piereg_field_visbility_addon_active){
			if( isset($this->field['enable_read_only']) && $this->field['enable_read_only'] != "disabled"){
				$this->read_only = apply_filters('pie_addon_readibility', $this->read_only, $this->field, 'registration');
			}		
			return $this->read_only;
		}
	}
	function printFields($fromwidget = false,$form_id="default",$title="false",$description="false")
	{
		if(!isset( $this->data['form']['label_alignment'] )) $this->data['form']['label_alignment'] = "";
		
		if($fromwidget == true)
			$this->is_pr_widget = true;
		else
			$this->is_pr_widget = false;
		
		if($form_id == "default" || $form_id == "0")
		{
			$id = "default";
			$this->data = $this->getCurrentFields();
			$this->label_alignment = isset($this->data['form']['label_alignment']) ? $this->data['form']['label_alignment'] : "";	
			$this->pages = 1;
		}
		else
		{
			$id = intval($form_id);
			$this->data = $this->getCurrentFields($id);
			$this->label_alignment = isset($this->data['form']['label_alignment']) ? $this->data['form']['label_alignment'] : "";
			$this->pages = 1;
		}
		
		$pie_reg_fields = "";
		$update = get_option(OPTION_PIE_REGISTER);
		$pie_reg_fields .= $this->addFormData($title,$description);
		$pie_reg_fields .= '<ul id="pie_register">';

		if(is_array($this->data)){
			$form_label = isset($this->data['form']['label']) ? $this->data['form']['label'] : '';
			foreach($this->data as $this->field)
			{
				if($this->field['type']=="")
				{
					continue;
				}
				
				if( $this->field['type'] == "two_way_login_phone" ) {
					$twilio_options = get_option('pie_register_twilio');
					if( isset($twilio_options["enable_twilio"]) && $twilio_options["enable_twilio"] == "0" ){
						continue;
					}
				}
				
				if( $this->field['type']=="invitation" && $update["enable_invitation_codes"]=="0" )
				{
					continue;
				}
				
				if( $this->field['type'] == "honeypot" ) {	
					continue;
				}	
				
				if($this->field['type'] == "form"){
					$pie_reg_fields .= '<input type="hidden" value="'.$id.'" name="form_id" />';
					continue;
				}
	
				$this->name 	= $this->createFieldName($this->field['type']."_".((isset($this->field['id']))?$this->field['id']:""));
				$this->id 		= $this->name;
				$this->no		= ( isset($this->field['id']) ? $this->field['id'] : "" );

				$this->read_only         = "";
				$this->not_visible       = false;
				$this->readibility       = false;

				if($this->piereg_field_visbility_addon_active){
					if(isset($this->field['show_on']) && !empty($this->field['show_on'])){
						$this->not_visible = apply_filters('pie_visibility_on_reg', $this->field, $this->not_visible);
					}
					if($this->not_visible){
						$pie_reg_fields .= '<input id="'.$this->id.'" name="'.$this->name.'" data-field_id="'.$this->get_pr_widget_prefix().'piereg_field_'.$this->no.'" type="hidden" value="" />';
						continue;
					}	
				}
	
				//We don't need to print li for hidden field
				if($this->field['type'] == "hidden")
				{
					$pie_reg_fields .= $this->addHiddenField($form_label);
					continue;
				}
				
				
				$topclass = "";
				if($this->label_alignment=="top")
					$topclass = " label_top";
				
				
				$_parent = isset($this->field['css']) ? $this->field['css'] : "";
				if( !empty( $_parent ) )
				{
					$_parent = 'parent_' . $_parent;
				}
				
				$pie_reg_fields .= '<li class="fields '.$_parent.$topclass.'  pageFields_'.$this->pages.' '.$this->get_pr_widget_prefix().'piereg_li_'.(isset($this->field['id'])?$this->field['id']:"").'" >';
				
				if($this->field['type'] == "pagebreak")
				{
					$pie_reg_fields .= $this->addPagebreak($fromwidget);	
					$this->pages++;			
				}
				//Printting Field
				switch($this->field['type']){
					case 'text' :								
					case 'website' :
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addTextField();
					break;				
					case 'username' :
						$pie_reg_fields .= $this->addUsername($fromwidget);
					break;
					case 'password' :
						$pie_reg_fields .= $this->addPassword($fromwidget);
					break;
					case 'email' :
						$pie_reg_fields .= $this->addEmail($fromwidget);
					break;
					case 'textarea':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addTextArea();
					break;
					case 'dropdown':
					case 'multiselect':
					case 'custom_role':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addDropdown();
					break;
					case 'number':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addNumberField();			
					break;
					case 'radio':
					case 'checkbox':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addCheckRadio();
					break;
					case 'html':
						$pie_reg_fields .= $this->addHTML();
					break;
					case 'name':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addName();
					break;
					case 'time':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addTime();
					break;
					case 'upload':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addUpload();
					break;
					case 'profile_pic':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addProfilePicUpload();
					break;
					case 'address':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addAddress();
					break;
					//pie-register-woocommerce addon
					case 'wc_billing_address':
						$pie_reg_fields .= $this->addWooCommerceBillingAddress();
					break;
					case 'wc_shipping_address':
						$pie_reg_fields .= $this->addWooCommerceShippingAddress();
					break;
					case 'captcha':
						$pie_reg_fields .= $this->addCaptcha($id);
					break;
					case 'math_captcha':
						global $piereg_math_captcha_register,$piereg_math_captcha_register_widget;
						if($piereg_math_captcha_register != true && $fromwidget == false){
							$pie_reg_fields .= '<div class="fieldset">'.($this->addLabel());
							$pie_reg_fields .= $this->addMath_Captcha($fromwidget);
							$pie_reg_fields .= '</div>';
							$piereg_math_captcha_register = true;
						}elseif($piereg_math_captcha_register_widget != true && $fromwidget == true){
							$pie_reg_fields .= '<div class="fieldset">'.($this->addLabel());
							$pie_reg_fields .= $this->addMath_Captcha($fromwidget);
							$pie_reg_fields .= '</div>';
							$piereg_math_captcha_register_widget = true;
						}
					break;
					case 'phone':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addPhone();
					break;
					case 'two_way_login_phone':
						if( is_plugin_active('pie-register-twilio/pie-register-twilio.php') && get_option('piereg_api_manager_addon_Twilio_activated') == "Activated") {
							$this->name = "piereg_two_way_login_phone";
							$this->read_only = $this->check_readability();
							$pie_reg_fields .= $this->addPhone();
						}
					break;
					case 'date':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addDate();			
					break;
					case 'list':
						$this->read_only = $this->check_readability();
						$pie_reg_fields .= $this->addList();
					break;
					case 'pricing':
						$pie_reg_fields .= $this->addPricing();
					break;
					case 'sectionbreak':
						$pie_reg_fields .= $this->addSectionBreak();
					break;	
					case 'default':
						$pie_reg_fields .= $this->addDefaultField();
					break;
					case 'terms':
						$pie_reg_fields .= $this->addTermsField();
					break;
					case 'invitation':
						$pie_reg_fields .= $this->addInvitationField();
					break;
					case 'submit':
						// mailchimp related code within PR
						if (is_plugin_active('pie-register-mailchimp/pie-register-mailchimp.php')  ) {
                            $pie_reg_fields .= apply_filters("pieregister_print_subscription_checkbox", $form_id);
                        }
						$pie_reg_fields .= $this->addSubmit($update);
					break;					
				}
						
				if($this->field['type'] == "password" )
				{
					$widget = (isset($fromwidget) && $fromwidget == true)? '_widget' : '';
					$pie_reg_fields .= '<input class="prMinimumPasswordStrengthlength" type="hidden" id="password_strength_meter_'.$id.'" data-id="'.$id.'" value="'.((isset($this->field['restrict_strength']))?intval($this->field['restrict_strength']):0).'" />';
					//Weak Password	
					$strength_message = ((isset($this->field['strength_message']) && !empty($this->field['strength_message']))?__($this->field['strength_message'],"pie-register"):__("Weak Password","pie-register"));
                    $pie_reg_fields .= '<span class="prMinimumPasswordStrengthMessage" id="password_strength_message_'.$id.'" style="display:none;">'.$strength_message.'</span>';
				}
	
				$pie_reg_fields .=  '</li>';
				if($this->field['type'] == "password" && $this->field['show_meter']==1)
				{
					$topclass = "";
					if($this->label_alignment=="top")
						$topclass = "label_top";
						
					$pie_reg_fields .=  '<li class="fields pageFields_'.$this->pages.' '.$topclass.' '.$this->get_pr_widget_prefix().'piereg_li_'.$this->field['id'].'">';
					//NEW PASSWORD STRENGHT METER
					$widget = (isset($fromwidget) && $fromwidget == true)? '_widget' : '';
					$widget_style = (isset($fromwidget) && $fromwidget == true)? 'display: none;' : 'visibility: hidden;';
					$pie_reg_fields .=  '<div id="password_meter" class="fieldset">';
					$pie_reg_fields .=  '<label style="'.$widget_style.'">'.__("Password not entered","pie-register").'</label>';
					$pie_reg_fields .=  '<div id="piereg_passwordStrength'.$widget.'" class="piereg_pass prPasswordStrengthMeter" >'.__($update['pass_strength_indicator_label'],"pie-register").'</div>';
					$pie_reg_fields .=  '</div>';
					$pie_reg_fields .=  '</li>';

				}
			}
		}
		$pie_reg_fields .= '</ul>';
		return $pie_reg_fields;
	}
	function get_pr_widget_prefix(){
		if($this->is_pr_widget == true)
			return "widget_";
			
		return "";
	}
}