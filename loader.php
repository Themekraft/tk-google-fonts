<?php
/*
 Plugin Name: TK Google Fonts
 Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 Description: Google Fonts UI for Themkraft Themes
 Version: 1.0
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
 Licence: GPLv3
 Network: true
 */
 
// This is the Themekraft Google Fonts Managing Plugin
// Thanks goes to Konstantin Kovshenin for his nice totorial. http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
// It was my starting point and makes developing easy ;-)

// Big Thanks goes also to tommoor for his jquery.fontselector plugin. https://github.com/tommoor/fontselect-jquery-plugin
// I only needet to put this together and create a admin ui to manage the fonts.


add_action( 'admin_menu', 'x2_google_fonts_admin_menu' );
function x2_google_fonts_admin_menu() {
    add_theme_page( 'X2 Google Fonts', 'X2 Google Fonts', 'edit_theme_options', 'x2-google-fonts-options', 'x2_google_fonts_screen' );
}

function x2_google_fonts_screen() {
?>
    <div class="wrap">
        <div><br></div>
        <h2>Google Fonts Setup</h2>

        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'x2-google-fonts-setup' ); ?>
            <?php do_settings_sections( 'x2-google-fonts-setup' ); ?>
            <?php //submit_button(); ?>
        </form>
    </div>
<?php
}

add_action( 'admin_init', 'x2_google_fonts_register_admin_settings' );
function x2_google_fonts_register_admin_settings() {
    register_setting( 'x2-google-fonts-setup', 'x2-google-fonts-setup' );

    // Settings fields and sections
    add_settings_section( 'section_typography', '', 'x2_google_fonts_typography', 'x2-google-fonts-setup' );
    add_settings_field( 'primary-font', '<b>Add Google Font</b>', 'x2_google_fonts_field_font', 'x2-google-fonts-setup', 'section_typography' );
    add_settings_field( 'primary-list', '<b>Manage Font</b>', 'x2_google_fonts_list', 'x2-google-fonts-setup', 'section_typography' );
}

function x2_google_fonts_typography() {
    echo '<p> Please ceep in mint "ever font will slow down your page a bit. <br>
			If you use to manny fonts you will have a slow siteloade and this is bad for seo 
			Best is to use one or to Fonts"</p>';
}

function x2_google_fonts_field_font() {
    $options = (array) get_option( 'tk_google_fonts_options' );

    if ( isset( $options['selected_fonts'] ) )
        $selected_fonts = $options['selected_fonts'];

    ?>
     
    <div id="google_fonts_selecter">
        <input id="font" type="text" /><input type="button" value="Add Font" name="add_google_font" class="add_google_font btn">
	     
	    <br><br><br>
	    <h2 class="add_text"> Preview for h2 </h2>
	    <h3 class="add_text"> Preview for h3 </h3>
	    <p class="add_text"> Preview for p </p>  
	    
	    <h2>Check with your texts</h2>
	    
	    <textarea id="myTxt" cols="50" rows="2">Your Custom Text</textarea>
	    	
    </div>

    <?php
}
function x2_google_fonts_list() {
	 $options = (array) get_option( 'tk_google_fonts_options' );

    if ( isset( $options['selected_fonts'] ) )
        $selected_fonts = $options['selected_fonts'];

    ?>
   
	<div class="display_selected_fonts">

		
		<ul id="selected-fonts">
	  	    <?php 
	  	    if(isset($selected_fonts)){
		  	    foreach( $selected_fonts as $key => $selected_font ):
					echo '<li class="'.$selected_font.'">
							<p style="font-family:'.$selected_font.'">'.$selected_font.'<p>
							<a class="dele_form" id="'.$selected_font.'" href="'.$selected_font.'">
							<b>Delete</b>
							</a>
						</li>';
		        endforeach;	
	  	    }
	  	    ?>
	  </ul>  
	</div>
<?php }
/**
 * Ajax call back function to add a form element
 *
 * @package BuddyForms
 * @since 0.1-beta
 */
function google_fonts_add_font(){
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	$tk_google_fonts_options['selected_fonts'][sanitize_title($_POST['google_font_name'])] = sanitize_title($_POST['google_font_name']);
		
	update_option("tk_google_fonts_options", $tk_google_fonts_options);

	die();
}
add_action( 'wp_ajax_google_fonts_add_font', 'google_fonts_add_font' );
add_action( 'wp_ajax_nopriv_google_fonts_add_font', 'google_fonts_add_font' );

/**
 * Ajax call back function to delete a form element
 *
 * @package BuddyForms
 * @since 0.1-beta
 */
function google_fonts_delete_font(){
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );
    
	update_option("tk_google_fonts_options", $tk_google_fonts_options);
    die();
}
add_action('wp_ajax_google_fonts_delete_font', 'google_fonts_delete_font');
add_action('wp_ajax_nopriv_google_fonts_delete_font', 'google_fonts_delete_font');

add_action('admin_enqueue_scripts', 'google_fonts_js');
function google_fonts_js(){

	wp_enqueue_script('google_fonts_admin_js', plugins_url('/js/admin.js', __FILE__));
    wp_register_script('jquery-fontselect', plugins_url('/js/jquery.fontselect.min.js', __FILE__), false,'1.6');
    wp_enqueue_script('jquery-fontselect');
}

add_action('admin_enqueue_scripts', 'google_fonts_css');
function google_fonts_css(){
	wp_enqueue_style('jquery-fontselect-css', plugins_url('/css/fontselect.css', __FILE__));	
}	
?>