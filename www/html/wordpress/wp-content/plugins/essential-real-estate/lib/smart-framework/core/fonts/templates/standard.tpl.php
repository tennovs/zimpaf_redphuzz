<script type="text/html" id="tmpl-gsf-standard-fonts">
    <div class="gsf-font-container" id="standard_fonts">
        <div class="gsf-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="gsf-font-item" data-name="{{item.family}}">
                    <div class="gsf-font-item-name">{{item.name}}</div>
                    <div class="gsf-font-item-action">
                        <#if (item.using) {#>
                            <a href="#" class="gsf-font-item-action-add" data-type="standard"
                               title="<?php esc_html_e('Font activated', 'smart-framework'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="gsf-font-item-action-add" data-type="standard"
                                   title="<?php esc_html_e('Use this font', 'smart-framework'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>
	                    <a href="#" class="gsf-font-item-change-font"
	                       data-type="standard"
	                       title="<?php echo esc_attr__('Replace Font', 'smart-framework') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
    </div>
</script>