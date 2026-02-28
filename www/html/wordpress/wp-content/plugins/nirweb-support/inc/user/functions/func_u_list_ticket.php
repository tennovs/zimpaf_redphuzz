<?php
if (!function_exists('nirweb_ticket_get_list_all_ticket_user')) {function nirweb_ticket_get_list_all_ticket_user(){
  global $wpdb,$table_prefix;

  
  $user_id=get_current_user_id();

  global $wpdb;
  $items_per_page = 20;
  $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
  $offset = ( $page * $items_per_page ) - $items_per_page;
  $query = 'SELECT * FROM '.$wpdb->prefix.'nirweb_ticket_ticket ticket WHERE ticket.id_receiver='.$user_id.' 
  or ticket.sender_id='.$user_id.' ';
  
  $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
  $total = $wpdb->get_var( $total_query );



  $new_ticket_list=$wpdb->get_results("SELECT ticket.* , users.ID , users.display_name,status.*,depart.*, depart.name as department_name,post.ID,post.post_title,priority.*,priority.name as proname
                                             FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
                                             
                                               LEFT JOIN  {$wpdb->prefix}posts post
                                             ON product=post.ID
                                             
                                             
                                             LEFT JOIN {$wpdb->prefix}users users
                                             ON sender_id=users.ID 
                                             
                                             LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
                                             ON status=status_id
                                             LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
                                             ON priority=priority_id
                                             
                                            LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department depart
                                             ON department=department_id
                                               
                                             WHERE ticket.id_receiver=$user_id 
                                               or ticket.sender_id=$user_id 

                                               ORDER BY ticket_id DESC  
                                               LIMIT  $offset,  $items_per_page
                                                                                            ");
  return array(

    $new_ticket_list,paginate_links( array(
      'base' => add_query_arg( 'cpage', '%#%' ),
      'format' => '',
      'prev_text' => __('&laquo;'),
      'next_text' => __('&raquo;'),
      'total' => ceil($total / $items_per_page),
      'current' => $page
  ))
  ) ;

  
}}
if (!function_exists('nirweb_ticket_edit_ticket')) {function nirweb_ticket_edit_ticket($ticket_id)
  {
      global $wpdb;
      $ticket = $wpdb->get_row("SELECT ticket.* , users.ID , users.display_name,status.*,department.* ,department.name as depname,priority.*,priority.name as proname ,revuser.ID as rev_id,revuser.display_name as rev_name ,posts.ID,posts.post_title as product_name
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
if (!function_exists('nirweb_ticket_count_all_ticket_user_fr')) {function nirweb_ticket_count_all_ticket_user_fr(){
  $user_id=get_current_user_id();
  global $wpdb;
  $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket WHERE  id_receiver= $user_id OR sender_id=$user_id "));
    echo $count_all;
}}
if (!function_exists('nirweb_ticket_count_new_ticket_user_fr')) {function nirweb_ticket_count_new_ticket_user_fr(){
  $user_id=get_current_user_id();

  global $wpdb;
  $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                          WHERE (id_receiver= $user_id OR sender_id=$user_id ) AND  status=1"));
    echo $count_all;
}}

if (!function_exists('nirweb_ticket_count_process_ticket_user_fr')) {function nirweb_ticket_count_process_ticket_user_fr(){
  $user_id=get_current_user_id();

  global $wpdb;
  $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                          WHERE (id_receiver= $user_id OR sender_id=$user_id ) AND status=2 "));
  echo $count_all;
}}
if (!function_exists('nirweb_ticket_count_answered_ticket_user_fr')) {function nirweb_ticket_count_answered_ticket_user_fr(){
  $user_id=get_current_user_id();

  global $wpdb;
  $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                          WHERE (id_receiver= $user_id OR sender_id=$user_id ) AND status=3"));
  echo $count_all;
}}
if (!function_exists('nirweb_ticket_count_closed_ticket_user_fr')) {function nirweb_ticket_count_closed_ticket_user_fr(){
  $user_id=get_current_user_id();

  global $wpdb;
  $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                          WHERE (id_receiver= $user_id OR sender_id=$user_id ) AND status=4 "));
  echo $count_all;
}


}
  