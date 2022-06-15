<?php

namespace cybot\cookiebot\lib\script_loader_tag;

interface Script_Loader_Tag_Interface {

	/**
	 * @param $tag
	 * @param $type
	 *
	 * @return mixed
	 */
	public function add_tag( $tag, $type );

	/**
	 * @param $script
	 *
	 * @return mixed
	 */
	public function ignore_script( $script );

	/**
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed
	 */
	public function cookiebot_add_consent_attribute_to_tag( $tag, $handle, $src );
}
