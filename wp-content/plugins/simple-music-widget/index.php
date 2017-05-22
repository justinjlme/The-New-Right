<?php
/**
 * Plugin Name: Simple Music Widget
 * Plugin URI: https://github.com/dolatabadi/simple-music-widget
 * Description: This plugin creates a widget that can be used to display a music player which includes artist's name, song and a cover image.
 * Version: 1.5
 * Author: Dolatabadi
 * Text Domain: simple-music-widget
 * Domain Path: /languages
 * Author URI: https://github.com/dolatabadi
 * License: GNU General Public License v2.
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/*
 * Set constants
 */
define( 'SIMPLE_MUSIC_WIDGET_VERSION', '1.3' );
define( 'SIMPLE_MUSIC_WIDGET_DIR', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_MUSIC_WIDGET_URL', plugin_dir_url( __FILE__ ) );

/*
 * Launch the plugin
 */
add_action( 'plugins_loaded', 'simple_music_widget_plugins_loaded' );
add_action( 'init', 'simple_music_widget_lang_init' );
add_action( 'init', 'simple_music_widget_styles' );
add_action( 'admin_enqueue_scripts', 'simple_music_widget_script' );

/**
 * Initializes the plugin.
 */
function simple_music_widget_plugins_loaded() {
  add_action( 'widgets_init', 'simple_music_widget_init' );
}

/**
 * Register the widget
 * Load widget file.
 */
function simple_music_widget_init() {
  require_once SIMPLE_MUSIC_WIDGET_DIR . 'widget.php';
  register_widget( 'simple_music_widget' );
}

/**
 * Load language files.
 */
function simple_music_widget_lang_init() {
  load_plugin_textdomain( 'simple-music-widget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/**
 * Load custom style.
 */
function simple_music_widget_styles() {
  wp_register_style( 'simple_music_widget_styles', plugins_url( '/css/style.css', __FILE__ ) );
  wp_enqueue_style( 'simple_music_widget_styles' );
}

/**
 * Load custom script.
 */
function simple_music_widget_script() {
  wp_enqueue_media();
  wp_enqueue_script( 'simple_music_widget_script', plugins_url( '/js/simple-music-widget.js', __FILE__ ) );
}
