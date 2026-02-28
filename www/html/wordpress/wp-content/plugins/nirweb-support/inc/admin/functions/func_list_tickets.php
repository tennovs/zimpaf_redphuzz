<?php
if (!function_exists('wp_list_ticket_check')) {function wp_list_ticket_check(){
    global $wpdb;
    $new_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname
   ,post.ID,post.post_title as product_name
                                              FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                              LEFT JOIN {$wpdb->prefix}users users
                                              ON sender_id=ID
                               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                              ON status=status_id  
                                              LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                              ON department=department_id  
                                LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                              ON priority=priority_id
                                LEFT JOIN  {$wpdb->prefix}posts post
                                              ON product=post.ID
                                              ");
                                              return $new_ticket_list;
}}
if (!function_exists('wp_list_ticket_check_posht')) {function wp_list_ticket_check_posht($id)
    {
        global $wpdb;
         $new_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname
        ,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID
                                    LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status=status_id  
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id  
                                     LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                     LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                    WHERE ticket.support_id = $id OR ticket.sender_id = $id
                                                 ");
        return $new_ticket_list;
    }}
if (!function_exists('nirweb_ticket_get_list_all_ticket')) {function nirweb_ticket_get_list_all_ticket()
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $new_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname
        ,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID
                                    LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status=status_id  
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id  
                                     LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                     LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                   ORDER BY ticket_id DESC
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
if (!function_exists('nirweb_ticket_get_list_all_ticket_posht')) {function nirweb_ticket_get_list_all_ticket_posht($id)
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $new_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname
        ,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID
                                    LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status=status_id  
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id  
                                     LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                     LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                    WHERE ticket.support_id = $id OR ticket.sender_id = $id
                                                   ORDER BY ticket_id DESC
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
if (!function_exists('nirweb_ticket_get_list_new_ticket')) {function nirweb_ticket_get_list_new_ticket()
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=1';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $process_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname ,priority.*,priority.name as proname  ,post.ID,post.post_title as product_name
               FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
               LEFT JOIN {$wpdb->prefix}users users
               ON sender_id=ID AND status=1
               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
               ON status_id=1
               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
               ON department=department_id
                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
               ON priority=priority_id
               LEFT JOIN  {$wpdb->prefix}posts post
               ON product=post.ID
                WHERE status=1
               ORDER BY ticket_id DESC
                                LIMIT  $offset,  $items_per_page
                                                   ");
        return array($process_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
    );
    }}
if (!function_exists('nirweb_ticket_get_list_new_ticket_posht')) {function nirweb_ticket_get_list_new_ticket_posht($id)
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=1';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $process_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname ,priority.*,priority.name as proname  ,post.ID,post.post_title as product_name
               FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
               LEFT JOIN {$wpdb->prefix}users users
               ON sender_id=ID AND status=1
               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
               ON status_id=1
               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
               ON department=department_id
                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
               ON priority=priority_id
               LEFT JOIN  {$wpdb->prefix}posts post
               ON product=post.ID
                WHERE (ticket.support_id = $id OR ticket.sender_id = $id) AND status=1
               ORDER BY ticket_id DESC
                                LIMIT  $offset,  $items_per_page
                                                   ");
        return array($process_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
    );
    }}
if (!function_exists('nirweb_ticket_get_list_process_ticket')) {function nirweb_ticket_get_list_process_ticket()
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
         $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=2';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $process_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                       FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                       LEFT JOIN {$wpdb->prefix}users users
                       ON sender_id=ID AND status=2
                       LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                       ON status_id=2
                           LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                       ON department=department_id
                           LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                       ON priority=priority_id
                       LEFT JOIN  {$wpdb->prefix}posts post
                       ON product=post.ID
                       WHERE status=2
                       ORDER BY ticket_id DESC
                    LIMIT  $offset,  $items_per_page
                                                   ");
        return array($process_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
        );
    }}
if (!function_exists('nirweb_ticket_get_list_process_ticket_posht')) {function nirweb_ticket_get_list_process_ticket_posht($id)
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
         $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=2';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $process_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                   LEFT JOIN {$wpdb->prefix}users users
                   ON sender_id=ID AND status=2
                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                   ON status_id=2
                       LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                   ON department=department_id
                       LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                   ON priority=priority_id
                   LEFT JOIN  {$wpdb->prefix}posts post
                   ON product=post.ID
                   WHERE (ticket.support_id = $id OR ticket.sender_id = $id ) AND status=2 
                   ORDER BY ticket_id DESC
                LIMIT  $offset,  $items_per_page  ");
        return array($process_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
        );
    }}
