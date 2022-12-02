<?php

namespace cybot\cookiebot\addons\controller\addons\add_to_any;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;

class Add_To_Any extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'addToAny Share Buttons';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.';
	const OPTION_NAME                 = 'add_to_any';
	const PLUGIN_FILE_PATH            = 'add-to-any/add-to-any.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/add-to-any/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'add-to-any.php';

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_head',
			10,
			array(
				'a2a_config' => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wp_footer',
			10,
			array(
				'a2a_config' => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'pre_get_posts',
			10,
			array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
			),
			false
		);

		// External js, so manipulate attributes
		if ( has_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_enqueue_script' ) ) {
			// add-to-any plugin version < 1.8.2
			$this->script_loader_tag->add_tag( 'addtoany', $this->get_cookie_types() );

			// add-to-any-plugin version >= 1.8.2
			$this->script_loader_tag->add_tag( 'addtoany-core', $this->get_cookie_types() );
			$this->script_loader_tag->add_tag( 'addtoany-jquery', $this->get_cookie_types() );
		}

		add_filter(
			'the_content',
			array(
				$this,
				'cookiebot_addon_add_to_any_content',
			),
			1000
		); // Ensure it is executed as the last filter

		add_filter(
			'the_excerpt',
			array(
				$this,
				'cookiebot_addon_add_to_any_content',
			),
			1000
		); // Ensure it is executed as the last filter
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__(
				'Blocks embedded videos from Youtube, Twitter, Vimeo and Facebook.',
				'cookiebot'
			),
		);
	}

	/**
	 * Display a placeholder on elements with "addtoany_share_save_container" class name.
	 *
	 * @param  string $content
	 *
	 * @return string
	 */
	public function cookiebot_addon_add_to_any_content( $content ) {
		if ( $this->has_placeholder() && $this->is_placeholder_enabled() ) {
			$pattern           = '/(<div[^>]*class="[^"]*addtoany_share_save_container[^"]*"[^>]*>)/';
			$placeholder_text  = $this->get_placeholder();
			$placeholder_class = cookiebot_addons_cookieconsent_optout( $this->get_cookie_types() );
			$placeholder       = '<div  class="' . $placeholder_class . '">' . $placeholder_text . '</div>';
			$content           = preg_replace( $pattern, '$1' . $placeholder, $content );
		}

		return $content;
	}
}
