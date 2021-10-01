<?php
namespace cybot\cookiebot\lib;

class Supported_Languages {

	public static function get() {
		$supported_languages       = array();
		$supported_languages['nb'] = __( 'Norwegian Bokmål', 'cookiebot' );
		$supported_languages['tr'] = __( 'Turkish', 'cookiebot' );
		$supported_languages['de'] = __( 'German', 'cookiebot' );
		$supported_languages['cs'] = __( 'Czech', 'cookiebot' );
		$supported_languages['da'] = __( 'Danish', 'cookiebot' );
		$supported_languages['sq'] = __( 'Albanian', 'cookiebot' );
		$supported_languages['he'] = __( 'Hebrew', 'cookiebot' );
		$supported_languages['ko'] = __( 'Korean', 'cookiebot' );
		$supported_languages['it'] = __( 'Italian', 'cookiebot' );
		$supported_languages['nl'] = __( 'Dutch', 'cookiebot' );
		$supported_languages['vi'] = __( 'Vietnamese', 'cookiebot' );
		$supported_languages['ta'] = __( 'Tamil', 'cookiebot' );
		$supported_languages['is'] = __( 'Icelandic', 'cookiebot' );
		$supported_languages['ro'] = __( 'Romanian', 'cookiebot' );
		$supported_languages['si'] = __( 'Sinhala', 'cookiebot' );
		$supported_languages['ca'] = __( 'Catalan', 'cookiebot' );
		$supported_languages['bg'] = __( 'Bulgarian', 'cookiebot' );
		$supported_languages['uk'] = __( 'Ukrainian', 'cookiebot' );
		$supported_languages['zh'] = __( 'Chinese', 'cookiebot' );
		$supported_languages['en'] = __( 'English', 'cookiebot' );
		$supported_languages['ar'] = __( 'Arabic', 'cookiebot' );
		$supported_languages['hr'] = __( 'Croatian', 'cookiebot' );
		$supported_languages['th'] = __( 'Thai', 'cookiebot' );
		$supported_languages['el'] = __( 'Greek', 'cookiebot' );
		$supported_languages['lt'] = __( 'Lithuanian', 'cookiebot' );
		$supported_languages['pl'] = __( 'Polish', 'cookiebot' );
		$supported_languages['lv'] = __( 'Latvian', 'cookiebot' );
		$supported_languages['fr'] = __( 'French', 'cookiebot' );
		$supported_languages['id'] = __( 'Indonesian', 'cookiebot' );
		$supported_languages['mk'] = __( 'Macedonian', 'cookiebot' );
		$supported_languages['et'] = __( 'Estonian', 'cookiebot' );
		$supported_languages['pt'] = __( 'Portuguese', 'cookiebot' );
		$supported_languages['ga'] = __( 'Irish', 'cookiebot' );
		$supported_languages['ms'] = __( 'Malay', 'cookiebot' );
		$supported_languages['sl'] = __( 'Slovenian', 'cookiebot' );
		$supported_languages['ru'] = __( 'Russian', 'cookiebot' );
		$supported_languages['ja'] = __( 'Japanese', 'cookiebot' );
		$supported_languages['hi'] = __( 'Hindi', 'cookiebot' );
		$supported_languages['sk'] = __( 'Slovak', 'cookiebot' );
		$supported_languages['es'] = __( 'Spanish', 'cookiebot' );
		$supported_languages['sv'] = __( 'Swedish', 'cookiebot' );
		$supported_languages['sr'] = __( 'Serbian', 'cookiebot' );
		$supported_languages['fi'] = __( 'Finnish', 'cookiebot' );
		$supported_languages['eu'] = __( 'Basque', 'cookiebot' );
		$supported_languages['hu'] = __( 'Hungarian', 'cookiebot' );
		asort( $supported_languages, SORT_LOCALE_STRING );

		return $supported_languages;
	}
}
