<?php
/*
 Plugin Name: WPeMatico Professional
 Description: Professional features for WPeMatico (Requires WPeMatico FREE activated)
 Plugin URI: https://etruel.com/downloads/wpematico-professional/
 Version: 1.6.2
 Author: etruel <esteban@netmdp.com>
 Author URI: https://etruel.com
 */

if ( ! function_exists( 'add_filter' ) )
	return;

// Plugin version
if(!defined( 'WPEMATICOPRO_VERSION' ) ) define( 'WPEMATICOPRO_VERSION', '1.6.2' );

if (is_admin()) {
	include_once( ABSPATH . basename(admin_url()) . '/includes/plugin.php' );
}
include( 'lib/pro_licenser.php' );

add_action( 'init', array( 'WPeMaticoPRO', 'init' ) );

add_filter('pro_check_campaigndata', array('NoNStatic', 'pro_check_campaigndata'), 10, 2);
add_filter('wpematico_before_update_campaign', array('NoNStatic', 'pro_update_campaign'), 10, 1);

register_activation_hook( plugin_basename( __FILE__ ), array( 'WPeMaticoPRO', 'activate' ) );
register_deactivation_hook( plugin_basename( __FILE__ ), array( 'WPeMaticoPRO', 'deactivate' ) );
register_uninstall_hook( plugin_basename( __FILE__ ), array( 'WPeMaticoPRO', 'uninstall' ) );

add_action('Wpematico_init_fetching', array( 'WPeMaticoPRO', 'init_fetching')); //hook for add actions and filter on niit fetching

