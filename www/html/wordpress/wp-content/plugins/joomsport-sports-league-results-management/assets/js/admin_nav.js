jQuery(document).ready(function() {
  jQuery('div.wp-menu-name').each(function() {
    if (jQuery(this).text() == "JoomSport") {
      // jsgroup
      jQuery('#adminmenu .wp-submenu li > a[href*="=joomsport"]').parent().addClass("jsjsgroup");
      // jsgroup-1
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_season"]').parent().addClass("jsgroup-1");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_match"]').parent().addClass("jsgroup-1");
      // jsgroup-2
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_team"]').parent().addClass("jsgroup-2");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_player"]').parent().addClass("jsgroup-2");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_venue"]').parent().addClass("jsgroup-2");
      jQuery('#adminmenu .wp-submenu li > a[href$="edit.php?post_type=joomsport_person"]').parent().addClass("jsgroup-2");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_import"]').parent().addClass("jsgroup-2");
          // jsgroup-3
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport-page-events"]').parent().addClass("jsgroup-3");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport-page-boxfields"]').parent().addClass("jsgroup-3");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_personcategory&post_type=joomsport_person"]').parent().addClass("jsgroup-3");
      
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport-page-gamestages"]').parent().addClass("jsgroup-3");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport-page-extrafields"]').parent().addClass("jsgroup-3");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_settings"]').parent().addClass("jsgroup-3");
      jQuery('#adminmenu .wp-submenu li > a[href$="=joomsport_help"]').parent().addClass("jsgroup-3");

      jQuery("li.jsgroup-1:first").before('<fieldset class="item item-1"><legend>Manage</legend></fieldset>');
      jQuery("li.jsgroup-2:first").before('<fieldset class="item item-2"><legend>Enter data</legend></fieldset>');
      jQuery("li.jsgroup-3:first").before('<fieldset class="item item-3"><legend>Configure</legend></fieldset>');
    }
  });
});