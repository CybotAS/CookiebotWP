<?php
/**
 * @var string $cbid
 * @var string $ruleset_id
 * @var string $source
 * @var string $auto
 */
?>
<script	id="usercentrics-cmp"
    data-usercentrics="Usercentrics Consent Management Platform"
	data-<?php echo esc_html( $ruleset_id )?>-id="<?php echo esc_attr( $cbid ); ?>"
	src="<?php echo esc_attr( $source ); ?>"
    type="text/javascript"
    async
></script>
<?php if( $auto ) : ?>
    <script src="https://web.cmp.usercentrics.eu/modules/autoblocker.js"></script>
<?php endif; ?>
