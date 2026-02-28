<script type="text/html" id="tmpl-gsf-active-fonts">
    <div class="gsf-font-active-container" id="active_fonts" style="display: block">
        <form action="<?php echo admin_url('admin-ajax.php?action=gsf_save_active_font&_nonce=' . GSF()->helper()->getNonceValue()); ?>" method="post">
            <div class="gsf-font-active-items">
                <# _.each(data.fonts.items, function(item, index) { console.log(item); #>
                    <div class="gsf-font-active-item" data-name="{{item.family}}">
                        <div class="gsf-font-active-item-header">
                            <h4>{{typeof(item.name) == 'undefined' ? item.family : item.name}}</h4>
	                        <a href="#" class="gsf-font-item-change-font"
	                           data-type="{{item.kind === 'webfonts#webfont' ? 'google' : item.kind}}"
	                           title="<?php echo esc_attr__('Replace Font', 'smart-framework') ?>">
		                        <i class="dashicons dashicons-randomize"></i>
	                        </a>
                            <a href="#" class="gsf-font-active-item-remove" title="<?php esc_html_e('Remove font!', 'smart-framework'); ?>">
                                <i class="dashicons dashicons-no-alt"></i>
                            </a>
                        </div>
                        <div class="gsf-font-active-content">
                            <div class="gsf-font-active-preview" style="font-family: {{GSF_Fonts.getFontFamily(item.family)}}">
                                <p class="gsf-font-active-preview-title">
                                    <?php esc_html_e('Welcome to font preview!', 'smart-framework'); ?>
                                </p>
                                <p class="gsf-font-active-preview-text">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890‘?’“!”(%)[#]{@}/&<-+÷×=>®©$€£¥¢:;,.*</p>
                            </div>
                            <input type="hidden" value="{{item.kind}}" name="font[{{index}}][kind]"/>
                            <div class="gsf-row">
                                <div class="gsf-variant">
                                    <h5><?php esc_html_e('Variants', 'smart-framework'); ?></h5>
                                    <div class="gsf-clearfix">
                                        <# _.each(item.default_variants, function(v, vIndex) { #>
                                            <# if (item.variants.indexOf(v) != -1) {#>
                                                <label><input name="font[{{index}}][variants][]" type="checkbox" value="{{v}}" checked="checked" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                <#} else {#>
                                                    <label><input name="font[{{index}}][variants][]" type="checkbox" value="{{v}}" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                    <#}#>
                                                        <# }); #>
                                    </div>
                                </div>
                                <div class="gsf-subset">
                                    <h5><?php esc_html_e('Subsets', 'smart-framework'); ?></h5>
                                    <div class="gsf-clearfix">
                                        <# _.each(item.default_subsets, function(v, vIndex) { #>
                                            <# if (item.subsets.indexOf(v) != -1) { #>
                                                <label><input name="font[{{index}}][subsets][]" type="checkbox" value="{{v}}" checked="checked" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                <#} else {#>
                                                    <label><input name="font[{{index}}][subsets][]" type="checkbox" value="{{v}}" {{item.kind !='webfonts#webfont' ? 'disabled="disabled"' : ''}}/> <span>{{v}}</span></label>
                                                    <#}#>
                                                        <# }); #>
                                    </div>
                                </div>
                            </div>
                            <div class="gsf-row gsf-font-selector">
                                <h5><?php esc_html_e('Selector apply:', 'smart-framework'); ?></h5>
                                <input name="font[{{index}}][selector]" type="text" value="{{item.selector}}"/>
                            </div>
                        </div>
                    </div>
                    <# }); #>
            </div>
            <div class="gsf-save-active-font">
                <button class="button button-primary" type="submit"><i class="dashicons dashicons-upload"></i> <?php esc_html_e('Save Changes', 'smart-framework'); ?></button>

            </div>
        </form>
    </div>
</script>