var piereg = jQuery.noConflict();

piereg(document).ready(function($) {
    (function($){
        piereg( "#dialog-message" ).dialog({
            dialogClass: "fixed-dialog",
              modal: true,
              buttons: {
                  Ok: function() {
                  piereg( this ).dialog( "close" );
                  }
              }
        });
    })(jQuery);
});

// Declare jQuery Object to $.
$ = jQuery;