<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>
<h3 class="vx_top_head">Export Forms</h3>
       <form method="post" class="crm_export_forms">
          <div class="crm-panel-field">
            <div class="col_label">
              <label class="crm_text_label">Select Form</label>
              <div class="crm-panel-description">Choose a form to export.</div>
               
            </div>
            <div class="col_val">
              <?php 
 foreach($forms as $form){ ?>
              <div>
                <label>
 <input type="checkbox" class="input_checks" value="<?php echo $form['id']?>" name="forms_exp[]" >
                  <?php echo $form['name']?></label>
              </div>
              <?php 
                            }
                             ?>
            </div>
            <div style="clear: both;"></div>
          </div>
          <?php
              if(count($forms)>0){
          ?>
          <div class="crm_buttons_div">
            <input type="hidden" name="cfx_form_tab_action" value="export_forms">
          <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
          <button type="submit" class="button-primary"><i class="fa fa-download"></i> Download File</button>
          </div>
            <?php
              }
          ?>
        </form>
        <hr style="margin-top: 30px;">
        <h1 >Import Forms</h1>
        <form method="post" enctype="multipart/form-data">
         <div class="crm-panel-field">
          <div class="col_label">
            <label class="crm_text_label">Select Form</label>
          </div>
          <div class="col_val">
              <input type="file" name="forms_file">
          </div> <div style="clear: both;"></div>       </div>
          <div class="crm_buttons_div">
           <input type="hidden" name="cfx_form_tab_action" value="import_forms">
           <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
          <button type="submit" class="button-primary"><i class="fa fa-upload"></i> Import File</button></div>
     
        </form>
        
          <hr style="margin-top: 30px;">
         <h1>Import from other Contact Forms</h1>
        <form method="post" id="crm_import_forms">
          <div class="crm-panel-field">
            <div class="col_label">
              <label class="crm_text_label">Select Contact Form</label>
               
            </div>
            <div class="col_val">
            <select name="form" id="crm_sel_other_form" autocomplete="off">
            <option value="">Select Any Contact Form</option>
              <?php 
$forms=array('cf7'=>'Contact Form 7','gf_forms'=>'Gravity Forms - Forms Only');
if(class_exists('vxcf_form')){
$forms['gf_entries']='Gravity Forms - Both Entries and Forms';
}
                            foreach($forms as $k=>$v){
                                ?>
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
              <?php 
                            }
                             ?>
              </select>
                       <div id="cforms_ajax" style="display: none;">
              <i class="fa fa-circle-o-notch fa-spin"></i> Getting forms ...
                       </div>      
            </div>
            <div style="clear: both;"></div>
          </div>
     <div id="cforms_options" style="display: none;">     
                 <div class="crm-panel-field">
            <div class="col_label">
              <label class="crm_text_label">Select Forms</label>
              <div class="crm-panel-description"></div>
               
            </div>
            <div class="col_val">
            <div class="cform_checks"></div>      
            </div>
            <div style="clear: both;"></div>
          </div>
              
            
 
               <div class="crm_buttons_div">
          <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
          <input type="hidden" name="action" value="vx_form_import_forms">
          <button type="submit" class="button-primary vx_submit" autocomplete="off">
          <span class="reg_ok"><i class="fa fa-download"></i> Start </span>
          <span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> working ... </span>
          </button></div>
            </div>
        
             
       
        </form>
 <script type="text/javascript">
 jQuery(document).ready(function($){
 $("#crm_import_forms").submit(function(e){
   e.preventDefault(); 
 var btn=$(this).find('.vx_submit');
 var form=$(this).serializeArray();
 button_state('ajax',btn);
 import_forms(form,btn);
 }); 
 function import_forms(form,btn){
      $.post(ajaxurl,form,function(res){
var re=jQuery.parseJSON(res);
if(re.status == 'working'){
    if(re.rows){
    form.push({name:'page',value:re.rows});    
    }
  import_forms(form,btn);  
}else if(re.status == 'ok'){
alert('successfully imported');
button_state('ok',btn);    
}else{
    alert('Error while importing ');
    button_state('ok',btn);
}
 });  
 }
 $(".crm_export_entries").submit(function(e){ 
 if(!$(this).find(".input_checks").filter(':checked').length || $(this).find(".crm_form").val() == ""){
 alert("Please Select a form and its fields");
 e.preventDefault();    
 }   
});
$(".crm_export_forms").submit(function(e){ 
 if(!$(this).find(".input_checks").filter(':checked').length){
 alert("Please Select atleast one form");
 e.preventDefault();    
 }   
});    
$("#sel_export_form").change(function(){
  var form_id=$(this).val(); 
  if(form_id == ""){
  $(".form_checks").html("");
  $(".crm_entries_options").hide();
  return;
  }
  var ajax=$(".form_checks_ajax");
  ajax.show();  
 $.post(ajaxurl,{action:'vx_form_fields_export_html',form_id:form_id,vx_nonce:$("#vx_nonce").val()},function(res){
ajax.hide();
$(".crm_entries_options").show();
$(".form_checks").html(res);
 });   
 }); 
$("#crm_sel_other_form").change(function(){
  var form_id=$(this).val(); 
    $('#cforms_options').hide();
  if(form_id == ""){
  $(".cform_checks").html("");
  $("#cforms_ajax").hide();
  return;
  }
  
  var ajax=$("#cforms_ajax");
  ajax.show();  
 $.post(ajaxurl,{action:'vx_other_forms_export_html',form_id:form_id,vx_nonce:$("#vx_nonce").val()},function(res){
ajax.hide();
$('#cforms_options').show();
$(".cform_checks").html(res);
 });   
 });
 
   
 $(document).on("click",".crm_add_con",function(e){
      e.preventDefault();
    var temp=$("#crm_con_temp").clone();
temp.removeAttr('id');
    $(".crm_con_div").append(temp);
 });
 $(document).on("click",".crm_remove_con",function(e){
 $(this).parents(".crm_con").remove();
 })
 $(document).on("click",".sel_all_checks",function(e){
    if($(this).is(":checked")){ 
$(this).parents(".crm_checks_w").find(".input_checks").attr('checked','checked');
    }else{
$(this).parents(".crm_checks_w").find(".input_checks").removeAttr('checked');
    }
});
function button_state(state,button){
var ok=button.find('.reg_ok');
var proc=button.find('.reg_proc');
     if(state == "ajax"){
          button.attr({'disabled':'disabled'});
ok.hide();
proc.show();
     }else{
         button.removeAttr('disabled');
   ok.show();
proc.hide();      
     }
}
 })
 </script> 
       