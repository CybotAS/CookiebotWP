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
						<h2 class="cb-main__card__title">
							<?php echo esc_html__( 'Welcome to Usercentrics Cookiebot WordPress Plugin', 'cookiebot' ); ?>
						</h2>
						<div class="cb-main__card__success">
							<div class="cb-btn cb-success-btn">
								<img src="<?php echo esc_html( $check_icon ); ?>" alt="Check">
								<?php echo esc_html__( 'Account added', 'cookiebot' ); ?>
							</div>
							<p class="cb-main__success__text">
								<?php echo esc_html__( 'You’ve added your settings ID to your Usercentrics Cookiebot WordPress Plugin.', 'cookiebot' ); ?>
							</p>
						</div>
					</div>
				</div>

				<?php if ( $cbid ) : ?>
					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner  <?php echo $cbid ? 'start_card' : 'new_card'; ?>">
							<h3 class="cb-main__card__subtitle">
								<?php echo esc_html__( 'Your opinion matters', 'cookiebot' ); ?>
							</h3>
							<p class="cb-main__review__text">
								<?php echo esc_html__( 'Are you happy with Usercentrics Cookiebot WordPress Plugin? Your feedback helps us improve it.', 'cookiebot' ); ?>
							</p>
							<a href="https://wordpress.org/support/plugin/cookiebot/reviews/#new-post" target="_blank"
								class="cb-btn cb-link-btn" rel="noopener">
								<?php echo esc_html__( 'Share feedback', 'cookiebot' ); ?>
							</a>
						</div>
					</div>

					<div class="cb-main__dashboard__card">
						<div class="cb-main__card__inner start_card">
							<div class="cb-main__card--content">
								<h3 class="cb-main__card__subtitle">
									<?php echo esc_html__( 'How to set up Usercentrics Cookiebot WordPress Plugin', 'cookiebot' ); ?>
								</h3>
								<a href="https://support.cookiebot.com/hc/en-us/articles/4408356523282-Getting-started"
									target="_blank" class="cb-btn cb-link-btn" rel="noopener">
									<?php echo esc_html__( 'Learn more', 'cookiebot' ); ?>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card">
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
							<a href="https://support.cookiebot.com/hc/en-us/articles/4416376763922-Using-Cookiebot-for-GDPR-compliance" target="_blank"
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
							<a href="https://support.cookiebot.com/hc/en-us/articles/360010952259-Using-Cookiebot-CMP-for-CCPA-CPRA-compliance" target="_blank"
								class="cb-btn cb-link-btn external-icon legislation-link" rel="noopener">
								<span><?php echo esc_html__( 'Learn More', 'cookiebot' ); ?></span>
								<img src="<?php echo esc_html( $link_icon ); ?>"
									alt="<?php echo esc_html__( 'Learn More', 'cookiebot' ); ?>">
							</a>
						</div>
						<a href="https://support.cookiebot.com/hc/en-us/categories/360000349934-Regulations" target="_blank"
							class="cb-btn cb-link-btn cb-right-btn" rel="noopener">
							<?php echo esc_html__( 'See other legislations', 'cookiebot' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
