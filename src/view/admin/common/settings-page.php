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

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item active-item"
						 id="general-settings">
						<div class="cb-general__new__account">
							<h2 class="cb-general__info__title"><?php esc_html_e( 'Do you not have an account yet?', 'cookiebot' ); ?></h2>
							<p class="cb-general__info__text">
								<?php esc_html_e( 'Before you can get started with Cookiebot CMP for WordPress, you need to create an account on our website by clicking on "Create a new account" below. After you have signed up, you can configure your banner in the Cookiebot Manager and then place the Cookiebot Domain Group ID in the designated field below. You can find your ID in the Cookiebot Manager by navigating to "Settings" and "Your Scripts".', 'cookiebot' ); ?>
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
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
