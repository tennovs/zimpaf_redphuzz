<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Widget')) {
	class GSF_Widget extends WP_Widget
	{
		public $widget_cssclass;
		public $widget_description;
		public $widget_id;
		public $widget_name;
		public $settings = array();

		public function __construct(){
			$widget_ops = array(
				'classname'   => $this->widget_cssclass,
				'description' => $this->widget_description
			);
			parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );
		}
		function get_cached_widget( $instance ) {
			$cache = wp_cache_get( $this->widget_id, 'widget' );
			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			$cache_key = $this->get_cache_key($instance);

			if ( isset( $cache[ $cache_key ] ) ) {
				echo ($cache[ $cache_key ]);
				return true;
			}

			return false;
		}

		public function get_cache_key($instance) {
		    if (! is_array($instance)) {
                $instance = array();
            }
            asort($instance);
            return md5(wp_json_encode($instance));

        }


		public function cache_widget( $instance, $content ) {
			$cache = wp_cache_get( $this->widget_id, 'widget' );
            if ( ! is_array( $cache ) ) {
                $cache = array();
            }

			$cache_key = $this->get_cache_key($instance);

			$cache[ $cache_key ] = $content;

			wp_cache_set( $this->widget_id, $cache, 'widget' );

            return $content;
		}

		/**
		 * Flush the cache
		 *
		 * @return void
		 */
		public function flush_widget_cache() {
			wp_cache_delete( $this->widget_id, 'widget' );
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			if ( ! $this->settings ) {
				return $instance;
			}

			$config_keys = GSF()->helper()->getConfigKeys($this->settings);

			foreach ($config_keys as $key => $field_meta ) {
				if ( isset( $new_instance[ $key ] ) ) {
					if ( current_user_can('unfiltered_html') ) {
						$instance[$key] =  $new_instance[$key];
					}
					else {
						$instance[$key] = stripslashes( wp_filter_post_kses( addslashes($new_instance[$key]) ) );
					}
				}
				else {
					$instance[$key] = $field_meta['empty_value'];
				}
			}

			$this->flush_widget_cache();

			return $instance;
		}

		/**
		 * Render widget form
		 *
		 * @param array $instance
		 * @return string|void
		 */
		public function form( $instance ) {

			if (!empty($_POST['gsf_group_status'])) {
				foreach ( $this->settings['fields'] as $idx => $field ) {
					if ( $field['type'] === 'group' && ! empty( $field['id'] ) ) {
						$id = &$field['id'];
						if ( ! empty( $_POST['gsf_group_status'][ $id ] ) ) {
							$this->settings['fields'][ $idx ]['toggle_default'] = $_POST['gsf_group_status'][ $id ] === 'open' ? true : false;
						}
					}
				}
			}

			GSF()->helper()->setFieldPrefix('widget-' . $this->id_base . '[' . $this->number . ']');
			GSF()->helper()->setFieldLayout(isset($this->settings['layout']) ? $this->settings['layout'] : 'full');
			GSF()->helper()->renderFields($this->settings, $instance);
			GSF()->helper()->setFieldPrefix('');
		}

		public function widget($args, $instance) {}

		public function widget_start( $args, $instance, $custom_class = null ) {

			if ($custom_class !== null) {
				if (is_array($custom_class)) {
					$custom_class = implode(' ', $custom_class);
				}
			}

			if (!empty($custom_class)) {
				$custom_class = 'class="' . $custom_class . ' ';
				$args['before_widget'] = str_replace(
					'class="',
					$custom_class,
					$args['before_widget']
				);
			}


			echo $args['before_widget'];


			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
		}

		public function widget_end( $args ) {
			echo $args['after_widget'];
		}
	}
}