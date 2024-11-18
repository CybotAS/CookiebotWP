<?php
/**
 * @var string $url
 * @var string $text
 */
?>
<li>
	<span class="dashicons dashicons-external"></span>
	<a href="<?php echo esc_url( $url ); ?>" target="_blank"
		rel="noopener">
		<?php
		echo esc_html( $text );
		?>
	</a>
</li>
