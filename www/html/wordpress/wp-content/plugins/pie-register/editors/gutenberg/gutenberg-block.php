<?php
defined( 'ABSPATH' ) || exit;

/**
 * Guten Block Class.
 */

class PieRegGutenbergBlock {

	public function __construct() {
		
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_sheets' ) );
	}
	
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		
		register_block_type(
			'pie-register/form-selector',
			array(
				'attributes'      => array(
					'formId'             => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
					'displayTitle'       => array(
						'type' => 'boolean',
					),
					'displayDescription' => array(
						'type' => 'boolean',
					),
				),
				'editor_style'    => 'pie-register-block-editor',
				'editor_script'   => 'pie-register-block-editor',
				'render_callback' => array( $this, 'get_form_html' ),
			)
		);
	}

	/**
	 * Load Pie Register Gutenberg block scripts.
	**/
	public function enqueue_block_editor_sheets() {
		$i18n = array(
			'title'             => esc_html__( 'Pie Register', 'pie-register' ),
			'description'       => esc_html__( 'Select and display one of your forms.', 'pie-register' ),
			'form_keywords'     => array(
				esc_html__( 'form', 'pie-register' ),
				esc_html__( 'register', 'pie-register' ),
				esc_html__( 'login', 'pie-register' ),
			),
			'form_select'       => esc_html__( 'Select a Form', 'pie-register' ),
			'form_settings'     => esc_html__( 'Form Settings', 'pie-register' ),
			'form_selected'     => esc_html__( 'Form', 'pie-register' ),
			'show_title'        => esc_html__( 'Show Title', 'pie-register' ),
			'show_description'  => esc_html__( 'Show Description', 'pie-register' ),
		);

		wp_enqueue_script(
			'pie-register-block-editor',
			PIEREG_PLUGIN_URL . 'assets/js/gutenberg-block.js',
			array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components' ),
			PIEREGISTER_VERSION
		);

		wp_enqueue_style(
			'pie-register-block-editor',
			PIEREG_PLUGIN_URL . 'assets/css/front.css',
			array( 'wp-edit-blocks' ),
			PIEREGISTER_VERSION
		);

		$forms_obj = array();

        if ( empty( $forms_obj ) ) {

            $fields_id    = get_option("piereg_form_fields_id");
			$PieReg_Base  = new PieReg_Base();
			
			for($a=1;$a<=$fields_id;$a++)
            {
				$option = get_option("piereg_form_field_option_".$a);

				if( !empty($option) && is_array($option) && isset($option['Id']) && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) )
                {
					$forms_obj[] = (object) $option;

					if(!$PieReg_Base->piereg_pro_is_activate())
						break;
                }	
            }
		}

		$forms = $forms_obj; 

		wp_localize_script(
			'pie-register-block-editor',
			'pie_register_block_editor',
			array(
				'forms'             => $forms,
				'i18n'              => $i18n,
			)
		);
	}

	/**
	 * Get form HTML to display in a Gutenberg block.
	 */
	public function get_form_html( $attr ) {
		$piereg = new PieRegister();

		$title        = ! empty( $attr['displayTitle'] ) ? true : false;
		$description  = ! empty( $attr['displayDescription'] ) ? true : false;

		// Disable form fields if called from the Gutenberg editor.
		if ( $this->is_gb_editor() ) {
			// Registration Form
			add_filter(
				'pie_register_frontend_container_class',
				function ( $classes ) {
					$classes[] = 'piereg-gutenberg-form-selector';
					return $classes;
				}
			);
			// Login Form
			add_filter(
				'pie_register_frontend_login_container_class',
				function ( $classes ) {
					$classes[] = 'piereg-gutenberg-login-form-selector';
					return $classes;
				}
			);
			// Forgot Password Form
			add_filter(
				'pie_register_forgot_pass_container_class',
				function ( $classes ) {
					$classes[] = 'piereg-gutenberg-login-form-selector';
					return $classes;
				}
			);
			add_filter(
				'pie_register_frontend_output_before',
				function ( $registration_from_fields ) {
					$registration_from_fields .= '<fieldset disabled>';
					return $registration_from_fields;
				}
			);
			add_filter(
				'pie_register_frontend_output_after',
				function ( $registration_from_fields ) {
					$registration_from_fields .= '</fieldset>';
					return $registration_from_fields;
				}
			);
			add_filter(
				'pie_register_frontend_login_output_before',
				function ( $login_form_fields ) {
					$login_form_fields .= '<fieldset disabled>';
					return $login_form_fields;
				}
			);
			add_filter(
				'pie_register_frontend_login_output_after',
				function ( $login_form_fields ) {
					$login_form_fields .= '</fieldset>';
					return $login_form_fields;
				}
			);
			add_filter(
				'pie_register_forgot_pass_output_before',
				function ( $forgot_pass_fields ) {
					$forgot_pass_fields .= '<fieldset disabled>';
					return $forgot_pass_fields;
				}
			);
			add_filter(
				'pie_register_forgot_pass_output_after',
				function ( $forgot_pass_fields ) {
					$forgot_pass_fields .= '</fieldset>';
					return $forgot_pass_fields;
				}
			);
		}

		if ( isset($attr['formId']) && !empty( $attr['formId'] ) && $attr['formId'] == 'login_form' ){
			// Wrapper classes
			// Adds additional classes from the Advanced > Additional CSS class(es)
			if ( ! empty( $attr['className'] ) ) {
				add_filter(
					'pie_register_frontend_login_container_class',
					function ( $classes ) use ( $attr ) {
						$cls = array_map( 'esc_attr', explode( ' ', $attr['className'] ) );
						return array_unique( array_merge( $classes, $cls ) );
					}
				);
			}
			$form_returned = $piereg->showLoginForm();
			return $form_returned;
		}elseif(isset($attr['formId']) && !empty( $attr['formId'] ) && $attr['formId'] == 'forgot_password'){
			// Wrapper classes
			// Adds additional classes from the Advanced > Additional CSS class(es)
			if ( ! empty( $attr['className'] ) ) {
				add_filter(
					'pie_register_forgot_pass_container_class',
					function ( $classes ) use ( $attr ) {
						$cls = array_map( 'esc_attr', explode( ' ', $attr['className'] ) );
						return array_unique( array_merge( $classes, $cls ) );
					}
				);
			}
			$form_returned = $piereg->showForgotPasswordForm();
			return $form_returned;
		}
		
		$form_id = ! empty( $attr['formId'] ) ? absint( $attr['formId'] ) : 0;

		if ( empty( $form_id ) ) {
			return '';
		}

		// Wrapper classes
		// Adds additional classes from the Advanced > Additional CSS class(es)

		if ( ! empty( $attr['className'] ) ) {
			add_filter(
				'pie_register_frontend_container_class',
				function ( $classes ) use ( $attr ) {
					$cls = array_map( 'esc_attr', explode( ' ', $attr['className'] ) );
					return array_unique( array_merge( $classes, $cls ) );
				}
			);
		}

		$form_attr = array(
							'id' 			=> $form_id,
							'title' 		=> $title,
							'description' 	=> $description
						);

		$form_returned = $piereg->piereg_registration_form($form_attr);
		
		return $form_returned;
	}

	/**
	 * Checking if is Gutenberg REST API call.
	 * @return bool True if is Gutenberg REST API call.
	 */
	public function is_gb_editor() {

		// TODO: Find a better way to check if is GB editor API call.
		return defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
	}

}

add_action( 'pieregister_gutenberg', 'initialize_gutenberg_block');
function initialize_gutenberg_block(){
	$PieRegGutenbergBlock = new PieRegGutenbergBlock();
}