if ( ! class_exists( 'WPeMaticoPRO' ) ) {

	class WPeMaticoPRO {
		const TEXTDOMAIN = 'WPeMaticoPRO';
		const OPTION_KEY = 'WPeMaticoPRO_Options';
		const RAMDOM_REWRITES_OPTION = 'WPeMaticoPRO_ramdom_rewrites';
		const STORE_URL = 'https://etruel.com';
		const AUTHOR = 'Esteban Truelsegaard';
		const NAME = 'WPeMatico PRO';
		
//		public static $version = WPEMATICOPRO_VERSION;
		public static $basen;		/** Plugin basename * @var string	 */
		public static $uri = '';
		public static $dir = '';		/** filesystem path to the plugin with trailing slash */
		public static $rssimg_add2img_featured_image = '';
		private $requirement;
		protected static $default_options = array(
			'enablemultifeed' => true,
			'enableimportfeed' => false,
			'enableauthorxfeed' => false,
			'enablecustomtitle' => false,
			'enablecfields' => false,
			'enableimgfilter' => false,
			'enableimgrename' => false,
			'enabletags' => false,
			'enablewcf' => false,
			'enablekwordf' => false,
			'enableeximport' => false,
			'enable_ramdom_words_rewrites' => false,
		);
		protected $options = array();

		public static function init() {
			self :: $uri = plugin_dir_url( __FILE__ );
			self :: $dir = plugin_dir_path( __FILE__ );
			self :: $basen = plugin_basename(__FILE__);

			new self( TRUE );
		}

		public function __construct( $hook_in = FALSE ) {
			$this->requirement = $this->meets_requirements();
			if ( !$this->requirement ) return;
//			$plugin_data = WPeMatico::plugin_get_version( __FILE__ );
//			self :: $version = $plugin_data['Version'];

			$this->load_options();
			require_once('includes/prosettings.php');
			require_once('includes/functions.php');
			require_once('includes/prohelps.php');
			
			$newcfg = get_option( 'WPeMatico_Options' );
			if(!$newcfg['nonstatic']) {
				$newcfg['nonstatic'] = true;
				update_option( 'WPeMatico_Options', $newcfg );
			}

			if ( $hook_in ) {
				//Additional links on the plugin page
				add_filter(	'plugin_row_meta',	array(	__CLASS__, 'init_row_meta'),10,2);
				add_filter(	'plugin_action_links_' . self :: $basen, array( __CLASS__,'init_action_links'));
				//Debug data in debug page
				add_filter(	'wpematico_sysinfo_after_wpematico_config', array( __CLASS__,'debug_data_cfg'), 5);
				
				if ($this->options['enableeximport']) {
					add_filter('post_row_actions' , array( 'NoNStatic', 'wpematico_quick_actions'), 30, 2);
					add_action('admin_action_wpematico_export_campaign', array('NoNStatic', 'wpematico_export_campaign'));
					//add_action('restrict_manage_posts', array( 'NoNStatic', 'import_in_views'), 9 );
					add_action('views_edit-wpematico', array( 'NoNStatic', 'import_in_views'), 9 );
					add_action( 'wpematico_import_campaign', array( 'NoNStatic', 'wpematico_import_campaign') );
					add_filter( 'bulk_actions-edit-wpematico', array('NoNStatic', 'bulk_actions_import_campaign') );
					add_filter( 'handle_bulk_actions-edit-wpematico', array('NoNStatic', 'bulk_action_handler_import_campaign'), 10, 3 );
				}

				if ($this->options['enableauthorxfeed']) {
					//add_action('nonstatic_feedat', array('NoNStatic', 'feedat'),10,2 );  //deprecated!!! 20160309 
					add_action('wpematico_campaign_feed_header_column', array('NoNStatic', 'headerfeedat') );
					add_action('wpematico_campaign_feed_body_column', array('NoNStatic', 'feedat'),10,3 );
				}
				if ($this->options['enablemultifeed']) {
					add_action('wpematico_campaign_feed_body_column', array('NoNStatic', 'is_multipage_icon'),97,3 );
					add_action('wpematico_campaign_feed_body_column', array('NoNStatic', 'advancedfeedicon'),99,3 );
					//add_action('nonstatic_feedat', array('NoNStatic', 'feedat'),10,2 );  //deprecated!!! 20160309 
					add_action('wpematico_campaign_feed_advanced_options', array('NoNStatic', 'multifeedfields'), 15, 4 );
					
					add_filter('wpematico_simplepie_url' , array( 'NoNStatic', 'multifeed_urls'), 30, 3);
					
				}
				if ($this->options['enableimportfeed']) {
					add_action('wpematico_campaign_feed_panel', array('NoNStatic', 'feedlist') );
					add_action('wpematico_campaign_feed_panel_buttons', array('NoNStatic', 'bimport') );
				}
				
				add_action('wpematico_permalinks_tools', array('NoNStatic', 'google_permalinks_option'),10,2 );

				add_action('wpematico_permalinks_tools', array(__CLASS__, 'add_no_follow_option'),11,2 );
				
				add_action('wpematico_before_template_box', array('NoNStatic', 'delete_from_phrase_box'),12,2 );
				add_action('wpematico_before_template_box', array('NoNStatic', 'last_html_tag'),15,2 );
			
				add_filter('wpematico_helptip_settings', 'wpematico_pro_helptips', 10, 1);
				add_filter('wpematico_help_settings', 'wpematico_pro_helptips', 10, 1);
				add_filter('wpematico_help_settings_rrewrites', 'wpematico_pro_help_settings_rrewrites', 10, 1);
				add_filter('wpematico_help_campaign', 'wpematico_pro_help_campaign', 10, 1);

				if ($this->options['enable_ramdom_words_rewrites']) {
					add_filter('wpematico_settings_tabs',  array( __CLASS__,'ramdom_rewrites'), 1, 1);
					add_action('wpematico_settings_tab_ramdom_rewrites', array(__CLASS__, 'ramdom_rewrites_form'));
					add_action('admin_post_save_wpe_pro_ramdom_rewrites', array(__CLASS__, 'save_ramdom_rewrites_form'));
					add_action('admin_init', array(__CLASS__, 'help_ramdom_rewrites_form'));
					
				
				}
				
			}
		}
		
		public static function ramdom_rewrites($tabs) {
			$tabs['ramdom_rewrites'] = '<img src="' . WPeMaticoPRO :: $uri.'images/administrator_16.png'.'" style="margin: 0pt 2px -2px 0pt;">'.__( 'Ramdom Rewrites', 'wpematico'); 
			return $tabs;
		}
		public static function default_ramdom_rewrites_options( $never_set = FALSE ) {
			$default_options = array(
				'words_to_rewrites' => '',
			);
			
			return $default_options;
		} 
		public static function help_ramdom_rewrites_form() {
			if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'wpematico_settings' ) && 
			( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wpematico' ) &&
			( isset( $_GET['tab'] ) && $_GET['tab'] == 'ramdom_rewrites' ) ) {

				$screen = WP_Screen::get('wpematico_page_wpematico_settings ');
				
				$helpcontent = apply_filters('wpematico_help_settings_rrewrites', '');
				
				$screen->add_help_tab( array(
					'id'	=> 'ramdomrewrites',
					'title'	=> 'Ramdom Rewrites',
					'content'=> $helpcontent,
				) );

			}
		}
		
		public static function ramdom_rewrites_form() {
			$ramdom_rewrites_options = get_option(self::RAMDOM_REWRITES_OPTION);
			$ramdom_rewrites_options = wp_parse_args($ramdom_rewrites_options, self::default_ramdom_rewrites_options( FALSE ) );
			?>
			<div class="wrap">
				
				<h3><?php _e( 'Ramdom Rewrites Settings', self :: TEXTDOMAIN);?></h3>			
				<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
					<?php wp_nonce_field('save_wpe_pro_ramdom_rewrites'); ?>
					<input type="hidden" name="action" value="save_wpe_pro_ramdom_rewrites"/>
					<table id="general-options" class="form-table">

						<b><label for="words_to_rewrites"><?php _e( 'Words to Rewrites:', self :: TEXTDOMAIN);?></label></b><br>
						<textarea style="width: 90%;min-height: 200px;" id="words_to_rewrites" name="words_to_rewrites"><?php echo $ramdom_rewrites_options['words_to_rewrites'];?></textarea><br>
						
						<?php _e( 'Enter a comma-separated list of words for rewrites use each line for different rewriting patterns.', self :: TEXTDOMAIN);?>

					</table>	
				<?php submit_button(); ?>				
				</form>
							
			</div>
		<?php
		}
		public static function save_ramdom_rewrites_form() {
			if ( ! wp_verify_nonce($_POST['_wpnonce'], 'save_wpe_pro_ramdom_rewrites' ) ) {
		    	wp_die(__( 'Security check', self :: TEXTDOMAIN )); 
			}
			update_option(self::RAMDOM_REWRITES_OPTION, $_POST);
			WPeMatico::add_wp_notice( array('text' => __('Settings saved.',  self :: TEXTDOMAIN), 'below-h2'=>false ) );
			wp_redirect($_POST['_wp_http_referer']);
			exit;

		}

		/**
		 * Filters to add to the fetch process (When runs a campaign)
		 */
		public static function init_fetching($campaign) {

			if (isset($campaign['activate_ramdom_rewrite']) && $campaign['activate_ramdom_rewrite']){
				add_filter('wpematico_pre_insert_post', array('NoNStatic', 'process_ramdom_rewrites'), 799, 2);
			}
			

			add_filter('wpematico_excludes', array('NoNStatic', 'exclfilters'), 10, 4);

			add_filter('wpematico_set_featured_img', array( __CLASS__,'rssimg_add2img_set_featured_image'), 10, 5);
			add_filter('wpematico_get_featured_img', array( __CLASS__,'rssimg_add2img_get_featured_image'), 10, 1);

			if($campaign['default_img']){
				//add_filter('wpematico_set_featured_img', array('NoNStatic','custom_img'), 10,5);
				add_filter('wpematico_allow_insertpost', array('NoNStatic','custom_img'),999 ,3 );
			}
			
			add_filter('wpematico_pre_insert_post', array('NoNStatic', 'strip_tags_title'),10,2 );
			if($campaign['add_no_follow']){
				add_filter('wpematico_pre_insert_post', array(__CLASS__, 'add_no_follow_links'),10,2 );
			}

			add_action('wpematico_inserted_post', array('NoNStatic', 'assign_custom_taxonomies'),10,2 );
			
			add_filter('Wpematico_end_fetching', array('NoNStatic', 'ending'),10,2 );
			
			if( isset($campaign['fix_google_links']) && $campaign['fix_google_links'] )
				add_filter('wpepro_full_permalink', array('NoNStatic', 'wpematico_googlenewslink'),10,1 );

			foreach ($campaign['campaign_feeds'] as $feed) {
				if( $campaign[$feed]['feed_author'] >= "0" ){
					add_filter('wpematico_get_author', array('NoNStatic', 'author'),10,4 );
					break; //add filter just one time.
				}
			}
			
			if( isset($campaign['strip_all_images']) && $campaign['strip_all_images'] ){
				add_filter('wpematico_item_filters_pre_img', array('NoNStatic', 'wpetruel_strip_img_tags_content'),10,2 );
			}else{
				if( isset($campaign['discardifnoimage']) && $campaign['discardifnoimage'] ) {

					if (isset($campaign['campaign_thumbnail_scratcher']) && $campaign['campaign_thumbnail_scratcher'] && class_exists( 'WPeMatico_Thumbnail_Scratcher')) {
						add_filter('wpematico_allow_insertpost', array('NoNStatic', 'discardifnoimage_aux'),99 ,3 );
					} else {
						add_filter('wpematico_item_parsers', array('NoNStatic', 'discardifnoimage'),99,4 );
					}	
					
				}
				if( isset($campaign['overwrite_image']) && $campaign['overwrite_image']=='overwrite' )
					add_filter('wpematico_overwrite_file', array('NoNStatic', 'wpematico_overwrite_file'),10,1 );
				if( isset($campaign['overwrite_image']) && $campaign['overwrite_image']=='keep' )
					add_filter('wpematico_overwrite_file', array('NoNStatic', 'wpematico_keep_file'),10,1 );
			}
			if( isset($campaign['clean_images_urls']) && $campaign['clean_images_urls'] )
				add_filter('wpematico_img_src_url',	array('NoNStatic', 'img_src_cleaner'),10,1 );
				
			//clean the image name from queries before save it
			if( isset($campaign['image_src_gettype']) && $campaign['image_src_gettype'] )
				add_filter('wpematico_newimgname',	array('NoNStatic', 'image_src_gettype'),9,4 );
			
			if( isset($campaign['campaign_enableimgrename']) && $campaign['campaign_enableimgrename'] )
				add_filter('wpematico_newimgname',	array('NoNStatic', 'image_rename'),10,3 );
				
			if( (isset($campaign['campaign_wcf']['great_amount']) && $campaign['campaign_wcf']['great_amount'] > 0 ) ||
				(isset($campaign['campaign_wcf']['cut_amount']) && $campaign['campaign_wcf']['cut_amount'] > 0 ) )
				add_filter('wpematico_item_parsers', array('NoNStatic', 'wordcountfilters'),20,4 );
			
			if( isset($campaign['campaign_wcf']['less_amount']) && $campaign['campaign_wcf']['less_amount'] > 0 ) 
				add_filter('wpematico_item_parsers', array('NoNStatic', 'discardwordcountless'),25,4 );

			if( isset($campaign['campaign_custitdup']) && $campaign['campaign_custitdup'] && isset($campaign['campaign_enablecustomtitle']) && $campaign['campaign_enablecustomtitle'] )
				add_filter('wpematico_item_parsers', 'wpempro_check_custom_titles',999,4);
			
			if( (isset($campaign['campaign_delfphrase']) && !empty($campaign['campaign_delfphrase'])) ) 
				add_filter('wpematico_item_parsers', array('NoNStatic', 'strip_lastphrasetoend'),28,4 );

			if( isset($campaign['campaign_lastag']['tag']) && !empty($campaign['campaign_lastag']['tag']) )
				add_filter('wpematico_item_parsers', array('NoNStatic', 'strip_lastag'),30,4 );
		}
		

		protected function load_options() {
			$this->options = self :: $default_options;
			$current_options = get_option( self :: OPTION_KEY );
			if ( !$current_options ) {
				if ( empty( self :: $default_options ) )  return;
				add_option( self :: OPTION_KEY, $this->options , '', 'yes' );
			}else {
				$this->options = array_merge( $this->options, $current_options );
				if( $this->options != $current_options ) { // add the new defaults to the saved
					update_option(self::OPTION_KEY, $this->options );
				}
			}
		}

		public function update_options() {
			return update_option( self :: OPTION_KEY, $this->options );
		}

		static function debug_data_cfg($return) {
			// WPeMatico PRO configuration
			$cfg = get_option( self :: OPTION_KEY);
			$return .= "\n" . '-- WPeMatico PROFESSIONAL Configuration' . "\n\n";
			$return .= 'Version:                  ' . WPEMATICOPRO_VERSION . "\n";

			foreach($cfg as $name => $value): 
				if ( wpematico_option_blacklisted($name)) continue; 
				$value = sanitize_option($name, $value); 
				$return .= $name . ":\t\t" . ((is_array($value))? print_r($value,1): esc_html($value)) . "\n";
			endforeach;
			
			$plugins_args = array();
			$plugins_args = apply_filters('wpematico_plugins_updater_args', $plugins_args);
			$plugin_args_name = 'pro_licenser';
			$args_plugin = $plugins_args[$plugin_args_name];
			$license = wpematico_licenses_handlers::get_key($plugin_args_name);
			$license_status = wpematico_licenses_handlers::get_license_status($plugin_args_name);
			$expire_license = 'No expiration';
			if ($license != false) {		
				$args_check = array(
					'license' 	=> $license,
					'item_name' => urlencode($args_plugin['api_data']['item_name']),
					'url'       => home_url(),
					'version' 	=> $args_plugin['api_data']['version'],
					'author' 	=> 'Esteban Truelsegaard'	
				);
				$api_url = $args_plugin['api_url'];
				$license_data = wpematico_licenses_handlers::check_license($api_url, $args_check);
				if (is_object($license_data)) {
								
					$expires = $license_data->expires;
					$expires = substr( $expires, 0, strpos( $expires, " "));
								
					if (!empty($license_data->payment_id) && !empty($license_data->license_limit)) {
						$expire_license = $expires;
					}
				}
			}

			if ($license_status == false) {
				$license_status = 'No license';
			}
		    $return .= 'License Status:           ' . $license_status . "\n";
		    $return .= 'License Expiration:       ' . $expire_license . "\n";


			
			$return .= "\n" . '-- First 3 CAMPAIGNS' . "\n\n";
			$allcampaigns = WPeMatico::get_campaigns();
			$qty = 1;
			$new_campaigns_data = array();
			foreach($allcampaigns as $key => $campaign ): 
				$wpecampaign = NoNStatic::get_exported_campaign($campaign['ID']);
				$new_campaigns_data[] = base64_encode($wpecampaign);
				if ($qty++ == 3) break ;
			endforeach;
			$new_campaigns_data_json = json_encode($new_campaigns_data);
			$return .= "Campaign code start -------" . $new_campaigns_data_json . "\n ------- Campaign code end \n\n";
			return $return;
		}


		/**
		* Actions-Links del Plugin
		*
		* @param   array   $data  Original Links
		* @return  array   $data  modified Links
		*/
		public static function init_action_links($data)	{
			if ( !current_user_can('manage_options') ) {
				return $data;
			}
			return array_merge(
				$data,
				array(
					'<a href="'.  admin_url('edit.php?post_type=wpematico&page=wpematico_settings&tab=prosettings').'" title="' . __('Go to WPeMatico Pro Settings Page', self :: TEXTDOMAIN ) . '">' . __('Settings', self :: TEXTDOMAIN ) . '</a>',
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

		public static function init_row_meta($data, $page)	{
			if ( $page != self::$basen ) {
				return $data;
			}
			return array_merge(
				$data,
				array(
				'<a href="http://etruel.com/" target="_blank">' . __('etruel Store') . '</a>',
				'<a href="http://etruel.com/my-account/support/" target="_blank">' . __('Support') . '</a>',
				'<a href="https://wordpress.org/support/view/plugin-reviews/wpematico?filter=5&rate=5#postform" target="_Blank" title="Rate 5 stars on Wordpress.org">' . __('Rate Plugin', self :: TEXTDOMAIN ) . '</a>'
				)
			);
		}		
		
		public static function activate() {
			$newcfg = get_option( 'WPeMatico_Options' );
			if( !empty($newcfg) ) {				
				$newcfg['nonstatic'] = false;
				update_option( 'WPeMatico_Options', $newcfg );
			}
			// update all campaigns with new values
			if(function_exists('wpematico_install') )
				wpematico_install( true );
		}
 		
		public static function deactivate() {
			$newcfg = get_option( 'WPeMatico_Options' );
			if( !empty($newcfg) ) {				
				$newcfg['nonstatic'] = false;
				update_option( 'WPeMatico_Options', $newcfg );
			}
		}

		public static function uninstall() {
			global $wpdb, $blog_id;
			$danger = get_option( 'WPeMatico_danger');
			$danger['wpemdeleoptions']	 = (isset($danger['wpemdeleoptions']) && !empty($danger['wpemdeleoptions']) ) ? $danger['wpemdeleoptions'] : false;
			$danger['wpemdelecampaigns'] = (isset($danger['wpemdelecampaigns']) && !empty($danger['wpemdelecampaigns']) ) ? $danger['wpemdelecampaigns'] : false;
			if ( is_network_admin() && $danger['wpemdeleoptions'] ) {
				if ( isset ( $wpdb->blogs ) ) {
					$blogs = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT blog_id ' .
							'FROM ' . $wpdb->blogs . ' ' .
							"WHERE blog_id <> '%s'",
							$blog_id
						)
					);
					foreach ( $blogs as $blog ) {
						delete_blog_option( $blog->blog_id, self :: OPTION_KEY );
					}
				}
			}
			if ($danger['wpemdeleoptions'])
				delete_option( self :: OPTION_KEY );
		}
	
		/*** REQUIREMENTS AND NOTICES 	*/
		/**	 * Check if requirements are met	 */
		function meets_requirements() {
			global $wp_version,$user_ID; //,$wpempro_admin_message;
			$message = $wpempro_admin_message = '';
			$checks=true;
			if(!is_admin()) return false;
			if( !is_plugin_active( 'wpematico/wpematico.php') ) {
				$message.= __('You are using WPeMatico PRO.', self :: TEXTDOMAIN ).'<br />';
				$message.= __('Plugin <b>WPeMatico FREE</b> must be activated!', self :: TEXTDOMAIN );
				$message.= ' <a href="'.admin_url('plugins.php').'#wpematico"> '. __('Go to Activate Now', self :: TEXTDOMAIN ). '</a>';
				$message.= '<script type="text/javascript">jQuery(document).ready(function($){$("#wpematico").css("backgroundColor","yellow");});</script>';
				$checks=false;
			}else{  //WPeMatico is active
				if( !class_exists( 'WPeMatico') ) {
					$message.= __('You are using WPeMatico PRO, but doesn\'t exist class WPeMatico.', self :: TEXTDOMAIN );
					$message.= __('Something is going wrong. May be PHP Version prior to 5.3', self :: TEXTDOMAIN );
					$checks=false;
				}
			}

			if (!empty($message))
				$wpempro_admin_message = '<div id="message" class="error fade"><strong>WPeMatico PRO:</strong><br />'.$message.'</div>';

			if (!empty($wpempro_admin_message)) {
				//send response to admin notice : ejemplo con la función dentro del add_action req. php 5.3
				add_action('admin_notices', function() use ($wpempro_admin_message) {
					echo $wpempro_admin_message;
				});
			}
			$this->requirement = $checks;
			return $this->requirement;
		}

		public static function rssimg_add2img_set_featured_image($img, $current_item, $campaign, $feed, $item) {
			if (!empty(self::$rssimg_add2img_featured_image) && $campaign['rssimg_add2img']) {
				return self::$rssimg_add2img_featured_image;
			} else {
				self::$rssimg_add2img_featured_image = '';
			}
			return $img;   
		}
		public static function rssimg_add2img_get_featured_image($img) {
			if (!empty(self::$rssimg_add2img_featured_image)) {
				return self::$rssimg_add2img_featured_image;
			}
			return $img;   
		}

		public static function add_no_follow_option( $campaign_data, $cfgbasic ) { 
			global $post, $campaign_data, $helptip;
				$add_no_follow = $campaign_data['add_no_follow'];
				$campaign_strip_links_options = $campaign_data['campaign_strip_links_options'];
				$campaign_striphtml = $campaign_data['campaign_striphtml'];
				$campaign_strip_links = $campaign_data['campaign_strip_links'];
				?>
			<div id="div_add_no_follow" style="<?php echo (($campaign_striphtml || ($campaign_strip_links && $campaign_strip_links_options['a']) || ($campaign_strip_links && !$campaign_strip_links_options['a'] && !$campaign_strip_links_options['iframe'] && !$campaign_strip_links_options['script']))?'display:none;':''); ?>">
			<p>
				<input class="checkbox" type="checkbox"<?php checked($add_no_follow ,true);?> name="add_no_follow" value="1" id="add_no_follow"/> 
				<label for="add_no_follow"><?php echo __('Add <code>rel="nofollow"</code> to links.', WPeMatico :: TEXTDOMAIN ); ?></label>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['add_no_follow']; ?>"></span>
			</p>
			</div>
			<?php
		}
		public static function add_no_follow_links($args, $campaign) {
			
			trigger_error(sprintf(__('Add nofollow to links in: %1s','wpematico'),$args['post_title']),E_USER_NOTICE);
			$args['post_content'] = self::function_no_follow_links($args['post_content']);

			return $args;
		}
		public static function function_no_follow_links( $content ) {

			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
			if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
				if( !empty($matches) ) {
					//$ownDomain = get_option('home');
					$ownDomain = $_SERVER['HTTP_HOST'];
					
					for ($i=0; $i < count($matches); $i++)
					{
					
						$tag  = $matches[$i][0];
						$tag2 = $matches[$i][0];
						$url  = $matches[$i][0];
							
						// bypass #more type internal link
						$res = preg_match('/href(\s)*=(\s)*"[#|\/]*[a-zA-Z0-9-_\/]+"/',$url);
						if($res) {
							continue;
						}
						
						$pos = strpos($url,$ownDomain);
						if ($pos === false) {
							
							$domainCheckFlag = true;
							
							
							$noFollow = '';

							//exclude domain or add nofollow
							if($domainCheckFlag) {
								$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
								preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
								if( count($match) < 1 )
									$noFollow .= ' rel="nofollow"';
							}
							
							// add nofollow/target attr to url
							$tag = rtrim ($tag,'>');
							$tag .= $noFollow.'>';
							$content = str_replace($tag2,$tag,$content);
						}
					}
				}
			}
			
			$content = str_replace(']]>', ']]&gt;', $content);
			return $content;
		}
	}
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ( ! class_exists( 'NoNStatic' ) ) {
	class NoNStatic extends WPeMaticoPRO {
		
		static function wpematico_quick_actions( $actions ) {
			global $post;
			if( $post->post_type == 'wpematico' && 'trash' != $post->post_status ) {
				$nonce= wp_create_nonce  ('wpemexport-nonce');
				$action_name = "wpematico_export_campaign";
				$action = '?action='.$action_name.'&amp;post='.$post->ID.'&_wpnonce=' . $nonce;
				$link = admin_url( "admin.php". $action );
				$actions['export'] = '<a href="'.$link.'" title="' . esc_attr(__("Export & download Campaign", WPeMatico :: TEXTDOMAIN)) . '">' .  __('Export', WPeMatico :: TEXTDOMAIN) . '</a>';
			}
			return $actions;
		}
		static function wpematico_export_campaign($status = ''){
			$nonce=(isset($_REQUEST['_wpnonce']) && !empty($_REQUEST['_wpnonce']) ) ? $_REQUEST['_wpnonce'] : '';
			if(!wp_verify_nonce($nonce, 'wpemexport-nonce') ) wp_die('Are you sure?'); 
			if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wpematico_export_campaign' == $_REQUEST['action'] ) ) ) {
				wp_die(__('No campaign ID has been supplied!',  WPeMatico :: TEXTDOMAIN));
			}
			// Get the original post
			$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
			
			$wpecampaign = self::get_exported_campaign($id);
			$new_campaigns_data = array();
			$new_campaigns_data[] = base64_encode($wpecampaign);
			$new_campaigns_data_json = json_encode($new_campaigns_data);
			$post_name = get_post_field('post_name', $id);
			
			// Copy the post and insert it
			if (isset($new_campaigns_data_json) && $new_campaigns_data_json!=null) {
				header('Content-type: text/plain');
				header('Content-Disposition: attachment; filename="'.$post_name.'.txt"');
				print $new_campaigns_data_json;
				die();
				
			} else {
				$post_type_obj = get_post_type_object( $post->post_type );
				wp_die(esc_attr(__('Exporting failed, could not find the campaign:',  WPeMatico :: TEXTDOMAIN)) . ' ' . $id);
			}
		}
		static function get_exported_campaign($id, $type='json'){
			$post = get_post($id);
			$wpecampaign = null;
			// Copy the post and insert it
			if (isset($post) && $post!=null && $post->post_type == 'wpematico') {
				$exp_post = array(
				'menu_order' => $post->menu_order,
				'guid' => $post->guid,
				'comment_status' => $post->comment_status,
				'ping_status' => $post->ping_status,
				'pinged' => $post->pinged,
				'post_author' => @$post->author,
				//'post_content' => $post->post_content,
				'post_excerpt' => $post->post_excerpt,
				'post_mime_type' => $post->post_mime_type,
				'post_parent' => $post->post_parent,
				'post_password' => $post->post_password,
				'post_status' => $post->post_status,
				'post_title' => $post->post_title,
				'post_type' => $post->post_type,
				'to_ping' => $post->to_ping, 
				'post_date' => $post->post_date,
				'post_date_gmt' => get_gmt_from_date($post->post_date)
				);	
				$cid = WPeMatico::get_campaign($id);
				$taxonomiesNewPost = get_object_taxonomies($cid['campaign_customposttype']);
				$taxonomiesNewPost = array_diff($taxonomiesNewPost, array('category', 'post_tag', 'post_format'));
				foreach($taxonomiesNewPost AS $tax) {
					$terms = wp_get_object_terms( $id, $tax );
					$term = array();
					foreach( $terms AS $t ) {
						$term[] = $t->slug;
					}
					$cus_tax[$tax]=$term;
				}
				$campaign = array();
				$campaign['exp_post'] = $exp_post ;
				$campaign['data'] = get_post_custom($post->ID);
				$campaign['cus_tax'] = (isset($cus_tax) && !empty($cus_tax) ) ? $cus_tax : null;
				foreach ($campaign['data'] as $dkey => $value) {
					foreach ($value as $vkey => $vvalue) {
						$campaign['data'][$dkey][$vkey] = base64_encode($vvalue);
					}
				}
				switch($type) {
					case "json":
						$wpecampaign = json_encode( $campaign );
						break;

					default:
						$wpecampaign = $campaign;
						break;
				}				
			}
			return $wpecampaign;
			
		}
		public static function get_json_error() {
			$error_json = '';
			switch(json_last_error()) {
		        case JSON_ERROR_NONE:
		            $error_json = '';
		        break;
		        case JSON_ERROR_DEPTH:
		            $error_json =  __('Maximum stack depth exceeded',  WPeMatico :: TEXTDOMAIN);
		        break;
		        case JSON_ERROR_STATE_MISMATCH:
		            $error_json = __('Underflow or the modes mismatch',  WPeMatico :: TEXTDOMAIN);
		        break;
		        case JSON_ERROR_CTRL_CHAR:
		            $error_json = __('Unexpected control character found',  WPeMatico :: TEXTDOMAIN);
		        break;
		        case JSON_ERROR_SYNTAX:
		            $error_json = __('Syntax error, malformed JSON',  WPeMatico :: TEXTDOMAIN);;
		        break;
		        case JSON_ERROR_UTF8:
		            $error_json = __('Malformed UTF-8 characters, possibly incorrectly encoded',  WPeMatico :: TEXTDOMAIN);;
		        break;
		        default:
		            $error_json = __('Unknown error',  WPeMatico :: TEXTDOMAIN);;
		        break;
		    }
		    return $error_json;
		}
		static function wpematico_import_campaign(){
			$nonce=(isset($_REQUEST['wpemimport_nonce']) && !empty($_REQUEST['wpemimport_nonce']) ) ? $_REQUEST['wpemimport_nonce'] : '';
			if(!wp_verify_nonce($nonce, 'import-campaign') ) wp_die('Can\'t import.'); 
			
			$post_type=(isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']) ) ? $_REQUEST['post_type'] : '';
			if( !$post_type == 'wpematico' ) wp_die('This was wrong.'); 
			
			//Allow Uploads files ?
			if( in_array(str_replace('.','',strrchr($_FILES['txtcampaign']['name'], '.')),explode(',','txt') ) 
				&& ($_FILES['txtcampaign']['type']=='text/plain')
				&& !$_FILES['txtcampaign']['error'] ) {	
			}else{
				$message = __("** Can't upload! Just .txt files allowed!",  WPeMatico::TEXTDOMAIN );
				WPeMatico::add_wp_notice( array('text' => $message, 'below-h2'=>false, 'error' => true) );
				return true;
			}
			$campaign = file_get_contents($_FILES['txtcampaign']['tmp_name']);
			unlink($_FILES['txtcampaign']['tmp_name']);
			
			//search first { to start the string
			//$pos = strpos($campaign,'{'); // UTF-8 add 3 bytes at the begining of the file
			//$campaign1 = substr($campaign, $pos); 

/*			$campaign2 = convert_uudecode($campaign1);
			$wpecampaign = self::print_r_reverse( $campaign2 );
*/			
			$wpecampaigns = json_decode( $campaign, true );
			$error_json = self::get_json_error();
		    if (!empty($error_json)) {
				WPeMatico::add_wp_notice( array('text' => 'Error Json: '.$error_json, 'below-h2'=>false, 'error' => true) );
				return true;
		    }
		    foreach ($wpecampaigns as $wpecampaign) {
		    	$wpecampaign = base64_decode($wpecampaign);
		    	$wpecampaign = json_decode($wpecampaign, true );
		    	$error_json = self::get_json_error();
			    if (!empty($error_json)) {
					WPeMatico::add_wp_notice( array('text' => 'Error Json: '.$error_json, 'below-h2'=>false, 'error' => true) );
					return true;
			    }
		    	$new_post_id = wp_insert_post($wpecampaign['exp_post']);
			
				$post_meta_keys = array_keys($wpecampaign['data']);
				if (!empty($post_meta_keys)) {
					foreach ($post_meta_keys as $meta_key) {
						$meta_values = $wpecampaign['data'][$meta_key];
						foreach ($meta_values as $meta_value) {
							$meta_value = base64_decode($meta_value);
							$meta_value = maybe_unserialize($meta_value);
							add_post_meta($new_post_id, $meta_key, $meta_value);
						}
					}
				}
				if(isset($wpecampaign['cus_tax']) && !empty($wpecampaign['cus_tax']) )
					foreach($wpecampaign['cus_tax'] as $tax => $term) {
						wp_set_object_terms( $new_post_id, $term, $tax );
					}  

				$campaign_data = WPeMatico :: get_campaign( $new_post_id );
				$campaign_data['activated'] = false;
				WPeMatico :: update_campaign( $new_post_id, $campaign_data );


		    }
			
			WPeMatico::add_wp_notice( array('text' => __('Campaigns Imported.',  WPeMatico :: TEXTDOMAIN), 'below-h2'=>false ) );
			
		}
		public static function bulk_actions_import_campaign($actions) {
	        $actions['export_campaigns'] = __( 'Export campaigns', WPeMatico :: TEXTDOMAIN);
	        return $actions;
	    }
	    public static function bulk_action_handler_import_campaign( $redirect_to, $doaction, $post_ids ) {
			if ($doaction !== 'export_campaigns' ) {
			    return $redirect_to;
			}
			$new_campaigns_data = array();
			$file_name = 'wpematico_campaigns';
			foreach ($post_ids as $post_id) {
			    $wpecampaign = self::get_exported_campaign($post_id);
				$new_campaigns_data[] = base64_encode($wpecampaign);
			}
			$new_campaigns_data_json = json_encode($new_campaigns_data);
			// Copy the post and insert it
			if (isset($new_campaigns_data_json) && $new_campaigns_data_json!=null) {
				header('Content-type: text/plain');
				header('Content-Disposition: attachment; filename="'.$file_name.'.txt"');
				print $new_campaigns_data_json;
				die();
			}
			$redirect_to = add_query_arg( 'bulk_export_campaigns', count( $post_ids ), $redirect_to );
			return $redirect_to;
		}
		public static function process_ramdom_rewrites($args, $campaign) {
			if (isset($campaign['activate_ramdom_rewrite']) && $campaign['activate_ramdom_rewrite']){
				trigger_error('<b>'.__('Initiating Ramdom Rewrites Process.', self ::TEXTDOMAIN).'</b>',E_USER_NOTICE);
				
				$ramdom_rewrites_options = get_option(self::RAMDOM_REWRITES_OPTION);
				$ramdom_rewrites_options = wp_parse_args($ramdom_rewrites_options, self::default_ramdom_rewrites_options( FALSE ) );
				$ramdom_rewrites_array = array();
				$line_arr = explode("\n", $ramdom_rewrites_options['words_to_rewrites']);	 
				foreach ($line_arr as $key => $value){
					$value = trim($value); 
					if  (!empty($value)) {
						$new_array_words = array();
						$array_words = explode(",", $value);
						foreach ($array_words as $kw => $valw){
							$valw = trim($valw); 
							if  (!empty($valw)) {
								$new_array_words[] = $valw;
							}
						}	 
						$ramdom_rewrites_array[] = $new_array_words;
				    }
				}
				$line_arr = explode("\n", $campaign['words_to_rewrites']);	 
				foreach ($line_arr as $key => $value){
					$value = trim($value); 
					if  (!empty($value)) {
						$new_array_words = array();
						$array_words = explode(",", $value);
						foreach ($array_words as $kw => $valw){
							$valw = trim($valw); 
							if  (!empty($valw) && apply_filters('wpe_pro_ramdom_rewrites_accept_word', true, $valw, $args, $campaign)) {
								$new_array_words[] = $valw;
							}
						}	 
						$ramdom_rewrites_array[] = $new_array_words;
				    }
				}
				$ramdom_rewrites_array = apply_filters('wpe_pro_ramdom_rewrites_array', $ramdom_rewrites_array, $args, $campaign);
				$maximum_replaces = 10;
				if (isset($campaign['ramdom_rewrite_count']) && is_numeric($campaign['ramdom_rewrite_count'])) {
					$maximum_replaces = $campaign['ramdom_rewrite_count']; 
				}
				$count_replaces = 0;
				foreach ($ramdom_rewrites_array as $rewrite_line) {
					$current_offeset = 0;
					$continue = false;
					if (count($rewrite_line) > 1) {
						while ($current_offeset < strlen($args['post_content'])) {
							if ($count_replaces >= $maximum_replaces) {
								break 2;
							}
							$kw = array_rand($rewrite_line);
							$current_search = $rewrite_line[$kw];
							$found_fail = array();
							while(strpos($args['post_content'], $current_search, $current_offeset) === false) {
								if (in_array($kw, $found_fail) === false) {
									$found_fail[] = $kw;
								}
								if (count($found_fail) >= count($rewrite_line)) {
						    		$continue = true;
						    		break 2;
						    	} 
								$kw = array_rand($rewrite_line);
								$current_search = $rewrite_line[$kw];
							}
							do {
								$rr = array_rand($rewrite_line);
						    	$random_remplace = $rewrite_line[$rr];
							} while ($kw == $rr);
							$new_replace = replace_first_offset($current_search, $random_remplace, $args['post_content'], $current_offeset);
							if ($current_offeset == $new_replace->pos) {
								$current_offeset = $current_offeset+5;
							} else {
								$current_offeset = $new_replace->pos;
								$args['post_content'] = $new_replace->result;
								$count_replaces++;
							}
						}
					}
					if ($continue) {
						$continue = false;
						continue;
					}	
				}
				trigger_error('<b>'.sprintf(__('Words replaced by Ramdom Rewrites: %s.', self ::TEXTDOMAIN), $count_replaces).'</b>',E_USER_NOTICE);		
					
			}

			

			return $args;
		}
		static function import_in_views($links) {
			global $post_type;
			if($post_type != 'wpematico') return $links;
			ob_start();
			?><form style="opacity: 0;position: absolute;" id="importcampaign" method='post' ENCTYPE='multipart/form-data'>
				<?php wp_nonce_field( 'import-campaign', 'wpemimport_nonce' );  ?>
				<input type="hidden" name="wpematico-action" value="import_campaign" />
				<input style="display:none;" type="file" class="button" name='txtcampaign' id='txtcampaign'>
			</form>
			<a id="importcpg" href="Javascript:void(0);" title="<?php echo esc_attr(__("Upload & import a Campaign", WPeMatico :: TEXTDOMAIN)) ?>"><?php echo   __('Import campaign', WPeMatico :: TEXTDOMAIN) ?></a>
			<script>(function($) {
				$('#importcpg').click(function(){
					$('#txtcampaign').click();
				});
				$('#txtcampaign').change(function(){
					$('#importcampaign').submit();
				});
			})(jQuery);
			</script>
			<?php
			$contents = ob_get_contents();
			ob_end_clean();

			$action_name = "wpematico_import_campaign";
			$links['import']=$contents;
			return $links;
		}

		static function print_r_reverse($in) {
			$lines = explode("\n", trim($in));
			if (trim($lines[0]) != 'Array') {
				// bottomed out to something that isn't an array
				return $in;
			} else {
				// this is an array, lets parse it
				if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
					// this is a tested array/recursive call to this function
					// take a set of spaces off the beginning
					$spaces = $match[1];
					$spaces_length = strlen($spaces);
					$lines_total = count($lines);
					for ($i = 0; $i < $lines_total; $i++) {
						if (substr($lines[$i], 0, $spaces_length) == $spaces) {
							$lines[$i] = substr($lines[$i], $spaces_length);
						}
					}
				}
				array_shift($lines); // Array
				array_shift($lines); // (
				array_pop($lines); // )
				$in = implode("\n", $lines);
				// make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
				preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
				$pos = array();
				$previous_key = '';
				$in_length = strlen($in);
				// store the following in $pos:
				// array with key = key of the parsed array's item
				// value = array(start position in $in, $end position in $in)
				foreach ($matches as $match) {
					$key = $match[1][0];
					$start = $match[0][1] + strlen($match[0][0]);
					$pos[$key] = array($start, $in_length);
					if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
					$previous_key = $key;
				}
				$ret = array();
				foreach ($pos as $key => $where) {
					// recursively see if the parsed out value is an array too
					$ret[$key] = self::print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
				}
				return $ret;
			}
		} 
		
		// Create new meta boxes
		public static function meta_boxes($campaign_data = array(), $cfgbasic ) { 
			global $post, $campaign_data; 
			$cfg = get_option( self :: OPTION_KEY); //PRO settings
			if ($cfg['enablecustomtitle'])   // Si está habilitado en settings, lo muestra 
				add_meta_box( 'custitle-box', __('Custom Title Options', WPeMatico :: TEXTDOMAIN ), array(  'NoNStatic'  ,'custitle_box' ),'wpematico','normal', 'default' );
			if ($cfg['enablekwordf'])   // Si está habilitado en settings, lo muestra 
				add_meta_box( 'kwordf-box', __('Keywords Filters', WPeMatico :: TEXTDOMAIN ), array(  'NoNStatic'  ,'kwordf_box' ),'wpematico','normal', 'default' );
			if ($cfg['enablewcf'])   // Si está habilitado en settings, lo muestra 
				add_meta_box( 'wcountf-box', __('Word Count Filters', WPeMatico :: TEXTDOMAIN ), array(  'NoNStatic'  ,'wcountf_box' ),'wpematico','normal', 'default' );
			if ($cfg['enable_ramdom_words_rewrites'])   // Si está habilitado en settings, lo muestra 
				add_meta_box( 'ramdom-words-rewrites-box', __('Ramdom Rewrites', WPeMatico :: TEXTDOMAIN ), array(  'NoNStatic'  ,'ramdom_words_rewrites_box' ),'wpematico','normal', 'default' );
			if ($cfg['enablecfields'])   // Si está habilitado en settings, lo muestra 
				add_meta_box( 'cfields-box', __('Custom Fields', WPeMatico :: TEXTDOMAIN ), array( 'NoNStatic' ,'cfields_box' ),'wpematico','normal', 'default' );
			
			add_meta_box( 'proimages-box', __('PRO Options for Images', WPeMatico :: TEXTDOMAIN ), array(  'NoNStatic'  ,'proimages_box' ),'wpematico','normal', 'default' );
			add_action('admin_print_scripts-post.php', array( __CLASS__ ,'admin_scripts'));
			add_action('admin_print_scripts-post-new.php', array( __CLASS__ ,'admin_scripts')); 
			add_action('admin_print_styles', array( __CLASS__ ,'wpe_m_styles'));
		}
		public static function ramdom_words_rewrites_box() {
			global $post, $campaign_data, $helptip;
			$activate_ramdom_rewrite = @$campaign_data['activate_ramdom_rewrite'];
			$ramdom_rewrite_count = @$campaign_data['ramdom_rewrite_count'];
			$words_to_rewrites = @$campaign_data['words_to_rewrites'];
			
			?>
			<input class="checkbox" type="checkbox"<?php checked($activate_ramdom_rewrite,true);?> name="activate_ramdom_rewrite" value="1" id="activate_ramdom_rewrite"/> <b><?php echo '<label for="activate_ramdom_rewrite">' . __('Activate Ramdom Rewrites.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['activate_ramdom_rewrite']; ?>"></span><br/>
			<div id="div_ramdom_words_rewrites" style="margin-left: 20px; <?php if(!$activate_ramdom_rewrite) echo 'display: none;' ?>">
			<label for="ramdom_rewrite_count"><b>Number of maximum words to replace:</b></label>
			<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['ramdom_rewrite_count']; ?>"></span><br/>
			<input type="number" min="0" size="5" class="small-text" id="ramdom_rewrite_count" name="ramdom_rewrite_count" value="<?php echo $ramdom_rewrite_count; ?>">
			<br/>
			<b><label for="words_to_rewrites"><?php _e( 'Words to Rewrites:', self :: TEXTDOMAIN);?></label></b><span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['words_to_rewrites']; ?>"></span><br/>
				<textarea style="width:100%;" id="words_to_rewrites" name="words_to_rewrites"><?php echo $words_to_rewrites; ?></textarea><br>
						
				<?php _e( 'Enter a comma-separated list of words for rewrites use each line for different rewriting patterns.', self :: TEXTDOMAIN);?>
			</div>
			<?php
		}
		static function wpe_m_styles() {
			global $post;
			if($post->post_type != 'wpematico') return $post->ID;
			wp_enqueue_style('thickbox');
		}
			
		static function admin_scripts() { // load javascript 
			global $post;
			if($post->post_type != 'wpematico') return $post_id;
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
//			wp_register_script('my-upload',self :: $uri .'lib/myetupload.js', array('jquery','media-upload','thickbox'));
//			wp_enqueue_script('my-upload');
			add_action('admin_head', array( __CLASS__ ,'procampaigns_admin_js'));
		}
		
		static function procampaigns_admin_js() { // load javascript 
			global $post, $campaign_data;
			$cfg = get_option( self :: OPTION_KEY);
			?>
			<style type="text/css">
			/* The Modal (background) */
			.modal {
				display: none; /* Hidden by default */
				position: fixed; /* Stay in place */
				z-index: 1; /* Sit on top */
				padding-top: 100px; /* Location of the box */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
				background-color: rgb(0,0,0); /* Fallback color */
				background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.modal-content {
				position: relative;
				background-color: #fefefe;
				margin: auto;
				padding: 0;
				border: 1px solid #888;
				width: 60%;
				box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
				-webkit-animation-name: animatetop;
				-webkit-animation-duration: 0.4s;
				animation-name: animatetop;
				animation-duration: 0.4s
			}

			/* Add Animation */
			@-webkit-keyframes animatetop {
				from {top:-300px; opacity:0} 
				to {top:0; opacity:1}
			}

			@keyframes animatetop {
				from {top:-300px; opacity:0}
				to {top:0; opacity:1}
			}

			/* The Close Button */
			.modal-close {
				color: white;
				float: right;
				font-size: 28px;
				font-weight: bold;
				line-height: 35px;
			}

			.modal-close:hover,
			.modal-close:focus {
				color: #000;
				text-decoration: none;
				cursor: pointer;
			}

			.modal-header {
				padding: 2px 16px;
				background-color: #eb9600;
				color: white;
			}

			.modal-body {padding: 2px 16px;}

			.modal-footer {
				padding: 2px 16px;
				background-color: #eb9600;
				color: white;
			}
			</style>
			<style type="text/css">
				#proimages-box h2.hndle {background: #e1fb34;	}
				#kwordf-box h2.hndle {background: #f0576d;	}
				#wcountf-box h2.hndle {background: #e7dd88;	}
				#custitle-box h2.hndle {background: #E3E3E3;	}
				#cfields-box h2.hndle {background: #a2d1e1;	}
				#ramdom-words-rewrites-box h2.hndle {background: orangered;	}
			</style>
			<script type="text/javascript" language="javascript">
			function LCeros(obj,num) {
				obj.value = Number(obj.value);
				while (obj.value.length<num)
					obj.value = '0'+obj.value;
			}
			<?php if($cfg['enablemultifeed']) : ?>
			jQuery(document).on('before_add_more_feed', function(e, feed_new, newval) {
				jQuery('.feedoptionsicon', feed_new).eq(0).attr('id', 'feedoptions_'+newval);
				jQuery('.modal', feed_new).eq(0).attr('id', 'modalopt_'+newval);
				jQuery('.is_multipagefeed', feed_new).eq(0).attr('name', 'is_multipagefeed['+newval+']');
				jQuery('.is_multipagefeed', feed_new).eq(0).attr('id', 'is_multipagefeed_'+newval);
				jQuery('.multifeed_maxpages', feed_new).eq(0).attr('name', 'multifeed_maxpages['+newval+']');
				jQuery('.multifeed_maxpages', feed_new).eq(0).attr('id', 'multifeed_maxpages_'+newval);
				
				jQuery('.feedoptionsicon').click(function() {
					var id= jQuery(this).attr('id');
					var feed= id.substring(12);
					var modal = '#modalopt_' + feed;
					jQuery(modal).show();
				});
				jQuery('.modal-close').click(function() {
					jQuery('.modal').hide();					
				});
				// If an event gets to the body
				jQuery(".modal").click(function(){
				  jQuery(".modal").fadeOut();
				});
				jQuery(".modal-content").click(function(e){
				  e.stopPropagation();
				});
			});
			<?php endif; ?>	
			function action_strip_links() {
				if (jQuery('#campaign_strip_links').is(':checked') && !jQuery('#campaign_strip_links_options_a').is(':checked')&& !jQuery('#campaign_strip_links_options_iframe').is(':checked')&& !jQuery('#campaign_strip_links_options_script').is(':checked')) {
					jQuery('#add_no_follow').attr('checked', false);
					jQuery('#div_add_no_follow').fadeOut();
				} else if (jQuery('#campaign_strip_links').is(':checked') && jQuery('#campaign_strip_links_options_a').is(':checked')) {
					jQuery('#add_no_follow').attr('checked', false);
					jQuery('#div_add_no_follow').fadeOut();
				}  else {
					jQuery('#div_add_no_follow').fadeIn();
				}
			}
			function add_events_no_follow() {
				jQuery('#campaign_striphtml').change(function() {
					if (jQuery('#campaign_striphtml').is(':checked')) {
						jQuery('#add_no_follow').attr('checked', false);
						jQuery('#div_add_no_follow').fadeOut();
						
					} else {
						jQuery('#div_add_no_follow').fadeIn();
					}
				});
				jQuery('#campaign_strip_links').change(function() {
					action_strip_links();
				});
				
				jQuery('#campaign_strip_links_options_a').change(function() {
					action_strip_links();
				});
				jQuery('#campaign_strip_links_options_iframe').change(function() {
					action_strip_links();
				});
				jQuery('#campaign_strip_links_options_script').change(function() {
					action_strip_links();
				});
				jQuery('#add_no_follow').change(function() {
					if (jQuery('#add_no_follow').is(':checked')) {
						jQuery('#campaign_strip_links_options_a').attr('checked', false);
						jQuery('#campaign_striphtml').attr('checked', false);
					}
				});
			}

			function add_event_ramdom_rewrite() {
				jQuery('#activate_ramdom_rewrite').change(function() {
					if (jQuery('#activate_ramdom_rewrite').is(':checked')) {
						jQuery('#div_ramdom_words_rewrites').fadeIn();
					} else {
						jQuery('#div_ramdom_words_rewrites').fadeOut();
					}
				});

			}

			jQuery(document).ready(function($){
				
				
				add_events_no_follow();
				add_event_ramdom_rewrite();
				<?php if($cfg['enablemultifeed']) : ?>
				$('.feedoptionsicon').click(function() {
					var id= $(this).attr('id');
					var feed= id.substring(12);
					var modal = '#modalopt_' + feed;
					$(modal).show();
				});
				$('.modal-close').click(function() {
					$('.modal').hide();					
				});
				// If an event gets to the body
				$(".modal").click(function(){
				  $(".modal").fadeOut();
				});
				$(".modal-content").click(function(e){
				  e.stopPropagation();
				});
				<?php endif; ?>
				
				<?php if($cfg['enableimportfeed']) : ?>
				$('#bimport').click(function() {
					$('.feed_header').fadeToggle();
					$('#feeds_list').toggle();
					$('#addmorefeed').toggle();
					$('#checkfeeds').toggle();
					$('#pbfeet').toggle();
					$('#blocktxt_feedlist').toggleClass('hide');
					if ( $(this).text() == "<?php _e('Cancel Import', WPeMatico :: TEXTDOMAIN ); ?>" ) 
						$(this).text('<?php _e('Import feed list', WPeMatico :: TEXTDOMAIN ); ?>');
					else
						$(this).text('<?php _e('Cancel Import', WPeMatico :: TEXTDOMAIN ); ?>');
				});
				<?php endif; ?>

				$('#campaign_enableimgrename').click(function() {
					if ( true == $('#campaign_enableimgrename').is(':checked')) {
						$('#noimgren').fadeIn();
					} else {
						$('#noimgren').fadeOut();
					}
				});

				$('#campaign_ctitlecont').click(function() {
					if ( true == $('#campaign_ctitlecont').is(':checked')) {
						$('#ctnocont').fadeIn();
					} else {
						$('#ctnocont').fadeOut();
					}
				});

				$('#campaign_enablecustomtitle').click(function() {
					if ( true == $('#campaign_enablecustomtitle').is(':checked')) {
						$('#nocustitle').fadeIn();
					} else {
						$('#nocustitle').fadeOut();
					}
				});

				$('#campaign_ctitlecont').click(function() {
					if ( true == $('#campaign_ctitlecont').is(':checked')) {
						$('#ctnocont').fadeIn();
					} else {
						$('#ctnocont').fadeOut();
					}
				});

				$('.chkgwol').click(function() {
					var wol = $(this).parent().children('#gwol');
					if ( true == $(this).is(':checked')) {
						wol.html('words.');
						wol.css('color', 'red');
					}else{
						wol.html('letters.');
						wol.css('color', 'black');
					}
				});
				$('.chkcwol').click(function() {
					var wol = $(this).parent().children('#cwol');
					if ( true == $(this).is(':checked')) {
						wol.html('words ');
						wol.css('color', 'red');
					}else{
						wol.html('letters ');
						wol.css('color', 'black');
					}
				});
				$('.chklwol').click(function() {
					var wol = $(this).parent().children('#lwol');
					if ( true == $(this).is(':checked')) {
						wol.html('words.');
						wol.css('color', 'red');
					}else{
						wol.html('letters.');
						wol.css('color', 'black');
					}
				});
				$('.et_upload_button').click(function() {
					var btnid = $(this).attr('id'); 
					var field_img = $(this).prev('input').attr('name');
					var field_link = field_img.substring(0,11)+'_link'; 
					var field_title = field_img.substring(0,11)+'_title';
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					ff(field_img, field_link, field_title);
					return false;
				});

				function ff(field_img, field_link, field_title){
					window.send_to_editor = function(html) {   // html = "<img src="http://domain/wp-content/uploads/2015/07/talleres_bandera11.png" alt="" width="170" height="99" class="alignnone size-full wp-image-345" />"
                        var linktit = jQuery('img',html).attr('title');
						if (typeof linktit == 'undefined') var linktit = jQuery(html).attr('title');
						var imgurl  = jQuery('img',html).attr('src');
						if (typeof imgurl == 'undefined') var imgurl = jQuery(html).attr('src');
						var linkurl = jQuery(html).attr('href');
						jQuery('input[name="'+field_img+'"]').val(imgurl);
						jQuery('input[name="'+field_link+'"]').val(linkurl);
						jQuery('input[name="'+field_title+'"]').val(linktit);
						tb_remove();
					}
				}
				$('#strip_all_images').click(function() {
					if ( true == $('#strip_all_images').is(':checked')) {
						$('#noimages').fadeOut();
					} else {
						$('#noimages').fadeIn();
					}
				});
				$('#default_img').click(function() {
					if ( true == $('#default_img').is(':checked')) {
						$('#tblupload').fadeIn();
					} else {
						$('#tblupload').fadeOut();
					}
				});
				$('#campaign_rssimg').click(function() {
					if ( true == $('#campaign_rssimg').is(':checked')) {
						$('.rssimg_opt').fadeIn();
					} else {
						$('.rssimg_opt').fadeOut();
					}
				});
				$('#rssimg_featured').click(function() {
					if ( true == $('#rssimg_featured').is(':checked')) {
						$('#featured_opt').fadeIn();
					} else {
						$('#featured_opt').fadeOut();
					}
				});				
				$('#add1stimg').click(function() {
					if ( true == $('#add1stimg').is(':checked')) {
						$('#img_permal').fadeIn();
					} else {
						$('#img_permal').fadeOut();
					}
				});
			<?php if($cfg['enableimgfilter']) : ?>
				$('#addmoreimgf').click(function() {
					$('#imgfilt_max').val( parseInt($('#imgfilt_max').val(),10) + 1 );
					newval = $('#imgfilt_max').val();					
					nuevo= $('#nuevoimgfilt').clone();
					$('select', nuevo).eq(0).attr('name','campaign_if_allow['+ newval +']');
					$('select', nuevo).eq(1).attr('name','campaign_if_woh['+ newval +']');
					$('select', nuevo).eq(2).attr('name','campaign_if_mol['+ newval +']');
					$('input', nuevo).eq(0).attr('name','campaign_if_value['+ newval +']');
					//$('select', nuevo).eq(0).val('');
					$('input', nuevo).eq(0).val('');
					nuevo.show();
					$('#imgfilt_edit').append(nuevo);
				});
			<?php endif; ?>			
			
				$('#addmorefeatimgf').click(function() {
					$('#featimgfilt_max').val( parseInt($('#featimgfilt_max').val(),10) + 1 );
					newval = $('#featimgfilt_max').val();					
					nuevo= $('#nuevofeatimgfilt').clone();
					$('select', nuevo).eq(0).attr('name','campaign_feat_allow['+ newval +']');
					$('select', nuevo).eq(1).attr('name','campaign_feat_woh['+ newval +']');
					$('select', nuevo).eq(2).attr('name','campaign_feat_mol['+ newval +']');
					$('input', nuevo).eq(0).attr('name','campaign_feat_value['+ newval +']');
					//$('select', nuevo).eq(0).val('');
					$('input', nuevo).eq(0).val('');
					nuevo.show();
					$('#featimgfilt_edit').append(nuevo);
				});
			
			<?php if($cfg['enablecfields']) : ?>
				$('#addmorecf').click(function() {
					$('#cfield_max').val( parseInt($('#cfield_max').val(),10) + 1 );
					newval = $('#cfield_max').val();					
					nuevo= $('#nuevocfield').clone();
					$('input', nuevo).eq(0).attr('name','campaign_cf_name['+ newval +']');
					$('input', nuevo).eq(1).attr('name','campaign_cf_value['+ newval +']');
					$('input', nuevo).eq(0).val('');
					$('input', nuevo).eq(1).val('');
					nuevo.show();
					$('#cfield_edit').append(nuevo);
				});
			<?php endif; ?>
				
				$('.tagcf').click(function(){
					lastval = $('#cfield_max').val();
					cval = $('input[name="campaign_cf_value['+ lastval +']"]').val();
					$('input[name="campaign_cf_value['+ lastval +']"]').val(cval+ $(this).html());
				});
				
				$('#campaign_autotags').click(function() {
					if ( false == $('#campaign_autotags').is(':checked')) {
						$('#manualtags').fadeIn();
						$('#badtags').fadeOut();
					} else {
						$('#manualtags').fadeOut();
						$('#badtags').fadeIn();
					}
				});
			});
			</script>
			<?php 
		}
		
		//*************************************************************************************
		static function feedlist() { // part of feeds metabox for import
			global $post, $campaign_data, $helptip;
			$cfg = get_option( self :: OPTION_KEY);
			if ($cfg['enableimportfeed']) :   ?>
				<div id="blocktxt_feedlist" class="hide">
					<p class="he20">
					<span class="left"><?php _e('Type or paste a list of urls, authors.  When update the campaign, the list will be imported as campaign feeds', WPeMatico :: TEXTDOMAIN ) ?></span> 
					<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['import_feed_list']; ?>"></span>
					</p>		
					
					<div id="wpe_post_template_edit" class="inlinetext">
						<textarea class="large-text" rows=7 id="txt_feedlist" name="txt_feedlist" /></textarea>
					</div>
				</div> <?php
		endif;
		}
		static function bimport(){
			$cfg = get_option( self :: OPTION_KEY);
			if($cfg['enableimportfeed']) : ?>
				<span class="button-primary" id="bimport" style="font-weight: bold; text-decoration: none;" > <?php _e('Import feed list', WPeMatico :: TEXTDOMAIN ); ?></span>
			<?php endif;
		}	
		
		//*************************************************************************************
		static function google_permalinks_option( $campaign_data, $cfgbasic ) { 
			global $post, $campaign_data, $helptip;
			$fix_google_links = $campaign_data['fix_google_links'];
//			$cfg = get_option( self :: OPTION_KEY);
			?>
		<p>
			<input class="checkbox" type="checkbox"<?php checked($fix_google_links ,true);?> name="fix_google_links" value="1" id="fix_google_links"/> 
			<label for="fix_google_links"><?php echo __('Sanitize Googlo News permalink.', WPeMatico :: TEXTDOMAIN ); ?></label>
			<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['fix_google_links']; ?>"></span>
		</p>
		<?php
		}

		//*************************************************************************************
		//*************************************************************************************
		static function headerfeedat( ) { 
			echo '<div style="display: inline-block;" class="author_column">' . __('Author', WPeMatico :: TEXTDOMAIN ) . '</div>';  
		}

		static function feedat( $feed, $cfgbasic ) { // part of basic feed every line metabox
			global $post, $campaign_data;
			@$feed_author = ($feed=="") ? "-1" : $campaign_data[$feed]['feed_author'];

			$feedauthorargs = array(
							'show_option_none' => __('Use campaign Author', WPeMatico :: TEXTDOMAIN ),
							'show_option_all' => __('Use feed Author', WPeMatico :: TEXTDOMAIN ),
							'name' => 'feed_author[]',
							'selected' => $feed_author 
						 );
			wp_dropdown_users($feedauthorargs); 
		}

		//*************************************************************************************
		static function multifeed_urls($feed, $kf, $campaign) {
			$is_multipagefeed = (!isset($campaign['is_multipagefeed'][$kf])?false:$campaign['is_multipagefeed'][$kf]);
			$multifeed_maxpages = (!isset($campaign['multifeed_maxpages'][$kf])?1:$campaign['multifeed_maxpages'][$kf]);
			if ($is_multipagefeed && !is_array($feed)) {
				if ($multifeed_maxpages > 1) {
					$array_urls = array();
					$array_urls[] = $feed;
				
					for($p = 2; $p<=$multifeed_maxpages; $p++) {
						$array_urls[] = add_query_arg('paged', $p, $feed );
					}
					return $array_urls;
				}
				
			}
			return $feed;
		}

		static function multifeedfields( $feed, $campaign_data, $cfgbasic, $key ) { 
			global $post;
			$is_multipagefeed = (!isset($campaign_data['is_multipagefeed'][$key])?false:$campaign_data['is_multipagefeed'][$key]);
			$multifeed_maxpages = (!isset($campaign_data['multifeed_maxpages'][$key])?1:$campaign_data['multifeed_maxpages'][$key]);
			?>
			<div id="ismultifeed" class="ibfix vtop">
			<p>
				<input class="is_multipagefeed checkbox" type="checkbox"<?php checked($is_multipagefeed ,true);?> name="is_multipagefeed[<?php echo $key; ?>]" value="1" id="is_multipagefeed_<?php echo $key; ?>"/>
				<label for="is_multipagefeed"><?php _e('Check to use as a multipage feed.', 'wpematico' ); ?></label><br/>
				<span class="description"><?php _e('This option allow to check multiple pages for feeds like https://etruel.com/feed/?paged=2.', 'wpematico' ); ?></span>
			</p>				
			<p>
				<input name="multifeed_maxpages[<?php echo $key; ?>]" type="number" min="0" size="3" value="<?php echo $multifeed_maxpages;?>" class="multifeed_maxpages small-text" id="multifeed_maxpages_<?php echo $key; ?>"/> 
				<label for="multifeed_maxpages"><?php echo __('Max pages to fetch.', 'wpematico' ); ?></label> <br/>
				<span class="description"><?php _e('You should change the field "Max items to create on each fetch" to a value = Max Pages * 10.', 'wpematico' ); ?></span><br/>
				<label class="description" onclick="jQuery('#campaign_max').val(jQuery('#multifeed_maxpages_<?php echo $key; ?>').val()*10);"><?php _e('Click here to fix it automatically.', 'wpematico' ); ?></label>
			</p>
			</div>				
			<?php
		}

		//*************************************************************************************
		static function is_multipage_icon( $feed, $cfgbasic, $key ) { // part of basic feed every line metabox
			global $post, $campaign_data;
			if( isset($campaign_data['is_multipagefeed'][$key]) && $campaign_data['is_multipagefeed'][$key] ) :
			?>
			<div style="display: inline-block;" class="multifeed_column">
				<span title="<?php _e('Is Multipage', 'wpematico' ); ?>" id="is_multifeed<?php echo $key; ?>" class="is_multifeedicon bicon two_thrid"></span>
			</div>
			<?php
			endif;
		}

		//*************************************************************************************
		static function advancedfeedicon( $feed, $cfgbasic, $key ) { // part of basic feed every line metabox
			global $post, $campaign_data;
			?>
			<div style="display: inline-block;" class="multifeed_column">
				<label title="<?php _e('Open Feed advanced Options', 'wpematico' ); ?>" id="feedoptions_<?php echo $key; ?>" class="feedoptionsicon bicon radioactive"></label>
			</div>
			<div id="modalopt_<?php echo $key; ?>" class="modal">
				<!-- Modal content -->
				<div class="modal-content">
				  <div class="modal-header">
					<span class="modal-close">&times;</span>
					<h3 style="background-color: transparent;"><?php _e('Feed Advanced Options', 'wpematico' ); ?>: <code><?php echo $feed; ?></code></h3>
				  </div>
				  <div class="modal-body">
					  <?php 
					  /**
					   * @param string $feed			Main feed URL 
					   * @param array  $campaign_data	All the campaign data
					   * @param array  $cfgbasic		Main core configuration options
					   * @param int	   $key				Feed key order in campaign 
					   */
						do_action('wpematico_campaign_feed_advanced_options', $feed, $campaign_data, $cfgbasic, $key); 
					  ?>
					<p></p>
				  </div>
				  <div class="modal-footer">
					  <span><b><?php _e('Close popup and save campaign to save the changes.', 'wpematico' ); ?></b></span>
				  </div>
				</div>
		  </div>
			<?php
		}

		//*************************************************************************************

		static function protags( $post ) {
			global $post, $campaign_data, $helptip;
			$cfg = get_option( self :: OPTION_KEY);
			$campaign_autotags = isset($campaign_data['campaign_autotags']) ? $campaign_data['campaign_autotags'] : $cfg['enabletags'];
			$campaign_badtags = @$campaign_data['campaign_badtags'];
			$campaign_tags_feeds = @$campaign_data['campaign_tags_feeds'];

			if ($cfg['enabletags']) { // Si está habilitado en settings, lo muestra y usa autotags
			?>
			<p><input class="checkbox" type="checkbox" <?php checked($campaign_tags_feeds,true);?> name="campaign_tags_feeds" value="1" id="campaign_tags_feeds"/><b><?php echo '<label for="campaign_tags_feeds">' . __('Use &lt;tag&gt; tags from feed if exist.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<small>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_tags_feeds']; ?>"></span>
				</p>


				<p><input class="checkbox" type="checkbox" <?php checked($campaign_autotags,true);?> name="campaign_autotags" value="1" id="campaign_autotags"/><b><?php echo '<label for="campaign_autotags">' . __('Auto generate tags', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<small>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_autotags']; ?>"></span>
				</p>
				<div id="manualtags" <?php if($campaign_autotags) echo 'style="display:none;"';?>>
			<?php
			}
		}	
			
		static function protags1( $post ) {
			global $post, $campaign_data, $helptip;
			$cfg = get_option( self :: OPTION_KEY);
			$campaign_autotags = isset($campaign_data['campaign_autotags']) ? $campaign_data['campaign_autotags'] : $cfg['enabletags'];
			$campaign_nrotags = @$campaign_data['campaign_nrotags'];
			$campaign_badtags = @$campaign_data['campaign_badtags'];
			
			if ($cfg['enabletags']) { // Si está habilitado en settings, lo muestra y usa autotags
			?>		
			</div>
			<div id="badtags" <?php if(!$campaign_autotags) echo 'style="display:none;"';?>>		
				


				<p><b><?php echo '<label for="campaign_nrotags">' . __('Limit tags quantity to:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<input style="" class="small-text" id="campaign_nrotags" name="campaign_nrotags" value="<?php echo stripslashes($campaign_nrotags); ?>" />
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_nrotags']; ?>"></span>
				</p>
				<p><b><?php echo '<label for="campaign_badtags">' . __('Bad Tags:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b><span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_badtags']; ?>"></span>
				<textarea style="" class="large-text" id="campaign_badtags" name="campaign_badtags"><?php echo stripslashes($campaign_badtags); ?></textarea><br />
				<?php echo __('Enter comma separated list of excluded Tags.', WPeMatico :: TEXTDOMAIN ); ?></p>
			</div>
			<?php
			}
		}

		//*************************************************************************************
		static function delete_from_phrase_box( $post, $cfgbasic ) { 
			global $post, $campaign_data, $helptip;
			
			$campaign_delfphrase = $campaign_data['campaign_delfphrase'];
			$campaign_delfphrase_keep = $campaign_data['campaign_delfphrase_keep'];
			//$cfg = get_option( self :: OPTION_KEY);
			?><hr style="border-color:#FFF;" />
			<div>
				<p class="he20">
					<b class="left"><?php _e('Delete all in the content AFTER a word or phrase till the end.',  WPeMatico :: TEXTDOMAIN ); ?></b>
					<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_delfphrase']; ?>"></span>
				</p>
			</div>
			
			
			<div class="" style="background: #eef1ff none repeat scroll 0% 0%;border: 2px solid #cee1ef;padding: 0.5em;">
				<label for="campaign_delfphrase"><b><?php _e('Phrases or keywords (one per line, case-insensitive):', WPeMatico :: TEXTDOMAIN ); ?></b></label><br />
				<textarea style="width: 50%; height: 70px;" class="regular-text" id="campaign_delfphrase" name="campaign_delfphrase"><?php echo stripslashes($campaign_delfphrase); ?></textarea><br />

				<p><label for="campaign_delfphrase_keep">
					<input class="checkbox" type="checkbox" <?php checked($campaign_delfphrase_keep,true);?> name="campaign_delfphrase_keep" value="1" id="campaign_delfphrase_keep"/>
					<b><?php _e('Keep phrase', WPeMatico :: TEXTDOMAIN ); ?></b></label>
					<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_delfphrase_keep']; ?>"></span>
				</p>
			</div>
			
			<div class="clear"></div>
			<?php
		}
		//*************************************************************************************
		static function last_html_tag( $post, $cfgbasic ) { // part of basic template metabox
			global $post, $campaign_data, $helptip;
			$campaign_lastag = @$campaign_data['campaign_lastag'];
			?><hr style="border-color:#FFF;" />
			<div>
				<p id="w1">
					<b><?php _e('Last HTML tag to remove', WPeMatico :: TEXTDOMAIN ); ?></b><span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_lastag_tag']; ?>"></span><br />
					<label for="campaign_lastag_tag"><?php _e('HTML tag:', WPeMatico :: TEXTDOMAIN ); ?></label>
					<b><</b><input type="text" size="6" class="small-text" id="campaign_lastag_tag" name="campaign_lastag_tag" value="<?php echo stripslashes($campaign_lastag['tag']); ?>" /><b>></b> <?php _e('(ex: div, p, span, etc.)', WPeMatico :: TEXTDOMAIN ); ?>
					
				</p>
			</div>
			<div class="clear"></div>
			<?php
		}
		//*************************************************************************************
		static function wcountf_box( $post ) { 
			global $post, $campaign_data, $helptip;
			//if(!is_array($campaign_data['campaign_wcf'])) $campaign_data['campaign_wcf'] = array();
			$campaign_wcf = @$campaign_data['campaign_wcf'];
			$cfg = get_option( self :: OPTION_KEY);
			?>
				<p class="he20">
				<span class="left"><?php _e('This allow you to ignore a post if below X words or letters in content.  Also allow assign a category to the post if greater than X words.',  WPeMatico :: TEXTDOMAIN ); ?></span>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['Word_Count_Filters']; ?>"></span>
				<ul id="wcf_edit" class="inlinetext">
				<li class="jobtype-select">
				<div id="w1" style="float:left;">
					<label for="campaign_wcf_great_amount"><b><?php _e('Greater than:', WPeMatico :: TEXTDOMAIN ); ?></b></label>
					<input type="number" min="0" size="5" class="small-text" id="campaign_wcf_great_amount" name="campaign_wcf_great_amount" value="<?php echo stripslashes($campaign_wcf['great_amount']); ?>" />
					<span id="gwol">
					<?php echo ($campaign_wcf['great_words']) ? __('words.', WPeMatico :: TEXTDOMAIN ) : __('letters.', WPeMatico :: TEXTDOMAIN );?> 
					</span>
					<br />
					<input name="campaign_wcf_great_words" id="campaign_wcf_great_words" class="checkbox chkgwol" value="1" type="checkbox"<?php checked($campaign_wcf['great_words'],true); ?> /><label for="campaign_wcf_great_words"> <?php _e('Words', WPeMatico :: TEXTDOMAIN ); ?></label>
				</div>
				<div id="c1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label for="campaign_wcf_category"> <?php _e('To Category:', WPeMatico :: TEXTDOMAIN ); ?></label>
				<?php 
					$catselected='selected='.$campaign_wcf['category'];
					$catname="name=campaign_wcf_category";
					$catid="id=campaign_wcf_category";
					wp_dropdown_categories('hide_empty=0&hierarchical=1&show_option_none='.__('Select category', WPeMatico :: TEXTDOMAIN ).'&'.$catselected.'&'.$catname.'&'.$catid);
				?>
				</div>
				</li>
				<?php // cut at if greater  ?>
				<li class="jobtype-select">
				<div id="w1" style="float:left;">
					<label for="campaign_wcf_cut_amount"><b><?php _e('Cut at:', WPeMatico :: TEXTDOMAIN ); ?></b></label>
					<input type="number" min="0" size="5" class="small-text" id="campaign_wcf_cut_amount" name="campaign_wcf_cut_amount" value="<?php echo stripslashes($campaign_wcf['cut_amount']); ?>" />
					<span id="cwol">
					<?php echo ($campaign_wcf['cut_words']) ? __('words.', WPeMatico :: TEXTDOMAIN ) : __('letters.', WPeMatico :: TEXTDOMAIN );?> 
					</span>
					<?php _e('if greater.', WPeMatico :: TEXTDOMAIN ); ?>
					<br />
					<input name="campaign_wcf_cut_words" id="campaign_wcf_cut_words" class="checkbox chkcwol" value="1" type="checkbox"<?php checked($campaign_wcf['cut_words'],true); ?> /><label for="campaign_wcf_cut_words"> <?php _e('Words', WPeMatico :: TEXTDOMAIN ); ?></label>
				</div>
				</li>
				<?php // Discard is less  ?>
				<li class="jobtype-select">
				<div id="w1" style="float:left;">
					<label for="campaign_wcf_less_amount"><b><?php _e('Discard post is less than:', WPeMatico :: TEXTDOMAIN ); ?></b></label>
					<input type="number" min="0" size="5" class="small-text" id="campaign_wcf_less_amount" name="campaign_wcf_less_amount" value="<?php echo stripslashes($campaign_wcf['less_amount']); ?>" />
					<span id="lwol">
					<?php echo ($campaign_wcf['less_words']) ? __('words.', WPeMatico :: TEXTDOMAIN ) : __('letters.', WPeMatico :: TEXTDOMAIN );?> 
					</span>
					<br />
					<input name="campaign_wcf_less_words" id="campaign_wcf_less_words" class="checkbox chklwol" value="1" type="checkbox"<?php checked($campaign_wcf['less_words'],true); ?> /><label for="campaign_wcf_less_words"> <?php _e('Words', WPeMatico :: TEXTDOMAIN ); ?></label>
				</div>
				</li>

				</ul>
			<?php
		}
		//*************************************************************************************
		static function kwordf_box( $post ) { 
			global $post, $campaign_data, $helptip;
			
			$campaign_kwordf = $campaign_data['campaign_kwordf'];
			$cfg = get_option( self :: OPTION_KEY);
			?>
			<p class="he20">
			<span class="left"><?php _e('Skip posts with words in content or words not in content.',  WPeMatico :: TEXTDOMAIN ); ?></span><span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['skip_posts_with_words']; ?>"></span>
			
			
			<div class="" style="background: #eef1ff none repeat scroll 0% 0%;border: 2px solid #cee1ef;padding: 0.5em;">
				<b><?php _e('Must contain', WPeMatico :: TEXTDOMAIN ); ?>:</b><br />
				<div style="padding: 0.5em;float:left;">
				<label><input name="campaign_kwordf_inc_tit" id="campaign_kwordf_inc_tit" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['inctit'],true); ?> /> <?php _e('Search in Title', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_inc_con" id="campaign_kwordf_inc_con" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['inccon'],true); ?> /> <?php _e('Search in Content', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_inc_cat" id="campaign_kwordf_inc_cat" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['inccat'],true); ?> /> <?php _e('Search in Categories', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_inc_anyall" id="campaign_kwordf_any" class="radio" value="anyword" type="radio" <?php checked("anyword"==$campaign_kwordf['inc_anyall'],true); ?> /> <?php _e('Any of these words', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_inc_anyall" id="campaign_kwordf_all" class="checkbox" value="allwords" type="radio"<?php checked("allwords"==$campaign_kwordf['inc_anyall'],true); ?> /> <?php _e('All of these words', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				</div>
				<label for="campaign_kwordf_inc"><?php _e('Words:', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<textarea style="width: 50%; height: 70px;" class="regular-text" id="campaign_kwordf_inc" name="campaign_kwordf_inc"><?php echo stripslashes($campaign_kwordf['inc']); ?></textarea><br />
				<label for="campaign_kwordf_incregex"><?php _e('RegEx:', WPeMatico :: TEXTDOMAIN ); ?></label>		
				<input class="regular-text" type="text" id="campaign_kwordf_incregex" name="campaign_kwordf_incregex" value="<?php echo stripslashes($campaign_kwordf['incregex']); ?>" />
			</div>
			<div class="" style="background: #eef1ff none repeat scroll 0% 0%;border: 2px solid #cee1ef;padding: 0.5em;">
				<b><?php _e('Cannot contain:', WPeMatico :: TEXTDOMAIN ); ?></b><br />
				<div style="padding: 0.5em;float:left;">
				<label><input name="campaign_kwordf_exc_tit" id="campaign_kwordf_exc_tit" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['exctit'],true); ?> /> <?php _e('Search in Title', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_exc_con" id="campaign_kwordf_exc_con" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['exccon'],true); ?> /> <?php _e('Search in Content', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_exc_cat" id="campaign_kwordf_exc_cat" class="checkbox" value="1" type="checkbox"<?php checked($campaign_kwordf['exccat'],true); ?> /> <?php _e('Search in Categories', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_exc_anyall" id="campaign_kwordf_any" class="radio" value="anyword" type="radio" <?php checked("anyword"==$campaign_kwordf['exc_anyall'],true); ?> /> <?php _e('Any of these words', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<label><input name="campaign_kwordf_exc_anyall" id="campaign_kwordf_all" class="checkbox" value="allwords" type="radio"<?php checked("allwords"==$campaign_kwordf['exc_anyall'],true); ?> /> <?php _e('All of these words', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				</div>
				<label for="campaign_kwordf_exc"><?php _e('Words:', WPeMatico :: TEXTDOMAIN ); ?></label><br />
				<textarea style="width: 50%; height: 70px;" class="regular-text" id="campaign_kwordf_exc" name="campaign_kwordf_exc"><?php echo stripslashes($campaign_kwordf['exc']); ?></textarea><br />
				<label for="campaign_kwordf_excregex"><?php _e('RegEx:', WPeMatico :: TEXTDOMAIN ); ?></label>		
				<input type="text" class="regular-text" id="campaign_kwordf_excregex" name="campaign_kwordf_excregex" value="<?php echo stripslashes($campaign_kwordf['excregex']); ?>" />				    
			</div>
			
			<div class="clear"></div>
			<?php
		}
		//*************************************************************************************
		static function custitle_box( $post ) {
			global $post, $campaign_data, $helptip;
			$campaign_striptagstitle = @$campaign_data['campaign_striptagstitle'];
			$campaign_enablecustomtitle = @$campaign_data['campaign_enablecustomtitle'];
			$campaign_customtitle = @$campaign_data['campaign_customtitle'];
			$campaign_ctitlecont = @$campaign_data['campaign_ctitlecont'];
			$campaign_custitdup = @$campaign_data['campaign_custitdup'];
			$campaign_ctdigits = @$campaign_data['campaign_ctdigits'];
			$campaign_ctnextnumber = @$campaign_data['campaign_ctnextnumber'];
			$cfg = get_option( self :: OPTION_KEY); 
			?><p><b>
			<?php echo '<label for="campaign_striptagstitle">' . __('Strip HTML Tags From Title', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b> <input class="checkbox" type="checkbox"<?php checked($campaign_striptagstitle,true);?> name="campaign_striptagstitle" value="1" id="campaign_striptagstitle"/>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_striptagstitle']; ?>"></span>
			</p>
			<p><b>
			<?php echo '<label for="campaign_enablecustomtitle">' . __('Enable Custom Post title', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b> <input class="checkbox" type="checkbox"<?php checked($campaign_enablecustomtitle,true);?> name="campaign_enablecustomtitle" value="1" id="campaign_enablecustomtitle"/> <span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_enablecustomtitle']; ?>"></span>
			</p>
			<div id="nocustitle" <?php if (!$campaign_enablecustomtitle) echo 'style="display:none;"';?>>					
			<p><b><?php echo '<label for="campaign_customtitle">' . __('Custom Title for every post:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			<input name="campaign_customtitle" type="text" size="3" value="<?php echo $campaign_customtitle;?>" class="regular-text" id="campaign_customtitle"/><br />
			<?php _e("You can write here the title for every post. All posts will be named with this field.",  WPeMatico :: TEXTDOMAIN ); ?><br />
			<?php _e("Now you can use {title} and {counter} and will be replaced on title.",  WPeMatico :: TEXTDOMAIN ); ?><br />
			<small><?php _e("If you don't use {counter} and checked the box below, by default the counter is added to end of title.",  WPeMatico :: TEXTDOMAIN ); ?></small>
			<br />
			<?php _e("Ex: 'New Post: {title}'",  WPeMatico :: TEXTDOMAIN ); ?>
			</p>
			<p>
				<input class="checkbox" type="checkbox"<?php checked($campaign_custitdup, true);?> name="campaign_custitdup" value="1" id="campaign_custitdup"/>
				<b><?php echo '<label for="campaign_custitdup">' . __('Add an extra filter to check duplicates by Custom Post title', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			</p>
			<p>
				<input class="checkbox" type="checkbox"<?php checked($campaign_ctitlecont,true);?> name="campaign_ctitlecont" value="1" id="campaign_ctitlecont"/>
				<b><?php echo '<label for="campaign_ctitlecont">' . __('Add counter to Post title', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			<div id="ctnocont" <?php if (!$campaign_ctitlecont) echo 'style="display:none;"';?>>	
				<b><?php echo '<label for="campaign_ctdigits">' . __('Min. Counter Digits:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<select id="campaign_ctdigits" name="campaign_ctdigits" onchange="LCeros(getElementById('campaign_ctnextnumber'), this.value);" >
					<option value="1" <?php echo ($campaign_ctdigits=="1") ? 'SELECTED' : ''; ?> > 1</option>
					<option value="2" <?php echo ($campaign_ctdigits=="2" || $campaign_ctdigits=="") ? 'SELECTED' : ''; ?> > 2</option>
					<option value="3" <?php echo ($campaign_ctdigits=="3") ? 'SELECTED' : ''; ?> > 3</option>
					<option value="4" <?php echo ($campaign_ctdigits=="4") ? 'SELECTED' : ''; ?> > 4</option>
					<option value="5" <?php echo ($campaign_ctdigits=="5") ? 'SELECTED' : ''; ?> > 5</option>
					<option value="6" <?php echo ($campaign_ctdigits=="6") ? 'SELECTED' : ''; ?> > 6</option>
					<option value="7" <?php echo ($campaign_ctdigits=="7") ? 'SELECTED' : ''; ?> > 7</option>
					<option value="8" <?php echo ($campaign_ctdigits=="8") ? 'SELECTED' : ''; ?> > 8</option>
					<option value="9" <?php echo ($campaign_ctdigits=="9") ? 'SELECTED' : ''; ?> > 9</option>
				</select>
				<b><?php echo '<label for="campaign_ctnextnumber">' . __('Next Number:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<input name="campaign_ctnextnumber" type="text" size="3" value="<?php echo sprintf("%0".$campaign_ctdigits."d",$campaign_ctnextnumber);?>" class="small-text" id="campaign_ctnextnumber" onblur="LCeros(this,getElementById('campaign_ctdigits').value);" style="width: 80px;"/></p>
			</div>
		</div>
		<?php
		}
			

		static function cfields_box( $post ) {
			global $post, $campaign_data, $helptip;
			$campaign_cfields = $campaign_data['campaign_cfields'];
			if(!($campaign_cfields)) $campaign_cfields = array('name'=>array(''),'value'=>array(''));
			?>
			<p class="he20">
				<span class="left"><?php _e('Add custom fields with values as templates.', WPeMatico :: TEXTDOMAIN ) ?></span> 
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['Custom_Fields']; ?>"></span>
			</p>		
			
			<div id="cfield_edit" class="inlinetext">		
				<?php for ($i = 0; $i <= count(@$campaign_cfields['name']); $i++) : ?>			
				<div class="<?php if(($i % 2) == 0) echo 'bw'; else echo 'lightblue'; ?> <?php if($i==count($campaign_cfields['name'])) echo 'hide'; ?>">
					<div class="clear pDiv jobtype-select rowflex" id="nuevocfield">
						<div id="cf1" class="rowblock left p4" style="width: 45%;">
							<?php _e('Name:','wpematico') ?>&nbsp;&nbsp;&nbsp;&nbsp; 
							<input name="campaign_cf_name[<?php echo $i; ?>]" type="text" value="<?php echo stripslashes(@$campaign_cfields['name'][$i]) ?>" class="large-text" id="campaign_cf_name" />
						</div>
						<div class="rowblock left p4" style="width: 45%;">
							 <?php _e('Value:','wpematico') ?>
							<input name="campaign_cf_value[<?php echo $i; ?>]" type="text" value="<?php echo stripslashes(@$campaign_cfields['value'][$i]) ?>" class="large-text" id="campaign_cf_value" />
						</div>
						<div class="rowactions">
							<span class="" id="w2cactions">
								<label title="<?php _e('Delete this item', WPeMatico :: TEXTDOMAIN ); ?>" onclick=" jQuery(this).parent().parent().parent().children('#cf1').children('#campaign_cf_name').val(''); jQuery(this).parent().parent().parent().fadeOut();" class="bicon delete left"></label>
							</span>
						</div>
					</div>
				</div>
				<?php $a=$i;endfor ?>
				<input id="cfield_max" value="<?php echo $a; ?>" type="hidden" name="cfield_max">
				
			  </div>
			<div class="clear"></div>
			  <div id="paging-box" class="clear">		  
					<a href="JavaScript:void(0);" class="button-primary add" id="addmorecf" style="font-weight: bold; text-decoration: none;"> <?php _e('Add more', WPeMatico :: TEXTDOMAIN ); ?>.</a>
			  </div>

			<?php 
		}

		static function proimages_box( $post ) {
			global $post, $campaign_data, $helptip;
			$default_img = @$campaign_data['default_img'];
			$default_img_url = @$campaign_data['default_img_url'];
			$default_img_link = @$campaign_data['default_img_link'];
			$default_img_title = @$campaign_data['default_img_title'];
			$campaign_rssimg = @$campaign_data['campaign_rssimg'];
			$strip_all_images = @$campaign_data['strip_all_images'];
			$overwrite_image = @$campaign_data['overwrite_image'];
			$clean_images_urls = @$campaign_data['clean_images_urls'];
			$image_src_gettype = @$campaign_data['image_src_gettype'];
			$discardifnoimage = @$campaign_data['discardifnoimage'];
			$rssimg_enclosure = @$campaign_data['rssimg_enclosure'];
			$rssimg_ifno = @$campaign_data['rssimg_ifno'];
			$rssimg_add2img = @$campaign_data['rssimg_add2img'];
			$add1stimg = @$campaign_data['add1stimg'];
			$rssimg_featured = @$campaign_data['rssimg_featured'];
			$which_featured = (!isset($campaign_data['which_featured'])) ? 'content1' : $campaign_data['which_featured'];
			$cfg = get_option( self :: OPTION_KEY);
			$cfgbasic = get_option( 'WPeMatico_Options' );
			$cfgbasic['customupload'] = (!isset($cfgbasic['customupload'])? false : $cfgbasic['customupload'] == true ? true : false );

			if($cfgbasic['customupload']) : ?>
				<p><b><?php _e('Determine what happens with duplicated image names',  'wpematico' ); ?></b></p>
					<div id="whatimgren" style="margin-left: 20px;">
					<label><input type="radio" name="overwrite_image" <?php echo checked('rename',$overwrite_image,false); ?> value="rename" /> <?php _e('Rename like Wordpress standards (name-1)'); ?></label><br />
					<label><input type="radio" name="overwrite_image" <?php echo checked('overwrite',$overwrite_image,false); ?> value="overwrite" /> <?php _e('Always Overwrite'); ?></label><br />
					<label><input type="radio" name="overwrite_image" <?php echo checked('keep',$overwrite_image,false); ?> value="keep" /> <?php _e('Always keep the first. Recommended.'); ?></label><br />
					</div>
				<p></p>
			<?php endif; ?>
			<input class="checkbox" type="checkbox"<?php checked($clean_images_urls,true);?> name="clean_images_urls" value="1" id="clean_images_urls"/> <b><?php echo '<label for="clean_images_urls">' . __('Strip the queries variables in images URls.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['clean_images_urls']; ?>"></span><br/>
			<input class="checkbox" type="checkbox"<?php checked($image_src_gettype,true);?> name="image_src_gettype" value="1" id="image_src_gettype"/> <b><?php echo '<label for="image_src_gettype">' . __('Check the source image to determine the extension.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
			<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['image_src_gettype']; ?>"></span>
			<?php	
			// **************************** Images Renamer *******************************
			if ($cfg['enableimgrename']) :  // Si está habilitado en settings, lo muestra 
				$campaign_enableimgrename = @$campaign_data['campaign_enableimgrename'];
				$campaign_imgrename = @$campaign_data['campaign_imgrename'];
				?><p></p>
				<div id="imagerenamer"  class="inmetabox" style="background-color: #fffe9e;">
					<input class="checkbox" type="checkbox"<?php checked($campaign_enableimgrename,true);?> name="campaign_enableimgrename" value="1" id="campaign_enableimgrename"/> 
					<label for="campaign_enableimgrename"><b><?php _e('Enable Image Renamer', WPeMatico :: TEXTDOMAIN ); ?></b></label>
					<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['campaign_enableimgrename']; ?>"></span>
					<div id="noimgren" style="margin-left: 20px;<?php if (!$campaign_enableimgrename) echo 'display:none;';?>">
						<b><label for="campaign_imgrename"><?php _e('Rename the images to', WPeMatico :: TEXTDOMAIN ); ?>:</label></b>
						<input name="campaign_imgrename" type="text" size="3" value="<?php echo $campaign_imgrename;?>" class="regular-text" id="campaign_imgrename"/><br />
						<p class="description"><?php _e("Don't complete the extension of the file. This field is used to change the name and remains the same extension.",  WPeMatico :: TEXTDOMAIN ); ?><br />
						<?php printf( __("You can use %1s or %1s and will be replaced on uploading the image. Wordpress adds a number at the end if the image name already exists.",  WPeMatico :: TEXTDOMAIN ),
									'<a href="JavaScript:void(0);" onclick="jQuery(\'#campaign_imgrename\').val( jQuery(\'#campaign_imgrename\').val()+jQuery(this).text() );">{title}</a>',
									'<a href="JavaScript:void(0);" onclick="jQuery(\'#campaign_imgrename\').val( jQuery(\'#campaign_imgrename\').val()+jQuery(this).text() );">{slug}</a>'
							  ); 
						?>
						</p>
					</div>
				</div><?php
			endif; //enableimgrenamer	?>
				
			<h3 class="subsection"><?php _e('From feed items','wpematico'); ?></h3>
			
			<p><input class="checkbox" type="checkbox"<?php checked($campaign_rssimg,true);?> name="campaign_rssimg" value="1" id="campaign_rssimg"/> <b><?php echo '<label for="campaign_rssimg">' . __('Get also Images from RSS', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b></p>
			<div class="rssimg_opt" style="padding-left:20px; <?php if (!$campaign_rssimg) echo 'display:none;';?>">
				<input class="checkbox" type="checkbox"<?php checked($rssimg_enclosure,true);?> name="rssimg_enclosure" value="1" id="rssimg_enclosure"/> <b><label for="rssimg_enclosure"> <?php _e('Also enclosure and media RSS tags.', WPeMatico :: TEXTDOMAIN ); ?></b></label>
				<br />
				<input class="checkbox" type="checkbox"<?php checked($rssimg_ifno,true);?> name="rssimg_ifno" value="1" id="rssimg_ifno"/> <b><label for="rssimg_ifno"> <?php _e('Only if no images on content.', WPeMatico :: TEXTDOMAIN ); ?></b></label>
				<br />
				<input class="checkbox" type="checkbox"<?php checked($rssimg_add2img,true);?> name="rssimg_add2img" value="1" id="rssimg_add2img"/> <label for="rssimg_add2img"><b> <?php _e('Make featured RSS image.', WPeMatico :: TEXTDOMAIN ); ?></b></label>
			</div>
			<p></p>

			<h3 class="subsection"><?php _e('From Content','wpematico'); ?></h3>
			<p><input class="checkbox" type="checkbox"<?php checked($strip_all_images,true);?> name="strip_all_images" value="1" id="strip_all_images"/> <b><?php echo '<label for="strip_all_images">' . __('Strip All Images from Content.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['strip_all_images']; ?>"></span>
			</p>
			
			<div id="noimages" <?php if ($strip_all_images) echo 'style="display:none;"';?>>
				<input class="checkbox" type="checkbox"<?php checked($discardifnoimage,true);?> name="discardifnoimage" value="1" id="discardifnoimage"/> <b><?php echo '<label for="discardifnoimage">' . __('Discard the Post if NO Images in Content.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['discardifnoimage']; ?>"></span>	<br/>	
				<input name="add1stimg" id="add1stimg" class="checkbox" value="1" type="checkbox" <?php checked($add1stimg,true); ?> /> <b><?php echo '<label for="add1stimg">' . __('Add featured image at the beginning of the post content.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b>	
				<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['add1stimg']; ?>"></span>
			<?php	
			// **************************** Images filters by dimensions *******************************
			if ($cfg['enableimgfilter']) :  // Si está habilitado en settings, lo muestra 
				@$imagefilters = $campaign_data['imagefilters'];
				if(!($imagefilters)) $imagefilters = array('allow'=>array(''),'woh'=>array(''),'mol'=>array(''),'value'=>array(''));
				?><p></p>
				<div class="lavender inmetabox">
					<p class="he20">
					<b><span class="left"><?php _e('Add filters by dimensions of images.', WPeMatico :: TEXTDOMAIN ) ?></span></b>
					<span class="mya4_sprite infoIco help_tip" title="<?php echo $helptip['filters_by_dimensions_of_images']; ?>"></span>
					<div id="imgfilt_edit" class="inlinetext">		
						<?php for ($i = 0; $i <= count(@$imagefilters['value']); $i++) : ?>
						<div class="<?php if(($i % 2) == 0) echo 'bw'; else echo 'lightblue'; ?> <?php if($i==count($imagefilters['value'])) echo 'hide'; ?>">
							<div class="pDiv jobtype-select p7" id="nuevoimgfilt">
								<div class="left p4">
									<b><?php echo '<label for="campaign_if_allow_'. $i .'">' . __('Filter:', WPeMatico :: TEXTDOMAIN ) . '</label>&nbsp;&nbsp;&nbsp;&nbsp; '; ?></b>
									<select id="campaign_if_allow_<?php echo $i; ?>" name="campaign_if_allow[<?php echo $i; ?>]">
										<option value="Allow" <?php echo ($imagefilters['allow'][$i]=="Allow" || $imagefilters['allow'][$i]=="") ? 'SELECTED' : ''; ?> > Allow</option>
										<option value="Skip" <?php echo ($imagefilters['allow'][$i]=="Skip") ? 'SELECTED' : ''; ?> > Skip</option>
									</select>						
								</div>
								<div class="left p4">
									<select id="campaign_if_woh_<?php echo $i; ?>" name="campaign_if_woh[<?php echo $i; ?>]">
										<option value="width" <?php echo ($imagefilters['woh'][$i]=="width" || $imagefilters['woh'][$i]=="") ? 'SELECTED' : ''; ?> > width</option>
										<option value="height" <?php echo ($imagefilters['woh'][$i]=="height") ? 'SELECTED' : ''; ?> > height</option>
									</select>						
								</div>
								<div class="left p4">
									<select id="campaign_if_mol_<?php echo $i; ?>" name="campaign_if_mol[<?php echo $i; ?>]">
										<option value="more" <?php echo ($imagefilters['mol'][$i]=="more" || $imagefilters['mol'][$i]=="") ? 'SELECTED' : ''; ?> > more</option>
										<option value="less" <?php echo ($imagefilters['mol'][$i]=="less") ? 'SELECTED' : ''; ?> > less</option>
									</select>						
								</div>
								<div id="cf1" class="left p4">
									 <?php _e('size:','wpematico') ?><input name="campaign_if_value[<?php echo $i; ?>]" type="text" value="<?php echo stripslashes(@$imagefilters['value'][$i]) ?>" class="normal-text" id="campaign_if_value" /> pixels
								</div>
								<div class="m7">
									<span class="" id="w2cactions">
										<label title="<?php _e('Delete this item', WPeMatico :: TEXTDOMAIN ); ?>" onclick=" jQuery(this).parent().parent().parent().children('#cf1').children('#campaign_if_value').val(''); jQuery(this).parent().parent().parent().fadeOut();" class="right ui-icon redx_circle"></label>
									</span>
								</div>
							</div>
						</div>
						<?php $a=$i;endfor ?>
						<input id="imgfilt_max" value="<?php echo $a; ?>" type="hidden" name="imgfilt_max">

					</div>
					<div class="clear"></div>
					<div id="paging-box">		  
						<a href="JavaScript:void(0);" class="button-primary left m4" id="addmoreimgf" style="font-weight: bold; text-decoration: none;"><?php _e('Add more', WPeMatico :: TEXTDOMAIN ); ?>.</a>
					</div>
				</div>
				<?php
				endif; //enableimgfilter
				?><br />
			</div>
		<?php
		// **************************** FEATURED Images Parsers  *******************************
			?><p></p>
			<h3 class="subsection leftText"><?php _e('Parsers for Featured image', WPeMatico :: TEXTDOMAIN ); ?></h3>
			<p></p>
			<div class="inmetabox" style="background-color: #F9F2B5;">
				<p><input name="default_img" id="default_img" class="checkbox" value="1" type="checkbox" <?php checked($default_img,true); ?> /> <b><?php echo '<label for="default_img">' . __('Default Featured image if not found image on content.', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b></p>
				<table class="form-table-tf" id="tblupload" style="padding-left:20px; <?php if (!$default_img) echo 'display:none;';?>">
				<?php //for($id = 1; $id <= 1 ; $id++) :  ?>
				  <tr>
					<th scope="row" style="line-height: 26px;"><?php  _e('Image URL:', WPeMatico :: TEXTDOMAIN ); ?> <!-- br /><?php  _e('Link:', WPeMatico :: TEXTDOMAIN ); ?> <br /><?php  _e('Title:', WPeMatico :: TEXTDOMAIN ); ?> --></th>
					<td><label for="default_img_url">
					  <input type="text" class="regular-text" name="default_img_url" id="default_img_url" value="<?php echo $default_img_url; ?>" />
					  <input id="upload_image_button" class="et_upload_button" type="button" value="<?php  _e('Upload image', WPeMatico :: TEXTDOMAIN ); ?>" />
					  <?php // ********** no se ve ?>
					  <input type="hidden" class="regular-text" name="default_img_link" id="default_img_link" value="<?php echo $default_img_link; ?>" /> 
					  <input type="hidden" class="regular-text" name="default_img_title" id="default_img_title" value="<?php echo $default_img_title; ?>" />
					  <?php // ********* no se ve ?>
					  </label>
					</td>
				  </tr>
				<?php //endfor; ?>
				</table>
				<br />
				<b><label><?php echo __('Only allow first Featured image that meets the following filters.', WPeMatico :: TEXTDOMAIN ); ?></label></b>
			<?php
				@$featimgfilters = $campaign_data['featimgfilters'];
				if(!($featimgfilters)) $featimgfilters = array('allow'=>array(''),'woh'=>array(''),'mol'=>array(''),'value'=>array('')); 
			?>
				<div id="featimgfilt_edit" class="inlinetext">		
					<?php for ($i = 0; $i <= count(@$featimgfilters['value']); $i++) : ?>
					<div class="<?php if(($i % 2) == 0) echo 'bw'; else echo 'lightblue'; ?> <?php if($i==count($featimgfilters['value'])) echo 'hide'; ?>">
						<div class="pDiv jobtype-select p7" id="nuevofeatimgfilt">
							<div class="left p4">
								<b><?php echo '<label for="campaign_feat_allow_'. $i .'">' . __('Filter:', WPeMatico :: TEXTDOMAIN ) . '</label>&nbsp;&nbsp;&nbsp;&nbsp; '; ?></b>
								<select id="campaign_feat_allow_<?php echo $i; ?>" name="campaign_feat_allow[<?php echo $i; ?>]">
									<option value="Allow" <?php echo ($featimgfilters['allow'][$i]=="Allow" || $featimgfilters['allow'][$i]=="") ? 'SELECTED' : ''; ?> > Allow</option>
									<option value="Skip" <?php echo ($featimgfilters['allow'][$i]=="Skip") ? 'SELECTED' : ''; ?> > Skip</option>
								</select>						
							</div>
							<div class="left p4">
								<select id="campaign_feat_woh_<?php echo $i; ?>" name="campaign_feat_woh[<?php echo $i; ?>]">
									<option value="width" <?php echo ($featimgfilters['woh'][$i]=="width" || $featimgfilters['woh'][$i]=="") ? 'SELECTED' : ''; ?> > width</option>
									<option value="height" <?php echo ($featimgfilters['woh'][$i]=="height") ? 'SELECTED' : ''; ?> > height</option>
								</select>						
							</div>
							<div class="left p4">
								<select id="campaign_feat_mol_<?php echo $i; ?>" name="campaign_feat_mol[<?php echo $i; ?>]">
									<option value="more" <?php echo ($featimgfilters['mol'][$i]=="more" || $featimgfilters['mol'][$i]=="") ? 'SELECTED' : ''; ?> > more</option>
									<option value="less" <?php echo ($featimgfilters['mol'][$i]=="less") ? 'SELECTED' : ''; ?> > less</option>
								</select>						
							</div>
							<div id="cf1" class="left p4">
								 <?php _e('size:','wpematico') ?><input name="campaign_feat_value[<?php echo $i; ?>]" type="text" value="<?php echo stripslashes(@$featimgfilters['value'][$i]) ?>" class="normal-text" id="campaign_feat_value" /> pixels
							</div>
							<div class="m7">
								<span class="" id="w2cactions">
									<label title="<?php _e('Delete this item', WPeMatico :: TEXTDOMAIN ); ?>" onclick=" jQuery(this).parent().parent().parent().children('#cf1').children('#campaign_feat_value').val(''); jQuery(this).parent().parent().parent().fadeOut();" class="right ui-icon redx_circle"></label>
								</span>
							</div>
						</div>
					</div>
					<?php $a=$i;endfor ?>
					<input id="featimgfilt_max" value="<?php echo $a; ?>" type="hidden" name="featimgfilt_max">
					
				</div>
				<div class="clear"></div>
				<div id="paging-box">		  
					<a href="JavaScript:void(0);" class="button-primary left m4" id="addmorefeatimgf" style="font-weight: bold; text-decoration: none;"><?php _e('Add more', WPeMatico :: TEXTDOMAIN ); ?>.</a>
				 </div>
			</div>
			<?php
		}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// RUNNING FETCHING
	//* Suma la cantidad de post al nro de titulo
	static public function ending($campaign, $fetched_posts ) {
		if( isset($campaign['campaign_enablecustomtitle']) && $campaign['campaign_enablecustomtitle'] ) {		
			$campaign['campaign_ctnextnumber'] += $fetched_posts;
		}
		return $campaign;
	}

	//* Return TRUE if skip the item 
	static public function exclfilters($skip, $current_item, $campaign, $item ) {
		$cfg = get_option( self :: OPTION_KEY);
		if (@$cfg['enablekwordf']) {
			$campaign_kwordfinc=(isset($campaign['campaign_kwordf']['inc']) && !empty($campaign['campaign_kwordf']['inc']) ) ? true : false;
			$campaign_kwordfexc=(isset($campaign['campaign_kwordf']['exc']) && !empty($campaign['campaign_kwordf']['exc']) ) ? true : false;
			if ($campaign_kwordfinc || $campaign_kwordfexc ) {
				trigger_error(sprintf(__('Processing Keyword filtering %1s','wpematico'),$item->get_title()),E_USER_NOTICE);
				if(! self :: KeywordFilter($current_item, $campaign, $item )) {
					$skip = true;
				}
			}
		}
		return $skip;
	}

	// Item author
	static public function author($current_item, $campaign, $feed, $item ) {
		$cfg = get_option( self :: OPTION_KEY);
		if ( isset($cfg['enableauthorxfeed']) && $cfg['enableauthorxfeed'] ) {
			if( $campaign[$feed]['feed_author'] > "0" ){
				$current_item['author'] = $campaign[$feed]['feed_author'];
			} else if ($campaign[$feed]['feed_author'] == "0") {
				$fauthor = $item->get_author();
				if (!empty($fauthor)) {
					$feed_name_author = '';
					if (!empty($fauthor->name)) {
						$feed_name_author = $fauthor->name;
					}
					if (!empty($fauthor->email) && empty($feed_name_author)) {
						$feed_name_author = $fauthor->email;
					}

					if (!empty($feed_name_author)) {
						$args= array(
					  		'search' => $feed_name_author, 
					  		'search_fields' => array('user_login','user_nicename','display_name')
						);
						$user_query = new WP_User_Query($args);
						$user_result = $user_query->get_results();
						if (empty($user_result)) {
							$userdata = array(
								//Filter to allow an external parser for the author name 
							    'user_login'  => apply_filters('wpempro_feed_name_author', $feed_name_author),
							    'user_pass'   =>  md5($feed_name_author.time()),
							    'display_name'=>  $feed_name_author,
							    'role'		  => 'author',
							);
							$user_id = wp_insert_user($userdata) ;
							if (!is_wp_error($user_id)) {
							    $current_item['author'] = $user_id;
							}
						} else {
							if (isset($user_result[0]->data->ID)) {
								$current_item['author'] = $user_result[0]->data->ID;
							}
							
						}
						
					}
					
				}
			}
		}
		trigger_error(sprintf(__('Assigning author %1s to %2s','wpematico'),$current_item['author'],$current_item['title']),E_USER_NOTICE);
		return $current_item;
	}
	
	//static function googlenewslink($permalink) {
	static function wpematico_googlenewslink($permalink) {
	// si es de google news feed toma del enlace destino con la variable &url=
		$urlparsed= parse_url($permalink );
		if(isset($urlparsed['query'] ) && !empty($urlparsed['query'] ) ) {
			parse_str($urlparsed['query']); 
			if(isset($url))
				if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) { 		
				}else {
					$permalink = $url;
				}
		}		
		return $permalink;
	}
	
	static function assign_custom_taxonomies($post_id, $campaign) {
		// copy taxonomies from campaign to new post, skip categories, tags and post formats 
		$taxonomiesNewPost = get_object_taxonomies($campaign['campaign_customposttype']);
		$taxonomiesNewPost = array_diff($taxonomiesNewPost, array('category', 'post_tag', 'post_format'));
		foreach($taxonomiesNewPost AS $tax) {
		    $terms = wp_get_object_terms( $campaign['ID'], $tax );
		    $term = array();
			$taxstr ='';
		    foreach( $terms AS $t ) {
		        $term[] = $t->slug;
				$taxstr .= ', '. $t->slug;
		    }
			$taxstr = substr($taxstr, 2);
			trigger_error(sprintf(__('Assigning Taxonomies %1s','wpematico'), $taxstr),E_USER_NOTICE);
		    wp_set_object_terms( $post_id, $term, $tax );
		}
	}
	
	// Item title
	static public function title( $current_item, $campaign, $item, $count ) {
		$cfg = get_option( self :: OPTION_KEY);
		$enablecustomtitle = ( isset($cfg['enablecustomtitle']) && $cfg['enablecustomtitle'] );
		$campaign_enablecustomtitle = ( isset($campaign['campaign_enablecustomtitle']) && $campaign['campaign_enablecustomtitle'] );
		if ( $enablecustomtitle && $campaign_enablecustomtitle ) {
			// miro si está y reemplazo la palabra {title}
			$vars = array('{title}');
			$replace = array( esc_attr($item->get_title()) );
			$current_item['title'] = str_ireplace($vars, $replace, $campaign['campaign_customtitle'] );
			//$current_item['title'] = $campaign['campaign_customtitle'];
			if ($campaign['campaign_ctitlecont']) {
				// si encuentra {counter} en el campo lo reemplaza por el contador, sino lo agrega al final
				$counter = sprintf("%0".$campaign['campaign_ctdigits']."d", ($count + (int)$campaign['campaign_ctnextnumber']) );
				$pos = strpos($current_item['title'], '{counter}');
				if ($pos !== false) {
					$current_item['title'] = str_ireplace('{counter}', $counter, $current_item['title'] );
				}else{
					$current_item['title'] = $current_item['title'] . $counter;
				}
			}
		}else{
			$current_item['title'] = esc_attr($item->get_title());
		}
		trigger_error(sprintf(__('Changing title to %1s','wpematico'),$current_item['title']),E_USER_NOTICE);
		return $current_item;
	}
	

	// Discard post is less than    $current_item, $campaign, $feed, $item
	public static function discardwordcountless( $current_item, $campaign, $feed, $item  ) {
		if($current_item == -1) return -1;
		$cfg = get_option( self :: OPTION_KEY);
		if( isset($cfg['enablewcf']) && $cfg['enablewcf'] ) {
			trigger_error(sprintf(__('Counting Words on %1s','wpematico'),$current_item['title'] ),E_USER_NOTICE);
			$words = self :: wordCount($current_item['content']);
			$letters = strlen($current_item['content']);
			trigger_error(sprintf(__('Found %1s words with %2s letters in content.','wpematico'),$words,$letters),E_USER_NOTICE);

			// skiping
			$mustcant = $campaign['campaign_wcf']['less_amount'];
			if ($mustcant > 0) {
				if($campaign['campaign_wcf']['less_words'] ) {
					$havecant = $words;
					$mes = $havecant. " words";
				}else{
					$havecant = $letters;
					$mes = $havecant. " letters";
				}
				if ($havecant < $mustcant) {
					trigger_error(sprintf(__('Skipping: %1s','wpematico'),$mes),E_USER_NOTICE);
					$current_item = -1 ;  // skip the post
				}
			}
		}
		return $current_item;
	}
	
	// Strip all in the content AFTER a word or phrase 
	public static function strip_lastphrasetoend( $current_item, $campaign, $feed, $item ) {
		if($current_item == -1) return -1;
		
		if( !empty($campaign['campaign_delfphrase']) ){
			$keyarr = explode( "\n", $campaign['campaign_delfphrase'] );
			foreach($keyarr  as  $key=>$value){
				$phrase=trim($value);  //  check the value for  empty line 
				if(!empty($phrase)){
					$index_phrase = stripos($current_item['content'], $phrase);
					if($index_phrase !== FALSE ) { // the string exists
						$add_content = '';
						if (isset($campaign['campaign_delfphrase_keep']) && $campaign['campaign_delfphrase_keep']) {
							$add_content .= substr($current_item['content'], $index_phrase, strlen($phrase)); // don't uses $phrase to keep Case-sensitive
						}
						$current_item['content'] = stristr($current_item['content'], $phrase, true); 
						$current_item['content'] .= $add_content;
						trigger_error('<strong>'.sprintf(__('Deleting since phrase: %1s','wpematico'),$phrase).'</strong>', E_USER_NOTICE);
						break;
					}
				}
			}
		}			
		return $current_item;
	}

	// strip only last HTML tag 
	public static function strip_lastag( $current_item, $campaign, $feed, $item ) {
		if($current_item == -1) return -1;
		$cfg = get_option( self :: OPTION_KEY);
		
		// *** Campaign Last html Tag to delete
		if (!empty($campaign['campaign_lastag']['tag'])){
			trigger_error('Deleting last HTML tag &lt;'.$campaign['campaign_lastag']['tag'].'&gt;<br>',E_USER_NOTICE);
			$current_item['content'] = self :: without_last($current_item['content'], $campaign['campaign_lastag']['tag']);
		}
			
		return $current_item;
	}
	/** * if found, delete the last paragraph of content  **/	
	Function without_last($string, $tag = "p") {
		$tag = str_replace(" ", "", $tag);
		if (!empty($tag)) {
			$pos = strripos($string, "<".$tag);
			if ($pos === false) {
				return $string;  // No lo encontró, devuelve todo
			} else { //lo encontró
				$restring= substr($string, $pos); //desde la posición que lo encontró hasta el final
				$regex = "#([<]".$tag.")(.*)([<]/".$tag."[>])#";  // tag y cierre de tag
				$cleanend = preg_replace($regex,'',$restring); //elimino el tag hasta el cierre
				return substr($string, 0, $pos) . $cleanend; //hasta el tag mas lo que resta sin el contenido del tag
			}
		}
	}


	// *** Word count filters I need the content for this
	public static function wordcountfilters( $current_item, $campaign, $feed, $item ) {
		if($current_item == -1) return -1;
		$cfg = get_option( self :: OPTION_KEY);
		if (@$cfg['enablewcf']) {
			trigger_error(sprintf(__('Processing Words count Filters %1s','wpematico'),$current_item['title'] ),E_USER_NOTICE);
			$words = self :: wordCount($current_item['content']);
			$letters = strlen($current_item['content']);
			trigger_error(sprintf(__('Found %1s words with %2s letters in content.','wpematico'),$words,$letters),E_USER_NOTICE);
			// if greather than x -> category
			$mustcant = $campaign['campaign_wcf']['great_amount'];
			if ($mustcant > 0) {
				if($campaign['campaign_wcf']['great_words'] ) {
					$havecant = $words;
					$mes = $mustcant. " words. ";
				}else{
					$havecant = $letters;
					$mes = $mustcant. " letters. ";
				}
				if ($mustcant <= $havecant ) {
					$tocat = $campaign['campaign_wcf']['category'];
					$categories[] = $tocat;
					trigger_error(sprintf(__('Greater than %1s To Cat_id %2s','wpematico'),$mes,$tocat),E_USER_NOTICE);
				}
			}
			// cutting at x
			$mustcant = $campaign['campaign_wcf']['cut_amount'];
			if ($mustcant > 0) {
				//$current_item['images']=array( '0' => $current_item['images'][0] );
				$current_item['images']= array_slice($current_item['images'], 0, 1);  // just first for featured img
				if($campaign['campaign_wcf']['cut_words'] ) {  //Counting words strip html tags
					$current_item['content'] = self :: wordCount($current_item['content'], $mustcant);
					$mes = $mustcant. " words";
				}else{  // counting letters count also html tags and close them after cut
					//$current_item['content'] = substr($current_item['content'],0,$mustcant);
					$current_item['content'] = self::closetags( substr($current_item['content'],0,$mustcant) );
					$mes = $mustcant. " letters. ";
				}
				trigger_error(sprintf(__('Cutting at %1s','wpematico'),$mes),E_USER_NOTICE);
			}
		} // Word count filters	
		return $current_item;
	}

	static function closetags($html) {
		preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	} 
	
	static function strip_tags_title($args, $campaign) {
		if (@$campaign['campaign_striptagstitle']) {
			trigger_error(sprintf(__('Strip HTML tags from title %1s','wpematico'),$args['post_title']),E_USER_NOTICE);
			$args['post_title'] = strip_tags( htmlspecialchars_decode($args['post_title'], ENT_QUOTES) );

		}
		return $args;
	}
		
	
	/** * Automatic tags  **/	
	static Function postags(&$current_item, &$campaign, &$item ) {
		$cfg = get_option( self :: OPTION_KEY);

		if (!is_array($current_item['tags'])) {
			$current_item['tags'] = array();
		}
		
		if ($campaign['campaign_tags_feeds'] && $cfg['enabletags']) {
			trigger_error(__('Adding tags from feed to post.', WPeMatico :: TEXTDOMAIN ) ,E_USER_NOTICE);
			$tags_item = $item->get_item_tags('', 'tag');
			if (is_array($tags_item)) {
				foreach($tags_item as $tagi) {
					$data_tag = $tagi['data'];
							
					if (strlen($data_tag) > apply_filters('wpem_autotags_min_length', 3) && strpos($data_tag, " ")===false ) {
						$current_item['tags'][] = $data_tag;
					}
				}
			}

		}


		if( !empty($campaign['campaign_tags']) && (!$cfg['enabletags'] || !$campaign['campaign_autotags'] )) {
			trigger_error(__('Adding custom tags to post.', WPeMatico :: TEXTDOMAIN ) ,E_USER_NOTICE);
			if (!is_array($current_item['tags'])) {
				$current_item['tags'] = array();
			}
			$manual_tags = explode(',', $campaign['campaign_tags']);
			foreach ($manual_tags as $mt) {
				$current_item['tags'][] = $mt;
			}

			
		}else {
			
			if($cfg['enabletags'] && $campaign['campaign_autotags'] ){
				if (!is_array($current_item['tags'])) {
					$current_item['tags'] = array();
				}

				trigger_error(__('Adding tags automatically to post.', WPeMatico :: TEXTDOMAIN ) ,E_USER_NOTICE);
				
				$badtags0 =  explode(',', sanitize_text_field($campaign['campaign_badtags']));
				$badtags1 =  sanitize_text_field( $cfg['all_badtags'] );
				$badtags1 =  (isset($badtags1) && empty($badtags1) ) ? $badtags1=array() : explode(',', $badtags1);
				//$badtags = array_merge($badtags0, $badtags1);
				$badtags = array_map( 'trim', array_merge($badtags0, $badtags1) );
				$badchars = array(",", ":", "(", ")", "]", "[", "?", "!", ";", "-", '.', '"', '<', '>');

				$i = count($current_item['tags']);
				if (count($current_item['tags']) >= (int)$campaign['campaign_nrotags']) {
					$current_item['tags'] = array_slice($current_item['tags'], 0, (int)$campaign['campaign_nrotags']);
					$i = count($current_item['tags']);
				}
				
				$content = str_replace($badchars, "", strip_tags(nl2br(html_entity_decode($current_item['content']))));
				$tags = explode(' ', $content);
				
				foreach ($tags as $key => $value) {
					if (strlen($value) > apply_filters('wpem_autotags_min_length', 3) && strpos($value, " ")===false ) {
						if (!in_array(strtolower($value), $badtags)) {
							if ($i++ >= (int)$campaign['campaign_nrotags'] ) {
								break;
							}
							$current_item['tags'][] = $value;
						}
					}
				}
			}
		}
		return $current_item;
	}


	/** * Custom Fields  **/	
	Public static function metaf(&$current_item, &$campaign, &$feed, &$item ) {
		$cfg = get_option( self :: OPTION_KEY);
		if( !empty($campaign['campaign_cfields']) && $cfg['enablecfields'] ) {
			trigger_error(__('Parsing Custom fields values.', WPeMatico :: TEXTDOMAIN ),E_USER_NOTICE);
			
			$template_vars = wpematico_campaign_fetch_functions::default_template_vars(array(), $current_item, $campaign, $feed, $item, '');
			$vars = array();
			$replace = array();
			foreach ($template_vars as $tvar => $tvalue) {
				$vars[] = $tvar;
				$replace[] = $tvalue;
			}
			$vars = apply_filters('wpematico_post_template_tags', $vars, $current_item, $campaign, $feed, $item);
			$replace = apply_filters('wpematico_post_template_replace', $replace, $current_item, $campaign, $feed, $item);
			
			for ($i = 0; $i < count($campaign['campaign_cfields']['name']); $i++) {
				$cf_name = $campaign['campaign_cfields']['name'][$i];
				$cf_value = $campaign['campaign_cfields']['value'][$i];
				$cf_value = str_ireplace($vars, $replace, $cf_value );
				$arraycf[$cf_name] = $cf_value;
			}
			$current_item['meta'] = (isset($current_item['meta']) && !empty($current_item['meta']) ) ? array_merge($current_item['meta'], $arraycf) :  $arraycf ;

		}
		//trigger_error(print_r($current_item['meta']),E_USER_NOTICE);
		return $current_item;
	}

	/**
	 * Keyword filtering
	 * @param type array $current_item
	 * @param type array $campaign
	 * @param type item Simplepie object $item
	 * @return boolean TRUE if is allowed, FALSE if must skip
	 */
	static protected function KeywordFilter(&$current_item, &$campaign, &$item ) {
		if (!function_exists('wpempro_contains')) {
			require_once 'includes/functions.php';
		}
		// Item content  //Todavia no tengo los contenidos (chequea los del feed)
		$content = $item->get_content(); //$current_item['content'];
		$title = $item->get_title(); //$current_item['title'];
		$categories = "";
		if($campaign['campaign_kwordf']['inccat']) {
			if ($autocats = $item->get_categories()) {
				trigger_error(__('Checking KeyWords in Categories.', 'wpematico' ) ,E_USER_NOTICE);
				foreach($autocats as $id => $catego) {
					$categories .= ','.$catego->term;
				}
				$categories = substr($categories, 1);
			}
		}

		// ***** Must include if at least one checkbox are checked
		if($campaign['campaign_kwordf']['inctit'] || $campaign['campaign_kwordf']['inccon'] || $campaign['campaign_kwordf']['inccat'] ) {
			$campaign_kwordf=(isset($campaign['campaign_kwordf']['inc']) && !empty($campaign['campaign_kwordf']['inc']) ) ? $campaign['campaign_kwordf']['inc'] : "";
			$keyarr=explode("\n",$campaign_kwordf);	 
			foreach($keyarr  as  $key=>$value){
			   $value=trim($value);  //  check the value for  empty line 
			   if  (!empty($value))	   {
					$words['inc'][]= $value;
			   }
			}
			$foundit = false;
			if( isset($words) && !empty($words) ) {
				if($campaign['campaign_kwordf']['inc_anyall'] == 'anyword' ) {
					// Must contain any word in title, in content OR in source tag
					$foundtit = $foundcon = $foundcat = false;
					if($campaign['campaign_kwordf']['inctit']) { //title 
						$foundtit =  wpempro_contains($title, $words['inc'], true);
					}
					if($campaign['campaign_kwordf']['inccon']) { //content
						$foundcon =  wpempro_contains($content, $words['inc'], true);
					}
					if($campaign['campaign_kwordf']['inccat']) { //in categories
						$foundcat =  wpempro_contains($categories, $words['inc'], true);
					}

					$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
					if ($foundit !== false ) {  
						trigger_error( __('Must contain: Found a keyword. Continuing...','wpematico'), E_USER_NOTICE );
					}else{
						trigger_error( __('Skiping: Must contain: Do not found any Keyword.','wpematico'), E_USER_WARNING );
						return false;
					}

				}else{
					// All Words must be in one field ?
/*					$foundtit = $foundcon = $foundcat = false;
					if($campaign['campaign_kwordf']['inctit']) { //title 
						$foundtit =  wpempro_contains($title, $words['inc']);
					}
					if($campaign['campaign_kwordf']['inccon']) { //content
						$foundcon =  wpempro_contains($content, $words['inc']);
					}
					if($campaign['campaign_kwordf']['inccat']) { //in categories
						$foundcat =  wpempro_contains($categories, $words['inc']);
					}
					$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
					if ($foundit !== false ) {  // a la primera que no encuentra ya se vuelve
						trigger_error(sprintf(__('Found all KeyWords!','wpematico')),E_USER_NOTICE);
					}else{
						trigger_error(sprintf(__('Skiping: Not found some Keyword','wpematico')),E_USER_WARNING);
						return false;
					}
*/
					// All Words can be by summing the 3 fields or all words 1 by 1 ?
					$foundit = false;

					for ($i = 0; $i < count($words['inc']); $i++) {
						$word = $words['inc'][$i];
						$foundtit = $foundcon = $foundcat = false;
						if($campaign['campaign_kwordf']['inctit']) { //title 
							$foundtit =  stripos($title, $word);
							$foundtit = ($foundtit !== false) ? true : false;
						}
						if($campaign['campaign_kwordf']['inccon']) { //content
							$foundcon =  stripos($content, $word);
							$foundcon = ($foundcon !== false) ? true : false;
						}
						if($campaign['campaign_kwordf']['inccat']) { //categories
							$foundcat =  stripos($categories, $word);
							$foundcat = ($foundcat !== false) ? true : false;
						}

						$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
						if ($foundit !== false ) {
							trigger_error(sprintf(__('MC:Found!: word %1s','wpematico'),$word),E_USER_NOTICE);
						}else{
							trigger_error(sprintf(__('MC:Skiping: Not found word %1s in content or title %2s.','wpematico'),$word,$title),E_USER_WARNING);
							return false;
						}
					}  // for i
				}
			}
		
			$foundit = false;
			$incregex = stripslashes($campaign['campaign_kwordf']['incregex']);
			if(!empty($incregex)) {
				$foundtit = $foundcon = $foundcat = false;
				if($campaign['campaign_kwordf']['inctit'] ) { //title 
					$foundtit = (preg_match($incregex, $title)) ? true : false;
				}
				if($campaign['campaign_kwordf']['inccon']) { //content
					$foundcon = (preg_match($incregex, $content)) ? true : false;
				}
				if($campaign['campaign_kwordf']['inccat']) { //categories
					$foundcat = (preg_match($incregex, $categories)) ? true : false;
				}

				$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
				if ($foundit !== false ) {  
					trigger_error(sprintf(__('Must contain: Found regex %1s. Continuing...','wpematico'),$incregex),E_USER_NOTICE);
				}else{
					trigger_error(sprintf(__('Skiping: Must contain do not found regex %1s.','wpematico'),$incregex),E_USER_WARNING);
					return false;
				}
			}
		}
				
		// ************ Cannot contain "exclude" *************************************
		// ***** Must include if at least one checkbox are checked
	if($campaign['campaign_kwordf']['exctit'] || $campaign['campaign_kwordf']['exccon'] || $campaign['campaign_kwordf']['exccat']) {
		$campaign_kwordf=(isset($campaign['campaign_kwordf']['exc']) && !empty($campaign['campaign_kwordf']['exc']) ) ? $campaign['campaign_kwordf']['exc'] : "";
		$keyarr=explode("\n",$campaign_kwordf);	 
		foreach($keyarr  as  $key=>$value){
			$value=trim($value);  //  check the value for  empty line 
			if  (!empty($value)) {
				$words['exc'][]= $value;
		    }
		}
		$foundit = false;
		if( isset($words) && !empty($words) ){
			if($campaign['campaign_kwordf']['exc_anyall'] != 'anyword' ) {
				$foundtit = $foundcon = $foundcat = false;
					// NO Debe contener TODAS las palabras sino dev. false
				if($campaign['campaign_kwordf']['exctit']) { //title 
					$foundtit =  wpempro_contains($title, $words['exc']);
				}
				if($campaign['campaign_kwordf']['exccon']) { //content
					$foundcon =  wpempro_contains($content, $words['exc']);
				}
				if($campaign['campaign_kwordf']['exccat']) { //categories
					$foundcat =  wpempro_contains($categories, $words['exc']);
				}

				$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
				if ($foundit === false ) {  
					trigger_error( __('Cannot contain: Do not found all keywords. Continuing...','wpematico'), E_USER_NOTICE );
				}else{
					trigger_error( __('Skiping: Cannot contain: Found all Keywords.','wpematico'), E_USER_WARNING );
					return false;
				}

			}else{
				// NO Debe contener ALGUNA de las palabras sino dev. false
				$foundit = false;
				for ($i = 0; $i < count($words['exc']); $i++) {
					$word = $words['exc'][$i];
					$foundtit = $foundcon = $foundcat = false;
					if($campaign['campaign_kwordf']['exctit']) { //title 
						$foundtit =  stripos($title, $word);
						$foundtit = ($foundtit !== false) ? true : false;
					}
					if($campaign['campaign_kwordf']['exccon']) { //content
						$foundcon =  stripos($content, $word);
						$foundcon = ($foundcon !== false) ? true : false;
					}
					if($campaign['campaign_kwordf']['exccat']) { //categories
						$foundcat =  stripos($categories, $word);
						$foundcat = ($foundcat !== false) ? true : false;
					}

					$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
					if ($foundit === false ) { 
						trigger_error(sprintf(__('CC:Not Found!: word %1s','wpematico'),$word),E_USER_NOTICE);
					}else{
						trigger_error(sprintf(__('CC:Skiping: Found word %1s in content or title %2s.','wpematico'),$word,$title),E_USER_WARNING);
							return false;
					}
				}
			}
		}

		$foundit = false;
		$excregex = stripslashes($campaign['campaign_kwordf']['excregex']);
		if(!empty($excregex)) {
			$foundtit = $foundcon = $foundcat = false;
			if($campaign['campaign_kwordf']['exctit'] ) { //title 
				$foundtit = (preg_match($excregex, $title)) ? true : false;
			}
			if($campaign['campaign_kwordf']['exccon']) { //content
				$foundcon = (preg_match($excregex, $content)) ? true : false;
			}
			if($campaign['campaign_kwordf']['exccat']) { //categories
				$foundcat = (preg_match($excregex, $categories)) ? true : false;
			}

			$foundit = $foundtit ||	$foundcon || $foundcat;  // found A word in title, in content OR in source tag
			if ($foundit === false ) {  
				trigger_error(sprintf(__('Cannot contain: Not Found regex %1s. Continuing...','wpematico'),$excregex),E_USER_NOTICE);
			}else{
				trigger_error(sprintf(__('Skiping: Cannot contain: found regex %1s.','wpematico'),$excregex),E_USER_WARNING);
				return false;
			}
		}
	}

	return true;
	}  //end Keyword filtering
  
  
    /**  * Count the real words from string
    *
    * if limit <> 0 return the new cuted string else return words counted
    */
    static Public function wordCount($string, $limit = 0, $endstr = ' ...'){
    # strip all html tags
	$text = strip_tags($string);

/*	# remove 'words' that don't consist of alphanumerical characters or punctuation
	$pattern = "#[^(\w|\d|\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]+#";
	$text = trim(preg_replace($pattern, " ", $text));

	# remove one-letter 'words' that consist only of punctuation
	$text = trim(preg_replace("#\s*[(\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]\s*#", " ", $text));

	# remove superfluous whitespace
	$text = preg_replace("/\s\s+/", " ", $text);
 */
	$characterMap = 'áéíóúüñ';
	$words = str_word_count($text, 2, $characterMap); 
	
	# remove empty elements
	$words = array_filter($words);
	
	$count = count($words);
	
    if ($limit > 0) {
	  $pos = array_keys($words);
      if ($count > $limit) {
          $text = substr($text, 0, $pos[$limit]) . $endstr ;
      }
	}
	
    return ($limit==0) ? $count : $text;
 }

	/**
	 * 
	 */
	static function wpematico_overwrite_file( $new_file ) {
		if(file_exists( $new_file )){
			if(unlink($new_file))
				trigger_error('Overwriting image '.$new_file,E_USER_WARNING);
			else
				trigger_error('Can\'t Overwrite image, renaming '.$new_file,E_USER_WARNING);
		}
		return $new_file;
	}
	static function wpematico_keep_file( $new_file ) {
		if(file_exists( $new_file )){
			trigger_error('Keeping original image '.$new_file,E_USER_WARNING);
			return false;
		}
		return $new_file;
	}
	/**
	 * strip if no image on content
	 * @return -1 if skip else $current_item
	 */
	static function discardifnoimage( $current_item, $campaign, $feed, $item  ) {
		if($current_item == -1) return -1;
		$images = wpematico_campaign_fetch::parseImages($current_item['content']);
		$urls = $images[2]; 
		if ( $campaign['rssimg_enclosure'] && ($enclosure = $item->get_enclosure())) {
			$imgenc = $enclosure->get_link();
			$urls[] = $imgenc ;
		}
		// removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$images =  array_values( array_filter( $urls , 'strlen' ) );
		if(sizeof($images) == 0 && empty($current_item['featured_image'])) {
			trigger_error(sprintf(__('No image in content -> skipping', WPeMatico :: TEXTDOMAIN)), E_USER_NOTICE);
			return -1;
		}
		
		return $current_item;
	}
	static function discardifnoimage_aux($allow, $fetch, $args) {
		if(!$allow){
			return $allow;
		}
		$campaign = $fetch->campaign;
		$current_item = $fetch->current_item; 
		$images = wpematico_campaign_fetch::parseImages($current_item['content']);
		$urls = $images[2]; 

		// removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$images =  array_values( array_filter( $urls , 'strlen' ) );
		if(sizeof($images) == 0 && empty($current_item['featured_image'])) {
			trigger_error(sprintf(__('No image in content -> skipping', WPeMatico :: TEXTDOMAIN)), E_USER_NOTICE);
			$allow  = false;
		}
		return $allow;
	}
	
	public static function image_rename($newimgname, $current_item = null, $campaign = null, $item = null ) {
		$cfg = get_option( self :: OPTION_KEY);
		if( !isset($cfg['enableimgrename']) || empty($cfg['enableimgrename']) ) 
			return $newimgname;
			
		$newimgname = self::img_src_cleaner($newimgname);
	// Find only image filenames after the / and before the ? sign (? = 3F here)
		preg_match('/[^\/\?]+\.(?:jp[eg]+|png|bmp|giff?|tiff?)/i', $newimgname, $matches);
	// First step of urldecode and sanitize the filename
		$imgname = sanitize_file_name(urldecode(basename($matches[0])));
	// Split the name from the extension
		$parts = explode('.', $imgname);
//		$name = array_shift($parts);
		$extension = array_pop($parts);
		$extension = (empty($extension)) ? 'jpg' : $extension; // Allways JPG if extension is missing
	// Join all names splitted by dots
//		foreach((array) $parts as $part) {
//			$name .= '.' . $part;
//		}
	// Second step of urldecode and sanitize only the name of the file
//		$name = sanitize_title(urldecode($name));

		$vars = array(
			'{slug}',
			'{title}',
		);
		$replace = array(
			sanitize_file_name( $current_item['title']), //slug
			sanitize_title( $current_item['title']),  //remove tags
		);
		$name = ( ( $campaign['campaign_imgrename'] ) ? str_ireplace($vars, $replace, stripslashes( $campaign['campaign_imgrename'] ) ) : $imgname) .".$extension";
		trigger_error(sprintf(__('Renamed image %1s -> %2s', WPeMatico :: TEXTDOMAIN),$newimgname, $name ), E_USER_NOTICE);

	// Join the name with the extension
		//$newimgname = dirname($newimgname) . '/' . $name . '.' . $extension;
		$newimgname = $name;
		return $newimgname;
	}	
	
	/**
	 * Clean parameters or query vars to a new image name
	 * @param type $newimgname
	 * @return string
	 */
	public static function image_src_gettype($newimgname, $current_item, $campaign, $item) {
		// Find only image filenames after the / and before the ? sign (? = 3F here)
		preg_match('/[^\/\3F]+\.(?:jp[eg]+|png|bmp|giff?|tiff?)/i', $newimgname, $matches);
		if(empty($matches)){
			preg_match('/[^\/\?]+\.(?:jp[eg]+|png|bmp|giff?|tiff?)/i', $newimgname, $matches);
		}
		if(empty($matches)){ // is not an image extension
			// busco la url completa por el nombre primero si es la featured o el array de imagenes
			$url = '';
			if( sanitize_file_name(urlencode(basename($current_item['featured_image'])))==$newimgname ) {
				$url = $current_item['featured_image'];
			}else {
				foreach($current_item['images'] as $image) {
					if( sanitize_file_name(urlencode(basename($image)))==$newimgname ) {
						$url = $image;
						break;
					}
				}
			}
			if(!empty( $url )){
				stream_context_set_default(array( 'http' => array('method' => 'HEAD'	) ));
				$headers = get_headers($url, 1);
				if ($headers !== false && isset($headers['Content-Type'])) {
					if( strpos($headers['Content-Type'], 'image')!==false ) {
						$parts = explode('/',$headers['Content-Type']);
						$extension = array_pop($parts);
					}
				}
			}
			
			$name = strtok($newimgname, 'F3');
			if( $name == $newimgname ){
				$name = strtok($newimgname, '?');
				if( $name == $newimgname ){ // last resource = harcoded name
					$name = 'codeimg.jpg';
				}
			}
		}else {
			$name = $matches[0];
		}

	// First step of urldecode and sanitize the filename
		$imgname = sanitize_file_name(urldecode(basename($name)));
	// Split the name from the extension
		$parts = explode('.', $imgname);
		$name = array_shift($parts);
		
		$extension = (empty($extension)) ? 'jpg' : $extension; // Allways JPG if extension is missing
	// Join all names splitted by dots
		foreach((array) $parts as $part) {
			$name .= '.' . $part;
		}
	// Second step of urldecode and sanitize only the name of the file
		$name = sanitize_title(urldecode($name));
	// Join the name with the extension
		//$newimgname = dirname($newimgname) . '/' . $name . '.' . $extension;
		$newimgname = $name . '.' . $extension;
		return $newimgname;
	}	

	/**
	 * Clean parameters or query vars from image url
	 * @param type $imagen_src_real
	 * @return string
	 */
	static function img_src_cleaner($imagen_src_real) {
	// Find only image filenames after the / and before the ? sign
		preg_match('/[^\/\?]+\.(?:jp[eg]+|png|bmp|giff?|tiff?)/i', $imagen_src_real, $matches);
	// First step of urldecode and sanitize the filename
		$imgname = sanitize_file_name(urldecode(basename($matches[0])));
	// Split the name from the extension
		$parts = explode('.', $imgname);
		$name = array_shift($parts);
		$extension = array_pop($parts);
	// Join all names splitted by dots
		foreach((array) $parts as $part) {
			$name .= '.' . $part;
		}
	// Second step of urldecode and sanitize only the name of the file
		//$name = sanitize_title(urldecode($name));  // Pierde mayusculas
		$name = (urldecode($name));
	// Join the name with the extension
		$newimgname = dirname($imagen_src_real) . '/' . $name . '.' . $extension;

		return $newimgname;
	}
		
	// Strip all images from wpematico posts before insert
	static function wpetruel_strip_img_tags($text) {
		$text = preg_replace("/<img[^>]+\>/i", " ", htmlspecialchars_decode($text, ENT_QUOTES) );
		return $text;
	}
	static function wpetruel_strip_img_tags_content($current_item, $campaign) {
		$current_item['content'] = self::wpetruel_strip_img_tags( $current_item['content'] );
		return $current_item;
	}

 
	// See if there is image in feed 
	// return array with images 
	static public function imgfind(&$current_item, &$campaign, &$item ) {
		$cfg = get_option( self :: OPTION_KEY);
		$urls = $current_item['images'] ;
		WPeMaticoPRO::$rssimg_add2img_featured_image = '';
		
		$rssurls = array();
		if ($campaign['campaign_rssimg']) { // Si busco en el RSS content SIN filtrar
			if( !$campaign['rssimg_ifno'] || (($campaign['rssimg_ifno']) && (sizeof($current_item['images']) == 0 ))) { // // la agrega si no hay img en el contenido
				$images = wpematico_campaign_fetch_functions :: parseImages( $item->get_content() ); 
				$rssurls = $images[2]; 
			}
			//$imgenc = $urls[0];
			if ( $campaign['rssimg_enclosure'] ) {
				trigger_error(sprintf(__('Getting enclosure link: %s', WPeMatico :: TEXTDOMAIN ),$urls[0]),E_USER_NOTICE);
				if($allenclosures = $item->get_enclosures()){
					foreach ($allenclosures as $enclosure) {
						foreach ((array) $enclosure->get_thumbnails() as $thumbnail) {
							$rssurls[] = $thumbnail;
						}							
						if($imgenc = $enclosure->get_link()) {
							$rssurls[] = $imgenc ;
						}
					}
				}
			}
			
			if ( $campaign['rssimg_add2img'] ) {  // RSS to Featured  
				$featured_image = $rssurls[0];
				WPeMaticoPRO::$rssimg_add2img_featured_image = $rssurls[0];
				/*add_filter( 'wpematico_set_featured_img', function($img) use ( $featured_image ) {
					return $featured_image;
				});
				add_filter( 'wpematico_get_featured_img', function($img) use ( $featured_image ) {
					return $featured_image;
				});			
				*/
				// sumo las nuevas primero en la lista
				// $urls = array_merge($rssurls,$current_item['images']);
			}else{    // sumo las nuevas al final en la lista
				$urls = array_merge($current_item['images'], $rssurls);
			}
		}
		// ************ image filters *****************
		// removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
		$urls =  array_values( array_filter( $urls , 'strlen' ) );
		$current_item['images']= $urls ;
		if( !empty($campaign['imagefilters']) && $cfg['enableimgfilter'] ) :  // Si está habilitado en settings
			trigger_error( __('Applying Image Filters.', WPeMatico :: TEXTDOMAIN ), E_USER_NOTICE);
			$img2del = array();
			for ($j = 0; $j < count($campaign['imagefilters']['value']); $j++) : 
				$allow 	  = ($campaign['imagefilters']['allow'][$j] == 'Allow')? true : false ;
				$woh	  = $campaign['imagefilters']['woh'][$j];
				$mol	  = ($campaign['imagefilters']['mol'][$j]) == 'more' ? '>=' : '<=' ;
				$if_value = $campaign['imagefilters']['value'][$j];
				for ($i = 0; $i < count($urls); $i++) {
					$imageurl = $urls[$i];
					$sizeimg = self :: getjpegsize( $imageurl );  // lee solo header, solo para jpeg
					if($sizeimg == false ) $sizeimg = getimagesize( $imageurl ); 
					if($sizeimg == false ) {
						trigger_error( __("Don't works filters with: " , WPeMatico :: TEXTDOMAIN ) .'"'. $urls[$i] .'"', E_USER_NOTICE);
						$current_item['content'] = wpematico_campaign_fetch_functions::strip_Image_by_src($urls[$i], $current_item['content']);
						$img2del[] = $urls[$i]; 
						continue;
					}
					list($ancho, $alto) = $sizeimg;
					$imgvalue=($woh=="width") ? $ancho : $alto;
					$imgfilter = $imgvalue.$mol.$if_value;
					$compute = create_function("", "return (" . $imgfilter . ");" );
					// Si no se cumple el filtro lo borra del contenido y del array y continua con la siguiente img
					trigger_error( __("Filter: " , WPeMatico :: TEXTDOMAIN ) .'"'. $urls[$i] .'" ===> '.$imgfilter , E_USER_NOTICE);
					if( !$compute() ) {
						$current_item['content'] = wpematico_campaign_fetch_functions::strip_Image_by_src($urls[$i], $current_item['content']);
						$img2del[] = $urls[$i]; 
						continue;
					}
				}
			endfor;
			$urls = array_diff($urls , $img2del);
		endif; //enableimgfilter
		
		// ************ Featured image filters ***************** 
		$current_item['images']= $urls ;
		$current_item['nofeatimg']= false ;
		if( !empty($campaign['featimgfilters']) ) :  // Si tiene algun filtro sino queda como está 
			trigger_error( __('Filtering Featured Image.', WPeMatico :: TEXTDOMAIN ), E_USER_NOTICE);
			$img2del = array();
			for ($j = 0; $j < count($campaign['featimgfilters']['value']); $j++) : 
				$allow 	  = ($campaign['featimgfilters']['allow'][$j] == 'Allow')? true : false ;
				$woh	  = $campaign['featimgfilters']['woh'][$j];
				$mol		  = ($campaign['featimgfilters']['mol'][$j]) == 'more' ? '>=' : '<=' ;
				$if_value = $campaign['featimgfilters']['value'][$j];
				for ($i = 0; $i < count($urls); $i++) {
					$imageurl = $urls[$i];
					$sizeimg = self :: getjpegsize( $imageurl );  // lee solo header, solo para jpeg
					if($sizeimg == false ) $sizeimg = getimagesize( $imageurl ); 
					if($sizeimg == false ) {
						trigger_error( __("Don't works filters with: " , WPeMatico :: TEXTDOMAIN ) .'"'. $urls[$i] .'"', E_USER_NOTICE);
						$current_item['content'] = wpematico_campaign_fetch_functions::strip_Image_by_src($urls[$i], $current_item['content']);
						$img2del[] = $urls[$i]; 
						continue;
					}
					list($ancho, $alto) = $sizeimg;
					$imgvalue=($woh=="width") ? $ancho : $alto;
					$imgfilter = $imgvalue.$mol.$if_value;
					$compute = create_function("", "return (" . $imgfilter . ");" );
					// Si se cumple el filtro la pone primero en el array y sale
					trigger_error( __("Filter: " , WPeMatico :: TEXTDOMAIN ) .'"'. $urls[$i] .'" ===> '.$imgfilter , E_USER_NOTICE);
					if( !$compute() ) { 
						$current_item['nofeatimg']= true ;
						// continue;
					}else{
						$current_item['nofeatimg']= false ;
						trigger_error( __("First featured image meets filters: " , WPeMatico :: TEXTDOMAIN ) .'"'. $urls[$i] .'"' , E_USER_NOTICE);
						$newfeat= $urls[$i];
						$urls = array_splice($urls , $i, 1); // la borra del lugar donde esta
						//$urls = array_splice($urls , 0, 0, $newfeat);
						array_unshift($urls, $newfeat );  // la pone primero
						break;  // sale del for
					}
				}
			endfor;
			$urls = array_diff($urls , $img2del);
		endif; //featimgfilters
		
		return $urls;  
	}
	
	// Put Default image as featured if there is no images in content
	//$this->current_item['images'][0], $this->current_item
	public static function custom_img($allow, $fetch, $args) {
		// agrego la imagen por defecto	//trigger_error('AA::'.print_r($current_item['images'],true),E_USER_NOTICE);
//		if( sizeof($current_item['images'])==0){
		if (empty($fetch->current_item['featured_image'])) {
			trigger_error(__('Inserting default Image Into Post.', WPeMatico :: TEXTDOMAIN ),E_USER_NOTICE);
			$campaign = $fetch->campaign;
			$fetch->current_item['featured_image'] = $campaign['default_img_url'];
		}
		
		return $allow;
	}
	
	// Put in content 1st image link
	public static function img1s(&$current_item, &$campaign, &$item ) {
		// $cfg = get_option( self :: OPTION_KEY);
		if ( $campaign['add1stimg'] ) {  // veo si tengo que agregar img primero en el content
			if(!empty($current_item['featured_image'])) {
				$imgstr = "<img class=\"wpe_imgrss\" src=\"" . $current_item['featured_image'] . "\">";  //Solo la imagen
				$imgstr .= $current_item['content'];
				$current_item['content'] = $imgstr;
			}
		}
		return $current_item['images'];
	}
	
	
	// Retrieve JPEG width and height without downloading/reading entire image.
	static private function getjpegsize($img_loc) {
		$handle = fopen($img_loc, "rb"); // or die("Invalid file stream.");
		if(!$handle) return FALSE;
		$new_block = NULL;
		if(!feof($handle)) {
			$new_block = fread($handle, 32);
			$i = 0;
			if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
				$i += 4;
				if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
					// Read block size and skip ahead to begin cycling through blocks in search of SOF marker
					$block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
					$block_size = hexdec($block_size[1]);
					while(!feof($handle)) {
						$i += $block_size;
						$new_block .= fread($handle, $block_size);
						if($new_block[$i]=="\xFF") {
							// New block detected, check for SOF marker
							$sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
							if(in_array($new_block[$i+1], $sof_marker)) {
								// SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
								$size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
								$unpacked = unpack("H*", $size_data);
								$unpacked = $unpacked[1];
								$height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
								$width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
								return array($width, $height);
							} else {
								// Skip block marker and read block size
								$i += 2;
								$block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
								$block_size = hexdec($block_size[1]);
							}
						} else {
							return FALSE;
						}
					}
				}
			}
		}
		return FALSE;
	}
	
	
	/*** checkea si existe el usuario
	Si no existe lo crea con mail username@thisdomain y devuelve el ID	***/
	static private function checkauthor($wpusername) {
		$ID = username_exists( $wpusername );
		if (!$ID){ //agrego usuario
			$wpuser =  sanitize_user( $wpusername );
			$ID = wp_insert_user( array ('user_login' => $wpuser) ) ;
		}
		return $ID;	
	}
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++		


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++ // check required fields values before save post
		public static function Checkp( $post_data, $err_message = "" ) {
			$inctit = @$post_data["campaign_kwordf_inc_tit"]==1 ? true : false;
			$inccon = @$post_data["campaign_kwordf_inc_con"]==1 ? true : false;
			$inccat = @$post_data["campaign_kwordf_inc_cat"]==1 ? true : false;
			if(!$inctit && !$inccon && !$inccat && (!empty($post_data["campaign_kwordf_inc"]) || !empty($post_data["campaign_kwordf_incregex"]))) {
				$err_message = ($err_message != "") ? $err_message."<br />" : "" ;
				$err_message .= sprintf(__('There\'s an error in Keyword include filter: You must check at least one search option.',  WPeMatico :: TEXTDOMAIN ),'<br />').' ';
			}
			$exctit = @$post_data["campaign_kwordf_exc_tit"]==1 ? true : false;
			$exccon = @$post_data["campaign_kwordf_exc_con"]==1 ? true : false;
			$exccat = @$post_data["campaign_kwordf_exc_cat"]==1 ? true : false;
			if(!$exctit && !$exccon && !$exccat && (!empty($post_data["campaign_kwordf_exc"]) || !empty($post_data["campaign_kwordf_excregex"]))) {
				$err_message = ($err_message != "") ? $err_message."<br />" : "" ;
				$err_message .= sprintf(__('There\'s an error in Keyword exclude filter: You must check at least one search option.',  WPeMatico :: TEXTDOMAIN ),'<br />').' ';
			}
			
			if(!empty($post_data["campaign_kwordf_incregex"])) 
				if(false === @preg_match($post_data["campaign_kwordf_incregex"], '')) {
					$err_message = ($err_message != "") ? $err_message."<br />" : "" ;
					$err_message .= sprintf(__('There\'s an error with the supplied RegEx expression in Keyword include filter: %s',  WPeMatico :: TEXTDOMAIN ),'<br />'.$post_data["campaign_kwordf_incregex"]).' ';
				}

			if(!empty($post_data["campaign_kwordf_excregex"])) 
				if(false === @preg_match($post_data["campaign_kwordf_excregex"], '')) {
					$err_message = ($err_message != "") ? $err_message."<br />" : "" ;
					$err_message .= sprintf(__('There\'s an error with the supplied RegEx expression in Keyword exclude filter: %s',  WPeMatico :: TEXTDOMAIN ),'<br />'.$post_data["campaign_kwordf_excregex"]).' ';
				}
			return $err_message;
		}
		

		/**
		 * 
		 * @param type $campaign	array current values
		 * @return type $campaign	array of fixed new and default values of campaign
		 */
		public static function pro_update_campaign( $campaign ) {  // agregado para que no borre las \ al grabar
			if(isset($campaign['campaign_kwordf']['excregex']) && !empty($campaign['campaign_kwordf']['excregex']) ){
				$campaign['campaign_kwordf']['excregex'] =	addslashes($campaign['campaign_kwordf']['excregex']);
			}
			if(isset($campaign['campaign_kwordf']['incregex']) && !empty($campaign['campaign_kwordf']['incregex']) ){
				$campaign['campaign_kwordf']['incregex'] =	addslashes($campaign['campaign_kwordf']['incregex']);
			}
			return $campaign;
		}
		/**
		 * 
		 * @param type $campaign_data	array current values
		 * @param type $post_data		array of new values
		 * @return type $campaign_data	array of fixed new and default values of campaign
		 */
		public static function pro_check_campaigndata( $campaign_data = array(), $post_data) {  // save no va mas, ahora chequea y agrega campos a campaign y graba en free
			$cfg = get_option( self :: OPTION_KEY);

			if( isset($cfg['enableimportfeed']) && $cfg['enableimportfeed'] && !empty($post_data["txt_feedlist"]) ) {  //importa feedlist
				$total = nl2br($post_data["txt_feedlist"]); 
				$keyarr=explode("<br />",$total);
				foreach($keyarr  as  $key=>$value)	{
					$value = trim($value);
					if(!empty($value)) { // la linea sirve, agrego el feed 
						list($feed, $author) = explode(",", $value);
						$feed = trim($feed);
						$author = trim($author);
						if(!empty($feed)) 
							$campaign_data['campaign_feeds'][] = $feed; //agrego feed nuevo
						if(!empty($author)) {
							$campaign_data[$feed]['feed_author'] = self::checkauthor($author); 
						}
					}
				}
			}
			
			if ( isset($cfg['enableauthorxfeed']) && @$cfg['enableauthorxfeed'] && empty($post_data["txt_feedlist"])) { //x si agrega listatxt
				$campaign_feeds = $campaign_data['campaign_feeds'];
				foreach($campaign_feeds as $id => $feed): 
					//$campaign_data[$feed]['feed_author'] = $post_data['feed_author'][$id];
					if (isset($post_data[$feed]['feed_author'])) {
						$campaign_data[$feed]['feed_author'] = (isset($post_data[$feed]['feed_author'])) ? $post_data[$feed]['feed_author'] : "-1" ;
					}else {
						$campaign_data[$feed]['feed_author'] = (isset($post_data['feed_author'])) ? $post_data['feed_author'][$id] : "-1" ;
					}
				endforeach;
			}
			
			#Proceso los Word count Filters
			$campaign_wcf = (isset($post_data['campaign_wcf']) && !empty($post_data['campaign_wcf']) ) ? $post_data['campaign_wcf'] : array();
			if(empty($campaign_wcf) ) {
				$campaign_wcf['great_amount']= (isset($post_data['campaign_wcf_great_amount']) && !empty($post_data['campaign_wcf_great_amount']) ) ? $post_data['campaign_wcf_great_amount'] : 0 ;
				$campaign_wcf['great_words'] = (!isset($post_data['campaign_wcf_great_words']) || empty($post_data['campaign_wcf_great_words'])) ? false: ($post_data['campaign_wcf_great_words']==1) ? true : false ;
				$campaign_wcf['category'] 	 = (isset($post_data['campaign_wcf_category']) && !empty($post_data['campaign_wcf_category']) ) ? $post_data['campaign_wcf_category'] : "-1";
				$campaign_wcf['cut_amount']  = (isset($post_data['campaign_wcf_cut_amount']) && !empty($post_data['campaign_wcf_cut_amount']) ) ? $post_data['campaign_wcf_cut_amount'] : 0;
				$campaign_wcf['cut_words'] 	 = (!isset($post_data['campaign_wcf_cut_words']) || empty($post_data['campaign_wcf_cut_words'])) ? false: ($post_data['campaign_wcf_cut_words']==1) ? true : false ;
				$campaign_wcf['less_amount'] = (isset($post_data['campaign_wcf_less_amount']) && !empty($post_data['campaign_wcf_less_amount']) ) ? $post_data['campaign_wcf_less_amount'] : 0;
				$campaign_wcf['less_words']  = (!isset($post_data['campaign_wcf_less_words']) || empty($post_data['campaign_wcf_less_words'])) ? false: ($post_data['campaign_wcf_less_words']==1) ? true : false ;
			}
			$campaign_data['campaign_wcf']= $campaign_wcf ;

		// *** Campaign Tags
			$campaign_data['campaign_autotags']	= (!isset($post_data['campaign_autotags']) || empty($post_data['campaign_autotags'])) ? false: ($post_data['campaign_autotags']==1) ? true : false;
			$campaign_data['campaign_tags_feeds']	= (!isset($post_data['campaign_tags_feeds']) || empty($post_data['campaign_tags_feeds'])) ? false: ($post_data['campaign_tags_feeds']==1) ? true : false;

			$campaign_data['campaign_nrotags']	= (isset($post_data['campaign_nrotags']) && !empty($post_data['campaign_nrotags']) ) ? $post_data['campaign_nrotags'] : 10 ;
			$campaign_data['campaign_badtags']	= (isset($post_data['campaign_badtags']) && !empty($post_data['campaign_badtags']) ) ? $post_data['campaign_badtags'] : '';
			
		// *** Campaign Options
			$campaign_data['fix_google_links']	= (!isset($post_data['fix_google_links']) || empty($post_data['fix_google_links'])) ? false: ($post_data['fix_google_links']==1) ? true : false;

			$campaign_data['add_no_follow']	= (!isset($post_data['add_no_follow']) || empty($post_data['add_no_follow'])) ? false: ($post_data['add_no_follow']==1) ? true : false;


			$campaign_data['campaign_striptagstitle']	= (!isset($post_data['campaign_striptagstitle']) || empty($post_data['campaign_striptagstitle'])) ? false: ($post_data['campaign_striptagstitle']==1) ? true : false;
			$campaign_data['campaign_enablecustomtitle']= (!isset($post_data['campaign_enablecustomtitle']) || empty($post_data['campaign_enablecustomtitle'])) ? false: ($post_data['campaign_enablecustomtitle']==1) ? true : false;
			$campaign_data['campaign_customtitle']	= (isset($post_data['campaign_customtitle']) && !empty($post_data['campaign_customtitle']) ) ? $post_data['campaign_customtitle'] : '' ;
			$campaign_data['campaign_custitdup']	= (!isset($post_data['campaign_custitdup']) || empty($post_data['campaign_custitdup'])) ? false: ($post_data['campaign_custitdup']==1) ? true : false;
			$campaign_data['campaign_ctitlecont']	= (!isset($post_data['campaign_enablecustomtitle']) || empty($post_data['campaign_enablecustomtitle'])) ? false: ($post_data['campaign_ctitlecont']==1) ? true : false;
			$campaign_data['campaign_ctdigits']		= (isset($post_data['campaign_ctdigits']) && !empty($post_data['campaign_ctdigits']) ) ? $post_data['campaign_ctdigits'] : 6 ;
			$campaign_data['campaign_ctnextnumber']	= (isset($post_data['campaign_ctnextnumber']) && !empty($post_data['campaign_ctnextnumber']) ) ? $post_data['campaign_ctnextnumber'] : 0 ;

		// *** Sobreescribo Autor por si lo crea en el momento o lo tiene el feed
			$campaign_data['campaign_author'] = (isset($post_data['campaign_author']) && !empty($post_data['campaign_author']) ) ? $post_data['campaign_author'] : '';

		// *** Campaign Last html Tag to delete
			$campaign_data['campaign_lastag']['tag']= (isset($post_data['campaign_lastag_tag']) && !empty($post_data['campaign_lastag_tag']) ) ? $post_data['campaign_lastag_tag'] : "";

 		// *** Campaign custom_fields	
			// Proceso los custom fields sacando los que estan en blanco
			if(isset($post_data['campaign_cf_name'])) {
				foreach($post_data['campaign_cf_name'] as $id => $cf_value) {       
					$cf_name = esc_attr( $post_data['campaign_cf_name'][$id] );
					$cf_value = esc_attr( $post_data['campaign_cf_value'][$id] );
					if(!empty($cf_name))  {
						if(!isset($campaign_cfields)) 
							$campaign_cfields = Array();
						$campaign_cfields['name'][]=$cf_name ;
						$campaign_cfields['value'][]=$cf_value ;
					}
				}
			}
			$cfields = (isset($post_data['campaign_cfields']) && !empty($post_data['campaign_cfields']) ) ? $post_data['campaign_cfields'] : array() ;
			$campaign_data['campaign_cfields']= (isset($campaign_cfields) && !empty($campaign_cfields) ) ? $campaign_cfields : $cfields ;
			
 		// *** Campaign Image Filters
			// Proceso los filtros sacando los que los pixels estan en blanco
			if(isset($post_data['campaign_if_value'])) {
				foreach($post_data['campaign_if_value'] as $id => $if_value) {       
					$allow = $post_data['campaign_if_allow'][$id];
					$woh = $post_data['campaign_if_woh'][$id];
					$mol = $post_data['campaign_if_mol'][$id];
					//$if_value = $post_data['campaign_if_value'][$id];
					if(!empty($if_value))  {
						if(!isset($imagefilters)) 
							$imagefilters = Array();
						$imagefilters['allow'][]=$allow ;
						$imagefilters['woh'][]=$woh ;
						$imagefilters['mol'][]=$mol ;
						$imagefilters['value'][]=$if_value ;
					}
				}
			}
			$imfilters = (isset($post_data['imagefilters']) && !empty($post_data['imagefilters']) ) ? $post_data['imagefilters'] : array();
			$campaign_data['imagefilters']=(isset($imagefilters) && !empty($imagefilters) ) ? $imagefilters : $imfilters ;

 		// *** Campaign Featured Image Filters
			// Proceso los filtros sacando los que los pixels estan en blanco
			if(isset($post_data['campaign_feat_value'])) {
				foreach($post_data['campaign_feat_value'] as $id => $if_value) {       
					$allow = $post_data['campaign_feat_allow'][$id];
					$woh = $post_data['campaign_feat_woh'][$id];
					$mol = $post_data['campaign_feat_mol'][$id];
					//$if_value = $post_data['campaign_feat_value'][$id];
					if(!empty($if_value))  {
						if(!isset($featimgfilters)) 
							$featimgfilters = Array();
						$featimgfilters['allow'][]=$allow ;
						$featimgfilters['woh'][]=$woh ;
						$featimgfilters['mol'][]=$mol ;
						$featimgfilters['value'][]=$if_value ;
					}
				}
			}
			$imfilters = (isset($post_data['featimgfilters']) && !empty($post_data['featimgfilters']) ) ? $post_data['featimgfilters'] : array() ;
			$campaign_data['featimgfilters']= (isset($featimgfilters) && !empty($featimgfilters) ) ? $featimgfilters : $imfilters ;
			
			// *** Campaign strip from phrase  
			$campaign_data['campaign_delfphrase'] = (isset($post_data['campaign_delfphrase'])) ? $post_data['campaign_delfphrase'] : null;
			$campaign_data['campaign_delfphrase_keep'] = (!isset($post_data['campaign_delfphrase_keep']) || empty($post_data['campaign_delfphrase_keep'])) ? false: ($post_data['campaign_delfphrase_keep']==1) ? true : false;
			// *** Campaign Keyword Filtering contain and not contain  
			$campaign_kwordf = (isset($post_data['campaign_kwordf']) && !empty($post_data['campaign_kwordf']) ) ? $post_data['campaign_kwordf'] : array();
			//must include
			$inc	= (isset($post_data['campaign_kwordf_inc'])) ? $post_data['campaign_kwordf_inc'] : null;
			$increg = (isset($post_data['campaign_kwordf_incregex'])) ? ($post_data['campaign_kwordf_incregex']) : null;
			$inctit = (!isset($post_data['campaign_kwordf_inc_tit']) || empty($post_data['campaign_kwordf_inc_tit'])) ? false: ($post_data["campaign_kwordf_inc_tit"]==1) ? true : false;
			$inccon = (!isset($post_data['campaign_kwordf_inc_con']) || empty($post_data['campaign_kwordf_inc_con'])) ? false: ($post_data["campaign_kwordf_inc_con"]==1) ? true : false;
			$inccat = (!isset($post_data['campaign_kwordf_inc_cat']) || empty($post_data['campaign_kwordf_inc_cat'])) ? false: ($post_data["campaign_kwordf_inc_cat"]==1) ? true : false;
			$inc_anyall = (!isset($post_data['campaign_kwordf_inc_anyall']) || empty($post_data['campaign_kwordf_inc_anyall'])) ? 'anyword': $post_data["campaign_kwordf_inc_anyall"];
			$campaign_kwordf['inc']= ( isset($campaign_kwordf['inc']) && !empty($campaign_kwordf['inc']) ) ? $campaign_kwordf['inc'] : $inc;
			$campaign_kwordf['incregex']= (isset($campaign_kwordf['incregex']) && !empty($campaign_kwordf['incregex']) ) ? $campaign_kwordf['incregex'] : $increg;
			$campaign_kwordf['inctit']= (isset($campaign_kwordf['inctit']) && !empty($campaign_kwordf['inctit']) ) ? $campaign_kwordf['inctit'] : $inctit; 
			$campaign_kwordf['inccon']= (isset($campaign_kwordf['inccon']) && !empty($campaign_kwordf['inccon']) ) ? $campaign_kwordf['inccon'] : $inccon;
			$campaign_kwordf['inccat']= (isset($campaign_kwordf['inccat']) && !empty($campaign_kwordf['inccat']) ) ? $campaign_kwordf['inccat'] : $inccat; 
			$campaign_kwordf['inc_anyall']= (isset($campaign_kwordf['inc_anyall']) && !empty($campaign_kwordf['inc_anyall']) ) ? $campaign_kwordf['inc_anyall'] : $inc_anyall;
			//must exclude
			$exc	= (isset($post_data['campaign_kwordf_exc'])) ? $post_data['campaign_kwordf_exc'] : null;
			$excreg = (isset($post_data['campaign_kwordf_excregex'])) ? ($post_data['campaign_kwordf_excregex']) : null;
			$exctit = (!isset($post_data['campaign_kwordf_exc_tit']) || empty($post_data['campaign_kwordf_exc_tit'])) ? false: ($post_data["campaign_kwordf_exc_tit"]==1) ? true : false;
			$exccon = (!isset($post_data['campaign_kwordf_exc_con']) || empty($post_data['campaign_kwordf_exc_con'])) ? false: ($post_data["campaign_kwordf_exc_con"]==1) ? true : false;			
			$exccat = (!isset($post_data['campaign_kwordf_exc_cat']) || empty($post_data['campaign_kwordf_exc_cat'])) ? false: ($post_data["campaign_kwordf_exc_cat"]==1) ? true : false;			
			$exc_anyall = (!isset($post_data['campaign_kwordf_exc_anyall']) || empty($post_data['campaign_kwordf_exc_anyall'])) ? 'anyword': $post_data["campaign_kwordf_exc_anyall"];
			$campaign_kwordf['exc']= (isset($campaign_kwordf['exc']) && !empty($campaign_kwordf['exc']) ) ? $campaign_kwordf['exc'] : $exc;
			$campaign_kwordf['excregex']= (isset($campaign_kwordf['excregex']) && !empty($campaign_kwordf['excregex']) ) ? $campaign_kwordf['excregex'] : $excreg;
			$campaign_kwordf['exctit']= (isset($campaign_kwordf['exctit']) && !empty($campaign_kwordf['exctit']) ) ? $campaign_kwordf['exctit'] : $exctit;
			$campaign_kwordf['exccon']= (isset($campaign_kwordf['exccon']) && !empty($campaign_kwordf['exccon']) ) ? $campaign_kwordf['exccon'] : $exccon;
			$campaign_kwordf['exccat']= (isset($campaign_kwordf['exccat']) && !empty($campaign_kwordf['exccat']) ) ? $campaign_kwordf['exccat'] : $exccat;
			$campaign_kwordf['exc_anyall']= (isset($campaign_kwordf['exc_anyall']) && !empty($campaign_kwordf['exc_anyall']) ) ? $campaign_kwordf['exc_anyall'] : $exc_anyall;
			$campaign_data['campaign_kwordf']=	$campaign_kwordf;

			$campaign_data['strip_all_images']	= (!isset($post_data['strip_all_images']) || empty($post_data['strip_all_images'])) ? false: ($post_data['strip_all_images']==1) ? true : false;
			$campaign_data['overwrite_image']	= (isset($post_data['overwrite_image']) && !empty($post_data['overwrite_image']) ) ? $post_data['overwrite_image'] : 'rename' ;
			$campaign_data['clean_images_urls']	= (!isset($post_data['clean_images_urls']) || empty($post_data['clean_images_urls'])) ? false: ($post_data['clean_images_urls']==1) ? true : false;
			$campaign_data['image_src_gettype']	= (!isset($post_data['image_src_gettype']) || empty($post_data['image_src_gettype'])) ? false: ($post_data['image_src_gettype']==1) ? true : false;
			$campaign_data['discardifnoimage']	= (!isset($post_data['discardifnoimage']) || empty($post_data['discardifnoimage'])) ? false: ($post_data['discardifnoimage']==1) ? true : false;
			$campaign_data['campaign_rssimg']	= (!isset($post_data['campaign_rssimg']) || empty($post_data['campaign_rssimg'])) ? false: ($post_data['campaign_rssimg']==1) ? true : false;
			$campaign_data['rssimg_enclosure']	= (!isset($post_data['rssimg_enclosure']) || empty($post_data['rssimg_enclosure']) ) ? false : ($post_data['rssimg_enclosure']) ? true : false;
			$campaign_data['rssimg_ifno'] 		= (!isset($post_data['rssimg_ifno']) || empty($post_data['rssimg_ifno']) ) ? false : ($post_data['rssimg_ifno']) ? true : false;
			$campaign_data['rssimg_add2img']	= (!isset($post_data['rssimg_add2img']) || empty($post_data['rssimg_add2img']) ) ? false : ($post_data['rssimg_add2img']) ? true : false;
			$campaign_data['add1stimg']			= (!isset($post_data['add1stimg']) || empty($post_data['add1stimg']) ) ? false : ($post_data['add1stimg']) ? true : false;
			$campaign_data['rssimg_featured'] 	= (!isset($post_data['rssimg_featured']) || empty($post_data['rssimg_featured']) ) ? false : ($post_data['rssimg_featured']) ? true : false;
			$campaign_data['which_featured'] 	= (!isset($post_data['which_featured'])) ? 'content1' : $post_data['which_featured'];		
			
			$campaign_data['campaign_enableimgrename'] 	= (!isset($post_data['campaign_enableimgrename']) || empty($post_data['campaign_enableimgrename']) ) ? false : ($post_data['campaign_enableimgrename']) ? true : false;
			$campaign_data['campaign_imgrename'] 	= (isset($post_data['campaign_imgrename']) && !empty($post_data['campaign_imgrename']) ) ? $post_data['campaign_imgrename'] : '{slug}';
			
			$campaign_data['default_img']		= (!isset($post_data['default_img']) || empty($post_data['default_img'])) ? false: ($post_data['default_img']==1) ? true : false;
			$campaign_data['default_img_url']	= (isset($post_data['default_img_url']) && !empty($post_data['default_img_url']) ) ? $post_data['default_img_url'] : '';
			$campaign_data['default_img_link']	= (isset($post_data['default_img_link']) && !empty($post_data['default_img_link']) ) ? $post_data['default_img_link'] : '';
			$campaign_data['default_img_title']	= (isset($post_data['default_img_title']) && !empty($post_data['default_img_title']) ) ? $post_data['default_img_title'] : '';

			$campaign_data['is_multipagefeed']	 = ( isset($post_data['is_multipagefeed']) && !empty($post_data['is_multipagefeed']) ) ? $post_data['is_multipagefeed'] : Array();
			$campaign_data['multifeed_maxpages'] = ( isset($post_data['multifeed_maxpages']) && !empty($post_data['multifeed_maxpages']) ) ? $post_data['multifeed_maxpages'] : Array();
			
			$campaign_data['activate_ramdom_rewrite']	= (!isset($post_data['activate_ramdom_rewrite']) || empty($post_data['activate_ramdom_rewrite'])) ? false: ($post_data['activate_ramdom_rewrite']==1) ? true : false;
			$campaign_data['ramdom_rewrite_count']	= (!isset($post_data['ramdom_rewrite_count']) || empty($post_data['ramdom_rewrite_count'])) ? '10': $post_data['ramdom_rewrite_count'];

			$campaign_data['words_to_rewrites']	= (!isset($post_data['words_to_rewrites']) || empty($post_data['words_to_rewrites'])) ? '': $post_data['words_to_rewrites'];
			
			
		// **** Return campaign_data
			return $campaign_data;			
		}
	}
}