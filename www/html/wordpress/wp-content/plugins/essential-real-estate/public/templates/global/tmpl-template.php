<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<script type="text/html" id="tmpl-ere-processing-template">
    <div class="ere-processing">
        <div class="loading">
            <i class="{{{data.ico}}}"></i><span>{{{data.text}}}</span>
        </div>
    </div>
</script>
<script type="text/html" id="tmpl-ere-alert-template">
    <div class="ere-alert-popup">
        <div class="content-popup">
            <div class="message">
                <i class="{{{data.ico}}}"></i><span>{{{data.text}}}</span>
            </div>
            <div class="btn-group">
                <a href="javascript:void(0)" class="btn-close"><?php esc_html_e('Close', 'essential-real-estate') ?></a>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="tmpl-ere-dialog-template">
    <div class="ere-dialog-popup" id="ere-dialog-popup">
        <div class="content-popup">
            <div class="message">
                <i class="{{{data.ico}}}"></i><span>{{{data.message}}}</span>
            </div>
        </div>
    </div>
</script>