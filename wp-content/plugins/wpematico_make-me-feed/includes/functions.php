<?php
/**
 * Helper Functions
 *
 * @package     WPeMatico\Make Me Feed\Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

function get_make_me_feed_meta($post_id){
	$meta = array();
	$meta = get_post_meta($post_id);
	foreach($meta as $key => $value) {
		$meta[$key] = get_post_meta($post_id, $key, true);
		if(substr($key,0,1)=="_" ) unset($meta[$key]);
	}
	return $meta;
}

    /**
     * Get absolute path
     * @param $relative relative url
     * @param $baseUrl base url (domain)
     * @return absolute url version of relative url
     */
    function rel2abs($relative, $baseUrl){
        $schemes = array('http', 'https', 'ftp');
        foreach($schemes as $scheme){
            if(strpos($relative, "{$scheme}://") === 0) //if not relative
                return $relative;
        }
        
        $urlInfo = parse_url($baseUrl);
        
        $basepath = @$urlInfo['path'];
        $basepathComponent = explode('/', $basepath);
        $resultPath = $basepathComponent;
        $relativeComponent = explode('/', $relative);
        $last = array_pop($relativeComponent);
        foreach($relativeComponent as $com){
            if($com === ''){
                $resultPath = array('');
            } else if ($com == '.'){
                $cur = array_pop($resultPath);
                if($cur === ''){
                    array_push($resultPath, $cur);
                } else {
                    array_push($resultPath, '');
                }
            } else if ($com == '..'){
                if(count($resultPath) > 1)
                    array_pop($resultPath);
                array_pop($resultPath);
                array_push($resultPath, '');
            } else {
                if(count($resultPath) > 1)
                    array_pop($resultPath);
                array_push($resultPath, $com);
                array_push($resultPath, '');
            }
        }
        array_pop($resultPath);
        array_push($resultPath, $last);
        $resultPathReal =  '/' . ltrim( implode('/', $resultPath) , '/\\' );
        return $urlInfo['scheme'] . '://' . $urlInfo['host'] . $resultPathReal;
    }
