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
		if ( 'unavailable_addons' === $_GET['tab'] ) {
			$this->register_unavailable_addons();
		} else {
			$this->register_available_addons();
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
	 * Returns header for installed plugins
	 *
	 * @since 1.3.0
	 */
	public function header_available_addons() {
		?>
        <p>
			<?php _e( 'Below is a list of addons for Cookiebot. Addons help you making contributed plugins GDPR compliant.', 'cookiebot-addons' ); ?>
            <br/>
			<?php _e( 'These addons are available because you have the corresponding plugins installed and activated.', 'cookiebot-addons' ); ?>
            <br/>
			<?php _e( 'Deactive addons if you want to handle GDPR compliance yourself or using another plugin.', 'cookiebot-addons' ); ?>
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
            <label for="<?php echo 'enabled_' . $addon->get_option_name(); ?>"><?php _e( 'Enable', 'cookie-addons' ); ?></label>
            <input type="checkbox" id="<?php echo 'enabled_' . $addon->get_option_name(); ?>"
                   name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][enabled]"
                   value="1" <?php checked( 1, $addon->is_addon_enabled( $addon->get_option_name() ), true ); ?> />

            <br><br>


            <p>
                <span><?php _e( 'Check one or multiple cookie types:', 'cookiebot-addons' ); ?></span><br>
            <ul class="cookietypes">
                <li><input type="checkbox" id="cookie_type_necessary_<?php echo $addon->get_option_name(); ?>"
                           value="necessary"
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][cookie_type][]"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types( $addon->get_option_name() ), 'necessary' ); ?>>
                    <label>Necessary</label>
                </li>
                <li><input type="checkbox" id="cookie_type_preferences_<?php echo $addon->get_option_name(); ?>"
                           value="preferences"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types( $addon->get_option_name() ), 'preferences' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][cookie_type][]"><label>Preferences</label>
                </li>
                <li><input type="checkbox" id="cookie_type_statistics_<?php echo $addon->get_option_name(); ?>"
                           value="statistics"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types( $addon->get_option_name() ), 'statistics' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][cookie_type][]"><label>Statistics</label>
                </li>
                <li><input type="checkbox" id="cookie_type_marketing_<?php echo $addon->get_option_name(); ?>"
                           value="marketing"
						<?php cookiebot_checked_selected_helper( $addon->get_cookie_types( $addon->get_option_name() ), 'marketing' ); ?>
                           name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][cookie_type][]"><label>Marketing</label>
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
			<?php _e( 'Following addons are unavailable. This is usual because the addon is not useable because the main plugin is not activated or installed.' ); ?>
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
					_e( 'The addon is not installed.', 'cookiebot-addons' );
				} else if ( ! $addon->is_addon_activated() ) {
					_e( 'The addon is not activated.', 'cookiebot-addons' );
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
			} // end if/else ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=cookiebot-addons&tab=available_addons"
                   class="nav-tab <?php echo $active_tab == 'available_addons' ? 'nav-tab-active' : ''; ?>">Available
                    Addons</a>
                <a href="?page=cookiebot-addons&tab=unavailable_addons"
                   class="nav-tab <?php echo $active_tab == 'unavailable_addons' ? 'nav-tab-active' : ''; ?>">Unavailable
                    Addons</a>
            </h2>

            <form method="post" action="options.php" class="<?php echo $active_tab; ?>">
				<?php

				if ( $active_tab == 'available_addons' ) {
					settings_fields( 'cookiebot_available_addons' );
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