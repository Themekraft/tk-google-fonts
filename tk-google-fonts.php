<?php

/**
 * Plugin Name: TK Google Fonts
 * Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 * Description: Google Fonts UI for WordPress Themes
 * Version: 2.0.4
 * Author: ThemeKraft
 * Author URI: http://themekraft.com/
 * Licence: GPLv3
 * Svn: tk-google-fonts
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
	public $version = '2.0.4';


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

// Create a helper function for easy SDK access.
function tk_gf_fs() {
	global $tk_gf_fs;

	if ( ! isset( $tk_gf_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/includes/resources/freemius/start.php';

		$tk_gf_fs = fs_dynamic_init( array(
			'id'                  => '426',
			'slug'                => 'tk-google-fonts',
			'type'                => 'plugin',
			'public_key'          => 'pk_27b7a20f60176ff52e48568808a9e',
			'is_premium'          => true,
			'premium_suffix'      => 'Premium',
			'has_premium_version' => true,
			'has_addons'          => false,
			'has_paid_plans'      => true,
			'trial'               => array(
				'days'               => 7,
				'is_require_payment' => true,
			),
			'has_affiliation'     => 'all',
			'menu'                => array(
				'slug'           => 'tk-google-fonts-options',
				'override_exact' => true,
				'support'        => false,
				'affiliation'    => false,
				'parent'         => array(
					'slug' => 'themes.php',
				),
			),
			// Set the SDK to work in a sandbox mode (for development & testing).
			// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
			'secret_key'          => 'sk_Ss)kqAn~sJ7Sqj[QX3AvLRz_V^dI+',
		) );
	}

	return $tk_gf_fs;
}

// Init Freemius.
tk_gf_fs();
// Signal that SDK was initiated.
do_action( 'tk_gf_fs_loaded' );

function tk_gf_fs_settings_url() {
	return admin_url( 'themes.php?page=tk-google-fonts-options' );
}

tk_gf_fs()->add_filter( 'connect_url', 'tk_gf_fs_settings_url' );
tk_gf_fs()->add_filter( 'after_skip_url', 'tk_gf_fs_settings_url' );
tk_gf_fs()->add_filter( 'after_connect_url', 'tk_gf_fs_settings_url' );
tk_gf_fs()->add_filter( 'after_pending_connect_url', 'tk_gf_fs_settings_url' );


function tk_google_fonts_special_admin_notice() {
	$user_id = get_current_user_id();
	if ( ! get_user_meta( $user_id, 'tk_google_fonts_special_admin_notice_dismissed' ) ) {
		?>
		<div class="notice notice-success is-dismissible">
			<h4 style="margin-top: 20px;">TK GOOGLE FONTS</h4>
			<p style="line-height: 2.2; font-size: 13px;"><b>GO PRO NOW – AND SAVE BIG – 50% OFF - THIS MONTH ONLY</b><br>
				Get 50% discount if you order within the next month – only until 07 August 2017.
				<br>
				Coupon Code: <span
					style="line-height: 1; margin: 0 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; background: #fff; border: 1px solid rgba(0,0,0,0.1);">TKGOOGLE50</span>
			</p>
			<p style="margin: 20px 0;">
				<a class="button button-primary"
				   style="font-size: 15px; padding: 8px 20px; height: auto; line-height: 1; box-shadow: none; text-shadow: none; background: #46b450; color: #fff; border: 1px solid rgba(0,0,0,0.1);"
				   href="https://themekraft.com/easy-google-fonts-wordpress-forever/"
				   target="_blank"><s>&dollar;179</s> &dollar;59 LIFETIME DEAL</a>
				<a class="button xbutton-primary"
				   style="font-size: 15px; padding: 8px 20px; height: auto; line-height: 1;"
				   href="?tk_google_fonts_special_admin_notice_dismissed">Dismiss</a>
			</p>
		</div>
		<?php
	}
}
//add_action( 'admin_notices', 'tk_google_fonts_special_admin_notice' );

function tk_google_fonts_special_admin_notice_dismissed() {
	$user_id = get_current_user_id();
	if ( isset( $_GET['tk_google_fonts_special_admin_notice_dismissed'] ) ){
		add_user_meta( $user_id, 'tk_google_fonts_special_admin_notice_dismissed', 'true', true );
	}
}
add_action( 'admin_init', 'tk_google_fonts_special_admin_notice_dismissed' );