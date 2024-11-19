<?php
/**
 * @var array $banners
 * @var array $supported_regions
 */

foreach ( $banners as $banner => $data ) : ?>
<div class="cb-region__table__item cb-region__secondary__banner" data-next-banner="<?php echo esc_attr( $banner ); ?>">
	<div class="cb-region__item__group">
		<input type="text" name="cookiebot-multiple-banners[<?php echo esc_attr( $banner ); ?>][group]" placeholder="1111-1111-1111-1111"
			   value="<?php echo esc_attr( $data['group'] ); ?>">
	</div>
	<div class="cb-region__item__region">
		<input type="hidden" name="cookiebot-multiple-banners[<?php echo esc_attr( $banner ); ?>][region]" class="second-banner-regions"
			   value="<?php echo esc_attr( implode( ', ', array_keys( $data['region'] ) ) ); ?>">
		<div class="cb-region__region__selector">
			<div class="default-none <?php echo $data['region'] ? 'hidden' : ''; ?>">
				<?php esc_html_e( 'Select region', 'cookiebot' ); ?>
			</div>
			<div class="selected-regions">
				<?php foreach ( $data['region'] as $code => $region ) : ?>
					<div id="<?php echo esc_attr( $code ); ?>" class="selected-regions-item">
						<?php echo esc_html( $region ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="cb-region__region__list hidden">
			<div class="cb-region__veil"></div>
			<div class="cb-region__list__container">
				<?php foreach ( $supported_regions as $code => $region ) : ?>
					<div class='cb-region__region__item <?php echo array_key_exists( $code, $data['region'] ) ? 'selected-region' : ''; ?>'
						 data-region="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $region ); ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="cb-region__remove__banner dashicons dashicons-dismiss"></div>
</div>

<?php endforeach; ?>
