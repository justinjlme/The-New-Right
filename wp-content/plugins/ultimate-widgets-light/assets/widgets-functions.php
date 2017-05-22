<?php
/**
 * Widgets Functions
 *
 * @author Nick
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'Ultimate_Widgets_Functions' ) ) {
	class Ultimate_Widgets_Functions {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'uwl_custom_widgets' ) );
			add_action( 'widgets_init', array( $this, 'register_uwl_widgets' ) );
		}

		/**
		 * Widgets
		 */
		public function uwl_custom_widgets() {
			
			// Define array of custom widgets
			$widgets = array(
				'about-me',
				'contact-info',
				'flickr',
				'instagram',
				'mailchimp',
				'menu',
				'text',
				'video',
			);

			// Apply filters so you can remove custom widgets via a child theme if wanted
			$widgets = apply_filters( 'custom_widgets', $widgets );

			// Loop through widgets and load their files
			foreach ( $widgets as $widget ) {
				$widget_file = UWL_PLUGIN_DIR .'/widgets/widget-'. $widget .'.php';
				if ( file_exists( $widget_file ) ) {
					require_once( $widget_file );
				}
			}
		
		}

		/**
		 * Register widgets
		 */
		public function register_uwl_widgets() {
			
			if ( '1' == uwl_option( 'about-me', '1' ) ) {
			    register_widget('uwl_about_me');
			}
			if ( '1' == uwl_option( 'contact-info', '1' ) ) {
			    register_widget('uwl_contact_info');
			}
			if ( '1' == uwl_option( 'flickr', '1' ) ) {
			    register_widget('uwl_flickr');
			}
			if ( '1' == uwl_option( 'instagram', '1' ) ) {
			    register_widget('uwl_instagram');
			}
			if ( '1' == uwl_option( 'mailchimp', '1' ) ) {
			    register_widget('uwl_mailchimp');
			}
			if ( '1' == uwl_option( 'menu', '1' ) ) {
			    register_widget('uwl_menu');
			}
			if ( '1' == uwl_option( 'text', '1' ) ) {
			    register_widget('uwl_text');
			}
			if ( '1' == uwl_option( 'video', '1' ) ) {
			    register_widget('uwl_video');
			}
		
		}

	}
}
$ultimate_widgets_functions = new Ultimate_Widgets_Functions();