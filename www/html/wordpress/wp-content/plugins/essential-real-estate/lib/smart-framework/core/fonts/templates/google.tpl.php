<script type="text/html" id="tmpl-gsf-google-fonts">
    <div class="gsf-font-container" id="google_fonts" style="display: block">
        <ul class="gsf-font-categories gsf-clearfix">
            <# _.each(data.fonts.categories, function(item, index) { #>
                <# if (index == 0) {#>
                    <li class="active" data-ref="{{item.name}}"><a href="#">{{item.name}} ({{item.count}})</a></li>
                    <#} else { #>
                        <li data-ref="{{item.name}}"><a href="#">{{item.name}} ({{item.count}})</a></li>
                        <#}#>
                            <# }); #>
        </ul>
        <div class="gsf-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="gsf-font-item" data-category="{{item.category}}" data-name="{{item.family}}" style="display: none">
                    <div class="gsf-font-item-name">{{item.family}}</div>
                    <div class="gsf-font-item-action">
                        <a href="https://www.google.com/fonts/specimen/{{item.family.replace(' ','+')}}" target="_blank"
                           title="<?php esc_html_e('Preview font', 'smart-framework'); ?>"
                           class="gsf-font-item-action-preview"><i class="dashicons dashicons-visibility"></i></a>
                        <#if (item.using) {#>
                            <a href="#" class="gsf-font-item-action-add" data-type="google"
                               title="<?php esc_html_e('Font activated', 'smart-framework'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="gsf-font-item-action-add" data-type="google"
                                   title="<?php esc_html_e('Use this font', 'smart-framework'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>

	                    <a href="#" class="gsf-font-item-change-font"
	                       data-type="google"
	                       title="<?php echo esc_attr__('Replace Font', 'smart-framework') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
    </div>
</script>