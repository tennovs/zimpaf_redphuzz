<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Role')) {
	/**
	 * Class ERE_Role
	 */
	class ERE_Role
	{
		/**
		 * Create roles and capabilities.
		 */
		public static function create_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			// Customer role
			add_role( 'ere_customer', esc_html__( 'ERE Customer', 'essential-real-estate' ), array(
				'read' 					=> true
			) );

			$capabilities = self::get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}

		/**
		 * get_core_capabilities
		 * @return array
		 */
		private static function get_core_capabilities() {
			$capabilities = array();

			$capabilities['core'] = array(
				'manage_real_estate'
			);

			$capability_types = array( 'property', 'agent','package','user_package','invoice','trans_log');

			foreach ( $capability_types as $capability_type ) {

				$capabilities[ $capability_type ] = array(
					// Post type
					"edit_{$capability_type}",
					"read_{$capability_type}",
					"delete_{$capability_type}",
					"edit_{$capability_type}s",
					"edit_others_{$capability_type}s",
					"publish_{$capability_type}s",
					"read_private_{$capability_type}s",
					"delete_{$capability_type}s",
					"delete_private_{$capability_type}s",
					"delete_published_{$capability_type}s",
					"delete_others_{$capability_type}s",
					"edit_private_{$capability_type}s",
					"edit_published_{$capability_type}s",

					// Terms
					"manage_{$capability_type}_terms",
					"edit_{$capability_type}_terms",
					"delete_{$capability_type}_terms",
					"assign_{$capability_type}_terms"
				);
			}

			return $capabilities;
		}
		/**
		 * Remove roles
		 */
		public static function remove_roles() {
			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			$capabilities = self::get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}

			remove_role( 'ere_customer' );
		}
	}
}