<?php

namespace cookiebot_addons\lib;

use Exception;

/**
 * Class Dependency_Container
 * @package cookiebot_addons\lib
 */
class Dependency_Container {

	/**
	 * @var array
	 */
	private $dependencies;

	/**
	 * Dependency_Container constructor.
	 *
	 * @param  array  $dependencies
	 */
	public function __construct( array $dependencies = array() ) {
		$this->dependencies = $dependencies;
	}

	/**
	 * @param $key
	 * @param $dependency
	 *
	 * @throws Exception
	 */
	public function set( $key, $dependency ) {
		if ( isset( $this->dependencies[ $key ] ) ) {
			throw new Exception( 'Dependency key ' . $key . ' already exists' );
		}
		$this->dependencies[ $key ] = $dependency;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function get( $key ) {
		if ( ! isset( $this->dependencies[ $key ] ) ) {
			throw new Exception( 'Dependency key ' . $key . ' does not exists' );
		}

		return $this->dependencies[ $key ];
	}
}
