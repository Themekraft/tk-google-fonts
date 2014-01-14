<?php


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
	
	 
    ?><b>Turn off Customizer: </b> <input id='checkbox' name='tk_google_fonts_options[customizer_disabled]' type='checkbox' value='1' <?php checked( $customizer_disabled, 1  ) ; ?> /><?php 
	
	submit_button(); 

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

add_action( 'init', 'tk_google_fonts_customizer_init' );

function tk_google_fonts_customizer_init(){
	
	add_action( 'customize_register', 'tk_google_fonts_customize_register' );
	
}

/**
 * Here comes the resulting CSS output for the frontend!
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */
 
add_action( 'wp_head', 'tk_google_fonts_customize_css',99999);

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

/**
 * WordPress Customizer Preview init
 * 
 * @author Sven Lehnert 
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'customize_preview_init', 'tk_google_fonts_customize_preview_init');

function tk_google_fonts_customize_preview_init(){
	
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	
	if(isset( $tk_google_fonts_options['customizer_disabled']))
	 	return;
	
	wp_enqueue_script(
		'google_fonts_customize_preview_js',
		plugins_url('/js/customizer.js', __FILE__),
		array( 'jquery','customize-preview' ),
		'',
		true
	);
	
}
