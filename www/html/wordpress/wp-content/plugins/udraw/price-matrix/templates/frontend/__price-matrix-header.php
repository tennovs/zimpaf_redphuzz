<?php

$_session_upload_id = uniqid();

$uDraw = new uDraw();
$udrawSettings = new uDrawSettings();
$uDrawPDFBlocks = new uDrawPDFBlocks();
$udrawPriceMatrix = new uDrawPriceMatrix();

$_udraw_settings = $udrawSettings->get_settings();
$udraw_price_matrix_access_key = $udrawPriceMatrix->get_product_price_matrix_key($post->ID);
if (metadata_exists('post', $post->ID, '_udraw_SVG_price_matrix_set') && get_post_meta($post->ID, '_udraw_SVG_price_matrix_set', true)) {
    $udraw_price_matrix_access_key = get_post_meta($post->ID, '_udraw_SVG_price_matrix_access_key', true);
}

$designTemplateId = $uDraw->get_udraw_template_ids($post->ID);
$is_design_product = false;
$is_upload_product = false;
$display_options_first = false;
$display_add_to_cart_btn = true;
$allow_structure_file = false;
$disable_price_matrix_size_check = false;

if (count($designTemplateId) > 0) { $is_design_product = true; }
$allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
$allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
if ($allowUploadArtwork == "yes" ) { $is_upload_product = true; }
$is_double_upload_product = ($allowUploadArtwork == "yes" && $allowDoubleUploadArtwork == "yes") ? true : false;
$displayOptionsPageFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);
if ($displayOptionsPageFirst == "yes") { $display_options_first = true; }
if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
$disableSizeCheckPriceMatrix = get_post_meta($post->ID, '_udraw_price_matrix_disable_size_check', true);
if ($disableSizeCheckPriceMatrix == "yes") { $disable_price_matrix_size_check = true; }



$valid_extensions = ".jpg,.jpeg,.png,.gif,.pdf";
if (isset($_udraw_settings['goprint2_file_upload_types']) && is_array($_udraw_settings['goprint2_file_upload_types'])) {
    $valid_extensions = "";
    foreach($_udraw_settings['goprint2_file_upload_types'] as $key => $value) {
        $valid_extensions_array = explode("|",$key);
        foreach ($valid_extensions_array as $ext) {
            $valid_extensions .= ".". $ext .",";
        }
    }
}
$valid_extensions = rtrim($valid_extensions, ",");
$target_dpi = $_udraw_settings['goprint2_file_upload_min_dpi'];

$blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);
$xmpieProductId = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
$isBlockProduct = false;
if (is_array($blockProductId)) {
    if (count($blockProductId) > 0) {
        $isBlockProduct = true;
    }
}
$isXmpieProduct = false;
if (is_array($xmpieProductId)) {
    if (count($xmpieProductId) > 0) {
        $isXmpieProduct = true;
    }
}

if ($display_options_first && $isBlockProduct) { $display_add_to_cart_btn = false; }
if ($display_options_first && $isXmpieProduct) { $display_add_to_cart_btn = false; }
if ($display_options_first && $is_design_product) { $display_add_to_cart_btn = false; }
if ($is_upload_product) { $display_add_to_cart_btn = false; }
$_font_color = "#000000";
$_background_color = "#FFFFFF";
$_measurement_unit = 'ft';
if (strlen($udraw_price_matrix_access_key) > 0) {
    
    $price_matrix_object = $udrawPriceMatrix->get_price_matrix_by_key($udraw_price_matrix_access_key);
    
    $udrawPriceMatrix->registerScripts();
    if (strlen($price_matrix_object[0]->font_color) > 1) {
        $_font_color = $price_matrix_object[0]->font_color;            
    }
    if (strlen($price_matrix_object[0]->background_color) > 1) {
        $_background_color = $price_matrix_object[0]->background_color;
    }
    
    if (strlen($price_matrix_object[0]->measurement_label) > 0) {
        $_measurement_unit = $price_matrix_object[0]->measurement_label;
        
        if (strlen($_measurement_unit) == 0) {
            $_measurement_unit = 'ft';
        }
    }
}
?>

<script>
    var allowUploadArtwork = '<?php echo $allowUploadArtwork ?>';
    jQuery(document).ready(function () {
        <?php if ($display_add_to_cart_btn) { echo "jQuery('#udraw-options-submit-form-btn').fadeIn();"; } ?>
        <?php if ($target_dpi !== '') { echo " targetDpi = ". $target_dpi . ";"; } ?>
    });
</script>
<style type="text/css">
    
    #canvas select, #txtQty {
        padding: 5px;
        font-size:10pt;
        min-width: 120px;
        margin-bottom: 2px;
    }

    #spanQty label {
        width: 30%;
        font-size: 12pt;
    }

    #txtQty, #txtRecords { width: 100%; }

    #spanWidth input, #spanWidth select, 
    #spanHeight input, #spanHeight select,
    #spanLength input, #spanLength select {
        width:70px;
        margin-right: 5px;
        margin-bottom: 2px;
        padding: 5px;        
    }

    #udraw-bootstrap .row {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    
</style>