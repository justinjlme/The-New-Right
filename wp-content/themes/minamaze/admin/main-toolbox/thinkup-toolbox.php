<?php

function minamaze_thinkup_setup() {    

	/**
	 * About page class
	 */
	require_once get_template_directory() . '/admin/main-toolbox/thinkup-toolbox-class-about.php';

	/*
	* About page instance
	*/

	// Get theme data
	$theme_data     = wp_get_theme();

	// Get name of parent theme
	if ( is_child_theme() ) {
		$theme_name    = trim( strtolower( str_replace( ' (Lite)', '', $theme_data->parent()->get( 'Name' ) ) ) );
		$theme_slug    = trim( strtolower( str_replace( ' (Lite)', '-lite', $theme_data->parent()->get( 'Name' ) ) ) );
		$theme_version = $theme_data->parent()->get( 'Version' );
	} else {
		$theme_name    = trim( strtolower( str_replace( ' (Lite)', '', $theme_data->get( 'Name' ) ) ) );
		$theme_slug    = trim( strtolower( str_replace( ' (Lite)', '-lite', $theme_data->get( 'Name' ) ) ) );
		$theme_version = $theme_data->get( 'Version' );
	}

	$config = array(
		// Menu name under Appearance.
		'menu_name'             => sprintf( __( 'About %1$s (Free)', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
		// Page title.
		'page_name'             => sprintf( __( 'About %1$s (Free)', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
		// Main welcome title
		'welcome_title'         => sprintf( __( 'Welcome to %1$s! - v', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
		// Main welcome content
		'welcome_content'       => sprintf( esc_html__(  '%1$s is a free multi-purpose WordPress theme. It\'s fully responsive and perfect for any type of website, it\'s great web agency businesses, corporate websites , personal, blogs, photography and everything in between! Create a stunning homepage with featured homepage sections and a beautiful slideshow in seconds.', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
		/**
		 * Tabs array.
		 *
		 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
		 * the will be the name of the function which will be used to render the tab content.
		 */
		'upgrade'             => array(
			'upgrade_url'     => '//www.thinkupthemes.com/themes/' . $theme_name . '/',
			'price_discount'  => __( 'Upgrade for $31 (10% off)', 'lan-thinkupthemes' ),
			'price_normal'	  => __( 'Normally $35. Use coupon at checkout.', 'lan-thinkupthemes' ),
			'coupon'	      => $theme_name . '31',
			'button'	      => __( 'Upgrade Now', 'lan-thinkupthemes' ),
		),
		'tabs'                 => array(
			'getting_started'  => __( 'Getting Started', 'lan-thinkupthemes' ),
			'support_content'  => __( 'Support', 'lan-thinkupthemes' ),
			'free_pro'         => __( 'Free VS PRO', 'lan-thinkupthemes' ),
		),
		// Getting started tab
		'getting_started' => array(
			'first' => array (
				'title'               => esc_html__( 'Setup Slideshow','lan-thinkupthemes' ),
				'text'                => sprintf( esc_html__( 'Add a slider to your site in seconds. Go to the Customizer -> Theme Options -> Homepage and choose Image Slider. Add up to 3 slides quickly!','lan-thinkupthemes' ) . '<br /><br />' . esc_html__( 'Want more slides? With %1$s Pro you can add as many slides as you want! Plus add video slides, image only slides and so much more!', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_label'        => sprintf( esc_html__( 'View %1$s Pro','lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_link'         => esc_url( '//www.thinkupthemes.com/themes/' . $theme_name . '/' ),
				'is_button'           => false,
				'recommended_actions' => false,
                'is_new_tab'          => true,
			),
			'second' => array(
				'title'               => esc_html__( 'Setup Homepage','lan-thinkupthemes' ),
				'text'                => sprintf( esc_html__( 'Create a homepage fast. Go to the Customizer -> Theme Options -> Homepage (Featured) to start adding content. Add up to 3 sections fast!','lan-thinkupthemes' ) . '<br /><br />' . esc_html__( 'Fully customize your homepage with %1$s Pro. Add as many sections as you want, change the button text, disable cropping. Plus much more!', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_label'        => sprintf( esc_html__( 'View %1$s Pro', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_link'         => esc_url( '//www.thinkupthemes.com/themes/' . $theme_name . '/' ),
				'is_button'           => false,
				'recommended_actions' => false,
                'is_new_tab'          => true,
			),
			'third' => array(
				'title'               => esc_html__( 'Go to Customizer','lan-thinkupthemes' ),
				'text'                => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.','lan-thinkupthemes' ),
				'button_label'        => esc_html__( 'Go to Customizer','lan-thinkupthemes' ),
				'button_link'         => esc_url( admin_url( 'customize.php' ) ),
				'is_button'           => true,
				'recommended_actions' => false,
                'is_new_tab'          => true,
			)
		),
		// Support content tab.
		'support_content'      => array(
			'first' => array (
				'title'        => esc_html__( 'Free Support','lan-thinkupthemes' ),
				'icon'         => 'dashicons dashicons-sos',
				'text'         => esc_html__( 'Get free support from the amazing volunteers over at the wordpress forums. This support is provided by volunteers not Think Up Themes staff.','lan-thinkupthemes' ),
				'button_label' => esc_html__( 'Contact Free Support','lan-thinkupthemes' ),
				'button_link'  => esc_url( '//wordpress.org/support/theme/' . $theme_slug . '/' ),
				'is_button'    => false,
				'is_new_tab'   => true,
			),
			'second' => array(
				'title'        => esc_html__( 'Changelog','lan-thinkupthemes' ),
				'icon'         => 'dashicons dashicons-portfolio',
				'text'         => esc_html__( 'Want to know what\'s been happing with the latest changes? Take a look at the changelog. Your can find this in the themes readme.txt file.','lan-thinkupthemes' ),
				'button_label' => '',
				'button_link'  => '',
				'is_button'    => false,
				'is_new_tab'   => false,
			),
			'third' => array(
				'title'        => esc_html__( 'Premium Support','lan-thinkupthemes' ),
				'icon'         => 'dashicons dashicons-book-alt',
				'text'         => sprintf( esc_html__( 'Get support from the brains behind %1$s. Upgrade to %1$s Pro and you\'ll get help straight from the theme developers!', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ) . '<br /><br />' . sprintf( esc_html__( 'Get VIP access to the knowledge base, personal ticket support and much more so you can make the most out of %1$s!', 'lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_label' => sprintf( esc_html__( 'Get %1$s Pro','lan-thinkupthemes' ), ucfirst( $theme_name ) ),
				'button_link'  => '//www.thinkupthemes.com/themes/' . $theme_name . '/',
				'is_button'    => true,
				'is_new_tab'   => true,
			),
		),
		// Free vs pro array.
		'free_pro'                => array(
			'free_theme_name'     => ucfirst( $theme_name ) . ' (free)',
			'pro_theme_name'      => ucfirst( $theme_name ) . ' PRO',
			'pro_theme_link'      => '//www.thinkupthemes.com/themes/' . $theme_name . '/',
			'get_pro_theme_label' => sprintf( __( 'Get %s Now!', 'lan-thinkupthemes' ), ucfirst( $theme_name ) . ' Pro' ),
			'features'            => array(
				array(
					'title'            => __( 'Mobile Friendly', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'true',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Background Image', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'true',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Fontpage Sections', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'true',
					'is_in_lite_text'  => '3',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => 'Unlimited',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Fontpage Slides', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'true',
					'is_in_lite_text'  => '3',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => 'Unlimited',
					'hidden'           => 'true',
				),
				array(
					'title'            => __( 'Built-In Social Buttons', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => '',
					'is_in_lite_text'  => '7',
					'is_in_pro'        => '',
					'is_in_pro_text'   => 'Unlimited',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Advanced Theme Options', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => '',
					'is_in_lite_text'  => 'Basic',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Page Builder', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Theme Options Panel', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Unlimited Color Options', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( '600+ Google Fonts', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( '150+ Shortcodes', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'ThinkUpSlider', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => 'true',
				),
				array(
					'title'            => __( 'Unlimited Sliders', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Video Sliders', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Portfolio', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Google Map Section', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Custom Widgets', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Parallax Effects', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Animation Effects', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Extended Layout Options', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'Premium Support', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
				array(
					'title'            => __( 'No credit footer link', 'lan-thinkupthemes' ),
					'description'      => '',
					'is_in_lite'       => 'false',
					'is_in_lite_text'  => '',
					'is_in_pro'        => 'true',
					'is_in_pro_text'   => '',
					'hidden'           => '',
				),
			),
		),
	);
	minamaze_thinkup_about_page::init( $config );

}

add_action('after_setup_theme', 'minamaze_thinkup_setup');

?>