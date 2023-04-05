<?php
/** @var string $site_default_languages_dropdown_html */
/** @var string $name */
/** @var string $default_placeholder */
/** @var string $placeholder_helper */
?>
<div class="placeholder_content">
	<p>
		<label class="placeholder_title"><?php esc_html_e( 'Language', 'cookiebot' ); ?>:</label>
		<?php
		echo $site_default_languages_dropdown_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</p>
	<p>
		<textarea cols="80" rows="5"
				  name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $default_placeholder ); ?></textarea>
		<span class="help-tip" title="<?php echo esc_attr( $placeholder_helper ); ?>"></span>
	</p>
</div>
