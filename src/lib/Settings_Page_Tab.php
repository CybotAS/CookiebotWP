<?php

namespace cybot\cookiebot\lib;

use InvalidArgumentException;

class Settings_Page_Tab {

	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $label;
	/**
	 * @var string
	 */
	private $settings_fields_option_group;
	/**
	 * @var string
	 */
	private $page_name;
	/**
	 * @var bool
	 */
	private $is_active;
	/**
	 * @var bool
	 */
	private $has_submit_button;

	/**
	 * @param $name
	 * @param $label
	 * @param $settings_fields_option_group
	 * @param $page_name
	 * @param bool                         $has_submit_button
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(
		$name,
		$label,
		$settings_fields_option_group,
		$page_name,
		$has_submit_button = true
	) {
		if ( ! is_string( $name ) || empty( $name ) ) {
			throw new InvalidArgumentException( 'The constructor argument "name" is a required string ' );
		}
		if ( ! is_string( $label ) || empty( $label ) ) {
			throw new InvalidArgumentException( 'The constructor argument "label" is a required string ' );
		}
		if ( ! is_string( $settings_fields_option_group ) || empty( $settings_fields_option_group ) ) {
			throw new InvalidArgumentException( 'The constructor argument "settings_fields_option_group" is a required string ' );
		}
		if ( ! is_string( $page_name ) || empty( $page_name ) ) {
			throw new InvalidArgumentException( 'The constructor argument "page_name" is a required string ' );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->is_active                    = isset( $_GET['tab'] ) && $_GET['tab'] === $name;
		$this->name                         = $name;
		$this->label                        = $label;
		$this->settings_fields_option_group = $settings_fields_option_group;
		$this->page_name                    = $page_name;
		$this->has_submit_button            = $has_submit_button;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_settings_fields_option_group() {
		return $this->settings_fields_option_group;
	}

	/**
	 * @return string
	 */
	public function get_page_name() {
		return $this->page_name;
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function set_is_active( $is_active ) {
		$this->is_active = $is_active;
	}

	public function get_tab_href() {
		$query = array(
			'page' => $this->page_name,
			'tab'  => $this->name,
		);

		return add_query_arg( $query, admin_url( 'admin.php' ) );
	}

	/**
	 * @return string
	 */
	public function get_classes() {
		return $this->is_active ? 'nav-tab nav-tab-active' : 'nav-tab';
	}

	/**
	 * @return bool
	 */
	public function has_submit_button() {
		return $this->has_submit_button;
	}

}
