<?php

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Widget for Elementor page builder
 */
class PieRegElementorWidget extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * Retrieve shortcode widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {

		return 'PieRegister';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve shortcode widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {

		return __( 'Pie Register', 'pie-register' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve shortcode widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {

		return 'icon-pieregister-forms';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {

		return [
			'form',
			'forms',
			'pieregister forms',
			'login form',
			'forgot password form',
			'the dude',
		];
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {

		return [
			'basic',
		];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {

		$this->content_controls();
	}

	/**
	 * Register content tab controls.
	 */
	protected function content_controls() {

		$this->start_controls_section(
			'section_form',
			[
				'label' => esc_html__( 'Form', 'pie-register' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$forms = $this->get_forms();

		$this->add_control(
			'form_id',
			[
				'label'       => esc_html__( 'Form', 'pie-register' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $forms,
				'default'     => '0',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_display',
			[
				'label'     => esc_html__( 'Display Options', 'pie-register' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'form_id!' => '0',
				],
			]
		);

		$this->add_control(
			'display_form_name',
			[
				'label'        => esc_html__( 'Form Name', 'pie-register' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'pie-register' ),
				'label_off'    => esc_html__( 'Hide', 'pie-register' ),
				'return_value' => 'yes',
				'condition'    => [
					'form_id!' => '0',
				],
			]
		);

		$this->add_control(
			'display_form_description',
			[
				'label'        => esc_html__( 'Form Description', 'pie-register' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'pie-register' ),
				'label_off'    => esc_html__( 'Hide', 'pie-register' ),
				'separator'    => 'after',
				'return_value' => 'yes',
				'condition'    => [
					'form_id!' => '0',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	/**
	 * Render widget output in edit mode.
	 */
	protected function render_edit_mode() {

		$form_id = $this->get_settings_for_display( 'form_id' );

		if ( empty( $form_id ) ) {

			// Render form selector.
			$forms = $this->get_form_selector_options();

			echo('<div class="pieregister-elementor pieregister-elementor-form-selector">');
				echo('<img src="'.esc_url(PIEREG_PLUGIN_URL . 'assets/images/editors/elementor/pieregister-form-selector.svg').'" alt="Pie Register Logo"/>');
			echo('</div>');

			return;
		}

		// Finally, render selected form.
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
		$this->render_frontend();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render_frontend() {

		// Render selected form.
		$classPieRegister = new PieRegister();
		echo wp_kses( do_shortcode( $this->render_shortcode() ) , $classPieRegister->piereg_forms_get_allowed_tags() );
	}

	/**
	 * Render widget as plain content.
	 */
	public function render_plain_content() {
		$classPieRegister = new PieRegister();
		echo wp_kses($this->render_shortcode(), $classPieRegister->piereg_forms_get_allowed_tags());
	}
	
	/**
	 * Render shortcode.
	 */
	public function render_shortcode() {

		$form_id = $this->get_settings_for_display( 'form_id' );

		if( $form_id == 'login'){
			return sprintf(
				'[pie_register_login]'
			);	
		}else if($form_id == 'forgot_pass'){
			return sprintf(
				'[pie_register_forgot_password]'
			);
		}else{
			return sprintf(
				'[pie_register_form id="%1$d" title="%2$s" description="%3$s"]',
				absint( $this->get_settings_for_display( 'form_id' ) ),
				sanitize_key( $this->get_settings_for_display( 'display_form_name' ) === 'yes' ? 'true' : 'false' ),
				sanitize_key( $this->get_settings_for_display( 'display_form_description' ) === 'yes' ? 'true' : 'false' )
			);
		}
	}

	/**
	 * Get forms list.
	 *
	 * @returns array Array of forms.
	 */
	public function get_forms() {

		static $forms_list = [];
		$base = new PieReg_Base();
		
		if ( empty( $forms_list ) ) {

            $fields_id    = get_option("piereg_form_fields_id");
            $form_on_free = get_option("piereg_form_free_id");
			
			$forms_list[0]			   = esc_html__( 'Select a form', 'pie-register' );
            $forms_list['login'] 	   = esc_html__( 'Login Form', 'pie-register' );
            $forms_list['forgot_pass'] = esc_html__( 'Forgot Password Form', 'pie-register' );
            
            for($a=1;$a<=$fields_id;$a++)
            {
                $option = get_option("piereg_form_field_option_".$a);
                if( !empty($option) && is_array($option) && isset($option['Id']) && (!isset($option['IsDeleted']) || trim($option['IsDeleted']) != 1) )
                {
					$forms_list[ $option['Id'] ] = mb_strlen( $option['Title'] ) > 100 ? mb_substr( $option['Title'], 0, 97 ) . '...' : $option['Title'];
					
					if(!$base->piereg_pro_is_activate){
						break;
					}
                }
            }
		}

		return $forms_list;
	}

	/**
	 * Get form selector options.
	 *
	 * @returns string Rendered options for the select tag.
	 */
	public function get_form_selector_options() {

		$forms   = $this->get_forms();
		$options = '';

		foreach ( $forms as $form_id => $form ) {
			$options .= sprintf(
				'<option value="%d">%s</option>',
				(int) $form_id,
				esc_html( $form )
			);
		}

		return $options;
	}
}