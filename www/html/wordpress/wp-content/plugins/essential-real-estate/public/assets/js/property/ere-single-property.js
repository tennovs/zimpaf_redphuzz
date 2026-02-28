(function ($) {
    'use strict';
    $(document).ready(function () {
        var isRTL = $('body').hasClass('rtl');
        function ere_single_property_gallery($propertyGalleryWrap){
            var $sliderMain = $propertyGalleryWrap.find('.single-property-image-main'),
                $sliderThumb = $propertyGalleryWrap.find('.single-property-image-thumb');

            $sliderMain.owlCarousel({
                items: 1,
                nav:true,
                navElement: 'div',
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                dots:false,
                loop: false,
                smartSpeed: 500,
                rtl: isRTL
            }).on('changed.owl.carousel', syncPosition);

            $sliderThumb.on('initialized.owl.carousel', function () {
                $sliderThumb.find(".owl-item").eq(0).addClass("current");
            }).owlCarousel({
                items : 5,
                navElement: 'div',
                nav: false,
                dots: false,
                rtl: isRTL,
                margin: 9,
                responsive: {
                    1200: {
                        items: 5
                    },
                    992 : {
                        items : 4
                    },
                    768 : {
                        items : 3
                    },
                    0 : {
                        items: 2
                    }
                }
            }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el){
                //if you set loop to false, you have to restore this next line
                var current = el.item.index;

                $sliderThumb
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                var onscreen = $sliderThumb.find('.owl-item.active').length - 1;
                var start = $sliderThumb.find('.owl-item.active').first().index();
                var end = $sliderThumb.find('.owl-item.active').last().index();

                if (current > end) {
                    $sliderThumb.data('owl.carousel').to(current, 500, true);
                }
                if (current < start) {
                    $sliderThumb.data('owl.carousel').to(current - onscreen, 500, true);
                }
            }

            function syncPosition2(el) {
                var number = el.item.index;
                $sliderMain.data('owl.carousel').to(number, 500, true);
            }

            $sliderThumb.on("click", ".owl-item", function(e){
                e.preventDefault();
                if ($(this).hasClass('current')) return;
                var number = $(this).index();
                $sliderMain.data('owl.carousel').to(number, 500, true);
            });
        }
        var $propertyGalleryWrap = $('.property-gallery-wrap');
        ere_single_property_gallery($propertyGalleryWrap);

        function ere_property_print() {
            $('#property-print').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    property_id = $this.data('property-id'),
                    ajax_url = $this.data('ajax-url'),
                    property_print_window = window.open('', 'Property Print Window', 'scrollbars=0,menubar=0,resizable=1,width=991 ,height=800');
                $.ajax({
                    type: 'POST',
                    url: ajax_url,
                    data: {
                        'action': 'property_print_ajax',
                        'property_id': property_id,
                        'isRTL': $('body').hasClass('rtl') ? 'true' : 'false'
                    },
                    success: function (html) {
                        property_print_window.document.write(html);
                        property_print_window.document.close();
                        property_print_window.focus();
                    }
                });
            });
        }
        ere_property_print();
    });
})(jQuery);