<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Official_Facebook_Pixel extends Addons_Base {

    const TRUNK = 'https://plugins.svn.wordpress.org/official-facebook-pixel/trunk/';

	public function setUp() {
	}

	public function test_official_facebook_pixel_pageview() {
		$content = $this->curl_get_content( self::TRUNK . 'core/FacebookPixel.php' );
		$snippet = "<!-- Facebook Pixel Code -->
<script type='text/javascript'>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
</script>
<!-- End Facebook Pixel Code -->";

		$this->assertNotFalse( strpos( $content, $snippet) );
	}

	public function test_official_facebook_pixel_caldera_form() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressCalderaForm.php' );
		$snippet = "add_action(
      'caldera_forms_ajax_return',
      array(__CLASS__, 'injectLeadEvent'),
      10, 2);";

		$this->assertNotFalse( strpos( $content, $snippet) );
	}

	public function test_official_facebook_pixel_contact_form_7() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressContactForm7.php' );
		$snippets[] = "add_action(
      'wpcf7_submit',
      array(__CLASS__, 'trackServerEvent'),
      10, 2);";

		$snippets[] = "add_action(
      'wpcf7_feedback_response',
      array(__CLASS__, 'injectLeadEvent'),
      20, 2);";

		foreach($snippets as $snippet) {
		    $this->assertNotFalse( strpos( $content, $snippet) );
        }
	}

	public function test_official_facebook_pixel_formidable_form() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressFormidableForm.php' );
		$snippets[] = "add_action(
      'frm_after_create_entry',
      array(__CLASS__, 'trackServerEvent'),
      20,
      2
    );";
		$snippets[] = "add_action(
      'wp_footer',
       array(__CLASS__, 'injectLeadEvent'),
       20
    );";

		foreach($snippets as $snippet) {
		    $this->assertNotFalse( strpos( $content, $snippet) );
        }
	}

	public function test_official_facebook_pixel_easy_digital_downloads() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressEasyDigitalDownloads.php' );
		$snippets[] = "add_action(
      'edd_payment_receipt_after',
      array(__CLASS__, 'trackPurchaseEvent'),
      10, 2);";
		$snippets[] = "add_action(
      'edd_after_download_content',
      array(__CLASS__, 'injectAddToCartListener')
    );";
		$snippets[] = "self::addPixelFireForHook(array(
      'hook_name' => 'edd_after_checkout_cart',
      'classname' => __CLASS__,
      'inject_function' => 'injectInitiateCheckoutEvent'));";
		$snippets[] = "add_action(
      'edd_after_download_content',
      array(__CLASS__, 'injectViewContentEvent'),
      40, 1
    );";

		foreach($snippets as $snippet) {
		    $this->assertNotFalse( strpos( $content, $snippet) );
        }
	}

	public function test_official_facebook_pixel_gravity_forms() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressGravityForms.php' );
		$snippet = "add_filter(
      'gform_confirmation',
      array(__CLASS__, 'injectLeadEvent'),
      10, 4);";

		$this->assertNotFalse( strpos( $content, $snippet) );
	}

	public function test_official_facebook_pixel_mailchimp_for_wp() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressMailchimpForWp.php' );
		$snippet = "self::addPixelFireForHook(array(
      'hook_name' => 'mc4wp_form_subscribed',
      'classname' => __CLASS__,
      'inject_function' => 'injectLeadEvent'));
  }";

		$this->assertNotFalse( strpos( $content, $snippet) );
	}

	public function test_official_facebook_pixel_ninja_forms() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressNinjaForms.php' );
		$snippet = "add_action(
      'ninja_forms_submission_actions',
      array(__CLASS__, 'injectLeadEvent'),
      10, 3);";

		$this->assertNotFalse( strpos( $content, $snippet) );
	}

	public function test_official_facebook_pixel_woocommerce() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressWooCommerce.php' );
		$snippets[] = "add_action('woocommerce_after_checkout_form',
        array(__CLASS__, 'trackInitiateCheckout'),
        40);";
		$snippets[] = "add_action( 'woocommerce_add_to_cart',
        array(__CLASS__, 'trackAddToCartEvent'),
        40, 4);";
		$snippets[] = "add_action( 'woocommerce_thankyou',
        array(__CLASS__, 'trackPurchaseEvent'),
        40);";
		$snippets[] = "add_action( 'woocommerce_payment_complete',
        array(__CLASS__, 'trackPurchaseEvent'),
        40);";

		foreach($snippets as $snippet) {
		    $this->assertNotFalse( strpos( $content, $snippet) );
        }
	}

	public function test_official_facebook_pixel_WPECommerce() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressWPECommerce.php' );
		$snippets[] = "add_action('wpsc_add_to_cart_json_response',
      array(__CLASS__, 'injectAddToCartEvent'), 11);";
		$snippets[] = "self::addPixelFireForHook(array(
      'hook_name' => 'wpsc_before_shopping_cart_page',
      'classname' => __CLASS__,
      'inject_function' => 'injectInitiateCheckoutEvent'));";
		$snippets[] = "add_action(
      'wpsc_transaction_results_shutdown',
      array(__CLASS__, 'injectPurchaseEvent'), 11, 3);";

        foreach($snippets as $snippet) {
            $this->assertNotFalse( strpos( $content, $snippet) );
        }
	}

    public function test_official_facebook_pixel_wp_forms() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressWPForms.php' );
        $snippets[] = "add_action(
      'wp_footer',
       array(__CLASS__, 'injectLeadEvent'),
       20
    );";
        $snippets[] = "add_action(
      'wpforms_process_before',
      array(__CLASS__, 'trackEvent'),
      20,
      2
    );";

        foreach($snippets as $snippet) {
            $this->assertNotFalse( strpos( $content, $snippet) );
        }
    }

    public function test_official_facebook_pixel_integration_base() {
        $content = $this->curl_get_content( self::TRUNK . 'integration/FacebookWordpressIntegrationBase.php' );

        $snippet = 'add_action(
        \'wp_footer\',
        $hook_wp_footer,
        11);';

        $this->assertNotFalse( strpos( $content, $snippet) );
    }
}