<?php
/** @var string $cbid */
/** @var string $lang */
/** @var string $tag_attr */
/** @var string $cookie_blocking_mode */
?>
<script type="text/javascript"
		id="Cookiebot"
		src="https://consent.cookiebot.com/uc.js"
		data-cbid="<?php echo esc_attr( $cbid ); ?>"
	<?php if ( (bool) get_option( 'cookiebot-iab' ) !== false ) : ?>
		data-framework="IAB"
	<?php endif; ?>
	<?php if ( (bool) get_option( 'cookiebot-ccpa' ) !== false ) : ?>
		data-georegions="{'region':'US-06','cbid':'<?php echo esc_attr( get_option( 'cookiebot-ccpa-domain-group-id' ) ); ?>'}"
	<?php endif; ?>
	<?php if ( (bool) get_option( 'cookiebot-gtm' ) !== false ) : ?>
		<?php if ( empty( get_option( 'cookiebot-data-layer' ) ) ) : ?>
			data-layer-name="dataLayer"
		<?php else : ?>
			data-layer-name="<?php echo esc_attr( get_option( 'cookiebot-data-layer' ) ); ?>"
		<?php endif; ?>
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
