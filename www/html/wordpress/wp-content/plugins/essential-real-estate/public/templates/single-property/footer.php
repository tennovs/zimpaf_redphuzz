<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
?>
<div class="single-property-element property-info-footer">
    <div class="ere-property-element">
        <?php $enable_create_date = ere_get_option('enable_create_date', 1);
        if ($enable_create_date == 1):?>
            <span class="property-date">
		        <i class="fa fa-calendar"></i> <?php echo get_the_time(get_option('date_format')); ?>
	        </span>
        <?php endif; ?>
        <?php $enable_views_count = ere_get_option('enable_views_count', 1);
        if ($enable_views_count == 1):?>
            <span class="property-views-count">
		        <i class="fa fa-eye"></i>
                <?php
                $ere_property = new ERE_Property();
                $total_views = $ere_property->get_total_views($post->ID);
                printf(_n('%s view', '%s views', $total_views, 'essential-real-estate'), ere_get_format_number($total_views));
                ?>
	        </span>
        <?php endif; ?>
    </div>
</div>