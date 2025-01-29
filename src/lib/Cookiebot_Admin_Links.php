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
		add_action( 'admin_init', array( $this, 'handle_external_redirects' ) );
		add_action( 'admin_menu', array( $this, 'add_extra_menu' ) );
	}

	public function handle_external_redirects() {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		foreach ( $this->menu_links as $slug => $link ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( $slug === $_GET['page'] ) {
				$link = $link['override'] && $link['condition'] ? $link['over_url'] : $link['url'];
				//phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $link );
				exit;
			}
		}
	}

	public function display() {
		return true;
	}

	public function add_extra_menu() {
		foreach ( $this->menu_links as $slug => $link ) {
			add_submenu_page(
				'cookiebot',
				$link['label'],
				$link['override'] && $link['condition'] ?
					// phpcs:ignore WordPress.WP.I18n.NoEmptyStrings,WordPress.WP.I18n.MissingTranslatorsComment
					esc_html( sprintf( __( '%s', 'cookiebot' ), $link['over_label'] ) ) :
					// phpcs:ignore WordPress.WP.I18n.NoEmptyStrings,WordPress.WP.I18n.MissingTranslatorsComment
					esc_html( sprintf( __( '%s', 'cookiebot' ), $link['label'] ) ),
				'manage_options',
				$slug,
				array( $this, 'display' ),
				20
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
			'cookiebot_upgrade' => array(
				'url'       => Cookiebot_Frame::is_cb_frame_type() === true ? self::CB_FRAME_UPGRADE_URL : self::UC_FRAME_UPGRADE_URL,
				'label'     => 'Upgrade a plan',
				'override'  => false,
				'condition' => false,
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
				'url'       => Cookiebot_Frame::is_cb_frame_type() === true ? self::CB_FRAME_UPGRADE_URL : self::UC_FRAME_UPGRADE_URL,
				'label'     => 'Upgrade your plan',
				'strong'    => true,
				'override'  => false,
				'condition' => false,
				'index'     => 'a',
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

	const CB_FRAME_UPGRADE_URL = 'https://admin.cookiebot.com/signup/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner';
	const UC_FRAME_UPGRADE_URL = 'https://account.usercentrics.eu/?trial=standard&uc_subscription_type=web&pricing_plan=FreeExtended&utm_source=wordpress&utm_medium=referral&utm_campaign=banner';
}
