<?php
/**
 * @var string $label
 * @var string[] $extra_information_lines
 */
?>
<?php echo esc_html( $label ); ?>
<div class="extra_information">
	<?php foreach ( $extra_information_lines as $extra_information_line ) : ?>
		<p><?php echo esc_html( $extra_information_line ); ?></p>
	<?php endforeach; ?>
</div>
