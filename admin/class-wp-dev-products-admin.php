<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/admin
 * @author     Devang <pujaradevang@gmail.com>
 */
class Wp_Dev_Products_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Dev_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Dev_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-dev-products-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Dev_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Dev_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-dev-products-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
	    /**
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     * Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     * add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	     *
	     * @link https://codex.wordpress.org/Function_Reference/add_options_page
	     */
	   /* add_submenu_page( 'plugins.php', 'Target Groups Options', 'target-groups-options', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
	    );*/
	    add_options_page('Target Groups Settings','Target Groups Settings','manage_options','target_group_settings',array($this,
		'display_plugin_setup_page')
		);
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
	   $setting_slug= 'target_group_settings';
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $setting_slug ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
	    include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );
	}

	/**
	 * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
	 * @param  mixed $input as field form settings form
	 * @return mixed as validated fields
	 */
	public function validate($input) {
	    $valid = array();
	  //  $valid['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
	   // $valid['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
	    $valid['target_groups_select'] = ( isset($input['target_groups_select'] ) && ! empty( $input['target_groups_select'] ) ) ? esc_attr($input['target_groups_select']) : '';
	    return $valid;
	}

	public function options_update() {
	    register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}

	// Save custom fields
	public function save_meta_options() {
	    if ( ! current_user_can( 'edit_posts' ) ) return;
	    global $post;
	    if( !is_object($post) ) 
        return;
	    update_post_meta($post->ID, "product-ratings", $_POST["product-ratings"]);
	}
	/* Create a meta box for our custom fields */
	public function rerender_meta_options() {
	    add_meta_box("rating-meta", "Product Rating", array($this, "display_rating_options"), "wp-products", "normal", "low");
	}
	// Display meta box and custom fields
	public function display_rating_options() {
	    global $post;
	    ?>
	    <label><?php _e( 'Product Rating:', $this->plugin_name ); ?></label>
	     <select name="product-ratings">
                <?php 
                    $option_values = array(1, 2, 3, 4, 5);

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($post->ID, "product-ratings", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>  
	    <?php
	}
	// Code to set default custom taxonomy which set in plugin setting page
	public function save_wp_products_taxonomy( $post_id, $post, $update ) {

		$slug = 'wp-products'; //Slug of CPT

	    // If this isn't a 'book' post, don't update it.
	    if ( $slug != $post->post_type ) {
	        return;
	    }
	    $terms = wp_get_post_terms( $post_id, 'target_groups' );	  
	    if(empty($terms)){
	    	$options = get_option($this->plugin_name); 
        
	        $target_groups_term_select = ( isset( $options['target_groups_select'] ) && ! empty( $options['target_groups_select'] ) ) ? esc_attr( $options['target_groups_select'] ) : '';
	        $target_groups_term_select = (int)$target_groups_term_select;
		    
		    wp_set_object_terms( get_the_ID(), $target_groups_term_select, 'target_groups' );
	    }	    
	}

	public function wp_dev_products_custom_widget(){
		register_widget( 'Wp_Dev_Products_Widget' );
	}
}
