<script type="text/html" id="tmpl-gsf-custom-fonts">
    <div class="gsf-font-container" id="custom_fonts">
        <div id="gsf-custom-font-popup" class="mfp-with-anim mfp-hide">
            <header>
                <h4><?php esc_html_e('Add Custom Font', 'smart-framework'); ?></h4>
            </header>
            <form action="<?php echo admin_url('admin-ajax.php?action=gsf_upload_fonts&_nonce=' . GSF()->helper()->getNonceValue()); ?>" method="post" enctype="multipart/form-data">
                <div>
                    <label><?php esc_html_e('Font name:', 'smart-framework'); ?></label>
                    <input type="text" name="name" required=""/>
                </div>
                <div>
                    <label><?php esc_html_e('Fonts files (zip):', 'smart-framework'); ?></label>
                    <input type="file" name="file_font" required="" accept="application/zip"/>
                    <p><?php esc_html_e('File zip contains stylesheet.css and font files (accept: .woff, .eot, .svg, .ttf)', 'smart-framework'); ?></p>
                </div>
                <div>
                    <button type="submit" class="button button-primary"><i class="dashicons dashicons-plus-alt2"></i> <?php esc_html_e('Add Custom Font', 'smart-framework'); ?></button>
                </div>
            </form>
        </div>
        <div class="gsf-font-items">
            <# _.each(data.fonts.items, function(item, index) { #>
                <div class="gsf-font-item" data-name="{{item.family}}">
                    <div class="gsf-font-item-name">{{item.family}}</div>
                    <div class="gsf-font-item-action">
	                    <#if (!item.is_default) {#>
	                    <a href="#" class="gsf-font-item-action-delete" title="<?php esc_html_e('Delete custom font', 'smart-framework'); ?>"><i class="dashicons dashicons-no-alt"></i></a>
	                    <# } #>
                        <#if (item.using) {#>
                            <a href="#" class="gsf-font-item-action-add" data-type="custom"
                               title="<?php esc_html_e('Font activated', 'smart-framework'); ?>"><i class="dashicons dashicons-yes"></i></a>
                            <#} else {#>
                                <a href="#" class="gsf-font-item-action-add" data-type="custom"
                                   title="<?php esc_html_e('Use this font', 'smart-framework'); ?>"><i class="dashicons dashicons-plus-alt2"></i></a>
                                <#}#>
	                    <a href="#" class="gsf-font-item-change-font"
	                       data-type="custom"
	                       title="<?php echo esc_attr__('Replace Font', 'smart-framework') ?>">
		                    <i class="dashicons dashicons-randomize"></i>
	                    </a>
                    </div>
                </div>
                <# }); #>
        </div>
        <div class="gsf-add-custom-font">
            <button class="button button-primary" type="button"><i class="dashicons dashicons-plus-alt2"></i> <?php esc_html_e('Add Custom Fonts', 'smart-framework'); ?></button>
        </div>
    </div>
</script>