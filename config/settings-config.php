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
	 * Registers addons for settings page.
	 *
	 * @since 1.3.0
	 */
	public function register_settings() {
		if ( 'not_installed_plugins' === $_GET['tab'] ) {
			add_settings_section( "not_installed_plugins", "Unavailable plugins", array(
				$this,
				"header_uninstalled_plugins"
			), "cookiebot-addons" );
		} else {
			add_settings_section( "installed_plugins", "Available plugins", array(
				$this,
				"header_installed_plugins"
			), "cookiebot-addons" );
		}

		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( $addon->is_addon_enabled() && $addon->is_addon_installed() && 'not_installed_plugins' !== $_GET['tab'] ) {
				add_settings_field( $addon->get_addon_name(), $addon->get_addon_name(), array(
					$this,
					"available_addon_callback"
				), "cookiebot-addons", "installed_plugins", array( 'addon' => $addon ) );
				register_setting( "cookiebot_installed_plugins", "addon_enabled" );
			} else if ( 'not_installed_plugins' === $_GET['tab'] && ( ! $addon->is_addon_enabled() || ! $addon->is_addon_installed() ) ) {
				// not installed plugins
				add_settings_field( "uninstalled_" . $addon->get_addon_name(), $addon->get_addon_name(), array(
					$this,
					"unavailable_addon_callback"
				), "cookiebot-addons", "not_installed_plugins", array( 'addon' => $addon ) );
				register_setting( "cookiebot_not_installed_plugins", "background_picture", "handle_file_upload" );
			}
		}
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
	 * Returns header for installed plugins
	 *
	 * @since 1.3.0
	 */
	public function header_installed_plugins() {
		?>
        <p>
			<?php _e( 'Below is a list of addons for Cookiebot. Addons help you making contributed plugins GDPR compliant.', 'cookiebot-addons' ); ?>
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

		//$option_values = $addon->get_option_values();

        $option_values = array('checkbox' => 1, 'selected' => 'statistics');
		?>
        <div class="postbox cookiebot-addon">
            <label for="<?php echo 'checkbox_' . $addon->get_option_name(); ?>"><?php _e( 'Enable', 'cookie-addons' ); ?></label>
            <input type="checkbox" id="<?php echo 'checkbox_' . $addon->get_option_name(); ?>"
                   name="<?php echo 'checkbox_' . $addon->get_option_name(); ?>"
                   value="1" <?php checked( 1, $option_values['checkbox'], true ); ?> />


            <p>
                <label for="<?php echo 'select_' . $addon->get_option_name(); ?>">Cookie type: </label>
                <select name="<?php echo 'select_' . $addon->get_option_name(); ?>"
                        id="<?php echo 'select_' . $addon->get_option_name(); ?>">
                    <option value="necessary" <?php selected( $option_values['selected'], 'necessary' ); ?>>Necessary
                    </option>
                    <option value="preferences" <?php selected( $option_values['selected'], 'preferences' ); ?>>
                        Preferences
                    </option>
                    <option value="statistics" <?php selected( $option_values['selected'], 'statistics' ); ?>>
                        Statistics
                    </option>
                    <option value="marketing" <?php selected( $option_values['selected'], 'marketing' ); ?>>Marketing
                    </option>
                </select>
            </p>
        </div>
		<?php
	}

	/**
	 * Returns header for unavailable plugins
	 *
	 * @since 1.3.0
	 */
	public function header_uninstalled_plugins() {
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
            <i><?php _e( 'Unavailable', 'cookiebot-addons' ); ?></i>
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
			<?php settings_errors(); ?>

			<?php if ( isset( $_GET['tab'] ) ) {
				$active_tab = $_GET['tab'];
			} else if ( $active_tab == 'not_installed_plugins' ) {
				$active_tab = 'not_installed_plugins';
			} else {
				$active_tab = 'installed_plugins';
			} // end if/else ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=cookiebot-addons&tab=installed_plugins"
                   class="nav-tab <?php echo $active_tab == 'installed_plugins' ? 'nav-tab-active' : ''; ?>">Installed
                    plugins</a>
                <a href="?page=cookiebot-addons&tab=not_installed_plugins"
                   class="nav-tab <?php echo $active_tab == 'not_installed_plugins' ? 'nav-tab-active' : ''; ?>">Not
                    installed plugins</a>
            </h2>

            <form method="post" action="options.php">
				<?php

				if ( $active_tab == 'installed_plugins' ) {
					settings_fields( 'cookiebot_installed_options' );
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