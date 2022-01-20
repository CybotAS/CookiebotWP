<div class="wrap">
	<h1><?php esc_html_e( 'IAB', 'cookiebot' ); ?></h1>

	<p>
		<?php
		echo sprintf(
				/* translators: %1$s is replaced by a starting tag of href, %2$s is replaced by a closing tag of href */
			esc_html__(
				'For more details about Cookiebot\'s IAB integration, see %1$sarticle about cookiebot and the IAB consent framework%2$s',
				'cookiebot'
			),
			'<a href="https://support.cookiebot.com/hc/en-us/articles/360007652694-Cookiebot-and-the-IAB-Consent-Framework" target="_blank">',
			'</a>'
		);
		?>
	</p>

	<form method="post" action="options.php">
		<?php settings_fields( 'cookiebot-iab' ); ?>
		<?php do_settings_sections( 'cookiebot-iab' ); ?>

		<label for="cookiebot-iab"><?php esc_html_e( 'Enable IAB integration', 'cookiebot' ); ?></label>
		<input
			type="checkbox"
			name="cookiebot-iab"
			id="cookiebot-iab"
			value="1" <?php checked( 1, get_option( 'cookiebot-iab' ) ); ?>>

		<?php submit_button(); ?>
	</form>
</div>
