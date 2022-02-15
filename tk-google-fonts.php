<?php

/**
 * Plugin Name: TK Google Fonts
 * Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 * Description: Google Fonts UI for WordPress Themes
 * Version: 2.2.0
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

if ( function_exists( 'tk_gf_fs' ) ) {
    tk_gf_fs()->set_basename( true, __FILE__ );
} else {

        if ( ! function_exists( 'tk_gf_fs' ) ) {

            // Create a helper function for easy SDK access.
            function tk_gf_fs(){
                global  $tk_gf_fs ;
                
                if ( !isset( $tk_gf_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/includes/resources/freemius/start.php';
                    $tk_gf_fs = fs_dynamic_init( array(
                        'id'              => '426',
                        'slug'            => 'tk-google-fonts',
                        'type'            => 'plugin',
                        'public_key'      => 'pk_27b7a20f60176ff52e48568808a9e',
                        'is_premium'      => false,
                        'premium_suffix'  => 'Premium',
                        'has_addons'      => false,
                        'has_paid_plans'  => true,
                        'trial'           => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                        'has_affiliation' => 'all',
                        'menu'            => array(
                        'slug'           => 'tk-google-fonts-options',
                        'override_exact' => true,
                        'support'        => false,
                        'affiliation'    => false,
                        'parent'         => array(
                        'slug' => 'themes.php',
                    ),
                    ),
                        'is_live'         => true,
                    ) );
                }
                
                return $tk_gf_fs;
            }

            // Init Freemius.
            tk_gf_fs();
            // Signal that SDK was initiated.
            do_action( 'tk_gf_fs_loaded' );
            function tk_gf_fs_settings_url()
            {
                return admin_url( 'themes.php?page=tk-google-fonts-options' );
            }

            tk_gf_fs()->add_filter( 'connect_url', 'tk_gf_fs_settings_url' );
            tk_gf_fs()->add_filter( 'after_skip_url', 'tk_gf_fs_settings_url' );
            tk_gf_fs()->add_filter( 'after_connect_url', 'tk_gf_fs_settings_url' );
            tk_gf_fs()->add_filter( 'after_pending_connect_url', 'tk_gf_fs_settings_url' );
        }

        class TK_Google_Fonts {
            /**
             * @var string
             */
            public  $version = '2.2.0' ;
            public function __construct()
            {
                define( 'TK_GOOGLE_FONTS', $this->version );
                require_once plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php';
                require_once plugin_dir_path( __FILE__ ) . 'includes/admin/customizer.php';
                if ( is_admin() ) {
                    // API License Key Registration Form
                    require_once plugin_dir_path( __FILE__ ) . 'includes/admin/admin.php';
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
    }