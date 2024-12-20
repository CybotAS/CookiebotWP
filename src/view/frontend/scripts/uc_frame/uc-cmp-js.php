<?php
/**
 * @var string $cbid
 * @var string $ruleset_id
 * @var bool $iab_enabled
 * @var string $auto
 */
?>
<?php if ( $iab_enabled ) : ?>
	<script src="https://web.cmp.usercentrics.eu/tcf/stub.js"></script>
<?php endif; ?>
<?php if ( $auto ) : ?>
	<script src="https://web.cmp.usercentrics.eu/modules/autoblocker.js"></script>
<?php endif; ?>
<script id="usercentrics-cmp"
		data-usercentrics="Usercentrics Consent Management Platform"
		data-<?php echo esc_html( $ruleset_id ); ?>-id="<?php echo esc_attr( $cbid ); ?>"
		src="https://web.cmp.usercentrics.eu/ui/loader.js"
		type="text/javascript"
		async
></script>
