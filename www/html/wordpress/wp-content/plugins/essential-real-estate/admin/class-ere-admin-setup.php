<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Admin_Setup')) {
    /**
     * Class ERE_Admin_Setup
     */
    class ERE_Admin_Setup
    {
        /**
         * admin_menu
         */
        public function admin_menu()
        {
            add_menu_page(
                esc_html__( 'Essential Real Estate', 'essential-real-estate' ),
                esc_html__('Essential Real Estate', 'essential-real-estate'),
                'manage_options',
                'ere_welcome',
                array($this, 'menu_welcome_page_callback'),
                'dashicons-building',
                2
            );
            add_submenu_page(
                'ere_welcome',
                esc_html__('Welcome', 'essential-real-estate'),
                esc_html__('Welcome', 'essential-real-estate'),
                'manage_options',
                'ere_welcome',
                array($this, 'menu_welcome_page_callback')
            );
            add_submenu_page(
                'ere_welcome',
                esc_html__('Real Estate Options', 'essential-real-estate'),
                esc_html__('Real Estate Options', 'essential-real-estate'),
                'manage_options',
                'admin.php?page=ere_options'
            );
            add_submenu_page(
                'ere_welcome',
                esc_html__('Setup Page', 'essential-real-estate'),
                esc_html__('Setup Page', 'essential-real-estate'),
                'manage_options',
                'ere_setup',
                array($this, 'setup_page')
            );
            if ( apply_filters( 'ere_show_addons_page', true ) )
                add_submenu_page(
                    'ere_welcome',
                    esc_html__( 'ERE Add-ons', 'essential-real-estate' ),
                    esc_html__( 'Add-ons', 'essential-real-estate' ) ,
                    'manage_options',
                    'ere_addons',
                    array( $this, 'addons_page' ) );
        }

        /**
         * Get list addons
         */
        public function addons_page() {
            if ( isset( $_GET['page'] ) && 'ere_addons' === $_GET['page'] ) {
                wp_redirect( 'http://plugins.g5plus.net/ere/add-ons/' );
            }
        }

        public function menu_welcome_page_callback()
        {
            ?>
            <div class="wrap about-wrap">
                <h1><?php echo sprintf( __( 'Welcome to Essential Real Estate %s', 'essential-real-estate' ), ERE_PLUGIN_VER) ?></h1>
                <div class="about-text">
                    <?php esc_html_e( 'Essential Real Estate is a latest plugins of Real Estate you want. Completely all features, easy customize and override layout, functions. Supported global payment, build market, single, list propery, single agent...etc. All fields are defined dynamic, they will help you can build any kind of Real Estate website.', 'essential-real-estate' ) ?>
                </div>
                <div class="ere-badge">
                    <img src="<?php echo ERE_PLUGIN_URL.'admin/assets/images/logo.png'; ?>" title="<?php esc_attr_e('Logo', 'essential-real-estate' ) ?>">
                </div>
                <a href="<?php echo admin_url( 'admin.php?page=ere_setup' )?>"
                   class="button button-primary"><?php esc_html_e( 'Setup page', 'essential-real-estate' ) ?></a>
                <a href="<?php echo admin_url( 'admin.php?page=ere_options' ) ?>"
                   class="button button-secondary"><?php esc_html_e( 'Settings', 'essential-real-estate' ) ?></a>
                <div style="margin-top: 50px;">
                    <iframe width="420" height="315"
                            src="https://www.youtube.com/embed/73Cahw3I7JM">
                    </iframe>
                </div>
            </div>
            <?php
        }

        /**
         * Redirect the setup page on first activation
         */
        public function redirect()
        {
            // Bail if no activation redirect transient is set
            if (!get_transient('_ere_activation_redirect')) {
                return;
            }

            if (!current_user_can('manage_options')) {
                return;
            }

            // Delete the redirect transient
            delete_transient('_ere_activation_redirect');

            // Bail if activating from network, or bulk, or within an iFrame
            if (is_network_admin() || isset($_GET['activate-multi']) || defined('IFRAME_REQUEST')) {
                return;
            }

            if ((isset($_GET['action']) && 'upgrade-plugin' == $_GET['action']) && (isset($_GET['plugin']) && strstr($_GET['plugin'], 'essential-real-estate.php'))) {
                return;
            }

            wp_redirect(admin_url('admin.php?page=ere_setup'));
            exit;
        }

        /**
         * Create page on first activation
         * @param $title
         * @param $content
         * @param $option
         */
        private function create_page($title, $content, $option)
        {
            $page_data = array(
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_name' => sanitize_title($title),
                'post_title' => $title,
                'post_content' => $content,
                'post_parent' => 0,
                'comment_status' => 'closed'
            );
            $page_id = wp_insert_post($page_data);
            if ($option) {
                $config = get_option(ERE_OPTIONS_NAME);
                $config[$option] = $page_id;
                update_option(ERE_OPTIONS_NAME, $config);
            }
        }

        /**
         * Output page setup
         */
        public function setup_page()
        {
	        $pages_to_create = ERE_Admin_Setup::get_page_setup_config();
            $step = !empty($_GET['step']) ? absint(wp_unslash($_GET['step'])) : 1;
            if (3 === $step && !empty($_POST)) {
                $create_pages = isset($_POST['ere-create-page']) ? ere_clean(wp_unslash($_POST['ere-create-page']))  : array();
                $page_titles = isset($_POST['ere-page-title']) ? ere_clean(wp_unslash($_POST['ere-page-title']))  : array();
                foreach ($pages_to_create as $page => $v) {
                    if (!isset($create_pages[$page]) || empty($page_titles[$page])) {
                        continue;
                    }
                    $content = isset($v['content']) ? $v['content'] : '';
                    $this->create_page(sanitize_text_field($page_titles[$page]), $content, 'ere_' . $page . '_page_id');
                }
            }
            ?>
            <div class="ere-setup-wrap">
                <h2><?php esc_html_e('Essential Real Estate Setup', 'essential-real-estate'); ?></h2>
                <ul class="ere-setup-steps">
                    <li class="<?php if ($step === 1) echo 'ere-setup-active-step'; ?>"><?php esc_html_e('1. Introduction', 'essential-real-estate'); ?></li>
                    <li class="<?php if ($step === 2) echo 'ere-setup-active-step'; ?>"><?php esc_html_e('2. Page Setup', 'essential-real-estate'); ?></li>
                    <li class="<?php if ($step === 3) echo 'ere-setup-active-step'; ?>"><?php esc_html_e('3. Done', 'essential-real-estate'); ?></li>
                </ul>

                <?php if (1 === $step) : ?>

                    <h3><?php esc_html_e('Setup Wizard Introduction', 'essential-real-estate'); ?></h3>
                    <p><?php _e('Thanks for installing <em>Essential-Real-Estate</em>!', 'essential-real-estate'); ?></p>
                    <p><?php esc_html_e('This setup wizard will help you get started by creating the pages for property submission, property management, profile management, listing property, listing agent, packages, payment, login, register...', 'essential-real-estate'); ?></p>
                    <p><?php printf(__('If you want to skip the wizard and setup the pages and shortcodes yourself manually, the process is still relatively simple. Refer to the %sdocumentation%s for help.', 'essential-real-estate'), '<a href="http://document.g5plus.net/essential-real-estate">', '</a>'); ?></p>

                    <p class="submit">
                        <a href="<?php echo esc_url(add_query_arg('step', 2)); ?>"
                           class="button button-primary"><?php esc_html_e('Continue to page setup', 'essential-real-estate'); ?></a>
                        <a href="<?php echo esc_url(add_query_arg('skip-ere-setup', 1, admin_url('admin.php?page=ere_setup&step=3'))); ?>"
                           class="button"><?php esc_html_e('Skip setup. I will setup the plugin manually (Not Recommended)', 'essential-real-estate'); ?></a>
                    </p>

                <?php endif; ?>
                <?php if (2 === $step) : ?>

                    <h3><?php esc_html_e('Page Setup', 'essential-real-estate'); ?></h3>

                    <p><?php printf(__('<em>essential-real-estate</em> includes %1$sshortcodes%2$s which can be used within your %3$spages%2$s to output content. These can be created for you below. For more information on the essential-real-estate shortcodes view the %4$sshortcode documentation%2$s.', 'essential-real-estate'), '<a href="https://codex.wordpress.org/shortcode" title="What is a shortcode?" target="_blank" class="help-page-link">', '</a>', '<a href="http://codex.wordpress.org/Pages" target="_blank" class="help-page-link">', '<a href="http://document.g5plus.net/essential-real-estate" target="_blank" class="help-page-link">'); ?></p>

                    <form action="<?php echo esc_url(add_query_arg('step', 3)); ?>" method="post">
                        <table class="ere-shortcodes widefat">
                            <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th><?php esc_html_e('Page Title', 'essential-real-estate'); ?></th>
                                <th><?php esc_html_e('Page Description', 'essential-real-estate'); ?></th>
                                <th><?php esc_html_e('Content Shortcode', 'essential-real-estate'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pages_to_create as $k => $v): ?>
                                <?php
	                                $title = isset($v['title']) ? $v['title'] : '';
	                                $desc = isset($v['desc']) ? $v['desc'] : '';
	                                $content = isset($v['content']) ? $v['content'] : '';
	                            ?>
	                            <tr>
		                            <td><input type="checkbox" checked="checked" name="ere-create-page[<?php echo esc_attr($k)?>]"/>
		                            </td>
		                            <td><input type="text" value="<?php echo esc_attr($title); ?>" name="ere-page-title[<?php echo esc_attr($k)?>]"/></td>
		                            <td>
			                            <?php if (!empty($desc)): ?>
			                                <p><?php echo esc_html($desc) ?></p>
	                                    <?php endif; ?>
		                            </td>
		                            <td>
			                            <?php if (!empty($content)): ?>
				                            <code><?php echo esc_html($v['content'])?></code>
	                                    <?php endif; ?>
		                            </td>
	                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">
                                    <input type="submit" class="button button-primary" value="<?php esc_html_e('Create selected pages', 'essential-real-estate'); ?>"/>
                                    <a href="<?php echo esc_url(add_query_arg('step', 3)); ?>"
                                       class="button"><?php esc_html_e('Skip this step', 'essential-real-estate'); ?></a>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </form>

                <?php endif; ?>
                <?php if (3 === $step) : ?>

                    <h3><?php esc_html_e('All Done!', 'essential-real-estate'); ?></h3>

                    <p><?php esc_html_e('Looks like you\'re all set to start using the plugin. In case you\'re wondering where to go next:', 'essential-real-estate'); ?></p>

                    <ul class="ere-next-steps">
                        <li>
                            <a href="<?php echo admin_url('themes.php?page=ere_options'); ?>"><?php esc_html_e('Real Estate plugin settings', 'essential-real-estate'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo admin_url('post-new.php?post_type=property'); ?>"><?php esc_html_e('Add a property the back-end', 'essential-real-estate'); ?></a>
                        </li>
	                    <?php foreach ($pages_to_create as $k => $v): ?>
	                        <?php if ($permalink = ere_get_permalink($k)): ?>
		                        <li><a href="<?php echo esc_url($permalink); ?>"><?php echo ere_get_page_title($k)?></a></li>
		                    <?php endif; ?>
	                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
        }


	    public static function get_page_setup_config() {
		    $config =  apply_filters('ere_page_setup_config',array(
			    'submit_property' => array(
				    'title' => 	_x('New Property', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to post property to your website via the front-end.If you do not want to accept submissions from users in this way (for example you just want to post property from the admin dashboard) you can skip creating this page.','essential-real-estate'),
				    'content' => '[ere_submit_property]',
				    'priority' => 10,
			    ),
			    'my_properties' => array(
				    'title' => _x('My Properties', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to manage and edit their own property via the front-end.', 'essential-real-estate'),
				    'content' => '[ere_my_properties]',
				    'priority' => 20,
			    ),
			    'my_profile' => array(
				    'title' => _x('My Profile', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to view and edit their own profile via the front-end.', 'essential-real-estate'),
				    'content' => '[ere_profile]',
				    'priority' => 30,
			    ),
			    'my_invoices' => array(
				    'title' => _x('My Invoices', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to view their own invoice via the front-end.', 'essential-real-estate'),
				    'content' => '[ere_my_invoices]',
				    'priority' => 40,
			    ),
			    'my_favorites' =>  array(
				    'title' => _x('My Favorites', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to view their own favorites via the front-end.', 'essential-real-estate'),
				    'content' => '[ere_my_favorites]',
				    'priority' => 50,
			    ),
			    'my_save_search' =>  array(
				    'title' => _x('My Saved Searches', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This page allows users to view their own "saved searches" via the front-end.', 'essential-real-estate'),
				    'content' => '[ere_my_save_search]',
				    'priority' => 60,
			    ),
			    'packages' => array(
				    'title' => _x('Packages', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is register page.', 'essential-real-estate'),
				    'content' => '[ere_package]',
				    'priority' => 70,
			    ),
			    'payment' => array(
				    'title' => _x('Payment Invoice', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is payment page.', 'essential-real-estate'),
				    'content' => '[ere_payment]',
				    'priority' => 80,
			    ),
			    'payment_completed' => array(
				    'title' => _x('Payment Completed', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is payment completed page.', 'essential-real-estate'),
				    'content' => '[ere_payment_completed]',
				    'priority' => 90,
			    ),
			    'login' => array(
				    'title' => _x('Login', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is login page.', 'essential-real-estate'),
				    'content' =>  '[ere_login]',
				    'priority' => 100,
			    ),
			    'register' => array(
				    'title' => _x('Register', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is register page.', 'essential-real-estate'),
				    'content' =>  '[ere_register]',
				    'priority' => 110,
			    ),
			    'compare' => array(
				    'title' => _x('Compare', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is compare page.', 'essential-real-estate'),
				    'content' =>  '[ere_compare]',
				    'priority' => 120,
			    ),
			    'advanced_search' =>  array(
				    'title' => _x('Advanced Search', 'Default page title (wizard)', 'essential-real-estate'),
				    'desc' => esc_html__('This is advanced search page.', 'essential-real-estate'),
				    'content' =>  '[ere_advanced_search]',
				    'priority' => 130,
			    ),
		    ));
		    uasort( $config, 'ere_sort_by_order_callback' );
		    return $config;
	    }
    }
}