<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>
    <h3 class="vx_top_head">Forms </h3>
<a class="button button-primary crm_action_button" href="<?php echo $page_url."&tab=new_form"?>"><i class="fa fa-plus-circle"></i> Add New Form</a>
<?php $i=1; 
if(count($forms)==0)
{ ?>
<div style="padding-top: 30px">
<div class="alert_danger" style="padding: 20px;"><i class="fa fa-warning"></i> No Record Found. <a href="<?php echo $page_url."&tab=new_form"?>" style="color: #fff;">Create New Form</a></div>
</div>
<?php 
}
else
{
$form_fields=array('views'=>__('Views','crmperks-forms'),'entries'=>__('Entries','crmperks-forms'),'conversion'=>__('Conversion','crmperks-forms'));
$form_fields=apply_filters('crmperks_forms_table_fields',$form_fields); 
$forms=apply_filters('crmperks_forms_table_data',$forms); 
 ?>
        <table class="crm_table widefat fixed">
          <thead>
               <tr>
<th style="width: 20px;">#</th>
              <th style="width: 30px;"></th>
              
              <th  style="width: 30%">Name</th>
              <?php foreach($form_fields as $v){
                  echo '<th>'.$v.'</th>';
              } ?>
              <th  style="width: 20%" class="short_th">Short Code</th>
            </tr>
          </thead>
          <tbody>
            <?php 
$nonce=wp_create_nonce('vx_nonce'); $no=0;       
foreach($forms as $k=> $form)
{ $no++;
$row_actions=array();
$row_actions['edit']='<a href="'.$page_url.'&form_id='.$form['id'].'">Edit</a>';
$row_actions['clone']='<a href="'.$page_url.'&form_id='.$form['id'].'&cfx_form_tab_action=copy_form&vx_nonce='.$nonce.'">Clone</a>';
$row_actions['delete']='<a href="'.$page_url.'&id='.$form['id'].'&cfx_form_tab_action=del_form&tab=forms" class="del_form">Delete</a>';
$row_actions=apply_filters('crmperks_forms_row_actions',$row_actions,$form);
$row_actions_str=array();
foreach( $row_actions as $id=>$v){
    $class= $id == 'delete' ? 'delete' : 'edit';
 $row_actions_str[]='<span class="'.$class.'">'.$v.'</span>';   
}
?>
<tr class="main_tr <?php echo $k%2 == 0 ? "alternate" : ""?>" data-id="<?php echo $form['id'];?>">
<td><?php echo $no;?></td>            
            <td class="vx_col"><img src="<?php echo cfx_form_plugin_url ?>images/active<?php echo intval($form['status']) ?>.png" alt="<?php echo $form['status'] == '1' ? "Active" : "Inactive";?>" title="<?php echo $form['status'] == '1' ? "Active" : "Inactive";?>" class="vx_toggle_status" /></td>
               
              <td><a  href="<?php echo $page_url ?>&form_id=<?php echo $form['id']; ?>" class="row-title"><?php echo $form['name'];?></a>
                <div class="row-actions"> 
            <?php echo implode(' | ',$row_actions_str); ?>    
                </div></td>
              <?php foreach($form_fields as $k=>$v){
                  if(isset($form[$k])){ $v=$form[$k]; }
                  if($k == 'conversion'){
                     $v=!empty($form['views']) ? round(((int)$form['entries']/(int)$form['views'])*100,2) : 0;
                     $v.='%'; 
                  }
                  echo '<td><strong>'.$v.'</strong></td>';
                  
              } ?>  
              <td>[crmperks-forms id=<?php echo $form['id'] ;?>]</td>
            </tr>
            <tr style="display: none;">
              <td colspan="6" style="padding: 0px;"></td>
            </tr>
            <?php
}
?>
          </tbody>
     
        </table>
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery(document).on("click",".del_form",function(e){
             e.preventDefault();
if(!confirm("Are You Sure To Delete?")){ return; }
         var href=$(this).attr('href');
       ///  jQuery(this).parents('tr').fadeIn('slow').remove();
window.location.href=href+'&vx_nonce=<?php echo $nonce; ?>';
          });
  $(".vx_toggle_status").click(function(e){
      e.preventDefault();
    var feed_id;
    var img=this;
  var is_active = img.src.indexOf("active1.png") >=0
  var $img=$(this);
  if(is_active){
  img.src=img.src.replace("active1.png", "active0.png");
  $img.attr('title','Inactive').attr('alt', 'Inactive');
  }else{
  img.src = img.src.replace("active0.png", "active1.png");
  $img.attr('title','Active').attr('alt', 'Active');
  }
  if(feed_id = $img.closest('tr').attr('data-id')) {
  $.post(ajaxurl,{action:"<?php echo cfx_form::$id ?>_form_status_toggle",vx_nonce:'<?php echo $nonce; ?>',form_id:feed_id,status:is_active ? 0 : 1})
  }
  });
            
});          
</script>  
<style type="text/css">
.vx_toggle_status{
    cursor: pointer;
}
</style>      
<?php
} 