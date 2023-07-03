<?php
/**
 * @var string $cookiebot_logo
 */
?>
<div class="cookiebot-popup-container">
	<div id="cookiebot-popup">
		<div class="cb-review__header">
			<div class="cb-review__logo"><img src="<?php echo esc_url( $cookiebot_logo ); ?>" alt="Cookiebot"></div>
			<h2 id="cb-review__title"><?php esc_html_e( 'Cookiebot CMP Deactivation', 'cookiebot' ); ?></h2>
			<div id="cb-review__close" class="dashicons dashicons-dismiss"></div>
		</div>
		<form id="cb-review__form" method="POST">
			<p><?php esc_html_e( 'We are sad to lose you. Take a moment to help us improve?', 'cookiebot' ); ?></p>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="1">
					<?php esc_html_e( 'The installation is too complicated', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="2">
					<?php esc_html_e( 'I found a plugin that better serves my needs', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="3">
					<?php esc_html_e( 'Missing features / did not meet my expectations', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="4">
					<?php esc_html_e( 'I need more customization options', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="5">
					<?php esc_html_e( 'The premium plan is too expensive', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="6">
					<?php esc_html_e( 'I’m only deactivating the plugin temporarily', 'cookiebot' ); ?>
				</label>
			</div>

			<div>
				<label class="cb-review__form--item">
					<input type="radio" name="cookiebot-review-option" value="7" data-custom-field="true">
					<?php esc_html_e( 'Other', 'cookiebot' ); ?>
				</label>

				<div class="cb-review__form--item__custom">
					<textarea id="cb-review__other-description" name="other-description" placeholder="<?php esc_html_e( 'Please specify here', 'cookiebot' ); ?>" rows="1"></textarea>
				</div>
			</div>

			<div class="consent-item">
				<label class="cb-review__form--item">
					<input id="cb-review__debug-reason" type="checkbox" name="cookiebot-review-debug" value="true" data-custom-field="true">
					<span><b>(Optional)</b>
						<?php
						esc_html_e(
							' By checking this box, you agree to submit troubleshooting information
                        and allow us to contact you regarding the problem if necessary. 
                        ',
							'cookiebot'
						);
						?>
						<br>
						<?php
						esc_html_e(
							'The information will be kept for no longer than 90 days. 
                        You may revoke this consent at any time, e.g. by sending an email to ',
							'cookiebot'
						);
						?>
						<a href="mailto:unsubscribe@usercentrics.com">unsubscribe@usercentrics.com</a>
					</span>
				</label>
			</div>

			<div id="cb-review__alert"><?php esc_html_e( 'Please select one option', 'cookiebot' ); ?></div>

			<div class="cb-review__actions">
				<a id="cb-review__skip" href="#">Skip and Deactivate</a>
				<input type="submit" id="cb-review__submit" value="Submit and Deactivate">
			</div>
			<p class="cb-review__policy">See our <a href="https://www.cookiebot.com/en/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a></p>
			<input type="hidden" name="cookiebot-review-send" value="Cookiebot_Review_Send">
		</form>
	</div>
</div>
