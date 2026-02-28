<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
//This is for if display options first
global $product, $post, $woocommerce;

if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
    $product_type = $product->get_type();
    $product_id = $product->get_id();
    $selected_attributes = $product->get_default_attributes();
} else {
    $product_type = $product->product_type;
    $product_id = $product->id;
    $selected_attributes = $product->get_variation_default_attributes();
}
            
$attributes = $product->get_variation_attributes();
$available_variations = $product->get_available_variations();

$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();

$_session_upload_id = uniqid();

$_cart_item_key = '';
// uDraw param for cart item key value
if (isset($_GET['cart_item_key'])) { $_cart_item_key = $_GET['cart_item_key']; }
// support for other plugin that uses diff. name than uDraw
if (isset($_GET['tm_cart_item_key'])) { $_cart_item_key = $_GET['tm_cart_item_key']; }

?>
<div id="udraw-display-options-ui" style="display: block;">
<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
    <?php if ( ! empty( $available_variations ) ) : ?>
    <table class="variations" cellspacing="0">
        <tbody>
        <?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
            <tr>
                <td class="label"><label for="<?php echo sanitize_title( $name ); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
                <td class="value">
                <select class="variation_select" id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
                <option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
                <?php
                    if ( is_array( $options ) ) {
                        if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
                            $selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
                        } elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
                            $selected_value = $selected_attributes[ sanitize_title( $name ) ];
                        } else {
                            $selected_value = '';
                        }
                        // Get terms if this is a taxonomy - ordered
                        if ( taxonomy_exists( $name ) ) {
                            $terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );
                            foreach ( $terms as $term ) {
                                if ( ! in_array( $term->slug, $options ) ) {
                                    continue;
                                }
                                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                            }
                        } else {
                            foreach ( $options as $option ) {
                                echo '<option value="' . esc_attr(  $option  ) . '" ' . selected( esc_attr( $selected_value ), esc_attr( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                            }
                        }
                    }
                ?>
                </select>
                    <?php
                    if ( sizeof( $attributes ) === $loop ) {
                        echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

    <div class="single_variation_wrap" style="display:none;">
        <?php do_action( 'woocommerce_before_single_variation' ); ?>

        <div class="single_variation"></div>

        <div class="variations_button">
            <?php woocommerce_quantity_input(); ?>
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
            <button type="submit" <?php echo $style ?> id="udraw-options-submit-form-btn" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>		
        </div>

        <input type="hidden" name="add-to-cart" value="<?php echo $product_id; ?>" />
        <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
        <input type="hidden" name="variation_id" class="variation_id" value="" />
        <input type="hidden" name="udraw_options_uploaded_files" value="" />
        <input type="hidden" name="udraw_options_converted_pdf" value="" />

        <?php do_action( 'woocommerce_after_single_variation' ); ?>
    </div>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    <?php else : ?>

    <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

    <?php endif; ?>

</form>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
    <?php
    $uDraw = new uDraw();
    $designTemplateId = $uDraw->get_udraw_template_ids($post->ID);
    $blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);
    $xmpieProductId = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
    $isTemplatelessProduct = get_post_meta($post->ID, '_udraw_templateless_product', true);
    if (gettype($xmpieProductId) == 'NULL') {
        $xmpieProductId = array();
    }
    
    $is_upload_product = false;
    $is_design_product = false;
    $is_converted_pdf_product = false;
    $is_upload_artwork = false;
    $is_design_product_update = false;
    if (count($designTemplateId) > 0 || count($xmpieProductId) > 0 || count($blockProductId) > 0 || $isTemplatelessProduct) { $is_design_product = true; }
    $allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
    $allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
    if ($allowUploadArtwork == "yes" || $allowDoubleUploadArtwork == "yes" || !$is_design_product) {
        $is_upload_artwork = true;
    }
    // Attempt to load in design from cart.
    if( strlen($_cart_item_key) > 0 ) {
        //load from cart item
        $cart = $woocommerce->cart->get_cart();
        $cart_item = $cart[$_cart_item_key];
        if($cart_item) {
            if( isset($cart_item['udraw_data']) ) {
                if (isset($cart_item['udraw_data']['udraw_product_data']) && strlen($cart_item['udraw_data']['udraw_product_data']) > 0) {
                    $is_design_product_update = true;
                    if ($cart_item['udraw_data']['udraw_options_converted_pdf']) {
                        $is_converted_pdf_product = true;
                    }
                }
                if (strlen($cart_item['udraw_data']['udraw_options_uploaded_files']) > 0) {
                    $is_upload_product = true;
                }
            }
        }
    }
    ?>
    <br />
    <table id="udraw-options-actions-btn-table">
        <tr class="udraw_action_row design_row">
            <?php if ($is_design_product) { if (($is_upload_product && $is_converted_pdf_product)|| !$is_upload_product) { ?>
            <td>
                <button id="udraw-options-page-design-btn" class="button btn btn-primary">
                    <span id="udraw-design-online-span"><?php _e('Design Now','udraw') ?></span>
                    <i class="fa fa-pulse fa-spinner" style="display: none;"></i>
                </button>
            </td>
            <?php } } ?>
            <?php do_action('udraw_product_action_row_design_row_custom', $post); ?>
        </tr>
        <tr class="udraw_action_row upload_row">
            <?php if ($is_upload_artwork || $is_upload_product) { ?>
            <td>
			<a class="UploadFront">Upload Front Side</a>
                <a href="#" id="udraw-options-page-upload-btn-a" class="button btn btn-primary" onclick="javascript: return false;"><?php if ($is_upload_product) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
                <input style="display: block;visibility: hidden;width: 0;height: 0;" id="fileuploadA" type="file" name="files[]" multiple>
            </td>
			<td>
			<a class="UploadBack">Upload Back Side</a>
                <a href="#" id="udraw-options-page-upload-btn-b" class="button btn btn-primary" onclick="javascript: return false;"><?php if ($is_upload_product) { echo "Replace File"; } else { echo "Select File(s)"; } ?></a>
                <input style="display: block;visibility: hidden;width: 0;height: 0;" id="fileuploadB" type="file" name="files2[]" multiple>
            </td>
            <?php } ?>
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
    

</div>

<div class="container" id="udraw-bootstrap" style="background:none;" >
    <div id="udraw-upload-preview-div" style="display:none;">
        <div class="row" style="padding-bottom:15px; padding-top: 50px;">
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