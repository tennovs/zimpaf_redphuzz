<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<style type="text/css">#wpbody-content > div:not(.pieregister-admin){display:none;}</style>
<?php 
$data 	= PieRegister::getCurrentFields();
if(!is_array($data) || sizeof($data) == 0) {	
	$data 	= get_option( 'pie_fields_default' );
}

$button = get_option(OPTION_PIE_REGISTER);
$meta   = PieRegister::getDefaultMeta();

if( !isset($_GET['form_id']) )
{
	die("You don't have permission to access this page.");
}

?>
<div style="width:99%;overflow:hidden;" class="pieregister-admin">
  <div class="right_section">
    <div class="pie_wrap">
      <h2>
        <?php _e("Manage Forms : Form Editor","pie-register"); ?>
      </h2>
      <?php
       	if( isset($this->pie_post_array['error_message']) && !empty( $this->pie_post_array['error_message'] ) )
            echo '<div id="error" class="error fade msg_belowheading"><p><strong>' . esc_html($this->pie_post_array['error_message'])  . "</strong></p></div>";
			
       	if(isset( $this->pie_post_array['success_message'] ) && !empty( $this->pie_post_array['success_message'] ))
            echo '<div id="message" class="updated fade msg_belowheading"><p><strong>' . esc_html($this->pie_post_array['success_message'])  . "</strong></p></div>";
		
    	?>
      <form method="post" id="formeditor">
        <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_reg_form_nonce','piereg_reg_form_nonce'); ?>
        <?php
        if(isset($_GET['form_id']) and ((int)$_GET['form_id']) != 0){
			echo '	<input type="hidden" name="form_id" value="'.esc_attr(base64_encode(((int)$_GET['form_id']))).'">
      				<input type="hidden" name="page" value="edit">';
		}?>
        <input type="hidden" name="field[form][type]" value="form">
        <input type="hidden" name="field[form][meta]" value="0">
        <div style="clear: both;float: none;">
          <input type="submit" style="padding:0px 20px;" class="button button-primary button-large" name="pie_form"  value="<?php _e("Save Settings",'pie-register');?>">
          <?php
        if(isset($_GET['form_id']) and ((int)$_GET['form_id']) != 0){
			$form_id = isset($_GET['form_id'])?(int)$_GET['form_id']:0;
			$form_name = str_replace(" ","_",$data['form']['label']);
			$preview_url = add_query_arg(array('pr_preview'=>1,'form_id'=>$form_id,'prFormId'=>$form_id,'form_name'=>$form_name), get_permalink($button['alternate_register']));
		?>
        	<a href="<?php echo esc_url($preview_url); ?>" style="padding:0px 20px;"target="_blank" class="button button-primary button-large" name="pie_form">
          <?php _e("Preview",'pie-register');?>
          </a>
          <?php } ?>
        </div>
        <!--Form Settings-->
        <ul>
          <li class="fields">
            <div class="fields_options" id="field_form_title"> <a href="#" class="edit_btn" title="<?php _e("Edit Form","pie-register"); ?>"></a>
              <label> <?php echo esc_html($data['form']['label'])?> </label>
              <br>
              <p id="paragraph_form"> <?php echo esc_html($this->piereg_get_small_string($data['form']['desc'],350));?> </p>
            </div>
            <div class="fields_main">
              <div class="advance_options_fields">
                <div class="advance_fields">
                  <label for="form_title">
                    <?php _e("Label","pie-register"); ?>
                  </label>
                  <input id="form_title" value="<?php echo esc_attr($data['form']['label'])?>" type="text" name="field[form][label]" class="input_fields field_label">
                </div>
                <div class="advance_fields">
                  <label for="form_desc">
                    <?php _e("Description","pie-register"); ?>
                  </label>
                  <textarea name="field[form][desc]" id="paragraph_textarea_form" rows="8" cols="16"><?php echo esc_html(html_entity_decode(stripslashes($data['form']['desc']))); ?></textarea>
                </div>
                <div class="advance_fields">
                  <label>
                    <?php _e("CSS Class Name","pie-register"); ?>
                  </label>
                  <input type="text" name="field[form][css]" value="<?php echo esc_attr($data['form']['css']);?>" class="input_fields">
                </div>
                <div class="advance_fields">
                  <label>
                    <?php _e("Label Alignment","pie-register"); ?>
                  </label>
                  <select class="swap_class" onchange="swapClass(this.value);" name="field[form][label_alignment]">
                    <option <?php if($data['form']['label_alignment']=='top') echo 'selected="selected"';?> value="top">
                    <?php _e("Top","pie-register"); ?>
                    </option>
                    <option <?php if($data['form']['label_alignment']=='left') echo 'selected="selected"';?> value="left">
                    <?php _e("Left","pie-register"); ?>
                    </option>
                  </select>
                </div>
                <div class="advance_fields">
                  <label id="set_user_role_"><?php echo __("Registering User Role","pie-register"); ?></label>
                  <select id="set_user_role_" name="set_user_role_" >
                    <?php
						if(
						   isset($_GET['form_id']) and intval($_GET['form_id']) != 0 and 
						   isset($_GET['form_name']) // and $_GET['form_name']!= ""
						   )
						{
							$user_role = $button['pie_regis_set_user_role_'.intval($_GET['form_id'])];
							$user_role = ( $user_role != "") ? $user_role : 'subscriber';
						}
						else
						{
							$user_role = $button['pie_regis_set_user_role_'];
							$user_role = ( $user_role != "") ? $user_role : 'subscriber';
						}
						
						global $wp_roles;
						
						$role = $wp_roles->roles;
						$wp_default_user_role = get_option("default_role");
						
						foreach($role as $key=>$value)
						{
							echo '<option value="'.esc_attr($key).'"';
							selected($user_role == $key, true, true);
							echo '>'.esc_html($value['name']).'</option>';
						}
						?>
                  </select>
                </div>
                <?php
				$notification = array(__("Admin Verification","pie-register"),
									  __("E-mail Verification","pie-register"));
			  	?>
                <div class="advance_fields">
                  <label>
                    <?php _e("User Verification","pie-register"); ?>
                  </label>
                  <select name="field[form][user_verification]" id="form_user_verification"  class="checkbox_fields enabel_user_verification">
                    <option value="0" <?php selected(((isset($data['form']['user_verification']) && $data['form']['user_verification'] == 0) || (!isset($data['form']['user_verification']))), true, true);?> >
                    <?php _e("Use Default","pie-register"); ?>
                    </option>
                    <option value="1" <?php selected((isset($data['form']['user_verification']) && $data['form']['user_verification'] == 1), true, true);?> ><?php echo esc_html($notification[0]); ?></option>
                    <option value="2" <?php selected((isset($data['form']['user_verification']) && $data['form']['user_verification'] == 2), true, true);?> ><?php echo esc_html($notification[1]);?></option>
                  </select>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <!--Form Settings End-->
        <fieldset>
          <legend align="center"><?php echo _e("Drag and Drop fields here","pie-register"); ?></legend>
          <div id="hint_1" class="fields_hint" style="left: 95%;top: 50%; z-index:2;"> <img src="<?php echo esc_url(PIEREG_PLUGIN_URL .'assets/images/left_arrow.jpg')?>" width="45" height="26" align="left">
            <div class="hint_content">
              <h4>
                <?php _e("Did you know?","pie-register"); ?>
              </h4>
              <span>
              <?php _e("You can sort fields vertically","pie-register"); ?>
              </span> <br>
              <input type="button" class="thanks" value="<?php _e("OK","pie-register"); ?>">
            </div>
          </div>
          <!--Form Fields-->
          <ul id="elements"  class="piereg_registration_form_fields">
            <?php   
		if(sizeof($data) >  0) 
		{
			$no = max(array_keys($data));			
			$field_values = array();
			$meta   = $this->getDefaultMeta();
			/*$is_pricing == false;*/
			foreach($data as $field)
			{	
				if( $field['type'] == "honeypot" || $field['type'] == "hcaptcha" ) {	
					continue;
				}
        //pie-register-woocommerce addon
        if( $field['type'] == "wc_billing_address" || $field['type'] == "wc_shipping_address" ) 
        {	
          # Don't display woocommerce fields tab at backend if addon is deactivated (even if the fields are saved in DB)
          if (!$this->woocommerce_and_piereg_wc_addon_active)
          {
              continue;
          } 
        }
          
				
				if( $field['type'] == "two_way_login_phone" ) {
					include_once( $this->admin_path . 'includes/plugin.php' );
					$twilio_option = get_option("pie_register_twilio");
					$plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
					if( isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 0 || $plugin_status != "Activated" || !is_plugin_active("pie-register-twilio/pie-register-twilio.php") ) {	
						continue;
					}
				}
					
				//We don't need Form and Submit Button in sorting
				if($field['type']=="submit" || $field['type']=="" || $field['type']=="form" || ($field['type']=="invitation" && $button["enable_invitation_codes"]=="0"))
				{
					continue;
				}
					
					?>
            <li class="fields">
              <div id="holder_<?php echo esc_attr($field['id'])?>" class="fields_options fields_optionsbg">
              <?php
		
		if($field['type'] == "url" || $field['type'] == "aim" || $field['type'] == "yim" || $field['type'] == "jabber" || $field['type'] == "description") 
		{
			$field['type'] = "default";	
		}
				
				$data_field_id = '';
				  switch($field['type']) :
            //case 'username' :
						//case 'password' :
						//case 'email' :
            //case 'multiselect':
						//case 'radio':
						//case 'checkbox':
						//case 'pricing':
            //case 'hidden' :
						
            case 'text' :
						case 'website' :
						case 'phone':
						case 'invitation' :
						case 'textarea':
            case 'dropdown':
						case 'custom_role':
						case 'number':
						case 'name':
							$data_field_id = ' data-field_id="'.esc_attr($field['id']).'" '; // Applied conditional logic in these fields. 
						break; 
				  endswitch;
		
		
		 //We can't edit default wordpress fields
		 echo '<a href="javascript:;" class="edit_btn" title="'.__("Edit","pie-register").'"></a>';
          ?>
              <!--Adding Label-->
              <div class="label_position"  id="field_label_<?php echo esc_attr($field['id'])?>"  <?php echo $data_field_id; ?>>
                <?php if( isset($field['label']) && $field['label'] == "E-mail")  { ?>
                <label><?php echo _e("Email"); ?></label>
                <?php } else if( $field['type'] == 'terms' ) {?>
                  <!-- do nothing here.. -->
                <?php } else { ?>
                  <label>
                  <?php 
                    if (empty($field['label'])) 
                    { // pie-register-woocommerce addon
                      if ($field['type'] == 'wc_billing_address') 
                      {
                        echo __("Billing Address","pie-register");
                      }
                      else if ($field['type'] == 'wc_shipping_address') 
                      {
                        echo __("Shipping Address","pie-register");
                      } 
                      else
                      {
                        echo esc_html($field['type']);
                      }
                    } 
                    else 
                    {
                      echo esc_html(trim($field['label']));
                    }

                  ?>
                </label>
                <?php } ?>
              </div>
              <?php
           //We can't remove Username, password and email fields
		    if(!isset($field['remove']) || ($field['type'] == "username") )
				echo '<a href="javascript:;" rel="'.esc_attr($field['id']).'" class="delete_btn" title="'.(__("Delete","pie-register")).'">X</a>';                
			else
				echo '<input  name="field['.esc_attr($field['id']).'][remove]" value="0" type="hidden" /> '; 
			
			$piereg_recaptcha_area = "";
			if( $field['type'] == "captcha" )
        $piereg_recaptcha_area = "piereg_recaptcha_area";
      if( $field['type'] == "terms" )
        $piereg_recaptcha_area = "piereg_terms";

           		?>
              <input type="hidden" name="field[<?php echo esc_attr($field['id'])?>][id]" value="<?php echo esc_attr($field['id'])?>" id="id_<?php echo esc_attr($field['id'])?>">
              <input type="hidden" name="field[<?php echo esc_attr($field['id'])?>][type]" id="type_<?php echo esc_attr($field['id'])?>" value="<?php echo esc_attr($field['type'])?>" >
              <div class="fields_position <?php echo esc_attr($piereg_recaptcha_area); ?>" id="field_position_<?php echo esc_attr($field['id'])?>">
                <?php
					switch($field['type']) :
					case 'text' :
					case 'username' :
					case 'website' :
					case 'hidden' :
					case 'phone':
					case 'two_way_login_phone':
						$this->addTextField($field,$field['id'],$field['type']);
					break;
					case 'invitation' :					
						$this->addInvitationField($field,$field['id']);					
          break;
          case 'terms' :					
						$this->addTermsField($field,$field['id']);					
					break;
					case 'password' :
						$this->addPassword($field,$field['id']);
					break;
					case 'email' :
						$this->addEmail($field,$field['id']);
					break;
					case 'textarea':
						$this->addTextArea($field,$field['id']);
					break;
					case 'dropdown':
					case 'multiselect':
          case 'custom_role':
						$this->addDropdown($field,$field['id']);
					break;
					case 'number':
						$this->addNumberField($field,$field['id']);			
					break;
					case 'radio':
          case 'checkbox':
						$this->addCheckRadio($field,$field['id']);
					break;
					case 'html':
						$this->addHTML($field,$field['id']);
					break;
					case 'name':
						$this->addName($field,$field['id']);
					break;
					case 'time':
						$this->addTime($field,$field['id']);
					break;
					case 'upload':
						$this->addUpload($field,$field['id']);
					break;
					case 'profile_pic':	
						$this->addProfilePicUpload($field,$field['id']);
					break;
					case 'address':
						$this->addAddress($field,$field['id']);
          break;
          //pie-register-woocommerce addon
          case 'wc_billing_address':
            $this->addWooCommerceBillingAddress($field,$field['id']);
          break;
          case 'wc_shipping_address':
            $this->addWooCommerceShippingAddress($field,$field['id']);
          break;
					case 'captcha':
						$this->addCaptcha($field,$field['id']);
					break;	
					case 'math_captcha':
						$this->addMath_Captcha($field,$field['id']);
					break;
					case 'date':
						$this->addDate($field,$field['id']);
					break;
					case 'list':
						$this->addList($field,$field['id']);
					break;
					case 'pricing':
						$this->addPricing($field,$field['id']);
						/*$is_pricing = true;*/
					break;
					case 'sectionbreak':
						$this->addSectionBreak($field,$field['id']);
					break;
					case 'pagebreak':
						$this->addPageBreak($field,$field['id']);
					break;
					case 'default':
						$this->addDefaultField($field,$field['id']);
					break;
				endswitch;				
					
				$field_values[$field['id']] = serialize($this->cleantext($field,$field['id']));
				
				  echo "</div>";
				 			  
			 ?>
              </div>
              <?php 
			if(isset($meta[$field['type']]) && $field['type'] == "pricing" ){
				$payment_gateways_html = "";
				$payment_gateways_list = $this->payment_gateways_list();
				foreach($payment_gateways_list as $pgKey=>$pgval){
					$selected_pnl = "";
					
					if( isset($field['allow_payment_gateways']) && !empty($field['allow_payment_gateways']) && is_array($field['allow_payment_gateways']) ){
						if( in_array($pgKey,$field['allow_payment_gateways']) ){
							$selected_pnl = 'checked="checked"';
						}
					}else{
						$selected_pnl = 'checked="checked"';
					}
					
					$payment_gateways_html .= '<label for="allow_payment_gateways_'.esc_attr($pgKey).'" class="required piereg-payment-list"><input name="field['.esc_attr($field['id']).'][allow_payment_gateways][]" id="allow_payment_gateways_'.esc_attr($pgKey).'" value="'.esc_attr($pgKey).'" type="checkbox" '.$selected_pnl.' class="checkbox_fields">'.$pgval.'</label>';
				}
				
				if( $payment_gateways_html == "" )
				{
					$payment_gateways_html .= "<label class='piereg-payment-list'>No payment gateway enable.</label>";	
				}
				
				echo str_replace( array("%d%","%payment_gateways_list_box%") , array($field['id'],$payment_gateways_html) , $meta[$field['type']] );
				
			}elseif(isset($meta[$field['type']])){
				echo str_replace("%d%",$field['id'],$meta[$field['type']]);
			}
		  		
		  	?>
            </li>
            <?php 	
				
			}	
		}
		
		?>
          </ul>
        </fieldset>
        <ul id="submit_ul">
          <li class="fields">
            <div class="fields_options submit_field"> <a href="#" class="edit_btn" title="<?php _e("Edit Button","pie-register"); ?>"></a>
              <input id="reset_btn" disabled="disabled" name="fields[reset]" type="reset" class="submit_btn" value="<?php echo esc_attr($data['submit']['reset_text'])?>" />
              <input disabled="disabled" name="fields[submit]" type="submit" class="submit_btn" value="<?php echo esc_attr($data['submit']['text'])?>" />
              <input name="field[submit][label]" value="Submit"  type="hidden" />
              <input name="field[submit][type]" value="submit" type="hidden" />
              <input name="field[submit][remove]" value="0" type="hidden" />
              <input name="field[submit][meta]" value="0" type="hidden">
            </div>
            <div class="fields_main">
              <div class="advance_options_fields advance_options_submit">
                <div class="advance_fields">
                  <label>
                    <?php _e("Submit Button Text","pie-register"); ?>
                  </label>
                  <input type="text" class="input_fields" name="field[submit][text]" value="<?php echo esc_attr($data['submit']['text'])?>">
                </div>
                <div class="advance_fields">
                  <label>
                    <?php _e("Show Reset Button","pie-register"); ?>
                  </label>
                  <select onchange="showHideReset();" id="show_reset" class="swap_reset" name="field[submit][reset]">
                    <option <?php if($data['submit']['reset']=='0') echo 'selected="selected"';?> value="0">
                    <?php _e("No","pie-register"); ?>
                    </option>
                    <option <?php if($data['submit']['reset']=='1') echo 'selected="selected"';?> value="1">
                    <?php _e("Yes","pie-register"); ?>
                    </option>
                  </select>
                </div>
                <div class="advance_fields reset-button-text">
                  <label>
                    <?php _e("Reset Button Text","pie-register"); ?>
                  </label>
                  <input type="text" class="input_fields" name="field[submit][reset_text]" value="<?php echo esc_attr($data['submit']['reset_text'])?>">
                </div>
                <div class="advance_fields">
                  <label>
                    <?php _e("Confirmation Message","pie-register"); ?>
                  </label>
                  <div class="radio_fields">
                    <input class="reg_success" type="radio" value="text" name="field[submit][confirmation]" <?php checked($data['submit']['confirmation']=='text', true, true);?>>
                    <label>
                      <?php _e("Text","pie-register"); ?>
                    </label>
                    <input class="reg_success" type="radio" value="page" name="field[submit][confirmation]" <?php checked($data['submit']['confirmation']=='page', true, true);?>>
                    <label>
                      <?php _e("Page","pie-register"); ?>
                    </label>
                    <input class="reg_success" type="radio" value="redirect" name="field[submit][confirmation]" <?php checked($data['submit']['confirmation']=='redirect', true, true);?>>
                    <label>
                      <?php _e("Redirect","pie-register"); ?>
                    </label>
                  </div>
                </div>
                <div class="advance_fields submit_meta submit_meta_redirect">
                  <label>
                    <?php _e("Redirect URL","pie-register"); ?>
                  </label>
                  <input type="text" class="input_fields" name="field[submit][redirect_url]" value="<?php echo esc_attr($data['submit']['redirect_url'])?>">
                </div>
                <div class="advance_fields submit_meta submit_meta_page">
                  <label>
                    <?php _e("Select Page","pie-register"); ?>
                  </label>
                  <?php  $args =  array("name"=>"field[submit][page]","selected"=>$data['submit']['page']);wp_dropdown_pages( $args ); ?>
                </div>
                <div class="advance_fields submit_meta submit_meta_text">
                  <label>
                    <?php _e("Registration Success Message","pie-register"); ?>
                  </label>
                  <textarea name="field[submit][message]" rows="8" cols="16"><?php echo esc_textarea($data['submit']['message']); ?></textarea>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <?php
	  	if($this->check_enable_payment_method() == "true")
		{
			?>
        <ul id="paypal_button">
          <li class="fields">
            <?php do_action("show_icon_payment_gateway"); ?>
          </li>
        </ul>
        <?php
		}
    ?>
      <div class="manage-form-btn">
          <input type="submit" class="button button-primary button-large" name="pie_form"  value="<?php _e("Save Settings",'pie-register');?>">
      </div>
      </form>
    </div>
    <div class="right_menu">
      <div id="hint_0" style="top: 135px;margin-left: -271px;position: fixed;float:right;" class="fields_hint"> <img src="<?php echo esc_url(PIEREG_PLUGIN_URL. 'assets/images/right_arrow.jpg') ?>" width="45" height="26" align="right">
        <div class="hint_content">
          <h4>
            <?php _e("Did you know?","pie-register"); ?>
          </h4>
          <span>
          <?php _e("You can drag and drop fields.","pie-register"); ?>
          </span> <br>
          <input type="button" class="thanks" value="<?php _e("OK","pie-register"); ?>">
        </div>
      </div>
      <ul>
        <li id="default_fields"><a class="right_menu_heading" href="javascript:;">
          <?php _e("Default Fields","pie-register"); ?>
          </a>
          <ul class="controls picker pie-content-ul"  id="content_1">
            <li class="standard_name"><a name="username" class="default" href="javascript:;">
              <?php _e("Username","pie-register"); ?>
              </a></li>
            <li class="standard_name"><a name="name" class="default" href="javascript:;">
              <?php _e("Name","pie-register"); ?>
              </a></li>
            <li class="standard_website"><a name="url" class="default" href="javascript:;">
              <?php _e("Website","pie-register"); ?>
              </a></li>
            <li class="standard_aim"><a name="aim" class="default" href="javascript:;">
              <?php _e("AIM","pie-register"); ?>
              </a></li>
            <li class="standard_yahoo"><a name="yim" class="default" href="javascript:;">
              <?php _e("Yahoo IM","pie-register"); ?>
              </a></li>
            <li class="standard_google"><a name="jabber" class="default" href="javascript:;">
              <?php _e("Jabber / Google Talk","pie-register"); ?>
              </a></li>
            <li class="standard_about"><a name="description" class="default" href="javascript:;">
              <?php _e("About Yourself","pie-register"); ?>
              </a></li>
          </ul>
        </li>
        <li id="standard_fields"><a class="right_menu_heading" href="javascript:;">
          <?php _e("Standard Fields","pie-register"); ?>
          </a>
          <ul class="controls picker pie-content-ul"  id="content_2">
            <li class="standard_text"><a name="text" href="javascript:;">
              <?php _e("Text Field","pie-register"); ?>
              </a></li>
            <li class="standard_textarea"><a name="textarea" href="javascript:;">
              <?php _e("Text Area","pie-register"); ?>
              </a></li>
            <li class="standard_dropdown"><a name="dropdown" href="javascript:;">
              <?php _e("Drop Down","pie-register"); ?>
              </a></li>
            <li class="standard_multiselect"><a name="multiselect" href="javascript:;">
              <?php _e("Multi Select","pie-register"); ?>
              </a></li>
            <li class="standard_numbers"><a name="number" href="javascript:;">
              <?php _e("Number","pie-register"); ?>
              </a></li>
            <li class="standard_checkbox"><a name="checkbox" href="javascript:;">
              <?php _e("Checkbox","pie-register"); ?>
              </a></li>
            <li class="standard_radio"><a name="radio" href="javascript:;">
              <?php _e("Radio Buttons","pie-register"); ?>
              </a></li>
            <li class="standard_hidden"><a name="hidden" href="javascript:;">
              <?php _e("Hidden Field","pie-register"); ?>
              </a></li>
            <li class="standard_html"><a name="html" href="javascript:;">
              <?php _e("HTML Script","pie-register"); ?>
              </a></li>
            <li class="standard_selection"><a name="sectionbreak" href="javascript:;">
              <?php _e("Section Break","pie-register"); ?>
              </a></li>
            <li class="standard_pagebreak"><a name="pagebreak" href="javascript:;">
              <?php _e("Page Break","pie-register"); ?>
              </a></li>
          </ul>
        </li>
        <li id="advanced_fields"><a class="right_menu_heading" href="javascript:;">
          <?php _e("Advanced Fields","pie-register"); ?>
          </a>
          <ul class="controls picker pie-content-ul"  id="content_3">
            <li class="standard_address"><a name="address" href="javascript:;">
              <?php _e("Address","pie-register"); ?>
              </a></li>
            <li class="standard_date"><a name="date" href="javascript:;">
              <?php _e("Date","pie-register"); ?>
              </a></li>
            <li class="standard_time"><a name="time" href="javascript:;">
              <?php _e("Time","pie-register"); ?>
              </a></li>
            <li class="standard_phone"><a name="phone" href="javascript:;">
              <?php _e("Phone","pie-register"); ?>
              </a></li>
            <?php 
          include_once( $this->admin_path . 'includes/plugin.php' );
		  $twilio_option = get_option("pie_register_twilio");
		  $plugin_status = get_option('piereg_api_manager_addon_Twilio_activated');
		  if( is_plugin_active("pie-register-twilio/pie-register-twilio.php") && isset($twilio_option["enable_twilio"]) && $twilio_option["enable_twilio"] == 1 && $plugin_status == "Activated" ){ ?>
            <li class="standard_twoway_phone"><a name="two_way_login_phone" class="default" href="javascript:;">
              <?php _e("2Way Login Phone #","pie-register"); ?>
              </a></li>
            <?php 
		  } ?>
            <li class="standard_upload"><a name="upload" href="javascript:;">
              <?php _e("Upload File","pie-register"); ?>
              </a></li>
            <li class="standard_profile"><a name="profile_pic" class="default" href="javascript:;">
              <?php _e("Profile Picture","pie-register"); ?>
              </a></li>
            <li class="standard_list"><a name="list" href="javascript:;">
              <?php _e("List","pie-register"); ?>
              </a></li>
            <li class="standard_custom_role"><a name="custom_role" class="default" href="javascript:;">
              <?php _e("Custom User Role","pie-register"); ?>
              </a></li>
            <?php if( $button['enable_paypal'] == 1 || PieRegister::check_payment_plugin_activation() == "true" ): ?>
            <li class="standard_pricing"><a name="pricing" class="default" href="javascript:;">
              <?php _e("Membership","pie-register"); ?>
              </a></li>
            <?php endif; ?>
            <?php if($button['enable_invitation_codes']==1) { ?>
            <li class="standard_invitation"><a name="invitation" class="default" href="javascript:;">
              <?php _e("Invitation Codes","pie-register"); ?>
              </a></li>
            <?php } ?>
            <li class="standard_terms"><a name="terms" class="default" href="javascript:;">
              <?php _e("Terms &amp; Conditions","pie-register"); ?>
              </a></li>
            <li class="standard_captcha_n">
              <a name="captcha" class="default" href="javascript:;">
                <?php _e("Re-Captcha","pie-register"); ?>
              </a>
            </li>
            <li class="standard_captcha"><a name="math_captcha" class="default" href="javascript:;">
              <?php _e("Math Captcha","pie-register"); ?>
              </a></li>
          </ul>
        </li>
        <?php 
        //pie-register-woocommerce addon
          if ($this->woocommerce_and_piereg_wc_addon_active)
          {
              $woocommerce_fields_tab = apply_filters("pieregister_print_woocommerce_fields_tab", 0); 
              echo wp_kses($woocommerce_fields_tab,$this->piereg_forms_get_allowed_tags());
          }
        ?>
      </ul>
    </div>
  </div>
</div>