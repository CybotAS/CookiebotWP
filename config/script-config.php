<?php

namespace cookiebot_addons\config;

class Script_Config {
	
	/**
	 * Script_Config constructor.
	 *
	 * @since 1.9.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'scripts' ) );
	}
	
	/**
	 * Run Fix scripts
	 *
	 * @since 1.9.0
	 */
	public function scripts() {
		$this->fix_cookietypes_placeholder();
	}
	
	/**
	 * Fix %s mergetag into %cookie_types
	 * Run it once and only in the cookiebot addon version 1.9.0
	 *
	 * @since 1.9.0
	 */
	private function fix_cookietypes_placeholder() {
		/**
		 * Only for the version 1.9.0
		 */
		if ( COOKIEBOT_ADDONS_VERSION == '1.9.0' ) {
			
			/**
			 * Only run it once
			 */
			if ( ! get_option( 'fix_cookietypes_placeholder' ) ) {
				
				$options = array( 'cookiebot_jetpack_addon', 'cookiebot_available_addons' );
				
				foreach ( $options as $option_key ) {
					/**
					 * Cookiebot jetpack addon
					 */
					$option = get_option( $option_key );
					
					if ( $option ) {
						
						foreach ( $option as $addon_key => $addon ) {
							
							if ( isset( $addon['placeholder'] ) ) {
								
								foreach ( $addon['placeholder']['languages'] as $addon_option_key => $addon_option ) {
									/**
									 * Replace %s[ with %cookie_types[
									 */
									$text = str_replace( '%s[', '%cookie_types[', $addon_option );
									
									$option[ $addon_key ]['placeholder']['languages'][ $addon_option_key ] = $text;
								}
							}
						}
					}
					
					/**
					 * Update the uption value
					 */
					update_option( $option_key, $option );
				}
				
				add_option( 'fix_cookietypes_placeholder' );
			}
		}
	}
}