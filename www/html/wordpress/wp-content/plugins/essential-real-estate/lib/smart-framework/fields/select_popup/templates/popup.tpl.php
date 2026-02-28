<script type="text/html" id="tmpl-gsf-select-popup">
	<div id="gsf-popup-select-wrapper"
	     <# if (data.popup_width) { #>
	     style="max-width: {{data.popup_width}}"
		<# } #>
	     class="gsf-popup-select-wrapper mfp-with-anim mfp-hide">
		<div class="gsf-popup-select-content">
			<h2 class="gsf-popup-select-header">
				<span>{{data.title}}</span>
			</h2>
			<div class="gsf-popup-select-listing">
				<div class="gsf-row">
					<# for (var item_key in data.options) { #>
						<div class="gsf-col gsf-col-{{12/(data.items)}}">
							<div class="gsf-popup-select-item" data-value="{{item_key}}">
								<img src="{{ data.options[item_key].img}}"
								     data-thumb="{{ data.options[item_key].thumb}}"
								     alt="{{ data.options[item_key].label}}">
								<div class="gsf-popup-select-item-footer">
									<span class="name">{{data.options[item_key].label}}</span>
									<span class="current"><?php esc_html_e('Current','smart-framework') ?></span>
								</div>
							</div>
						</div>
					<# }; #>
				</div>
			</div>
		</div>
	</div>
</script>