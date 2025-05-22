<?php
/**
 * @var string $cbid
 * @var bool $is_ms
 * @var string $network_cbid
 * @var bool $network_cbid_override
 * @var string $ruleset_id
 * @var bool $cookiebot_iab
 * @var string $network_scrip_tag_uc_attr
 * @var string $cookie_blocking_mode
 * @var bool $network_auto
 */

use cybot\cookiebot\lib\Cookiebot_WP;
use function cybot\cookiebot\lib\include_view;

// Check if user was onboarded via signup
$was_onboarded = Cookiebot_WP::was_onboarded_via_signup() && ! empty( Cookiebot_WP::get_auth_token() );

?>
<?php include_view( 'admin/common/templates/extra/cbid-disconnect-alert.php' ); ?>
<?php
if ( ! empty( $network_cbid ) ) {
	include_view( 'admin/common/templates/extra/subsite-disconnect-alert.php' );
}
?>

<?php if ( $was_onboarded ) : ?>
	<div class="cb-settings__notabs">
	</div>
<?php endif; ?>

<div class="cb-settings__config__item cb-settings__config__cbid">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Disconnecting your banner setup?', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Click', 'cookiebot' ); ?>
			<strong><?php esc_html_e( 'Disconnect account', 'cookiebot' ); ?></strong>
			<?php esc_html_e( 'to remove this cookie banner from your site. You can add it back at any time using the same ID or a new one.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Since you signed up through the plugin, disconnecting will remove your login access and clear your plugin data. But don\'t worry, you can still use your ID by copying and pasting it from the Admin Interface.', 'cookiebot' ); ?>
		</p>

		<?php if ( $was_onboarded ) : ?>
			<p class="cb-general__info__text">
				<?php esc_html_e( 'Need more control over your banner?', 'cookiebot' ); ?>
				<a href="https://admin.usercentrics.eu/#/v3/configuration/setup?settingsId=<?php echo esc_attr( $cbid ); ?>"
					target="_blank" class="cb-btn cb-link-btn" rel="noopener">
					<?php esc_html_e( 'Go to full banner settings', 'cookiebot' ); ?>
				</a>
			</p>
		<?php else : ?>
			<a href="https://support.usercentrics.com/hc/en-us/articles/18097606499100-What-is-a-Settings-ID-and-where-can-I-find-it"
				target="_blank" class="cb-btn cb-link-btn" rel="noopener">
				<?php esc_html_e( 'Where to find settings ID', 'cookiebot' ); ?>
			</a>

		<?php endif; ?>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle">
				<?php esc_html_e( 'Your settings ID', 'cookiebot' ); ?>
			</h3>
			<div class="cookiebot-cbid-container">
				<div class="cookiebot-cbid-input">
					<input
						<?php
						echo ( $is_ms ) ?
							' placeholder="' . esc_attr( $network_cbid ) . '" data-network="' . esc_attr( $network_cbid ) . '"' :
							'';
						?>
							type="text" id="cookiebot-cbid" class="cbid-active" name="cookiebot-cbid"
							value="<?php echo esc_attr( $cbid ); ?>"/>
					<div class="cookiebot-cbid-check"></div>
				</div>
				<div id="cookiebot-cbid-reset-dialog"
					class="cb-btn cb-main-btn
					<?php
					if ( ! empty( $network_cbid ) && ! $network_cbid_override ) {
						echo 'hidden';
					}
					?>
					"><?php esc_html_e( 'Disconnect account', 'cookiebot' ); ?></div>
				<?php if ( $is_ms ) : ?>
					<div id="cookiebot-cbid-network-dialog"
						class="cb-btn cb-white-btn
						<?php
						if ( ! empty( $network_cbid ) && $network_cbid_override ) {
							echo 'hidden';
						}
						?>
						"><?php esc_html_e( 'Using network account', 'cookiebot' ); ?></div>
					<?php submit_button( esc_html__( 'Connect account', 'cookiebot' ), 'hidden' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $network_cbid ) ) : ?>
				<div id="cb-network-id-override">
					<label class="switch-checkbox" for="cookiebot-cbid-override">
						<input class="
					<?php
					if ( ! $network_cbid_override ) {
						echo 'cb-no-network';
					}
					?>
					"
								type="checkbox" name="cookiebot-cbid-override" id="cookiebot-cbid-override" value="1"
							<?php checked( 1, $network_cbid_override ); ?>>
						<div class="switcher"></div>
						<?php esc_html_e( 'Do not use Network Settings ID', 'cookiebot' ); ?>
					</label>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ( ! $was_onboarded ) : ?>
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
<?php endif; ?>

<?php if ( ! $was_onboarded ) : ?>
	<div class="cb-settings__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle">
				<?php esc_html_e( 'TCF integration', 'cookiebot' ); ?>
			</h3>
			<p class="cb-general__info__text">
				<?php esc_html_e( 'Enable the integration with the latest version of IAB TCF.', 'cookiebot' ); ?>
			</p>
		</div>
		<div class="cb-settings__config__data">
			<div class="cb-settings__config__data__inner">
				<label class="switch-checkbox" for="cookiebot-iab">
					<input type="checkbox" name="cookiebot-iab" id="cookiebot-iab" value="1"
						<?php checked( 1, $cookiebot_iab ); ?>>
					<div class="switcher"></div>
					<?php esc_html_e( 'IAB TCF integration', 'cookiebot' ); ?>
				</label>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ( ! $was_onboarded ) : ?>
	<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Cookie-blocking', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Choose the type of your cookie-blocking mode. Select automatic to automatically block all cookies except those strictly necessary to use before user gives consent. Manual mode lets you adjust your cookie settings within your website’s HTML.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.usercentrics.com/hc/en-us/articles/17332104757148-Automatic-Data-Processing-Service-blocking"
			target="_blank" class="cb-btn cb-link-btn" rel="noopener">
			<?php esc_html_e( 'Learn more', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle">
				<?php esc_html_e( 'Select cookie-blocking mode', 'cookiebot' ); ?>
			</h3>
			<label class="recommended-item">
				<input <?php checked( 'auto', $cookie_blocking_mode ); ?>
						type="radio"
						name="cookiebot-cookie-blocking-mode"
						value="auto"
					<?php echo $is_ms && $network_auto ? ' disabled' : ''; ?>/>
				<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
				<span class="recommended-tag"><?php esc_html_e( 'Recommended', 'cookiebot' ); ?></span>
			</label>
			<label>
				<input <?php checked( 'manual', $cookie_blocking_mode ); ?>
						type="radio"
						name="cookiebot-cookie-blocking-mode"
						value="manual"
					<?php echo $is_ms && $network_auto ? ' disabled' : ''; ?>/>
				<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
			</label>
			<?php if ( $is_ms && $network_auto ) { ?>
				<p class="cb-general__info__note"><?php esc_html_e( 'Disabled by active setting in Network Settings', 'cookiebot' ); ?></p>
			<?php } ?>
		</div>
	</div>
	</div>
<?php endif; ?>

<?php
$cv       = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
$disabled = false;
if ( $is_ms && $network_scrip_tag_uc_attr !== 'custom' ) {
	$disabled = true;
	$cv       = $network_scrip_tag_uc_attr;
}
$auto_disabled = $cookie_blocking_mode === 'auto' ? ' disabled__item' : '';
?>

<div class="cb-settings__config__item secondary__item<?php echo esc_attr( $auto_disabled ); ?>" id="cookie-popup">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Hide cookie popup', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This feature is only available when using Manual Blocking', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'This will remove the cookie consent banner from your website. The cookie declaration shortcode will still be available if you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.', 'cookiebot' ); ?>
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
