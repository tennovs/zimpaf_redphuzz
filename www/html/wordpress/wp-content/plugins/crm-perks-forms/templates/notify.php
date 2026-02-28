<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } $options=$form['notify']; 
 if(empty($options['alerta_subject'])){
     $options['alerta_subject']=$form['name'].' Form Submitted';
 }
   ?>
    <form method="post"  class="crm_form" novalidate>
    <div class="steps notify">
      
     <div class="crm-panel-field">
              <label >
                <input type="checkbox"  data-rel="alert_div" name="vx_notify[use_alert]" value="yes" <?php if(  !empty($options['use_alert'] )){echo "checked='checked'";}?> autocomplete="off">
               Send Email Notification to Admin</label>
            </div>
            <div id="alert_div"> 
              <div class="email_alert alert_type">
                <div class="crm-panel-field">
                  <label class="crm_text_label">Email To</label>
                  <div class="crm-panel-description">Enter new line separated email addresses.Form submission detail will be sent to these email addresses and email address from global settings</div>
                  <textarea name="vx_notify[alert_emails]" placeholder="Enter comma separated email addresses" class="text"><?php echo cfx_form::post('alert_emails',$options);?></textarea>
                </div>
              </div>
            </div>

             <div class="crm-panel-field">
                  <label class="crm_text_label">Reply To Email</label> <a href="#" class="crm_merge_tags">Add Merge Tags</a>
                  <div class="crm-panel-description">Reply To email address.</div>
 <input type="text"  name="vx_notify[alerta_reply]" class="text" value="<?php echo cfx_form::post('alerta_reply',$options); ?>">
 </div>
 
   <div class="crm-panel-field">
                  <label class="crm_text_label">From Name</label> 
                  <div class="crm-panel-description">From which name notification should be sent.</div>
 <input type="text"  name="vx_notify[alerta_name]" class="text" value="<?php echo cfx_form::post('alerta_name',$options); ?>">
 </div>
 
    <div class="crm-panel-field">
                  <label class="crm_text_label">From Email</label> 
                  <div class="crm-panel-description">From which email address notification should be sent.</div>
 <input type="text"  name="vx_notify[alerta_from]" class="text" value="<?php echo cfx_form::post('alerta_from',$options); ?>">
 </div>
 
  <div class="crm-panel-field">
                  <label class="crm_text_label">Admin Email Subject</label> <a href="#" class="crm_merge_tags">Add Merge Tags</a>
 <input type="text"  name="vx_notify[alerta_subject]" class="text" value="<?php echo cfx_form::post('alerta_subject',$options); ?>">
 </div>
 
    <div class="crm-panel-field">
              <label class="crm_text_label">Admin Email Body Type</label> 
              <div class="crm-panel-description">Choose admin email body type.</div>
              <select name="vx_notify[admin_email_type]"  class="text" id="vx_sel_admin_email"  autocomplete="off">
 <?php
     $email_types=array(''=>'Built-In email with all details','custom'=>'Custom email message');
     foreach($email_types as $k=>$v){
         $sel=''; if(!empty($options['admin_email_type']) && $options['admin_email_type'] == $k){
            $sel='selected="selected"'; 
         }
      echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';   
     }
     
 ?>
              </select>
            </div>
            
 
 
            <div id="vx_sel_admin_email_div"  style="<?php if( empty( $options['admin_email_type'] )){ echo "display:none"; }?>">
              <div class="crm-panel-field">
                <label class="crm_text_label">Admin Email Body</label>   <a href="#" class="crm_merge_tags">Add Merge Tags</a>
                <div class="crm-panel-description">You can use %field_name% in message like use %FirstName% to display first name.</div>
                <?php
        $content = cfx_form::post('alerta_body',$options);
  $editor_id = 'vx_config_admin_body';
  $settings = array("textarea_name"=>"vx_html[alerta_body]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20,);
  wp_editor($content,$editor_id,$settings);
      ?>
              </div>
          
            </div>
            
            
               <div class="crm-panel-field">
              <label >
                <input type="checkbox"  data-rel="alert_div_c" name="vx_notify[use_alertc]" value="yes" <?php if(  !empty($options['use_alertc'] )){echo "checked='checked'";}?> autocomplete="off">
               Send Email Notification to User</label>
            </div>
            <div id="alert_div_c"> 
                <div class="crm-panel-field">
                  <label class="crm_text_label">Email To</label> 
                  <div class="crm-panel-description">Select Customer email address field</div>
                  <select  name="vx_notify[alertc_email]" class="text">
                  <?php 
              if(is_array($form['fields'])){
    foreach($form['fields'] as $k=>$f_val){
        if($f_val['type'] == 'email'){
               $sel="";
           if($k == cfx_form::post( 'alertc_email',$options) ){
           $sel="selected='selected'"; }
?><option value="<?php echo $k?>" <?php echo $sel ?>><?php echo $f_val['label'];?></option>
<?php 
        }  }
}
                  ?>
                  </select>
              </div>
                  <div class="crm-panel-field">
                  <label class="crm_text_label">Email BCC</label> 
                  <div class="crm-panel-description">Send a copy to an email address</div>
                  <input type="text"  name="vx_notify[alertc_bcc]" class="text" value="<?php echo cfx_form::post('alertc_bcc',$options); ?>">
               
              </div>
 
  <div class="crm-panel-field">
                  <label class="crm_text_label">Reply To Email</label> <a href="#" class="crm_merge_tags">Add Merge Tags</a>
                  <div class="crm-panel-description">Reply To email address.</div>
 <input type="text"  name="vx_notify[alertc_reply]" class="text" value="<?php echo cfx_form::post('alertc_reply',$options); ?>">
 </div>
 
   <div class="crm-panel-field">
                  <label class="crm_text_label">From Name</label> 
                  <div class="crm-panel-description">From which name notification should be sent.</div>
 <input type="text"  name="vx_notify[alertc_name]" class="text" value="<?php echo cfx_form::post('alertc_name',$options); ?>">
 </div>
 
    <div class="crm-panel-field">
                  <label class="crm_text_label">From Email</label>
                  <div class="crm-panel-description">From which email address notification should be sent.</div>
 <input type="text"  name="vx_notify[alertc_from]" class="text" value="<?php echo cfx_form::post('alertc_from',$options); ?>">
 </div>
 <div class="crm-panel-field">
                  <label class="crm_text_label">Email Subject</label> <a href="#" class="crm_merge_tags">Add Merge Tags</a>
 <input type="text"  name="vx_notify[alertc_subject]" class="text" value="<?php echo cfx_form::post('alertc_subject',$options); ?>">
 </div>

      <div class="crm-panel-field">
                  <label class="crm_text_label">Customer Notification Body</label> <a href="#" class="crm_merge_tags">Add Merge Tags</a>
                  <div class="crm-panel-description">Body of customer notification message.</div>
              <?php
        $content = cfx_form::post('alertc_body',$options);
  $editor_id = 'vx_notify_alert_body';
  $settings = array("textarea_name"=>"vx_html[alertc_body]","tinymce"=>array( 'autop' => false),"textarea_rows"=>20);
  wp_editor($content,$editor_id,$settings);
      ?>
 </div>
    
     
                                   
            </div>
                 
            <div class="crm-panel-field">
              <h3 class="crm-panel-description">use <code> [crmperks-forms id=<?php echo $form_id; ?>] </code> as a shortcode to place it in a post or a page.</h3>
            </div>

            <!--SAVE-->
             <!-- #crm-panel-actions --> 
            <div id="crm-panel-actions">
              <div class="clear-block" style="text-align: right; padding: 10px;">
              <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
                <input type="hidden" name="form_id" class="form_id" value="<?php echo $form_id; ?>">
                <button type="submit" class="button button-hero button-primary main_submit"> 
                <span class="reg_ok"><i class="fa fa-download"></i> Save</span> 
                <span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Saving ...</span> 
                </button>
              </div>
            </div>
            <!-- /crm-panel-actions --> 
          </div> <!-- /STEP 4-->

</form>       
<?php include_once(cfx_form_plugin_dir.'templates/merge_tags.php'); ?>
       