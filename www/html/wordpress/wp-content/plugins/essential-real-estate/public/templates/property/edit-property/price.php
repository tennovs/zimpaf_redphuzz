<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_property_fields,$property_data, $property_meta_data;
$dec_point = ere_get_price_decimal_separator();
$property_price_short_format = '^[0-9]+([' . $dec_point . '][0-9]+)?$';
$property_price_short = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_short' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_short' ][0] : '';
$property_price_short = ere_format_localized_price($property_price_short);
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Property Price', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-price row">
        <?php
        if (!in_array("property_price", $hide_property_fields)) {
            $enable_price_unit=ere_get_option('enable_price_unit', '1');
            $price_short_class='col-sm-6';
            if($enable_price_unit=='1')
            {
                $price_short_class='col-sm-3';
            }
        ?>
            <div class="<?php echo esc_attr($price_short_class); ?>">
                <div class="form-group">
                    <label for="property_price_short"> <?php esc_html_e( 'Price', 'essential-real-estate' ); echo ere_required_field( 'property_price' );
                        echo esc_html(ere_get_option('currency_sign', '')) . ' ';?>  </label>
	                <input pattern="<?php echo esc_attr($property_price_short_format)?>" type="text" id="property_price_short" class="form-control" name="property_price_short"
	                       value="<?php echo esc_attr($property_price_short)?>">
	                <small class="form-text text-muted"><?php echo sprintf(esc_html__('Example Value: 12345%s05','essential-real-estate'),$dec_point)  ?></small>
                </div>
            </div>
            <?php if($enable_price_unit=='1'){?>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="property_price_unit"><?php esc_html_e('Unit', 'essential-real-estate');
                            echo ere_required_field('property_price_unit'); ?></label>
                        <select name="property_price_unit" id="property_price_unit" class="form-control">
                            <option value="1" <?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'] ) &&  $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'][0]=='1') { echo 'selected';} ?>><?php esc_html_e('None', 'essential-real-estate');?></option>
                            <option value="1000" <?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'] ) &&  $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'][0]=='1000') { echo 'selected';} ?>><?php esc_html_e('Thousand', 'essential-real-estate');?></option>
                            <option value="1000000" <?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'] ) &&  $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'][0]=='1000000') { echo 'selected';} ?>><?php esc_html_e('Million', 'essential-real-estate');?></option>
                            <option value="1000000000" <?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'] ) &&  $property_meta_data[ERE_METABOX_PREFIX. 'property_price_unit'][0]=='1000000000') { echo 'selected';} ?>><?php esc_html_e('Billion', 'essential-real-estate');?></option>
                        </select>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?php
        if (!in_array("property_price_prefix", $hide_property_fields)) {
            ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="property_price_prefix"><?php esc_html_e( 'Before Price Label (ex: Start From)', 'essential-real-estate' ); echo ere_required_field( 'property_price_prefix' ); ?></label>
                    <input type="text" id="property_price_prefix" value="<?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_prefix'] ) ) { echo esc_attr( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_prefix'][0] ); } ?>" class="form-control" name="property_price_prefix">
                </div>
            </div>
        <?php } ?>
        <?php
        if (!in_array("property_price_postfix", $hide_property_fields)) {
            ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="property_price_postfix"><?php esc_html_e( 'After Price Label (ex: Per Month)', 'essential-real-estate' ); echo ere_required_field( 'property_price_postfix' ); ?></label>
                    <input type="text" id="property_price_postfix" value="<?php if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_postfix'] ) ) { echo esc_attr( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_postfix'][0] ); } ?>" class="form-control" name="property_price_postfix">
                </div>
            </div>
        <?php } ?>
        <?php
        if (!in_array("property_price_on_call", $hide_property_fields)) {?>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="property_price_on_call" name="property_price_on_call" <?php
                            if( isset( $property_meta_data[ERE_METABOX_PREFIX. 'property_price_on_call'] ) && $property_meta_data[ERE_METABOX_PREFIX. 'property_price_on_call'][0]=='1') echo ' checked="checked"'?>
                                ><?php esc_html_e( 'Price on Call', 'essential-real-estate' ); echo ere_required_field( 'property_price_on_call' ); ?>
                        </label>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>