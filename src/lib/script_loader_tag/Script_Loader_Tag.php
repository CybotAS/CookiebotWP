<?php

namespace cybot\cookiebot\lib\script_loader_tag;

use DOMDocument;
use DOMElement;

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
		add_action( 'init', array( $this, 'initialize_ignore_scripts' ) );

		if ( version_compare( get_bloginfo( 'version' ), '5.7.0', '>=' ) ) {
			add_filter( 'wp_script_attributes', array( $this, 'cookiebot_add_consent_attribute_to_script_tag' ), 10, 1 );
			add_filter( 'wp_inline_script_attributes', array( $this, 'cookiebot_add_consent_attribute_to_inline_script_tag' ), 10, 2 );
		} else {
			add_filter( 'script_loader_tag', array( $this, 'cookiebot_add_consent_attribute_to_tag' ), 10, 3 );
		}
	}

	/**
	 * Initialize the list of scripts to ignore cookiebot scan.
	 */
	public function initialize_ignore_scripts() {
		$this->ignore_scripts = apply_filters( 'cybot_cookiebot_ignore_scripts', $this->ignore_scripts );
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
		// Check if the handle is in our list of tags to modify
		if ( array_key_exists( $handle, $this->tags ) && ! empty( $this->tags[ $handle ] ) ) {
			// If we have a match, we completely replace the tag with our own constructed one
			// This is safer than parsing for this specific case as we know exactly what we want
			//phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			return '<script src="' . esc_url( $src ) . '" type="text/plain" data-cookieconsent="' . esc_attr( implode( ',', $this->tags[ $handle ] ) ) . '"></script>';
		}

		// Check if the script should be ignored
		if ( $this->check_ignore_script( $src ) ) {
			// Use DOMDocument to safely parse and modify the script tag
			$dom = new DOMDocument();
			
			// Suppress errors for partial HTML
			$libxml_previous_state = libxml_use_internal_errors( true );
			
			// Load HTML with UTF-8 encoding hack
			// The mb_convert_encoding is to ensure we don't have encoding issues
			// Replacement for deprecated mb_convert_encoding(..., 'HTML-ENTITIES', 'UTF-8')
			$encoded_tag = mb_encode_numericentity( $tag, array( 0x80, 0x10FFFF, 0, 0x1FFFFF ), 'UTF-8' );
			$dom->loadHTML( $encoded_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
			
			libxml_use_internal_errors( $libxml_previous_state );

			$scripts = $dom->getElementsByTagName( 'script' );

			if ( $scripts->length > 0 ) {
				/** @var DOMElement $script */
				$script = $scripts->item( 0 );

				// Validate attributes to ensure we are targeting the right script
				// This mimics the logic in validate_attributes_for_consent_ignore
				$id = $script->getAttribute( 'id' );
				
				// If validation fails, return original tag
				if ( ! $this->validate_attributes_for_consent_ignore_dom( $handle, $id, $script ) ) {
					return $tag;
				}

				// Add the ignore attribute
				$script->setAttribute( 'data-cookieconsent', 'ignore' );

				// Save HTML
				return $dom->saveHTML( $script );
			}
		}

		return $tag;
	}

	/**
	 * Modifies script tags to add the consent ignore attribute.
	 *
	 * @param array $attributes List of the attributes for the tag.
	 *
	 * @return array List of the attributes for the tag.
	 */
	public function cookiebot_add_consent_attribute_to_script_tag( $attributes ) {
		if ( isset( $attributes['src'] ) && $this->check_ignore_script( $attributes['src'] ) ) {
			$attributes['data-cookieconsent'] = 'ignore';
		}

		return $attributes;
	}

	/**
	 * Modifies inline script tags to add the consent ignore attribute.
	 *
	 * @param array $attributes List of the attributes for the tag.
	 *
	 * @return array List of the attributes for the tag.
	 */
	public function cookiebot_add_consent_attribute_to_inline_script_tag( $attributes ) {
		if ( isset( $attributes['id'] ) && $this->is_inline_of_ignored_script( $attributes['id'] ) ) {
			$attributes['data-cookieconsent'] = 'ignore';
		}

		return $attributes;
	}

	/**
	 * Check if the script is part of an ignored script.
	 *
	 * @param string $src URL of the script.
	 *
	 * @return bool True if the script is part of an ignored script.
	 */
	private function check_ignore_script( $src ) {
		foreach ( $this->ignore_scripts as $ignore_script ) {
			if ( strpos( $src, $ignore_script ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the inline script is part of an ignored script.
	 *
	 * @param string $inline_script_id ID of the inline script.
	 *
	 * @return bool True if the inline script is part of an ignored script.
	 */
	private function is_inline_of_ignored_script( $inline_script_id ) {
		$base_id = $this->extract_base_id_from_inline_id( $inline_script_id );

		$scripts = wp_scripts();

		if ( isset( $scripts->registered[ $base_id ] ) && ! empty( $scripts->registered[ $base_id ]->src ) ) {
			$src = $scripts->registered[ $base_id ]->src;
			return $this->check_ignore_script( $src );
		}

		return false;
	}

	/**
	 * Extract the base ID from the inline script ID.
	 *
	 * @param string $inline_script_id ID of the inline script.
	 *
	 * @return string Base ID of the inline script.
	 */
	private function extract_base_id_from_inline_id( $inline_script_id ) {
		// Strip suffix to get the base ID.
		return preg_replace( '/-js-(extra|after|before)$/', '', $inline_script_id );
	}

	/**
	 * Check if the script tag attributes are valid for the injection of the consent ignore attribute.
	 *
	 * @param string $script_handle Handle of the enqueued script. Required for identification of the scripts.
	 * @param string $id ID attribute of the script tag.
	 * @param DOMElement $script The script element.
	 *
	 * @return bool True if the attributes are valid for the injection of the consent ignore attribute.
	 */
	private function validate_attributes_for_consent_ignore_dom( $script_handle, $id, $script ) {
		// Exclude any scripts not related to currently processed script handle. Only script itself and inline block
		// before/after are supported.
		
		// Construct expected ID pattern logic
		// Original regex: "/id=['\"]$quoted_handle(?:-js(-(after|before))?)?['\"]/"
		
		// Check if ID starts with handle
		if ( strpos( $id, $script_handle ) !== 0 ) {
			return false;
		}
		
		// Check if it's an exact match or has valid suffixes
		$valid_suffixes = [
			'-js',
			'-js-after',
			'-js-before'
		];
		
		$is_valid_id = $id === $script_handle;
		if ( ! $is_valid_id ) {
			foreach ( $valid_suffixes as $suffix ) {
				if ( $id === $script_handle . $suffix ) {
					$is_valid_id = true;
					break;
				}
			}
		}
		
		if ( ! $is_valid_id ) {
			return false;
		}

		// Exclude any scripts already containing the consent ignore attribute.
		if ( $script->hasAttribute( 'data-cookieconsent' ) ) {
			return false;
		}

		return true;
	}
}
