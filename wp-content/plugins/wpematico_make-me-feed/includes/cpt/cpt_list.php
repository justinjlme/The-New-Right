<?php
/**
 * Custom Post type Functions
 *
 * @package     WPeMatico\Make Me Feed\CPT
 * @since       1.0.0
 */

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

add_action('init', 'make_me_feed_cpt_init');
function make_me_feed_cpt_init(){
	$labels = array(
	  'name' => __('Make Me Feed',  'make-me-feed' ),
	  'singular_name' => __('Feed',  'make-me-feed' ),
	  'add_new' => __('Add New', 'make-me-feed' ),
	  'add_new_item' => __('Add New Feed', 'make-me-feed' ),
	  'edit_item' => __('Edit Feed', 'make-me-feed' ),
	  'new_item' => __('New Feed', 'make-me-feed' ),
	  'all_items' => __('All Feeds', 'make-me-feed' ),
	  'view_item' => __('View Feed', 'make-me-feed' ),
	  'search_items' => __('Search Feed', 'make-me-feed' ),
	  'not_found' =>  __('No Feed found', 'make-me-feed' ),
	  'not_found_in_trash' => __('No Feed found in Trash', 'make-me-feed' ), 
	  'parent_item_colon' => '',
	  'menu_name' => 'Make Me feed');
	$args = array(
		); 


	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'description' => 'Feed campaigns from different websites',
		'public' => false,
		'show_ui' => true,
		//'show_in_menu' => true,
		//'menu_position' => (get_option('wpem_menu_position')) ? 999 : 8,
		'show_in_menu' => 'wpematico',
//		'menu_position' => 5,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'has_archive' => false,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
	//  'menu_icon' => MAKE_ME_FEED_DIR.'/images/robotico_orange-25x25.png',
		'register_meta_box_cb' => 'make_me_feed_cpt_meta_boxes',
		'supports' => array( 'title' ),
//		'supports' => array( 'title', 'author', 'thumbnail' ),
	);
	
	register_post_type('make-me-feed',$args);

	add_action('save_post', 'make_me_feed_cpt_save_feeddata');
	add_filter('make_me_feed_pre_save_post','make_me_feed_save_classes');
	add_action('wp_ajax_checkfields', 'make_me_feed_cpt_CheckFields');
	add_action('wp_ajax_test_feed', 'make_me_feed_cpt_Test_feed');
	add_action('admin_print_styles-post.php', 'make_me_feed_cpt_admin_styles');
	add_action('admin_print_styles-post-new.php', 'make_me_feed_cpt_admin_styles');
	add_action('admin_print_scripts-post.php', 'make_me_feed_cpt_admin_scripts');
	add_action('admin_print_scripts-post-new.php', 'make_me_feed_cpt_admin_scripts');
	add_action('parent_file',   'make_me_feed_cpt_menu_correction');

	if( class_exists('extratests') && class_exists('wpematico_falseitem') ){
		add_action('make_me_feed_urls_box','make_me_feed_get_full_content',7);
		add_filter('make_me_feed_pre_save_post','make_me_feed_set_full_content_option');
	}
	
}


add_action( 'admin_menu', 'make_me_feed_cpt_menu',90 );	
function make_me_feed_cpt_menu(){
	$page = add_submenu_page(
		'edit.php?post_type=wpematico',
		__('All Feeds', 'make-me-feed' ),
		 __('Make Me Feed',  'make-me-feed' ),
		'manage_options',
		'edit.php?post_type=make-me-feed'
	);
//	add_action( 'admin_print_scripts-' . $page, 'make_me_feed_cpt_admin_scripts' );
	
}

function make_me_feed_cpt_menu_correction($parent_file) {
	global $current_screen;
	if ($current_screen->post_type == "make-me-feed") {
		$parent_file = 'edit.php?post_type=wpematico';
	}
	return $parent_file;
	
}

