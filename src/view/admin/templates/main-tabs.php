<?php
use cybot\cookiebot\settings\pages\Dashboard_Page;
use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\addons\config\Settings_Config;
use cybot\cookiebot\settings\pages\Support_Page;
use cybot\cookiebot\settings\pages\Debug_Page;

/**
 * @var string $active_tab
 */

$isnw = is_network_admin();
?>
<div class="cb-main__tabs">
	<?php if ( ! $isnw ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'dashboard' ? 'active-item' : ''; ?>">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Dashboard_Page::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
				<div class="cb-main__tabs__icon dashboard-icon"></div>
				<span><?php esc_html_e( 'Dashboard', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>
	<div class="cb-main__tabs_item <?php echo $active_tab === 'settings' ? 'active-item' : ''; ?>">
		<?php if ( $isnw ) : ?>
		<a href="<?php echo esc_url( network_admin_url( 'admin.php?page=cookiebot_network' ) ); ?>" class="cb-main__tabs__link">
		<?php else : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Settings_Page::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
		<?php endif; ?>
			<div class="cb-main__tabs__icon settings-icon"></div>
			<span><?php esc_html_e( 'Settings', 'cookiebot' ); ?></span>
		</a>
	</div>
	<?php if ( ! $isnw ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'addons' ? 'active-item' : ''; ?>">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Settings_Config::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
				<div class="cb-main__tabs__icon plugins-icon"></div>
				<span><?php esc_html_e( 'Plugins', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>
	<div class="cb-main__tabs_item <?php echo $active_tab === 'support' ? 'active-item' : ''; ?>">
		<?php if ( $isnw ) : ?>
		<a href="<?php echo esc_url( network_admin_url( 'admin.php?page=' . Support_Page::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
		<?php else : ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Support_Page::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
		<?php endif; ?>
			<div class="cb-main__tabs__icon support-icon"></div>
			<span><?php esc_html_e( 'Support', 'cookiebot' ); ?></span>
		</a>
	</div>
	<?php if ( ! $isnw ) : ?>
		<div class="cb-main__tabs_item <?php echo $active_tab === 'debug' ? 'active-item' : ''; ?>">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Debug_Page::ADMIN_SLUG ) ); ?>" class="cb-main__tabs__link">
				<div class="cb-main__tabs__icon debug-icon"></div>
				<span><?php esc_html_e( 'Debug info', 'cookiebot' ); ?></span>
			</a>
		</div>
	<?php endif; ?>
</div>
