<?php
/** 
 *  @package WPeMatico Full Content
**/
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

add_action('Wpematico_init_fetching', 'wpemfullcontent_init_fetching'); //hook for add actions and filter on init fetching
function wpemfullcontent_init_fetching($campaign) {  // chequea y agrega campos a campaign y graba en free
	if ($campaign['campaign_fullcontent'] || $campaign['campaign_ogimage'] ) {
		// get entire content of source
		add_filter('wpepro_getfullcontent', 'wpemfullcontent_getcontent',10,3 );
		add_filter('wpematico_item_parsers', 'exclfiltersAfterFull',1, 4);
		add_filter('wpematico_item_parsers', 'full_img1s',1, 4);
		
		// process and parses the content, the title and other fields from full content
		add_filter('wpematico_get_post_content', 'wpemfullcontent_content',10,4 );
	}
}

function exclfiltersAfterFull($current_item, $campaign, $feed, $item) {
	if($current_item == -1) {
		return -1;
	}
	$enablekwordf = false;
	if (class_exists('WPeMaticoPRO')) {
		$cfg = get_option(WPeMaticoPRO::OPTION_KEY);
		if (@$cfg['enablekwordf']) {
			$campaign_kwordfinc=(isset($campaign['campaign_kwordf']['inc']) && !empty($campaign['campaign_kwordf']['inc']) ) ? true : false;
			$campaign_kwordfexc=(isset($campaign['campaign_kwordf']['exc']) && !empty($campaign['campaign_kwordf']['exc']) ) ? true : false;
			if ($campaign_kwordfinc || $campaign_kwordfexc ) {
				$enablekwordf = true;
			}
		}
	}

	$skip = false;
	if ($enablekwordf) {
		trigger_error(sprintf(__('Processing Keyword filtering after full content: %1s','wpematico'), $current_item['title']),E_USER_NOTICE);
		if(!KeywordFilterAfterFull($current_item, $campaign, $item, true)) {
			$skip = true;
		}
	}
	if ($skip) {
		$current_item = -1;
	}
	return $current_item;
}

function KeywordFilterAfterFull(&$current_item, &$campaign, &$item, $user_current = false ) {
		if (!function_exists('wpempro_contains')) {
			require_once WPeMaticoPRO::$dir.'includes/functions.php';
		}
		// Item content  //Todavia no tengo los contenidos (chequea los del feed)
		$content = $item->get_content(); //$current_item['content'];
		$title = $item->get_title(); //$current_item['title'];
		if ($user_current) {
			$content = $current_item['content'];
			$title = $current_item['title'];
		}
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
} 


function wpemfullcontent_getcontent($permalink, $campaign, $html='') {
	if( !isset($campaign['campaign_usecurl']) || !$campaign['campaign_usecurl'] ){  // no usa curl
		$html = WPeMatico::wpematico_get_contents($permalink, false);
	} else {  // usa curl y si no anda el resto
		$html = WPeMatico::wpematico_get_contents($permalink, true);
	}
	$charset = '';
	//Convert all to UTF-8  - May be will add an option in the campaign to avoid this
	if ($charset == '') {
		$meta_tags = wpematico_getMetaTags($html);
		if (!empty($meta_tags['content-type'])) {
			if (preg_match("#.+?/.+?;\\s?charset\\s?=\\s?(.+)#i", $meta_tags['content-type'], $m)) {
			    $charset = $m[1];
		    }
		} else {
			if (preg_match('~<meta ([\w ]*)charset([\w ]*)=([\w ]*)"([-a-z0-9_]+)"~i', $html, $m)) {
				$charset = $m[4];
			}
		}

		/*$dd = new DOMDocument;
		$dd->loadHTML($html);
		foreach ($dd->getElementsByTagName("meta") as $m) {
		    if (strtolower($m->getAttribute("http-equiv")) == "content-type") {
		        $v = $m->getAttribute("content");
		        if (preg_match("#.+?/.+?;\\s?charset\\s?=\\s?(.+)#i", $v, $m)) {
		            $charset = $m[1];
		            break;
		        }
		    }
		}
		*/
	}
	if ($charset == '') {  // This is other request to $permalink, should be after meta charset detection because waste more performance.
		$response_header = wp_remote_get($permalink);
		$content_type = wp_remote_retrieve_header($response_header, 'content-type');
		if (!empty($content_type)) {
			if (preg_match("#.+?/.+?;\\s?charset\\s?=\\s?(.+)#i", $content_type, $m)) {
		        $charset = $m[1];
		    }
		}
	}
	if ($charset == '') {
		$charset = mb_detect_encoding($html, "auto");
	}
	if ($charset && strtolower($charset) != strtolower('UTF-8')) {
		$html = mb_convert_encoding($html, 'UTF-8', $charset);
	}
	return $html;
}

