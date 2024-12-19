<?php
/**
 * @var string $debug_output
 */

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'debug' ); ?>
		<div class="cb-main__content">
			<h1 class="cb-main__page_title"><?php esc_html_e( 'Debug info', 'cookiebot' ); ?></h1>

			<div class="cb-debug__content">
				<div class="cb-debug__info__card">
					<h2 class="cb-debug__info__title"><?php esc_html_e( 'Debug your plugin', 'cookiebot' ); ?></h2>
					<p class="cb-debug__info__text">
						<?php
						esc_html_e(
							'If you encounter any issues with your Usercentrics Cookiebot WordPress Plugin, provide the information below to help us assist you. Visit our Support Center and send us a copy of what is displayed in the window below.',
							'cookiebot'
						);
						?>
					</p>
					<button class="cb-btn cb-main-btn" onclick="copyDebugInfo();">
						<?php
						esc_html_e(
							'Copy debug information to clipboard',
							'cookiebot'
						);
						?>
					</button>
				</div>
				<div class="cb-debug__support__card">
					<div class="cb-debug__support__inner">
						<a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-main-btn"
							rel="noopener">
							<?php
							esc_html_e(
								'Visit Support Center',
								'cookiebot'
							);
							?>
						</a>
					</div>

				</div>
			</div>

			<div class="cb-debug__code__container">
				<textarea
						cols="50"
						rows="40"
						id="cookiebot-debug-info"
						readonly="readonly"
				><?php echo esc_textarea( $debug_output ); ?></textarea>
			</div>
		</div>
	</div>
</div>
