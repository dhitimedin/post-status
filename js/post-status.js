/**
 * File post-status.js.
 *
 * Handles AJAX Form Submission
 */

jQuery( document ).ready(function() {
    
    // Handler for storing the status message and displaying that stored data.
    jQuery( function() {
        jQuery( '#status-message' ).submit( function( event ) {
            alert( 'are you sure you want to save this data' );
            event.preventDefault();
            var ps_form = jQuery( this );
            var ps_data = {};
            var form_data = jQuery( this ).serializeArray();
            jQuery.each( form_data, function( i, field ) {
                ps_data[ field.name ] = field.value;
            });            
            form_data.push( { 'name': 'action', 'value': 'store_status'},
                    { 'name': 'security', 'value': poststatus_ajax.check_nonce }
                    );
            
            // Do js validation. 
            var result = jQuery.ajax({
                url: poststatus_ajax.ajax_url,
                type: ps_form.attr( 'method' ),
                data: form_data,
                dataType: 'JSON',
                success: function( response ){
                    jQuery( '#display-res' ).html( '<div class=\'success\'><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-delete-resp' ).html( '' );
                    console.log( response.data );
                    jQuery( '#stmessage-table' ).append(
                            '<tr class=\'row-status\' id=' + response.data.id + '>' + 
                                '<td class=\'status-td\'><input type=\'checkbox\' /></td>' +
                                '<td class=\'status-td\'>' + ps_data.message + '</td>' +
                                '<td class=\'status-td\'>' + ps_data.msgtype + '</td>' +
                                '<td class=\'status-td\'>' + ps_data.duedate + '</td>' +
                                '<td class=\'status-td\'>' + ps_data.enddate + '</td>' +
                            '</tr>'
                            );
                },
                error: function(response){
                    console.log(response); // For debugging.
                    jQuery( '#display-res' ).html( '<div class=\'fail\'><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-delete-resp' ).html( '' );
                }                
            });
        });

        //Handler for Delete functionality.
        jQuery( '#ps-delete' ).click(function( event ){
            alert( 'are you sure you want to delete the item' );
            event.preventDefault();
            var ps_table = jQuery( '#stmessage-table' );
            var ps_data = [];
            var form_data = [];
            ps_table.find( 'tr' ).each(function () {
                var row = jQuery(this);
                if (row.find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
                    ps_data.push( parseInt( row.attr( 'id' ) ) ); 
                }
            });
            
            console.log(ps_data); // For debugging.
            form_data.push( {'name' : 'id' , 'value' : ps_data},
                    { 'name' : 'action', 'value' : 'delete_status' },
                    { 'name' : 'security', 'value' : poststatus_ajax.check_nonce }
                    );
            
            // Do js validation. 
            var result = jQuery.ajax({
                url: poststatus_ajax.ajax_url,
                type: 'GET',
                data: form_data,
                dataType: 'JSON',
                success: function(response){
                    console.log( response.data ); // For debugging.
                    ps_table.find( 'tr' ).each(function() {
                        var row = jQuery( this );
                        
                        if( row.find( 'input[type="checkbox"]' ).is( ':checked' )) {
                            row.remove(); 
                        }
                    });                    

                    jQuery( '#display-delete-resp' ).html( '<div class=\'success\'><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-res' ).html( '' );
                    
                },
                error: function( response ){
                    console.log( response ); // For debugging.
                    jQuery( '#display-delete-resp' ).html( '<div class="fail"><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-res' ).html( '' );
                }                
            });

        }); 
        
        // Need further implementation
        //Handler for Edit functionality.
    /*    jQuery( '#ps-edit' ).click(function( event ){
            alert( 'are you sure you want to edit the item' );
            event.preventDefault();
            var ps_table = jQuery( '#stmessage-table' );
            var ps_data = [];
            var form_data = [];
            ps_table.find( 'tr' ).each( function() {
                var row = jQuery( this );
                if ( row.find('input[type="checkbox"]' ).is( ':checked' ) ) {
                    ps_data.push( parseInt( row.attr( 'id' ) ) ); 
                }
            });
            
            console.log( ps_data ); // For debugging.
            form_data.push( {'name' : 'id', 'value' : ps_data},
                    { 'name' : 'action', 'value' : 'delete_status' },
                    { 'name' : 'security', 'value' : poststatus_ajax.check_nonce }
                    );
            
            // Do js validation. 
            var result = jQuery.ajax({
                url: poststatus_ajax.ajax_url,
                type: 'GET',
                data: form_data,
                dataType: 'JSON',
                success: function( response ){
                    console.log( response.data ); // For debugging.
                    ps_table.find( 'tr' ).each( function() {
                        var row = jQuery( this );
                        
                        if( row.find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
                            row.remove(); 
                        }
                    });                    

                    jQuery( '#display-delete-resp' ).html( '<div class=\'success\'><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-res' ).html( '' );
                    
                },
                error: function( response ){
                    console.log( response ); // For debugging.
                    jQuery( '#display-delete-resp' ).html( '<div class=\'fail\'><p>' + response.data.msg + '</p></div>' );
                    jQuery( '#display-res' ).html( '' );
                }                
            });
        });           */
        
        return false;
        
    });        
    
});
