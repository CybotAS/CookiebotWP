<?php
/**
 * @var string $two_week_review_ignore
 * @var string $two_week_review_temp
 * @var string $visit_review_temp
 */
?>
<li>
	<span class="dashicons dashicons-external"></span>
	<a href="https://wordpress.org/support/plugin/cookiebot/reviews?filter=5&rate=5#new-post" target="_blank"
	   rel="noopener">
		<?php
		echo esc_html__(
			'Ok, you deserve it',
			'cookiebot'
		);
		?>
	</a>
</li>
<li>
	<span class="dashicons dashicons-calendar-alt"></span>
	<a href="<?php echo esc_html( $visit_review_temp ); ?>">
		<?php
		echo esc_html__(
			'Nope, maybe Later',
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
			'I already did it',
			'cookiebot'
		);
		?>
	</a>
</li>
