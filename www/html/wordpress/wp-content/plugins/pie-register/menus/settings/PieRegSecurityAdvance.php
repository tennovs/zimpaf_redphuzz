<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php $piereg = PieReg_Base::get_pr_global_options();

	$_disable 			= true;
	$_available_in_pro 	= ' - <span style="color:red;">'. __("Available in premium version","pie-register") . '</span>';

if( !isset($_GET['act']) || !isset($_GET['pie_id']) || !isset($_GET['option']) )
{
	?>
    <form name="frm_settings_security_advanced" action="" method="post">
    <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_settings_security_advanced','piereg_settings_security_advanced'); ?>
        <div class="fields">
        	<div class="piereg_box_style_1">
             <input <?php disabled($_disable, true, true); ?> type="checkbox" name="reg_form_submission_time_enable" id="reg_form_submission_time_enable" value="1" <?php checked($piereg['reg_form_submission_time_enable']=="1", true, true); ?> /> 
             <?php _e("Time form submission, reject form if submitted within ",'pie-register') ?>
             <input <?php disabled($_disable, true, true); ?> type="text" name="reg_form_submission_time" 
             		style="width:auto;"
                    id="reg_form_submission_time" 
                    value="<?php echo ( (isset($piereg['reg_form_submission_time']) && !empty($piereg['reg_form_submission_time'])) ? esc_attr($piereg['reg_form_submission_time']) : 0 ); ?>" 
                    class="input_fields submissionfield" 
                    />
                    <?php _e("seconds or less.",'pie-register') ?>
            <span class="quotation" style="margin-left:0px;"><?php _e("Security enhancement for forms (timed submission)",'pie-register') ?><?php echo wp_kses_post($_available_in_pro); ?></span>
            </div>
        </div>
        <div class="fields">
        	<div class="piereg_box_style_1 limit-submission">
             <?php _e("Form Submission Limit for a Device ",'pie-register') ?>
             <input <?php disabled($_disable, true, true); ?> type="text" name="reg_form_submission_limit" 
             		style="width:auto;"
                    id="reg_form_submission_limit" 
                    value="<?php echo ( (isset($piereg['reg_form_submission_limit']) && !empty($piereg['reg_form_submission_limit'])) ? esc_attr($piereg['reg_form_submission_limit']) : 0 ); ?>" 
                    class="input_fields submissionfield" 
                    /><br>
            <span class="quotation" style="margin-left:0px;"><?php _e("Limits how many times a form can be submitted from a device within a day. Helpful to prevent spams. Set it to zero(0) to disable this feature.",'pie-register') ?><?php echo wp_kses_post($_available_in_pro);  ?></span>
            </div>
        </div>
        <div class="fields">
            <input name="action" value="pie_reg_settings" type="hidden" />
            <input <?php disabled($_disable, true, true); ?> type="submit" class="submit_btn flt_none" value="<?php _e("Save Settings","pie-register"); ?>" />
        </div>
    </form>
<hr class="seperator" />    
<?php 
}
	?>
<h2 class="hydin_without_bg mar_none"><?php _e("Restrict Widgets",'pie-register') ?></h2>
    <div class="piereg_clear"></div>        
	<p><?php _e('Upgrade to premium version to use the Restrict Widgets feature. Want to learn about this feature ?','pie-register');?> 
    <a href="https://pieregister.com/manual/pie-register-features/7329-2/" target="_blank"><?php _e('Click Here','pie-register');?></a>
    </p>
