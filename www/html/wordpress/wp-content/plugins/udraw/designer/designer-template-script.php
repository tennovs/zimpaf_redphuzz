<?php
global $user_session_id;
if (!$user_session_id || strlen($user_session_id) === 0) {
    $user_session_id = uniqid();
}
$displayOptionsFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);
$allowConvertPDF = get_post_meta($post->ID, '_udraw_allow_convert_pdf', true); //'yes'
$_cart_item_key = '';
$udraw_access_key = '';
$product_type = $product->get_type();
$friendly_item_name = get_the_title($post->ID);
// uDraw param for cart item key value
if (isset($_GET['cart_item_key'])) { $_cart_item_key = $_GET['cart_item_key']; }
// support for other plugin that uses diff. name than uDraw
if (isset($_GET['tm_cart_item_key'])) { $_cart_item_key = $_GET['tm_cart_item_key']; }
//If loading saved design
if (isset($_GET['udraw_access_key'])) { $udraw_access_key = $_GET['udraw_access_key']; }

$_asset_path = '';
$_asset_path_url = '';
$_local_image_path = '';
$_output_path = '';
$_output_path_url = '';
$_pattern_path = '';
$_pattern_path_url = '';
$_user_first_name = '';
$_user_last_name = '';

$url = SITE_CDN_DOMAIN;

if (is_user_logged_in()) {
    // Physical Path
    $_asset_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/assets/';
    $_output_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/output/';
    $_pattern_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/patterns/';
    $_cart_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/cart/';
    //Product folder
    $_user_session_path = $_output_path . $user_session_id . '/';
    
    // Web Path
    $_asset_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/assets/';
    $_output_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/output/';
    $_pattern_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/patterns/';
    $_cart_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/cart/';

    //Product folder
    $_user_session_path_url = $_output_path_url . $user_session_id . '/';

    //CDN Asset Path
    // First Check if CDN Path Exists = Pressable Sites
    $curl = curl_init($url); 
    curl_setopt($curl, CURLOPT_NOBODY, true);         
    $result = curl_exec($curl);  
    if ($result !== false) {   
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
        if ($statusCode == 404) { 
            //CDN Doesn't Exist
            $_local_image_path = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/assets/';
            $_local_image_path = wp_make_link_relative( $_local_image_path );
        } 
        else { 
            //CDN Exists
            $_local_image_path = $url . '/wp-content/udraw/storage/' . wp_get_current_user()->user_login . '/assets/';
        } 
    } 
    else { 
        //CDN Doesn't Exist
        $_local_image_path = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/assets/';
        $_local_image_path = wp_make_link_relative( $_local_image_path );
    }
} else {
    if (!session_id()) {
        session_start();
    }
    $session_id = session_id();
    $_asset_path = UDRAW_STORAGE_DIR .'_'. $session_id .'_/assets/';
    $_output_path = UDRAW_STORAGE_DIR .'_'. $session_id . '_/output/';
    $_pattern_path = UDRAW_STORAGE_DIR .'_'. $session_id . '_/patterns/';
    $_cart_path = UDRAW_STORAGE_DIR .'_'. $session_id . '_/cart/';
    //Product folder
    $_user_session_path = $_output_path . $user_session_id . '/';
    
    $_asset_path_url = UDRAW_STORAGE_URL .'_'. $session_id . '_/assets/';
    $_output_path_url = UDRAW_STORAGE_URL .'_'. $session_id . '_/output/';
    $_pattern_path_url = UDRAW_STORAGE_URL .'_'. $session_id . '_/patterns/';
    $_cart_path_url = UDRAW_STORAGE_URL .'_'. $session_id . '_/cart/';
    //Product folder
    $_user_session_path_url = $_output_path_url . $user_session_id . '/';

    //CDN Asset Path
    // First Check if CDN Path Exists = Pressable Sites
    $curl = curl_init($url); 
    curl_setopt($curl, CURLOPT_NOBODY, true);         
    $result = curl_exec($curl);  
    if ($result !== false) {   
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
        if ($statusCode == 404) { 
            //CDN Doesn't Exist
            $_local_image_path = UDRAW_STORAGE_URL .'_'. $session_id . '_/assets/';
            $_local_image_path = wp_make_link_relative( $_local_image_path );
        } 
        else { 
            //CDN Exists
            $_local_image_path = $url . '/wp-content/udraw/storage/' . $session_id . '/assets/';
        } 
    } 
    else { 
        //CDN Doesn't Exist
        $_local_image_path = UDRAW_STORAGE_URL .'_'. $session_id . '_/assets/';
        $_local_image_path = wp_make_link_relative( $_local_image_path );
    } 
}

// Create folders if doesn't exist.
if (!file_exists($_asset_path)) { wp_mkdir_p($_asset_path); }
if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }
if (!file_exists($_pattern_path)) { wp_mkdir_p($_pattern_path); }
if (!file_exists($_cart_path)) { wp_mkdir_p($_cart_path); }

$bleed_px = 0;
if ($_udraw_settings['designer_impose_bleed']) {
    $bleed = $_udraw_settings['designer_bleed'];
    $metric = $_udraw_settings['designer_bleed_metric'];
    switch ($metric) {
        case 'mm' :
            $bleed_px = $bleed / 25.4 * 72;
            break;
        case 'cm' :
            $bleed_px = $bleed / 2.54 * 72;
            break;
        case 'in' : 
            $bleed_px = $bleed * 72;
            break;
        default: 
            $bleed_px = $bleed * 72;
            break;
    }
}
$designer_ui = $_udraw_settings['designer_skin'];
$designerSkinOverride = get_post_meta($post->ID, '_udraw_designer_skin_override', true);
if ($designerSkinOverride) {
    $designer_ui = get_post_meta($post->ID, '_udraw_designer_skin', true);
}
?>

