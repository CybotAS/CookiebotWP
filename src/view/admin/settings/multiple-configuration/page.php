<?php
/**
 * @var string $cbid
 * @var string $secondary_group_id
 * @var array $supported_regions
 * @var string $ccpa_compatibility
 * @var array $selected_regions
 */
?>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'Additional configurations:', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'You can add a second alternative banner or configuration to your website by creating a second Domain Group and specify it on a region.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360010932419-Multiple-banners-on-the-same-website-example-CCPA-GDPR-"
		   target="_blank" class="cb-btn cb-link-btn" rel="noopener">
			<?php esc_html_e( 'Read more about multiple configurations here', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="multiple-config">
				<input
					type="checkbox"
					name="cookiebot-multiple-config"
					id="multiple-config"
					value="1" <?php checked( 1, get_option( 'cookiebot-multiple-config' ) ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'Multiple configurations', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>

<div class="cb-multiple__container <?php echo ! get_option( 'cookiebot-multiple-config' ) ? 'hidden' : ''; ?>">
	<div class="cb-settings__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle">
				<?php esc_html_e( 'Set up your additional banner configuration:', 'cookiebot' ); ?>
			</h3>
			<p class="cb-general__info__text">
				<?php esc_html_e( 'To enable a different configuration, create a separate DomainGroup without adding the domain to it and paste the ID below. Then select the countries in which you want to show this configuration. For example, if your main Domain Group is defined as a banner matching GDPR requirements, you might want to add another Domain Group for visitors from California. The number of additional configurations is restricted to one at the moment.', 'cookiebot' ); ?>
			</p>
		</div>
		<div class="cb-settings__config__data"></div>
	</div>

	<div class="cb-region__table">
		<div class="cb-region__table__header">
			<div class="cb-region__table__column">
				<div class="cb-region__table__header--title">
					<?php esc_html_e( 'Domain Group ID', 'cookiebot' ); ?>
				</div>
			</div>
			<div class="cb-region__table__column">
				<div class="cb-region__table__header--title"><?php esc_html_e( 'Region', 'cookiebot' ); ?></div>
			</div>
		</div>
		<div class="cb-region__table__item">
			<div class="cb-region__item__group">
				<input type="text" disabled
					   placeholder="<?php echo $cbid ? esc_html( $cbid ) : '1111-1111-1111-1111'; ?>">
			</div>
			<div class="cb-region__item__region">
				<p class="cb-region__item__region--primary">
					<?php esc_html_e( 'Primary domain group', 'cookiebot' ); ?>
				</p>
			</div>
		</div>
		<div class="cb-region__table__item">
			<div class="cb-region__item__group">
				<input type="text" name="cookiebot-second-banner-id" placeholder="1111-1111-1111-1111"
					   value="<?php echo esc_html( $secondary_group_id ); ?>">
			</div>
			<div class="cb-region__item__region">
				<input type="hidden" name="cookiebot-second-banner-regions" id="second-banner-regions"
					   value="<?php echo esc_html( implode( ', ', array_keys( $selected_regions ) ) ); ?>">
				<input type="hidden" name="cookiebot-ccpa" id="ccpa-compatibility"
					   value="<?php echo esc_html( $ccpa_compatibility ); ?>">
				<div class="cb-region__region__selector">
					<div class="default-none <?php echo $selected_regions ? 'hidden' : ''; ?>">
						<?php esc_html_e( 'Select region', 'cookiebot' ); ?>
					</div>
					<div class="selected-regions">
						<?php foreach ( $selected_regions as $code => $region ) : ?>
							<div id="<?php echo esc_html( $code ); ?>" class="selected-regions-item">
								<?php echo esc_html( $region ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="cb-region__region__list hidden">
					<div class="cb-region__veil"></div>
					<div class="cb-region__list__container">
					<?php foreach ( $supported_regions as $code => $region ) : ?>
						<div class='cb-region__region__item <?php echo array_key_exists( $code, $selected_regions ) ? 'selected-region' : ''; ?>'
							 data-region="<?php echo esc_html( $code ); ?>"><?php echo esc_html( $region ); ?></div>
					<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
