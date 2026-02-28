<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $property
 * @var $action
 */
do_action('ere_property_submitted_content_before', sanitize_title($property->post_status), $property);
?>
    <div class="property-submitted-content">
        <div class="ere-message alert alert-success" role="alert">
            <?php
            switch ($property->post_status) :
                case 'publish' :
                    if($action=='new')
                    {
                        printf(wp_kses_post(__('<strong>Success!</strong> Your property was submitted successfully. To view your property listing <a class="accent-color" href="%s">click here</a>.', 'essential-real-estate')), get_permalink($property->ID));
                    }
                    else
                    {
                        printf(wp_kses_post(__('<strong>Success!</strong> Your changes have been saved. To view your property listing <a class="accent-color" href="%s">click here</a>.', 'essential-real-estate')), get_permalink($property->ID));
                    }
                    break;
                case 'pending' :
                    if($action=='new')
                    {
                        printf(wp_kses_post(__('<strong>Success!</strong> Your property was submitted successfully. Once approved, your listing will be visible on the site.', 'essential-real-estate')), get_permalink($property->ID));
                    }
                    else{
                        echo  wp_kses_post(__('<strong>Success!</strong> Your changes have been saved. Once approved, your listing will be visible on the site.', 'essential-real-estate'));
                    }
                    break;
                default :
                    do_action('ere_property_submitted_content_' . str_replace('-', '_', sanitize_title($property->post_status)), $property);
                    break;
            endswitch;
            ?></div>
    </div>
<?php
do_action('ere_property_submitted_content_after', sanitize_title($property->post_status), $property);