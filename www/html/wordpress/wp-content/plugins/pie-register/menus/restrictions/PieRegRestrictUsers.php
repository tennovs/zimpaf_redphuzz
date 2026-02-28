<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
  $piereg = PieReg_Base::get_pr_global_options(); 
	$_disable 			= true;
	$_available_in_pro 	= ' - <span style="color:red;">'. __("Available in premium version","pie-register") . '</span>';
?>

<div class="pieregister-admin">
  <div class="settings">
    <div id="blacklisted_tabs" class="hideBorder">
      <div class="tabOverwrite">
        <div id="tabsSetting" class="tabsSetting">
          <ul class="tabLayer1">
            <li><a href="#piereg_block_by_username">
              <?php _e("Block Users by Username","pie-register") ?>
              </a></li>
            <li><a href="#piereg_block_by_ip">
              <?php _e("Block Users by IP Address","pie-register") ?>
              </a></li>
            <li><a href="#piereg_block_by_email">
              <?php _e("Block Users by Email Address","pie-register") ?>
              </a></li>
          </ul>
        </div>
      </div>
      <div id="piereg_block_by_username">
        <div class="right_section">
          <div class="">
            <div class="pie-register-blocked-users">
              <form method="post" action="#piereg_block_by_username">
                <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_restrict_users', 'piereg_restrict_users'); ?>
                <div class="fields">
                  <div class="radio_fields">
                    <input <?php disabled($_disable, true, true); ?> id="enable_blockedusername" type="checkbox" name="enable_blockedusername" value="1" <?php checked(isset($piereg['enable_blockedusername']) && $piereg['enable_blockedusername'] == "1", true, true); ?>>
                  </div>
                  <label for="enable_blockedusername">
                    <?php _e("Do not allow users listed below to login or registration to my site.","pie-register"); ?>
                  </label>
                </div>
                <div class="fields">
                  <h3>
                    <?php _e("Usernames:","pie-register");?>
                  </h3>
                  <textarea <?php disabled($_disable, true, true); ?> id="piereg_blk_username" name="piereg_blk_username"><?php echo isset($piereg['piereg_blk_username']) ? esc_textarea($piereg['piereg_blk_username']) : ""; ?></textarea>
                  <div class="note_parent width_full flt_lft">
                    <div class="note"> <strong>
                      <?php _e("Note","pie-register");?>
                      :</strong>
                      <?php _e("For every single username
use new line","pie-register");?>. <span class="align_right"><strong>
                      <?php _e("Example","pie-register");?>
                      :</strong> johnny<br />
                      cruz<br />
                      downey*<br />
                      <?php _e("Use '*' to block username containg the string. Example, *zorro will block all users ending with the string zorro.","pie-register");?>
                      </span> </div>
                  </div>
                </div>
                <div class="fields fields_submitbtn">
                  <input name="action" value="pie_reg_update" type="hidden" />
                  <input type="hidden" name="restrict_user_by_username" value="1" />
                  <input <?php disabled($_disable, true, true); ?> name="Submit" class="submit_btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div id="piereg_block_by_ip">
        <div class="right_section">
          <div class="">
            <div class="pie-register-blocked-users">
              <form method="post" action="#piereg_block_by_ip">
                <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_restrict_users', 'piereg_restrict_users'); ?>
                <div class="fields">
                  <div class="radio_fields">
                    <input <?php disabled($_disable, true, true); ?> id="enable_blockedips" type="checkbox" name="enable_blockedips" value="1" <?php checked(isset($piereg['enable_blockedips']) && $piereg['enable_blockedips']=="1" , true, true) ?>>
                  </div>
                  <label for="enable_blockedips">
                    <?php _e("Do not allow login or registration from IP Addresses/Subnets listed below.","pie-register"); ?>
                  </label>
                </div>
                <div class="fields">
                  <h3>
                    <?php _e("IP Addresses","pie-register");?>
                  </h3>
                  <textarea <?php disabled($_disable, true, true); ?> id="piereg_blk_ip" name="piereg_blk_ip"><?php echo isset($piereg['piereg_blk_ip']) ? esc_textarea($piereg['piereg_blk_ip']) : ""; ?></textarea>
                  <div class="note_parent width_full flt_lft">
                    <div class="note"> <strong>
                      <?php _e("Note","pie-register");?>
                      :</strong>
                      <?php _e("Enter one IP address or subnet per line.","pie-register");?>. <span class="align_right"><strong>
                      <?php _e("Example","pie-register");?>
                      :</strong> <br />
                      192.168.1.1<br/>
                      192.168.2.0-24</span> </div>
                  </div>
                </div>
                <div class="fields fields_submitbtn">
                  <input name="action" value="pie_reg_update" type="hidden" />
                  <input type="hidden" name="restrict_user_by_ip" value="1" />
                  <input <?php disabled($_disable, true, true); ?> name="Submit" class="submit_btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div id="piereg_block_by_email">
        <div class="right_section">
          <div class="">
            <div class="pie-register-blocked-users">
              <form method="post" action="#piereg_block_by_email">
                <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_restrict_users', 'piereg_restrict_users'); ?>
                <div class="fields">
                  <div class="radio_fields">
                    <input <?php disabled($_disable, true, true); ?> id="enable_blockedemail" type="checkbox" name="enable_blockedemail" value="1" <?php checked(isset($piereg['enable_blockedemail']) && $piereg['enable_blockedemail']=="1", true, true) ?>>
                  </div>
                  <label for="enable_blockedemail">
                    <?php _e("Do not allow login or registration using email address listed below .","pie-register"); ?>
                  </label>
                </div>
                <div class="fields">
                  <h3>
                    <?php _e("Email Addresses:","pie-register");?>
                  </h3>
                  <textarea <?php disabled($_disable, true, true); ?> id="piereg_blk_email" name="piereg_blk_email"><?php echo isset($piereg['piereg_blk_email']) ? esc_textarea($piereg['piereg_blk_email']) : ""; ?></textarea>
                  <div class="note_parent width_full flt_lft">
                    <div class="note"> <strong>
                      <?php _e("Note","pie-register");?>
                      :</strong>
                      <?php _e("For every single email address
use new line","pie-register");?>. <span class="align_right"><strong>
                      <?php _e("Example","pie-register");?>
                      :</strong> some@example.com<br />
                      @domain.com*<br />
                      <?php _e("Give (*) to block user containing that domain","pie-register");?>
                      </span> </div>
                  </div>                  
                </div>
                <div class="fields fields_submitbtn">
                  <input name="action" value="pie_reg_update" type="hidden" />
                  <input type="hidden" name="restrict_user_by_email" value="1" />
                  <input <?php disabled($_disable, true, true); ?> name="Submit" class="submit_btn" value="<?php _e('Save Changes','pie-register');?>" type="submit" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
