<?php
/**
 * File Meta Manager Class
 **/
 
 if( ! defined('ABSPATH') ) die('Not Allowed');
 
class WPFM_Meta {
 
    private static $ins;
    
    function __construct() {
     
         // scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
    }
    
    public static function get_instance()
    {
        // create a new object if it doesn't exist.
        is_null(self::$ins) && self::$ins = new self;
        return self::$ins;
    }
    
    
    function add_scripts($hook) {
        
        
        if($hook != 'wpfm-files_page_wpfm-fields' ) return '';
        
        wp_enqueue_style('wpfm-fontawsome', WPFM_URL."/css/font-awesome.min.css");
        wp_enqueue_style('wpfm_field', WPFM_URL."/css/admin.css");
        
        // Swal
        wp_enqueue_style('wpfm-swal', WPFM_URL."/js/swal/sweetalert.css");
        wp_enqueue_script('wpfm-swal', WPFM_URL."/js/swal/sweetalertmeta.min.js", array('jquery'), WPFM_VERSION, true); 
        
        // Bootstrap
        wp_enqueue_style( 'wpfm-bootstrap4', WPFM_URL .'/css/bootstrap-4/css/bootstrap.css');
        wp_enqueue_script( 'wpfm-bootstrap4-min-js', WPFM_URL .'/css/bootstrap-4/bootstrap.min.js');
        wp_enqueue_style( 'wpfm-modal-css', WPFM_URL .'/css/wpfm-modal.css');
        
        // JS
        wp_enqueue_script('wpfm-field', WPFM_URL."/js/field.js", array('jquery','jquery-ui-core','jquery-ui-sortable'), WPFM_VERSION, true);

        // select2
        wp_enqueue_style( 'wpfm-select', WPFM_URL .'/css/select2.css');
        wp_enqueue_script( 'wpfm-select-js', WPFM_URL .'/js/select2.js', array('jquery'));
    }
    
    function render_field_settings() {
        
        $meta_key = 0;
        
        $wpfm_fields = $this->meta_array();
        
        $html = '<div id="wpfm-fields-wrapper">';
        foreach($wpfm_fields as $field_id => $setting) {

            $field_title = $setting['title'];
            $field_description = $setting['description'];

            $html .= '<div class="wpfm-slider wpfm-arrange-trash wpfm-field-'.esc_attr($field_id).'">';
            $html .='<input type="hidden" name="field_title" value="'.$field_title.'">';
            $html .= '<span class="wpfm-span wpfm_check ">
                        <i class="fa fa-times wpfm-remove-field" aria-hidden="true" title="Remove"></i>
                        <i class="fa fa-angle-double-down wpfm-clone-field" aria-hidden="true" title="Slide"></i>
                        <i class="fa fa-files-o wpfm-copy-field" aria-hidden="true" title="Copy" data-copy-field_id="'.esc_attr($field_id).'" ></i>
                    </span>';
            
            $html .= '<h3 class="next-slide field-heading" style="margin-top:0;">'.sprintf(__("%s","wpfm"),$field_title).'<span class="fields_title_show title_render" id=""></span></h3>';
            $html .= '<div class="wpfm-slider-inner wpfm_slide_toggle dccc container-fluid">';
            $html .= '<p class="meta-desc">'.sprintf(__("%s","wpfm"),$field_description).'</p>';
            $html .= $this->render_field_meta($setting['field_meta'], $field_id);

            $html .= '</div>';
            $html .= '</div>';

        $meta_key++;
        }

        $html .= '</div>';
        echo $html;
    }

    function render_field_meta($field_meta, $field_id, $field_index='', $save_meta='') {

        $html = '<table class="form-table wpfm-pd" data-table-id="'.esc_attr($field_id).'">';

        // wpfm_pa($field_meta);
        if ($field_meta) {
            
        foreach ($field_meta as $id => $meta) {
            
            // $data_name  = $meta['data_name'];
            $title      = $meta['title'];
            $desc       = $meta['desc'];   
            $type       = $meta['type'];

            $values = isset($save_meta[$id])? $save_meta[$id] : '';


            $html .= '<tr data-meta-id="'.esc_attr($id).'">';

            $html .= '<td class="col-title">'.sprintf(__("%s","wpfm"),$title).'</td>';
            
            $html .= '<td>'.$this->render_input($id, $meta, $field_id, $field_index, $values).'</td>';

            $html .= '<td style="color: #8a7777; font-size:12px;">'.sprintf(__("%s","wpfm"),$desc).'</td>';

            $html .= '</tr>';
        }
      }

        $html .= '</table>';

        return $html;        
    }

