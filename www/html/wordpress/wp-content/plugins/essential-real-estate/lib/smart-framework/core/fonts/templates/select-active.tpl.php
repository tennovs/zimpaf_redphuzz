<script type="text/html" id="tmpl-gsf-popup-change-font">
	<div class="gsf-popup-wrap">
		<div class="gsf-popup-inner gsf-popup-change-font" data-msg-confirm="<?php echo esc_attr__('Are you sure to change the font from "{1}" to "{2}?"', 'smart-framework') ?>">
			<div class="gsf-popup-header">
				<h4><?php echo esc_html__('Select font to replace','smart-framework') ?></h4>
				<span class="gsf-popup-close">×</span>
			</div>
			<div class="gsf-popup-content">
				<# _.each(data, function(item, index) { #>
				<div class="gsf-change-font-item">
					<span>{{item.family}}</span>
					<button type="button" class="button button-secondary button-small" data-name="{{item.family}}"><?php echo esc_html__('Change', 'smart-framework') ?></button>
				</div>
				<# }); #>
			</div>
		</div>
	</div>
</script>