<?php

add_action( 'admin_enqueue_scripts', function () {
    if(is_rtl()){
        wp_enqueue_style('admin-css-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/admin-rtl.css');
    }else{
        wp_enqueue_style('admin-css-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/admin.css');
    }
    wp_enqueue_style('select-wpyt-tw.css', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/select.tw.css');
    wp_enqueue_script('jquery') ;
     wp_enqueue_script('sweetalert2-wpyt-min-js', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/sweetalert2.min.js');
    wp_enqueue_style('sweetalert2-wpyt-min-css', NIRWEB_SUPPORT_URL_TICKET . 'assets/css/sweetalert2.css');
     wp_enqueue_script('select_2-js-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/select_2.js');
    wp_enqueue_script('admin-js-file-wpyt', NIRWEB_SUPPORT_URL_TICKET . 'assets/js/admin.js');
    wp_localize_script(
        'admin-js-file-wpyt',
        'wpyarticket',
        [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'comp_sec' => __('Please complete all starred sections', 'nirweb-support'),
            'send_info' => __('Sending information...', 'nirweb-support'),
            'add_file' => __('Add File', 'nirweb-support'),
            'use_file' => __('Use the file', 'nirweb-support'),
            'send_tik_success' => __('Your ticket was sent successfully', 'nirweb-support'),
            'send_ans_success' => __('Your answer was sent successfully', 'nirweb-support'),
            'send_ans_err' => __('There was a problem sending the reply', 'nirweb-support'),
            'ques' => __('Are you sure?', 'nirweb-support'),
            'subdel' => __('The delete action causes the information to be lost.', 'nirweb-support'),
            'ok' => __('Ok', 'nirweb-support'),
            'cancel' => __('Cancel', 'nirweb-support'),
            'add_dep' => __('Add Department', 'nirweb-support'),
            'name_dep_err' => __('Please enter the name of the department', 'nirweb-support'),
            'sup_dep_err' => __('Please enter the support of the department', 'nirweb-support'),
            'chenge_dep' => __('The department changed successfully', 'nirweb-support'),
            'add_ques_err' => __('Please enter a question', 'nirweb-support'),
            'add_text_faq_err' => __('Please enter the answer text', 'nirweb-support'),
            'faq_ques_add' => __('Question added', 'nirweb-support'),
        ]
    );
    wp_localize_script(
        'sweetalert2-wpyt-min-js',
        'wpyarticketsw',
        [
             'ok' => __('Ok', 'nirweb-support'),
            'cancel' => __('Cancel', 'nirweb-support'),
         ]
    );

} );






