<?php
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\lib\Settings_Page_Tab;

/**
* @var Settings_Page_Tab[] $settings_page_tabs
* @var Settings_Page_Tab $active_tab
*/

$header          = new Header();
$main_tabs       = new Main_Tabs();
$jetpack_enabled = isset( get_option( 'cookiebot_available_addons' )['jetpack']['enabled'] ) && get_option( 'cookiebot_available_addons' )['jetpack']['enabled'];

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'addons' ); ?>
		<div class="cb-main__content">
			<form method="post" action="options.php" class="<?php echo esc_attr( $active_tab->get_name() ); ?>">
				<div class="cb-settings__header">
					<h1 class="cb-main__page_title"><?php esc_html_e( 'Plugins', 'cookiebot' ); ?></h1>
					<?php
					if ( $active_tab->has_submit_button() ) {
						if ( $active_tab->get_name() === 'jetpack' ) {
							if ( $jetpack_enabled ) {
								submit_button();
							}
						} else {
							submit_button();
						}
					}
					?>
				</div>

				<div class="cb-settings__tabs">
					<?php foreach ( $settings_page_tabs as $settings_page_tab ) : ?>
						<a href="<?php echo esc_url( $settings_page_tab->get_tab_href() ); ?>" class="cb-settings__tabs__item <?php echo esc_attr( $settings_page_tab->get_classes() ); ?>">
							<?php echo esc_html( $settings_page_tab->get_label() ); ?>
						</a>
					<?php endforeach; ?>
				</div>

				<div class="cb-settings__tabs__content">
					<div class="cb-settings__tabs__content--item">

							<?php
							settings_fields( $active_tab->get_settings_fields_option_group() );
							do_settings_sections( $active_tab->get_page_name() );
							if ( $active_tab->has_submit_button() ) {
								if ( $active_tab->get_name() === 'jetpack' ) {
									if ( $jetpack_enabled ) {
										submit_button();
									}
								} else {
									submit_button();
								}
							}
							?>

					</div>
				</div>
			</form>
		</div>
	</div>
</div>
