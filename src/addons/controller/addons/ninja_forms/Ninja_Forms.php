<?php

namespace cybot\cookiebot\addons\controller\addons\ninja_forms;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Ninja_Forms extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Ninja forms';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'ninja_forms';
	const PLUGIN_FILE_PATH            = 'ninja-forms/ninja-forms.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/ninja-forms/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'ninja-forms.php';

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		/**
		 * block google captcha script
		 */
		$this->script_loader_tag->add_tag( 'nf-google-recaptcha', $this->get_cookie_types() );

		/**
		 * Display placeholder message
		 */
		if ( $this->is_placeholder_enabled() ) {
			add_filter(
				'ninja_forms_display_fields',
				function ( $fields ) {
					foreach ( $fields as $key => $field ) {
						if ( $field['type'] === 'recaptcha' ) {
							$fields[ $key ]['afterField'] = $this->get_placeholder();
						}
					}
					return $fields;
				},
				10,
				1
			);
		}
	}
}
