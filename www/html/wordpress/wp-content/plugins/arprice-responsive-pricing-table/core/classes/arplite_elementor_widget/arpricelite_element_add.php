<?php
namespace ElementorARPRICELITEELEMENT\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; 


class arpricelite_element_shortcode extends Widget_Base {

	public function get_name() {
		return 'arpricelite-element-shortcode';
	}

	
	public function get_title() {
		return '<p>'.esc_html__( 'ARPrice Lite', 'arprice-responsive-pricing-table').'</p>';
	}

	
	public function get_icon() {
		return 'arpricelite_element_icon';
	}

	
	public function get_categories() {
		return [ 'general' ];
	}

	
	public function get_script_depends() {
		return [ 'elementor-arpricelite-element-shortcode' ];
	}

	protected function _register_controls() {
		global $arprice_class, $wpdb;
		$tables = $wpdb->get_results($wpdb->prepare("SELECT ID, table_name FROM " . $wpdb->prefix . "arplite_arprice WHERE status = '%s' and is_template != '%d'", array('published', '1')));
        $price_tabel=array();
        $price_tabel['Please select table']= esc_html__('Please select table','arprice-responsive-pricing-table');
        if($tables){
	        foreach ($tables as $table) {
	        	$price_tabel['[ARPLite id='.$table->ID.']']=$table->table_name . ' (ID:'.$table->ID.')';
	        }
	    }
        
		
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'ARPrice Lite Shortcode', 'arprice-responsive-pricing-table'),
			)
		);

		$this->add_control(
			'title',
			array(
				'label' => esc_html__( 'Title:', 'arprice-responsive-pricing-table'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$this->add_control(
			'arp_select',
			array(
				'label' => esc_html__( 'Table:', 'arprice-responsive-pricing-table'),
				'type' => Controls_Manager::SELECT,
				'default' => esc_html__('Please select table','arprice-responsive-pricing-table'),
				'options' => $price_tabel,
				'label_block' => true,
				
			)
		);

		$this->end_controls_section();

	}

	
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo '<h2 class="title">';
		echo $settings['title'];
		echo '</h2>';
		echo '<div class="arp_select">';
		echo do_shortcode($settings['arp_select']);
		echo '</div>';
		
	}

	
	protected function _content_template() {
		?>
		<h2 class="title">
			{{{ settings.title }}}
		</h2>
		<div class="arp_select">
			{{{ settings.arp_select }}}

		</div>
		<?php
	}
}