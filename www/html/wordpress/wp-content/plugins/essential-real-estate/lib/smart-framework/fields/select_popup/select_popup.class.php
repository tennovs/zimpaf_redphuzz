<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Select_Popup')) {
	class GSF_Field_Select_Popup extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_style(GSF()->assetsHandle('field-select-popup'));
			wp_enqueue_script(GSF()->assetsHandle('field-select-popup'));
			add_action('admin_footer', array($this, 'popup_template'), 1000);
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			$field_data = isset($this->_setting['options'][$field_value]) ? $this->_setting['options'][$field_value] : array();

			$src = empty($field_data) ? '' : $field_data['img'];
			$label =  empty($field_data) ? '' : $field_data['label'];

			$items = isset($this->_setting['items']) ? $this->_setting['items'] : 1;
			$popup_width = isset($this->_setting['popup_width']) ? $this->_setting['popup_width'] : '';

			foreach ($this->_setting['options'] as $key => $value) {
				if (!isset($this->_setting['options'][$key]['thumb'])) {
					$this->_setting['options'][$key]['thumb'] = $this->_setting['options'][$key]['img'];
				}
			}

			$style = isset($this->_setting['style']) ? $this->_setting['style'] : '';
			?>
			<div class="gsf-field-select_popup-inner <?php echo  esc_attr($style)?>">
				<input data-field-control=""
				       name="<?php $this->theInputName(); ?>"
				       type="hidden" value="<?php echo esc_attr($field_value) ?>">
				<div class="gsf-field-select_popup-preview-outer">
                    <img class="gsf-field-select_popup-preview" src="<?php echo esc_url($src) ?>">
				</div>
				<div class="gsf-field-select_popup-info">
					<span class="info-name"><?php echo esc_html($label) ?></span>
					<button type="button" class="button button-primary info-select"
					        data-title="<?php echo esc_attr($this->getFieldTitle()) ?>"
					        data-items="<?php echo esc_attr($items) ?>"
					        data-popup-width="<?php echo esc_attr($popup_width) ?>"
					        data-options='<?php echo json_encode($this->_setting['options']) ?>'><?php esc_html_e('Change','smart-framework') ?></button>
				</div>
			</div>
		<?php
		}

		public function popup_template() {
            global $gsf_is_load_select_popup;
            if (isset($gsf_is_load_select_popup)) {
                return;
            }
            $gsf_is_load_select_popup = true;
			GSF()->helper()->getTemplate('fields/select_popup/templates/popup.tpl');
		}
	}
}