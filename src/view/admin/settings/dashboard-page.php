<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\Settings_Page;

/**
 * @var string $cbid
 * @var string $cb_wp
 * @var string $europe_icon
 * @var string $usa_icon
 * @var string $check_icon
 * @var string $link_icon
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$today     = new DateTime( 'now' );
$end_date  = new DateTime( '2024-12-03' );
$remaining = $today->diff( $end_date );
$days_left = $remaining->format( '%d' );

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>
		<div class="cb-main__content <?php echo $cbid ? 'sync-account' : ''; ?>">
			<?php
			if ( ! $cbid ) :
				if ( $today < $end_date ) :
					?>
					<div class="cb-main__dashboard__promo">
						<div class="cb-main__dashboard__promo--inner">
							<div class="cb-main__dashboard__promo--content">
							<div class="cb-dashboard__promo--label"><div class="icon"></div>Black Friday Deal</div>
							<h2 class="cb-dashboard__promo--title">Get <div class="highlight">10% off</div> for 6 months</h2>
							<p class="promo-condition">Enjoy a free plan for sites with up to 50 subpages. Premium plans are available for sites with 50+ subpages. Start with a 14-day trial, then enjoy a 10% discount on your selected plan for the first 6 months. After that, pricing will revert to the regular rate. This offer is available for new users who sign up between November 20 and December 2, 2024.</p>
							</div>
							<div class="cb-main__dashboard__promo--banner">
								<img src="<?php echo esc_html( CYBOT_COOKIEBOT_PLUGIN_URL . '/assets/img/extra/cb_bf_banner.svg' ); ?>" alt="CB BF banner">
							</div>
						</div>
					</div>
					<?php
				endif;
			endif;
			?>
			<div class="cb-main__dashboard__card--container">
				<?php if ( ! $cbid ) : ?>
					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner new_card">
							<div class="cb-main__card__content">
								<h2 class="cb-main__card__title">
									<?php echo esc_html__( 'Create a new Cookiebot CMP account', 'cookiebot' ); ?>
								</h2>
								<?php if ( $today < $end_date ) : ?>
								<div class="cb-bf-counter">
									<div class="cb-bf-counter-label">-10%</div>
									<div class="cb-bf-counter-number"><?php echo esc_html( $days_left ); ?></div>
									<div class="cb-bf-counter-last"><?php echo $days_left === '1' ? esc_html( 'day' ) : esc_html( 'days' ); ?> to go</div>
								</div>
									<a href="https://admin.cookiebot.com/signup?coupon=BFRIDAYWP10&utm_source=wordpress&utm_medium=referral&utm_campaign=banner"
										target="_blank" class="cb-btn cb-main-btn" rel="noopener">
										<?php echo esc_html__( 'Create a new account', 'cookiebot' ); ?>
									</a>
								<?php else : ?>
								<a href="https://admin.cookiebot.com/signup/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner"
									target="_blank" class="cb-btn cb-main-btn" rel="noopener">
									<?php echo esc_html__( 'Create a new account', 'cookiebot' ); ?>
								</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner account_card">
							<div class="cb-main__card__content">
								<h2 class="cb-main__card__title">
									<?php echo esc_html__( 'I already have a Cookiebot CMP account', 'cookiebot' ); ?>
								</h2>
								<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
									class="cb-btn cb-main-btn">
									<?php echo esc_html__( 'Connect my existing account', 'cookiebot' ); ?>
								</a>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner start_card">
								<h2 class="cb-main__card__title">
									<?php echo esc_html__( 'Your Cookiebot CMP for WordPress solution', 'cookiebot' ); ?>
								</h2>
								<div class="cb-main__card__success">
									<div class="cb-btn cb-success-btn">
										<img src="<?php echo esc_html( $check_icon ); ?>" alt="Check">
									</div>
									<p class="cb-main__success__text">
										<span><?php echo esc_html__( 'Congratulations!', 'cookiebot' ); ?></span>
										<?php echo esc_html__( 'You have added your Domain Group ID to WordPress. You are all set!', 'cookiebot' ); ?>
									</p>
								</div>
						</div>
					</div>

					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner start_card">
							<h3 class="cb-main__card__subtitle">
								<?php echo esc_html__( 'Your opinion matters', 'cookiebot' ); ?>
							</h3>
							<p class="cb-main__review__text">
								<?php echo esc_html__( 'Are you happy with our WordPress plugin? Your feedback will help us make our product better for you.', 'cookiebot' ); ?>
							</p>
							<a href="https://wordpress.org/support/plugin/cookiebot/reviews/#new-post" target="_blank"
								class="cb-btn cb-link-btn" rel="noopener">
								<?php echo esc_html__( 'Write a review', 'cookiebot' ); ?>
							</a>
						</div>
					</div>

					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner start_card">
							<h3 class="cb-main__card__subtitle">
								<?php echo esc_html__( 'Learn more about how to optimize your Cookiebot CMP setup?', 'cookiebot' ); ?>
							</h3>
							<a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-link-btn"
								rel="noopener">
								<?php echo esc_html__( 'Visit Help Center', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner start_card">
						<div class="cb-main__video">
							<iframe src="https://www.youtube.com/embed/1-lvuJa42P0"
									title="Cookiebot WordPress Installation"
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
									allowfullscreen></iframe>
						</div>
						<div class="cb-main__card--content">
							<p class="cb-main__card__label"><?php echo esc_html__( 'Video guide', 'cookiebot' ); ?></p>
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'How to get started with Cookiebot CMP', 'cookiebot' ); ?>
							</h2>
							<a href="https://support.cookiebot.com/hc/en-us/articles/4408356523282-Getting-started"
								target="_blank" class="cb-btn cb-link-btn" rel="noopener">
								<?php echo esc_html__( 'Learn more about Cookiebot CMP', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner legislations_card">
						<div class="cb-main__legislation__item">
							<div class="cb-main__legislation____icon">
								<img src="<?php echo esc_html( $europe_icon ); ?>" alt="GDPR">
							</div>
							<div class="cb-main__legislation__name">
								<?php echo esc_html__( 'GDPR', 'cookiebot' ); ?>
							</div>
							<div class="cb-main__legislation__region">
								<?php echo esc_html__( 'Europe', 'cookiebot' ); ?>
							</div>
							<a href="https://www.cookiebot.com/en/gdpr/" target="_blank"
								class="cb-btn cb-link-btn external-icon legislation-link" rel="noopener">
								<span><?php echo esc_html__( 'Learn More', 'cookiebot' ); ?></span>
								<img src="<?php echo esc_html( $link_icon ); ?>"
									alt="<?php echo esc_html__( 'Learn More', 'cookiebot' ); ?>">
							</a>
						</div>
						<div class="cb-main__legislation__item">
							<div class="cb-main__legislation____icon">
								<img src="<?php echo esc_html( $usa_icon ); ?>" alt="CCPA">
							</div>
							<div class="cb-main__legislation__name">
								<?php echo esc_html__( 'CCPA', 'cookiebot' ); ?>
							</div>
							<div class="cb-main__legislation__region">
								<?php echo esc_html__( 'North America', 'cookiebot' ); ?>
							</div>
							<a href="https://www.cookiebot.com/en/what-is-ccpa/" target="_blank"
								class="cb-btn cb-link-btn external-icon legislation-link" rel="noopener">
								<span><?php echo esc_html__( 'Learn More', 'cookiebot' ); ?></span>
								<img src="<?php echo esc_html( $link_icon ); ?>"
									alt="<?php echo esc_html__( 'Learn More', 'cookiebot' ); ?>">
							</a>
						</div>
						<a href="https://www.cookiebot.com/en/blog/" target="_blank"
							class="cb-btn cb-link-btn cb-right-btn" rel="noopener">
							<?php echo esc_html__( 'See other legislations', 'cookiebot' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