<script type="text/javascript">
    var cart_item_key = '<?php echo $_cart_item_key ?>';
    var udraw_access_key = '<?php echo $udraw_access_key ?>';
    var optionsFirst = '<?php echo $displayOptionsFirst ?>';
    var convertPDF = '<?php echo $allowConvertPDF?>';
    var bleed = <?php echo $bleed_px ?>;
    var selectedTemplateText = "";
    if (typeof sessionID === 'undefined') {
        var sessionID = '<?php echo $user_session_id ?>';
    }
    var productType = '<?php echo $product_type ?>';
    
    function get_uniqid () {
        return sessionID;
    }
    if (optionsFirst == '' && (typeof templateCount === 'undefined' || templateCount <= 1)) {
        jQuery('#designer-wrapper').css('top', 0);
        jQuery('#designer-wrapper #udraw-bootstrap').show();
    }
    //disableDesignNowButton();
    jQuery(document).ready(function ($) {
        if ($('#wpadminbar').length > 0 && $('#wpadminbar').is(':visible')) {
            $('#designer-wrapper').css('padding-top', 50);
        }
        RacadDesigner.HideDesigner();
        window.onbeforeunload = function (e) {
            e = e || window.event;
            if (typeof window.designerAction != 'undefined') {
                if (window.designerAction != 'addToCart' && window.designerAction != 'saveDesign' && window.designerAction != 'update') {
                    if (e) {
                        e.returnValue = "Any unsaved changes will be lost.";
                    }
                    //for Safari
                    return "Any unsaved changes will be lost.";
                }
            }
        };
        $('#show-udraw-display-options-ui-btn').click(function () {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            if (templateCount > 1 && cart_item_key.length === 0) {
                if (selectedTemplateText == "") {
                    jQuery('div.multi_template_container').show();
                    jQuery('div#designer-wrapper').css('top', -9999);
                } else {
                    __show_options_page_ui();
                }
            } else {
                __show_options_page_ui();
            }
        });

        $('#udraw-update-design-btn').click(function() {
            window.designerAction = 'update';
            if (cart_item_key.length > 0) {
                RacadDesigner.Pages.save(true);
                create_cart_merged_file(function(merged_file) {
                    RacadDesigner.export_preview_image(0, function(preview_url){
                        $('input[name="udraw_product_data"]').val(merged_file);
                        $('input[name="udraw_product_preview"]').val(preview_url);
                        $('input[name="udraw_product_cart_item_key"]').val(cart_item_key);
                        $('form.cart').submit();
                    });
                });
            }
        });
        
        $('#udraw-save-design-btn').click(function(e) {
            e.preventDefault();
            __finalize_add_to_cart();
        });
        
        $('#udraw-save-later-design-btn').click(function() {
            window.designerAction = 'saveDesign';
            RacadDesigner.HideDesigner();
            RacadDesigner.Pages.save(true);
            RacadDesigner.SaveDesignXML('save');
            create_cart_merged_file(function(merged_file) {
                RacadDesigner.export_preview_image(0, function(preview_url){
                    $('input[name="udraw_product_data"]').val(merged_file);
                    $('input[name="udraw_product_preview"]').val(preview_url);
                    $('input[name="udraw_save_product_data"]').val(merged_file);
                    $('input[name="udraw_save_product_preview"]').val(preview_url);
                    if (typeof selectedByUser !== 'undefined') {
                        var pm_options = {
                            options: selectedByUser,
                            quantity: $('input[name="udraw_price_matrix_qty"]').val(),
                            price: $('input[name="udraw_price_matrix_price"]').val(),
                            uploaded_file: $('input[name="udraw_options_uploaded_files"]').val(),
                            converted_pdf: $('input[name="udraw_options_converted_pdf"]').val(),
                            uploaded_excel: $('input[name="udraw_options_uploaded_excel"]').val(),
                            shipping_dimensions: $('input[name="udraw_price_matrix_shipping_dimensions"]').val(),
                            selectedOptions: selectedPMOptions,
                            selectedOutput: selectedOutput,
                            dimensions: {
                                width: $('[name="txtWidth"]').val(),
                                height: $('[name="txtHeight"]').val()
                            }
                        }
                        $('input[name="udraw_price_matrix_selected_by_user"]').val(JSON.stringify(pm_options));
                    } else if ($('table.variations').length > 0) {
                        var variation_options = new Array();
                        $('select.variation_select').each(function(){
                            var object = {
                                name: $(this).attr('name'),
                                value: $(this).val()
                            }
                            variation_options.push(object);
                        });
                        $('input[name="udraw_selected_variations"]').val(JSON.stringify(variation_options));
                    }
                    $('#udraw_save_later').submit();
                });
            });
        });
        
        $('#udraw-next-step-1-btn, #udraw-add-to-cart-btn').click(function () {
            if (window.fullScreen) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
            if ( (!$('.variations_form').length) && (!$('.price_matrix_form').length) ) {
                __finalize_add_to_cart();
            } else {            
                if ( $('#udraw-select-options-ui').is(":visible") || $('#udraw-price-matrix-ui').is(":visible") ) {                
                    __finalize_add_to_cart();
                } else {
                    RacadDesigner.Pages.save(true);
                    create_cart_merged_file(function(merged_file) {
                        RacadDesigner.export_preview_image(0, function(preview_url){
                            jQuery('input[name="udraw_product_data"]').val(merged_file);
                            jQuery('input[name="udraw_product_preview"]').val(preview_url);
                            <?php if (strlen($_cart_item_key) > 0) { ?>
                                $('input[name="udraw_product_cart_item_key"]').val(cart_item_key);
                            <?php } ?>

                            if ($('.price_matrix_form').length) {
                                display_udraw_price_matrix_preview();
                                uDraw_display_product_previews();
                                $('.price_matrix_form #udraw-bootstrap').show();
                                $('#udraw-price-matrix-ui').show();
                                $('#udraw-save-later-design-btn').hide();
                                $('#udraw-main-designer-ui').fadeOut();
                                $('#designer-wrapper').css('top',-9999);
                                $('#udraw-next-step-1-btn-label').html("Add To Cart");
                            } else if ($('.variations_form').length) {
                                $('#udraw-select-options-ui').show();
                                $('#udraw-save-later-design-btn').hide();
                                uDraw_update_variable_pricing();
                                uDraw_display_product_previews();
                                $('#udraw-main-designer-ui').fadeOut();
                                $('#designer-wrapper').css('top',-9999);
                                $('#udraw-next-step-1-btn-label').html("Add To Cart");                        
                            }
                        });
                    });
                }
            }
        });
		
        $('.form.cart').on('click', ':submit', function(evt) {
            if (evt.currentTarget.id == "variable-product-back-btn") { evt.preventDefault(); return false; }   
            if (evt.currentTarget.id == "udraw-product-back-btn") { evt.preventDefault(); return false; }

            if (evt.currentTarget.id == "udraw-options-submit-form-btn") {
                window.designerAction = 'addToCart';
                //If is multi-template and has no design data and has no uploaded file, alert the user
                if (cart_item_key.length === 0 && templateCount > 1 && RacadDesigner.settings.designFile == '' &&
                        jQuery('[name="udraw_options_uploaded_files"]').val().length == 0) {
                    var confirmProcess = confirm("You are about to add this product to cart without a design. Proceed?");
                    if (!confirmProcess) {
                        evt.preventDefault(); return false;
                    }
                }
                jQuery('#udraw-options-submit-form-btn').hide();
                jQuery('.udraw-add-to-cart-spinner').show();
                RacadDesigner.Pages.save(true);
                create_cart_merged_file(function(merged_file) {
                    RacadDesigner.export_preview_image(0, function(preview_url){
                        jQuery('input[name="udraw_product_data"]').val(merged_file);
                        jQuery('input[name="udraw_product_preview"]').val(preview_url);
                        jQuery('form.cart').submit();
                    });
                });
            } else {
                __finalize_add_to_cart()
                <?php if (!$_udraw_settings['improved_display_options'] && $_udraw_settings['split_variations_2_step']) { ?>
                $('.udraw-preview').hide();
                $('#udraw-variations-step-0-btn').hide();
                if (evt.currentTarget.classList[0] == "single_add_to_cart_button") { evt.preventDefault(); }
                <?php } ?>
            }
            
		});

        jQuery(document).on('udraw_price_matrix_display_price', function(e) {
            //Updates Woocommerce Product Gallery Image if 'LinkedTemplate' has been defined as a Custom Setting for Price Matrix Options.
            if (typeof selectedPMOptions !== 'undefined') {
                for (var x = 0; x < selectedPMOptions.length; x++) {
                    if (typeof selectedPMOptions[x].meta !== 'undefined') {
                        for (var y = 0; y < selectedPMOptions[x].meta.length; y++) {
                            if (selectedPMOptions[x].meta[y].key == 'linkedtemplate') {
                                selectedTemplateText = selectedPMOptions[x].meta[y].value.trim();
                            }
                        }
                    }
                }
            }
        });

        jQuery(document).on('click', '#udraw-options-page-design-btn', function () {
            if (typeof window.designerAction == 'undefined') {
                window.designerAction = '';
            }
            if ($(this).hasClass("isLoading")) {
                return false;
            }
            if (typeof templateCount != 'undefined' && templateCount > 1 && (typeof cart_item_key === 'undefined' || (typeof cart_item_key === 'string' && cart_item_key.length === 0)) && udraw_access_key.length === 0) {
                if (selectedTemplateText == "") {
                    RacadDesigner.HideDesigner();
                    jQuery('div.multi_template_container').show();
                } else {
                    jQuery( '#multi_template_display .template_display_item a' ).each(function() {
	                    var templateName = jQuery(this).attr('data-template-name');
                        //Select template and open designer based on the Linked Template ID defined in the price matrix.
                        if (templateName.indexOf(selectedTemplateText) >= 0) {
                            jQuery(this).trigger('click');
                            return false;
                        }
                    });
                }
            } else {
                jQuery('#designer-wrapper').css('top', 0);
                jQuery('#udraw-bootstrap').show();
            }
            if (RacadDesigner.Pages) {
                if (RacadDesigner.Pages.list.length > 1) {
                    RacadDesigner.Pages.switchByID(RacadDesigner.Pages.list[0].id);
                }
            }
            if (typeof __load_extra_functions == 'function') {
                __load_extra_functions();
            }
        });

        jQuery('#multi_template_display_btn').click(function () {
            jQuery('div.multi_template_container').hide();
        });

        $('#udraw-preview-back-to-design-btn').click(function() {
            RacadDesigner.ShowDesigner();
            $('#udraw-preview-ui').hide();
            $('.cart').show();
            $('.price').show(); 
            $('#udraw-main-designer-ui').fadeIn();
            jQuery('.udraw-top-buttons-span').show();
            __udraw_variations_step_0();                           
        });
        
        $('#udraw-preview-add-to-cart-btn').click(function() {
            $('form.cart').submit();
        });
        
        <?php if (strlen($_cart_item_key) > 0) { ?>
            $('#reviews').hide();
        <?php } ?>
        
        <?php if (!$_udraw_settings['show_product_description']) { ?>
            $('#reviews').hide();
            $('.woocommerce-tabs').hide();
        <?php } ?>
        
        <?php if (!$_udraw_settings['show_product_title']) { ?>
            $('.product_title').hide();
        <?php } ?>
        
        <?php if (!$_udraw_settings['show_product_breadcrumbs']) { ?>
            $('.shop-navigation').hide();
        <?php } ?>                            
        
        if ( (!$('.variations_form').length) && (!$('.price_matrix_form').length) ) {
            $('#udraw-next-step-1-btn-label').html('<?php _e('Add To Cart', 'udraw')?>');
        }
        
        <?php if ($product_type == "variable" || (isset($isPriceMatrix) && $isPriceMatrix)) { ?>
            $('.variations_form').hide();
                
            $('#udraw-variations-step-1-btn').click(function() {
                $('.variations_form').show();
                RacadDesigner.SaveHistory();
                RacadDesigner.Pages.updatePreview(true);
                
                $('.udraw-preview').remove();
                
                var _previewHtml =  '<div class="col-md-offset-2 col-md-4 product-content udraw-preview">';
                _previewHtml += '<h1 style="text-align:center;">Preview</h1> <br />';
                for (var x = 0; x < RacadDesigner.Pages.list.length; x++) {
                    _previewHtml += '<img style="border: 1px solid #CFCFCF;" src="'+RacadDesigner.Pages.list[x].DataUri+'" /><br /><br />';
                }        
                _previewHtml += '</div>';
                
                $('.col-md-6.product-content').after(_previewHtml);
                uDraw_update_variable_pricing();
                uDraw_display_product_previews();
                $('.udraw-preview').show();
                //$('#udraw-main-designer-ui').hide();
                $('#designer-wrapper').css('top',-9999);
                $('.variations_form').fadeIn();
                $('#udraw-select-options-ui').show();
                $('#udraw-variations-step-0-btn').show();
                $('#udraw-variations-step-1-btn').hide(); 
                $('#udraw-save-later-design-btn').hide();

            });
        
            $('#udraw-variations-step-0-btn').click(function() {
                __udraw_variations_step_0();
            });
	    <?php } ?> 


       

	    // UI Updates.
        <?php                        
        if ($displayOptionsFirst) {
        ?>
        $('.variations_form').fadeIn();
        <?php } else { ?>
            $("input[name=quantity]").css("width", "60px");
            $('.single-product-image').removeClass("span6");
            $('.single-product-image').css({ "min-width": "1048px" });

            $('.product_title').css('padding-left', '18px').css('margin', '0px');
            $('.summary').css('float', 'none').css('padding-left', '15px');
            $('.price').css('width', '100%').css('text-align', 'left');
            if (typeof templateCount != 'undefined' && templateCount > 1) {
                $('div.multi_template_container').show();
            } else {
                $('div#designer-wrapper').css('top', 0);
            }
            $('#primary, #main').css("width", "100%");

            // Theme modifications ( maybe make these changes on a site level instead of in core plugin )
            // TODO: Make this more customizable.
            $('.entry-summary').removeClass("col-lg-6 col-md-5 col-sm-12");
            $('.summary-before').removeClass("col-lg-6 col-md-7 col-sm-12");
            if (typeof window.designerAction == 'undefined') {
                designerAction = '';
            }
        <?php } ?>
        
        $('input[name=quantity]').change(function(){
            var productPrice = '<?php echo $product->get_price(); ?>';
            if (productPrice.length > 0) {
                var totalPrice = (parseFloat(productPrice) * parseFloat($('input[name=quantity]').val()));
                $('#product_total_price').html(totalPrice);
            }
        });
        jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-loaded',function() {
            if (typeof templateCount != 'undefined' && templateCount > 1 || <?php echo strlen($isTemplatelessProduct) ?>) {
                enableDesignNowButton();
            }
        });

        jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design udraw-switched-page', function () {
            //Clears Char Width Cache
            fabric.charWidthsCache = {};
            RacadDesigner.Layers.applyObjectProperties();
            RacadDesigner.Pages.save();
        });

        jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design', function(){
            //Set user_session_id if not set
            <?php if (!isset($_GET['cart_item_key'])) { ?>
                RacadDesigner.settings.user_session_id = '<?php echo $user_session_id ?>';
            <?php } ?>
            
            if (jQuery('[data-udraw="pagesModal"]').length > 0 && RacadDesigner.Pages.list.length > 1) {
                jQuery('[data-udraw="pagesModal"]').modal('show');
            }
            //Get array of page design xmls
            RacadDesigner.GetPageDesignArray(true, function(xml_array){
                //Base64 encode the items in the array so that when it reaches the server, slashes aren't added
                for (var i = 0; i < xml_array.length; i++) {
                    xml_array[i] = Base64.encode(xml_array[i]);
                }
                //Send xml data to server for file creation
                jQuery.ajax({
                    method: 'POST',
                    dataType: "json",
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    data: {
                        'action': 'udraw_designer_create_product_xml_pages',
                        'folder_path' : '<?php echo $_user_session_path_url ?>',
                        'xml_array': xml_array
                    },
                    success: function (response) {
                        if (jQuery('input[name="udraw_options_converted_pdf"]').val() == 'false') {
                            jQuery('#udraw-options-page-design-btn').hide();
                        } else {
                            if (typeof override_enable_button === 'undefined' || !override_enable_button) {
                                //Show the button if it is not being overwritten
                                enableDesignNowButton();
                            }
                        }
                        jQuery('[data-udraw="uDrawBootstrap"]').trigger({
                            type: 'udraw-after-xml-pages-created'
                        });
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            });
        });
        
        jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-switched-page', function(event){
            if (bleed > 0) {
                RacadDesigner.imposeBleed(bleed / RacadDesigner.settings.pdfRatio, 0);
            }
            //Get previous page id
            var previous_page_id = event.previous_page_id;
            var previous_page_index = 0;
            for (var i = 0; i < RacadDesigner.Pages.list.length; i++) {
                var page = RacadDesigner.Pages.list[i];
                if (page.id === previous_page_id) {
                    previous_page_index = i;
                    //Get the xml of the previous page
                    RacadDesigner.GetPageDesignXML(i, true, function(page_xml){
                        //Update xml file
                        create_page_xml(page_xml, previous_page_index);
                    });
                    break;
                }
            }
        });
        
        jQuery('[data-udraw="cart_btn"]').on('click', function(){
            if (typeof override_add_to_cart === 'function') {
                override_add_to_cart(jQuery);
            } else {
                __finalize_add_to_cart();
            }
        });
        jQuery('[data-udraw="load_xml_modal"]').modal({ keyboard: false, backdrop: 'static', show: false });
        
        //Download PDF function 
        jQuery('[data-udraw="downloadPDFButton"]').on('click', function(){
                RacadDesigner.settings.pdfQualityLevel = 8;
            <?php if (uDraw::is_udraw_okay()) { ?>
                RacadDesigner.ExportToLayeredPDF(function(url){ 
                    var dl = document.createElement('a'); 
                    dl.setAttribute('href', url); 
                    dl.setAttribute('download', '<?php echo $friendly_item_name ?>'); 
                    dl.click(); 
                });
            <?php } else { ?>
                RacadDesigner.ExportToMultiPagePDF('<?php echo $friendly_item_name ?>',false);
            <?php } ?>
        });
        
        //Add to cart button
        jQuery('[data-udraw="addToCart"]').on('click', function(){
            __finalize_add_to_cart();
        });
    });
    
    function disableDesignNowButton() {
        if (!jQuery('#udraw-options-page-design-btn').hasClass('isLoading')) {
            jQuery('#udraw-options-page-design-btn').attr('disabled', true);
            jQuery('#udraw-options-page-design-btn i').show();
        }
    }
    
    function enableDesignNowButton(){
        jQuery('#udraw-options-page-design-btn').removeClass("isLoading");
        jQuery('#udraw-options-page-design-btn i').remove();
        jQuery('#udraw-options-page-design-btn').attr('disabled', false);
        jQuery('#udraw-options-page-design-btn i').hide();
    }

    function update_multi_template_display(templates) {
        disableDesignNowButton();
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action': 'udraw_get_templates_id',
                'templates': templates
            },
            success: function (response) {
                if (response.length > 0) {
                    templateCount = response.length;
                    if (response.length > 1) {
                        jQuery('#multi_template_display').empty();
                        var _html = '';
                        for (var x = 0; x < response.length; x++) {
                            _html += '<div class="col-md-4 template_display_item">';
                            _html += '<a href="#" class="udraw_multitemplate" onclick="displayDesigner(\'' + response[x].design + '\')"><img style="max-width:300px" src="' + response[x].preview + '"/></a>';
                            _html += '</div>';
                        }
                        jQuery('#multi_template_display').html(_html);
                    } else {
                        if (RacadDesigner.settings.designFile != response[0].design) {
                            RacadDesigner.Templates.loadTemplateDesign(response[0].design);
                            RacadDesigner.settings.designFile = response[0].design;
                        }
                        RacadDesigner.settings.privateTemplateApi = '<?php echo admin_url( 'admin-ajax.php' ) ?>?action=udraw_related_templates&template_id=' + templates;
                    }
                    enableDesignNowButton();
                }
            }
        });
    }

    function __udraw_variations_step_0() {
        jQuery('#designer-wrapper').css('top', 0);
        jQuery('#udraw-main-designer-ui').fadeIn();
        jQuery('.variations_form').hide();
        jQuery('#udraw-variations-step-0-btn').hide();
        jQuery('#udraw-variations-step-1-btn').show();
        jQuery('#udraw-save-later-design-btn').show();
        jQuery('.udraw-preview').hide();
    }
    function displayDesigner(design) {
        jQuery('div#designer-wrapper').css('top',-9999);
        RacadDesigner.Templates.loadTemplateDesign(design);
        RacadDesigner.settings.designFile = design;
        jQuery('div.multi_template_container').hide();
        
        setTimeout(function () {
            jQuery('[data-udraw="uDrawBootstrap"]').show();
            jQuery('div#designer-wrapper').css('top',0);
        }, 500);
    }
    function __init_udraw(settings) {
        settings.assetPath = '<?php echo wp_make_link_relative($_asset_path_url); ?>';
        settings.outputPath = '<?php echo wp_make_link_relative($_output_path_url); ?>';
        settings.handlerFile = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        settings.localImagePath = '<?php echo $_local_image_path ?>';    
        settings.localPatternsPath = '<?php echo wp_make_link_relative($_pattern_path_url); ?>';
        settings.localImageDisplayThumbs = true;
        settings.displayWizard = false;
        settings.isTemplate = false;
        settings.relativeImagePath = '<?php echo wp_make_link_relative(UDRAW_DESIGNER_IMG_PATH); ?>';
        settings.relativeIncludePath = '<?php echo wp_make_link_relative(UDRAW_DESIGNER_INCLUDE_PATH); ?>';
        settings.virtualAppPath = '';
        settings.useLocalFonts = true;
        settings.localFontPath = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
        settings.language = '<?php echo $_udraw_settings['udraw_designer_language']; ?>';
        settings.activationKey = '<?php echo uDraw::get_udraw_activation_key()?>';
        settings.contentUploadPath = '<?php echo wp_make_link_relative(content_url()."/uploads/")?>';
        settings.localesPath = '<?php echo (file_exists(UDRAW_LANGUAGES_DIR.'udraw-'.$_udraw_settings['udraw_designer_language'].'.txt')) ? wp_make_link_relative(UDRAW_LANGUAGES_URL) : wp_make_link_relative(UDRAW_DESIGNER_INCLUDE_PATH.'locales/');  ?>';
        settings.displayOrientation = '<?php echo $_udraw_settings['udraw_designer_display_orientation']; ?>';
        settings.excludeBleed = false;
        settings.designer_ui = '<?php echo $designer_ui ?>';
        settings.locale = '<?php echo get_locale() ?>';
        <?php if ($_udraw_settings['show_customer_preview_before_adding_to_cart']) {
        if ($_udraw_settings['designer_exclude_bleed']) { ?>
        settings.excludeBleed = true;
        <?php } ?>
        <?php } ?>
        
        <?php if ($_udraw_settings['designer_disable_image_cropper']) { ?>
        settings.disableImageCropper = true;
        <?php } ?>
    
        <?php if ($_udraw_settings['designer_disable_image_replace']) { ?>
        settings.disableImageReplace = true;
        <?php } ?>
        
        <?php if ($_udraw_settings['designer_disable_image_filters']) { ?>
        settings.disableImageFilters = true;
        <?php } ?>
        
        <?php if ($_udraw_settings['designer_disable_image_fill']) { ?>
        settings.disableImageFill = true;
        <?php } ?>

        <?php if ($_udraw_settings['designer_disable_text_gradient']) { ?>
        settings.disableTextGradient = true;
        <?php } ?>

        <?php if ($_udraw_settings['designer_enable_optimize_large_images']) { ?>
        settings.enableOptimizedLargeImages = true;
        <?php } ?>
        <?php if ($_udraw_settings['designer_out_of_bounds_warning']) { ?>
        settings.enableOutOfBoundsWarning = true;
        <?php } ?>
        <?php if ($_udraw_settings['designer_check_dpi']) { ?>
            settings.check_dpi = true;
            settings.minimum_dpi_requirement = parseFloat('<?php echo $_udraw_settings['designer_minimum_dpi'] ?>');
            <?php if ($_udraw_settings['designer_enforce_dpi_requirement']) { ?>
                settings.enforce_dpi_requirement = true;
            <?php } ?>
        <?php } ?>
        <?php if ($_udraw_settings['udraw_designer_display_linked_template_name']) { ?>
            settings.display_linked_templates_name = true;
        <?php } ?>
        <?php			
            $isApparelProduct = get_post_meta($post->ID, '_udraw_apparel', true);
            if( strlen($_cart_item_key) < 1 && !isset($_GET['udraw_access_key'])) {
                echo "settings.designMode = 'new';";
                if (!isset($isBlankCanvas)) {
                    // Default to load in original template design.
                    $table_name = $_udraw_settings['udraw_db_udraw_templates'];
                    $uDraw = new uDraw();
                    $templateId = $uDraw->get_udraw_template_ids($post->ID);
                    
                    if (count($templateId) > 0) {
                        //if it is array then preselect the first template
                        $designURL = $wpdb->get_var("SELECT design FROM $table_name WHERE id = '". $templateId[0] ."'");
                        if (count($templateId) > 1) {
                            echo "settings.designFile = '';";
                        } else {
                            echo "settings.designFile = '".$designURL."';";
                        }
                        echo "settings.privateTemplateApi = '". admin_url( 'admin-ajax.php' ) ."?action=udraw_related_templates&template_id=" . $templateId[0] . "';";
                    }
                    if ($isApparelProduct === 'true') {
                        echo "settings.designFile = '';";
                    }
                }
            }
        ?>
        
        <?php if ($_udraw_settings['designer_enable_local_clipart']) { ?>
        settings.privateClipArtPath = '<?php echo wp_make_link_relative(UDRAW_CLIPART_URL) ?>';
        <?php } ?>
        
        <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
        settings.FBappID = '<?php echo $_udraw_settings['designer_facebook_app_id'] ?>';
        <?php } else { ?>
        settings.FBappID = '';
        <?php } ?>
        <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
        settings.instagramClientID = '<?php echo $_udraw_settings['designer_instagram_client_id']?>';
        <?php } else { ?>
        settings.instagramClientID = '';
        <?php } ?>
        return settings;		
    }

    function __init_handler_actions(actions) {
        actions.localFontsList = 'udraw_designer_local_fonts_list';
        actions.localFontsCSS = 'udraw_designer_local_fonts_css';
        actions.localFontsCSSBase64 = 'udraw_designer_local_fonts_css_base64';
        actions.upload = 'udraw_designer_upload';
        actions.save = 'udraw_designer_save';
        actions.finalize = 'udraw_designer_finalize';
        actions.exportPDF = 'udraw_designer_export_pdf';
        actions.asset = 'udraw_designer_asset';
        actions.localImages = 'udraw_designer_local_images';
        actions.removeImage = 'udraw_designer_remove_image';
        actions.localPatterns = 'udraw_designer_local_patterns';
        actions.uploadPatterns = 'udraw_designer_upload_patterns';
        actions.savePageData = 'udraw_designer_save_page_data';
        actions.compileDesignData = 'udraw_designer_compile_design_data';
        actions.authenticate_instagram = 'udraw_designer_authenticate_instagram';
        actions.retrieve_instagram = 'udraw_designer_retrieve_instagram';
        actions.authenticate_flickr = 'udraw_designer_authenticate_flickr';
        actions.flickr_get = 'udraw_designer_flickr_get';
        actions.download_image = 'udraw_designer_download_image';
        actions.export_preview_image = 'udraw_designer_export_preview_image';
        actions.exportImage = 'udraw_designer_export_image';
        actions.textTemplates = 'udraw_designer_get_text_templates';
        actions.get_private_images_library = 'udraw_retrieve_clipart';

        return actions;
    }
    
    function __get_app_id(){
        RacadDesigner.settings.FBappID = '<?php echo $_udraw_settings['designer_facebook_app_id']?>';
    }
	
	function __loaded_udraw() {
	    <?php
            if (isset($session_id)) {
                echo 'RacadDesigner.session_id="' . $session_id . '";';
            }
            // Attempt to load in design from cart.
            if( strlen($_cart_item_key) > 0 ) {
                //load from cart item
                $cart = $woocommerce->cart->get_cart();
                $cart_item = $cart[$_cart_item_key];
                
                // Update hidden form item for cart_item_key
                echo "jQuery('input[name=\"udraw_product_cart_item_key\"]').val('". $_cart_item_key ."');";
                
                if($cart_item) {
                    if( isset($cart_item['udraw_data']) ) {
                        // Insert previous data
                        echo "jQuery('input[name=\"udraw_product_data\"]').val('" . UDRAW_STORAGE_URL .$cart_item['udraw_data']['udraw_product_data']."');";
                        echo "jQuery('input[name=\"udraw_product_preview\"]').val('".$cart_item['udraw_data']['udraw_product_preview']."');";
                        
                        if (isset($cart_item['udraw_data']['udraw_product_data'])) {
                            if (strlen($cart_item['udraw_data']['udraw_product_data']) > 0) {
                                if (strlen($cart_item['udraw_data']['udraw_product_data']) < 100) {
                                    // new file system format
                                    echo "jQuery.ajax({ method: 'GET', dataType: 'text', url: '". UDRAW_STORAGE_URL .$cart_item['udraw_data']['udraw_product_data'] ."', success: function(response) { RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode(response)); },error: function(error) {console.error(error);} });";
                                } else {
                                    // older db format
                                    echo "RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode('". $cart_item['udraw_data']['udraw_product_data'] ."'));";
                                }
                            }
                                
                        } else if (isset($cart_item['udraw_data']['udraw_price_matrix_design_data'])) {
                            echo "RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode('". $cart_item['udraw_data']['udraw_price_matrix_design_data'] ."'));";
                        }
                        
                    }
                }
            }

            // Attempt to load saved customer design.
            if( isset($_GET['udraw_access_key']) ) {
                $design = uDraw::get_udraw_customer_design($_GET['udraw_access_key']);
                if (strlen($design['design_data']) > 1 ) {
                    if (strlen($design['design_data']) < 100) {
                        // new file system format
                        if(strpos($design['design_data'], UDRAW_STORAGE_URL) !== false) { $design_file_path = $design['design_data']; }
                        else { $design_file_path = UDRAW_STORAGE_URL . $design['design_data']; }

                        echo "jQuery.ajax({ method: 'GET', dataType: 'text', url: '". $design_file_path ."', success: function(response) { RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode(response)); },error: function(error) {console.error(error);} });";                        
                    } else {
                        // older db format
                        echo "RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode('". $design['design_data'] ."'));";
                    }
                }
                //Check if variations exist
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

	    try {
	        __loaded_udraw_finished();
	        jQuery('#udraw-price-matrix-ui select').trigger('change');
	    } catch (Error) { }
	}
    
    function __loaded_udraw_design() {
        //will not fire if templateless product
        RacadDesigner.ReloadObjects();
        setTimeout(function() {
            jQuery('[data-udraw="addPage"]').fadeOut();
            jQuery('.remove-page-btn-designer').fadeOut();
            jQuery('.update-page-btn-designer').fadeOut();            
        }, 1500);
        RacadDesigner.ShowDesigner();
        if (RacadDesigner.Labels.hasAssigned()) {
            RacadDesigner.Labels.generateInputContainer();
            jQuery('[data-udraw="layerLabelsModal"]').modal('show');
        }
        if (typeof __load_extra_functions == 'function') {
            __load_extra_functions();
        }
    }

    function __show_options_page_ui() {
        jQuery('#udraw-display-options-ui').fadeIn();
        jQuery('#udraw-options-page-design-btn').show();
        jQuery('#designer-wrapper').css('top', -9999);
    }
    
    function __finalize_add_to_cart(callback, before_submit) {
        jQuery('#simple-add-to-cart-btn').addClass('disabled');
        jQuery('#simple-add-to-cart-btn i, #simple-add-to-cart-btn span').hide();
        jQuery('#simple-add-to-cart-btn i.fa-pulse').show();
        RacadDesigner.HideDesigner();
        _add_to_cart_action(callback, before_submit);
    }
    
    function _add_to_cart_action (callback, before_submit) {
        jQuery('[data-udraw="progressModal"]').modal('show');
        window.designerAction = 'addToCart';
        RacadDesigner.Pages.save(true);
        var currentPage = RacadDesigner.settings.currentPageId;
        var currentPageIndex = RacadDesigner.Pages.getPageIndex(currentPage);
        create_cart_merged_file(function(merged_file) {
            RacadDesigner.export_preview_image(currentPageIndex, function(preview_url){
                RacadDesigner.Pages.list[currentPageIndex].DataUri = preview_url;
                if (currentPageIndex !== 0) {
                    preview_url = RacadDesigner.Pages.list[0].DataUri;
                }
                jQuery('input[name="udraw_product_data"]').val(merged_file);
                jQuery('input[name="udraw_product_preview"]').val(preview_url);

                //Remove the newly created files
                remove_xml_files(RacadDesigner.Pages.list.length, function(){
                    jQuery('[data-udraw="uDrawBootstrap"]').trigger({
                        type: 'udraw_before_add_to_cart'
                    });
                    <?php if ($_udraw_settings['show_customer_preview_before_adding_to_cart']) { ?>
                        jQuery('[data-udraw="progressModal"]').modal('hide');
                        jQuery('.cart').hide();
                        jQuery('.price').hide();
                        jQuery('#udraw-main-designer-ui, #udraw-display-options-ui').hide();
                        jQuery('.udraw-top-buttons-span').hide();
                        //jQuery('#designer-wrapper').css('top', -9999);
						jQuery('#designer-wrapper').css('top', 0);
                        uDrawDesignProof();
                        jQuery('#udraw-preview-ui').fadeIn();
                    <?php } else { ?>
                        if (typeof before_submit === 'function') {
                            before_submit();
                        }
                        jQuery('form.cart').submit();
                    <?php } ?> 
                    jQuery('#simple-add-to-cart-btn').removeClass('disabled');
                    jQuery('#simple-add-to-cart-btn i, #simple-add-to-cart-btn span').show();
                    jQuery('#simple-add-to-cart-btn i.fa-pulse').hide();
                    if (typeof callback == 'function') {
                        callback();
                    }
                });
            });
        });
    }

    function create_page_xml(xml, page_index, callback) {
        RacadDesigner.Pages.list[page_index].xml_being_created = true;
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action': 'udraw_designer_create_page_xml',
                'folder_path' : '<?php echo $_user_session_path_url ?>',
                'xml': Base64.encode(xml),
                'page_no': page_index
            },
            success: function (response) {
                RacadDesigner.Pages.list[page_index].xml_being_created = false;
                if (typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    function save_design_preview(design_preview, callback) {
            jQuery.ajax({
                method: 'POST',
                dataType: "json",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action': 'udraw_designer_upload_preview',
                'udraw_product_preview': design_preview, 
            },
            success: function (response) {
                if (typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
    
    function create_cart_merged_file (callback) {
        jQuery('[data-udraw="progressDialog"] .progress-bar').css('width', '0%');
        //Get the xml of current page
        var page_id = RacadDesigner.settings.currentPageId;
        var page_index = 0;
        for (var i = 0; i < RacadDesigner.Pages.list.length; i++) {
            var page = RacadDesigner.Pages.list[i];
            if (page.id === page_id) {
                page_index = i;
                RacadDesigner.GetPageDesignXML(i, true, function(page_xml){
                    create_page_xml(page_xml, page_index, function(response){
                        if (response) {
                            check_xml_files_before_merge(callback);
                        }
                    });
                });
                break;
            }
        }
    }
    
    function check_xml_files_before_merge(callback) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action': 'udraw_designer_retrieve_page_xml_files',
                'folder_path' : '<?php echo $_user_session_path_url ?>'
            },
            success: function (files_array) {
                for (var i = 0; i < files_array.length; i++) {
                    files_array[i] = files_array[i].replace('<?php echo $_user_session_path_url ?>', '');
                }
                var designxml_index = jQuery.inArray('design.xml', files_array);
                if (designxml_index > -1) {
                    files_array.splice(designxml_index, 1);
                }
                var progress = files_array.length / RacadDesigner.Pages.list.length * 100;
                jQuery('[data-udraw="progressDialog"] .progress-bar').css('width',(progress / 2) + '%');
                if (files_array.length === RacadDesigner.Pages.list.length) {
                    //Merge the pages into one file if we have all the pages
                    create_cart_xml_file(RacadDesigner.Pages.list.length, function(merged_file){
                        if (typeof callback === 'function') {
                            callback(merged_file);
                        }
                    });
                } else {
                    var exists_array = new Array();
                    var index_array = new Array();
                    var missing_array = new Array();
                    for (var i = 0; i < RacadDesigner.Pages.list.length; i++) {
                        index_array.push(i);
                    }
                    for (var i = 0; i < files_array.length; i++) {
                        exists_array.push(parseInt(files_array[i].replace('.xml', '')));
                    }
                    for (var i = 0; i < index_array.length; i++) {
                        if (jQuery.inArray(i, exists_array) < 0) {
                            missing_array.push(i);
                        }
                    }
                    if (missing_array.length > 0) {
                        //Otherwise create the missing page(s)
                        create_missing_pages(missing_array, 0, function(){
                            check_xml_files_before_merge(callback)
                        });
                    } else {
                        //If missing_array is empty, continue with merge.
                        create_cart_xml_file(RacadDesigner.Pages.list.length, function(merged_file){
                            if (typeof callback === 'function') {
                                callback(merged_file);
                            }
                        });
                    }
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    
    function create_missing_pages(missing_array, index, callback) {
        RacadDesigner.GetPageDesignXML(missing_array[index], false, function(page_xml){
            create_page_xml(page_xml, missing_array[index], function(){
                if (typeof missing_array[index + 1] !== 'undefined') {
                    create_missing_pages(missing_array, index + 1, callback)
                } else {
                    callback();
                }
            });
        });
    }
    
    function create_cart_xml_file (numOfPages, callback) {
        var canvas_header = RacadDesigner.GetCanvasXML();
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action': 'udraw_designer_create_merged_xml',
                'folder_path' : '<?php echo $_user_session_path_url ?>',
                'cart_folder_path' : '<?php echo $_cart_path_url ?>',
                'pages_length' : numOfPages,
                'canvas_header': Base64.encode(canvas_header)
            },
            success: function (merged_file) {
                if (typeof callback === 'function') {
                    callback(merged_file);
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    
    function remove_xml_files (pages_length, callback) {
        //Check that no xml files are in the process of being created first
        var _okay = true;
        for (var i = 0; i < RacadDesigner.Pages.list.length; i++) {
            if (RacadDesigner.Pages.list[i].xml_being_created) {
                _okay = false;
                break;
            }
        }
        if (_okay) {
            jQuery.ajax({
                method: 'POST',
                dataType: "json",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    'action': 'udraw_designer_remove_xml_files',
                    'folder_path' : '<?php echo $_user_session_path_url ?>',
                    'pages_length': pages_length
                },
                success: function (response) {
                    if (response) {
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        } else {
            setTimeout(function(){
                remove_xml_files (callback);
            }, 1000);
        }
    }
                
    function uDrawDesignProof() {
        var _placeHolder = document.getElementById("udraw-preview-design-placeholer");
        var timestamp = new Date().getTime();

        if (_placeHolder) {
            while (_placeHolder.hasChildNodes()) {
                _placeHolder.removeChild(_placeHolder.lastChild);
            }            
            
            for (var x = 0; x < RacadDesigner.Pages.list.length; x++) {
                var imgPreview = document.createElement("img");
                imgPreview.src = RacadDesigner.Pages.list[x].DataUri + '?' + timestamp;
                imgPreview.setAttribute("style", "max-width:300px;");
                _placeHolder.appendChild(imgPreview);
            }
        }
    }

    function _SaveDesign_Response(data) {
        console.log(data);
    }
    
    function apparelAddToCart() {
        window.designerAction = 'addToCart';
        RacadDesigner.canvas.deactivateAll().renderAll();
        var _currentZoom = RacadDesigner.zoom.currentZoom;
        __show_options_page_ui();
        //Alter "Design Now" button to prevent showing designer before the design is ready again
        disableDesignNowButton();
        var designImageData = RacadDesigner.extractDesignAsPNG(true);
        jQuery.ajax({
            method: 'POST',
            dataType: "html",
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            data: {
                'action' : 'udraw_apparel_merge_image',
                'image': designImageData
            },
            success: function (response) {
                response = response.replace(/\\|"/g, '');
                jQuery('input[name="ua_ud_graphic_url"]').val(response);
                _init_colour_images();
                _ua_update_feature_image(ua_current_idx);
                var withPreview = true;
                if (RacadDesigner.Pages.list.length > 2) { withPreview = false; }
                RacadDesigner.GenerateDesignXML(withPreview, function(designXML){
                    jQuery('input[name="udraw_product_data"]').val(Base64.encode(designXML));
                    jQuery('input[name="udraw_product_preview"]').val(RacadDesigner.GetDocumentPreviewThumbnail());
                    RacadDesigner.SaveDesignSVG(function (response) {
                        jQuery('input[name="udraw_product_svg"]').val(JSON.stringify(response));
                        setTimeout(function(){
                            RacadDesigner.ForceZoom(_currentZoom);
                            RacadDesigner.Pages.save();
                            RacadDesigner.Pages.switch(RacadDesigner.Pages.list[0].id, function(){
                                //Change the button back so it will show designer now that the design is ready
                                enableDesignNowButton();
                            });
                        }, 2000);
                    }, 0);
                });
            }
        });
    }
    function _excel_add_to_cart (callback) {
        window.designerAction = 'addToCart';
        <?php if ($_udraw_settings['show_customer_preview_before_adding_to_cart']) { ?>
            jQuery('[data-udraw="progressDialog"]').removeClass('active');
            jQuery('.cart').hide();
            jQuery('.price').hide();
            jQuery('#udraw-main-designer-ui').hide();
            jQuery('.udraw-top-buttons-span').hide();
            uDrawDesignProof();
            jQuery('#udraw-preview-ui').fadeIn();
        <?php } else { ?>
            if (typeof before_submit === 'function') {
                before_submit();
            }
            jQuery('form.cart').submit();
        <?php } ?> 
        jQuery('#simple-add-to-cart-btn').removeClass('disabled');
        jQuery('#simple-add-to-cart-btn i, #simple-add-to-cart-btn span').show();
        jQuery('#simple-add-to-cart-btn i.fa-pulse').hide();
        if (typeof callback == 'function') {
            callback();
        }
    }
</script>