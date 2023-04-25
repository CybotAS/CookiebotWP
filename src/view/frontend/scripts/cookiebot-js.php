<?php
/** @var string $cbid */
/** @var string $lang */
/** @var string $tag_attr */
/** @var string $cookie_blocking_mode */

$is_multi_config      = ! empty( get_option( 'cookiebot-multiple-config' ) ) ?
	get_option( 'cookiebot-multiple-config' ) :
	false;
$second_banner_region = ! empty( get_option( 'cookiebot-second-banner-regions' ) ) ?
	get_option( 'cookiebot-second-banner-regions' ) :
	false;
$second_banner_id     = ! empty( get_option( 'cookiebot-second-banner-id' ) ) ?
	get_option( 'cookiebot-second-banner-id' ) :
	false;
?>
<script type="text/javascript"
		id="Cookiebot"
		src="https://consent.cookiebot.com/uc.js"
		data-cbid="<?php echo esc_attr( $cbid ); ?>"
	<?php if ( (bool) get_option( 'cookiebot-iab' ) !== false ) : ?>
		data-framework="IAB"
	<?php endif; ?>
	<?php
	if ( (bool) $is_multi_config !== false &&
		$second_banner_region &&
		$second_banner_id ) :
		?>
		data-georegions="{'region':'<?php echo esc_attr( $second_banner_region ); ?>','cbid':'<?php	echo esc_attr( $second_banner_id ); ?>'}"
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
