<?php

require_once(plugin_dir_path( __FILE__ ).'/PieRegPluginSilentUpgraderSkin.php');

/**
 * Skin for on-the-fly addon installations.
 *
 * Extend PieRegPluginSilentUpgraderSkin and clean up the class.
 */
class PieReg_Install_Skin extends PieRegPluginSilentUpgraderSkin {

	/**
	 * Instead of outputting HTML for errors, json_encode the errors and send them
	 * back to the Ajax script for processing.
	 *
	 * @param array $errors Array of errors with the install process.
	 */
	public function error( $errors ) {
		if ( ! empty( $errors ) ) {
			wp_send_json_error( $errors );
		}
	}
}
