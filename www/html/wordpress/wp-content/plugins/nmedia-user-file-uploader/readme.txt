=== Frontend File Manager Plugin ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: Front end upload, File uploader, User files, User files manager, File uploaders, Image upload, user private files
Requires at least: 3.5
Tested up to: 5.8.1
Stable tag: 21.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

N-Media Frontend File Manager plugin allow site users to upload files and share with admin.

== Description ==
This plugin lets the wordpress site users to upload files for admin. Each file is saved in private directory so each user can download/delete their own files after login. For more control please see PRO feature below. Use folowing shortcode:: <strong>[ffmwp]</strong>

<h4>NOTE: Since version 20.0 Old shortcode [nm-wp-file-uploader] will be deprecated soon</h4>
<h3>Legacy: If you face any issue in new shortcode you can rever back using legacy shortcode: [nm-wp-file-uploader-legacy]</h3>

== Quick Video ==
[vimeo https://vimeo.com/285132267]

== Features ==
* Secure Uploader Script
* Fast, Responsive and Beautiful UI
* Searching, Sorting Filters
* File Detail Popup
* File Types & Size Settings
* Labels for Upload & Save Button
* Progressbar Uploader
* Thumbs for Images
* File Details in Admin

<h3>New Pro Demo</h3>
<a href="http://filemanager.nmediahosting.com/">See Demo</a>

== Pro Features ==
* Create Directories
* Set Maximum File Upload
* Set Limit per User File Count
* Set Filesize quota for Roles
* Email Notifications Settings
* File Rename by Timestamp Prefix
* Allow Geusts to Upload
* Allow Users to Share File via Email
* File Groups
* Create Unlimited Download Areas
* File Meta - Create Fields and attache with files
* Visual Composer Addon Avaiable

== Download Areas ==
Downloads Manager allow you to create unlimited download pages. Now file sources can be selected from User Roles, Users and Groups. And File
access can be granted to User Roles and indivisual users.
[vimeo https://vimeo.com/287895466]

== File Meta ==
File Meta are fields below, which admin can drag/drop. This will be attached to all files and user can add meta against each file.
<ul>
	<li><strong>Text</strong></li>
	<li><strong>Textarea</strong>]</li>
	<li><strong>Select</strong></li>
	<li><strong>Checkbox</strong></li>
	<li><strong>Mask (customized format)</strong></li>
	<li><strong>Email</strong></li>
	<li><strong>Date (datepicker)</strong></li>
	<li><strong>Image</strong></li>
	<li><strong>Checkbox</strong></li>
	
</ul>

<a href="https://najeebmedia.com/wordpress-plugin/wp-front-end-file-upload-and-download-manager/">Pro Version</a>


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `File Manager` menu

== Frequently Asked Questions ==

= Only user can see their own files? =

Yes, not others.

= Can I set filesize and types? =
Yes

= Can I upload Big files? =
Yes, Big files can be uplaoded

= Where all files are saved? =
All files are saved under each user directory

= Can admin download files from Dashboard? =

Yes

= Why I see HTTP Error message =
it is because of your server side settings, sometime php.ini does not allow to upload big files. You need to check following two settings in php.ini:<br>
1- post_max_size<br>
2- upload_max_filesize
<a href="http://www.najeebmedia.com/how-increase-file-upload-size-limit-in-wordpress/">check this tutorial</a>

== Screenshots ==

1. Frontend View File Uploading
2. Frontend View List Files
3. Frontend View List Files and Directories (PRO)
4. Frontend View of Single File Detail
5. Adding Fields (File Meta) PRO
6. File Listing with Details

== Changelog ==
= 21.2 May 13, 2022 =
* Feature: Tiff image support added.
* Feature: Files excluded form search
* Bug fixed: Js error: fileupload.js?ver=5.9.2:67 Uncaught TypeError: Cannot read properties of undefined (reading 'file_dimension_error') removed
* Feature: Gutenberg Block Removed, some issues when renderd with Gutenberg.
= 21.1 February 23, 2022 =
* Feature: File Revision Addon Updated with new version
= 21.0 January 26, 2022 =
* Feature: Refresh page removed after edit title and description.
* Tweaks: Upload area is not set to top
* Warnings Removed: Illegal key warning removed from helpers.php file line 41
* Bug fixed: Download area rendering issue fixed with upload option
= 20.9 December 23, 2021 =
* Bug fixed: [Syntax error fixed for PHP version](https://wordpress.org/support/topic/files-data-is-saving-overlay-does-not-go-away/)s
* Bug fixed: [WP function deprecated or wrong called, replaced](https://wordpress.org/support/topic/php-error-497/)
* Tweaks: When user is removed from DB their files may generates some warnings.
= 20.8 December 18, 2021 =
* Bug fixed: File meta wrapper div showing even no meta attached
* Bug fixed: File cancel button were linked to saved button.
* Bug fixed: File searching and sorting issue fixed inside directories.
* Tweaks: file title was set inside a h3, now it is set with span
* Tweaks: File title and description styles updated with new classes
= 20.7 December 12, 2021 =
* Bug fixed: Buddypress files inside directories were also showing outside, it is fixed
* Bug fixed: File meta issue fixed for loggedin members
= 20.6 November 24, 2021 =
* Tweaks: `<h3>` tags are replaced with div tag for CSS fix.
* Feature: Searching the files by description is added
* Bug fixed: [AWS thumb is now generated using base64 URL](https://clients.najeebmedia.com/forums/topic/aws-s3-and-file-manager-problem-2/)
* Bug fixed: File upload, edit or move errors sometimes due to nonce check, it is fixed
= 20.5 November 22, 2021 =
* Guest upload issue fixed
* Bug fixed: buddypress file group sharing issue fixed
= 20.4 October 31, 2021 =
* Bug fixed: Pagination option disabled in Shared Files (user specific addon) due to issue in legacy version
* [Due to too many reported issues regarding the Nonce check, it is disabled for now](https://wordpress.org/support/topic/unable-to-save-on-settings-page/)
= 20.3 October 31, 2021 =
* Bug fixed: Guest file upload issues fixed
= 20.2 October 31, 2021 =
* Bug fixed: File upload issue fixed from dashboard
* Bug fixed: File upload issue fixed after first upload.
= 20.1 October 29, 2021 =
* Bug fixed: Loading all files when directory is created in groups
* Bug fixed: Directories were not adding in groups, now it is fixed.
* Feature: Create Directory label option added in settings
= 20.0 September 11, 2021 =
* Feature: Fast UI with WP Util Template
* Feature: Page refresh removed on file created, delete
= 19.3 July 7, 2021 =
* Bug fixed: File meta fields issues fixed
* Bug fixed: File count issue fixed
= 19.2 July 7, 2021 =
* Bug fixed: Data sanitized and escaped.
= 19.1 June 30, 2021 =
* Bug fixed: Sanitized and escaped all data where required
* Feature: Bootstrap older version replaced with latest
= 19.0 June 30, 2021 =
* Bug fixed: Readme file stable tag now set to standard 
* Bug fixed: downlaod.php is removed all the way
* Bug fixed: All data checked and sanitized again
* Bug fixed: All data checked and escaped again
* Bug fixed: Get rid of Out of Date Libraries
= 18.3 June 26, 2021 =
* Bug fixed: Some security related issues fixed mentioned by WP security team.
= 18.2 June 23, 2021 =
* Bug fixed: [File delete issue fixed with AMAZON files](https://clients.najeebmedia.com/forums/topic/amazon-s3-addon-pro-plugin/)
= 18.1 June 7, 2021 =
* Feature: Directories now can be moved inside another directories
* Bug fixed: BuddyPress releated issue for group file sharing code optimized.
* Bug fixed: JS related issue fixed
= 18.0 June 7, 2021 =
* Bug fixed: Make plugin more secure from unauthorized access and activities
= 17.1 April 19, 2021 =
* Feature: API based scripts being added to pull files.
* Bug Fixed: [Sometime downloaded file is currupted, now it is fixed](https://clients.najeebmedia.com/forums/topic/corrupted-uploaded-files/)
= 17.0 February 21, 2021 =
* Bug fixed: Some options were not saving, now it is fixed.
* Bug fixed: Directory delete issue fixed
* Bug fixed: Directory inside files issue fixed for table view
* Tweaks: Directories rename option added via Dashboard.
= 16.1.1 October 7, 2020 =
* Bug fixed: Some issues fixed caused by last release
* Bug fixed: images with capital type like PNG, have broken thumbs. Now it is fixed.
= 16.0 October 5, 2020 =
* Feature: WaterMark Addon Released
* Tweaks: Default thumb site set
* Bug fixed: Delete file issue fixed
= 15.9 April 21, 2020 =
* Bug Fixed: Share files issued fixed.
= 15.8 April 19, 2020 =
* Feature: Admin can see all user's files on the frontend.
* Feature: Admin can share the files on the frontend.
* Bug Fixed: File can be deleted from the admin panel.
* Bug Fixed: File count issue fixed.
* Bug Fixed: Files thumb issue fixed.
* Bug Fixed: Translation file updated.
* Bug Fixed: User specific model issue fixed.
= 15.7 Feb 29, 2020 =
* Feature: Allow any user see each user files. 
* Feature: Input Field added in field meta
= 15.6 Feb 16, 2020 =
* Bug Fixed: Thumbs enable for all type of files for amazon s3
= 15.5 Feb 6, 2020 =
* Bug Fixed: File upload with Amazon s3 addon are fixed
= 15.4 Jan 27, 2020 =
* Feature: Filename support with all languages
= 15.3 Jan 22, 2020 =
* Feature: Admin can also upload the files & create the dir on admin side. 
* Bug fixed: Field meta default value set.
* Bug fixed: Files count correct.
* Bug fixed: Screen option on admin side working.
= 15.2 Nov 5, 2019 =
* Bug fixed: Thumbnail upadted of MS files .
= 15.1 Oct 30, 2019 =
* Bug fixed: upload multiple file using drag & drop restriction applied.
= 15.0 Oct 17, 2019 =
* Bug fixed: Multiple uploaded files not showing, but only single file.
* Feature: Pagination added to load large number of files
= 14.9 Sep 19, 2019 =
* Feature: [Language file updated](https://clients.najeebmedia.com/forums/topic/no-language-file/)
= 14.8 Aug 26, 2019 =
* Bug fixed: A fatal error removed in last release.
* Tweaks: Warnings removed on front-end.
= 14.7 Aug 20, 2019 =
* Feature: Amazon image thumb display added.
* Feature: Front-end design change
* Bug fixed: Email notification on file upload fix
* Bug fixed: File upload limit fix
= 14.6 June 16, 2019 = 
* Feature: PRO feature added Set default upload directory by role for new files.
= 14.5 June 02, 2019 = 
* Tweaks: Language Translation updated.
= 14.4 May 27, 2019 = 
* Feature: PRO feature added to set filesize limit for role base user.
* Feature: PRO feature added to set total file uploaded limit for role base user. 
= 14.3 May 23, 2019 = 
* Tweaks: Alert fixed during page refresh and files upload.
= 14.2 MAY 16, 2019 =
* Bug fixed: Field Meta reset
= 14.1 May 9, 2019 = 
* Feature: PRO feature added to Disallow users to delete files as Option
= 14.0 March 17, 2019 =
* Feature: %USER_EMAIL% template var added in email
* Feature: Gutenberg Block Support Added
* Feature: WordPress Latest version compatible check
= 13.9 February 28, 2019 =
* Feature: Now Download page show directories
* Feature: Option Add to Open PDF and Images file on new Tab
= 13.8 February 11, 2019 =
* Feature: Public user access for Files is removed from shortcode, only settings allow public user access
* Bug fixed: Download issue fixed for Amazon Files for Public Users
= 13.7 January 27, 2019 =
* Bug fixed: [Amazon Download file issue in admin panel fixed](https://clients.najeebmedia.com/forums/topic/bug-file-download-from-amazon-s3/)
* Bug fixed: [Plugin were not working in IE 11, it is fixed now](https://clients.najeebmedia.com/forums/topic/upload-button-does-nothing-in-internet-explorer-11/)
= 13.6 December 11, 2018 =
* Bug fixed: Download URL was wrong in email when used AMAZON S3. It's fixed now.
* Tweaks: Warnings removed for admin settings when not set.
= 13.5 November 29, 2018 =
* Bug fixed: Amazon download link will be gnerated on the run to prevent memory overflow issue
= 13.4 November 20, 2018 =
* Feature: Readme file added about secure files
* Feature: POT files added and some translations
= 13.3 September 29, 2018 =
* Bug fixed: Directories were not showing it's fixed
* Feature: Amazon Addon - Directories will also shown on Amazon S3 Bucket
= 13.2 September 23, 2018 =
* Bug fixed: Non-existed file will not be shownn on front end.
= 13.1 September 3, 2018 =
* Bug fixed: [File groups not attached from frontend](https://clients.najeebmedia.com/forums/topic/folder-everbody-can-see/#post-9309)
* Bug fixed: Download page issue (PRO)
* Bug fixed: Page scrolling were disabled when directory is selected.
= 13.0 August 19, 2018 =
* Feature: [Add shortcode paramter group_id to add files in group_id](https://najeebmedia.com/2015/01/19/share-files-groups-create-download-sections-file-upload-download-manager-version-8-3/)
* Feature: Add settings to hide Uploader Area or Files Area on Frontend
= 13.0 August 15, 2018 =
* Feature: New and Fast UI
* Feature: Admin Column for info
* Bug fixed: All WordPress warnings removed
* Bug fixed: Design related issue fixed
= 5.2 March 7, 2018 =
* Bug fixed: Creating thumb issue in PHP version fixed
= 5.1 =
* Bug fixed: Settings save issue in new PHP version fixed
= 5.0 =
* Bug fixed: Some php warnings removed
* Bug fixed: functions renamed properly
* Bug fixed: scripts enqued properly
* Bug fixed: DataTable API version updated
* Bug fixed: offload image removed
* Bug fixed: extra files and directories removed
* Bug fixed: filename sanitzed while uploading and delete
= 4.2 =
* Bug fixed: Invalid filetype error in Wordpress Version 4.7.2
* Bug fixed: Physical files not removed, now it is removing.
* Bug fixed: Intermedia images are generated on each file upload, now only one image and thumb will be generated
= 4.1 =
* Feaure: Tanslation support added for different laguages. Included languages, DE, ES, FR, NL
= 4.0 =
* Bug fixed: Arbitrary File Upload Vulnerability issue fixed
= 3.9 =
* Added: Some admin UI element added
= 3.8 =
* SECURITY ALERT: This version has removed a BUG related to security. Remote invalid file types are NOT allowed
= 3.7 =
* No more Flash needed for IE. It's replaced with HTML5 runtime.
= 3.6 =
* Plupload replace with new version 2.1.2
= 3.5 =
BUG Fixed: Filename will will displayed after uploading for all files. 

= 3.4 =
* IE not supported message will be shown if IE browser detected

= 3.3 =
* issue with FF/IE on file saving is fixed.

= 3.2 =
* reloding the page once file is save.

= 3.1 =
* plugin option menu was replacing appearance menu. Fixed now

= 3.0 =
* developed on new plugin framework which is more efficient
* better upload script using PlUpload
* listing uploaded files with Data Table
* showing images thumbs

= 2.1 =
* Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.

= 2.0 = 
* doupload.php and uploadify.php files have removed for best security practice
* front end design is replced with ul/li based structure 
* pagination control

= 1.8 =
* Some major security issues is being fixed, please update to this version

= 1.7 =
* Admin can see the file uploade by users

= 1.6 =
* there was error sometimes when creating directory for users, not it is fixed.

= 1.5 = 
* Physical file deleted from folder
* Every user will have its own upload directory as `user_name`
* File Name field is removed

= 1.4 =
* Change the Upload File button 

= 1.3 =
* Fixed the bug for php short code
= 1.0 =
* It is first version, and working perfectly

= 1.2 =
* Fixed content placement issue when using shortcode in middle of post/page.

= 1.1 =
* Just fixed the delete file bug.


== Upgrade Notice ==

= 1.0 =
Nothing for now.

= 1.1 =
Update to this version, Delete File issue is just fixed.

= 1.2 =
Update to this version, Content Placement issue is being fixed

= 1.3 =
Fixed the bug for php short code

= 1.4 =
Wrapped up the Upload File Button with some CSS.

= 1.5 =
This plugin has three major changes, please update to get these.

= 1.6 =
Upload directory was not creating due to some server side settings, now it is fixed

= 1.7 =
Admin can see the file uploade by users

= 1.8 =
Some major security issues is being fixed, please update to this version

= 2.0 = 
doupload.php and uploadify.php files have removed for best security practice
front end design is replced with ul/li based structure 
pagination control

= 2.1 =
Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.

= 3.0 =
this version has major updates. It's not using userfiles table. But we included a migration script which will copy old files into new custom post types. Although some data may be lost.

1. It is very light plugin
2. We are working on more plugins to get our users more excited.
3. More options/controls will be given soon.

= 3.6 =
* Plupload replace with new version 2.1.2, MUST update to this version as older version of plupload have some security issues.

= 3.8 =
* SECURITY ALERT: This version has removed a BUG related to security. Remote invalid file types are NOT allowed