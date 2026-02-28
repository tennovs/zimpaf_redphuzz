<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $woocommerce, $wpdb;

    $_font_color = "#000000";
    $_background_color = "#FFFFFF";
    $_disable_file_upload = false;
    $_disable_design_online = false;
    $_linked_template_id = 0;
    $_measurement_unit = '';

    $uDrawPriceMatrix = new uDrawPriceMatrix();
    $price_matrix_object = $uDrawPriceMatrix->get_price_matrix_by_key($udraw_price_matrix_access_key);  
    
    $udrawSettings = new uDrawSettings();
    $_udraw_settings = $udrawSettings->get_settings();

    $_session_upload_id = uniqid();

    $uDraw = new uDraw();
    $uDraw->registerStyles();
    $uDraw->registerDesignerDefaultStyles();

    if (strlen($price_matrix_object[0]->font_color) > 1) {
        $_font_color = $price_matrix_object[0]->font_color;            
    }
    if (strlen($price_matrix_object[0]->background_color) > 1) {
        $_background_color = $price_matrix_object[0]->background_color;
    }
    if (isset($price_matrix_object[0]->disable_file_upload)) {
        $_disable_file_upload = $price_matrix_object[0]->disable_file_upload;                    
    }
    if (isset($price_matrix_object[0]->disable_design_online)) {
        $_disable_design_online = $price_matrix_object[0]->disable_design_online;
    }
    if (strlen($price_matrix_object[0]->udraw_template_id) > 0) {
        $_linked_template_id = $price_matrix_object[0]->udraw_template_id;
    }
    if (strlen($price_matrix_object[0]->measurement_label) > 0) {
        $_measurement_unit = $price_matrix_object[0]->measurement_label;
    }

?>

<style type="text/css">
    
    #canvas select, #txtQty {
        border: 0 !important;      
        -webkit-appearance: none;  
        -moz-appearance: none;     
        background: <?php echo $_background_color; ?> url(<?php echo UDRAW_PLUGIN_URL ?>assets/includes/arrowdown.png) no-repeat 98% center; /* #0088cc url(img/select-arrow.png) no-repeat 90% center; */
        text-indent: 0.01px;
        text-overflow: "";
        color: <?php echo $_font_color; ?> !important;
        padding: 5px;
        box-shadow: inset 0 0 5px rgba(000,000,000, 0.5);
        font-size:11pt;
        height:38px;
        min-width: 120px;
    }
    
    #spanQty label {
        width: 30%;
        font-size:12pt;        
    }
    
    #udraw-bootstrap img {
        padding: 0px;
        background-color: transparent;
    }
    
    #udraw-bootstrap .row {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
</style>

