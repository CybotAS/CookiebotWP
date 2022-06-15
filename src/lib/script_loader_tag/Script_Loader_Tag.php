<?php

namespace cybot\cookiebot\lib\script_loader_tag;

class Script_Loader_Tag implements Script_Loader_Tag_Interface {

	/**
	 * List of tags to load in cookiebot attributes
	 *
	 * @var array
	 *
	 * @since 1.1.0
	 */
	private $tags = array();

	/**
	 * List of scripts to ignore cookiebot scan
	 * @var array
	 */
	private $ignore_scripts = array();

	/**
	 * Cookiebot_Script_Loader_Tag constructor.
	 * Adds filter to enhance script attribute
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'cookiebot_add_consent_attribute_to_tag' ), 10, 3 );
	}

	/**
	 * Adds enqueue script handle tag to the array of tags.
	 * So that the script can be updated with cookieconsent attribute.
	 *
	 * @param $tag  string  Enqueue script handle name
	 * @param $type array
	 *
	 * @since 1.2.0
	 */
	public function add_tag( $tag, $type ) {
		$this->tags[ $tag ] = $type;
	}

	public function ignore_script( $script ) {
		array_push( $this->ignore_scripts, $script );
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
	 * @since 1.2.0
	 */
	public function cookiebot_add_consent_attribute_to_tag( $tag, $handle, $src ) {
		if ( array_key_exists( $handle, $this->tags ) && ! empty( $this->tags[ $handle ] ) ) {
			//phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			return '<script src="' . $src . '" type="text/plain" data-cookieconsent="' . implode( ',', $this->tags[ $handle ] ) . '"></script>';
		}

		apply_filters( 'cybot_cookiebot_ignore_scripts', $this->ignore_scripts );

		if ( $this->check_ignore_script( $src ) ) {
            //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			return str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag );
		}

		return $tag;
	}

	private function check_ignore_script( $src ) {
		foreach ( $this->ignore_scripts as $ignore_script ) {
			if ( strpos( $src, $ignore_script ) !== false ) {
				return true;
			}
		}

		return false;
	}
}
