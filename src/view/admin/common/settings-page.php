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

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item active-item"
						id="general-settings">
						<div class="cb-general__new__account">
							<h2 class="cb-general__info__title"><?php esc_html_e( 'New to our solutions? Create your account. ', 'cookiebot' ); ?></h2>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'If you’re new to our solutions, create an account first to obtain your settings ID.', 'cookiebot' ); ?>
							</p>
							<div class="new-account-actions">
								<a href="https://account.usercentrics.eu/?trial=standard&uc_subscription_type=web&pricing_plan=FreeExtended/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner"
									target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php esc_html_e( 'Create your account', 'cookiebot' ); ?>
								</a>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Connect your account', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Enter your settings ID to quickly link your account with the plugin.', 'cookiebot' ); ?>
								</p>
								<a href="https://support.usercentrics.com/hc/en-us/articles/18097606499100-What-is-a-Settings-ID-and-where-can-I-find-it"
									target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Where to find settings ID', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Your settings ID', 'cookiebot' ); ?>
									</h3>
									<div class="cookiebot-cbid-container">
										<div class="cookiebot-cbid-input">
											<input placeholder="XxxXXXxx"
													type="text" id="cookiebot-cbid" class="initial-cbid-setup"
													name="cookiebot-cbid"
													value="<?php echo esc_attr( $cbid ); ?>"/>
											<div class="cookiebot-cbid-check <?php echo $cbid ? 'check-pass' : ''; ?>"></div>
										</div>
										<?php submit_button( esc_html__( 'Connect account', 'cookiebot' ), 'disabled' ); ?>
									</div>
								</div>
							</div>
						</div>
						<div id="cookiebot-ruleset-id-selector" class="cb-settings__config__item hidden">
							<div class="cb-settings__config__content">
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Let us know if your account is set for compliance with a single privacy law (e.g. GDPR) or multiple laws (e.g. GDPR and CCPA) based on user’s location. The default is a single privacy law, so this is likely your setting unless modified.', 'cookiebot' ); ?>
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
