<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>
    <h3 class="vx_top_head">Select Form Template </h3>
<?php
$nonce=wp_create_nonce('vx_nonce');
$link=$page_url.'&'.cfx_form::$id.'_tab_action='.$tab;
if(!empty($form_id)){ $link.='&form_id='.$form_id; }
 include_once(cfx_form_plugin_dir.'includes/form-templates.php'); 
foreach($temps as $k=>$v){
?>
<div class="vx_box_wrap">
<a href="<?php echo $link.'&id='.$k.'&vx_nonce='.$nonce ?>" class="vx_box">
<div class="vx_label"><?php echo $v['label'] ?></div>
<div class="vx_img"><img src="<?php echo cfx_form_plugin_url.'images/themes/'.$v['img'] ?>" /></div>
</a>    
</div>
<?php
}
 ?>
<style type="text/css">
#crm-panel-content * {
  -webkit-box-sizing: border-box; /* Safari 3.0 - 5.0, Chrome 1 - 9, Android 2.1 - 3.x */
  -moz-box-sizing: border-box;    /* Firefox 1 - 28 */
  box-sizing: border-box;  
}
.vx_box_wrap{
 float: left; 
  display: block;
 width: 33%; padding: 14px; height: 300px;

}
.vx_box{
  border: 3px solid #cbcbcb;
 display: block;
  overflow: hidden; 
 text-decoration: none;
 height: 100%;
}
.vx_box:hover{
    border-color: #045881;
    border-style: dashed;
}
.vx_label{
    font-size: 16px;
    font-weight: bold;
    color: #045881;
    background-color: #f6f6f6;
    padding: 14px;
}
.vx_img{
 overflow: hidden;
}
.vx_img img{  width: 100%; }
</style>      
