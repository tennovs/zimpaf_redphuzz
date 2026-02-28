<?php
$uDraw_SVG_settings = new uDraw_SVG_settings();
if (isset($_POST['save_udraw_settings']) && $_GET['tab'] === 'svg_designer') {
    $uDraw_SVG_settings->update_settings();
}
$settings = $uDraw_SVG_settings->get_settings();
$display_tools_top = $settings['udraw_SVGDesigner_display_tools_top'];
$tab_text_editor = $settings['udraw_SVGDesigner_tab_text_editor'];
$dpi_value = $settings['udraw_SVGDesigner_minimum_dpi'];
$tab_text_editor_checked = '';
$dpi_checked = '';
$dpi_display = ' hidden ';
if ($tab_text_editor) {
    $tab_text_editor_checked = ' checked ';
}
if ($settings['udraw_SVGDesigner_enable_dpi']) {
    $dpi_checked = ' checked ';
    $dpi_display = '';
}
$debug_pdf = '';
if ($settings['udraw_svg_debug_pdf_production']) { 
    $debug_pdf = ' checked '; 
}

// For reference in the future; Changed from 'Default' to 'Unique' because we changed the default UI from 'Default' to 'Widescreen', 
// and therefore the 'Default' UI is no longer the default UI. To avoid confusion on the user end, the UI named 'Default' is changed to 'Unique',
// because it was originally made for a specific client. Option/folder name stays the same because there's no need to actually change that.
$skins = array(
    'default'       => __('Unique', 'udraw_svg'),
    'widescreen'    => __('Widescreen', 'udraw_svg'),
    'simple'        => __('Simple', 'udraw_svg')
);
$default_hidden = ' hidden ';
if ($settings['udraw_SVGDesigner_skin'] === 'default') {
    $default_hidden = '';
}
$widescreen_hidden = ' hidden ';
if ($settings['udraw_SVGDesigner_skin'] === 'widescreen') {
    $widescreen_hidden = '';
}
$layers_tab_hidden = '';
if ($settings['udraw_SVGDesigner_skin'] === 'simple') {
    $layers_tab_hidden = ' hidden ';
}

$enable_proofing = '';
if ($settings['udraw_SVGDesigner_display_proof']) {
    $enable_proofing = ' checked ';
}

$enable_tutorials = '';
if ($settings['udraw_SVGDesigner_load_tutorial']) {
    $enable_tutorials = ' checked ';
}

$enable_stock_images = $settings['udraw_SVGDesigner_enable_stock_images'];
$enabled_sources_array = $settings['udraw_SVGDesigner_stock_images_list'];
$sources_array = array(
                    'clipart'       => '<span>Clipart</span>',
                    'pixabay'       => '<span>Pixabay</span>',
                    'pexel'         => '<span>Pexel</span>',
                    'unsplash'      => '<span>Unsplash</span>',
                    'private'       => '<a href="admin.php?page=upload_private_image_collection">' . __('Private Image Library', 'udraw_svg') . '</a>'
                );
$stock_images_hidden = ' hidden ';
$stock_image_checked = '';
if ($enable_stock_images) {
    $stock_images_hidden = '';
    $stock_image_checked = ' checked ';
}

$display_layers = $settings['udraw_SVGDesigner_display_layers'];
$display_layers_checked = '';
if ($display_layers) {
    $display_layers_checked = ' checked ';
}

$display_image_name = '';
if ($settings['udraw_SVGDesigner_display_image_name']) {
    $display_image_name = ' checked ';
}

$embed_images = '';
if ($settings['udraw_SVGDesigner_embed_images']) {
    $embed_images = ' checked ';
}

$display_rulers_check = '';
$display_rulers = $settings['udraw_SVGDesigner_display_rulers'];
if ($display_rulers) {
    $display_rulers_check = ' checked ';
}

$uDraw_SVG = new uDraw_SVG();

function get_checked ($source, $stock_images_array) {
    if (in_array($source, $stock_images_array)) {
        return ' checked ';
    }
    return '';
}

