<?php
/**
 * @var bool $cookiebot_iab
 * @var string $cookiebot_tcf_version
 */
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
				<?php esc_html_e( 'IAB TCF integration', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>

<div class="cb-settings__config__item"<?php echo ( $cookiebot_iab ) ? '' : ' style="display: none"'; ?>>
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'TCF version:', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'In May 2023 The Interactive Advertising Bureau (IAB) announced the latest version of its Transparency and Consent Framework (TCF), or TCF v2.2, which must be implemented by all consent management platforms (CMPs) by November 20, 2023. We will migrate you automatically on November 20,2023, but we recommend to do it manually before. To manually switch the version before please select it on the right.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<h3 class="cb-settings__data__subtitle">
				<?php esc_html_e( 'Select the TCF Version below', 'cookiebot' ); ?>
			</h3>
			<label>
				<input <?php checked( 'IAB', $cookiebot_tcf_version ); ?>
						class="tcf-option"
						type="radio"
						name="cookiebot-tcf-version"
						value="IAB"/>
				<?php esc_html_e( 'TCF 2.0', 'cookiebot' ); ?>
			</label>
			<label class="recommended-item">
				<input <?php checked( 'TCFv2.2', $cookiebot_tcf_version ); ?>
						class="tcf-option"
						type="radio"
						name="cookiebot-tcf-version"
						value="TCFv2.2"/>
				<?php esc_html_e( 'TCF 2.2', 'cookiebot' ); ?>
				<span class="recommended-tag"><?php esc_html_e( 'New', 'cookiebot' ); ?></span>
			</label>
		</div>
	</div>
</div>
