<?php

// The widget class for Cookiebot D
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
	public function form( $instance ) {	
		$defaults = array('lang'=>'');
		extract( wp_parse_args( ( array ) $instance, $defaults ) );		
		$title = (isset($title) ? $title : '');
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'Cookiebot' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'lang' ); ?>"><?php esc_html_e( 'Language', 'cookiebot' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'lang' ); ?>" id="<?php echo $this->get_field_id( 'lang' ); ?>" class="widefat">
				<option value=""><?php echo esc_html__('- Default -', 'cookiebot'); ?></option>
				<?php
				$options = Cookiebot_WP::get_supported_languages();
				foreach ( $options as $key => $name ) {
					echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $lang, $key, false ) . '>'. $name . '</option>';
				}
				?>
			</select>
			
		</p>
		<?php
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['lang']   = isset( $new_instance['lang'] ) ? wp_strip_all_tags( $new_instance['lang'] ) : '';
		$instance['title']  = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		
		// WordPress core before_widget hook
		echo $before_widget;
		
		echo '<div class="widget-text wp_widget_plugin_box cookiebot_cookie_declaration">';
		
		// Display widget title if defined
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		$cbid = Cookiebot_WP::get_cbid();
		if(!empty($lang)) { $lang = '  data-culture="'.$lang.'"'; }
		if(!is_multisite() || get_site_option('cookiebot-script-tag-cd-attribute','custom') == 'custom') {
			$tagAttr = get_option('cookiebot-script-tag-cd-attribute','async');
		}
		else {
			$tagAttr = get_site_option('cookiebot-script-tag-cd-attribute');
		}
		
		echo '<script id="CookieDeclaration" src="https://consent.cookiebot.com/'.$cbid.'/cd.js"'.$lang.' type="text/javascript" '.$tagAttr.'></script>';
		
		echo '</div>';
		
		// WordPress core after_widget hook
		echo $after_widget;
	}

}
