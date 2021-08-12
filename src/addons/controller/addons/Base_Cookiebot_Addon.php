<?php

namespace cybot\cookiebot\addons\controller\addons;

use cybot\cookiebot\addons\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\addons\lib\Class_Constant_Override_Validator;
use cybot\cookiebot\addons\lib\Cookie_Consent_Interface;
use cybot\cookiebot\addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\addons\lib\Settings_Service_Interface;
use Exception;
use function cybot\cookiebot\addons\lib\cookiebot_addons_output_cookie_types;

/**
 * Class Base_Cookiebot_Addon
 * @package cybot\cookiebot\addons\controller\addons
 */
abstract class Base_Cookiebot_Addon {

	use Class_Constant_Override_Validator;

	const ADDON_NAME                  = '';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies.';
	const OPTION_NAME                 = '';
	const DEFAULT_COOKIE_TYPES        = array();
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	public $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

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

		$this->validate_required_string_class_constants(
			array(
				'ADDON_NAME',
				'DEFAULT_PLACEHOLDER_CONTENT',
				'OPTION_NAME',
			)
		);
		$this->validate_required_boolean_class_constant( 'ENABLE_ADDON_BY_DEFAULT' );
		$this->validate_required_array_class_constant(
			'DEFAULT_COOKIE_TYPES',
			array( 'necessary', 'marketing', 'statistics', 'preferences' )
		);
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 *
	 * @TODO why do we need to use priority 5? and not 0? or anything else?
	 */
	final public function register_hooks() {
		add_action( 'wp_loaded', array( $this, 'load_addon_configuration' ), 5 );
	}

	abstract public function load_addon_configuration();

	/**
	 * Get placeholder content
	 *
	 * This function will check following features:
	 * - Current language
	 *
	 * @param $src
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	final public function get_placeholder( $src = '' ) {
		return $this->settings->get_placeholder(
			static::OPTION_NAME,
			static::DEFAULT_PLACEHOLDER_CONTENT,
			cookiebot_addons_output_cookie_types( $this->get_cookie_types() ),
			$src
		);
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( static::OPTION_NAME, static::DEFAULT_COOKIE_TYPES );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	final public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( static::OPTION_NAME );
	}

	/**
	 * Checks if it does have custom placeholder content
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	final public function has_placeholder() {
		return $this->settings->has_placeholder( static::OPTION_NAME );
	}

	/**
	 * @return array|false
	 */
	final public function get_placeholders() {
		return $this->settings->get_placeholders( static::OPTION_NAME );
	}

	/**
	 * Return true if the placeholder is enabled
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	final public function is_placeholder_enabled() {
		return $this->settings->is_placeholder_enabled( static::OPTION_NAME );
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	abstract public function get_extra_information();

	/**
	 * Placeholder helper overlay in the settings page.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	final public function get_placeholder_helper() {
		return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in frontend</li></ul>';
	}


	/**
	 * Returns parent class or false
	 *
	 * @return string|bool
	 *
	 * @since 2.1.3
	 * @TODO check if this works!
	 */
	public function get_parent_class() {
		return get_parent_class( $this );
	}

	/**
	 * Action after enabling the addon on the settings page
	 *
	 * @since 2.2.0
	 */
	public function post_hook_after_enabling() {
		// do nothing by default
	}

	/**
	 * Cookiebot plugin is deactivated
	 *
	 * @since 2.2.0
	 */
	public function plugin_deactivated() {
		// do nothing by default
	}

	public function extra_available_addon_option() {
		// do nothing by default
	}

	/**
	 * Sets default settings for this addon
	 *
	 * @return array
	 *
	 * @since 3.6.3
	 */
	public function get_default_enable_setting() {
		return array(
			'enabled'     => 1,
			'cookie_type' => static::DEFAULT_COOKIE_TYPES,
			'placeholder' => static::DEFAULT_PLACEHOLDER_CONTENT,
		);
	}
}
