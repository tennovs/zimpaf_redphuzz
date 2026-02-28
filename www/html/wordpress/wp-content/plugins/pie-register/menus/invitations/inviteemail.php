<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php $piereg = get_option(OPTION_PIE_REGISTER); 

$_disable       = true;

?>
<fieldset class="piereg_fieldset_area-nobg" <?php disabled($_disable, true, true); ?>>
<form method="post" action="" name="pie_invite_sent" id="pie_invite_sent" enctype="multipart/form-data">
<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_invitation_code_nonce','piereg_invitation_code_nonce'); ?>
<h3><?php echo _e('Invite Users','pie-register'); ?></h3>
<ul class="bg-white clearfix invite-form">
  <h3 class="inactive-promessage"><?php _e("Available in premium version","pie-register");?></h3>
<li class="clearfix">
  <div class="fields">
    <div  class="cols-2">
      <h3>
        <?php _e("Registration Page *","pie-register");?>
      </h3>
    </div><!-- cols-3 -->         
    <div class="cols-3">          
      <select required id="pie_email_linkpage" name="pie_email_linkpage" >
        <?php 
        $pages = get_pages(array( 'numberposts' => -1));
        foreach( $pages as $page ) : $page->post_content; ?>
          <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($piereg['pie_email_linkpage'] == $page->ID, true, true); ?>>
            <?php echo esc_html($page->post_title); ?>
          </option>
        <?php endforeach; ?>
      </select>          
    </div><!-- cols-3 -->
  </div>
</li>
<li class="clearfix">
  <div class="fields">
    <div  class="cols-2">
      <h3>
        <?php _e("Select Invitation Code *","pie-register");?>
      </h3>
    </div><!-- cols-3 -->
    <div class="cols-3">          
      <select required id="pie_email_invitecode" name="pie_email_invitecode" >
        <?php 
        global $wpdb;
        $codetable    = $wpdb->prefix."pieregister_code";
        $inviteCodes  = $wpdb->get_results("SELECT * FROM $codetable WHERE `status` = 1");
        $today        = date("Y-m-d");
        foreach($inviteCodes as $key => $val){
          $expiryTime = date("Y-m-d", strtotime($inviteCodes[$key]->expiry_date));
          $selected = selected($piereg['pie_email_invitecode'] == $inviteCodes[$key]->name, true);
          if(($inviteCodes[$key]->expiry_date == "0000-00-00" || $expiryTime > $today) && ($inviteCodes[$key]->code_usage == 0 || ($inviteCodes[$key]->code_usage > 0 && intval($inviteCodes[$key]->code_usage) > intval($inviteCodes[$key]->count))) ){
            echo "<option value='".esc_attr($inviteCodes[$key]->name)."' ".$selected.">".esc_html($inviteCodes[$key]->name)."</option>";
          }
        }
        ?>
      </select>          
    </div><!-- cols-3 -->
  </div>
</li>
<li class="clearfix">
  <div class="fields">
    <div class="cols-2">
      <h3>
        <?php _e("Invite users to register (invite manually or through import): ","pie-register");?>
      </h3>
    </div>
    <div class="cols-3">
      <textarea name="pie_email_invite" id="pie_email_invite" rows="20"></textarea>   
      <span class="quotation import-email-invites">
        <?php _e("Add Email Addresses, comma seperated.","pie-register"); ?>
      </span>   
    </div>
  </div>
</li>
<li class="clearfix">
<div class="fields extra-margin">
    <div class="cols-3">
      <span class="quotation import-email-invites optional-selection">
        <strong><?php _e("OR","pie-register"); ?></strong>
      </span>   
    </div>
  </div>
</li>
<li class="clearfix">
  <div class="fields extra-margin">
    <div class="cols-3">
      <input type="file" name="import_email_addresses_file" class="import_email_addresses_file" title="">
      <span class="quotation import-email-invites">
        <strong><?php _e("Warning","pie-register"); ?></strong>:
        <?php _e("Supports CSV format.","pie-register"); ?>
      </span>             
      <span class="quotation import-email-invites"><?php echo sprintf( __( 'You may want to see', 'pie-register').' <a target="_blank" download href="%s"> '.__('this example of the CSV file.', 'pie-register').'</a>. Use a separate line for each email address.' , esc_url(plugin_dir_url(__FILE__).'../examples/example-import-email-addresses-test.csv')); ?>
      </span>
    </div>
  </div>
