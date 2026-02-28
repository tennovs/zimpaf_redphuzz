<?php 
    $udrawSettings = new uDrawSettings();
    $_udraw_settings = $udrawSettings->get_settings();
    
    $uDraw = new uDraw();
?>

<!--<a href="#" class="btn btn-warning btn-sm" data-udraw="replaceImage">
    <i class="fas fa-retweet"></i>
    <span class="desktop_only left_space" data-i18n="[html]common_label.replace"></span>
</a>-->

<a href="#" class="btn btn-warning btn-sm" data-udraw="editObject">
    <i class="fas fa-pencil-alt"></i>
</a>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark row" data-udraw="designerMenu">
    <div class="col-8">
        <?php if (isset($load_frontend_navigation)) { ?>
            <?php include_once(UDRAW_PLUGIN_DIR . '/designer/bootstrap-default/designer-frontend-navbar.php'); ?>
        <?php } else { ?>
            <!-- Otherwise load the admin menu bar -->
            <div class="dropdown">
                <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                    <span class="desktop_only left_space" data-i18n="[html]menu_label.menu"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-udraw="settingsButton" href="#">
                        <i class="fas fa-tools"></i>
                        <span class="left_space" data-i18n="[html]menu_label.settings"></span>
                    </a>
                    <?php $currentURL = $_SERVER['REQUEST_URI']; 
                    $exists = strpos($currentURL, 'admin.php?page=udraw_add_template'); 
                    if ($exists === false) {?>
                    <a class="dropdown-item" data-udraw="saveButton" href="#">
                        <i class="far fa-save"></i>
                        <span class="left_space" data-i18n="[html]common_label.save-continue"></span>
                    </a>
                    <a class="dropdown-item" data-udraw="SaveCloseButton" href="#">
                        <i class="fas fa-angle-left"></i>
                        <span class="left_space" data-i18n="[html]common_label.save-close"></span>
                    </a>
                    <?php } else { ?>
                    <a class="dropdown-item" data-udraw="SaveCloseButton" href="#">
                        <i class="fas fa-angle-left"></i>
                        <span class="left_space" data-i18n="[html]common_label.save-template"></span>
                    </a>
                    <?php } ?>
                    <?php if (isset($allowCustomerDownloadDesign) &&  $allowCustomerDownloadDesign) {?>
                    <a class="dropdown-item" data-udraw="downloadPDFButton" href="#">
                        <i class="fas fa-cloud-download-alt"></i>
                        <span class="left_space" data-i18n="[html]button_label.download_pdf"></span>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-list"></i>
                    <span class="desktop_only left_space" data-i18n="[html]menu_label.view"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-udraw="boundingBox" href="#">
                        <i class="far fa-square"></i>
                        <span class="left_space" data-i18n="[html]menu_label.bounding_box"></span>
                    </a>
                    <a class="dropdown-item" data-udraw="toggleRuler" href="#">
                        <i class="fas fa-ruler-combined"></i>
                        <span class="left_space" data-i18n="[html]menu_label.ruler"></span>
                    </a>
                    <a class="dropdown-item" data-udraw="snapToGrid" href="#">
                        <input type="checkbox" data-udraw="snapCheckbox">
                        <span class="left_space" data-i18n="[html]menu_label.snap_to_grid"></span>
                    </a>
                    <a class="dropdown-item" data-udraw="toggleGridLines" href="#">
                        <input type="checkbox" data-udraw="gridCheckbox">
                        <span class="left_space" data-i18n="[html]menu_label.toggle_grid"></span>
                    </a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-folder-open"></i>
                    <span class="desktop_only left_space" data-i18n="[html]menu_label.templates">Templates</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" data-udraw="browseTemplate" href="#">
                        <span class="left_space" data-i18n="[html]menu_label.udraw_templates"></span>
                    </a>
                    <a class="dropdown-item" data-udraw="browsePrivateTemplate" href="#">
                        <span class="left_space" data-i18n="[html]menu_label.private_templates"></span>
                    </a>
                </div>
            </div>

            <button class="btn bg-light toggle_pages_layers">
                <i class="far fa-file"></i>
                <span>/</span>
                <i class="fas fa-list"></i>
            </button>
        <?php } ?>
    </div>

    <div class="col-4 text-right" data-udraw="versionContainer">
        <button id="full-screen" type="button" class="btn btn-success">
            <i class="fas fa-expand"></i>
            <span class="desktop_only left_space" data-i18n="[html]menu_label.expand"></span>
        </button>
        <span class="small" data-udraw="designerVersion"></span>
    </div>
</nav>

<div class="body_block">
    <nav class="navbar navbar-dark bg-dark sidebar col-2 col-md-2">
        <div class="dropdown dropright">
            <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-font"></i>
                <span class="desktop_only left_space" data-i18n="[html]common_label.text"></span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-udraw="addText" href="#">
                    <i class="fas fa-font"></i>
                    <span class="left_space" data-i18n="[html]common_label.text"></span>
                </a>
                <a class="dropdown-item" data-udraw="addCurvedText" href="#">
                    <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>arc-text-black.png" style="margin-top: -10px;" />
                    <span data-i18n="[html]menu_label.curved_text"></span>
                </a>
                <a class="dropdown-item" data-udraw="addTextbox" href="#">
                    <i class="fas fa-i-cursor"></i>
                    <span class="left_space" data-i18n="[html]menu_label.textbox"></span>
                </a>
                <a class="dropdown-item" data-udraw="addLink" href="#">
                    <i class="fas fa-link"></i>
                    <span class="left_space" data-i18n="[html]menu_label.link"></span>
                </a>
                <a class="dropdown-item" data-udraw="textTemplates" href="#">
                    <i class="far fa-file-word"></i>
                    <span class="left_space" data-i18n="[html]menu_label.templates"></span>
                </a>
            </div>
        </div>

        <div class="dropdown dropright">
            <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="far fa-images"></i>
                <span class="desktop_only left_space" data-i18n="[html]common_label.image"></span>
            </button>
            <div class="dropdown-menu">
                <input class="hidden" type="file" name="files[]" multiple data-udraw="uploadImage" />
                <a class="dropdown-item trigger_image_upload" data-udrawImage="Upload" href="#">
                    <i class="fas fa-desktop"></i>
                    <span class="left_space" data-i18n="[html]button_label.device"></span>
                </a>
                <a class="dropdown-item" data-udrawImage="StockImages" data-udraw="stock_image" href="#">
                    <i class="fas fa-images icon"></i>
                    <span data-i18n="[html]menu_label.stock_image"></span>
                </a>
                <?php if ($_udraw_settings['designer_enable_facebook_photos']) { ?>
                <a class="dropdown-item" data-udrawImage="Facebook" data-udraw="facebookPhotos" href="#">
                    <i class="fab fa-facebook-square"></i>
                    <span class="left_space" data-i18n="[html]menu_label.facebook"></span>
                </a>
                <?php } ?>
                <?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
                <a class="dropdown-item" data-udrawImage="Instagram" data-udraw="instagramPhotos" href="#">
                    <i class="fab fa-instagram"></i>
                    <span class="left_space" data-i18n="[html]menu_label.instagram"></span>
                </a>
                <?php } ?>
                <a class="dropdown-item" data-udraw="imagePlaceHolder" href="#">
                    <i class="far fa-image"></i>
                    <span class="left_space" data-i18n="[html]menu_label.image_placeholder"></span>
                </a>
                <a class="dropdown-item" data-udraw="qrCode" href="#">
                    <i class="fas fa-qrcode"></i>
                    <span class="left_space" data-i18n="[html]common_label.QRcode"></span>
                </a>
            </div>
        </div>

        <div class="dropdown dropright">
            <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-shapes"></i>
                <span class="desktop_only left_space" data-i18n="[html]common_label.shapes"></span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-udraw="addCircle" href="#">
                    <i class="fas fa-circle"></i>
                    <span class="left_space" data-i18n="[html]menu_label.circle"></span>
                </a>
                <a class="dropdown-item" data-udraw="addRectangle" href="#">
                    <i class="fas fa-square"></i>
                    <span class="left_space" data-i18n="[html]menu_label.rect"></span>
                </a>
                <a class="dropdown-item" data-udraw="addTriangle" href="#">
                    <i class="fas fa-play"></i>
                    <span class="left_space" data-i18n="[html]menu_label.triangle"></span>
                </a>
                <a class="dropdown-item" data-udraw="addLine" href="#">
                    <i class="fas fa-grip-lines"></i>
                    <span class="left_space" data-i18n="[html]menu_label.line"></span>
                </a>
                <a class="dropdown-item" data-udraw="addCurvedLine" href="#">
                    <i class="fas fa-bezier-curve"></i>
                    <span class="left_space" data-i18n="[html]menu_label.curved_line"></span>
                </a>
                <a class="dropdown-item" data-udraw="addPolygon" href="#">
                    <i class="fas fa-draw-polygon"></i>
                    <span class="left_space" data-i18n="[html]menu_label.polyshape"></span>
                </a>
                <a class="dropdown-item" data-udraw="addStar" href="#">
                    <i class="fas fa-star"></i>
                    <span class="left_space" data-i18n="[html]menu_label.star"></span>
                </a>
            </div>
        </div>

        <div class="dropdown dropright">
            <button class="btn bg-light dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-magic"></i>
                <span class="desktop_only left_space" data-i18n="[html]menu_label.effects"></span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-udraw="gradientButton" href="#">
                    <i class="fas fa-barcode"></i>
                    <span class="left_space" data-i18n="[html]menu_label.gradient"></span>
                </a>
                <a class="dropdown-item" data-udraw="shadowButton" href="#">
                    <i class="fas fa-clone"></i>
                    <span class="left_space" data-i18n="[html]menu_label.shadow"></span>
                </a>
            </div>
        </div>
    </nav>

    <div class="canvas_container col-9 col-lg-6">
        <div data-udraw="canvasWrapper">
            <table>
                <tbody>
                    <tr>
                        <td></td>
                        <td><canvas data-udraw="topRuler"></canvas></td>
                    </tr>
                    <tr>
                        <td><canvas data-udraw="sideRuler"></canvas></td>
                        <td>
                            <canvas width="504" height="288" data-udraw="canvas"></canvas>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="zoom_container">
            <i class="fas fa-search-minus right_space" data-udraw="decreaseZoomButton"></i>
            <input type="range" data-udraw="zoomLevel" min="0.1" max="2.5" step="0.1" />
            <i class="fas fa-search-plus left_space" data-udraw="increaseZoomButton"></i>
            <br />
            <span data-udraw="zoomDisplay"></span>
        </div>
    </div>

    <div class="tools_container col-11 col-lg-4">
        <div class="text-center close_tools">
            <a class="close_tools_btn" href="#">
                <i class="fas fa-times fa-2x"></i>
            </a>
        </div>
        <div class="tools">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#tools"
                    aria-expanded="false" aria-controls="tools">
                Tools
            </button>
            <div id="tools" class="collapse multi-collapse">
                <div class="btn-group" data-udraw="designerColourContainer">
                    <input type="text" readonly value="#000000" data-opacity="1" data-udraw="designerColourPicker" class="colourwheel">
                    <input type="hidden" data-opacity="1" data-udraw="restrictedColourPicker" />
                </div>
                <div class="btn-group">
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignLeft"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-left-white.png" alt="Align Left" /></a>
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignCenter"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-horizontal-middle-white.png" alt="Align Center" /></a>
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignRight"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-right-white.png" alt="Align Right" /></a>
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignTop"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-top-white.png" alt="Align Top" /></a>
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignMiddle"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-vertical-middle-white.png" alt="Align Middle" /></a>
                    <a href="#" class="btn btn-secondary" data-udraw="objectsAlignBottom"><img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>align-bottom-white.png" alt="Align Bottom" /></a>
                </div>
                <div class="btn-group">
                    <a href="#" class="btn btn-secondary" data-udraw="undoButton"><i class="fas fa-undo"></i></a>
                    <a href="#" class="btn btn-secondary" data-udraw="redoButton"><i class="fas fa-redo"></i></a>
                    <a href="#" class="btn btn-secondary" data-udraw="removeButton"><i class="far fa-trash-alt"></i></a>
                    <a href="#" class="btn btn-secondary" data-udraw="duplicateButton"><i class="far fa-copy"></i></a>
                </div>
                <div class="row">
                    <div class="col-6 text-center">
                        <div class="btn-group">
                            <i class="fas fa-compress-arrows-alt right_space"></i>
                            <input type="range" min="0.05" max="10" step="0.05" data-udraw="objectScaleSelector" />
                            <i class="fas fa-expand-arrows-alt left_space"></i>
                        </div>
                        <span data-udraw="objectScaleLabel"></span><span>x</span>
                    </div>
                    <div class="col-6 text-center">
                        <div class="btn-group">
                            <i class="fas fa-redo-alt right_space"></i>
                            <input type="range" min="0" max="360" step="1" data-udraw="objectRotationSelector" />
                        </div>
                        <span data-udraw="objectRotationLabel"></span><span>º</span>
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

        <div class="modal tools_modal" data-udraw="textModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#text"
                    aria-expanded="false" aria-controls="layers" data-i18n="[html]common_label.text">
                Text
            </button>
            <div id="text" class="collapse multi-collapse">
                <textarea class="form-control" data-udraw="textArea"></textarea>
                <hr />
                <div class="btn-group" data-udraw="fontFamilyContainer">
                    <select class="form-control" data-udraw="fontFamilySelector">
                        <option value="Arial" style="font-family:'Arial';">Arial</option>
                        <option value="Calibri" style="font-family:'Calibri';">Calibri</option>
                        <option value="Times New Roman" style="font-family:'Times New Roman'">Times New Roman</option>
                        <option value="Comic Sans MS" style="font-family:'Comic Sans MS';">Comic Sans MS</option>
                        <option value="French Script MT" style="font-family:'French Script MT';">French Script MT</option>
                    </select>
                </div>
                <div class="btn-group" data-udraw="fontSizeContainer">
                    <select class="form-control" data-udraw="fontSizeSelector"></select>
                </div>
                <div class="btn-group" data-udraw="fontHeightContainer">
                    <span><i class="fas fa-text-height"></i></span>
                    <select class="form-control" data-udraw="fontHeightSelector"></select>
                </div>
                <div class="btn-group" data-udraw="spacing_row">
                    <i class="fas fa-font"></i>
                    <i class="fas fa-arrows-alt-h"></i>
                    <i class="fas fa-font right_space"></i>
                    <input type="number" data-udraw="letterSpaceInput" class="form-control" />
                </div>
                <div class="btn-group" data-udraw="designerColourContainer">
                    <input type="text" readonly value="#000000" data-opacity="1" data-udraw="designerColourPicker" class="colourwheel">
                    <input type="hidden" data-opacity="1" data-udraw="restrictedColourPicker" />
                </div>
                <div class="btn-group" data-udraw="fontStyleContainer">
                    <a class="btn btn-secondary" data-udraw="boldButton"><i class="fas fa-bold"></i></a>
                    <a class="btn btn-secondary" data-udraw="italicButton"><i class="fas fa-italic"></i></a>
                    <a class="btn btn-secondary" data-udraw="underlineButton"><i class="fas fa-underline"></i></a>
                    <a class="btn btn-secondary" data-udraw="overlineButton"><span style="text-decoration: overline; font-weight: bold;">O</span></a>
                    <a class="btn btn-secondary" data-udraw="strikeThroughButton"><i class="fas fa-strikethrough"></i></a>
                </div>
                <div class="btn-group" data-udraw="fontAlignContainer">
                    <a class="btn btn-secondary" data-udraw="textAlignLeft"><i class="fas fa-align-left"></i></a>
                    <a class="btn btn-secondary" data-udraw="textAlignCenter"><i class="fas fa-align-center"></i></a>
                    <a class="btn btn-secondary" data-udraw="textAlignRight"><i class="fas fa-align-right"></i></a>
                </div>
                <div class="btn-group hidden" data-udraw="link_row">
                    <i class="fas fa-link right_space"></i>
                    <input type="text" data-udraw="link_href_input" class="form-control" placeholder="https://www.google.ca" style="width: 100px;" />
                </div>

                <div data-udraw="curvedTextContainer">
                    <div class="btn-group">
                        <i class="fas fa-font"></i>
                        <i class="fas fa-arrows-alt-h"></i>
                        <i class="fas fa-font right_space"></i>
                        <input type="range" min="0" max="30" step="1" data-udraw="curvedTextSpacing" />
                    </div>
                    <div class="btn-group">
                        <i class="fas fa-circle-notch right_space"></i>
                        <input type="range" min="0" max="50" step="1" data-udraw="curvedTextRadius" />
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" data-udraw="reverseCurve">
                            <span>Reverse Curve</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="shadowModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#shadow"
                    aria-expanded="false" aria-controls="shadow" data-i18n="[html]menu_label.shadow">
            </button>
            <div id="shadow" class="collapse multi-collapse">
                <div class="row" style="margin: 0;">
                    <div class="col-6">
                        <i class="fas fa-arrows-alt-h right_space"></i>
                        <input type="range" data-udraw="shadowOffsetX" min="-50" max="50" step="1" />
                    </div>
                    <div class="col-6">
                        <i class="fas fa-arrows-alt-v right_space"></i>
                        <input type="range" data-udraw="shadowOffsetY" min="-50" max="50" step="1" />
                    </div>
                    <div class="col-6">
                        <i class="fas fa-tint right_space"></i>
                        <input type="range" data-udraw="shadowBlur" min="0" max="20" step="1" />
                    </div>
                    <div class="col-6">
                        <i class="fas fa-palette right_space"></i>
                        <input type="hidden" data-opacity="1" data-udraw="shadowColourPicker" />
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-udraw="shadowRemove">
                            <i class="fas fa-times right_margin"></i>
                            <span data-i18n="[html]common_label.remove"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="gradientModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#gradient"
                    aria-expanded="false" aria-controls="gradient" data-i18n="[html]menu_label.gradient">
            </button>
            <div id="gradient" class="collapse multi-collapse">
                <div data-udraw="gradientContainer"></div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="imageFilterModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#imageFilters"
                    aria-expanded="false" aria-controls="imageFilters" data-i18n="[html]button_label.image_filters">
            </button>
            <div id="imageFilters" class="collapse multi-collapse">
                <div data-udraw="image_filters_container"></div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="imageColouringModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#svgColouring"
                    aria-expanded="false" aria-controls="svgColouring" data-i18n="[html]button_label.svg_colouring">
            </button>
            <div id="svgColouring" class="collapse multi-collapse">
                <div data-udraw="imageColourContainer"></div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="imageClippingModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#imageClipping"
                    aria-expanded="false" aria-controls="imageClipping" data-i18n="[html]button_label.image_clipping">
            </button>
            <div id="imageClipping" class="collapse multi-collapse">
                <select class="form-control" data-udraw="imageClippingSelection">
                    <option value="Circle" selected="selected" data-i18n="[html]menu_label.circle"></option>
                    <option value="Rectangle" data-i18n="[html]menu_label.rect"></option>
                    <option value="Triangle" data-i18n="[html]menu_label.triangle"></option>
                </select>

                <div class="input-group mb-3">
                    <div class=" input-group-prepend">
                        <span class="input-group-text" data-i18n="[html]text_label.stroke_width"></span>
                    </div>
                    <input class="form-control" type="number" value="0.1" step="0.1" min="0" max="20" data-udraw="imageClippingStrokeWidth" />

                    <input type="color" value="#000000" data-opacity="1" data-udraw="imageClippingStrokeColour" />
                </div>

                <button type="button" class="btn btn-secondary" data-udraw="applyImageClippingMask">
                    <i class="fas fa-check right_space"></i>
                    <span data-i18n="[html]button_label.clip-image"></span>
                </button>

                <button type="button" class="btn btn-secondary" data-udraw="removeImageClippingMask">
                    <i class="fas fa-times right_space"></i>
                    <span data-i18n="[html]common_label.remove"></span>
                </button>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="boundingBoxModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#boundingBox"
                    aria-expanded="false" aria-controls="boundingBox" data-i18n="[html]button_label.bounding_box">
                Bounding Box
            </button>
            <div id="boundingBox" class="collapse multi-collapse">
                <div class="btn-group" data-udraw="boundingBoxCreateContainer">
                    <button type="button" class="btn btn-success" data-udraw="boundingBoxCreate">
                        <i class="fas fa-plus-circle right_space"></i>
                        <span data-i18n="[html]common_label.create"></span>
                    </button>
                </div>
                <div data-udraw="boundingBoxControlContainer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" data-udraw="boundingBoxLock">
                            <i class="fas fa-lock right_space"></i>
                            <span data-i18n="[html]common_label.lock"></span>
                        </button>
                        <button type="button" class="btn btn-info" data-udraw="boundingBoxUnlock">
                            <i class="fas fa-unlock right_space"></i>
                            <span data-i18n="[html]common_label.unlock"></span>
                        </button>
                        <button type="button" class="btn btn-danger" data-udraw="boundingBoxRemove">
                            <i class="fas fa-times right_space"></i>
                            <span data-i18n="[html]common_label.remove"></span>
                        </button>
                    </div>

                    <div class="input-group mb-3">
                        <div class=" input-group-prepend">
                            <span class="input-group-text" data-i18n="[html]text_label.stroke_width"></span>
                        </div>
                        <input class="form-control" type="number" value="0.1" step="0.1" min="0" max="20" data-udraw="boundingBoxSpinner" />

                        <input type="color" value="#000000" data-opacity="1" data-udraw="boundingBoxColourPicker" />
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="modal tools_modal" data-udraw="layersModal">-->
        <div data-udraw="layersModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#layers"
                    aria-expanded="false" aria-controls="layers" data-i18n="[html]common_label.layers">
            </button>
            <div id="layers" class="collapse multi-collapse">
                <ul class="layer-box" data-udraw="layersContainer"></ul>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="linkedTemplatesModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#linkedTemplates"
                    aria-expanded="false" aria-controls="linkedTemplates" data-i18n="[html]text_label.linked_templates">
            </button>
            <div id="linkedTemplates" class="collapse multi-collapse">
                <div data-udraw="linkedTemplatesContainer"></div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="layerLabelsModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#labels"
                    aria-expanded="false" aria-controls="labels" data-i18n="[html]menu_label.labeled_objects">
            </button>
            <div id="labels" class="collapse multi-collapse">
                <div data-udraw="layerLabelsContent"></div>
            </div>
        </div>

        <div class="modal tools_modal" data-udraw="multilayerImageModal">
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="#layered_image"
                    aria-expanded="false" aria-controls="layered_image" data-i18n="[html]text_label.select_image">
            </button>
            <div id="layered_image" class="collapse multi-collapse">
                <ul data-udraw="multilayerImageContainer" class="row"></ul>
            </div>
        </div>

        <div>
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="[data-udraw='background_container']"
                    aria-expanded="false" aria-controls="pages" data-i18n="[html]menu_label.background">
            </button>
            <div data-udraw="background_container" class="collapse multi-collapse">
                <div data-udraw="backgroundColourContainer">
                    <span data-i18n="[html]menu_label.select-background">Set Background Colour</span>&nbsp;
                    <input type="text" readonly="" value="#ffffff" data-opacity="1" class="standard-js-colour-picker text-colour-picker colourwheel" data-udraw="background_colour" data-i18n="[title]tooltip.background-colour" title="Change background colour">
                    <button data-udraw="clear_background" data-i18n="[title]menu_label.clear-background" title="Clear Background Colour"><i class="fas fa-times" style="color: red"></i></button>
                </div>

                <hr>

                <div data-udraw="uploadBackgroundImage">
                    <label class="btn btn-secondary" style="cursor: pointer">
                        <i class="fas fa-cloud-upload-alt"></i>&nbsp;<span data-i18n="[html]menu_label.upload-background">Upload Background</span>
                        <input id="uploadBackground" type="file" style="display: none">
                    </label>
                </div>
            </div>
        </div>

        <div>
            <button type="button" class="btn bg-light btn-outline-secondary" data-toggle="collapse" data-target="[data-udraw='pages_section']"
                    aria-expanded="false" aria-controls="pages" data-i18n="[html]common_label.pages">
            </button>
            <div data-udraw="pages_section" class="collapse multi-collapse">
                <button type="button" data-udraw="addPage" class="btn btn-success">
                    <i class="fas fa-plus-circle right_space"></i>
                    <span data-i18n="[html]button_label.add_page"></span>
                </button>
                <div data-udraw="pagesContainer">
                    <div data-udraw="pagesList"></div>
                    <div data-udraw="pagesEditContainer">
                        <div>
                            <input type="text" data-udraw="pageLabelInput" />
                            <button type="button" class="btn btn-success" data-udraw="pageLabelUpdate">
                                <i class="fas fa-check-circle right_space"></i>
                                <span class="desktop_only" data-i18n="[html]common_label.update"></span>
                            </button>
                            <button type="button" class="btn btn-danger" data-udraw="pageLabelCancel">
                                <i class="fas fa-times-circle right_space"></i>
                                <span class="desktop_only" data-i18n="[html]common_label.cancel"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End tools_container -->