add_action( 'admin_init',  'make_me_feed_cpt_disable_autosave' );
function make_me_feed_cpt_disable_autosave() {
	global $post_type;
	if($post_type != 'make-me-feed') return;
	wp_deregister_script( 'autosave' );
}
	
function make_me_feed_cpt_admin_styles(){
	global $post;
	if($post->post_type != 'make-me-feed') return $post->ID;
	wp_enqueue_style('make-me-feed_styles',MAKE_ME_FEED_URL .'app/css/make-me-feed_styles.css');	
	add_action('admin_head', 'make_me_feed_cpt_admin_head_style' );
}

function make_me_feed_cpt_admin_scripts(){
	global $post;
	if($post->post_type != 'make-me-feed') return $post->ID;
	wp_enqueue_script('jquery-vsort'); 
	wp_dequeue_script( 'autosave' );
	add_action('admin_head', 'make_me_feed_cpt_admin_head');
}

function make_me_feed_cpt_admin_head_style() {
	global $post;
	if($post->post_type != 'make-me-feed') return $post_id;
	?><style type="text/css">
#msgdrag {display:none;color:red;padding: 0 0 0 20px;font-weight: 600;font-size: 1em;}
.classes_header {padding: 0 0 0 30px;font-weight: 600;font-size: 0.9em;}
div.classes_column {float: left;width: 80%;}
#feed_actions{margin-left: 5px;}

#feed_actions .bicon, #w2cactions .bicon{
	font-size: 1.6em;
	width: 24px;
	text-align: center;
	padding: 5px 2px;
}
.rowactions{margin: 1.7em; display: flex;}
.rowflex{display: flex;}
.rowblock{display: block;}

