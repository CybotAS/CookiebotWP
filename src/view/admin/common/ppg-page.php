<?php
/**
 * @var string $hero_image
 * @var bool   $is_installed
 * @var bool   $is_active
 */

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'ppg' ); ?>
		<div class="cb-main__content">
			<h1 class="cb-ppg__page-title"><?php esc_html_e( 'Explore our plugins', 'cookiebot' ); ?></h1>

			<div class="cb-ppg__divider"></div>

			<div class="cb-ppg__plugin-container">
				<div class="cb-ppg__plugin-header">
					<h2 class="cb-ppg__plugin-name">
						<?php esc_html_e( 'Usercentrics Privacy Policy Generator', 'cookiebot' ); ?>
					</h2>
					<div class="cb-ppg__plugin-actions">
						<?php if ( $is_active ) : ?>
							<span class="cb-btn cb-success-btn">
								<?php esc_html_e( 'Active', 'cookiebot' ); ?>
							</span>
						<?php elseif ( $is_installed ) : ?>
							<button type="button" id="cb-ppg-activate-btn" class="cb-btn cb-main-btn">
								<?php esc_html_e( 'Activate', 'cookiebot' ); ?>
							</button>
						<?php else : ?>
							<button type="button" id="cb-ppg-install-btn" class="cb-btn cb-main-btn">
								<?php esc_html_e( 'Install Now', 'cookiebot' ); ?>
							</button>
						<?php endif; ?>
					</div>
				</div>

				<div class="cb-ppg__hero">
					<img src="<?php echo esc_url( $hero_image ); ?>"
						alt="<?php esc_attr_e( 'Usercentrics Privacy Policy Generator - Generate your privacy policy in minutes', 'cookiebot' ); ?>"
						class="cb-ppg__hero-image" />
				</div>
			</div>
		</div>
	</div>
</div>
