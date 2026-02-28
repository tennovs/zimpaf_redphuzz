<?php
if( file_exists( dirname(__FILE__) . '/base.php') ) 
	require_once('base.php');
class Profile_admin extends PieReg_Base
{
    var $field;
    var $user_id;
    var $slug;
    var $type;
    var $name;
    var $id;
    var $data;

    function __construct($form_id = "default")
    {
		parent::__construct();
		$this->data = $this->getCurrentFields($form_id);
		$this->default_fields = FALSE;
		add_action("init", array($this,"get_user_registered_form"));
		add_action( 'user_edit_form_tag', array($this,"piereg_wp_admin_form_tag") );
    }
	function get_user_registered_form(){
		if(is_user_logged_in()){
			$form_id = get_user_meta(get_current_user_id(),"user_registered_form_id",true);
			if( is_numeric($form_id) && !empty($form_id) )
				$this->data = $this->getCurrentFields($form_id);
		}
	}
    function addTextField($disabled)
    {
       	$val = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<input'.esc_attr($disabled).' id="' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '" class="' . esc_attr($this->field['css']) . '"  placeholder="' . esc_attr($this->field['placeholder']) . '" type="text" value="' . esc_attr($val) . '" />';
    }

    function addTextArea($disabled)
    {
        $val = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<textarea'.esc_attr($disabled).' id="' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '" rows="' . esc_attr($this->field['rows']) . '" cols="' . esc_attr($this->field['cols']) . '"  class="' . esc_attr($this->field['css']) . '"  placeholder="' . esc_attr($this->field['placeholder']) . '">' . esc_textarea($val) . '</textarea>';
    }

    function addDropdown($disabled)
    {
        $sel_options = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
        $multiple    = "";
        if ($this->type == "multiselect") {
				$multiple = 'multiple';
				$this->slug .= "[]";
			echo '<select' . esc_attr($disabled) . ' ' . $multiple . ' id="' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '" class="' . esc_attr($this->field['css']) . '" style="min-width:200px;" >';
			if (sizeof($this->field['value']) > 0) {
				for ($a = 0; $a < sizeof($this->field['value']); $a++) {
					$selected = '';
					if(isset($this->field['value'][$a]) && !empty($this->field['value'][$a]))
					{
						$sel_options = (isset($sel_options[0]) && is_array($sel_options[0])) ? $sel_options[0] : $sel_options;
						if (in_array($this->field['value'][$a], $sel_options)){
							$selected = 'selected';
						}
					}
					if ($this->field['value'][$a] != "" || $this->field['display'][$a] != "")
						echo '<option ' . esc_attr($selected) . ' value="' . esc_attr($this->field['value'][$a]) . '">' . esc_html($this->field['display'][$a]) . '</option>';
				}
			}
			echo '</select>';
		}elseif ($this->type == "dropdown"){
			$sel_options = (isset($sel_options[0]) && is_array($sel_options[0])) ? $sel_options[0] : $sel_options;
			$this->slug .= "[]";
			echo '<select' . esc_attr($disabled) . ' id="' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '" class="' . esc_attr($this->field['css']) . '" style="min-width:100px;" >';
			if($this->field['list_type']=="country")
			{
				$countries = get_option("pie_countries");	
				echo wp_kses($this->createDropdown($countries,$sel_options), $this->piereg_forms_get_allowed_tags());			   	
			}
			else if($this->field['list_type']=="us_states")
			{
				$us_states	= get_option("pie_us_states");
				echo wp_kses($this->createDropdown($us_states,$sel_options), $this->piereg_forms_get_allowed_tags());
			}
			else if($this->field['list_type']=="can_states")
			{
				$can_states	= get_option("pie_can_states");
				echo wp_kses($this->createDropdown($can_states,$sel_options), $this->piereg_forms_get_allowed_tags());
			}
			else if (sizeof($this->field['value']) > 0) {
				
				for ($a = 0; $a < sizeof($this->field['value']); $a++) {
					$selected = '';
					$sel_options = (isset($sel_options[0]) && is_array($sel_options[0])) ? $sel_options[0] : $sel_options;
					if ( in_array($this->field['value'][$a], $sel_options) ) {
						$selected = 'selected';
					}
					if ($this->field['value'][$a] != "" || $this->field['display'][$a] != "")
						echo '<option ' . esc_attr($selected) . ' value="' . esc_attr($this->field['value'][$a]) . '">' . esc_html($this->field['display'][$a]) . '</option>';
				}
			}
			echo '</select>';
		
		}
    }

