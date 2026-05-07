<?php
/**
 * Cookiebot_Abilities_Registrar class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

/**
 * Registers Cookiebot abilities with the WP Abilities API.
 *
 * @since 4.8.0
 */
class Cookiebot_Abilities_Registrar {

	/**
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	/**
	 * @param Ability_Audit_Logger|null $logger
	 *
	 * @since 4.8.0
	 */
	public function __construct( $logger = null ) {
		$this->logger = $logger ? $logger : new Ability_Audit_Logger();
	}

	/**
	 * Registers the WP action hooks for categories and abilities.
	 *
	 * @return void
	 *
	 * @since 4.8.0
	 */
	public function register_hooks() {
		add_action( 'wp_abilities_api_categories_init', array( $this, 'register_category' ) );
		add_action( 'wp_abilities_api_init', array( $this, 'register' ) );
	}

	/**
	 * Registers the 'cookiebot' ability category.
	 *
	 * @return void
	 *
	 * @since 4.8.0
	 */
	public function register_category() {
		wp_register_ability_category(
			'cookiebot',
			array(
				'label'       => __( 'Cookiebot CMP', 'cookiebot' ),
				'description' => __( 'Abilities for reading and configuring the Cookiebot CMP plugin.', 'cookiebot' ),
			)
		);
	}

	/**
	 * Registers all six Cookiebot abilities.
	 *
	 * @return void
	 *
	 * @since 4.8.0
	 */
	public function register() {
		$logger = $this->logger;
		$abilities = array(
			new Get_Status_Ability(),
			new Verify_Setup_Ability(),
			new Get_Compliance_Summary_Ability(),
			new Set_Cbid_Ability( $logger ),
			new Toggle_Gcm_Ability( $logger ),
			new Install_Ppg_Ability( $logger ),
		);
		foreach ( $abilities as $ability ) {
			wp_register_ability( $ability->get_name(), $ability->get_args() );
		}
	}
}
