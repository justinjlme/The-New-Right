<?php
// Core Functions

/*-----------------------------------------------------------------------------------*/
/*	- Setup core Framework
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'uwl_global_config' ) ) {
	function uwl_global_config() {
		$setup = array(
			'primary'		=> array(
				'admin'			=> true,
				'post_types'	=> true,
			),
			'mce'			=> array(
				'fontselect'		=> true,
				'fontsizeselect'	=> true,
				'formats'			=> true,
				'shortcodes'		=> true,
			),
			'helpers'		=> array (
				'display_queries_memory'	=> false,
			),
			'minify'		=> array(
				'js'	=> true,
				'css'	=> true
			),
		);
		return apply_filters( 'uwl_global_config', $setup );
	}
}

/*-----------------------------------------------------------------------------------*/
/*	- Checks framework for core support
/*-----------------------------------------------------------------------------------*/
function uwl_supports( $group, $feature ) {
	$setup = uwl_global_config();
	if( isset( $setup[$group][$feature] ) && $setup[$group][$feature] ) {
		return true;
	} else {
		return false;
	}
}

/*-----------------------------------------------------------------------------------*/
/*	- Returns the correct option value
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'uwl_option' ) ) {
	function uwl_option( $id, $fallback = false, $param = false ) {
		// Return value based on $_GET value
		if ( isset( $_GET[ 'uwl_'.$id ] ) ) {
			if ( '-1' == $_GET[ 'uwl_'.$id ] ) {
				return false;
			} else {
				return $_GET[ 'uwl_'.$id ];
			}
		}
		// Return value based on panel option
		else {
			// Get options
			global $uwl_options;
			// Check if fallback is false set to empty
			if ( $fallback == false ) {
				$fallback = '';
			}
			// If option value exists and not empty return value
			if( isset( $uwl_options[ $id ] ) && '' != $uwl_options[ $id ] ) {
				$output = $uwl_options[ $id ];
			}
			// Otherwise return fallback
			else {
				$output = $fallback;
			}
			// If param defined return param
			if ( !empty( $uwl_options[ $id ] ) && $param ) {
				$output = $uwl_options[ $id ][ $param ];
			}
		}
		return $output;
	}
}