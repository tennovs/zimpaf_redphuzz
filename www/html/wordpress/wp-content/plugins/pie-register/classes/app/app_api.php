<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class Pie_api extends PieReg_Base {

  
    function __construct()
    {
      parent::__construct();
      //REST API INIT
      add_action('rest_api_init', array( &$this, 'registerRoute') );
      add_action('pie_register_after_user_created',array( &$this, 'registerUser'));

    }
    
    /*
      ======== ************************ ========
      ======== ROUTE REGISTRATION START ========
      ======== ************************ ========
    */

    function registerRoute()
    {
        register_rest_route("pie/v1","/login",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_login_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/unverify",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_unverify_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/user-data",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_user_data_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/act-verify",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_act_verify_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/invitation",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_invitation_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/act-invitation",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_act_invitation_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/generate-invitation-manual",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_gene_invitation_callback"),
          'permission_callback' => '__return_true'
        )); 

        register_rest_route("pie/v1","/generate-invitation-auto",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_auto_gene_invitation_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/invitation-email-template-get",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_invitation_email_template_get_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/invitation-email-template-set",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_invitation_email_template_set_callback"),
          'permission_callback' => '__return_true'
        ));
        
        register_rest_route("pie/v1","/registration-page-list",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_registration_page_list_callback"),
          'permission_callback' => '__return_true'
        ));
        
        register_rest_route("pie/v1","/invite-user-email",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_invite_user_email_callback"),
          'permission_callback' => '__return_true'
        ));
        
        register_rest_route("pie/v1","/enable-email",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_enable_email_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/is-pro-user",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_is_pro_user_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/edit-invitation",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_edit_invitation_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/token-key",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_token_key_callback"),
          'permission_callback' => '__return_true'
        ));

        register_rest_route("pie/v1","/feedback",array(
          "methods"   => "POST",
          "callback"  => array($this, "pie_feedback"),
          'permission_callback' => '__return_true'
        ));

    }


    /*
      ======== ************************ ========
      ======== CALLBACK FUNCTION START ========
      ======== ************************ ========
    */

    /*============================= 
    CALLBACK API KEY ROUTE: 
    http://DOMAIN-NAME/wp-json/pie/v1/token-key
    ==================================*/

    function pie_feedback(  $request_data )
    {
      $data       = array();
      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce       = $this->authKey($auth_key);
      $status_code          = ( count( $auth_key_nonce ) > 0 );
      

      if ($status_code) {
          $req_data             = $request_data->get_json_params();

          $from_name  = $req_data['username'];
          $from_email = $req_data['email'];

          $email      = "pieregister@genetechsolutions.com";
          $subject    = "Feedback - Pie Register APP";
          $emailbody  = $req_data['message'];

          $headers    .= "From: ".$from_name." <".$from_email."> \r\n";

          if(wp_mail($email, $subject, $emailbody, $headers)){
              $data['message']      = "Email Sent";    
              $data['status_code']  = 200;    
          }else{
              $data['message']      = "Failed to send feedback pie-register";
          }
      }else{
        $data['message'] = "Authentication Error";
       // $data['status'] = "Authentication Error"
      }

      $data['status_code']  = $this->statusCode($status_code);

      return $data;

    }

    function pie_token_key_callback(  $request_data )
    {

      $data                 = array();

      
      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce       = $this->authKey($auth_key);

      $status_code          = ( count( $auth_key_nonce ) > 0 );
      
      $data['status_code']  = $this->statusCode($status_code);
      $data['message']      = "Authentication Errror";     

      if($status_code){
        $req_data             = $request_data->get_json_params();
        $id                   = $req_data['id'];
        $api_key              = $req_data['auth_api_key'];

        if(!empty($id) && !empty($api_key)){
          update_user_meta($id, 'auth_api_key', $api_key);
          $data['message']      = "success";     
          //$data['status_code']  = 200;     
        }else{
          $data['message']  = "error";     
          $data['status_code']  = 404;     

        }
      }

      return $data;

    }

    /*============================= 
    CALLBACK LOGIN ROUTE: 
    http://DOMAIN-NAME/wp-json/pie/v1/login
    ==================================*/

    function pie_login_callback(  $request_data )
    {
      
      global $wpdb;

      $data = $request_data->get_params();
      
      $login      = isset($data['user_login']) && $data['user_login'] != '' ? sanitize_user( trim($data['user_login']) ) : '';
      $password   = isset($data['user_pass']) && $data['user_pass'] != '' ? trim( $data['user_pass'] ) : '';
      
      // Authenticate login request is a valid user.
      $user_id = $this->piereg_authentication($login,$password);
      
      // If user is authenticated and has an administrotor role then logs a user in.
      if($user_id !== false && $this->pie_is_user_in_role($user_id, 'administrator')){
        
        $creds = array();
        
        $creds['user_login']    = $login;
        $creds['user_password'] = $password;
        $creds['remember']      = true;
        $piereg_secure_cookie   = $this->PR_IS_SSL();

        $user = wp_signon( $creds, $piereg_secure_cookie);

      } else {
        $user = new WP_Error('piereg_authentication_failed', __("User credentials are invalid or user is not administrator.","pie-register"));
      }
      
      return $this->getSignInDetails($user);      
    }


    /*=============================================== 
    CALLBACK UNVERIFY ACTION TO VERIFY: 
    http://DOMAIN-NAME/wp-json/pie/v1/act-verify
    =================================================*/
    function pie_act_verify_callback($request_data)
    {
      $data = array();

      $getHeaders = apache_request_headers();
      $auth_key = $getHeaders['auth_key'];
     
      //GET AUTH KEY
      $auth_key_nonce       = $this->authKey($auth_key);
      $status_code          = ( count( $auth_key_nonce ) > 0 );
      
      if($status_code){
        global $piereg_api_manager;
        $req_data             = $request_data->get_params();
        $action               = $req_data['action'];
        $_POST['vusers']      = $req_data['vusers'];
        $req_verify_users     = (is_array($_POST['vusers'])) ? array_map( 'sanitize_key', $_POST['vusers'] ) : sanitize_key($_POST['vusers']);
        
        $_verify_users        = ( !empty($req_verify_users) && count($auth_key_nonce) > 0 && !empty($action));
        if($_verify_users){
          if($action == "verify"){
            $piereg_api_manager->verifyUsers();
            $data['data']       =     array("action_message"=>  "Verified"); 
          }
  
          if($action == "delete" ){
              $piereg_api_manager->AdminDeleteUnvalidated();
              $data['data']    =   array("action_message"=>  "Deleted"); 
          }
  
          if($action == "resend_payment_email" ){
            $piereg_api_manager->PaymentLink();
            $data['data']     =   array("action_message"=>  "Resend payment email"); 
          }
  
          if($action == "resend_verify_email" ){
            $piereg_api_manager->AdminEmailValidate();
            $data['data']     = array("action_message"=>  "Resend verify email"); 
          }
        }
      }
      
      $data['status_code']   = $this->statusCode($status_code);
      $data['message']       = "";
      
      return $data;
    }

    /*=============================================== 
    CALLBACK EDIT INVITATION FIELDS: 
    http://DOMAIN-NAME/wp-json/pie/v1/edit-invitation 
    =================================================*/
    function pie_edit_invitation_callback($request_data)
    {
      $data                 = array();

      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];
      
      //GET AUTH KEY
      $auth_key_nonce       = $this->authKey($auth_key);
      
      $status_code          = ( count( $auth_key_nonce ) > 0 );
      
      if($status_code){
        $req_data             = $request_data->get_params();
        $code_id              = $req_data['code_id'];
        $code_name            = $req_data['code_name'];
        $code_usage           = $req_data['code_usage'];
        $expiry_date          = $req_data['expiry_date'];
        
        if(!$this->piereg_pro_is_activate){
          $expiry_date = "0000-00-00";           
        }
        
        if($this->editInvitationCode($code_id, $code_name, $code_usage, $expiry_date)){
          $data["message"] = "Data updated";
          $data['status_code']          = 200;
        }else{
          $data["message"] = "Data already exits";
          $data['status_code']          = 409;
        }

      }else{
          $data["message"] = "Authentication Error";
          $data['status_code']          = 401;
      }

      return $data;
      
    }


    /*=============================================== 
    CALLBACK INVITATION CODE: 
    http://DOMAIN-NAME/wp-json/pie/v1/invitation 
    =================================================*/
    function pie_invitation_callback($request_data)
    {
      $data   = array();

      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];
      
      //GET AUTH KEY
      $auth_key_nonce   = $this->authKey($auth_key);
      
      $status_code      = ( count( $auth_key_nonce ) > 0 );
      
      $data['status_code']   = $this->statusCode($status_code);
      
      if($status_code){
        global $wpdb;
        $req_data             = $request_data->get_params();
        $page                 = isset($req_data['paged']) && $req_data['paged'] != '' ? $req_data['paged'] : 1 ;
        
        $list_invitation  = $this->getInvitationCode($page);
        $is_empty         = ( count( $list_invitation ) > 0 );
        if(!empty($list_invitation)){        
          foreach ($list_invitation as $key => $value) {
            $date               = $value->expiry_date;
            
            if($this->piereg_pro_is_activate && $date != "0000-00-00"){
              $expiry_date        = date( "M d, Y", strtotime( $date ) );
            }else{
              $expiry_date = "0000-00-00";
            }
  
              
              $prefix     = $wpdb->prefix."pieregister_";
              $emailtable = $prefix."invite_code_emails";
              $results    = $wpdb->get_results("SELECT `*` FROM ".$emailtable." WHERE code_id=".$value->id);
              $email_sent = array();
              
              $total_emails_sent = $wpdb->num_rows;
  
              $data['data'][] = array(
                  "code_id"             => $value->id,
                  "code_name"           => $value->name,
                  "total_code_usage"    => $value->code_usage,
                  "code_used_count"     => $list_invitation[ $key ]->count,
                  "total_email_sent"    => $total_emails_sent,
                  "expiry_date"         => $expiry_date,
                  "expiry_timestamp"    => strtotime($date),
                  "status"              => $value->status,
              );
          }
        }

        if($is_empty){
          if(!empty($page) && !count( $list_invitation ) > 0){
            $data['status_code']  = 404;
            $data['message']      = "No More User Found";
          }
        }else{
          $data['status_code']  = 404;
          $data['data']         = NULL;
          $data['message']      = "No data found";
          return $data;
        }
      }else{
        $data['data']           = NULL;
        $data['message']        = "Authentication Error";
        return $data;
      }
      $data['message']        = "Success";
      return $data;
      
    }

    /*=============================================== 
    CALLBACK GENERATE INVITATION CODE: 
    http://DOMAIN-NAME/wp-json/pie/v1/generate-invitation-manual 
    =================================================*/
    function pie_gene_invitation_callback($request_data)
    {
      $data_array            = array();
      
      $getHeaders = apache_request_headers();
      $auth_key   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce   = $this->authKey($auth_key);

      $status_code      = count( $auth_key_nonce ) > 0;

        if($status_code){
          $req_data             = $request_data->get_json_params();
          $insert_codes         = $req_data['insert_codes'];
          $usage                = $req_data['usage_count'];
          $expiry               = "";

          if($this->piereg_pro_is_activate){
            $expiry = $req_data['expiry_date'];
          }else{
            $expiry = "0000-00-00";
          }

          if(!empty($insert_codes)){
            $count_code       = count($insert_codes);
            $codeadded        = false;
            $count_added_code = 0;
          
            foreach($insert_codes as $key => $code_name){
             
              if( $this->insertCode($code_name, $usage, $expiry) )
              {
                $count_added_code++;
                $codeadded = true;
              }
            }
  
          }
          $already_exist = $count_code - $count_added_code;

            if(!$codeadded && $count_code != $count_added_code){
              $data_array['message']              = $count_code. " invitation Code(s) already exists.";
              $data_array['status_code']          = 409;
              return $data_array;
            }elseif($codeadded){
              $data_array['status_code']          = 200;
              $data_array['message']              = "Invitation Code(s) added successfully";

              if($count_code != $count_added_code){
                $count_not_added_code = $count_code - $count_added_code - $count_special_char;
                $data_array['message'] = $count_added_code." invitation Code(s) added successfully.";
                if($count_not_added_code != 0){
                  $data_array['status_code']          = 999;
                  $data_array['message2'] = $count_not_added_code. " invitation Code(s) already exists";
                }
              }else{            
                $data_array['message'] = $count_added_code. " invitation Code(s) added successfully";
              }




              return $data_array;
            }else{
              $data_array['status_code']          = 404;
              $data_array['message']              = "Data not found"; 
              return $data_array;
            }
        }else{
              $data_array['status_code']          = 401;
              $data_array['message']              = "Authentication Error"; 
              return $data_array;
        }
      

      
    }

    /*=============================================== 
    CALLBACK GENERATE INVITATION CODE: 
    http://DOMAIN-NAME/wp-json/pie/v1/generate-invitation-auto 
    =================================================*/
    function pie_auto_gene_invitation_callback($request_data)
    {
      $getHeaders = apache_request_headers();
      $auth_key   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce   = $this->authKey($auth_key);
      
      $status_code      = count( $auth_key_nonce ) > 0;

      $data['status_code']          = $this->statusCode($status_code);

      if($status_code){
        $req_data             = $request_data->get_json_params();
        $data_array           = array();
        $code_prefix          = $req_data['code_prefix'];
        $no_code              = $req_data['no_code'];
        $usage                = $req_data['usage_count'];
        $expiry               = "";
        
        if($this->piereg_pro_is_activate){
          $expiry = $req_data['expiry_date'];
        }else{
          $expiry = "0000-00-00";
        }

        if(!empty($code_prefix) && !empty($no_code)){
         
            for($i = 1; $i <= $no_code; $i++){
              $invite_random = '0123456789abcdefghijklmnopqrstuvwxyz';
              $invite_code = $code_prefix . '_' . substr(str_shuffle($invite_random), 0, 4);
              $this->insertCode($invite_code, $usage, $expiry);
            }
         
          $data_array['status_code']          = 200;
          $data_array['message']              = $no_code. " Invitation Code(s) added successfully";
        }
        else{
          $data_array['status_code']          = 404;
          $data_array['message']              = "Data not found";
        }
        return $data_array;
        
      }else{
        $data_array['status_code']          = 401;
        $data_array['message']              = "Authentication Error"; 
        return $data_array;
      }
       
    }
    /*=============================================== 
    CALLBACK INVITATION CODE: 
    http://DOMAIN-NAME/wp-json/pie/v1/act-invitation 
    =================================================*/
    function pie_act_invitation_callback($request_data)
    {
      $getHeaders     = apache_request_headers();
      $auth_key       = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce = $this->authKey($auth_key); 
      
      $status_code                  = (count($auth_key_nonce) > 0);
      
      $data['status_code']          = $this->statusCode($status_code);
      $data['message']              = "";

      if($status_code){
        global $piereg_api_manager;
        $data                         = array();
        $req_data                     = $request_data->get_json_params();
        $bulk_option                  = $req_data['action'];
        $req_status_code_id           = $req_data['status_code_id'];
        $array_ids 		                = implode(',', $req_status_code_id);

        $is_empty                     = (!empty($bulk_option) && count($req_status_code_id) > 0);

        if($is_empty){
          if($bulk_option == "delete")
          {
            $piereg_api_manager->delete_invitation_codes($array_ids);
            $data['message']          = "Code Deleted";
          }
          else if($bulk_option == "active"){
            $piereg_api_manager->active_or_unactive_invitation_codes($array_ids,"1");
            $data['message']          = "Code Activated";
          }
          else if($bulk_option == "unactive")
          {
            $piereg_api_manager->active_or_unactive_invitation_codes($array_ids,"0");
            $data['message']          = "Code Deactivated";
          }

        }else{
          $data['status_code']          = 404;
          $data['message']              = "Not found";
        }  
        
        
      }

      return $data;
      

    }

    /*============================================== 
    CALLBACK USER DATA ROUTE: 
    http://DOMAIN-NAME/wp-json/pie/v1/user-data
    ================================================*/
    function pie_user_data_callback()
    {

      $getHeaders     = apache_request_headers();
      $auth_key       = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce = $this->authKey($auth_key);      
      
      $data = array();

      $status_code    =   ( count( $auth_key_nonce ) > 0 );
      $data['status_code']          = $this->statusCode($status_code);

      if(!$status_code){
        $data['data']     = null;
        $data['message']  = "Authentication Error";
      }else{
        $roles_all      = wp_roles();
        $roles          = $roles_all->roles;

        $req_type = array(
          "email_verify"        => "E-mail Verification",
          "admin_verify"        => "Admin Verification",
          "admin_email_verify"  => "E-mail & Admin Verification",
          "payment_verify"      => "Payment Verification",
        );
        foreach ($req_type as $key => $reg_type) {
          $data['data']['reg_type'][] =  array(
              "key"   => $key,
              "value" => $reg_type
          );  
        }
        foreach ($roles as $key => $role) {
          $data['data']['roles'][] =  array(
              "key"   => $key,
              "value" => $role['name']
          );  
        }
        $data['message']  = "Success";
      }
      return $data;


    }
    /*============================================== 
    CALLBACK UNVERIFY ROUTE: 
    http://DOMAIN-NAME/wp-json/pie/v1/unverify
    ================================================*/
    function pie_unverify_callback($request_data)
    {
      $getHeaders = apache_request_headers();
      $auth_key   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce = $this->authKey($auth_key);      
      
      $data = array();
      $status_code         =   ( count( $auth_key_nonce ) > 0 );
      $data['status_code'] = $this->statusCode($status_code);
        
        if($status_code){
          global $wpdb;

          $req_data             = $request_data->get_params();

          $_paged               = $req_data['paged'];
          $_search              = $req_data['search'];
          $filter_reg_type      = isset($req_data['reg_type']) && $req_data['reg_type'] != '' ? $req_data['reg_type'] : '' ;
          $filter_role          = isset($req_data['role']) && $req_data['role'] != '' ? $req_data['role'] : '' ;

          //GET UN-VERIFY USERS
          $number = 5; //max display per page
          $paged = ($_paged) ? $_paged : 1; //current number of page
          $offset = ($paged - 1) * $number; //page offset

          $get_unverify_users   = get_users(array(
                                  'meta_key'    => 'active',
                                  'meta_value'  => 0,
                                  'role'        => $filter_role,
                                  'offset'      => $offset,
                                  'number'      => $number,
                                  'search'      => '*'.$_search.'*',
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

          foreach($get_unverify_users as $key => $user){

            $user_id          = $user->ID;
            $user_name        = $user->data->user_login;
            $user_email       = $user->data->user_email;
            $user_role        = array_shift($user->roles);
            $reg_type 	      = get_user_meta($user_id, 'register_type'); 
  
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
                $type =  ucwords($reg_type);
              break;
            }
            
            $udata            = get_userdata( $user_id );
            $registered       = $udata->user_registered;
            $date             = date( "d M Y", strtotime( $registered ) );

            $data['data'][]  =   array(
                      'user_id'               => $user_id,
                      'user_name'             => $user_name,
                      'user_email'            => $user_email,
                      'registration_type'     => $type,
                      'user_role'             => $user_role,
                      'date'                  => $date
                );
          }
          $is_empty       =   ( count($get_unverify_users) > 0 );

            if( !empty($_paged) && empty($get_unverify_users) || empty($_paged) ) {
                $data['status_code']  = 404;
                $data['message']      = "No More User Found";
                $data['data']         = null;

                if( !empty($_search) && empty($get_unverify_users) || 
                    !empty($filter_reg_type) || !empty($filter_role)) {
                        
                          $data['status_code']  = 201;
                          $data['message']      = "Search Result Not Found";
                          $data['data']         = null; 
                }
            }
            
         
        }else{
          $data['message']              = "Authentication Error";
          $data['data']                 = null;
        }

        return $data;
  
    }


     /*=============================================== 
    CALLBACK INIVITATION EMAIL TEMPLATE SET: 
    http://DOMAIN-NAME/wp-json/pie/v1/invitation-email-template-set 
    =================================================*/
    function pie_invitation_email_template_set_callback($request_data)
    {
      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce       = $this->authKey($auth_key);

      $data                 = array();
      $status_code                  = count( $auth_key_nonce ) > 0;
      $data['status_code']  = $this->statusCode($status_code);
      
      if($status_code){
        $req_data             = $request_data->get_json_params();
        $from                 = $req_data['from'];
        $from_email           = $req_data['from_email'];
        $subject              = $req_data['subject'];
        $email_body           = $req_data['email_body'];
        
        $piereg 	            = get_option(OPTION_PIE_REGISTER);
        
        //DATA SETTER
        $piereg['pie_name_from']      =  $from;
        $piereg['pie_email_from']     =  $from_email;
        $piereg['pie_email_subject']  =  $subject;
        $piereg['pie_email_content']  =  $email_body;
        
        if(empty($from)){
          $data['message']      = "Please Enter The field (FROM)"; 
          $data['status_code']  = 404;
        }elseif(empty($from_email)){
          $data['message']      = "Please Enter The field (EMAIL)"; 
          $data['status_code']  = 404;
        }elseif(empty($subject)){
          $data['message']      = "Please Enter The field (SUBJECT)"; 
          $data['status_code']  = 404;
        }else{
          update_option(OPTION_PIE_REGISTER,$piereg);
          $data['message']  = "Success";  
        }
        
      }else{
        $data['message']  = "Authentication Error";  
      }
      return $data;



    }

    /*=============================================== 
    CALLBACK INIVITATION EMIAL TEMPLATE GET: 
    http://DOMAIN-NAME/wp-json/pie/v1/invitation-email-template-get 
    =================================================*/
    function pie_invitation_email_template_get_callback($request_data)
    {
      $getHeaders                 = apache_request_headers();
      $auth_key                   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce             = $this->authKey($auth_key);

      $data                       = array();
      
      $status_code                = count( $auth_key_nonce ) > 0;

      $data['status_code']        = $this->statusCode($status_code);
      
      if($status_code){
        $req_data                   = $request_data->get_json_params();
        $piereg 	                  = get_option(OPTION_PIE_REGISTER);

        //DATA GETTER
        $from                       = $piereg['pie_name_from'];
        $from_email                 = $piereg['pie_email_from'];
        $subject                    = $piereg['pie_email_subject'];
        $email_body                 = $piereg['pie_email_content'];
        
        $data['data']               = array(
          "from"        => $from,
          "from_email"  => $from_email,
          "subject"     => $subject,
          "email_body"  => $email_body,
        );

        $data['message']            = "Success";  
      }else{
        $data['message']            = "Authentication Error";  
      }
      return $data;



    }

    /*=============================================== 
    CALLBACK IS PRO USER: 
    http://DOMAIN-NAME/wp-json/pie/v1/is-pro-user
    =================================================*/
    function pie_is_pro_user_callback($request_data)
    {
      $data = array();

      $getHeaders                 = apache_request_headers();
      $auth_key                   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce             = $this->authKey($auth_key);
      $status_code                = count( $auth_key_nonce ) > 0;
      $data['status_code']        = $this->statusCode($status_code);
      
      if(!$status_code){
        $data['data']             = null;
        $data['message']          = "Authantication Error";
      }else{
        $data['data']              = $this->piereg_pro_is_activate ? true : false;
        $data['message']          = "Success";

      }


      return $data;

    }
    /*=============================================== 
    CALLBACK ENABLE EMAIL: 
    http://DOMAIN-NAME/wp-json/pie/v1/enable-email
    =================================================*/
    function pie_enable_email_callback($request_data)
    {
      $data                       = array();
      $getHeaders                 = apache_request_headers();
      $auth_key                   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce             = $this->authKey($auth_key);
      $status_code                = count( $auth_key_nonce ) > 0;
      
      $data['status_code']        = $this->statusCode($status_code);
      
      if(!$auth_key_nonce){
        $data['data']    = "";
        $data['message'] = "Authantication Error";
      }else{
        $piereg 	                  = get_option(OPTION_PIE_REGISTER);
        $data['data']               = $piereg['enable_invitation_codes'];
        $data['message']            = "success";
      }
      return $data;
    }
    /*=============================================== 
    CALLBACK INVITE USERS EMAIL: 
    http://DOMAIN-NAME/wp-json/pie/v1/invite-user-email
    =================================================*/
    function pie_invite_user_email_callback($request_data)
    {
      $getHeaders           = apache_request_headers();
      $auth_key             = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce             = $this->authKey($auth_key);
      $status_code                = count( $auth_key_nonce ) > 0;
      $data['status_code']        = $this->statusCode($status_code);
      $is_auth                    = false;
      
      if($status_code){
        $is_auth          = true;  
      }else{
        $data['message']  = "Authantication Error";
      }

      if($is_auth){
        $user_email           = $req_data['user_email'];

        if(!empty($user_email)){
          $piereg 	            = get_option(OPTION_PIE_REGISTER);
          $req_data             = $request_data->get_json_params();
          $page_id              = $req_data['page_id'];
          $invite_code          = $req_data['invite_code'];
           //Headers
           $subject 	  = $piereg['pie_email_subject'];
           $subject 	= str_replace('%blogname%',get_bloginfo('name'),$subject);
           $from_name	  = $piereg['pie_name_from'];
           $from_email	= $piereg['pie_email_from'];
           
           $headers    = 'MIME-Version: 1.0' . "\r\n";
           $headers    .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
           $headers    .= "From: ".$from_name." <".$from_email."> \r\n";     
           
           foreach($user_email as $email){
             
             if( get_user_by('email', $email) ){
              $data['message2'][]    = $email;
              $data['status_code'] = 409;
               continue;
             }
             
             $getURL  = get_permalink($page_id);
             $key = base64_encode($email."|".$invite_code);
             $pageURL 	= add_query_arg(array('action'=>'pie-ic','key'=>urlencode($key)),$getURL);
  
             $emailBody 	= str_replace('%invitation_link%',$pageURL,$piereg['pie_email_content']);
             $emailBody 	= str_replace('%blogname%',get_bloginfo('name'),$emailBody);
             
             if(wp_mail($email, $subject, nl2br($emailBody), $headers)){
             //if(1 == 1){
              // email count
              global $wpdb;
              $prefix = $wpdb->prefix."pieregister_";
              $invite_code_emails_table_name = $prefix."invite_code_emails";
              $invite_code_table_name        = $prefix."code";
              //var_dump($invite_code);
              $code_id = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM ".$invite_code_table_name." WHERE name=%s", esc_sql($invite_code) ) );
              $code_id = (int)$code_id[0]->id;
  
              $add_address = $wpdb->query($wpdb->prepare("INSERT INTO ".$invite_code_emails_table_name." (`code_id`,`email_address`)VALUES(%s,%s)", $code_id, $email));
  
               $data['message'][] = $email;							
             } else {
               $data['message'] = "Failed to send invitation code to pie-register";						
             }
           }
          }
          elseif(empty($user_email)){
            $data['message']        = "Data not found";
            $data['status_code']    = 404;
            $data['data']           = "";
          }
      }

      return $data;
     
    }
    /*=============================================== 
    CALLBACK INVITE USERS: 
    http://DOMAIN-NAME/wp-json/pie/v1/registration-page-list
    =================================================*/
    function pie_registration_page_list_callback($request_data)
    {
      $data  = array();
      $getHeaders = apache_request_headers();
      $auth_key   = $getHeaders['auth_key'];

      //GET AUTH KEY
      $auth_key_nonce   = $this->authKey($auth_key);

      
      $data['status_code']   = $this->statusCode($auth_key_nonce);
      
      if($auth_key_nonce){
        global $wpdb;
        $pages = get_pages(array( 'numberposts' => -1));
        if(!empty($pages)){
          foreach ($pages as $key => $page) { 
            $data['data'][] = array(
                "id"        => $page->ID,
                "page_name" => $page->post_title
            );
          }
        }
        if(empty($pages)){
          $data['status_code']   = 404;
          $data['data']          = "";
          $data['message']       = "Data not found";
        }else{
          $data['message']       = "Success";
        }
      }else{
        $data['message']       = "Authentication Error";
        $data['data']          = "";
      }
      
      return $data;
    }

    /*
      ======== *********************** ========
      ======== GENERAL FUNCTIONS START ========
      ======== *********************** ========
    */

    /*============================= 
    GET USER SIGN IN DETAILS 
    ===============================*/
    function getSignInDetails($user){
      
      $permitted_chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      
      //Sucess results
      $user_id          =  isset($user->data->ID) && $user->data->ID != '' ? $user->data->ID : null;
      $user_login       =  isset($user->data->user_login) && $user->data->user_login != '' ? $user->data->user_login : null ;
      $user_email       =  isset($user->data->user_email) && $user->data->user_email != '' ? $user->data->user_email : null ;
      
      //$error_messages   =  $this->login_error_message($user);

      $status_code      =  isset($user->data) && $user->data != '' ? $this->statusCode($user->data) : 401;    
      $error_code       =  isset($user->data) && $user->data != '' ? "success" : "User credentials are invalid.";

      $generator          =  $this->generate_string($permitted_chars, 20);
      $auth_key_nonce     =  !empty($user_id) ? 'pie_auth_'.$generator : null;

      $is_pro_active      =  ($this->piereg_pro_is_activate && !empty($auth_key_nonce)) ? $is_pro_active = true : $is_pro_active = false; 
      
      $user = array(
          "data" => array(
              'id'            => $user_id,
              'user_login'    => $user_login,
              'user_email'    => $user_email,
              'pro_user'      => $is_pro_active,
              'auth_key'      => $auth_key_nonce
          ),
          'status_code' => $status_code,
          'message'     => $error_code,
          
      );
      
      update_user_meta($user_id, 'auth_key_nonce', $auth_key_nonce);
     
      return $user;
      
    }



    
    
     /*============================= 
     GET ERROR MESSAGE OF LOGIN 
     ===============================*/
    function login_error_message($user){
      
      $error_message = array();
      if(!empty($user->errors)){
        foreach ($user->errors as $key => $value) {
          $error_message = $value[0];
        }
      }
      return $error_message;

    }
    
     /*============================= 
     STATUS CODE 
     ===============================*/

     function statusCode($data)
     {
        $status_code  = ($data === NULL || empty($data)) ? 401 : 200;
        return $status_code;
     }
     
     /*============================= 
     AUTH KEY 
     ===============================*/
     function authKey($auth_key)
     {
       global $wpdb;

       $results = $wpdb->get_results( 
          $wpdb->prepare("

                    SELECT user_id from {$wpdb->prefix}usermeta where meta_key = 'auth_key_nonce' AND meta_value = %s  
          ", $auth_key) 
        );

       return $results;
     }

     
     /*============================= 
     APP LOGIN AUTH 
     ===============================*/
     function piereg_authentication($log,$pwd){
      
      global $wpdb;
    
      $user_data = wp_authenticate($log,$pwd);
      $is_error = ( (is_wp_error($user_data))? true : $user_data );
      
      if ( is_wp_error($user_data) ) { 
        $user_id = false;
      } else {
        $user_id = $user_data->ID;
      }
      
      return $user_id;
    }

    /*============================= 
     GET USER ROLES BY USER ID
     ===============================*/
    function pie_get_roles_by_user_id( $user_id ) {
      $user = get_userdata( $user_id );
      return empty( $user ) ? array() : $user->roles;
    }

    /*===================================== 
     CHECK IF USER IS IN THE SPECIFIC ROLE
     ======================================*/    
    function pie_is_user_in_role( $user_id, $role  ) {
        return in_array( $role, $this->pie_get_roles_by_user_id( $user_id ) );
    }

    /*============================= 
     GET INVITATION CODE 
     ===============================*/
    private function getInvitationCode($_paged)
    {
        global $wpdb;

        $prefix = $wpdb->prefix . "pieregister_";
        $codetable  = $prefix."code";
        $order_by = "`id` DESC";
        
        $items_per_page = 5;
        $page = isset( $_paged ) ? abs( (int) $_paged ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;

        
        //$list_invitation_code       = $wpdb->get_results( "SELECT * FROM $codetable" );
        
        $latestposts = $wpdb->get_results("SELECT * FROM $codetable ORDER BY $order_by LIMIT ${offset}, ${items_per_page}" );
        
        return $latestposts;
        
    }

    /*============================= 
     EDIT INVITATION CODE 
     ===============================*/
    function editInvitationCode($code_id, $code_name,$code_usage,$expiry_date)
    {
        global $wpdb;
        
        $prefix               = $wpdb->prefix."pieregister_";
        $codetable            = $prefix."code";
        $name_lower           = strtolower($code_name);
        $name_upper           = strtoupper($code_name);
        
        $data     = [ 'name' => $code_name, 'code_usage' => $code_usage, 'expiry_date' => $expiry_date  ]; 
        $where    = [ 'id' => $code_id ];

        $codes    = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $codetable WHERE id != %s AND (BINARY `name`=%s OR `name`=%s)", $code_id, $name_lower, $name_upper) );

        $counts   = count($codes);
        
        if($counts > 0){
          return false;
        }else{
            $wpdb->update( $codetable, $data, $where );
        }
      
        return true;
    }

    /*============================= 
     INSERT INVITATION CODE 
     ===============================*/

     function insertCode($insert_codes,$usage,$expiry){
      global $wpdb;

      $prefix               = $wpdb->prefix . "pieregister_";
      $codetable            = $prefix."code";
      $date                 = date_i18n("Y-m-d");
      
      $expiry               = isset($expiry) && $expiry != '' ? $expiry : "0000-00-00";
      $name_lower           = strtolower($insert_codes);
      $name_upper           = strtoupper($insert_codes);

      $codes = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $codetable WHERE BINARY `name`=%s OR `name`=%s" , $name_lower, $name_upper) );
      $counts = count($codes);

      $data   = array('created' => $date, 'modified' => $date, 'name' => $insert_codes, 'count' => $counts, 'status' => "1", "code_usage" => $usage, 'expiry_date' => $expiry);
      $format = array('%s','%s','%s','%s','%s','%s','%s');
      
      if($counts > 0){
        return false;
      }else{
        $wpdb->insert($codetable,$data,$format);
      }
      return true;

     }

     /*============================= 
     REGISTER USER REPORT TO FIREBASE 
     ===============================*/

     function registerUser($user){

        global $wpdb;
        $firebase                 = new Pie_App_Firebase();
        $push                     = new Pie_App_Push();
        
        $response                 = "";

        $regIds = $wpdb->get_results( 
          "SELECT meta_value FROM {$wpdb->prefix}usermeta where meta_key = 'auth_api_key'" 
        );

        foreach ($regIds as $key => $regId) {
          $res                      = array();
          $res['data']['message']   = $user->data->user_login. " New registration received.";
          $response                 = $firebase->send($regId->meta_value, $res);
        
        }
        
    }

    function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }

} //class
