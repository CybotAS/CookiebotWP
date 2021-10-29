<?php


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
 * @var string $add_language_gif_url
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Cookiebot Settings', 'cookiebot' ); ?></h1>
	<a href="https://www.cookiebot.com">
		<img
			src="<?php echo esc_url( $cookiebot_logo ); ?>"
			alt="Cookiebot logo"
			style="float:right;margin-left:1em;">
	</a>
	<p>
		<?php
		printf(
			/* translators: %1$s: GDPR URL; %2$s: extra information about the requirements */
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
	<form method="post" action="options.php">
		<?php settings_fields( 'cookiebot' ); ?>
		<?php do_settings_sections( 'cookiebot' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Cookiebot ID', 'cookiebot' ); ?></th>
				<td>
					<input <?php echo ( $is_ms ) ? ' placeholder="' . esc_attr( $network_cbid ) . '"' : ''; ?>
							type="text" name="cookiebot-cbid"
							value="<?php echo esc_attr( $cbid ); ?>"
							style="width:300px"
					/>
					<p class="description">
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
						<input <?php checked( 'auto', $cookie_blocking_mode ); ?>
								type="radio"
								name="cookiebot-cookie-blocking-mode"
								value="auto"
						/>
						<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
					</label>
					&nbsp; &nbsp;
					<label>
						<input <?php checked( 'manual', $cookie_blocking_mode ); ?>
								type="radio"
								name="cookiebot-cookie-blocking-mode"
								value="manual"
						/>
						<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
					</label>
					<p class="description">
						<?php esc_html_e( 'Automatic block cookies (except necessary) until the user has given their consent.', 'cookiebot' ); ?>
						<a
							href="https://support.cookiebot.com/hc/en-us/articles/360009063100-Automatic-Cookie-Blocking-How-does-it-work-"
							target="_blank">
							<?php esc_html_e( 'Learn more', 'cookiebot' ); ?>
						</a>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Cookiebot Language', 'cookiebot' ); ?></th>
				<td>
					<div>
						<select name="cookiebot-language" id="cookiebot-language">
							<option value=""><?php esc_html_e( 'Default (Autodetect)', 'cookiebot' ); ?></option>
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
								echo '<option value="' . esc_attr( $lang_code ) . '"' . ( ( $current_lang === esc_attr( $lang_code ) ) ? ' selected' : '' ) . '>' . esc_html( $lang_name ) . '</option>';
							}
							?>
						</select>
					</div>
					<div
						class="notice inline notice-warning notice-alt cookiebot-notice"
						style="padding:12px;font-size:13px;display:inline-block;">
						<div
							style="<?php echo ( $current_lang === '' ) ? 'display:none;' : ''; ?>"
							id="info_lang_specified">
							<?php esc_html_e( 'You need to add the language in the Cookiebot administration tool.', 'cookiebot' ); ?>
						</div>
						<div
							style="<?php echo ( $current_lang === '' ) ? '' : 'display:none;'; ?>"
							id="info_lang_autodetect">
							<?php
							esc_html_e(
								'You need to add all languages that you want auto-detected in the Cookiebot administration tool.',
								'cookiebot'
							);
							?>
							<br/>
							<?php
							esc_html_e(
								'The auto-detect checkbox needs to be enabled in the Cookiebot administration tool.',
								'cookiebot'
							);
							?>
							<br/>
							<?php
							esc_html_e(
								'If the auto-detected language is not supported, Cookiebot will use the default language.',
								'cookiebot'
							);
							?>
						</div>
						<br>

						<a
							href="#"
							id="show_add_language_guide">
							<?php esc_html_e( 'Show guide to add languages', 'cookiebot' ); ?>
						</a>
						&nbsp;
						<a
							href="https://support.cookiebot.com/hc/en-us/articles/360003793394-How-do-I-set-the-language-of-the-consent-banner-dialog-"
							target="_blank">
							<?php esc_html_e( 'Read more here', 'cookiebot' ); ?>
						</a>

						<div id="add_language_guide" style="display:none;">
							<img
								src="<?php echo esc_attr( $add_language_gif_url ); ?>"
								alt="Add language in Cookiebot administration tool"/>
							<br/>
							<a
								href="#"
								id="hide_add_language_guide">
								<?php esc_html_e( 'Hide guide', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<h3 id="advanced_settings_link"
			class="cookiebot_fieldset_header"><?php esc_html_e( 'Advanced settings', 'cookiebot' ); ?></h3>
		<div id="advanced_settings" style="display:none;">
			<table class="form-table">
				<tr id="cookiebot-setting-async">
					<th scope="row">
						<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
						<br/><?php esc_html_e( 'Consent banner script tag', 'cookiebot' ); ?>
					</th>
					<td>
						<?php
						$cv       = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
						$disabled = false;
						if ( $is_ms && $network_scrip_tag_uc_attr !== 'custom' ) {
							$disabled = true;
							$cv       = $network_scrip_tag_uc_attr;
						}
						?>
						<label>
							<input
								type="radio"
								name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
								value="" <?php checked( '', $cv ); ?> />
							<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
						</label>
						&nbsp; &nbsp;
						<label>
							<input
									type="radio"
									name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
									value="async" <?php checked( 'async', $cv ); ?> />
							async
						</label>
						&nbsp; &nbsp;
						<label>
							<input
									type="radio"
									name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
									value="defer" <?php checked( 'defer', $cv ); ?> />
							defer
						</label>
						<p class="description">
							<?php
							if ( $disabled ) {
								echo '<b>' . esc_html__(
									'Network setting applied. Please contact website administrator to change this setting.',
									'cookiebot'
								) . '</b><br />';
							}
							?>
							<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: async', 'cookiebot' ); ?>
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
							<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
						</label>
						&nbsp; &nbsp;
						<label>
							<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
								value="async" <?php checked( 'async', $cv ); ?>/>
							async
						</label>
						&nbsp; &nbsp;
						<label>
							<input
								type="radio"
								name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
								value="defer" <?php checked( 'defer', $cv ); ?>/>
							defer
						</label>
						<p class="description">
							<?php
							if ( $disabled ) {
								echo '<b>' . esc_html__(
									'Network setting applied. Please contact website administrator to change this setting.',
									'cookiebot'
								) . '</b><br />';
							}
							?>
							<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: async', 'cookiebot' ); ?>
						</p>
					</td>
				</tr>
				<?php
				if ( ! is_multisite() ) {
					?>
					<tr>
						<th scope="row"><?php esc_html_e( 'Auto-update Cookiebot', 'cookiebot' ); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-autoupdate" value="1"
								<?php
								checked(
									1,
									get_option( 'cookiebot-autoupdate', false )
								);
								?>
							/>
							<p class="description">
								<?php esc_html_e( 'Automatic update your Cookiebot plugin when new releases becomes available.', 'cookiebot' ); ?>
							</p>
						</td>
					</tr>
					<?php
				}
				?>
				<tr id="cookiebot-setting-hide-popup">
					<th scope="row"><?php esc_html_e( 'Hide Cookie Popup', 'cookiebot' ); ?></th>
					<td>
						<?php
						$disabled = false;
						if ( $is_ms && get_site_option( 'cookiebot-nooutput' ) ) {
							$disabled = true;
							echo '<input type="checkbox" checked disabled />';
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
							<?php
						}
						?>
						<p class="description">
							<?php
							if ( $disabled ) {
								echo '<b>' . esc_html__(
									'Network setting applied. Please contact website administrator to change this setting.',
									'cookiebot'
								) . '</b><br />';
							}
							?>
							<b>
								<?php
								esc_html_e(
									'This checkbox will remove the cookie consent banner from your website. The <i>[cookie_declaration]</i> shortcode will still be available.',
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
							<a
								href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment"
								target="_blank">
								<?php esc_html_e( 'See a detailed guide here', 'cookiebot' ); ?>
							</a>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Disable Cookiebot in WP Admin', 'cookiebot' ); ?></th>
					<td>
						<?php
						$disabled = false;
						if ( $is_ms && get_site_option( 'cookiebot-nooutput-admin' ) ) {
							echo '<input type="checkbox" checked disabled />';
							$disabled = true;
						} else {
							?>
							<input type="checkbox" name="cookiebot-nooutput-admin" value="1"
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
						<p class="description">
							<?php
							if ( $disabled ) {
								echo '<b>' . esc_html__( 'Network setting applied. Please contact website administrator to change this setting.', 'cookiebot' ) . '</b><br />';
							}
							?>
							<b><?php esc_html_e( 'This checkbox will disable Cookiebot in the WordPress Admin area.', 'cookiebot' ); ?></b>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable Cookiebot on front end while logged in', 'cookiebot' ); ?></th>
					<td>
						<?php
						$disabled = false;
						if ( $is_ms && get_site_option( 'cookiebot-output-logged-in' ) ) {
							echo '<input type="checkbox" checked disabled />';
							$disabled = true;
						} else {
							?>
							<input type="checkbox" name="cookiebot-output-logged-in" value="1"
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
						<p class="description">
							<?php
							if ( $disabled ) {
								echo '<b>' . esc_html__( 'Network setting applied. Please contact website administrator to change this setting.', 'cookiebot' ) . '</b><br />';
							}
							?>
							<b><?php esc_html_e( 'This checkbox will enable Cookiebot on front end while you\'re logged in', 'cookiebot' ); ?></b>
						</p>
					</td>
				</tr>
			</table>
		</div>
		<?php if ( $is_wp_consent_api_active ) { ?>
			<h3 id="consent_level_api_settings" class="cookiebot_fieldset_header">
				<?php
				esc_html_e(
					'Consent Level API Settings',
					'cookiebot'
				);
				?>
			</h3>
			<div id="consent_level_api_settings" style="display:none;">
				<p>
					<?php
					esc_html_e(
						'WP Consent Level API and Cookiebot categorise cookies a bit different. The default settings should fit mosts needs - but if you need to change the mapping you are able to do it below.',
						'cookiebot'
					);
					?>
				</p>

				<?php
				$consent_types = array( 'preferences', 'statistics', 'marketing' );
				$states        = array_reduce(
					$consent_types,
					function ( $t, $v ) {
						$newt = array();
						if ( empty( $t ) ) {
							$newt = array(
								array( $v => true ),
								array( $v => false ),
							);
						} else {
							foreach ( $t as $item ) {
								$newt[] = array_merge( $item, array( $v => true ) );
								$newt[] = array_merge( $item, array( $v => false ) );
							}
						}

						return $newt;
					},
					array()
				);

				?>


				<table class="widefat striped consent_mapping_table">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Cookiebot categories', 'cookiebot' ); ?></th>
						<th class="consent_mapping"><?php esc_html_e( 'WP Consent Level categories', 'cookiebot' ); ?></th>
					</tr>
					</thead>
					<?php
					foreach ( $states as $state ) {
						$key   = array();
						$key[] = 'n=1';
						$key[] = 'p=' . ( $state['preferences'] ? '1' : '0' );
						$key[] = 's=' . ( $state['statistics'] ? '1' : '0' );
						$key[] = 'm=' . ( $state['marketing'] ? '1' : '0' );
						$key   = implode( ';', $key );
						?>
						<tr>
							<td>
								<div class="cb_consent">
												<span class="forceconsent">
													<?php esc_html_e( 'Necessary', 'cookiebot' ); ?>
												</span>
									<span class="<?php echo( $state['preferences'] ? 'consent' : 'noconsent' ); ?>">
													<?php esc_html_e( 'Preferences', 'cookiebot' ); ?>
												</span>
									<span class="<?php echo( $state['statistics'] ? 'consent' : 'noconsent' ); ?>">
													<?php esc_html_e( 'Statistics', 'cookiebot' ); ?>
												</span>
									<span class="<?php echo( $state['marketing'] ? 'consent' : 'noconsent' ); ?>">
													<?php esc_html_e( 'Marketing', 'cookiebot' ); ?>
												</span>
								</div>
							</td>
							<td>
								<div class="consent_mapping">
									<label><input
												type="checkbox"
												name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][functional]"
												data-default-value="1" value="1" checked disabled
										> <?php esc_html_e( 'Functional', 'cookiebot' ); ?> </label>
									<label><input
												type="checkbox"
												name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][preferences]"
												data-default-value="<?php echo esc_attr( $m_default[ $key ]['preferences'] ); ?>"
												value="1"
											<?php
											if ( $m[ $key ]['preferences'] ) {
												echo 'checked';
											}
											?>
										> <?php esc_html_e( 'Preferences', 'cookiebot' ); ?> </label>
									<label><input
												type="checkbox"
												name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics]"
												data-default-value="<?php echo esc_attr( $m_default[ $key ]['statistics'] ); ?>"
												value="1"
											<?php
											if ( $m[ $key ]['statistics'] ) {
												echo 'checked';
											}
											?>
										> <?php esc_html_e( 'Statistics', 'cookiebot' ); ?> </label>
									<label><input
												type="checkbox"
												name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics-anonymous]"
												data-default-value="<?php echo esc_attr( $m_default[ $key ]['statistics-anonymous'] ); ?>"
												value="1"
											<?php
											if ( $m[ $key ]['statistics-anonymous'] ) {
												echo 'checked';
											}
											?>
										> <?php esc_html_e( 'Statistics Anonymous', 'cookiebot' ); ?>
									</label>
									<label><input
												type="checkbox"
												name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][marketing]"
												data-default-value="<?php echo esc_attr( $m_default[ $key ]['marketing'] ); ?>"
												value="1"
											<?php
											if ( $m[ $key ]['marketing'] ) {
												echo 'checked';
											}
											?>
										> <?php esc_html_e( 'Marketing', 'cookiebot' ); ?></label>
								</div>
							</td>
						</tr>
						<?php
					}
					?>
					<tfoot>
					<tr>
						<td colspan="2" style="text-align:right;">
							<button class="button" onclick="return resetConsentMapping();">
								<?php
								esc_html_e(
									'Reset to default mapping',
									'cookiebot'
								);
								?>
							</button>
						</td>
					</tr>
					</tfoot>
				</table>
			</div>
		<?php } ?>
		<?php submit_button(); ?>
	</form>
</div>
