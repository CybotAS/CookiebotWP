<?php

namespace cybot\cookiebot\addons\controller\addons\wpforms;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Cookiebot_WP;

class Wpforms extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'WPForms';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable saving user information.';
	const OPTION_NAME                 = 'wpforms';
	const PLUGIN_FILE_PATH            = 'wpforms-lite/wpforms.php';
	const DEFAULT_COOKIE_TYPES        = array( 'preferences' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		add_filter( 'wpforms_disable_entry_user_ip', array( $this, 'gdpr_consent_is_given' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_script_for_adding_the_cookie_after_the_consent' ), 18 );
	}

	/**
	 * Create cookie when the visitor gives consent
	 */
	public function enqueue_script_for_adding_the_cookie_after_the_consent() {
		wp_enqueue_script(
			'wpforms-gdpr-cookiebot',
			CYBOT_COOKIEBOT_PLUGIN_URL . 'addons/controller/addons/wpforms/cookie-after-consent.js',
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);
		wp_localize_script( 'wpforms-gdpr-cookiebot', 'cookiebot_wpforms_settings', array( 'cookie_types' => $this->get_cookie_types() ) );
	}

	/**
	 * Retrieve if the cookie consent is given
	 *
	 * @return bool
	 *
	 * @since 2.1.4
	 */
	public function gdpr_consent_is_given() {
		if ( $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'If the user gives correct consent, IP and Unique User ID will be saved on form submissions, otherwise not.', 'cookiebot' ),
			__( 'Increases opt-in rate compared to WPForms "GDPR mode".', 'cookiebot' ),
		);
	}

	/**
	 * Action after enabling the addon on the settings page
	 *
	 * Clear gdpr settings in the wpforms
	 *
	 * @since 2.2.0
	 */
	public function post_hook_after_enabling() {
		$wpforms_settings = get_option( 'wpforms_settings' );

		$wpforms_settings['gdpr']                 = false;
		$wpforms_settings['gdpr-disable-uuid']    = false;
		$wpforms_settings['gdpr-disable-details'] = false;

		update_option( 'wpforms_settings', $wpforms_settings );
	}

	/**
	 * Cookiebot plugin is deactivated
	 *
	 * @since 2.2.0
	 */
	public function plugin_deactivated() {
		// if the checkbox was checked and the cookiebot plugin is deactivated
		// remove the setting so the default gdpr checkboxes are still visible
		$this->wpforms_set_setting( 'gdpr-cookiebot', false );
	}

	/**
	 * Set the value of a specific WPForms setting.
	 *
	 * @param $key
	 * @param $new_value
	 * @param string    $option
	 */
	public function wpforms_set_setting( $key, $new_value, $option = 'wpforms_settings' ) {
		if ( function_exists( 'wpforms_sanitize_key' ) ) {
			$key          = wpforms_sanitize_key( $key );
			$options      = get_option( $option, false );
			$option_value = is_array( $options ) && ! empty( $options[ $key ] ) ? $options[ $key ] : false;

			if ( $new_value !== $option_value ) {
				$options[ $key ] = $new_value;
			}

			update_option( $option, $options );
		}
	}
}
