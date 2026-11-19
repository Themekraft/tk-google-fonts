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

add_action( 'admin_enqueue_scripts', 'tkgf_js' );
/**
 * Enqueue JS and CSS for the admin screen
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */
function tkgf_js() {

	wp_enqueue_script( 'google_fonts_admin_js', plugins_url( '/admin/js/admin.js', __FILE__ ), array(), TK_GOOGLE_FONTS, true );
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
	tkgf_enqueue_fonts();
}

add_action( 'wp_enqueue_scripts', 'tkgf_enqueue_fonts' );
/**
 * Enqueue JS and CSS for the frontend
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */
function tkgf_enqueue_fonts() {

	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );

	if ( ! isset( $tk_google_fonts_options['selected_fonts'] ) ) {
		return;
	}

	$missing = array();

	// Enquire only the selected fonts.
	foreach ( $tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font ) {
		$tk_font_base_url = plugin_dir_url( __FILE__ ) . '/resources/my-fonts/' . $tk_google_font . '/' . $tk_google_font . '.css';

		if ( is_ssl() ) {
			$tk_font_base_url = str_replace( 'http:', 'https:', $tk_font_base_url );
		}

		// Version by file time, so caches pick up a font downloaded again.
		$tk_font_css_file = tkgf_fonts_dir() . $tk_google_font . '/' . $tk_google_font . '.css';
		if ( file_exists( $tk_font_css_file ) ) {
			$tk_font_version = (string) filemtime( $tk_font_css_file );
		} else {
			$tk_font_version = TK_GOOGLE_FONTS;
			$missing[]       = $tk_google_font;
		}

		wp_register_style( 'font-style-' . $tk_google_font, $tk_font_base_url, array(), $tk_font_version );
		wp_enqueue_style( 'font-style-' . $tk_google_font );
	}

	// Reuses the file_exists() above, so a normal request costs nothing extra.
	if ( $missing ) {
		tkgf_fonts_missing( $missing );
	}

}

/**
 * Some selected fonts have no CSS file. Updates replace the plugin folder,
 * which holds the fonts, and the update hook does not always refill it
 * (2.3.2 fataled outside wp-admin, a switch between the free and premium
 * copies, the other sites of a network), so download them in the background.
 *
 * @param string[] $missing Selected fonts without a CSS file.
 */
function tkgf_fonts_missing( $missing ) {
	// Fonts Google did not have at the last refresh are not retried for a day.
	$unavailable = get_transient( 'tkgf_fonts_unavailable' );
	if ( is_array( $unavailable ) ) {
		$missing = array_diff( $missing, $unavailable );
	}
	if ( $missing ) {
		tkgf_schedule_refresh();
	}
}

/**
 * Schedule a background download of the selected fonts, unless one is
 * already scheduled or the last one failed less than an hour ago.
 */
