<?php
/* 
 #--------------- Ajax Search in ticketes
 */
add_action('wp_ajax_ajax_search_in_ticketes_wpyar', 'ajax_search_in_ticketes_wpyar'); 
add_action('wp_ajax_nopriv_ajax_search_in_ticketes_wpyar', 'ajax_search_in_ticketes_wpyar');  
if (!function_exists('func_ajax_search_in_ticketes_wpyar')) {
    function ajax_search_in_ticketes_wpyar()
    {
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET . 'ajax_search_in_ticketes_wpyar.php';
        func_ajax_search_in_ticketes_wpyar(sanitize_text_field($_POST['value']));
        exit();   
    }
} 

/*
 #--------------- Ajax Send type user
 */
add_action('wp_ajax_send_type_role_user', 'send_type_role_user');  
add_action('wp_ajax_nopriv_send_type_role_user', 'send_type_role_user'); 
if (!function_exists('send_type_role_user')) {
    function send_type_role_user()
    {
        if (sanitize_text_field($_POST['selectedtypsender']) == 1) {
            global $wpdb;
            $get_users = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users");
            foreach ($get_users as $user) {
                 echo '<option  data-mail="'.$user->user_email.'"  value="' . sanitize_text_field($user->ID) . '">' . sanitize_text_field($user->display_name) . '</option>';
            }
        } else if (sanitize_text_field($_POST['selectedtypsender']) == 2) {
            $get_users = get_users(['role__in' => ['user_support']]);  
            foreach ($get_users as $user) {
                echo '<option value="' . sanitize_text_field($user->ID) . '">' . sanitize_text_field($user->display_name) . '</option>';
            }
        }
        exit();
    }
} 

/*
 #--------------- Ajax get_product
 */
add_action('wp_ajax_get_product__user', 'get_product__user');
add_action('wp_ajax_nopriv_get_product__user', 'get_product__user');
if (!function_exists('get_product__user')) {
    function get_product__user()
    {
        $customer_orders = get_posts(array(
            'meta_key' => '_customer_user',
            'meta_value' => sanitize_text_field($_POST['userId']),
            'post_type' => wc_get_order_types(),//-----پست های از نوع خرید
            'post_status' => array_keys(wc_get_is_paid_statuses()),////------ وضعیت: خریداری
        ));
        if (!$customer_orders) return;
         foreach ($customer_orders as $customer_order) {
            $order = wc_get_order($customer_order->ID);
            $items = $order->get_items();
            echo '<option value="0">'.__('Select Prodcut', 'nirweb-support').'</option>';
            foreach ($items as $item) {
                $product_id = $item->get_product_id();
                $product_name = $item->get_name();
                echo '<option value="' . $product_id . '">' . $product_name . '</option>';
            }
        }
        exit();
    }
}

/*
 #--------------- Ajax load_send_new_ticket
 */
add_action('wp_ajax_load_page_new_tiket', 'load_page_new_tiket');
add_action('wp_ajax_nopriv_load_page_new_tiket', 'load_page_new_tiket');
if (!function_exists('load_page_new_tiket')) {
    function load_page_new_tiket(){
    include_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'new_ticket.php';
    exit();
}
}
/*
 #--------------- Ajax send_new_ticket_admin
 */
add_action('wp_ajax_send_new_ticket', 'send_new_ticket');
add_action('wp_ajax_nopriv_send_new_ticket', 'send_new_ticket');
if (!function_exists('send_new_ticket')) {
    function send_new_ticket(){
    include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_send_ticket.php';
    nirweb_ticket_send_ticket();
    }
}

 /*
 #--------------- Ajax answer_ticket_admin
 */
add_action('wp_ajax_answerd_ticket', 'answerd_ticket');
add_action('wp_ajax_nopriv_answerd_ticket', 'answerd_ticket');
if (!function_exists('answerd_ticket')) {
    function answerd_ticket()
        {
            include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_answerd_ticket.php';
            nirweb_ticket_answer_ticket(sanitize_text_field($_POST['id_form']));
                func_list_answer_ajax(sanitize_text_field($_POST['id_form']));
        exit();
        }
}

/*
 #--------------- Ajax delete_tickets_admin
 */
add_action('wp_ajax_delete_tickets_admin', 'delete_tickets_admin');
add_action('wp_ajax_nopriv_delete_tickets_admin', 'delete_tickets_admin');
if (!function_exists('delete_tickets_admin')) {
    function delete_tickets_admin()
    {
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_list_tickets.php';
        nirweb_ticket_delete_ticket(sanitize_post($_POST['check']));
        exit();
    }
}

