<?php
/**
 * @var $property_id
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$map_icons_path_marker = ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
$default_marker = ere_get_option('marker_icon', '');
if ($default_marker != '') {
    if (is_array($default_marker) && $default_marker['url'] != '') {
        $map_icons_path_marker = $default_marker['url'];
    }
}
$location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
$lat = $lng = $nearby_places_field = $PlaceArray = $nearby_places_field_type = $nearby_places_field_label = $nearby_places_field_icon = '';
if (!empty($location) && !empty($location['location'])) {
    list($lat, $lng) = explode(',', $location['location']);
} else {
    return;
}
$nearby_places_radius = ere_get_option('nearby_places_radius');
$nearby_places_rank_by = ere_get_option('nearby_places_rank_by');
$set_map_height = ere_get_option('set_map_height');
$nearby_places_distance_in = ere_get_option('nearby_places_distance_in');
$nearby_places_field = ere_get_option('nearby_places_field');
if ($nearby_places_field != "") {
    foreach ($nearby_places_field as $data) {
        $PlaceArray .= "'" . (isset($data['nearby_places_select_field_type']) ? $data['nearby_places_select_field_type'] : '') . "',";
        $nearby_places_field_type .= (isset($data['nearby_places_select_field_type']) ? $data['nearby_places_select_field_type'] : '') . ",";
        $nearby_places_field_label .= (isset($data['nearby_places_field_label']) ? $data['nearby_places_field_label'] : '') . ",";
        $nearby_places_field_icon .= (isset($data['nearby_places_field_icon']['url']) ? $data['nearby_places_field_icon']['url'] : '') . ",";
    }
}
if (empty($nearby_places_radius)) {
    $nearby_places_radius = '5000';
}
if (empty($set_map_height)) {
    $set_map_height = '475';
}
$google_map_style = ere_get_option('googlemap_style', '');
$googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
wp_localize_script(ERE_PLUGIN_PREFIX . 'main', 'ere_property_map_vars',
    array(
        'google_map_style' => $google_map_style
    )
);
wp_enqueue_script('google-map');
?>
<div class="single-property-element property-nearby-places">
    <div class="ere-heading-style2">
        <h2><?php esc_html_e('Nearby Places', 'essential-real-estate'); ?></h2>
    </div>
    <div class="ere-property-element row">
        <div class="col-md-5 col-sm-12 col-xs-12 near-location-map"
             style="height:<?php echo esc_attr($set_map_height) ?>px;">
            <div class="near-location-map" style="width:100%;height:100%;">
                <div id="googleMapNearestPlaces"
                     style="width:100%;height:100%;"></div>
            </div>
        </div>
        <div class="col-md-7 col-sm-12 col-xs-12 nearby-places-detail" id="nearby-places-detail"></div>
    </div>
</div>
<script>
    (function ($) {
        "use strict";
        var G5PlusGoogleMap = {
            init: function () {
                G5PlusGoogleMap.settingMap();
            },
            settingMap: function () {
                var map, lat = "<?php echo esc_js($lat) ?>", lng = "<?php echo esc_js($lng) ?>", infowindow, i;
                var bounds = new google.maps.LatLngBounds();
                var map_icons_path_marker = '<?php echo esc_url_raw($map_icons_path_marker) ?>';
                var PlaceArray = [<?php echo $PlaceArray; ?>];
                var PlacePlaceArray = '<?php echo esc_js($nearby_places_field_type); ?>'.split(',');
                var PlaceLabelArray = '<?php echo esc_js($nearby_places_field_label); ?>'.split(',');
                var PlaceIconArray = '<?php echo esc_js($nearby_places_field_icon); ?>'.split(',');
                var distance_in = '<?php echo $nearby_places_distance_in ?>';
                var Place_Counter = 0;
                var rank_by = '<?php echo $nearby_places_rank_by ?>';
                var PlaceDetail = [];
                for (var n = 0; n < PlacePlaceArray.length; n++) {
                    PlaceDetail[PlacePlaceArray[n]] = [PlaceLabelArray[n], PlaceIconArray[n]];
                }
                function initialize() {
                    "use strict";
                    var myLatLng = new google.maps.LatLng(lat, lng);
                    infowindow = new google.maps.InfoWindow();
                    map = new google.maps.Map(document.getElementById('googleMapNearestPlaces'), {
                        center: myLatLng,
                        icon: map_icons_path_marker,
                        scrollwheel: false,
                        fullscreenControl: true

                    });
                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        icon: map_icons_path_marker
                    });
                    marker.setMap(map);
                    var request = '';
                    if (rank_by == 'distance') {
                        request = {
                            location: myLatLng,
                            type: PlaceArray,
                            rankBy: google.maps.places.RankBy.DISTANCE
                        };
                    } else {
                        request = {
                            location: myLatLng,
                            radius: '<?php echo esc_js($nearby_places_radius) ?>',
                            type: PlaceArray
                        };
                    }

                    var service = new google.maps.places.PlacesService(map);
                    service.nearbySearch(request, callback);
                }

                function callback(results, status) {
                    "use strict";
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        for (var i = 0; i < results.length; i++) {
                            createMarker(results[i]);
                        }
                        setScroll();
                    }
                    else {
                        $('.nearby-places-detail').append('<p><?php  esc_html_e( 'No result!', 'essential-real-estate' ) ?></p>');
                    }
                }

                function createMarker(place) {
                    "use strict";

                    var PlaceType = "";
                    jQuery.each(place.types, function (key, value) {
                        if (jQuery.inArray(value, PlaceArray) != -1) {
                            PlaceType = value;
                        }
                    });
                    if (PlaceType == "") {
                        return;
                    }
                    Place_Counter++;
                    var Distance = distance(place.geometry.location.lat(), place.geometry.location.lng());
                    var place_label = PlaceDetail[PlaceType][0];
                    var place_icon = PlaceDetail[PlaceType][1];

                    jQuery("#nearby-places-detail").append("<div class='near-location-info'><ul><li class='dotted-left'>" + place.name + "</li><li class='dotted-right'><span>" + Distance + " " + distance_in + "</span></li></ul><span>" + place_label + "</span></div>");
                    var marker = new google.maps.Marker({
                        map: map,
                        position: place.geometry.location,
                        icon: place_icon
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.setContent('<strong>' + place_label + '</strong>' + '</br>' + place.name);
                        infowindow.open(map, this);
                    });
                    bounds.extend(marker.position);
                    //now fit the map to the newly inclusive bounds
                    map.fitBounds(bounds);
                    var google_map_style = ere_property_map_vars.google_map_style;
                    if (google_map_style !== '') {
                        var styles = JSON.parse(google_map_style);
                        map.setOptions({styles: styles});
                    }
                    var boundsListener = google.maps.event.addListener((map), 'idle', function (event) {
                        this.setZoom(<?php echo esc_js($googlemap_zoom_level); ?>);
                        google.maps.event.removeListener(boundsListener);
                    });
                }

                google.maps.event.addDomListener(window, 'load', initialize);
                function distance(latitude, longitude) {
                    var lat1 = lat;
                    var lng1 = lng;
                    var lat2 = latitude;
                    var lng2 = longitude;
                    var radlat1 = Math.PI * lat1 / 180;
                    var radlat2 = Math.PI * lat2 / 180;
                    var theta = lng1 - lng2;
                    var radtheta = Math.PI * theta / 180;
                    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
                    dist = Math.acos(dist);
                    dist = dist * 180 / Math.PI;
                    dist = dist * 60 * 1.1515;
                    if (distance_in == "km") {
                        dist = dist * 1.609344;
                    } else if (distance_in == "m") {
                        dist = dist * 1.609344 * 1000;
                    }
                    var result = Math.round(dist * 100) / 100;
                    result = result.toLocaleString().replace(/[^\d.]/ig, '<?php echo ere_get_price_decimal_separator(); ?>');
                    return result;
                }

                function setScroll() {
                    var $this = $('#nearby-places-detail');
                    var map_height = $('#googleMapNearestPlaces');
                    var height = $this.height();
                    if (height >= map_height.height()) {
                        $this.css('position', 'relative');
                        $this.css('max-height', +map_height.height());
                        $this.css('overflow-y', 'scroll');
                        $this.css('overflow-x', 'hidden');
                        $this.perfectScrollbar({
                            wheelSpeed: 0.5,
                            suppressScrollX: true
                        });
                    }
                }
            }
        };
        $(document).ready(G5PlusGoogleMap.init);
    })(jQuery);
</script>
