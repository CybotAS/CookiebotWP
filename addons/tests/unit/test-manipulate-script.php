<?php

namespace cookiebot_addons\tests\unit;

class Test_Manipulate_Script extends \WP_UnitTestCase {
	
	/**
	 * Validate if the plugins in addons.json do exist as a class in addons controller directory.
	 */
	public function test_manipulate_script() {
		$buffer = "<head>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='alternate' type='application/rss+xml' title='Ursula Sandner - Use your strength Feed' href='https://www.ursula-sandner.com/feed/'>
<link rel='apple-touch-icon' sizes='57x57' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-57x57.png'>
<link rel='apple-touch-icon' sizes='60x60' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-60x60.png'>
<link rel='apple-touch-icon' sizes='72x72' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-72x72.png'>
<link rel='apple-touch-icon' sizes='76x76' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-76x76.png'>
<link rel='apple-touch-icon' sizes='114x114' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-114x114.png'>
<link rel='apple-touch-icon' sizes='120x120' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-120x120.png'>
<link rel='apple-touch-icon' sizes='144x144' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-144x144.png'>
<link rel='apple-touch-icon' sizes='152x152' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-152x152.png'>
<link rel='apple-touch-icon' sizes='180x180' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/apple-touch-icon-180x180.png'>
<link rel='icon' type='image/png' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/favicon-32x32.png' sizes='32x32'>
<link rel='icon' type='image/png' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/favicon-194x194.png' sizes='194x194'>
<link rel='icon' type='image/png' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/favicon-96x96.png' sizes='96x96'>
<link rel='icon' type='image/png' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/android-chrome-192x192.png' sizes='192x192'>
<link rel='icon' type='image/png' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/favicon-16x16.png' sizes='16x16'>
<link rel='manifest' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/manifest.json'>
<link rel='shortcut icon' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/favicon.ico'>
<meta name='msapplication-TileColor' content='#da532c'>
<meta name='msapplication-TileImage' content='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/mstile-144x144.png'>
<meta name='msapplication-config' content='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/images/favicon/browserconfig.xml'>
<meta name='theme-color' content='#ffffff'>


<!--[if lt IE 9]>
		<script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
		<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
	<![endif]-->
<title>Ursula Sandner - Use your strength</title>
<link rel='alternate' hreflang='ro-ro' href='https://www.ursula-sandner.com'>
<link rel='alternate' hreflang='en-us' href='https://www.ursula-sandner.com/en/'>

<meta name='description' content='Sunt life-coach, psihoterapeut și trainer de programare neurolingvistică. Iubesc ceea ce fac și în fiecare zi îmi practic meseria cu entuziasm și bucurie. Dar nu a fost dintotdeauna așa... Până să ajung aici viața m-a purtat pe cărări întortocheate sau, mai bine zis, eu am ales să explorez toate aceste cărări până când mi-am dat seama că ceea ce fac acum este parte din misiunea vieții mele și că nu doresc să mă mulțumesc cu mai puțin de atât.'>
<link rel='canonical' href='https://www.ursula-sandner.com/'>
<meta property='og:locale' content='ro_RO'>
<meta property='og:type' content='website'>
<meta property='og:title' content='Ursula Sandner - Use your strength'>
<meta property='og:description' content='Sunt life-coach, psihoterapeut și trainer de programare neurolingvistică. Iubesc ceea ce fac și în fiecare zi îmi practic meseria cu entuziasm și bucurie. Dar nu a fost dintotdeauna așa... Până să ajung aici viața m-a purtat pe cărări întortocheate sau, mai bine zis, eu am ales să explorez toate aceste cărări până când mi-am dat seama că ceea ce fac acum este parte din misiunea vieții mele și că nu doresc să mă mulțumesc cu mai puțin de atât.'>
<meta property='og:url' content='https://www.ursula-sandner.com/'>
<meta property='og:site_name' content='Ursula Sandner - Use your strength'>
<meta property='og:image' content='https://www.ursula-sandner.com/wp-content/uploads/2015/05/facebook-default-uys.png'>
<meta property='og:image:secure_url' content='https://www.ursula-sandner.com/wp-content/uploads/2015/05/facebook-default-uys.png'>
<meta property='og:image:width' content='470'>
<meta property='og:image:height' content='246'>
<script src='https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js' type='text/javascript' async=''></script><script async='' src='//w.likebtn.com/js/w/widget.js'></script><script type='text/javascript' charset='UTF-8' async='' src='https://consent.cookiebot.com/7eebc73a-cd27-4eff-acdc-08662a546220/cc.js?renew=false&amp;referer=https%3A%2F%2Fwww.ursula-sandner.com&amp;culture=RO&amp;dnt=false&amp;forceshow=false&amp;cbid=7eebc73a-cd27-4eff-acdc-08662a546220&amp;whitelabel=false&amp;brandid=Cookiebot'></script><script async='' src='//w.likebtn.com/js/w/widget.js'></script><script type='application/ld+json'>{'@context':'https:\/\/schema.org','@type':'WebSite','@id':'#website','url':'https:\/\/www.ursula-sandner.com\/','name':'Ursula Sandner - Use your strength','potentialAction':{'@type':'SearchAction','target':'https:\/\/www.ursula-sandner.com\/?s={search_term_string}','query-input':'required name=search_term_string'}}</script>
<script type='application/ld+json'>{'@context':'https:\/\/schema.org','@type':'Person','url':'https:\/\/www.ursula-sandner.com\/','sameAs':['https:\/\/www.facebook.com\/SandnerMindConsulting','https:\/\/instagram.com\/ursula.sandner','https:\/\/twitter.com\/psihotimisoara'],'@id':'#person','name':'Ursula Yvonne Sandner'}</script>

<link rel='dns-prefetch' href='//s.w.org'>
<link rel='alternate' type='application/rss+xml' title='Flux comentarii Ursula Sandner - Use your strength » Prima pagină' href='https://www.ursula-sandner.com/prima-pagina/feed/'>
<style>.monsterinsights-async-hide { opacity: 0 !important} </style>
<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'monsterinsights-async-hide','dataLayer',4000,
{'GTM-5RSGFLM':true});</script>

<script type='text/plain' data-cfasync='false' data-cookieconsent='statistics'>
	var mi_version         = '7.1.1';
	var mi_track_user      = true;
	var mi_no_track_reason = '';
	
	var disableStr = 'ga-disable-UA-22754433-1';

