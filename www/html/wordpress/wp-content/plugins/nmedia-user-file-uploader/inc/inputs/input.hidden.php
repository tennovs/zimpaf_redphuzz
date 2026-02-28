<?php
/*
 * Followig class handling hidden input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Hidden extends WPFM_Inputs{
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings;
	
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;
		
	function __construct(){
		
		// $this -> plugin_meta = get_plugin_meta_filemanager();
		
		$this -> title 		= __ ( 'Hidden Input', 'nm-filemanager' );
		$this -> desc		= __ ( 'regular hidden input', 'nm-filemanager' );
		$this -> settings	= self::get_settings();
		
	}
	
	
	
	
	private function get_settings(){
		
		return array (

					'data_name' => array (
							'type' => 'text',
							'title' => __ ( 'Data name', 'nm-filemanager' ),
							'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'nm-filemanager' )
					),
					'field_value' => array (
							'type' => 'text',
							'title' => __ ( 'Field value', 'nm-filemanager' ),
							'desc' => __ ( 'you can pre-set the value of this hidden input.', 'nm-filemanager' )
					),
				);
	}
	
	
	/*
	 * @params: args
	*/
	function render_input($args, $content=""){
		
		$_html = '<input type="hidden" ';
		
		foreach ($args as $attr => $value){
			
			$_html .= $attr.'="'.stripslashes( $value ).'"';
		}
		
		if($content)
			$_html .= 'value="' . stripslashes($content) . '"';
		
		$_html .= ' />';
		
		echo $_html;
	}
}