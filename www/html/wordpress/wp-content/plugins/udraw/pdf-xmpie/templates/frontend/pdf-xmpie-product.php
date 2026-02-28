<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');

global $woocommerce, $product, $post;

if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_type = $product->get_type();
} else {
    $product_type = $product->product_type;
}

$uDrawPdfXMPie = new uDrawPdfXMPie();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();

$allowPDFPrintSave = get_post_meta($post->ID, '_udraw_pdf_xmpie_allow_print_save', true);
$displayOptionsFirst = (!is_null(get_post_meta($post->ID, '_udraw_display_options_page_first', true))) ? get_post_meta($post->ID, '_udraw_display_options_page_first', true) : false;
$use_colour_palette = get_post_meta($post->ID, '_udraw_pdf_xmpie_use_colour_palette', true);
if (is_null($use_colour_palette) || !isset($use_colour_palette) || strlen($use_colour_palette) < 1) {
    $use_colour_palette = 0;
} else {
    $use_colour_palette = 1;
}

// Support for multiple xmpie templates for one product.
// If the xmpie template is 'old' method, we will change it to array 'new' method of getting id.
$xmpie_array = Array();
if (isset($xmpieProductId)) {
    if (gettype($xmpieProductId) == "string") {
        if (strlen($xmpieProductId) > 0) {
            array_push($xmpie_array, $xmpieProductId);
        }
    } else if (gettype($xmpieProductId) == "array") {
        $xmpie_array = $xmpieProductId;    
    } else {
        // Uh Oh! Spagetios!
        // This condition shouldn't happen and there was a problem defining the product Id.
        header('/', true);
    }
}

// Check if xmpie are multiple or single.
$isMultiXMPie = false;
if (count($xmpie_array) > 1) {
    $isMultiXMPie = true;
}
$udraw_access_key = (isset($_GET['udraw_access_key'])) ? $_GET['udraw_access_key'] : NULL;

if (isset($_REQUEST['display_error']) && $_REQUEST['display_error'] === '1') {
    ?>
    <div style="background-color: #ffe8e8; padding: 10px;">
        <?php _e('There was an error adding this item to cart. Please try again.', 'udraw'); ?>
    </div>
    <?php
}

?>


<form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
    <input type="hidden" name="udraw_save_product_data" value="" />
    <input type="hidden" name="udraw_save_product_preview" value="" />
    <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
    <input type="hidden" name="udraw_save_access_key" value="<?php echo $udraw_access_key; ?>" />
    <input type="hidden" name="udraw_is_saving_for_later" value="1" />
    <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
    <input type="hidden" name="udraw_selected_variations" value="" />
    <?php wp_nonce_field('save_udraw_customer_design'); ?>
</form>

<div id="pdf-xmpie-product-ui" style="display:none;">
    <div id="udraw-bootstrap" style="background:none;">
        <div class="container" style="background:none; margin:0px; padding:0px;">
            <?php
            $button_placement = "top";
            if (isset($_udraw_settings['goepower_pdf_approve_btn_below_preview']) && $_udraw_settings['goepower_pdf_approve_btn_below_preview']) { $button_placement = "bottom"; }
            if ($_udraw_settings['goepower_approve_button_placement'] == "bottom") { $button_placement = "bottom"; }
            if ($_udraw_settings['goepower_approve_button_placement'] == "both") { $button_placement = "both"; }
            
            if($button_placement == "top" || $button_placement == "both") {
                pdf_xmpie_display_approve_html($_udraw_settings, $allowPDFPrintSave, $displayOptionsFirst);
            }
            
            $preview_mode = "image";
            if ($_udraw_settings['goepower_preview_mode'] == "pdf") { $preview_mode = "pdf"; }            
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="col-lg-4">
                    <?php if ($isMultiXMPie) { ?>
                    <div class="col-lg-12">Product Selection</div>
                    <div class="col-lg-12">
                        <select class="udrawProductSelect2 dropdownList" id="_product_selection" style='width:100%;'>
                        <?php 
                            foreach ($xmpie_array as $xmpie) {                                
                                $xmpie_json = $uDrawPdfXMPie->get_product($xmpie);
                                echo '<option value="'.$xmpie_json['UniqueID']. '">' . $xmpie_json['ProductName']. '</option>';
                            }
                        ?>
                        </select>
                    </div>                 
                    <?php } ?>
                    <div id="pdf-xmpie-inputs"></div>
                    <br />
                </div>
                <div class="col-lg-8">
                    <div class="product_image_preview" style="text-align: center;">
                        <?php
                            $thumbnail_id = get_post_meta($post->ID, '_thumbnail_id', true);
                            $product_thumbnail = get_the_guid($thumbnail_id);
                        ?>
                        <img src="<?php echo $product_thumbnail; ?>">
                    </div>
                    <div id="pdf-xmpie-thumbnail-preview" class="touchcarousel three-d" style="border: 1px solid rgb(203, 203, 203); float:right;">
                        <!--<img src="<?php echo $uDrawPdfXMPie->get_product($xmpie_array[0])['ThumbnailLarge']; ?>" />-->
                    </div>
                    <div id="loadingDiv" style="float:right; display:none;" >
                            <img src="<?php echo UDRAW_PLUGIN_URL ?>assets/includes/loading-animation.gif" />
                    </div>
                    <div id="previewDiv" style="display: block; padding-top:10px;"></div>
                </div>                
            </div>
            <?php if($button_placement == "bottom" || $button_placement == "both") { pdf_xmpie_display_approve_html($_udraw_settings, $allowPDFPrintSave, $displayOptionsFirst); }?>
        </div>
    </div>
