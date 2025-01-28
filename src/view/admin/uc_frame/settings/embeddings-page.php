<div class="cb-settings__config__head-section">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Privacy Policy Sync', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Use our pre-defined, automatically generated embeddings to help you keep your Privacy Policy page in sync with your consent banner settings. This feature saves you time by automatically updating legally required information, so you don’t need to manually copy data into your Privacy Policy page. Once you’re done setting the options below, simply copy the code and paste it into your Privacy Policy page.', 'cookiebot' ); ?>
		</p>
		<input type="text" id="embedding-shortcode"
				value="<?php echo esc_attr( '[uc_embedding class="gdpr" show-toggle="false" type="all"]' ); ?>">
		<div class="cb-btn cb-main-btn" onclick="copyEmbedShortcode();" role="button" tabIndex="0">
			<?php
			esc_html_e(
				'Copy shortcode',
				'cookiebot'
			);
			?>
		</div>
	</div>
</div>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Sync options for privacy legislations', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Select the legislation you want to automatically sync with your Privacy Policy page.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<select name="cookiebot-embedding" id="cookiebot-embedding">
				<option value="gdpr"><?php esc_html_e( 'GDPR', 'cookiebot' ); ?></option>
				<option value="tcf"><?php esc_html_e( 'TCF', 'cookiebot' ); ?></option>
			</select>
		</div>
	</div>
</div>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Sync options for data processing services (DPS) ', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Define what to include on your Privacy Policy page: DPS categories only, categories with their services, a single service, or detailed information on both categories and services. Choose based on the level of detail you want to display.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<select name="cookiebot-embedding-type" id="cookiebot-embedding-type">
				<option class="gdpr" value="all"><?php esc_html_e( 'Services (Default)', 'cookiebot' ); ?></option>
				<option class="gdpr"
						value="category"><?php esc_html_e( 'Categories and services', 'cookiebot' ); ?></option>
				<option class="gdpr" value="category-only"><?php esc_html_e( 'Categories only', 'cookiebot' ); ?></option>
				<option class="gdpr"
						value="service-specific"><?php esc_html_e( 'Single service', 'cookiebot' ); ?></option>
				<option class="tcf hide-option" value="purposes"><?php esc_html_e( 'Purposes', 'cookiebot' ); ?></option>
				<option class="tcf hide-option" value="vendors"><?php esc_html_e( 'Vendors', 'cookiebot' ); ?></option>
			</select>
		</div>
	</div>
</div>
<div id="cookiebot-embedding-single-service-container" class="cb-settings__config__item hide-container">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Single Service ID', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Add the service ID that you want to display.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This feature is required.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<input type="text" id="cookiebot-embedding-single-service">
		</div>
	</div>
</div>
<div id="cookiebot-tcf-toggle-container" class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Privacy toggles', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Define whether you want the privacy toggles to be enabled and displayed on your Privacy Policy page.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-tcf-toggle">
				<input type="checkbox" name="cookiebot-tcf-toggle" id="cookiebot-tcf-toggle" value="1">
				<div class="switcher"></div>
				<?php esc_html_e( 'Enable privacy toggles', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>