    function addNumberField($disabled)
    {
		$val   = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
        echo '<input' . esc_attr($disabled) .' id="' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '" class="' . esc_attr($this->field['css']) . '"  placeholder="' . esc_attr($this->field['placeholder']) . '" min="' . esc_attr($this->field['min']) . '" max="' . esc_attr($this->field['max']) . '" type="number" value="' . esc_attr($val) . '" />';
    }

    function addCheckRadio($disabled)
    {
        if (sizeof($this->field['value']) > 0) {
            $val = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
            for ($a = 0; $a < sizeof($this->field['value']); $a++) {
                $checked = '';
				$val = (isset($val[0]) && is_array($val[0])) ? $val[0] : $val;
                if (is_array($val) && in_array($this->field['value'][$a], $val)) {
                    $checked = 'checked="checked"';
                }
                echo '<span style="margin-left:5px;">'.esc_html($this->field['display'][$a]).'</span>';
                echo '<input' . esc_attr($disabled) . ' style="margin-left:5px;" value="' . esc_attr($this->field['value'][$a]) . '" ' . $checked . ' type="' . esc_attr($this->type) . '" name="' . esc_attr($this->slug) . '[]" class="' . esc_attr($this->field['css']) . '"  >';
            }
        }
    }

    function addHTML()
    {
        echo html_entity_decode($this->field['html']);
    }

	function addUpload($disabled)
	{
		$val = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<input'.esc_attr($disabled).' name="' . esc_attr($this->slug) . '" type="file" value="'.esc_attr($val) .'">';
		echo (trim($val) != "")? '<br /><a href="'.esc_url($val).'" target="_blank">'.esc_html(basename($val)).'</a>' : "";
	}

    function addProfilePic($disabled)
	{
		$val = get_user_meta($this->user_id , $this->slug, true); #get_usermeta deprecated
		
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<input'.esc_attr($disabled).' id="'.esc_attr($this->id).'" name="'.esc_attr($this->slug).'" type="file" class=" validate[funcCall[checkExtensions],ext[gif|jpeg|jpg|png|bmp]]" />';
		echo '<input id="'.esc_attr($this->id).'" name="'.esc_attr($this->slug).'_hidden" value="'.esc_attr($val).'" type="hidden"  />';
		
		$ext = (trim(basename($val)))? $val." Not Found" : "Profile Pictuer Not Found";
		$imgPath = (trim($val) != "")? $val : plugins_url("assets/images/userImage.png",dirname(__FILE__));
		echo '<br /><img src="'.esc_url($imgPath).'" style="max-width:150px;" alt="'.esc_attr(__('User Profile Picture',"pie-register")).'" />';
	}

