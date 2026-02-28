<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
abstract class ERE_Widget extends WP_Widget {

	/**
	 * CSS class.
	 */
	public $widget_cssclass;

	/**
	 * Widget description.
	 */
	public $widget_description;

	/**
	 * Widget ID.
	 */
	public $widget_id;

	/**
	 * Widget name.
	 */
	public $widget_name;

	/**
	 * Settings.
	 */
	public $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => $this->widget_cssclass,
			'description' => $this->widget_description,
			'customize_selective_refresh' => true
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_action( 'load-widgets.php', array( $this, 'enqueue_resources' ), 100 );
	}

	public function enqueue_resources(){
		// base
		wp_enqueue_style('selectize', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/css/selectize.css', array(), '0.12.3');
		wp_enqueue_style('selectize_default', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/css/selectize.default.css', array(), '0.12.3');
		wp_enqueue_script('selectize', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/js/selectize.js', array(), '0.12.3', true);
		wp_enqueue_style(ERE_PLUGIN_PREFIX.'widget', ERE_PLUGIN_URL . 'includes/widgets/assets/css/widget.css', array(), ERE_PLUGIN_VER, 'all');
		wp_enqueue_script(ERE_PLUGIN_PREFIX.'widget',ERE_PLUGIN_URL . 'includes/widgets/assets/js/widget.js',array(),ERE_PLUGIN_VER,true);
	}
	/**
	 * Get cached widget
	 * @param $args
	 * @return bool
	 */
	public function get_cached_widget( $args ) {

		$cache = wp_cache_get( apply_filters( 'ere_cached_widget_id', $this->widget_id ), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo  $cache[ $args['widget_id'] ]; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			return true;
		}

		return false;
	}
	/**
	 * Cache the widget
	 * @param $args
	 * @param $content
	 * @return mixed
	 */
	public function cache_widget( $args, $content ) {
		wp_cache_set( apply_filters( 'ere_cached_widget_id', $this->widget_id ), array( $args['widget_id'] => $content ), 'widget' );

		return $content;
	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache() {
		wp_cache_delete( apply_filters( 'ere_cached_widget_id', $this->widget_id ), 'widget' );
	}
	/**
	 * Output the html at the start of a widget.
	 * @param $args
	 * @param $instance
	 */
	public function widget_start( $args, $instance ) {
		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
	}
	/**
	 * Output the html at the end of a widget
	 * @param $args
	 */
	public function widget_end( $args ) {
		echo $args['after_widget'];
	}
	/**
	 * Updates a particular instance of a widget
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if ( empty( $this->settings ) ) {
			return $instance;
		}

		// Loop settings and get values to save.
		foreach ( $this->settings as $key => $setting ) {
			if ( ! isset( $setting['type'] ) ) {
				continue;
			}

			// Format the value based on settings type.
			switch ( $setting['type'] ) {
				case 'number' :
					$instance[ $key ] =  $new_instance[ $key ];

					if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
						$instance[ $key ] = max( $instance[ $key ], $setting['min'] );
					}

					if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
						$instance[ $key ] = min( $instance[ $key ], $setting['max'] );
					}
				break;
				case 'textarea' :
					$instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
				break;
				case 'checkbox' :
					$instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
				break;
				default:
					$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
				break;
			}

			/**
			 * Sanitize the value of a setting.
			 */
			$instance[ $key ] = apply_filters( 'ere_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
		}

		$this->flush_widget_cache();

		return $instance;
	}
	/**
	 * Outputs the settings update form
	 * @param array $instance
	 */
	public function form( $instance ) {

		if ( empty( $this->settings ) ) {
			return;
		}

		foreach ( $this->settings as $key => $setting ) {

			$std = isset($setting['std']) ? $setting['std'] : '';
			$class = isset( $setting['class'] ) ? $setting['class'] : '';
			$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $std;

			switch ( $setting['type'] ) {

				case 'text' :
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html($setting['label']); ?></label>
						<input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $key )); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
					</p>
					<?php
				break;

				case 'number' :
					$step = isset($setting['step']) ? $setting['step'] : '';
					$min = isset($setting['min']) ? $setting['min'] : '';
					$max = isset($setting['max']) ? $setting['max'] : '';
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html($setting['label']); ?></label>
						<input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="number" step="<?php echo esc_attr($step); ?>" min="<?php echo esc_attr( $min); ?>" max="<?php echo esc_attr($max); ?>" value="<?php echo esc_attr( $value ); ?>" />
					</p>
					<?php
				break;

				case 'select' :
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html($setting['label']); ?></label>
						<select class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<?php
				break;

				case 'textarea' :
					?>
					<p>
						<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html($setting['label']); ?></label>
						<textarea class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" cols="20" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
						<?php if ( isset( $setting['desc'] ) ) : ?>
							<small><?php echo esc_html( $setting['desc'] ); ?></small>
						<?php endif; ?>
					</p>
					<?php
				break;

				case 'checkbox' :
					?>
					<p class="ere-widget-checkbox-field">
						<input class="checkbox <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
						<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html($setting['label']); ?></label>
					</p>
					<?php
				break;

				case "multi-select" :
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html($setting['label']); ?></label>
						<input name="<?php echo esc_attr($this->get_field_name( $key )); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" />
						<select multiple class="widefat widget-select2" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" data-value="<?php echo esc_attr($value) ?>">
							<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_html( $option_value ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>

					<?php
					break;

				// Default: run an action
				default :
					do_action( 'ere_widget_field_' . $setting['type'], $key, $value, $setting, $instance );
				break;
			}
		}
	}
}