	/* Function to detect opted out users */
	function __gaTrackerIsOptedOut() {
		return document.cookie.indexOf(disableStr + '=true') > -1;
	}

	/* Disable tracking if the opt-out cookie exists. */
	if ( __gaTrackerIsOptedOut() ) {
		window[disableStr] = true;
	}

	/* Opt-out function */
	function __gaTrackerOptout() {
	  document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
	  window[disableStr] = true;
	}
	
	if ( mi_track_user ) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','__gaTracker');

window.ga = __gaTracker;		__gaTracker('create', 'UA-22754433-1', 'auto', {'useAmpClientId':true});
		__gaTracker('set', 'forceSSL', true);
		__gaTracker('set', 'anonymizeIp', true);
		__gaTracker('require', 'ec');
		__gaTracker('require', 'linkid', 'linkid.js');
		__gaTracker('set', 'displayFeaturesTask', null);
		__gaTracker('require', 'GTM-5RSGFLM');
		__gaTracker('send','pageview');
	} else {
		console.log( '' );
		(function() {
			/* https://developers.google.com/analytics/devguides/collection/analyticsjs/ */
			var noopfn = function() {
				return null;
			};
			var noopnullfn = function() {
				return null;
			};
			var Tracker = function() {
				return null;
			};
			var p = Tracker.prototype;
			p.get = noopfn;
			p.set = noopfn;
			p.send = noopfn;
			var __gaTracker = function() {
				var len = arguments.length;
				if ( len === 0 ) {
					return;
				}
				var f = arguments[len-1];
				if ( typeof f !== 'object' || f === null || typeof f.hitCallback !== 'function' ) {
					console.log( 'Not running function __gaTracker(' + arguments[0] + ' ....) because you are not being tracked. ' + mi_no_track_reason );
					return;
				}
				try {
					f.hitCallback();
				} catch (ex) {

				}
			};
			__gaTracker.create = function() {
				return new Tracker();
			};
			__gaTracker.getByName = noopnullfn;
			__gaTracker.getAll = function() {
				return [];
			};
			__gaTracker.remove = noopfn;
			window['__gaTracker'] = __gaTracker;
			window.ga = __gaTracker;		})();
		}
</script>

<script type='text/javascript'>
			window._wpemojiSettings = {'baseUrl':'https:\/\/s.w.org\/images\/core\/emoji\/11\/72x72\/','ext':'.png','svgUrl':'https:\/\/s.w.org\/images\/core\/emoji\/11\/svg\/','svgExt':'.svg','source':{'concatemoji':'https:\/\/www.ursula-sandner.com\/wp-includes\/js\/wp-emoji-release.min.js'}};
			!function(a,b,c){function d(a,b){var c=String.fromCharCode;l.clearRect(0,0,k.width,k.height),l.fillText(c.apply(this,a),0,0);var d=k.toDataURL();l.clearRect(0,0,k.width,k.height),l.fillText(c.apply(this,b),0,0);var e=k.toDataURL();return d===e}function e(a){var b;if(!l||!l.fillText)return!1;switch(l.textBaseline='top',l.font='600 32px Arial',a){case'flag':return!(b=d([55356,56826,55356,56819],[55356,56826,8203,55356,56819]))&&(b=d([55356,57332,56128,56423,56128,56418,56128,56421,56128,56430,56128,56423,56128,56447],[55356,57332,8203,56128,56423,8203,56128,56418,8203,56128,56421,8203,56128,56430,8203,56128,56423,8203,56128,56447]),!b);case'emoji':return b=d([55358,56760,9792,65039],[55358,56760,8203,9792,65039]),!b}return!1}function f(a){var c=b.createElement('script');c.src=a,c.defer=c.type='text/javascript',b.getElementsByTagName('head')[0].appendChild(c)}var g,h,i,j,k=b.createElement('canvas'),l=k.getContext&&k.getContext('2d');for(j=Array('flag','emoji'),c.supports={everything:!0,everythingExceptFlag:!0},i=0;i<j.length;i++)c.supports[j[i]]=e(j[i]),c.supports.everything=c.supports.everything&&c.supports[j[i]],'flag'!==j[i]&&(c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&c.supports[j[i]]);c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&!c.supports.flag,c.DOMReady=!1,c.readyCallback=function(){c.DOMReady=!0},c.supports.everything||(h=function(){c.readyCallback()},b.addEventListener?(b.addEventListener('DOMContentLoaded',h,!1),a.addEventListener('load',h,!1)):(a.attachEvent('onload',h),b.attachEvent('onreadystatechange',function(){'complete'===b.readyState&&c.readyCallback()})),g=c.source||{},g.concatemoji?f(g.concatemoji):g.wpemoji&&g.twemoji&&(f(g.twemoji),f(g.wpemoji)))}(window,document,window._wpemojiSettings);
		</script><script src='https://www.ursula-sandner.com/wp-includes/js/wp-emoji-release.min.js' type='text/javascript' defer=''></script>
