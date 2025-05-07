<?php
/**
 * @var string $debug_output
 */

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'support' ); ?>
		<div class="cb-main__content">
			<h1 class="cb-main__page_title"><?php esc_html_e( 'Support', 'cookiebot' ); ?></h1>

			<div class="cb-support__content">
				<div class="cb-support__info__card">
					<h2 class="cb-support__info__title"><?php esc_html_e( 'Having trouble or need help?', 'cookiebot' ); ?></h2>
					<p class="cb-support__info__text">
						<?php
						esc_html_e(
							'Need help? Visit our Support Center for answers.',
							'cookiebot'
						);
						echo '<br>';
						esc_html_e(
							'Still stuck? Click',
							'cookiebot'
						);
						echo '<strong> ';
						esc_html_e(
							'Submit a request',
							'cookiebot'
						);
						echo ' </strong>';
						esc_html_e(
							'in the top-right corner of the Support Center page.',
							'cookiebot'
						);
						echo '<br>';
						esc_html_e(
							'Please include your debug info so we can help faster.',
							'cookiebot'
						);
						?>
					</p>
				</div>

				<div class="cb-debug__support__card">
					<a href="https://usercentricsforwordpress.zendesk.com/hc/en-us" target="_blank" class="cb-btn cb-main-btn" style="border: 3px solid #1032cf"
						rel="noopener">
						<?php esc_html_e( 'Visit Support Center', 'cookiebot' ); ?>
					</a>
					<a href="#" onclick="copyDebugInfo();" class="cb-btn cb-secondary-btn" style="margin-left: 20px;">
						<?php esc_html_e( 'Copy Debug Info', 'cookiebot' ); ?>
					</a>
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
