<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//If not displaying options 
global $woocommerce, $product, $post;

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
$_update_price_after_loading = false;

// Get Template Id's ( PDF, XMPie, Designer )
$designTemplateId = get_post_meta($post->ID, '_udraw_template_id', true); 
$blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);
$xmpieProductId = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);

$_cart_item_key = '';
// uDraw param for cart item key value
if (isset($_GET['cart_item_key'])) { $_cart_item_key = $_GET['cart_item_key']; }
// support for other plugin that uses diff. name than uDraw
if (isset($_GET['tm_cart_item_key'])) { $_cart_item_key = $_GET['tm_cart_item_key']; }

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
                if (strlen($cart_item['udraw_data']['udraw_pdf_xmpie_product_data'])) {
                    $xmpie_data = stripslashes($cart_item['udraw_data']['udraw_pdf_xmpie_product_data']);
                }
            }
        }
    }
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<div id="udraw-bootstrap">

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">	
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    <div id="udraw-select-options-ui" style="display:none;">                
        <div class="container" style="background: transparent;">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <a href="#" class="btn btn-success btn-sm" id="udraw-add-to-cart-btn" style="position: inherit; float:right;">                    
                        <span style="font-size:14pt;" id="udraw-next-step-1-btn-label">Add To Cart</span>
                        &nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" style="font-size:2em;"></i>                    
                    </a>
                </div>
            </div>
        </div>

        <div class="container" style="background: transparent;">
            <div class="row" style="padding-top:10px;">
                <div class="col-md-8">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="variable-product-preview" class="row">
                                </div>
                                <br />
                                <strong style="font-size:12pt"><?php _e('Options:', 'udraw') ?></strong>
                                <table class="variations" cellspacing="0">
                                    <tbody>
                                    <?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
                                        <tr>
                                            <td class="label"><label for="<?php echo sanitize_title( $name ); ?>" style="color: #797979;"><?php echo wc_attribute_label( $name ); ?></label></td>
                                            <td class="value"><select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
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
                                                    if ($selected_value != '') {
                                                        $_update_price_after_loading = true;
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
                                <div id="variable-product-options" class="row">
                                </div>
                                <hr />
                                <div id="variable-product-price" class="row">
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                          <button type="button" style="float:left" class="btn btn-warning btn-xs" id="variable-product-back-btn"><i class="fa fa-chevron-left"></i>&nbsp;<?php _e('Preview or Edit Your Design', 'udraw') ?></button>
                                          <?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
                                          <button type="button" style="float:right" class="btn btn-primary btn-xs" id="variable-product-save-btn"><?php _e('Save & Finish Later', 'udraw') ?></button>
                                          <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single_variation_wrap" style="display:none;">
        <div class="single_variation"></div>

        <input type="hidden" name="add-to-cart" value="<?php echo $product_id; ?>" />
        <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
        <input type="hidden" name="variation_id" value="" />
    </div>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>
</div>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<script>
    Array.prototype.equals = function (array, strict) {
        if (!array)
            return false;

        if (arguments.length == 1)
            strict = true;

        if (this.length != array.length)
            return false;

        for (var i = 0; i < this.length; i++) {
            if (this[i] instanceof Array && array[i] instanceof Array) {
                if (!this[i].equals(array[i], strict))
                    return false;
            }
            else if (strict && this[i] != array[i]) {
                return false;
            }
            else if (!strict) {
                return this.sort().equals(array.sort(), true);
            }
        }
        return true;
    }
    
    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
    
    function uDraw_display_product_previews() {
        var _placeHolder = document.getElementById("variable-product-preview");
        
        while (_placeHolder.hasChildNodes()) {
            _placeHolder.removeChild(_placeHolder.lastChild);
        }            
        
        for (var x = 0; x < RacadDesigner.Pages.list.length; x++) {
            var imgPreview = document.createElement("img");
            imgPreview.src = RacadDesigner.Pages.list[x].DataUri;
            imgPreview.setAttribute("class", "thumbnail col-md-5");
            imgPreview.style["margin"] = "3px";
            imgPreview.style["margin-left"] = "17px";
            _placeHolder.appendChild(imgPreview);
            //_placeHolder.appendChild(document.createTextNode( '\u00A0' ));
        }        
    }
    
    function uDraw_update_variable_pricing() {
        jQuery('input[name="variation_id"]').val(__get_variations_price()['variation_id']); 
        var selected_variations = __get_selected_variations_with_headers();
        var _html = "";
        for (var x = 0; x < selected_variations.length; x++) {
            _html += "<label class=\"col-md-12\"><strong>"+ selected_variations[x].label +"</strong>: " + selected_variations[x].value + "</label>";
        }
        jQuery('#variable-product-options').empty();
        jQuery('#variable-product-options').html(_html);
                
        jQuery('#variable-product-price').empty();
        jQuery('#variable-product-price').html('<div class=\"col-md-12\"><strong>Subtotal: </strong>' + __get_variations_price()['price_html'] + '</div>');
        
        jQuery('.price').css('font-size', '22pt').css('color', 'green').css('font-weight', 'bold');
        
        jQuery('#udraw-bootstrap').trigger({
            type: 'udraw_after_update_variable_pricing',
            price: __get_variations_price()
        });
    }
        
    function __get_selected_variations_with_headers() 
    {
        var attribute_headers = new Array();
        <?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>            
        attribute_headers.push('<?php echo wc_attribute_label( $name ); ?>');
        <?php endforeach ?>
        
        var selected_variations = __get_selected_variations();
        
        var response = new Array();
        for (var x = 0; x < attribute_headers.length; x++) {
            var _item = new Object();
            _item.label = attribute_headers[x];
            _item.value = selected_variations[x];
            
            response.push(_item);
        }
        
        return response;        
    }
    
    function __get_selected_variations() {        
        var attributes_object = jQuery.parseJSON('<?php echo json_encode( $attributes ) ?>');
        var attributes = Object.keys(attributes_object);
        var selected_variations = new Array();
        for ( var x = 0; x < attributes.length; x++) {
            var attribute_name = attributes[x].toLowerCase();
            attribute_name = attribute_name.replace(/\s/g, '-');
            var attribute_value = jQuery("select[name='attribute_"+ attribute_name +"']").val();
            selected_variations.push(attribute_value.capitalizeFirstLetter());
        }
        return selected_variations;
    }
    
    function __get_variations_price() {
        var selected_variations = __get_selected_variations();
        var avail_variations = jQuery('.variations_form').data('product_variations');
        for (var x = 0; x < avail_variations.length; x++) 
        {
            var _keys = Object.keys(avail_variations[x].attributes);
            var _keysArray = new Array();
            for (var key in avail_variations[x].attributes) {
                _keysArray.push(avail_variations[x].attributes[key].capitalizeFirstLetter());
            }
            if (selected_variations.equals(_keysArray)) {
                return {"variation_id" : avail_variations[x].variation_id , "price_html" : avail_variations[x].price_html }
            }
        }
        return {"variation_id" : '' , "price_html" : '' }
    }
    
    jQuery(document).ready(function( $ ) {
        <?php if ($_update_price_after_loading) { ?>
            uDraw_update_variable_pricing();
        <?php } ?>
        $('#udraw-select-options-ui select').on('change', function() {
            uDraw_update_variable_pricing();
            var all_selected = true;
            $('#udraw-select-options-ui select').each(function(){
                if ($(this).val() === '') {
                    all_selected = false;
                    return;
                }
            });
            if (all_selected) {
                $('#udraw-select-options-ui #udraw-add-to-cart-btn').show();
            } else {
                $('#udraw-select-options-ui #udraw-add-to-cart-btn').hide();
            }
        }).trigger('change');        
        
        $('#variable-product-back-btn').on('click', function() {
            $('#udraw-select-options-ui').hide();
            $('#designer-wrapper').show();
            $('#udraw-main-designer-ui').fadeIn();
            $('#udraw-save-later-design-btn, button.udraw-save-later-design-btn').fadeIn();
            $('#udraw-next-step-1-btn-label').html("Next Step");
        });
        
        $('#variable-product-save-btn').on('click', function() {
            if ('<?php echo count($blockProductId) > 0?>' === '1') {
                saveLaterButtonClicked = true;
                approvedButtonClicked = false;
                __process_pdf_preview();
                return;
            }
            <?php if (gettype($designTemplateId) == 'array') { ?>
                $('input[name="udraw_save_product_data"]').val(Base64.encode(RacadDesigner.GenerateDesignXML()));
                $('input[name="udraw_save_product_preview"]').val(RacadDesigner.GetDocumentPreviewThumbnail());
            <?php } ?>
            $('#udraw_save_later').submit();            
        });
        
        $('input[name="quantity"]').width('5em');
    });
</script>