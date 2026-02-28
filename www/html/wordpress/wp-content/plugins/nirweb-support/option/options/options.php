<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.


function user_upload(){
   require_once NIRWEB_SUPPORT_INC_ADMIN_THEMES_TICKET.'file_uploads.php';
}
function ticket_text_var_wpyartick()
{
  echo '<div class ="info_set_text_body">';
  echo '<h2 style="margin-bottom:20px;color:#ff0000">'.__('Help to create email text', 'nirweb-support').'</h2>';
  echo '<p><span style="width:120px;display:inline-block">'.__('ticket number', 'nirweb-support').'</span> <code>{{ticket_id}}</code></p>';
  echo '<p><span style="width:120px;display:inline-block">'.__('ticket title', 'nirweb-support').'</span><code>{{ticket_title}}</code></p>';
  echo '<p><span style="width:120px;display:inline-block">'.__('name support', 'nirweb-support').'</span><code>{{ticket_poshtiban}}</code></p>';
  echo '<p><span style="width:120px;display:inline-block">'.__('name department', 'nirweb-support').'</span><code>{{ticket_dep}}</code></p>';
  echo '<p><span style="width:120px;display:inline-block">'.__('Priority', 'nirweb-support').'</span> <code>{{ticket_pri}}</code></p>';
  echo '<p><span style="width:120px;display:inline-block">'.__('status', 'nirweb-support').'</span> <code>{{ticket_stu}}</code></p>';
  echo '</div>';
}

//
// Set a unique slug-like ID
//
$prefix = 'nirweb_ticket_perfix';



  CSFTICKET::createOptions( $prefix, array(
    'menu_title'  => __('Settings', 'nirweb-support'),
    'menu_slug'   => 'settings',
    'menu_type'   => 'submenu',
    'framework_title'   => __('NirWeb Team', 'nirweb-support'),
    'menu_parent' => 'nirweb_ticket_manage_tickets',
     'show_bar_menu'           => false,
  ) );
  
//
// Create options
//
 CSFTICKET::createSection( $prefix, array(
  'title'  => __('General settings', 'nirweb-support'),
  'icon'   => 'fas fa-sliders-h',
  'fields' => array(

     array(
      'id'         => 'display_icon_send_ticket',
      'type'       => 'switcher',
      'title'  => __('Display Icon', 'nirweb-support'),
      'text_on'    => __('active', 'nirweb-support'),
      'text_off'   => __('deactivate', 'nirweb-support'),
      'text_width' => 100
    ),
  
    array(
      'id'          => 'select_page_ticket',
      'type'        => 'select',
      'title'       => __('If you do not have the WooCommerce plugin installed, select a Page' , 'nirweb-support' ),
      'placeholder' => __('Please Select Page', 'nirweb-support'),
      'subtitle'     => __('short code : [wpyar_ticket]', 'nirweb-support'),
      'options'     => 'pages',
      'query_args'  => array(
      'posts_per_page' => -1 // for get all pages (also it's same for posts).
          )
    ),

    array(
      'id'    => 'icon_nirweb_ticket_front',
      'type'  => 'media',
      'title' => __('Select Icon', 'nirweb-support'),
    ),
    array(
      'id'          => 'position_icon_nirweb_ticket_front',
      'type'        => 'select',
      'title'       => __('Posotion Icon', 'nirweb-support'),
       'options'     => array(
        'icon-left'  => __('Left', 'nirweb-support'),
        'icon-right'  => __('Right', 'nirweb-support'),
       ),
      'default'     => 'icon-left'
    ),

    array(
      'id'         => 'template_send_ticket_email',
      'type'       => 'wp_editor',
      'title'  => __('Email Template For Send Ticket By Admin', 'nirweb-support'),
      'subtitle'  => __('Receiver : {username}', 'nirweb-support'),
     ),

  )
) );



 CSFTICKET::createSection( $prefix, array(
  'title'  => __('Uploads File By User', 'nirweb-support'),
  'icon'   => 'fas fa-cloud-upload-alt',
  'fields' => array(
 
  array(
  'type'     => 'callback',
  'function' => 'user_upload',
),
  )
) );




//
// Field: Support
//
CSFTICKET::createSection($prefix, array(
  'title'       => __('Support', 'nirweb-support'),
  'icon'        => 'fa fa-life-ring',
  'id'          => 'support_set_wpyarud'
));



