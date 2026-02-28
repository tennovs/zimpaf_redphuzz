<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $post, $woocommerce; $wpdb;
$udraw_settings = new uDrawSettings();
$settings = $udraw_settings->get_settings();
if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_id = $product->get_id();
} else {
    $product_id = $product->id;
}
include('__price-matrix-header.php');
include('__price-matrix-script.php');
?>

<div class="udraw_price_matrix_container">
<div id="udraw-price-matrix-loading">
    <i class="fa fa-pulse fa-spinner fa-5x"></i>
</div>
<div id="udraw-display-options-ui" style="display: none;">
<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<?php
$bypass_design = false;
if (metadata_exists('post', $post->ID, '_udraw_allow_design_bypass')) {
    $bypass_design = get_post_meta($post->ID, '_udraw_allow_design_bypass', true);
}
$style = '';
if ($bypass_design === false || $bypass_design === '') {
    $style = 'style="display:none;"';
}

$allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
$allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
if ($allowUploadArtwork == "yes" ) { $is_upload_product = true; }
$is_double_upload_product = ($allowUploadArtwork == "yes" && $allowDoubleUploadArtwork == "yes") ? true : false;
?>

<form class="cart price_matrix_form" method="post" enctype='multipart/form-data'>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_idx" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options" />
    <input type="hidden" value="" name="udraw_price_matrix_selected_options_object" />
    <input type="hidden" value="" name="udraw_price_matrix_projected_pricing" />
    <input type="hidden" value="" name="udraw_price_matrix_price" />
    <input type="hidden" value="" name="udraw_price_matrix_qty" />
    <input type="hidden" value="" name="udraw_price_matrix_records" />
    <input type="hidden" value="" name="udraw_options_uploaded_files_preview" />
    <input type="hidden" value="" name="udraw_options_uploaded_files" />
    <input type="hidden" value="" name="udraw_options_converted_pdf" />
    <input type="hidden" value="" name="udraw_options_uploaded_excel" />
    <input type="hidden" value="" name="udraw_price_matrix_weight" />
    <input type="hidden" value="" name="udraw_price_matrix_width" />
    <input type="hidden" value="" name="udraw_price_matrix_height" />
    <input type="hidden" value="" name="udraw_price_matrix_length" />
    <input type="hidden" value="" name="udraw_price_matrix_shipping_dimensions" />
    <input type="hidden" value="true" name="udraw_price_matrix_submit" />
    <input type="hidden" value="" name="udraw_custom_design_name" />
    <input type="hidden" value="<?php echo $price_matrix_object[0]->name; ?>" name="udraw_price_matrix_name" />
    <div id="udraw-price-matrix-ui">
        <div id="udraw-price-matrix-ui-container" style="background: transparent;">
            <div id="udraw-price-matrix-ui-row" style="padding-top:10px;">
                <div>
                    <div class="divContainer">
                        <?php if ($settings['udraw_price_matrix_settings_placement'] !== 'bottom'){ ?>
                            <div id="divSettings" class="divSettings"></div>
                        <?php } ?>
                        <div id="canvas" class="divCanvas"></div>
                        <?php if ($settings['udraw_price_matrix_settings_placement'] === 'bottom'){ ?>
                            <div id="divSettings" class="divSettings"></div>
                        <?php } ?>
                        <div class="Total" style="width:100%; text-align: right; padding-bottom: 10px !important;">
                            <strong><?php _e('Total Price:', 'udraw') ?></strong>
                            <span style="font-size: 18pt;color: rgb(0, 128, 0);font-weight: bold;">
                                <img src="<?php echo UDRAW_PLUGIN_URL ?>assets/includes/price-matrix-loading.gif" id="udraw-price-matrix-loading-img" />
                                <span id="totalPriceSymbol"><?php echo get_woocommerce_currency_symbol(); ?></span><span id="totalPrice"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table style="width: 100%;">
        <tr>
            <td>
            <?php if ($settings['show_customer_preview_before_adding_to_cart']) {
                if(($is_design_product || $is_upload_product) && $bypass_design !== 'yes') { ?>
                    <button type="submit" <?php echo $style; ?> id="udraw-options-submit-form-btn" class="single_add_to_cart_button btn btn-primary">Preview & Add to Cart</button>
                <?php } else { ?>
                    <button type="submit" <?php echo $style; ?> id="udraw-options-submit-form-btn" class="single_add_to_cart_button btn btn-primary"><?php echo esc_html($product->single_add_to_cart_text() ); ?></button>
                <?php }
            } else { ?>
                <button type="submit" <?php echo $style; ?> id="udraw-options-submit-form-btn" class="single_add_to_cart_button btn btn-primary"><?php echo esc_html($product->single_add_to_cart_text() ); ?></button>
            <?php } ?>
            <i class="udraw-add-to-cart-spinner fa fa-spinner fa-pulse" style="display: none; margin-left: 25px; margin-top: 5px;"></i>
            </td>
        </tr>
    </table>
    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" />

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
    
