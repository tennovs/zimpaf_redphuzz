<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$ere_ayment = new ERE_Payment();
$payment_method = isset($_GET['payment_method']) ? absint(wp_unslash($_GET['payment_method'])) : -1;
if ($payment_method == 1) {
    $ere_ayment->paypal_payment_completed();
} elseif ($payment_method == 2) {
    $ere_ayment->stripe_payment_completed();
}
?>
<div class="ere-payment-completed-wrap">
    <?php
    do_action('ere_before_payment_completed');
    if (isset($_GET['order_id']) && $_GET['order_id'] != ''):
        $order_id = absint(wp_unslash($_GET['order_id']));
        $ere_invoice = new ERE_Invoice();
        $invoice_meta = $ere_invoice->get_invoice_meta($order_id);
        ?>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php esc_html_e('My Order', 'essential-real-estate'); ?></div>
                    <ul class="list-group">
                        <li class="list-group-item"><?php esc_html_e('Order Number', 'essential-real-estate'); ?>
                            <strong class="pull-right"><?php echo esc_html($order_id); ?></strong></li>
                        <li class="list-group-item"><?php esc_html_e('Date', 'essential-real-estate'); ?>
                            <strong class="pull-right"><?php echo get_the_date('', $order_id); ?></strong></li>
                        <li class="list-group-item"><?php esc_html_e('Total', 'essential-real-estate'); ?>
                            <strong class="pull-right"><?php echo ere_get_format_money($invoice_meta['invoice_item_price']); ?></strong></li>
                        <li class="list-group-item"><?php esc_html_e('Payment Method', 'essential-real-estate'); ?>
                            <strong class="pull-right">
                                <?php echo ERE_Invoice::get_invoice_payment_method($invoice_meta['invoice_payment_method']);  ?>
                            </strong>
                        </li>
                        <li class="list-group-item"><?php esc_html_e('Payment Type', 'essential-real-estate'); ?>
                            <strong class="pull-right">
                                <?php echo ERE_Invoice::get_invoice_payment_type($invoice_meta['invoice_payment_type']);  ?>
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="ere-heading">
                    <h2><?php echo ere_get_option('thankyou_title_wire_transfer',''); ?></h2>
                </div>
                <div class="ere-thankyou-content">
                    <?php
                    $html_info=ere_get_option('thankyou_content_wire_transfer','');
                    echo wpautop($html_info); ?>
                </div>
                <a href="<?php echo ere_get_permalink('my_properties'); ?>"
                   class="btn btn-primary"> <?php esc_html_e('Go to Dashboard', 'essential-real-estate'); ?> </a>
            </div>
        </div>
    <?php else: ?>
        <div class="ere-heading">
            <h2><?php echo ere_get_option('thankyou_title',''); ?></h2>
        </div>
        <div class="ere-thankyou-content">
            <?php
            $html_info=ere_get_option('thankyou_content','');
            echo wpautop($html_info); ?>
           </div>
        <a href="<?php echo ere_get_permalink('my_properties'); ?>"
           class="btn btn-primary"> <?php esc_html_e('Go to Dashboard', 'essential-real-estate'); ?> </a>
    <?php endif;
    do_action('ere_after_payment_completed');
    ?>
</div>