    function render_input($id, $meta, $field_id, $field_index, $value){
        
        
        $options    = isset($meta['options']) ? $meta['options'] : array();
        // wpfm_pa($value);
        $existing_name = 'name="wpfm['.esc_attr($field_index).']['.esc_attr($field_id).']['.esc_attr($id).']"';
        $meta_type  = $meta['type'];
        $html = '';
        
       switch ($meta_type) {
           
       case 'text':
           $html .= '<input type="text" class="wpfm-meta-field wpfm_inputs_design" data-metatype="'.esc_attr($id).'" value= "'.esc_attr($value).'"';
              if( $field_index != '') {

                  $html .= $existing_name;
                }
              $html .= '>';
           break;

       case 'checkbox':
            $checkbox_value = checked( $value , 'on' , false);
            
           $html .= '<input type="checkbox" class="wpfm-meta-field wpfm_inputs_design" data-metatype="'.esc_attr($id).'" value="on" ';
                if( $field_index != '') {
                  $html .= $existing_name;
                }
           $html .= '"'.esc_attr($checkbox_value).'">';
           break;

       case 'radio':
           $html .= '<input type="radio" class="wpfm-meta-field wpfm_inputs_design" data-metatype="'.esc_attr($id).'">';
           break;

       case 'wp_color':
           $html .= '<input style="border-color: #c3b9b9cc;" type="wp_color" class="wpfm-meta-field" class="wpfm-meta-field" data-metatype="'.esc_attr($id).'">';
           break;

       case 'select':
               $html .= '<select class="wpfm-select-design wpfm-meta-field sl_check" data-metatype="'.esc_attr($id).'"';
                if( $field_index != '') {
                    $html .= $existing_name;
                }
               $html .= '>';
                    foreach($options as $val => $text) {
                        $html .= '<option value="'.esc_attr($val).'" ' . selected( $value, $val, false). '>'.$text.'</option>';
                    }
                   
                   $html .= '</select>';
                   
               break;

        case 'options':

                $html .= $this->add_options($id, $value, $field_id, $field_index);
                break;
        case 'sp_roles':

            $html .= $this->specific_roles($id, $value, $field_index, $field_id);
            break;
        }  
       return $html;
   }
   
    function specific_roles($id, $value, $field_index, $field_id) {
       $_roles = get_editable_roles();
       $html  = '<span class="wpfm_roles_set">';
       $html .= '<select class="privacy_check wpfm-meta-pr-role" multiple style="width: 190px;" data-metatype="'.esc_attr($id).'"';
       if( $field_index != '') {
           $html .= 'name="wpfm['.esc_attr($field_index).']['.esc_attr($field_id).']['.esc_attr($id).'][]"';
       }
       $html .='>';
       foreach ($_roles as $role => $role_name) {
            $selected = '';
             if( !empty($value) ) {
               $selected = in_array($role, $value) ? 'selected="selected"' : '';
            }
       $html .= '<option value="'.esc_attr($role).'" '.$selected.'>'.sprintf(__("%s","wp-registration"),$role).'</option>';
       }
       $html .= '</select>';
       $html .= '</span>';

      return $html;
    }

   function add_options($id, $value=array(), $field_id, $field_index){
        
        if( empty($value) ) {

            $value = array(__('Option', 'wpfm'));
        }
            $add_opt_index = 0; 

        $html = '<div class="controls hrlo add-option-design" data-opt-ex="'.esc_attr($field_index).'">';
        foreach ($value as $opt_index => $opt_val) {
            $existing_name = 'name="wpfm['.esc_attr($field_index).']['.esc_attr($field_id).']['.esc_attr($id).']['.esc_attr($opt_index).']"';
            $html .= '<div class="entry input-group" style="Width:100%">';
                   $html  .= '<input class="wpfm-meta-field wpfm-meta-field-opt" type="text" data-metatype="'.esc_attr($id).'" value="'.esc_attr($opt_val).'"';
                if( $field_index != '') {
                    $html .= $existing_name;
                }

                   $html .= ' style="width: 100%;">';
                   $html  .='<input type="hidden" id="meta-field-type" value="'.esc_attr($field_id).'">';
                   $add_opt_index =  $opt_index;
                   $add_opt_index++;
        }
                    $html  .= '<span class="input-group-btn">';
                        $html  .=   '<button class="btn btn-success btn-add btn-done btn-sm" type="button" style="height:26px;">';
                           $html .=   '<i class="fa fa-plus" aria-hidden="true"></i>';
                           $html .=   '<div class="btn-add-remove"></div>';
                        $html .=  '</button>';
                   $html  .=  '</span>';
            $html .= '</div>';
                   $html  .='<input type="hidden" id="meta-opt-index" value="'.esc_attr($add_opt_index).'">';
        $html .='</div>';
          return $html;
   }

