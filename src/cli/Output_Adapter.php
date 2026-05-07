<?php

namespace cybot\cookiebot\cli;

/**
 * Abstraction over WP_CLI output so the CLI command class is unit-testable.
 *
 * @since 4.8.0
 */
interface Output_Adapter {

	/**
	 * Print a success message and return (or exit, depending on adapter).
	 *
	 * @param string $message
	 * @return void
	 */
	public function success( $message );

	/**
	 * Print an error message. Production adapter exits non-zero.
	 *
	 * @param string $message
	 * @return void
	 */
	public function error( $message );

	/**
	 * Print structured data in the requested format ('json', 'yaml', 'table', 'csv').
	 *
	 * @param array  $items  List of associative arrays.
	 * @param array  $fields Column ordering for table format.
	 * @param string $format One of: table, json, yaml, csv.
	 * @return void
	 */
	public function format_items( $items, $fields, $format );
}
