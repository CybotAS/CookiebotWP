<?php

namespace cybot\cookiebot\admin_notices;

use function cybot\cookiebot\lib\get_view_html;

class Cookiebot_View_Notice extends Cookiebot_Base_Notice {

	const COOKIEBOT_NOTICE_OPTION_KEY         = 'cookiebot_notice_view';
	const COOKIEBOT_NOTICE_TEMPLATE_PATH      = 'admin/notices/cookiebot-base-notice.php';
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
			add_option( self::COOKIEBOT_NOTICE_OPTION_KEY, 1717974000 );
		}
	}

	public function get_link_html() {
		return get_view_html(
			static::COOKIEBOT_NOTICE_LINK_TEMPLATE_PATH,
			array(
				'url'  => 'https://forms.gle/2vTSvhoLqU9qKNeQ8',
				'text' => __( 'Take the survey now', 'cookiebot' ),
			)
		);
	}

	public function define_translations() {
		$this->translations = array(
			'title' => __( 'Your Voice Matters: Quick Product Survey', 'cookiebot' ),
			'msg'   => __(
				'We want to know more about you. Take our survey for a chance to win an Apple iPad Mini. Act fast, as the survey will only be open until June 9.',
				'cookiebot'
			),
		);
	}

}
