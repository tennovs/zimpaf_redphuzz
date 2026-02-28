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
    $udraw_settings = new uDrawSettings();
    $settings = $udraw_settings->get_settings();
    $product_id = $product->get_id();
    
    $udraw_price_matrix_access_key = get_post_meta($post->ID, '_udraw_SVG_price_matrix_access_key', true);
    $from_cart = isset($_REQUEST['cart_item_key']) ? 1 : 0;
    $ajax_url = admin_url('admin-ajax.php');
    $is_update = (isset($_REQUEST['cart_item_key'])) ? true : false;
    $style = ($is_update) ? '' : 'display: none';
    
    do_action( 'woocommerce_before_add_to_cart_form' );
?>
<div class="price_matrix_loading">
    <i class="fa fa-pulse fa-spinner fa-5x"></i>
</div>
<div class="price_matrix_container">
    <form class="cart price_matrix_form variations_form" method="post" enctype='multipart/form-data'>
        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
        <input type="hidden" value="" name="udraw_price_matrix_selected_options_idx" />
        <input type="hidden" value="" name="udraw_price_matrix_selected_options" />
        <input type="hidden" value="" name="udraw_price_matrix_selected_options_object" />
        <input type="hidden" value="" name="udraw_price_matrix_price" />
        <input type="hidden" value="" name="udraw_price_matrix_qty" />
        <input type="hidden" value="" name="udraw_price_matrix_records" />
        <input type="hidden" value="" name="udraw_options_uploaded_files_preview" />
        <input type="hidden" name="udraw_options_uploaded_files" value="" />
        <input type="hidden" name="udraw_options_converted_pdf" value="" />
        <input type="hidden" name="udraw_options_uploaded_excel" value="" />
        <input type="hidden" value="" name="udraw_price_matrix_weight" />
        <input type="hidden" value="" name="udraw_price_matrix_width" />
        <input type="hidden" value="" name="udraw_price_matrix_height" />
        <input type="hidden" value="" name="udraw_price_matrix_length" />
        <input type="hidden" value="" name="udraw_price_matrix_shipping_dimensions" />
        <input type="hidden" value="true" name="udraw_price_matrix_submit" />
        <input type="hidden" value="<?php echo $price_matrix_object[0]->name; ?>" name="udraw_price_matrix_name" />
        <div id="udraw-price-matrix-ui">
            <div id="udraw-price-matrix-ui-container" style="background: transparent;">
                <div id="udraw-price-matrix-ui-row" style="padding-top:10px;">
                    <div>
                        <div class="divContainer">
                            <div id="divSettings" class="divSettings"></div>
                            <div id="canvas" class="divCanvas"></div>
                            <div class="Total" style="width:100%; text-align: center; padding-bottom: 10px !important;">
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
                    <button type="submit" style="<?php echo $style ?>" class="single_add_to_cart_button button alt"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                    <i class="udraw-add-to-cart-spinner fa fa-spinner fa-pulse" style="display: none; margin-left: 25px; margin-top: 5px;"></i>
                </td>
            </tr>
        </table>
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" />

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>
</div>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

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
                if (!Boolean(<?php echo $from_cart ?>)) {
                    selectedDefault = priceMatrixObj.getDataDefaults();//jQuery.parseJSON(response);
                    selectedByUser = selectedDefault;
                }

                // Now that we have all data, display UI.                        
                DisplayFieldsJSON(true);

                if (priceMatrixInit) {
                    // Show Form.
                    jQuery('div.price_matrix_loading').hide();
                    jQuery('div.price_matrix_container').show();
                    priceMatrixInit = false;
                }
            }
        });
    }

    function __display_price_callback(response) {
        var _html = "";
        var _selectedOutput = jQuery.parseJSON(selectedOutput);
        jQuery('input[name="udraw_price_matrix_selected_options"]').val(selectedOutput);
        jQuery('input[name="udraw_price_matrix_selected_options_idx"]').val(selectedByUser);
        jQuery('input[name="udraw_price_matrix_selected_options_object"]').val(JSON.stringify(selectedPMOptions));
        jQuery('input[name="udraw_price_matrix_price"]').val(response.Price);
        jQuery('input[name="udraw_price_matrix_qty"]').val(jQuery("#txtQty").val());
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
        // Show Design Button After Price is Displayed
        jQuery('#udraw-options-page-design-btn').fadeIn();
    }

    jQuery(document).ready(function ($) {
        // Display Price
        display_udraw_price_matrix_preview();
        $('form.cart input[name="quantity"]').hide();
        <?php echo $settings['udraw_price_matrix_js_hook'] ?>
        $(document).on('udraw_price_matrix_load_templates', function(event){
            if (event.svg_template_id) {
                _disable_design_button()
                var saved_design_file = $('[name="udraw_SVG_product_data"]').val();
                if(saved_design_file.length > 0 && selectedByUser[0] === selectedDefault[0]) {
                    window.original_template_id = event.svg_template_id;
                    load_file (saved_design_file);
                } else if (saved_design_file.length > 0 && original_template_id === event.svg_template_id) {
                    load_file (saved_design_file);
                } else {
                    $('[data-udrawsvg="image_replace_modal"]').modal('hide');
                    $.ajax({
                        url: '<?php echo $ajax_url ?>',
                        type: 'POST',
                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",
                        data: {
                            action: 'udraw_svg_get_template',
                            template_id: event.svg_template_id,
                            clone: true,
                            output_path: RacadSVGDesigner.settings.output_path
                        },
                        success: function (response) {
                            var design_file = response.design_path;
                            load_file (design_file);
                        }
                    });
                }
            }
            <?php if ($from_cart) { ?>
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function(){
                _enable_design_button();
            });
            <?php } ?>
            
            function load_file (design_file) {
                if (design_file !== null && design_file !== RacadSVGDesigner.settings.design_file) {
                    if (design_file.substring(design_file.length - 3) === 'svg') {
                        RacadSVGDesigner.Load.file(design_file, function(){
                            $('[data-udrawSVG="create_page"]').hide();
                            //Build Font Size select
                            RacadSVGDesigner.Text.build_font_size_select();
                            //Build zoom dropdown menu
                            RacadSVGDesigner.Zoom.build_zoom_dropdown_menu();
                            if (RacadSVGDesigner.Images.placeholder_array.length > 0) {
                                RacadSVGDesigner.Images.show_bulk_replace_modal();
                            }
                            _enable_design_button()
                        });
                    } else if (design_file.substring(design_file.length - 4) === 'json') {
                        RacadSVGDesigner.Load.json_file(design_file, function () {
                            $('[data-udrawSVG="create_page"]').hide();
                            RacadSVGDesigner.settings.design_file = design_file;
                            //Build Font Size select
                            RacadSVGDesigner.Text.build_font_size_select();
                            //Build zoom dropdown menu
                            RacadSVGDesigner.Zoom.build_zoom_dropdown_menu();
                            if (RacadSVGDesigner.Images.placeholder_array.length > 0) {
                                RacadSVGDesigner.Images.show_bulk_replace_modal();
                            }
                            _enable_design_button()
                        });
                    }
                }
            }
            
            function _disable_design_button () {
                $('[data-udrawSVG="design_now"]').addClass('disabled').prop('disabled', true);
                $('[data-udrawSVG="design_now"] i').removeClass('hidden');
                $('[data-udrawSVG="design_now"] span.loading').removeClass('hidden');
                $('[data-udrawSVG="design_now"] span.design_now_span').addClass('hidden');
            }
            
            function _enable_design_button () {
                $('[data-udrawSVG="design_now"]').removeClass('disabled').prop('disabled', false);
                $('[data-udrawSVG="design_now"] i').addClass('hidden');
                $('[data-udrawSVG="design_now"] span.loading').addClass('hidden');
                $('[data-udrawSVG="design_now"] span.design_now_span').removeClass('hidden');
            }
        });
        
        $(document).on('udraw_price_matrix_loaded', function(e){
            //In case it doesn't actually trigger the template change
            jQuery('#udraw-price-matrix-ui div.divCanvas select').each(function(){
                var has_selected = false;
                if (jQuery('option[data-pm-selected="true"]', this).length) {
                    has_selected = true;
                }
                if (!has_selected) {
                    jQuery(this).val(jQuery(this).children()[0].value).trigger('change');
                }
                if (RacadSVGDesigner) {
                    if (!RacadSVGDesigner.design_loaded) {
                        $(this).prop('disabled', true);
                    }
                }
            });
            
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function(){
                jQuery('#udraw-price-matrix-ui div.divCanvas select').each(function(){
                    $(this).prop('disabled', false);
                });
            });
        });
        
        $(document).on('udraw_price_matrix_display_price', function (e){
            if (e.dimensions) {
                var width = parseFloat(e.dimensions.width);
                var height = parseFloat(e.dimensions.height);
                RacadSVGDesigner.settings.resize_documents = {
                    width: width,
                    height: height,
                    unit: measurement_unit_label
                }
            }
        });
        $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function(event){
            $('[data-udrawSVG="design_now"]').on('click', function(){
                RacadSVGDesigner.resize_template();
            });
        });
        $('[data-udrawSVG="design_now"]').on('click', function(){
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_design_loaded', function(event){
                RacadSVGDesigner.resize_template();
            });
        });
    });
</script>
<style>
    tr.udraw_action_row td{
        padding: 5px;
    }
    span.woocommerce-Price-amount.amount {
        display: none;
    }
    div.price_matrix_container {
        display: none;
    }
    <?php echo $settings['udraw_price_matrix_css_hook'] ?>
</style>