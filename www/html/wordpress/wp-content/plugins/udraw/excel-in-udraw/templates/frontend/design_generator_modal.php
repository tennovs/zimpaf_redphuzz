<?php
    global $post;
    $priceMatrixIsset = (metadata_exists('post', $post->ID, '_udraw_is_price_matrix_set')) ? 
            ((get_post_meta($post->ID, '_udraw_is_price_matrix_set', true) === 'yes') ? true : false) 
            : false;
?>

<div class="modal udraw_modal overlay-modal" data-udraw="design_generator_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Input Data
                </h4>
            </div>
            <div class="modal-body">
                <div style="overflow: auto;">
                    <table class="input_data_table">
                        <thead>
                            <tr class="labels_row">
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div>
                    <button type="button" class="btn btn-success" data-udraw="add_excel_table_row">
                        <i class="fa fa-plus"></i>
                        <span><?php _e('Add Row', 'udraw'); ?></span>
                    </button>
                    <button type="button" class="btn btn-warning hidden" data-udraw="edit_qrcode_settings">
                        <i class="fa fa-pencil"></i>
                        <span><?php _e('Edit QRCode settings', 'udraw'); ?></span>
                    </button>
                    <div class="quantity_div">
                        <span><?php _e('Total Qty: ', 'udraw'); ?></span>
                        <!-- Displays total quantity -->
                        <span data-udraw="inputTotalQuantity"></span>
                    </div>
                </div>
                <div class="alert alert-danger" data-udraw="inputTableError">
                    <strong><?php _e('Oops! Something\'s not quite right', 'udraw'); ?></strong><br/>
                    <span><?php _e('Please check to make sure that', 'udraw'); ?></span>
                    <ul>
                        <li>
                            <span><?php _e('All fields are filled in', 'udraw'); ?></span>
                        </li>
                        <li>
                            <span><?php _e('Quantity for each entry is at least one', 'udraw'); ?></span>
                        </li>
                        <li>
                            <span><?php _e('Settings for QRCodes are set, if QRCodes exist on the design', 'udraw'); ?></span>
                        </li>
                        <?php if ($priceMatrixIsset) { ?>
                        <li>
                            <span><?php _e('Total quantity is equal to the quantity selected in the pricing options', 'udraw'); ?></span>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <div style="float: left; display: inline-block;">
                    <form id="udraw-structure-form-input" action="<?php echo admin_url('admin-ajax.php') ?>" method="post" target="_blank" style="width: auto; display: inline-block; border: none;padding: 0; margin-bottom: 0;">
                      <input type="hidden" name="action" value="udraw_designer_generate_structure_file">
                      <input type="hidden" name="pages" value="">
                      <input type="submit" class="btn btn-info" value="Download Structure File">
                    </form>
                    <button type="button" id="udraw-upload-structure-file-btn" class="button btn btn-info" data-i18n="[html]common_label.upload-excel"></button>
                    <input style="display: none;" id="udraw-structure-file-upload" type="file" name="structureFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
                    <button type="button" data-udraw="udraw_excel_image_upload_trigger" class="button btn btn-info" data-i18n="[html]text.upload-photos"></button>
                    <input style="display: none;" data-udraw="udraw_excel_image_upload" type="file" name="files[]" multiple="multiple" accept="image/jpg, image/jpeg, image/png, image/svg+xml">
                </div>
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.cancel"></button>
                <button type="button" class="btn btn-success" data-udraw="inputTableApply" data-i18n="[html]common_label.next"></button>
            </div>
        </div>
    </div>
</div>


<div class="modal udraw_modal overlay-modal" data-udraw="design_preview_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    Preview
                </h3>
            </div>
            <div class="modal-body">
                <div>
                    <ul data-udraw="designPreviewList"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-4"><button type="button" class="btn btn-warning" data-udraw="designPreviewPrevious" data-i18n="[html]common_label.previous"></button></div>
                <div class="col-md-4"></div>
                <div class="col-md-4"><button type="button" class="btn btn-success" data-udraw="designPreviewConfirm" data-i18n="[html]common_label.confirm"></button></div>
            </div>
        </div>
    </div>
</div>

<div class="modal udraw_modal overlay-modal" data-udraw="edit_qrcode_setting_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" data-i18n="[html]header_label.edit_qrcode_settings"></h3>
            </div>
            <div class="modal-body">
                <div class="QRCodeLabelNavDiv"></div>
                <div class="prefixDiv">
                    <input type="text" data-udraw="qrcode_prefix_input" placeholder="http://example.com/" />
                    <p style="font-style: italic;"><?php _e('Prefix to be used when generating the QRCode, such as an URL. (Optional)', 'udraw'); ?></p>
                </div>
                <div class="valuesDiv">
                    <div class="valuesListDiv">
                        <p><?php _e('Input values to be used:', 'udraw'); ?></p>
                        <ul data-udraw="qrcode_input_list"></ul>
                    </div>
                    <div class="valuesListDiv">
                        <p><?php _e('Extra values to be used:', 'udraw'); ?></p>
                        <table data-udraw="encodedValuesTable">
                            <thead>
                                <tr>
                                    <td><?php _e('Key', 'udraw') ?></td>
                                    <td><?php _e('Value', 'udraw') ?></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-success" data-udraw="addInputKeyValueRow">
                                            <i class="fa fa-plus"></i>
                                            <span data-i18n="[html]common_label.add"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="valuesListDiv">
                        <div>
                            <input type="checkbox" data-udraw="generateSerialCode"/>
                            <span>Generate Serial Code</span>
                        </div>
                        <div data-udraw="serialCodeSettingsContainer">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>Label</span>
                                        </td>
                                        <td>
                                            <input type="text" data-udraw="serialCodeLabel"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Starting Value</span>
                                        </td>
                                        <td>
                                            <input type="number" data-udraw="serialCodeStartValue" min="0" step="1" value="0"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Incrementing Value</span>
                                        </td>
                                        <td>
                                            <input type="number" data-udraw="serialCodeIncrementValue" min="1" step="1" value="1"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="previewDiv">
                        <p>Preview of the first entry:</p>
                        <span data-udraw="qrcode_url_preview"></span>
                        <div data-udraw="qrcode_image_preview" class="qrcode_preview"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-udraw="cancelQRCodeSettings" data-dismiss="modal" data-i18n="[html]common_label.cancel"></button>
                <button type="button" class="btn btn-success" data-udraw="applyQRCodeSettings" data-i18n="[html]common_label.confirm"></button>
            </div>
        </div>
    </div>
</div>

<div class="modal udraw_modal overlay-modal" data-udraw="excel_select_image_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <span data-i18n="[html]header_label.image-header"></span>
                    <button type="button" style="float:right;" data-udraw="udraw_excel_image_upload_trigger" class="button btn btn-info" data-i18n="[html]text.upload-photos"></button>
                </h3>
            </div>
            <div class="modal-body">
                <div>
                    <ul data-udraw="excelUploadedImagesList"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]common_label.cancel"></button>
            </div>
        </div>
    </div>
</div>