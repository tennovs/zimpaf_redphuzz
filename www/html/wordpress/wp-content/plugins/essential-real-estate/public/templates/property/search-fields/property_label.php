<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_label
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="label" title="<?php esc_attr_e('Property Label', 'essential-real-estate') ?>"
            class="search-field form-control" data-default-value="">
        <?php ere_get_taxonomy_slug('property-label', $request_label); ?>
        <option
            value="" <?php if (empty($request_label)) echo 'selected'; ?>>
            <?php esc_html_e('All Labels', 'essential-real-estate') ?></option>
    </select>
</div>