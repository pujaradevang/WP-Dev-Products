<?php

/**
 * Register custom post type
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Portfolio
 * @subpackage Exopite_Portfolio/includes
 */
class Wp_Dev_Products_Post_Types {

    /**
     * Create post types
     */
    public function create_custom_post_type() {

        $labels = array(
            'name' => _x( 'Products', 'Post Type General Name', 'textdomain' ),
            'singular_name' => _x( 'Products', 'Post Type Singular Name', 'textdomain' ),
            'menu_name' => _x( 'Products', 'Admin Menu text', 'textdomain' ),
            'name_admin_bar' => _x( 'Products', 'Add New on Toolbar', 'textdomain' ),
            'archives' => __( 'Products Archives', 'textdomain' ),
            'attributes' => __( 'Products Attributes', 'textdomain' ),
            'parent_item_colon' => __( 'Parent Products:', 'textdomain' ),
            'all_items' => __( 'All Product', 'textdomain' ),
            'add_new_item' => __( 'Add New Products', 'textdomain' ),
            'add_new' => __( 'Add New', 'textdomain' ),
            'new_item' => __( 'New Products', 'textdomain' ),
            'edit_item' => __( 'Edit Products', 'textdomain' ),
            'update_item' => __( 'Update Products', 'textdomain' ),
            'view_item' => __( 'View Products', 'textdomain' ),
            'view_items' => __( 'View Product', 'textdomain' ),
            'search_items' => __( 'Search Products', 'textdomain' ),
            'not_found' => __( 'Not found', 'textdomain' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
            'featured_image' => __( 'Featured Image', 'textdomain' ),
            'set_featured_image' => __( 'Set featured image', 'textdomain' ),
            'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
            'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
            'insert_into_item' => __( 'Insert into Products', 'textdomain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Products', 'textdomain' ),
            'items_list' => __( 'Product list', 'textdomain' ),
            'items_list_navigation' => __( 'Product list navigation', 'textdomain' ),
            'filter_items_list' => __( 'Filter Product list', 'textdomain' ),
        );
        $args = array(
            'label' => __( 'Products', 'textdomain' ),
            'description' => __( '', 'textdomain' ),
            'labels' => $labels,
            'menu_icon' => 'dashicons-category',
            'supports' => array('title', 'thumbnail','editor'),
            'taxonomies' => array('target_groups'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        register_post_type( 'wp-products', $args );
        

        $tax_labels = array(
        'name'              => _x( 'Target Group', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Target Groups', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Target Group', 'textdomain' ),
        'all_items'         => __( 'All Target Group', 'textdomain' ),
        'parent_item'       => __( 'Parent Target Groups', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Target Groups:', 'textdomain' ),
        'edit_item'         => __( 'Edit Target Groups', 'textdomain' ),
        'update_item'       => __( 'Update Target Groups', 'textdomain' ),
        'add_new_item'      => __( 'Add New Target Groups', 'textdomain' ),
        'new_item_name'     => __( 'New Target Groups Name', 'textdomain' ),
        'menu_name'         => __( 'Target Groups', 'textdomain' ),
        );
        $args = array(
            'labels' => $tax_labels,
            'description' => __( '', 'textdomain' ),
            'hierarchical' => true,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_quick_edit' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
        );
        register_taxonomy( 'target_groups', array('wp-products'), $args );
    } 
    
}