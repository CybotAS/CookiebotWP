<?php

namespace cybot\cookiebot\lib\traits;

use cybot\cookiebot\exceptions\InvalidClassConstantException;
use Exception;
use InvalidArgumentException;

trait Class_Constant_Override_Validator_Trait {

	/**
	 * @param array $fixed_class_constant_names
	 *
	 * @throws Exception
	 */
	protected function validate_fixed_class_constants( array $fixed_class_constant_names ) {
		foreach ( $fixed_class_constant_names as $fixed_class_constant_name ) {
			$this->validate_fixed_class_constant( $fixed_class_constant_name );
		}
	}

	/**
	 * @param $fixed_class_constant_name
	 *
	 * @throws Exception
	 */
	protected function validate_fixed_class_constant( $fixed_class_constant_name ) {
		$value_self   = constant( 'self::' . $fixed_class_constant_name );
		$value_static = constant( 'static::' . $fixed_class_constant_name );

		if ( $value_self !== $value_static ) {
			throw new InvalidClassConstantException(
				sprintf(
					'Class constant "%s" should be changed by %s',
					$fixed_class_constant_name,
					static::class
				)
			);
		}
	}

	/**
	 * @param array $required_string_constant_names
	 *
	 * @throws Exception
	 */
	protected function validate_required_string_class_constants( array $required_string_constant_names ) {
		foreach ( $required_string_constant_names as $required_string_constant_name ) {
			$this->validate_required_string_class_constant( $required_string_constant_name );
		}
	}

	/**
	 * @param string $required_string_constant_name
	 *
	 * @throws Exception
	 */
	protected function validate_required_string_class_constant( $required_string_constant_name ) {
		if ( ! is_string( $required_string_constant_name ) ) {
			throw new InvalidArgumentException();
		}
		$value = constant( 'static::' . $required_string_constant_name );
		if ( empty( $value ) || ! is_string( $value ) ) {
			throw new InvalidClassConstantException(
				sprintf(
					'Class constant "%s" must be a non-empty string in %s',
					$required_string_constant_name,
					static::class
				)
			);
		}
	}

	/**
	 * @param array $required_boolean_constant_names
	 *
	 * @throws Exception
	 */
	protected function validate_required_boolean_class_constants( array $required_boolean_constant_names ) {
		foreach ( $required_boolean_constant_names as $required_boolean_constant_name ) {
			$this->validate_required_boolean_class_constant( $required_boolean_constant_name );
		}
	}

	/**
	 * @param string $required_boolean_constant_name
	 *
	 * @throws Exception
	 */
	protected function validate_required_boolean_class_constant( $required_boolean_constant_name ) {
		if ( ! is_string( $required_boolean_constant_name ) ) {
			throw new InvalidArgumentException();
		}
		$value = constant( 'static::' . $required_boolean_constant_name );
		if ( ! is_bool( $value ) ) {
			throw new InvalidClassConstantException(
				sprintf(
					'Class constant "%s" must be a boolean in %s',
					$required_boolean_constant_name,
					static::class
				)
			);
		}
	}

	/**
	 * @param array $required_array_constant_names
	 *
	 * @throws Exception
	 */
	protected function validate_required_array_class_constants( array $required_array_constant_names ) {
		foreach ( $required_array_constant_names as $required_array_constant_name ) {
			$this->validate_required_array_class_constant( $required_array_constant_name );
		}
	}

	/**
	 * @param $required_array_constant_name
	 * @param array|null                   $allowed_item_values
	 *
	 * @throws Exception
	 */
	protected function validate_required_array_class_constant( $required_array_constant_name, array $allowed_item_values = null ) {
		if ( ! is_string( $required_array_constant_name ) ) {
			throw new InvalidArgumentException();
		}
		$value = constant( 'static::' . $required_array_constant_name );
		if ( empty( $value ) || ! is_array( $value ) ) {
			throw new InvalidClassConstantException(
				sprintf(
					'Class constant "%s" must be an array in %s',
					$required_array_constant_name,
					static::class
				)
			);
		}
		if ( ! empty( $allowed_item_values ) ) {
			foreach ( $value as $item ) {
				if ( ! in_array( $item, $allowed_item_values, true ) ) {
					throw new InvalidClassConstantException(
						sprintf(
							'Class constant "%s" array items should be one of "%s" in %s',
							$required_array_constant_name,
							implode( ', ', $allowed_item_values ),
							static::class
						)
					);
				}
			}
		}
	}
}
