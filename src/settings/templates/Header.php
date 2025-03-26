<?php

namespace cybot\cookiebot\settings\templates;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Header {


	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cookiebot_logo' => CYBOT_COOKIEBOT_PLUGIN_URL . 'logo.svg',
		);

		$style_sheets = array(
			array( 'cookiebot-main-css', 'css/backend/cookiebot_admin_main.css' ),
		);

		foreach ( $style_sheets as $style ) {
			wp_enqueue_style(
				$style[0],
				asset_url( $style[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);
		}

		// Trial banner
		$user_data           = get_option( 'cookiebot-user-data', array() );
		$subscription        = isset( $user_data['subscriptions']['active'] ) ? $user_data['subscriptions']['active'] : array();
		$subscription_status = isset( $subscription['subscription_status'] ) ? $subscription['subscription_status'] : '';
		$trial_end_date      = isset( $subscription['trial_end_date'] ) ? new \DateTime( $subscription['trial_end_date'] ) : null;

		$days_left = 0;
		$is_trial  = $subscription_status === 'trial_missing_payment' || $subscription_status === 'trial_will_be_billed';
		if ( $is_trial && $trial_end_date ) {
			$now       = new \DateTime();
			$interval  = $now->diff( $trial_end_date );
			$days_left = max( 0, $interval->days + 1 );
		}

		$args['subscription_status'] = $subscription_status;
		$args['days_left']           = $days_left;

		include_view( 'admin/common/templates/header.php', $args );
	}
}
