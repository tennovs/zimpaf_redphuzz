<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_status
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="status" title="<?php esc_attr_e('Property Status', 'essential-real-estate') ?>"
            class="search-field form-control" data-default-value="">
        <?php ere_get_property_status_search_slug($request_status); ?>
	    <option value="" <?php if (empty($request_status)) echo esc_attr('selected'); ?>>
		    <?php esc_html_e('All Status', 'essential-real-estate') ?>
	    </option>
    </select>
</div>