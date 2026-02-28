<?php
global $woocommerce;
include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');

$load_frontend_navigation = true;
$displayInlineAddToCart = false;

$uDraw = new uDraw();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();

$loggedInUser = '';
if (is_user_logged_in()) {
    $loggedInUser = wp_get_current_user()->user_login;
}

if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
    $product_type = $product->get_type();
    $product_id = $product->get_id();
} else {
    $product_type = $product->product_type;
    $product_id = $product->id;
}
if ($product_type == "simple" && !$isPriceMatrix) {
    $displayInlineAddToCart = true;
}
$friendly_item_name = get_the_title($post->ID);
$allow_structure_file = false;
if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
?>
<div id="designer-wrapper" data-udraw="designerWrapper">
    <div id="udraw-bootstrap" data-udraw="uDrawBootstrap" style="display: none;">
        <?php
        //Apply extra action here
        do_action('udraw_frontend_extra_items', $post);
        ?>
        <div id="udraw-main-designer-ui">
            <div class="body-block">
                <div id="designer-left-toolbar" data-udraw="designerSideBar">
                    <ul data-udraw="toolbarList">
                        <li class="toolCat active" data-tools_container="text">
                            <button id="addText">
                                <i class="fas fa-font fa-2x"></i>
                                <span data-i18n="[html]common_label.text"></span>
                            </button>
                        </li>
                        <li class="toolCat" data-tools_container="images">
                            <button id="addImage">
                                <i class="fas fa-images fa-2x"></i>
                                <span data-i18n="[html]common_label.image"></span>
                            </button>
                        </li>
                        <?php if (!$_udraw_settings['designer_disable_shapes']) { ?>
                        <li class="toolCat" data-tools_container="shapes">
                            <button id="addShape">
                                <i class="fas fa-shapes fa-2x"></i>
                                <span data-i18n="[html]common_label.shapes"></span>
                            </button>
                        </li>
                        <?php } ?>
                        <li class="toolCat" data-tools_container="linked_templates">
                            <button id="linkedTemplates">
                                <i class="fas fa-book-open fa-2x"></i>
                                <span data-i18n="[html]common_label.library"></span>
                            </button>
                        </li>
                        <li class="toolCat" data-tools_container="background">
                            <button id="backgroundColour">
                                <i class="fas fa-fill-drip fa-2x"></i>
                                <span data-i18n="[html]menu_label.background"></span>
                            </button>
                        </li>
                    </ul>
                </div>
                
                <!--Side Bar-->
                <div data-udraw="toolsSidebar">
                    <!--Text Container-->
                    <div class="tools_container text">
                        <div class="tools_list">
                            <button class="sidebarBtn" data-udraw="addText" data-i18n="[title]tooltip.add-text">
                                <i class="fas fa-plus"></i></i>&nbsp;<span data-i18n="[html]button_label.add_text"></span>
                            </button>
                            <button class="sidebarBtn" data-udraw="textTemplates" data-i18n="[title]text_label.text_templates">
                                <i class="far fa-file-word"></i></i>&nbsp;<span data-i18n="[html]text_label.text_templates"></span>
                            </button>

                            <h5 style="text-align: center">Enter your Text</h5>
                            <div class="text_objects_list"></div>
                        </div>
                    </div>
                    
                    
                    <!--Images Container-->
                    <div class="tools_container images">
                        <div class="tools_list">
                            <button class="sidebarBtn" data-udraw="imageGallery">
                                <i class="fas fa-camera"></i>&nbsp;<span data-i18n="[html]button_label.image_gallery"></span>
                            </button>
                            <div data-udraw="uploadImage">
                                <label class="sidebarBtn" style="cursor: pointer">
                                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;<span data-i18n="[html]text.upload-photo"></span>
                                    <input id="upload_image_button" type="file" style="display: none"/>
                                </label>
                            </div>
                            <div class="replaceImage" data-udraw="replaceImage">
                                <label class="sidebarBtn" style="cursor: pointer">
                                    <i class="fas fa-retweet"></i>
                                    <span>Replace Image</span>
                                </label>
                            </div>
							<?php if ($_udraw_settings['designer_enable_local_clipart']) { 
								$siteIcon = esc_url(get_site_icon_url());?>
								<button class="sidebarBtn" data-udraw="privateClipartCollection" id="Private-Clipart-Collection-list-container">
									<?php if ($siteIcon !== '' && $siteIcon !== null && $siteIcon !== 'null') {?><img src="<?php echo $siteIcon; ?>" width="15"/><?php } ?>&nbsp;<span data-i18n="[html]button_label.private_image_library"></span>
								</button>
                            <?php  } ?>
                            <h4 style="text-align: center; display: none" data-i18n="common_label.my_photos"></h4>
                            <div data-udraw="recentlyUploaded"></div>
                            <div data-udraw="localImageList"></div>


                        </div>
                    </div>

                    <!--Shapes Container-->
                    <div class="tools_container shapes">
                        <ul>
                            <li id="Circle-list-container">
                                <a href="#" id="shapes-circle-add-btn" data-udraw="addCircle" data-toggle="tooltip" data-i18n="[title]menu_label.circle">
                                    <i class="fas fa-circle fa-4x shape-icon"></i> 
                                </a>
                            </li>
                            <li id="Rectangle-list-container">
                                <a href="#" id="shapes-sqaure-add-btn" data-udraw="addRectangle" data-toggle="tooltip" data-i18n="[title]menu_label.rect">
                                    <i class="fas fa-square fa-4x shape-icon"></i> 
                                </a>
                            </li>
                            <li id="Triangle-list-container">
                                <a href="#" id="shapes-triangle-add-btn" data-udraw="addTriangle" data-toggle="tooltip" data-i18n="[title]menu_label.triangle">
                                    <i class="fas fa-play fa-4x shape-icon"></i>
                                </a>
                            </li>
                            <li id="Line-list-container">
                                <a href="#" id="shapes-line-add-btn" data-udraw="addLine" data-toggle="tooltip" data-i18n="[title]menu_label.line">
                                    <i class="fas fa-grip-lines-vertical fa-4x shape-icon"></i> 
                                </a>
                            </li>
                            <li id="Curved-line-list-container">
                                <a href="#" id="shapes-curved-line-add-btn" data-udraw="addCurvedLine" data-toggle="tooltip" data-i18n="[title]menu_label.curved_line">
                                    <i class="fas fa-bezier-curve fa-4x shape-icon"></i> 
                                </a>
                            </li>
                            <li id="Polygon-list-container">
                                <a href="#" id="open-polyshape-modal-btn" data-udraw="addPolygon" data-toggle="tooltip" data-i18n="[title]menu_label.polyshape">
                                    <i class="fas fa-draw-polygon fa-4x shape-icon"></i>
                                </a>
                            </li>
                            <li id="Star-list-container">
                                <a href="#" id="shapes-star-add-btn" data-udraw="addStar" data-toggle="tooltip" data-i18n="[title]menu_label.star">
                                    <i class="fas fa-star fa-4x shape-icon"></i> 
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!--Linked Templates-->
                    <div class="tools_container linked_templates">
                        <div class="tools_list">
                            <h5 data-i18n="header_label.linked-templates-header"></h5>
                            <div data-udraw="linked_templates_container">
                                <div data-udraw="linkedTemplatesContainer" style="text-align: center;"></div>
                            </div>  
                        </div>
                    </div>

                    <!--Background-->
                    <div class="tools_container background">
                        <div class="background_container">
                            <div data-udraw="backgroundColourContainer">
                                <span data-i18n="[html]menu_label.select-background"></span>&nbsp;
                                <input type="text" readonly value="#ffffff" data-opacity="1" class="standard-js-colour-picker text-colour-picker" data-udraw="background_colour" data-i18n="[title]tooltip.background-colour">
                                <button data-udraw="clear_background" data-i18n="[title]menu_label.clear-background"><i class="fas fa-times" style="color: red"></i></button>
                            </div>
                            <div data-udraw="uploadBackgroundImage">
                                <label class="sidebarBtn" style="cursor: pointer">
                                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;<span data-i18n="[html]menu_label.upload-background"></span>
                                    <input id="uploadBackground" type="file" style="display: none"/>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
           

                <!--Canvas-->
                <div id="canvas-container" data-udraw="canvasContainer">
                        <div id="designer-menu" data-udraw="designerMenu">  
                        <div class="btn-groups left">
                            <div class="group">
                                <button class="group-btn" data-udraw="toggleRuler" type="button" data-i18n="[title]Toggle Ruler">
                                <i class="fas fa-ruler-combined"></i>
                                </button>
                            </div>
                            <div class="group">
                                <button class="group-btn" data-udraw="pagesGrid" type="button" data-i18n="[title]Page Grid">
                                <i class="fas fa-th"></i>
                                </button>
                            </div>
                            <div class="group">
                                <button class="group-btn" data-udraw="undoButton" type="button" data-i18n="[title]tooltip.undo">
                                    <i class="fas fa-undo-alt"></i>
                                </button>
                                <button class="group-btn" data-udraw="redoButton" type="button" data-i18n="[title]tooltip.redo">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </div>
                            <div class="group">
                                <button class="group-btn" data-udraw="increaseZoomButton" type="button" data-i18n="[title]tooltip.zoom-in">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button class="group-btn" data-udraw="decreaseZoomButton" type="button" data-i18n="[title]tooltip.zoom-out">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                            </div>
                            <div class="group">
                                <button class="group-btn" data-udraw="removeButton" type="button" data-i18n="[title]tooltip.delete">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                            <!--<label data-udraw="toggleGridLines">
                                <input type="checkbox" data-udraw="gridCheckbox" />
                                <span style="color: #fff" data-i18n="[html]menu_label.toggle_grid"></span>
                            </label>
                            <label data-udraw="snapToGrid">
                                <input type="checkbox" data-udraw="snapCheckbox" />
                                <span style="color: #fff" data-i18n="[html]menu_label.snap_to_grid"></span>
                            </label>-->
                        </div>
                        <!-- Center Buttons Group Ends -->
                        
                        <!-- Right Buttons Group Starts -->
                        <div class="btn-groups right">
                            <div class="group">
                                <?php if ( (wp_get_current_user()->ID > 0) && ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) ) { ?>
                                <button class="group-btn"  id="udraw-save-later-design-btn" type="button">
                                    <i class="far fa-clock"></i>
                                    <span>Save for Later</span>
                                </button>
                                <?php } ?>
                                <button class="group-btn" data-udraw="reloadDesign" type="button" data-i18n="[title]tooltip.clear_canvas">
                                    <i class="fas fa-recycle"></i>
                                    <span>Start Again</span>
                                </button>
                                <?php if ($allowCustomerDownloadDesign) { ?>
                                <button class="group-btn" data-udraw="downloadPDFButton" type="button">
                                    <i class="far fa-file-pdf"></i>
                                    <span>Proof</span>
                                </button>
                                <?php } ?>
                                
                        <!-- If Display Options First is enabled-->
                                <?php if ($displayOptionsFirst) { ?>
                                <button class="group-btn" id="show-udraw-display-options-ui-btn" type="button">
                                    <i class="fas fa-times"></i>
                                    <span>Close</span>
                                </button>
                                    <?php if ($product_type == "variable") { ?>
                                    <button class="group-btn" data-udraw="addToCart" type="button">
                                        <i class="fas fa-cart-plus"></i></i>&nbsp;<span>Continue</span>
                                    </button>
                                    <?php } ?>
                                    <?php if ($allow_structure_file) { ?>
                                    <button class="group-btn" data-udraw="excelContinue" type="button">
                                        <i class="fas fa-cart-plus"></i>&nbsp;<span>Continue</span>
                                    </button>
                                    <?php } ?>
                                    <?php if ($product_type == "simple" && $displayInlineAddToCart && !$allow_structure_file) { ?>
                                        <form class="cart" method="post" enctype="multipart/form-data" style="display: inline-block; margin-top: -3px; margin-left: 0!important; float: right;">
                                            <input type="hidden" value="" name="udraw_product">
                                            <input type="hidden" value="" name="udraw_product_data">
                                            <input type="hidden" value="" name="udraw_product_svg">
                                            <input type="hidden" value="" name="udraw_product_preview">
                                            <input type="hidden" value="" name="udraw_product_cart_item_key">
                                            <input type="hidden" value="" name="ua_ud_graphic_url" />
                                    
                                            <button class="group-btn" type="button" data-udraw="addToCart" id="simple-add-to-cart-btn">
                                                <i class="fas fa-cart-plus"></i><i class="fa fa-spinner fa-pulse" style="display: none;"></i>&nbsp;<span>Continue</span>
                                            </button>
                                        </form>
                                    <?php } ?>
                                <?php } ?>
                        
                        <!-- If Display Options is not enabled and Product with multiple or no templates -->
                                <?php if (!$displayOptionsFirst && ($templateCount > 1 || $isTemplatelessProduct)) { ?>
                                <button class="group-btn" id="show-udraw-display-options-ui-btn" type="button">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Back</span>
                                </button>
                                <?php } ?>
                        
                        
                                <?php if (isset($displayPriceMatrixOptions)) { ?>
                                <button class="group-btn" id="udraw-price-matrix-show-quote" type="button">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Back</span>
                                </button>
                                <button class="group-btn" id="udraw-price-matrix-designer-save" type="button">
                                    <i class="fas fa-arrow-right"></i>&nbsp;<span>Continue</span>
                                </button>
                                <?php } ?>
                        
                        
                                <?php if (!$displayOptionsFirst && !isset($displayPriceMatrixOptions)) { ?>
                                    <?php if ($product_type == "variable") { ?>
                                    <button class="group-btn" id="udraw-variations-step-0-btn" type="button" style="display: none;">
                                        <i class="fas fa-arrow-right"></i>&nbsp;<span>Back</span>
                                    </button>
                                    <button class="group-btn" id="udraw-variations-step-1-btn" type="button">
                                        <i class="fas fa-arrow-right"></i>&nbsp;<span>Next</span>
                                    </button>
                                    <?php } else if ($product_type == "variable" ||$isPriceMatrix) { ?>
                                    <button class="group-btn" id="udraw-variations-step-1-btn" type="button">
                                        <i class="fas fa-arrow-right"></i>&nbsp;<span>Next</span>
                                    </button>
                                    <?php } else { ?>
                                    <form class="cart" method="post" enctype="multipart/form-data" style="display: inline-block; float: right;">
                                        <input type="hidden" value="" name="udraw_product">
                                        <input type="hidden" value="" name="udraw_product_data">
                                        <input type="hidden" value="" name="udraw_product_svg">
                                        <input type="hidden" value="" name="udraw_product_preview">
                                        <input type="hidden" value="" name="udraw_product_cart_item_key">

                                        <?php if ($displayInlineAddToCart) {?>
                                            <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" style="width: 60px; display: inline; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 5px;">
                                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"> 
                                            <span style="font-size:1.5em; vertical-align:middle;"><?php echo get_woocommerce_currency_symbol(); ?></span><span id="product_total_price" style="font-size:1.5em; vertical-align:middle;"><?php echo $product->get_price(); ?></span>
                                            <button class="group-btn" type="button" data-udraw="addToCart">
                                                <i class="fas fa-cart-plus"></i><i class="fa fa-spinner fa-pulse" style="display: none;"></i>&nbsp;<span>Continue</span>
                                            </button>
                                        <?php } ?>
                                    </form>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if ($isPriceMatrix && !$allow_structure_file) { ?>
                                        <?php if ($product_type != "variable") { ?>
                                        <button class="group-btn" type="button" data-udraw="addToCart">
                                            <i class="fas fa-cart-plus"></i>&nbsp;<span>Finish & Pay</span>
                                        </button>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- Right Buttons Group Ends -->
                    </div>
                    <!-- Top Toolbar -->                        
                    <div data-udraw="top_toolbar_container" class="top_toolbar_container">
                        <div data-udraw="top_toolbar" class="top_toolbar">
                            <div class="text_tools toolset tools" style="display: none;">
                                <!-- TEXT TOOLS -->
                                <div class="group">
                                    <!-- Font Family -->
                                    <div data-udraw="fontFamilyContainer" style="width: 230px; padding: 5px;" data-i18n="[title]tooltip.font-list">
                                        <select id="font-family-selection" class="font-family-selection" data-udraw="fontFamilySelector">
                                            <option value="Arial" style="font-family:'Arial';">Arial</option>
                                            <option value="Calibri" style="font-family:'Calibri';">Calibri</option>
                                            <option value="Times New Roman" style="font-family:'Times New Roman'">Times New Roman</option>
                                            <option value="Comic Sans MS" style="font-family:'Comic Sans MS';">Comic Sans MS</option>
                                            <option value="French Script MT" style="font-family:'French Script MT';">French Script MT</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="group">
                                    <!-- Font Size -->
                                    <span style="vertical-align: middle">Font Size</span>
                                    <div data-udraw="font_size_container" style="display: inline-block; width: 100px; padding: 5px;" data-i18n="[title]tooltip.font-size"> 
                                            <select class="font-size-select-option" data-udraw="fontSizeSelector"></select>
                                    </div>
                                </div>

                                <div class="group" data-udraw="fontStyleContainer">
                                    <!-- Font Style -->
                                    <button class="group-btn" data-udraw="boldButton" data-i18n="[title]tooltip.bold">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button class="group-btn" data-udraw="italicButton" data-i18n="[title]tooltip.italic">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button class="group-btn" data-udraw="underlineButton" data-i18n="[title]tooltip.underline">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>

                                <div class="group" data-udraw="fontAlignContainer">
                                    <!-- Font Align -->
                                    <button class="group-btn" data-udraw="textAlignLeft" data-i18n="[title]tooltip.text-left">
                                        <i class="fas fa-align-left"></i>
                                    </button>
                                    <button class="group-btn" data-udraw="textAlignCenter" data-i18n="[title]tooltip.text-center">
                                        <i class="fas fa-align-center"></i>
                                    </button>
                                    <button class="group-btn" data-udraw="textAlignRight" data-i18n="[title]tooltip.text-right">
                                        <i class="fas fa-align-right"></i>
                                    </button>
                                </div>

                                <div class="group">
                                    <!-- Line Height and Letter Spacing -->
                                    <div class="group-element" data-udraw="fontHeightContainer">
                                        <span data-i18n="[title]tooltip.line-spacing"><i class="fas fa-text-height"></i></span>
                                        <select class="line-height-selector" data-udraw="fontHeightSelector"></select>
                                    </div>

                                    <div class="group-element" data-udraw="spacing_row">
                                        <button class="group-btn" data-toggle="dropdown" data-i18n="[title]tooltip.letter-spacing">
                                            <i class="fas fa-text-width"></i>
                                        </button>
                                        <div data-udraw="font_width_container" class="dropdown-menu" style="width: auto; padding: 5px;"> 
                                            <input type="number" class="letter-spacing-selector" data-udraw="letterSpaceInput"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="group">
                                    <!-- Object Color-->
                                    <div class="group-element" data-udrae="objectColour">
                                        <div data-udraw="designerColourContainer" style="inline-block">
                                            <button class="group-btn" data-toggle="dropdown" data-i18n="[title]tooltip.object-colour">
                                                <i class="fas fa-palette"></i>
                                            </button>
                                            <div data-udraw="object_colour_container" class="dropdown-menu" style="width: auto; padding: 5px;">
                                                <ul class="color-list" style="max-width: 166px">
                                                    <li><a href="#" data-hex="#000000" style="background-color: #000000;" class="black is-active" data-i18n="[title]Black"></a></li>
                                                    <li><a href="#" data-hex="#00AEEF" style="background-color: #00AEEF;" class="cyan" data-i18n="[title]Cyan"></a></li>
                                                    <li><a href="#" data-hex="#FFF200" style="background-color: #FFF200;" class="yellow" data-i18n="[title]Yellow"></a></li>
                                                    <li><a href="#" data-hex="#EC008C" style="background-color: #EC008C;" class="magenta" data-i18n="[title]Magenta"></a></li>
                                                    <li><a href="#" data-hex="#D2232A" style="background-color: #D2232A;" class="red" data-i18n="[title]Red"></a></li>
                                                    <li><a href="#" data-hex="#21409A" style="background-color: #21409A;" class="blue" data-i18n="[title]Blue"></a></li>
                                                    <li><a href="#" data-hex="#8561A3" style="background-color: #8561A3;" class="lilac" data-i18n="[title]Lilac"></a></li>
                                                    <li><a href="#" data-hex="#41AC14" style="background-color: #41AC14;" class="green" data-i18n="[title]Green"></a></li>
                                                    <li><a href="#" data-hex="#29949F" style="background-color: #29949F;" class="aqua" data-i18n="[title]Aqua"></a></li>
                                                    <li><a href="#" data-hex="#C7990B" style="background-color: #C7990B;" class="gold" data-i18n="[title]Gold"></a></li>
                                                    <li><a href="#" data-hex="#AA825A" style="background-color: #AA825A;" class="bronze" data-i18n="[title]Bronze"></a></li>
                                                    <li><a href="#" data-hex="#B2B2B2" style="background-color: #B2B2B2;" class="silver" data-i18n="[title]Silver"></a></li>
                                                    <li><a href="#" data-hex="#FFF1D2" style="background-color: #FFF1D2;" class="ivory" data-i18n="[title]Ivory"></a></li>
                                                    <li><a href="#" data-hex="#BADAF3" style="background-color: #BADAF3;" class="paleblue" data-i18n="[title]Pale Blue"></a></li>
                                                    <li><a href="#" data-hex="#DBEDD8" style="background-color: #DBEDD8;" class="palegreen" data-i18n="[title]Pale Green"></a></li>
                                                    <li><a href="#" data-hex="#F5E1E3" style="background-color: #F5E1E3;" class="palepink" data-i18n="[title]Pale Pink"></a></li>
                                                    <li><a href="#" data-hex="#FCD7D0" style="background-color: #FCD7D0;" class="palepeach" data-i18n="[title]Pale Beach"></a></li>
                                                    <li><a href="#" data-hex="#D3D1E9" style="background-color: #D3D1E9;" class="palelilac" data-i18n="[title]Pale Lilac"></a></li>
                                                    <li><a href="#" data-hex="#B6D5DA" style="background-color: #B6D5DA;" class="paleaqua" data-i18n="[title]Pale Aqua"></a></li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="image_tools toolset tools" style="display: none">
                                <!-- IMAGE TOOLS -->
                                <div class="group">
                                    <!-- Crop -->
                                    <button class="group-btn" data-udraw="cropButton" data-i18n="[title]button_label.crop">
                                        <i class="fas fa-crop-alt"></i>
                                    </button>
                                </div>

                                <div class="group">
                                    <button class="group-btn" data-udraw="replaceImage" data-i18n="[title]tooltip.replace-image">
                                        <i class="fas fa-retweet"></i>
                                    </button>
                                </div>

                                <div class="group">
                                    <div class="group-element" data-udraw="rotateContainer">       
                                        <button class="group-btn" data-toggle="dropdown" data-i18n="[title]tooltip.rotate">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                        <div data-udraw="rotate_container" class="dropdown-menu">
                                            <button data-udraw="rotateleft" class="rotate" type="button">
                                                <i class="fas fa-undo-alt"></i>&nbsp;<span data-i18n="[html]button_label.RotateLeft">Rotate Left</span>
                                            </button>
                                            <button data-udraw="rotateright" class="rotate" type="button">
                                                <i class="fas fa-redo-alt"></i>&nbsp;<span data-i18n="[html]button_label.RotateRight">Rotate Right</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="shapes_tools toolset tools" style="display: none">
                                <div class="group">
                                    <div class="group-element" data-udraw="fillContainer">   
                                        <button class="group-btn" data-toggle="dropdown" data-i18n="[title]text_label.object-fill">
                                            Fill
                                        </button>
                                        <div data-udraw="fill_container" class="dropdown-menu">
                                            <div data-udraw="designerColourContainer" style="display: inline-block;">
                                                <input type="text" value="#000000" data-opacity="1" class="standard-js-colour-picker" style="background-color: rgb(178, 74, 74);" data-udraw="designerColourPicker">
                                                <input type="hidden" data-opacity="1" data-udraw="restrictedColourPicker" value="rgba(178, 74, 74, 1)">
                                            </div>
                                            <div data-udraw="clearObjFill" style="display: inline-block;">
                                                <button data-udraw="clear_fill" data-i18n="[title]menu_label.clear-fillcolour" title="Clear Fill Colour"><i class="fas fa-times" style="color: red"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="group-element" data-udraw="strokeContainer">   
                                        <button class="group-btn" data-toggle="dropdown" data-i18n="[title]text_label.object-fill">
                                            Stroke
                                        </button>
                                        <div data-udraw="stroke_container"  class="dropdown-menu">
                                            <div data-udraw="strokeColourContainer" style="display: inline-block">
                                                <input data-udraw="objectStrokeColour" type="color" value="" data-opacity="1" class="col-sm-3 stroke-colour-picker"/>
                                            </div>
                                            <div data-udraw="clearObjStroke" style="display: inline-block">
                                                <button data-udraw="clear_stroke" data-i18n="[title]menu_label.clear-strokecolour" title="Clear Stroke Colour"><i class="fas fa-times" style="color: red"></i></button>
                                            </div>
                                            <div data-udraw="strokeWidthContainer" style="display: inline-block">
                                                <input data-udraw="objectStrokeSpinner" type="text" value="0" data-opacity="1" class="stroke-spinner spinedit noSelect form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="group tools">
                                <div class="group-element" data-udraw="opacityContainer">
                                    <button class="group-btn" data-toggle="dropdown" data-i18n="[title]text.opacity-level">
                                        <i class="fas fa-adjust"></i>
                                    </button>
                                    <div data-udraw="opacity_container" class="dropdown-menu" style="padding: 0px 10px;">
                                        <input type="range" data-udraw="opacityLevel" value="1" min="0" max="1" step="0.1"/>
                                    </div>
                                </div>

                                <!-- Duplicate Object -->
                                <button class="group-btn" data-udraw="duplicateButton" data-i18n="[title]tooltip.duplicate-object">
                                    <i class="fas fa-copy"></i>
                                </button>

                                <!-- Objects Arrange -->
                                <div class="group-element" data-udraw="objectOptionsContainer">
                                    <button class="group-btn" data-toggle="dropdown">
                                        <i class="fas fa-layer-group"></i>
                                    </button>
                                    <div data-udraw="object_options_container" class="dropdown-menu">
                                        <a href="#" data-udraw="bringForwardButton">
                                            <div class="innerAnchorDiv">
                                                <i class="fa fa-arrow-up"></i>
                                                <span data-i18n="[html]button_label.bring-forward"></span>
                                            </div>
                                        </a>
                                        <a href="#" data-udraw="sendBackwardsButton">
                                            <div class="innerAnchorDiv">
                                                <i class="fa fa-arrow-down"></i>
                                                <span data-i18n="[html]button_label.send-backwards"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Object Align -->
                                <div class="group-element" data-udraw="objectAlignContainer" data-i18n="[title]tooltip.object_align">
                                    <button class="group-btn" data-toggle="dropdown">
                                        <i class="fas fa-sort-amount-up"></i>
                                    </button>
                                    <div data-udraw="object_align_container" class="dropdown-menu">
                                        <a href="#" data-udraw="objectsAlignLeft">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_left.png" alt="Align Left" /><span> Align Left</span></div>
                                        </a>
                                        <a href="#" data-udraw="objectsAlignCenter">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_center.png" alt="Align Center" /><span> Align Center</span></div>
                                        </a>
                                        <a href="#" data-udraw="objectsAlignRight">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_right.png" alt="Align Right" /><span> Align Right</span></div>
                                        </a>
                                        <a href="#" data-udraw="objectsAlignTop">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_top.png" alt="Align Top" /><span> Align Top</span></div>
                                        </a>
                                        <a href="#" data-udraw="objectsAlignMiddle">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_middle.png" alt="Align Middle" /><span> Align Middle</span></div>
                                        </a>
                                        <a href="#" data-udraw="objectsAlignBottom">
                                            <div class="innerAnchorDiv"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_bottom.png" alt="Align Bottom" /><span> Align Bottom</span></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div id="racad-designer" data-udraw="canvasWrapper">
                        <div class="alert alert-danger fade in" role="alert" id="racad-designer-object-outside-alert" style="display:none;padding: 5px;" data-udraw="outsideAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" data-i18n="[html]text.objects-outside-dismiss"></span><span class="sr-only">Close</span></button>
                            <p data-i18n="[html]text.objects-outside-description"></p>
                        </div>
                        <!--Ruler-->
			            <?php if (!$_udraw_settings['designer_disable_ruler']) { ?>
                            <canvas id="racad-designer-top-ruler-canvas" data-udraw="topRuler"></canvas>
                            <canvas id="racad-designer-side-ruler-canvas" data-udraw="sideRuler"></canvas>
			            <?php } ?>
                        <canvas id="racad-designer-canvas" width="504" height="288" data-udraw="canvas"></canvas>
                    </div>
                    <div data-udraw="pagesContainer">
                        <div id="pages-carousel" data-udraw="pagesList"></div>
                    </div>
                </div>
            </div>
            
            <!--Layers dialog-->
            <div class="modal toolbox-modal" style="top:125px;" data-udraw="layersModal" style="display: none;">
                <div class="modal-dialog modal-md" style="margin: 0px auto 0px auto;">
                    <div class="modal-content">
                        <div class="modal-header toolbox-header">
                            <span data-i18n="[html]common_label.layers"></span>
                            <div classInof="modal-header-btn-container" style="float:right;">
                                <a href="#" class="btn btn-default btn-xs" data-udraw="layersRefresh">
                                    <i class="fas fa-sync"></i>
                                </a>
                                <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxHide">
                                    <i class="fas fa-chevron-up"></i>
                                </a>
                                <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxClose">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="modal-body toolbox-body">
                            <div class="object-rotation-slider-container" data-udraw="objectRotationContainer">
                                <span data-i18n="[html]text_label.object-angle"></span>
                                <div style="width: 30px; display: inline-block;" data-udraw="objectRotationLabel"></div>
                                <div class="slider-class" style="width: 200px; display: inline-block; margin-left: 5px;" 
                                    data-udraw="objectRotationSelector"></div>
                                <a href="#" class="btn btn-warning btn-sm" style="float: right; margin-top: -4px; padding:2px;" 
                                    data-udraw="objectRotationClose">
                                    <i class="fas fa-times"></i>
                                    <span data-i18n="[html]common_label.close"></span>
                                </a>
                            </div>
                            <div class="object-rotation-slider-container" data-udraw="objectScaleContainer">
                                <span data-i18n="[html]text_label.object-scale"></span>
                                <div style="width: 30px; display: inline-block;" data-udraw="objectScaleLabel"></div>
                                <div class="slider-class" style="width: 200px; display: inline-block; margin-left: 5px;" 
                                    data-udraw="objectScaleSelector"></div>
                                <a href="#" class="btn btn-warning btn-sm" style="float: right; margin-top: -4px; padding:2px;" data-udraw="objectScaleClose">
                                    <i class="fas fa-times"></i>
                                    <span data-i18n="[html]common_label.close"></span>
                                </a>
                            </div>
                            <div data-udraw="rectangleCornerContainer">
                                <span data-i18n="[html]text_label.rectangle-corner-radius"></span>
                                <input type="number" id="rectangle-corner-radius-spinner" min="0" max="50" step="1" data-udraw="rectangleCornerSelector" />
                                <a href="#" class="btn btn-warning btn-sm" style="float: right; margin-top: 0px; padding:2px;" data-udraw="rectangleCornerClose">
                                    <i class="fas fa-times"></i>
                                    <span data-i18n="[html]common_label.close"></span>
                                </a>
                            </div>
                            <div class="scroll-content panel-body designer-panel-body">
                                <ul class="layer-box" data-udraw="layersContainer"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--LayerLabels Modal-->
            
            <!--Polyshape dialog-->
            <div class="modal" style="top:305px;" data-udraw="polygonModal">
                <div class="modal-dialog modal-md" style="margin: 0px auto 0px auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span data-i18n="[html]button_label.create-polyshape"></span>
                            <div class="topRightContainer">
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                        </div>
                        <div class="modal-body toolbox-body">
                            <div id="create-polyshape-div" style="padding: 5px;">
                                <div>
                                    <label style="width: 30%; font-weight: normal; font-size: 14px;"><span data-i18n="[html]text.polygon-sides"></span></label>
                                    <input id="polygon-sides-input" type="number" min="3" value="3" data-udraw="polygonSideSelector" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer toolbox-footer">
                            <a href="#" class="btn btn-danger" data-dismiss="modal" data-udraw="polygonCancel"><span data-i18n="[html]common_label.cancel"></span></a>
                            <a href="#" class="btn btn-success" tabindex="3" data-udraw="polygonCreate"><span data-i18n="[html]common_label.create"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div id="toolbox-holder" data-udraw="toolboxContainer">    
                <!--bounding box dialog-->
                <div class="modal toolbox-modal" id="bounding-box-modal" style="top: 95px;" data-udraw="boundingBoxModal">
                    <div class="modal-dialog modal-md" style="margin: 0px auto 0px auto;">
                        <div class="modal-content">
                            <div class="modal-header toolbox-header">
                                <span data-i18n="[html]menu_label.bounding-box"></span>
                                <div class="modal-header-btn-container" style="float:right;">
                                    <a href="#" class="btn btn-default btn-xs hide-toolbox" id="hide-boundingbox-control" style="padding-top:0px;" data-udraw="toolboxHide"><i class="fa fa-chevron-up"></i><span id="bounding-box-span" data-i18n="[html]common_label.hide"></span></a>
                                    <a href="#" class="btn btn-default btn-xs" id="close-boundingbox-control" style="padding-top:0px;" data-udraw="toolboxClose"><i class="fa fa-close"></i><span data-i18n="[html]common_label.close"></span></a>
                                </div>
                            </div>
                            <div class="modal-body toolbox-body">
                                <div class="panel-body designer-panel-body" id="bounding-box-body">
                                    <div class=" row" id="boundingbox-create-btn-area" data-udraw="boundingBoxCreateContainer">
                                        <a href="#" id="boundingbox-create-btn" class="btn btn-xs btn-success col-sm-3" style="margin-left:15px;" data-udraw="boundingBoxCreate"><i class="fa fa-plus-circle"></i>&nbsp;<span data-i18n="[html]common_label.create"></span></a>
                                    </div>
                                    <div id="boundingbox-control-div" style="display:none;" data-udraw="boundingBoxControlContainer">
                                        <div class="row" id="boundingbox-remove-btn-area">
                                            <a href="#" id="boundingbox-lock-btn" class="btn btn-xs btn-info col-sm-3" style="margin-left:15px;" data-udraw="boundingBoxLock"><i class="fa fa-lock"></i>&nbsp;<span data-i18n="[html]common_label.lock"></span></a>
                                            <a href="#" id="boundingbox-unlock-btn" class="btn btn-xs btn-info col-sm-3" style="margin-left:15px;" data-udraw="boundingBoxUnlock"><i class="fa fa-unlock"></i>&nbsp;<span data-i18n="[html]common_label.unlock"></span></a>
                                            <a href="#" id="boundingbox-remove-btn" class="btn btn-xs btn-danger col-sm-3" style="margin-left:15px;" data-udraw="boundingBoxRemove"><i class="fa fa-times-circle"></i>&nbsp;<span data-i18n="[html]common_label.remove"></span></a>
                                        </div>
                                        <div class="row" style="margin-top: 5px;">
                                            <div id="boundingbox-visual-options">
                                                <span class="col-md-8">
                                                    <span class="input-group">
                                                        <span class="input-group-addon" data-i18n="[html]text_label.thickness"></span>
                                                        <input class="boundingbox-spinner spinedit noselect form-control" type="text" id="boundingbox-stroke-size" value="1" data-udraw="boundingBoxSpinner" />
                                                    </span>
                                                </span>
                                                <span class="col-md-4">
                                                    <input type="color" id="boundingbox-colour-picker" value="#000000" data-opacity="1" style="height:15px;" data-udraw="boundingBoxColourPicker" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Advanced colouring dialog-->
                <div class="modal toolbox-modal" id="advanced-colouring-modal" style="top: 200px;" data-udraw="objectColouringModal">
                    <div class="modal-dialog modal-md" style="margin: 0px auto 0px auto;">
                        <div class="modal-content">
                            <div class="modal-header toolbox-header">
                                <span data-i18n="[html]header_label.advanced-colouring-header"></span>
                                <div class="modal-header-btn-container" style="float:right;">
                                    <a href="#" class="btn btn-default btn-xs" id="hide-designer-header-advanced-colouring-box" style="padding-top:0px;" data-udraw="toolboxHide"><i class="fa fa-chevron-up"></i><span id="advanced-colouring-box-span" data-i18n="[html]common_label.hide"></span></a>
                                    <a href="#" class="btn btn-default btn-xs" id="close-designer-header-advanced-colouring-box" style="padding-top:0px;" data-udraw="toolboxClose"><i class="fa fa-close"></i><span data-i18n="[html]common_label.close"></span></a>
                                </div>
                            </div>
                            <div class="modal-body toolbox-body">
                                <a href="#" class="btn btn-default" id="trigger-object-pattern-upload-btn" style="margin: 5px;" data-udraw="triggerObjectColouringUpload">
                                    <i class="fa fa-upload icon"></i>&nbsp; <span data-i18n="[html]button_label.upload-pattern"></span>
                                </a>
                                <input id="object-pattern-upload-btn" type="file" name="files[]" multiple style="width:142px; height:34px;" data-udraw="objectColouringUpload" />
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
                    </div>
                </div>
            </div>

            <!--Footer-->
            <div id="designer-footer" data-udraw="designerFooter">
            </div>
            <!--End-->
            <!-- Public Template Browser Dialog -->
            <div class="modal" id="browse-templates-modal" data-udraw="templatesModal">
                <div class="modal-dialog" style="width:1000px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2><span data-i18n="[html]header_label.templates-header"></span></h2>
                        </div>
                        <div class="modal-body" style="min-height: 520px; max-height: 520px; overflow: auto;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-2" style="margin-left: 0px; width:250px; display: inline-block;" data-udraw="templatesCategoryList">
                                    </div>
                                    <div class="col-md-8" style="display: inline-block;">
                                        <h4 data-udraw="templatesCategoryTitle"><span data-i18n="[html]header_label.items"></span></h4>
                                        <div data-udraw="templatesContainer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Private Template Browser Dialog -->
            <div class="modal" id="browse-private-templates-modal" data-udraw="privateTemplatesModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2><span data-i18n="[html]header_label.templates-header"></span></h2>
                        </div>
                        <div class="modal-body" style="min-height:520px; max-height: 520px; overflow:auto;">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-2" id="private-templates-category-list" style="margin-left: 0px; width:250px; display: inline-block;" data-udraw="privateTemplatesCategoryList">
                                        <h4 id="private-templates-category-list-container"><span data-i18n="[html]common_label.category"></span></h4>
                                    </div>
                                    <div id="private-templates-category-content" class="col-md-10" style="display:inline-block;">
                                        <h4 id="private-templates-container-title"><span data-i18n="[html]header_label.items"></span></h4>
                                        <div id="private-templates-container-list" data-udraw="privateTemplatesContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar Dialog -->
            <div class="modal" id="progress-bar-modal" data-udraw="progressModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!--<button class="close" data-dismiss="modal">×</button>-->
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

            <!-- Local Images Dialog -->
            <div class="modal" id="local-images-modal" data-udraw="userUploadedModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span data-i18n="[html]header_label.image-header"></span></strong>
                            <div class="topRightContainer">
                                <a href="#" onclick="javascript: jQuery('[data-udraw=\'uploadImage\']').trigger('click');">
                                    <span data-i18n="[html]common_label.upload-image"></span>
                                </a>
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                            <div style="padding-top:10px;">
                                <ol class="breadcrumb" id="local-images-folder-list" data-udraw="localFoldersList"></ol>
                            </div>
                        </div>
                        <div class="modal-body" style="max-height: 575px; overflow:auto;">
                            <div id="local-images-list" data-udraw="localImageList">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clipart Collection Dialog -->
            <div class="modal" id="clipart-collection-modal" data-udraw="clipartModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong style="font-size:large; padding-right: 15px;"><span data-i18n="[html]header_label.image-header"></span></strong>
                            <div class="topRightContainer">
                                <a href="#" data-udraw="uDrawClipartButton"><span data-i18n="[html]button_label.udraw-clipart"></span></a>
                                <!--<a href="#" data-udraw="openClipartButton"><span data-i18n="[html]button_label.open-clipart"></span></a>-->
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                        </div>
                        <div class="modal-body" style="max-height: 575px; overflow:auto;">
                            <div data-udraw="uDrawClipartFolderContainer">

                            </div>
                            <div id="clipart-collection-list" data-udraw="uDrawClipartList">

                            </div>
                            <div style="display: none" data-udraw="openClipartContainer">
                                <div data-udraw="openClipartList">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div data-udraw="openClipartPageContainer" style="float: left; display: inline-block;">
                                <a href="#" class="btn btn-default btn-sm" data-udraw="openClipartPrevious"><span data-i18n="[html]common_label.previous"></span></a>
                                <a href="#" class="btn btn-default btn-sm" data-udraw="openClipartNext"><span data-i18n="[html]common_label.next"></span></a>
                                <span data-i18n="[html]text_label.clipart-page"></span>
                                <select data-udraw="openClipartPageSelect"></select>
                                <a href="#" class="btn btn-default btn-sm" data-udraw="openClipartGoButton"><span data-i18n="[html]common_label.go"></span></a>
                            </div>
                            <ol class="breadcrumb" data-udraw="clipartFolderList"></ol>
                            <div style="float: right; display: none;" data-udraw="searchOpenClipartContainer">
                                <input type="text" data-i18n="[placeholder]text.search-by-word" data-udraw="searchOpenClipartInput" />
                                <a href="#" class="btn btn-default btn-sm" data-udraw="searchOpenClipartButton"><span data-i18n="[html]button_label.search"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Private  Clipart Collection Dialog -->
            <div class="modal" id="private-clipart-collection-modal" data-udraw="privateClipartModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span data-i18n="[html]menu_label.private-clipart-collection"></span></strong>
                            <div class="topRightContainer">
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                        </div>
                        <div class="modal-body" style="max-height: 575px; overflow:auto;">
                            <div data-udraw="privateClipartFolderContainer">

                            </div>
                            <div id="private-clipart-collection-list" data-udraw="privateClipartList">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <ol class="breadcrumb" id="private-clipart-collection-folder-list" data-udraw="privateClipartFolderList"></ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stock Image Dialog -->
            <div class="modal overlay-modal" data-udraw="stockImageModal">
                <div class="modal-dialog">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span data-i18n="[html]menu_label.stock_image"></span></strong>
                            <div style="float: right;">
                                <input type="text" data-udraw="stock_image_input" data-i18n="[placeholder]text.search-by-word"/>
                                <button type="button" data-udraw="stock_image_search" data-i18n="[html]button_label.search"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div>
                                <h6>Pixabay</h6>
                                <ul data-udraw="pixabay_results"></ul>
                            </div>
                            <div>
                                <h6>Pexels</h6>
                                <ul data-udraw="pexel_results"></ul>
                            </div>
                            <div>
                                <h6>Unsplash</h6>
                                <ul data-udraw="unsplash_results"></ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div data-udraw="pagination_container" style="float: left;">
                                <a href="#" class="btn btn-info" data-udraw="stock_image_previous" disabled><span data-i18n="[html]common_label.previous"></span></a>
                                <a href="#" class="btn btn-default" data-udraw="stock_image_next" disabled><span data-i18n="[html]common_label.next"></span></a>
                            </div>
                            <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image Modal -->
            <div class="modal overlay-modal" data-udraw="imageGalleryModal">
                <div class="modal-dialog">
                    <div class="modal-content modal-lg">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span data-i18n="[html]button_label.image_gallery"></span></strong>
                            <button class="close" data-dismiss="modal">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="Sidebar" style="width: 20%; display: inline-block; vertical-align: top;">
                                <button class="sidebarOption" data-udraw="stock-images" data-tools_container="stockImagesContainer">
                                    <i class="fas fa-images"></i>
                                    <span data-i18n="[html]menu_label.stock_image"></span>
                                </button>
                                <button class="sidebarOption" data-udraw="qrCodeInit" data-tools_container="qrCodeContainer">
                                    <i class="fas fa-qrcode"></i>
                                    <span data-i18n="[html]common_label.QRcode"></span>
                                </button>
                                <div class="sidebarOption" data-udraw="uploadImage" data-tools_container="uploadImageContainer">
                                    <label class="sidebarBtn" style="cursor: pointer">
                                        <i class="fas fa-desktop"></i>&nbsp;<span data-i18n="[html]text.my-computer"></span>
                                        <input id="upload_image_button" type="file" style="display: none">
                                    </label>
                                </div>
                            </div>
                            <div class="mainContent" style="width: 78%; display: inline-block; padding: 2%; border-left: 1px solid #ccc;">
                                <div class="main uploadImageContainer" style="overflow: auto; height: 535px; display: none;">
                                    <h4 data-i18n="[html]common_label.recent_uploads"></h4>
                                    <div data-udraw="localImageList"></div>
                                </div>
                                <div class="main stockImagesContainer" style="/*overflow: auto*/; height: 535px;">
                                    <h4 data-i18n="[html]menu_label.stock_image"></h4>
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
                                </div>
                                <div class="main qrCodeContainer" style="display: none;">
                                    <h4 data-i18n="[html]common_label.QRcode"></h4>
                                    <input type="text" tabindex="1" value="http://somedomain" data-udraw="qrInput" />
                                    <input type="hidden" value="#000000" data-udraw="qrColourPicker" />
                                    <a href="#" class="btn btn-success btn-sm" data-udraw="qrRefreshButton" style="float: right;"><i class="fas fa-sync"></i>&nbsp;<span data-i18n="[html]common_label.refresh"></span></a>
                                    <br />
                                    <div style="padding-top:25px;" data-udraw="qrPreviewContainer">
                                    </div>
                                    <a href="#" class="btn btn-success" tabindex="3" data-udraw="qrAddButton" style="float: right;"><span data-i18n="[html]common_label.add"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div data-udraw="pagination_container" style="float: left;">
                                <a href="#" class="btn btn-info" data-udraw="stock_image_previous" disabled><span data-i18n="[html]common_label.previous"></span></a>
                                <a href="#" class="btn btn-default" data-udraw="stock_image_next" disabled><span data-i18n="[html]common_label.next"></span></a>
                            </div>
                            <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Dialog -->
            <div class="modal" id="qrcode-modal" data-udraw="qrModal">
                <div class="modal-dialog modal-md" style="width: 600px;">
                    <div class="modal-content">
                        <div class="modal-body" style="max-height: 575px; overflow:auto;">
                            <span class="col-md-8">
                                <input type="text" class="form-control" tabindex="1" id="qrcode-value-txtbox" value="http://somedomain" data-udraw="qrInput" />
                            </span>
                            <span class="col-md-2">
                                <input type="hidden" id="qrcode-colour-picker" value="#000000" data-udraw="qrColourPicker" />
                            </span>
                            <span class="col-md-2">
                                <a href="#" class="btn btn-success btn-sm" data-udraw="qrRefreshButton">
                                    <i class="fa fa-refresh"></i>&nbsp;<span data-i18n="[html]common_label.refresh"></span>
                                </a>
                            </span>
                            <br />
                            <div id="qrcode-preview" style="padding-top:25px;" data-udraw="qrPreviewContainer">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]common_label.cancel"></span></a>
                            <a href="#" class="btn btn-success" tabindex="3" id="qrcode-add-btn" data-udraw="qrAddButton"><span data-i18n="[html]common_label.add"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crop Dialog -->
            <div class="modal" id="crop-modal" data-udraw="cropModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div data-udraw="crop_preview" style="padding-top:35px;">
                                <img src="#" data-udraw="image_crop" />
                            </div>
                        </div>
                    <!--Test-->
                    <div data-udraw="imageClippingModal" class="slim_modal objectEditorContainer">
                        <div data-i18n="[html]text.clip-usage"></div>
                        <span data-i18n="[html]text.select-clip-image-shape" style="font-size: 12px; width: 30%"></span>
                        <select id="clip-image-shapes-selection" class="image-clipping-box" style="width: 30%;" data-udraw="imageClippingSelection">
                            <option value="Circle" data-i18n="[html]menu_label.circle-shape" selected="selected"></option>
                            <option value="Rectangle" data-i18n="[html]menu_label.rect-shape"></option>
                            <option value="Triangle" data-i18n="[html]menu_label.triangle-shape"></option>
                        </select>
                        <div style="margin-top: 5px;">
                            <a href="#" class="btn" style="width: initial;" data-udraw="applyImageClippingMask"><span data-i18n="[html]button_label.clip-image"></span></a>
                            <a href="#" class="btn" style="width: initial;" data-udraw="removeImageClippingMask"><span data-i18n="[html]button_label.clip-image-remove"></span></a>
                        </div>
                        <div id="clip-image-shape-mask-control" style="margin-top: 15px;">
                            <div data-i18n="[html]text.clip-image-offset"></div>
                            <a href="#" class="btn clip-image-offset-btn" id="move-clip-image-up" style="margin-left: 30px;" data-udraw="imageClippingOffsetUp">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <div style="margin-left: -5px;">
                                <a href="#" class="btn clip-image-offset-btn" id="move-clip-image-left" data-udraw="imageClippingOffsetLeft">
                                    <i class="fa fa-chevron-left"></i>
                                </a>
                                <a href="#" class="btn clip-image-offset-btn" id="move-clip-image-right" style="margin-left: 30px;" data-udraw="imageClippingOffsetRight">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </div>
                            <a href="#" class="btn clip-image-offset-btn" id="move-clip-image-down" style="margin-left: 30px;" data-udraw="imageClippingOffsetDown">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
						<!--Test End-->
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
            <div class="modal" id="replace-image-modal" data-udraw="replaceImageModal">
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

            <!-- Facebook Images Dialog -->
            <div class="modal" id="facebook-images-modal" data-udraw="facebookModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span>Facebook Images</span></strong>
                            <div class="topRightContainer">
                                <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" 
                                     data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" 
                                     data-use-continue-as="false" onLogin="RacadDesigner.Facebook.get_login_status(function () { RacadDesigner.Facebook.get_albums(); });"></div>
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                        </div>
                        <div class="modal-body" style="max-height: 500px; overflow:auto;">
                            <div class="facebook_content">
                                <ul data-udraw="facebook_albums_list"></ul>
                                <ul data-udraw="facebook_photos_list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instagram Images Dialog -->
            <div class="modal" id="instagram-images-modal" data-udraw="instagramModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong style="font-size:large;"><span>Instagram Images</span></strong>
                            <div class="topRightContainer">
                                <div style="display: inline-block;">
                                    <a href="#" data-udraw="instagramLogin">Login / Authenticate</a>
                                    <a href="#" data-udraw="instagramLogout" style="display: none;">Logout</a>
                                </div>
                                <a href="#" data-dismiss="modal"><span data-i18n="[html]common_label.close"></span></a>
                            </div>
                        </div>
                        <div class="modal-body" style="max-height: 500px; overflow:auto;">
                            <div data-udraw="instagramContent" style="margin: auto;"></div>
                        </div>
                        <div class="modal-footer">
                            <div data-udraw="instagramSearchContainer" style="float: right; display: inline-block; display: none;">
                                <input type="text" data-udraw="instagramSearchInput" data-i18n="[placeholder]text.search-tags" />
                                <a href="#" data-udraw="instagramSearchButton" class="btn btn-default" data-i18n="[html]button_label.search"></a>
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
                                <i class="fa fa-spinner fa-pulse fa-5x"></i>
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
                            <div class="text-templates">
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

        <div id="udraw-preview-ui" style="display: none; padding-left: 30px;">
            <div class="row" style="padding-bottom: 15px;">
                <button class="btn button" id="udraw-preview-back-to-design-btn" style="color: #000 !important;"><i class="fa fa-chevron-left"></i><strong style="margin-left: 5px;">Back to Update Design</strong></button>
                <button class="btn button" id="udraw-preview-add-to-cart-btn" style="color: #000 !important;"><strong style="margin-right: 5px;">Approve &amp; Add to Cart</strong><i class="fa fa-chevron-right"></i></button>
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
    <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
    <input type="hidden" name="udraw_save_access_key" value="<?php echo (isset($_GET['udraw_access_key'])) ? $_GET['udraw_access_key'] : NULL; ?>" />
    <input type="hidden" name="udraw_is_saving_for_later" value="1" />
    <?php wp_nonce_field('save_udraw_customer_design'); ?>
</form>
<?php include_once(UDRAW_PLUGIN_DIR . '/designer/multi-udraw-templates.php'); ?>
<?php include_once(UDRAW_PLUGIN_DIR . '/designer/designer-template-script.php'); ?>

<style type="text/css">
    <?php echo $_udraw_settings['udraw_designer_css_hook']; ?>
</style>

<script>
    jQuery(document).ready(function () {
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

        jQuery('div.entry-summary form.cart div.quantity input').css('width', '5em');
        <?php echo $_udraw_settings['udraw_designer_js_hook']; ?>
    
        jQuery('#udraw-options-page-design-btn').on('click', function(){
            __initialize_slim();
        });
        <?php if (!$displayOptionsFirst) { ?>
            jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design', function(){
                __initialize_slim();
            });
        <?php } ?>
        jQuery('div.slim_modal').modal({
            'backdrop': false,
            'keyboard': false,
            'show' : false
        });
        jQuery('div.slim_modal').draggable();

    });
</script>