</div>

<script> var _previous_pdf_xmpie_entries = undefined; </script>
<?php

// Attempt to previous options selected from cart.
if( isset($_GET['cart_item_key']) ) {
    //load from cart item
    $cart = $woocommerce->cart->get_cart();
    $cart_item = $cart[$_GET['cart_item_key']];
    if($cart_item) {
        if( isset($cart_item['udraw_data']['udraw_pdf_xmpie_product_data']) ) {
            //$json_data = json_decode(stripslashes($cart_item['udraw_data']['udraw_pdf_block_product_data']));
            echo '<script> _previous_pdf_xmpie_entries = jQuery.parseJSON(\''. stripslashes($cart_item['udraw_data']['udraw_pdf_xmpie_product_data']) .'\');</script>';
        }
    }
}

// Attempt to load saved customer design.
if( isset($_GET['udraw_access_key']) ) {
    $design = uDraw::get_udraw_customer_design($_GET['udraw_access_key']);
    if (strlen($design['design_data']) > 1 ) {
        //get file contents
        $contents = file_get_contents(UDRAW_STORAGE_DIR . '/' . stripslashes($design['design_data']));
        echo '<script> _previous_pdf_xmpie_entries = jQuery.parseJSON(\''. $contents .'\');</script>';
    }
}
?>

