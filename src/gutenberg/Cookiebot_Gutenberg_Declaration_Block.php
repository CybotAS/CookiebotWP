<?php
namespace cybot\cookiebot\gutenberg;

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\shortcode\Cookiebot_Declaration_Shortcode;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;

class Cookiebot_Gutenberg_Declaration_Block {

	public function register_hooks() {
		add_action( 'init', array( $this, 'gutenberg_block_setup' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_block_admin_assets' ) );
	}

	/**
	 * Cookiebot_WP Setup Gutenberg block
	 *
	 * @version 3.7.0
	 * @since       3.7.0
	 */
	public function gutenberg_block_setup() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return; // gutenberg not active
		}

		register_block_type(
			'cookiebot/cookie-declaration',
			array(
				'render_callback' => array( $this, 'block_cookie_declaration' ),
			)
		);
	}

	/**
	 * Cookiebot_WP Render Cookiebot Declaration as Gutenberg block
	 *
	 * @throws InvalidArgumentException
	 * @since       3.7.0
	 * @version 3.7.0
	 */
	public function block_cookie_declaration() {
		return Cookiebot_Declaration_Shortcode::show_declaration();
	}

	/**
	 * Cookiebot_WP Add block JS
	 *
	 * @throws InvalidArgumentException
	 * @since       3.7.1
	 * @version 3.7.1
	 */
	public function gutenberg_block_admin_assets() {
		// Add Gutenberg Widget
		wp_enqueue_script(
			'cookiebot-declaration',
			asset_url( 'js/backend/gutenberg/cookie-declaration-gutenberg-block.js' ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ), // Required scripts for the block
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			false
		);
	}
}
