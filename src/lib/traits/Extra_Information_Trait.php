<?php

namespace cybot\cookiebot\lib\traits;

trait Extra_Information_Trait {

	/**
	 * Adds extra information under the label
	 * Each string in the array will be rendered in a <p> element, and should contain a localized string of information.
	 *
	 * @return string[]
	 */
	public function get_extra_information() {
		return array();
	}
}
