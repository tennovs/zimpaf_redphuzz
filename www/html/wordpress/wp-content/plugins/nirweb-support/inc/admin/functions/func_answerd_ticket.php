<?php
$ticket_id = sanitize_text_field($_POST['id_form']);
if (!function_exists('nirweb_ticket_answer_ticket')) {
  function nirweb_ticket_answer_ticket($ticket_id){
    $text = preg_replace('/\\\\/', '', sanitize_textarea_field(wpautop($_POST['content'])));
       global $wpdb;
   
       $frm_ary_elements=array(
           'user_id' =>  get_current_user_id(),
           'time_answer'   =>  current_time("Y-m-d H:i:s"),
           'text'  => $text,
           'attach_url' =>  isset($_POST['file_url']) ? esc_url($_POST['file_url'] ) : '' ,
           'ticket_id' =>  isset($_POST['id_form']) ? sanitize_text_field($_POST['id_form']) :'' );
           if(strlen(sanitize_text_field($_POST['content'])) >3){
            $wpdb->insert($wpdb->prefix.'nirweb_ticket_ticket_answered',$frm_ary_elements);
           }
          
            $wpdb->update($wpdb->prefix.'nirweb_ticket_ticket',array(
            'department' => intval(sanitize_text_field($_POST['department'])),
            'status' =>  intval(sanitize_text_field($_POST['status'])),
       ),array(   'ticket_id' =>  intval(sanitize_text_field($_POST['id_form']) ) ) );
       
   
       if( get_option('nirweb_ticket_perfix')['active_send_mail_to_user']=='1'){
           
   
        $user = get_user_by('id',intval(sanitize_text_field($_POST['sender_id'] ) ));
        $user =$user->user_email;
   
        $ticket_id= sanitize_text_field($_POST['id_form']);
    
        $ticket_title= sanitize_text_field($_POST['subject']);
        $name_poshtiban = get_user_by('id',intval(sanitize_text_field( $_POST['resivered_id'] ) ) );
        $ticket_poshtiban = $name_poshtiban->user_nicename;
        $ticket_dep = sanitize_text_field($_POST['department_name']);
        
         
        $ticket_pri = sanitize_text_field($_POST['proname']);
        $status_name = sanitize_text_field($_POST['status_name']);
        $search = ['{{ticket_id}}','{{ticket_title}}','{{ticket_poshtiban}}','{{ticket_dep}}','{{ticket_pri}}','{{ticket_stu}}'];
        $replace = [ $ticket_id,$ticket_title,$ticket_poshtiban,$ticket_dep,$ticket_pri ,$status_name ];		
        $to = $user;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = get_option('nirweb_ticket_perfix')['ueser_tab_wpyarticket']['subject_mail_user_answer'];
        $body = str_replace($search, $replace, get_option('nirweb_ticket_perfix')['ueser_tab_wpyarticket']['user_text_email_send_answer']);
                
         wp_mail( $to, $subject, $body, $headers );
   }
      
   }
}


if (!function_exists('func_list_answer_ajax')) {
  function func_list_answer_ajax($ticket_id){
    global $wpdb;
    $process_answer_list = $wpdb->get_results("SELECT answered.* ,users.ID , users.display_name
    FROM {$wpdb->prefix}nirweb_ticket_ticket_answered answered   JOIN {$wpdb->prefix}users users ON user_id=ID
    WHERE ticket_id=$ticket_id  ORDER BY answer_id ASC ");

    foreach($process_answer_list as $row):
        echo'<li> <div class="head_answer"> <span class="name">'.$row->display_name.'  </span>
        <span class="time">'.$date= date('(H:i:s)',strtotime($row->time_answer)).''.$date = wp_date( ' Y-m-d' , strtotime($row->time_answer)).' </span>  </div> <div class="content">'. $row->text;
          ?>
             <?php if ($row->attach_url){
                             echo '<p>'.
                            __('Attachment File', 'nirweb-support').'
                              '.$row->attach_url.'
                                  </p>';
                        } ?>
                        
     <?php                    
        
       echo ' </div></li> ';
     endforeach;
     exit();
    }
}

