<?php
namespace cybot\cookiebot\widgets;

use cybot\cookiebot\lib\Cookiebot_WP;

class Dashboard_Widget_Cookiebot_Status {

	public function register_hooks() {
		if ( is_admin() ) {
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
		}
	}

	/**
	 * Cookiebot_WP Add dashboard widgets to admin
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'cookiebot_status',
			esc_html__( 'Cookiebot Status', 'cookiebot' ),
			array(
				$this,
				'dashboard_widget_status',
			)
		);
	}

	/**
	 * Cookiebot_WP Output Dashboard Status Widget
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function dashboard_widget_status() {
		if ( empty( Cookiebot_WP::get_cbid() ) ) {
			echo '<p>' . esc_html__( 'You need to enter your Cookiebot ID.', 'cookiebot' ) . '</p>';
			echo '<p><a href="options-general.php?page=cookiebot">';
			echo esc_html__( 'Update your Cookiebot ID', 'cookiebot' );
			echo '</a></p>';
		} else {
			echo '<p>' . esc_html_e( 'Your Cookiebot is working!', 'cookiebot' ) . '</p>';
		}
	}
}
