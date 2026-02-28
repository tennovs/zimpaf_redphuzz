<?php
if (!function_exists('filter_ajax_ticket_func')) {
function filter_ajax_ticket_func()
{
    $user_id = get_current_user_id();
    switch (sanitize_text_field($_POST['status'])) {
        case "open":
            $status = 1;
            break;
        case "inprogress":
            $status = 2;
            break;
        case "answered":
            $status = 3;
            break;
        case "closed":
            $status = 4;
            break;
    }
    global $wpdb;
    if($status){
        $process_ticket_list = $wpdb->get_results("SELECT ticket.* , users.ID , users.display_name,status.*,department.* ,department.name as depname ,priority.*,priority.name as proname  ,post.ID,post.post_title as product_name
        FROM {$wpdb->prefix}nirweb_ticket_ticket ticket
        LEFT JOIN {$wpdb->prefix}users users
        ON sender_id=ID AND status= $status
        LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_status status
        ON status_id=$status
        LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_department department
        ON department=department_id
            LEFT JOIN  {$wpdb->prefix}nirweb_ticket_ticket_priority priority
        ON priority=priority_id
        LEFT JOIN  {$wpdb->prefix}posts post
        ON product=post.ID
         WHERE (ticket.support_id = $user_id OR ticket.sender_id = $user_id) AND status=$status
        ORDER BY ticket_id DESC ");
        if($process_ticket_list){
     ?>
<div class="base_list_ticket_uwpyar">
    <ul class="ul_list_ticket_uwpyar">
        <?php      foreach ($process_ticket_list as $row) :    ?>
        <li>
            <a href="?action=reply&id=<?php echo $row->ticket_id ?>" class="<?php
                    if (intval($row->status) == 1) {
                        echo 'ariborder_wpyaru-red';
                    }
                    if (intval($row->status) == 2) {
                        echo 'ariborder_wpyaru-blue';
                    }
                    if (intval($row->status) == 3) {
                        echo 'ariborder_wpyaru-purple';
                    }
                    if (intval($row->status) == 4) {
                        echo 'ariborder_wpyaru-green';
                    }
                                ?>">
                <div class="info_user_time_wpyaru">
                    <?php  $user = wp_get_current_user(); echo get_avatar( $user->ID, 130); ?>
                    <div class="icon_nameUser">
                        <svg id="svg_username" viewBox="0 0 14.8 17.1" style="width: 17px">
                            <path
                                d="M10.9 7.3c.6-.8.9-1.7.9-2.8C11.8 2 9.8 0 7.3 0S2.8 2 2.8 4.5c0 1.1.4 2 1 2.8C1.5 8.6 0 11.1 0 13.7c0 2.3 3.7 3.4 7.4 3.4s7.4-1 7.4-3.3c0-2.7-1.5-5.2-3.9-6.5zM7.3 1c2 0 3.5 1.6 3.5 3.5 0 2-1.6 3.5-3.5 3.5-2 0-3.5-1.6-3.5-3.5S5.4 1 7.3 1zm.1 15.1c-3.1 0-6.4-.8-6.4-2.4 0-2.3 1.3-4.5 3.3-5.6.9.6 2 1 3.1 1s2.2-.3 3-1c2 1.1 3.3 3.3 3.3 5.6.1 1.6-3.2 2.4-6.3 2.4z">
                            </path>
                        </svg>
                        <?php echo $user->display_name; ?>
                    </div>
                    <time>
                        <?php
                        $orig_time = strtotime( $row->time_update);
                        $date = strtotime($row->date_qustion);
                         echo $date = wp_date('d F Y', $date); ?>
                        <?php echo __('Hour', 'nirweb-support') ?>
                        <?php
                        echo $date = wp_date('H:i', $date); ?>
                    </time>
                </div>
                <div class="title_time_ticket">
                    <p><?php echo $row->subject ?></p>
                    <time>
                        <?php
                        $orig_time = strtotime( $row->time_update);
                       ago_ticket_wpyar($orig_time) ?>
                    </time>
                </div>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
</div>
<?php
        }else{
        echo '<h3 style="color:red;font-weight: 400;text-align: center;font-size: 18px;">
        '.__('not found', 'nirweb-support').'
        </h3>' ;
        }
    }else{
        include NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET .'func_u_list_ticket.php';
        $list_ticket = nirweb_ticket_get_list_all_ticket_user(); ?>
<div class="base_list_ticket_uwpyar">
    <ul class="ul_list_ticket_uwpyar">
        <?php      foreach ($list_ticket[0] as $row) :    ?>
        <li>
            <a href="?action=reply&id=<?php echo $row->ticket_id ?>" class="<?php
                    if (intval($row->status) == 1) {
                        echo 'ariborder_wpyaru-red';
                    }
                    if (intval($row->status) == 2) {
                        echo 'ariborder_wpyaru-blue';
                    }
                    if (intval($row->status) == 3) {
                        echo 'ariborder_wpyaru-purple';
                    }
                    if (intval($row->status) == 4) {
                        echo 'ariborder_wpyaru-green';
                    }
                                ?>">
                <div class="info_user_time_wpyaru">
                    <?php  $user = wp_get_current_user(); echo get_avatar( $user->ID, 130); ?>
                    <div class="icon_nameUser">
                        <svg id="svg_username" viewBox="0 0 14.8 17.1" style="width: 17px">
                            <path
                                d="M10.9 7.3c.6-.8.9-1.7.9-2.8C11.8 2 9.8 0 7.3 0S2.8 2 2.8 4.5c0 1.1.4 2 1 2.8C1.5 8.6 0 11.1 0 13.7c0 2.3 3.7 3.4 7.4 3.4s7.4-1 7.4-3.3c0-2.7-1.5-5.2-3.9-6.5zM7.3 1c2 0 3.5 1.6 3.5 3.5 0 2-1.6 3.5-3.5 3.5-2 0-3.5-1.6-3.5-3.5S5.4 1 7.3 1zm.1 15.1c-3.1 0-6.4-.8-6.4-2.4 0-2.3 1.3-4.5 3.3-5.6.9.6 2 1 3.1 1s2.2-.3 3-1c2 1.1 3.3 3.3 3.3 5.6.1 1.6-3.2 2.4-6.3 2.4z">
                            </path>
                        </svg>
                        <?php echo $user->display_name; ?>
                    </div>
                    <time>
                        <?php
                        $orig_time = strtotime( $row->time_update);
                        $date = strtotime($row->date_qustion);
                         echo $date = wp_date('d F Y', $date); ?>
                        <?php echo __('Hour', 'nirweb-support') ?>
                        <?php
                        echo $date = wp_date('H:i', $date); ?>
                    </time>
                </div>
                <div class="title_time_ticket">
                    <p><?php echo $row->subject ?></p>
                    <time>
                        <?php
                        $orig_time = strtotime( $row->time_update);
                       ago_ticket_wpyar($orig_time) ?>
                    </time>
                </div>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
</div>
<div class="pagination_ticket_index">
    <?php echo $list_ticket[1] ?>
</div>
<?php  }
}
}