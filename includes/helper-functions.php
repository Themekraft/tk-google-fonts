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

add_action( 'upgrader_process_complete', 'tkgf_after_update', 5, 2 );
function tkgf_after_update( $upgrader, $info ){

	if( isset( $upgrader->result['destination_name'] ) && ( $upgrader->result['destination_name'] == 'tk-google-fonts' || $upgrader->result['destination_name'] == 'tk-google-fonts-premium' ) ){
		$tk_google_fonts_options = get_option( 'tk_google_fonts_options' );
		if ( ! empty( $tk_google_fonts_options['selected_fonts'] ) ) {
			$tk_fonts_folder  = dirname( plugin_dir_path( __FILE__ ) ) . '/includes/resources/my-fonts/';
			$tk_search_url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{3,4}(\/\S[^)]*)?/';
			foreach( $tk_google_fonts_options['selected_fonts'] as $selected_font ){
				if ( ! is_dir( $tk_fonts_folder . $selected_font ) ) {
					wp_mkdir_p( $tk_fonts_folder . $selected_font );
				}
				$font_url     = 'https://fonts.googleapis.com/css2?family=' . $selected_font;
				$font_request = wp_remote_get( $font_url, array( 'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36' ) );
				$fp           = fopen( $tk_fonts_folder . $selected_font . '/' . $selected_font . '.css', 'w+' );
				if ( isset( $font_request['response']['code'] ) && 200 === $font_request['response']['code'] ) {
					if ( preg_match_all( $tk_search_url, $font_request['body'], $tk_font_fileinfo ) ) {
						$tk_font_urls    = $tk_font_fileinfo[0];
						$tk_hosted_fonts = $font_request['body'];
						$tk_main_url     = dirname( plugin_dir_url( __FILE__ ) ) . '/includes/resources/my-fonts/';
						$i               = 0;
						foreach ( $tk_font_urls as $googlefonts_urls ) {
							$tk_google_urls         = $tk_font_urls[ $i ];
							$ff                     = fopen( $tk_fonts_folder . $selected_font . '/' . $selected_font . '-' . $i . '.woff2', 'w+' );
							$tk_new_fontfamily_urls = file_get_contents( $tk_google_urls, false );
							fwrite( $ff, $tk_new_fontfamily_urls );
							fclose( $ff );
							$tk_hosted_fonts = str_replace( $tk_google_urls, $tk_main_url . $selected_font . '/' . $selected_font . '-' . $i . '.woff2', $tk_hosted_fonts );
							$i++;
			
						}
						fwrite( $fp, $tk_hosted_fonts );
						fclose( $fp );
					}
				} else {
					wp_die( 'We don\'t found your font, sorry... 😔', 'Not Found', 400 );
				}
			}
		}
	}
}

