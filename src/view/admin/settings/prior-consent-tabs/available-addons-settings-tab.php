<?php
/** @var bool $addon_is_enabled */
/** @var bool $addon_placeholder_is_enabled */
/** @var bool $addon_has_placeholder */
/** @var array $addon_placeholders */
/** @var string $addon_default_placeholder */
/** @var string $site_default_languages_dropdown_html */
/** @var string $addon_option_name */
/** @var array $addon_cookie_types */
/** @var string $addon_placeholder_helper */
/** @var string $addon_extra_options_html */

use function cybot\cookiebot\addons\lib\cookiebot_addons_checked_selected_helper;

require_once ABSPATH . '/wp-includes/l10n.php';
require_once ABSPATH . '/wp-admin/includes/translation-install.php';
?>
<div class="postbox cookiebot-addon">
	<p>
		<label for="<?php echo 'enabled_' . esc_attr( $addon_option_name ); ?>">
			<?php esc_html_e( 'Enable', 'cookie' ); ?>
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
		<label><?php esc_html_e( 'Display a placeholder', 'cookiebot-addons' ); ?></label>
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
		<?php if ( $addon_has_placeholder ) : ?>
			<?php
			foreach (
				$addon_placeholders as list(
				'name' => $name,
				'removable' => $removable,
				'language' => $language,
				'placeholder' => $placeholder,
				'languages_dropdown_html' => $languages_dropdown_html,
			)
			) :
				?>
				<div class="placeholder_content submitbox">
					<p>
						<label><?php esc_html_e( 'Language', 'cookiebot-addons' ); ?></label>
						<?php
						echo $languages_dropdown_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<?php if ( $removable ) : ?>
							<a href="" class="submitdelete deletion">
								<?php esc_html_e( 'Remove language', 'cookiebot-addons' ); ?>
							</a>
						<?php endif; ?>
					</p>
					<p>
						<textarea
								cols="60"
								rows="5"
								name="<?php echo esc_attr( $name ); ?>"
						><?php echo esc_textarea( $placeholder ); ?></textarea>
						<span class="help-tip" title="<?php echo esc_attr( $addon_placeholder_helper ); ?>"></span>
					</p>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="placeholder_content">
				<p>
					<label><?php esc_html_e( 'Language', 'cookiebot-addons' ); ?></label>
					<?php
					echo $site_default_languages_dropdown_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</p>
				<p>
					<textarea
							cols="80"
							rows="5"
							name="cookiebot_available_addons[<?php echo esc_attr( $addon_option_name ); ?>][placeholder][languages][site-default]"
					><?php echo esc_textarea( $addon_default_placeholder ); ?></textarea>
					<span class="help-tip" title="<?php echo esc_attr( $addon_placeholder_helper ); ?>"></span>
				</p>
			</div>
		<?php endif; ?>

		<p class="add_placeholder_language">
			<button class="btn_add_language button button-secondary"
					data-addon="<?php echo esc_attr( $addon_option_name ); ?>"><?php esc_html_e( '+ Add language', 'cookiebot-addons' ); ?></button>
		</p>
	</div>
	<?php echo $addon_extra_options_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
