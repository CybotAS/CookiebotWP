<?php

namespace cybot\cookiebot\lib {

	use DomainException;
	use Exception;
	use InvalidArgumentException;
	use RuntimeException;
	use DOMDocument;
	use DOMElement;

	/**
	 * @param string $type
	 * @param string $deprecated_name
	 * @param string $alternative_name
	 */
	function deprecation_error( $type, $deprecated_name, $alternative_name ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
		trigger_error(
			esc_html( $type . ' `' . $deprecated_name . '` is deprecated. Use `' . $alternative_name . '` instead.' ),
			E_USER_DEPRECATED
		);
	}

	/**
	 * Check if a cache plugin is activated and in function.
	 *
	 * @return boolean  True    If attributes always should be added
	 *                  False   If attributes only should be added if consent no given
	 */
	function cookiebot_addons_enabled_cache_plugin() {
		// WP Rocket - We need to ensure we not cache tags without attributes
		if ( defined( 'WP_ROCKET_PATH' ) ||
			// W3 Total Cache
			defined( 'W3TC' ) ||
			// WP Super Cache
			defined( 'WPCACHEHOME' ) ||
			// WP Fastest Cache
			defined( 'WPFC_WP_PLUGIN_DIR' ) ||
			// Litespeed Cache
			defined( 'LSCWP_CONTENT_DIR' ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Removes action with class in callback
	 *
	 * @param $action   string  action name
	 * @param $class    string  class name
	 * @param $method   string  method name
	 * @param $priority integer action priority number
	 *
	 * @return boolean  True    if the action hook is deleted
	 *                  False   If the action hook is not deleted
	 *
	 * @since 1.2.0
	 */
	function cookiebot_addons_remove_class_action( $action, $class, $method, $priority = 10 ) {
		global $wp_filter;
		$deleted = false;

		if ( ! isset( $wp_filter[ $action ] ) || ! isset( $wp_filter[ $action ][ $priority ] ) ) {
			return false;
		}

		foreach ( $wp_filter[ $action ][ $priority ] as $name => $def ) {
			if ( substr( $name, -strlen( $method ) ) !== $method || ! is_array( $def['function'] ) ) {
				continue;
			}

			$def_class = is_string( $def['function'][0] ) !== false
				? $def['function'][0]
				: get_class( $def['function'][0] );

			if ( $def_class === $class ) {
				if ( is_object( $wp_filter[ $action ] ) && isset( $wp_filter[ $action ]->callbacks ) ) {
					$wp_filter[ $action ]->remove_filter( $action, $name, $priority );
				} else {
					unset( $wp_filter[ $action ][ $priority ][ $name ] );
				}
				$deleted = true;
			}
		}

		return $deleted;
	}

	/**
	 * Custom manipulation of the script
	 *
	 * @param $buffer
	 * @param $keywords
	 *
	 * @return mixed|null|string|string[]
	 *
	 * @version 2.0.4
	 * @since   1.2.0
	 */
	function cookiebot_addons_manipulate_script( $buffer, $keywords ) {
		if ( empty( $buffer ) ) {
			return $buffer;
		}

		// Use DOMDocument to safely parse and modify the script tag
		$dom = new DOMDocument();

		// Suppress errors for partial HTML
		$libxml_previous_state = libxml_use_internal_errors( true );

		// Wrap buffer in a custom tag to ensure correct parsing of fragments (e.g. multiple siblings at root)
		// This prevents DOMDocument from trying to fix structure by nesting siblings
		$wrapped_buffer = '<cookiebot-wrapper>' . $buffer . '</cookiebot-wrapper>';

		// Load HTML with UTF-8 encoding hack
		// The mb_convert_encoding is to ensure we don't have encoding issues
		// We use LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD to avoid adding <html><body> wrappers automatically
		// Replacement for deprecated mb_convert_encoding(..., 'HTML-ENTITIES', 'UTF-8')
		$encoded_buffer = mb_encode_numericentity( $wrapped_buffer, array( 0x80, 0x10FFFF, 0, 0x1FFFFF ), 'UTF-8' );
		$dom->loadHTML( $encoded_buffer, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		libxml_use_internal_errors( $libxml_previous_state );

		$scripts = $dom->getElementsByTagName( 'script' );
		$modified = false;

		// Convert DOMNodeList to array to avoid modification issues during iteration
		$script_nodes = array();
		foreach ( $scripts as $script ) {
			$script_nodes[] = $script;
		}

		foreach ( $script_nodes as $script ) {
			/** @var DOMElement $script */
			// Get the full script HTML to check for keywords
			// We check the outer HTML to include attributes in the search, matching the regex behavior
			$full_script_content = $dom->saveHTML( $script );

			foreach ( $keywords as $needle => $cookie_type ) {
				if ( strpos( $full_script_content, $needle ) !== false ) {
					// Match found

					// Remove existing attributes
					$script->removeAttribute( 'type' );
					$script->removeAttribute( 'data-cookieconsent' );

					// Add new attributes
					$script->setAttribute( 'type', 'text/plain' );
					$script->setAttribute( 'data-cookieconsent', cookiebot_addons_output_cookie_types( $cookie_type ) );

					$modified = true;
					// Break inner loop (keywords) as we found a match
					break;
				}
			}
		}

		if ( $modified ) {
			// Save HTML
			// We extract children of our wrapper
			$wrapper = $dom->getElementsByTagName( 'cookiebot-wrapper' )->item( 0 );
			if ( $wrapper ) {
				$output = '';
				foreach ( $wrapper->childNodes as $node ) {
					$output .= $dom->saveHTML( $node );
				}
				return $output;
			}
			// Fallback if wrapper is missing (should not happen)
			return $dom->saveHTML();
		}

		return $buffer;
	}

	/**
	 * @param array       $cookie_types
	 * @param $cookie_type
	 */
	function cookiebot_addons_checked_selected_helper( array $cookie_types, $cookie_type ) {
		if ( in_array( $cookie_type, $cookie_types, true ) ) {
			echo " checked='checked'";
		}
	}

	/**
	 * Returns cookie types in a string
	 * Default is statistics
	 *
	 * @param $cookie_types
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 * @version 3.9.1
	 */
	function cookiebot_addons_output_cookie_types( $cookie_types ) {
		if ( is_array( $cookie_types ) && count( $cookie_types ) > 0 ) {
			return implode(
				', ',
				array_map(
					function ( $value ) {
						return cookiebot_translate_type_name( $value );
					},
					$cookie_types
				)
			);
		} elseif ( is_string( $cookie_types ) && ! empty( $cookie_types ) ) {
			return cookiebot_translate_type_name( $cookie_types );
		}

		return cookiebot_translate_type_name( 'statistics' );
	}

	/**
	 * Translates the cookie type to different language
	 *
	 * @param $type string
	 *
	 * @return string
	 *
	 * @since 3.9.1
	 */
	function cookiebot_translate_type_name( $type ) {
		switch ( $type ) {
			case 'marketing':
				$translated_name = esc_html__( 'marketing', 'cookiebot' );
				break;
			case 'statistics':
				$translated_name = esc_html__( 'statistics', 'cookiebot' );
				break;
			case 'preferences':
				$translated_name = esc_html__( 'preferences', 'cookiebot' );
				break;
			case 'necessary':
				$translated_name = esc_html__( 'necessary', 'cookiebot' );
				break;
			default:
				$translated_name = $type;
		}

		return $translated_name;
	}

	/**
	 * @param $cookie_types
	 *
	 * @return string
	 *
	 * @version 3.9.0
	 */
	function cookiebot_addons_cookieconsent_optout( $cookie_types ) {
		$output = '';

		foreach ( $cookie_types as $cookie_type ) {
			$output .= 'cookieconsent-optout-' . $cookie_type . ' ';
		}

		return trim( $output );
	}

	/**
	 * Returns current site language
	 *
	 * @return mixed|string
	 *
	 * @since 1.9.0
	 */
	function cookiebot_get_current_site_language() {
		$lang = get_locale(); // Gets language in en-US format

		/**
		 *  Add support for 3rd party plugins
		 */
		return apply_filters( 'cybot_cookiebot_addons_language', $lang );
	}

	/**
	 * Cookiebot_WP Get the language code for Cookiebot
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function cookiebot_get_language_from_setting( $only_from_setting = false ) {
		// Get language set in setting page - if empty use WP language info
		$lang           = get_option( 'cookiebot-language' );
		$front_language = get_option( 'cookiebot-front-language' );

		if ( ! empty( $front_language ) ) {
			$lang = get_locale(); // Gets language in en-US format
			if ( ! empty( $lang ) ) {
				list($lang) = explode( '_', $lang ); // Changes format from eg. en-US to en.
			}
			return $lang;
		}

		if ( ! empty( $lang ) && $lang !== '_wp' ) {
			return $lang;
		}

		if ( $only_from_setting ) {
			return $lang; // We want only to get if already set
		}

		// Language not set - use WP language
		if ( $lang === '_wp' ) {
			$lang = get_bloginfo( 'language' ); // Gets language in en-US format
			if ( ! empty( $lang ) ) {
				list($lang) = explode( '-', $lang ); // Changes format from eg. en-US to en.
			}
		}

		return $lang;
	}

	/**
	 * @param array $cookie_names
	 *
	 * @return array
	 */
	function cookiebot_translate_cookie_names( $cookie_names ) {
		$translated_cookie_names = array(
			'preferences' => esc_html__( 'preferences', 'cookiebot' ),
			'statistics'  => esc_html__( 'statistics', 'cookiebot' ),
			'marketing'   => esc_html__( 'marketing', 'cookiebot' ),
		);

		return array_map(
			function ( $cookie_name ) use ( $translated_cookie_names ) {
				$cookie_name = trim( $cookie_name );
				if ( isset( $translated_cookie_names[ $cookie_name ] ) ) {
					return $translated_cookie_names[ $cookie_name ];
				}

				return $cookie_name;
			},
			$cookie_names
		);
	}

	/**
	 * @param string $placeholder
	 *
	 * @return string
	 */
	function cookiebot_translate_placeholder( $placeholder ) {
		$translated_placeholder = array(
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to view this element.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to view this element.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Services.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Services.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable facebook shopping feature.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable facebook shopping feature.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to track for google analytics.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to track for google analytics.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Analytics.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Analytics.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable instagram feed.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable instagram feed.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Facebook Pixel.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Facebook Pixel.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to Social Share buttons.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to Social Share buttons.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to allow Matomo statistics.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to allow Matomo statistics.',
				'cookiebot'
			),
			// translators: %cookie_types refers to the list of cookie types assigned to the addon placeholder
			'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable saving user information.' => esc_html__(
				'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable saving user information.',
				'cookiebot'
			),
		);

		return empty( $translated_placeholder[ $placeholder ] ) ? $placeholder : $translated_placeholder[ $placeholder ];
	}

	/**
	 * Show languages in a select field
	 *
	 * @param $class
	 * @param $name
	 * @param $selected
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	function cookiebot_addons_get_dropdown_languages( $class, $name, $selected ) {
		$args     = array(
			'name'                     => $name,
			'selected'                 => $selected,
			'show_option_site_default' => true,
			'echo'                     => false,
			'languages'                => get_available_languages(),
		);
		$dropdown = wp_dropdown_languages( $args );

		$output = str_replace( 'select ', 'select class="' . $class . '" ', $dropdown );

		return (string) str_replace( 'value="" ', 'value="en_US" ', $output );
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 3.11.0
	 */
	function cookiebot_addons_get_domain_from_url( $url ) {
		$parsed_url = wp_parse_url( $url );

		// relative url does not have host so use home url domain
		$host = isset( $parsed_url['host'] ) ? $parsed_url['host'] : cookiebot_addons_get_home_url_domain();

		$url_parts = explode( '.', $host );

		$url_parts = array_slice( $url_parts, -2 );

		return implode( '.', $url_parts );
	}

	/**
	 * @return string
	 * @throws Exception
	 *
	 * @since 3.11.0
	 */
	function cookiebot_addons_get_home_url_domain() {
		$home_url = wp_parse_url( home_url() );
		/** @var $host string */
		$host = $home_url['host'];

		if ( empty( $host ) ) {
			throw new DomainException( 'Home url domain is not found.' );
		}

		return $host;
	}

	/**
	 * @param $file_path
	 *
	 * @return false|string
	 * @throws Exception
	 */
	function cookiebot_get_local_file_contents( $file_path ) {
		if ( ! file_exists( $file_path ) ) {
			throw new InvalidArgumentException( 'File ' . $file_path . ' does not exist' );
		}

		ob_start();
		include $file_path;

		return ob_get_clean();
	}

	/**
	 * @param string $relative_path
	 * @throws InvalidArgumentException
	 */
	function include_view( $relative_path, array $view_args = array() ) {
		if ( isset( $view_args['absolute_path'] ) ) {
			throw new InvalidArgumentException( 'Param $view_args array should not include an "absolute_path" key' );
		}
		$absolute_path = CYBOT_COOKIEBOT_PLUGIN_DIR . 'src/view/' . $relative_path;
		if ( ! file_exists( $absolute_path ) ) {
			throw new InvalidArgumentException( 'View could not be loaded from "' . $absolute_path . '"' );
		}
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $view_args );
		include $absolute_path;
	}

	/**
	 * @param string $relative_path
	 * @throws InvalidArgumentException
	 */
	function get_view_html( $relative_path, array $view_args = array() ) {
		ob_start();
		include_view( $relative_path, $view_args );
		return (string) ob_get_clean();
	}

	/**
	 * @param string $relative_path
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	function asset_path( $relative_path ) {
		$absolute_path = CYBOT_COOKIEBOT_PLUGIN_DIR . CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR . $relative_path;
		if ( ! file_exists( $absolute_path ) ) {
			throw new InvalidArgumentException( 'Asset could not be loaded from "' . $absolute_path . '"' );
		}
		return $absolute_path;
	}

	/**
	 * @param string $relative_path
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	function asset_url( $relative_path ) {
		if ( is_multisite() ) {
			$url = get_site_url( 1 ) . CYBOT_COOKIEBOT_PLUGIN_NETWORK_DIR . CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR . $relative_path;
			return $url;
		}

		$absolute_path = CYBOT_COOKIEBOT_PLUGIN_DIR . CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR . $relative_path;
		$url           = esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR . $relative_path );
		if ( ! file_exists( $absolute_path ) || empty( $url ) ) {
			throw new InvalidArgumentException( 'Asset could not be loaded from "' . $absolute_path . '"' );
		}
		return $url;
	}

	/**
	 * @return string
	 * @throws InvalidArgumentException
	 */
	function logo_url() {
		if ( is_multisite() ) {
			$url = get_site_url( 1 ) . CYBOT_COOKIEBOT_PLUGIN_NETWORK_DIR . CYBOT_COOKIEBOT_PLUGIN_LOGO_FILE;
			return $url;
		}

		$absolute_path = CYBOT_COOKIEBOT_PLUGIN_DIR . CYBOT_COOKIEBOT_PLUGIN_LOGO_FILE;
		$url           = esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . CYBOT_COOKIEBOT_PLUGIN_LOGO_FILE );
		if ( ! file_exists( $absolute_path ) || empty( $url ) ) {
			throw new InvalidArgumentException( 'Asset could not be loaded from "' . $absolute_path . '"' );
		}
		return $url;
	}

	/**
	 * Helper function to update your scripts
	 *
	 * @param string|string[] $type
	 *
	 * @return string
	 */
	function cookiebot_assist( $type = 'statistics' ) {
		$type_array = array_filter(
			is_array( $type ) ? $type : array( $type ),
			function ( $type ) {
				return in_array( $type, array( 'marketing', 'statistics', 'preferences' ), true );
			}
		);

		if ( count( $type_array ) > 0 ) {
			return ' type="text/plain" data-cookieconsent="' . implode( ',', $type_array ) . '"';
		}

		return '';
	}

	/**
	 * Helper function to check if cookiebot is active.
	 * Useful for other plugins adding support for Cookiebot.
	 *
	 * @return  bool
	 * @since   1.2
	 * @version 2.2.2
	 */
	function cookiebot_active() {
		$cbid = Cookiebot_WP::get_cbid();
		return ! empty( $cbid );
	}

	/**
	 * Returns the main instance of Cookiebot_WP to prevent the need to use globals.
	 *
	 * @return  Cookiebot_WP
	 * @throws RuntimeException
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function cookiebot() {
		return Cookiebot_WP::instance();
	}

	const CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR  = 'assets/';
	const CYBOT_COOKIEBOT_PLUGIN_NETWORK_DIR = '/wp-content/plugins/cookiebot/';
	const CYBOT_COOKIEBOT_PLUGIN_LOGO_FILE   = 'logo.svg';
}
