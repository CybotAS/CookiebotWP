<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use cybot\cookiebot\lib\traits\Class_Constant_Override_Validator_Trait;
use cybot\cookiebot\lib\Settings_Service_Interface;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\lib\Cookie_Consent_Interface;
use cybot\cookiebot\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\lib\traits\Extra_Information_Trait;
use Exception;
use function cybot\cookiebot\lib\cookiebot_addons_output_cookie_types;

abstract class Base_Jetpack_Widget {

	use Class_Constant_Override_Validator_Trait;
	use Extra_Information_Trait;

	/**
	 * @var string ADDON_OPTION_NAME
	 */
	const ADDON_OPTION_NAME = 'cookiebot_jetpack_addon';
	/**
	 * @var string WIDGET_OPTION_NAME
	 */
	const WIDGET_OPTION_NAME = null;
	/**
	 * @var string LABEL
	 */
	const LABEL = null;
	/**
	 * @var string DEFAULT_PLACEHOLDER
	 */
	const DEFAULT_PLACEHOLDER = null;

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
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * @param Settings_Service_Interface  $settings
	 * @param Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookie_Consent_Interface    $cookie_consent
	 * @param Buffer_Output_Interface     $buffer_output
	 *
	 * @throws Exception
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

		$this->validate_fixed_class_constant( 'ADDON_OPTION_NAME' );
		$this->validate_required_string_class_constants(
			array(
				'LABEL',
				'WIDGET_OPTION_NAME',
				'DEFAULT_PLACEHOLDER',
			)
		);
	}

	/**
	 * @return string
	 */
	final public function get_label() {
		return static::LABEL;
	}

	/**
	 * @return string
	 */
	final public function get_widget_option_name() {
		return static::WIDGET_OPTION_NAME;
	}

	/**
	 * Returns cookie types for a widget
	 *
	 * @return array
	 *
	 * @since 1.8.0
	 */
	final public function get_widget_cookie_types() {
		return $this->settings->get_widget_cookie_types(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME
		);
	}

	/**
	 * @return bool
	 */
	final public function is_widget_enabled() {
		return $this->settings->is_widget_enabled(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME
		);
	}

	/**
	 * @return string
	 */
	final public function get_default_placeholder() {
		return static::DEFAULT_PLACEHOLDER;
	}

	/**
	 * Checks if a widget placeholder is enabled
	 *
	 * @return boolean  true    If widget placeholder is checked
	 *                  false   If widget placeholder is not checked
	 *
	 * @since 1.8.0
	 */
	final public function is_widget_placeholder_enabled() {
		return $this->settings->is_widget_placeholder_enabled(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME
		);
	}

	/**
	 * Checks if widget has existing placeholders
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	final public function widget_has_placeholder() {
		return $this->settings->widget_has_placeholder(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME
		);
	}

	/**
	 * @return array
	 */
	final public function get_widget_placeholders() {
		return $this->settings->get_widget_placeholders(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME
		);
	}

	/**
	 * returns widget placeholder
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	final public function get_widget_placeholder() {
		return $this->settings->get_widget_placeholder(
			self::ADDON_OPTION_NAME,
			static::WIDGET_OPTION_NAME,
			static::DEFAULT_PLACEHOLDER,
			cookiebot_addons_output_cookie_types( $this->get_widget_cookie_types() )
		);
	}

	/**
	 * @return string
	 */
	final public function get_widget_default_placeholder() {
		return (string) static::DEFAULT_PLACEHOLDER;
	}

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

	abstract public function load_configuration();

	/**
	 * @param string $view
	 * @param string $widget
	 */
	public function cookie_consent_div( $view, $widget ) {}
}
