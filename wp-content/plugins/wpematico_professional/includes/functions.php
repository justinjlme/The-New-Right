<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

add_action( 'init', 'wpempro_register_taxonomies', 99 );
function wpempro_register_taxonomies() {
	$taxonomies = get_taxonomies();
	foreach ($taxonomies as $taxonomy) {
		register_taxonomy_for_object_type($taxonomy, 'wpematico');
	}
}	

add_action('admin_menu', 'wpempro_remtax_from_menu',99);
function wpempro_remtax_from_menu() {
	global $submenu, $menu;
	// This needs to be set to the URL for the admin menu section (aka "Menu Page")
	$menu_page = 'edit.php?post_type=wpematico';
	if( !isset($submenu[$menu_page])) {
		foreach($menu as $item=>$arrayval) {
			if($arrayval[0]=="WPeMatico") {
				$menu_page = $arrayval[2];
			}
		}
	}
		
	$taxonomies = get_taxonomies();
	foreach ($taxonomies as $taxonomy) {
		// This needs to be set to the URL for the admin menu option to remove (aka "Submenu Page")
		$taxonomy_admin_page = 'edit-tags.php?taxonomy='.$taxonomy.'&amp;post_type=wpematico';
		// This removes the menu option but doesn't disable the taxonomy
		foreach($submenu[$menu_page] as $index => $submenu_item) {
			if ($submenu_item[2]==$taxonomy_admin_page) {
				unset($submenu[$menu_page][$index]);
			}
		}	
	}	

/*	add_submenu_page(
		'edit.php?post_type=wpematico',
		__( 'PRO Settings', WPeMatico :: TEXTDOMAIN ),
		'<img src="' . self :: $uri.'/images/administrator_16.png'.'" style="margin: 0pt 2px -2px 0pt;"><span>' . __( 'PRO Settings', WPeMatico :: TEXTDOMAIN ) . "</span>",
		'manage_options',
		'wpematico_settings&tab=prosettings',
		'wpematico_settings&tab=prosettings' 
	);
*/
}

//- Check duplicates by title after change the custom title
//add_filter('wpematico_item_parsers', 'wpematico_check_custom_titles',999,4);
function wpempro_check_custom_titles( $current_item, $campaign, $feed, $item ) {
	global $wpdb;
	$title = $current_item['title'];

	$table_name = $wpdb->prefix . "posts";
	$query="SELECT post_title,id FROM $table_name
				WHERE post_title = '".$title."'
				AND ((`post_status` = 'published') OR (`post_status` = 'publish' ) OR (`post_status` = 'draft' ) OR (`post_status` = 'private' ))";
				//GROUP BY post_title having count(*) > 1" ;
	$row = $wpdb->get_row($query);

	trigger_error(sprintf(__('Checking duplicated title \'%1s\'', WPeMatico :: TEXTDOMAIN ),$title).': '.((!! $row) ? __('Yes') : __('No')) ,E_USER_NOTICE);
	$dup = !! $row;

	return ($dup) ? -1 : $current_item;
}

/**
 * Checks if all words in array are in a string
 * @param type $string
 * @param array $array
 * @param boolean $anyword to check if a word exist or ALL words exist
 * @return type $boolean True depends $anyword if all words in array are in $string
 */
function wpempro_contains($string, array $array, $anyword = false) {
    $count = 0;
    foreach($array as $value) {
        if (false !== stripos($string,$value)) {
            ++$count;
        };
    }
    return ($anyword) ? $count > 0 : $count == count($array) ;
}

function replace_first_offset($search, $replace, $var, $offset) {
	$pos = strpos($var, $search, $offset);
	$ret = new stdClass();
	$ret->result = $var;
    $ret->pos = $offset;
	if ($pos !== false) {
       $ret->result = substr_replace($var, $replace, $pos, strlen($search));
       $ret->pos = $pos+strlen($replace);
    } 
    return $ret;
}