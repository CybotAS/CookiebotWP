<?php

namespace cybot\cookiebot\addons\controller;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use cybot\cookiebot\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\lib\Settings_Service_Interface;
use cybot\cookiebot\lib\Cookiebot_WP;
use Exception;
use function cybot\cookiebot\lib\cookiebot_addons_enabled_cache_plugin;
use function cybot\cookiebot\lib\cookiebot_active;

class Plugin_Controller {


	/**
	 * @var Settings_Service_Interface
	 */
	private $settings_service;

	/**
	 * @param Settings_Service_Interface $settings_service
	 */
	public function __construct( Settings_Service_Interface $settings_service ) {
		$this->settings_service = $settings_service;
	}

	/**
	 * @throws Exception
	 */
	public function load_active_addons() {
		if ( ! cookiebot_active() ) {
			return;
		}

		/**
		 * Add notice for the user if any addons is enabled and cookie
		 * blocking mode is set to auto.
		 */
		if ( count( $this->settings_service->get_active_addons() ) > 0 &&
			Cookiebot_WP::get_cookie_blocking_mode() === 'auto' &&
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			isset( $_GET['page'] ) &&
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			in_array( $_GET['page'], array( 'cookiebot', 'cookiebot-addons' ), true ) ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-warning"><p><strong>' .
						esc_html__(
							'You enabled Cookiebot™ auto blocking mode but still using addons',
							'cookiebot'
						) .
						'</strong><br>' .
						esc_html__(
							'In some occasions this may cause client side errors. If you notice any errors please try to disable Cookiebot™ addons or contact Cookiebot™ support.',
							'cookiebot'
						) .
						'</p></div>';
				}
			);
		}

		if ( Cookiebot_WP::cookiebot_disabled_in_admin() === true && is_admin() ) {
			return;
		}

		$addons_enabled_counter = 0;
		/** @var Base_Cookiebot_Addon $addon */
		foreach ( $this->settings_service->get_active_addons() as $addon ) {
			if ( ! $addon->cookie_consent->are_cookie_states_accepted( $addon->get_cookie_types() )
			|| cookiebot_addons_enabled_cache_plugin() ) {
				$addon->load_addon_configuration();
				$addons_enabled_counter++;
			}
		}

		/**
		 * After WordPress is fully loaded
		 *
		 * Run buffer output actions - this runs after scanning of every addons
		 */
		add_action( 'parse_request', array( $this, 'run_buffer_output_manipulations' ) );
	}

	/**
	 * Runs every added action hooks to manipulate script tag
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	public function run_buffer_output_manipulations() {
		 /**
		 * @var $buffer_output Buffer_Output_Interface
		 */
		$buffer_output = $this->settings_service->container->get( 'Buffer_Output_Interface' );

		if ( $buffer_output->has_action() ) {
			$buffer_output->run_actions();
		}
	}
}
