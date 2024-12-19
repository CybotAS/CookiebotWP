<?php
/**
 * @var string $allowed_vendors
 * @var string $allowed_google_ac_vendors
 * @var string $allowed_purposes
 * @var string $allowed_special_purposes
 * @var string $allowed_features
 * @var string $allowed_special_features
 * @var string $vendor_restrictions
 */
?>
<script id="CookiebotConfiguration" type="application/json" data-cookieconsent="ignore">
	{
		"Frameworks": {
			"IABTCF2": {
				"AllowedVendors": [<?php echo esc_js( $allowed_vendors ); ?>],
				"AllowedGoogleACVendors": [<?php echo esc_js( $allowed_google_ac_vendors ); ?>],
				"AllowedPurposes": [<?php echo esc_js( $allowed_purposes ); ?>],
				"AllowedSpecialPurposes": [<?php echo esc_js( $allowed_special_purposes ); ?>],
				"AllowedFeatures": [<?php echo esc_js( $allowed_features ); ?>],
				"AllowedSpecialFeatures": [<?php echo esc_js( $allowed_special_features ); ?>],
	<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	"VendorRestrictions": [<?php echo $vendor_restrictions; ?>]
			}
		}
	}
</script>
