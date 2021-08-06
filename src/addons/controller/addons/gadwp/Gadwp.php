<?php

namespace cybot\cookiebot\addons\controller\addons\gadwp;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use function cybot\cookiebot\addons\lib\cookiebot_addons_output_cookie_types;


/**
 * Class Gadwp
 * @package cybot\cookiebot\addons\controller\addons\gadwp
 */
class Gadwp extends Base_Cookiebot_Addon {
	const ADDON_NAME                  = 'Google Analytics Dashboard for WP by ExactMetrics';
	const OPTION_NAME                 = 'gadwp';
	const PLUGIN_FILE_PATH            = 'google-analytics-dashboard-for-wp/gadwp.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';

	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'gadwp-nprogress', $this->get_cookie_types() );
		$this->script_loader_tag->add_tag( 'gadwp-frontend-item-reports', $this->get_cookie_types() );

		$this->script_loader_tag->add_tag( 'gadwp-tracking-analytics-events', $this->get_cookie_types() );
		$this->script_loader_tag->add_tag( 'gadwp-pagescrolldepth-tracking', $this->get_cookie_types() );

		$this->script_loader_tag->add_tag( 'gadwp-front-widget', $this->get_cookie_types() );
		$this->script_loader_tag->add_tag( 'googlecharts', $this->get_cookie_types() );

		$this->buffer_output->add_tag(
			'wp_head',
			99,
			array(
				'google-analytics.com' => $this->get_cookie_types(),
				'googletagmanager.com' => $this->get_cookie_types(),
				'ga-disable-'          => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wp_footer',
			99,
			array(
				'google-analytics.com' => $this->get_cookie_types(),
				'googletagmanager.com' => $this->get_cookie_types(),
				'ga-disable-'          => $this->get_cookie_types(),
			),
			false
		);

		/* For new versions of GADWP */
		$this->script_loader_tag->add_tag( 'exactmetrics-frontend-script', $this->get_cookie_types() );
		$this->buffer_output->add_tag(
			'wp_head',
			6,
			array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
				'googletagmanager'      => $this->get_cookie_types(),
			),
			false
		);

		add_filter(
			'exactmetrics_tracking_analytics_script_attributes',
			function( $atts ) {
				$atts['type']               = 'text/plain';
				$atts['data-cookieconsent'] = cookiebot_addons_output_cookie_types( $this->get_cookie_types() );

				return $atts;
			}
		);

	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/gadwp.php';
	}
}
