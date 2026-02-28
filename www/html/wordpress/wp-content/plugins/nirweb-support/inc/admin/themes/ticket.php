<?php
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_send_ticket.php';
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_department.php';
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_status_and_priority.php';
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_list_tickets.php';
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_upload_file.php';

$departments=nirweb_ticket_ticket_get_list_department();
//$ticket_id=isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])?
//    intval($_GET['id']):null;
//    $info_ticket=nirweb_ticket_edit_ticket($ticket_id);

if (isset($_GET['id'])){
    include  NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET.'answer-ticket.php';
}else{
    include  NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET.'send_ticket.php';
}



?>



