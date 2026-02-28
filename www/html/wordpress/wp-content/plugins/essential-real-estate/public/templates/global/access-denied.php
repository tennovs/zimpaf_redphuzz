<?php
/**
 * @var $type
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
do_action('ere_access_denied_before', sanitize_title($type), $type);
?>
<div class="ere-access-denied">
    <div class="ere-message alert alert-success" role="alert">
        <?php
        switch ($type) :
            case 'not_login' :
                ?>
                <p class="ere-account-sign-in"><?php esc_html_e('You need login to continue.', 'essential-real-estate'); ?>
                    <button title="<?php esc_attr_e('Login Or Register', 'essential-real-estate'); ?>" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ere_signin_modal">
                        <?php esc_html_e('Login Or Register', 'essential-real-estate'); ?>
                    </button>
                </p>
                <?php
                break;
            case 'not_permission' :
                echo wp_kses_post(__('<strong>Access Denied!</strong> You can\'t access this feature', 'essential-real-estate')) ;
                break;
            case 'not_allow_submit' :
                $enable_submit_property_via_frontend = ere_get_option('enable_submit_property_via_frontend', 1);
                $user_can_submit = ere_get_option('user_can_submit', 1);
                $is_agent = ere_is_agent();
                if($enable_submit_property_via_frontend!=1)
                {
                    echo wp_kses_post(__('<strong>Access Denied!</strong> You can\'t access this feature', 'essential-real-estate'));
                }
                else{
                    if($user_can_submit!=1)
                    {
                        echo wp_kses_post(__('<strong>Access Denied!</strong> You need to become an agent to access this feature.', 'essential-real-estate'));
                    }
                }
                break;
            default :
                do_action('ere_access_denied_' . sanitize_title($type), $type);
                break;
        endswitch;
        ?></div>
    <?php if($type=='not_allow_submit'):?>
    <a class="btn btn-primary" href="<?php echo ere_get_permalink('my_profile'); ?>"
       title="<?php esc_attr_e('Go to My Profile to become an agent', 'essential-real-estate') ?>"><?php esc_html_e('Become an agent', 'essential-real-estate') ?></a>
    <?php endif;
    if($type=='not_permission'):?>
        <a class="btn btn-primary" href="<?php echo ere_get_permalink('my_profile'); ?>"
           title="<?php esc_attr_e('Go to Dashboard', 'essential-real-estate') ?>"><?php esc_html_e('My Profile', 'essential-real-estate') ?></a>
    <?php endif;?>
    <a class="btn btn-default" href="<?php echo home_url(); ?>"
       title="<?php esc_attr_e('Go to Home Page', 'essential-real-estate') ?>"><?php esc_html_e('Home Page', 'essential-real-estate') ?></a>
</div>
<?php
do_action('ere_access_denied_after', sanitize_title($type), $type);