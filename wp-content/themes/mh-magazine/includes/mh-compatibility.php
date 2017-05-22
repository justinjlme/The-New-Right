<?php

/***** Deprecated functionality *****/

/** Page Title Output - Scheduled for removal in MH Magazine v3.7.0 **/

if (!function_exists('mh_magazine_page_title')) {
	function mh_magazine_page_title() {
		if (!is_front_page()) {
			echo '<header class="page-header">' . "\n";
				echo '<h1 class="page-title">';
					if (is_archive()) {
						if (is_category() || is_tax()) {
							single_cat_title();
						} elseif (is_tag()) {
							single_tag_title();
						} elseif (is_author()) {
							global $author;
							$user_info = get_userdata($author);
							printf(_x('Articles by %s', 'post author', 'mh-magazine'), esc_attr($user_info->display_name));
						} elseif (is_day()) {
							echo get_the_date();
						} elseif (is_month()) {
							echo get_the_date('F Y');
						} elseif (is_year()) {
							echo get_the_date('Y');
						} elseif (is_post_type_archive()) {
							global $post;
							$post_type = get_post_type_object(get_post_type($post));
							echo esc_attr($post_type->labels->name);
						} else {
							_e('Archives', 'mh-magazine');
						}
					} else {
						if (is_home()) {
							echo esc_attr(get_the_title(get_option('page_for_posts', true)));
						} elseif (is_404()) {
							_e('Page not found (404)', 'mh-magazine');
						} elseif (is_search()) {
							printf(__('Search Results for %s', 'mh-magazine'), esc_attr(get_search_query()));
						} else {
							the_title();
						}
					}
				echo '</h1>' . "\n";
			echo '</header>' . "\n";
		}
	}
}

/** Add custom CSS field only if it already contains data - Scheduled for removal **/

if (!function_exists('mh_magazine_custom_css_field')) {
	function mh_magazine_custom_css_field($wp_customize) {
		$mh_magazine_options = mh_magazine_theme_options();
		if ($mh_magazine_options['custom_css'] != '') {
			$wp_customize->add_section('mh_magazine_css', array('title' => esc_html__('Custom CSS', 'mh-magazine'), 'priority' => 8, 'panel' => 'mh_magazine_theme_options'));
			$wp_customize->add_setting('mh_magazine_options[custom_css]', array('default' => '', 'type' => 'option', 'sanitize_callback' => 'mh_sanitize_textarea'));
			$wp_customize->add_control('custom_css', array('label' => esc_html__('Custom CSS', 'mh-magazine'), 'section' => 'mh_magazine_css', 'settings' => 'mh_magazine_options[custom_css]', 'priority' => 1, 'type' => 'textarea'));
		}
	}
}
add_action('customize_register', 'mh_magazine_custom_css_field');

?>