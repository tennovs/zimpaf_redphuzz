<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$property_id = isset($_GET['property_id']) ? absint(wp_unslash($_GET['property_id']))  : 0;
$terms_conditions = ere_get_option('payment_terms_condition');
$enable_paypal = ere_get_option('enable_paypal',1);
$enable_stripe = ere_get_option('enable_stripe',1);
$enable_wire_transfer = ere_get_option('enable_wire_transfer',1);
$price_per_listing = ere_get_option('price_per_listing',0);
$price_featured_listing = ere_get_option('price_featured_listing',0);
$price_per_listing_with_featured = intval($price_per_listing) + intval($price_featured_listing);
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="ere-payment-for panel panel-default">
            <div class="ere-package-title panel-heading"><?php esc_html_e('Choose Option', 'essential-real-estate'); ?></div>
            <ul class="list-group">
                <li class="list-group-item">
            <span
                class="badge"><?php echo ere_get_format_money($price_per_listing); ?></span>
                    <label>
                        <input type="radio" class="ere_payment_for" name="ere_payment_for" value="1" checked>
                        <?php esc_html_e('Submission standard', 'essential-real-estate'); ?>
                    </label>
                </li>
                <li class="list-group-item">
            <span
                class="badge"><?php echo ere_get_format_money($price_per_listing_with_featured); ?></span>
                    <label>
                        <input type="radio" class="ere_payment_for" name="ere_payment_for" value="2">
                        <?php esc_html_e('Submission with featured', 'essential-real-estate'); ?>
                    </label>
                </li>
                <?php
                $per_listing_expire_days=ere_get_option('per_listing_expire_days',0);
                if($per_listing_expire_days==1):?>
                <li class="list-group-item list-group-item-info">
                    <?php
                    $number_expire_days=ere_get_option('number_expire_days',0);
                    printf( _n( 'Note: Number expire days: <strong>%s day</strong>', 'Note: Number expire days: <strong>%s days</strong>', $number_expire_days, 'essential-real-estate' ), $number_expire_days );
                    ?>
                </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="ere-payment-method-wrap">
            <div class="ere-heading">
                <h2><?php esc_html_e('Payment Method','essential-real-estate'); ?></h2>
            </div>
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
                    $ere_payment->stripe_payment_per_listing($property_id, $price_per_listing);
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
	        <?php do_action('ere_select_payment_method', 1) ?>
        </div>
        <input type="hidden" id="ere_property_id" name="ere_property_id" value="<?php echo esc_attr($property_id); ?>">

        <p class="terms-conditions"><i class="fa fa-hand-o-right"></i> <?php echo (wp_kses_post(sprintf(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> first', 'essential-real-estate'),get_permalink($terms_conditions)))); ?></p>
        <button id="ere_payment_listing" type="button"
                class="btn btn-success btn-submit"> <?php esc_html_e('Pay Now', 'essential-real-estate'); ?> </button>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('.ere_payment_for').change(function(){
            if( jQuery(this).val() == 1 ){
                jQuery("#ere_stripe_per_listing script").attr("data-amount","<?php echo esc_js(intval($price_per_listing*100)); ?>");
                jQuery("#ere_stripe_per_listing input[name='payment_money']").val("<?php echo esc_js(intval($price_per_listing*100)); ?>");
                jQuery("#ere_stripe_per_listing input[name='payment_for']").val("1");
            }
            if( jQuery(this).val() == 2 ){
                jQuery("#ere_stripe_per_listing script").attr("data-amount","<?php echo esc_js(intval($price_per_listing_with_featured*100)); ?>");
                jQuery("#ere_stripe_per_listing input[name='payment_money']").val("<?php echo esc_js(intval($price_per_listing_with_featured*100)) ; ?>");
                jQuery("#ere_stripe_per_listing input[name='payment_for']").val("2");
            }
        });
    });
</script>