<?php
$jetpack_enabled = isset( get_option( 'cookiebot_available_addons' )['jetpack']['enabled'] ) && get_option( 'cookiebot_available_addons' )['jetpack']['enabled'];
?>
<div class="cb-addons__tab__header">
	<div class="cb-addons__header__column--inner">
		<div class="cb-addons__header__column">
			<h2 class="cb-addons__tab__title"><?php esc_html_e( 'Jetpack settings', 'cookiebot' ); ?></h2>
			<p class="cb-addons__tab__text"><?php esc_html_e( 'Enable Jetpack on "Available Addons" to see this page options.', 'cookiebot' ); ?></p>
		</div>
	</div>
	<?php if ( $jetpack_enabled ) : ?>
	<div class="cb-addons__header__column--inner">
		<div class="cb-addons__header__column submit-column">
			<h2 class="cb-addons__tab__subtitle"><?php esc_html_e( 'Remember to save your changes before switching tabs', 'cookiebot' ); ?></h2>
		</div>
	</div>
	<?php endif; ?>
</div>
