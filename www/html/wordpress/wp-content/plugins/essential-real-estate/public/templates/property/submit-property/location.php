<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:45 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_property_fields;
$location_dropdowns = ere_get_option('location_dropdowns', 1);
$default_country = ere_get_option('default_country', 'US');
$default_city = ere_get_option('default_city', '');
$googlemap_coordinate_default = ere_get_option('googlemap_coordinate_default', '-33.868419,151.193245');
list( $lat, $long ) =  (!empty($googlemap_coordinate_default) && strpos($googlemap_coordinate_default,',')) ? explode( ',', $googlemap_coordinate_default ) : array('', '');
wp_enqueue_style( 'select2_css');
wp_enqueue_script('select2_js');
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e('Property Location', 'essential-real-estate'); ?></h2>
    </div>
    <div class="property-fields property-location row">
        <?php if (!in_array("property_map_address", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="geocomplete"><?php echo esc_html__('Address', 'essential-real-estate') . ere_required_field('property_map_address'); ?></label>
                    <input type="text" class="form-control" name="property_map_address" id="geocomplete"
                           value=""
                           placeholder="<?php esc_attr_e('Enter property address', 'essential-real-estate'); ?>">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("country", $hide_property_fields)) { ?>
            <div class="col-sm-4 submit_country_field">
                <div class="form-group ere-loading-ajax-wrap">
                    <label for="country"><?php echo esc_html__('Country', 'essential-real-estate') . ere_required_field('country'); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="property_country" id="country" class="ere-property-country-ajax form-control">
                            <?php
                            $countries = ere_get_selected_countries();
                            foreach ($countries as $key => $country):
                                echo '<option ' . selected($default_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                            endforeach;
                            ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="country" id="country">
                        <input name="country_short" type="hidden" value="">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("state", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group ere-loading-ajax-wrap">
                    <label for="administrative_area_level_1"><?php echo  esc_html__('Province / State', 'essential-real-estate') . ere_required_field('state'); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="administrative_area_level_1" id="administrative_area_level_1" class="ere-property-state-ajax form-control">
                            <?php ere_get_taxonomy('property-state', true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="administrative_area_level_1" id="administrative_area_level_1">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("city", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group ere-loading-ajax-wrap">
                    <label for="city"><?php echo esc_html__('City / Town', 'essential-real-estate') . ere_required_field('city'); ?></label>
                    <?php if ($location_dropdowns == 1) {?>
                        <select name="locality" id="city" class="ere-property-city-ajax form-control">
                            <?php ere_get_taxonomy_slug('property-city',$default_city); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="locality" id="city">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("neighborhood", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group ere-loading-ajax-wrap">
                    <label for="neighborhood"><?php echo esc_html__('Neighborhood', 'essential-real-estate') . ere_required_field('neighborhood'); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="neighborhood" id="neighborhood" class="ere-property-neighborhood-ajax form-control">
                            <?php ere_get_taxonomy('property-neighborhood', true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="neighborhood" id="neighborhood">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("postal_code", $hide_property_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="zip"><?php echo  esc_html__('Postal Code / Zip', 'essential-real-estate') . ere_required_field('postal_code') ; ?></label>
                    <input type="text" class="form-control" name="postal_code" id="zip">
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e('Google Map Location', 'essential-real-estate'); ?></h2>
    </div>
    <div class="property-fields property-location row">
        <div class="col-sm-9">
            <div class="map_canvas" id="map" style="height: 300px">
            </div>

        </div>
        <div class="col-sm-3 xs-mg-top-30">
            <div class="form-group">
                <label for="latitude"><?php esc_html_e('Google Maps latitude', 'essential-real-estate'); ?></label>
                <input type="text" class="form-control" name="lat" id="latitude" value="<?php echo esc_attr($lat); ?>">
            </div>
            <div class="form-group">
                <label for="longitude"><?php esc_html_e('Google Maps longitude', 'essential-real-estate'); ?></label>
                <input type="text" class="form-control" name="lng" id="longitude" value="<?php echo esc_attr($long); ?>">
            </div>
            <div class="form-group">
                <input id="find" type="button" class="btn btn-primary btn-block" title="<?php esc_attr_e('Place the pin the address above', 'essential-real-estate'); ?>"
                       value="<?php esc_attr_e('Pin address', 'essential-real-estate'); ?>">
                <a id="reset" href="#"
                   style="display:none;"><?php esc_html_e('Reset Marker', 'essential-real-estate'); ?></a>
            </div>
        </div>
    </div>
</div>