/*
 #--------------- Ajax Add Department
 */
add_action('wp_ajax_add_department_wpyt', 'add_department_wpyt');
add_action('wp_ajax_nopriv_add_department_wpyt', 'add_department_wpyt');
if (!function_exists('add_department_wpyt')) {
    function add_department_wpyt(){
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_department.php';
        nirweb_ticket_ticket_add_department();
        get_list_department_ajax();
        exit();
    }
}

/*
 #--------------- Ajax delete department
 */
add_action('wp_ajax_delete_department', 'delete_department');
add_action('wp_ajax_nopriv_delete_department', 'delete_department');
if (!function_exists('delete_department')) {
    function delete_department()
    {
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_department.php';
        nirweb_ticket_delete_department();
         exit();
    }
}

/*
 #--------------- Ajax edit department
 */
add_action('wp_ajax_edite_department', 'edite_department');
add_action('wp_ajax_nopriv_edite_department', 'edite_department');
if (!function_exists('edite_department')) {
    function edite_department()
        {
            include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_department.php';
            nirweb_ticket_edite_department(sanitize_post($_POST));
            get_list_department_ajax();
            exit();
        }
}

/*
 #--------------- Ajax Add Question
 */
add_action('wp_ajax_add_question_faq', 'add_question_faq');
add_action('wp_ajax_nopriv_add_question_faq', 'add_question_faq');
if (!function_exists('add_question_faq')) {
    function add_question_faq(){
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_FAQ.php';
        nirweb_ticket_add_question_faq();
        nirweb_ticket_ajax_get_all_faq();
        exit();
    }
}

/*
 #--------------- Ajax Delete Question
 */
add_action('wp_ajax_delete_faq', 'delete_faq');
add_action('wp_ajax_nopriv_delete_faq', 'delete_faq');
if (!function_exists('delete_faq')) {
    function delete_faq()
    {
        include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_FAQ.php';
        nirweb_ticket_delete_faq();
         exit();
    }
}

/*
 #--------------- Ajax Fiels Upload By user
 */
add_action('wp_ajax_ticket_wpyar_file_user_delete', 'ticket_wpyar_file_user_delete');
add_action('wp_ajax_nopriv_ticket_wpyar_file_user_delete', 'ticket_wpyar_file_user_delete');
if (!function_exists('ticket_wpyar_file_user_delete')) {
    function ticket_wpyar_file_user_delete()
{
    include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_upload_file.php';    fun_ticket_wpyar_file_user_delete();
     exit();
}
}

/*
``````````````````````````````````   USER ```````````````````````````````````````````````
*/
/* 
 #--------------- Ajax Search in ticketes
 */
add_action('wp_ajax_nirweb_ticket_user_search', 'nirweb_ticket_user_search'); 
add_action('wp_ajax_nopriv_nirweb_ticket_user_search', 'nirweb_ticket_user_search');  
if (!function_exists('nirweb_ticket_user_search')) {
    function nirweb_ticket_user_search()
            {
                include_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET.'ajax_nirweb_ticket_user_search.php';
            func_ajax_nirweb_ticket_user_search(sanitize_text_field($_POST['value']));
            exit();
            }
} 

  /*
 #--------------- Ajax send  ticketes
 */
add_action('wp_ajax_user_send_tiket', 'user_send_tiket'); 
add_action('wp_ajax_nopriv_user_send_tiket', 'user_send_tiket');   
if (!function_exists('user_send_tiket')) {
    function user_send_tiket()
            {
                include_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET.'ajax_user_send_tiket.php';
                func_user_send_tiket();
                exit();
            }
}

/*
 #--------------- Ajax send Answer
 */
add_action('wp_ajax_user_answer_ticket','user_answer_ticket');
add_action('wp_ajax_nopriv_user_answer_ticket','user_answer_ticket');
if (!function_exists('user_answer_ticket')) {
    function user_answer_ticket(){
        include_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET.'ajax_user_send_answer.php';
        user_wpyar_answer_ticket();
        func_list_answer_ajax_user();
        exit();
    }
}

/*
 #--------------- Ajax Filtter Ststus
 */
add_action('wp_ajax_filtter_ticket_status','filtter_ticket_status');
add_action('wp_ajax_nopriv_filtter_ticket_status','filtter_ticket_status');
if (!function_exists('filtter_ticket_status')) {
    function filtter_ticket_status(){
        include_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET.'filter_ajax_ticket.php';
        filter_ajax_ticket_func();
         exit();
    }
}

