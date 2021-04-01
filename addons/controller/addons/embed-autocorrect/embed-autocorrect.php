<?php

namespace cookiebot_addons\controller\addons\embed_autocorrect;

use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

class Embed_Autocorrect implements Cookiebot_Addons_Interface {

	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	public $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings          Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent    Cookie_Consent_Interface
	 * @param $buffer_output     Buffer_Output_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct(
		Settings_Service_Interface $settings,
		Script_Loader_Tag_Interface $script_loader_tag,
		Cookie_Consent_Interface $cookie_consent,
		Buffer_Output_Interface $buffer_output
	) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration() {
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

		//add filters to handle autocorrection in content
		add_filter( 'the_content', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content',
		), 1000 ); //Ensure it is executed as the last filter

		//add filters to handle autocorrection in widget text
		add_filter( 'widget_text', array(
			$this,
			'cookiebot_addon_embed_autocorrect_content',
		), 1000 ); //Ensure it is executed as the last filter


		//add fitler to handle video shortcodes
		add_filter( 'wp_video_shortcode', array(
			$this,
			'cookiebot_addon_embed_autocorrect_handle_video',
		), 1000 );

		//add fitler to handle audio shortcodes
		add_filter( 'wp_audio_shortcode', array(
			$this,
			'cookiebot_addon_embed_autocorrect_handle_audio',
		), 1000 );

