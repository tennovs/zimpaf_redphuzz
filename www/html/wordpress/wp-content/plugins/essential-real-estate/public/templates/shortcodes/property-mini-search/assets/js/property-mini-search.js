jQuery(document).ready(function ($) {
    "use strict";

    function ere_execute_url_mini_search() {
        $('#mini-search-btn', '.ere-mini-search-properties-form').on('click', function (e) {
            e.preventDefault();
            var search_form = $(this).closest('.ere-mini-search-properties-form'),
                search_url = search_form.data('href'),
                search_field = [],
                query_string = '?';
            if(search_url.indexOf('?') !== -1) {
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
            if(search_field !== []) {
                for (var k in search_field){
                    if (search_field.hasOwnProperty(k)) {
                        query_string += k+"="+search_field[k] + "&";
                    }
                }
            }
            query_string = query_string.substring('0', query_string.length - 1);
            window.location.href = search_url+query_string;
        });
    }
    ere_execute_url_mini_search();
});