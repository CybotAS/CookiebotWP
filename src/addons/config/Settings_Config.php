<?php

namespace cybot\cookiebot\addons\config;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Theme_Addon;
use cybot\cookiebot\addons\controller\addons\jetpack\Jetpack;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Base_Jetpack_Widget;
use cybot\cookiebot\lib\Settings_Page_Tab;
use cybot\cookiebot\lib\Settings_Service_Interface;
use cybot\cookiebot\lib\Cookiebot_WP;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\cookiebot_addons_get_dropdown_languages;
use function cybot\cookiebot\lib\get_view_html;
use function cybot\cookiebot\lib\include_view;

class Settings_Config {



	/**
	 * @var Settings_Service_Interface
	 */
	protected $settings_service;

	const ADMIN_SLUG                        = 'cookiebot-addons';
	const LANGUAGE_DROPDOWN_OPTION_REPLACE  = '%optionname%';
	const JETPACK_DEFAULT_LANGUAGE_DROPDOWN = 'cookiebot_jetpack_addon[%optionname%][placeholder][languages][site-default]';
	const ADDONS_DEFAULT_LANGUAGE_DROPDOWN  = 'cookiebot_available_addons[%optionname%][placeholder][languages][site-default]';
	// Templates
	const INFO_HEADER_TEMPLATE            = 'admin/settings/prior-consent/partials/info-tab-header.php';
	const EXTRA_INFO_TEMPLATE             = 'admin/settings/prior-consent/partials/extra-information.php';
	const JETPACK_TAB_HEADER_TEMPLATE     = 'admin/settings/prior-consent/jetpack-widgets/tab-header.php';
	const JETPACK_WIDGET_TAB_TEMPLATE     = 'admin/settings/prior-consent/jetpack-widgets/tab.php';
	const PLACEHOLDER_TEMPLATE            = 'admin/settings/prior-consent/partials/placeholder-submitboxes.php';
	const DEFAULT_PLACEHOLDER_TEMPLATE    = 'admin/settings/prior-consent/partials/placeholder-submitbox-default.php';
	const AVAILABLE_TAB_HEADER_TEMPLATE   = 'admin/settings/prior-consent/available-addons/tab-header.php';
	const AVAILABLE_ADDONS_TAB_TEMPLATE   = 'admin/settings/prior-consent/available-addons/tab.php';
	const UNAVAILABLE_TAB_HEADER_TEMPLATE = 'admin/settings/prior-consent/unavailable-addons/tab-header.php';
	const UNAVAILABLE_ADDONS_TAB_TEMPLATE = 'admin/settings/prior-consent/unavailable-addons/field.php';

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
		add_action( 'admin_menu', array( $this, 'add_submenu' ), 2 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_wp_admin_style_script' ) );
		add_action(
			'update_option_cookiebot_available_addons',
			array(
				$this,
				'post_hook_available_addons_update_option',
			),
			10,
			3
		);
	}

	/**
	 * Registers submenu in options menu.
	 *
	 * @since 1.3.0
	 */
	public function add_submenu() {
		add_submenu_page(
			'cookiebot',
			esc_html__( 'Plugins', 'cookiebot' ),
			esc_html__( 'Plugins', 'cookiebot' ),
			'manage_options',
			'cookiebot-addons',
			array(
				$this,
				'setting_page',
			),
			2
		);
	}

