<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>
<div class="crm-panel-field"> <h3 class="vx_top_head">Settings</h3> </div>
<form method="post"  id="crm-sales-settings">
               <div class="crm-panel-field">
                  <label class="crm_text_label">Google reCAPTCHA V3 Client-side Key</label>
                  <div class="crm-panel-description">Get keys from <a href="https://www.google.com/recaptcha/admin#list" target="_blank">here</a></div>
                  <input type="text" name="cfx_settings[google_public]" placeholder="Google Client-side Key" class="text" value="<?php echo cfx_form::post('google_public',$api)?>" />
                </div>
                <div class="crm-panel-field">
          <label class="crm_text_label">Google reCAPTCHA V3 Server-side Key</label>
          <div class="crm-panel-description">Get keys from <a href="https://www.google.com/recaptcha/admin#list" target="_blank">here</a></div>
          <input type="text" name="cfx_settings[google_private]" placeholder="Google Server-side Key" class="text" value="<?php echo cfx_form::post('google_private',$api)?>" />
                </div>
                  <div class="crm-panel-field">
          <label class="crm_text_label">Email Notification</label>
          <div class="crm-panel-description">Enter new separated email addresses.If email notifications are enabled in form settings an email will be sent to these email addresses</div>
   <textarea name="cfx_settings[alert_emails]" placeholder="Enter comma separated email addresses" class="text"><?php echo cfx_form::post('alert_emails',$api);?></textarea>
                </div>

      <div class="crm-panel-field">
                <label class="crm_text_label">Currency</label>
   <select name="cfx_settings[currency]"  class="text vx_select2" style="width: 100%;"  autocomplete="off">
<?php
    $list=cfx_form::get_currency_list();
       foreach($list as $k=>$v){
           $sel="";
           if($k == cfx_form::post( 'currency',$api) )
           $sel="selected='selected'";
        echo '<option value="'.$k.'" '.$sel.'>'.sprintf( '%s (%s %s)', $v['name'], $k, $v['symbol'] ).'</option>';
       }
?>
              </select>
              </div>
                              
 <?php
     do_action( 'settings_'.cfx_form::$id,$api);
 ?>
  <div class="crm-panel-field">
      <label >
      <input type="checkbox"  autocomplete="off"  name="cfx_settings[cookies]" value="yes" <?php if( !empty($api['cookies']) ){echo "checked='checked'";}?>>
                  Disable tracking cookies</label>
                </div>
                
         <div class="crm-panel-field">
      <label >
      <input type="checkbox"  autocomplete="off"  name="cfx_settings[plugin_data]" value="yes" <?php if( !empty($api['plugin_data']) ){echo "checked='checked'";}?>>
                  On deleting this plugin remove all of its data</label>
                </div>
                         
          <div style=" padding-top: 10px">
            <button type="submit" class="button button-primary button-hero main_submit"  style="float: left;"> <span class="reg_ok"><i class="fa fa-download"></i> Save</span> <span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Saving ...</span> </button>
          </div>
          <input type="hidden" name="id" value="1">
          <input type="hidden" name="vx_nonce" value="<?php echo wp_create_nonce("vx_nonce"); ?>">
</form>
<script type="text/javascript">
  jQuery(document).ready(function($){
 $(document).on('click','.vx_toggle_key',function(e){
  e.preventDefault();  
  var key=$(this).parents(".vx_tr").find(".crm_text"); 
  if($(this).hasClass('vx_hidden')){
  $(this).text('Show Key');  
  $(this).removeClass('vx_hidden');
  key.attr('type','password');  
  }else{
  $(this).text('Hide Key');  
  $(this).addClass('vx_hidden');
  key.attr('type','text');  
  }
  });
  $("#crm-sales-settings").submit(function(e){
    e.preventDefault();
    var form=$(this);
    var button=form.find(".main_submit");
    button_state("ajax",button);
    var form_arr=form.serializeArray();
    form_arr.push({name:'action',value:'vx_form_save_api_settings'})
    $.post(ajaxurl,form_arr
    ,function(res){
     button_state("ok",button);   
     var re={}; try{re=$.parseJSON(res);}catch(e){}
       if(!re || !re.status || re.status!='ok' ){
           alert(res || 'Error While Saving Data');
       }
   
    })   
});
});
</script>
<div style="clear: both; padding-top: 20px;">      
<?php
    do_action('add_section_'.cfx_form::$id);
?>
</div>  