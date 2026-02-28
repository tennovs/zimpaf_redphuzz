<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/01/16
 * Time: 5:11 PM
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$redirect='my_profile';
extract( shortcode_atts( array(
    'redirect'       => 'my_profile'
), $atts ) );
wp_enqueue_script( ERE_PLUGIN_PREFIX . 'login');
$redirect_url = ere_get_permalink('my_profile');
if($redirect!='my_profile')
{
    $redirect_url='';
}
?>
<div class="ere-login-wrap">
    <div class="ere_messages message"></div>
    <form class="ere-login" method="post" enctype="multipart/form-data">
        <div class="form-group control-username">
            <input name="user_login" class="form-control control-icon login_user_login"
                   placeholder="<?php esc_attr_e('Username or email address', 'essential-real-estate'); ?>" type="text"/>
        </div>
        <div class="form-group control-password">
            <input name="user_password" class="form-control control-icon"
                   placeholder="<?php esc_attr_e('Password', 'essential-real-estate'); ?>" type="password"/>
        </div>
        <div class="checkbox">
            <label>
                <input name="remember" type="checkbox">
                <?php esc_html_e('Remember me', 'essential-real-estate'); ?>
            </label>
        </div>
        <input type="hidden" name="ere_security_login"
               value="<?php echo wp_create_nonce('ere_login_ajax_nonce'); ?>"/>
        <input type="hidden" name="action" value="ere_login_ajax">
        <a href="javascript:void(0)" class="ere-reset-password"><?php esc_html_e('Lost password','essential-real-estate')?></a>
	    <?php if (ere_enable_captcha('login')) {do_action('ere_generate_form_recaptcha');} ?>
        <button type="submit" data-redirect-url="<?php echo esc_url($redirect_url); ?>"
                class="ere-login-button btn btn-primary btn-block"><?php esc_html_e('Login', 'essential-real-estate'); ?></button>
    </form>
    <hr>
    <?php
    $enable_social_login = ere_get_option('enable_social_login',1);
    if ($enable_social_login == 1) {
        if( has_action('wordpress_social_login') ){ do_action( 'wordpress_social_login' ); }
    }
    ?>
</div>
<div class="ere-reset-password-wrap" style="display: none">
    <?php echo ere_get_template_html('account/reset-password.php'); ?>
    <a href="javascript:void(0)" class="ere-back-to-login"><?php esc_html_e('Back to Login','essential-real-estate')?></a>
</div>