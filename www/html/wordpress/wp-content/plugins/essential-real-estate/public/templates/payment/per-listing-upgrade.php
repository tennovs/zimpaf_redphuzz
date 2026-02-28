<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$property_id = isset($_GET['property_id']) ? absint(wp_unslash($_GET['property_id'])) : 0;
$terms_conditions = ere_get_option('payment_terms_condition');
$enable_paypal = ere_get_option('enable_paypal',1);
$enable_stripe = ere_get_option('enable_stripe',1);
$enable_wire_transfer = ere_get_option('enable_wire_transfer',1);

$price_featured_listing = ere_get_option('price_featured_listing',0);
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="ere-payment-for panel panel-default">
            <div class="ere-package-title panel-heading"><?php esc_html_e('Choose Option', 'essential-real-estate'); ?></div>
            <ul class="list-group">
                <li class="list-group-item">
            <span
                class="badge"><?php echo ere_get_format_money($price_featured_listing); ?></span>
                    <label>
                        <input type="radio" class="ere_payment_for" name="ere_payment_for" value="3" checked>
                        <?php esc_html_e('Upgrade to Featured', 'essential-real-estate'); ?>
                    </label>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="ere-payment-method-wrap">
            <?php if ($enable_paypal != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-paypal" name="ere_payment_method" value="paypal" checked><i
                            class="fa fa-paypal"></i>
                        <?php esc_html_e('Pay With Paypal', 'essential-real-estate'); ?>
                    </label>
                </div>
            <?php endif; ?>

            <?php if ($enable_stripe != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" class="payment-stripe" name="ere_payment_method" value="stripe">
                        <i class="fa fa-credit-card"></i> <?php esc_html_e('Pay with Credit Card', 'essential-real-estate'); ?>
                    </label>
                    <?php
                    $ere_payment = new ERE_Payment();
                    $ere_payment->stripe_payment_upgrade_listing($property_id, $price_featured_listing);
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($enable_wire_transfer != 0) : ?>
                <div class="radio">
                    <label>
                        <input type="radio" name="ere_payment_method" value="wire_transfer">
                        <i class="fa fa-send-o"></i> <?php esc_html_e('Wire transfer', 'essential-real-estate'); ?>
                    </label>
                </div>
                <div class="ere-wire-transfer-info">
                    <?php
                    $html_info=ere_get_option('wire_transfer_info','');
                    echo wpautop($html_info); ?>
                </div>
            <?php endif; ?>
	        <?php do_action('ere_select_payment_method', 3) ?>
        </div>
        <input type="hidden" id="ere_property_id" name="ere_property_id" value="<?php echo esc_attr($property_id); ?>">
        <p class="terms-conditions"
           role="alert"><?php echo wp_kses_post(sprintf(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> before click "Pay Now"', 'essential-real-estate'), get_permalink($terms_conditions))); ?></p>
        <button id="ere_upgrade_listing" type="button"
                class="btn btn-success btn-submit"> <?php esc_html_e('Pay Now', 'essential-real-estate'); ?> </button>
    </div>
</div>