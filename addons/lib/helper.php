<?php
/**
 * Check if a cache plugin is activated and in function.
 *
 * @return boolean  True    If attributes always should be added
 *                  False   If attributes only should be added if consent no given
 */

function cookiebot_addons_enabled_cache_plugin() {
	if ( defined( 'WP_ROCKET_PATH' ) ) {
		return true; //WP Rocket - We need to ensure we not cache tags without attributes
	}
	if ( defined( 'W3TC' ) ) {
		return true; //W3 Total Cache
	}
	if ( defined( 'WPCACHEHOME' ) ) {
		return true; //WP Super Cache
	}
	if ( defined( 'WPFC_WP_PLUGIN_DIR' ) ) {
		return true; //WP Fastest Cache
	}
	if ( defined( 'LSCWP_CONTENT_DIR' ) ) {
		return true; //Litespeed Cache
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

	if ( isset( $wp_filter[ $action ] ) && isset( $wp_filter[ $action ][ $priority ] ) ) {
		$len = strlen( $method );
		foreach ( $wp_filter[ $action ][ $priority ] as $name => $def ) {
			if ( substr( $name, - $len ) == $method ) {
				if ( is_array( $def['function'] ) ) {
					if ( is_string( $def['function'][0] ) !== false ) {
						$def_class = $def['function'][0];
					} else {
						$def_class = get_class( $def['function'][0] );
					}

					if ( $def_class == $class ) {
						if ( is_object( $wp_filter[ $action ] ) && isset( $wp_filter[ $action ]->callbacks ) ) {
							$wp_filter[ $action ]->remove_filter( $action, $name, $priority );
							$deleted = true;
						} else {
							unset( $wp_filter[ $action ][ $priority ][ $name ] );
							$deleted = true;
						}
					}
				}
			}
		}
	}

	return $deleted;
}

/**
 * Custom manipulation of the script
 *
 * @param $buffer
 * @param $keywords
 * @param $cookie_type
 *
 * @return mixed|null|string|string[]
 *
 * @version 2.0.4
 * @since   1.2.0
 */
function cookiebot_addons_manipulate_script( $buffer, $keywords ) {
	/**
	 * normalize potential self-closing script tags
	 */

	$normalized_buffer = preg_replace( '/(<script(.*?)\/>)/is', '<script$2></script>', $buffer );

	if ( $normalized_buffer !== null ) {
		$buffer = $normalized_buffer;
	}

	/**
	 * Pattern to get all scripts
	 *
	 * @version 2.0.4
	 * @since   1.2.0
	 */
	$pattern = '/(<script(?:.*?)>)(.*?)(<\/script>)/is';

	/**
	 * Get all scripts and add cookieconsent if it does match with the criterion
	 */
	$updated_scripts = preg_replace_callback(
		$pattern,
		function ( $matches ) use ( $keywords ) {

			$script           = $matches[0]; // the full script html
			$script_tag_open  = $matches[1]; // only the script open tag with all attributes
			$script_tag_inner = $matches[2]; // only the script's innerText
			$script_tag_close = $matches[3]; // only the script closing tag

			/**
			 * Check if the script contains the keywords, checks keywords one by one
			 *
			 * If one match, then the rest of the keywords will be skipped.
			 **/
			foreach ( $keywords as $needle => $cookie_type ) {
				/**
				 * The script contains the needle
				 **/
				if ( strpos( $script, $needle ) !== false ) {
					/**
					 * replace all single quotes with double quotes in the open tag
					 * remove previously set data-cookieconsent attribute
					 * remove type attribute
					 */
					$script_tag_open = preg_replace( '/\'/', '"', $script_tag_open );
					$script_tag_open = preg_replace( '/\sdata-cookieconsent=\"(?:.*?)\"/', '', $script_tag_open );
					$script_tag_open = preg_replace( '/\stype=\"(?:.*?)\"/', '', $script_tag_open );

					/**
					 * set the type attribute to text/plain to prevent javascript execution
					 * add data-cookieconsent attribute
					 */
					$cookie_types    = cookiebot_addons_output_cookie_types( $cookie_type );
					$replacement     = '<script type="text/plain" data-cookieconsent="' . $cookie_types . '"';
					$script_tag_open = preg_replace( '/<script/', $replacement, $script_tag_open );

					/**
					 * reconstruct the script and break the foreach loop
					 */
					$script = $script_tag_open . $script_tag_inner . $script_tag_close;
					continue;
				}
			}

			/**
			 * return the reconstructed script
			 */
			return $script;
		},
		$buffer
	);

	/**
	 * Fallback when the regex fails to work due to PCRE_ERROR_JIT_STACKLIMIT
	 *
	 * @version 2.0.4
	 * @since   2.0.4
	 */
	if ( $updated_scripts === null ) {
		$updated_scripts = $buffer;

		if ( get_option( 'cookiebot_regex_stacklimit' ) === false ) {
			update_option( 'cookiebot_regex_stacklimit', 1 );
		}
	}

	return $updated_scripts;
}

/**
 * Compares array to string to add checked attribute in checkbox
 *
 * @param        $helper
 * @param        $current
 * @param  bool  $echo
 * @param  string  $type
 *
 * @return string
 *
 * @since 1.3.0
 */
function cookiebot_addons_checked_selected_helper( $helper, $current, $echo = true, $type = 'checked' ) {
	$current_in_helper_array      = is_array( $helper ) && in_array( $current, $helper, true );
	$current_equals_helper_string = is_string( $helper ) && is_string( $current ) && $helper === $current;

	if ( $current_in_helper_array || $current_equals_helper_string ) {
		if ( $echo ) {
			echo ' ' . esc_attr( $type ) . '=\'' . esc_attr( $type ) . '\'';
		}

		return " $type='$type'";
	}

	return '';
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
	} elseif ( is_string( $cookie_types ) && $cookie_types != '' ) {
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
			return esc_html__( 'marketing', 'cookiebot' );
		case 'statistics':
			return esc_html__( 'statistics', 'cookiebot' );
		case 'preferences':
			return esc_html__( 'preferences', 'cookiebot' );
		case 'necessary':
			return esc_html__( 'necessary', 'cookiebot' );
		default:
			return $type;
	}
}

