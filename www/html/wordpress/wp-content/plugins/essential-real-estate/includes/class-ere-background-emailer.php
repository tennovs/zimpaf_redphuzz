<?php
/**
 * Background Emailer
 *
 * @version  3.0.1
 * @package  WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once ERE_PLUGIN_DIR . 'includes/libraries/wp-async-request.php';
}

if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once ERE_PLUGIN_DIR . 'includes/libraries/wp-background-process.php';
}

/**
 * WC_Background_Emailer Class.
 */
class ERE_Background_Emailer extends WP_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'ere_email_process';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'shutdown', array( $this, 'dispatch_queue' ), 1000 );
	}

	protected function task( $callback ) {
		if ( isset($callback['email'] ) ) {
			//error_log(json_encode($callback));
			try {
				$email=$callback['email'];
				$email_type=$callback['email_type'];
				$args = $callback['args'];
				if ($args['user_lang']) {
					do_action( 'wpml_switch_language', $args['user_lang'] );
				}

				$email_enable = ere_get_option("{$email_type}_enable",'1');
				if ($email_enable == '1') {
					$message = ere_get_option($email_type, '');
					$message =wpautop($message);
					$subject = ere_get_option('subject_' . $email_type, '');

					if (function_exists('icl_translate')) {
						$message = icl_translate('essential-real-estate', 'ere_email_' . $message, $message);
						$subject = icl_translate('essential-real-estate', 'ere_email_subject_' . $subject, $subject);
					}
					$args ['website_url'] = get_option('siteurl');
					$args ['website_name'] = get_option('blogname');
					$args ['user_email'] = $email;
					$user = get_user_by('email', $email);
					$args ['username'] = $user->user_login;

					foreach ($args as $key => $val) {
						$subject = str_replace('%' . $key, $val, $subject);
						$message = str_replace('%' . $key, $val, $message);
					}
					$headers = apply_filters( "ere_contact_mail_header", array('Content-Type: text/html; charset=UTF-8'));
					@wp_mail(
						$email,
						$subject,
						$message,
						$headers
					);
				}

			} catch ( Exception $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					trigger_error( 'Transactional email triggered fatal error', E_USER_WARNING );
				}
			}
		}
		return false;
	}

	public function dispatch_queue() {
		if ( ! empty( $this->data ) ) {
			$this->save()->dispatch();
		}
	}
}
