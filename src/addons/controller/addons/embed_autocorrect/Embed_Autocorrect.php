<?php

namespace cybot\cookiebot\addons\controller\addons\embed_autocorrect;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Other_Addon;
use cybot\cookiebot\lib\Cookiebot_WP;
use Exception;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;
use function cybot\cookiebot\lib\cookiebot_addons_get_domain_from_url;
use function cybot\cookiebot\lib\cookiebot_addons_get_home_url_domain;
use function cybot\cookiebot\lib\cookiebot_addons_output_cookie_types;
use function cybot\cookiebot\lib\get_view_html;

class Embed_Autocorrect extends Base_Cookiebot_Other_Addon {

	const ADDON_NAME                  = 'Embed autocorrect';
	const OPTION_NAME                 = 'embed_autocorrect';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_embed_autocorrect' ) );
	}

	/**
	 * Check for embed autocorrect action hooks
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_embed_autocorrect() {

		// add filters to handle autocorrection in content
		add_filter(
			'the_content',
			array(
				$this,
				'cookiebot_addon_embed_autocorrect_content',
			),
			1000
		); // Ensure it is executed as the last filter

		// add filters to handle autocorrection in widget text
		add_filter(
			'widget_text',
			array(
				$this,
				'cookiebot_addon_embed_autocorrect_content',
			),
			1000
		); // Ensure it is executed as the last filter

		// add fitler to handle video shortcodes
		add_filter(
			'wp_video_shortcode',
			array(
				$this,
				'cookiebot_addon_embed_autocorrect_handle_video',
			),
			1000
		);

		// add fitler to handle audio shortcodes
		add_filter(
			'wp_audio_shortcode',
			array(
				$this,
				'cookiebot_addon_embed_autocorrect_handle_audio',
			),
			1000
		);

		add_action(
			'wp_head',
			array(
				$this,
				'cookiebot_addon_embed_autocorrect_javascript',
			)
		);
	}

	/**
	 * Add javascript to handle videos as loaded
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect_javascript() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
		if ( $library === 'mediaelement' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_mediaelement_style' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_mediaelement_script' ) );
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function enqueue_mediaelement_style() {
		wp_enqueue_style(
			'embed_autocorrect_mediaelement_style',
			asset_url( 'css/frontend/addons/embed-autocorrect/mediaelement.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function enqueue_mediaelement_script() {
		wp_register_script(
			'embed_autocorrect_mediaelement_script',
			asset_url( 'js/frontend/addons/embed-autocorrect/mediaelement.js' ),
			array( 'jQuery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			false
		);
		wp_localize_script(
			'embed_autocorrect_mediaelement_script',
			'cookieTypes',
			$this->get_cookie_types()
		);
	}

	/**
	 * Autocorrection of Vimeo and Youtube tags to make them GDPR compatible
	 *
	 * @since 1.1.0
	 * @todo refactor this function, reduce duplicate code,
	 * @todo fix the behaviour in places where variables are "probably undefined"
	 */
	public function cookiebot_addon_embed_autocorrect_content( $content ) {
		// Make sure Cookiebot is active and the user has enabled autocorrection

		preg_match_all( '|<div[^>]*id=\"fb-root\">.*?</blockquote>|si', $content, $matches );
		foreach ( $matches[0] as $match ) {
			// Find src.
			preg_match( '|<a href=\"([^\"]*)\">([^<]*)</a></p></blockquote>|', $match, $match_src );
			$src = $match_src[1];

			$script_replace = '<script type="text/plain" data-cookieconsent="' .
				cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '">';

			// Replace - and add cookie consent notice.
			$adjusted = str_replace(
				'<script>',
				$script_replace,
				$match
			);

			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src(
				apply_filters(
					'cybot_cookiebot_addons_embed_source',
					$src
				)
			);

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters(
				'cybot_cookiebot_addons_embed_placeholder',
				$placeholder,
				$src,
				$this->get_cookie_types()
			);

			$adjusted .= $placeholder;
			$content   = str_replace( $match, $adjusted, $content );
		}
		unset( $matches );

		preg_match_all( '|<blockquote[^>]*class=\"twitter-tweet\"[^>]*>.*?</script>|si', $content, $matches );
		foreach ( $matches[0] as $match ) {
			// Find src.
			preg_match( '|<a href=\"([^\"]*)\">([^<]*)</a></blockquote>|', $match, $match_src );

			if ( empty( $match_src ) ) {
				continue;
			}

			$src = $match_src[1];

			$script_replace = '<script type="text/plain" data-cookieconsent="' .
				cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" ';

			// Replace - and add cookie consent notice.
			$adjusted = str_replace(
				'<script ',
				$script_replace,
				$match
			);

			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src(
				apply_filters(
					'cybot_cookiebot_addons_embed_source',
					$src
				)
			);

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters(
				'cybot_cookiebot_addons_embed_placeholder',
				$placeholder,
				$src,
				$this->get_cookie_types()
			);

			$adjusted .= $placeholder;
			$content   = str_replace( $match, $adjusted, $content );
		}
		unset( $matches );

		// Match all speakerdeck, slideshare, screencast, reverbnation, mixcloud, kickstarter,
		// dailymoition, collegehumor, cloudup, animoto, videopress, youtube, vimeo and facebook iframes.
		preg_match_all(
			$this->get_regex(),
			$content,
			$matches
		);

		foreach ( $matches[0] as $x => $match ) {
			/** Get the source attribute value */
			$start = strpos( $match, ' src=' ) + 6;
			$end   = strpos( $match, $matches[1][ $x ], $start );
			$src   = substr( $match, $start, $end - $start );

			/** Skip the matched iframe if the data-cookieconsent attribute exists */
			if ( strpos( $match, 'data-cookieconsent' ) !== false ) {
				continue;
			}

			/**  Replace - and add cookie consent notice. */
			$adjusted = str_replace(
				' src=',
				' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
				$match
			);

			/** Generate placeholder */
			$placeholder = $this->generate_placeholder_with_src(
				apply_filters(
					'cybot_cookiebot_addons_embed_source',
					$src
				)
			);

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters(
				'cybot_cookiebot_addons_embed_placeholder',
				$placeholder,
				$src,
				$this->get_cookie_types()
			);

			$adjusted .= $placeholder;
			$content   = str_replace( $match, $adjusted, $content );
		}

		unset( $matches );
		preg_match_all(
			'/<script.*(instagram|twitter|issuu|imgur|redditmedia\.com|tiktok\.com|polldaddy|tumblr)+.*<\/script>/mi',
			$content,
			$matches
		);
		foreach ( $matches[0] as $match ) {
			preg_match( '/src\s*=\s*"(.+?)"/', $match, $src );

			// $matches[1] will have the text that matched the first captured parenthesized
			if ( isset( $src[1] ) ) {
				$src = $src[1];
			} else {
				$src = '';
			}

			// Replace - and add cookie consent notice.
			$adjusted = str_replace(
				' src=',
				' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
				$match
			);
			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src(
				apply_filters(
					'cybot_cookiebot_addons_embed_source',
					$src
				)
			);
			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters(
				'cybot_cookiebot_addons_embed_placeholder',
				$placeholder,
				$src,
				$this->get_cookie_types()
			);
			$adjusted   .= $placeholder;
			$content     = str_replace( $match, $adjusted, $content );
		}
		unset( $matches );

		return $content;
	}

	/**
	 * Implementation of filter wp_video_shortcode - fixing code for cookiebot.
	 *
	 * @throws Exception
	 */
	public function cookiebot_addon_embed_autocorrect_handle_video(
		$output
	) {
		/* Find src in markup */
		preg_match( '| src=\"([^\"]*)\"|', $output, $match );
		$src = $match[1];

		// allow same domain embeds without cookieconsent
		$src_domain = cookiebot_addons_get_domain_from_url( $src );
		if ( cookiebot_addons_get_home_url_domain() === $src_domain ) {
			return $output;
		}

		/**
		 * Generate placeholder
		 */
		$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cybot_cookiebot_addons_embed_source', $src ) );
		$placeholder = apply_filters(
			'cybot_cookiebot_addons_embed_placeholder',
			$placeholder,
			$src,
			$this->get_cookie_types()
		);

		$output  = str_replace( 'wp-video-shortcode', 'wp-video-shortcode__disabled', $output );
		$output  = str_replace(
			' src=',
			' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
			$output
		);
		$output .= $placeholder;

		return $output;
	}

	/**
	 * Implementation of filter wp_audio_shortcode - fixing code for cookiebot.
	 */
	public function cookiebot_addon_embed_autocorrect_handle_audio(
		$output
	) {
		/* Find src in markup */
		preg_match( '| src=\"([^\"]*)\"|', $output, $match );
		$src = $match[1];

		/**
		 * Generate placeholder
		 */
		$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cybot_cookiebot_addons_embed_source', $src ) );
		$placeholder = apply_filters(
			'cybot_cookiebot_addons_embed_placeholder',
			$placeholder,
			$src,
			$this->get_cookie_types()
		);

		$output  = str_replace( 'wp-audio-shortcode', 'wp-audio-shortcode__disabled', $output );
		$output  = str_replace(
			' src=',
			' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
			$output
		);
		$output .= $placeholder;

		return $output;
	}

	/**
	 * Generates placeholder for given source
	 *
	 * @param $src
	 *
	 * @return string
	 */
	private function generate_placeholder_with_src( $src = '' ) {
		$cookie_content_notice  = '<div class="' . cookiebot_addons_cookieconsent_optout( $this->get_cookie_types() ) . '">';
		$cookie_content_notice .= $this->get_placeholder( $src );
		$cookie_content_notice .= '</div>';

		return $cookie_content_notice;
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__(
				'Blocks embedded videos from Youtube, Twitter, Vimeo and Facebook.',
				'cookiebot'
			),
		);
	}

	/**
	 * Returns regex from the database
	 * If it does not exist then it will return the default regex
	 *
	 * @return string
	 *
	 * @since 2.4.6
	 */
	private function get_regex() {
		return apply_filters(
			'cybot_cookiebot_embed_regex',
			$this->settings->get_addon_regex( self::OPTION_NAME, $this->get_default_regex() )
		);
	}

	/**
	 * Returns the default regex
	 *
	 * @return string
	 *
	 * @since 2.4.6
	 */
	private function get_default_regex() {
		return apply_filters(
			'cybot_cookiebot_embed_default_regex',
			'/<iframe[^>]* src=("|\').*(facebook\.com|youtu\.be|youtube\.com|youtube-nocookie\.com|player\.vimeo\.com|soundcloud\.com|spotify\.com|speakerdeck\.com|slideshare\.net|screencast\.com|reverbnation\.com|mixcloud\.com|cloudup\.com|animoto\.com|video\.WordPress\.com|embed\.ted\.com|embedly\.com|kickstarter\.com).*[^>].*>.*?<\/iframe>/mi'
		);
	}

	/**
	 * Returns true if the default and the normal regex functions match
	 *
	 * @return bool
	 *
	 * @since 2.4.6
	 */
	private function is_regex_default() {
		return $this->get_regex() === $this->get_default_regex();
	}

	/**
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function get_extra_addon_options_html() {
		$view_args = array(
			'addon_option_name' => self::OPTION_NAME,
			'regex'             => $this->get_regex(),
			'regex_is_default'  => $this->is_regex_default(),
			'default_regex'     => $this->get_default_regex(),
		);

		return get_view_html(
			'admin/settings/prior-consent/other-addons/embed-autocorrect-extra-addon-options.php',
			$view_args
		);
	}

	/**
	 * Sets default settings for this addon
	 *
	 * @return array
	 *
	 * @since 3.6.3
	 */
	public function get_default_enable_setting() {
		return array(
			'enabled'     => 1,
			'cookie_type' => static::DEFAULT_COOKIE_TYPES,
			'placeholder' => static::DEFAULT_PLACEHOLDER_CONTENT,
			'regex'       => $this->get_default_regex(),
		);
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return '0.0.1';
	}
}
