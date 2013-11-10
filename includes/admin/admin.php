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
		<span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>
        <div style="margin: 30px 0 0 0; background: #f4f4f4; padding: 20px; overflow: auto; border-radius: 6px;">

	
			<div style="float: left; overflow: auto; border-right: 1px solid #ddd; padding: 0 20px 0 0;">
				<h3>Get Support.</h3> 
				<p><a class="button secondary" href="https://themekraft.zendesk.com/hc/en-us/categories/200002802-TK-Google-Fonts" target="_new">Documentation</a> <a class="button secondary" onClick="script: Zenbox.show(); return false;" class="button secondary"  href="#" target="_blank" title="Submit an email support ticket">Ask Question</a></p>
			</div>	        
			
			<div style="float: left; overflow: auto; padding: 0 20px 0 20px; border-right: 1px solid #ddd;">
		        <h3>Contribute your ideas.</h3>
		        <p>Add ideas and vote in our <a class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200001211-TK-Google-Fonts-Ideas" target="_new">Ideas Forums</a></p>
			</div>	        
			
			<div style="float: left; overflow: auto; padding: 0 20px 0 20px;">
		        <h3>Discuss with others.</h3>
		        <p>Learn, share, discuss. Visit our <a class="button button-secondary" href="https://themekraft.zendesk.com/hc/communities/public/topics/200001201-TK-Google-Fonts-Trouble-Shooting" target="_new">Community Forums</a></p>
			</div>	        
			
		</div>
		<br>
		
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
	
	add_settings_field(		'primary-font'			, '<p><b>Add Google Font</b></p> <i>The Plugin loade Google Fonts automaticly from google.<br>
							If the Google Font you are looking fore shouldent be in the Font Selctbox, please type in the name into the text field.<br>
        					Just use the name with spaces, no need to find a special slug. The name is enought, we do the rest. <br>
        					You can find all available Goole Fonts on the <br><br><a href="http://www.google.com/fonts/" target="blank">Goole Fonts Website</a> </i>', 'tk_google_fonts_field_font'	, 'tk_google_fonts_options'	, 'section_typography' );
    add_settings_field(		'primary-list'			, '<p><b>Manage Font</b></p> <i>Please keep in mind that every font will slow down your site a bit more. <br>
			If you use to many fonts you will have a slow siteload and that\'s also bad for SEO. 
			Best is to use 1-2 Fonts.</i>'			, 'tk_google_fonts_list'		, 'tk_google_fonts_options' , 'section_typography' );
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
    <h3>Choose a font from the font select or type in the name to add a new Font to WordPress:</h3>
    <div id="google_fonts_selecter">

        <div class="input-wrap">
        	<input id="font" type="text" />
        	<input type="text" id="new_font" placeholder="Add a not listed font here" />
        	<input type="button" value="Add Font" name="add_google_font" class="button add_google_font btn" />
        	
        </div>

	    <div class="font-preview-screen">
	    	<input type="text" id="myTxt" placeholder="Test your custom text here!" />
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

