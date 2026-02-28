<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Framework')) {
	class GSF_Framework
	{
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance() {
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $metaFields = array();

		/**
		 * @param string $meta_type option | post_meta | term_meta | user_meta
		 * @return array
		 */
		public function getMetaField($meta_type = 'option') {
			return isset($this->metaFields[$meta_type]) ? $this->metaFields[$meta_type] : array();
		}

		public function init() {
			do_action('gsf_before_init');

			spl_autoload_register(array($this, 'incAutoload'));
			spl_autoload_register(array($this, 'fieldsAutoload'));
			$this->includes();
			$this->hook()->init();
			GSF()->assets()->init();
			GSF()->adminMetaBoxes()->init();
			GSF()->adminThemeOption()->init();
			GSF()->adminWidget()->init();
			GSF()->adminTaxonomy()->init();
			GSF()->adminUserMeta()->init();
			$this->customCss()->init();
			$this->content_inject()->init();

			$this->loadFile(GSF()->pluginDir('core/icons-popup/icons-popup.class.php'));
			GSF_Core_Icons_Popup::getInstance()->init();

			$this->loadFile(GSF()->pluginDir('core/fonts/fonts.class.php'));
			GSF_Core_Fonts::getInstance()->init();
			$this->load_textdomain();

			do_action('gsf_init');
		}


		/**
		 * Inc library auto loader
		 *
		 * @param $class
		 */
		public function incAutoload($class) {
			$file_name = preg_replace('/^GSF_Inc_/', '', $class);
			if ($file_name !== $class) {
				$file_name = strtolower($file_name);
				$file_name = str_replace('_', '-', $file_name);
				$this->loadFile(GSF()->pluginDir("inc/{$file_name}.class.php"));
			}
		}

		/**
		 * Field auto loader
		 * @param $class
		 */
		public function fieldsAutoload($class) {
			$file_name = preg_replace('/^GSF_Field_/', '', $class);
			if ($file_name !== $class) {
				$file_name = strtolower($file_name);
				$this->loadFile(GSF()->pluginDir("fields/{$file_name}/{$file_name}.class.php"));
			}
		}

		public function loadFile($path) {
			if ($path && is_readable($path)) {
				include_once($path);
				return true;
			}
			return false;
		}

		/**
		 * Include library
		 */
		private function includes() {
			require_once GSF()->pluginDir('fields/field.php');
		}

		public function pluginVer() {
			return '2.4';
		}

		/**
		 *
		 * @param string $path
		 * @return string
		 */
		public function pluginUrl($path = '') {
			return trailingslashit(GSF_PLUGIN_URI) . $path;
		}

		/**
		 * Get Plugin Dir
		 *
		 * @param string $path
		 * @return string
		 */
		public function pluginDir($path = '') {
			return plugin_dir_path(__FILE__) . $path;
		}

		public function assetsHandle($handle = '') {
			return apply_filters('gsf_assets_prefix', 'gsf_') . $handle;
		}

		public function load_textdomain() {
			$text_domain_file = GSF()->pluginDir() . 'languages/smart-framework-' . get_locale() . '.mo';
			if (is_readable($text_domain_file)) {
				load_textdomain('smart-framework', $text_domain_file);
			}
			load_plugin_textdomain('smart-framework', false, GSF()->pluginDir() . 'languages');

			$loco_settings_key = '';
			if (defined('GSF_PLUGIN_OWNER_FILE')) {
				$loco_settings_key = 'loco_plugin_config__' . plugin_basename(GSF_PLUGIN_OWNER_FILE);
			}

			if ($loco_settings_key !== '') {
				$loca_config = get_option($loco_settings_key);
				if (isset($loca_config['d'][2]) && is_array($loca_config['d'][2])) {
					$plugin_configs = $loca_config['d'][2];
					$is_exists_text_domain = false;
					foreach ($plugin_configs as $p) {
						if (!isset($p[0]) || !isset($p[1]) || !isset($p[1]['name'])) {
							continue;
						}
						if (($p[0] === 'domain') && ($p[1]['name'] === 'smart-framework')) {
							$is_exists_text_domain = true;
						}
					}
					if (!$is_exists_text_domain) {
						array_push($loca_config['d'][2], array(
							0 => 'domain',
							1 => array(
								'name' => 'smart-framework',
							),
							2 => array(
								0 => array(
									0 => 'project',
									1 => array(
										'name' => esc_html__('Smart Framework', 'smart-framework'),
										'slug' => 'smart-framework',
									),
									2 => array(
										0 => array(
											0 => 'source',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'directory',
													1 => array(),
													2 => array(''),
												),
											),
										),
										1 => array(
											0 => 'target',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'directory',
													1 => array(),
													2 => array('lib/smart-framework/languages'),
												),
											),
										),
										2 => array(
											0 => 'template',
											1 => array(),
											2 => array(
												0 => array(
													0 => 'file',
													1 => array(),
													2 => array('lib/smart-framework/languages/smart-framework.pot'),
												),
											),
										),
									),
								),
							),
						));
						update_option($loco_settings_key, $loca_config);
					}

				}
			}
		}

		/**
		 * @return GSF_Inc_Hook
		 */
		public function hook() {
			return GSF_Inc_Hook::getInstance();
		}

		/**
		 * GSF helper function
		 * @return GSF_Inc_Helper
		 */
		public function helper() {
			return GSF_Inc_Helper::getInstance();
		}

		/**
		 * @return GSF_Inc_Custom_Css
		 */
		public function customCss() {
			return GSF_Inc_Custom_Css::getInstance();
		}

		/**
		 * GSF Assets
		 *
		 * @return GSF_Inc_Assets
		 */
		public function assets() {
			return GSF_Inc_Assets::getInstance();
		}

		/**
		 * GSF ajax
		 * @return GSF_Inc_Admin_Ajax
		 */
		public function adminAjax() {
			return GSF_Inc_Admin_Ajax::getInstance();
		}


		/**
		 * GSF Theme Options
		 * @return GSF_Inc_Admin_Theme_Options
		 */
		public function adminThemeOption() {
			return GSF_Inc_Admin_Theme_Options::getInstance();
		}

		/**
		 * GSF Meta Boxes
		 * @return GSF_Inc_Admin_Meta_Boxes
		 */
		public function adminMetaBoxes() {
			return GSF_Inc_Admin_Meta_Boxes::getInstance();
		}

		/**
		 * Widget Loader
		 *
		 * @return GSF_Inc_Admin_Widget
		 */
		public function adminWidget() {
			return GSF_Inc_Admin_Widget::getInstance();
		}

		/**
		 * GSF Taxonomy
		 * @return GSF_Inc_Admin_Taxonomy
		 */
		public function adminTaxonomy() {
			return GSF_Inc_Admin_Taxonomy::getInstance();
		}

		/**
		 * GSF User Meta
		 * @return GSF_Inc_Admin_User_Meta
		 */
		public function adminUserMeta() {
			return GSF_Inc_Admin_User_Meta::getInstance();
		}

		/**
		 * @return GSF_Inc_File
		 */
		public function file() {
			return GSF_Inc_File::getInstance();
		}

		/**
		 * @return GSF_Inc_Content_Inject
		 */
		public function content_inject() {
			return GSF_Inc_Content_Inject::getInstance();
		}
	}

	/**
	 * @return GSF_Framework
	 */
	function GSF() {
		return GSF_Framework::getInstance();
	}

	/**
	 * Init Smart Framework
	 */
	GSF()->init();
}