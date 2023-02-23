<?php
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Iab_Page;
use cybot\cookiebot\settings\pages\Multiple_Page;

/**
 * @var string $cbid
 * @var bool $is_ms
 * @var string $network_cbid
 * @var string $network_scrip_tag_uc_attr
 * @var string $network_scrip_tag_cd_attr
 * @var string $cookiebot_gdpr_url
 * @var string $cookiebot_logo
 * @var array $supported_languages
 * @var string $current_lang
 * @var bool $is_wp_consent_api_active
 * @var array $m_default
 * @var array $m
 * @var string $cookie_blocking_mode
 * @var bool $network_auto
 * @var string $add_language_gif_url
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
					<?php submit_button(); ?>
				</div>

				<div class="cb-settings__tabs">
					<div class="cb-settings__tabs__item <?php echo ! $active_tab || $active_tab === 'general-settings' ? 'active-item' : ''; ?>"
						 data-tab="general-settings">
						<?php esc_html_e( 'General Settings', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'additional-settings' ? 'active-item' : ''; ?>"
						 data-tab="additional-settings">
						<?php esc_html_e( 'Additional Settings', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'tag-manager' ? 'active-item' : ''; ?>"
						 data-tab="tag-manager">
						<?php esc_html_e( 'Google Tag Manager', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'consent-mode' ? 'active-item' : ''; ?>"
						 data-tab="consent-mode">
						<?php esc_html_e( 'Google Consent Mode', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'iab' ? 'active-item' : ''; ?>"
						 data-tab="iab">
						<?php esc_html_e( 'IAB', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'multiple-configurations' ? 'active-item' : ''; ?>"
						 data-tab="multiple-configurations">
						<?php esc_html_e( 'Multiple Configurations', 'cookiebot' ); ?>
					</div>
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item <?php echo ! $active_tab || $active_tab === 'general-settings' ? 'active-item' : ''; ?>"
						 id="general-settings">
						<?php if ( ! $cbid ) : ?>
						<div class="cb-general__new__account">
							<h2 class="cb-general__info__title"><?php esc_html_e( 'Do you not have an account yet?', 'cookiebot' ); ?></h2>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'Before you can get started with Cookiebot CMP for WordPress, you need to create an account on our website by clicking on "Create a new account" below. After you have signed up, you can configure your banner in the Cookiebot Manager and then place the Cookiebot Domain Group ID in the designated field below. You can find your ID in the Cookiebot Manager by navigating to "Settings" and "Your Scripts".', 'cookiebot' ); ?>
							</p>
							<div class="new-account-actions">
								<a href="https://manage.cookiebot.com/en/signup/?utm_source=wordpress&utm_medium=organic&utm_campaign=banner"
								   target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php esc_html_e( 'Create a new Account', 'cookiebot' ); ?>
								</a>
								<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174-Installing-Cookiebot-CMP-on-WordPress"
								   target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php esc_html_e( 'Get help with connecting your account', 'cookiebot' ); ?>
								</a>
							</div>
						</div>
						<?php endif; ?>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Connect your Domain Group:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'To connect your Domain Group, paste your Domain Group ID here. If you want to connect a second ID for other regions, you can do this under the "Multiple Configurations" tab.', 'cookiebot' ); ?>
								</p>
								<a href="https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it"
								   target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Read more on the Domain Group ID', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Add your Domain Group ID', 'cookiebot' ); ?>
									</h3>
									<input <?php echo ( $is_ms ) ? ' placeholder="' . esc_attr( $network_cbid ) . '"' : ''; ?>
											type="text" name="cookiebot-cbid"
											value="<?php echo esc_attr( $cbid ); ?>"
									/>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Language:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Select your main language here. Please make sure that the language selected has also been added in the Cookiebot™ Manager.', 'cookiebot' ); ?>
								</p>
								<a href="https://support.cookiebot.com/hc/en-us/articles/360003793394-How-to-set-the-language-of-the-consent-banner-"
								   target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Read more on how to add languages', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Select the language', 'cookiebot' ); ?>
									</h3>
									<select name="cookiebot-language" id="cookiebot-language">
										<option value="">
											<?php esc_html_e( 'Default (Autodetect)', 'cookiebot' ); ?>
										</option>
										<option value="_wp"<?php echo ( $current_lang === '_wp' ) ? ' selected' : ''; ?>>
											<?php
											esc_html_e(
												'Use WordPress Language',
												'cookiebot'
											);
											?>
										</option>
										<?php
										foreach ( $supported_languages as $lang_code => $lang_name ) {
											echo '<option value="' . esc_attr( $lang_code ) . '"' .
												( ( $current_lang === esc_attr( $lang_code ) ) ? ' selected' : '' ) .
												'>' . esc_html( $lang_name ) . '</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Cookie-blocking mode', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Select your cookie-blocking mode here. Auto cookie-blocking mode will automatically block all cookies (except for ‘strictly necessary’ cookies) until a user has given consent. Manual cookie-blocking mode requests manual adjustments to the cookie-setting scripts. Please find our implementation guides below:', 'cookiebot' ); ?>
								</p>
								<a href="https://support.cookiebot.com/hc/en-us/articles/360009074960-Automatic-cookie-blocking"
								   target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Guide to auto cookie-blocking', 'cookiebot' ); ?>
								</a>
								<a href="https://support.cookiebot.com/hc/en-us/articles/4405978132242-Manual-cookie-blocking"
								   target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Guide to manual cookie-blocking', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Select the cookie-blocking mode', 'cookiebot' ); ?>
									</h3>
									<label class="recommended-item">
										<input <?php checked( 'auto', $cookie_blocking_mode ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="auto"
											<?php echo $is_ms && $network_auto ? ' disabled' : ''; ?>/>
										<?php esc_html_e( 'Automatic cookie-blocking mode', 'cookiebot' ); ?>
									</label>
									<label>
										<input <?php checked( 'manual', $cookie_blocking_mode ); ?>
												type="radio"
												name="cookiebot-cookie-blocking-mode"
												value="manual"
											<?php echo $is_ms && $network_auto ? ' disabled' : ''; ?>/>
										<?php esc_html_e( 'Manual cookie-blocking mode', 'cookiebot' ); ?>
									</label>
									<?php if ( $is_ms && $network_auto ) { ?>
										<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>

						<?php
						$cv       = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
						$disabled = false;
						if ( $is_ms && $network_scrip_tag_uc_attr !== 'custom' ) {
							$disabled = true;
							$cv       = $network_scrip_tag_uc_attr;
						}
						?>

						<div class="cb-settings__config__item secondary__item" id="declaration-tag">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Cookiebot™ script tag', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Depending on cookie-blocking mode', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Add async or defer attribute to cookie declaration script tag', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="" <?php checked( '', $cv ); ?> />
										<?php esc_html_e( 'None', 'cookiebot' ); ?>
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="async" <?php checked( 'async', $cv ); ?> />
										async
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="defer" <?php checked( 'defer', $cv ); ?> />
										defer
									</label>
									<?php if ( $is_ms && $network_auto || $is_ms && $network_scrip_tag_uc_attr !== 'custom' ) { ?>
										<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item secondary__item" id="cookie-popup">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Hide cookie popup', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__note">
									<?php esc_html_e( 'Depending on cookie-blocking mode', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This checkbox will remove the cookie consent banner from your website. The declaration shortcode will still be available. If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label>
										<?php
										$disabled = false;
										if ( $is_ms && get_site_option( 'cookiebot-nooutput' ) || $is_ms && $network_auto ) {
											$disabled = true;
											if ( ! $network_auto ) {
												echo '<input type="checkbox" checked disabled />';
											} else {
												echo '<input type="checkbox" disabled />';
											}
										} else {
											?>
											<input type="checkbox" name="cookiebot-nooutput" value="1"
												<?php
												checked(
													1,
													get_option( 'cookiebot-nooutput', false )
												);
												?>
											/>
										<?php } ?>
										<?php esc_html_e( 'Hide the cookie popup banner', 'cookiebot' ); ?>
									</label>
									<?php if ( $is_ms && get_site_option( 'cookiebot-nooutput' ) || $is_ms && $network_auto ) { ?>
										<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>

					</div>

					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'additional-settings' ? 'active-item' : ''; ?>"
						 id="additional-settings">
						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Auto-update Cookiebot™ Plugin:', 'cookiebot' ); ?>
								</h3>
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
												get_option( 'cookiebot-autoupdate', false )
											);
											?>
										/>
										<div class="switcher"></div>
										<?php esc_html_e( 'Automatically update Cookiebot Plugin', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Cookiebot CMP in WP Admin:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This checkbox will disable Cookiebot CMP to act within the WordPress Admin area', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-nooutput-admin">
										<?php
										$disabled = false;
										if ( $is_ms && get_site_option( 'cookiebot-nooutput-admin' ) ) {
											echo '<input type="checkbox" checked disabled />';
											$disabled = true;
										} else {
											?>
											<input id="cookiebot-nooutput-admin" type="checkbox"
												   name="cookiebot-nooutput-admin" value="1"
												<?php
												checked(
													1,
													get_option( 'cookiebot-nooutput-admin', false )
												);
												?>
											/>
											<?php
										}
										?>
										<div class="switcher"></div>
										<?php esc_html_e( 'Disable Cookiebot CMP in the WordPress Admin area', 'cookiebot' ); ?>
									</label>
									<?php
									if ( $is_ms && get_site_option( 'cookiebot-nooutput-admin' ) ) {
										?>
										<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Cookiebot CMP on front-end while logged in:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This setting will enable Cookiebot CMP on the front-end while you\'re logged in.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="cookiebot-output-logged-in">
										<?php
										$disabled = false;
										if ( $is_ms && get_site_option( 'cookiebot-output-logged-in' ) ) {
											echo '<input type="checkbox" checked disabled />';
											$disabled = true;
										} else {
											?>
											<input id="cookiebot-output-logged-in" type="checkbox"
												   name="cookiebot-output-logged-in" value="1"
												<?php
												checked(
													1,
													get_option( 'cookiebot-output-logged-in', false )
												);
												?>
											/>
											<?php
										}
										?>
										<div class="switcher"></div>
										<?php esc_html_e( 'Render Cookiebot CMP on front-end while logged in', 'cookiebot' ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Cookie declaration script tag:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'If you implemented the declaration on your page through our widget in WordPress, you can choose here how the script should be loaded.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Select the cookie declaration script loading setting', 'cookiebot' ); ?>
									</h3>
									<?php
									$cv       = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
									$disabled = false;
									if ( $is_ms && $network_scrip_tag_cd_attr !== 'custom' ) {
										$disabled = true;
										$cv       = $network_scrip_tag_cd_attr;
									}
									?>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="" <?php checked( '', $cv ); ?>/>
										<?php esc_html_e( 'None', 'cookiebot' ); ?>
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="async" <?php checked( 'async', $cv ); ?>/>
										async
									</label>
									<label>
										<input
												type="radio"
												name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
												value="defer" <?php checked( 'defer', $cv ); ?>/>
										defer
									</label>
									<?php if ( $disabled ) { ?>
										<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Ignore scripts in queue from Cookiebot CMP scan:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'List scripts source URL (one per line) from the queue to ignore Cookiebot CMP scan. Partial source URL will also work, e.g. wp-content/plugins/woocommerce will block every WooCommerce script.', 'cookiebot' ); ?>
								</p>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This feature only works for scripts loaded via wp_enqueue_script. Manually added scripts must be manually edited.', 'cookiebot' ); ?>
								</p>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Script source URL:', 'cookiebot' ); ?>
									</h3>
									<textarea
											name="cookiebot-ignore-scripts"
											rows="4"
											cols="50"
											placeholder="<?php esc_attr_e( 'Add script source URL, one per line', 'cookiebot' ); ?>"
									><?php echo esc_html( get_option( 'cookiebot-ignore-scripts', false ) ); ?></textarea>
								</div>
							</div>
						</div>

					</div>

					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'tag-manager' ? 'active-item' : ''; ?>"
						 id="tag-manager">
						<?php $gtm_settings = new Gtm_Page(); ?>
						<?php $gtm_settings->display(); ?>
					</div>

					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'consent-mode' ? 'active-item' : ''; ?>"
						 id="consent-mode">
						<div class="cb-general__consent__mode">
							<h2 class="cb-general__info__title">
								<?php esc_html_e( 'What is Google Consent Mode and why should you enable it?', 'cookiebot' ); ?>
							</h2>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'Google Consent Mode is a way for your website to measure conversions and get analytics insights while being fully GDPR-compliant when using services like Google Analytics, Google Tag Manager (GTM) and Google Ads.', 'cookiebot' ); ?>
							</p>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'Cookiebot consent managment platform (CMP) and Google Consent Mode integrate seamlessly to offer you plug-and-play compliance and streamlined use of all Google\'s services in one easy solution.', 'cookiebot' ); ?>
							</p>
							<a class="cb-btn cb-link-btn" target="_blank" rel="noopener"
							   href="https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode">
								<?php esc_html_e( 'Read more about Cookiebot CMP and Google Consent Mode', 'cookiebot' ); ?>
							</a>
						</div>

						<?php $gcm_enabled_option = get_option( 'cookiebot-gcm' ); ?>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Google Consent Mode:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'Enable Google Consent Mode with default settings on your WordPress page.', 'cookiebot' ); ?>
								</p>
								<a class="cb-btn cb-link-btn" target="_blank" rel="noopener"
								   href="https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode">
									<?php esc_html_e( 'Read more', 'cookiebot' ); ?>
								</a>
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

						<?php $gcm_url_passthrough_option = get_option( 'cookiebot-gcm-url-passthrough', 1 ); ?>

						<div class="cb-settings__config__item"<?php echo ( (string) $gcm_enabled_option === '1' ) ? '' : ' style="display: none"'; ?>>
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'URL passthrough:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'This feature will allow you to pass data between pages when not able to use cookies without/prior consent.', 'cookiebot' ); ?>
								</p>
								<a class="cb-btn cb-link-btn" target="_blank" rel="noopener"
								   href="https://developers.google.com/tag-platform/devguides/consent#passing_ad_click_client_id_and_session_id_information_in_urls">
									<?php esc_html_e( 'Read more', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<label class="switch-checkbox" for="gcm-url-pasthrough">
										<input
												type="checkbox"
												name="cookiebot-gcm-url-passthrough"
												id="gcm-url-pasthrough"
												value="1" <?php checked( 1, $gcm_url_passthrough_option ); ?>>
										<div class="switcher"></div>
										<?php esc_html_e( 'URL passthrough', 'cookiebot' ); ?>
										<?php echo ( $gcm_url_passthrough_option === '1' ) ? 'enabled' : 'disabled'; ?>
									</label>
								</div>
							</div>
						</div>

					</div>

					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'iab' ? 'active-item' : ''; ?>"
						 id="iab">
						<?php $iab_settings = new Iab_Page(); ?>
						<?php $iab_settings->display(); ?>
					</div>

					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'multiple-configurations' ? 'active-item' : ''; ?>"
						 id="multiple-configurations">
						<?php $multiple_settings = new Multiple_Page(); ?>
						<?php $multiple_settings->display(); ?>
					</div>

				</div>
			</form>
		</div>
	</div>
</div>
