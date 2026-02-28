<?php
/**
 * Class Divi.
 */

class PieRegDivi {

	public function __construct()
	{
		$this->load();
		$this->allow_load();
     
	}
	/**
	 * Load integration
	 *
	 * @return bool
	 */
	public function allow_load() {

		if ( function_exists( 'et_divi_builder_init_plugin' ) ) {
			return true;
		}

		$allow_themes = [ 'Divi', 'Extra' ];
		$theme        = wp_get_theme();
		$theme_name   = $theme->get_template();
		$theme_parent = $theme->parent();

		return (bool) array_intersect( [ $theme_name, $theme_parent ], $allow_themes );
	}

	/**
	 * Load integration
	 */
	public function load() {
		$this->hooks();
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
        
		add_action( 'et_builder_ready', [ $this, 'register_module' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_styles' ], 5 );

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_pieregister_divi_preview', [ $this, 'preview' ] );
		}

		if ( $this->is_divi_builder() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'builder_styles' ], 12 );
			add_action( 'wp_enqueue_scripts', [ $this, 'builder_scripts' ] );
        
		}

	}

	/**
	 * Check is div
	 *
	 * @return bool
	 */
	private function is_divi_builder() {
		return ! empty( $_GET['et_fb'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}


	/**
	 * Get current style name.
	 * Overwrite st
	 *
	 * @return string
	 */
	public function get_current_styles_name() {

		$disable_css ='disable-css';
		if ( 1 === $disable_css ) {
			return 'full';
		}
		if ( 2 === $disable_css ) {
			return 'base';
		}

		return '';
	}

	/**
	 * Is the Divi 
	 *
	 * @return bool
	 */
	protected function is_divi_plugin_loaded() {

		if ( ! is_singular() ) {
			return false;
		}

		return function_exists( 'et_is_builder_plugin_active' );
	}

	/**
	 * Register frontend_styles
	 */
	public function frontend_styles() {

		if ( ! $this->is_divi_plugin_loaded() ) {
			return;
		}
	
	}

	/**
	 * Load styles 
	 */
	public function builder_styles() {

		wp_enqueue_style(
			'PieRegisterFrontCSS',
			PIEREG_PLUGIN_URL . "assets/css/front.css",
			null,
			PIEREGISTER_VERSION
		);

		wp_enqueue_style(
			'PieRegisterdiviCSS',
			PIEREG_PLUGIN_URL . "assets/css/divi-integration.css",
			null,
			PIEREGISTER_VERSION
		);
	}

	/**
	 * Load scripts
	 */
	public function builder_scripts() {

		wp_enqueue_script(
			'PieregisterdiviJS',
			PIEREG_PLUGIN_URL . "assets/js/divi-integration.js",
			[ 'react', 'react-dom' ],
			PIEREGISTER_VERSION,
			true
		);
		wp_localize_script(
			'PieregisterdiviJS',
			'pieregister_divi_builder',
			[
				'ajax_url'          => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'pieregister_divi_builder' ),
				'placeholder'       => PIEREG_PLUGIN_URL . "assets/images/editors/divi/pieregister-logo.svg",
				'placeholder_title' => esc_html__( 'Pie Register', 'pie-register' ),
			]
		);
	}

	/**
	 * Register mod
	 */

    public function register_module() {
        if ( ! class_exists( 'ET_Builder_Module' ) ) {
			return;
		}

        if( file_exists(plugin_dir_path(__FILE__)."diviModule.php") )
            require_once(plugin_dir_path(__FILE__)."diviModule.php");
            new PieRegDiviModule();

	}
    
	/**
	 * Ajax handler
	 */
	public function preview() {

		check_ajax_referer( 'pieregister_divi_builder', 'nonce' );

		$form_id    = filter_input( INPUT_POST, 'form_id', FILTER_SANITIZE_STRING ) ;
		$show_title = 'on' === filter_input( INPUT_POST, 'show_title', FILTER_SANITIZE_STRING );
		$show_desc  = 'on' === filter_input( INPUT_POST, 'show_desc', FILTER_SANITIZE_STRING );

		add_action(
			'pieregister_frontend_output',
			function () {
				echo '<fieldset disabled>';
			},
			3
		);
		add_action(
			'pieregister_frontend_output',
			function () {

				echo '</fieldset>';
			},
			30
		);

		if($form_id  == 'undefined' || $form_id  == '0'  ){
			return;

		}else if($form_id  == 'login'){
            wp_send_json_success(
				do_shortcode( 
					sprintf(
						'[pie_register_login]'
					)
				)
			);
		}else if($form_id  == 'forgot_pass'){
            wp_send_json_success(
				do_shortcode( 
					sprintf(
						'[pie_register_forgot_password]'
					)
				)
			);
		}else{
            wp_send_json_success(
                do_shortcode(
                    sprintf(
                        '[pie_register_form id="%1$s" title="%2$s" description="%3$s"]',
						$form_id, 
						sanitize_key( $show_title == '1' ? 'true' : 'false' ),
						sanitize_key( $show_desc == '1' ? 'true' : 'false' )
                    )
                )
            );
		}
	}
}

add_action( 'pieregister_divi', 'initialize_diviwidget');
function initialize_diviwidget(){
	$divi_widget = new PieRegDivi();
}
