<?php

namespace cybot\cookiebot\lib;

interface Open_Source_Addon_Interface {
	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url();
}
