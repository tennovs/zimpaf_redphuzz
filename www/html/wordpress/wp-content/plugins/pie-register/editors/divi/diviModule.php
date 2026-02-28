<?php

class PieRegDiviModule extends ET_Builder_Module {

    /**
     * Module slug.
     *
     * @var string
     */
    public $slug = 'pieregister_selector';

    /**
     * VB support.
     *
     * @var string
     */
    public $vb_support = 'on';

    /**
     * Init module.
     */
    public function init() {

        $this->name = esc_html__( 'Pie Register', 'pie-register' );
    }

    /**
     * Get list of settings.
     *
     * @return array
     */
    public function get_fields() {

        $forms = $this->get_forms();
		$forms    = array_map(
			function ( $form ) {

				return htmlspecialchars_decode( $form, ENT_QUOTES );
			},
			$forms
		);
		$forms[0] = esc_html__( 'Select form', 'pie-register' );

        return [
            'form_id'    => [
                'label'           => esc_html__( 'Form', 'pie-register' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
                'options'         => $forms
            ],
            'show_title' => [
                'label'           => esc_html__( 'Show Title', 'pie-register' ),
                'type'            => 'yes_no_button',
                'option_category' => 'basic_option',
                'toggle_slug'     => 'main_content',
                'options'         => [
                    'off' => esc_html__( 'Off', 'pie-register' ),
                    'on'  => esc_html__( 'On', 'pie-register' ),
                ],
            ],
            'show_desc'  => [
                'label'           => esc_html__( 'Show Description', 'pie-register' ),
                'option_category' => 'basic_option',
                'type'            => 'yes_no_button',
                'toggle_slug'     => 'main_content',
                'options'         => [
                    'off' => esc_html__( 'Off', 'pie-register' ),
                    'on'  => esc_html__( 'On', 'pie-register' ),
                ],
            ],
        ];

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
	 * Disable advanced fields configuration.
	 *
	 * @since 1.6.3
	 *
	 * @return array
	 */
	public function get_advanced_fields_config() {

		return [
			'link_options' => false,
			'text'         => false,
			'background'   => false,
			'borders'      => false,
			'box_shadow'   => false,
			'button'       => false,
			'filters'      => false,
			'fonts'        => false,
		];
	}

    /**
     * Render module on the frontend.
     *
     * @param array  $attrs       List of unprocessed attributes.
     * @param string $content     Content being processed.
     * @param string $render_slug Slug of module that is used for rendering output.
     *
     * @return string
     */

    public function render( $attrs, $render_slug, $content = null) {
        if ( empty( $this->props['form_id'] ) ) {
            return '';
        }
        if( $this->props['form_id']  == 'login'){
            return do_shortcode( 
                sprintf(
                '[pie_register_login]'
                )
            );
		}else if($this->props['form_id']  == 'forgot_pass'){
			return do_shortcode( 
                sprintf(
				'[pie_register_forgot_password]'
                )
            );
		}else{
            return do_shortcode(
                sprintf(
                    '[pie_register_form id="%1$s" title="%2$s" description="%3$s"]',
                    absint( $this->props['form_id'] ),
                    (bool) apply_filters( 'pieregister_divi_builder_form_title', ! empty( $this->props['show_title'] ) && 'on' === $this->props['show_title'], $this->props['form_id'] ),
                    (bool) apply_filters( 'pieregister_divi_builder_form_desc', ! empty( $this->props['show_desc'] ) && 'on' === $this->props['show_desc'], $this->props['form_id'] )
                )
            );
		}
    }
}
    