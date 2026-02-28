<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_keyword_title
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="city" class="ere-property-city-ajax search-field form-control" title="<?php esc_attr_e('Cities', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_city); ?>" data-default-value="">
        <?php if(!empty($request_city)):?>
            <?php ere_get_taxonomy_slug('property-city', $request_city); ?>
            <option value="" >
                <?php esc_html_e('All Cities', 'essential-real-estate'); ?>
            </option>
        <?php else:?>
            <?php ere_get_taxonomy_slug('property-city'); ?>
            <option value="" selected="selected">
                <?php esc_html_e('All Cities', 'essential-real-estate'); ?>
            </option>
        <?php endif;?>
    </select>
</div>