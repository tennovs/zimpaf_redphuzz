<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');

global $woocommerce, $product, $post;

if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_type = $product->get_type();
} else {
    $product_type = $product->product_type;
}

$GoEpower = new GoEpower();
$uDrawPDFBlocks = new uDrawPDFBlocks();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();

$allowPDFPrintSave = get_post_meta($post->ID, '_udraw_pdf_allow_print_save', true);
$displayOptionsFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);

// Support for multiple block templates for one product.
// If the block template is 'old' method, we will change it to array 'new' method of getting id.
$blocks_array = Array();
if (isset($blockProductId)) {
    if (gettype($blockProductId) == "string") {
        if (strlen($blockProductId) > 0) {
            array_push($blocks_array, $blockProductId);
        }
    } else if (gettype($blockProductId) == "array") {
        $blocks_array = $blockProductId;    
    } else {
        // Uh Oh! Spagetios!
        // This condition shouldn't happen and there was a problem defining the product Id.
        header('/', true);
    }
}

$block_product = $uDrawPDFBlocks->get_product($blocks_array[0]);
// Check if blocks are multiple or single.
$isMultiBlock = (count($blocks_array) > 1) ? true : false;

// Reverse compatibility of older GoEpower implementations
if ($_udraw_settings["goepower_username"] === "") {
    $blocks_array_updated = Array();
    foreach ($blocks_array as $block) {                                
        $block_data = $uDrawPDFBlocks->get_product($block);
        array_push($blocks_array_updated, $block_data['ProductID']);
    }
    $blocks_array = $blocks_array_updated;
}

$goepower_token = '';
if (strlen($blocks_array[0]) > 10) {
    $_auth_object = $GoEpower->get_auth_object();
    $goepower_token = $_auth_object->Token;
    $goepower_custom_id = '';
    if (isset($_auth_object->CustomID)) {
        $goepower_custom_id = $_auth_object->CustomID;
    }
}

if (isset($_REQUEST['display_error']) && $_REQUEST['display_error'] === '1') {
    ?>
    <div style="background-color: #ffe8e8; padding: 10px;">
        <?php _e('There was an error adding this item to cart. Please try again.', 'udraw'); ?>
    </div>
    <?php
}

$designer_location = $_udraw_settings['goepower_designer_location'];
$udraw_pdf_layout_override = get_post_meta($post->ID, '_udraw_pdf_layout_override', true);
if ($udraw_pdf_layout_override) {
        /* Overriding Designer Location / Layout on Product Settings Page */
	$designer_location = get_post_meta($post->ID, '_udraw_pdf_layout', true);
}
?>



<form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
    <input type="hidden" name="udraw_save_product_data" value="" />
    <input type="hidden" name="udraw_selected_product" value="" />
    <input type="hidden" name="udraw_save_product_preview" value="" />
    <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
    <input type="hidden" name="udraw_save_access_key" value="<?php echo (isset($_GET['udraw_access_key'])) ? $_GET['udraw_access_key'] : NULL; ?>" />
    <input type="hidden" name="udraw_is_saving_for_later" value="1" />
    <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
    <input type="hidden" name="udraw_selected_variations" value="" />
    <?php wp_nonce_field('save_udraw_customer_design'); ?>
</form>

