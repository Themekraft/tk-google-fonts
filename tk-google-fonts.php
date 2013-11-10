<?php

/**
 * Plugin Name: TK Google Fonts
 * Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 * Description: Google Fonts UI for WordPress Themes
 * Version: 1.1
 * Author: Sven Lehnert
 * Author URI: http://themekraft.com/members/svenl77/
 * Licence: GPLv3
 * Network: true
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
	 * Self Upgrade Values
	 */
	// Base URL to the remote upgrade API server
	public $upgrade_url = 'http://themekraft.com/';

	/**
	 * @var string
	 */
	public $version = '1.1';

	/**
	 * @var string
	 */
	public $tk_google_fonts_version_name = 'tk_google_fonts_license_options_version';

	/**
	 * @var string
	 */
	public $plugin_url;

	public function __construct() {
		
		// API License Key Registration Form
		require_once( plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php');
			// API License Key Registration Form
			require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/customizer.php');
		// Run the activation function
		if ( get_option( 'tk_google_fonts_license_options' ) === false ) {
			$this->activation();
		}

		if ( is_admin() ) {

			// License Key API Class
			require_once( plugin_dir_path( __FILE__ ) . 'includes/resources/api-manager/classes/class-tk-google-fonts-key-api.php');

			// Plugin Updater Class
			require_once( plugin_dir_path( __FILE__ ) . 'includes/resources/api-manager/classes/class-tk-google-fonts-plugin-update.php');

			// API License Key Registration Form
			require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/admin.php');

			// API License Key Registration Form
			require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/license-registration.php');

			// Load update class to update $this plugin from for example toddlahman.com
			$this->load_plugin_self_updater();

		}


	}

	public function plugin_url() {
		if ( isset( $this->plugin_url ) ) return $this->plugin_url;
		return $this->plugin_url = get_template_directory_uri() . '/';
	}

	/**
	 * Check for software updates
	 */
	public function load_plugin_self_updater() {
		$options = get_option( 'tk_google_fonts_license_options' );

		$upgrade_url = $this->upgrade_url; // URL to access the Update API Manager.
		$plugin_name = 'tk_google_fonts'; // same as plugin slug. if a theme use a theme name like 'twentyeleven'
		$product_id = get_option( 'tk_google_fonts_product_id' ); // Software Title
		$api_key = $options['api_key']; // API License Key
		$activation_email = $options['activation_email']; // License Email
		$renew_license_url = 'http://themekraft.com/my-account'; // URL to renew a license
		$instance = get_option( 'tk_google_fonts_instance' ); // Instance ID (unique to each blog activation)
		$domain = site_url(); // blog domain name
		$software_version = get_option( $this->tk_google_fonts_version_name ); // The software version
		$plugin_or_theme = 'plugin'; // 'theme' or 'plugin'

		new TK_Google_Fonts_Update_API_Check( $upgrade_url, $plugin_name, $product_id, $api_key, $activation_email, $renew_license_url, $instance, $domain, $software_version, $plugin_or_theme );
	}


	/**
	 * Generate the default data arrays
	 */
	public function activation() {
		global $wpdb;

		$global_options = array(
			'api_key' 			=> '',
			'activation_email' 	=> '',
					);

		update_option( 'tk_google_fonts_license_options', $global_options );

		require_once( get_template_directory() . '/includes/resources/api-manager/classes/class-cc-tk-passwords.php' );

		$tk_google_fonts_password_management = new tk_google_fonts_Password_Management();

		// Generate a unique installation $instance id
		$instance = $tk_google_fonts_password_management->generate_password( 12, false );

		$single_options = array(
			'tk_google_fonts_product_id' 				=> 'TK Google Fonts',
			'tk_google_fonts_instance' 				=> $instance,
			'tk_google_fonts_deactivate_checkbox' 	=> 'on',
			'tk_google_fonts_activated' 				=> 'Deactivated',
			);

		foreach ( $single_options as $key => $value ) {
			update_option( $key, $value );
		}

		$curr_ver = get_option( $this->tk_google_fonts_version_name );

		// checks if the current plugin version is lower than the version being installed
		if ( version_compare( $this->version, $curr_ver, '>' ) ) {
			// update the version
			update_option( $this->tk_google_fonts_version_name, $this->version );
		}

	}

} // End of class

$GLOBALS['TK_Google_Fonts'] = new TK_Google_Fonts();
?>