<table id="udraw-options-actions-btn-table" style="width: 100%;text-align: justify;">
    <tr class="udraw_action_row design_row">
        <?php if ( ($is_design_product || $isBlockProduct || $isXmpieProduct) && (!$is_upload_product_update || $is_converted_pdf_product)) { ?>
        <td>
            <button id="udraw-options-page-design-btn" class="button btn btn-primary">
                <span id="udraw-design-online-span">
                <?php
                if (isset($_GET['cart_item_key'])) {
                    _e('Update Design','udraw');
                } else {
                    _e('Design Now', 'udraw');
                }
                ?>
                </span>
                <i class="fa fa-pulse fa-spinner" style="display: none;"></i>
            </button>
        </td>
        <?php } else { ?>
            <td></td>
        <?php } ?>

        <?php if ($is_upload_product && !$is_design_product_update && !$is_double_upload_product) { ?>
        <td>
            <a href="#" id="udraw-options-page-upload-btn-a" class="button btn btn-primary" onclick="javascript: return false;">Upload Your File(s)</a>
            <input style="display: none; visibility: hidden; width: 0; height: 0;" id="fileuploadA" type="file" name="files[]" accept="<?php echo $valid_extensions ?>" multiple>
        </td>
        <?php } else if ($is_upload_product && !$is_design_product_update && $is_double_upload_product) { ?>
        <td>
            <a class="UploadFront">Upload Front Side</a>
            <a href="#" id="udraw-options-page-upload-btn-a" class="button btn btn-primary" onclick="javascript: return false;"><?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
            <input style="display: none; visibility: hidden; width: 0; height: 0;" id="fileuploadA" type="file" name="files[]" accept="<?php echo $valid_extensions ?>" multiple>
        </td>
	<td>
            <a class="UploadBack">Upload Back Side</a>
            <a href="#" id="udraw-options-page-upload-btn-b" class="button btn btn-primary" onclick="javascript: return false;"><?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
            <input style="display: none; visibility: hidden; width: 0; height: 0;" id="fileuploadB" type="file" name="files[]" accept="<?php echo $valid_extensions ?>" multiple>
        </td>

        <?php } else { ?>
            <td></td>
        <?php } ?>
        <?php do_action('udraw_product_action_row_design_row_custom', $post); ?>
    </tr>
    
    <tr class="udraw_action_row upload_row">
        <td colspan="2">
            <div id="udraw-options-file-upload-progress" style="display:none;">
                <div class="udraw-progress-bar udraw-progress-bar-animate">
			        <span style="width: 0%"><span></span></span>
		        </div>
                <div class="udraw-uploaded-files-list"></div>
            </div>
        </td>
    </tr>
</table>

</div>

<div class="container" style="background:none;" >
    <div id="udraw-upload-preview-div" style="display:none;">
        <div class="row" style="padding-bottom:15px; padding-top: 50px;">
            <button class="btn btn-danger button" id="udraw-preview-back-to-options-btn"><strong>&nbsp;Back to Options</strong></button>
            <button class="btn btn-success button" id="udraw-preview-approve-btn"><strong>Approve &amp; Add to Cart&nbsp;></strong></button>
        </div>
        <div class="row" id="udraw-preview-upload-placeholer">
        </div>
    </div>