    function addAddress($disabled)
    {
        $address = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
        echo '<div class="address">
		  <input'.esc_attr($disabled).' type="text" name="' . esc_attr($this->slug) . '[address]" id="' . esc_attr($this->id) . '" value="' . ((isset($address['address']))?esc_attr($address['address']):"") . '" >
		  <label>'.__("Street Address","pie-register").'</label>
		</div>';
        if (empty($this->field['hide_address2'])) {
            echo '<div class="address">
			  <input'.esc_attr($disabled).' type="text" name="' . esc_attr($this->slug) . '[address2]" id="address2_' . esc_attr($this->id) . '" value="' . ((isset($address['address2']))?esc_attr($address['address2']):"") . '" >
			  <label>'.__("Address Line 2","pie-register").'</label>
			</div>';
        }
        echo '<div class="address">
		  <div class="address2">
			<input'.esc_attr($disabled).' type="text" name="' . esc_attr($this->slug) . '[city]" id="city_' . esc_attr($this->id) . '" value="' . ((isset($address['city']))?esc_attr($address['city']):"") . '">
			<label>'.__("City","pie-register").'</label>
		  </div>';
        if (empty($this->field['hide_state'])) {
            if ($this->field['address_type'] == "International") {
                echo '<div class="address2"  >
					<input'.esc_attr($disabled).' type="text" name="' . esc_attr($this->slug) . '[state]" id="state_' . esc_attr($this->id) . '" value="' . ((isset($address['state']))?esc_attr($address['state']):"") . '">
					<label>'.__("State / Province / Region","pie-register").'</label>
				 	 </div>';
            } else if ($this->field['address_type'] == "United States") {
                $us_states = get_option("pie_us_states");
                $options   = wp_kses($this->createDropdown($us_states, ((isset($address['state']))?$address['state']:"")),$this->piereg_forms_get_allowed_tags());
                echo '<div class="address2"  >
					<select'.esc_attr($disabled).' id="state_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[state]">
					 ' . $options . ' 
					</select>
					<label>'.__("State","pie-register").'</label>
				  </div>';
            } else if ($this->field['address_type'] == "Canada") {
                $can_states = get_option("pie_can_states");
                $options    = wp_kses($this->createDropdown($can_states, ((isset($address['state']))?$address['state']:"")),$this->piereg_forms_get_allowed_tags());
                echo '<div class="address2">
						<select'.esc_attr($disabled).' id="state_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[state]">
						  ' . $options . '
						</select>
						<label>'.__("Province","pie-register").'</label>
					  </div>';
            }
        }
        echo '</div>';
        echo '<div class="address">';
        echo ' <div class="address2">
		<input'.esc_attr($disabled).' id="zip_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[zip]" type="text" value="' . ((isset($address['zip']))?esc_attr($address['zip']):"") . '" >
		<label>'.__("Zip / Postal Code","pie-register").'</label>
		</div>';
        if ($this->field['address_type'] == "International") {
            $countries = get_option("pie_countries");
            $options   = wp_kses($this->createDropdown($countries, ((isset($address['country']))?$address['country']:"")),$this->piereg_forms_get_allowed_tags());
            echo '<div class="address2" >
					<select'.esc_attr($disabled).' id="country_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[country]" >
                    <option value="">'.esc_html(__("Select Country","pie-register")).'</option>
					' . $options . '
					 </select>
					<label>'.esc_html(__("Country","pie-register")).'</label>
		  		</div>';
        }
        echo '</div>';
    }

    function addPhone($disabled)
    {
		$val   = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<input'.esc_attr($disabled).' id="' . esc_attr($this->id) . '"  name="' . esc_attr($this->slug) . '" class="input_fields"  placeholder="' . (isset($this->field['placeholder']) ? esc_attr($this->field['placeholder']) : "") . '" type="text" value="' . esc_attr($val) . '" />';
    }

    function addInvitation($disabled)
    {
		$val   = get_user_meta($this->user_id, "invite_code", true); #get_usermeta deprecated
		if(is_array($val))
		{
			$val = implode( ",", $val );	
		}
		echo '<input'.esc_attr($disabled).' id="' . esc_attr($this->id) . '"  name="' . esc_attr($this->slug) . '" class="input_fields"  placeholder="' . (isset($this->field['placeholder']) ? esc_attr($this->field['placeholder']) : "") . '" type="text" disabled="disabled" style="width:25em;" value="' . esc_attr($val) . '" />';
    }
	
	function addTime($disabled)
    {
        $time = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
        echo '<input' . esc_attr($disabled) . ' size="2" maxlength="2" id="hh_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[hh]" type="text" value="' . ((isset($time['hh']))?esc_attr($time['hh']):"") . '"> : <input' . esc_attr($disabled) . ' size="2" maxlength="2" id="mm_' . esc_attr($this->id) . '" type="text" name="' . esc_attr($this->slug) . '[mm]"  value="' . ((isset($time['mm']))?esc_attr($time['mm']):"") . '">';
		
        if ($this->field['time_type'] == "12") {
            $time_format = ((isset($time['time_format']))?$time['time_format']:"");
            echo '<select' . esc_attr($disabled) . ' name="' . esc_attr($this->slug) . '[time_format]" >			
			<option ' . (($time_format == "am") ? 'selected' : "") . ' value="am">'.esc_html(__("AM","pie-register")).'</option>
			<option ' . (($time_format == "pm") ? 'selected' : "") . ' value="pm">'.esc_html(__("PM","pie-register")).'</option>			
			</select>';
        }
        echo '</div>';
    }

