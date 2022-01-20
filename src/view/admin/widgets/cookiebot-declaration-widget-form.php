<?php

/** @var string $lang */
/** @var string $title */
/** @var string $title_field_id */
/** @var string $title_field_name */
/** @var string $lang_field_id */
/** @var string $lang_field_name */
/** @var array $supported_languages */
?>

<p>
	<label for="<?php echo esc_attr( $title_field_id ); ?>">
		<?php esc_html_e( 'Title', 'cookiebot' ); ?>
	</label>
	<input
			class="widefat"
			id="<?php echo esc_attr( $title_field_id ); ?>"
			name="<?php echo esc_attr( $title_field_name ); ?>"
			type="text"
			value="<?php echo esc_attr( $title ); ?>"
	/>
</p>
<p>
	<label for="<?php echo esc_attr( $lang_field_id ); ?>">
		<?php esc_html_e( 'Language', 'cookiebot' ); ?>
	</label>
	<select
			name="<?php echo esc_attr( $lang_field_name ); ?>"
			id="<?php echo esc_attr( $lang_field_id ); ?>"
			class="widefat"
	>
		<option value=""><?php echo esc_html__( '- Default -', 'cookiebot' ); ?></option>
		<?php foreach ( $supported_languages as $supported_language_code => $supported_language ) : ?>
			<option 
					value="<?php echo esc_attr( $supported_language_code ); ?>"
					id="<?php echo esc_attr( $supported_language_code ); ?>"
					<?php echo selected( $lang, $supported_language_code, false ); ?>
			>
				<?php echo esc_html( $supported_language ); ?>
			</option>
		<?php endforeach; ?>
	</select>

</p>