</li>
<li class="clearfix">
  <div class="fields fields_submitbtn">
    <div class="cols-2">&nbsp;</div><!-- cols-3 -->
    <div class="cols-3 text-right">
      <input name="send_invite_email" class="submit_btn btn-send-invite" value="<?php _e('Send Invitation','pie-register');?>" type="submit" />
    </div><!-- cols-3 -->
  </div>
</li>
<li class="notice-invite-status">
  <div class="fields "><div class="cols-2">&nbsp;</div><!-- cols-3 -->
    <div class="cols-3 text-right"><i><?php echo _e('Enable the invitation code feature to send invitations.','pie-register') ?>.</i></div><!-- cols-3 -->
  </div>
</li>
</ul>
</form>
</fieldset>
<fieldset class="piereg_fieldset_area-nobg" <?php disabled($_disable, true, true); ?>>
<form method="post" action="">
  <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_invitation_code_nonce','piereg_invitation_code_nonce'); ?>
  <h3><?php echo _e('Customize Email Template','pie-register'); ?></h3>
  <ul class="bg-white clearfix invite-form custom-template">
        <h3 class="inactive-promessage"><?php _e("Available in premium version","pie-register");?></h3>
       <li class="clearfix">
        <div class="fields">
          <div  class="cols-2">
            <h3>
              <?php _e("From","pie-register");?>
            </h3>
          </div><!-- cols-3 -->
         <div class="cols-3">          
            <input value="<?php echo (isset($piereg['pie_name_from']) ? esc_attr($piereg['pie_name_from']):''); ?>" type="text" name="pie_name_from" id="pie_name_from" class="input_fields2" required="" />         
          </div><!-- cols-3 -->
        </div>
      </li>
      <li class="clearfix">
        <div class="fields">
          <div  class="cols-2">
            <h3>
              <?php _e("From Email","pie-register");?>
            </h3>
          </div><!-- cols-3 -->
         <div class="cols-3">          
            <input value="<?php echo (isset($piereg['pie_email_from'])?esc_attr($piereg['pie_email_from']):''); ?>" type="text" name="pie_email_from" id="pie_email_from" class="input_fields2" required="" />         
          </div><!-- cols-3 -->
        </div>
      </li>
      <li class="clearfix">
        <div class="fields">
          <div  class="cols-2">
            <h3>
              <?php _e("Subject","pie-register");?>
            </h3>
          </div><!-- cols-3 -->
         <div class="cols-3">
            <input value="<?php echo (isset($piereg['pie_email_subject'])?esc_attr($piereg['pie_email_subject']):''); ?>" type="text" name="pie_email_subject" id="pie_email_subject" class="input_fields2" required="">
          </div><!-- cols-3 -->
        </div>
      </li>      
      <li class="clearfix">
        <div class="fields">
          <div  class="cols-2">
            <h3>
              <?php _e("Email Body","pie-register");?>
            </h3>
          </div><!-- cols-3 -->
         <div class="cols-3">          
          <textarea class="invite-email-text" name="pie_email_content" rows="10" cols="50"><?php echo (isset($piereg['pie_email_content'])?esc_textarea($piereg['pie_email_content']):''); ?></textarea>
          <b onclick="selectText('piereg-select-all-text-onclick_invite_link')" id="piereg-select-all-text-onclick_invite_link">%invitation_link%</b> <?php echo _e('Invitation link replacement key can be used in Email Body','pie-register'); ?>
          <br />
          <b onclick="selectText('piereg-select-all-text-onclick_blogname')" id="piereg-select-all-text-onclick_blogname">%blogname%</b> <?php echo _e('Site Title replacement key can be used in Email Body and Subject','pie-register'); ?>
          </div><!-- cols-3 -->         
        </div>
      </li>
      <li class="clearfix">
        <div class="fields fields_submitbtn">
          <div class="cols-2">&nbsp;</div><!-- cols-3 -->
          <div class="cols-3 text-right">
            <input name="submit_invite_email" class="submit_btn" value="<?php _e('Save','pie-register');?>" type="submit" />
          </div><!-- cols-3 -->
        </div>
      </li>
  </ul>
</form>
</fieldset>