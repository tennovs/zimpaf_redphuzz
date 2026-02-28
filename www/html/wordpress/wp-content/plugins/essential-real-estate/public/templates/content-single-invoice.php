<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post;
?>
<div id="invoice-<?php the_ID(); ?>" <?php post_class('ere-invoice-single-wrap'); ?>>
<?php
/**
 * ere_single_invoice_before_summary hook.
 */
do_action( 'ere_single_invoice_before_summary' );
?>
<?php
/**
 * ere_single_invoice_summary hook.
 *
 * @hooked single_invoice - 5
 */
do_action( 'ere_single_invoice_summary' ); ?>
<?php
/**
 * ere_single_invoice_after_summary hook.
 */
do_action( 'ere_single_invoice_after_summary' );
?>
</div>