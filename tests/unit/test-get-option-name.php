<?php

namespace cookiebot_addons_framework\tests\unit;

use cookiebot_addons_framework\lib\Settings_Service;
use DI;
use DI\Container;
use DI\ContainerBuilder;

class Test_Get_Option_Name extends \WP_UnitTestCase {

	protected $plugins;

	private $container;

	public function setUp() {
		Parent::setUp();

		$this->get_plugins();

		$this->build_container();
	}

	/**
	 * Load the addons through json file.
	 */
	private function get_plugins() {
		$file          = file_get_contents( CAF_DIR . 'addons.json' );
		$this->plugins = json_decode( $file );
	}

	/**
	 * Build IoC container
	 *
	 * @since 1.3.0
	 */
	protected function build_container() {
		$builder = new ContainerBuilder();

		$builder->addDefinitions( [
			'Script_Loader_Tag_Interface' => DI\object( 'cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag' ),
			'Cookie_Consent_Interface'    => DI\object( 'cookiebot_addons_framework\lib\Cookie_Consent' ),
			'Buffer_Output_Interface'     => DI\object( 'cookiebot_addons_framework\lib\buffer\Buffer_Output' ),
			'plugins'                     => DI\value( $this->plugins )
		] );

		$this->container = $builder->build();

		$this->container->set( 'Settings_Service_Interface', DI\object( 'cookiebot_addons_framework\lib\Settings_Service' )
			->constructor( $this->container ) );

		foreach ( $this->plugins as $plugin ) {
			/**
			 * Load addon class to the container
			 */
			$this->container->set( $plugin->class, \DI\object( $plugin->class )
				->constructor(
					$this->container->get( 'Settings_Service_Interface' ),
					$this->container->get( 'Script_Loader_Tag_Interface' ),
					$this->container->get( 'Cookie_Consent_Interface' ),
					$this->container->get( 'Buffer_Output_Interface' ) )
			);
		}
	}

	/**
	 * Validate if the plugins in addons.json do exist as a class in addons controller directory.
	 */
	public function test_plugins_are_valid() {
		foreach ( $this->plugins as $plugin ) {
			$check = is_object( $plugin ) && class_exists( $plugin->class );
			$this->assertTrue( $check );
		}
	}

	/**
	 * get_option_name is unique in every addon.
	 */
	public function test_get_option_name_unique() {
		$options = array();

		foreach ( $this->plugins as $plugin ) {
			$p = $this->container->get( $plugin->class );

			$this->assertNotContains( $p->get_option_name(), $options );

			$options[] = $p;
		}
	}

}