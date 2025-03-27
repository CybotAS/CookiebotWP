<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
use cybot\cookiebot\settings\pages\Settings_Page;
use function cybot\cookiebot\lib\cookiebot_is_trial_expired;
use function cybot\cookiebot\lib\cookiebot_is_upgraded;

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
		<!-- Trial expiration notice -->
		<?php
		$trial_expired = cookiebot_is_trial_expired();
		$upgraded      = cookiebot_is_upgraded();
		if ( $trial_expired && ! $upgraded ) :
			?>
			<div class="header-top-banners trial-expired-banner">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/clock-icon.svg' ); ?>"
						alt="Clock Icon">
					<div>
						<h3><?php echo esc_html__( 'Your premium trial is over', 'cookiebot' ); ?></h3>
						<p>
							<?php echo esc_html__( 'Your trial has ended, and advanced features are now disabled. You can still use your banner on the Free plan with a 1,000-session limit. Upgrade to Premium for a higher limit and full access to premium features.', 'cookiebot' ); ?>
						</p>
					</div>
				</div>
				<div class="upgrade-expired-trial">
					<a href="https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage" target="_blank" style="text-decoration: none; color: inherit;">
						<h3><?php echo esc_html__( 'Upgrade now', 'cookiebot' ); ?> <span class="upgrade-chevron">&rsaquo;</span></h3>
					</a>
				</div>
			</div>
		<?php endif; ?>

		<!-- Banner connected notice -->
		<?php if ( ! empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
			<div class="header-top-banners connected-banner">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-green.svg' ); ?>"
						alt="Check Icon">
					<div>
						<h3><?php echo esc_html__( 'Your banner is connected!', 'cookiebot' ); ?></h3>
						<p style="text-wrap: nowrap;">
							<?php echo esc_html__( 'Everything works as before. Manage your banner in the', 'cookiebot' ); ?>
							<a href="<?php echo esc_url( $template_args['cookiebot_admin_link'] ); ?>" target="_blank"><?php echo esc_html__( 'Cookiebot Manager', 'cookiebot' ); ?></a>
							<?php echo esc_html__( 'or', 'cookiebot' ); ?>
							<a href="<?php echo esc_url( $template_args['uc_admin_link'] ); ?>" target="_blank"><?php echo esc_html__( 'Usercentrics Admin', 'cookiebot' ); ?></a>.
						</p>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- Banner is live notice -->
		<?php
		$banner_dismissed = get_option( 'cookiebot_banner_live_dismissed', false );

		if ( ! empty( $template_args['cbid'] ) && ! empty( $template_args['user_data'] ) && ! $banner_dismissed ) :
			?>
			<div class="header-top-banners banner-live-banner" id="banner-live-notice">
				<div class="banner-content">
					<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-white.svg' ); ?>"
						alt="Check Icon">
					<div>
						<h3>Well done! Your <a href="<?php echo esc_url( site_url() ); ?>" target="_blank" class="banner-live-link">banner is live</a>.</h3>
						<p>
							<?php echo esc_html__( 'If you\'ve signed up or logged in for the first time, your banner may take a few seconds to load on your website.', 'cookiebot' ); ?>
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
						<?php if ( ! empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
							<h1><?php echo \esc_html__( 'Simplify your banner management', 'cookiebot' ); ?></h1>
						<?php else : ?>
							<h1><?php echo \esc_html__( 'Set up your consent banner', 'cookiebot' ); ?></h1>
						<?php endif; ?>
					</div>
					<div class="header-section">
						<?php if ( empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
							<p class="subtitle">
								<?php echo esc_html__( 'Get your site GDPR-compliant in', 'cookiebot' ); ?>
								<strong><?php echo esc_html__( 'just a few clicks.', 'cookiebot' ); ?></strong>
								<?php echo esc_html__( 'Enter your email, verify it, and create your password.', 'cookiebot' ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
							<p class="subtitle">
								<?php echo esc_html__( 'Your setup is good to go - but we\'re making banner control even easier inside WordPress.', 'cookiebot' ); ?>
								<strong><?php echo esc_html__( 'Get access to new features', 'cookiebot' ); ?></strong>
								<?php echo esc_html__( 'by updating your banner today.', 'cookiebot' ); ?>
							</p>
						<?php endif; ?>
					</div>
	
	
					<!-- Steps Container -->
					<div class="steps-container">
						<!-- Activate your banner step -->
						<div class="step-box <?php echo ! empty( $template_args['cbid'] ) ? 'completed' : ''; ?>">
							<div class="step-row">
	
								<?php if ( ! empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
									<div class="step-icon">
										<div class="empty-circle"></div>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Unlock new banner', 'cookiebot' ); ?></h2>
									</div>
								<?php endif; ?>
	
								<?php if ( empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
									<div class="step-icon">
										<div class="empty-circle"></div>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Activate your banner', 'cookiebot' ); ?></h2>
									</div>
								<?php endif; ?>
	
								<?php if ( ! empty( $template_args['cbid'] ) && ! empty( $template_args['user_data'] ) ) : ?>
									<div class="step-icon">
										<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-mark.svg' ); ?>" alt="Checkmark">
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Activate your banner', 'cookiebot' ); ?></h2>
									</div>
									<span class="done-status">Done!</span>
								<?php endif; ?>
	
							</div>
	
							<?php if ( empty( $template_args['user_data'] ) ) : ?>
								<div class="banner-preview-container">
									<div class="divider"></div>
									<?php if ( ! empty( $template_args['cbid'] ) && empty( $template_args['user_data'] ) ) : ?>
										<p class="step-description">
											<?php echo esc_html__( 'We\'ve simplified privacy compliance for you. Save time with auto-setup, website scanning for data processing services, and consent-first blocking.', 'cookiebot' ); ?>
										</p>
									<?php else : ?>
										<p class="step-description">
											<?php echo esc_html__( 'Activate your banner in seconds with easy auto-setup, smart data processing services detection, and consent-first blocking for automated privacy compliance.', 'cookiebot' ); ?>
										</p>
									<?php endif; ?>
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
											<?php echo esc_html__( 'Get Started', 'cookiebot' ); ?>
										</button>
										<img src="<?php echo \esc_url( $arrow_url ); ?>"
											alt="arrow"
											class="banner-arrow">
									</div>
	
									<?php if ( ! empty( $template_args['cbid'] ) ) : ?>
										<div class="note-text">
											<img class="note-icon" src="<?php echo esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/info.svg' ); ?>" />
											<span>Note: A new account comes with a new banner, which will replace your existing one.</span>
										</div>
									<?php endif; ?>
	
								</div>
							<?php endif; ?>
						</div>
	
						<!-- Scan website step - only show when CBID exists -->
						<?php if ( ! empty( $template_args['user_data'] ) ) : ?>
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
										<h2><?php echo \esc_html__( 'Scan website', 'cookiebot' ); ?></h2>
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
								<div id="scan-details" class="scan-details" style="display: block;">
									<div class="divider"></div>
									<p class="scan-message">
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
						<?php if ( ! empty( $template_args['user_data'] ) ) : ?>
							<div class="step-box">
								<div class="step-row">
									<div class="step-icon">
										<?php
										$subscription_type = $template_args['subscription'];
										$is_upgraded       = $subscription_type !== 'Free' && $subscription_type !== 'Premium trial';
										if ( $is_upgraded ) :
											?>
											<img src="<?php echo \esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check-mark.svg' ); ?>" alt="Checkmark">
										<?php else : ?>
											<div class="empty-circle"></div>
										<?php endif; ?>
									</div>
									<div class="step-content">
										<h2><?php echo \esc_html__( 'Upgrade your plan', 'cookiebot' ); ?></h2>
									</div>
									<?php if ( $is_upgraded ) : ?>
										<span class="done-status">Done!</span>
									<?php else : ?>
										<div class="step-status">
											<div class="lightning-badge">
												<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M7.58333 0.583344L1.75 8.16668H7L6.41667 13.4167L12.25 5.83334H7L7.58333 0.583344Z" fill="#0047FF" />
												</svg>
											</div>
										</div>
									<?php endif; ?>
								</div>

								<!-- Upgrade details section -->
								<?php if ( $is_upgraded ) : ?>
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
										<p class="upgrade-intro">Upgrade to unlock these premium benefits:</p>

										<ul class="upgrade-features">
											<li><strong>Match your consent banner to your brand</strong> with advanced customization options.</li>
											<li><strong>Adapt your banner to increase opt-ins</strong> using our consent analytics data.</li>
											<li><strong>Benefit from higher session limits</strong> and maintain privacy compliance as your traffic grows.</li>
										</ul>

										<p class="ready-text">Ready to take your consent experience to the next level?</p>

										<div class="upgrade-container">
											<button id="upgrade-now-button" class="cb-btn cb-primary-btn" onclick="window.open('https://account.usercentrics.eu/subscription/<?php echo isset( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) ? esc_attr( $template_args['user_data']['subscriptions']['active']['subscription_id'] ) . '/' : ''; ?>manage', '_blank')">
												<?php echo esc_html__( 'Upgrade now', 'cookiebot' ); ?>
											</button>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
	
				<!-- Right Side - Banner Control (only show when CBID exists) -->
				<?php if ( $template_args['user_data'] ) : ?>
					<div class="gray-box-overview">
						<div class="header-section">
							<div>
								<div class="top-row">
									<a href="<?php echo esc_url( site_url() ); ?>" target="_blank" class="dashboard-link"><?php echo esc_html__( 'Preview', 'cookiebot' ); ?></a>
									<span class="free-badge"><?php echo esc_html( $template_args['subscription'] ); ?></span>
								</div>
								<h1><?php echo esc_html__( 'Banner control', 'cookiebot' ); ?></h1>
								<p class="subtitle"><?php echo esc_html__( 'Choose and control your banner settings and display options.', 'cookiebot' ); ?></p>
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
	
							<!-- Google Consent Mode option -->
							<div class="option-group">
								<div class="option-label-wrapper">
									<span class="option-label"><?php echo esc_html__( 'Google Consent Mode', 'cookiebot' ); ?></span>
	
									<div class="tooltip">
										<span class="tooltiptext">Enable Google Consent Mode integration within your Usercentrics Cookiebot WordPress Plugin.</span>
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
							<button class="cb-btn customize-banner-btn" onclick="window.open('<?php echo esc_url( $template_args['customize_banner_link'] ); ?>', '_blank')"><?php echo esc_html__( 'Customize banner', 'cookiebot' ); ?></button>
							<a href="<?php echo esc_url( $template_args['configure_banner_link'] ); ?>" class="configure-link" target="_blank">
								<img class="material-icons" src="<?php echo esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/link.svg' ); ?>" />
								<?php echo esc_html__( 'How to configure your banner', 'cookiebot' ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			 </div>

			<!-- Help text (only show when user_data doesn't exist) -->
			<?php if ( empty( $template_args['user_data'] ) ) : ?>
				<div>
					<div class="cb-general__info__text">
						<span class="note-text">Need to manage an existing Cookiebot or Usercentrics account?</span>

						<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
							class="note-link">
							<span><?php esc_html_e( 'Go to Settings', 'cookiebot' ); ?></span>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