   // this function control edit fields options 
   /*function wpfm_edit_field($id, $value, $field_index, $existing_name){
        $yes = checked( $value , 'yes',false);
        $no  = checked( $value , 'no', false);
        $html = '<div class="btn-group wpfm-switch edit-rol" data-toggle="buttons">';
            $html .= '<label class="btn btn-default btn-on btn-sm active-rd">';
            $html .= '<input type="radio" class="wpfm-meta-field edit-rd" value="yes" checked="checked" data-metatype="'.esc_attr($id).'"';
            if( $field_index != '') {
                $html .= $existing_name;
            }
            $html .= '"'.esc_attr($yes).'">yes</label>';
            $html .= '<label class="btn btn-default btn-off btn-sm inactive-rd">';
            $html .= '<input type="radio" class="wpfm-meta-field edit-rd" value="no" data-metatype="'.esc_attr($id).'"';
            if( $field_index != '') {
                $html .= $existing_name;
            }
            $html .= '"'.esc_attr($no).'">no</label>';
        $html .= '</div>';
        return $html;
   }*/
    
    
    //get fields title
    function meta_title(){
    
        return array (
                'type' => 'text',
                'title'=> __ ( 'Title', 'wpfm' ),
                'desc' => __ ( 'It will be shown as field label', 'wpfm' ),
                'default' => '',
            );
    }
    
