<?php
/**
 * @var string $option_name
 * @var string $page
 * @var string $current_preset
 * @var string $page_title
 * @var bool $preset
 */
$theme = wp_get_theme();
if (!$preset) {
	$current_preset = '';
}
GSF()->adminThemeOption()->is_theme_option_page = true;
GSF()->adminThemeOption()->current_preset = $current_preset;
GSF()->adminThemeOption()->current_page = $page;

$preset_listing = GSF()->adminThemeOption()->getPresetOptionKeys($option_name);
?>
<div class="gsf-theme-options-page-loading">
	<div class="loader"></div>
</div>
<div class="wrap"><h2 style="display: none"></h2></div>
<div class="gsf-theme-options-wrapper wrap" style="display: block">
	<form action="<?php echo admin_url( 'admin-ajax.php' ) . '?action=gsf_save_options'; ?>" method="post" enctype="multipart/form-data" class="gsf-theme-options-form">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo GSF()->helper()->getNonceValue() ?>" />
		<input type="hidden" id="_current_page" name="_current_page" value="<?php echo esc_attr($page); ?>" />
		<input type="hidden" id="_current_preset" name="_current_preset" value="<?php echo esc_attr($current_preset); ?>" />
		<?php if (GSF()->adminThemeOption()->current_section !== ''): ?>
			<input type="hidden" id="_current_section" name="_current_section" value="<?php echo esc_attr(GSF()->adminThemeOption()->current_section) ?>">
		<?php endif; ?>
		<div class="gsf-theme-options-header-wrapper">
			<div class="gsf-theme-options-header gsf-clearfix">
				<div class="gsf-theme-options-title">
					<h1>
						<?php echo esc_html($page_title) ?>
						<span><?php esc_html_e('version', 'smart-framework'); ?> <?php echo $theme->get('Version'); ?></span>
					</h1>
					<?php if (!empty($desc)): ?>
						<p><?php echo wp_kses_post($desc) ?></p>
					<?php endif; ?>
				</div>
				<?php if ($preset && !empty($current_preset)): ?>
					<div class="gsf-preset-action">
						<button type="button" class="button button-success gsf-preset-action-make-default"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Make Default Options', 'smart-framework'); ?></button>
						<button type="button" class="button button-danger gsf-preset-action-delete"><i class="dashicons dashicons-no-alt"></i> <?php esc_html_e('Delete Preset', 'smart-framework'); ?></button>
						<a href="<?php echo esc_url(home_url('/?_gsf_preset=' . $current_preset)); ?>" target="_blank" class="button"><i class="dashicons dashicons-visibility"></i> <?php esc_html_e('Preview', 'smart-framework'); ?></a>
					</div>
				<?php endif;?>
			</div>
		</div>
		<div class="gsf-theme-options-action-wrapper">
			<div class="gsf-theme-options-action-inner gsf-clearfix">
				<?php if ($preset): ?>
					<div class="gsf-theme-options-preset">
						<div class="gsf-theme-options-preset-select">
							<div>
								<?php esc_html_e('Select Preset Options...', 'smart-framework'); ?>
								<i class="dashicons dashicons-arrow-down"></i>
							</div>
							<ul>
								<li data-preset=""><?php esc_html_e('Default Options', 'smart-framework'); ?></li>
								<?php foreach ($preset_listing as $preset_name=> $preset_title): ?>
									<li data-preset="<?php echo esc_attr($preset_name); ?>"><?php echo esc_attr($preset_title); ?></li>
								<?php endforeach;?>
							</ul>
						</div>
						<button type="button" class="button button-primary gsf-theme-options-preset-create"><i class="dashicons dashicons-plus"></i> <?php esc_html_e('Create Preset Options', 'smart-framework'); ?></button>
					</div>
				<?php endif;?>
				<div class="gsf-theme-options-action">
					<button class="button button-success gsf-theme-options-save-options" type="submit" name="gsf_save_option"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Save Options', 'smart-framework'); ?></button>
				</div>
			</div>
		</div>