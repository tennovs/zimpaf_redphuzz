<canvas id="canvg_canvas" style="display: none;"></canvas>
<div data-udrawSVG="SVGDesigner">
    <div class="designer_menu row">
        <div class="col menu_item">
            <a href="#" class="btn" data-udrawSVG="back_to_options" >
                <i class="fas fa-chevron-left desktop_only"></i>
                <i class="fas fa-chevron-left mobile_only fa-2x"></i>
                <span class="desktop_only" style="margin-left: 5px;" data-i18n="[html]back_to_options"></span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-object_type="text">
                <i class="fas fa-font"></i>
                <br />
                <span>Text</span>
            </a>
        </div>
        <?php if (isset($allow_custom_objects) && $allow_custom_objects) { ?>
        <div class="col menu_item">
            <a href="#" class="btn" data-object_type="image">
                <i class="far fa-image"></i>
                <br />
                <span>Images</span>
            </a>
        </div>
        <?php } ?>
        <div class="col menu_item">
            <a href="#" class="btn" data-udrawSVG="undo_button">
                <i class="fas fa-undo"></i>
                <br />
                <span data-i18n="[html]undo"></span>
            </a>
        </div>
        <div class="col menu_item">
            <a href="#" class="btn" data-udrawSVG="redo_button">
                <i class="fas fa-redo"></i>
                <br />
                <span data-i18n="[html]redo"></span>
            </a>
        </div>
        <div class="col text-right menu_item">
            <a href="#" class="btn" data-udrawSVG="add_to_cart">
                <span class="desktop_only" style="margin-right: 5px;" data-i18n="[html]add_to_cart"></span>
                <i class="fas fa-shopping-cart desktop_only"></i>
                <i class="fas fa-shopping-cart fa-2x mobile_only"></i>
            </a>
        </div>
    </div>

    <div class="object_tools hidden" data-object_type="text">
        <div class="row">
            <textarea class="form-control" data-udrawSVG="text_area"></textarea>
        </div>
        <div class="row">
            <div class="col-4 col-md-2 menu_item">
                <select data-udrawSVG="font_family_select"></select>
            </div>
            <div class="col-4 col-md-2 menu_item">
                <select data-udrawSVG="font_size_select"></select>
            </div>
            <div class="col-4 col-md-2 menu_item">
                <input type="hidden" data-udrawSVG="colour_picker" />
            </div>
        </div>
        <div class="row">
            <a href="#" data-udrawSVG="bold_button" class="col menu_item">
                <div>
                    <i class="fas fa-bold"></i>
                </div>
            </a>
            <a href="#" data-udrawSVG="italic_button" class="col menu_item">
                <div>
                    <i class="fas fa-italic"></i>
                </div>
            </a>
            <a href="#" data-udrawSVG="underline_button" class="col menu_item">
                <div>
                    <i class="fas fa-underline"></i>
                </div>
            </a>
            <a href="#" data-text_align="start" class="col menu_item text_align_button">
                <div>
                    <i class="fas fa-align-left"></i>
                </div>
            </a>
            <a href="#" data-text_align="middle" class="col menu_item text_align_button">
                <div>
                    <i class="fas fa-align-center"></i>
                </div>
            </a>
            <a href="#" data-text_align="end" class="col menu_item text_align_button">
                <div>
                    <i class="fas fa-align-right"></i>
                </div>
            </a>
        </div>
        <div class="row">
            <a href="#" data-udrawSVG="remove_object" class="col menu_item">
                <div>
                    <i class="far fa-trash-alt"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="object_tools hidden" data-object_type="image">
        <div class="row">
            <a href="#" data-udrawSVG="edit_image" class="col menu_item">
                <div>
                    <i class="fas fa-crop"></i>
                </div>
            </a>
            <a href="#" data-udrawSVG="replace_image" class="col menu_item">
                <div>
                    <i class="fas fa-retweet"></i>
                </div>
            </a>
            <a href="#" data-udrawSVG="remove_object" class="col menu_item">
                <div>
                    <i class="far fa-trash-alt"></i>
                </div>
            </a>
        </div>
    </div>

    <div class="canvas_container">
        <table>
            <tbody>
                <tr>
                    <td></td>
                    <td>
                        <canvas class="top_ruler ruler" width="0" height="0"></canvas>
                    </td>
                </tr>
                <tr>
                    <td>
                        <canvas class="side_ruler ruler" width="0" height="0"></canvas>
                    </td>
                    <td>
                        <div id="svg_canvas"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="page_container">
        <ul data-udrawSVG="page_list"></ul>
    </div>

    <div class="modal fade" data-udrawSVG="user_image_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span data-i18n="[html]local_storage"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="local_image_container" style="display: inline-block; vertical-align: top; padding: 5px;">
                        <ul data-udrawSVG="local_image_list"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]cancel"></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-udrawSVG="edit_image_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal_header_content">
                        <a href="#" data-dismiss="modal">
                            <i class="fas fa-times"></i>
                            <span data-i18n="[html]cancel"></span>
                        </a>
                    </div>
                    <div class="modal_header_content">
                        <span data-i18n="[html]edit_image"></span>
                    </div>
                    <div class="modal_header_content">
                        <a href="#" data-udrawSVG="apply_edited_image">
                            <i class="fas fa-check"></i>
                            <span data-i18n="[html]apply"></span>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="cropping_container">
                        <div class="image_cropping_container">
                            <div data-udrawSVG="image_cropper" id="svg_cropper"></div>
                            <img data-udrawSVG="cropping_image" style="max-height: 20vh;">
                        </div>
                    </div>
                    <!-- Where tools such as apply crop, reset, rotate, zoom in/out will go -->
                    <div class="image_editing_tools">
                        <ul data-udrawSVG="image_editing_tools_list" class="cropping_tool_list">
                            <li>
                                <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="clear_cropper">
                                    <i class="fas fa-times"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="rotate_counterclockwise">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="rotate_clockwise">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="flip_x">
                                    <i class="fas fa-arrows-alt-h"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="flip_y">
                                    <i class="fas fa-arrows-alt-v"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="image_filters_container">
                        <ul data-udrawSVG="image_filters_list">
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="none">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>original.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]none"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="gaussian_blur">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>gaussian_blur.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]gaussian_blur"></span>
                                    <br />
                                    <input type="number" class="filter_modifier" data-filter_mod_type="gaussian_blur" min="0" max="100" step="1" value="50" /><span>%</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="horizontal_blur">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>horizontal_blur.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]horizontal_blur"></span>
                                    <br />
                                    <input type="number" class="filter_modifier" data-filter_mod_type="horizontal_blur" min="0" max="100" step="1" value="50" /><span>%</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="desaturate">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>desaturate.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]desaturate"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="contrast">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>contrast.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]contrast"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="sepiatone">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>sepiatone.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]sepiatone"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="hue_rotate">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>hue_rotate.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]hue_rotate"></span>
                                    <br />
                                    <input type="number" class="filter_modifier" data-filter_mod_type="hue_rotate" min="0" max="359" step="1" value="180" /><span>°</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="luminance_to_alpha">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>luminance_to_alpha.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]luminance_to_alpha"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="colourize">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>colourize.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]colourize"></span>
                                    <br />
                                    <input type="hidden" class="filter_modifier" data-filter_mod_type="colourize" value="#ff0000" />
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="posterize">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>posterize.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]posterize"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="darken">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>darken.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]darken"></span>
                                    <br />
                                    <input type="number" class="filter_modifier" data-filter_mod_type="darken" min="0" max="100" step="1" value="20" /><span>%</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="lighten">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>lighten.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]lighten"></span>
                                    <br />
                                    <input type="number" class="filter_modifier" data-filter_mod_type="lighten" min="0" max="100" step="1" value="20" /><span>%</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="invert">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>invert.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]invert"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="gamma_correct_1">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>gamma_correct_1.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]gamma_correct_1"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="image_filter_button btn btn-default" data-filter_type="gamma_correct_2">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>gamma_correct_2.png" class="image_filter_thumbnail" />
                                    <span data-i18n="[html]gamma_correct_2"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
    <div class="modal fade" data-udrawSVG="facebook_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" data-dismiss="modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                    <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="false" onLogin="RacadSVGDesigner.Facebook.get_login_status(function () { RacadSVGDesigner.Facebook.get_albums(); });" style="float:right;"></div>
                    <h4 class="modal-title"><span data-i18n="[html]facebook_images"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="facebook_content">
                        <div data-udrawSVG="facebook_albums_container">
                            <ul data-udrawSVG="facebook_albums_list"></ul>
                        </div>
                        <div data-udrawSVG="facebook_photos_container">
                            <ul data-udrawSVG="facebook_photos_list"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" data-udrawSVG="cancel_object_action"><span data-i18n="[html]cancel"></span></button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
    <div class="modal fade" data-udrawSVG="google_photos_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal_header_content">
                        <h4 class="modal-title"><span data-i18n="[html]google_images"></span></h4>
                    </div>
                    <div class="modal_header_content"></div>
                    <div class="modal_header_content">
                        <div class="g-signin2" data-theme="dark" style="display: inline-block; cursor: pointer;"><span class="google_signin_span" data-i18n="[html]sign_in"></span></div>
                        <a href="#" data-dismiss="modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="google_content">
                        <div data-udrawSVG="google_albums_container">
                            <ul data-udrawSVG="google_albums_list"></ul>
                        </div>
                        <div data-udrawSVG="google_photos_container">
                            <ul data-udrawSVG="google_photos_list"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><span data-i18n="[html]cancel"></span></button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
    <div class="modal fade" data-udrawSVG="instagram_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal_header_content" style="width: 84%;">
                        <h4 class="modal-title"><span data-i18n="[html]instagram_images"></span></h4>
                    </div>
                    <div class="modal_header_content">
                        <div style="display: inline-block; cursor: pointer;"><span class="instagram_signin_span" data-i18n="[html]sign_in"></span></div>
                        <a href="#" data-dismiss="modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="instagram_content">
                        <div data-udrawSVG="instagram_photos_container">
                            <ul data-udrawSVG="instagram_photos_list"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" data-udrawSVG="cancel_object_action"><span data-i18n="[html]cancel"></span></button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="modal fade" data-udrawSVG="image_replace_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 100%; height: 90%; max-height: 90%;" role="document">
            <div class="modal-content cornered" style="width: 100%; height: 100%; max-height: 100%;">
                <div class="modal-header">
                    <div class="modal_header_content">
                        <button type="button" class="cornered" data-udrawSVG="back_to_options">
                            <i class="fas fa-chevron-left"></i>
                            <span class="desktop_only" style="margin-left: 5px;" data-i18n="[html]cancel"></span>
                        </button>
                    </div>
                    <div class="modal_header_content product_title_container">
                    </div>
                    <div class="modal_header_content">
                        <button type="button" class="btn btn-success cornered" data-udrawSVG="apply_bulk_image_replace" disabled>
                            <span class="desktop_only" data-i18n="[html]continue"></span>
                            <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <ul data-udrawSVG="image_replace_list">
                        <li class="empty_list"><span data-i18n="[html]empty_selection"></span></li>
                    </ul>
                    <div class="image_count_container">
                        <span data-udrawSVG="selected_image_count"></span> / <span data-udrawSVG="image_count_span"></span> <span data-i18n="[html]images_selected"></span>
                    </div>
                    <div class="replace_image_browse_container">
                        <div class="replace_method_container">
                            <input type="file" name="files[]" data-udrawSVG="bulk_image_upload" style="display: none;" multiple />
                            <a href="#" class="bulk_image_button" data-udrawSVG="bulk_image_upload_button">
                                <i class="fas fa-cloud-upload fa-2x"></i>
                                <br />
                                <span data-i18n="[html]upload"></span>
                            </a>
                            <a href="#" class="bulk_image_button" data-bulk_image_type="local_storage">
                                <i class="fas fa-desktop fa-2x"></i>
                                <br />
                                <span data-i18n="[html]local_storage"></span>
                            </a>
                            <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                            <a href="#" class="bulk_image_button" data-bulk_image_type="facebook_image">
                                <i class="fab fa-facebook fa-2x fb"></i>
                                <br />
                                <span data-i18n="[html]facebook_images"></span>
                            </a>
                            <?php } ?>
                            <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
                            <a href="#" class="bulk_image_button" data-bulk_image_type="google_image">
                                <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" style="width: 32px;" />
                                <br />
                                <span data-i18n="[html]google_images"></span>
                            </a>
                            <?php } ?>
                            <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                            <a href="#" class="bulk_image_button" data-bulk_image_type="instagram_image">
                                <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" style="width: 32px;" />
                                <br />
                                <span data-i18n="[html]instagram_images"></span>
                            </a>
                            <?php } ?>
                        </div>
                        <div class="images_container">
                            <div class="images_container_child" data-bulk_image_type="local_storage">
                                <ul data-udrawSVG="local_image_list" style="height: 100%; overflow: auto;"></ul>
                            </div>
                            <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                            <div class="images_container_child" data-bulk_image_type="facebook_image">
                                <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="false" onLogin="RacadSVGDesigner.Facebook.get_login_status(function () { RacadSVGDesigner.Facebook.get_albums(); });" style="float:right;"></div>
                                <div data-udrawSVG="facebook_albums_container">
                                    <ul data-udrawSVG="facebook_albums_list"></ul>
                                </div>
                                <div data-udrawSVG="facebook_photos_container">
                                    <ul data-udrawSVG="facebook_photos_list"></ul>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
                            <div class="images_container_child" data-bulk_image_type="google_image">
                                <ul data-udrawSVG="google_photos_list" style="height: 100%; overflow: auto;"></ul>
                            </div>
                            <?php } ?>
                            <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                            <div class="images_container_child" data-bulk_image_type="instagram_image">
                                <ul data-udrawSVG="instagram_photos_list" style="height: 100%; overflow: auto;"></ul>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-udrawSVG="add_image_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span data-i18n="[html]select_method"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="add_image_container">
                        <input type="file" name="files[]" style="display: none;" data-udrawSVG="image_upload" />
                        <button type="button" data-udrawSVG="trigger_upload_image">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span data-i18n="[html]upload"></span>
                        </button>
                        <button type="button" data-udrawSVG="local_storage">
                            <i class="fas fa-desktop"></i>
                            <span data-i18n="[html]device"></span>
                        </button>
                        <?php if ($settings['udraw_SVGDesigner_enable_stock_images']) { ?>
                        <button type="button" data-udrawSVG="stock_image">
                            <i class="fas fa-images"></i>
                            <span data-i18n="[html]stock_image"></span>
                        </button>
                        <?php } ?>
                        <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                        <button type="button" data-udrawSVG="facebook_images">
                            <i class="fab fa-facebook fb"></i>
                            <span data-i18n="[html]facebook_images"></span>
                        </button>
                        <?php } ?>
                        <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
                        <button type="button" data-udrawSVG="google_images">
                            <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" class="photos_icon small" />
                            <span data-i18n="[html]google_images"></span>
                        </button>
                        <?php } ?>
                        <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                        <button type="button" data-udrawSVG="instagram_images">
                            <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" class="photos_icon small" />
                            <span data-i18n="[html]instagram_images"></span>
                        </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close_modal" data-i18n="[html]cancel" data-udrawSVG="cancel_replace_image"></button>
                </div>
            </div>
        </div>
    </div>

    <?php   if ($settings['udraw_SVGDesigner_enable_stock_images']) {
                $enabled_sources = $settings['udraw_SVGDesigner_stock_images_list'];
                $sources_array = array(
                                    'clipart'       => 'clipart',
                                    'pixabay'       => 'pixabay',
                                    'pexel'         => 'pexel',
                                    'unsplash'      => 'unsplash',
                                    'private'       => 'private_image_library'
                                );
    ?> 
    
    <div class="modal fade" data-udrawSVG="stock_image_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" data-dismiss="modal" style="float: right; margin-left: 5px;"><i class="fa fa-times"></i></a>
                    <h4 class="modal-title"><span data-i18n="[html]stock_image"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="search_container">
                        <input type="text" data-i18n="[placeholder]search" data-udrawSVG="stock_image_search_input" />

                        <select data-udrawSVG="stock_image_type" class="col-xs-6 col-sm-3">
                            <option data-i18n="[html]select_source"></option>
                            <?php 
                                foreach ($sources_array as $source => $display) {
                                    if (in_array($source, $enabled_sources)) {
                                        echo sprintf('<option value="%s" data-i18n="[html]%s"></option>', $source, $display);
                                    }
                                } 
                            ?>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" 
                            data-udrawSVG="cancel_object_action">
                        <span data-i18n="[html]cancel"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="modal enter_text fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong style="font-size:large;"><span>Add Text</span></strong>
                    <a href="#" data-dismiss="modal" style="float: right;"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <?php if (isset($allow_custom_objects) && $allow_custom_objects) { ?>
                    <div>
                        <ul class="objects_menu">
                            <li role="presentation" data-udrawSVG="add_text">
                                <i class="fas fa-font"></i>
                                <span data-i18n="[html]text"></span>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                    <div>
                        <h3 style="text-align: center;">Enter Your Text</h3>
                        <ul class="text_objects_list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade udraw_modal" data-udrawSVG="bulk_image_replace_continue_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <div>
                        <h4 style="color: #69c1a8;"><span data-i18n="[html]continue"></span><span>?</span></h4>
                        <span data-udrawSVG="selected_image_count"></span>
                        <span data-i18n="[html]enough_images_added"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cornered close_modal" style="float: left;">
                        <i class="fas fa-chevron-left" style="margin-right: 5px;"></i>
                        <span class="desktop_only" data-i18n="[html]add_more_images"></span>
                    </button>
                    <button type="button" class="btn btn-success cornered close_modal" data-udrawSVG="apply_bulk_image_replace" disabled>
                        <span class="desktop_only" data-i18n="[html]continue"></span>
                        <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade udraw_modal" data-udrawSVG="confirm_add_to_cart_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <div>
                        <span data-i18n="[html]confirm_add_to_cart"></span>
                        <br/>
                        <span data-i18n="[html]add_to_cart_disclaimer"></span>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: space-between; ">
                    <button type="button" class="btn cornered close_modal" style="float: left;">
                        <i class="fas fa-chevron-left" style="margin-right: 5px;"></i>
                        <span class="desktop_only" data-i18n="[html]return_to_design"></span>
                    </button>
                    <button type="button" class="btn btn-success cornered close_modal" data-udrawSVG="confirm_add_to_cart">
                        <span class="desktop_only" data-i18n="[html]add_to_cart"></span>
                        <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade udraw_modal" data-udrawSVG="progress_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="progress">
                        <div data-udrawSVG="progress_bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span data-udrawSVG="progress_percentage"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>