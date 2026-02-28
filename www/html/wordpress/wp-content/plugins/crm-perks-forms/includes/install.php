<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'cfx_form_install' ) ):

class cfx_form_install{
    
public function get_roles(){
      $roles=array(
     // cfx_form::$id."_read_entries" , 
      cfx_form::$id."_read_settings" , 
      cfx_form::$id."_edit_entries" , 
     cfx_form::$id."_edit_settings" 
      );
      return $roles;

}
public function create_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->add_cap( 'administrator', $role );
}
$wp_roles->add_cap( 'administrator', 'vx_crmperks_view_plugins' );
$wp_roles->add_cap( 'administrator', 'vx_crmperks_view_addons' );
$wp_roles->add_cap( 'administrator', 'vx_crmperks_edit_addons' );

}

public function remove_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->remove_cap( 'administrator', $role );
}
}
public function remove_data(){
    global $wpdb;
  delete_option(cfx_form::$id."_plugin_version"); 
  delete_option(cfx_form::$id."_upload_folder"); 
  delete_option(cfx_form::$id."_meta"); 
  $this->drop_tables();
  $this->remove_roles();
}
  /**
  * drop tables
  * 
  */
  public  function drop_tables(){
  global $wpdb;
  $wpdb->query("DROP TABLE IF EXISTS " . cfx_form::table_name('forms'));
  }
public function create_tables(){
           global $wpdb;    
    $charset_collate ="DEFAULT CHARSET=utf8";
    if(method_exists($wpdb,'get_charset_collate'))
    $charset_collate = $wpdb->get_charset_collate();
    // $wpdb->show_errors();
    $table_name = cfx_form::table_name('forms');

    $sql = "CREATE TABLE $table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(200) DEFAULT 'new form' NOT NULL,
        `form_location` varchar(20),
        `settings` longtext,
        `config` longtext,
        `fields` longtext,
        `notify` longtext,
        `extra` longtext,
        `views` bigint(20) unsigned NOT NULL default 0,
        `entries` bigint(20) unsigned NOT NULL default 0,
        `rejected` bigint(20) unsigned NOT NULL default 0,
        `status` int(4) NOT NULL DEFAULT 0,
        `time` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    }
public function create_upload_dir(){
$upload_dir= wp_upload_dir();
$folder=cfx_form::get_upload_folder();

$htaccess = <<<XML
# BEGIN CRM Perks
# Disable parsing of PHP for some server configurations.

<Files *>
  SetHandler none
  SetHandler default-handler
  Options -ExecCGI
  RemoveHandler .cgi .php .php3 .php4 .php5 .phtml .pl .py .pyc .pyo
</Files>
<IfModule mod_php5.c>
  php_flag engine off
</IfModule>
# END CRM Perks
XML;

$files = array(
            array(
                'base'         => $upload_dir['basedir'].'/'.cfx_form::$upload_folder,
                'file'         => 'index.html',
                'content'     => ''
            ),
             array(
                'base'         => $upload_dir['basedir'].'/'.cfx_form::$upload_folder,
                'file'         => '.htaccess',
                'content'     => $htaccess
            ),
             array(
                'base'         => $upload_dir['basedir'].'/'.$folder,
                'file'         => 'index.html',
                'content'     => ''
            )
        );

foreach ( $files as $file ) {
if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
                if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
                    fwrite( $file_handle, $file['content'] );
                    fclose( $file_handle );
                }
            }
        }
}

}

endif;
