<?php

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
use cybot\cookiebot\settings\pages\Settings_Page;

/**
 * @var array $template_args Array containing all template variables
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>


<div class="cb-body">

	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>

		<div class="dashboard-grid">

			<!-- Main Content Area -->
			 <div class="dashboard-grid-row">
				<div class="gray-box">
					<div class="header-section-no-margin">
						<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/set-up-icon.svg' ); ?>" alt="Usercentrics Logo">
						<h1><?php echo \esc_html__( 'Set up your cookie banner', 'cookiebot' ); ?></h1>
					</div>
					<div class="header-section">
						<p class="subtitle">
								<?php echo esc_html__( 'Get your site GDPR-compliant in ', 'cookiebot' ); ?>
								<strong><?php echo esc_html__( 'just a few clicks.', 'cookiebot' ); ?></strong>
								<?php echo esc_html__( 'Enter your email, verify it, and create your password.', 'cookiebot' ); ?>
						</p>
					</div>
	
	
					<!-- Steps Container -->
					<div class="steps-container">
						<!-- Activate your banner step -->
						<div class="step-box">
							<div class="step-row">
		
								<div class="step-icon">
									<div class="empty-circle"></div>
								</div>
								<div class="step-content">
									<h2><?php echo \esc_html__( 'Get your banner live in seconds', 'cookiebot' ); ?></h2>
								</div>	
							</div>
	
								<div class="banner-preview-container">
									<div class="divider"></div>
									<p class="step-description">
										• Instant setup & automatic cookie blocking<br>
										• 14 days of all-access premium features (no card needed)<br>
										• Keep it live afterwards on our Free plan or upgrade any time
									</p>
									<div class="banner-images">
										<?php
										$banner1_url = CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/banner-getting-started1.png';
										$banner2_url = CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/banner-getting-started2.png';
										$arrow_url   = CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/banner-arrow.png';
										?>
										<img src="<?php echo \esc_url( $banner1_url ); ?>"
											alt="Banner Preview 1"
											class="banner-image">
										<img src="<?php echo \esc_url( $banner2_url ); ?>"
											alt="Banner Preview 2"
											class="banner-image">
									</div>
									<div class="activate-container">
										<button id="get-started-button-static-dashboard" class="cb-btn cb-primary-btn cb-get-started-btn">
											<?php echo esc_html__( 'Get Started', 'cookiebot' ); ?>
										</button>
										<img src="<?php echo \esc_url( $arrow_url ); ?>"
											alt="arrow"
											class="banner-arrow">
									</div>	
								</div>
						</div>
	
					</div>
				</div>	
			 </div>
		</div>
	</div>
</div>
