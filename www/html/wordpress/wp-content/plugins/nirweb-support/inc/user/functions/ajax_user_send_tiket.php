<?php
if (!function_exists('func_user_send_tiket')) {
    function func_user_send_tiket(){
        global $wpdb;
      
        
  if (sizeof( $_FILES) >0) {
    $accsses_file = explode(",",trim( wpyar_ticket['mojaz_file_upload_user_wpyar']));
            if( $_FILES['updoc']['type'] =='text/javascript' || $_FILES['updoc']['type'] == 'application/octet-stream' ){
                echo 'error_valid_type';
                exit;
            }
   

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $file_handler = 'updoc';
        $attach_id = media_handle_upload($file_handler,$post_id);
        $url_file = wp_get_attachment_url( $attach_id );
  
     }else{
         $url_file='';
       }
    $frm_ary_elements=array(
        'sender_id' =>  get_current_user_id(),
        'id_receiver'   => sanitize_text_field($_POST['resived_id']),
        'receiver_type' =>2,
        'subject'  =>  sanitize_text_field($_POST['subject']),
        'content'  => sanitize_textarea_field($_POST['content']),
        'department'    => isset($_POST['department'])?intval(sanitize_text_field($_POST['department'])):'',
        'priority'  =>  isset($_POST['priority']) ? intval(sanitize_text_field($_POST['priority'])) : '',
        'website' =>   isset($_POST['website']) ? sanitize_text_field($_POST['website']) : '',
        'product' =>  isset($_POST['product']) ? intval(sanitize_text_field($_POST['product'])) : '',
        'support_id' =>  isset($_POST['resived_id']) ? sanitize_text_field($_POST['resived_id']) : '',
        'status' =>  1,
        'file_url' =>  $url_file,
        'date_qustion'   =>  current_time("Y-m-d H:i:s"),
        'time_update'   =>  current_time("Y-m-d H:i:s")
    );
    $wpdb->insert($wpdb->prefix.'nirweb_ticket_ticket',$frm_ary_elements);
     $ticket_id=$wpdb->insert_id;
     if($attach_id){
                 $wpyar_user_upload=array(  
            'user_id' =>  get_current_user_id(),
            'url_file'   =>   $url_file,
            'file_id'   =>   $attach_id,
            'time_upload'   =>   current_time("Y-m-d H:i:s")
        );

        $wpdb->insert($wpdb->prefix.'nirweb_ticket_ticket_user_upload',$wpyar_user_upload);
     }


 //----------- Information Send Mail
 
 if( wpyar_ticket['active_send_mail_to_poshtiban']=='1' or wpyar_ticket['active_send_mail_to_user']=='1'  ){
  $ticket_title= sanitize_text_field($_POST['subject']);
  $name_poshtiban = get_user_by('id', intval(sanitize_text_field($_POST['resived_id'])));
  $ticket_poshtiban = $name_poshtiban->display_name;
  $ticket_dep = sanitize_text_field($_POST['dep_name']);
  $ticket_pri = sanitize_text_field($_POST['priority_name']);
  $search = ['{{ticket_id}}','{{ticket_title}}','{{ticket_poshtiban}}','{{ticket_dep}}','{{ticket_pri}}','{{ticket_stu}}'];
  $replace = [ $ticket_id,$ticket_title,$ticket_poshtiban,$ticket_dep,$ticket_pri ,__('open', 'nirweb-support') ];		
}

//----------- Start Mail Department User
 if(wpyar_ticket['active_send_mail_to_poshtiban'] == '1'){
 $user_poshtiban = get_user_by('id', intval(sanitize_text_field($_POST['resived_id'])));
 $user_poshtiban =$user_poshtiban->user_email;
 $subject = wpyar_ticket['oposhtiban_tab_wpyarticket']['subject_mail_poshtiban_new'];
 $body = wpautop(str_replace($search, $replace, wpyar_ticket['oposhtiban_tab_wpyarticket']['poshtiban_text_email_send']));
 $to = $user_poshtiban;
 $headers = array('Content-Type: text/html; charset=UTF-8');
 wp_mail( $to, $subject, $body, $headers );
}

//---------------- End Mail Department User

//----------- Start Mail  User
   
 if( $wpytik['active_send_mail_to_user']==1 ){
 $user = get_user_by('id',get_current_user_id());
 $user =$user->user_email;
 
 
 $subject = wpyar_ticket['ueser_tab_wpyarticket']['subject_mail_user_new'];
 $body = wpautop(str_replace($search, $replace, wpyar_ticket['ueser_tab_wpyarticket']['user_text_email_send']));
 $to = $user;
 $headers = array('Content-Type: text/html; charset=UTF-8');
 wp_mail( $to, $subject, $body, $headers );
}
 
    echo '<div class="upfile_wpyartick">
             
        <label for="main_image" class="label_main_image">
             <span class="remove_file_by_user"><i class="fal fa-times-circle"></i></span>  
            <i class="fal fa-arrow-up upicon" style="font-size: 30px;margin-bottom: 10px;"></i>
            <span class="text_label_main_image">'.__('Attachment File', 'nirweb-support').'</span>
         </label>
        <input type="file" name="main_image" id="main_image" accept="'.wpyar_ticket['mojaz_file_upload_user_wpyar'].'">
            </div> ';
}
}
