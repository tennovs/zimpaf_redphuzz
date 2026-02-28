<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
global $piereg_dir_path;
if( file_exists(PIEREG_DIR_NAME."/classes/custom_user_role_table.php") )
  include_once( PIEREG_DIR_NAME."/classes/custom_user_role_table.php");

$_disable       = true;
?>
<form method="post" action="" id="del_role_form">
    <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_user_role_delete_nonce','piereg_user_role_delete_nonce'); ?>
    <input type="hidden" id="role_del_id" name="role_del_id" value="0" />
    <input type="hidden" id="role_del_key" name="role_del_key" value="0" />
</form>

<div class="pieregister-admin">
    <div class="pie_user_roles_area invitation">
        <div class="settings pad_bot_none">
            <h2 class="headingwidth"><?php _e("User Roles",'pie-register') ?></h2>
            <?php

                if( isset($this->pie_post_array['error']) && !empty( $this->pie_post_array['error'] ) )
                    echo '<div style="clear: both;float: none;"><p class="error">' . esc_html($this->pie_post_array['error']) . "</p></div>";

                if(isset($this->pie_post_array['notice']) && !empty($this->pie_post_array['notice']) )
                    echo '<div id="message" class="updated fade msg_belowheading ext-space"><p><strong>' . esc_html($this->pie_post_array['notice']) . '.</strong></p></div>';

            ?>
            <div class="clearfix">
            <ul>
                <li> <?php _e("Create Custom User Roles","pie-register") ?> </li>
            </ul>
            </div>
        </div> 

        <fieldset class="piereg_fieldset_area-nobg" <?php disabled($_disable, true, true); ?>>
        <form method="post" action="">
        <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_user_role_nonce','piereg_user_role_nonce'); ?>
        <ul class="bg-white clearfix settings invite-form">
            <h3 class="inactive-promessage"><?php _e("Available in premium version","pie-register");?></h3>
            <li class="clearfix">
                <div class="fields">
                <div  class="cols-2">
                    <h3>
                    <?php _e("Role Key *","pie-register");?>
                    </h3>
                </div><!-- cols-3 -->
                <div class="cols-3">
                    <input <?php disabled($_disable, true, true) ?> id="piereg_role_key" name="piereg_role_key" class="input_fields2" style="float:left;">
                    <span style="text-align:left;" class="note pie_usage_note">
                    <?php _e("Key value of the role which will be saved in the database. This will not be visible on the front-end. This can be the same as Role Name.","pie-register");?>
                    </span>
                </div><!-- cols-3 -->
                </div>
            </li>
            <li class="clearfix code_usageItem">
                <div class="fields">
                <div class="cols-2">
                    <h3>
                    <?php _e("Role Name *","pie-register");?>
                    </h3>
                </div><!-- cols-3 -->
                <div class="cols-3">
                    <input style="float:left;" type="text" id="user_role_name" name="user_role_name" class="input_fields2" />
                    <span style="text-align:left;" class="note pie_usage_note">
                    <?php _e("Name of the Role that will be displayed on the front-end and the dashboard.","pie-register");?>
                    </span>
                </div><!-- cols-3 -->
                </div>
            </li>
            <li class="clearfix code_expiryDate">
                <div class="fields">
                <div class="cols-2">
                    <h3>
                    <?php _e("Inherit Permissions","pie-register");?>
                    </h3>
                </div><!-- cols-3 -->
                <div class="cols-3">
                    <select id="inherit_wp_roles" name="inherit_wp_roles" >
                    <?php 
                        global $wp_roles;
			
                        $role = $wp_roles->roles;
                        $wp_default_user_role = get_option("default_role");

                        foreach($role as $key=>$value)
                        {
                    ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['name']); ?></option>;
                    <?php
                        }


                    ?>
                    </select>
                    <span style="text-align:left;" class="note pie_usage_note" style="color:red;">
                            <?php _e("Inherit viewing and editing permissions for this custom Role from one of default Wordpress roles.","pie-register");?>
                        </span>
                </div><!-- cols-3 -->
                </div>
            </li>
            
            <li class="clearfix">
            <div class="fields fields_submitbtn">
                <div class="cols-2">&nbsp;</div><!-- cols-3 -->
                <div class="cols-3 text-right">
                <input name="add_role" class="submit_btn" value="<?php _e('Add Role','pie-register');?>" type="submit" />
                </div><!-- cols-3 -->
            </div>
            </li>

        </ul>
        </form>  
        </fieldset>
        <div style="float:left;">
            <form method="post" onsubmit="return get_selected_roles_ids();" >
            <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_custom_role_bulk_option_nonce','piereg_custom_role_bulk_option_nonce'); ?>
            <input type="hidden" value="" name="select_custom_role_bulk_option" id="select_custom_role_bulk_option">
                <select name="custom_role_bulk_option" id="custom_role_bulk_option">
                <option selected="selected" value="0">
                <?php _e("Bulk Actions","pie-register"); ?>
                </option>
                <option value="delete">
                <?php _e("Delete","pie-register"); ?>
                </option>
            </select>
            <input type="submit" value="<?php _e("Apply","pie-register"); ?>" class="button action" id="doaction" name="btn_submit_custom_role_bulk_option">
            </form>
            <span style="color:#F00;display:none;" id="custom_role_error"><?php _e("Select Bulk Option and also User Role","pie-register");?></span>
        </div> 
        <?php 
        $Pie_Custom_Role_Table = new Pie_Custom_Role_Table();
            $Pie_Custom_Role_Table->prepare_items();
        $Pie_Custom_Role_Table->display();
            ?> 
    </div>
</div>