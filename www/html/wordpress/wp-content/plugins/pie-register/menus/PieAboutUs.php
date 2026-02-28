<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 
	$action =	"";
    $active	= 'class="selected"';
	if(isset($_GET['tab']))
        $action	= sanitize_key($_GET['tab']);
    
    $can_install_plugins = true;
    if ( ! current_user_can( 'install_plugins' ) ) {
        $can_install_plugins = false;
    }

    $images_url = PIEREG_PLUGIN_URL . 'assets/images/about-us/';
    $all_plugins = get_plugins();

    $genetech_products = array(

        'pie-forms-for-wp/pie-forms-for-wp.php' => array(
            'icon'  => $images_url . 'pieforms-for-wp.jpg',
            'name'  => esc_html__( 'Pie Forms', 'pie-register' ),
            'desc'  => esc_html__( 'Your custom Drag and Drop Form Builder with a user-friendly interface, built-in ready to use templates, and various Form Field options to Create Advanced Forms without a single line of Code!', 'pie-register' ),
            'wporg' => 'https://wordpress.org/plugins/pie-forms-for-wp/',
            'url'   => 'https://downloads.wordpress.org/plugin/pie-forms-for-wp.zip',
        ),

        'vc-addons-by-bit14/bit14-vc-addons.php' => array(
            'icon'  => $images_url . 'vc-addons-by-bit14.jpg',
            'name'  => esc_html__( 'PB Add-ons for WP Bakery', 'pie-register' ),
            'desc'  => esc_html__( 'Build your website with premium quality All-in-One Web elements for WPBakery Page Builder.', 'pie-register' ),
            'wporg' => 'https://wordpress.org/plugins/vc-addons-by-bit14/',
            'url'   => 'https://downloads.wordpress.org/plugin/vc-addons-by-bit14.zip',
        ),
    );
	?>

<div id="container"  class="pieregister-admin aboutus-page-admin">
    <div class="aboutus-page">
        <div class="aboutus-header">
            <img src="<?php echo esc_url(plugins_url("assets/images/about-us/pie-register-logo.png", dirname(__FILE__) )); ?>" alt="Pie Register Logo">
        </div>
    </div>
        
        <ul class="aboutus-menu-tabs">
            <li <?php echo ($action != "addons") ? $active :""; ?>>
                <a href="admin.php?page=pie-about-us"><?php _e("About Us","pie-register") ?></a>
            </li>
        </ul>
        <div class="pane">
        	<?php if( sanitize_key($_GET['page']) == 'pie-about-us' ) { ?> 
            	<div id="tab2" class="tab-content">
                <div class="addons-container-section">
                    <div class="content-row">
                        <div class="about-content">
                            <h3 class="welcome-to-pr">Welcome to Pie Register, a WordPress Registration Plugin that will help you create custom registration forms in minutes. Zero coding, no hassle!</h3>
                            <p class="about-us-p">With Pie Register’s simple Drag and Drop form builder, you can design custom Login & Registration forms for your WordPress website. Build simple to the most robust forms, and registration flows using the various form fields. Customize the registration process with amazing add-ons and easy-to-use features to make your website exclusive, spam-free, and secure.</p>

                            <h3 class="welcome-to-pr">Resourceful links:</h3>
                            <ul class="resourceful-links">
                                <li><a href="https://pieregister.com/docs-category/getting-started/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">Getting Started</a></li>
                                <li><a href="https://pieregister.com/docs-category/addons/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">Add-ons</a></li>
                                <li><a href="https://pieregister.com/docs-category/features/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">Features</a></li>
                                <li><a href="https://pieregister.com/docs-category/shortcuts/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">Shortcuts</a></li>
                                <li><a href="https://pieregister.com/docs-category/how-to-articles/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">How-to Articles</a></li>
                            </ul>

                            <p class="about-us-p genetech-resource">Pie Register is a product of <a class="red-anchor" href="https://www.genetechsolutions.com/?utm_source=PRplugindashboard&utm_medium=prabouttab&utm_campaign=Genetech" target="_blank" rel="noopener noreferrer">Genetech Solutions</a>.</p>
                            <p class="about-us-p">Other products by the Team include:</p>
                            <p class="about-us-p"><a class="red-anchor" href="https://pieforms.com/?utm_source=PRplugindashboard&utm_medium=prabouttab&utm_campaign=pieformsfrompr" target="_blank" rel="noopener noreferrer">Pie Forms</a>, the Easiest Drag and Drop WordPress Form Builder Plugin.</p>
                            <p class="about-us-p"><a class="red-anchor" href="https://pagebuilderaddons.com/?utm_source=PRplugindashboard&utm_medium=prabouttab&utm_campaign=pbfrompr" target="_blank" rel="noopener noreferrer">PB Add-ons for WP Bakery</a>, a collection of free and premium add-ons to build your website using WP Bakery.</p>
                        </div>
                        <div class="about-links">
                            <div class="about-pr-video">
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/yjyPZ-E_fqg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                            <div class="about-pr-docs">
                                <a href="https://pieregister.com/documentation/how-to-create-your-first-registration-form/?utm_source=plugindashboard&utm_medium=abouttab&utm_campaign=documentlink" target="_blank">
                                    <img src="<?php echo esc_url(plugins_url("assets/images/about-us/create-pieregister-form.png", dirname(__FILE__) )); ?>" alt="Create a Form">    
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>			
						
			<?php } ?>
        </div>
        <div class="pieregister-sib-products">
            <div class="sib-products-container">
                <div class="sib-products">
                    <?php
                foreach ( $genetech_products as $plugin => $details ) :
					$plugin_data = $this->get_aboutus_plugin_data( $plugin, $details, $all_plugins );
                    ?>
                    <div class="sib-product-container">
                        <div class="sib-product">
                            <div class="sib-product-detail">
                                <img src="<?php echo esc_url( $plugin_data['details']['icon'] ); ?>">
								<h5>
									<?php echo esc_html( $plugin_data['details']['name'] ); ?>
								</h5>
								<p>
									<?php echo wp_kses_post( $plugin_data['details']['desc'] ); ?>
								</p>
                            </div>
                            <div class="sib-product-action">
                                <div class="product-status">
                                    <strong>
										<?php
										printf(
										/* translators: %s - addon status label. */
											esc_html__( 'Status: %s', 'pie-register' ),
											'<span class="status-label ' . esc_attr( $plugin_data['status_class'] ) . '">' . wp_kses_post( $plugin_data['status_text'] ) . '</span>'
										);
										?>
									</strong>
                                </div>
                                <div class="product-action">
                                    <?php if ( $can_install_plugins ) { ?>
										<button class="<?php echo esc_attr( $plugin_data['action_class'] ); ?>" data-plugin="<?php echo esc_attr( $plugin_data['plugin_src'] ); ?>" data-type="plugin">
											<?php echo wp_kses_post( $plugin_data['action_text'] ); ?>
										</button>
									<?php } else { ?>
										<a href="<?php echo esc_url( $details['wporg'] ); ?>" target="_blank" rel="noopener noreferrer">
											<?php esc_html_e( 'WordPress.org', 'pie-register' ); ?>
											<span aria-hidden="true" class="dashicons dashicons-external"></span>
										</a>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>