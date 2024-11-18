<?php

namespace cybot\cookiebot\lib;

class Cookiebot_Admin_Links {
	/**
	 * Extra admin links
	 *
	 * @var array
	 */
	protected $admin_links;
	protected $menu_links;

	public function __construct() {
		$this->admin_links = $this->add_links();
		$this->menu_links  = $this->add_menu_links();
	}

	public function register_hooks() {
		add_filter( 'plugin_action_links_cookiebot/cookiebot.php', array( $this, 'set_settings_action_link' ) );
		add_action( 'admin_menu', array( $this, 'add_extra_menu' ) );
	}

	public function add_extra_menu() {
		global $submenu;

		foreach ( $this->menu_links as $link ) {
			$submenu['cookiebot'][] = array(
				$link['override'] && $link['condition'] ?
					esc_html( sprintf( __( '%s', 'cookiebot' ), $link['over_label'] ) ) :
					esc_html( sprintf( __( '%s', 'cookiebot' ), $link['label'] ) ),
				'manage_options',
				$link['override'] && $link['condition'] ?
					esc_url( $link['over_url'] ) :
					esc_url( $link['url'] ),
			);
		}
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

	private function add_menu_links() {
		return array(
			array(
				'url'        => 'https://admin.cookiebot.com/signup/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'label'      => 'Upgrade a plan',
				'override'   => true,
				'over_url'   => 'https://admin.cookiebot.com/signup?coupon=BFRIDAYWP10&utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'over_label' => 'Upgrade a plan',
				'condition'  => empty( Cookiebot_WP::get_cbid() ) && ( strtotime( 'now' ) < strtotime( '2024-11-30' ) ),
			),
		);
	}

	private function add_links() {
		return array(
			array(
				'url'       => add_query_arg( 'page', 'cookiebot', admin_url( 'admin.php' ) ),
				'label'     => 'Dashboard',
				'strong'    => false,
				'override'  => false,
				'condition' => false,
				'index'     => 'dashboard',
			),
			array(
				'url'        => 'https://admin.cookiebot.com/signup/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'label'      => 'Upgrade your plan',
				'strong'     => true,
				'override'   => true,
				'over_url'   => 'https://admin.cookiebot.com/signup?coupon=BFRIDAYWP10&utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'over_label' => 'Get 10% off until 29.11',
				'condition'  => empty( Cookiebot_WP::get_cbid() ) && ( strtotime( 'now' ) < strtotime( '2024-11-30' ) ),
				'index'      => 'a',
			),
		);
	}

	private function get_link_html( $link ) {
		$link_html = '<a href="' . esc_url( $link['url'] ) . '">';

		if ( $link['strong'] ) {
			$link_html .= '<b>';
		}

		$link_html .= esc_html( sprintf( __( '%s', 'cookiebot' ), $link['label'] ) );

		if ( $link['strong'] ) {
			$link_html .= '</b>';
		}

		$link_html .= '</a>';

		return $link_html;
	}
}
