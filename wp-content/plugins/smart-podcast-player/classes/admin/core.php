<?php
/**
 * Smart Podcast Player
 * 
 * @package   SPP_Core
 * @author    jonathan@redplanet.io
 * @link      http://www.smartpodcastplayer.com
 * @copyright 2015 SPI Labs, LLC
 */

/**
  * @package SPP_Admin_Core
  * @author Jonathan Wondrusch <jonathan@redplanet.io?
 */

class SPP_Admin_Core {

	protected $_settings = array();

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = SPP_Core::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( dirname(__FILE__) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		add_action( 'init', array( $this, 'settings' ) );
		add_action( 'init', array( 'SPP_Admin_Core', 'update_check' ) );
		
		add_action( 'admin_post_clear_spp_cache', 'SPP_Admin_Core::clear_spp_cache_fn' );
		
		add_action( 'admin_post_spp_set_license_key', 'SPP_Admin_Core::spp_set_license_key_fn' );

		global $pagenow;

		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ||  $pagenow == 'options-general.php' || $pagenow != 'widgets.php' || current_user_can('publish_posts') ) {

			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// add new buttons
			add_filter('mce_buttons', array( $this, 'register_buttons' ) );

			add_filter('mce_external_plugins', array( $this, 'register_tinymce_javascript' ) );

			add_action( 'admin_head', array( $this, 'fb_add_tinymce' ) );

			add_action( 'admin_head', array( $this, 'admin_css' ) );

		}


	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
		
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if( is_admin() ) {
        	wp_enqueue_style( 'wp-color-picker' );
        }

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$advanced_options = get_option( 'spp_player_advanced');
		$color_pickers = isset( $advanced_options['color_pickers'] ) ? $advanced_options['color_pickers'] : "true";
		
		// Load the color pickers if the option is true or unset
		if ("true" == $color_pickers) { 
			$dependencies = array('jquery', 'wp-color-picker');
		} else {
			$dependencies = array('jquery');
		}
		wp_enqueue_script( $this->plugin_slug . '-admin-script',
				SPP_ASSETS_URL . 'js/admin-spp.min.js',
				$dependencies,
				SPP_Core::VERSION );

		$plugin = SPP_Core::get_instance();
		wp_localize_script( $this->plugin_slug . '-admin-script',
				'Smart_Podcast_Player_Admin',
				array('licensed' => $plugin->is_paid_version()));
				
