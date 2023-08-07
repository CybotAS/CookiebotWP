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
	 *
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
			return preg_replace_callback(
				'/<script\s*(?<atts>[^>]*)>/',
				function ( $tag ) use ( $handle ) {
					// Prevent modification of the script tags inside the other script tag by validating the ID of the
					// script and checking if we already set the consent status for the script. This will fix the issue
					// on Gutenberg editor pages.
					if ( ! self::validate_attributes_for_consent_ignore( $handle, $tag['atts'] ) ) {
						return $tag[0];
					}

                    //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
					return str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag[0] );
				},
				$tag
			);
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

	/**
	 * Check if the script tag attributes are valid for the injection of the consent ignore attribute.
	 *
	 * @param string $script_handle Handle of the enqueued script. Required for identification of the scripts.
	 * @param string $tag_attributes List of the attributes for the tag.
	 *
	 * @return bool True if the attributes are valid for the injection of the consent ignore attribute.
	 */
	private static function validate_attributes_for_consent_ignore( $script_handle, $tag_attributes ) {
		$quoted_handle = preg_quote( $script_handle, '/' );

		// Exclude any scripts not related to currently processed script handle. Only script itself and inline block
		// before/after are supported.
		if ( ! preg_match( "/id=['\"]$quoted_handle(?:-js(-(after|before))?)?['\"]/", $tag_attributes ) ) {
			return false;
		}

		// Exclude any scripts already containing the consent ignore attribute.
		return is_bool( strpos( $tag_attributes, 'data-cookieconsent=' ) );
	}
}