<div id="pdf-block-product-ui" style="display: none;">
    <div id="udraw-bootstrap">
        <?php
        $button_placement = "top";
        if (isset($_udraw_settings['goepower_pdf_approve_btn_below_preview']) && $_udraw_settings['goepower_pdf_approve_btn_below_preview']) { $button_placement = "bottom"; }
        if ($_udraw_settings['goepower_approve_button_placement'] == "bottom") { $button_placement = "bottom"; }
        if ($_udraw_settings['goepower_approve_button_placement'] == "both") { $button_placement = "both"; }

        $preview_mode = "image";
        if ($_udraw_settings['goepower_preview_mode'] == "pdf") { $preview_mode = "pdf"; }

        if($button_placement == "top" || $button_placement == "both") {
            pdf_block_display_approve_html($_udraw_settings, $displayOptionsFirst, $allowPDFPrintSave, $designer_location);
        }
        ?>
        <?php if ($designer_location == "onepageh") {  
        $displayOptionsFirst = "yes";   
        ?>
        <div id="price-matrix-container">
        </div>
        <?php }  ?>
        <div class="container">
            <div class="row">
                <div class="main-row">
                    <?php if ($isMultiBlock) { ?>
                    <div class="col-xs-12 col-md-12 col-lg-4" style="float: none;">
                        <div>Product Selection</div>
                        <div>
                            <select class="udrawProductSelect2 dropdownList form-control" id="_product_selection" style='width:100%;'>
                            <?php 
                                foreach ($blocks_array as $block) {                                
                                    $block_json = $uDrawPDFBlocks->get_product($block);
                                    echo '<option value="'. $block . '" data-thumb-small="'. $block_json['ThumbnailSmall'] .'" data-thumb-large="'. $block_json['ThumbnailLarge'] .'">' . $block_json['ProductName'] . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-xs-12 col-md-12 col-lg-12" style="float: none;">
                        <div id="w2p-pdf-template-wrapper">
                      
                            <div id="w2p-pdf-template-container">
                                <?php if ($designer_location == "onepagev") {  
                                    $displayOptionsFirst = "yes";   
                                ?>
                                <div id="price-matrix-container"></div>
                                <?php }  ?>
                                <div id="w2p-pdf-template-product"></div>
                                <div id="w2p-pdf-template-preview">
                                    <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
                                    <div class="refresh-preview-button">
                                        <button class="pdf-block-preview-btn btn btn-primary"><i class="far fa-file-image"></i>&nbsp;<?php _e('Refresh Preview', 'udraw') ?></button>
                                        <p style="display: inline-block">Click to view your changes</p>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!--Approve Artwork Disclaimer-->
                            <?php if ($_udraw_settings['approve_proof_text'] == "") { ?>
                                <div class="accept-proof"><p class="acceptProof"><span class="approval">ARTWORK APPROVAL: </span>By clicking the Approve &amp; Continue button, I am verifying that I have customized this template to my satisfaction and give <?php echo get_bloginfo( 'name' ); ?> authorization to print the artwork exactly as presented here. I understand that <?php echo get_bloginfo( 'name' ); ?> will not be held responsible for any typos or errors that I have inputted or any logo reproduction quality issues based on my provided art file.</p></div>
                            <?php } else { ?>
                                <div class="accept-proof"><p class="acceptProof"><span class="approval">ARTWORK APPROVAL: </span><?php echo $_udraw_settings['approve_proof_text']; ?></p></div>
                            <?php } ?>
                            <!--Approve Artwork Disclaimer ends-->
                        </div>
                    </div>                
                </div>
            </div>
        </div>
        <?php
        if($button_placement == "bottom" || $button_placement == "both") {
            pdf_block_display_approve_html($_udraw_settings, $displayOptionsFirst, $allowPDFPrintSave, $designer_location);
        }
        ?>
    </div>

    <?php echo $_udraw_settings['udraw_pdf_template_html_hook']; ?>
</div>

<?php 

function pdf_block_display_approve_html($_udraw_settings, $displayOptionsFirst, $allowPDFPrintSave, $designer_location) {    
    ?>
    <div class="row" style="margin: 0; padding: 5px; width: 100%;">
        <div class="buttonGrp">
            <?php if ($displayOptionsFirst === "yes" && $designer_location !== "onepageh" && $designer_location !== "onepagev") { ?>
            <button class="btn btn-default back_to_options"><i class="fas fa-chevron-left"></i>&nbsp;<?php _e('Back to Options', 'udraw') ?></button> 
            <?php } ?>
            <?php if ($allowPDFPrintSave === "yes") { ?>
                <button id="udraw-download-pdf-btn" class="btn btn-primary udraw-download-pdf-btn"><i class="fas fa-cloud-download-alt"></i></i>&nbsp;<?php _e('Download PDF', 'udraw') ?></button> 
            <?php } ?>
            <?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
                <span style="float:right;">&nbsp;&nbsp;</span>
                <button class="btn btn-primary udraw-save-later-design-btn"><i class="far fa-save"></i>&nbsp;<?php _e('Save For Later', 'udraw') ?></button>        
            <?php } ?>
            <?php if ($designer_location === "onepageh" || $designer_location === "onepagev") { ?>
            <button class="pdf-block-next-btn btn btn-success"><?php _e('Approve & Add to Cart', 'udraw') ?> &nbsp; <i class="fas fa-chevron-right"></i></button>
            <?php }  else {?>
            <button class="pdf-block-next-btn btn btn-success" style="float: right;"><?php _e('Approve & Continue', 'udraw') ?> &nbsp; <i class="fas fa-chevron-right"></i></button>
            <?php } ?>
        </div>
        <!--<?php if ($designer_location === "onepagev") { ?>
            <div class="RegPrice"></div>
        <?php } ?>-->
    </div>
    <?php
}
?>

<script type="text/javascript">
    var _previous_pdf_block_entries = new Array();
    var currentBlockId = '<?php echo $blocks_array[0]; ?>';
    <?php

    // Attempt to previous options selected from cart.
    if( isset($_GET['cart_item_key']) ) {
        //load from cart item
        $cart = $woocommerce->cart->get_cart();
        $cart_item = $cart[$_GET['cart_item_key']];
        if($cart_item) {
            if( isset($cart_item['udraw_data']['udraw_pdf_block_product_data']) ) {
                //$json_data = json_decode(stripslashes($cart_item['udraw_data']['udraw_pdf_block_product_data']));
                echo '_previous_pdf_block_entries = jQuery.parseJSON(\''. ($cart_item['udraw_data']['udraw_pdf_block_product_data']) .'\');';
                echo 'currentBlockId = "'. $cart_item['udraw_data']['udraw_pdf_block_product_id'] . '";';
            }
        }
    }

    // Attempt to load saved customer design.
    if( isset($_GET['udraw_access_key']) ) {
        $design = uDraw::get_udraw_customer_design($_GET['udraw_access_key']);
        if (strlen($design['design_data']) > 1 ) {
            if (strlen($design['design_data']) < 100) {
                if (is_file(UDRAW_STORAGE_DIR . $design['design_data'])) {
                    $_saved_block_data = file_get_contents(UDRAW_STORAGE_DIR . $design['design_data']);
                    echo '_previous_pdf_block_entries = jQuery.parseJSON(\''. $_saved_block_data .'\');';
                }
            } else {
                echo '_previous_pdf_block_entries = jQuery.parseJSON(\''. stripslashes($design['design_data']) .'\');';
            }   
        }
    }

    ?>
    var appPath =  '<?php echo $GoEpower->get_api_url(); ?>/';
    var lastBlockPreview = '';
    
    var currentPDFDoc = '';
    var hideTextLabels = false;
    <?php if ($_udraw_settings['goepower_hide_labels_on_text_input']) { echo "hideTextLabels = true;"; } ?>

    function __process_pdf_preview(callback) {
        jQuery('#previewDiv').hide();
        <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
            jQuery('.pdf-block-preview-btn').html('<i class="fa fa-refresh"></i>&nbsp;Refresh Preview');
        <?php } ?>
        BlocksManager.process_preview(true, callback);
    }

    function __update_cart_form() {
        var goepower_url = 'https://live.goepower.com/';
        if (w2p_bm.options.api_path.indexOf('w2pshop') > 0) {
            goepower_url = 'https://live.w2pshop.com/';
        }
        if (w2p_bm.last_preview_url.indexOf(goepower_url) > -1) {
            goepower_url = '';
        }
        jQuery('<input>').attr({
            type: 'hidden',
            id: 'udraw_pdf_block_product_id',
            name: 'udraw_pdf_block_product_id',
            value: w2p_bm.options.product_unique_id
        }).appendTo('form.cart');
        
        jQuery('<input>').attr({
            type: 'hidden',
            id: 'udraw_pdf_block_product_thumbnail',
            name: 'udraw_pdf_block_product_thumbnail',
            value: w2p_bm.last_preview_url
        }).appendTo('form.cart');

        jQuery('<input>').attr({
            type: 'hidden',
            id: 'udraw_pdf_block_product_data',
            name: 'udraw_pdf_block_product_data',
            value: JSON.stringify(w2p_bm.current_block_json_entry)
        }).appendTo('form.cart');

        if (typeof pdf_block_order_info === "object") {
            if (pdf_block_order_info.length > 0) {
                jQuery('<input>').attr({
                    type: 'hidden',
                    id: 'udraw_pdf_order_info',
                    name: 'udraw_pdf_order_info',
                    value: JSON.stringify(pdf_block_order_info)
                }).appendTo('form.cart');
            }
        }
    }

    function enableDesignNowButton() {
        jQuery('#udraw-options-page-design-btn').removeClass("isLoading");
        jQuery('#udraw-options-page-design-btn i').remove();
        jQuery('#udraw-options-page-design-btn').attr('disabled', false);
        jQuery('#udraw-options-page-design-btn span').show();
        jQuery('#udraw-options-page-design-btn i').hide();
    }

    jQuery(document).ready(function ($) {
        //Load up saved variation options if available
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
        
        var _pdf_container_element = '#w2p-pdf-template-product'
        _load_pdf_template(currentBlockId);

        <?php if ($allowPDFPrintSave == "yes") {  ?>
        jQuery('#udraw-download-pdf-btn, .udraw-download-pdf-btn').click(function () {
                var epowerURL = '';                
		        if (w2p_bm.last_preview_url.indexOf('https://live.goepower.com/') === -1) {
                    epowerURL = 'https://live.goepower.com/';
                }
                var printProof = w2p_bm.last_preview_url;
                window.open(printProof, '_blank');
        });
        <?php } ?>

        jQuery('.pdf-block-preview-btn').click(function () { __process_pdf_preview(); });        

        jQuery('.pdf-block-next-btn').click(function () {
            __process_pdf_preview(function(){
               var isValid = true;
               jQuery('#pdf-block-product-ui :input[required]:visible').each(function (index) {
                   var element = jQuery(this);
                   var elementValue = element.val();
                   if (elementValue.length === 0) {
                       element.css("border", "1px solid #F00");
                       element.attr("placeholder", "Please fill in required field");
                       isValid = false;
                   } else {
                       element.css("border", "1px solid #ccc");
                   }
                });

                if (!isValid) {
                   return false;
                }

                jQuery('#pdf-block-product-ui').hide();

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
               <?php } else if ($isPriceMatrix) { ?>
                   // Set Image Preview
                   var _placeHolder = document.getElementById("udraw-product-preview");

				   if (_placeHolder !== undefined && _placeHolder !== null) {
					   while (_placeHolder.hasChildNodes()) {
						   _placeHolder.removeChild(_placeHolder.lastChild);
					   }

					   var imgPreview = document.createElement("img");
                       var epowerURL = '';
                       
					   if (w2p_bm.last_preview_url.indexOf('https://live.goepower.com/') === -1) {
						   epowerURL = 'https://live.goepower.com/';
                       }
                       
					   imgPreview.src = w2p_bm.last_preview_url;
					   imgPreview.setAttribute("class", "thumbnail col-md-10");
					   _placeHolder.appendChild(imgPreview);
				   }

                <?php if ($button_placement === "bottom" || $button_placement === "both") { ?>
                    // Move to top of the page.
                    jQuery('html,body').animate({ scrollTop: jQuery('head') }, 700);
                <?php } ?>
               <?php } else { ?>
                   //Check that variations are selectable
                   if (!jQuery('#udraw-select-options-ui').is(':visible')) {
                       jQuery('#pdf-block-product-ui').hide();
                       jQuery('#udraw-select-options-ui').show();
                       <?php if ($button_placement == "bottom" || $button_placement == "both") { ?>
                           // Move to top of the page.
                           jQuery('html,body').animate({ scrollTop: jQuery('head') }, 700);
                       <?php } ?>
                   }
               <?php } ?>

               <?php if ($displayOptionsFirst !== "yes") { ?>
                   jQuery('div.udraw-product').show();
				   jQuery('div.summary.entry-summary').show();
               <?php } ?>
            });
        });

        jQuery('button.udraw-save-later-design-btn').click(function () {
            __process_pdf_preview(function(){
                var epowerURL = '';
                if (w2p_bm.last_preview_url.indexOf('https://live.goepower.com/') === -1) {
                    epowerURL = 'https://live.goepower.com/';
                }
                jQuery('input[name="udraw_selected_product"]').val(w2p_bm.blocks_info.ProductName);
                jQuery('input[name="udraw_save_product_data"]').val(JSON.stringify(w2p_bm.current_block_json_entry));
                jQuery('input[name="udraw_save_product_preview"]').val(w2p_bm.last_preview_url);
                //Save selected PM or variable options
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
                jQuery('#udraw_save_later').submit();
            });
        });

        setTimeout(function () {
            <?php if ($displayOptionsFirst === "yes" && ($designer_location === "onepageh" || $designer_location === "onepagev")) { ?>
                jQuery('div.product.udraw-product').hide();
                jQuery('#pdf-block-product-ui').show();
                jQuery('#udraw-price-matrix-ui').show();
                jQuery('div.summary.entry-summary').hide();
            <?php } else if ($displayOptionsFirst !== "yes") {?>
                jQuery('div.product.udraw-product').hide();
                jQuery('div.summary.entry-summary').hide();
                jQuery('#pdf-block-product-ui').show();
            <?php } else if ($displayOptionsFirst === "yes") { ?>
                jQuery('#udraw-price-matrix-ui').show();
                jQuery('#pdf-block-product-ui').hide();
                jQuery('div.summary.entry-summary').show();
                jQuery('div.product.udraw-product').show();
            <?php } ?>
        }, 500);

        jQuery('#_product_selection').change(function () {
            _load_pdf_template(jQuery('#_product_selection').val());
        });

        jQuery(document).on('udraw_price_matrix_load_templates', function(event) {
            if (typeof event.template_id !== 'undefined' && event.template_id !== null) {
                _load_pdf_template(event.template_id);
            } else {
                var template_id = '<?php echo $block_product['UniqueID'] ?>';
                if((typeof(template_id) !== undefined) && template_id !== null) {
                    _load_pdf_template(template_id);
                }
            }
        });

        function _load_pdf_template(currentBlockId) {
            $(_pdf_container_element).empty();
            window.w2p_bm = BlocksManager.init(_pdf_container_element, {
                goepower_token : '<?php echo $goepower_token ?>',
                product_unique_id : currentBlockId,
                preview_element: '#w2p-pdf-template-preview',
                hide_text_labels: false,
                api_path: appPath,
                pid: currentBlockId,
                upload_path: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                upload_file_data: {
                    action: 'udraw_pdf_block_upload',
                    session: '<?php echo uniqid() ?>'
                },
                previous_block_entries: _previous_pdf_block_entries,
                preview_mode: '<?php echo $preview_mode ?>',
                allow_download_pdf: false,
                pdf_viewer_path: '<?php echo UDRAW_PLUGIN_URL ?>/assets/pdfjs/web/viewer.php?file='
            });
            
            $(w2p_bm.options.preview_element).on('get_blocks_completed', function(){
                $('select', _pdf_container_element).addClass('udrawProductSelect2 dropdownList form-control');
                w2p_bm.process_preview();
            });

            enableDesignNowButton();

            jQuery('#pdf-block-product-ui').trigger({
                type: 'product-loaded'
            });
        }

        <?php if ($product_type == "simple") { ?>
            jQuery('.cart').click(function() {
                __update_cart_form();
            });
        <?php } ?>

        jQuery('#udraw-options-page-design-btn').click(function () {
            jQuery('#pdf-block-product-ui').show();
        });
        jQuery('#udraw-add-to-cart-btn').click(function(){
            if (_previous_pdf_block_entries !== undefined) {
                //If updating a cart item, remove cart item first before adding to cart;
            }
            if (jQuery('.price_matrix_form').length > 0) {
                jQuery('.price_matrix_form').submit();
            } else {
                jQuery('.variations_form').submit();
            }
            return false;
        });
        jQuery('button#variable-product-back-btn').on('click',function(){
            jQuery('#pdf-block-product-ui').show();
        });
        
        jQuery('button.back_to_options').on('click', function(){
            jQuery('div#pdf-block-product-ui').hide();
            jQuery('div.udraw-product').show();
        });
        
        <?php if ($designer_location === "onepagev") {  ?>
            var price_matrix_form = $('form.price_matrix_form').detach();
            $('#price-matrix-container').append(price_matrix_form);
            $('#udraw-options-submit-form-btn').hide();
            var product_title = $('h1.product_title').detach();
            $('#pdf-block-product-ui').prepend(product_title);
            $('#price-matrix-container,#w2p-pdf-template-product').wrapAll('<div class="optionsRow"></div>');
        <?php }
        if ($designer_location === "onepageh") {?>
            var price_matrix_form = $('form.price_matrix_form').detach();
            $('#price-matrix-container').append(price_matrix_form);
            $('#udraw-options-submit-form-btn').hide();
            var product_title = $('h1.product_title').detach();
            $('#pdf-block-product-ui').prepend(product_title);
        <?php } ?>
        <?php if ($_udraw_settings['goepower_pdf_preview_auto_update']) { ?>
            $(_pdf_container_element).on('blur', '.w2p-pdf-template-input-text', function () { w2p_bm.process_preview();});
            $(_pdf_container_element).on('change', '.w2p-pdf-template-input-select', function () { w2p_bm.process_preview(); });
            $(_pdf_container_element).on('change', '.w2p-pdf-template-input-textarea', function () { w2p_bm.process_preview(); });
            $(_pdf_container_element).on('change', '.w2p-pdf-template-input-radio', function () { w2p_bm.process_preview(); });
            $(_pdf_container_element).on('change', '.block-image-modal-selected', function () { w2p_bm.process_preview(); });
            //$(_pdf_container_element).on('click', '.block_image_select_wrapper span a', function () { jQuery('#w2p-loading-animation').fadeIn(); });
			//$(document).on('click', '.modal_close', function () { if (jQuery('.loadingAnimationPreview').length > 0) {jQuery('#w2p-loading-animation').fadeOut();}});
        <?php } ?>

        <?php echo $_udraw_settings['udraw_pdf_template_js_hook']; ?>
   });
