<?php
$logfile = rawurldecode( $_GET['logfile'] );
// Make sure the file is exist.
if ( file_exists( $logfile ) ) {
	// Get the content and echo it.
	$text = file_get_contents( $logfile );
	echo( $text );
}
exit;
