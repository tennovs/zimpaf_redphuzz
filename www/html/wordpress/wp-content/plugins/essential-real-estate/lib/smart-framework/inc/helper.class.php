<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('GSF_Inc_Helper')) {
	class GSF_Inc_Helper
	{
		private static $_instance;

		public static function getInstance() {
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Create field object from field type
		 *
		 * @param $type
		 * @return GSF_Field
		 */
		public function createField($type) {
			$class_name = str_replace('_', ' ', $type);
			$class_name = ucwords($class_name);
			$class_name = str_replace(' ', '_', $class_name);
			$class_name = 'GSF_Field_' . $class_name;
			if (class_exists($class_name)) {
				return new $class_name();
			}
			return null;
		}

		public function setFieldPrefix($prefix) {
			$GLOBALS['gsf_field_prefix'] = $prefix;
		}

		public function getFieldPrefix() {
			return isset($GLOBALS['gsf_field_prefix']) ? $GLOBALS['gsf_field_prefix'] : '';
		}

		/**
		 * Set field layout
		 * @param $layout
		 */
		public function setFieldLayout($layout) {
			if (!in_array($layout, array('inline', 'full'))) {
				$layout = 'inline';
			}
			$GLOBALS['gsf_field_layout'] = $layout;
		}

		/**
		 * Get field layout
		 * @return string
		 */
		public function getFieldLayout() {
			if (!isset($GLOBALS['gsf_field_layout'])) {
				$GLOBALS['gsf_field_layout'] = 'inline';
			}
			return $GLOBALS['gsf_field_layout'];
		}

		/**
		 * Get template
		 * @param $slug
		 * @param $args
		 */
		public function getTemplate($slug, $args = array()) {
			if ($args && is_array($args)) {
				extract($args);
			}
			$located = GSF()->pluginDir($slug . '.php');
			if (!file_exists($located)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $slug), '1.0');

				return;
			}
			include($located);
		}

		/**
		 * Get plugin assets url
		 * @param $file
		 * @return string
		 */
		public function getAssetUrl($file) {
			if (!file_exists(GSF()->pluginDir($file)) || (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG)) {
				$ext = explode('.', $file);
				$ext = end($ext);
				$normal_file = preg_replace('/((\.min\.css)|(\.min\.js))$/', '', $file);
				if ($normal_file != $file) {
					$normal_file = untrailingslashit($normal_file) . ".{$ext}";
					if (file_exists(GSF()->pluginDir($normal_file))) {
						return GSF()->pluginUrl(untrailingslashit($normal_file));
					}
				}
			}
			return GSF()->pluginUrl(untrailingslashit($file));
		}

		public function renderFields(&$config, &$values, $current_preset = '') {
			$list_section = array();
			if (isset($config['section'])) {
				foreach ($config['section'] as &$section) {
					$list_section[] = array(
						'id'    => $section['id'],
						'title' => $section['title'],
						'icon'  => isset($section['icon']) ? $section['icon'] : 'dashicons-admin-generic',
					);
				}
			}
			$this->getTemplate('admin/templates/meta-start', array(
				'list_section'   => $list_section,
				'current_preset' => $current_preset
			));

			if (!empty($config)) {
				if (isset($config['section'])) {
					?>
					<?php if (GSF()->adminThemeOption()->is_theme_option_page): ?>
						<?php
						$section_current_id = isset($_GET['section']) ? $_GET['section'] : '';
						if ($section_current_id === '') {
							$section_current_id = array_keys($config['section']);
							$section_current_id = $section_current_id[0];
						}
						?>
						<?php foreach ($config['section'] as &$section): ?>
							<?php if ($section_current_id === $section['id']): ?>
								<div id="section_<?php echo esc_attr($section['id']) ?>" class="gsf-section-container">
									<h4 class="gsf-section-title">
										<i class="gsf-section-title-icon <?php echo isset($section['icon']) ? esc_attr($section['icon']) : 'dashicons dashicons-admin-generic'; ?>"></i>
										<span><?php echo esc_html($section['title']); ?></span>
										<span class="gsf-section-title-toggle"></span>
									</h4>
									<div class="gsf-section-inner">
										<?php if (isset($section['fields'])): ?>
											<?php $this->renderSubFields($section['fields'], $values) ?>
										<?php endif; ?>
									</div>
								</div><!-- /.gsf-section-container  -->
								<?php break; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else: ?>
						<?php foreach ($config['section'] as &$section): ?>
							<div id="section_<?php echo esc_attr($section['id']) ?>" class="gsf-section-container">
								<h4 class="gsf-section-title">
									<i class="gsf-section-title-icon <?php echo isset($section['icon']) ? esc_attr($section['icon']) : 'dashicons dashicons-admin-generic'; ?>"></i>
									<span><?php echo esc_html($section['title']); ?></span>
									<span class="gsf-section-title-toggle"></span>
								</h4>
								<div class="gsf-section-inner">
									<?php if (isset($section['fields'])): ?>
										<?php $this->renderSubFields($section['fields'], $values) ?>
									<?php endif; ?>
								</div>
							</div><!-- /.gsf-section-container  -->
						<?php endforeach; ?>
					<?php endif; ?>
					<?php
				} else {
					$this->renderSubFields($config['fields'], $values);
				}
			}

			$this->getTemplate('admin/templates/meta-end');
		}

		public function renderSubFields(&$fields, &$values) {
			foreach ($fields as &$config) {
				$type = isset($config['type']) ? $config['type'] : '';
				if (empty($type)) {
					continue;
				}
				$id = isset($config['id']) ? $config['id'] : '';
				$field = $this->createField($config['type']);
				$field->_setting = &$config;
				if (in_array($type, array('group', 'row'))) {
					$field->_value = $values;
				} else {
					if (!empty($id)) {
						$field->_value = isset($values[$id]) ? $values[$id] : null;
					} else {
						$field->_value = null;
					}
				}

				$field->render();
			}
		}

		/**
		 * Get Config Keys
		 *
		 * @param $configs
		 * @param $current_section
		 * @return array
		 */
		public function getConfigKeys(&$configs, $current_section = '') {
			$field_keys = array();
			if (isset($configs['section'])) {
				if (!empty($current_section) && isset($configs['section'][$current_section])) {
					$field_keys = array_merge($field_keys, $this->getConfigFieldKeys($configs['section'][$current_section]['fields']));
				} else {
					foreach ($configs['section'] as $section) {
						if (isset($section['fields'])) {
							$field_keys = array_merge($field_keys, $this->getConfigFieldKeys($section['fields']));
						}
					}
				}
			} else {
				if (isset($configs['fields'])) {
					$field_keys = array_merge($field_keys, $this->getConfigFieldKeys($configs['fields']));
				}
			}
			return $field_keys;
		}

		private function getConfigFieldKeys(&$fields) {
			$field_keys = array();
			foreach ($fields as $field) {
				if (!isset($field['type'])) {
					continue;
				}
				$field_type = $field['type'];

				switch ($field_type) {
					case 'row':
					case 'group':
						$field_keys = array_merge($field_keys, $this->getConfigFieldKeys($field['fields']));
						break;
					default:
						if (!isset($field['id'])) {
							break;
						}
						$field_obj = $this->createField($field_type);
						$field_obj->_setting = $field;
						$field_keys[$field['id']] = array(
							'type'        => $field_type,
							'empty_value' => $field_obj->getEmptyValue()
						);
						break;
				}
			}
			return $field_keys;
		}

		public function getConfigDefault(&$configs, $current_section = '') {
			$field_default = array();
			if (!empty($current_section)) {
				if (isset($configs['section'])) {
					foreach ($configs['section'] as $section) {
						if ('section_' . $section['id'] == $current_section) {
							if (isset($section['fields'])) {
								$field_default = array_merge($field_default, $this->getConfigDefaultField($section['fields']));
							}
						}
					}
				}
			} else {
				if (isset($configs['section'])) {
					foreach ($configs['section'] as $section) {
						if (isset($section['fields'])) {
							$field_default = array_merge($field_default, $this->getConfigDefaultField($section['fields']));
						}
					}
				} else {
					if (isset($configs['fields'])) {
						$field_default = array_merge($field_default, $this->getConfigDefaultField($configs['fields']));
					}
				}
			}
			return $field_default;
		}

		private function getConfigDefaultField(&$fields) {
			$field_default = array();
			foreach ($fields as $field) {
				if (!isset($field['type'])) {
					continue;
				}
				$field_type = $field['type'];

				switch ($field_type) {
					case 'row':
					case 'group':
						$field_default = array_merge($field_default, $this->getConfigDefaultField($field['fields']));
						break;
					default:
						if (!isset($field['id'])) {
							break;
						}
						$field_obj = $this->createField($field_type);
						$field_obj->_setting = $field;
						$field_default[$field['id']] = $field_obj->getFieldDefault();
						break;
				}
			}
			return $field_default;
		}

		/**
		 * Get list sidebars
		 *
		 * @return array
		 */
		public function getSidebars() {
			$sidebars = array();
			if (is_array($GLOBALS['wp_registered_sidebars'])) {
				foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
					$sidebars[$sidebar['id']] = ucwords($sidebar['name']);
				}
			}
			return $sidebars;
		}

		/**
		 * Get listing menu
		 *
		 * @return array
		 */
		public function getMenus() {
			$user_menus = get_categories(array(
				'taxonomy'   => 'nav_menu',
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC'
			));
			$menus = array();
			foreach ($user_menus as $menu) {
				$menus[$menu->term_id] = $menu->name;
			}

			return $menus;
		}

		/**
		 * Get listing taxonomies
		 *
		 * @param array $params
		 * @return array
		 */
		public function getTaxonomies($params = array()) {
			$args = array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			);
			if (!empty($params)) {
				$args = wp_parse_args($params, $args);
			}

			$categories = get_categories($args);
			$taxs = array();
			foreach ($categories as $cate) {
				$taxs[$cate->term_id] = $cate->name;
			}

			return $taxs;
		}

		/**
		 * Get listing post
		 *
		 * @param array $params
		 * @return array
		 */
		public function getPosts($params = array()) {
			$args = array(
				'numberposts' => 20,
				'orderby'     => 'post_title',
				'order'       => 'ASC',
			);
			if (!empty($params)) {
				$args = array_merge($args, $params);
			}
			$posts = get_posts($args);
			$ret_posts = array();
			foreach ($posts as $post) {
				$ret_posts[$post->ID] = $post->post_title;
			}

			return $ret_posts;
		}

		/**
		 * Render selected attribute
		 *
		 * @param $value
		 * @param $current
		 */
		public function theSelected($value, $current) {
			echo ((is_array($current) && in_array($value, $current)) || (!is_array($current) && ($value == $current))) ? 'selected="selected"' : '';
		}

		/**
		 * Render checked attribute
		 *
		 * @param $value
		 * @param $current
		 */
		public function theChecked($value, $current) {
			echo ((is_array($current) && in_array($value, $current)) || (!is_array($current) && ($value == $current))) ? 'checked="checked"' : '';
		}

		/**
		 * Get attachment id by url
		 *
		 * @param $url
		 * @return int
		 */
		public function getAttachmentIdByUrl($url) {
			global $wpdb;
			$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url));
			if (!empty($attachment)) {
				return $attachment[0];
			}

			return 0;
		}

		/**
		 * Get framework nonce verify key
		 * @return mixed|void
		 */
		public function getNonceVerifyKey() {
			return apply_filters('gsf_nonce_verify_key', 'GSF_SMART_FRAMEWORK_VERIFY');
		}

		/**
		 * Get framework nonce value
		 * @return string
		 */
		public function getNonceValue() {
			return wp_create_nonce($this->getNonceVerifyKey());
		}

		public function getFontIcons() {
			return apply_filters('gsf_font_icon_config', array(
				'font-awesome' => array(
					'label' => esc_html__('Font Awesome', 'smart-framework'),
					'total' => '786',
					'iconGroup' => json_decode('[{"id":"all","title":"All Fonts","icons":["fa fa-glass","fa fa-music","fa fa-search","fa fa-envelope-o","fa fa-heart","fa fa-star","fa fa-star-o","fa fa-user","fa fa-film","fa fa-th-large","fa fa-th","fa fa-th-list","fa fa-check","fa fa-remove","fa fa-close","fa fa-times","fa fa-search-plus","fa fa-search-minus","fa fa-power-off","fa fa-signal","fa fa-gear","fa fa-cog","fa fa-trash-o","fa fa-home","fa fa-file-o","fa fa-clock-o","fa fa-road","fa fa-download","fa fa-arrow-circle-o-down","fa fa-arrow-circle-o-up","fa fa-inbox","fa fa-play-circle-o","fa fa-rotate-right","fa fa-repeat","fa fa-refresh","fa fa-list-alt","fa fa-lock","fa fa-flag","fa fa-headphones","fa fa-volume-off","fa fa-volume-down","fa fa-volume-up","fa fa-qrcode","fa fa-barcode","fa fa-tag","fa fa-tags","fa fa-book","fa fa-bookmark","fa fa-print","fa fa-camera","fa fa-font","fa fa-bold","fa fa-italic","fa fa-text-height","fa fa-text-width","fa fa-align-left","fa fa-align-center","fa fa-align-right","fa fa-align-justify","fa fa-list","fa fa-dedent","fa fa-outdent","fa fa-indent","fa fa-video-camera","fa fa-photo","fa fa-image","fa fa-picture-o","fa fa-pencil","fa fa-map-marker","fa fa-adjust","fa fa-tint","fa fa-edit","fa fa-pencil-square-o","fa fa-share-square-o","fa fa-check-square-o","fa fa-arrows","fa fa-step-backward","fa fa-fast-backward","fa fa-backward","fa fa-play","fa fa-pause","fa fa-stop","fa fa-forward","fa fa-fast-forward","fa fa-step-forward","fa fa-eject","fa fa-chevron-left","fa fa-chevron-right","fa fa-plus-circle","fa fa-minus-circle","fa fa-times-circle","fa fa-check-circle","fa fa-question-circle","fa fa-info-circle","fa fa-crosshairs","fa fa-times-circle-o","fa fa-check-circle-o","fa fa-ban","fa fa-arrow-left","fa fa-arrow-right","fa fa-arrow-up","fa fa-arrow-down","fa fa-mail-forward","fa fa-share","fa fa-expand","fa fa-compress","fa fa-plus","fa fa-minus","fa fa-asterisk","fa fa-exclamation-circle","fa fa-gift","fa fa-leaf","fa fa-fire","fa fa-eye","fa fa-eye-slash","fa fa-warning","fa fa-exclamation-triangle","fa fa-plane","fa fa-calendar","fa fa-random","fa fa-comment","fa fa-magnet","fa fa-chevron-up","fa fa-chevron-down","fa fa-retweet","fa fa-shopping-cart","fa fa-folder","fa fa-folder-open","fa fa-arrows-v","fa fa-arrows-h","fa fa-bar-chart-o","fa fa-bar-chart","fa fa-twitter-square","fa fa-facebook-square","fa fa-camera-retro","fa fa-key","fa fa-gears","fa fa-cogs","fa fa-comments","fa fa-thumbs-o-up","fa fa-thumbs-o-down","fa fa-star-half","fa fa-heart-o","fa fa-sign-out","fa fa-linkedin-square","fa fa-thumb-tack","fa fa-external-link","fa fa-sign-in","fa fa-trophy","fa fa-github-square","fa fa-upload","fa fa-lemon-o","fa fa-phone","fa fa-square-o","fa fa-bookmark-o","fa fa-phone-square","fa fa-twitter","fa fa-facebook-f","fa fa-facebook","fa fa-github","fa fa-unlock","fa fa-credit-card","fa fa-feed","fa fa-rss","fa fa-hdd-o","fa fa-bullhorn","fa fa-bell","fa fa-certificate","fa fa-hand-o-right","fa fa-hand-o-left","fa fa-hand-o-up","fa fa-hand-o-down","fa fa-arrow-circle-left","fa fa-arrow-circle-right","fa fa-arrow-circle-up","fa fa-arrow-circle-down","fa fa-globe","fa fa-wrench","fa fa-tasks","fa fa-filter","fa fa-briefcase","fa fa-arrows-alt","fa fa-group","fa fa-users","fa fa-chain","fa fa-link","fa fa-cloud","fa fa-flask","fa fa-cut","fa fa-scissors","fa fa-copy","fa fa-files-o","fa fa-paperclip","fa fa-save","fa fa-floppy-o","fa fa-square","fa fa-navicon","fa fa-reorder","fa fa-bars","fa fa-list-ul","fa fa-list-ol","fa fa-strikethrough","fa fa-underline","fa fa-table","fa fa-magic","fa fa-truck","fa fa-pinterest","fa fa-pinterest-square","fa fa-google-plus-square","fa fa-google-plus","fa fa-money","fa fa-caret-down","fa fa-caret-up","fa fa-caret-left","fa fa-caret-right","fa fa-columns","fa fa-unsorted","fa fa-sort","fa fa-sort-down","fa fa-sort-desc","fa fa-sort-up","fa fa-sort-asc","fa fa-envelope","fa fa-linkedin","fa fa-rotate-left","fa fa-undo","fa fa-legal","fa fa-gavel","fa fa-dashboard","fa fa-tachometer","fa fa-comment-o","fa fa-comments-o","fa fa-flash","fa fa-bolt","fa fa-sitemap","fa fa-umbrella","fa fa-paste","fa fa-clipboard","fa fa-lightbulb-o","fa fa-exchange","fa fa-cloud-download","fa fa-cloud-upload","fa fa-user-md","fa fa-stethoscope","fa fa-suitcase","fa fa-bell-o","fa fa-coffee","fa fa-cutlery","fa fa-file-text-o","fa fa-building-o","fa fa-hospital-o","fa fa-ambulance","fa fa-medkit","fa fa-fighter-jet","fa fa-beer","fa fa-h-square","fa fa-plus-square","fa fa-angle-double-left","fa fa-angle-double-right","fa fa-angle-double-up","fa fa-angle-double-down","fa fa-angle-left","fa fa-angle-right","fa fa-angle-up","fa fa-angle-down","fa fa-desktop","fa fa-laptop","fa fa-tablet","fa fa-mobile-phone","fa fa-mobile","fa fa-circle-o","fa fa-quote-left","fa fa-quote-right","fa fa-spinner","fa fa-circle","fa fa-mail-reply","fa fa-reply","fa fa-github-alt","fa fa-folder-o","fa fa-folder-open-o","fa fa-smile-o","fa fa-frown-o","fa fa-meh-o","fa fa-gamepad","fa fa-keyboard-o","fa fa-flag-o","fa fa-flag-checkered","fa fa-terminal","fa fa-code","fa fa-mail-reply-all","fa fa-reply-all","fa fa-star-half-empty","fa fa-star-half-full","fa fa-star-half-o","fa fa-location-arrow","fa fa-crop","fa fa-code-fork","fa fa-unlink","fa fa-chain-broken","fa fa-question","fa fa-info","fa fa-exclamation","fa fa-superscript","fa fa-subscript","fa fa-eraser","fa fa-puzzle-piece","fa fa-microphone","fa fa-microphone-slash","fa fa-shield","fa fa-calendar-o","fa fa-fire-extinguisher","fa fa-rocket","fa fa-maxcdn","fa fa-chevron-circle-left","fa fa-chevron-circle-right","fa fa-chevron-circle-up","fa fa-chevron-circle-down","fa fa-html5","fa fa-css3","fa fa-anchor","fa fa-unlock-alt","fa fa-bullseye","fa fa-ellipsis-h","fa fa-ellipsis-v","fa fa-rss-square","fa fa-play-circle","fa fa-ticket","fa fa-minus-square","fa fa-minus-square-o","fa fa-level-up","fa fa-level-down","fa fa-check-square","fa fa-pencil-square","fa fa-external-link-square","fa fa-share-square","fa fa-compass","fa fa-toggle-down","fa fa-caret-square-o-down","fa fa-toggle-up","fa fa-caret-square-o-up","fa fa-toggle-right","fa fa-caret-square-o-right","fa fa-euro","fa fa-eur","fa fa-gbp","fa fa-dollar","fa fa-usd","fa fa-rupee","fa fa-inr","fa fa-cny","fa fa-rmb","fa fa-yen","fa fa-jpy","fa fa-ruble","fa fa-rouble","fa fa-rub","fa fa-won","fa fa-krw","fa fa-bitcoin","fa fa-btc","fa fa-file","fa fa-file-text","fa fa-sort-alpha-asc","fa fa-sort-alpha-desc","fa fa-sort-amount-asc","fa fa-sort-amount-desc","fa fa-sort-numeric-asc","fa fa-sort-numeric-desc","fa fa-thumbs-up","fa fa-thumbs-down","fa fa-youtube-square","fa fa-youtube","fa fa-xing","fa fa-xing-square","fa fa-youtube-play","fa fa-dropbox","fa fa-stack-overflow","fa fa-instagram","fa fa-flickr","fa fa-adn","fa fa-bitbucket","fa fa-bitbucket-square","fa fa-tumblr","fa fa-tumblr-square","fa fa-long-arrow-down","fa fa-long-arrow-up","fa fa-long-arrow-left","fa fa-long-arrow-right","fa fa-apple","fa fa-windows","fa fa-android","fa fa-linux","fa fa-dribbble","fa fa-skype","fa fa-foursquare","fa fa-trello","fa fa-female","fa fa-male","fa fa-gittip","fa fa-gratipay","fa fa-sun-o","fa fa-moon-o","fa fa-archive","fa fa-bug","fa fa-vk","fa fa-weibo","fa fa-renren","fa fa-pagelines","fa fa-stack-exchange","fa fa-arrow-circle-o-right","fa fa-arrow-circle-o-left","fa fa-toggle-left","fa fa-caret-square-o-left","fa fa-dot-circle-o","fa fa-wheelchair","fa fa-vimeo-square","fa fa-turkish-lira","fa fa-try","fa fa-plus-square-o","fa fa-space-shuttle","fa fa-slack","fa fa-envelope-square","fa fa-wordpress","fa fa-openid","fa fa-institution","fa fa-bank","fa fa-university","fa fa-mortar-board","fa fa-graduation-cap","fa fa-yahoo","fa fa-google","fa fa-reddit","fa fa-reddit-square","fa fa-stumbleupon-circle","fa fa-stumbleupon","fa fa-delicious","fa fa-digg","fa fa-pied-piper-pp","fa fa-pied-piper-alt","fa fa-drupal","fa fa-joomla","fa fa-language","fa fa-fax","fa fa-building","fa fa-child","fa fa-paw","fa fa-spoon","fa fa-cube","fa fa-cubes","fa fa-behance","fa fa-behance-square","fa fa-steam","fa fa-steam-square","fa fa-recycle","fa fa-automobile","fa fa-car","fa fa-cab","fa fa-taxi","fa fa-tree","fa fa-spotify","fa fa-deviantart","fa fa-soundcloud","fa fa-database","fa fa-file-pdf-o","fa fa-file-word-o","fa fa-file-excel-o","fa fa-file-powerpoint-o","fa fa-file-photo-o","fa fa-file-picture-o","fa fa-file-image-o","fa fa-file-zip-o","fa fa-file-archive-o","fa fa-file-sound-o","fa fa-file-audio-o","fa fa-file-movie-o","fa fa-file-video-o","fa fa-file-code-o","fa fa-vine","fa fa-codepen","fa fa-jsfiddle","fa fa-life-bouy","fa fa-life-buoy","fa fa-life-saver","fa fa-support","fa fa-life-ring","fa fa-circle-o-notch","fa fa-ra","fa fa-resistance","fa fa-rebel","fa fa-ge","fa fa-empire","fa fa-git-square","fa fa-git","fa fa-y-combinator-square","fa fa-yc-square","fa fa-hacker-news","fa fa-tencent-weibo","fa fa-qq","fa fa-wechat","fa fa-weixin","fa fa-send","fa fa-paper-plane","fa fa-send-o","fa fa-paper-plane-o","fa fa-history","fa fa-circle-thin","fa fa-header","fa fa-paragraph","fa fa-sliders","fa fa-share-alt","fa fa-share-alt-square","fa fa-bomb","fa fa-soccer-ball-o","fa fa-futbol-o","fa fa-tty","fa fa-binoculars","fa fa-plug","fa fa-slideshare","fa fa-twitch","fa fa-yelp","fa fa-newspaper-o","fa fa-wifi","fa fa-calculator","fa fa-paypal","fa fa-google-wallet","fa fa-cc-visa","fa fa-cc-mastercard","fa fa-cc-discover","fa fa-cc-amex","fa fa-cc-paypal","fa fa-cc-stripe","fa fa-bell-slash","fa fa-bell-slash-o","fa fa-trash","fa fa-copyright","fa fa-at","fa fa-eyedropper","fa fa-paint-brush","fa fa-birthday-cake","fa fa-area-chart","fa fa-pie-chart","fa fa-line-chart","fa fa-lastfm","fa fa-lastfm-square","fa fa-toggle-off","fa fa-toggle-on","fa fa-bicycle","fa fa-bus","fa fa-ioxhost","fa fa-angellist","fa fa-cc","fa fa-shekel","fa fa-sheqel","fa fa-ils","fa fa-meanpath","fa fa-buysellads","fa fa-connectdevelop","fa fa-dashcube","fa fa-forumbee","fa fa-leanpub","fa fa-sellsy","fa fa-shirtsinbulk","fa fa-simplybuilt","fa fa-skyatlas","fa fa-cart-plus","fa fa-cart-arrow-down","fa fa-diamond","fa fa-ship","fa fa-user-secret","fa fa-motorcycle","fa fa-street-view","fa fa-heartbeat","fa fa-venus","fa fa-mars","fa fa-mercury","fa fa-intersex","fa fa-transgender","fa fa-transgender-alt","fa fa-venus-double","fa fa-mars-double","fa fa-venus-mars","fa fa-mars-stroke","fa fa-mars-stroke-v","fa fa-mars-stroke-h","fa fa-neuter","fa fa-genderless","fa fa-facebook-official","fa fa-pinterest-p","fa fa-whatsapp","fa fa-server","fa fa-user-plus","fa fa-user-times","fa fa-hotel","fa fa-bed","fa fa-viacoin","fa fa-train","fa fa-subway","fa fa-medium","fa fa-yc","fa fa-y-combinator","fa fa-optin-monster","fa fa-opencart","fa fa-expeditedssl","fa fa-battery-4","fa fa-battery","fa fa-battery-full","fa fa-battery-3","fa fa-battery-three-quarters","fa fa-battery-2","fa fa-battery-half","fa fa-battery-1","fa fa-battery-quarter","fa fa-battery-0","fa fa-battery-empty","fa fa-mouse-pointer","fa fa-i-cursor","fa fa-object-group","fa fa-object-ungroup","fa fa-sticky-note","fa fa-sticky-note-o","fa fa-cc-jcb","fa fa-cc-diners-club","fa fa-clone","fa fa-balance-scale","fa fa-hourglass-o","fa fa-hourglass-1","fa fa-hourglass-start","fa fa-hourglass-2","fa fa-hourglass-half","fa fa-hourglass-3","fa fa-hourglass-end","fa fa-hourglass","fa fa-hand-grab-o","fa fa-hand-rock-o","fa fa-hand-stop-o","fa fa-hand-paper-o","fa fa-hand-scissors-o","fa fa-hand-lizard-o","fa fa-hand-spock-o","fa fa-hand-pointer-o","fa fa-hand-peace-o","fa fa-trademark","fa fa-registered","fa fa-creative-commons","fa fa-gg","fa fa-gg-circle","fa fa-tripadvisor","fa fa-odnoklassniki","fa fa-odnoklassniki-square","fa fa-get-pocket","fa fa-wikipedia-w","fa fa-safari","fa fa-chrome","fa fa-firefox","fa fa-opera","fa fa-internet-explorer","fa fa-tv","fa fa-television","fa fa-contao","fa fa-500px","fa fa-amazon","fa fa-calendar-plus-o","fa fa-calendar-minus-o","fa fa-calendar-times-o","fa fa-calendar-check-o","fa fa-industry","fa fa-map-pin","fa fa-map-signs","fa fa-map-o","fa fa-map","fa fa-commenting","fa fa-commenting-o","fa fa-houzz","fa fa-vimeo","fa fa-black-tie","fa fa-fonticons","fa fa-reddit-alien","fa fa-edge","fa fa-credit-card-alt","fa fa-codiepie","fa fa-modx","fa fa-fort-awesome","fa fa-usb","fa fa-product-hunt","fa fa-mixcloud","fa fa-scribd","fa fa-pause-circle","fa fa-pause-circle-o","fa fa-stop-circle","fa fa-stop-circle-o","fa fa-shopping-bag","fa fa-shopping-basket","fa fa-hashtag","fa fa-bluetooth","fa fa-bluetooth-b","fa fa-percent","fa fa-gitlab","fa fa-wpbeginner","fa fa-wpforms","fa fa-envira","fa fa-universal-access","fa fa-wheelchair-alt","fa fa-question-circle-o","fa fa-blind","fa fa-audio-description","fa fa-volume-control-phone","fa fa-braille","fa fa-assistive-listening-systems","fa fa-asl-interpreting","fa fa-american-sign-language-interpreting","fa fa-deafness","fa fa-hard-of-hearing","fa fa-deaf","fa fa-glide","fa fa-glide-g","fa fa-signing","fa fa-sign-language","fa fa-low-vision","fa fa-viadeo","fa fa-viadeo-square","fa fa-snapchat","fa fa-snapchat-ghost","fa fa-snapchat-square","fa fa-pied-piper","fa fa-first-order","fa fa-yoast","fa fa-themeisle","fa fa-google-plus-circle","fa fa-google-plus-official","fa fa-fa","fa fa-font-awesome","fa fa-handshake-o","fa fa-envelope-open","fa fa-envelope-open-o","fa fa-linode","fa fa-address-book","fa fa-address-book-o","fa fa-vcard","fa fa-address-card","fa fa-vcard-o","fa fa-address-card-o","fa fa-user-circle","fa fa-user-circle-o","fa fa-user-o","fa fa-id-badge","fa fa-drivers-license","fa fa-id-card","fa fa-drivers-license-o","fa fa-id-card-o","fa fa-quora","fa fa-free-code-camp","fa fa-telegram","fa fa-thermometer-4","fa fa-thermometer","fa fa-thermometer-full","fa fa-thermometer-3","fa fa-thermometer-three-quarters","fa fa-thermometer-2","fa fa-thermometer-half","fa fa-thermometer-1","fa fa-thermometer-quarter","fa fa-thermometer-0","fa fa-thermometer-empty","fa fa-shower","fa fa-bathtub","fa fa-s15","fa fa-bath","fa fa-podcast","fa fa-window-maximize","fa fa-window-minimize","fa fa-window-restore","fa fa-times-rectangle","fa fa-window-close","fa fa-times-rectangle-o","fa fa-window-close-o","fa fa-bandcamp","fa fa-grav","fa fa-etsy","fa fa-imdb","fa fa-ravelry","fa fa-eercast","fa fa-microchip","fa fa-snowflake-o","fa fa-superpowers","fa fa-wpexplorer","fa fa-meetup"]}]', true)
				),
			));
		}

		public function getFontIconsSvg() {
			return apply_filters('gsf_font_icon_svg', array());
		}

		/**
		 * Get field by field id
		 * @param $fields
		 * @param $id (example: name, contact/address, ...)
		 *
		 * @return null | array
		 */
		public function &getFieldById(&$fields, $id) {
			$currentField = null;
			if (strpos($id, '/') === false) {
				if (isset($fields['fields'][$id])) {
					return $fields['fields'][$id];
				}
				return $currentField;
			}
			$arr_id = explode('/', $id);
			$currentField = &$fields;
			foreach ($arr_id as $key => $id) {
				if (!isset($currentField['fields'][$id])) {
					$currentField = null;
					break;
				}
				$currentField = &$currentField['fields'][$id];
			}
			return $currentField;
		}

		/**
		 * @param $field
		 * @param $fields_added
		 * @param $key - -1: Append Top Array, key: Append after key (if key not exists append to last array)
		 *
		 * @return array
		 */
		function &addFields(&$field, $fields_added, $key) {
			if (!isset($field['fields'])) {
				return $field;
			}
			if ($key === -1) {
				$field['fields'] = array_merge($fields_added, $field['fields']);
				return $field;
			}

			$new_fields = array();
			if (!isset($field['fields'][$key])) {
				$field['fields'] = array_merge($field['fields'], $fields_added);
				return $field;
			}
			foreach ($field['fields'] as $k => $v) {
				$new_fields[$k] = $v;
				if ($key === $k) {
					$new_fields = array_merge($new_fields, $fields_added);
				}
			}
			$field['fields'] = &$new_fields;
			return $field;
		}

		function processConfigsFieldID(&$configs, &$new_array) {
			$new_array = array();
			foreach ($configs as $page => &$page_config) {
				$new_array[$page] = array();
				foreach ($page_config as $k => &$v) {
					if ($k === 'section') {
						$new_array[$page][$k] = array();
						foreach ($v as $section_key => &$section_value) {
							$new_array[$page][$k][$section_value['id']] = &$section_value;
						}
					} else {
						$new_array[$page][$k] = &$v;
					}
				}
			}
		}
	}
}