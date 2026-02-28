<?php
if (!defined('ABSPATH')) exit; //No direct access allowed
require_once NIRWEB_SUPPORT_TICKET . 'core/create_db.php';
require_once NIRWEB_SUPPORT_TICKET . 'option/framework.php';
require_once NIRWEB_SUPPORT_TICKET . 'option/options/options.php';
require_once NIRWEB_SUPPORT_INC_ADMIN_TICKET . 'functions/scripts.php';
require_once NIRWEB_SUPPORT_INC_ADMIN_TICKET . 'functions/ajax.php';
require_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET . 'func_shourt_code.php';
//---------- Styles And JS Files --------------------
add_action('wp_enqueue_scripts', function () {
    $size = wpyar_ticket['size_of_file_wpyartik'];

    wp_enqueue_style('font5-css-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/all.min.css');
    if (is_rtl()) {
        wp_enqueue_style('user-css-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/user-rtl.css');
    } else {
        wp_enqueue_style('user-css-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/user.css');
    }
    wp_enqueue_style('select.tw.css', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/select.tw.css');
    wp_enqueue_script('main_js_nirweb_ticket',NIRWEB_SUPPORT_URL_TICKET.'assets/js/jquery-3.4.1.min.js',['jquery']);
    wp_enqueue_script('select_2-js-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/select_2.js');
    wp_enqueue_script('sweetalert2-min-js', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/sweetalert2.min.js');
     wp_enqueue_script('user-js-file', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/user.js');
    wp_localize_script(
        'user-js-file',
        'wpyarticket',
        [
            'upload_url' => admin_url('async-upload.php'),
            'ajax_url'   => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('media-form'),
            'reset_form_title' => __('Are you sure you want to reset?', 'nirweb-support'),
            'reset_form_subtitle' => __('This will cause data loss.', 'nirweb-support'),
            'reset_form_success' => __('Form successfully reset.', 'nirweb-support'),
            'attach_file' => __('Attachment File', 'nirweb-support'),
            'recv_info' => __('Receiving information ...', 'nirweb-support'),
            'nes_field' =>  __('complete all stared sections Please', 'nirweb-support'),
            'max_size_file' => __("maximum Size Of File $size MB", 'nirweb-support'),
            'nvalid_file' => __("File is not valid.", 'nirweb-support'),
            'answerede' => __("Answerede", 'nirweb-support'),
            'necessary' => __("necessary", 'nirweb-support'),
            'normal' => __("normal", 'nirweb-support'),
            'low' => __("low", 'nirweb-support'),
            'new' => __("new", 'nirweb-support'),
            'inprogress' => __("inprogress", 'nirweb-support'),
            'answerede' => __("answerede", 'nirweb-support'),
            'closed' => __("closed", 'nirweb-support'),
        ]
    );
    wp_localize_script(
        'sweetalert2-min-js',
        'wpyarticketsw',
        [
            'ok' => __('Ok', 'nirweb-support'),
            'cancel' => __('Cancel', 'nirweb-support'),
        ]
    );
});
//~~~~~~~~~~~~START Create Menu~~~~~~~~~~
add_action('admin_menu',function ()
{
    require_once NIRWEB_SUPPORT_TICKET . 'inc/admin/functions/func_number_tab_ticktes.php';
    if (current_user_can('administrator')) {
        $scount = nirweb_ticket_count_new_ticket();
    } else {
        $scount = nirweb_ticket_count_new_ticket_posht(get_current_user_id());
    }
    $cont = $scount > 0 ? '<p class="number_ticket">' . nirweb_ticket_count_new_ticket() . '</p>' : '';
    add_menu_page(
        __('Tickets', 'nirweb-support'),
        __('Tickets', 'nirweb-support') . $cont,
        'upload_files',
        'nirweb_ticket_manage_tickets',
        'nirweb_ticket_manage_tickets_callback',
        'dashicons-tickets',
        8
    );
    add_submenu_page('nirweb_ticket_manage_tickets&tab=all_ticket', __('All tickets', 'nirweb-support'), __('All tickets', 'nirweb-support'), 'upload_files', 'nirweb_ticket_manage_tickets&tab=all_ticket', 'nirweb_ticket_manage_tickets_callback');
    add_submenu_page('nirweb_ticket_manage_tickets', __('Send ticket', 'nirweb-support'), __('Send ticket', 'nirweb-support'), 'upload_files', 'nirweb_ticket_send_ticket', 'nirweb_ticket_send_ticket_callback');
    add_submenu_page('nirweb_ticket_manage_tickets', __('Department', 'nirweb-support'), __('Department', 'nirweb-support'), 'manage_options', 'nirweb_ticket_department_ticket', 'nirweb_ticket_department_ticket_callback');
    add_submenu_page('nirweb_ticket_manage_tickets', __('Pre answer', 'nirweb-support'), __('Pre answer', 'nirweb-support'), 'manage_options', 'edit.php?post_type=pre_answer_wpyticket');
    add_submenu_page('nirweb_ticket_manage_tickets', __('FAQ', 'nirweb-support'), __('FAQ', 'nirweb-support'), 'manage_options', 'nirweb_ticket_FAQ_ticket', 'nirweb_ticket_FAQ_ticket_callback');

       
    //---------- Transfer Data Start ------------
        global $wpdb;
     $table_name = $wpdb->prefix . 'wp_yar_ticket';
    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        if ($wpdb->get_var($query) == $table_name) {
  add_submenu_page('nirweb_ticket_manage_tickets', __('Transfer Data', 'nirweb-support'), __('Transfer Data', 'nirweb-support'), 'manage_options', 'nirweb_ticket_tranfer_data', 'nirweb_ticket_tranfer_data_callback');
        } 
} );

function nirweb_ticket_tranfer_data_callback(){
if (isset( $_POST['trans_data_nirweb_ticket'] ) 
    &&  wp_verify_nonce( $_POST['trans_data_nirweb_ticket'], 'trans_data_nirweb_ticket' ) 
) {
   
 global $wpdb;  

      $table_name = $wpdb->prefix . 'nirweb_ticket_ticket_faq';
      $table_name2 = $wpdb->prefix . 'wp_yar_ticket_faq';
      $list_1 = $wpdb->get_results("SELECT * FROM $table_name2");
      foreach($list_1 as $row){
          $wpdb->insert($table_name,[
              'question'=>$row->question,
              'answer'=>$row->answer,
              'department_id'=>$row->department_id,
              ]);
          
      }
      $table_name = $wpdb->prefix . 'nirweb_ticket_ticket_user_upload';
      $table_name2 = $wpdb->prefix . 'wp_yar_ticket_user_upload';
      $list_1 = $wpdb->get_results("SELECT * FROM $table_name2");
      foreach($list_1 as $row){
          $wpdb->insert($table_name,[
              'url_file'=>$row->url_file,
              'user_id'=>$row->user_id,
              'file_id'=>$row->file_id,
              'time_upload'=>$row->time_upload,
              ]);
      }
      
      $table_name = $wpdb->prefix . 'nirweb_ticket_ticket_department';
      $table_name2 = $wpdb->prefix . 'wp_yar_ticket_department';
      $list_1 = $wpdb->get_results("SELECT * FROM $table_name2");
      foreach($list_1 as $row){
          $wpdb->insert($table_name,[
              'name'=>$row->name,
              'support_id'=>$row->support_id,
              ]);
      }    
      
      $table_name = $wpdb->prefix . 'nirweb_ticket_ticket_answered';
      $table_name2 = $wpdb->prefix . 'wp_yar_ticket_answered';
      $list_1 = $wpdb->get_results("SELECT * FROM $table_name2");
      foreach($list_1 as $row){
          $wpdb->insert($table_name,[
              'user_id'=>$row->user_id,
              'text'=>$row->text,
              'attach_url'=>$row->attach_url,
              'time_answer'=>$row->time_answer,
               'ticket_id'=>$row->ticket_id,
              ]);
      }   
         $table_name = $wpdb->prefix . 'nirweb_ticket_ticket';
      $table_name2 = $wpdb->prefix . 'wp_yar_ticket';
      $list_1 = $wpdb->get_results("SELECT * FROM $table_name2");
      foreach($list_1 as $row){
          $wpdb->insert($table_name,[
              'id_receiver'=>$row->id_receiver,
              'subject'=>$row->subject,
              'content'=>$row->content,
              'department'=>$row->department,
              'priority'=>$row->priority,
              'website'=>$row->website,
              'product'=>$row->product,
              'support_id'=>$row->support_id,
              'sender_id'=>$row->sender_id,
              'status'=>$row->status,
              'date_qustion'=>$row->date_qustion,
              'time_update'=>$row->time_update,
              'file_url'=>$row->file_url,
      
              ]);
      }   
       
       
   update_option( 'trans_ticket_nirweb', true );    
       
        
   
}

if (isset( $_POST['del_data_nirweb_ticket'] )  &&  wp_verify_nonce( $_POST['del_data_nirweb_ticket'], 'del_data_nirweb_ticket' ) ) {
  global $wpdb;
  
      $tb1 = $wpdb->prefix . 'wp_yar_ticket_faq';
      $tb2 = $wpdb->prefix . 'wp_yar_ticket_user_upload';
      $tb3 = $wpdb->prefix . 'wp_yar_ticket_department';
      $tb4 = $wpdb->prefix . 'wp_yar_ticket_priority';
      $tb5 = $wpdb->prefix . 'wp_yar_ticket_status';
      $tb6 = $wpdb->prefix . 'wp_yar_ticket_pre_answer';
      $tb7 = $wpdb->prefix . 'wp_yar_ticket_answered';
      $tb8 = $wpdb->prefix . 'wp_yar_ticket';
      
      
      $wpdb->query( "DROP TABLE  $tb1" );
      $wpdb->query( "DROP TABLE  $tb2" );
      $wpdb->query( "DROP TABLE  $tb3" );
      $wpdb->query( "DROP TABLE  $tb4" );
      $wpdb->query( "DROP TABLE  $tb5" );
      $wpdb->query( "DROP TABLE  $tb6" );
      $wpdb->query( "DROP TABLE  $tb7" );
      $wpdb->query( "DROP TABLE  $tb8" );
    
        wp_safe_redirect(admin_url());exit;
              
 }


?>
        <div class="wrap">
            <h1><?php echo  __('Transfer Data', 'nirweb-support') ?></h1> 
                <?php if(! get_option('trans_ticket_nirweb')) :?>        
             <form method="POST" style="background: #fff;padding: 15px;border-radius: 3px;border: solid 1px #CCC;">
                    <?php wp_nonce_field( 'trans_data_nirweb_ticket', 'trans_data_nirweb_ticket' ); ?>

                 <p><?php echo  __('To transfer data from the old table to the new one, please click on the button below', 'nirweb-support') ?></p>
                 
                 <button type="submit" class="button button-primary"><?php echo  __('Transfer Data', 'nirweb-support') ?></button>
             </form>   
                <?php endif; ?>
                <form method="POST" id="del_old_tbl_nirweb_ticket" style="background: #ffffff;padding: 15px;border-radius: 3px;border: solid 1px #CCC;margin-top: 20px;color: #f00;">
                
                                    <?php wp_nonce_field( 'del_data_nirweb_ticket', 'del_data_nirweb_ticket' ); ?>

                 <p style="font-size: 18px;font-weight: bold;"><?php echo  __('To delete old tables, click on the button below (if deleted, it is not possible to recover)', 'nirweb-support') ?></p>
                 <button type="submit" class="button"><?php echo  __('Delete Data', 'nirweb-support') ?></button>
             </form>   
                
                
                
                
        </div>
<?php }
//-------- Transfer Data End --------------



if (!function_exists('nirweb_ticket_manage_tickets_callback')) {
    function nirweb_ticket_manage_tickets_callback()
    {
        require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET . 'manage_tickets.php';
    }
}
if (!function_exists('nirweb_ticket_send_ticket_callback')) {
    function nirweb_ticket_send_ticket_callback()
    {
        require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET . 'ticket.php';
    }
}
if (!function_exists('nirweb_ticket_department_ticket_callback')) {
    function nirweb_ticket_department_ticket_callback()
        {
         require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET . 'department.php';
        }
}
if (!function_exists('nirweb_ticket_PreAnswer_ticket_callback')) {
    function nirweb_ticket_PreAnswer_ticket_callback()
    {
        require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET . 'pre_answer.php';
    }
}
if (!function_exists('nirweb_ticket_FAQ_ticket_callback')) {
    function nirweb_ticket_FAQ_ticket_callback()
    {
        require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET . 'FAQ.php';
    }
}


//~~~~~~~~~~~~END create Menu~~~~~~~~~~~~~
//~~~~~~~~~~~~START Action Create Roles~~~~~~
add_action('init',function ()
{
    $role = add_role('user_support', __('Support', 'nirweb-support'), array(
        'read' => true,
        'upload_files' => true
    ));
    $role = get_role('user_support');
    $role->add_cap('upload_files');
    $role->add_cap('delete_posts');
    $role->add_cap('edit_posts');
    remove_role('support_moderator');
});

//~~~~~~~~~~~~END Action Create Roles~~~~~~
add_action('admin_menu', function ()
{
    if (current_user_can('user_support')) {
        remove_menu_page('edit.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');
    }
});

add_action('admin_bar_menu', function ($wp_admin_bar)
{
    if (current_user_can('user_support')) {
        $wp_admin_bar->remove_node('new-post');
    }
}, 999);

//~~~~~~~~~~~~START Create Post type Pre Answer~~~~~~
add_action('init', function () {
    $labels = array(
        'name' => __('pre answer', 'nirweb-support'),
        'singular_name' => __('pre answer', 'nirweb-support'),
        'add_new' => __('New Answer', 'nirweb-support'),
        'add_new_item' => __('New Answer', 'nirweb-support'),
        'edit_item' => __('Edit Answer', 'nirweb-support'),
        'new_item' => __('New Answer', 'nirweb-support'),
        'all_items' => __('pre answer', 'nirweb-support'),
        'view_item' => __('Show Answer', 'nirweb-support'),
        'menu_name' => __('pre answer', 'nirweb-support')
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'query_var' => false,
        'rewrite' => array('slug' => false),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 8,
        'supports' => array('title', 'editor', 'revisions'),
        'show_in_menu' => false
    );
    register_post_type('pre_answer_wpyTicket', $args);
}, 20);
//~~~~~~~~~~~~END Post type Pre Answer~~~~~~
//~~~~~~~~~~~~Start Add Page Ticket To My Account ~~~~~~
if (is_plugin_active('woocommerce/woocommerce.php')) {
    class nirweb_ticket_My_Account_Endpoint
    {
        /**
         * Custom endpoint name.
         *
         * @var string
         */
        public static $endpoint = 'wpyar-ticket';
        /**
         * Plugin actions.
         */
        public function __construct()
        {
            add_action('init', array($this, 'add_endpoints'));
            add_filter('query_vars', array($this, 'add_query_vars'), 0);
            add_filter('the_title', array($this, 'endpoint_title'));
            add_filter('woocommerce_account_menu_items', array($this, 'new_menu_items'));
            add_action('woocommerce_account_' . self::$endpoint . '_endpoint', array($this, 'endpoint_content'));
        }
        public function add_endpoints()
        {
            add_rewrite_endpoint(self::$endpoint, EP_ROOT | EP_PAGES);
        }
        public function add_query_vars($vars)
        {
            $vars[] = self::$endpoint;
            return $vars;
        }
        public function endpoint_title($title)
        {
            global $wp_query;
            $is_endpoint = isset($wp_query->query_vars[self::$endpoint]);
            if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {
                // New page title.
                $title = __('Support Ticket', 'nirweb-support');
                remove_filter('the_title', array($this, 'endpoint_title'));
            }
            return $title;
        }
        public function new_menu_items($items)
        {
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);
            $items[self::$endpoint] = __('Support Ticket', 'nirweb-support');
            $items['customer-logout'] = $logout;
            return $items;
        }
        /**
         * Endpoint HTML content.
         */
        public function endpoint_content()
        {
         
            if (isset($_GET['action']) && !empty(sanitize_text_field($_GET['action']))) {
                if (sanitize_text_field($_GET['action']) == 'new') {
                    require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'new_ticket.php';
                } elseif (sanitize_text_field($_GET['action']) == 'reply') {
                    require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'replay_ticket.php';
                }
            } else {
                require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'home.php';
            }
        }
        /**
         * Plugin install action.
         * Flush rewrite rules to make our custom endpoint available.
         */
        public static function install()
        {
            flush_rewrite_rules();
        }
    }
    new nirweb_ticket_My_Account_Endpoint();
    // Flush rewrite rules on plugin activation.
    register_activation_hook(__FILE__, array('nirweb_ticket_My_Account_Endpoint', 'install'));
}
//~~~~~~~~~~~~END Add Page Ticket To My Account ~~~~~~~~~~
//~~~~~~~~~~~~ Start ShortCode ~~~~~~
add_shortcode('nirweb_ticket',function ()
{
    if (is_user_logged_in()) {
         if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'new') {
            require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'new_ticket.php';
        } else if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'reply') {
            require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'replay_ticket.php';
        } else {
            require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'home.php';
        }
    }
});

 

