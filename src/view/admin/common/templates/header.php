<?php
/**
 * @var string $cookiebot_logo
 */
?>
<?php
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! empty( $_GET['settings-updated'] ) ) :
	?>
<div class="cb-submit__msg"><?php esc_html_e( 'Changes has been saved', 'cookiebot' ); ?></div>
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
