<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }   ?>      
<div id="merge_tags_list" class="short_code_box crm_overlay_divs crm_arrow_box" style="display: none;">
<div class="short_code_list">
<span title="form_id">Form Id</span>
<span title="form_name">Form Name</span>
<?php
if(is_array($form['fields'])){
    foreach($form['fields'] as $k=>$f_val){
if(in_array($f_val['type'],array('html','hr'))){ continue; }
?><span title="<?php echo $k?>"><?php echo $f_val['label'];?></span>
<?php 
    }
}
      ?></div></div>
       