add_shortcode('nirweb_ticket_new', function (){
    require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'new_ticket.php';
});

add_shortcode('nirweb_ticket_rep',function ()
{
     require_once NIRWEB_SUPPORT_INC_USER_THEMES_TICKET . 'replay_ticket.php';
});
//--- ~~~~~~~~~~~~ END ShortCode ~~~~~~~~~~~~~
if (wpyar_ticket['display_icon_send_ticket'] == '1') {
    add_action('wp_footer',     function (){
        $pag_id = intval(wpyar_ticket['select_page_ticket']);
?>
        <a class="nirweb_ticket_logo" href="<?php
                                            if (wpyar_ticket['select_page_ticket']) {
                                                echo esc_url(get_page_link($pag_id));
                                            } else {
                                                echo get_permalink(get_option('woocommerce_myaccount_page_id'));
                                            } ?>" style="<?php
                        echo (wpyar_ticket['position_icon_nirweb_ticket_front'] == 'icon-left') ? 'left:0' : 'right:0';
                        ?> ">
            <img class="logo_ticket_wpyar" src="<?php if (wpyar_ticket['icon_nirweb_ticket_front']['url']) {
                                                    echo wpyar_ticket['icon_nirweb_ticket_front']['url'];
                                                } else {
                                                    echo NIRWEB_SUPPORT_URL_TICKET . 'assets/images/defualt-logo.png';
                                                } ?>" alt="<?php bloginfo('name') ?>" />
        </a>
        <style>
            .nirweb_ticket_logo {
                position: fixed;
                bottom: 0;
                z-index: 99999;
            }
        </style>
<?php }, 100);

    
}
//----------- Add Number Ticket in Admin Bar    
require_once NIRWEB_SUPPORT_TICKET . 'inc/admin/functions/func_number_tab_ticktes.php';

