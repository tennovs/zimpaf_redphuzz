<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Heading')) {
	class GSF_Field_Heading extends GSF_Field
	{
		function enqueue()
		{
			wp_enqueue_style(GSF()->assetsHandle('field_heading'), GSF()->helper()->getAssetUrl('fields/heading/assets/heading.min.css'), array(), GSF()->pluginVer());
		}
		function render()
		{
			$title = isset($this->_setting['title']) ? $this->_setting['title']: '';
			$class_inner = array('gsf-heading-inner');
			if (isset($this->_setting['style'])) {
				$class_inner[] = 'gsf-heading-style-' . $this->_setting['style'];
			}

			$field_classes = array('gsf-field');
			$field_classes[] = 'gsf-field-' . $this->getType();
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo join(' ', $field_classes); ?>">
				<div class="<?php echo join(' ', $class_inner) ?>">
					<div class="gsf-heading-content">
						<?php if (!empty($title)): ?>
							<h3><?php echo wp_kses_post($title); ?></h3>
						<?php endif;?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}