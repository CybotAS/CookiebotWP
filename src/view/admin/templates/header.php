<?php
/**
 * @var string $cookiebot_logo
 */
?>
<?php
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! empty( $_GET['settings-updated'] ) ) :
	?>
<div class="cb-submit__msg">Changes has been saved</div>
<?php endif; ?>
<div class="cb-header">
	<div class="cb-wrapper">
		<a href="https://www.cookiebot.com">
			<img
				src="<?php echo esc_url( $cookiebot_logo ); ?>"
				alt="Cookiebot logo">
		</a>
	</div>
</div>
