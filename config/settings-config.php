<?php

namespace cookiebot_addons_framework\config;

class Settings_Config {

	/**
	 * @var \DI\Container
	 */
	protected $settings_service;

	public function __construct( $settings_service ) {
		$this->settings_service = $settings_service;
	}

	public function load() {
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_submenu() {
		add_options_page( 'Cookiebot addons', 'Cookiebot addons', 'manage_options', 'cookiebot-addons', array(
			$this,
			'settings_page'
		) );
	}

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


	public function settings_page( $active_tab = '' ) {
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