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

class Resource_single extends TwitterResource 
{
    const URL_SCRIPT = 'statuses/show.json';
    const CACHE_SLUG = 'single';
    
    public function init()
    {
        $this->settings['count'] = 1; // Ignore any count set by the user
    }
    
    public function get_cached_data() 
    {
        $cache_data = self::$cache->data();
        return $cache_data[self::CACHE_SLUG][$this->settings['id']];
    }

    public function is_in_cache() 
    {
        $cache_data = self::$cache->data();
        return (isset($cache_data[self::CACHE_SLUG][$this->settings['id']]));
    }

    public function update_cache_data($tweets) 
    {
        $cache_data = self::$cache->data();
        $cache_data[self::CACHE_SLUG][$this->settings['id']] = $tweets;
        self::$cache->update($cache_data);
    }

    public function build_argument_list( array $args = array() ) 
    {
        // Build the argument list
        if ( isset($this->settings['retweets']) ) $args['include_my_retweet'] = $this->settings['retweets'] ? 'true' : 'false';
        $args['id'] = $this->settings['id'];
        return parent::build_argument_list($args);
    } 
    
    public function filter_response($resp) 
    {
        return array($resp);
    }    
}