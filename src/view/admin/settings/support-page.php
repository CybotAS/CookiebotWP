<?php
/**
 * @var string $manager_language
 */

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
	<div class="cb-wrapper">
		<?php $main_tabs->display( 'support' ); ?>
		<div class="cb-main__content">
			<h1 class="cb-main__page_title"><?php esc_html_e( 'Support', 'cookiebot' ); ?></h1>

			<div class="cb-support__content">
				<div class="cb-support__info__card">
					<h2 class="cb-support__info__title"><?php esc_html_e( 'Need help with your configuration?', 'cookiebot' ); ?></h2>
					<p  class="cb-support__info__text">
						<?php
						esc_html_e(
							'In our Help Center you find all the answers to your questions. If you have additional questions, create a support request and our Support Team will help out as soon as possible.',
							'cookiebot'
						);
						?>
					</p>
					<a href="https://support.cookiebot.com/hc/en-us" target="_blank" class="cb-btn cb-main-btn" rel="noopener">
						<?php
						esc_html_e(
							'Visit Cookiebot CMP Help Center',
							'cookiebot'
						);
						?>
					</a>
				</div>
				<div class="cb-support__video__card">
					<div class="cb-support__video__inner">
						<h2 class="cb-support__video__title"><?php esc_html_e( 'Video guide', 'cookiebot' ); ?></h2>
						<div class="cb-main__video">
							<iframe src="https://www.youtube.com/embed/QgB315qko-c"
									title="Cookiebot WordPress Installation"
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
									allowfullscreen></iframe>
						</div>
					</div>

				</div>
			</div>

			<div class="cb-faqs">
				<h2 class="cb-support__info__title">FAQ:</h2>
				<div class="cb-faq__container cb-faq--opened">
					<h3 class="cb-faq__question"><?php esc_html_e( 'How to find my Cookiebot™ ID', 'cookiebot' ); ?><span class="cb-faq__toggle"></span></h3>
					<div class="cb-faq__answer">
						<p class="cb-faq__answer__content">
							<ol>
								<li>
									<?php
									echo sprintf(
									// translators: the first placeholder string will be replaced with a html anchor open tag and the second placeholder string will be replaced by the html anchor closing tag
										esc_html__( 'Log in to your %1$sCookiebot CMP account%2$s.', 'cookiebot' ),
										'<a href="https://www.cookiebot.com/' . esc_html( $manager_language ) . '/account" target="_blank" rel="noopener">',
										'</a>'
									);
									?>
								</li>
								<li>
									<?php
									echo sprintf(
									// translators: the placeholder strings denote the positions of <b>, </b>, <b> and </b> HTML tags
										esc_html__( 'Go to %1$s"Settings"%2$s and setup your Cookiebot CMP', 'cookiebot' ),
										'<b>',
										'</b>'
									);
									?>
								</li>
								<li>
									<?php
									echo sprintf(
									// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
										esc_html__( 'Go to the %1$s"Your scripts"%2$s tab', 'cookiebot' ),
										'<b>',
										'</b>'
									);
									?>
								</li>
								<li><?php esc_html_e( 'Copy the value inside the data-cid parameter - eg.: abcdef12-3456-7890-abcd-ef1234567890', 'cookiebot' ); ?></li>
								<li>
									<?php
									echo sprintf(
									// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
										esc_html__( 'Add %1$s[cookie_declaration]%2$s shortcode to a page to show the declaration', 'cookiebot' ),
										'<b>',
										'</b>'
									);
									?>
								</li>
								<li><?php esc_html_e( 'Remember to change your scripts as described below', 'cookiebot' ); ?></li>
							</ol>
						</p>
					</div>
				</div>

				<div class="cb-faq__container">
					<h3 class="cb-faq__question"><?php esc_html_e( 'Add the Cookie Declaration to your website', 'cookiebot' ); ?><span class="cb-faq__toggle"></span></h3>
					<div class="cb-faq__answer">
						<p class="cb-faq__answer__content">
							<?php
							echo sprintf(
							// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
								esc_html__( 'Use the shortcode %1$s[cookie_declaration]%2$s to add the cookie declaration to a page or post. The cookie declaration will always show the latest version from Cookiebot CMP.', 'cookiebot' ),
								'<b>',
								'</b>'
							);
							?>
						</p>
						<p class="cb-faq__answer__content">
							<?php
							echo sprintf(
							// translators: the placeholder strings denote the positions of <i>, </i>, <b> and </b> HTML tags
								esc_html__( 'If you want to show the cookie declaration in a specific language, you can add the %1$s"lang"%2$s attribute, e.g. %3$s[cookie_declaration lang="de"]%4$s.', 'cookiebot' ),
								'<i>',
								'</i>',
								'<b>',
								'</b>'
							);
							?>
						</p>
						<p class="cb-faq__answer__content">
							<a href="https://www.youtube.com/watch?v=67XUgTUy3ok" target="_blank" class="cb-btn cb-main-btn" rel="noopener">
								<?php
								esc_html_e(
									'Watch video demonstration',
									'cookiebot'
								);
								?>
							</a>
						</p>
					</div>
				</div>

				<div class="cb-faq__container">
					<h3 class="cb-faq__question"><?php esc_html_e( 'Update your script tags', 'cookiebot' ); ?><span class="cb-faq__toggle"></span></h3>
					<div class="cb-faq__answer">
						<p class="cb-faq__answer__content">
							<?php
							esc_html_e(
								'To enable prior consent, apply the attribute "data-cookieconsent" to cookie-setting script tags on your website. Set the comma-separated value to one or more of the cookie categories "preferences", "statistics" and/or "marketing" in accordance with the types of cookies being set by each script. Finally, change the attribute "type" from "text/javascript" to "text/plain".',
								'cookiebot'
							);
							?>
						</p>
						<p class="cb-faq__answer__content">
							<?php
							echo sprintf(
							// translators: the placeholder strings denote the positions of <i>, </i>, <b> and </b> HTML tags
								esc_html__( 'Example on modifying an existing Google Analytics Universal script tag can be found %1$shere in step 4%2$s.', 'cookiebot' ),
								'<a href="https://www.cookiebot.com/en/manual-implementation/" target="_blank" rel="noopener">',
								'</a>'
							);
							?>
						</p>
						<code class="cb-faq__code">
							<?php
							$output = "<script type=\"text/plain\" data-cookieconsent=\"statistics\">
								(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
								ga('create', 'UA-00000000-0', 'auto');
								ga('send', 'pageview');
								</script>";
							echo nl2br( esc_html( $output ) );
							?>
						</code>
						<p class="cb-faq__answer__content">
							<a href="https://www.youtube.com/watch?v=MeHycvV2QCQ" target="_blank" class="cb-btn cb-main-btn" rel="noopener">
								<?php
								esc_html_e(
									'Watch video demonstration',
									'cookiebot'
								);
								?>
							</a>
						</p>
					</div>
				</div>

				<div class="cb-faq__container">
					<h3 class="cb-faq__question"><?php esc_html_e( 'Helper function to update your scripts', 'cookiebot' ); ?><span class="cb-faq__toggle"></span></h3>
					<div class="cb-faq__answer">
						<p class="cb-faq__answer__content">
							<?php
							esc_html_e(
								'You can update your scripts yourself. However, Cookiebot CMP also offers a small helper function that can make the work easier.',
								'cookiebot'
							);
							?>
						</p>
						<p class="cb-faq__answer__content">
							<?php esc_html_e( 'Update your script tags this way:', 'cookiebot' ); ?>
						</p>
						<?php
						printf(
						// translators: %1$s refers to the original script tag HTML, and %2$s refers to its replacement
							esc_html__( '%1$s to %2$s', 'cookiebot' ),
							'<code class="cb-faq__code">' . esc_html( '<script type="text/javascript">' ) . '</code>',
							'<code class="cb-faq__code">' . esc_html( '<script<?php echo function_exists(\'cookiebot_assist\') ? cookiebot_assist(\'marketing\') : \' type="text/javascript"\' ?>>' ) . '</code>'
						);
						?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
