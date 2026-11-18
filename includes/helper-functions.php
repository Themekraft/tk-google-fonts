<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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

/**
 * Version of the local font download format. Bump it when the way fonts are
 * downloaded changes, so existing sites download their fonts again.
 * 2: regular, bold, italic and bold italic (TKT-130).
 */
define( 'TKGF_FONTS_FORMAT', 2 );

/**
 * Folder where the downloaded fonts are stored.
 *
 * @return string
 */
function tkgf_fonts_dir() {
	return plugin_dir_path( __FILE__ ) . 'resources/my-fonts/';
}

/**
 * URL of the folder where the downloaded fonts are stored.
 *
 * @return string
 */
function tkgf_fonts_url() {
	return plugin_dir_url( __FILE__ ) . 'resources/my-fonts/';
}

/**
 * Styles requested from Google for each font, in order. Google answers 400
 * when none of the requested styles exist, so a font without regular or bold
 * (for example Buda, only 300) falls back to every static weight, and a name
 * Google does not accept with axes falls back to the plain family request.
 *
 * @param string $google_font_name Font name as stored, with + for spaces.
 * @return string[] Query strings to append after "family=<name>".
 */
function tkgf_font_style_queries( $google_font_name ) {
	// Legacy names that already carry a style (for example "Open+Sans:300").
	if ( false !== strpos( $google_font_name, ':' ) ) {
		return array( '' );
	}

	$all = array();
	foreach ( array( 0, 1 ) as $ital ) {
		foreach ( range( 100, 900, 100 ) as $wght ) {
			$all[] = $ital . ',' . $wght;
		}
	}

	$queries = array(
		':ital,wght@0,400;0,700;1,400;1,700',
		':ital,wght@' . implode( ';', $all ),
		'',
	);

	/**
	 * Filters the style queries tried, in order, when downloading a font.
	 *
	 * @param string[] $queries          Query strings appended to the family name.
	 * @param string   $google_font_name Font name.
	 */
	return apply_filters( 'tkgf_font_style_queries', $queries, $google_font_name );
}

/**
 * Download a Google font (CSS and woff2 files) into the local fonts folder.
 *
 * The @font-face rules are kept as Google returns them, so each one carries
 * its own font-weight and font-style; only the file URLs are rewritten.
 *
 * @param string $google_font_name Font name as stored, with + for spaces.
 * @return true|WP_Error
 */
function tkgf_download_font( $google_font_name ) {
	if ( '' === $google_font_name || preg_match( '#[/\\\\]|\.\.#', $google_font_name ) ) {
		return new WP_Error( 'tkgf_invalid', 'Invalid font name.' );
	}

	$wp_filesystem = tk_google_fonts_get_filesystem();
	if ( ! $wp_filesystem ) {
		return new WP_Error( 'tkgf_retry', 'The filesystem is not writable.' );
	}

	$user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36';
	$css        = '';
	$last_error = null;

	foreach ( tkgf_font_style_queries( $google_font_name ) as $query ) {
		$response = wp_remote_get(
			'https://fonts.googleapis.com/css2?family=' . $google_font_name . $query,
			array(
				'user-agent' => $user_agent,
				'timeout'    => 15,
			)
		);
		if ( is_wp_error( $response ) ) {
			$last_error = new WP_Error( 'tkgf_retry', $response->get_error_message() );
			continue;
		}
		$body = wp_remote_retrieve_body( $response );
		if ( 200 === wp_remote_retrieve_response_code( $response ) && false !== strpos( $body, '@font-face' ) ) {
			$css = $body;
			break;
		}
		$last_error = new WP_Error( 'tkgf_not_found', 'Font not found.' );
	}

	if ( '' === $css ) {
		return $last_error ? $last_error : new WP_Error( 'tkgf_not_found', 'Font not found.' );
	}

	if ( ! preg_match_all( '/url\((https:\/\/[^)\s]+)\)/', $css, $matches ) ) {
		return new WP_Error( 'tkgf_not_found', 'Font not found.' );
	}

	// Variable fonts reuse one file for several weights: download it once.
	$files = array();
	$i     = 0;
	foreach ( array_unique( $matches[1] ) as $file_url ) {
		$asset = wp_remote_get( $file_url, array( 'timeout' => 15 ) );
		if ( is_wp_error( $asset ) || 200 !== wp_remote_retrieve_response_code( $asset ) ) {
			return new WP_Error( 'tkgf_retry', 'Could not download ' . $file_url );
		}
		$file_name           = $google_font_name . '-' . $i . '.woff2';
		$files[ $file_name ] = wp_remote_retrieve_body( $asset );
		$css                 = str_replace( $file_url, tkgf_fonts_url() . $google_font_name . '/' . $file_name, $css );
		$i++;
	}

	$folder = tkgf_fonts_dir() . $google_font_name . '/';
	if ( ! is_dir( $folder ) ) {
		wp_mkdir_p( $folder );
	}

	// Remove files from a previous download that the new CSS no longer uses.
	foreach ( (array) glob( $folder . '*.woff2' ) as $old_file ) {
		if ( $old_file && ! isset( $files[ basename( $old_file ) ] ) ) {
			wp_delete_file( $old_file );
		}
	}

	foreach ( $files as $file_name => $contents ) {
		$wp_filesystem->put_contents( $folder . $file_name, $contents, FS_CHMOD_FILE );
	}
	$wp_filesystem->put_contents( $folder . $google_font_name . '.css', $css, FS_CHMOD_FILE );

	return true;
}

