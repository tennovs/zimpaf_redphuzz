<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
$piereg = PieReg_Base::get_pr_global_options();

$paypal_live = $paypal_sandbox = false;
if($piereg['paypal_sandbox'] == "no"){
	$paypal_live 	= true;
} elseif ($piereg['paypal_sandbox'] == "yes" || !$piereg['paypal_sandbox']) {
	$paypal_sandbox = true;   
} 
				  
?>
<div class="pieregister-admin" >
<div id="payment_gateway_tabs" class="hideBorder">
    <div class="settings pad_bot_none">
        <h2 class="headingwidth"><?php _e("Payment Gateways",'pie-register') ?></h2>
        <?php 
		if(isset($this->pie_post_array['notice']) && $this->pie_post_array['notice'] ){
			echo '<div id="message" class="updated fade msg_belowheading"><p><strong>' . esc_html($this->pie_post_array['notice']) . '.</strong></p></div>';
        }else if( (isset($_POST['notice']) && $_POST['notice']) ){
			echo '<div id="message" class="updated fade msg_belowheading"><p><strong>' . esc_html($_POST['notice']) . '</strong></p></div>';
		}else if( isset($this->pie_post_array['error']) && !empty($this->pie_post_array['error']) ){
			echo '<div id="error" class="error fade msg_belowheading"><p><strong>' . esc_html($this->pie_post_array['error']) . '</strong></p></div>';
		}
		?>
        <div class="tabOverwrite">
            <div id="tabsSetting" class="tabsSetting">            
                <ul class="tabLayer1">
                    <li><a href="#piereg_general_settings_payment_gateway"><?php _e("General Settings","pie-register") ?></a></li><!--Add General Settings Menu-->
                    <li><a href="#piereg_paypal_payment_gateway"><?php _e("PayPal Standard","pie-register") ?></a></li><!--Add Paypal-->
                    <?php //pie_register_Authorize_Net_paymentgateways_menus
                        do_action('pie_register_payment_setting_menus'); //<!--for Authorize.Net-->
                    ?>
                    <li><a href="#piereg_payment_log"><?php _e("Payment Log","pie-register") ?></a></li><!--Add Payment Log-->
                </ul>
            </div>
        </div>
    </div>
    <!-- start Paypal pament gateway -->
    <div id="piereg_paypal_payment_gateway">
        <div id="container">
          <div class="right_section">
            <div class="settings">
              <?php echo '<a href="http://www.paypal.com/payments-standard" target="_blank"><img class="logo-payment-align" src="'.esc_url($this->plugin_url."/assets/images/paypal-standard-logo.png").'" /></a>'; ?>
              <div id="pie-register">
              	<form method="post" action="#piereg_paypal_payment_gateway" enctype="multipart/form-data">
                <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_payment_gateway_page_nonce','piereg_payment_gateway_page_nonce'); ?>
                <div class="fields">
                  <div class="radio_fields">
                  	<input type="checkbox" name="enable_paypal" id="enable_paypal" value="1" <?php checked($piereg['enable_paypal']=="1", true, true); ?> />
                  </div>
                  <label for="enable_paypal" class="label_mar_top"><?php _e("Enable PayPal Standard","pie-register"); ?></label>
                </div>
                <div class="fields">
                  <label for="paypal_butt_id"><?php _e('PayPal Hosted Button ID', 'pie-register');?></label>
                  <input type="text" name="piereg_paypal_butt_id" class="input_fields" id="paypal_butt_id" value="<?php echo esc_attr($piereg['paypal_butt_id']);?>" />
                </div>
                <div class="fields">
                  <label for="paypal_sandbox">
                    <?php _e('Paypal Mode', 'pie-register');?>
                  </label>
                  <select name="piereg_paypal_sandbox" id="paypal_sandbox">
                    <option <?php selected($paypal_live, true, true); ?> value="no"><?php _e('Live', 'pie-register');?></option>
                    <option <?php selected($paypal_sandbox, true, true); ?> value="yes"><?php _e('Sandbox', 'pie-register');?></option>
                  </select>
                </div>
                <div class="fields fields_submitbtn">
                	  <input name="action" value="pie_reg_update" type="hidden" />
                	<input type="hidden" name="payment_gateway_page" value="1" />
                    <input name="Submit" class="submit_btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
                  </div>
                <h3><?php _e("Instructions","pie-register"); ?></h3>
                <div style="width:1px;height:20px;"></div>
                <div class="fields">
                <p><strong>
                  <?php _e('Please follow the steps below to create a PayPal payment button.', 'pie-register');?>
                  </strong></p>
                <ol>
                <li><?php _e("Login to your","pie-register"); ?> <a target="_blank" href="https://www.paypal.com/"><?php _e("Paypal account","pie-register"); ?></a>.</li>
                <li><?php _e("Go to Merchant Services and Click on","pie-register"); ?> <a target="_blank" href="https://www.paypal.com/ae/cgi-bin/webscr?cmd=_web-tools"><?php _e("Buy Now","pie-register"); ?></a> <?php _e("button","pie-register"); ?>.</li>
                <li><?php _e("Give your Button a name. i.e: Website Access fee and set the price.","pie-register"); ?></li>
                <li><?php _e('Click on Step3: Customize advance features (optional) Tab, select "Add advanced variables" checkbox and add the following snippet',"pie-register"); ?>:

