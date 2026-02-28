<?php
/**
 * Check progress.
 *
 * @since 2.7.7
 * @return void
 */
function usces_item_progress_check(){
	check_ajax_referer( 'wel_progress_check_ajax', 'nonce' );

	$progressfile = wp_unslash( filter_input( INPUT_POST, 'progressfile', FILTER_DEFAULT ) );
	$progressfile = WP_CONTENT_DIR . USCES_UPLOAD_TEMP . '/' . $progressfile;
	if ( file_exists( $progressfile ) ) {
		$text = file_get_contents( $progressfile );
		echo( $text );
	}
	exit;
}