/**
 * Download every selected font again.
 *
 * @return bool False when a font could not be downloaded because of a
 *              connection error, so the caller can retry later.
 */
function tkgf_download_selected_fonts() {
	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );
	$ok                      = true;
	if ( ! empty( $tk_google_fonts_options['selected_fonts'] ) ) {
		foreach ( $tk_google_fonts_options['selected_fonts'] as $selected_font ) {
			$result = tkgf_download_font( $selected_font );
			if ( is_wp_error( $result ) && 'tkgf_retry' === $result->get_error_code() ) {
				$ok = false;
			}
		}
	}
	return $ok;
}

/**
 * Initialise and return the WP_Filesystem instance for direct file access.
 * Loaded on every request (not only in wp-admin) because fonts are also
 * downloaded from cron and from background plugin updates.
 *
 * @return WP_Filesystem_Base
 */
function tk_google_fonts_get_filesystem() {
	global $wp_filesystem;
	if ( ! $wp_filesystem ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
	}
	return $wp_filesystem;
}

add_action( 'init', 'tkgf_maybe_refresh_fonts' );
/**
 * Fonts downloaded before TKGF_FONTS_FORMAT only have the regular style.
 * Updating does not fix that by itself: upgrader_process_complete runs the
 * code of the version being replaced, which downloads the old way. So the
 * first request on the new version schedules a background download.
 */
function tkgf_maybe_refresh_fonts() {
	if ( (int) get_option( 'tkgf_fonts_format' ) >= TKGF_FONTS_FORMAT ) {
		return;
	}
	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );
	if ( empty( $tk_google_fonts_options['selected_fonts'] ) ) {
		update_option( 'tkgf_fonts_format', TKGF_FONTS_FORMAT );
		return;
	}
	if ( get_transient( 'tkgf_refresh_fonts_retry' ) || wp_next_scheduled( 'tkgf_refresh_fonts' ) ) {
		return;
	}
	wp_schedule_single_event( time(), 'tkgf_refresh_fonts' );
}

add_action( 'tkgf_refresh_fonts', 'tkgf_refresh_fonts' );
/**
 * Cron callback: download the selected fonts in the current format.
 */
function tkgf_refresh_fonts() {
	if ( tkgf_download_selected_fonts() ) {
		update_option( 'tkgf_fonts_format', TKGF_FONTS_FORMAT );
	} else {
		set_transient( 'tkgf_refresh_fonts_retry', 1, HOUR_IN_SECONDS );
	}
}

add_action( 'upgrader_process_complete', 'tkgf_after_update', 5, 2 );
/**
 * Updates replace the plugin folder, which holds the downloaded fonts, so
 * download them again after this plugin is updated.
 *
 * @param WP_Upgrader $upgrader Upgrader instance.
 * @param array       $info     Update details.
 */
function tkgf_after_update( $upgrader, $info ) {
	if ( isset( $upgrader->result['destination_name'] ) && in_array( $upgrader->result['destination_name'], array( 'tk-google-fonts', 'tk-google-fonts-premium' ), true ) ) {
		tkgf_download_selected_fonts();
	}
}