<style type='text/css'>
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel='stylesheet' id='contact-form-7-css' href='https://www.ursula-sandner.com/wp-content/plugins/contact-form-7/includes/css/styles.css' type='text/css' media='all'>
<link rel='stylesheet' id='dedo-css-css' href='https://www.ursula-sandner.com/wp-content/plugins/delightful-downloads/assets/css/delightful-downloads.min.css' type='text/css' media='all'>
<link rel='stylesheet' id='likebtn_style-css' href='https://www.ursula-sandner.com/wp-content/plugins/likebtn-like-button/public/css/style.css' type='text/css' media='all'>
<link rel='stylesheet' id='follow-up-emails-css' href='https://www.ursula-sandner.com/wp-content/plugins/woocommerce-follow-up-emails/templates/followups.css' type='text/css' media='all'>
<link rel='stylesheet' id='wpml-legacy-horizontal-list-0-css' href='//www.ursula-sandner.com/wp-content/plugins/sitepress-multilingual-cms/templates/language-switchers/legacy-list-horizontal/style.css' type='text/css' media='all'>
<link rel='stylesheet' id='ywot_style-css' href='https://www.ursula-sandner.com/wp-content/plugins/yith-woocommerce-order-tracking/assets/css/ywot_style.css' type='text/css' media='all'>
<link rel='stylesheet' id='wc-bundle-style-css' href='https://www.ursula-sandner.com/wp-content/plugins/woocommerce-product-bundles/assets/css/wc-pb-frontend.css' type='text/css' media='all'>
<link rel='stylesheet' id='sage_css-css' href='https://www.ursula-sandner.com/wp-content/themes/ursula-sandner-2015/dist/styles/main-dc01f269.css' type='text/css' media='all'>
<script type='text/javascript'>
/* <![CDATA[ */
var wp_sentry = {'dsn':'https:\/\/935f99b8fbe14f3b95c53ff8b820c3b3@sentry.io\/293758','options':{'release':'1.0','environment':'unspecified','tags':{'wordpress':'4.9.8','language':'ro-RO'},'user':{'id':0,'name':'anonymous'}}};
/* ]]> */
</script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/wp-sentry-integration/public/raven-3.26.3.min.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var monsterinsights_frontend = {'js_events_tracking':'true','is_debug_mode':'false','download_extensions':'doc,exe,js,pdf,ppt,tgz,zip,xls','inbound_paths':'','home_url':'https:\/\/www.ursula-sandner.com','track_download_as':'event','internal_label':'int','hash_tracking':'false'};
/* ]]> */
</script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/google-analytics-premium/assets/js/frontend.min.js'></script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-includes/js/jquery/jquery.js'></script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-includes/js/jquery/jquery-migrate.min.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var likebtn_eh_data = {'ajaxurl':'https:\/\/www.ursula-sandner.com\/wp-admin\/admin-ajax.php','security':'5b3b461d95'};
/* ]]> */
</script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/likebtn-like-button/public/js/frontend.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var FUE = {'ajaxurl':'https:\/\/www.ursula-sandner.com\/wp-admin\/admin-ajax.php','ajax_loader':'https:\/\/www.ursula-sandner.com\/wp-content\/plugins\/woocommerce-follow-up-emails\/templates\/images\/ajax-loader.gif'};
/* ]]> */
</script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/woocommerce-follow-up-emails/templates/js/fue-account-subscriptions.js'></script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/yith-woocommerce-order-tracking/assets/js/jquery.tooltipster.min.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var ywot = {'p':''};
/* ]]> */
</script>
<script type='text/javascript' src='https://www.ursula-sandner.com/wp-content/plugins/yith-woocommerce-order-tracking/assets/js/ywot.js'></script>
<link rel='https://api.w.org/' href='https://www.ursula-sandner.com/wp-json/'>
<link rel='shortlink' href='https://www.ursula-sandner.com/'>
<link rel='alternate' type='application/json+oembed' href='https://www.ursula-sandner.com/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fwww.ursula-sandner.com%2F'>
<link rel='alternate' type='text/xml+oembed' href='https://www.ursula-sandner.com/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fwww.ursula-sandner.com%2F&amp;format=xml'>
<meta name='generator' content='WPML ver:4.0.7 stt:1,46;'>
<script id='Cookiebot' src='https://consent.cookiebot.com/uc.js' data-cbid='7eebc73a-cd27-4eff-acdc-08662a546220' data-culture='RO' type='text/javascript' async=''></script>
<script type='text/javascript'>
    var USY = {'theme_dir_uri':'https:\/\/www.ursula-sandner.com\/wp-content\/themes\/ursula-sandner-2015','promotions_active':1,'promotions_minimize_time':'7'};
  </script>
<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>
<meta name='onesignal' content='wordpress-plugin'>
<link rel='manifest' href='https://www.ursula-sandner.com/wp-content/plugins/onesignal-free-web-push-notifications/sdk_files/manifest.json.php?gcm_sender_id='>
<script src='https://cdn.onesignal.com/sdks/OneSignalSDK.js' async=''/>
<script>

      window.OneSignal = window.OneSignal || [];

      OneSignal.push( function() {
        OneSignal.SERVICE_WORKER_UPDATER_PATH = 'OneSignalSDKUpdaterWorker.js.php';
        OneSignal.SERVICE_WORKER_PATH = 'OneSignalSDKWorker.js.php';
        OneSignal.SERVICE_WORKER_PARAM = { scope: '/' };

        OneSignal.setDefaultNotificationUrl('https://www.ursula-sandner.com');
        var oneSignal_options = {};
        window._oneSignalInitOptions = oneSignal_options;

        oneSignal_options['wordpress'] = true;
oneSignal_options['appId'] = 'eedf1600-3f95-47ee-a5f3-df67051e1bcf';
oneSignal_options['autoRegister'] = true;
oneSignal_options['welcomeNotification'] = { };
oneSignal_options['welcomeNotification']['title'] = 'Dr. Ursula Sandner';
oneSignal_options['welcomeNotification']['message'] = 'Îți urez success în călătoria propriei tale dezvoltări';
oneSignal_options['path'] = 'https://www.ursula-sandner.com/wp-content/plugins/onesignal-free-web-push-notifications/sdk_files/';
oneSignal_options['safari_web_id'] = 'web.onesignal.auto.145f18a4-510a-4781-b676-50fa3f7fa700';
oneSignal_options['persistNotification'] = true;
oneSignal_options['promptOptions'] = { };
oneSignal_options['notifyButton'] = { };
oneSignal_options['notifyButton']['enable'] = true;
oneSignal_options['notifyButton']['position'] = 'bottom-right';
oneSignal_options['notifyButton']['theme'] = 'default';
oneSignal_options['notifyButton']['size'] = 'large';
oneSignal_options['notifyButton']['prenotify'] = true;
oneSignal_options['notifyButton']['showCredit'] = false;
oneSignal_options['notifyButton']['text'] = {};
oneSignal_options['notifyButton']['text']['message.prenotify'] = 'Click aici pentru a te abona la notificări';
oneSignal_options['notifyButton']['text']['tip.state.unsubscribed'] = 'Abonează-te la notificări';
oneSignal_options['notifyButton']['text']['tip.state.subscribed'] = 'Te-ai abonat la notificări';
oneSignal_options['notifyButton']['text']['tip.state.blocked'] = 'Ai blocat notificările';
oneSignal_options['notifyButton']['text']['message.action.subscribed'] = 'Bun venit în comunitate!';
oneSignal_options['notifyButton']['text']['message.action.resubscribed'] = 'Te-ai re-abonat la notificări';
oneSignal_options['notifyButton']['text']['message.action.unsubscribed'] = 'Nu vei mai primi notificări de acum înainte';
oneSignal_options['notifyButton']['text']['dialog.main.title'] = 'Gestionează notificările';
oneSignal_options['notifyButton']['text']['dialog.main.button.subscribe'] = 'Abonează-te';
oneSignal_options['notifyButton']['text']['dialog.main.button.unsubscribe'] = 'Dezbonează-te';
oneSignal_options['notifyButton']['text']['dialog.blocked.title'] = 'Deblochează notificările';
oneSignal_options['notifyButton']['text']['dialog.blocked.message'] = 'Urmează aceste instrucțiuni pentru a te abona la notificări';
              OneSignal.init(window._oneSignalInitOptions);
                    });

      function documentInitOneSignal() {
        var oneSignal_elements = document.getElementsByClassName('OneSignal-prompt');

        var oneSignalLinkClickHandler = function(event) { OneSignal.push(['registerForPushNotifications']); event.preventDefault(); };        for(var i = 0; i < oneSignal_elements.length; i++)
          oneSignal_elements[i].addEventListener('click', oneSignalLinkClickHandler, false);
      }

      if (document.readyState === 'complete') {
           documentInitOneSignal();
      }
      else {
           window.addEventListener('load', function(event){
               documentInitOneSignal();
          });
      }
    </script>


