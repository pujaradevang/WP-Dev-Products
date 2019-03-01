<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/includes
 * @author     Devang <pujaradevang@gmail.com>
 */
class Wp_Dev_Products_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		/**
	     * This only required if custom post type has rewrite!
	     */
	    flush_rewrite_rules();
	}

}
