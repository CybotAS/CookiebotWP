<div class="wrap">
	<h1><?php esc_html_e( 'Support', 'cookiebot' ); ?></h1>
	<h2><?php esc_html_e( 'How to find my Cookiebot ID', 'cookiebot' ); ?></h2>
	<p>
	<ol>
		<li>
			<?php
			esc_html_e(
				'Log in to your <a href="https://www.cookiebot.com/goto/account" target="_blank">Cookiebot account</a>.',
				'cookiebot'
			);
			?>
		</li>
		<li><?php esc_html_e( 'Go to <b>Manage</b> > <b>Settings</b> and add setup your Cookiebot', 'cookiebot' ); ?></li>
		<li><?php esc_html_e( 'Go to the <b>"Your scripts"</b> tab', 'cookiebot' ); ?></li>
		<li><?php esc_html_e( 'Copy the value inside the data-cid parameter - eg.: abcdef12-3456-7890-abcd-ef1234567890', 'cookiebot' ); ?></li>
		<li><?php esc_html_e( 'Add <b>[cookie_declaration]</b> shortcode to a page to show the declation', 'cookiebot' ); ?></li>
		<li><?php esc_html_e( 'Remember to change your scripts as descripted below', 'cookiebot' ); ?></li>
	</ol>
	</p>
	<h2><?php esc_html_e( 'Add the Cookie Declaration to your website', 'cookiebot' ); ?></h2>
	<p>
		<?php
		esc_html_e(
			'Use the shortcode <b>[cookie_declaration]</b> to add the cookie declaration a page or post. The cookie declaration will always show the latest version from Cookiebot.',
			'cookiebot'
		);
		?>
		<br/>
		<?php
		esc_html_e(
			'If you need to force language of the cookie declaration, you can add the <i>lang</i> attribute. Eg. <b>[cookie_declaration lang="de"]</b>.',
			'cookiebot'
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
        /* phpcs:disable */
		echo htmlentities( '<script type="text/plain" data-cookieconsent="statistics">' ) . '<br>';
		echo htmlentities( "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');" ) . '<br>';
		echo htmlentities( "ga('create', 'UA-00000000-0', 'auto');" ) . '<br />';
		echo htmlentities( "ga('send', 'pageview');" ) . '<br />';
		echo htmlentities( '</script>' ) . '<br />';
		/* phpcs:enable */
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
			/* translators: first script is an example script tag, second is the modified script tag */
		esc_html__( '%1$s to %2$s', 'cookiebot' ),
		'<code>' . htmlentities( '<script type="text/javascript">' ) . '</code>', //phpcs:disable
		'<code>' . htmlentities( '<script<?php echo cookiebot_assist(\'marketing\') ?>>' ) . '</code>'
	);
	//phpcs:enable
	?>
</div>
