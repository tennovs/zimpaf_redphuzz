<?php 
    global $woocommerce;
    $udrawSettings = new uDrawSettings();
    $_udraw_settings = $udrawSettings->get_settings();
    
    $uDraw = new uDraw();
    $friendly_item_name = get_the_title($post->ID);
    if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
        $product_type = $product->get_type();
        $product_id = $product->get_id();
    } else {
        $product_type = $product->product_type;
        $product_id = $product->id;
    }
    $allow_structure_file = false;
    if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
?>
<div id="udraw-main-designer-ui" style="height: 96%; position: relative; padding: 25px; overflow: auto;">
    <div class="row menu_bar" data-udraw="designerMenu">
        <?php if ($displayOptionsFirst || $templateCount > 1) { ?>
        <div class="col menu_item">
            <a href="#" class="btn" id="show-udraw-display-options-ui-btn">
                <i class="fas fa-chevron-left desktop_only"></i>
                <i class="fas fa-chevron-left mobile_only fa-2x"></i>
                <span class="desktop_only" style="margin-left: 5px;">Back to Options</span>
            </a>
        </div>
        <?php } ?>
        <div class="col menu_item">
            <a href="#" class="btn" id="reset_layers">
                <i class="fas fa-circle-notch"></i>
                <br />
                <span>Clear Selection</span>
            </a>
        </div>
        <?php if ($allowCustomerDownloadDesign) { ?>
        <div class="col menu_item">
            <a href="#" class="btn" data-udraw="downloadPDFButton">
                <i class="fas fa-cloud-download-alt"></i>
                <br />
                <span data-i18n="[html]button_label.download_pdf"></span>
            </a>
        </div>
        <?php } ?>
        <?php if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) { ?>
        <div class="col menu_item">
            <a href="#" class="btn" id="udraw-save-later-design-btn">
                <i class="far fa-save"></i>
                <br />
                <span data-i18n="[html]common_label.save"></span>
            </a>
        </div>
        <?php } ?>
        <?php if ((!$displayOptionsFirst || $displayOptionsFirst == '') && ($product_type == "variable" || $isPriceMatrix)) { ?>
            <div class="col menu_item">
                <a href="#" class="btn" id="udraw-next-step-1-btn">
                    <span class="desktop_only">Next Step</span>
                    <i class="fas fa-chevron-right desktop_only"></i>
                    <i class="fas fa-chevron-right fa-2x mobile_only"></i>
                </a>
            </div>
        <?php } else { ?>
            <?php if ($displayOptionsFirst == '' || !$displayOptionsFirst) { ?>
                <div class="col menu_item">
                    <form class="cart" method="post" enctype="multipart/form-data">
                        <input type="hidden" value="" name="udraw_product">
                        <input type="hidden" value="" name="udraw_product_data">
                        <input type="hidden" value="" name="udraw_product_svg">
                        <input type="hidden" value="" name="udraw_product_preview">
                        <input type="hidden" value="" name="udraw_product_cart_item_key">
                        <input type="hidden" value="" name="ua_ud_graphic_url" />
                        <div class="quantity_box">
                            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
                            <br>
                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>">
                            <span><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <span><?php echo $product->get_price(); ?></span>
                        </div>
                        <a href="#" class="btn" data-udraw="addToCart">
                            <?php if (isset($_GET['cart_item_key'])) { ?>
                                <span class="desktop_only">Update Cart</span>
                            <?php } else { ?>
                                <span class="desktop_only">Add to Cart</span>
                            <?php } ?>
                            <i class="fas fa-chevron-right desktop_only"></i>
                            <i class="fas fa-chevron-right fa-2x mobile_only"></i>
                        </a>
                    </form>
                </div>
            <?php } else { ?>
                <?php if ($allow_structure_file) { ?>
                    <div class="col menu_item">
                        <a href="#" class="btn" data-udraw="excelContinue">
                            <span style="font-weight: bold; font-size: 18px;" class="desktop_only"><?php _e('Continue', 'udraw') ?></span>
                            <i class="fas fa-chevron-right desktop_only"></i>
                            <i class="fas fa-chevron-right fa-2x mobile_only"></i>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col menu_item">
                        <a href="#" class="btn" data-udraw="addToCart">
                            <?php if (isset($_GET['cart_item_key'])) { ?>
                                <span class="desktop_only">Update Cart</span>
                            <?php } else { ?>
                                <span class="desktop_only">Add to Cart</span>
                            <?php } ?>
                            <i class="fas fa-chevron-right desktop_only"></i>
                            <i class="fas fa-chevron-right fa-2x mobile_only"></i>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    
    <div class="footer row menu_bar">
        <div class="col menu_item">
            <a href="#" class="btn" data-udraw="backgroundColour">
                <i class="fas fa-fill-drip"></i>
                <br />
                <span>Background</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-object_type="text">
                <i class="fas fa-font"></i>
                <br />
                <span>Text</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-object_type="image">
                <i class="far fa-image"></i>
                <br />
                <span>Images</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-object_type="misc">
                <i class="fas fa-ellipsis-h"></i>
                <br />
                <span>Misc</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-udraw="undoButton">
                <i class="fas fa-undo"></i>
                <br />
                <span>Undo</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-udraw="redoButton">
                <i class="fas fa-redo"></i>
                <br />
                <span>Redo</span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" id="linkedTemplates">
                <i class="fas fa-link"></i>
                <br />
                <span>Linked Templates</span>
            </a>
        </div>
    </div>
    <div class="object_tools hidden" data-object_type="text">
        <div class="row">
            <textarea class="form-control" data-udraw="textArea"></textarea>
        </div>
        <div data-udraw="link_row" class="row">
            <textarea class="form-control" data-udraw="link_href_input" placeholder="https://www.google.ca"></textarea>
        </div>
        <div class="row">
            <div class="col-4 col-md-2 menu_item">
                <select data-udraw="fontFamilySelector"></select>
            </div>
            <div class="col-4 col-md-2 menu_item">
                <select data-udraw="fontSizeSelector"></select>
            </div>
            <div class="col-4 col-md-2 menu_item">
                <input type="text" readonly value="#000000" data-opacity="1" data-udraw="designerColourPicker" class="colourwheel" style="background: url('<?php echo UDRAW_DESIGNER_IMG_PATH; ?>colourwheel.png') no-repeat; background-size: contain;">
            </div>
            <div class="col-4 col-md-2 menu_item hidden curved_text">
                <a href="#" class="increase_curve_btn">
                    <span>Increase Curve</span>
                </a>
            </div>
            <div class="col-4 col-md-2 menu_item hidden curved_text">
                <a href="#" class="decrease_curve_btn">
                    <span>Decrease Curve</span>
                </a>
            </div>
            <div class="col-4 col-md-2 menu_item hidden curved_text">
                <a href="#" class="reverse_curve_btn">
                    <span>Reverse Curve</span>
                </a>
            </div>
        </div>
        <div class="row">
            <a href="#" data-udraw="boldButton" class="col menu_item">
                <div>
                    <i class="fas fa-bold"></i>
                </div>
            </a>
            <a href="#" data-udraw="italicButton" class="col menu_item">
                <div>
                    <i class="fas fa-italic"></i>
                </div>
            </a>
            <a href="#" data-udraw="underlineButton" class="col menu_item">
                <div>
                    <i class="fas fa-underline"></i>
                </div>
            </a>
            <a href="#" data-udraw="textAlignLeft" class="col menu_item">
                <div>
                    <i class="fas fa-align-left"></i>
                </div>
            </a>
            <a href="#" data-udraw="textAlignCenter" class="col menu_item">
                <div>
                    <i class="fas fa-align-center"></i>
                </div>
            </a>
            <a href="#" data-udraw="textAlignRight" class="col menu_item">
                <div>
                    <i class="fas fa-align-right"></i>
                </div>
            </a>
            <a href="#" data-udraw="textAlignJustify" class="col menu_item">
                <div>
                    <i class="fas fa-align-justify"></i>
                </div>
            </a>
        </div>
        <div class="row">
            <a href="#" class="col menu_item layer_btn" data-layer_type="forward">
                <div>
                    <i class="fas fa-sort-amount-up"></i>
                </div>
            </a>
            <a href="#" class="col menu_item layer_btn" data-layer_type="backwards">
                <div>
                    <i class="fas fa-sort-amount-down"></i>
                </div>
            </a>
            <a href="#" class="col menu_item duplicate_btn">
                <div>
                    <i class="far fa-copy"></i>
                </div>
            </a>
            <a href="#" data-udraw="removeButton" class="col menu_item">
                <div>
                    <i class="far fa-trash-alt"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="object_tools hidden" data-object_type="image">
        <div class="row filter_row">
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="grayscale"><span data-i18n="[html]button_label.grayscale"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="sepiaPurple"><span data-i18n="[html]button_label.purple-sepia"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="sepiaYellow"><span data-i18n="[html]button_label.yellow-sepia"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="sharpen"><span data-i18n="[html]button_label.sharpen"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="emboss"><span data-i18n="[html]button_label.emboss"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="blur"><span data-i18n="[html]button_label.blur"></span></a>
            <a href="#" class="btn btn-light image-filter-btn" data-udraw="invert"><span data-i18n="[html]button_label.invert"></span></a>
        </div>
        <div class="row">
            <a href="#" class="col menu_item layer_btn" data-layer_type="forward">
                <div>
                    <i class="fas fa-sort-amount-up"></i>
                </div>
            </a>
            <a href="#" class="col menu_item layer_btn" data-layer_type="backwards">
                <div>
                    <i class="fas fa-sort-amount-down"></i>
                </div>
            </a>
            <a href="#" class="col menu_item" data-udraw="cropButton">
                <div>
                    <i class="fas fa-crop"></i>
                </div>
            </a>
            <?php if (!$_udraw_settings['designer_disable_image_replace']) { ?>
		<a href="#" class="col menu_item replace_image">
                    <div>
                        <i class="fas fa-retweet"></i>
                    </div>
                </a>
            <?php } ?>
            <a href="#" class="col menu_item duplicate_btn">
                <div>
                    <i class="far fa-copy"></i>
                </div>
            </a>
            <a href="#" data-udraw="removeButton" class="col menu_item">
                <div>
                    <i class="far fa-trash-alt"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="object_tools hidden" data-object_type="shape">
        <div class="row">
            <div class="col menu_item">
                <input type="text" readonly value="#000000" data-opacity="1" data-udraw="designerColourPicker" class="colourwheel" style="background: url('<?php echo UDRAW_DESIGNER_IMG_PATH; ?>colourwheel.png') no-repeat; background-size: contain;">
            </div>
        </div>
        <div class="row">
            <a href="#" class="col menu_item layer_btn" data-layer_type="forward">
                <div>
                    <i class="fas fa-sort-amount-up"></i>
                </div>
            </a>
            <a href="#" class="col menu_item layer_btn" data-layer_type="backwards">
                <div>
                    <i class="fas fa-sort-amount-down"></i>
                </div>
            </a>
            <a href="#" class="col menu_item duplicate_btn">
                <div>
                    <i class="far fa-copy"></i>
                </div>
            </a>
            <a href="#" data-udraw="removeButton" class="col menu_item">
                <div>
                    <i class="far fa-trash-alt"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="zoom_container">
            <i class="fas fa-search-minus right_space"></i>
            <input type="range" data-udraw="zoomLevel" min="0.1" max="2.5" step="0.1" />
            <i class="fas fa-search-plus left_space"></i>
            <br />
            <span data-udraw="zoomDisplay"></span>
        </div>
    </div>
    
    <div class="canvas_container" data-udraw="canvasContainer">
        <div data-udraw="canvasWrapper">
            <canvas id="racad-designer-canvas" width="504" height="288" data-udraw="canvas"></canvas>
        </div>
    </div>
    <div data-udraw="pagesContainer">
        <div data-udraw="pagesList"></div>
    </div>

    <div class="modal" data-udraw="userUploadedModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span data-i18n="[html]header_label.image-header"></span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fas fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <a class="btn btn-light" onclick="javascript: jQuery('[data-udraw=\'uploadImage\']').trigger('click');">
                        <i class="fas fa-cloud-upload-alt icon"></i>
                        <span data-i18n="[html]common_label.upload-image"></span>
                    </a>
                    <div data-udraw="localImageList"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal overlay-modal" role="dialog" aria-labelledby="stock_image_modal" aria-hidden="true" data-udraw="stock_image_modal">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Library</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="search_container input-group">
                        <input type="text" class="form-control" data-i18n="[placeholder]search" data-udraw="stock_image_search_input" />

                        <select data-udraw="stock_image_type" class="form-control">
                            <option>Select Source</option>
                            <option value="pixabay">Pixabay</option>
                            <option value="pexel">Pexel</option>
                            <option value="unsplash">Unsplash</option>
                            <option value="udraw_clipart">uDraw Clipart</option>
                            <option value="private">Site</option>
                        </select>
                    </div>
                    <div class="stock_image_container" data-stock_image="clipart">
                        <ul class="stock_image_list" data-stock_image="clipart"></ul>
                    </div>
                    <div class="stock_image_container" data-stock_image="pixabay">
                        <ul class="stock_image_list" data-stock_image="pixabay"></ul>
                    </div>
                    <div class="stock_image_container" data-stock_image="pexel">
                        <ul class="stock_image_list" data-stock_image="pexel"></ul>
                    </div>
                    <div class="stock_image_container" data-stock_image="unsplash">
                        <ul class="stock_image_list" data-stock_image="unsplash"></ul>
                    </div>
                    <div class="stock_image_container row" data-stock_image="udraw_clipart">
                        <div class="col-6" data-udraw="uDrawClipartFolderContainer"></div>
                        <div class="col-6 stock_image_list" data-udraw="uDrawClipartList"></div>
                    </div>
                    <div class="stock_image_container row" data-stock_image="private">
                        <ul class="stock_image_list col-6" data-stock_image="private_category"></ul>
                        <ul class="stock_image_list col-6" data-stock_image="private"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-udraw="social_media_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span data-i18n="[html]menu_label.social_media"></span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <select data-udraw="social_media_type">
                        <option>Select Source</option>
                        <?php if ($_udraw_settings['designer_enable_facebook_photos']) { ?>
                            <option value="facebook">Facebook</option>
                        <?php } ?>
                        <?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
                            <option value="instagram">Instagram</option>
                        <?php } ?>
                    </select>

                    <div class="social_media_container" data-social_media="facebook">
                        <div class="fb-login-button"
                             data-size="small" data-button-type="login_with"
                             data-auto-logout-link="true" data-use-continue-as="false"
                             onLogin="RacadDesigner.Facebook.get_login_status(function () { RacadDesigner.Facebook.get_albums(); });"
                             style="float:right;">

                        </div>
                        <div>
                            <ul data-udraw="facebook_albums_list"></ul>
                            <ul data-udraw="facebook_photos_list"></ul>
                        </div>
                    </div>
                    <div class="social_media_container" data-social_media="instagram">
                        <div style="display: block; float:right;">
                            <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogin">
                                <i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                                <span>Login / Authenticate</span>
                            </a>
                            <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogout" style="display: none;">
                                <i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                        <div data-udraw="instagramContent"></div>
                    </div>
                    <div class="social_media_container" data-social_media="flickr">
                        <div style="float: right;">
                            <a href="#" class="btn btn-default" data-udraw="flickrLogin">
                                <i class="fab fa-flickr" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                                <span>Login / Authenticate</span>
                            </a>
                            <a href="#" class="btn btn-default" data-udraw="flickrLogout" style="display: none;">
                                <i class="fab fa-flickr" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                                <span>Logout / Deauthenticate</span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div data-udraw="flickrPhotosets" style="margin: auto; height: 45%;"></div>
                            <div data-udraw="flickrPhotosContainer" style="margin: auto; height: 45%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-udraw="qrModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <input type="text" class="form-control" tabindex="1" id="qrcode-value-txtbox" value="http://www.example.com" data-udraw="qrInput" />
                        </div>
                    </div>
                    <div data-udraw="qrPreviewContainer" class="row"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.cancel"></span></a>
                    <a href="#" class="btn btn-success" tabindex="3" data-udraw="qrAddButton"><span data-i18n="[html]common_label.add"></span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal enter_text">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span>Add Text</span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <div>
                        <ul class="objects_menu">
                            <li role="presentation" data-udraw="addText">
                                <i class="fas fa-font"></i>
                                <span data-i18n="[html]common_label.text"></span>
                            </li>
                            <li role="presentation" data-udraw="addCurvedText">
                                <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>arc-text-black.png" />
                                <span data-i18n="[html]menu_label.curvetext"></span>
                            </li>
                            <li role="presentation" data-udraw="addTextbox">
                                <i class="fas fa-i-cursor"></i>
                                <span data-i18n="[html]menu_label.textbox"></span>
                            </li>
                            <li role="presentation" data-udraw="addLink">
                                <i class="fas fa-link"></i>
                                <span data-i18n="[html]menu_label.link"></span>
                            </li>
                            <li role="presentation" data-udraw="textTemplates">
                                <i class="far fa-file-word"></i>
                                <span data-i18n="[html]menu_label.templates"></span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="text-align: center;">Enter Your Text</h3>
                        <ul class="text_objects_list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal add_image">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span data-i18n="[html]common_label.image"></span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <input class="hidden" type="file" name="files[]" multiple data-udraw="uploadImage" />
                    <ul class="objects_menu">
                        <li role="presentation" class="trigger_upload_image">
                            <i class="fas fa-cloud-upload-alt icon"></i>
                            <span data-i18n="[html]common_label.upload-image"></span>
                        </li>
                        <li role="presentation" data-udraw="userUploadedImages">
                            <i class="fas fa-desktop icon"></i>
                            <span data-i18n="[html]common_label.local-storage"></span>
                        </li>
                        <li role="presentation" data-udraw="image_library">
                            <i class="far fa-images icon"></i>
                            <span data-i18n="[html]menu_label.image_library"></span>
                        </li>
                        <?php if ($_udraw_settings['designer_enable_facebook_photos'] || $_udraw_settings['designer_enable_instagram_photos']) { ?> 
                            <li role="presentation" data-udraw="social_media">
                                <span>
                                    <i class="fab fa-facebook-square icon fb"></i>
                                    <i class="fab fa-instagram icon"></i>
                                    <i class="fab fa-flickr icon"></i>
                                </span>
                                <span data-i18n="[html]menu_label.social_media"></span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal add_misc">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span>Misc</span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <ul class="objects_menu">
                        <li role="presentation" data-udraw="addCircle">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>circle-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.circle-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="addRectangle">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>square-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.rect-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="addTriangle">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>triangle-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.triangle-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="addLine">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>line-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.line-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="addCurvedLine">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>curved-line-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.curved-line-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="addStar">
                            <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>star-icon.png" class="icon" />
                            <span data-i18n="[html]menu_label.star-shape"></span>
                        </li>
                        <li role="presentation" data-udraw="qrCode">
                            <i class="fas fa-qrcode icon"></i>
                            <span data-i18n="[html]common_label.QRcode"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Linked-templates dialog-->
    <div class="modal" id="linked-templates-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span>Select Available Templates</span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <div data-udraw="linkedTemplatesContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-udraw="progressModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:larger;"><span data-i18n="[html]common_label.progress"></span></strong>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" role="progressbar" aria-valuenow="105" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Multipage PDF upload dialog -->
    <div class="modal overlay-modal" data-udraw="multipagePDFModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span data-i18n="[html]header_label.import-images"></span></strong>
                </div>
                <div class="modal-body" style="overflow:auto;">
                    <div data-udraw="page_list_container">
                        <div data-udraw="page_list"></div>
                    </div>
                    <div data-udraw="imported_images_container">
                        <div data-udraw="imported_images_list"></div>
                    </div>
                    <div class="progress_div">
                        <span data-i18n="[html]common_label.progress" style="font-size: 5em; color: #aaa;"></span>
                        <i class="fas fa-spinner fa-pulse fa-5x"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-success" data-udraw="multipage_import_apply"><span data-i18n="[html]common_label.apply"></span></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Crop Dialog -->
    <div class="modal" data-udraw="cropModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div data-udraw="crop_preview" style="padding-top:35px;">
                        <img src="#" data-udraw="image_crop"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" data-dismiss="modal" data-udraw="crop_cancel"><span data-i18n="[html]button_label.cancel_crop"></span></a>
                    <a href="#" class="btn btn-success" tabindex="3" data-udraw="crop_apply"><span data-i18n="[html]common_label.apply"></span></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Load XML modal -->
    <div class="modal overlay-modal" data-udraw="load_xml_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <p data-i18n="[html]text.load_saved_xml"></p>
                    <button type="button" class="button" data-udraw="load_saved_xml" data-i18n="[html]common_label.yes"></button>
                    <button type="button" class="button" data-dismiss="modal" data-i18n="[html]common_label.no" data-udraw="not_load_saved_xml"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal overlay-modal" data-udraw="textTemplatesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span>Text Templates</span></strong>
                </div>
                <div class="modal-body" style="overflow:auto;">
                    <div>
                        <input type="text" data-udraw="textTemplateSearch" placeholder="Enter a keyword"/>
                    </div>
                    <div>
                        <ul data-udraw="textTemplatesList"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