</script>
<style>
    #udraw-bootstrap input[type="text"], #udraw-bootstrap select {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 6px 12px;
    }

    #udraw-bootstrap .pdf-text-input-maxlength { width: 100% !important; }

    #udraw-bootstrap input[type="color"]{
		width: 40px !important;
    }

	#udraw-bootstrap .textstyle {
		width: auto !important;
		display: inline-block !important;
	}

    #udraw-bootstrap {
        background: white;
        width: 90%;
        height: 90%;
        margin: 5%;
        overflow: auto;
        font-size: 15px;
    }

    #udraw-bootstrap .container {
        width: 100%;
        margin: 0;
        padding: 0;
        background: none;
    }

    .slim-btn {
        width: 40px !important;
        height: 40px !important;
        font-size: 5px !important;
        color: transparent !important;
    }

    .slim {
        width: 50%;
        margin: auto;
        display: none;
        background-color: transparent !important;
    }

    #udraw-bootstrap div.container div.row div.main-row {
	    width: 100%;
    }

    div#w2p-pdf-template-container > div {
        display: inline-block;
        width: 48%;
        margin: auto;
        vertical-align: top;
        padding: 1%;
    }

    #w2p-pdf-template-preview { 
        text-align: center; 
    } 

    #w2p-pdf-template-preview img { 
        margin-bottom: 5%;
        box-shadow: rgba(0,0,0,0.25) 0 0 10px;
        border: 1px solid #dfdfdf;
    }

    #w2p-pdf-template-preview .refresh-preview-button {
        padding-bottom: 5px;
    }
    
    #pdf-block-product-ui .select2-container .select2-choice {
        height: 55px !important;
        line-height: 55px !important;
    }

    .select2-selection {
        height: 55px !important;
        line-height: 55px !important;
        padding-top: 1px !important;
        padding-bottom: 1px !important;
    }

    #udraw-bootstrap input, #udraw-bootstrap select, #udraw-bootstrap textarea {
        width: 100%;
    }

    #udraw-bootstrap .inline, #udraw-bootstrap .multi {
        width: auto !important;
        display: inline-block !important;
        min-width: 40px;
        margin-right: 5px;
    }
    
    <?php if ($designer_location === 'popup') { ?>
    #pdf-block-product-ui {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(43, 43, 43, 0.3);
        width: 100%;
        height: 100%;
        z-index: 9999;
    }
    <?php } else if ($designer_location === "onepageh") {  ?>
    #udraw-bootstrap div.container {
        width: 69%;
        display: inline-block;
        float: right;
    }
    #udraw-bootstrap div#price-matrix-container {
        width: 30%;
        display: inline-block;
        float: left;
    }

    #udraw-bootstrap div.fieldlabel, #udraw-bootstrap div.fieldcontrol {
	width: 100% !important;
    }
    @media (max-width: 760px) {
        #udraw-bootstrap div.container, #udraw-bootstrap #price-matrix-container {
            width: 100% !important;
            display: inline-block;
            float: none !important;
            padding: 0px !important;
        }  
        div#w2p-pdf-template-container > div {
            width: 100% !important;
        }
    }
    <?php } else if ($designer_location === "onepagev"){ ?>
    #udraw-bootstrap div.container, #udraw-bootstrap div#price-matrix-container {
        width: 100%;
        display: inline-block;
        float: none;
        padding: 0px !important;
    }
    #udraw-bootstrap div.fieldlabel, #udraw-bootstrap div.fieldcontrol {
	width: 100% !important;
    }
    .buttonGrp{width: 75%;}
    .RegPrice {width: 25%;}
    
    @media (max-width: 760px) {
        div#w2p-pdf-template-container > div {
        width: 100% !important;
        }
        .buttonGrp, .RegPrice {width: 100%;}
    }
    <?php } else { ?>
    #udraw-bootstrap div.container {
        width: 100%;
    }
    <?php } ?>
    
    #pdf-block-product-ui input.ga-inputfile.ga-inputfile-theme {
        display: none;
    }

    #pdf-block-product-ui .tab-content .tab-pane div:nth-of-type(even) {
        margin-bottom: 10px;
    }

    .image_selector_modal .block_image_modal_container .row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto;
        row-gap: 20px;
        text-align: center;
        margin-right: -15px;
        margin-left: -15px;
    }

    @media (max-width: 475px) {
        .image_selector_modal .block_image_modal_container .row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /*Tabs Styling*/
    #udraw-bootstrap .nav-tabs {
        margin: auto;
        border-bottom: 1px solid #ddd;
        padding-bottom: 0 !important;
    }
    #udraw-bootstrap .nav-tabs > li > a.active {
        border-color: #ddd #ddd #fff #ddd;
        color: #333;
        background: transparent !important;
    }
    #udraw-bootstrap .nav-tabs > li > a {
        border: 2px solid #ddd;
        border-radius: 20px 20px 0 0 !important;
        background: #ddd;
        color: #333;
        font-weight: 700 !important;
        font-size: 15px !important;
        margin-right: 5px !important;
        line-height: 1 !important;
        margin-bottom: 5px;
    }
    /*Tab Styling ends*/

    /*Accept Proof*/
    #udraw-bootstrap .accept-proof {
        display: inherit;
        width: 100%;
        padding: 5px 15px;
        margin-bottom: 5px;
    }
    #udraw-bootstrap .approval {
        color: #ff0000;
        font-weight: bold;
    }
    /*Accept Proof ends*/
    <?php echo $_udraw_settings['udraw_pdf_template_css_hook']; ?>
</style>