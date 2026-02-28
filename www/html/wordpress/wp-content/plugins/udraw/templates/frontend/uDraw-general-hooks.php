<?php
    $udrawSettings = new uDrawSettings();
    $_udraw_settings = $udrawSettings->get_settings();
?>
<style type="text/css">
    <?php echo $_udraw_settings['udraw_general_css_hook']; ?>
</style>

<script>
    jQuery(document).ready(function () {
        <?php echo $_udraw_settings['udraw_general_js_hook']; ?>
    });
</script>