<?php
/**
 * @var $property_id
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$title = get_the_title($property_id);
$location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
$lat = $lng = '';
if (!empty($location) && !empty($location['location'])) {
    list($lat, $lng) = explode(',', $location['location']);
} else {
    return;
}

if (empty($lat) || empty($lng)) {
	return;
}


$map_icons_path_marker = ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
$default_marker = ere_get_option('marker_icon', '');
if ($default_marker != '') {
    if (is_array($default_marker) && $default_marker['url'] != '') {
        $map_icons_path_marker = $default_marker['url'];
    }
}
wp_enqueue_script('google-map');
$google_map_style = ere_get_option('googlemap_style', '');
$googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
$map_directions_distance_units = ere_get_option('map_directions_distance_units', 'metre');
wp_localize_script(ERE_PLUGIN_PREFIX . 'main', 'ere_property_map_vars',
    array(
        'google_map_style' => $google_map_style
    )
);
$map_id = 'map-' . uniqid();
?>
<div class="single-property-element property-google-map-directions ere-google-map-directions">
    <div class="ere-heading-style2">
        <h2><?php esc_html_e('Get Directions', 'essential-real-estate'); ?></h2>
    </div>
    <div class="ere-property-element">
        <div id="<?php echo esc_attr($map_id) ?>" class="ere-google-map-direction">
        </div>
        <div class="ere-directions">
            <input id="directions-input" class="controls" type="text"
                   placeholder="<?php esc_attr_e('Enter a location', 'essential-real-estate'); ?>">
            <button type="button" id="get-direction"><i class="fa fa-search"></i></button>
            <p id="total"></p>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        var bounds = new google.maps.LatLngBounds();
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var isDraggable = w > 1024 ? true : false;
        var mapOptions = {
            mapTypeId: 'roadmap',
            draggable: isDraggable,
            scrollwheel: false
        };
        var map = new google.maps.Map(document.getElementById("<?php echo esc_attr($map_id) ?>"), mapOptions);

        var infoWindow = new google.maps.InfoWindow(), marker, i;
        var property_position = new google.maps.LatLng(<?php echo esc_html($lat) ?>, <?php echo esc_html($lng) ?>);
        bounds.extend(property_position);
        marker = new google.maps.Marker({
            position: property_position,
            map: map,
            title: '<?php echo esc_html($title) ?>',
            animation: google.maps.Animation.DROP,
            icon: '<?php echo esc_html($map_icons_path_marker) ?>'
        });
        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                infoWindow.setContent('<h6>' + '<?php echo esc_html($title) ?>' + '</h6>');
                infoWindow.open(map, marker);
            }
        })(marker));
        map.fitBounds(bounds);
        var google_map_style = ere_property_map_vars.google_map_style;
        if (google_map_style !== '') {
            var styles = JSON.parse(google_map_style);
            map.setOptions({styles: styles});
        }
        var boundsListener = google.maps.event.addListener((map), 'idle', function (event) {
            this.setZoom(<?php echo esc_html($googlemap_zoom_level); ?>);
            google.maps.event.removeListener(boundsListener);
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        directionsDisplay.setMap(map);

        directionsDisplay.addListener('directions_changed', function () {
            ereGetTotalDistance(directionsDisplay.getDirections());
        });

        var ere_get_directions = function () {
            ereDisplayRoute(directionsService, directionsDisplay, marker);
        };

        document.getElementById('get-direction').addEventListener('click', ere_get_directions);

        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('directions-input'));
        autocomplete.bindTo('bounds', map);

        function ereDisplayRoute(directionsService, directionsDisplay, marker) {
            directionsService.route({
                origin: property_position,
                destination: document.getElementById('directions-input').value,
                travelMode: 'DRIVING'
            }, function (response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    marker.setVisible(false);
                    directionsDisplay.setDirections(response);
                }
            });
        }

        function ereGetTotalDistance(result) {
            var total = 0;
            var unit = "metre";
            var myroute = result.routes[0];
            for (var i = 0; i < myroute.legs.length; i++) {
                total += myroute.legs[i].distance.value;
            }
            unit = "<?php echo esc_html($map_directions_distance_units); ?>";
            document.getElementById('total').style.display = 'inline-block';
            if (unit == "kilometre") {
                total = total / 1000;
                document.getElementById('total').innerHTML = '<?php esc_html_e('Distance:','essential-real-estate'); ?> ' + total + ' km';
            }
            else if (unit == "mile") {
                total = total * 0.000621371;
                document.getElementById('total').innerHTML = '<?php esc_html_e('Distance:','essential-real-estate'); ?> ' + total + ' mi';
            }
            else {
                document.getElementById('total').innerHTML = '<?php esc_html_e('Distance:','essential-real-estate'); ?> ' + total + ' m';
            }
        }
    });
</script>