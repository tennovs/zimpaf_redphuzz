var ERE_Property_Search = ERE_Property_Search || {};
(function ($) {
    'use strict';
    var ajax_url = '';// ere_search_vars.ajax_url;
    var price_is_slider = '';// ere_search_vars.price_is_slider;
    var css_class_wrap = '.ere-search-properties';
    ERE_Property_Search = {
        init: function () {
            if ($(css_class_wrap).length === 0) {
                return;
            }

            if ($(css_class_wrap).hasClass('ere-search-properties-map')) {
                return;
            }

            if (typeof ($(css_class_wrap).data('options')) !== "undefined") {
                window['ere_search_vars'] = $(css_class_wrap).data('options');
            }

            if (typeof (ere_search_vars) === "undefined") {
                return;
            }


            ajax_url = ere_search_vars.ajax_url;
            price_is_slider = ere_search_vars.price_is_slider;


            var enable_filter_location=ere_search_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.ere-property-country-ajax', css_class_wrap).select2();
                $('.ere-property-state-ajax', css_class_wrap).select2();
                $('.ere-property-city-ajax', css_class_wrap).select2();
                $('.ere-property-neighborhood-ajax', css_class_wrap).select2();
            }

            this.get_states_by_country();
            $(".ere-property-country-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Search.get_states_by_country();
            });
            this.get_cities_by_state();
            $(".ere-property-state-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Search.get_cities_by_state();
            });
            this.get_neighborhoods_by_city();
            $(".ere-property-city-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Search.get_neighborhoods_by_city();
            });
            $('.btn-status-filter', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var status = $(this).data("value");
                $(this).parent().find('input').val(status);
                $(this).parent().find('button').removeClass('active');
                $(this).addClass('active');
                ERE_Property_Search.change_price_on_status_change(status);
            });
            $('select[name="status"]', css_class_wrap).on('change', function (e) {
                e.preventDefault();
                var status = $(this).val();
                ERE_Property_Search.change_price_on_status_change(status);
            });
            this.execute_url_search();
            $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).on('register.again', function () {
                $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).each(function () {
                    var slider_filter = $(this);
                    ERE_Property_Search.set_slider_filter(slider_filter);
                });
            });
            this.register_slider_filter();
            this.set_slider_value();
            $('.other-features-wrap .btn-other-features', css_class_wrap).on('click', function (event) {
                event.preventDefault();
                $('.other-features-list', css_class_wrap).slideToggle();
                $(this).toggleClass('show');
                if ($(this).hasClass('show') == true) {
                    $('input[name="features-search"]', css_class_wrap).attr('value', '1');
                    $(this).find('i').removeClass('fa-chevron-down');
                    $(this).find('i').addClass('fa-chevron-up');
                }
                else {
                    $('input[name="features-search"]', css_class_wrap).attr('value', '0');
                    $(this).find('i').removeClass('fa-chevron-up');
                    $(this).find('i').addClass('fa-chevron-down');
                }
            });
        },
        get_states_by_country: function () {
            var $this = $(".ere-property-country-ajax", css_class_wrap);
            if ($this.length) {
                var selected_country = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_states_by_country_ajax',
                        'country': selected_country,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".ere-property-state-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-state-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-state-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_cities_by_state: function () {
            var $this = $(".ere-property-state-ajax", css_class_wrap);
            if ($this.length) {
                var selected_state = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_cities_by_state_ajax',
                        'state': selected_state,
                        'type': 1
                    },
                    success: function (response) {
                        $(".ere-property-city-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-city-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-city-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_neighborhoods_by_city: function () {
            var $this = $(".ere-property-city-ajax", css_class_wrap);
            if ($this.length) {
                var selected_city = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_neighborhoods_by_city_ajax',
                        'city': selected_city,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".ere-property-neighborhood-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-neighborhood-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-neighborhood-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        execute_url_search: function () {
            $('.ere-advanced-search-btn', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var search_form = $(this).closest('.search-properties-form'),
                    search_url = search_form.data('href'),
                    search_field = [],
                    query_string = '?';
                if (search_url.indexOf('?') !== -1) {
                    query_string = '&';
                }
                $('.search-field', search_form).each(function () {
                    var $this = $(this),
                        field_name = $this.attr('name'),
                        current_value = $this.val(),
                        default_value = $this.data('default-value');
                    if (current_value != default_value) {
                        search_field[field_name] = current_value;
                    }
                });
                $('.ere-sliderbar-filter', search_form).each(function () {
                    var $this = $(this),
                        field_name_min = $this.find('.min-input-request').attr('name'),
                        field_name_max = $this.find('.max-input-request').attr('name'),
                        current_value_min = $this.find('.min-input-request').val(),
                        current_value_max = $this.find('.max-input-request').val(),
                        default_value_min = $this.data('min-default'),
                        default_value_max = $this.data('max-default');
                    if (current_value_min != default_value_min || current_value_max != default_value_max) {
                        search_field[field_name_min] = current_value_min;
                        search_field[field_name_max] = current_value_max;
                    }
                });
                var other_features = '';
                $('[name="other_features"]', search_form).each(function () {
                    var $this = $(this),
                        value = $this.attr('value');
                    if ($this.is(':checked')) {
                        other_features += value + ";";
                    }
                });
                if (other_features !== '') {
                    other_features = other_features.substring('0', other_features.length - 1);
                    search_field['other_features'] = other_features;
                }
                if (search_field !== []) {
                    for (var k in search_field) {
                        if (search_field.hasOwnProperty(k)) {
                            query_string += k + "=" + search_field[k] + "&";
                        }
                    }
                }
                query_string = query_string.substring('0', query_string.length - 1);
                window.location.href = search_url + query_string;
            });
        },
        set_slider_filter: function (elm) {
            var $container = elm,
                min = parseInt($container.attr('data-min-default')),
                max = parseInt($container.attr('data-max-default')),
                min_value = $container.attr('data-min'),
                max_value = $container.attr('data-max'),
                $sidebar_filter = $container.find('.sidebar-filter'),
                min_text = '',
                max_text = '',
                x, y;
            $sidebar_filter.slider({
                min: min,
                max: max,
                range: true,
                values: [min_value, max_value],
                slide: function (event, ui) {
                    x = ui.values[0];
                    y = ui.values[1];
                    $container.attr('data-min', x);
                    $container.attr('data-max', y);
                    $container.find('input.min-input-request').attr('value', x);
                    $container.find('input.max-input-request').attr('value', y);

                    if ($container.find('span').hasClass("not-format")) {
                        min_text =  x;
                        max_text =  y;
                    } else {
                        min_text =  ERE.number_format(x);
                        max_text = ERE.number_format(y);
                    }

                    if ($container.hasClass('ere-sliderbar-price')) {
                        if (ere_main_vars.currency_position === 'before') {
                            min_text =  ere_main_vars.currency + min_text;
                            max_text = ere_main_vars.currency + max_text;
                        } else {
                            min_text = min_text +  ere_main_vars.currency;
                            max_text = max_text + ere_main_vars.currency;
                        }
                    }

                    $container.find('span.min-value').html(min_text);
                    $container.find('span.max-value').html(max_text);
                },
                stop: function (event, ui) {

                }
            });
        },
        register_slider_filter: function () {
            $(".ere-sliderbar-filter", css_class_wrap).each(function () {
                var slider_filter = $(this);
                ERE_Property_Search.set_slider_filter(slider_filter);
            });
        },
        set_slider_value: function () {
            $('.ere-sliderbar-filter', css_class_wrap).each(function () {
                var $this = $(this),
                    min_default = $this.attr('data-min-default'),
                    max_default = $this.attr('data-max-default'),
                    min_value = $this.attr('data-min'),
                    max_value = $this.attr('data-max'),
                    left = (min_value - min_default) / (max_default - min_default) * 100 + '%',
                    width = (max_value - min_value) / (max_default - min_default) * 100 + '%',
                    left_max = (max_value - min_default) / (max_default - min_default) * 100 + '%';
                $this.find('.ui-slider-range.ui-corner-all.ui-widget-header').css({
                    'left': left,
                    'width': width
                });
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default').css('left', left);
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default:last-child').css('left', left_max);
            })
        },
        change_price_on_status_change: function (status) {
            $.ajax({
                type: 'POST',
                url: ajax_url,
                dataType: 'json',
                data: {
                    'action': 'ere_ajax_change_price_on_status_change',
                    'status': status,
                    'price_is_slider': price_is_slider
                },
                success: function (response) {
                    if (response.slide_html) {
                        $('.ere-sliderbar-price-wrap', css_class_wrap).html(response.slide_html);
                        ERE_Property_Search.register_slider_filter();
                        ERE_Property_Search.set_slider_value();
                    }
                    else {
                        if (response.min_price_html) {
                            $('select[name="min-price"]', css_class_wrap).html(response.min_price_html);
                        }
                        if (response.max_price_html) {
                            $('select[name="max-price"]', css_class_wrap).html(response.max_price_html);
                        }
                    }
                }
            });
        }
    };
    $(document).ready(function () {
        if (!$('body').hasClass('elementor-editor-active')) {
            ERE_Property_Search.init();
        }
    });
})(jQuery);