function tkgf_schedule_refresh() {
	if ( wp_next_scheduled( 'tkgf_refresh_fonts' ) || get_transient( 'tkgf_refresh_fonts_retry' ) ) {
		return;
	}
	wp_schedule_single_event( time(), 'tkgf_refresh_fonts' );
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

	$wp_filesystem = tkgf_get_filesystem();
	if ( ! $wp_filesystem || ! wp_mkdir_p( tkgf_fonts_dir() ) || ! $wp_filesystem->is_writable( tkgf_fonts_dir() ) ) {
		return new WP_Error( 'tkgf_retry', 'The fonts folder is not writable.' );
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
		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		if ( 200 === $code && false !== strpos( $body, '@font-face' ) ) {
			$css = $body;
			break;
		}
		// Google is down or rate limiting: the font may exist, try again later.
		if ( 429 === $code || $code >= 500 ) {
			return new WP_Error( 'tkgf_retry', 'Google Fonts answered HTTP ' . $code . '.' );
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
	$urls   = array_values( array_unique( $matches[1] ) );
	$bodies = tkgf_fetch_files( $urls );
	if ( is_wp_error( $bodies ) ) {
		return $bodies;
	}

	$files = array();
	foreach ( $urls as $i => $file_url ) {
		$file_name           = $google_font_name . '-' . $i . '.woff2';
		$files[ $file_name ] = $bodies[ $i ];
		$css                 = str_replace( $file_url, tkgf_fonts_url() . $google_font_name . '/' . $file_name, $css );
	}

	$folder = tkgf_fonts_dir() . $google_font_name . '/';
	if ( ! wp_mkdir_p( $folder ) || ! $wp_filesystem->is_writable( $folder ) ) {
		return new WP_Error( 'tkgf_retry', 'The fonts folder is not writable.' );
	}

	// Remove files from a previous download that the new CSS no longer uses.
	foreach ( (array) glob( $folder . '*.woff2' ) as $old_file ) {
		if ( $old_file && ! isset( $files[ basename( $old_file ) ] ) ) {
			wp_delete_file( $old_file );
		}
	}

	foreach ( $files as $file_name => $contents ) {
		if ( ! $wp_filesystem->put_contents( $folder . $file_name, $contents, FS_CHMOD_FILE ) ) {
			return new WP_Error( 'tkgf_retry', 'Could not write ' . $file_name );
		}
	}
	// The CSS file goes last: its presence means the font is complete.
	if ( ! $wp_filesystem->put_contents( $folder . $google_font_name . '.css', $css, FS_CHMOD_FILE ) ) {
		return new WP_Error( 'tkgf_retry', 'Could not write ' . $google_font_name . '.css' );
	}

	return true;
}

/**
 * Download font files, several at a time. A font with regular, bold and
 * italic styles is around 4 files per character subset (36 for Roboto), and
 * one by one that takes long enough to hit max_execution_time.
 *
 * @param string[] $urls File URLs.
 * @return string[]|WP_Error File contents, in the same order as $urls.
 */
function tkgf_fetch_files( $urls ) {
	$bodies = array();

	if ( class_exists( 'WpOrg\\Requests\\Requests' ) ) {
		$requests_class = 'WpOrg\\Requests\\Requests';
	} elseif ( class_exists( 'Requests' ) ) {
		$requests_class = 'Requests';
	} else {
		$requests_class = '';
	}

	if ( '' === $requests_class ) {
		foreach ( $urls as $i => $url ) {
			$response = wp_remote_get( $url, array( 'timeout' => 15 ) );
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return new WP_Error( 'tkgf_retry', 'Could not download ' . $url );
			}
			$bodies[ $i ] = wp_remote_retrieve_body( $response );
		}
		return $bodies;
	}

	$options = array(
		'timeout'   => 15,
		'useragent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
		'verify'    => ABSPATH . WPINC . '/certificates/ca-bundle.crt',
	);

	foreach ( array_chunk( $urls, 12, true ) as $chunk ) {
		$requests = array();
		foreach ( $chunk as $i => $url ) {
			$requests[ $i ] = array( 'url' => $url );
		}
		$responses = call_user_func( array( $requests_class, 'request_multiple' ), $requests, $options );
		foreach ( $chunk as $i => $url ) {
			$response = isset( $responses[ $i ] ) ? $responses[ $i ] : null;
			if ( ! is_object( $response ) || $response instanceof Exception || 200 !== (int) $response->status_code ) {
				return new WP_Error( 'tkgf_retry', 'Could not download ' . $url );
			}
			$bodies[ $i ] = $response->body;
		}
	}

	return $bodies;
}

/**
 * Download the selected fonts again.
 *
 * @param bool $only_missing Only download fonts that have no CSS file.
 * @return array {
 *     @type bool     $ok          False when a font could not be downloaded
 *                                 for a temporary reason (connection error,
 *                                 Google 5xx or 429, folder not writable), so
 *                                 the caller can retry later.
 *     @type string[] $unavailable Fonts Google does not have.
 * }
 */
function tkgf_download_selected_fonts( $only_missing = false ) {
	$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );
	$ok                      = true;
	$unavailable             = array();
	if ( ! empty( $tk_google_fonts_options['selected_fonts'] ) ) {
		foreach ( $tk_google_fonts_options['selected_fonts'] as $selected_font ) {
			if ( $only_missing && file_exists( tkgf_fonts_dir() . $selected_font . '/' . $selected_font . '.css' ) ) {
				continue;
			}
			$result = tkgf_download_font( $selected_font );
			if ( ! is_wp_error( $result ) ) {
				continue;
			}
			if ( 'tkgf_retry' === $result->get_error_code() ) {
				$ok = false;
				// The rest would fail the same way: stop and retry later.
				break;
			}
			$unavailable[] = $selected_font;
		}
	}
	return array(
		'ok'          => $ok,
		'unavailable' => $unavailable,
	);
}

