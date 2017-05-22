<?php
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

add_action('admin_init', 'make_me_feed_admin_init');
function make_me_feed_admin_init(){
	add_filter(	'plugin_row_meta',	'make_me_feed_init_row_meta',10,2);
	add_filter(	'plugin_action_links_' . plugin_basename( __FILE__ ), 'make_me_feed_init_action_links');
}

function make_me_feed_tab($tabs) {
	$tabs['make_me_feed'] = __( 'Make Me Feed', 'wpematico' );
	return $tabs;
}
//add_filter( 'wpematico_settings_tabs',  'make_me_feed_tab');

/*function make_me_feed_license_menu() {
	add_submenu_page(
				'edit.php?post_type=wpematico',
				'SMTP Settings',
				'SMTP <span class="dashicons-before dashicons-admin-plugins"></span>',
				'manage_options',
				'make_me_feed_license',
				'make_me_feed_license_page'
			);
	//add_plugins_page( 'Plugin License', 'Plugin License', 'manage_options', 'make_me_feed_license', 'make_me_feed_license_page' );
}
add_action('admin_menu', 'make_me_feed_license_menu');
*/


/** * Activate Make Me Feed on Activate Plugin */
register_activation_hook( plugin_basename( __FILE__ ), 'make_me_feed_activate' );
function make_me_feed_activate() {
	if(class_exists('WPeMatico')) {
		$cfg = get_option(WPeMatico :: OPTION_KEY);
		if( update_option( WPeMatico::OPTION_KEY, $cfg ) ) {
			$link= '<a href="' . admin_url("edit.php?post_type=wpematico&page=wpematico_settings&tab=make-me-feed") . '">'.__('Make Me Feed Plugin Settings.',  'make_me_feed')."</a>";
			$notice= __('Make Me Feed Activated.  Please check the fields on', 'make_me_feed').' '. $link;
			WPeMatico::add_wp_notice( array('text' => $notice , 'below-h2'=>false ) );
		}
	}
}

/** * Deactivate Make Me Feed on Deactivate Plugin  */
register_deactivation_hook( plugin_basename( __FILE__ ), 'make_me_feed_deactivate' );
function make_me_feed_deactivate() {
	if(class_exists('WPeMatico')) {
		if( update_option( WPeMatico::OPTION_KEY, $cfg ) ) {
			$notice= __('Make Me Feed DEACTIVATED.',  'make_me_feed');
			WPeMatico::add_wp_notice( array('text' => $notice , 'below-h2'=>false ) );
		}
	}
}

/*
register_uninstall_hook( plugin_basename( __FILE__ ), 'make_me_feed_uninstall' );
function make_me_feed_uninstall() {
	
}
*/



/**
* Actions-Links del Plugin
*
* @param   array   $data  Original Links
* @return  array   $data  modified Links
*/
function make_me_feed_init_action_links($data)	{
	if ( !current_user_can('manage_options') ) {
		return $data;
	}
	return array_merge(
		$data,
		array(
			'<a href="'.  admin_url('edit.php?post_type=wpematico&page=wpematico_settings&tab=make-me-feed').'" title="' . __('Go to Make Me Feed Settings Page') . '">' . __('Settings') . '</a>',
		)
	);
}

/**
* Meta-Links del Plugin
*
* @param   array   $data  Original Links
* @param   string  $page  plugin actual
* @return  array   $data  modified Links
*/

function make_me_feed_init_row_meta($data, $page)	{
	if ( basename($page) != 'make-me-feed.php' ) {
		return $data;
	}
	return array_merge(
		$data,
		array(
		'<a href="http://etruel.com/" target="_blank">' . __('etruel Store') . '</a>',
		'<a href="http://etruel.com/my-account/support/" target="_blank">' . __('Support') . '</a>',
		'<a href="https://wordpress.org/support/view/plugin-reviews/wpematico?filter=5&rate=5#postform" target="_Blank" title="Rate 5 stars on Wordpress.org">' . __('Rate Plugin' ) . '</a>'
		)
	);
}	

