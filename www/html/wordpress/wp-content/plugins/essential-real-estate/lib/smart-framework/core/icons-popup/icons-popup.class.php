<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('GSF_Core_Icons_Popup')) {
	final class GSF_Core_Icons_Popup
	{
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {

			add_action('init',array($this,'register_icon_font'),1);

			add_action('wp_enqueue_scripts',array($this,'enqueue_icon_font'));

			add_action('admin_footer', array($this, 'icon_svg_template'));
			add_action('wp_footer', array($this, 'icon_svg_template'));
		}

		public function enqueue()
		{
			wp_enqueue_media();

			/**
			 * Enqueue Icon Font Css
			 */
			$this->enqueue_icon_font();
			wp_enqueue_style(GSF()->assetsHandle('icons-popup'), GSF()->helper()->getAssetUrl('core/icons-popup/assets/icons-popup.min.css'), array('magnific-popup','perfect-scrollbar'), GSF()->pluginVer());
			wp_enqueue_script(GSF()->assetsHandle('icons-popup'), GSF()->helper()->getAssetUrl('core/icons-popup/assets/icons-popup.min.js'), array('jquery','magnific-popup','perfect-scrollbar'), GSF()->pluginVer(), true);
			wp_localize_script(GSF()->assetsHandle('icons-popup'), 'GSF_POPUP_DATA', array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'nonce'   => GSF()->helper()->getNonceValue(),
			));
			add_action('admin_footer', array($this, 'popup_template'), 1000);
		}

		public function popup_template() {
			GSF()->helper()->getTemplate('core/icons-popup/templates/icons-popup.tpl');
		}

		public function get_icon_font_resources() {
			$icon_font_css = apply_filters('gsf_font_icon_assets', array(
				'font-awesome' => array(
					'url' => GSF()->helper()->getAssetUrl('assets/vendors/font-awesome/css/font-awesome.min.css'),
					'ver' => '4.7.0',
					'deps' => array(),
				)
			));
			return $icon_font_css;
		}

		public function enqueue_icon_font() {
			$icon_font_css = $this->get_icon_font_resources();
			foreach ($icon_font_css as $font_key => $font_value) {
				wp_enqueue_style($font_key);
			}
		}

		public function register_icon_font() {
			$icon_font_css = $this->get_icon_font_resources();
			foreach ($icon_font_css as $font_key => $font_value) {
				wp_register_style($font_key, $font_value['url'], isset($font_value['deps']) ? $font_value['deps'] : array(), $font_value['ver']);
			}
		}

		public function icon_svg_template() {
			GSF()->helper()->getTemplate('core/icons-popup/templates/icons-svg.tpl');
		}
	}
}