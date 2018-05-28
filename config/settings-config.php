<?php

namespace cookiebot_addons_framework\config;

use cookiebot_addons_framework\lib\Settings_Service_Interface;

class Settings_Config {

	/**
	 * @var Settings_Service_Interface
	 */
	protected $settings_service;

	public function __construct( Settings_Service_Interface $settings_service ) {
		$this->settings_service = $settings_service;
	}

	public function load() {
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		//add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_wp_admin_style' ) );
	}

	public function add_submenu() {
		add_options_page( 'Cookiebot Addons', __( 'Cookiebot Addons', 'cookiebot-addons' ), 'manage_options', 'cookiebot-addons', array(
			$this,
			'setting_page'
		) );
	}

	/**
	 * TODO Refactor use Settings_Service_Interface to load checkbox fields
	 */
	public function register_settings() {
		add_settings_section( "installed_plugins", "Header Options", array(
			$this,
			"display_header_options_content"
		), "cookiebot-addons" );

		add_settings_section( "not_installed_plugins", "Header Options", array(
			$this,
			"display_header_options_content"
		), "cookiebot-addons" );

		foreach ( $this->container->get( 'plugins' ) as $plugin ) {
			$addon = $this->container->get( $plugin->class );

			if ( $addon->is_plugin_installed() && $_GET['tab'] !== 'not_installed_plugins' ) {
				// installed plugins
				add_settings_field( "addon_enabled", $addon->get_addon_name(), array(
					$this,
					"checkbox_element_callback"
				), "cookiebot-addons", "installed_plugins", array( 'addon' => $addon ) );
				register_setting( "installed_plugins", "addon_enabled" );
			} else if ( ! $addon->is_plugin_installed() && $_GET['tab'] == 'not_installed_plugins' ) {
				// not installed plugins
				add_settings_field( "background_picture", "Picture File Upload", array(
					$this,
					"checkbox_element_callback"
				), "cookiebot-addons", "installed_plugins", array( 'addon' => $addon ) );
				register_setting( "not_installed_plugins", "background_picture", "handle_file_upload" );
			}
		}
	}

	/**
	 *
	 */
	public function add_wp_admin_style( $hook ) {
		if ( $hook != 'settings_page_cookiebot-addons' ) {
			return;
		}

		wp_enqueue_style( 'cookiebot_addons_custom_css', plugins_url( 'style/css/admin_styles.css', dirname( __FILE__ ) ) );
	}

	public function display_header_options_content() {
		echo "The header of the Cookiebot addons";
	}

	public function checkbox_element_callback( $args ) {
		$addon   = $args['addon'];
		$options = get_option( 'sandbox_theme_input_examples' );
		$label   = $addon->get_addon_name();

		$html = '<input type="checkbox" id="checkbox_example" name="sandbox_theme_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
		$html .= '&nbsp;';

		echo $html;
	} // end sandbox_checkbox_element_callback


	/**
     * TODO This will be used for settings through Settings_Service_Interface
     *
	 * @param string $active_tab
	 */
	public function settings_page_refactored_version( $active_tab = '' ) {
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

	/**
	 * Settign page for Cookiebot addons
	 *
	 * @since 1.2.0
	 */
	function setting_page() {
		if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'deactivate' || $_GET['action'] == 'activate' ) ) {
			$active = ( $_GET['action'] == 'activate' ) ? 'yes' : 'no';
			update_option( 'cookiebot-addons-active-' . sanitize_key( $_GET['addon'] ), $active );


			$status = ( $active == 'yes' ) ? 'The addon is now activated' : 'The addon is now deactivated';
			?>
            <div class="updated notice is-dismissible">
                <p><?php _e( $status, 'cookiebot-addons' ); ?></p>
            </div>
			<?php
		}

		$addons = $this->settings_service->get_addon_list();

		?>
        <div class="wrap">
            <h1><?php _e( 'Cookiebot Addons', 'cookiebot-addons' ); ?></h1>
            <p>
				<?php _e( 'Below is a list of addons for Cookiebot. Addons help you making contributed plugins GDPR compliant.', 'cookiebot-addons' ); ?>
                <br/>
				<?php _e( 'Deactive addons if you want to handle GDPR compliance yourself or using another plugin.', 'cookiebot-addons' ); ?>
            </p>
			<?php

			foreach ( $addons['available'] as $plugin_class => $plugin ) {
				?>
                <div class="postbox cookiebot-addon">
                    <h2><?php echo $plugin['name']; ?></h2>
					<?php
					if ( get_option( 'cookiebot-addons-active-' . sanitize_key( $plugin_class ), 'yes' ) == 'yes' ) {
						?>
                        <a href="<?php echo admin_url( 'options-general.php?page=cookiebot-addons&action=deactivate&addon=' . $plugin_class ); ?>">
							<?php _e( 'Deactivate addon', 'cookiebot-addons' ); ?>
                        </a>
						<?php
					} else {
						?>
                        <a href="<?php echo admin_url( 'options-general.php?page=cookiebot-addons&action=activate&addon=' . $plugin_class ); ?>">
							<?php _e( 'Activate addon', 'cookiebot-addons' ); ?>
                        </a>
						<?php
					}
					?>
                </div>
				<?php
			}
			?>
            <h2><?php _e( 'Unavailable Addons', 'cookiebot-addons' ); ?></h2>
            <p>
				<?php _e( 'Following addons are unavailable. This is usual because the addon is not useable because the main plugin is not activated.' ); ?>
            </p>
			<?php
			foreach ( $addons['unavailable'] as $plugin_class => $plugin ) {
				?>
                <div class="postbox cookiebot-addon">
                    <h2><?php echo $plugin['name']; ?></h2>
                    <i><?php _e( 'Unavailable', 'cookiebot-addons' ); ?></i>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}
}