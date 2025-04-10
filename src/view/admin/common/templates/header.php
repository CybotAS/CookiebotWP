<?php

/**
 * @var string $cookiebot_logo
 * @var string $subscription_status
 * @var int $days_left
 */

use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\settings\pages\Support_Page;
use cybot\cookiebot\settings\pages\Debug_Page;
use cybot\cookiebot\settings\pages\Plugins_Page;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\settings\templates\Main_Tabs;

/**
 * @var array $template_args Array containing all template variables
 */

$main_tabs = new Main_Tabs();

$cbid             = Cookiebot_WP::get_cbid();
$user_data        = Cookiebot_WP::get_user_data();
$trial_expired    = Cookiebot_WP::is_trial_expired();
$upgraded         = Cookiebot_WP::has_upgraded();
$days_left        = Cookiebot_WP::get_trial_days_left();
$is_authenticated = ! empty( Cookiebot_WP::get_auth_token() );

?>
<?php
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! empty( $_GET['settings-updated'] ) ) :
	?>
	<div class="cb-submit__msg"><?php esc_html_e( 'Changes has been saved', 'cookiebot' ); ?></div>
<?php endif; ?>
<?php

if ( Cookiebot_WP::is_in_trial() && ! $trial_expired && $is_authenticated ) :
	?>
	<div class="trial-banner">
		<div class="trial-info">
			<span class="trial-icon"></span>
			<span class="trial-text">
				<span class="trial-label"><?php echo esc_html__( 'Premium Trial:', 'cookiebot' ); ?></span>
				<span class="days-left"><?php /* translators: %d is replaced with "integer" */ echo sprintf( esc_html__( '%d days left', 'cookiebot' ), absint( $days_left ) ); ?></span>
			</span>
		</div>
		<?php if ( isset( $user_data['subscriptions']['active']['subscription_id'] ) ) : ?>
		<a href="https://account.usercentrics.eu/subscription/<?php echo esc_attr( $user_data['subscriptions']['active']['subscription_id'] ); ?>/manage" target="_blank" class="upgrade-button">
		<?php else : ?>
		<a href="https://account.usercentrics.eu/subscription/manage" target="_blank" class="upgrade-button">
		<?php endif; ?>
			<?php echo esc_html__( 'Upgrade now', 'cookiebot' ); ?>
			<span class="arrow-icon">→</span>
		</a>
	</div>
<?php endif; ?>
<div class="cb-header">
	<div class="cb-wrapper">
		<a href="https://www.cookiebot.com/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner">
			<img
				src="<?php echo esc_url( $cookiebot_logo ); ?>"
				alt="Cookiebot logo">
		</a>
	</div>
</div>
