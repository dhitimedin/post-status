<?php
/**
 * File contains methods used to generate notice
 */

/**
 * Reads all the messages that are due and calls up method to display them.
 */

function display_notice() {    
    $dataset = read_status_table( array( 'message' , 'msgtype' , 'duedate' , 'enddate' ) );

    foreach($dataset as $key => $row)
    {
        $today_date = date("Y-m-d");
        if( ( $row["duedate"] <= $today_date ) && ( $row["enddate"] >= $today_date ) )
        {
            my_notice($row["message"], $row["msgtype"]);
        }
    }
    
}
add_action( 'admin_notices', 'display_notice');


/**
 * Displays notices as per their categories 
 * (Success, Warning, Information, and Error).
 * @param type $message
 * @param type $type
 */

function my_notice($message, $type) {
    switch($type){
        case "success":
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( $message, 'my_plugin_textdomain' ); ?></p>
    </div>
    <?php
            break;
        case "warning":
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( $message, 'my_plugin_textdomain' ); ?></p>
    </div>
    <?php
            break;            
        case "info":
    ?>
    <div class="notice notice-info is-dismissible">
        <p><?php _e( $message, 'my_plugin_textdomain' ); ?></p>
    </div>
    <?php
            break;            
        case "error":
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( $message, 'my_plugin_textdomain' ); ?></p>
    </div>
    <?php 
    }
}