
(function () {
    tinymce.PluginManager.add('udraw_price_matrix_shortcode', function (editor, url) {
        editor.addButton('udraw_price_matrix_shortcode', {
            text: 'Price Matrix',
            icon: false,
            type: 'menubutton',
            onPostRender: function () {
                var self = this;
                jQuery.getJSON(ajaxurl + '?action=udraw_price_matrix_get_all',
                     function (data) {
                         var _price_matrix_mce_menu = [];
                         for (var x = 0; x < data.length; x++) {
                             var _menuItem = Object();
                             var _accessKey = data[x].access_key;
                             _menuItem.text = data[x].name;
                             _menuItem.onclick = function () {
                                 editor.insertContent('[display_udraw_price_matrix id="' + _accessKey + '"]');
                             };
                             _price_matrix_mce_menu.push(_menuItem);
                         }

                         self.state.data.menu = self.settings.menu = _price_matrix_mce_menu;
                     }
                 );
            },
        });      
    });
})();