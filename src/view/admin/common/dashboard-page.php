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

	<!-- Banner container for proper alignment -->
	<div class="banner-container">
		<?php if ( Cookiebot_WP::get_subscription_type() === 'Free' ) : ?>
			<div class="header-top-banners free-plan-banner">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/bolt.svg' ); ?>" alt="Bolt Icon">
					<div>
						<h3><?php echo esc_html__( "You're on the Free plan. Upgrade now to unlock your full site experience.", 'cookiebot' ); ?></h3>
						<p><?php echo esc_html__( 'Upgrade for higher session limits, custom branding, and features that help you deliver a better experience on your site.', 'cookiebot' ); ?></p>
					</div>
				</div>
				<div class="upgrade-free-plan">
					<a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" class="upgrade-now-link">
						<h3><?php echo esc_html__( 'Upgrade now', 'cookiebot' ); ?> <span class="upgrade-chevron">&rsaquo;</span></h3>
					</a>
				</div>
			</div>
		<?php endif; ?>
		<!-- Trial expiration notice -->
		<?php
		$trial_expired = Cookiebot_WP::is_trial_expired();
		$upgraded      = Cookiebot_WP::has_upgraded();

		if ( $trial_expired && ! $upgraded ) :
			?>
			<div class="header-top-banners trial-expired-banner">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/clock-icon.svg' ); ?>"
						alt="Clock Icon">
					<div>
						<h3><?php echo esc_html__( 'Your premium trial is over', 'cookiebot' ); ?></h3>
						<p>
							<?php echo esc_html__( 'Reactive your banner to regain full access to your account and display the cookie banner on your website.', 'cookiebot' ); ?>
						</p>
					</div>
				</div>
				<div class="upgrade-expired-trial">
					<a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" style="text-decoration: none; color: inherit;">
						<h3><?php echo esc_html__( 'Reactivate banner', 'cookiebot' ); ?> <span class="upgrade-chevron">&rsaquo;</span></h3>
					</a>
				</div>
			</div>
		<?php endif; ?>

		<!-- Banner is live notice -->
		<?php
		$banner_dismissed = get_option( 'cookiebot_banner_live_dismissed', false );

		if ( ! empty( $template_args['cbid'] ) && ! empty( $template_args['user_data'] ) && ! $banner_dismissed && ! Cookiebot_WP::has_upgraded() ) :
			?>
			<div class="header-top-banners banner-live-banner" id="banner-live-notice">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-white.svg' ); ?>"
						alt="Check Icon">
					<div>

						<!-- <h3>Well done! Your <a href="<?php echo esc_url( site_url() ); ?>" target="_blank" class="banner-live-link" onclick="window.trackAmplitudeEvent('Banner Live Viewed', { settingsId: '<?php echo esc_js( $template_args['cbid'] ); ?>' });">banner is live</a>.</h3> -->
						<h3>Well done! Your <a href="<?php echo esc_url( site_url() ); ?>" target="_blank" class="banner-live-link">banner is live</a>.</h3>
						<p>
							<?php
							echo esc_html__( 'Choose your plan to stay live: pick our Free plan or upgrade to Premium for full control.', 'cookiebot' );
							?>
							<!-- <a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" style="text-decoration: underline; color: inherit;" onclick="window.trackAmplitudeEvent('Choose Plan Link Clicked', { price_plan: '<?php echo esc_js( $template_args['user_data']['subscriptions']['active']['price_plan'] ? $template_args['user_data']['subscriptions']['active']['price_plan'] : '' ); ?>', account_id: '<?php echo esc_js( $template_args['cbid'] ); ?>' });"><?php echo esc_html__( 'Choose plan', 'cookiebot' ); ?></a> -->
							<a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" style="text-decoration: underline; color: inherit;">
								<?php echo esc_html__( 'Choose plan', 'cookiebot' ); ?>
							</a>
						</p>
					</div>
				</div>
				<button class="banner-close-btn" aria-label="Close banner" id="banner-close-btn">
					<span aria-hidden="true">×</span>
				</button>
			</div>
		<?php endif; ?>


	</div>

	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>

		<div class="dashboard-grid <?php echo ! empty( $template_args['cbid'] ) ? 'has-cbid' : ''; ?>">

			<!-- Main Content Area -->
			<div class="dashboard-grid-row">
				<div class="gray-box">
					<div class="header-section-no-margin">
						<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/set-up-icon.svg' ); ?>" alt="Usercentrics Logo">
						<h1><?php echo \esc_html__( 'Set up your cookie banner', 'cookiebot' ); ?></h1>
					</div>
					<div class="header-section">
						<?php if ( empty( $template_args['user_data'] ) ) : ?>
						<p class="subtitle">
							<?php echo esc_html__( 'Get your site GDPR-compliant in', 'cookiebot' ); ?>
							<strong><?php echo esc_html__( 'just a few clicks.', 'cookiebot' ); ?></strong>
							<?php echo esc_html__( 'Enter your email, verify it, and create your password.', 'cookiebot' ); ?>
						</p>
						<?php endif; ?>
					</div>

					<!-- Steps Container -->
					<div class="steps-container">
						<!-- Activate your banner step -->
						<div class="step-box <?php echo ! empty( $template_args['cbid'] ) ? 'completed' : ''; ?>">
							<div class="step-row">
								<?php if ( ! empty( $template_args['cbid'] ) && ! empty( $template_args['user_data'] ) ) : ?>
									<div class="step-icon">
										<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-mark.svg' ); ?>" alt="Checkmark">
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Activate your banner', 'cookiebot' ); ?></h2>
									</div>
									<span class="done-status">Done!</span>
								<?php else : ?>
									<div class="step-icon">
										<div class="empty-circle"></div>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Get your banner live in seconds', 'cookiebot' ); ?></h2>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( empty( $template_args['user_data'] ) || empty( $template_args['cbid'] ) && ! empty( $template_args['scan_id'] ) ) : ?>
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
										<button id="get-started-button" class="cb-btn cb-primary-btn cb-get-started-btn">
											<?php echo esc_html__( 'Activate free banner', 'cookiebot' ); ?>
										</button>
										<img src="<?php echo \esc_url( $arrow_url ); ?>"
											alt="arrow"
											class="banner-arrow">
									</div>

									<div>
										<div class="cb-general__info__text">
											<span class="note-text">Already have a Cookiebot or Usercentrics account?</span>

											<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
												class="note-link">
												<span><?php esc_html_e( 'Connect account', 'cookiebot' ); ?></span>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!-- Scan website step - only show when CBID exists -->
						<?php if ( ! empty( $template_args['user_data'] ) && ! empty( $template_args['cbid'] ) ) : ?>
							<div class="step-box">
								<div class="step-row">
									<div class="step-icon">
										<?php if ( $template_args['scan_status'] === 'DONE' ) : ?>
											<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-mark.svg' ); ?>" alt="Checkmark">
										<?php else : ?>
											<div class="empty-circle"></div>
										<?php endif; ?>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Scan your website', 'cookiebot' ); ?></h2>
									</div>
									<div class="step-status">
										<?php
										switch ( $template_args['scan_status'] ) {
											case 'IN_PROGRESS':
												?>
												<span class="in-progress-status">
													<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/clock-icon.svg' ); ?>" alt="Clock Icon">
													In Progress
												</span>
												<?php
												break;
											case 'DONE':
												?>
												<span class="done-status">Done!</span>
												<?php
												break;
											default:
												?>
												<span class="failed-status">
													Failed
												</span>
												<?php
										}
										?>
										<?php if ( $template_args['scan_status'] !== 'DONE' ) : ?>
											<button class="expand-toggle" aria-expanded="false" aria-controls="scan-details">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="arrow-icon">
													<path d="M7 10l5 5 5-5z" fill="#6B7280" />
												</svg>
											</button>
										<?php endif; ?>
									</div>
								</div>

								<!-- Scan details section (initially hidden) -->
								<?php if ( $template_args['scan_status'] !== 'DONE' ) : ?>
									<div id="scan-details" class="scan-details" style="display: none;">
										<div class="divider"></div>
										<p class="step-message">
											<?php if ( $template_args['scan_status'] !== 'IN_PROGRESS' ) : ?>
												Oops! We couldn't start or complete your scan! Try initiating a scan manually via the <a href="https://admin.usercentrics.eu/#/v3/service-settings/dps-scanner?settingsId=<?php echo esc_attr( $template_args['cbid'] ); ?>" target="_blank">Admin Interface</a>.
											<?php else : ?>
												We're scanning your website for data processing services. They should appear in 10 minutes, but it may take up to 24 hours. For more information, please review your <a href="https://admin.usercentrics.eu/#/v3/service-settings/dps-scanner?settingsId=<?php echo esc_attr( $template_args['cbid'] ); ?>" target="_blank">service settings</a>.
											<?php endif; ?>
										</p>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<!-- Upgrade your plan step -->
						<?php if ( ! empty( $template_args['user_data'] ) && ! empty( $template_args['cbid'] ) ) : ?>
							<div class="step-box">
								<div class="step-row">
									<div class="step-icon">
										<?php
										$is_upgraded       = Cookiebot_WP::has_upgraded();
										$subscription_type = Cookiebot_WP::get_subscription_type();
										if ( $is_upgraded && $subscription_type !== 'Free' ) :
											?>
											<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-mark.svg' ); ?>" alt="Checkmark">
										<?php else : ?>
											<div class="empty-circle"></div>
										<?php endif; ?>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Choose your plan', 'cookiebot' ); ?></h2>
									</div>
									<div class="step-status">
										<div class="lightning-badge">
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.58333 0.583344L1.75 8.16668H7L6.41667 13.4167L12.25 5.83334H7L7.58333 0.583344Z" fill="#0047FF" />
											</svg>
										</div>
									</div>
								</div>

								<!-- Upgrade details section -->
								<?php if ( $is_upgraded && $subscription_type !== 'Free' ) : ?>
									<div class="upgrade-details">
										<div class="divider"></div>
										<div class="subscription-info">
											<div>
												<div class="upgrade-header">
													<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/celebration.svg' ); ?>" alt="Celebration" class="celebration-icon">
													<h3>You've upgraded to <span class="plan-name"><?php echo esc_html( $template_args['subscription'] ); ?></span>!</h3>
												</div>
												<?php if ( isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ) : ?>
													<a href="https://account.usercentrics.eu/subscription/<?php echo esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ); ?>/manage" class="manage-features-link" target="_blank">
													<?php else : ?>
														<a href="https://account.usercentrics.eu/subscription/manage" class="manage-features-link" target="_blank">
														<?php endif; ?>
														<p>Manage advanced features</p>
														</a>
											</div>
											<?php if ( isset( $template_args['user_data']['subscriptions']['active']['next_billing_date'] ) ) : ?>
												<div class="billing-date">
													<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/calendar.svg' ); ?>" alt="Calendar" class="calendar-icon">
													<h3><?php echo esc_html__( 'Next billing date: ', 'cookiebot' ) . esc_html( gmdate( 'd/m/Y', strtotime( $template_args['user_data']['subscriptions']['active']['next_billing_date'] ) ) ); ?></h3>
												</div>
											<?php endif; ?>
											<div class="manage-subscription">
												<button id="manage-subscription-button" class="cb-btn cb-primary-btn" onclick="window.open('https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage', '_blank')">
													<?php echo esc_html__( 'Manage subscription', 'cookiebot' ); ?>
												</button>
											</div>
										</div>
									</div>
								<?php else : ?>
									<div class="upgrade-details">
										<div class="divider"></div>
										<div class="trial-countdown">
											<?php if ( Cookiebot_WP::is_in_trial() && ! $trial_expired ) : ?>
												<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/clock-icon-blue.svg' ); ?>" alt="Clock Icon">
												<p class="step-message"><strong>Enjoy all Premium features for</strong> <span class="days-highlight"><?php echo absint( Cookiebot_WP::get_trial_days_left() ); ?> days</span>.</p>
											<?php endif; ?>
										</div>

										<p class="step-message">Remember to choose a Free or Premium plan to keep your banner live. Premium includes:</p>

										<ul class="upgrade-features">
											<li><strong>Higher session limits</strong> for growing traffic</li>
											<li><strong>Custom branding</strong> for a seamless site experience</li>
											<li><strong>Better insights</strong> to optimize your opt-ins</li>
										</ul>

										<div class="upgrade-container">
											<button id="upgrade-now-button" class="cb-btn cb-primary-btn" onclick="/* window.trackAmplitudeEvent('Bottom Upgrade Now Clicked', { settingsId: '<?php echo esc_js( $template_args['cbid'] ); ?>', account_id: '<?php echo esc_js( $template_args['cbid'] ); ?>' }); */ window.open('https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage', '_blank')">
												<?php echo esc_html__( 'Choose my plan', 'cookiebot' ); ?>
											</button>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<!-- Right Side - Banner Control (only show when CBID exists) -->
				<?php if ( $template_args['user_data'] && $template_args['cbid'] ) : ?>
					<div class="gray-box-overview">
						<div class="header-section">
							<div>
								<div class="top-row">
									<a href="<?php echo esc_url( site_url() ); ?>" target="_blank" class="dashboard-link" onclick="/* window.trackAmplitudeEvent('Preview Link Clicked', { settingsId: '<?php echo esc_js( $template_args['cbid'] ); ?>' }); */"><?php echo esc_html__( 'Preview', 'cookiebot' ); ?></a>
									<span class="free-badge"><?php echo esc_html( $template_args['subscription'] ); ?></span>
								</div>
								<h1><?php echo esc_html__( 'Banner control', 'cookiebot' ); ?></h1>
								<p class="subtitle">
									<?php esc_html_e( 'Need more options? Head to the', 'cookiebot' ); ?>
									<a href="https://admin.usercentrics.eu/#/v3/configuration/setup?settingsId=<?php echo esc_attr( $template_args['cbid'] ); ?>" target="_blank" style="color: inherit;">
										<?php esc_html_e( 'Admin Interface.', 'cookiebot' ); ?>
									</a>
								</p>
							</div>
						</div>

						<div class="banner-options">
							<!-- Show banner on site option -->
							<div class="option-group">
								<span class="option-label"><?php echo esc_html__( 'Show banner on site', 'cookiebot' ); ?></span>
								<div class="option-controls">
									<div class="toggle-switch">
										<input type="checkbox" id="cookiebot-banner-enabled" class="toggle-input"
											value="1"
											<?php
											checked( 1, $template_args['banner_enabled'] === '1' );
											?>
											 />
										<label for="cookiebot-banner-enabled" class="toggle-label"></label>
									</div>
									<div class="label-wrapper status-badge <?php echo ! empty( $template_args['cbid'] ) && $template_args['banner_enabled'] === '1' ? 'active' : ' inactive'; ?>" id="cookiebot-banner-badge">
										<div class="label-2">
											<?php echo ! empty( $template_args['cbid'] ) && $template_args['banner_enabled'] === '1' ? esc_html__( 'Active', 'cookiebot' ) : esc_html__( 'Inactive', 'cookiebot' ); ?>
										</div>
									</div>
								</div>
							</div>

							<div class="option-divider"></div>

							<!-- Auto blocking mode option -->
							<div class="option-group">
								<div class="option-label-wrapper">
									<span class="option-label"><?php echo esc_html__( 'Auto blocking mode', 'cookiebot' ); ?></span>
									<div class="tooltip">
										<span class="tooltiptext">Blocks cookies automatically until users consent. No script tagging required. Scan results determine what gets blocked.</span>
										<img class="img" src="<?php echo esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/info.svg' ); ?>" />
									</div>
								</div>
								<div class="option-controls">
									<div class="toggle-switch">
										<input type="checkbox" id="cookiebot-uc-auto-blocking-mode" class="toggle-input"
											value="1"
											<?php
											checked( 1, $template_args['auto_blocking_mode'] === '1' );
											?>
											 />
										<label for="cookiebot-uc-auto-blocking-mode" class="toggle-label"></label>
									</div>
									<div class="label-wrapper status-badge <?php echo ! empty( $template_args['cbid'] ) && $template_args['auto_blocking_mode'] === '1' ? 'active' : ' inactive'; ?>" id="cookiebot-uc-auto-blocking-mode-badge">
										<div class="label-2">
											<?php echo ! empty( $template_args['cbid'] ) && $template_args['auto_blocking_mode'] === '1' ? esc_html__( 'Active', 'cookiebot' ) : esc_html__( 'Inactive', 'cookiebot' ); ?>
										</div>
									</div>
								</div>
							</div>

							<div class="option-divider"></div>

							<!-- Google Consent Mode option -->
							<div class="option-group">
								<div class="option-label-wrapper">
									<span class="option-label"><?php echo esc_html__( 'Google Consent Mode', 'cookiebot' ); ?></span>

									<div class="tooltip">
										<span class="tooltiptext">Enable Google Consent Mode to continue running remarketing campaigns and tracking conversions in Google Ads and Google Analytics. Required if you have visitors from the European Economic Area (EEA).</span>
										<img class="img" src="<?php echo esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/info.svg' ); ?>" />
									</div>

								</div>
								<div class="option-controls">
									<div class="toggle-switch">
										<input type="checkbox" id="cookiebot-gcm" class="toggle-input"
											value="1"
											<?php
											checked( 1, $template_args['gcm_enabled'] === '1' );
											?>
											 />
										<label for="cookiebot-gcm" class="toggle-label"></label>
									</div>

									<div class="label-wrapper status-badge <?php echo ! empty( $template_args['cbid'] ) && $template_args['gcm_enabled'] === '1' ? 'active' : 'inactive'; ?>" id="cookiebot-gcm-badge">
										<div class="label-2">
											<?php echo ! empty( $template_args['cbid'] ) && $template_args['gcm_enabled'] === '1' ? esc_html__( 'Active', 'cookiebot' ) : esc_html__( 'Inactive', 'cookiebot' ); ?>
										</div>
									</div>
								</div>
							</div>

							<div class="option-divider"></div>

							<!-- Legal framework option -->
							<div class="option-group">
								<span class="option-label"><?php echo esc_html__( 'Legal framework', 'cookiebot' ); ?></span>
								<div class="option-controls legal-framework">
									<span class="legal-framework-badge"><?php echo esc_html( $template_args['legal_framework'] ); ?></span>
								</div>
							</div>
						</div>

						<div class="banner-actions">
							<button class="cb-btn customize-banner-btn" onclick="/* window.trackAmplitudeEvent('Customize Banner Clicked', { settingsId: '<?php echo esc_js( $template_args['cbid'] ); ?>', plugin_version: '<?php echo esc_js( CYBOT_COOKIEBOT_VERSION ); ?>' }); */ window.open('<?php echo esc_url( $template_args['customize_banner_link'] ); ?>', '_blank')"><?php echo esc_html__( 'Customize banner', 'cookiebot' ); ?></button>
							<a href="<?php echo esc_url( $template_args['configure_banner_link'] ); ?>" class="configure-link" target="_blank">
								<img class="material-icons" src="<?php echo esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/link.svg' ); ?>" />
								<?php echo esc_html__( 'How to configure your banner', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
