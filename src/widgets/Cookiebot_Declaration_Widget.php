<?php

namespace cybot\cookiebot\widgets;

use cybot\cookiebot\lib\Supported_Languages;
use InvalidArgumentException;
use WP_Widget;
use cybot\cookiebot\lib\Cookiebot_WP;
use function cybot\cookiebot\lib\include_view;

class Cookiebot_Declaration_Widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'cookiebot_declaration_widget',
			esc_html__( 'Cookiebot - Cookie Declaration', 'cookiebot' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )

	/**
	 * @throws InvalidArgumentException
	 */
	public function form( $instance ) {
		$defaults   = array(
			'lang'  => '',
			'title' => '',
		);
		$fixed_args = array(
			'title_field_id'      => $this->get_field_id( 'title' ),
			'title_field_name'    => $this->get_field_name( 'title' ),
			'lang_field_id'       => $this->get_field_id( 'lang' ),
			'lang_field_name'     => $this->get_field_name( 'lang' ),
			'supported_languages' => Supported_Languages::get(),
		);
		$view_args  = wp_parse_args( (array) $instance, array_merge( $defaults, $fixed_args ) );
		include_view( 'admin/widgets/cookiebot-declaration-widget-form.php', $view_args );
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['lang']  = isset( $new_instance['lang'] ) ? wp_strip_all_tags( $new_instance['lang'] ) : '';
		$instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		return $instance;
	}

	/**
	 * Display the widget
	 *
	 * @throws InvalidArgumentException
	 */
	public function widget( $args, $instance ) {
		$before_widget_html    = isset( $args['before_widget'] ) && is_string( $args['before_widget'] )
			? $args['before_widget']
			: '';
		$after_widget_html     = isset( $args['after_widget'] ) && is_string( $args['after_widget'] )
			? $args['after_widget']
			: '';
		$has_before_title_html = isset( $args['before_title'] ) && is_string( $args['before_title'] );
		$has_after_title_html  = isset( $args['after_title'] ) && is_string( $args['after_title'] );
		if ( $has_before_title_html && $has_after_title_html ) {
			$before_title_html = $args['before_title'];
			$after_title_html  = $args['after_title'];
		} else {
			$before_title_html = '<h2>';
			$after_title_html  = '</h2>';
		}
		$has_widget_title   = isset( $instance['title'] ) && is_string( $instance['title'] );
		$widget_title_html  = $has_widget_title
			? $before_title_html . $instance['title'] . $after_title_html
			: '';
		$tag_attribute_html = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
		if ( ! is_multisite() || $tag_attribute_html === 'custom' ) {
			$tag_attribute_html = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
		}
		$view_args = array(
			'cookie_declaration_script_url' => 'https://consent.cookiebot.com/' . Cookiebot_WP::get_cbid() . '/cd.js',
			'culture'                       => isset( $lang ) && is_string( $lang ) ? $lang : null,
			'before_widget_html'            => $before_widget_html,
			'after_widget_html'             => $after_widget_html,
			'widget_title_html'             => $widget_title_html,
			'tag_attribute_html'            => $tag_attribute_html,
		);
		include_view( 'frontend/widgets/cookiebot-declaration-widget.php', $view_args );
	}

}
