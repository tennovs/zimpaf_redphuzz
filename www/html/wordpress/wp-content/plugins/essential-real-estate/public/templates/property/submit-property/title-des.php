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
global $hide_property_fields;
?>
<div class="property-fields-wrap">
    <div class="property-fields property-title-des row">
        <div class="col-sm-12 mg-bottom-50">
            <div class="form-group">
                <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
                    <h2><?php esc_html_e( 'Property Title', 'essential-real-estate' ); echo ere_required_field( 'property_title' );?></h2>
                </div>
                <input type="text" id="property_title" class="form-control" name="property_title"/>
            </div>
        </div>
        <?php if (!in_array("property_des", $hide_property_fields)) {?>
        <div class="col-sm-12">
            <div class="form-group">
                <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
                    <h2><?php esc_html_e( 'Property Description', 'essential-real-estate' ); ?></h2>
                </div>
                <?php
                $content = '';
                $editor_id = 'property_des';
                $settings = array(
                    'wpautop' => true,
                    'media_buttons' => false,
                    'textarea_name' => $editor_id,
                    'textarea_rows' => get_option('default_post_edit_rows', 10),
                    'tabindex' => '',
                    'editor_css' => '',
                    'editor_class' => '',
                    'teeny' => false,
                    'dfw' => false,
                    'tinymce' => true,
                    'quicktags' => true
                );
                wp_editor($content, $editor_id, $settings); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
