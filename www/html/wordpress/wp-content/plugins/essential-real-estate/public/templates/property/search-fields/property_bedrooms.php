<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_bedrooms
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="bedrooms" title="<?php esc_attr_e('Property Bedrooms', 'essential-real-estate') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Bedrooms', 'essential-real-estate') ?>
        </option>
        <?php
        $bedrooms_list = ere_get_option('bedrooms_list','1,2,3,4,5,6,7,8,9,10');
        $bedrooms_array = explode( ',', $bedrooms_list );
        if( is_array( $bedrooms_array ) && !empty( $bedrooms_array ) ) {
            foreach( $bedrooms_array as $n ) {
                ?>
                <option
                    value="<?php echo esc_attr($n) ?>" <?php if ($n == $request_bedrooms) {
                    echo esc_attr('selected');
                } ?>>
                    <?php echo esc_attr($n); ?>
                </option>
                <?php
            }
        }?>
    </select>
</div>