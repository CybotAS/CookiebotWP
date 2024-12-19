<?php
/**
 * @var string $class
 * @var string $type
 * @var bool|string $unique_service
 * @var string $toggle
 */

?>
<div class="<?php echo esc_attr( $class ); ?>"
	uc-embed-type="<?php echo esc_attr( $type ); ?>"
	<?php if ( $unique_service ) : ?>
		uc-embed-service-id="<?php echo esc_attr( $unique_service ); ?>"
	<?php endif; ?>
	uc-embed-show-toggle="<?php echo esc_attr( $toggle ); ?>"
></div>