    function addDate($disabled)
    {
        $date = get_user_meta($this->user_id, $this->slug, true); #get_usermeta deprecated
		if(!$date)
		{
    		if(!is_array($date)) $date = array();
			$date['date']['mm'] = "";
			$date['date']['dd'] = "";
			$date['date']['yy'] = "";	
		}
        if ($this->field['date_type'] == "datefield") {
            if ($this->field['date_format'] == "mm/dd/yy") {
                echo '<div class="piereg_time date_format_field">
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="mm_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][mm]" maxlength="2" type="text" value="' . esc_attr($date['date']['mm']) . '" >
					<label>'.__("MM","pie-register").'</label>
				  </div>
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="dd_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][dd]" maxlength="2"  type="text" value="' . esc_attr($date['date']['dd']) . '">
					<label>'.__("DD","pie-register").'</label>
				  </div>
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="yy_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][yy]" maxlength="4"  type="text" value="' . esc_attr($date['date']['yy']) . '">
					<label>'.__("yy","pie-register").'</label>
				  </div>
				</div>';
            } else if ($this->field['date_format'] == "yy/mm/dd" || $this->field['date_format'] == "yy.mm.dd") {
                echo '<div class="piereg_time date_format_field">
				 <div class="time_fields">
					<input'.esc_attr($disabled).' id="yy_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][yy]" maxlength="4"  type="text" value="' . esc_attr($date['date']['yy']) . '">
					<label>'.__("yy","pie-register").'</label>
				  </div>
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="mm_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][mm]" maxlength="2" type="text" value="' . esc_attr($date['date']['mm']) . '">
					<label>'.__("MM","pie-register").'</label>
				  </div>
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="dd_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][dd]" maxlength="2"  type="text" value="' . esc_attr($date['date']['dd']) . '">
					<label>'.__("DD","pie-register").'</label>
				  </div>				  
				</div>';
            } else {
                echo '<div class="piereg_time date_format_field">
				 <div class="time_fields">
					<input'.esc_attr($disabled).' id="dd_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][dd]" maxlength="2"  type="text" value="' . esc_attr($date['date']['dd']) . '">
					<label>'.__("DD","pie-register").'</label>
				  </div>	
				 <div class="time_fields">
					<input'.esc_attr($disabled).' id="yy_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][yy]" maxlength="4"  type="text" value="' . esc_attr($date['date']['yy']) . '">
					<label>'.__("yy","pie-register").'</label>
				  </div>
				  <div class="time_fields">
					<input'.esc_attr($disabled).' id="mm_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][mm]" maxlength="2" type="text" value="' . esc_attr($date['date']['mm']) . '">
					<label>'.__("MM","pie-register").'</label>
				  </div>				  			  
				</div>';
            }
        } 
		else if ($this->field['date_type'] == "datepicker") {
			$returned_date = ( isset($date['date'][0]) ) ? $date['date'][0] : "";
			echo '<div class="piereg_time date_format_field">
				  <input'.esc_attr($disabled).' id="' . esc_attr($this->id) . '" class="date_start" name="' . esc_attr($this->slug) . '[date][]" value="' . esc_attr($returned_date) . '" type="text" ></div>';
        } else if ($this->field['date_type'] == "datedropdown") {
            echo '<div class="piereg_time date_format_field">
					<select'.esc_attr($disabled).' id="mm_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][mm]">
					  <option value="">'.esc_html(__("Month","pie-register")).'</option>';
            for ($a = 1; $a <= 12; $a++) {
                $sel = '';
                if ((int) $a == (int) $date['date']['mm']) {
                    $sel = 'selected';
                }
                echo '<option ' . esc_attr($sel) . ' value="' . esc_attr($a) . '">' . esc_html(__(sprintf("%02s", $a),"pie-register")) . '</option>';
            }
            echo '</select>
					<select'.esc_attr($disabled).' id="dd_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][dd]">
					  <option value="">'.esc_html(__("Day","pie-register")).'</option>';
            for ($a = 1; $a <= 31; $a++) {
                $sel = '';
                if ((int) $a == (int) $date['date']['dd']) {
                    $sel = 'selected';
                }
                echo '<option ' . esc_attr($sel) . ' value="' . esc_attr($a) . '">' . esc_html(__(sprintf("%02s", $a),"pie-register")) . '</option>';
            }
            echo '</select>
					<select'.esc_attr($disabled).' id="yy_' . esc_attr($this->id) . '" name="' . esc_attr($this->slug) . '[date][yy]">
					  <option value="">'.esc_html(__("Year","pie-register")).'</option>';
            for ($a = 2099; $a >= 1900; $a--) {
                $sel = '';
                if ((int) $a == (int) $date['date']['yy']) {
                    $sel = 'selected';
                }
                echo '<option ' . esc_attr($sel) . ' value="' . esc_attr($a) . '">' . esc_html($a) . '</option>';
            }
            echo '</select>
				</div>';
        }
    }

