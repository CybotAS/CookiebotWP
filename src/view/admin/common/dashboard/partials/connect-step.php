<?php

use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

/**
 * @var string $form_id     Unique per instance ("new" or "existing").
 * @var int    $step_number
 * @var string $step_label  "STEP 2 OF 2", or '' when the step stands alone.
 * @var bool   $locked      Field and button disabled until "Create account" is clicked.
 * @var string $support_url
 */

$cb_dash_input_id   = 'cb-dash-cbid-' . $form_id;
$cb_dash_message_id = 'cb-dash-cbid-message-' . $form_id;
$cb_dash_where_id   = 'cb-dash-where-' . $form_id;
?>
<div class="cb-dash__card cb-dash__step" data-cb-connect data-cb-connect-locked="<?php echo $locked ? '1' : '0'; ?>">
	<span class="cb-dash__step-num" aria-hidden="true"><?php echo esc_html( (string) $step_number ); ?></span>
	<div class="cb-dash__step-body">
		<div class="cb-dash__step-text">
			<?php if ( $step_label ) : ?>
				<p class="cb-dash__step-label"><?php echo esc_html( $step_label ); ?></p>
			<?php endif; ?>
			<h3 class="cb-dash__h3"><?php esc_html_e( 'Connect Cookiebot to this site', 'cookiebot' ); ?></h3>
			<div class="cb-dash__help-row">
				<p class="cb-dash__body"><?php esc_html_e( 'Copy your Domain Group ID from Cookiebot (Implementation page) and paste it below.', 'cookiebot' ); ?></p>
				<?php include_view( 'admin/common/dashboard/partials/where-is-it.php', array( 'popover_id' => $cb_dash_where_id ) ); ?>
			</div>
		</div>

		<form class="cb-dash__field-row" data-cb-connect-form novalidate>
			<div class="cb-dash__field">
				<label class="cb-dash__label" for="<?php echo esc_attr( $cb_dash_input_id ); ?>">
					<?php esc_html_e( 'Domain Group ID', 'cookiebot' ); ?>
					<span class="cb-dash__label-muted"><?php esc_html_e( 'or Settings ID', 'cookiebot' ); ?></span>
				</label>
				<input type="text" class="cb-dash__input" id="<?php echo esc_attr( $cb_dash_input_id ); ?>"
					data-cb-connect-input
					aria-describedby="<?php echo esc_attr( $cb_dash_message_id ); ?>"
					placeholder="<?php esc_attr_e( 'e.g. 1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'cookiebot' ); ?>"
					autocomplete="off" spellcheck="false"
					<?php disabled( $locked ); ?>>
				<div class="cb-dash__message" id="<?php echo esc_attr( $cb_dash_message_id ); ?>" aria-live="polite" data-cb-connect-message hidden>
					<img class="cb-dash__message-icon cb-dash__message-icon--error" src="<?php echo esc_url( asset_url( 'img/dashboard/error.svg' ) ); ?>" width="24" height="24" alt="">
					<img class="cb-dash__message-icon cb-dash__message-icon--warning" src="<?php echo esc_url( asset_url( 'img/dashboard/warning.svg' ) ); ?>" width="24" height="24" alt="">
					<span class="cb-dash__message-text" data-cb-connect-message-text></span>
				</div>
			</div>
			<div class="cb-dash__field-action">
				<button type="submit" class="cb-dash__btn cb-dash__btn--primary cb-dash__btn--connect" data-cb-connect-button <?php disabled( $locked ); ?>>
					<img class="cb-dash__spinner" src="<?php echo esc_url( asset_url( 'img/dashboard/spinner.svg' ) ); ?>" width="24" height="24" alt="" hidden>
					<span data-cb-connect-button-label><?php esc_html_e( 'Connect', 'cookiebot' ); ?></span>
				</button>
			</div>
			<span class="screen-reader-text" aria-live="polite" data-cb-connect-status></span>
		</form>

		<div class="cb-dash__help-links">
			<a class="cb-dash__link" href="https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it" target="_blank" rel="noopener">
				<?php esc_html_e( 'How to find your Domain Group ID', 'cookiebot' ); ?>
				<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-outlined.svg' ) ); ?>" width="16" height="16" alt="">
			</a>
			<p class="cb-dash__small cb-dash__help-fallback">
				<?php esc_html_e( 'Using Usercentrics CMP? You can enter your Settings ID instead.', 'cookiebot' ); ?>
				<a class="cb-dash__link cb-dash__link--small" href="https://support.usercentrics.com/hc/en-us/articles/18097606499100-What-is-a-Settings-ID-and-where-can-I-find-it" target="_blank" rel="noopener">
					<?php esc_html_e( 'How to find it', 'cookiebot' ); ?>
					<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-outlined.svg' ) ); ?>" width="14" height="14" alt="">
				</a>
			</p>
		</div>
	</div>
</div>
