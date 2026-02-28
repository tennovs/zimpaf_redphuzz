<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 *  @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$redirect='login';
extract( shortcode_atts( array(
    'redirect'       => 'login'
), $atts ) );
$redirect_url = ere_get_permalink('login');
if($redirect!='login')
{
    $redirect_url='';
}
$register_terms_condition = ere_get_option('register_terms_condition');
$enable_password = ere_get_option('enable_password',0);
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'register');
?>
<div class="ere-register-wrap">
    <div class="ere_messages message"></div>
    <form class="ere-register" method="post" enctype="multipart/form-data">
        <div class="form-group control-username">
            <input name="user_login" class="form-control control-icon" type="text"
                   placeholder="<?php esc_attr_e('Username', 'essential-real-estate'); ?>"/>
        </div>
        <div class="form-group control-email">
            <input name="user_email" type="email" class="form-control control-icon"
                   placeholder="<?php esc_attr_e('Email', 'essential-real-estate'); ?>"/>
        </div>

        <?php if ($enable_password) { ?>
            <div class="form-group control-password">
                <input name="user_password" class="form-control control-icon"
                       placeholder="<?php esc_attr_e('Password', 'essential-real-estate'); ?>" type="password"/>
            </div>
            <div class="form-group control-ere-password">
                <input name="user_password_retype" class="form-control control-icon"
                       placeholder="<?php esc_attr_e('Retype Password', 'essential-real-estate'); ?>" type="password"/>
            </div>
        <?php } ?>
        <div class="form-group control-term-condition">
            <div class="checkbox">
                <label>
                    <input name="term_condition" type="checkbox">
                    <?php echo sprintf(wp_kses(__('I agree with your <a target="_blank" href="%s">Terms & Conditions</a>', 'essential-real-estate'), array(
                        'a' => array(
                            'target'=> array(),
                            'href' => array()
                        )
                    )), get_permalink($register_terms_condition)); ?>
                </label>
            </div>
        </div>
        <?php if (ere_enable_captcha('register')) {do_action('ere_generate_form_recaptcha');} ?>
        <input type="hidden" name="ere_register_security"
               value="<?php echo wp_create_nonce('ere_register_ajax_nonce'); ?>"/>
        <input type="hidden" name="action" value="ere_register_ajax">
        <button type="submit" data-redirect-url="<?php echo esc_url($redirect_url); ?>"
                class="ere-register-button btn btn-primary btn-block"><?php esc_html_e('Register', 'essential-real-estate'); ?></button>
    </form>
</div>