	/**
	 * Load css styling to the settings page
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function add_wp_admin_style_script( $hook ) {
		if ( $hook !== 'cookiebot_page_cookiebot-addons' ) {
			return;
		}

		wp_enqueue_script(
			'cookiebot_tiptip_js',
			asset_url( 'js/backend/jquery.tipTip.js' ),
			array( 'jquery' ),
			'1.8',
			true
		);
		wp_enqueue_script(
			'cookiebot_addons_custom_js',
			asset_url( 'js/backend/prior-consent-settings.js' ),
			array( 'jquery' ),
			'1.8',
			true
		);
		wp_localize_script(
			'cookiebot_addons_custom_js',
			'php',
			array( 'remove_link' => ' <a href="" class="submitdelete deletion">' . esc_html__( 'Remove language', 'cookiebot' ) . '</a>' )
		);
		wp_enqueue_style(
			'cookiebot_addons_custom_css',
			asset_url( 'css/backend/addons_page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
		wp_enqueue_style(
			'cookiebot_admin_css',
			asset_url( 'css/backend/cookiebot_admin_main.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}

	/**
	 * Registers addons for settings page.
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	public function register_settings() {
		global $pagenow;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ( isset( $_GET['page'] ) && $_GET['page'] === 'cookiebot-addons' ) || $pagenow === 'options.php' ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['tab'] ) && 'unavailable_addons' === $_GET['tab'] ) {
				$this->register_unavailable_addons();
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_GET['tab'] ) && 'available_addons' === $_GET['tab'] ) {
				$this->register_available_addons();
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_GET['tab'] ) && 'jetpack' === $_GET['tab'] ) {
				$this->register_jetpack_addon();
			} else {
				$this->register_addons_info();
			}

			if ( $pagenow === 'options.php' ) {
				$this->register_jetpack_addon();
				$this->register_available_addons();
			}
		}
	}

	/**
	 * Register addons info
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	private function register_addons_info() {
		add_settings_section(
			'info_addons',
			'',
			array(
				$this,
				'header_addons_info',
			),
			'cookiebot-addons'
		);
	}

	/**
	 * Returns header for info tab
	 *
	 * @since 1.3.0
	 */
	public function header_addons_info() {
		include_view( self::INFO_HEADER_TEMPLATE );
	}

