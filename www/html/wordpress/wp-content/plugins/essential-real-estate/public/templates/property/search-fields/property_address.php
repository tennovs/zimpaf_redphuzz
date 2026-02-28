<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_address
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <input type="text" class="ere-location form-control search-field" data-default-value=""
           value="<?php echo esc_attr($request_address); ?>"
           name="address"
           placeholder="<?php esc_attr_e('Address', 'essential-real-estate') ?>">
</div>