jQuery(document).ready(function () {

    jQuery('.sac-category').on('change', function () {

        if (jQuery('.sac-post-types').val() == 'post') {
            sac_category = jQuery(this).val();
            jQuery('.sac-post-types').trigger('change');
        }
    });

    jQuery('.sac-post-types').on('change', function () {
        var sac_post_type = jQuery(this).val();

        if (sac_post_type) {
            var sac_post_type_data = {
                'action': 'sac_post_type_call',
                'post_type': sac_post_type
            };
            if (typeof sac_posts != 'undefined') {
                sac_post_type_data.post_id = sac_posts;
            }

            if (typeof sac_category != 'undefined') {
                sac_post_type_data.post_category = sac_category;
            }
            jQuery('.sac-posts option').remove();
            jQuery.post(sac_ajax_url, sac_post_type_data, function (response) {
                jQuery('.sac-posts').append(response);
            });
        }

        if (sac_post_type == 'post') {
            jQuery('.sac-category').css('display', 'block');
        } else {
            jQuery('.sac-category').css('display', 'none');
            jQuery('.sac-category').val('');
        }
    });

    var sac_post_type = jQuery('.sac-post-types').val();
    if (sac_post_type) {

        jQuery('.sac-post-types').trigger('change');
    }
});