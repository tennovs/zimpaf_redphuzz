<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Abstract ERE_Form class.
 * Class ERE_Form
 */
abstract class ERE_Form {
	/**
	 * Form fields
	 */
	protected $fields = array();

	/**
	 * Form action
	 */
	protected $action = '';

	/**
	 * Form errors
	 */
	protected $errors = array();

	/**
	 * Form steps
	 */
	protected $steps = array();

	/**
	 * Form step
	 */
	protected $step = 0;

	/**
	 * Form fields
	 */
	public $form_name = '';

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__ );
	}
	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__ );
	}
	/**
	 * Process function. all processing code if needed - can also change view if step is complete
	 */
	public function process() {
		$step_key = $this->get_step_key( $this->step );

		if ( $step_key && is_callable( $this->steps[ $step_key ]['handler'] ) ) {
			call_user_func( $this->steps[ $step_key ]['handler'] );
		}

		$next_step_key = $this->get_step_key( $this->step );

		// if the step changed, but the next step has no 'view', call the next handler in sequence.
		if ( $next_step_key && $step_key !== $next_step_key && ! is_callable( $this->steps[ $next_step_key ]['view'] ) ) {
			$this->process();
		}
	}

	/**
	 * Call the view handler if set, otherwise call the next handler.
	 */
	public function output( $atts = array() ) {
		ob_start();
		$step_key = $this->get_step_key( $this->step );
		$this->show_errors();

		if ( $step_key && is_callable( $this->steps[ $step_key ]['view'] ) ) {
			call_user_func( $this->steps[ $step_key ]['view'], $atts );
		}
		return ob_get_clean();
	}

	/**
	 * Add an error
	 * @param $error
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Show errors
	 */
	public function show_errors() {
		foreach ( $this->errors as $error ) {
			echo '<div class="ere-manager-error">' . $error . '</div>';
		}
	}
	/**
	 * Get action (URL for forms to post to).
	 * @return string
	 */
	public function get_action() {
		return esc_url_raw( $this->action ? $this->action : wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}
	/**
	 * Get formn name
	 * @return string
	 */
	public function get_form_name() {
		return $this->form_name;
	}
	/**
	 * Get steps from outside of the class
	 * @return array
	 */
	public function get_steps() {
		return $this->steps;
	}

	/**
	 * Get step from outside of the class
	 */
	public function get_step() {
		return $this->step;
	}

	/**
	 * Get step key from outside of the class
	 * @param string $step
	 * @return string
	 */
	public function get_step_key( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}
		$keys = array_keys( $this->steps );
		return isset( $keys[ $step ] ) ? $keys[ $step ] : '';
	}
	/**
	 * Get step from outside of the class
	 * @param $step
	 */
	public function set_step( $step ) {
		$this->step = absint( $step );
	}
	/**
	 * Increase step from outside of the class
	 */
	public function next_step() {
		$this->step ++;
	}

	/**
	 * Decrease step from outside of the class
	 */
	public function previous_step() {
		$this->step --;
	}
	/**
	 * get_fields function
	 * @param $key
	 * @return array
	 */
	public function get_fields( $key ) {
		if ( empty( $this->fields[ $key ] ) ) {
			return array();
		}

		$fields = $this->fields[ $key ];

		uasort( $fields, array( $this, 'sort_by_priority' ) );

		return $fields;
	}
	/**
	 * Sort array by priority value
	 * @param $a
	 * @param $b
	 * @return int
	 */
	protected function sort_by_priority( $a, $b ) {
		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}
		return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
	}
}
