<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Dev_Products
 * @subpackage Wp_Dev_Products/admin/partials
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>WP Dev Products <?php _e('Options', $this->plugin_name); ?></h2>

    <form method="post" name="cleanup_options" action="options.php">
    <?php
        //Grab all options
        $options = get_option($this->plugin_name); 
        
        $target_groups_term_select = ( isset( $options['target_groups_select'] ) && ! empty( $options['target_groups_select'] ) ) ? esc_attr( $options['target_groups_select'] ) : '';
        //$example_text = ( isset( $options['example_text'] ) && ! empty( $options['example_text'] ) ) ? esc_attr( $options['example_text'] ) : 'default';
        //$example_checkbox = ( isset( $options['example_checkbox'] ) && ! empty( $options['example_checkbox'] ) ) ? 1 : 0;
        
        
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);

        $target_groups_terms = get_terms( 'target_groups', array(
		    'hide_empty' => false,
		) );    		
    ?>

    <!-- Select -->
    <fieldset>
        <p><?php _e( 'Default Target Groups', $this->plugin_name ); ?></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Default Target Group', $this->plugin_name ); ?></span>
        </legend>
        <?php if ( ! empty( $target_groups_terms ) && ! is_wp_error( $target_groups_terms ) ){ ?>
        <label for="target_groups_select">
            <select name="<?php echo $this->plugin_name; ?>[target_groups_select]" id="<?php echo $this->plugin_name; ?>-target_groups_select">
            	<option value="">Select default target group</option>   
            	<?php foreach ( $target_groups_terms as $target_groups_term ) { ?>
                <option <?php if ( $target_groups_term_select == $target_groups_term->term_id ) echo 'selected="selected"'; ?> value="<?php echo $target_groups_term->term_id; ?>"><?php echo $target_groups_term->name; ?></option>                
            	<?php } ?>
            </select>
        </label>
    	<?php } ?>
    </fieldset>
    <?php /*
    <!-- Text -->
    <fieldset>
        <p><?php _e( 'Example Text.', $this->plugin_name ); ?></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Example Text', $this->plugin_name ); ?></span>
        </legend>
        <input type="text" class="example_text" id="<?php echo $this->plugin_name; ?>-example_text" name="<?php echo $this->plugin_name; ?>[example_text]" value="<?php if( ! empty( $example_text ) ) echo $example_text; else echo 'default'; ?>"/>
    </fieldset>

    <!-- Checkbox -->
    <fieldset>
        <p><?php _e( 'Example Checkbox.', $this->plugin_name ); ?></p>
        <legend class="example-Checkbox">
            <span><?php _e( 'Example Checkbox', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-example_checkbox">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-example_checkbox" name="<?php echo $this->plugin_name; ?>[example_checkbox]" value="1" <?php checked( $example_checkbox, 1 ); ?> />
            <span><?php esc_attr_e('Example Checkbox', $this->plugin_name); ?></span>
        </label>
    </fieldset>
    */
    ?>

    <?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>
</div>