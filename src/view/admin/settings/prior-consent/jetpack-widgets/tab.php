<?php

use function cybot\cookiebot\addons\lib\cookiebot_addons_checked_selected_helper;

/** @var $widget_is_enabled bool */
/** @var $widget_placeholder_is_enabled bool */
/** @var $widget_has_placeholder bool */
/** @var $widget_default_placeholder string */
/** @var $widget_placeholders array */
/** @var $widget_option_name string */
/** @var $widget_cookie_types array */
/** @var $addon_placeholder_helper string */
/** @var $site_default_languages_dropdown_html string */

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
		<?php if ( $widget_has_placeholder ) : ?>
			<?php
			foreach (
				$widget_placeholders as list(
				'name' => $name,
				'removable' => $removable,
				'language' => $language,
				'placeholder' => $placeholder,
				'languages_dropdown_html' => $languages_dropdown_html
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
								cols="80"
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
								name="cookiebot_jetpack_addon[<?php echo esc_attr( $widget_option_name ); ?>][placeholder][languages][site-default]"
						><?php echo esc_textarea( $widget_default_placeholder ); ?></textarea>
					<span class="help-tip" title="<?php echo esc_attr( $addon_placeholder_helper ); ?>"></span>
				</p>
			</div>
		<?php endif; ?>

		<p class="add_placeholder_language">
			<button class="btn_add_language button button-secondary"
					data-addon="<?php echo esc_attr( $widget_option_name ); ?>"><?php esc_html_e( '+ Add language', 'cookiebot-addons' ); ?></button>
		</p>
	</div>
</div>
