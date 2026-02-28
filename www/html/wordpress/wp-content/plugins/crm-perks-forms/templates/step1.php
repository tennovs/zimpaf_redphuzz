<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>
       <!-- #STEP 2-->
        <div class="steps step1">
          <form method="post"  class="crm_form_fields">
            <div class="crm-panel-field">
              <label class="crm_text_label">Form Name</label>
        <input type="text" name="form_name" id="cfx_form_name"  class="text" value="<?php echo $form['name']; ?>" required="required">
         <h3 class="crm-panel-description">use <code> [crmperks-forms id=<?php echo $form_id; ?>] </code> as a shortcode to place it in a post or a page.</h3>
            </div>

<div class="crm_sales_fields">
<?php   

     $data_types=array("text","number","email","url");
     $input_align=array("1"=>"One option in one line","2"=>"Two Options in one line","3"=>"Three Options in one line","4"=>"Four Options in one line","5"=>"Five Options in one line");
$fields_row=array("1"=>"One field in one line","2"=>"Two fields in one line","3"=>"Three fields in one line","4"=>"Four fields in one line","5"=>"Five fields in one line",'90'=>'90% of line width','80'=>'80% of line width','70'=>'70% of line width','60'=>'60% of line width','50'=>'50% of line width','40'=>'40% of line width','30'=>'30% of line width','20'=>'20% of line width','10'=>'10% of line width','6'=>'5% of line width','append_top'=>'Add in Top Line');
          
     $masks=array(""=>"Select Mask Type","(999) 999-9999"=>"US Phone","(999) 999-9999? x99999"=>"US Phone + Ext","99/99/9999"=>"Date","99-9999999"=>"Tax ID","999-99-9999"=>"SSN","99999"=>"Zip Code","99999?-9999"=>"Full Zip Code","custom"=>"Custom Mask");
   
   $cap_types=array(''=>'Simple','google'=>'Google Recaptcha');             
   $date_formats=array('mm/dd/yy'=>'mm/dd/yyyy','dd/mm/yy'=>'dd/mm/yyyy','dd-mm-yy'=>'dd-mm-yyyy','dd.mm.yy'=>'dd.mm.yyyy','yy/mm/dd'=>'yyyy/mm/dd','yy-mm-dd'=>'yyyy-mm-dd','yy.mm.dd'=>'yyyy.mm.dd');             

    $data=array();
$f_type=''; 

    ?>
<div class="crm-panel-field tr"> 
<label class="crm_text_label">Form Fields</label>
<div class="crm-panel-description">Add and order fields. </div>
<div class="crm_clear"></div>
</div>

<div class="sortable_fields">
  <?php  
