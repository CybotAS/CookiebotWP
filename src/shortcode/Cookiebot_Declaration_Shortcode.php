<?php
namespace cybot\cookiebot\shortcode;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\cookiebot_get_language_from_setting;
use function cybot\cookiebot\lib\get_view_html;

class Cookiebot_Declaration_Shortcode {

	public function register_hooks() {
		add_shortcode( 'cookie_declaration', array( static::class, 'show_declaration' ) );
	}

	/**
	 * Cookiebot_WP Output declation shortcode [cookie_declaration]
	 * Support attribute lang="LANGUAGE_CODE". Eg. lang="en".
	 *
	 * @throws InvalidArgumentException
	 * @since   1.0.0
	 * @version 2.2.0
	 */
	public static function show_declaration( $shortcode_attributes = array() ) {
		$cbid = Cookiebot_WP::get_cbid();

		if ( ! empty( $cbid ) ) {
			$url                  = 'https://consent.cookiebot.com/' . $cbid . '/cd.js';
			$shortcode_attributes = shortcode_atts(
				array(
					'lang' => cookiebot_get_language_from_setting(),
				),
				$shortcode_attributes,
				'cookie_declaration'
			);

			$lang = empty( $shortcode_attributes['lang'] ) ? '' : strtoupper( $shortcode_attributes['lang'] );

			if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' ) === 'custom' ) {
				$tag_attr = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
			} else {
				$tag_attr = get_site_option( 'cookiebot-script-tag-cd-attribute' );
			}

			return get_view_html(
				'frontend/shortcodes/cookie-declaration.php',
				array(
					'url'      => $url,
					'lang'     => $lang,
					'tag_attr' => $tag_attr,
				)
			);
		} else {
			return esc_html__( 'Please add your Cookiebot ID to show Cookie Declarations', 'cookiebot' );
		}
	}
}
