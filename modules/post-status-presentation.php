<?php
/**
 * All methods that are generating html are mentioned here
 */


/**
 * Render the Settings page where one can input new status message.
 * Calls the method that lists all stored status messages. 
 *  
 */

function render_page(){
?>  
    <div class='wrap'>  
        <h1>Add Status Message</h1>  
        <div class="main-content">  
          
    <!-- You only need this form and the form-basic.css --> 
            <div class="form-title-row">  
                <h2>Enter status message along with other details</h2>  
            </div>      
            <form class="form-basic" id="status-message" method="post" action="">  

                <label for="message">Status Message</label>
                <input type="text" id="message" name="message">
                <br />

                <label for="msgtype">Status Message Type</label> 
                <select id="msgtype" type="text" id="msgtype" name="msgtype">
                    <option value="success">Notice Success</option>
                    <option value="warning">Notice Warning</option>
                    <option value="info">Notice Info</option>
                    <option value="error">Notice Error</option>
                </select>
                <br />

                <label for="">Date to Publish</label>  
                <input type="date" id="duedate" name="duedate" />
                <br />

                <label for="">Date to Unpublish</label> 
                <input type="date" id="enddate" name="enddate" /> 
                <br />

                <input type="submit" class="button-primary" value="Submit" />
                <br />
            </form>
            <div id="display-res"></div>

        </div>  

    </div>  
<?php
    if( is_plugin_active( 'post-status/post-status.php' ) )
    {
        list_messages();
    }
}


/**
 * Lists all the stored status messages.
 */

function list_messages(){
    $dataset = read_status_table( array( 'id' , 'message' , 'msgtype' , 'duedate' , 'enddate' ) );
?>   
    
    <h2 class="header2-status">Stored Status Messages</h2>
    <table class="table-status" id="stmessage-table">
        <tr class="row-status">
            <th>    &nbsp;   </th>
            <th>Message</th>
            <th>Message type</th>
            <th>Due Date</th>
            <th>End Date</th>
        </tr>
<?php       
    foreach($dataset as $key => $row)
    {
?>      
        <tr class="row-status" id="<?php echo $row["id"] ?>">
            <td class="status-td"><input type="checkbox" />    &nbsp;   </td>
            <td class="status-td"><?php echo $row["message"] ?></td>
            <td class="status-td"><?php echo $row["msgtype"] ?></td>
            <td class="status-td"><?php echo $row["duedate"] ?></td>
            <td class="status-td"><?php echo $row["enddate"] ?></td>
        </tr>
<?php        
    }
?>    
        </table>
        <div>
            <br /><button type="button" class="button-secondary" id="ps-delete"> Delete </button>
            <!-- Removing this feature as of now. Need further implementation.
        <!--    <button type="button" class="button-secondary" id="ps-edit"> Edit </button>
            <button type="button" class="button-secondary" id="ps-edit-submit" style="display: none;"> Submit </button> -->
        </div>
        <div id="display-delete-resp"></div>
<?php    
}

