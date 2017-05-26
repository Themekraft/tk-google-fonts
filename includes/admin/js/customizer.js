// This file here handles the live changes in your preview. 
( function( $ ) {

	// Update the h1 titles in real time...
	wp.customize( 'h1_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h1, h1 a').css('font-family', '' );
			} else {
				$('h1, h1 a').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'h2_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h2, h2 a').css('font-family', '' );
			} else {
				$('h2, h2 a').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'h3_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h3, h3 a').css('font-family', '' );
			} else {
				$('h3, h3 a').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'h4_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h4, h4 a').css('font-family', '' );
			} else {
				$('h4, h4 a').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'h5_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h5, h5 a').css('font-family', '' );
			} else {
				$('h5, h5 a').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'h6_font', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('h6, h6 a').css('font-family', '' );
			} else {
				$('h6, h6 a').css('font-family', newval );
			}
		} );
	} );

	wp.customize( 'body_text', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('body, p').css('font-family', '' );
			} else {
				$('body, p').css('font-family', newval );
			}
		} );
	} );
	
	wp.customize( 'blockquotes', function( value ) {
		value.bind( function( newval ) {
			if(newval == 'none'){
				$('blockquote, blockquote p, blockquote p a').css('font-family', '' );
			} else {
				$('blockquote, blockquote p, blockquote p a').css('font-family', newval );
			}
		} );
	} );
	
} )( jQuery );
