<?php

namespace cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Addon_With_Extra_Information_Interface;

/**
 * Class Simple_Share_Buttons_Adder
 * @package cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder
 */
class Simple_Share_Buttons_Adder extends Base_Cookiebot_Plugin_Addon implements Addon_With_Extra_Information_Interface {

	const ADDON_NAME                  = 'Simple Share Buttons Adder';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to Social Share buttons.';
	const OPTION_NAME                 = 'simple_share_buttons_adder';
	const PLUGIN_FILE_PATH            = 'simple-share-buttons-adder/simple-share-buttons-adder.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'ssba-sharethis', $this->get_cookie_types() );
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string[]
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks Simple Share Buttons Adder.', 'cookiebot-addons' ),
		);
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public static function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/simple-share-buttons-adder/trunk/simple-share-buttons-adder.php';
	}
}
