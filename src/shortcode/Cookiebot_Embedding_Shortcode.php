<?php

namespace cybot\cookiebot\shortcode;

use InvalidArgumentException;
use function cybot\cookiebot\lib\get_view_html;

class Cookiebot_Embedding_Shortcode {


	public function register_hooks() {
		add_shortcode( 'uc_embedding', array( static::class, 'show_declaration' ) );
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
		$class          = self::get_embed_class( $shortcode_attributes['class'] );
		$type           = ! empty( $shortcode_attributes['type'] ) ? $shortcode_attributes['type'] : 'all';
		$unique_service = ! empty( $shortcode_attributes['service'] ) ? $shortcode_attributes['service'] : false;
		$toggle         = self::get_embed_toggle( $shortcode_attributes['show-toggle'] );

		if ( $type === 'service-specific' && ! $unique_service ) {
			return esc_html__( 'Please add a service ID into the shortcode "service" parameter.', 'cookiebot' );
		}

		return get_view_html(
			'frontend/shortcodes/uc-embed.php',
			array(
				'class'          => $class,
				'type'           => $type,
				'unique_service' => $unique_service,
				'toggle'         => $toggle,
			)
		);
	}

	private static function get_embed_class( $attribute ) {
		return empty( $attribute ) || $attribute === 'gdpr' ? 'uc-embed' : 'uc-embed-tcf';
	}

	private static function get_embed_toggle( $attribute ) {
		return empty( $attribute ) || $attribute === 'false' ? 'false' : 'true';
	}
}