</div>
</div>
<script>

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

                if (priceMatrixInit) {
                    // Show Form.
                    jQuery('#udraw-price-matrix-loading').hide();
                    jQuery('#udraw-display-options-ui').show();

                    priceMatrixInit = false;

                    // Show Design Now Button.
                    /*jQuery('#udraw-options-page-design-btn').prop('disabled', false);
                    jQuery('#udraw-design-online-span').html('Design Now');*/
                }
            }
        });
    }

    function __display_price_callback(response) {
        var _html = "";
        var _selectedOutput = jQuery.parseJSON(selectedOutput);
        var qty = jQuery("#txtQty").val();
        jQuery('input[name="udraw_price_matrix_selected_options"]').val(selectedOutput);
        jQuery('input[name="udraw_price_matrix_selected_options_idx"]').val(selectedByUser);
        jQuery('input[name="udraw_price_matrix_selected_options_object"]').val(JSON.stringify(selectedPMOptions));
        jQuery('input[name="udraw_price_matrix_price"]').val(response.Price);
        jQuery('input[name="udraw_price_matrix_qty"]').val(qty);
		if (jQuery("#txtRecords").val() > 0){
			jQuery('input[name="udraw_price_matrix_records"]').val(jQuery("#txtRecords").val());
		}
        if (typeof response.Weight != 'undefined') {
            if (response.FormattedWeight > 0) {
                jQuery('input[name="udraw_price_matrix_weight"]').val(response.FormattedWeight);
            } else  {
                jQuery('input[name="udraw_price_matrix_weight"]').val(response.Weight);
            }
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
        // Show Design Button After Price is Displayed
        jQuery('#udraw-options-page-design-btn').fadeIn();
    }

    jQuery(document).ready(function ($) {
        // Display Price
        display_udraw_price_matrix_preview();
        <?php echo $settings['udraw_price_matrix_js_hook'] ?>

        //To restrict submitting price matrix form when "Enter" key is pressed.
        //Conflicting with designer options. Need to rework, commenting out for now.
        /*jQuery(window).keydown(function(event){
            if(event.keyCode == 13 || event.keyCode == '13') {
                event.preventDefault();
                return false;
            }
        });*/

        jQuery(document).on('udraw_price_matrix_display_price', function(event){
            //Disable Add to Cart Button if price is 0;
            if (parseFloat(event.formatted_price) == 0) {
                jQuery('#udraw-options-submit-form-btn').prop('disabled', true);
            } else {
                jQuery('#udraw-options-submit-form-btn').removeAttr('disabled');
            }
        });

        var price_matrix_placement = '<?php echo $settings['udraw_price_matrix_placement'] ?>';
        //Move the price matrix if there is a designated place for it, if said place exists
        if (price_matrix_placement.length > 0) {
            if ($(price_matrix_placement).length > 0) {
                $(price_matrix_placement).append($('div.udraw_price_matrix_container'));
            }
        }

        <?php if ($bypass_design && $settings['show_customer_preview_before_adding_to_cart']) { ?>
            jQuery(document).on('udraw-price-matrix-file-uploaded', function(e){
                if (jQuery('#udraw-options-submit-form-btn').text().trim() === 'Add to cart') {
                    jQuery('#udraw-options-submit-form-btn').text('Preview & Add to Cart');
                }
            });
        <?php } ?>
    });

</script>
<style>
    table#udraw-options-actions-btn-table tr.udraw_action_row td{
        padding: 5px;
		width: auto;
		display: inline-block;
    }
    table#udraw-options-actions-btn-table tr.udraw_action_row.upload_row td{
		width: 100% !important;
    }
	table.upload_table td {
		padding: 5px;
		vertical-align: middle;
		display: table-cell;
	}
    .fieldlabel .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }
    @media (min-width: 601px){
    .fieldlabel .tooltip .tooltiptext {
        visibility: hidden;
        width: 350px;
        background-color: #ededed;
        border: 1px solid #ededed;
        color: #555;
        text-align: left;
        border-radius: 6px;
        padding: 5px 5px;
        position: absolute;
        z-index: 999;
        top: -5px;
        left: 125%;
        margin-left: auto;
        opacity: 0;
        transition: opacity 0.3s;
    }
    }

    .fieldlabel .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    #udraw-upload-preview-div .row { justify-content: center; }

    #udraw-preview-upload-placeholer {
        display: grid;
        grid-template-columns: repeat(auto-fit, 350px);
        justify-content: center;
        grid-gap: 15px;
    }

    @media (max-width: 600px){
    .fieldlabel .tooltip .tooltiptext {
        visibility: hidden;
        width: 300px;
        background-color: #ededed;
        border: 1px solid #ededed;
        color: #555;
        text-align: left;
        border-radius: 6px;
        padding: 5px 5px;
        position: absolute;
        z-index: 999;
        top: 135%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
    }     
    }
    <?php echo $settings['udraw_price_matrix_css_hook'] ?>
</style>
<?php include_once(UDRAW_PLUGIN_DIR . '/templates/frontend/__display-options-first-script.php'); ?>
<?php do_action('udraw_designer_extra_scripts'); ?>