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

	<form method="post" action="options.php">
		<?php settings_fields( 'cookiebot-legislations' ); ?>
		<?php do_settings_sections( 'cookiebot-legislations' ); ?>


		<table class="form-table" role="presentation">
			<tbody>
			<tr>
				<th scope="row">
					<label>
						<?php esc_html_e( 'Enable CCPA configuration for visitors from California', 'cookiebot' ); ?>
					</label>
				</th>
				<td>
					<input
						type="checkbox"
						name="cookiebot-ccpa"
						value="1" <?php checked( 1, esc_attr( get_option( 'cookiebot-ccpa' ) ) ); ?>>
				</td>
			</tr>
			<tr>
				<th><label><?php esc_html_e( 'Domain Group ID', 'cookiebot' ); ?></label></th>
				<td>
					<input
						type="text"
						style="width: 300px;"
						name="cookiebot-ccpa-domain-group-id"
						value="<?php echo esc_attr( get_option( 'cookiebot-ccpa-domain-group-id' ) ); ?>">
				</td>
			</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