		wp_localize_script( $this->plugin_slug . '-admin-script',
				'smart_podcast_player_user_settings',
				get_option( 'spp_player_defaults' ));


	}

	public function settings() {

		require_once( SPP_PLUGIN_BASE . 'classes/admin/settings.php' );
		$this->settings = new SPP_Admin_Settings();
		
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	// Filter to set sslverify to false in the plugin update checker
	public static function puc_filter( $options ) {
		$options[ 'sslverify' ] = false;
		return $options;
	}
	
	// Performs an update check.  Returns whether the check completed successfully,
	// regardless of whether updates are available.  This also serves as a license
	// check, since the server at smartpodcastplayer.com will not return information
	// if the license is invalid.
	public static function update_check( $license_key = null ) {
		if( empty( $license_key ) ) {
			$license_key = SPP_Core::get_license_key();
			if( empty( $license_key ) )
				return false;
		}
		
		$update_server = 'https://smartpodcastplayer.com';
		if( ( $util_opt = get_option( 'spp_util_general' ) )
				&& isset( $util_opt[ 'update_server' ] )
				&& defined( 'ABSPATH' )
				&& include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
			if( function_exists( 'is_plugin_active' )
					&& is_plugin_active( 'smart-podcast-player-utilities/smart-podcast-player-utilities.php' ) ) {
				$update_server = $util_opt[ 'update_server' ];
			}
		}
		
		require_once( SPP_PLUGIN_BASE . 'classes/vendor/plugin-update-checker-1.6.1/plugin-update-checker.php' );
		$puc = new PluginUpdateChecker_1_6_for_SmartPodcastPlayer (
			$update_server . '/license/check/' . trim($license_key) . '/',
			SPP_PLUGIN_BASE . 'smart-podcast-player.php',
			'smart-podcast-player',
			24
		);
		$puc->addHttpRequestArgFilter( array( 'SPP_Admin_Core', 'puc_filter' ) );
		if( current_user_can( 'update_plugins' ) ) {
			$puc->maybeCheckForUpdates();
		}
		
		$state = $puc->getUpdateState();
		if( $state !== null && $state->update !== null )
			return true;
		return false;
	}

	public function download_progress( $download_size, $downloaded, $upload_size, $uploaded ) {

	    $percent = $downloaded / $download_size;
		
		return ( $downloaded > ( 512 * 1024 ) ) ? 1 : 0;

	}

	public function register_buttons($buttons) {
	   array_push( $buttons, 'separator', 'spp' );
	   array_push( $buttons, 'separator', 'stp' );
	   return $buttons;
	}

	public function register_tinymce_javascript( $plugin_array ) {
	   $plugin_array['spp'] = SPP_PLUGIN_URL . '/assets/js/spp-mce/spp.js' . '?v=' . SPP_Core::VERSION;
	   $plugin_array['stp'] = SPP_PLUGIN_URL . '/assets/js/spp-mce/stp.js' . '?v=' . SPP_Core::VERSION;
	   return $plugin_array;
	}

	public function fb_add_tinymce() {
	    global $typenow;
	    global $pagenow;

	    // only on Post Type: post and page
	    if( ! in_array( $typenow, array( 'post', 'page' ) ) && $pagenow != 'post.php' && $pagenow != 'post-new.php' )
	        return ;

	    add_filter( 'mce_external_plugins', array( $this, 'fb_add_tinymce_plugin' ) );
	    // Add to line 1 form WP TinyMCE
	    add_filter( 'mce_buttons', array( $this, 'fb_add_tinymce_button' ) );

	}

	// inlcude the js for tinymce
	public function fb_add_tinymce_plugin( $plugin_array ) {

	    $plugin_array['spp'] = SPP_PLUGIN_URL . '/assets/js/spp-mce/spp.js' . '?v=' . SPP_Core::VERSION;
	    $plugin_array['stp'] = SPP_PLUGIN_URL . '/assets/js/spp-mce/stp.js' . '?v=' . SPP_Core::VERSION;
	    
	    return $plugin_array;
	}

	// Add the button key for address via JS
	public function fb_add_tinymce_button( $buttons ) {

	    array_push( $buttons, 'spp_button_key' );
	    array_push( $buttons, 'stp_button_key' );

	    return $buttons;
	    
	}

	public function admin_css() {

		echo '<style>' . "\n\t";
			echo '.spp-indented-option { margin-left: 50px; }' . "\n\t";
			echo 'th.spp-wider-column { width: 250px; }' . "\n\t";
			echo '.mce-container .spp-mce-hr { '
					. 'border-top: 1px solid #444;'
					. 'margin-top: 5px;'
					. 'margin-bottom: 5px;'
				    . '}' . "\n\t";
			echo '.spp-color-picker .wp-picker-container { position: relative; top: 4px; left: 2px; }' . "\n\t";
			echo '.spp-color-picker .wp-picker-container a { margin: 0; }' . "\n\t";
			echo 'i.mce-i-stp-icon { background: transparent url("' . SPP_PLUGIN_URL . 'assets/images/stp-icon.png" ) 0 0 no-repeat; background-size: 100%; }' . "\n\t";
			echo 'i.mce-i-spp-icon { background: transparent url("' . SPP_PLUGIN_URL . 'assets/images/spp-icon.png" ) 0 0 no-repeat; background-size: 100%; }' . "\n\t";
		echo '</style>';

	}
	
	public static function clear_spp_cache_fn() {
	
		if ( ! wp_verify_nonce( $_POST[ 'clear_spp_cache_nonce' ], 'clear_spp_cache' ) )
            die( 'Invalid nonce.' . var_export( $_POST, true ) );
		
		SPP_Core::clear_cache();
		
		if ( ! isset ( $_POST['_wp_http_referer'] ) )
            die( 'Missing target.' );
		
		$url = add_query_arg( 'spp_cache', 'cleared', urldecode( $_POST['_wp_http_referer'] ) );
        wp_safe_redirect( $url );
        exit;
	}
	
	public static function spp_set_license_key_fn() {
		if ( ! wp_verify_nonce( $_POST[ 'spp_set_license_key_nonce' ], 'spp_set_license_key' ) )
            die( 'Invalid nonce.' . var_export( $_POST, true ) );
		
		if( isset( $_POST[ 'spp_player_general' ] ) ) {
			update_option( 'spp_player_general', $_POST[ 'spp_player_general' ] );
		}
		
		// Invalidate any previous license checks
		delete_option( 'spp_license_check' );
		delete_site_option( 'external_updates-smart-podcast-player' );
		
		if ( ! isset ( $_POST['_wp_http_referer'] ) )
            die( 'Missing target.' );
        wp_safe_redirect( urldecode( $_POST['_wp_http_referer'] ) );
		exit;
	}

}
