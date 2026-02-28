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
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Property Type', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-type row">
        <?php if (!in_array("property_type", $hide_property_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="property_type"><?php esc_html_e('Type', 'essential-real-estate');
                        echo ere_required_field('property_type'); ?></label>
                    <select name="property_type[]" id="property_type" class="form-control" multiple>
                        <?php ere_get_taxonomy_by_post_id($property_data->ID, 'property-type',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_status", $hide_property_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="property_status"><?php esc_html_e('Status', 'essential-real-estate');?></label>
                    <select  name="property_status[]" id="property_status" class="form-control" multiple>
                        <?php ere_get_taxonomy_by_post_id($property_data->ID, 'property-status',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php
        if (!in_array("property_label", $hide_property_fields)) { ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="property_label"><?php esc_html_e('Label', 'essential-real-estate');
                        echo ere_required_field('property_label'); ?></label>
                    <select name="property_label[]" id="property_label" class="form-control" multiple>
                        <?php ere_get_taxonomy_by_post_id($property_data->ID, 'property-label',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>
    </div>
</div>