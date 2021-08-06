<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">
	
	<div id="icon-themes" class="icon32"></div>
	<h2><?php esc_html_e( 'Prior consent', 'cookiebot' ); ?></h2>
	<div class="notice inline notice-warning notice-alt cookiebot-notice">
		<p>
			<?php esc_html_e( 'These add-ons are produced by an open-source community of developers. This is done to help make it easier for Wordpress users to implement ‘prior consent’ for cookies and trackers set by plugins that do not offer this as a built-in functionality.' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'The add-ons are currently the best alternative to a Wordpress Core framework that can signal the user’s consent state to other plugins (if and when this will be implemented is unknown) and to those plugins who do not yet offer native support for Cookiebot built into the plugin itself.' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'We do not assume any responsibility for the use of these add-ons. If one of the plugins that the add-ons hook into makes a ‘breaking change’, there may be a period of time where the add-on will not work properly until it has been updated to accommodate the changes in the plugin.' ); ?>
		</p>
		<p>
			<strong>
				<?php echo sprintf( esc_html__( 'If your favourite plugin isn\'t supported you\'re welcome to contribute or request on our <a href="%s" target="_blank">Github development page.</a>' ), 'https://github.com/CybotAS/CookiebotAddons' ); ?>
			</strong>
		</p>
	
	</div>
	<?php
	if ( defined( 'COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION' ) ) {
		?>
		<p><strong>
			<?php
			esc_html_e('This feature is only available in PHP 5.4 and above.');
			?>
		</strong></p>
		<?php
	}
	else { 
		?>
		<?php if ( isset( $_GET['tab'] ) ) {
			$active_tab = esc_attr( $_GET['tab'] );
		} else if ( $active_tab == 'unavailable_addons' ) {
			$active_tab = 'unavailable_addons';
		} else {
			$active_tab = 'available_addons';
		} ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=cookiebot-addons&tab=available_addons"
				 class="nav-tab <?php echo $active_tab == 'available_addons' ? 'nav-tab-active' : ''; ?>">Available
				Plugins</a>
			<a href="?page=cookiebot-addons&tab=unavailable_addons"
				 class="nav-tab <?php echo $active_tab == 'unavailable_addons' ? 'nav-tab-active' : ''; ?>">Unavailable
				Plugins</a>
			<?php
			if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
				?>
				<a href="?page=cookiebot-addons&tab=jetpack"
					 class="nav-tab <?php echo $active_tab == 'jetpack' ? 'nav-tab-active' : ''; ?>">Jetpack</a>
				<?php
			}
			?>
		
		</h2>
		
		<form method="post" action="options.php" class="<?php echo $active_tab; ?>">
			<?php
			
			if ( $active_tab == 'available_addons' ) {
				settings_fields( 'cookiebot_available_addons' );
				do_settings_sections( 'cookiebot-addons' );
			} elseif ( $active_tab == 'jetpack' ) {
				settings_fields( 'cookiebot_jetpack_addon' );
				do_settings_sections( 'cookiebot-addons' );
			} else {
				settings_fields( 'cookiebot_not_installed_options' );
				do_settings_sections( 'cookiebot-addons' );
			} // end if/else
			
			submit_button();
			
			?>
		</form>
		<?php
	}
	?>
</div><!-- /.wrap -->
