<?php
global $wpdb,$table_prefix;
if (!function_exists('nirweb_ticket_ticket_get_supporter_department')) {function nirweb_ticket_ticket_get_supporter_department()
    {
        $users =  get_users( [ 'role__in' => [ 'user_support', 'administrator'] ]);
        return $users;
    }}
if (!function_exists('nirweb_ticket_ticket_add_department')) {
    function nirweb_ticket_ticket_add_department()
{
    $new_arg=array(
        'name'  =>  sanitize_text_field($_POST['department_name']),
        'support_id'  => sanitize_text_field($_POST['id_poshtiban'])
    );
    global $wpdb;
    $wpdb->insert($wpdb->prefix.'nirweb_ticket_ticket_department',$new_arg);
    return $wpdb->last_query;
}
}
if (!function_exists('get_list_department_ajax')) {function get_list_department_ajax(){
    global $wpdb;
    $departments = $wpdb->get_results("SELECT wd.*,wu.ID,wu.display_name
                    FROM {$wpdb->prefix}nirweb_ticket_ticket_department wd
                    JOIN  {$wpdb->prefix}users wu
                    ON ID=support_id 
                    ORDER BY department_id DESC                                          
                ");
  foreach ($departments as $department){  
echo'
        <tr style="border: solid 1px #ccc" class="row_dep">
            <th><input type="checkbox" id="frm_check_items" name="frm_check_items[]" 
            value="'.$department->department_id.'"></th>
            <th  class="dep_name"  data-id="'.$department->department_id .'" >'.$department->name .'</th>
            <th class="name_user"  data-user_id="'.$department->support_id .'" >'.$department->display_name .'</th>
            <th><a class="edit_ticket_wpys">
            <span class="dashicons dashicons-edit"></span></a></a></th>
        </tr>';
   }
}}
if (!function_exists('nirweb_ticket_ticket_get_list_department')) {function nirweb_ticket_ticket_get_list_department()
    {
        global $wpdb;
        $departments = $wpdb->get_results("SELECT wd.*,wu.*,wu.display_name 
                        FROM {$wpdb->prefix}nirweb_ticket_ticket_department wd
                        JOIN  {$wpdb->prefix}users wu
                        ON ID=support_id 
                        ORDER BY department_id DESC
                    ");
        return $departments;
    }}
if (!function_exists('nirweb_ticket_delete_department')) {function nirweb_ticket_delete_department(){
    global $wpdb;
    $item_delete = sanitize_post($_POST['check']);
    for ($i = 0; $i < count($item_delete); $i++) {
        $wpdb->delete($wpdb->prefix.'nirweb_ticket_ticket_department', array('department_id' => $item_delete[$i]));
    }
}}
if (!function_exists('nirweb_ticket_edite_department')) {function nirweb_ticket_edite_department($data){
    $name = sanitize_text_field($data['department_name']);
      $id = sanitize_text_field($data['depa_id']);
      $support_id = sanitize_text_field($data['id_poshtiban']);
      global $wpdb;
     $table_name  = $wpdb->prefix."nirweb_ticket_ticket_department";
     $wpdb->update( $table_name, array('name'=>$name, 'support_id'=>$support_id), array('department_id' => $id));
 }}
 



