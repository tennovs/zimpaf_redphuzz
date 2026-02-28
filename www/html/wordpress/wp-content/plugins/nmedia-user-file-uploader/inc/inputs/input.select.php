<?php
/*
 * Followig class handling select input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Select extends WPFM_Inputs{
	
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
		
		$this -> title 		= __ ( 'Select-box Input', 'nm-filemanager' );
		$this -> desc		= __ ( 'regular select-box input', 'nm-filemanager' );
		$this -> settings	= self::get_settings();
		
	}
	
	
	
	
	private function get_settings(){
		
		return array (
		'title' => array (
				'type' => 'text',
				'title' => __ ( 'Title', 'nm-filemanager' ),
				'desc' => __ ( 'It will be shown as field label', 'nm-filemanager' ) 
		),
		'data_name' => array (
				'type' => 'text',
				'title' => __ ( 'Data name', 'nm-filemanager' ),
				'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'nm-filemanager' ) 
		),
		'description' => array (
				'type' => 'text',
				'title' => __ ( 'Description', 'nm-filemanager' ),
				'desc' => __ ( 'Small description, it will be diplay near name title.', 'nm-filemanager' ) 
		),
		'error_message' => array (
				'type' => 'text',
				'title' => __ ( 'Error message', 'nm-filemanager' ),
				'desc' => __ ( 'Insert the error message for validation.', 'nm-filemanager' ) 
		),
		
		'options' => array (
				'type' => 'textarea',
				'title' => __ ( 'Add options', 'nm-filemanager' ),
				'desc' => __ ( 'Type each option per line', 'nm-filemanager' ) 
		),
		'selected' => array (
				'type' => 'text',
				'title' => __ ( 'Selected option', 'nm-filemanager' ),
				'desc' => __ ( 'Type option name (given above) if you want already selected.', 'nm-filemanager' ) 
		),
		
		'required' => array (
				'type' => 'checkbox',
				'title' => __ ( 'Required', 'nm-filemanager' ),
				'desc' => __ ( 'Select this if it must be required.', 'nm-filemanager' ) 
		),
		
		'class' => array (
				'type' => 'text',
				'title' => __ ( 'Class', 'nm-filemanager' ),
				'desc' => __ ( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'nm-filemanager' ) 
		),
		'width' => array (
				'type' => 'text',
				'title' => __ ( 'Width', 'nm-filemanager' ),
				'desc' => __ ( 'Type field width in % e.g: 50%', 'nm-filemanager' ) 
		),
		
		);
	}
	
	
	/*
	 * @params: $options
	*/
	function render_input($args, $options="", $default=""){
		
		$_html = '<select class="form-control" ';
		
		foreach ($args as $attr => $value){
			
			$_html .= $attr.'="'.stripslashes( $value ).'"';
		}
		
		$_html .= '>';
		
		$_html .= '<option value="">'.__('Select option', $this -> plugin_meta['shortname']).'</option>';
		
		foreach($options as $opt)
		{
				
			$selected = ($opt == $default) ? 'selected="selected"' : '';
			$output = stripslashes(trim($opt));
				
			$_html .= '<option value="'.$opt.'" '. $selected.'>';
			$_html .= $output;
			$_html .= '</option>';
		}
		
		$_html .= '</select>';
		
		echo $_html;
	}
}