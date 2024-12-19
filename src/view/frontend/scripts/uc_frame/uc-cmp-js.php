<?php
/**
 * @var string $cbid
 * @var string $ruleset_id
 * @var bool $iab_enabled
 * @var string $auto
 */
?>
<?php if ( $iab_enabled ) : ?>
	<script src="https://web.cmp.usercentrics-sandbox.eu/tcf/stub.js"></script>
<?php endif; ?>
<?php if ( $auto ) : ?>
	<script src="https://web.cmp.usercentrics-sandbox.eu/modules/autoblocker.js"></script>
<?php endif; ?>
<script id="usercentrics-cmp"
		data-usercentrics="Usercentrics Consent Management Platform"
		data-<?php echo esc_html( $ruleset_id ); ?>-id="<?php echo esc_attr( $cbid ); ?>"
		src="https://web.cmp.usercentrics-sandbox.eu/ui/loader.js"
		type="text/javascript"
		data-sandbox="true"
		async
></script>
