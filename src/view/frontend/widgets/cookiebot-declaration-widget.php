<?php
/** @var string $before_widget_html */
/** @var string $after_widget_html */
/** @var string $widget_title_html */
/** @var string $cookie_declaration_script_url */
/** @var string $tag_attribute_html */

echo $before_widget_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
	<div class="widget-text wp_widget_plugin_box cookiebot_cookie_declaration">
		<?php echo $widget_title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<script
				type="text/javascript"
				id="CookieDeclaration"
				src="<?php echo esc_url( $cookie_declaration_script_url ); ?>"
			<?php if ( ! empty( $culture ) && is_string( $culture ) ) : ?>
				data-culture="<?php echo esc_attr( $culture ); ?>"
			<?php endif; ?>
			<?php echo $tag_attribute_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		>
		</script>
	</div>
<?php
echo $after_widget_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
