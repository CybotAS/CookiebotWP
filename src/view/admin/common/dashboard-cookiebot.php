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
		<div class="cb-main__content">
			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card__cookiebot">
					<div class="cb-main__card__inner account_card">
						<img src="<?php echo esc_html( $cb_wp ); ?>" alt="Cookiebot for WordPress" class="cb-wp">
						<div class="cb-main__card__content">
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'I already have a Cookiebot CMP account', 'cookiebot' ); ?>
							</h2>
							<a href="/wp-admin/admin.php?page=<?php echo esc_html( Settings_Page::ADMIN_SLUG ); ?>"
							   class="cb-btn cb-main-btn">
								<?php echo esc_html__( 'Connect my existing account', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="cb-main__dashboard__card__cookiebot">
					<div class="cb-main__card__inner new_card">
						<div class="cb-main__card__content">
							<p class="cb-main__card__label">
								<?php echo esc_html__( 'Get started', 'cookiebot' ); ?>
							</p>
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'Create a new Cookiebot CMP account', 'cookiebot' ); ?>
							</h2>
							<a href="https://admin.cookiebot.com/en/signup/?utm_source=wordpress&utm_medium=organic&utm_campaign=banner"
								target="_blank" class="cb-btn cb-white-btn" rel="noopener">
								<?php echo esc_html__( 'Create a new account', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>
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
