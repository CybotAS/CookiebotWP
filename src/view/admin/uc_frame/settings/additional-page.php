<?php
/**
 * @var bool $is_ms
 * @var string $network_scrip_tag_cd_attr
 */
?>
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
