<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use Exception;
use Generator;

class Settings_Service implements Settings_Service_Interface {

	/**
	 * @var Dependency_Container
	 */
	public $container;

	const OPTION_NAME = 'cookiebot_available_addons';

	/**
	 * Settings_Service constructor.
	 *
	 * @param $container
	 *
	 * @since 1.3.0
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}

	/**
	 * Returns true if the addon is enabled in the backend
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon ) {
		$option = get_option( static::OPTION_NAME );

		return isset( $option[ $addon ]['enabled'] );
	}

	/**
	 * Returns all cookie type for given addon
	 *
	 * @param $addon    string  option name
	 * @param $default  array   default cookie types
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types( $addon, $default = array() ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $addon ]['cookie_type'] ) && is_array( $option[ $addon ]['cookie_type'] ) ) {
			return $option[ $addon ]['cookie_type'];
		}

		return $default;
	}

	/**
	 * Returns regex for given addon
	 *
	 * @param $addon    string  option name
	 * @param $default  string   default regex
	 *
	 * @return string
	 *
	 * @since 2.4.5
	 */
	public function get_addon_regex( $addon, $default = '' ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $addon ]['regex'] ) ) {
			return $option[ $addon ]['regex'];
		}

		return $default;
	}

	/**
	 * @return Generator
	 * @throws Exception
	 */
	public function get_addons() {
		foreach ( $this->container->get( 'addons_list' ) as $addon ) {
			yield $this->container->get( $addon );
		}
	}

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	public function get_active_addons() {
		$active_addons = array();

		foreach ( $this->get_addons() as $addon ) {
			/**
			 * @var $addon Base_Cookiebot_Addon
			 * Load addon code if the plugin is active and addon is activated
			 */
			if ( $addon->is_addon_enabled() && $addon->is_addon_installed() && $addon->is_addon_activated() ) {
				$active_addons[] = $addon;
			}
		}

		return $active_addons;
	}

	/**
	 * Returns widget cookie types
	 *
	 * @param       $option_key
	 * @param       $widget
	 * @param  array      $default
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_widget_cookie_types( $option_key, $widget, $default = array() ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ]['cookie_type'] ) && is_array( $option[ $widget ]['cookie_type'] ) ) {
			return $option[ $widget ]['cookie_type'];
		}

		return $default;
	}

	/**
	 * Is widget enabled
	 *
	 * @param $option_key
	 * @param $widget
	 *
	 * @return bool
	 */
	public function is_widget_enabled( $option_key, $widget ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ] ) && ! isset( $option[ $widget ]['enabled'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is placeholder enabled for a widget
	 *
	 * @param $option_key
	 * @param $widget
	 *
	 * @return bool
	 */
	public function is_widget_placeholder_enabled( $option_key, $widget ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ] ) && ! isset( $option[ $widget ]['placeholder']['enabled'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if addon has placeholders
	 *
	 * @param $option_key
	 * @param $widget_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function widget_has_placeholder( $option_key, $widget_key ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['languages'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $option_key
	 * @param $widget_key
	 *
	 * @return array
	 */
	public function get_widget_placeholders( $option_key, $widget_key ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['languages'] ) &&
			is_array( $option[ $widget_key ]['placeholder']['languages'] ) ) {
			return (array) $option[ $widget_key ]['placeholder']['languages'];
		}

		return array();
	}

	/**
	 * @param $option_key
	 *
	 * @return array
	 */
	public function get_placeholders( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) &&
			is_array( $option[ $option_key ]['placeholder']['languages'] ) ) {
			return (array) $option[ $option_key ]['placeholder']['languages'];
		}

		return array();
	}

	/**
	 * Checks if addon has placeholders
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function has_placeholder( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * returns true if the addon placeholder is enabled
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function is_placeholder_enabled( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['enabled'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * returns the placeholder if it does exist
	 *
	 * @param $option_key
	 * @param $default_placeholder
	 * @param $cookies
	 * @param  string              $src
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder( $option_key, $default_placeholder, $cookies, $src = '' ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['enabled'] ) ) {
			return $this->get_translated_placeholder( $option, $option_key, $default_placeholder, $cookies, $src );
		}

		return false;
	}

	/**
	 * returns the placeholder if it does exist
	 *
	 * @param $option_key
	 * @param $widget_key
	 * @param $default_placeholder
	 * @param string              $cookies
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholder( $option_key, $widget_key, $default_placeholder, $cookies = '' ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['enabled'] ) ) {
			return $this->get_translated_placeholder( $option, $widget_key, $default_placeholder, $cookies );
		}

		return false;
	}

	/**
	 * Translates the placeholder text in the current page language
	 *
	 * @param        $option
	 * @param        $option_key
	 * @param        $default_placeholder
	 * @param        $cookies
	 * @param  string              $src
	 *
	 * @return mixed
	 *
	 * @since 1.9.0
	 */
	private function get_translated_placeholder( $option, $option_key, $default_placeholder, $cookies, $src = '' ) {
		$current_lang = cookiebot_get_current_site_language();

		if ( $current_lang === false || $current_lang === '' ) {
			$current_lang = 'site-default';
		}

		/**
		 * Loop every language and match current language
		 */
		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) &&
			is_array( $option[ $option_key ]['placeholder']['languages'] ) ) {
			foreach ( $option[ $option_key ]['placeholder']['languages'] as $key => $value ) {

				/**
				 * if current lang match with the prefix language in the database then get the text
				 */
				if ( $key === $current_lang ) {
					$cookies_array           = explode( ', ', $cookies );
					$translated_cookie_names = cookiebot_translate_cookie_names( $cookies_array );
					$translated_cookie_names = implode( ', ', $translated_cookie_names );
					$placeholder             = cookiebot_translate_placeholder(
						$option[ $option_key ]['placeholder']['languages'][ $key ]
					);
					return $this->placeholder_merge_tag(
						$placeholder,
						$translated_cookie_names,
						$src
					);
				}
			}
		}

		/**
		 * Returns site-default text if no match found.
		 */
		if ( isset( $option[ $option_key ]['placeholder']['languages']['site-default'] ) ) {
			$placeholder = cookiebot_translate_placeholder( $option[ $option_key ]['placeholder']['languages']['site-default'] );
			return $this->placeholder_merge_tag(
				$placeholder,
				$cookies,
				$src
			);
		}

		/**
		 * Returns addon default placeholder (code)
		 */
		return $this->placeholder_merge_tag( $default_placeholder, $cookies, $src );
	}

	/**
	 * Merges placeholder tags with values
	 *
	 * @param $placeholder
	 * @param $cookies
	 * @param $src
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	private function placeholder_merge_tag( $placeholder, $cookies, $src ) {
		if ( strpos( $placeholder, '%cookie_types' ) !== false ) {
			$placeholder = str_replace( '%cookie_types', $cookies, $placeholder );
		}

		if ( strpos( $placeholder, '%src' ) !== false ) {
			$placeholder = str_replace( '%src', $src, $placeholder );
		}

		if ( strpos( $placeholder, '[renew_consent]' ) !== false ) {
			$placeholder = str_replace( '[renew_consent]', '<a href="javascript:Cookiebot.renew()">', $placeholder );
		}

		if ( strpos( $placeholder, '[/renew_consent]' ) !== false ) {
			$placeholder = str_replace( '[/renew_consent]', '</a>', $placeholder );
		}

		return $placeholder;
	}

	/**
	 * Check if the addon option name matchs with the parameter
	 * then run the post_hook_after_enabling function in the addon class.
	 *
	 * @param $addon_option_name    string  Addon option name
	 *
	 * @throws Exception
	 *
	 * @since 2.2.0
	 */
	public function post_hook_after_enabling_addon_on_settings_page( $addon_option_name ) {
		$addons = $this->get_addons();

		/** @var Base_Cookiebot_Addon $addon */
		foreach ( $addons as $addon ) {
			if ( $addon::OPTION_NAME === $addon_option_name ) {
				$addon->post_hook_after_enabling();
			}
		}
	}

	/**
	 * The cookiebot plugin is deactivated
	 * so run this function to cleanup the addons.
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function cookiebot_deactivated() {
		/** @var Base_Cookiebot_Addon $addon */
		foreach ( $this->get_active_addons() as $addon ) {
			$addon->plugin_deactivated();
		}
	}

	/**
	 * The cookiebot plugin is activated and the addon settings is activated
	 *
	 * @throws Exception
	 * @since 3.6.3
	 */
	public function cookiebot_activated() {
		$option = get_option( static::OPTION_NAME );

		if ( $option === false ) {
			$option = array();

			/** @var Base_Cookiebot_Addon $addon */
			foreach ( $this->get_addons() as $addon ) {
				if ( $addon::ENABLE_ADDON_BY_DEFAULT ) {
					$option[ $addon::OPTION_NAME ] = $addon->get_default_enable_setting();
				}
			}

			update_option( static::OPTION_NAME, $option );
		}
	}
}
