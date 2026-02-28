<?php
if (!function_exists('nirweb_ticket_get_status')) {function nirweb_ticket_get_status(){
    global $wpdb;
    $list_status=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}nirweb_ticket_ticket_status ORDER BY status_id");
    return $list_status;
}}
if (!function_exists('nirweb_ticket_get_priority')) {function nirweb_ticket_get_priority(){
    global $wpdb;
    $list_priority=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}nirweb_ticket_ticket_priority ORDER BY priority_id");
    return $list_priority;
}}

