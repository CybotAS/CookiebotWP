<?php

namespace cookiebot_addons\lib;

class Theme_Settings_Service extends Settings_Service {


	/**
	 * Returns true if the addon is installed
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed( $addon ) {
		return wp_get_theme($addon)->exists();
	}

	/**
	 * Returns the addon version
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 2.2.1
	 */
	public function get_addon_version( $addon ) {
		$theme = wp_get_theme($addon);
		if($theme->exists()) {
			return $theme->get('Version');
		}

		return false;
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
		if ( empty( $addon ) ) {
			return false;
		}

		$addon = strtolower( $addon );

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

}
