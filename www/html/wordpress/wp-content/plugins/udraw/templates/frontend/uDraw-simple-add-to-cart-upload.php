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

global $product, $post, $woocommerce;

if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_id = $product->get_id();
} else {
    $product_id = $product->id;
}

$is_design_product = false;
$is_upload_artwork = false;

$uDraw = new uDraw();
$designTemplateId = $uDraw->get_udraw_template_ids($post->ID);
$blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);
$xmpieProductId = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
$isTemplatelessProduct = get_post_meta($post->ID, '_udraw_templateless_product', true);
if (gettype($designTemplateId) === 'array') {
    if (count($designTemplateId) > 0) {
        $is_design_product = true;
    }
}
if (gettype($xmpieProductId) === 'array') {
    if (count($xmpieProductId) > 0) {
        $is_design_product = true;
    }
}
if (gettype($blockProductId) === 'array') {
    if (count($blockProductId) > 0) {
        $is_design_product = true;
    }
}
if ($isTemplatelessProduct) {
    $is_design_product = true;
}

$allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
$allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
if ($allowUploadArtwork == "yes" ||$allowDoubleUploadArtwork == "yes" || !$is_design_product) {
    $is_upload_artwork = true;
}

$allowConvertPDF = get_post_meta($post->ID, '_udraw_allow_convert_pdf', true); //'yes'
?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
         <input type="hidden" name="udraw_options_uploaded_files" value="" />
         <input type="hidden" name="udraw_options_converted_pdf" value="" />
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php
	 		if ( !$product->is_sold_individually()) {
	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
	 			) );
	 		}
	 	?>
	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" />
<?php 
        $bypass_design = false;
        if (metadata_exists('post', $post->ID, '_udraw_allow_design_bypass')) {
            $bypass_design = get_post_meta($post->ID, '_udraw_allow_design_bypass', true);
        }
        $style = '';
        if ($bypass_design === false || $bypass_design === '') {
            $style = 'style="display:none;"';
        }
        ?>
        <button type="submit" <?php echo $style; ?> id="udraw-options-submit-form-btn" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>

<?php
$is_upload_product_update = false;
$is_design_product_update = false;
$is_converted_pdf_product = false;
$_cart_item_key = (isset($_REQUEST['cart_item_key'])) ? $_REQUEST['cart_item_key'] : '';

// Attempt to load in design from cart.
if( strlen($_cart_item_key) > 0 ) {
    //load from cart item
    $cart = $woocommerce->cart->get_cart();
    $cart_item = $cart[$_cart_item_key];
    if($cart_item) {
        if( isset($cart_item['udraw_data']) ) {
            if (strlen($cart_item['udraw_data']['udraw_options_uploaded_files']) > 0) {
                $is_upload_product_update = true;
                if ($cart_item['udraw_data']['udraw_options_converted_pdf']) {
                    $is_converted_pdf_product = true;
                }
            } else {
                if (strlen($cart_item['udraw_data']['udraw_product_data']) > 0) {
                    $is_design_product_update = true;
                }
            }
        }
    }
}
?>
<table id="udraw-options-actions-btn-table" style="width: 100%;text-align: center;">
    <tr class="udraw_action_row design_row">
        <?php if ( $is_design_product && (!$is_upload_product_update || $is_converted_pdf_product)) { ?>
        <td>
            <button id="udraw-options-page-design-btn" class="button btn btn-primary">
                <span id="udraw-design-online-span"><?php _e('Design Now','udraw') ?></span>
            </button>
        </td>
        <?php } ?>
        
        <?php do_action('udraw_product_action_row_design_row_custom', $post); ?>
    </tr>
    <tr class="udraw_design_row upload_row">
        <?php if ($is_upload_artwork && !$is_design_product_update) {  
        if ($allowDoubleUploadArtwork === "yes") {  
        ?>
        <td>
		<a class="UploadFront">Upload Front Side</a>
            <a href="#" id="udraw-options-page-upload-btn-a" class="button btn btn-primary" onclick="javascript: return false;">Upload Artwork <?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
            <input style="display: none;visibility: hidden;width: 0;height: 0;" id="fileuploadA" type="file" name="files[]" accept="image/jpg,image/png,image/jpeg,image/gif,application/pdf" multiple>
        </td>
		<td>
		<a class="UploadBack">Upload Back Side</a>
            <a href="#" id="udraw-options-page-upload-btn-b" class="button btn btn-primary" onclick="javascript: return false;"><?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
            <input style="display: none;visibility: hidden;width: 0;height: 0;" id="fileuploadB" type="file" name="files2[]" accept="image/jpg,image/png,image/jpeg,image/gif,application/pdf" multiple>
        </td>
        <?php }  else { ?>
        <td>
		<!--<a class="UploadFront">Upload Front Side</a>-->
            <a href="#" id="udraw-options-page-upload-btn-a" class="button btn btn-primary" onclick="javascript: return false;">Upload Artwork</a>
            <input style="display: none;visibility: hidden;width: 0;height: 0;" id="fileuploadA" type="file" name="files[]" accept="image/jpg,image/png,image/jpeg,image/gif,application/pdf" multiple>
        </td>
        <?php } }?>
        <td colspan="2">
            <div id="udraw-options-file-upload-progress" style="display:none;">
                <div class="udraw-progress-bar udraw-progress-bar-animate">
			        <span style="width: 0%"><span></span></span>
		        </div>
				<div class="dimension_check"></div>
                <div class="udraw-uploaded-files-list"></div>
            </div>
        </td>
    </tr>
</table>

<div class="container" id="udraw-bootstrap" style="background:none;" >
    <div id="udraw-upload-preview-div" style="display:none;">
        <div class="row" style="padding-bottom:15px; padding-top: 50px">
            <a href="#" class="btn btn-danger" id="udraw-preview-back-to-options-btn"><strong>Back to Options</strong></a>
            <a href="#" class="btn btn-success" id="udraw-preview-approve-btn"><strong>Approve &amp; Add to Cart</strong></a>
        </div>
        <div class="row" id="udraw-preview-upload-placeholer">
        </div>
    </div>
</div>

<style>
    tr.udraw_action_row td{
        padding: 5px;
		width: auto;
		display: inline-block;
    }
    tr.udraw_action_row.upload_row td{
		width: 100% !important;
    }
    #udraw-upload-preview-div .row { justify-content: center; }
    #udraw-preview-upload-placeholer {
        display: grid;
        grid-template-columns: repeat(auto-fit, 350px);
        justify-content: center;
        grid-gap: 15px;
    }
	
</style>

<?php include_once(UDRAW_PLUGIN_DIR . '/templates/frontend/__display-options-first-script.php'); ?>
<?php do_action('udraw_designer_extra_scripts'); ?>