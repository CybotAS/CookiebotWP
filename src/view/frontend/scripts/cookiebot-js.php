<?php
/**
 * @var string $cbid
 * @var string $lang
 * @var string $tag_attr
 * @var string $cookie_blocking_mode
 * @var array $data_regions
 */
?>
<script type="text/javascript"
		id="Cookiebot"
		src="https://consent.cookiebot.com/uc.js"
		data-cbid="<?php echo esc_attr( $cbid ); ?>"
	<?php if ( (bool) get_option( 'cookiebot-iab' ) !== false ) : ?>
		data-framework="IAB"
	<?php endif; ?>
	<?php
	if ( $data_regions ) {
		echo 'data-georegions="';
		foreach ( $data_regions as $cbid => $regions ) {
			echo '{\'region\':\'' . esc_attr( $regions ) . '\',\'cbid\':\'' . esc_attr( $cbid ) . '\'}';
			end( $data_regions );
			if ( $cbid !== key( $data_regions ) ) {
				echo ',';
			}
		}
		echo '"';
	}
	?>
	<?php if ( (bool) get_option( 'cookiebot-gtm' ) !== false && ! empty( get_option( 'cookiebot-data-layer' ) ) ) : ?>
		data-layer-name="<?php echo esc_attr( get_option( 'cookiebot-data-layer' ) ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $lang ) ) : ?>
		data-culture="<?php echo esc_attr( strtoupper( $lang ) ); ?>"
	<?php endif; ?>
	<?php if ( $cookie_blocking_mode === 'auto' ) : ?>
		data-blockingmode="auto"
	<?php else : ?>
		<?php echo esc_attr( $tag_attr ); ?>
	<?php endif; ?>
></script>
