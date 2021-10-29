<?php
/**
 * @var string $logo
 * @var string $cookiebot_gdpr_url
 * @var string $cbm
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Cookiebot Network Settings', 'cookiebot' ); ?></h1>
	<a href="https://www.cookiebot.com">
		<img
			src="<?php echo esc_attr( $logo ); ?>"
			alt="Cookiebot"
			style="float:right;margin-left:1em;">
	</a>
	<p>
		<?php
		printf(
				/* translators: first link is for GDPR, second is more information about third-party services */
			esc_html__(
				'Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the  %1$s %2$s.',
				'cookiebot'
			),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( $cookiebot_gdpr_url ),
				esc_html__( 'General Data Protection Regulation (GDPR)', 'cookiebot' )
			),
			esc_html__(
				' requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.',
				'cookiebot'
			)
		);
		?>
	</p>
	<p>
		<b style="color:red;font-size: larger">
				<?php
				esc_html_e(
					'The settings below is network wide settings. See notes below each field.',
					'cookiebot'
				);
				?>
			</b>
	</p>
	<form method="post" action="edit.php?action=cookiebot_network_settings">
		<?php wp_nonce_field( 'cookiebot-network-settings' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Network Cookiebot ID', 'cookiebot' ); ?></th>
				<td>
					<input
						type="text"
						name="cookiebot-cbid"
						value="<?php echo esc_attr( get_site_option( 'cookiebot-cbid', '' ) ); ?>"
						style="width:300px"/>
					<p class="description">
						<b>
							<?php
							esc_html_e(
								'If added this will be the default Cookiebot ID for all subsites. Subsites are able to override the Cookiebot ID.',
								'cookiebot'
							);
							?>
						</b>
						<br/>
						<?php esc_html_e( 'Need an ID?', 'cookiebot' ); ?>
						<a href="https://www.cookiebot.com/goto/signup" target="_blank">
							<?php
							esc_html_e(
								'Sign up for free on cookiebot.com',
								'cookiebot'
							);
							?>
						</a>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Cookie-blocking mode', 'cookiebot' ); ?>
				</th>
				<td>
					<label>
						<input
								type="radio"
								name="cookiebot-cookie-blocking-mode"
								value="auto" <?php checked( 'auto', $cbm ); ?> />
						<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-cookie-blocking-mode"
								value="manual" <?php checked( 'manual', $cbm ); ?> />
						<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
					</label>
					<p class="description">
						<?php esc_html_e( 'Should Cookiebot automatic block cookies by tagging known tags.', 'cookiebot' ); ?>
					</p>
				</td>
			</tr>
			<tr id="cookiebot-setting-async">
				<th scope="row">
					<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
					<br/><?php esc_html_e( 'Consent banner script tag', 'cookiebot' ); ?>
				</th>
				<td>
					<?php
					$cv = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
					?>
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-uc-attribute"
								value="" <?php checked( '', $cv ); ?> />
						<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-uc-attribute"
								value="async" <?php checked( 'async', $cv ); ?> />
						async
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-uc-attribute"
								value="defer" <?php checked( 'defer', $cv ); ?> />
						defer
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-uc-attribute"
								value="custom" <?php checked( 'custom', $cv ); ?> />
						<i><?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?></i>
					</label>
					<p class="description">
						<b>
							<?php
							esc_html_e(
								'Setting will apply for all subsites. Subsites will not be able to override.',
								'cookiebot'
							);
							?>
						</b><br/>
						<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite', 'cookiebot' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
					<br/><?php esc_html_e( 'Cookie declaration script tag', 'cookiebot' ); ?>
				</th>
				<td>
					<?php
					$cv = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
					?>
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"
								value="" <?php checked( '', $cv ); ?> />
						<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"
								value="async" <?php checked( 'async', $cv ); ?> />
						async
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"
								value="defer" <?php checked( 'defer', $cv ); ?> />
						defer
					</label>
					&nbsp; &nbsp;
					<label>
						<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"
								value="custom" <?php checked( 'custom', $cv ); ?> />
						<i><?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?></i>
					</label>
					<p class="description">
						<b>
							<?php
							esc_html_e(
								'Setting will apply for all subsites. Subsites will not be able to override.',
								'cookiebot'
							);
							?>
						</b><br/>
						<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite', 'cookiebot' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Auto-update Cookiebot', 'cookiebot' ); ?></th>
				<td>
					<input
							type="checkbox"
							name="cookiebot-autoupdate"
							value="1"
						<?php
						checked(
							1,
							get_site_option( 'cookiebot-autoupdate' )
						);
						?>
					/>
					<p class="description">
						<?php esc_html_e( 'Automatic update your Cookiebot plugin when new releases becomes available.', 'cookiebot' ); ?>
					</p>
				</td>
			</tr>
			<tr id="cookiebot-setting-hide-popup">
				<th scope="row"><?php esc_html_e( 'Hide Cookie Popup', 'cookiebot' ); ?></th>
				<td>
					<input type="checkbox" name="cookiebot-nooutput" value="1"
						<?php
						checked(
							1,
							get_site_option( 'cookiebot-nooutput' )
						);
						?>
					/>
					<p class="description">
						<b>
							<?php
							esc_html_e(
								'Remove the cookie consent banner from all subsites. This cannot be changed by subsites. The <i>[cookie_declaration]</i> shortcode will still be available.',
								'cookiebot'
							);
							?>
						</b><br/>
						<?php
						esc_html_e(
							'If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.',
							'cookiebot'
						);
						?>
						<br/>
						<?php
						esc_html_e(
							'<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">See a detailed guide here</a>',
							'cookiebot'
						);
						?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Hide Cookie Popup in WP Admin', 'cookiebot' ); ?></th>
				<td>
					<input type="checkbox" name="cookiebot-nooutput-admin" value="1"
						<?php
						checked(
							1,
							get_site_option( 'cookiebot-nooutput-admin' )
						);
						?>
					/>
					<p class="description">
						<b>
							<?php
							esc_html_e(
								'Remove the cookie consent banner the WordPress Admin area for all subsites. This cannot be changed by subsites.',
								'cookiebot'
							);
							?>
						</b>
					</p>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>
