<?php
// Custom Menu Walker

if( ! class_exists( 'UWL_Dropdown_Walker_Nav_Menu' ) ) {
	class UWL_Dropdown_Walker_Nav_Menu extends Walker_Nav_Menu {
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "\n" . $indent  .'<ul class="uwl-sub-menu">' . "\n";
		}

		function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>" ."\n";
		}

	    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		    global $wp_query;
		    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$class_names = $value = '';

		    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
		    $classes[] = 'menu-item-' . $item->ID;
		    
			if($depth >=0 && $args->has_children) {
		    	$classes[] = 'has-sub';
		    }

		    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		    $class_names = ' class="' . esc_attr( $class_names ) . '"';

		    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		    $output .= $indent . '<li' . $id . $value . $class_names .'>';

		    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		    // Icon
		    $icon = '';
		    if($item->icon != ""){
		    	$icon = '<i class="'.$item->icon.'"></i>';
		    }

		    $item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . $icon . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			if($args->has_children) {
		    	$item_output .= '<span class="uwl-sub-icon"><i class="icon_plus"></i><i class="icon_close"></i></span>';
		    }
		    $item_output .= $args->after;

		    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
			$id_field = $this->db_fields['id'];
			if ( is_object( $args[0] ) ) {
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
			}
			return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

	}
}