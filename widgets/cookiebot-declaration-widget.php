<?php

// The widget class for Cookiebot D
class Cookiebot_Declaration_Widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'cookiebot_declaration_widget',
			esc_html__( 'Cookiebot - Cookie Declaration', 'cookiebot' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 * @return string Default return is 'noform'.
	 */
	public function form( $instance ) {
		$lang  = isset( $instance['lang'] ) ? $instance['lang'] : '';
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'Cookiebot' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>"><?php esc_html_e( 'Language', 'cookiebot' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'lang' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>"
					class="widefat">
				<option value=""><?php echo esc_html__( '- Default -', 'cookiebot' ); ?></option>
				<?php
				$options = Cookiebot_WP::get_supported_languages();
				foreach ( $options as $key => $name ) {
					?>
					<option value="<?php echo esc_attr( $key ); ?>"
							id="<?php echo esc_attr( $key ); ?>"
							class="value"
							<?php selected( $lang, $key, false, true ); ?>
					><?php echo esc_html( $name ); ?></option>
					<?php
				}
				?>
			</select>
		</p>
		<?php

		// the base function expects a string to be returned
		return '';
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['lang']  = isset( $new_instance['lang'] ) ? wp_strip_all_tags( $new_instance['lang'] ) : '';
		$instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		return $instance;
	}

	// Display the widget

	/**
	 * Echoes the widget content.
	 *
	 * Subclasses should override this function to generate their widget code.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? $args['after_title'] : '';
		$lang          = isset( $instance['lang'] ) ? $instance['lang'] : '';
		$title         = isset( $instance['title'] ) ? $instance['title'] : '';

		// WordPress core before_widget hook
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $before_widget;

		echo '<div class="widget-text wp_widget_plugin_box cookiebot_cookie_declaration">';

		// Display widget title if defined
		if ( $title ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $before_title . esc_html( $title ) . $after_title;
		}

		$cbid = Cookiebot_WP::get_cbid();
		if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' ) === 'custom' ) {
			$tag_attr = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
		} else {
			$tag_attr = get_site_option( 'cookiebot-script-tag-cd-attribute' );
		}

		?>
		<script type="text/javascript"
				id="CookieDeclaration"
				src="<?php echo esc_url( 'https://consent.cookiebot.com/' . $cbid . '/cd.js' ); ?>"
				<?php if ( ! empty( $lang ) ) : ?>
					data-culture="<?php echo esc_attr( $lang ); ?>"
				<?php endif; ?>
				<?php echo esc_attr( $tag_attr ); ?>
		/>
		<?php
		echo '</div>';

		// WordPress core after_widget hook
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_widget;
	}

}
