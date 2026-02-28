var GSF_META_BOX;
(function($) {
    "use strict";

    GSF_META_BOX = {
        init: function() {
            var $firstMeta = $('.gsf-sections','.postbox').first().closest('.postbox');
            $('.gsf-sections','.postbox').each(function (index,el) {
                if ((index > 0)) {
                    var $wrap = $(el).closest('.postbox');
                    $(el).find('li').removeClass('active').appendTo($firstMeta.find('.gsf-sections ul'));
                    $wrap.find('.gsf-meta-box-fields > div').hide().appendTo($firstMeta.find('.gsf-meta-box-fields'));
                    $wrap.hide();
                }
            });


        }
    };
    $(document).on('gsf_before_init_fields',function() {
        GSF_META_BOX.init();
    });
})(jQuery);