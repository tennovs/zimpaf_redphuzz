<?php
/**
 * @var $list_section
 * @var $current_preset
 */
?>
<div class="gsf-meta-box-wrap">
	<div class="gsf-meta-box-wrap-inner">
		<?php
		GSF()->helper()->getTemplate('admin/templates/meta-section', array(
			'list_section' => $list_section,
			'current_preset' => $current_preset
		));
		?>
		<div class="gsf-meta-box-fields-wrapper">
			<div class="gsf-meta-box-fields">