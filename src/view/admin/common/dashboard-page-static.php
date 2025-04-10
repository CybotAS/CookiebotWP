<?php

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

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

		<div class="dashboard-grid has-cbid">
			<!-- Main Content Area -->
			<div class="dashboard-grid-row">
				<div class="gray-box">
					<div class="header-section-no-margin">
						<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/set-up-icon.svg' ); ?>" alt="Usercentrics Logo">
						<h1><?php echo \esc_html__( 'Your Consent Management Platform', 'cookiebot' ); ?></h1>
					</div>
					<div class="header-section">
						<p class="subtitle">
							<?php echo esc_html__( 'Set up your consent banner in seconds with easy auto-setup, smart data processing services detection, and consent-first blocking for automated privacy compliance.', 'cookiebot' ); ?>
						</p>
					</div>

					<div class="banner-preview-container">
						<div class="banner-images">
							<?php
							$banner1_url = CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/banner-getting-started1.png';
							$banner2_url = CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/banner-getting-started2.png';
							?>
							<img src="<?php echo \esc_url( $banner1_url ); ?>"
								alt="Banner Preview 1"
								class="banner-image">
							<img src="<?php echo \esc_url( $banner2_url ); ?>"
								alt="Banner Preview 2"
								class="banner-image">
						</div>
					</div>  

					<div class="banner-actions">
						<button class="cb-btn cb-primary-btn" id="get-started-button-static-dashboard">
							<?php echo esc_html__( 'Login to continue', 'cookiebot' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
