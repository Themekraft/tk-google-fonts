<?php

/**
 * Plugin Name: TK Google Fonts
 * Plugin URI:  http://themekraft.com/shop/product-category/themes/extentions/
 * Description: Google Fonts UI for WordPress Themes
 * Version: 1.0
 * Author: Sven Lehnert
 * Author URI: http://themekraft.com/members/svenl77/
 * Licence: GPLv3
 * Network: true
 *
 *
 * This is the ThemeKraft Google Fonts WordPress Plugin
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
 * Next we added the integration into the WordPress Customizer ;)
 * 
 * Have fun!
 * 
 */


/**
 * Adding the Admin Page
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
add_action( 'admin_menu', 'tk_google_fonts_admin_menu' );
function tk_google_fonts_admin_menu() {
	define('TK_GOOGLE_FONTS', '1.0' );
    add_theme_page( 'TK Google Fonts', 'TK Google Fonts', 'edit_theme_options', 'tk-google-fonts-options', 'tk_google_fonts_screen' );	
}


/**
 * The Admin Page
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function tk_google_fonts_screen() { ?>

    <div class="wrap">

        <div id="icon-themes" class="icon32"><br></div>
        <h2>Google Fonts Setup</h2>
		
		<p style="margin: 30px 0; font-size: 15px;">
		    Need help? <a class="button secondary" href="http://support.themekraft.com/" target="_blank">Documentation</a> <a class="button secondary" href=/#" target="_blank" title="Submit an email support ticket">Ask Question</a>
		    <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_blank">Themekraft</a>.</span>
        </p>
		
        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'tk_google_fonts_options' ); ?>
            <?php do_settings_sections( 'tk_google_fonts_options' ); ?>
            
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
add_action( 'admin_init', 'tk_google_fonts_register_admin_settings' );
function tk_google_fonts_register_admin_settings() {
    register_setting( 'tk_google_fonts_options', 'tk_google_fonts_options' );
    
    // Settings fields and sections
    add_settings_section(	'section_typography'	, ''							, 'tk_google_fonts_typography'	, 'tk_google_fonts_options' );
	
	add_settings_field(		'primary-font'			, '<b>Add Google Font</b>'		, 'tk_google_fonts_field_font'	, 'tk_google_fonts_options'	, 'section_typography' );
    add_settings_field(		'primary-list'			, '<b>Manage Font</b>'			, 'tk_google_fonts_list'		, 'tk_google_fonts_options' , 'section_typography' );
    add_settings_field(		'customizer_disabled'	, '<b>Use the Customizer</b>'	, 'tk_google_fonts_customizer'	, 'tk_google_fonts_options' , 'section_typography' );

}


/**
 * Important notice on top of the screen
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function tk_google_fonts_typography() {
    echo '<p><i>Please keep in mind that every font will slow down your site a bit more. <br>
			If you use to many fonts you will have a slow siteload and that\'s also bad for SEO. 
			Best is to use 1-2 Fonts.</i></p><br>';
}


/**
 * The font selector and preview screen
 * 
 * @author Sven Lehnert
 * @package TK Google Fonts 
 * @since 1.0
 */ 
function tk_google_fonts_field_font() {
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
function tk_google_fonts_list() {
	 $options = (array) get_option( 'tk_google_fonts_options' );

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
					echo '<input type="hidden" name="tk_google_fonts_options[selected_fonts][' . $key . ']" value="' . $selected_font . '" />';
				endforeach;	
			}
			?>
		</ul>  
	</div>
	<?php 
}

