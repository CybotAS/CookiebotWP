<?php

namespace cookiebot_addons_framework\config;

use cookiebot_addons_framework\lib\Settings_Service_Interface;

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
		add_action( 'admin_enqueue_scripts', array( $this, 'add_wp_admin_style' ) );
	}

	/**
	 * Registers submenu in options menu.
	 *
	 * @since 1.3.0
	 */
	public function add_submenu() {
		add_options_page( 'Cookiebot Addons', __( 'Cookiebot Addons', 'cookiebot-addons' ), 'manage_options', 'cookiebot-addons', array(
			$this,
			'setting_page'
		) );
	}

	/**
	 * Load css styling to the settings page
	 *
	 * @since 1.3.0
	 */
	public function add_wp_admin_style( $hook ) {
		if ( $hook != 'settings_page_cookiebot-addons' ) {
			return;
		}

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

			if( $pagenow == 'options.php'  ) {
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
				add_settings_field( $addon->get_option_name(), $addon->get_addon_name(), array(
					$this,
					"available_addon_callback"
				), "cookiebot-addons", "available_addons", array( 'addon' => $addon ) );

				register_setting( 'cookiebot_available_addons', "cookiebot_available_addons" );
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
					foreach ( $addon->get_widgets() as $option => $widget ) {
						add_settings_field( $option, $widget, array(
							$this,
							"jetpack_addon_callback"
						), "cookiebot-addons", "jetpack_addon", array(
							'option' => $option,
							'label'  => $widget,
							'addon'  => $addon
						) );

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
	 */
	private function register_unavailable_addons() {
		add_settings_section( "unavailable_addons", "Unavailable plugins", array(
			$this,
			"header_unavailable_addons"
		), "cookiebot-addons" );

		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( ! $addon->is_addon_installed() || ! $addon->is_addon_activated() ) {
				// not installed plugins
				add_settings_field( $addon->get_addon_name(), $addon->get_addon_name(), array(
					$this,
					"unavailable_addon_callback"
				), "cookiebot-addons", "unavailable_addons", array( 'addon' => $addon ) );
				register_setting( $addon->get_option_name(), "cookiebot_unavailable_addons" );
			}
		}
	}

	/**
	 * Jetpack tab - header
     *
     * @since 1.3.0
	 */
	public function header_jetpack_addon() {
		?>
        <p>
			<?php _e( 'Jetpack settings.', 'cookiebot-addons' ); ?>
        </p>
		<?php
	}

	/**
     * Jetpack tab - widget callback
     *
	 * @param $args array   Information about the widget addon and the option
     *
     * @since 1.3.0
	 */
	public function jetpack_addon_callback( $args ) {
		$option = $args['option'];
		$addon  = $args['addon'];

		?>
        <div class="postbox cookiebot-addon">
            <p>
                <label for="<?php echo 'enabled_' . $option; ?>"><?php _e( 'Enable', 'cookie-addons' ); ?></label>
                <input type="checkbox" id="<?php echo 'enabled_' . $option; ?>"
                       name="cookiebot_jetpack_addon[<?php echo $option; ?>][enabled]"
                       value="1" <?php checked( 1, $addon->is_widget_enabled( $option ), true ); ?> />
            </p>
            <p>
                <span><?php _e( 'Check one or multiple cookie types:', 'cookiebot-addons' ); ?></span><br>
            <ul class="cookietypes">
                <li><input type="checkbox" id="cookie_type_necessary_<?php echo $option; ?>"
                           value="necessary"
                           name="cookiebot_jetpack_addon[<?php echo $option; ?>][cookie_type][]"
						<?php cookiebot_checked_selected_helper( $addon->get_widget_cookie_types( $option ), 'necessary' ); ?>>
                    <label>Necessary</label>
                </li>
                <li><input type="checkbox" id="cookie_type_preferences_<?php echo $option; ?>"
                           value="preferences"
						<?php cookiebot_checked_selected_helper( $addon->get_widget_cookie_types( $option ), 'preferences' ); ?>
                           name="cookiebot_jetpack_addon[<?php echo $option; ?>][cookie_type][]"><label>Preferences</label>
                </li>
                <li><input type="checkbox" id="cookie_type_statistics_<?php echo $option; ?>"
                           value="statistics"
						<?php cookiebot_checked_selected_helper( $addon->get_widget_cookie_types( $option ), 'statistics' ); ?>
                           name="cookiebot_jetpack_addon[<?php echo $option; ?>][cookie_type][]"><label>Statistics</label>
                </li>
                <li><input type="checkbox" id="cookie_type_marketing_<?php echo $option; ?>"
                           value="marketing"
						<?php cookiebot_checked_selected_helper( $addon->get_widget_cookie_types( $option ), 'marketing' ); ?>
                           name="cookiebot_jetpack_addon[<?php echo $option; ?>][cookie_type][]"><label>Marketing</label>
                </li>
            </ul>
            </p>
            <p>
                <label for=""><?php _e( 'Display a placeholder', 'cookiebot-addons' ); ?></label>
                <input type="checkbox" id="" name="cookiebot_jetpack_addon[<?php echo $option; ?>][placeholder]"
                       value="1" <?php checked( 1, $addon->is_widget_placeholder_enabled( $option ), true ); ?>>
            </p>

        </div>
		<?php
	}

	/**
	 * Returns header for installed plugins
	 *
	 * @since 1.3.0
	 */
	public function header_available_addons() {
		?>
        <p>
			<?php _e( 'Below is a list of addons for Cookiebot. Addons help you make installed plugins GDPR compliant.', 'cookiebot-addons' ); ?>
            <br/>
			<?php _e( 'These addons are available because you have the corresponding plugins installed and activated.', 'cookiebot-addons' ); ?>
            <br/>
			<?php _e( 'Deactivate an addon if you want to handle GDPR compliance yourself, or through another plugin.', 'cookiebot-addons' ); ?>
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
		$addon = $args['addon'];

		?>
        <div class="postbox cookiebot-addon">
            <p>
                <label for="<?php echo 'enabled_' . $addon->get_option_name(); ?>"><?php _e( 'Enable', 'cookie-addons' ); ?></label>
                <input type="checkbox" id="<?php echo 'enabled_' . $addon->get_option_name(); ?>"
                       name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][enabled]"
                       value="1" <?php checked( 1, $addon->is_addon_enabled(), true ); ?> />
            </p>
            <p>
                <span><?php _e( 'Check one or multiple cookie types:', 'cookiebot-addons' ); ?></span><br>
            <ul class="cookietypes">
                <li><input type="checkbox" id="cookie_type_necessary_<?php echo $addon->get_option_name(); ?>"
                           value="necessary"
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types(), 'necessary' ); ?>>
                    <label>Necessary</label>
                </li>
                <li><input type="checkbox" id="cookie_type_preferences_<?php echo $addon->get_option_name(); ?>"
                           value="preferences"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types(), 'preferences' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Preferences</label>
                </li>
                <li><input type="checkbox" id="cookie_type_statistics_<?php echo $addon->get_option_name(); ?>"
                           value="statistics"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types(), 'statistics' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Statistics</label>
                </li>
                <li><input type="checkbox" id="cookie_type_marketing_<?php echo $addon->get_option_name(); ?>"
                           value="marketing"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types(), 'marketing' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Marketing</label>
                </li>
            </ul>
            </p>
        </div>
		<?php
	}

	/**
	 * Returns header for unavailable plugins
	 *
	 * @since 1.3.0
	 */
	public function header_unavailable_addons() {
		?>
        <p>
			<?php _e( 'The following addons are unavailable. This is because the corresponding plugin is not installed.', 'cookiebot-addons' ); ?>
        </p>
		<?php
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
					_e( 'The plugin is not installed.', 'cookiebot-addons' );
				} else if ( ! $addon->is_addon_activated() ) {
					_e( 'The plugin is not activated.', 'cookiebot-addons' );
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
		?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <div id="icon-themes" class="icon32"></div>
            <h2>Cookiebot addons</h2>

			<?php if ( isset( $_GET['tab'] ) ) {
				$active_tab = $_GET['tab'];
			} else if ( $active_tab == 'unavailable_addons' ) {
				$active_tab = 'unavailable_addons';
			} else {
				$active_tab = 'available_addons';
			} ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=cookiebot-addons&tab=available_addons"
                   class="nav-tab <?php echo $active_tab == 'available_addons' ? 'nav-tab-active' : ''; ?>">Available
                    Plugins</a>
                <a href="?page=cookiebot-addons&tab=unavailable_addons"
                   class="nav-tab <?php echo $active_tab == 'unavailable_addons' ? 'nav-tab-active' : ''; ?>">Unavailable
                    Plugins</a>
				<?php
				if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
					?>
                    <a href="?page=cookiebot-addons&tab=jetpack"
                       class="nav-tab <?php echo $active_tab == 'jetpack' ? 'nav-tab-active' : ''; ?>">Jetpack</a>
					<?php
				}
				?>

            </h2>

            <form method="post" action="options.php" class="<?php echo $active_tab; ?>">
				<?php

				if ( $active_tab == 'available_addons' ) {
					settings_fields( 'cookiebot_available_addons' );
					do_settings_sections( 'cookiebot-addons' );
				} elseif ( $active_tab == 'jetpack' ) {
					settings_fields( 'cookiebot_jetpack_addon' );
					do_settings_sections( 'cookiebot-addons' );
				} else {
					settings_fields( 'cookiebot_not_installed_options' );
					do_settings_sections( 'cookiebot-addons' );
				} // end if/else

				submit_button();

				?>
            </form>

        </div><!-- /.wrap -->
		<?php
	}
}
