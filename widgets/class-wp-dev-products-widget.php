<?php
session_start();
/**
 * Register custom widget
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Portfolio
 * @subpackage Exopite_Portfolio/includes
 */

// The widget class
class Wp_Dev_Products_Widget extends WP_Widget {
	// Main constructor
	public function __construct() {
		parent::__construct(
			'wp_dev_products_widget',
			__( 'Wp Dev Products Widget', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}
	// The widget form (for the backend )
	public function form( $instance ) {
		// Set widget defaults
		$defaults = array(
			'title'    => '',
			'text'     => '',
			'select'   => '',
		);
		
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>	
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Number of Products:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Sort Order', 'text_domain' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
			<?php
			// Your options array
			$options = array(
				''        => __( 'Select Order', 'text_domain' ),
				'asc' => __( 'ASC', 'text_domain' ),				
				'desc' => __( 'DESC', 'text_domain' ),
			);

			// Loop through options and add each one to the select dropdown
			foreach ( $options as $key => $name ) {
				echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $select, $key, false ) . '>'. $name . '</option>';

			} ?>
			</select>
		</p>	


	<?php }
	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['text']     = isset( $new_instance['text'] ) ? wp_strip_all_tags( $new_instance['text'] ) : '';
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';		
		return $instance;
	}
	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
	
		// Check the widget options
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$post_number     = isset( $instance['text'] ) ? $instance['text'] : '5';
		$sort_order   = isset( $instance['select'] ) ? $instance['select'] : 'desc';
		// WordPress core before_widget hook (always include )
		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box wp_dev_products_widget">';
			// Display widget title if defined

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			
			$options = get_option('wp-dev-products');

			$term = get_term_by('id', $options['target_groups_select'], 'target_groups');
			$target_group = '';
			if(isset($_SESSION['target_group'])) {
				$target_group = $_SESSION["target_group"];
			} else {
				$target_group = $term->slug;						
			}	

			$target_get = isset($_GET['target']) ? $_GET['target'] : '';
			if(!empty($target_ge)) {
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
				'order' => $sort_order,
				'posts_per_page' => $post_number,
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
					echo '<div class="wp-dev-related-products">';
					echo '<h2>'.get_the_title().'</h2>';
					echo '<img src="'.$img_url.'"/>';
					if($prod_rating == 1){
						echo "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 2) {
						echo "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 3) {
						echo "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 4) {
						echo "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-empty'></span>";
					}elseif ($prod_rating == 5) {
						echo "<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>&nbsp&nbsp<span class='dashicons dashicons-star-filled'></span>";
					}
					echo '';
					
					echo '</div>';
				}
				wp_reset_postdata();
			}
					echo '</div>';
					// WordPress core after_widget hook (always include )
					echo $after_widget;
				}
			}
// Register the widget
/*function wp_dev_products_custom_widget() {
	register_widget( 'Wp_Dev_Products_Widget' );
}
add_action( 'widgets_init', 'wp_dev_products_custom_widget' );*/