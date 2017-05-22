<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
* Retrieve tools tabs
* @since       1.2.4
* @return      array
*/
function wpematicopro_prosettings_tabs($tabs) {
	$protab = array('prosettings' => '<img src="' . WPeMaticoPRO :: $uri.'images/administrator_16.png'.'" style="margin: 0pt 2px -2px 0pt;">' . __( 'PRO Settings', WPeMatico::TEXTDOMAIN ) );
	$tabs = array_slice($tabs, 0, 1, true) + $protab + array_slice($tabs, 1, count($tabs)-1, true);
	return $tabs;
}
add_filter( 'wpematico_settings_tabs',  'wpematicopro_prosettings_tabs');


add_action( 'wpematico_settings_tab_prosettings', 'wpematico_prosettings' );
function wpematico_prosettings(){
	global $cfg;
	$cfg = get_option(WPeMaticoPRO::OPTION_KEY);
	?><form method="post" action="<?php admin_url( 'edit.php?post_type=wpematico&page=pro_settings'); ?>">
			<?php wp_nonce_field('wpematicopro-settings'); ?>
			<div class="wrap">
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<div id="side-info-column" class="inner-sidebar">
						<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox">
						<h3 class="handle"><?php _e( 'About', WPeMatico :: TEXTDOMAIN );?></h3>
						<div class="inside">
							<p id="left1" style="text-align:center;">
								<a href="http://etruel.com/downloads/wpematico-professional/" target="_Blank" title="Go to new etruel WebSite">
									<img style="width: 100%; background: transparent;border-radius: 15px;" src="http://etruel.com/wp-content/uploads/edd/2016/04/wpematico_professional-440x220.png" title="">
								</a><br />
								<b>WPeMatico PRO <?php echo WPEMATICOPRO_VERSION ; ?></b>
							</p>
							<p><?php _e( 'Thanks for use and enjoy this plugin.', WPeMatico :: TEXTDOMAIN );?></p>
							<p><?php _e( 'If you like it and want to thank, you can write a 5 star review on Wordpress.', WPeMatico :: TEXTDOMAIN );?></p>
							<style type="text/css">#linkrate:before { content: "\2605\2605\2605\2605\2605";font-size: 18px;}
							#linkrate { font-size: 18px;}</style>
							<p style="text-align: center;">
								<a href="https://wordpress.org/support/view/plugin-reviews/wpematico?filter=5&rate=5#postform" id="linkrate" class="button" target="_Blank" title="Click here to rate plugin on Wordpress">  Rate</a>
							</p>
							<p></p>
						</div>
						</div>
							
						<div class="postbox"><div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br></div>
							<h3 class="handle"><?php _e( 'Advanced Features', WPeMatico :: TEXTDOMAIN );?></h3>
							<div class="inside">
								<p></p>
								<label><input class="checkbox" value="1" type="checkbox" <?php @checked($cfg['enableeximport'],true); ?> name="enableeximport" id="enableeximport" /> <?php _e('Enable <b><i>Export/Import</i></b> single Campaign', WPeMatico :: TEXTDOMAIN ); ?></label>
								<p></p>
							</div>
						</div>
						<div class="postbox">
							<div class="inside">
								<p>
								<input type="hidden" name="wpematico-action" value="save_prosettings" />
								<?php submit_button( __( 'Save settings', WPeMatico :: TEXTDOMAIN ), 'primary', 'wpematico-save-prosettings', false ); ?>
								</p>
							</div>
						</div>

						<?php do_action('wpematico_wp_ratings'); ?>
						</div>
					</div>
					<div id="post-body">
						<div id="post-body-content">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox inside">
									<h3><?php _e( 'PRO options', WPeMatico :: TEXTDOMAIN );?></h3>
									<div class="inside">
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enablekwordf'],true); ?> name="enablekwordf" id="enablekwordf" /> <?php _e('Enable <b><i>Keyword Filtering</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('If you need this feature in every campaign, you can activate here.  Not recommended if you will not use this.', WPeMatico :: TEXTDOMAIN ); ?><br /> 
											<?php _e('This is for exclude or include posts according to the keywords <b>found</b> at content or title.', WPeMatico :: TEXTDOMAIN ); ?>
											</div><br /> 
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enablewcf'],true); ?> name="enablewcf" id="enablewcf" /> <?php _e('Enable <b><i>Word count Filters</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('If you need this feature in every campaign, you can activate here.  Not recommended if you will not use this.', WPeMatico :: TEXTDOMAIN ); ?><br /> 
											<?php _e('This is for cut, exclude or include posts according to the letters o words <b>counted</b> at content.', WPeMatico :: TEXTDOMAIN ); ?>
											</div><br /> 
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enablecustomtitle'],true); ?> name="enablecustomtitle" id="enablecustomtitle" /> <?php _e('Enable <b><i>Custom Title</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('If you want a custom title for posts of a campaign, you can activate here.  Not recommended if you will not use this.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br /> 
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enableauthorxfeed'],true); ?> name="enableauthorxfeed" id="enableauthorxfeed" /> <?php _e('Enable <b><i>Author per feed</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('This option allow you assign an author per feed when editing campaign.  If no choice any author, the campaign author will be taken.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br />
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enableimportfeed'],true); ?> name="enableimportfeed" id="enableimportfeed" /> <?php _e('Enable <b><i>Import feed list</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('On campaign edit you can import, copy & paste in a textarea field, a list of feed addresses with/out author names.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br />
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enablemultifeed'],true); ?> name="enablemultifeed" id="enablemultifeed" /> <?php _e('Enable <b><i>Multipaged</i></b> feeds feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('On campaign edit you can set the fetching process to multipaged RSS feeds.  Like https://etruel.com/feed/?paged=1', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br />
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enabletags'],true); ?> name="enabletags" id="enabletags" onclick="if(true==jQuery(this).is(':checked')) jQuery('#badtags').fadeIn(); else jQuery('#badtags').fadeOut();" /> <?php _e('Enable <b><i>Auto Tags</i></b> feature.', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('This feature generate tags automatically on every post fetched, on campaign edit you can disable auto feature and manually enter a list of tags or leave empty.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div>
											<div id="badtags" style="margin-left:20px;<?php if(!$cfg['enabletags']) echo 'display:none;';?>">
											<b><?php echo '<label for="all_badtags">' . __('Bad Tags that will be not used on all posts:', WPeMatico :: TEXTDOMAIN ) . '</label>'; ?></b><br />
											<textarea style="width:600px;" id="all_badtags" name="all_badtags"><?php echo stripslashes(@$cfg['all_badtags']); ?></textarea><br />
											<?php echo __('Enter comma separated list of excluded Tags in all campaigns.', WPeMatico :: TEXTDOMAIN ); ?>
											</div><br />
											
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enablecfields'],true); ?> name="enablecfields" id="enablecfields" /> <?php _e('Enable <b><i>Custom Fields</i></b> feature.', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('Add custom fields with values as templates on every post.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br />
											
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enableimgfilter'],true); ?> name="enableimgfilter" id="enableimgfilter" /> <?php _e('Enable <b><i>Image Filters</i></b> feature.', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('You can allow or skip each image in every post depends on image dimensions.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div><br />
											<input class="checkbox" value="1" type="checkbox" <?php checked(@$cfg['enableimgrename'],true); ?> name="enableimgrename" id="enableimgrename" /> <?php _e('Enable <b><i>Image Rename</i></b> feature.', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php _e('Change the name of each image uploaded to the website, predefined with custom tags.', WPeMatico :: TEXTDOMAIN ); ?><br />
											</div>
											<br /> 
											<input class="checkbox" value="1" type="checkbox" <?php checked($cfg['enable_ramdom_words_rewrites'],true); ?> name="enable_ramdom_words_rewrites" id="enable_ramdom_words_rewrites" /> <?php _e('Enable <b><i>Ramdom Rewrites</i></b> feature', WPeMatico :: TEXTDOMAIN ); ?><br />
											<div id="hlpw2c" style="padding-left:20px;"><?php echo (($cfg['enable_ramdom_words_rewrites'])? sprintf(__('Rewrite custom words randomly as synonyms.  You must complete the words separated by comma and per line in the <a href="%s">textarea</a>.', WPeMatico :: TEXTDOMAIN ), admin_url('edit.php?post_type=wpematico&page=wpematico_settings&tab=ramdom_rewrites')) : __('Rewrite custom words randomly as synonyms.  You must complete the words separated by comma and per line in the textarea.', WPeMatico :: TEXTDOMAIN )); ?>
											
											</div><br /> 
											<p>

												<input type="hidden" name="wpematico-action" value="save_prosettings" />
												<?php submit_button( __( 'Save settings', WPeMatico :: TEXTDOMAIN ), 'primary', 'wpematico-save-prosettings', false ); ?>

											</p>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
<?php
}

add_action( 'wpematico_save_prosettings', 'wpematicopro_prosettings_save' );
function wpematicopro_prosettings_save() {
	if ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) {
		if ( get_magic_quotes_gpc() ) {
			$_POST = array_map( 'stripslashes_deep', $_POST );
		}

		# evaluation goes here
		check_admin_referer('wpematicopro-settings');
		$cfg = array();

		$errlev = error_reporting();
		error_reporting(E_ALL & ~E_NOTICE);  // desactivo los notice que aparecen con los _POST

		$cfg['enablekwordf']		= $_POST['enablekwordf']==1 ? true : false;
		$cfg['enablewcf']			= $_POST['enablewcf']==1 ? true : false;
		$cfg['enablecustomtitle']	= $_POST['enablecustomtitle']==1 ? true : false;
		$cfg['enablefullcontent']	= $_POST['enablefullcontent']==1 ? true : false;
		$cfg['enableauthorxfeed']	= $_POST['enableauthorxfeed']==1 ? true : false;
		$cfg['enableimportfeed']	= $_POST['enableimportfeed']==1 ? true : false;
		$cfg['enablemultifeed']	= $_POST['enablemultifeed']==1 ? true : false;
		$cfg['enabletags']			= $_POST['enabletags']==1 ? true : false;
		$cfg['all_badtags']			= $_POST['all_badtags'];
		$cfg['enablecfields']		= $_POST['enablecfields']==1 ? true : false;
		$cfg['enableimgfilter']	= $_POST['enableimgfilter']==1 ? true : false;
		$cfg['enableimgrename']	= $_POST['enableimgrename']==1 ? true : false;
		$cfg['enableeximport']	= $_POST['enableeximport']==1 ? true : false;
		$cfg['enable_ramdom_words_rewrites']	= $_POST['enable_ramdom_words_rewrites']==1 ? true : false;

		if( update_option( WPeMaticoPRO::OPTION_KEY, $cfg ) ) {
			WPeMatico::add_wp_notice( array('text' => __('Settings saved.',  WPeMatico :: TEXTDOMAIN), 'below-h2'=>false ) );			
		}
		error_reporting($errlev);
		wp_redirect( admin_url( 'edit.php?post_type=wpematico&page=wpematico_settings&tab=prosettings') );

	}
}