$last_field=0; 
if(isset($form['fields']) && is_array($form['fields']) && count($form['fields'])>0){
         $fields=$form['fields']; 
     foreach($fields as $k=>$v){ 
         $last_field=max($k,$last_field);
         if(!isset($v['type']) ){
         $v['type']='text';
         }
    $label= !empty($v['label']) ? $v['label'] : 'No Label';
    $field_type=$v['type'];  
   
if(in_array($field_type,array('text','email','url','tel','number','name','f_name','l_name','password','search'))){
$field_type='input';
}     
?>
<div class="crm_panel">
<div class="crm_panel_head">
<div class="crm_head_div crm_move"><span class="crm_head_text"> #<span class="crm_text_no"><?php echo $k ?></span> - 
<label class="crm_text_label crm_head_text_label"><?php echo $label; ?></label></span>
</div>
<div class="crm_btn_div show_more_span"><i class="fa crm_fields_right_btn crm_remove_btn fa-trash" title="Trash"></i> <i class="fa crm_fields_right_btn crm_toggle_btn fa-plus"  title="Expand/Collapse"></i></div><div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: none;">

<div class="field_options_div">
<div>
      <label class="crm_text_label">Field Type</label>
      <select name="fields[<?php echo $k;?>][type]" data-name="type"  class="text field_type" autocomplete="off">
<?php foreach($types_group as $type_label=>$labels){ ?>
        <optgroup label="<?php echo ucfirst($type_label)?> Fields">
        <?php foreach($labels as $type){
         ?>
        <option value="<?php echo $type['type']; ?>" <?php  if($type['type'] == $v['type']){echo "selected='selected'"; }?>><?php echo $type['label'];?></option>
        <?php    }?>
        </optgroup>
        <?php  } ?>
      </select>
    </div>

    <div>
        <label class="crm_text_label">Field Label</label>
  <input type="text" name="fields[<?php echo $k;?>][label]" data-name="label" placeholder="Enter Field Label"  class="text field_label_value" value="<?php echo htmlentities($v['label']); ?>">
  </div>
    
    
<div class="cfx_field_html cfx_field_row">
      <label class="crm_text_label"></label>
<div class="html_div">  
<textarea name="fields[<?php echo $k;?>][html]" data-name="html" id="cfx_html_<?php echo $k ?>"  class="text cfx_html_area" placeholder="HTML"><?php echo isset($v['html']) ? htmlentities($v['html']) : '';
 ?></textarea>
 </div>
</div>
    
<div class="cfx_field_select cfx_field_row">
      <label class="crm_text_label sf_options_label">Enter new line seprated values and Options (example: option_value1=option_text1)</label>

      <textarea name="fields[<?php echo $k;?>][field_val]" data-name="field_val" class="text sf_options_val" placeholder="New line seprated Option values and text"><?php echo !empty($v['field_val']) ? $v['field_val'] : 'option_value=option_text'."\n".'option_value=option_text';
 ?></textarea>
</div>

 
        <div class="cfx_field_input cfx_field_desc cfx_field_row">
        <div>  <label class="crm_text_label">Field Description Text</label>
      <textarea name="fields[<?php echo $k;?>][desc]" data-name="desc" placeholder="Enter Field Description Text"><?php echo cfx_form::post('desc',$v); ?></textarea></div>
    </div>
    
    <div class="cfx_field_input_align cfx_field_row">
      <label class="crm_text_label">Options Alignment</label>
      <select name="fields[<?php echo $k;?>][input_align]" data-name="input_align"  class="text">
        <?php
        if(empty($v['input_align'])){ $v['input_align']='1'; }
    foreach($input_align as $align_k=>$align){
    ?>
        <option value="<?php echo $align_k; ?>" <?php if($align_k== $v['input_align']){echo "selected='selected'"; }?>><?php echo ucfirst($align)?></option>
        <?php    
    }
?>
      </select>
    </div>
    

<div class="cfx_field_captcha_type cfx_field_row">
<label class="crm_text_label">Captcha Type</label>
<select name="fields[<?php echo $k;?>][captcha_type]" data-name="captcha_type"  class="text">
<?php
if(empty($v['captcha_type'])){ $v['captcha_type']='1'; }
foreach($cap_types as $type_k=>$cap_type){
?>
<option value="<?php echo $type_k; ?>" <?php if($type_k== $v['captcha_type']){echo "selected='selected'"; }?>><?php echo $cap_type; ?></option>
        <?php    
    }
?>
</select>

</div>

<div class="cfx_field_star cfx_field_row">
<label class="crm_text_label">No of Stars</label>
<input type="text" name="fields[<?php echo $k;?>][stars]" data-name="stars" placeholder="5"  class="text" value="<?php echo cfx_form::post('stars',$v); ?>">
</div>

<?php do_action('crmperks_forms_field_html',$k,$v); ?>   

<div class="cfx_field_input cfx_field_req cfx_field_row">
       <label class="crm_text_label"><input type="checkbox" value="yes" class="req_check1" name="fields[<?php echo $k;?>][required]" data-name="required" <?php if(isset($v['required']) && $v['required'] == "yes") echo 'checked="checked"'?>> Required Field</label>
</div>
   
   
      
 <div class="vx_sub_panel">
 <div class="vx_sub_head">
 <div class="vx_sub_left">Advanced</div>
 <div class="vx_sub_right"> <i class="fa fa-plus crm_toggle_sub"  title="Expand/Collapse"></i></div>
 <div class="crm_clear"></div> 
 </div>
 <div class="vx_sub_body">
 
<div class="cfx_field_input cfx_field_row_fields cfx_field_row">
<label class="crm_text_label">No of fields in a line</label>
<select name="fields[<?php echo $k;?>][row_fields]" data-name="row_fields"  class="text">
        <?php
        if(empty($v['row_fields'])){ $v['row_fields']='1'; }
    foreach($fields_row as $align_k=>$align){
    ?>
        <option value="<?php echo $align_k; ?>" <?php if($align_k== $v['row_fields']){echo "selected='selected'"; }?>><?php echo ucfirst($align)?></option>
        <?php    
    }
?>
</select>
</div>

<div class="cfx_field_date_format cfx_field_row">
<label class="crm_text_label">Select Date Format</label>
<select name="fields[<?php echo $k;?>][date_format]" data-name="date_format"  class="text">
<?php
    foreach($date_formats as $fk=>$format){
    ?>
    <option value="<?php echo $fk; ?>" <?php if($fk== $v['date_format']){echo "selected='selected'"; }?>><?php echo $format?></option>
        <?php    
    }
?>
</select>
</div>
    
<div class="cfx_field_input cfx_field_hint  cfx_field_row">
    <div>  <label class="crm_text_label">Hint Text (Placeholder)</label>
      <input type="text" name="fields[<?php echo $k;?>][hint]" data-name="hint" placeholder="Enter Hint Text"  class="text" value="<?php echo cfx_form::post('hint',$v); ?>"></div>
 </div>
 
<div class="cfx_field_file_exts cfx_field_row">
    <div>  <label class="crm_text_label">Allowed File Extensions</label>
      <input type="text" name="fields[<?php echo $k;?>][exts]" data-name="exts" placeholder="png,pdf,jpg,gif,jpeg,docx,csv,xlsx,txt"  class="text" value="<?php echo cfx_form::post('exts',$v); ?>"></div>
 </div>
 
<div class="cfx_field_text_height cfx_field_row">
      <label class="crm_text_label">Textarea Height</label>
      <input type="text" name="fields[<?php echo $k;?>][text_height]" data-name="text_height" placeholder="Enter Textarea Height"  class="text" value="<?php echo cfx_form::post('text_height',$v); ?>">
    </div>
    
<div class="cfx_field_min_max cfx_field_row">
      <label class="crm_text_label">Minimum Value</label>
      <input type="text" name="fields[<?php echo $k;?>][min_value]" data-name="min_value" placeholder="0"  class="text" value="<?php echo cfx_form::post('min_value',$v); ?>">
</div>

<div class="cfx_field_min_max cfx_field_row">
      <label class="crm_text_label">Maximum Value</label>
      <input type="text" name="fields[<?php echo $k;?>][max_value]" data-name="max_value" placeholder="100"  class="text" value="<?php echo cfx_form::post('max_value',$v); ?>">
</div>
     
<div class="cfx_field_max_length cfx_field_row">
      <div><label class="crm_text_label">Max Characters</label>
      <input type="text" name="fields[<?php echo $k;?>][max]" data-name="max" placeholder="Enter Max Length"  class="text" value="<?php echo cfx_form::post('max',$v); ?>"></div>
       
       <div><label class="crm_text_label">Input Mask</label>
        <select name="fields[<?php echo $k;?>][mask]" data-name="mask"  class="text select_mask">
        <?php
    foreach($masks as $f_k=>$f_val){
    ?>
        <option value="<?php echo $f_k; ?>" <?php if($f_k== cfx_form::post('mask',$v) ){echo "selected='selected'"; }?>><?php echo ucfirst($f_val)?></option>
        <?php    
    }
?>
      </select></div>
       <div class="custom_mask_div" style="<?php if(cfx_form::post('mask',$v) !="custom"){echo "display:none";} ?>"><label class="crm_text_label">Input Format</label> <a href="javascript:void(0);" onclick="sf_colorbox('Custom Input Mask Examples','#sf_mask_help',500,800);">Help</a>
      <input type="text" name="fields[<?php echo $k;?>][custom_mask]" data-name="custom_mask" placeholder="Enter Custom Mask" value="<?php echo cfx_form::post('custom_mask',$v); ?>"  class="text"></div> 
        
</div>
    
<div class="cfx_field_default_value cfx_field_input cfx_field_row">
      <label class="crm_text_label">Default Value</label>
      <input type="text" name="fields[<?php echo $k;?>][default]" data-name="default" placeholder="Enter Field Default Value"  class="text" value="<?php echo cfx_form::post('default',$v); ?>">
    </div>

    
<div class="cfx_field_read_request cfx_field_input cfx_field_row">
      <label class="crm_text_label">Dynamic Field Filling</label>
      <input type="text" name="fields[<?php echo $k;?>][par_name]" data-name="par_name" placeholder="Enter Your Dynamic URL query parameter name or Cookie Name"  class="text" value="<?php echo cfx_form::post('par_name',$v); ?>">
</div>

 
<div class="cfx_field_input cfx_field_valid_msg cfx_field_row">
<div><label class="crm_text_label">Custom Validation Message</label>
      <input type="text" name="fields[<?php echo $k;?>][err_msg]" data-name="err_msg" placeholder="Enter Validation Error Message"  class="text" value="<?php echo cfx_form::post('err_msg',$v);?>" /></div>  
</div>
      
<div class="cfx_field_input cfx_field_input_class cfx_field_row">
        <label class="crm_text_label">Field Input Classes</label>
      <input type="text" name="fields[<?php echo $k;?>][field_class]" data-name="field_class"  placeholder="Add additional class ids to this input field. Separate classes with spaces."  class="text" value="<?php echo cfx_form::post('field_class',$v);?>" />
</div>

      
<div class="cfx_field_input cfx_field_field_id cfx_field_input_id cfx_field_row">
        <label class="crm_text_label">Field Input Id</label>
      <input type="text" name="fields[<?php echo $k;?>][field_id]" data-name="field_id"  placeholder="Add field Id"  class="text" value="<?php echo cfx_form::post('field_id',$v);?>" />
</div>
      
<div class="cfx_field_input cfx_field_con_class cfx_field_row">
        <label class="crm_text_label">Field Container Classes </label>
      <input type="text" name="fields[<?php echo $k;?>][con_class]" data-name="con_class"  placeholder="Add additional class ids to the div that contains this field. Separate classes with spaces."  class="text" value="<?php echo cfx_form::post('con_class',$v);?>" />
</div> 

<?php do_action('crmperks_forms_field_html_adv',$k,$v); ?>   
       
<div class="cfx_field_input cfx_field_hide_label cfx_field_row">
<div><label class="crm_text_label">Label Position</label>
<select name="fields[<?php echo $k;?>][label_pos]" data-name="label_pos">
<?php $pos=array('top'=>'Top','left'=>'Left','right'=>'Right','hidden'=>'Hidden');
    foreach($pos as $f_k=>$f_val){
    ?>
        <option value="<?php echo $f_k; ?>" <?php if($f_k== cfx_form::post('label_pos',$v) ){echo "selected='selected'"; }?>><?php echo ucfirst($f_val)?></option>
        <?php    
    }
?>
</select></div>
</div>
      
 </div>
 </div>
      
    </div>
  </div>
</div> 
<?php  
 
 }
 }
 else if(2==3){ 
$msg='No Fields Found';
$info=self::$info;
if(isset($info['error']) && $info['error']!=""){
$msg=$info['error'];    
}
     ?>
 <div class="alert_danger"><i class="fa fa-warning"></i> <?php echo $msg; ?></div>
 <p> </p>
 <?php
 }
