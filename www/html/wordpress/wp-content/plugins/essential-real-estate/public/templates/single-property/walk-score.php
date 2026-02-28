<?php
/**
 * @var $property_id
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$walkscore_api_key = ere_get_option('walk_score_api_key', '');
if ($walkscore_api_key != '') {
    $location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
    $lat = $lng = $address = '';
    if (!empty($location) && !empty($location['location'])) {
        list($lat, $lng) = explode(',', $location['location']);
        $address = $location['address'];
        $address = urlencode($address);
    } else {
        return;
    }
    $key = sanitize_title($address . $lat . $lng);
    $response = wp_remote_get("http://api.walkscore.com/score?format=json&transit=1&bike=1&address=$address&lat=$lat&lon=$lng&wsapikey=$walkscore_api_key");
    if (is_array($response)) {
        $response = json_decode($response['body'], true);
        ?>
        <div class="single-property-element property-walkscore walkscore-wrap">
            <div class="ere-heading-style2">
                <h2><?php esc_html_e('Walk Score', 'essential-real-estate'); ?></h2>
            </div>
            <div class="ere-property-element">
                <div class="walkscore-logo">
                    <a href="https://www.walkscore.com" target="_blank">
                        <img src="https://cdn.walk.sc/images/api-logo.png"
                             alt="<?php esc_attr_e('Walk Scores', 'essential-real-estate'); ?>">
                    </a>
                </div>
                <ul class="walkscore-list">
                    <?php if (isset($response['status']) && $response['status'] == 1) : ?>
                        <?php if (isset($response['walkscore'])) : ?>
                            <li>
                            <span
                                class="walkscore-score"><?php echo wp_kses_post($response['walkscore']); ?></span>

                                <div class="walkscore-info">
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"><strong><?php esc_html_e('Walk Scores', 'essential-real-estate'); ?></strong></a>
                                    <address>
                                        <?php echo wp_kses_post($response['description']); ?>
                                    </address>
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"
                                       class="walk-score-more-detail"><?php esc_html_e('View more', 'essential-real-estate'); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($response['transit']) && !empty($response['transit']['score'])) : ?>
                            <li class="walkscore-transit">
                            <span
                                class="walkscore-score"><?php echo wp_kses_post($response['transit']['score']); ?></span>

                                <div class="walkscore-info">
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"><strong><?php esc_html_e('Transit Score', 'essential-real-estate'); ?></strong></a>
                                    <address>
                                        <?php echo wp_kses_post($response['transit']['description']); ?>
                                    </address>
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"
                                       class="walk-score-more-detail"><?php esc_html_e('View more', 'essential-real-estate'); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($response['bike']) && !empty($response['bike']['score'])) : ?>
                            <li class="walkscore-bike">
                            <span
                                class="walkscore-score"><?php echo wp_kses_post($response['bike']['score']); ?></span>

                                <div class="walkscore-info">
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"><strong><?php esc_html_e('Bike Score', 'essential-real-estate'); ?></strong></a>
                                    <address>
                                        <?php echo wp_kses_post($response['bike']['description']); ?>
                                    </address>
                                    <a href="<?php echo esc_url($response['ws_link']); ?>"
                                       class="walk-score-more-detail"><?php esc_html_e('View more', 'essential-real-estate'); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>

                    <?php else: ?>
                        <li>
                            <?php esc_html_e('An error occurred while fetching walk scores.', 'essential-real-estate'); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php
    }
}