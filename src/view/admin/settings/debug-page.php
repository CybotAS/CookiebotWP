<?php
/**
 * @var string $debug_output
 */
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Debug information', 'cookiebot' ); ?></h1>
	<p>
		<?php
		esc_html_e(
			'The information below is for debugging purpose. If you have any issues with your Cookiebot integration, the information below is usefull for a supporter to help you the best way.',
			'cookiebot'
		);
		?>
	</p>
	<p>
		<button class="button button-primary" onclick="copyDebugInfo();">
			<?php
			esc_html_e(
				'Copy debug information to clipboard',
				'cookiebot'
			);
			?>
		</button>
	</p>
	<textarea
			cols="100"
			rows="40"
			style="width:800px;max-width:100%;"
			id="cookiebot-debug-info"
			readonly="readonly"
	><?php echo esc_textarea( $debug_output ); ?></textarea>
</div>
