<?php
/**
 * Scripts
 *
 * @package     WPeMatico\PluginName\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      array $wpematico_settings_page The slug for the WPeMatico settings page
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function make_me_feed_admin_scripts( $hook ) {
    global $wpematico_settings_page, $post_type;

    // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    /**
     * @todo		This block loads styles or scripts explicitly on the
     *				WPeMatico settings page.
     */
    if( $hook == $wpematico_settings_page ) {
        wp_enqueue_script( 'make_me_feed_admin_js', MAKE_ME_FEED_URL . '/assets/js/admin' . $suffix . '.js', array( 'jquery' ) );
        wp_enqueue_style( 'make_me_feed_admin_css', MAKE_ME_FEED_URL . '/assets/css/admin' . $suffix . '.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'make_me_feed_admin_scripts', 100 );


/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function make_me_feed_scripts( $hook ) {
    // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_script( 'make_me_feed_js', MAKE_ME_FEED_URL . '/assets/js/scripts' . $suffix . '.js', array( 'jquery' ) );
    wp_enqueue_style( 'make_me_feed_css', MAKE_ME_FEED_URL . '/assets/css/styles' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'make_me_feed_scripts' );
