<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Custom')) {
	class GSF_Field_Custom extends GSF_Field
	{
		function render()
		{
			$field_classes = array('gsf-field');
			$field_classes[] = 'gsf-field-' . $this->getType();
			$template_file = $this->_setting['template'];
			?>
			<div <?php $this->theFieldAttribute()?> class="<?php echo join(' ', $field_classes); ?>">
				<?php
				extract(array(
					'field' => $this
				));
				include $template_file;
				?>
			</div>
			<?php
		}
	}
}