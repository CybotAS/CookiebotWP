<?php

namespace cookiebot_addons\controller\addons\wp_mautic;

use cookiebot_addons\controller\addons\Base_Cookiebot_Addon;
use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;

class Wp_Mautic extends Base_Cookiebot_Addon {

	const ADDON_NAME                  = 'Mautic';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'mautic';
	const PLUGIN_FILE_PATH            = 'wp-mautic/wpmautic.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.5.0
	 */
	public function load_addon_configuration() {
        	$this->buffer_output->add_tag( 'wp_head', 10, array(
        	    'MauticTrackingObject'     => $this->get_cookie_types()
        	), false );
        	$this->buffer_output->add_tag( 'wp_footer', 10, array(
        	    'MauticTrackingObject'     => $this->get_cookie_types()
       		), false );
		
		//Remove noscript tracking
		if( has_action( 'wp_footer', 'wpmautic_inject_noscript' ) ) {
			remove_action( 'wp_footer', 'wpmautic_inject_noscript' );
		}
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
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'https://plugins.svn.wordpress.org/wp-mautic/trunk/wpmautic.php';
	}
}
