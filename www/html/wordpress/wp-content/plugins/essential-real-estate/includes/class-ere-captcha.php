<?php

/**
 * Class ERE_Captcha
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('ERE_Captcha')) {
	class ERE_Captcha
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == null) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function render_recaptcha() {
			$enable_captcha = ere_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				wp_enqueue_script('ere-google-recaptcha');
				$captcha_site_key = ere_get_option('captcha_site_key', '');
				?>
				<script type="text/javascript">
					var ere_widget_ids = [];
					var ere_captcha_site_key = '<?php echo esc_js($captcha_site_key); ?>';
					/**
					 * reCAPTCHA render
					 */
					var ere_recaptcha_onload_callback = function() {
						jQuery('.ere-google-recaptcha').each( function( index, el ) {
							var widget_id = grecaptcha.render( el, {
								'sitekey' : ere_captcha_site_key
							} );
							ere_widget_ids.push( widget_id );
						} );
					};
					/**
					 * reCAPTCHA reset
					 */
					var ere_reset_recaptcha = function() {
						if( typeof ere_widget_ids != 'undefined' ) {
							var arrayLength = ere_widget_ids.length;
							for( var i = 0; i < arrayLength; i++ ) {
								grecaptcha.reset( ere_widget_ids[i] );
							}
						}
					};
				</script>
				<?php
			}
		}

		public function render_recaptcha_wp_login() {
			$enable_captcha = ere_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				$captcha_site_key = ere_get_option('captcha_site_key', '');
				$recaptcha_src = esc_url_raw(add_query_arg(array(
					'render' => 'explicit',
					'onload' => 'ere_recaptcha_onload_callback'
				), 'https://www.google.com/recaptcha/api.js'));
				?>
				<script type="text/javascript">
					var ere_widget_ids = [];
					var ere_captcha_site_key = '<?php echo esc_js($captcha_site_key); ?>';
					/**
					 * reCAPTCHA render
					 */
					var ere_recaptcha_onload_callback = function() {

						for ( var i = 0; i < document.forms.length; i++ ) {
							var form = document.forms[i];
							var captcha_div = form.querySelector( '.ere-google-recaptcha' );

							var widget_id = grecaptcha.render( captcha_div, {
								'sitekey' : ere_captcha_site_key
							} );
							ere_widget_ids.push( widget_id );
						}
					};
					/**
					 * reCAPTCHA reset
					 */
					var ere_reset_recaptcha = function() {
						if( typeof ere_widget_ids != 'undefined' ) {
							var arrayLength = ere_widget_ids.length;
							for( var i = 0; i < arrayLength; i++ ) {
								grecaptcha.reset( ere_widget_ids[i] );
							}
						}
					};
				</script>
				<script src="<?php echo esc_url( $recaptcha_src ); ?>"
				        async defer>
				</script>
				<?php
			}
		}

		public function verify_recaptcha() {
			if (!$this->verify()) {
				echo json_encode( array(
					'success' => false,
					'message' => esc_attr__( 'Captcha Invalid', 'essential-real-estate' )
				) );
				wp_die();
			}
		}

		public function verify() {
			if (isset($_POST['g-recaptcha-response'])) {
				$captcha_secret_key = ere_get_option('captcha_secret_key', '');
				$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $captcha_secret_key ."&response=". ere_clean(wp_unslash($_POST['g-recaptcha-response'])));
				$response = json_decode($response["body"], true);
				return true == $response["success"];
			}
			return true;
		}

		public function form_recaptcha() {
			$enable_captcha = ere_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				?>
				<div class="ere-recaptcha-wrap clearfix">
					<div class="ere-google-recaptcha"></div>
				</div>
				<?php
			}
		}

		public function verify_recaptcha_wp_login($user, $username = '', $password = '') {
			if ( ! $username ) {
				return $user;
			}

			if (!$this->verify()) {
				return new WP_Error( 'captcha_error',__( '<strong>Error</strong>: Captcha Invalid', 'essential-real-estate' ) );
			}
			return $user;
		}

		public function verify_recaptcha_wp_lostpassword($errors) {
			if (!$this->verify()) {
				$errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid', 'essential-real-estate' ) );
			}
			return $errors;
		}

		function verify_recaptcha_wp_registration( $errors, $sanitized_user_login, $user_email ) {
			if ( ! $this->verify() ) {
				$errors->add( 'captcha_error', __( '<strong>Error</strong>: Captcha Invalid', 'essential-real-estate' ) );
			}

			return $errors;
		}
	}
}