<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

if (current_user_can('manage_options')) {
	delete_option('dbd_totoplink');
}

?>