<?php

namespace cybot\cookiebot\addons {

	use cybot\cookiebot\addons\controller\addons\add_to_any\Add_To_Any;
	use cybot\cookiebot\addons\controller\addons\addthis\Addthis;
	use cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
	use cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed;
	use cybot\cookiebot\addons\controller\addons\custom_facebook_feed_pro\Custom_Facebook_Feed_Pro;
	use cybot\cookiebot\addons\controller\addons\embed_autocorrect\Embed_Autocorrect;
	use cybot\cookiebot\addons\controller\addons\enfold\Enfold;
	use cybot\cookiebot\addons\controller\addons\enhanced_ecommerce_for_woocommerce_store\Enhanced_Ecommerce_For_WooCommerce_Store;
	use cybot\cookiebot\addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce;
	use cybot\cookiebot\addons\controller\addons\ga_google_analytics\Ga_Google_Analytics;
	use cybot\cookiebot\addons\controller\addons\gadwp\Gadwp;
	use cybot\cookiebot\addons\controller\addons\google_analyticator\Google_Analyticator;
	use cybot\cookiebot\addons\controller\addons\google_analytics\Google_Analytics;
	use cybot\cookiebot\addons\controller\addons\google_analytics_plus\Google_Analytics_Plus;
	use cybot\cookiebot\addons\controller\addons\google_site_kit\Google_Site_Kit;
	use cybot\cookiebot\addons\controller\addons\hubspot_leadin\Hubspot_Leadin;
	use cybot\cookiebot\addons\controller\addons\hubspot_tracking_code\Hubspot_Tracking_Code;
	use cybot\cookiebot\addons\controller\addons\instagram_feed\Instagram_Feed;
	use cybot\cookiebot\addons\controller\addons\jetpack\Jetpack;
	use cybot\cookiebot\addons\controller\addons\litespeed_cache\Litespeed_Cache;
	use cybot\cookiebot\addons\controller\addons\matomo\Matomo;
	use cybot\cookiebot\addons\controller\addons\ninja_forms\Ninja_Forms;
	use cybot\cookiebot\addons\controller\addons\official_facebook_pixel\Official_Facebook_Pixel;
	use cybot\cookiebot\addons\controller\addons\optinmonster\Optinmonster;
	use cybot\cookiebot\addons\controller\addons\pixel_caffeine\Pixel_Caffeine;
	use cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder;
	use cybot\cookiebot\addons\controller\addons\wd_google_analytics\Wd_Google_Analytics;
	use cybot\cookiebot\addons\controller\addons\woocommerce_google_analytics_pro\Woocommerce_Google_Analytics_Pro;
	use cybot\cookiebot\addons\controller\addons\wp_analytify\Wp_Analytify;
	use cybot\cookiebot\addons\controller\addons\wp_google_analytics_events\Wp_Google_Analytics_Events;
	use cybot\cookiebot\addons\controller\addons\wp_mautic\Wp_Mautic;
	use cybot\cookiebot\addons\controller\addons\wp_piwik\Wp_Piwik;
	use cybot\cookiebot\addons\controller\addons\wp_rocket\Wp_Rocket;
	use cybot\cookiebot\addons\controller\addons\wp_seopress\Wp_Seopress;
	use cybot\cookiebot\addons\controller\addons\wpforms\Wpforms;

	const PLUGIN_ADDONS = array(
		Add_To_Any::class,
		Addthis::class,
		CAOS_Host_Analyticsjs_Local::class,
		Custom_Facebook_Feed::class,
		Custom_Facebook_Feed_Pro::class,
		Enhanced_Ecommerce_For_WooCommerce_Store::class,
		Facebook_For_Woocommerce::class,
		Ga_Google_Analytics::class,
		Gadwp::class,
		Google_Analyticator::class,
		Google_Analytics::class,
		Google_Analytics_Plus::class,
		Google_Site_Kit::class,
		Wd_Google_Analytics::class,
		Simple_Share_Buttons_Adder::class,
		Optinmonster::class,
		Pixel_Caffeine::class,
		Woocommerce_Google_Analytics_Pro::class,
		Wp_Analytify::class,
		Wp_Google_Analytics_Events::class,
		Wp_Mautic::class,
		Wp_Piwik::class,
		Wp_Rocket::class,
		Wp_Seopress::class,
		Wpforms::class,
		Official_Facebook_Pixel::class,
		Ninja_Forms::class,
		Jetpack::class,
		Hubspot_Leadin::class,
		Hubspot_Tracking_Code::class,
		Litespeed_Cache::class,
		matomo::class,
		Instagram_Feed::class,
	);

	const THEME_ADDONS = array(
		Enfold::class,
	);

	const OTHER_ADDONS = array(
		Embed_Autocorrect::class,
	);
}
