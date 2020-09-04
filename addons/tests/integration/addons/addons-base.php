<?php

namespace cookiebot_addons\tests\integration\addons;

abstract class Addons_Base extends \WP_UnitTestCase {

	/**
	 * Returns the file content from the WordPress repository.
	 *
	 * @param $url
	 *
	 * @return bool|string
	 */
	public function curl_get_content( $url ) {
		$curl = curl_init();

		curl_setopt_array( $curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				"Host: plugins.svn.wordpress.org",
				"User-Agent: PostmanRuntime/7.26.1"
			),
		) );

		$response = curl_exec( $curl );

		curl_close( $curl );

		return $response;
	}
}
