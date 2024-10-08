<?php
/**
 * @var bool $cookiebot_iab
 * @var object $purposes
 * @var object $special_purposes
 * @var object $features
 * @var object $special_features
 * @var object $vendors
 * @var array $vendor_data
 * @var array $custom_tcf_purposes
 * @var array $custom_tcf_special_purposes
 * @var array $custom_tcf_features
 * @var array $custom_tcf_special_features
 * @var array $custom_tcf_vendors
 * @var array $custom_tcf_restrictions
 * @var array $custom_tcf_ac_vendors
 * @var array $extra_providers
 */

use cybot\cookiebot\settings\pages\Iab_Page;

$iab_page = new Iab_Page();
?>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'IAB Integration:', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'If you want to use the IAB Framework TCF within your Consent Management Platform (CMP) you can enable it on the right. Be aware that activating this could override some of the configurations you made with the default setup defined by the IAB.', 'cookiebot' ); ?>
		</p>
		<a href="https://support.cookiebot.com/hc/en-us/articles/360007652694-Cookiebot-and-the-IAB-Consent-Framework"
			target="_blank" class="cb-btn cb-link-btn" rel="noopener">
			<?php esc_html_e( 'Read more on IAB with Cookiebot CMP here', 'cookiebot' ); ?>
		</a>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-iab">
				<input type="checkbox" name="cookiebot-iab" id="cookiebot-iab" value="1"
					<?php checked( 1, $cookiebot_iab ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'IAB TCF V2.2 integration', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>

<?php if ( $vendor_data ) : ?>
	<?php foreach ( $vendor_data as $name => $data ) : ?>
		<?php if ( $name !== 'vendors' ) : ?>
			<?php $item_attribute = Iab_Page::get_option_attribute_name( $name ); ?>
			<div class="cb-settings__vendor__config__item">
				<div class="cb-settings__config__content">
					<h3 class="cb-settings__config__subtitle">
						<?php echo esc_html( $data['title'] ); ?>
					</h3>
					<p class="cb-general__info__text"><?php echo esc_html( $data['description'] ); ?></p>
				</div>
				<div class="cb-settings__config__data">
					<div class="cb-settings__config__data__inner">
						<div class="vendor-selected-items search-list">
							<?php foreach ( $data['items'] as $item ) : ?>
								<label class="switch-checkbox" for="cookiebot-<?php echo esc_attr( $item_attribute ); ?>-<?php echo esc_attr( $item['id'] ); ?>">
									<input
											type="checkbox"
											name="cookiebot-tcf-<?php echo esc_attr( $item_attribute ); ?>[]"
											id="cookiebot-<?php echo esc_attr( $item_attribute ); ?>-<?php echo esc_attr( $item['id'] ); ?>"
											value="<?php echo esc_attr( $item['id'] ); ?>"
										<?php echo $iab_page->vendor_checked( $item['id'], $data['selected'] ) ? 'checked' : ''; ?>>
									<div class="switcher"></div>
									<?php echo esc_html( $iab_page->return_translation_value( $name, $item ) ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php else : ?>
	<div class="cb-settings__vendor__config__item vendor-list-offline">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle">
				<?php echo esc_html_e( 'IAB vendor list is temporarily offline. Please try refreshing the page after a couple of minutes.', 'cookiebot' ); ?>
			</h3>
			<p class="cb-general__info__text"><?php echo esc_html_e( 'If you had previously saved configurations, don’t worry, they will continue to work.', 'cookiebot' ); ?></p>
		</div>
	</div>
	<?php
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_option( 'cookiebot-tcf-purposes', $custom_tcf_purposes );
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_option( 'cookiebot-tcf-special-purposes', $custom_tcf_special_purposes );
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_option( 'cookiebot-tcf-features', $custom_tcf_features );
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_option( 'cookiebot-tcf-special-features', $custom_tcf_special_features );
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_option( 'cookiebot-tcf-vendors', $custom_tcf_vendors );
	//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Iab_Page::get_backup_custom_restrictions( $custom_tcf_restrictions );
	?>
<?php endif; ?>

<?php if ( ( $vendor_data && $vendor_data['vendors'] ) || $extra_providers ) : ?>
	<div class="cb-settings__vendor__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle">
				<?php esc_html_e( 'Sharing data with third-party vendors', 'cookiebot' ); ?>
			</h3>
			<p class="cb-general__info__text">
				<?php
				esc_html_e( 'Select vendors with whom you’ll share users’ data. We’ll include this information on the second layer of your consent banner, where users interested in more granular detail about who will access their data can view it.', 'cookiebot' );
				?>
			</p>
		</div>
	</div>
<?php endif; ?>


<?php if ( $vendor_data && $vendor_data['vendors'] ) : ?>
	<?php $item_attribute = Iab_Page::get_option_attribute_name( 'vendors' ); ?>
	<div class="cb-settings__vendor__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle only-title">
				<?php echo esc_html( $vendor_data['vendors']['title'] ); ?>
			</h3>
		</div>
		<div class="cb-settings__config__data">
			<div class="cb-settings__config__data__inner cb-vendor-settings">
				<input type="text" class="cb-settings__selector-search checkbox-vendor-search" placeholder="<?php esc_html_e( 'Search', 'cookiebot' ); ?>...">
				<div class="cb-settings__selector-all cb-btn cb-main-btn"><?php esc_html_e( 'Select All', 'cookiebot' ); ?></div>
				<div class="cb-settings__selector-none cb-btn cb-white-btn"><?php esc_html_e( 'Deselect All', 'cookiebot' ); ?></div>
				<div class="vendor-selected-items-message hidden"><span><?php esc_html_e( 'Select at least one vendor', 'cookiebot' ); ?></span></div>
				<div class="vendor-selected-items search-list">
					<?php foreach ( $vendor_data['vendors']['items'] as $item ) : ?>
						<label class="switch-checkbox" for="cookiebot-<?php echo esc_attr( $item_attribute ); ?>-<?php echo esc_attr( $item['id'] ); ?>">
							<input
									type="checkbox"
									name="cookiebot-tcf-<?php echo esc_attr( $item_attribute ); ?>[]"
									id="cookiebot-<?php echo esc_attr( $item_attribute ); ?>-<?php echo esc_attr( $item['id'] ); ?>"
									value="<?php echo esc_attr( $item['id'] ); ?>"
								<?php echo $iab_page->vendor_checked( $item['id'], $vendor_data['vendors']['selected'] ) ? 'checked' : ''; ?>>
							<div class="switcher"></div>
							<?php echo esc_html( $iab_page->return_translation_value( 'vendors', $item ) ); ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ( $extra_providers ) : ?>
	<div class="cb-settings__vendor__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle only-title">
				<?php esc_html_e( 'Google Ads certified external vendors', 'cookiebot' ); ?>
			</h3>
		</div>
		<div class="cb-settings__config__data">
			<div class="cb-settings__config__data__inner cb-vendor-settings">
				<input type="text" class="cb-settings__selector-search checkbox-vendor-search" placeholder="<?php esc_html_e( 'Search', 'cookiebot' ); ?>...">
				<div class="cb-settings__selector-all cb-btn cb-main-btn"><?php esc_html_e( 'Select All', 'cookiebot' ); ?></div>
				<div class="cb-settings__selector-none cb-btn cb-white-btn"><?php esc_html_e( 'Deselect All', 'cookiebot' ); ?></div>
				<div class="vendor-selected-items search-list">
					<?php foreach ( $extra_providers as $item ) : ?>
						<label class="switch-checkbox" for="cookiebot-ac-vendor<?php echo esc_attr( $item['id'] ); ?>">
							<input
									type="checkbox"
									name="cookiebot-tcf-ac-vendors[]"
									id="cookiebot-ac-vendor<?php echo esc_attr( $item['id'] ); ?>"
									value="<?php echo esc_attr( $item['id'] ); ?>"
								<?php echo $iab_page->vendor_checked( $item['id'], $custom_tcf_ac_vendors ) ? 'checked' : ''; ?>>
							<div class="switcher"></div>
							<?php echo esc_html( $item['name'] ); ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php if ( $vendor_data ) : ?>

	<div class="cb-settings__vendor__config__item">
		<div class="cb-settings__config__content">
			<h3 class="cb-settings__config__subtitle">
				<?php esc_html_e( 'Restrictions of data use purposes for vendors', 'cookiebot' ); ?>
			</h3>
			<p class="cb-general__info__text">
				<?php
				esc_html_e(
					'Set restrictions on data use purposes for specific vendors. Add vendors and the data use purposes that each vendor is allowed. We’ll share this information with users within your consent banner.',
					'cookiebot'
				);
				?>
			</p>
			<div class="cb-btn cb-main-btn restriction-vendor-add"><?php esc_html_e( 'Add Vendor', 'cookiebot' ); ?></div>
		</div>
		<?php
		foreach ( $custom_tcf_restrictions as $vendor => $settings ) :
			$select_name_attr     = $vendor !== 0 ? 'cookiebot-tcf-disallowed[' . $vendor . ']' : '';
			$vendors_list         = $vendor_data['vendors'];
			$selector_placeholder = __( 'Select Vendor', 'cookiebot' );
			foreach ( $vendors_list['items'] as $item ) {
				if ( $item['id'] === $vendor ) {
					$selector_placeholder = $item['name'];
				}
			}
			?>
			<div class="cb-settings__config__data cb-settings__vendor__restrictions">
				<div class="cb-settings__config__data__inner">
					<div class="cb-settings__selector__container">
						<input type="hidden" name="<?php echo esc_html( $select_name_attr ); ?>" class="cb-settings__selector__container-input">
						<div class="cb-settings__selector-selector" data-placeholder="<?php esc_attr_e( 'Select Vendor', 'cookiebot' ); ?>"><?php echo esc_html( $selector_placeholder ); ?></div>
						<div class="cb-settings__selector-list-container hidden">
							<div class="cb-settings__selector-veil"></div>
							<input type="text" class="cb-settings__selector-search" placeholder="<?php esc_html_e( 'Search', 'cookiebot' ); ?>...">
							<div class="cb-settings__selector-list search-list">
								<?php foreach ( $vendors_list['items'] as $item ) : ?>
									<div
											data-value="<?php echo esc_attr( $item['id'] ); ?>"
											class="cb-settings__selector-list-item <?php echo $item['id'] === $vendor ? 'selected' : ''; ?>"><?php echo esc_html( $item['name'] ); ?></div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="cb-btn cb-main-btn vendor-purposes-show"><?php esc_html_e( 'Set Purposes', 'cookiebot' ); ?></div>
					<div class="remove__restriction dashicons dashicons-dismiss"></div>
				</div>
				<div class="vendor-purposes-restrictions hidden">
					<?php
					foreach ( $vendor_data as $name => $data ) :
						if ( $name === 'purposes' ) :
							?>
							<div class="cb-settings__config__data__inner">
								<div class="vendor-selected-items">
									<?php foreach ( $data['items'] as $item ) : ?>

										<?php
										$item_attribute = str_replace( '_', '-', $name );
										$item_name      = $vendor !== 0 ? 'cookiebot-tcf-disallowed[' . $vendor . '][purposes][]' : '';
										$item_id        = 'cookiebot-vendor' . $vendor . '-' . $item_attribute . $item['id'];
										?>

										<label class="switch-checkbox" for="<?php echo esc_html( $item_id ); ?>">
											<input
													type="checkbox"
													name="<?php echo esc_attr( $item_name ); ?>"
													id="<?php echo esc_html( $item_id ); ?>"
													class="purpose-item"
													value="<?php echo esc_attr( $item['id'] ); ?>"
												<?php echo $iab_page->vendor_checked( $item['id'], $settings['purposes'] ) ? 'checked' : ''; ?>>

											<div class="switcher"></div>
											<?php echo esc_html( $iab_page->return_translation_value( $name, $item ) ); ?>
										</label>
									<?php endforeach; ?>
								</div>
							</div>
							<?php
						endif;
					endforeach;
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