	/**
	 * Register available addons
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	private function register_available_addons() {
		add_settings_section(
			'available_addons',
			'',
			array(
				$this,
				'header_available_addons',
			),
			'cookiebot-addons'
		);

		/** @var Base_Cookiebot_Addon $addon */
		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( $addon->is_addon_installed() && $addon->is_addon_activated() ) {
				add_settings_field(
					$addon::OPTION_NAME,
					get_view_html(
						$this::EXTRA_INFO_TEMPLATE,
						array(
							'label'                   => $addon::ADDON_NAME,
							'extra_information_lines' => $addon->get_extra_information(),
						)
					),
					array(
						$this,
						'available_addon_callback',
					),
					'cookiebot-addons',
					'available_addons',
					array(
						'addon' => $addon,
					)
				);

				register_setting(
					'cookiebot_available_addons',
					'cookiebot_available_addons',
					array(
						$this,
						'sanitize_cookiebot',
					)
				);
			}
		}
	}

	/**
	 * Register jetpack addon - new tab for jetpack specific settings
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	private function register_jetpack_addon() {
		add_settings_section(
			'jetpack_addon',
			'',
			array(
				$this,
				'jetpack_addons_header_callback',
			),
			'cookiebot-addons'
		);

		/** @var Jetpack $addon */
		foreach ( $this->settings_service->get_addons() as $addon ) {
			if ( 'Jetpack' === ( new ReflectionClass( $addon ) )->getShortName() &&
				$addon->is_addon_installed() && $addon->is_addon_activated() ) {
				foreach ( $addon->get_widgets() as $widget ) {
					add_settings_field(
						$widget->get_widget_option_name(),
						get_view_html(
							$this::EXTRA_INFO_TEMPLATE,
							array(
								'label'                   => $widget->get_label(),
								'extra_information_lines' => $widget->get_extra_information(),
							)
						),
						array(
							$this,
							'jetpack_addon_callback',
						),
						'cookiebot-addons',
						'jetpack_addon',
						array(
							'widget' => $widget,
							'addon'  => $addon,
						)
					);

					register_setting( 'cookiebot_jetpack_addon', 'cookiebot_jetpack_addon' );
				}
			}
		}
	}

	/**
	 * Registers unavailabe addons
	 *
	 * @throws Exception
	 * @version 2.1.3
	 * @since 1.3.0
	 */
	private function register_unavailable_addons() {
		add_settings_section(
			'unavailable_addons',
			'',
			array(
				$this,
				'unavailable_addons_header_callback',
			),
			'cookiebot-addons'
		);

		$addons = $this->settings_service->get_addons();

		/** @var Base_Cookiebot_Addon $addon */
		foreach ( $addons as $addon ) {
			if ( ! $addon->is_addon_installed() || ! $addon->is_addon_activated() ) {
				// not installed plugins
				add_settings_field(
					$addon::ADDON_NAME,
					get_view_html(
						$this::EXTRA_INFO_TEMPLATE,
						array(
							'label'                   => $addon::ADDON_NAME,
							'extra_information_lines' => $addon->get_extra_information(),
						)
					),
					array(
						$this,
						'unavailable_addon_settings_field_callback',
					),
					'cookiebot-addons',
					'unavailable_addons',
					array( 'addon' => $addon )
				);
				register_setting( $addon::OPTION_NAME, 'cookiebot_unavailable_addons' );
			}
		}
	}

	/**
	 * Jetpack tab - header
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function jetpack_addons_header_callback() {
		include_view( self::JETPACK_TAB_HEADER_TEMPLATE );
	}

	/**
	 * Jetpack tab - widget callback
	 *
	 * @param $args array   Information about the widget addon and the option
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function jetpack_addon_callback( $args ) {
		$widget = isset( $args['widget'] ) ? $args['widget'] : null;
		$addon  = isset( $args['addon'] ) ? $args['addon'] : null;

		if ( ! is_a( $widget, Base_Jetpack_Widget::class ) ) {
			throw new InvalidArgumentException();
		}

		if ( ! is_a( $addon, Base_Cookiebot_Addon::class ) ) {
			throw new InvalidArgumentException();
		}

		$widget_is_enabled                    = $widget->is_widget_enabled();
		$widget_placeholder_is_enabled        = $widget->is_widget_placeholder_enabled();
		$widget_default_placeholder           = $widget->get_widget_default_placeholder();
		$widget_option_name                   = $widget->get_widget_option_name();
		$widget_placeholders_array            = $widget->get_widget_placeholders();
		$widget_placeholders_array_keys       = array_keys( $widget_placeholders_array );
		$first_placeholder_language           = isset( $widget_placeholders_array_keys[0] )
			? $widget_placeholders_array_keys[0]
			: null;
		$site_default_languages_dropdown_html = cookiebot_addons_get_dropdown_languages(
			'placeholder_select_language',
			str_replace(
				self::LANGUAGE_DROPDOWN_OPTION_REPLACE,
				$widget_option_name,
				self::JETPACK_DEFAULT_LANGUAGE_DROPDOWN
			),
			'site-default'
		);
		$widget_placeholders                  = array_map(
			function (
				$language,
				$placeholder
			) use (
				$widget_option_name,
				$first_placeholder_language
			) {
				$removable               = $first_placeholder_language !== $language;
				$option_name             = str_replace(
					array( self::LANGUAGE_DROPDOWN_OPTION_REPLACE, 'site-default' ),
					array( $widget_option_name, $language ),
					self::JETPACK_DEFAULT_LANGUAGE_DROPDOWN
				);
				$languages_dropdown_html = cookiebot_addons_get_dropdown_languages(
					'placeholder_select_language',
					$option_name,
					$language
				);
				return array(
					'name'                    => $option_name,
					'removable'               => $removable,
					'language'                => $language,
					'placeholder'             => $placeholder,
					'languages_dropdown_html' => $languages_dropdown_html,
				);
			},
			array_keys( $widget_placeholders_array ),
			array_values( $widget_placeholders_array )
		);
		$placeholder_helper                   = $addon->get_placeholder_helper();
		$placeholders_html                    = $widget->widget_has_placeholder()
			? get_view_html(
				self::PLACEHOLDER_TEMPLATE,
				array(
					'placeholders'       => $widget_placeholders,
					'placeholder_helper' => $placeholder_helper,
				)
			)
			: get_view_html(
				self::DEFAULT_PLACEHOLDER_TEMPLATE,
				array(
					'site_default_languages_dropdown_html' => $site_default_languages_dropdown_html,
					'name'                                 => str_replace(
						self::LANGUAGE_DROPDOWN_OPTION_REPLACE,
						$widget_option_name,
						self::JETPACK_DEFAULT_LANGUAGE_DROPDOWN
					),
					'default_placeholder'                  => $widget_default_placeholder,
					'placeholder_helper'                   => $placeholder_helper,
				)
			);

		$view_args = array(
			'widget_option_name'            => $widget_option_name,
			'widget_is_enabled'             => $widget_is_enabled,
			'widget_cookie_types'           => $widget->get_widget_cookie_types(),
			'widget_placeholder_is_enabled' => $widget_placeholder_is_enabled,
			'placeholders_html'             => $placeholders_html,
		);

		include_view( self::JETPACK_WIDGET_TAB_TEMPLATE, $view_args );
	}

	/**
	 * Returns header for installed plugins
	 *
	 * @since 1.3.0
	 */
	public function header_available_addons() {
		 include_view( self::AVAILABLE_TAB_HEADER_TEMPLATE );
	}

	/**
	 * Available addon callback:
	 * - checkbox to enable
	 * - select field for cookie type
	 *
	 * @param $args
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function available_addon_callback( $args ) {
		$addon = isset( $args['addon'] ) ? $args['addon'] : null;

		if ( ! is_a( $addon, Base_Cookiebot_Addon::class ) ) {
			throw new InvalidArgumentException();
		}

		$site_default_languages_dropdown_html = cookiebot_addons_get_dropdown_languages(
			'placeholder_select_language',
			str_replace(
				self::LANGUAGE_DROPDOWN_OPTION_REPLACE,
				$addon::OPTION_NAME,
				self::ADDONS_DEFAULT_LANGUAGE_DROPDOWN
			),
			'site-default'
		);
		$addon_placeholders_array             = $addon->get_placeholders();
		$addon_placeholders_array_keys        = array_keys( $addon_placeholders_array );
		$first_placeholder_language           = isset( $addon_placeholders_array_keys[0] )
			? $addon_placeholders_array_keys[0]
			: null;
		$addon_placeholders                   = array_map(
			function (
				$language,
				$placeholder
			) use (
				$addon,
				$first_placeholder_language
			) {
				$removable               = $first_placeholder_language !== $language;
				$option_name             = str_replace(
					array( self::LANGUAGE_DROPDOWN_OPTION_REPLACE, 'site-default' ),
					array( $addon::OPTION_NAME, $language ),
					self::ADDONS_DEFAULT_LANGUAGE_DROPDOWN
				);
				$languages_dropdown_html = cookiebot_addons_get_dropdown_languages(
					'placeholder_select_language',
					$option_name,
					$language
				);
				return array(
					'name'                    => $option_name,
					'removable'               => $removable,
					'language'                => $language,
					'placeholder'             => $placeholder,
					'languages_dropdown_html' => $languages_dropdown_html,
				);
			},
			$addon_placeholders_array_keys,
			$addon_placeholders_array
		);
		$placeholder_helper                   = $addon->get_placeholder_helper();
		$addon_extra_options_html             = $addon->get_extra_addon_options_html();
		$placeholders_html                    = $addon->has_placeholder()
			? get_view_html(
				self::PLACEHOLDER_TEMPLATE,
				array(
					'placeholders'       => $addon_placeholders,
					'placeholder_helper' => $placeholder_helper,
				)
			)
			: get_view_html(
				self::DEFAULT_PLACEHOLDER_TEMPLATE,
				array(
					'site_default_languages_dropdown_html' => $site_default_languages_dropdown_html,
					'name'                                 => str_replace(
						self::LANGUAGE_DROPDOWN_OPTION_REPLACE,
						$addon::OPTION_NAME,
						self::ADDONS_DEFAULT_LANGUAGE_DROPDOWN
					),
					'default_placeholder'                  => $addon::DEFAULT_PLACEHOLDER_CONTENT,
					'placeholder_helper'                   => $placeholder_helper,
				)
			);

		$view_args = array(
			'addon_option_name'            => $addon::OPTION_NAME,
			'addon_is_enabled'             => $addon->is_addon_enabled(),
			'addon_cookie_types'           => $addon->get_cookie_types(),
			'addon_placeholder_is_enabled' => $addon->is_placeholder_enabled(),
			'placeholders_html'            => $placeholders_html,
			'addon_extra_options_html'     => $addon_extra_options_html,
		);

		include_view( self::AVAILABLE_ADDONS_TAB_TEMPLATE, $view_args );
	}

	/**
	 * Returns header for unavailable plugins
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function unavailable_addons_header_callback() {
		include_view( self::UNAVAILABLE_TAB_HEADER_TEMPLATE );
	}

	/**
	 * @param $args
	 *
	 * @throws InvalidArgumentException
	 */
	public function unavailable_addon_settings_field_callback( $args ) {
		$addon = $args['addon'];

		if ( ! is_a( $addon, Base_Cookiebot_Addon::class ) ) {
			throw new InvalidArgumentException();
		}

		$message = '';
		if ( ! $addon->is_addon_installed() ) {
			if ( is_a( $addon, Base_Cookiebot_Plugin_Addon::class ) ) {
				$message = __( 'The plugin is not installed.', 'cookiebot' );
			}
			if ( is_a( $addon, Base_Cookiebot_Theme_Addon::class ) ) {
				$message = __( 'The theme is not installed.', 'cookiebot' );
			}
		} elseif ( ! $addon->is_addon_activated() ) {
			if ( is_a( $addon, Base_Cookiebot_Plugin_Addon::class ) ) {
				$message = __( 'The plugin is not activated.', 'cookiebot' );
			}
			if ( is_a( $addon, Base_Cookiebot_Theme_Addon::class ) ) {
				$message = __( 'The theme is not activated.', 'cookiebot' );
			}
		}

		$view_args = array(
			'message' => $message,
		);
		include_view( self::UNAVAILABLE_ADDONS_TAB_TEMPLATE, $view_args );
	}

	/**
	 * Build up settings page
	 *
	 * @throws InvalidArgumentException
	 * @since 1.3.0
	 */
	public function setting_page() {
		$addons_info_tab        = new Settings_Page_Tab(
			'addons_info',
			esc_html__( 'Info', 'cookiebot' ),
			'info_addons',
			'cookiebot-addons',
			false
		);
		$available_addons_tab   = new Settings_Page_Tab(
			'available_addons',
			esc_html__( 'Available Add-ons', 'cookiebot' ),
			'cookiebot_available_addons',
			'cookiebot-addons'
		);
		$unavailable_addons_tab = new Settings_Page_Tab(
			'unavailable_addons',
			esc_html__( 'Unavailable Add-ons', 'cookiebot' ),
			'cookiebot_not_installed_options',
			'cookiebot-addons',
			false
		);
		$settings_page_tabs     = array(
			$addons_info_tab,
			$available_addons_tab,
			$unavailable_addons_tab,
		);
		if ( is_plugin_active( Jetpack::PLUGIN_FILE_PATH ) ) {
			$settings_page_tabs[] = new Settings_Page_Tab(
				'jetpack',
				esc_html__( 'Jetpack', 'cookiebot' ),
				'cookiebot_jetpack_addon',
				'cookiebot-addons'
			);
		}
		$active_tab = array_reduce(
			$settings_page_tabs,
			function ( $active_tab, Settings_Page_Tab $settings_page_tab ) {
				if ( ! is_null( $active_tab ) ) {
					return $active_tab;
				}
				if ( $settings_page_tab->is_active() ) {
					return $settings_page_tab;
				}
				return null;
			},
			null
		);
		if ( ! $active_tab ) {
			$addons_info_tab->set_is_active( true );
			$active_tab = $addons_info_tab;
		}
		$view_args = array(
			'settings_page_tabs' => $settings_page_tabs,
			'active_tab'         => $active_tab,
		);
		include_view( 'admin/settings/prior-consent/page.php', $view_args );
	}

	/**
	 * Post action hook after enabling the addon on the settings page.
	 *
	 * @param $old_value
	 * @param $value
	 * @param $option_name
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function post_hook_available_addons_update_option( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $addon_option_name => $addon_settings ) {
				if ( isset( $addon_settings['enabled'] ) ) {
					$this->settings_service->post_hook_after_enabling_addon_on_settings_page( $addon_option_name );
				}
			}
		}
	}
}