<script type="text/javascript">
    var use_colour_palette = <?php echo $use_colour_palette ?>;
    var udrawFileUploadHandlerURL = '<?php echo admin_url( 'admin-ajax.php' ) . '?action=udraw_price_matrix_upload&session='. uniqid() ?>';

    var lastXMPiePreview = '';
    var approvedButtonClicked = false;
    var saveLaterButtonClicked = false;
    var currentXMPieId = '<?php echo $xmpie_array[0]; ?>';
    
    <?php if($isMultiXMPie){ ?>
    var currentXMPieUniqueID = jQuery('#_product_selection').val();
    <?php } else { ?>
    var currentXMPieUniqueID = '<?php echo $uDrawPdfXMPie->get_product($xmpie_array[0])['UniqueID']; ?>';
    <?php } ?>
    var currentPDFDoc = '';

    function __process_xmpie_preview(doUpdate) {
        jQuery('div.product_image_preview').hide();
        jQuery('#previewDiv').hide();
        <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
        jQuery('.pdf-xmpie-preview-btn').html('<i class="fa fa-refresh"></i>&nbsp;Refresh Preview');
        <?php } ?>
        jQuery('.pdf-xmpie-next-span').fadeIn();
        jQuery('#pdf-xmpie-thumbnail-preview').fadeOut();
        //jQuery('#loadingDiv').fadeIn();
        if (typeof doUpdate !== 'undefined') {
            Xmpie.preview();
        }
    }

    function __process_xmpie_preview_completed() {
        //jQuery('#loadingDiv').hide();
        jQuery('#previewDiv').show();

        setTimeout(function () {
            if (saveLaterButtonClicked) {
                jQuery('#udraw_save_later').submit();
            }
        }, 1000);
    }

    function __process_xmpie_preview_success(result) {
        jQuery('div.product_image_preview').hide();
        jQuery("#previewDiv").empty();
        lastXMPiePreview = result.src + '_1.png';
        <?php if ($preview_mode == "image") { ?>
            jQuery("#previewDiv").empty();
            for (var i = 0; i < parseInt(result.count); i++) {
                jQuery("#previewDiv").append("<img style='display:block;margin:0 auto;' src='" + result.src + "_"+ (i+1) +".png"+"' /><br />");
            }
        <?php } else { ?>
        jQuery("#previewDiv").html("<iframe src='<?php echo wp_make_link_relative(UDRAW_PLUGIN_URL) ?>/assets/pdfjs/web/viewer.php?file=" + result.src + "&ps=<?php echo $allowPDFPrintSave; ?>' style='width:100%; min-height: 475px;' allowfullscreen webkitallowfullscreen></iframe>");
        <?php } ?>

        jQuery("#previewDiv").fadeIn();

        <?php if ($allowPDFPrintSave == "yes") {  ?>
        currentPDFDoc = result.src;
        <?php } ?>
        if (saveLaterButtonClicked) {
            var xmpieJsonEntry = Xmpie.getEntries(function(json_entry){
                jQuery('input[name="udraw_save_product_data"]').val(JSON.stringify(json_entry));
                jQuery('input[name="udraw_save_product_preview"]').val(lastXMPiePreview);
                
                if (typeof selectedByUser !== 'undefined') {
                    var pm_options = {
                        options: selectedByUser,
                        quantity: jQuery('input[name="udraw_price_matrix_qty"]').val(),
                        uploaded_file: jQuery('input[name="udraw_options_uploaded_files"]').val(),
                        converted_pdf: jQuery('input[name="udraw_options_converted_pdf"]').val(),
                        uploaded_excel: jQuery('input[name="udraw_options_uploaded_excel"]').val(),
                        dimensions: {
                            width: jQuery('[name="txtWidth"]').val(),
                            height: jQuery('[name="txtHeight"]').val()
                        }
                    }
                    jQuery('input[name="udraw_price_matrix_selected_by_user"]').val(JSON.stringify(pm_options));
                } else if (jQuery('table.variations').length > 0) {
                    var variation_options = new Array();
                    jQuery('select.variation_select').each(function(){
                        var object = {
                            name: jQuery(this).attr('name'),
                            value: jQuery(this).val()
                        }
                        variation_options.push(object);
                    });
                    jQuery('input[name="udraw_selected_variations"]').val(JSON.stringify(variation_options));
                }
                // Defer form submit to _completed function
                __process_xmpie_preview_completed();
            });
        }
        if (approvedButtonClicked) {
            var isValid = true;
            jQuery('#pdf-xmpie-inputs :input[required]:visible').each(function (index) {
                var element = jQuery(this);
                var elementValue = element.val();
                if (elementValue.length == 0) {
                    element.css("border", "1px solid #F00");
                    element.attr("placeholder", "Please fill in required field");
                    isValid = false;
                } else {
                    element.css("border", "1px solid #ccc");
                }
            });

            if (!isValid) { return false; }


            jQuery('#pdf-xmpie-product-ui').hide();

            /*if (typeof display_udraw_price_matrix_preview == 'function') {
                display_udraw_price_matrix_preview();
                jQuery('#udraw-price-matrix-ui').fadeIn();
            }*/

            __update_cart_form(function (){
                <?php if ($displayOptionsFirst == "yes") { ?>
                jQuery('#pdf-xmpie-thumbnail-preview').hide();
                if (jQuery('.variations_form').length > 0) {
                    jQuery('.variations_form').submit();
                } else {
                    jQuery('form.cart[method="post"]').submit();
                }
                <?php } else if ($isPriceMatrix) { ?>
                    // Set Image Preview
                    var _placeHolder = document.getElementById("udraw-product-preview");

                    while (_placeHolder.hasChildNodes()) {
                        _placeHolder.removeChild(_placeHolder.lastChild);
                    }

                    var imgPreview = document.createElement("img");
                    imgPreview.src = lastXMPiePreview;
                    imgPreview.setAttribute("class", "thumbnail col-md-10");
                    _placeHolder.appendChild(imgPreview);                
                <?php } else { ?>
                    //Check that variations are selectable
                    if (!jQuery('#udraw-select-options-ui').is(':visible')) {
                        jQuery('#pdf-xmpie-product-ui').hide();
                        jQuery('#udraw-select-options-ui').show();
                    }
                <?php } ?>

                jQuery('html,body').animate({ scrollTop: jQuery('head') }, 700);
            });
        }
    }

    function __update_cart_form(callback) {
        Xmpie.getEntries(function(xmpieJsonEntry){
            jQuery('<input>').attr({
                type: 'hidden',
                id: 'udraw_pdf_xmpie_product_id',
                name: 'udraw_pdf_xmpie_product_id',
                value: '<?php echo $xmpie_array[0]; ?>'
            }).appendTo('form.cart');

            jQuery('<input>').attr({
                type: 'hidden',
                id: 'udraw_pdf_xmpie_product_thumbnail',
                name: 'udraw_pdf_xmpie_product_thumbnail',
                value: lastXMPiePreview
            }).appendTo('form.cart');

            jQuery('<input>').attr({
                type: 'hidden',
                id: 'udraw_pdf_xmpie_product_data',
                name: 'udraw_pdf_xmpie_product_data',
                value: JSON.stringify(xmpieJsonEntry)
            }).appendTo('form.cart');

            if (typeof callback === 'function') {
                callback();
            }
        });
    }

    function __process_pdf_form_completed() {

    }

    function enableDesignNowButton() {
        jQuery('#udraw-options-page-design-btn').removeClass("isLoading");
        jQuery('#udraw-options-page-design-btn i').remove();
        jQuery('#udraw-options-page-design-btn').attr('disabled', false);
        jQuery('#udraw-options-page-design-btn span').show();
        jQuery('#udraw-options-page-design-btn i').hide();
    }

    jQuery(document).ready(function ($) {
        <?php
            if( isset($_GET['udraw_access_key']) ) {
                if ($design['variation_options'] !== NULL) {
                    $variation_options = json_decode(stripslashes($design['variation_options']));
                    for ($i = 0; $i < count($variation_options); $i++) {
                        $name = $variation_options[$i]->name;
                        $value = $variation_options[$i]->value;
                        ?>
                            jQuery('[name="<?php echo $name ?>"]').val('<?php echo $value ?>');
                        <?php
                    }
                }
            }
        ?>
        
        $('div#pdf-xmpie-inputs').xmpieProduct({
            productId: currentXMPieUniqueID,
            slider: $("#pdf-xmpie-thumbnail-preview"),                               // div for output
            loader: $("#loadingDiv"),                                           // div for Loader image
            submitButton: $(".pdf-xmpie-preview-btn"),                               // Preview button selector
            priceTag: $(".price"),                                                  // Price Display Tag
            qtyTag: $(".input-text.qty.text"),                                       // Quantity Panel Tag
            xmpieHandler: "https://live.goepower.com/CS_Handlers/Remote/XmpieRemoteHandler.ashx"     // Fields Provider, Asset Uploader and Previewer
        });

        enableDesignNowButton();

        <?php if ($allowPDFPrintSave == "yes") {  ?>
        jQuery('#udraw-download-pdf-btn').click(function () {
            if (currentPDFDoc.length > 0) {
                window.open(currentPDFDoc, '_blank');
            }
        });
        <?php } ?>

        jQuery('.pdf-xmpie-preview-btn').click(function () { __process_xmpie_preview(); });

        jQuery('.pdf-xmpie-next-btn').click(function () {
            saveLaterButtonClicked = false;
            approvedButtonClicked = true;
            __process_xmpie_preview(true);

            if (typeof display_udraw_price_matrix_preview === 'function') {
                   display_udraw_price_matrix_preview();
                   jQuery('#udraw-price-matrix-ui').fadeIn();
            }

            __update_cart_form();
            <?php if ($displayOptionsFirst === "yes") { ?>
                if (jQuery('.variations_form').length > 0) {
                    jQuery('.variations_form').submit();
                } else if (jQuery('form.cart').length > 0) {
                    jQuery('form.cart').submit();
                }
               <?php } ?>
        });

        jQuery('button.udraw-save-later-design-btn').click(function () {
            saveLaterButtonClicked = true;
            approvedButtonClicked = false;
            __process_xmpie_preview(true);
        });

        <?php if ($displayOptionsFirst == "yes") { ?>
            jQuery('#pdf-xmpie-product-ui').hide();

        <?php } else { ?>
            jQuery('#pdf-xmpie-product-ui').show();
            jQuery('#udraw-price-matrix-ui').hide();
        <?php } ?>

        jQuery('#_product_selection').change(function () {
            jQuery('#pdf-xmpie-inputs').empty();
            currentXMPieUniqueID = jQuery('#_product_selection').val();
			_load_xmpie_template(currentXMPieUniqueID);
        });

		jQuery(document).on('udraw_price_matrix_load_templates', function(event) {
			if (typeof event.template_id !== 'undefined') {
				_load_xmpie_template(event.template_id);
			}
		});

		function _load_xmpie_template(template_id) {
			$('div#pdf-xmpie-inputs').xmpieProduct({
				productId: template_id,
				slider: $("#pdf-xmpie-thumbnail-preview"),                               // div for output
				loader: $("#loadingDiv"),                                           // div for Loader image
				submitButton: $(".pdf-xmpie-preview-btn"),                               // Preview button selector
				priceTag: $(".price"),                                                  // Price Display Tag
				qtyTag: $(".input-text.qty.text"),                                       // Quantity Panel Tag
				xmpieHandler: "https://live.goepower.com/CS_Handlers/Remote/XmpieRemoteHandler.ashx"     // Fields Provider, Asset Uploader and Previewer
			});	
		}

        <?php if ($product_id == "simple") { ?>
        jQuery('.cart').click(function() {
            __update_cart_form();
        });
        <?php } ?>


        jQuery('#udraw-options-page-design-btn').click(function () {
            jQuery('#pdf-xmpie-product-ui').show();
        });
        jQuery('#show-udraw-display-options-ui-btn').click(function(){
            jQuery('.single-product-image').addClass("span6");
            jQuery('.single-product-image').css({ "min-width": "" });

            jQuery('.product_title').css('padding-left', '').css('margin', '');
            jQuery('.summary').css('float', '').css('padding-left', '');
            jQuery('.price').show();
            jQuery('#primary, #main').css("width", "");
            jQuery('.entry-summary').addClass("col-lg-6 col-md-5 col-sm-12");
            jQuery('.summary-before').addClass("col-lg-6 col-md-7 col-sm-12");
            jQuery('.images').show();

            jQuery('.description').show();
            jQuery('.entry-summary').show();
            jQuery('#product-tab').show();
            jQuery('div.container #design-now-div').css('display', 'inline-block');                
            jQuery('#pdf-xmpie-product-ui').hide();
            jQuery('#udraw-display-options-ui').show();
        });
        jQuery("#pdf-xmpie-inputs").on("click", "a.trigger-btn", function(){
            var _id = jQuery(this).find('input')[0].id;
            jQuery("#"+_id).trigger("click");
        })
        jQuery('#udraw-next-step-1-btn-price-matrix').click(function(){
           jQuery('form.cart[method="post"]').submit(); 
        });
        jQuery('#variable-product-back-btn').on('click', function() {
            approvedButtonClicked = false;
            jQuery('#udraw-select-options-ui').hide();
            jQuery('#pdf-xmpie-product-ui').show();
        });
        jQuery('#udraw-add-to-cart-btn').click(function(){
            if (_previous_pdf_xmpie_entries != undefined) {
                //If updating a cart item, remove cart item first before adding to cart;
            }
            jQuery('.cart').submit();
            return false;
        });
        
        <?php echo $_udraw_settings['udraw_pdf_template_js_hook']; ?>
            
    });
    jQuery('#pdf-xmpie-inputs').on('xmpie_fields_loaded', function () {
        jQuery('#udraw-options-page-design-btn').show();

        if (_previous_pdf_xmpie_entries != undefined) {
            for (var i = 0; i < _previous_pdf_xmpie_entries.length; i++) {
                var property = _previous_pdf_xmpie_entries[i];
                var dialID = property.DialID;
                var stepID = property.StepID;
                var element = jQuery('.ep-xmpie-field[data-stepid="'+ stepID +'"][data-dialid="'+dialID+'"]');
                var fieldType = element.data('fieldtype');
                if (element){
                    if (fieldType == 'styledisplayattr') {
                        var selectedValues = property.Value.split('|');
                        var selectedStyle = [selectedValues[1],selectedValues[2]].join('|');
                        jQuery('select#AttrFont_'+stepID+'_'+dialID).val(selectedStyle);
                        var selectedSize = selectedValues[3];
                        jQuery('select#AttrSize_'+stepID+'_'+dialID).val(selectedSize);
                        var selectedColour = selectedValues[4];
                        console.log(selectedColour);
                        jQuery('input#ColorPicker_'+stepID+'_'+dialID).data('rgb', selectedColour).css('color', 'rgb('+selectedColour+')').css('background-color', 'rgb('+selectedColour+')');
                    }
                    if (fieldType == 'dropdown') {
                        jQuery('select#Input_'+stepID+'_'+dialID).val(property.Value);
                    }
                    if (fieldType == 'multiline') {
                        jQuery('textarea#Input_'+stepID+'_'+dialID).val(property.Value);
                    }
                    if (fieldType == 'image') {
                        var selectedValues = property.Value.split('::::');
                        //Can't reload the saved uploaded image values
                        //jQuery('img#Input_'+stepID+'_'+dialID).data('assetid',selectedValues[0]).data('filename',selectedValues[1]).attr('title', selectedValues[1]);
                    }
                    if (fieldType == 'textbox') {
                        jQuery('input#Input_'+stepID+'_'+dialID).val(property.Value);
                    }
                    if (fieldType == 'radio') {
                        jQuery('form#Input_'+stepID+'_'+dialID+' input[value="'+property.Value+'"]').prop('checked',true);
                    }
                    if (fieldType == 'textboxattr') {
                    }
                }
            }
        }
    });
