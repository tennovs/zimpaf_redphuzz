<?php

class PieReg_WPBakery extends WPBakeryShortCode {
    function __construct(){

        // add_action( 'admin_init', array( $this, 'mapping' ) );
        vc_add_shortcode_param( 'search_select',array($this, 'search_select') );
        add_action( 'wp_loaded', array( $this, 'mapping' ) );
        add_shortcode('pieregister_for_wpb',array($this,'shortcode_html'));

    }
    function search_select( $param, $value ) {
        $param_line = '';
        $param_line .= '<select name="'. esc_attr( $param['param_name'] ).'" class="vc-search-select wpb_vc_param_value wpb-input wpb-select'. esc_attr( $param['param_name'] ).' '. esc_attr($param['type']).'">';

        foreach ( $param['value'] as $val => $text_val ) {

                $text_val = __($text_val, "js_composer");
                $selected = '';

                if(!is_array($value)) {
                    $param_value_arr = explode(',',$value);
                } else {
                    $param_value_arr = $value;
                }

                if ($value!=='' && in_array($val, $param_value_arr)) {
                    $selected = ' selected="selected"';
                }
                $param_line .= '<option value="'.$val.'"'.$selected.'>'.$text_val.'</option>';
            }
        $param_line .= '</select>';
                
        return  $param_line;
    }
    function mapping(){

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
        
        $forms_id = [];
        $forms_id['login_form'] = 'Login Form';
        $forms_id['forgot_password'] = 'Forgot Password';

        $forms = new PieReg_Base();
        $piereg_forms = $forms->get_pr_forms_info_check();
        foreach($piereg_forms as $key => $value){
            $forms_id[$value['Id']] = $value['Title'];
        }
        // Map the block with vc_map()
        vc_map(
            array(
                'name'          => __('Pie Register', 'bit14'),
                'base'          => 'pieregister_for_wpb',
                'description'   => __('Custom Registration Form Plugin for your WordPress Website', 'bit14'),
                'category'      => __('Pie Register', 'bit14'),
                'icon'          => PIEREG_PLUGIN_URL . 'assets/images/editors/wpbakery/pie-register-element.png',
                'params'        => array(
                    array(
                        "type"          => "search_select",
                        "heading"       => esc_html__("Forms", 'pie-register'),
                        "param_name"    => "form_id",
                        "value"         =>  $forms_id,
                    ),
                )
            )
        );
    }

    function shortcode_html($atts, $content = null){
        $output = '';

        if ( $this->is_wpb_editor() ) {
            add_filter(
                'pie_register_frontend_output_before',
                function ( $registration_from_fields ) {
                    $registration_from_fields .= '<fieldset disabled>';
                    return $registration_from_fields;
                }
            );
            add_filter(
                'pie_register_frontend_output_after',
                function ( $registration_from_fields ) {
                    $registration_from_fields .= '</fieldset>';
                    return $registration_from_fields;
                }
            );
            add_filter(
                'pie_register_frontend_login_output_before',
                function ( $login_form_fields ) {
                    $login_form_fields .= '<fieldset disabled>';
                    return $login_form_fields;
                }
            );
            add_filter(
                'pie_register_frontend_login_output_after',
                function ( $login_form_fields ) {
                    $login_form_fields .= '</fieldset>';
                    return $login_form_fields;
                }
            );
            add_filter(
                'pie_register_forgot_pass_output_before',
                function ( $forgot_pass_fields ) {
                    $forgot_pass_fields .= '<fieldset disabled>';
                    return $forgot_pass_fields;
                }
            );
            add_filter(
                'pie_register_forgot_pass_output_after',
                function ( $forgot_pass_fields ) {
                    $forgot_pass_fields .= '</fieldset>';
                    return $forgot_pass_fields;
                }
            );
        }

        extract( shortcode_atts( array(
            'form_id'   =>  '',
        ), $atts ) );
 
        if($form_id == 'login_form'){
            $output = '<div>';
                $output .= do_shortcode("[pie_register_login]");
            $output .= '</div>';
        }else if($form_id == 'forgot_password'){
            $output = '<div>';
                $output .= do_shortcode("[pie_register_forgot_password]");
            $output .= '</div>';
        }else{
            $output = '<div>';
                $output .= do_shortcode("[pie_register_form id='".$form_id."']");
            $output .= '</div>';
        }

        return $output;
    }

    function is_wpb_editor(){
        return ! empty( $_REQUEST['vc_editable'] ) && 'true' === $_REQUEST['vc_editable'];
    }
}

new PieReg_WPBakery;