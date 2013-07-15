<?php
/*
 Plugin Name: TK Google Fonts
 Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 Description: Google Fonts UI for WordPress Themes
 Version: 1.0
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
 Licence: GPLv3
 Network: true
 */
 
/* 
 * This is the ThemeKraft Google Fonts Managing Plugin
 * Thanks goes to Konstantin Kovshenin for his nice tutorial. 
 * http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 * It was my starting point and makes developing easy ;-)

 * Big thanks goes also to tommoor for his jquery fontselector plugin. https://github.com/tommoor/fontselect-jquery-plugin
 * I only needed to put this together and create an admin UI to manage the fonts.
 * 
 */


/**
 * Adding the Admin Page
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
add_action( 'admin_menu', 'x2_google_fonts_admin_menu' );
function x2_google_fonts_admin_menu() {
    add_theme_page( 'TK Google Fonts', 'TK Google Fonts', 'edit_theme_options', 'x2-google-fonts-options', 'x2_google_fonts_screen' );
}


/**
 * The Admin Page
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function x2_google_fonts_screen() { ?>

    <div class="wrap">

        <div id="icon-themes" class="icon32"><br></div>
        <h2>Google Fonts Setup</h2>

        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'x2-google-fonts-setup' ); ?>
            <?php do_settings_sections( 'x2-google-fonts-setup' ); ?>
            <?php //submit_button(); ?>
        </form>

    </div><?php

}


/**
 * Register the admin settings
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
add_action( 'admin_init', 'x2_google_fonts_register_admin_settings' );
function x2_google_fonts_register_admin_settings() {
    register_setting( 'x2-google-fonts-setup', 'x2-google-fonts-setup' );
    // Settings fields and sections
    add_settings_section( 'section_typography', '', 'x2_google_fonts_typography', 'x2-google-fonts-setup' );
    add_settings_field( 'primary-font', '<b>Add Google Font</b>', 'x2_google_fonts_field_font', 'x2-google-fonts-setup', 'section_typography' );
    add_settings_field( 'primary-list', '<b>Manage Font</b>', 'x2_google_fonts_list', 'x2-google-fonts-setup', 'section_typography' );
}


/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function x2_google_fonts_typography() {
    echo '<p><i>Please keep in mind that every font will slow down your site a bit more. <br>
			If you use to many fonts you will have a slow siteload and that\'s also bad for SEO. Best is to use 1-2 Fonts.</i></p><br>';
}


/**
 * The font selector and preview screen
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function x2_google_fonts_field_font() {
    $options = (array) get_option( 'tk_google_fonts_options' );

    if ( isset( $options['selected_fonts'] ) )
        $selected_fonts = $options['selected_fonts'];

    ?>
     
    <div id="google_fonts_selecter">
        
        <div class="input-wrap">
        	<input id="font" type="text" />
        	<input type="text" id="myTxt" placeholder="Test your custom text here!" />
        	<input type="button" value="Add Font" name="add_google_font" class="button add_google_font btn" />
        </div>
	     
	    <div class="font-preview-screen">
		    <h2 class="add_text">Preview for h2 titles </h2>
		    <h3 class="add_text">Preview for h3 subtitles </h3>
		    <p class="add_text">Preview for p text. This is how it looks with more and smaller or italic text. <br>
		    	How about <b>one more coffee?</b> or maybe some <i>fast looking italic text?</i></p>  
	    </div>
	    	
    </div>

    <?php
}


/**
 * Google fonts list
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function x2_google_fonts_list() {
	 $options = (array) get_option( 'tk_google_fonts_options' );

// echo '<pre>';
// print_r($options);
// echo '</pre>';

    if ( isset( $options['selected_fonts'] ) )
        $selected_fonts = $options['selected_fonts'];

    ?>
   
	<div class="display_selected_fonts">
		
		<ul id="selected-fonts">

	  	    <?php 

	  	    if( isset( $selected_fonts ) ) {
		  	    foreach( $selected_fonts as $key => $selected_font ):
					$font_family =  str_replace("+", " ", $selected_font);
					echo '<li class="'.$selected_font.'">
							<p style="font-family:'.$font_family.'">'.$font_family.'<p>
							<a class="dele_form" id="'.$selected_font.'" href="'.$selected_font.'">
							<b>Delete</b>
							</a>
						</li>';
		        endforeach;	
	  	    } ?>

	  </ul>  

	</div><?php 

}


/**
 * Ajax call back function to add a form element
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function google_fonts_add_font(){
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	$tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] = $_POST['google_font_name'];
		
	update_option("tk_google_fonts_options", $tk_google_fonts_options);

	die();
}
add_action( 'wp_ajax_google_fonts_add_font', 'google_fonts_add_font' );
add_action( 'wp_ajax_nopriv_google_fonts_add_font', 'google_fonts_add_font' );


/**
 * Ajax call back function to delete a form element
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function google_fonts_delete_font(){
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );
    
	update_option("tk_google_fonts_options", $tk_google_fonts_options);
    die();
}
add_action('wp_ajax_google_fonts_delete_font', 'google_fonts_delete_font');
add_action('wp_ajax_nopriv_google_fonts_delete_font', 'google_fonts_delete_font');


/** 
 * Enqueue JS
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
add_action('admin_enqueue_scripts', 'google_fonts_js');
function google_fonts_js(){
	wp_enqueue_script('google_fonts_admin_js', plugins_url('/js/admin.js', __FILE__));
    wp_register_script('jquery-fontselect', plugins_url('/js/jquery.fontselect.min.js', __FILE__), false,'1.6');
    wp_enqueue_script('jquery-fontselect');
	
		$x2google_fonts_options = get_option('tk_google_fonts_options');
	 foreach ($x2google_fonts_options['selected_fonts'] as $key => $x2google_font) {
		wp_register_style( 'font-style-'.$x2google_font, 'http://fonts.googleapis.com/css?family='.$x2google_font );
		wp_enqueue_style( 'font-style-'.$x2google_font );
	}
	
}

// Includes the necessary css
add_action('wp_enqueue_scripts', 'google_fonts_enqueue_fonts' );
function google_fonts_enqueue_fonts() {
    
	$x2google_fonts_options = get_option('tk_google_fonts_options');
	 foreach ($x2google_fonts_options['selected_fonts'] as $key => $x2google_font) {
		wp_register_style( 'font-style-'.$x2google_font, 'http://fonts.googleapis.com/css?family='.$x2google_font );
		wp_enqueue_style( 'font-style-'.$x2google_font );
	}
        
        
}

/** 
 * Enqueue CSS
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
add_action('admin_enqueue_scripts', 'google_fonts_css');
function google_fonts_css(){
	wp_enqueue_style('jquery-fontselect-css', plugins_url('/css/fontselect.css', __FILE__));
	wp_enqueue_style('tk-google-fonts-css', plugins_url('/css/tk-google-fonts.css', __FILE__));	
}	

?>