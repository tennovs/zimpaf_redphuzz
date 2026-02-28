<?php $piereg = PieReg_Base::get_pr_global_options(); ?>
<?php
$pie_user_email_types 	= get_option( 'pie_user_email_types' );
$replacement_fields = "";		
$woocommerce_fields = "";	   	
$fields = maybe_unserialize(get_option("pie_fields"));

if( (is_array($fields) || is_object($fields)) && sizeof($fields ) > 0 )
{
	
	foreach($fields as $pie_fields)	
	{
		switch($pie_fields['type']) :
		case 'default' :
		case 'form' :					
		case 'submit' :
		case 'username' :
		case 'email' :
		case 'password' :
		case 'name' :
		case 'pagebreak' :
		case 'sectionbreak' :
		case 'html' :
		case 'hidden' :
		case 'captcha' :
		case 'math_captcha' :
		continue 2;
		break;
		endswitch;						

    if($pie_fields['type'] == "invitation")
    {
      $meta_key = "invitation_code";
    }
    elseif($pie_fields['type'] == "custom_role")
    {
      $meta_key = "custom_role";
    }
    elseif($pie_fields['type'] == "wc_billing_address")
    {
      $meta_key = "wc_billing_address";
      if( empty($pie_fields['label']) ) 
      {
        $pie_fields['label'] = "WC Billing Address";
      }
    }
    elseif($pie_fields['type'] == "wc_shipping_address") 
    {
      $meta_key = "wc_shipping_address";
      if( empty($pie_fields['label']) ) 
      {
        $pie_fields['label'] = "WC Shipping Address";
      }
    }
    else
    {
      $meta_key	= "pie_".$pie_fields['type']."_".$pie_fields['id'];
    }
    
    if ($pie_fields['type'] == "wc_billing_address" || $pie_fields['type'] == "wc_shipping_address")
    {
      $woocommerce_fields .= '<option value="%'.esc_attr($meta_key).'%">'.esc_html($pie_fields['label']).'</option>';
    }
    else
    {
      $replacement_fields .= '<option value="%'.esc_attr($meta_key).'%">'.esc_html($pie_fields['label']).'</option>';
    }
  }
}
?>

