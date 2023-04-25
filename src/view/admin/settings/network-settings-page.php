<?php
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
/**
 * @var string $logo
 * @var string $cookiebot_gdpr_url
 * @var string $cbm
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
						<?php if ( ! esc_attr( get_site_option( 'cookiebot-cbid', '' ) ) ) : ?>
							<div class="cb-general__new__account">
								<h2 class="cb-general__info__title"><?php esc_html_e( 'Do you not have an account yet?', 'cookiebot' ); ?></h2>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'To use Cookiebot for WordPress you need to visit our website and sign-up. After you have signed up, you can configure your banner and then place the Cookiebot Domain Group ID below. Navigate to Settings and to "Your Scripts" to find your ID.', 'cookiebot' ); ?>
								</p>
								<div class="new-account-actions">
									<a href="https://manage.cookiebot.com/en/signup/?utm_source=wordpress&utm_medium=organic&utm_campaign=banner" target="_blank" rel="noopener" class="cb-btn cb-main-btn"><?php esc_html_e( 'Create a new Account', 'cookiebot' ); ?></a>
									<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174-Installing-Cookiebot-CMP-on-WordPress" target="_blank" rel="noopener" class="cb-btn cb-link-btn"><?php esc_html_e( 'Get help with connecting your account', 'cookiebot' ); ?></a>
								</div>
							</div>
						<?php endif; ?>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Network Domain Group:', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'If added this will be the default Cookiebot ID for all subsites. Subsites are able to override the Cookiebot ID.', 'cookiebot' ); ?>
								</p>
								<a href="https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it" target="_blank" rel="noopener" class="cb-btn cb-link-btn"><?php esc_html_e( 'Read more on the Domain Group ID', 'cookiebot' ); ?></a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Add your Domain Group ID', 'cookiebot' ); ?></h3>
									<input
											type="text"
											name="cookiebot-cbid"
											value="<?php echo esc_attr( get_site_option( 'cookiebot-cbid', '' ) ); ?>"
									/>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Cookie-blocking mode', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Select your cookie-blocking mode here. Auto cookie-blocking mode will automatically block all cookies (except for ‘strictly necessary’ cookies) until a user has given consent. Manual cookie-blocking mode requests manual adjustments to the cookie-setting scripts. Please find our implementation guides below:', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Select the Cookie-blocking mode', 'cookiebot' ); ?></h3>
									<label class="recommended-item">
										<input <?php checked( 'auto', $cbm ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="auto"
										/>
										<?php esc_html_e( 'Automatic cookie-blocking mode', 'cookiebot' ); ?>
									</label>
									<label>
										<input <?php checked( 'manual', $cbm ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="manual"
										/>
										<?php esc_html_e( 'Manual cookie-blocking mode', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item secondary__item" id="declaration-tag">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Cookiebot™ script tag', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Depending on Cookie-blocking mode', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Setting will apply for all subsites. Subsites will not be able to override.', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Add async or defer attribute to Cookie banner script tag. Default: Choose per subsite', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<?php
									$cv = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
									?>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"
												value="" <?php checked( '', $cv ); ?> />
										<?php esc_html_e( 'None', 'cookiebot' ); ?>
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"
												value="async" <?php checked( 'async', $cv ); ?> />
										async
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"
												value="defer" <?php checked( 'defer', $cv ); ?> />
										defer
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"
												value="custom" <?php checked( 'custom', $cv ); ?> />
										<?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Cookiebot declaration script tag', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Setting will apply for all subsites. Subsites will not be able to override.', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Add async or defer attribute to Cookie declaration script tag. Default: Choose per subsite', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<?php
									$cv = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
									?>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"
												value="" <?php checked( '', $cv ); ?> />
										<?php esc_html_e( 'None', 'cookiebot' ); ?>
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"
												value="async" <?php checked( 'async', $cv ); ?> />
										async
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"
												value="defer" <?php checked( 'defer', $cv ); ?> />
										defer
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"
												value="custom" <?php checked( 'custom', $cv ); ?> />
										<?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Auto-update Cookiebot™ Plugin:', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Automatically update your Cookiebot™ plugin when new releases becomes available.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-autoupdate">
										<input id="cookiebot-autoupdate" type="checkbox" name="cookiebot-autoupdate" value="1"
											<?php
											checked(
												1,
												get_site_option( 'cookiebot-autoupdate' )
											);
											?>
										/>
										<div class="switcher"></div>
										<?php esc_html_e( 'Automatically update Cookiebot Plugin', 'cookiebot' ); ?>
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
									<?php esc_html_e( 'This checkbox will remove the cookie consent banner from your website. The declaration shortcode will still be available. If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-nooutput">
										<input id="cookiebot-nooutput" type="checkbox" name="cookiebot-nooutput" value="1"
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

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Cookiebot CMP in WP Admin:', 'cookiebot' ); ?></h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Setting will apply for all subsites. Subsites will not be able to override.', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This checkbox will disable Cookiebot to act within the WordPress Admin area', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-nooutput-admin">
										<input id="cookiebot-nooutput-admin" type="checkbox" name="cookiebot-nooutput-admin" value="1"
											<?php
											checked(
												1,
												get_site_option( 'cookiebot-nooutput-admin' )
											);
											?>
										/>
										<div class="switcher"></div>
										<?php esc_html_e( 'Disable Cookiebot CMP in the WordPress Admin area', 'cookiebot' ); ?>
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
