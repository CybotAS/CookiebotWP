<?php

namespace cybot\cookiebot\admin_notices;

use Exception;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use LogicException;
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
			// If exception has been thrown, then we don't need to show the notice.
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
		$visit_review_temp      = wp_nonce_url(
			add_query_arg( array( 'cookiebot_admin_notice' => 'visit' ) ),
			'hide_recommendation_next',
			'nonce'
		);

		$notice = array(
			'title'      => __( 'Leave A Review?', 'cookiebot' ),
			'msg'        => __(
				'Hi, you have been using our Cookiebot CMP plugin to actively collect user consent - that is awesome. Could you please do us a BIG favor and give it a 5-star rating on WordPress? To help us spread the word and enable more WP websites to easily achieve compliance with GDPR and CCPA.',
				'cookiebot'
			),
			'link_html'  => get_view_html(
				'admin/notices/cookiebot-recommendation-notice-links.php',
				array(
					'two_week_review_ignore' => $two_week_review_ignore,
					'two_week_review_temp'   => $two_week_review_temp,
					'visit_review_temp'      => $visit_review_temp,
				)
			),
			'later_link' => $visit_review_temp,
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
			// "Never show again" is clicked
			if ( $option === 'hide' ) {
				throw new LogicException( 'Never show again is clicked' );
			} elseif ( $option === 'visit' ) {
				throw new LogicException( 'Show me after 1 day' );
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
		if ( isset( $_GET['cookiebot_admin_notice'] ) && isset( $_GET['nonce'] ) ) {
			if ( wp_verify_nonce( $_GET['nonce'], 'hide_recommendation' ) ) {
				update_option( static::COOKIEBOT_RECOMMENDATION_OPTION_KEY, 'hide' );
			}

			if ( wp_verify_nonce( $_GET['nonce'], 'hide_recommendation_next' ) ) {
				update_option( static::COOKIEBOT_RECOMMENDATION_OPTION_KEY, strtotime( '+1 day' ) );
			}
		}
	}
}
