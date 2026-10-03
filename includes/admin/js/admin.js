// The TK Google Fonts Admin Settings Panel 
jQuery(document).ready(function(){
	
	if (typeof(Zenbox) !== "undefined") {
		Zenbox.init({
			dropboxID:   "20204992",
			url:         "https://themekraft.zendesk.com",
			tabTooltip:  "Feedback",
			tabColor:    "black",
			tabPosition: "Left",
			hide_tab: true
		});
	}
		
	jQuery('.add_google_font').click(function(){
		var action = jQuery(this);
		var google_font_name = jQuery('#font').val();
		var new_google_font = jQuery('#new_font').val();
		
		if(new_google_font != ''){
			google_font_name = new_google_font;
		}	
		jQuery.ajax({
			type: 'POST',
			url: ajax_var.url,
			data: {"action": "tk_google_fonts_add_font", "google_font_name": google_font_name, "font_nonce": ajax_var.nonce},
		}).done(function(){
			location.reload();
		}).fail(function(jqXHR){
			const error_msg = (jqXHR && jqXHR.responseText) ? jqXHR.responseText : 'Something went wrong...'
			alert( error_msg );
		});
	});
	
 	jQuery('.dele_form').click(function(){
 		var google_font_name = jQuery(this).attr('id');
		var action = jQuery(this); 

		if (confirm('Delete Permanently'))
			jQuery.ajax({
				type: 'POST',
				url: ajax_var.url,
				data: {"action": "tk_google_fonts_delete_font", "google_font_name": google_font_name, "font_nonce": ajax_var.nonce},
				success: function(data){
					location.reload();				}
			});
		
		return false;
 	});
		
    jQuery('#myTxt').keyup(function(){
        jQuery('.add_text').html(jQuery(this).val());
    });

});


/**
 * The Font Selector himself
 * 
 * @author Sven Lehnert 
 * @since 1.0
 */     
jQuery(function(){

	jQuery('#font').fontselect(
		{
			googleApi: 'https://fonts.googleapis.com/css2?family=',
			localFontsUrl: '../my-fonts/',
			systemFonts: 'Roboto|Open+Sans|Lato|Montserrat|Oswald|Raleway|Ubuntu'.split('|'),
		}
	).change(function(){

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