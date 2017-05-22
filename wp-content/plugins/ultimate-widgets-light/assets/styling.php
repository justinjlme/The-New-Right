<?php
/**
 * All Custom CSS
 *
 * @author     Nick
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'UWL_Custom_CSS' ) ) {
	
	class UWL_Custom_CSS {

		/**
		 * Main constructor
		 */
		public function __construct() {
		   
			// Add custom CSS for the accent
			add_filter( 'uwl_head_css', array( $this, 'generate' ), 1 );

		}

		/**
		 * Style Widgets
		 */
		public function uwl_style_widgets() {

			// Var
			$css = '';

			/**
			   Widget Style 1
			**/
			// Border color
			$style1_border_color = uwl_option( 'style1_border_color' );
			if ( '' != $style1_border_color && '#e0e0e0' != $style1_border_color ) {
				$css .= '.uwl_widget_wrap.style1 {border-color: '. $style1_border_color .';}';
			}

			// Padding
			$style1_padding = uwl_option( 'style1_padding' );
			if ( '' != $style1_padding && '16px' != $style1_padding ) {
				$css .= '.uwl_widget_wrap.style1 {padding: '. $style1_padding .' !important;}
						.uwl_widget_wrap.style1 .uwl-title {top: -'. $style1_padding .';}';
			}

			// Margin bottom
			$style1_margin_bottom = uwl_option( 'style1_margin_bottom' );
			if ( '' != $style1_margin_bottom && '40px' != $style1_margin_bottom ) {
				$css .= '.uwl-style1, .uwl_tabs_widget {margin-bottom: '. $style1_margin_bottom .';}';
			}

			// Title background
			$style1_title_bg = uwl_option( 'style1_title_bg' );
			if ( '' != $style1_title_bg && '#e7f1ff' != $style1_title_bg ) {
				$css .= '.uwl_widget_wrap.style1 .uwl-title span {background: '. $style1_title_bg .';}';
			}

			// Title color
			$style1_title_color = uwl_option( 'style1_title_color' );
			if ( '' != $style1_title_color && '#2676ef' != $style1_title_color ) {
				$css .= '.uwl_widget_wrap.style1 .uwl-title {color: '. $style1_title_color .';}';
			}

			// Title font size
			$style1_title_font_size = uwl_option( 'style1_title_font_size' );
			if ( '' != $style1_title_font_size && '10px' != $style1_title_font_size ) {
				$css .= '.uwl_widget_wrap.style1 .uwl-title {font-size: '. $style1_title_font_size .';}';
			}

			/**
			   Widget Style 2
			**/
			// Margin bottom
			$style2_margin_bottom = uwl_option( 'style2_margin_bottom' );
			if ( '' != $style2_margin_bottom && '50px' != $style2_margin_bottom ) {
				$css .= '.uwl-style2, .uwl_tabs_widget {margin-bottom: '. $style2_margin_bottom .';}';
			}
			
			// Title color
			$style2_title_color = uwl_option( 'style2_title_color' );
			if ( '' != $style2_title_color && '#333' != $style2_title_color ) {
				$css .= '.uwl_widget_wrap.style2 .uwl-title {color: '. $style2_title_color .';}';
			}

			// Title font size
			$style2_title_font_size = uwl_option( 'style2_title_font_size' );
			if ( '' != $style2_title_font_size && '16px' != $style2_title_font_size ) {
				$css .= '.uwl_widget_wrap.style2 .uwl-title {font-size: '. $style2_title_font_size .';}';
			}

			// Title border color
			$style2_title_border_color = uwl_option( 'style2_title_border_color' );
			if ( '' != $style2_title_border_color && '#4dbefa' != $style2_title_border_color ) {
				$css .= '.uwl_widget_wrap.style2 .uwl-title::after {border-color: '. $style2_title_border_color .';}';
			}

			// Title border width
			$style2_title_border_width = uwl_option( 'style2_title_border_width' );
			if ( '' != $style2_title_border_width && '40%' != $style2_title_border_width ) {
				$css .= '.uwl_widget_wrap.style2 .uwl-title::after {width: '. $style2_title_border_width .';}';
			}

			/**
			   Widget Style 3
			**/
			// Margin bottom
			$style3_margin_bottom = uwl_option( 'style3_margin_bottom' );
			if ( '' != $style3_margin_bottom && '30px' != $style3_margin_bottom ) {
				$css .= '.uwl-style3, .uwl_tabs_widget {margin-bottom: '. $style3_margin_bottom .';}';
			}
			
			// Title background
			$style3_title_bg = uwl_option( 'style3_title_bg' );
			if ( '' != $style3_title_bg && '#f2f2f2' != $style3_title_bg ) {
				$css .= '.uwl_widget_wrap.style3 .uwl-title {background-color: '. $style3_title_bg .';}';
			}

			// Title color
			$style3_title_color = uwl_option( 'style3_title_color' );
			if ( '' != $style3_title_color && '#666' != $style3_title_color ) {
				$css .= '.uwl_widget_wrap.style3 .uwl-title {color: '. $style3_title_color .';}';
			}

			// Title font size
			$style3_title_font_size = uwl_option( 'style3_title_font_size' );
			if ( '' != $style3_title_font_size && '18px' != $style3_title_font_size ) {
				$css .= '.uwl_widget_wrap.style3 .uwl-title {font-size: '. $style3_title_font_size .';}';
			}

			// Title border color
			$style3_title_border_color = uwl_option( 'style3_title_border_color' );
			if ( '' != $style3_title_border_color && '#4dbefa' != $style3_title_border_color ) {
				$css .= '.uwl_widget_wrap.style3 .uwl-title {border-color: '. $style3_title_border_color .';}';
			}

			/**
			   Widget Style 4
			**/
			// Background
			$style4_bg = uwl_option( 'style4_bg' );
			if ( '' != $style4_bg && '#fcfcfc' != $style4_bg ) {
				$css .= '.uwl_widget_wrap.style4 {background: '. $style4_bg .';}';
			}

			// Border color
			$style4_border_color = uwl_option( 'style4_border_color' );
			if ( '' != $style4_border_color && '#e0e0e0' != $style4_border_color ) {
				$css .= '.uwl_widget_wrap.style4 {border-color: '. $style4_border_color .';}';
			}

			// Padding
			$style4_padding = uwl_option( 'style4_padding' );
			if ( '' != $style4_padding && '20px' != $style4_padding ) {
				$css .= '.uwl_widget_wrap.style4 {padding: '. $style4_padding .' !important;}
						.uwl_widget_wrap.style4 .uwl-title {margin: -'. $style4_padding .';margin-bottom: '. $style4_padding .';}';
			}

			// Margin bottom
			$style4_margin_bottom = uwl_option( 'style4_margin_bottom' );
			if ( '' != $style4_margin_bottom && '30px' != $style4_margin_bottom ) {
				$css .= '.uwl-style4, .uwl_tabs_widget {margin-bottom: '. $style4_margin_bottom .';}';
			}
			
			// Title background
			$style4_title_bg = uwl_option( 'style4_title_bg' );
			if ( '' != $style4_title_bg && '#f8f8f8' != $style4_title_bg ) {
				$css .= '.uwl_widget_wrap.style4 .uwl-title {background-color: '. $style4_title_bg .';}
						.uwl_widget_wrap.style4.uwl_testimonials_widget .uwl-testimonial-nav {background-color: '. $style4_title_bg .';}';
			}

			// Title color
			$style4_title_color = uwl_option( 'style4_title_color' );
			if ( '' != $style4_title_color && '#666' != $style4_title_color ) {
				$css .= '.uwl_widget_wrap.style4 .uwl-title {color: '. $style4_title_color .';}';
			}

			// Title font size
			$style4_title_font_size = uwl_option( 'style4_title_font_size' );
			if ( '' != $style4_title_font_size && '14px' != $style4_title_font_size ) {
				$css .= '.uwl_widget_wrap.style4 .uwl-title {font-size: '. $style4_title_font_size .';}';
			}

			// Title border color
			$style4_title_border_color = uwl_option( 'style4_title_border_color' );
			if ( '' != $style4_title_border_color && '#eaeaea' != $style4_title_border_color ) {
				$css .= '.uwl_widget_wrap.style4 .uwl-title {border-color: '. $style4_title_border_color .';}';
			}

			// Title border right color
			$style4_title_border_right_color = uwl_option( 'style4_title_border_right_color' );
			if ( '' != $style4_title_border_right_color && '#eee' != $style4_title_border_right_color ) {
				$css .= '.uwl_widget_wrap.style4 .uwl-title span::after {background-color: '. $style4_title_border_right_color .';}';
			}

			/**
			   Widget Style 10
			**/
			// Margin bottom
			$style10_margin_bottom = uwl_option( 'style10_margin_bottom' );
			if ( '' != $style10_margin_bottom && '50px' != $style10_margin_bottom ) {
				$css .= '.uwl-style10, .uwl_tabs_widget {margin-bottom: '. $style10_margin_bottom .';}';
			}
			
			// Title color
			$style10_title_color = uwl_option( 'style10_title_color' );
			if ( '' != $style10_title_color && '#333' != $style10_title_color ) {
				$css .= '.uwl_widget_wrap.style10 .uwl-title {color: '. $style10_title_color .';}';
			}
			
			// Widget hover title color
			$style10_widget_hover_title_color = uwl_option( 'style10_widget_hover_title_color' );
			if ( '' != $style10_widget_hover_title_color && '#4dbefa' != $style10_widget_hover_title_color ) {
				$css .= '.uwl_widget_wrap.style10:hover .uwl-title {color: '. $style10_widget_hover_title_color .';}';
			}

			// Title font size
			$style10_title_font_size = uwl_option( 'style10_title_font_size' );
			if ( '' != $style10_title_font_size && '12px' != $style10_title_font_size ) {
				$css .= '.uwl_widget_wrap.style10 .uwl-title {font-size: '. $style10_title_font_size .';}';
			}

			// Round border color
			$style10_round_border_color = uwl_option( 'style10_round_border_color' );
			if ( '' != $style10_round_border_color && '#4dbefa' != $style10_round_border_color ) {
				$css .= '.uwl_widget_wrap.style10 .uwl-title:before {border-color: '. $style10_round_border_color .';}';
			}

			// Widget hover round background color
			$style10_round_bg = uwl_option( 'style10_round_bg' );
			if ( '' != $style10_round_bg && '#4dbefa' != $style10_round_bg ) {
				$css .= '.uwl_widget_wrap.style10:hover .uwl-title:before {background-color: '. $style10_round_bg .';}';
			}

			// Title margin bottom
			$style10_title_margin_bottom = uwl_option( 'style10_title_margin_bottom' );
			if ( '' != $style10_title_margin_bottom && '20px' != $style10_title_margin_bottom ) {
				$css .= '.uwl_widget_wrap.style10 .uwl-title {margin-bottom: '. $style10_title_margin_bottom .';}';
			}

			/**
			   Links Color
			**/
			$links_color = uwl_option( 'custom_links_color' );
			if ( '' != $links_color && '#333' != $links_color ) {
				$css .= 'body .uwl_widget_wrap a {color: '. $links_color .';}';
			}

			// Links hover Color
			$links_hover_color = uwl_option( 'custom_links_hover_color' );
			if ( '' != $links_hover_color && '#4dbefa' != $links_hover_color ) {
				$css .= 'body .uwl_widget_wrap a:hover {color: '. $links_hover_color .';}';
			}

			/**
			   Inputs Styles
			**/
			// Input background
			$input_bg = uwl_option( 'input_bg' );
			$input_bg_regular = $input_bg['regular'];
			if ( '' != $input_bg_regular && '#fff' != $input_bg_regular ) {
				$css .= '.uwl_widget_wrap input[type="text"], .uwl_widget_wrap input[type="password"], .uwl_widget_wrap input[type="email"], .uwl_widget_wrap input[type="search"] {background-color: '. $input_bg_regular .';}';
			}
			$input_bg_hover = $input_bg['hover'];
			if ( '' != $input_bg_hover && '#fff' != $input_bg_hover ) {
				$css .= '.uwl_widget_wrap input[type="text"]:hover, .uwl_widget_wrap input[type="password"]:hover, .uwl_widget_wrap input[type="email"]:hover, .uwl_widget_wrap input[type="search"]:hover {background-color: '. $input_bg_hover .';}';
			}
			$input_bg_active = $input_bg['active'];
			if ( '' != $input_bg_active && '#fff' != $input_bg_active ) {
				$css .= '.uwl_widget_wrap input[type="text"]:focus, .uwl_widget_wrap input[type="password"]:focus, .uwl_widget_wrap input[type="email"]:focus, .uwl_widget_wrap input[type="search"]:focus {background-color: '. $input_bg_active .';}';
			}

			// Input color
			$input_color = uwl_option( 'input_color' );
			if ( '' != $input_color && '#888' != $input_color ) {
				$css .= '.uwl_widget_wrap input[type="text"], .uwl_widget_wrap input[type="password"], .uwl_widget_wrap input[type="email"], .uwl_widget_wrap input[type="search"] {color: '. $input_color .';}';
			}

			// Input border color
			$input_border = uwl_option( 'input_border' );
			$input_border_regular = $input_border['regular'];
			if ( '' != $input_border_regular && '#e0e0e0' != $input_border_regular ) {
				$css .= '.uwl_widget_wrap input[type="text"], .uwl_widget_wrap input[type="password"], .uwl_widget_wrap input[type="email"], .uwl_widget_wrap input[type="search"], .uwl_login_widget .input-append .show-pass {border-color: '. $input_border_regular .';}';
			}
			$input_border_hover = $input_border['hover'];
			if ( '' != $input_border_hover && '#c1c1c1' != $input_border_hover ) {
				$css .= '.uwl_widget_wrap input[type="text"]:hover, .uwl_widget_wrap input[type="password"]:hover, .uwl_widget_wrap input[type="email"]:hover, .uwl_widget_wrap input[type="search"]:hover {border-color: '. $input_border_hover .';}';
			}
			$input_border_active = $input_border['active'];
			if ( '' != $input_border_active && '#4dbefa' != $input_border_active ) {
				$css .= '.uwl_widget_wrap input[type="text"]:focus, .uwl_widget_wrap input[type="password"]:focus, .uwl_widget_wrap input[type="email"]:focus, .uwl_widget_wrap input[type="search"]:focus {border-color: '. $input_border_active .';}';
			}

			/**
			   Buttons Styles
			**/
			// Input submit background
			$input_submit_bg = uwl_option( 'input_submit_bg' );
			$input_submit_bg_regular = $input_submit_bg['regular'];
			if ( '' != $input_submit_bg_regular && '#4dbefa' != $input_submit_bg_regular ) {
				$css .= '.uwl_widget_wrap input[type="submit"] {background-color: '. $input_submit_bg_regular .';}';
			}
			$input_submit_bg_hover = $input_submit_bg['hover'];
			if ( '' != $input_submit_bg_hover ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:hover {background-color: '. $input_submit_bg_hover .';}';
			}
			$input_submit_bg_active = $input_submit_bg['active'];
			if ( '' != $input_submit_bg_active && '#4dbefa' != $input_submit_bg_active ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:active {background-color: '. $input_submit_bg_active .';}';
			}

			// Input submit color
			$input_submit_color = uwl_option( 'input_submit_color' );
			$input_submit_color_regular = $input_submit_color['regular'];
			if ( '' != $input_submit_color_regular && '#fff' != $input_submit_color_regular ) {
				$css .= '.uwl_widget_wrap input[type="submit"] {color: '. $input_submit_color_regular .';}';
			}
			$input_submit_color_hover = $input_submit_color['hover'];
			if ( '' != $input_submit_color_hover && '#4dbefa' != $input_submit_color_hover ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:hover {color: '. $input_submit_color_hover .';}';
			}
			$input_submit_color_active = $input_submit_color['active'];
			if ( '' != $input_submit_color_active && '#4dbefa' != $input_submit_color_active ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:active {color: '. $input_submit_color_active .';}';
			}

			// Input submit border color
			$input_submit_border = uwl_option( 'input_submit_border' );
			$input_submit_border_regular = $input_submit_border['regular'];
			if ( '' != $input_submit_border_regular && '#4dbefa' != $input_submit_border_regular ) {
				$css .= '.uwl_widget_wrap input[type="submit"] {border-color: '. $input_submit_border_regular .';}';
			}
			$input_submit_border_hover = $input_submit_border['hover'];
			if ( '' != $input_submit_border_hover && '#4dbefa' != $input_submit_border_hover ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:hover {border-color: '. $input_submit_border_hover .';}';
			}
			$input_submit_border_active = $input_submit_border['active'];
			if ( '' != $input_submit_border_active && '#4dbefa' != $input_submit_border_active ) {
				$css .= '.uwl_widget_wrap input[type="submit"]:active {border-color: '. $input_submit_border_active .';}';
			}
			
			/**
			   Contact Info Widget
			**/
			// Default style icons color
			$default_style_icons_color = uwl_option( 'default_style_icons_color' );
			if ( '' != $default_style_icons_color && '#01aef0' != $default_style_icons_color ) {
				$css .= '.uwl-contact-info-container.default i {color: '. $default_style_icons_color .';}';
			}

			// Default style icons border color
			$default_style_icons_border_color = uwl_option( 'default_style_icons_border_color' );
			if ( '' != $default_style_icons_border_color && '#e2e2e2' != $default_style_icons_border_color ) {
				$css .= '.uwl-contact-info-container.default i {border-color: '. $default_style_icons_border_color .';}';
			}

			// Default style titles color
			$default_style_titles_color = uwl_option( 'default_style_titles_color' );
			if ( '' != $default_style_titles_color && '#777' != $default_style_titles_color ) {
				$css .= '.uwl-contact-info-container.default span.uwl-contact-title {color: '. $default_style_titles_color .';}';
			}

			// Big icons style icons background
			$big_icons_style_icons_bg = uwl_option( 'big_icons_style_icons_bg' );
			$big_icons_style_icons_bg_regular = $big_icons_style_icons_bg['regular'];
			if ( '' != $big_icons_style_icons_bg_regular ) {
				$css .= '.uwl-contact-info-container.big-icons i {background-color: '. $big_icons_style_icons_bg_regular .';}';
			}
			$big_icons_style_icons_bg_hover = $big_icons_style_icons_bg['hover'];
			if ( '' != $big_icons_style_icons_bg_hover && '#01aef0' != $big_icons_style_icons_bg_hover ) {
				$css .= '.uwl-contact-info-container.big-icons li:hover i {background-color: '. $big_icons_style_icons_bg_hover .';}';
			}
			$big_icons_style_icons_bg_active = $big_icons_style_icons_bg['active'];
			if ( '' != $big_icons_style_icons_bg_active ) {
				$css .= '.uwl-contact-info-container.big-icons li:active i {background-color: '. $big_icons_style_icons_bg_active .';}';
			}

			// Big icons style icons color
			$big_icons_style_icons_color = uwl_option( 'big_icons_style_icons_color' );
			$big_icons_style_icons_color_regular = $big_icons_style_icons_color['regular'];
			if ( '' != $big_icons_style_icons_color_regular && '#01aef0' != $big_icons_style_icons_color_regular ) {
				$css .= '.uwl-contact-info-container.big-icons i {color: '. $big_icons_style_icons_color_regular .';}';
			}
			$big_icons_style_icons_color_hover = $big_icons_style_icons_color['hover'];
			if ( '' != $big_icons_style_icons_color_hover && '#fff' != $big_icons_style_icons_color_hover ) {
				$css .= '.uwl-contact-info-container.big-icons li:hover i {color: '. $big_icons_style_icons_color_hover .';}';
			}
			$big_icons_style_icons_color_active = $big_icons_style_icons_color['active'];
			if ( '' != $big_icons_style_icons_color_active ) {
				$css .= '.uwl-contact-info-container.big-icons li:active i {color: '. $big_icons_style_icons_color_active .';}';
			}

			// Big icons style icons border color
			$big_icons_style_icons_border_color = uwl_option( 'big_icons_style_icons_border_color' );
			$big_icons_style_icons_border_color_regular = $big_icons_style_icons_border_color['regular'];
			if ( '' != $big_icons_style_icons_border_color_regular && '#01aef0' != $big_icons_style_icons_border_color_regular ) {
				$css .= '.uwl-contact-info-container.big-icons i {border-color: '. $big_icons_style_icons_border_color_regular .';}';
			}
			$big_icons_style_icons_border_color_hover = $big_icons_style_icons_border_color['hover'];
			if ( '' != $big_icons_style_icons_border_color_hover && '#01aef0' != $big_icons_style_icons_border_color_hover ) {
				$css .= '.uwl-contact-info-container.big-icons li:hover i {border-color: '. $big_icons_style_icons_border_color_hover .';}';
			}
			$big_icons_style_icons_border_color_active = $big_icons_style_icons_border_color['active'];
			if ( '' != $big_icons_style_icons_border_color_active ) {
				$css .= '.uwl-contact-info-container.big-icons li:active i {border-color: '. $big_icons_style_icons_border_color_active .';}';
			}

			// Big icons style titles color
			$big_icons_style_titles_color = uwl_option( 'big_icons_style_titles_color' );
			if ( '' != $big_icons_style_titles_color ) {
				$css .= '.uwl-contact-info-container.big-icons span.uwl-contact-title {color: '. $big_icons_style_titles_color .';}';
			}

			// Skype background
			$contact_info_skype_bg = uwl_option( 'contact_info_skype_bg' );
			$contact_info_skype_bg_regular = $contact_info_skype_bg['regular'];
			if ( '' != $contact_info_skype_bg_regular && '#00AFF0' != $contact_info_skype_bg_regular ) {
				$css .= '.uwl-contact-info-container li.skype a {background-color: '. $contact_info_skype_bg_regular .';}';
			}
			$contact_info_skype_bg_hover = $contact_info_skype_bg['hover'];
			if ( '' != $contact_info_skype_bg_hover && '#333' != $contact_info_skype_bg_hover ) {
				$css .= '.uwl-contact-info-container li.skype a:hover {background-color: '. $contact_info_skype_bg_hover .';}';
			}
			$contact_info_skype_bg_active = $contact_info_skype_bg['active'];
			if ( '' != $contact_info_skype_bg_active ) {
				$css .= '.uwl-contact-info-container li.skype a:active {background-color: '. $contact_info_skype_bg_active .';}';
			}

			// Skype color
			$contact_info_skype_color = uwl_option( 'contact_info_skype_color' );
			$contact_info_skype_color_regular = $contact_info_skype_color['regular'];
			if ( '' != $contact_info_skype_color_regular && '#fff' != $contact_info_skype_color_regular ) {
				$css .= '.uwl-contact-info-container li.skype a {color: '. $contact_info_skype_color_regular .'!important;}';
			}
			$contact_info_skype_color_hover = $contact_info_skype_color['hover'];
			if ( '' != $contact_info_skype_color_hover && '#fff' != $contact_info_skype_color_hover ) {
				$css .= '.uwl-contact-info-container li.skype a:hover {color: '. $contact_info_skype_color_hover .'!important;}';
			}
			$contact_info_skype_color_active = $contact_info_skype_color['active'];
			if ( '' != $contact_info_skype_color_active ) {
				$css .= '.uwl-contact-info-container li.skype a:active {color: '. $contact_info_skype_color_active .'!important;}';
			}

			/**
			   MailChimp Widget
			**/
			// MailChimp Border Color
			$mailchimp_style2_border_color = uwl_option( 'mailchimp_style2_border_color' );
			if ( '' != $mailchimp_style2_border_color && '#1a1f24' != $mailchimp_style2_border_color ) {
				$css .= '.uwl-mailchimp.style-two input[type="email"], .uwl-mailchimp.style-two span {border-color: '. $mailchimp_style2_border_color .';}';
			}
			$mailchimp_style2_border_color_hover = uwl_option( 'mailchimp_style2_border_color_hover' );
			if ( '' != $mailchimp_style2_border_color_hover ) {
				$css .= '.uwl-mailchimp.style-two input[type="email"]:hover {border-color: '. $mailchimp_style2_border_color_hover .';}';
			}
			$mailchimp_style2_border_color_active = uwl_option( 'mailchimp_style2_border_color_focus' );
			if ( '' != $mailchimp_style2_border_color_active && '#4dbefa' != $mailchimp_style2_border_color_active ) {
				$css .= '.uwl-mailchimp.style-two input[type="email"]:focus {border-color: '. $mailchimp_style2_border_color_active .';}';
			}

			// MailChimp Button Color
			$mailchimp_style2_button_color = uwl_option( 'mailchimp_style2_button_color' );
			if ( '' != $mailchimp_style2_button_color && '#1a1f24' != $mailchimp_style2_button_color ) {
				$css .= '.uwl-mailchimp.style-two button {color: '. $mailchimp_style2_button_color .';}';
			}
			$mailchimp_style2_button_color_hover = uwl_option( 'mailchimp_style2_button_color_hover' );
			if ( '' != $mailchimp_style2_button_color_hover ) {
				$css .= '.uwl-mailchimp.style-two button:hover {color: '. $mailchimp_style2_button_color_hover .';}';
			}
			$mailchimp_style2_button_color_active = uwl_option( 'mailchimp_style2_button_color_focus' );
			if ( '' != $mailchimp_style2_button_color_active && '#4dbefa' != $mailchimp_style2_button_color_active ) {
				$css .= '.uwl-mailchimp.style-two button:active {color: '. $mailchimp_style2_button_color_active .';}';
			}

			/**
			   Menu Widget
			**/
			// Menu link border bottom color
			$links_border_bottom = uwl_option( 'links_border_bottom' );
			if ( '' != $links_border_bottom && '#e0e0e0' != $links_border_bottom ) {
				$css .= 'ul.uwl-menu li {border-color: '. $links_border_bottom .';}';
			}

			// Sub icon color
			$sub_icon_color = uwl_option( 'sub_icon_color' );
			$sub_icon_color_regular = $sub_icon_color['regular'];
			if ( '' != $sub_icon_color_regular && '#aaa' != $sub_icon_color_regular ) {
				$css .= '.uwl-menu li .uwl-sub-icon {color: '. $sub_icon_color_regular .';}';
			}
			$sub_icon_color_hover = $sub_icon_color['hover'];
			if ( '' != $sub_icon_color_hover && '#4dbefa' != $sub_icon_color_hover ) {
				$css .= '.uwl-menu li .uwl-sub-icon:hover {color: '. $sub_icon_color_hover .';}';
			}
			$sub_icon_color_active = $sub_icon_color['active'];
			if ( '' != $sub_icon_color_active ) {
				$css .= '.uwl-menu li .uwl-sub-icon:active {color: '. $sub_icon_color_active .';}';
			}

			/**
			   Output css on front end
			**/
			if ( ! empty( $css ) ) {
				return $css;
			}

		}

		/**
		 * Custom CSS
		 */
		public function uwl_custom_css() {

			$css = uwl_option( 'custom_css' );
			if ( ! empty( $css ) ) {
				return $css;
			}

		}

		/**
		 * Custom CSS
		 */
		public function generate( $output ) {

			// Set output Var
			$css = '';
			$css .= $this->uwl_style_widgets();
			$css .= $this->uwl_custom_css();

			// Return CSS
			if ( ! empty( $css ) ) {
				$output .= $css;
			}

			// Return output css
			return $output;

		}

	}

}
new UWL_Custom_CSS();