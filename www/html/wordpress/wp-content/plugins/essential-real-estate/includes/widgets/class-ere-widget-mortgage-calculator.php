<?php
/**
 * Created by G5Theme.
 * User: Kaga
 * Date: 21/12/2016
 * Time: 9:33 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Widget_Mortgage_Calculator')) {
	class ERE_Widget_Mortgage_Calculator extends ERE_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->widget_cssclass = 'ere_widget ere_widget_mortgage_calculator';
			$this->widget_description = esc_html__("Mortgage calculator widget", 'essential-real-estate');
			$this->widget_id = 'ere_widget_mortgage_calculator';
			$this->widget_name = esc_html__('ERE Mortgage Calculator', 'essential-real-estate');
			$this->settings = array(
				'title' => array(
					'type' => 'text',
					'std' => esc_html__('Mortgage Calculator', 'essential-real-estate'),
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

			echo ere_get_template_html('widgets/mortgage-calculator/mortgage-calculator.php');

			$this->widget_end($args);
		}
	}
}