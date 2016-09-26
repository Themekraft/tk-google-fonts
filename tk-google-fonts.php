<?php

/**
 * Plugin Name: TK Google Fonts
 * Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 * Description: Google Fonts UI for WordPress Themes
 * Version: 1.3.1
 * Author: Sven Lehnert
 * Author URI: http://themekraft.com/members/svenl77/
 * Licence: GPLv3
 *
 */

/** This is the ThemeKraft Google Fonts WordPress Plugin
 *
 * Manage your Google Fonts and use them in the WordPress Customizer,
 * via CSS or via theme options if intehrated into your theme.
 *
 * Thanks goes to Konstantin Kovshenin for his nice tutorial.
 * http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 * It was my starting point and makes developing easy ;-)
 *
 * Big thanks goes also to tommoor for his jquery fontselector plugin. https://github.com/tommoor/fontselect-jquery-plugin
 * I only needed to put this together and create an admin UI to manage the fonts.
 *
 *
 * Have fun!
 *
 */
class TK_Google_Fonts {

	/**
	 * @var string
	 */
	public $version = '1.3.1';


	public function __construct() {

		define( 'TK_GOOGLE_FONTS', $this->version );

		require_once( plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/customizer.php' );

		if ( is_admin() ) {

			// API License Key Registration Form
			require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/admin.php' );

		}

	}

	public function plugin_url() {
		if ( isset( $this->plugin_url ) ) {
			return $this->plugin_url;
		}

		return $this->plugin_url = get_template_directory_uri() . '/';
	}

} // End of class

$GLOBALS['TK_Google_Fonts'] = new TK_Google_Fonts();
