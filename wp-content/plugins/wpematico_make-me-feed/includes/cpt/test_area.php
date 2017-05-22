<?php
// don't load directly 
//if ( !defined('ABSPATH') ) 
//	die('-1');

$nonce=$_REQUEST['_wpnonce'];
if ( !isset( $nonce ) ) {
	include('wp-includes/pluggable.php');
	if(!wp_verify_nonce($nonce, 'testa-nonce') ) wp_die('Are you sure?'); 
}
//include('../../../../wp-config.php');
if ( !defined('ABSPATH') ) {
	/** Set up WordPress environment */
	//require_once( '/wp-load.php');
	if( !(@include $_SERVER['DOCUMENT_ROOT'].'/wp-load.php') )
		if( !(@include $_SERVER['DOCUMENT_ROOT'].'../wp-load.php') )
		if( !(@include 'wp-load.php') )
		if( !(@include '../../../wp-load.php') )
		if( !(@include '../../../../wp-load.php') )
		if( !(@include '../../../../../wp-load.php') )
		if( !(@include '../../../../../../wp-load.php') )
		if( !(@include __DIR__ .'/../../../../../wp-load.php') )
			die('<H1>Can\'t include wp-load. Report to Technical Support form on http://etruel.com/support</H1>');
}
if ( isset( $_GET['p'] ) )
 	$post_id = $post_ID = (int) $_GET['p'];
elseif ( isset( $_POST['post_ID'] ) )
 	$post_id = $post_ID = (int) $_POST['post_ID'];
else
 	$post_id = $post_ID = 0;

add_action( 'wp_print_styles', 'rssv_styles', 100 );

function rssv_styles() {
	global $wp_styles;
	if ( !is_a( $wp_styles, 'WP_Styles' ) ) return;
//	echo "<pre>" . print_r($wp_styles,1) . "</pre>";
	/*
	 * De-register ALL WP styles for this screen
	 */
	foreach ( $wp_styles->registered as $handle => $style ) {
		$wp_styles->registered[$handle]->ver = null;
		wp_deregister_style( $handle );
	}
//	echo "<pre>" . print_r($wp_styles,1) . "</pre>";
}

header('Content-Type: text/html; charset='.get_bloginfo( 'charset' ) ); 

remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'stylesheet_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version

require_once( MAKE_ME_FEED_DIR . '/lib/simple_html_dom.php');
ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; us-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6');
ini_set('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; us-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6');

?>
<head>
<?php wp_head(); ?>
<style type="text/css">
h1 {
    font-size: 1.5em;
    display: block;
    margin: 0;
    border-bottom: #555 1px solid;
    color: #333;
	background-color: #E1DC9C;
	background: -moz-linear-gradient(center bottom,#FCF6BC 0,#E1DC9C 98%,#FFFEA8 0);
	background: -webkit-gradient(linear,left top,left bottom,from(#FCF6BC),to(#E1DC9C));
	-ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FCF6BC',endColorstr='#E1DC9C');
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FCF6BC',endColorstr='#E1DC9C');
}
body {
    margin: 0;
}
#mmf_URL, #single {
    margin: 0;
    padding: 4px 0 2px 15px;
    font-size: 1em;
    width: 100%;
    background: #037799;
    border: 0;
    border-bottom: #333 1px solid;
    color: #FFF;
}
</style>
<script>
jQuery(document).ready(function($){
	//$('#mmf_URL').val( '<?php echo __('Showing links to process on ', 'make-me-feed' ); ?>'+$('#mmf_URL', window.opener.document).val() );
});
</script>
</head>
<body>
<?php 
	$campaign = get_make_me_feed_meta($post_id);
	
	$mmf_URL = $campaign['mmf_URL'];
	
	$url_classes = $campaign['url_classes'];
	if(!($url_classes)) $url_classes = array();
	
	$subtitulo = __('Showing links to process on ', 'make-me-feed' ). $mmf_URL ;
  ?>
<h1>Test Area for <?php echo $post_id.": ".get_the_title($post_id); ?></h1>
<input class="cookieinput" type="text" value="<?php echo $subtitulo; ?>" id="mmf_URL" disabled name="jecho">
<?php 
  do_action('mmf_testarea_before_getcontent',$campaign);
/*	$campaign_cookies = $campaign['campaign_cookies'];
	if(!($campaign_cookies)) $campaign_cookies = array();
	foreach($campaign_cookies as $id => $cookie) {
		ini_set('Cookie: ', $cookie );  // "cityid=6"
	}
*/	
	//$html = file_get_html('http://noticierodelcine.com',true);
  
	
	if(class_exists('WPeMatico')){
		$content = get_transient('mmf_'.$post_id);
		if( $content===false ) {
			$args = apply_filters('mmf_getcontents_args', array('curl'=>TRUE) );
			$content = WPeMatico::wpematico_get_contents($mmf_URL, $args);
			set_transient("mmf_$mmf_URL$post_id", $content, HOUR_IN_SECONDS);
		}
		//$html = str_get_html($content);
		$html = new simple_html_dom();
		$html->load($content, true, false);
	}else{	
		$html = file_get_html($mmf_URL);
	}
	
	//$urls = $html->find("div.post div.buffer div.content b a');
	$urls = array();
	foreach($url_classes as $id => $maintags) {
		$urls = array_merge($urls, $html->find($maintags)); //$urls = $html->find('.posttitle a');
	}
	$urls = array_values( array_unique( $urls ) );
	$itemmax=0;
	foreach($urls as $a) {
		if( $itemmax++ >= (int)$campaign['mmf_max'] ) break;
		$source = untrailingslashit($mmf_URL);
		$a->href = rel2abs( $a->href, $source ); // rel2abs
		//
//		$a->href = preg_replace("#(?!http)([^\"'>]+)#", "$source$1$2", $a->href ); // rel2abs
//		$a->href = preg_replace('~(^|[^:])//+~', '\\1/', $a->href); // '//' -> '/'
/*		$anchor = $a->outertext();
		$anchor = preg_replace("#(<\s*a\s+[^>]*href\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#", "$1$source$2$3", $anchor); // rel2abs
		$a->outertext = preg_replace('~(^|[^:])//+~', '\\1/', $anchor); // '//' -> '/'
*/		
		echo $a->outertext();
		
		if( class_exists('extratests') && class_exists('wpematico_falseitem') && $campaign['mmf_fullcontent']){
			$link = $a->href;
//			$content = get_transient("mmf_$link");
//			if( $content===false ) {
			$test = new extratests();
			$content = $test->getthecontent($link);
			$content = substr($content, strpos($content, '/div>')+5);
			set_transient("mmf_$link", $content, HOUR_IN_SECONDS);
//			}
			
			if (!is_null($content) or !empty($content)) {
				if ( !is_string( $content ) ){
					$content = __( 'There is no html in content.', 'make-me-feed' );
				}else{
					if($campaign['mmf_parseURIsFC']) {  // parse urls relative to full
						$single = str_get_html($content);
						//all hyperlinks
						foreach($single->find('a') as $a) {
							$a->href = rel2abs( $a->href, $source ); // rel2abs
							$ddd = 0;
						}
						//all images
						foreach($single->find('img') as $img) {
							$img->src = rel2abs( $img->src, $source ); // rel2abs
						}
						$content = (string)$single;
						$single->clear();
						unset($single);
					}
					$content = "<br /><b><small>".__( 'Using WPeMatico Full Content AddOn to get the content:', 'make-me-feed' )."</small></b><hr>".$content;
				}
			}
			echo $content;
		}
		echo "<hr />";
	}

?>
</body>
</html>
