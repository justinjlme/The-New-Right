<?php
/**
 * Plugin Name:     Make Me Feed
 * Plugin URI:      http://etruel.com/downloads/wpematico-make-feed-good/
 * Description:     Addon for WPeMatico that allows to create RSS 2.0 feeds with content from external sites on your Wordpress blog, regardless of whether or not those have their own feed.
 * Version:         1.4
 * Author:          etruel
 * Author URI:      http://www.netmdp.com
 * Text Domain:     make-me-feed
 *
 * @package         etruel\Make me Feed
 * @author          Esteban Truelsegaard
 * @copyright       Copyright (c) 2016
 *
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;
// Plugin version
if( !defined( 'MAKE_ME_FEED_VER' ) ) define( 'MAKE_ME_FEED_VER', '1.4' );

if( !class_exists( 'Make_me_Feed' ) ) {

    /**
     * Main Make_me_Feed class
     *
     * @since       1.0.0
     */
    class Make_me_Feed {

        /**
         * @var         Make_me_Feed $instance The one true Make_me_Feed
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true Make_me_Feed
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new Make_me_Feed();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin path
            define( 'MAKE_ME_FEED_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'MAKE_ME_FEED_URL', plugin_dir_url( __FILE__ ) );
			
			if(!defined( 'MAKE_ME_FEED_STORE_URL' ) ) define( 'MAKE_ME_FEED_STORE_URL', 'http://etruel.com' ); 
			if(!defined( 'MAKE_ME_FEED_ITEM_NAME' ) ) define( 'MAKE_ME_FEED_ITEM_NAME', 'WPeMatico Make me Feed Good' ); 
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Include scripts
			if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) 
				if( file_exists( WPEMATICO_PLUGIN_DIR . 'app/lib/Plugin_Updater.php' ) )
					require_once ( WPEMATICO_PLUGIN_DIR . 'app/lib/Plugin_Updater.php' );
				else require_once ( MAKE_ME_FEED_DIR . 'includes/Plugin_Updater.php' );
			require_once MAKE_ME_FEED_DIR . 'includes/etruel_licenses_handler.php';
			require_once MAKE_ME_FEED_DIR . 'includes/scripts.php';
            require_once MAKE_ME_FEED_DIR . 'includes/plugin_functions.php';
            require_once MAKE_ME_FEED_DIR . 'includes/cpt/cpt_list.php';
            require_once MAKE_ME_FEED_DIR . 'includes/functions.php';

            // require_once MAKE_ME_FEED_DIR . 'includes/shortcodes.php';
            // require_once MAKE_ME_FEED_DIR . 'includes/widgets.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         *
         */
        private function hooks() {
            // Register settings
            add_filter( 'wpematico_settings_extensions', array( $this, 'settings' ), 1 );
			add_filter( 'template_include', array(	__CLASS__, 'include_template_function'), 1 );
            
            if( class_exists( 'EDD_License' ) ) {
                $license = new EDD_License( __FILE__, MAKE_ME_FEED_STORE_URL, MAKE_ME_FEED_VER, 'Esteban Truelsegaard' );
            }
        }

		public static function include_template_function( $template_path ) {
			global $post;
			if ( get_post_type() == 'make-me-feed' ) {
			//if ( $post->post_type == 'make-me-feed' ) {
				if ( is_single() ) {
					// checks if the file exists in the theme first,
					// otherwise serve the file from the plugin
					if ( $theme_file = locate_template( array ( 'single-make-me-feed.php' ) ) ) {
						$template_path = $theme_file;
					} else {
						$template_path = MAKE_ME_FEED_DIR . 'page-template/single-make-me-feed.php';
					}
				}
			}
			return $template_path;
		}

        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = MAKE_ME_FEED_DIR . '/languages/';
            $lang_dir = apply_filters( 'make_me_feed_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'make-me-feed' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'make-me-feed', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/make-me-feed/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/make-me-feed/ folder
                load_textdomain( 'make-me-feed', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/make-me-feed/languages/ folder
                load_textdomain( 'make-me-feed', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'make-me-feed', false, $lang_dir );
            }
        }


        /**
         * Add settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings The existing EDD settings array
         * @return      array The modified EDD settings array
         */
        public function settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'make_me_feed_settings',
                    'name'  => '<strong>' . __( 'Plugin Name Settings', 'make-me-feed' ) . '</strong>',
                    'desc'  => __( 'Configure Plugin Name Settings', 'make-me-feed' ),
                    'type'  => 'header',
                )
            );

            return array_merge( $settings, $new_settings );
        }
    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true Make_me_Feed
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \Make_me_Feed The one true Make_me_Feed
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but EDD is not
 *              present.
 */
function Make_me_Feed_load() {
    if( !class_exists( 'WPeMatico' ) ) {
        if( !class_exists( 'WPeMatico_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }

        $activation = new WPeMatico_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        return Make_me_Feed::instance();
    }
}
add_action( 'plugins_loaded', 'Make_me_Feed_load' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function make_me_feed_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'make_me_feed_activation' );
