<?php
if (!function_exists('user_wpyar_answer_ticket')) {
    function user_wpyar_answer_ticket()
    {
         global $wpdb;
      if ( $_FILES ) {
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
        $time = current_time("Y-m-d H:i:s");
        global $wpdb;
        if($_POST['closed_answer']){
            $status =  4;
        }else{
            $status =  1;
        }
        $frm_ary_elements = array(
            'user_id' => get_current_user_id(),
            'time_answer' => current_time("Y-m-d H:i:s"),
            'text' => isset($_POST['user_answer']) ? sanitize_textarea_field(wpautop($_POST['user_answer'])) : '',
            'attach_url' => esc_url($url_file),
            'ticket_id' => isset($_POST['tik_id']) ? sanitize_text_field( $_POST['tik_id'] ) : '');
        if(sanitize_text_field($_POST['user_answer']) or $url_file){    
        $wpdb->insert($wpdb->prefix . 'nirweb_ticket_ticket_answered', $frm_ary_elements);
        }
        $wpdb->update($wpdb->prefix.'nirweb_ticket_ticket',array(
            'status' =>  $status,
            'time_update' => $time,
        ),array(   'ticket_id' =>  intval(sanitize_text_field($_POST['tik_id']) )));
            if($attach_id){
                     global $wpdb;
        $wpyar_user_upload=array(  
                'user_id' =>  get_current_user_id(),
                'url_file'   =>   $url_file,
                'file_id'   =>   $attach_id,
                'time_upload'   =>   current_time("Y-m-d H:i:s")
            );
         $wpdb->insert($wpdb->prefix.'nirweb_ticket_ticket_user_upload',$wpyar_user_upload);
            }
            //----------- Start Mail Department User 
      $wpytik = get_option('nirweb_ticket_perfix');
      if( $wpytik['active_send_mail_to_poshtiban']=='1'){
      $user_poshtiban = get_user_by('id',intval(sanitize_text_field($_POST['id_user'])));
      $user_poshtiban =$user_poshtiban->user_email;
      $ticket_id=sanitize_text_field($_POST['tik_id']);
      $ticket_title= sanitize_text_field($_POST['subject']);
      $name_poshtiban = get_user_by('id', intval(sanitize_text_field($_POST['id_user'] )));
      $ticket_poshtiban = $name_poshtiban->user_nicename;
      $ticket_dep = sanitize_text_field($_POST['dep_name']);
      $ticket_pri = sanitize_text_field($_POST['priority_name']);
      $search = ['{{ticket_id}}','{{ticket_title}}','{{ticket_poshtiban}}','{{ticket_dep}}','{{ticket_pri}}','{{ticket_stu}}'];
      $replace = [ $ticket_id,$ticket_title,$ticket_poshtiban,$ticket_dep,$ticket_pri ,__('open', 'nirweb-support') ];		
      $to = $user_poshtiban;
      $headers = array('Content-Type: text/html; charset=UTF-8');
      $subject = $wpytik['oposhtiban_tab_wpyarticket']['subject_mail_poshtiban_answer'];
      $body = wpautop(str_replace($search, $replace, $wpytik['oposhtiban_tab_wpyarticket']['poshtiban_text_email_send_answer']));
      wp_mail( $to, $subject, $body, $headers );
      }
    }
}

if (!function_exists('func_list_answer_ajax_user')) {
    function func_list_answer_ajax_user(){
        $ticket_id = sanitize_text_field($_POST['tik_id']);
        global $wpdb;
        $process_answer_list = $wpdb->get_results("SELECT answered.* ,users.ID , users.display_name
        FROM {$wpdb->prefix}nirweb_ticket_ticket_answered answered   JOIN {$wpdb->prefix}users users ON user_id=ID
        WHERE ticket_id=$ticket_id  ORDER BY answer_id ASC");
        foreach($process_answer_list as $row):  
              $user=get_userdata( $row->user_id);
                $role = $user->roles;
                 if(in_array ('user_support', $role) or in_array ('administrator', $role)){
                    $cls ='user_support_wpyar';
                }else{
                    $cls='';
                }
                 ?>
                <li class="<?php echo $cls ?>">
                    <div class="img_avatar_wpyartick">
                        <?php echo get_avatar( $row->user_id, 100) ?>
                    </div>
                    <div class="info_answer_box_wpyartick">
                        <div class="text_message_wpyartick">
                            <?php echo wpautop($row->text) ?>
                            <?php if ($row->attach_url){ ?>
                            <p class="file_atach_url">
                                <a href="<?php echo $row->attach_url ?>" target="_blank">  
                                <?php echo __('show attachment file', 'nirweb-support') ?>
                                 </a>
                            </p>
                            <?php } ?>
                        </div>
                        <div class="head_answer">
                            <span class="name">
                                <?php echo $row->display_name ?>
                            </span>
                            <?php  if(in_array ('user_support', $role)){ ?>
                              <span class="time">
                              <?php echo __('Hour', 'nirweb-support') ?>
                                <?php echo  $date = date( 'H:i:s' , strtotime($row->time_answer)); ?>
                            </span>
                                <span class="date">
                                <?php echo __('Date :', 'nirweb-support') ?>
                                <?php echo wp_date('d F Y' , strtotime($row->time_answer))   ?>
                            </span>
                            <?php } else { ?>
                            <span class="date">
                            <?php echo __('Date :', 'nirweb-support') ?>
                                <?php echo wp_date('d F Y' , strtotime($row->time_answer))   ?>
                            </span>
                            <span class="time">
                            <?php echo __('Hour', 'nirweb-support') ?>
                                <?php echo  $date = date( 'H:i:s' , strtotime($row->time_answer)); ?>
                            </span>
                        <?php } ?>
                        </div>
                    </div>
                </li>
       <?php  endforeach;
        exit();
        }
}

