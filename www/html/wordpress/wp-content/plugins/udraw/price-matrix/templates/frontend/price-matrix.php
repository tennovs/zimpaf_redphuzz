
<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post, $woocommerce;

if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_id = $product->get_id();
} else {
    $product_id = $product->id;
}

include('__price-matrix-header.php');
include('__price-matrix-script.php');

$udraw_settings = new uDrawSettings();
$settings = $udraw_settings->get_settings();
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>


<form class="cart price_matrix_form" method="post" enctype='multipart/form-data'>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_idx" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_object" />
    <input type="hidden" value="" name="udraw_price_matrix_projected_pricing" />
    <input type="hidden" value="" name="udraw_options_uploaded_files_preview" />
    <input type="hidden" value="" name="udraw_price_matrix_price" />
    <input type="hidden" value="" name="udraw_price_matrix_qty" />
    <input type="hidden" value="" name="udraw_price_matrix_records" />
    <input type="hidden" value="" name="udraw_price_matrix_weight" />
    <input type="hidden" value="" name="udraw_price_matrix_width" />
    <input type="hidden" value="" name="udraw_price_matrix_height" />
    <input type="hidden" value="" name="udraw_price_matrix_length" />
    <input type="hidden" value="" name="udraw_price_matrix_shipping_dimensions" />
    <input type="hidden" value="true" name="udraw_price_matrix_submit" />
    <input type="hidden" value="" name="udraw_custom_design_name" />
    <input type="hidden" value="<?php echo $price_matrix_object[0]->name; ?>" name="udraw_price_matrix_name" />
    <div id="udraw-bootstrap">        
        <div id="udraw-price-matrix-ui" style="display:none;">
            <div class="container" style="background: transparent;">
                
                <?php if ( ($_udraw_settings['designer_skin'] === "fullscreen") && !($uDrawPDFBlocks::is_pdf_block_product($product_id)) ) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-success btn-sm designer-menu-btn" id="udraw-next-step-1-btn-price-matrix" style="float:right; font-size:1.0em">
                            <span id="udraw-next-step-1-btn-label">Add To Cart</span>
                            <i class="fa fa-chevron-right"></i>
                            <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>

                <div class="row" style="padding-top:10px;">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12 thumbnail">            
                                <div class="divContainer">
                                    <?php if ($settings['udraw_price_matrix_settings_placement'] !== 'bottom'){ ?>
                                        <div class="row" style="margin:0px">
                                            <div id="divSettings" class="divSettings col-md-12" style="padding: 0;"></div>
                                        </div>
                                    <?php } ?>
                                    <div id="canvas" class="divCanvas"></div>    
                                    <?php if ($settings['udraw_price_matrix_settings_placement'] === 'bottom'){ ?>
                                        <div class="row" style="margin:0px">
                                            <div id="divSettings" class="divSettings col-md-12" style="padding: 0;"></div>
                                        </div>
                                    <?php } ?>            
                                </div>                                
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><?php _e('Subtotal :', 'udraw') ?></strong>
                                        <span style="font-size: 22pt;color: rgb(0, 128, 0);font-weight: bold;">
                                            <img src="<?php echo UDRAW_PLUGIN_URL ?>assets/includes/price-matrix-loading.gif" id="udraw-price-matrix-loading-img" />
                                            <span id="totalPriceSymbol"><?php echo get_woocommerce_currency_symbol(); ?></span><span id="totalPrice"></span>
                                        </span>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <?php if (!isset($isStaticProduct)) { ?>
                                        <div class="col">
                                            <a href="#" class="btn btn-warning" id="udraw-product-back-btn">
                                                <i class="fa fa-chevron-left"></i>
                                                <span><?php _e('Back', 'udraw') ?></span>
                                            </a>
                                        </div>
                                        <?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
                                            <div class="col">
                                                <a href="#" class="btn btn-info" id="udraw-product-save-btn">
                                                    <i class="far fa-save"></i>
                                                    <span><?php _e('Save & Finish Later', 'udraw') ?></span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    <?php }  else { ?>
                                        <div class="col">
                                            <a href="#" class="btn btn-warning" id="udraw-block-preview-back-btn">
                                                <i class="fa fa-chevron-left"></i>
                                                <span><?php _e('Back', 'udraw') ?></span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="col" style="text-align: right;">
                                        <a href="#" style="display:none;" class="btn btn-success" id="udraw-add-to-cart-btn" >
                                            <span><?php _e('Add To Cart', 'udraw') ?></span>
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="panel panel-default">
                          <div class="panel-body">
                              <div id="udraw-product-preview" class="row">
                              </div>
                              <br />
                              <strong style="font-size:12pt"><?php _e('Options:', 'udraw') ?></strong>
                              <div id="udraw-price-matrix-product-options" class="row">
                              </div>                              
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" />

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>


<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<script type="text/javascript"> 
    jQuery(document).ready(function ($) {
        <?php echo $settings['udraw_price_matrix_js_hook'] ?>

        //To restrict submitting price matrix form when "Enter" key is pressed.
        //Conflicting with designer options. Need to rework, commenting out for now.
        /*jQuery(window).keydown(function(event){
            if(event.keyCode == 13 || event.keyCode == '13') {
                event.preventDefault();
                return false;
            }
        });*/

        $('#udraw-next-step-1-btn-price-matrix').on('click', function () {
            window.designerAction = 'addToCart';
            $('span, i', this).hide();
            $('i.fa-spinner', this).show();
            jQuery('#udraw-next-step-1-btn').trigger('click');
        });

        $('#udraw-product-back-btn').on('click', function() {
            $('.price_matrix_form #udraw-bootstrap').hide();
            $('#udraw-price-matrix-ui').hide();           
            $('#udraw-main-designer-ui').fadeIn();
            $('#designer-wrapper').show().css('top', 0);
            $('#udraw-save-later-design-btn, button.udraw-save-later-design-btn').fadeIn();
            $('#udraw-next-step-1-btn-label').html("Next Step");
        });

        $('#udraw-block-preview-back-btn').on('click', function (evt) {
            $('#udraw-price-matrix-ui').hide();
            $('#pdf-xmpie-product-ui').fadeIn();
            $('#pdf-block-product-ui').fadeIn();
            //$('#udraw-price-matrix-ui').fadeIn();
            approvedButtonClicked = false; // reset approve button
            // Scroll to top of form.
            jQuery('html,body').animate({ scrollTop: jQuery('head') }, 700);
            evt.preventDefault(); return false;
        });

        $('#udraw-product-save-btn').on('click', function() {
            window.designerAction = 'saveDesign';
            $('input[name="udraw_save_product_data"]').val(Base64.encode(RacadDesigner.GenerateDesignXML()));
            $('input[name="udraw_save_product_preview"]').val(RacadDesigner.GetDocumentPreviewThumbnail());

            $('#udraw_save_later').submit();            
        });
        
        $('#udraw-add-to-cart-btn').on('click', function () {
            jQuery('.udraw-product form.cart').submit();
        });               
        
    });

    function display_udraw_price_matrix_preview() {
        jQuery('#udraw-add-to-cart-btn').hide();

        eFileName = '<?php echo admin_url('admin-ajax.php') . '?action=udraw_price_matrix_get&price_matrix_id='. $udraw_price_matrix_access_key; ?>';

        priceMatrixObj = PriceMatrix({
            url: eFileName,
            key: '<?php echo uDraw::get_udraw_activation_key(); ?>',
            callback: function (obj) {
                json = priceMatrixObj.getFields();
                bs = json;
                AddSettings();
                if (!loadedFromCart && !loadedfromSavedDesign) {
                    selectedDefault = priceMatrixObj.getDataDefaults();//jQuery.parseJSON(response);
                    selectedByUser = selectedDefault;
                } else if (loadedFromCart || loadedfromSavedDesign) {
                    if (jQuery('#udraw-options-page-design-btn').length > 0) {
                        jQuery('#udraw-options-page-design-btn span').text('Modify Design');
                    }
                }

                // Now that we have all data, display UI.                        
                DisplayFieldsJSON(true);

                if (priceMatrixInit) {
                    // Show Form.
                    jQuery('#udraw-price-matrix-loading').hide();
                    jQuery('#udraw-display-options-ui').show();

                    priceMatrixInit = false;
                }
            }
        });        
    }

    function uDraw_display_product_previews() {
        var _placeHolder = document.getElementById("udraw-product-preview");

        while (_placeHolder.hasChildNodes()) {
            _placeHolder.removeChild(_placeHolder.lastChild);
        }            

        for (var x = 0; x < RacadDesigner.Pages.list.length; x++) {
        //for (var x = 0; x < RacadDesigner.pages.length; x++) {
            var imgPreview = document.createElement("img");
            imgPreview.src = RacadDesigner.Pages.list[x].DataUri;
            //imgPreview.src = RacadDesigner.pages[x].DataUri;
            imgPreview.setAttribute("class", "thumbnail col-md-6");
            _placeHolder.appendChild(imgPreview);
        }        
    }
    
    function __display_price_callback(response) {        
        var _html = "";
        var _selectedOutput = jQuery.parseJSON(selectedOutput);
        jQuery('input[name="udraw_price_matrix_selected_options"]').val(selectedOutput);
        jQuery('input[name="udraw_price_matrix_selected_options_object"]').val(JSON.stringify(selectedPMOptions));
        jQuery('input[name="udraw_price_matrix_selected_options_idx"]').val(selectedByUser);
        jQuery('input[name="udraw_price_matrix_price"]').val(response.Price);
        jQuery('input[name="udraw_price_matrix_qty"]').val(jQuery("#txtQty").val());
		if (jQuery("#txtRecords").val() > 0){
			jQuery('input[name="udraw_price_matrix_records"]').val(jQuery("#txtRecords").val());
        }
        if (typeof response.Weight != 'undefined') {
            jQuery('input[name="udraw_price_matrix_weight"]').val(response.Weight);
            jQuery('input[name="udraw_price_matrix_length"]').val(response.Length);
        }
        if (typeof response.Width != 'undefined') {
            jQuery('input[name="udraw_price_matrix_width"]').val(response.Width);
            jQuery('input[name="udraw_price_matrix_height"]').val(response.Height);
        }
        if (typeof response.ShippingDimensions != 'undefined') {
            var _stripped = response.ShippingDimensions.replace(/\""/g, '"');
            jQuery('input[name="udraw_price_matrix_shipping_dimensions"]').val(_stripped);
        }
        jQuery.each(_selectedOutput, function (key, value) {
            _html += "<label class=\"col-md-12\"><strong>"+ key +"</strong>: " + value + "</label>";
        });
        
        jQuery('#udraw-price-matrix-product-options').empty();
        jQuery('#udraw-price-matrix-product-options').html(_html);

        jQuery('#udraw-add-to-cart-btn').show();
    }
</script>

<style>
    #udraw-bootstrap #udraw-product-preview .thumbnail {
        height: 100%;
        max-height: 250px;
    }
</style>