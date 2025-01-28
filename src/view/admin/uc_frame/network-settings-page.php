<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

/**
 * @var string $logo
 * @var string $cookiebot_gdpr_url
 * @var string $cbm
 * @var string $ruleset_id
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'settings' ); ?>
		<div class="cb-main__content">
			<form method="post" action="edit.php?action=cookiebot_network_settings">
				<?php wp_nonce_field( 'cookiebot-network-settings' ); ?>
				<div class="cb-settings__header">
					<h1 class="cb-main__page_title"><?php esc_html_e( 'Network Settings', 'cookiebot' ); ?></h1>
					<?php submit_button(); ?>
				</div>

				<div class="cb-settings__tabs">
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item active-item">
						<div class="cb-cbid-alert__msg hidden">
							<h3 class="cb-settings__config__subtitle">
								<?php esc_html_e( 'Are you sure?', 'cookiebot' ); ?>
							</h3>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'You will need to add a new ID before updating other network settings. If any subsite is using its own account disconnecting this account won’t affect it.', 'cookiebot' ); ?>
							</p>
							<div class="new-account-actions">
								<div id="cookiebot-cbid-cancel" class="cb-btn cb-white-btn">
									<?php esc_html_e( 'Cancel', 'cookiebot' ); ?>
								</div>
								<div id="cookiebot-cbid-reset" class="cb-btn cb-main-btn">
									<?php esc_html_e( 'Disconnect account', 'cookiebot' ); ?>
								</div>
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
								<p class="cb-general__info__text">
									<?php esc_html_e( 'If added this will be the default account for all subsites. Subsites are able to override this and use their own account.', 'cookiebot' ); ?>
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
											<input type="text" id="cookiebot-cbid" class="cbid-active"
													name="cookiebot-cbid"
													value="<?php echo esc_attr( get_site_option( 'cookiebot-cbid', '' ) ); ?>"/>
											<div class="cookiebot-cbid-check"></div>
										</div>
										<div id="cookiebot-cbid-reset-dialog"
											class="cb-btn cb-main-btn"><?php esc_html_e( 'Disconnect account', 'cookiebot' ); ?></div>
									</div>
								</div>
							</div>
						</div>
						<div id="cookiebot-ruleset-id-selector" class="cb-settings__config__item">
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

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Cookie-blocking', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Choose the type of your cookie-blocking mode. Select automatic to automatically block all cookies except those strictly necessary to use before user gives consent. Manual mode lets you adjust your cookie settings within your website’s HTML.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Select cookie-blocking mode', 'cookiebot' ); ?></h3>
									<label class="recommended-item">
										<input <?php checked( 'auto', $cbm ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="auto"
										/>
										<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
										<span class="recommended-tag"><?php esc_html_e( 'Recommended', 'cookiebot' ); ?></span>
									</label>
									<label>
										<input <?php checked( 'manual', $cbm ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="manual"
										/>
										<?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Automatic updates', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Enable automatic updates whenever we release a new version of the plugin.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-autoupdate">
										<input id="cookiebot-autoupdate" type="checkbox" name="cookiebot-autoupdate"
												value="1"
											<?php
											checked(
												1,
												get_site_option( 'cookiebot-autoupdate' )
											);
											?>
										/>
										<div class="switcher"></div>
										<?php esc_html_e( 'Automatically update to new version', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item" id="cookie-popup">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Hide cookie popup', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Setting will apply for all subsites. Subsites will not be able to override.', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This will remove the cookie consent banner from your website. The cookie declaration shortcode will still be available if you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-nooutput">
										<input id="cookiebot-nooutput" type="checkbox" name="cookiebot-nooutput"
												value="1"
											<?php
											checked(
												1,
												get_site_option( 'cookiebot-nooutput', false )
											);
											?>
										/>
										<div class="switcher"></div>
										<?php esc_html_e( 'Hide the cookie popup banner', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
