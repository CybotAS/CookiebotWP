<?php

namespace cybot\cookiebot\admin_notices;

use function cybot\cookiebot\lib\get_view_html;

class Cookiebot_Bf_Notice extends Cookiebot_Base_Notice {
	const COOKIEBOT_NOTICE_OPTION_KEY         = 'cookiebot_bf_notice';
	const COOKIEBOT_NOTICE_TEMPLATE_PATH      = 'admin/notices/cookiebot-bf-notice.php';
	const COOKIEBOT_NOTICE_LINK_TEMPLATE_PATH = 'admin/notices/cookiebot-notice-single-cta.php';

	const COOKIEBOT_NOTICE_TIMES = array(
		'later' => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_view_notice',
			'name'   => 'view_nonce',
			'time'   => 'hide',
		),
		'hide'  => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_view_notice',
			'name'   => 'view_nonce',
			'time'   => 'hide',
		),
	);

	const COOKIEBOT_NOTICE_TIMES_REVERT = true;

	public function __construct() {
		parent::__construct();
		if ( get_option( self::COOKIEBOT_NOTICE_OPTION_KEY, false ) === false ) {
			add_option( self::COOKIEBOT_NOTICE_OPTION_KEY, strtotime( '2024-12-03' ) );
		}
	}

	public function get_link_html() {
		return get_view_html(
			static::COOKIEBOT_NOTICE_LINK_TEMPLATE_PATH,
			array(
				'url'  => 'https://admin.cookiebot.com/signup?coupon=BFRIDAYWP10&utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'text' => __( 'Sign up now', 'cookiebot' ),
			)
		);
	}

	public function define_translations() {
		$this->translations = array(
			'title' => __(
				'Get 10% off for 6 months',
				'cookiebot'
			),
			'msg'   => __(
				'Limited offer for new users! Sign up by Dec 2, 2024, to get a 10% discount for the first 6 months.',
				'cookiebot'
			),
		);
	}
}
