<?php


/**
 * Do you want to use the WordPress Customizer? This is the option to turn on/off the WordPress Customizer Support.
 *
 * @author Sven Lehnert & Konrad Sroka
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


  $wp_customize->add_panel( 'tk_google_fonts_settings', array(
    'priority'          => 9999,
    'capability'        => 'edit_theme_options',
    'theme_supports'    => '',
    'title'             => 'TK Google Fonts',
  ) );

  $wp_customize->add_section( 'tk_headings', array(
		'title'             => 'Headings - General',
		'priority'          => 20,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h1', array(
		'title'             => 'H1 Heading',
		'priority'          => 21,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h2', array(
		'title'             => 'H2 Heading',
		'priority'          => 22,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h3', array(
		'title'             => 'H3 Heading',
		'priority'          => 23,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h4', array(
		'title'             => 'H4 Heading',
		'priority'          => 24,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h5', array(
		'title'             => 'H5 Heading',
		'priority'          => 25,
    'panel'             => 'tk_google_fonts_settings'
	) );

	$wp_customize->add_section( 'tk_h6', array(
		'title'             => 'H6 Heading',
		'priority'          => 26,
    'panel'             => 'tk_google_fonts_settings'
	) );

  $wp_customize->add_section( 'tk_body', array(
		'title'             => 'Body Text',
		'priority'          => 40,
    'panel'             => 'tk_google_fonts_settings'
	) );

  $wp_customize->add_section( 'tk_blockquote', array(
		'title'             => 'Blockquotes',
		'priority'          => 60,
    'panel'             => 'tk_google_fonts_settings'
	) );

  $wp_customize->add_section( 'tk_post_fonts', array(
		'title'             => 'Posts',
		'priority'          => 80,
    'panel'             => 'tk_google_fonts_settings'
	) );

  $wp_customize->add_section( 'tk_page_fonts', array(
    'title'             => 'Pages',
    'priority'          => 100,
    'panel'             => 'tk_google_fonts_settings'
  ) );


	// Headings

  $wp_customize->add_setting( 'headings_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'headings_font', array(
		'label'             => 'Headings Font Family',
		'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_headings',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'headings_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'headings_font_color', array(
		'label'             => 'Headings Font Color',
		'section'           => 'tk_headings',
		'settings'          => 'headings_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'headings_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'headings_font_weight', array(
		'label'             => 'Headings Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_headings',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );



	// H1

  $wp_customize->add_setting( 'h1_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h1_font', array(
		'label'             => 'H1 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h1',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h1_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h1_font_color', array(
		'label'             => 'H1 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h1',
		'settings'          => 'h1_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h1_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h1_font_weight', array(
		'label'             => 'H1 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h1',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h1_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h1_font_size_sm', array(
		'label'             => 'H1 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h1',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h1_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h1_font_size_md', array(
		'label'             => 'H1 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h1',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h1_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h1_font_size_lg', array(
		'label'             => 'H1 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h1',
		'type'              => 'text',
		'priority'		      => 18,
	) );



	// H2

  $wp_customize->add_setting( 'h2_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h2_font', array(
		'label'             => 'H2 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h2',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h2_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h2_font_color', array(
		'label'             => 'H2 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h2',
		'settings'          => 'h2_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h2_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h2_font_weight', array(
		'label'             => 'H2 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h2',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h2_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h2_font_size_sm', array(
		'label'             => 'H2 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h2',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h2_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h2_font_size_md', array(
		'label'             => 'H2 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h2',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h2_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h2_font_size_lg', array(
		'label'             => 'H2 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h2',
		'type'              => 'text',
		'priority'		      => 18,
	) );




	// H3

  $wp_customize->add_setting( 'h3_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h3_font', array(
		'label'             => 'H3 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h3',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h3_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h3_font_color', array(
		'label'             => 'H3 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h3',
		'settings'          => 'h3_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h3_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h3_font_weight', array(
		'label'             => 'H3 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h3',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h3_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h3_font_size_sm', array(
		'label'             => 'H3 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h3',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h3_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h3_font_size_md', array(
		'label'             => 'H3 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h3',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h3_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h3_font_size_lg', array(
		'label'             => 'H3 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h3',
		'type'              => 'text',
		'priority'		      => 18,
	) );




	// H4

  $wp_customize->add_setting( 'h4_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h4_font', array(
		'label'             => 'H4 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h4',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h4_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h4_font_color', array(
		'label'             => 'H4 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h4',
		'settings'          => 'h4_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h4_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h4_font_weight', array(
		'label'             => 'H4 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h4',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h4_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h4_font_size_sm', array(
		'label'             => 'H4 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h4',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h4_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h4_font_size_md', array(
		'label'             => 'H4 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h4',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h4_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h4_font_size_lg', array(
		'label'             => 'H4 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h4',
		'type'              => 'text',
		'priority'		      => 18,
	) );





	// H5

  $wp_customize->add_setting( 'h5_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h5_font', array(
		'label'             => 'H5 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h5',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h5_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h5_font_color', array(
		'label'             => 'H5 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h5',
		'settings'          => 'h5_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h5_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h5_font_weight', array(
		'label'             => 'H5 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h5',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h5_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h5_font_size_sm', array(
		'label'             => 'H5 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h5',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h5_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h5_font_size_md', array(
		'label'             => 'H5 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h5',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h5_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h5_font_size_lg', array(
		'label'             => 'H5 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h5',
		'type'              => 'text',
		'priority'		      => 18,
	) );




	// H6

  $wp_customize->add_setting( 'h6_font', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h6_font', array(
		'label'             => 'H6 Font Family',
    'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_h6',
		'type'              => 'select',
		'priority'		      => 10,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'h6_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h6_font_color', array(
		'label'             => 'H6 Font Color',
		// 'description'       => 'Leave blank to fallback to your current theme\'s CSS.',
		'section'           => 'tk_h6',
		'settings'          => 'h6_font_color',
    'priority'		      => 12,
	) ) );

  $wp_customize->add_setting( 'h6_font_weight', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h6_font_weight', array(
		'label'             => 'H6 Font Weight',
		'description'       => 'Auto = fallback to your current theme\'s CSS.',
		'section'           => 'tk_h6',
		'type'              => 'radio',
		'priority'		      => 14,
		'choices'           => array(
      'auto'     => 'auto',
			'normal'   => 'normal',
			'bold'     => 'bold',
		),
	) );

  $wp_customize->add_setting( 'h6_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h6_font_size_sm', array(
		'label'             => 'H6 Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h6',
		'type'              => 'text',
		'priority'		      => 16,
	) );

	$wp_customize->add_setting( 'h6_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h6_font_size_md', array(
		'label'             => 'H6 Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h6',
		'type'              => 'text',
		'priority'		      => 17,
	) );

	$wp_customize->add_setting( 'h6_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'h6_font_size_lg', array(
		'label'             => 'H6 Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_h6',
		'type'              => 'text',
		'priority'		      => 18,
	) );





	// $wp_customize->add_setting( 'h1_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h1_font', array(
	// 	'label'   => 'H1 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 20,
	// 	'choices'    => $tk_google_font_array
	// ) );
  //
 // 	$wp_customize->add_setting( 'h2_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h2_font', array(
	// 	'label'   => 'H2 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 20,
	// 	'choices'    => $tk_google_font_array
	// ) );
  //
 // 	$wp_customize->add_setting( 'h3_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h3_font', array(
	// 	'label'   => 'H3 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 30,
	// 	'choices'    => $tk_google_font_array
	// ) );
  //
 // 	$wp_customize->add_setting( 'h4_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h4_font', array(
	// 	'label'   => 'H4 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 40,
	// 	'choices'    => $tk_google_font_array
	// ) );
  //
 // 	$wp_customize->add_setting( 'h5_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h5_font', array(
	// 	'label'   => 'H5 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 50,
	// 	'choices'    => $tk_google_font_array
	// ) );
  //
 // 	$wp_customize->add_setting( 'h6_font', array(
	// 	'default'        => 'default',
	// 	'transport'   => 'postMessage',
	// ) );
  //
	// $wp_customize->add_control( 'h6_font', array(
	// 	'label'   => 'H6 Heading',
	// 	'section' => 'tk_google_fonts_settings',
	// 	'type'    => 'select',
	// 	'priority'		=> 60,
	// 	'choices'    => $tk_google_font_array
	// ) );


	// Body Text

 	$wp_customize->add_setting( 'body_text', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'body_text', array(
		'label'             => 'Body Font (text, paragraph)',
		'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_body',
		'type'              => 'select',
		'priority'		      => 70,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'body_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_font_color', array(
		'label'             => __( 'Body Font Color', 'mytheme' ),
		'section'           => 'tk_body',
		'settings'          => 'body_font_color',
    'priority'		      => 72,
	) ) );

	$wp_customize->add_setting( 'body_font_size_sm', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'body_font_size_sm', array(
		'label'             => 'Body Font Size - Mobile',
		'description'       => 'on screens smaller than 768px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_body',
		'type'              => 'text',
		'priority'		      => 100,
	) );

	$wp_customize->add_setting( 'body_font_size_md', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'body_font_size_md', array(
		'label'             => 'Body Font Size - Pad Devices',
		'description'       => 'on screens from 768px to 1199px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_body',
		'type'              => 'text',
		'priority'		      => 120,
	) );

	$wp_customize->add_setting( 'body_font_size_lg', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'body_font_size_lg', array(
		'label'             => 'Body Font Size - Large Screens',
		'description'       => 'on screens from 1200px. Write including unit, for example "14px" or "2em"',
		'section'           => 'tk_body',
		'type'              => 'text',
		'priority'		      => 140,
	) );




	// Blockquotes

 	$wp_customize->add_setting( 'blockquotes', array(
		'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'blockquotes', array(
		'label'             => 'Blockquotes',
		'description'       => 'Add the Google Fonts you would like to use <a href="#">in your settings</a> first.',
		'section'           => 'tk_blockquote',
		'type'              => 'select',
		'priority'		      => 80,
		'choices'           => $tk_google_font_array
	) );

  $wp_customize->add_setting( 'blockquote_font_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blockquote_font_color', array(
		'label'             => __( 'Blockquote Font Color', 'mytheme' ),
		'section'           => 'tk_blockquote',
		'settings'          => 'blockquote_font_color',
    'priority'		      => 82,
	) ) );

  $wp_customize->add_setting( 'blockquote_bg_color', array(
    'sanitize_callback' => 'sanitize_hex_color',
    'default'           => 'default',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blockquote_bg_color', array(
		'label'             => __( 'Blockquote Background Color', 'tk-google-fonts' ),
		'section'           => 'tk_blockquote',
		'settings'          => 'blockquote_bg_color',
    'priority'		      => 84,
	) ) );


  // $wp_customize->add_setting( 'tk_post_title', array(
	// 	'default'           => 'default',
	// 	'transport'         => 'postMessage',
	// ) );
	//
	// $wp_customize->add_control( 'tk_post_title', array(
	// 	'label'             => 'Post Title Font Family',
	// 	'section'           => 'tk_post_fonts',
	// 	'type'              => 'select',
	// 	'priority'		      => 10,
	// 	'choices'           => $tk_google_font_array
	// ) );
	//
  // $wp_customize->add_setting( 'tk_post_title_color', array(
  //   'sanitize_callback' => 'sanitize_hex_color',
  //   'default'           => 'default',
  //   'transport'         => 'postMessage',
  // ) );
	//
  // $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tk_post_title_color', array(
  //   'label'             => __( 'Post Title Color', 'tk-google-fonts' ),
  //   'section'           => 'tk_post_fonts',
  //   'settings'          => 'tk_post_title_color',
  //   'priority'		      => 20,
  // ) ) );
	//
  // $wp_customize->add_setting( 'tk_page_title', array(
	// 	'default'           => 'default',
	// 	'transport'         => 'postMessage',
	// ) );
	//
	// $wp_customize->add_control( 'tk_page_title', array(
	// 	'label'             => 'Page Title Font Family',
	// 	'section'           => 'tk_page_fonts',
	// 	'type'              => 'select',
	// 	'priority'		      => 10,
	// 	'choices'           => $tk_google_font_array
	// ) );
	//
  // $wp_customize->add_setting( 'tk_page_title_color', array(
  //   'sanitize_callback' => 'sanitize_hex_color',
  //   'default'           => 'default',
  //   'transport'         => 'postMessage',
  // ) );
	//
  // $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tk_page_title_color', array(
  //   'label'             => __( 'Page Title Color', 'tk-google-fonts' ),
  //   'section'           => 'tk_page_fonts',
  //   'settings'          => 'tk_page_title_color',
  //   'priority'		      => 20,
  // ) ) );



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

	?><style type="text/css">
						<?php


		// Headings

		if( get_theme_mod('headings_font', '') || get_theme_mod('headings_font_color', '') || get_theme_mod('headings_font_weight', '') && get_theme_mod('headings_font_weight', '') != 'auto' ):
			echo 'h1, h2, h3, h4, h5, h6 { ';
				if( get_theme_mod('headings_font', '') )
					echo 'font-family: '. get_theme_mod('headings_font') . '; ';
				if( get_theme_mod('headings_font_color', '') )
					echo 'color: '. get_theme_mod('headings_font_color') . '; ';
				if( get_theme_mod('headings_font_weight', '') && get_theme_mod('headings_font_weight', '') != 'auto' )
					echo 'font-weight: '. get_theme_mod('headings_font_weight') . '; ';
			echo '} ';
		endif;


		// H1

		if( get_theme_mod('h1_font', '') || get_theme_mod('h1_font_color', '') || get_theme_mod('h1_font_weight', '') && get_theme_mod('h1_font_weight', '') != 'auto' ):
			echo 'h1 { ';
				if( get_theme_mod('h1_font', '') )
					echo 'font-family: '. get_theme_mod('h1_font') . '; ';
				if( get_theme_mod('h1_font_color', '') )
					echo 'color: '. get_theme_mod('h1_font_color') . '; ';
				if( get_theme_mod('h1_font_weight', '') && get_theme_mod('h1_font_weight', '') != 'auto' )
					echo 'font-weight: '. get_theme_mod('h1_font_weight') . '; ';
			echo '} ';
		endif;

		if( get_theme_mod('h1_font_size_sm', '') )
				echo '
						@media (max-width: 767px) {
							h1 {
								font-size: '. get_theme_mod('h1_font_size_sm') . ';
							}
						} ';

		if( get_theme_mod('h1_font_size_md', '') )
				echo '
						@media (min-width: 768px) and (max-width: 1199px) {
							h1 {
								font-size: '. get_theme_mod('h1_font_size_md') . ';
							}
						} ';

		if( get_theme_mod('h1_font_size_lg', '') )
				echo '
						@media (min-width: 1200px) {
							h1 {
								font-size: '. get_theme_mod('h1_font_size_lg') . ';
							}
						} ';


		// H2

		if( get_theme_mod('h2_font', '') || get_theme_mod('h2_font_color', '') || get_theme_mod('h2_font_weight', '') && get_theme_mod('h2_font_weight', '') != 'auto' ):
			echo 'h2 { ';
				if( get_theme_mod('h2_font', '') )
					echo 'font-family: '. get_theme_mod('h2_font') . '; ';
				if( get_theme_mod('h2_font_color', '') )
					echo 'color: '. get_theme_mod('h2_font_color') . '; ';
				if( get_theme_mod('h2_font_weight', '') && get_theme_mod('h2_font_weight', '') != 'auto' )
					echo 'font-weight: '. get_theme_mod('h2_font_weight') . '; ';
			echo '} ';
		endif;

		if( get_theme_mod('h2_font_size_sm', '') )
				echo '
						@media (max-width: 767px) {
							h2 {
								font-size: '. get_theme_mod('h2_font_size_sm') . ';
							}
						} ';

		if( get_theme_mod('h2_font_size_md', '') )
				echo '
						@media (min-width: 768px) and (max-width: 1199px) {
							h2 {
								font-size: '. get_theme_mod('h2_font_size_md') . ';
							}
						} ';

		if( get_theme_mod('h2_font_size_lg', '') )
				echo '
						@media (min-width: 1200px) {
							h2 {
								font-size: '. get_theme_mod('h2_font_size_lg') . ';
							}
						} ';



		// H3

		if( get_theme_mod('h3_font', '') || get_theme_mod('h3_font_color', '') || get_theme_mod('h3_font_weight', '') && get_theme_mod('h3_font_weight', '') != 'auto' ):
			echo 'h3 { ';
				if( get_theme_mod('h3_font', '') )
					echo 'font-family: '. get_theme_mod('h3_font') . '; ';
				if( get_theme_mod('h3_font_color', '') )
					echo 'color: '. get_theme_mod('h3_font_color') . '; ';
				if( get_theme_mod('h3_font_weight', '') && get_theme_mod('h3_font_weight', '') != 'auto' )
					echo 'font-weight: '. get_theme_mod('h3_font_weight') . '; ';
			echo '} ';
		endif;

		if( get_theme_mod('h3_font_size_sm', '') )
				echo '
						@media (max-width: 767px) {
							h3 {
								font-size: '. get_theme_mod('h3_font_size_sm') . ';
							}
						} ';

		if( get_theme_mod('h3_font_size_md', '') )
				echo '
						@media (min-width: 768px) and (max-width: 1199px) {
							h3 {
								font-size: '. get_theme_mod('h3_font_size_md') . ';
							}
						} ';

		if( get_theme_mod('h3_font_size_lg', '') )
				echo '
						@media (min-width: 1200px) {
							h3 {
								font-size: '. get_theme_mod('h3_font_size_lg') . ';
							}
						} ';
		}



		if( get_theme_mod('h4_font', '') )
			echo 'h4 { font-family: '. get_theme_mod('h4_font') . '; } ';

		if( get_theme_mod('h5_font', '') )
			echo 'h5 { font-family: '. get_theme_mod('h5_font') . '; } ';

		if( get_theme_mod('h6_font', '') )
			echo 'h6 { font-family: '. get_theme_mod('h6_font') . '; } ';

		if( get_theme_mod('body_text', '') )
			echo 'body, p { font-family: '. get_theme_mod('body_text') . '; } ';

		if( get_theme_mod('blockquotes', '') )
			echo 'blockquote, blockquote p { font-family: '. get_theme_mod('blockquotes') . '; } ';

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
