<?php
global $post;
$uDraw = new uDraw();
$uDraw_SVG = new uDraw_SVG();
$templates = $uDraw_SVG->get_udraw_svg_templates();
if (metadata_exists('post', $post->ID, '_udraw_SVG_template_id')) {
    $template_id = get_post_meta($post->ID, '_udraw_SVG_template_id', true);
}
if (metadata_exists('post', $post->ID, '_udraw_SVG_product')) {
    $SVG_product = get_post_meta($post->ID, '_udraw_SVG_product', true);
} else {
    $SVG_product = false;
}
$selected_background_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_selected_background_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_selected_background_colour', true) : '';

$price_matrix_isset = (metadata_exists('post', $post->ID, '_udraw_SVG_price_matrix_set')) ? 
        get_post_meta($post->ID, '_udraw_SVG_price_matrix_set', true) : false;
$selected_price_matrix = (metadata_exists('post', $post->ID, '_udraw_SVG_price_matrix_access_key')) ? 
        get_post_meta($post->ID, '_udraw_SVG_price_matrix_access_key', true) : '';

$private_product_isset = (metadata_exists('post', $post->ID, '_udraw_SVG_private_product')) ? 
        get_post_meta($post->ID, '_udraw_SVG_private_product', true) : false;
$private_customers = (metadata_exists('post', $post->ID, '_udraw_SVG_private_users_list')) ? 
        get_post_meta($post->ID, '_udraw_SVG_private_users_list', true) : array();

$background_image_isset = (metadata_exists('post', $post->ID, '_udraw_SVG_use_background_image')) ? 
        get_post_meta($post->ID, '_udraw_SVG_use_background_image', true) : false;
$selected_background_image_id = (metadata_exists('post', $post->ID, '_udraw_SVG_selected_background_image')) ? 
        get_post_meta($post->ID, '_udraw_SVG_selected_background_image', true) : false;
$selected_background_image_url = ($background_image_isset) ? get_post($selected_background_image_id)->guid : '';

$selected_editing_tips_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_editing_tips_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_editing_tips_colour', true) : '#000';

$allow_custom_objects = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_custom_objects')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_custom_objects', true) : false;
$allow_background_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_background_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_background_colour', true) : false;
$allow_rotate_template = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_rotate_template')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_rotate_template', true) : false;

$allow_upload_artwork = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_upload_artwork')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork', true) : false;
$upload_artwork_pages = (metadata_exists('post', $post->ID, '_udraw_SVG_upload_artwork_pages')) ? 
        get_post_meta($post->ID, '_udraw_SVG_upload_artwork_pages', true) : json_encode(array());

$allow_download_template = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_download_template')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_download_template', true) : false;

$allow_upload_artwork_single_document = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_upload_artwork_single_document')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork_single_document', true) : false;

$udrawPriceMatrix = new uDrawPriceMatrix();
$price_matrix_list = $udrawPriceMatrix->get_price_matrix();

$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
        
