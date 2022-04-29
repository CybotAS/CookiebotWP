<?php

namespace cybot\cookiebot\tests;

/**
 * PHPUnit bootstrap file.
 *
 * @package Cookiebot
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/./helpers.php';
require_once __DIR__ . '/../src/addons/addons.php';
require_once __DIR__ . '/../src/lib/helper.php';

if ( PHP_MAJOR_VERSION >= 8 ) {
	echo 'The scaffolded tests cannot currently be run on PHP 8.0+. See https://github.com/wp-cli/scaffold-command/issues/285' . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

$cybot_cookiebot_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $cybot_cookiebot_tests_dir ) {
	$cybot_cookiebot_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "{$cybot_cookiebot_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$cybot_cookiebot_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$cybot_cookiebot_tests_dir}/includes/functions.php";

tests_add_filter(
	'muplugins_loaded',
	function () {
		require dirname( dirname( __FILE__ ) ) . '/cookiebot.php';
	}
);

// Start up the WP testing environment.
require "{$cybot_cookiebot_tests_dir}/includes/bootstrap.php";
