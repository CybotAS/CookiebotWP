<?php

namespace cookiebot_addons_framework\lib;

class Cookiebot_Script_Loader_Tag {

	/**
	 * List of tags to load in cookiebot attributes
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private $tags = array();

	/**
	 * @var   Cookiebot_Script_Loader_Tag The single instance of the class
	 *
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'cookiebot_add_consent_attribute_to_tag' ), 10, 3 );
	}

	public function add_tag( $tag ) {
		$this->tags[] = $tag;
	}

	/**
	 * Modifies external links to google analytics
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function cookiebot_add_consent_attribute_to_tag( $tag, $handle, $src ) {
		if ( in_array( $handle, $this->tags ) ) {
			return '<script src="' . $src . '" type="text/plain" data-cookieconsent="statistics"></script>';
		}

		return $tag;
	}
}