<?php

namespace cookiebot_addons_framework\controller\addons\embed_autocorrect;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;

class Embed_Autocorrect implements Cookiebot_Addons_Interface {

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
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.2.0
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
		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_embed_autocorrect' ) );
	}

	/**
	 * Check for embed autocorrect action hooks
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_embed_autocorrect() {
		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		//add filters to handle autocorrection in content
		add_filter( 'the_content', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content'
		), 1000 ); //Ensure it is executed as the last filter

		//add filters to handle autocorrection in widget text
		add_filter( 'widget_text', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content'
		), 1000 ); //Ensure it is executed as the last filter
	}

	/**
	 * Autocorrection of Vimeo and Youtube tags to make them GDPR compatible
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect_content( $content ) {
		//Make sure Cookiebot is active and the user has enabled autocorrection
		$cookieContentNotice = '<div class="cookieconsent-optout-' . cookiebot_get_one_cookie_type( $this->get_cookie_types() ) . '">';
		$cookieContentNotice .= sprintf( __( 'Please <a href="javascript:Cookiebot.renew()">accept %s cookies</a> to watch this video.', 'cookiebot-addons' ), cookiebot_output_cookie_types( $this->get_cookie_types() ) );
		$cookieContentNotice .= '</div>';

		//Match twitter.
		preg_match_all( '#\<(script).+src=".+platform.twitter.com\/widgets\.js.+\<\/(script)\>#mis', $content, $matches );
		if ( ! empty( $matches[0][0] ) ) {
			$adjusted_content = str_replace( '<script', '<script type="text/plain" data-cookieconsent="' . cookiebot_output_cookie_types( $this->get_cookie_types() ) . '"', $matches[0][0] );
			$adjusted_content = $cookieContentNotice . $adjusted_content;
			$content          = str_replace( $matches[0][0], $adjusted_content, $content );
		}
		unset( $matches );

		//Match all youtube, vimeo and facebook iframes.
		preg_match_all( '/<iframe[^>]*src=\"[^\"]*(facebook\.com|youtu\.be|youtube\.com|youtube-nocookie\.com|player\.vimeo\.com)\/[^>]*>.*?<\\/iframe>/mi', $content, $matches );
		foreach ( $matches[0] as $match ) {
			//Replace - and add cookie consent notice.
			$adjusted = str_replace( ' src=', ' data-cookieconsent="' . cookiebot_output_cookie_types( $this->get_cookie_types() ) . '" data-src=', $match );
			$content  = str_replace( $match, $adjusted . $cookieContentNotice, $content );
		}

		return $content;
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Embed autocorrect';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'embed_autocorrect';
	}

	/**
	 * Plugin file path
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return false;
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
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
