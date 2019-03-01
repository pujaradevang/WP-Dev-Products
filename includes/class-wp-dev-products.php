<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/includes
 * @author     Devang <pujaradevang@gmail.com>
 */
class Wp_Dev_Products {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Dev_Products_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-dev-products';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Dev_Products_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Dev_Products_i18n. Defines internationalization functionality.
	 * - Wp_Dev_Products_Admin. Defines all hooks for the admin area.
	 * - Wp_Dev_Products_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dev-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dev-products-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-dev-products-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-dev-products-public.php';

		/**
	     * Custom Post Types
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-dev-products-post_types.php';

	    /**
	     * Custom Widgets
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/class-wp-dev-products-widget.php';

		$this->loader = new Wp_Dev_Products_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Dev_Products_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Dev_Products_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	*/
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Dev_Products_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_post_types = new Wp_Dev_Products_Post_Types();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Save/Update our plugin options
    	$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
    	// Add menu item
    	$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		
    	//Custom post type function
		$this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999 );
		// Custom Widget function
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'wp_dev_products_custom_widget' );

	    // Add Settings link to the plugin
    	$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

    	$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );    	

    	/**
	    * Add metabox and register custom fields for star rating	     
     	*/
	    $this->loader->add_action( 'admin_init', $plugin_admin, 'rerender_meta_options' );
	    $this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_options' );
	    // default custom taxonomy set on post update
	    $this->loader->add_action( 'save_post', $plugin_admin, 'save_wp_products_taxonomy', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Dev_Products_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'archive_template', $plugin_public, 'get_custom_post_type_archive_template' );

		/**
	     * Register shortcode via loader
	     *
	     * Use: [short-code-name args]
	     *
	     * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/262
	     */
	    $this->loader->add_shortcode( "wp-products", $plugin_public, "wp_dev_products_function", $priority = 10, $accepted_args = 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Dev_Products_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
