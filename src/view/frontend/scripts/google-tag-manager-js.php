<?php
/** @var string $data_layer */
?>
<script>
	<?php if ( get_option( 'cookiebot-iab' ) ) : ?>
	window ["gtag_enable_tcf_support"] = true;
	<?php endif; ?>
	(function (w, d, s, l, i) {
		w[l] = w[l] || []; w[l].push({'gtm.start':new Date().getTime(), event: 'gtm.js'});
		var f = d.getElementsByTagName(s)[0],  j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
		j.async = true; j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
		f.parentNode.insertBefore(j, f);})(
		window,
		document,
		'script',
		'<?php echo esc_js( $data_layer ); ?>',
		'<?php echo esc_js( get_option( 'cookiebot-gtm-id' ) ); ?>'
	);
</script>
