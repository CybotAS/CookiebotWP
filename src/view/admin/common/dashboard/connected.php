<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'dashboard' ); ?>
		<div class="cb-main__content">
			<div class="cb-dash" data-cb-dash-view="connected"></div>
		</div>
	</div>
</div>
