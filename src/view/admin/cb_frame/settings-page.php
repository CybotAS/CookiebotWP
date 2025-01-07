<?php

use cybot\cookiebot\settings\pages\Additional_Page;
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
						<?php $additional_settings = new Additional_Page(); ?>
						<?php $additional_settings->display(); ?>
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
