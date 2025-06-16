<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * @var array $template_args Array containing all template variables
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>

<div class="cb-body">
<div class="banner-container">
		<!-- Trial expiration notice -->
			<div class="header-top-banners trial-expired-banner">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/clock-icon.svg' ); ?>"
						alt="Clock Icon">
					<div>
						<h3><?php echo esc_html__( 'Your premium trial is over', 'cookiebot' ); ?></h3>
						<p style="text-wrap: nowrap;">
							<?php echo esc_html__( 'To stay compliant, reactivate your banner by choosing a plan. Start with Free or unlock more features by upgrading.', 'cookiebot' ); ?>
						</p>
					</div>
				</div>
				<div class="upgrade-expired-trial">
					<a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" style="text-decoration: none; color: inherit;">
						<h3><?php echo esc_html__( 'Reactivate banner', 'cookiebot' ); ?> <span class="upgrade-chevron">&rsaquo;</span></h3>
					</a>
				</div>
			</div>
	</div>
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>

		<div class="dashboard-grid">
			<!-- Main Content Area -->
			<div class="dashboard-grid-row">
				<div class="gray-box">
					<div class="header-section-no-margin">
						<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/set-up-icon.svg' ); ?>" alt="Setup Icon">
						<h1><?php echo \esc_html__( 'Set up your cookie banner', 'cookiebot' ); ?></h1>
					</div>

					<!-- Steps Container -->
					<div class="steps-container" style="padding-top: 1em;">
						<!-- Reactivate banner step -->
						<div class="step-box">
							<div class="step-row">
								<div class="step-icon">
									<div class="empty-circle"></div>
								</div>
								<div class="step-content">
									<h2><?php echo \esc_html__( 'Reactivate your banner', 'cookiebot' ); ?></h2>
								</div>
								<span class="to-do-status">To do</span>
							</div>
						</div>

						<!-- Scan website step -->
						<div class="step-box">
							<div class="step-row">
								<div class="step-icon">
									<div class="empty-circle"></div>
								</div>
								<div class="step-content">
									<h2><?php echo \esc_html__( 'Scan your website', 'cookiebot' ); ?></h2>
								</div>
							</div>
						</div>

						<!-- Upgrade your plan step -->
						<div class="step-box">
							<div class="step-row">
								<div class="step-icon">
									<div class="empty-circle"></div>
								</div>
								<div class="step-content">
									<h2><?php echo \esc_html__( 'Upgrade your plan', 'cookiebot' ); ?></h2>
								</div>
								<div class="step-status">
									<div class="lightning-badge">
										<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M7.58333 0.583344L1.75 8.16668H7L6.41667 13.4167L12.25 5.83334H7L7.58333 0.583344Z" fill="#0047FF"/>
										</svg>
									</div>
								</div>
							</div>

							<div class="upgrade-details">
								<div class="divider"></div>
								<p class="upgrade-intro">Upgrade to unlock these premium benefits:</p>

								<ul class="upgrade-features">
									<li><strong>Match your consent banner to your brand</strong> with advanced customization options.</li>
									<li><strong>Adapt your banner to increase opt-ins</strong> using our consent analytics data.</li>
									<li><strong>Benefit from higher session limits</strong> and maintain privacy compliance as your traffic grows.</li>
								</ul>

								<p class="ready-text">Ready to take your consent experience to the next level?</p>

								<div class="upgrade-container">
									<button id="upgrade-now-button" class="cb-btn cb-primary-btn" onclick="window.open('https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage', '_blank')">
										<?php echo esc_html__( 'Choose my plan', 'cookiebot' ); ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Right Side - Banner Control -->
				<div class="gray-box-overview">
					<div class="header-section">
						<div>
							<div class="top-row">
								<span class="banner-inactive-badge">Banner inactive</span>
								<span class="trial-ended-badge">Trial ended</span>
							</div>
							<h1><?php echo esc_html__( 'Banner control', 'cookiebot' ); ?></h1>
							<p class="subtitle"><?php echo esc_html__( 'Your banner is currently inactive. Reactivate it below to keep your site compliant.', 'cookiebot' ); ?></p>
						</div>
					</div>

					<div class="banner-actions">
						<button class="cb-btn cb-primary-btn" onclick="window.open('https://account.usercentrics.eu/subscription/manage', '_blank')">
							<?php echo esc_html__( 'Reactivate banner', 'cookiebot' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.to-do-status {
	background-color: #FEF3C7;
	color: #92400E;
	padding: 0.375rem 0.75rem;
	border-radius: 0.375rem;
	font-size: 0.875rem;
	font-weight: 500;
	margin-left: auto;
}

.banner-inactive-badge {
	background-color: #9F1818;
	color: white;
	padding: 0.375rem 0.75rem;
	border-radius: 0.375rem;
	font-size: 0.875rem;
	font-weight: 500;
}

.trial-ended-badge {
	background-color: white;
	color: #1032CF;
	border: 1px solid #1032CF;
	padding: 0.375rem 0.75rem;
	border-radius: 1rem;
	font-size: 0.875rem;
	font-weight: 500;
}
</style>
