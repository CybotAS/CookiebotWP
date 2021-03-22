<?php
/** @var \cookiebot_addons\controller\addons\Cookiebot_Addons_Interface $addon */
$addon = $args['addon'];

require_once( ABSPATH . '/wp-includes/l10n.php' );
require_once( ABSPATH . '/wp-admin/includes/translation-install.php' );
?>
    <div class="postbox cookiebot-addon">
        <p>
            <label for="<?php echo 'enabled_' . $addon->get_option_name(); ?>"><?php esc_html_e( 'Enable', 'cookie' ); ?></label>
            <input type="checkbox" id="<?php echo 'enabled_' . $addon->get_option_name(); ?>"
                   name="cookiebot_available_addons[<?php echo $addon->get_option_name() ?>][enabled]"
                   value="1" <?php checked( 1, $addon->is_addon_enabled(), true ); ?> />
        </p>
        <p><?php esc_html_e( 'Check one or multiple cookie types:', 'cookiebot' ); ?></p>
        <ul class="cookietypes">
            <li><input type="checkbox" id="cookie_type_preferences_<?php echo $addon->get_option_name(); ?>"
                       value="preferences"
					<?php cookiebot_addons_checked_selected_helper( $addon->get_cookie_types(), 'preferences' ); ?>
                       name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Preferences</label>
            </li>
            <li><input type="checkbox" id="cookie_type_statistics_<?php echo $addon->get_option_name(); ?>"
                       value="statistics"
					<?php cookiebot_addons_checked_selected_helper( $addon->get_cookie_types(), 'statistics' ); ?>
                       name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Statistics</label>
            </li>
            <li><input type="checkbox" id="cookie_type_marketing_<?php echo $addon->get_option_name(); ?>"
                       value="marketing"
					<?php cookiebot_addons_checked_selected_helper( $addon->get_cookie_types(), 'marketing' ); ?>
                       name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][cookie_type][]"><label>Marketing</label>
            </li>
        </ul>

        <p>
            <label><?php esc_html_e( 'Display a placeholder', 'cookiebot-addons' ); ?></label>
            <input type="checkbox"
                   class="placeholder_enable"
                   data-addon="<?php echo $addon->get_option_name(); ?>"
                   name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][placeholder][enabled]"
				<?php checked( 1, $addon->is_placeholder_enabled() ); ?>
                   value="1">
        </p>

        <div class="placeholder"
             data-addon="<?php echo $addon->get_option_name(); ?>" <?php echo ( ! $addon->is_placeholder_enabled() ) ? 'style="display:none"' : ''; ?>>
			<?php if ( $addon->has_placeholder() ): ?>
				<?php $count = 0; ?>
				<?php foreach ( $addon->get_placeholders() as $placeholder_lang => $placeholder_value ): ?>
                    <div class="placeholder_content submitbox">
                        <p>
                            <label><?php esc_html_e( 'Language', 'cookiebot-addons' ); ?></label>
							<?php
							$name = 'cookiebot_available_addons[' . $addon->get_option_name() . '][placeholder][languages][' . $placeholder_lang . ']';
							echo cookiebot_addons_get_dropdown_languages( 'placeholder_select_language', $name, $placeholder_lang );
							?>
							<?php if ( $count != 0 ): ?>
                                <a href=""
                                   class="submitdelete deletion"><?php esc_html_e( 'Remove language', 'cookiebot-addons' ); ?></a>
							<?php endif; ?>
                        </p>
                        <p>
                        <textarea cols="60" rows="5"
                                  name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][placeholder][languages][<?php echo $placeholder_lang; ?>]"><?php echo $placeholder_value; ?></textarea>
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
						$name = 'cookiebot_available_addons[' . $addon->get_option_name() . '][placeholder][languages][site-default]';
						echo cookiebot_addons_get_dropdown_languages( 'placeholder_select_language', $name, '' );
						?>
                    </p>
                    <p>
                        <textarea cols="80" rows="5"
                                  name="cookiebot_available_addons[<?php echo $addon->get_option_name(); ?>][placeholder][languages][site-default]"><?php echo $addon->get_default_placeholder(); ?></textarea>
                        <span class="help-tip" title="<?php echo $addon->get_placeholder_helper(); ?>"></span>
                    </p>
                </div>
			<?php endif; ?>

            <p class="add_placeholder_language">
                <button class="btn_add_language button button-secondary"
                        data-addon="<?php echo $addon->get_option_name(); ?>"><?php esc_html_e( '+ Add language', 'cookiebot-addons' ); ?></button>
            </p>
        </div>
	    <?php
	        $addon->extra_available_addon_option();
	    ?>
    </div>