do_action('crmperks_forms_fields_end',$form); 
 ?>     
</div>

 <h3 style="border: 2px dashed #ccc; color: #999; text-align: center; padding: 30px 0; display: none;" id="vx_add_fields_head">Please Add New Field</h3>
<div style="display: none;">
<div id="sf_mask_help">  
<h3>Usage</h3>
<ol>
<li>Use a <em>'9'</em> to indicate a numerical character.</li>
<li>Use a lower case <em>'a'</em> to indicate an alphabetical character.</li>
<li>Use an asterick <em>'*'</em> to indicate any alphanumeric character.</li>
<li>Use a question mark <em>'?'</em> to indicate optional characters. <em>Note:</em> All characters after the question mark will be optional.</li>
<li>All other characters are literal values and will be displayed automatically.</li>
</ol>

<h3>Examples</h3>
<ul class="examples-list">
<li>
<h5>Date</h5>
<span class="label">Mask</span> <code>99/99/9999</code><br>
<span class="label">Valid Input</span> <code>10/21/2011</code>
</li>
<li>
<h5>Social Security Number</h5>
<span class="label">Mask</span> <code>999-99-9999</code><br>
<span class="label">Valid Input</span> <code>987-65-4329</code>
</li>
<li>
<h5>Course Code</h5>
<span class="label">Mask</span> <code>aaa 999</code><br>
<span class="label">Valid Input</span> <code>BIO 101</code>
</li>
<li>
<h5>License Key</h5>
<span class="label">Mask</span> <code>***-***-***</code><br>
<span class="label">Valid Input</span> <code>a9a-f0c-28Q</code>
</li>
<li>
<h5>Zip Code w/ Optional Plus Four</h5>
<span class="label">Mask</span> <code>99999?-9999</code><br>
<span class="label">Valid Input</span> <code>23462</code> or <code>23462-4062</code>
</li>
</ul>