add_filter('wpem_video_regexp', 'wpemfullcontent_add_video_regexp', 15, 2);
function wpemfullcontent_add_video_regexp( $regexvideo ) {  // add video sites
	$website_videos = array();
	$website_videos[0] = 'youtu.com';
	$website_videos[1] = 'youtube.com';
	$website_videos[2] = 'dailymotion.com';
	$website_videos[3] = 'natabanu.com';
	$website_videos[4] = 'vimeo.com';
	$website_videos[5] = 'euractiv.com';
	$website_videos[6] = 'viddler.com';
	$website_videos[7] = 'twitch.tv';
	$website_videos[8] = 'toys.com';
	
	$website_videos = apply_filters('wpemfullcontent_websites_video', $website_videos);
	$websites_reg_ex = '';
	foreach ($website_videos as $k => $wv) {
		$website_videos[$k] = str_replace('.', '\.', $wv);
		$website_videos[$k] = str_replace('-', '\-', $website_videos[$k]);
	}
	$websites_reg_ex = '('.implode('|', $website_videos).')';
	$regexvideo = '!'.$websites_reg_ex.'!i';
	return $regexvideo;
}

/**
* Get Item content FULL or feed and first PRO parses
* @param   $feed       object    Feed database object
* @param   $campaign   array     Campaign data
* @param   $item       object    SimplePie_Item object
*/
//public static function content(&$current_item, &$campaign, &$item ) {
function wpemfullcontent_content($current_item, $campaign, $feed, $item ){
	if ($campaign['campaign_fullcontent'] || $campaign['campaign_ogimage'] ) {
		trigger_error(sprintf(__('Attempting to get full article %1s','wpematico'),$item->get_title() ),E_USER_NOTICE);
		$newcontent = wpemfullcontent_GetFullContent($current_item, $campaign, $feed, $item);
		if ($campaign['campaign_fullcontent']){
			$val= trim($newcontent);
			if( !empty($val) ) $current_item['content'] = $newcontent; //If not full content persists original feed content.
		}
	} 

	return $current_item;
}

//protected static function GetFullContent(&$campaign, &$feed, &$item) {
$wpematico_full_featured = '';
function wpemfullcontent_set_featured_image($img, $current_item, $campaign, $feed, $item) {
	global $wpematico_full_featured;
	$use_full_featured = true;
	if ($campaign['campaign_ogimage_if_not_in_content'] && full_get_count_images($current_item, $campaign, $feed, $item) > 0) {
		$use_full_featured = false;
	}
	if ($use_full_featured) {
		return $wpematico_full_featured;
	} else {
		$wpematico_full_featured = '';
	}
	return $img;   
}
add_filter( 'wpematico_set_featured_img', 'wpemfullcontent_set_featured_image', 90, 5);
function wpemfullcontent_get_featured_image($img) {
	global $wpematico_full_featured;
	 if (!empty($wpematico_full_featured)) {
		return $wpematico_full_featured;
    }	  
	return $img;   
}
add_filter('wpematico_get_featured_img', 'wpemfullcontent_get_featured_image', 90, 1);