CSFTICKET::createSection($prefix, array(
  'parent'      => 'support_set_wpyarud',
  'title'       => __('Email', 'nirweb-support'),
  'icon'        => 'fa fa-envelope',
  'fields'      => array(

    array(
      'id'    => 'active_send_mail_to_poshtiban',
      'type'  => 'switcher',
      'title' => __('Send Email To Support', 'nirweb-support'),
      'text_on'    => __('active', 'nirweb-support'),
      'text_off'   => __('deactivate', 'nirweb-support'),
      'text_width' => '100',
    ),

    array(
      'id'    => 'active_send_mail_to_user',
      'type'  => 'switcher',
      'title' => __('Send Email To User', 'nirweb-support'),
      'text_on'    => __('active', 'nirweb-support'),
      'text_off'   => __('deactivate', 'nirweb-support'),
      'text_width' => '100',
    ),


    array(
      'id'    => 'oposhtiban_tab_wpyarticket',
      'type'  => 'tabbed',
      'title' => __('Support', 'nirweb-support'),
      'tabs'  => array(

        array(
          'title'  => __('Send Ticket', 'nirweb-support'),
          'fields' => array(
            array(
              'id'      => 'subject_mail_poshtiban_new',
              'type'    => 'text',
              'title'   => __('Subject', 'nirweb-support'),
            ),
            array(
              'id'            => 'poshtiban_text_email_send',
              'type'          => 'wp_editor',
              'title'         => __('Message', 'nirweb-support'),
              'tinymce'       => true,
              'quicktags'     => true,
              'media_buttons' => true,
              'height'        => '250px',
            ),


            // A Callback Field Example
            array(
              'type'     => 'callback',
              'function' => 'ticket_text_var_wpyartick',
            ),





          ),
        ),

        array(
          'title'  => __('Answer Ticket', 'nirweb-support'),
          'fields' => array(
            array(
              'id'      => 'subject_mail_poshtiban_answer',
              'type'    => 'text',
              'title'   => __('Subject', 'nirweb-support'),
            ),
            array(
              'id'            => 'poshtiban_text_email_send_answer',
              'type'          => 'wp_editor',
              'title'         =>__('Message', 'nirweb-support'),
              'tinymce'       => true,
              'quicktags'     => true,
              'media_buttons' => true,
              'height'        => '250px',
            ),


            // A Callback Field Example
            array(
              'type'     => 'callback',
              'function' => 'ticket_text_var_wpyartick',
            ),

          ),
        ),

      ),
    ),


    array(
      'id'    => 'ueser_tab_wpyarticket',
      'type'  => 'tabbed',
      'title' => __('User', 'nirweb-support'),
      'tabs'  => array(

        array(
          'title' => __('Send Ticket', 'nirweb-support'),
          'fields' => array(
            array(
              'id'      => 'subject_mail_user_new',
              'type'    => 'text',
              'title'   => __('Subject', 'nirweb-support'),
            ),
            array(
              'id'            => 'user_text_email_send',
              'type'          => 'wp_editor',
              'title'         => __('Message', 'nirweb-support'),
              'tinymce'       => true,
              'quicktags'     => true,
              'media_buttons' => true,
              'height'        => '250px',
            ),


            // A Callback Field Example
            array(
              'type'     => 'callback',
              'function' => 'ticket_text_var_wpyartick',
            ),

          ),
        ),

        array(
          'title'  => __('Answer Ticket', 'nirweb-support'),
          'fields' => array(
            array(
              'id'      => 'subject_mail_user_answer',
              'type'    => 'text',
              'title'   => __('Subject', 'nirweb-support'),
            ),
            array(
              'id'            => 'user_text_email_send_answer',
              'type'          => 'wp_editor',
              'title'         => __('Message', 'nirweb-support'),
              'tinymce'       => true,
              'quicktags'     => true,
              'media_buttons' => true,
              'height'        => '250px',
            ),


            // A Callback Field Example
            array(
              'type'     => 'callback',
              'function' => 'ticket_text_var_wpyartick',
            ),

          ),
        ),


      ),
    ),


  )
));



CSFTICKET::createSection($prefix, array(
  'parent'      => 'support_set_wpyarud',
  'title'       => __('Ticket', 'nirweb-support'),
  'icon'        => 'fas fa-ticket-alt',
  'fields'      => array(


    array(
      'id'         => 'require_procut_user_wpyar',
      'type'       => 'switcher',
       'title'    => __('Select Product Required', 'nirweb-support'),
      'text_on'    => __('active', 'nirweb-support'),
      'text_off'   => __('deactivate', 'nirweb-support'),
      'text_width' => 100
    ),

    array(
      'id'    => 'text_top_send_mail',
      'type'  => 'wp_editor',
      'title' => __('Text above the Send Ticket', 'nirweb-support'),
    ),

  )
));


CSFTICKET::createSection($prefix, array(
  'parent'      => 'support_set_wpyarud',
  'title'       => __('File', 'nirweb-support'),
  'icon'        => 'fa fa-file-o',
  'fields'      => array(


    array(
        'id' => 'size_of_file_wpyartik',
        'type' =>'number',    
        'title'=>__('Upload volume in MB', 'nirweb-support'),
        'default'=>2
        ),

    array(
      'id'      => 'mojaz_file_upload_user_wpyar',
      'type'    => 'text',
      'title'   => __('Authorized file extensions', 'nirweb-support'),
      'default' => '.png,.jpg,.jpeg',
      'desc' => __('Expamle : .png,.jpg,.jpeg', 'nirweb-support')
    ),
    
  )
));




