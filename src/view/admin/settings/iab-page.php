<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle"><?php esc_html_e( 'IAB Integration:', 'cookiebot' ); ?></h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'If you want to use the IAB Framework TCF v2.0 within your Consent Management Platform (CMP) you can enable it on the right. Be aware that activating this could override some of the configurations you made with the default setup defined by the IAB.', 'cookiebot' ); ?>
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
					<?php checked( 1, get_option( 'cookiebot-iab' ) ); ?>>
				<div class="switcher"></div>
				<?php esc_html_e( 'IAB TCF v2.0 integration', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>
