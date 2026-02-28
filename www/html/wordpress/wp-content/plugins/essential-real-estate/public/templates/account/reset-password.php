<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'login');
?>
<div class="ere-resset-password-wrap">
    <div class="ere_messages message ere_messages_reset_password"></div>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group control-username">
            <input name="user_login" class="form-control control-icon reset_password_user_login"
                   placeholder="<?php esc_attr_e('Enter your username or email', 'essential-real-estate'); ?>">
            <input type="hidden" name="ere_security_reset_password"
                   value="<?php echo wp_create_nonce('ere_reset_password_ajax_nonce'); ?>"/>
            <input type="hidden" name="action" value="ere_reset_password_ajax">
            <?php if (ere_enable_captcha('reset_password')) {do_action('ere_generate_form_recaptcha');} ?>
            <button type="submit"
                    class="btn btn-primary btn-block ere_forgetpass"><?php esc_html_e('Get new password', 'essential-real-estate'); ?></button>
        </div>
    </form>
</div>