<form id="price_matrix_form" method="post" action="">
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_idx" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_object" />
    <input type="hidden" value="" name="udraw_price_matrix_projected_pricing" />
    <input type="hidden" value="" name="udraw_price_matrix_price" />
    <input type="hidden" value="" name="udraw_price_matrix_qty" />
	<input type="hidden" value="" name="udraw_price_matrix_records" />
	<input type="hidden" value="" name="udraw_price_matrix_contingency" />
    <input type="hidden" value="" name="udraw_price_matrix_uploaded_files" />
    <input type="hidden" value="" name="udraw_price_matrix_design_data" />
    <input type="hidden" value="" name="udraw_price_matrix_design_preview" />
    <input type="hidden" value="" name="udraw_price_matrix_weight" />
    <input type="hidden" value="" name="udraw_price_matrix_width" />
    <input type="hidden" value="" name="udraw_price_matrix_height" />
    <input type="hidden" value="" name="udraw_price_matrix_length" />
    <input type="hidden" value="" name="udraw_price_matrix_shipping_dimensions" />
    <input type="hidden" value="true" name="udraw_price_matrix_submit" />
    <input type="hidden" value="" name="udraw_custom_design_name" />
    <input type="hidden" value="<?php echo $price_matrix_object[0]->name; ?>" name="udraw_price_matrix_name" />
    <?php if (isset($_GET['cart_item_key'])) { ?>
        <input type="hidden" value="<?php echo $_GET['cart_item_key']; ?>" name="cart_item_key" />
    <?php } ?>
    
    <div id="udraw-bootstrap">        
        <div id="udraw-price-matrix-ui" style="display:block;">
        <div class="container" style="background: transparent;">
            <div class="row" style="padding-top:10px;">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">            
                            <div class="divContainer">
                                <?php if ($_udraw_settings ['udraw_price_matrix_settings_placement'] !== 'bottom'){ ?>
                                    <div class="row">
                                        <div id="divSettings" class="divSettings col-md-12" style="padding: 0;"></div>
                                    </div>                
                                    <br />
                                <?php } ?>
                                <div id="canvas" class="divCanvas"></div>  
                                <?php if ($_udraw_settings ['udraw_price_matrix_settings_placement'] === 'bottom'){ ?>
                                    <div class="row">
                                        <div id="divSettings" class="divSettings col-md-12" style="padding: 0;"></div>
                                    </div>                
                                <?php } ?>                
                            </div>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                      <div class="panel-body">
                          <div id="udraw-product-preview" class="row">
                          </div>
                          <br />
                          <strong style="font-size:12pt"><?php _e('Options:', 'udraw') ?></strong>
                          <div id="udraw-price-matrix-product-options" class="row">
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-md-12">
                                  <strong><?php _e('Total Price:', 'udraw') ?></strong>
                                  <span style="font-size: 22pt;color: rgb(0, 128, 0);font-weight: bold;">
                                      <span><?php echo get_woocommerce_currency_symbol(); ?></span><span id="totalPrice"></span>
                                  </span>
                              </div>
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-md-12">
                                  
                                <div class="progress" id="progress-bar-div" style='display:none;'>
                                  <div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span style='font-size:12pt;'><?php _e('Upload in progress', 'udraw') ?></span>
                                  </div>
                                </div>                                
                                  
                                <span class="col-md-12" id="upload-successful-span" style='display:none;'>
                                </span>
                                
                                  
                                <span class="btn btn-success fileinput-button col-md-12" id="add-to-cart-btn-span" <?php if ( (!$_disable_file_upload) || (!$_disable_design_online) ) { ?>style='display:none;'><?php } ?>
                                    <span>Confirm &amp; Add to Cart</span>
                                </span>                                  
                                
                                <?php if(!$_disable_file_upload) { ?>
                                <span class="btn btn-primary fileinput-button col-md-12" id="upload-files-btn-span">
                                    <span><?php _e('Upload File(s)', 'udraw') ?></span>
                                    <!-- The file input field used as target for the file upload widget -->
                                    <input id="fileuploadA" type="file" name="files[]" multiple>
                                    <input id="fileuploadB" type="file" name="files2[]" multiple>
                                </span>
                                <?php } ?>
                                  
                                <?php if ( (!$_disable_file_upload) && (!$_disable_design_online) ) { ?>
                                <div id="or-div-ui">
                                    <span class="col-md-4"><hr /></span>
                                    <span class="col-md-4" style="text-align:center; margin-top:8px"><strong>OR</strong></span>
                                    <span class="col-md-4"><hr /></span>
                                </div>
                                <?php } ?>
                                
                                <?php if (!$_disable_design_online) { ?>
                                <span class="btn btn-primary fileinput-button col-md-12" id="design-now-btn-span">
                                    <span id="design-now-btn-label"><?php _e('Design Now', 'udraw') ?></span>
                                </span>
                                <?php } ?>
                                    
                              </div>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    
        </div>
        
        <div id="udraw-price-matrix-designer" style="display:none;">

            <?php
            $isBlankCanvas = true;
            $displayPriceMatrixOptions = true;
            $load_frontend_navigation = true;
            include_once(UDRAW_PLUGIN_DIR . '/designer/bootstrap-default/designer-template-wrapper.php');
            ?>
        </div>
    </div>
</form>

