<?php

namespace cybot\cookiebot\admin_notices;

use Exception;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use LogicException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Cookiebot_Base_Notice {
	const COOKIEBOT_NOTICE_OPTION_KEY    = '';
	const COOKIEBOT_NOTICE_TEMPLATE_PATH = '';

	const COOKIEBOT_NOTICE_TIMES        = array();
	const COOKIEBOT_NOTICE_TIMES_REVERT = false;

	public $translations = array();

	public function __construct() {
		$this->define_translations();
	}

	public function register_hooks() {
		add_action( 'admin_notices', array( $this, 'show_notice_if_needed' ) );
	}

	/**
	 * Set translations
	 *
	 * @version 4.3.9
	 * @since 4.3.9
	 */
	public function define_translations() {
		$this->translations = array(
			'title' => '',
			'msg'   => '',
		);
	}

	/**
	 * Save the user action on cookiebot recommendation link
	 *
	 * @version 4.3.9
	 * @since 2.0.5
	 */
	private function save_notice_link() {
		if ( isset( $_GET[ static::COOKIEBOT_NOTICE_OPTION_KEY ] ) ) {
			foreach ( static::COOKIEBOT_NOTICE_TIMES as $item ) {
				if ( isset( $_GET[ $item['name'] ] ) &&
					wp_verify_nonce( $_GET[ $item['name'] ], $item['action'] ) ) {
					$option = $item['time'];
					if ( isset( $item['str'] ) && $item['str'] === true ) {
						$option = strtotime( $item['time'] );
					}
					update_option( static::COOKIEBOT_NOTICE_OPTION_KEY, $option );
				}
			}
		}
	}

	/**
	 * Validate if the last user action is valid for plugin recommendation
	 *
	 * @throws Exception
	 *
	 * @version 4.3.9
	 * @since 2.0.5
	 */
	private function do_we_need_to_show_the_notice_message() {
		$option = get_option( static::COOKIEBOT_NOTICE_OPTION_KEY );

		if ( $option !== false ) {
			// "Never show again" is clicked
			if ( array_key_exists( $option, static::COOKIEBOT_NOTICE_TIMES ) ) {
				throw new LogicException( static::COOKIEBOT_NOTICE_TIMES[ $option ]['msg'] );
			} elseif ( is_numeric( $option ) ) {
				if ( ! self::notice_check_option_date( $option ) ) {
					throw new LogicException( 'Show me after some time' );
				}
			}
		}
	}

	private function notice_check_option_date( $option ) {
		if ( ( strtotime( 'now' ) < $option && static::COOKIEBOT_NOTICE_TIMES_REVERT ) ||
			( strtotime( 'now' ) > $option && ! static::COOKIEBOT_NOTICE_TIMES_REVERT ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Return html for the link html parameter
	 *
	 * @version 4.3.9
	 * @since 4.3.9
	 */
	public function get_link_html() {
		return '';
	}

	/**
	 * Include notice on page
	 *
	 * @version 4.3.9
	 * @since 4.3.9
	 * @throws InvalidArgumentException
	 */
	private function show_notice() {
		include_view(
			static::COOKIEBOT_NOTICE_TEMPLATE_PATH,
			array(
				'notice' => array(
					'title'      => $this->translations['title'],
					'msg'        => $this->translations['msg'],
					'link_html'  => $this->get_link_html(),
					'later_link' => wp_nonce_url(
						add_query_arg(
							array(
								static::COOKIEBOT_NOTICE_OPTION_KEY => static::COOKIEBOT_NOTICE_TIMES['later']['time'],
							)
						),
						static::COOKIEBOT_NOTICE_TIMES['later']['action'],
						static::COOKIEBOT_NOTICE_TIMES['later']['name']
					),
				),
			)
		);

		wp_enqueue_style(
			'cookiebot-admin-notices',
			asset_url( 'css/notice.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}

	/**
	 * Check if the notice needs to be shown
	 *
	 * @version 4.3.9
	 * @since 4.3.9
	 */
	public function show_notice_if_needed() {
		// Save actions when someone click on the notice message
		$this->save_notice_link();

		try {
			$this->do_we_need_to_show_the_notice_message();
			$this->show_notice();
			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		} catch ( Exception $e ) {
			// If exception has been thrown, then we don't need to show the notice.
		}
	}
}
