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

		wp_register_script( 'jquery-fontselect', plugins_url( '/resources/font-select/jquery.fontselect.min.js', __FILE__ ), false, '1.0' );
		wp_enqueue_script( 'jquery-fontselect' );

	wp_enqueue_style( 'jquery-fontselect-css', plugins_url( '/resources/font-select/fontselect.css', __FILE__ ) );

	wp_enqueue_style( 'tk-google-fonts-css', plugins_url( '/admin/css/tk-google-fonts.css', __FILE__ ) );

    wp_enqueue_script( 'jquery-ui-dialog' ); 
    wp_enqueue_style( 'wp-jquery-ui-dialog' );

	tk_google_fonts_enqueue_fonts();
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

	// Enquire only the selected fonts
	foreach ( $tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font ) {
		$tk_font_base_url = plugin_dir_url(__FILE__) . '/resources/my-fonts/' . $tk_google_font .'/' . $tk_google_font . '.css';

		if ( is_ssl() ) {
			$tk_font_base_url = str_replace( 'http:', 'https:', $tk_font_base_url );
		}

		wp_register_style( 'font-style-' . $tk_google_font,  $tk_font_base_url );
		wp_enqueue_style( 'font-style-' . $tk_google_font );
	}
	
}

add_action( 'wp_ajax_tk_notice_modal', 'tk_google_font_notice_modal_ajax' );
function tk_google_font_notice_modal_ajax() {

	if ( empty( $_POST['modal-id'] ) ) {
		die();
	}

	$modal_id = sanitize_text_field( $_POST['modal-id'] );

	update_option( 'tk_google_fonts_notice_modal', $modal_id );
	die();
}