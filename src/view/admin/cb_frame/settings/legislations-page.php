<div class="wrap">
	<h1><?php esc_html_e( 'Legislations', 'cookiebot' ); ?></h1>

	<p>
		<?php
		echo sprintf(
		/* translators: The first placeholder is the HTML anchor open tag, and the second placeholder is the closing tag. */
			esc_html__(
				'For more details about Cookiebot\'s CCPA Legislation integration, see %1$sarticle about cookiebot and the CCPA compliance%2$s',
				'cookiebot'
			),
			'<a href="https://support.cookiebot.com/hc/en-us/articles/360010932419-Use-multiple-banners-on-the-same-website-support-both-CCPA-GDPR-compliance-" ' .
			'target="_blank" rel="noopener">',
			'</a>'
		);
		?>
	</p>
</div>
