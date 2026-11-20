<?php
/**
 * @var string $data_layer
 * @var string $url_passthrough
 * @var bool|string $consent_attribute
 * @var string $script_type
 */

$script_attributes = array(
    'type' => $script_type,
);
if ($consent_attribute) {
    $script_attributes['data-cookieconsent'] = $consent_attribute;
}

ob_start();
?>
	window.<?php echo esc_js( $data_layer ); ?> = window.<?php echo esc_js( $data_layer ); ?> || [];

	function gtag() {
		<?php echo esc_js( $data_layer ); ?>.push(arguments);
	}

	gtag("consent", "default", {
		ad_personalization: "denied",
		ad_storage: "denied",
		ad_user_data: "denied",
		analytics_storage: "denied",
		functionality_storage: "denied",
		personalization_storage: "denied",
		security_storage: "granted",
		wait_for_update: 500,
	});
	gtag("set", "ads_data_redaction", true);
	<?php
	if ( $url_passthrough ) {
		echo /** @lang JavaScript */
			'gtag("set", "url_passthrough", true);' . PHP_EOL;
	}
	?>

<?php wp_print_inline_script_tag( ob_get_clean(), $script_attributes ); ?>