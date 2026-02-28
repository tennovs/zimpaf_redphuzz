<?php
    include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');
    global $post, $wpdb, $woocommerce;
    $uDraw = new uDraw();
    $udrawSettings = new uDrawSettings();
    $_udraw_settings = $udrawSettings->get_settings();
    $isUpdate = (isset($_GET['cart_item_key'])) ? 'true' : 'false';
    
    if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
        $product_type = $product->get_type();
        $product_id = $product->get_id();
    } else {
        $product_type = $product->product_type;
        $product_id = $product->id;
    }

    $loggedInUser = '';
    if (is_user_logged_in()) {
        $loggedInUser = wp_get_current_user()->user_login;
    }

    $allow_structure_file = false;
    if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
    
    $designTemplateId = $uDraw->get_udraw_template_ids($post->ID);
    $is_design_product = count($designTemplateId) > 0 ? true : false ;
    
    $allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
    $allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
    
    $text_templates_table = $wpdb->prefix . 'udraw_text_templates';
    $text_templates = $wpdb->get_results("SELECT ID,json,preview,tags FROM $text_templates_table", ARRAY_A);
?>

<div id="designer-wrapper">
    <div id="udraw-bootstrap" data-udraw="uDrawBootstrap">
        <div class="loading_overlay active">
            <i class="fas fa-pulse fa-spinner fa-4x"></i>
        </div>
        <?php
            //Apply extra action here
            do_action('udraw_frontend_extra_items', $post);
        ?>
        <div id="udraw-main-designer-ui">
            <div data-udraw="designerMenu">
                <div class="left">
                    <div class="menu_button_div"><span style="font-weight: bold;"><?php echo get_bloginfo('name'); ?></span></div>
                    <div class="divider"></div>
                    <div class="menu_button_div"><a href="#" class="menu_button" data-udraw="undoButton" data-i18n="[title]tooltip.undo"><span class="menu_button_text" data-i18n="[html]button_label.undo"></span></a></div>
                    <div class="menu_button_div"><a href="#" class="menu_button" data-udraw="redoButton" data-i18n="[title]tooltip.redo"><span class="menu_button_text" data-i18n="[html]button_label.redo"></span></a></div>
                    <div class="divider"></div>
                    <div class="menu_button_div">
                        <input type="checkbox" data-udraw="hide_tooltips" style="width: auto;" />
                        <span style="margin-left: 5px;"><?php _e('Hide tooltips','udraw'); ?></span>
                    </div>
                    <div class="divider"></div>
                    <div class="menu_button_div">
                        <label data-udraw="toggleGridLines">
                            <input type="checkbox" data-udraw="gridCheckbox" />
                            <span style="color: #fff" data-i18n="[html]menu_label.toggle_grid"></span>
                        </label>
                        <label data-udraw="snapToGrid">
                            <input type="checkbox" data-udraw="snapCheckbox" />
                            <span style="color: #fff" data-i18n="[html]menu_label.snap_to_grid"></span>
                        </label>
                    </div>

                    <?php if ($allow_structure_file) { ?>
                        <div class="menu_button_div" style="float: right; margin-right: 5px;">
                            <div class="divider"></div>
                            <a href="#" data-udraw="excelContinue" class="menu_button">
                                <span class="menu_button_text"><?php _e('Continue','udraw'); ?></span>
                                <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                    
                <div class="right">
                    <?php if ($allowCustomerDownloadDesign) { ?>
                        <a href="#" class="menu_button" data-udraw="downloadPDFButton">
                            <span>Download PDF</span>&nbsp;
                            <i class="fas fa-cloud-download-alt"></i>
                        </a>
                    <?php } ?>
                    <?php if ($isPriceMatrix) { ?>
                        <div class="menu_button_div" style="margin-right: 5px;">
                            <div class="divider"></div>
                            <a href="#" data-udraw="cart_btn" class="menu_button" disabled>
                                <span class="menu_button_text"><?php _e('Continue','udraw'); ?></span>
                                <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    <?php } else { ?>
                        <form class="cart" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="" name="udraw_product">
                            <input type="hidden" value="" name="udraw_product_data">
                            <input type="hidden" value="" name="udraw_product_svg">
                            <input type="hidden" value="" name="udraw_product_preview">
                            <input type="hidden" value="" name="udraw_product_cart_item_key">
                            <input type="hidden" value="" name="ua_ud_graphic_url" />

                            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" style="width: 60px; display: inline; height: 33px;">
                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"> 
                            <span style="font-size:1.5em; vertical-align:middle;"><?php echo get_woocommerce_currency_symbol(); ?></span>
                            <span id="product_total_price" style="font-size:1.5em; vertical-align:middle;"><?php echo $product->get_price(); ?></span>
                            <a href="#" id="simple-add-to-cart-btn" data-udraw="cart_btn" class="btn btn-success designer-menu-btn" style="margin-top: -1px;">
                                <i class="fas fa-shopping-cart"></i>
                                <i class="fas fa-spinner fa-pulse" style="display: none;"></i>
                                <span>&nbsp;<?php echo $product->single_add_to_cart_text(); ?></span>
                            </a>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <div class="under_menu_bar">
                <div data-udraw="designerSideBar">
                    <div class="btn-group button_holder" data-tools_container="layouts">
                        <button type="button" class="btn btn-sm sidebar_button">
                            <i class="fas fa-th-large fa-2x"></i>
                            <br>
                            <?php if ($isPriceMatrix) { ?>
                                <span data-i18n="[html]button_label.price-layouts"></span>
                            <?php } else { ?>
                                <span data-i18n="[html]header_label.layouts"></span>
                            <?php } ?>
                        </button>
                    </div>
                    <div class="designer_tools">
                        <!-- Canvas Elements (Clipart and QR code) -->
                        <div class="btn-group button_holder" data-tools_container="canvas_elements">
                            <button type="button" class="btn btn-sm sidebar_button" data-i18n="[title]tooltip.insert-element" >
                                <i class="far fa-object-group fa-2x"></i><br><span><?php _e('Elements','udraw'); ?></span>
                            </button>
                        </div>
                        <div class="btn-group button_holder" data-tools_container="text">
                            <button type="button" class="btn btn-sm sidebar_button" data-i18n="[title]tooltip.create-text">
                                <i class="fas fa-font fa-2x"></i><br><span data-i18n="[html]common_label.text"></span>
                            </button>
                        </div>
                        <!-- Background Colours -->
                        <div class="btn-group button_holder" data-tools_container="background_colours">
                            <button type="button" class="btn btn-sm sidebar_button" data-i18n="[title]tooltip.background-colour" style="word-break: break-all;">
                                <span class="fa-stack" style="margin-left: -12%; margin-bottom: 25%;">
                                    <i class="far fa-square fa-stack-2x"></i>
                                    <i class="fas fa-square fa-stack-2x" style="margin-left: 25%;margin-top: 25%;"></i>
                                </span>
                                <br><span style="font-size: 9px;"><?php _e('Background','udraw'); ?></span>
                            </button>
                        </div>
                        <!-- Images -->
                        <div class="btn-group button_holder" data-tools_container="image">
                            <button type="button" class="btn btn-sm sidebar_button" data-i18n="[title]tooltip.upload-photo">
                                <i class="fas fa-arrow-up fa-2x"></i><br><span data-i18n="[html]button_label.uploads"></span>
                            </button>
                        </div>
                        <!-- Layers -->
                        <div class="btn-group button_holder" data-tools_container="layers">
                            <button type="button" class="btn btn-sm sidebar_button" data-i18n="[title]tooltip.display-layers">
                                <i class="fas fa-layer-group fa-2x"></i><br><span data-i18n="[html]button_label.layers"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Side Tool Bar-->
                <div data-udraw="tools_container">
                    <div class="inner_container tools">
                        <div class="tools_container layouts">
                            <?php if ($isPriceMatrix) { ?>
                                <div data-udraw="price_matrix_container"></div>
                                <?php if ($allowUploadArtwork === "yes") { ?>
                                    <?php if ($allowDoubleUploadArtwork === "yes") { ?>
                                        <a class="UploadFront">Upload Artwork</a>
                                    <?php } ?>
                                    <a href="#" id="udraw-options-page-upload-btn-a" class="button" onclick="javascript: return false;">
                                        <?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Upload Artwork"; } ?>
                                    </a>
                                    <input style="display: none; visibility: hidden; width: 0; height: 0;" id="fileuploadA" 
                                           type="file" name="files[]" accept="<?php echo $valid_extensions ?>" multiple>
                                    <?php if ($allowDoubleUploadArtwork === "yes") { ?>
                                        <a class="UploadBack">Upload Back Side</a>
                                        <a href="#" id="udraw-options-page-upload-btn-b" class="button" onclick="javascript: return false;">
                                            <?php if ($is_upload_product_update) { echo "Replace File"; } else { echo "Select File(s)"; } ?>
                                        </a>
                                        <input style="display: none; visibility: hidden; width: 0; height: 0;" id="fileuploadB" 
                                               type="file" name="files[]" accept="<?php echo $valid_extensions ?>" multiple>
                                    <?php } ?>
                                    <div id="udraw-options-file-upload-progress" style="display:none;">
                                        <div class="udraw-progress-bar udraw-progress-bar-animate">
                                            <span style="width: 0%"><span></span></span>
                                        </div>
                                        <div class="udraw-uploaded-files-list"></div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <div data-udraw="linked_templates_container">
                                <h4 data-i18n="[html]header_label.layouts" style="text-align: center;"></h4>
                                <div data-udraw="linkedTemplatesContainer" style="text-align: center;"></div>
                                <div style="text-align: center;">
                                    <i class="fas fa-pulse fa-spinner fa-3x" data-udraw="linked_templates_spinner"></i>
                                </div>
                            </div>
                        </div>
                        <div class="tools_container text">
                            <h2 style="text-align: center;"><?php _e('Click to add: ', 'udraw'); ?></h2>
                            <ul class="tools_list">
                                <li>
                                    <a href="#" data-udraw="addText" data-i18n="[title]tooltip.add-text"><span data-i18n="[html]common_label.text"></span></a>
                                </li>
                                <li>
                                    <!--Curved text-->
                                    <a href="#" data-udraw="addCurvedText" data-i18n="[title]tooltip.add-curved-text"><span data-i18n="[html]menu_label.curvetext"></span></a>
                                </li>
                                <li>
                                    <!--Textbox-->
                                    <a href="#" data-udraw="addTextbox" data-i18n="[title]tooltip.add-textbox"><span data-i18n="[html]menu_label.textbox"></span></a>
                                </li>
                                <li>
                                    <!--Text with Link-->
                                    <a href="#" data-udraw="addLink" ><span data-i18n="[html]menu_label.link"></span></a>
                                </li>
                            </ul>
                            <?php if (count($text_templates)) { ?>
                                <hr />
                                <div>
                                    <input type="text" data-udraw="textTemplateSearch" placeholder="Enter a keyword"/>
                                </div>
                                <div class="text-templates">
                                    <ul data-udraw="textTemplatesList"></ul>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tools_container image">
                            <div style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px;">
                                <div class="image_btn_container">
                                    <a href="#" class="image_btn" data-image_container="user_uploads" data-udraw="userUploadedImages">
                                        <span data-i18n="[html]common_label.local-storage"></span>
                                    </a>
                                </div>
                                <?php if ($_udraw_settings['designer_enable_facebook_photos']) { ?>
                                    <div class="image_btn_container">
                                        <a href="#" class="image_btn" data-image_container="facebook_uploads">
                                            <span data-i18n="[html]menu_label.facebook-uploads"></span>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
                                    <div class="image_btn_container">
                                        <a href="#" class="image_btn" data-image_container="instagram_uploads">
                                            <span data-i18n="[html]menu_label.instagram-uploads"></span>
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="pointer"></div>
                            </div>
                            <div class="image_container user_uploads">
                                <ol class="breadcrumb" data-udraw="localFoldersList"></ol>
                                <button type="button" class="button" data-udraw="upload_image_button" style="width: 90%; margin-left: 5%; margin-right: 5%;">
                                    <i class="fas fa-cloud-upload-alt" style="margin-right: 5px;"></i>
                                    <span data-i18n="[html]text.upload-photo"></span>
                                </button>
                                <button type="button" class="button" data-udraw="replaceImage" style="width: 90%; margin-left: 5%; margin-right: 5%; position: inherit;">
                                    <i class="fas fa-retweet" style="margin-right: 5px;"></i>
                                    <span data-i18n="[html]tooltip.replace-image"></span>
                                </button>
                                <p>
                                    Accepted File Types: 
                                    <?php
                                    $file_list = ['pdf', 'eps', 'jpg', 'jpeg', 'png', 'gif', 'svg'];
                                    $accepted_file_list = apply_filters('udraw_designer_accepted_image_file_types', $file_list);
                                    echo join(', ', $accepted_file_list);
                                    ?>
                                </p>
                                <div data-udraw="localImageList"></div>
                            </div>
                            <div class="image_container facebook_uploads">
                                <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" 
                                    data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" 
                                    data-use-continue-as="false" onLogin="RacadDesigner.Facebook.get_login_status(function () { RacadDesigner.Facebook.get_albums(); });"></div>
                                <div data-udraw="facebookPaging"></div>
                                <div class="facebook_content">
                                    <ul data-udraw="facebook_albums_list"></ul>
                                    <ul data-udraw="facebook_photos_list"></ul>
                                </div>
                            </div>
                            <div class="image_container instagram_uploads">
                                <div>
                                    <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogin"><i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>Login / Authenticate</a>
                                    <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogout" style="display: none;"><i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>Logout</a>
                                </div>
                                <div data-udraw="instagramContent" style="margin: auto;"></div>
                            </div>
                        </div>
                        <div class="tools_container canvas_elements">
                            <div style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px;">
                                <div class="element_btn_container">
                                    <a href="#" class="element_btn" data-element_container="clipart">
                                        <span data-i18n="[html]menu_label.stock_image"></span>
                                    </a>
                                </div>
                                <?php if (!$_udraw_settings['designer_disable_qrqode']) { ?>
                                    <div class="element_btn_container">
                                        <a href="#" class="element_btn" data-udraw="qrCode" data-element_container="qrcode">
                                            <span data-i18n="[html]common_label.QRcode"></span>
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="pointer"></div>
                            </div>
                            <div class="element_container clipart" data-udraw="stock_image_modal">
                                <div class="search_container">
                                    <input type="text" data-i18n="[placeholder]search" data-udraw="stock_image_search_input" />
                                    <select data-udraw="stock_image_type">
                                        <option>Select Source</option>
<!--                                        <option value="clipart">Clipart</option>-->
                                        <option value="pixabay">Pixabay</option>
                                        <option value="pexel">Pexel</option>
                                        <option value="unsplash">Unsplash</option>
                                        <?php if (!$_udraw_settings['designer_enable_local_clipart']) { ?>
                                            <option value="private">Site</option>
                                        <?php } ?>
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
                                <div class="stock_image_container" data-stock_image="private">
                                    <ul class="stock_image_list" data-stock_image="private_category"></ul>
                                    <ul class="stock_image_list" data-stock_image="private"></ul>
                                </div>
                            </div>
                            <div class="element_container qrcode">
                                <input type="text" class="form-control" tabindex="1" value="http://somedomain" data-udraw="qrInput" />
                                <input type="hidden" value="#000000" data-udraw="qrColourPicker" />
                                <a href="#" class="btn btn-success btn-sm" data-udraw="qrRefreshButton" style="float: right;"><i class="fas fa-sync"></i>&nbsp;<span data-i18n="[html]common_label.refresh"></span></a>
                                <br />
                                <div style="padding-top:25px;" data-udraw="qrPreviewContainer">
                                </div>
                                <a href="#" class="btn btn-success" tabindex="3" data-udraw="qrAddButton" style="float: right;"><span data-i18n="[html]common_label.add"></span></a>
                            </div>
                        </div>
                        <div class="tools_container background_colours">
                            <div class="colour_container">
                                <div class="btn-group btn-group-sm" data-udraw="backgroundColourContainer">
                                    <input type="text" readonly value="#ffffff" data-opacity="1" class="standard-js-colour-picker text-colour-picker" data-udraw="background_colour">
                                </div>
                                <h4>Custom colours</h4>
                                <ul class="colour_list custom_colours background"></ul>
                            </div>
                            <hr>
                            <div class="colour_container">
                                <h4>Default colours</h4>
                                <ul class="colour_list background">
                                    <li data-colour="#ff5c5c" style="background-color: #ff5c5c;"></li>
                                    <li data-colour="#ffbd4a" style="background-color: #ffbd4a;"></li>
                                    <li data-colour="#fff952" style="background-color: #fff952;"></li>
                                    <li data-colour="#99e265" style="background-color: #99e265;"></li>
                                    <li data-colour="#35b729" style="background-color: #35b729;"></li>
                                    <li data-colour="#44d9e6" style="background-color: #44d9e6;"></li>
                                    <li data-colour="#2eb2ff" style="background-color: #2eb2ff;"></li>
                                    <li data-colour="#5271ff" style="background-color: #5271ff;"></li>
                                    <li data-colour="#b760e6" style="background-color: #b760e6;"></li>
                                    <li data-colour="#ff63b1" style="background-color: #ff63b1;"></li>
                                </ul>
                                <ul class="colour_list background">
                                    <li data-colour="#000000" style="background-color: #000000;"></li>
                                    <li data-colour="#666666" style="background-color: #666666;"></li>
                                    <li data-colour="#a8a8a8" style="background-color: #a8a8a8;"></li>
                                    <li data-colour="#d9d9d9" style="background-color: #d9d9d9;"></li>
                                    <li data-colour="#ffffff" style="background-color: #ffffff;"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="tools_container layers">
                            <div  class="layers-container" style="display: inline-block; overflow: auto;width:100%;">
                            <h4 data-i18n="[html]common_label.layers">Layers</h4>
                            <div data-udraw="multilayerImageModal" style="display: none;">
                                <div class="panel-body designer-panel-body" style="height: 120px; overflow-y: auto;">
                                    <ul id="multi-layer-selection-panel" style="padding-left: 0px;" data-udraw="multilayerImageContainer"></ul>
                                </div>
                            </div>
                            <div data-udraw="imageFilterModal" class="layers-inner-container" style="display:none">
                                <h4 data-i18n="[html]header_label.image-filter-header" style="display: inline-block;"></h4>
                                <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxClose"><i class="fa fa-close"></i><span data-i18n="[html]common_label.close"></span></a>
                                <div id="designer-advanced-image-properties" style="display:block; font-size: 12px;">
                                    <a href="#" id="designer-advanced-image-filter-grayscale" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="grayscale"><span data-i18n="[html]button_label.grayscale"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-sepia-purple" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="sepiaPurple"><span data-i18n="[html]button_label.purple-sepia"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-sepia" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="sepiaYellow"><span data-i18n="[html]button_label.yellow-sepia"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-sharpen" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="sharpen"><span data-i18n="[html]button_label.sharpen"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-emboss" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="emboss"><span data-i18n="[html]button_label.emboss"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-blur" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="blur"><span data-i18n="[html]button_label.blur"></span></a>
                                    <a href="#" id="designer-advanced-image-filter-invert" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="invert"><span data-i18n="[html]button_label.invert"></span></a>
                                    <!--<a href="#" id="designer-advanced-image-filter-remove-white" class="btn btn-default designer-toolbar-btn" style="display: inline-block; width: 30%; margin-bottom: 5px; padding-left: 5px; padding-right: 5px;">Remove White</a>-->
                                    <a href="#" id="designer-advanced-image-clip-image" class="btn btn-default designer-toolbar-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="clipImage"><span data-i18n="[html]button_label.clip-image"></span></a>

                                    <div id="image-tint-container">
                                        <a href="#" id="designer-advanced-image-filter-tint" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="tint"><span data-i18n="[html]button_label.tint"></span></a>
                                        <label style="width: 30%; margin-bottom: 5px;"><span data-i18n="[html]text_label.tint-colour"></span><input type="hidden" id="image-tint-color-picker" data-opacity="1" data-udraw="tintColourPicker" /></label>
                                    </div>
                                    <div id="image-brightness-container">
                                        <a href="#" id="designer-advanced-image-filter-brightness" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="brightness"><span data-i18n="[html]button_label.brightness"></span></a>
                                        <label style="width: 30%;"><span data-i18n="[html]text_label.brightness-level"></span></label>
                                        <div class="slider-class" id="image-brightness-slider" style="width: 30%" data-udraw="imageBrightnessLevel"></div>
                                    </div>
                                    <div id="image-noise-container">
                                        <a href="#" id="designer-advanced-image-filter-noise" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="noise"><span data-i18n="[html]button_label.noise"></span></a>
                                        <label style="width: 30%;"><span data-i18n="[html]text_label.noise-level"></span></label>
                                        <div class="slider-class" id="image-noise-slider" style="width: 30%" data-udraw="imageNoiseLevel"></div>
                                    </div>
                                    <div id="image-pixel-container">
                                        <a href="#" id="designer-advanced-image-filter-pixelate" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="pixelate"><span data-i18n="[html]button_label.pixelate"></span></a>
                                        <label style="width: 30%;"><span data-i18n="[html]text_label.pixel-size"></span></label>
                                        <div class="slider-class" id="image-pixel-slider" style="width: 30%" data-udraw="imagePixelateLevel"></div>
                                    </div>
                                    <div id="image-gradient-transparency-container" style="display: none">
                                        <a href="#" id="designer-advanced-image-filter-gradient-transparency" class="btn btn-default designer-toolbar-btn image-filter-btn" style="display: inline-block; width: 30%; margin-bottom: 5px; white-space: normal;" data-udraw="gradientTransparency"><span data-i18n="[html]button_label.gradient-transparency"></span></a>
                                        <label style="width: 30%;"><span data-i18n="[html]text_label.transparency-level"></span></label>
                                        <div class="slider-class" id="image-gradient-transparency-slider" style="width: 30%" data-udraw="imageGradientTransparencyLevel"></div>
                                    </div>
                                    <div id="image-opacity-container">
                                        <a id="designer-advanced-image-filter-opacity" class="btn btn-default designer-toolbar-btn" style="display: inline-block; width: 30%; margin-bottom: 5px; white-space: normal; opacity: 0; cursor: default;" data-udraw="opacity"></a>
                                        <label style="width: 30%;"><span data-i18n="[html]text.opacity-level"></span></label>
                                        <div class="slider-class" id="image-opacity-slider" style="width: 30%" data-udraw="opacityLevel"></div>
                                    </div>
                                </div>
                            </div>
                            <div data-udraw="objectColouringModal" class="layers-inner-container" style="display: none;">
                                <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxClose"><i class="fa fa-close"></i><span data-i18n="[html]common_label.close"></span></a>
                                <div>
                                    <h4 data-i18n="[html]header_label.advanced-colouring-header" style="display: inline-block;"></h4>
                                    <a href="#" class="btn btn-default" id="trigger-object-pattern-upload-btn" style="margin: 5px;" data-udraw="triggerObjectColouringUpload">
                                        <i class="fa fa-upload icon"></i>&nbsp; <span data-i18n="[html]button_label.upload-pattern"></span>
                                    </a>
                                    <input id="object-pattern-upload-btn" type="file" name="files[]" multiple style="display: none;" data-udraw="objectColouringUpload" />
                                    <div class="panel-body designer-panel-body" id="advanced-colouring-panel">
                                        <span data-i18n="[html]header_label.advanced-colouring-fill-header"></span>
                                        <div id="advanced-colouring-fill-box" style="margin: 5px;" data-udraw="objectColouringFillContainer">

                                        </div>
                                        <span data-i18n="[html]header_label.advanced-colouring-stroke-header"></span>
                                        <div id="advanced-colouring-stroke-box" style="margin: 5px;" data-udraw="objectColouringStrokeContainer">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-default btn-xs" id="refresg-designer-layers-box" style="padding-top:0px;" data-udraw="layersRefresh"><i class="fa fa-refresh"></i><span data-i18n="[html]common_label.refresh"></span></a>
                                <div class="scroll-content panel-body designer-panel-body" id="layers-box-body" style="padding: 5px; height:inherit; min-height:10px; max-height:250px;">
                                    <ul class="layer-box" id="layersContainer" data-udraw="layersContainer"></ul>
                                </div>
                            </div>
                            </div>
                        </div> 
                    </div>
                    <div class="text-right inner_container hide_tools_container">
                        <div class="tab">
                            <a href="#" class="hide_tools">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-udraw="body_block">
                    <div data-udraw="top_toolbar_container">
                        <div data-udraw="top_toolbar">
                            <div class="text_tools">
                                <div class="btn-group" data-udraw="fontFamilyContainer">
                                    <select class="font-family-selection" name="font-family-selection" data-udraw="fontFamilySelector">
                                        <option value="Arial" style="font-family:'Arial';">Arial</option>
                                        <option value="Calibri" style="font-family:'Calibri';">Calibri</option>
                                        <option value="Times New Roman" style="font-family:'Times New Roman'">Times New Roman</option>
                                        <option value="Comic Sans MS" style="font-family:'Comic Sans MS';">Comic Sans MS</option>
                                        <option value="French Script MT" style="font-family:'French Script MT';">French Script MT</option>
                                    </select>
                                </div>
                                <div class="btn-group" data-udraw="fontSizeContainer">
                                    <span class="fa-stack" style="margin-right: 5px;">
                                        <i class="fas fa-font fa-stack-2x" style="color: #aaa;"></i>
                                        <i class="fas fa-font fa-stack-1x" style="margin-left: 35%;margin-top: 25%;"></i>
                                    </span>
                                    <select class="dropdownList font-size-select-option" data-udraw="fontSizeSelector"></select>
                                </div>
                                <br class="mobile_only"/>
                                <div class="btn-group" data-udraw="fontHeightContainer">
                                    <i class="fas fa-font fa-text-height" style="margin: 0 5px;"></i>
                                    <select class="dropdownList" data-udraw="fontHeightSelector"></select>
                                </div>
                                <div>
                                    <a href="#" class="text_buttons text_area_button dropdown-toggle" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-font"></i>
                                        <i class="fas fa-i-cursor"></i>
                                    </a>
                                    <div class="dropdown-menu textarea_dropdown">
                                        <textarea class="form-control" data-udraw="textArea"></textarea>
                                    </div>
                                </div>
                                <div data-udraw="fontStyleContainer">
                                    <a href="#" class="text_buttons" data-udraw="font_styles_btn" data-toggle="dropdown"><i class="fas fa-bold"></i></a>
                                    <div data-udraw="font_styles_container" class="dropdown-menu">
                                        <a class="text_buttons" data-udraw="boldButton"><i class="fas fa-bold"></i></a>
                                        <a class="text_buttons" data-udraw="italicButton"><i class="fas fa-italic"></i></a>
                                        <a class="text_buttons" data-udraw="underlineButton"><i class="fas fa-underline"></i></a>
                                        <a class="text_buttons" style="text-decoration:overline; font-weight: bold;" data-udraw="overlineButton"><span>O</span></a>
                                        <a class="text_buttons" data-udraw="strikeThroughButton"><i class="fas fa-strikethrough"></i></a>
                                    </div>
                                </div>
                                <div data-udraw="fontAlignContainer">
                                    <a href="#" class="text_buttons" data-udraw="font_align_btn" data-toggle="dropdown"><i class="fas fa-align-left"></i></a>
                                    <div data-udraw="font_align_container" class="dropdown-menu">
                                        <a class="text_buttons" data-udraw="textAlignLeft"><i class="fas fa-align-left"></i></a>
                                        <a class="text_buttons" data-udraw="textAlignCenter"><i class="fas fa-align-center"></i></a>
                                        <a class="text_buttons" data-udraw="textAlignRight"><i class="fas fa-align-right"></i></a>
                                        <a class="text_buttons" data-udraw="textAlignJustify"><i class="fas fa-align-justify"></i></a>
                                    </div>
                                </div>
                                <div data-udraw="letter_spacing_container">
                                    <a href="#" class="text_buttons" data-toggle="dropdown"><i class="fas fa-font"></i><i class="fas fa-arrows-alt-h" style="font-size: 0.75em;"></i><i class="fas fa-bold"></i></a>
                                    <div class="dropdown-menu">
                                        <i class="fas fa-font"></i><i class="fas fa-arrows-alt-h" style="font-size: 0.75em;"></i><i class="fas fa-bold"></i>
                                        <input type="number" data-udraw="letterSpaceInput" style="margin-left: 15px;"/>
                                    </div>
                                </div>
                                <div data-udraw="link_row" class="hidden">
                                    <span data-i18n="[html]text.link_url"></span>
                                    <input type="text" data-udraw="link_href_input" placeholder="https://www.google.ca" style="width: 100px;" />
                                </div>
                                <div data-udraw="curved_text_container">
                                    <a href="#" class="text_buttons" data-toggle="dropdown"><i class="fas fa-font"></i><i class="fas fa-circle-notch"></i></a>
                                    <div class="dropdown-menu">
                                        <div data-i18n="[title]tooltip.curved-text-spacing">
                                            <i class="fas fa-font"></i><i class="fas fa-arrows-alt-h" style="font-size: 0.75em;"></i><i class="fas fa-bold"></i>
                                            <div class="slider-class" data-udraw="curvedTextSpacing"></div>
                                        </div>
                                        <div data-i18n="[title]tooltip.curved-text-radius">
                                            <i class="fas fa-circle-notch"></i>
                                            <div class="slider-class" data-udraw="curvedTextRadius"></div>
                                        </div>
                                        <div data-i18n="[data-original-title]tooltip.curved-text-starting-angle">
                                            <i class="fas fa-spinner"></i>
                                            <div class="slider-class" data-udraw="curvedTextStartingAngle"></div>
                                        </div>
                                        <div>
                                            <a href="#" class="btn btn-default designer-toolbar-btn" data-i18n="[title]tooltip.flip-curve" data-udraw="reverseCurve"><span data-i18n="[html]button_label.flip-curve"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="svg_tools">
                                <div data-udraw="imageColourContainer"></div>
                            </div>
                            <div class="image_tools">
                                <a href="#" data-udraw="convert_svg"><div></div></a>
                                <div class="btn-group btn-group-sm" style="padding: 5px;">
                                    <a href="#" data-udraw="replaceImage" class="replaceImage" data-i18n="[title]tooltip.replace-image"><i class="fas fa-retweet"></i></a>
                                </div>
                            </div>
                            <div class="general_tools">
                                <a href="#" data-udraw="colour_picker_btn" data-toggle="dropdown"><div></div></a>
                                <div class="dropdown-menu">
                                    <div class="colour_container">
                                        <div class="btn-group btn-group-sm">
                                            <input type="text" readonly value="#000000" data-opacity="1" class="standard-js-colour-picker text-colour-picker" data-udraw="designerColourPicker">
                                        </div>
                                        <h4>Custom colours</h4>
                                        <ul class="colour_list custom_colours"></ul>
                                    </div>
                                    <hr>
                                    <div class="colour_container">
                                        <h4>Default colours</h4>
                                        <ul class="colour_list">
                                            <li data-colour="#ff5c5c" style="background-color: #ff5c5c;"></li>
                                            <li data-colour="#ffbd4a" style="background-color: #ffbd4a;"></li>
                                            <li data-colour="#fff952" style="background-color: #fff952;"></li>
                                            <li data-colour="#99e265" style="background-color: #99e265;"></li>
                                            <li data-colour="#35b729" style="background-color: #35b729;"></li>
                                            <li data-colour="#44d9e6" style="background-color: #44d9e6;"></li>
                                            <li data-colour="#2eb2ff" style="background-color: #2eb2ff;"></li>
                                            <li data-colour="#5271ff" style="background-color: #5271ff;"></li>
                                            <li data-colour="#b760e6" style="background-color: #b760e6;"></li>
                                            <li data-colour="#ff63b1" style="background-color: #ff63b1;"></li>
                                        </ul>
                                        <ul class="colour_list">
                                            <li data-colour="#000000" style="background-color: #000000;"></li>
                                            <li data-colour="#666666" style="background-color: #666666;"></li>
                                            <li data-colour="#a8a8a8" style="background-color: #a8a8a8;"></li>
                                            <li data-colour="#d9d9d9" style="background-color: #d9d9d9;"></li>
                                            <li data-colour="#ffffff" style="background-color: #ffffff;"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm" style="padding: 5px;">
                                    <a href="#" data-udraw="layer_up" class="text_buttons" data-i18n="[title]tooltip.layer-up"><i class="fas fa-arrow-up"></i></a>
                                </div>
                                <div class="btn-group btn-group-sm" style="padding: 5px;">
                                    <a href="#" data-udraw="layer_down" class="text_buttons" data-i18n="[title]tooltip.layer-down"><i class="fas fa-arrow-down"></i></a>
                                </div>
                                <div class="btn-group btn-group-sm" style="padding: 5px;">
                                    <a href="#" data-udraw="duplicateButton" class="text_buttons" data-i18n="[title]tooltip.duplicate-object"><i class="fas fa-copy"></i></a>
                                </div>
                                <div class="btn-group btn-group-sm" style="padding: 5px;">
                                    <a href="#" data-udraw="removeButton" class="text_buttons" data-i18n="[title]tooltip.delete"><i class="far fa-trash-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-udraw="previous_page_container" class="page_container">
                        <img data-udraw="previous_page_thumb" class="page_thumb"/>
                    </div>
                    <div data-udraw="canvasContainer">
                        <div class="alert alert-danger fade in" role="alert" style="display:none;padding: 5px;" data-udraw="outsideAlert">
                            <button type="button" class="close button" data-dismiss="alert">
                                <span aria-hidden="true" data-i18n="[html]text.objects-outside-dismiss"></span>
                                <span class="sr-only">Close</span>
                            </button>
                            <p data-i18n="[html]text.objects-outside-description"></p>
                        </div>
                        <div data-udraw="canvasWrapper">
                            <canvas id="racad-designer-canvas" width="504" height="288" data-udraw="canvas"></canvas>
                        </div>
                    </div>
                    <div data-udraw="next_page_container" class="page_container">
                        <div>
                            <img data-udraw="next_page_thumb" class="page_thumb"/>
                        </div>
                    </div>
                    <div class="footer">
                        <div data-udraw="zoom_container">
                            <div><a href="#" id="full-screen" class="text_buttons"><i class="fas fa-expand"></i></a></div>
                            <div class="divider"></div>
                            <div>
                                <a href="#" class="text_buttons" data-udraw="zoom_out"><i class="fas fa-search-minus"></i></a>
                                <a href="#" class="text_buttons" data-toggle="dropdown" data-udraw="zoom_text_btn"><span data-udraw="zoom_text"></span></a>
                                <ul data-udraw="zoom_selector" class="dropdown-menu zoom_dropdown">
                                    <li data-zoom="0.1">10%</li>
                                    <li data-zoom="0.25">25%</li>
                                    <li data-zoom="0.5">50%</li>
                                    <li data-zoom="0.75">75%</li>
                                    <li data-zoom="1">100%</li>
                                    <li data-zoom="1.25">125%</li>
                                    <li data-zoom="1.5">150%</li>
                                    <li data-zoom="1.75">175%</li>
                                    <li data-zoom="2">200%</li>
                                    <li data-zoom="2.25">225%</li>
                                    <li data-zoom="2.5">250%</li>
                                    <li data-udraw="fill_zoom">Fill</li>
                                </ul>
                                <a href="#" class="text_buttons" data-udraw="zoom_in"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                        <div data-udraw="add_container">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="modal overlay-modal" data-udraw="progressModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close button" data-dismiss="modal">×</button>
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

                
                <!-- Crop Dialog -->
                <div class="modal overlay-modal" id="crop-modal" data-udraw="cropModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div data-udraw="crop_preview" style="padding-top:35px;">
                                    <img src="#" data-udraw="image_crop" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-danger" data-dismiss="modal" data-udraw="crop_cancel"><span data-i18n="[html]button_label.cancel_crop"></span></a>
                                <a href="#" class="btn btn-success" tabindex="3" data-udraw="crop_apply"><span data-i18n="[html]common_label.apply"></span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crop Button ( Overlay on Images ) -->
                <button id="image-crop-btn" class="btn btn-warning btn-xs" style="position:absolute; display:none;"><i class="fa fa-crop"></i>&nbsp;Crop</button>

                <!-- Replace Image Dialog -->
                <div class="modal overlay-modal" id="replace-image-modal" data-udraw="replaceImageModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div id="replace-image-body-div">
                                    <input class="replace-image-upload-btn" type="file" name="files[]" multiple accept="image/*" />

                                    <a href="#" class="btn btn-default " style="width:175px;">
                                        <i class="fa fa-upload" style="font-size:1.5em"></i>&nbsp; <span data-i18n="[html]common_label.upload-image"></span>
                                    </a>
                                    <a href="#" class="replace-image-local-storage-btn btn btn-default" style="width: 175px;">
                                        <i class="fa fa-briefcase" style="font-size:1.5em"></i>&nbsp; <span data-i18n="[html]button_label.replace-image-local"></span>
                                    </a>
                                    <a href="#" class="replcae-image-clipart-btn btn btn-default" style="width: 175px;">
                                        <i class="far fa-image" style="font-size:1.5em"></i>&nbsp; <span data-i18n="[html]common_label.clipart-collection"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.cancel"></span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Multipage PDF upload dialog -->
                <div class="modal overlay-modal" data-udraw="multipagePDFModal">
                    <div class="modal-dialog">
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
                <!-- Load XML modal -->
                <div class="modal overlay-modal" data-udraw="load_xml_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body" style="text-align: center;">
                                <p data-i18n="[html]text.load_saved_xml"></p>
                                <button type="button" class="button" data-udraw="load_saved_xml" data-i18n="[html]common_label.yes"></button>
                                <button type="button" class="button" data-dismiss="modal" data-i18n="[html]common_label.no" data-udraw="not_load_saved_xml"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Choose route dialog-->
                <div class="modal overlay-modal" data-udraw="choose_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body" style="text-align: center;">
                                <?php if ($is_design_product) { ?>
                                <button type="button" class="button" data-udraw="choose_design"><?php _e('Custom Design','udraw'); ?></button>
                                <span><?php _e('or','udraw'); ?></span>
                                <?php } ?>
                                <input type="file" data-udraw="upload_artwork" files="[]" style='display: none;'/>
                                <button data-udraw="upload_artwork_button" class="button" disabled>
                                    <span style="display: none;"><?php _e('Upload Artwork','udraw'); ?></span>
                                    <i class="fas fa-pulse fa-spinner"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="udraw-preview-ui" style="display:none; padding-left:30px;">
            <div class="row" style="padding-bottom:15px;">
                <a href="#" class="btn btn-danger" id="udraw-preview-back-to-design-btn"><strong>Back to Update Design</strong></a>
                <a href="#" class="btn btn-success" id="udraw-preview-add-to-cart-btn"><strong>Approve &amp; Add to Cart</strong></a>
            </div>
            <div class="row" id="udraw-preview-design-placeholer">
            </div>
        </div>
    </div>
</div>

<form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
    <input type="hidden" name="udraw_save_product_data" value="" />
    <input type="hidden" name="udraw_save_product_preview" value="" />
    <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
    <input type="hidden" name="udraw_save_access_key" value="<?php echo (isset($_GET['udraw_access_key'])) ? $_GET['udraw_access_key'] : NULL; ?>" />
    <input type="hidden" name="udraw_is_saving_for_later" value="1" />
    <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
    <input type="hidden" name="udraw_selected_variations" value="" />
    <?php wp_nonce_field('save_udraw_customer_design'); ?>
</form>

<?php include_once(UDRAW_PLUGIN_DIR . '/designer/designer-template-script.php'); ?>

<style type="text/css">
    <?php echo $_udraw_settings['udraw_designer_css_hook']; ?>
</style>
<script>
    jQuery(document).ready(function ($) {
        jQuery(document).on('udraw-loaded', function(){
            //In case loading a saved design.
            var productURL = window.location.href;
            var designFile;
            if(productURL.indexOf('udraw_access_key') !== -1) {
                var urlSplit = productURL.split('udraw_');
                accessKey = 'udraw_' + urlSplit[urlSplit.length - 1];
                var storage = '<?php echo UDRAW_STORAGE_URL ?>';
                var username = '<?php echo $loggedInUser ?>';
                if (username !== '') {
                    designFile = storage + username + '/output/' + accessKey + '_usdf.xml';
                } else {
                    designFile = storage + '_' + urlSplit[urlSplit.length - 1] + '_'  + '/output/' + accessKey + '_usdf.xml';
                }
                RacadDesigner.Legacy.loadCanvasDesign(designFile);
            }
        });
        
        <?php echo $_udraw_settings['udraw_designer_js_hook']; ?>
    });
</script>