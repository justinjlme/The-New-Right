<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://themeisle.com/plugins/feedzy-rss-feed-pro/
 * @since      1.0.0
 *
 * @package    feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/includes/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/includes/admin
 * @author     Bogdan Preda <bogdan.preda@themeisle.com>
 */

/**
 * Class Feedzy_Rss_Feed_Pro_Admin
 */
class Feedzy_Rss_Feeds_Pro_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @access      public
	 *
	 * @param       string $plugin_name The name of this plugin.
	 * @param       string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since       1.0.0
	 * @access      public
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, FEEDZY_PRO_ABSURL . 'css/feedzy-rss-feed-pro.css', array(), $this->version, 'all' );
	}

	/**
	 * The custom plugin_row_meta function
	 * Adds additional links on the plugins page for this plugin
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   array  $links The array having default links for the plugin.
	 * @param   string $file The name of the plugin file.
	 *
	 * @return  array
	 */
	public function feedzy_filter_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'feedzy-rss-feed-pro.php' ) !== false ) {
			$new_links = array(
				'doc'          => '<a href="http://docs.themeisle.com/article/277-feedzy-rss-feeds-hooks" target="_blank" title="' . __( 'Documentation and examples', 'feedzy-rss-feeds' ) . '">' . __( 'Documentation and examples', 'feedzy-rss-feeds' ) . '</a>',
				'more_plugins' => '<a href="http://themeisle.com/wordpress-plugins/" target="_blank" title="' . __( 'More Plugins', 'feedzy-rss-feeds' ) . '">' . __( 'More Plugins', 'feedzy-rss-feeds' ) . '</a>',
			);
			$links     = array_merge( $links, $new_links );
		}

		return $links;
	}

	/**
	 * Register required plugins default image for Feedzy with PRO version
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function register_required_plugins() {
		$plugins = array(
			array(
				'name'     => 'Feedzy RSS Feeds Lite',
				'slug'     => 'feedzy-rss-feeds',
				'required' => true,
			),
		);
		$config  = array(
			'id'           => 'feedzy-rss-feeds-pro',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',
			// Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'parent_slug'  => 'plugins.php',
			// Parent menu slug.
			'capability'   => 'manage_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => 'Required',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
		);
		tgmpa( $plugins, $config );
	}

	/**
	 * Replace default image for Feedzy with PRO version
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string $imageSrc The array having default links for the plugin.
	 *
	 * @return  string
	 */
	public function feedzy_pro_define_default_image( $imageSrc ) {
		$defaultImg = FEEDZY_PRO_ABSURL . '/img/feedzy_pro.jpg';

		return $defaultImg;
	}

	/**
	 * Returns the attributes of the shortcode for the PRO version
	 * Overrides the Lite method
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   array $atts The attributes passed by WordPress.
	 *
	 * @return array
	 */
	public function feedzy_pro_get_short_code_attributes( $atts ) {
		// Retrieve & extract shorcode parameters
		$sc = shortcode_atts( array(
			'price'        => '',          // yes, no, auto (if price is shown)
			'referral_url' => '',   // the referral variables
			'keywords_ban' => '',   // the keywords exclude var
			'columns'      => '1',       // the columns number
			'template'     => '',       // the template name
		), $atts, 'feedzy_default' );

		return $sc;
	}

	/**
	 * Add grid class to item
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   array $classes The feed item classes.
	 * @param   array $sc The shortcode attributes.
	 *
	 * @return string
	 */
	public function feedzy_filter_add_grid_class( $classes = '', $sc = '' ) {
		$classes[] = 'feedzy-rss-col-' . $sc['columns'];

		return $classes;
	}

	/**
	 * Check title for banned keywords
	 *
	 * @since   1.0.2
	 * @access  public
	 *
	 * @param   boolean $continue A boolean to stop the script.
	 * @param   array   $sc The shortcode attrs.
	 * @param   object  $item The feed item.
	 * @param   string  $feedURL The feed URL.
	 *
	 * @return  boolean
	 */
	public function item_keywords_ban( $continue, $sc, $item, $feedURL ) {
		$keywords_ban = $sc['keywords_ban'];
		if ( ! empty( $keywords_ban ) ) {
			foreach ( $keywords_ban as $keyword ) {
				if ( strpos( $item->get_title(), $keyword ) !== false || strpos( $item->get_content(), $keyword ) !== false ) {
					$continue = false;
				}
			}
		}

		return $continue;
	}

	/**
	 * Add attributes to $itemArray.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   array  $itemArray The item attributes array.
	 * @param   object $item The feed item.
	 *
	 * @return mixed
	 */
	public function feedzy_pro_add_data_to_item( $itemArray, $item ) {
		$price                   = $this->retrive_price( $item );
		$price                   = apply_filters( 'feedzy_price_output', $price );
		$itemArray['item_price'] = $price;

		return $itemArray;
	}

	/**
	 * Retrive the price from feed
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   object $item The feed item.
	 *
	 * @return string
	 */
	private function retrive_price( $item ) {
		$thePrice = '';
		if ( empty( $thePrice ) ) {
			$data = $item->get_item_tags( '', 'price' );
			if ( isset( $data[0]['data'] ) && ! empty( $data[0]['data'] ) ) {
				$thePrice = $data[0]['data'];
			}
		}
		if ( empty( $thePrice ) ) {
			$data = $item->get_item_tags( 'http://base.google.com/ns/1.0', 'price' );
			if ( isset( $data[0]['data'] ) && ! empty( $data[0]['data'] ) ) {
				$thePrice = $data[0]['data'];
			}
		}

		return $thePrice;
	}

	/**
	 * Append referral params if the option is set.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string $itemLink The item url.
	 * @param   array  $sc The shortcode attributes array.
	 *
	 * @return string
	 */
	public function feedzy_pro_referral_url( $itemLink, $sc ) {
		$newLink = $itemLink;
		if ( isset( $sc['referral_url'] ) && $sc['referral_url'] != '' ) {
			$parseUrl = parse_url( $itemLink );
			if ( isset( $parseUrl['query'] ) ) {
				$newLink = $itemLink . '&' . $sc['referral_url'];
			} else {
				$newLink = $itemLink . '?' . $sc['referral_url'];
			}
		}

		return $newLink;
	}

	/**
	 * Render the content to be displayed for the PRO version
	 * Takes into account the PRO shortcode attributes
	 * Overrides the Lite method
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string $content The original content.
	 * @param   array  $sc The shorcode attributes array.
	 * @param   array  $feed_title The feed title array.
	 * @param   array  $feed_items The feed items array.
	 *
	 * @return string
	 */
	public function feedzy_pro_render_content( $content, $sc, $feed_title, $feed_items ) {
		$template_name = 'default';
		if ( isset( $sc['template'] ) && $sc['template'] != '' ) {
			$template_name = $sc['template'];
		}
		if ( $this->check_template_file_exists( $template_name ) ) {
			ob_start();
			include $this->get_template( $template_name );
			$content = ob_get_clean();

			return $content;
		} else {
			return $content;
		}
	}

	/**
	 * Checks if file exists in templates.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   string $fileName The name of the file to check in templates (defaults to default).
	 *
	 * @return mixed
	 */
	private function check_template_file_exists( $fileName = 'default' ) {
		$userTemplate = get_template_directory() . '/feedzy_templates/' . $fileName . '.php';
		$filePath     = FEEDZY_PRO_ABSPATH . '/templates/' . $fileName . '.php';
		$defaultPath  = FEEDZY_PRO_ABSPATH . '/templates/default.php';
		if ( file_exists( $userTemplate ) ) {
			return $userTemplate;
		}
		if ( file_exists( $filePath ) ) {
			return $filePath;
		}
		if ( file_exists( $defaultPath ) ) {
			return $defaultPath;
		}

		return false;
	}

	/**
	 * Get the template content
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   string $fileName The name of the file to check in templates (defaults to default).
	 *
	 * @return string
	 */
	private function get_template( $fileName = 'default' ) {
		if ( $this->check_template_file_exists( $fileName ) != false ) {
			return $this->check_template_file_exists( $fileName );
		}

		return FEEDZY_PRO_ABSPATH . '/templates/default.php';
	}

}