		add_action( 'wp_head', array(
			$this,
			'cookiebot_addon_embed_autocorrect_javascript',
		) );
	}

	/**
	 * Add javascript to handle videos as loaded
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect_javascript() {
		$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
		if ( $library === 'mediaelement' ) {
			?>
            <style type="text/css">video.wp-video-shortcode__disabled, audio.wp-audio-shortcode__disabled {
                    display: none;
                }</style>
            <script>
                window.addEventListener( 'CookiebotOnTagsExecuted', function ( e ) {
                    if (<?php echo 'Cookiebot.consent.' . implode( ' && Cookiebot.consent.',
							$this->get_cookie_types() ); ?>) {
                        jQuery( '.wp-video-shortcode__disabled' ).addClass( 'wp-video-shortcode' ).removeClass( 'wp-video-shortcode__disabled' );
                        jQuery( '.wp-audio-shortcode__disabled' ).addClass( 'wp-audio-shortcode' ).removeClass( 'wp-audio-shortcode__disabled' );
                        if ( window.wp && window.wp.mediaelement && window.wp.mediaelement.initialize ) {
                            window.wp.mediaelement.initialize();
                        }
                    }
                }, false );
            </script><?php
		}
	}


	/**
	 * Autocorrection of Vimeo and Youtube tags to make them GDPR compatible
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_embed_autocorrect_content( $content ) {
		//Make sure Cookiebot is active and the user has enabled autocorrection

		preg_match_all( '|<div[^>]*id=\"fb-root\">.*?</blockquote>|si', $content, $matches );
		foreach ( $matches[0] as $match ) {
			//Find src.
			preg_match( '|<a href=\"([^\"]*)\">([^<]*)</a></p></blockquote>|', $match, $matchSrc );
			$src = $matchSrc[1];

			//Replace - and add cookie consent notice.
			$adjusted = str_replace( '<script>',
				'<script type="text/plain" data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '">',
				$match );

			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source',
				$src ) );

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
				$this->get_cookie_types() );

			$adjusted .= $placeholder;
			$content  = str_replace( $match, $adjusted, $content );

		}
		unset( $matches );

		preg_match_all( '|<blockquote[^>]*class=\"twitter-tweet\"[^>]*>.*?</script>|si', $content, $matches );
		foreach ( $matches[0] as $match ) {
			//Find src.
			preg_match( '|<a href=\"([^\"]*)\">([^<]*)</a></blockquote>|', $match, $matchSrc );

			if ( empty( $matchSrc ) ) {
				continue;
			}

			$src = $matchSrc[1];

			//Replace - and add cookie consent notice.
			$adjusted = str_replace( '<script ',
				'<script type="text/plain" data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" ',
				$match );

			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source',
				$src ) );

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
				$this->get_cookie_types() );

			$adjusted .= $placeholder;
			$content  = str_replace( $match, $adjusted, $content );

		}
		unset( $matches );


		//Match all speakerdeck, slideshare, screencast, reverbnation, mixcloud, kickstarter,
		// dailymoition, collegehumor, cloudup, animoto, videopress, youtube, vimeo and facebook iframes.
		preg_match_all( $this->get_regex(),
			$content, $matches );

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
			$adjusted = str_replace( ' src=',
				' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
				$match );

			/** Generate placeholder */
			$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source',
				$src ) );

			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
				$this->get_cookie_types() );

			$adjusted .= $placeholder;
			$content  = str_replace( $match, $adjusted, $content );
		}

		unset( $matches );
		preg_match_all( '/<script.*(instagram|twitter|issuu|imgur|redditmedia\.com|tiktok\.com|polldaddy|tumblr)+.*<\/script>/mi',
			$content, $matches );
		foreach ( $matches[0] as $x => $match ) {
			//Replace - and add cookie consent notice.
			$adjusted = str_replace( ' src=',
				' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
				$match );
			/**
			 * Generate placeholder
			 */
			$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source',
				$src ) );
			/**
			 * Modify placeholder by Filter
			 *
			 * @param   $placeholder    string  Current placeholder text
			 * @param   $src            string  Source attribute from the embedded video
			 * @param   $this           array   Array of required cookie types
			 */
			$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
				$this->get_cookie_types() );
			$adjusted    .= $placeholder;
			$content     = str_replace( $match, $adjusted, $content );
		}
		unset( $matches );

		return $content;
	}

	/**
	 * Implementation of filter wp_video_shortcode - fixing code for cookiebot.
	 */
	public function cookiebot_addon_embed_autocorrect_handle_video(
		$output,
		$atts = array(),
		$video = '',
		$post_id = null,
		$library = ''
	) {
		/* Find src in markup */
		preg_match( '| src=\"([^\"]*)\"|', $output, $match );
		$src = $match[1];

		// allow same domain embeds without cookieconsent
		$src_domain = cookiebot_addons_get_domain_from_url( $src );
		if( cookiebot_addons_get_home_url_domain() ===  $src_domain ) {
		    return $output;
		}

		/**
		 * Generate placeholder
		 */
		$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source', $src ) );
		$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
			$this->get_cookie_types() );

		$output = str_replace( 'wp-video-shortcode', 'wp-video-shortcode__disabled', $output );
		$output = str_replace( ' src=',
			' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
			$output );
		$output .= $placeholder;

		return $output;
	}

	/**
	 * Implementation of filter wp_audio_shortcode - fixing code for cookiebot.
	 */
	public function cookiebot_addon_embed_autocorrect_handle_audio(
		$output,
		$atts = array(),
		$video = '',
		$post_id = null,
		$library = ''
	) {
		/* Find src in markup */
		preg_match( '| src=\"([^\"]*)\"|', $output, $match );
		$src = $match[1];

		/**
		 * Generate placeholder
		 */
		$placeholder = $this->generate_placeholder_with_src( apply_filters( 'cookiebot_addons_embed_source', $src ) );
		$placeholder = apply_filters( 'cookiebot_addons_embed_placeholder', $placeholder, $src,
			$this->get_cookie_types() );

		$output = str_replace( 'wp-audio-shortcode', 'wp-audio-shortcode__disabled', $output );
		$output = str_replace( ' src=',
			' data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->get_cookie_types() ) . '" data-src=',
			$output );
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
	public function generate_placeholder_with_src( $src = '' ) {
		$cookieContentNotice = '<div class="' . cookiebot_addons_cookieconsent_optout( $this->get_cookie_types() ) . '">';
		$cookieContentNotice .= $this->get_placeholder( $src );
		$cookieContentNotice .= '</div>';

		return $cookieContentNotice;
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Embed autocorrect';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'embed_autocorrect';
	}

	/**
	 * Plugin file path
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return false;
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name(), $this->get_default_cookie_types() );
	}

	/**
	 * Returns default cookie types
	 * @return array
	 *
	 * @since 1.5.0
	 */
	public function get_default_cookie_types() {
		return array( 'marketing', 'statistics' );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}

	/**
	 * Retrieves current installed version of the addon
	 *
	 * @return bool
	 *
	 * @since 2.2.1
	 */
	public function get_addon_version() {
		return false;
	}

	/**
	 * Default placeholder content
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';
	}

	/**
	 * Get placeholder content
	 *
	 * This function will check following features:
	 * - Current language
	 *
	 * @param $src
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder( $src = '' ) {
		return $this->settings->get_placeholder( $this->get_option_name(), $this->get_default_placeholder(),
			cookiebot_addons_output_cookie_types( $this->get_cookie_types() ), $src );
	}

	/**
	 * Checks if it does have custom placeholder content
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function has_placeholder() {
		return $this->settings->has_placeholder( $this->get_option_name() );
	}

	/**
	 * returns all placeholder contents
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholders() {
		return $this->settings->get_placeholders( $this->get_option_name() );
	}

	/**
	 * Return true if the placeholder is enabled
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function is_placeholder_enabled() {
		return $this->settings->is_placeholder_enabled( $this->get_option_name() );
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '<p>' . esc_html__( 'Blocks embedded videos from Youtube, Twitter, Vimeo and Facebook.',
				'cookiebot-addons' ) . '</p>';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return false;
	}

	/**
	 * Placeholder helper overlay in the settings page.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder_helper() {
		return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%src - video source</li><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in the frontend</li></ul>';
	}

	/**
	 * Returns parent class or false
	 *
	 * @return string|bool
	 *
	 * @since 2.1.3
	 */
	public function get_parent_class() {
		return get_parent_class( $this );
	}

	/**
	 * Action after enabling the addon on the settings page
	 *
	 * @since 2.2.0
	 */
	public function post_hook_after_enabling() {
		//do nothing
	}

	/**
	 * Cookiebot plugin is deactivated
	 *
	 * @since 2.2.0
	 */
	public function plugin_deactivated() {
		//do nothing
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
		return apply_filters( 'cookiebot_embed_regex',
			$this->settings->get_addon_regex( $this->get_option_name(), $this->get_default_regex() ) );
	}

	/**
	 * Returns the default regex
	 *
	 * @return string
	 *
	 * @since 2.4.6
	 */
	private function get_default_regex() {
		return apply_filters( 'cookiebot_embed_default_regex',
			'/<iframe[^>]* src=("|\').*(facebook\.com|youtu\.be|youtube\.com|youtube-nocookie\.com|player\.vimeo\.com|soundcloud\.com|spotify\.com|speakerdeck\.com|slideshare\.net|screencast\.com|reverbnation\.com|mixcloud\.com|cloudup\.com|animoto\.com|video\.wordpress\.com|embed\.ted\.com|embedly\.com|kickstarter\.com).*[^>].*>.*?<\/iframe>/mi' );
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
	 * @return mixed
	 *
	 * @since 2.4.6
	 */
	public function extra_available_addon_option() {
		?>
        <div class="show_advanced_options">
            <button class="button button-secondary"><?php esc_html_e( 'Show advanced options',
					'cookiebot-addons' ); ?></button>
            <span class="help-tip"
                  title="<?php echo esc_html__( 'This is for more advanced users.', 'cookiebot-addons' ); ?>"></span>
        </div>
        <div class="advanced_options">

            <label for="embed_regex"><?php esc_html_e( 'Regex:', 'cookiebot-addons' ); ?></label>
            <textarea
                    id="embed_regex"
                    cols="80"
                    rows="5"
                    name="cookiebot_available_addons[<?php echo $this->get_option_name(); ?>][regex]"
                    disabled
            ><?php echo esc_html( $this->get_regex() ); ?></textarea>

			<?php if ( $this->is_regex_default() ) : ?>
                <button id="edit_embed_regex" class="button"><?php esc_html_e( 'Edit regex',
						'cookiebot-addons' ); ?></button>
			<?php endif; ?>

            <button
                    id="btn_default_embed_regex"
                    class="button<?php echo ( $this->is_regex_default() ) ? ' hidden' : ''; ?>"
                    type="button"
                    value="Reset to default regex"><?php esc_html_e( 'Reset to default regex',
					'cookiebot-addons' ); ?></button>
            <input
                    type="hidden"
                    name="default_embed_regex"
                    id="default_embed_regex"
                    value="<?php echo esc_html( $this->get_default_regex() ); ?>"/>
        </div>
		<?php
	}

	/**
	 * Returns boolean to enable/disable plugin by default
	 *
	 * @return bool
	 *
	 * @since 3.6.3
	 */
	public function enable_by_default() {
		return false;
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
			'cookie_type' => $this->get_default_cookie_types(),
			'placeholder' => $this->get_default_placeholder(),
			'regex'       => $this->get_default_regex(),
		);
	}
}
