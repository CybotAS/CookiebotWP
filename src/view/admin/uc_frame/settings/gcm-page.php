<?php
/**
 * @var string $cookie_categories_disabled
 * @var string $gcm_enabled_option
 * @var string $gcm_url_passthrough_option
 * @var string $auto_disabled
 * @var bool $is_preferences
 * @var bool $is_statistics
 * @var bool $is_marketing
 */
?>
<div class="cb-general__consent__mode">
	<h2 class="cb-general__info__title">
		<?php esc_html_e( 'Integration with Google Consent Mode', 'cookiebot' ); ?>
	</h2>
	<p class="cb-general__info__text">
		<?php esc_html_e( 'The Usercentrics Cookiebot WordPress Plugin and Google Consent Mode integrate seamlessly, providing plug-and-play privacy compliance and effortless use of all Google services in one solution.', 'cookiebot' ); ?>
	</p>
	<a class="cb-btn cb-link-btn" target="_blank" rel="noopener"
		href="https://support.cookiebot.com/hc/en-us/articles/360016047000-Implementing-Google-consent-mode#h_01J03HKJ8K2WPB1HJZQQ5BDMQQ">
		<?php esc_html_e( 'Learn more', 'cookiebot' ); ?>
	</a>
</div>

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Google Consent Mode', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Enable Google Consent Mode integration within your Usercentrics Cookiebot WordPress Plugin.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="gcm">
				<input
						type="checkbox"
						name="cookiebot-gcm"
						id="gcm"
						value="1" <?php checked( 1, $gcm_enabled_option ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'Google Consent Mode', 'cookiebot' ); ?>
				<?php echo ( $gcm_enabled_option === '1' ) ? 'enabled' : 'disabled'; ?>
			</label>
			<input type="hidden" name="cookiebot-gcm-first-run" value="1">
		</div>
	</div>
</div>
