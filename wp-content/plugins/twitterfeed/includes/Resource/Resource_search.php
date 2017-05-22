<?php
/**
 * @package    twitterfeed
 * @date       Mon Mar 06 2017 12:36:25
 * @version    2.1.11
 * @author     Askupa Software <hello@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2017 Askupa Software
 */

namespace TwitterFeed\Resource;

class Resource_search extends TwitterResource 
{    
    const URL_SCRIPT = 'search/tweets.json';
    const CACHE_SLUG = 'search';
    
    private $query;
    
    public function init() 
    {
        $this->query = $this->settings['query'];
    }
    
    public function get_cached_data() 
    {
        $cache_data = self::$cache->data();
        return $cache_data[self::CACHE_SLUG][$this->query_to_slug( $this->query )];
    }

    public function is_in_cache() 
    {
        $cache_data = self::$cache->data();
        return (isset($cache_data[self::CACHE_SLUG][$this->query_to_slug( $this->query )]) && count($cache_data[self::CACHE_SLUG][$this->query_to_slug( $this->query )]) >= $this->settings['count']);
    }

    public function update_cache_data( $tweets ) 
    {
        $cache_data = self::$cache->data();
        $cache_data[self::CACHE_SLUG][$this->query_to_slug( $this->query )] = $tweets;
        self::$cache->update($cache_data);
    }

    public function build_argument_list( array $args = array() ) 
    {
        // Build the argument list
        if( $this->settings['count'] ) $args['count'] = $this->settings['count'];
        // include retweets/replies is not applicable for this resource.
        // It is being handled below in filter_response()
        $args['q'] = urlencode($this->settings['query']);
        return parent::build_argument_list($args);
    }
    
    // Take only the "statuses" part of the response
    public function filter_response( $resp ) 
    {
        if(isset($this->settings['retweets']) && false == $this->settings['retweets']) $this->remove_retweets( $resp );
        if(isset($this->settings['replies']) && false == $this->settings['replies']) $this->remove_replies( $resp );
        return $resp->statuses;
    }
    
    public function query_to_slug( $query )
    {
        $query = preg_replace('/[ ]+/', '_', $query); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $query); // Removes special chars.
    }
    
    public function remove_retweets( $resp )
    {
        foreach( $resp->statuses as $i => $status )
        {
            if(isset($status->retweeted_status))
            {
                unset($resp->statuses[$i]); // (It is safe to remove elements from an array while iterating over it in PHP)
            }
        }
    }
    
    public function remove_replies( $resp )
    {
        foreach( $resp->statuses as $i => $status )
        {
            if(null != $status->in_reply_to_status_id)
            {
                unset($resp->statuses[$i]);
            }
        }
    }
}