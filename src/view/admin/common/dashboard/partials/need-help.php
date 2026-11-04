<?php

defined( 'ABSPATH' ) || exit;

use function cybot\cookiebot\lib\asset_url;

$cb_dash_video_id = 'q8qDVa7Uv0M';
?>
<section class="cb-dash__need-help">
	<h2 class="cb-dash__h2"><?php esc_html_e( 'Need help?', 'cookiebot' ); ?></h2>
	<div class="cb-dash__card cb-dash__video-card">
		<div class="cb-dash__video-text">
			<h3 class="cb-dash__h3"><?php esc_html_e( 'Watch the setup guide', 'cookiebot' ); ?></h3>
			<p class="cb-dash__body"><?php esc_html_e( 'A short video that walks you through connecting Cookiebot to WordPress.', 'cookiebot' ); ?></p>
			<a class="cb-dash__link cb-dash__link--external"
				href="https://www.cookiebot.com/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin_dashboard&utm_content=need_help_card"
				target="_blank" rel="noopener">
				<?php esc_html_e( 'Learn more about Cookiebot CMP', 'cookiebot' ); ?>
				<img src="<?php echo esc_url( asset_url( 'img/dashboard/launch-blue-16.svg' ) ); ?>" width="16" height="16" alt="">
			</a>
		</div>
		<div class="cb-dash__video" data-cb-video="<?php echo esc_attr( $cb_dash_video_id ); ?>">
			<button type="button" class="cb-dash__video-play" data-cb-video-play>
				<img class="cb-dash__video-thumb" src="<?php echo esc_url( asset_url( 'img/dashboard/video-thumbnail.jpg' ) ); ?>" width="352" height="198" alt="">
				<img class="cb-dash__video-play-bg" src="<?php echo esc_url( asset_url( 'img/dashboard/play-button-bg.svg' ) ); ?>" width="80" height="80" alt="">
				<img class="cb-dash__video-play-icon" src="<?php echo esc_url( asset_url( 'img/dashboard/play.svg' ) ); ?>" width="64" height="64" alt="">
				<span class="screen-reader-text"><?php esc_html_e( 'Play the setup guide video', 'cookiebot' ); ?></span>
			</button>
		</div>
	</div>
</section>
