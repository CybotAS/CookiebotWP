<?php
/** @var  array $placeholders */
/** @var  string $placeholder_helper */
foreach ( $placeholders as $placeholder ) :
	/** @var string $name */
	$name = $placeholder['name'];
	/** @var string $removable */
	$removable = $placeholder['removable'];
	/** @var string $language */
	$language = $placeholder['language'];
	/** @var string $placeholder_content */
	$placeholder_content = $placeholder['placeholder'];
	/** @var string $languages_dropdown_html */
	$languages_dropdown_html = $placeholder['languages_dropdown_html'];
	?>
	<div class="placeholder_content submitbox">
		<p>
			<label class="placeholder_title"><?php esc_html_e( 'Language', 'cookiebot' ); ?>:</label>
			<?php
			echo $languages_dropdown_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<?php if ( $removable ) : ?>
				<a href="" class="submitdelete deletion cb-btn cb-main-btn">
					<?php esc_html_e( 'Remove language', 'cookiebot' ); ?>
				</a>
			<?php endif; ?>
		</p>
		<p>
			<textarea class="placeholder_textarea"
				cols="5"
				rows="5"
				name="<?php echo esc_attr( $name ); ?>"
			>
			<?php
			echo esc_textarea( $placeholder_content );
			?>
			</textarea>
			<span class="help-tip" title="<?php echo esc_attr( $placeholder_helper ); ?>"></span>
		</p>
	</div>
<?php endforeach; ?>
