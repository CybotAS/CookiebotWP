<?php

/**
 * Adds enqueue script handle tag to Cookiebot_Script_loader_Tag class
 * So the script can have cookiebot attributes
 *
 * @param $tag  string  Handle tag name
 *
 * @since 1.1.0
 */
function cookiebot_script_loader_tag( $tag ) {
	$script_loader_tag = \cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag::instance();
	$script_loader_tag->add_tag( $tag );
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