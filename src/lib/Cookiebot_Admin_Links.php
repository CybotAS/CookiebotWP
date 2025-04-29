<?php

namespace cybot\cookiebot\lib;

class Cookiebot_Admin_Links {

	/**
	 * Extra admin links
	 *
	 * @var array
	 */
	protected $admin_links;

	public function __construct() {
		$this->admin_links = $this->add_links();
	}

	public function register_hooks() {
		add_filter( 'plugin_action_links_cookiebot/cookiebot.php', array( $this, 'set_settings_action_link' ) );
		add_action( 'admin_init', array( $this, 'handle_external_redirects' ) );
	}

	public function handle_external_redirects() {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) ) {
			return;
		}
	}

	public function display() {
		return true;
	}

	public function set_settings_action_link( $actions ) {
		$cb_actions = array();

		foreach ( $this->admin_links as $link ) {
			$item = array(
				'url'    => $link['override'] && $link['condition'] ? $link['over_url'] : $link['url'],
				'label'  => $link['override'] && $link['condition'] ? $link['over_label'] : $link['label'],
				'strong' => $link['strong'],
			);

			$cb_actions[ $link['index'] ] = $this->get_link_html( $item );
		}

		$actions = array_merge( $actions, $cb_actions );
		ksort( $actions );

		return $actions;
	}

	private function add_links() {
		return array(
			array(
				'url'       => add_query_arg( 'page', 'cookiebot', admin_url( 'admin.php' ) ),
				'label'     => 'Get Started',
				'strong'    => false,
				'override'  => false,
				'condition' => false,
				'index'     => 'dashboard',
			),
		);
	}

	private function get_link_html( $link ) {
		$link_html = '<a href="' . esc_url( $link['url'] ) . '">';

		if ( $link['strong'] ) {
			$link_html .= '<b>';
		}

		// phpcs:ignore WordPress.WP.I18n.NoEmptyStrings,WordPress.WP.I18n.MissingTranslatorsComment
		$link_html .= esc_html( sprintf( __( '%s', 'cookiebot' ), $link['label'] ) );

		if ( $link['strong'] ) {
			$link_html .= '</b>';
		}

		$link_html .= '</a>';

		return $link_html;
	}
}
