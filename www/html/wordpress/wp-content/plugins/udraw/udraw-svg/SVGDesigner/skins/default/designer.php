<canvas id="canvg_canvas" style="display: none;"></canvas>
<div data-udrawSVG="SVGDesigner">
    <div class="designer_menu">
        <?php if ($admin) { ?>
            <div class="top_strip_container">
                <ul role="tablist" class="menu_ul">
                    <li role="presentation" class="dropdown">
                        <a href="#" data-toggle="dropdown"><i class="fas fa-list" style="margin-right: 5px;"></i><span data-i18n="[html]menu"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" data-udrawSVG="document_settings"><i class="fas fa-wrench" style="margin-right: 5px;"></i><span data-i18n="[html]settings"></span></a>
                            </li>
                            <li>
                                <a href="#" data-udrawSVG="admin_save"><i class="far fa-save" style="margin-right: 5px;"></i><span data-i18n="[html]save"></span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } else { ?>
            <div class="top_strip_container">
                <a href="#" data-udrawSVG="back_to_options">
                    <i class="fas fa-chevron-left desktop_only"></i>
                    <i class="fas fa-chevron-left mobile_only fa-2x"></i>
                    <span class="desktop_only" style="margin-left: 5px;" data-i18n="[html]back_to_options"></span>
                </a>
            </div>
            <div class="top_strip_container">
                <div class="product_title_container">
                    <?php 
                    if (isset($product) && $product !== null) {
                        echo $product->get_title(); 
                    }
                    ?>
                </div>
            </div>
            <div class="top_strip_container">
                <a href="#" data-udrawSVG="add_to_cart">
                    <div>
                        <span class="desktop_only" style="margin-right: 5px;" data-i18n="[html]add_to_cart"></span>
                    </div>
                    <div>
                        <i class="fas fa-shopping-cart desktop_only"></i>
                        <i class="fas fa-shopping-cart fa-2x mobile_only"></i>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
    <?php if (!$admin && $display_tools_top) { ?>
    <div class="tool_container_top has_tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li data-udrawSVG="pages_tab" role="presentation">
                <a href="#pages" class="nav-link active" aria-controls="pages" role="tab" data-toggle="tab"><span data-i18n="[html]pages"></span></a>
            </li>
            <?php if ($edit_text_tab && !$admin) { ?>
                <li data-udrawSVG="edit_text_tab" role="presentation">
                    <a href="#edit_text" class="nav-link hidden" aria-controls="edit_text" role="tab" data-toggle="tab"><span data-i18n="[html]edit_text"></span></a>
                </li>
            <?php } ?>
            <?php if ($display_layers || $admin) { ?>
            <li data-udrawSVG="layers_tab" role="presentation">
                <a href="#layers" class="nav-link" aria-controls="layers" role="tab" data-toggle="tab"><span data-i18n="[html]layers"></span></a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="pages">
                <div>
                    <button type="button" class="btn btn-default" data-i18n="[html]new_page" data-udrawSVG="create_page"></button>
                </div>
                <ul data-udrawSVG="page_list"></ul>
            </div>
            <?php if ($edit_text_tab) { ?>
            <div role="tabpanel" class="tab-pane" id="edit_text">
                <div data-udrawSVG="edit_text_modal" class="udraw_modal">
                    <div class="third">
                        <div class="third"><select data-udrawSVG="font_family_select"></select></div>
                        <div class="third"><input type="hidden" data-udrawSVG="colour_picker" /></div>
                        <div class="third"><select data-udrawSVG="font_size_select"></select></div>
                    </div>
                    <div class="user_added hidden third" style="width: auto;">
                        <button type="button" class="btn btn-default" data-udrawSVG="move_forward" data-i18n="[title]move_forward">
                            <i class="fas fa-sort-amount-up"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-udrawSVG="move_backwards" data-i18n="[title]move_backwards">
                            <i class="fas fa-sort-amount-down"></i>
                        </button>
                        <button type="button" class="btn btn-info hidden close_modal" data-udrawSVG="remove_object" >
                            <span data-i18n="[html]remove"></span>
                        </button>
                    </div>
                    <div class="third">
                        <textarea class="form-control" data-udrawSVG="text_area"></textarea>
                    </div>
                    <div class="more_options_container">
                        <div style="text-align: right;">
                            <a href="#" data-udrawSVG="advanced_text_options">
                                <span data-i18n="[html]show_advanced_options"></span>
                                <i class="fas fa-chevron-circle-down chevron_down"></i>
                                <i class="fas fa-chevron-circle-up chevron_up hidden"></i>
                            </a>
                        </div>
                        <div data-udrawSVG="advanced_text_options_container" class="hidden">
                            <div class="half">
                                <div class="third">
                                    <div class="quarter" style="margin-top: 5px;"><i class="fas fa-font"></i><i class="fas fa-arrows-alt-h"></i><i class="fas fa-bold"></i></div>
                                    <div class="three_quarters"><input type="number" min="0" value="0" data-udrawSVG="letter_spacing" /></div>
                                </div>
                                <div class="third">
                                    <div class="quarter">
                                        <span class="fa-stack fa-lg">
                                            <i class="far fa-square"></i>
                                            <i class="fas fa-paint-brush" style="transform: translateY(-50%) translateX(-75%); "></i>
                                        </span>
                                    </div>
                                    <div class="three_quarters"><input type="hidden" data-udrawSVG="stroke_colour_picker" /></div>
                                </div>
                                <div class="third">
                                    <div class="quarter"><img class="icon" src="<?php echo UDRAW_SVG_IMAGE_URL ?>line_width.png"></div>
                                    <div class="three_quarters"><input type="number" data-udrawSVG="stroke_width" min="0" value="0" step="0.1" /></div>
                                </div>
                            </div>
                            <div class="half">
                                <div class="button_group text_style_group three_quarters">
                                    <button type="button" data-udrawSVG="bold_button"><i class="fas fa-bold"></i></button>
                                    <button type="button" data-udrawSVG="italic_button"><i class="fas fa-italic"></i></button>
                                    <button type="button" data-udrawSVG="underline_button"><i class="fas fa-underline"></i></button>
                                    <button type="button" data-udrawSVG="overline_button"><span style="text-decoration: overline">O</span></button>
                                    <button type="button" data-udrawSVG="line-through_button"><i class="fas fa-strikethrough"></i></button>
                                </div>
                                <div class="button_group text_align_group quarter">
                                    <button type="button" class="text_align_button" data-text_align="start"><i class="fas fa-align-left"></i></button>
                                    <button type="button" class="text_align_button" data-text_align="middle"><i class="fas fa-align-center"></i></button>
                                    <button type="button" class="text_align_button" data-text_align="end"><i class="fas fa-align-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($display_layers || $admin) { ?>
            <div role="tabpanel" class="tab-pane" id="layers">
                <ul data-udrawSVG="layers_list"></ul>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <div class="sidebar">
        <?php if ($admin || (isset($allow_custom_objects) && $allow_custom_objects)) { ?>
        <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="add_text">
            <i class="fas fa-font"></i>
            <span data-i18n="[html]text" class="hover-text"></span>
        </button>
        <br />
        <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="add_image">
            <i class="fas fa-image"></i>
            <span data-i18n="[html]image" class="hover-text"></span>
        </button>
            <?php if ($admin) { ?>
            <br />
            <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="add_image_placeholder">
                <i class="fas fa-square"></i>
                <span data-i18n="[html]image_placeholder" class="hover-text"></span>
            </button>
            <?php } ?>
            <?php if ($admin || (isset($allow_background_colour) && $allow_background_colour)) { ?>
            <br />
            <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="document_background_colour">
                <img class="icon" src="<?php echo UDRAW_SVG_IMAGE_URL ?>paint_bucket.png" style="margin-left: -4px;">
                <span data-i18n="[html]background_colour" class="hover-text" style="font-size: 16px;"></span>
            </button>
            <?php } ?>
            <?php if ($admin || (isset($allow_rotate_template) && $allow_rotate_template)) { ?>
            <br />
            <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="rotate_counter_clockwise">
                <i class="fas fa-undo"></i>
                <span data-i18n="[html]rotate_counter_clockwise" class="hover-text" style="font-size: 14px;"></span>
            </button>
            <br />
            <button type="button" class="hover-icon btn btn-default btn-lg" data-udrawSVG="rotate_clockwise">
                <i class="fas fa-redo"></i>
                <span data-i18n="[html]rotate_clockwise" class="hover-text" style="font-size: 14px;"></span>
            </button>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="main_body">
        <?php if (!$admin) { ?>
        <div class="editing_tips_div">
            <h3 data-i18n="[html]editing_tips"></h3>
            <h4 data-i18n="[html]next_page_tips" class="hidden next_page_tips"></h4>
        </div>
        <?php } ?>
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
        <div class="zoom_container">
            <button type="button" data-udrawSVG="decrease_zoom"><i class="fas fa-search-minus"></i></button>
            <ul role="tablist" class="zoom_ul">
                <li role="presentation" class="dropdown">
                    <a href="#" data-toggle="dropdown">
                        <span data-udrawSVG="zoom_display"></span>
                        <i class="fas fa-caret-down" style="margin-left: 5px;"></i>
                    </a>
                    <ul class="dropdown-menu zoom_dropdown_menu"></ul>
                </li>
            </ul>
            <button type="button" data-udrawSVG="increase_zoom"><i class="fas fa-search-plus"></i></button>
        </div>
        <div class="action_container">
            <button type="button" class="btn btn-default" data-udrawSVG="undo_button" title="Undo">
                <i class="fas fa-undo"></i>
            </button>
            <button type="button" class="btn btn-default" data-udrawSVG="redo_button" title="Redo">
                <i class="fas fa-redo"></i>
            </button>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="page_settings_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span data-i18n="[html]page_settings"></span></h4>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tbody>
                                <tr>
                                    <td><label data-i18n="[html]width"></label></td>
                                    <td>
                                        <input type="number" min="0" value="0" step="any" data-udrawSVG="page_size_width" />
                                        <br />
                                        <span data-i18n="[html]not_valid" class="hidden error"></span>
                                    </td>
                                    <td><span class="wizard_custom" data-i18n="[html]inch"></span></td>
                                </tr>
                                <tr>
                                    <td><label data-i18n="[html]height"></label></td>
                                    <td>
                                        <input type="number" min="0" value="0" step="any" data-udrawSVG="page_size_height" />
                                        <br />
                                        <span data-i18n="[html]not_valid" class="hidden error"></span>
                                    </td>
                                    <td><span class="wizard_custom" data-i18n="[html]inch"></span></td>
                                </tr>
                                <tr>
                                    <td><label data-i18n="[html]bleed"></label></td>
                                    <td>
                                        <input type="number" min="0" value="0" step="any" data-udrawSVG="page_size_bleed" />
                                        <br />
                                        <span data-i18n="[html]not_valid" class="hidden error"></span>
                                    </td>
                                    <td><span class="wizard_custom" data-i18n="[html]inch"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close_modal"><span data-i18n="[html]cancel"></span></button>
                        <button type="button" class="btn btn-success" data-udrawSVG="update_page_settings"><span data-i18n="[html]update"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="facebook_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" class="close_modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
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
                        <button type="button" class="btn btn-danger close_modal"><span data-i18n="[html]close"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="google_photos_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" style="display: inline-block;"><span data-i18n="[html]google_images"></span></h4>
                        <a href="#" class="close_modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                        <button type="button" class="g-signin2" data-theme="dark" style="margin-top: -5px;">
                            <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" class="google_photos small photos_icon"/>
                            <span class="google_signin_span" data-i18n="[html]sign_in"></span>
                        </button>
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
                        <button type="button" class="btn btn-danger close_modal"><span data-i18n="[html]close"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="instagram_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" style="display: inline-block;"><span data-i18n="[html]instagram_images"></span></h4>
                        <a href="#" class="close_modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                        <button type="button" class="instagram_signin_button" style="margin-top: -5px;">
                            <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" class="photos_icon small"/>
                            <span class="instagram_signin_span" data-i18n="[html]sign_in"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="instagram_content">
                            <div data-udrawSVG="instagram_photos_container">
                                <ul data-udrawSVG="instagram_photos_list"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close_modal"><span data-i18n="[html]close"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="image_replace_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 100%; height: 90%; max-height: 90%;" role="document">
                <div class="modal-content cornered" style="width: 100%; height: 100%; max-height: 100%;">
                    <div class="modal-header">
                        <div class="modal_header_content">
                            <a href="#" data-udrawSVG="back_to_options">
                                <i class="fas fa-chevron-left"></i>
                                <span class="desktop_only" style="margin-left: 5px;" data-i18n="[html]cancel"></span>
                            </a>
                        </div>
                        <div class="modal_header_content product_title_container">
                            <?php 
                            if (isset($product) && $product !== null) {
                                echo $product->get_title(); 
                            }
                            ?>
                        </div>
                        <div class="modal_header_content" style="text-align: right;">
                            <a href="#" data-udrawSVG="apply_bulk_image_replace" disabled>
                                <span class="desktop_only" data-i18n="[html]continue"></span>
                                <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    </div>
                    <div class="modal-body">
                        <ul data-udrawSVG="image_replace_list">
                            <li class="empty_list"><span data-i18n="[html]empty_selection"></span></li>
                        </ul>
                        <div class="image_count_container">
                            <div style="position: absolute; height: 40px; z-index: 1; width: 100%;">
                                <span data-udrawSVG="selected_image_count"></span> / <span data-udrawSVG="image_count_span"></span> <span data-i18n="[html]images_selected"></span>
                            </div>
                            <div class="progress_div"><div class="progress_bar"></div></div>
                        </div>
                        <div class="replace_image_browse_container">
                            <div class="replace_method_container desktop_only">
                                <a href="#" class="bulk_image_button" data-bulk_image_type="local_storage">
                                    <i class="fas fa-desktop fa-2x"></i>
                                    <br />
                                    <span data-i18n="[html]device"></span>
                                </a>
                                <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="facebook_image">
                                    <i class="fab fa-facebook-square fa-2x fb"></i>
                                    <br />
                                    <span data-i18n="[html]facebook_images"></span>
                                </a>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="google_image">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" class="photos_icon" />
                                    <br />
                                    <span data-i18n="[html]google_images"></span>
                                </a>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="instagram_image">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" class="photos_icon"/>
                                    <br />
                                    <span data-i18n="[html]instagram_images"></span>
                                </a>
                                <?php } ?>
                            </div>
                            <div class="images_container">
                                <div class="images_container_child" data-bulk_image_type="local_storage">
                                    <input type="file" name="files[]" data-udrawSVG="bulk_image_upload" style="display: none;" multiple accept="image/*" />
                                    <a href="#" class="bulk_image_button" data-bulk_image_type="bulk_image_upload_button" data-udrawSVG="bulk_image_upload_button">
                                        <i class="fas fa-cloud-upload-alt fa-2x" style="vertical-align: top;"></i>
                                        <span data-i18n="[html]upload" style="vertical-align: sub;"></span>
                                    </a>
                                    <ul data-udrawSVG="local_image_list"></ul>
                                </div>
                                <div class="images_container_child" data-bulk_image_type="facebook_image">
                                    <div style="text-align: right;">
                                        <div class="fb-login-button" data-scope="user_photos" data-max-rows="1" data-size="small" 
                                             data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" 
                                             data-use-continue-as="false" onLogin="RacadSVGDesigner.Facebook.get_login_status(function () { RacadSVGDesigner.Facebook.get_albums(); });"></div>
                                    </div>
                                    <div data-udrawSVG="facebook_albums_container">
                                        <ul data-udrawSVG="facebook_albums_list"></ul>
                                    </div>
                                    <div data-udrawSVG="facebook_photos_container">
                                        <ul data-udrawSVG="facebook_photos_list"></ul>
                                    </div>
                                </div>
                                <div class="images_container_child" data-bulk_image_type="google_image">
                                    <div class="sign_in" style="text-align: right;">
                                        <button type="button" class="g-signin2" data-theme="dark" style="float: none;">
                                            <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" class="photos_icon"/>
                                            <span class="google_signin_span" data-i18n="[html]sign_in"></span>
                                        </button>
                                    </div>
                                    <div data-udrawSVG="google_albums_container">
                                        <ul data-udrawSVG="google_albums_list"></ul>
                                    </div>
                                    <div data-udrawSVG="google_photos_container">
                                        <ul data-udrawSVG="google_photos_list"></ul>
                                    </div>
                                </div>
                                <div class="images_container_child" data-bulk_image_type="instagram_image">
                                    <button type="button" class="instagram_signin_button">
                                        <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" class="photos_icon small"/>
                                        <span class="instagram_signin_span" data-i18n="[html]sign_in"></span>
                                    </button>
                                    <ul data-udrawSVG="instagram_photos_list"></ul>
                                </div>
                            </div>
                            <div class="replace_method_container mobile_only">
                                <a href="#" class="bulk_image_button" data-bulk_image_type="local_storage">
                                    <i class="fas fa-desktop fa-2x"></i>
                                    <br />
                                    <span data-i18n="[html]device"></span>
                                </a>
                                <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="facebook_image">
                                    <i class="fab fa-facebook-square fa-2x fb"></i>
                                    <br />
                                    <span data-i18n="[html]facebook_images"></span>
                                </a>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="google_image">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg" class="photos_icon" />
                                    <br />
                                    <span data-i18n="[html]google_images"></span>
                                </a>
                                <?php } ?>
                                <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                                <a href="#" class="bulk_image_button" data-bulk_image_type="instagram_image">
                                    <img src="<?php echo UDRAW_SVG_IMAGE_URL ?>instagram.png" class="photos_icon"/>
                                    <br />
                                    <span data-i18n="[html]instagram_images"></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="bulk_image_replace_continue_modal" tabindex="-1" role="dialog">
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
        <div class="modal udraw_modal" data-udrawSVG="user_image_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span data-i18n="[html]device"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="local_image_container" style="display: inline-block; vertical-align: top; padding: 5px;">
                            <ul data-udrawSVG="local_image_list"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close_modal" data-i18n="[html]close"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="add_image_modal" tabindex="-1" role="dialog">
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
                                <i class="fab fa-facebook-square fb"></i>
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
        <?php if ($admin || !$edit_text_tab) { ?>
        <div class="modal udraw_modal" data-udrawSVG="edit_text_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <div class="third"><select data-udrawSVG="font_family_select"></select></div>
                            <div class="third"><input type="hidden" data-udrawSVG="colour_picker" /></div>
                            <div class="third"><select data-udrawSVG="font_size_select"></select></div>
                        </div>
                        <div style="margin-top: 5px;">
                            <textarea class="form-control" data-udrawSVG="text_area"></textarea>
                        </div>
                        <div class="more_options_container">
                            <div style="text-align: right;">
                                <a href="#" data-udrawSVG="advanced_text_options">
                                    <span data-i18n="[html]show_advanced_options"></span>
                                    <i class="fas fa-chevron-circle-down chevron_down"></i>
                                    <i class="fas fa-chevron-circle-up chevron_up hidden"></i>
                                </a>
                            </div>
                            <div data-udrawSVG="advanced_text_options_container" class="hidden">
                                <div class="half">
                                    <div class="quarter" style="margin-top: 5px;"><i class="fas fa-font"></i><i class="fas fa-arrows-h"></i><i class="fas fa-bold"></i></div>
                                    <div class="three_quarters"><input type="number" min="0" value="0" data-udrawSVG="letter_spacing" /></div>
                                </div>
                                <div class="half">
                                    <div class="quarter">
                                        <span class="fa-stack fa-lg">
                                            <i class="far fa-square" style="color: #f16f6f;"></i>
                                            <i class="fas fa-paint-brush" style="transform: translateY(-15%) translateX(-75%);"></i>
                                        </span>
                                    </div>
                                    <div class="three_quarters"><input type="hidden" data-udrawSVG="stroke_colour_picker" /></div>
                                </div>
                                <div class="half">
                                    <div class="quarter"><img class="icon" src="<?php echo UDRAW_SVG_IMAGE_URL ?>line_width.png"></div>
                                    <div class="three_quarters"><input type="number" data-udrawSVG="stroke_width" min="0" value="0" step="0.1" /></div>
                                </div>
                                <div class="button_group text_style_group">
                                    <button type="button" data-udrawSVG="bold_button"><i class="fas fa-bold"></i></button>
                                    <button type="button" data-udrawSVG="italic_button"><i class="fas fa-italic"></i></button>
                                    <button type="button" data-udrawSVG="underline_button"><i class="fas fa-underline"></i></button>
                                    <button type="button" data-udrawSVG="overline_button"><span style="text-decoration: overline">O</span></button>
                                    <button type="button" data-udrawSVG="line-through_button"><i class="fas fa-strikethrough"></i></button>
                                </div>
                                <div class="button_group text_align_group">
                                    <button type="button" class="text_align_button" data-text_align="start"><i class="fas fa-align-left"></i></button>
                                    <button type="button" class="text_align_button" data-text_align="middle"><i class="fas fa-align-center"></i></button>
                                    <button type="button" class="text_align_button" data-text_align="end"><i class="fas fa-align-right"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="admin_container hidden" style="margin-top: 5px;">
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_preview_only" />
                                    <span data-i18n="[html]preview_only"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_uneditable" />
                                    <span data-i18n="[html]make_uneditable"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_always_front" />
                                    <span data-i18n="[html]make_always_front"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_always_back" />
                                    <span data-i18n="[html]make_always_back"></span>
                                </label>
                            </div>
                        </div>
                        <div class="user_added hidden">
                            <button type="button" class="btn btn-default" data-udrawSVG="move_forward">
                                <span data-i18n="[html]move_forward"></span>
                            </button>
                            <button type="button" class="btn btn-default" data-udrawSVG="move_backwards">
                                <span data-i18n="[html]move_backwards"></span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info hidden close_modal" data-i18n="[html]remove" data-udrawSVG="remove_object" ></button>
                        <button type="button" class="btn btn-danger close_modal" data-i18n="[html]close"></button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="modal udraw_modal" data-udrawSVG="edit_image_modal" tabindex="-1" role="dialog">
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
                                    <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="clear_changes">
                                        <i class="fas fa-refresh"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="zoom_in">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="zoom_out">
                                        <i class="fas fa-search-minus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="rotate_counterclockwise">
                                        <i class="fas fa-rotate-left"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="image_editing_tool_button btn btn-default" data-tool_type="rotate_clockwise">
                                        <i class="fas fa-rotate-right"></i>
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
        <div class="modal udraw_modal" data-udrawSVG="image_action_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="edit_image_container">
                            <button type="button" data-udrawSVG="edit_image">
                                <i class="fas fa-crop"></i>
                                <span data-i18n="[html]edit_image"></span>
                            </button>
                            <br />
                            <button type="button" data-udrawSVG="replace_image">
                                <i class="fas fa-retweet"></i>
                                <span data-i18n="[html]replace_image"></span>
                            </button>
                        </div>
                        <div class="admin_container hidden" style="margin-top: 5px;">
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_preview_only" />
                                    <span data-i18n="[html]preview_only"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_uneditable" />
                                    <span data-i18n="[html]make_uneditable"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_always_front" />
                                    <span data-i18n="[html]make_always_front"></span>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" data-udrawSVG="make_always_back" />
                                    <span data-i18n="[html]make_always_back"></span>
                                </label>
                            </div>
                        </div>
                        <div class="user_added hidden">
                            <button type="button" class="btn btn-default" data-udrawSVG="move_forward">
                                <span data-i18n="[html]move_forward"></span>
                            </button>
                            <button type="button" class="btn btn-default" data-udrawSVG="move_backwards">
                                <span data-i18n="[html]move_backwards"></span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info hidden close_modal" data-i18n="[html]remove" data-udrawSVG="remove_object" ></button>
                        <button type="button" class="btn btn-danger close_modal" data-i18n="[html]close"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal udraw_modal" data-udrawSVG="confirm_add_to_cart_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="text-align: center;">
                        <div>
                            <span data-i18n="[html]confirm_add_to_cart"></span>
                            <br/>
                            <span data-i18n="[html]add_to_cart_disclaimer"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
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
        <div class="modal udraw_modal" data-udrawSVG="stock_image_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" data-dismiss="modal" style="float: right; margin-left: 5px;"><i class="fas fa-times"></i></a>
                        <h4 class="modal-title"><span data-i18n="[html]stock_image"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="search_container row">
                            <input type="text" data-i18n="[placeholder]search" data-udrawSVG="stock_image_search_input" class="col-xs-6 col-sm-3" />

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
                        <div class="stock_image_container row" data-stock_image="private">
                            <ul class="private_library_list col-xs-2 col-sm-2" data-stock_image="private_category"></ul>
                            <ul class="private_library_list col-xs-8 col-sm-10" data-stock_image="private"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger close_modal" data-udrawSVG="cancel_object_action"><span data-i18n="[html]cancel"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="modal udraw_modal" data-udrawSVG="progress_modal" tabindex="-1" role="dialog">
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
    <?php if ($admin || !$display_tools_top) { ?>
    <div class="sidebar has_tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li data-udrawSVG="pages_tab" role="presentation">
                <a href="#pages" class="nav-link active" aria-controls="pages" role="tab" data-toggle="tab"><span data-i18n="[html]pages"></span></a>
            </li>
            <?php if ($edit_text_tab && !$admin) { ?>
                <li data-udrawSVG="edit_text_tab" role="presentation">
                    <a href="#edit_text" class="nav-link hidden" aria-controls="edit_text" role="tab" data-toggle="tab"><span data-i18n="[html]edit_text"></span></a>
                </li>
            <?php } ?>
            <?php if ($display_layers || $admin) { ?>
            <li data-udrawSVG="layers_tab" role="presentation">
                <a href="#layers" class="nav-link" aria-controls="layers" role="tab" data-toggle="tab"><span data-i18n="[html]layers"></span></a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="pages">
                <div>
                    <button type="button" class="btn btn-default" data-i18n="[html]new_page" data-udrawSVG="create_page"></button>
                </div>
                <ul data-udrawSVG="page_list"></ul>
            </div>
            <?php if ($edit_text_tab) { ?>
            <div role="tabpanel" class="tab-pane" id="edit_text">
                <div data-udrawSVG="edit_text_modal" class="udraw_modal">
                    <div style="text-align: center;">
                        <div class="third"><select data-udrawSVG="font_family_select"></select></div>
                        <div class="third"><input type="hidden" data-udrawSVG="colour_picker" /></div>
                        <div class="third"><select data-udrawSVG="font_size_select"></select></div>
                    </div>
                    <div style="margin-top: 5px;">
                        <table data-udrawSVG="text_table">
                            <tbody></tbody>
                            <?php if ($admin || $allow_custom_objects) { ?>
                            <tfoot>
                                <tr>
                                    <td><button type="button" data-udrawSVG="add_text_line"><span data-i18n="[html]add_text_line"></span></button></td>
                                </tr>
                            </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="more_options_container">
                        <div style="text-align: right;">
                            <a href="#" data-udrawSVG="advanced_text_options">
                                <span data-i18n="[html]show_advanced_options"></span>
                                <i class="fas fa-chevron-circle-down chevron_down"></i>
                                <i class="fas fa-chevron-circle-up chevron_up hidden"></i>
                            </a>
                        </div>
                        <div data-udrawSVG="advanced_text_options_container" class="hidden">
                            <div class="half">
                                <div class="quarter" style="margin-top: 5px;"><i class="fas fa-font"></i><i class="fas fa-arrows-h"></i><i class="fas fa-bold"></i></div>
                                <div class="three_quarters"><input type="number" min="0" value="0" data-udrawSVG="letter_spacing" /></div>
                            </div>
                            <div class="half">
                                <div class="quarter">
                                    <span class="fa-stack fa-lg">
                                        <i class="far fa-square" style="color: #f16f6f;"></i>
                                        <i class="fas fa-paint-brush" style="transform: translateY(-15%) translateX(-75%);"></i>
                                    </span>
                                </div>
                                <div class="three_quarters"><input type="hidden" data-udrawSVG="stroke_colour_picker" /></div>
                            </div>
                            <div class="half">
                                <div class="quarter"><img class="icon" src="<?php echo UDRAW_SVG_IMAGE_URL ?>line_width.png"></div>
                                <div class="three_quarters"><input type="number" data-udrawSVG="stroke_width" min="0" value="0" step="0.1" /></div>
                            </div>
                            <div class="button_group text_style_group">
                                <button type="button" data-udrawSVG="bold_button"><i class="fas fa-bold"></i></button>
                                <button type="button" data-udrawSVG="italic_button"><i class="fas fa-italic"></i></button>
                                <button type="button" data-udrawSVG="underline_button"><i class="fas fa-underline"></i></button>
                                <button type="button" data-udrawSVG="overline_button"><span style="text-decoration: overline">O</span></button>
                                <button type="button" data-udrawSVG="line-through_button"><i class="fas fa-strikethrough"></i></button>
                            </div>
                            <div class="button_group text_align_group">
                                <button type="button" class="text_align_button" data-text_align="start"><i class="fas fa-align-left"></i></button>
                                <button type="button" class="text_align_button" data-text_align="middle"><i class="fas fa-align-center"></i></button>
                                <button type="button" class="text_align_button" data-text_align="end"><i class="fas fa-align-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="user_added hidden">
                        <button type="button" class="btn btn-default" data-udrawSVG="move_forward">
                            <i class="fas fa-sort-amount-up"></i>
                            <span data-i18n="[html]move_forward"></span>
                        </button>
                        <button type="button" class="btn btn-default" data-udrawSVG="move_backwards">
                            <i class="fas fa-sort-amount-down"></i>
                            <span data-i18n="[html]move_backwards"></span>
                        </button>
                        <button type="button" class="btn btn-info hidden close_modal" data-udrawSVG="remove_object" >
                            <span data-i18n="[html]remove"></span>
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($display_layers || $admin) { ?>
            <div role="tabpanel" class="tab-pane" id="layers">
                <ul data-udrawSVG="layers_list"></ul>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    
    <?php if (!$admin) { ?>
        <div class="designer_menu">
            <div class="top_strip_container">
                <a href="#" data-udrawSVG="back_to_options">
                    <i class="fas fa-chevron-left desktop_only"></i>
                    <i class="fas fa-chevron-left mobile_only fa-2x"></i>
                    <span class="desktop_only" style="margin-left: 5px;" data-i18n="[html]back_to_options"></span>
                </a>
            </div>
            <div class="top_strip_container">
                <div class="product_title_container">
                    <?php 
                    if (isset($product) && $product !== null) {
                        echo $product->get_title(); 
                    }
                    ?>
                </div>
            </div>
            <div class="top_strip_container">
                <a href="#" data-udrawSVG="add_to_cart">
                    <div>
                        <span class="desktop_only" style="margin-right: 5px;" data-i18n="[html]add_to_cart"></span>
                    </div>
                    <div>
                        <i class="fas fa-shopping-cart desktop_only"></i>
                        <i class="fas fa-shopping-cart fa-2x mobile_only"></i>
                    </div>
                </a>
            </div>
        </div>
    <?php } ?>
</div>