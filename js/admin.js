jQuery(document).ready(function(){
		
	jQuery('.add_google_font').click(function(){
		var action = jQuery(this);
		var google_font_name = jQuery('#font').val();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "tk_google_fonts_add_font", "google_font_name": google_font_name},
			success: function(data){
				window.location.reload(true);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
	});
 	jQuery('.dele_form').click(function(){

 		var google_font_name = jQuery(this).attr('id');

		var action = jQuery(this); 
		if (confirm('Delete Permanently'))
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "tk_google_fonts_delete_font", "google_font_name": google_font_name},
				success: function(data){
					window.location.reload(true);
				}
			});
		
		return false;
 	});
		

    jQuery('#myTxt').keyup(function(){
        jQuery('.add_text').html(jQuery(this).val());
    });


});
    
jQuery(function(){


	jQuery('#font').fontselect().change(function(){

		// replace + signs with spaces for css
		var font = jQuery(this).val().replace(/\+/g, ' ');
  
		// split font into family and weight
		font = font.split(':');
  
		// set family on paragraphs 
		jQuery('#google_fonts_selecter textarea').css('font-family', font[0]);
		jQuery('#google_fonts_selecter h2').css('font-family', font[0]);
		jQuery('#google_fonts_selecter h3').css('font-family', font[0]);
		jQuery('#google_fonts_selecter p').css('font-family', font[0]);
		
	});
	
});