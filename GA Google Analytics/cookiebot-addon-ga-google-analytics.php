<?php
/**
 * Plugin Name:	Cookiebot Addon for `GA Google Analytics` 
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen
*/ 

function cookiebot_addon_ga_google_analytics() {
	//Check if MonsterInsights is loaded.
	if(!function_exists('ga_google_analytics_init')) { return; }
	//Check if Cookiebot is activated and active.
	if(!function_exists('cookiebot_active') || !cookiebot_active()) { return; } 
	
	//Remove GA Google action and replace it with our own
	if(has_action('wp_head','ga_google_analytics_tracking_code')) {
		remove_action('wp_head','ga_google_analytics_tracking_code');
		add_action('wp_head','cookiebot_ga_google_analytics_tracking_code');
	}
	elseif(has_action('wp_footer','ga_google_analytics_tracking_code')) {
		remove_action('wp_footer','ga_google_analytics_tracking_code');
		add_action('wp_footer','cookiebot_ga_google_analytics_tracking_code');
	}
}

/* Use manipulated source code from GA Google Analytics */
function cookiebot_ga_google_analytics_tracking_code() {
	extract(ga_google_analytics_options());
	
	if (empty($tracking_id)) return;
	
	if (empty($tracking_method)) return;
	
	if (current_user_can('administrator') && $disable_admin) return;
	
	if ($custom && $custom_location) echo $custom . "\n";
	
	if ($tracking_method == 3) {
		
		cookiebot_ga_google_analytics_legacy($options);
		
	} elseif ($tracking_method == 2) {
		
		cookiebot_ga_google_analytics_global($options);
		
	} else {
		
		if ($universal) {
			
			cookiebot_ga_google_analytics_universal($options);
			
		} else {
			
			cookiebot_ga_google_analytics_legacy($options);
			
		}
		
	}
	
	if ($custom && !$custom_location) echo $custom . "\n";
}
function cookiebot_ga_google_analytics_universal() {
	
	extract(ga_google_analytics_options());
	
	$custom_code = ga_google_analytics_custom($custom_code);
	
	$ga_display = "ga('require', 'displayfeatures');";
	$ga_link    = "ga('require', 'linkid', 'linkid.js');";
	$ga_anon    = "ga('set', 'anonymizeIP', true);";
	$ga_ssl     = "ga('set', 'forceSSL', true);";
	
	?>

		<script type="text/plain" data-cookieconsent="statistics">
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
			ga('create', '<?php echo $tracking_id; ?>', 'auto'<?php if ($tracker_object) echo ', '. $tracker_object; ?>);
			<?php 
				if ($display_ads) echo $ga_display  . "\n\t\t\t";
				if ($link_attr)   echo $ga_link     . "\n\t\t\t";
				if ($anonymize)   echo $ga_anon     . "\n\t\t\t";
				if ($force_ssl)   echo $ga_ssl      . "\n\t\t\t";
				if ($custom_code) echo $custom_code . "\n\t\t\t";
			?>ga('send', 'pageview');
		</script>

	<?php
	
}

function cookiebot_ga_google_analytics_global() {
	
	extract(ga_google_analytics_options());
	
	$custom_code = ga_google_analytics_custom($custom_code);
	
	?>

		<script type="text/plain" data-cookieconsent="statistics" async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_id; ?>"></script>
		<script type="text/plain" data-cookieconsent="statistics">
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments)};
			gtag('js', new Date());
			gtag('config', '<?php echo $tracking_id; ?>'<?php if ($tracker_object) echo ', '. $tracker_object; ?>);
			<?php if ($custom_code) echo $custom_code; ?>

		</script>

	<?php
	
}

function cookiebot_ga_google_analytics_legacy() {
	
	extract(ga_google_analytics_options());
	
	$custom_code = ga_google_analytics_custom($custom_code);
	
	$ga_alt  = "('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';";
	$ga_src  = "('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
	$ga_link = "var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';\n\t\t\t_gaq.push(['_require', 'inpage_linkid', pluginUrl]);";
	$ga_anon = "_gaq.push(['_gat._anonymizeIp']);";
	$ga_ssl  = "_gaq.push(['_gat._forceSSL']);";
	
	if ($display_ads) $ga_src = $ga_alt;
	
	?>

		<script type="text/plain" data-cookieconsent="statistics">
			var _gaq = _gaq || [];
			<?php 
				if ($link_attr)   echo $ga_link     . "\n\t\t\t";
				if ($anonymize)   echo $ga_anon     . "\n\t\t\t"; 
				if ($force_ssl)   echo $ga_ssl      . "\n\t\t\t"; 
				if ($custom_code) echo $custom_code . "\n\t\t\t"; 
			?>_gaq.push(['_setAccount', '<?php echo $tracking_id; ?>']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = <?php echo $ga_src . "\n"; ?>
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>

	<?php
	
}



/*
 * We add the action after wp_loaded and replace the original GA Google
 * Analytics action with our own adjusted version.
 */
add_action('wp_loaded', 'cookiebot_addon_ga_google_analytics', 5);
