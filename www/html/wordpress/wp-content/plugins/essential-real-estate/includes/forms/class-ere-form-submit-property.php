<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (!class_exists('ERE_Form_Submit_Property')) {
	/**
	 * Class ERE_Form_Submit_Property
	 */
	class ERE_Form_Submit_Property extends ERE_Form
	{
		public $form_name = 'submit-property';
		protected $property_id;
		protected static $_instance = null;
		/**
		 * Main Instance
		 * @return null|ERE_Form_Submit_Property
		 */
		public static function instance()
		{
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			add_action('wp', array($this, 'process'));
			$this->steps = (array)apply_filters('submit_property_steps', array(
				'submit' => array(
					'name' => __('Submit Details', 'essential-real-estate'),
					'view' => array($this, 'submit'),
					'handler' => array($this, 'submit_handler'),
					'priority' => 10
				),
				'done' => array(
					'name' => __('Done', 'essential-real-estate'),
					'view' => array($this, 'done'),
					'priority' => 20
				)
			));

			uasort($this->steps, array($this, 'sort_by_priority'));

			if (isset($_POST['step'])) {
				$this->step = is_numeric(wp_unslash($_POST['step'])) ? max(absint(wp_unslash($_POST['step'])), 0) : array_search(wp_unslash($_POST['step']), array_keys($this->steps));
			} elseif (!empty($_GET['step'])) {
				$this->step = is_numeric(wp_unslash($_GET['step']) ) ? max(absint(wp_unslash($_GET['step'])), 0) : array_search(wp_unslash($_GET['step']), array_keys($this->steps));
			}

			$this->property_id = !empty($_REQUEST['property_id']) ? absint(wp_unslash($_REQUEST['property_id'])) : 0;
			$ere_property = new ERE_Property();
			if (!$ere_property->user_can_edit_property($this->property_id)) {
				$this->property_id = 0;
			}
		}
		/**
		 * Get the submitted property ID
		 * @return int
		 */
		public function get_property_id()
		{
			return absint($this->property_id);
		}

		/**
		 * Submit step
		 */
		public function submit()
		{
			ere_get_template('property/property-submit.php', array(
				'form' => $this->form_name,
				'property_id' => $this->get_property_id(),
				'action' => $this->get_action(),
				'step' => $this->get_step(),
				'submit_button_text' => apply_filters('submit_property_form_submit_button_text', esc_html__('Submit Property', 'essential-real-estate'))
			));
		}

		/**
		 * Submit handler
		 */
		public function submit_handler()
		{
			$submit_action = isset($_POST['property_form']) ? ere_clean(wp_unslash($_POST['property_form'])) : '';
			if ($submit_action === '') {
				return;
			}
			if (!is_user_logged_in()) {
				echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
				return;
			}
			try {
				$paid_submission_type = ere_get_option('paid_submission_type','no');
				$payment_page_link = ere_get_permalink('payment');
				wp_get_current_user();
				if (wp_verify_nonce(ere_clean(wp_unslash($_POST['ere_submit_property_nonce_field'])), 'ere_submit_property_action')) {
					$property_id = apply_filters('ere_submit_property', array());
					$this->property_id = $property_id;
					if ($paid_submission_type == 'per_listing') {
						$price_per_listing = ere_get_option('price_per_listing',0);
						if ($price_per_listing>0 && !empty($payment_page_link) && $submit_action != 'edit-property') {
							$return_link = add_query_arg(array('property_id' => $property_id), $payment_page_link);
							wp_redirect($return_link);
							exit();
						}
					}
					// Successful, show next step
					$this->step++;
				}
			} catch (Exception $e) {
				echo '<div class="ere-error">' . $e->getMessage() . '</div>';
				return;
			}
		}

		/**
		 * Done Step
		 */
		public function done()
		{
			do_action('ere_property_submitted', $this->property_id);
			global $current_user;
			wp_get_current_user();
			$user_email = $current_user->user_email;
			$admin_email = get_bloginfo('admin_email');
			$args = array(
				'listing_title'  =>  get_the_title($this->property_id),
				'listing_id'     =>  $this->property_id
			);
			ere_send_email( $user_email, 'mail_new_submission_listing', $args);
			ere_send_email( $admin_email, 'admin_mail_new_submission_listing', $args);

			$my_properties_page_link = ere_get_permalink('my_properties');
			$return_link = add_query_arg(array('new_id' => $this->property_id), $my_properties_page_link);
			wp_redirect($return_link);
		}
	}
}