<?php

include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');

$uDraw = new uDraw();
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();
$isUpdate = (isset($_GET['cart_item_key'])) ? 'true' : 'false';
$friendly_item_name = get_the_title($post->ID);

global $woocommerce;
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
?>
<div id="designer-wrapper">
    <div id="udraw-bootstrap" style="display: none;" data-udraw="uDrawBootstrap">
        <?php
            //Apply extra action here
            do_action('udraw_frontend_extra_items', $post);
        ?>
        <div id="udraw-main-designer-ui" style="height: 96%; position: relative; padding: 0px;">
            <!--Designer Menu-->
            <div data-udraw="designerMenu">
                <div data-udraw="zoomContainer" class="zoom_container">
                    <i class="fas fa-search-minus right_space"></i>
                    <input type="range" data-udraw="zoomLevel" min="0.1" max="2.5" step="0.1" />
                    <i class="fas fa-search-plus left_space"></i>
                    <span data-udraw="zoomDisplay"></span>
                </div>
                <ul class="actions-list">
                    <?php if ($displayOptionsFirst || $templateCount > 1) { ?>
                    <li>
                        <a href="#" class="btn btn-default" id="show-udraw-display-options-ui-btn">
                            <i class="fas fa-chevron-left" style="font-size: 1.5em; vertical-align: middle; display: table-cell; color: #CCCCCC;"></i>
                            <div style="color: #999999">
                                <span style="font-weight: bold; font-size: 18px;">Back</span>
                                <br />
                                <span style="font-size: 11px;">to options</span>
                            </div>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($allowCustomerDownloadDesign) { ?>
                        <li>
                            <a href="#" class="btn btn-primary" data-udraw="downloadPDFButton">
                                <div style="padding: 1px; display: table-cell;">
                                    <span style="font-weight: bold; font-size: 14px;">Download</span>
                                    <br />
                                    <span style="font-size: 11px;">PDF</span>
                                </div>
                                <i class="fas fa-cloud-download-alt fa-2x" style="display: table-cell; vertical-align: middle;"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ((!$displayOptionsFirst || $displayOptionsFirst == '') && ($product_type == "variable" ||$isPriceMatrix)) { ?>
                    <li>
                        <a href="#" class="btn btn-success btn-sm designer-menu-btn" id="udraw-next-step-1-btn">
                            <div style="padding: 10px;">
                                <span id="udraw-next-step-1-btn-label">Next Step</span>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size: 1.5em; vertical-align: middle; display:  table-cell;"></i>
                        </a>
                    </li>
                    <?php } else { ?>
                    <li>
                        <?php if ($displayOptionsFirst == '' || !$displayOptionsFirst) { ?>
                        <form class="cart" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="" name="udraw_product">
                            <input type="hidden" value="" name="udraw_product_data">
                            <input type="hidden" value="" name="udraw_product_svg">
                            <input type="hidden" value="" name="udraw_product_preview">
                            <input type="hidden" value="" name="udraw_product_cart_item_key">
                            <div style="display: inline; vertical-align: middle; color:#fff; float:left; line-height:2.5;">
                                <label for="input-text" style="font-size: 1.25em; color:#fff;">Qty:</label>
                                <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" >
                                <!--<br>-->
                                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>">
                                <label for="product_total_price" style="font-size: 1.25em; color:#fff;">Price:</label>
                                <span style="font-size:1.5em; vertical-align:middle;"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                <span id="product_total_price" style="font-size:1.5em; vertical-align:middle;"><?php echo $product->get_price(); ?></span>
                                <span style="margin-left:60%;"></span>
                            </div>

                            <a href="#" class="btn btn-success" data-udraw="addToCart">
                                <div>
                                    <?php if (isset($_GET['cart_item_key'])) { ?>
                                        <span style="font-weight: bold; font-size: 18px;">Update</span>
                                        <br />
                                        <span style="font-size: 12px;">cart</span>
                                    <?php } else { ?>
                                        <span style="font-weight: bold; font-size: 18px;">Add</span>
                                        <br />
                                        <span style="font-size: 12px;">to cart</span>
                                    <?php } ?>
                                </div>

                                <i class="fas fa-chevron-right" style="font-size: 1.5em; vertical-align: middle; display:  table-cell;"></i>
                            </a>
                        </form>
                        <?php } else { ?>
                            <?php if ($allow_structure_file) { ?>
                                <a href="#" class="btn btn-success" data-udraw="excelContinue">
                                    <div>
                                        <span style="font-weight: bold; font-size: 18px;"><?php _e('Continue', 'udraw') ?></span>
                                        <br />
                                        <br />
                                    </div>
                                    <i class="fas fa-chevron-right" style="font-size: 1.5em; vertical-align: middle; display:  table-cell;"></i>
                                </a>
                            <?php } else { ?>
                                <a href="#" class="btn btn-success" data-udraw="addToCart">
                                    <div>
                                        <?php if (isset($_GET['cart_item_key'])) { ?>
                                            <span style="font-weight: bold; font-size: 18px;">Update</span>
                                            <br />
                                            <span style="font-size: 12px;">cart</span>
                                        <?php } else { ?>
                                            <span style="font-weight: bold; font-size: 18px;">Add</span>
                                            <br />
                                            <span style="font-size: 12px;">to cart</span>
                                        <?php } ?>
                                    </div>

                                    <i class="fas fa-chevron-right" style="font-size: 1.5em; vertical-align: middle; display:  table-cell;"></i>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <!--Designer Menu Ends-->
            <div class="main-designer-container">
            <div id="canvas-container" data-udraw="canvasContainer">
                <div style="display: inline-block; vertical-align: top; float: left">
                    <a href="#" data-udraw="undoButton" class="action-button"><div><i class="fas fa-undo fa-2x"><span data-i18n="[html]button_label.undo"></span></i></div></a>
                    <a href="#" data-udraw="redoButton" class="action-button"><div><i class="fas fa-redo fa-2x"><span data-i18n="[html]button_label.redo"></span></i></div></a>
                </div>
                
                <div class="float-toolbar">
                    <div class="inner-div" style="padding: 1px;">
                        <div class="text-items">
                            <div class="btn-group" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.font-style" data-udraw="fontFamilyContainer">
                                <select class="font-family-selection" name="font-family-selection" data-udraw="fontFamilySelector">
                                    <option value="Arial" style="font-family:'Arial';">Arial</option>
                                    <option value="Calibri" style="font-family:'Calibri';">Calibri</option>
                                    <option value="Times New Roman" style="font-family:'Times New Roman'">Times New Roman</option>
                                    <option value="Comic Sans MS" style="font-family:'Comic Sans MS';">Comic Sans MS</option>
                                    <option value="French Script MT" style="font-family:'French Script MT';">French Script MT</option>
                                </select>
                            </div>
                            <div class="btn-group" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.font-size" 
                                 data-udraw="fontSizeContainer">
                                <select class="dropdownList font-size-select-option" data-udraw="fontSizeSelector"></select>
                            </div>
                            <div class="btn-group" data-udraw="fontStyleContainer">
                                <button type="button" class="dropdown-toggle designer-toolbar-btn" data-toggle="dropdown">
                                    <i class="fas fa-bold"></i>&nbsp;&nbsp;
                                    <i class="fas fa-caret-down"></i>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a href="#" class="btn btn-default designer-toolbar-btn" data-udraw="boldButton"><i class="fas fa-bold"></i></a>
                                    <a href="#" class="btn btn-default designer-toolbar-btn" data-udraw="italicButton"><i class="fas fa-italic"></i></a>
                                    <a href="#" class="btn btn-default designer-toolbar-btn" data-udraw="underlineButton"><i class="fas fa-underline"></i></a>
                                    <a href="#" class="btn btn-default designer-toolbar-btn" style="text-decoration:overline" data-udraw="overlineButton">
                                        <span data-i18n="[html]menu_label.font-overline"></span>
                                    </a>
                                    <a href="#" class="btn btn-default designer-toolbar-btn" style="text-decoration:line-through" data-udraw="strikeThroughButton">
                                        <span data-i18n="[html]menu_label.font-linethrough"></span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div data-udraw="designerColourContainer">
                            <input type="text" value="#000000" data-opacity="1" class="standard-js-colour-picker text-colour-picker" 
                                   style="background-color: rgb(255, 255, 255);" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.object-colour-picker" 
                                   data-udraw="designerColourPicker">
                            <input type="hidden" data-opacity="1" data-udraw="restrictedColourPicker" style="display: none;" />
                        </div>
                        <div class="btn-group" data-udraw="objectAlignContainer">
                            <button type="button" class="dropdown-toggle designer-toolbar-btn" data-toggle="dropdown">
                                <i class="fas fa-object-group"></i>&nbsp;&nbsp;
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="min-width: 95px;">
                                <li>
                                    <a href="#" data-udraw="objectsAlignLeft"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_left.png" alt="Align Left" /></a>
                                    <a href="#" data-udraw="objectsAlignCenter"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_center.png" alt="Align Center" /></a>
                                    <a href="#" data-udraw="objectsAlignRight"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_right.png" alt="Align Right" /></a>
                                </li>
                                <li>
                                    <a href="#" data-udraw="objectsAlignTop"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_top.png" alt="Align Top" /></a>
                                    <a href="#" data-udraw="objectsAlignMiddle"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_middle.png" alt="Align Middle" /></a>
                                    <a href="#" data-udraw="objectsAlignBottom"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>bg_btn_align_bottom.png" alt="Align Bottom" /></a>
                                </li>
                                <li>
                                    <a class="designer-toolbar-btn" style="width: 30%;" data-udraw="textAlignLeft"><i class="fas fa-align-left"></i></a>
                                    <a class="designer-toolbar-btn" style="width: 30%;" data-udraw="textAlignCenter"><i class="fas fa-align-center"></i></a>
                                    <a class="designer-toolbar-btn" style="width: 30%;" data-udraw="textAlignRight"><i class="fas fa-align-right"></i></a>
                                    <a class="designer-toolbar-btn" style="width: 30%;" data-udraw="textAlignJustify"><i class="fas fa-align-justify"></i></a>
                                </li>
                            </ul>
                        </div>
                        <button type="button" data-udraw="duplicateBtn"><i class="fas fa-copy"></i></button>
                        <?php if (!$_udraw_settings['designer_disable_image_replace']) { ?>
                        <button type="button replaceImage" data-udraw="replaceImage"><i class="fas fa-retweet">&nbsp;</i><span data-i18n="[html]common_label.replace"></span></button>
                        <?php } ?>
                        <button type="button" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.delete" data-udraw="removeButton">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        <div class="row">
                            <div class="col-6 text-center object_rotation_container">
                                <div class="btn-group">
                                    <i class="fas fa-redo-alt right_space"></i>
                                    <input type="range" min="0" max="360" step="1" data-udraw="objectRotationSelector" />
                                </div>
                                <span data-udraw="objectRotationLabel"></span><span>&#176;</span>
                            </div>
                            <div class="col-6 text-center">
                                <div class="btn-group" data-udraw="rectangleCornerContainer">
                                    <i class="fas fa-square right_space"></i>
                                    <input type="range" min="0" max="50" step="1" data-udraw="rectangleCornerSelector" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="racad-designer" data-udraw="canvasWrapper">
                    <div class="alert alert-danger fade in" role="alert" style="display:none;padding: 5px;" data-udraw="outsideAlert">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true" data-i18n="[html]text.objects-outside-dismiss"></span>
                            <span class="sr-only">Close</span>
                        </button>
                        <p data-i18n="[html]text.objects-outside-description"></p>
                    </div>
                    <?php if (!$_udraw_settings['designer_disable_ruler']) { ?>
                        <table>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <canvas data-udraw="topRuler"></canvas>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <canvas data-udraw="sideRuler"></canvas>
                                    </td>
                                    <td>
                                        <canvas id="racad-designer-canvas" width="504" height="288" data-udraw="canvas"></canvas>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <canvas id="racad-designer-canvas" width="504" height="288" data-udraw="canvas"></canvas>
                    <?php } ?>
                </div>
            </div>
            <div class="element-panel">
                <div>
                    <ul style="padding: 0px;" class="element-list">
                        <li class="element-btn col-xs-2">
                            <a href="#" class="text">
                                <i class="fas fa-font"></i>
                                <span data-i18n="[html]common_label.text" class="desktop_only"></span>
                            </a>
                        </li>
                        <?php if (!$_udraw_settings['designer_disable_shapes']) { ?>
                        <li class="element-btn col-xs-2">
                            <a href="#" class="shape">
                                <i class="fas fa-circle"></i>
                                <span data-i18n="[html]common_label.shapes" class="desktop_only"></span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="element-btn col-xs-2">
                            <a href="#" class="image">
                                <i class="fas fa-image"></i>
                                <span data-i18n="[html]common_label.image" class="desktop_only"></span>
                            </a>
                        </li>
                        <li class="element-btn col-xs-2">
                            <a href="#" class="layers">
                                <i class="fas fa-list"></i>
                                <span data-i18n="[html]common_label.layers" class="desktop_only"></span>
                            </a>
                        </li>
                        <li class="element-btn col-xs-2">
                            <a href="#" class="pages">
                                <i class="fas fa-file"></i>
                                <span data-i18n="[html]common_label.pages" class="desktop_only"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="elements-container">
                    <div class="text-container" style="display: none; overflow: auto;">
                        <div>
                            <div>
                                <div class="element-btn col-xs-2">
                                    <a href="#" data-udraw="addText">
                                        <i class="fas fa-2x fa-font"></i>
                                        <span data-i18n="[html]common_label.text"></span>
                                    </a>
                                </div>
                                <div class="element-btn col-xs-2">
                                    <a href="#" data-udraw="addCurvedText">
                                    <i class="fas fa-bezier-curve"></i>
                                        <span data-i18n="[html]menu_label.curved_text"></span>
                                    </a>
                                </div>
                                <!--Textbox-->
                                <div class="element-btn col-xs-2">
                                    <a href="#" data-udraw="addTextbox">
                                        <i class="fas fa-2x fa-i-cursor"></i>
                                        <span data-i18n="[html]menu_label.textbox"></span>
                                    </a>
                                </div>
                                
                                <div class="element-btn col-xs-2">
                                    <a href="#" data-udraw="addLink">
                                        <i class="fas fa-2x fa-link"></i>
                                        <span data-i18n="[html]menu_label.link"></span>
                                    </a>
                                </div>
                                <div class="element-btn col-xs-2">
                                    <a href="#" data-udraw="textTemplates">
                                        <i class="fas fa-2x fa-file-word"></i>
                                        <span data-i18n="[html]menu_label.templates"></span>
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <div>
                                <textarea class="form-control" style="margin-top:5px; margin-bottom: 5px; height:75px; resize: none;" data-udraw="textArea"></textarea>
                                <div style="display:none;" data-udraw="curvedTextContainer">
                                    <hr style="margin-bottom: 3px;margin-top: 14px;">
                                    <div style="padding: 5px;">
                                        <div style="width: 20%; display:inline-block;" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.curved-text-spacing">
                                            <span data-i18n="[html]text_label.curved-text-spacing"></span>
                                        </div>
                                        <input type="range" class="slider-class" style="width: 60%; display:inline-block;" data-udraw="curvedTextSpacing"/>
                                    </div>
                                    <div style="padding: 5px;">
                                        <div style="width: 20%; display:inline-block;" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.curved-text-radius">
                                            <span data-i18n="[html]text_label.curved-text-radius"></span>
                                        </div>
                                        <input type="range" class="slider-class" style="width: 60%; display:inline-block;" data-udraw="curvedTextRadius"/>
                                    </div>
                                    <div style="padding: 5px;">
                                        <div style="width: 20%; display:inline-block;" data-toggle="tooltip-top" data-i18n="[data-original-title]tooltip.curved-text-starting-angle">
                                            <span data-i18n="[html]text_label.curved-text-starting-angle"></span>
                                        </div>
                                        <input type="range" class="slider-class" style="width: 60%; display:inline-block;" data-udraw="curvedTextStartingAngle"/>
                                    </div>
                                    <a href="#" class="btn btn-default designer-toolbar-btn" data-toggle="tooltip-top" 
                                       data-i18n="[data-original-title]tooltip.flip_curve" style="display: inline-block; margin-top: 5px;" data-udraw="reverseCurve">
                                        <span data-i18n="[html]button_label.flip_curve"></span>
                                    </a>
                                </div>
                                <div data-udraw="link_row" class="hidden">
                                    <div><span data-i18n="[html]text.link_url"></span></div>
                                    <div><input type="text" data-udraw="link_href_input" placeholder="https://www.google.ca" style="width: 100px;" /></div>
                                </div>
                            </div>
                        </div>
                        <div data-udraw="layerLabelsModal" class="mobile_modal">
                            <div data-udraw="layerLabelsContent" style="text-align: left;"></div>
                        </div>
                    </div>
                    <div class="shape-container" style="display: none; overflow: auto;">
                        <div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addCircle">
                                    
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>circle-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.circle"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addRectangle">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>square-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.rect"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addTriangle">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>triangle-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.triangle"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addLine">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>line-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.line"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addCurvedLine">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>curved-line-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.curved_line"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addPolygon">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>octagon-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.polyshape"></span>
                                </a>
                            </div>
                            <div class="element-btn col-xs-2">
                                <a href="#" data-udraw="addStar">
                                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH ?>star-icon.png" class="shape-icon" />
                                    <span data-i18n="[html]menu_label.star"></span>
                                </a>
                            </div>
                        </div>
                        <hr />
                        <div>
                            <div data-udraw="polygonModal" class="mobile_modal" style="display: none;">
                                <div style="padding: 5px;">
                                    <label style="width: 30%; font-weight: normal; font-size: 14px;">
                                        <span data-i18n="[html]text.polygon-sides"></span>
                                    </label>
                                    <input type="number" min="3" value="3" data-udraw="polygonSideSelector" />
                                    <div style="padding: 5px;">
                                        <a href="#" class="btn btn-success" tabindex="3" data-udraw="polygonCreate"><span data-i18n="[html]common_label.create"></span></a>
                                        <a href="#" class="btn btn-danger" data-udraw="polygonCancel"><span data-i18n="[html]common_label.cancel"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image-container" style="display: none; overflow: auto;">
                        <div>
                            <ul style="padding: 0px;" class="image-list">
                                <li class="element-btn col-xs-2">
                                    <a href="#" class="uploadImage" onclick="RacadDesigner.triggerImageUpload();">
                                        <div>&nbsp;<span data-i18n="[html]common_label.upload-image"></span></div>
                                    </a>
                                    <input id="upload_image_button" type="file" style="display: none"/>
                                </li>
                                <li class="element-btn col-xs-2 active">
                                    <a href="#" class="local"><div>&nbsp;<span data-i18n="[html]common_label.local-storage"></span></div></a>
                                </li>
                                <li class="element-btn col-xs-2">
                                    <a href="#" class="stock_image" data-udraw="stock_image"><div>&nbsp;<span data-i18n="[html]menu_label.stock_image"></span></div></a>
                                </li>
                                <?php if ($_udraw_settings['designer_enable_facebook_photos']) {?>
                                <li class="element-btn col-xs-2">
                                    <a href="#" class="facebook"><div>&nbsp;<span data-i18n="[html]menu_label.facebook"></span></div></a>
                                </li>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
                                    <li class="element-btn col-xs-2">
                                        <a href="#" class="instagram"><div>&nbsp;<span data-i18n="[html]menu_label.instagram"></span></div></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="local-container inner-image-container" data-udraw="localImageList" style="display: block;"></div>

                        <div class="local-container stock_image-container inner-image-container" data-udraw="stockImageModal" style="display: none;">
                            <h4 data-i18n="[html]menu_label.stock_image"></h4>
                            <div class="element_container clipart" data-udraw="stock_image_modal">
                                <div class="search_container">
                                    <input type="text" data-i18n="[placeholder]search" data-udraw="stock_image_search_input" />
                                    <select data-udraw="stock_image_type">
                                        <option>Select Source</option>
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



                        <div class="facebook-container inner-image-container">
                            <div style="text-align: right;">
                                <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" 
                                     data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" 
                                     data-use-continue-as="false" onLogin="RacadDesigner.Facebook.get_login_status(function () { RacadDesigner.Facebook.get_albums(); });"></div>
                            </div>
                            <div class="facebook_content">
                                <ul data-udraw="facebook_albums_list"></ul>
                                <ul data-udraw="facebook_photos_list"></ul>
                            </div>
                        </div>
                        <div class="instagram-container inner-image-container">
                            <div style="display: block;">
                                <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogin">
                                    <i class="fab fa-instagram"></i>
                                    <span>Login / Authenticate</span>
                                </a>
                                <a href="#" class="btn btn-primary btn-xs" data-udraw="instagramLogout" style="display: none;">
                                    <i class="fab fa-instagram"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                            <div data-udraw="instagramSearchContainer" style="display: none;">
                                <input type="text" data-udraw="instagramSearchInput" data-i18n="[placeholder]text.search-tags"/>
                                <a href="#" data-udraw="instagramSearchButton" class="btn btn-default" data-i18n="[html]button_label.search"></a>
                            </div>
                            <div data-udraw="instagramContent" style="margin: auto;"></div>
                        </div>
                    </div>
                    <div class="layers-container" style="display: none; overflow: auto;">
                        <div data-udraw="multilayerImageModal" class="mobile_modal" style="display: none;">
                            <h4 data-i18n="[html]text_label.select_image"></h4>
                            <div class="panel-body designer-panel-body" style="height: 120px; overflow-y: auto;">
                                <ul style="padding-left: 0px;" data-udraw="multilayerImageContainer"></ul>
                            </div>
                        </div>
                        <div data-udraw="imageFilterModal" class="mobile_modal layers-inner-container" style="display: none;">
                            <h4 data-i18n="[html]header_label.image-filter-header" style="display: inline-block;"></h4>
                            <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxClose"><i class="fas fa-close"></i><span data-i18n="[html]common_label.close"></span></a>
                            <div id="designer-advanced-image-properties" style="display:block; font-size: 12px;">
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="grayscale"><span data-i18n="[html]button_label.grayscale"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="sepiaPurple"><span data-i18n="[html]button_label.purple-sepia"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="sepiaYellow"><span data-i18n="[html]button_label.yellow-sepia"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="sharpen"><span data-i18n="[html]button_label.sharpen"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="emboss"><span data-i18n="[html]button_label.emboss"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="blur"><span data-i18n="[html]button_label.blur"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="invert"><span data-i18n="[html]button_label.invert"></span></a>
                                <a href="#" class="btn btn-default designer-toolbar-btn" style="display: inline-block; width: 30%; margin-bottom: 5px;" data-udraw="clipImage"><span data-i18n="[html]button_label.clip-image"></span></a>

                                <div>
                                    <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="tint">
                                        <span data-i18n="[html]button_label.tint"></span>
                                    </a>
                                    <label style="width: 30%; margin-bottom: 5px;">
                                        <span data-i18n="[html]text_label.tint-colour"></span>
                                        <input type="hidden" data-opacity="1" data-udraw="tintColourPicker" />
                                    </label>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="brightness">
                                        <span data-i18n="[html]button_label.brightness"></span>
                                    </a>
                                    <label style="width: 30%;"><span data-i18n="[html]text_label.brightness-level"></span></label>
                                    <div class="slider-class" style="width: 30%" data-udraw="imageBrightnessLevel"></div>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="noise">
                                        <span data-i18n="[html]button_label.noise"></span>
                                    </a>
                                    <label style="width: 30%;"><span data-i18n="[html]text_label.noise-level"></span></label>
                                    <div class="slider-class" style="width: 30%" data-udraw="imageNoiseLevel"></div>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" data-udraw="pixelate">
                                        <span data-i18n="[html]button_label.pixelate"></span>
                                    </a>
                                    <label style="width: 30%;"><span data-i18n="[html]text_label.pixel-size"></span></label>
                                    <div class="slider-class" style="width: 30%" data-udraw="imagePixelateLevel"></div>
                                </div>
                                <div style="display: none">
                                    <a href="#" class="btn btn-default designer-toolbar-btn image-filter-btn" style="white-space: normal;" data-udraw="gradientTransparency">
                                        <span data-i18n="[html]button_label.gradient-transparency"></span>
                                    </a>
                                    <label style="width: 30%;"><span data-i18n="[html]text_label.transparency-level"></span></label>
                                    <div class="slider-class" style="width: 30%" data-udraw="imageGradientTransparencyLevel"></div>
                                </div>
                                <div>
                                    <a class="btn btn-default designer-toolbar-btn" style="white-space: normal; opacity: 0; cursor: default;" data-udraw="opacity"></a>
                                    <label style="width: 30%;"><span data-i18n="[html]text.opacity-level"></span></label>
                                    <div class="slider-class" style="width: 30%" data-udraw="opacityLevel"></div>
                                </div>
                            </div>
                        </div>
                        <div data-udraw="objectColouringModal" class="mobile_modal layers-inner-container" style="display: none;">
                            <a href="#" class="btn btn-default btn-xs" data-udraw="toolboxClose"><i class="fas fa-times"></i><span data-i18n="[html]common_label.close"></span></a>
                            <div>
                                <h4 data-i18n="[html]header_label.advanced-colouring-header" style="display: inline-block;"></h4>
                                <a href="#" class="btn btn-default" style="margin: 5px;" data-udraw="triggerObjectColouringUpload">
                                    <i class="fas fa-upload icon"></i>&nbsp; <span data-i18n="[html]button_label.upload-pattern"></span>
                                </a>
                                <input id="object-pattern-upload-btn" type="file" name="files[]" multiple style="display: none;" data-udraw="objectColouringUpload" />
                                <div class="panel-body designer-panel-body">
                                    <span data-i18n="[html]header_label.advanced-colouring-fill-header"></span>
                                    <div style="margin: 5px;" data-udraw="objectColouringFillContainer"></div>
                                    <span data-i18n="[html]header_label.advanced-colouring-stroke-header"></span>
                                    <div style="margin: 5px;" data-udraw="objectColouringStrokeContainer"></div>
                                </div>
                            </div>
                        </div>
                        <!--SVG image dialog-->
                        <div data-udraw="imageColouringModal" class="mobile_modal layers-inner-container" style="display: none;">
                            <a href="#" class="btn btn-default btn-xs" style="padding-top:0px;" data-udraw="toolboxClose">
                                <i class="fas fa-times"></i><span data-i18n="[html]common_label.close"></span>
                            </a>
                            <div class="panel-body designer-panel-body" style="height: 87px; overflow-y: auto; display: inline-block;" data-udraw="imageColourContainer"></div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-default btn-xs" style="padding-top:0px;" data-udraw="layersRefresh">
                                <i class="fas fa-refresh"></i>
                                <span data-i18n="[html]common_label.refresh"></span>
                            </a>
                            <div class="scroll-content panel-body designer-panel-body" style="padding: 5px; height:inherit; min-height:10px; max-height:500px;">
                                <ul class="layer-box" data-udraw="layersContainer"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="pages-container" style="display: none; overflow: auto;">
                        <div style="padding: 5px;">
                            <span data-i18n="[html]menu_label.background"></span>&nbsp;
                            <input type="hidden" data-opacity="1" data-udraw="backgroundColour" />
                        </div>
                        <div style="height: 90%!important;" data-udraw="pagesList"></div>
                    </div>
                    <!--Available Templates-->
                    <hr>
                    <div data-udraw="linkedTemplatesModal" class="widescreen_modal objectEditorContainer">
                        <label data-i18n="[html]header_label.linked-templates-header"></label>
                        <div data-udraw="linkedTemplatesContainer"></div>
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Replace Image -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="replaceImageModal" aria-hidden="true" data-udraw="replaceImageModal">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" data-i18n="[html]common_label.image"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <input class="replace-image-upload-btn" type="file" name="files[]" multiple="" accept="image/*">
                                <button type="button" class="btn btn-secondary replaceBtn">
                                    <i class="fas fa-desktop"></i>
                                    <span class="left_space" data-i18n="[html]button_label.device"></span>
                                </button>
                                <br />
                                <button type="button" class="btn btn-secondary" data-udrawImage="StockImages" data-udraw="stock_images">
                                    <i class="fas fa-images icon"></i>
                                    <span data-i18n="[html]menu_label.stock_image"></span>
                                </button>
                                <?php if ($_udraw_settings['designer_enable_facebook_photos']) { ?>
                                <br />
                                <button type="button" class="btn btn-secondary" data-udrawImage="Facebook" data-udraw="facebookPhotos">
                                    <i class="fab fa-facebook-square"></i>
                                    <span class="left_space" data-i18n="[html]menu_label.facebook"></span>
                                </button>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
                                <br />
                                <button type="button" class="btn btn-secondary" data-udrawImage="Instagram" data-udraw="instagramPhotos">
                                    <i class="fab fa-instagram"></i>
                                    <span class="left_space" data-i18n="[html]menu_label.instagram"></span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
                        
            <!-- Local storage -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="userUploadedModal" aria-hidden="true" data-udraw="userUploadedModal">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" data-i18n="[html]button_label.device"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input class="hidden" type="file" name="files[]" multiple data-udraw="uploadImage" />
                            <button type="button" class="btn btn-secondary trigger_image_upload">
                                <i class="fas fa-upload right_space"></i>
                                <span>Upload</span>
                            </button>
                            <div>
                                <ol data-udraw="localFoldersList" class="breadcrumb"></ol>
                            </div>
                            <div data-udraw="localImageList"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
                        
            <!-- Crop Modal -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="cropModal" aria-hidden="true" data-udraw="cropModal">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" data-i18n="[html]menu_label.image_cropping"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div data-udraw="crop_preview">
                                <img src="#" data-udraw="image_crop" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" data-udraw="crop_cancel" data-i18n="[html]common_label.cancel"></button>
                            <button type="button" class="btn btn-success" data-udraw="crop_apply" data-i18n="[html]button_label.crop"></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multipage PDF upload Modal -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="multipagePDFModal" aria-hidden="true" data-udraw="multipagePDFModal">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" data-i18n="[html]text_label.multipage_pdf"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row inner_container">
                                <div data-udraw="page_list_container" class="col-6 text-center">
                                    <div data-udraw="page_list"></div>
                                </div>
                                <div data-udraw="imported_images_container" class="col-6 text-center">
                                    <div data-udraw="imported_images_list"></div>
                                </div>
                            </div>
                            <div class="progress_div">
                                <span data-i18n="[html]common_label.progress" style="font-size: 5em; color: #aaa;"></span>
                                <i class="fas fa-spinner fa-pulse fa-5x"></i>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-udraw="multipage_import_apply" data-i18n="[html]common_label.apply"></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Load XML modal -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="load_xml_modal" aria-hidden="true" data-udraw="load_xml_modal">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body" style="text-align: center;">
                            <p data-i18n="[html]text.load_saved_xml"></p>
                            <button type="button" class="button" data-udraw="load_saved_xml" data-i18n="[html]common_label.yes"></button>
                            <button type="button" class="button" data-dismiss="modal" data-i18n="[html]common_label.no" data-udraw="not_load_saved_xml"></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Text Templates -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="textTemplatesModal" aria-hidden="true" data-udraw="textTemplatesModal">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" data-i18n="[html]text_label.text_templates"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <input type="text" data-udraw="textTemplateSearch" placeholder="Enter a keyword" class="form-control" />
                            </div>
                            <div>
                                <ul data-udraw="textTemplatesList"></ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.close"></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress Modal - Put it last -->
            <div class="modal overlay-modal" role="dialog" aria-labelledby="progressModal" aria-hidden="true" data-udraw="progressModal">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="105" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="udraw-preview-ui" style="display: none; padding-left: 30px;">
            <div class="row" style="padding-bottom: 15px;">
                <button class="btn button" id="udraw-preview-back-to-design-btn"><strong>&nbsp;Back to Update Design</strong></button>
                <button class="btn button" id="udraw-preview-add-to-cart-btn"><strong>Approve &amp; Add to Cart&nbsp;></strong></button>
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
<?php include_once(UDRAW_PLUGIN_DIR . '/designer/multi-udraw-templates.php'); ?>
<?php include_once(UDRAW_PLUGIN_DIR . '/designer/designer-template-script.php'); ?>

<style type="text/css">
    <?php echo $_udraw_settings['udraw_designer_css_hook']; ?>
</style>

<script>
    var isUpdate = (<?php echo $isUpdate; ?> === true) ? true : false ;
    var designing = false;
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
        jQuery('div.mobile_modal').modal({
            'backdrop': false,
            'keyboard': false,
            'show' : false
        });
    });

    __load_extra_functions = function () {
        jQuery('.float-toolbar').removeClass('active');
        jQuery(window).resize(function () {
            jQuery('[data-udraw="canvasContainer"]').css('height', jQuery('[data-udraw="canvasWrapper"]').css('height') * 1.5);
        }).trigger('resize');
        
        jQuery('.element-list > li > a, .image-list > li > a').click(function () {
            jQuery(this).parent().siblings().removeClass('active');
            jQuery(this).parent().addClass('active');
            var classType = jQuery(this).attr('class');
            if (classType === 'image') {
                RacadDesigner.Image.loadLocalImages(RacadDesigner.settings.assetPath);
                jQuery('[data-udraw="uDrawClipartFolderContainer"]').show();
                RacadDesigner.Image.initClipartCollection();
                RacadDesigner.Image.getPrivateClipart();
            }
            if (classType === 'clipart') {
                jQuery('[data-udraw="uDrawClipartFolderContainer"]').show();
                RacadDesigner.Image.initClipartCollection();
            }
            if (classType === 'openClipart') {
                RacadDesigner.Image.__loadOpenClipartRoutine(RacadDesigner.openClipartCurrentPage, RacadDesigner.openClipartSearchTerm);
            }
            if (classType === 'private-clipart') {
                RacadDesigner.Image.initPrivateClipartCollection();
            }
            if (classType === 'facebook') {
                RacadDesigner.Facebook.get_login_status(function () {
                    RacadDesigner.Facebook.get_albums();
                }, function () {
                    console.log('Logged out');
                });
            }
            jQuery(`div.${classType}-container`).show();
            jQuery(`div.${classType}-container`).siblings().each(function () {
                if (typeof jQuery(this).attr('class') !== 'undefined' && jQuery(this).attr('class').indexOf('container') > 0) {
                    jQuery(this).hide();
                }
            });
        });
        jQuery('.elements-container').children().hide();
        jQuery('[data-udraw="addPolygon"]').click(function () {
            jQuery('[data-udraw="polygonModal"]').show();
        });
        jQuery('.jQimage-upload-btn').hide();
        jQuery('ul#imagelist li.clipart').click(function(){
            jQuery('[data-udraw="uDrawClipartFolderContainer"]').show();
            RacadDesigner.Image.initClipartCollection();
        });
        if (jQuery('input[name="udraw_options_converted_pdf"]').val() == 'false') {
            jQuery('#udraw-options-page-design-btn').hide();
        } else {
            jQuery('#udraw-options-page-design-btn').show(); 
        }
        RacadDesigner.isMobile = true;
        jQuery('li.element-btn a.pages').trigger('click');
    }
    jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-undo udraw-redo udraw-switched-page', function(){
        jQuery('.float-toolbar').removeClass('active');
    });
</script>