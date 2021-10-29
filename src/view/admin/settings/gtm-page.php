<div class="wrap">
	<h1><?php esc_html_e( 'Google Tag Manager', 'cookiebot' ); ?></h1>

	<form method="post" action="options.php" class="form_gtm">
		<?php settings_fields( 'cookiebot-gtm' ); ?>
		<?php do_settings_sections( 'cookiebot-gtm' ); ?>

		<p><?php esc_html_e( 'Enable GTM', 'cookiebot' ); ?></p>
		<div class="GTM_check">
			<input
					type="checkbox"
					name="cookiebot-gtm"
					id="cookiebot-gtm"
					value="1" <?php checked( 1, get_option( 'cookiebot-gtm' ) ); ?>>
			<p>
				<?php
				esc_html_e(
					'For more details about Cookiebot and Google Tag Manager click',
					'cookiebot'
				);
				?>
				<a
					target="_blank"
					href="https://www.cookiebot.com/en/google-tag-manager-and-gdpr-compliance-with-cookiebot/">
					&nbsp;<?php esc_html_e( 'here', 'cookiebot' ); ?>
				</a>
			</p>
		</div>

		<p><?php esc_html_e( 'GTM ID', 'cookiebot' ); ?></p>
		<input
				type="text"
				name="cookiebot-gtm-id"
				id="cookiebot-gtm-id"
				value="<?php echo esc_html( get_option( 'cookiebot-gtm-id' ) ); ?>">

		<p><?php esc_html_e( 'DataLayer name', 'cookiebot' ); ?></p>
		<div>
			<input
					type="text"
					name="cookiebot-data-layer"
					id="data_layer"
					placeholder="dataLayer"
					value="<?php echo esc_html( get_option( 'cookiebot-data-layer' ) ); ?>">
			<p style="margin: 0;"><?php esc_html_e( 'Optional, only change if necessary', 'cookiebot' ); ?></p>
		</div>

		<p><?php esc_html_e( 'Google Consent Mode', 'cookiebot' ); ?></p>
		<div class="GTM_check">
			<input
					type="checkbox"
					name="cookiebot-gcm"
					id="gcm"
					value="1" <?php checked( 1, get_option( 'cookiebot-gcm' ) ); ?>>
			<p>
				<?php
				esc_html_e(
					'For more details about Cookiebot and Google Consent Mode click',
					'cookiebot'
				);
				?>
				<a
					target="_blank"
					href="https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode">
					&nbsp;<?php esc_html_e( 'here', 'cookiebot' ); ?>
				</a>
			</p>
		</div>
		<input type="submit" value="Save" name="gtm_save" id="gtm_save">
	</form>
</div>