if (!function_exists('wp_yap_get_list_answered_ticket')) {
    function wp_yap_get_list_answered_ticket()
{
    global $wpdb;
    $items_per_page = 20;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset = ( $page * $items_per_page ) - $items_per_page;
    $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=3';
    $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
    $total = $wpdb->get_var( $total_query );
    $answered_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                                               FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                               LEFT JOIN {$wpdb->prefix}users users
                                               ON sender_id=ID AND status=3
                                               LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                               ON status_id=3
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                               ON department=department_id
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                               ON priority=priority_id
                                               LEFT JOIN  {$wpdb->prefix}posts post
                                               ON product=post.ID
                                               WHERE status=3
                                               ORDER BY ticket_id DESC
                                        LIMIT  $offset,  $items_per_page
                                               ");
    return array($answered_ticket_list,paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => ceil($total / $items_per_page),
        'current' => $page
    ))
);
}
}
if (!function_exists('wp_yap_get_list_answered_ticket_posht')) {function wp_yap_get_list_answered_ticket_posht($id)
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=3';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $answered_ticket_list = $wpdb->get_results("SELECT ticket.* ,users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID AND status=3
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status_id=3
                                                       LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id
                                                       LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                                   LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                   WHERE (ticket.support_id = $id OR ticket.sender_id = $id ) AND status=3 
                                                   ORDER BY ticket_id DESC
                                            LIMIT  $offset,  $items_per_page
                                                   ");
        return array($answered_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
    );
    }}
if (!function_exists('wp_yap_get_list_closed_ticket')) {function wp_yap_get_list_closed_ticket()
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=4';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $closed_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID AND status=4
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status_id=4
                                                 LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id
                                                LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                                   LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                   WHERE status=4
                                                   ORDER BY ticket_id DESC
                                                    LIMIT  $offset,  $items_per_page
                                                   ");
        return array($closed_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
    );
    }}
if (!function_exists('wp_yap_get_list_closed_ticket_posht')) {function wp_yap_get_list_closed_ticket_posht($id)
    {
        global $wpdb;
        $items_per_page = 20;
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset = ( $page * $items_per_page ) - $items_per_page;
        $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE status=4';
        $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total = $wpdb->get_var( $total_query );
        $closed_ticket_list = $wpdb->get_results("SELECT ticket.* , users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname,post.ID,post.post_title as product_name
                                                   FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                                   LEFT JOIN {$wpdb->prefix}users users
                                                   ON sender_id=ID AND status=4
                                                   LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                                   ON status_id=4
                                                 LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
                                                   ON department=department_id
                                                LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                                   ON priority=priority_id
                                                   LEFT JOIN  {$wpdb->prefix}posts post
                                                   ON product=post.ID
                                                   WHERE (ticket.support_id = $id OR ticket.sender_id = $id ) AND status=4 
                                                   ORDER BY ticket_id DESC
                                                    LIMIT  $offset,  $items_per_page
                                                   ");
        return array($closed_ticket_list,paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => ceil($total / $items_per_page),
            'current' => $page
        ))
    );
    }}
if (!function_exists('nirweb_ticket_edit_ticket')) { function nirweb_ticket_edit_ticket($ticket_id)
    {
        global $wpdb;
        $ticket = $wpdb->get_row("SELECT ticket.* ,users.* ,status.*,department.* ,department.name as depname,priority.*,priority.name as proname ,revuser.ID as rev_id,revuser.display_name as rev_name ,posts.ID,posts.post_title as product_name
        FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
        LEFT JOIN {$wpdb->prefix}users users
        ON sender_id=ID                 
        LEFT JOIN {$wpdb->prefix}posts posts
        ON product=posts.ID
        LEFT JOIN {$wpdb->prefix}users revuser
        ON id_receiver=revuser.ID
        LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
        ON status=status_id  
        LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
        ON department=department_id  
        LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
        ON priority=priority_id        
      where $ticket_id = ticket_id;  ");                              
        return $ticket;
    }}
if (!function_exists('nirweb_ticket_delete_ticket')) {function nirweb_ticket_delete_ticket($item_delete)
    {
        global $wpdb;
        for ($i = 0; $i < count($item_delete); $i++) {
            $wpdb->delete($wpdb->prefix.'nirweb_ticket_ticket', array('ticket_id' => $item_delete[$i]));
            $wpdb->delete($wpdb->prefix.'nirweb_ticket_ticket_answered', array('ticket_id' => $item_delete[$i]));
        }
    }
    }
 