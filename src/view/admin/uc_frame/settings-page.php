<?php

use cybot\cookiebot\settings\pages\Additional_Page;
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\General_Page;
use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Gcm_Page;
use cybot\cookiebot\settings\pages\Embeddings_Page;

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
					<?php submit_button( __( 'Save changes', 'cookiebot' ) ); ?>
				</div>

				<div class="cb-settings__tabs cb-settings__tabs--uc">
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
					<div class="cb-settings__tabs__item <?php echo $active_tab === 'embeddings' ? 'active-item' : ''; ?>"
						data-tab="embeddings">
						<?php esc_html_e( 'Privacy Policy Sync', 'cookiebot' ); ?>
					</div>
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item <?php echo ! $active_tab || $active_tab === 'general-settings' ? 'active-item' : ''; ?>"
						id="general-settings">
						<?php $general_settings = new General_Page(); ?>
						<?php $general_settings->display(); ?>
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
					<div class="cb-settings__tabs__content--item <?php echo $active_tab === 'embeddings' ? 'active-item' : ''; ?>"
						id="embeddings">
						<?php $embedding_settings = new Embeddings_Page(); ?>
						<?php $embedding_settings->display(); ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
