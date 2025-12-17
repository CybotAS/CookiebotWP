<?php
/**
 * @var bool $is_wp_consent_api_active
 * @var array $m_default
 * @var array $m
 */

use cybot\cookiebot\addons\config\Settings_Config;

?>
<div class="cb-addons__tab__header">
	<div class="cb-addons__header__column--inner">
		<div class="cb-addons__header__column submit-column">
			<h2 class="cb-addons__tab__subtitle"><?php esc_html_e( 'Remember to save your changes before switching tabs', 'cookiebot' ); ?></h2>
		</div>
	</div>
</div>


<?php if ( $is_wp_consent_api_active ) { ?>
	<h3 id="consent_level_api_settings_title" class="cookiebot_fieldset_header">
		<?php
		esc_html_e(
			'WP Consent API Settings',
			'cookiebot'
		);
		?>
	</h3>
	<div id="consent_level_api_settings">
		<p>
			<?php
			esc_html_e(
				'WP Consent API and Cookiebot by Usercentrics WordPress Plugin categorize cookies a bit differently. The default settings should fit most needs, but if you need to change the mapping you can do so below.',
				'cookiebot'
			);
			?>
		</p>
		<table class="cb-settings__consent__mapping-table uc-consent-table"
				aria-describedby="consent_level_api_settings_title">
			<thead>
			<tr>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="cb-settings__data__head">
					<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Cookiebot by Usercentrics cookie categories', 'cookiebot' ); ?></h3>
				</td>
				<td class="cb-settings__data__head">
					<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'WP Consent API cookies categories equivalent', 'cookiebot' ); ?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<div class="cb_consent">
							<span class="uc_categories">
								<?php esc_html_e( 'essential', 'cookiebot' ); ?>
							</span>
					</div>
				</td>
				<td>
					<div class="consent_mapping">
						<input type="text" disabled value="Functional">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="cb_consent">
							<span class="uc_categories">
								<?php esc_html_e( 'functional', 'cookiebot' ); ?>
							</span>
					</div>
				</td>
				<td>
					<div class="consent_mapping">
						<select class="cb-category-selectors" name="cookiebot-uc-consent-mapping[functional]"
								id="cookiebot-uc-consent-mapping-functional" data-default="preferences">
							<?php
							echo wp_kses(
								Settings_Config::get_wp_consent_values( 'functional', $m ),
								array(
									'option' => array(
										'selected' => true,
										'value'    => array(),
									),
								)
							);
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="cb_consent">
							<span class="uc_categories">
								<?php esc_html_e( 'marketing', 'cookiebot' ); ?>
							</span>
					</div>
				</td>
				<td>
					<div class="consent_mapping">
						<select class="cb-category-selectors" name="cookiebot-uc-consent-mapping[marketing]"
								id="cookiebot-uc-consent-mapping-marketing" data-default="marketing">
							<?php
							echo wp_kses(
								Settings_Config::get_wp_consent_values( 'marketing', $m ),
								array(
									'option' => array(
										'selected' => true,
										'value'    => array(),
									),
								)
							);
							?>
						</select>
					</div>
				</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td>
					<div id="cb-consent-api-reset-defaults" class="cb-btn cb-main-btn uc-consent">
						<?php
						esc_html_e(
							'Reset to default categories',
							'cookiebot'
						);
						?>
					</div>
				</td>
			</tr>
			</tfoot>
		</table>
	</div>
<?php } ?>