/**
 * Initialise and return the WP_Filesystem instance for direct file access.
 * Loaded on every request (not only in wp-admin) because fonts are also
 * downloaded from cron and from background plugin updates.
 *
 * @return WP_Filesystem_Base|false False when the filesystem needs credentials.
 */
function tkgf_get_filesystem() {
	global $wp_filesystem;
	if ( ! $wp_filesystem ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		if ( ! WP_Filesystem() ) {
			return false;
		}
	}
	return $wp_filesystem;
}

add_action( 'init', 'tkgf_maybe_refresh_fonts' );
/**
 * Fonts downloaded before TKGF_FONTS_FORMAT only have the regular style.
 * Updating does not fix that by itself: upgrader_process_complete runs the
 * code of the version being replaced, which downloads the old way. So the
 * first request on the new version schedules a background download.
 * Fonts whose files are missing are detected in tkgf_enqueue_fonts().
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
	tkgf_schedule_refresh();
}

add_action( 'tkgf_refresh_fonts', 'tkgf_refresh_fonts' );
/**
 * Cron callback: download the selected fonts in the current format, or only
 * the missing ones when the format is already current.
 */
function tkgf_refresh_fonts() {
	$only_missing = (int) get_option( 'tkgf_fonts_format' ) >= TKGF_FONTS_FORMAT;
	$result       = tkgf_download_selected_fonts( $only_missing );

	if ( ! $result['ok'] ) {
		set_transient( 'tkgf_refresh_fonts_retry', 1, HOUR_IN_SECONDS );
		return;
	}

	delete_transient( 'tkgf_refresh_fonts_retry' );
	update_option( 'tkgf_fonts_format', TKGF_FONTS_FORMAT );
	if ( $result['unavailable'] ) {
		set_transient( 'tkgf_fonts_unavailable', $result['unavailable'], DAY_IN_SECONDS );
	} else {
		delete_transient( 'tkgf_fonts_unavailable' );
	}
}

add_action( 'admin_init', 'tkgf_run_overdue_refresh' );
/**
 * Sites without a working WP-Cron never run the scheduled refresh. When it is
 * more than 10 minutes overdue, run it on an admin page load instead.
 */
function tkgf_run_overdue_refresh() {
	$next = wp_next_scheduled( 'tkgf_refresh_fonts' );
	if ( ! $next || $next > time() - 10 * MINUTE_IN_SECONDS || wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_unschedule_event( $next, 'tkgf_refresh_fonts' );
	tkgf_refresh_fonts();
}

add_action( 'upgrader_process_complete', 'tkgf_after_update', 5, 2 );
/**
 * Updates replace the plugin folder, which holds the downloaded fonts, so
 * download them again after this plugin is updated. Single updates pass the
 * plugin in $info['plugin'], bulk updates (several plugins in wp-admin, any
 * multi-plugin WP-CLI update) in $info['plugins'].
 *
 * @param WP_Upgrader $upgrader Upgrader instance.
 * @param array       $info     Update details.
 */
function tkgf_after_update( $upgrader, $info ) {
	if ( ! isset( $info['type'] ) || 'plugin' !== $info['type'] ) {
		return;
	}

	$folders = array();
	if ( ! empty( $info['plugins'] ) ) {
		$folders = array_map( 'dirname', (array) $info['plugins'] );
	} elseif ( ! empty( $info['plugin'] ) ) {
		$folders = array( dirname( $info['plugin'] ) );
	}
	// Uploading a zip to replace the installed copy reports no plugin basename.
	if ( isset( $upgrader->result['destination_name'] ) ) {
		$folders[] = $upgrader->result['destination_name'];
	}

	// Only refill when the copy running this code (free or premium, whatever
	// its folder name) was updated: an inactive copy's fonts are not used.
	if ( in_array( basename( dirname( __DIR__ ) ), $folders, true ) ) {
		tkgf_refresh_fonts();
	}
}
