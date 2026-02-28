(function ($) {
    'use strict';
    $(document).ready(function () {
        if (typeof ere_login_vars !== "undefined") {
            var ajax_url = ere_login_vars.ajax_url;
            var loading = ere_login_vars.loading;
            $('.ere-login').validate({
                errorElement: "span", // wrap error elements in span not label
                rules: {
                    user_login: {
                        required: true
                    },
                    user_password: {
                        required: true
                    }
                },
                messages: {
                    user_login: "",
                    user_password: ""
                }
            });
            $('.ere-login-button').on('click', function (e) {
                e.preventDefault();
                var $form = $(this).parents('form');
                var $redirect_url=$(this).data('redirect-url');
                var $messages = $(this).parents('.ere-login-wrap').find('.ere_messages');
                if ($form.valid()) {
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: $form.serialize(),
                        beforeSend: function () {
                            $messages.empty().append('<span class="success text-success"> ' + loading + '</span>');
                        },
                        success: function (response) {
                            if (response.success) {
                                $messages.empty().append('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');
                                if ($redirect_url == '') {
                                    window.location.reload();
                                }
                                else {
                                    window.location.href = $redirect_url;
                                }
                            } else {
                                if (typeof ere_reset_recaptcha == 'function') {
                                    ere_reset_recaptcha();
                                }
                                $messages.empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                            }
                        }
                    })
                }
            });

            $('.ere_forgetpass').on('click',function (e) {
                e.preventDefault();
                var $form = $(this).parents('form');
                $.ajax({
                    type: 'post',
                    url: ajax_url,
                    dataType: 'json',
                    data: $form.serialize(),
                    beforeSend: function () {
                        $('.ere_messages_reset_password').empty().append('<span class="success text-success"> ' + loading + '</span>');
                    },
                    success: function (response) {
                        if (response.success) {
                            $('.ere_messages_reset_password').empty().append('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');
                        } else {
                            if (typeof ere_reset_recaptcha == 'function') {
                                ere_reset_recaptcha();
                            }
                            $('.ere_messages_reset_password').empty().append('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                        }
                    }
                });
            });
            $('.ere-reset-password').off('click').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    $login_wrap = $this.closest('.ere-login-wrap').slideUp('slow'),
                    $reset_password_wrap = $login_wrap.next('.ere-reset-password-wrap');
                    $reset_password_wrap.slideDown('slow');
                    $reset_password_wrap.find('.reset_password_user_login').focus();
            });
            $('.ere-back-to-login').off('click').on('click', function (event) {
                event.preventDefault();
                var $this = $(this),
                    $reset_password_wrap = $this.closest('.ere-reset-password-wrap').slideUp('slow'),
                    $login_wrap = $reset_password_wrap.prev('.ere-login-wrap');
                    $login_wrap.slideDown('slow');
                    $login_wrap.find('.login_user_login').focus();
            });
            $('#ere_signin_modal').on('shown.bs.modal', function () {
                $('.ere-back-to-login', $('#ere_signin_modal')).click();
            });
            $('#ere_signin_modal').on('hide.bs.modal', function () {
                $('.ere-back-to-login', $('#ere_signin_modal')).click();
            })
        }
    });
})(jQuery);