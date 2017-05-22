<?php
/**
 * Template Name: MAKE-ME-FEED for RSS
 * Description: Show as RSS the content obtained from the feed campaign
 *
 * @package Make Me Feed Plugin
 * 
 */
?>
<?php
function wpeapp_rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}
 
function wpeapp_rss_text_limit($string, $length, $replacer = '...') { 
  $string = strip_tags($string);
  if(strlen($string) > $length) 
    return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;   
  return $string; 
}
 
require_once(plugin_dir_path(__FILE__)."/../lib/simple_html_dom.php");

$post_id = $post->ID;

$campaign = get_make_me_feed_meta($post_id);

$mmf_URL = $campaign['mmf_URL'];	

$url_classes = $campaign['url_classes'];
if(!($url_classes)) $url_classes = array();

$subtitulo = __('Showing links to process on ', 'make-me-feed' ). $mmf_URL ;

do_action('mmf_testarea_before_getcontent',$campaign);

if(class_exists('WPeMatico')){
	$content = get_transient("mmf_$mmf_URL$post_id");
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

header("Content-Type: application/rss+xml; charset=".get_bloginfo( 'charset' ) );
echo '<?xml version="1.0"?>';
?><rss version="2.0">
<channel>
  <title>WPeMatico Make Me Feed 'Good'</title>
  <link>http://etruel.com/downloads/wpematico-make-me-feed/</link>
  <description>WPeMatico Make Me Feed just make this feed with the data from a campaign posted by someone in this website.</description>
  <language>en-us</language>
  <pubDate><?php wpeapp_rss_date( time() ); ?></pubDate>
  <lastBuildDate><?php wpeapp_rss_date( time() ); ?></lastBuildDate>
  <managingEditor></managingEditor>
  <?php 
	$itemmax=0;
	foreach($urls as $a) {
		if( $itemmax++ >= (int)$campaign['mmf_max'] ) break;
		$source = untrailingslashit($mmf_URL);
		$a->href = rel2abs( $a->href, $source ); // rel2abs
		$link = $a->href;
		$title = '<![CDATA['.trim($a->text()).']]>';
		if( class_exists('extratests') && class_exists('wpematico_falseitem') && $campaign['mmf_fullcontent'] ){
			$content = get_transient("mmf_$link");
			if( $content===false ) {
				$test = new extratests();
				$content = $test->getthecontent($link);
				$content = substr($content, strpos($content, '/div>')+5);
				set_transient("mmf_$link", $content, HOUR_IN_SECONDS);
			}
			
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
					//$content .= "<br />".__( 'Using WPeMatico Full Content AddOn to get the content:', 'make-me-feed' );
				}
			}
		}else{
			$date = ''; // $a->outertext();
			$content = ''; // $a->outertext();
		}
?><item>
    <title><?php echo $title; ?></title>
    <link><?php echo $link; ?></link>
    <description><?php echo '<![CDATA['. ( (isset($content) && !empty($content)) ? $content : '') . '<br/>'.']]>';  ?></description>
    <pubDate><?php wpeapp_rss_date( strtotime( $date) ); ?></pubDate>
    <guid><?php echo $link; ?></guid>
  </item>
  <?php 
	}
?></channel>
</rss>