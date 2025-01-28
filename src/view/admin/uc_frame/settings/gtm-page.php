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
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Google Tag Manager', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Enable Google Tag Manager integration to streamline tracking tags with your Usercentrics Cookiebot WordPress Plugin.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174#3"
			class="cb-btn cb-link-btn" target="_blank" rel="noopener">
			&nbsp;<?php esc_html_e( 'Learn more', 'cookiebot' ); ?>
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
			<?php esc_html_e( 'Enter your Google Tag Manager ID for seamless integration.', 'cookiebot' ); ?>
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
					placeholder="<?php esc_html_e( 'GTM-XXXXXXX', 'cookiebot' ); ?>"
					value="<?php echo esc_html( get_option( 'cookiebot-gtm-id' ) ); ?>">
		</div>
	</div>
</div>

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Data layer name (only if changed)', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'The default name for the data layer in Google Tag Manager is ‘dataLayer’. If you renamed it, enter the new name. Otherwise, leave this field empty.', 'cookiebot' ); ?>
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
