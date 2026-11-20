=== TK Google Fonts ===
Contributors: svenl77, konradS, themekraft
Tags: fonts, google fonts, webfonts, gdpr, local fonts
Requires at least: 5.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.3.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Pick Google Fonts in a live-preview picker and use them in any theme. Fonts are hosted on your own server, so visitors never connect to Google.

== Description ==

[youtube https://www.youtube.com/watch?v=i1_us4mjlCA]

TK Google Fonts lets you add Google Fonts to any WordPress site without writing code. Choose fonts in a visual picker with live preview, then apply them to your site title, headings, body text and blockquotes in the Customizer. The fonts are downloaded and served from your own server, so your visitors never connect to Google.

= Free features =

* Visual font picker with live preview and your own preview text
* Add any Google Font by name
* Fonts hosted on your own server, so visitors never connect to Google (GDPR-friendly)
* Customizer options to set the font for the site title, post and page titles, all headings (H1 to H6), body text and blockquotes
* Only the fonts you select are loaded on your site
* Works with any theme. Apply the fonts in the Customizer or in your own CSS

= Want more? TK Google Fonts Pro adds =

* Font color for the site title, post and page titles, headings, body text and blockquotes
* Font weight for the site title, post and page titles and headings
* Responsive font sizes: separate sizes for mobile, tablet and large screens, for the site title, post and page titles, H1 to H6 and body text

Pro is a yearly license for 1 site, 5 sites or unlimited sites, and every Pro feature is included at every size. [Compare Free and Pro on themekraft.com](https://themekraft.com/plugins/gdpr-compliant-google-fonts/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=free-vs-pro&utm_content=tk-google-fonts)

== Installation ==

= Manual installation =
You can download and install the plugin using the built in WordPress plugin installer.

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application.

1. Download the plugin file to your computer and unzip it.
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin panel.

== Frequently Asked Questions ==

= Will TK Google Fonts work with my theme? =

Yes. It works with any theme. Apply the fonts in the Customizer, or use the font name in your own CSS, for example font-family: 'Roboto', sans-serif;

= Does TK Google Fonts load fonts from Google's servers? =

No. The fonts are downloaded once and served from your own server, so your visitors never connect to Google.

= Is local font hosting a Pro feature? =

No. Local hosting is included in the free version.

= What does Pro add? =

Font color (including blockquotes), font weight for titles and headings, and responsive font sizes for mobile, tablet and large screens.

= Can I add a font that is not in the list? =

You can add any Google Font by name.

= How is Pro licensed? =

Pro is a yearly license for 1 site, 5 sites or unlimited sites. Every Pro feature is included at every size. Pricing and purchase are on themekraft.com.

= Is there a free trial of Pro? =

Yes. Pro has a 7-day free trial. A payment method is required to start it.

= Can I run the free and Pro versions together? =

No. Activating one version deactivates the other.

== Screenshots ==

1. Test a font in live preview
2. Manage your fonts
3. Turn Customizer support on or off
4. Select a font with preview in the settings panel

== Changelog ==
= 2.3.3 - 18 Nov 2026 =
* Fixed bold and italic text being faked by the browser: fonts are now downloaded with their bold and italic styles, not only regular 400. Fonts you already added are downloaded again once after the update.
* Fixed the fonts folder being left empty when the plugin was updated outside wp-admin (WP-CLI or automatic background updates).
* Fonts that only come in other weights or only in italic can now be added.
* Font files are downloaded in parallel, so adding a font is faster.
* The Go Pro page now lists each Pro Customizer option and describes local font hosting accurately.

= 2.3.2 - 07 Oct 2026 =
* Fixed a fatal error when activating the premium version while the free version is active. Activating one version now deactivates the other one.

= 2.3.1 - 03 Oct 2026 =
* Removed build tooling files that were accidentally included in the 2.3.0 package.

= 2.3.0 - 02 Oct 2026 =
* Plugin Check: fixed plugin-header License field and stripped hidden macOS metadata from the release.
* Plugin Check: replaced direct fopen/fwrite/fclose calls in the font cache writers with the WordPress Filesystem API; remote font assets are now fetched via wp_remote_get instead of file_get_contents.
* Plugin Check: registered a sanitize callback for the plugin's options array (Settings API).
* Plugin Check: hardened the add-font and delete-font AJAX handlers by sanitizing and unslashing the nonce input before verification.
* Fixed two customizer labels that were calling `__()` with the wrong text domain (`mytheme` instead of `tk-google-fonts`), so the strings now go through the plugin's translation pipeline.
* Updated Freemius SDK to 2.13.1.
* Reworked the Go Pro page to use the parameterized shared pricing-page submodule with three site-license tiers.
* Refreshed the Go Pro page card layout to a responsive CSS grid.
* Hid the duplicate Upgrade menu item under Appearance > TK Google Fonts; the Go Pro link is now the single upsell entry.
* Highlighted the Go Pro menu link in the admin sidebar.
* Plugin Check: aligned the readme `===` heading from "TK Google Fonts GDPR Compliant" → "TK Google Fonts" with the plugin-header Plugin Name; trimmed the short description under the 150-char limit. Aligned the `@package` in `pricing-page-config.php` with the plugin name. Three TextDomainMismatch warnings on the shared pricing-page submodule are now suppressed at the submodule layer.
* Fixed a PHP warning in the Customizer when the plugin settings had never been saved.
* Requires WordPress 5.9 or later and PHP 7.4 or later.
* Tested up to WordPress 7.1.

= 2.2.14 - 12 Jul 2024 =
* Updated Freemius SDK.
* Tested up tp WordPress 6.6

= 2.2.13 - 05 Nov 2023 =
* Updated Freemius SDK.

= 2.2.12 - 24 Oct 2023 =
* Fixed admin action vulnerability.
* Tested up to WordPress 6.3.2

= 2.2.11 - 05 Jul 2023 =
* Fixed XSS vulnerability.
* Tested up to WordPress 6.2.2

= 2.2.10 - 04 May 2023 =
* Updated Freemius SDK.
* Tested up to WordPress 6.2

= 2.2.9 - 06 Mar 2023 =
* Hotfix: Fixed issue with selected fonts not showing up after plugin update.

= 2.2.8 - 03 Mar 2023 =
* Fixed issue with CSRF vulnerability.

= 2.2.7 - 24 Jan 2023 =
* Added new Go Pro screen.

= 2.2.6 - 16 Dec 2022 =
* Updated Freemius SDK.
* Enabled 7 days trial version.

= 2.2.5 - 28 Nov 2022 =
* Updated Freemius SDK.
* Added bundle license auto activation.
* Tested up to WordPress 6.1.1

= 2.2.4 - 21 Jul 2022 =
* Removing old promotions notices.
* Tested up to WordPress 6.0.1

= 2.2.3 - 9 Jun 2022 =
* Fixed vulnerability issue.
* Tested up to WordPress 6.0

= 2.2.2 - 17 May 2022 =
* Updated readme.txt

= 2.2.1 - 15 Mar 2022 =
* Fixed security issue.
* Fixed issue with multiple trial version admin notices.

= 2.2.0 - 14 Feb 2022 =
* GDPR feature is now available in free version.
* Tested up to WordPress 5.9

= 2.1.3 - 5 Dec 2021 =
* Fixed issue with free version deactivation.

= 2.1.2 - 12 Jul 2021 =
* Tested up WordPress 5.8
* Fixed security issue.

= 2.1.1 - 02 Jun 2021 =
* Hotfix: Fixed issue when a non-existing font was loaded.
* Hotfix: temporary solution to fix the issue with fonts family names on the font selector.

= 2.1.0 - 14 May 2021 = 
* Added new feature to make plugin GDPR Compliant on the Pro version.
* Adding GDPR Compliant on the landing page.
* Removed some fonts from the list due to now are not "free accessible" on Google Fonts API.

= 2.0.4 - 17 Mar 2021 =
* Updated Google Font API used by the plugin
* Updated FontSelect library
* Fix CSS priority to be higherq
* Updated Fremius SDK

= 2.0.3 =
Smaller changes
Freemius Update

= 2.0.2 =
Freemius integration script update.

= 2.0.1 =
Freemius integration script update.
New pricing options for the professional version. We dropped prices and add unlimited sits plan.

= 2.0 =
* Created a new free and pro version with tons of new options to layout your fonts.
* Please read the blog post for a complete list of new options: https://themekraft.com/easy-google-fonts-wordpress-forever/

= 1.3.2 =
* Freemius Integration
* Smaller changes

= 1.3.1 =
* Add https is_ssl support

= 1.3 =
* Initial Release.
