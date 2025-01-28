<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Iab_Page implements Settings_Page_Interface {


	public $vendor_purpose_translations;

	public function __construct() {
		$this->define_translations();
	}

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'IAB', 'cookiebot' ),
			__( 'IAB', 'cookiebot' ),
			'manage_options',
			'cookiebot_iab',
			array( $this, 'display' ),
			30
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cookiebot_iab'               => get_option( 'cookiebot-iab' ),
			'custom_tcf_purposes'         => self::get_vendor_custom_option( 'cookiebot-tcf-purposes' ),
			'custom_tcf_special_purposes' => self::get_vendor_custom_option( 'cookiebot-tcf-special-purposes' ),
			'custom_tcf_features'         => self::get_vendor_custom_option( 'cookiebot-tcf-features' ),
			'custom_tcf_special_features' => self::get_vendor_custom_option( 'cookiebot-tcf-special-features' ),
			'custom_tcf_vendors'          => self::get_vendor_custom_option( 'cookiebot-tcf-vendors' ),
			'custom_tcf_ac_vendors'       => self::get_vendor_custom_option( 'cookiebot-tcf-ac-vendors' ),
			'custom_tcf_restrictions'     => self::get_restrictions_custom_option(),
			'vendor_data'                 => self::get_vendor_list_data(),
			'extra_providers'             => self::get_extra_providers(),
		);

		include_view( Cookiebot_Frame::get_view_path() . 'settings/iab-page.php', $args );
	}

	public function get_vendor_list_data() {
		$json = wp_safe_remote_request( self::IAB_VENDOR_LIST_URL );

		if ( is_wp_error( $json ) ) {
			return false;
		}

		$response = json_decode( $json['body'] );

		return array(
			'purposes'         => self::get_vendor_array( $response->purposes, self::IAB_PURPOSE_FIELD_NAME ),
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			'special_purposes' => self::get_vendor_array( $response->specialPurposes, self::IAB_SPECIAL_PURPOSE_FIELD_NAME ),
			'features'         => self::get_vendor_array( $response->features, self::IAB_FEATURES_FIELD_NAME ),
			//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			'special_features' => self::get_vendor_array( $response->specialFeatures, self::IAB_SPECIAL_FEATURES_FIELD_NAME ),
			'vendors'          => self::get_vendor_array( $response->vendors, self::IAB_VENDOR_FIELD_NAME ),
		);
	}

	private function get_vendor_custom_option( $option ) {
		return empty( get_option( $option ) ) ? array() : get_option( $option );
	}

	private function get_restrictions_custom_option() {
		$restrictions            = array();
		$custom_tcf_restrictions = get_option( 'cookiebot-tcf-disallowed' );

		if ( ! empty( $custom_tcf_restrictions ) ) {
			foreach ( $custom_tcf_restrictions as $vendor => $t ) {
				$restrictions[ $vendor ] = empty( $t['purposes'] ) ? array( 'purposes' => array() ) : $t;
			}
		} else {
			$restrictions = array(
				'0' => array(
					'purposes' => array(),
				),
			);
		}

		return $restrictions;
	}

	private function get_vendor_array( $items, $item_name ) {
		$array = array(
			'title'       => self::get_vendor_option_content( $item_name, 'title' ),
			'description' => self::get_vendor_option_content( $item_name, 'description' ),
			'selected'    => self::get_vendor_option_content( $item_name, 'selected' ),
		);
		foreach ( $items as $item ) {
			$array['items'][] = array(
				'id'   => intval( esc_html( $item->id ) ),
				'name' => esc_html( $item->name ),
			);
		}
		return $array;
	}

	private function get_extra_providers() {
		$get_info = array_map( 'str_getcsv', file( self::IAB_GAD_EXTRA_PROVIDERS ) );

		if ( ! $get_info ) {
			return false;
		}

		$extra_vendors = array_map(
			function ( $n ) {
				return array(
					'id'   => intval( esc_html( $n[0] ) ),
					'name' => esc_html( $n[1] ),
				);
			},
			$get_info
		);

		return $extra_vendors ? $extra_vendors : false;
	}

	private function get_vendor_option_content( $item, $value ) {
		$defaults = array(
			self::IAB_PURPOSE_FIELD_NAME          => array(
				'title'       => __( 'Purposes of data use', 'cookiebot' ),
				'description' => __(
					'Inform your users how you’ll use their data. We’ll show this on the second layer of your consent banner, where users interested in more granular detail about data processing can view it.',
					'cookiebot'
				),
				'selected'    => self::get_vendor_custom_option( 'cookiebot-tcf-purposes' ),
			),
			self::IAB_SPECIAL_PURPOSE_FIELD_NAME  => array(
				'title'       => __( 'Special purposes of data use', 'cookiebot' ),
				'description' => __(
					'Inform your users about special purposes of using their data. We’ll show this on the second layer of your consent banner.',
					'cookiebot'
				),
				'selected'    => self::get_vendor_custom_option( 'cookiebot-tcf-special-purposes' ),
			),
			self::IAB_FEATURES_FIELD_NAME         => array(
				'title'       => __( 'Features required for data processing', 'cookiebot' ),
				'description' => __(
					'Inform users about the features necessary for processing their personal data. We’ll list the selected features on the second layer of your consent banner.',
					'cookiebot'
				),
				'selected'    => self::get_vendor_custom_option( 'cookiebot-tcf-features' ),
			),
			self::IAB_SPECIAL_FEATURES_FIELD_NAME => array(
				'title'       => __( 'Special features required for data processing', 'cookiebot' ),
				'description' => __(
					'Inform users about any specially categorized features required for processing their personal data. We’ll list the selected features on the second layer of your consent banner, offering options for users to enable or disable them.',
					'cookiebot'
				),
				'selected'    => self::get_vendor_custom_option( 'cookiebot-tcf-special-features' ),
			),
			self::IAB_VENDOR_FIELD_NAME           => array(
				'title'       => __( 'TCF listed vendors', 'cookiebot' ),
				'description' => false,
				'selected'    => self::get_vendor_custom_option( 'cookiebot-tcf-vendors' ),
			),
		);

		return isset( $defaults[ $item ] ) ? $defaults[ $item ][ $value ] : array();
	}

	public function vendor_checked( $id, $selected ) {
		return in_array( strval( $id ), array_values( $selected ), true );
	}

	public function return_translation_value( $option, $item ) {
		$translations = $this->vendor_purpose_translations;
		return isset( $translations[ $option ][ $item['id'] ] ) ? $translations[ $option ][ $item['id'] ] : $item['name'];
	}

	private function define_translations() {
		$this->vendor_purpose_translations = array(
			self::IAB_PURPOSE_FIELD_NAME          => array(
				'1'  => __(
					'Store and/or access information on a device',
					'cookiebot'
				),
				'2'  => __(
					'Use limited data to select advertising',
					'cookiebot'
				),
				'3'  => __(
					'Create profiles for personalised advertising',
					'cookiebot'
				),
				'4'  => __(
					'Use profiles to select personalised advertising',
					'cookiebot'
				),
				'5'  => __(
					'Create profiles to personalise content',
					'cookiebot'
				),
				'6'  => __(
					'Use profiles to select personalised content',
					'cookiebot'
				),
				'7'  => __(
					'Measure advertising performance',
					'cookiebot'
				),
				'8'  => __(
					'Measure content performance',
					'cookiebot'
				),
				'9'  => __(
					'Understand audiences through statistics or combinations of data from different sources',
					'cookiebot'
				),
				'10' => __(
					'Develop and improve services',
					'cookiebot'
				),
				'11' => __(
					'Use limited data to select content',
					'cookiebot'
				),
			),
			self::IAB_SPECIAL_PURPOSE_FIELD_NAME  => array(
				'1' => __(
					'Ensure security, prevent and detect fraud, and fix errors',
					'cookiebot'
				),
				'2' => __(
					'Deliver and present advertising and content',
					'cookiebot'
				),
			),
			self::IAB_FEATURES_FIELD_NAME         => array(
				'1' => __(
					'Match and combine data from other data sources',
					'cookiebot'
				),
				'2' => __(
					'Link different devices',
					'cookiebot'
				),
				'3' => __(
					'Identify devices based on information transmitted automatically',
					'cookiebot'
				),
			),
			self::IAB_SPECIAL_FEATURES_FIELD_NAME => array(
				'1' => __(
					'Use precise geolocation data',
					'cookiebot'
				),
				'2' => __(
					'Actively scan device characteristics for identification',
					'cookiebot'
				),
			),
		);
	}

	public static function get_option_attribute_name( $option_name ) {
		return str_replace( '_', '-', $option_name );
	}

	public static function get_backup_custom_option( $option_name, $values ) {
		$inputs = '';
		if ( $values ) {
			foreach ( $values as $item ) {
				$inputs .= '<input type="hidden" name="' . $option_name . '[]" value="' . $item . '">';
			}
		}
		return $inputs;
	}

	public static function get_backup_custom_restrictions( $values ) {
		$inputs = '';
		if ( $values ) {
			foreach ( $values as $item => $data ) {
				foreach ( $data['purposes'] as $purpose ) {
					$inputs .= '<input type="hidden" name="cookiebot-tcf-disallowed[' . $item . '][purposes][]" value="' . $purpose . '">';
				}
			}
		}
		return $inputs;
	}

	const IAB_VENDOR_LIST_URL             = 'https://vendor-list.consensu.org/v3/vendor-list.json';
	const IAB_GAD_EXTRA_PROVIDERS         = CYBOT_COOKIEBOT_PLUGIN_DIR . 'assets/docs/cookiebot-extra-providers.csv';
	const IAB_PURPOSE_FIELD_NAME          = 'purposes';
	const IAB_SPECIAL_PURPOSE_FIELD_NAME  = 'special_purposes';
	const IAB_FEATURES_FIELD_NAME         = 'features';
	const IAB_SPECIAL_FEATURES_FIELD_NAME = 'special_features';
	const IAB_VENDOR_FIELD_NAME           = 'vendors';
}
