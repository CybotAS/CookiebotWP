<?php
/**
 * @var string $cbid
 * @var string $lang
 * @var string $tag_attr
 * @var string $cookie_blocking_mode
 * @var array $data_regions
 * @var string|bool $tcf
 */

$script_attributes = array(
	'type'                 => 'text/javascript',
	'id'                   => 'Cookiebot',
	'src'                  => 'https://consent.cookiebot.com/uc.js',
	'data-implementation' => 'wp',
	'data-cbid'            => $cbid,
);

if ( $tcf ) {
	$script_attributes['data-framework'] = $tcf;
}

if ( $data_regions ) {
	$georegions = array();
	foreach ( $data_regions as $cbid => $regions ) {
		$georegions[] = '{\'region\':\'' . esc_attr( $regions ) . '\',\'cbid\':\'' . esc_attr( $cbid ) . '\'}';
	}
	$script_attributes['data-georegions'] = implode( ',', $georegions );
}

if ( (bool) get_option( 'cookiebot-gtm' ) !== false && ! empty( get_option( 'cookiebot-data-layer' ) ) ) {
	$script_attributes['data-layer-name'] = get_option( 'cookiebot-data-layer' );
}

if ( ! empty( $lang ) ) {
	$script_attributes['data-culture'] = strtoupper( $lang );
}

if ( $cookie_blocking_mode === 'auto' ) {
	$script_attributes['data-blockingmode'] = 'auto';
} elseif ( ! empty( $tag_attr ) ) {
	$script_attributes[ $tag_attr ] = true;
}

wp_print_script_tag( $script_attributes );
