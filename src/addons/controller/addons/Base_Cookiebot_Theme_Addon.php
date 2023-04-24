<?php

namespace cybot\cookiebot\addons\controller\addons;

use cybot\cookiebot\exceptions\addons\InstallationException;

abstract class Base_Cookiebot_Theme_Addon extends Base_Cookiebot_Addon {

	/**
	 * @return bool
	 */
	final public function is_addon_installed() {
		return wp_get_theme( static::ADDON_NAME )->exists();
	}

	/**
	 * @return bool
	 */
	final public function is_addon_activated() {
		$addon = strtolower( static::ADDON_NAME );

		$addon_theme      = wp_get_theme( $addon );
		$addon_theme_name = strtolower( $addon_theme->get( 'Name' ) );
		$active_theme     = wp_get_theme();
		if ( $addon_theme_name === strtolower( $active_theme->get( 'Name' ) ) ) {
			return true;
		}

		$active_theme_parent = $active_theme->parent();
		if ( $active_theme_parent !== false && $addon_theme_name === strtolower( $active_theme_parent->get( 'Name' ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 * @throws InstallationException
	 */
	final public function get_version() {
		$theme = wp_get_theme( static::ADDON_NAME );
		if ( ! $theme->exists() ) {
			throw new InstallationException( 'Check if theme is installed before calling get_version()' );
		}
		return $theme->get( 'Version' );
	}
}
