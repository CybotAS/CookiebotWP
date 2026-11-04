<?php

defined( 'ABSPATH' ) || exit;

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;
use function cybot\cookiebot\lib\include_view;

/**
 * @var string $domain
 * @var bool   $signup_clicked
 * @var bool   $offer_active
 * @var string $signup_url
 * @var string $support_url
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>
		<div class="cb-main__content">
			<div class="cb-dash" data-cb-dash-view="welcome">
				<h1 class="cb-dash__title"><?php esc_html_e( 'Welcome', 'cookiebot' ); ?></h1>
				<hr class="cb-dash__divider">

				<div class="cb-dash__intro">
					<h2 class="cb-dash__h3"><?php esc_html_e( 'Let’s get your cookie banner set up', 'cookiebot' ); ?></h2>
					<p class="cb-dash__body" data-cb-path-intro="new">
						<?php esc_html_e( 'Easy implementation in 2 steps. Most setups take less than 5 minutes.', 'cookiebot' ); ?>
					</p>
					<p class="cb-dash__body" data-cb-path-intro="existing" hidden>
						<?php esc_html_e( 'Connect your existing Cookiebot account in 1 step. Most setups take less than 5 minutes.', 'cookiebot' ); ?>
					</p>
				</div>

				<div class="cb-dash__setup">
					<fieldset class="cb-dash__paths">
						<legend class="screen-reader-text"><?php esc_html_e( 'How do you want to set up?', 'cookiebot' ); ?></legend>
						<label class="cb-dash__path">
							<input type="radio" name="cb-dash-path" value="new" data-cb-path checked>
							<span class="cb-dash__path-text">
								<span class="cb-dash__h4"><?php esc_html_e( 'Create a new account', 'cookiebot' ); ?></span>
								<span class="cb-dash__small"><?php esc_html_e( 'New site or new client', 'cookiebot' ); ?></span>
							</span>
						</label>
						<label class="cb-dash__path">
							<input type="radio" name="cb-dash-path" value="existing" data-cb-path>
							<span class="cb-dash__path-text">
								<span class="cb-dash__h4"><?php esc_html_e( 'Connect an existing account', 'cookiebot' ); ?></span>
								<span class="cb-dash__small"><?php esc_html_e( 'You already have an ID for this site', 'cookiebot' ); ?></span>
							</span>
						</label>
					</fieldset>

					<div class="cb-dash__steps" data-cb-steps="new">
						<?php
						include_view(
							'admin/common/dashboard/partials/new-account-step.php',
							array(
								'domain'         => $domain,
								'offer_active'   => $offer_active,
								'signup_url'     => $signup_url,
								'signup_clicked' => $signup_clicked,
							)
						);
						include_view(
							'admin/common/dashboard/partials/connect-step.php',
							array(
								'form_id'     => 'new',
								'step_number' => 2,
								'step_label'  => __( 'STEP 2 OF 2', 'cookiebot' ),
								'locked'      => ! $signup_clicked,
								'support_url' => $support_url,
							)
						);
						?>
					</div>

					<div class="cb-dash__steps" data-cb-steps="existing" hidden>
						<?php
						include_view(
							'admin/common/dashboard/partials/connect-step.php',
							array(
								'form_id'     => 'existing',
								'step_number' => 1,
								'step_label'  => '',
								'locked'      => false,
								'support_url' => $support_url,
							)
						);
						?>
					</div>
				</div>

				<hr class="cb-dash__divider">

				<?php include_view( 'admin/common/dashboard/partials/need-help.php' ); ?>
			</div>
		</div>
	</div>
</div>
