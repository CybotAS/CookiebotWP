<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Supported_Regions;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Multiple_Page implements Settings_Page_Interface {

	private function selectedRegionList() {
		$countries = Supported_Regions::get();
		$list      = explode( ', ', esc_attr( get_option( 'cookiebot-second-banner-regions' ) ) );
		$ccpa      = esc_attr( get_option( 'cookiebot-ccpa' ) );

		if ( $ccpa === '1' && ! in_array( 'US-06', $list, true ) ) {
			array_push( $list, 'US-06' );
		}

		$selected = array();

		foreach ( $list as $item ) {
			if ( isset( $countries[ $item ] ) ) {
				$selected[ $item ] = $countries[ $item ];
			}
		}

		return $selected;
	}

	private function retroSecondaryId() {
		$ccpa_group_id      = esc_attr( get_option( 'cookiebot-ccpa-domain-group-id' ) );
		$secondary_group_id = esc_attr( get_option( 'cookiebot-second-banner-id' ) );

		if ( $ccpa_group_id && ! $secondary_group_id ) {
			$secondary_group_id = $ccpa_group_id;
			update_option( 'cookiebot-second-banner-id', $ccpa_group_id );
			delete_option( 'cookiebot-ccpa-domain-group-id' );
		}

		return $secondary_group_id;
	}

	public function getCountryName( $code ) {
		$countries = Supported_Regions::get();

		return $countries[ $code ];
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cbid'               => Cookiebot_WP::get_cbid(),
			'secondary_group_id' => $this->retroSecondaryId(),
			'supported_regions'  => Supported_Regions::get(),
			'ccpa_compatibility' => esc_attr( get_option( 'cookiebot-ccpa' ) ),
			'selected_regions'   => $this->selectedRegionList(),
		);

		wp_enqueue_style(
			'cookiebot-multiple-page-css',
			asset_url( 'css/backend/multiple_page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-multiple-page-js',
			asset_url( 'js/backend/multiple-page.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		include_view( 'admin/settings/multiple-configuration/page.php', $args );
	}
}
