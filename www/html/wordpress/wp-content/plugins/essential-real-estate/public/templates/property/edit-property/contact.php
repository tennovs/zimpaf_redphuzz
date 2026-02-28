<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 10/11/16
 * Time: 1:52 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $property_data, $property_meta_data,$hide_property_fields;
$agent_display_option = isset($property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option'][0]) ? $property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option'][0] : 'none';
$property_agent = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_agent'][0]) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_agent'][0] : '';
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Contact Information', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-contact row">
        <div class="col-sm-6">
            <p><?php esc_html_e('What to display in contact information box?', 'essential-real-estate'); ?></p>
            <?php if (!in_array("author_info", $hide_property_fields)) : ?>
            <div class="radio">
                <label><input value="author_info" <?php checked($agent_display_option, 'author_info'); ?> type="radio"
                              name="agent_display_option"><?php esc_html_e('My profile information', 'essential-real-estate'); ?>
                </label>
            </div>
            <?php endif;?>
            <?php if (!in_array("other_info", $hide_property_fields)) : ?>
            <div class="radio">
                <label><input value="other_info" <?php checked($agent_display_option, 'other_info'); ?> type="radio"
                              name="agent_display_option"><?php esc_html_e('Other contact', 'essential-real-estate'); ?>
                </label>
            </div>
            <div id="property_other_contact" style="display: <?php if($agent_display_option=='other_info') {echo 'block;';} else {echo 'none;';}?>">
                <div class="form-group">
                    <label
                        for="property_other_contact_name"><?php esc_html_e('Other contact Name', 'essential-real-estate'); ?></label>
                    <input type="text" id="property_other_contact_name" class="form-control" name="property_other_contact_name"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name'][0]);
                           } ?>">

                </div>
                <div class="form-group">
                    <label
                        for="property_other_contact_mail"><?php esc_html_e('Other contact Email', 'essential-real-estate'); ?></label>
                    <input type="text" id="property_other_contact_mail" class="form-control" name="property_other_contact_mail"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_mail'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_mail'][0]);
                           } ?>">
                </div>
                <div class="form-group">
                    <label
                        for="property_other_contact_phone"><?php esc_html_e('Other contact Phone', 'essential-real-estate'); ?></label>
                    <input type="text" id="property_other_contact_phone" class="form-control" name="property_other_contact_phone"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_phone'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_phone'][0]);
                           } ?>">
                </div>
                <div class="form-group">
                    <label
                        for="property_other_contact_description"><?php esc_html_e('Other contact more info', 'essential-real-estate'); ?></label>
                    <textarea rows="3" id="property_other_contact_description" class="form-control" name="property_other_contact_description"><?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_description'])) {
                            echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_description'][0]);
                        } ?></textarea>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>