<?php
/**
 * Enqueue admin JS and CSS
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'admin_enqueue_scripts', 'tk_google_fonts_js' );

function tk_google_fonts_js() {

	wp_enqueue_script( 'google_fonts_admin_js', plugins_url( '/admin/js/admin.js', __FILE__ ) );

	wp_register_script( 'jquery-fontselect', plugins_url( '/resources/font-select/jquery.fontselect.min.js', __FILE__ ), false, '1.6' );
	wp_enqueue_script( 'jquery-fontselect' );

	wp_enqueue_style( 'jquery-fontselect-css', plugins_url( '/resources/font-select/fontselect.css', __FILE__ ) );

	wp_enqueue_style( 'tk-google-fonts-css', plugins_url( '/admin/css/tk-google-fonts.css', __FILE__ ) );
	wp_enqueue_script( 'tk_loop_designer_zendesk_js', '//assets.zendesk.com/external/zenbox/v2.6/zenbox.js' );
	wp_enqueue_style( 'tk_loop_designer_zendesk_css', '//assets.zendesk.com/external/zenbox/v2.6/zenbox.css' );

	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );

	if ( isset( $tk_google_fonts_options['selected_fonts'] ) ) {
		foreach ( $tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font ) {
			wp_register_style( 'font-style-' . $tk_google_font, 'http://fonts.googleapis.com/css?family=' . $tk_google_font );
			wp_enqueue_style( 'font-style-' . $tk_google_font );
		}
	}
}

/**
 * Enqueue JS and CSS for the frontend
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'wp_enqueue_scripts', 'tk_google_fonts_enqueue_fonts' );

function tk_google_fonts_enqueue_fonts() {

	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );

	if ( ! isset( $tk_google_fonts_options['selected_fonts'] ) ) {
		return;
	}

	foreach ( $tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font ) {
		wp_register_style( 'font-style-' . $tk_google_font, 'http://fonts.googleapis.com/css?family=' . $tk_google_font );
		wp_enqueue_style( 'font-style-' . $tk_google_font );
	}
}

?>
