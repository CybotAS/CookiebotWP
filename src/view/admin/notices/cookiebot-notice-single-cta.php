<?php
/**
 * @var string $url
 * @var string $text
 */
?>
<li>
	<a class="cb-btn cb-main-btn" href="<?php echo esc_url( $url ); ?>" target="_blank"
	   rel="noopener">
		<?php
		echo esc_html( $text );
		?>
	</a>
</li>
