<?php

require( plugin_dir_path( __FILE__ ) . 'modules/post-status-database.php');

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/**
 * Function to check for multisite or a single site and drop tables accordingly.
 * @global type $wpdb
 */

function status_message_uninstall(){
    
    // check if it is a multisite network
    if (is_multisite()) {
        global $wpdb;
        $blog_sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";
        $blog_ids = $wpdb->get_col( $blog_sql );
        
        //For each of the sites drop the table. 
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            // create tables for each site
            drop_status_message_table();
            restore_current_blog();
        }
    }
    else
    {
        // activated on a single site
        drop_status_message_table();
    }      
}

//Call the function to drop the tables.
status_message_uninstall();
