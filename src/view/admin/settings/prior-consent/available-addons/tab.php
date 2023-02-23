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
	<div class="cookiebot-addon-enable">
		<label class="switch-checkbox" for="<?php echo 'enabled_' . esc_attr( $addon_option_name ); ?>">
			<input <?php checked( 1, $addon_is_enabled ); ?>
					id="<?php echo 'enabled_' . esc_attr( $addon_option_name ); ?>"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][enabled]"
					type="checkbox"
					value="1"
			/>
			<div class="switcher"></div>
			<?php esc_html_e( 'Plugin enabled', 'cookiebot' ); ?>
		</label>
	</div>
	<p class="cookiebot-addon-text"><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></p>
	<ul class="cookietypes">
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'preferences' ); ?>
					type="checkbox"
					id="cookie_type_preferences_<?php echo esc_attr( $addon_option_name ); ?>"
					value="preferences"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label class="cookiebot-addon-text"><?php esc_html_e( 'preferences', 'cookiebot' ); ?></label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'statistics' ); ?>
					type="checkbox"
					id="cookie_type_statistics_<?php echo esc_attr( $addon_option_name ); ?>"
					value="statistics"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label class="cookiebot-addon-text"><?php esc_html_e( 'statistics', 'cookiebot' ); ?></label>
		</li>
		<li>
			<input <?php cookiebot_addons_checked_selected_helper( $addon_cookie_types, 'marketing' ); ?>
					type="checkbox"
					id="cookie_type_marketing_<?php echo esc_attr( $addon_option_name ); ?>"
					value="marketing"
					name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][cookie_type][]"
			>
			<label class="cookiebot-addon-text"><?php esc_html_e( 'marketing', 'cookiebot' ); ?></label>
		</li>
	</ul>
	<p class="cookiebot-addon-text"><?php esc_html_e( 'Placeholder text:', 'cookiebot' ); ?></p>
	<p class="cookiebot-addon-placeholder">
		<input <?php checked( 1, $addon_placeholder_is_enabled ); ?>
				type="checkbox"
				class="placeholder_enable"
				data-addon="<?php echo esc_attr( $addon_option_name ); ?>"
				name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][placeholder][enabled]"
				value="1"
		>
		<label class="cookiebot-addon-text"><?php esc_html_e( 'Display a placeholder', 'cookiebot' ); ?></label>
	</p>

	<div <?php echo ( ! $addon_placeholder_is_enabled ) ? 'style="display:none"' : ''; ?>
			class="placeholder"
			data-addon="<?php echo esc_attr( $addon_option_name ); ?>"
	>
		<?php echo $placeholders_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<p class="add_placeholder_language">
			<button class="btn_add_language cb-btn cb-main-btn"
					data-addon="<?php echo esc_attr( $addon_option_name ); ?>">
				<?php esc_html_e( '+ Add language', 'cookiebot' ); ?>
			</button>
		</p>
	</div>
	<?php echo $addon_extra_options_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
