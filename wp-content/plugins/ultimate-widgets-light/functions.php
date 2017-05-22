<?php

/**
 * Plugin Name: Ultimate Widgets Light
 * Plugin URI:  http://khothemes.com/ultimate-widgets/
 * Description: Ultimate Widgets includes several widgets and shortcodes with a style unique covering many different things you may need to add to your website.
 * Author:      KhoThemes
 * Author URI:  http://khothemes.com/
 * License:     GPLv2
 * Text Domain: kho
 * Domain Path: /languages/
 * Version:     1.5.9.4
 *
 * @fs_premium_only /premium/
 */
/*  Copyright 2007-2016 Khothemes
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Create a helper function for easy SDK access.
function uwl_fs()
{
    global  $uwl_fs ;
    
    if ( !isset( $uwl_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $uwl_fs = fs_dynamic_init( array(
            'id'             => '286',
            'slug'           => 'ultimate-widgets-light',
            'public_key'     => 'pk_0ac879dbcb2b339fe7c26c9b9ac3f',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'    => 'uwl_options',
            'support' => false,
        ),
            'is_live'        => true,
        ) );
    }
    
    return $uwl_fs;
}

// Init Freemius.
uwl_fs();
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    die;
}
define( 'UWL_PLUGIN', __FILE__ );
define( 'UWL_PLUGIN_DIR', untrailingslashit( dirname( UWL_PLUGIN ) ) );
define( 'UWL_VERSION', '1.5.9.4' );
function uwl_plugin_url( $path = '' )
{
    $url = plugins_url( $path, UWL_PLUGIN );
    if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
        $url = 'https:' . substr( $url, 5 );
    }
    return $url;
}

function uwl_minify_css( $css = '' )
{
    // Return if no CSS
    if ( !$css ) {
        return;
    }
    // Normalize whitespace
    $css = preg_replace( '/\\s+/', ' ', $css );
    // Remove ; before }
    $css = preg_replace( '/;(?=\\s*})/', '', $css );
    // Remove space after , : ; { } */ >
    $css = preg_replace( '/(,|:|;|\\{|}|\\*\\/|>) /', '$1', $css );
    // Remove space before , ; { }
    $css = preg_replace( '/ (,|;|\\{|})/', '$1', $css );
    // Strips leading 0 on decimal values (converts 0.5px into .5px)
    $css = preg_replace( '/(:| )0\\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
    // Strips units if value is 0 (converts 0px to 0)
    $css = preg_replace( '/(:| )(\\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
    // Trim
    $css = trim( $css );
    // Return minified CSS
    return $css;
}

/**
 * Generate random string
 */
function uwl_random_string( $length )
{
    $key = null;
    $keys = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
    for ( $i = 0 ;  $i < $length ;  $i++ ) {
        $key .= $keys[array_rand( $keys )];
    }
    return $key;
}

// Make sure we have a global var of the class
global  $uwl_setup ;
// Start up class
class UWL_Setup
{
    /**
     * Start things up
     */
    public function __construct()
    {
        require_once UWL_PLUGIN_DIR . '/assets/core.php';
        require_once UWL_PLUGIN_DIR . '/assets/images-resize.php';
        require_once UWL_PLUGIN_DIR . '/assets/inline-style.php';
        require_once UWL_PLUGIN_DIR . '/assets/styling.php';
        require_once UWL_PLUGIN_DIR . '/assets/walker-nav.php';
        require_once UWL_PLUGIN_DIR . '/assets/widgets-functions.php';
        add_action( 'plugins_loaded', array( $this, 'uwl_core' ) );
        add_action( 'widgets_init', array( $this, 'uwl_register_sidebar' ), 1 );
        add_action( 'wp_enqueue_scripts', array( $this, 'uwl_plugin_css' ) );
        add_action( 'admin_init', array( $this, 'uwl_admin_style' ) );
        add_action( 'wp_head', array( $this, 'uwl_custom_css' ), 9999 );
    }
    
    /**
     * Language/admin
     */
    public function uwl_core()
    {
        load_plugin_textdomain( 'kho', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        // ReduxFramework admin panel
        
        if ( function_exists( 'uwl_supports' ) && uwl_supports( 'primary', 'admin' ) ) {
            // Include the Redux options Framework
            if ( !class_exists( 'ReduxFramework' ) ) {
                require_once UWL_PLUGIN_DIR . '/assets/admin/redux-core/framework.php';
            }
            // Register all the main options
            require_once UWL_PLUGIN_DIR . '/assets/admin/admin-config.php';
        }
    
    }
    
    /**
     * Register sidebar
     */
    public static function uwl_register_sidebar()
    {
        register_sidebar( array(
            'name'        => __( 'UWL - Shortcode Generator', 'kho' ),
            'id'          => 'uwl-shortcodes',
            'description' => __( '1. Drag widget(s) here. 2. Fill in the fields and hit save. 3. Copy the shortocde generated at the bottom of the widget form and use it on posts or pages.', 'kho' ),
        ) );
    }
    
    /**
     * Scripts
     */
    public function uwl_plugin_css()
    {
        // Core style
        wp_enqueue_style(
            'uwl-style',
            uwl_plugin_url( 'assets/css/style.min.css' ),
            array(),
            UWL_VERSION
        );
        // RTL style
        if ( is_rtl() ) {
            wp_enqueue_style(
                'ultimate-style-rtl',
                uwl_plugin_url( 'assets/css/rtl.min.css' ),
                array(),
                UWL_VERSION
            );
        }
    }
    
    /**
     * Admin style
     */
    public function uwl_admin_style()
    {
        wp_enqueue_style(
            'uwl-admin-style',
            uwl_plugin_url( 'assets/css/admin.css' ),
            array(),
            UWL_VERSION
        );
    }
    
    /**
     * All plugin functions hook into the uwl_head_css filter for this function.
     * This way all dynamic CSS is minified and outputted in one location in the site header.
     */
    public static function uwl_custom_css( $output = NULL )
    {
        // Add filter for adding custom css via other functions
        $output = apply_filters( 'uwl_head_css', $output );
        // Minify and output CSS in the wp_head
        if ( !empty($output) ) {
            echo  '<!-- UWL CSS -->
<style type="text/css">
' . wp_strip_all_tags( uwl_minify_css( $output ) ) . '
</style>' ;
        }
    }

}
$uwl_setup = new UWL_Setup();