<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic%7CCardo:400,700,400italic%7CMerriweather:400,300,700&amp;subset=latin,latin-ext,latin,latin-ext,latin,latin-ext' media='all'><style type='text/css' id='CookieConsentStateDisplayStyles'>.cookieconsent-optin-preferences,.cookieconsent-optin-statistics,.cookieconsent-optin-marketing,.cookieconsent-optin{display:none;}.cookieconsent-optout-preferences,.cookieconsent-optout-statistics,.cookieconsent-optout-marketing,.cookieconsent-optout{display:block;}</style><link type='text/css' rel='stylesheet' href='//w.likebtn.com/css/w/widget.css?v=36'><style type='text/css' id='CookiebotDialogStyle'>#CybotCookiebotDialog,#CybotCookiebotDialogBodyUnderlay{animation:none;animation-fill-mode:none;animation-name:none;border-collapse:separate;border-image:none;bottom:auto;caption-side:top;clear:none;clip:auto;columns:auto;column-count:auto;column-fill:balance;column-gap:normal;column-rule:medium none currentColor;column-rule-color:currentColor;column-rule-style:none;column-rule-width:none;column-span:1;column-width:auto;content:normal;counter-increment:none;counter-reset:none;cursor:auto;display:inline;empty-cells:show;hyphens:none;max-height:none;min-height:0;min-width:0;orphans:0;overflow-x:visible;overflow-y:visible;page-break-after:auto;page-break-before:auto;page-break-inside:auto;perspective:none;right:auto;tab-size:8;table-layout:auto;text-align-last:auto;text-decoration:none;text-decoration-color:inherit;text-decoration-line:none;text-decoration-style:solid;text-indent:0;text-shadow:none;text-transform:none;transform:none;transition:none;transition-property:none;vertical-align:baseline;white-space:normal;widows:0;width:auto;word-spacing:normal;opacity:1;font-weight:400;box-sizing:content-box;outline:0;top:0;left:0;height:auto;border-spacing:0}#CybotCookiebotDialog a,#CybotCookiebotDialog a:hover{text-decoration:underline;color:#2a2a2a}#CybotCookiebotDialog,#CybotCookiebotDialogBodyContentTitle,#CybotCookiebotDialogBodyUnderlay{margin:0;visibility:visible;letter-spacing:normal;float:none}#CybotCookiebotDialog,#CybotCookiebotDialog::after,#CybotCookiebotDialog::before,.CybotCookiebotDialogBodyButton,.CybotCookiebotDialogBodyButton::after,.CybotCookiebotDialogBodyButton::before{box-sizing:content-box}#CybotCookiebotDialogBodyUnderlay{animation-delay:0;animation-direction:normal;animation-duration:0;animation-iteration-count:1;animation-play-state:running;animation-timing-function:ease;backface-visibility:visible;background:#121212;background-clip:border-box;background-origin:padding-box;background-position-x:0;background-position-y:0;background-size:auto auto;border:0;border-width:medium;border-color:inherit;border-bottom:0;border-bottom-color:inherit;border-left:0;border-left-color:inherit;border-radius:0;border-right:0;border-right-color:inherit;border-top:0;border-top-color:inherit;box-shadow:none;color:inherit;font:400;font-family:inherit;font-size:medium;font-style:normal;font-variant:normal;line-height:normal;list-style:disc;max-width:none;outline-width:medium;perspective-origin:50% 50%;text-align:inherit;transform-style:flat;transition-delay:0s;transition-duration:0s;transition-timing-function:ease;position:absolute;padding:0;z-index:2147483630;overflow:hidden}#CybotCookiebotDialog{animation-delay:0;animation-direction:normal;animation-duration:0;animation-iteration-count:1;animation-play-state:running;animation-timing-function:ease;backface-visibility:visible;background:#ffffff;background-clip:border-box;background-origin:padding-box;background-position-x:0;background-position-y:0;background-size:auto auto;font:400;font-style:normal;font-variant:normal;list-style:disc;outline-width:medium;padding:0 0 8px;perspective-origin:50% 50%;transform-style:flat;transition-delay:0s;transition-duration:0s;transition-timing-function:ease;max-width:574px;position:fixed;overflow:hidden;filter:Alpha(opacity=100);z-index:2147483640;color:#2a2a2a;font-family:'Segoe UI',Arial,Helvetica,Verdana,sans-serif;font-size:8pt;border:18px solid #2a2a2a;box-shadow:#121212 2px 2px 14px 2px;-webkit-border-radius:12px;-moz-border-radius:12px;border-radius:12px;line-height:1.231;text-align:left;text-rendering:geometricPrecision}#CybotCookiebotDialog br,#CybotCookiebotDialog div,#CybotCookiebotDialog td{line-height:1.231}#CybotCookiebotDialog a,#CybotCookiebotDialog div,#CybotCookiebotDialogBodyContentTitle{font-family:'Segoe UI',Arial,Helvetica,Verdana,sans-serif}#CybotCookiebotDialog[dir=rtl],div[dir=rtl] #CybotCookiebotDialogBodyContentText,div[dir=rtl] #CybotCookiebotDialogBodyContentTitle{text-align:right}#CybotCookiebotDialog a{line-height:1.231}#CybotCookiebotDialogDetailBodyContentCookieContainerAdvertising,#CybotCookiebotDialogDetailBodyContentCookieContainerNecessary,#CybotCookiebotDialogDetailBodyContentCookieContainerPreference,#CybotCookiebotDialogDetailBodyContentCookieContainerStatistics,#CybotCookiebotDialogDetailBodyContentCookieContainerUnclassified{margin:0}#CybotCookiebotDialogPoweredbyLink{position:absolute;z-index:2147483646;width:48px;height:36px;margin-left:16px;margin-right:0;margin-top:20px}div[dir=rtl] #CybotCookiebotDialogPoweredbyLink{margin-left:0;margin-right:16px}#CybotCookiebotDialogPoweredbyImage{border:none;padding:0;margin:1px 0 0 1px;width:46px}#CybotCookiebotDialogBody{width:100%;z-index:2147483647;vertical-align:top;overflow:hidden}#CybotCookiebotDialogBodyContent{min-height:32px;font-size:12pt;font-weight:400;line-height:130%;margin:0;padding:16px 12px 4px 80px}div[dir=rtl] #CybotCookiebotDialogBodyContent{padding-left:12px;padding-right:80px}#CybotCookiebotDialogBodyContentTitle{font-size:18pt;font-weight:700;line-height:110%;padding:0;color:#2a2a2a;display:block;position:static;text-align:left}#CybotCookiebotDialogBodyContentText{margin-top:14px;margin-bottom:12px;text-align:left}#CybotCookiebotDialogBodyButtons{display:block;margin:0 12px 18px 6px;z-index:10;padding-left:62px;padding-right:0}div[dir=rtl] #CybotCookiebotDialogBodyButtons{margin:0 6px 18px 12px;padding-left:0;padding-right:62px}#CybotCookiebotDialogBodyButtons a,#CybotCookiebotDialogBodyButtons a:hover{text-decoration:none;color:#ffffff}.CybotCookiebotDialogBodyButton{display:inline-block;padding:1px 4px 3px;z-index:10;font-size:9.5pt;font-weight:400;margin-left:12px;margin-right:0;margin-top:8px;text-align:center;white-space:nowrap;min-width:80px}div[dir=rtl] .CybotCookiebotDialogBodyButton{margin-left:0;margin-right:12px}#CybotCookiebotDialogBodyButtonAccept{background-color:#18a300;border:1px solid #18a300}#CybotCookiebotDialogBodyButtonDecline{background-color:#333333;border:1px solid #333333}#CybotCookiebotDialogBodyButtons .CybotCookiebotDialogBodyLink,#CybotCookiebotDialogBodyLevelDetailsButton{display:inline-block;color:#2a2a2a;text-decoration:none;font-size:9pt;margin-top:8px;text-align:right;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAGCAMAAAAmGUT3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI3NDEyNDEwNzk0MjExRTQ5RUE5RkRFMUQ3MEU1NTZDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI3NDEyNDExNzk0MjExRTQ5RUE5RkRFMUQ3MEU1NTZDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Qjc0MTI0MEU3OTQyMTFFNDlFQTlGREUxRDcwRTU1NkMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Qjc0MTI0MEY3OTQyMTFFNDlFQTlGREUxRDcwRTU1NkMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz70ohqeAAAABlBMVEVgYGAAAAAPhzbbAAAAAnRSTlP/AOW3MEoAAAAjSURBVHjaYmBkYAQBBkYGIAAxQBQcQ/ggCiLFCGFBCIAAAwADkwAg7Yr51AAAAABJRU5ErkJggg==);background-repeat:no-repeat;background-position-x:right;background-position-y:12px;background-position:right 12px;border-left:0;border-right:5px solid transparent;padding:7px 19px 0 0}div[dir=rtl] #CybotCookiebotDialogBodyButtons .CybotCookiebotDialogBodyLink,div[dir=rtl] #CybotCookiebotDialogBodyLevelDetailsButton{padding-left:19px;padding-right:0;background-position-x:left;background-position-y:12px;background-position:left 12px;border-left:5px solid transparent;border-right:0}div[dir=rtl] #CybotCookiebotDialogBodyLevelDetailsButton,div[dir=rtl] .CybotCookiebotDialogBodyLevelDetailsButtonExpanded{background-position-x:left!important;background-position-y:5px!important;background-position:left 5px!important}#CybotCookiebotDialogBodyButtons .CybotCookiebotDialogBodyLink:hover,#CybotCookiebotDialogBodyLevelDetailsButton:hover{color:#2a2a2a;text-decoration:underline}.CybotCookiebotDialogBodyLevelDetailsButtonExpanded,.CybotCookiebotDialogBodyLinkExpanded{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAGCAYAAAARx7TFAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjU0QzAwODExNzk0MjExRTQ4QzBERTBGMTkxMUY2M0M0IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjU0QzAwODEyNzk0MjExRTQ4QzBERTBGMTkxMUY2M0M0Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NTRDMDA4MEY3OTQyMTFFNDhDMERFMEYxOTExRjYzQzQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NTRDMDA4MTA3OTQyMTFFNDhDMERFMEYxOTExRjYzQzQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz576KdnAAAATklEQVR42kyO2xEAMQgCJZ3afxFcyGRz+uMD3EHdXbYrJSltrz4Dt4UBNfsWPG614oRwO2Q/Eg+IwvnDj8kjk+48MzmZeNYI/4jRPwEGAFy/MS7NcXxJAAAAAElFTkSuQmCC)!important}a#CybotCookiebotDialogBodyLevelDetailsButton{text-decoration:none;padding-top:0;padding-left:4px;margin:1px 0 0;background-position-x:right;background-position-y:5px;background-position:right 5px;text-align:left;color:#2a2a2a;line-height:16px}div[dir=rtl] a#CybotCookiebotDialogBodyLevelDetailsButton{text-align:right}a#CybotCookiebotDialogBodyLevelDetailsButton:hover{text-decoration:none;color:#2a2a2a}#CybotCookiebotDialogBodyButtonDetails{display:inline-block;float:right;outline:0}div[dir=rtl] #CybotCookiebotDialogBodyButtonDetails{float:left}#CybotCookiebotDialogBodyLevelWrapper{display:none;text-align:right;padding-left:4px;padding-right:12px;pointer-events:none}div[dir=rtl] #CybotCookiebotDialogBodyLevelWrapper{text-align:left;padding-left:12px;padding-right:4px}#CybotCookiebotDialogBodyLevelButtons{font-size:9pt;float:left;margin-left:8px;margin-right:0;pointer-events:auto}div[dir=rtl] #CybotCookiebotDialogBodyLevelButtons{float:right;margin-left:0;margin-right:8px}#CybotCookiebotDialogBodyLevelButtonsTable{display:inline-table;margin:5px 0 4px}div[dir=rtl] #CybotCookiebotDialogBodyLevelButtonsTable{margin-left:0;margin-right:0}#CybotCookiebotDialogBodyLevelButtonsRow{display:table-row}#CybotCookiebotDialogBodyLevelButtonsSelectPane{display:table-cell;border:1px solid #cccccc;padding:4px 3px 3px 9px;-webkit-border-radius:4px 0 0 4px;-moz-border-radius:4px 0 0 4px;border-radius:4px 0 0 4px;text-align:left;vertical-align:top}#CybotCookiebotDialogBodyLevelButtonAcceptWrapper{display:inline-block;float:right;margin-left:0;margin-right:0;pointer-events:auto}div[dir=rtl] #CybotCookiebotDialogBodyLevelButtonAcceptWrapper{display:inline-block;float:left;margin-left:0;margin-right:0}a#CybotCookiebotDialogBodyLevelButtonAccept{display:inline-block;background-color:#18a300;border:1px solid #18a300;padding:3px 2px;min-width:80px;color:#ffffff;text-decoration:none;margin-left:0;margin-top:6px;z-index:10;font-size:9.5pt;font-weight:400;margin-right:0;text-align:center;white-space:nowrap}a#CybotCookiebotDialogBodyLevelButtonAccept:hover{color:#ffffff;text-decoration:none}div[dir=rtl] #CybotCookiebotDialogBodyLevelButtonsSelectPane{text-align:right;-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.CybotCookiebotDialogBodyLevelButtonWrapper{display:inline-block;position:relative;margin-right:14px;margin-left:0;line-height:16px}div[dir=rtl] .CybotCookiebotDialogBodyLevelButtonWrapper{margin-right:0;margin-left:14px}.CybotCookiebotDialogBodyLevelButtonWrapper:last-of-type{margin-right:7px;margin-left:0}div[dir=rtl] .CybotCookiebotDialogBodyLevelButtonWrapper:last-of-type{margin-right:0;margin-left:7px}.CybotCookiebotDialogBodyLevelButtonWrapper label{white-space:nowrap}input[type=checkbox].CybotCookiebotDialogBodyLevelButton{opacity:0;position:absolute;top:0;left:0;z-index:2;cursor:pointer}input[type=checkbox].CybotCookiebotDialogBodyLevelButton.CybotCookiebotDialogBodyLevelButtonDisabled{cursor:default}input[type=checkbox].CybotCookiebotDialogBodyLevelButton+label{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjYxNkU3NEJGQkJDMjExRTNCMzA3ODU5MUUzMDlDM0FDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjYxNkU3NEMwQkJDMjExRTNCMzA3ODU5MUUzMDlDM0FDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NjE2RTc0QkRCQkMyMTFFM0IzMDc4NTkxRTMwOUMzQUMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NjE2RTc0QkVCQkMyMTFFM0IzMDc4NTkxRTMwOUMzQUMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz71Yc/eAAAAOklEQVR42mL8//8/AzmAkf4aL1y4QLROfX19RjgHpBFkKyGMro6JgUwwqnFQaWQBERcvXiQ53QEEGADSSDs5lXMYKAAAAABJRU5ErkJggg==);background-repeat:no-repeat;height:auto;min-height:14px;width:auto;display:inline-block;padding:1px 0 0 17px;position:relative;top:0;left:0;z-index:1;cursor:pointer;margin-top:0;background-position:left 1px;vertical-align:top;line-height:16px}div[dir=rtl] input[type=checkbox].CybotCookiebotDialogBodyLevelButton+label{background-position:right 1px;padding:2px 17px 0 0}input[type=checkbox].CybotCookiebotDialogBodyLevelButton:checked+label{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjNGMUE0MkE1QkJDMjExRTM5QUIxQzQwRjkwREYzMUIyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjNGMUE0MkE2QkJDMjExRTM5QUIxQzQwRjkwREYzMUIyIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6M0YxQTQyQTNCQkMyMTFFMzlBQjFDNDBGOTBERjMxQjIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6M0YxQTQyQTRCQkMyMTFFMzlBQjFDNDBGOTBERjMxQjIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4IZcVrAAAA5UlEQVR42mL8//8/AyMjIwMxQHwRgwOQAuEJjMRqBGoSAFL3gRhEf2BiIB7Mh2oCgQ+MFy5c+E9Ix/o3SxlmPOtGFnJkAZEGBgaMeJyoAKTOI9k24WUcwwEmNEUGBJx4AaipEMRgQtIEUnAeSCcgiRVAQxEGEmEMsB/dLxk4Atn70RRcgDoRDGLEMxh63WfAvQSz8QIUIztvPRL/QixQIzIAawS6+wMopNA0K8CCHogD0T0O9yMOzSDQCJR7gFMjmuYFUKEDQLEJ2KKJBV0AqjkRGKILsdiOqhFv6kHSCkrXMAAQYACIkU0SIPgtxAAAAABJRU5ErkJggg==);background-repeat:no-repeat;height:auto;width:auto;min-height:14px;display:inline-block;padding:1px 0 0 17px;background-position:left 1px;vertical-align:top;line-height:16px}div[dir=rtl] input[type=checkbox].CybotCookiebotDialogBodyLevelButton:checked+label{background-position:right 1px;padding:2px 17px 0 0}input[type=checkbox].CybotCookiebotDialogBodyLevelButton.CybotCookiebotDialogBodyLevelButtonDisabled+label{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjgzMjc3NEM2QkJDMjExRTNBN0ExOUJFMzFCMzdBRjdEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjgzMjc3NEM3QkJDMjExRTNBN0ExOUJFMzFCMzdBRjdEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ODMyNzc0QzRCQkMyMTFFM0E3QTE5QkUzMUIzN0FGN0QiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6ODMyNzc0QzVCQkMyMTFFM0E3QTE5QkUzMUIzN0FGN0QiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz54CbH2AAABFElEQVR42oxSLQ/CQAy9I6hZMrVgQW4aCdnvGIoER8Lm+AcbWBIcFo0cFuw0dkxMMAlyvC69y/hek6VN19e+16ssy1JIKUUTC8Owh9o+wkNbNDSADICmCA18w1ZTIEAeg8juMkmS8h+oKAqR57nIsmzD0pYVVcdx5A+KHRQuLMuaEYj0+b5/br0UdT9QHANgcJwGQbCjWAOjKPKoM/yglhvB9dTW0WCrG5LGOI77SM5rz0IFKXILniRs2564rqsL2tzpQjQQKqoectdao9Q0zScJFVXwvsGteIr612F/Q279ql1rxKYqME8WNV17NL5+BSowCmnykUFngA6fnunt5Jj2Fhs9wV++XlKTy1GGzeptPQQYAF1/e0nsKZ1HAAAAAElFTkSuQmCC);background-repeat:no-repeat;height:auto;width:auto;min-height:14px;display:inline-block;padding:1px 0 0 17px;position:relative;top:0;left:0;z-index:1;cursor:default;background-position:left 1px;vertical-align:top;line-height:16px}div[dir=rtl] input[type=checkbox].CybotCookiebotDialogBodyLevelButton.CybotCookiebotDialogBodyLevelButtonDisabled+label{background-position:right 1px;padding:2px 17px 0 0}input[type=checkbox].CybotCookiebotDialogBodyLevelButton:focus+label{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkUwQkVDMzlCQkQ4NTExRTM5RTEwRUIwNUNENTg2N0Q4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkUwQkVDMzlDQkQ4NTExRTM5RTEwRUIwNUNENTg2N0Q4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RTBCRUMzOTlCRDg1MTFFMzlFMTBFQjA1Q0Q1ODY3RDgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RTBCRUMzOUFCRDg1MTFFMzlFMTBFQjA1Q0Q1ODY3RDgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7Y0XwIAAAAOElEQVR42mL8//8/AzmAiYFMQLZGxtLSUqLd2tXVxQhjs8AMIELf/4H146hGWmhkwRa5xACAAAMAL2gJGKxaSssAAAAASUVORK5CYII=);background-repeat:no-repeat;height:auto;width:auto;min-height:14px;display:inline-block;padding:1px 0 0 17px;background-position:left 1px;vertical-align:top;line-height:16px}div[dir=rtl] input[type=checkbox].CybotCookiebotDialogBodyLevelButton:focus+label{background-position:right 1px;padding:2px 17px 0 0}input[type=checkbox].CybotCookiebotDialogBodyLevelButton:checked:focus+label{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk3MjVBRTM5QkQ4MDExRTM4RDBEOTEzMTQxN0RDRjc0IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk3MjVBRTNBQkQ4MDExRTM4RDBEOTEzMTQxN0RDRjc0Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTcyNUFFMzdCRDgwMTFFMzhEMEQ5MTMxNDE3RENGNzQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTcyNUFFMzhCRDgwMTFFMzhEMEQ5MTMxNDE3RENGNzQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7V1txIAAAA6UlEQVR42mL8//8/AyMjIwMxQHwRgwOQAuEJjMRqBGoSAFL3gRhEf2BiIB7Mh2oCgQ+MpaWl/wnpuC5yluG05D5kIUcWENnd3c2Ix4kKQOo8km0TXsYxHGBCU2RAwIkXgJoKQQwmJE0gBeeBdAKSWAE0FGEgEcZgQQpmmIb5QD7YdCDuhynUf2XFsKvk2AUYH2bjBShGdt56JP4F/ZfWKO4HawS6+wMopNA0K8CCHogD0T0O9yMOzSDQCJR7gFMjmuYFUKEDQLEJ2KKJBV0AqjkRGEALsdiOqhFr6rkMpUsRQqB0DQMAAQYAX31KK0vr8I0AAAAASUVORK5CYII=);background-repeat:no-repeat;height:auto;width:auto;min-height:14px;display:inline-block;padding:1px 0 0 17px;background-position:left 1px;vertical-align:top;line-height:16px}div[dir=rtl] input[type=checkbox].CybotCookiebotDialogBodyLevelButton:checked:focus+label{background-position:right 1px;padding:2px 17px 0 0}#CybotCookiebotDialogBodyLevelDetailsWrapper{display:table-cell;background-color:#f6f6f9;border:1px solid #cccccc;border-left:none;height:14px;padding:4px 0 4px 4px;text-align:left;vertical-align:top;cursor:pointer}div[dir=rtl] #CybotCookiebotDialogBodyLevelDetailsWrapper{border:1px solid #cccccc;border-right:none;text-align:right;padding:4px 4px 4px 0}#CybotCookiebotDialogDetail{display:none;background-color:#ffffff;padding-top:0;padding-bottom:1px;overflow:auto}#CybotCookiebotDialogDetailBody{max-width:632px;padding:0 12px;vertical-align:top}div[dir=rtl] #CybotCookiebotDialogDetailBody{text-align:right}#CybotCookiebotDialogDetailBodyContent{background-color:#ffffff;color:#2a2a2a;border:1px solid #cccccc;border-bottom:4px solid #cccccc;height:170px}#CybotCookiebotDialogDetailBodyContent a{font-size:9pt}#CybotCookiebotDialogDetailBodyContentTabs a{font-size:10.5pt}#CybotCookiebotDialogDetailBodyContentText{padding:8px;font-size:10pt}#CybotCookiebotDialogDetailBodyContentTabs{position:relative;height:auto;display:inline-block;white-space:nowrap}.CybotCookiebotDialogDetailBodyContentTabsItem,.CybotCookiebotDialogDetailBodyContentTabsItemSelected{font-size:10.5pt;border-right:1px solid #cccccc;position:relative;top:1px;z-index:10;white-space:normal;line-height:100%;display:inline-block;border-top:1px solid #cccccc;font-weight:400}a.CybotCookiebotDialogDetailBodyContentTabsItem{margin:0;text-decoration:none!important}a.CybotCookiebotDialogDetailBodyContentTabsItem:hover{text-decoration:none!important;background-color:#ffffff!important;color:#2a2a2a!important;opacity:.9}a.CybotCookiebotDialogDetailBodyContentTabsItemSelected{margin:0;text-decoration:none!important;color:#2a2a2a!important;opacity:1}a.CybotCookiebotDialogDetailBodyContentTabsItemSelected:hover{text-decoration:none!important;color:#2a2a2a!important;cursor:default}.CybotCookiebotDialogDetailBodyContentTabsItem{background:#f6f6f9;color:#2a2a2a!important;opacity:.85;margin-top:1px;cursor:pointer;padding:7px 18px 5px}.CybotCookiebotDialogDetailBodyContentTabsItem:first-of-type{border-left:1px solid #cccccc}.CybotCookiebotDialogDetailBodyContentTabsItemSelected{background:#ffffff;color:#2a2a2a;padding:8px 18px 6px}.CybotCookiebotDialogDetailBodyContentTab:first-child{border-left:1px solid #cccccc;-webkit-border-radius:4px 0 0 0;-moz-border-radius:4px 0 0;border-radius:4px 0 0}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentTab:first-child{border-left:none;-webkit-border-radius:0 4px 0 0;-moz-border-radius:0 4px 0 0;border-radius:0 4px 0 0}.CybotCookiebotDialogDetailBodyContentTab{border-left:none}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentTab{border-left:1px solid #cccccc}#CybotCookiebotDialogDetailFooter{padding-top:4px;padding-right:2px;color:#2a2a2a;text-align:right;opacity:.85;background-color:#ffffff}div[dir=rtl] #CybotCookiebotDialogDetailFooter{text-align:left}#CybotCookiebotDialogDetailFooter a{color:#2a2a2a}#CybotCookiebotDialogDetailBodyContentTextAbout{padding:18px 12px 12px;font-size:9pt;height:140px;overflow:auto;display:none}#CybotCookiebotDialogDetailBodyContentTextOverview{display:inline-block}#CybotCookiebotDialogDetailBodyContentCookieContainerTypes{float:left;white-space:nowrap;padding:0;background-color:#f6f6f9;font-size:9pt}div[dir=rtl] #CybotCookiebotDialogDetailBodyContentCookieContainerTypes{float:right}#CybotCookiebotDialogDetailBodyContentCookieContainerTypeDetails{padding:12px;font-size:9pt;overflow:auto;height:146px;max-height:146px}.CybotCookiebotDialogDetailBodyContentCookieContainerTypesSelected{padding:8px;background-color:#ffffff;border-bottom:1px solid #cccccc;border-right:1px solid #ffffff;border-left:none;display:block;text-decoration:none!important;color:#2a2a2a!important}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentCookieContainerTypesSelected{border-left:1px solid #ffffff;border-right:none}.CybotCookiebotDialogDetailBodyContentCookieContainerTypes{padding:8px;cursor:pointer;background-color:#f6f6f9;border-bottom:1px solid #cccccc;border-right:1px solid #cccccc;border-left:none;display:block;text-decoration:none!important;color:#2a2a2a!important;opacity:1}.CybotCookiebotDialogDetailBodyContentCookieContainerTypes:first-child{border-top:1px solid #cccccc}.CybotCookiebotDialogDetailBodyContentCookieContainerTypesSelected:first-child{border-top:1px solid #ffffff}.CybotCookiebotDialogDetailBodyContentCookieContainerTypes label,.CybotCookiebotDialogDetailBodyContentCookieContainerTypesSelected label{cursor:pointer;display:none}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentCookieContainerTypes{border-left:1px solid #cccccc;border-right:none}a.CybotCookiebotDialogDetailBodyContentCookieContainerTypes:hover{text-decoration:none!important;background:#ffffff!important;color:#2a2a2a!important;opacity:1}a.CybotCookiebotDialogDetailBodyContentCookieContainerTypesSelected:hover{text-decoration:none!important;color:#2a2a2a!important;cursor:default}#CybotCookiebotDialogDetailBodyContentCookieTabsAdvertising,#CybotCookiebotDialogDetailBodyContentCookieTabsPreference,#CybotCookiebotDialogDetailBodyContentCookieTabsStatistics,#CybotCookiebotDialogDetailBodyContentCookieTabsUnclassified{display:none}.CybotCookiebotDialogDetailBodyContentCookieTypeTable{padding:0;margin:8px 0 0;font-size:9pt;border-spacing:0;border-collapse:collapse;width:100%}.CybotCookiebotDialogDetailBodyContentCookieTypeTable thead td,.CybotCookiebotDialogDetailBodyContentCookieTypeTable thead th{background-color:#f6f6f9!important;color:#2a2a2a!important;text-align:left;vertical-align:top;padding:2px;border-bottom:1px solid #cccccc;font-weight:400}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentCookieTypeTable thead td,div[dir=rtl] .CybotCookiebotDialogDetailBodyContentCookieTypeTable thead th{text-align:right}.CybotCookiebotDialogDetailBodyContentCookieTypeTable tbody td{border-bottom:1px solid #cccccc;border-right:1px solid #cccccc;text-align:left;vertical-align:top;padding:4px;max-width:72px;overflow:hidden;font-size:9pt;color:#2a2a2a!important}.CybotCookiebotDialogDetailBodyContentCookieTypeTable tbody td:last-child{border-right:0}div[dir=rtl] .CybotCookiebotDialogDetailBodyContentCookieTypeTable tbody td{text-align:right}.CybotCookiebotDialogDetailBodyContentCookieTypeTable tbody td.CybotCookiebotDialogDetailBodyContentCookieTypeTableEmpty{border:none;border-top:1px solid #cccccc;padding:4px 0 0}</style></head>";
		
		$keywords = array(
			'onesignal' => 'statistics',
			'facebook'  => 'marketing'
		);

		$changed_header = cookiebot_addons_manipulate_script( $buffer, $keywords );

		$expected_replacements = array();

		ob_start(); // first match
        ?><script type="text/plain" data-cookieconsent="marketing">{'@context':'https:\/\/schema.org','@type':'Person','url':'https:\/\/www.ursula-sandner.com\/','sameAs':['https:\/\/www.facebook.com\/SandnerMindConsulting','https:\/\/instagram.com\/ursula.sandner','https:\/\/twitter.com\/psihotimisoara'],'@id':'#person','name':'Ursula Yvonne Sandner'}</script><?php
        $expected_replacements[] = ob_get_clean();

        ob_start(); // second match
        ?><script type='text/plain' data-cfasync='false' data-cookieconsent='statistics'><?php
            $expected_replacements[] = ob_get_clean();

		ob_start(); // third match
        ?><script type="text/plain" data-cookieconsent="statistics" src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script><?php
		$expected_replacements[] = ob_get_clean();

        ob_start(); // fourth match
		?><script type="text/plain" data-cookieconsent="statistics"><?php
        $expected_replacements[] = ob_get_clean();

        foreach($expected_replacements as $expected_replacement) {
            $this->assertNotFalse( strpos( $changed_header, $expected_replacement ) );
        }
	}
}