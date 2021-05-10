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

		<script src="https://www.youtube.com/iframe_api"></script>
		<script>(function(f,b){if(!b.__SV){var e,g,i,h;window.mixpanel=b;b._i=[];b.init=function(e,f,c){function g(a,d){var b=d.split(".");2==b.length&&(a=a[b[0]],d=b[1]);a[d]=function(){a.push([d].concat(Array.prototype.slice.call(arguments,0)))}}var a=b;"undefined"!==typeof c?a=b[c]=[]:c="mixpanel";a.people=a.people||[];a.toString=function(a){var d="mixpanel";"mixpanel"!==c&&(d+="."+c);a||(d+=" (stub)");return d};a.people.toString=function(){return a.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking start_batch_senders people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");
			for(h=0;h<i.length;h++)g(a,i[h]);var j="set set_once union unset remove delete".split(" ");a.get_group=function(){function b(c){d[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));a.push([e,call2])}}for(var d={},e=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<j.length;c++)b(j[c]);return d};b._i.push([e,f,c])};b.__SV=1.2;e=f.createElement("script");e.type="text/javascript";e.async=!0;e.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?
			MIXPANEL_CUSTOM_LIB_URL:"file:"===f.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";g=f.getElementsByTagName("script")[0];g.parentNode.insertBefore(e,g)}})(document,window.mixpanel||[]);
			mixpanel.init("b9e34444f551aa572ff78c8a3f813d37", {batch_requests: true})
		</script>
		
		<?php 
		if ( get_option( 'tk_google_fonts_notice_modal' ) !== 'gdpr' ) {
			tk_google_font_notice_modal(); 
		}?>

    </div><?php
}

function tk_google_font_notice_modal() { 
	
	/**
	 * @var WP_User
	 */
	$user_id    = get_current_user_id();
	$user       = get_userdata( $user_id );
	$user_email = isset( $user->data->user_email ) ? $user->data->user_email : '';
	?>

	<div data-user-id="<?php echo $user_id; ?>" data-user-email="<?php echo $user_email ?>" id="tk-notice-modal" title="TK Google Font Plugin Pro Version GDPR Compliant update.">
		<p>Hi, thanks for installing/updating your TK Google Fonts Plugin.</p>

		<p>With this new update, the pro version is GDPR Compliant, so you don’t risk getting fined by the European Union. (Between 10 and 20 million euros!!)</p>

		<?php if ( tk_gf_fs()->is_free_plan() ) : ?>
		
		<p>Because we care about you, your business, and your web, just for this month, we have a 30% discount on our TK
			Google Font plugin. Don’t let this offer pass you by nor the opportunity to be GDPR compliant.</p>
			
		<div class="tk-upgrade-box">
			<a target="_blank" href="https://checkout.freemius.com/mode/dialog/plugin/426/plan/1631/?coupon=TKGDPRLimitedPromotion">Click now to upgrade/purchase!</a>
			<p id="tk-count-down"></p>
		</div>
		<div class="videoWrapper" style="--aspect-ratio: 3 / 4;">
			<div id="tkVideo"></div>
		</div>

		<?php else : ?>

			<img src="<?php echo plugins_url( '/media/gdpr-thumbnail.png', __FILE__ ); ?>" alt="TK Google Font GDPR">

		<?php endif; ?>

		<footer>
			<p>
				If you need any help on implementation or have any questions about this plugin you can always write us at
					support@themekraft.com.
			</p>
		</footer>

		<a href="#" class="close-for-ever" data-close-for-ever="gdpr">Don't show me this massage again.</a>
	</div> 
	
	<script>

		jQuery(document).ready(function($) {

			let player = {};

			window.onYouTubeIframeAPIReady = function() {
				player = new YT.Player('tkVideo', {
					height: '940',
					width: '529',
					videoId: 'R15UnbmMDac',
					events: {
						'onStateChange': onPlayerStateChange
					}
				});
			}

			var started = false;
			window.onPlayerStateChange = function(event) {
				if (event.data == YT.PlayerState.PLAYING && !started) {
					console.log('Video started');
					mixpanel.track("GDPR Pop-up",{ "action": "Video started"});
					const interval = setInterval(function() {
						if (parseInt(player.getCurrentTime()) === 30) {
							console.log('Video After 30s')
							mixpanel.track("GDPR Pop-up",{ "action": "Video After 30s"});
							clearInterval(interval);

						}
					}, 1000);

					started = true;
				}
			}

			const initCounterDown = function() {
				// Set the date we're counting down to
				const countDownDate = new Date("May 31, 2021 00:00:00").getTime();

				// Update the count down every 1 second
				const x = setInterval(function() {

					// Get today's date and time
					const now = new Date().getTime();
						
					// Find the distance between now and the count down date
					const distance = countDownDate - now;
						
					// Time calculations for days, hours, minutes and seconds
					const days = Math.floor(distance / (1000 * 60 * 60 * 24));
					const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					const seconds = Math.floor((distance % (1000 * 60)) / 1000);
						
					// Output the result in an element with id="demo"
					$('#tk-count-down').text( 
						days + "d " + hours + "h " + minutes + "m " + seconds + "s " 
					);
						
					// If the count down is over, write some text 
					if (distance < 0) {
						clearInterval(x);
						$('#tk-count-down').text("This promotion has expired 😓");
					}

				}, 1000);
			}

			const initMixPanelEvents = function() {

				const USER_ID    = $('#tk-notice-modal').data('user-id');
				const USER_EMAIL = $('#tk-notice-modal').data('user-email');

				// Identify for MixPanel
				mixpanel.identify(USER_ID);
				mixpanel.people.set({
					"$email" : USER_EMAIL,
					"USER_ID": USER_ID
				});

				console.log('Pop-up show');
				mixpanel.track("GDPR Pop-up",{ "action": "Pop-up show"});

				$( '.ui-dialog-titlebar button' ).click(function(e){
					console.log('Close');
					mixpanel.track("GDPR Pop-up",{ "action": "Close"});
					player.stopVideo();
				});

				$( '.close-for-ever' ).click(function(e){
					console.log('Close For Ever');
					mixpanel.track("GDPR Pop-up",{ "action": "Close For Ever"});
					player.stopVideo();
				});

				$( '.tk-upgrade-box a' ).click(function(e){
					console.log('Upgrade/Purchase');
					mixpanel.track("GDPR Pop-up",{ "action": "Upgrade/Purchase"});
					player.pauseVideo();
				});
			}
			
			initCounterDown();
			initMixPanelEvents();

		});
	</script>

	<?php
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

	$tk_fonts_folder = dirname(plugin_dir_path(__FILE__)) . '/resources/my-fonts/';

	if ( ! is_dir( $tk_fonts_folder . $google_font_name ) ) {
		wp_mkdir_p( $tk_fonts_folder . $google_font_name );
	}

	$fp = fopen ($tk_fonts_folder . $google_font_name . '/' . $google_font_name . '.css', 'w+');
	$tk_userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36';
    $tk_family_url = 'https://fonts.googleapis.com/css2?family=' . $google_font_name;
    $ch = curl_init();
	$timeout = 5;
    curl_setopt($ch, CURLOPT_USERAGENT, $tk_userAgent);
    curl_setopt($ch, CURLOPT_URL, $tk_family_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$tk_font_data = curl_exec($ch);
	$tk_search_url = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{3,4}(\/\S[^)]*)?/";

	if( preg_match_all( $tk_search_url, $tk_font_data, $tk_font_fileinfo ) ) {
        $tk_font_urls = $tk_font_fileinfo[0];
        $tk_hosted_fonts = $tk_font_data;
		$tk_main_url = dirname(plugin_dir_url(__FILE__)) . '/resources/my-fonts/';
        $i = 0;
        foreach($tk_font_urls as $googlefonts_urls){
          $tk_google_urls = $tk_font_urls[$i];
          $ff = fopen ($tk_fonts_folder . $google_font_name . '/' . $google_font_name . '-' . $i . '.woff2', 'w+');
          $ci = curl_init();
          curl_setopt($ci, CURLOPT_USERAGENT, $tk_userAgent);
          curl_setopt($ci, CURLOPT_URL, $tk_font_urls[$i]);
          curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 5);
          $tk_new_fontfamily_urls = curl_exec($ci);
          fwrite($ff, $tk_new_fontfamily_urls);
    	  curl_close($ci);
          fclose($ff); 
          $tk_hosted_fonts = str_replace( $tk_google_urls, $tk_main_url . $google_font_name . '/' . $google_font_name . '-' . $i .'.woff2', $tk_hosted_fonts);
          $i++;
          
        }
		fwrite($fp, $tk_hosted_fonts);
		curl_close($ch);
		fclose($fp); 
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

add_action('wp_ajax_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');
add_action('wp_ajax_nopriv_tk_google_fonts_delete_font', 'tk_google_fonts_delete_font');

function tk_google_fonts_delete_font(){
	$tk_fonts_folder = dirname(plugin_dir_path(__FILE__)) . '/resources/my-fonts/';
	$google_font_name = $_POST['google_font_name'];
	$tk_google_fonts_options = get_option('tk_google_fonts_options');
	unset( $tk_google_fonts_options['selected_fonts'][$_POST['google_font_name']] );
	$direc= $tk_fonts_folder . $google_font_name;
	
	if( is_dir( $direc ) ) {
        WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->rmdir($direc, true);
    }
	

	update_option("tk_google_fonts_options", $tk_google_fonts_options);
    die();

}

// Adding the admin notice

if( get_option( 'tk_dismiss_notice' ) != true && ! tk_gf_fs()->is_plan__premium_only( 'professional' )) {
    add_action( 'admin_notices', 'add_dismissible' );
}

function add_dismissible() {
    
    echo '<div class="notice notice-warning tk-dismiss-notice is-dismissible">
        <p><strong>GOOGLE FONTS ARE AGAINST THE EUROPE LOW.</strong></p>
		<p>Get our <a href="themes.php?page=tk-google-fonts-options-pricing" class="current" aria-current="page">latest pro version with GDPR Compliance</a> to stay save and avoid paying costly warning letter.</p>
      	</div>';
}


