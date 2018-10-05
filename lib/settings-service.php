<?php

namespace cookiebot_addons\lib;

class Settings_Service implements Settings_Service_Interface {

	/**
	 * @var \DI\Container
	 */
	public $container;

	CONST OPTION_NAME = 'cookiebot_available_addons';

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
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $addon ]['enabled'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns true if the addon is installed
	 *
	 * @param $addon
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed( $addon ) {
		return ( $addon !== false && is_wp_error( validate_plugin( $addon ) ) ) ? false : true;
	}

	/**
	 * Returns true if the addon plugin is activated
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated( $addon ) {
		return ( $addon === false || is_plugin_active( $addon ) ) ? true : false;
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
	 * Returns addons one by one through a generator
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_addons() {
		$addons = array();

		foreach ( $this->container->get( 'plugins' ) as $addon ) {
			$addons[] = $this->container->get( $addon->class );
		}

		return $addons;
	}

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_active_addons() {
		$active_addons = array();

		foreach ( $this->get_addons() as $addon ) {
			/**
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
	 * @param array $default
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
	 * Returns widget placeholders
	 *
	 * @param $option_key
	 * @param $widget_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholders( $option_key, $widget_key ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['languages'] ) ) {
			return $option[ $widget_key ]['placeholder']['languages'];
		}

		return false;
	}

	/**
	 * Returns all placeholders
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function get_placeholders( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) ) {
			return $option[ $option_key ]['placeholder']['languages'];
		}

		return false;
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
	 * @param $default_placeholder
	 * @param $cookies
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
	 * @param string $src
	 *
	 * @return mixed
	 *
	 * @since 1.9.0
	 */
	private function get_translated_placeholder( $option, $option_key, $default_placeholder, $cookies, $src = '' ) {
		$current_lang = cookiebot_addons_get_language();

		if ( $current_lang == false || $current_lang == '' ) {
			$current_lang = 'site-default';
		}

		/**
		 * Loop every language and match current language
		 */
		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) && is_array( $option[ $option_key ]['placeholder']['languages'] ) ) {
			foreach ( $option[ $option_key ]['placeholder']['languages'] as $key => $value ) {

				/**
				 * if current lang match with the prefix language in the database then get the text
				 */
				if ( $key == $current_lang ) {
					return $this->placeholder_merge_tag( $option[ $option_key ]['placeholder']['languages'][ $key ], $cookies, $src );
				}
			}
		}

		/**
		 * Returns site-default text if no match found.
		 */
		if ( isset( $option[ $option_key ]['placeholder']['languages']['site-default'] ) ) {
			return $this->placeholder_merge_tag( $option[ $option_key ]['placeholder']['languages']['site-default'], $cookies, $src );
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
	 *
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
	 * returns true if the "remove tag" option is enabled
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 2.1.0
	 */
	public function is_remove_tag_enabled( $option_key ) {
		//Always return false if COOKIEBOT_OPTION_REMOVE_TAG
		if ( !defined( 'COOKIEBOT_OPTION_REMOVE_TAG' ) || !COOKIEBOT_OPTION_REMOVE_TAG ) {
			return false;
		}

		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['remove_tag'] ) ) {
			return true;
		}

		return false;
	}
}
