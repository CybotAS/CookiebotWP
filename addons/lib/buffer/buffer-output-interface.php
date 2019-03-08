<?php

namespace cookiebot_addons\lib\buffer;

Interface Buffer_Output_Interface {

	/**
	 * @param $tag_name         string      Hook name
	 * @param $priority         integer     Hook priority
	 * @param array $keywords               List of words to search for in the script
	 * @param boolean   $use_cache          Use Cache
	 *
	 * @since 1.2.0
	 */
	public function add_tag( $tag_name, $priority, $keywords = array(), $use_cache = true );

	/**
	 * Process every tag
	 *
	 * @since 1.2.0
	 */
	public function run_actions();

	/**
	 * Returns true if tags has more than 0 item
	 *
	 * @return bool
	 *
	 * @since 1.2.0
	 */
	public function has_action();
}
