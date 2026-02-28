<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Section extends WPFM_Inputs{
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings;
	
	
	/*
	 * check if section is started
	 */
	var $is_section_stared;
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;
	
	function __construct(){
		
		// $this -> plugin_meta = get_plugin_meta_filemanager();
		
		$this -> title 		= __ ( 'Section', 'nm-filemanager' );
		$this -> desc		= __ ( 'Form section', 'nm-filemanager' );
		$this -> settings	= self::get_settings();
		
	}
	
	
	
	
	private function get_settings(){
		
		return array (
						'title' => array (
								'type' => 'text',
								'title' => __ ( 'Title', 'nm-filemanager' ),
								'desc' => __ ( 'It will as section heading wrapped in h2', 'nm-filemanager' ) 
						),
						'description' => array (
								'type' => 'text',
								'title' => __ ( 'Description', 'nm-filemanager' ),
								'desc' => __ ( 'Type description, it will be diplay under section heading.', 'nm-filemanager' ) 
						)
				);
	
	}
	
	
	/*
	 * @params: args
	*/
	function render_input($args, $content=""){
		
		
		$_html =  '<section id="'.$args['id'].'">';
		$_html .= '<div style="clear: both"></div>';
		
		$_html .= '<header class="filemanager-section-header">';
		$_html .= '<h2>'. stripslashes( $args['title'] ).'</h2>';
		$_html .= '<p id="box-'.$name.'">'. stripslashes( $args['description']).'</p>';
		$_html .= '</header>';
		
		$_html .= '<div style="clear: both"></div>';
		
		echo $_html;
	}
}