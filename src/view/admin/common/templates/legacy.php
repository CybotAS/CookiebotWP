<?php

/**
 * @var array $options
 */

use cybot\cookiebot\settings\templates\Legacy_Settings;

?>
<div>
	<?php
	if ( ! empty( $options ) ) {
		foreach ( $options as $option => $value ) {
			echo wp_kses(
				Legacy_Settings::get_legacy_option( $option, $value ),
				array(
					'input' => array(
						'type'  => array(),
						'value' => array(),
						'name'  => array(),
					),
				)
			);
		}
	}
	?>
</div>
