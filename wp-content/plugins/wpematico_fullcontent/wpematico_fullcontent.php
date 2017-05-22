<?php
/*
Plugin Name: WPeMatico Full Content
Plugin URI: https://etruel.com/downloads/wpematico-full-content/
Description: Add On for WPeMatico plugin. Add Full Content Parser and editor of config files to get full content from almost all domains.
Version: 1.5.3
Author: etruel
Author URI: http://www.netmdp.com
License: GPLv2
*/

/*  Copyright 2017	Esteban  Truelsegaard(email : esteban@netmdp.com)
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

*/
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if(!defined( 'WPEFULLCONTENT_VERSION' ) ) define( 'WPEFULLCONTENT_VERSION', '1.5.3' ); 
if(!defined( 'WPEFULLCONTENT_STORE_URL' ) ) define( 'WPEFULLCONTENT_STORE_URL', 'https://etruel.com' ); 
if(!defined( 'WPEFULLCONTENT_ITEM_NAME' ) ) define( 'WPEFULLCONTENT_ITEM_NAME', 'WPeMatico Full Content' ); 
if(!defined( 'WPEFULLCONTENT_PATH' ) ) define( 'WPEFULLCONTENT_PATH', plugin_dir_path( __FILE__ ) ); 
if(!defined( 'WPEFULLCONTENT_REQ_WPEMATICO' ) )   define( 'WPEFULLCONTENT_REQ_WPEMATICO', '1.5' );

if(!WPeMatico_fullcontent_checkPrerequisites()){
	return false;
}

if( !class_exists( 'EDD_SL_Plugin_Updater' ) )
require_once( dirname( __FILE__ ) . '/Plugin_Updater.php' );
require_once( dirname( __FILE__ ) . '/inc/functions.php' );

require_once( dirname( __FILE__ ) . '/inc/campaign_edit.php' );
require_once( dirname( __FILE__ ) . '/inc/getcontent.php' );
require_once( dirname( __FILE__ ) . '/inc/settings.php' );

/**
* check for required PHP extensions, tell admin if any are missing
*/
function WPeMatico_fullcontent_checkPrerequisites() {
	// need at least PHP 5.2.11 for libxml_disable_entity_loader()
	$message = '';
	
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . basename(admin_url()) . '/includes/plugin.php' );
	}
	
	if( !is_plugin_active( 'wpematico/wpematico.php') ) {
		$message.= __('You are using WPeMatico Full Content.', 'WPeMatico_fullcontent' ).' ';
		$message.= __('Plugins <b>WPeMatico</b> must be activated!', 'WPeMatico_fullcontent' );
		$message.= ' <a href="'.admin_url('plugins.php').'#wpematico"> '. __('Go to Activate Now', 'WPeMatico_fullcontent' ). '</a>';
		$message.= '<script type="text/javascript">jQuery(document).ready(function($){$("#wpematico").css("backgroundColor","yellow");});</script>';
		$checks=false;
	}else{  //WPeMatico is active
		if( !class_exists( 'WPeMatico') ) {
			$message.= __('You are using WPeMatico Full Content, but doesn\'t exist class WPeMatico.', 'WPeMatico_fullcontent' );
			$message.= __('Something is going wrong. Contact etruel.', 'WPeMatico_fullcontent' );
			$checks=false;
		}
		if (version_compare(WPEMATICO_VERSION, WPEFULLCONTENT_REQ_WPEMATICO, '<')) {
			$message = '<p>'.WPEFULLCONTENT_ITEM_NAME.' requires WPeMatico '. esc_html(WPEFULLCONTENT_REQ_WPEMATICO) . ' or higher; your website has WPeMatico '. esc_html(WPEMATICO_VERSION) .
				' which is old, obsolete, and unsupported.</p>
				<p>Please upgrade your WPeMatico Plugin from the Wordpress Plugins page.</p>';
		}		
	}

	if (!empty($message)) {
		add_action('admin_notices', function() use ($message) {
				echo '<div id="message" class="error fade">'.$message.'</div>';
			});
		return false;
	}
	
	return true;
}


/**
 * Filter for wpematico pro
 * @param string $customconfigdir If not exist new dir return this
 * @return string
 */
add_filter( 'wpematico_fullcontent_folder',  'wpematico_fullcontent_folder',10,1 );
function wpematico_fullcontent_folder($customconfigdir) {
	if( is_dir(wpematico_fullcontent_foldercreator(false)) ) return wpematico_fullcontent_foldercreator(false);
	
	return $customconfigdir;
}

function fullcontent_is_folder_exist(){
	if( is_dir(wpematico_fullcontent_foldercreator(false)) ) return true;
	else return false;
}
function wpefullcontent_folder_notice($param) {
	?><div class="notice notice-error is-dismissible"><p><?php
		_e('Your Custom folder stil remains inside own plugin directory. This means that your files will be replaced/deleted when update the plugin.');
		echo "<br />";
		_e('It\'s strongly recommended that you move your files to Wordpress uploads directory to don\'t loose your files later.');
	?></div></p><?php
}

add_action('admin_init', 'wpematico_fullcontent_admin_init');
function wpematico_fullcontent_admin_init(){
	//Additional links on the plugin page
	add_filter(	'plugin_row_meta',	'wpematico_fullcontent_init_row_meta',10,2);
	add_filter(	'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpematico_fullcontent_init_action_links');
}

/**
* Actions-Links del Plugin
*
* @param   array   $data  Original Links
* @return  array   $data  modified Links
*/
function wpematico_fullcontent_init_action_links($data)	{
	if ( !current_user_can('manage_options') ) {
		return $data;
	}
	return array_merge(
		$data,
		array(
			'<a href="'.  admin_url('edit.php?post_type=wpematico&page=wpematico_settings&tab=fullcontent').'" title="' . __('Go to Full Content Settings Page') . '">' . __('Settings') . '</a>',
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

function wpematico_fullcontent_init_row_meta($data, $page)	{
	if ( basename($page) != basename(__FILE__) ) {
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

add_filter( 'wpematico_plugins_updater_args', 'plugin_updater_full_content', 10, 1);
function plugin_updater_full_content($args) {
	if (empty($args['fullcontent'])) {
		$args['fullcontent'] = array();
		$args['fullcontent']['api_url'] = 'https://etruel.com';
		$args['fullcontent']['plugin_file'] = WPEFULLCONTENT_PATH.'/wpematico_fullcontent.php';
		$args['fullcontent']['api_data'] = array(
										'version' 	=> WPEFULLCONTENT_VERSION, // current version number
										'item_name' => WPEFULLCONTENT_ITEM_NAME, 	// name of this plugin
										'author' 	=> 'Esteban Truelsegaard'  // author of this plugin
									);
					
	}
	return $args;
}



