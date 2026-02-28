<?php 
    global $woocommerce, $product;
    if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
        $product_type = $product->get_type();
        $product_id = $product->get_id();
    } else {
        $product_type = $product->product_type;
        $product_id = $product->id;
    }
    $displayInlineAddToCart = false;
    if ($product_type == "simple" && !$isPriceMatrix) {
        $displayInlineAddToCart = true;
    }
    $friendly_item_name = get_the_title($post->ID);
    $allow_structure_file = false;
    if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
?>

<!-- Display option first or has multiple templates linked -->
<?php if ($displayOptionsFirst || (!$displayOptionsFirst && $templateCount > 1)) { ?>
    <button class="btn bg-light" id="show-udraw-display-options-ui-btn">
        <i class="fas fa-chevron-left"></i>
        <span class="desktop_only left_space">Back to Options</span>
    </button>
<?php } ?>

<div class="dropdown">
    <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-list"></i>
        <span class="desktop_only left_space">Views</span>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" data-udraw="toggleRuler" href="#">
            <i class="fas fa-ruler-combined"></i>
            <span class="left_space">Ruler</span>
        </a>
        <a class="dropdown-item" data-udraw="snapToGrid" href="#">
            <input type="checkbox" data-udraw="snapCheckbox">
            <span class="left_space">Snap To Grid</span>
        </a>
        <a class="dropdown-item" data-udraw="toggleGridLines" href="#">
            <input type="checkbox" data-udraw="gridCheckbox">
            <span class="left_space">Toggle Grid Lines</span>
        </a>
    </div>
</div>

<?php if ($allowCustomerDownloadDesign) {?>
    <button type="button" class="btn bg-light" data-udraw="downloadPDFButton">
        <i class="fas fa-cloud-download-alt"></i>
        <span class="left_space">Download PDF</span>
    </button>
<?php } ?>
<?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
    <button type="button" class="btn bg-light" id="udraw-save-later-design-btn">
        <i class="fas fa-save"></i>
        <span class="left_space">Save Design</span>
    </button>
<?php } ?>

<button class="btn bg-light toggle_pages_layers">
    <i class="far fa-file"></i>
    <span>/</span>
    <i class="fas fa-list"></i>
</button>

<!-- Add to cart button / Next step buttons -->
<?php if ($displayOptionsFirst) { ?>
    <?php if ($product_type == "variable") { ?> 
        <button type="button" class="btn btn-success" data-udraw="cart_btn">
            <span><?php _e('Next Step', 'udraw') ?></span>
            <i class="fas fa-chevron-right left_space"></i>
        </button>
    <?php } ?>
    <?php if ($allow_structure_file) { ?>
        <button type="button" class="btn btn-success" data-udraw="excelContinue">
            <span><?php _e('Continue', 'udraw') ?></span>
            <i class="fas fa-chevron-right left_space"></i>
        </button>
    <?php } else { ?>
        <button class="btn btn-success" id="udraw-add-to-cart-btn">
            <span class="desktop_only">Add to Cart</span>
            <i class="fas fa-shopping-cart left_space"></i>
        </button>
    <?php } ?>
<?php } else  { ?>
    <!-- Not display options first -->
    <?php if (isset($displayPriceMatrixOptions)) { ?>
        <button type="button" class="btn btn-default" id="udraw-price-matrix-show-quote">
            <i class="fas fa-chevron-left"></i>
            <span class="left_space"><?php _e('Show Quote', 'udraw') ?></span>
        </button>>
        <button type="button" class="btn btn-success" id="udraw-price-matrix-designer-save">
            <span><?php _e('Next', 'udraw') ?></span>
            <i class="fas fa-chevron-right left_space"></i>
        </button>
    <?php } else { ?>
        <?php if ($product_type == "variable") { ?> 
            <button type="button" class="btn btn-success" id="udraw-variations-step-1-btn">
                <span id="udraw-variations-step-1-btn-label">Next Step</span>
                <i class="fas fa-chevron-right left_space"></i>
            </button>
            <button type="button" class="btn btn-default" id="udraw-variations-step-0-btn" style="display:none;">
                <i class="fas fa-chevron-left"></i>
                <span id="udraw-variations-step-0-btn-label" class="left_space">Back to Design</span>
            </button>                   
        <?php } else if ($product_type == "variable" ||$isPriceMatrix) { ?>
            <button type="button" class="btn btn-success" id="udraw-next-step-1-btn">
                <span id="udraw-next-step-1-btn-label">Next Step</span>
                <i class="fas fa-chevron-right left_space"></i>
            </button>
        <?php } else { ?>
        <form class="cart" method="post" enctype="multipart/form-data" >
            <input type="hidden" value="" name="udraw_product">
            <input type="hidden" value="" name="udraw_product_data">
            <input type="hidden" value="" name="udraw_product_svg">
            <input type="hidden" value="" name="udraw_product_preview">
            <input type="hidden" value="" name="udraw_product_cart_item_key">

            <?php if ($displayInlineAddToCart) {?>
            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text form-control" size="4">
            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>">
            <p>
                <span><?php echo get_woocommerce_currency_symbol(); ?></span>
                <span id="product_total_price"><?php echo $product->get_price(); ?></span>
            </p>
            <button type="button" data-udraw="cart_btn" class="btn btn-success">
                <i class="fas fa-shopping-cart"></i>
                <span class="left_space"><?php echo $product->single_add_to_cart_text(); ?></span>
            </button>
            <?php } ?>
        </form>
        <?php } ?>
    <?php } ?>
<?php } ?>

<style>
    [data-udraw="uDrawBootstrap"] [data-udraw="designerMenu"] form.cart {
        display: inline-block;
        margin: 0;
        padding: 0;
    }
    [data-udraw="uDrawBootstrap"] [data-udraw="designerMenu"] form.cart input.text.qty {
        max-width: 100px;
        width: 25%;
        display: inline-block;
    }
    [data-udraw="uDrawBootstrap"] [data-udraw="designerMenu"] form.cart p {
        display: inline-block;
        margin: 0;
        color: white;
    }
</style>