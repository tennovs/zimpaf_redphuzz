<?php

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('ERE_Widget_Top_Agents')) {

	class ERE_Widget_Top_Agents extends ERE_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'ere_widget ere_widget_top_agents';
			$this->widget_description = esc_html__("Display the top agents.", 'essential-real-estate');
			$this->widget_id = 'ere_widget_top_agents';
			$this->widget_name = esc_html__('ERE Top Agents', 'essential-real-estate');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('Top Agents', 'essential-real-estate'),
					'label' => esc_html__('Title', 'essential-real-estate')
				),
				'number' => array(
					'type' => 'number',
					'std' => '3',
					'label' => esc_html__('Number of top agents', 'essential-real-estate')
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

			echo ere_get_template_html('widgets/top-agents/top-agents.php', array('args' => $args, 'instance' => $instance));

			$this->widget_end($args);
		}
	}
}