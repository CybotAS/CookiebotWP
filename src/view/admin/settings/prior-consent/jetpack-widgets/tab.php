<?php

use function cybot\cookiebot\lib\cookiebot_addons_checked_selected_helper;

/** @var string $widget_option_name */
/** @var bool $widget_is_enabled */
/** @var array $widget_cookie_types */
/** @var bool $widget_placeholder_is_enabled */
/** @var string $placeholders_html */

?>
<div class="postbox cookiebot-addon">
	<p>
		<label for="<?php echo 'enabled_' . esc_attr( $widget_option_name ); ?>">
			<?php esc_html_e( 'Enable', 'cookiebot' ); ?>
		</label>
		<input <?php checked( 1, $widget_is_enabled ); ?>
				type="checkbox"
				id="<?php echo 'enabled_' . esc_attr( $widget_option_name ); ?>"
				name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][enabled]"
				value="1"
		/>
	</p>
	<p>
		<span><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></span><br>
	<ul class="cookietypes">
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $widget_cookie_types, 'preferences' ); ?>
					type="checkbox"
					id="cookie_type_preferences_<?php echo esc_attr( $widget_option_name ); ?>"
					value="preferences"
					name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][cookie_type][]"><label>Preferences</label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $widget_cookie_types, 'statistics' ); ?>
					type="checkbox"
					id="cookie_type_statistics_<?php echo esc_attr( $widget_option_name ); ?>"
					value="statistics"
					name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][cookie_type][]"
			>
			<label>Statistics</label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $widget_cookie_types, 'marketing' ); ?>
					type="checkbox"
					id="cookie_type_marketing_<?php echo esc_attr( $widget_option_name ); ?>"
					value="marketing"
					name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][cookie_type][]"><label>Marketing</label>
		</li>
	</ul>

	<p>
		<label><?php esc_html_e( 'Display a placeholder', 'cookiebot' ); ?></label>
		<input <?php checked( 1, $widget_placeholder_is_enabled ); ?>
				type="checkbox"
				class="placeholder_enable"
				data-addon="<?php echo esc_attr( $widget_option_name ); ?>"
				name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][placeholder][enabled]"
				value="1">
	</p>

	<div <?php echo ( ! $widget_placeholder_is_enabled ) ? 'style="display:none"' : ''; ?>
			class="placeholder"
			data-addon="<?php echo esc_attr( $widget_option_name ); ?>"

	>
		<?php echo $placeholders_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<p class="add_placeholder_language">
			<button class="btn_add_language cb-btn cb-main-btn"
					data-addon="<?php echo esc_attr( $widget_option_name ); ?>">
				<?php esc_html_e( '+ Add language', 'cookiebot' ); ?>
			</button>
		</p>
	</div>
</div>
