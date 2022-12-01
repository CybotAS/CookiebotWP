<?php
/** @var string $data_layer */
?>
<script data-cookieconsent="ignore">
    window.<?php echo esc_js( $data_layer ); ?> = window.<?php echo esc_js( $data_layer ); ?> || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag("consent", "default", {
        ad_storage: "denied",
        analytics_storage: "denied",
        functionality_storage: "denied",
        personalization_storage: "denied",
        security_storage: "granted",
        wait_for_update: 500,
    });
    gtag("set", "ads_data_redaction", true);
    gtag("set", "url_passthrough", true);
</script>