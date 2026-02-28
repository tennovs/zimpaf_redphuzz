jQuery(document).ready(function($) {
    
	$(document).on('click', '.file-edit-btn', function(event) {
		console.log('hi');
		$(this).closest('.ffmwp-admin-wrapper').find('.title_dec_adit_wrapper').toggle();
	});

	$(document).on('click', '.file-title-dec-cancel-adit-btn', function(event) {
		$(this).closest('.title_dec_adit_wrapper').hide();
	});

    // $('.wpfm_files_grid .wpfm_file_box').each(function(index, key) {
    //     var icon_id = $(this).find('.view-icon').attr('id');
    //     var modal_id = '#' + icon_id;
    //     var modal_target = $(this).find('.view-icon').data('target');


    //     // alert('load cs');

    //     $(modal_id).animatedModal({
    //         modalTarget: modal_target,
    //         animatedIn: 'lightSpeedIn',
    //         animatedOut: 'bounceOutDown',
    //         color: '#fff',
    //         opacityIn: '1'
    //     });

    // });


    // $('.wpfm_files_list_table .wpfm_file_box').each(function(index, key) {
    //     var wrapper_1 = $(key).find('a.view-icon');
    //     var icon_id = $(this).find('a.view-icon').attr('id');
    //     var modal_id = '#' + icon_id;
    //     var modal_target = $(this).find('a.view-icon').data('target');

    //     // alert('load cs');
    //     console.log(modal_target);

    //     $(modal_id).animatedModal({
    //         modalTarget: modal_target,
    //         animatedIn: 'lightSpeedIn',
    //         animatedOut: 'bounceOutDown',
    //         color: '#fff',
    //         opacityIn: '1'
    //     });

    // });




    $('table .column-detail').each(function(index, key) {
        var icon_id = $(this).find('.view-icon').attr('id');
        var modal_id = '#' + icon_id;
        var modal_target = $(this).find('.view-icon').data('target');

        $(modal_id).animatedModal({
            modalTarget: modal_target,
            animatedIn: 'lightSpeedIn',
            animatedOut: 'bounceOutDown',
            color: '#fff',
        });
    });

    $('.wpfm-modal-content .col-md-2').each(function(index, key) {
        var icon_id = $(this).find('.view-icon').data('modal_id');
        var modal_id = '.' + icon_id;
        var modal_target = $(this).find('.view-icon').data('target');

        $(modal_id).animatedModal({
            modalTarget: modal_target,
            animatedIn: 'lightSpeedIn',
            animatedOut: 'bounceOutDown',
            color: '#fff',
        });

        // var pdf_id = $(this).find('.pdf-icon').data('modal_id');
        // var pdf_modal_id = '.' + pdf_id;
        // var pdf_modal_target = $(this).find('.pdf-icon').data('target');
        // $(pdf_modal_id).animatedModal({
        //     modalTarget: pdf_modal_target,
        //     animatedIn: 'lightSpeedIn',
        //     animatedOut: 'bounceOutDown',
        //     color: '#fff',
        // });

    });
});
