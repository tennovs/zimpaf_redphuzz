<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!is_user_logged_in()){
    echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
    return;
}
$allow_submit=ere_allow_submit();
if (!$allow_submit)
{
    echo ere_get_template_html('global/access-denied.php',array('type'=>'not_permission'));
    return;
}
$package_id = isset($_GET['package_id']) ? absint(wp_unslash($_GET['package_id']))  : '';
$property_id = isset($_GET['property_id']) ? absint(wp_unslash($_GET['property_id']))  : '';
$is_upgrade = isset($_GET['is_upgrade']) ? absint(wp_unslash($_GET['is_upgrade']))  : '';
if ($is_upgrade == 1) {
    $prop_featured = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', true);
    if ($prop_featured == 1) {
        wp_redirect(home_url());
    }
}
if (empty($package_id) && empty($property_id)) {
    wp_redirect(home_url());
}
$ere_property = new ERE_Property();
if (!empty($property_id) && !$ere_property->user_can_edit_property($property_id)) {
    wp_redirect(home_url());
}
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'payment');
set_time_limit(700);
$paid_submission_type = ere_get_option('paid_submission_type','no');
?>
<div class="payment-wrap">
    <?php
    do_action('ere_payment_before');
    if ($paid_submission_type == 'per_package') {
        ere_get_template('payment/per-package.php');
    } else if ($paid_submission_type == 'per_listing') {
        if ($is_upgrade == 1) {
            ere_get_template('payment/per-listing-upgrade.php');
        } else {
            ere_get_template('payment/per-listing.php');
        }
    }
    wp_nonce_field('ere_payment_ajax_nonce', 'ere_security_payment');
    do_action('ere_payment_after');
    ?>
</div>