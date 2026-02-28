<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_state
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="state" class="ere-property-state-ajax search-field form-control" title="<?php esc_attr_e('States', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_state); ?>" data-default-value="">
        <?php ere_get_taxonomy_slug('property-state', $request_state); ?>
        <option value="" <?php if (empty($request_state)) echo esc_attr('selected'); ?>>
            <?php esc_html_e('All States', 'essential-real-estate'); ?>
        </option>
    </select>
</div>