/**
 * Return 1 cookie type if more than 1 is selected
 *
 * @param $cookie_types
 *
 * @return string
 *
 * @since 1.3.0
 */
function cookiebot_addons_get_one_cookie_type( $cookie_types ) {
	if ( is_array( $cookie_types ) ) {
		if ( in_array( 'marketing', $cookie_types ) ) {
			return 'marketing';
		} elseif ( in_array( 'statistics', $cookie_types ) ) {
			return 'statistics';
		} elseif ( in_array( 'preferences', $cookie_types ) ) {
			return 'preferences';
		}
	}

	return '';
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
function cookiebot_addons_get_language() {
	$lang = get_locale(); //Gets language in en-US format

	/**
	 *  Add support for 3rd party plugins
	 */
	$lang = apply_filters( 'cookiebot_addons_language', $lang );

	return $lang;
}

/**
 * @param  array  $cookie_names
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
		function ( string $cookie_name ) use ( $translated_cookie_names ) {
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
 * Get supported languages by the cookiebot
 *
 * @return array
 *
 * @since 1.9.0
 */
function cookiebot_addons_get_supported_languages() {
	$cookiebot = cookiebot();

	return $cookiebot->get_supported_languages();
}

/**
 * Returns an escaped HTML "select" element
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
	$output   = str_replace( '<select ', '<select class="' . esc_attr( $class ) . '" ', $dropdown );

	return str_replace( ' value="" ', 'value="en_US" ', $output );
}

/**
 * Run actions when the cookiebot plugin is deactivated
 *
 * @since 2.2.0
 */
function cookiebot_addons_plugin_deactivated() {
	$cookiebot_addons = \cookiebot_addons\Cookiebot_Addons::instance();
	$cookiebot_addons->cookiebot_deactivated();
}

/**
 * Run actions when the cookiebot plugin is deactivated
 *
 * @since 3.6.3
 */
function cookiebot_addons_plugin_activated() {
	$cookiebot_addons = \cookiebot_addons\Cookiebot_Addons::instance();
	$cookiebot_addons->cookiebot_activated();
}

/**
 * @param  string  $url
 *
 * @return string
 *
 * @since 3.11.0
 */
function cookiebot_addons_get_domain_from_url( $url ) {
	$parsed_url = parse_url( $url );

	// relative url does not have host so use home url domain
	$host = isset( $parsed_url['host'] ) ? $parsed_url['host'] : cookiebot_addons_get_home_url_domain();

	$url_parts = explode( '.', $host );

	$url_parts = array_slice( $url_parts, - 2 );

	return implode( '.', $url_parts );
}

/**
 * @return string
 * @throws Exception
 *
 * @since 3.11.0
 */
function cookiebot_addons_get_home_url_domain() {
	$home_url = parse_url( home_url() );
	/** @var $host string */
	$host = $home_url['host'];

	if ( empty( $host ) ) {
		throw new Exception( 'Home url domain is not found.' );
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
		throw new Exception( 'File ' . $file_path . ' does not exist' );
	}

	ob_start();
	include $file_path;

	return ob_get_clean();
}

/**
 * @param $file_path
 *
 * @return array
 * @throws Exception
 */
function cookiebot_get_local_file_json_contents( $file_path ) {
	$json = cookiebot_get_local_file_contents( $file_path );

	$decoded_json = json_decode( $json );

	if ( ! is_a( $decoded_json, stdClass::class ) ) {
		throw new Exception( 'Filepath ' . $file_path . ' could not be parsed as json file' );
	}

	/**
	 * @var array $decoded_json
	 */
	return $decoded_json;
}