function wpemfullcontent_GetFullContent(&$current_item, &$campaign, &$feed, &$item, $use_author_name = false) {
	global $wpematico_full_featured;
	$wpematico_full_featured = '';
	require_once(__DIR__ . '/content-extractor/ContentExtractor.php');
	require_once(__DIR__ . '/content-extractor/SiteConfig.php');
	//require_once(__DIR__ . '/html5php/HTML5.php');
	if(!class_exists('Readability'))
	require_once(__DIR__ . '/readability/Readability.php');
	if(!class_exists('wpematico_campaign_fetch_functions') ) {
		require_once(WPeMatico::$dir . 'app/campaign_fetch.php');
	}
	
	if( !isset($extractor) || !$extractor ) {
		$customconfigdir = __DIR__ . '/content-extractor/config/custom';
		$customconfigdir = apply_filters('wpematico_fullcontent_folder', $customconfigdir);
		$extractor = new ContentExtractor( $customconfigdir, __DIR__ . '/content-extractor/config/standard');
	}

	if( !isset($current_item['permalink']) or empty($current_item['permalink']) ){
		$permalink = $item->get_permalink();
		$permalink = wpematico_campaign_fetch::getReadUrl( $permalink , $campaign );
	}else{
		// Uses the source permalink obtained at start of the campaign's run.
		$permalink = $current_item['permalink'];
	}

	if(has_filter('wpepro_getfullcontent')) {
		$html = apply_filters('wpepro_getfullcontent', $permalink, $campaign,'');
	}

	if(!$html) return;

	$extract_result = $extractor->process($html, $permalink);
	$readability = $extractor->readability;
	if(!$extract_result) return '';
	$content_block = ($extract_result) ? $extractor->getContent() : null;
	
	$meta_tags = wpematico_getMetaTags($html);
	// Featured image try og:image then twitter:image
	if( $campaign['campaign_ogimage'] && is_array($meta_tags) ) {
		if( isset($meta_tags['og:image']) ){
			$wpematico_full_featured = $meta_tags['og:image'];
			trigger_error(sprintf(__('Set featured image from og:image: %1s','wpematico'), $current_item['featured_image'] ),E_USER_NOTICE);
		}else {
			trigger_error(sprintf(__('Meta tag og:image not found.','wpematico') ),E_USER_WARNING);
			if( isset($meta_tags['twitter:image']) ){
				$wpematico_full_featured = $meta_tags['twitter:image'];
				trigger_error(sprintf(__('Set featured image from twitter:image: %1s','wpematico'), $current_item['featured_image'] ),E_USER_NOTICE);
			}else {
				trigger_error(sprintf(__('Meta tag twitter:image not found.','wpematico') ),E_USER_WARNING);
				if( isset($meta_tags['twitter:image:src']) ){
					$wpematico_full_featured = $meta_tags['twitter:image:src'];
					trigger_error(sprintf(__('Set featured image from twitter:image:src: %1s','wpematico'), $current_item['featured_image'] ),E_USER_NOTICE);
				}else {
					$wpematico_full_featured = '';
					trigger_error(sprintf(__('Meta tag twitter:image:src not found.','wpematico') ),E_USER_WARNING);
				}
			}
		}
		
		//if( !empty($wpematico_full_featured) ){
			// This have priority over the RSS image (this overwrite the other)
			/*add_filter( 'wpematico_set_featured_img', function($img, $current_item, $campaign, $feed, $item) use ( $featured_image ) {
				$use_full_featured = true;
				if ($campaign['campaign_ogimage_if_not_in_content'] && full_get_count_images($current_item, $campaign, $feed, $item) > 0) {
					$use_full_featured = false;
				}
				if ($use_full_featured) {
					return $featured_image;
				}
				return $img;
			   
			},90, 5);  
			
			add_filter( 'wpematico_get_featured_img', function($img) use ( $featured_image ) {
			    if (!empty($featured_image)) {
			    	 return $featured_image;
			    }
			   return $img;
			},90);	
			*/		
		//}
	}
	// el titulo 
	if($extract_result && $campaign['campaign_fulltitle'] && $extractor->getTitle() != '' ){
		$current_item['title'] = $extractor->getTitle();
		trigger_error(sprintf(__('Got title from full article: %1s','wpematico'), $current_item['title'] ),E_USER_NOTICE);
	}
	// set date
	if($campaign['campaign_fulldate'] && $extractor->getDate() ) {
		$itemdate = $extractor->getDate();
		if (($itemdate > $campaign['lastrun']) && $itemdate < current_time('timestamp', 1)) {  
			$current_item['date'] = $itemdate;
			trigger_error(__('Assigning original date from full article to the post.', WPeMatico :: TEXTDOMAIN ),E_USER_NOTICE);
		}else{
			trigger_error(__('Full article date out of range.  Assigning current date to the post.', WPeMatico :: TEXTDOMAIN ) ,E_USER_NOTICE);
		}
	}
	// add authors
	if ($campaign['campaign_fullauthor'] && $authors = $extractor->getAuthors()) {
		//TODO: make sure the list size is reasonable
		foreach ($authors as $author) {
			// TODO: xpath often selects authors from other articles linked from the page. // for now choose first item
			// Checks if username exist.	If not exist create it with the email username@thisdomain and return the ID	
			if (!empty($use_author_name)) {
				$current_item['author'] = $author;
				break;
			}
			$wpuser = sanitize_user($author, true);
			if (empty($wpuser)) {
				$wpuser = 'wpematico'.trim(crc32($author), '-');
			}
			
			$ID = username_exists($wpuser);
			
			if (!$ID && $campaign['campaign_fccreateauthor']){ //add user
				
				
				$userdata = array(
					'user_login'  =>  $wpuser,
					'user_pass'   =>  md5($wpuser.time()),
					'display_name'=>  $author,
					'role'		  => 'author',
				);
				$ID = wp_insert_user($userdata) ;

				//$ID = wp_insert_user( array ('user_login' => $wpuser) ) ;
			}
			
			$current_item['author'] = $ID;
			break;
		}
	}

	if ($campaign['campaign_fullcontent']) {  // Just run if get full content is selected
		// Deal with multi-page articles
		if($extract_result && $campaign['campaign_fullmultipage'] && $extractor->getNextPageUrl() ){
			trigger_error(sprintf(__('Processing multipage.','wpematico')),E_USER_NOTICE);
			$multi_page_urls = array();
			$multi_page_content = array();
			while ($next_page_url = $extractor->getNextPageUrl()) {
				trigger_error(sprintf(__('--Processing next page: %1s','wpematico'), $next_page_url ),E_USER_NOTICE);
				// If we've got URL, resolve against $url
				if ($next_page_url = wpematico_campaign_fetch::getRelativeUrl($permalink, $next_page_url)) {
					// check it's not what we have already!
					if (!in_array($next_page_url, $multi_page_urls)) {
						// it's not, so let's attempt to fetch it
						$multi_page_urls[] = $next_page_url;
						$html = apply_filters('wpepro_getfullcontent', $next_page_url, $campaign);
						// remove strange things
						$html = str_replace('</[>', '', $html);
						if ($extractor->process($html, $next_page_url)) {
							$multi_page_content[] = $extractor->getContent();
							continue;
						} else { 
							trigger_error(sprintf(__('--Failed to extract content','wpematico') ),E_USER_WARNING);
						}
					} else { 
						trigger_error(sprintf(__('--URL already processed: %1s','wpematico'), $next_page_url ),E_USER_WARNING);
					}
				} else { 
					trigger_error(sprintf(__('--Failed to resolve against: %1s','wpematico'), $permalink ),E_USER_WARNING);
				}
				// failed to process next_page_url, so cancel further requests
				$multi_page_content = array();
				break;
			}
			// did we successfully deal with this multi-page article?
			if (empty($multi_page_content)) {
				trigger_error(sprintf(__('--Failed to extract all parts of multi-page article, so not going to include them','wpematico') ),E_USER_WARNING);
				$_page = $readability->dom->createElement('p');
				$_page->innerHTML = '<em>This article appears to continue on subsequent pages which we could not extract</em>';
				$multi_page_content[] = $_page;
			}
			foreach ($multi_page_content as $_page) {
				$_page = $content_block->ownerDocument->importNode($_page, true);
				$content_block->appendChild($_page);
			}
			unset($multi_page_urls, $multi_page_content, $page_mime_info, $next_page_url, $_page);
		}

		$readability->clean($content_block, 'select');
		// remove empty text nodes
		foreach ($content_block->childNodes as $_n) {
			if ($_n->nodeType === XML_TEXT_NODE && trim($_n->textContent) == '') {
				$content_block->removeChild($_n);
			}
		}
		// remove nesting: <div><div><div><p>test</p></div></div></div> = <p>test</p>
		while ($content_block->childNodes->length == 1 && $content_block->firstChild->nodeType === XML_ELEMENT_NODE) {
			// only follow these tag names
			if (!in_array(strtolower($content_block->tagName), array('div', 'article', 'section', 'header', 'footer'))) break;
			//$html = $content_block->firstChild->innerHTML; // FTR 2.9.5
			$content_block = $content_block->firstChild;
		}
		// convert content block to HTML string
		// Need to preserve things like body: //img[@id='feature']
		if (in_array(strtolower($content_block->tagName), array('div', 'article', 'section', 'header', 'footer', 'li', 'td'))) {
			$html = $content_block->innerHTML;
		//} elseif (in_array(strtolower($content_block->tagName), array('td', 'li'))) {
		//	$html = '<div>'.$content_block->innerHTML.'</div>';
		} else {
			$html = $content_block->ownerDocument->saveXML($content_block); // essentially outerHTML
		}

		//unset($content_block);
		// post-processing cleanup
		$html = preg_replace('!<p>[\s\h\v]*</p>!u', '', $html);

		$html = apply_filters('full_html_content', $html);	
	}
	do_action('after_full_html_content', $html, $current_item, $campaign, $feed, $item);

   return $html;
}

