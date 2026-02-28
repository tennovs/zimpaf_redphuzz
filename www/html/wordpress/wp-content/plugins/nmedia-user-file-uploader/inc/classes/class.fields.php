<?php 
/**
** Building file fields(meta)
**/

class WPFM_Fields
{
	
	var $file_id;
	var $file_fields;
	var $saved_meta;
	// var $form_settings;
	
	
	private static $ins = null;

	function __construct( $file_id, $saved_meta ){
		
		$this->file_id 		= $file_id;
 		$this->file_fields	=  wpfm_get_fields_meta();
 		$this->saved_meta	= $saved_meta;
	}

	function generate_fields() {

		if( $this->file_fields == '' ) return '';
		
		$field_html  = '<input type="hidden" name="file_id" value="'.esc_attr($this->file_id).'">';
		$field_html .= '<table class="table table-striped ffmwp-file-meta-table">';
		$field_html .= '<thead>';
		$field_html .= '<tr>';
		$field_html .= '<th>'.__('Fields' , 'wpfm').'</th>';
		$field_html .= '<th>'.__('Value' , 'wpfm').'</th>';
		$field_html .= '</tr>';
		$field_html .= '</thead>';
		$field_html .= '<tbody>';
		

		foreach($this->file_fields as $field) {
			
			foreach ($field as $type => $meta) {

	
				if( ! isset($meta['data_name']) ) continue;
				if( ! $this->is_field_visibility($meta) ) continue;

				$field_name 	=  $meta['data_name'];
				$default_val 	=  isset($meta['default_value']) ? $meta['default_value'] : 'Null';
				$title 			=  isset($meta['title']) ? $meta['title'] : 'Title';
				$desc 			=  isset($meta['desc']) ? $meta['desc'] : '';
				$class 			=  isset($meta['class']) ? preg_replace('/[,]+/', ' ', trim($meta['class'])) : '';
				$placeholder 	=  isset($meta['placeholder']) ? $meta['placeholder'] : '';
				$required 		=  isset($meta['required']) ? 'required' : '';
				$input_mask 	=  isset($meta['input_mask']) ? $meta['input_mask'] : '';	
				$max_values 	=  isset($meta['max_values']) ? $meta['max_values'] : '';	
				$min_values 	=  isset($meta['min_values']) ? $meta['min_values'] : '';	
			
				
				// geting value
				$saved_value = $this->get_default_value($field_name);
				
				
		
				$default_val = $saved_value != 'Null' ? $saved_value : $default_val;
				// var_dump($field_name, $default_val); exit;
    //             if(empty($default_val)){
				//     $default_val = 'Empty';
				// }

				if(is_admin()){
					$required  = '';
				}	

				$field_html .= '<tr>';
				$field_html .= '<td>';
				$field_html .= '<label>';
				$field_html .= $title;
				$field_html .= '<sapn class="description"> '.$desc.'<span>';
				$field_html .= '</label>';
				$field_html .= '</td>';
				$field_html .= '<td>';
				
				

				switch ( $type ) {
					case 'text':
						
						$field_html .= '<input type="text" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';	
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= $required.'>';
						break;
					case 'date':

						$field_html .= '<input type="date" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';	
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= $required.'>';
						break;
					
					case 'select':
						$selected_value = $meta['select_option'] ? $meta['select_option'] : '';
						$field_html .= '<select ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';
						$field_html .= $required.'>';
						$options = $meta['options'];
						foreach ($options as $index => $value) {
							$field_html .= '<option';
							$field_html .= selected( $default_val, $value, false );
							$field_html .= '>';
							$field_html .= $value;
							$field_html .= '</option>';
						}
						$field_html .= '</select>';
						break;
					

					case 'checkbox':
						
						// wpfm_pa($default_val);
						
						foreach ($meta['options'] as $index => $value) {
							$checked =  is_array($default_val) && in_array($value, $default_val) ? 'checked="checked"' : '';
							$field_html .= '<label class="checkbox-inline">';
							$field_html .= '<input type="checkbox" ';
							$field_html .= 'name="'.esc_attr($field_name).'[]" ';				
							$field_html .= 'class="'.esc_attr($class).'" ';
							$field_html .= 'value="'.$value.'" ';
							$field_html .= $required;
							$field_html .= $checked;
							$field_html .= '> ';
							$field_html .= $value;
							$field_html .= '</label>';
						}

						break;

					case 'radio':
						
						foreach ($meta['options'] as $index => $value) {
							$field_html .= '<label class="radio-inline">';
							$field_html .= '<input type="radio" ';
							$field_html .= 'name="'.esc_attr($field_name).'" ';				
							$field_html .= 'class="'.esc_attr($class).'" ';
							$field_html .= 'value="'.$value.'" ';
							$field_html .= $required;
							$field_html .= checked( $default_val, $value, false );
							$field_html .= '> ';
							$field_html .= $value;
							$field_html .= '</label>';
						}

						break;
					case 'number':
					
						$field_html .= '<input type="number" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';	
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= 'min="'.esc_attr($min_values).'" ';	
						$field_html .= 'max="'.esc_attr($max_values).'" ';	
						$field_html .= $required.'>';

						break;
					case 'color':

						$field_html .= '<input type="color" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';					
						$field_html .= 'class="'.esc_attr($class).'" ';
						$field_html .= $required.'>';
						break;
					
					case 'email':
						
						$field_html .= '<input type="email" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= $required.'>';
						break;

					case 'url':
						
						$field_html .= '<input type="url" ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';
						$field_html .= 'value="'.esc_attr($default_val).'" ';
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= $required.'>';
						break;

					case 'textarea':
						
						$field_html .= '<textarea ';
						$field_html .= 'name="'.esc_attr($field_name).'" ';					
						$field_html .= 'class="form-control '.esc_attr($class).'" ';
						$field_html .= 'placeholder="'.esc_attr($placeholder).'" ';	
						$field_html .= $required.'>';
						$field_html .= esc_textarea($default_val);
						$field_html .= '</textarea>';
						break;
				}
				$field_html .= '</td>';
				$field_html .= '</tr>';
			}
		}
		$field_html .= '</tbody>';
		$field_html .= '</table>';

		return apply_filters('wpfm_fields_html', $field_html, $this);
	}

