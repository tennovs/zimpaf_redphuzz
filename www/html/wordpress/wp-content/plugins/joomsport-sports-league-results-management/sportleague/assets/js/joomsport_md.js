jQuery(document).ready(function() {
    jQuery('body').on("change",".shrtMday", function(){
        loadNewMd(jQuery(this).val(), jQuery(this).closest('.shrtMdContainer'));
    });
    jQuery('body').on("click",".btnMdPrev", function(){
        var md = jQuery(this).attr("data-md");
        if(md > 0){
            loadNewMd(md, jQuery(this).closest('.shrtMdContainer'));
        }

    });
    jQuery('body').on("click",".btnMdNext", function(){
        var md = jQuery(this).attr("data-md");
        if(md > 0){
            loadNewMd(md, jQuery(this).closest('.shrtMdContainer'));
        }
    });

    function loadNewMd(mdId, pdiv){
        console.log(pdiv.find('input[name="shrtAttrs"]').val());
        var attr = pdiv.find('input[name="shrtAttrs"]').val();
        var container = pdiv;
        console.log(container);
        pdiv.find(".shrtMday").prop("disabled", true);
        pdiv.find(".btnMdNext").prop("disabled", true);
        pdiv.find(".btnMdPrev").prop("disabled", true);
        pdiv.addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_md_load',
            'mdId': mdId,
            'shattr': attr,
        };

        jQuery.post(ajaxurl, data, function(response) {
            console.log(container);
            container.html(response);
            pdiv.removeClass("jsSjLoading");
        });
    }
});