<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php $piereg = PieReg_Base::get_pr_global_options();

$_available_in_pro  = ' - <span style="color:red;">'. __("Available in premium version","pie-register") . '</span>';

?>
<div class="pieregister-admin">
<div class="pie_restrictions_area">
    <div class="settings pad_bot_none">
        <h2 class="headingwidth"><?php _e("User Control",'pie-register') ?> <?php echo wp_kses_post($_available_in_pro); ?></h2>
        
        <div class="invite-tabs restrictions-tabs clearfix">
          <ul>
            <li <?php if(!isset($_GET['allowed_users'])){ echo 'class="invite-active"'; } ?>><a href="admin.php?page=pie-black-listed-users&block_ussers"><?php _e("Block Users","pie-register") ?></a></li>
            <li <?php if(isset($_GET['allowed_users'])){ echo 'class="invite-active"'; } ?>><a href="admin.php?page=pie-black-listed-users&allowed_users"><?php _e("Allow Users","pie-register") ?></a></li>
          </ul>
        </div>
  </div>
    <div id="container" class="pieregister-admin pieregister-admin-restrictions">
        <?php 
          if(!isset($_GET['allowed_users'])){
            $this->require_once_file(PIEREG_DIR_NAME.'/menus/restrictions/PieRegRestrictUsers.php');
          } else {
            $this->require_once_file(PIEREG_DIR_NAME.'/menus/restrictions/PieRegAllowedUsers.php');
          }

         ?>
        
    </div> 
</div>
</div>