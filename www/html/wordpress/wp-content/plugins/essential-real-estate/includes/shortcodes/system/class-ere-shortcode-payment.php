<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Payment')) {
	/**
	 * ERE_Shortcode_Payment class.
	 */
	class ERE_Shortcode_Payment
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			add_shortcode('ere_payment', array($this, 'payment_shortcode'));
			add_shortcode('ere_payment_completed', array($this, 'payment_completed_shortcode'));
		}

		/**
		 * Payment shortcode
		 */
		public function payment_shortcode()
		{
			return ere_get_template_html('payment/payment.php');
		}

		/**
		 * Payment completed shortcode
		 */
		public function payment_completed_shortcode()
		{
			return ere_get_template_html('payment/payment-completed.php');
		}
	}
}
new ERE_Shortcode_Payment();