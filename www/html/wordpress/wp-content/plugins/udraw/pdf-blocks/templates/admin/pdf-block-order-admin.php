<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $post;
$GoEpower = new GoEpower();
$uDrawPDFBlocks = new uDrawPDFBlocks();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();
$blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);

?>

<div id="pdf-block-product-ui">
    <div id="udraw-bootstrap">
        <div class="container" style="width: 100%;">
            <div class="row"  style="padding-right:30px; padding-bottom:5px;">
                <div class="col-md-12 col-lg-12">
                    <button id="pdf-block-download-btn" class="btn btn-success" style="float:right;"><i class="fa fa-floppy-o"></i>&nbsp;<?php _e('Download Artwork', 'udraw') ?></button>                    
                    <button id="pdf-block-preview-btn" class="btn btn-primary" style="float:right; margin-right:5px;"><i class="fa fa-file-image-o"></i>&nbsp;<?php _e('Preview Now', 'udraw') ?></button>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div id="w2p-pdf-template-wrapper">
                    <div id="w2p-pdf-template-container">
                        <div id="w2p-pdf-template-product"></div>
                        <div id="w2p-pdf-template-preview"></div>
                    </div>
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
    if ($design['variation_options'] !== NULL) {
        $variation_options = json_decode(stripslashes($design['variation_options']));
        for ($i = 0; $i < count($variation_options); $i++) {
            $name = $variation_options[$i]->name;
            $value = $variation_options[$i]->value;
            ?>
            <script>
                jQuery('[name="<?php echo $name ?>"]').val('<?php echo $value ?>');
            </script>
            <?php
        }
    }
}

?>

<script type="text/javascript">
    var appPath = '<?php echo $GoEpower->get_api_url(); ?>/';
    var lastBlockPreview = '';

    function __process_pdf_preview(callback) {
        jQuery('#previewDiv').hide();
        <?php if (!$_udraw_settings['goepower_pdf_disable_refresh_button']) { ?>
            jQuery('.pdf-block-preview-btn').html('<i class="fa fa-refresh"></i>&nbsp;Refresh Preview')
        <?php } ?>
        BlocksManager.process_preview(false, callback);
    }
</script>

<style>
    #pdf-block-product-ui .select2-container .select2-choice {
        height: 55px !important;
        line-height: 55px !important;
    }
    div#w2p-pdf-template-container > div {
        display: inline-block;
        width: 45%;
        vertical-align: top;
        padding: 1%;
    }
    #pdf-block-product-ui input.ga-inputfile.ga-inputfile-theme {
        display: none;
    }
    
</style>
