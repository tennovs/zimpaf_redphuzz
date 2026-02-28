<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 7/15/2017
 * Time: 11:20 PM
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $request_min_price
 * @var $request_max_price
 * @var $request_status
 * @var $price_is_slider
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ($price_is_slider=='true'):
$min_price = ere_get_option('property_price_slider_min',200);
$max_price = ere_get_option('property_price_slider_max',2500000);
if ($request_status !== '') {
	$property_price_slider_search_field = ere_get_option('property_price_slider_search_field','');
	if ($property_price_slider_search_field != '') {
		foreach ($property_price_slider_search_field as $data) {
			$term_id =(isset($data['property_price_slider_property_status']) ? $data['property_price_slider_property_status'] : '');
			$term = get_term_by('id', $term_id, 'property-status');
			if($term)
			{
				if($term->slug==$request_status)
				{
					$min_price = (isset($data['property_price_slider_min']) ? $data['property_price_slider_min'] : $min_price);
					$max_price = (isset($data['property_price_slider_max']) ? $data['property_price_slider_max'] : $max_price);
					break;
				}
			}
		}
	}
}

?>
<div class="ere-sliderbar-price-wrap <?php echo esc_attr($css_class_field); ?> form-group">
	<?php
		$min_price_change = ($request_min_price === '') ? $min_price : $request_min_price;
		$max_price_change = ($request_max_price === '') ? $max_price : $request_max_price;
	?>
    <div class="ere-sliderbar-price ere-sliderbar-filter"
         data-min-default="<?php echo esc_attr($min_price) ?>"
         data-max-default="<?php echo esc_attr($max_price); ?>"
         data-min="<?php echo esc_attr($min_price_change) ?>"
         data-max="<?php echo esc_attr($max_price_change); ?>">
        <div class="title-slider-filter">
            <?php esc_html_e('Price', 'essential-real-estate') ?> [<span
                class="min-value"><?php echo ere_get_format_money($min_price_change) ?></span> - <span
                class="max-value"><?php echo ere_get_format_money($max_price_change) ?></span>]
            <input type="hidden" name="min-price" class="min-input-request"
                   value="<?php echo esc_attr($min_price_change) ?>">
            <input type="hidden" name="max-price" class="max-input-request"
                   value="<?php echo esc_attr($max_price_change) ?>">
        </div>
        <div class="sidebar-filter">
        </div>
    </div>
</div>
<?php else:
	$property_price_dropdown_min= apply_filters('ere_price_dropdown_min_default', ere_get_option('property_price_dropdown_min','0,100,300,500,700,900,1100,1300,1500,1700,1900')) ;
	$property_price_dropdown_max= apply_filters('ere_price_dropdown_max_default', ere_get_option('property_price_dropdown_max','200,400,600,800,1000,1200,1400,1600,1800,2000')) ;
    $property_price_dropdown_search_field = ere_get_option('property_price_dropdown_search_field','');
    if ($property_price_dropdown_search_field != '') {
        foreach ($property_price_dropdown_search_field as $data) {
            $term_id =(isset($data['property_price_dropdown_property_status']) ? $data['property_price_dropdown_property_status'] : '');
            $term = get_term_by('id', $term_id, 'property-status');
            if($term)
            {
                if($term->slug==$request_status)
                {
                    $property_price_dropdown_min = (isset($data['property_price_dropdown_min']) ? $data['property_price_dropdown_min'] : $property_price_dropdown_min);
                    $property_price_dropdown_max = (isset($data['property_price_dropdown_max']) ? $data['property_price_dropdown_max'] : $property_price_dropdown_max);
                    break;
                }
            }
        }
    }
    ?>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="min-price" title="<?php esc_attr_e('Min Price', 'essential-real-estate') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php esc_html_e('Min Price', 'essential-real-estate') ?>
            </option>
            <?php
            $property_price_array = explode(',', $property_price_dropdown_min);
            if (is_array($property_price_array) && !empty($property_price_array)) {
                foreach ($property_price_array as $n) {
                    ?>
                    <option
                        value="<?php echo esc_attr($n) ?>" <?php if ($n == $request_min_price) {
                        echo esc_attr('selected');
                    } ?>><?php echo ere_get_format_money_search_field($n); ?>
                    </option>
                    <?php
                }
            } ?>
        </select>
    </div>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="max-price" title="<?php esc_attr_e('Max Price', 'essential-real-estate') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php esc_html_e('Max Price', 'essential-real-estate') ?>
            </option>
            <?php
            $property_price_array = explode(',', $property_price_dropdown_max);
            if (is_array($property_price_array) && !empty($property_price_array)) {
                foreach ($property_price_array as $n) {
                    ?>
                    <option value="<?php echo  esc_attr($n) ?>" <?php if ($n == $request_max_price) {
                        echo esc_attr('selected');
                    } ?>><?php echo ere_get_format_money_search_field($n); ?>
                    </option>
                    <?php
                }
            } ?>
        </select>
    </div>
<?php endif; ?>