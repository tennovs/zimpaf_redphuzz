<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Icon')) {
	class GSF_Field_Icon extends GSF_Field
	{
		function enqueue() {
			GSF_Core_Icons_Popup::getInstance()->enqueue();
			wp_enqueue_script(GSF()->assetsHandle('field_icon'), GSF()->helper()->getAssetUrl('fields/icon/assets/icon.min.js'), array(), GSF()->pluginVer(), true);
			wp_enqueue_style(GSF()->assetsHandle('field_icon'), GSF()->helper()->getAssetUrl('fields/icon/assets/icon.min.css'), array(), GSF()->pluginVer());
		}
		function renderContent()
		{
			$field_value = $this->getFieldValue();
			?>
			<div class="gsf-field-icon-inner">
				<input data-field-control="" type="hidden"
				       name="<?php $this->theInputName(); ?>"
				       value="<?php echo esc_attr($field_value); ?>"/>
				<div class="gsf-field-icon-item"
				     data-icon-title="<?php esc_html_e('Select icon', 'smart-framework'); ?>"
				     data-icon-remove="<?php esc_html_e('Remove icon', 'smart-framework'); ?>"
				     data-icon-search="<?php esc_html_e('Search icon...', 'smart-framework'); ?>">
					<div class="gsf-field-icon-item-info">
						<span class="<?php echo esc_attr($field_value); ?>"></span>
						<div class="gsf-field-icon-item-label"><?php esc_html_e('Set Icon', 'smart-framework'); ?></div>
                        <a href="javascript:;" title="<?php esc_html_e('Remove icon', 'smart-framework'); ?>" class="gsf-field-icon-remove"><i class="dashicons dashicons-no-alt"></i></a>
					</div>
				</div>
			</div>
		<?php
		}
	}
}