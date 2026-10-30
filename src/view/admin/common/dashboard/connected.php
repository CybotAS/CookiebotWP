<?php

use cybot\cookiebot\lib\Cbid_Checker;
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
use function cybot\cookiebot\lib\asset_url;

/**
 * @var string $cbid
 * @var string $id_type        Cbid_Checker::TYPE_DOMAIN_GROUP or TYPE_SETTINGS.
 * @var string $domain
 * @var string $check_result   Cbid_Checker::RESULT_*.
 * @var bool   $just_connected
 * @var bool   $banner_enabled
 * @var bool   $is_network_id  ID inherited from the multisite network; can't be disconnected here.
 * @var string $preview_url
 * @var string $support_url
 */

$cb_dash_is_settings_id = Cbid_Checker::TYPE_SETTINGS === $id_type;
$cb_dash_not_set_up     = Cbid_Checker::RESULT_NOT_SET_UP === $check_result;

if ( $cb_dash_not_set_up ) {
	$cb_dash_subline = __( 'Connected through your Cookiebot account, but your banner may not be showing on this site.', 'cookiebot' );
} elseif ( $cb_dash_is_settings_id ) {
	$cb_dash_subline = __( 'Connected to this WordPress site through your Usercentrics account.', 'cookiebot' );
} else {
	$cb_dash_subline = __( 'Connected to this WordPress site through your Cookiebot account.', 'cookiebot' );
}

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>
		<div class="cb-main__content">
			<div class="cb-dash" data-cb-dash-view="connected">
				<h1 class="cb-dash__title"><?php esc_html_e( 'Dashboard', 'cookiebot' ); ?></h1>
				<hr class="cb-dash__divider">

				<section class="cb-dash__card cb-dash__connection">
					<div class="cb-dash__connection-header">
						<h2 class="cb-dash__h3"><?php esc_html_e( 'Your cookie banner', 'cookiebot' ); ?></h2>
						<p class="cb-dash__body"><?php echo esc_html( $cb_dash_subline ); ?></p>
					</div>

					<div class="cb-dash__connection-fields">
						<div class="cb-dash__connection-group">
							<div class="cb-dash__readonly-row">
								<div class="cb-dash__field cb-dash__field--fixed">
									<label class="cb-dash__label" for="cb-dash-website"><?php esc_html_e( 'Website', 'cookiebot' ); ?></label>
									<input type="text" class="cb-dash__input" id="cb-dash-website" value="<?php echo esc_attr( $domain ); ?>" readonly>
								</div>
								<div class="cb-dash__field-action">
									<a class="cb-dash__btn cb-dash__btn--ghost" href="<?php echo esc_url( $preview_url ); ?>" target="_blank" rel="noopener">
										<?php esc_html_e( 'Preview banner', 'cookiebot' ); ?>
										<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-blue-24.svg' ) ); ?>" width="24" height="24" alt="">
										<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'cookiebot' ); ?></span>
									</a>
								</div>
							</div>

							<div class="cb-dash__switch-row">
								<input type="checkbox" class="cb-dash__switch" id="cb-dash-show-banner" role="switch"
									aria-describedby="cb-dash-show-banner-help"
									data-cb-banner-toggle <?php checked( $banner_enabled ); ?>>
								<label class="cb-dash__switch-label" for="cb-dash-show-banner">
									<span class="cb-dash__h4"><?php esc_html_e( 'Show cookie banner on this site', 'cookiebot' ); ?></span>
									<span class="cb-dash__small" id="cb-dash-show-banner-help"><?php esc_html_e( 'Turn off to hide the banner while you make changes. Visitors aren’t asked for consent when it’s off.', 'cookiebot' ); ?></span>
								</label>
							</div>

							<div class="cb-dash__notice cb-dash__notice--warning" role="status" data-cb-banner-off-notice <?php echo $banner_enabled ? 'hidden' : ''; ?>>
								<img src="<?php echo esc_url( asset_url( 'img/dashboard/warning-amber.svg' ) ); ?>" width="20" height="20" alt="">
								<p class="cb-dash__body">
									<?php
									printf(
										/* translators: %s: the site's domain, e.g. clientsite.com. */
										esc_html__( 'Your cookie banner is off. Visitors to %s won’t see it or be asked for consent until you turn it back on.', 'cookiebot' ),
										esc_html( $domain )
									);
									?>
								</p>
							</div>
						</div>

						<div class="cb-dash__connection-group<?php echo $cb_dash_not_set_up ? ' is-warning' : ''; ?>">
							<div class="cb-dash__readonly-row">
								<div class="cb-dash__field cb-dash__field--fixed">
									<label class="cb-dash__label" for="cb-dash-cbid">
										<?php
										if ( $cb_dash_is_settings_id ) {
											esc_html_e( 'Settings ID', 'cookiebot' );
										} else {
											esc_html_e( 'Cookiebot Domain Group ID', 'cookiebot' );
										}
										?>
									</label>
									<input type="text" class="cb-dash__input" id="cb-dash-cbid" value="<?php echo esc_attr( $cbid ); ?>" readonly
										aria-describedby="cb-dash-cbid-help">
									<?php if ( $cb_dash_not_set_up ) : ?>
										<div class="cb-dash__message" id="cb-dash-cbid-help">
											<img class="cb-dash__message-icon cb-dash__message-icon--warning" src="<?php echo esc_url( asset_url( 'img/dashboard/warning.svg' ) ); ?>" width="24" height="24" alt="">
											<span class="cb-dash__message-text">
												<?php
												printf(
													/* translators: %s: the site's domain, e.g. clientsite.com. */
													esc_html__( 'Your banner may not show on %s. This ID isn’t set up for this site in Cookiebot.', 'cookiebot' ),
													esc_html( $domain )
												);
												?>
											</span>
										</div>
									<?php elseif ( ! $is_network_id ) : ?>
										<p class="cb-dash__small" id="cb-dash-cbid-help"><?php esc_html_e( 'To connect a different ID, disconnect this one first.', 'cookiebot' ); ?></p>
									<?php endif; ?>
								</div>
								<?php if ( ! $is_network_id ) : ?>
									<div class="cb-dash__field-action">
										<button type="button" class="cb-dash__btn cb-dash__btn--ghost" data-cb-disconnect>
											<?php esc_html_e( 'Disconnect', 'cookiebot' ); ?>
										</button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if ( ! $cb_dash_is_settings_id ) : ?>
						<div class="cb-dash__connection-footer">
							<hr class="cb-dash__divider">
							<div class="cb-dash__footer-link">
								<img src="<?php echo esc_url( asset_url( 'img/dashboard/dashboard-customize.svg' ) ); ?>" width="20" height="20" alt="">
								<div class="cb-dash__footer-text">
									<p class="cb-dash__h4"><?php esc_html_e( 'Do more in Cookiebot', 'cookiebot' ); ?></p>
									<p class="cb-dash__body"><?php esc_html_e( 'Customize your banner and manage your plan in your Cookiebot account.', 'cookiebot' ); ?></p>
								</div>
								<a class="cb-dash__btn cb-dash__btn--ghost" href="https://admin.cookiebot.com/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin_dashboard&utm_content=do_more_card" target="_blank" rel="noopener">
									<?php esc_html_e( 'Go to Cookiebot', 'cookiebot' ); ?>
									<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-blue-24.svg' ) ); ?>" width="24" height="24" alt="">
									<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'cookiebot' ); ?></span>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</section>

				<hr class="cb-dash__divider">

				<section class="cb-dash__more">
					<h2 class="cb-dash__h2"><?php esc_html_e( 'More from Cookiebot', 'cookiebot' ); ?></h2>
					<div class="cb-dash__promo-cards">
						<div class="cb-dash__promo cb-dash__promo--partner">
							<div class="cb-dash__promo-text">
								<h3 class="cb-dash__h3"><?php esc_html_e( 'Join the Cookiebot Partner Program', 'cookiebot' ); ?></h3>
								<p class="cb-dash__body"><?php esc_html_e( 'Earn commission on every client site you set up with Cookiebot, plus partner perks and support.', 'cookiebot' ); ?></p>
							</div>
							<a class="cb-dash__btn cb-dash__btn--primary" href="https://www.cookiebot.com/en/wordpress-partner-program/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin_dashboard&utm_content=partner_card" target="_blank" rel="noopener">
								<?php esc_html_e( 'Become a Partner', 'cookiebot' ); ?>
								<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-white.svg' ) ); ?>" width="24" height="24" alt="">
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'cookiebot' ); ?></span>
							</a>
						</div>
						<div class="cb-dash__promo cb-dash__promo--review">
							<h3 class="cb-dash__h3"><?php esc_html_e( 'Your opinion matters', 'cookiebot' ); ?></h3>
							<p class="cb-dash__body"><?php esc_html_e( 'Are you happy with our WordPress plugin? Your feedback will help us make our product better for you.', 'cookiebot' ); ?></p>
							<a class="cb-dash__link cb-dash__link--underline" href="https://wordpress.org/support/plugin/cookiebot/reviews/#new-post" target="_blank" rel="noopener">
								<?php esc_html_e( 'Write a review', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</section>

				<p class="cb-dash__body cb-dash__help-line">
					<?php
					printf(
						/* translators: 1: "Watch the setup guide" link, 2: "Get support" link. */
						esc_html__( 'Need help? %1$s or %2$s', 'cookiebot' ),
						'<a class="cb-dash__link" href="https://www.youtube.com/watch?v=q8qDVa7Uv0M" target="_blank" rel="noopener">' . esc_html__( 'Watch the setup guide', 'cookiebot' ) . '</a>',
						'<a class="cb-dash__link" href="' . esc_url( $support_url ) . '">' . esc_html__( 'Get support', 'cookiebot' ) . '</a>'
					);
					?>
				</p>
			</div>
		</div>
	</div>
</div>

<?php if ( $just_connected ) : ?>
	<div class="cb-dash-toast" role="status" data-cb-toast>
		<img class="cb-dash-toast__check" src="<?php echo esc_url( asset_url( 'img/dashboard/toast-check.svg' ) ); ?>" width="18" height="14" alt="">
		<p class="cb-dash-toast__text">
			<?php
			printf(
				/* translators: %s: the site's domain, e.g. clientsite.com. */
				esc_html__( 'Connected. Your cookie banner is now set up on %s.', 'cookiebot' ),
				esc_html( $domain )
			);
			?>
		</p>
		<button type="button" class="cb-dash-toast__close" data-cb-toast-close>
			<img src="<?php echo esc_url( asset_url( 'img/dashboard/toast-close.svg' ) ); ?>" width="14" height="14" alt="">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'cookiebot' ); ?></span>
		</button>
	</div>
<?php endif; ?>
