<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_neighborhood
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="neighborhood" class="ere-property-neighborhood-ajax search-field form-control" title="<?php esc_attr_e('Property Neighborhoods', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_neighborhood); ?>" data-default-value="">
        <?php ere_get_taxonomy_slug('property-neighborhood', $request_neighborhood); ?>
        <option value="" <?php if (empty($request_neighborhood)) echo esc_attr('selected'); ?>>
            <?php esc_html_e('All Neighborhoods', 'essential-real-estate'); ?>
        </option>
    </select>
</div>