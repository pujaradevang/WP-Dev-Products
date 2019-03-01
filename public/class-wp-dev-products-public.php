<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/public
 * @author     Devang <pujaradevang@gmail.com>
 */
class Wp_Dev_Products_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-dev-products-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-dev-products-public.js', array( 'jquery' ), $this->version, false );

	}

	
	/**
	 * Override archive template location for custom post type
	 *
	 * If the archive template file not exist in the theme folder, then use  the plugin template.
	 * In this case, file can be overridden inside the [child] theme.
	 *
	 */
	public function get_custom_post_type_archive_template() {
	    global $post;
	    
	    $custom_post_type = 'wp-products';
	    $templates_dir = 'templates';
	    if ( is_post_type_archive( $custom_post_type ) ) {
	        $theme_files = array('archive-' . $custom_post_type . '.php', $this->plugin_name . '/archive-' . $custom_post_type . '.php');
	        $exists_in_theme = locate_template( $theme_files, false );

	        if ( $exists_in_theme != '' ) {
	            // Try to locate in theme first
	        	
	            return $archive_template;
	        } else {
	            // Try to locate in plugin templates folder
	            if ( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/' . $templates_dir . '/archive-' . $custom_post_type . '.php' ) ) {
	                return WP_PLUGIN_DIR . '/' . $this->plugin_name . '/' . $templates_dir . '/archive-' . $custom_post_type . '.php';
	            } elseif ( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php' ) ) {
	                // Try to locate in plugin base folder
	                return WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php';
	            } else {
	                return null;
	            }
	        }
	    }
	    return $archive_template;
	}

	public function wp_dev_products_function( $atts ) {
		
	    $args = shortcode_atts(
	        array(
	            'title'   => '',	            
	        ),
	        $atts
	    );
	   $html = '';
	    $title = ( $atts['title'] != "" ) ? $atts['title'] : 'Related Products';	    

		$html .= '<div class="wp_dev_products_shortcode">';
			// Display widget title if defined

			if ( $title ) {
				$html .= '<h2>'.$title.'</h2>';
			}
			
			
			$options = get_option('wp-dev-products');

			$term = get_term_by('id', $options['target_groups_select'], 'target_groups');
			$target_group = '';
			if(isset($_SESSION['target_group'])) {
				$target_group = $_SESSION["target_group"];
			} else {
				$target_group = $term->slug;						
			}	

			
			if($_GET['target']) {
				$url_term = get_term_by('slug', $_GET['target'], 'target_groups');
				if($url_term) {
					$target_group = $url_term->slug;
					$_SESSION['target_group'] = $target_group;
				} else {
					//$target_group = $term->slug;
					if(isset($_SESSION['target_group'])) {
						$target_group = $_SESSION["target_group"];
					} else {
						$target_group = $term->slug;						
					}					
				}				
			} 
			
			$query = new WP_Query(array(
				'post_type' => 'wp-products',
				'post_status' => 'publish',
				'meta_key' => 'product-ratings',
				'orderby' => 'meta_value_num',
				'order' => 'DESC',
				'tax_query' => array(
				array(
					'taxonomy' => 'target_groups',
					'terms' => $target_group,
					'field' => 'slug',
				)),
			));	

            if ( $query->have_posts() ) {			
				while ($query->have_posts()) {
					$query->the_post();
					$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail', true );
					if(!empty($image_src[0])){
						$img_url = $image_src[0];
					}else{
						$img_url = 'https://via.placeholder.com/350x150';	
					}
					
					$prod_rating = get_post_meta(get_the_ID(), 'product-ratings', true);
					$html .= '<div class="wp-dev-related-products">';
					$html .= '<h2>'.get_the_title().'</h2>';
					$html .= '<img src="'.$img_url.'"/>';
					if($prod_rating == 1){
						$html .= "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 2) {
						$html .= "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 3) {
						$html .= "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 4) {
						$html .= "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 5) {
						$html .= "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>";
					}
										
					$html .= '</div>';
				}
				wp_reset_postdata();
			}
	    return $html;
	}

}