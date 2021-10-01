<?php

/** @var string $url */
/** @var string $lang */
/** @var string $tag_attr */

?>
<script
		type="text/javascript"
		id="CookieDeclaration"
		src="<?php echo esc_url( $url ); ?>"
		<?php if ( ! empty( $lang ) ) : ?>
			data-culture="<?php echo esc_attr( $lang ); ?>"
		<?php endif; ?>
		<?php echo esc_attr( $tag_attr ); ?>
></script>
