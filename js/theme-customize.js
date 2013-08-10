
/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	// Update the site title in real time...
	wp.customize( 'h1_font', function( value ) {
		value.bind( function( newval ) {
			$('h1, h1 a').css('font-family', newval );
		} );
	} );
	
	
} )( jQuery );
