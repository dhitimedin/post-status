<?php
/**
 * All methods that are invoked by AJAX call are mentioned here.
 */


/**
 * Store Status Message Data that comes from the form.
 * @global type $wpdb
 */

function store_status(){
    check_ajax_referer( 'ps-nonce', 'security' );
    global $wpdb;
    $is_super_admin_flag = 0;
    $table_name = $wpdb->prefix . 'status_message';
    $message = sanitize_text_field( trim( $_POST['message'] ) );
    $msgtype = sanitize_text_field( trim( $_POST['msgtype']) );
    $duedate = date( 'Y-m-d', strtotime( $_POST['duedate']) );
    $enddate = date( 'Y-m-d', strtotime( $_POST['enddate']) );
    if( is_super_admin() )
    {
        $is_super_admin_flag = 1;
    }
    /*$data = array(
        'message' => sanitize_text_field( trim( $_POST['message'] ) ),
        'msgtype' => sanitize_text_field( trim( $_POST['msgtype']) ),
        'duedate'    => date( 'Y-m-d', strtotime( $_POST['duedate']) ),
        'enddate'    => date( 'Y-m-d', strtotime( $_POST['enddate']) ),
    );
    $status_data_types = array(
        '%s',
        '%s',
        '%s',
        '%s'
    );    */

    $sql = $wpdb->prepare(
            "INSERT INTO `$table_name` (message, msgtype, duedate, enddate, supadmin) values ( %s, %s, %s, %s, %d )", 
            $message, $msgtype, $duedate, $enddate, $is_super_admin_flag );
    $success = $wpdb->query($sql);

    $id = $wpdb->insert_id;  
    if($success){
        wp_send_json_success( array( 
            'msg' => 'Your data has been successfully saved',
            'id' => $id,
        ), 200 );        
    }
    else {
        wp_send_json_error( array( 
            'msg' => 'your data could not be stored. Please enter valid input.', 
        ), 500 );          
    }
}

/**
 * Deletes status message from the database  
 * @global type $wpdb
 */

function delete_status(){
    check_ajax_referer( 'ps-nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . 'status_message';
    if( isset($_GET['id']) )
    {
        $ids = $_GET['id'];

        try{

            $value = $wpdb->query( "DELETE FROM $table_name WHERE ID IN($ids)" );
        }
        catch(Exception $e)
        {
            wp_send_json_error( array( 
                'msg' => "Encountered an exception", 
            ), 500 );             
        }

        wp_send_json_success( array( 
            'msg' => "Successfully Deleted $value rows",
        ), 200 ); 
    }
    
            wp_send_json_error( array( 
                'msg' => "You haven't selected any row", 
            ), 403 );     
    
}


/**
 * Edit status message
 * @global type $wpdb
 */

//Need further implementation
/*
function edit_status(){
    check_ajax_referer( 'ps-nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "status_message";

    wp_send_json_success( array( 
        'msg' => "Successfully Updated rows",
    ), 200 );    
    
}*/ 