</div>
<!-- Global Templates -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="templatesModal" aria-hidden="true" data-udraw="templatesModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.templates"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4" data-udraw="templatesCategoryList"></div>
                    <div class="col-8">
                        <h4 data-udraw="templatesCategoryTitle">
                            <span>Items</span>
                        </h4>
                        <div data-udraw="templatesContainer" class="row"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Private templates -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="privateTemplatesModal" aria-hidden="true" data-udraw="privateTemplatesModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.private_templates"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4" data-udraw="privateTemplatesCategoryList"></div>
                    <div class="col-8">
                        <div data-udraw="privateTemplatesContainer" class="row"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Local storage -->
<!--<div class="modal overlay-modal" role="dialog" aria-labelledby="userUploadedModal" aria-hidden="true" data-udraw="userUploadedModal">
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
                </button>-->
                <!--<div>
                    <ol data-udraw="localFoldersList" class="breadcrumb"></ol>
                </div>
                <div data-udraw="localImageList"></div>-->
            <!--</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>-->

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

<!-- Stock Image -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="stock_image_modal" aria-hidden="true" data-udraw="stock_image_modal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.image_library"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="search_container input-group">
                    <input type="text" class="form-control" data-i18n="[placeholder]search" data-udraw="stock_image_search_input" />

                    <select data-udraw="stock_image_type" class="form-control">
                        <option data-i18n="[html]text_label.select_source"></option>
                        <option value="pixabay">Pixabay</option>
                        <option value="pexel">Pexel</option>
                        <option value="unsplash">Unsplash</option>
                        <?php if (!$_udraw_settings['designer_disable_global_clipart']) { ?>
                        <option value="udraw_clipart" data-i18n="[html]common_label.clipart-collection"></option>
                        <?php } ?>
                        <?php if ($_udraw_settings['designer_enable_local_clipart']) { ?>
                        <option value="private" data-i18n="[html]button_label.private_image_library"></option>
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
                <?php if (!$_udraw_settings['designer_disable_global_clipart']) { ?>
                <div class="stock_image_container row" data-stock_image="udraw_clipart">
                    <div class="col-6" data-udraw="uDrawClipartFolderContainer"></div>
                    <div class="col-6 stock_image_list" data-udraw="uDrawClipartList"></div>
                </div>
                <?php } ?>
                <?php if ($_udraw_settings['designer_enable_local_clipart']) { ?>
                <div class="stock_image_container row" data-stock_image="private">
                    <ul class="stock_image_list col-6" data-stock_image="private_category"></ul>
                    <ul class="stock_image_list col-6" data-stock_image="private"></ul>
                </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- QR Code -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="qrModal" aria-hidden="true" data-udraw="qrModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]common_label.QRcode"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" style="margin: 0;">
                    <input type="text" class="form-control" tabindex="1" value="http://www.google.com/" data-udraw="qrInput" />
                    <input type="hidden" value="#000000" data-udraw="qrColourPicker" />
                    <button type="button" class="btn btn-info" data-udraw="qrRefreshButton">
                        <i class="fas fa-sync right_space"></i>
                        <span data-i18n="[html]common_label.refresh"></span>
                    </button>
                </div>
                <div data-udraw="qrPreviewContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.close"></button>
                <button type="button" class="btn btn-success" data-udraw="qrAddButton" data-i18n="[html]common_label.add"></button>
            </div>
        </div>
    </div>
</div>
<!-- Wizard -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="wizardModal" aria-hidden="true" data-udraw="wizardModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]wizard.product_wizard"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" style="padding: 1%;">
                    <div class="col-7">
                        <ul class="row" data-udraw="wizardProductList">
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('bc');">
                                        <span data-i18n="[html]wizard.business-card"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/BC.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('brochure');">
                                        <span data-i18n="[html]wizard.brochure"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Brochures.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('envelope');">
                                        <span data-i18n="[html]wizard.envelopes"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Envelopes.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('flyers');">
                                        <span data-i18n="[html]wizard.flyers"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Flyers.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('gc');">
                                        <span data-i18n="[html]wizard.greetings-card"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/GreetingsCards.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('lh');">
                                        <span data-i18n="[html]wizard.letter-head"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Letterheads.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('postcard');">
                                        <span data-i18n="[html]wizard.postcard"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Postcard.png" />
                                    </a>
                                </div>
                            </li>
                            <li class="col-4">
                                <div class="thumbnail">
                                    <a href="#" onclick="RacadDesigner.Wizard.ShowProductOptions('custom');">
                                        <span data-i18n="[html]wizard.custom"></span>
                                        <img src="<?php echo UDRAW_DESIGNER_IMG_PATH; ?>wizard/Upload.png" />
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-5 product_size_container">
                        <h2><span data-i18n="[html]wizard.product_size"></span></h2>
                        <hr />
                        <div class="wizard_size_options">
                            <div class="product_type" data-productType="bc">
                                <input type="radio" name="product-size" value="3.5,2,bc,2.5" checked="checked" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.2x3"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.horizontal"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="2,3.5,bc,1.85" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.2x3"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.vertical"></span>
                                </strong>
                            </div>
                            <div class="product_type" data-productType="brochure">
                                <input type="radio" name="product-size" value="11,8.5,brochure,0.8" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.11x8-5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.letter"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="14,8.5,brochure,0.6" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.14x8-5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.legal"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="17,11,brochure,0.5" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.17x11"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.tabloid"></span>
                                </strong>
                            </div>
                            <div class="product_type" data-productType="envelope">
                                <input type="radio" name="product-size" value="6,3.5,envelope,1.45" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.6x3-5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number6"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="7.5,3.875,envelope,1.15" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.7-5x3-875"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.monarch"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="8.875,3.875,envelope,1" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-875x3-875"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number9"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="9.5,4.125,envelope,0.9" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.9-5x4-125"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number10"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="10.375,4.5,envelope,0.85" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.10-375x4-5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number11"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="11,4.5,envelope,0.8" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.11x4-5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number12"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="11.5,5,envelope,0.75" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.11-5x5"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.number14"></span>
                                </strong>
                            </div>
                            <div class="product_type" data-productType="flyers">
                                <input type="radio" name="product-size" value="8.5,5.5,flyers,1.05" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-5x5-5"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="8.5,11,flyers,0.8" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-5x11"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="5.5,4,flyers,1.6" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.5-5x4"></span>
                                </strong>
                                <br />
                            </div>
                            <div class="product_type" data-productType="gc">
                                <input type="radio" name="product-size" value="5,3.5,gc,1.75" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.5x3-5"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="6,4,gc,1.45" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.6x4"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="5.5,4.25,gc,1.6" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.5-5x4-25"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="6,4.25,gc,1.45" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.6x4-25"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="7,5,gc,1.25" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.7x5"></span>
                                </strong>
                                <br />
                            </div>
                            <div class="product_type" data-productType="lh">
                                <input type="radio" name="product-size" value="8.5,11,lh,0.8" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-5x11"></span>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.standard"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="8.5,14,lh,0.65" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-5x14"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="11,17,lh,0.5" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.11x17"></span>
                                </strong>
                                <br />
                            </div>
                            <div class="product_type" data-productType="postcard">
                                <input type="radio" name="product-size" value="6,4,postcard,1.45" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.6x4"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="6,4.25,postcard,1.45" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.6x4-25"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="7,5,postcard,1.25" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.7x5"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="8.5,5.5,postcard,1.05" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.8-5x5-5"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="9,6,postcard,1" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.9x6"></span>
                                </strong>
                                <br />
                                <input type="radio" name="product-size" value="11,6,postcard,0.8" />
                                <strong>
                                    <span class="product-size-span left_space" data-i18n="[html]wizard.11x6"></span>
                                </strong>
                                <br />
                            </div>
                            <div class="product_type" data-productType="custom">
                                <div class="input-group mb-3">
                                    <div class=" input-group-prepend">
                                        <span class="input-group-text" data-i18n="[html]wizard.custom-width"></span>
                                    </div>
                                    <input type="text" class="form-control " tabindex="1" value="3.5" data-udraw="wizardWidth" />
                                    <div class=" input-group-append">
                                        <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                    </div>
                                </div>

                                <div class="input-group mb-3">
                                    <div class=" input-group-prepend">
                                        <span class="input-group-text" data-i18n="[html]wizard.custom-height"></span>
                                    </div>
                                    <input type="text" class="form-control " tabindex="1" value="2" data-udraw="wizardHeight" />
                                    <div class=" input-group-append">
                                        <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div data-udraw="wizardBleedContainer">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" data-i18n="[html]wizard.custom-bleed-header"></span>
                                </div>
                                <input type="text" class="form-control" tabindex="1" value="0" data-udraw="wizardBleed" />
                                <div class="input-group-append">
                                    <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span data-i18n="[html]text.measurement-unit"></span>
                            <select data-udraw="selectMeasurementUnit">
                                <option value="mm">mm</option>
                                <option value="cm">cm</option>
                                <option value="inch">inch</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <a href="#" class="btn btn-success" data-udraw="wizardCreate"><span data-i18n="[html]wizard.create-btn"></span></a>
            </div>
        </div>
    </div>
</div>
<!-- Document Settings -->
<div class="modal overlay-modal" role="dialog" aria-labelledby="settingsModal" aria-hidden="true" data-udraw="settingsModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.settings"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs settings_tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="[aria-labelledby='document-tab']" aria-controls="document" aria-selected="true">Document Size</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="[aria-labelledby='restrict-tab']" aria-controls="restrict" aria-selected="false">Restrict/Disable Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="[aria-labelledby='labels-tab']" aria-controls="labels" aria-selected="false">Object Labels</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" aria-labelledby="document-tab">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <div class="row document_settings_container">
                                    <div class="input-group mb-3 col-6 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-width"></span>
                                        </div>
                                        <input type="text" class="form-control" tabindex="1" data-udraw="documentWidth" />
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3 col-6 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-height"></span>
                                        </div>
                                        <input type="text" class="form-control" tabindex="1" data-udraw="documentHeight" />
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3 col-6 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-bleed"></span>
                                        </div>
                                        <input type="text" class="form-control" tabindex="1" data-udraw="documentBleed" />
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-i18n="[html]text_label.settings-inch"></span>
                                        </div>
                                    </div>

                                    <div data-udraw="settings_warning">
                                        <span data-i18n="[html]text.settings_warning_pt1"></span>
                                        <span data-udraw="ratio_suggest_num">0</span>
                                        <span data-i18n="[html]text.settings_warning_pt2"></span>
                                    </div>
                                </div>

                                <div class="checkbox col-sm-offset-1 checkbox col-sm-11 document-settings-container">
                                    <span data-i18n="[html]text.measurement-unit"></span>
                                    <select data-udraw="selectMeasurementUnit">
                                        <option value="mm" data-i18n="[html]text_label.settings-mm"></option>
                                        <option value="cm" data-i18n="[html]text_label.settings-cm"></option>
                                        <option value="inch" data-i18n="[html]text_label.settings-inch"></option>
                                    </select>
                                </div>
                                <div class="checkbox col-sm-offset-1 checkbox col-sm-11 document-settings-container">
                                    <span data-i18n="[html]text.canvas-pdf-ratio"></span>
                                    <br />
                                    <span>1 <span data-i18n="[html]text_label.settings-inch"></span> <span data-i18n="[html]text.canvas"></span> = </span>
                                    <select data-udraw="selectPDFratio">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="10">10</option>
                                        <option value="12">12</option>
                                    </select>
                                    <span data-i18n="[html]text_label.settings-inch"></span> <span>PDF</span>
                                    <br />
                                    <div class="pdf-dimensions-container" style="font-size: 12px;">
                                        <span data-i18n="[html]text.settings-pdf-dimensions" style="font-weight: bold;"></span><br />
                                        <table style="width: 25%; margin-left: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td><span data-i18n="[html]text_label.settings-width"></span></td>
                                                    <td><span data-udraw="pdfDimensionsWidth"></span></td>
                                                    <td><span data-i18n="[html]text_label.settings-inch"></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span data-i18n="[html]text_label.settings-height"></span></td>
                                                    <td><span data-udraw="pdfDimensionsHeight"></span></td>
                                                    <td><span data-i18n="[html]text_label.settings-inch"></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span data-i18n="[html]text_label.settings-bleed"></span></td>
                                                    <td><span data-udraw="pdfDimensionsBleed"></span></td>
                                                    <td><span data-i18n="[html]text_label.settings-inch"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="checkbox col-sm-offset-1 checkbox col-sm-11">
                                    <label>
                                        <input type="checkbox" data-udraw="documentGridCheckbox" />
                                        <span data-i18n="[html]text.settings-display-grid"></span>
                                    </label>
                                </div>
                                <div class="checkbox col-sm-offset-1 checkbox col-sm-11">
                                    <label>
                                        <input type="checkbox" data-udraw="documentCropCheckbox" />
                                        <span data-i18n="[html]text.settings-display-crop"></span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" role="tabpanel" aria-labelledby="restrict-tab">
                        <div data-udraw="restrictDisableContainer"></div>
                    </div>
                    <div class="tab-pane" role="tabpanel" aria-labelledby="labels-tab">
                        <div style="padding-left: 15px; padding-top: 15px;">
                            <strong><span data-i18n="[html]text.layers-labels"></span></strong>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" data-i18n="[html]text_label.layer-labels-name"></span>
                            </div>
                            <input type="text" class="form-control" data-udraw="layerLabelText" data-i18n="[placeholder]text.layer-labels-placeholder" />

                            <div class="input-group-prepend">
                                <span class="input-group-text" data-i18n="[html]text_label.layer-labels-type"></span>
                            </div>
                            <select class="form-control" data-udraw="layerLabelSelect">
                                <option value="text" data-i18n="[html]common_label.text"></option>
                                <option value="image" data-i18n="[html]common_label.image"></option>
                                <option value="qrcode" data-i18n="[html]common_label.QRcode"></option>
                                <option value="object" data-i18n="[html]common_label.object"></option>
                            </select>
                            <button type="button" class="btn btn-secondary" data-i18n="[html]common_label.add" data-udraw="addLayerLabel"></button>
                        </div>

                        <div class="restriction-list-container" data-udraw="layerLabelsList"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.cancel"></button>
                <button type="button" class="btn btn-success" data-udraw="documentSettingsUpdate" data-i18n="[html]common_label.update"></button>
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
                    <button type="button" class="btn btn-secondary" data-udrawImage="Upload" data-udraw="userUploadedImages">
                        <i class="fas fa-desktop"></i>
                        <span class="left_space" data-i18n="[html]button_label.device"></span>
                    </button>
                    <br />
                    <button type="button" class="btn btn-secondary" data-udrawImage="StockImages" data-udraw="stock_image">
                        <i class="fas fa-images icon"></i>
                        <span data-i18n="[html]menu_label.stock_images"></span>
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

<!-- Facebook Modal -->
<?php if ($_udraw_settings['designer_enable_facebook_photos']) { ?>
<div class="modal overlay-modal" role="dialog" aria-labelledby="facebookModal" aria-hidden="true" data-udraw="facebookModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.facebook"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fb-login-button"
                     data-size="small" data-button-type="login_with"
                     data-auto-logout-link="true" data-use-continue-as="false"
                     onLogin="RacadDesigner.Facebook.get_login_status(function () { RacadDesigner.Facebook.get_albums(); });"
                     style="float:right;">

                </div>
                <div class="row">
                    <ul data-udraw="facebook_albums_list" class="col-6"></ul>
                    <ul data-udraw="facebook_photos_list" class="col-6"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.close"></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Instagram Modal -->
<?php if ($_udraw_settings['designer_enable_instagram_photos']) { ?>
<div class="modal overlay-modal" role="dialog" aria-labelledby="instagramModal" aria-hidden="true" data-udraw="instagramModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" data-i18n="[html]menu_label.instagram"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-right">
                    <button type="button" class="btn" data-udraw="instagramLogin">
                        <i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                        <span data-i18n="[html]button_label.login_auth"></span>
                    </button>
                    <button type="button" class="btn" data-udraw="instagramLogout" style="display: none;">
                        <i class="fab fa-instagram" style="border-right: 1px solid #cccccc; margin-right: 5px; padding-right: 5px;"></i>
                        <span data-i18n="[html]button_label.logout"></span>
                    </button>
                </div>
                <div data-udraw="instagramContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.close"></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

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

<!-- Progress bar (put last) -->
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

<script>
    jQuery(document).ready(function () {
        jQuery('[data-udraw="uDrawBootstrap"]').on('udraw-loaded-design udraw-switched-page', function () {
            //Clears Char Width Cache
            fabric.charWidthsCache = {};
        });
    });
</script>