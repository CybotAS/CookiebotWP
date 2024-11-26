<?php
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Gcm_Page;
use cybot\cookiebot\settings\pages\Iab_Page;
use cybot\cookiebot\settings\pages\Multiple_Page;

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
					<h1 class="cb-main__page_title"><?php esc_html_e( 'Initial Settings', 'cookiebot' ); ?></h1>
					<?php submit_button(); ?>
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item active-item"
						id="general-settings">
						<div class="cb-general__new__account">
							<h2 class="cb-general__info__title"><?php esc_html_e( 'Do you not have an account yet?', 'cookiebot' ); ?></h2>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'Demo content on creating a new account.', 'cookiebot' ); ?>
							</p>
							<div class="new-account-actions">
								<a href="https://admin.cookiebot.com/signup/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner"
									target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php esc_html_e( 'Create a new Account', 'cookiebot' ); ?>
								</a>
								<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174-Installing-Cookiebot-CMP-on-WordPress"
									target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php esc_html_e( 'Get help with connecting your account', 'cookiebot' ); ?>
								</a>
							</div>
						</div>

						<div class="cb-settings__config__item">
							<div class="cb-settings__config__content">
								<h3 class="cb-settings__config__subtitle">
									<?php esc_html_e( 'Connect your ID:', 'cookiebot' ); ?>
								</h3>
								<p class="cb-general__info__text">
									<?php esc_html_e( 'To connect your account, paste your settings ID here. If you want to connect a geolocation ID for other regions, you can add the ruleset ID.', 'cookiebot' ); ?>
								</p>
								<a href="https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it"
									target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php esc_html_e( 'Read more on the settings ID', 'cookiebot' ); ?>
								</a>
							</div>
							<div class="cb-settings__config__data">
								<div class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Add your ID', 'cookiebot' ); ?>
									</h3>
									<div class="cookiebot-cbid-container">
										<input <?php echo ( $is_ms ) ? ' placeholder="' . esc_attr( $network_cbid ) . '"' : ''; ?>
											type="text" id="cookiebot-cbid" name="cookiebot-cbid"
											value="<?php echo esc_attr( $cbid ); ?>"/>
										<div class="cookiebot-cbid-check <?php echo $cbid ? 'check-pass' : ''; ?>"></div>
									</div>
								</div>
								<div id="cookiebot-ruleset-id-selector" class="cb-settings__config__data__inner">
									<h3 class="cb-settings__data__subtitle">
										<?php esc_html_e( 'Settings or Ruleset', 'cookiebot' ); ?>
									</h3>
									<label class="recommended-item">
										<input <?php checked( 'settings', $ruleset_id ); ?>
												type="radio"
												name="cookiebot-ruleset-id"
												value="settings"/>
										<?php esc_html_e( 'Settings ID', 'cookiebot' ); ?>
									</label>
									<label>
										<input <?php checked( 'ruleset', $ruleset_id ); ?>
												type="radio"
												name="cookiebot-ruleset-id"
												value="ruleset"/>
										<?php esc_html_e( 'Ruleset ID', 'cookiebot' ); ?>
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
