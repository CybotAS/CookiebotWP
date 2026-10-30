<?php

use function cybot\cookiebot\lib\asset_url;

/**
 * @var string $domain
 */
?>
<dialog class="cb-dash-dialog" aria-labelledby="cb-dash-disconnect-title" aria-describedby="cb-dash-disconnect-text" data-cb-disconnect-dialog>
	<div class="cb-dash-dialog__header">
		<h2 class="cb-dash-dialog__title" id="cb-dash-disconnect-title"><?php esc_html_e( 'Are you sure?', 'cookiebot' ); ?></h2>
		<button type="button" class="cb-dash-dialog__close" data-cb-dialog-cancel>
			<img src="<?php echo esc_url( asset_url( 'img/dashboard/close.svg' ) ); ?>" width="24" height="24" alt="">
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'cookiebot' ); ?></span>
		</button>
	</div>
	<p class="cb-dash-dialog__text" id="cb-dash-disconnect-text">
		<?php
		printf(
			/* translators: %s: the site's domain, e.g. clientsite.com. */
			esc_html__( 'Your cookie banner will stop showing on %s until you connect the Domain Group ID or Settings ID.', 'cookiebot' ),
			esc_html( $domain )
		);
		?>
	</p>
	<div class="cb-dash-dialog__actions">
		<button type="button" class="cb-dash-dialog__btn cb-dash-dialog__btn--secondary" data-cb-dialog-cancel autofocus>
			<?php esc_html_e( 'Cancel', 'cookiebot' ); ?>
		</button>
		<button type="button" class="cb-dash-dialog__btn cb-dash-dialog__btn--danger" data-cb-dialog-confirm>
			<?php esc_html_e( 'Disconnect', 'cookiebot' ); ?>
		</button>
	</div>
</dialog>
