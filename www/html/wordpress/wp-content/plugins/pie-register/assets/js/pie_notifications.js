'use strict';

var PieRegAdminNotifications = window.PieRegAdminNotifications || ( function( document, window, $ ) {

    /**
     * Elements holder.
     *
     * @type {object}
     */
    var el = {
        $notifications:    $( '#piereg-notifications' ),
        $nextButton:       $( '#piereg-notifications .navigation .next' ),
        $prevButton:       $( '#piereg-notifications .navigation .prev' ),
        $adminBarCounter:  $( '#wp-admin-bar-pie_register .pie-reg-admin-bar-menu-notification-counter'),
    };

    /**
     * Public functions and properties.
     *
     * @type {object}
     */
    var app = {

        /**
         * Start the engine.
         */
        init: function() {

            $( app.ready );
        },

        /**
         * Document ready.
         */
        ready: function() {

            app.updateNavigation();
            app.events();
        },

        /**
         * Register JS events.
         */
        events: function() {

            el.$notifications
                .on( 'click', '.dismiss', app.dismiss )
                .on( 'click', '.next', app.navNext )
                .on( 'click', '.prev', app.navPrev );
        },

        /**
         * Click on the Dismiss notification button.
         *
         * @param {object} event Event object.
         */
        dismiss: function( event ) {

            if ( el.$currentMessage.length === 0 ) {
                return;
            }
            
            // AJAX call - update option.
            var data = {
                action: 'piereg_notification_dismiss',
                nonce: pie_reg.nonce,
                id: el.$currentMessage.data( 'message-id' ),
            };

            $.post( pie_reg.ajax_url, data, function( response ) {
                if ( ! response.success ) {
                    return;
                }

                // Update counter.
                var count = parseInt( el.$adminBarCounter.text(), 10 );
                if ( count > 1 ) {
                    --count;
                    el.$adminBarCounter.html( '<span>' + count + '</span>' );
                } else {
                    el.$adminBarCounter.remove();
                    $('.piereg-menu-notification-indicator').remove();
                }

                // Remove notification.
                var $nextMessage = el.$nextMessage.length < 1 ? el.$prevMessage : el.$nextMessage;

                if ( $nextMessage.length === 0 ) {
                    el.$notifications.remove();
                } else {
                    el.$currentMessage.remove();
                    $nextMessage.addClass( 'current' );
                    app.updateNavigation();
                }
            } );
        },

        /**
         * Click on the Next notification button.
         *
         * @param {object} event Event object.
         */
        navNext: function( event ) {

            if ( el.$nextButton.hasClass( 'disabled' ) ) {
                return;
            }

            el.$currentMessage.removeClass( 'current' );
            el.$nextMessage.addClass( 'current' );

            app.updateNavigation();
        },

        /**
         * Click on the Previous notification button.
         *
         * @param {object} event Event object.
         */
        navPrev: function( event ) {

            if ( el.$prevButton.hasClass( 'disabled' ) ) {
                return;
            }

            el.$currentMessage.removeClass( 'current' );
            el.$prevMessage.addClass( 'current' );

            app.updateNavigation();
        },

        /**
         * Update navigation buttons.
         */
        updateNavigation: function() {

            el.$currentMessage = el.$notifications.find( '.piereg-notifications-message.current' );
            el.$nextMessage = el.$currentMessage.next( '.piereg-notifications-message' );
            el.$prevMessage = el.$currentMessage.prev( '.piereg-notifications-message' );

            if ( el.$nextMessage.length === 0 ) {
                el.$nextButton.addClass( 'disabled' );
            } else {
                el.$nextButton.removeClass( 'disabled' );
            }

            if ( el.$prevMessage.length === 0 ) {
                el.$prevButton.addClass( 'disabled' );
            } else {
                el.$prevButton.removeClass( 'disabled' );
            }
        },
    };

    return app;

}( document, window, jQuery ) );

// Initialize.
PieRegAdminNotifications.init();