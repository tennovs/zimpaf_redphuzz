<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 

$options = $this->get_pr_global_options();
global $piereg_dir_path;
if( file_exists(PIEREG_DIR_NAME."/classes/pie_redirect_settings.php") )
	include_once( PIEREG_DIR_NAME."/classes/pie_redirect_settings.php");

	$_disable 			= true;
	$_available_in_pro 	= ' - <span style="color:red;">'. __("Available in premium version","pie-register") . '</span>';    	
?>
<div id="role_based_redirects">
<p><strong><?php _e("Note",'pie-register') ?>:</strong> <?php _e("Page settings on the Role Based Redirect tab will always override page settings on the All Users tab",'pie-register') ?>.</p>
<div class="settings piereg_added_area roles_container" style="padding-bottom:0px;margin-left:0px;">
<fieldset class="piereg_fieldset_area" <?php disabled($_disable, true, true); ?>>
  
      <legend><?php echo __("Add Record",'pie-register') . wp_kses_post($_available_in_pro); ?></legend>

  <form method="post" id="redirect_form">
    <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_redirect_settings_nonce','piereg_redirect_settings_nonce'); ?>
    <?php
		$input_user_role = $input_logged_in = $logged_in_page_id = $input_logout = $log_out_page_id = "";
		$is_add_new = true;
		
		if(isset($this->pie_post_array['redirect_settings_add_new']) && !isset($this->pie_post_array['notice']) ){
			$input_user_role 	= ((isset($this->pie_post_array['piereg_user_role']))?$this->pie_post_array['piereg_user_role']:"");
			$input_logged_in 	= ((isset($this->pie_post_array['logged_in_url']))?$this->pie_post_array['logged_in_url']:"");
			$logged_in_page_id 	= ((isset($this->pie_post_array['log_in_page']))?$this->pie_post_array['log_in_page']:"");
			$input_logout 		= ((isset($this->pie_post_array['log_out_url']))?$this->pie_post_array['log_out_url']:"");
			$log_out_page_id 	= ((isset($this->pie_post_array['log_out_page']))?$this->pie_post_array['log_out_page']:"");
		}
		?>
    <div class="fields" style="width:100%;">
      <div class="fields">
        <label for="piereg_user_role"><?php _e("User Role",'pie-register') ?></label>
        <?php
			$PieRedirectSettings = new PieRedirectSettings();
			$PieRedirectSettings->set_order();
			$PieRedirectSettings->set_orderby();
			$all_user_roles = $PieRedirectSettings->get_sql_results("`user_role`");
			$saved_user_roles = array();
			foreach($all_user_roles as $val) {
				if($val->user_role)
					$saved_user_roles[$val->user_role] = $val->user_role;
			}
			
			$user_role = "";
			
			if(!empty($input_user_role)) {
				$user_role = $input_user_role;
				
			} 
			?>
        <select id="piereg_user_role" name="piereg_user_role" >
        <?php
			global $wp_roles;
			$role = $wp_roles->roles;
			
			$piereg_user_role = (!empty($user_role))?$user_role:"";
			foreach($role as $key=>$value) {
				if(in_array($key,$saved_user_roles) && ($piereg_user_role != $key))
					continue;
				
				echo '<option value="'.esc_attr($key).'"';
				selected($piereg_user_role == $key, true, true);
				echo '>'.esc_html($value['name']).'</option>';
			}
			?>
        </select>
      </div>
    </div>
    <div class="fields" style="width:100%;">
      <div class="fields">
        <label for="log_in_page">
          <?php _e("After Log In Page",'pie-register') ?>
        </label>
        <?php 
			$args 	= array("show_option_no_change"=>"None","id"=>"log_in_page","name"=>"log_in_page","selected"=>$logged_in_page_id,"echo"=>false);
			$pages	= wp_dropdown_pages( $args );
			$url	= '<option value="0"'; 
			if($logged_in_page_id == "0") $url.=' selected="selected"'; 
			$url.='>&lt;URL&gt;</option></select>';
			$pages	= str_replace('</select>', $url, $pages);
			echo wp_kses($pages,$this->piereg_forms_get_allowed_tags());
			?>
      </div>
      <div class="fields <?php echo ($logged_in_page_id == "0") ? "": "hide"; ?>">
        <label for="logged_in_url"></label>
        <input type="url" name="logged_in_url" id="logged_in_url" value="<?php echo esc_url(urldecode($input_logged_in)); ?>" class="input_fields" />
      </div>
    </div>
    <div class="piereg_clear"></div>
    <div class="fields" style="width:100%;">
      <div class="fields">
        <label for="log_out_page">
          <?php _e("After Log out Page",'pie-register') ?>
        </label>
        <?php 
			$args 	= array("show_option_no_change"=>"None","id"=>"log_out_page","name"=>"log_out_page","selected"=>$log_out_page_id,"echo"=>false);
			$pages2	= wp_dropdown_pages( $args );
			$url2	= '<option value="0"'; 
			if($log_out_page_id == "0") $url2.=' selected="selected"'; 
			$url2.='>&lt;URL&gt;</option></select>';
			$pages2	= str_replace('</select>', $url2, $pages2);
			echo wp_kses($pages2,$this->piereg_forms_get_allowed_tags());
			?>
      </div>
      <div class="fields <?php echo ($log_out_page_id == "0") ? "": "hide"; ?>">
        <label for="log_out_url"></label>
        <input type="url" name="log_out_url" id="log_out_url" value="<?php echo esc_url(urldecode($input_logout)); ?>" class="input_fields" />
      </div>
    </div>
    <div class="fields">
      <?php if(!$is_add_new){ ?>
          <input type="submit" class="submit_btn submit_btn_mar_ryt2" name="redirect_settings_update" value=" <?php _e("Update","pie-register");?> " />
          <a href="<?php echo esc_url(admin_url('admin.php?page=pie-settings&tab=pages&subtab=role-based')) ?>" style="float:right;margin:19px 10px 0 0;">Go back to add new record</a>
      <?php }else{?>
      	<input type="submit" class="submit_btn submit_btn_mar_ryt2" name="redirect_settings_add_new" value=" <?php _e("Save Record","pie-register");?> " />
      <?php } ?>
    </div>
  </form>
</fieldset>
</div>
<div class="piereg_clear"></div>
<div class="piereg_clear"></div>
<?php
/*
*	Add Table
*/
$PieRedirectSettings = new PieRedirectSettings();
$PieRedirectSettings->set_order();
$PieRedirectSettings->set_orderby();
$PieRedirectSettings->prepare_items();
$PieRedirectSettings->display();
?>

<form method="post" action="" id="redirect_settings_del_form">
  <input type="hidden" id="redirect_settings_del_id" name="redirect_settings_del_id" value="0" />
  <input type="submit" style="display:none;" />
</form>
<form method="post" action="" id="redirect_settings_status_form">
  <input type="hidden" id="redirect_settings_status_id" name="redirect_settings_status_id" value="0" />
  <input type="submit" style="display:none;" />
</form>
</div>