<script type="text/javascript"> 
        
    var json, bs, selectedDefault, selectedByUser, eFileName = "";
    var selectedSaved = [];
    var selectedOutput = '';
    var selectedPrice = '';
    var loadedFromCart = false;
    var loadedfromSavedDesign = false;
    var uploadedFiles = [];
    var design_data = '';
    var design_preview = '';
    var measurement_unit_label = '<?php echo $_measurement_unit ?>';
    var priceMatrixObj;
    
    jQuery(document).ready(function($) {
        $('#udraw-product-back-btn').on('click', function() {
            $('#udraw-price-matrix-ui').hide();           
            $('#udraw-main-designer-ui').fadeIn();
            $('#udraw-save-later-design-btn, button.udraw-save-later-design-btn').fadeIn();
            $('#udraw-next-step-1-btn-label').html("Next Step");
        });
        
        $('#design-now-btn-span').on('click', function() {
            $('#udraw-price-matrix-designer').fadeIn();
            $('#udraw-price-matrix-ui').hide();
        });
        
        $('#udraw-price-matrix-show-quote').on('click', function() {
            $('#upload-files-btn-span').hide();
            $('#or-div-ui').hide();
            $('#design-now-btn-label').text('Back to Design');
            $('#udraw-price-matrix-designer').hide();
            $('#udraw-price-matrix-ui').fadeIn();
        });
        
        $('#udraw-price-matrix-designer-save').on('click', function() {
            $('input[name="udraw_price_matrix_design_data"]').val(Base64.encode(RacadDesigner.GenerateDesignXML()));
            $('input[name="udraw_price_matrix_design_preview"]').val(RacadDesigner.GetDocumentPreviewThumbnail());

            jQuery('#price_matrix_form').submit();         
        });                
        
        $('#fileuploadA').fileupload({
            url: '<?php echo admin_url( 'admin-ajax.php' ) . '?action=udraw_price_matrix_upload&session='. $_session_upload_id ?>',
            dataType: 'json',
            done: function (e, data) {
                $('#upload-successful-span').show();
                for (var x = 0; x < data.result.length; x++) {
                    var _item = new Object();
                    _item.name = data.result[x].name;
                    _item.url = data.result[x].url;
                    uploadedFiles.push(_item);
                    jQuery('#upload-successful-span').after('<label class="col-md-12" style="font-size:15px; line-height: 2"><i class=\"fa fa-check-circle\" style=\"color: green; transform: scale(1.5,1.5); margin-right: 15px;\"></i><strong>Uploaded</strong>: ' + _item.name + '</label>');
                }
                jQuery(document).trigger({
                    type: 'udraw-price-matrix-file-uploaded',
                    uploadedFiles: uploadedFiles
                });
                
                $('#progress-bar-div').hide();
                //$('#upload-files-btn-span').hide();
                $('#design-now-btn-span').hide();
                $('#add-to-cart-btn-span').show();
                $('#or-div-ui').hide();
            },
            start: function(e) {
                $('#progress-bar-div').show();
            },
            send: function (e, data) {
               $('#progress-bar-div').show(); 
            },
            progressall: function (e, data) {

            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');  

        $('#fileuploadB').fileupload({
            url: '<?php echo admin_url( 'admin-ajax.php' ) . '?action=udraw_price_matrix_upload&session='. $_session_upload_id ?>',
            dataType: 'json',
            done: function (e, data) {
                $('#upload-successful-span').show();
                for (var x = 0; x < data.result.length; x++) {
                    var _item = new Object();
                    _item.name = data.result[x].name;
                    _item.url = data.result[x].url;
                    uploadedFiles.push(_item);
                    jQuery('#upload-successful-span').after('<label class="col-md-12" style="font-size:15px; line-height: 2"><i class=\"fa fa-check-circle\" style=\"color: green; transform: scale(1.5,1.5); margin-right: 15px;\"></i><strong>Uploaded</strong>: ' + _item.url + '</label>');
                }
                jQuery(document).trigger({
                    type: 'udraw-price-matrix-file-uploaded',
                    uploadedFiles: uploadedFiles
                });
                
                $('#progress-bar-div').hide();
                $('#upload-files-btn-span').hide();
                $('#design-now-btn-span').hide();
                $('#add-to-cart-btn-span').show();
                $('#or-div-ui').hide();
            },
            start: function(e) {
                $('#progress-bar-div').show();
            },
            send: function (e, data) {
               $('#progress-bar-div').show(); 
            },
            progressall: function (e, data) {

            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');  
        
        $('#add-to-cart-btn-span').click(function() {
            var _upload_path = '<?php echo $_udraw_settings['udraw_price_matrix_upload_path'] . $_session_upload_id . "/"; ?>';
            $('input[name="udraw_price_matrix_uploaded_files"]').val(JSON.stringify(uploadedFiles));
            
             jQuery('#price_matrix_form').submit();
        });
        
        // Handle GET request of previously loaded design with price matrix.
        <?php if (isset($_GET['cart_item_key'])) { ?>
            jQuery('#udraw-price-matrix-designer').fadeIn();
            jQuery('#udraw-price-matrix-ui').hide();
        
            <?php
                if (isset($_GET['cart_item_key'])) {
                    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                        if ($cart_item_key == $_GET['cart_item_key']) {
                            ?>
                                var selectedOutput = '<?php echo $values['udraw_data']['udraw_price_matrix_selected_options']; ?>';
                                var selectedPMOptions = '<?php echo $values['udraw_data']['udraw_price_matrix_selected_options_object']; ?>';
                                var projected_pricing = '<?php echo $values['udraw_data']['udraw_price_matrix_projected_pricing']; ?>';
                                var _idx = '<?php echo $values['udraw_data']['udraw_price_matrix_selected_options_idx']; ?>';
                                selectedSaved = _idx.split(',');
                                selectedDefault = _idx.split(',');
                                selectedByUser = _idx.split(',');
                                loadedFromCart = true;
                                setTimeout(function() {
                                    jQuery("#txtQty option[value='<?php echo $values['udraw_data']['udraw_price_matrix_qty']; ?>']").prop('selected', "selected");
                                    DisplayFieldsJSON(true);
                                }, 1500);
                            <?php
                        }                    
                    }
                }
            }
        ?>
        
        display_udraw_price_matrix_preview();

    });

    function display_udraw_price_matrix_preview() {
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
            }
        });       
    }

    function uDraw_display_product_previews() {
        var _placeHolder = document.getElementById("udraw-product-preview");

        while (_placeHolder.hasChildNodes()) {
            _placeHolder.removeChild(_placeHolder.lastChild);
        }            

        for (var x = 0; x < RacadDesigner.Pages.list.length; x++) {
            var imgPreview = document.createElement("img");
            imgPreview.src = RacadDesigner.Pages.list[x].DataUri;
            imgPreview.setAttribute("class", "thumbnail col-md-5");
            //imgPreview.style["margin"] = "3px";
            //imgPreview.style["margin-left"] = "17px";
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
		if (jQuery("#txtRecords").val() > 0){
			jQuery('input[name="udraw_price_matrix_records"]').val(jQuery("#txtRecords").val());
		}
        jQuery('input[name="udraw_price_matrix_qty"]').val(jQuery("#txtQty").val());
        jQuery('input[name="udraw_price_matrix_contingency"]').val(jQuery("#txtContingency").val());
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
            _html += '<label class="col-md-12"><strong>'+ key +'</strong>: ' + value + '</label>';
        });
        
        jQuery('#udraw-price-matrix-product-options').empty();
        jQuery('#udraw-price-matrix-product-options').html(_html);                
    }
    
    function __redirct_to_cart() {
        window.location.href = "<?php echo get_permalink( get_option('woocommerce_cart_page_id') ); ?>";
    }
    
    function __loaded_udraw_finished() {
        <?php if ($_linked_template_id == 0) { ?>
        RacadDesigner.zoom.currentZoom = 1;
        RacadDesigner.SetCanvasInInches(bs.DefaultWidth,bs.DefaultHeight);
        RacadDesigner.canvas.setWidth(RacadDesigner.documentSize.width * RacadDesigner.zoom.currentZoom);
        RacadDesigner.canvas.setHeight(RacadDesigner.documentSize.height * RacadDesigner.zoom.currentZoom);
        RacadDesigner.InitCanvas();
        RacadDesigner.ReloadObjects();
        <?php } else { ?>
        <?php             
            if ($_linked_template_id > 0) {
                $uDrawTemplate = $uDraw->get_udraw_templates($_linked_template_id);
                $uDrawDesign = $uDrawTemplate[0]->design;
                echo "RacadDesigner.Legacy.loadCanvasDesign('". $uDrawDesign ."');";
            }
        ?>
        <?php } ?>
    }
    
</script>

<?php include_once(UDRAW_PLUGIN_DIR . '/designer/designer-template-script.php'); ?>


<?php

    // Handle POST and add to cart.
    if (isset($_POST["udraw_price_matrix_submit"])) {
        if ($_POST["udraw_price_matrix_submit"] == "true") {
            ?>
            <script type="text/javascript">__redirct_to_cart();</script>
            <?php
        }
    }
?>