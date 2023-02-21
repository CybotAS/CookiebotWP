<?php
/** @var array $notice */
?>
<div class="cookiebot-admin-notice-container">
	<div class="update-nag cookiebot-admin-notice">
		<div class="cookiebot-notice-logo"></div>
		<p class="cookiebot-notice-title"><?php echo esc_html( $notice['title'] ); ?></p>
		<p class="cookiebot-notice-body"><?php echo esc_html( $notice['msg'] ); ?></p>
        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<ul class="cookiebot-notice-body wd-blue"><?php echo $notice['link_html']; ?></ul>
		<a href="<?php echo esc_url( $notice['later_link'] ); ?>" class="dashicons dashicons-dismiss"></a>
	</div>
</div>
