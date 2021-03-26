
jQuery( document ).ready( function() {
	
	jQuery( document ).on( 'click', '.tk-dismiss-notice .notice-dismiss', function() {
		
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {"action": "tk_dismiss_notice",},
            success: function(data){
                window.location.reload(true);
            }
        });    


	})
});