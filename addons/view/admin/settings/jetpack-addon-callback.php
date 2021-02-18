<?php
$widget = $args['widget'];
$addon  = $args['addon'];

?>
<div class="postbox cookiebot-addon">
    <p>
        <label for="<?php echo 'enabled_' . $widget->get_widget_option_name(); ?>"><?php esc_html_e( 'Enable', 'cookiebot' ); ?></label>
        <input type="checkbox" id="<?php echo 'enabled_' . $widget->get_widget_option_name(); ?>"
               name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][enabled]"
               value="1" <?php checked( 1, $widget->is_widget_enabled(), true ); ?> />
    </p>
    <p>
        <span><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></span><br>
    <ul class="cookietypes">
        <li><input type="checkbox" id="cookie_type_preferences_<?php echo $widget->get_widget_option_name(); ?>"
                   value="preferences"
				<?php cookiebot_addons_checked_selected_helper( $widget->get_widget_cookie_types( $widget->get_widget_option_name() ), 'preferences' ); ?>
                   name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][cookie_type][]"><label>Preferences</label>
        </li>
        <li><input type="checkbox" id="cookie_type_statistics_<?php echo $widget->get_widget_option_name(); ?>"
                   value="statistics"
				<?php cookiebot_addons_checked_selected_helper( $widget->get_widget_cookie_types( $widget->get_widget_option_name() ), 'statistics' ); ?>
                   name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][cookie_type][]"><label>Statistics</label>
        </li>
        <li><input type="checkbox" id="cookie_type_marketing_<?php echo $widget->get_widget_option_name(); ?>"
                   value="marketing"
				<?php cookiebot_addons_checked_selected_helper( $widget->get_widget_cookie_types( $widget->get_widget_option_name() ), 'marketing' ); ?>
                   name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][cookie_type][]"><label>Marketing</label>
        </li>
    </ul>
    </p>

    <p>
        <label><?php esc_html_e( 'Display a placeholder', 'cookiebot' ); ?></label>
        <input type="checkbox"
               class="placeholder_enable"
               data-addon="<?php echo $widget->get_widget_option_name(); ?>"
               name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][placeholder][enabled]"
			<?php checked( 1, $widget->is_widget_placeholder_enabled() ); ?>
               value="1">
    </p>

    <div class="placeholder"
         data-addon="<?php echo $widget->get_widget_option_name(); ?>" <?php echo ( ! $widget->is_widget_placeholder_enabled() ) ? 'style="display:none"' : ''; ?>>
		<?php if ( $widget->widget_has_placeholder() ): ?>
			<?php $count = 0; ?>
			<?php foreach ( $widget->get_widget_placeholders() as $placeholder_lang => $placeholder_value ): ?>
                <div class="placeholder_content submitbox">
                    <p>
                        <label><?php esc_html_e( 'Language', 'cookiebot-addons' ); ?></label>
						<?php
						$name = 'cookiebot_jetpack_addon[' . $widget->get_widget_option_name() . '][placeholder][languages][' . $placeholder_lang . ']';
						echo cookiebot_addons_get_dropdown_languages( 'placeholder_select_language', $name, $placeholder_lang );
						?>
						<?php if ( $count != 0 ): ?>
                            <a href=""
                               class="submitdelete deletion"><?php esc_html_e( 'Remove language', 'cookiebot-addons' ); ?></a>
						<?php endif; ?>
                    </p>
                    <p>
                        <textarea cols="60" rows="5"
                                  name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][placeholder][languages][<?php echo $placeholder_lang; ?>]"><?php echo $placeholder_value; ?></textarea>
                        <span class="help-tip" title="<?php echo $addon->get_placeholder_helper(); ?>"></span>
                    </p>
                </div>
				<?php $count ++; ?>
			<?php endforeach; ?>
		<?php else: ?>
            <div class="placeholder_content">
                <p>
                    <label><?php esc_html_e( 'Language', 'cookiebot-addons' ); ?></label>
					<?php
					$name = 'cookiebot_jetpack_addon[' . $widget->get_widget_option_name() . '][placeholder][languages][site-default]';
					echo cookiebot_addons_get_dropdown_languages( 'placeholder_select_language', $name, '' );
					?>
                </p>
                <p>
                        <textarea cols="80" rows="5"
                                  name="cookiebot_jetpack_addon[<?php echo $widget->get_widget_option_name(); ?>][placeholder][languages][site-default]"><?php echo $widget->get_default_placeholder(); ?></textarea>
                    <span class="help-tip" title="<?php echo $addon->get_placeholder_helper(); ?>"></span>
                </p>
            </div>
		<?php endif; ?>


		<p class="add_placeholder_language">
			<button class="btn_add_language button button-secondary"
			        data-addon="<?php echo $widget->get_widget_option_name(); ?>"><?php esc_html_e( '+ Add language', 'cookiebot-addons' ); ?></button>
		</p>
	</div>
</div>
