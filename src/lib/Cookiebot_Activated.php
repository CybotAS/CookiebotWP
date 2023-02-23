<?php
namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Recommendation_Notice;
use Exception;

class Cookiebot_Activated {

	/**
	 * @throws Exception
	 */
	public function run() {
		$this->delay_notice_recommandation_when_it_is_first_activation();

		$this->set_to_mode_auto_when_no_cookiebot_id_is_set();

		$this->set_addons_default_settings();
	}

	private function delay_notice_recommandation_when_it_is_first_activation() {
		// Delay display of recommendation notice in 3 days if not activated earlier
		if ( get_option( Cookiebot_Recommendation_Notice::COOKIEBOT_RECOMMENDATION_OPTION_KEY, false ) === false ) {
			// Not set yet - this must be first activation - delay in 3 days
			update_option( Cookiebot_Recommendation_Notice::COOKIEBOT_RECOMMENDATION_OPTION_KEY, strtotime( '+3 days' ) );
		}
	}

	private function set_to_mode_auto_when_no_cookiebot_id_is_set() {
		if ( Cookiebot_WP::get_cbid() === '' ) {
			if ( is_multisite() ) {
				update_site_option( 'cookiebot-cookie-blocking-mode', 'auto' );
				update_site_option( 'cookiebot-nooutput-admin', true );
			} else {
				update_option( 'cookiebot-cookie-blocking-mode', 'auto' );
				update_option( 'cookiebot-nooutput-admin', true );
			}
		}
	}

	/**
	 * @throws Exception
	 */
	private function set_addons_default_settings() {
		$cookiebot_addons = Cookiebot_Addons::instance();
		$cookiebot_addons->cookiebot_activated();
	}
}
