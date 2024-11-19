<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\General_Page;
use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Gcm_Page;
use cybot\cookiebot\settings\pages\Iab_Page;
use cybot\cookiebot\settings\pages\Multiple_Page;

/**
 * @var string $cbid
 * @var bool $cbid_frame
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
				<div class="cb-vendor-alert__msg hidden"><?php esc_html_e( 'Select at least one vendor on TCF tab', 'cookiebot' ); ?></div>
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
						<?php esc_html_e( 'TCF', 'cookiebot' ); ?>
					</div>
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'multiple-configurations' ? 'active-item' : ''; ?>"
						 data-tab="multiple-configurations">
						<?php esc_html_e( 'Multiple Configurations', 'cookiebot' ); ?>
					</div>
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item <?php echo ! $active_tab || $active_tab === 'general-settings' ? 'active-item' : ''; ?>"
						 id="general-settings">
						<?php $gtm_settings = new General_Page(); ?>
						<?php $gtm_settings->display(); ?>
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
						<?php $gcm_settings = new Gcm_Page(); ?>
						<?php $gcm_settings->display(); ?>
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