<textarea readonly="readonly" onfocus="this.select();" onclick="this.select();" onkeypress="this.select();" style="height:100px;min-height:auto;" >rm=2<?php echo "\r\n"; ?>
notify_url=<?php echo ''.trailingslashit(get_bloginfo("url")).'';?>?action=ipn_success<?php echo "\r\n"; ?>
cancel_return=<?php echo ''.trailingslashit(get_bloginfo("url")).'';?>?action=payment_cancel<?php echo "\r\n"; ?>
return=<?php echo ''.trailingslashit(get_bloginfo("url")).'' ;?>?action=payment_success</textarea>

                  
                </li>
                <li><?php _e("Click Create button, On the next page, you will see the generated button code snippet like the following","pie-register"); ?>:
                    <xmp style="cursor:text;width:100%;white-space:pre-line; margin:0;">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                          <input type="hidden" name="cmd" value="_s-xclick">
                          <input type="hidden" name="hosted_button_id" value="XXXXXXXXXX">
                          <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                          <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                        </form>
                    </xmp>
                </li>
                <li><?php _e("Copy the snippet into any text editor, extract and put the hosted_button_id value (XXXXXXXXXX) into the Above Field.","pie-register"); ?></li>
                <li><?php _e("Save Changes, You're done!","pie-register"); ?></li>
                </ol>
              </div>
              </form>
            </div>
          </div>
        </div>
        </div>
    </div>
    <!--End Paypal-->
    
    <!-- start pament gateway General Settings page-->
    <div id="piereg_general_settings_payment_gateway" style="display:inline-block;">
        <div id="container">
            <div class="right_section">
                <div class="settings">
                    <div id="pie-register">
                       <form method="post" action="#piereg_general_settings_payment_gateway">
                       		<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_payment_gateway_settings_nonce','piereg_payment_gateway_settings_nonce'); ?>
                            <h3><?php _e("Messages",'pie-register'); ?></h3>
                            <!--	Payment success Message	-->
                            <div class="fields">
                                <label for="payment_success_msg"><?php _e('Payment Success', 'pie-register');?></label>
                                <input type="text" class="input_fields" name="payment_success_msg" id="payment_success_msg" value="<?php echo ((isset($piereg['payment_success_msg']) && !empty($piereg['payment_success_msg']))?esc_attr($piereg['payment_success_msg']):__("Payment was successful.","pie-register"));?>" />
                            </div>
                            <!--	Payment Failed Message	-->
                            <div class="fields">
                                <label for="payment_faild_msg"><?php _e('Payment Failed', 'pie-register');?></label>
                                <input type="text" class="input_fields" name="payment_faild_msg" id="payment_faild_msg" value="<?php echo ((isset($piereg['payment_faild_msg']) && !empty($piereg['payment_faild_msg']))?esc_attr($piereg['payment_faild_msg']):__("Payment failed.","pie-register"));?>" />
                            </div>
                            <!--	Renew Account Message	-->
                            <div class="fields">
                                <label for="payment_renew_msg"><?php _e('Reactivate Account', 'pie-register');?></label>
                                <input type="text" class="input_fields" name="payment_renew_msg" id="payment_renew_msg" value="<?php echo ((isset($piereg['payment_renew_msg']) && !empty($piereg['payment_renew_msg']))?esc_attr($piereg['payment_renew_msg']):__("Account needs to be activated.","pie-register"));?>" />
                            </div>
                            <!--	Alreact Activate Message	-->
                            <div class="fields">
                                <label for="payment_already_activate_msg"><?php _e('Already Active', 'pie-register');?></label>
                                <input type="text" class="input_fields" name="payment_already_activate_msg" id="payment_already_activate_msg" value="<?php echo ((isset($piereg['payment_already_activate_msg']) && !empty($piereg['payment_already_activate_msg']))?esc_attr($piereg['payment_already_activate_msg']):__("Account is already active.","pie-register"));?>" />
                            </div>
                            
			                <input name="action" value="pie_reg_update" type="hidden" />
                            <input type="hidden" name="payment_gateway_general_settings" value="1" />
                            <!-- style="background:#0C6;"-->
                            <div class="fields fields_submitbtn">
                                <input name="Submit" class="submit_btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End General Settings-->
	
	<?php
		do_action("pie_register_Authorize_Net_paymentgateways");//Depricate
		do_action("pie_register_PaymentGateways");//Get Payment Gateways Page
    ?>

	 <!-- start pament log page-->
    <div id="piereg_payment_log" style="display:inline-block;">
    	<div id="pie-register-payment-log">
            <div class="settings" style="margin: 0px;width: 99%;">
               <div class="piereg-payment-log-area">
                	<table class="wp-list-table widefat fixed tableexamples piereg-payment-log-table">
                    	<thead>
                            <tr>
                                <th><?php _e("User Email","pie-register"); ?></th>
                                <th><?php _e("Method","pie-register"); ?></th>
                                <th><?php _e("Type","pie-register"); ?></th>
                                <th><?php _e("Date","pie-register"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
						$data = get_option("piereg_payment_log_option");
						$x = 0;
						if(!empty($data) && is_array($data)){
							
							usort($data, function( $a, $b ) {
								return strtotime($b["date"]) - strtotime($a["date"]);
							});
							
							foreach( $data as $k_data=>$v_data){?>
								<tr <?php echo ( ($x % 2)?'class="alternate"':'' ); ?> data-piereg-id="piereg-id-<?php echo esc_attr(md5( $v_data['date']." | " . $v_data['email'] )); ?>" >
									<td><?php echo esc_html($v_data['email']); ?></td>
									<td><?php echo esc_html($v_data['method']); ?></td>
									<td><?php echo esc_html($v_data['type']); ?></td>
									<td><?php echo esc_html($v_data['date']); ?></td>
								</tr>
								<tr style="display:none;" class="piereg-payment-log-desc piereg-id-<?php echo esc_attr(md5( $v_data['date']." | " . $v_data['email'] )); ?>" >
									<td colspan="4"><pre><?php print_r( $v_data['responce'] ); ?></pre></td>
								</tr>
							<?php 
							$x++;
							}
						}else{?>
							<tr class="piereg-payment-log-desc" >
                                <td colspan="4" align="center" ><?php _e("No Record Found","pie-register"); ?></td>
                            </tr>
						<?php } ?>
                        </tbody>
                        <tfoot>
                        	<tr>
                                <th><?php _e("User Email","pie-register"); ?></th>
                                <th><?php _e("Method","pie-register"); ?></th>
                                <th><?php _e("Type","pie-register"); ?></th>
                                <th><?php _e("Date","pie-register"); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="fields" style="width:99.9%;">
                	
                    <form action="#piereg_payment_log" method="post" onsubmit="return confirm('<?php _e("Are you sure you want to clear the payment log?","pie-register"); ?>');">
                    	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_payment_log','piereg_payment_log'); ?>
	                    <input name="piereg_delete_payment_log_file" style="margin:0;" class="submit_btn" value="<?php _e('Clear All','pie-register');?>" type="submit" />
                    </form>
                    
                    <form action="#piereg_payment_log" method="post">
                    	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_payment_log','piereg_payment_log'); ?>
	                    <input name="piereg_download_payment_log_file" style="margin:0;margin-right:10px;" class="submit_btn" value="<?php _e('Download','pie-register');?>" type="submit" />
                    </form>
                    
                </div>
             </div>
        </div>
    </div>
    <!--End General Settings-->

</div>
</div>