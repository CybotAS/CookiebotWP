<?php

use function cybot\cookiebot\lib\asset_url;

/**
 * @var string $popover_id
 */
?>
<div class="cb-dash__where" data-cb-where>
	<button type="button" class="cb-dash__where-trigger" data-cb-where-trigger aria-describedby="<?php echo esc_attr( $popover_id ); ?>" aria-expanded="false">
		<?php esc_html_e( 'Where is it?', 'cookiebot' ); ?>
	</button>
	<div class="cb-dash__popover" id="<?php echo esc_attr( $popover_id ); ?>" role="tooltip" data-cb-where-popover hidden>
		<img class="cb-dash__popover-caret" src="<?php echo esc_url( asset_url( 'img/dashboard/popover-caret.svg' ) ); ?>" width="20" height="10" alt="">
		<div class="cb-dash__popover-box">
			<p class="cb-dash__h4"><?php esc_html_e( 'Where to find your ID', 'cookiebot' ); ?></p>
			<div class="cb-dash__popover-examples">
				<div class="cb-dash__popover-example">
					<p class="cb-dash__h4"><?php esc_html_e( 'Cookiebot › Implementation', 'cookiebot' ); ?></p>
					<p class="cb-dash__small"><?php esc_html_e( 'Scroll down the page to find:', 'cookiebot' ); ?></p>
					<p class="cb-dash__body"><?php esc_html_e( 'Domain Group ID', 'cookiebot' ); ?></p>
					<span class="cb-dash__popover-id">
						<mark class="cb-dash__code-mark">00000000-0000-0000-…</mark>
						<img src="<?php echo esc_url( asset_url( 'img/dashboard/content-copy.svg' ) ); ?>" width="16" height="16" alt="">
					</span>
				</div>
				<div class="cb-dash__popover-example cb-dash__popover-example--script">
					<p class="cb-dash__h4"><?php esc_html_e( 'Or in your Cookiebot script', 'cookiebot' ); ?></p>
					<p class="cb-dash__small"><?php esc_html_e( 'Copy only the highlighted ID:', 'cookiebot' ); ?></p>
					<code class="cb-dash__code">&lt;script id="Cookiebot"
&#160;&#160;src="…/uc.js"
&#160;&#160;data-cbid="<mark class="cb-dash__code-mark">00000000-0000-…</mark>"
&gt;&lt;/script&gt;</code>
				</div>
			</div>
		</div>
	</div>
</div>