/**
 * Ajax call back function to add a form element
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_add_font($google_font_name){
	
	if(isset($_POST['google_font_name']))
		$google_font_name = $_POST['google_font_name'];
	
	if(empty($google_font_name))
		return;
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	$tk_google_fonts_options['selected_fonts'][$google_font_name] = $google_font_name;
		
	update_option("tk_google_fonts_options", $tk_google_fonts_options);

	die();
}
add_action( 'wp_ajax_tk_google_fonts_add_font', 'tk_google_fonts_add_font' );
add_action( 'wp_ajax_nopriv_tk_google_fonts_add_font', 'tk_google_fonts_add_font' );


/**
 * Ajax call back function to delete a form element
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_delete_font(){
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );
    
	update_option("tk_google_fonts_options", $tk_google_fonts_options);
    die();
}
add_action('wp_ajax_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');
add_action('wp_ajax_nopriv_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');


/**
 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.   
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_customizer(){ ?>
	
	<h3>Use the WordPress Theme Customizer</h3>

	<p>You can define the use of Google fonts in the Theme Customizer. </p>
		
	<p><a href="<?php echo get_admin_url(); ?>customize.php"  class="button-primary">Go to the Customizer</a></p>
	
	<br>
		
	<h3>Turn off Customizer Support</h3>
	
	<p>
		If your theme supports TK Google Fonts or you use the Google fonts in your CSS, keep in mind that the TK Google Fonts customizer settings are stronger than the rest of the site CSS and will overwrite your other settings (except you make a very strong CSS).
		
		If you already use TK Google Fonts in your themes options or CSS you might want to deactivate the Customizer Support.
	</p>	
	
	<?php 
	 $options = get_option( 'tk_google_fonts_options' );
	 
	 $customizer_disabled = 0;
	 if(isset( $options['customizer_disabled']))
	 	 $customizer_disabled = $options['customizer_disabled'];
	
	 
    	?> <b>Turn off Customizer: </b> <input id='checkbox' name='tk_google_fonts_options[customizer_disabled]' type='checkbox' value='1' <?php checked( $customizer_disabled, 1  ) ; ?> />
	
	<?php submit_button(); ?>
	
	
	<?php
}

/**
 * Registering for the WordPress Customizer
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_customize_register( $wp_customize ) {

	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	 if(isset( $tk_google_fonts_options['customizer_disabled']))
	 	return;
	
	$tk_selected_fonts = $tk_google_fonts_options['selected_fonts'];
	
	$tk_google_font_array = Array();
	
	$tk_google_font_array['none'] = '';
	
	if(isset($tk_selected_fonts)){
		foreach ($tk_selected_fonts as $key => $tk_selected_font) {
			$tk_google_font_string = str_replace("+", " ", $tk_selected_font);
			$tk_google_font_array[$tk_google_font_string] = $tk_google_font_string;
		}
	}
	
	$wp_customize->get_setting( 'h1_font' )->transport = 'postMessage';

	$wp_customize->add_section( 'tk_google_fonts_settings', array(
		'title'          => 'TK Google Fonts',
		'priority'       => 9999,
	) );
 
	$wp_customize->add_setting( 'h1_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h1_font', array(
		'label'   => 'H1 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 10,
		'choices'    => $tk_google_font_array
	) );
 	
 	$wp_customize->add_setting( 'h2_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h2_font', array(
		'label'   => 'H2 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 20,
		'choices'    => $tk_google_font_array
	) );

 	$wp_customize->add_setting( 'h3_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h3_font', array(
		'label'   => 'H3 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 30,
		'choices'    => $tk_google_font_array
	) );

 	$wp_customize->add_setting( 'h4_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h4_font', array(
		'label'   => 'H4 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 40,
		'choices'    => $tk_google_font_array
	) );
	
 	$wp_customize->add_setting( 'h5_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h5_font', array(
		'label'   => 'H5 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 50,
		'choices'    => $tk_google_font_array
	) );

 	$wp_customize->add_setting( 'h6_font', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'h6_font', array(
		'label'   => 'H6 Heading',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 60,
		'choices'    => $tk_google_font_array
	) );

 	$wp_customize->add_setting( 'body_text', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );
 
	$wp_customize->add_control( 'body_text', array(
		'label'   => 'Body Text (body, paragraph)',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 70,
		'choices'    => $tk_google_font_array
	) );
	
 	$wp_customize->add_setting( 'blockquotes', array(
		'default'        => 'default',
		'transport'   => 'postMessage',
	) );

	$wp_customize->add_control( 'blockquotes', array(
		'label'   => 'Blockquotes',
		'section' => 'tk_google_fonts_settings',
		'type'    => 'select',
		'priority'		=> 80,
		'choices'    => $tk_google_font_array
	) );
 
}

/**
 * WordPress Customizer initialization
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_customizer_init(){
	add_action( 'customize_register', 'tk_google_fonts_customize_register' );
}
add_action( 'init', 'tk_google_fonts_customizer_init' );

/**
 * Here comes the resulting CSS output for the frontend!
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_customize_css(){
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	if(isset( $tk_google_fonts_options['customizer_disabled']))
	 	return;
	
	?><style type="text/css"><?php 
	
		if(  get_theme_mod('h1_font', '') != 'none' )
			echo 'h1, h1 a, h1 a:hover { font-family:'. get_theme_mod('h1_font') . '; } ';
		
		if(  get_theme_mod('h2_font', '') != 'none' )
			echo 'h2, h2 a, h2 a:hover { font-family:'. get_theme_mod('h2_font') . '; } ';
		
		if(  get_theme_mod('h3_font', '') != 'none' )
			echo 'h3, h3 a, h3 a:hover { font-family:'. get_theme_mod('h3_font') . '; } ';
		
		if(  get_theme_mod('h4_font', '') != 'none' )
			echo 'h4, h4 a, h4 a:hover { font-family:'. get_theme_mod('h4_font') . '; } ';
		
		if(  get_theme_mod('h5_font', '') != 'none' )
			echo 'h5, h5 a, h5 a:hover { font-family:'. get_theme_mod('h5_font') . '; } ';
		
		if(  get_theme_mod('h6_font', '') != 'none' )
			echo 'h6, h6 a, h6 a:hover { font-family:'. get_theme_mod('h6_font') . '; } ';
		
		if(  get_theme_mod('body_text', '') != 'none' )
			echo 'body, p { font-family:'. get_theme_mod('body_text') . '; } ';
		
		if(  get_theme_mod('blockquotes', '') != 'none' )
			echo 'blockquote, blockquote p, blockquote p a { font-family:'. get_theme_mod('blockquotes') . '; }'; 
	
	?></style>

<?php

}
add_action( 'wp_head', 'tk_google_fonts_customize_css',99999);

/**
 * WordPress Customizer Preview init
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
function tk_google_fonts_customize_preview_init(){
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	if(isset( $tk_google_fonts_options['customizer_disabled']))
	 	return;
	
	wp_enqueue_script(
		'google_fonts_customize_preview_js',
		plugins_url('/js/theme-customize.js', __FILE__),
		array( 'jquery','customize-preview' ),
		'',
		true
	);
}
add_action( 'customize_preview_init', 'tk_google_fonts_customize_preview_init');

/** 
 * Enqueue admin JS and CSS
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
add_action('admin_enqueue_scripts', 'tk_google_fonts_js');
function tk_google_fonts_js(){
	
	wp_enqueue_script('google_fonts_admin_js', plugins_url('/js/admin.js', __FILE__));
	
    wp_register_script('jquery-fontselect', plugins_url('/js/jquery.fontselect.min.js', __FILE__), false,'1.6');
    wp_enqueue_script('jquery-fontselect');
		
	wp_enqueue_style('jquery-fontselect-css', plugins_url('/css/fontselect.css', __FILE__));
	
	wp_enqueue_style('tk-google-fonts-css', plugins_url('/css/tk-google-fonts.css', __FILE__));	
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	if(isset($tk_google_fonts_options['selected_fonts'])){
		foreach ($tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font) {
			wp_register_style( 'font-style-'.$tk_google_font, 'http://fonts.googleapis.com/css?family='.$tk_google_font );
			wp_enqueue_style( 'font-style-'.$tk_google_font );
		}
	}
}

/** 
 * Enqueue JS and CSS for the frontend
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
add_action('wp_enqueue_scripts', 'tk_google_fonts_enqueue_fonts' );
function tk_google_fonts_enqueue_fonts() {
    
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	if( !isset( $tk_google_fonts_options['selected_fonts'] ) )
		return;
	
	foreach ($tk_google_fonts_options['selected_fonts'] as $key => $tk_google_font) {
		wp_register_style( 'font-style-'.$tk_google_font, 'http://fonts.googleapis.com/css?family='.$tk_google_font );
		wp_enqueue_style( 'font-style-'.$tk_google_font );
	}
               
}
?>