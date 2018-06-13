<?php

namespace cookiebot_addons_framework\controller\addons\host_analyticsjs_local;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;

class Host_Analyticsjs_Local implements Cookiebot_Addons_Interface {

	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Complete Analytics Optimization Suite (CAOS) (Host Analyticsjs Local) constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_host_analyticsjs_local' ), 5 );
	}

	/**
	 * Check for Host Analyticsjs Local action hooks
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_host_analyticsjs_local() {
		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}
		
		// consent is given
		if( $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			return;
		}
		
		/* Priority need to be more than 0 so we are able to hook in before output begins */
		$scriptPriority = $this->cookiebot_addon_host_analyticsjs_local_priority();
		if( $scriptPriority <= 0 ) {
			//Force priority to 2
			$scriptPriority = 2;
			update_option( 'sgal_enqueue_order', $scriptPriority );
		}

		/**
		 * ga scripts are loaded in wp_footer priority is defined in option variable
		 */
		if ( has_action( 'wp_footer', 'add_ga_header_script' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag( 'wp_footer', $scriptPriority, array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
			), false );
		}
		
		/**
		 * ga scripts are loaded in wp_head priority is defined in option variable
		 */
		if ( has_action( 'wp_head', 'add_ga_header_script' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag( 'wp_head', $scriptPriority, array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
			), false );
		}

	}
	
	/**
	 * Get priority of script
	 *
	 * @return integer
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_host_analyticsjs_local_priority() {
		return ( esc_attr( get_option( 'sgal_enqueue_order' ) ) ) ? esc_attr( get_option( 'sgal_enqueue_order' ) ) : 0;
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Complete Analytics Optimization Suite (CAOS)';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'host_analyticsjs_local';
	}

	/**
	 * plugin file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return 'host-analyticsjs-local/save-ga-local.php';
	}

	/**
	 * Returns checked cookie types
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}
}
