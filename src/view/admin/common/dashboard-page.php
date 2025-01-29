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
		<div class="cb-main__content <?php echo $cbid ? 'sync-account' : 'no-account'; ?>">
			<div class="cb-main__dashboard__card--container">
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner account_card">
						<div class="cb-main__card__content">
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'I already have an account', 'cookiebot' ); ?>
							</h2>
							<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
								class="cb-btn cb-main-btn">
								<?php echo esc_html__( 'Connect my account', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner  <?php echo $cbid ? 'start_card' : 'new_card'; ?>">
						<div class="cb-main__card__content">
							<p class="cb-main__card__label">
								<?php echo esc_html__( 'Get started', 'cookiebot' ); ?>
							</p>
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'New to our solutions? Create your account. ', 'cookiebot' ); ?>
							</h2>
							<a href="https://account.usercentrics.eu/?trial=standard&uc_subscription_type=web&pricing_plan=FreeExtended&utm_source=wordpress&utm_medium=referral&utm_campaign=banner"
								target="_blank" class="cb-btn cb-white-btn" rel="noopener">
								<?php echo esc_html__( 'Create a new account', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="cb-main__dashboard__card--container-full">
				<div class="cb-main__dashboard__card">
					<div class="cb-main__card__inner start_card">
						<div class="cb-main__card--content">
							<h2 class="cb-main__card__title">
								<?php echo esc_html__( 'How to get started', 'cookiebot' ); ?>
							</h2>
							<a href="https://support.cookiebot.com/hc/en-us/articles/360003784174-Installing-Cookiebot-CMP-on-WordPress"
								target="_blank" class="cb-btn cb-link-btn" rel="noopener">
								<?php echo esc_html__( 'Learn more about your CMP', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				</div>
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
							<a href="https://support.cookiebot.com/hc/en-us/articles/360010952259-Using-Cookiebot-CMP-for-CCPA-CPRA-compliance" target="_blank"
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
