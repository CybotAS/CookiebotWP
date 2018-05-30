<?php

namespace cookiebot_addons_framework\controller\addons\embed_autocorrect;

class Embed_Autocorrect {

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_embed_autocorrect' ) );
	}

	/**
	 * Check for embed autocorrect action hooks
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect() {
		//add filters to handle autocorrection in content
		add_filter( 'the_content', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content'
		), 1000 ); //Ensure it is executed as the last filter

		//add filters to handle autocorrection in widget text
		add_filter( 'widget_text', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content'
		), 1000 ); //Ensure it is executed as the last filter
	}

	/**
	 * Autocorrection of Vimeo and Youtube tags to make them GDPR compatible
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect_content( $content ) {
		//Make sure Cookiebot is active and the user has enabled autocorrection
		$cookieContentNotice = '<div class="cookieconsent-optout-marketing">';
		$cookieContentNotice .= 'Please <a href="javascript:Cookiebot.renew()">accept marketing-cookies</a> to watch this video.';
		$cookieContentNotice .= '</div>';

		//match twitter
		preg_match_all( '#\<(script).+src=".+platform.twitter.com\/widgets\.js.+\<\/(script)\>#mis', $content, $matches );
		if ( ! empty( $matches[0][0] ) ) {
			$adjusted_content = str_replace( '<script', '<script type="text/plain" data-cookieconsent="marketing"', $matches[0][0] );
			$content = str_replace($matches[0][0], $adjusted_content, $content );
		}
		unset($matches);

		//Match all youtube and vimeo iframes.
		preg_match_all( '/<iframe[^>]*src=\"[^\"]*(youtu\.be|youtube\.com|youtube-nocookie\.com|player\.vimeo\.com)\/[^>]*>.*?<\\/iframe>/mi', $content, $matches );
		foreach( $matches[0] as $match ) {
			//Replace - and add cookie consent notice.
			$adjusted = str_replace( ' src=', ' data-cookieconsent="marketing" data-src=', $match );
			$content = str_replace( $match, $adjusted . $cookieContentNotice, $content );
		}

		return $content;
	}
}
