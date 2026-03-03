<?php
/**
 * Privacy Policy Generator (PPG) Banner Template
 *
 * Displays a promotional banner for the Privacy Policy Generator plugin.
 * This template is designed to be included in the success state of dashboard pages.
 *
 * @package suspended
 */

defined( 'ABSPATH' ) || exit;

$ppg_icon_url         = esc_url( CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/shield_icon.svg' );
$utm_params           = '?utm_source=wordpress&utm_medium=content-distribution&utm_campaign=ppg-cross-promo&utm_content=install-now-banner';
$ppg_integrations_url = 'https://usercentrics.com/integrations/privacy-policy-generator-wordpress/' . $utm_params;
?>
<div class="cb-ppg-section">
	<h2 class="cb-ppg-section__title">
		<?php echo esc_html__( 'Explore more plugins from Cookiebot', 'cookiebot' ); ?>
	</h2>
	<div class="cb-ppg-banner">
		<div class="cb-ppg-banner__icon">
			<img src="<?php echo esc_attr( $ppg_icon_url ); ?>"
				alt="<?php echo esc_attr__( 'Privacy Policy Generator', 'cookiebot' ); ?>"
				width="80"
				height="80">
		</div>
		<div class="cb-ppg-banner__content">
			<h3 class="cb-ppg-banner__headline">
				<?php echo esc_html__( 'Generate your privacy policy in minutes', 'cookiebot' ); ?>
			</h3>
			<p class="cb-ppg-banner__description">
				<?php echo esc_html__( 'Automatically create compliant privacy policies for your WordPress website.', 'cookiebot' ); ?>
			</p>
		</div>
		<div class="cb-ppg-banner__cta">
			<a href="<?php echo esc_url( $ppg_integrations_url ); ?>"
				target="_blank"
				class="cb-btn cb-ppg-btn cb-main-btn"
				rel="noopener noreferrer">
				<?php echo esc_html__( 'Install Now', 'cookiebot' ); ?>
			</a>
		</div>
	</div>
</div>
