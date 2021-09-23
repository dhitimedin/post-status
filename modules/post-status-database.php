<?php
/**
 * All methods that interacts with the database are mentioned here
 */


/**
 * Create wp_status_message table when the plugin is registered or a new site is created.
 * @global type $wpdb
 */

function create_status_message_table(){
    global $wpdb;
    $table_name = 'status_message';
    $wp_track_table = $wpdb->prefix . $table_name;
    
    // Check to see if the table exists already, if not, then create it.
   if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
   {
        // Create db sql for table.
        $sql = "CREATE TABLE $wp_track_table (
            id int NOT NULL AUTO_INCREMENT,
            message varchar(30) NOT NULL,
            msgtype varchar(30) NOT NULL,
            duedate DATE,
            enddate DATE,
            supadmin int NOT NULL DEFAULT '0',
            PRIMARY KEY(id)
            );";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        maybe_create_table( $wp_track_table, $sql );
    }    
    
}


/**
 * Drop the status message table
 * @global type $wpdb
 */

function drop_status_message_table(){

    global $wpdb;
    
     $table_name = $wpdb->prefix . "status_message";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     //delete_option("my_plugin_db_version");    
}

//register_uninstall_hook( __FILE__, 'drop_status_message_table' );


/**
 * Function to read the table and send the result of the select query
 * 
 * @global type $wpdb
 * @param type array of column names (as string)
 * @return array of result values
 */
function read_status_table($fields = array('*')){
    global $wpdb;
    $table_name = $wpdb->prefix . "status_message";
    $query_fields = implode(', ', $fields);
    $dataset = [];
    //echo '<h1>' . $query_fields . '</h1>';
    if(is_super_admin()){
        $dataset = $wpdb->get_results(
                    "SELECT $query_fields FROM `$table_name`", 
                    ARRAY_A
                );
    }
    else {
        $dataset = $wpdb->get_results(
                    $wpdb->prepare( "SELECT $query_fields FROM `$table_name` WHERE supeadmin = %d", 0 ), 
                    ARRAY_A
                );        
    }
    return $dataset;
}

/**
 * Query the blog table to get list of all the sites.
 * @global type $wpdb
 * @return type array
 */

function read_blog_id(){
    global $wpdb;
    $dataset = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    return $dataset;    
}


