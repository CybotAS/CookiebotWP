<?php

namespace cybot\cookiebot\tests {
	/**
	 * Returns the file content from the WordPress repository.
	 *
	 * @param $url
	 *
	 * @return string
	 */
	function remote_get_svn_contents( $url ) {
		$response = wp_remote_get( $url );
		return wp_remote_retrieve_body( $response );
	}
}
