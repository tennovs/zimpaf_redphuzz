<?php
/**
 * @var $form
 * @var $action
 * @var $property_id
 * @var $submit_button_text
 * @var $step
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_login'));
    return;
}
$allow_submit = ere_allow_submit();
if (!$allow_submit) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_allow_submit'));
    return;
}
global $property_data, $property_meta_data, $hide_property_fields, $current_user;
$hide_property_fields = ere_get_option('hide_property_fields', array());
if (!is_array($hide_property_fields)) {
    $hide_property_fields = array();
}
if ($form == 'edit-property') {
    $property_data = get_post($property_id);
    $property_meta_data = get_post_custom($property_data->ID);
} else {
    $paid_submission_type = ere_get_option('paid_submission_type', 'no');
    if ($paid_submission_type == 'per_package') {
        wp_get_current_user();
        $user_id = $current_user->ID;
        $ere_profile = new ERE_Profile();
        $check_package = $ere_profile->user_package_available($user_id);
        $select_packages_link = ere_get_permalink('packages');
        if ($check_package == 0) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('You are not yet subscribed to a listing! Before you can list a property, you must select a listing package. Click the button below to select a listing package.', 'essential-real-estate') . ' </div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Get a Listing Package', 'essential-real-estate') . '</a>';
            return;
        } elseif ($check_package == -1) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('Your current listing package has expired! Please click the button below to select a new listing package.', 'essential-real-estate') . '</div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Upgrade Listing Package', 'essential-real-estate') . '</a>';
            return;
        } elseif ($check_package == -2) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('Your current listing package doesn\'t allow you to publish any more properties! Please click the button below to select a new listing package.', 'essential-real-estate') . '</div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Upgrade Listing Package', 'essential-real-estate') . '</a>';
            return;
        }
    }
}
wp_enqueue_script('plupload');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-geocomplete');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_steps');
wp_print_styles(ERE_PLUGIN_PREFIX . 'submit-property');
?>
<section class="ere-property-multi-step">
    <?php
    $layout = ere_get_option('property_form_sections', array('title_des', 'location', 'type', 'price', 'features', 'details', 'media', 'floors', 'contact'));
    if (!in_array("private_note", $hide_property_fields)){
        $layout['private_note']='private_note';
    }
    unset($layout['sort_order']);
    $keys= array_keys($layout);
    $total=count($keys);
    ?>
    <div class="ere-steps">
        <?php
        $i=0;$step_name='';
        foreach ($layout as $value):
            $i++;
            switch ($value) {
                case 'title_des':
                    $step_name=esc_html__('Title & Description', 'essential-real-estate');
                    break;
                case 'location':
                    $step_name=esc_html__('Location', 'essential-real-estate');
                    break;
                case 'type':
                    $step_name=esc_html__('Type', 'essential-real-estate');
                    break;
                case 'price':
                    $step_name=esc_html__('Price', 'essential-real-estate');
                    break;
                case 'features':
                    $step_name=esc_html__('Features', 'essential-real-estate');
                    break;
                case 'details':
                    $step_name=esc_html__('Details', 'essential-real-estate');
                    break;
                case 'media':
                    $step_name=esc_html__('Media', 'essential-real-estate');
                    break;
                case 'floors':
                    $step_name=esc_html__('Floors', 'essential-real-estate');
                    break;
                case 'contact':
                    $step_name=esc_html__('Contact', 'essential-real-estate');
                    break;
                case 'private_note':
                    $step_name=esc_html__('Private Note', 'essential-real-estate');
                    break;
            }
            ?>
            <button class="ere-btn-arrow<?php if($i==1) echo ' active'; ?>" type="button" disabled><?php echo esc_html($step_name); ?></button>
        <?php endforeach;?>
    </div>
    <form action="<?php echo esc_url($action); ?>" method="post" id="submit_property_form" class="property-manager-form"
          enctype="multipart/form-data">
        <?php do_action('ere_before_submit_property');
        foreach ($layout as $value) {
            $index = array_search($value,$keys);
            $prev_key = $next_key= '';
            if($index>0)
            {
                $prev_key = $keys[$index-1];
            }
            if($index<$total-1){
                $next_key = $keys[$index+1];
            }
            ?>
            <fieldset tabindex="-1" id="step-<?php echo esc_attr($value); ?>">
                <?php
                ere_get_template('property/' . $form . '/'.$value.'.php');?>
                <div class="ere-step-nav">
                <?php
                if($prev_key!=''):?>
                    <button class="ere-btn-prev" aria-controls="step-<?php echo esc_attr($prev_key); ?>"
                        type="button" title="<?php esc_attr_e('Previous', 'essential-real-estate') ?>"><i class="fa fa-angle-left"></i><span><?php esc_html_e('Previous', 'essential-real-estate') ?></span></button>
                <?php endif; ?>
                    <button class="ere-btn-edit" type="button" title="<?php esc_attr_e('Show All Fields', 'essential-real-estate') ?>"><?php esc_html_e('Show All', 'essential-real-estate') ?></button>
                <?php if($next_key!=''):?>
                    <button class="ere-btn-next" aria-controls="step-<?php echo esc_attr($next_key); ?>"
                        type="button" title="<?php esc_attr_e('Next', 'essential-real-estate') ?>"><span><?php esc_html_e('Next', 'essential-real-estate') ?></span><i class="fa fa-angle-right"></i></button>
                <?php else:?>
                    <input type="submit" name="submit_property" class="button btn-submit-property"
                           value="<?php echo esc_attr($submit_button_text); ?>"/>
                <?php endif;?>
                </div>
            </fieldset>
            <?php
        }
        do_action('ere_after_submit_property'); ?>
        <?php wp_nonce_field('ere_submit_property_action', 'ere_submit_property_nonce_field'); ?>
        <input type="hidden" name="property_form" value="<?php echo esc_attr($form); ?>"/>
        <input type="hidden" name="property_action" value="<?php echo esc_attr($action) ?>"/>
        <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
        <input type="hidden" name="step" value="<?php echo esc_attr($step); ?>"/>
    </form>
</section>