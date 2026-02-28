<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uDrawPDFBlocks = new uDrawPDFBlocks();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();


?>

<div id="pdf-block-product-ui">
    <div id="udraw-bootstrap">
        <div class="container">
            <div class="row"  style="padding-right:30px; padding-bottom:5px;">
                <div class="col-md-12 col-lg-12">
                    <button id="pdf-block-download-btn" class="btn btn-success" style="float:right;"><i class="fa fa-floppy-o"></i>&nbsp;<?php _e('Download Artwork', 'udraw') ?></button>                    
                    <button id="pdf-block-preview-btn" class="btn btn-primary" style="float:right; margin-right:5px;"><i class="fa fa-file-image-o"></i>&nbsp;<?php _e('Preview Now', 'udraw') ?></button>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="col-lg-4">                    
                    <div id="pdf-block-inputs"></div>
                    <br />
                </div>
                <div class="col-lg-8">
                    <div id="pdf-block-thumbnail-preview" style="border: 1px solid rgb(203, 203, 203); float:right;">
                        <img src="<?php echo $uDrawPDFBlocks->get_product($blockProductId)['ThumbnailLarge']; ?>" />
                    </div>
                    <div id="loadingDiv" style="float:right; display:none;" >
                        <img src="<?php echo UDRAW_PLUGIN_URL ?>assets/includes/loading-animation.gif" />
                    </div>
                    <div id="previewDiv" style="padding-top:10px;"></div>
                </div>                
            </div>
        </div>
    </div>
</div>

<script> var _previous_pdf_block_entries = undefined; </script>
<?php

// Attempt to previous options selected from cart.
if( isset($_GET['cart_item_key']) ) {
    //load from cart item
    $cart = $woocommerce->cart->get_cart();
    $cart_item = $cart[$_GET['cart_item_key']];
    if($cart_item) {
        if( isset($cart_item['udraw_data']['udraw_pdf_block_product_data']) ) {
            //$json_data = json_decode(stripslashes($cart_item['udraw_data']['udraw_pdf_block_product_data']));
            echo '<script> _previous_pdf_block_entries = jQuery.parseJSON(\''. stripslashes($cart_item['udraw_data']['udraw_pdf_block_product_data']) .'\');</script>';
        }
    }
}

// Attempt to load saved customer design.
if( isset($_GET['udraw_access_key']) ) {
    $design = uDraw::get_udraw_customer_design($_GET['udraw_access_key']);
    if (strlen($design['design_data']) > 1 ) {
        echo '<script> _previous_pdf_block_entries = jQuery.parseJSON(\''. stripslashes($design['design_data']) .'\');</script>';
    }
}

?>

<script type="text/javascript">

    var udrawFileUploadHandlerURL = '<?php echo admin_url( 'admin-ajax.php' ) . '?action=udraw_price_matrix_upload&session='. uniqid() ?>';

    var lastBlockPreview = '';
    var approvedButtonClicked = false;
    var saveLaterButtonClicked = false;

    function __process_pdf_preview(productId) 
    {
        jQuery('#previewDiv').hide();
        <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
        jQuery('#pdf-block-preview-btn').html('<i class="fa fa-refresh"></i>&nbsp;Refresh Preview')
        <?php } ?>
        jQuery('#pdf-block-next-span').fadeIn();
        jQuery('#pdf-block-thumbnail-preview').fadeOut();
        jQuery('#loadingDiv').fadeIn();
        Blocks_Process(productId);
    }

    function __process_pdf_preview_completed() {
        jQuery('#loadingDiv').hide();
        setTimeout(function () {
            jQuery('#previewDiv').show();
        }, 500);

        setTimeout(function () {
            if (saveLaterButtonClicked) {
                jQuery('#udraw_save_later').submit();
            }
        }, 1000);
    }
</script>

<style>
    #pdf-block-product-ui .select2-container .select2-choice {
        height: 55px !important;
        line-height: 55px !important;
    }
</style>