add_action('admin_bar_menu', function ($wp_admin_bar)
{
    if (current_user_can('administrator')) {
        $scount = nirweb_ticket_count_new_ticket();
    } else {
        $scount = nirweb_ticket_count_new_ticket_posht(get_current_user_id());
    }
    if ($scount > 0) {
        $args = array(
            'id' => 'New_ticket',
            'title' => '<p style="background:red;color:#fff;padding:0 5px;"> ' . __('Count New Ticket', 'nirweb-support') . $scount . '</p>',
            'href' => get_bloginfo('url') . '/wp-admin/admin.php?page=nirweb_ticket_manage_tickets&tab=new_ticket',
            'meta' => array(
                'class' => 'New_ticket',
                'title' => __('New Ticket', 'nirweb-support')
            )
        );
        $wp_admin_bar->add_node($args);
    }
}, 999);
//--------------- Filter Media
add_filter('ajax_query_attachments_args', function ($query = array())
{
    $user_id = get_current_user_id();
    if ($user_id) {
        $query['user_support'] = $user_id;
    }
    return $query;
}, 10, 1);

//--------------- Timr Ago
if (!function_exists('ago_ticket_wpyar')) {
    function ago_ticket_wpyar($post_date)
    {
        echo esc_html(human_time_diff($post_date, current_time('timestamp'))) . __(' ago', 'nirweb-support');
    }
}//--------------- Timr Ago
if (!function_exists('ago_ticket_nirweb')) {
    function ago_ticket_nirweb($post_date)
    {
        echo esc_html(human_time_diff($post_date, current_time('timestamp'))) . __(' ago', 'nirweb-support');
    }
}