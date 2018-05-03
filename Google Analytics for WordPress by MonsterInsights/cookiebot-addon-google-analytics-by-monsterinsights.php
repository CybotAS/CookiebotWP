<?php
/**
 * Plugin Name:	Cookiebot Addon for `Google Analytics for Wordpress by MonsterInsights` 
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen
*/ 

function cookiebot_addon_monsterinsights() {
	//Check if MonsterInsights is loaded.
	if(!defined('MONSTERINSIGHTS_PLUGIN_FILE')) { return; }
	//Check if Cookiebot is activated and active.
	if(!function_exists('cookiebot_active') || !cookiebot_active()) { return; } 
	
	//Remove Monster Insights action - need to be same priority as added = 6.
	remove_action('wp_head', 'monsterinsights_tracking_script', 6);
	
	//Include classes to simulate MonsterInsights embed.
	require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/class-tracking-abstract.php';	
	
	//Check the current mode
	$mode = is_preview() ? 'preview' : 'analytics';
	
	if(!is_preview()) {
		//Simulate the way MonsterInsights create tracking script
		do_action('monsterinsights_tracking_before_' . $mode );
		do_action('monsterinsights_tracking_before', $mode );
		
		
		//Create tracking code - and fix script tag
		require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-analytics.php';
		$tracking = new MonsterInsights_Tracking_Analytics();
		$output = $tracking->frontend_output();
		$output = preg_replace('/<script[^>]*>/i','<script type="text/plain" data-cookieconsent="statistics">',$output);
		echo $output;
		
		do_action('monsterinsights_tracking_after_' . $mode );
    do_action('monsterinsights_tracking_after', $mode );

	}
	else {
		//Continue as normal
		monsterinsights_tracking_script();
	}
}

/*
 * Since monsterinsights_tracking_script has priority of 6 we need to 
 * hook in before to ensure we can remove the action and replacd it
 * with our own.
 */
add_action('wp_head', 'cookiebot_addon_monsterinsights', 5);
