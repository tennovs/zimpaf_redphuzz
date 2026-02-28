<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 
	$action =	""; $active	= 'class="selected"';
	if(isset($_GET['tab']))
        $action	= sanitize_key($_GET['tab']);
        $this->no_addon_activated = $this->anyAddonActivated();        
	?>

<div id="container"  class="pieregister-admin">
    <div class="right_section pro-page">
        <div class="pro-features-top">
            <div class="top-left">
                <div class="welcome-text">
                    <h3>Welcome to Pie Register</h3>
                </div>
                <div class="welcome-description">
                    <p>
                    Pie Register helps you create registration forms in minutes with a simple drag and drop form builder. No coding required. You can build simple to the most robust forms and registration flows using the various form fields and UI controls. Customize the registration process using the many add-ons included with the premium version to make your website exclusive, spam-free, and secure.
                    </p>
                </div>
                <div class="go-pro-button">
                    <a href="https://pieregister.com/plan-and-pricing/" target="_blank">Get Started</a>
                </div>
            </div>
            <div class="top-right">
                <div class="right-img-pro">
                    <img src="<?php echo esc_url(plugins_url("assets/images/pro/pieregister-premium-features.png", dirname(__FILE__) )); ?>" alt="Pie Register Pro">
                </div>
            </div>
        </div>
        
        <ul class="go-pro-tabs">
            <li <?php echo ($action != "addons") ? $active :""; ?>><a href="admin.php?page=pie-pro-features"><?php _e("Features","pie-register") ?></a></li>
            <?php if( $this->no_addon_activated ){
                ?>
                <li <?php echo ($action == "addons") ? $active :""; ?>><a href="admin.php?page=pie-pro-features&tab=addons"><?php _e("Addons","pie-register") ?></a></li>
                <?php
            }
            ?>
        </ul>
        <div class="pane">
        	<?php if( $action == 'addons' ) { ?> 
            	<div id="tab2" class="tab-content">
                <div class="addons-container-section">
                    <div class="addon-row">
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/6.jpg", dirname(__FILE__) )); ?>" alt="Authorize.net Payment Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Authorize.net Payment Addon</h3>
                                        <p>Use Authorize.net addon to process membership payments using Pie Register.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=878">Get this addon - $19.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/authorize-net-payment-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/5.jpg", dirname(__FILE__) )); ?>" alt="Stripe Payment Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Stripe Payment Addon</h3>
                                        <p>Use Stripe addon to process membership payments using Pie Register.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=835">Get this addon - $19.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/stripe-payment-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/3.jpg", dirname(__FILE__) )); ?>" alt="Two-step Authentication Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Two-step Authentication Addon</h3>
                                        <p>Add an additional security layer by having users verify registration via SMS (TWILIO).</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=200">Get this addon - $19.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/two-step-authentication-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/4.jpg", dirname(__FILE__) )); ?>" alt="MailChimp Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>MailChimp Addon</h3>
                                        <p>Use Pie Register to export your site users into MailChimp lists to send communication, sales and marketing emails.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=197">Get this addon - $19.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/mailchimp-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/8.jpg", dirname(__FILE__) )); ?>" alt="Geolocation Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Bulk Email Addon</h3>
                                        <p>Bulk Email addon gives Admin the ability to send email in bulk to all the registered users at once.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=1190">Get this addon - $9.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/bulk-email-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/1.jpg", dirname(__FILE__) )); ?>" alt="Social Login Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Social Login Addon</h3>
                                        <p>Let your site or blog users to login via their Facebook, Twitter, Google, LinkedIn and WordPress accounts.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=199">Get this addon - $14.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/social-login-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/2.jpg", dirname(__FILE__) )); ?>" alt="Profile Search Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Profile Search Addon</h3>
                                        <p>With the Profile Search tool, admin can provide users the feature to search or filter to display user data.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=198">Get this addon - $7.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/profile-search-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/7.png", dirname(__FILE__) )); ?>" alt="Geolocation Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Geolocation Addon</h3>
                                        <p>Allows you to collect and store your website visitor’s geolocation details along with their form submission data.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=1190">Get this addon - $12.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/geolocation/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/9.jpg", dirname(__FILE__) )); ?>" alt="WooCommerce Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>WooCommerce Addon</h3>
                                        <p>With the WooCommerce Addon, you can now add fields for billing and shipping address to your PR forms.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=8226">Get this addon - $9.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/woocommerce-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/10.jpg", dirname(__FILE__) )); ?>" alt="Field Visibility Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>Field Visibility Addon</h3>
                                        <p>Allows you to show or hide certain fields on the front-end registration form or the User’s Profile page.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=8393">Get this addon - $19.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/field-visibility-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="addon-column margin-right">
                            <div class="addon-container">
                                <img class="addon-img" src="<?php echo esc_url(plugins_url("assets/images/pro/11.jpg", dirname(__FILE__) )); ?>" alt="bbPress Addon">
                                <div class="">
                                    <div class="addon-content-container">
                                        <h3>bbPress Addon</h3>
                                        <p>Show the Pie Register fields on your bbPress User profile and let your users edit the profile from there directly.</p>
                                        <a class="get-addon" href="https://store.genetech.co/cart/?add-to-cart=8930">Get this addon - $9.99</a>
                                        <a class="read-more" href="https://pieregister.com/addons/bbpress-addon/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"> Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>			
			<?php  } else { ?> 
				<div id="tab1" class="tab-content">
                <div class="features-main-container">
                    <div class="et_pb_row et_pb_row_1 features-row">
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url('<?php echo esc_url(plugins_url("assets/images/pro/feature-1.png", dirname(__FILE__) )); ?>')"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Multiple Registration Forms</h5>
                                    <p class="feature-content">Drag-drop fields to create registration forms so users can register to your blog or site.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/block-users.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>User Control</h5>
                                    <p class="feature-content">Block unwanted users by username, email and IP address. Allow users by username, email.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/role-based-redirection.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Role Based Redirection</h5>
                                    <p class="feature-content">Rules for Role-Based Redirection to land users on different pages based on user role.</p>
                                </div>
                            </a>
                        </div>

                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/auto-login.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Auto Login</h5>
                                    <p class="feature-content">Auto login users after registration and let them complete verification process later on.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/built-in-forms.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Built-in Pie Register Form Themes</h5>
                                    <p class="feature-content">Change the default forms UI and apply the built-in form themes according to website UI.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/login-security.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Customizable Login Security</h5>
                                    <p class="feature-content">Advanced security will lets you throw CAPTCHA based on the number of unsuccessful login attempts.</p>
                                </div>
                            </a>
                        </div>

                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/content-restriction.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Content Restriction</h5>
                                    <p class="feature-content">Restrict access to website pages or posts based on user role or current logged in status.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/timed-form-submission.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Timed Form Submission</h5>
                                    <p class="feature-content">Prevent bots for event timed submission.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/restrict-widgets.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Restrict Widgets</h5>
                                    <p class="feature-content">Set visibility of widgets for specific user roles and non-logged in users.</p>
                                </div>
                            </a>
                        </div>

                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/importnexport.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Import and Export</h5>
                                    <p class="feature-content">Want to quickly duplicate or move your existing WordPress user or configuration data?</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/ticket-support.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Ticket Based Support</h5>
                                    <p class="feature-content">Pie Register provides a premium support directly from the development team.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/user-roles.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>User Roles</h5>
                                    <p class="feature-content">Create and name custom user roles. Inherit permissions from WP User roles. Add user role dropdown in registration form.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/file-upload.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>File Upload</h5>
                                    <p class="feature-content">Admin can now restrict the file size, view uploaded files in the admin dashboard and download files in bulk.</p>
                                </div>
                            </a>
                        </div>
                        <div class="feature-single-container margin-right">
                            <div class="feature-icon-container" style="background-image: url(<?php echo esc_url(plugins_url("assets/images/pro/invitation-only.png", dirname(__FILE__) )); ?>)"></div>
                            <a class="" href="">
                                <div class="feature-content-container">
                                    <h5>Invitation only Registration</h5>
                                    <p class="feature-content">Rules for Role-Based Redirection to land users on different pages based on user role.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    

                    <!-- <div class="et_pb_row et_pb_row_1 features-row-last">
                        <a class="features-last-pricing" target="_blank" href="https://pieregister.com/plan-and-pricing/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"><?php _e("Upgrade Now","pie-register") ?></a>
                        <a class="view-all-features" target="_blank" href="https://pieregister.com/features/?utm_source=plugin-dashboard&utm_medium=goprodashboard&utm_campaign=go_pro_admin&utm_content=addons"><?php _e("View all features","pie-register") ?></a>
                    </div> -->

                </div>
            </div>			
			<?php } ?>
        </div>
    </div>
</div>