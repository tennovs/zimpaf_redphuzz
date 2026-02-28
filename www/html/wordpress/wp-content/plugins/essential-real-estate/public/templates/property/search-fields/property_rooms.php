<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $request_rooms
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <select name="rooms" title="<?php esc_attr_e('Property Rooms', 'essential-real-estate') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php esc_html_e('Any Rooms', 'essential-real-estate') ?>
        </option>
        <?php
        $rooms_list = ere_get_option('rooms_list','1,2,3,4,5,6,7,8,9,10');
        $rooms_array = explode( ',', $rooms_list );
        if( is_array( $rooms_array ) && !empty( $rooms_array ) ) {
            foreach( $rooms_array as $n ) {
                ?>
                <option
                    value="<?php echo esc_attr($n) ?>" <?php if ($n == $request_rooms) {
                    echo esc_attr('selected');
                } ?>>
                    <?php echo esc_attr($n); ?>
                </option>
                <?php
            }
        }?>
    </select>
</div>