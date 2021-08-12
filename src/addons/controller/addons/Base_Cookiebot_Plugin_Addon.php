<?php

namespace cybot\cookiebot\addons\controller\addons;

use cybot\cookiebot\addons\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\addons\lib\Cookie_Consent_Interface;
use cybot\cookiebot\addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\addons\lib\Settings_Service_Interface;
use Exception;

abstract class Base_Cookiebot_Plugin_Addon extends Base_Cookiebot_Addon {

	const PLUGIN_FILE_PATH      = '';
	const LATEST_PLUGIN_VERSION = true;
	/** @var bool|string False or PLUGIN FILE PATH OF THE LATEST PLUGIN VERSION  */
	const PREVIOUS_PLUGIN_VERSION = false;

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
		parent::__construct( $settings, $script_loader_tag, $cookie_consent, $buffer_output );
		$this->validate_required_string_class_constant( 'PLUGIN_FILE_PATH' );
		$this->validate_required_boolean_class_constant( 'LATEST_PLUGIN_VERSION' );
	}

	/**
	 * Checks if addon plugin is installed
	 *
	 * @since 1.3.0
	 */
	final public function is_addon_installed() {
		return $this->settings->is_addon_installed( static::PLUGIN_FILE_PATH );
	}

	/**
	 * Checks if addon plugin is activated
	 *
	 * @since 1.3.0
	 */
	final public function is_addon_activated() {
		return $this->is_addon_installed() && $this->settings->is_addon_activated( static::PLUGIN_FILE_PATH );
	}

	/**
	 * Retrieves current installed version of the addon plugin
	 *
	 * @return bool
	 *
	 * @since 2.2.1
	 */
	final public function get_addon_version() {
		return $this->settings->get_addon_version( static::PLUGIN_FILE_PATH );
	}

	/**
	 * @return bool
	 */
	final public function has_previous_version_plugin() {
		return class_exists( static::PREVIOUS_PLUGIN_VERSION );
	}

	/**
	 * @return bool
	 */
	final public function is_latest_plugin_version() {
		return static::LATEST_PLUGIN_VERSION;
	}

	/**
	 * @return bool
	 */
	final public function is_previous_version_plugin_activated() {
		return $this->has_previous_version_plugin() && $this->settings->is_addon_activated(
			self::PREVIOUS_PLUGIN_VERSION
		);
	}
}
