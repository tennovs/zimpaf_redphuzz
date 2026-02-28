<?php
if (!function_exists('func_ajax_nirweb_ticket_user_search')) {
    function func_ajax_nirweb_ticket_user_search($value){
        global $wpdb;
        $user_id=get_current_user_id();
        $address_table = $wpdb->prefix . "nirweb_ticket_ticket";
        // To use "LIKE" with the wildcards (%), we have to do some funny business:
        $search = "%{$value}%";
        // Build the where clause using $wpdb->prepare to prevent SQL injection attacks
        // Searching ALL THREE of our columns: Product, Application, Sector 
        $where = $wpdb->prepare( 'WHERE (id_receiver=%s OR sender_id=%s) AND (ticket_id LIKE %s OR subject LIKE %s)', $user_id, $user_id,$search, $search);
        // Execute the query with our WHERE clause
        // NOTE: Your code originally used "$sc_products", but that variable was not defined - so have replaced to the proper $address_table here.
        $results = $wpdb->get_results( "SELECT * FROM {$address_table} {$where}" );
                if(sizeof($results)>0){
                    foreach($results as $row){
                 
                        echo'<li><a href="#">'.$row->subject.'</a></li>';
                             }
                }else{
                     echo'<p class="not_found">'.__('not found', 'nirweb-support').'</p>' ;  
                }
    }
}
