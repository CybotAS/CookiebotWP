<div class="wrap">
	<h1><?php esc_html_e( 'Support', 'cookiebot' ); ?></h1>
	<h2><?php esc_html_e( 'How to find my Cookiebot ID', 'cookiebot' ); ?></h2>
	<div>
		<ol>
			<li>
				<?php
				echo sprintf(
				// translators: the first placeholder string will be replaced with a html anchor open tag and the second placeholder string will be replaced by the html anchor closing tag
					esc_html__( 'Log in to your %1$sCookiebot account%2$s.', 'cookiebot' ),
					'<a href="https://www.cookiebot.com/goto/account" target="_blank">',
					'</a>'
				);
				?>
			</li>
			<li>
				<?php
				echo sprintf(
				// translators: the placeholder strings denote the positions of <b>, </b>, <b> and </b> HTML tags
					esc_html__( 'Go to %1$sManage%2$s > %3$sSettings%4$s and add setup your Cookiebot', 'cookiebot' ),
					'<b>',
					'</b>',
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
					esc_html__( 'Add %1$s[cookie_declaration]%2$s shortcode to a page to show the declation', 'cookiebot' ),
					'<b>',
					'</b>'
				);
				?>
			</li>
			<li><?php esc_html_e( 'Remember to change your scripts as descripted below', 'cookiebot' ); ?></li>
		</ol>
	</div>
	<h2><?php esc_html_e( 'Add the Cookie Declaration to your website', 'cookiebot' ); ?></h2>
	<p>
		<?php
		echo sprintf(
		// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
			esc_html__( 'Use the shortcode %1$s[cookie_declaration]%2$s to add the cookie declaration a page or post. The cookie declaration will always show the latest version from Cookiebot.', 'cookiebot' ),
			'<b>',
			'</b>'
		);
		?>
		<br/>
		<?php
		echo sprintf(
		// translators: the placeholder strings denote the positions of <i>, </i>, <b> and </b> HTML tags
			esc_html__( 'If you need to force language of the cookie declaration, you can add the %1$slang%2$s attribute. Eg. %3$s[cookie_declaration lang="de"]%4$s.', 'cookiebot' ),
			'<i>',
			'</i>',
			'<b>',
			'</b>'
		);
		?>
	</p>
	<p>
		<a href="https://www.youtube.com/watch?v=OCXz2bt4H_w" target="_blank" class="button">
			<?php
			esc_html_e(
				'Watch video demonstration',
				'cookiebot'
			);
			?>
		</a>
	</p>
	<h2><?php esc_html_e( 'Update your script tags', 'cookiebot' ); ?></h2>
	<p>
		<?php
		esc_html_e(
			'To enable prior consent, apply the attribute "data-cookieconsent" to cookie-setting script tags on your website. Set the comma-separated value to one or more of the cookie categories "preferences", "statistics" and "marketing" in accordance with the types of cookies being set by each script. Finally change the attribute "type" from "text/javascript" to "text/plain". Example on modifying an existing Google Analytics Universal script tag.',
			'cookiebot'
		);
		?>
	</p>
	<code>
		<?php
		$output = <<<HTML
<script type="text/plain" data-cookieconsent="statistics">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-00000000-0', 'auto');
ga('send', 'pageview');
</script>
HTML;
		echo nl2br( esc_html( $output ) );
		?>
	</code>
	<p>
		<a href="https://www.youtube.com/watch?v=MeHycvV2QCQ" target="_blank" class="button">
			<?php
			esc_html_e(
				'Watch video demonstration',
				'cookiebot'
			);
			?>
		</a>
	</p>

	<h2><?php esc_html_e( 'Helper function to update your scripts', 'cookiebot' ); ?></h2>
	<p>
		<?php
		esc_html_e(
			'You are able to update your scripts yourself. However, Cookiebot also offers a small helper function that makes the work easier.',
			'cookiebot'
		);
		?>
		<br/>
		<?php esc_html_e( 'Update your script tags this way:', 'cookiebot' ); ?>
	</p>
	<?php
	printf(
	// translators: %1$s refers to the original script tag HTML, and %2$s refers to its replacement
		esc_html__( '%1$s to %2$s', 'cookiebot' ),
		'<code>' . esc_html( '<script type="text/javascript">' ) . '</code>',
		'<code>' . esc_html( '<script<?php echo cookiebot_assist(\'marketing\') ?>>' ) . '</code>'
	);
	?>
</div>
