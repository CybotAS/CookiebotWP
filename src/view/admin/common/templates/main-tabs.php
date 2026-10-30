<?php

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\settings\pages\Dashboard_Page;
use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\addons\config\Settings_Config;
use cybot\cookiebot\settings\pages\Support_Page;
use cybot\cookiebot\settings\pages\PPG_Page;

/**
 * @var string $active_tab
 */

$isnw = is_network_admin();

$cbid          = Cookiebot_WP::get_cbid();
$auth_token    = get_option( 'cookiebot-auth-token' );
$user_data     = Cookiebot_WP::get_user_data();
$show_settings = true;
$show_plugins  = ! $isnw && Cookiebot_Frame::is_cb_frame_type() !== 'empty';
$feedback_url  = 'https://form.typeform.com/to/n6ZlunZP';

?>
<div class="cb-main__tabs">
	<?php if ( ! $isnw ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'dashboard' ? 'active-item' : ''; ?>">
			<a href="<?php echo esc_url( add_query_arg( 'page', Dashboard_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
				class="cb-main__tabs__link">
				<div class="cb-main__tabs__icon dashboard-icon"></div>
				<span><?php esc_html_e( 'View dashboard', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $cbid ) && ! empty( $auth_token ) ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'customize' ? 'active-item' : ''; ?>">
			<a href="https://admin.usercentrics.eu/#/v3/appearance/styling?settingsId=<?php echo esc_attr( $cbid ); ?>"
				class="cb-main__tabs__link" target="_blank">
				<div class="cb-main__tabs__icon customize-icon"></div>
				<span><?php esc_html_e( 'Customize banner', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>

	<div class="cb-main__tabs_item <?php echo $active_tab === 'support' ? 'active-item' : ''; ?>">
		<?php if ( $isnw ) : ?>
		<a href="<?php echo esc_url( add_query_arg( 'page', Support_Page::ADMIN_SLUG, network_admin_url( 'admin.php' ) ) ); ?>"
			class="cb-main__tabs__link">
			<?php else : ?>
			<a href="<?php echo esc_url( add_query_arg( 'page', Support_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
				class="cb-main__tabs__link">
				<?php endif; ?>
				<div class="cb-main__tabs__icon support-icon"></div>
				<span><?php esc_html_e( 'Get support', 'cookiebot' ); ?></span>
			</a>
	</div>
	<?php if ( $show_plugins ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'addons' ? 'active-item' : ''; ?>">
			<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Config::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
				class="cb-main__tabs__link">
				<div class="cb-main__tabs__icon plugins-icon"></div>
				<span><?php esc_html_e( 'Manage add-ons', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( $show_settings ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'settings' ? 'active-item' : ''; ?>">
			<?php if ( $isnw ) : ?>
			<a href="<?php echo esc_url( add_query_arg( 'page', 'cookiebot_network', network_admin_url( 'admin.php' ) ) ); ?>"
				class="cb-main__tabs__link">
				<?php else : ?>
				<a href="<?php echo esc_url( add_query_arg( 'page', Settings_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
					class="cb-main__tabs__link">
					<?php endif; ?>
					<div class="cb-main__tabs__icon settings-icon"></div>
					<span><?php esc_html_e( 'Configure settings', 'cookiebot' ); ?></span>
				</a>
		</div>
	<?php endif; ?>

	<div class="cb-main__tabs_separator"></div>

	<div class="cb-main__tabs_item <?php echo $active_tab === 'ppg' ? 'active-item' : ''; ?>">
		<a href="<?php echo esc_url( add_query_arg( 'page', PPG_Page::ADMIN_SLUG, admin_url( 'admin.php' ) ) ); ?>"
			class="cb-main__tabs__link">
			<div class="cb-main__tabs__icon plugins-icon"></div>
			<span><?php esc_html_e( 'Policy Generator Plugin', 'cookiebot' ); ?></span>
			<span class="cb-main__tabs__badge"><?php esc_html_e( 'NEW', 'cookiebot' ); ?></span>
		</a>
	</div>

	<div class="cb-main__tabs_item">
		<a href="https://www.cookiebot.com/en/wordpress-partner-program/?utm_source=wordpress&amp;utm_medium=referral&amp;utm_campaign=plugin_dashboard&amp;utm_content=side_menu"
			class="cb-main__tabs__link" target="_blank" rel="noopener">
			<div class="cb-main__tabs__icon partner-icon"></div>
			<span class="cb-main__tabs__label-external">
				<?php esc_html_e( 'Become a Partner', 'cookiebot' ); ?>
				<img src="<?php echo esc_url( \cybot\cookiebot\lib\asset_url( 'img/dashboard/launch-outlined.svg' ) ); ?>" alt="" width="18" height="18">
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'cookiebot' ); ?></span>
			</span>
		</a>
	</div>

	<div class="cb-feedback_link">
		<a href="<?php echo esc_url( $feedback_url ); ?>" target="_blank"><?php echo esc_html__( 'Help us improve', 'cookiebot' ); ?></a>
	</div>

</div>
