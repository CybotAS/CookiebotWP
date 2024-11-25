<?php
/**
 * @var bool $is_wp_consent_api_active
 * @var array $m_default
 * @var array $m
 */
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
			'Consent Level API Settings',
			'cookiebot'
		);
		?>
	</h3>
	<div id="consent_level_api_settings">
		<p>
			<?php
			esc_html_e(
				'WP Consent Level API and Cookiebot™ categorize cookies a bit differently. The default settings should fit most needs, but if you need to change the mapping you can do so below.',
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
		<table class="cb-settings__consent__mapping-table" aria-describedby="consent_level_api_settings_title">
			<thead>
				<tr>
					<th></th>
					<th></th>
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
						<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'Cookiebot™ cookie categories', 'cookiebot' ); ?></h3>
						<div class="cb_consent">
												<span class="forceconsent">
													<?php esc_html_e( 'necessary', 'cookiebot' ); ?>
												</span>
							<span class="<?php echo $state['preferences'] ? 'consent' : 'noconsent'; ?>">
													<?php esc_html_e( 'preferences', 'cookiebot' ); ?>
												</span>
							<span class="<?php echo $state['statistics'] ? 'consent' : 'noconsent'; ?>">
													<?php esc_html_e( 'statistics', 'cookiebot' ); ?>
												</span>
							<span class="<?php echo $state['marketing'] ? 'consent' : 'noconsent'; ?>">
													<?php esc_html_e( 'marketing', 'cookiebot' ); ?>
												</span>
						</div>
					</td>
					<td>
						<h3 class="cb-settings__data__subtitle"><?php esc_html_e( 'WP Consent API cookies categories equivalent', 'cookiebot' ); ?></h3>
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
									if ( ! empty( $m[ $key ]['preferences'] ) ) {
										echo 'checked';
									}
									?>
									<?php
									if ( $m_default[ $key ]['preferences'] ) {
										echo 'disabled';
									}
									?>
								> <?php esc_html_e( 'preferences', 'cookiebot' ); ?> </label>
							<label><input
									type="checkbox"
									name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics]"
									data-default-value="<?php echo esc_attr( $m_default[ $key ]['statistics'] ); ?>"
									value="1"
									<?php
									if ( ! empty( $m[ $key ]['statistics'] ) ) {
										echo 'checked';
									}
									?>
									<?php
									if ( $m_default[ $key ]['statistics'] ) {
										echo 'disabled';
									}
									?>
								> <?php esc_html_e( 'statistics', 'cookiebot' ); ?> </label>
							<label><input
									type="checkbox"
									name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics-anonymous]"
									data-default-value="<?php echo esc_attr( $m_default[ $key ]['statistics-anonymous'] ); ?>"
									value="1"
									<?php
									if ( ! empty( $m[ $key ]['statistics-anonymous'] ) ) {
										echo 'checked';
									}
									?>
									<?php
									if ( $m_default[ $key ]['statistics-anonymous'] ) {
										echo 'disabled';
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
									if ( ! empty( $m[ $key ]['marketing'] ) ) {
										echo 'checked';
									}
									?>
									<?php
									if ( $m_default[ $key ]['marketing'] ) {
										echo 'disabled';
									}
									?>
								> <?php esc_html_e( 'marketing', 'cookiebot' ); ?></label>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
			<tfoot>
			<tr>
				<td>
					<button class="cb-btn cb-main-btn" onclick="return resetConsentMapping();">
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
