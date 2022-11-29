<?php
/** @var $addon_option_name string */
/** @var $regex string */
/** @var $regex_is_default bool */
/** @var $default_regex string */
?>
<div class="show_advanced_options">
	<button class="cb-btn cb-main-btn">
		<?php
		esc_html_e(
			'Show advanced options',
			'cookiebot'
		);
		?>
	</button>
	<span
			class="help-tip"
			title="<?php echo esc_html__( 'This is for more advanced users.', 'cookiebot' ); ?>"
	></span>
</div>
<div class="advanced_options">

	<label for="embed_regex"><?php esc_html_e( 'Regex:', 'cookiebot' ); ?></label>
	<textarea
			id="embed_regex"
			cols="80"
			rows="5"
			name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][regex]"
			disabled
	><?php echo esc_textarea( $regex ); ?></textarea>

	<?php if ( $regex_is_default ) : ?>
		<button id="edit_embed_regex" class="cb-btn cb-main-btn">
			<?php
			esc_html_e(
				'Edit regex',
				'cookiebot'
			);
			?>
		</button>
	<?php endif; ?>

	<button
			id="btn_default_embed_regex"
			class="cb-btn cb-main-btn<?php echo ( $regex_is_default ) ? ' hidden' : ''; ?>"
			type="button"
			value="Reset to default regex">
		<?php
		esc_html_e(
			'Reset to default regex',
			'cookiebot'
		);
		?>
	</button>
	<input
			type="hidden"
			name="default_embed_regex"
			id="default_embed_regex"
			value="<?php echo esc_html( $default_regex ); ?>"/>
</div>
