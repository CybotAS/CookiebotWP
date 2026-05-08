<?php

namespace cybot\cookiebot\cli;

/**
 * Manage Cookiebot CMP from the command line.
 *
 * Wraps the WordPress Abilities API surface (Phase 2) so that all configuration
 * available to AI agents over MCP / REST is also available from the shell.
 *
 * ## EXAMPLES
 *
 *     # Read current status
 *     wp cookiebot status
 *
 *     # Set the Domain Group ID
 *     wp cookiebot set-cbid 12345678-1234-1234-1234-123456789abc
 *
 *     # Verify setup is complete
 *     wp cookiebot verify --format=json
 *
 * @since 4.8.0
 */
class Cookiebot_CLI_Command {

	/**
	 * @var Output_Adapter
	 */
	private $output;

	/**
	 * @param Output_Adapter|null $output Adapter for output. Defaults to WP_CLI.
	 */
	public function __construct( $output = null ) {
		$this->output = $output ? $output : new WP_CLI_Output_Adapter();
	}

	/**
	 * Validate and return the requested output format, or emit an error and return null.
	 *
	 * @param array  $assoc_args WP-CLI named arguments.
	 * @param string $default    Default format when not specified.
	 * @return string|null  One of table|json|yaml|csv, or null on invalid input.
	 */
	private function get_format( $assoc_args, $default = 'table' ) {
		$allowed = array( 'table', 'json', 'yaml', 'csv' );
		$format  = isset( $assoc_args['format'] ) ? $assoc_args['format'] : $default;
		if ( ! in_array( $format, $allowed, true ) ) {
			$this->output->error( sprintf( 'Invalid --format "%s". Allowed: table, json, yaml, csv.', $format ) );
			return null;
		}
		return $format;
	}

	/**
	 * Resolve a registered ability or fail fast.
	 *
	 * @param string $name e.g. 'cookiebot/get-status'
	 * @return object|null  WP_Ability instance, or null on failure.
	 */
	private function get_ability( $name ) {
		if ( ! function_exists( 'wp_get_ability' ) ) {
			$this->output->error( 'Cookiebot abilities are not registered. Is the Abilities API plugin active?' );
			return null;
		}
		$ability = wp_get_ability( $name );
		if ( ! $ability ) {
			$this->output->error( sprintf( 'Ability "%s" is not registered.', $name ) );
			return null;
		}
		return $ability;
	}

	/**
	 * Show current Cookiebot CMP configuration.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 *   - csv
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot status
	 *     wp cookiebot status --format=json
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function status( $args, $assoc_args ) {
		$ability = $this->get_ability( 'cookiebot/get-status' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( null );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		$format = $this->get_format( $assoc_args );
		if ( null === $format ) {
			return;
		}
		$this->output->format_items(
			array( $result ),
			array_keys( $result ),
			$format
		);
	}

	/**
	 * Verify that Cookiebot CMP is correctly configured.
	 *
	 * Returns issues[] — empty means setup is complete.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 *   - csv
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot verify
	 *     wp cookiebot verify --format=json | jq '.issues'
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function verify( $args, $assoc_args ) {
		$ability = $this->get_ability( 'cookiebot/verify-setup' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( null );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		$format = $this->get_format( $assoc_args );
		if ( null === $format ) {
			return;
		}
		if ( 'table' === $format && isset( $result['issues'] ) ) {
			$result['issues'] = implode( '; ', $result['issues'] );
		}
		$this->output->format_items(
			array( $result ),
			array_keys( $result ),
			$format
		);
	}

	/**
	 * Show the privacy regulations covered by Cookiebot CMP on this site.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 *   - csv
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot compliance
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function compliance( $args, $assoc_args ) {
		$ability = $this->get_ability( 'cookiebot/get-compliance-summary' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( null );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		$format = $this->get_format( $assoc_args );
		if ( null === $format ) {
			return;
		}
		if ( 'table' === $format && isset( $result['regulations_covered'] ) ) {
			$result['regulations_covered'] = implode( ', ', $result['regulations_covered'] );
		}
		$this->output->format_items(
			array( $result ),
			array_keys( $result ),
			$format
		);
	}

	/**
	 * Set the Domain Group ID (CBID).
	 *
	 * Accepts a Cookiebot UUID (36 chars) or a Usercentrics Settings ID (9 or 14 alphanumeric chars).
	 *
	 * ## OPTIONS
	 *
	 * <cbid>
	 * : The Domain Group ID (UUID for Cookiebot, Settings ID for Usercentrics).
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot set-cbid 12345678-1234-1234-1234-123456789abc
	 *     wp cookiebot set-cbid AbCdEfGhI
	 *     wp cookiebot set-cbid AbCdEfGhIjKlMn
	 *
	 * @subcommand set-cbid
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function set_cbid( $args, $assoc_args ) {
		if ( empty( $args[0] ) ) {
			$this->output->error( 'CBID is required. Usage: wp cookiebot set-cbid <cbid>' );
			return;
		}
		$ability = $this->get_ability( 'cookiebot/set-cbid' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( array( 'cbid' => $args[0] ) );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		$this->output->success(
			sprintf(
				'CBID updated: %s -> set',
				$result['old_cbid_set'] ? 'previous value' : '(unset)'
			)
		);
	}

	/**
	 * Enable or disable Google Consent Mode v2.
	 *
	 * GCM v2 is enabled by default and required for Google Ads/Analytics in EU/EEA.
	 * Only disable if explicitly requested.
	 *
	 * ## OPTIONS
	 *
	 * --enabled=<bool>
	 * : true to enable, false to disable.
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot toggle-gcm --enabled=false
	 *     wp cookiebot toggle-gcm --enabled=true
	 *
	 * @subcommand toggle-gcm
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function toggle_gcm( $args, $assoc_args ) {
		if ( ! isset( $assoc_args['enabled'] ) || '' === $assoc_args['enabled'] ) {
			$this->output->error( '--enabled flag is required (true|false).' );
			return;
		}
		$enabled = filter_var( $assoc_args['enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		if ( null === $enabled ) {
			$this->output->error( '--enabled must be exactly "true" or "false".' );
			return;
		}
		$ability = $this->get_ability( 'cookiebot/toggle-gcm' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( array( 'enabled' => $enabled ) );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		$this->output->success(
			sprintf(
				'Google Consent Mode v2: %s -> %s',
				$result['old_value'] ? 'enabled' : 'disabled',
				$result['new_value'] ? 'enabled' : 'disabled'
			)
		);
	}

	/**
	 * Install and activate the Privacy Policy Generator plugin (idempotent).
	 *
	 * Returns immediately if the plugin is already active.
	 *
	 * ## EXAMPLES
	 *
	 *     wp cookiebot install-ppg
	 *
	 * @subcommand install-ppg
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 */
	public function install_ppg( $args, $assoc_args ) {
		$ability = $this->get_ability( 'cookiebot/install-ppg' );
		if ( ! $ability ) {
			return;
		}
		$result = $ability->execute( null );
		if ( is_wp_error( $result ) ) {
			$this->output->error( $result->get_error_message() );
			return;
		}
		if ( ! empty( $result['was_already_active'] ) ) {
			$this->output->success( sprintf( 'PPG was already active. Configure at: %s', $result['admin_url'] ) );
			return;
		}
		$this->output->success( sprintf( 'PPG installed and activated. Configure at: %s', $result['admin_url'] ) );
	}
}
