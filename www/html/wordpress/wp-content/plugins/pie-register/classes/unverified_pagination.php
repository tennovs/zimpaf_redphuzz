<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Pie_Unverfied_users_Table extends WP_List_Table {

  var $found_data;
  function __construct(){
    global $status, $page;

        parent::__construct( array(
            'singular'  => __( 'user', 'pie-register' ),     //singular name of the listed records
            'plural'    => __( 'users', 'pie-register' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?

    ) );

    add_action( 'admin_head', array( &$this, 'admin_header' ) );

  }

  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? sanitize_key( $_GET['page'] ) : false;
    if( 'my_list_test' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-username { width: 35%; }';
    echo '.wp-list-table .column-email { width: 20%; }';
    echo '.wp-list-table .column-reg_type { width: 20%; }';
    echo '.wp-list-table .column-role { width: 20%;}';
    echo '</style>';
  }

  function no_items() {
    _e( 'No unverfied users found.' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'username':
        case 'email':
        case 'reg_type':
        case 'role':
        case 'form_name':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'username'  => array('username',false),
      'email'     => array('email',false),
      'reg_type'  => array('reg_type',false),
      'role'      => array('role',false)
    );
    return $sortable_columns;
  }

  function get_columns(){
          $columns = array(
              'cb'        => '<input type="checkbox" />',
              'username'  => __( 'Username', 'pie-register' ),
              'email'    => __( 'Email', 'pie-register' ),
              'reg_type'  => __( 'Verification Type', 'pie-register' ),
              'role'      => __( 'Role', 'pie-register' ),
              'form_name' => __('Form Title','pie-register') 
          );
          return $columns;
      }

  function usort_reorder( $a, $b ) {
    // If no sort, default to username
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? sanitize_key($_GET['orderby']) : 'username';
    // If no order, default to asc
    $order = ( ! empty($_GET['order'] ) ) ? sanitize_key($_GET['order']) : 'asc';
    // Determine sort order
        
    /**
     * Actual: $result = strcmp( $a[$orderby], $b[$orderby] );
     * Changed because of case-insensitive issue 
     * **/
    $result = strcmp( strtolower($a[$orderby]), strtolower($b[$orderby]) );

    // Send final sort direction to usort
    return ( $order === 'asc' ) ? $result : -$result;
  }
  function column_username($item){
    $actions = array(
              'edit'      => sprintf('<a href="%s.php?user_id=%d">Edit</a>','user-edit',intval($item['ID']))
              // 'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
          );

    return sprintf('%1$s %2$s', $item['username'], $this->row_actions($actions) );
  }
  function get_bulk_actions() {
    $actions = array(
      'verify'                    => 'Verify Users',
      'resend_payment_email'      => 'Resend Payment Pending Email',
      'resend_verification_email' => 'Resend Verification Email',
      'delete'                    => 'Delete'
    );
    return $actions;
  }

  function process_bulk_action() {

    global $piereg_api_manager;

    // security check!
    if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
        $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
        $action = 'bulk-' . $this->_args['plural'];

        if ( ! wp_verify_nonce( $nonce, $action ) )
            die( 'Nope! Security check failed!' );

    }

    $action = $this->current_action();

    switch ( $action ) {
        case 'delete':
          $piereg_api_manager->AdminDeleteUnvalidated();
          break;

        case 'resend_payment_email':
          $piereg_api_manager->PaymentLink();
          break;

        case 'resend_verification_email':
          $piereg_api_manager->AdminEmailValidate();
          break;

        case 'verify':
          $piereg_api_manager->verifyUsers();
          break;

        default:
          // do nothing or something else
          return;
          break;
    }

    return;
  }

  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="vusers[]" value="%s" />', intval($item['ID'])
      );    
  }

  function prepare_items($search='') {
    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );
    $this->process_bulk_action();    

    $data = $this->getdata($search);

    if(isset($_GET['orderby']) && !empty($_GET['orderby'])) usort( $data, array( &$this, 'usort_reorder' ) );

    $user     = get_current_user_id();
    $screen   = get_current_screen();
    $option   = $screen->get_option('per_page', 'option');
    $per_page = get_user_meta($user, $option, true);    
    
    if ( empty ( $per_page) || $per_page < 1 ) {    
        $per_page = $screen->get_option( 'per_page', 'default' );    
    }
     
    $current_page = $this->get_pagenum();
    $total_items  = count( $data );

    // only ncessary because we have sample data
    $this->found_data = array_slice( $data,( ( $current_page-1 )* $per_page ), $per_page );

    $this->set_pagination_args( array(
      'total_items' => $total_items,                  //WE have to calculate the total number of items
      'per_page'    => $per_page                     //WE have to determine how many items to show on a page
    ) );
    $this->items = $this->found_data;
  }
  function extra_tablenav( $which ) {
    if ( $which === "top" ){
      ?>
      <div class="alignleft actions bulkactions">
        <select name="type-filter" class="pie-filer-type">
          <option value=""><?php _e('All Verification Types','pie-register'); ?></option>
          <option <?php echo selected(isset($_POST['type-filter']) && $_POST['type-filter'] == 'admin_verify', true); ?> value="admin_verify"><?php _e('Admin Verification','pie-register'); ?></option>
          <option <?php echo selected(isset($_POST['type-filter']) && $_POST['type-filter'] == 'email_verify', true); ?> value="email_verify"><?php _e('E-mail Verification','pie-register'); ?></option>
          <option <?php echo selected(isset($_POST['type-filter']) && $_POST['type-filter'] == 'admin_email_verify', true); ?> value="admin_email_verify"><?php _e('E-mail & Admin Verification','pie-register'); ?></option>
          <option <?php echo selected(isset($_POST['type-filter']) && $_POST['type-filter'] == 'payment_verify', true); ?> value="payment_verify"><?php _e('Payment Verification','pie-register'); ?></option>
        </select>         
      </div>
      <div class="alignleft actions bulkactions">
        <select name="role-filter" class="pie-filer-type">
          <option value=""><?php _e('All User Roles','pie-register'); ?></option>
          <?php
          global $wp_roles;
          $role = $wp_roles->roles;
          foreach($role as $key => $value)
          { 
            ?>
            <option <?php echo selected(isset($_POST['role-filter']) && $_POST['role-filter'] == $key, true); ?> value="<?php echo esc_attr($key) ?>"><?php echo esc_attr( trim( $value['name'] ) ); ?></option>
            <?php
          }
          ?>
        </select>         
      </div>
      <?php

      submit_button( __( 'Filter' ), '', 'filter_action', false, array( 'id' => 'users-query-submit' ) );
    }


    if ( $which === "bottom" ){
        //The code that goes after the table is there

    }
  }
  
  function getdata($search="")
  {

    $filter_reg_type = (isset($_POST['type-filter']) && !empty($_POST['type-filter'])) ? sanitize_key($_POST['type-filter']) : "";
    $filter_role     = (isset($_POST['role-filter']) && !empty($_POST['role-filter'])) ? sanitize_key($_POST['role-filter']) : "";
    
    $data = array();    
    $unverified = get_users(array(
                              'meta_key'    => 'active',
                              'meta_value'  => 0,
                              'role'        => $filter_role,
                              'search'      => '*'.$search.'*',
                              'orderby'     => 'user_registered',
                              'order'       => 'desc',
                              'meta_query'  => array(
                                array(
                                  'key'   => 'register_type',
                                  'value' =>  $filter_reg_type,
                                  'compare' => "LIKE"
                                )
                              )
                            ));
    
    foreach( $unverified as $key=>$un ) {
      if( isset($alt) ) $alt = ''; else $alt = "alternate";
        $user_object 	= new WP_User($un->ID);
        $roles		 	  = $user_object->roles;
        $role			    = array_shift($roles);
        $reg_type 	  = get_user_meta($un->ID, 'register_type');        
        $user_form_id = get_user_meta( $un->ID, "user_registered_form_id", true);
        $user_form_data = get_option("piereg_form_field_option_".$user_form_id);
        $user_form_name = isset($user_form_data['Title']) ? $user_form_data['Title'] : "";

        $data[$key]['ID'] = $un->ID;
        $data[$key]['username'] = $un->user_login;
        $data[$key]['email'] = $un->user_email;
        $type = 'default';
        if( isset($reg_type[0]) )
        {
          switch($reg_type[0]){
            case "email_verify":
              $type = __("E-mail Verification","pie-register");
            break;
            case "admin_verify":
              $type = __("Admin Verification","pie-register");
            break;
            case "admin_email_verify":
              $type = __("E-mail & Admin Verification","pie-register");
            break;
            case "payment_verify":
              $type = __("Payment Verification","pie-register");
            break;
            default:
              $type =  ucwords($reg_type[0]);
            break;
          }
        
        }
        $data[$key]['reg_type'] = $type;
        $data[$key]['role'] = ucwords($role);
        $data[$key]['form_name'] = $user_form_name;         
    }
    return $data;
  }
} //class
