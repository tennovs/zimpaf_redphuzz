<?php
global $post;
$allowStructureFile = (metadata_exists('post', $post->ID, '_udraw_allow_structure_file')) ? get_post_meta($post->ID, '_udraw_allow_structure_file', true) : false;

$allow_excel_upload_args = array(
    'label' => __('Allow Structure File Download/Upload', 'udraw_svg'),
    'class' => '',
    'style' => '',
    'wrapper_class' => '',
    'value' => '_udraw_allow_structure_file', // if empty, retrieved from post meta where id is the meta_key
    'id' => 'udraw_allow_structure_file', // required
    'name' => 'udraw_allow_structure_file', //name will set from id if empty
    'cbvalue' => 'yes',
    'desc_tip' => false, //true or '' if for hover tip, false for description span
    'custom_attributes' => '', // array of attributes 
    'description' => __('This will allow the customer to upload an excel file to automatically fill in labelled objects (If there are labelled objects in the template). '
            . '         This option will also force Display Options First.', 'udraw')
);
woocommerce_wp_checkbox($allow_excel_upload_args);
?>
<script>
    jQuery('#udraw_allow_structure_file').on('change', function(){
        var isChecked = jQuery(this).prop('checked');
        if (isChecked) {
            jQuery('#udraw_display_options_page_first').prop('checked', true).trigger('change');
            jQuery('#udraw_display_options_page_first').attr('disabled', true);
        } else {
            jQuery('#udraw_display_options_page_first').attr('disabled', false);
        }
    });

    jQuery(document).ready(function($){
        var checked = ('<?php echo $allowStructureFile ?>' === 'yes') ? true : false;
        $('#udraw_allow_structure_file').prop('checked', checked).trigger('change');
    });
</script>