	function get_default_value($data_name) {

		$input_value = null;
		if( isset($this->saved_meta[$data_name]) && $this->saved_meta[$data_name] != '' ) {

			$input_value = $this->saved_meta[$data_name];
		}
		

		return apply_filters('wpfm_input_value', $input_value, $data_name, $this);
	}

	function generate_saved_meta() {

		if( $this->file_fields == '' ) return '';
		
		$field_html = '<table class="table table-bordered table-striped">';
		$field_html .= '<thead>';
		$field_html .= '<tr>';
		$field_html .= '<th>'.__('Fields' , 'wpfm').'</th>';
		$field_html .= '<th>'.__('Value' , 'wpfm').'</th>';
		$field_html .= '</tr>';
		$field_html .= '</thead>';
		$field_html .= '<tbody>';

		foreach($this->file_fields as $field) {
			
			foreach ($field as $type => $meta) {

				if( ! isset($meta['data_name']) ) continue;
				if(  ! $this->is_field_visibility($meta) ) continue;

				$field_name 	=  $meta['data_name'];
				$default_val 	=  isset($meta['default_value']) ? $meta['default_value'] : '';
				$title 			=  isset($meta['title']) ? $meta['title'] : 'Title';
				$desc 			=  isset($meta['desc']) ? $meta['desc'] : '';
				
				// get value
				$saved_value = $this->get_default_value($field_name);
				$saved_value = is_array($saved_value) ? implode(',', $saved_value) : $saved_value;
				$default_val = $saved_value != '' ? $saved_value : $default_val;

				$field_html .= '<tr>';
				$field_html .= '<td>';
				$field_html .= '<label>';
				$field_html .= $title;
				$field_html .= '</label>';
				$field_html .= '</td>';
				$field_html .= '<td class="'. $meta['data_name'] .'">';
				$field_html .= $default_val;

				$field_html .= '</td>';
				$field_html .= '</tr>';
			}
		}
		$field_html .= '</tbody>';
		$field_html .= '</table>';

		return apply_filters('wpfm_fields_saved_meta', $field_html, $this);
	}


	function is_field_visibility( $meta ) {

	    $privacy_role = isset($meta['specific_roles']) ? $meta['specific_roles'] : array();
	    $permission    = isset($meta['permission']) ? $meta['permission'] : '';
	    $visiblity  = false;

	    if( ! isset($permission) ) {

	       $visiblity = true;
	    } else {

	       switch ( $permission ) {
	           // everyone permission option set to view fields to all
	           case 'everyone':
	               $visiblity = true;
	               break;

	           // member permission option set to view fields to only members
	           case 'members':

	               if( is_user_logged_in() ) {

	                   $visiblity = true;
	               }
	               break;

	           
	           // Only visible to profile owner and specific roles
	           case 'specific_role':

	               // Get logged in user role
	               $curent_user_role = wpfm_get_current_user_role();

	               // if( ( in_array( $curent_user_role, $permission_role)) ||
	               // $this->id() == get_current_user_id() ) {
	                if(  in_array( $curent_user_role, $privacy_role) ) {


	                   $visiblity = true;
	               }
	               break;

	       }
	    }

	    return $visiblity;
	}
}