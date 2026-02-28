<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $property_data, $property_meta_data, $hide_property_fields;
$auto_property_id = ere_get_option('auto_property_id',0);
$measurement_units = ere_get_measurement_units();
$measurement_units_land_area = ere_get_measurement_units_land_area();
$additional_features = absint(get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_features', true));
$additional_feature_title = get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_feature_title', true);
$additional_feature_value = get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_feature_value', true);
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Property Details', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-detail row">
        <?php if (!in_array("property_size", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_size"><?php echo wp_kses_post(sprintf(__('Size (%s) %s', 'essential-real-estate'),$measurement_units,ere_required_field('property_size'))); ?></label>
                    <input type="number" id="property_size" class="form-control" name="property_size"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_land", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_land"><?php echo wp_kses_post(sprintf(esc_html__('Land Area (%s) %s', 'essential-real-estate'),$measurement_units_land_area, ere_required_field('property_land'))); ?></label>
                    <input type="number" id="property_land" class="form-control" name="property_land"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_rooms", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_rooms"><?php echo esc_html__('Rooms', 'essential-real-estate') . ere_required_field('property_rooms'); ?></label>
                    <input type="number" id="property_rooms" class="form-control" name="property_rooms"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_rooms'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_rooms'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bedrooms", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_bedrooms"><?php echo esc_html__('Bedrooms', 'essential-real-estate') . ere_required_field('property_bedrooms'); ?></label>
                    <input type="number" id="property_bedrooms" class="form-control" name="property_bedrooms"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bathrooms", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_bathrooms"><?php echo esc_html__('Bathrooms', 'essential-real-estate') . ere_required_field('property_bathrooms'); ?></label>
                    <input type="number" id="property_bathrooms" class="form-control" name="property_bathrooms"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="property_garage"><?php echo esc_html__('Garages', 'essential-real-estate') . ere_required_field('property_garage'); ?></label>
                    <input type="number" id="property_garage" class="form-control" name="property_garage"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage_size", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_garage_size"><?php echo wp_kses_post(sprintf(esc_html__('Garages Size (%s)', 'essential-real-estate'),$measurement_units)); ?></label>
                    <input type="number" id="property_garage_size" class="form-control" name="property_garage_size"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("property_year", $hide_property_fields)) { ?>
        <div class="col-sm-4">
            <div class="form-group">
                <label
                    for="property_year"><?php echo esc_html__('Year Built', 'essential-real-estate') . ere_required_field('property_year'); ?></label>
                <input type="number" id="property_year" class="form-control" name="property_year"
                       value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year'])) {
                           echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0]);
                       } ?>">
            </div>
        </div>
        <?php } ?>
        <?php if (!in_array("property_identity", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_identity"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></label>
                    <input type="text" class="form-control" name="property_identity" id="property_identity" value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_identity'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_identity'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
        <?php
        $additional_fields = ere_render_additional_fields();
        if(count($additional_fields)>0) {
	        $required_fields = ere_get_option('required_fields', array('property_title', 'property_type', 'property_price', 'property_map_address'));
            foreach ($additional_fields as $key => $field) {
	            $field_attributes = array();
	            if (is_array($required_fields) && in_array($field['id'], $required_fields)) {
		            $field_attributes[] = 'data-toggle="ere-property-additional-field-required"';
	            }
                switch ($field['type']) {
                    case 'text':
                        ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label
                                    for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . ere_required_field($field['id'])); ?></label>
                                <input <?php echo implode(' ', $field_attributes)?> type="text" id="<?php echo esc_attr($field['id']); ?>" class="form-control"
                                       name="<?php echo esc_attr($field['id']); ?>"
                                       value="<?php if (isset($property_meta_data[$field['id']])) {
                                           echo sanitize_text_field($property_meta_data[$field['id']][0]);
                                       } ?>">
                            </div>
                        </div>
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . ere_required_field($field['id'])); ?></label>
                                <textarea <?php echo implode(' ', $field_attributes)?>
                                    name="<?php echo esc_attr($field['id']); ?>"
                                    rows="3"
                                    id="<?php echo esc_attr($field['id']); ?>"
                                    class="form-control"><?php if (isset($property_meta_data[$field['id']])) {
                                        echo sanitize_text_field($property_meta_data[$field['id']][0]);
                                    } ?></textarea>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'select':
                        ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . ere_required_field($field['id'])); ?></label>
                                <select <?php echo implode(' ', $field_attributes)?> name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>"
                                        class="form-control">
                                    <?php
                                    foreach ($field['options'] as $opt_value):?>
                                        <option value="<?php echo esc_attr($opt_value); ?>" <?php if( isset( $property_meta_data[$field['id']] ) &&  $property_meta_data[$field['id']][0]==$opt_value) { echo 'selected';} ?>><?php echo esc_html($opt_value); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'checkbox_list':
	                    $field_attributes[] = sprintf('data-name="%s"', $field['id'].'[]');
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo esc_html($field['title'] . ere_required_field($field['id'])); ?></label>
                                <div <?php echo implode(' ', $field_attributes)?> class="ere-field-<?php echo esc_attr($field['id']); ?>">
                                <?php
                                $property_field= get_post_meta($property_data->ID, $field['id'], true);
                                if(empty($property_field))
                                {
                                    $property_field=array();
                                }
                                foreach ($field['options'] as $opt_value):
                                    if ( in_array( $opt_value, $property_field ) ):?>
                                        <label class="checkbox-inline"><input type="checkbox"
                                                                              name="<?php echo esc_attr($field['id']); ?>[]"
                                                                              value="<?php echo esc_attr($opt_value); ?>" checked><?php echo esc_html($opt_value); ?>
                                        </label>
                                    <?php else:?>
                                        <label class="checkbox-inline"><input type="checkbox"
                                                                              name="<?php echo esc_attr($field['id']); ?>[]"
                                                                              value="<?php echo esc_attr($opt_value); ?>"><?php echo esc_html($opt_value); ?>
                                        </label>
                                    <?php endif;
                                endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'radio':
	                    $field_attributes[] = sprintf('data-name="%s"', $field['id']);
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo esc_html($field['title'] . ere_required_field($field['id'])); ?></label>
                                <div <?php echo implode(' ', $field_attributes)?> class="ere-field-<?php echo esc_attr($field['id']); ?>">
                                <?php
                                foreach ($field['options'] as $opt_value):?>
                                    <label class="radio-inline"><input type="radio" name="<?php echo esc_attr($field['id']); ?>"
                                                                       value="<?php echo esc_attr($opt_value); ?>" <?php if( isset( $property_meta_data[$field['id']] ) &&  $property_meta_data[$field['id']][0]==$opt_value) { echo 'checked';} ?>><?php echo esc_html($opt_value); ?>
                                    </label>
                                <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;
                }
            }
        }
        ?>
    </div>
    <?php if (!in_array("additional_details", $hide_property_fields)) { ?>
        <div class="add-tab-row">
            <h4><?php esc_html_e('Additional details', 'essential-real-estate'); ?></h4>
            <table class="additional-block">
                <thead>
                <tr>
                    <td class="ere-column-action"></td>
                    <td><label><?php esc_html_e('Title', 'essential-real-estate'); ?></label></td>
                    <td><label><?php esc_html_e('Value', 'essential-real-estate'); ?></label></td>
                    <td class="ere-column-action"></td>
                </tr>
                </thead>
                <tbody id="ere_additional_details">
                <?php
                if (!empty($additional_features)) {
                    for ($i = 0; $i < $additional_features; $i++) { ?>
                        <tr>
                            <td>
                                <span class="sort-additional-row"><i class="fa fa-navicon"></i></span>
                            </td>
                            <td>
                                <input class="form-control" type="text"
                                       name="additional_feature_title[<?php echo esc_attr($i); ?>]"
                                       id="additional_feature_title_<?php echo esc_attr($i); ?>"
                                       value="<?php echo esc_attr($additional_feature_title[$i]); ?>">
                            </td>
                            <td>
                                <input class="form-control" type="text"
                                       name="additional_feature_value[<?php echo esc_attr($i); ?>]"
                                       id="additional_feature_value_<?php echo esc_attr($i); ?>"
                                       value="<?php echo esc_attr($additional_feature_value[$i]); ?>">
                            </td>

                            <td>
                                    <span data-remove="<?php echo esc_attr($i); ?>" class="remove-additional-feature"><i
                                            class="fa fa-remove"></i></span>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td colspan="3">
                        <button type="button" data-increment="<?php echo esc_attr($additional_features - 1); ?>"
                                class="add-additional-feature"><i
                                class="fa fa-plus"></i> <?php esc_html_e('Add New', 'essential-real-estate'); ?></button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</div>