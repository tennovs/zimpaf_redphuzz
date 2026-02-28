<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Textarea')) {
	class GSF_Field_Textarea extends GSF_Field
	{
		function enqueue()
		{
			wp_enqueue_script(GSF()->assetsHandle('field_textarea'), GSF()->helper()->getAssetUrl('fields/textarea/assets/textarea.min.js'), array(), GSF()->pluginVer(), true);
			wp_enqueue_style(GSF()->assetsHandle('field_textarea'), GSF()->helper()->getAssetUrl('fields/textarea/assets/textarea.min.css'), array(), GSF()->pluginVer());
		}

		function renderContent($content_args = '')
		{
			$field_value = $this->getFieldValue();
			$attr = array();

			$row = (isset($this->_setting['args']) && isset($this->_setting['args']['row'])) ? esc_attr($this->_setting['args']['row']) : '5';
			$attr[] = sprintf('cols="%s"', esc_attr($row));

			if (isset($this->_setting['args']['col']) && (isset($this->_setting['args']['col']) !== '')) {
				$attr[] = sprintf('rows="%s"', esc_attr($this->_setting['args']['col']));
			}
			if (isset($this->_setting['is_required']) && ($this->_setting['is_required'] === true)) {
				$attr[] = 'required="required"';
			}
			if (isset($this->_setting['placeholder']) && ($this->_setting['placeholder'] !== '')) {
				$attr[] = sprintf('placeholder="%s"', esc_attr($this->_setting['placeholder']));
			}
			?>
			<div class="gsf-field-textarea-inner">
			<textarea data-field-control="" name="<?php $this->theInputName(); ?>"
	            <?php echo join(' ', $attr); ?>><?php echo esc_textarea($field_value); ?></textarea>
			</div>
			<?php
		}
	}
}