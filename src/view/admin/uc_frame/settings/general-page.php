<?php
/**
 * @var string $cbid
 * @var bool $is_ms
 * @var string $network_cbid
 * @var string $ruleset_id
 * @var bool $cookiebot_iab
 * @var string $network_scrip_tag_uc_attr
 * @var string $cookie_blocking_mode
 * @var bool $network_auto
 */
?>
<div class="cb-cbid-alert__msg hidden">
	<h3 class="cb-settings__config__subtitle">
		<?php esc_html_e( 'Are you sure?', 'cookiebot' ); ?>
	</h3>
	<p class="cb-general__info__text">
		<?php esc_html_e( 'You will need to add a new ID before updating other settings', 'cookiebot' ); ?>
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
<div class="cb-settings__config__item cb-settings__config__cbid">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Connect your account:', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'To connect your account, paste your settings ID here. If you want to connect a geolocation ruleset for other regions, you can add your ruleset id', 'cookiebot' ); ?>
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
				<div class="cookiebot-cbid-input">
				<input <?php echo ( $is_ms ) ? ' placeholder="' . esc_attr( $network_cbid ) . '"' : ''; ?>
					type="text" id="cookiebot-cbid" class="cbid-active" name="cookiebot-cbid"
					value="<?php echo esc_attr( $cbid ); ?>"/>
				<div class="cookiebot-cbid-check"></div>
				</div>
				<div id="cookiebot-cbid-reset-dialog" class="cb-btn cb-main-btn"><?php esc_html_e( 'Disconnect account', 'cookiebot' ); ?></div>
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

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'TCF enabled on Usercentrics admin:', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Enable this if the option is also enabled on Usercentrics admin dashboard', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-iab">
				<input type="checkbox" name="cookiebot-iab" id="cookiebot-iab" value="1"
					<?php checked( 1, $cookiebot_iab ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'IAB TCF integration enabled', 'cookiebot' ); ?>
			</label>
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
				<span class="recommended-tag"><?php esc_html_e( 'Recommended', 'cookiebot' ); ?></span>
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
$auto_disabled = $cookie_blocking_mode === 'auto' ? ' disabled__item' : '';
?>

<div class="cb-settings__config__item secondary__item<?php echo esc_attr( $auto_disabled ); ?>" id="cookie-popup">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Hide cookie popup', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'This checkbox will remove the cookie consent banner from your website. The declaration shortcode will still be available. If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This feature is only available when using Manual Blocking', 'cookiebot' ); ?>
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
