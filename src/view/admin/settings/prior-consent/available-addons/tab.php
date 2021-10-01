<?php

/** @var string $addon_option_name */
/** @var bool $addon_is_enabled */
/** @var array $addon_cookie_types */
/** @var bool $addon_placeholder_is_enabled */
/** @var string $placeholders_html */
/** @var string $addon_extra_options_html */

use function cybot\cookiebot\lib\cookiebot_addons_checked_selected_helper;

require_once ABSPATH . '/wp-includes/l10n.php';
require_once ABSPATH . '/wp-admin/includes/translation-install.php';
?>
<div class="postbox cookiebot-addon">
	<p>
		<label for="<?php echo 'enabled_' . esc_attr( $addon_option_name ); ?>">
			<?php esc_html_e( 'Enable', 'cookiebot' ); ?>
		</label>
		<input <?php checked( 1, $addon_is_enabled ); ?>
				id="<?php echo 'enabled_' . esc_attr( $addon_option_name ); ?>"
				name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][enabled]"
				type="checkbox"
				value="1"
		/>
	</p>
	<p><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></p>
	<ul class="cookietypes">
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'preferences' ); ?>
					type="checkbox"
					id="cookie_type_preferences_<?php echo esc_attr( $addon_option_name ); ?>"
					value="preferences"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label>Preferences</label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'statistics' ); ?>
					type="checkbox"
					id="cookie_type_statistics_<?php echo esc_attr( $addon_option_name ); ?>"
					value="statistics"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label>Statistics</label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'marketing' ); ?>
					type="checkbox"
					id="cookie_type_marketing_<?php echo esc_attr( $addon_option_name ); ?>"
					value="marketing"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label>Marketing</label>
		</li>
	</ul>

	<p>
		<label><?php esc_html_e( 'Display a placeholder', 'cookiebot' ); ?></label>
		<input <?php checked( 1, $addon_placeholder_is_enabled ); ?>
				type="checkbox"
				class="placeholder_enable"
				data-addon="<?php echo esc_attr( $addon_option_name ); ?>"
				name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][placeholder][enabled]"
				value="1"
		>
	</p>

	<div <?php echo ( ! $addon_placeholder_is_enabled ) ? 'style="display:none"' : ''; ?>
			class="placeholder"
			data-addon="<?php echo esc_attr( $addon_option_name ); ?>"
	>
		<?php echo $placeholders_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<p class="add_placeholder_language">
			<button class="btn_add_language button button-secondary"
					data-addon="<?php echo esc_attr( $addon_option_name ); ?>">
				<?php esc_html_e( '+ Add language', 'cookiebot' ); ?>
			</button>
		</p>
	</div>
	<?php echo $addon_extra_options_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
