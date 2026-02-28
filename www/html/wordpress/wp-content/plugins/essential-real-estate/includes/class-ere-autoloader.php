<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ERE_Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( ERE_PLUGIN_FILE ) );
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path
	 * @return bool successful or not
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}
		return false;
	}

	/**
	 * Auto-load WC classes on demand to reduce memory consumption.
	 *
	 * @param string $class
	 */
	public function autoload( $class ) {
		$class = strtolower( $class );

		if ( 0 !== strpos( $class, 'ere_' ) ) {
			return;
		}

		$file  = $this->get_file_name_from_class( $class );
		$path  = '';

		if ( 0 === strpos( $class, 'ere_shortcode_' ) ) {
			$path = $this->include_path . '/includes/shortcodes/';
		}
		elseif ( 0 === strpos( $class, 'ere_widget' ) ) {
			$path = $this->include_path . '/includes/widgets/';
		}
		elseif ( 0 === strpos( $class, 'ere_admin' ) ) {
			$path = $this->include_path . '/admin/';
		}
		elseif (( 0 === strpos( $class, 'ere_login_register' ) )||( 0 === strpos( $class, 'ere_profile' ) )) {
			$path = $this->include_path . '/public/partials/account/';
		}
		elseif ( 0 === strpos( $class, 'ere_agent' ) ) {
			$path = $this->include_path . '/public/partials/agent/';
		}
		elseif ( 0 === strpos( $class, 'ere_invoice' ) ) {
			$path = $this->include_path . '/public/partials/invoice/';
		}
		elseif ( 0 === strpos( $class, 'ere_package' ) ) {
			$path = $this->include_path . '/public/partials/package/';
		}
		elseif (( 0 === strpos( $class, 'ere_payment' ) )||( 0 === strpos( $class, 'ere_trans_log' ) )) {
			$path = $this->include_path . '/public/partials/payment/';
		}
		elseif (( 0 === strpos( $class, 'ere_property' ) )||( 0 === strpos( $class, 'ere_compare' ) )||( 0 === strpos( $class, 'ere_save_search' ) )||( 0 === strpos( $class, 'ere_search' ) )) {
			$path = $this->include_path . '/public/partials/property/';
		}
		if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}
}

new ERE_Autoloader();