/** 
 * Get all metatags from the page content
 * http://php.net/manual/es/function.get-meta-tags.php#117766
 */
function wpematico_getMetaTags($str) {
  $pattern = '
  ~<\s*meta\s

  # using lookahead to capture type to $1
    (?=[^>]*?
    \b(?:name|property|http-equiv)\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  )

  # capture content to $2
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>

  ~ix';
 
  if(preg_match_all($pattern, $str, $out))
    return array_combine($out[1], $out[2]);
  return array();
}

function full_get_count_images($current_item, $campaign, $feed, $item ) {
	$images = wpematico_campaign_fetch::parseImages($current_item['content']);
	$urls = $images[2]; 
	
	// removes all NULL, FALSE and Empty Strings but leaves 0 (zero) values
	$images =  array_values( array_filter( $urls , 'strlen' ) );
	return sizeof($images);
}

function full_img1s($current_item, $campaign, $feed, $item) {
	$insert_adove = true;
	if (!$campaign['campaign_ogimage_above_content']) {
		return $current_item;
	}
	if (class_exists('WPeMaticoPRO')) {
		if (isset($campaign['add1stimg'])) {
			if ($campaign['add1stimg']) {
				$insert_adove = false;
			}
		}
	}
	if ($campaign['campaign_ogimage_if_not_in_content'] && full_get_count_images($current_item, $campaign, $feed, $item) > 0) {
		$insert_adove = false;
	}
	if ($insert_adove) {  // veo si tengo que agregar img primero en el content
		if(!empty($current_item['featured_image'])) {
			$imgstr = "<img class=\"wpe_imgrss\" src=\"" . $current_item['featured_image'] . "\">";  //Solo la imagen
			$imgstr .= $current_item['content'];
			$current_item['content'] = $imgstr;
		}
	}
	return $current_item;
}

Function get_excerpt_from_fullcontent($html){
	// add content
	if ($options->summary === true) {
		// get summary
		$summary = '';
		if (!$do_content_extraction) {
			$summary = $html;
		} else {
			// Try to get first few paragraphs
			if (isset($content_block) && ($content_block instanceof DOMElement)) {
				$_paras = $content_block->getElementsByTagName('p');
				foreach ($_paras as $_para) {
					$summary .= preg_replace("/[\n\r\t ]+/", ' ', $_para->textContent).' ';
					if (strlen($summary) > 200) break;
				}
			} else {
				$summary = $html;
			}
		}
		unset($_paras, $_para);
		$summary = get_excerpt($summary);
		$newitem->setDescription($summary);
		if ($options->content) $newitem->setElement('content:encoded', $html);
	} else {
		if ($options->content) $newitem->setDescription($html);
	}
	
}	