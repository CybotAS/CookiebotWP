<?php
/**
 * @var string $two_week_review_ignore
 * @var string $two_week_review_temp
 */
?>
<li>
	<span class="dashicons dashicons-external"></span>
	<a href="https://wordpress.org/support/plugin/cookiebot/reviews?filter=5&rate=5#new-post" target="_blank">
		<?php
		echo esc_html__(
			'Sure! I\'d love to!',
			'cookiebot'
		);
		?>
	</a>
</li>
<li>
	<span class="dashicons dashicons-smiley"></span>
	<a href="<?php echo esc_html( $two_week_review_ignore ); ?>">
		<?php
		echo esc_html__(
			'I\'ve already left a review',
			'cookiebot'
		);
		?>
	</a>
</li>
<li>
	<span class="dashicons dashicons-calendar-alt"></span>
	<a href="<?php echo esc_html( $two_week_review_temp ); ?>">
		<?php
		echo esc_html__(
			'Maybe Later',
			'cookiebot'
		);
		?>
	</a>
</li>
<li>
	<span class="dashicons dashicons-dismiss"></span>
	<a href="<?php echo esc_html( $two_week_review_ignore ); ?>">
		<?php
		echo esc_html__(
			'Never show again',
			'cookiebot'
		);
		?>
	</a>
</li>
