<?php
/**
 * @var string $cbid
 * @var bool $cbid_frame
 * @var bool $is_ms
 * @var string $network_cbid
 * @var bool $network_cbid_override
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

use function cybot\cookiebot\lib\include_view;

?>
<?php include_view( 'admin/common/templates/extra/cbid-disconnect-alert.php' ); ?>
<?php
if ( ! empty( $network_cbid ) ) {
	include_view( 'admin/common/templates/extra/subsite-disconnect-alert.php' );
}
?>
<div class="cb-settings__config__item cb-settings__config__cbid">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Connect your Domain Group', 'cookiebot' ); ?>
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
				<div class="cookiebot-cbid-error-container">
					<div class="cookiebot-cbid-error hidden" style="color: #d63638; margin-top: 8px; font-size: 14px;">
						<?php esc_html_e( 'Invalid ID length. Please enter a Settings ID (14 characters) or Domain Group ID (36 characters).', 'cookiebot' ); ?>
					</div>
				</div>
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

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Language:', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text"><?php esc_html_e( 'Select your main language here.', 'cookiebot' ); ?></p>
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
		<p class="cb-general__info__text">
			<?php esc_html_e( 'If enabled, Cookiebot™ will use the current location to set the banner and cookie declaration language.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'Please make sure that all languages in use have been added in the Cookiebot™ Manager.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note"><?php esc_html_e( 'This feature disables the main language selector.', 'cookiebot' ); ?></p>
		<p class="cb-general__info__note"><?php esc_html_e( 'If you have already set a language in the cookie declaration shortcode, this feature will not change it.', 'cookiebot' ); ?></p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360003793394-How-to-set-the-language-of-the-consent-banner-"
			target="_blank" class="cb-btn cb-link-btn" rel="noopener">
			<?php esc_html_e( 'Read more on how to add languages', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-front-language">
				<input id="cookiebot-front-language" type="checkbox" name="cookiebot-front-language" value="1"
					<?php
					checked(
						1,
						get_option( 'cookiebot-front-language', false )
					);
					?>
				/>
				<div class="switcher"></div>
				<?php esc_html_e( 'Use website location to set language', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>


<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Cookie-blocking', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Choose the type of your cookie-blocking mode. Select automatic to automatically block all cookies except those strictly necessary to use before user gives consent. Manual mode lets you adjust your cookie settings within your website’s HTML.', 'cookiebot' ); ?>
		</p>
		<div>
			<a href="https://support.cookiebot.com/hc/en-us/articles/360009074960-Automatic-cookie-blocking"
				target="_blank" class="cb-btn cb-link-btn" rel="noopener">
				<?php esc_html_e( 'Guide to auto cookie-blocking', 'cookiebot' ); ?>
			</a>
		</div>
		<div>
			<a href="https://support.cookiebot.com/hc/en-us/articles/4405978132242-Manual-cookie-blocking"
				target="_blank" class="cb-btn cb-link-btn" rel="noopener">
				<?php esc_html_e( 'Guide to manual cookie-blocking', 'cookiebot' ); ?>
			</a>
		</div>
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
					/>
				<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
				<span class="recommended-tag"><?php esc_html_e( 'Recommended', 'cookiebot' ); ?></span>
			</label>
			<label>
				<input <?php checked( 'manual', $cookie_blocking_mode ); ?>
						type="radio"
						name="cookiebot-cookie-blocking-mode"
						value="manual"
					/>
				<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
			</label>
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

<div class="cb-settings__config__item secondary__item<?php echo esc_attr( $auto_disabled ); ?>" id="declaration-tag">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Cookiebot™ script tag', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Add async or defer attribute to cookie declaration script tag', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This feature is only available when using Manual Blocking', 'cookiebot' ); ?>
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
