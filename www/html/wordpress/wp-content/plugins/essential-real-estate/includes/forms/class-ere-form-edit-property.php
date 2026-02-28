<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include_once(ERE_PLUGIN_DIR.'includes/forms/class-ere-form-submit-property.php');
if (!class_exists('ERE_Form_Edit_Property')) {
	/**
	 * ERE_Form_Edit_Property class.
	 */
	class ERE_Form_Edit_Property extends ERE_Form_Submit_Property
	{
		public $form_name = 'edit-property';
		protected static $_instance = null;
		/**
		 * Main Instance
		 * @return null|ERE_Form_Edit_Property
		 */
		public static function instance()
		{
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->property_id = !empty($_REQUEST['property_id']) ? absint(wp_unslash($_REQUEST['property_id'])) : 0;
			$ere_property = new ERE_Property();
			if (!$ere_property->user_can_edit_property($this->property_id)) {
				$this->property_id = 0;
			}
		}

		/**
		 * output function.
		 */
		public function output($atts = array())
		{
			$this->submit_handler();
			$this->submit();
		}

		/**
		 * Submit Step
		 */
		public function submit()
		{
			if (empty($this->property_id)) {
				echo wpautop(__('Invalid listing', 'essential-real-estate'));
				return;
			}
			ere_get_template('property/property-submit.php', array(
				'form' => $this->form_name,
				'property_id' => $this->get_property_id(),
				'action' => $this->get_action(),
				'step' => $this->get_step(),
				'submit_button_text' => esc_html__('Save changes', 'essential-real-estate')
			));
		}

		/**
		 * Submit handler
		 */
		public function submit_handler()
		{
			if (empty($_POST['property_form'])) {
				return;
			}
			if (!is_user_logged_in()) {
				echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
				return;
			}
			try {
				if (wp_verify_nonce(ere_clean(wp_unslash($_POST['ere_submit_property_nonce_field'])) , 'ere_submit_property_action')) {
					$property_id = apply_filters('ere_submit_property', array());
					if($property_id<1 || is_null($property_id))
					{
						echo '<div class="ere-message alert alert-danger" role="alert">' . __('<strong>Warning!</strong> Can not edit this property', 'essential-real-estate') . '</div>';
						return;
					}
					$this->property_id = $property_id;
					do_action('ere_property_edited', $this->property_id);
				}
				$post_status=get_post_status($this->property_id);
				if($post_status=='pending')
				{
					$args = array(
						'listing_title'  =>  get_the_title($this->property_id),
						'listing_id'     =>  $this->property_id
					);
					global $current_user;
					wp_get_current_user();
					$user_email = $current_user->user_email;
					$admin_email = get_bloginfo('admin_email');
					ere_send_email( $user_email, 'mail_new_modification_listing', $args);
					ere_send_email( $admin_email, 'admin_mail_new_modification_listing', $args);
				}
				$my_properties_page_link = ere_get_permalink('my_properties');
				$return_link = add_query_arg(array('edit_id' => $this->property_id), $my_properties_page_link);
				wp_redirect($return_link);

			} catch (Exception $e) {
				echo '<div class="ere-error">' . $e->getMessage() . '</div>';
				return;
			}
		}
	}
}