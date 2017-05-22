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

class Resource_list extends TwitterResource 
{    
    const URL_SCRIPT = 'lists/statuses.json';
    const CACHE_SLUG = 'list';
    
    private $list;
    private $user;
    
    public function init() 
    {
        $this->list = $this->settings['list'];
        $this->user = $this->settings['user'];
    }
    
    public function get_cached_data() 
    {
        $cache_data = self::$cache->data();
        return $cache_data[self::CACHE_SLUG][$this->user][$this->list];
    }

    public function is_in_cache() 
    {
        $cache_data = self::$cache->data();
        $list_data = @$cache_data[self::CACHE_SLUG][$this->user][$this->list];
        return (isset($list_data) && count($list_data) >= $this->settings['count']);
    }

    public function update_cache_data($tweets) 
    {
        $cache_data = self::$cache->data();
        $cache_data[self::CACHE_SLUG][$this->user][$this->list] = $tweets;
        self::$cache->update($cache_data);
    }

    public function build_argument_list(  array $args = array()  ) 
    {
        // Build the argument list
        if ( $this->settings['count'] ) $args['count'] = $this->settings['count'];
        if ( isset($this->settings['replies']) ) $args['exclude_replies'] = $this->settings['replies'] ? 'false' : 'true';
        if ( isset($this->settings['retweets']) ) $args['include_rts'] = $this->settings['retweets'] ? 'true' : 'false';
        $args['owner_screen_name'] = $this->settings['user'];
        $args['slug'] = $this->friendly_url($this->settings['list']);
        return parent::build_argument_list($args);
    } 
    
    public function filter_response($resp) 
    {
        return $resp;
    }    
}