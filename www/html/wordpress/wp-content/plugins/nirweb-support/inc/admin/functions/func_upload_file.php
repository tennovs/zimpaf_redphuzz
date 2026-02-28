<?php

add_action( 'admin_enqueue_scripts', function () {

    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
    wp_enqueue_script( 'myuploadscript', NIRWEB_SUPPORT_URL_JS_TICKET. 'admin.js.', array('jquery'), null, false );
} );
if (!function_exists('get_list_user_files')) {function get_list_user_files(){
    global $wpdb;
    $items_per_page = 20;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset = ( $page * $items_per_page ) - $items_per_page;
    $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket_user_upload';
    $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
    $total = $wpdb->get_var( $total_query );
    $new_ticket_list = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}nirweb_ticket_ticket_user_upload                                  
                                               ORDER BY id DESC
                                               LIMIT  $offset,  $items_per_page
                                               ");
    return array($new_ticket_list,paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => ceil($total / $items_per_page),
        'current' => $page
    )));
 
}}
if (!function_exists('fun_ticket_wpyar_file_user_delete')) {
    function fun_ticket_wpyar_file_user_delete()
    {
        $id_file = sanitize_text_field($_POST['checkeds_id_file']);
        $item_delete = sanitize_text_field($_POST['check']);
        global $wpdb;
        for ($i = 0; $i < count($item_delete); $i++) {
            $wpdb->delete($wpdb->prefix.'nirweb_ticket_ticket_user_upload', array('id' => $item_delete[$i]));
            wp_delete_attachment( $id_file[$i] );
        }
    }
}