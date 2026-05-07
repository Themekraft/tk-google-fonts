<?php
/**
 * Pricing page filter registration for TK Google Fonts.
 * No bundle product exists for this plugin family — the page sells the Pro Version of TK Google Fonts.
 *
 * @package TK Google Fonts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'tk_google_fonts_pricing_page_config' ) ) {
	/**
	 * @param array<string,mixed> $config
	 * @return array<string,mixed>
	 */
	function tk_google_fonts_pricing_page_config( $config ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! str_contains( $screen->id, 'tk-google-fonts-bundle_screen' ) ) {
			return $config;
		}

		$config['heading']    = __( 'Get TK Google Fonts Pro', 'tk-google-fonts' );
		$config['subheading'] = __( 'Unlock all premium features with a year of updates and support.', 'tk-google-fonts' );

		$config['bundle'] = array(
			'product_id' => '426',
			'plan_id'    => '1631',
			'public_key' => 'pk_27b7a20f60176ff52e48568808a9e',
			'name'       => __( 'TK Google Fonts Pro', 'tk-google-fonts' ),
		);

		$bullets = array(
			array(
				'label'     => __( 'All TK Google Fonts Pro features unlocked', 'tk-google-fonts' ),
				'highlight' => true,
			),
			__( 'GDPR-compliant Google Fonts integration', 'tk-google-fonts' ),
			__( 'One year of support', 'tk-google-fonts' ),
			__( 'One year of updates', 'tk-google-fonts' ),
		);

		$config['tiers'] = array(
			array(
				'id'       => 'personal',
				'name'     => __( 'Personal Plan', 'tk-google-fonts' ),
				'sites'    => __( 'One Site', 'tk-google-fonts' ),
				'licenses' => '1',
				'price'    => '99.99',
				'bullets'  => $bullets,
			),
			array(
				'id'        => 'professional',
				'name'      => __( 'Professional Plan', 'tk-google-fonts' ),
				'sites'     => __( 'Five Sites', 'tk-google-fonts' ),
				'licenses'  => '5',
				'price'     => '149.99',
				'highlight' => true,
				'bullets'   => $bullets,
			),
			array(
				'id'       => 'agency',
				'name'     => __( 'Agency Plan', 'tk-google-fonts' ),
				'sites'    => __( 'Unlimited Sites', 'tk-google-fonts' ),
				'licenses' => 'unlimited',
				'price'    => '249.99',
				'bullets'  => $bullets,
			),
		);

		return $config;
	}
}
add_filter( 'tk_pricing_page_config', 'tk_google_fonts_pricing_page_config' );
