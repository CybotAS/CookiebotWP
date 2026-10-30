<?php

use function cybot\cookiebot\lib\asset_url;

/**
 * @var string $domain
 * @var bool   $offer_active
 * @var string $signup_url
 * @var bool   $signup_clicked
 */
?>
<div class="cb-dash__card cb-dash__step" data-cb-new-account data-cb-step-done="<?php echo $signup_clicked ? '1' : '0'; ?>">
	<span class="cb-dash__step-num" aria-hidden="true">1</span>
	<div class="cb-dash__step-body">
		<div class="cb-dash__step-text">
			<p class="cb-dash__step-label"><?php esc_html_e( 'STEP 1 OF 2', 'cookiebot' ); ?></p>
			<h3 class="cb-dash__h3"><?php esc_html_e( 'Create your Cookiebot account', 'cookiebot' ); ?></h3>
			<p class="cb-dash__body">
				<?php esc_html_e( 'Sign up on Cookiebot to create your consent banner. The sign-up opens in a new tab.', 'cookiebot' ); ?>
				<?php if ( ! $offer_active ) : ?>
					<a class="cb-dash__link" href="https://www.cookiebot.com/en/pricing/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin_dashboard&utm_content=setup_step_1" target="_blank" rel="noopener">
						<?php esc_html_e( 'See pricing', 'cookiebot' ); ?><span aria-hidden="true">&nbsp;↗</span>
					</a>
				<?php endif; ?>
			</p>
			<p class="cb-dash__body">
				<?php
				printf(
					/* translators: %s: the site's domain, e.g. clientsite.com. */
					esc_html__( 'When Cookiebot asks for your website, enter %s. It must match this site, or your banner won’t appear.', 'cookiebot' ),
					'<strong>' . esc_html( $domain ) . '</strong>'
				);
				?>
			</p>
		</div>

		<div class="cb-dash__action">
			<?php if ( $offer_active ) : ?>
				<div class="cb-dash__offer">
					<span class="cb-dash__offer-icon">
						<img src="<?php echo esc_url( asset_url( 'img/dashboard/sell.svg' ) ); ?>" width="22" height="22" alt="">
					</span>
					<span class="cb-dash__offer-text">
						<span class="cb-dash__h4"><?php esc_html_e( '20% off your first year', 'cookiebot' ); ?></span>
						<span class="cb-dash__small"><?php esc_html_e( 'Applied automatically when you sign up.', 'cookiebot' ); ?></span>
					</span>
					<a class="cb-dash__link cb-dash__link--small" href="https://www.cookiebot.com/en/pricing/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin_dashboard&utm_content=offer_callout" target="_blank" rel="noopener">
						<?php esc_html_e( 'See pricing', 'cookiebot' ); ?>
						<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-outlined.svg' ) ); ?>" width="14" height="14" alt="">
					</a>
				</div>
			<?php endif; ?>
			<a class="cb-dash__btn cb-dash__btn--primary" href="<?php echo esc_url( $signup_url ); ?>" target="_blank" rel="noopener" data-cb-signup>
				<?php esc_html_e( 'Create account', 'cookiebot' ); ?>
				<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-white.svg' ) ); ?>" width="24" height="24" alt="">
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'cookiebot' ); ?></span>
			</a>
		</div>
	</div>
</div>
