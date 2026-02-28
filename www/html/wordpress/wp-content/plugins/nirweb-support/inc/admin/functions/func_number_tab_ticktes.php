<?php
if (!function_exists('nirweb_ticket_count_all_ticket')) {function nirweb_ticket_count_all_ticket(){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket"));
    return $count_all;
}}
if (!function_exists('nirweb_ticket_count_new_ticket')) {function nirweb_ticket_count_new_ticket(){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                            WHERE status=1"));
    return $count_all;
}}
if (!function_exists('nirweb_ticket_count_new_ticket_posht')) {function nirweb_ticket_count_new_ticket_posht($id){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                            WHERE (support_id = $id OR sender_id = $id) AND status=1"));
    return $count_all;
}}
if (!function_exists('nirweb_ticket_count_process_ticket')) {function nirweb_ticket_count_process_ticket(){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                            WHERE status=2"));
    return $count_all;
}}
if (!function_exists('nirweb_ticket_count_answered_ticket')) {function nirweb_ticket_count_answered_ticket(){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                            WHERE status=3"));
    return $count_all;
}}
if (!function_exists('nirweb_ticket_count_closed_ticket')) {function nirweb_ticket_count_closed_ticket(){
    global $wpdb;
    $count_all=intval($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nirweb_ticket_ticket 
                                            WHERE status=4"));
    return $count_all;
}}
 