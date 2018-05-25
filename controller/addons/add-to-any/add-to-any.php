<?php

namespace cookiebot_addons_framework\controller\addons\add_to_any;

class Add_To_Any {

  public function __construct() {
    add_action( 'wp_loaded', array( $this, 'cookiebot_addon_add_to_any' ), 5 );
  }

  /**
   * Disable scripts if state not accepted
   *
   * @since 1.2.0
   */
  public function cookiebot_addon_add_to_any() {
    // Check if Add To Any is loaded.
    if ( ! function_exists( 'A2A_SHARE_SAVE_init' ) ) {
      return;
    }

    // Check if Cookiebot is activated and active.
    if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
      return;
    }

    // Disable Add To Any if marketing not allowed
    if ( ! cookiebot_is_cookie_state_accepted( 'marketing' ) ) {
      add_filter( 'addtoany_script_disabled', '__return_true' );
    }

    // External js, so manipulate attributes
    if ( has_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_enqueue_script' ) ) {
      cookiebot_script_loader_tag( 'addtoany', 'marketing');
    }
  }
}