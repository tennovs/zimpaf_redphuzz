<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Switch2')) {
	class GSF_Field_Switch2 extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF()->assetsHandle('field_switch2'), GSF()->helper()->getAssetUrl('fields/switch2/assets/switch2.min.js'), array(), GSF()->pluginVer(), true);
			wp_enqueue_style(GSF()->assetsHandle('field_switch'));
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			$value_inline = isset($this->_setting['value_inline']) ? $this->_setting['value_inline'] : true;
			$on_text = isset($this->_setting['on_text']) ? $this->_setting['on_text'] : esc_html__('On', 'smart-framework');
			$off_text = isset($this->_setting['off_text']) ? $this->_setting['off_text'] : esc_html__('Off', 'smart-framework');
			?>
			<div class="gsf-field-switch-inner <?php echo ($value_inline ? 'value-inline' : ''); ?>">
				<label>
					<input type="hidden" data-field-control=""
					       name="<?php $this->theInputName(); ?>"
					       value="<?php echo esc_attr($field_value) ?>">
					<input type="checkbox" <?php GSF()->helper()->theChecked($on_text, $field_value) ?> value="<?php echo esc_attr($field_value) ?>" />
					<div class="gsf-field-switch-button" data-switch-on="<?php echo esc_attr($on_text); ?>" data-switch-off="<?php echo esc_attr($off_text); ?>">
						<span class="gsf-field-switch-off"><?php echo esc_html($off_text); ?></span>
						<span class="gsf-field-switch-on"><?php echo esc_html($on_text); ?></span>
					</div>
				</label>
			</div>
		<?php
		}
	}
}