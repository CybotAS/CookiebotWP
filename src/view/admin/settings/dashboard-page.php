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

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>
		<div class="cb-main__content <?php echo $cbid ? 'sync-account' : ''; ?>">
			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner <?php echo $cbid ? 'start_card' : 'account_card'; ?>">
						<?php if ( ! $cbid ) : ?>
							<img src="<?php echo esc_html( $cb_wp ); ?>" alt="Cookiebot for WordPress" class="cb-wp">
							<div class="cb-main__card__content">
								<h2 class="cb-main__card__title">
									<?php echo esc_html__( 'I already have a Cookiebot CMP account', 'cookiebot' ); ?>
								</h2>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Settings_Page::ADMIN_SLUG ) ); ?>"
								   class="cb-btn cb-main-btn">
									<?php echo esc_html__( 'Connect my existing account', 'cookiebot' ); ?>
								</a>
							</div>
						<?php else : ?>
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'Your Cookiebot CMP for WordPress solution', 'cookiebot' ); ?>
							</h2>
							<div class="cb-main__card__success">
								<div class="cb-btn cb-success-btn">
									<img src="<?php echo esc_html( $check_icon ); ?>" alt="Check">
									<?php echo esc_html__( 'Congratulations!', 'cookiebot' ); ?>
								</div>
								<p class="cb-main__success__text">
									<?php echo esc_html__( 'You have added your Domain Group ID to WordPress. You are all set!', 'cookiebot' ); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $cbid ) : ?>
					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner  <?php echo $cbid ? 'start_card' : 'new_card'; ?>">
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
				<?php endif; ?>

				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner  <?php echo $cbid ? 'start_card' : 'new_card'; ?>">
						<?php if ( ! $cbid ) : ?>
							<div class="cb-main__card__content">
								<p class="cb-main__card__label">
									<?php echo esc_html__( 'Get started', 'cookiebot' ); ?>
								</p>
								<h2 class="cb-main__card__title">
									<?php echo esc_html__( 'Create a new Cookiebot CMP account', 'cookiebot' ); ?>
								</h2>
								<a href="https://manage.cookiebot.com/en/signup/?utm_source=wordpress&utm_medium=organic&utm_campaign=banner"
								   target="_blank" class="cb-btn cb-white-btn" rel="noopener">
									<?php echo esc_html__( 'Create a new account', 'cookiebot' ); ?>
								</a>
							</div>
						<?php else : ?>
							<h3 class="cb-main__card__subtitle">
								<?php echo esc_html__( 'Learn more about how to optimize your Cookiebot CMP setup?', 'cookiebot' ); ?>
							</h3>
							<a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-link-btn"
							   rel="noopener">
								<?php echo esc_html__( 'Visit Help Center', 'cookiebot' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner start_card">
						<div class="cb-main__video">
							<iframe src="https://www.youtube.com/embed/eSVFnjoMKFk"
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
								<?php echo esc_html__( 'Learn More', 'cookiebot' ); ?>
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
								<?php echo esc_html__( 'Learn More', 'cookiebot' ); ?>
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
