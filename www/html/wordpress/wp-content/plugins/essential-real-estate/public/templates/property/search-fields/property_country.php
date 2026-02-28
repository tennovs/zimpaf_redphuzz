<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_country
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="country" class="ere-property-country-ajax search-field form-control" title="<?php esc_attr_e('Countries', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_country); ?>" data-default-value="">
        <?php
        $countries = ere_get_selected_countries();
        foreach ($countries as $key => $val) {
            if($key == $request_country){
                echo '<option value="' . $key . '" selected>' . $val . '</option>';
            }else{
                echo '<option value="' . $key . '">' . $val . '</option>';
            }
        }
        ?>
        <option
            value="" <?php if (empty($request_country)) echo esc_attr('selected'); ?>>
            <?php esc_html_e('All Countries', 'essential-real-estate'); ?>
        </option>
    </select>
</div>