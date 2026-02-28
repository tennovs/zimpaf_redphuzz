<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Widget_Login_Menu')) {
	class ERE_Widget_Login_Menu extends ERE_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'ere_widget ere_widget_login_menu';
			$this->widget_description = esc_html__("Show Login and Logout menu.", 'essential-real-estate');
			$this->widget_id = 'ere_widget_login_menu';
			$this->widget_name = esc_html__('ERE Login Menu', 'essential-real-estate');
			parent::__construct();
		}
		/**
		 * Output widget
		 * @param array $args
		 * @param array $instance
		 */
		public function widget($args, $instance)
		{
			$this->widget_start($args, $instance);
			echo ere_get_template_html('widgets/login-menu/login-menu.php',array('args' => $args, 'instance' => $instance));
			$this->widget_end($args);
		}
	}
}