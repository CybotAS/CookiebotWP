<?php
/** @var Settings_Page_Tab[] $settings_page_tabs */
/** @var Settings_Page_Tab $active_tab */

use cybot\cookiebot\lib\Settings_Page_Tab;

?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
	<div id="icon-themes" class="icon32"></div>
	<h2><?php esc_html_e( 'Prior consent', 'cookiebot' ); ?></h2>
	<div class="notice inline notice-warning notice-alt cookiebot-notice">
		<p>
			<?php esc_html_e( 'These add-ons are produced by an open-source community of developers. This is done to help make it easier for WordPress users to implement ‘prior consent’ for cookies and trackers set by plugins that do not offer this as a built-in functionality.', 'cookiebot' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'The add-ons are currently the best alternative to a WordPress Core framework that can signal the user’s consent state to other plugins (if and when this will be implemented is unknown) and to those plugins who do not yet offer native support for Cookiebot built into the plugin itself.', 'cookiebot' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'We do not assume any responsibility for the use of these add-ons. If one of the plugins that the add-ons hook into makes a ‘breaking change’, there may be a period of time where the add-on will not work properly until it has been updated to accommodate the changes in the plugin.', 'cookiebot' ); ?>
		</p>
		<p>
			<strong>
				<?php
				echo sprintf(
					// translators: The first placeholder is the HTML anchor open tag, and the second placeholder is the closing tag.
					esc_html__( 'If your favourite plugin isn\'t supported you\'re welcome to contribute or request on our %1$sGithub development page.%2$s', 'cookiebot' ),
					'<a href="https://github.com/CybotAS/CookiebotAddons" target="_blank">',
					'</a>'
				);
				?>
			</strong>
		</p>
	</div>
	<h2 class="nav-tab-wrapper">
		<?php foreach ( $settings_page_tabs as $settings_page_tab ) : ?>
			<a href="<?php echo esc_url( $settings_page_tab->get_tab_href() ); ?>" class="<?php echo esc_attr( $settings_page_tab->get_classes() ); ?>">
				<?php echo esc_html( $settings_page_tab->get_label() ); ?>
			</a>
		<?php endforeach; ?>
	</h2>
	<form method="post" action="options.php" class="<?php echo esc_attr( $active_tab->get_name() ); ?>">
		<?php
		settings_fields( $active_tab->get_settings_fields_option_group() );
		do_settings_sections( $active_tab->get_page_name() );
		if ( $active_tab->has_submit_button() ) {
			submit_button();
		}
		?>
	</form>
</div>
