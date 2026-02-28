<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_property_identity
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <input type="text" class="ere-property-identity form-control search-field" data-default-value=""
           value="<?php echo esc_attr($request_property_identity); ?>"
           name="property_identity"
           placeholder="<?php esc_attr_e('Property ID', 'essential-real-estate') ?>">
</div>