?>
<table class="form-table">
    <tbody>
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('uDraw SVG Designer Skin', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('uDraw SVG Designer Skin', 'udraw_svg') ?></span></legend>
                    <select name="udraw_SVGDesigner_skin">
                        <?php
                            foreach($skins as $skin => $skin_name) {
                                $selected = '';
                                if ($settings['udraw_SVGDesigner_skin'] === $skin) {
                                    $selected = ' selected ';
                                }
                                echo sprintf('<option value="%s" %s>%s</option>', $skin, $selected, $skin_name);
                            }
                        ?>
                    </select>
                    <span class="description"><br><?php _e('Select the skin customers will see/use.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        <tr valign="top" class="<?php echo $default_hidden ?> default_skin">
            <th scope="row" class="titledesc"><?php _e('Where to display tabs container?', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Where to display tabs container?', 'udraw_svg') ?></span></legend>
                    <select name="udraw_SVGDesigner_display_tools_top">
                        <option value="top" <?php if ($display_tools_top) { echo 'selected'; } ?> ><?php _e('Top', 'udraw_svg') ?></option>
                        <option value="right" <?php if (!$display_tools_top) { echo 'selected'; } ?> ><?php _e('Right / Side', 'udraw_svg') ?></option>
                    </select>
                    <span class="description"><br><?php _e('Select the position of where the tabs container which may contain pages and text editing tools.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        <tr valign="top" class="<?php echo $default_hidden ?> default_skin">
            <th scope="row" class="titledesc"><?php _e('Display Text Editor in Tabs', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Text Editor in Tabs Instead', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_tab_text_editor" <?php echo $tab_text_editor_checked; ?> />
                    <span class="description"><br><?php _e('Display the text editor in a tab instead of as an overlay box.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top" class="<?php echo $widescreen_hidden ?> widescreen_skin">
            <th scope="row" class="titledesc"><?php _e('Display Short Tutorial', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Short Tutorial', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_load_tutorial" <?php echo $enable_tutorials; ?> />
                    <span class="description"><br><?php _e('Display a short tutorial of how the designer works.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc"><?php _e('Display Proof', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Proof', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_display_proof" <?php echo $enable_proofing; ?> />
                    <span class="description"><br><?php _e('Display a proof of the design before adding to cart. This will act as the confirmation notice.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top" class="" >
            <th scope="row" class="titledesc"><?php _e('Display Uploaded Images Names', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Uploaded Images Names', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_display_image_name" <?php echo $display_image_name; ?> />
                    <span class="description"><br><?php _e('Display the names of uploaded images in user\'s library.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top" class="" >
            <th scope="row" class="titledesc"><?php _e('Enable Stock Image Library', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Enable Stock Image Library', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_enable_stock_images" <?php echo $stock_image_checked; ?> />
                    <span class="description"><br><?php _e('Allow users to add images from a selection of stock images.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        <tr valign="top" class="<?php echo $stock_images_hidden ?> stock_image_sources">
            <th scope="row" class="titledesc"><?php _e('Select Stock Image Sources', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Select Stock Image Sources', 'udraw_svg') ?></span></legend>
                    <?php 
                        foreach ($sources_array as $source => $display) {
                            echo '<div>';
                            $checked = get_checked ($source, $enabled_sources_array);
                            echo sprintf('<input type="checkbox" name="%s" %s />', $source, $checked);
                            echo $display;
                            echo '</div>';
                        }
                    ?>
                    <span class="description"><br><?php _e('Select the sources of which the user can choose from.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top" class="layers_tab_option <?php echo $layers_tab_hidden; ?>" >
            <th scope="row" class="titledesc"><?php _e('Display Layers Tab', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Layers Tab', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_display_layers" <?php echo $display_layers_checked; ?> />
                    <span class="description"><br><?php _e('Display the layers tab.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top" class="rulers_tab_option <?php echo $display_rulers; ?>" >
            <th scope="row" class="titledesc"><?php _e('Display Rulers', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Display Rulers', 'udraw_svg') ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_display_rulers" <?php echo $display_rulers_check; ?> />
                    <span class="description"><br><?php _e('Display the top and side rulers in the design area.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        
        <?php if (version_compare(phpversion(), '7.2.0', '>=') && extension_loaded('exif')) { ?>
        <!-- Minimum DPI -->
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('Enable Minimum DPI Requirement', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Enable Minimum DPI Requirement', 'udraw_svg') ?></span></legend>
                    <?php if (version_compare(phpversion(), '7', '>=')) { ?>
                        <input type="checkbox" name="udraw_SVGDesigner_enable_dpi" <?php echo $dpi_checked; ?> />
                    <?php } else { ?>
                    <br/>
                    <span class="description"><?php _e('Minimum PHP Version of 7.0.0 is required for this feature to be available.', 'udraw_svg'); ?></span>
                    <?php } ?>
                </fieldset>
            </td>
        </tr>
        <tr valign="top" class="udraw_svg_minimum_dpi <?php echo $dpi_display; ?>" >
            <th scope="row" class="titledesc"><?php _e('Minimum DPI', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Minimum DPI', 'udraw_svg') ?></span></legend>
                    <input type="number" name="udraw_SVGDesigner_minimum_dpi" value="<?php echo $dpi_value; ?>" />
                </fieldset>
            </td>
        </tr>
        <?php } ?>
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('Embed Images for Production PDF', 'udraw_svg'); ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Embed Images for Production PDF', 'udraw_svg'); ?></span></legend>
                    <input type="checkbox" name="udraw_SVGDesigner_embed_images" value="true" <?php echo $embed_images; ?> />
                    <span class="description"><br><?php _e('Embed images in the production PDF for editing purposes. Not recommended if there will be a lot of images on the design.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        <?php if (uDraw::is_udraw_okay()) { ?>
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('Debug Production PDF', 'udraw_svg'); ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Debug Production PDF', 'udraw_svg'); ?></span></legend>
                    <input type="checkbox" name="udraw_svg_debug_pdf_production" value="true" <?php echo $debug_pdf; ?> />
                    <span class="description"><br><?php _e('Debug the PDF production process. All PDFs created while this is checked will have a watermark present.', 'udraw_svg'); ?></span>
                </fieldset>
            </td>
        </tr>
        <?php } ?>
        <!-- Custom CSS Hook -->
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('Custom CSS Hook', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <textarea name="udraw_svg_css_hook" id="udraw_svg_css_hook" rows="7" cols="100" style="display:none;"><?php echo ($settings['udraw_svg_css_hook']); ?></textarea>
                    <legend class="screen-reader-text"><span><?php _e('Custom CSS Hook', 'udraw_svg') ?></span></legend>
                    <div id="udraw_svg_css_hook_ace" name="udraw_svg_css_hook" style="position: relative;width: auto;height: 300px;"></div>
                    <span class="description"><br><?php _e('Custom CSS for uDraw SVG products.', 'udraw_svg') ?></span>
                </fieldset>
            </td>
        </tr>
        <!-- Custom JS Hook -->
        <tr valign="top" class="">
            <th scope="row" class="titledesc"><?php _e('Custom JS Hook', 'udraw_svg') ?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <textarea name="udraw_svg_js_hook" id="udraw_svg_js_hook" rows="7" cols="100" style="display:none;"><?php echo ($settings['udraw_svg_js_hook']); ?></textarea>
                    <legend class="screen-reader-text"><span><?php _e('Custom JS Hook', 'udraw_svg') ?></span></legend>
                    <div id="udraw_svg_js_hook_ace" name="udraw_svg_js_hook" style="position: relative;width: auto;height: 300px;"></div>
                    <span class="description"><br><?php _e('Custom JS for uDraw SVG products.', 'udraw_svg') ?></span>
                </fieldset>
            </td>
        </tr>
    </tbody>
</table>
<script>
    jQuery(document).ready(function($){
        var css_editor, js_editor;
        css_editor = ace.edit("udraw_svg_css_hook_ace");
        css_editor.setTheme("ace/theme/chrome");
        css_editor.getSession().setMode("ace/mode/css");
        css_editor.getSession().setValue($('#udraw_svg_css_hook').val());
        css_editor.resize();

        js_editor = ace.edit("udraw_svg_js_hook_ace");
        js_editor.setTheme("ace/theme/chrome");
        js_editor.getSession().setMode("ace/mode/javascript");
        js_editor.getSession().setValue($('#udraw_svg_js_hook').val());
        js_editor.resize();
        $('#udraw_settings_form').submit(function(){
            $('#udraw_svg_css_hook').val(css_editor.getValue());
            $('#udraw_svg_js_hook').val(js_editor.getValue());
        });
        
        $('[name="udraw_SVGDesigner_enable_dpi"]').on('change', function(){
            var checked = $(this).prop('checked');
            $('tr.udraw_svg_minimum_dpi').addClass('hidden');
            if (checked) {
                $('tr.udraw_svg_minimum_dpi').removeClass('hidden');
            }
        });
        
        $('[name="udraw_SVGDesigner_skin"]').on('change', function(){
            $('tr.layers_tab_option').removeClass("hidden");
            if ($(this).val() === 'default') {
                $('tr.default_skin').removeClass('hidden');
                $('tr.widescreen_skin').addClass('hidden');
            } else {
                $('tr.default_skin').addClass('hidden');
                $('tr.widescreen_skin').removeClass('hidden');
            }
            if ($(this).val() === 'simple') {
                $('tr.layers_tab_option').addClass('hidden');
            }
        });
        
        $('[name="udraw_SVGDesigner_enable_stock_images"]').on('change', function (){
            var checked = $(this).prop('checked');
            $('tr.stock_image_sources').addClass('hidden');
            if (checked) {
                $('tr.stock_image_sources').removeClass('hidden');
            }
        });
    });
</script>

<style>
    select {
        width: 25%;
    }
</style>