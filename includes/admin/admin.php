<?php
/**
 * Adding the Admin Page
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'admin_menu', 'tk_google_fonts_admin_menu' );

function tk_google_fonts_admin_menu() {
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
        <h2>TK Google Fonts Setup</h2>
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
    add_settings_section(	'section_typography'	, ''							, ''	, 'tk_google_fonts_options' );

	add_settings_field(		'primary-font'			, '<h3>1. Add Google Fonts</h3> <i>The plugin loads the Google Fonts automatically from Google.<br><br>
							If the Google Font you are looking for shouldn\'t be available in the Font Selectbox, just type in the name into the text field and then click on "Add Font".<br><br>
        					Just use the name with spaces, no need to find a special slug. The name is enough, we do the rest. ;-) <br><br>
        					You can find all available Google Fonts on the <br><br><a href="http://www.google.com/fonts/" target="blank">Google Fonts Website</a> </i>', 'tk_google_fonts_field_font'	, 'tk_google_fonts_options'	, 'section_typography' );
    add_settings_field(		'primary-list'			, '<h3>2. Manage Google Fonts</h3> <i>Please keep in mind that every font loaded will slow down your site a bit more.
			If you use to many fonts you will have a slow siteload and that\'s also bad for SEO.
			Best is to use only 1-2 Fonts.</i><br><br>'			, 'tk_google_fonts_list'		, 'tk_google_fonts_options' , 'section_typography' );
    add_settings_field(		'customizer_disabled'	, '<h3>3. Apply Google Fonts</h3>'	, 'tk_google_fonts_customizer'	, 'tk_google_fonts_options' , 'section_typography' );

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
    <h3>Choose a font from the font select or just type the name. Then click on "Add Font".</h3>
    <div id="google_fonts_selecter">

        <div class="input-wrap">
        	<input id="font" type="text" />
        	<input type="text" id="new_font" placeholder="Add a not listed font here" />
        	<input type="button" value="Add Font" name="add_google_font" class="button add_google_font btn" />

        </div>

	    <div class="font-preview-screen">
	    	<input type="text" id="myTxt" placeholder="Test your custom preview text here!" />
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
			if( isset( $selected_fonts ) &&  count($selected_fonts) > 0) {
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
			} else {
				echo '<li><b>You have no fonts enqueued right now.</b><br>Select a font above and add it first.</li>';
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

add_action( 'wp_ajax_tk_google_fonts_add_font', 'tk_google_fonts_add_font' );
add_action( 'wp_ajax_nopriv_tk_google_fonts_add_font', 'tk_google_fonts_add_font' );

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

/**
 * Ajax call back function to delete a form element
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action('wp_ajax_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');
add_action('wp_ajax_nopriv_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');

function tk_google_fonts_delete_font(){

	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );

	update_option("tk_google_fonts_options", $tk_google_fonts_options);
    die();

}
