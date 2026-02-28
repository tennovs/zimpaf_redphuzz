<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 09/11/16
 * Time: 11:52 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e('Floor Plans', 'essential-real-estate'); ?></h2>
    </div>
    <div class="property-fields">
        <div class="property-floors-control">
            <label><input value="1" type="radio" name="floors_enable"><?php esc_html_e('Enable', 'essential-real-estate'); ?>
            </label>
            <label><input value="0" checked="checked" type="radio"
                          name="floors_enable"><?php esc_html_e('Disable', 'essential-real-estate'); ?></label>
        </div>
        <div class="property-floors-data">
            <table class="add-sort-table">
                <tbody id="ere_floors">
                <tr>
                    <td class="row-sort">
                        <span class="sort-floors-row sort"><i class="fa fa-navicon"></i></span>
                    </td>
                    <td class="sort-middle">
                        <div class="sort-inner-block">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_name_0"><?php esc_html_e('Floor Name', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_name]"
                                            type="text"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_name_0" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_price_0"><?php esc_html_e('Floor Price (Only digits)', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_price]"
                                            type="number"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_price_0" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_price_postfix_0"><?php esc_html_e('Price Postfix', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_price_postfix]"
                                            type="text"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_price_postfix_0"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_size_0"><?php esc_html_e('Floor Size (Only digits)', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_size]"
                                            type="number"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_size_0" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_size_postfix_0"><?php esc_html_e('Size Postfix', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_size_postfix]"
                                            type="text"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_size_postfix_0"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_bedrooms_0"><?php esc_html_e('Bedrooms', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_bedrooms]"
                                            type="number"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_bedrooms_0"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_bathrooms_0"><?php esc_html_e('Bathrooms', 'essential-real-estate'); ?></label>
                                        <input
                                            name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_bathrooms]"
                                            type="number"
                                            id="<?php echo ERE_METABOX_PREFIX ?>floor_bathrooms_0" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="<?php echo ERE_METABOX_PREFIX ?>floor_image_url_0"><?php esc_html_e('Floor Image', 'essential-real-estate'); ?></label>

                                        <div id="ere-floor-plupload-container-0" class="file-upload-block">
                                            <input
                                                name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_image][url]"
                                                type="text"
                                                id="<?php echo ERE_METABOX_PREFIX ?>floor_image_url_0"
                                                class="ere_floor_image_url form-control">
                                            <input type="hidden" class="ere_floor_image_id"
                                                   name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_image][id]"
                                                   value="" id="<?php echo ERE_METABOX_PREFIX ?>floor_image_id_0"/>
                                            <button type="button" id="ere-floor-0" style="position: absolute" title="<?php esc_attr_e('Choose image','essential-real-estate') ?>" class="ere_floorsImg"><i class="fa fa-file-image-o"></i></button>
                                        </div>
                                        <div id="ere-floor-errors-log-0"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label
                                        for="<?php echo ERE_METABOX_PREFIX ?>floor_description_0"><?php esc_html_e('Plan Description', 'essential-real-estate'); ?></label>
                                    <textarea
                                        name="<?php echo ERE_METABOX_PREFIX ?>floors[0][<?php echo ERE_METABOX_PREFIX ?>floor_description]"
                                        rows="4"
                                        id="<?php echo ERE_METABOX_PREFIX ?>floor_description_0"
                                        class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="row-remove">
                        <span data-remove="0" class="remove-floors-row remove"><i class="fa fa-remove"></i></span>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="button" id="add-floors-row" data-increment="0" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> <?php esc_html_e('Add More', 'essential-real-estate'); ?>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>