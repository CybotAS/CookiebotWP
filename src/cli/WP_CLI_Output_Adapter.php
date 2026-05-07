<?php

namespace cybot\cookiebot\cli;

/**
 * Default Output_Adapter implementation. Forwards all calls to WP_CLI.
 *
 * @since 4.8.0
 */
class WP_CLI_Output_Adapter implements Output_Adapter {

	public function success( $message ) {
		\WP_CLI::success( $message );
	}

	public function error( $message ) {
		\WP_CLI::error( $message );
	}

	public function format_items( $items, $fields, $format ) {
		\WP_CLI\Utils\format_items( $format, $items, $fields );
	}
}
