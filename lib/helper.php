<?php

/**
 * Removes action with class in callback
 *
 * @param $action   string  action name
 * @param $class    string  class name
 * @param $method   string  method name
 * @param $priority integer action priority number
 *
 * @since 1.2.0
 */
function cookiebot_remove_class_action( $action, $class, $method, $priority = 10 ) {
	global $wp_filter;

	if ( isset( $wp_filter[ $action ] ) ) {
		$len = strlen( $method );
		foreach ( $wp_filter[ $action ][ $priority ] as $name => $def ) {
			if ( substr( $name, - $len ) == $method ) {
				if ( is_array( $def['function'] ) ) {
					if ( get_class( $def['function'][0] ) == $class ) {
						if ( is_object( $wp_filter[ $action ] ) && isset( $wp_filter[ $action ]->callbacks ) ) {
							$wp_filter[ $action ]->remove_filter( $action, $name, $priority );
						} else {
							unset( $wp_filter[ $action ][ $priority ][ $name ] );
						}
					}
				}
			}
		}
	}
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
 * @since 1.2.0
 */
function cookiebot_manipulate_script( $buffer, $keywords ) {
	/**
	 * Pattern to get all scripts
	 */
	$pattern = "/\<script(.*?)?\>(.|\s)*?\<\/script\>/i";

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
				$data = preg_replace( '/\<script/', '<script type="text/plain" data-cookieconsent="' . $cookie_type . '"', $data );
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

	return $updated_scripts;
}

/**
 * Compares array to string to add checked attribute in checkbox
 *
 * @param $helper
 * @param $current
 * @param bool $echo
 * @param string $type
 *
 * @return string
 *
 * @since 1.3.0
 */
function cookiebot_checked_selected_helper( $helper, $current, $echo = true, $type = 'checked' ) {
	if ( is_array( $helper ) && in_array( $current, $helper ) ) {
		$result = " $type='$type'";
	} elseif ( is_string( $helper) && is_string( $current) && $helper === $current ) {
		$result = " $type='$type'";
	} else {
		$result = '';
	}


	if ( $echo ) {
		echo $result;
	}

	return $result;
}