<?php
/**
 * The template for displaying selectize.php
 *
 * @package WordPress
 * @subpackage emo
 * @since emo 1.0
 */
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
if (!class_exists('ERE_Vc_Param_Selectize')) {
	class ERE_Vc_Param_Selectize {
		public function __construct()
		{
			add_action('vc_load_default_params', array($this, 'register_param'));
			add_action( 'vc_backend_editor_enqueue_js_css', array($this,'enqueue_admin_resources'));
		}

		public function register_param()
		{
			vc_add_shortcode_param('ere_selectize', array($this, 'render_param'),ERE_PLUGIN_URL. 'includes/vc-params/selectize/assets/selectize.js');
		}

		public function render_param($settings, $value)
		{
			ob_start();
			$field_classes = array(
				'wpb_vc_param_value wpb-input wpb-select',
				$settings['param_name'],
				"{$settings['type']}_field"
			);
			$field_class = implode(' ', array_filter($field_classes));
			$multiple = isset($settings['multiple']) ? $settings['multiple'] : false;
			$tags = isset($settings['tags']) ? $settings['tags'] : false;
			$attributes = array(
				'data-selectize="true"'
			);

			if (($multiple === true) || (($tags === true) && !empty($settings['value']) )) {
				$attributes[] = 'multiple="multiple"';
				if (!is_array($value)) {
					$value = preg_split('/\,/', $value);
				}
			}

			if ($tags === true) {
				$attributes[] = 'data-tags="true"';
			}

			if ((($multiple === true) || ($tags === true)) && !empty($settings['value'])) {
				$attributes[] = "data-value='". esc_attr((is_array($value) ? json_encode($value) : $value)) ."'";
			}


			$options = array();
			if (!empty($settings['value'])) {
				$options = $settings['value'];
			}

			if (is_array($value) && ($tags === true)) {
				$options = array_merge($options,$value);
			}
			?>
			<div class="ere-vc-selectize-wrapper">
				<?php if (($tags === true) && empty($settings['value'])): ?>
					<input class="<?php echo esc_attr($field_class) ?>" <?php echo implode(' ', array_filter($attributes)); ?> value="<?php echo esc_attr($value); ?>" type="text" name="<?php echo esc_attr($settings['param_name']) ?>" id="<?php echo esc_attr($settings['param_name']) ?>">
				<?php else: ?>
					<select class="<?php echo esc_attr($field_class) ?>" <?php echo implode(' ', array_filter($attributes)); ?> name="<?php echo esc_attr($settings['param_name']) ?>" id="<?php echo esc_attr($settings['param_name']) ?>">
						<?php foreach ($options as $index => $data): ?>
							<?php
							if (is_numeric($index) && (is_string($data) || is_numeric($data))) {
								$option_label = $data;
								$option_value = $data;
							} elseif (is_numeric($index) && is_array($data)) {
								$option_label = isset($data['label']) ? $data['label'] : array_pop($data);
								$option_value = isset($data['value']) ? $data['value'] : array_pop($data);
							} else {
								$option_value = $data;
								$option_label = $index;
							}
							if (!is_array($value)) {
								$option_value_string = (string) $option_value;
								$value_string = (string) $value;
							}
							?>
							<option value="<?php echo esc_attr($option_value) ?>"
                                <?php if ('' !== $value && $option_value_string === $value_string): ?>
                                    selected="selected"
                                <?php endif; ?>
                                ><?php echo esc_html($option_label) ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</div>
			<?php
			return ob_get_clean();
		}

		public function enqueue_admin_resources() {
			wp_enqueue_style('selectize', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/css/selectize.css', array(), '0.12.3');
			wp_enqueue_style('selectize_default', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/css/selectize.default.css', array(), '0.12.3');
			wp_enqueue_script('selectize', ERE_PLUGIN_URL . 'lib/smart-framework/assets/vendors/selectize/js/selectize.js', array('jquery'), '0.12.3', true);
		}
	}
	new ERE_Vc_Param_Selectize();
}