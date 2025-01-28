<?php
/**
 * @var bool $is_ms
 * @var string $network_scrip_tag_cd_attr
 */
?>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Automatic updates', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Enable automatic updates whenever we release a new version of the plugin.', 'cookiebot' ); ?>
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
				<?php esc_html_e( 'Automatically update to new version', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>

<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Show the banner while logged in', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'You can choose to display the consent banner on your website while you’re logged in and changing settings or customizing your banner.', 'cookiebot' ); ?>
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
				<?php esc_html_e( 'Show banner on website when logged in', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>