</script>

<style type="text/css">
    #pdf-xmpie-product-ui .select2-container .select2-choice {
        height: 55px !important;
        line-height: 55px !important;
    }
    .select2-selection {
        height: 55px !important;
        line-height: 55px !important;
        padding-top: 1px !important;
        padding-bottom: 1px !important;
    }

    <?php echo $_udraw_settings['udraw_pdf_template_css_hook']; ?>
</style>


<?php 

function pdf_xmpie_display_approve_html($_udraw_settings, $allowPDFPrintSave, $displayOptionsFirst) {    
    ?>
    <div class="row"  style="padding-right:30px; padding-bottom:5px;">
        <div class="col-md-6 col-md-offset-6">
            <?php if ($displayOptionsFirst === 'yes') { ?>
            <a href="#" class="btn btn-default" id="show-udraw-display-options-ui-btn">
                <i class="fa fa-chevron-left"></i><span>&nbsp;&nbsp;&nbsp;Back to Options</span>
            </a>
            <?php } ?>
            <?php if ($allowPDFPrintSave == "yes") { ?>
                <button id="udraw-download-pdf-btn" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp;<?php _e('Download PDF', 'udraw') ?></button> 
            <?php } ?>

            <?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
                <span style="float:right;">&nbsp;&nbsp;</span>
                <button class="btn btn-primary udraw-save-later-design-btn"><i class="fa fa-floppy-o"></i>&nbsp;<?php _e('Save For Later', 'udraw') ?></button>        
            <?php } ?>

            <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
                <button class="pdf-xmpie-preview-btn btn btn-primary"><i class="fa fa-file-image-o"></i>&nbsp;<?php _e('Preview Now', 'udraw') ?></button>
            <?php } ?>

            <span style="padding-left:10px; display:none;" class="pdf-xmpie-next-span">
                <button class="pdf-xmpie-next-btn btn btn-success"><?php _e('Approve & Continue', 'udraw') ?> &nbsp; <i class="fa fa-chevron-right"></i></button>
            </span>
        </div>
    </div>
    <?php
}
?>

<style>
    #udraw-bootstrap input select {
        max-width:89% !important;
    }

    @media (max-width:1200px) {
        #udraw-bootstrap .form-control, .udrawSelect2 {
            max-width: 75vw !important;
            min-width: 75vw !important;
        }

        #previewDiv {
            width: 75vw !important;
        }

        .container div {
            padding: 0px !important;
            margin: 0px !important;
        }
    }

    #udraw-bootstrap {
        min-width: 100% !important;
    }

    #udraw-bootstrap .container {
        width: 100% !important;
        padding-right: 0px !important;
        padding-left: 0px !important;
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    #udraw-bootstrap .iris-picker .iris-square {
        margin-right: 5px;
    }
    #udraw-bootstrap .iris-picker .iris-square-handle {
        top: -2px;
        left: -2px;
    }
    #udraw-bootstrap .wp-color-result {
        height: 30px;
        vertical-align: top;
    }
    #udraw-bootstrap .wp-color-result:after {
        display:none;
    }
    #udraw-bootstrap .wp-picker-holder{
        position: absolute;
        z-index: 1;
    }
    #udraw-bootstrap input[type="file"] {
        width: 100%;
    }
</style>