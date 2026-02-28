var ERE_POPUP = ERE_POPUP || {};
(function ($) {
    ERE_POPUP = {
        init: function () {
            //The chosen one
            $("select#ere-shortcodes").chosen();

            this.insert_shortcode();
            this.select_shortcode();
        },
        required_element: function() {
            var shortcode_option = $('#options-'+$('#ere-shortcodes').val());
            $('[data-required-element]', shortcode_option).each(function () {
                var $this = $(this),
                    required_element = $this.attr('data-required-element'),
                    element_required = $('[name="'+required_element+'"]', shortcode_option);
                var check_show_hide_elm = function (required_element, elm, elm_type) {
                    $('[data-required-element="'+required_element+'"]', shortcode_option).each(function () {
                        var option_wrap = $(this).closest('.option-item-wrap'),
                            required_value = $(this).attr('data-required-value');
                        if('checkbox' == elm_type) {
                            if(((elm.is(':checked') && required_value==='true') || (!elm.is(':checked') && required_value==='false')) && (elm.closest('.option-item-wrap').css('display')!='none')) {
                                option_wrap.show();
                            } else {
                                option_wrap.hide();
                            }
                        } else {
                            if(required_value.indexOf(elm.val()) !== -1  && (elm.closest('.option-item-wrap').css('display')!='none')) {
                                option_wrap.show();
                            } else {
                                option_wrap.hide();
                            }
                        }
                    });
                    setTimeout(check_show_hide_wrap, 100);
                };

                var check_show_hide_wrap = function () {
                    $('.two-option-wrap', shortcode_option).each(function () {
                        var check = false;
                        $('.option-item-wrap', $(this)).each(function () {
                            if($(this).css('display') != 'none') {
                                check = true;
                            }
                        });
                        if (!check) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                };

                setTimeout(function(){
                    element_required.each( function () {
                        var elm = $(this),
                            elm_type = elm.is('input:checkbox') ? 'checkbox' : 'select-one';
                        check_show_hide_elm(required_element, elm, elm_type);
                    });
                },100);

                element_required.off('change').on('change', function (e) {
                    e.preventDefault();
                    var elm = $(this);
                    check_show_hide_elm(required_element, elm, e.target.type);
                })
            });
        },
        update_shortcode: function(){
            var name = $('#ere-shortcodes').val(),
                code = '['+name;

            //checkbox
            $('#options-'+name+' input[type=checkbox]').each(function(){
                if( $(this).closest('.option-item-wrap').css('display') != 'none') {
                    if($(this).is(':checked'))
                    {
                        code += ' ' + $(this).attr('name')+'="true"';
                    }
                    else
                    {
                        code += ' ' + $(this).attr('name')+'="false"';
                    }
                }
            });

            //select
            $('#options-'+name+' select:not("[multiple=multiple]")').each(function(){
                if( $(this).closest('.option-item-wrap').css('display') != 'none') {
                    code += ' ' + $(this).attr('id') + '="' + $(this).val() + '"';
                }
            });

            //multi select
            $('#options-'+name+' select[multiple=multiple]').each(function(){
                if( $(this).closest('.option-item-wrap').css('display') != 'none') {
                    var $categories = ($(this).val() != null && $(this).val().length > 0) ? $(this).val() : '';
                    code += ' ' + $(this).attr('id') + '="' + $categories + '"';
                }
            });

            //image
            $('#options-'+name+' [data-name=image-upload] input#options-item-id').each(function(){
                if( $(this).closest('.option-item-wrap').css('display') != 'none') {
                    code += ' ' + $(this).attr('name') + '="' + $(this).attr('value') + '"';
                }
            });

            //input
            $('#options-'+name+' input[type=text]').each(function(){
                if( $(this).closest('.option-item-wrap').css('display') != 'none' && typeof($(this).attr('name')) != 'undefined') {
                    code += ' ' + $(this).attr('name') + '="' + $(this).val() + '"';
                }
            });
            code += ']';

            //insert shortcode
            window.wp.media.editor.insert( code );

            $.magnificPopup.close();
        },
        insert_shortcode: function () {
            $('#insert-shortcode').on('click', function(){
                ERE_POPUP.update_shortcode();
                return false;
            });
        },
        select_shortcode: function () {
            $('#ere-shortcodes').on('change', function(){
                ERE_POPUP.required_element();

                var shortcode = $(this).val(),
                    shortcode_content = $('#options-'+shortcode),
                    dataType = shortcode_content.attr('data-type');
                $('.shortcode-options').hide();
                shortcode_content.show();
                ERE_POPUP.reset_fileds();
            });
        },
        reset_fileds: function(){
            //reset data
            var wrap = $('#ere-input-shortcode'),
                shortcode = wrap.find('#ere-shortcodes').val(),
                current_shortcode = $('#options-'+shortcode);
            //current_shortcode.find('');
            current_shortcode.find('.ere-selectize-input').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: function(input) {
                    return {
                        value: input,
                        text: input
                    }
                }
            });
            current_shortcode.find('input:text, input:password, input:file, textarea').each(function () {
                var $this = $(this),
                    default_value = $this.attr('data-default-value');
                if(typeof(default_value) != 'undefined' ) {
                    $this.attr('value', default_value);
                    $this.val(default_value);
                } else {
                    $this.attr('value', '');
                    $this.val('');
                }
            });
            current_shortcode.find('[multiple="multiple"]').val('');

            current_shortcode.find('select:not("#ere-shortcodes, [multiple=multiple]")').each(function () {
                var $this = $(this),
                    default_value = $this.attr('data-default-value');
                $this.prop('selectedIndex',0);
                if(typeof(default_value) != 'undefined' ) {
                    $this.find('option[value="'+default_value+'"]').attr("selected", "selected");
                    $this.val(default_value);
                } else {
                    $this.find('option:first-child').attr("selected", "selected");
                }
            });
            current_shortcode.find('input:checkbox').each(function () {
                var $this = $(this),
                    default_value = $this.attr('data-default-value');
                if(typeof(default_value) != 'undefined' && default_value == 'true' ) {
                    $this.attr('checked', "checked");
                } else {
                    $this.removeAttr('checked')
                }
            });
            current_shortcode.find('.shortcode-options').each(function(){
                $(this).find('.shortcode-dynamic-item').addClass('marked-for-removal');
                $(this).find('.shortcode-dynamic-item:first').removeClass('marked-for-removal');
                $(this).find('.shortcode-dynamic-item.marked-for-removal').remove();
            });

            current_shortcode.find('#options-item-id').each(function () {
                $(this).val('');
                $(this).attr('value','');
            });
            current_shortcode.find('.ere-image-screenshot').attr('src','');
            current_shortcode.find('.ere-image-upload-remove').hide();
            current_shortcode.find('.ere-image-upload').show();
            current_shortcode.find('.ere-selectize-input').each(function(){
                if ($(this)[0].selectize) {
                    $(this)[0].selectize.destroy();
                    $(this).val('');
                }
                $(this).selectize({
                    plugins: ['remove_button'],
                    delimiter: ',',
                    persist: false,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
            });
        }
    };
    $(document).ready(function() {
        ERE_POPUP.init();
    });
})(jQuery);