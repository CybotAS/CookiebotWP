<?php
/**
 * @var string $gtm_id
 * @var string $data_layer
 * @var bool|string $consent_attribute
 * @var string $script_type
 * @var bool $iab
 */

$script_attributes = array(
    'type' => $script_type,
);
if ($consent_attribute) {
    $script_attributes['data-cookieconsent'] = $consent_attribute;
}

ob_start();
?>
	<?php if ( $iab ) : ?>
	window ["gtag_enable_tcf_support"] = true;
	<?php endif; ?>
	(function (w, d, s, l, i) {
		w[l] = w[l] || [];
		w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
		var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
		j.async = true;
		j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
		f.parentNode.insertBefore(j, f);
	})(
		window,
		document,
		'script',
		'<?php echo esc_js( $data_layer ); ?>',
		'<?php echo esc_js( $gtm_id ); ?>'
	);

<?php
wp_print_inline_script_tag( ob_get_clean(), $script_attributes ); ?>
