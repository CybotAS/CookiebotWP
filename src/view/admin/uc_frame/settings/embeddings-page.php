<div class="cb-settings__config__head-section">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Shortcode Generator:', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Select your options to generate the shortcode, then copy it on you privacy page..', 'cookiebot' ); ?>
		</p>
		<input type="text" id="embedding-shortcode" value="<?php echo esc_attr( '[uc_embedding class="gdpr" show-toggle="false" type="all"]' ); ?>">
		<div class="cb-btn cb-main-btn" onclick="copyEmbedShortcode();">
			<?php
			esc_html_e(
				'Copy shortcode to clipboard',
				'cookiebot'
			);
			?>
		</div>
	</div>
</div>
<div class="cb-settings__config__item">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Embedding class', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Select the class of the embedding.', 'cookiebot' ); ?>
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
			<?php esc_html_e( 'Embedding Type', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Select the type of the embedding.', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<select name="cookiebot-embedding-type" id="cookiebot-embedding-type">
				<option class="gdpr" value="all"><?php esc_html_e( 'Services (Default)', 'cookiebot' ); ?></option>
				<option class="gdpr" value="category"><?php esc_html_e( 'Categories and services', 'cookiebot' ); ?></option>
				<option class="gdpr" value="category-only"><?php esc_html_e( 'Categories only', 'cookiebot' ); ?></option>
				<option class="gdpr" value="service-specific"><?php esc_html_e( 'Single service', 'cookiebot' ); ?></option>
				<option class="tcf hide-option" value="purposes"><?php esc_html_e( 'Purposes', 'cookiebot' ); ?></option>
				<option class="tcf hide-option" value="vendors"><?php esc_html_e( 'Vendors', 'cookiebot' ); ?></option>
			</select>
		</div>
	</div>
</div>
<div id="cookiebot-embedding-single-service-container" class="cb-settings__config__item hide-container">
	<div class="cb-settings__config__content">
		<h3 class="cb-settings__config__subtitle">
			<?php esc_html_e( 'Service', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Add service ID as it is displayed on Usercentrics admin.', 'cookiebot' ); ?>
		</p>
		<p class="cb-general__info__note">
			<?php esc_html_e( 'This feature is only available, and it is required, on Single service type embedding class', 'cookiebot' ); ?>
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
			<?php esc_html_e( 'Display toggles', 'cookiebot' ); ?>
		</h3>
		<p class="cb-general__info__text">
			<?php esc_html_e( 'Display toggles or not', 'cookiebot' ); ?>
		</p>
	</div>
	<div class="cb-settings__config__data">
		<div class="cb-settings__config__data__inner">
			<label class="switch-checkbox" for="cookiebot-tcf-toggle">
				<input type="checkbox" name="cookiebot-tcf-toggle" id="cookiebot-tcf-toggle" value="1">
				<div class="switcher"></div>
				<?php esc_html_e( 'Enable toggles', 'cookiebot' ); ?>
			</label>
		</div>
	</div>
</div>
