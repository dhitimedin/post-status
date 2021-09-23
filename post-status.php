<?php
/*
* Plugin Name: Post Status
* Description: A simple non bloated plugin to set and activate status messages.
* Author: NishitKumar
* Version: 1.0
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


register_activation_hook( __FILE__ , 'status_message_activate' );
register_deactivation_hook( __FILE__ , 'status_message_deactivate' );
define( 'MYPLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require( MYPLUGIN_PATH . 'modules/post-status-database.php' );
require( MYPLUGIN_PATH . 'modules/post-status-presentation.php' );
require( MYPLUGIN_PATH . 'modules/post-status-notice.php' );
require( MYPLUGIN_PATH . 'modules/post-status-ajax.php' );


/**
 * Checks the configuration of the Wordpress site and creates databases accordingly.
 * @global type $wpdb
 */

function status_message_activate($network_wide){

    // check if it is a multisite network
    if (is_multisite()) {

        // check if the plugin has been activated on the network or on a single site
        if($network_wide) {

            // Get ids of all sites
            $blog_ids = read_blog_id(); // $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs" ) );
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                // Create tables for each site
                create_status_message_table();
                restore_current_blog();
            }
        }
        else
        {
            
            // Activated on a single site, in a multi-site
            create_status_message_table();

        }
    }
    else
    {
        // Activated on a single site
        create_status_message_table();
    }    
}
 

/**
 * Checks if a new site has been created and creates a new database accordingly.
 * @global type $wpdb
 * @param type $new_site
 */ 

function activate_new_blog($new_site) {
    $network_wide = get_site_option( 'active_sitewide_plugins' );

    if ( array_key_exists('post-status/post-status.php', $network_wide) ) {
	switch_to_blog($new_site->blog_id);
	create_status_message_table();
	restore_current_blog();
    }
}
add_action( 'wp_initialize_site', 'activate_new_blog', 900 );


/**
 * Registering the Menu page. It is under tools with the name of 'Setting'.
 */

function plugin_menu()
{
    $my_plugin_screen_name = add_management_page(
        'Post Status',
        'Settings',
        'manage_options',
        __FILE__,
        'render_page',
        4,
    );
}
add_action('admin_menu', 'plugin_menu');


/**
 * AJAX declarations. 
 */

function plugin_ps_ajax_init(){
        add_action('wp_ajax_nopriv_store_status', 'store_status' );
        add_action('wp_ajax_store_status', 'store_status' ); /* notice green_do_something appended to action name of wp_ajax_ */
        add_action('wp_ajax_nopriv_delete_status', 'delete_status' );
        add_action('wp_ajax_delete_status', 'delete_status' ); 
        add_action('wp_ajax_nopriv_edit_status', 'edit_status' );
        add_action('wp_ajax_edit_status', 'edit_status' );         
}
add_action( 'admin_init', 'plugin_ps_ajax_init' );


/**
 * Register all CSS and Javascript code. Also, register AJAX related variables. 
 */

function plugin_status_enque_styles() {

    $my_current_screen_slug = get_current_screen()->base;
    
    if( stripos($my_current_screen_slug, "post-status" ) ){
        wp_enqueue_script('ps_script', plugin_dir_url(__FILE__) . 'js/post-status.js', array('jquery'));
        wp_enqueue_style( 'handlecss',  plugin_dir_url( __FILE__ ) . 'css/post-status.css' );
        wp_localize_script('ps_script', 'poststatus_ajax', array('ajax_url' => admin_url('admin-ajax.php'), 'check_nonce' => wp_create_nonce('ps-nonce')));
    }

}
add_action( 'admin_enqueue_scripts', 'plugin_status_enque_styles' );


/**
 * Check configuration setting and process deletion of table accordingly.
 * @param type $network_wide
 */

function status_message_deactivate($network_wide){
    
    // check if it is a multisite network
    if (is_multisite()) {

        // check if the plugin has been activated on the network or on a single site
        if ( $network_wide ) {
            
            // get ids of all sites
            $blog_ids = read_blog_id();  // $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs" ) );
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                // create tables for each site
                drop_status_message_table();
                restore_current_blog();
            }
        }
        else
        {
            // activated on a single site, in a multi-site
            drop_status_message_table();

        }
    }
    else
    {
        // activated on a single site
        drop_status_message_table();
    }      
    
}



