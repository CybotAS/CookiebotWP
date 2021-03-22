<?php

namespace cookiebot_addons\config;

use cookiebot_addons\lib\Settings_Service_Interface;

class Settings_Config {

	/**
	 * @var Settings_Service_Interface
	 */
	protected $settings_service;

	/**
	 * Settings_Config constructor.
	 *
	 * @param Settings_Service_Interface $settings_service
	 *
	 * @since 1.3.0
	 */
	public function __construct( Settings_Service_Interface $settings_service ) {
		$this->settings_service = $settings_service;
	}

	/**
	 * Load data for settings page
	 *
	 * @since 1.3.0
	 */
	public function load() {
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_wp_admin_style_script' ) );
		add_action( 'update_option_cookiebot_available_addons', array(
			$this,
			'post_hook_available_addons_update_option'
		), 10, 3 );
	}

	/**
	 * Registers submenu in options menu.
	 *
	 * @since 1.3.0
	 */
	public function add_submenu() {
		/*add_submenu_page('cookiebot', 'Prior Consent', esc_html__( 'Prior Consent', 'cookiebot' ), 'manage_options', 'cookiebot_addons', array(
			$this,
			'setting_page'
		) );*/

		add_submenu_page( 'cookiebot', esc_html__( 'Prior Consent', 'cookiebot' ), esc_html__( 'Prior Consent', 'cookiebot' ), 'manage_options', 'cookiebot-addons', array(
			$this,
			'setting_page'
		) );

	}

	/**
	 * Load css styling to the settings page
	 *
	 * @since 1.3.0
	 */
	public function add_wp_admin_style_script( $hook ) {
		if ( $hook != 'cookiebot_page_cookiebot-addons' ) {
			return;
		}

		wp_enqueue_script( 'cookiebot_tiptip_js', plugins_url( 'js/jquery.tipTip.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.8', true );
		wp_enqueue_script( 'cookiebot_addons_custom_js', plugins_url( 'js/settings.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.8', true );
		wp_localize_script( 'cookiebot_addons_custom_js', 'php', array( 'remove_link' => ' <a href="" class="submitdelete deletion">' . esc_html__( 'Remove language', 'cookiebot-addons' ) . '</a>' ) );
		wp_enqueue_style( 'cookiebot_addons_custom_css', plugins_url( 'style/css/admin_styles.css', dirname( __FILE__ ) ) );
	}

	/**
	 * Registers addons for settings page.
	 *
	 * @since 1.3.0
	 */
	public function register_settings() {
		global $pagenow;

		if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'cookiebot-addons' ) || $pagenow == 'options.php' ) {
			if ( isset( $_GET['tab'] ) && 'unavailable_addons' === $_GET['tab'] ) {
				$this->register_unavailable_addons();
			} elseif ( ( isset( $_GET['tab'] ) && 'jetpack' === $_GET['tab'] ) ) {
				$this->register_jetpack_addon();
			} else {
				$this->register_available_addons();
			}

			if ( $pagenow == 'options.php' ) {
				$this->register_jetpack_addon();
			}
		}
	}

