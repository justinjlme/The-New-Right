<?php
/**
 * The FEEDZY RSS Feeds bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://themeisle.com/plugins/feedzy-rss-feeds/
 * @since             1.0.0
 * @package feedzy-rss-feeds-pro
 *
 * @wordpress-plugin
 * Plugin Name:     Feedzy RSS Feeds Premium
 * Plugin URI:      http://themeisle.com/plugins/feedzy-rss-feeds/
 * Description:     FEEDZY RSS Feeds extends the functionality of FEEDZY RSS Feeds.
 * Version:         1.1.2
 * Author:          Themeisle
 * Author URI:      http://themeisle.com
 * Text Domain:     feedzy-rss-feeds
 * Domain Path:     /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-feedzy-rss-feed-pro-activator.php
 *
 * @since    1.0.0
 */
function activate_feedzy_rss_feeds_pro() {
	Feedzy_Rss_Feeds_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-feedzy-rss-feed-pro-deactivator.php
 *
 * @since    1.0.0
 */
function deactivate_feedzy_rss_feeds_pro() {
	Feedzy_Rss_Feeds_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_feedzy_rss_feeds_pro' );
register_deactivation_hook( __FILE__, 'deactivate_feedzy_rss_feeds_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    1.0.0
 */
function feedzy_rss_feeds_pro_autoload( $class ) {
	$namespaces = array( 'Feedzy_Rss_Feeds_Pro' );
	foreach ( $namespaces as $namespace ) {
		if ( substr( $class, 0, strlen( $namespace ) ) == $namespace ) {
			$filename = plugin_dir_path( __FILE__ ) . 'includes/' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
			if ( is_readable( $filename ) ) {
				require_once $filename;
				return true;
			}

			$filename = plugin_dir_path( __FILE__ ) . 'includes/abstract/' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
			if ( is_readable( $filename ) ) {
				require_once $filename;
				return true;
			}

			$filename = plugin_dir_path( __FILE__ ) . 'includes/admin/' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
			if ( is_readable( $filename ) ) {
				require_once $filename;
				return true;
			}
		}
	}
	return false;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_feedzy_rss_feeds_pro() {
	define( 'FEEDZY_PRO_BASEFILE', __FILE__ );
	define( 'FEEDZY_PRO_ABSURL', plugins_url( '/', __FILE__ ) );
	define( 'FEEDZY_PRO_ABSPATH', dirname( __FILE__ ) );

	$plugin = new Feedzy_Rss_Feeds_Pro();
	$plugin->run();
}

spl_autoload_register( 'feedzy_rss_feeds_pro_autoload' );

run_feedzy_rss_feeds_pro();



function feedzy_rss_feeds_pro_themeisle_sdk(){
	require dirname(__FILE__).'/vendor/themeisle/load.php';
	themeisle_sdk_register (
		array(
			'product_slug'=>'feedzy-rss-feeds-pro',
			'store_url'=>'https://themeisle.com',
			'store_name'=>'Themeisle',
			'product_type'=>'plugin',
			'wordpress_available'=>false,
			'paid'=>true,
		)
	);
}

feedzy_rss_feeds_pro_themeisle_sdk(); 

 
