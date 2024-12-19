<?php

namespace cybot\cookiebot\admin_notices;

class Cookiebot_Temp_Notice extends Cookiebot_Base_Notice {

	const COOKIEBOT_NOTICE_OPTION_KEY    = 'cookiebot_notice_temp';
	const COOKIEBOT_NOTICE_TEMPLATE_PATH = 'admin/notices/cookiebot-base-notice.php';

	const COOKIEBOT_NOTICE_TIMES = array(
		'later' => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_temp_notice',
			'name'   => 'temp_nonce',
			'time'   => 'hide',
		),
		'hide'  => array(
			'msg'    => 'Never show again is clicked',
			'action' => 'hide_temp_notice',
			'name'   => 'temp_nonce',
			'time'   => 'hide',
		),
	);

	public function __construct() {
		parent::__construct();

		$option = get_option( static::COOKIEBOT_NOTICE_OPTION_KEY );

		if ( empty( $option ) ) {
			if ( version_compare( phpversion(), '7.0.0' ) >= 0 ) {
				update_option( static::COOKIEBOT_NOTICE_OPTION_KEY, 'hide' );
			} else {
				update_option( static::COOKIEBOT_NOTICE_OPTION_KEY, 'show' );
			}
		} elseif ( $option !== 'hide' && version_compare( phpversion(), '7.0.0' ) >= 0 ) {
			update_option( self::COOKIEBOT_NOTICE_OPTION_KEY, 'hide' );
		}
	}

	public function define_translations() {
		$this->translations = array(
			'title' => '',
			'msg'   => __(
				'Cookiebot CMP Plugin will soon no longer support PHP 5. If your website still runs on this version we recommend upgrading so you can continue enjoying the features Cookiebot CMP offers.',
				'cookiebot'
			),
		);
	}
}