if (isset($_GET['udraw_svg_template_id']) && isset($_GET['udraw_svg_action'])) {
    if ($_GET['udraw_svg_action'] == "new-product") {
        $template_id = $_GET['udraw_svg_template_id'];
        for ($i = 0; $i < count($templates); $i++) {
            if ($templates[$i]['ID'] === $template_id) {
                $selected_template = $templates[$i];
                $template_name = $templates[$i]['name'];
                $SVG_product = true;
                break;
            }
        }
    }
}
?>
<div id="udraw_SVG_product_data" class="panel woocommerce_options_panel hidden">
    <?php
    $template_options = array(
        '' => __('Select One', 'udraw_svg')
    );
    foreach($templates as $template) {
        $design_summary = $template['design_summary'];
        $_width = (isset($design_summary['width'])) ? $design_summary['width'] : 'N/A';
        $_height = (isset($design_summary['height'])) ? $design_summary['height'] : 'N/A';
        $template_options[$template['ID']] = __($template['name'] . ' - ' . $_width . ' x '. $_height, 'udraw_svg');
    }
    $templates_select_args = array(
        'label' => __('Select uDraw SVG Template', 'udraw_svg'), // Text in Label
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_template_id', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_template_id', // required
        'name' => 'udraw_SVG_template_id', //name will set from id if empty
        'options' => $template_options, // Options for select, array
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Link an existing template. Templates can be created from W2P:SVG->Add Template.', 'udraw_svg')
    );
    woocommerce_wp_select($templates_select_args);
    ?>
    
    <p class="form-field udraw_SVG_template_preview">
        <label for="udraw_SVG_template_preview"><?php _e('Template Preview', 'udraw_svg'); ?></label>
        <div id="udraw_SVG_template_preview"></div>
    </p>
    
    <?php
    $custom_objects_cb_args = array(
        'label' => __('Allow Additional Objects', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_custom_objects', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_custom_objects', // required
        'name' => 'udraw_SVG_allow_custom_objects', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow the customer to add additional objects onto the design.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($custom_objects_cb_args);
    
    $allow_bg_cb_args = array(
        'label' => __('Allow Background Colour', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_background_colour', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_background_colour', // required
        'name' => 'udraw_SVG_allow_background_colour', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow the customer to change the background colour of the design.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($allow_bg_cb_args);
    
    $allow_rotate_template_args = array(
        'label' => __('Allow Template Rotation', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_rotate_template', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_rotate_template', // required
        'name' => 'udraw_SVG_allow_rotate_template', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow the customer to rotate the template of the design.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($allow_rotate_template_args);
    ?>

    <!--Background Select-->
    <fieldset class="form-field udraw_SVG_selected_background_colour_field ">
        <legend><?php _e('Select Background Colour', 'udraw_svg'); ?></legend>
        <span class="woocommerce-help-tip" data-tip="<?php _e('Selected colour will be used in backing of product designer.', 'udraw_svg'); ?>"></span>
        <input type="hidden" name="udraw_SVG_selected_background_colour" id="udraw_SVG_selected_background_colour"/>
    </fieldset>
    
    <?php
    $background_image_cb_args = array(
        'label' => __('Use Background Image', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_use_background_image', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_use_background_image', // required
        'name' => 'udraw_SVG_use_background_image', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Use a selected background image instead of colour.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($background_image_cb_args);
    ?>
    
    <fieldset class="form-field udraw_SVG_selected_background_image_field ">
        <legend><?php _e('Select Background Image', 'udraw_svg'); ?></legend>
        <div class="image-preview-wrapper">
                <img id="selected_background_image_preview" src="<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>" height='100'>
        </div>
        <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ,'udraw_svg' ); ?>" />
        <input type="hidden" name="udraw_SVG_selected_background_image" id="udraw_SVG_selected_background_image" value="<?php echo get_option( 'media_selector_attachment_id' ); ?>">
    </fieldset>
    
    <fieldset class="form-field udraw_SVG_editing_tips_colour_field ">
        <legend><?php _e('Select Editing Tips Colour', 'udraw_svg'); ?></legend>
        <span class="woocommerce-help-tip" data-tip="<?php _e('Selected colour will be used in displaying editing tips (Default: #000).', 'udraw_svg'); ?>"></span>
        <input type="hidden" name="udraw_SVG_editing_tips_colour" id="udraw_SVG_editing_tips_colour"/>
    </fieldset>
    
<?php    
    if ($uDraw->is_udraw_okay()) {
        $price_matrix_cb_args = array(
            'label' => __('Define Price Matrix', 'udraw_svg'),
            'class' => '',
            'style' => '',
            'wrapper_class' => '',
            'value' => '_udraw_SVG_price_matrix_set', // if empty, retrieved from post meta where id is the meta_key
            'id' => 'udraw_SVG_price_matrix_set', // required
            'name' => 'udraw_SVG_price_matrix_set', //name will set from id if empty
            'cbvalue' => true,
            'desc_tip' => '',
            'custom_attributes' => '', // array of attributes 
            'description' => __('Override default Price with Price Matrix', 'udraw_svg')
        );

        $price_matrix_options = array(
            '' => __('Select One', 'udraw_svg')
        );
        foreach ($price_matrix_list as $price_matrix_item) {
            $price_matrix_options[$price_matrix_item->access_key] = __($price_matrix_item->name, 'udraw_svg');
        }
        $price_matrix_select_args = array(
            'label' => __('Price Matrix', 'udraw_svg'), // Text in Label
            'class' => '',
            'style' => '',
            'wrapper_class' => '',
            'value' => '_udraw_SVG_price_matrix_access_key', // if empty, retrieved from post meta where id is the meta_key
            'id' => 'udraw_SVG_price_matrix_access_key', // required
            'name' => 'udraw_SVG_price_matrix_access_key', //name will set from id if empty
            'options' => $price_matrix_options, // Options for select, array
            'desc_tip' => '',
            'custom_attributes' => '', // array of attributes 
            'description' => __('This price matrix will override the default price and price matrix.', 'udraw_svg')
        );
        woocommerce_wp_checkbox($price_matrix_cb_args);
        woocommerce_wp_select($price_matrix_select_args);
    }

    //Private Template
    $private_product_cb_args = array(
        'label' => __('Make Product Private', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_private_product', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_private_product', // required
        'name' => 'udraw_SVG_private_product', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Make this product visible for specific set of users only.', 'udraw_svg')
    );
    
    $private_customers_options = array( '__loading_list__' => __('Loading List... Please wait.', 'udraw_svg'));    
    $private_product_select_args = array(
        'label' => __('Visible to Selected Customers', 'udraw_svg'), // Text in Label
        'class' => 'disabled',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_private_users_list', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_private_users_list', // required
        'name' => 'udraw_SVG_private_users_list[]', //name will set from id if empty
        'options' => $private_customers_options, // Options for select, array
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('These users will have private access to this product.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($private_product_cb_args);
    woocommerce_wp_select($private_product_select_args);
    
    //Upload Artwork
    $allow_upload_artwork_args = array(
        'label' => __('Allow Upload Multiple Custom Artwork with Page Labels', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_upload_artwork', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_upload_artwork', // required
        'name' => 'udraw_SVG_allow_upload_artwork', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow customers to upload multiple custom artwork with page labels instead of using the designer.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($allow_upload_artwork_args);
    
    //$allow_download_template
    /*$allow_download_template_args = array(
        'label' => __('Allow Download Template', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_download_template', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_download_template', // required
        'name' => 'udraw_SVG_allow_download_template', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow customers to download the template for offline design, and then upload it.', 'udraw_svg')
    );
    
    woocommerce_wp_checkbox($allow_download_template_args);*/
    ?>
    
    <div class="form-field udraw_SVG_file_upload_input_pages hidden">
        <label for="udraw_SVG_file_upload_input_pages"><?php _e('List of Pages', 'udraw_svg'); ?></label>
        <label class="upload_input_pages_list_hideshow">
            <span><?php _e('Hide list', 'udraw_svg') ?></span>
            <i class="fa fa-chevron-up"></i>
        </label>
        <ul id="udraw_SVG_file_upload_input_pages_list"></ul>
        <button type="button" class="add_input_page_btn">
            <i class="fa fa-plus"></i>
            <span><?php _e('Add a Page', 'udraw_svg'); ?></span>
        </button>
        <input type="hidden" id="udraw_SVG_upload_pages_list_input" name="udraw_SVG_upload_pages_list_input" />
    </div>
    
    <?php
    
    //Upload Artwork
    $allow_upload_artwork_single_document_args = array(
        'label' => __('Allow Upload Artwork (Single Document)', 'udraw_svg'),
        'class' => '',
        'style' => '',
        'wrapper_class' => '',
        'value' => '_udraw_SVG_allow_upload_artwork_single_document', // if empty, retrieved from post meta where id is the meta_key
        'id' => 'udraw_SVG_allow_upload_artwork_single_document', // required
        'name' => 'udraw_SVG_allow_upload_artwork_single_document', //name will set from id if empty
        'cbvalue' => true,
        'desc_tip' => '',
        'custom_attributes' => '', // array of attributes 
        'description' => __('Allow customers to upload custom artwork (single document, can have multiple pages) instead of using the designer.', 'udraw_svg')
    );
    woocommerce_wp_checkbox($allow_upload_artwork_single_document_args);
    
    do_action('udraw_svg_admin_product_panel');
    ?>
</div>

<script>
    jQuery(document).ready(function($){
        //Media Library
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
        jQuery('#upload_image_button').on('click', function( event ){
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('Select an image', 'udraw_svg'); ?>',
                button: {
                    text: '<?php _e('Use this image', 'udraw_svg'); ?>',
                },
                multiple: false	// Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                $( '#selected_background_image_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                $( '#udraw_SVG_selected_background_image' ).val( attachment.id );
                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });
            // Finally, open the modal
            file_frame.open();
        });
        // Restore the main ID when the add media button is pressed
        jQuery( 'a.add_media' ).on( 'click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });
                        
        //Init colourpickers
        $('#udraw_SVG_selected_background_colour, #udraw_SVG_editing_tips_colour').wpColorPicker();
        //Disable all select option[value='']
        $('#udraw_SVG_product_data select option[value=""]').prop('disabled', true);
        $('#udraw_SVG_product_data select').attr({
            'data-placeholder': '<?php echo __('Select one', 'udraw_svg'); ?>',
            'placeholder': '<?php echo __('Select one', 'udraw_svg'); ?>'
        });
        var templates = JSON.parse('<?php echo json_encode($templates) ?>');
        $('#_udraw_SVG_product').on('change', function(){
            if ($(this).prop('checked')) {
                $('li.udraw_SVG_options').show();
            } else {
                $('li.udraw_SVG_options').hide();
            }
        });
        $('#udraw_SVG_price_matrix_set').on('change', function(){
            if ($(this).prop('checked')) {
                $('p.udraw_SVG_price_matrix_access_key_field').show();
            } else {
                $('p.udraw_SVG_price_matrix_access_key_field').hide();
            }
        });
        
        $('#udraw_SVG_private_product').on('change', function(){
            if ($(this).prop('checked')) {
                $('p.udraw_SVG_private_users_list_field').show();
            } else {
                $('p.udraw_SVG_private_users_list_field').hide();
            }
        });
        $('#udraw_SVG_template_id').on('change', function(){
            $('#udraw_SVG_template_preview').empty();
            var id = $(this).val();
            for (var i = 0; i < templates.length; i++) {
                var template = templates[i];
                if (template.ID === id) {
                    var img = $('<img />').attr('src', template.preview).addClass('image_preview');
                    $('#udraw_SVG_template_preview').append(img);
                    return;
                }
            }
        });
        $('#udraw_SVG_use_background_image').on('change',function(){
            if ($(this).prop('checked')) {
                $('fieldset.udraw_SVG_selected_background_image_field').show();
            } else {
                $('fieldset.udraw_SVG_selected_background_image_field').hide();
            }
        });
        $('#udraw_SVG_allow_upload_artwork').on('change', function(){
            if ($(this).prop('checked')) {
                $('div.udraw_SVG_file_upload_input_pages').removeClass('hidden');
                $('p.udraw_SVG_allow_upload_artwork_single_document_field').addClass('hidden');
            } else {
                $('div.udraw_SVG_file_upload_input_pages').addClass('hidden');
                $('p.udraw_SVG_allow_upload_artwork_single_document_field').removeClass('hidden');
            }
        });
        $('button.add_input_page_btn').on('click', function(){
            var index = 0;
            if ($('#udraw_SVG_file_upload_input_pages_list li').length > 0) {
                index = parseInt($('#udraw_SVG_file_upload_input_pages_list li:last-child').attr('data-page_index')) + 1;
            }
            var label = 'Page ' + (index + 1);
            add_upload_page(index, label);
        });
        
        $('label.upload_input_pages_list_hideshow').on('click', function(){
            if ($('ul#udraw_SVG_file_upload_input_pages_list').hasClass('closed')) {
                $('ul#udraw_SVG_file_upload_input_pages_list').css('height', '');
                $('ul#udraw_SVG_file_upload_input_pages_list').removeClass('closed');
                $('label.upload_input_pages_list_hideshow span').text('<?php _e('Hide List', 'udraw_svg') ?>');
                $('label.upload_input_pages_list_hideshow i').removeClass('fa-chevron-down');
                $('label.upload_input_pages_list_hideshow i ').addClass('fa-chevron-up');
            } else {
                $('ul#udraw_SVG_file_upload_input_pages_list').addClass('closed');
                $('label.upload_input_pages_list_hideshow span').text('<?php _e('Show List', 'udraw_svg') ?>');
                $('label.upload_input_pages_list_hideshow i').removeClass('fa-chevron-up');
                $('label.upload_input_pages_list_hideshow i ').addClass('fa-chevron-down');
                setTimeout(function(){
                    $('ul#udraw_SVG_file_upload_input_pages_list').height(0);
                }, 250);
            }
        });
        
        $('#udraw_SVG_allow_upload_artwork_single_document').on('change', function(){
            if ($(this).prop('checked')) {
                $('#udraw_SVG_allow_upload_artwork').prop('checked', false).trigger('change');
                $('p.udraw_SVG_allow_upload_artwork_field').addClass('hidden');
            } else{
                $('p.udraw_SVG_allow_upload_artwork_field').removeClass('hidden');
            }
        });
        
        var saved_pages = JSON.parse('<?php echo $upload_artwork_pages ?>');
        for (var i = 0; i < saved_pages.length; i++) {
            var page = saved_pages[i];
            add_upload_page(page.index, page.label);
        }
        
        //Trigger changes
        <?php if (isset($template_name)) { ?>
            $('#title').val('<?php echo $template_name ?>');
        <?php } ?>
        <?php if (isset($template_id)) { ?>
            $('#udraw_SVG_template_id').val('<?php echo $template_id ?>').trigger('change');
        <?php } ?>
        $('#udraw_SVG_template_id').select2();
        $('#_udraw_SVG_product').prop('checked', Boolean(<?php echo $SVG_product ?>)).trigger('change');
        
        $('#udraw_SVG_price_matrix_set').prop('checked', Boolean(<?php echo $price_matrix_isset ?>)).trigger('change');
        $('#udraw_SVG_price_matrix_access_key').val('<?php echo $selected_price_matrix ?>').trigger('change');
        $('#udraw_SVG_price_matrix_access_key').select2();
       
        $('#udraw_SVG_private_product').prop('checked', Boolean(<?php echo $private_product_isset ?>)).trigger('change');
        
        get_customers(1, 500, function(){
            $('#udraw_SVG_private_users_list option[value="__loading_list__"]').remove();
            var selected_customers = JSON.parse('<?php echo json_encode($private_customers); ?>');
            $('#udraw_SVG_private_users_list').attr({
                'multiple': 'multiple'
            }).val(selected_customers).removeClass('disabled');
            $('#udraw_SVG_private_users_list').select2();
        });
        $('#udraw_SVG_allow_background_colour').prop('checked', Boolean(<?php echo $allow_background_colour ?>)).trigger('change');
        $('#udraw_SVG_allow_rotate_template').prop('checked', Boolean(<?php echo $allow_rotate_template ?>)).trigger('change');
        $('#udraw_SVG_selected_background_colour').val('<?php echo $selected_background_colour ?>').trigger('change');
        $('#udraw_SVG_use_background_image').prop('checked', Boolean(<?php echo $background_image_isset ?>)).trigger('change');
        $('#udraw_SVG_selected_background_image').val('<?php echo $selected_background_image_id ?>');
        $('#selected_background_image_preview').attr('src', '<?php echo $selected_background_image_url ?>');
        
        $('#udraw_SVG_editing_tips_colour').val('<?php echo $selected_editing_tips_colour ?>').trigger('change');
        $('#udraw_SVG_allow_upload_artwork').prop('checked', Boolean('<?php echo $allow_upload_artwork ?>')).trigger('change');
        $('#udraw_SVG_upload_pages_list_input').val('<?php echo $upload_artwork_pages ?>');
        $('#udraw_SVG_allow_upload_artwork_single_document').prop('checked', Boolean('<?php echo $allow_upload_artwork_single_document ?>')).trigger('change');
        $('#udraw_SVG_allow_download_template').prop('checked', Boolean('<?php echo $allow_download_template ?>'));
;        
        $('#udraw_SVG_allow_custom_objects').prop('checked', Boolean('<?php echo $allow_custom_objects ?>')).trigger('change');
        
        //Init sortable
        $('#udraw_SVG_file_upload_input_pages_list').sortable({
            handle: '.sortable_handle',
            stop: function (event, ui) {
                update_pages_list();
            }
        });
        
        function add_upload_page (index, page_label) {
            if ($('ul#udraw_SVG_file_upload_input_pages_list').hasClass('closed')) {
                $('label.upload_input_pages_list_hideshow').trigger('click');
            }
            var handle = $('<i></i>').addClass('fas fa-arrows-alt-v sortable_handle');
            var label_input = $('<input />').attr({
                type: 'text',
                class: 'page_label'
            }).val(page_label).on('input propertychange', function(){
                update_pages_list();
            });
            var delete_btn = $('<button></button>').attr({
                type: 'button',
                class: 'delete_page_btn'
            }).text('<?php _e('Delete Page', 'udraw_svg'); ?>').on('click', function(){
                $(this).parent().remove();
                update_pages_list();
            });
            var _li = $('<li></li>').attr({
                'data-page_index': index
            }).append(handle, label_input, delete_btn);
            $('#udraw_SVG_file_upload_input_pages_list').append(_li);
        }
        
        function update_pages_list () {
            var pages_list = new Array();
            var count = 0;
            $('#udraw_SVG_file_upload_input_pages_list li').each(function(){
                //Update the index
                $(this).attr('data-page_index', count);
                var label = $('input', this).val();
                pages_list.push({
                    index: count,
                    label: label
                });
                count++;
            });
            $('#udraw_SVG_upload_pages_list_input').val(JSON.stringify(pages_list));
        }
        
        function get_customers (page_index, per_page, callback) {
            $.ajax({
                method: 'POST',
                dataType: "json",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    action: 'udraw_get_customers',
                    page: page_index,
                    per_page: per_page
                },
                success: function (response) {
                    if (typeof response === 'object' && response.customers.length > 0) {
                        var _select = $('select#udraw_SVG_private_users_list');
                        for (var i = 0; i < response.customers.length; i++) {
                            var customer = response.customers[i];
                            var option = $('<option></option>').attr({
                                value: customer.ID
                            }).text(customer.display_name + ' - ' + customer.email);
                            _select.append(option);
                        }
                        if (response.get_next_page) {
                            get_customers (page_index + 1, per_page, callback);
                        } else {
                            if (typeof callback == 'function') {
                                callback();
                            }
                        }
                    } else {
                        if (typeof callback == 'function') {
                            callback();
                        }
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
    });
</script>
<style>
    select#udraw_SVG_template_id {
        width: 50%;
    }
    img.image_preview {
        max-width: 50%;
        max-height: 50%;
        margin-left: 15px;
        border: 1px solid #ccc;
    }
    .select2-container .select2-search__field {
        min-width: initial;
    }
    .wp-picker-container {
        display: inline-block;
    }
    div.udraw_SVG_file_upload_input_pages {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
    }
    div.udraw_SVG_file_upload_input_pages label {
        margin: 0;
        font-size: 12px;
        line-height: 24px;
        float: none;
    }
    div.udraw_SVG_file_upload_input_pages input.page_label {
        float: none;
    }
    div.udraw_SVG_file_upload_input_pages i.sortable_handle {
        cursor: move;
        border: 1px solid #ccc;
        border-right: 0;
        border-radius: 5px 0px 0px 5px;
        padding: 6.5px 10px 5px 10px;
    }
    button.add_input_page_btn {
        margin: 0;
        font-size: 12px;
        line-height: 24px;
    }
    div.udraw_SVG_file_upload_input_pages label.upload_input_pages_list_hideshow {
        float: right;
        cursor: pointer;
        color: #0073aa;
    }
    ul#udraw_SVG_file_upload_input_pages_list {
        transform: scaleY(1);
        transform-origin: top;
        transition: transform 0.26s ease;
    }
    ul#udraw_SVG_file_upload_input_pages_list.closed {
        transform: scaleY(0);
    }
    div#udraw_SVG_product_data .select2-container {
        min-width: 250px;
    }
</style>