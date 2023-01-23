<?php

namespace cybot\cookiebot\admin_notices;

use Exception;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\get_view_html;
use function cybot\cookiebot\lib\include_view;

class Cookiebot_Recommendation_Notice {

	const COOKIEBOT_RECOMMENDATION_OPTION_KEY = 'cookiebot_notice_recommend';

	public function register_hooks() {
		add_action( 'admin_notices', array( $this, 'show_notice_if_needed' ) );
	}

	public function show_notice_if_needed() {
		/** Save actions when someone click on the notice message */
		$this->save_notice_link();

		try {
			$this->do_we_need_to_show_the_notice_message();
			$this->show_notice();
			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		} catch ( Exception $e ) {
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	private function show_notice() {
		$two_week_review_ignore = wp_nonce_url(
			add_query_arg( array( 'cookiebot_admin_notice' => 'hide' ) ),
			'hide_recommendation',
			'nonce'
		);
		$two_week_review_temp   = wp_nonce_url(
			add_query_arg( array( 'cookiebot_admin_notice' => 'two_week' ) ),
			'hide_recommendation_for_two_weeks',
			'nonce'
		);

		$notice = array(
			'title'      => __( 'Leave A Review?', 'cookiebot' ),
			'msg'        => __(
				'We hope you enjoy using WordPress Cookiebot™! Would you consider leaving us a review on WordPress.org?',
				'cookiebot'
			),
			'link_html'  => get_view_html(
				'admin/notices/cookiebot-recommendation-notice-links.php',
				array(
					'two_week_review_ignore' => $two_week_review_ignore,
					'two_week_review_temp'   => $two_week_review_temp,
				)
			),
			'later_link' => $two_week_review_temp,
			'int'        => 14,
		);

		include_view( 'admin/notices/cookiebot-recommendation-notice.php', array( 'notice' => $notice ) );

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
		$option = get_option( static::COOKIEBOT_RECOMMENDATION_OPTION_KEY );

		if ( $option !== false ) {
			//"Never show again" is clicked
			if ( $option === 'hide' ) {
				throw new Exception( 'Never show again is clicked' );
			} elseif ( is_numeric( $option ) && strtotime( 'now' ) < $option ) {
				throw new Exception( '"Show me after 2 weeks" is clicked and 2 weeks is not passed yet' );
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
		if ( isset( $_GET['cookiebot_admin_notice'] ) && isset( $_GET['nonce'] ) ) {
			if ( wp_verify_nonce( $_GET['nonce'], 'hide_recommendation' ) ) {
				update_option( static::COOKIEBOT_RECOMMENDATION_OPTION_KEY, 'hide' );
			}

			if ( wp_verify_nonce( $_GET['nonce'], 'hide_recommendation_for_two_weeks' ) ) {
				update_option( static::COOKIEBOT_RECOMMENDATION_OPTION_KEY, strtotime( '+2 weeks' ) );
			}
		}
	}
}
