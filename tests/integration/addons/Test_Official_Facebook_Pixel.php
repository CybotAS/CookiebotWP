<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Official_Facebook_Pixel extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_pageview() {
		$content = Official_Facebook_Pixel::get_svn_file_content( 'core/class-facebookpixel.php' );
		$snippet = <<<TEXT
<!-- Meta Pixel Code -->
<script type='text/javascript'>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js?v=next');
</script>
<!-- End Meta Pixel Code -->
TEXT;

		$this->assertNotFalse( strpos( $content, $snippet ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_caldera_form() {
		$content = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresscalderaform.php' );
		$snippet = <<<TEXT
add_action(
            'caldera_forms_ajax_return',
            array( __CLASS__, 'injectLeadEvent' ),
            10,
            2
        );
TEXT;

		$this->assertNotFalse( strpos( $content, $snippet ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_contact_form_7() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresscontactform7.php' );
		$snippets[] = <<<TEXT
add_action(
            'wpcf7_submit',
            array( __CLASS__, 'trackServerEvent' ),
            10,
            2
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'wpcf7_feedback_response',
            array( __CLASS__, 'injectLeadEvent' ),
            20,
            2
        );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_formidable_form() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpressformidableform.php' );
		$snippets[] = <<<TEXT
add_action(
            'frm_after_create_entry',
            array( __CLASS__, 'trackServerEvent' ),
            20,
            2
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'wp_footer',
            array( __CLASS__, 'injectLeadEvent' ),
            20
        );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_easy_digital_downloads() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresseasydigitaldownloads.php' );
		$snippets[] = <<<TEXT
add_action(
            'edd_payment_receipt_after',
            array( __CLASS__, 'trackPurchaseEvent' ),
            10,
            2
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'edd_after_download_content',
            array( __CLASS__, 'injectAddToCartListener' )
        );
TEXT;

		$snippets[] = <<<TEXT
self::add_pixel_fire_for_hook(
            array(
                'hook_name'       => 'edd_after_checkout_cart',
                'classname'       => __CLASS__,
                'inject_function' => 'injectInitiateCheckoutEvent',
            )
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'edd_after_download_content',
            array( __CLASS__, 'injectViewContentEvent' ),
            40,
            1
        );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_mailchimp_for_wp() {
		$content = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpressmailchimpforwp.php' );
		$snippet = <<<TEXT
self::add_pixel_fire_for_hook(
        array(
            'hook_name'       => 'mc4wp_form_subscribed',
            'classname'       => __CLASS__,
            'inject_function' => 'injectLeadEvent',
        )
    );
TEXT;

		$this->assertNotFalse( strpos( $content, $snippet ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_ninja_forms() {
		$content = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpressninjaforms.php' );
		$snippet = <<<TEXT
add_action(
            'ninja_forms_submission_actions',
            array( __CLASS__, 'injectLeadEvent' ),
            10,
            3
        );
TEXT;

		$this->assertNotFalse( strpos( $content, $snippet ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_woocommerce() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresswoocommerce.php' );
		$snippets[] = <<<TEXT
add_action(
                'woocommerce_after_checkout_form',
                array( __CLASS__, 'trackInitiateCheckout' ),
                40
            );
TEXT;

		$snippets[] = <<<TEXT
add_action(
                'woocommerce_add_to_cart',
                array( __CLASS__, 'trackAddToCartEvent' ),
                40,
                4
            );
TEXT;

		$snippets[] = <<<TEXT
add_action(
                'woocommerce_thankyou',
                array( __CLASS__, 'trackPurchaseEvent' ),
                40
            );
TEXT;

		$snippets[] = <<<TEXT
add_action(
                'woocommerce_payment_complete',
                array( __CLASS__, 'trackPurchaseEvent' ),
                40
            );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_WPECommerce() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresswpecommerce.php' );
		$snippets[] = <<<TEXT
add_action(
            'wpsc_add_to_cart_json_response',
            array( __CLASS__, 'injectAddToCartEvent' ),
            11
        );
TEXT;

		$snippets[] = <<<TEXT
self::add_pixel_fire_for_hook(
            array(
                'hook_name'       => 'wpsc_before_shopping_cart_page',
                'classname'       => __CLASS__,
                'inject_function' => 'injectInitiateCheckoutEvent',
            )
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'wpsc_transaction_results_shutdown',
            array( __CLASS__, 'injectPurchaseEvent' ),
            11,
            3
        );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_wp_forms() {
		$content    = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpresswpforms.php' );
		$snippets[] = <<<TEXT
add_action(
            'wp_footer',
            array( __CLASS__, 'injectLeadEvent' ),
            20
        );
TEXT;

		$snippets[] = <<<TEXT
add_action(
            'wpforms_process_before',
            array( __CLASS__, 'trackEvent' ),
            20,
            2
        );
TEXT;

		foreach ( $snippets as $snippet ) {
			$this->assertNotFalse( strpos( $content, $snippet ) );
		}
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_official_facebook_pixel_integration_base() {
		$content = Official_Facebook_Pixel::get_svn_file_content( 'integration/class-facebookwordpressintegrationbase.php' );

		$snippet = <<<TEXT
add_action(
            'wp_footer',
            \$hook_wp_footer,
            11
        );
TEXT;

		$this->assertNotFalse( strpos( $content, $snippet ) );
	}
}
