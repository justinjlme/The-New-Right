<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://themeisle.com/plugins/feedzy-rss-feed-pro/
 * @since      1.0.0
 *
 * @package    feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/includes
 * @author     Bogdan Preda <bogdan.preda@themeisle.com>
 */
class Feedzy_Rss_Feeds_Pro {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Feedzy_Rss_Feed_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		$this->plugin_name = 'feedzy-rss-feeds-pro';
		$this->version = '1.1.2';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Feedzy_Rss_Feed_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Feedzy_Rss_Feed_Pro_i18n. Defines internationalization functionality.
	 * - Feedzy_Rss_Feed_Pro_Admin. Defines all hooks for the admin area.
	 * - Feedzy_Rss_Feed_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */

		$this->loader = new Feedzy_Rss_Feeds_Pro_Loader();
		include_once FEEDZY_PRO_ABSPATH . '/vendor/class-tgm-plugin-activation.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		$plugin_i18n = new Feedzy_Rss_Feeds_Pro_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_ui = new Feedzy_Rss_Feeds_Pro_Ui( $this->get_plugin_name(), $this->get_version(), $this->loader );
		$this->loader->add_filter( 'feedzy_rss_feeds_ui_lang_filter', $plugin_ui, 'feedzy_add_tinymce_lang' );
		$this->loader->add_filter( 'feedzy_get_form_elements_filter', $plugin_ui, 'get_form_elements_pro' );

		$plugin_admin = new Feedzy_Rss_Feeds_Pro_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'feedzy_define_default_image_filter', $plugin_admin, 'feedzy_pro_define_default_image' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin ,'feedzy_filter_plugin_row_meta', 10, 2 );

		$this->loader->add_filter( 'feedzy_add_classes_item', $plugin_admin ,'feedzy_filter_add_grid_class', 10, 2 );
		$this->loader->add_filter( 'feedzy_item_keyword', $plugin_admin, 'item_keywords_ban', 20, 4 );
		$this->loader->add_filter( 'feedzy_get_short_code_attributes_filter', $plugin_admin, 'feedzy_pro_get_short_code_attributes' );
		$this->loader->add_filter( 'feedzy_global_output', $plugin_admin, 'feedzy_pro_render_content', 10, 4 );
		$this->loader->add_filter( 'feedzy_item_url_filter', $plugin_admin, 'feedzy_pro_referral_url', 10, 2 );
		$this->loader->add_filter( 'feedzy_item_filter', $plugin_admin, 'feedzy_pro_add_data_to_item', 10, 2 );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'tgmpa_register', $plugin_admin, 'register_required_plugins' );
		$plugin_widget = new Feedzy_Rss_Feeds_Pro_Widget( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'feedzy_widget_form_filter', $plugin_widget ,'feedzy_pro_form_widget', 11, 3 );
		$this->loader->add_filter( 'feedzy_widget_update_filter', $plugin_widget ,'feedzy_pro_widget_update', 11, 3 );
		$this->loader->add_filter( 'feedzy_widget_shortcode_attributes_filter', $plugin_widget ,'feedzy_pro_widget_shortcode_attributes', 11, 3 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return  string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return  Feedzy_Rss_Feeds_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return  string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
