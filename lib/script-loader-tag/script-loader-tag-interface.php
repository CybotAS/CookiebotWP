<?php

namespace cookiebot_addons\lib\script_loader_tag;

Interface Script_Loader_Tag_Interface {

	/**
	 * @param $tag
	 * @param $type
	 *
	 * @return mixed
	 */
	public function add_tag($tag, $type );

	/**
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed
	 */
	public function cookiebot_add_consent_attribute_to_tag( $tag, $handle, $src );
}
