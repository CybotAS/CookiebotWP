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
					<h2 class="cb-debug__info__title"><?php esc_html_e( 'Debug information', 'cookiebot' ); ?></h2>
					<p  class="cb-debug__info__text">
						<?php
						esc_html_e(
							'The information below is for debugging purposes. If you have any issues with your Cookiebot CMP integration, this information is the best place to start.',
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
						<h2 class="cb-debug__support__title"><?php esc_html_e( 'If you have any issues with the implemenation of Cookiebot CMP, please visit our Support Center.', 'cookiebot' ); ?></h2>
						<a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-main-btn" rel="noopener">
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
