<?php $piereg = PieReg_Base::get_pr_global_options(); ?>
            <div class="right_section">
                <div class="notifications">
                  <form method="post" action="#piereg_admin_notification">
                    <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_admin_email_notification','piereg_admin_email_notification'); ?>
                    <ul>
                      <li>
                        <div class="fields">
                          <input name="enable_admin_notifications" <?php checked($piereg['enable_admin_notifications'] == "1", true, true); ?> type="checkbox" class="checkbox" value="1" />
                          <?php _e("Enable email notifications to administrator",'pie-register');?>   
                          <label style="font-size:12px;margin-bottom:10px;margin-top:10px;font-size:14px;"><i><?php _e("Note: If the default WP Login page is used, Email verification links will not work.",'pie-register');?></i></label>           
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          <label><?php _e("Send To Email(s)*",'pie-register');?></label>
                          <textarea name="admin_sendto_email" class="textarea_fields" rows="6"><?php echo esc_textarea($piereg['admin_sendto_email'])?></textarea>
                          <p style="font-size:13px;margin-top:0;"><?php _e("comma seperated for multiple emails. e.g. someone@example.com,someoneelse@example.com",'pie-register');?></p>
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          <label><?php _e("From Name",'pie-register');?></label>
                          <input name="admin_from_name" value="<?php echo esc_attr($piereg['admin_from_name'])?>" type="text" class="input_fields2" />
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          <label><?php _e("From Email",'pie-register');?></label>
                          <input name="admin_from_email" value="<?php echo esc_attr($piereg['admin_from_email'])?>" type="text" class="input_fields2" />
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          <label><?php _e("Reply To",'pie-register');?></label>
                          <input name="admin_to_email" value="<?php echo esc_attr($piereg['admin_to_email'])?>" type="text" class="input_fields2" />
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          <label><?php _e("BCC",'pie-register');?></label>
                          <input  name="admin_bcc_email" value="<?php echo esc_attr($piereg['admin_bcc_email'])?>" type="text" class="input_fields" />
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                          	<label><?php _e("Subject",'pie-register');?></label>
                          	<input name="admin_subject_email" id="admin_subject_email" value="<?php echo esc_attr($piereg['admin_subject_email'])?>" type="text" class="input_fields" />
                        	<div class="pie_wrap_keys">                                
                                <strong><?php _e("Use tags in subject field","pie-register"); ?>:</strong>
                                <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_1')" id="piereg-select-all-text-onclick_1" readonly="readonly">%user_login%</span>
                                <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_2')" id="piereg-select-all-text-onclick_2" readonly="readonly">%user_email%</span>
                                <span class="style_textarea" onclick="selectText('piereg-select-all-text-onclick_3')" id="piereg-select-all-text-onclick_3" readonly="readonly">%blogname%</span>
                            </div>
                        </div>
                      </li>
                      <li>
                        <div class="fields flex-format">
                          <div class="radio_fields">
                            	<input type="checkbox" name="admin_message_email_formate" id="admin_message_email_formate" value="1" <?php checked($piereg['admin_message_email_formate'] == "1", true, true); ?> />	
                          </div>
                          <label class="labelaligned"><?php _e("Email HTML Format",'pie-register');?></label>
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                        <label style="font-size:12px;margin-bottom:10px;font-size:14px;"><i><?php _e("Message: Enter a message below to receive notification email when new users register.",'pie-register');?></i></label>
                        <label style="font-size:12px;margin-bottom:10px;font-size:14px;"><i><?php _e("Note: If the default WP Login page is used, Admin and Email verifications links will not work.",'pie-register');?></i></label>
                        <p>
                        <label><?php _e("Replacement Keys","pie-register"); ?>:</label>
                        <?php
                            $fields = maybe_unserialize(get_option("pie_fields"));
                            $replacement_fields = '';
                            $woocommerce_fields = '';	   	
                            if( (is_array($fields) || is_object($fields)) && sizeof($fields) > 0 )
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
                                    case 'hidden' :
                                    case 'html' :
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
                                          $pie_fields['label'] = "Billing Address";
                                        }
                                    }
                                    elseif($pie_fields['type'] == "wc_shipping_address")
                                    {
                                        $meta_key = "wc_shipping_address";
                                        if( empty($pie_fields['label']) ) 
                                        {
                                          $pie_fields['label'] = "Shipping Address";
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
                                      $replacement_fields .= '<option value="%'.esc_attr($meta_key).'%">'.ucwords(esc_html($pie_fields['label'])).'</option>';
                                    }
                                }
                            }
                            ?>
                            <select class="piereg_replacement_keys" name="replacement_keys" id="replacement_keys">
                                <option value="select"><?php _e('Select','pie-register');?></option>
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
                                    <option value="%blogname%"><?php _e("Blog Name",'pie-register') ?></option>
                                    <option value="%siteurl%"><?php _e("Site URL",'pie-register') ?></option>
                                    <option value="%verificationurl%"><?php _e("Verification URL",'pie-register') ?></option> <!-- task duplicate form -->
                                    <option value="%blogname_url%"><?php _e("Blog Name With Site URL",'pie-register') ?></option>
                                    <option value="%user_ip%"><?php _e("User IP",'pie-register') ?></option>
                                </optgroup>
                            </select>
                           </p>
                          <?php  
                              $settings = array( 'textarea_name' => "admin_message_email");
                              $textarea_text = $piereg['admin_message_email'];
                              wp_editor($textarea_text, 'piereg_text_editor', $settings );
                          ?>  
                          <div class="piereg_clear"></div>
                        </div>
                      </li>
                      <li>
                        <div class="fields">
                            <input name="action" value="pie_reg_update" type="hidden" />
                            <input type="hidden" name="admin_email_notification_page" value="1" />
                            <p class="submit"><input style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: -15px;margin-right:0px;min-width: 113px;float:right;" class="submit_btn" name="Submit" value="<?php _e('Save Changes','pie-register');?>" type="submit" /></p>
                        </div>
                      </li>
                    </ul>
                  </form>
                </div>
              </div>