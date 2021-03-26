<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

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

	
	if ( ! is_dir( WP_PLUGIN_DIR . '/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name ) ) {
		wp_mkdir_p( WP_PLUGIN_DIR . '/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name );
	}

	$fp = fopen ( WP_PLUGIN_DIR . '/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name . '/' . $google_font_name . '.css', 'w+' );
	$tk_userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36';
    $tk_family_url = 'https://fonts.googleapis.com/css2?family=' . $google_font_name;
    $ch = curl_init();
	$timeout = 5;
    curl_setopt( $ch, CURLOPT_USERAGENT, $tk_userAgent );
    curl_setopt( $ch, CURLOPT_URL, $tk_family_url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	$tk_font_data = curl_exec( $ch );
	$tk_search_url = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{3,4}(\/\S[^)]*)?/";

	if( preg_match_all( $tk_search_url, $tk_font_data, $tk_font_fileinfo ) ) {
        $tk_font_urls = $tk_font_fileinfo[0];
        $tk_hosted_fonts = $tk_font_data;
		$tk_main_url = home_url( '/' );
        $i = 0;
        foreach( $tk_font_urls as $googlefonts_urls ){
          $tk_google_urls = $tk_font_urls[$i];
          $ff = fopen ( WP_PLUGIN_DIR . '/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name . '/' . $google_font_name . '-' . $i . '.woff2', 'w+' );
          $ci = curl_init();
          curl_setopt( $ci, CURLOPT_USERAGENT, $tk_userAgent );
          curl_setopt( $ci, CURLOPT_URL, $tk_font_urls[ $i ] );
          curl_setopt( $ci, CURLOPT_RETURNTRANSFER, 1 );
          curl_setopt( $ci, CURLOPT_CONNECTTIMEOUT, 5 );
          $tk_new_fontfamily_urls = curl_exec( $ci );
          fwrite( $ff, $tk_new_fontfamily_urls );
    	  curl_close( $ci );
          fclose( $ff ); 
          $tk_hosted_fonts = str_replace( $tk_google_urls, $tk_main_url . 'wp-content/plugins/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name . '/' . $google_font_name . '-' . $i .'.woff2', $tk_hosted_fonts);
          $i++;
          
        }
		fwrite( $fp, $tk_hosted_fonts );
		curl_close( $ch );
		fclose( $fp ); 
    }

	die();

}

/**
 * Ajax call back function to delete a form element
 *
 * @author Sven Lehnert
 * @package TK Google Fonts
 * @since 1.0
 */

add_action( 'wp_ajax_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font' );
add_action( 'wp_ajax_nopriv_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font' );

function tk_google_fonts_delete_font(){
	$google_font_name = $_POST['google_font_name'];
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );
	$direc= WP_PLUGIN_DIR . '/tk-google-fonts-premium/includes/resources/my-fonts/' . $google_font_name;
	
	if( is_dir( $direc ) ) {
        WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->rmdir( $direc, true );
    }
	

	update_option( "tk_google_fonts_options", $tk_google_fonts_options );
    die();

}

/* Adding the admin notice */

if( get_option( 'tk_dismiss_notice' ) != true ) {
    add_action( 'admin_notices', 'add_dismissible' );
}

function add_dismissible() {
    
    echo '<div class="notice notice-error tk-dismiss-notice is-dismissible">
          <p><strong>GOOGLE FONTS ARE AGAINST THE EUROPE LOW.</strong></p>
		  <p>Get our <a href="themes.php?page=tk-google-fonts-options-pricing" class="current" aria-current="page">latest pro version with GDPR Compliance</a> to stay save and avoid paying costly warning letter.</p>
      	  </div>';
}


