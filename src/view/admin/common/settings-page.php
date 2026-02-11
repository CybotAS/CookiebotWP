<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Legacy_Settings;
use cybot\cookiebot\settings\templates\Main_Tabs;

/**
 * @var string $cbid
 * @var bool $is_ms
 * @var string $network_cbid
 * @var string $ruleset_id
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$active_tab = ! empty( $_GET['tab'] ) ? $_GET['tab'] : false;

$header->display();
?>

<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'settings' ); ?>
		<div class="cb-main__content">
			<form method="post" action="options.php">
				<?php settings_fields( 'cookiebot' ); ?>
				<?php do_settings_sections( 'cookiebot' ); ?>
				<div class="cb-settings__header">
					<h1 class="cb-main__page_title"><?php esc_html_e( 'Settings', 'cookiebot' ); ?></h1>
				</div>

				<div class="cb-settings__tabs">
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item active-item" id="general-settings">
						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Connect your account', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Enter the ID of your account to quickly connect it with the plugin.', 'cookiebot' ); ?>
								</p>

								<div class="cb-general__info__text">
									<a href="https://support.usercentrics.com/hc/en-us/articles/18097606499100-What-is-a-Settings-ID-and-where-can-I-find-it"
										target="_blank" class="cb-btn cb-link-btn" rel="noopener">
										<?php esc_html_e( 'How to find your Usercentrics Settings ID', 'cookiebot' ); ?>
									</a>
								</div>
								<div class="cb-general__info__text">
									<a href="https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it"
										target="_blank" class="cb-btn cb-link-btn" rel="noopener">
										<?php esc_html_e( 'How to find your Cookiebot CMP Domain Group ID', 'cookiebot' ); ?>
									</a>
								</div>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Settings ID or Domain Group ID', 'cookiebot' ); ?>
									</h3>
									<div class="cookiebot-cbid-container">
										<div class="cookiebot-cbid-input">
											<input placeholder="9, 14 or 36 characters"
												type="text" id="cookiebot-cbid" class="initial-cbid-setup"
												name="cookiebot-cbid"
												value="<?php echo esc_attr( $cbid ); ?>"/>
											<div class="cookiebot-cbid-check <?php echo $cbid ? 'check-pass' : ''; ?>"></div>
										</div>
										<?php submit_button( esc_html__( 'Connect account', 'cookiebot' ), 'disabled' ); ?>
										<div class="cookiebot-cbid-error hidden" style="color: #d63638; margin-top: 8px; font-size: 14px;">
											<?php esc_html_e( 'Invalid ID length. Please enter a Settings ID (9 or 14 characters) or Domain Group ID (36 characters).', 'cookiebot' ); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="cookiebot-ruleset-id-selector" class="cb-settings__config__item hidden">
							<div class="cb-settings__config__content">
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Let us know if your account is set for compliance with a single privacy law (e.g. GDPR) or multiple laws (e.g. GDPR and CCPA) based on user\'s location. The default is a single privacy law, so this is likely your setting unless modified.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Your current account setup:', 'cookiebot' ); ?>
									</h3>
									<label class="recommended-item">
										<input <?php checked( 'settings', $ruleset_id ); ?>
											type="radio"
											name="cookiebot-ruleset-id"
											value="settings"/>
										<?php esc_html_e( 'Compliance with one privacy law', 'cookiebot' ); ?>
									</label>
									<label>
										<input <?php checked( 'ruleset', $ruleset_id ); ?>
											type="radio"
											name="cookiebot-ruleset-id"
											value="ruleset"/>
										<?php esc_html_e( 'Compliance with multiple privacy laws (geolocation)', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				$legacy_settings = new Legacy_Settings();
				$legacy_settings->display();
				?>
			</form>
		</div>
	</div>
</div>
