<?php
namespace cybot\cookiebot\shortcode;

use cybot\cookiebot\Cookiebot_WP;

class Cookiebot_Declaration_Shortcode {

	public function register_hooks() {
		add_shortcode( 'cookie_declaration', array( static::class, 'show_declaration' ) );
	}

	/**
	 * Cookiebot_WP Output declation shortcode [cookie_declaration]
	 * Support attribute lang="LANGUAGE_CODE". Eg. lang="en".
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	public static function show_declaration( $atts = array() ) {
		$cookiebot_wp = Cookiebot_WP::instance();

		$cbid = $cookiebot_wp->get_cbid();

		$lang = '';
		if ( ! empty( $cbid ) ) {

			$atts = shortcode_atts(
				array(
					'lang' => $cookiebot_wp->get_language(),
				),
				$atts,
				'cookie_declaration'
			);

			if ( ! empty( $atts['lang'] ) ) {
				$lang = ' data-culture="' . strtoupper( $atts['lang'] ) . '"'; //Use data-culture to define language
			}

			if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' ) === 'custom' ) {
				$tag_attr = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
			} else {
				$tag_attr = get_site_option( 'cookiebot-script-tag-cd-attribute' );
			}

			return '<script id="CookieDeclaration" src="https://consent.cookiebot.com/' . $cbid . '/cd.js"' . $lang . ' type="text/javascript" ' . $tag_attr . '></script>';
		} else {
			return esc_html__( 'Please add your Cookiebot ID to show Cookie Declarations', 'cookiebot' );
		}
	}
}
