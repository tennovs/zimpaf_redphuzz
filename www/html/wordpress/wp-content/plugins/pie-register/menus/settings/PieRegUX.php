<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php $piereg = PieReg_Base::get_pr_global_options(); $_available_in_pro = "";?>
<div class="forms_max_label ux_wrap">
<form action="" method="post" id="frm_settings_ux">
<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_settings_ux','piereg_settings_ux'); ?>
<?php if( (isset($_GET['tab']) && $_GET['tab'] == 'ux') && (isset($_GET['subtab']) && $_GET['subtab'] == 'advanced')) { ?>
        <?php
          $_disable 			= true;
          $_available_in_pro 	= ' - <span style="color:red;font-size:14px;">'. __("Available in premium version","pie-register") . '</span>';
			  ?>
        <h3><?php echo __("Pie Register Theme",'pie-register') . wp_kses_post($_available_in_pro) ?></h3>
        <div class="fields">
        	<div class="flt_lft width_full">
            <label for="piereg_pr_theme"><?php _e("Select Theme",'pie-register') ?></label>
            <select <?php disabled($_disable, true, true); ?> name="pr_theme" id="piereg_pr_theme">
                <option value="0" <?php selected(isset($piereg['pr_theme']) && $piereg['pr_theme'] == 0, true, true); ?>>
                    <?php _e("Theme Default","pie-register"); ?>
                </option>
                <?php 
                    $theme_name_array 		= array();
                    $theme_name_array[1] 	= 'Black Cherry';
                    $theme_name_array[2]	= 'Fresh Blue';
                    $theme_name_array[3] 	= 'Digital Pink';
                    $theme_name_array[4] 	= 'Dull Blue';
                    $theme_name_array[5] 	= 'Yellow Stroke';
                    $theme_name_array[6] 	= 'Glossy Spring';
                    $theme_name_array[7] 	= 'Eco Green';
                    $theme_name_array[8] 	= 'Soft Pink';
                    $theme_name_array[9] 	= 'Tangerine';
                
                for($x = 1;$x <= 9; $x++){ ?>
                    <?php $theme_name = $theme_name_array[$x]; ?>
                    <option value="<?php echo esc_attr($x) ?>" <?php selected(isset($piereg['pr_theme']) && $piereg['pr_theme'] == $x, true, true) ?>>
                        <?php _e($theme_name,"pie-register"); ?>
                    </option>
                <?php } ?>
            </select>
            <input type="hidden" name="is_advanced" value="1" />
            </div>
            <span class="quotation"><?php _e("Select a theme for Pie Register.",'pie-register') ?></span>
        </div>
        <div class="fields fields_submitbtn">
            <input name="action" value="pie_reg_settings" type="hidden" />
            <input <?php disabled($_disable, true, true); ?> type="submit" class="submit_btn" value="<?php _e("Save Settings","pie-register"); ?>" />
        </div>        
<?php } else { ?>	
	
    <div class="fields">
        <div class="radio_fields">
            <input type="checkbox" name="display_hints" id="display_hints" value="1" <?php checked($piereg['display_hints']=="1", true, true); ?> />
        </div>
        <label class="label_mar_top" for="display_hints">
			<?php _e("Show tips and hints on the form editor tool.",'pie-register') ?>
        </label>        
    </div>
    
    <hr class="seperator">
    
    <h3>
		<?php _e("Login Form",'pie-register'); ?>
      </h3>
      <div class="fields">
        <label for="login_username_label">
          <?php _e("Username Label",'pie-register') ?>
        </label>
        <input type="text" name="login_username_label" id="login_username_label" value="<?php echo esc_attr($piereg['login_username_label']); ?>" class="input_fields" />
      </div>
      <div class="fields">
        <label for="login_username_placeholder">
          <?php _e("Username Placeholder",'pie-register') ?>
        </label>
        <input type="text" name="login_username_placeholder" id="login_username_placeholder" value="<?php echo esc_attr($piereg['login_username_placeholder']); ?>" class="input_fields" />
      </div>
      <div class="fields">
        <label for="login_password_label">
          <?php _e("Password Label",'pie-register') ?>
        </label>
        <input type="text" name="login_password_label" id="login_password_label" value="<?php echo esc_attr($piereg['login_password_label']); ?>" class="input_fields" />
      </div>
      <div class="fields">
        <label for="login_password_placeholder">
          <?php _e("Password Placeholder",'pie-register') ?>
        </label>
        <input type="text" name="login_password_placeholder" id="login_password_placeholder" value="<?php echo esc_attr($piereg['login_password_placeholder']); ?>" class="input_fields" />
      </div>
  
    <hr class="seperator">
    
    <h3>
		<?php _e("Forgot Password Form",'pie-register'); ?>
      </h3>
      <div class="fields">
        <label for="forgot_pass_username_label">
          <?php _e("Username Label",'pie-register') ?>
        </label>
        <input type="text" name="forgot_pass_username_label" id="forgot_pass_username_label" value="<?php echo esc_attr($piereg['forgot_pass_username_label']); ?>" class="input_fields" />
      </div>
      <div class="fields">
        <label for="forgot_pass_username_placeholder">
          <?php _e("Username Placeholder",'pie-register') ?>
        </label>
        <input type="text" name="forgot_pass_username_placeholder" id="forgot_pass_username_placeholder" value="<?php echo esc_attr($piereg['forgot_pass_username_placeholder']); ?>" class="input_fields" />
      </div>
      
       <hr class="seperator">
    
    <h3><?php _e("Custom Logo",'pie-register'); ?></h3>
    <div class="fields">
        <label for="logo"><?php _e('Custom Logo URL', 'pie-register');?></label>
        <?php wp_enqueue_script('thickbox'); ?>
        <?php
        if( ( isset($piereg['custom_logo_url']) && $piereg['custom_logo_url'] == '') && (isset($piereg['logo']) && $piereg['logo'] != '') )
        $piereg['custom_logo_url'] = $piereg['logo'];?>
        <input id="pie_custom_logo_url" type="text" name="custom_logo_url" value="<?php echo esc_url($piereg['custom_logo_url']);?>" placeholder="<?php _e("Please enter Logo URL","pie-register"); ?>" class="input_fields" />
        &nbsp;<sub><span style="font-size:16px;"><?php _e( 'OR', 'pie-register' ); ?></span></sub>&nbsp;
        <?php add_thickbox();?>
        <button id="pie_custom_logo_button" class="button" type="button" value="1" name="pie_custom_logo_button">
        <?php _e( 'Select Image to Upload', 'pie-register' ); ?>
        </button>
    </div>
    <div class="fields">
        <label for="custom_logo_title"><?php _e( 'Tooltip Text', 'pie-register' ); ?></label>
        <input type="text" name="custom_logo_tooltip" class="input_fields" id="custom_logo_title" value="<?php echo esc_attr($piereg['custom_logo_tooltip']);?>" placeholder="<?php _e("Enter logo tooltip text","pie-register"); ?>" />
        <span class="quotation"><?php _e("Show tooltip on custom logo.","pie-register"); ?></span>
    </div>
    <div class="fields">
        <label for="custom_logo_link"><?php _e( 'Link URL', 'pie-register' ); ?></label>
        <input type="text" name="custom_logo_link" class="input_fields" id="custom_logo_link" value="<?php echo esc_url($piereg['custom_logo_link']);?>" 
            placeholder="<?php _e("Enter logo Link","pie-register"); ?>" />
    </div>
    <?php if ( $piereg['custom_logo_url'] ) {?>
        <div class="fields">
            <label><?php _e( 'Selected Logo', 'pie-register' ); ?></label>
            <img class="mar_as_allinputs" src="<?php echo esc_url($piereg['custom_logo_url']);?>" alt="<?php _e( 'Custom Logo', 'pie-register' ); ?>" />
        </div>
        <div class="fields">
            <label><?php _e( 'Show Custom Logo', 'pie-register' ); ?></label>
            <div class="radio_fields mar_as_allinputs">
                <input type="radio" name="show_custom_logo" value="1" 
                    id="show_custom_logo_yes" <?php checked($piereg['show_custom_logo'] == "1", true, true) ?> />
                <label for="show_custom_logo_yes"><?php _e('Yes', 'pie-register');?></label>
                
                <input type="radio" name="show_custom_logo" value="0" 
                    id="show_custom_logo_no" <?php checked($piereg['show_custom_logo'] == "0", true, true) ?> />
                <label for="show_custom_logo_no"><?php _e('No', 'pie-register');?></label>
            </div>
        </div>
    <?php } ?>   
    
    <hr class="seperator" />
    <div class="fields">
      <div class="radio_fields">
      	<input type="checkbox" name="outputcss" id="outputcss" value="1" <?php checked($piereg['outputcss']=="1", true, true); ?> />
      </div>
      <label class="label_mar_top" for="outputcss"><?php _e("Let Pie Register generate custom CSS. Turn this off if you have themes installed that conflict with Pie Register's CSS.",'pie-register') ?></label>
    </div>
    <!--
    <div class="fields">
        <div class="radio_fields">
        	<input type="checkbox" name="outputjquery_ui" id="outputjquery_ui" value="1" <?php //echo ($piereg['outputjquery_ui']=="1")?'checked="checked"':''?>  />
        </div>
        <label class="label_mar_top" for="outputjquery_ui"><?php //_e("Let Pie Register generate jQuery UI for enhancements. Warning: Turning this off may restrict Pie Register's functionality. Do it at your own peril!",'pie-register') ?></label>
    </div>
      -->
    <h3><?php _e("Custom CSS",'pie-register'); ?></h3>
    <div class="fields">
      <!--<span class="quotation mar_left_none" ><?php //_e("If need to apply custom CSS to Pie Register, enter it here. Note: Do not use style tags.",'pie-register') ?></span>-->
      <span class="quotation mar_left_none"><?php _e("Note: Custom CSS is now deprecated. Please copy the code and paste in your theme options or use another plugin. ",'pie-register') ?></span>
      <textarea disabled="disabled" name="custom_css"><?php echo esc_textarea(html_entity_decode($piereg['custom_css'],ENT_COMPAT,"UTF-8"))?></textarea>      
    </div>
    <h3><?php _e("Tracking Code",'pie-register'); ?></h3>
    <div class="fields">
      <!--<span class="quotation mar_left_none"><?php //_e("Enter your custom tracking code (Java Script) here.",'pie-register') ?></span>-->
      <span class="quotation mar_left_none"><?php _e("Note: Tracking Code is now deprecated. Please copy the code and paste in your theme option or use another plugin. ",'pie-register') ?></span>
      <textarea disabled="disabled" name="tracking_code"><?php echo esc_textarea(html_entity_decode($piereg['tracking_code'],ENT_COMPAT,"UTF-8"))?></textarea>         
    </div>
    <div class="fields fields_submitbtn">
        <input name="action" value="pie_reg_settings" type="hidden" />
        <input type="submit" class="submit_btn" value="<?php _e("Save Settings","pie-register"); ?>" />
    </div>
<?php } ?>
</form>
</div>