<div class="right_section">
          <div class="notifications">
            <form method="post" action="#piereg_user_notification">
            <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_user_email_notification','piereg_user_email_notification'); ?>
            <div class="notification-accordion">
              <?php foreach ($pie_user_email_types as $val=>$type) { 
                if($val == 'user_renew_temp_blocked_account_notice') continue;
                ?>
              <div class="notification-item">
                <div class="notification-item-toggler">
                  <p><?php _e(str_replace('.','',$type) ,"pie-register"); ?></p>
                  <img src="<?php echo esc_url(PIEREG_PLUGIN_URL . 'assets/images/cog-icon.png');?>" alt="">
                </div>
                <div class="content clearfix">
                  <ul class="clearfix">
                  <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                          <div class="fields">
                            <label style="width:auto;margin-right:20px;"><?php _e("Enable",'pie-register');?></label>
                              <div class="radio_fields">
                                  <input type="checkbox" id="<?php echo 'user_enable_'.esc_attr($val); ?>" name="<?php echo 'user_enable_'.esc_attr($val); ?>" value="1" <?php checked($piereg['user_enable_'.$val] == "1", true, true); ?>>
                              </div>
                          </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                      <div class="fields">
                        <label><?php _e("From Name",'pie-register') ?></label>
                        <input name="user_from_name_<?php echo esc_attr($val)?>" value="<?php echo esc_attr($piereg['user_from_name_'.$val])?>" type="text" class="input_fields2" />
                      </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                      <div class="fields">
                        <label><?php _e("From Email",'pie-register') ?></label>
                        <input name="user_from_email_<?php echo esc_attr($val)?>" value="<?php echo esc_attr($piereg['user_from_email_'.$val])?>" type="text" class="input_fields2" />
                      </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                      <div class="fields">
                        <label><?php _e("Reply To",'pie-register') ?></label>
                        <input name="user_to_email_<?php echo esc_attr($val)?>" value="<?php echo esc_attr($piereg['user_to_email_'.$val])?>" type="text" class="input_fields2" />
                      </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                      <div class="fields">
                        <label><?php _e("Subject",'pie-register') ?></label>
                        <input name="user_subject_email_<?php echo esc_attr($val)?>" value="<?php echo esc_attr($piereg['user_subject_email_'.$val])?>" type="text" class="input_fields" />
                        <div class="pie_wrap_keys">                                
                            <strong><?php _e("Use tags in subject field","pie-register"); ?>:</strong>
                            <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_1')" id="piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_1" readonly="readonly">%user_login%</span>
                            <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_2')" id="piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_2" readonly="readonly">%user_email%</span>
                            <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_3')" id="piereg-select-all-text-onclick_<?php echo esc_attr($val)?>_3" readonly="readonly">%blogname%</span>
                        </div>
                      </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                          <div class="fields">
                            <label style="width:auto;margin-right:20px;"><?php _e("Email Format: HTML Text",'pie-register');?></label>
                              <div class="radio_fields">
                                  <input type="radio" id="<?php echo 'user_formate_email_'.esc_attr($val); ?>_yes" name="<?php echo 'user_formate_email_'.esc_attr($val); ?>" value="1" <?php checked( $piereg['user_formate_email_'.$val] == "1", true, true); ?>>
                                  <label for="<?php echo 'user_formate_email_'.esc_attr($val); ?>_yes" style="float:none;"><?php _e("Yes",'pie-register');?></label>
                                  &nbsp;&nbsp;
                                  <input type="radio" id="<?php echo 'user_formate_email_'.esc_attr($val); ?>_no" name="<?php echo 'user_formate_email_'.esc_attr($val); ?>" value="0" <?php checked($piereg['user_formate_email_'.$val] == "0", true, true); ?>>
                                  <label for="<?php echo 'user_formate_email_'.esc_attr($val); ?>_no" style="float:none;"><?php _e("No",'pie-register');?></label>
                              </div>
                          </div>
                    </li>
                    <li class="<?php echo esc_attr($val)?> hide-div clearfix">
                      <div class="fields">
                          <label style="font-size:12px;margin-bottom:10px;font-size:14px;"><i><?php _e("Message: Enter a message below to send notification emails to users when a condition is met.",'pie-register') ?></i></label>    
                          <p>
                            <label><?php _e("Replacement Keys","pie-register");?>:</label>
                          <select class="piereg_replacement_keys" name="replacement_keys<?php echo esc_attr($val)?>" id="replacement_keys<?php echo esc_attr($val)?>">
                              <option value="select"><?php _e("Select",'pie-register') ?></option>
                              <optgroup label="<?php _e("Default Fields",'pie-register') ?>">
                                  <option value="%user_login%"><?php _e("User Name",'pie-register') ?></option>
                                  <option value="%user_email%"><?php _e("User E-mail",'pie-register') ?></option>
                                  <option value="%firstname%"><?php _e("User First Name",'pie-register') ?></option>
                                  <option value="%lastname%"><?php _e("User Last Name",'pie-register') ?></option>
                                  <option value="%user_url%"><?php _e("User URL",'pie-register') ?></option>
                                  <option value="%user_aim%"><?php _e("User AIM",'pie-register') ?></option>
                                  <option value="%user_yim%"><?php _e("User YIM",'pie-register') ?></option>
                                  <option value="%user_jabber%"><?php _e("User Jabber",'pie-register') ?></option>
                                  <option value="%user_biographical_nfo%"><?php _e("User Biographical Info",'pie-register') ?></option>
                                  <option value="%user_registration_date%"><?php _e("User Registration Date",'pie-register') ?></option>
                              </optgroup>
                              <optgroup label="<?php _e("Custom Fields",'pie-register') ?>">
                                  <?php echo wp_kses($replacement_fields,$this->piereg_forms_get_allowed_tags()); ?>
                              </optgroup>
                              <optgroup label="<?php _e("WooCommerce Fields",'pie-register') ?>">
                                  <?php echo wp_kses($woocommerce_fields,$this->piereg_forms_get_allowed_tags()); ?>
                              </optgroup>
                              <optgroup label="<?php _e("Other",'pie-register') ?>">
                                  <option value="%user_ip%"><?php _e("User IP",'pie-register') ?></option>
                                  <option value="%user_new_email%"><?php _e("User New E-mail",'pie-register') ?></option>
                                  <option value="%user_last_date%"><?php _e("User Last Date",'pie-register') ?></option>
                                  <option value="%blogname%"><?php _e("Blog Name",'pie-register') ?></option>
                                  <option value="%siteurl%"><?php _e("Site URL",'pie-register') ?></option>
                                  <option value="%blogname_url%"><?php _e("Blog Name With Site URL",'pie-register') ?></option>
                                  <option value="%reset_password_url%"><?php _e("Reset Password URL",'pie-register') ?></option>
                                  <option value="%activationurl%"><?php _e("User Activation URL",'pie-register') ?></option>
                                  <option value="%reset_email_url%"><?php _e("Reset Email URL",'pie-register') ?></option>
                                  <option value="%confirm_current_email_url%"><?php _e("Confirm Current Email URL",'pie-register') ?></option>
                                  <option value="%pending_payment_url%"><?php _e("Pending Payment URL",'pie-register') ?></option>
                              </optgroup>
                          </select>
                          </p>
                          <?php  
                              $settings = array( 'textarea_name' => "user_message_email_".$val, 'editor_height' => 220);
                              $textarea_text = $piereg['user_message_email_'.$val];
                              wp_editor($textarea_text, 'piereg_text_editor_'.$val, $settings );
                          ?>            
                        <div class="piereg_clear"></div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <?php } ?>
            </div>
              <input name="action" value="pie_reg_update" type="hidden" />
              <input type="hidden" name="user_email_notification_page" value="1" />
              <p class="submit btnvisibile">
                <input name="Submit" class="notify-submit-btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
              </p>
            </form>
      		<?php 
              $old_ver_options = get_option("pie_register");
              if( (isset($old_ver_options['adminvmsg']) && $old_ver_options['adminvmsg'] != "")  || (isset($old_ver_options['emailvmsg']) && $old_ver_options['emailvmsg'] != "") || isset($old_ver_options['msg']) )
              {
      		?>
                  <div class="fields">
                      <form method="post">
                      	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_old_version_import','piereg_old_version_import'); ?>
                          <label><?php _e("Click here to import version 1.x email template","pie-register"); ?></label>                
                          <p class="submit"><input name="import_email_template_from_version_1" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px;margin-top: 15px;" value=" <?php _e('Import email template','pie-register');?> " type="submit" /></p>
                          <input type="hidden" name="old_version_import" value="yes" />
                      </form>
                  </div>
      		<?php
              }
              unset($old_ver_options);
      		?>
          </div>
        </div>