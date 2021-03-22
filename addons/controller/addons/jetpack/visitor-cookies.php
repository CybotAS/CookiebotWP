<?php

namespace cookiebot_addons\controller\addons\jetpack;

use cookiebot_addons\controller\addons\jetpack\widget\Jetpack_Widget_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

class Visitor_Cookies implements Jetpack_Widget_Interface {

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	protected $cookie_types;

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
	 * Option name for jetpack addon
	 *
	 * @var string
	 */
	public $widget_option;

	/**
	 * Visitor_Cookies constructor.
	 *
	 * @param Settings_Service_Interface $settings
	 * @param Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookie_Consent_Interface $cookie_consent
	 * @param Buffer_Output_Interface $buffer_output
	 * @param $widget_option
	 *
	 * @version 1.8.0
	 * @since 1.2.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output, $widget_option ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
		$this->widget_option     = $widget_option;
	}

	public function load_configuration() {
		/**
		 * When consent is not given
		 * Then disable comment cookies
		 */
		if ( ! $this->cookie_consent->is_cookie_state_accepted( $this->get_widget_cookie_types() ) ) {
			$this->disable_comment_cookies();
			$this->do_not_save_mobile_or_web_view();
			$this->disable_eu_cookie_law();
			$this->disable_comment_subscriptions();
		}
	}

	public function get_label() {
		return 'Visitor cookies';
	}

	/**
	 * Returns widget option name
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_widget_option_name() {
		return 'visitor_cookies';
	}

	/**
	 * Returns cookie types for a widget
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_cookie_types() {
		return $this->settings->get_widget_cookie_types( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Checks if a widget is enabled
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function is_widget_enabled() {
		return $this->settings->is_widget_enabled( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * @return string
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';
	}

	/**
	 * Checks if widget has existing placeholders
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function widget_has_placeholder() {
		return $this->settings->widget_has_placeholder( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Returns all widget placeholders
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholders() {
		return $this->settings->get_widget_placeholders( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Checks if a widget placeholder is enabled
	 *
	 * @return boolean  true    If widget placeholder is checked
	 *                  false   If widget placeholder is not checked
	 *
	 * @since 1.8.0
	 */
	public function is_widget_placeholder_enabled() {
		return $this->settings->is_widget_placeholder_enabled( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * returns widget placeholder
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholder() {
		return $this->settings->get_widget_placeholder( $this->widget_option, $this->get_widget_option_name(), $this->get_default_placeholder(), $this->get_widget_cookie_types() );
	}

	/**
	 * Set comment subscribe cookie time to zero so it expires.
	 *
	 * @since 1.2.0
	 */
	protected function disable_comment_subscriptions() {
		add_filter( 'comment_cookie_lifetime', function ( $time ) {
			return 0;
		} );
	}

	/**
	 * Disable eu cookie law script
	 *
	 * @since 1.2.0
	 */
	protected function disable_eu_cookie_law() {
		$this->script_loader_tag->add_tag( 'eu-cookie-law-script', array( 'preferences' ) );
	}

	/**
	 * Disable cookie comments
	 *
	 * Cookies:
	 * - comment_author_{HASH}
	 * - comment_author_email_{HASH}
	 * - comment_author_url_{HASH}
	 * @since 1.2.0
	 */
	protected function disable_comment_cookies() {
		/**
		 * Remove action comment cookies in jetpack
		 *
		 * we have to remove this action, because it does manually add the cookie.
		 */
		cookiebot_addons_remove_class_action( 'comment_post', 'Highlander_Comments_Base', 'set_comment_cookies' );

		/**
		 * Remove action comment cookies in wordpress core
		 *
		 * we have to remove this action, because it does manually add the cookie.
		 */
		if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) ) {
			remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
		}
	}

	/**
	 * Doesn't save the visitor wish in cookie
	 *
	 * Cookie:
	 * - akm_mobile
	 *
	 * @since 1.2.0
	 */
	protected function do_not_save_mobile_or_web_view() {
		/**
		 * we have to remove this action, because it does manually add the cookie.
		 */
		if ( has_action( 'init', 'jetpack_mobile_request_handler' ) ) {
			remove_action( 'init', 'jetpack_mobile_request_handler' );
		}

		/**
		 * Show message to accept preferences consent to save
		 */
		if ( $this->is_widget_placeholder_enabled() ) {
			add_action( 'wp_footer', array( $this, 'view_accept_preferences_consent' ) );
		}
	}

	/**
	 * Display message to enable
	 *
	 * @since 1.2.0
	 */
	public function view_accept_preferences_consent() {
		echo '<div class="' . cookiebot_addons_cookieconsent_optout( $this->get_widget_cookie_types() ) . '">
						  ' . $this->get_default_placeholder() . '
						</div>';
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return false;
	}

	/**
	 * Placeholder helper overlay in the settings page.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder_helper() {
		return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in frontend</li></ul>';
	}

}
