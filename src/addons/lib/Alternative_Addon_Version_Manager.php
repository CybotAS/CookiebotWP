<?php

namespace cybot\cookiebot\addons\lib;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use cybot\cookiebot\addons\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use Exception;
use InvalidArgumentException;

class Alternative_Addon_Version_Manager {

	/**
	 * @var array $alternative_versions
	 */
	private $alternative_versions = array();
	/**
	 * @var Settings_Service_Interface
	 */
	private $settings;
	/**
	 * @var Script_Loader_Tag_Interface
	 */
	private $script_loader_tag;
	/**
	 * @var Cookie_Consent_Interface
	 */
	private $cookie_consent;
	/**
	 * @var Buffer_Output_Interface
	 */
	private $buffer_output;


	/**
	 * @param $settings          Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent    Cookie_Consent_Interface
	 * @param $buffer_output     Buffer_Output_Interface
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	public function __construct(
		Settings_Service_Interface $settings,
		Script_Loader_Tag_Interface $script_loader_tag,
		Cookie_Consent_Interface $cookie_consent,
		Buffer_Output_Interface $buffer_output
	) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * @param $version_string
	 * @param $addon_class
	 */
	public function add_version( $version_string, $addon_class ) {
		if ( ! version_compare( $version_string, '0.0.1', '>=' ) ) {
			throw new InvalidArgumentException( 'Invalid version number "' . $version_string . '"' );
		}
		if ( ! class_exists( $addon_class ) ) {
			throw new InvalidArgumentException( 'Class not found at "' . $addon_class . '"' );
		}
		if ( array_key_exists( $version_string, $this->alternative_versions ) ) {
			throw new InvalidArgumentException( 'Version "' . $version_string . '" has already been added.' );
		}
		$this->alternative_versions[ $version_string ] = $addon_class;
		uksort( $this->alternative_versions, 'version_compare' );
	}

	/**
	 * @param array $versions
	 * @throws InvalidArgumentException
	 */
	public function add_versions( array $versions ) {
		foreach ( $versions as $version_string => $addon_class ) {
			$this->add_version( $version_string, $addon_class );
		}
	}


	/**
	 * @return Base_Cookiebot_Addon|null
	 */
	public function get_installed_version() {
		foreach ( $this->alternative_versions as $version_string => $addon_class ) {
			/** @var Base_Cookiebot_Addon $addon */
			$addon = new $addon_class(
				$this->settings,
				$this->script_loader_tag,
				$this->cookie_consent,
				$this->buffer_output
			);

			if ( $addon->is_addon_installed() ) {
				if ( version_compare( $addon->get_version(), $version_string, '<=' ) ) {
					return $addon;
				}
			}
		}
		return null;
	}
}
