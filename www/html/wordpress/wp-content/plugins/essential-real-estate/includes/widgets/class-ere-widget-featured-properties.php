<?php

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('ERE_Widget_Featured_Properties')) {

	class ERE_Widget_Featured_Properties extends ERE_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'ere_widget ere_widget_featured_properties ere-property';
			$this->widget_description = esc_html__("Display the Recent Properties.", 'essential-real-estate');
			$this->widget_id = 'ere_widget_featured_properties';
			$this->widget_name = esc_html__('ERE Featured Properties', 'essential-real-estate');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('Featured Properties', 'essential-real-estate'),
					'label' => esc_html__('Title', 'essential-real-estate')
				),
				'number' => array(
					'type' => 'number',
					'std' => '3',
					'label' => esc_html__('Number of Properties', 'essential-real-estate')
				),
				'link' => array(
					'type' => 'text',
					'label' => esc_html__('View More Link', 'essential-real-estate')
				),
				'filter_by_agent'=>array(
					'type' => 'checkbox',
					'std' => '0',
					'label' => esc_html__('Filter by agent if current page is Single Agent page?', 'essential-real-estate')
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

			echo ere_get_template_html('widgets/featured-properties/featured-properties.php', array('args' => $args, 'instance' => $instance));

			$this->widget_end($args);
		}
	}
}