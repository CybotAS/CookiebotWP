<?php
/**
 * Tests for Get_Compliance_Summary_Ability.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Get_Compliance_Summary_Ability;
use WP_UnitTestCase;

/**
 * Test class for Get_Compliance_Summary_Ability.
 *
 * @since 4.8.0
 */
class Test_Get_Compliance_Summary_Ability extends WP_UnitTestCase {

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		update_option( 'cookiebot-scan-status', 'completed' );
		update_option( 'cookiebot-user-data', '' );
		update_option( 'cookiebot-cbid', '' );
	}

	/**
	 * Test get_name returns correct ability name.
	 *
	 * @return void
	 */
	public function test_get_name() {
		$this->assertSame( 'cookiebot/get-compliance-summary', ( new Get_Compliance_Summary_Ability() )->get_name() );
	}

	/**
	 * Test output has all required keys.
	 *
	 * @return void
	 */
	public function test_output_has_all_required_keys() {
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		foreach ( array( 'regulations_covered', 'scan_status', 'consent_log_available', 'plan_type' ) as $key ) {
			$this->assertArrayHasKey( $key, $result );
		}
	}

	/**
	 * Test regulations_covered includes key standards.
	 *
	 * @return void
	 */
	public function test_regulations_covered_includes_key_standards() {
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		foreach ( array( 'GDPR', 'CCPA/CPRA', 'LGPD', 'IAB TCF 2.2', 'Google Consent Mode v2' ) as $reg ) {
			$this->assertContains( $reg, $result['regulations_covered'] );
		}
	}

	/**
	 * Test scan_status reflects option.
	 *
	 * @return void
	 */
	public function test_scan_status_reflects_option() {
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertSame( 'completed', $result['scan_status'] );
	}

	/**
	 * Test consent_log_available is true when CBID is set.
	 *
	 * @return void
	 */
	public function test_consent_log_available_true_when_cbid_set() {
		update_option( 'cookiebot-cbid', 'aabbccdd-1111-2222-3333-aabbccddeeff' );
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertTrue( $result['consent_log_available'] );
	}

	/**
	 * Test consent_log_available is false when CBID is empty.
	 *
	 * @return void
	 */
	public function test_consent_log_available_false_when_cbid_empty() {
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['consent_log_available'] );
	}

	/**
	 * Test plan_type is unknown with no user data.
	 *
	 * @return void
	 */
	public function test_plan_type_unknown_with_no_user_data() {
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertSame( 'unknown', $result['plan_type'] );
	}

	/**
	 * Test plan_type detects free plan.
	 *
	 * @return void
	 */
	public function test_plan_type_free_detected() {
		update_option( 'cookiebot-user-data', json_encode( array( 'subscription' => array( 'plan' => 'Free Plan' ) ) ) );
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertSame( 'free', $result['plan_type'] );
	}

	/**
	 * Test plan_type detects premium plan.
	 *
	 * @return void
	 */
	public function test_plan_type_premium_detected() {
		update_option( 'cookiebot-user-data', json_encode( array( 'subscription' => array( 'plan' => 'Premium' ) ) ) );
		$args   = ( new Get_Compliance_Summary_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertSame( 'premium', $result['plan_type'] );
	}
}
