<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Inc_Admin_Widget')) {
	class GSF_Inc_Admin_Widget
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			$this->includes();

			spl_autoload_register(array($this, 'widgetsAutoload'));
			add_action( 'load-widgets.php', array( $this, 'enqueueResources' ), 100 );
		}

		public function includes() {
			include_once GSF()->pluginDir('widgets/widget.php');
		}
		public function enqueueResources() {
			wp_enqueue_media();
			wp_enqueue_style(GSF()->assetsHandle('fields'));
			wp_enqueue_style(GSF()->assetsHandle('widget'));

			wp_enqueue_script(GSF()->assetsHandle('fields'));
			wp_enqueue_script(GSF()->assetsHandle('widget'));
			wp_localize_script(GSF()->assetsHandle('fields'), 'GSF_META_DATA', array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => GSF()->helper()->getNonceValue(),
			));
		}

		/**
		 * Widget auto loader
		 *
		 * @param $class
		 */
		public function widgetsAutoload($class)
		{
			$file_name = preg_replace('/^GSF_Widget_/', '', $class);
			if ($file_name !== $class) {
				$file_name = strtolower($file_name);
				$file_name = str_replace('_', '-', $file_name);
                GSF()->loadFile(GSF()->pluginDir("widgets/{$file_name}.class.php"));
			}
		}
	}
}