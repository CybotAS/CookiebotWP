<?php

/**
 * Adds enqueue script handle tag to Cookiebot_Script_loader_Tag class
 * So the script can have cookiebot attributes
 *
 * @param $tag  string  Handle tag name
 * @param $type string  marketing|statistics|preferences|necessary
 *
 * @since 1.1.0
 */
function cookiebot_script_loader_tag( $tag, $type = 'statistics' ) {
	$script_loader_tag = \cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag::instance();
	$script_loader_tag->add_tag( $tag, $type );
}

/**
 * Adds buffer to manipulate scripts
 *
 * @param $tag  string  Action hook name
 * @param $priority integer Action hook priority
 *
 * @since 1.1.0
 */
function cookiebot_buffer_output( $tag, $priority ) {
	new \cookiebot_addons_framework\lib\Cookiebot_Buffer_Output( $tag, $priority );
}

/**
 * Returns checked consent in an array
 *
 * @return array    array of checked consents
 *
 * @since 1.2.0
 */
function cookiebot_get_accepted_cookie_states() {
	$cookie_consent = \cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent::instance();

	return $cookie_consent->get_cookie_states();
}

/**
 * Check if given consent type is accepted
 *
 * @param $state    string  Consent type
 *
 * @return bool
 *
 * @since 1.2.0
 */
function cookiebot_is_cookie_state_accepted( $state ) {
	$cookie_consent = \cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent::instance();

	return $cookie_consent->is_cookie_state_accepted( $state );
}