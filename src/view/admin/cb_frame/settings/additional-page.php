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
