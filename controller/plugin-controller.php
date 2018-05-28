<?php

namespace cookiebot_addons_framework\controller;

class Plugin_Controller {

	/**
	 * Array of addon plugins
	 *
	 * @var array
	 *
	 * @since 1.1.0
	 */
	private $plugins;

	/**
	 * Load addons if the plugin is activated
	 *
	 * @since 1.1.0
	 */
	public function check_addons() {
		$this->load_plugins();
		$activeAddons = $this->get_active_addons();

		if ( ! function_exists( 'is_plugin_active' ) ){
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		foreach ( $this->plugins as $plugin_class => $plugin ) {
			/**
			 * Load addon code if the plugin is active and addon is activated
			 */
			if ( is_plugin_active( $plugin->file ) ) {
				$this->load_addon( $plugin->class );
			}
		}
	}
	
	/**
	 * Load list of addons with status
	 *
	 * @since 1.2.0
	 */
	 
	public function load_active_addons() {
		$addons = $this->get_addon_list();
		foreach( $addons['available'] as $plugin_class=>$plugin ) {
			if ( is_plugin_active( $plugin['file'] ) ) {
				$this->load_addon( $plugin['class'] );
			}
		}
	}
	
	/**
	 * Load list of addons with status
	 *
	 * @since 1.2.0
	 */
	public function get_addon_list() {
		$addons = [];
		
		$this->load_plugins();
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		foreach ( $this->plugins as $plugin_class => $plugin ) {
			$addons[(is_plugin_active( $plugin->file ) ? 'available' : 'unavailable')][$plugin_class] = [
					'name'=>$plugin->name,
					'file'=>$plugin->file,
					'class'=>$plugin->class,
					'available'=> ( is_plugin_active( $plugin->file ) ? true : false ),
				];
		}
		return $addons;
	}
	
	
	/**
	 * Get list of active addons
	 *
	 * @since 1.2.0
	 */
	public function get_active_addons() {
		$activePlugins = get_option('cookiebot-addons-activated','');
		if(!empty($activePlugins)) {
			return explode(';',$activePlugins);
		}
		return false;
	}
	
	/** 
	 * Register settings for addons
	 * 
	 * @since 1.2.0
	 */
	/*public function register_setting() {
		$this->load_plugins();
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		foreach ( $this->plugins as $plugin_class => $plugin ) {
			
		}
	}*/
	
	/**
	 * Add menu for addon settings
	 * 
	 * @since 1.2.0
	 */
	public function add_menu() {
		add_options_page( 'Cookiebot Addons', __('Cookiebot Addons','cookiebot-addons'), 'manage_options', 'cookiebot-addons', array($this,'setting_page'));
	}
	
	/**
	 * Settign page for Cookiebot addons
	 *
	 * @since 1.2.0
	 */
	function setting_page() {
		if(isset($_GET['action']) && ($_GET['action'] == 'deactivate' || $_GET['action'] == 'activate')) {
			$active = ( $_GET['action'] == 'activate' ) ? 'yes' : 'no';
			update_option('cookiebot-addons-active-'.sanitize_key($_GET['addon']),$active);
			
			
			$status = ($active == 'yes') ? 'The addon is now activated' : 'The addon is now deactivated';
			?>
			<div class="updated notice is-dismissible">
        <p><?php _e( $status, 'cookiebot-addons' ); ?></p>
			</div>
			<?php
		}
		$addons = $this->get_addon_list();
		
		?>
		<div class="wrap">
			<h1><?php _e('Cookiebot Addons','cookiebot-addons'); ?></h1>
			<p>
				<?php _e('Below is a list of addons for Cookiebot. Addons help you making contributed plugins GDPR compliant.','cookiebot-addons'); ?>
				<br />
				<?php _e('Deactive addons if you want to handle GDPR compliance yourself or using another plugin.','cookiebot-addons'); ?>
			</p>
			<?php
			
			foreach($addons['available'] as $plugin_class=>$plugin) {
				?>
				<div class="postbox cookiebot-addon">
					<h2><?php echo $plugin['name']; ?></h2>
					<?php
					if(get_option('cookiebot-addons-active-'.sanitize_key($plugin_class),'yes') == 'yes') {
						?>
						<a href="<?php echo admin_url('options-general.php?page=cookiebot-addons&action=deactivate&addon='.$plugin_class); ?>">
							<?php _e('Deactivate addon','cookiebot-addons'); ?>
						</a>
						<?php
					}
					else {
						?>
						<a href="<?php echo admin_url('options-general.php?page=cookiebot-addons&action=activate&addon='.$plugin_class); ?>">
							<?php _e('Activate addon','cookiebot-addons'); ?>
						</a>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<h2><?php _e('Unavailable Addons','cookiebot-addons'); ?></h2>
			<p>
				<?php _e('Following addons are unavailable. This is usual because the addon is not useable because the main plugin is not activated.'); ?>
			</p>
			<?php
			foreach($addons['unavailable'] as $plugin_class=>$plugin) {
				?>
				<div class="postbox cookiebot-addon">
					<h2><?php echo $plugin['name']; ?></h2>
					<i><?php _e('Unavailable','cookiebot-addons'); ?></i>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	
	/**
	 * 
	 * 
	 */
	 
	public function add_wp_admin_style($hook) {
		if( $hook != 'settings_page_cookiebot-addons' ) { return; }
		//die(__FILE__);
		wp_enqueue_style( 'cookiebot_addons_custom_css', plugins_url('admin_styles.css',dirname(__FILE__)) );
	}
	
	/**
	 * Loads plugins from json file
	 *
	 * All the addon plugins are defined there.
	 *
	 * The file is located at the root map of this plugin
	 *
	 * @since 1.1.0
	 */
	private function load_plugins() {
		if( empty( $this->plugins ) ) {
			$file          = file_get_contents( CAF_DIR . 'addons.json' );
			$this->plugins = json_decode( $file );
		}
	}

	/**
	 * Dynamically Loads addon plugin configuration class
	 *
	 * For example:
	 * /controller/addons/google-analyticator/google-analyticator.php
	 *
	 * @param $class    string  Plugin class name
	 *
	 * @since 1.1.0
	 */
	private function load_addon( $class ) {
		$full_class_name = 'cookiebot_addons_framework\\controller\\addons\\' . $class;

		/**
		 * Load addon class
		 */

		if ( class_exists( $full_class_name ) ) {
			new $full_class_name;
		}
	}
}
