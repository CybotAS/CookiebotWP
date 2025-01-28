<?php

namespace cybot\cookiebot\admin_notices;

use function cybot\cookiebot\lib\get_view_html;

class Cookiebot_Recommendation_Notice extends Cookiebot_Base_Notice {

	const COOKIEBOT_NOTICE_OPTION_KEY         = 'cookiebot_notice_recommend';
	const COOKIEBOT_NOTICE_TEMPLATE_PATH      = 'admin/notices/cookiebot-recommendation-notice.php';
	const COOKIEBOT_NOTICE_LINK_TEMPLATE_PATH = 'admin/notices/cookiebot-recommendation-notice-links.php';

	const COOKIEBOT_NOTICE_TIMES = array(
		'later'    => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_temp_notice',
			'name'   => 'review_nonce',
			'str'    => false,
			'time'   => 'hide',
		),
		'hide'     => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_recommendation',
			'name'   => 'review_nonce',
			'str'    => false,
			'time'   => 'hide',
		),
		'visit'    => array(
			'msg'    => 'Show me after 1 day',
			'action' => 'hide_recommendation_next',
			'name'   => 'review_nonce',
			'str'    => true,
			'time'   => '+1 day',
		),
		'two_week' => array(
			'msg'    => 'Show me after 2 weeks',
			'action' => 'hide_recommendation_for_two_weeks',
			'name'   => 'review_nonce',
			'str'    => true,
			'time'   => '+2 weeks',
		),
	);

	public function __construct() {
		parent::__construct();
		// Check if recommendation notice delay option exists
		if ( get_option( static::COOKIEBOT_NOTICE_OPTION_KEY, false ) === false ) {
			// Delay in 1 day
			add_option( static::COOKIEBOT_NOTICE_OPTION_KEY, strtotime( '+1 day' ) );
		}
	}

	/**
	 * @return string
	 */
	public function get_link_html() {
		$two_week_review_ignore = wp_nonce_url(
			add_query_arg( array( static::COOKIEBOT_NOTICE_OPTION_KEY => 'hide' ) ),
			static::COOKIEBOT_NOTICE_TIMES['hide']['action'],
			static::COOKIEBOT_NOTICE_TIMES['hide']['name']
		);
		$two_week_review_temp   = wp_nonce_url(
			add_query_arg( array( static::COOKIEBOT_NOTICE_OPTION_KEY => 'two_week' ) ),
			static::COOKIEBOT_NOTICE_TIMES['two_week']['action'],
			static::COOKIEBOT_NOTICE_TIMES['two_week']['name']
		);
		$visit_review_temp      = wp_nonce_url(
			add_query_arg( array( static::COOKIEBOT_NOTICE_OPTION_KEY => 'visit' ) ),
			static::COOKIEBOT_NOTICE_TIMES['visit']['action'],
			static::COOKIEBOT_NOTICE_TIMES['visit']['name']
		);

		return get_view_html(
			static::COOKIEBOT_NOTICE_LINK_TEMPLATE_PATH,
			array(
				'two_week_review_ignore' => $two_week_review_ignore,
				'two_week_review_temp'   => $two_week_review_temp,
				'visit_review_temp'      => $visit_review_temp,
			)
		);
	}

	public function define_translations() {
		$this->translations = array(
			'title' => __(
				'Share your experience',
				'cookiebot'
			),
			'msg'   => __(
				'Hi there! We are thrilled you love the Cookiebot CMP plugin. Could you do us a huge favor and leave a 5-star rating on WordPress? Your support will help us spread the word and empower more WordPress websites to meet GDPR and CCPA compliance standards effortlessly. Thank you for your support!',
				'cookiebot'
			),
		);
	}
}
