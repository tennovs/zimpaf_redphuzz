<?php
/**
 * @var $list_section
 * @var $current_preset
 */
?>
<?php if (!empty($list_section)): ?>
	<div class="gsf-sections">
		<ul>
			<?php foreach ($list_section as $section):?>
				<?php
				$class_active = '';
				$section_link = '#section_' . $section['id'];
				if (GSF()->adminThemeOption()->is_theme_option_page) {
					if ($current_preset !== '') {
						$section_link = sprintf('%s?page=%s&_gsf_preset=%s&section=%s',
							admin_url('admin.php'),
							GSF()->adminThemeOption()->current_page,
							$current_preset,
							$section['id']);
					}
					else {
						$section_link = admin_url('admin.php?page=') . GSF()->adminThemeOption()->current_page . '&section=' . $section['id'];
					}
					if ($section['id'] === GSF()->adminThemeOption()->current_section) {
						$class_active = 'active';
					}
				}
				?>
				<li data-id="section_<?php echo esc_attr($section['id']); ?>" class="<?php echo esc_attr($class_active)?>">
					<a href="<?php echo esc_url($section_link); ?>">
						<i class="<?php echo esc_attr($section['icon']); ?>"></i>
						<span><?php echo wp_kses_post($section['title']); ?></span></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>