	function addList($disabled)
	{
		$list = get_user_meta($this->user_id, $this->slug, true);	#get_usermeta deprecated
		if(!is_array($list))
			$list = array();
		$width  = 90 /  $this->field['cols']; 
		for($a = 1 ,$c=0; $a <= $this->field['rows'] ; $a++,$c++)
		{
			echo '<div style="max-width:55%;">';
			for($b = 1,$d=0 ; $b <= $this->field['cols'] ;$b++,$d++)
			{
				echo '<input'.esc_attr($disabled).' value="'.((isset($list[$c][$d]))?esc_attr($list[$c][$d]):"").'" style="width:'.$width.'%;margin-right:2px;" type="text" name="'.esc_attr($this->slug.'['.$c.'][]').'" class="input_fields"> ';
			}
			echo '</div>';
		}
	}

	function createFieldName($text)
    {
        return "pie_".$this->getMetaKey($text);
    }

    function createFieldID()
    {
        $key_id = (isset($this->field['id'])) ? $this->field['id'] : "";
		return "field_" . $key_id;
    }

    function addLabel()
    {
		return '<label for="' . esc_attr($this->id) . '">' . esc_html($this->field['label']) . '</label>';
    }

    function printFields()
    {
        $update     = get_option(OPTION_PIE_REGISTER);
		$disabled 	= "";
		
		if( !current_user_can( 'edit_users' ) ) {
			$disabled 	= ' disabled';
		}
		
        switch ($this->type):
            case 'text':           
                $this->addTextField($disabled);
                break;
            case 'textarea':
                $this->addTextArea($disabled);
                break;
            case 'dropdown':
            case 'multiselect':
                $this->addDropdown($disabled);
                break;
            case 'number':
                $this->addNumberField($disabled);
                break;
            case 'radio':
            case 'checkbox':
                $this->addCheckRadio($disabled);
                break;
            case 'html':
                $this->addHTML(); 
                break;
            case 'time':
                $this->addTime($disabled);
                break;
            case 'upload':
                $this->addUpload($disabled);
                break;
            case 'profile_pic':
                $this->addProfilePic($disabled);
                break;
            case 'address':
                $this->addAddress($disabled);
				break;
				//Pie-register-woocommerce Addon
				case 'wc_billing_address':
					if ($this->woocommerce_and_piereg_wc_addon_active)
					{
						echo apply_filters("pieregister_print_woocommerce_billing_address_admin_profile", $disabled); 
					}
					break;
				case 'wc_shipping_address':
					if ($this->woocommerce_and_piereg_wc_addon_active)
					{
						echo apply_filters("pieregister_print_woocommerce_shipping_address_admin_profile", $disabled); 	
					}
					break;
            case 'phone':
			/*
				*	Just Work 2 way login
			*/
            case 'two_way_login_phone':
                $this->addPhone($disabled);
             	break;
				
			case 'invitation':
				$this->addInvitation($disabled);
				break;
				
            case 'date':
                $this->addDate($disabled);
                break;
            case 'list':
                $this->addList($disabled);
                break;
        endswitch;
    }
    function edit_user_profile($user)
    {
		$form_id = "default";
		if(isset($_GET['user_id'])){
			$form_id = get_user_meta((intval($_GET['user_id'])),"user_registered_form_id",true);
			$user_id = intval($_GET['user_id']);
		}elseif(is_admin()){
			$form_id = get_user_meta((intval($user->ID)),"user_registered_form_id",true);
			$user_id = intval($user->ID);
		}else{
			return;
		}
		
		$form_id = ((!empty($form_id)) ? $form_id : "default");
		if( $form_id == 'default' ) {
			$form_free_id 	= $this->regFormForFreeVers();
			update_user_meta($user_id, 'user_registered_form_id', $form_free_id);
			$form_id 		= $form_free_id;
		}
		
		$this->data = $this->getCurrentFields($form_id);
		
		/**
		 * IF USER ASSIGNED FORM DELETED, THEN ASSIGN DEFAULT FORM
		 * since v3.6
		 */
		if(!is_array($this->data) || sizeof($this->data) == 0) {	
			$this->data 	= $this->getCurrentFields($this->regFormForFreeVers()); 
			update_user_meta($user_id, 'user_registered_form_id', $form_free_id);
		}

        if (sizeof($this->data) > 0 && is_array($this->data))
		{
           	$this->user_id = $user->ID;
			echo "<h3>". __('Pie Register Registration Fields','pie-register')."</h3>";
            echo '<table class="form-table">';
			
           	foreach ($this->data as $this->field) 
		   	{
			  	$key_id = (isset($this->field['id'])) ? $this->field['id'] : "";
             	$this->slug = $this->createFieldName($this->field['type']."_".$key_id);
                $this->type = $this->field['type'];
                $this->id   = $this->createFieldID();	   
				if((isset($this->field['show_in_profile']) and $this->field['show_in_profile'] == 0 ) && !is_admin())
					continue;
				/*
					*	Just work 2way login
				*/
				if($this->type == "two_way_login_phone")
				{
					$this->slug = "piereg_two_way_login_phone";
				}
				//When to add label
				switch($this->type) :
					case 'two_way_login_phone':
						include_once( $this->admin_path . 'includes/plugin.php' );
						$twilio_option = get_option("pie_register_twilio");
						$plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
						if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") && isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 1 && $plugin_status == "Activated" ){
							echo '<tr><td>'.wp_kses_post($this->addLabel()).'</td><td>';
							echo wp_kses($this->printFields(), $this->piereg_forms_get_allowed_tags()).'</td></tr>';
						}
					break;
					case 'time':
					case 'text' :
					case 'textarea':
					case 'dropdown':
					case 'multiselect':
					case 'number':
					case 'radio':
					case 'checkbox':
					case 'upload':
					case 'profile_pic':
					case 'address':
					case 'phone':
					/*
						*	Just Work 2 way login
					*/
					
					case 'invitation':
					case 'date':
					case 'list':
					case "default" && $this->default_fields:
						echo '<tr><th>'.wp_kses_post($this->addLabel()).'</th><td>';
						echo wp_kses($this->printFields(),$this->piereg_forms_get_allowed_tags()).'</td></tr>';
					break;
				endswitch;
			 }
           echo '</table>';
        }
    }

	function updateMyProfile($user_id) 
	{
     	if ( current_user_can( 'edit_users' ) ) // only admin can update profile from WP Admin Profile Page
     	{
			$this->updateProfile($user_id); 
	 	}
 	}

    function validate_user_profile($errors, $update, $user)
    {
		/*
			*	Sanitizing post data
		*/
		$this->pie_post_array	= $this->piereg_sanitize_post_data_escape( ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
        foreach ($this->data as $this->field) {
            $this->slug         = $this->createFieldName($this->field['label']);
            $this->type         = $this->type;
            $this->id           = $this->createFieldID();
			
			/*
				*	Just Work 2way login
			*/
			if($this->field['type'] == "two_way_login_phone")
			{
				$this->slug = "piereg_two_way_login_phone";
			}
			
            $field_name         = $this->pie_post_array[$this->slug];
            $required           = $this->field['required'];
            $rule               = $this->field['validation_rule'];
            $validation_message = (!empty($this->field['validation_message']) ? $this->field['validation_message'] : $this->field['label'] . " is required.");
            if ((!isset($field_name) || empty($field_name)) && $required) {
                $errors->add($this->slug, '<strong>'.ucwords(__('error','pie-register')).'</strong>: ' . $validation_message);
            } else if ($rule == "number") {
                if (!is_numeric($field_name)) {
                    $errors->add( $this->slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$this->field['label'] .apply_filters("piereg_field_must_contain_only_numbers",__('Field must only contain numbers.','pie-register' )));
			    }
            } else if ($rule == "alphanumeric") {
                if (!preg_match("/^([a-z 0-9])+$/i", $field_name)) {
                   	$errors->add( $this->slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$this->field['label'] .apply_filters("piereg_field_may_only_contain_alpha_numeric_characters",__(' Field must only contain alpha-numeric characters.','pie-register' )));
                }
            } else if ($rule == "email") {
                if (!filter_var($field_name, FILTER_VALIDATE_EMAIL)) {
                    $errors->add( $this->slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$this->field['label'].apply_filters("piereg_field_must_contain_a_valid_email_address",__(' Field must contain a valid email address.','pie-register' )));
                }
            } else if ($rule == "website") {
                if (!filter_var($field_name, FILTER_VALIDATE_URL)) {                   
					$errors->add( $this->slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.$this->field['label'] .apply_filters("piereg_must_be_a_valid_URL",__(' Must be a valid URL.','pie-register' )));
                }
            }
        }
        if (sizeof($errors->errors) == 0) {
            $this->updateProfile($user->ID);
        }
        return $errors;
    }

	function updateProfile($user_id)
    {
		$form_id 	  	= get_user_meta($user_id,"user_registered_form_id",true);		
		$this->data 	= $this->getCurrentFields($form_id);
		
		if( empty($this->pie_post_array) ) { 
			$this->pie_post_array = $this->piereg_sanitize_post_data_escape( ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );
		}
		
		global $errors;
		$errors = new WP_Error();

		if( isset($this->pie_post_array['pie_form_assign']) && !empty($this->pie_post_array['pie_form_assign']) && ($this->pie_post_array['pie_form_assign'] !== $form_id)  )
		{
			update_user_meta($user_id,"user_registered_form_id", $this->pie_post_array['pie_form_assign']);
		}

        foreach ($this->data as $this->field){
		//When to add label
			if(!isset($this->field['id'])) continue;
			$slug       = $this->createFieldName($this->field['type']."_".$this->field['id']);
			switch($this->field['type']) :
				case 'time':
					if($this->pie_post_array[$slug]['time_format'])
					{
						$this->pie_post_array[$slug]['hh'] = intval($this->pie_post_array[$slug]['hh']);
						if($this->pie_post_array[$slug]['hh'] > 12)
							$this->pie_post_array[$slug]['hh'] = "12";
						$this->pie_post_array[$slug]['mm'] = intval($this->pie_post_array[$slug]['mm']);
						if($this->pie_post_array[$slug]['mm'] > 59)
							$this->pie_post_array[$slug]['mm'] = "59";
						$field_name			= $this->pie_post_array[$slug];
						update_user_meta($user_id, $slug, $this->pie_post_array[$slug]);
					}else{
						$this->pie_post_array[$slug]['hh'] = intval($this->pie_post_array[$slug]['hh']);
						if($this->pie_post_array[$slug]['hh'] > 23)
							$this->pie_post_array[$slug]['hh'] = "23";
						$this->pie_post_array[$slug]['mm'] = intval($this->pie_post_array[$slug]['mm']);
						if($this->pie_post_array[$slug]['mm'] > 59)
							$this->pie_post_array[$slug]['mm'] = "59";
						$field_name			= $this->pie_post_array[$slug];
						update_user_meta($user_id, $slug, $this->pie_post_array[$slug]);
					}
				break;
				case 'upload':
					$this->pie_upload_files($user_id,$this->field,$slug);
					break;
				case 'profile_pic':
					$this->pie_profile_pictures_upload($user_id,$this->field,$slug);
					break;				
				case 'text' :
				case 'textarea':
				case 'dropdown':
				case 'multiselect':
				case 'number':
				case 'radio':
				case 'checkbox':
				//case 'html':
				case 'address':
				case 'phone':
				case 'invitation':
				case 'date':
				case 'list':
					$field_value = $this->pie_post_array[$slug];
					update_user_meta($user_id,$slug, $field_value);
				break;
				/*
					*	Just work 2way login phone
				*/
				case "two_way_login_phone":
					$slug = "piereg_two_way_login_phone";
					$field_value = $this->pie_post_array[$slug];
					update_user_meta($user_id,$slug, $field_value);
				break;
			endswitch;
        }
    }
	function pie_profile_pictures_upload($user_id,$field,$field_slug,$form_id=0){
		global $errors;
		$errors = new WP_Error();
		if($_FILES[$field_slug]['name'] != ''){
			/** 
			 * UPLOAD PROFILE PICTURE
			 */
			$result = $this->piereg_validate_files(sanitize_file_name($_FILES[$field_slug]['name']),array("gif","jpeg","jpg","png","bmp"));
			if($result)
			{
				$temp = explode(".", $_FILES[$field_slug]["name"]);
				$extension = end($temp);
				$upload_dir = wp_upload_dir();
				$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
				wp_mkdir_p($temp_dir);
				$temp_file_name = sanitize_file_name("profile_pic_".crc32($user_id."_".$extension."_".time()).".".$extension);
				$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
				$required           = $field['required'];
				if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name) && $required){
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Fail_to_upload_profile_picture",__('Failed to upload the profile picture.','pie-register' )));
				}else{
					update_user_meta($user_id,$field_slug, $temp_file_url);
				}
			}else{
				$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_invalid_profile_picture",__('Invalid profile picture','pie-register' )));
			}
		}
	}

	function pie_upload_files($user_id,$field,$field_slug,$form_id=0)
	{
		global $errors;
		$errors = new WP_Error();
		$required           = $field['required'];
		if($_FILES[$field_slug]['name'] != ''){
			if($field['file_types'] != ""){
				$filter_string = stripcslashes($field['file_types']);
				$filter_array = explode(",",$filter_string);
				$result = $this->piereg_validate_files(sanitize_file_name($_FILES[$field_slug]['name']),$filter_array);
				if($result){
					$temp = explode(".", sanitize_file_name($_FILES[$field_slug]["name"]));
					$extension = end($temp);
					$upload_dir = wp_upload_dir();
					$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
					wp_mkdir_p($temp_dir);
					$temp_file_name = sanitize_file_name("file_".crc32($user_id."_".$extension."_".time()).".".$extension);
					$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
					if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name) && $required){
						$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Fail_to_upload_profile_picture",__('Failed to upload the profile picture.','pie-register' )));
					}else{
						update_user_meta($user_id,$field_slug, $temp_file_url);
					}
				}
				else{
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Invlid_upload_file",__('Invalid upload file.','pie-register' )));
				}
			}else{
				$temp = explode(".", sanitize_file_name($_FILES[$field_slug]["name"]));
				$extension = end($temp);
				$upload_dir = wp_upload_dir();
				$temp_dir = realpath($upload_dir['basedir'])."/piereg_users_files/".$user_id;
				wp_mkdir_p($temp_dir);
				$temp_file_name = sanitize_file_name("file_".crc32($user_id."_".$extension."_".time()).".".$extension);
				$temp_file_url = $upload_dir['baseurl']."/piereg_users_files/".$user_id."/".$temp_file_name;
				if(!move_uploaded_file($_FILES[$field_slug]['tmp_name'],$temp_dir."/".$temp_file_name) && $required){
					$errors->add( $field_slug , '<strong>'.ucwords(__('error','pie-register')).'</strong>: '.apply_filters("piereg_Fail_to_upload_profile_picture",__('Failed to upload the profile picture.','pie-register' )));
				}else{
					update_user_meta($user_id,$field_slug, $temp_file_url);
				}
			}
		}
	}

	function piereg_wp_admin_form_tag(){
		echo ' enctype="multipart/form-data" ';
	}
}
?>