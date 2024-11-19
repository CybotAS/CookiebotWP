<?php
/**
 * @var string $cookie_categories_disabled
 * @var string $auto_disabled
 * @var bool $is_preferences
 * @var bool $is_statistics
 * @var bool $is_marketing
 */
?>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Google Tag Manager:', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'For more details about Cookiebot CMP and Google Tag Manager.', 'cookiebot' ); ?>
		</p>
		<a href="https://www.cookiebot.com/en/google-tag-manager-and-gdpr-compliance-with-cookiebot/"
		   class="cb-btn cb-link-btn" target="_blank" rel="noopener">
			&nbsp;<?php esc_html_e( 'Read more', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-gtm">
				<input type="checkbox" name="cookiebot-gtm" id="cookiebot-gtm" value="1"
					<?php checked( 1, get_option( 'cookiebot-gtm' ) ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'Google Tag Manager', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Google Tag Manager ID', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Paste your Tag Manager ID into the field on the right.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174#3" class="cb-btn cb-link-btn"
		   target="_blank" rel="noopener">
			&nbsp;<?php esc_html_e( 'How to find the GTM ID', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Google Tag Manager ID', 'cookiebot' ); ?></h3>
			<input type="text" name="cookiebot-gtm-id" id="cookiebot-gtm-id"
				   placeholder="<?php esc_html_e( 'Enter GTM ID', 'cookiebot' ); ?>"
				   value="<?php echo esc_html( get_option( 'cookiebot-gtm-id' ) ); ?>">
		</div>
	</div>
</div>

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Data Layer Name (optional)', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'You can also paste your Data Layer Name here. This is optional information.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174#3" class="cb-btn cb-link-btn"
		   target="_blank" rel="noopener">
			&nbsp;<?php esc_html_e( 'How to find the Data Layer Name', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Name of your Data Layer', 'cookiebot' ); ?></h3>
			<input type="text" name="cookiebot-data-layer" id="data_layer" placeholder="dataLayer"
				   value="<?php echo esc_html( get_option( 'cookiebot-data-layer' ) ); ?>">
		</div>
	</div>
</div>

<div id="gtm-cookie-categories" class="cb-settings__config__item<?php echo esc_attr( $auto_disabled ); ?>">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Google Tag Manager cookies', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Select the cookie types that need to be consented for the Google Tag Manager script', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note"><?php esc_html_e( 'This feature is only available when using Manual Blocking', 'cookiebot' ); ?></p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This option may affect the behaviour of your GTM Tags, as the script will run on the selected cookies consent.', 'cookiebot' ); ?>
			<br>
			<?php esc_html_e( 'Please make sure your Tags in Google Tag Manager are triggered correctly.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></h3>
			<ul id="cb-settings__gtm__cookie-types">
				<li>
					<input
							type="checkbox"
							value="preferences"
						<?php checked( true, $is_preferences ); ?>
							name="cookiebot-gtm-cookies[]"<?php echo esc_attr( $cookie_categories_disabled ); ?>>
					<label class="cb-settings__cookie-types"><?php esc_html_e( 'preferences', 'cookiebot' ); ?></label>
				</li>
				<li>
					<input
							type="checkbox"
							value="statistics"
						<?php checked( true, $is_statistics ); ?>
							name="cookiebot-gtm-cookies[]"<?php echo esc_attr( $cookie_categories_disabled ); ?>>
					<label class="cb-settings__cookie-types"><?php esc_html_e( 'statistics', 'cookiebot' ); ?></label>
				</li>
				<li>
					<input
							type="checkbox"
							value="marketing"
						<?php checked( true, $is_marketing ); ?>
							name="cookiebot-gtm-cookies[]"<?php echo esc_attr( $cookie_categories_disabled ); ?>>
					<label class="cb-settings__cookie-types"><?php esc_html_e( 'marketing', 'cookiebot' ); ?></label>
				</li>
			</ul>
		</div>
	</div>
</div>
