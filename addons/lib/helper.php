<?php
/**
 * Check if a cache plugin is activated and in function.
 * 
 * @return boolean	True	If attributes always should be added
 * 					False	If attributes only should be added if consent no given
 */

function cookiebot_addons_enabled_cache_plugin() {
	if( defined( "WP_ROCKET_PATH" ) ) {
		return true; //WP Rocket - We need to ensure we not cache tags without attributes
	}
	if( defined( "W3TC" ) ) {
		return true; //W3 Total Cache
	}
	if( defined( "WPCACHEHOME" ) ) {
		return true; //WP Super Cache
	}
	if( defined( "WPFC_WP_PLUGIN_DIR" ) ) {
		return true; //WP Fastest Cache
	}
	if( defined( "LSCWP_CONTENT_DIR" ) ) {
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
	 * Pattern to get all scripts
	 *
	 * @version 2.0.4
	 * @since   1.2.0
	 */
	$pattern = "/<script[\s\S]*?>[\s\S]*?<\/script>/i";

	/**
	 * Get all scripts and add cookieconsent if it does match with the criterion
	 */
	$updated_scripts = preg_replace_callback( $pattern, function ( $matches ) use ( $keywords ) {
		/**
		 * Matched script data
		 */
		$data = ( isset( $matches[0] ) ) ? $matches[0] : '';

		/**
		 * Check if the script contains the keywords, checks keywords one by one
		 *
		 * If one match, then the rest of the keywords will be skipped.
		 **/
		foreach ( $keywords as $needle => $cookie_type ) {
			/**
			 * The script contains the needle
			 **/
			if ( strpos( $data, $needle ) !== false ) {
				$data = preg_replace( '/\<script/', '<script type="text/plain" data-cookieconsent="' . cookiebot_addons_output_cookie_types( $cookie_type ) . '"', $data );
				$data = preg_replace( '/type\=\"text\/javascript\"/', '', $data );

				/**
				 * matched already so we can skip other keywords
				 **/
				continue;
			}
		}

		/**
		 * Return updated script data
		 */
		return $data;
	}, $buffer );

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
 * @param bool $echo
 * @param string $type
 *
 * @return string
 *
 * @since 1.3.0
 */
function cookiebot_addons_checked_selected_helper( $helper, $current, $echo = true, $type = 'checked' ) {
	if ( is_array( $helper ) && in_array( $current, $helper ) ) {
		$result = " $type='$type'";
	} elseif ( is_string( $helper ) && is_string( $current ) && $helper === $current ) {
		$result = " $type='$type'";
	} else {
		$result = '';
	}


	if ( $echo ) {
		echo $result;
	}

	return $result;
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
 */
function cookiebot_addons_output_cookie_types( $cookie_types ) {
	if ( is_array( $cookie_types ) && count( $cookie_types ) > 0 ) {
		return implode( ', ', $cookie_types );
	} elseif ( is_string( $cookie_types ) && $cookie_types != '' ) {
		return $cookie_types;
	}

	return 'statistics';
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
 * Show languages in a select field
 *
 * @param $class
 * @param $name
 * @param $selected
 *
 * @return mixed
 *
 * @since 1.8.0
 */
function cookiebot_addons_get_dropdown_languages( $class, $name, $selected ) {
	$args     = array(
		'name'                     => $name,
		'selected'                 => $selected,
		'show_option_site_default' => true,
		'echo'                     => false,
		'languages'                => get_available_languages()
	);
	$dropdown = wp_dropdown_languages( $args );

	$output = str_replace( 'select ', 'select class="' . $class . '" ', $dropdown );

	$output = str_replace( 'value="" ', 'value="en_US" ', $output );

	return $output;
}

/**
 * Run actions when the cookiebot plugin is deactivated
 *
 * @since 2.2.0
 */
function cookiebot_addons_plugin_deactivated( ) {
	$cookiebot_addons = \cookiebot_addons\Cookiebot_Addons::instance();
	$cookiebot_addons->cookiebot_deactivated();
}
