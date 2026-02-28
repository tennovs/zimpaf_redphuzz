=== Rezgo Online Booking ===
Contributors: rezgo
Donate link: http://www.rezgo.com/
Tags:  tour operator software, tour booking system, activity booking software, tours, activities, events, attractions, booking, reservation, ticketing, e-commerce, business, rezgo
Requires at least: 3.3.0
Tested up to: 6.0
Requires PHP: 5.2
Stable tag: 4.1.6

Sell your tours, activities, and events on your WordPress website using Rezgo.

== Description ==

This plugin is completely free to use, but it requires a Rezgo account. [Try Rezgo today](https://www.rezgo.com "Try Rezgo") and experience the world's best hosted tour and activity booking platform.

**Rezgo** is a cloud based software as a service booking system that gives tours, activities, events, and attraction businesses the ability to manage their inventory, manage reservations, and process credit card payments. This plugin is a full featured front-end booking engine that connects your WordPress site to your Rezgo account.

= A fully integrated booking experience =

The Rezgo WordPress Booking Plugin is a completely integrated booking engine that takes advantage of all the content management capabilities of WordPress. Tag, search, tour list, and tour detail pages are all fully integrated with the WordPress site structure giving you the ability to link directly to product pages, specific dates, or apply promotional codes or referral ids.  Every Rezgo WordPress page is search optimized and index ready, which means your site gets all the benefit of your Rezgo content.

You get all the features of the regular Rezgo hosted booking engine plus the flexibility to completely control the look and feel of your customer booking experience.

= Plugin features include =

* Complete control over look and feel through CSS and access to display templates
* Full multiple booking (shopping cart) functionality
* Powerful AJAX booking calendar features
* Support for discount and referral codes
* Fully search-ready pages and search engine friendly URLs
* Integrated media gallery for photos and videos
* Complete transaction processing on your own site (with secure certificate)
* Full integration with leading payment gateways including Authorize.net, Opayo, Stripe, and many more.
* Plus all the other [features of Rezgo](http://www.rezgo.com/features)

= Support for your Rezgo Account =

If you need help getting set-up, Rezgo support is only a click or phone call away:

* [Rezgo Support](http://www.rezgo.com/support/)
* [Rezgo on Twitter](http://www.twitter.com/rezgo)
* [Rezgo on Facebook](http://www.facebook.com/rezgo)
* Pick up the phone and call +1 (604) 983-0083
* Email support AT rezgo.com

== Installation ==

= Install the Rezgo Booking Plugin =

1. Install the Rezgo Booking plugin in your WordPress admin by going to 'Plugins / Add New' and searching for 'Rezgo' **OR** upload the 'rezgo' folder to the `/wp-content/plugins/` directory
2. Activate the Rezgo plugin through the 'Plugins' menu in WordPress
3. Add your Rezgo Company Code (CID) and API KEY in the plugin settings
4. Use the shortcode [rezgo_shortcode] in your page content. Advanced shortcode commands are [available here](http://rezgo.me/wordpress).

= Plugin Configuration and Settings =

In order to use the Rezgo plugin, your Rezgo account must be activated.  This means that you **must** have a valid credit card on file with Rezgo before your Rezgo plugin can connect to your Rezgo account.

1. Make sure the Rezgo booking plugin is activated in WordPress.
2. Copy your Company Code and XML API KEY from your Rezgo Settings.
3. To display maps on tour pages, you can get your own [Google maps API key here](https://developers.google.com/maps/documentation/embed/get-api-key).
4. If you would like to use the included Rezgo Contact Form, you may want to [get a reCAPTCHA API Key](http://www.google.com/recaptcha).
5. Create a page and embed the Rezgo booking engine by using the shortcode: [rezgo_shortcode]
6. Advanced shortcode commands are [available here](http://rezgo.me/wordpress)

= Important Notes =

1. The Rezgo plug-in requires that you have permalinks enabled in your WordPress settings. You must use a permalink structure other than the default structure.  You can update your permalink structure by going to Settings > Permalinks in your WordPress admin.
2. The Rezgo plug-in is not supported on posts, it will only function on pages.
3. The Rezgo shortcode cannot be placed on a page that will be used as a static homepage.  The Rezgo shortcode must be placed on a page that has a slug.
4. If you DO NOT have a secure certificate enabled on your website, you should choose the option "Forward secure page to Rezgo".

== Frequently Asked Questions ==

= Read this first: When you encounter a problem with the Rezgo WordPress plugin... =

... before you do anything else, first check your Rezgo white label site to make sure everything works there.  If your white label website is working correctly and all your information is updated, then there might be an issue with your specific WordPress install.  If so, refer to the following common scenarios: 

= I have added the shortcode to a page but Rezgo is not displaying =

The most common reason is a problem connecting to your Rezgo API. Try removing and replacing your CID and API Key in the WordPress Rezgo settings page.  Check to make sure that, if the API Key you created is IP restricted, that the IP address of your website has not changed.

= Why am I seeing PHP errors or PHP code when displaying the tours? =

There are certain server requirements for the plugin to operate correctly. In particular, the following PHP directives need to be set accordingly.  Your hosting provider can help you properly configure these directives.

* The PHP [Client URL Library](http://www.php.net/manual/en/book.curl.php) must be installed
* PHP's [safe_mode](http://www.php.net/manual/en/features.safe-mode.php) needs to be OFF
* PHP's [open_basedir](http://www.php.net/manual/en/ini.core.php#ini.open-basedir) must NOT be set

= When I click on the details link I get a page not found error or nothing happens =

This could be because you are using the default link structure in WordPress. The Rezgo plug-in requires that you use permalinks in order to show the Rezgo content correctly.

= When I click on the book now button I get a page not found or server error? =

This could be because you do not have a secure certificate installed correctly on your site.  If this is the case, or if you are just not sure, we recommend you choose the "Forward secure page to Rezgo" option.

= Can I use the Rezgo WordPress Plugin without connecting to Rezgo? =

No, the Rezgo WordPress Plugin needs to pull tour and activity data so it needs to connect to your account via the Rezgo XML API. Your Rezgo credentials (specifically your Company Code (CID) and API Key) are used by the Rezgo WordPress Plugin to display your tour and activities on your WordPress site.

= Can I take credit card payments on my WordPress site? =

Yes, the Rezgo WordPress plugin has the ability to handle credit card payments.  Make sure to configure your Rezgo account to connect to your payment gateway.  Rezgo supports a growing list of Global payment processors including Stripe, Authorize.net, PayTrace, Opayo, and many others.  In order for your site to handle payments, you will need to install a secure certificate.  Check with your web host if you need help installing a secure certificate. If you do not wish to set-up a secure certificate, you can have the secure booking complete on your Rezgo hosted booking engine.

= I have updated my pricing or tour details, but my WordPress site doesn't show the new information =

Check to see if you are using a caching plugin like WPCache.  These caching plugins are great for speeding up your site but will also cache old details and pricing information.  You will want to exclude the Rezgo pages from your caching in order to avoid this in the future.

= I received an API warning email from Rezgo, what should I do? =

The Rezgo API automatically monitors usage and will notify you should your API show unusual activity.  If you receive an API warning message, check your web stats or analytics to see if you can find any unusual spikes in traffic. If you are using a content delivery network (CDN) like CloudFlare or you do daily PCI scans from a service like McAfee, make sure to exclude your Rezgo pages. If you continue to receive API warnings from Rezgo, this may be an indication that something is triggering excessive usage on your site. If you do not rectify the problem, your API usage may be restricted.

= What if I have a problem not covered here? =

Not a problem, you can contact us directly and create a support ticket by emailing <support@rezgo.com>

== Screenshots ==

1. Once you activate the Rezgo WordPress plugin, you will need to enter 
in your Rezgo API credentials on the Rezgo settings page located in your 
WordPress Admin.  Look for Rezgo in the sidebar.
2. Your tours and activities will display in a list on your default
tour page.  
3. Detail pages are designed to provide your customers with all the
information they need to make a booking decision. When customers choose a date, 
they are presented with a list of options.  Customers can then choose a preferred 
option in order to continue the booking process.  
4. If the WordPress site is secure, the transaction
will complete on the WordPress site.  If however, there is no secure
certificate, the transaction will complete on your Rezgo hosted
booking engine.

== Changelog ==
= 4.1.6 =
* Bug fixes

= 4.1.5 =
* Template UI updates
* Gift card purchase improvements
* Added compatibility for WordPress.com installs
* Compatibility with future PHP versions
* Fixes to canonical links
* Bug fixes

= 4.1.4 =
* Updated TMT modal to latest version 3.6.0

= 4.1.3 =
* Disabled gift card purchase for TMT gateway
* Changed labels on order / booking
* Bug fixes

= 4.1.2 =
* Bug fix (PHP short tag)

= 4.1.1 =
* Bug fixes for Stripe

= 4.1 =
* Added ability to sort and order through review ratings on review list page
* Upgraded to Font Awesome 5 Pro
* New UI on booking receipt and order summary (including print view)
* Updated book now form calendar view and edit view
* Added support for SCA/3DS on payment page and gift card purchase page
* Bug fixes

= 4.0.5 =
* Updated TMT modal to latest version 3.5.0
* Improved honeypot implementation on gift card purchase page
* Bug fixes

= 4.0.4 =
* Fixed 404 error from calendar view

= 4.0.3 =
* Payment processing bug fixes

= 4.0.2 =
* Force update of rewrite rules

= 4.0.1 =
* Bug fixes

= 4.0 =
* Updated tour availability calendar & booking flow / UI
* Simplification of cross-sell process
* Added support for Stripe Elements on payment page
* Added support for TMT payment modal
* Changed gift cards redemption to apply per order instead of per booking
* Updated to reCAPTCHA to v3 on gift card and contact pages
* Various bug fixes

= 3.5.6 =
* Bug fix when forwarding secure page to Rezgo

= 3.5.5 =
* Added fraud prevention measures to gift card purchase page
* Updated admin menu icon
* Updated branding images in plugin assets

= 3.5.4 =
* Updates to debug implementation

= 3.5.3 =
* Bug fix - distinguish multisite from subdirectory install when getting current page

= 3.5.2 =
* Bug fix - cart cookie not clearing on multisite in subdirectories

= 3.5.1 =
* Bug fixes

= 3.5.0 =
* Adding support for cross sell items

= 3.4.0 =
* Strike through prices
* Hide option when availability less than block size
* Inventory option specific CSS on booking form
* Status based CSS classes for receipt page
* Schema.org markup for reviews

= 3.3.0 =
* Support for Ticket Guardian
* Support for GDPR/privacy policy
* Added for verified reviews
* Added bundled pricing
* Pick up location support
* Check-in instruction support
* Check-in time support

= 3.2.3 =
* Bug fix - removing PHP short tags

= 3.2.2 =
* Adding support for waiver signing on booking checkout 
* Fixed ArgumentCountError in template function

= 3.2.1 =
* Bug fix - getting template directory locations

= 3.2.0 =
* Added better support for custom templates
* Fixed issue with multisite subdirectory method
* Improved multisite session / cookie handling
* Updated reCAPTCHA to version 2
* Changed Google map instances to embed instead of JS API
* Users must now enter their own Google maps API key
* Added 'calendar only' support to shortcode

= 3.1.1 =
* Changed method of getting Canonical link for iframe 

= 3.1.0 =
* Resolved incorrect API call when searching gift cards
* Removed session error that affected PAX edit

= 3.0.9 =
* Handling gift-card redirect to white label
* Removing PHP setting check before validating API connection
* Checking safe_mode and open_basedir before retrieving plugin template directories

= 3.0.8 =
* Adding gift card support
* Check for CID and API key before calling main plugin logic
* Code clean up

= 3.0.7 =
* Bug fix for subdirectory installs
* Removed default page title from rezgo_page_title()

= 3.0.6 =
* Added missing analytics to footer
* Added warnings for missing template directory

= 3.0.5 =
* Fixing issue with getting correct template directory
* Added canonical link to frame header
* Code cleanup

= 3.0.4 =
* Removing template backup function
* JS bug fixes on settings

= 3.0.3 =
* Reverting meta buffer methods
* Added 'pre_get_document_title' filter for Rezgo title
* Fixed PHP notice/warning output
* Preserve custom template directories during plugin update

= 3.0.2 =
* SEO title and description rewrite
* Ability to specify refid and page slug in shortcode
* Fixed issue with shortcode placement in page
* Fixed domain mismatch when setting cookies
* Bug fixes

= 3.0.1 =
* Bug fixes DIRECTORY_SEPARATOR

= 3.0.0 =
* Adding missing files

= 2.2.7 =
* Bug fixes open availability

= 2.2.6 =
* general security updates
* Bug fixes

= 2.2.5 =
* restoring missing files

= 2.2.4 =
* fixed bug, new libs

= 2.2.3 =
* fixed bug, intlTelInput lib missing

= 2.2.2 =
* fixed bug, false positive pax error

= 2.2.1 =
* fixed bug displaying specific tours with shortcode

= 2.2.0 =
* general security fixes
* Bug fixes

= 2.1.0 =
* general security fixes

= 2.0.9 =
* general security fixes

= 2.0.8 =
* fixing plugin paths

= 2.0.7 =
* Discontinued use of PHP short tags
* General bug fixes

= 2.0.6 =
* Updates to display of tour details page
* Additional element IDs and CSS classes to support customization 
* Clearing promo code cookie and session after purchase

= 2.0.5 =
* Added new setting to support setting all pages to secure (https)

= 2.0.4 =
* Added missing rewrite rules for about and contact pages

= 2.0.3 =
* Fixed incompatibility issue with WordPress 4.4

= 2.0.2 =
* Fixed error with "forward to Rezgo" setting

= 2.0.1 =
* Patching missing files

= 2.0.0 =
* Complete overhaul of plugin structure to match Rezgo white label version 6
* Plugin layout is now responsive

= 1.8.6 =
* Various bug fixes

= 1.8.5 =
* Fixes to image thumbnails to support both new and old formats.
* Modified JS validation on PAX form

= 1.8.4 =
* Fixes to media gallery and map on details page.

= 1.8.3 =
* Minor bug fixes.

= 1.8.2 =
* Added new output method.
* Fixed an issue with SSL detection.

= 1.8 =
* Added support for new line items system.
* Improved AJAX booking request to better prevent accidental submission.
* Added no-conflict template to the update exceptions.
* Added new line items method to rezgo class.

= 1.7 =
* Brand new shopping cart interface allows many items to be booked at once.
* New XML commit request supporting multiple items.
* New anti-spam measures added to contact form.
* Inconsistent date labels have been changed to "booked for."
* Some share links removed from item details.
* Fixed a bug causing the search date range to produce inconsistent results.
* Many bug fixes and performance improvements.

= 1.6.1 =
* Fixed an issue with the new default template not loading in certain themes.
* Added a state/prov dropdown for countries with existing state/prov lists.
* Fixed a number of small display issues with the new payment template.
* Added a no-conflict template for themes with jQuery conflicts.
* Fixed a rare bug preventing the booking page from forwarding to Rezgo.

= 1.6 =
* Updated payment step of booking page to new async version.
* Updated calendar ajax to new faster version used on white label.
* Updated jQuery in default template to use noConflict() mode.
* Fixed a number of small issues with the checkout process.
* Fixed a bug preventing the calendar from going forward more than 12 months.

= 1.5 =
* Added support for passing variables to the shortcode.
* Added support for new multi-tag searches.
* Improved handling of API keys entered on settings page.
* Switched all remaining file fetching to use configured fetch method.
* Plugin update should no longer remove custom templates.
* Fixed a number of display and instruction errors on settings page.
* Fixed an issue with 'required' field alerts on some browsers.
* Fixed a rare bug with the receipt print button.
* Fixed a bug with smart/keyword searches failing due to bad encoding.
* Fixed an issue with the plugin not returning it's output correctly.

= 1.4.5 =
* Moved to new repository.

= 1.4.3 =
* Deprecated Rezgo plugin and replaced with Rezgo Online Booking plugin

= 1.4.2 =
* Fixed a number of issues with directory pathing.
* Changed template location to match new pathing info.
* Added support for WordPress Multisite.
* Plugin directory name changed.

= 1.4.1 =
* Fixed a bug with the details links on the calendar when the month auto-advanced.

= 1.4 =
* Fixed a number of template path issues.
* Fixed an issue that would cause the calendar to hang if there was no availability.
* Added some default settings to the plugin config, including a warning about the default permalink structure.
* Improved calendar loading speed.
* Fixed a few issues with the receipt paypal button.
* Also fixed a whole pile of minor issues.

= 1.3 =
* Initial release.

== Upgrade Notice ==

= You have the most recent version =