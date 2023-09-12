<?php

namespace cybot\cookiebot\admin_notices;

use Exception;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use LogicException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Cookiebot_Temp_Notice {

	const COOKIEBOT_TEMP_OPTION_KEY = 'cookiebot_notice_temp';

	public function register_hooks() {
		add_action( 'admin_notices', array( $this, 'show_notice_temp' ) );
	}

	public function show_notice_temp() {
		/** Save actions when someone click on the notice message */
		$this->save_notice_link();

		try {
			$this->do_we_need_to_show_the_notice_message();
			$this->show_notice();
			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		} catch ( Exception $e ) {
			// If exception has been thrown, then we don't need to show the notice.
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	private function show_notice() {
		$show_temp = wp_nonce_url(
			add_query_arg( array( 'cookiebot_notice_temp' => 'hide' ) ),
			'hide_temp_notice',
			'temp_nonce'
		);

		$update_notice = array(
			'title'      => '',
			'msg'        => __(
				'Cookiebot CMP Plugin will soon no longer support PHP 5. If your website still runs on this version we recommend upgrading so you can continue enjoying the features Cookiebot CMP offers.',
				'cookiebot'
			),
			'link_html'  => '',
			'later_link' => $show_temp,
			'int'        => 14,
		);

		include_view( 'admin/notices/cookiebot-recommendation-notice.php', array( 'notice' => $update_notice ) );

		wp_enqueue_style(
			'cookiebot-admin-notices',
			asset_url( 'css/notice.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}

	/**
	 * Validate if the last user action is valid for plugin recommendation
	 *
	 * @throws Exception
	 *
	 * @version 2.0.5
	 * @since 2.0.5
	 */
	private function do_we_need_to_show_the_notice_message() {
		$option = get_option( static::COOKIEBOT_TEMP_OPTION_KEY );

		if ( $option !== false ) {
			// "Never show again" is clicked
			if ( $option === 'hide' ) {
				throw new LogicException( 'Never show again is clicked' );
			} elseif ( is_numeric( $option ) && strtotime( 'now' ) < $option ) {
				throw new LogicException( 'Show me after 1 day' );
			}
		}
	}

	/**
	 * Save the user action on cookiebot recommendation link
	 *
	 * @version 2.0.5
	 * @since 2.0.5
	 */
	private function save_notice_link() {
		if ( isset( $_GET['cookiebot_notice_temp'] ) &&
			isset( $_GET['temp_nonce'] ) &&
			wp_verify_nonce( $_GET['temp_nonce'], 'hide_temp_notice' ) ) {
				update_option( static::COOKIEBOT_TEMP_OPTION_KEY, 'hide' );
		}
	}
}
