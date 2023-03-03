<?php
/**
 * Enqueue admin JS and CSS
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'admin_enqueue_scripts', 'tk_google_fonts_js' );
/**
 * Enqueue JS and CSS for the admin screen
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_js() {

	wp_enqueue_script( 'google_fonts_admin_js', plugins_url( '/admin/js/admin.js', __FILE__ ), array(), '1.0', true );
	wp_localize_script( 'google_fonts_admin_js', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'font-nonce' )
	));
	wp_register_script( 'tkgf-freemius-checkout', 'https://checkout.freemius.com/checkout.min.js', array(), false );
    wp_enqueue_script( 'tkgf-freemius-checkout' );
	wp_enqueue_script( 'google_fonts_gopro_js', plugins_url( '/admin/js/gopro.js', __FILE__ ), array(), '1.0', true );
	wp_register_script( 'jquery-fontselect', plugins_url( '/resources/font-select/jquery.fontselect.min.js', __FILE__ ), false, '1.0', true );
	wp_enqueue_script( 'jquery-fontselect' );
	wp_enqueue_style( 'jquery-fontselect-css', plugins_url( '/resources/font-select/fontselect.css', __FILE__ ), array(), '1.0' );
	wp_enqueue_style( 'tk-google-fonts-css', plugins_url( '/admin/css/tk-google-fonts.css', __FILE__ ), array(), '1.0' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	tk_google_fonts_enqueue_fonts();
}

add_action( 'wp_enqueue_scripts', 'tk_google_fonts_enqueue_fonts' );
/**
 * Enqueue JS and CSS for the frontend
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_enqueue_fonts() {

	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );

	if ( ! isset( $tk_google_fonts_options['selected_fonts'] ) ) {
		return;
	}

	// Enquire only the selected fonts.
	foreach ( $tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font ) {
		$tk_font_base_url = plugin_dir_url( __FILE__ ) . '/resources/my-fonts/' . $tk_google_font . '/' . $tk_google_font . '.css';

		if ( is_ssl() ) {
			$tk_font_base_url = str_replace( 'http:', 'https:', $tk_font_base_url );
		}

		wp_register_style( 'font-style-' . $tk_google_font, $tk_font_base_url, array(), '1.0' );
		wp_enqueue_style( 'font-style-' . $tk_google_font );
	}

}