	/**
	 * Register available addons
	 *
	 * @since 1.3.0
	 */
	private function register_available_addons() {
		add_settings_section( "available_addons", "Available plugins", array(
			$this,
			"header_available_addons"
		), "cookiebot-addons" );

		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( $addon->is_addon_installed() && $addon->is_addon_activated() ) {
				add_settings_field(
					$addon->get_option_name(),
					$addon->get_addon_name() . $this->get_extra_information( $addon ),
					array(
						$this,
						"available_addon_callback"
					),
					"cookiebot-addons",
					"available_addons",
					array(
						'addon' => $addon
					)
				);

				register_setting( 'cookiebot_available_addons', "cookiebot_available_addons", array(
					$this,
					'sanitize_cookiebot'
				) );
			}
		}
	}

	/**
	 * Register jetpack addon - new tab for jetpack specific settings
	 *
	 * @since 1.3.0
	 */
	private function register_jetpack_addon() {
		add_settings_section( "jetpack_addon", "Jetpack", array(
			$this,
			"header_jetpack_addon"
		), "cookiebot-addons" );

		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( 'Jetpack' === ( new \ReflectionClass( $addon ) )->getShortName() ) {
				if ( $addon->is_addon_installed() && $addon->is_addon_activated() ) {

					foreach ( $addon->get_widgets() as $widget ) {
						add_settings_field(
							$widget->get_widget_option_name(),
							$widget->get_label() . $this->get_extra_information( $widget ),
							array(
								$this,
								"jetpack_addon_callback"
							),
							"cookiebot-addons",
							"jetpack_addon",
							array(
								'widget' => $widget,
								'addon'  => $addon
							)
						);

						register_setting( 'cookiebot_jetpack_addon', 'cookiebot_jetpack_addon' );
					}
				}
			}
		}
	}

	/**
	 * Registers unavailabe addons
	 *
	 * @since 1.3.0
	 * @version 2.1.3
	 */
	private function register_unavailable_addons() {
		add_settings_section( "unavailable_addons", "Unavailable plugins", array(
			$this,
			"header_unavailable_addons"
		), "cookiebot-addons" );

		$addons = $this->settings_service->get_addons();

		foreach ( $addons as $addon ) {
			if ( ( ! $addon->is_addon_installed() || ! $addon->is_addon_activated() )
			     && $this->settings_service->is_latest_plugin_version( $addon )
			     && ! $this->settings_service->is_previous_version_active(
					$addons,
					get_class( $addon )
				)
			) {
				// not installed plugins
				add_settings_field(
					$addon->get_addon_name(),
					$addon->get_addon_name() . $this->get_extra_information( $addon ),
					array(
						$this,
						"unavailable_addon_callback"
					),
					"cookiebot-addons",
					"unavailable_addons",
					array( 'addon' => $addon )
				);
				register_setting( $addon->get_option_name(), "cookiebot_unavailable_addons" );
			}
		}
	}

	/**
	 * Adds extra information under the label.
	 *
	 * @param $addon
	 *
	 * @return string
	 */
	private function get_extra_information( $addon ) {
		return ( $addon->get_extra_information() !== false ) ? '<div class="extra_information">' . $addon->get_extra_information() . '</div>' : '';
	}

	/**
	 * Jetpack tab - header
	 *
	 * @since 1.3.0
	 */
	public function header_jetpack_addon() {
		echo '<p>' . esc_html__( 'Jetpack settings.', 'cookiebot' ) . '</p>';
	}

	/**
	 * Jetpack tab - widget callback
	 *
	 * @param $args array   Information about the widget addon and the option
	 *
	 * @since 1.3.0
	 */
	public function jetpack_addon_callback( $args ) {
		include COOKIEBOT_ADDONS_DIR . 'view/admin/settings/jetpack-addon-callback.php';
	}

	/**
	 * Returns header for installed plugins
	 *
	 * @since 1.3.0
	 */
	public function header_available_addons() {
		?>
        <p>
			<?php esc_html_e( 'Below is a list of addons for Cookiebot. Addons help you make installed plugins GDPR compliant.', 'cookiebot' ); ?>
            <br/>
			<?php esc_html_e( 'These addons are available because you have the corresponding plugins installed and activated.', 'cookiebot' ); ?>
            <br/>
			<?php esc_html_e( 'Deactivate an addon if you want to handle GDPR compliance yourself, or through another plugin.', 'cookiebot' ); ?>
        </p>
		<?php
	}

	/**
	 * Available addon callback:
	 * - checkbox to enable
	 * - select field for cookie type
	 *
	 * @param $args
	 *
	 * @since 1.3.0
	 */
	public function available_addon_callback( $args ) {
		include COOKIEBOT_ADDONS_DIR . 'view/admin/settings/available-addon-callback.php';
	}

	/**
	 * Returns header for unavailable plugins
	 *
	 * @since 1.3.0
	 */
	public function header_unavailable_addons() {
		echo '<p>' . esc_html__( 'The following addons are unavailable. This is because the corresponding plugin is not installed.', 'cookiebot' ) . '</p>';
	}

	/**
	 * Unavailable addon callback
	 *
	 * @param $args
	 *
	 * @since 1.3.0
	 */
	public function unavailable_addon_callback( $args ) {
		$addon = $args['addon'];

		?>
        <div class="postbox cookiebot-addon">
            <i><?php
				if ( ! $addon->is_addon_installed() ) {
					esc_html_e( 'The plugin is not installed.', 'cookiebot' );
				} else if ( ! $addon->is_addon_activated() ) {
					esc_html_e( 'The plugin is not activated.', 'cookiebot' );
				}
				?></i>
        </div>
		<?php
	}

	/**
	 * Build up settings page
	 *
	 * @param string $active_tab
	 *
	 * @since 1.3.0
	 */
	public function setting_page( $active_tab = '' ) {
		include COOKIEBOT_ADDONS_DIR . 'view/admin/settings/setting-page.php';
	}

	/**
     * Post action hook after enabling the addon on the settings page.
     *
	 * @param $old_value
	 * @param $value
	 * @param $option_name
     *
     * @since 2.2.0
	 */
	public function post_hook_available_addons_update_option( $old_value, $value, $option_name ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $addon_option_name => $addon_settings ) {
				if ( isset( $addon_settings['enabled'] ) ) {
					$this->settings_service->post_hook_after_enabling_addon_on_settings_page( $addon_option_name );
				}
			}
		}
	}
}
