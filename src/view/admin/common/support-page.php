<?php
/**
 * @var string $manager_language
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
					<h2 class="cb-support__info__title"><?php esc_html_e( 'Need help?', 'cookiebot' ); ?></h2>
					<p class="cb-support__info__text">
						<?php
						esc_html_e(
							'Visit our Support Center to find answers to your questions or get help with configuration. If you need further assistance, use the Contact Support button in the top navigation to create a support request. We’ll respond as soon as possible.',
							'cookiebot'
						);
						?>
					</p>
					<a href="https://usercentricsforwordpress.zendesk.com/hc/en-us/sections/19194997558428-FAQ" target="_blank" class="cb-btn cb-main-btn"
						rel="noopener">
						<?php
						esc_html_e(
							'Go to Support Center',
							'cookiebot'
						);
						?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
