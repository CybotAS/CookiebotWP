<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Consent_API_Helper;
use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Supported_Languages;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\cookiebot_get_language_from_setting;
use function cybot\cookiebot\lib\include_view;

class General_Page implements Settings_Page_Interface {

	public function display() {
		$consent_api_helper = new Consent_API_Helper();

		$args = array(
			'cbid'                     => Cookiebot_WP::get_cbid(),
			'ruleset_id'               => ! empty( get_option( 'cookiebot-ruleset-id' ) ) ? get_option( 'cookiebot-ruleset-id' ) : 'settings',
			'is_ms'                    => false,
			'cookiebot_gdpr_url'       => 'https://www.cookiebot.com/' . Cookiebot_WP::get_manager_language() . '/gdpr',
			'cookiebot_logo'           => CYBOT_COOKIEBOT_PLUGIN_URL . 'cookiebot-logo.png',
			'supported_languages'      => Supported_Languages::get(),
			'current_lang'             => cookiebot_get_language_from_setting( true ),
			'is_wp_consent_api_active' => $consent_api_helper->is_wp_consent_api_active(),
			'm_default'                => $consent_api_helper->get_default_wp_consent_api_mapping(),
			'm'                        => $consent_api_helper->get_wp_consent_api_mapping(),
			'cookie_blocking_mode'     => Cookiebot_WP::get_cookie_blocking_mode(),
			'network_auto'             => Cookiebot_WP::check_network_auto_blocking_mode(),
			'add_language_gif_url'     => asset_url( 'img/guide_add_language.gif' ),
			'cookiebot_iab'            => get_option( 'cookiebot-iab' ),
		);

		/* Check if multisite */
		if ( is_multisite() ) {
			// Receive settings from multisite - this might change the way we render the form
			$args['network_cbid']              = get_site_option( 'cookiebot-cbid', '' );
			$args['network_cbid_override']     = ! empty( get_option( 'cookiebot-cbid-override' ) || ( ! empty( $args['network_cbid'] ) && $args['network_cbid'] !== $args['cbid'] ) );
			$args['ruleset_id']                = empty( get_option( 'cookiebot-ruleset-id' ) ) ? get_site_option( 'cookiebot-ruleset-id' ) : $args['ruleset_id'];
			$args['network_scrip_tag_uc_attr'] = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
			$args['network_scrip_tag_cd_attr'] = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
			$args['is_ms']                     = true;
		}

		include_view( Cookiebot_Frame::get_view_path() . 'settings/general-page.php', $args );
	}
}