.delete{color: #F88;}
.delete:hover{color: red;}
.warning{color: #eb9600; opacity: 0.6;}
.warning:hover{opacity: 1;}
.classes_header{
	background-color:#E1DC9C;
	background:-moz-linear-gradient(center bottom,#FCF6BC 0,#E1DC9C 98%,#FFFEA8 0);
	background:-webkit-gradient(linear,left top,left bottom,from(#FCF6BC),to(#E1DC9C));
	-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#FCF6BC',endColorstr='#E1DC9C');
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#FCF6BC',endColorstr='#E1DC9C');
	border:1px solid #BCBCBC;border-bottom-style:none;margin-top:-1px;overflow:hidden;padding:3px 10px}
.classes_header input{color:#999;font-size:13px;padding:0px;width:227px}
.classes_header .classes_column:first-child{padding-left:20px;}
#classes_list {background:#E2E2E2;}
.sortitem{background:#fff;border:2px solid #ccc;padding-left:20px; display: flex;}
.sortitem .sorthandle{position:absolute;top:5px;bottom:5px;left:3px;width:8px;display:none;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAB3RJTUUH3wIDBycZ/Cj09AAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAAAWSURBVHjaY2DABhoaGupBGMRmYiAEAKo2BAFbROu9AAAAAElFTkSuQmCC');}
.sortitem:hover .sorthandle{display:block;}
.classes_column input {font-size: 1em !important;}
	
	.delete:before { content: "\2718";}
	.add:before { content: "\271A";}
	.isok:before { content: "\2714";}
	.cross:before { content: "\2716"; }
	.warning:before { content: "\26A0"; }
	.frowning_face:before { content: "\2639"; }
	.smiling_face:before { content: "\263A"; }
	#checkURL {    
		position: absolute;
		margin-left: -50px;
		font-size: 24px;
		padding: 5px;
	}
	.wpeurllink {
		background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAVklEQVR4Xn3PgQkAMQhDUXfqTu7kTtkpd5RA8AInfArtQ2iRXFWT2QedAfttj2FsPIOE1eCOlEuoWWjgzYaB/IkeGOrxXhqB+uA9Bfcm0lAZuh+YIeAD+cAqSz4kCMUAAAAASUVORK5CYII=) center right no-repeat;
		position: absolute;
		margin-left: -25px;
		padding: 10px;
	}
	.ruedita{background: url(<?php echo admin_url('images/spinner.gif'); ?>) no-repeat;height: 10px;margin: 5px 3px 0;}
</style>
	<?php
}
function make_me_feed_cpt_admin_head() {
	global $post;
	if($post->post_type != 'make-me-feed') return $post_id;
	$post->post_password = '';
	$visibility = 'public';
	$visibility_trans = __('Public');
	$linktestarea='<div class="clear left he20"><p class="m4">';
	$nonce= wp_create_nonce('testa-nonce');
	$nombre = get_the_title($post->ID);
	$actionurl = MAKE_ME_FEED_URL . 'includes/cpt/test_area.php?p='.$post->ID.'&_wpnonce=' . $nonce;
	//$actionjs = "javascript:window.open('$actionurl','$nombre','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=600');";
	$actionjs = "window.open('$actionurl','$nombre','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=700, height=600');";
	$linktestarea .= "<a href=\"#Run-test-area\" id=\"runtest\" class=\"button-primary\" onclick=\"return false;\" title=\"" . esc_attr(__('Run Test Area. (Open a PopUp window)', 'make-me-feed')) . "\">" . __('Test Area', 'make-me-feed') . "</a>";
	$linktestarea .= '</p></div>';
	?>
	<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($){
		//try {
		jQuery('#post-visibility-display').text('<?php echo $visibility_trans; ?>');
		jQuery('#hidden-post-visibility').val('<?php echo $visibility; ?>');
		jQuery('#visibility-radio-<?php echo $visibility; ?>').attr('checked', true);
		jQuery('#delete-action').after('<?php echo $linktestarea; ?>');

		jQuery("input, select").live('change', (function() {
			jQuery('#runtest').removeClass('button-primary');
			jQuery('#runtest').addClass('button-secondary');
			$('#runtest').css('color','#ccc');
			$('#runtest').unbind('click');
			$('#runtest').attr('title','<?php _e('You must save before Test', 'make-me-feed'); ?>');
			console.log("changed...");
			isChanged = true;
		})); 
		jQuery('#runtest').click(function() {
		<?php echo $actionjs; ?>
		});	
		
		
		jQuery('#mmf_fullcontent').click(function() {
			if ( true == jQuery('#mmf_fullcontent').is(':checked')) {
				jQuery('#parsefulluris').fadeIn();
			} else {
				jQuery('#parsefulluris').fadeOut();
			}
		});

		$('#addmoreclass').click(function() {
			oldval = $('#feedfield_max').val();
			jQuery('#feedfield_max').val( parseInt(jQuery('#feedfield_max').val(),10) + 1 );
			newval = $('#feedfield_max').val();
			feed_new= $('.feed_new_field').clone();
			$('div.feed_new_field').removeClass('feed_new_field');
			$('div#feed_ID'+oldval).fadeIn();
			$('input[name="url_classes['+oldval+']"]').focus();
			feed_new.attr('id','feed_ID'+newval);
			$('input', feed_new).eq(0).attr('name','url_classes['+ newval +']');
			$('.delete', feed_new).eq(0).attr('onclick', "delete_class_or_id('#feed_ID"+ newval +"');");
			$('#classes_list').append(feed_new);
			$('#classes_list').vSort();
		});

		delete_class_or_id = function(row_id){
			jQuery(row_id).fadeOut(); 
			jQuery(row_id).remove();
			disable_run_now();
			jQuery('#msgdrag').html('<?php _e('Update Campaign to save changes.', WPeMatico :: TEXTDOMAIN ); ?>').fadeIn();
		}

		delete_row_input = function(row_id){
			jQuery(row_id).fadeOut('slow', function() { $(this).remove(); });
			disable_run_now();
		}

		
	});	
	</script>
	<?php
}



/**
 * Meta boxes
 * @global type $post
 * @global type $current_screen
 */

function make_me_feed_cpt_meta_boxes() {
		global $post, $current_screen; 
		require( dirname( __FILE__ ) . '/cpt_help.php' );
	//	add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
		add_meta_box( 'urls-box', __('Main URL to get the titles for this Feed', 'make-me-feed' ), 'make_me_feed_urls_box','make-me-feed','normal', 'default' );
		add_action('make_me_feed_urls_box', 'make_me_feed_title_id_classes'); 
		// Publish Meta_box edited
		add_action('post_submitbox_start', 'make_me_feed_submitbox_start'); 

		do_action('make_me_feed_metaboxes',$post); 
}


// Action handler - The 'Save' button is about to be drawn on the advanced edit screen.
function make_me_feed_urls_box( $post ) {  
		global $post;
		$mmf_URL = get_post_meta($post->ID,'mmf_URL',TRUE);
		$mmf_max = (int) get_post_meta($post->ID,'mmf_max',TRUE);
		?>  
		<div class="website_header">
			<div><?php _e('Source index URL', 'make-me-feed'  ) ?></div>
			<input name="mmf_URL" type="text" value="<?php echo $mmf_URL ?>" class="large-text feedinput"/>
			<?php if (!filter_var($mmf_URL, FILTER_VALIDATE_URL) === false) : ?>
				<a href="<?php echo $mmf_URL ?>" title="<?php _e('Open the URL in a new browser tab', 'make-me-feed' ); ?>" target="_Blank" class="wpeurllink"></a>
			<?php endif; ?>
			<?php /*<label title="<?php _e('Check if this item work', 'make-me-feed' ); ?>" id="checkURL" class="bicon warning"></label>*/ ?>
		</div>
		<p>
			<input name="mmf_max" type="number" min="0" size="3" value="<?php echo $mmf_max;?>" class="small-text" id="mmf_max"/> 
			<label for="mmf_max"><?php echo __('Max items titles to get from source.', 'make-me-feed' ); ?></label>
		</p>
		<?php do_action('make_me_feed_urls_box'); ?>
	<?php
}

function make_me_feed_get_full_content()	{
		global $post;
		$mmf_fullcontent = get_post_meta($post->ID,'mmf_fullcontent',TRUE);
		$mmf_parseURIsFC = get_post_meta($post->ID,'mmf_parseURIsFC',TRUE);
?>	
	<p>
		<input name="mmf_fullcontent" type="checkbox" value="1" <?php checked($mmf_fullcontent,true) ?> class="checkbox" id="mmf_fullcontent"/> 
		<label for="mmf_fullcontent"><?php echo __('Get Full Content from source permalinks to use as feed item content.', 'make-me-feed' ); ?></label>
	</p>
	<p id="parsefulluris" style="<?php ($mmf_fullcontent) ? 'display: block;' : '' ?>">
		<input name="mmf_parseURIsFC" type="checkbox" value="1" <?php checked($mmf_parseURIsFC,true) ?> class="checkbox" id="mmf_parseURIsFC"/> 
		<label for="mmf_parseURIsFC"><?php echo __('Parse relative links and images from full Content.', 'make-me-feed' ); ?></label>
	</p>
<?php
}

function make_me_feed_title_id_classes()	{
		global $post, $campaign_data, $cfg, $helptip;
		$url_classes = get_post_meta($post->ID,'url_classes',TRUE);
		?>  
		<div class="classes_header">
			<div class="classes_column"><?php _e('CSS Classes or IDs in source code to get the permalinks.', 'make-me-feed'  ) ?></div>
		</div>
		<div id="classes_list" class="maxhe290" data-callback="jQuery('#msgdrag').html('<?php _e('Update Campaign to save classes order', 'make-me-feed'  ); ?>').fadeIn();"> <!-- callback script to run on successful sort -->
			<?php 
				for ($i = 0; $i <= count(@$url_classes); $i++) : ?>
				<?php $feed = @$url_classes[$i]; ?>			
				<?php $lastitem = $i==count(@$url_classes); ?>			
				<div id="feed_ID<?php echo $i; ?>" class="sortitem <?php if(($i % 2) == 0) echo 'bw'; else echo 'lightblue'; ?> <?php if($lastitem) echo 'feed_new_field'; ?> " <?php if($lastitem) echo 'style="display:none;"'; ?> > <!-- sort item -->
					<div class="sorthandle"> </div> <!-- sort handle -->
					<div class="classes_column" id="">
						<input name="url_classes[<?php echo $i; ?>]" type="text" value="<?php echo $feed ?>" class="large-text feedinput"/><a href="<?php echo $feed ?>" title="<?php _e('Open URL in a new browser tab', 'make-me-feed' ); ?>" target="_Blank" class="wpefeedlink"></a>
					</div>
					<div class="" id="feed_actions">
						<label title="<?php _e('Delete this item',  'make-me-feed'  ); ?>" onclick="delete_class_or_id('#feed_ID<?php echo $i; ?>');" class="bicon delete left"></label>
					</div>
				</div>
				<?php $a=$i;
			endfor; ?>
		</div>
		<input id="feedfield_max" value="<?php echo $a; ?>" type="hidden" name="feedfield_max">
		<div id="paging-box">		  
			<a href="JavaScript:void(0);" class="button-primary add" id="addmoreclass" style="font-weight: bold; text-decoration: none;"> <?php _e('Add Class or ID', 'make-me-feed'  ); ?>.</a> <label id="msgdrag"></label>
		</div>
	<?php
	
}

// Save array of classes
function make_me_feed_set_full_content_option($mmfdata) {
	$mmfdata['mmf_fullcontent'] = (!isset($_POST['mmf_fullcontent']) || empty($_POST['mmf_fullcontent']) ) ? false: ($_POST['mmf_fullcontent']==1) ? true : false; 
	$mmfdata['mmf_parseURIsFC'] = (!isset($_POST['mmf_parseURIsFC']) || empty($_POST['mmf_parseURIsFC']) ) ? false: ($_POST['mmf_parseURIsFC']==1) ? true : false; 
	return $mmfdata;
}

// Save array of classes
function make_me_feed_save_classes($mmfdata) {
	$url_classes = array();
	$all_classes = ( isset($_POST['url_classes']) && !empty($_POST['url_classes']) ) ? $_POST['url_classes'] : Array();
	if( !empty($all_classes) ) {  // Proceso los feeds sacando los que estan en blanco
		foreach($all_classes as $id => $feedname) {
			if(!empty($feedname)) 
				$url_classes[]=$feedname ;
		}
	}
	$mmfdata['url_classes'] = (array)$url_classes ;
	return $mmfdata;
}

// Action handler - The 'Save' button is about to be drawn on the advanced edit screen.
function make_me_feed_submitbox_start()	{
	global $post;
	if($post->post_type != 'make-me-feed') return $post->ID;		
	wp_nonce_field( 'edit-make-me-feed', 'makemefeed_nonce' ); 
}


//************************* GRABA CAMPAÑA *******************************************************
function make_me_feed_cpt_save_feeddata( $post_id ) {
	global $post;
	// Stop WP from clearing custom fields on autosave, and also during ajax requests (e.g. quick edit) and bulk edits.
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']))
		return $post_id;
	if ( !wp_verify_nonce( @$_POST['makemefeed_nonce'], 'edit-make-me-feed' ) )
		return $post_id;
	if($post->post_type != 'make-me-feed') return $post_id;

	$nivelerror = error_reporting(E_ERROR | E_WARNING | E_PARSE);

	$mmfdata['mmf_URL'] = (isset($_POST['mmf_URL']) && !empty($_POST['mmf_URL']) ) ? $_POST['mmf_URL'] : '';
	$mmfdata['mmf_max'] = (isset($_POST['mmf_max']) && !empty($_POST['mmf_max']) ) ? (int) $_POST['mmf_max'] : 0;

	$mmfdata = apply_filters('make_me_feed_pre_save_post', $mmfdata);
	
	foreach($mmfdata as $key => $value) {
		add_post_meta($post_id, $key, $value, true) or update_post_meta($post_id, $key, $value);
	}
	do_action('make_me_feed_post_saved');
}
