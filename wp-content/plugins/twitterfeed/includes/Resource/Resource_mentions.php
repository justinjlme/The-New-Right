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

class Resource_mentions extends TwitterResource 
{
    const URL_SCRIPT = 'statuses/mentions_timeline.json';
    const CACHE_SLUG = 'mentionstimeline';
    
    public function init() {}
    
    public function get_cached_data() 
    {
        $cache_data = self::$cache->data();
        return $cache_data[self::CACHE_SLUG];
    }

    public function is_in_cache() 
    {
        $cache_data = self::$cache->data();
        return (isset($cache_data[self::CACHE_SLUG]) && count($cache_data[self::CACHE_SLUG]) >= $this->settings['count']);
    }

    public function update_cache_data($tweets) 
    {
        $cache_data = self::$cache->data();
        $cache_data[self::CACHE_SLUG] = $tweets;
        self::$cache->update($cache_data);
    }

    public function build_argument_list( array $args = array() ) 
    {
        // Build the argument list
        if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
        if ( isset($this->settings['retweets']) ) $args['include_rts'] = $this->settings['retweets'] ? 'true' : 'false';
        $args['exclude_replies'] = 'false'; // Must include replies for mentions
        return parent::build_argument_list($args);
    }   
    
    public function filter_response($resp) 
    {
        return $resp;
    }    
}