    // get fields data name
    function meta_data_name(){
        return array (
                'type'  => 'text',
                'title' => __ ( 'Field ID', 'wpfm' ),
                'desc'  => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'wpfm' )
            );
    }
    
    
    // get fields description
    function meta_desc(){
      return  array (
                'type'  => 'text',
                'title' => __ ( 'Description', 'wpfm' ),
                'desc'  => __ ( 'Small description, it will be diplay near name title.', 'wpfm' ) 
            );
    }
    
    // get fields required option
    function meta_required(){
    
        return array (
                'type'  => 'checkbox',
                'title' => __ ( 'Required', 'wpfm' ),
                'desc'  => __ ( 'Select this if it must be required.', 'wpfm' ) 
            );
    }
    
    // get fields classes
    function meta_class(){
       return  array (
                'type'  => 'text',
                'title' => __ ( 'Class', 'wpfm' ),
                'desc'  => __ ( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'wpfm' ) 
                );
        
    }
    
    // get fields add options for select
    function meta_add_options(){
        return array (
                'type'  => 'options',
                'title' => __ ( 'Add options', 'wpfm' ),
                'desc'  => __ ( 'Enter multiple options.', 'wpfm' )
            );
    }
    
    // get fields selected options that already selected for selected fields
    function meta_select_options() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Selected Option', 'wpfm' ),
                    'desc'  => __ ( 'Type option name (given above) if you want already selected.', 'wpfm' ) 
                );
    }
    
    // get fields options that already selected for checkbox fields
    function meta_check_option() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Checked option(s)', 'wpfm' ),
                    'desc'  => __ ( 'Type option(s) name (given above) if you want already checked.', 'wpfm' ) 
                );
    }
    
    // get minimum checkbox is checked for checkbox fields
    function meta_width_min_check() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Min checked option(s)', 'wpfm' ),
                    'desc'  => __ ( 'How many options can be checked by user e.g: 2. Leave blank for default.', 'wpfm' ) 
                );
    }
    
    // get maximum checkbox is checked for checkbox fields
    function meta_width_max_check() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Max checked option(s)', 'wpfm' ),
                    'desc'  => __ ( 'How many options can be checked by user e.g: 3. Leave blank for default.', 'wpfm' ) 
                );
    }
    
    // get fields placeholder
    function meta_placeholder() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Placeholder', 'wpfm' ),
                    'desc'  => __ ( 'Type field Placeholder.', 'wpfm' ) 
                );
    }

    // get fields default value
    function meta_default_value() {
        return array (
                    'type'  => 'text',
                    'title' => __ ( 'Default value', 'wpfm' ),
                    'desc'  => __ ( 'Type field Default value.', 'wpfm' ) 
                );
    }

    // get fields placeholder
    function meta_permission_fields() {
        
        $wpfm_permission_fields = array(    
           'everyone'       => __('Everyone', 'wpfm'),
           'members'         => __('Members(logged in)', 'wpfm'),
            'specific_role'     => __('Specific roles', 'wpfm'),
        );

        return array (
                    'type'  => 'select',
                    'title' => __ ( 'Permission', 'wpfm' ),
                    'desc'  => __ ( 'select permissions for view fields.', 'wpfm' ),
                    'options'=> $wpfm_permission_fields,
                );
    }

    function meta_Privacy_specific_roles() {
       return array (
                   'type'  => 'sp_roles',
                   'title' => __ ( 'Member Roles', 'wp-registration' ),
                   'desc'  => __ ( 'Select the member roles that can view this field on the front-end.', 'wp-registration' ),
               );
   }
    // get date formats for date fields 
    function date_formats(){
        
        $wpfm_date_formats = array(    
                'mm/dd/yy'    => __('Default - mm/dd/yy', 'wpfm'),
                'yy-mm-dd'    => __('ISO 8601 - yy-mm-dd', 'wpfm'),
                'd M, y'     => __('Short - d M, y',   'wpfm'),
                'd MM, y'    => __('Medium - d MM, y', 'wpfm'),
                'DD, d MM, yy'      => __('Full - DD, d MM, yy', 'wpfm'),
                '\'day\' d \'of\' MM \'in the year\' y'           => __('With text - \'day\' d \'of\' MM \'in the year\' yy', 'wpfm'),
            );
            
        return array (
            'type'  => 'select',
            'title' => __ ( 'Date formats', 'wpfm' ),
            'desc'  => __ ( 'Select date format.', 'wpfm' ),
            'options' => $wpfm_date_formats,
            'default' => '', 
        );
    }
    
   
    // Get input fields meta
    function meta_array() {

        $wpfm_fields = array(
            'text' => array(
    
                'title'       => __('Text Input','wpfm'),
                'icon'        => __('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>','wpfm'),
                'description' => __('Textbox Field','wpfm'),
                'field_meta'  => array (
                    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'default_value'   => $this->meta_default_value(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
                )
            ),
    
            'select' => array(
    
                'title'       => __('Select Input','wpfm'),
                'icon'        => __('<i class="fa fa-check" aria-hidden="true"></i> ','wpfm'),
                'description' => __('Select Field','wpfm'),
                'scripts'     => array( 'js'  =>array(
                                            'source' => 'js/select2.js',
                                            'depends' => array('jquery'),
                                        ),
                                    'css' => array(
                                        'source' => 'css/select2.css',
                                        ),
                ),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'options'   => $this->meta_add_options(),
                    'select_option' => $this->meta_select_options(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
                )
            ),
    
            'radio' => array(
    
                'title'       => __('Radio Input','wpfm'),
                 'icon'       => __('<i class="fa fa-dot-circle-o" aria-hidden="true"></i>','wpfm'),
                'description' => __('Radio Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'options'   => $this->meta_add_options(),
                    'select_option' => $this->meta_select_options(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
                )
            ),
    
            'checkbox' => array(
    
                'title'       => __('Checkbox Input','wpfm'),
                 'icon'       => __('<i class="fa fa-check-square-o" aria-hidden="true"></i>','wpfm'),
                'description' => __('Checkbox Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'options'   => $this->meta_add_options(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
                    'check_option'  => $this->meta_check_option(),
                    'min_checked'   => $this->meta_width_min_check(),
                    'max_checked'   => $this->meta_width_max_check(),
                )
            ),
    
            'date' => array(
    
                'title'       => __('Date Input','wpfm'),
                 'icon'       => __('<i class="fa fa-calendar" aria-hidden="true"></i>','wpfm'),
                'description' => __('Date Field','wpfm'),
                'scripts'     => array( 'default'  =>array(
                                                    'source' => 'jquery-ui-datepicker',
                                                    ),
                                        'css' => array(
                                                    'source' => 'css/ui//css/smoothness/jquery-ui-1.10.3.custom.min.css',
                                                    ),
                            ),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'required'      => $this->meta_required(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'class'         => $this->meta_class(),
                    // 'year_range'    => array (
                    //                     'type'  => 'text',
                    //                     'title' => __ ( 'Year Range', 'wpfm' ),
                    //                     'desc'  => __ ( 'e.g: 1950:2016. (if jQuery enabled above) Set star/end 
                    //                                     year like ', 'wpfm' ) 
                    //                     ),
                    'default_value'   => $this->meta_default_value(),
                    'date_formats'  => $this->date_formats(),
                )
            ),
    
            'color' => array(
    
                'title'       => __('Color Input','wpfm'),
                 'icon'       => __('<i class="fa fa-user-plus" aria-hidden="true"></i>','wpfm'),
                'description' => __('Color Field','wpfm'),
                'scripts'     => array( 'js'  =>array(
                                                    'source' => 'js/Iris/dist/iris.min.js',
                                                    'depends' => array('jquery','jquery-ui-core','jquery-ui-draggable', 'jquery-ui-slider')
                                                ),
                                        ),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'required'      => $this->meta_required(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'default_value'   => $this->meta_default_value(),
                    'show_palletes'  => array (
                                          'type'  => 'checkbox',
                                          'title' => __ ( 'Show palletes', 'wpfm' ),
                                          'desc'  => __ ( 'Tick if need to show a group of common colors beneath the square', 'wpfm' ) 
                                        ),
                )
            ),
  
            'email' => array(
    
    
                'title'       => __('Email Input','wpfm'),
                'icon'        => __('<i class="fa fa-user-plus" aria-hidden="true"></i>','wpfm'),
                'description' => __('Email Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'required'      => $this->meta_required(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'class'         => $this->meta_class(),
                    
                )
            ),
            'url' => array(
    
                'title'       => __('Url Input','wpfm'),
                'icon'        => __('<i class="fa fa-user-plus" aria-hidden="true"></i>','wpfm'),
                'description' => __('Url Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'required'      => $this->meta_required(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'=> $this->meta_Privacy_specific_roles(),
                    'class'         => $this->meta_class(),
                    'default_value'   => $this->meta_default_value(),
                )
            ),
            'number' => array(
    
                'title'       => __('Number Input','wpfm'),
                 'icon'       => __('<i class="fa fa-hashtag" aria-hidden="true"></i>','wpfm'),
                'description' => __('Number Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'max_values'    => array (
                                      'type'  => 'text',
                                      'title' => __ ( 'Max. values', 'wpfm' ),
                                      'desc'  => __ ( 'Max. values allowed, leave blank for default', 'wpfm' ) 
                                    ),
                    'min_values'    => array (
                                      'type'  => 'text',
                                      'title' => __ ( 'Min. values', 'wpfm' ),
                                      'desc'  => __ ( 'Min. values allowed, leave blank for default', 'wpfm' ) 
                                      ),
                    'steps'        => array (
                                    'type'  => 'text',
                                    'title' => __ ( 'Steps', 'wpfm' ),
                                    'desc'  => __ ( 'specified legal number intervals', 'wpfm' ) 
                                    ),
                    'default_value'   => $this->meta_default_value(),
                    'required'      => $this->meta_required(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'class'         => $this->meta_class(),
    
                )
            ),
            'textarea' => array(
    
                'title'       => __('Textarea Input','wpfm'),
                 'icon'       => __('<i class="fa fa-file-text-o" aria-hidden="true"></i>','wpfm'),
                'description' => __('Textarea Field','wpfm'),
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'permission'    => $this->meta_permission_fields(),
                    'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
    
                )
            ),
            'autocomplete' => array(
    
                'title'       => __('Auto Complete','wpfm'),
                 'icon'       => __('<i class="fa fa-user-plus" aria-hidden="true"></i>','wpfm'),
                'description' => __('Auto Complete Field','wpfm'),
                'scripts'     => array( 'js'  =>array(
                                            'source' => 'js/select2.js',
                                            'depends' => array('jquery'),
                                        ),
                                    'css' => array(
                                        'source' => 'css/select2.css',
                                        ),
                ),
    
                'field_meta'  => array (
    
                    'title'         => $this->meta_title(),
                    'data_name'     => $this->meta_data_name(),
                    'desc'          => $this->meta_desc(),
                    'placeholder'   => $this->meta_placeholder(),
                    'options'   => $this->meta_add_options(),
                     'max_select'    => array (
                                      'type'  => 'text',
                                      'title' => __ ( 'Max Select', 'wpfm' ),
                                      'desc'  => __ ( 'Enter number to maximum select options', 'wpfm' ) 
                                    ),
                     'permission'    => $this->meta_permission_fields(),
                     'specific_roles'    => $this->meta_Privacy_specific_roles(),
                    'required'      => $this->meta_required(),
                    'class'         => $this->meta_class(),
    
                )
            ),
        );
    
        return apply_filters('wpfm_field_meta_array', $wpfm_fields);
    }

    // This function will return field's meta
    function get_field_settings( $field_type ) {
    
        $all_fieds = $this->meta_array();
        if( isset( $all_fieds[$field_type]) ) {
    
            return $all_fieds[$field_type];
        }
    }

}


WPFM_META();
function WPFM_META(){
    return WPFM_Meta::get_instance();
}
 