</div>
  <input type="hidden" id="field_name" data-name="crm_sales_fields[<?php echo $form;?>][<?php echo $key?>][data]">
<div id="sf_duplicate_fields_help">
<h3>No Duplicates</h3>
Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the database.
</div> 
    
       </div>

</div>
<script type="text/javascript">
var cfx_field_types=<?php echo json_encode($types_js); ?>;
jQuery(document).ready(function($){
$('#cfx_form_name').blur(function(){
   $('#cfx_title').text($(this).val()); 
})

jQuery(".sortable_fields").sortable({placeholder: "fields_placeholder",opacity:.9,handle:'.crm_move',revert:true,forcePlaceholderSize: true,items:'.crm_panel'});
vx_check_no_fields();

jQuery('.field_type').each(function(){
 apply_field_type(this);
});

var vx_last_field=<?php echo $last_field; ?>;

$(document).on("click",'.crm_toggle_btn',function(e) {
var panel=$(this).parents('.crm_panel');
if($(this).hasClass('fa-plus')){
 if(panel.find('.field_type').val() == 'html'){
add_ed(panel.find('.cfx_html_area').attr('id'));     
 }    
}
});
$(document).on("click",'.crm_toggle_sub',function(e) {
var panel=jQuery(this).parents(".vx_sub_panel");
 var div=panel.find(".vx_sub_body");
 var btn=panel.find(".crm_toggle_sub");
 div.slideToggle('fast',function(){
  if(jQuery(this).is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');  
 }else{
 btn.addClass('fa-plus');     
 btn.removeClass('fa-minus'); 
   
  }   
 });
}); 
function remove_ed(id){
 if(wp && typeof wp.editor != 'undefined' && typeof wp.editor.remove == 'function'){
     var elem=$('#'+id); 
     if(elem.hasClass('cfx_ed_added')){
  wp.editor.remove(id); 
  elem.removeClass('cfx_ed_added');  
     }
 }  
}
function add_ed(id){
    if(wp && typeof wp.editor != 'undefined' && typeof wp.editor.initialize == 'function'){
        var elem=$('#'+id);
        if(!elem.hasClass('cfx_ed_added')){
    wp.editor.initialize( id, {
    mediaButtons: true,
    tinymce: { height: 200,toolbar1:"formatselect,bold,italic,bullist,numlist,strikethrough,hr,forecolor,backcolor,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",toolbar2:"blockquote,alignleft,aligncenter,alignright,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help"},
    quicktags:    true,
});
    elem.addClass('cfx_ed_added'); }
    }
}   
$(document).on("click",'.new_field',function(e) {
  var div=$('.sortable_fields');  
var f_id=vx_last_field+=1;
    //var temp=$("#field_options_temp").clone().removeAttr('id');
    var temp=$('.crm_panel').eq(0).clone();
temp.find(":input").each(function(){
var type=$(this).attr('type'); 
if($.inArray(type,['checkbox']) == -1){
    $(this).val('');     
}
f_name='fields['+f_id+']['+$(this).attr('data-name')+']';
$(this).attr('name',f_name);     
});
  var label='Field '+f_id;
    temp.find('.crm_head_text_label').text(label);
    temp.find('.crm_text_no').text(f_id);
    temp.find('.field_label_value').val(label);

temp.find('.cfx_html_area').attr('id','cfx_html_'+f_id);
temp.find('.field_type').val('text');
temp.attr('id','vx_field_'+f_id);
div.append(temp);

var field=$('#vx_field_'+f_id);
var btn=field.find('.crm_toggle_btn');
if(btn.hasClass('fa-plus')){
btn.click();
}
 field.find(".field_type").trigger('change');
});

$(document).on("click",'.crm_remove_btn',function(e) {
        var div=$(this).parents('.crm_panel');
        div.fadeOut('slow',function(){
           div.remove();
           vx_check_no_fields();
        if(div.find('.field_type').val() == 'html'){
        remove_ed(div.find('.cfx_html_area').attr('id'));       
           }
        });
});
         
jQuery(document).on("change",".field_type",function(){
 apply_field_type(this,true);
});
function apply_field_type(elem,html){
var options=$(elem).parents(".more_options");
var val=$(elem).val();
    options.find('.cfx_field_row').hide(); 
    if(cfx_field_types[val]){
        $.each(cfx_field_types[val],function(k,cl){
         options.find('.cfx_field_'+cl).show();
        });
    }
    if(html){
        var html_elem=options.find('.cfx_html_area');
         var html_id=html_elem.attr('id');
if(val == 'html'){ add_ed(html_id) }else if(html_elem.hasClass('cfx_ed_added')){ remove_ed(html_id); }
    }
}
$(document).on("change",'.select_mask',function(e) {
     e.preventDefault();
var div=$(this).parents(".more_options").find(".custom_mask_div");
   if(this.value!="custom")
div.hide('fast');
else  
div.show('fast');  
});
$(document).on("change",'.valid_err_check',function(e) {
     e.preventDefault();
var div=$(this).parents(".more_options").find(".valid_err_div");
if($(this).is(":checked")){
    div.show();
}else{
    div.hide();
}  
});

$(".crm_form_fields").submit(function(e){
    e.preventDefault();
    if(typeof tinymce != 'undefined'){
    jQuery.each(tinymce.editors,function(){ 
        $('#'+this.id).val(this.getContent());
    });
    }
    var form=$(this);
    var button=form.find(".main_submit");
    button_state("ajax",button);
    $('.cfx_field_row').each(function(){
        if($(this).css('display') == 'none'){
     $(this).find(':input').each(function(){
    $(this).attr('name-temp',$(this).attr('name'));
    $(this).removeAttr('name');     
     });       
        }
    })
    var form_arr=form.serializeArray();
      $(':input').each(function(){
        if($(this).attr('name-temp')){
    $(this).attr('name',$(this).attr('name-temp'));
    $(this).removeAttr('name-temp');      
        }
    });
    form_arr.push({name:'action',value:'vx_form_save_main_form'});
    $.post(ajaxurl,form_arr,function(res){
     button_state("ok",button);   
       var re={}; try{re=$.parseJSON(res);}catch(e){}
       if(!re || !re.status || re.status!='ok' ){
           alert(res || 'Error While Saving Data');
       }

    })   
}); 
                   
function vx_check_no_fields(){ //crm_fields_right_btn
    if(jQuery('.sortable_fields .crm_panel').length<2){
     jQuery('.crm_remove_btn').hide();   
    }else{
        jQuery('.crm_remove_btn').show();  
    }
} 

})
     </script>  
            <div style=" padding-top: 10px">
            <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
              <input type="hidden" name="form_id" class="form_id" value="<?php echo $form_id; ?>">
              <button type="submit" class="button-primary  button button-hero main_submit"  style="float: left;"> <span class="reg_ok"><i class="fa fa-download"></i> Save</span> 
<span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Saving ...</span> 
</button>
              
              <span style="float: right">
  <button type="button" class="button button-hero button-primary new_field" style="vertical-align: middle;">
  <span class="reg_ok"><i class="fa fa-plus-circle"></i> Add New Field</span> 
  </button>
 </span>
 
            </div>
          </form>
        </div> <!-- /STEP 2-->