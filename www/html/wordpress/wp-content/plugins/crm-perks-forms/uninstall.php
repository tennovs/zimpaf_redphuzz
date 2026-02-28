<?php
/**
 * Uninstall
 */
 if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}
$path=plugin_dir_path(__FILE__);
include_once($path . "crm-perks-forms.php");
include_once($path . "includes/install.php");
$install=new cfx_form_install();
$settings=cfx_form::get_meta();
if(!empty($settings['plugin_data'])){
  $install->remove_data();
}
