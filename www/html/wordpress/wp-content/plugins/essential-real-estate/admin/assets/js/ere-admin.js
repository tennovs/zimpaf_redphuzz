(function ($) {
    'use strict';
    var ERE_ADMIN = {
        init: function () {
            this.toolTip();
            this.formSearchProcess();
            this.additionalFieldProcess();
            this.additionalFeaturesProcess();
        },
        toolTip: function () {
            $('.tips, .help_tip').tipTip({
                'attribute': 'data-tip',
                'fadeIn': 50,
                'fadeOut': 50,
                'delay': 200
            });
        },
        formSearchProcess: function () {
            var css_class_wrap = '.ere-property-select-meta-box-wrap';
            var ajax_url = ere_admin_vars.ajax_url;
            var enable_filter_location=ere_admin_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.ere-property-country-ajax', css_class_wrap).select2();
                $('.ere-property-state-ajax', css_class_wrap).select2();
                $('.ere-property-city-ajax', css_class_wrap).select2();
                $('.ere-property-neighborhood-ajax', css_class_wrap).select2();
            }

            var ere_get_states_by_country = function () {
                var $this = $(".ere-property-country-ajax", css_class_wrap);
                var $property_state = $(".ere-property-state-ajax", css_class_wrap);
                var $is_slug = $property_state.attr('data-slug');
                if (typeof($is_slug) === 'undefined') {
                    $is_slug='1';
                }
                if ($this.length && $property_state.length) {
                    var selected_country = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_states_by_country_ajax',
                            'country': selected_country,
                            'type': 0,
                            'is_slug':$is_slug
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $property_state.html(response);
                            var val_selected =$property_state.attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $property_state.val(val_selected);
                            }
                            $this.parent().children('.ere-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.ere-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.ere-loading').remove();
                        }
                    });
                }
            };
            ere_get_states_by_country();

            $(".ere-property-country-ajax", css_class_wrap).on('change', function () {
                ere_get_states_by_country();
            });

            var ere_get_cities_by_state = function () {
                var $this = $(".ere-property-state-ajax", css_class_wrap);
                var $property_city = $(".ere-property-city-ajax", css_class_wrap);
                var $is_slug = $property_city.attr('data-slug');
                if (typeof($is_slug) === 'undefined') {
                    $is_slug='1';
                }
                if ($this.length && $property_city.length) {
                    var selected_state = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_cities_by_state_ajax',
                            'state': selected_state,
                            'type': 0,
                            'is_slug':$is_slug
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $property_city.html(response);
                            var val_selected = $property_city.attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $property_city.val(val_selected);
                            }
                            $this.parent().children('.ere-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.ere-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.ere-loading').remove();
                        }
                    });
                }
            };
            ere_get_cities_by_state();

            $(".ere-property-state-ajax", css_class_wrap).on('change', function () {
                ere_get_cities_by_state();
            });

            var ere_get_neighborhoods_by_city = function () {
                var $this = $(".ere-property-city-ajax", css_class_wrap);
                var $property_neighborhood = $(".ere-property-neighborhood-ajax", css_class_wrap);
                var $is_slug = $property_neighborhood.attr('data-slug');
                if (typeof($is_slug) === 'undefined') {
                    $is_slug='1';
                }
                if ($this.length && $property_neighborhood.length) {
                    var selected_city = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_neighborhoods_by_city_ajax',
                            'city': selected_city,
                            'type': 0,
                            'is_slug':$is_slug
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $property_neighborhood.html(response);
                            var val_selected = $property_neighborhood.attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $property_neighborhood.val(val_selected);
                            }
                            $this.parent().children('.ere-loading').remove();
                        },
                        error: function () {
                            $this.parent().children('.ere-loading').remove();
                        },
                        complete: function () {
                            $this.parent().children('.ere-loading').remove();
                        }
                    });
                }
            };
            ere_get_neighborhoods_by_city();

            $(".ere-property-city-ajax", css_class_wrap).on('change', function () {
                ere_get_neighborhoods_by_city();
            });
        },
        additionalFieldProcess: function () {
            var $wrapAdditionalField = $('#additional_fields');
            this.additionalFieldReadOnly();
            this.additionalFieldEventProcess($wrapAdditionalField);
            $wrapAdditionalField.on('gsf_add_clone_field', function (event) {
                var $target = $(event.target);
                ERE_ADMIN.additionalFieldEventProcess($target);

                $target.find('.gsf-field-panel-content,.gsf-clone-field-panel-inner')
                    .find('#additional_fields_label input,#additional_fields_id input,#additional_fields_select_choices textarea')
                    .val('');

                $target.find('.gsf-field-panel-content,.gsf-clone-field-panel-inner')
                    .find('#additional_fields_field_type select').val('text');
            });

            $(document).on('gsf_save_option_success', function () {
                console.log('Done');
                ERE_ADMIN.additionalFieldReadOnly();
            });
        },
        additionalFieldReadOnly: function() {
            $('.gsf-field-panel-content,.gsf-clone-field-panel-inner').each(function () {
                var $label = $(this).find('#additional_fields_label input'),
                    $id = $label.closest('.gsf-field-panel-content,.gsf-clone-field-panel-inner').find('#additional_fields_id input');
                if ($id.val() !== '') {
                    $id.attr('readonly', 'readonly');
                }
            });

        },
        additionalFieldEventProcess: function($wrap) {
            $wrap.find('.gsf-field-panel-content,.gsf-clone-field-panel-inner').find('#additional_fields_label input').on('change', function () {
                var $label = $(this),
                    $id = $label.closest('.gsf-field-panel-content,.gsf-clone-field-panel-inner').find('#additional_fields_id input');
                if ($id.attr('readonly') !== 'readonly') {
                    $id.val(ERE_ADMIN.toSlug($label.val()));
                }
            });
        },

        additionalFeaturesProcess: function() {
            var $wrap = $('#real_estate_additional_features');

            $wrap.find('button').on('click', function () {
                var count = $wrap.find('tbody tr').length;
                var html = '<tr>\n' +
                    '    <td class="sort">\n' +
                    '        <span><i class="dashicons dashicons-menu"></i></span>\n' +
                    '    </td>\n' +
                    '    <td class="title">\n' +
                    '        <input type="text" name="real_estate_additional_feature_title[' + count +  ']" value="">\n' +
                    '    </td>\n' +
                    '    <td class="value">\n' +
                    '        <input type="text" name="real_estate_additional_feature_value[' + count + ']" value="">\n' +
                    '    </td>\n' +
                    '    <td class="remove"><i class="dashicons dashicons-dismiss"></i></td>\n' +
                    '</tr>';

                $wrap.find('tbody').append(html);
                $wrap.find('.total').val(count + 1);
            });

            $wrap.find('tbody').sortable({
                'items': 'tr',
                handle: '.sort > span',
                update: function( event, ui ) {
                    ERE_ADMIN.reindexAdditionalFeatures($wrap);
                },
                stop: function (event, ui) {}
            });

            $wrap.on('click', '.remove > i', function () {
                $(this).closest('tr').remove();
                $wrap.find('.total').val($wrap.find('tbody tr').length);
                ERE_ADMIN.reindexAdditionalFeatures($wrap);
            });
        },
        reindexAdditionalFeatures: function($wrap) {
            $wrap.find(' tbody > tr').each(function (index) {
                $(this).find('input').each(function () {
                    var name = $(this).attr('name');
                    name = name.replace( /^(\w+\[)(\d+)(\].*)$/g , function(m,p1,p2,p3){ return p1+index+p3; });
                    $(this).attr('name', name);
                });
            });
        },

        toSlug: function(str) {
            str = String(str).toString();
            str = str.replace(/^\s+|\s+$/g, "");
            str = str.toLowerCase();

            var swaps = {
                '0': ['°', '₀', '۰', '０'],
                '1': ['¹', '₁', '۱', '１'],
                '2': ['²', '₂', '۲', '２'],
                '3': ['³', '₃', '۳', '３'],
                '4': ['⁴', '₄', '۴', '٤', '４'],
                '5': ['⁵', '₅', '۵', '٥', '５'],
                '6': ['⁶', '₆', '۶', '٦', '６'],
                '7': ['⁷', '₇', '۷', '７'],
                '8': ['⁸', '₈', '۸', '８'],
                '9': ['⁹', '₉', '۹', '９'],
                'a': ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å', 'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά', 'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ', 'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا', 'ａ', 'ä'],
                'b': ['б', 'β', 'ب', 'ဗ', 'ბ', 'ｂ'],
                'c': ['ç', 'ć', 'č', 'ĉ', 'ċ', 'ｃ'],
                'd': ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ', 'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ', 'ｄ'],
                'e': ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ', 'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э', 'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ', 'ｅ'],
                'f': ['ф', 'φ', 'ف', 'ƒ', 'ფ', 'ｆ'],
                'g': ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ', 'ｇ'],
                'h': ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ', 'ｈ'],
                'i': ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į', 'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ', 'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი', 'इ', 'ی', 'ｉ'],
                'j': ['ĵ', 'ј', 'Ј', 'ჯ', 'ج', 'ｊ'],
                'k': ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ', 'ک', 'ｋ'],
                'l': ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ', 'ｌ'],
                'm': ['м', 'μ', 'م', 'မ', 'მ', 'ｍ'],
                'n': ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န', 'ნ', 'ｎ'],
                'o': ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő', 'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό', 'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ', 'ｏ', 'ö'],
                'p': ['п', 'π', 'ပ', 'პ', 'پ', 'ｐ'],
                'q': ['ყ', 'ｑ'],
                'r': ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ', 'ｒ'],
                's': ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ', 'ſ', 'ს', 'ｓ'],
                't': ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ', 'თ', 'ტ', 'ｔ'],
                'u': ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ', 'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ', 'ｕ', 'ў', 'ü'],
                'v': ['в', 'ვ', 'ϐ', 'ｖ'],
                'w': ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ', 'ｗ'],
                'x': ['χ', 'ξ', 'ｘ'],
                'y': ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ', 'ϋ', 'ύ', 'ΰ', 'ي', 'ယ', 'ｙ'],
                'z': ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ', 'ｚ'],
                'aa': ['ع', 'आ', 'آ'],
                'ae': ['æ', 'ǽ'],
                'ai': ['ऐ'],
                'ch': ['ч', 'ჩ', 'ჭ', 'چ'],
                'dj': ['ђ', 'đ'],
                'dz': ['џ', 'ძ'],
                'ei': ['ऍ'],
                'gh': ['غ', 'ღ'],
                'ii': ['ई'],
                'ij': ['ĳ'],
                'kh': ['х', 'خ', 'ხ'],
                'lj': ['љ'],
                'nj': ['њ'],
                'oe': ['ö', 'œ', 'ؤ'],
                'oi': ['ऑ'],
                'oii': ['ऒ'],
                'ps': ['ψ'],
                'sh': ['ш', 'შ', 'ش'],
                'shch': ['щ'],
                'ss': ['ß'],
                'sx': ['ŝ'],
                'th': ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],
                'ts': ['ц', 'ც', 'წ'],
                'ue': ['ü'],
                'uu': ['ऊ'],
                'ya': ['я'],
                'yu': ['ю'],
                'zh': ['ж', 'ჟ', 'ژ'],
                '(c)': ['©'],
                'A': ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą', 'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ', 'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ', 'Ａ', 'Ä'],
                'B': ['Б', 'Β', 'ब', 'Ｂ'],
                'C': ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ', 'Ｃ'],
                'D': ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ', 'Ｄ'],
                'E': ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ', 'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э', 'Є', 'Ə', 'Ｅ'],
                'F': ['Ф', 'Φ', 'Ｆ'],
                'G': ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ', 'Ｇ'],
                'H': ['Η', 'Ή', 'Ħ', 'Ｈ'],
                'I': ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į', 'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ', 'Ｉ'],
                'J': ['Ｊ'],
                'K': ['К', 'Κ', 'Ｋ'],
                'L': ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल', 'Ｌ'],
                'M': ['М', 'Μ', 'Ｍ'],
                'N': ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν', 'Ｎ'],
                'O': ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő', 'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ', 'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ', 'Ｏ', 'Ö'],
                'P': ['П', 'Π', 'Ｐ'],
                'Q': ['Ｑ'],
                'R': ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ', 'Ｒ'],
                'S': ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ', 'Ｓ'],
                'T': ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ', 'Ｔ'],
                'U': ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ', 'Ｕ', 'Ў', 'Ü'],
                'V': ['В', 'Ｖ'],
                'W': ['Ω', 'Ώ', 'Ŵ', 'Ｗ'],
                'X': ['Χ', 'Ξ', 'Ｘ'],
                'Y': ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ', 'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ', 'Ｙ'],
                'Z': ['Ź', 'Ž', 'Ż', 'З', 'Ζ', 'Ｚ'],
                'AE': ['Æ', 'Ǽ'],
                'Ch': ['Ч'],
                'Dj': ['Ђ'],
                'Dz': ['Џ'],
                'Gx': ['Ĝ'],
                'Hx': ['Ĥ'],
                'Ij': ['Ĳ'],
                'Jx': ['Ĵ'],
                'Kh': ['Х'],
                'Lj': ['Љ'],
                'Nj': ['Њ'],
                'Oe': ['Œ'],
                'Ps': ['Ψ'],
                'Sh': ['Ш'],
                'Shch': ['Щ'],
                'Ss': ['ẞ'],
                'Th': ['Þ'],
                'Ts': ['Ц'],
                'Ya': ['Я'],
                'Yu': ['Ю'],
                'Zh': ['Ж']
            };
            Object.keys(swaps).forEach(function (swap) {
                swaps[swap].forEach(function (s) {
                    str = str.replace(new RegExp(s, "g"), swap);
                });
            });
            return str.replace(/[^a-z0-9 -]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
        }
    };

    $(document).ready(function () {
        ERE_ADMIN.init();
    });
})(jQuery);
