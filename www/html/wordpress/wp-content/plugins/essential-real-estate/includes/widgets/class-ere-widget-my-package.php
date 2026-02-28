<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Widget_My_Package')) {
	class ERE_Widget_My_Package extends ERE_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'ere_widget ere_widget_my_package';
			$this->widget_description = esc_html__("Display the user's package in the sidebar.", 'essential-real-estate');
			$this->widget_id = 'ere_widget_my_package';
			$this->widget_name = esc_html__('ERE My Package', 'essential-real-estate');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('My Package', 'essential-real-estate'),
					'label' => esc_html__('Title', 'essential-real-estate')
				),
			);

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
			echo ere_get_template_html('widgets/my-package/my-package.php',array('args' => $args, 'instance' => $instance));

			$this->widget_end($args);
		}
	}
}